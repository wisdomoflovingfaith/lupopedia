---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260407235921"
  file_path_from_root: "lupo-docs/prd/01_core_identity.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/01_core_identity.md"
  last_modified_utc: "20260407235921"
  channel_id: 42
  thread_id: "prd-grouped"
  actor_id: 2
  actor_name: "LILITH"
  delegation_chain: "lilith:audit|hephaestus:implementation"
  artifact_type: "prd"
  artifact_kind: "database_namespace"
  purpose: "PRD for core identity database tables"
  tags:
  - "tag-prd"
  - "tag-database"
  - "tag-namespace"
  - "tag-core-identity"
  - "tag-session-identity"
  - "tag-4.0.96"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/database/lupopedia/tables/"
      type: references
      weight: 1.0
      reason: "Detailed table documentation"
    - to: "lupo-docs/prd/02_channels_discussions.md"
      type: references
      weight: 1.0
      reason: "Channel system depends on identity"
    - to: "lupo-docs/prd/07_agents_faucets.md"
      type: references
      weight: 1.0
      reason: "Agents depend on identity system"
    - to: "craftysyntax-reference/functions.php"
      type: references
      weight: 0.85
      reason: "Historical Crafty Syntax 3.7.5 session and IP helpers (get_ipaddress, identity, SESSIONID)"
lupopedia.footer:
  last_verified: "20260407235921"
  verified_by:
    actor_id: 2
    agent_name_identity: "LILITH"
  orchestrator: "lilith:audit|cursor:implementation"
  lilith_audit:
    accuracy_score: 98
    constitutional_violations:
      - "version_when_written is DEPRECATED - should be when_updated"
      - "metadata_json missing JSON schema reference"
      - "session_id generation method not documented"
      - "No actor_id workspace path resolution table for deterministic IDs"
    security_concerns:
      - "No memory retention/expiration policy in lupo_actor_memory"
      - "No session cleanup strategy documented"
      - "No actor merge protocol defined"
    bias_detected: no
    better_alternative_exists: Yes
    counter_proposal: "Add memory retention policy, session cleanup strategy, actor merge protocol, and update deprecated version field"
    recommendations:
      - "FIX: Replace version_when_written with when_updated"
      - "ADD: JSON schema reference for metadata_json fields"
      - "ADD: Memory retention policy with expires_ymdhis"
      - "ADD: Session cleanup strategy (cron job reference)"
      - "ADD: Actor merge protocol with lineage tracking"
      - "ADD: Deterministic ID path resolution table"
    verdict: approved_with_minor_corrections
---

# PRD: Core Identity Database Tables

## Overview

**Namespace Purpose:** Provides foundational identity system for Lupopedia, managing actors, authentication, sessions, capabilities, and extended actor capabilities including memory, skills, tools, prompts, and training. This namespace establishes who can act within system and what they're allowed to do.

**Primary Actors:** 
- End users (via lupo_auth_users)
- System actors (via lupo_actors)
- AI agents (via lupo_agents)
- Session managers (via lupo_sessions)
- Capability system (via lupo_actor_capabilities)
- Memory managers (via lupo_actor_memory)
- Skill managers (via lupo_actor_skills)
- Tool managers (via lupo_actor_tools)
- Prompt managers (via lupo_actor_prompts)
- Training managers (via lupo_actor_training)
- Claude Code (actor_id 116, distinct AI actor; see registry and PRD 15)

**4.0.x Release Continuity:** This namespace structure is designed for continuity across 4.0.x releases, with backward compatibility maintained through version-specific extensions and deprecation policies.

**Constitutional Compliance:** All tables in this namespace follow Lupopedia constitutional rules:
- NO foreign keys (relationships in application logic)
- NO triggers
- NO stored procedures
- BIGINT timestamps (YYYYMMDDHHIISS UTC)
- Explicit ID generation (application layer)
- Soft delete (is_deleted + deleted_ymdhis)

## Tables in This Namespace

---

## Agents vs Actors: The Two-Layer Identity Model

### Critical Distinction

| | **Agent** | **Actor** |
|---|-----------|-----------|
| **Definition** | Immutable template / persona | Runtime instance with context |
| **Storage** | Filesystem (`lupo-agents/{agent_key}/`) | Database + workspace (`lupo-actors/`) |
| **ID Type** | Agent ID (1–2025 reserved for system agents per registry; see `00_root` §5.5) | Actor ID: registry-reserved low IDs + human actors (typically ≥ 1000); new generated IDs use `YYYYMMDDHHIISS` + 4-digit sequence (`IdGenerator`); see `00_root` §5.6 |
| **Lifecycle** | Permanent (system agents) | Created, used, archived |
| **Learns** | No — agent is the template | Yes — actors learn from humans |
| **Context** | None — agent is generic | Department-specific, user-specific |
| **Behavior** | Defined by capabilities.json | Adapted by department context |

### Why This Matters

**Agents don't learn. Actors do.**

If you treat them the same, you lose:
- Department-specific behavior
- User-specific adaptations
- The ability to have the same agent behave differently in different contexts
- Audit trail of which human influenced which behavior

