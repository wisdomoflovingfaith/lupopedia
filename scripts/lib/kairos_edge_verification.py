# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "scripts/lib/kairos_edge_verification.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/lib/kairos_edge_verification.py"
#   status: "complete"
#   when_updated: "20260412022326"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/115/04/kairos-edge-verification.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/kairos-edge-verification"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: "kairos-edge-verification"
#   content_id: null
#   pk_id: null
#   pk_slug: "kairos-edge-verification"
#   parent_pk_id: "38"
#   lupopedia.schema: implementation
#   title: "KAIROS edge verification helpers (memory graph)"
#   summary: "KAIROS (115): verify_edges_for_file with node_status (isolated|incomplete|complete|missing|deleted_only); --expected-edge-types; orphan Pattern #6 warnings."
# ---------------------------------------------------------------------
# NOTE — Header memory_toon path …/canonical/115/04/…: the segment **115** is
# **KAIROS** actor_id (registry), not the trust-ladder **year-offset** segment
# (e.g. **1026** ≈ calendar **2026**) used under memory for many artifacts.
# **04** is month. This mirror layout is **intentionally actor-scoped** for facet
# tooling; do not reinterpret **115** as a year without an explicit migration plan.
# ---------------------------------------------------------------------
"""
KAIROS (actor_id **115**) — memory consolidation and edge verification after migration.

**`memory_key` filesystem shape:** The dense header uses
``memory/development/canonical/115/04/kairos-edge-verification.toon`` — **115**
is **KAIROS** ``actor_id``, not the **{year_offset}/{month}** bucket pattern
(e.g. **1026/04** for 2026) common elsewhere under ``memory/``. Both conventions
can coexist; this file documents the **actor-scoped** choice for this tool.

Reference SQL (portable MySQL/MariaDB; table prefix from **lupopedia-config.php**):

1. **Edge counts** for a node resolved by **`memory_toon`** or **`memory_node_id`**.
2. **Stale edges** — active edges whose **to** node is soft-deleted.

Requires **PyYAML**, **pymysql**, and **db_config** / **lupopedia-config.php** (same as **detect_memory_graph_orphans.py**).

CLI (repo root):

  python scripts/lib/kairos_edge_verification.py --test --file docs/prd/16_lupopedia_headers.md
  python scripts/lib/kairos_edge_verification.py --stale-edges --limit 50
"""

from __future__ import annotations

import argparse
import json
import os
import sys

from typing import Any, Dict, List, Optional
from decimal import Decimal

try:
    import pymysql
    from pymysql.cursors import DictCursor
except ImportError:
    pymysql = None  # type: ignore
    DictCursor = None  # type: ignore

try:
    import yaml
except ImportError:
    yaml = None  # type: ignore

_SCRIPTS_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
_REPO_ROOT = os.path.dirname(_SCRIPTS_DIR)
if _SCRIPTS_DIR not in sys.path:
    sys.path.insert(0, _SCRIPTS_DIR)

# Set True when this module imports; normalize script treats as "KAIROS tooling present".
KAIROS_EDGE_VERIFICATION_AVAILABLE = True

SQL_STALE_EDGES_TO_SOFT_DELETED_TARGET = """
SELECT
        me.memory_edge_id,
        me.from_memory_node_id,
        me.to_memory_node_id,
        me.edge_type,
        mn_to.is_deleted AS target_deleted,
        mn_to.deleted_ymdhis
FROM `{edges}` me
JOIN `{nodes}` mn_to ON mn_to.memory_node_id = me.to_memory_node_id
WHERE mn_to.is_deleted = 1
    AND me.is_deleted = 0
ORDER BY me.memory_edge_id
LIMIT %(limit)s
"""


def _safe_nodes_table(prefix: str) -> str:
    from import_content import _safe_sql_identifier

    base = (prefix or "lupo_").strip()
    if not base.endswith("_"):
        base = base + "_"
    return _safe_sql_identifier(base + "memory_nodes")


def _safe_edges_table(prefix: str) -> str:
    from import_content import _safe_sql_identifier

    base = (prefix or "lupo_").strip()
    if not base.endswith("_"):
        base = base + "_"
    return _safe_sql_identifier(base + "memory_edges")


def _first_yaml_fence_block(text: str) -> Optional[str]:
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


