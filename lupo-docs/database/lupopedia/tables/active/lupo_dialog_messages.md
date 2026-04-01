---
lupopedia.headers:
  namespace: "core"
  lupopedia.schema: database_table
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_dialog_messages.md"
  web_path: '[lupo_dialog_messages](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_dialog_messages)'
  last_modified_utc: "20260327234500"
  channel_id: 42
  actor_id: 26
  actor_name: thoth
  delegation_chain: thoth:knowledge
  artifact_type: table_documentation
  artifact_kind: table
  purpose: Complete documentation for lupo_dialog_messages table - dialog message
    storage and delivery system
  tags:
  - database
  - table
  - content
  - 4.0.84
  when_updated: "20260327234500"
lupopedia.edges:
  comment: "Snapshot stage1 confidence-scored edges (git=1.0, code-scan=0.7, db=0.5)."
    by repo search; non-exhaustive).
  meta: php_hits=23 python_hits=7
  outbound_edges:
  - to: database.table.lupo_dialog_messages
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
  - to: lupo-database/lupopedia/toon/lupo_dialog_messages.toon
    type: schema_reference
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
  - to: admin.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: check_db_state.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: lupo-api/v1/dialog/health.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: lupo-api/v1/dialog/metrics.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: lupo-database/lupopedia/content/lupo-app/Services/AnubisUnknownRecipientService.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: lupo-database/lupopedia/content/lupo-app/Services/TriggerReplacements/DialogMessagesDeleteService.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: lupo-database/lupopedia/content/lupo-app/Services/TriggerReplacements/DialogMessagesInsertService.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/Dialog/Database/DialogDatabase.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/DialogChannelMigration/MessageBuilder.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/DialogChannelMigration/MigrationOrchestrator.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/DialogChannelMigration/ValidationTool.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/classes/dialog-manager.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/classes/ANUBIS_Resolver.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/classes/ChannelService.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/modules/channels/ChannelsController.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: analyze_unused_tables.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
  - to: lupo-scripts/audit_schema_doctrine.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
  - to: lupo-scripts/check_doc_schema_consistency.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
  - to: lupo-scripts/fetch_doctrines.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
  - to: lupo-scripts/import_channels_and_artifacts.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
  - to: lupo-scripts/rebuild_schema_from_toons.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
  - to: lupo-tools/anubis_orphan_scanner.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
lupopedia.footer:
  provenance: "phase2_git_header_recovered_body_regenerated"
  generated: true
  last_verified: "20260327234500"
  last_verified_by: cursor
  orchestrator: wolfie
  next_action:
  - Maintain schema consistency with install SQL and TOON files
  - Update documentation when schema changes occur
  last_verified_by_actor_id: 102
---
# file: lupo_dialog_messages.md

# lupo_dialog_messages

## Purpose
Canonical table documentation regenerated from TOON JSON for `lupo_dialog_messages`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `dialog_message_id` | `bigint NOT NULL` |
| `message_id` | `bigint NOT NULL DEFAULT 0` |
| `dialog_thread_id` | `bigint` |
| `channel_id` | `bigint` |
| `from_actor_id` | `bigint` |
| `source_faucet_slug` | `varchar(100)` |
| `source_faucet_instance_id` | `varchar(100)` |
| `to_actor_id` | `bigint` |
| `read_by_actor_id` | `bigint NOT NULL DEFAULT 0` |
| `read_by_actor_utc` | `bigint NOT NULL DEFAULT 0` |
| `message_text` | `varchar(1000) NOT NULL` |
| `message_type` | `varchar(64) NOT NULL DEFAULT 'text'` |
| `metadata_json` | `json` |
| `mood_rgb` | `char(6)` |
| `mood_framework` | `varchar(32) NOT NULL DEFAULT 'western_analytical'` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `message_body` | `mediumtext` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_dialog_messages_idx_channel` | `channel_id` | no |
| `lupo_dialog_messages_idx_created` | `created_ymdhis` | no |
| `lupo_dialog_messages_idx_deleted` | `is_deleted` | no |
| `lupo_dialog_messages_idx_dialog_thread_id` | `dialog_thread_id` | no |
| `lupo_dialog_messages_idx_faucet` | `source_faucet_slug`, `source_faucet_instance_id` | no |
| `lupo_dialog_messages_idx_message_type` | `message_type` | no |
| `lupo_dialog_messages_idx_read_by_actor` | `read_by_actor_id` | no |
| `lupo_dialog_messages_idx_read_utc` | `read_by_actor_utc` | no |
| `lupo_dialog_messages_idx_to_actor_id` | `to_actor_id` | no |
| `lupo_dialog_messages_idx_updated` | `updated_ymdhis` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Phase 2 deterministic rebuild
- Edge mode: placeholder only