### Agent Directory Structure (Immutable Template)

```
lupo-agents/{agent_key}/
├── agent.json           # Core metadata (REQUIRED)
├── capabilities.json    # Agent capabilities (REQUIRED)
├── properties.json      # Agent properties (REQUIRED)
├── system_prompt.txt    # System prompt (REQUIRED)
├── versions/            # Version history (optional)
├── api/                # API endpoints (RECOMMENDED)
├── assets/             # Images, icons (RECOMMENDED)
├── components/         # UI components (RECOMMENDED)
├── context/            # Context providers (RECOMMENDED)
├── data/               # Static data (RECOMMENDED)
├── hooks/              # Reusable logic (RECOMMENDED)
├── includes/           # Shared helpers (RECOMMENDED)
├── pages/              # Page logic (RECOMMENDED)
├── tools/              # Tool definitions (RECOMMENDED)
└── utils/              # Utility functions (RECOMMENDED)
```

### Actor Workspace Structure (Runtime Instance)

```
lupo-actors/
├── <actor_id>/ # System actors (actor_id < 2026)
│   ├── agent_link.json # Reference to source agent
│   ├── context.json # Department and user context
│   └── preferences.json # User-specific preferences
│
└── YYYY/ # Year (for runtime actors)
    └── MM/ # Month
        └── <actor_id>/ # Actor ID (deterministic)
            ├── agent_link.json # Reference to source agent
            ├── context.json # Department and user context
            └── preferences.json # User-specific preferences

# Actor root memory node (4.0.96+):
# lupo-memory/YYYY/MM/{memory_slug}.json
# Registered in lupo_memory_nodes; all memory relationships via lupo_edges.
# memory.json in lupo-actors/ is DEPRECATED — do not create new memory.json files.
```

### Actor Creation Flow

1. User selects an Agent (e.g., WOLFIE)
2. User is in a Department (e.g., Sales)
3. System creates an Actor from that Agent for that Department
4. Actor gets deterministic ID: YYYYMMDDHHIISS + 4 random digits
5. If actor_id < 2026: workspace at `lupo-actors/{actor_id}/`
6. If actor_id >= 2026: workspace at `lupo-actors/YYYY/MM/{actor_id}/`
7. Actor inherits all agent capabilities from `lupo-agents/{agent_key}/`
8. Actor learns from user interactions in its department context
9. Learned behavior stored as root memory node at `lupo-memory/YYYY/MM/{memory_slug}.json`; registered in `lupo_memory_nodes`; all memory relationships expressed via `lupo_edges` (4.0.96+). `memory.json` is deprecated.

### Department Context Effect

| Department | Actor Behavior |
|------------|----------------|
| Sales | Persuasive, urgency-driven, deal-focused |
| Engineering | Analytical, precise, architecture-focused |
| Support | Empathetic, patient, solution-focused |
| Security | Paranoid, thorough, threat-focused |

**Same agent. Different actors. Different behavior.**

### Actor ID Ranges

| Range | Type | Workspace Location |
|-------|------|-------------------|
| 1-2025 | System Actor | `lupo-actors/{actor_id}/` |
| 2026+ | Runtime Actor | `lupo-actors/YYYY/MM/{actor_id}/` |

### Database Tables

| Table | Purpose | Links to |
|-------|---------|----------|
| `lupo_actors` | Actor metadata | agent_id (references lupo_agents) |
| `lupo_actor_auth_users` | Actor-user relationship | auth_user_id |
| `lupo_actor_departments` | Department context | department_id |
| `lupo_actor_memory` | Learned behavior | memory entries |
| `lupo_actor_moods` | Emotional state | mood tracking |

| Table | Purpose | Primary Key | Key Application Relationships |
|-------|---------|-------------|------------------------------|
| `lupo_auth_users` | User authentication and profile data | `auth_user_id` | Links to `lupo_actor_auth_users` via service layer |
| `lupo_actors` | System actor definitions and metadata | `actor_id` | Central to all identity operations |
| `lupo_actor_auth_users` | Many-to-many relationship between actors and auth users | `actor_auth_user_id` | Bridges `lupo_actors` and `lupo_auth_users` |
| `lupo_sessions` | Active session tracking for actors | `session_id` (VARCHAR) | Links to `lupo_actors` via service layer |
| `lupo_actor_capabilities` | Capability definitions for actors | `capability_id` | Links to `lupo_permissions` via service layer |
| `lupo_permissions` | Permission definitions and access control | `permission_id` | Core to authorization system |
| `lupo_actor_departments` | Department assignments for actors | `actor_department_id` | Links to `lupo_departments` |
| `lupo_departments` | Organizational department definitions | `department_id` | Core to organization structure |
| `lupo_actor_moods` | Actor emotional state tracking | `mood_id` | Links to `lupo_actors` |
| `lupo_actor_channels` | Actor channel membership and roles | `actor_channel_id` | Links to `lupo_channels` |
| `lupo_actor_channel_roles` | Role definitions for channel participation | `role_id` | Links to `lupo_actor_channels` |
| `lupo_banned_actors` | Banned actor tracking | `ban_id` | Links to `lupo_actors` |
| `lupo_actor_memory` | Actor memory storage (episodic, semantic, etc.) | `actor_memory_id` | Links to `lupo_actors` |
| `lupo_actor_skills` | Actor skill registry and metadata | `skill_id` | Links to `lupo_actors` |
| `lupo_actor_tools` | Tools and utilities available to actors | `tool_id` | Links to `lupo_actors` |
| `lupo_actor_prompts` | Prompt templates and prompt history for actors | `prompt_id` | Links to `lupo_actors` |
| `lupo_actor_training` | Actor training data and learning events | `training_id` | Links to `lupo_actors` |

