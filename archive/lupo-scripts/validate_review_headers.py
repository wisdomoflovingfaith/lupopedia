#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "lupo-scripts/validate_review_headers.py"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/validate_review_headers.py"
#   status: "deprecated"
#   when_updated: "20260412171953"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/validate-review-headers.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/validate-review-headers"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   parent_pk_id: "16"
#   lupopedia.schema: implementation
#   title: "Deprecated shim; delegates header checks to universal PRD 16 validator"
#   summary: "Forwards common CLI flags to validate_lupopedia_headers_universal.py; expires 2026-05-01."
# ---------------------------------------------------------------------
"""
DEPRECATED entry point (historical name ``validate_review_headers.py``).

The previous implementation expected PHP ``/**`` docblocks and ``lupopedia.headers { ... }``,
which is not PRD 16 v4.0.99. **Always** use the universal envelope validator instead:

  python lupo-scripts/validate_lupopedia_headers_universal.py <path> [flags]

This file remains as a **shim** so old docs or muscle memory still run a real check. It forwards
a subset of flags; for anything else (or ``--version``), call the universal validator directly.

**Removal:** This shim is scheduled for removal after **2026-05-01** (UTC calendar date).
"""

from __future__ import annotations

import argparse
import os
import subprocess
import sys
from datetime import date

_SCRIPTS_DIR = os.path.dirname(os.path.abspath(__file__))
_REPO_ROOT = os.path.dirname(_SCRIPTS_DIR)

# Shim expires after this date (inclusive on this day = still allowed).
_SHIM_EXPIRATION = date(2026, 5, 1)


def _append_flag(cmd: list, args, attr: str, flag: str) -> None:
    if getattr(args, attr, False):
        cmd.append(flag)


def main() -> int:
    if date.today() > _SHIM_EXPIRATION:
        sys.stderr.write(
            "[FATAL] validate_review_headers.py shim expired (removed after %s). "
            "Use: python lupo-scripts/validate_lupopedia_headers_universal.py <path> [flags]\n"
            % _SHIM_EXPIRATION.isoformat()
        )
        return 2

    parser = argparse.ArgumentParser(
        description=(
            "DEPRECATED shim: runs validate_lupopedia_headers_universal.py on the given file. "
            "For flags not listed here, invoke the universal validator directly."
        ),
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog=(
            "Forwarded flags: --quiet, --development, --type, --check-db, --check-links, "
            "--strict-memory-pair (--strict), --strict-memory-year, --strict-memory-files, "
            "--reject-legacy-envelope.\n"
            "Example:\n"
            "  python lupo-scripts/validate_review_headers.py lupo-docs/prd/16_lupopedia_headers.md "
            "--quiet --check-links --strict-memory-pair\n"
            "Other options (e.g. --version): use validate_lupopedia_headers_universal.py directly."
        ),
    )
    parser.add_argument("file", help="Path to validate (any supported extension)")
    parser.add_argument(
        "--type",
        choices=("auto", "md", "yaml", "py", "php", "js"),
        default="auto",
        help="Forward --type to the universal validator (default: auto)",
    )
    parser.add_argument(
        "--quiet",
        action="store_true",
        help="Forward --quiet to the universal validator",
    )
    parser.add_argument(
        "--development",
        action="store_true",
        help="Forward --development to the universal validator",
    )
    parser.add_argument(
        "--check-db",
        action="store_true",
        help="Forward --check-db to the universal validator",
    )
    parser.add_argument(
        "--check-links",
        action="store_true",
        help="Forward --check-links to the universal validator",
    )
    parser.add_argument(
        "--strict-memory-pair",
        "--strict",
        dest="strict_memory_pair",
        action="store_true",
        help="Forward --strict-memory-pair (alias: --strict)",
    )
    parser.add_argument(
        "--strict-memory-year",
        action="store_true",
        help="Forward --strict-memory-year to the universal validator",
    )
    parser.add_argument(
        "--strict-memory-files",
        action="store_true",
        help="Forward --strict-memory-files to the universal validator",
    )
    parser.add_argument(
        "--reject-legacy-envelope",
        action="store_true",
        help="Forward --reject-legacy-envelope to the universal validator",
    )
    args = parser.parse_args()

    target = os.path.abspath(args.file)
    if not os.path.isfile(target):
        sys.stderr.write("[ERROR] Not a file: %s\n" % target)
        return 1

    rel = os.path.relpath(target, _REPO_ROOT).replace("\\", "/")
    vpy = os.path.join(_SCRIPTS_DIR, "validate_lupopedia_headers_universal.py")
    if not os.path.isfile(vpy):
        sys.stderr.write(
            "[FATAL] Universal validator not found: %s\n"
            "Expected validate_lupopedia_headers_universal.py next to this script under lupo-scripts/.\n"
            % vpy.replace("\\", "/")
        )
        return 2

    cmd = [sys.executable, vpy, rel]
    if args.type and args.type != "auto":
        cmd.extend(["--type", args.type])
    _append_flag(cmd, args, "quiet", "--quiet")
    _append_flag(cmd, args, "development", "--development")
    _append_flag(cmd, args, "check_db", "--check-db")
    _append_flag(cmd, args, "check_links", "--check-links")
    _append_flag(cmd, args, "strict_memory_pair", "--strict-memory-pair")
    _append_flag(cmd, args, "strict_memory_year", "--strict-memory-year")
    _append_flag(cmd, args, "strict_memory_files", "--strict-memory-files")
    _append_flag(cmd, args, "reject_legacy_envelope", "--reject-legacy-envelope")

    sys.stderr.write(
        "[DEPRECATED] %s is deprecated and will be removed after %s.\n"
        "    Use: python lupo-scripts/validate_lupopedia_headers_universal.py %s"
        % (os.path.basename(__file__), _SHIM_EXPIRATION.isoformat(), rel)
    )
    if len(cmd) > 3:
        sys.stderr.write("\n    Forwarding: %s\n" % " ".join(cmd[3:]))
    sys.stderr.write("\n")

    proc = subprocess.run(cmd, cwd=_REPO_ROOT)
    return int(proc.returncode)


if __name__ == "__main__":
    sys.exit(main())
