---
lupopedia.headers:
  file_path_from_root: "lupo-database/lupopedia/mysql/migrations/README.md"
  system_version: "4.0.73"
  last_modified_utc: "20260313"
  purpose: "Pre-4.1.0 migration doctrine: no replay; install SQL is canonical."
lupopedia.footer:
  version: "4.0.73"
  last_verified: "20260313"
  orchestrator: "cursor"
---
# Lupopedia MySQL migrations (pre-4.1.0)

**As of v4.0.73 (consolidation release), this directory contains no migration files.**

## Pre-4.1.0 doctrine

- **There is no Lupopedia → Lupopedia upgrade path** before v4.1.0.
- All schema required for a fresh Lupopedia 4.0.x install is in:
  - **Install:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
  - **Optional/future features:** `lupo-database/lupopedia/mysql/install/future_features_lupopedia.sql`
- **Supported setup paths** until 4.1.0:
  1. **Fresh install** — Run install SQL, then seed.
  2. **Upgrade from Crafty Syntax 3.7.5** — Run import/upgrade from original Crafty 3.7.5 schema.

Migration replay is not used for 4.0.x. Do not add incremental migration files here for pre-4.1.0; any schema change must be made in the canonical install (or future_features) SQL.

## From 4.1.0 onward

When Lupopedia-to-Lupopedia upgrade and auto-installers are introduced, this directory may again contain incremental migration scripts. Until then, it remains empty except for this README.
