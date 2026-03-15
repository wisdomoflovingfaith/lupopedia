---
lupopedia.headers:
  lupopedia.version: "4.0.75"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/status/TOON_DATABASE_STRUCTURE_COLLECTIONS_ACTORS_ORGANIZATION.md"
  system_version: "4.0.75"
  last_modified_utc: "20260315"
  channel_id: 42
  artifact_type: "status"
  artifact_kind: "research"
  purpose: "Summary of database structure from TOON files: collections, organization, and actors."

lupopedia.footer:
  version: "4.0.75"
  last_verified: "20260315"
  next_action: ["Update if TOONs or install schema change"]
---

# TOON Database Structure — Collections, Organization, and Actors

**Source:** TOON files in `lupo-database/lupopedia/toon/*.toon.json` (canonical column/type reference; generated from install SQL).  
**Focus:** How the database structures **collections**, **organization** (registry, federation, departments), and **actors**.

---

## 1. Actors

### 1.1 Core identity: `lupo_actors`

- **Primary key:** `actor_name` (varchar 64) — semantic identifier.
- **Unique:** `actor_id` (bigint), `slug` (varchar 255).
- **Identity and type:** `actor_type`, `slug`, `name`; `is_agent`, `can_login`, `is_kernel`.
- **Orchestration:** `paired_actor_id` (human orchestrator for IDE agents); `primary_federation_node_id`; `department_id`.
- **Paths and namespace:** `actor_root_path`, `workspace_path`, `php_namespace`.
- **Soft delete:** `is_deleted`, `deleted_ymdhis`; `is_active`.
- **Metadata:** `metadata` (text), `metadata_json` (json), `identity_provider_config` (json), `avatar_hash`.
- **Reserved-ID doctrine:** No AUTO_INCREMENT; IDs from registry or explicit allocation. Insert/update by explicit ID.

### 1.2 Auth and humans: `lupo_auth_users`

- **Primary key:** `auth_user_id` (bigint).
- **Unique:** `username`; (`auth_provider`, `provider_id`).
- **Columns:** `display_name`, `email`, `password_hash`, `profile_image_url`, `last_login_ymdhis`, `is_active`, soft delete.
- Maps to human orchestrators; actors (e.g. IDE agents) reference `paired_actor_id` to the human’s actor.

### 1.3 AI agents: `lupo_agents`

- **Primary key:** `agent_id` (bigint).
- **Unique:** `agent_key` (varchar 100).
- **Columns:** `agent_name`, `archetype`, `description`, `version`, `model_name`; `is_global_authority`, `is_internal_only`; provider/API and safety fields; `system_prompt`; Pono/Pilau/Kapakai scores and kapu state.
- Separate from `lupo_actors`; actors can be “agent” type and link to agent config.

### 1.4 Actor–channel membership: `lupo_actor_channels`

- **Primary key:** `actor_channel_id` (bigint).
- **Unique:** (`actor_id`, `channel_id`).
- **Columns:** `actor_id`, `actor_name`, `channel_id`, `created_by_actor_id`; `status`, `start_date`; `channel_color`, `last_read_ymdhis`, `muted_until_ymdhis`; `preferences_json`, `dialog_output_file`; soft delete.

### 1.5 Actor–channel roles: `lupo_actor_channel_roles`

- **Primary key:** `actor_channel_role_id` (bigint).
- **Columns:** `actor_id`, `actor_name`, `channel_id`, `role_key`; handshake/awareness fields (`handshake_metadata_json`, `awareness_snapshot_json`, `protocol_completion_status`, `protocol_version`, `join_sequence_step`, `*_completed_ymdhis`); soft delete.

### 1.6 Actor–actor graph: `lupo_actor_edges`

- **Primary key:** `actor_edge_id` (bigint).
- **Unique:** (`domain_id`, `source_actor_id`, `target_actor_id`, `edge_type`).
- **Columns:** `domain_id`, `source_actor_id`, `target_actor_id`, `edge_type`, `weight`, `properties` (text); soft delete.
- Used for delegation, trust, and semantic relationships between actors within a domain.

