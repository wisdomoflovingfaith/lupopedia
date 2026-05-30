#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "lupo-scripts/detect_memory_graph_orphans.py"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/detect_memory_graph_orphans.py"
#   status: "complete"
#   when_updated: "20260415080730"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/detect-memory-graph-orphans.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/pattern-6-orphan-detector"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: "pattern-6-orphan-detector"
#   content_id: null
#   pk_id: null
#   pk_slug: "detect-memory-graph-orphans"
#   parent_pk_id: "38"
#   lupopedia.schema: implementation
#   title: "detect_memory_graph_orphans.py — Pattern #6 Orphan Detection Loop"
#   summary: "Pattern #6: header memory_toon vs DB; KAIROS verify_edges_for_file on db ok (isolated/incomplete stderr WARN only; kairos missing/deleted_only exit 1). Mirror drift informational."
# ---------------------------------------------------------------------
"""
detect_memory_graph_orphans.py

**ANUBIS / Breakthrough Registry Pattern #6** — Orphan detection loop.

**What this tool checks (three tracks):**

1. **Header vs DB** — For each scanned Markdown file with a **first-envelope** memory key,
   compares that key to **{prefix}memory_nodes** (soft-delete aware).
2. **KAIROS (graph)** — When DB status is **ok**, runs **verify_edges_for_file** (outgoing edge
   counts and **node_status**). **isolated** / **incomplete** are warnings only (stderr). **missing**
   / **deleted_only** from KAIROS set exit **1** alongside header-level **missing_node** /
   **soft_deleted_only**.
3. **Mirror / export drift (separate signal)** — Lists **active** DB rows whose **memory_toon**
   looks like a **lupo-memory/...** path but the file is **missing** on disk. Per **PRD 38**,
   **DB is authority**; the filesystem mirror is **export** — absence is **reported** for ops
   but does **not** by itself imply a broken graph node.

Aligned with **PRD 38** (DB-first), **PRD 16** headers, and breakthrough registry **§2.9**.

Requires: **pymysql**, **db_config** / **lupopedia-config.php** parity with other **lupo-scripts**.

Usage:
  python lupo-scripts/detect_memory_graph_orphans.py --under lupo-docs/prd --json
  python lupo-scripts/detect_memory_graph_orphans.py --no-db --verbose
  python lupo-scripts/detect_memory_graph_orphans.py

Exit **1** if any scanned file has **missing_node** or **soft_deleted_only** (header track),
or KAIROS **node_status** **missing** / **deleted_only** after an **ok** DB row. **isolated** /
**incomplete** (KAIROS) are warnings only. **active_nodes_missing_mirror_file** is
**informational** — exit **0** if that is the only issue. Exit **0** if fully clean. Exit **2**
for bad path, unsafe table name, missing pymysql when DB requested, config error, or DB connect
failure.
"""

from __future__ import annotations

import argparse
import json
import os
import re
import sys
from datetime import datetime, timezone
from pathlib import Path
from typing import Any, Dict, List, Optional

try:
    import pymysql
    from pymysql.cursors import DictCursor
except ImportError:
    pymysql = None
    DictCursor = None

_SCRIPTS_DIR = Path(__file__).resolve().parent
_REPO_ROOT = _SCRIPTS_DIR.parent
if str(_SCRIPTS_DIR) not in sys.path:
    sys.path.insert(0, str(_SCRIPTS_DIR))

SKIP_DIR_NAMES = {
    ".git",
    "node_modules",
    "vendor",
    "lupo-archive",
    "__pycache__",
    ".cursor",
    ".idea",
}


def _packed_utc_now() -> str:
    """Packed UTC YmdHis for report fields (real clock, doctrine-aligned)."""
    return datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")


def _first_yaml_fence_block(text: str) -> Optional[str]:
    """Return inner text of the first --- ... --- block, or None."""
    t = text.replace("\r\n", "\n").lstrip("\ufeff \t")
    if not t.startswith("---"):
        return None
    lines = t.split("\n")
    if lines[0].strip() != "---":
        return None
    for i in range(1, min(len(lines), 500)):
        if lines[i].strip() == "---":
            return "\n".join(lines[1:i])
    return None


