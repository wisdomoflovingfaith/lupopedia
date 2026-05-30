---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: database/lupopedia/mysql/migrations/README.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/mysql/migrations/README.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: null
  artifact_kind: null
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: null
  prd_cluster: null
  title: null
  summary: null
---
# Lupopedia MySQL migrations (pre-4.1.0)

**As of v4.0.74**, one one-time migration file exists for the 12-table install expansion only.

## Pre-4.1.0 doctrine

- **There is no Lupopedia → Lupopedia upgrade path** before v4.1.0.
- All schema required for a fresh Lupopedia 4.0.x install is in:
  - **Install:** `database/lupopedia/mysql/install/install_new_lupopedia.sql`
  - **Optional/future features:** `database/lupopedia/mysql/install/future_features_lupopedia.sql`
- **Supported setup paths** until 4.1.0:
  1. **Fresh install** — Run install SQL, then seed. (Install already includes the 12 added tables.)
  2. **Upgrade from Crafty Syntax 3.7.5** — Run import/upgrade from original Crafty 3.7.5 schema.
  3. **Existing Lupopedia install missing the 12-table batch** — Run once: `migration_20260314_12_table_install_expansion_v4_0_74.sql` (adds the 12 tables with `CREATE TABLE IF NOT EXISTS`). Do not use for fresh installs.

Migration replay is not used for 4.0.x. The only migration file present is a one-time exception for adding the approved 12 tables to existing installs; any new schema change must be made in the canonical install (or future_features) SQL.

## From 4.1.0 onward

When Lupopedia-to-Lupopedia upgrade and auto-installers are introduced, this directory may again contain incremental migration scripts. Until then, it remains empty except for this README.