def _header_dict_from_markdown(text: str) -> Optional[Dict[str, Any]]:
    if yaml is None:
        return None
    block = _first_yaml_fence_block(text)
    if not block:
        return None
    try:
        data = yaml.safe_load(block)
    except Exception:
        return None
    if not isinstance(data, dict):
        return None
    hdr = data.get("lupopedia.headers")
    if not isinstance(hdr, dict):
        return None
    return hdr


def _parse_content_id(raw: Any) -> Optional[int]:
    if raw is None or raw is False:
        return None
    if isinstance(raw, int):
        return raw
    if isinstance(raw, str):
        s = raw.strip()
        if not s or s.lower() == "null":
            return None
        try:
            return int(s, 10)
        except ValueError:
            return None
    return None


def verify_edges_for_file(
    file_path: str,
    memory_node_id: Optional[int] = None,
    expected_edge_types: Optional[List[str]] = None,
) -> Dict[str, Any]:
    """
    After a Markdown migration, check **lupo_memory_edges** counts for the file's node.

    Resolves node by **memory_node_id** argument, else header **content_id**, else **memory_toon**.
    Returns **issues** (e.g. zero outgoing/incoming) for operator / **needs_review** workflows.

    **Classification (node_status):**

    - **complete** — Active node exists and (if expected_edge_types given) each expected type
      appears on at least one outgoing edge; else any outgoing edge suffices.
    - **isolated** — Active node exists but zero outgoing edges.
    - **incomplete** — Outgoing edges exist but at least one **expected_edge_types** entry is
      missing (case-insensitive match on **edge_type**).
    - **missing** — No matching node row, or tooling/read/DB failure before a row is resolved.
    - **deleted_only** — Row exists but **is_deleted** is set (soft-deleted node).

    Args:
        file_path: Path to the Markdown file.
        memory_node_id: Optional override for **lupo_memory_nodes.memory_node_id**.
        expected_edge_types: Optional list of edge types this artifact should emit outbound
            (e.g. ``["references", "implements"]``). Compared case-insensitively to DB **edge_type**.
    """
    issues: List[str] = []
    exp = expected_edge_types or []
    out: Dict[str, Any] = {
        "file": file_path,
        "memory_key": None,
        "memory_node_id": None,
        "outgoing_edges": None,
        "incoming_edges": None,
        "outgoing_edge_types": [],
        "expected_edge_types": list(exp),
        "node_status": "unknown",
        "issues": issues,
        "summary": "",
    }

    if yaml is None:
        issues.append("pyyaml_missing")
        out["summary"] = "PyYAML required to read header"
        out["node_status"] = "missing"
        return out

    try:
        with open(file_path, "r", encoding="utf-8-sig") as f:
            text = f.read()
    except OSError as exc:
        issues.append("file_read_error")
        out["summary"] = str(exc)
        out["node_status"] = "missing"
        return out

    hdr = _header_dict_from_markdown(text)
    from detect_memory_graph_orphans import _extract_memory_key_from_md

    memory_key = _extract_memory_key_from_md(text)
    out["memory_key"] = memory_key

    resolved_id = memory_node_id
    if resolved_id is None and hdr:
        resolved_id = _parse_content_id(hdr.get("content_id"))

    if pymysql is None or DictCursor is None:
        issues.append("pymysql_missing")
        out["summary"] = "pymysql not installed; pip install pymysql"
        out["node_status"] = "missing"
        return out

    from lib.db_connection import get_connection_params
    from import_content import _load_table_prefix_from_config

    prefix = _load_table_prefix_from_config()
    nodes = _safe_nodes_table(prefix)
    edges = _safe_edges_table(prefix)
    params = get_connection_params()
    params["charset"] = "utf8mb4"

    try:
        conn = pymysql.connect(cursorclass=DictCursor, **params)
        conn.ping(reconnect=False)
    except Exception as exc:
        issues.append("db_unavailable")
        out["summary"] = str(exc)
        out["node_status"] = "missing"
        return out

    try:
        cursor = conn.cursor()

        row = None
        if resolved_id is not None:
            cursor.execute(
                "SELECT memory_node_id, memory_toon, is_deleted FROM `{}` WHERE memory_node_id = %s".format(
                    nodes
                ),
                (int(resolved_id),),
            )
            row = cursor.fetchone()
        elif memory_key:
            cursor.execute(
                "SELECT memory_node_id, memory_toon, is_deleted FROM `{}` WHERE memory_toon = %s".format(
                    nodes
                ),
                (memory_key,),
            )
            row = cursor.fetchone()

        if row is None:
            out["node_status"] = "missing"
            issues.append("no_active_memory_node")
            out["summary"] = "No lupo_memory_nodes row found for header"
            return out

        node_is_deleted = bool(row.get("is_deleted", 0))
        if node_is_deleted:
            out["node_status"] = "deleted_only"
            issues.append("node_soft_deleted")
            out["summary"] = "Memory node exists but is soft-deleted (is_deleted=1)"
            return out

        out["memory_node_id"] = int(row["memory_node_id"])
        out["memory_key"] = row.get("memory_toon")

        cursor.execute(
            """
            SELECT edge_type, COUNT(*) AS cnt
            FROM `{}`
            WHERE from_memory_node_id = %s AND is_deleted = 0
            GROUP BY edge_type
            """.format(edges),
            (out["memory_node_id"],),
        )
        edge_rows = cursor.fetchall()

        out_going = 0
        outgoing_types: List[str] = []
        for edge_row in edge_rows:
            cnt_raw = edge_row.get("cnt")
            if cnt_raw is None:
                cnt_raw = edge_row.get("COUNT(*)")  # type: ignore[assignment]
            if isinstance(cnt_raw, Decimal):
                count = int(cnt_raw)
            elif isinstance(cnt_raw, int):
                count = cnt_raw
            else:
                count = int(cnt_raw or 0)
            edge_type = edge_row.get("edge_type") or "unknown"
            if not isinstance(edge_type, str):
                edge_type = str(edge_type)
            out_going += count
            if count > 0 and edge_type not in outgoing_types:
                outgoing_types.append(edge_type)

        cursor.execute(
            """
            SELECT COUNT(*) AS cnt
            FROM `{}`
            WHERE to_memory_node_id = %s AND is_deleted = 0
            """.format(edges),
            (out["memory_node_id"],),
        )
        in_row = cursor.fetchone()
        in_raw = in_row.get("cnt") if in_row else None
        if in_raw is None and in_row:
            in_raw = in_row.get("COUNT(*)")  # type: ignore[assignment]
        if isinstance(in_raw, Decimal):
            incoming = int(in_raw)
        elif isinstance(in_raw, int):
            incoming = in_raw
        else:
            incoming = int(in_raw or 0) if in_raw is not None else 0

        out["outgoing_edges"] = out_going
        out["incoming_edges"] = incoming
        out["outgoing_edge_types"] = outgoing_types

        types_lower = {t.lower() for t in outgoing_types}
        expected_clean = [t.strip() for t in exp if t and str(t).strip()]
        expected_lower = [e.lower() for e in expected_clean]

        if out_going == 0:
            out["node_status"] = "isolated"
            issues.append("zero_outgoing_edges")
        elif expected_clean:
            missing_types = [e for e in expected_clean if e.lower() not in types_lower]
            if missing_types:
                out["node_status"] = "incomplete"
                issues.append("missing_expected_edge_types: %s" % (missing_types,))
            else:
                out["node_status"] = "complete"
        else:
            out["node_status"] = "complete"

        if incoming == 0 and out_going > 0:
            issues.append("zero_incoming_edges")

        out["summary"] = (
            "memory_node_id=%s outgoing=%s incoming=%s status=%s"
            % (out["memory_node_id"], out_going, incoming, out["node_status"])
        )

    finally:
        conn.close()

    return out


