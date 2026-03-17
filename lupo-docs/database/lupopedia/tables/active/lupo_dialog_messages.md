---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_dialog_messages.md"
  web_path: "[lupo_dialog_messages](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_dialog_messages)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "content"
  purpose: "Complete documentation for lupo_dialog_messages table - dialog message storage and delivery system"
  tags: ["database", "table", "content"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_dialog_messages table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=23 python_hits=7"
  outbound_edges:
    - { to: "database.table.lupo_dialog_messages", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "admin.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "check_db_state.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-api/v1/dialog/health.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-api/v1/dialog/metrics.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-database/lupopedia/content/lupo-app/Services/AnubisUnknownRecipientService.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-database/lupopedia/content/lupo-app/Services/TriggerReplacements/DialogMessagesDeleteService.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-database/lupopedia/content/lupo-app/Services/TriggerReplacements/DialogMessagesInsertService.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-includes/Dialog/Database/DialogDatabase.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/DialogChannelMigration/MessageBuilder.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/DialogChannelMigration/MigrationOrchestrator.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/DialogChannelMigration/ValidationTool.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/class-dialog-manager.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/classes/ANUBIS_Resolver.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/classes/ChannelService.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-includes/modules/channels/ChannelsController.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "analyze_unused_tables.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/audit_schema_doctrine.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/check_doc_schema_consistency.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/fetch_doctrines.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/import_channels_and_artifacts.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/rebuild_schema_from_toons.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-tools/anubis_orphan_scanner.py", type: "USED_IN_PYTHON", weight: 0.5 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: lupo_dialog_messages ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_dialog_messages
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
