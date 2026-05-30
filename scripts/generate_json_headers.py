#!/usr/bin/env python3
# -*- coding: utf-8 -*-
# DRAFT — scripts/generate_json_headers.py
# Companion to generate_prd_shorthands.py.
# Reads PRD .md files and/or .toon memory files, generates:
#   1. JSON header companions -> docs/headers/{stem}.json
#   2. Memory .toon files -> memory/YYYY/MM/M-{slug}-{date}.toon
#
# This script is the "toon-aware" counterpart to generate_prd_shorthands.py.
# It does NOT replace the shorthand generator — it adds the JSON header layer.
#
# Usage:
#   python scripts/generate_json_headers.py --all
#   python scripts/generate_json_headers.py --prd 0
#   python scripts/generate_json_headers.py --toon memory/2026/04/M-example.toon
#   python scripts/generate_json_headers.py --all --force
#   python scripts/generate_json_headers.py --check

from __future__ import annotations

import argparse
import json
import re
import sys
from datetime import datetime, timezone
from pathlib import Path

try:
    import yaml
except ImportError:
    sys.stderr.write("PyYAML required: pip install pyyaml\n")
    sys.exit(1)

PROJECT_ROOT = Path(__file__).resolve().parent.parent
_SCRIPT_DIR = Path(__file__).resolve().parent
if str(_SCRIPT_DIR) not in sys.path:
    sys.path.insert(0, str(_SCRIPT_DIR))
try:
    from utf8_structured_write import prepare_for_filesystem_write
except ImportError as exc:
    sys.stderr.write(
        "generate_json_headers.py: utf8_structured_write.prepare_for_filesystem_write is required "
        "(encoding guard). Import failed: %s\n" % (exc,)
    )
    sys.exit(1)

# Soft warning only (bytes); does not skip write.
_SOFT_OUTPUT_WARN_BYTES = 262144

PRD_DIR = PROJECT_ROOT / "docs" / "prd"
HEADERS_DIR = PROJECT_ROOT / "docs" / "headers"
MEMORY_BASE_DIR = PROJECT_ROOT / "memory"
SCHEMA_VERSION = "header_json_v1"
TOON_SCHEMA_VERSION = "toon_v1"
ACTOR_ID = 116
AGENT_NAME = "Claude Code"

DEFAULT_SKIP = frozenset({"PRD_INDEX.md", "README.md", "WHAT_TO_DO_NEXT.md", "PRD_AGENT_DEFINITION_MODEL.md"})


def _is_yaml_frontmatter_fence_line(line: str) -> bool:
    """
    True only for a Markdown YAML fence at column 0: whole line is --- plus optional trailing whitespace.

    Rejects indented lines (e.g. --- inside a YAML block scalar) that strip() would misread as a fence.
    """
    s = line.rstrip("\r\n")
    return bool(re.match(r"^---\s*$", s))


def _ensure_trailing_newline(text: str) -> str:
    if not text.endswith("\n"):
        return text + "\n"
    return text


def now_utc() -> str:
    return datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")


# ---------------------------------------------------------------------------
# YAML parsing (shared with generate_prd_shorthands.py pattern)


# ---------------------------------------------------------------------------

def split_frontmatter(text: str):
    if not text.startswith("---"):
        return None, text
    lines = text.splitlines()
    if not lines or not _is_yaml_frontmatter_fence_line(lines[0]):
        return None, text
    h1_idx = None
    for i, line in enumerate(lines[1:], 1):
        if re.match(r"^#\s+\S", line):
            h1_idx = i
            break
    for i in range(1, len(lines)):
        if _is_yaml_frontmatter_fence_line(lines[i]):
            return "\n".join(lines[1:i]), "\n".join(lines[i + 1:]).lstrip("\n")
    if h1_idx is not None:
        return "\n".join(lines[1:h1_idx]), "\n".join(lines[h1_idx:])
    return None, text


def parse_full_yaml(md_path: Path) -> dict:
    try:
        text = md_path.read_text(encoding="utf-8")
    except OSError:
        return {}
    ym, _ = split_frontmatter(text)
    if not ym:
        return {}
    try:
        data = yaml.safe_load(ym)
    except yaml.YAMLError as exc:
        print(
            "  [WARN] YAML parse failed for %s: %s" % (md_path.name, exc),
            file=sys.stderr,
        )
        return {}
    return data if isinstance(data, dict) else {}


# ---------------------------------------------------------------------------
# Edge helpers
# ---------------------------------------------------------------------------

