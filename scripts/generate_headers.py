#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "scripts/generate_headers.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/generate_headers.py"
#   status: "deprecated"
#   when_updated: "20260412021730"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/generate-headers-deprecated.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/generate-headers-deprecated"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: "generate-headers-deprecated"
#   content_id: null
#   pk_id: null
#   pk_slug: "generate-headers"
#   parent_pk_id: "16"
#   lupopedia.schema: implementation
#   title: "generate_headers.py (deprecated stub)"
#   summary: "DEPRECATED: Wolfie Header v4.2 DB-to-file generator; obsolete vs PRD 16 file-first doctrine."
# ---------------------------------------------------------------------
"""
generate_headers.py — **DEPRECATED** (2026-04-12).

**Obsolete:** emitted **Wolfie Header v4.2** from **lupo_contents** / **lupo_edges** into files.
That **DB → file** flow contradicts **Lupopedia v4.0.99**: headers for **Type B** system artifacts
are **file-first**; the database receives snapshots via **import_content.py**, not the reverse.

**Also wrong for current tooling:** **mysql.connector**, hardcoded prefix assumptions, v2/v3-era script
header on the archived implementation.

**Use instead:**

1. **scripts/add_lupopedia_header_to_file.py** — bootstrap new files;
2. **scripts/normalize_lupopedia_md_header_25.py** — migrate dense v4.0.99 envelopes;
3. **python scripts/validate_lupopedia_headers_universal.py** — validate;
4. **scripts/import_content.py** — promote file + sync metadata/edges into DB.

**Archived (unsupported):** ``scripts/legacy/generate_headers_archived_20260412.py``

Exit **0** with ``--help`` or ``--legacy-archive-path``. Default invocation exits **3**.
"""

from __future__ import annotations

import argparse
import sys


def main() -> int:
    parser = argparse.ArgumentParser(
        description=(
            "DEPRECATED: Wolfie v4.2 header generator removed from supported tooling. "
            "See module docstring."
        )
    )
    parser.add_argument(
        "--legacy-archive-path",
        action="store_true",
        help="Print path to archived script and exit 0.",
    )
    args = parser.parse_args()

    if args.legacy_archive_path:
        print("scripts/legacy/generate_headers_archived_20260412.py")
        return 0

    sys.stderr.write(
        "[DEPRECATED] generate_headers.py - do not use.\n\n"
        "Reason: Wolfie Header v4.2 output; DB-to-file header generation; mysql.connector; "
        "not PRD 16 v4.0.99.\n\n"
        "Alternatives:\n"
        "  - scripts/add_lupopedia_header_to_file.py\n"
        "  - scripts/normalize_lupopedia_md_header_25.py\n"
        "  - python scripts/validate_lupopedia_headers_universal.py\n"
        "  - scripts/import_content.py (file to DB)\n\n"
        "Archived copy (unsupported): "
        "scripts/legacy/generate_headers_archived_20260412.py\n"
        "  python scripts/generate_headers.py --legacy-archive-path\n\n"
        "See: scripts/legacy/README.md\n"
    )
    return 3


if __name__ == "__main__":
    raise SystemExit(main())