def fetch_stale_edges_to_deleted_targets(limit: int = 500) -> List[Dict[str, Any]]:
    """
    Rows where **me.is_deleted = 0** but **to_memory_node_id** points at a soft-deleted node.
    """
    if pymysql is None or DictCursor is None:
        return []

    from lib.db_connection import get_connection_params
    from import_content import _load_table_prefix_from_config

    prefix = _load_table_prefix_from_config()
    nodes = _safe_nodes_table(prefix)
    edges = _safe_edges_table(prefix)
    params = get_connection_params()
    params["charset"] = "utf8mb4"

    sql = SQL_STALE_EDGES_TO_SOFT_DELETED_TARGET.format(nodes=nodes, edges=edges)
    lim = max(0, int(limit))

    try:
        conn = pymysql.connect(cursorclass=DictCursor, **params)
        conn.ping(reconnect=False)
    except Exception:
        return []

    try:
        cursor = conn.cursor()
        cursor.execute(sql, {"limit": lim})
        return list(cursor.fetchall())
    finally:
        conn.close()


def _resolve_repo_path(file_arg: str) -> str:
    p = file_arg.strip()
    if os.path.isabs(p):
        return os.path.normpath(p)
    return os.path.normpath(os.path.join(_REPO_ROOT, p.replace("/", os.sep)))