def _extract_memory_key_from_md(text: str) -> Optional[str]:
    """
    memory_toon (v4.1.0) or legacy memory_key from first YAML envelope (Pattern #2 / #4 friendly).
    Prefers memory_toon:; falls back to memory_key: for legacy headers (read-only compat).
    YAML **memory_toon: null** (or ~) → **None** (do not treat as the string 'null').
    """
    block = _first_yaml_fence_block(text)
    if not block:
        return None
    # Try v4.1.0 field name first; fall back to legacy
    field_name = None
    if "memory_toon:" in block:
        field_name = "memory_toon"
    elif "memory_key:" in block:
        field_name = "memory_key"
    if field_name is None:
        return None
    m = re.search(r"(?m)^\s*" + field_name + r":\s*(.+?)\s*$", block)
    if not m:
        return None
    raw = m.group(1).strip()
    if not raw or raw == "~" or raw.lower() == "null":
        return None
    if len(raw) >= 2 and raw[0] == raw[-1] and raw[0] in "\"'":
        inner = raw[1:-1].strip()
        return inner if inner else None
    return raw


def _iter_markdown_files(root: Path) -> List[Path]:
    out: List[Path] = []
    for dirpath, dirnames, filenames in os.walk(root):
        dirnames[:] = [d for d in dirnames if d not in SKIP_DIR_NAMES]
        for fn in filenames:
            if fn.lower().endswith(".md"):
                out.append(Path(dirpath) / fn)
    return sorted(out)


def _validate_sql_identifier_local(name: str) -> str:
    """
    Minimal safe identifier for constructed table name only.
    Allow [A-Za-z0-9_] only — no coupling to other scripts.
    """
    if not name or not re.fullmatch(r"[A-Za-z0-9_]+", name):
        raise ValueError(
            "Unsafe or invalid SQL identifier for memory_nodes table: %r "
            "(after prefix normalization; allowed: ASCII letters, digits, underscore only)"
            % (name,)
        )
    return name


def _safe_table_name(prefix: str) -> str:
    base = (prefix or "lupo_").strip()
    if not base.endswith("_"):
        base = base + "_"
    full = base + "memory_nodes"
    return _validate_sql_identifier_local(full)


def _classify_key(cursor: Any, table: str, memory_key: str) -> str:
    """Return: ok | missing_node | soft_deleted_only"""
    cursor.execute(
        "SELECT is_deleted, COUNT(*) AS c FROM `"
        + table
        + "` WHERE memory_toon = %s GROUP BY is_deleted",
        (memory_key,),
    )
    rows = cursor.fetchall()
    if not rows:
        return "missing_node"
    has_active = False
    has_deleted = False
    for row in rows:
        sid = int(row.get("is_deleted", 0) or 0)
        if sid == 0:
            has_active = True
        else:
            has_deleted = True
    if has_active:
        return "ok"
    if has_deleted:
        return "soft_deleted_only"
    return "missing_node"


def _active_keys_missing_mirror(cursor: Any, table: str, repo: Path) -> List[str]:
    cursor.execute(
        "SELECT DISTINCT memory_toon FROM `"
        + table
        + "` WHERE is_deleted = 0 AND memory_toon LIKE %s",
        ("lupo-memory/%",),
    )
    missing: List[str] = []
    for row in cursor.fetchall():
        mk = (row.get("memory_toon") or "").strip()
        if not mk:
            continue
        p = repo / mk.replace("/", os.sep)
        if not p.is_file():
            missing.append(mk)
    return missing


