# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "INSTALL_SQL_AUDIT_TASKS_THREADS_4.0.45.md"
  file_hash: "7d7522248575b619663274871d8765bd07f02f284b0fe997755b67f21662db05"
  file_path_from_root: "INSTALL_SQL_AUDIT_TASKS_THREADS_4.0.45.md"
  file_hash: "1945aaf3aafab51de6ece43166e463155f73b256795fc0cbc70ac67d985c6ea1"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for INSTALL_SQL_AUDIT_TASKS_THREADS_4.0.45.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["install_sql_audit_tasks_threads_4045md"]
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
  file_path_from_root: "INSTALL_SQL_AUDIT_TASKS_THREADS_4.0.45.md"
  system_version: "4.0.45"
  channel_id: 42
  purpose: "Audit of install_new_lupopedia.sql for tasks, threads, actors, and MD import support"
  last_modified: "20260225"
  actor_id: 1000
  artifact_type: "audit_report"
  artifact_kind: "schema_audit"
  created_utc: "2026-02-25T23:00:00Z"
---

# INSTALL SQL AUDIT - TASKS, THREADS, ACTORS, MD IMPORT

**Auditor:** Kiro IDE (1000)  
**Date:** 2026-02-25T23:00:00Z  
**File:** `database/migrations/install_new_lupopedia.sql`  
**Total Tables:** 166  
**Total Lines:** 3,812

---

## Executive Summary

**Audit Result:** ❌ TASKS TABLES MISSING (as reported in CHANGELOG)

**Required Actions:**
1. ✅ Threads: EXIST (lupo_dialog_threads)
2. ✅ Actors: EXIST (lupo_actors, lupo_agents)
3. ✅ Messages: EXIST (lupo_dialog_doctrine)
4. ✅ Channels: EXIST (lupo_channels)
5. ✅ Registry: EXIST (lupo_registry, lupo_registry_open)
6. ❌ Tasks: MISSING (must be added)

**Recommendation:** Integrate task tables from `add_tasks_schema_4.0.45.sql` directly into `install_new_lupopedia.sql` as source of truth.

---

## 1. THREADS SYSTEM AUDIT

### ✅ THREADS TABLE EXISTS

**Table:** `lupo_dialog_threads`  
**Location:** Line ~1906  
**Status:** COMPLETE

**Columns:**
- `dialog_thread_id` BIGINT NOT NULL PRIMARY KEY
- `thread_id` BIGINT NOT NULL DEFAULT 0
- `federation_node_id` BIGINT NOT NULL DEFAULT 1
- `channel_id` BIGINT (nullable)
- `project_slug` VARCHAR(100)
- `task_name` VARCHAR(255)
- `created_by_actor_id` BIGINT NOT NULL
- `summary_text` TEXT
- `bg_color` CHAR(6) DEFAULT 'FFFFFF'
- `text_color` CHAR(6) DEFAULT '000000'
- `alt_text_color` CHAR(6) DEFAULT '666666'
- `status` VARCHAR(64) DEFAULT 'Open'
- `artifacts` JSON
- `metadata_json` JSON
- `created_ymdhis` BIGINT NOT NULL DEFAULT 0
- `updated_ymdhis` BIGINT NOT NULL
- `is_deleted` TINYINT NOT NULL DEFAULT 0
- `deleted_ymdhis` BIGINT
- `escalated_to_operator_id` BIGINT
- `escalation_reason` VARCHAR(255)
- `escalation_timestamp` BIGINT

**Indexes:**
- federation_node_id
- channel_id
- project_slug
- task_name
- status
- created_ymdhis
- updated_ymdhis
- is_deleted
- created_by_actor_id

**MD Import Support:** ✅ COMPLETE
- Can create threads with channel_id
- Can track created_by_actor_id
- Supports metadata_json for FLP/FLIP
- Has status tracking
- Has timestamps in ymdhis format

---

## 2. ACTORS SYSTEM AUDIT

### ✅ ACTORS TABLE EXISTS

**Table:** `lupo_actors`  
**Location:** Line ~4  
**Status:** COMPLETE