def outbound_edges_from_yaml(edges_block: dict) -> list:
    raw = edges_block.get("outbound_edges") or []
    out = []
    for item in raw:
        if not isinstance(item, dict):
            continue
        to = item.get("to")
        if not to:
            continue
        out.append({
            "to": str(to),
            "type": str(item.get("type", "references")),
            "weight": float(item.get("weight", 1.0)),
        })
    return out


def edges_from_toon(toon: dict) -> list:
    """Extract edges from a .toon file, normalized to header companion format."""
    raw = toon.get("edges") or []
    out = []
    for e in raw:
        if not isinstance(e, dict):
            continue
        to = e.get("to")
        if not to:
            continue
        out.append({
            "to": str(to),
            "type": str(e.get("type", "references")),
            "weight": float(e.get("weight", 1.0)),
        })
    return out


# ---------------------------------------------------------------------------
# Slug helpers
# ---------------------------------------------------------------------------

def toon_slug(stem: str) -> str:
    s = re.sub(r"[^a-zA-Z0-9_-]", "-", stem).lower().strip("-")
    return re.sub(r"-{2,}", "-", s)


def memory_path_for(stem: str, ts: str) -> Path:
    date_part = ts[:8]
    year, month = date_part[:4], date_part[4:6]
    slug = toon_slug(stem)
    return MEMORY_BASE_DIR / year / month / f"M-{slug}-{date_part}.toon"


# ---------------------------------------------------------------------------
# Build functions
# ---------------------------------------------------------------------------

def load_v3_metadata(header: dict) -> dict:
    if header.get('header_format_version') == 3:
        memory_key = header.get('memory_key')
        if memory_key:
            toon_path = PROJECT_ROOT / memory_key
            if toon_path.exists():
                with open(toon_path, 'r', encoding='utf-8') as f:
                    return json.load(f)
    return {}

def build_header_companion(md_path: Path, data: dict, ts: str) -> dict:
    headers = data.get("lupopedia.headers") or {}
    
    v3_meta = load_v3_metadata(headers)
    if v3_meta:
        if not data.get("lupopedia.edges") and v3_meta.get("edges"):
            data["lupopedia.edges"] = {"outbound": v3_meta["edges"]}
        if not headers.get("purpose") and v3_meta.get("summary"):
            headers["purpose"] = v3_meta["summary"]
        if not headers.get("tags") and v3_meta.get("tags"):
            headers["tags"] = v3_meta["tags"]

    edges_block = data.get("lupopedia.edges") or {}

    tags = headers.get("tags") or []
    if isinstance(tags, str):
        tags = [tags] if tags.strip() else []
    tags = list(tags)
    status_raw = headers.get("status")
    has_explicit_status = isinstance(status_raw, str) and status_raw.strip() != ""
    has_explicit_tags = len(tags) > 0
    if not has_explicit_tags and not has_explicit_status:
        if "draft" not in tags:
            tags.append("draft")

    stem = md_path.stem
    date_part = ts[:8]
    year, month = date_part[:4], date_part[4:6]
    memory_ref = f"memory/{year}/{month}/M-{toon_slug(stem)}-{date_part}.toon"

    companion = {
        "file_id": md_path.name,
        "file_path": str(md_path.relative_to(PROJECT_ROOT)).replace("\\", "/"),
        "last_updated": ts,
        "memory_ref": memory_ref,
        "edges": {
            "outbound": outbound_edges_from_yaml(edges_block)
        },
        "tags": tags,
        "schema_version": SCHEMA_VERSION,
        "footer": {
            "last_verified": ts,
            "verified_by": {
                "actor_id": ACTOR_ID,
                "agent_name_identity": AGENT_NAME,
            }
        }
    }

    for key in ("prd_id", "prd_slug", "status", "purpose", "thread_id"):
        val = headers.get(key)
        if val is not None:
            companion[key] = val

    return companion


def build_toon_from_prd(md_path: Path, companion: dict, ts: str) -> dict:
    stem = md_path.stem
    date_part = ts[:8]
    toon_id = f"M-{toon_slug(stem)}-{date_part}"

    edges = [{"to": f"FILE:{md_path.name}", "type": "modifies", "weight": 1.0}]
    for e in companion["edges"]["outbound"][:5]:
        edges.append({"to": e["to"], "type": e["type"], "weight": e["weight"]})

    return {
        "id": toon_id,
        "type": "prd_memory",
        "ts": f"{ts}.000",
        "actor_id": ACTOR_ID,
        "summary": f"JSON header companion generated for {md_path.name}",
        "edges": edges,
        "content": {
            "action": "json_header_companion_generated",
            "source": "generate_json_headers.py",
            "outbound_edge_count": len(companion["edges"]["outbound"]),
            "status": "draft",
        },
        "schema_version": TOON_SCHEMA_VERSION,
        "status": "draft",
    }


