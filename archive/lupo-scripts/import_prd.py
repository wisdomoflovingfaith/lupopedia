#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "lupo-scripts/import_prd.py"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/import_prd.py"
#   status: "complete"
#   when_updated: "20260415224017"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/import-prd.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/import-prd"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: null
#   content_slug: "import-prd"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "import_prd.py — PRD batch wrapper for import_content.py"
#   summary: "Validates lupo-docs/prd/*.md as artifact_type prd with content_parent_id null; runs import_content.py per file with --dry-run / --write-back / --append-history."
# ---------------------------------------------------------------------
"""
import_prd.py — thin wrapper around import_content.py for normative PRD Markdown.

Enforces before import:
  - file exists and ends with .md
  - YAML front matter contains lupopedia.headers.artifact_type == prd
  - lupopedia.headers.content_parent_id is null (PRDs have no parent)

Does not replace import_content.py; delegates DB work and header write-back to it.
"""

from __future__ import annotations

import argparse
import re
import subprocess
import sys
from pathlib import Path
from typing import Any, Dict, List, Optional, Tuple

try:
    import yaml
except ModuleNotFoundError:
    print("ERROR: PyYAML is required (same as import_content.py).", file=sys.stderr)
    raise


def _repo_root() -> Path:
    return Path(__file__).resolve().parent.parent


def _import_content_script() -> Path:
    return Path(__file__).resolve().parent / "import_content.py"


def _extract_yaml_front_matter_block(text: str) -> Optional[str]:
    """
    Return YAML text between first-line --- and the next line that is only --- (trimmed).
    Uses splitlines() so \\r\\n / \\r / \\n are handled without manual normalization bugs.
    """
    lines = text.splitlines()
    if not lines:
        return None
    if lines[0].strip() != "---":
        return None
    end_idx = None
    for i in range(1, len(lines)):
        if lines[i].strip() == "---":
            end_idx = i
            break
    if end_idx is None:
        return None
    inner = lines[1:end_idx]
    return "\n".join(inner)


def _parse_lupopedia_headers(path: Path) -> Optional[Dict[str, Any]]:
    """Return the lupopedia.headers mapping from the first YAML front matter block, or None."""
    text = path.read_text(encoding="utf-8", errors="replace")
    block = _extract_yaml_front_matter_block(text)
    if block is None:
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


# PRD 16 normative: content_slug is lowercase words with hyphens (e.g. 16-lupopedia-headers).
_SLUG_WARN_RE = re.compile(r"^[a-z0-9]+(?:-[a-z0-9]+)*$")


def _maybe_warn_prd_content_slug(hdr: Dict[str, Any], path: Path) -> None:
    raw = hdr.get("content_slug")
    if raw is None:
        return
    s = str(raw).strip()
    if not s:
        return
    if _SLUG_WARN_RE.fullmatch(s):
        return
    print(
        "WARNING: %s: content_slug %r should be lowercase hyphenated (e.g. 16-lupopedia-headers)"
        % (path, s),
        file=sys.stderr,
    )


def _validate_prd_header(path: Path) -> Tuple[bool, str]:
    """
    PRD-only gate: artifact_type prd and content_parent_id null.
    """
    if not path.is_file():
        return False, "not a file"
    if path.suffix.lower() != ".md":
        return False, "expected .md"
    hdr = _parse_lupopedia_headers(path)
    if hdr is None:
        return False, "could not parse YAML front matter / lupopedia.headers"
    at = str(hdr.get("artifact_type", "")).strip()
    if at != "prd":
        return False, "artifact_type must be prd (got %r)" % (at,)
    cp = hdr.get("content_parent_id")
    if cp is not None and str(cp).strip() != "" and str(cp).strip().lower() not in ("null", "none"):
        return False, "content_parent_id must be null for PRD files (got %r)" % (cp,)
    _maybe_warn_prd_content_slug(hdr, path)
    return True, ""


def _collect_prd_paths(repo: Path, paths: List[str], use_all: bool) -> List[Path]:
    out: List[Path] = []
    if use_all:
        prd_dir = repo / "lupo-docs" / "prd"
        for p in sorted(prd_dir.glob("*.md")):
            if p.is_file():
                out.append(p)
        return out
    for raw in paths:
        p = Path(raw)
        if not p.is_absolute():
            p = (repo / p).resolve()
        out.append(p)
    return out


def _run_import_content(
    repo: Path,
    md_path: Path,
    dry_run: bool,
    write_back: bool,
    append_history: bool,
) -> int:
    cmd = [sys.executable, str(_import_content_script()), str(md_path)]
    if dry_run:
        cmd.append("--dry-run")
    if write_back:
        cmd.append("--write-back")
    if append_history:
        cmd.append("--append-history")
    return int(subprocess.call(cmd, cwd=str(repo)))


def main() -> int:
    parser = argparse.ArgumentParser(
        description="Import PRD Markdown into lupo_contents via import_content.py (PRD-only validation)."
    )
    parser.add_argument(
        "paths",
        nargs="*",
        default=[],
        help="One or more PRD .md paths (repo-relative or absolute). Ignored if --all.",
    )
    parser.add_argument(
        "--all",
        action="store_true",
        help="Import every *.md in lupo-docs/prd/ (root only, not subfolders).",
    )
    parser.add_argument("--dry-run", action="store_true", help="Forward to import_content.py")
    parser.add_argument("--write-back", action="store_true", help="Forward to import_content.py")
    parser.add_argument("--append-history", action="store_true", help="Forward to import_content.py")
    args = parser.parse_args()

    repo = _repo_root()
    if args.all and args.paths:
        print("ERROR: use either --all or explicit paths, not both.", file=sys.stderr)
        return 2
    if not args.all and not args.paths:
        print("ERROR: specify one or more paths or use --all.", file=sys.stderr)
        return 2

    targets = _collect_prd_paths(repo, args.paths, args.all)
    if not targets:
        print("ERROR: no matching PRD files.", file=sys.stderr)
        return 2

    ic = _import_content_script()
    if not ic.is_file():
        print("ERROR: missing %s" % (ic,), file=sys.stderr)
        return 2

    rc = 0
    for md_path in targets:
        ok, reason = _validate_prd_header(md_path)
        if not ok:
            print("SKIP (not a normative PRD header): %s — %s" % (md_path, reason), file=sys.stderr)
            rc = 1
            continue
        print("Importing: %s" % (md_path,))
        sub = _run_import_content(repo, md_path, args.dry_run, args.write_back, args.append_history)
        if sub != 0:
            rc = sub
    return rc


if __name__ == "__main__":
    sys.exit(main())