## Table Details

### `lupo_actor_memory`

**Purpose:** Stores memory items for each actor (e.g. KAIROS observations and consolidations). Schema matches **`install_new_lupopedia.sql`**.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| actor_memory_id | BIGINT | NO | (application) | Primary key (`IdGenerator::generate()`); PK name follows actor_memory + `_id` doctrine |
| actor_id | BIGINT | NO |  | Application-managed reference to `lupo_actors` |
| memory_type | VARCHAR(64) | NO |  | Type of memory (e.g. kairos observation / consolidated) |
| memory_key | VARCHAR(128) | NO |  | Semantic key for the row |
| memory_value | TEXT | YES | NULL | Memory content |
| context_json | JSON | YES | NULL | Structured context (e.g. KAIROS provenance) |
| created_ymdhis | BIGINT | NO | 0 | Packed UTC |
| updated_ymdhis | BIGINT | YES | NULL | Packed UTC |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | Packed UTC when deleted |

**Indexes (install):** `actor_memory_idx_actor_id`, `actor_memory_idx_type_key`, `actor_memory_idx_is_deleted`.

**Future lineage** (parent/root/depth, retention) is not in the current install row; express via `context_json` or add columns in a future install revision.

### Memory Retention Policy

Retention and expiry for `lupo_actor_memory` are **policy-defined** (e.g. GC or KAIROS stages in `context_json`). Any cleanup MUST respect soft delete: **`UPDATE … SET is_deleted = 1, deleted_ymdhis = :now`**, not hard `DELETE`, unless an explicit constitutional exemption is documented for a scratch table class.

### `lupo_actor_skills`

**Purpose:** Registers skills for each actor, including skill name, level, and metadata.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| skill_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | NO |  | Reference to lupo_actors |
| skill_name | VARCHAR(128) | NO |  | Name of the skill |
| skill_level | VARCHAR(32) | YES | NULL | Proficiency or level |
| skill_version | VARCHAR(32) | NO | '1.0.0' | Version of skill definition |
| previous_skill_id | BIGINT | YES | NULL | Previous skill version for lineage |
| skill_metadata | JSON | YES | NULL | Additional skill metadata |
| acquired_ymdhis | BIGINT | NO | (application) | UTC timestamp |
| updated_ymdhis | BIGINT | YES | NULL | UTC timestamp |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_actor_skills_actor | actor_id | Lookup by actor |
| idx_actor_skills_name | skill_name | Lookup by skill |
| idx_actor_skills_deleted | is_deleted | Soft delete filter |

### `lupo_actor_tools`

**Purpose:** Catalogs tools and utilities available to each actor, with metadata and acquisition date.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| tool_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | NO |  | Reference to lupo_actors |
| tool_name | VARCHAR(128) | NO |  | Name of the tool |
| tool_type | VARCHAR(64) | YES | NULL | Type/category of tool |
| input_schema_json | JSON | YES | NULL | JSON schema for tool input |
| output_schema_json | JSON | YES | NULL | JSON schema for tool output |
| execution_timeout_ms | INT | NO | 30000 | Tool execution timeout in milliseconds |
| tool_metadata | JSON | YES | NULL | Additional tool metadata |
| acquired_ymdhis | BIGINT | NO | (application) | UTC timestamp |
| updated_ymdhis | BIGINT | YES | NULL | UTC timestamp |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_actor_tools_actor | actor_id | Lookup by actor |
| idx_actor_tools_name | tool_name | Lookup by tool |
| idx_actor_tools_deleted | is_deleted | Soft delete filter |

### `lupo_actor_prompts`

**Purpose:** Stores prompt templates and prompt history for each actor, supporting prompt engineering and context.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| prompt_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | NO |  | Reference to lupo_actors |
| prompt_key | VARCHAR(128) | NO |  | Unique key for prompt |
| prompt_text | TEXT | NO |  | Prompt content |
| prompt_type | VARCHAR(64) | YES | NULL | Type/category of prompt |
| prompt_version | VARCHAR(32) | NO | '1.0.0' | Version of prompt template |
| inherits_from_prompt_id | BIGINT | YES | NULL | Parent prompt for inheritance |
| is_active | TINYINT | NO | 1 | Whether prompt is active |
| is_default | TINYINT | NO | 0 | Whether this is default prompt |
| context_json | JSON | YES | NULL | Additional context |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp |
| updated_ymdhis | BIGINT | YES | NULL | UTC timestamp |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_actor_prompts_actor | actor_id | Lookup by actor |
| idx_actor_prompts_key | prompt_key | Lookup by key |
| idx_actor_prompts_deleted | is_deleted | Soft delete filter |

