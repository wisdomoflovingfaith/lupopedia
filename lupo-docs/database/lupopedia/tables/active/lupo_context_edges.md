---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_context_edges.md
  web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_context_edges
  last_modified_utc: '20260324'
  channel_id: 42
  actor_id: 108
  actor_name: junie
  delegation_chain: junie:root
  artifact_type: table_documentation
  artifact_kind: table
  purpose: Documentation for lupo_context_edges table - agent cognitive context links
  tags:
  - database
  - table
  - context
  - edges
  - 4.0.86
  when_updated: '20260324174654'
lupopedia.footer:
  last_verified: '20260324000000'
  last_verified_by: cursor
  orchestrator: junie:root
  last_verified_by_actor_id: 102
---
# Table: lupo_context_edges

Purpose: Stores AI/agent cognitive context links.
Status: active

## 1. Overview
- **Scope:** `lupo_context_edges` stores ONLY AI/agent cognitive context links — references within an agent's reasoning chain, context window boundary markers, and inference dependency edges.
- **System Role:** Specialized graph for agent reasoning and context management.
- **Mandate:** It is NOT for general channel-to-channel or thread-to-thread relationships. Use `lupo_edges` for all inter-entity relationship storage.

## 2. Schema

### Fields
| Column | Type | Description |
|---|---|---|
| edge_id | bigint NOT NULL | Primary key |
| source_type | varchar(64) NOT NULL | Type of source object |
| source_id | bigint NOT NULL | ID of source object |
| target_type | varchar(64) NOT NULL | Type of target object |
| target_id | bigint NOT NULL | ID of target object |
| edge_type | varchar(64) NOT NULL | Type of reasoning/context link |
| metadata_json | text | Additional context metadata (JSON) |
| created_ymdhis | bigint NOT NULL | Creation timestamp |
| updated_ymdhis | bigint NOT NULL | Update timestamp |
| is_deleted | tinyint DEFAULT 0 | Soft delete flag |
| deleted_ymdhis | bigint DEFAULT 0 | Soft delete timestamp |

### Indexes
- `idx_source` (source_type, source_id)
- `idx_target` (target_type, target_id)
- `idx_type` (edge_type)
- `idx_created` (created_ymdhis)

## 3. Usage Patterns
Used by the Agent subsystem to track how pieces of information were retrieved or used in an inference step.

## 4. Doctrine Compliance
- **No Foreign Keys:** Explicitly prohibited.
- **Timestamps:** Uses BIGINT (YYYYMMDDHHIISS UTC).
- **Soft Deletes:** Implemented via `is_deleted` and `deleted_ymdhis`.