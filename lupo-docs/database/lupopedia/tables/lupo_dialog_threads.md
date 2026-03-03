# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\database\lupopedia\tables\lupo_dialog_threads.md"
  file_hash: "b3adc3a6052f002d63004aba6f13c032582affae43b7760fa4b77ab334f41ca9"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_dialog_threads.md"
  system_version: "4.0.49"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "table_documentation"
  purpose: "Dialog thread registry for channel conversations"
  dialog_message: "DBDOC batch 2: enriched documentation and optimization notes."
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "database", "curated"]
  tags: ["database", "table", "lupo_dialog_threads"]
  lupo_agent: "codex-ide"
  lupo_dialog_threads.dialog_thread_id: "bigint NOT NULL"
  lupo_dialog_threads.title: "varchar(255) NOT NULL"
  lupo_dialog_threads.last_message_ymdhis: "bigint"
  lupo_dialog_threads.federation_node_id: "bigint NOT NULL DEFAULT 1"
  lupo_dialog_threads.channel_id: "bigint"
  lupo_dialog_threads.project_slug: "varchar(100)"
  lupo_dialog_threads.task_name: "varchar(255)"
  lupo_dialog_threads.created_by_actor_id: "bigint NOT NULL"
  lupo_dialog_threads.summary_text: "text"
  lupo_dialog_threads.bg_color: "char(6) NOT NULL DEFAULT 'FFFFFF'"
  lupo_dialog_threads.text_color: "char(6) NOT NULL DEFAULT '000000'"
  lupo_dialog_threads.alt_text_color: "char(6) NOT NULL DEFAULT '666666'"
  lupo_dialog_threads.status: "varchar(64) NOT NULL DEFAULT 'Open'"
  lupo_dialog_threads.artifacts: "json"
  lupo_dialog_threads.metadata_json: "json"
  lupo_dialog_threads.created_ymdhis: "bigint NOT NULL DEFAULT 0"
  lupo_dialog_threads.updated_ymdhis: "bigint NOT NULL"
  lupo_dialog_threads.is_deleted: "tinyint NOT NULL DEFAULT 0"
  lupo_dialog_threads.deleted_ymdhis: "bigint"
  lupo_dialog_threads.escalated_to_operator_id: "bigint"
  lupo_dialog_threads.escalation_reason: "varchar(255)"
  lupo_dialog_threads.escalation_timestamp: "bigint"
  table_primary_key: "dialog_thread_id"
  table_engine: "unknown"
  table_charset: "unknown"
  table_collation: "unknown"
  table_indexes: ["lupo_dialog_threads_idx_channel", "lupo_dialog_threads_idx_created", "lupo_dialog_threads_idx_created_by_actor", "lupo_dialog_threads_idx_deleted", "lupo_dialog_threads_idx_last_message", "lupo_dialog_threads_idx_node", "lupo_dialog_threads_idx_project", "lupo_dialog_threads_idx_status", "lupo_dialog_threads_idx_task", "lupo_dialog_threads_idx_updated"]
  table_foreign_keys: []

# FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml

flare.footer:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_dialog_threads.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_dialog_threads" }
    - { to: "docs/database/lupopedia/tables/lupo_dialog_messages.md", type: "references", weight: 0.8, reason: "message linkage" }
    - { to: "docs/database/lupopedia/tables/lupo_channels.md", type: "references", weight: 0.7, reason: "channel context" }
  inbound_edges: []
  semantic_tags: ["database", "table", "dialog"]
  version: "4.0.49"
  last_verified: "20260227"
  last_verified_by: "codex-ide"
---

# Table: lupo_dialog_threads

Purpose: Stores conversation thread metadata for channel dialogs.
Type: database_table
Status: production_ready
Volume: medium

## 1. Overview
- Key responsibilities: thread identity, status, and routing metadata.
- System role: anchors dialog messages to a thread entity.
- Importance: enables chat history, moderation, and escalation.

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
