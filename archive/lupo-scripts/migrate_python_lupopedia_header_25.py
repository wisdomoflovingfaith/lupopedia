#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "lupo-scripts/migrate_python_lupopedia_header_25.py"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/migrate_python_lupopedia_header_25.py"
#   status: "complete"
#   when_updated: "20260411035853"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/migrate-python-lupopedia-header-25.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/migrate-python-lupopedia-header-25"
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
#   title: "DEPRECATED stub — legacy Python header blank-line migrator"
#   summary: "No-op under PRD 16 v4.1.0 dense Python headers"
# ---------------------------------------------------------------------
"""
DEPRECATED (PRD 16 v4.0.99)

This script used to insert two blank lines before the closing ``# -----`` line in Python
headers (legacy 20-key envelope). **v4.0.99** requires a **dense** 22-key block with **no**
internal blank lines before the closing separator.

**Do not run** this migrator for new work. Instead:

- Regenerate headers with ``python lupo-scripts/add_lupopedia_header_to_file.py`` (or batch
  ``add_lupopedia_headers_everywhere.py``), or
- Manually align with ``validate_lupopedia_headers_universal.py`` after editing.

Kept as a no-op stub so old CI/docs references fail soft.

Usage:
  python lupo-scripts/migrate_python_lupopedia_header_25.py
"""

from __future__ import annotations

import sys


def main() -> int:
    sys.stderr.write(
        "[DEPRECATED] migrate_python_lupopedia_header_25.py: no-op under PRD 16 v4.0.99 "
        "(dense 22-key Python headers). See script docstring.\n"
    )
    return 0


if __name__ == "__main__":
    sys.exit(main())
