---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_artifacts.md"
  last_modified_utc: "20260312"
  system_version: "4.0.69"
  channel_id: 1
  actor_id: 103
  delegation_chain: "103:10000"
  artifact_type: "documentation"
  artifact_kind: "database_table"
  purpose: "JetBrains domain table documentation for lupo_artifacts"
  lupo_agent: "jetbrains"
---

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