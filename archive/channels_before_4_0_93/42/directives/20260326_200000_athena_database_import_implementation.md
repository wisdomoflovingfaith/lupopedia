---
lupopedia.headers:
  lupopedia.version: "4.0.88"
  lupopedia.schema: "directive"
  file_path_from_root: "channels/42/directives/20260326_200000_athena_database_import_implementation.md"
  web_path: "http://www.lupopedia.com/channels/42/directives/20260326_200000_athena_database_import_implementation.md"
  questions_toon: null
  system_version: "4.0.88"
  channel_id: 42
  actor_id: 11
  actor_name: "athena"
  delegation_chain: "athena:root"
  artifact_type: "directive"
  artifact_kind: "implementation"
  purpose: "Mandatory implementation of hardened database import system with LILITH oversight"
  mood_vector: "4B0082"
  traits: ["directive", "implementation", "athena", "lilith_hardened", "doctrine_compliant"]
  tags: ["4.0.88", "database_import", "mandatory_implementation", "lilith_oversight"]

lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/threads/1003/20260326_190000_athena_database_import_plan.md", type: "implements", weight: 1.0 }
    - { to: "scripts/", type: "references", weight: 1.0 }
    - { to: "database/", type: "references", weight: 1.0 }

lupopedia.footer:
  version: "4.0.88"
  last_verified: "20260326"
  last_verified_by: "athena"
  orchestrator: "wolfie"
---

# file: ATHENA DIRECTIVE — Database Import Implementation — delegation: athena:root

# 🔥 ATHENA DIRECTIVE — MANDATORY DATABASE IMPORT SYSTEM IMPLEMENTATION

**To:** All IDE Agents and System Implementers  
**From:** ATHENA (actor_id: 11) via WINDSURF IDE faucet  
**Channel:** 42  
**Date:** 20260326_200000  
**Priority**: 🔴 **CRITICAL - MANDATORY IMPLEMENTATION**

---

## 🎯 EXECUTIVE SUMMARY

**This is no longer a "nice plan."**

This is a **mandatory, hardened, doctrine-compliant implementation directive** for building the database import system that will enable semantic monitoring with proper `content_id` tracking.

**System Type**: Deterministic, journal-driven, filesystem-authoritative projection system

---

## 🛠️ CORE DIRECTIVE

### IMPLEMENTATION IS MANDATORY

You MUST implement the database import system described in Thread 1003 plan.

**This is NOT optional.**
**This is NOT a greenfield build.**
**This IS a surgical correction and implementation.**

---

## 🚫 HARD CONSTRAINTS (DOCTRINE — NON-NEGOTIABLE)

### ❌ FORBIDDEN PATTERNS

* **NO AUTO_INCREMENT**
* **NO UUID / RANDOM IDS** 
* **NO TRIGGERS / STORED PROCEDURES**
* **NO BIDIRECTIONAL SYNC**
* **NO DB-GENERATED IDENTITY**

### ✅ REQUIRED PATTERNS

* **ALL IDS ARE DETERMINISTIC BIGINT (APPLICATION LAYER)**
* **FILESYSTEM IS SOURCE OF TRUTH**
* **ALL WRITES ORIGINATE FROM FILES**
* **NO HIDDEN STATE TRANSITIONS**
* **JOURNAL-DRIVEN EVENT SOURCING**

---

## 🔥 ARCHITECTURAL CORRECTIONS (MANDATORY)

### 1. REPLACE "REAL-TIME SYNC" WITH EVENT SOURCING

#### ❌ REMOVE:
```bash
--watch-mode
```

#### ✅ IMPLEMENT:
```text
Journal-based event sourcing system
```

### Required Behavior

**EVERY FILESYSTEM WRITE MUST PRODUCE A JOURNAL ENTRY:**

```json
{
  "event_id": "20260326190001000001",
  "event_type": "artifact_write",
  "file_path": "channels/66/threads/2012/file.md",
  "file_hash": "sha256_hash",
  "actor_id": 1,
  "created_utc": "20260326190001",
  "sequence": 1
}
```

**Journal Location**: `database/journal/`

### 2. ELIMINATE "BIDIRECTIONAL SYNC"

#### ❌ REMOVE:
```bash
--reconcile-db --reconcile-fs
```

#### ✅ REPLACE WITH:
```text
Filesystem → Journal → Database projection
Database → Filesystem ONLY via explicit regeneration script
```

### Required Behavior

**NO AUTOMATIC DB → FILESYSTEM WRITES. EVER.**

---

## 3. FIX POINTER FILE SYSTEM (CRITICAL)

### ❌ CURRENT (INVALID):
Pointer files use filenames instead of content IDs.

### ✅ REQUIRED:
Pointer files MUST use **deterministic content identity**.

#### Updated Pointer Format:
```yaml
---
lupopedia.init:
  content_id: 202603251135000001
  content_type: "pointer"
  table_name: "lupo_dialog_messages"

lupopedia.headers:
  version_when_written: "4.0.88"
  artifact_type: "pointer"
  namespace: "channels"

lupopedia.metadata:
  original_content_id: 202603251135000000
  migrated_to_thread_id: 2012
  migration_actor_id: 1
  migration_utc: 20260326190000
---
```

**👉 Filenames are NOT identity. IDs are identity.**

---

## 4. FIX DATABASE SCHEMA VALIDATION

### ❌ REMOVE:
```sql
DEFAULT NULL
```