### `lupo_actor_training`

**Purpose:** Tracks training data and learning events for each actor, including type, data, and metadata.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| training_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | NO |  | Reference to lupo_actors |
| training_type | VARCHAR(64) | NO |  | Type of training event |
| training_data | TEXT | YES | NULL | Training content/data |
| training_metadata | JSON | YES | NULL | Additional metadata |
| resulted_in_actor_memory_id | BIGINT | YES | NULL | `lupo_actor_memory.actor_memory_id` created by this training (application-managed reference) |
| resulted_in_skill_id | BIGINT | YES | NULL | Skill created/updated by this training |
| resulted_in_tool_id | BIGINT | YES | NULL | Tool created/updated by this training |
| started_ymdhis | BIGINT | NO | (application) | UTC timestamp |
| completed_ymdhis | BIGINT | YES | NULL | UTC timestamp |
| updated_ymdhis | BIGINT | YES | NULL | UTC timestamp |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_actor_training_actor | actor_id | Lookup by actor |
| idx_actor_training_type | training_type | Lookup by type |
| idx_actor_training_deleted | is_deleted | Soft delete filter |

### `lupo_auth_users`

**Purpose:** Stores user authentication credentials, profile data, and authentication state.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| auth_user_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| email | VARCHAR(255) | NO |  | User email address (unique) |
| password_hash | VARCHAR(255) | NO |  | Bcrypt hash (cost factor 12) of user password |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| last_login_ymdhis | BIGINT | YES | NULL | UTC timestamp of last login |
| is_active | TINYINT | NO | 1 | Account active flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |
| created_by_actor_id | BIGINT | YES | NULL | Actor who created this user |
| updated_by_actor_id | BIGINT | YES | NULL | Actor who last updated this user |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_auth_users_email | email | Unique email lookup |
| idx_auth_users_active | is_active, created_ymdhis | Filter active users |
| idx_auth_users_deleted | is_deleted, created_ymdhis | Filter soft-deleted rows |

**Application Logic Requirements:**

- [x] IDs generated via `IdGenerator::generate()` 
- [x] Relationships enforced in service layer (not database)
- [x] Soft delete via `$service->delete()` sets `is_deleted = 1` and `deleted_ymdhis` 
- [x] No direct DELETE statements

### `lupo_actors`

**Purpose:** Defines system actors (users, AI agents, system processes) and their metadata.

**PK / clock (seed vs runtime — PRD 00 §3.2.1):**

```sql
CREATE TABLE {{prefix}}actors (
    actor_id BIGINT NOT NULL,
    -- Seed: fixed low ids from install/seed (e.g. WOLFIE=1, LILITH=2, ANUBIS=19)
    -- Runtime: IdGenerator::generate() → YYYYMMDDHHIISS + 4-digit suffix (typically >= 2026…)

    created_ymdhis BIGINT NOT NULL,
    -- Seed: installation packed UTC OR 0 (immemorial / before temporal tracking)
    -- Runtime: same 14-digit prefix as actor_id at insert

    -- …remaining columns: see install_new_lupopedia.sql
);
```

**Seed actors (`actor_id` in registry / install, workspace rule `actor_id` < 2026):** Fixed ids — **`created_ymdhis`** is **not** derivable from **`actor_id`**. Use the **`created_ymdhis`** column as stored.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| actor_id | BIGINT | NO | (application) | Primary key: **runtime** rows use **`IdGenerator::generate()`**; **seed / install** rows use **fixed reserved ids** (e.g. 1, 2, 19) per **`install_new_lupopedia.sql`** — see PRD 00 §3.2 registry exception |
| actor_name | VARCHAR(64) | NO |  | Unique actor identifier |
| actor_type | VARCHAR(32) | NO | 'human' | Type: human, agent, system |
| display_name | VARCHAR(255) | YES | NULL | Human-readable display name |
| description | TEXT | YES | NULL | Actor description |
| created_ymdhis | BIGINT | NO | (application) | Packed UTC **`YYYYMMDDHHIISS`**, **`0`** for immemorial seed, or install-time UTC — **independent** of whether **`actor_id`** is low (seed) or timestamp-shaped (runtime) |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_active | TINYINT | NO | 1 | Actor active flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |
| created_by_actor_id | BIGINT | YES | NULL | Actor who created this actor |
| updated_by_actor_id | BIGINT | YES | NULL | Actor who last updated this actor |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_actors_name | actor_name | Unique name lookup |
| idx_actors_type | actor_type, is_active | Filter by type and status |
| idx_actors_deleted | is_deleted, created_ymdhis | Filter soft-deleted rows |

**Actor ID and Workspace Path Rules (v4.0.93):**

