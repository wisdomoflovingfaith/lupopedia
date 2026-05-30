---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/deprecated/lupo_artifacts.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/deprecated/lupo_artifacts.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: deprecation_notice
  artifact_kind: table_deprecation
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: null
  prd_cluster: null
  title: ''
  summary: ''
---
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
  - to: database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: api/v1/artifact.php
    type: USED_IN_PHP
    weight: 0.6
  - to: api/v1/timeline.php
    type: USED_IN_PHP
    weight: 0.6
  - to: scripts/import_channels_and_artifacts.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: scripts/wolfie_orms.py
    type: USED_IN_PYTHON
    weight: 0.5
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: '20260317000000'
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