### 1.7 Actor–collection access: `lupo_actor_collections`

- **Primary key:** `actor_collection_id` (bigint).
- **Columns:** `actor_id`, `collection_id`, `access_level` (default `read`), `trust_level`, `persistent_identity_json`, `identity_signature`; `emotional_geometry_baseline` (json), `doctrine_alignment_version`; soft delete.
- Links actors to collections with access level and optional identity/trust metadata.

### 1.8 Actor apps (paths): `lupo_actor_apps`

- **Primary key:** `actor_app_id` (bigint).
- **Unique:** `actor_id` (one row per actor).
- **Columns:** `actor_id`, `apps_path` (varchar 512), `updated_ymdhis`.
- Per-actor app path configuration.

---

## 2. Collections

### 2.1 Collections root: `lupo_collections`

- **Primary key:** `collection_id` (bigint).
- **Unique:** (`federation_node_id`, `slug`).
- **Columns:** `federation_node_id`, `actor_id`, `department_id`, `channel_id`, `parent_id`; `name`, `slug`, `color`, `description`, `sort_order`, `properties` (text); `published_ymdhis`; `is_nav_menu`, `nav_icon`; soft delete.
- Collections are scoped by federation node; can be tied to an actor, department, or channel, and can be hierarchical (`parent_id`).

### 2.2 Collection membership (generic): `lupo_collection_map`

- **Primary key:** `collection_map_id` (bigint).
- **Columns:** `collection_id`, `object_type` (varchar 64), `object_id` (bigint), `sort_order`; soft delete.
- Polymorphic: any entity type can be linked into a collection by `object_type` + `object_id`.

### 2.3 Collection links (URLs): `lupo_collection_links`

- **Primary key:** `collection_link_id` (bigint).
- **Columns:** `collection_id`, `link_url` (varchar 2000), `link_label`, `sort_order`; soft delete.
- External or internal links attached to a collection.

### 2.4 Tabs within a collection: `lupo_collection_tabs`

- **Primary key:** `collection_tab_id` (bigint).
- **Columns:** `collection_tab_parent_id` (self-ref for hierarchy), `collection_id`, `federations_node_id`, `department_id`, `actor_id`; `sort_order`, `name`, `slug`, `color`, `description`; `is_hidden`, `visibility_rule`, `tab_type`; `is_active`; soft delete.
- Tabs can be nested; optionally scoped by department/actor.

### 2.5 Tab contents (polymorphic): `lupo_collection_tab_map`

- **Primary key:** `collection_tab_map_id` (bigint).
- **Unique:** (`collection_tab_id`, `item_type`, `item_id`).
- **Columns:** `collection_tab_id`, `federations_node_id`, `item_type` (varchar 20), `item_id` (bigint), `sort_order`, `properties` (text); soft delete.
- Items in a tab are generic: `item_type` + `item_id`.

### 2.6 Tab path (breadcrumb/depth): `lupo_collection_tab_paths`

- **Primary key:** `collection_tab_path_id` (bigint).
- **Unique:** (`collection_id`, `collection_tab_id`, `path`).
- **Columns:** `collection_id`, `collection_tab_id`, `path` (varchar 500), `depth` (int); soft delete.
- Stores path/depth for tab hierarchy and navigation.

---

## 3. Organization

### 3.1 Registry: `lupo_registry`

- **Primary key:** `registry_id` (bigint).
- **Unique:** (`entity_type`, `entity_index_id`, `federation_node_id`).
- **Columns:** `entity_type` (varchar 50), `entity_index_id`, `entity_index`, `federation_node_id`; `reserved_ymdhis`; `entity_key`, `entity_name`, `entity_table`; `metadata` (text), `metadata_json` (text); `is_active`, `is_kernel`; soft delete.
- Global registry for reserved IDs and entity identity (actors, channels, etc.) per federation node. Code must allocate/lookup IDs via registry, not AUTO_INCREMENT, for registry-backed tables.