def build_header_from_toon(toon: dict, toon_path: Path, ts: str) -> dict:
    """Build a header companion from an existing .toon file."""
    toon_id = toon.get("id", toon_path.stem)
    return {
        "file_id": toon_path.name,
        "file_path": str(toon_path.relative_to(PROJECT_ROOT)).replace("\\", "/"),
        "last_updated": ts,
        "memory_ref": str(toon_path.relative_to(PROJECT_ROOT)).replace("\\", "/"),
        "edges": {
            "outbound": edges_from_toon(toon)
        },
        "tags": ["toon", "memory", "draft"],
        "schema_version": SCHEMA_VERSION,
        "toon_id": toon_id,
        "footer": {
            "last_verified": ts,
            "verified_by": {
                "actor_id": ACTOR_ID,
                "agent_name_identity": AGENT_NAME,
            }
        }
    }


# ---------------------------------------------------------------------------
# File I/O
# ---------------------------------------------------------------------------

def write_companion(companion: dict, stem: str, dry_run: bool) -> Path:
    out_path = HEADERS_DIR / f"{stem}.json"
    if dry_run:
        print(f"  [DRY-RUN] Would write: {out_path.relative_to(PROJECT_ROOT)}")
    else:
        HEADERS_DIR.mkdir(parents=True, exist_ok=True)
        try:
            # Align with PHP JSON_UNESCAPED_UNICODE (ensure_ascii=False). Slashes are not escaped by default in Python json.
            raw = json.dumps(companion, indent=2, ensure_ascii=False)
        except (TypeError, ValueError) as exc:
            print(
                "  [ERROR] json.dumps failed for companion %s (object type %s): %s"
                % (out_path.name, type(companion).__name__, exc),
                file=sys.stderr,
            )
            raise RuntimeError("json.dumps failed for %s" % out_path.name) from exc
        if len(raw) > _SOFT_OUTPUT_WARN_BYTES:
            print(
                "  [WARN] Large companion JSON (%d bytes) for %s — continuing"
                % (len(raw), out_path.name),
                file=sys.stderr,
            )
        prep = prepare_for_filesystem_write(raw, str(out_path))
        if not prep.get("ok"):
            raise RuntimeError(
                "UTF-8 prepare failed for %s: %s" % (out_path, prep.get("reason"))
            )
        raw = _ensure_trailing_newline(prep["text"])
        out_path.write_text(raw, encoding="utf-8", newline="\n")
        print(f"  [OK] {out_path.relative_to(PROJECT_ROOT)}")
    return out_path


def write_toon(toon: dict, toon_path: Path, dry_run: bool) -> None:
    if dry_run:
        print(f"  [DRY-RUN] Would write: {toon_path.relative_to(PROJECT_ROOT)}")
    else:
        toon_path.parent.mkdir(parents=True, exist_ok=True)
        try:
            # Same JSON flags as write_companion (PHP JSON_UNESCAPED_UNICODE parity).
            raw = json.dumps(toon, indent=2, ensure_ascii=False)
        except (TypeError, ValueError) as exc:
            print(
                "  [ERROR] json.dumps failed for toon %s (object type %s): %s"
                % (toon_path.name, type(toon).__name__, exc),
                file=sys.stderr,
            )
            raise RuntimeError("json.dumps failed for %s" % toon_path.name) from exc
        if len(raw) > _SOFT_OUTPUT_WARN_BYTES:
            print(
                "  [WARN] Large toon JSON (%d bytes) for %s — continuing"
                % (len(raw), toon_path.name),
                file=sys.stderr,
            )
        prep = prepare_for_filesystem_write(raw, str(toon_path))
        if not prep.get("ok"):
            raise RuntimeError(
                "UTF-8 prepare failed for %s: %s" % (toon_path, prep.get("reason"))
            )
        raw = _ensure_trailing_newline(prep["text"])
        toon_path.write_text(raw, encoding="utf-8", newline="\n")
        print(f"  [OK] {toon_path.relative_to(PROJECT_ROOT)}")


# ---------------------------------------------------------------------------
# Per-file processors
# ---------------------------------------------------------------------------

def process_prd_file(md_path: Path, ts: str, force: bool, dry_run: bool) -> bool:
    out_path = HEADERS_DIR / f"{md_path.stem}.json"
    if out_path.exists() and not force:
        print(f"  [EXISTS] {out_path.name}")
        return False

    data = parse_full_yaml(md_path)
    headers = data.get("lupopedia.headers")
    if not headers:
        print(f"  [SKIP] {md_path.name} — no lupopedia.headers")
        return False

    companion = build_header_companion(md_path, data, ts)
    toon = build_toon_from_prd(md_path, companion, ts)
    toon_path = memory_path_for(md_path.stem, ts)

    write_companion(companion, md_path.stem, dry_run)
    write_toon(toon, toon_path, dry_run)
    return True


