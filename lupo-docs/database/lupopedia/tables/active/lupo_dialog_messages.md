# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\lupo_dialog_messages.md"
  file_hash: "0a2708fdd0b550c16746c75ce0398d56ff32ac40ea9f130f11896ff826b57134"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_dialog_messages.md"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "table_documentation"
  purpose: "Dialog message storage and delivery"
  dialog_message: "DBDOC batch 2: enriched documentation and optimization notes."
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "database", "curated"]
  tags: ["database", "table", "lupo_dialog_messages"]
  lupo_agent: "codex-ide"
  lupo_dialog_messages.dialog_message_id: "bigint NOT NULL"
  lupo_dialog_messages.dialog_thread_id: "bigint NOT NULL"
  lupo_dialog_messages.channel_id: "bigint NOT NULL"
  lupo_dialog_messages.from_actor_id: "bigint NOT NULL"
  lupo_dialog_messages.to_actor_id: "bigint"
  lupo_dialog_messages.message_type: "varchar(50) NOT NULL DEFAULT 'text'"
  lupo_dialog_messages.message_text: "text"
  lupo_dialog_messages.metadata_json: "text"
  lupo_dialog_messages.created_ymdhis: "bigint NOT NULL DEFAULT 0"
  lupo_dialog_messages.updated_ymdhis: "bigint NOT NULL DEFAULT 0"
  lupo_dialog_messages.is_deleted: "tinyint NOT NULL DEFAULT 0"
  lupo_dialog_messages.deleted_ymdhis: "bigint DEFAULT 0"
  table_primary_key: "dialog_message_id"
  table_engine: "unknown"
  table_charset: "unknown"
  table_collation: "unknown"
  table_indexes: ["idx_channel_id", "idx_dialog_thread_id", "idx_from_actor_id", "idx_to_actor_id", "idx_created_ymdhis"]
  table_foreign_keys: []

# FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml

  last_updated_utc: "20260228"
lupopedia.footer:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_dialog_messages.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_dialog_messages" }
    - { to: "docs/database/lupopedia/tables/lupo_dialog_threads.md", type: "references", weight: 0.8, reason: "thread linkage" }
    - { to: "docs/database/lupopedia/tables/lupo_channels.md", type: "references", weight: 0.7, reason: "channel context" }
  inbound_edges: []
  semantic_tags: ["database", "table", "dialog"]
  version: "4.0.49"
  last_verified: "20260227"
  last_verified_by: "codex-ide"
---

# Table: lupo_dialog_messages

Purpose: Stores messages for dialog threads and channel conversations.
Type: database_table
Status: production_ready
Volume: high

## 1. Overview
- Key responsibilities: message storage and retrieval.
- System role: primary chat payload store.
- Importance: core of live help and thread history.

## 2. Schema Reference
Primary Key: dialog_message_id
Field Categories: identity, routing, payload, lifecycle.

### All Fields
| Column | Type | Notes |
|---|---|---|
| dialog_message_id | bigint NOT NULL | Primary key. |
| dialog_thread_id | bigint NOT NULL | Thread reference. |
| channel_id | bigint NOT NULL | Channel reference. |
| from_actor_id | bigint NOT NULL | Sender. |
| to_actor_id | bigint | Recipient. |
| message_type | varchar(50) NOT NULL DEFAULT 'text' | Message type. |
| message_text | text | Message body. |
| metadata_json | text | Metadata JSON. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | Created timestamp. |
| updated_ymdhis | bigint NOT NULL DEFAULT 0 | Updated timestamp. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT 0 | Soft delete timestamp. |

## 3. Relationships and Dependencies
- Primary relationships: dialog_thread_id, channel_id, actor ids.
- Referencing tables: channel views and search index.
- Integration points: chat UI, moderation, exports.

## 4. Indexes and Performance
Primary Indexes:
- dialog_message_id
Performance Indexes:
- idx_dialog_thread_id
- idx_channel_id
- idx_created_ymdhis
Index Strategy: optimize thread + time retrieval.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_dialog_messages WHERE dialog_thread_id = :thread_id AND is_deleted = 0 ORDER BY created_ymdhis ASC LIMIT 200;
SELECT * FROM lupo_dialog_messages WHERE channel_id = :channel_id AND created_ymdhis > :since ORDER BY created_ymdhis ASC;
UPDATE lupo_dialog_messages SET updated_ymdhis = :ts WHERE dialog_message_id = :id;
```
Best Practices: use keyset pagination on created_ymdhis for large threads.
Anti-Patterns: OFFSET pagination on large datasets.

## 6. Performance Considerations
- High-volume operations: continuous inserts in active chats.
- Optimization tips: add composite index on (dialog_thread_id, created_ymdhis).
- Scaling considerations: archive old messages using is_deleted.

## 7. Data Integrity
- Constraints: thread_id and channel_id required.
- Validation rules: enforce message_type values.
- Soft delete: archive and retain for audit.

## 8. Common Issues and Solutions
- Slow thread loads: add composite index and use keyset pagination.
- Message drift: ensure created_ymdhis monotonic.
- Duplicate sends: enforce idempotency at application layer.

## 9. Future Enhancements
- Add message_hash for deduplication.
- Add indexed summary fields for moderation.