def main() -> int:
    parser = argparse.ArgumentParser(
        description="Pattern #6 — memory graph orphan detector (header memory_key vs lupo_memory_nodes)."
    )
    parser.add_argument(
        "--under",
        default="",
        help="Restrict scan to this path relative to repo root (default: whole repo).",
    )
    parser.add_argument(
        "--no-db",
        action="store_true",
        help="Do not connect to MySQL; skip DB classification.",
    )
    parser.add_argument("--json", action="store_true", help="Emit JSON report on stdout.")
    parser.add_argument(
        "-v",
        "--verbose",
        action="store_true",
        help="Per-file lines (non-JSON) or include scan_root in JSON.",
    )
    args = parser.parse_args()

    scan_root = _REPO_ROOT
    if args.under:
        scan_root = (_REPO_ROOT / args.under).resolve()
        if not scan_root.is_dir():
            print("Not a directory: %s" % (scan_root,), file=sys.stderr)
            return 2

    packed_utc = _packed_utc_now()
    file_issues: List[Dict[str, Any]] = []
    for fp in _iter_markdown_files(scan_root):
        try:
            raw = fp.read_text(encoding="utf-8", errors="replace")
        except OSError as exc:
            rel_read = str(fp.relative_to(_REPO_ROOT)).replace("\\", "/")
            print(
                "[WARN] Could not read Markdown file %s: %s" % (rel_read, exc),
                file=sys.stderr,
            )
            continue
        mk = _extract_memory_key_from_md(raw)
        if not mk:
            continue
        rel = str(fp.relative_to(_REPO_ROOT)).replace("\\", "/")
        file_issues.append({"file": rel, "memory_key": mk, "db_status": None})
        if args.verbose and not args.json:
            print("[SCAN] %s -> %s" % (rel, mk))

    if args.no_db or pymysql is None:
        if pymysql is None and not args.no_db:
            print(
                "pymysql not installed; use pip install pymysql or --no-db",
                file=sys.stderr,
            )
            return 2
        report = {
            "mode": "files_only",
            "report_generated_ymdhis": packed_utc,
            "scan_root": str(scan_root.relative_to(_REPO_ROOT)).replace("\\", "/")
            if args.under
            else "",
            "files_with_memory_key": len(file_issues),
            "summary": {
                "files_checked": len(file_issues),
                "ok": 0,
                "missing_node": 0,
                "soft_deleted_only": 0,
                "mirror_missing": 0,
            },
            "note": "DB checks skipped; cannot classify soft-delete or missing_node.",
        }
        if args.json:
            print(json.dumps(report, indent=2))
        else:
            print(
                "[INFO] %d Markdown files declare memory_key (DB skipped)."
                % len(file_issues)
            )
            print(
                "[SUMMARY] files_checked=%d ok=0 missing_node=0 soft_deleted_only=0 mirror_missing=0 (DB skipped)"
                % len(file_issues)
            )
        return 0

    from db_config import LupopediaConfigError, get_table_prefix
    from lib.db_connection import get_connection_params

    prefix = get_table_prefix()
    try:
        table = _safe_table_name(prefix)
    except ValueError as exc:
        print("[ERROR] %s" % exc, file=sys.stderr)
        return 2
    try:
        params = dict(get_connection_params())
    except LupopediaConfigError as exc:
        print("[ERROR] %s" % exc, file=sys.stderr)
        return 2
    params["charset"] = "utf8mb4"

    try:
        conn = pymysql.connect(cursorclass=DictCursor, **params)
        conn.ping(reconnect=False)
    except Exception as exc:
        print("[WARN] Database unavailable: %s" % (exc,), file=sys.stderr)
        if args.json:
            print(
                json.dumps(
                    {
                        "error": str(exc),
                        "report_generated_ymdhis": packed_utc,
                        "files_scanned": len(file_issues),
                    }
                )
            )
        return 2

    actionable = False
    mirror_missing: List[str] = []
    try:
        cursor = conn.cursor()
        for item in file_issues:
            st = _classify_key(cursor, table, item["memory_key"])
            item["db_status"] = st
            if args.verbose and not args.json:
                print(
                    "  [db] %s -> %s (%s)"
                    % (item["file"], item["memory_key"], st)
                )
            if st in ("missing_node", "soft_deleted_only"):
                actionable = True

        mirror_missing = _active_keys_missing_mirror(cursor, table, _REPO_ROOT)
        # Mirror absence is export drift, not the same severity as missing_node /
        # soft_deleted_only (DB authority — PRD 38). Do not set actionable for mirror only.

        verify_edges_for_file = None
        try:
            from lib.kairos_edge_verification import verify_edges_for_file as _kairos_vef

            verify_edges_for_file = _kairos_vef
        except ImportError:
            pass

        for item in file_issues:
            if item.get("db_status") != "ok":
                continue
            if verify_edges_for_file is None:
                item["kairos"] = None
                continue
            abs_path = str((_REPO_ROOT / item["file"]).resolve())
            kr = verify_edges_for_file(abs_path)
            item["kairos"] = kr
            ns = kr.get("node_status")
            if ns in ("missing", "deleted_only"):
                actionable = True
            elif ns in ("isolated", "incomplete"):
                print(
                    "  [WARN] %s is %s (outgoing_edges=%s)"
                    % (item["file"], ns, kr.get("outgoing_edges")),
                    file=sys.stderr,
                )
    finally:
        conn.close()

    def _summary_counts() -> Dict[str, int]:
        ok_c = sum(1 for x in file_issues if x.get("db_status") == "ok")
        miss_c = sum(1 for x in file_issues if x.get("db_status") == "missing_node")
        soft_c = sum(1 for x in file_issues if x.get("db_status") == "soft_deleted_only")
        return {
            "files_checked": len(file_issues),
            "ok": ok_c,
            "missing_node": miss_c,
            "soft_deleted_only": soft_c,
            "mirror_missing": len(mirror_missing),
        }

    summary = _summary_counts()

    report = {
        "table": table,
        "report_generated_ymdhis": packed_utc,
        "files_checked": len(file_issues),
        "summary": summary,
        "file_rows": file_issues,
        "active_nodes_missing_mirror_file": mirror_missing,
        "mirror_drift_informational_only": True,
    }
    if args.verbose and args.json:
        report["scan_root"] = str(scan_root.relative_to(_REPO_ROOT)).replace(
            "\\", "/"
        )

    if args.json:
        print(json.dumps(report, indent=2))
        if mirror_missing and not actionable:
            print(
                "[NOTE] mirror_missing is informational; exit 0 unless missing_node, "
                "soft_deleted_only, or KAIROS node_status missing/deleted_only on scanned files.",
                file=sys.stderr,
            )
    else:
        bad = [
            x
            for x in file_issues
            if x["db_status"] in ("missing_node", "soft_deleted_only")
        ]
        kairos_bad = [
            x
            for x in file_issues
            if (x.get("kairos") or {}).get("node_status")
            in ("missing", "deleted_only")
        ]
        print(
            "[INFO] Table `%s` — files with memory_key: %d"
            % (table, len(file_issues))
        )
        for x in bad:
            print("  [%s] %s -> %s" % (x["db_status"], x["file"], x["memory_key"]))
        for x in kairos_bad:
            kr = x.get("kairos") or {}
            print(
                "  [kairos_%s] %s -> %s"
                % (kr.get("node_status"), x["file"], kr.get("summary") or "")
            )
        if mirror_missing:
            print(
                "[INFO] Export/mirror drift — active DB keys with missing filesystem file (%d) "
                "(informational; does not affect exit code):"
                % len(mirror_missing)
            )
            for mk in mirror_missing[:50]:
                print("  mirror_missing: %s" % mk)
            if len(mirror_missing) > 50:
                print("  ... and %d more" % (len(mirror_missing) - 50))
        print(
            "[SUMMARY] files_checked=%d ok=%d missing_node=%d soft_deleted_only=%d mirror_missing=%d"
            % (
                summary["files_checked"],
                summary["ok"],
                summary["missing_node"],
                summary["soft_deleted_only"],
                summary["mirror_missing"],
            )
        )
        if mirror_missing and not actionable:
            print(
                "[NOTE] Exit code 0: mirror_missing is reported only; "
                "exit 1 requires missing_node, soft_deleted_only, or KAIROS missing/deleted_only.",
                file=sys.stderr,
            )

    return 1 if actionable else 0


if __name__ == "__main__":
    raise SystemExit(main())
