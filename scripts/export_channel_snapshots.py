#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "scripts/export_channel_snapshots.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/export_channel_snapshots.py"
#   status: "deprecated"
#   when_updated: "20260412011607"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/export-channel-snapshots-deprecated.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/export-channel-snapshots-deprecated"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: "export-channel-snapshots-deprecated"
#   content_id: null
#   pk_id: null
#   pk_slug: "export-channel-snapshots"
#   parent_pk_id: "38"
#   lupopedia.schema: implementation
#   title: "export_channel_snapshots.py (deprecated stub)"
#   summary: "DEPRECATED: channel filesystem snapshots removed from supported tooling; DB is authority; see legacy/ archive and memory/PHP export paths."
# ---------------------------------------------------------------------
"""
export_channel_snapshots.py — **DEPRECATED** (2026-04-12).

The historical channel snapshot exporter violated current doctrine (obsolete header,
Decimal→float loss, numeric channel_id paths, mirror-as-authority risk). **Do not use**
this entry point for operational exports.

**Source of truth:** **`lupo_*` database tables** (PRD 38). Filesystem JSON under
``channels/<id>/`` from the old script are **read-only mirrors at best**, never
authoritative.

**Use instead:**

1. **Memory graph** — query **`lupo_memory_nodes`** / **`lupo_memory_edges`** (or IDE/PHP
   tools that read them). CLI helper example: **`scripts/export_memory_nodes_116.py`**
   (facet-scoped export; not a full graph dump).
2. **PHP DB export** — **`php bin/export.php`** (**`MemoryExportService`**) for
   canonical node JSON mirrors from **`lupo_memory_nodes`**.
3. **Direct SQL** — parameterized queries against **`lupo_channels`**, **`lupo_dialog_*`**,
   **`lupo_contents`**, etc., via **PDO_DB** (runtime) or **`scripts/db_config`** +
   **pymysql** (tooling), with **`LUPO_TABLE_PREFIX`**.

**Archived implementation (unsupported):**
``scripts/legacy/export_channel_snapshots_archived_20260324.py`` — run only if you
explicitly need the old behavior; it prints an additional stderr warning.

Exit **0** with ``--help``. Default invocation (no args) prints this notice to **stderr**
and exits **3** (deprecated / do not use). **There is no** ``--verify`` or live export on
this path.
"""

from __future__ import annotations

import argparse
import sys


def main() -> int:
    parser = argparse.ArgumentParser(
        description=(
            "DEPRECATED: Channel snapshot export removed. "
            "Use DB, memory graph, or php bin/export.php. See module docstring."
        )
    )
    parser.add_argument(
        "--legacy-archive-path",
        action="store_true",
        help="Print path to archived script and exit 0.",
    )
    args = parser.parse_args()

    if args.legacy_archive_path:
        print("scripts/legacy/export_channel_snapshots_archived_20260324.py")
        return 0

    sys.stderr.write(
        "[DEPRECATED] export_channel_snapshots.py - do not use.\n\n"
        "Reason: obsolete patterns (header, float Decimals, channel_id paths, "
        "non-authoritative mirrors).\n\n"
        "Alternatives:\n"
        "  - lupo_memory_nodes / lupo_memory_edges (queries or export_memory_nodes_116.py)\n"
        "  - php bin/export.php --node-id N or --full\n"
        "  - Parameterized DB access via PDO_DB / db_config + pymysql\n\n"
        "Archived copy (unsupported): "
        "scripts/legacy/export_channel_snapshots_archived_20260324.py\n"
        "  python scripts/export_channel_snapshots.py --legacy-archive-path\n\n"
        "See: scripts/legacy/README.md\n"
    )
    return 3


if __name__ == "__main__":
    raise SystemExit(main())
