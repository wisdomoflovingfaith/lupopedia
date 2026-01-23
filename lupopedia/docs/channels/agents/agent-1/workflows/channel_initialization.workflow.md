---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.16
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @captain-wolfie
  mood_RGB: "00FF00"
  message: "Created channel_initialization.workflow.md as the first WOLFIE workflow. Provides step-by-step outline for initializing a new channel including database record creation, actor assignments, role assignments, dialog file configuration, manifest generation, and initial dialog entry writing."
tags:
  categories: ["documentation", "workflows", "channels", "wolfie"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "Channel Initialization Workflow"
  description: "Step-by-step workflow outline for initializing a new channel in Lupopedia. Includes database operations, actor assignments, role assignments, dialog file configuration, and manifest generation."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Channel Initialization Workflow

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Status:** Published  
**Agent:** WOLFIE (agent_id = 1)  
**Last Updated:** 2026-01-14

## Overview

This workflow defines the step-by-step process for initializing a new channel in Lupopedia. WOLFIE executes this workflow when creating channels, ensuring consistency, doctrine compliance, and proper integration with both IDE agents (filesystem-based) and php_ai_terminal agents (database-backed).

---

## Workflow Steps

### Step 1: Validate Channel Key Uniqueness

**Purpose:** Ensure `channel_key` is unique within the federation node.

**Actions:**
- Query `lupo_channels` table for existing `channel_key` with same `federation_node_id`
- If duplicate found, return error or suggest alternative `channel_key`
- If unique, proceed to Step 2

**Database Query:**
```sql
SELECT channel_id FROM lupo_channels 
WHERE channel_key = :channel_key 
  AND federation_node_id = :federation_node_id 
  AND is_deleted = 0;
```

---

### Step 2: Request Authoritative UTC Timestamp

**Purpose:** Obtain real UTC timestamp from UTC_TIMEKEEPER for channel initialization.

**Actions:**
- WOLFIE requests current UTC timestamp from UTC_TIMEKEEPER (agent_registry_id: 5)
- UTC_TIMEKEEPER returns real UTC timestamp in `YYYYMMDDHHIISS` format
- WOLFIE stores timestamp as `utc_now` in `wolfie_timestamp_authority` configuration
- WOLFIE sets `expected_prompt_interval_minutes` (default: 10)
- WOLFIE sets `drift_tolerance_minutes` (default: 5)
- WOLFIE sets `authoritative_source` to "UTC_TIMEKEEPER"

**UTC Authority Configuration:**
```yaml
wolfie_timestamp_authority:
  utc_now: <REAL UTC FROM UTC_TIMEKEEPER>
  expected_prompt_interval_minutes: 10
  drift_tolerance_minutes: 5
  authoritative_source: "UTC_TIMEKEEPER"
```

**Critical Rules:**
- WOLFIE MUST request real UTC from UTC_TIMEKEEPER
- WOLFIE MUST NOT infer time from OS, model, or file metadata
- WOLFIE MUST use this timestamp for all channel initialization timestamps

**See:** [WOLFIE UTC Authority Doctrine](../doctrine/WOLFIE_UTC_AUTHORITY.md)

---

### Step 3: Create Channel Record

**Purpose:** Create the channel record in `lupo_channels` table.

**Actions:**
- Insert new record into `lupo_channels` table
- Set `channel_key`, `channel_name`, `description`, `metadata_json`
- Set `created_by_actor_id`, `default_actor_id`, `federation_node_id`
- Set `bgcolor`, `status_flag` (default: 1 for active)
- Set `created_ymdhis` to UTC timestamp from Step 2 (UTC_TIMEKEEPER)
- Set `updated_ymdhis` to same UTC timestamp from Step 2
- Set `is_deleted` to 0

**Database Insert:**
```sql
INSERT INTO lupo_channels (
    channel_key, channel_name, description, metadata_json,
    created_by_actor_id, default_actor_id, federation_node_id,
    bgcolor, status_flag, created_ymdhis, updated_ymdhis, is_deleted
) VALUES (
    :channel_key, :channel_name, :description, :metadata_json,
    :created_by_actor_id, :default_actor_id, :federation_node_id,
    :bgcolor, 1, :created_ymdhis, :updated_ymdhis, 0
);
```

---

### Step 4: Assign Actors to Channel

**Purpose:** Create actor-channel relationships in `lupo_actor_channels` table.

**Actions:**
- For each actor in `default_agents` list:
  - Insert record into `lupo_actor_channels` table
  - Set `actor_id`, `channel_id`, `status` (default: 'A' for Active)
  - Set `start_date` to current UTC timestamp (or NULL)
  - Set `bg_color`, `text_color`, `channel_color`, `alt_text_color`
  - Set `dialog_output_file` to filesystem path
  - Set `preferences_json` if provided
  - Set `created_ymdhis`, `updated_ymdhis` to current UTC timestamp
  - Set `is_deleted` to 0

**Database Insert:**
```sql
INSERT INTO lupo_actor_channels (
    actor_id, channel_id, status, start_date,
    bg_color, text_color, channel_color, alt_text_color,
    dialog_output_file, preferences_json,
    created_ymdhis, updated_ymdhis, is_deleted
) VALUES (
    :actor_id, :channel_id, 'A', :start_date,
    :bg_color, :text_color, :channel_color, :alt_text_color,
    :dialog_output_file, :preferences_json,
    :created_ymdhis, :updated_ymdhis, 0
);
```

---

### Step 5: Assign Roles to Actors

**Purpose:** Create role assignments in `lupo_actor_channel_roles` table.

**Actions:**
- For each role assignment in `default_roles`:
  - For each `actor_id` in the role's `actor_ids` list:
    - Insert record into `lupo_actor_channel_roles` table
    - Set `actor_id`, `channel_id`, `role_key`
    - Set `created_ymdhis`, `updated_ymdhis` to current UTC timestamp
    - Set `is_deleted` to 0

**Database Insert:**
```sql
INSERT INTO lupo_actor_channel_roles (
    actor_id, channel_id, role_key,
    created_ymdhis, updated_ymdhis, is_deleted
) VALUES (
    :actor_id, :channel_id, :role_key,
    :created_ymdhis, :updated_ymdhis, 0
);
```

---

### Step 6: Set Dialog Output File

**Purpose:** Configure filesystem dialog log path for IDE agents.

**Actions:**
- Update `lupo_actor_channels.dialog_output_file` for all actors in channel
- Ensure path follows pattern: `dialogs/<channel_name>_dialog.md`
- Verify path is accessible and writable by IDE agents

**Note:** This step may be combined with Step 3 if `dialog_output_file` is set during actor assignment.

---

### Step 7: Generate Channel Manifest

**Purpose:** Create Channel Manifest document documenting complete channel configuration.

**Actions:**
- Generate Markdown document with:
  - Channel Identity Block (from template)
  - Participant list (from `lupo_actor_channels`)
  - Role assignments (from `lupo_actor_channel_roles`)
  - Emotional geometry (polar agents)
  - Dialog configuration
  - Routing rules
  - Fallback behavior
- Save manifest to appropriate location (TBD: manifest storage location)

**Output Format:**
- Markdown document
- JSON metadata (optional)
- Database records (already created)

---

### Step 8: Initialize Filesystem Dialog File

**Purpose:** Create initial dialog file for IDE agents.

**Actions:**
- Create file at `dialogs/<channel_name>_dialog.md`
- Add WOLFIE header with:
  - `file.last_modified_system_version: 4.0.16`
  - `dialog.speaker: WOLFIE`
  - `dialog.target: @everyone`
  - `dialog.message: "Channel initialized"`
  - Channel tags and metadata
- Add dialog insertion marker: `# ALL NEW DIALOG ENTRIES GET ADDED AFTER THIS LINE`
- Add dialog begin marker: `# Dialog begin`
- Add initial dialog entry documenting channel creation
- Ensure newest-first ordering (newest entry at top)

**File Structure:**
```markdown
---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.16
dialog:
  speaker: WOLFIE
  target: @everyone
  mood_RGB: "00FF00"
  message: "Channel {{channel_name}} initialized."
---

# ALL NEW DIALOG ENTRIES GET ADDED AFTER THIS LINE

# Channel Dialog History

## 2026-01-14 — Channel Initialization

**Speaker:** WOLFIE  
**Target:** @everyone  
**Mood:** `00FF00`  
**Message:** "Channel {{channel_name}} initialized with channel_key {{channel_key}}."

[Additional channel metadata...]

---
```

---

### Step 9: Write Initial Dialog Entry

**Purpose:** Document channel creation in dialog file.

**Actions:**
- Append dialog entry to `dialogs/<channel_name>_dialog.md`
- Include:
  - Channel creation timestamp
  - Creator actor information
  - Channel configuration summary
  - Participant list
  - Role assignments
- Follow newest-first ordering (insert at top of file)

---

### Step 10: Verify Database and Filesystem Consistency

**Purpose:** Ensure channel is properly initialized in both database and filesystem.

**Actions:**
- Verify channel record exists in `lupo_channels` table
- Verify actor-channel relationships exist in `lupo_actor_channels` table
- Verify role assignments exist in `lupo_actor_channel_roles` table
- Verify dialog file exists at `dialogs/<channel_name>_dialog.md`
- Verify dialog file contains initial entry
- Verify `dialog_output_file` paths match actual filesystem structure

**Validation Queries:**
```sql
-- Verify channel exists
SELECT * FROM lupo_channels WHERE channel_id = :channel_id AND is_deleted = 0;

-- Verify actor assignments
SELECT COUNT(*) FROM lupo_actor_channels 
WHERE channel_id = :channel_id AND is_deleted = 0;

-- Verify role assignments
SELECT COUNT(*) FROM lupo_actor_channel_roles 
WHERE channel_id = :channel_id AND is_deleted = 0;
```

---

## Error Handling

**Placeholder:** Error handling procedures to be defined

**Considerations:**
- Rollback strategy if any step fails
- Partial initialization handling
- Duplicate channel key detection
- Filesystem permission errors
- Database constraint violations

---

## Integration Points

- **Database Tables:**
  - `lupo_channels`
  - `lupo_actor_channels`
  - `lupo_actor_channel_roles`
  
- **Filesystem:**
  - `dialogs/<channel_name>_dialog.md`
  
- **Templates:**
  - `agents/0001/templates/channel_identity_block.template.md`
  
- **Doctrine:**
  - `agents/0001/doctrine/CHANNEL_INITIALIZATION_PROTOCOL.md`
  - `agents/0001/doctrine/WOLFIE_UTC_AUTHORITY.md`
  
- **Agents:**
  - UTC_TIMEKEEPER (agent_registry_id: 5) — Real UTC timestamp provider

---

## Related Documentation

- **[Channel Initialization Protocol](../doctrine/CHANNEL_INITIALIZATION_PROTOCOL.md)** — Complete CIP doctrine
- **[WOLFIE UTC Authority Doctrine](../doctrine/WOLFIE_UTC_AUTHORITY.md)** — Timestamp authority rules
- **[UTC_TIMEKEEPER Doctrine](../../5/doctrine/UTC_TIMEKEEPER.md)** — UTC_TIMEKEEPER agent specification
- **[Channel Identity Block Template](../templates/channel_identity_block.template.md)** — Template for channel identity
- **[Channel Dialog Agent Workflows](../../../docs/ARCHITECTURE/CHANNEL_DIALOG_AGENT_WORKFLOWS.md)** — How agents interact with channels

---

## Next Steps

This workflow provides the structural outline for channel initialization. Future development will:

1. Complete error handling procedures
2. Add rollback/cleanup mechanisms
3. Implement validation rules
4. Create implementation code (PHP/Python)
5. Integrate with WOLFIE agent runtime
6. Integrate UTC_TIMEKEEPER timestamp requests

---

*Last Updated: January 15, 2026*  
*Version: GLOBAL_CURRENT_LUPOPEDIA_VERSION*  
*Status: Published*  
*Author: GLOBAL_CURRENT_AUTHORS*