**Columns:**
- `actor_id` BIGINT NOT NULL PRIMARY KEY
- `actor_type` VARCHAR(64) NOT NULL
- `slug` VARCHAR(255) NOT NULL UNIQUE
- `name` VARCHAR(255) NOT NULL
- `created_ymdhis` BIGINT NOT NULL DEFAULT 0
- `updated_ymdhis` BIGINT NOT NULL
- `is_active` TINYINT NOT NULL DEFAULT 1
- `is_deleted` TINYINT NOT NULL DEFAULT 0
- `deleted_ymdhis` BIGINT
- `actor_source_id` BIGINT
- `actor_source_type` VARCHAR(64)
- `metadata` TEXT
- `adversarial_role` VARCHAR(64) DEFAULT 'none'
- `adversarial_oversight_actor_id` BIGINT
- `avatar_hash` VARCHAR(64)
- `primary_federation_node_id` BIGINT NOT NULL DEFAULT 1
- `department_id` BIGINT
- `is_kernel` TINYINT NOT NULL DEFAULT 0
- `can_login` TINYINT NOT NULL DEFAULT 0
- `metadata_json` JSON
- `identity_provider_config` JSON
- `paired_actor_id` BIGINT NOT NULL DEFAULT 0
- `is_agent` TINYINT NOT NULL DEFAULT 0

**Indexes:**
- slug (UNIQUE)
- actor_type
- is_active
- created_ymdhis

**MD Import Support:** ✅ COMPLETE
- Can create actors with explicit actor_id (NOT AUTO_INCREMENT)
- Supports all required fields for importer
- Has metadata_json for FLP/FLIP
- Has is_agent flag for AI agents
- Has timestamps in ymdhis format

### ✅ AGENTS TABLE EXISTS

**Table:** `lupo_agents`  
**Location:** Line ~413  
**Status:** COMPLETE

**Columns:**
- `agent_id` BIGINT NOT NULL PRIMARY KEY
- `agent_key` VARCHAR(100) NOT NULL UNIQUE
- `agent_name` VARCHAR(255) NOT NULL
- `archetype` VARCHAR(100)
- `description` TEXT
- `version` VARCHAR(20)
- `model_name` VARCHAR(100)
- `is_global_authority` TINYINT DEFAULT 0
- `is_internal_only` TINYINT DEFAULT 0
- `created_ymdhis` BIGINT NOT NULL DEFAULT 0
- `updated_ymdhis` BIGINT NOT NULL
- `is_deleted` TINYINT NOT NULL DEFAULT 0
- `deleted_ymdhis` BIGINT
- `system_prompt` TEXT
- `provider` VARCHAR(64)
- `temperature` DECIMAL(3,2)
- `top_p` DECIMAL(3,2)
- `max_tokens` INT
- (plus many more AI-specific fields)

**MD Import Support:** ✅ COMPLETE
- Can link actors to agents via agent_id
- Supports system prompts
- Has timestamps in ymdhis format

---

## 3. MESSAGES SYSTEM AUDIT

### ✅ MESSAGES TABLE EXISTS

**Table:** `lupo_dialog_doctrine`  
**Location:** Line ~1874  
**Status:** COMPLETE

**Columns:**
- `dialog_message_id` BIGINT NOT NULL PRIMARY KEY
- `message_id` BIGINT NOT NULL DEFAULT 0
- `dialog_thread_id` BIGINT (nullable)
- `channel_id` BIGINT (nullable)
- `from_actor_id` BIGINT (nullable)
- `to_actor_id` BIGINT (nullable)
- `read_by_actor_id` BIGINT NOT NULL DEFAULT 0
- `read_by_actor_utc` BIGINT NOT NULL DEFAULT 0
- `message_text` VARCHAR(1000) NOT NULL
- `message_type` VARCHAR(64) NOT NULL DEFAULT 'text'
- `metadata_json` JSON
- `mood_rgb` CHAR(6)
- `mood_framework` VARCHAR(32) DEFAULT 'western_analytical'
- `created_ymdhis` BIGINT NOT NULL DEFAULT 0
- `updated_ymdhis` BIGINT NOT NULL
- `is_deleted` TINYINT NOT NULL DEFAULT 0
- `deleted_ymdhis` BIGINT
- `message_body` MEDIUMTEXT

