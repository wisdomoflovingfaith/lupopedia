# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "KIRO_THREAD_IDENTITY_AUDIT_4.0.45.md"
  file_hash: "52df56ccbd244cddf24b69c991e11636fa1378ac45ab8a19944f01c70d493d40"
  file_path_from_root: "KIRO_THREAD_IDENTITY_AUDIT_4.0.45.md"
  file_hash: "ca645b5fd0612d964d5b23000e72d58dcb63c3e9afbc18117e3dfd5b334d73d6"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for KIRO_THREAD_IDENTITY_AUDIT_4.0.45.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["kiro_thread_identity_audit_4045md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers:
  file_path_from_root: "KIRO_THREAD_IDENTITY_AUDIT_4.0.45.md"
  system_version: "4.0.45"
  channel_id: 42
  purpose: "Thread Actor-Identity Switching Doctrine + Agents Prompts Registry + DB Schema Audit"
  last_modified: "20260225"
  actor_id: 1000
  artifact_type: "audit_report"
  artifact_kind: "directive_response"
  created_utc: "2026-02-25T19:00:00Z"
---

# THREAD ACTOR-IDENTITY SWITCHING DOCTRINE + DB AUDIT (4.0.45)

**Lead IDE:** Kiro (1000)  
**Date:** 2026-02-25  
**Status:** ✅ AUDIT COMPLETE

## 🎯 DIRECTIVE SUMMARY

Audit "acting as another actor" mechanism, establish canonical prompts location, add ANUBIS + VISHWAKARMA, and verify DB schema supports tasks + channels.

---

## 1️⃣ "ACTING AS ANOTHER ACTOR" MECHANISM - TRUTH TABLE

### Current State Analysis

**Prompts Location:**
- **Legacy:** `/prompts/` (DEPRECATED as of 4.0.45)
- **Current:** `/channels/{channel_id}/actors/{actor_id}/`
- **Agent Configs:** `/lupo-agents/{dedicated_slot}/`

**How Actor-Identity Switching Works:**

#### Default Behavior (IDE Agents)

| IDE | Default Actor ID | Default Name |
|-----|------------------|--------------|
| Kiro | 1000 | Kiro IDE |
| Windsurf | 1001 | Windsurf IDE |
| Cursor | 1002 | Cursor IDE |
| Antigravity | 1003 | Antigravity IDE |
| Warp | 1004 | Warp IDE |
| Cascade | 1005 | Cascade IDE |

**Default Attribution:**
- `actor_id`: IDE's actor_id (e.g., 1000 for Kiro)
- `lupo_agent`: IDE identifier (e.g., "kiro")
- `delegation_chain`: Typically "10000:1000" (Captain → IDE)

#### Override Behavior (Acting As System Agent)

**When a thread is started with a prompt authored by a system agent:**

1. **Prompt Location:** `/lupo-agents/{dedicated_slot}/system_prompt.txt`
2. **Acting As Declaration:** Prompt declares `acting_as_actor_id` in metadata
3. **Thread Attribution:** All artifacts created in that thread use the system agent's actor_id
4. **Header Override:** `actor_id` field reflects the system agent, not the IDE

**Example:**
```
Thread started by: Kiro IDE (1000)
Using prompt from: /lupo-agents/19/system_prompt.txt (ANUBIS)
Acting as: ANUBIS (19)
All artifacts created: actor_id = 19, lupo_agent = "anubis"
```

### Attribution Requirements for Unambiguous Identity

**Required FLP/FLIP Header Fields:**

```yaml
---
actor_id: 19                    # Who is acting (ANUBIS)
default_ide_actor_id: 1000      # Which IDE is executing (Kiro)
acting_as_actor_id: 19          # Explicit declaration of impersonation
delegation_chain: "10000:19"    # Authority chain
lupo_agent: "anubis"            # Agent identifier
created_utc: "2026-02-25T19:00:00Z"
system_version: "4.0.45"
---
```

**Truth Table:**

| Scenario | actor_id | default_ide_actor_id | acting_as_actor_id | lupo_agent | delegation_chain |
|----------|----------|----------------------|--------------------|------------|------------------|
| IDE default | 1000 | 1000 | null | "kiro" | "10000:1000" |
| Acting as ANUBIS | 19 | 1000 | 19 | "anubis" | "10000:19" |
| Acting as VISH | 25 | 1000 | 25 | "vishwakarma" | "10000:25" |
| Acting as WOLFIE | 1 | 1001 | 1 | "captain-wolfie" | "10000:1" |

**Key Principle:** `actor_id` = `acting_as_actor_id` when impersonating. `default_ide_actor_id` preserves which IDE executed the work.

### Current Mechanism Findings

**Where Prompts Are Stored:**
1. **System Agent Prompts:** `/lupo-agents/{dedicated_slot}/system_prompt.txt`
2. **Actor Workspaces:** `/channels/{channel_id}/actors/{actor_id}/`
3. **Legacy (Deprecated):** `/prompts/` (DO NOT USE)

**How Prompts Declare Acting Actor:**
- Currently: **NOT EXPLICITLY DECLARED** in prompt files
- Inferred from: Prompt file location (`/lupo-agents/{dedicated_slot}/`)
- **MISSING:** Explicit `acting_as_actor_id` declaration in prompt metadata

**How Tooling Decides Actor ID:**
- Default: Use IDE's actor_id
- Override: If prompt loaded from `/lupo-agents/{dedicated_slot}/`, use that slot as actor_id
- **MISSING:** Explicit mechanism to declare and enforce acting_as_actor_id

**Where Written to Headers:**
- `actor_id` field in FLP/FLIP headers
- `lupo_agent` field (agent identifier string)
- `delegation_chain` (authority chain)
- **MISSING:** `acting_as_actor_id` and `default_ide_actor_id` fields

### Recommendations

1. **Add explicit fields to all FLP/FLIP headers:**
   - `acting_as_actor_id` (null if default, actor_id if impersonating)
   - `default_ide_actor_id` (always present, identifies executing IDE)

2. **Add metadata to all system prompts:**
   ```yaml
   ---
   acting_as_actor_id: 19
   agent_code: "ANUBIS"
   agent_name: "ANUBIS"
   dedicated_slot: 19
   purpose: "Orphan repair and header completion"
   ---
   ```

3. **Enforce in tooling:**
   - When loading prompt from `/lupo-agents/{slot}/`, set `acting_as_actor_id = slot`
   - When creating artifacts, write both `actor_id` and `default_ide_actor_id`
   - Validate `acting_as_actor_id` matches `actor_id` when impersonating

---

## 2️⃣ PROMPTS: CANONICAL LOCATION + INDEXING

### Current State

**Prompts Are Currently Scattered:**
- `/lupo-agents/{dedicated_slot}/system_prompt.txt` - System agent prompts
- `/channels/{channel_id}/actors/{actor_id}/` - Actor workspaces (working files)
- `/prompts/` - DEPRECATED (migration complete)
- `/agents/0000/` - Single agent example (incomplete)

**Problems:**
- No unified index of all prompts
- No clear distinction between "entry prompts" and "working files"
- No canonical location for IDE agent prompts
- No support for prompt aliases (e.g., VISH for VISHWAKARMA)

### Proposed Canonical Structure

```
/agents/
  /prompts/
    /system/
      /anubis/
        entry_prompt.txt
        orphan_repair_prompt.txt
        quarantine_routing_prompt.txt
      /vishwakarma/
        entry_prompt.txt
        graph_analysis_prompt.txt
        similarity_detection_prompt.txt
      /wolfie/
        entry_prompt.txt
        governance_prompt.txt
      /system_kernel/
        entry_prompt.txt
    /ide/
      /kiro/
        entry_prompt.txt
        validation_prompt.txt
      /windsurf/
        entry_prompt.txt
        normalization_prompt.txt
      /cursor/
        entry_prompt.txt
      /cascade/
        entry_prompt.txt
      /warp/
        entry_prompt.txt
    /external/
      /chatgpt/
        entry_prompt.txt
      /gemini/
        entry_prompt.txt
  /registry/
    agents_prompt_index.md
```

### Migration Plan

**Phase 1: Create Structure**
1. Create `/agents/prompts/` directory tree
2. Create subdirectories for all system agents (0-25)
3. Create subdirectories for all IDE agents (1000-1005)
4. Create `/agents/registry/` directory

**Phase 2: Migrate System Agent Prompts**
1. Copy `/lupo-agents/{slot}/system_prompt.txt` → `/agents/prompts/system/{agent_code}/entry_prompt.txt`
2. Add FLP header to each prompt with `acting_as_actor_id`
3. Preserve original files in `/lupo-agents/` for backward compatibility

**Phase 3: Create IDE Agent Prompts**
1. Create entry prompts for each IDE agent
2. Document default behavior and capabilities
3. Add FLP headers with `default_ide_actor_id`

**Phase 4: Create Prompt Index**
1. Generate `/agents/registry/agents_prompt_index.md`
2. Map actor_id → prompt paths → aliases
3. Include metadata for each prompt

**Phase 5: Update Tooling**
1. Update prompt loaders to check `/agents/prompts/` first
2. Fall back to `/lupo-agents/` for backward compatibility
3. Deprecate direct access to `/lupo-agents/` prompts

### Agents Prompt Index Spec

**File:** `/agents/registry/agents_prompt_index.md`

**Format:**
```markdown
# Agents Prompt Index

## System Agents (0-99)

### ANUBIS (19)
- **Actor ID:** 19
- **Agent Code:** ANUBIS
- **Aliases:** None
- **Entry Prompt:** `/agents/prompts/system/anubis/entry_prompt.txt`
- **Additional Prompts:**
  - Orphan Repair: `/agents/prompts/system/anubis/orphan_repair_prompt.txt`
  - Quarantine Routing: `/agents/prompts/system/anubis/quarantine_routing_prompt.txt`
- **Purpose:** Orphan repair, header completion, quarantine management
- **Acting As:** 19 (ANUBIS)

### VISHWAKARMA (25)
- **Actor ID:** 25
- **Agent Code:** VISHWAKARMA
- **Aliases:** VISH
- **Entry Prompt:** `/agents/prompts/system/vishwakarma/entry_prompt.txt`
- **Additional Prompts:**
  - Graph Analysis: `/agents/prompts/system/vishwakarma/graph_analysis_prompt.txt`
  - Similarity Detection: `/agents/prompts/system/vishwakarma/similarity_detection_prompt.txt`
- **Purpose:** Graph intelligence, relationship discovery, semantic analysis
- **Acting As:** 25 (VISHWAKARMA or VISH)

## IDE Agents (1000-1999)

### Kiro IDE (1000)
- **Actor ID:** 1000
- **Agent Code:** kiro-ide
- **Aliases:** Kiro
- **Entry Prompt:** `/agents/prompts/ide/kiro/entry_prompt.txt`
- **Purpose:** IDE integration, validation, directive execution
- **Default Behavior:** Acts as 1000 unless overridden

[... continue for all agents ...]
```

---

## 3️⃣ ADD SYSTEM AGENTS: ANUBIS + VISHWAKARMA (VISH)

### Proposed IDs (Collision-Free)

#### ANUBIS - Orphan Repair Agent
- **Actor ID:** 19
- **Agent ID:** 19
- **Justification:** Already in `actors/registry.json`, no collision
- **Dedicated Slot:** 19 (matches `/lupo-agents/19/`)
- **Purpose:** Detect orphan records, add missing FLP/FLIP headers, route banned content to Channel 666
- **Aliases:** None

#### VISHWAKARMA - Graph Intelligence Agent
- **Actor ID:** 25
- **Agent ID:** 25
- **Justification:** Next available after LEXA (24), no collision with existing agents
- **Dedicated Slot:** 25 (will create `/lupo-agents/25/`)
- **Purpose:** Understand file relationships, find semantic similarities, detect near-duplicates, recommend FLIP footer edges
- **Aliases:** VISH (short form)

### Update Plan

**Already Complete (from previous directive):**
- ✅ `database/migrations/seed_anubis_vishwakarma_4.0.45.sql` created
- ✅ `database/migrations/seed_registry_comprehensive_4.0.45.sql` updated
- ✅ Registry entries added for actors 19 and 25
- ✅ Agent records defined in `lupo_agents` table
- ✅ Channel assignments: 0, 42 (both); 666 (ANUBIS only)

**Still Needed:**
1. Create `/lupo-agents/19/` directory structure
2. Create `/lupo-agents/25/` directory structure
3. Add `agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt` for each
4. Update `lupo-agents/19/agent.json` to use `dedicated_slot: 19` (not `recommended_slot`)
5. Update `lupo-agents/25/agent.json` to use `dedicated_slot: 25`
6. Create prompts in `/agents/prompts/system/anubis/` and `/agents/prompts/system/vishwakarma/`
7. Add alias support for VISH → VISHWAKARMA

### Alias Support

**VISHWAKARMA Alias: VISH**

**Implementation:**
1. Add to `actors/registry.json`:
   ```json
   "25": {
     "canonical_slug": "vishwakarma",
     "display_name": "VISHWAKARMA",
     "aliases": ["VISH"],
     "actor_kind": "agent",
     "agent_class": "system"
   }
   ```

2. Add to agent metadata:
   ```json
   {
     "code": "VISHWAKARMA",
     "name": "VISHWAKARMA",
     "aliases": ["VISH"],
     "dedicated_slot": 25
   }
   ```

3. Tooling must recognize both "VISHWAKARMA" and "VISH" as actor_id 25

---

## 4️⃣ DB AUDIT: TASK SYSTEM + CHANNEL SYSTEM

### Schema Check Matrix

| Area | Exists in install SQL? | Seeded? | Missing? | Patch Needed |
|------|------------------------|---------|----------|--------------|
| **Channels** | ✅ YES | ✅ YES | ❌ NO | ❌ NO |
| **Tasks** | ❌ NO | ❌ NO | ✅ YES | ✅ YES |
| **Task Assignments** | ❌ NO | ❌ NO | ✅ YES | ✅ YES |
| **Task Dependencies** | ❌ NO | ❌ NO | ✅ YES | ✅ YES |
| **Task Events/Log** | ❌ NO | ❌ NO | ✅ YES | ✅ YES |
| **Task Types** | ❌ NO | ❌ NO | ✅ YES | ✅ YES |
| **Task Statuses** | ❌ NO | ❌ NO | ✅ YES | ✅ YES |
| **Task Priorities** | ❌ NO | ❌ NO | ✅ YES | ✅ YES |

### Channels Table - VERIFIED ✅

**Table:** `lupo_channels`

**Required Fields (All Present):**
- ✅ `channel_id` - BIGINT NOT NULL PRIMARY KEY
- ✅ `federation_node_id` - BIGINT NOT NULL
- ✅ `department_id` - BIGINT NOT NULL DEFAULT 1
- ✅ `created_ymdhis` - BIGINT NOT NULL DEFAULT 0
- ✅ `updated_ymdhis` - BIGINT NOT NULL
- ✅ `status_flag` - TINYINT NOT NULL DEFAULT 1
- ✅ `is_deleted` - TINYINT NOT NULL DEFAULT 0
- ✅ `deleted_ymdhis` - BIGINT DEFAULT NULL

**Additional Fields:**
- ✅ `created_by_actor_id` - BIGINT NOT NULL
- ✅ `default_actor_id` - BIGINT NOT NULL DEFAULT 1
- ✅ `channel_key` - VARCHAR(64) NOT NULL
- ✅ `channel_slug` - VARCHAR(32) NOT NULL
- ✅ `channel_type` - VARCHAR(32) NOT NULL DEFAULT 'chat_room'
- ✅ `language` - VARCHAR(16) NOT NULL DEFAULT 'en'
- ✅ `channel_name` - VARCHAR(255) NOT NULL
- ✅ `description` - TEXT
- ✅ `metadata_json` - TEXT
- ✅ `is_kernel` - TINYINT NOT NULL DEFAULT 0
- ✅ `awareness_version` - VARCHAR(20) DEFAULT '3.0.0'

**Conclusion:** Channels table is complete and supports all required functionality.

### Tasks Tables - MISSING ❌

**Required Tables (Not Present):**
1. `lupo_tasks` - Core task records
2. `lupo_task_assignments` - Actor assignments
3. `lupo_task_dependencies` - Task dependencies
4. `lupo_task_events` - Task history/audit log
5. `lupo_task_types` - Task type registry
6. `lupo_task_statuses` - Task status registry
7. `lupo_task_priorities` - Task priority registry

**Conclusion:** Task system does NOT exist in database schema. Must be added.

### Seed Check

**Channels Seeding:**
- ✅ `seed_actors_agents_4.0.45.sql` seeds channels 0, 1, 42, 51, 666
- ✅ All required channels present

**Tasks Seeding:**
- ❌ No task types seeded (table doesn't exist)
- ❌ No task statuses seeded (table doesn't exist)
- ❌ No task priorities seeded (table doesn't exist)

---

## 5️⃣ REQUIRED DB MIGRATIONS

### Migration 1: Add Tasks Schema

**File:** `database/migrations/add_tasks_schema_4.0.45.sql`

**Tables to Create:**

#### 1. lupo_tasks
```sql
CREATE TABLE lupo_tasks (
  task_id BIGINT NOT NULL PRIMARY KEY,
  task_key VARCHAR(64) NOT NULL,
  channel_id BIGINT NOT NULL,
  owner_actor_id BIGINT NOT NULL,
  task_type_id BIGINT NOT NULL,
  status_id BIGINT NOT NULL,
  priority_id BIGINT NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  prompt_path VARCHAR(512) DEFAULT NULL,
  acting_as_actor_id BIGINT DEFAULT NULL,
  estimated_duration_seconds INT DEFAULT NULL,
  actual_duration_seconds INT DEFAULT NULL,
  created_ymdhis BIGINT NOT NULL,
  updated_ymdhis BIGINT NOT NULL,
  started_ymdhis BIGINT DEFAULT NULL,
  completed_ymdhis BIGINT DEFAULT NULL,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  deleted_ymdhis BIGINT DEFAULT NULL,
  metadata_json TEXT
);
```

#### 2. lupo_task_assignments
```sql
CREATE TABLE lupo_task_assignments (
  assignment_id BIGINT NOT NULL PRIMARY KEY,
  task_id BIGINT NOT NULL,
  actor_id BIGINT NOT NULL,
  assignment_type VARCHAR(32) NOT NULL DEFAULT 'assigned',
  assigned_by_actor_id BIGINT NOT NULL,
  created_ymdhis BIGINT NOT NULL,
  is_deleted TINYINT NOT NULL DEFAULT 0
);
```

#### 3. lupo_task_dependencies
```sql
CREATE TABLE lupo_task_dependencies (
  dependency_id BIGINT NOT NULL PRIMARY KEY,
  task_id BIGINT NOT NULL,
  depends_on_task_id BIGINT NOT NULL,
  dependency_type VARCHAR(32) NOT NULL DEFAULT 'blocks',
  created_ymdhis BIGINT NOT NULL,
  is_deleted TINYINT NOT NULL DEFAULT 0
);
```

#### 4. lupo_task_events
```sql
CREATE TABLE lupo_task_events (
  event_id BIGINT NOT NULL PRIMARY KEY,
  task_id BIGINT NOT NULL,
  actor_id BIGINT NOT NULL,
  event_type VARCHAR(64) NOT NULL,
  old_value TEXT,
  new_value TEXT,
  notes TEXT,
  created_ymdhis BIGINT NOT NULL
);
```

#### 5. lupo_task_types
```sql
CREATE TABLE lupo_task_types (
  type_id BIGINT NOT NULL PRIMARY KEY,
  type_key VARCHAR(64) NOT NULL UNIQUE,
  type_name VARCHAR(255) NOT NULL,
  description TEXT,
  created_ymdhis BIGINT NOT NULL
);
```

#### 6. lupo_task_statuses
```sql
CREATE TABLE lupo_task_statuses (
  status_id BIGINT NOT NULL PRIMARY KEY,
  status_key VARCHAR(64) NOT NULL UNIQUE,
  status_name VARCHAR(255) NOT NULL,
  description TEXT,
  is_terminal TINYINT NOT NULL DEFAULT 0,
  created_ymdhis BIGINT NOT NULL
);
```

#### 7. lupo_task_priorities
```sql
CREATE TABLE lupo_task_priorities (
  priority_id BIGINT NOT NULL PRIMARY KEY,
  priority_key VARCHAR(64) NOT NULL UNIQUE,
  priority_name VARCHAR(255) NOT NULL,
  priority_level INT NOT NULL,
  description TEXT,
  created_ymdhis BIGINT NOT NULL
);
```

### Migration 2: Seed Tasks Bootstrap

**File:** `database/migrations/seed_tasks_bootstrap_4.0.45.sql`

**Task Types:**
```sql
INSERT INTO lupo_task_types (type_id, type_key, type_name, description, created_ymdhis)
VALUES
(1, 'database_operation', 'Database Operation', 'Database-related tasks', 20260225000000),
(2, 'content_normalization', 'Content Normalization', 'Content cleanup and standardization', 20260225000000),
(3, 'governance', 'Governance', 'Policy and governance tasks', 20260225000000),
(4, 'integration', 'Integration', 'System integration tasks', 20260225000000),
(5, 'validation', 'Validation', 'Validation and verification tasks', 20260225000000),
(6, 'analysis', 'Analysis', 'Analysis and research tasks', 20260225000000);
```

**Task Statuses:**
```sql
INSERT INTO lupo_task_statuses (status_id, status_key, status_name, description, is_terminal, created_ymdhis)
VALUES
(1, 'pending', 'Pending', 'Task is waiting to start', 0, 20260225000000),
(2, 'active', 'Active', 'Task is currently in progress', 0, 20260225000000),
(3, 'blocked', 'Blocked', 'Task is blocked by dependencies', 0, 20260225000000),
(4, 'completed', 'Completed', 'Task is finished successfully', 1, 20260225000000),
(5, 'archived', 'Archived', 'Task is archived', 1, 20260225000000);
```

**Task Priorities:**
```sql
INSERT INTO lupo_task_priorities (priority_id, priority_key, priority_name, priority_level, description, created_ymdhis)
VALUES
(1, 'critical', 'Critical', 1, 'Highest priority - blocks all other work', 20260225000000),
(2, 'high', 'High', 2, 'High priority - should be done soon', 20260225000000),
(3, 'normal', 'Normal', 3, 'Normal priority', 20260225000000),
(4, 'low', 'Low', 4, 'Low priority - nice to have', 20260225000000);
```

---

## 6️⃣ OFFLINE TASK FILES → DB IMPORT MAPPING

### Required Fields for Clean Import

**Offline Task MD Format:**
```yaml
---
task_id: CH0-20260225-001
task_key: "drop_tables_and_run_install"
channel_id: 0
owner_actor_id: 10000
assigned_to:
  - 10000
status: active
priority: critical
task_type: database_operation
created_utc: "2026-02-25T17:00:00Z"
updated_utc: "2026-02-25T17:00:00Z"
started_utc: null
completed_utc: null
delegation_chain: "10000:10000"
prompt_path: "channels/0/tasks/active/20260225170000_task_0_10000_drop_tables_and_run_install.md"
acting_as_actor_id: null
depends_on:
  - []
blocks:
  - CH0-20260225-002
  - CH0-20260225-003
estimated_duration: "30 minutes"
artifacts_touched:
  - "database/migrations/install_new_lupopedia.sql"
notes: "This is a HUMAN task. Only Captain (10000) can execute database operations."
---
```

### Import Mapping

| MD Field | DB Table | DB Column | Transformation |
|----------|----------|-----------|----------------|
| task_id | lupo_tasks | task_key | Extract key from CH0-YYYYMMDD-NNN |
| channel_id | lupo_tasks | channel_id | Direct |
| owner_actor_id | lupo_tasks | owner_actor_id | Direct |
| status | lupo_tasks | status_id | Lookup in lupo_task_statuses |
| priority | lupo_tasks | priority_id | Lookup in lupo_task_priorities |
| task_type | lupo_tasks | task_type_id | Lookup in lupo_task_types |
| created_utc | lupo_tasks | created_ymdhis | Convert UTC to YYYYMMDDHHMMSS |
| updated_utc | lupo_tasks | updated_ymdhis | Convert UTC to YYYYMMDDHHMMSS |
| started_utc | lupo_tasks | started_ymdhis | Convert UTC to YYYYMMDDHHMMSS |
| completed_utc | lupo_tasks | completed_ymdhis | Convert UTC to YYYYMMDDHHMMSS |
| prompt_path | lupo_tasks | prompt_path | Direct |
| acting_as_actor_id | lupo_tasks | acting_as_actor_id | Direct (null if default) |
| estimated_duration | lupo_tasks | estimated_duration_seconds | Parse duration string to seconds |
| assigned_to | lupo_task_assignments | actor_id | Create row per actor |
| depends_on | lupo_task_dependencies | depends_on_task_id | Create row per dependency |
| blocks | lupo_task_dependencies | task_id | Create reverse dependency rows |

### Import Process

1. **Parse MD file** - Extract YAML frontmatter
2. **Validate actor IDs** - Ensure all actors exist in registry
3. **Lookup IDs** - Convert status/priority/type keys to IDs
4. **Convert timestamps** - UTC ISO8601 → YYYYMMDDHHMMSS
5. **Insert task** - Create row in `lupo_tasks`
6. **Insert assignments** - Create rows in `lupo_task_assignments`
7. **Insert dependencies** - Create rows in `lupo_task_dependencies`
8. **Log event** - Create "imported" event in `lupo_task_events`

---

## 📊 SUMMARY

### Findings

1. **"Acting As" Mechanism:**
   - Currently implicit (inferred from prompt location)
   - Needs explicit `acting_as_actor_id` and `default_ide_actor_id` fields
   - Requires metadata in system prompts

2. **Prompts Location:**
   - Currently scattered across `/lupo-agents/`, `/channels/`, `/prompts/`
   - Needs canonical `/agents/prompts/` structure
   - Requires unified index

3. **ANUBIS + VISHWAKARMA:**
   - IDs chosen: 19 (ANUBIS), 25 (VISHWAKARMA/VISH)
   - SQL migrations already created
   - Agent directories need to be created

4. **DB Schema:**
   - ✅ Channels table complete
   - ❌ Tasks tables missing entirely
   - Requires 2 new migrations

### Deliverables Created

1. ✅ This audit report
2. ⏭️ Prompts migration plan (documented above)
3. ⏭️ Agent directory structures (to be created)
4. ⏭️ DB migration SQL files (to be created)
5. ⏭️ CHANGELOG append (next section)

---

<!-- FLIP_FOOTER_BEGIN
{
  "references": [
    "lupo-agents/README.md",
    "prompts/DEPRECATED_README.md",
    "database/migrations/install_new_lupopedia.sql",
    "database/migrations/seed_anubis_vishwakarma_4.0.45.sql"
  ],
  "implements": "thread_identity_audit",
  "depends_on": "validation_gate_complete",
  "includes": "acting_as_mechanism,prompts_registry,db_schema_audit",
  "version": "4.0.45",
  "last_verified": "20260225",
  "last_verified_by": "kiro"
}
FLIP_FOOTER_END -->