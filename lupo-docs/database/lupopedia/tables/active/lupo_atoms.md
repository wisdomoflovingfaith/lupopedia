---
lupopedia.headers:
  lupopedia.version: "4.0.78"
  lupopedia.schema: "database_table"
  system_version: "4.0.78"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_atoms.md"
  web_path: "[lupo_atoms](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_atoms)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "System-wide atomic configuration and constants storage; global atoms, version, and config values with context scoping"
  traits: ["canonical", "core_system", "configuration", "v4.0.78"]
  tags: ["database", "atoms", "configuration", "constants", "global_settings"]
  table_primary_key: "atom_id"
  doctrine_note: "No database foreign keys; referential integrity enforced in application code. Timestamps BIGINT YYYYMMDD UTC."

lupopedia.edges:
  comment: "Snapshot of edges for lupo_atoms table doc at 4.0.78."
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "config/global_atoms.yaml", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md", type: "references", weight: 0.7 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.7 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_contents.md", type: "references", weight: 0.7 }

lupopedia.footer:
  version: "4.0.78"
  last_verified: "20260316"
  last_verified_by: "cursor"
---
# file: lupo_atoms — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_atoms

# Table: lupo_atoms

## Table Overview

- **Purpose:** Atomic configuration and constants storage for Lupopedia. Each row is an "atom" (named key with optional JSON value, summary, tags) scoped by context_id. Used for global constants, system version, build info, and configuration values; can sync with `config/global_atoms.yaml`. Supports authoritative-source flag and context-specific values (e.g. context_id = 0 for global, channel_id or actor_id for scoped).
- **Category:** Core System / Configuration
- **Status:** Active (in install_new_lupopedia.sql)
- **Version introduced:** 4.0.x

## Where This Table Is Used

- **Global atoms and version:** Version and build information; GLOBAL_CURRENT_LUPOPEDIA_VERSION and related atoms loaded by `lupo-includes/version.php` and config.
- **Configuration:** Centralized key-value configuration; atoms loaded at runtime for feature flags, limits, and system settings.
- **Channel/actor scoping:** context_id scopes atoms to a channel or actor for per-channel or per-actor overrides.
- **Authoritative source:** is_authoritative marks the canonical source for a given atom name in a context; used when merging or resolving config from multiple sources.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| atom_id | bigint | No | — | Primary key. Application-supplied (e.g. timestamp-based or from registry). |
| atom_name | varchar(255) | No | — | Unique atomic name/identifier within context. |
| context_id | bigint | No | — | Context or scope identifier (0 = global; channel_id or actor_id for scoped). |
| is_authoritative | tinyint | No | 0 | Authoritative source flag (0 = no, 1 = yes). |
| value_json | json | Yes | NULL | Atomic value in JSON format. |
| summary | text | Yes | NULL | Human-readable summary of the atom value. |
| tags | varchar(255) | Yes | NULL | Categorization and discovery tags. |
| created_ymd | bigint | No | 0 | Creation date (BIGINT YYYYMMDD UTC). |
| updated_ymd | bigint | No | 0 | Last update date (BIGINT YYYYMMDD UTC). |

## Indexes

- **PRIMARY KEY:** atom_id
- **INDEX:** lupo_atoms_idx_atom_name (atom_name), lupo_atoms_idx_context_id (context_id), lupo_atoms_idx_authoritative (is_authoritative), lupo_atoms_idx_atom_context (atom_name, context_id)

## Relationships

- **Logical references (no DB FKs):** context_id may represent channel_id, actor_id, or 0 for global; enforced in application code.

## Doctrine notes

- No database foreign keys; referential integrity enforced in application code.
- Timestamps are BIGINT YYYYMMDD UTC (created_ymd, updated_ymd).
- Atom names are unique per (atom_name, context_id) by application convention.