**Indexes:**
- channel_id
- created_ymdhis
- updated_ymdhis
- is_deleted
- message_type
- dialog_thread_id
- to_actor_id
- read_by_actor_id
- read_by_actor_utc

**MD Import Support:** ✅ COMPLETE
- Can import messages with from_actor_id/to_actor_id
- Can link to threads via dialog_thread_id
- Can link to channels via channel_id
- Supports metadata_json for FLP/FLIP
- Has message_text (short) and message_body (long)
- Has timestamps in ymdhis format
- Supports read tracking

---

## 4. CHANNELS SYSTEM AUDIT

### ✅ CHANNELS TABLE EXISTS

**Table:** `lupo_channels`  
**Location:** Line ~1145  
**Status:** COMPLETE

**Columns:**
- `channel_id` BIGINT NOT NULL PRIMARY KEY
- `federation_node_id` BIGINT NOT NULL
- `department_id` BIGINT
- `channel_name` VARCHAR(255) NOT NULL
- `channel_type` VARCHAR(64) DEFAULT 'chat_room'
- `description` TEXT
- `status` VARCHAR(64) DEFAULT 'active'
- `created_ymdhis` BIGINT NOT NULL DEFAULT 0
- `updated_ymdhis` BIGINT NOT NULL
- `is_deleted` TINYINT NOT NULL DEFAULT 0
- `deleted_ymdhis` BIGINT
- (plus many more channel-specific fields)

**MD Import Support:** ✅ COMPLETE
- Can reference channels by channel_id
- Has all required fields
- Has timestamps in ymdhis format

---

## 5. REGISTRY SYSTEM AUDIT

### ✅ REGISTRY TABLES EXIST

**Table 1:** `lupo_registry`  
**Location:** Line ~3408  
**Status:** COMPLETE

**Columns:**
- `registry_id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY
- `entity_type` VARCHAR(50) NOT NULL
- `entity_index_id` BIGINT NOT NULL
- `entity_index` BIGINT NOT NULL
- `federation_node_id` BIGINT NOT NULL DEFAULT 1
- `reserved_ymdhis` BIGINT NOT NULL DEFAULT 0
- `metadata` TEXT
- `entity_key` VARCHAR(255)
- `entity_name` VARCHAR(255)
- `entity_table` VARCHAR(100)
- `created_ymdhis` BIGINT NOT NULL DEFAULT 0
- `updated_ymdhis` BIGINT NOT NULL
- `is_deleted` TINYINT NOT NULL DEFAULT 0
- `deleted_ymdhis` BIGINT
- `is_active` TINYINT NOT NULL DEFAULT 1
- `is_kernel` TINYINT NOT NULL DEFAULT 0
- `metadata_json` TEXT

**Table 2:** `lupo_registry_open`  
**Location:** Line ~3434  
**Status:** COMPLETE

**Columns:**
- `unregistry_id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY
- `entity_type` VARCHAR(50) NOT NULL
- `entity_index` BIGINT NOT NULL
- `federation_node_id` BIGINT NOT NULL DEFAULT 1
- `reserved_ymdhis` BIGINT NOT NULL DEFAULT 0

**MD Import Support:** ✅ COMPLETE
- Can track reserved actor IDs
- Can track reserved channel IDs
- Supports entity_type for different entities

---

## 6. TASKS SYSTEM AUDIT

### ❌ TASKS TABLES MISSING

**Required Tables:** 7  
**Found:** 0  
**Status:** MISSING

**Missing Tables:**
1. `lupo_task_types` - Task type registry
2. `lupo_task_statuses` - Task status registry
3. `lupo_task_priorities` - Task priority registry
4. `lupo_tasks` - Core tasks table
5. `lupo_task_assignments` - Task assignments
6. `lupo_task_dependencies` - Task dependencies
7. `lupo_task_events` - Task audit log

