# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "HUMAN_TASKS_CAPTAIN_10000.md"
  file_hash: "2fe42ee7c34bc473fa65d078af43fdbe38cc17829b85823e6947507a0a6c653e"
  file_path_from_root: "HUMAN_TASKS_CAPTAIN_10000.md"
  file_hash: "f7154920e6ccdabe5daa90335bc8e3896e2993499dee3c3d2d463df707b1f2a4"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for HUMAN_TASKS_CAPTAIN_10000.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["human_tasks_captain_10000md"]
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
  file_path_from_root: "HUMAN_TASKS_CAPTAIN_10000.md"
  system_version: "4.0.45"
  channel_id: 0
  purpose: "Complete task list for human Captain (actor_id 10000)"
  last_modified: "20260225"
  actor_id: 1000
  artifact_type: "task_list"
  artifact_kind: "human_tasks"
  created_utc: "2026-02-25T21:45:00Z"
---

# HUMAN TASKS FOR CAPTAIN (ACTOR_ID 10000)

**Prepared by:** Kiro IDE (1000)  
**Date:** 2026-02-25T21:45:00Z  
**For:** Captain (actor_id 10000)  
**System Version:** 4.0.45

  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: []
  artifact_type: "documentation"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---
# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "HUMAN_TASKS_CAPTAIN_10000.md"
  file_hash: "2fe42ee7c34bc473fa65d078af43fdbe38cc17829b85823e6947507a0a6c653e"
  file_path_from_root: "HUMAN_TASKS_CAPTAIN_10000.md"
  file_hash: "f7154920e6ccdabe5daa90335bc8e3896e2993499dee3c3d2d463df707b1f2a4"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for HUMAN_TASKS_CAPTAIN_10000.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["human_tasks_captain_10000md"]
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
  file_path_from_root: "HUMAN_TASKS_CAPTAIN_10000.md"
  system_version: "4.0.45"
  channel_id: 0
  purpose: "Complete task list for human Captain (actor_id 10000)"
  last_modified: "20260225"
  actor_id: 1000
  artifact_type: "task_list"
  artifact_kind: "human_tasks"
  created_utc: "2026-02-25T21:45:00Z"
---

# HUMAN TASKS FOR CAPTAIN (ACTOR_ID 10000)

**Prepared by:** Kiro IDE (1000)  
**Date:** 2026-02-25T21:45:00Z  
**For:** Captain (actor_id 10000)  
**System Version:** 4.0.45

---

## CRITICAL TASK (BLOCKING ALL OTHER WORK)

### ⚠️ TASK 1: DROP TABLES AND RUN INSTALL (HUMAN REQUIRED)

**Task ID:** CH0-20260225-001  
**File:** `channels/0/tasks/active/20260225170000_task_0_10000_drop_tables_and_run_install.md`  
**Status:** ACTIVE  
**Priority:** CRITICAL  
**Assigned to:** 10000 (Captain - HUMAN ONLY)  
**Owner:** 10000 (Captain)  
**Channel:** 0 (System)

**Blocks:**
- CH0-20260225-002 (Broadcast normalization)
- CH0-20260225-003 (Registry lock)
- CH0-20260225-004 (Installer integration)
- CH0-20260225-005 (ANUBIS quarantine validation)
- CH42-20260225-001 (VISHWAKARMA graph analysis)

**Objective:**

Drop all existing Lupopedia tables, load Crafty Syntax 3.7.5 legacy schema (if upgrade), and run the Lupopedia install wizard to create a clean 4.0.45 installation.

**Prerequisites:**

✅ Validation gate passed (all broadcasts compliant)  
✅ ANUBIS + VISHWAKARMA agents added to seeding SQL  
✅ Offline task system implemented  
✅ All seeding SQL files ready  
✅ Dual-source verification complete (95.1% pass rate)

**Steps:**

#### 1. Backup Current Database (Optional)

