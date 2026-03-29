---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_context_snapshots.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_agent_context_snapshots from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_agent_context_snapshots.json"
    type: "references"
    weight: 1.0
    reason: "authoritative TOON JSON source"
lupopedia.footer:
  last_verified: "20260328013000"
  last_verified_by: "hephaestus"
  last_verified_by_actor_id: 23
  generated: true
  provenance: "stage3_track_c_normalization"
---
# file: lupo_agent_context_snapshots.md

# lupo_agent_context_snapshots

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_agent_context_snapshots`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `agent_context_snapshot_id` | `bigint NOT NULL` |
| `session_id` | `varchar(100) NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `parent_snapshot_id` | `bigint` |
| `snapshot_type` | `varchar(64) NOT NULL DEFAULT 'full'` |
| `snapshot_purpose` | `varchar(50)` |
| `context_data` | `text NOT NULL` |
| `context_summary` | `text` |
| `context_metadata` | `json` |
| `token_count` | `int` |
| `character_count` | `int` |
| `compressed_size` | `int` |
| `compression_ratio` | `float` |
| `compression_method` | `varchar(64) DEFAULT 'gzip'` |
| `serialization_time_ms` | `int` |
| `compression_time_ms` | `int` |
| `related_tool_call_id` | `bigint` |
| `conversation_turn` | `int` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `expires_ymdhis` | `bigint` |
| `is_corrupt` | `tinyint DEFAULT 0` |
| `retention_policy` | `varchar(64) DEFAULT 'temporary'` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_agent_context_snapshots_idx_created` | `created_ymdhis` | no |
| `lupo_agent_context_snapshots_idx_parent` | `parent_snapshot_id` | no |
| `lupo_agent_context_snapshots_idx_related_tool` | `related_tool_call_id` | no |
| `lupo_agent_context_snapshots_idx_retention` | `retention_policy`, `expires_ymdhis` | no |
| `lupo_agent_context_snapshots_idx_session_agent` | `session_id`, `actor_id` | no |
| `lupo_agent_context_snapshots_idx_turn` | `session_id`, `conversation_turn` | no |
| `lupo_agent_context_snapshots_idx_type_purpose` | `snapshot_type`, `snapshot_purpose` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