- **Core actors**: All `actor_id < 2026` are reserved for install-time/system actors. These IDs are immutable and determine folder placement.
- **Seed `created_ymdhis`:** For **install/seed** actors with fixed low **`actor_id`**, set **`created_ymdhis`** to the **installation** packed UTC, **`0`** (pre-existing / before temporal tracking), or another documented constant — it **does not** have to match digits inside **`actor_id`**.
- **Runtime actors**: Actors created after install must use deterministic timestamp BIGINT IDs in `YYYYMMDDHHIISS + 4 random digits` format (minimum `202601010000000000`). Set **`created_ymdhis`** to the **14-digit prefix** of the new **`actor_id`** at insert (same pattern as other IdGenerator-backed rows).
- **Workspace path resolution rule:**
  - if `actor_id < 2026` (core actors): `lupo-actors/<actor_id>/`
  - otherwise (runtime actors): `lupo-actors/YYYY/MM/<actor_id>/` where YYYY/MM are extracted from the timestamp ID
- **Actor IDs are immutable**: Once assigned, an actor's ID never changes and determines its permanent folder location.

### Actor Merge Protocol

When duplicate actors are detected, merge using:

```sql
-- 1. Identify source and target
-- 2. Reassign all foreign references from source to target
-- 3. Update lupo_actor_auth_users: source.status = 'merged', target.is_primary = 1
-- 4. Log in lupo_actor_actions with action_type='actor_merge'
-- 5. Soft delete source actor
UPDATE lupo_actors SET is_deleted = 1, deleted_ymdhis = CURRENT_UTC WHERE actor_id = source_id;
```

**Lineage**: `lupo_actor_actions` tracks merge history with `action_type='actor_merge'` and `metadata_json` containing source/target.

### Deterministic ID Path Resolution

| actor_id range | workspace path | example |
|----------------|----------------|---------|
| < 2026 | `lupo-actors/{actor_id}/` | `lupo-actors/2/` |
| ≥ 2026, year YYYY | `lupo-actors/YYYY/MM/{actor_id}/` | `lupo-actors/2026/03/202603311735001234/` |

**Resolution Logic:**
```php
if ($actor_id < 2026) {
    $path = "lupo-actors/{$actor_id}/";
} else {
    $year = substr($actor_id, 0, 4);
    $month = substr($actor_id, 4, 2);
    $path = "lupo-actors/{$year}/{$month}/{$actor_id}/";
}
```

### `lupo_actor_auth_users`

**Purpose:** Many-to-many relationship between system actors and authentication users, enabling single user to have multiple actor personas.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| actor_auth_user_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| auth_user_id | BIGINT | NO |  | Foreign reference to lupo_auth_users |
| relationship_role | VARCHAR(64) | NO | 'supporting_human' | Role type in relationship |
| is_primary | TINYINT | NO | 0 | Whether this is the primary actor for the user |
| routing_priority | SMALLINT | NO | 100 | Priority for actor selection |
| status | VARCHAR(32) | NO | 'active' | Relationship status |
| metadata_json | JSON | YES | NULL | Additional relationship metadata. **Schema**: See `lupo-docs/doctrine/METADATA_JSON_SCHEMA.md` |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_actor_auth_users_unq_actor_user_role | actor_id, auth_user_id, relationship_role | Unique constraint |
| idx_actor_auth_users_auth_user_status | auth_user_id, status | User relationship lookup |
| idx_actor_auth_users_actor_status_primary_priority | actor_id, status, is_primary, routing_priority | Actor selection |

### `lupo_sessions`

**Purpose:** DB-backed session authority (Model A). The browser stores only `session_id`; actor binding, CSRF, activity, and fingerprint hashes live in this table. Canonical implementation: `app/auth/Session.php`. **Constitutional note:** `session_id` is an opaque cryptographic string (e.g. `bin2hex(random_bytes(32))`), not a BIGINT from `IdGenerator` — security tokens are exempt from numeric PK doctrine.

**Columns (align `install_new_lupopedia.sql`):**

| Column | Type | Nullable | Description |
|--------|------|----------|-------------|
| session_id | VARCHAR(128) | NO | Opaque session token |
| actor_id | BIGINT | NO | Bound actor |
| actor_name | VARCHAR(64) | YES | Display hint |
| federation_node_id | BIGINT | NO | Default 0 |
| ip_hash | VARCHAR(128) | YES | Hash of **resolved** client IP (see Session Identity Resolution below); not raw IP |
| ua_hash | VARCHAR(255) | YES | Hash of User-Agent |
| csrf_token | VARCHAR(128) | YES | CSRF secret |
| last_activity_ymdhis | BIGINT | NO | Packed UTC |
| created_ymdhis | BIGINT | NO | Packed UTC |
| updated_ymdhis | BIGINT | NO | Packed UTC |
| name_key | VARCHAR(100) | YES | Named session key |
| is_named | TINYINT | NO | Named session flag |
| metadata | JSON | YES | Extension point: Crafty-parity keys (`identity`, `cookied`, embed flags, `auth_user_id`) until promoted to first-class columns |
| is_active | TINYINT | NO | Active flag |
| is_expired | TINYINT | NO | Expired flag |
| is_revoked | TINYINT | NO | Revoked flag |
| is_deleted | TINYINT | NO | Soft delete |
| last_seen_ymdhis | BIGINT | YES | Heartbeat / last seen |
| expires_ymdhis | BIGINT | YES | Expiration packed UTC |
| security_level | VARCHAR(64) | YES | Optional policy |
| system_context | VARCHAR(64) | YES | Optional context |
| status | VARCHAR(32) | YES | Optional status string |

