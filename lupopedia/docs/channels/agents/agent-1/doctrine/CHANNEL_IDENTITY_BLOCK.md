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
  message: "Created CHANNEL_IDENTITY_BLOCK.md as complete doctrine specification for Channel Identity Blocks. Defines all required and optional fields, validation rules, emotional geometry hooks, routing hooks, fallback behavior, manifest mapping, and integration points. This is the foundation for WOLFIE's channel initialization authority."
tags:
  categories: ["documentation", "doctrine", "channels", "wolfie"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
file:
  title: "Channel Identity Block (CIB) Doctrine Specification"
  description: "Complete doctrine specification for Channel Identity Blocks (CIB), the birth certificate of every channel in Lupopedia. Defines identity, metadata, emotional poles, routing behavior, and fallback rules used by WOLFIE during channel initialization."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# CHANNEL IDENTITY BLOCK (CIB) — Doctrine Specification

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Status:** Published  
**Agent:** WOLFIE (agent_id = 1)  
**Last Updated:** 2026-01-14

## 1. Purpose

The Channel Identity Block (CIB) is the **birth certificate** of every channel in Lupopedia. It defines the channel's identity, metadata, emotional poles, routing behavior, and fallback rules. WOLFIE uses this block during channel initialization to ensure consistency, doctrine compliance, and proper integration with both IDE agents (filesystem-based) and php_ai_terminal agents (database-backed).

The CIB serves as:
- The single source of truth for channel configuration
- The foundation for channel manifest generation
- The reference for HERMES routing decisions
- The anchor for CADUCEUS emotional balancing
- The specification for IDE agent fallback behavior

---

## 2. Required Fields

All Channel Identity Blocks MUST include the following required fields:

### 2.1. `channel_key` (String)

**Purpose:** URL-friendly identifier (slug) for the channel  
**Type:** `VARCHAR(64)`  
**Format:** Lowercase, snake_case  
**Uniqueness:** Must be unique per `federation_node_id`  
**Validation Rules:**
- Must be lowercase only
- Must use snake_case (underscores, no spaces, no hyphens)
- Must be 1-64 characters
- Must match pattern: `^[a-z0-9_]+$`
- Cannot be reserved values: `0`, `1`, `system`, `kernel`, `lobby`

**Examples:**
- `dev/documentation`
- `routing_development`
- `lupopedia_4_0_16_channels_documentation_database_refinement`

**Database Mapping:** `lupo_channels.channel_key`

---

### 2.2. `channel_name` (String)

**Purpose:** Human-readable channel name  
**Type:** `VARCHAR(255)`  
**Format:** Title case or sentence case  
**Validation Rules:**
- Must be 1-255 characters
- Should be descriptive and clear
- May include spaces and punctuation

**Examples:**
- `Development Documentation Channel`
- `Routing Development`
- `Lupopedia 4.0.16 - Channels/Documentation/Database Refinement`

**Database Mapping:** `lupo_channels.channel_name`

---

### 2.3. `channel_description` (String)

**Purpose:** Channel purpose and scope  
**Type:** `TEXT`  
**Format:** Free-form text  
**Validation Rules:**
- May be NULL (not recommended)
- Should clearly describe channel purpose
- Should document scope and boundaries

**Examples:**
- `Channel for coordinating documentation work across IDE agents and php_ai_terminal agents.`
- `Development channel for routing subsystem architecture and implementation.`

**Database Mapping:** `lupo_channels.description`

---

### 2.4. `federation_node_id` (BigInt)

**Purpose:** Domain/tenant identifier for federation  
**Type:** `BIGINT(20) UNSIGNED`  
**Format:** Integer  
**Default:** `1` (local installation)  
**Validation Rules:**
- Must be positive integer
- Must reference valid `lupo_federation_nodes.federation_node_id`
- Cannot be NULL

**Database Mapping:** `lupo_channels.federation_node_id`

---

### 2.5. `dialog_output_file` (String)

**Purpose:** Filesystem path for IDE agent dialog logs  
**Type:** `VARCHAR(500)`  
**Format:** Relative path under `dialogs/` directory  
**Validation Rules:**
- Must end with `_dialog.md`
- Must be relative path (no absolute paths)
- Must follow pattern: `dialogs/<channel_name>_dialog.md`
- Must be unique per channel (one dialog file per channel)
- IDE agents MUST be able to write to this file

**Examples:**
- `dialogs/dev_documentation_dialog.md`
- `dialogs/routing_changelog.md`
- `dialogs/changelog_dialog.md`

**Database Mapping:** `lupo_channels.dialog_output_file` (stored in `lupo_actor_channels.dialog_output_file` per actor)

**Critical Rule:** All agents (IDE and php_ai_terminal) MUST be able to write to this file as a fallback when database dialog tables are unavailable.

---

### 2.6. `created_by_actor_id` (BigInt)

**Purpose:** Actor who created the channel  
**Type:** `BIGINT(20) UNSIGNED`  
**Format:** Integer  
**Validation Rules:**
- Must reference valid `lupo_actors.actor_id`
- Cannot be NULL
- Actor must exist in `lupo_actors` table

**Database Mapping:** `lupo_channels.created_by_actor_id`

---

### 2.7. `created_ymdhis` (BigInt)

**Purpose:** Channel creation timestamp  
**Type:** `BIGINT`  
**Format:** UTC timestamp in `YYYYMMDDHHIISS` format  
**Source:** Real UTC from UTC_TIMEKEEPER (via WOLFIE)  
**Validation Rules:**
- Must be valid UTC timestamp
- Must be in `YYYYMMDDHHIISS` format (14 digits)
- Cannot be NULL
- Must be current or past timestamp (not future)
- MUST be obtained from UTC_TIMEKEEPER by WOLFIE during channel initialization
- MUST NOT be inferred from OS, model, or file metadata

**UTC Authority:**
- WOLFIE requests real UTC from UTC_TIMEKEEPER during channel initialization
- WOLFIE embeds this timestamp in `created_ymdhis`
- All agents MUST use this timestamp as authoritative baseline
- Terminal agents MUST request real UTC from UTC_TIMEKEEPER for subsequent timestamps
- IDE agents MAY use approximated timestamps based on this baseline

**Examples:**
- `20260114233000` (2026-01-14 23:30:00 UTC)
- `20260115000000` (2026-01-15 00:00:00 UTC)

**Database Mapping:** `lupo_channels.created_ymdhis`

**See:** [WOLFIE UTC Authority Doctrine](./WOLFIE_UTC_AUTHORITY.md)

---

### 2.8. `updated_ymdhis` (BigInt)

**Purpose:** Last update timestamp  
**Type:** `BIGINT`  
**Format:** UTC timestamp in `YYYYMMDDHHIISS` format  
**Source:** Real UTC from UTC_TIMEKEEPER (via WOLFIE) or approximated (IDE agents)  
**Validation Rules:**
- Must be valid UTC timestamp
- Must be in `YYYYMMDDHHIISS` format (14 digits)
- Cannot be NULL
- Must be >= `created_ymdhis`
- Should be updated on every channel modification
- Terminal agents MUST use real UTC from UTC_TIMEKEEPER
- IDE agents MAY use approximated timestamps (see WOLFIE_UTC_AUTHORITY doctrine)
- MUST NOT be inferred from OS, model, or file metadata

**UTC Authority:**
- Initial value set to same as `created_ymdhis` (from UTC_TIMEKEEPER)
- Terminal agents MUST request real UTC from UTC_TIMEKEEPER for updates
- IDE agents MAY increment by `expected_prompt_interval_minutes` (default: 10 minutes)
- IDE agents MUST request new timestamp from WOLFIE if drift exceeds tolerance

**Database Mapping:** `lupo_channels.updated_ymdhis`

**See:** [WOLFIE UTC Authority Doctrine](./WOLFIE_UTC_AUTHORITY.md)

---

## 3. Optional Fields

Channel Identity Blocks MAY include the following optional fields:

### 3.1. `default_roles` (Array)

**Purpose:** Default role assignments for channel participants  
**Type:** Array of role keys  
**Format:** `["role_key_1", "role_key_2", ...]`  
**Validation Rules:**
- Each role_key must be valid per `lupo_actor_channel_roles` doctrine
- Role keys must be strings
- Array may be empty

**Examples:**
- `["channel_admin", "participant"]`
- `["observer", "contributor"]`

**Database Mapping:** Stored in `lupo_actor_channel_roles.role_key` per actor-channel pair

---

### 3.2. `default_agents` (Array)

**Purpose:** Default agent assignments for the channel  
**Type:** Array of agent IDs  
**Format:** `[agent_id_1, agent_id_2, ...]`  
**Validation Rules:**
- Each agent_id must reference valid `lupo_actors.actor_id`
- Agent must have `actor_type = 'ai_agent'`
- Array may be empty

**Examples:**
- `[1, 3, 4]` (WOLFIE, KIRO, CURSOR)
- `[1, 2]` (WOLFIE, CAPTAIN)

**Database Mapping:** Stored in `lupo_actor_channels` table with `actor_id` references

---

### 3.3. `emotional_poles` (Array)

**Purpose:** Polar agents for CADUCEUS emotional balancing  
**Type:** Array of pole identifiers  
**Format:** `[pole_a_agent_id, pole_b_agent_id]`  
**Validation Rules:**
- Must contain exactly 2 agent IDs
- Each agent_id must reference valid `lupo_actors.actor_id`
- Agents must have `actor_type = 'ai_agent'`
- Agents must be different (cannot be same agent)
- Agents must be assigned to the channel

**Examples:**
- `[3, 4]` (KIRO and CURSOR as polar agents)
- `[1, 2]` (WOLFIE and CAPTAIN as polar agents)

**Database Mapping:** Stored in channel metadata or `lupo_actor_channels` with special flag

**Emotional Geometry Integration:**
- CADUCEUS reads moods from these two polar agents
- CADUCEUS blends their emotional states to produce channel emotional current
- HERMES may use this emotional current as context for routing decisions

---

### 3.4. `metadata_json` (JSON Object)

**Purpose:** Additional channel metadata  
**Type:** JSON object  
**Format:** Valid JSON  
**Validation Rules:**
- Must be valid JSON if provided
- May contain any structure
- Should follow Lupopedia metadata governance doctrine

**Examples:**
```json
{
  "intent": "documentation",
  "scope": "channel_system",
  "priority": "high",
  "tags": ["channels", "documentation", "database"]
}
```

**Database Mapping:** `lupo_channels.metadata_json`

---

### 3.5. `tags` (Array)

**Purpose:** Channel tags for categorization  
**Type:** Array of strings  
**Format:** `["tag1", "tag2", ...]`  
**Validation Rules:**
- Each tag must be a string
- Tags should be lowercase, hyphenated
- Array may be empty

**Examples:**
- `["channels", "documentation", "database"]`
- `["routing", "hermes", "caduceus"]`

**Database Mapping:** Stored in `metadata_json` or separate tag table

---

### 3.6. `bgcolor` (String)

**Purpose:** Background color for channel UI  
**Type:** `VARCHAR(6)`  
**Format:** 6-character hex color (no # prefix)  
**Default:** `FFFFFF`  
**Validation Rules:**
- Must be 6-character hex string
- Must match pattern: `^[0-9A-Fa-f]{6}$`
- No # prefix allowed

**Examples:**
- `FFFFFF` (white)
- `F7FAFF` (light blue)
- `FF6600` (orange)

**Database Mapping:** `lupo_channels.bgcolor`

---

### 3.7. `status_flag` (TinyInt)

**Purpose:** Channel status (active/inactive)  
**Type:** `TINYINT`  
**Format:** Integer (1 = active, 0 = inactive)  
**Default:** `1`  
**Validation Rules:**
- Must be 0 or 1
- 1 = active channel
- 0 = inactive channel

**Database Mapping:** `lupo_channels.status_flag`

---

## 4. Emotional Geometry Hooks

The Channel Identity Block integrates with Lupopedia's emotional geometry system through the `emotional_poles` field.

### 4.1. Polar Agents

Each channel may define two **polar agents** (the "serpents" of the CADUCEUS metaphor):

- **Pole A:** First polar agent (agent_id)
- **Pole B:** Second polar agent (agent_id)

These agents represent opposite ends of the emotional spectrum for the channel.

### 4.2. CADUCEUS Integration

**CADUCEUS** (the emotional balancing subsystem) uses polar agents to:

1. **Read moods** from both polar agents
2. **Blend emotional states** to produce channel emotional current
3. **Compute channel mood** that other subsystems can use

**Critical Rule:** CADUCEUS balances moods, **not routes messages**. CADUCEUS is an emotional balancer, not a routing subsystem.

### 4.3. Emotional Current

The channel emotional current is computed by CADUCEUS and represents:
- The blended emotional state of the channel
- A vector that guides routing decisions
- Context for HERMES routing (optional)

**Note:** Emotional poles must map to valid agents in the emotional geometry doctrine. Each agent's mood is tracked per the MOOD_RGB_DOCTRINE.

---

## 5. Routing Hooks

The Channel Identity Block integrates with HERMES (the routing subsystem) through the `channel_key` and actor assignments.

### 5.1. HERMES Routing Rules

**HERMES** uses the following CIB fields for routing:

1. **`channel_key`:** Identifies the channel for routing context
2. **`default_agents`:** List of agents available for routing
3. **`emotional_poles`:** Optional context for mood-aware routing

**Routing Process:**
1. Message arrives in channel (identified by `channel_key`)
2. HERMES determines target agent based on:
   - Message content
   - Agent availability
   - Agent capabilities
   - Optional: CADUCEUS emotional current
3. HERMES routes message to selected agent

### 5.2. Fallback Behavior

When no agent is available or routing fails:

1. **IDE Agents:** Write to `dialog_output_file` (filesystem fallback)
2. **php_ai_terminal Agents:** Write to database dialog tables
3. **WOLFIE Escalation:** If routing fails completely, WOLFIE may:
   - Reroute to alternate agent
   - Queue message for later processing
   - Escalate to channel administrator

### 5.3. Dialog Output File Usage

**IDE agents** use `dialog_output_file` as:
- **Primary write target:** All dialog entries written here
- **Fallback read source:** May read from this file when database unavailable
- **Required capability:** MUST be able to write to this file

**Format:** Standard dialog block format with WOLFIE headers, newest-first ordering.

---

## 6. Fallback Behavior

The Channel Identity Block defines fallback behavior for different agent types.

### 6.1. IDE Agent Fallback

**Primary:** Filesystem dialog files (`dialog_output_file`)  
**Fallback:** Toon files (database schema references)  
**Required:** Filesystem write capability

**Workflow:**
1. IDE agent reads channel configuration from toon files
2. IDE agent writes dialog entries to `dialog_output_file`
3. If database unavailable, IDE agent continues using filesystem
4. IDE agent may read from toon files for schema reference

### 6.2. php_ai_terminal Agent Fallback

**Primary:** Database dialog tables (`lupo_dialog_threads`, `lupo_dialog_messages`)  
**Fallback:** Direct database queries  
**Required:** Database write capability

**Workflow:**
1. php_ai_terminal agent reads channel configuration from database
2. php_ai_terminal agent writes dialog entries to database tables
3. If database unavailable, agent cannot operate (no filesystem fallback)

### 6.3. WOLFIE Escalation

When an agent cannot respond or routing fails:

1. **WOLFIE evaluates** the failure reason
2. **WOLFIE reroutes** to alternate agent if available
3. **WOLFIE queues** message for later processing if no agents available
4. **WOLFIE escalates** to channel administrator if critical failure

**Critical Rule:** All agents MUST be able to write to `dialog_output_file` as a mandatory fallback, even if they primarily use database tables.

---

## 7. Manifest Mapping

The Channel Identity Block maps to the Channel Manifest stored at:

```
channels/<channel_key>/manifest.json
```

### 7.1. Required Manifest Fields

The following CIB fields MUST appear in the manifest:

- `channel_key`
- `channel_name`
- `channel_description`
- `federation_node_id`
- `dialog_output_file`
- `created_by_actor_id`
- `created_ymdhis`
- `updated_ymdhis`

### 7.2. Optional Manifest Fields

The following CIB fields MAY appear in the manifest:

- `default_roles`
- `default_agents`
- `emotional_poles`
- `metadata_json`
- `tags`
- `bgcolor`
- `status_flag`

### 7.3. Manifest Generation

WOLFIE generates the manifest during channel initialization:

1. Read CIB from database (`lupo_channels` table)
2. Enrich with actor assignments (`lupo_actor_channels`)
3. Enrich with role assignments (`lupo_actor_channel_roles`)
4. Generate JSON manifest file
5. Write to `channels/<channel_key>/manifest.json`

---

## 8. Validation Rules

The following validation rules MUST be enforced for all Channel Identity Blocks:

### 8.1. Channel Key Validation

- ✅ `channel_key` must be lowercase snake_case
- ✅ `channel_key` must match pattern: `^[a-z0-9_]+$`
- ✅ `channel_key` + `federation_node_id` must be unique
- ❌ `channel_key` cannot be reserved values: `0`, `1`, `system`, `kernel`, `lobby`

### 8.2. Dialog Output File Validation

- ✅ `dialog_output_file` must end with `_dialog.md`
- ✅ `dialog_output_file` must be relative path under `dialogs/`
- ✅ `dialog_output_file` must follow pattern: `dialogs/<channel_name>_dialog.md`
- ❌ `dialog_output_file` cannot be absolute path

### 8.3. Timestamp Validation

- ✅ `created_ymdhis` must be valid UTC timestamp in `YYYYMMDDHHIISS` format
- ✅ `updated_ymdhis` must be valid UTC timestamp in `YYYYMMDDHHIISS` format
- ✅ `updated_ymdhis` must be >= `created_ymdhis`
- ❌ Timestamps cannot be NULL
- ❌ Timestamps cannot be future dates

### 8.4. Emotional Poles Validation

- ✅ `emotional_poles` must contain exactly 2 agent IDs
- ✅ Both agent IDs must reference valid `lupo_actors.actor_id`
- ✅ Both agents must have `actor_type = 'ai_agent'`
- ✅ Both agents must be different (cannot be same agent)
- ✅ Both agents must be assigned to the channel
- ✅ Emotional poles must be valid per emotional geometry doctrine

### 8.5. Actor Validation

- ✅ `created_by_actor_id` must reference valid `lupo_actors.actor_id`
- ✅ `default_agents` array must contain valid `lupo_actors.actor_id` values
- ✅ All referenced actors must exist in `lupo_actors` table

### 8.6. Federation Node Validation

- ✅ `federation_node_id` must reference valid `lupo_federation_nodes.federation_node_id`
- ✅ `federation_node_id` cannot be NULL

---

## 9. Database Integration

The Channel Identity Block maps to the following database tables:

### 9.1. Primary Table: `lupo_channels`

**Fields:**
- `channel_id` (Primary Key)
- `federation_node_id` → CIB `federation_node_id`
- `created_by_actor_id` → CIB `created_by_actor_id`
- `default_actor_id` (default: 1)
- `channel_key` → CIB `channel_key`
- `channel_name` → CIB `channel_name`
- `description` → CIB `channel_description`
- `metadata_json` → CIB `metadata_json`
- `bgcolor` → CIB `bgcolor`
- `status_flag` → CIB `status_flag`
- `created_ymdhis` → CIB `created_ymdhis`
- `updated_ymdhis` → CIB `updated_ymdhis`
- `is_deleted` (soft delete flag)
- `deleted_ymdhis` (deletion timestamp)

**Note:** `dialog_output_file` is stored in `lupo_actor_channels.dialog_output_file` per actor, not in `lupo_channels` table.

### 9.2. Related Tables

**`lupo_actor_channels`:**
- Stores actor-channel relationships
- Contains `dialog_output_file` per actor-channel pair
- Links to `lupo_actors` and `lupo_channels`

**`lupo_actor_channel_roles`:**
- Stores role assignments per actor-channel pair
- Links to `lupo_actors` and `lupo_channels`

**`lupo_actors`:**
- Registry of all actors (users, AI agents, services)
- Referenced by `created_by_actor_id` and `default_agents`

---

## 10. Integration Points

The Channel Identity Block integrates with the following Lupopedia subsystems:

### 10.1. WOLFIE (Channel Initialization)

- WOLFIE uses CIB during channel initialization
- WOLFIE validates all CIB fields
- WOLFIE generates channel manifest from CIB
- WOLFIE creates database records from CIB

### 10.2. HERMES (Routing)

- HERMES uses `channel_key` for routing context
- HERMES uses `default_agents` for agent pool
- HERMES may use CADUCEUS emotional current (from `emotional_poles`)

### 10.3. CADUCEUS (Emotional Balancing)

- CADUCEUS reads moods from `emotional_poles` agents
- CADUCEUS computes channel emotional current
- CADUCEUS provides context to HERMES (optional)

### 10.4. IDE Agents (Filesystem)

- IDE agents read CIB from toon files
- IDE agents write to `dialog_output_file`
- IDE agents use CIB for channel context

### 10.5. php_ai_terminal Agents (Database)

- php_ai_terminal agents read CIB from database
- php_ai_terminal agents write to database dialog tables
- php_ai_terminal agents use CIB for channel context

---

## 11. Related Documentation

- **[Channel Initialization Protocol](./CHANNEL_INITIALIZATION_PROTOCOL.md)** — Complete CIP doctrine
- **[Channel Dialog Agent Workflows](../../../docs/ARCHITECTURE/CHANNEL_DIALOG_AGENT_WORKFLOWS.md)** — How agents interact with channels
- **[Channel Dialog Schema Review](../../../docs/ARCHITECTURE/CHANNEL_DIALOG_SCHEMA_REVIEW.md)** — Database schema for channels
- **[Dialogs and Channels Architecture](../../../docs/ARCHITECTURE/DIALOGS_AND_CHANNELS.md)** — Thread vs channel distinction
- **[HERMES and CADUCEUS](../../../docs/agents/HERMES_AND_CADUCEUS.md)** — Routing and emotional balancing subsystems
- **[Emotional Geometry Doctrine](../../../docs/doctrine/EMOTIONAL_GEOMETRY.md)** — Emotional geometry principles
- **[MOOD_RGB Doctrine](../../../docs/doctrine/MOOD_RGB_DOCTRINE.md)** — Mood tracking specification

---

*Last Updated: January 14, 2026*  
*Version: GLOBAL_CURRENT_LUPOPEDIA_VERSION*  
*Status: Published*  
*Author: GLOBAL_CURRENT_AUTHORS*
