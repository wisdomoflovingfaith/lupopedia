---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/COMMUNICATION_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/doctrine/COMMUNICATION_DOCTRINE"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: communication
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: Communication Doctrine — session: L-LUPO-ROOT-CURSOR — delegation: wolfie:root (faucet: cursor) — web_path: http://www.lupopedia.com/doctrine/COMMUNICATION_DOCTRINE

# Communication Doctrine — v4.0.69

## 1. Canonical Communication Tables

All communication in Lupopedia — whether live chat, channel discussions, version threads, or actor coordination (actors operate through faucets) — MUST use the **dialog tables** (with table prefix, e.g. `lupo_`):

| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `lupo_dialog_channels` | Channel metadata for communication | `channel_id`, `channel_name`, `file_source`, `speaker`, `target`, `message_count` |
| `lupo_dialog_threads` | Conversation threads | `dialog_thread_id`, `channel_id`, `project_slug`, `task_name`, `status`, `created_by_actor_id`, `last_message_ymdhis` |
| `lupo_dialog_messages` | Individual messages | `dialog_message_id`, `dialog_thread_id`, `channel_id`, `from_actor_id`, `source_faucet_slug`, `source_faucet_instance_id`, `to_actor_id`, `message_text`, `message_type`, `read_by_actor_id`, `read_by_actor_utc`, `mood_vector` (faucet columns for traceability; see FAUCET_TRACEABILITY_DOCTRINE.md) |

## 2. What This Means

- **Channel 42 discussions** — MUST be stored in `lupo_dialog_*` tables, not JSON files (or migrated from JSON into these tables).
- **Version threads** — MUST use `lupo_dialog_*` tables, not separate `lupo_threads`/`lupo_messages`.
- **All communication** — By actors (via faucets when using IDE/LLM surfaces); goes through the dialog tables with appropriate `channel_id`.

## 3. Removed Tables

The following tables are **DEPRECATED and REMOVED** as of v4.0.69:

| Table | Reason |
|-------|--------|
| `lupo_threads` | Replaced by `lupo_dialog_threads` with `channel_id=42` for version discussions |
| `lupo_messages` | Replaced by `lupo_dialog_messages` |

## 4. Migration Path

### 4.1 For Channel 42 JSON Threads

File-based threads under `channels/42/threads/` MAY be migrated to `lupo_dialog_*` tables:

- Each JSON file becomes a `lupo_dialog_thread` with `channel_id=42`
- Each message in the JSON becomes a `lupo_dialog_message` linked to that thread
- Original JSON files may be archived to `channels/42/threads/archive/`

Use `scripts/migrate_channel42_threads_to_db.php` for migration.

### 4.2 For Version Thread Tables

The empty `lupo_threads` and `lupo_messages` tables have been removed from the schema. Any version discussions use `lupo_dialog_*` with `channel_id=42`.

## 5. Code References

All communication code MUST use:

- **Table name for messages:** `dialog_messages` (with prefix → `lupo_dialog_messages`). Do **not** use `dialog_doctrine`.
- **ChannelService** — for channel operations
- **MessageBuilder** — for message creation
- Direct SQL against `lupo_dialog_*` tables when needed (with configured table prefix)

The namespaced `DialogDatabase` layer uses `lupo_dialog_messages` (not `dialog_doctrine`).

## 6. Verification

```sql
-- All communication lives in dialog tables
SELECT COUNT(*) FROM lupo_dialog_messages WHERE channel_id = 42;

-- Threads for channel 42
SELECT COUNT(*) FROM lupo_dialog_threads WHERE channel_id = 42 AND is_deleted = 0;
```

## 7. See Also

- Migration script: `database/migrations/20260310_remove_duplicate_thread_message_tables.sql`
- Channel 42 thread migration: `scripts/migrate_channel42_threads_to_db.php`
- Audit: `docs/status/DIALOG_VS_MESSAGES_TABLES_AUDIT.md`
