---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_context_snapshots.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Serialized agent context per session/actor (full/delta, retention)"
  mood_rgb: "4169E1"
  traits: ["canonical", "agent", "session", "cursor_domain", "v4.0.70"]
  tags: ["database", "agents", "context", "snapshots"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_agent_context_snapshots.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_sessions.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_agent_tool_calls.md", type: "references", weight: 0.6 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

# Table: lupo_agent_context_snapshots

## Table Overview

- **Purpose:** Stores agent context snapshots per session and actor: context_data (text), context_summary, context_metadata (JSON), optional compression and token counts, parent_snapshot_id, related_tool_call_id, conversation_turn, retention_policy, expires_ymdhis.
- **Category:** Agent / Session
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| agent_context_snapshot_id | bigint | No | — | Primary key. |
| session_id | varchar(100) | No | — | Session (logical → lupo_sessions). |
| actor_id | bigint | No | — | Actor. |
| parent_snapshot_id | bigint | Yes | — | Parent snapshot for delta chains. |
| snapshot_type | varchar(64) | No | 'full' | full or delta. |
| snapshot_purpose | varchar(50) | Yes | — | Purpose code. |
| context_data | text | No | — | Serialized context. |
| context_summary | text | Yes | — | Summary. |
| context_metadata | json | Yes | — | Metadata. |
| token_count | int | Yes | — | Token count. |
| character_count | int | Yes | — | Character count. |
| compressed_size | int | Yes | — | Compressed size. |
| compression_ratio | float | Yes | — | Ratio. |
| compression_method | varchar(64) | Yes | 'gzip' | Method. |
| serialization_time_ms | int | Yes | — | Serialization time. |
| compression_time_ms | int | Yes | — | Compression time. |
| related_tool_call_id | bigint | Yes | — | Related tool call. |
| conversation_turn | int | Yes | — | Turn number. |
| created_ymdhis | bigint | No | 0 | Creation. |
| expires_ymdhis | bigint | Yes | — | Expiry. |
| is_corrupt | tinyint | Yes | 0 | Corrupt flag. |
| retention_policy | varchar(64) | Yes | 'temporary' | Retention policy. |

## Relationships

- **Logical references:** session_id → lupo_sessions; actor_id → lupo_actors; parent_snapshot_id → same table; related_tool_call_id → lupo_agent_tool_calls.
- **Inbound:** Agent context persistence and restore.
- **Join patterns:** By created_ymdhis, parent_snapshot_id, related_tool_call_id, (retention_policy, expires_ymdhis), (session_id, actor_id), (session_id, conversation_turn), (snapshot_type, snapshot_purpose).

## Usage Notes

- **Indexes:** created_ymdhis, parent_snapshot_id, related_tool_call_id, (retention_policy, expires_ymdhis), (session_id, actor_id), (session_id, conversation_turn), (snapshot_type, snapshot_purpose).
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC.