def process_toon_file(toon_path: Path, ts: str, force: bool, dry_run: bool) -> bool:
    stem = toon_path.stem
    out_path = HEADERS_DIR / f"{stem}.json"
    if out_path.exists() and not force:
        print(f"  [EXISTS] {out_path.name}")
        return False

    try:
        raw_toon = toon_path.read_text(encoding="utf-8")
    except UnicodeDecodeError as exc:
        print(
            "  [ERROR] UTF-8 decode failed for toon %s: %s" % (toon_path.name, exc),
            file=sys.stderr,
        )
        return False
    except OSError as exc:
        print(f"  [ERROR] Could not read {toon_path.name}: {exc}")
        return False
    try:
        toon = json.loads(raw_toon)
    except json.JSONDecodeError as exc:
        print(f"  [ERROR] JSON parse failed for {toon_path.name}: {exc}")
        return False

    companion = build_header_from_toon(toon, toon_path, ts)
    write_companion(companion, stem, dry_run)
    return True


# ---------------------------------------------------------------------------
# Stale check
# ---------------------------------------------------------------------------

def check_staleness() -> None:
    print("Checking header companion staleness...\n")
    stale = 0
    ok = 0
    missing = 0
    for md_path in sorted(PRD_DIR.glob("*.md")):
        if md_path.name in DEFAULT_SKIP:
            continue
        if not re.match(r"^\d{2}_", md_path.name):
            continue
        companion_path = HEADERS_DIR / f"{md_path.stem}.json"
        if not companion_path.exists():
            print(f"  MISSING: {md_path.name}")
            missing += 1
        else:
            prd_mtime = md_path.stat().st_mtime
            comp_mtime = companion_path.stat().st_mtime
            if prd_mtime > comp_mtime:
                print(f"  STALE: {md_path.name}")
                stale += 1
            else:
                print(f"  OK: {md_path.name}")
                ok += 1
    print(f"\nOK: {ok}, Stale: {stale}, Missing: {missing}")


# ---------------------------------------------------------------------------
# Main
# ---------------------------------------------------------------------------

def main() -> None:
    parser = argparse.ArgumentParser(
        description="[DRAFT] Generate JSON header companions and .toon memory nodes from PRD .md files"
    )
    parser.add_argument("--all", action="store_true", help="Process all PRD .md files")
    parser.add_argument("--prd", type=int, metavar="N", help="Process PRD by number (NN_ prefix)")
    parser.add_argument("--stem", metavar="STEM", help="Process single PRD file by stem (e.g. 00_root_constitutional_system_requirements)")
    parser.add_argument("--toon", metavar="FILE", help="Generate header companion from a .toon file")
    parser.add_argument("--check", action="store_true", help="Check which companions are missing or stale")
    parser.add_argument("--force", action="store_true", help="Overwrite existing files")
    parser.add_argument("--dry-run", action="store_true", help="Show what would be written without writing")
    args = parser.parse_args()

    if not any([args.all, args.prd is not None, args.stem, args.toon, args.check]):
        parser.print_help()
        sys.exit(1)

    if args.check:
        check_staleness()
        return

    ts = now_utc()

    if args.toon:
        p = Path(args.toon)
        if not p.exists():
            sys.exit(f"Toon file not found: {args.toon}")
        print(f"Processing toon: {p.name}")
        process_toon_file(p, ts, args.force, args.dry_run)
        return

    # Collect PRD files
    all_prds = [
        f for f in sorted(PRD_DIR.glob("*.md"))
        if f.name not in DEFAULT_SKIP and re.match(r"^\d{2}_", f.name)
    ]

    files = []
    if args.stem:
        target = PRD_DIR / (args.stem if args.stem.endswith(".md") else args.stem + ".md")
        if not target.exists():
            sys.exit(f"File not found: {target}")
        files = [target]
    elif args.prd is not None:
        prefix = f"{args.prd:02d}_"
        files = [f for f in all_prds if f.name.startswith(prefix)]
        if not files:
            sys.exit(f"No PRD files found with prefix {prefix}")
    elif args.all:
        files = all_prds

    written = 0
    skipped = 0
    for f in files:
        print(f"Processing: {f.name}")
        if process_prd_file(f, ts, args.force, args.dry_run):
            written += 1
        else:
            skipped += 1

    print(f"\nProcessed: {len(files)}, Written: {written}, Skipped/Existing: {skipped}")
    if args.dry_run:
        print("[DRY-RUN] No files were written.")


if __name__ == "__main__":
    main()