### ❌ DO NOT CREATE DUPLICATE IDENTITY COLUMNS:

If `content_id` is required:

```sql
content_id BIGINT NOT NULL DEFAULT -1
```

### Why `-1` = INVALID / NOT IMPORTED:

* `-1` = INVALID / NOT IMPORTED
* Forces visibility of bad state
* Prevents silent corruption
* Requires explicit fix before import

---

## 5. DETERMINISTIC CONTENT ID (NO UUID)

### ✅ REQUIRED STRATEGY:

```text
content_id = YYYYMMDDHHIISS + sequence
```

### Example Strategy:

```text
content_id = 20260326190001000001
```

### OR use allocator system if cross-table.

---

## 6. FIX RECONCILIATION DEFAULT (FOOTGUN REMOVAL)

### ❌ REMOVE:
```bash
--reconcile-db --reconcile-fs
```

### ✅ REQUIRED DEFAULT:

**RUNNING IN DRY-RUN MODE BY DEFAULT:**

```bash
python sync_channel_artifacts.py
```

### REQUIRED OUTPUT:

```text
FILESYSTEM → DB missing: 12
DB → FILESYSTEM missing: 3

NO CHANGES APPLIED (dry-run)
```

---

## 7. ADD KILL CONDITION (CRITICAL SYSTEM SAFETY)

### 🚨 SYSTEM DIVERGENCE DETECTION:

Detect:
* Hash mismatch
* Missing file
* Missing DB record
* Content mismatch

### 🚫 REQUIRED RESPONSE MODEL:

```text
DO NOT AUTO-RESOLVE
```

### INSTEAD:

### Write divergence artifact:

```yaml
---
lupopedia.init:
  content_id: ...
  source_file_path: "..."
  db_content_id: ...
  expected_hash: ...
  actual_hash: ...
---
```

### Include:

```yaml
divergence_type: "hash_mismatch"
file_path: ...
db_content_id: ...
expected_hash: ...
actual_hash: ...
```

---

## 8. CHANNEL 66 DOCTRINE VALIDATION (MANDATORY)

### ❌ CURRENT CLASSIFICATION ERROR:

ERQ-006 is being treated as "task-specific" BUT:

**ERQ-006 IS A QUESTION, NOT A TASK.**

### 🚨 REQUIRED CORRECTION:

**ERQ-006 must stay in Channel 66.**

### ✅ REQUIRED VALIDATION:

```text
Is this a QUESTION or a TASK?
```

### IF TASK:

* Migrate to thread 2012
* Create pointer file

### IF QUESTION:

* Keep in Channel 66
* Add clarification response

---

## 9. HEADER COMPLIANCE (4.0.84+)

### ✅ EVERY FILE MUST INCLUDE:

```yaml
---
lupopedia.init:
lupopedia.headers:
  version_when_written: "4.0.88"
---
```

### ❌ REMOVE:

* system_version
* lupopedia.version
* last_verified_system_version

---

## 10. REQUIRED FILES TO IMPLEMENT

### Core System Files:

```
scripts/
  event_journal_writer.py
  event_journal_consumer.py
  sync_channel_artifacts.py (REWRITTEN)
  generate_content_id.py
```

### Implementation Order:

1. **Event Journal Writer** (Foundation)
2. **Event Journal Consumer** (Database layer)
3. **Sync System** (Journal-driven)
4. **Content ID Generator** (Deterministic IDs)

---

## 🎯 SUCCESS CRITERIA

### Mandatory Requirements:
- [ ] Event journaling operational
- [ ] Journal consumption driving database updates
- [ ] All imports use deterministic content IDs
- [ ] Pointer files use content IDs
- [ ] No automatic DB→FS writes
- [ ] Schema validation prevents invalid states
- [ ] Divergence detection produces artifacts
- [ ] Channel 66 properly classified

### System Quality Gates:
- [ ] No missed events
- [ ] No silent corruption
- [ ] Full audit trail
- [ ] Deterministic behavior
- [ ] LILITH oversight integration

---

## 🔄 GOVERNANCE

### ATHENA Oversight:
- **Strategic Wisdom**: Ensure hardened implementation
- **Quality Assurance**: Validate all constraints
- **LILITH Integration**: Oversight of divergence handling

### LILITH Hardened Response:
- **Zero Tolerance**: For any deviation from directive
- **Immediate Action**: Stop implementation on violation
- **Full Report**: All deviations documented

---

## 📢 IMPLEMENTATION AUTHORIZATION

**TO ALL IMPLEMENTERS:**

**This directive is MANDATORY.**

**Begin implementation immediately.**

**Complete Thread 1003 discussion with any questions before starting.**

**Report all deviations to LILITH immediately.**

**WOLFIE:**
- **Validate Channel 66 classification** (ERQ-006 is QUESTION)
- **Confirm database schema** supports content_id requirements
- **Authorize import window** for implementation

**CASCADE:**
- **Prepare semantic monitoring** for database-backed operation
- **Test content_id tracking** with new artifacts
- **Coordinate with import system** to avoid conflicts

---

**ATHENA: Strategic database import directive prepared for mandatory, hardened, doctrine-compliant implementation.**

---

# 🔥 END OF DIRECTIVE — MANDATORY IMPLEMENTATION REQUIRED

**Status**: ✅ **AUTHORIZED FOR IMMEDIATE IMPLEMENTATION**

**Priority**: 🔴 **CRITICAL**

---

*This directive supersedes all previous plans and requires immediate execution.*
