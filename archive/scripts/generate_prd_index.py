#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.4"
#   file_path_from_root: "scripts/generate_prd_index.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/generate_prd_index.py"
#   status: "complete"
#   when_updated: "20260422160000"   # ← will be updated on next run
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/generate-prd-index.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/generate-prd-index"
#   artifact_type: "implementation"
#   artifact_kind: "tool"
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: 29
#   default_collection_id: null
#   lupopedia.schema: "implementation"
#   prd_cluster: "00_A_16_C_29_A"
#   title: "Regenerate PRD_INDEX.md from docs/prd"
#   summary: "Scans PRD markdown files and generates PRD_INDEX.md with correct 22-field canonical header per PRD 16."
# ---------------------------------------------------------------------

from __future__ import annotations

import argparse
import os
import re
import sys
from datetime import datetime, timezone
from pathlib import Path
from typing import Any, Optional

try:
    import yaml
except ImportError:
    sys.stderr.write("PyYAML required: pip install pyyaml\n")
    sys.exit(1)


def _project_root() -> Path:
    env = os.environ.get("LUPOPEDIA_ROOT", "").strip()
    if env:
        return Path(env).resolve()
    return Path(__file__).resolve().parent.parent


PROJECT_ROOT = _project_root()
PRD_DIR = PROJECT_ROOT / "docs" / "prd"
INDEX_FILE = PRD_DIR / "PRD_INDEX.md"

SKIP_FILES = frozenset({"PRD_INDEX.md", "README.md", "WHAT_TO_DO_NEXT.md", "PRD_AGENT_DEFINITION_MODEL.md"})
SKIP_FILES_LOWER = frozenset(name.lower() for name in SKIP_FILES)

HEADER_FORMAT_VERSION = "4.1.6"

# --- Unified PRD filename regex ---
PRD_NAME_RE = re.compile(
    r"^(\d{2})_([A-F])(?:-([ivxlcdm]+))?_(.+)\.md$",
    re.IGNORECASE,
)

def parse_prd_filename(filename: str) -> dict:
    m = PRD_NAME_RE.match(filename)
    if not m:
        return {
            "group_number": 999,
            "letter": "Z",
            "roman": None,
            "title_slug": filename,
            "is_legacy": True,
        }
    return {
        "group_number": int(m.group(1)),
        "letter": m.group(2),
        "roman": m.group(3),
        "title_slug": m.group(4),
        "is_legacy": m.group(3) is None,
    }

# --- Robust Roman numeral parser ---
ROMAN_VALUES = {
    'i': 1, 'v': 5, 'x': 10,
    'l': 50, 'c': 100, 'd': 500, 'm': 1000
}

def roman_to_int(roman: Optional[str]) -> int:
    if not roman:
        return 9999
    roman = roman.lower()
    total = 0
    prev = 0
    for ch in reversed(roman):
        val = ROMAN_VALUES.get(ch, 0)
        if val < prev:
            total -= val
        else:
            total += val
        prev = val
    return total


def now_utc() -> str:
    return datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")


def split_frontmatter(text: str) -> tuple[Optional[str], str]:
    if not text.startswith("---"):
        return None, text
    lines = text.splitlines(keepends=True)
    for i in range(1, min(len(lines), 300)):
        if lines[i].strip() == "---":
            return "".join(lines[1:i]), "".join(lines[i + 1:])
    return None, text


def parse_headers(text: str) -> dict[str, Any]:
    ym, _ = split_frontmatter(text)
    if not ym:
        return {}
    try:
        data = yaml.safe_load(ym)
        if isinstance(data, dict):
            return data.get("lupopedia.headers") or {}
    except Exception:
        pass
    return {}


def extract_title(text: str, headers: dict) -> str:
    title = str(headers.get("title", "")).strip()
    if title:
        return title
    _, body = split_frontmatter(text)
    for line in body.splitlines():
        line = line.strip()
        if line.startswith("# ") and not line.startswith("## "):
            return line[2:].strip()
    return ""


def scan_prds() -> list[dict]:
    prds = []
    for md_file in sorted(PRD_DIR.glob("*.md")):
        if md_file.name.lower() in SKIP_FILES_LOWER:
            continue

        text = md_file.read_text(encoding="utf-8")
        headers = parse_headers(text)
        meta = parse_prd_filename(md_file.name)

        title = extract_title(text, headers) or md_file.stem.replace("_", " ").title()
        artifact_kind = str(headers.get("artifact_kind", "specification"))

        prds.append({
            "filename": md_file.name,
            "group_number": meta["group_number"],
            "letter": meta["letter"],
            "roman": meta["roman"],
            "title_slug": meta["title_slug"],
            "is_legacy": meta["is_legacy"],
            "title": title,
            "artifact_kind": artifact_kind,
        })

    # Sort: group_number, letter, roman (chronology), legacy last, artifact_kind, filename
    return sorted(
        prds,
        key=lambda p: (
            p["group_number"],
            p["letter"],
            roman_to_int(p["roman"]),
            p["is_legacy"],
            p["artifact_kind"] != "specification",
            p["filename"].lower(),
        ),
    )


def render_index(prds: list[dict], ts: str) -> str:
    frontmatter = f"""---
lupopedia.headers:
  header_format_version: "{HEADER_FORMAT_VERSION}"
  file_path_from_root: "docs/prd/PRD_INDEX.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/PRD_INDEX.md"
  status: "active"
  when_updated: "{ts}"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/prd-index.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/prd_files/prd_index"
  artifact_type: "prd"
  artifact_kind: "guide"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "prd"
  prd_cluster: "00_A-i_16_C-i"
  title: "PRD Index - Master Document Index"
  summary: "Canonical grouped index of all PRD files with proper 22-field headers. PRD numbers are group identifiers."
---
"""

    body = [
        "# PRD Index",
        f"*Auto-generated by `scripts/generate_prd_index.py` -- {ts}*",
        "*DRAFT -- Do NOT mark FINAL*",
        "",
        "## All PRDs",
        "| PRD | Sig | Roman | File | Kind | Title |",
        "|-----|-----|-------|------|------|-------|",
    ]

    for prd in prds:
        num = f"{prd['group_number']:02d}"
        sig = prd["letter"]
        roman = prd["roman"] if prd["roman"] else ("legacy" if prd["is_legacy"] else "")
        kind = prd["artifact_kind"]
        title = prd["title"].replace("|", "\\|")
        body.append(f"| {num} | {sig} | {roman} | `{prd['filename']}` | {kind} | {title} |")

    body.append("")
    body.append(f"*{len(prds)} PRDs indexed*")

    return frontmatter + "\n".join(body) + "\n"


def main() -> None:
    parser = argparse.ArgumentParser(description="Generate PRD_INDEX.md with correct 22-field header")
    parser.add_argument("--dry-run", action="store_true")
    args = parser.parse_args()

    ts = now_utc()
    prds = scan_prds()
    content = render_index(prds, ts)

    if args.dry_run:
        print(content)
        return

    INDEX_FILE.write_text(content, encoding="utf-8")
    print(f"[OK] Written: {INDEX_FILE} ({len(prds)} PRDs indexed)")


if __name__ == "__main__":
    main()