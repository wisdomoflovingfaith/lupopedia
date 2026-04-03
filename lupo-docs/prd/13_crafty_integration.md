---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/prd/13_crafty_integration.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/13_crafty_integration.md"
  last_modified_utc: "20260403220739"
  channel_id: 42
  thread_id: "prd-grouped"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root|lilith:audit"
  artifact_type: "prd"
  artifact_kind: "database_namespace"
  purpose: "PRD for Crafty Syntax 3.7.5 integration tables - ACTIVE, NOT deprecated"
  tags:
  - "prd"
  - "database"
  - "namespace"
  - "crafty_integration"
  - "active"
  - "import"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/database/lupopedia/tables/migrations/"
      type: references
      weight: 1.0
      reason: "livehelp_* → lupo_* per-table mapping — mandatory before code"
    - to: "lupo-docs/database/lupopedia/tables/"
      type: references
      weight: 0.85
      reason: "Table documentation hub; pair with migrations/ for mappings"
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: references
      weight: 0.9
      reason: "§4.1 no schema guessing; §4.2 Y/N → tinyint"
    - to: "lupo-docs/prd/01_core_identity.md"
      type: references
      weight: 1.0
      reason: "Crafty users map to Lupopedia actors"
    - to: "lupo-docs/prd/02_channels_discussions.md"
      type: references
      weight: 1.0
      reason: "Crafty chat imports to Lupopedia discussions"
    - to: "lupo-docs/prd/05_auth_user_actor_agent_transformation.md"
      type: references
      weight: 1.0
      reason: "Auth user, actor, department act-as; chat-time identity chain"
    - to: "lupo-docs/prd/15_actors.md"
      type: references
      weight: 1.0
      reason: "Actor types, department-first eligibility, visitor-facing operator as actor"
    - to: "lupo-docs/prd/25_departments_system.md"
      type: references
      weight: 1.0
      reason: "Department-scoped actors and chat routing"
    - to: "lupo-docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Canonical approved: operator to actor; visitor sees actor_id"
    - to: "lupo-docs/implementations/13_crafty_integration/README.md"
      type: implements
      weight: 1.0
      reason: "Open questions and chat-model follow-ups live under implementations/13_crafty_integration/questions/"
lupopedia.footer:
  last_verified: "20260403220410"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"
---

# PRD: Crafty Syntax 3.7.5 Integration Tables

## Overview

**Namespace Purpose:** Enables data import from Crafty Syntax 3.7.5 and maintains runtime compatibility with Crafty features. These tables are **ACTIVE** and **NOT DEPRECATED** - they are core to Lupopedia's ability to migrate from and operate with Crafty Syntax data.

**Supported transition in 4.0.x:** The only **data-bearing** path from a legacy install into Lupopedia on the **4.0.x** line is **Crafty Syntax 3.7.5 → Lupopedia** (schema + seed from **`install_new_lupopedia.sql`**, then **`import_from_old_crafty_syntax.sql`**). There is **no** Lupopedia→Lupopedia upgrade until **4.1.0** gates in **PRD 33** / **PRD 27** are satisfied; for 4.0.x schema changes, **drop Lupopedia tables** and **re-install** from current DDL.

**Primary Actors:** 
- Migration administrators (via import scripts)
- Legacy system compatibility (via runtime features)
- Data historians (via mapping tables)

**Constitutional Compliance:** All tables in this namespace follow Lupopedia constitutional rules:
- NO foreign keys (relationships in application logic)
- NO triggers
- NO stored procedures
- BIGINT timestamps (YYYYMMDDHHIISS UTC)
- Explicit ID generation (application layer)
- Soft delete (is_deleted + deleted_ymdhis)

**Implementer obligation:** Before using any table listed below in code or SQL, read the matching **`livehelp_*_migration.md`** under **`lupo-docs/database/lupopedia/tables/migrations/`** and confirm columns in **`install_new_lupopedia.sql`**. **PRD 33** §4.1 forbids guessing names from TOON/JSON or Crafty memory alone.

**Canonical mental model (approved):** **[`ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`](../doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md)** — Crafty **operator-centric** chat vs Lupopedia **actor + department** runtime; import must produce **`lupo_actors`** + **`lupo_actor_departments`** (and mappings) so visitors are not modeled as chatting with a bare human row only.

## Tables in This Namespace

