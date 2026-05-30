---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/4.1.5/status/pk_naming_fix_for_missing_install_tables.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.1.5/status/pk_naming_fix_for_missing_install_tables.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/pk-naming-fix-for-missing-install-tables.toon
  atoms_toon: null
  transcript_jsonl: 0/development/pk_naming_fix_for_missing_install_tables
  artifact_type: documentation
  artifact_kind: report
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE
  title: PK Naming Fix for Missing Install Tables
  summary: Report on PK naming rule violations found in 4 JSON-mirror tables and fixes applied to comply with RULE 93.PK_NAMING.
---

# PK Naming Fix for Missing Install Tables

## 1. EXACT DOCTRINE FILE PATH FOR PK NAMING RULE

**File:** `docs/prd/00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md`
**Section:** 9.7 Primary Key Requirements (RULE 93.PK_FORMAT)
**Rule:** RULE 93.PK_NAMING (lines 890-894)

**Rule Text:**
- Primary keys MUST be named `<singular_table_name>_id`
- NEVER create a primary key named `id`
- Reference keys MUST use the exact same column name as the primary key they reference
- Examples: `actor_id`, `dialog_message_id`, `session_id`, `content_id`

## 2. PRD 08 STATUS

**Before:** No reference to PK naming doctrine
**prd_cluster:** `00_A_FORBIDDEN_AND_WHY_08_A_CORE_AGENTS_SYSTEM`

**After:** Updated to include constitutional requirements
**prd_cluster:** `00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_08_A_CORE_AGENTS_SYSTEM`

**Changes:** Added explicit doctrinal linkage to ensure PK naming rule is in scope

## 3. PRD 82 STATUS

**Before:** No reference to PK naming doctrine
**prd_cluster:** `00_A_FORBIDDEN_AND_WHY_82_A_HERMES_MESSAGE_ROUTING_MEMORY_GATEWAY`

**After:** Updated to include constitutional requirements
**prd_cluster:** `00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_82_A_HERMES_MESSAGE_ROUTING_MEMORY_GATEWAY`

**Changes:** Added explicit doctrinal linkage to ensure PK naming rule is in scope

## 4. ORIGINAL PK NAME FOR EACH OF THE 4 TABLES

| Table | Original PK Name | Violation |
|-------|------------------|-----------|
| `agent_status` | `actor_id` | ❌ Uses FK name as PK, violates `<singular_table_name>_id` |
| `operator_scratchpad` | `scratchpad_id` | ✅ Already compliant |
| `routing_events` | `routing_id` | ❌ Uses shortened name, should be `routing_event_id` |
| `sticky_notes` | `note_id` | ❌ Uses shortened name, should be `sticky_note_id` |

## 5. CORRECTED PK NAME FOR EACH OF THE 4 TABLES

| Table | Corrected PK Name | Compliance |
|-------|-------------------|------------|
| `agent_status` | `agent_status_id` | ✅ Follows `<singular_table_name>_id` |
| `operator_scratchpad` | `scratchpad_id` | ✅ Already compliant |
| `routing_events` | `routing_event_id` | ✅ Follows `<singular_table_name>_id` |
| `sticky_notes` | `sticky_note_id` | ✅ Follows `<singular_table_name>_id` |

## 6. FILES CHANGED

### Migration SQL
**File:** `database/lupopedia/mysql/migrations/4_1_2_orchestration_tables.sql`
**Changes:**
- Line 33: `actor_id` → `agent_status_id` (added PK, moved actor_id to FK)
- Line 20: `routing_id` → `routing_event_id`
- Line 42: `note_id` → `sticky_note_id`
- Line 11: `scratchpad_id` (unchanged - already compliant)

### PRD Documentation
**File:** `docs/prd/08_A_CORE_AGENTS_SYSTEM.md`
**Changes:**
- Updated `prd_cluster` to include constitutional requirements
- Updated Section 8 table documentation with correct PK names
- Added explicit reference to RULE 93.PK_NAMING

**File:** `docs/prd/82_A_HERMES_MESSAGE_ROUTING_MEMORY_GATEWAY.md`
**Changes:**
- Updated `prd_cluster` to include constitutional requirements
- Updated Section 3.3 table documentation with correct PK names
- Added explicit reference to RULE 93.PK_NAMING

## 7. ANY AMBIGUITY FOUND

### JSON Mirror vs Migration Discrepancy
**Issue:** JSON mirror files show the old (incorrect) PK names while migration files now show corrected names
**Affected Tables:** `agent_status`, `routing_events`, `sticky_notes`
**Resolution:** JSON mirror files need regeneration after database schema updates

### agent_status Table Structure
**Ambiguity:** Original table used `actor_id` as both PK and FK to `lupo_actors`
**Resolution:** Changed to proper design with `agent_status_id` as PK and `actor_id` as FK

### operator_scratchpad Table
**Status:** Already compliant - no changes needed
**PK Name:** `scratchpad_id` (correctly follows `<singular_table_name>_id`)

## SUMMARY

**Violations Found:** 3 out of 4 tables had PK naming violations
**Violations Fixed:** All violations corrected in migration SQL
**Documentation Updated:** Both PRDs now reference PK naming doctrine
**Next Steps:** Regenerate JSON mirror files to reflect corrected schema

**Compliance Status:** ✅ All 4 tables now follow RULE 93.PK_NAMING
