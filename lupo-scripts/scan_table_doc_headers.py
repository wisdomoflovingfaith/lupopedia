#!/usr/bin/env python3
"""
Scan table documentation under lupo-docs/database/lupopedia/tables/ for
LUPOPEDIA_HEADERS version and anomalies. Produces a markdown report for
controlled header/version cleanup. No mass edits; reporting only.

Usage: python lupo-scripts/scan_table_doc_headers.py
  (run from repo root)

Output: lupo-docs/status/table_doc_header_version_report_4_0_78.md
"""
from __future__ import print_function

import os
import re
import sys

# Expected version for 4.0.78 header cleanup
EXPECTED_VERSION = "4.0.78"

# Root directory to scan (relative to script's parent = repo root)
TABLES_DIR = "lupo-docs/database/lupopedia/tables"
REPORT_PATH = "lupo-docs/status/table_doc_header_version_report_4_0_78.md"


def _repo_root():
    script_dir = os.path.abspath(os.path.dirname(__file__))
    return os.path.abspath(os.path.join(script_dir, ".."))


def _norm_version(val):
    """Normalize version string from YAML value (strip quotes, whitespace)."""
    if val is None:
        return None
    s = val.strip().strip('"\'')
    return s if s else None


def _extract_header_fields(block_text):
    """
    From a YAML block (between ---), extract lupopedia.version, system_version,
    file_path_from_root from the lupopedia.headers section (indented lines
    after 'lupopedia.headers:').
    Returns dict with keys: lupopedia.version, system_version, file_path_from_root.
    """
    out = {"lupopedia.version": None, "system_version": None, "file_path_from_root": None}
    in_headers = False
    # Match key: value (value may be quoted or unquoted)
    key_val = re.compile(r"^(\s*)(\S+):\s*(.*)$")
    for line in block_text.splitlines():
        m = key_val.match(line)
        if not m:
            continue
        indent, key, val = m.group(1), m.group(2), m.group(3)
        if key == "lupopedia.headers":
            in_headers = True
            continue
        if in_headers:
            # End of headers section: top-level key (no leading space)
            if indent == "" or (len(indent) < 2 and key.startswith("lupopedia.")):
                break
            if key in out:
                out[key] = _norm_version(val) if val else None
    return out


def _block_has_lupopedia_headers(block_text):
    return "lupopedia.headers" in block_text


def _block_has_legacy_flare(block_text):
    """Legacy FLARE: 'FLARE' or 'flare' in header/metadata context."""
    return "FLARE" in block_text or "flare" in block_text


def _extract_version_from_block(block_text):
    """Get system_version or lupopedia.version from first headers section in block."""
    fields = _extract_header_fields(block_text)
    return fields.get("system_version") or fields.get("lupopedia.version")


def scan_file(root_dir, rel_path):
    """
    Scan a single .md file. Returns dict:
      path, current_version, expected_version, has_headers, duplicate_blocks, legacy_flare, file_path_from_root
    """
    full_path = os.path.join(root_dir, rel_path)
    try:
        with open(full_path, "r", encoding="utf-8", errors="replace") as f:
            content = f.read()
    except IOError as e:
        return {
            "path": rel_path,
            "current_version": None,
            "expected_version": EXPECTED_VERSION,
            "has_headers": False,
            "duplicate_blocks": False,
            "legacy_flare": False,
            "file_path_from_root": None,
            "error": str(e),
        }

    blocks = re.split(r"\n---\n", content)
    header_blocks = [b for b in blocks if _block_has_lupopedia_headers(b)]
    duplicate_blocks = len(header_blocks) > 1
    legacy_flare = any(_block_has_legacy_flare(b) for b in blocks)
    has_headers = len(header_blocks) >= 1

    current_version = None
    file_path_from_root = None
    if header_blocks:
        first_header = header_blocks[0]
        current_version = _extract_version_from_block(first_header)
        file_path_from_root = _extract_header_fields(first_header).get("file_path_from_root")

    return {
        "path": rel_path,
        "current_version": current_version,
        "expected_version": EXPECTED_VERSION,
        "has_headers": has_headers,
        "duplicate_blocks": duplicate_blocks,
        "legacy_flare": legacy_flare,
        "file_path_from_root": file_path_from_root,
        "error": None,
    }


