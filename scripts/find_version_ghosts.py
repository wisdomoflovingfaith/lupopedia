#!/usr/bin/env python3
"""
Scan docs/doctrine/ and docs/prd/ for 3.0.x contamination and related smells:
  - Semver 3.0.x references in prose (excluding archive paths)
  - Deprecated wolfie.headers blocks
  - delegation_chain: null
  - version_when_written vs body "System Version:" mismatch

Usage:
  python scripts/find_version_ghosts.py
  python scripts/find_version_ghosts.py --out docs/implementations/29_project_structure/status/version_ghosts_report.json
  python scripts/find_version_ghosts.py --require-zero   # exit 1 if any finding
"""
from __future__ import print_function

import argparse
import json
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]

# Scanned by default (4.0.x active docs only; archives excluded)
DEFAULT_REL_ROOTS = ("docs/doctrine", "docs/prd")

# Paths under these prefixes are skipped (expected 3.0.x or historical content)
EXCLUDE_SUBSTRINGS = (
    "docs/versions/3.0.x/",
    "/versions/archive/",
)

THREE_ZERO = re.compile(r"\b3\.0\.\d+\b")
WOLFIE_HEADERS = re.compile(r"^wolfie\.headers\s*:", re.MULTILINE)
NULL_DELEGATION = re.compile(r"delegation_chain:\s*null\b")
VERSION_WHEN = re.compile(
    r'version_when_written:\s*["\']?(\d+\.\d+\.\d+)["\']?', re.IGNORECASE
)
SYSTEM_VERSION_LINE = re.compile(
    r"System Version:\s*(\d+\.\d+\.\d+)", re.IGNORECASE
)
# Legacy top-level paths called out in LILITH audit (not docs)
PHANTOM_DOCS = re.compile(r"(?<![\w/])/(?:docs|database|channels|scripts)/")


def should_skip(rel_posix):
    for ex in EXCLUDE_SUBSTRINGS:
        if ex in rel_posix.replace("\\", "/"):
            return True
    return False


def scan_file(path):
    rel = path.relative_to(ROOT).as_posix()
    if should_skip(rel):
        return None
    try:
        text = path.read_text(encoding="utf-8", errors="replace")
    except Exception as e:
        return {"path": rel, "error": str(e)}

    findings = []

    if THREE_ZERO.search(text):
        findings.append("three_zero_semver_reference")

    if WOLFIE_HEADERS.search(text):
        findings.append("deprecated_wolfie_headers_block")

    if NULL_DELEGATION.search(text):
        findings.append("null_delegation_chain")

    if PHANTOM_DOCS.search(text):
        findings.append("phantom_legacy_path_slash_docs_or_similar")

    vw = VERSION_WHEN.search(text)
    sv = SYSTEM_VERSION_LINE.search(text)
    if vw and sv and vw.group(1) != sv.group(1):
        findings.append(
            "version_mismatch_header_vs_system_version: %s vs %s"
            % (vw.group(1), sv.group(1))
        )

    if not findings:
        return None
    critical = []
    for f in findings:
        if f in (
            "three_zero_semver_reference",
            "deprecated_wolfie_headers_block",
            "phantom_legacy_path_slash_docs_or_similar",
        ):
            critical.append(f)
        elif f.startswith("version_mismatch_header_vs_system_version:"):
            critical.append(f)
    return {"path": rel, "findings": findings, "critical_findings": critical}


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument(
        "--roots",
        default=",".join(DEFAULT_REL_ROOTS),
        help="Comma-separated paths under repo root (default: doctrine + prd)",
    )
    ap.add_argument(
        "--out",
        metavar="PATH",
        help="Write JSON report to this file (default: stdout only)",
    )
    ap.add_argument(
        "--require-zero",
        action="store_true",
        help="Exit 1 if any file has critical findings (3.0.x, wolfie.headers, version mismatch, phantom /docs/ paths)",
    )
    ap.add_argument(
        "--require-zero-all",
        action="store_true",
        help="Exit 1 if any finding including null delegation (strict)",
    )
    args = ap.parse_args()

    roots = [r.strip() for r in args.roots.split(",") if r.strip()]
    results = []
    for r in roots:
        base = ROOT.joinpath(*r.split("/"))
        if not base.is_dir():
            continue
        for path in sorted(base.rglob("*.md")):
            row = scan_file(path)
            if row:
                results.append(row)

    critical_hits = sum(1 for r in results if r.get("critical_findings"))
    report = {
        "scanned_roots": roots,
        "exclude_substrings": list(EXCLUDE_SUBSTRINGS),
        "files_with_findings": len(results),
        "files_with_critical_findings": critical_hits,
        "results": results,
    }

    out_json = json.dumps(report, indent=2)
    if args.out:
        out_path = ROOT / args.out
        out_path.parent.mkdir(parents=True, exist_ok=True)
        out_path.write_text(out_json + "\n", encoding="utf-8", newline="\n")
    print(out_json)

    if args.require_zero_all and results:
        sys.exit(1)
    if args.require_zero:
        if any(r.get("critical_findings") for r in results):
            sys.exit(1)
    sys.exit(0)


if __name__ == "__main__":
    main()