**Indexes (install):** `sessions_idx_actor`, `sessions_idx_actor_name`, `sessions_idx_last_activity`, `sessions_idx_federation`, `sessions_idx_is_active`, `sessions_idx_last_seen`.

---

### Session Identity Resolution (4.0.96+)

Lupopedia inherits a **multi-layer session identity** model from **Crafty Syntax 3.7.5**, suited to shared hosting: cookies may be blocked, client IPs are usually behind proxies/CDNs, and legacy embeds may pass `SESSIONID` in the query string.

**Historical source:** `craftysyntax-reference/functions.php` (and related session managers). **Lupopedia target:** apply the same **resolution order** when computing fingerprints and when implementing visitor/embed parity; persist binding via **`ip_hash` / `ua_hash`** and optional **`metadata`** JSON for `identity` / `cookied` until dedicated columns exist.

#### IP address detection (prefer real client, not only proxy hop)

**Do not use `$_SERVER['REMOTE_ADDR']` alone** when a trusted chain of proxy headers may carry the original client IP.

**Header order (conceptual port of Crafty `get_ipaddress()`):**

```php
function get_ipaddress() {
    $headers = array(
        'HTTP_CF_CONNECTING_IP',
        'HTTP_TRUE_CLIENT_IP',
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_REAL_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
    );
    foreach ($headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ips = explode(',', $_SERVER[$header]);
            foreach ($ips as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP,
                    FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
    }
    return isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';
}
```

**Deployment note:** Behind a **trusted** reverse proxy on a private network, “first public IP” heuristics may fail; operators may need a **trusted-proxy allowlist** and to accept RFC1918 forwarded addresses when validated. **IPv6:** Class-C bucketing below is **IPv4-only**; IPv6 clients need an explicit policy (e.g. /64 prefix + cookie name) in a follow-on spec.

**Current code path:** `App\Auth\Session` fingerprints from `$_SERVER['REMOTE_ADDR']` and `HTTP_USER_AGENT` today (`app/auth/Session.php`); **production parity** with this PRD requires feeding **`get_ipaddress()`** (or equivalent) into the hash step before persistence.

#### Identity string (Class C + session cookie name)

Used for **cookieless** recovery: same site / network grouping without collapsing distinct browsers (cookie name differentiates jars).

```php
function get_identitystring($ip, $sessionName) {
    if (strpos($ip, ':') !== false) {
        return null;
    }
    $ipParts = explode('.', $ip);
    if (count($ipParts) < 3) {
        return null;
    }
    $classC = $ipParts[0] . '.' . $ipParts[1] . '.' . $ipParts[2];
    return $classC . '-' . $sessionName;
}
```

#### Session ID detection (fallback chain)

Order of precedence for an incoming **session token** (Crafty `detectID` / embed style):

1. Explicit request parameter map (e.g. `$UNTRUSTED['SESSIONID']` in legacy code — never trust without validation)
2. `$_GET['SESSIONID']`
3. `$_POST['SESSIONID']`
4. `$_COOKIE[$sessionName]`

**`matchip` (optional):** When enabled, a recovered `SESSIONID` must belong to a row whose stored IP fingerprint is compatible with the current client (e.g. same Class C as resolved IP), reducing session leakage via referer URLs.

#### Cookieless recovery (`allow_ip_host_sessions`)

If no token is found and policy allows host/IP sessions, select the newest matching session for the computed **identity string**. Crafty stored **`identity`** and **`cookied`** on the sessions row. In Lupopedia, persist equivalents under **`metadata`** (e.g. JSON keys `identity`, `cookied`) **or** add columns in a future install revision. Illustrative Crafty-era SQL shape:

```sql
SELECT sessionid FROM {{prefix}}sessions
WHERE identity = :identity AND cookied = 'N'
ORDER BY created_ymdhis DESC
LIMIT 1
```

**Portable rule:** Prefer resolving this in **application code** with `PDO_DB` (filter in PHP or use JSON helpers already permitted by your install doctrine) rather than vendor-specific JSON operators in SQL.

#### New session token generation

- **Crafty Syntax legacy (visitor / embed):** `md5(uniqid($client_ip . $user_agent . microtime(), true))` plus `setcookie($sessionName, $SESSIONID, …)`.
- **Lupopedia `App\Auth\Session` (admin / Model A):** `session_id = bin2hex(random_bytes(32))` and hashed fingerprints — see `createSession()` in `app/auth/Session.php`.

Choose the generator to match the surface: **cryptographic** for authenticated app sessions; **legacy-compatible** only where Crafty wire protocol requires it.

#### Fingerprint storage mapping

