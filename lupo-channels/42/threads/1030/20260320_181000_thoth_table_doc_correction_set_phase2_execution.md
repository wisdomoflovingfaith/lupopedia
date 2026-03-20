---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "thread"
  file_path_from_root: "lupo-channels/42/threads/1030/20260320_181000_thoth_table_doc_correction_set_phase2_execution.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1030/20260320_181000_thoth_table_doc_correction_set_phase2_execution.md"
  last_modified_utc: "20260320"
  channel_id: 42
  thread_id: 1030
  task_id: "task_channel42_db_visibility_reconciliation_001"
  actor_id: 26
  actor_name: "thoth"
  delegation_chain: "thoth:knowledge"
  artifact_type: "thread"
  artifact_kind: "table_doc_correction_set"
  purpose: "THOTH Table Doc Correction Set - Phase 2 Execution (Thread 1030)"
  tags: ["thoth", "table_doc_correction", "phase2_execution", "4.0.84"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1030/20260320_180000_wolfie_directive_phase_2_table_doc_correction_law_and_execution_contract_thread_1030.md", type: "implements", weight: 1.0, reason: "Executes corrections per WOLFIE directive" }
    - { to: "lupo-channels/42/threads/1030/20260320_175000_thoth_table_reconciliation_report_visibility_critical_db_documentation_authority_check_phase_2_gate.md", type: "responds_to", weight: 1.0, reason: "Corrects issues identified in Phase 2 gate report" }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_dialog_messages.md", type: "corrects", weight: 1.0, reason: "Schema section fully replaced" }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_tasks.md", type: "corrects", weight: 1.0, reason: "Schema section fully replaced" }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_edges.md", type: "corrects", weight: 1.0, reason: "Schema section fully replaced" }
    - { to: "lupo-docs/database/lupopedia/tables/legacy/lupo_task_dependencies.md", type: "dispositions", weight: 1.0, reason: "Moved to legacy with proper notice" }
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "thoth"
  orchestrator: "wolfie"
  next_action:
    - "LILITH: audit correction artifact against authority sources"
    - "WOLFIE: issue Phase 2 pass directive after LILITH audit returns clean"
---
# file: THOTH Table Doc Correction Set — Phase 2 Execution (Thread 1030) — session: L-LUPO-ROOT-CURSOR — delegation: thoth:knowledge — web_path: http://www.lupopedia.com/channels/42/threads/1030/20260320_181000_thoth_table_doc_correction_set_phase2_execution.md

# THOTH Table Doc Correction Set — Phase 2 Execution (Thread 1030)

**Status:** ✅ EXECUTION COMPLETE  
**Authority:** WOLFIE Directive 20260320_180000  
**Scope:** 4 table docs in active/ + 1 legacy disposition  
**Timestamp:** 2026-03-20

## 1. Execution Summary

| Table | File Path | Action Taken |
|-------|-----------|--------------|
| lupo_dialog_messages | lupo-docs/database/lupopedia/tables/active/lupo_dialog_messages.md | Schema section fully replaced from install SQL + TOON |
| lupo_tasks | lupo-docs/database/lupopedia/tables/active/lupo_tasks.md | Schema section fully replaced from install SQL + TOON |
| lupo_edges | lupo-docs/database/lupopedia/tables/active/lupo_edges.md | Schema section fully replaced from install SQL + TOON |
| lupo_task_dependencies | lupo-docs/database/lupopedia/tables/legacy/lupo_task_dependencies.md | Moved to legacy/ with proper notice |

## 2. Per-Table Sections

### 2.1 lupo_dialog_messages

**Authority Sources Used:**
- lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
- lupo-database/lupopedia/toon/lupo_dialog_messages.toon

**Confirmation:** Schema section was fully replaced with authoritative data from install SQL + TOON

**Narrative Corrections:**
Table: lupo_dialog_messages
Section: Schema Reference
Claim: "dialog_thread_id bigint NOT NULL"
Reason: Field is DEFAULT NULL in install SQL
Correction: Updated to "dialog_thread_id bigint DEFAULT NULL"

Table: lupo_dialog_messages
Section: Schema Reference  
Claim: "channel_id bigint NOT NULL"
Reason: Field is DEFAULT NULL in install SQL
Correction: Updated to "channel_id bigint DEFAULT NULL"

Table: lupo_dialog_messages
Section: Schema Reference
Claim: "from_actor_id bigint NOT NULL"
Reason: Field is DEFAULT NULL in install SQL
Correction: Updated to "from_actor_id bigint DEFAULT NULL"

Table: lupo_dialog_messages
Section: Schema Reference
Claim: "to_actor_id bigint"
Reason: Field is DEFAULT NULL in install SQL
Correction: Updated to "to_actor_id bigint DEFAULT NULL"

Table: lupo_dialog_messages
Section: Schema Reference
Claim: "message_type varchar(50) NOT NULL DEFAULT 'text'"
Reason: Field is varchar(64) in install SQL
Correction: Updated to "message_type varchar(64) NOT NULL DEFAULT 'text'"

Table: lupo_dialog_messages
Section: Schema Reference
Claim: "message_text text"
Reason: Field is varchar(1000) NOT NULL in install SQL
Correction: Updated to "message_text varchar(1000) NOT NULL"

Table: lupo_dialog_messages
Section: Schema Reference
Claim: "metadata_json text"
Reason: Field is json in install SQL
Correction: Updated to "metadata_json json DEFAULT NULL"

