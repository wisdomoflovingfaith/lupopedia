---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_artifacts.md
  web_path: '[lupo_artifacts](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_artifacts)'
  last_modified_utc: '20260317'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: content
  purpose: JetBrains domain table documentation for lupo_artifacts
  tags:
  - database
  - table
  - content
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of edges for lupo_artifacts table doc at 4.0.79 (grounded by repo
    search; non-exhaustive).
  meta: php_hits=2 python_hits=2
  outbound_edges:
  - to: database.table.lupo_artifacts
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: lupo-api/v1/artifact.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-api/v1/timeline.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-scripts/import_channels_and_artifacts.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/wolfie_orms.py
    type: USED_IN_PYTHON
    weight: 0.5
lupopedia.footer:
  last_verified: '20260317000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_artifacts ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_artifacts
# Table: lupo_artifacts

## Table Overview
- purpose: Artifact storage records for structured content payloads.
- category: active
- status: active (present in current TOON and install schema)
- version introduced: not explicitly documented in TOON/install comments
- version deprecated: not applicable
- removal notes: not applicable
- migration references: none found in migration docs scanned

## Column Documentation
| Column | Type | Nullability | Default | Description |
|---|---|---|---|---|
| artifact_id | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| actor_id | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| federation_node_id | bigint | NOT NULL | 1 | TOON-defined field; canonical semantic description not specified in TOON. |
| utc_timestamp | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| entity_type | varchar(64) | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| content | text | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| metadata | json | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| channel_id | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| artifact_kind | varchar(50) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| file_path_from_root | varchar(500) | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| created_ymdhis | bigint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| updated_ymdhis | bigint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| is_deleted | tinyint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| deleted_ymdhis | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |

## Relationships
- foreign keys: none (database doctrine forbids foreign keys)
- inbound references: no canonical inbound reference list found in TOON
- outbound references: No foreign keys or explicit relationships in TOON (`relationships: []`).
- join patterns: Join by `artifact_id`; common joins: `lupo_artifact_chunks.artifact_id`, actor joins by `actor_id`.

## Usage Notes
- migration notes: TOON and install schema are aligned for this table name.
- compatibility notes: current schema uses BIGINT timestamp doctrine and soft-delete patterns where present.
- warnings: avoid assuming implicit constraints; use doctrine that logic is application-side.
- future considerations: if additional relationships are introduced, document via TOON updates first.
- historical changes if updating existing docs: existing flat documentation was retained; this file is the category-structured canonical doc for this domain pass.
