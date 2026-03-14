---
lupopedia.headers:
  file_path_from_root: "lupo-database/lupopedia/mysql/migrations/README.md"
  system_version: "4.0.74"
  last_modified_utc: "20260313"
  purpose: "Pre-4.1.0 migration doctrine: no replay; install SQL is canonical."
lupopedia.footer:
  version: "4.0.74"
  last_verified: "20260313"
  orchestrator: "cursor"
---
# Lupopedia MySQL migrations (pre-4.1.0)

**As of v4.0.74**, one one-time migration file exists for the 12-table install expansion only.

## Pre-4.1.0 doctrine

- **There is no Lupopedia → Lupopedia upgrade path** before v4.1.0.
- All schema required for a fresh Lupopedia 4.0.x install is in:
  - **Install:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
  - **Optional/future features:** `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`
- **Supported setup paths** until 4.1.0:
  1. **Fresh install** — Run install SQL, then seed. (Install already includes the 12 added tables.)
  2. **Upgrade from Crafty Syntax 3.7.5** — Run import/upgrade from original Crafty 3.7.5 schema.
  3. **Existing Lupopedia install missing the 12-table batch** — Run once: `migration_20260314_12_table_install_expansion_v4_0_74.sql` (adds the 12 tables with `CREATE TABLE IF NOT EXISTS`). Do not use for fresh installs.

Migration replay is not used for 4.0.x. The only migration file present is a one-time exception for adding the approved 12 tables to existing installs; any new schema change must be made in the canonical install (or future_features) SQL.

## From 4.1.0 onward

When Lupopedia-to-Lupopedia upgrade and auto-installers are introduced, this directory may again contain incremental migration scripts. Until then, it remains empty except for this README.