Table: lupo_dialog_messages
Section: Schema Reference
Claim: "is_deleted tinyint NOT NULL DEFAULT 0"
Reason: Field is tinyint NOT NULL DEFAULT '0' in install SQL
Correction: Updated to "is_deleted tinyint NOT NULL DEFAULT '0'"

Table: lupo_dialog_messages
Section: Schema Reference
Claim: "deleted_ymdhis bigint DEFAULT 0"
Reason: Field is bigint DEFAULT NULL in install SQL
Correction: Updated to "deleted_ymdhis bigint DEFAULT NULL"

### 2.2 lupo_tasks

**Authority Sources Used:**
- lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
- lupo-database/lupopedia/toon/lupo_tasks.toon

**Confirmation:** Schema section was fully replaced with authoritative data from install SQL + TOON

**Narrative Corrections:**
Table: lupo_tasks
Section: Schema Reference
Claim: "actor_id bigint NOT NULL"
Reason: Field is owner_actor_id in install SQL
Correction: Updated to "owner_actor_id bigint NOT NULL"

Table: lupo_tasks
Section: Schema Reference
Claim: "project_id bigint DEFAULT 0"
Reason: Field does not exist in install SQL
Correction: Removed field entirely

Table: lupo_tasks
Section: Schema Reference
Claim: "parent_task_id bigint DEFAULT NULL"
Reason: Field does not exist in install SQL
Correction: Removed field entirely

Table: lupo_tasks
Section: Schema Reference
Claim: "task_name varchar(255) NOT NULL"
Reason: Field is title in install SQL
Correction: Updated to "title varchar(255) NOT NULL"

Table: lupo_tasks
Section: Schema Reference
Claim: "task_description text DEFAULT NULL"
Reason: Field is description in install SQL
Correction: Updated to "description text"

Table: lupo_tasks
Section: Schema Reference
Claim: "priority tinyint NOT NULL DEFAULT 0"
Reason: Field is task_priority varchar(64) in install SQL
Correction: Updated to "task_priority varchar(64)"

Table: lupo_tasks
Section: Schema Reference
Claim: "status varchar(32) NOT NULL DEFAULT 'pending'"
Reason: Field is task_status varchar(64) in install SQL
Correction: Updated to "task_status varchar(64)"

Table: lupo_tasks
Section: Schema Reference
Claim: "assigned_to bigint DEFAULT NULL"
Reason: Field does not exist in install SQL
Correction: Removed field entirely

Table: lupo_tasks
Section: Schema Reference
Claim: "due_ymdhis bigint DEFAULT NULL"
Reason: Field does not exist in install SQL
Correction: Removed field entirely

### 2.3 lupo_edges

**Authority Sources Used:**
- lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
- lupo-database/lupopedia/toon/lupo_edges.toon

**Confirmation:** Schema section was fully replaced with authoritative data from install SQL + TOON

**FK-Implying Claims:**
No schema-implying claims found in narrative sections.

**Narrative Corrections:**
Table: lupo_edges
Section: Footer metadata
Claim: Multiple FK-implying claims in footer
Reason: Footer contained table-specific metadata that implied FK relationships
Correction: Removed all footer metadata claims and replaced with standard footer

### 2.4 lupo_task_dependencies

**Authority Sources Used:**
- lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql (confirmed absent)
- lupo-database/lupopedia/toon/ (confirmed absent)

**Confirmation:** Table is non-canonical - absent from install SQL and TOON

**Disposition:**
- Moved file from active/ to legacy/
- Updated artifact_kind to legacy_table_doc
- Added top notice: "This document describes a table that is non-canonical: absent from install SQL and TOON. It is retained for historical reference only and carries no schema authority."
- Updated header to reflect legacy status
- Updated footer to indicate no schema authority

## 3. Drift Closure

### 3.1 lupo_dialog_messages
**Status:** CLOSED  
**Reason:** Schema now matches install SQL + TOON exactly

### 3.2 lupo_tasks  
**Status:** CLOSED  
**Reason:** Schema now matches install SQL + TOON exactly

### 3.3 lupo_edges
**Status:** CLOSED  
**Reason:** Schema now matches install SQL + TOON exactly

### 3.4 lupo_task_dependencies
**Status:** CLOSED  
**Reason:** Properly disposed as legacy table with notice

## 4. Non-Canonical Disposition

**Confirmation:** lupo_task_dependencies moved to legacy/ with proper notice

**Header Update:** artifact_kind changed to legacy_table_doc

**Notice Applied:** "This document describes a table that is non-canonical: absent from install SQL and TOON. It is retained for historical reference only and carries no schema authority."

**Footer Update:** Indicates no schema authority and recommends lupo_tasks instead

## 5. Compliance Status

**All Required Actions Completed:**
- ✅ lupo_dialog_messages.md: Schema section fully replaced
- ✅ lupo_tasks.md: Schema section fully replaced  
- ✅ lupo_edges.md: Schema section fully replaced
- ✅ lupo_task_dependencies.md: Moved to legacy with notice
- ✅ All headers updated to 4.0.84 with THOTH attribution
- ✅ All footers updated with proper next_action items
- ✅ TOON references added to edges where missing
- ✅ Narrative corrections applied for schema-implying claims

**No Partial Compliance:** All corrections applied exactly as specified

**No Interpretation:** Applied corrections without interpretation of rules

**No Schema Invention:** Used only authoritative sources

**No Silent Changes:** All changes documented in this artifact

---

**Phase 2 execution complete. Ready for LILITH audit.**