| Table | Purpose | Primary Key | Key Application Relationships |
|-------|---------|-------------|------------------------------|
| `lupo_crafty_user_mapping` | Maps Lupopedia actors to Crafty Syntax operators | `mapping_id` | Links `lupo_actors` to legacy users |
| `lupo_crafty_syntax_auto_invite` | Auto-invite rules for chat | `auto_invite_id` | Chat invitation automation |
| `lupo_crafty_syntax_chat_mod_departments` | Chat module to department mapping | `chat_mod_dept_id` | Legacy department routing |
| `lupo_crafty_syntax_chat_questions` | Pre-chat questions for visitors | `chat_question_id` | Visitor qualification system |
| `lupo_crafty_syntax_layer_invites` | Layer-based invitations (image maps) | `layer_invite_id` | Visual invitation system |
| `lupo_crafty_syntax_leave_message` | Offline messages from visitors | `leave_message_id` | Visitor message capture |

## Table Details

### `lupo_crafty_user_mapping`

**Purpose:** Maps Lupopedia actors to Crafty Syntax operators for seamless migration and operation.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| mapping_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| lupo_actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| crafty_operator_id | INT | NO |  | Legacy Crafty operator ID |
| crafty_username | VARCHAR(255) | NO |  | Legacy Crafty username |
| mapping_type | VARCHAR(32) | NO | 'import' | Type: import, sync, legacy |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_active | TINYINT | NO | 1 | Mapping active flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_crafty_mapping_lupo_actor | lupo_actor_id, is_active, is_deleted | Lupopedia actor lookup |
| idx_crafty_mapping_crafty_operator | crafty_operator_id, is_active, is_deleted | Legacy operator lookup |
| idx_crafty_mapping_type | mapping_type, is_active, is_deleted | Type-based queries |

### `lupo_crafty_syntax_auto_invite`

**Purpose:** Manages automatic chat invitation rules based on visitor behavior and criteria.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| auto_invite_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| invite_type | VARCHAR(32) | NO | 'time_based' | Type: time_based, action_based, page_based |
| trigger_condition | TEXT | NO |  | JSON condition for invitation |
| invite_message | TEXT | YES | NULL | Custom invitation message |
| delay_seconds | INT | NO | 30 | Delay before invitation |
| max_invites_per_session | INT | NO | 1 | Maximum invitations per session |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_active | TINYINT | NO | 1 | Rule active flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_auto_invite_actor | actor_id, is_active, is_deleted | Actor's invite rules |
| idx_auto_invite_type | invite_type, is_active, is_deleted | Type-based queries |

### `lupo_crafty_syntax_chat_questions`

**Purpose:** Stores pre-chat qualification questions for visitors.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| chat_question_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| question_text | TEXT | NO |  | Question content |
| question_type | VARCHAR(32) | NO | 'text' | Type: text, choice, rating |
| options_json | JSON | YES | NULL | Multiple choice options |
| is_required | TINYINT | NO | 0 | Whether question is required |
| sort_order | INT | NO | 0 | Display order |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_active | TINYINT | NO | 1 | Question active flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_chat_questions_actor | actor_id, sort_order, is_active, is_deleted | Actor's questions |
| idx_chat_questions_type | question_type, is_active, is_deleted | Type-based queries |

### `lupo_crafty_syntax_layer_invites`

**Purpose:** Manages layer-based visual invitation system using image maps.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| layer_invite_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| layer_name | VARCHAR(255) | NO |  | Layer identifier |
| image_map_url | VARCHAR(512) | YES | NULL | URL to image map |
| invite_coordinates | JSON | YES | NULL | Clickable coordinates |
| invite_message | TEXT | YES | NULL | Custom invitation message |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| click_count | INT | NO | 0 | Number of times clicked |
| is_active | TINYINT | NO | 1 | Layer invite active flag |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_layer_invites_actor | actor_id, is_active, is_deleted | Actor's layer invites |
| idx_layer_invites_layer | layer_name, is_active, is_deleted | Layer-based queries |

### `lupo_crafty_syntax_leave_message`

**Purpose:** Captures offline messages from visitors when operators are unavailable.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| leave_message_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| visitor_identifier | VARCHAR(255) | NO |  | Visitor session identifier |
| message_text | TEXT | NO |  | Leave message content |
| email_address | VARCHAR(255) | YES | NULL | Visitor email for response |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_read | TINYINT | NO | 0 | Whether message has been read |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_leave_messages_actor | actor_id, is_read, is_deleted | Actor's messages |
| idx_leave_messages_visitor | visitor_identifier, created_ymdhis, is_deleted | Visitor message lookup |

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 13_crafty_integration | This → 01_core_identity | User mapping | lupo_actor_id references |
| 13_crafty_integration | This → 02_channels_discussions | Chat import | Message conversion to discussions |
| 13_crafty_integration | This → 06_content_management | File handling | File attachment mapping |

