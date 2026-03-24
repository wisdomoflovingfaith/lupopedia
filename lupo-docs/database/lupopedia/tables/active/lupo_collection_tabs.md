---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_collection_tabs.md
  namespace: content
  channel_id: 1
  actor_id: 1003
  last_modified_utc: '20260313'
  artifact_type: documentation
  artifact_kind: database_table
  purpose: JetBrains domain table documentation for lupo_collection_tabs
  lupo_agent: antigravity
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of files edited during 4.0.73 finalization and initialization
    thread by ANTIGRAVITY IDE Agent. Edges reflect discovered relationships between
    database tables and PHP/Python codebase entities. Values should be verified against
    live database schemas/queries for the most current semantic graph state.
  meta: "Thread: Finalize 4.0.72 \u2192 Push to GitHub \u2192 Initialize 4.0.73 \u2192\
    \ Migrate Tasks \u2192 Validate Upgrade Path"
  outbound_edges:
  - to: lupo-database/lupopedia/toon/lupo_collection_tabs.toon.json
    type: schema_reference
    weight: 1.0
    reason: TOON schema definition
    db_source: lupo_collection_tabs
lupopedia.engagement:
  comment: Snapshot of files edited during 4.0.73 finalization and initialization
    thread by ANTIGRAVITY IDE Agent. Engagement metrics track edit frequency and importance
    of each file in the version transition process.
  meta: "Thread: Finalize 4.0.72 \u2192 Push to GitHub \u2192 Initialize 4.0.73 \u2192\
    \ Migrate Tasks \u2192 Validate Upgrade Path"
  views: 0
lupopedia.footer:
  last_verified: '20260313000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Table: lupo_collection_tabs

## Table Overview
- purpose: Tab-level grouping structure within collections.
- category: active
- status: active (present in current TOON and install schema)
- version introduced: not explicitly documented in TOON/install comments
- version deprecated: not applicable
- removal notes: not applicable
- migration references: MIGRATION_MAPPING_REFERENCE.md, livehelp_qa_migration.md

## Column Documentation
| Column | Type | Nullability | Default | Description |
|---|---|---|---|---|
| collection_tab_id | bigint auto_increment | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| collection_tab_parent_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| collection_id | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| federations_node_id | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| department_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| actor_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| sort_order | int | Nullable/unspecified | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| name | varchar(255) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| slug | varchar(100) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| color | char(6) | Nullable/unspecified | ''4caf50 | TOON-defined field; canonical semantic description not specified in TOON. |
| description | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| is_hidden | tinyint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| visibility_rule | text | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| tab_type | varchar(32) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| created_ymdhis | bigint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| updated_ymdhis | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| is_active | tinyint | NOT NULL | 1 | TOON-defined field; canonical semantic description not specified in TOON. |
| is_deleted | tinyint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| deleted_ymdhis | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |

## Relationships
- foreign keys: none (database doctrine forbids foreign keys)
- inbound references: no canonical inbound reference list found in TOON
- outbound references: No foreign keys or explicit relationships in TOON (`relationships: []`).
- join patterns: Join by `collection_tab_id`; common joins: `lupo_collection_tab_map.collection_tab_id`, `lupo_collection_tab_paths.collection_tab_id`.

## Usage Notes
- migration notes: TOON and install schema are aligned for this table name.
- compatibility notes: current schema uses BIGINT timestamp doctrine and soft-delete patterns where present.
- warnings: avoid assuming implicit constraints; use doctrine that logic is application-side.
- future considerations: if additional relationships are introduced, document via TOON updates first.
- historical changes if updating existing docs: existing flat documentation was retained; this file is the category-structured canonical doc for this domain pass.
