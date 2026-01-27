# Lupopedia Migration Pipeline (Canonical)

This directory contains the authoritative, clean migration sequence for upgrading any Crafty Syntax 3.7.5 installation into the modern Lupopedia Semantic OS.

All migrations in this folder are:
- idempotent
- prefix-aware (using @table_prefix from lupopedia-config.php)
- safe on corrupted or partially-upgraded installs
- safe across MySQL 5.0 to 8.x
- generated only after TOON regeneration
- ordered strictly by timestamp

Legacy migrations from previous eras have been moved to:

Code
/database/migrations_legacy/

Those files are frozen artifacts and must never be modified.

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
unified_dialog_messages -> lupo_unified_dialog_messages_old

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