def collect_table_docs(root_dir, tables_dir):
    """Yield relative paths of .md files under tables_dir."""
    base = os.path.join(root_dir, tables_dir)
    if not os.path.isdir(base):
        return
    for dirpath, _dirnames, filenames in os.walk(base):
        for name in filenames:
            if name.lower().endswith(".md"):
                full = os.path.join(dirpath, name)
                rel = os.path.relpath(full, root_dir)
                yield rel.replace("\\", "/")


def run_scan(root_dir):
    tables_dir = TABLES_DIR
    results = []
    for rel_path in sorted(collect_table_docs(root_dir, tables_dir)):
        results.append(scan_file(root_dir, rel_path))
    return results


def write_report(results, root_dir, out_path):
    """Write markdown report to out_path."""
    at_version = [r for r in results if r.get("current_version") == EXPECTED_VERSION]
    needs_update = [r for r in results if r.get("current_version") != EXPECTED_VERSION and r.get("has_headers")]
    missing_headers = [r for r in results if not r.get("has_headers")]
    anomalies_dup = [r for r in results if r.get("duplicate_blocks")]
    anomalies_flare = [r for r in results if r.get("legacy_flare")]

    lines = [
        "# Table doc header version report (4.0.78)",
        "",
        "Generated by `lupo-scripts/scan_table_doc_headers.py`. Use for controlled header/version cleanup; do not mass-edit without this report.",
        "",
        "---",
        "",
        "## Summary",
        "",
        "| Metric | Count |",
        "|--------|-------|",
        "| Total table docs scanned | %d |" % len(results),
        "| Docs already at %s | %d |" % (EXPECTED_VERSION, len(at_version)),
        "| Docs requiring version update | %d |" % len(needs_update),
        "| Docs missing LUPOPEDIA_HEADERS | %d |" % len(missing_headers),
        "",
        "---",
        "",
        "## File list",
        "",
        "| File | Current Version | Required Version |",
        "|------|-----------------|-----------------|",
    ]
    for r in results:
        cur = r.get("current_version") or "(none)"
        if not r.get("has_headers"):
            cur = "(no headers)"
        lines.append("| %s | %s | %s |" % (r["path"], cur, r["expected_version"]))
    lines.extend(["", "---", "", "## Header anomalies", ""])
    if anomalies_dup or anomalies_flare or missing_headers:
        if anomalies_dup:
            lines.append("### Duplicate header blocks")
            lines.append("")
            for r in anomalies_dup:
                lines.append("- `%s`" % r["path"])
            lines.append("")
        if anomalies_flare:
            lines.append("### Legacy FLARE references")
            lines.append("")
            for r in anomalies_flare:
                lines.append("- `%s`" % r["path"])
            lines.append("")
        if missing_headers:
            lines.append("### Missing LUPOPEDIA_HEADERS")
            lines.append("")
            for r in missing_headers:
                lines.append("- `%s`" % r["path"])
            lines.append("")
    else:
        lines.append("No anomalies detected (no duplicate blocks, no legacy FLARE, no missing headers in scanned files).")
        lines.append("")

    lines.append("---")
    lines.append("")
    lines.append("*End of report.*")
    lines.append("")

    out_abs = os.path.join(root_dir, out_path)
    out_dir = os.path.dirname(out_abs)
    if not os.path.isdir(out_dir):
        os.makedirs(out_dir)
    with open(out_abs, "w", encoding="utf-8") as f:
        f.write("\n".join(lines))
    return out_abs


def main():
    root_dir = _repo_root()
    os.chdir(root_dir)
    results = run_scan(root_dir)
    out_abs = write_report(results, root_dir, REPORT_PATH)
    print("Scanned %d table docs. Report: %s" % (len(results), out_abs))
    at = len([r for r in results if r.get("current_version") == EXPECTED_VERSION])
    need = len([r for r in results if r.get("current_version") != EXPECTED_VERSION and r.get("has_headers")])
    print("At %s: %d | Require update: %d" % (EXPECTED_VERSION, at, need))
    return 0


if __name__ == "__main__":
    sys.exit(main())
