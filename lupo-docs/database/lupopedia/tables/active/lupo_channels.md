---
lupopedia.headers:
  lupopedia.version: "4.0.78"
  lupopedia.schema: "database_table"
  system_version: "4.0.78"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md"
  web_path: "[lupo_channels](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_channels)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "channels"
  purpose: "Documentation for lupo_channels table - communication channel management and routing"
  traits: ["canonical", "core_system", "channels", "v4.0.78"]
  tags: ["database", "channels", "communication", "routing", "federation"]
  table_primary_key: "channel_id"
  doctrine_note: "No database foreign keys; referential integrity enforced in application code."

lupopedia.edges:
  comment: "Snapshot of edges for lupo_channels table doc at 4.0.78."
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_departments.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_contents.md", type: "references", weight: 0.7 }
    - { to: "lupo-channels/registry.json", type: "references", weight: 0.8 }

lupopedia.footer:
  version: "4.0.78"
  last_verified: "20260316"
  last_verified_by: "cursor"
---
# file: lupo_channels — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_channels

# Table: lupo_channels

## Table Overview

- **Purpose:** Central routing and management for all communication channels in Lupopedia. Stores channel identity (channel_id, channel_key, channel_slug), type, language, display name, and links to federation node, creator actor, default actor, department, and optional parent channel. Supports hierarchical channels, kernel/system channels, awareness versioning, and JSON metadata (AAL, fleet composition).
- **Category:** Core System / Channels
- **Status:** Active (in install_new_lupopedia.sql)
- **Version introduced:** 4.0.x

## Where This Table Is Used

- **Channel orchestration:** Modules and routing logic resolve channels by channel_id or (channel_key, federation_node_id). All dialog and content routing is scoped to a channel; this table is the authority for channel existence and metadata.
- **Federation node partitioning:** Each channel belongs to a federation_node_id; channel_key is unique per node. Multi-node deployments use this for distribution and local-first routing.
- **Content routing:** Content and help tree can be scoped by channel_id; lupo_contents and related tables reference channel_id for channel-specific content.
- **Department relationships:** department_id links to lupo_departments for operator assignment and department-based channel grouping; lupo_channel_departments extends many-to-many where needed.
- **Actor/channel association:** created_by_actor_id and default_actor_id reference lupo_actors; lupo_actor_channels links actors to channels for membership and participation.
- **Kernel and boot:** is_kernel and boot_sequence_order support system/kernel channels and ordered startup; channel boot lifecycle and detail tables reference channel_id.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| channel_id | bigint | No | — | Primary key. Reserved-ID doctrine: application supplies explicit ID (e.g. timestamp-based or from registry). |
| federation_node_id | bigint | No | — | Federation node this channel belongs to. Logical reference to lupo_federation_nodes. |
| created_by_actor_id | bigint | No | — | Actor who created the channel. Logical reference to lupo_actors. |
| default_actor_id | bigint | No | 1 | Default actor for the channel. Logical reference to lupo_actors. |
| department_id | bigint | No | 1 | Department assignment. Logical reference to lupo_departments. |
| channel_key | varchar(64) | No | — | Unique channel identifier; unique per (channel_key, federation_node_id). |
| channel_slug | varchar(32) | No | 'channel_key' | URL-friendly slug. |
| channel_type | varchar(32) | No | 'chat_room' | Type of channel (e.g. chat_room, dialog, system). |
| language | varchar(16) | No | 'en' | Channel language. |
| channel_name | varchar(255) | No | — | Display name. |
| description | text | Yes | — | Channel description. |
| website_link | varchar(512) | Yes | — | Related website link. |
| metadata_json | text | Yes | — | Legacy metadata (deprecated in favor of JSON columns where used). |
| status_flag | tinyint | No | 1 | Channel status. |
| end_ymdhis | bigint | Yes | — | Channel end timestamp (BIGINT UTC YYYYMMDDHHIISS). |
| duration_seconds | int | Yes | — | Channel duration in seconds. |
| created_ymdhis | bigint | No | 0 | Creation timestamp (BIGINT UTC). |
| updated_ymdhis | bigint | No | — | Last update timestamp (BIGINT UTC). |
| is_deleted | tinyint | No | 0 | Soft delete flag. |
| deleted_ymdhis | bigint | Yes | — | Soft delete timestamp. |
| aal_metadata_json | json | Yes | — | Actor Action Language metadata. |
| fleet_composition_json | json | Yes | — | Fleet composition and agent assignments. |
| awareness_version | varchar(20) | Yes | '3.0.0' | Awareness system version. |
| channel_number | int | Yes | — | Numeric channel identifier. |
| parent_channel_id | bigint | Yes | — | Parent channel for hierarchy. Self-reference to lupo_channels. |
| project_id | bigint | Yes | — | Optional project scope. Logical reference to lupo_projects. |
| is_kernel | tinyint | No | 0 | Kernel/system channel flag. |
| boot_sequence_order | int | Yes | — | Boot sequence order for kernel channels. |

## Indexes

- **PRIMARY KEY:** channel_id
- **UNIQUE:** lupo_channels_unq_channel_key_per_node (channel_key, federation_node_id)
- **INDEX:** lupo_channels_idx_domain (federation_node_id), lupo_channels_idx_channel_key (channel_key), lupo_channels_idx_status (status_flag), lupo_channels_idx_dates (end_ymdhis), lupo_channels_idx_awareness_version (awareness_version), lupo_channels_idx_project_id (project_id)

## Relationships

- **Logical references (no DB FKs):** federation_node_id → lupo_federation_nodes; created_by_actor_id, default_actor_id → lupo_actors; department_id → lupo_departments; parent_channel_id → lupo_channels; project_id → lupo_projects.
- **Tables that reference lupo_channels:** lupo_dialog_messages, lupo_contents, lupo_actor_channels, lupo_channel_departments, lupo_channel_boot_detail, lupo_channel_boot_lifecycle, and related channel-scoped tables use channel_id as the join key.

## Doctrine Notes

- **No foreign keys.** All referential integrity in application code.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC; set in PHP with `gmdate('YmdHis')`.
- **Soft delete:** Filter by `is_deleted = 0` unless querying deleted channels.
- **Reserved ID:** channel_id is not AUTO_INCREMENT; application must supply explicit ID per reserved-ID doctrine.