## State Transitions

| State | Description | Transition To |
|--------|-------------|--------------|
| active | Normal operation | inactive, deleted (soft) |
| inactive | Temporarily disabled | active, deleted (soft) |
| deleted | Soft-deleted | N/A (can't be restored without explicit action) |

## Security & Privacy

Legacy Crafty data is mapped but isolated from current authentication

Visitor messages are stored with minimal PII

Soft delete preserves migration history for compliance

## Testing Requirements

Unit tests for all import/mapping operations

Integration tests for Crafty feature compatibility

Performance tests for legacy data access

Soft delete behavior verification

## Migration Notes

**Fresh Install Only - No upgrade path until 4.1.0.**

### Crafty Syntax Import Mapping:

| Crafty Table | Lupopedia Table | Purpose |
|---------------|------------------|---------|
| livehelp_users | lupo_auth_users + lupo_actors + lupo_crafty_user_mapping | User account migration |
| livehelp_messages | lupo_dialog_messages | Chat message history |
| livehelp_sessions | lupo_sessions | Session continuity |
| livehelp_operators | lupo_actors (+ `lupo_actor_departments`, mapping) | Operators become **actors**; see **Identity model after import** below |
| livehelp_departments | lupo_departments | Department structure |
| livehelp_autoinvite | lupo_crafty_syntax_auto_invite | Auto-invite rules |
| livehelp_chat_questions | lupo_crafty_syntax_chat_questions | Pre-chat questions |
| livehelp_leave_message | lupo_crafty_syntax_leave_message | Offline messages |

### Identity model after import (Crafty operator → Lupopedia actor)

**Crafty runtime mental model:** visitor ↔ **human operator** (legacy user/operator row) as the main “who answers.” **Lupopedia target:** visitor-facing chat resolves **`actor_id`** first (**department-scoped** via **`lupo_actor_departments`**), with **human `auth_user`** and optional **LLM/automation** as **fallback layers** — **[PRD 05](05_auth_user_actor_agent_transformation.md)**. **`livehelp_operators`** still materialize as **`lupo_actors`** rows; import and seed must also place them in **`lupo_actor_departments`** (and related mapping) so runtime does **not** treat the Crafty operator id as the only identity key. **Actors belong to departments, not to individual users** — **[PRD 15](15_actors.md)**. Answered design threads: [implementations/13_crafty_integration/questions/THREAD_INDEX.md](../implementations/13_crafty_integration/questions/THREAD_INDEX.md).

### Runtime Compatibility:

These tables enable Lupopedia to:
1. **Import** all Crafty Syntax 3.7.5 data
2. **Maintain** compatibility with existing Crafty installations
3. **Provide** seamless migration path for users
4. **Preserve** all Crafty functionality within Lupopedia architecture

## Usage Patterns

```php
// Import Crafty users
$importService = new CraftyImportService();
$importService->importUsers($craftyDbConnection);

// Map existing operator to actor
$mappingService = new CraftyUserMappingService();
$mappingId = $mappingService->createMapping($lupoActorId, $craftyOperatorId);

// Process auto-invite
$autoInviteService = new AutoInviteService();
$inviteId = $autoInviteService->checkAndInvite($visitorSession, $rules);

// Handle leave message
$leaveMessageService = new LeaveMessageService();
$messageId = $leaveMessageService->createLeaveMessage($visitorId, $messageText);
```

## Implementation workspace (visitor chat — history and follow-ups)

**Normative model:** visitor / operator migration and **actor-first** runtime are specified in **PRD 05**, **PRD 15**, **PRD 25**, and **Identity model after import** above. **Do not** bury new open design work inline in this PRD — use the hub below.

- **Hub:** [lupo-docs/implementations/13_crafty_integration/README.md](../implementations/13_crafty_integration/README.md)  
- **Index:** [lupo-docs/implementations/13_crafty_integration/questions/THREAD_INDEX.md](../implementations/13_crafty_integration/questions/THREAD_INDEX.md)  

Visitor-chat **QUESTION** threads (answered; audit/runtime tasks may continue in code): **`THREAD_INDEX.md`** — `20260403214146` (backing auth / LLM), `20260403215011` (actor-first code), `20260403215024` (PRD doc pass).

## Important Notes

⚠️ **These tables are ACTIVE and NOT deprecated**
- They are essential for Crafty Syntax 3.7.5 import
- They maintain runtime compatibility with Crafty features
- LiveHelp chat functionality depends on these tables
- Do NOT modify structure without updating import scripts
- These tables will remain active post-4.1.0 for compatibility
