---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_channels.md"
  web_path: '[lupo_actor_channels](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_channels)'
  last_modified_utc: "20260327234500"
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: "core"
  purpose: Documentation file with LUPOPEDIA HEADERS applied
  tags:
  - database
  - table
  - core
  when_updated: "20260327234500"
lupopedia.edges:
  comment: "Snapshot stage1 confidence-scored edges (git=1.0, code-scan=0.7, db=0.5)."
    by repo search; non-exhaustive).
  meta: php_hits=8 python_hits=4
  outbound_edges:
  - to: database.table.lupo_actor_channels
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
  - to: debug_captain.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/classes/AgentAwarenessLayer.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: lupo-includes/classes/ContentChannelActorResolver.php
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
  - to: lupo-scripts/verify_grounded_architecture.php
    type: USED_IN_PHP
    weight: 0.7
    confidence: 0.7
    source: "code-scan"
  - to: lupo-tests/unit/channel_api_security_test.php
    type: USED_IN_PHP
    weight: 0.7
    confidence: 0.7
    source: "code-scan"
  - to: analyze_unused_tables.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
  - to: lupo-scripts/rebuild_schema_from_toons.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
  - to: lupo-scripts/wolfie_orms.py
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
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_actor_channels.md

# lupo_actor_channels

## Purpose
Canonical table documentation regenerated from TOON JSON for `lupo_actor_channels`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `actor_channel_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `actor_name` | `varchar(64)` |
| `created_by_actor_id` | `bigint NOT NULL DEFAULT 0` |
| `channel_id` | `bigint NOT NULL` |
| `status` | `char(1) NOT NULL DEFAULT 'A'` |
| `start_date` | `bigint` |
| `channel_color` | `varchar(6) NOT NULL DEFAULT 'F7FAFF'` |
| `last_read_ymdhis` | `bigint` |
| `muted_until_ymdhis` | `bigint` |
| `preferences_json` | `json` |
| `dialog_output_file` | `varchar(500)` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_actor_channels_idx_actor` | `actor_id` | no |
| `lupo_actor_channels_idx_actor_name` | `actor_name` | no |
| `lupo_actor_channels_idx_channel` | `channel_id` | no |
| `lupo_actor_channels_idx_created` | `created_ymdhis` | no |
| `lupo_actor_channels_idx_deleted` | `is_deleted` | no |
| `lupo_actor_channels_idx_status` | `status` | no |
| `lupo_actor_channels_idx_updated` | `updated_ymdhis` | no |
| `lupo_actor_channels_unq_actor_channel` | `actor_id`, `channel_id` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Phase 2 deterministic rebuild
- Edge mode: placeholder only