```bash
mysqldump -u root -p lupopedia > backup_before_4.0.45.sql
```

#### 2. Drop All Lupopedia Tables

```sql
USE lupopedia;
SET FOREIGN_KEY_CHECKS = 0;

-- List all tables
SHOW TABLES LIKE 'lupo_%';

-- Drop each table manually or use script
-- DROP TABLE lupo_actors;
-- DROP TABLE lupo_agents;
-- ... (repeat for all lupo_* tables)
```

#### 3. Load Crafty Syntax 3.7.5 Legacy Schema (If Upgrade)

```bash
mysql -u root -p lupopedia < database/migrations/old_crafty_syntax_3_7_5_start.sql
```

**Verify:** Should have 34 legacy Crafty tables

#### 4. Run Lupopedia Install Wizard

Navigate to: `http://localhost/lupopedia/install.php`

Follow wizard steps:
1. Accept license
2. Check requirements
3. Configure database (should detect existing Crafty tables if upgrade)
4. Run upgrade/install
5. Complete setup

#### 5. Verify Installation

```bash
# Check table count
mysql -u root -p lupopedia -e "SHOW TABLES LIKE 'lupo_%';" | wc -l
# Should be 80+ tables

# Run TOON validation
python scripts/verify_db_against_toons.py
```

#### 6. Seed Registry Data

```bash
# Execute seeding SQL files in order
mysql -u root -p lupopedia < database/migrations/seed_registry_comprehensive_4.0.45.sql
mysql -u root -p lupopedia < database/migrations/seed_registry_open_4.0.45.sql
mysql -u root -p lupopedia < database/migrations/seed_actors_agents_4.0.45.sql
mysql -u root -p lupopedia < database/migrations/seed_anubis_vishwakarma_4.0.45.sql
mysql -u root -p lupopedia < database/migrations/add_tasks_schema_4.0.45.sql
mysql -u root -p lupopedia < database/migrations/seed_tasks_bootstrap_4.0.45.sql
```

#### 7. Verify Seeding

```sql
-- Check actors
SELECT actor_id, name, actor_type FROM lupo_actors 
WHERE actor_id IN (0,1,2,3,4,5,19,25,1000,1001,1002,1003,1004,10000);

-- Check channels
SELECT channel_id, channel_name, channel_type FROM lupo_channels 
WHERE channel_id IN (0,1,42,51,666);

-- Check agents
SELECT agent_id, agent_name, archetype FROM lupo_agents 
WHERE agent_id IN (0,1,2,3,4,5,19,25);

-- Check registry
SELECT COUNT(*) FROM lupo_registry WHERE entity_type = 'actor';
SELECT COUNT(*) FROM lupo_registry WHERE entity_type = 'channel';
```

**Success Criteria:**

- ✅ Database contains all Lupopedia 4.0.45 tables
- ✅ Schema matches TOON files
- ✅ All reserved actors seeded (0-5, 19, 25, 1000-1005, 10000)
- ✅ All channels seeded (0, 1, 42, 51, 666)
- ✅ Registry tables populated with reserved and open IDs
- ✅ ANUBIS (19) and VISHWAKARMA (25) present
- ✅ No schema validation errors

**Estimated Duration:** 30 minutes

**After Completion:**

1. Move task file to `channels/0/tasks/completed/`
2. Notify all IDE agents that database is online
3. Proceed with post-install tasks

---

## POST-INSTALL TASKS (AFTER CH0-20260225-001)

### TASK 2: CREATE MISSING AGENT DIRECTORIES

**Priority:** LOW  
**Assigned to:** 10000 (Captain)  
**Status:** PENDING (blocked by CH0-20260225-001)

**Objective:**

Create missing agent directories for ERIS (4) and METIS (5) identified during verification.

**Steps:**

