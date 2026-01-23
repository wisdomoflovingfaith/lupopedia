# Channel and Dialog System Schema Review

**Review Date:** 2026-01-14  
**Version:** 4.0.16  
**Source:** Toon files from `database/toon_data/`

This document provides a comprehensive schema review of all channel and dialog-related tables extracted from toon files.

---

## lupo_channels

**Purpose:** Channel definitions for multi-agent collaboration contexts

### Columns:
- `channel_id`: BIGINT NOT NULL AUTO_INCREMENT (Primary Key)
- `federation_node_id`: BIGINT NOT NULL (Domain/tenant identifier)
- `created_by_actor_id`: BIGINT NOT NULL (who made this channel)
- `default_actor_id`: BIGINT NOT NULL DEFAULT 1
- `channel_key`: VARCHAR(64) NOT NULL (URL-friendly identifier/slug)
- `channel_name`: VARCHAR(255) NOT NULL (Human-readable channel name)
- `description`: TEXT NULL (Channel description)
- `metadata_json`: TEXT NULL (JSON metadata for the channel)
- `bgcolor`: VARCHAR(6) NOT NULL DEFAULT 'FFFFFF'
- `status_flag`: TINYINT NOT NULL DEFAULT 1 (Status flag: 1=active, 0=inactive)
- `end_ymdhis`: BIGINT NULL (Channel end timestamp, NULL if ongoing)
- `duration_seconds`: INT NULL (Duration of the channel in seconds, if ended)
- `created_ymdhis`: BIGINT NOT NULL (Creation timestamp YYYYMMDDHHMMSS)
- `updated_ymdhis`: BIGINT NOT NULL (Last update timestamp YYYYMMDDHHMMSS)
- `is_deleted`: TINYINT NOT NULL DEFAULT 0 (Soft delete flag: 1=deleted, 0=active)
- `deleted_ymdhis`: BIGINT NULL (Deletion timestamp YYYYMMDDHHMMSS)

### Keys:
- **PK:** `channel_id` ✓ (matches expected name)
- **Unique:** None (but `channel_key` should be unique per federation_node_id)
- **Indexes:**
  - `idx_channel_key` on `channel_key` (non-unique)
  - `idx_dates` on `end_ymdhis`
  - `idx_domain` on `federation_node_id`
  - `idx_status` on `status_flag`

### Relationships:
- **Logical FK to `lupo_actors`:** `created_by_actor_id` → `lupo_actors.actor_id` (no FK constraint per doctrine)
- **Logical FK to `lupo_actors`:** `default_actor_id` → `lupo_actors.actor_id` (no FK constraint per doctrine)
- **Logical FK to `lupo_federation_nodes`:** `federation_node_id` → `lupo_federation_nodes.federation_node_id` (no FK constraint per doctrine)

