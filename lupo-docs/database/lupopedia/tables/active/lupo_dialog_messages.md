---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_dialog_messages.md"
  system_version: "4.0.73"
  namespace: "dialog"
  channel_id: 1
  actor_id: 1003
  last_modified_utc: "20260313"
  artifact_type: "table_documentation"
  purpose: "Complete documentation for lupo_dialog_messages table - dialog message storage and delivery system"
  mood_rgb: "4169E1"
  traits: ["canonical", "dialog", "communication", "antigravity_rotation", "v4.0.73"]
  tags: ["database", "dialogs", "messages", "chat", "communication"]
  lupo_agent: "antigravity"

lupopedia.edges:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Edges reflect discovered relationships between database tables and PHP/Python codebase entities. Values should be verified against live database schemas/queries for the most current semantic graph state."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_dialog_messages.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_dialog_messages" }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_dialog_threads.md", type: "references", weight: 0.8, reason: "thread linkage" }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md", type: "references", weight: 0.7, reason: "channel context" }

lupopedia.engagement:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Engagement metrics track edit frequency and importance of each file in the version transition process."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  views: 0

lupopedia.footer:
  version: "4.0.73"
  last_verified: "20260313"
  last_verified_by: "antigravity"
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
