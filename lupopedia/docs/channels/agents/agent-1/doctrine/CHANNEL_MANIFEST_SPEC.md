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
  message: "Created CHANNEL_MANIFEST_SPEC.md as complete doctrine specification for Channel Manifests. Defines machine-readable JSON representation of Channel Identity Blocks, mapping rules, validation, emotional geometry integration, routing integration, and fallback behavior. This enables IDE agents, php_ai_terminal agents, WOLFIE, HERMES, and CADUCEUS to understand channel metadata."
tags:
  categories: ["documentation", "doctrine", "channels", "wolfie"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
file:
  title: "Channel Manifest Specification — Doctrine"
  description: "Complete doctrine specification for Channel Manifests, the machine-readable JSON representation of Channel Identity Blocks. Defines file location, required/optional fields, CIB mapping rules, emotional geometry integration, routing integration, fallback behavior, and validation rules."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# CHANNEL MANIFEST SPECIFICATION — Doctrine

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Status:** Published  
**Agent:** WOLFIE (agent_id = 1)  
**Last Updated:** 2026-01-14

## 1. Purpose

The Channel Manifest is the **machine-readable representation** of the Channel Identity Block (CIB). It provides a standardized JSON format that enables IDE agents, php_ai_terminal agents, WOLFIE, HERMES, and CADUCEUS to understand channel metadata, routing rules, emotional poles, and fallback behavior without requiring direct database access.

The Channel Manifest serves as:
- **Machine-readable channel configuration** for all agent types
- **Filesystem-based channel reference** for IDE agents
- **Routing context** for HERMES message routing
- **Emotional geometry anchor** for CADUCEUS mood balancing
- **Fallback schema reference** when database is unavailable
- **Version-controlled channel metadata** in the filesystem

**Relationship to CIB:**
- CIB is the **source of truth** (stored in database)
- Manifest is the **derived representation** (stored in filesystem)
- Manifest is **generated from CIB** during channel initialization
- Manifest is **regenerated** when CIB is updated

---

## 2. File Location

Channel Manifests are stored at:

```
channels/<channel_key>/manifest.json
```

### 2.1. Directory Structure

The `channels/` directory follows this structure:

```
channels/
├── <channel_key_1>/
│   └── manifest.json
├── <channel_key_2>/
│   └── manifest.json
└── ...
```

### 2.2. File Naming Rules

- **Directory name:** Must match `channel_key` exactly
- **File name:** Always `manifest.json` (lowercase, no variation)
- **Path format:** `channels/<channel_key>/manifest.json`

### 2.3. Examples

- `channels/dev_documentation/manifest.json`
- `channels/routing_development/manifest.json`
- `channels/lupopedia_4_0_16_channels_documentation_database_refinement/manifest.json`

---

## 3. Required Fields

All Channel Manifests MUST include the following required fields:

### 3.1. `channel_key` (String)

**Purpose:** URL-friendly identifier (slug) for the channel  
**Type:** `string`  
**Format:** Lowercase, snake_case  
**CIB Mapping:** Direct mapping from `CIB.channel_key`  
**Validation Rules:**
- Must be lowercase only
- Must use snake_case (underscores, no spaces, no hyphens)
- Must be 1-64 characters
- Must match pattern: `^[a-z0-9_]+$`
- Cannot be reserved values: `0`, `1`, `system`, `kernel`, `lobby`

**JSON Example:**
```json
{
  "channel_key": "dev_documentation"
}
```

---

### 3.2. `channel_name` (String)

**Purpose:** Human-readable channel name  
**Type:** `string`  
**Format:** Title case or sentence case  
**CIB Mapping:** Direct mapping from `CIB.channel_name`  
**Validation Rules:**
- Must be 1-255 characters
- Should be descriptive and clear
- May include spaces and punctuation

**JSON Example:**
```json
{
  "channel_name": "Development Documentation Channel"
}
```

---

### 3.3. `channel_description` (String)

**Purpose:** Channel purpose and scope  
**Type:** `string`  
**Format:** Free-form text  
**CIB Mapping:** Direct mapping from `CIB.channel_description`  
**Validation Rules:**
- May be empty string (not recommended)
- Should clearly describe channel purpose
- Should document scope and boundaries

**JSON Example:**
```json
{
  "channel_description": "Channel for coordinating documentation work across IDE agents and php_ai_terminal agents."
}
```

---

### 3.4. `federation_node_id` (Number)

**Purpose:** Domain/tenant identifier for federation  
**Type:** `number` (integer)  
**Format:** Positive integer  
**CIB Mapping:** Direct mapping from `CIB.federation_node_id`  
**Validation Rules:**
- Must be positive integer
- Must reference valid federation node
- Cannot be null or undefined

**JSON Example:**
```json
{
  "federation_node_id": 1
}
```

---

### 3.5. `dialog_output_file` (String)

**Purpose:** Filesystem path for IDE agent dialog logs  
**Type:** `string`  
**Format:** Relative path under `dialogs/` directory  
**CIB Mapping:** Direct mapping from `CIB.dialog_output_file`  
**Validation Rules:**
- Must end with `_dialog.md`
- Must be relative path (no absolute paths)
- Must follow pattern: `dialogs/<channel_name>_dialog.md`
- Must be unique per channel

**JSON Example:**
```json
{
  "dialog_output_file": "dialogs/dev_documentation_dialog.md"
}
```

---

### 3.6. `created_by_actor_id` (Number)

**Purpose:** Actor who created the channel  
**Type:** `number` (integer)  
**Format:** Positive integer  
**CIB Mapping:** Direct mapping from `CIB.created_by_actor_id`  
**Validation Rules:**
- Must be positive integer
- Must reference valid actor
- Cannot be null or undefined

**JSON Example:**
```json
{
  "created_by_actor_id": 1
}
```

---

### 3.7. `created_ymdhis` (Number)

**Purpose:** Channel creation timestamp  
**Type:** `number` (integer)  
**Format:** UTC timestamp in `YYYYMMDDHHIISS` format  
**CIB Mapping:** Direct mapping from `CIB.created_ymdhis`  
**Source:** Real UTC from UTC_TIMEKEEPER (via WOLFIE)  
**Validation Rules:**
- Must be valid UTC timestamp
- Must be in `YYYYMMDDHHIISS` format (14 digits)
- Cannot be null or undefined
- Must be current or past timestamp (not future)
- MUST be obtained from UTC_TIMEKEEPER by WOLFIE during channel initialization
- MUST NOT be inferred from OS, model, or file metadata

**UTC Authority:**
- WOLFIE requests real UTC from UTC_TIMEKEEPER during channel initialization
- WOLFIE embeds this timestamp in manifest `created_ymdhis`
- All agents MUST use this timestamp as authoritative baseline

**JSON Example:**
```json
{
  "created_ymdhis": 20260114233000
}
```

**See:** [WOLFIE UTC Authority Doctrine](./WOLFIE_UTC_AUTHORITY.md)

---

### 3.8. `updated_ymdhis` (Number)

**Purpose:** Last update timestamp  
**Type:** `number` (integer)  
**Format:** UTC timestamp in `YYYYMMDDHHIISS` format  
**CIB Mapping:** Direct mapping from `CIB.updated_ymdhis`  
**Source:** Real UTC from UTC_TIMEKEEPER (terminal agents) or approximated (IDE agents)  
**Validation Rules:**
- Must be valid UTC timestamp
- Must be in `YYYYMMDDHHIISS` format (14 digits)
- Cannot be null or undefined
- Must be >= `created_ymdhis`
- Should be updated on every channel modification
- Terminal agents MUST use real UTC from UTC_TIMEKEEPER
- IDE agents MAY use approximated timestamps (see WOLFIE_UTC_AUTHORITY doctrine)
- MUST NOT be inferred from OS, model, or file metadata

**UTC Authority:**
- Initial value set to same as `created_ymdhis` (from UTC_TIMEKEEPER)
- Terminal agents MUST request real UTC from UTC_TIMEKEEPER for updates
- IDE agents MAY increment by `expected_prompt_interval_minutes` (default: 10 minutes)

**JSON Example:**
```json
{
  "updated_ymdhis": 20260114233000
}
```

**See:** [WOLFIE UTC Authority Doctrine](./WOLFIE_UTC_AUTHORITY.md)

---

## 4. Optional Fields

Channel Manifests MAY include the following optional fields:

### 4.1. `default_roles` (Array)

**Purpose:** Default role assignments for channel participants  
**Type:** `array` of `string`  
**Format:** `["role_key_1", "role_key_2", ...]`  
**CIB Mapping:** Direct mapping from `CIB.default_roles`  
**Validation Rules:**
- Each role_key must be a string
- Each role_key must be valid per role doctrine
- Array may be empty or omitted

**JSON Example:**
```json
{
  "default_roles": ["channel_admin", "participant"]
}
```

---

### 4.2. `default_agents` (Array)

**Purpose:** Default agent assignments for the channel  
**Type:** `array` of `number` (integers)  
**Format:** `[agent_id_1, agent_id_2, ...]`  
**CIB Mapping:** Direct mapping from `CIB.default_agents`  
**Validation Rules:**
- Each agent_id must be a positive integer
- Each agent_id must reference valid actor
- Array may be empty or omitted

**JSON Example:**
```json
{
  "default_agents": [1, 3, 4]
}
```

---

### 4.3. `emotional_poles` (Object)

**Purpose:** Polar agents for CADUCEUS emotional balancing  
**Type:** `object`  
**Format:** `{ "pole_a": agent_id, "pole_b": agent_id }`  
**CIB Mapping:** Expanded from `CIB.emotional_poles` array  
**Validation Rules:**
- Must contain exactly 2 properties: `pole_a` and `pole_b`
- Each pole must be a positive integer (agent_id)
- Both agent_ids must reference valid actors
- Both agents must have `actor_type = 'ai_agent'`
- Agents must be different (cannot be same agent)
- Object may be omitted if no emotional poles defined

**JSON Example:**
```json
{
  "emotional_poles": {
    "pole_a": 3,
    "pole_b": 4
  }
}
```

**CIB Mapping Rule:**  
If CIB contains `emotional_poles: [agent_id_1, agent_id_2]`, manifest expands to:
```json
{
  "emotional_poles": {
    "pole_a": agent_id_1,
    "pole_b": agent_id_2
  }
}
```

---

### 4.4. `metadata_json` (Object)

**Purpose:** Additional channel metadata  
**Type:** `object`  
**Format:** Valid JSON object  
**CIB Mapping:** Direct mapping from `CIB.metadata_json` (parsed JSON)  
**Validation Rules:**
- Must be valid JSON object if provided
- May contain any structure
- Should follow Lupopedia metadata governance doctrine
- May be omitted if no metadata

**JSON Example:**
```json
{
  "metadata_json": {
    "intent": "documentation",
    "scope": "channel_system",
    "priority": "high",
    "tags": ["channels", "documentation", "database"]
  }
}
```

---

### 4.5. `tags` (Array)

**Purpose:** Channel tags for categorization  
**Type:** `array` of `string`  
**Format:** `["tag1", "tag2", ...]`  
**CIB Mapping:** Direct mapping from `CIB.tags`  
**Validation Rules:**
- Each tag must be a string
- Tags should be lowercase, hyphenated
- Array may be empty or omitted

**JSON Example:**
```json
{
  "tags": ["channels", "documentation", "database"]
}
```

---

### 4.6. `bgcolor` (String)

**Purpose:** Background color for channel UI  
**Type:** `string`  
**Format:** 6-character hex color (no # prefix)  
**CIB Mapping:** Direct mapping from `CIB.bgcolor`  
**Default:** `"FFFFFF"`  
**Validation Rules:**
- Must be 6-character hex string
- Must match pattern: `^[0-9A-Fa-f]{6}$`
- No # prefix allowed
- May be omitted (defaults to `"FFFFFF"`)

**JSON Example:**
```json
{
  "bgcolor": "F7FAFF"
}
```

---

### 4.7. `status_flag` (String)

**Purpose:** Channel status (active/inactive)  
**Type:** `string`  
**Format:** `"active"` or `"inactive"`  
**CIB Mapping:** Converted from `CIB.status_flag` (1 = "active", 0 = "inactive")  
**Default:** `"active"`  
**Validation Rules:**
- Must be `"active"` or `"inactive"`
- May be omitted (defaults to `"active"`)

**JSON Example:**
```json
{
  "status_flag": "active"
}
```

**CIB Mapping Rule:**  
If CIB contains `status_flag: 1`, manifest converts to `"status_flag": "active"`  
If CIB contains `status_flag: 0`, manifest converts to `"status_flag": "inactive"`

---

## 5. Mapping from CIB → Manifest

The following table defines how each CIB field maps to the manifest:

| CIB Field | Manifest Field | Mapping Rule | Required |
|-----------|----------------|--------------|----------|
| `channel_key` | `channel_key` | Direct mapping (string) | ✅ Required |
| `channel_name` | `channel_name` | Direct mapping (string) | ✅ Required |
| `channel_description` | `channel_description` | Direct mapping (string) | ✅ Required |
| `federation_node_id` | `federation_node_id` | Direct mapping (number) | ✅ Required |
| `dialog_output_file` | `dialog_output_file` | Direct mapping (string) | ✅ Required |
| `created_by_actor_id` | `created_by_actor_id` | Direct mapping (number) | ✅ Required |
| `created_ymdhis` | `created_ymdhis` | Direct mapping (number) | ✅ Required |
| `updated_ymdhis` | `updated_ymdhis` | Direct mapping (number) | ✅ Required |
| `default_roles` | `default_roles` | Direct mapping (array) | ⚪ Optional |
| `default_agents` | `default_agents` | Direct mapping (array) | ⚪ Optional |
| `emotional_poles` | `emotional_poles` | **Expanded** (array → object) | ⚪ Optional |
| `metadata_json` | `metadata_json` | **Parsed** (TEXT → object) | ⚪ Optional |
| `tags` | `tags` | Direct mapping (array) | ⚪ Optional |
| `bgcolor` | `bgcolor` | Direct mapping (string) | ⚪ Optional |
| `status_flag` | `status_flag` | **Converted** (1/0 → "active"/"inactive") | ⚪ Optional |

### 5.1. Field Renaming

**No field renaming occurs.** CIB field names map directly to manifest field names.

### 5.2. Field Omission

**Optional fields may be omitted** from the manifest if:
- CIB field is NULL or empty
- CIB field has default value that matches manifest default
- Field is not relevant to channel configuration

**Required fields MUST NOT be omitted** under any circumstances.

### 5.3. Field Expansion

**`emotional_poles` expansion:**
- **CIB format:** `[agent_id_1, agent_id_2]` (array)
- **Manifest format:** `{ "pole_a": agent_id_1, "pole_b": agent_id_2 }` (object)
- **Rule:** First element becomes `pole_a`, second element becomes `pole_b`

### 5.4. Field Conversion

**`status_flag` conversion:**
- **CIB format:** `1` (active) or `0` (inactive) - integer
- **Manifest format:** `"active"` or `"inactive"` - string
- **Rule:** `1` → `"active"`, `0` → `"inactive"`

**`metadata_json` parsing:**
- **CIB format:** TEXT (JSON string in database)
- **Manifest format:** Object (parsed JSON)
- **Rule:** Parse JSON string into object structure

### 5.5. JSON Formatting Requirements

- **Encoding:** UTF-8
- **Indentation:** 2 spaces (for readability)
- **Line endings:** Unix-style (`\n`)
- **Trailing commas:** Not allowed
- **Comments:** Not allowed (pure JSON, no JSON5)

---

## 6. Emotional Geometry Integration

The Channel Manifest integrates with Lupopedia's emotional geometry system through the `emotional_poles` field.

### 6.1. Pole Structure

The `emotional_poles` object contains exactly two poles:

```json
{
  "emotional_poles": {
    "pole_a": 3,
    "pole_b": 4
  }
}
```

- **`pole_a`:** First polar agent (agent_id)
- **`pole_b`:** Second polar agent (agent_id)

### 6.2. Validation Rules

**Pole Validation:**
- ✅ Both `pole_a` and `pole_b` must be positive integers
- ✅ Both must reference valid `lupo_actors.actor_id`
- ✅ Both agents must have `actor_type = 'ai_agent'`
- ✅ Both agents must be different (cannot be same agent)
- ✅ Both agents must be assigned to the channel
- ❌ Cannot have only one pole (must have both or neither)
- ❌ Cannot have more than two poles

### 6.3. Allowed Pole Identifiers

**Pole identifiers are agent IDs:**
- Must reference valid agents in `lupo_actors` table
- Agents must exist and be active
- Agents must be assigned to the channel

**Example valid pole identifiers:**
- `1` (WOLFIE)
- `3` (KIRO)
- `4` (CURSOR)
- `5` (CASCADE)

### 6.4. CADUCEUS Integration

**CADUCEUS reads emotional poles from manifest:**

1. **Read `emotional_poles`** from manifest
2. **Resolve agent IDs** to actor records
3. **Read moods** from both polar agents (per MOOD_RGB_DOCTRINE)
4. **Blend emotional states** to produce channel emotional current
5. **Provide emotional current** to HERMES (optional context)

**Critical Rule:** CADUCEUS balances moods, **not routes messages**. CADUCEUS is an emotional balancer, not a routing subsystem.

**Manifest Usage:**
- IDE agents read `emotional_poles` from manifest
- php_ai_terminal agents read `emotional_poles` from manifest
- CADUCEUS uses `emotional_poles` to identify which agents to read moods from
- HERMES may use CADUCEUS emotional current (derived from poles) for routing context

---

## 7. Routing Integration

The Channel Manifest integrates with HERMES (the routing subsystem) through several fields.

### 7.1. Channel Key Routing

**HERMES uses `channel_key` for routing context:**

1. Message arrives with `channel_key` identifier
2. HERMES loads manifest from `channels/<channel_key>/manifest.json`
3. HERMES uses manifest fields for routing decisions

**Manifest Field:** `channel_key`

### 7.2. Agent Pool Routing

**HERMES uses `default_agents` for agent pool:**

1. HERMES reads `default_agents` array from manifest
2. HERMES filters agents by availability and capabilities
3. HERMES selects target agent from pool
4. HERMES routes message to selected agent

**Manifest Field:** `default_agents`

**Example:**
```json
{
  "default_agents": [1, 3, 4]
}
```

HERMES considers agents 1, 3, and 4 as potential routing targets.

### 7.3. Role-Based Routing

**HERMES may use `default_roles` for role-based routing:**

1. HERMES reads `default_roles` array from manifest
2. HERMES resolves roles to actor lists
3. HERMES uses role assignments for routing context

**Manifest Field:** `default_roles`

**Note:** Role-based routing is optional. HERMES primarily uses agent IDs, not roles.

### 7.4. Fallback Behavior

**When routing fails, HERMES uses manifest fallback rules:**

1. **No agents available:** Route to `dialog_output_file` (filesystem fallback)
2. **Agent unavailable:** Try next agent in `default_agents` array
3. **Routing decision fails:** Write to `dialog_output_file` and escalate to WOLFIE

**Manifest Fields Used:**
- `dialog_output_file` (fallback write target)
- `default_agents` (alternate agent selection)

---

## 8. Fallback Behavior

The Channel Manifest defines fallback behavior for different agent types when normal operations fail.

### 8.1. IDE Agent Fallback

**Primary:** Read manifest from `channels/<channel_key>/manifest.json`  
**Fallback:** Read from toon files (database schema references)  
**Required:** Write to `dialog_output_file` (filesystem)

**Workflow:**
1. IDE agent reads manifest from filesystem
2. IDE agent uses manifest fields for channel context
3. IDE agent writes dialog entries to `dialog_output_file`
4. If manifest unavailable, IDE agent reads from toon files
5. IDE agent continues using filesystem even if database unavailable

**Manifest Fields Used:**
- `dialog_output_file` (required write target)
- `channel_key` (for manifest location)
- `default_agents` (for agent context)
- `emotional_poles` (for emotional geometry context)

### 8.2. php_ai_terminal Agent Fallback

**Primary:** Read manifest from filesystem (if available)  
**Fallback:** Read from database (`lupo_channels` table)  
**Required:** Write to database dialog tables

**Workflow:**
1. php_ai_terminal agent attempts to read manifest from filesystem
2. If manifest unavailable, agent reads from database
3. Agent uses manifest/database fields for channel context
4. Agent writes dialog entries to database tables
5. If database unavailable, agent cannot operate (no filesystem fallback)

**Manifest Fields Used:**
- `channel_key` (for database lookup)
- `default_agents` (for agent context)
- `emotional_poles` (for emotional geometry context)

### 8.3. Agent Unavailability Fallback

**When an agent is unavailable:**

1. **HERMES checks `default_agents` array** for alternate agents
2. **HERMES routes to next available agent** in the list
3. **If no agents available:** Write to `dialog_output_file` and queue for later processing

**Manifest Fields Used:**
- `default_agents` (alternate agent selection)
- `dialog_output_file` (fallback write target)

### 8.4. Dialog Write Failure Fallback

**When dialog cannot be written:**

1. **IDE agents:** Write to `dialog_output_file` (always available)
2. **php_ai_terminal agents:** Queue message for later database write
3. **WOLFIE escalation:** If critical failure, escalate to channel administrator

**Manifest Fields Used:**
- `dialog_output_file` (IDE agent fallback)
- `created_by_actor_id` (escalation target)

### 8.5. Routing Decision Failure Fallback

**When routing decision fails:**

1. **HERMES writes to `dialog_output_file`** (filesystem fallback)
2. **HERMES queues message** for later processing
3. **WOLFIE evaluates** failure reason and reroutes if possible

**Manifest Fields Used:**
- `dialog_output_file` (fallback write target)
- `default_agents` (rerouting context)

---

## 9. Validation Rules

The following validation rules MUST be enforced for all Channel Manifests:

### 9.1. JSON Structure Validation

- ✅ Must be valid JSON (parseable by standard JSON parser)
- ✅ Must be UTF-8 encoded
- ✅ Must be an object (not array, string, or primitive)
- ✅ Must not contain comments (pure JSON, no JSON5)
- ✅ Must not contain trailing commas
- ❌ Invalid JSON structure causes manifest rejection

### 9.2. Required Fields Validation

- ✅ All 8 required fields MUST be present
- ✅ Required fields cannot be null or undefined
- ✅ Required fields must match their type specifications
- ❌ Missing required field causes manifest rejection

**Required Fields Checklist:**
- [ ] `channel_key` (string)
- [ ] `channel_name` (string)
- [ ] `channel_description` (string)
- [ ] `federation_node_id` (number)
- [ ] `dialog_output_file` (string)
- [ ] `created_by_actor_id` (number)
- [ ] `created_ymdhis` (number)
- [ ] `updated_ymdhis` (number)

### 9.3. Timestamp Format Validation

- ✅ `created_ymdhis` must be valid UTC timestamp in `YYYYMMDDHHIISS` format
- ✅ `updated_ymdhis` must be valid UTC timestamp in `YYYYMMDDHHIISS` format
- ✅ Timestamps must be 14 digits (no leading zeros required, but format must be correct)
- ✅ `updated_ymdhis` must be >= `created_ymdhis`
- ✅ Timestamps cannot be future dates (within reasonable tolerance)
- ❌ Invalid timestamp format causes manifest rejection

**Timestamp Validation Pattern:**
- Format: `YYYYMMDDHHIISS` (14 digits)
- Example: `20260114233000` (2026-01-14 23:30:00 UTC)
- Range: Must be valid date/time

### 9.4. Emotional Pole Validity Validation

**If `emotional_poles` is present:**

- ✅ Must be an object (not array or primitive)
- ✅ Must contain exactly 2 properties: `pole_a` and `pole_b`
- ✅ Both `pole_a` and `pole_b` must be positive integers
- ✅ Both agent IDs must reference valid actors
- ✅ Both agents must have `actor_type = 'ai_agent'`
- ✅ Both agents must be different (cannot be same agent)
- ✅ Both agents must be assigned to the channel
- ❌ Invalid emotional poles cause manifest rejection

**If `emotional_poles` is omitted:**
- ✅ Channel operates without emotional geometry
- ✅ CADUCEUS does not compute channel emotional current
- ✅ HERMES routing proceeds without emotional context

### 9.5. Dialog Output File Path Validation

- ✅ `dialog_output_file` must be a string
- ✅ Must end with `_dialog.md`
- ✅ Must be relative path (no absolute paths)
- ✅ Must follow pattern: `dialogs/<channel_name>_dialog.md`
- ✅ Path must be valid filesystem path (no invalid characters)
- ❌ Invalid path causes manifest rejection

**Path Validation Pattern:**
- Must match: `^dialogs/[a-z0-9_]+_dialog\.md$`
- Example: `dialogs/dev_documentation_dialog.md`
- Invalid: `/absolute/path/dialog.md` (absolute path)
- Invalid: `dialogs/channel.md` (missing `_dialog` suffix)

### 9.6. Channel Key Validation

- ✅ `channel_key` must be a string
- ✅ Must be lowercase only
- ✅ Must use snake_case (underscores, no spaces, no hyphens)
- ✅ Must be 1-64 characters
- ✅ Must match pattern: `^[a-z0-9_]+$`
- ✅ Cannot be reserved values: `0`, `1`, `system`, `kernel`, `lobby`
- ❌ Invalid channel_key causes manifest rejection

### 9.7. Agent ID Validation

**For `default_agents` array (if present):**
- ✅ Must be an array
- ✅ Each element must be a positive integer
- ✅ Each agent_id must reference valid actor
- ✅ Array may be empty

**For `emotional_poles` object (if present):**
- ✅ `pole_a` and `pole_b` must be positive integers
- ✅ Both must reference valid actors
- ✅ Both agents must exist and be active

### 9.8. Status Flag Validation

**If `status_flag` is present:**
- ✅ Must be string `"active"` or `"inactive"`
- ✅ Case-sensitive (must be lowercase)
- ❌ Invalid status_flag causes manifest rejection

**If `status_flag` is omitted:**
- ✅ Defaults to `"active"`

---

## 10. Complete Manifest Example

```json
{
  "channel_key": "dev_documentation",
  "channel_name": "Development Documentation Channel",
  "channel_description": "Channel for coordinating documentation work across IDE agents and php_ai_terminal agents.",
  "federation_node_id": 1,
  "dialog_output_file": "dialogs/dev_documentation_dialog.md",
  "created_by_actor_id": 1,
  "created_ymdhis": 20260114233000,
  "updated_ymdhis": 20260114233000,
  "default_roles": ["channel_admin", "participant"],
  "default_agents": [1, 3, 4],
  "emotional_poles": {
    "pole_a": 3,
    "pole_b": 4
  },
  "metadata_json": {
    "intent": "documentation",
    "scope": "channel_system",
    "priority": "high"
  },
  "tags": ["channels", "documentation", "database"],
  "bgcolor": "F7FAFF",
  "status_flag": "active"
}
```

---

## 11. Related Documentation

- **[Channel Identity Block Doctrine](./CHANNEL_IDENTITY_BLOCK.md)** — Source specification for CIB fields
- **[Channel Initialization Protocol](./CHANNEL_INITIALIZATION_PROTOCOL.md)** — Complete CIP doctrine
- **[Channel Dialog Agent Workflows](../../../docs/ARCHITECTURE/CHANNEL_DIALOG_AGENT_WORKFLOWS.md)** — How agents interact with channels
- **[Channel Dialog Schema Review](../../../docs/ARCHITECTURE/CHANNEL_DIALOG_SCHEMA_REVIEW.md)** — Database schema for channels
- **[HERMES and CADUCEUS](../../../docs/agents/HERMES_AND_CADUCEUS.md)** — Routing and emotional balancing subsystems
- **[Emotional Geometry Doctrine](../../../docs/doctrine/EMOTIONAL_GEOMETRY.md)** — Emotional geometry principles
- **[MOOD_RGB Doctrine](../../../docs/doctrine/MOOD_RGB_DOCTRINE.md)** — Mood tracking specification

---

*Last Updated: January 14, 2026*  
*Version: GLOBAL_CURRENT_LUPOPEDIA_VERSION*  
*Status: Published*  
*Author: GLOBAL_CURRENT_AUTHORS*