**Impact:**
- Cannot import offline tasks from MD files
- Cannot track task management in database
- Separate migration file exists (`add_tasks_schema_4.0.45.sql`) but not integrated into source of truth

**Resolution Required:**
- Integrate all 7 task tables from `add_tasks_schema_4.0.45.sql` into `install_new_lupopedia.sql`
- Add tables before final registry inserts
- Maintain consistency with existing table naming and conventions

---

## 7. MD IMPORT READINESS SUMMARY

### ✅ Threads Support
- Table: lupo_dialog_threads
- Can create threads: YES
- Can link to channels: YES
- Can track creator: YES
- Metadata support: YES (metadata_json)

### ✅ Actors Support
- Table: lupo_actors
- Can create actors: YES (explicit ID)
- Can link to agents: YES (lupo_agents)
- Registry support: YES (lupo_registry)
- Metadata support: YES (metadata_json)

### ✅ Messages Support
- Table: lupo_dialog_doctrine
- Can import messages: YES
- Can link from/to actors: YES
- Can link to threads: YES
- Can link to channels: YES
- Metadata support: YES (metadata_json)
- FLP/FLIP support: YES

### ❌ Tasks Support
- Tables: MISSING
- Can import tasks: NO
- Must add 7 tables to install SQL

---

## 8. SCHEMA CONVENTIONS OBSERVED

**Timestamp Format:**
- All tables use `BIGINT` for timestamps
- Format: YYYYMMDDHHIISS (e.g., 20260225230000)
- Columns: `created_ymdhis`, `updated_ymdhis`, `deleted_ymdhis`

**Soft Delete Pattern:**
- All tables have `is_deleted` TINYINT NOT NULL DEFAULT 0
- All tables have `deleted_ymdhis` BIGINT (nullable)

**Primary Keys:**
- Most tables use explicit BIGINT NOT NULL PRIMARY KEY
- Some use AUTO_INCREMENT, some don't (per RESERVED ID DOCTRINE)
- Task tables should NOT use AUTO_INCREMENT (explicit IDs)

**Indexes:**
- All tables have indexes on common query patterns
- created_ymdhis, updated_ymdhis, is_deleted are standard
- Foreign key columns are indexed

**JSON Support:**
- Many tables have `metadata_json` JSON or TEXT columns
- Supports FLP/FLIP headers and footers

---

## 9. RECOMMENDED CHANGES

### Add Task Tables to install_new_lupopedia.sql

**Location:** After `lupo_flip_artifacts` table (line ~3750)  
**Before:** Final registry inserts (line ~3790)

**Tables to Add (in order):**
1. lupo_task_types
2. lupo_task_statuses
3. lupo_task_priorities
4. lupo_tasks
5. lupo_task_assignments
6. lupo_task_dependencies
7. lupo_task_events

**Source:** `database/migrations/add_tasks_schema_4.0.45.sql`

**Changes Required:**
- Copy table definitions verbatim
- Maintain BIGINT timestamp format
- Maintain soft delete pattern
- Maintain index naming conventions
- Add comment header for section

---

## 10. FINAL VERDICT

### ✅ MD Import Support: READY (except tasks)

**Threads:** ✅ COMPLETE  
**Actors:** ✅ COMPLETE  
**Messages:** ✅ COMPLETE  
**Channels:** ✅ COMPLETE  
**Registry:** ✅ COMPLETE  
**Tasks:** ❌ MISSING (must be added)

### Action Required

Integrate 7 task tables from `add_tasks_schema_4.0.45.sql` into `install_new_lupopedia.sql` to make it the complete source of truth.

---

<!-- FLIP_FOOTER_BEGIN
{
  "references": [
    "database/migrations/install_new_lupopedia.sql",
    "database/migrations/add_tasks_schema_4.0.45.sql",
    "CHANGELOG.md"
  ],
  "implements": "schema_audit",
  "depends_on": "install_sql_source_of_truth",
  "includes": "threads_audit,actors_audit,messages_audit,channels_audit,registry_audit,tasks_audit",
  "version": "4.0.45",
  "last_verified": "20260225",
  "last_verified_by": "kiro"
}
FLIP_FOOTER_END -->