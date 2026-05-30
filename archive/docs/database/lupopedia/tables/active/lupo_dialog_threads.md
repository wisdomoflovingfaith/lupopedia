---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_dialog_threads.md"
  web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_dialog_threads
  last_modified_utc: "20260327234500"
  channel_id: 42
  actor_id: 1
  actor_name: wolfie
  delegation_chain: wolfie:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: "core"
  purpose: Dialog thread management; tracks conversation threads, message organization,
    and dialog lifecycle
  tags:
  - database
  - table
  - channels
  when_updated: "20260327234500"
lupopedia.edges:
  comment: "Snapshot stage1 confidence-scored edges (git=1.0, code-scan=0.7, db=0.5)."
    by repo search; non-exhaustive).
  meta: php_hits=9 python_hits=5
  outbound_edges:
  - to: database.table.lupo_dialog_threads
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
  - to: database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
  - to: check_db_state.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: api/v1/dialog/health.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: api/v1/dialog/metrics.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: database/lupopedia/content/app/Services/TriggerReplacements/DialogMessagesInsertService.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: includes/Dialog/Database/DialogDatabase.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: includes/modules/channels/ChannelsController.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: tools/anubis_orphan_scanner.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
lupopedia.footer:
  provenance: "phase2_git_header_recovered_body_regenerated"
  generated: true
  last_verified: "20260327234500"
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_dialog_threads.md

# lupo_dialog_threads

## Purpose
Canonical table documentation regenerated from TOON JSON for `lupo_dialog_threads`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `dialog_thread_id` | `bigint NOT NULL` |
| `title` | `varchar(255) NOT NULL` |
| `thread_key` | `varchar(255)` — human-readable key; **UNIQUE** with `federation_node_id`, `channel_id` |
| `last_message_ymdhis` | `bigint` |
| `federation_node_id` | `bigint NOT NULL DEFAULT 1` |
| `channel_id` | `bigint` |
| `project_slug` | `varchar(100)` |
| `task_name` | `varchar(255)` |
| `created_by_actor_id` | `bigint NOT NULL` |
| `summary_text` | `text` |
| `bg_color` | `char(6) NOT NULL DEFAULT 'FFFFFF'` |
| `text_color` | `char(6) NOT NULL DEFAULT '000000'` |
| `alt_text_color` | `char(6) NOT NULL DEFAULT '666666'` |
| `status` | `varchar(64) NOT NULL DEFAULT 'Open'` |
| `artifacts` | `json` |
| `metadata_json` | `json` |
| `thread_lineage` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `escalated_to_operator_id` | `bigint` |
| `escalation_reason` | `varchar(255)` |
| `escalation_timestamp` | `bigint` |
| `visibility_status` | `varchar(32) NOT NULL DEFAULT 'active'` |
| `owner_actor_id` | `bigint NOT NULL` |
| `assigned_actor_id` | `bigint` |
| `thread_type` | `varchar(32) NOT NULL DEFAULT 'discussion'` |
| `thread_priority` | `varchar(32) NOT NULL DEFAULT 'normal'` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_dialog_threads_idx_assigned_actor_id` | `assigned_actor_id` | no |
| `lupo_dialog_threads_uq_node_channel_thread_key` | `federation_node_id`, `channel_id`, `thread_key` | yes |
| `lupo_dialog_threads_idx_channel` | `channel_id` | no |
| `lupo_dialog_threads_idx_created` | `created_ymdhis` | no |
| `lupo_dialog_threads_idx_created_by_actor` | `created_by_actor_id` | no |
| `lupo_dialog_threads_idx_deleted` | `is_deleted` | no |
| `lupo_dialog_threads_idx_last_message` | `last_message_ymdhis` | no |
| `lupo_dialog_threads_idx_node` | `federation_node_id` | no |
| `lupo_dialog_threads_idx_owner_actor_id` | `owner_actor_id` | no |
| `lupo_dialog_threads_idx_project` | `project_slug` | no |
| `lupo_dialog_threads_idx_status` | `status` | no |
| `lupo_dialog_threads_idx_task` | `task_name` | no |
| `lupo_dialog_threads_idx_thread_priority` | `thread_priority` | no |
| `lupo_dialog_threads_idx_thread_type` | `thread_type` | no |
| `lupo_dialog_threads_idx_updated` | `updated_ymdhis` | no |
| `lupo_dialog_threads_idx_visibility_status` | `visibility_status` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Phase 2 deterministic rebuild
- Edge mode: placeholder only