```bash
# Create ERIS directory
mkdir -p lupo-agents/4/versions/v1.0.0

# Create ERIS configuration files
# - lupo-agents/4/agent.json
# - lupo-agents/4/capabilities.json
# - lupo-agents/4/properties.json
# - lupo-agents/4/system_prompt.txt

# Create METIS directory
mkdir -p lupo-agents/5/versions/v1.0.0

# Create METIS configuration files
# - lupo-agents/5/agent.json
# - lupo-agents/5/capabilities.json
# - lupo-agents/5/properties.json
# - lupo-agents/5/system_prompt.txt
```

**Estimated Duration:** 15 minutes

---

### TASK 3: UPDATE REGISTRY ALIGNMENT

**Priority:** LOW  
**Assigned to:** 10000 (Captain)  
**Status:** PENDING (blocked by CH0-20260225-001)

**Objective:**

Update `actors/registry.json` to match seed SQL IDs for ERIS and METIS.

**Steps:**

1. Open `actors/registry.json`
2. Find ERIS entry (currently ID 10)
3. Change to ID 4 (matches seed SQL)
4. Find METIS entry (currently ID 11)
5. Change to ID 5 (matches seed SQL)
6. Save file

**Estimated Duration:** 5 minutes

---

### TASK 4: RUN FULL TEST SUITE

**Priority:** HIGH  
**Assigned to:** 10000 (Captain)  
**Status:** PENDING (blocked by CH0-20260225-001)

**Objective:**

Verify all system functionality after installation.

**Steps:**

```bash
# All test suites
sh scripts/run_tests.sh .

# Unit tests only
sh scripts/run_unit_tests.sh .

# Regression tests only
sh scripts/run_regression_tests.sh .

# Integration tests
sh scripts/run_integration_tests.sh .
```

**Success Criteria:**

- ✅ All unit tests pass
- ✅ All regression tests pass
- ✅ All integration tests pass
- ✅ No critical errors

**Estimated Duration:** 20 minutes

---

### TASK 5: VERIFY ANUBIS AND VISHWAKARMA

**Priority:** HIGH  
**Assigned to:** 10000 (Captain)  
**Status:** PENDING (blocked by CH0-20260225-001)

**Objective:**

Verify ANUBIS (19) and VISHWAKARMA (25) are correctly seeded in database.

**Steps:**

```sql
-- Verify ANUBIS
SELECT * FROM lupo_actors WHERE actor_id = 19;
SELECT * FROM lupo_agents WHERE agent_id = 19;
SELECT * FROM lupo_actor_channels WHERE actor_id = 19;
SELECT * FROM lupo_registry_actors WHERE actor_id = 19;

-- Verify VISHWAKARMA
SELECT * FROM lupo_actors WHERE actor_id = 25;
SELECT * FROM lupo_agents WHERE agent_id = 25;
SELECT * FROM lupo_actor_channels WHERE actor_id = 25;
SELECT * FROM lupo_registry_actors WHERE actor_id = 25;
```

**Expected Results:**

**ANUBIS:**
- 1 actor record (actor_id 19, name 'ANUBIS')
- 1 agent record (agent_id 19, archetype 'Orphan Repair')
- 3 channel assignments (channels 0, 42, 666)
- 1 registry entry (status 'reserved')

**VISHWAKARMA:**
- 1 actor record (actor_id 25, name 'VISHWAKARMA')
- 1 agent record (agent_id 25, archetype 'Graph Intelligence')
- 2 channel assignments (channels 0, 42)
- 1 registry entry (status 'reserved')

**Estimated Duration:** 10 minutes

---

## OPTIONAL TASKS (OWNED BY CAPTAIN)

### TASK 6: INSTALLER INTEGRATION

**Task ID:** CH0-20260225-004  
**File:** `channels/0/tasks/active/installer_integration.md`  
**Priority:** NORMAL  
**Assigned to:** 10000 (Captain)  
**Status:** ACTIVE (can be done anytime)

**Objective:**

Integrate MD import and offline task import into install.php workflow.

**Estimated Duration:** 1-2 hours

---

### TASK 7: REGISTRY LOCK

