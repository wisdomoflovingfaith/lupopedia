#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "scripts/migrate_filesystem_to_db.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/migrate_filesystem_to_db.py"
#   status: "deprecated"
#   when_updated: "20260412015258"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/migrate-filesystem-deprecated.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/migrate-filesystem-db-deprecated"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: "migrate-filesystem-db-deprecated"
#   content_id: null
#   pk_id: null
#   pk_slug: "migrate-filesystem-to-db"
#   parent_pk_id: "38"
#   lupopedia.schema: implementation
#   title: "migrate_filesystem_to_db.py (deprecated stub)"
#   summary: "DEPRECATED: legacy agents/channels numeric-dir migrator; lastrowid IDs; obsolete header era. Use import_content.py and validators."
# ---------------------------------------------------------------------
"""
migrate_filesystem_to_db.py — **DEPRECATED** (2026-04-12).

The historical filesystem→DB migrator predates v4.0.99 and violates current doctrine:
obsolete v2/v3 header shape, **cursor.lastrowid** (non-deterministic IDs), legacy **4-digit**
directory layout under **agents/** and **channels/**, **Path(__file__).parent.parent** instead
of **LUPOPEDIA_PATH**, dead **update_file_header_with_content_id**, **datetime.utcnow()** usage.

**Do not use** this entry point for operational migrations.

**Use instead (4.0.99+):**

1. **scripts/import_content.py** — file promotion; deterministic **content_id** (SHA256);
2. **python scripts/validate_lupopedia_headers_universal.py** — header validation;
3. **lib/header_db_sync.sync_header_artifact_to_db** (invoked from **import_content.py**) —
   metadata / edges sync after **lupo_contents** upsert;
4. **KAIROS / memory graph** tooling for edge verification (see **PRD 37**, **38**, **52**).

**Archived implementation (unsupported):**
``scripts/legacy/migrate_filesystem_to_db_archived_20260412.py`` — prints an extra
**stderr** warning when run.

Exit **0** with ``--help`` or ``--legacy-archive-path``. Default invocation exits **3**.
"""

from __future__ import annotations

import argparse
import sys


def main() -> int:
    parser = argparse.ArgumentParser(
        description=(
            "DEPRECATED: Legacy filesystem→DB migration removed from supported tooling. "
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
        print("scripts/legacy/migrate_filesystem_to_db_archived_20260412.py")
        return 0

    sys.stderr.write(
        "[DEPRECATED] migrate_filesystem_to_db.py - do not use.\n\n"
        "Reason: lastrowid IDs, obsolete headers, numeric 4-digit dirs, hardcoded paths, "
        "dead header-update helper, datetime usage.\n\n"
        "Alternatives:\n"
        "  - scripts/import_content.py (deterministic content_id; header sync)\n"
        "  - python scripts/validate_lupopedia_headers_universal.py\n"
        "  - lib/header_db_sync.sync_header_artifact_to_db (via import_content)\n"
        "  - KAIROS / memory graph for edge verification (PRD 37/38/52)\n\n"
        "Archived copy (unsupported): "
        "scripts/legacy/migrate_filesystem_to_db_archived_20260412.py\n"
        "  python scripts/migrate_filesystem_to_db.py --legacy-archive-path\n\n"
        "See: scripts/legacy/README.md\n"
    )
    return 3


if __name__ == "__main__":
    raise SystemExit(main())