### 3.2 Federation nodes: `lupo_federation_nodes`

- **Primary key:** `federation_node_id` (bigint).
- **Columns:** `node_type` (default `local`), `node_base_url`, `default_department_id`; `node_name`, `node_description`, `node_contact`; `allows_foreign_traits`; `content_count`, `atom_count`, `hashtag_count`, `actor_count`, `last_sync_ymdhis`; `trust_level`, `status`; `active_theme_slug`; `meta_json` (json); soft delete.
- Top-level organization: each node is a deployment/domain; actors, channels, and collections are scoped by `federation_node_id` (local default 1).

### 3.3 Departments: `lupo_departments`

- **Primary key:** `department_id` (bigint).
- **Columns:** `federation_node_id`, `name`, `description`, `department_type` (default `general`), `default_actor_id`; `settings_json` (json); soft delete.
- Organizational unit within a node; referenced by actors, channels, and collection tabs.

### 3.4 Channels: `lupo_channels`

- **Primary key:** `channel_id` (bigint).
- **Unique:** (`channel_key`, `federation_node_id`).
- **Columns:** `federation_node_id`, `created_by_actor_id`, `default_actor_id`, `department_id`; `channel_key`, `channel_slug`, `channel_type` (default `chat_room`), `language`; `channel_name`, `description`, `website_link`; `metadata_json`, `aal_metadata_json` (json), `fleet_composition_json` (json); `status_flag`, `end_ymdhis`, `duration_seconds`; `awareness_version`, `channel_number`, `parent_channel_id`; `is_kernel`, `boot_sequence_order`; soft delete.
- Channels are the main conversation/context containers; collections can be linked via `lupo_collections.channel_id`.

---

## 4. How It Fits Together

| Concept | Tables | Notes |
|--------|--------|------|
| **Actor identity** | `lupo_actors`, `lupo_auth_users`, `lupo_agents` | Actors by name/id; humans in auth_users; AI config in agents. |
| **Actor placement** | `lupo_actor_channels`, `lupo_actor_channel_roles`, `lupo_actor_collections`, `lupo_actor_edges` | Which channels/collections an actor is in; roles; actor–actor graph. |
| **Collections** | `lupo_collections`, `lupo_collection_map`, `lupo_collection_links`, `lupo_collection_tabs`, `lupo_collection_tab_map`, `lupo_collection_tab_paths` | Collection root → map (objects) + links (URLs) + tabs (nested) → tab_map (items) + tab_paths (paths). |
| **Organization** | `lupo_registry`, `lupo_federation_nodes`, `lupo_departments`, `lupo_channels` | Registry for IDs; nodes → departments; channels as first-class scopes. |

- **Scoping:** Most entities are scoped by `federation_node_id`. Collections and channels can also be scoped by `department_id` and/or `actor_id`.
- **Reserved IDs:** For `lupo_actors`, `lupo_channels`, `lupo_auth_users`, and other registry-backed tables, IDs come from `lupo_registry` or explicit allocation; no reliance on AUTO_INCREMENT or `lastInsertId()`.
- **Doctrine:** All TOONs mark `no_foreign_keys: true`, `no_triggers: true`. Timestamps are BIGINT UTC YmdHis; set in application code.

---

## 5. TOON Location and Regeneration

- **Path:** `lupo-database/lupopedia/toon/*.toon.json`.
- **Source of truth:** Install SQL (e.g. `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`); TOONs are generated from it (e.g. via `lupo-scripts/generate_toon_from_sql.py`). Do not hand-edit TOONs for schema changes; change install SQL and regenerate.

This document reflects the TOON state at the time of writing; align code and docs to these structures and to the reserved-ID and doctrine rules in the repo.