| Concept | Crafty (historical) | Lupopedia `lupo_sessions` |
|--------|---------------------|----------------------------|
| Session token | `SESSIONID` | `session_id` |
| Identity key | `identity` (Class C + cookie name) | `metadata.identity` (or future column) |
| Cookie vs cookieless | `cookied` `'Y'` / `'N'` | `metadata.cookied` (or future column) |
| Client IP | Plain IP for logic | **`ip_hash`** only (hash of resolved IP string) |
| User-Agent | Plain UA for logic | **`ua_hash`** only |

#### Two browsers on the same PC (same Class C)

Different browsers → different cookie jars → different `SESSIONID` values → different rows. The shared **Class C** component does not merge them; the **session cookie name** (and tokens) keeps sessions distinct.

#### Why this matters

- Corporate proxies and privacy tools block cookies.
- Real IPs are hidden behind Cloudflare, load balancers, and CDNs.
- Office / library NAT must not collapse unrelated users into one session.
- Embeds may still pass `SESSIONID` in GET/POST for historical clients.

---

### `lupo_memory_nodes` (Polymorphic Memory Model)

**Purpose:** Defines memory as first-class, polymorphic nodes in the semantic graph. Memory nodes are not actors; they are linked to actors, channels, or other entities via edges. This enables flexible, multi-entity memory modeling and avoids conflating memory with actor identity.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| memory_node_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| owner_type | VARCHAR(32) | NO |  | Polymorphic owner type (e.g., 'actor', 'channel', 'system', 'custom') |
| owner_id | BIGINT | NO |  | Polymorphic owner id (references the owning entity) |
| memory_type | VARCHAR(64) | NO |  | Type of memory (e.g., 'observation', 'consolidated', 'preference', 'note') |
| memory_key | VARCHAR(128) | YES | NULL | Optional semantic key for memory (topic, tag, etc.) |
| memory_slug | VARCHAR(128) | NO |  | Filesystem-safe slug for mapping to lupo-memory/YYYY/MM/{memory_slug} |
| memory_value | TEXT | YES | NULL | Memory content/value |
| context_json | JSON | YES | NULL | Additional context (confidence, provenance, etc.) |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |
| created_by_actor_id | BIGINT | YES | NULL | Actor who created this memory node |
| updated_by_actor_id | BIGINT | YES | NULL | Actor who last updated this memory node |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_memory_nodes_owner | owner_type, owner_id, is_deleted | Lookup by owner |
| idx_memory_nodes_type | memory_type, is_deleted | Filter by memory type |
| idx_memory_nodes_key | memory_key, is_deleted | Semantic key lookup |
| idx_memory_nodes_slug | memory_slug, is_deleted | Filesystem lookup by slug |
| idx_memory_nodes_deleted | is_deleted, created_ymdhis | Soft delete filter |

**Doctrine:**

- Memory nodes are not actors and do not appear in lupo_actors.
- All relationships (ownership, provenance, consolidation, contradiction, etc.) are modeled via lupo_edges.
- owner_type/owner_id enables polymorphic linkage (actor, channel, system, etc.).
- Memory can be attached to any entity type, not just actors.
- `memory_slug` is a unique, filesystem-safe identifier for mapping memory nodes to disk. Memory JSON files are stored in `lupo-memory/YYYY/MM/{memory_slug}` where YYYY/MM is derived from `created_ymdhis`.
- Edges (lupo_edges) define relationships: e.g., 'owns', 'consolidates_from', 'contradicts', 'evidence_of'.
- Memory nodes support soft delete and full audit trail.
- No foreign keys; all joins in application layer.

**Usage Example:**

```php
$memoryService = new MemoryNodeService();
$memoryId = $memoryService->create([
  'owner_type' => 'actor',
  'owner_id' => $actorId,
  'memory_type' => 'observation',
  'memory_key' => 'lead_qualification',
  'memory_value' => 'Qualified lead: Acme Corp',
  'context_json' => json_encode(['confidence' => 0.92, 'source' => 'dialog_message_123']),
  'created_by_actor_id' => $actorId,
]);
// Link memory to actor via lupo_edges
$edgeService->create([
  'left_object_type' => 'actor',
  'left_object_id' => $actorId,
  'right_object_type' => 'memory_node',
  'right_object_id' => $memoryId,
  'edge_type' => 'owns',
]);
```

**Rationale:**

- Avoids conflating memory with actor identity.
- Enables multi-entity, multi-type memory modeling (channels, actors, system, etc.).
- Supports future federation and cross-entity memory sharing.
- Doctrine: Memory is a node, not an actor; all linkage is via edges.

### Session Cleanup Strategy

- **Expiration**: Sessions expire after `expires_ymdhis` (policy-defined maximum, e.g. 30 days).
- **Cleanup (soft delete, constitutional default):** Periodic job marks expired rows:
  ```sql
  UPDATE {{prefix}}sessions SET is_deleted = 1, updated_ymdhis = :now
  WHERE expires_ymdhis IS NOT NULL AND expires_ymdhis < :cutoff AND is_deleted = 0
  ```
  Use packed UTC BIGINT for `:now` and `:cutoff` (`gmdate('YmdHis')` / doctrine helpers). If `deleted_ymdhis` is added to the sessions row in a future revision, set it here as well.