def main() -> int:
    parser = argparse.ArgumentParser(
        description="KAIROS (115) — memory edge verification (PRD 38).",
        formatter_class=argparse.RawDescriptionHelpFormatter,
    )
    parser.add_argument(
        "--test",
        action="store_true",
        help="Run verify_edges_for_file on the Markdown file given by --file",
    )
    parser.add_argument(
        "--file",
        metavar="PATH",
        help="Repo-relative or absolute path to a .md file (required with --test)",
    )
    parser.add_argument(
        "--stale-edges",
        action="store_true",
        help="List active edges whose target memory node is soft-deleted",
    )
    parser.add_argument(
        "--limit",
        type=int,
        default=50,
        help="Max rows for --stale-edges (default: 50; use 0 for no LIMIT)",
    )
    parser.add_argument(
        "--json",
        action="store_true",
        help="Emit machine JSON on stdout for --test (default: human-readable)",
    )
    parser.add_argument(
        "--expected-edge-types",
        metavar="TYPES",
        help="Comma-separated expected outgoing edge types (e.g. references,implements)",
    )
    args = parser.parse_args()

    if args.test:
        if not args.file:
            print("[KAIROS] --test requires --file", file=sys.stderr)
            return 2
        path = _resolve_repo_path(args.file)
        if not os.path.isfile(path):
            print("[KAIROS] Not a file: %s" % path, file=sys.stderr)
            return 2
        expected_types = None
        if args.expected_edge_types:
            expected_types = [
                t.strip() for t in args.expected_edge_types.split(",") if t.strip()
            ]
        result = verify_edges_for_file(path, expected_edge_types=expected_types)
        status = str(result.get("node_status") or "unknown")
        if args.json:
            print(json.dumps(result, indent=2, default=str))
        else:
            print("file: %s" % result.get("file"))
            print("memory_key: %r" % (result.get("memory_key"),))
            print("memory_node_id: %r" % (result.get("memory_node_id"),))
            print("outgoing_edges: %r" % (result.get("outgoing_edges"),))
            print("incoming_edges: %r" % (result.get("incoming_edges"),))
            print("outgoing_edge_types: %s" % (result.get("outgoing_edge_types") or [],))
            print("expected_edge_types: %s" % (result.get("expected_edge_types") or [],))
            print("node_status: %s" % status)
            print("summary: %s" % (result.get("summary") or "",))
            issues = result.get("issues") or []
            if issues:
                print("issues: %s" % issues)
            else:
                print("issues: (none)")
        if status in ("isolated", "incomplete"):
            print(
                "[KAIROS] WARNING: node_status=%s (see issues / expected_edge_types)"
                % status,
                file=sys.stderr,
            )
        if status in ("missing", "deleted_only"):
            return 1
        return 0

    if args.stale_edges:
        rows = fetch_stale_edges_to_deleted_targets(limit=args.limit)
        if args.json:
            print(json.dumps(rows, indent=2, default=str))
        else:
            print("[KAIROS] stale edges (target soft-deleted, edge active): %d row(s)" % len(rows))
            for r in rows[:20]:
                print(
                    "  edge_id=%s from=%s to=%s type=%s deleted_ymdhis=%s"
                    % (
                        r.get("memory_edge_id"),
                        r.get("from_memory_node_id"),
                        r.get("to_memory_node_id"),
                        r.get("edge_type"),
                        r.get("deleted_ymdhis"),
                    )
                )
            if len(rows) > 20:
                print("  ... and %d more (raise --limit or use --json)" % (len(rows) - 20))
        return 1 if rows else 0

    parser.print_help()
    return 2


if __name__ == "__main__":
    sys.exit(main())
