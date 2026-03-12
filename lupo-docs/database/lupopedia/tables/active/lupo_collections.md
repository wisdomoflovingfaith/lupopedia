---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_collections.md"
  last_modified_utc: "20260312"
  system_version: "4.0.69"
  channel_id: 1
  actor_id: 103
  delegation_chain: "103:10000"
  artifact_type: "documentation"
  artifact_kind: "database_table"
  purpose: "JetBrains domain table documentation for lupo_collections"
  lupo_agent: "jetbrains"
---

# Table: lupo_collections

## Table Overview
- purpose: Collection containers used to organize content and navigation structures.
- category: active
- status: active (present in current TOON and install schema)
- version introduced: not explicitly documented in TOON/install comments
- version deprecated: not applicable
- removal notes: not applicable
- migration references: MIGRATION_MAPPING_REFERENCE.md, livehelp_qa_migration.md

## Column Documentation
| Column | Type | Nullability | Default | Description |
|---|---|---|---|---|
| collection_id | bigint auto_increment | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| federation_node_id | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| actor_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| department_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| name | varchar(255) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| slug | varchar(100) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| color | char(6) | Nullable/unspecified | ''666666 | TOON-defined field; canonical semantic description not specified in TOON. |
| description | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| sort_order | int | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| properties | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| published_ymdhis | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| created_ymdhis | bigint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| updated_ymdhis | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| is_deleted | tinyint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| deleted_ymdhis | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| parent_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| channel_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| is_nav_menu | tinyint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| nav_icon | varchar(64) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |

## Relationships
- foreign keys: none (database doctrine forbids foreign keys)
- inbound references: no canonical inbound reference list found in TOON
- outbound references: No foreign keys or explicit relationships in TOON (`relationships: []`).
- join patterns: Join by `collection_id`; common joins: `lupo_collection_tabs.collection_id`, `lupo_contents.default_collection_id`.

## Usage Notes
- migration notes: TOON and install schema are aligned for this table name.
- compatibility notes: current schema uses BIGINT timestamp doctrine and soft-delete patterns where present.
- warnings: avoid assuming implicit constraints; use doctrine that logic is application-side.
- future considerations: if additional relationships are introduced, document via TOON updates first.
- historical changes if updating existing docs: existing flat documentation was retained; this file is the category-structured canonical doc for this domain pass.