- **Archive**: Operational detail may log to `lupo_unified_log` where required.

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 01_core_identity | This → 02_channels_discussions | Actor attribution | actor_id columns |
| 01_core_identity | This → 07_agents_faucets | Agent identity | agent_id columns |
| 01_core_identity | This → 06_content_management | Content ownership | created_by_actor_id columns |
| 01_core_identity | This → 03_truth_knowledge | Question/answer attribution | actor_id columns |
| 01_core_identity | This → 08_governance_rules | Permission checks | actor_id columns |

## State Transitions

| State | Description | Transition To |
|--------|-------------|--------------|
| active | Normal operation | deleted (soft) |
| deleted | Soft-deleted | N/A (can't be restored without explicit action) |

## Security & Privacy

All PII stored with actor attribution (created_by_actor_id, updated_by_actor_id)

Soft delete preserves audit trail for compliance

IP addresses stored only for session tracking and security logs

Passwords stored as bcrypt hashes, never in plain text

## Testing Requirements

Unit tests for all CRUD operations

Integration tests for actor-auth-user relationships

Performance tests for session lookup and cleanup

Soft delete behavior verification

## Migration Notes

Fresh Install Only - No upgrade path until 4.1.0.

Crafty Syntax Import Mapping:

- livehelp_users → lupo_auth_users + lupo_actors
- livehelp_sessions → lupo_sessions
- livehelp_operators → lupo_actors (type='human')

## Usage Patterns

```php
// Create actor
$service = new ActorService();
$actorId = $service->create($data);

// Create user-actor relationship
$authService = new ActorAuthService();
$relationshipId = $authService->linkUserToActor($userId, $actorId, 'primary');

// Session management
$sessionService = new SessionService();
$sessionId = $sessionService->create($actorId, $authUserId);

// Soft delete
$service->softDelete($actorId, $currentActorId);

---

## Context‑Typed, Status‑Aware, Directional Edged Memory Doctrine (4.0.96)

1. Memory in Lupopedia is represented as a directed graph of nodes and edges. 
  Each memory node is a first-class entity in the semantic network and may be 
  owned by actors, departments, auth_users, channels, federation nodes, or the 
  global system.

2. Every edge in the memory graph has FOUR dimensions:
  - **edge type** (the relationship)
  - **edge context** (the classification of the memory)
  - **edge status** (the epistemic support level)
  - **edge direction** (the traversal orientation)

3. **Edge Direction** defines whether the relationship is:
  - unidirectional (A → B)
  - bidirectional (A ↔ B)
  - restricted-direction (A → B but not B → A unless explicitly defined)
  Reverse traversal MUST NOT be inferred unless explicitly defined.

4. **Edge Type** defines the relationship between nodes, including but not 
  limited to:
  - influences
  - inherits
  - authored_by
  - observed_by
  - contradicts
  - supports
  - consolidates_from
  - refines
  - overrides

5. **Edge Context** defines the classification of the memory node. Context is 
  not based on the content of the memory, but on the structural support 
  provided by the graph. The primary context classifications are:
  - doctrine
  - experiential
  - system_generated
  - countermeasure_generated
  - summary
  - contradictory
  - deprecated

6. **Edge Status** defines the epistemic support level of the memory node:
  - **unsupported**: insufficient supporting edges; provisional memory.
  - **supported**: sufficient supporting edges; validated memory.
  - **needs_review**: conflicting, incomplete, or ambiguous edges requiring 
    agent or human intervention.

7. When `edge_status = 'needs_review'`, a **review_reason** MUST be provided. 
  This field explains *why* the edge requires review and *which agent* should 
  handle it. Examples include:
  - orphaned_edge
  - contradiction
  - new_doctrine
  - schema_drift
  - consolidation_candidate
  - integrity_unknown
  - human_escalation

  Agents use this field to determine their work queues:
  - ANUBIS handles: integrity_unknown, orphaned_edge
  - THOTH handles: schema_drift, contradiction, new_doctrine
  - KAIROS handles: consolidation_candidate
  - Human operator handles: human_escalation

8. Memory nodes may transition between statuses as edges are added, removed, 
  or reclassified. A node may move from unsupported → supported when 
  sufficient supporting edges accumulate.

9. Actors inherit memory edges from:
  - their department
  - their auth_user
  - their federation node
  - their assigned faucets
  - their assigned tasks

10. Memory traversal is context-aware and direction-aware. Actors may only 
   traverse edges permitted by their boundaries, department rules, auth_user 
   pairing, faucet assignments, and operational mode (live, simulation, 
   analysis).

11. No inference is allowed. All edges, contexts, statuses, directions, and 
   review reasons must be explicitly defined in PRDs, database rows, or 
   system-generated memory.

12. Memory is not a flat file. It is a structured, typed, classified, 
   status-aware, direction-aware graph. Traversal depth determines visible 
   memory; deeper traversal reveals more context, subject to boundary rules.

13. All changes to memory structure, edge types, edge contexts, edge statuses, 
   edge directions, or review reasons must be documented in PRDs and versioned.
```