### Notes:
- Channel 0 is reserved for system/kernel
- Channel 1 is reserved for system/lobby
- `channel_key` should be unique per `federation_node_id` but no unique constraint exists
- `bgcolor` uses 6-char hex format (no # prefix)
- Soft delete pattern follows WOLFIE doctrine

### Recommended Refinements:
- **Add unique constraint:** `UNIQUE KEY uk_channel_key_node (federation_node_id, channel_key)` to ensure channel_key uniqueness per node
- **Add comment to `channel_key`:** Clarify that it should be URL-friendly slug format
- **Consider index on `created_ymdhis`:** For chronological queries
- **Consider index on `is_deleted`:** For filtering active channels

---

## lupo_actors

**Purpose:** Registry of all entities that can perform actions (users, AI agents, services)

### Columns:
- `actor_id`: BIGINT NOT NULL AUTO_INCREMENT (Primary Key)
- `actor_type`: ENUM('user','ai_agent','service') NOT NULL (Type of actor)
- `slug`: VARCHAR(255) NOT NULL (Stable unique identifier)
- `name`: VARCHAR(255) NOT NULL (Human-readable name)
- `created_ymdhis`: BIGINT NOT NULL (UTC YYYYMMDDHHMMSS)
- `updated_ymdhis`: BIGINT NOT NULL (UTC YYYYMMDDHHMMSS)
- `is_active`: TINYINT NOT NULL DEFAULT 1
- `is_deleted`: TINYINT NOT NULL DEFAULT 0
- `deleted_ymdhis`: BIGINT NULL (UTC YYYYMMDDHHMMSS)
- `actor_source_id`: BIGINT NULL (ID from source table: auth_users, agents, etc.)
- `actor_source_type`: VARCHAR(20) NULL (Source type: user, agent, system)
- `metadata`: TEXT NULL (Optional JSON for additional actor attributes)

### Keys:
- **PK:** `actor_id` ✓ (matches expected name)
- **Unique:** `unique_slug` on `slug` (unique constraint)
- **Indexes:**
  - `idx_actor_type` on `actor_type`
  - `idx_created_ymdhis` on `created_ymdhis`
  - `idx_is_active` on `is_active`
  - `unique_slug` on `slug` (unique index)

### Relationships:
- **Logical FK to source tables:** `actor_source_id` + `actor_source_type` reference various source tables (no FK constraint per doctrine)
- **Referenced by:** `lupo_actor_channels.actor_id`, `lupo_dialog_threads.created_by_actor_id`, `lupo_dialog_messages.from_actor_id`, `lupo_dialog_messages.to_actor_id`

### Notes:
- Actor ID 0 is reserved for system-kernel
- Actor ID 1 is reserved for captain-wolfie
- Slug generation follows email-based algorithm (e.g., `lupopedia-at-gmail-com`)
- Soft delete pattern follows WOLFIE doctrine
- `actor_source_id` and `actor_source_type` enable polymorphic relationships

### Recommended Refinements:
- **Add comment to `slug`:** Document slug generation algorithm (email-based canonical format)
- **Consider index on `actor_source_id` + `actor_source_type`:** For source lookups
- **Consider index on `is_deleted`:** For filtering active actors
- **Add validation:** Ensure `actor_source_id` is provided when `actor_source_type` is not NULL

---

## lupo_actor_channels

**Purpose:** Many-to-many relationship between actors and channels, including per-actor channel preferences and status

### Columns:
- `actor_channel_id`: BIGINT NOT NULL AUTO_INCREMENT (Primary Key)
- `actor_id`: BIGINT NOT NULL (Reference to actor: user/agent)
- `channel_id`: BIGINT NOT NULL (Reference to channel)
- `status`: CHAR(1) NOT NULL DEFAULT 'A' (Status: A=Active, I=Inactive, etc.)
- `start_date`: BIGINT NOT NULL DEFAULT 0 (Timestamp when actor joined channel YYYYMMDDHHMMSS)
- `bg_color`: VARCHAR(6) NOT NULL DEFAULT '000000' (Background color: 6-char hex, no #)
- `text_color`: VARCHAR(6) NOT NULL DEFAULT '000000' (Text color: 6-char hex, no #)
- `channel_color`: VARCHAR(6) NOT NULL DEFAULT 'F7FAFF' (Channel-specific color: 6-char hex, no #)
- `alt_text_color`: VARCHAR(6) NOT NULL DEFAULT '000000' (Alternate text color: 6-char hex, no #)
- `last_read_ymdhis`: BIGINT NULL (Timestamp when actor last read messages YYYYMMDDHHMMSS)
- `muted_until_ymdhis`: BIGINT NULL (Timestamp until notifications are muted YYYYMMDDHHMMSS)
- `preferences_json`: JSON NULL (Additional UI/UX preferences in JSON format)
- `dialog_output_file`: VARCHAR(500) NULL (Filesystem dialog log path for this actor in this channel; IDE agents write here as mandatory fallback when database dialog tables unavailable)
- `created_ymdhis`: BIGINT NOT NULL (Creation timestamp YYYYMMDDHHMMSS)
- `updated_ymdhis`: BIGINT NOT NULL (Last update timestamp YYYYMMDDHHMMSS)
- `is_deleted`: TINYINT NOT NULL DEFAULT 0 (Soft delete flag: 1=deleted, 0=active)
- `deleted_ymdhis`: BIGINT NULL (Deletion timestamp YYYYMMDDHHMMSS)

### Keys:
- **PK:** `actor_channel_id` ✓ (matches expected name)
- **Unique:** `unq_actor_channel` on (`actor_id`, `channel_id`) (prevents duplicate memberships)
- **Indexes:**
  - `idx_actor` on `actor_id`
  - `idx_channel` on `channel_id`
  - `idx_created` on `created_ymdhis`
  - `idx_deleted` on `is_deleted`
  - `idx_status` on `status`
  - `idx_updated` on `updated_ymdhis`
  - `unq_actor_channel` on (`actor_id`, `channel_id`) (unique index)

### Relationships:
- **Logical FK to `lupo_actors`:** `actor_id` → `lupo_actors.actor_id` (no FK constraint per doctrine)
- **Logical FK to `lupo_channels`:** `channel_id` → `lupo_channels.channel_id` (no FK constraint per doctrine)

### Notes:
- Unique constraint ensures one actor-channel relationship per pair
- `dialog_output_file` field added for IDE agent fallback behavior (filesystem dialog log path)
- Color fields use 6-char hex format (no # prefix)
- Soft delete pattern follows WOLFIE doctrine
- `start_date` default is 0 (should probably be NULL or actual timestamp)

### Recommended Refinements:
- **Fix `start_date` default:** Change DEFAULT 0 to NULL or use actual timestamp on insert
- **Add comment to `dialog_output_file`:** Document expected format: `dialogs/<channel_name>_dialog.md`
- **Consider index on `last_read_ymdhis`:** For unread message queries
- **Consider index on `muted_until_ymdhis`:** For notification filtering
- **Add validation:** Ensure `dialog_output_file` follows expected path pattern when provided

---

## lupo_actor_channel_roles

**Purpose:** Role assignments for actors within channels

### Columns:
- `actor_channel_role_id`: BIGINT NOT NULL AUTO_INCREMENT (Primary Key)
- `actor_id`: BIGINT NOT NULL
- `channel_id`: BIGINT NOT NULL
- `role_key`: VARCHAR(64) NOT NULL
- `created_ymdhis`: BIGINT NOT NULL
- `updated_ymdhis`: BIGINT NOT NULL
- `is_deleted`: TINYINT(1) NOT NULL DEFAULT 0
- `deleted_ymdhis`: BIGINT NULL

### Keys:
- **PK:** `actor_channel_role_id` ✓ (matches expected name)
- **Unique:** None (multiple roles per actor-channel pair allowed)
- **Indexes:**
  - `idx_actor_id` on `actor_id`
  - `idx_channel_id` on `channel_id`
  - `idx_role_key` on `role_key`

### Relationships:
- **Logical FK to `lupo_actors`:** `actor_id` → `lupo_actors.actor_id` (no FK constraint per doctrine)
- **Logical FK to `lupo_channels`:** `channel_id` → `lupo_channels.channel_id` (no FK constraint per doctrine)
- **Logical FK to `lupo_actor_channels`:** (`actor_id`, `channel_id`) → `lupo_actor_channels` (no FK constraint per doctrine)

### Notes:
- No unique constraint on (`actor_id`, `channel_id`, `role_key`) — allows multiple role assignments
- Missing column comments — should document purpose of each field
- `TINYINT(1)` syntax is MySQL-specific (should be `TINYINT` per doctrine)
- Soft delete pattern follows WOLFIE doctrine

### Recommended Refinements:
- **Add column comments:** Document purpose of each field
- **Fix `TINYINT(1)` syntax:** Change to `TINYINT` for consistency
- **Consider unique constraint:** `UNIQUE KEY uk_actor_channel_role (actor_id, channel_id, role_key)` if duplicate roles should be prevented
- **Add composite index:** `idx_actor_channel` on (`actor_id`, `channel_id`) for role lookups
- **Add comment to `role_key`:** Document expected role key values/format

---

## lupo_dialog_threads

**Purpose:** High-level dialog threads grouping messages across agents, tasks, and projects

### Columns:
- `dialog_thread_id`: BIGINT NOT NULL AUTO_INCREMENT (Primary Key)
- `federation_node_id`: BIGINT NOT NULL DEFAULT 1 (Node that owns this thread; default is local installation 1)
- `channel_id`: BIGINT NULL (Optional channel identifier for grouping threads)
- `project_slug`: VARCHAR(100) NULL (Project or subsystem this thread belongs to)
- `task_name`: VARCHAR(255) NULL (Human-readable task name for this thread)
- `created_by_actor_id`: BIGINT NOT NULL (Agent or human who initiated the thread)
- `summary_text`: TEXT NULL (Short summary of the thread purpose or context)
- `status`: ENUM('Open','Ongoing','Closed','Archived') NOT NULL DEFAULT 'Open' (Thread lifecycle state)
- `artifacts`: JSON NULL (Optional JSON list of related files, URLs, or resources)
- `metadata_json`: JSON NULL (Metadata: intent, scope, persona, mood, inline dialog metadata)
- `created_ymdhis`: BIGINT NOT NULL (Creation timestamp YYYYMMDDHHMMSS)
- `updated_ymdhis`: BIGINT NOT NULL (Last update timestamp YYYYMMDDHHMMSS)
- `is_deleted`: TINYINT NOT NULL DEFAULT 0 (Soft delete flag: 1=deleted)
- `deleted_ymdhis`: BIGINT NULL (Deletion timestamp YYYYMMDDHHMMSS)

### Keys:
- **PK:** `dialog_thread_id` ✓ (matches expected name)
- **Unique:** None
- **Indexes:**
  - `idx_agent_name` on `created_by_actor_id` (misnamed — should be `idx_created_by_actor`)
  - `idx_channel` on `channel_id`
  - `idx_created` on `created_ymdhis`
  - `idx_deleted` on `is_deleted`
  - `idx_node` on `federation_node_id`
  - `idx_project` on `project_slug`
  - `idx_status` on `status`
  - `idx_task` on `task_name`
  - `idx_updated` on `updated_ymdhis`

### Relationships:
- **Logical FK to `lupo_federation_nodes`:** `federation_node_id` → `lupo_federation_nodes.federation_node_id` (no FK constraint per doctrine)
- **Logical FK to `lupo_channels`:** `channel_id` → `lupo_channels.channel_id` (no FK constraint per doctrine)
- **Logical FK to `lupo_actors`:** `created_by_actor_id` → `lupo_actors.actor_id` (no FK constraint per doctrine)
- **Referenced by:** `lupo_dialog_messages.dialog_thread_id`

### Notes:
- Threads are one-on-one conversations stored in database (vs channels which are multi-agent collaboration contexts in filesystem)
- `channel_id` is optional — threads can exist without channel association
- Index `idx_agent_name` is misnamed (should reference `created_by_actor_id`, not "agent_name")
- Soft delete pattern follows WOLFIE doctrine
- Status enum provides thread lifecycle management

### Recommended Refinements:
- **Rename index:** `idx_agent_name` → `idx_created_by_actor` for clarity
- **Add composite index:** `idx_channel_status` on (`channel_id`, `status`) for channel thread queries
- **Add composite index:** `idx_actor_status` on (`created_by_actor_id`, `status`) for actor thread queries
- **Consider index on `project_slug` + `task_name`:** For project/task-based queries
- **Add comment to `channel_id`:** Clarify that NULL means thread is not associated with a channel

---

## lupo_dialog_messages

**Purpose:** Individual messages within dialog threads

### Columns:
- `dialog_message_id`: BIGINT NOT NULL AUTO_INCREMENT (Primary Key)
- `dialog_thread_id`: BIGINT NULL (Optional thread grouping for related dialogs)
- `channel_id`: BIGINT NULL (Optional channel identifier)
- `from_actor_id`: BIGINT NULL (Actor ID of the message sender)
- `to_actor_id`: BIGINT NULL (Actor ID of the message recipient)
- `message_text`: VARCHAR(1000) NOT NULL (The message under 1000 chars)
- `message_type`: ENUM('text','command','system','error') NOT NULL DEFAULT 'text' (Type of message)
- `metadata_json`: JSON NULL (Additional message metadata)
- `mood_rgb`: CHAR(6) NOT NULL DEFAULT '666666' (Mood color: 6-char hex)
- `weight`: DECIMAL(3,2) NOT NULL DEFAULT 0.00
- `created_ymdhis`: BIGINT NOT NULL (Creation timestamp YYYYMMDDHHMMSS)
- `updated_ymdhis`: BIGINT NOT NULL (Last update timestamp YYYYMMDDHHMMSS)
- `is_deleted`: TINYINT NOT NULL DEFAULT 0 (Soft delete flag: 1=deleted)
- `deleted_ymdhis`: BIGINT NULL (Deletion timestamp YYYYMMDDHHMMSS)

### Keys:
- **PK:** `dialog_message_id` ✓ (matches expected name)
- **Unique:** None
- **Indexes:**
  - `idx_channel` on `channel_id`
  - `idx_created` on `created_ymdhis`
  - `idx_deleted` on `is_deleted`
  - `idx_dialog_thread_id` on `dialog_thread_id`
  - `idx_message_type` on `message_type`
  - `idx_to_actor_id` on `to_actor_id`
  - `idx_updated` on `updated_ymdhis`

### Relationships:
- **Logical FK to `lupo_dialog_threads`:** `dialog_thread_id` → `lupo_dialog_threads.dialog_thread_id` (no FK constraint per doctrine)
- **Logical FK to `lupo_channels`:** `channel_id` → `lupo_channels.channel_id` (no FK constraint per doctrine)
- **Logical FK to `lupo_actors`:** `from_actor_id` → `lupo_actors.actor_id` (no FK constraint per doctrine)
- **Logical FK to `lupo_actors`:** `to_actor_id` → `lupo_actors.actor_id` (no FK constraint per doctrine)
- **Referenced by:** `lupo_dialog_message_bodies.dialog_message_id`

### Notes:
- `message_text` limited to 1000 characters — longer messages use `lupo_dialog_message_bodies`
- Comment on `from_actor_id` says "recipient" but should say "sender" (inconsistency)
- Comment on `to_actor_id` says "Agent ID if message is from an AI agent" but should say "recipient actor ID" (inconsistency)
- Both `dialog_thread_id` and `channel_id` are optional — messages can exist independently
- `mood_rgb` uses 6-char hex format (no # prefix)
- Soft delete pattern follows WOLFIE doctrine

### Recommended Refinements:
- **Fix comment on `from_actor_id`:** Change "recipient" to "sender"
- **Fix comment on `to_actor_id`:** Change to "Actor ID of the message recipient"
- **Add index on `from_actor_id`:** For sender-based queries
- **Add composite index:** `idx_thread_created` on (`dialog_thread_id`, `created_ymdhis`) for thread message ordering
- **Add composite index:** `idx_channel_created` on (`channel_id`, `created_ymdhis`) for channel message ordering
- **Add composite index:** `idx_to_actor_created` on (`to_actor_id`, `created_ymdhis`) for inbox queries
- **Consider index on `from_actor_id` + `to_actor_id`:** For direct message queries

---

## lupo_dialog_message_bodies

**Purpose:** Full long-form message content for messages exceeding 1000 characters

### Columns:
- `dialog_message_body_id`: BIGINT NOT NULL AUTO_INCREMENT (Primary Key)
- `dialog_message_id`: BIGINT NOT NULL (Links to dialog_messages entry; no FK by doctrine)
- `full_text`: LONGTEXT NOT NULL (Full long-form message content)
- `metadata_json`: JSON NOT NULL
- `mood_rgb`: CHAR(6) NOT NULL DEFAULT '666666' (Mood color: 6-char hex)
- `weight`: DECIMAL(3,2) NOT NULL DEFAULT 0.00
- `created_ymdhis`: BIGINT NOT NULL (Creation timestamp YYYYMMDDHHMMSS)
- `updated_ymdhis`: BIGINT NOT NULL (Last update timestamp YYYYMMDDHHMMSS)

### Keys:
- **PK:** `dialog_message_body_id` ✓ (matches expected name)
- **Unique:** None (but should be one-to-one with `lupo_dialog_messages`)
- **Indexes:**
  - `idx_created` on `created_ymdhis`
  - `idx_dialog_message_id` on `dialog_message_id`
  - `idx_updated` on `updated_ymdhis`

### Relationships:
- **Logical FK to `lupo_dialog_messages`:** `dialog_message_id` → `lupo_dialog_messages.dialog_message_id` (no FK constraint per doctrine)
- **One-to-one relationship:** Each message should have at most one body (enforced in application layer)

### Notes:
- No soft delete fields — body deletion handled via parent message deletion
- `metadata_json` is NOT NULL (unlike parent table where it's nullable)
- `mood_rgb` and `weight` duplicated from parent message (consider normalization)
- No unique constraint on `dialog_message_id` — should enforce one-to-one relationship

### Recommended Refinements:
- **Add unique constraint:** `UNIQUE KEY uk_dialog_message_id (dialog_message_id)` to enforce one-to-one relationship
- **Consider soft delete fields:** Add `is_deleted` and `deleted_ymdhis` for consistency
- **Consider normalization:** Remove `mood_rgb` and `weight` if they're always synced with parent message
- **Add comment to `metadata_json`:** Document expected structure/format
- **Consider full-text index:** On `full_text` for content search (if supported by database)

---

## Summary of Findings

### Strengths:
1. **Consistent naming:** All primary keys follow expected naming convention (`{table}_id`)
2. **Soft delete pattern:** All tables (except `lupo_dialog_message_bodies`) follow WOLFIE soft delete doctrine
3. **Timestamp format:** All timestamps use BIGINT UTC YYYYMMDDHHMMSS format
4. **No foreign keys:** Follows WOLFIE doctrine (no FK constraints)
5. **Comprehensive indexing:** Good coverage of common query patterns

### Issues Found:
1. **Missing unique constraint:** `lupo_channels.channel_key` should be unique per `federation_node_id`
2. **Incorrect comments:** `lupo_dialog_messages.from_actor_id` comment says "recipient" instead of "sender"
3. **Misnamed index:** `lupo_dialog_threads.idx_agent_name` should be `idx_created_by_actor`
4. **Missing unique constraint:** `lupo_dialog_message_bodies.dialog_message_id` should be unique (one-to-one relationship)
5. **Inconsistent nullability:** `lupo_dialog_message_bodies.metadata_json` is NOT NULL while parent table allows NULL
6. **Missing soft delete:** `lupo_dialog_message_bodies` lacks soft delete fields
7. **Default value issue:** `lupo_actor_channels.start_date` defaults to 0 instead of NULL or actual timestamp

### Recommended Actions:
1. Add unique constraint on `lupo_channels(channel_key, federation_node_id)`
2. Fix comment on `lupo_dialog_messages.from_actor_id`
3. Rename index `lupo_dialog_threads.idx_agent_name` to `idx_created_by_actor`
4. Add unique constraint on `lupo_dialog_message_bodies.dialog_message_id`
5. Consider adding soft delete fields to `lupo_dialog_message_bodies`
6. Fix `lupo_actor_channels.start_date` default value
7. Add composite indexes for common query patterns (channel+status, actor+status, thread+created, etc.)

---

*Review completed: January 14, 2026*  
*Version: 4.0.16*
