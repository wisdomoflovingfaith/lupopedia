---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_modules.md"
  last_modified_utc: "20260312"
  system_version: "4.0.69"
  channel_id: 1
  actor_id: 103
  delegation_chain: "103:10000"
  artifact_type: "documentation"
  artifact_kind: "database_table"
  purpose: "JetBrains domain table documentation for lupo_modules"
  lupo_agent: "jetbrains"
---

# Table: lupo_modules

## Table Overview
- purpose: Module registry and configuration records.
- category: active
- status: active (present in current TOON and install schema)
- version introduced: not explicitly documented in TOON/install comments
- version deprecated: not applicable
- removal notes: not applicable
- migration references: MIGRATION_MAPPING_REFERENCE.md, livehelp_config_migration.md, livehelp_modules_dep_migration.md, livehelp_modules_migration.md

## Column Documentation
| Column | Type | Nullability | Default | Description |
|---|---|---|---|---|
| module_id | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| module_key | varchar(100) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| module_name | varchar(150) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| namespace | varchar(100) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| version | varchar(50) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| version_code | int | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| minimum_core_version | varchar(50) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| user_path | varchar(255) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| admin_path | varchar(255) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| api_path | varchar(255) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| route_params | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| description | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| author | varchar(100) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| website | varchar(255) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| icon | varchar(100) | Nullable/unspecified | ''puzzle-piece | TOON-defined field; canonical semantic description not specified in TOON. |
| dependencies | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| conflicts | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| config_json | text | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| is_system | tinyint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| is_active | tinyint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| federation_node_id | bigint | NOT NULL | 1 | TOON-defined field; canonical semantic description not specified in TOON. |
| settings | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| installed_ymdhis | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| created_ymdhis | bigint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| updated_ymdhis | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| is_deleted | tinyint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| deleted_ymdhis | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |

## Relationships
- foreign keys: none (database doctrine forbids foreign keys)
- inbound references: no canonical inbound reference list found in TOON
- outbound references: No foreign keys or explicit relationships in TOON (`relationships: []`).
- join patterns: Join by `module_id` where module-specific policy or UI mappings are maintained.

## Usage Notes
- migration notes: TOON and install schema are aligned for this table name.
- compatibility notes: current schema uses BIGINT timestamp doctrine and soft-delete patterns where present.
- warnings: avoid assuming implicit constraints; use doctrine that logic is application-side.
- future considerations: if additional relationships are introduced, document via TOON updates first.
- historical changes if updating existing docs: existing flat documentation was retained; this file is the category-structured canonical doc for this domain pass.