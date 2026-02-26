---
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
flare.headers:
  file_path_from_root: "docs/doctrine/database/dialog_messages.md"
  system_version: "4.0.47"
  channel_id: 1
  actor_id: 1001
  last_modified_utc: "20260226"
  delegation_chain: "1001:10000"
  artifact_type: "doctrine"
  purpose: "Comprehensive documentation for lupo_dialog_messages table schema and usage"
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "comprehensive"]
  tags: ["dialog", "messages", "table", "schema", "database"]
  lupo_agent: "windsurf"

flare.footer:
  outbound_edges:
    - { to: "docs/toons/lupo_dialog_messages.toon.json", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/database/lupo_dialog_threads.md", type: "references", weight: 0.9 }
    - { to: "docs/doctrine/database/lupo_actors.md", type: "references", weight: 0.8 }
    - { to: "docs/doctrine/database/lupo_channels.md", type: "references", weight: 0.8 }
    - { to: "docs/doctrine/migrations/livehelp_transcripts_migration.md", type: "references", weight: 0.7 }
    - { to: "docs/doctrine/migrations/livehelp_messages_migration.md", type: "references", weight: 0.7 }
    - { to: "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.6 }
  semantic_tags: ["dialog", "messages", "chat", "transcripts", "communication", "messaging"]
---

# lupo_dialog_messages

**Purpose:** **Messages** belonging to a dialog thread. Each row has dialog_thread_id, channel_id, from_actor_id, to_actor_id, message_text, created_ymdhis (and any other columns in the TOON). Order by created_ymdhis ASC for display. Ephemeral livehelp_messages are not imported; durable content comes from livehelp_transcripts.

**Schema:** See `docs/toons/lupo_dialog_messages.toon.json`. Primary key: `dialog_message_id`. Foreign references are application-managed (no FK); actor and channel identity are in lupo_actors and lupo_channels.

**IMPORTANT: dialog_message_id is NOT a traditional auto-increment ID**
- **Format**: `bigint` containing `YYYYMMDDHHMMSS` (UTC timestamp)
- **Insertion Logic**: When inserting, get current UTC date/time, then:
  1. Calculate `current_ymdhis = gmdate('YmdHis')`
  2. `SELECT MAX(dialog_message_id) FROM lupo_dialog_messages`
  3. If `max_id < current_ymdhis`, use `current_ymdhis`
  4. If `max_id >= current_ymdhis`, use `max_id + 1`
- **Purpose**: Creates human-readable, time-ordered primary keys that can be generated without auto-increment
- **Edge Case Handling**: In high-volume scenarios, the ID may drift from real time (e.g., `20260226052899` + 1 = `20260226052900`). This is acceptable - the ID serves as a unique primary key and approximate ordering, while `created_ymdhis` stores the actual creation timestamp
- **Example**: `20260226052800` = 2026-02-26 05:28:00 UTC
- **Note**: All *_ymdhis columns use the same YYYYMMDDHHMMSS bigint format for consistency

**Complete Column Reference:**
| Column Name | Type | Description | Default | Indexed | Notes |
|-------------|------|-------------|---------|---------|-------|
| `dialog_message_id` | bigint | Primary key: Timestamp-based ID | - | YES (PK) | UNIQUE, NOT NULL |
| `message_id` | bigint | Legacy message ID reference | 0 | - | For migration compatibility |
| `dialog_thread_id` | bigint | Thread identifier | - | YES | Links to lupo_dialog_threads (app-managed) |
| `channel_id` | bigint | Channel foreign reference | - | YES | Links to lupo_channels (app-managed) |
| `from_actor_id` | bigint | Sender actor ID | - | YES | Links to lupo_actors (app-managed) |
| `to_actor_id` | bigint | Receiver actor ID | - | YES | Links to lupo_actors (app-managed) |
| `read_by_actor_id` | bigint | Actor who read message | 0 | - | Links to lupo_actors (app-managed) |
| `read_by_actor_utc` | bigint | Read timestamp (YYYYMMDDHHMMSS) | 0 | - | When message was read |
| `message_text` | varchar(1000) | Message content | - | - | Use for short messages (≤1000 chars) |
| `message_type` | varchar(64) | Message type (text, system, etc.) | 'text' | YES | For filtering different message types |
| `metadata_json` | json | Additional metadata | NULL | - | Flexible extra data storage |
| `mood_rgb` | char(6) | Message mood color code | NULL | - | Semantic analysis output |
| `mood_framework` | varchar(32) | Mood analysis framework | 'western_analytical' | - | Analysis method used |
| `created_ymdhis` | bigint | Actual creation timestamp | 0 | YES | Primary ordering column |
| `updated_ymdhis` | bigint | Last update timestamp | - | YES | For change tracking |
| `is_deleted` | tinyint | Soft delete flag | 0 | YES | For filtering active records |
| `deleted_ymdhis` | bigint | Deletion timestamp | NULL | - | When record was soft-deleted |
| `message_body` | mediumtext | Full message body | NULL | - | Use for longer content (>1000 chars) |

**Content Storage Strategy:**
- **message_text**: Use for short messages (≤1000 characters) - indexed for full-text search
- **message_body**: Use for longer content (>1000 characters) - stores full transcript content
- **Migration Pattern**: Legacy transcripts imported as single message with full text in `message_body`

---

## Use and need

- **Channel UI:** Messages for a channel are read by channel_id (and optionally dialog_thread_id). Composer sends new messages with from_actor_id = current actor, to_actor_id and thread/channel as context.
- **Transcript import:** For legacy transcripts, one message per thread is created containing the full transcript text; timestamps from transcript start/end.
- **Ordering:** Messages ordered by created_ymdhis ASC; threads can be interleaved by sort order for legacy compatibility.

---

## Mapping from Crafty Syntax

**Legacy:** `livehelp_transcripts` (content) and `livehelp_messages` (ephemeral buffer).

**Migration:** livehelp_messages is **DROPPED** (no import). livehelp_transcripts → one **lupo_dialog_message** per transcript row, with transcript text in message_text and recno/starttime/endtime mapping to dialog_message_id and timestamps. See `livehelp_transcripts_migration.md`, `livehelp_messages_migration.md`, and `MIGRATION_MAPPING_REFERENCE.md`.