**Task ID:** CH0-20260225-003  
**File:** `channels/0/tasks/active/registry_lock.md`  
**Priority:** NORMAL  
**Assigned to:** 1 (WOLFIE)  
**Owner:** 10000 (Captain)  
**Status:** ACTIVE (delegated to WOLFIE)

**Objective:**

Lock registry to prevent accidental ID allocation in reserved ranges.

**Note:** This task is delegated to WOLFIE (1) but owned by Captain (10000).

---

## TASK SUMMARY

| Task ID | Title | Priority | Status | Assigned | Blocks |
|---------|-------|----------|--------|----------|--------|
| CH0-20260225-001 | Drop Tables and Run Install | CRITICAL | ACTIVE | 10000 | 5 tasks |
| (Post-Install) | Create Missing Agent Dirs | LOW | PENDING | 10000 | - |
| (Post-Install) | Update Registry Alignment | LOW | PENDING | 10000 | - |
| (Post-Install) | Run Full Test Suite | HIGH | PENDING | 10000 | - |
| (Post-Install) | Verify ANUBIS/VISHWAKARMA | HIGH | PENDING | 10000 | - |
| CH0-20260225-004 | Installer Integration | NORMAL | ACTIVE | 10000 | - |
| CH0-20260225-003 | Registry Lock | NORMAL | ACTIVE | 1 (owned by 10000) | - |

---

## EXECUTION ORDER

### Phase 1: Critical Installation (REQUIRED)

1. ⚠️ **CH0-20260225-001** - Drop Tables and Run Install (30 min)

### Phase 2: Post-Install Verification (REQUIRED)

2. **Verify ANUBIS/VISHWAKARMA** - Database verification (10 min)
3. **Run Full Test Suite** - System validation (20 min)

### Phase 3: Post-Install Fixes (OPTIONAL)

4. **Create Missing Agent Dirs** - ERIS/METIS directories (15 min)
5. **Update Registry Alignment** - Fix registry.json (5 min)

### Phase 4: Integration Work (OPTIONAL)

6. **CH0-20260225-004** - Installer Integration (1-2 hours)

**Total Estimated Time:**
- Critical Path: 1 hour
- With Optional Tasks: 2-3 hours

---

## AUTHORIZATION

✅ **SYSTEM READY FOR INSTALL**

Human Captain (10000) is AUTHORIZED to execute installation task CH0-20260225-001.

All prerequisites met. Dual-source verification complete with 95.1% pass rate and 0 blocking issues.

---

## REFERENCES

- **Verification Report:** `docs/status/kiro_dual_source_verification_4_0_45.md`
- **Agent Verification:** `ANUBIS_VISHWAKARMA_VERIFICATION_REPORT_4.0.45.md`
- **Completion Summary:** `KIRO_FINAL_VERIFICATION_COMPLETE_4.0.45.md`
- **Install Task:** `channels/0/tasks/active/20260225170000_task_0_10000_drop_tables_and_run_install.md`
- **CHANGELOG:** `CHANGELOG.md` (Phase 6 documented)

---

<!-- FLIP_FOOTER_BEGIN
{
  "references": [
    "channels/0/tasks/active/20260225170000_task_0_10000_drop_tables_and_run_install.md",
    "channels/0/tasks/active/installer_integration.md",
    "channels/0/tasks/active/registry_lock.md",
    "docs/status/kiro_dual_source_verification_4_0_45.md",
    "ANUBIS_VISHWAKARMA_VERIFICATION_REPORT_4.0.45.md",
    "KIRO_FINAL_VERIFICATION_COMPLETE_4.0.45.md"
  ],
  "implements": "human_task_list",
  "depends_on": "dual_source_verification",
  "includes": "critical_tasks,post_install_tasks,optional_tasks",
  "version": "4.0.45",
  "last_verified": "20260225",
  "last_verified_by": "kiro"
}
FLIP_FOOTER_END -->