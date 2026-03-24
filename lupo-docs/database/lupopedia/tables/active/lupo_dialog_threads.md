---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_dialog_threads.md
  web_path: '[lupo_dialog_threads](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_dialog_threads)'
  last_modified_utc: '20260317'
  channel_id: 42
  actor_id: 1
  actor_name: wolfie
  delegation_chain: wolfie:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: channels
  purpose: Dialog thread management; tracks conversation threads, message organization,
    and dialog lifecycle
  tags:
  - database
  - table
  - channels
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of edges for lupo_dialog_threads table doc at 4.0.79 (grounded
    by repo search; non-exhaustive).
  meta: php_hits=9 python_hits=5
  outbound_edges:
  - to: database.table.lupo_dialog_threads
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: check_db_state.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-api/v1/dialog/health.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-api/v1/dialog/metrics.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/Services/TriggerReplacements/DialogMessagesInsertService.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-includes/Dialog/Database/DialogDatabase.php
    type: USED_IN_PHP
    weight: 0.9
  - to: lupo-includes/modules/channels/ChannelsController.php
    type: USED_IN_PHP
    weight: 0.9
  - to: lupo-tools/anubis_orphan_scanner.py
    type: USED_IN_PYTHON
    weight: 0.5
lupopedia.footer:
  last_verified: '20260317000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_dialog_threads ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_dialog_threads
# Table: lupo_dialog_threads

Purpose: Stores conversation thread metadata for channel dialogs.
Type: database_table
Status: production_ready
Volume: medium

## 1. Overview
- Key responsibilities: thread identity, status, and routing metadata.
- System role: anchors dialog messages to a thread entity.
- Importance: enables chat history, moderation, and escalation.

> **Deprecation Notice (4.0.86):** The `thread_lineage` text column is deprecated. All new thread lineage relationships (continuations, forks, citations) must be stored in `lupo_edges` using `thread_continuation` or `thread_spawned_from` edge types. Existing data is being migrated.

## 2. Schema Reference
Primary Key: dialog_thread_id
Field Categories: identity, routing, status, styling, lifecycle.

### All Fields
| Column | Type | Notes |
|---|---|---|
| dialog_thread_id | bigint NOT NULL | Primary key. |
| title | varchar(255) NOT NULL | Thread title. |
| last_message_ymdhis | bigint | Last message time. |
| federation_node_id | bigint NOT NULL DEFAULT 1 | Federation scope. |
| channel_id | bigint | Channel id. |
| project_slug | varchar(100) | Project slug. |
| task_name | varchar(255) | Task name. |
| created_by_actor_id | bigint NOT NULL | Creator actor. |
| summary_text | text | Summary. |
| bg_color | char(6) NOT NULL DEFAULT 'FFFFFF' | UI color. |
| text_color | char(6) NOT NULL DEFAULT '000000' | UI color. |
| alt_text_color | char(6) NOT NULL DEFAULT '666666' | UI color. |
| status | varchar(64) NOT NULL DEFAULT 'Open' | Status. |
| artifacts | json | Attached artifacts list. |
| metadata_json | json | Extra metadata. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | Created timestamp. |
| updated_ymdhis | bigint NOT NULL | Updated timestamp. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint | Soft delete timestamp. |
| escalated_to_operator_id | bigint | Escalation actor. |
| escalation_reason | varchar(255) | Escalation reason. |
| escalation_timestamp | bigint | Escalation time. |

## 3. Relationships and Dependencies
- Primary relationships: channel_id, created_by_actor_id.
- Referencing tables: lupo_dialog_messages, channel views.
- Integration points: chat monitoring and escalation.

## 4. Indexes and Performance
Primary Indexes:
- dialog_thread_id
Performance Indexes:
- lupo_dialog_threads_idx_channel
- lupo_dialog_threads_idx_last_message
- lupo_dialog_threads_idx_status
Index Strategy: optimize by channel, status, and last activity.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_dialog_threads WHERE channel_id = :channel AND is_deleted = 0 ORDER BY last_message_ymdhis DESC LIMIT 50;
SELECT * FROM lupo_dialog_threads WHERE status = 'Open' AND is_deleted = 0 ORDER BY created_ymdhis DESC;
UPDATE lupo_dialog_threads SET updated_ymdhis = :ts WHERE dialog_thread_id = :id;
```
Best Practices: update last_message_ymdhis on new messages.
Anti-Patterns: full scans without channel_id filters.

## 6. Performance Considerations
- High-volume operations: frequent updates to last_message_ymdhis.
- Optimization tips: add composite index on (channel_id, status, last_message_ymdhis).
- Scaling considerations: archive old threads using is_deleted and deleted_ymdhis.

## 7. Data Integrity
- Constraints: title required, created_by_actor_id required.
- Validation rules: enforce status values.
- Soft delete: maintain audit trail.

## 8. Common Issues and Solutions
- Slow listings: use last_message_ymdhis and channel index.
- Escalation drift: keep escalation fields synced with operator actions.
- Missing summaries: update summary_text via background summarization.

## 9. Future Enhancements
- Add message_count cache for faster thread list rendering.
- Add last_message_id for precise pagination.
