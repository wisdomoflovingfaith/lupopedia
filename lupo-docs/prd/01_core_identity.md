---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/prd/01_core_identity.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/01_core_identity.md"
  last_modified_utc: "20260331173500"
  channel_id: 42
  thread_id: "prd-grouped"
  actor_id: 2
  actor_name: "LILITH"
  delegation_chain: "lilith:audit|hephaestus:implementation"
  artifact_type: "prd"
  artifact_kind: "database_namespace"
  purpose: "PRD for core identity database tables"
  tags:
  - "prd"
  - "database"
  - "namespace"
  - "core_identity"
lupopedia.edges:
  outbound_edges:
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
    - to: "lupo-docs/versions/4.0.93/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Root constitutional system requirements"
lupopedia.footer:
  last_verified: "20260331140000"
  verified_by:
    actor_id: 2
    agent_name_identity: LILITH QA Agent
  orchestrator: "lilith:audit"
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

**4.0.x Release Continuity:** This namespace structure is designed for continuity across 4.0.x releases, with backward compatibility maintained through version-specific extensions and deprecation policies.

**Constitutional Compliance:** All tables in this namespace follow Lupopedia constitutional rules:
- NO foreign keys (relationships in application logic)
- NO triggers
- NO stored procedures
- BIGINT timestamps (YYYYMMDDHHIISS UTC)
- Explicit ID generation (application layer)
- Soft delete (is_deleted + deleted_ymdhis)

## Tables in This Namespace

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
| `lupo_actor_memory` | Actor memory storage (episodic, semantic, etc.) | `memory_id` | Links to `lupo_actors` |
| `lupo_actor_skills` | Actor skill registry and metadata | `skill_id` | Links to `lupo_actors` |
| `lupo_actor_tools` | Tools and utilities available to actors | `tool_id` | Links to `lupo_actors` |
| `lupo_actor_prompts` | Prompt templates and prompt history for actors | `prompt_id` | Links to `lupo_actors` |
| `lupo_actor_training` | Actor training data and learning events | `training_id` | Links to `lupo_actors` |

## Table Details

### `lupo_actor_memory`

**Purpose:** Stores memory items for each actor, supporting episodic, semantic, and contextual memory types.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| memory_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | NO |  | Reference to lupo_actors |
| memory_type | VARCHAR(64) | NO |  | Type of memory (episodic, semantic, etc.) |
| memory_key | VARCHAR(128) | NO |  | Unique key for memory item |
| memory_value | TEXT | YES | NULL | Memory content |
| context_json | JSON | YES | NULL | Additional context for memory |
| parent_memory_id | BIGINT | YES | NULL | Parent memory for lineage tracking |
| root_memory_id | BIGINT | YES | NULL | Root memory for lineage tracking |
| depth | TINYINT | NO | 0 | Depth in memory hierarchy |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp |
| updated_ymdhis | BIGINT | YES | NULL | UTC timestamp |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_actor_memory_actor | actor_id | Lookup by actor |
| idx_actor_memory_type_key | memory_type, memory_key | Fast lookup by type/key |
| idx_actor_memory_deleted | is_deleted | Soft delete filter |

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
| resulted_in_memory_id | BIGINT | YES | NULL | Memory created by this training |
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

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| actor_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_name | VARCHAR(64) | NO |  | Unique actor identifier |
| actor_type | VARCHAR(32) | NO | 'human' | Type: human, agent, system |
| display_name | VARCHAR(255) | YES | NULL | Human-readable display name |
| description | TEXT | YES | NULL | Actor description |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
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

- Reserved system range: all `actor_id < 2026` are reserved for install-time/system actors.
- Deterministic runtime range: actors created after install must use deterministic IDs in `YYYYMMDDHHIISS + 4 random digits` format.
- Lowest deterministic ID for year 2026 is `202601010000000000` (2026-01-01 00:00:00 UTC + `0000` suffix).
- Workspace path resolution rule:
  - if `actor_id < 2026`: `lupo-actors/<actor_id>/`
  - otherwise (deterministic IDs): `lupo-actors/YYYY/MM/<actor_id>/`

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
| metadata_json | JSON | YES | NULL | Additional relationship metadata |
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

**Purpose:** Tracks active sessions for actors, enabling stateful interactions across requests.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| session_id | VARCHAR(64) | NO | (application) | Primary key, session token |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| auth_user_id | BIGINT | YES | NULL | Foreign reference to lupo_auth_users |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| expires_ymdhis | BIGINT | NO | (application) | UTC timestamp when session expires |
| ip_address | VARCHAR(45) | YES | NULL | Client IP address **(Privacy Note: For GDPR compliance, consider storing hashed IP addresses in main table with raw IPs only in segregated audit logs)** |
| user_agent | TEXT | YES | NULL | Client user agent string |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_sessions_actor | actor_id, is_deleted | Actor session lookup |
| idx_sessions_expires | expires_ymdhis, is_deleted | Session expiration cleanup |
| idx_sessions_auth_user | auth_user_id, is_deleted | User session lookup |

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
```
