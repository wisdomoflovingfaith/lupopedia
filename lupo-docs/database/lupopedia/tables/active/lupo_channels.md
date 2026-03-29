---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md"
  web_path: '[lupo_channels](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_channels)'
  last_modified_utc: "20260327234500"
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: "channels"
  purpose: Documentation for lupo_channels table - communication channel management
    and routing
  tags:
  - database
  - table
  - channels
  when_updated: "20260327234500"
lupopedia.edges:
  comment: "Snapshot stage1 confidence-scored edges (git=1.0, code-scan=0.7, db=0.5)."
    search; non-exhaustive).
  meta: php_hits=17 python_hits=8
  outbound_edges:
  - to: database.table.lupo_channels
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
  - to: install.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: lupo-bin/channel_startup_lifecycle.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: lupo-bin/initialize_system.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: lupo-database/lupopedia/channels/channel_id/1/admin/dashboard.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: lupo-database/lupopedia/content/lupo-app/Services/AnubisUnknownRecipientService.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/classes/AgentAwarenessLayer.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/functions/reserved-id-helpers.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/modules/api/channels-api.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/modules/channels/ChannelsController.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/modules/channels/channels-controller.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/modules/crafty_syntax/choosedepartment.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/modules/crafty_syntax/livehelp-js.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/modules/crafty_syntax/visitor-image.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: lupo-scripts/audit_schema_doctrine.php
    type: USED_IN_PHP
    weight: 0.7
    confidence: 0.7
    source: "code-scan"
  - to: lupo-scripts/init_channels.php
    type: USED_IN_PHP
    weight: 0.7
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
  - to: lupo-scripts/export_channel_snapshots.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
  - to: lupo-scripts/migrate_filesystem_to_db.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
  - to: lupo-scripts/rebuild_lupo_contents.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
  - to: lupo-scripts/rebuild_schema_from_toons.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
  - to: lupo-scripts/verify_db_against_toons.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
  - to: lupo-scripts/wolfie_orms.py
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
# file: lupo_channels.md

# lupo_channels

## Purpose
Canonical table documentation regenerated from TOON JSON for `lupo_channels`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `channel_id` | `bigint NOT NULL` |
| `federation_node_id` | `bigint NOT NULL` |
| `created_by_actor_id` | `bigint NOT NULL` |
| `default_actor_id` | `bigint NOT NULL DEFAULT 1` |
| `department_id` | `bigint NOT NULL DEFAULT 1` |
| `channel_key` | `varchar(64) NOT NULL` |
| `channel_slug` | `varchar(32) NOT NULL DEFAULT 'channel_key'` |
| `channel_type` | `varchar(32) NOT NULL DEFAULT 'chat_room'` |
| `language` | `varchar(16) NOT NULL DEFAULT 'en'` |
| `channel_name` | `varchar(255) NOT NULL` |
| `description` | `text` |
| `website_link` | `varchar(512)` |
| `metadata_json` | `text` |
| `channel_config` | `text` |
| `status_flag` | `tinyint NOT NULL DEFAULT 1` |
| `end_ymdhis` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `aal_metadata_json` | `json` |
| `fleet_composition_json` | `json` |
| `awareness_version` | `varchar(20) DEFAULT '3.0.0'` |
| `channel_number` | `int` |
| `parent_channel_id` | `bigint` |
| `project_id` | `bigint` |
| `is_kernel` | `tinyint NOT NULL DEFAULT 0` |
| `boot_sequence_order` | `int` |
| `visibility_status` | `varchar(32) NOT NULL DEFAULT 'active'` |
| `owner_actor_id` | `bigint NOT NULL DEFAULT 1` |
| `access_level` | `varchar(32) NOT NULL DEFAULT 'public'` |
| `last_activity_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_channels_idx_access_level` | `access_level` | no |
| `lupo_channels_idx_awareness_version` | `awareness_version` | no |
| `lupo_channels_idx_channel_key` | `channel_key` | no |
| `lupo_channels_idx_dates` | `end_ymdhis` | no |
| `lupo_channels_idx_domain` | `federation_node_id` | no |
| `lupo_channels_idx_last_activity` | `last_activity_ymdhis` | no |
| `lupo_channels_idx_owner_actor_id` | `owner_actor_id` | no |
| `lupo_channels_idx_project_id` | `project_id` | no |
| `lupo_channels_idx_status` | `status_flag` | no |
| `lupo_channels_idx_visibility_status` | `visibility_status` | no |
| `lupo_channels_unq_channel_key_per_node` | `channel_key`, `federation_node_id` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Phase 2 deterministic rebuild
- Edge mode: placeholder only
