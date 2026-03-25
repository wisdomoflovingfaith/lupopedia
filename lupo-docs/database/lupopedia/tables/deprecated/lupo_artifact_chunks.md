---
lupopedia.headers:
  lupopedia.schema: database_table
  version_when_written: "4.0.87"
  file_path_from_root: lupo-docs/database/lupopedia/tables/deprecated/lupo_artifact_chunks
  web_path: http://www.lupopedia.com/database/lupopedia/tables/deprecated/lupo_artifact_chunks
  last_modified_utc: "20260325_103500"
  channel_id: null
  thread_id: null
  actor_id: 105
  actor_name: windsurf
  artifact_type: deprecation_notice
  artifact_kind: table_deprecation
  purpose: Deprecation notice for lupo_artifact_chunks table - replaced by channel-based file storage
  references:
    - lupo-docs/database/lupopedia/tables/deprecated/lupo_artifacts.md
    - lupo-channels/semantic-edges/README.md
  artifact_kind: table
  namespace: content
  purpose: JetBrains domain table documentation for lupo_artifact_chunks
  tags:
  - database
  - table
  - content
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of edges for lupo_artifact_chunks table doc at 4.0.79 (grounded
    by repo search; non-exhaustive).
  meta: php_hits=1 python_hits=0
  outbound_edges:
  - to: database.table.lupo_artifact_chunks
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: lupo-includes/schema-config.php
    type: USED_IN_PHP
    weight: 0.9
  - to: (no_python_refs_found)
    type: USED_IN_PYTHON
    weight: 0.0
lupopedia.footer:
  last_verified: '20260317000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_artifact_chunks ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_artifact_chunks
# Table: lupo_artifact_chunks

## Table Overview
- purpose: Chunked payload segments for artifact bodies.
- category: active
- status: active (present in current TOON and install schema)
- version introduced: not explicitly documented in TOON/install comments
- version deprecated: not applicable
- removal notes: not applicable
- migration references: none found in migration docs scanned

## Column Documentation
| Column | Type | Nullability | Default | Description |
|---|---|---|---|---|
| artifact_chunk_id | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| artifact_id | bigint | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| chunk_index | int | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| chunk_content | mediumtext | NOT NULL | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| token_count | int | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| metadata | json | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |
| created_ymdhis | bigint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| updated_ymdhis | bigint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| is_deleted | tinyint | NOT NULL | 0 | TOON-defined field; canonical semantic description not specified in TOON. |
| deleted_ymdhis | bigint | Nullable/unspecified | none/unspecified | TOON-defined field; canonical semantic description not specified in TOON. |

## Relationships
- foreign keys: none (database doctrine forbids foreign keys)
- inbound references: no canonical inbound reference list found in TOON
- outbound references: No foreign keys or explicit relationships in TOON (`relationships: []`).
- join patterns: Join by `artifact_id` to `lupo_artifacts`.

## Usage Notes
- migration notes: TOON and install schema are aligned for this table name.
- compatibility notes: current schema uses BIGINT timestamp doctrine and soft-delete patterns where present.
- warnings: avoid assuming implicit constraints; use doctrine that logic is application-side.
- future considerations: if additional relationships are introduced, document via TOON updates first.
- historical changes if updating existing docs: existing flat documentation was retained; this file is the category-structured canonical doc for this domain pass.
