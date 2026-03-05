# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\database\migrations\README.md"
  file_hash: "3246dc82b21cf57f4f7b3ac5e638bb69f83504b12d94cc4597488ab3b4c559a6"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "database\migrations\README.md"
  file_hash: "2cc885d6a5ed05d332f8c92ac096db4400fbd6b9d0e110d30811da0beefe84ad"
  file_path_from_root: "database\migrations\README.md"
  file_hash: "1a892bb063ae4597842bbfac785aac3582205e45b45dba840210dda311a5a04d"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Lupopedia Migration Pipeline (Canonical)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["database", "migrations", "readmemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Lupopedia Migration Pipeline (Canonical)

This directory contains the authoritative migration set used by the **install wizard**: new install, upgrade from Crafty Syntax 3.7.5, and (for dev/testing) reverting to a Crafty Syntax 3.7.5 snapshot. Only these files belong here; one-time or Lupopedia→Lupopedia migrations live in **database/migrations_legacy/**.

## Canonical SQL set (wizard and revert baseline)

Only the following files remain in this folder:

| File | Purpose |
|------|--------|
| **install_new_lupopedia.sql** | Creates all required Lupopedia tables. Run on new install and at upgrade (before seed). |
| **seed_lupopedia.sql** | Seeds unified registry, system channels, actors, Collection 0. Run after install. |
| **import_from_old_crafty_syntax.sql** | Migrates data from livehelp_* into lupo_* (upgrade path only). |
| **drop_old_crafty_syntax_tables.sql** | Drops legacy livehelp_* tables (optional at credentials). |
| **future_features_lupopedia.sql** | Defines non-required tables; not run by wizard. |
| **old_crafty_syntax_3_7_5_start.sql** | Baseline snapshot of Crafty Syntax 3.7.5 schema. Used to bring the system back to a clean Crafty 3.7.5 state for testing (not a Lupopedia rollback). |

All other SQL and report files (one-time migrations, Lupopedia→Lupopedia patches, dev reports) are in **database/migrations_legacy/**.

## Development workflow baseline: `old_crafty_syntax_3_7_5_start.sql`

The file **`old_crafty_syntax_3_7_5_start.sql`** is the canonical starting point for upgrade testing. It contains the exact 34 legacy Crafty Syntax tables. Do not modify it unless explicitly instructed. It is the baseline for importer logic, identity normalization, operator detection, and legacy table dropping. See **docs/doctrine/DEVELOPMENT_WORKFLOW_DOCTRINE.md** and **docs/doctrine/IMPORT_FROM_CRAFTY_TROUBLESHOOTING.md**.

All migrations in this folder are:
- idempotent
- prefix-aware (using @table_prefix from lupopedia-config.php)
- safe on corrupted or partially-upgraded installs
- safe across MySQL 5.0 to 8.x
- generated only after TOON regeneration
- ordered strictly by timestamp

One-time and Lupopedia→Lupopedia migration files have been moved to **database/migrations_legacy/** (e.g. migration_operator_to_actor_channel_roles.sql, migration_drop_lupo_channel_roles.sql, migration_REGISTRY_*.sql, dev_*.sql, grant_captain_admin_channel_role.sql, registry_seed_raw_test.sql, and audit/summary .txt files). Those files are frozen artifacts and are not run by the wizard.

## Migration File Naming

All new migrations must follow this strict format:

Code
YYYYMMDDHHIISS.sql

This ensures:
- lexicographic ordering
- chronological ordering
- predictable execution
- compatibility with installers and agents
- zero ambiguity across 23,000+ legacy installations

Semantic versioning belongs in the canonical changelog, not in filenames.

## Prefix Doctrine

All migrations must use the dynamic table prefix defined in:

Code
lupopedia-config.php

The prefix is injected into the migration runner as:

Code
@table_prefix

Migrations must never hard-code lupo_ or any other literal prefix.

## TOON Doctrine

Before generating or applying any migration:

Run the TOON generator:

Code
python scripts/generate_toon_files.py

Allow it to:
- regenerate all .toon.json files
- remove stale TOONs
- reflect the current live schema

Only after TOON regeneration may new migrations be created.

TOON files are the authoritative schema source, not the database.

## Legacy Table Handling

Legacy Crafty Syntax tables and early unified subsystem tables must be:
- preserved
- renamed with prefix + _old suffix
- never overwritten
- never merged with new Lupopedia tables

Example:

Code
dialog_messages -> lupo_dialog_messages_old

These tables remain available for data extraction during upgrade but are not part of the active schema.

## Universal Upgrade Philosophy

Every migration in this folder must be written to survive:
- missing tables
- corrupted tables
- partially renamed tables
- partially applied upgrades
- shared hosting limitations
- inconsistent collations
- inconsistent engines
- modified Crafty Syntax installs
- plugin-altered schemas

A migration must never fail if a table is missing or malformed.
Instead, it must detect, correct, and continue.