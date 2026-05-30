---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: operational_guide
  when_updated: null
  file_path_from_root: "docs/versions/4.0.88/DATABASE_IMPORT_OPERATIONAL_GUIDE.md"
  web_path: "http://www.lupopedia.com/versions/4.0.88/DATABASE_IMPORT_OPERATIONAL_GUIDE.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: operational_guide
  artifact_kind: documentation
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# DATABASE IMPORT OPERATIONAL GUIDE

**Version**: 4.0.88  
**Authority**: ATHENA (actor_id: 11)  
**Status**: ✅ **HARDENED IMPLEMENTATION REQUIRED**  
**Implementation Mode**: Event Sourcing + Deterministic IDs + Kill Conditions

---

## 🎯 EXECUTIVE SUMMARY

This is the **operational manual** for the hardened, journal-driven database import system. It implements the ATHENA DIRECTIVE's requirements for:

1. **Event Sourcing** — All filesystem writes tracked in immutable journal
2. **Deterministic IDs** — No UUID, no AUTO_INCREMENT; all IDs are BIGINT YYYYMMDDHHIISS + sequence
3. **Kill Conditions** — Stop on divergence, write artifact, require manual review
4. **Filesystem Authority** — All writes originate from files, journal drives DB updates
5. **Dry-Run Default** — No database changes unless explicitly approved

---

## 🔨 4 CORE SCRIPTS

### 1. generate_content_id.py
**Purpose**: Allocate deterministic content IDs

**Usage**:
```bash
# Generate single ID for a file
python scripts/generate_content_id.py

# Allocate for specific artifact
python scripts/generate_content_id.py \
  channels/42/threads/1003/file.md 1 lupo_dialog_messages
```

**Output**:
```
content_id: 20260326190001000001
file_path: channels/42/threads/1003/file.md
file_hash: sha256_hash
table_name: lupo_dialog_messages
```

**Key Detail**: All IDs = YYYYMMDDHHIISS (14 chars) + sequence (6 chars) = 20-digit BIGINT

### 2. event_journal_writer.py
**Purpose**: Write filesystem artifact changes to journal

**Usage**:
```bash
# Write journal entry for a created file
python scripts/event_journal_writer.py write \
  channels/42/threads/1003/file.md created

# Write journal entry for updated file
python scripts/event_journal_writer.py write \
  channels/42/broadcasts/file.md updated

# Check journal status
python scripts/event_journal_writer.py status

# Load recent journal entries
python scripts/event_journal_writer.py load 10
```

**Output Location**: `database/journal/{event_id}.json`

**Entry Format**:
```json
{
  "event_id": "20260326190001000001",
  "event_type": "artifact_write",
  "file_path": "channels/66/threads/2012/file.md",
  "file_hash": "sha256_hash",
  "actor_id": 0,
  "created_utc": "20260326190001",
  "file_status": "created",
  "file_size": 1024,
  "content_preview": "..."
}
```

### 3. event_journal_consumer.py
**Purpose**: Consume journal events and apply to database

**Usage**:
```bash
# Check consumer status (divergences, pending events)
python scripts/event_journal_consumer.py status

# Dry-run: test processing single event
python scripts/event_journal_consumer.py dryrun 20260326190001000001

# Apply: import event to database
python scripts/event_journal_consumer.py apply 20260326190001000001
```

**Kill Conditions** (MANDATORY STOP):
- ❌ FILE_NOT_FOUND — File referenced in journal missing from filesystem
- ❌ HASH_MISMATCH — File hash changed since journal entry created
- ❌ DUPLICATE_CONTENT_ID — content_id already in database

**Response**: Write divergence artifact to `database/divergences/`, do NOT continue

### 4. sync_channel_artifacts.py
**Purpose**: Orchestrate channel artifact sync (filesystem → journal → database)

**Usage**:
```bash
# DRY-RUN (default): Report what would sync
python scripts/sync_channel_artifacts.py --repo-root .

# DRY-RUN specific channel
python scripts/sync_channel_artifacts.py --repo-root . --channel 42

# VALIDATE: Check for divergences
python scripts/sync_channel_artifacts.py --validate

# STATUS: Show sync state
python scripts/sync_channel_artifacts.py --status

# SYNC: apply to database (REQUIRES APPROVAL)
python scripts/sync_channel_artifacts.py --sync

# Output as JSON
python scripts/sync_channel_artifacts.py --json
```

**Output** (dry-run example):
```json
{
  "mode": "dry_run",
  "timestamp": "20260326190001",
  "artifacts_found": 42,
  "artifacts_ready": 41,
  "artifacts_error": 1,
  "details": [
    {
      "file_path": "channels/42/threads/1003/file.md",
      "channel_id": 42,
      "thread_id": 1003,
      "status": "READY",
      "action": "WOULD_IMPORT"
    }
  ]
}
```

---

## 📊 OPERATIONAL WORKFLOW

### Phase 1: Scan Artifacts (Read-Only)
```bash
# See what exists in filesystem
python scripts/sync_channel_artifacts.py --status
```

**Output**: Report of channels, threads, broadcasts found.

### Phase 2: Validate for Divergences (Read-Only)
```bash
# Check filesystem ←→ journal consistency
python scripts/sync_channel_artifacts.py --validate
```

**Output**: List of divergences (if any). If divergences found:
- 🛑 STOP
- Review divergence artifacts in `database/divergences/`
- Manually resolve conflicts
- Repeat validation

### Phase 3: Dry-Run Import (Read-Only)
```bash
# See what would be imported WITHOUT making changes
python scripts/sync_channel_artifacts.py --repo-root . --channel 42 --dry-run
```

**Output**: List of artifacts ready to import, any errors encountered.

### Phase 4: Apply to Database (WRITE)
```bash
# ⚠️ THIS MODIFIES DATABASE — requires explicit approval
python scripts/sync_channel_artifacts.py --sync
```

**Safeguards**:
1. BLOCKED if divergences exist (no auth override)
2. All writes journaled
3. Hash verification before every insert
4. Divergence artifacts written on mismatch

---

## 🚨 KILL CONDITIONS (MANDATORY STOPS)

### Condition 1: File Missing
**Trigger**: Journal entry references file that doesn't exist in filesystem

**Response**: 
- Write divergence artifact
- Print error: `FILE_NOT_FOUND: channels/42/threads/1003/file.md`
- STOP — do NOT create phantom DB records

### Condition 2: Hash Mismatch
**Trigger**: File hash changed since journal entry was created

**Response**:
- Write divergence artifact with:
  - `expected_hash`: hash from journal
  - `actual_hash`: current file hash
  - `file_path`: path to file
- STOP — do NOT import modified data without review

### Condition 3: Duplicate Content ID
**Trigger**: content_id already exists in database

**Response**:
- Check if in-memory conflict or stale import
- Write divergence artifact for LILITH review
- STOP — do NOT overwrite existing records

### Condition 4: Divergence Already Detected
**Trigger**: `database/divergences/` contains unresolved issues

**Response**:
- BLOCK all sync operations
- Print: `Cannot sync while {N} divergences exist`
- Operator must review and manually resolve

---

## 📝 CONTENT_ID ALLOCATION STRATEGY

### Deterministic ID Format
```
content_id = YYYYMMDDHHIISS + SEQUENCE
           = 14 digits       + 6 digits
           = 20-digit BIGINT
```

**Example**: `20260326190001000001`

### Allocation Per Artifact
1. **Timestamp Base**: Current UTC time (YYYYMMDDHHIISS)
2. **Sequence**: 1-999999 (distinguishes multiple artifacts in same second)
3. **Result**: Fully deterministic, reproducible, no randomness

### Table Mapping
| Table | content_id Strategy | Rationale |
|-------|---------------------|-----------|
| lupo_dialog_messages | TIMESTAMP + SEQ | Artifact creation time + ordering |
| lupo_edges | TIMESTAMP + SEQ | Edge creation order |
| lupo_visits | TIMESTAMP + SEQ | Visit sequence tracking |
| lupo_channels | MANUAL ALLOCATION | Channel IDs pre-exist |

---

## 🔍 MONITORING & AUDIT

### Journal Status
```bash
python scripts/event_journal_writer.py status
```

### Divergence Report
```bash
ls -la database/divergences/
```

### Consumer Status
```bash
python scripts/event_journal_consumer.py status
```

### Sync Status
```bash
python scripts/sync_channel_artifacts.py --status
```

---

## ❌ FORBIDDEN PATTERNS (ATHENA DOCTRINE)

### ❌ NO AUTO_INCREMENT
```sql
-- FORBIDDEN:
ALTER TABLE lupo_contents ADD COLUMN content_id BIGINT AUTO_INCREMENT;

-- ✅ CORRECT:
ALTER TABLE lupo_contents ADD COLUMN content_id BIGINT NOT NULL;
-- (Application generates ID)
```

### ❌ NO UUID
```python
# FORBIDDEN:
import uuid
content_id = uuid.uuid4()

# ✅ CORRECT:
from generate_content_id import generate_content_id
content_id = generate_content_id(file_path, sequence)
```

### ❌ NO BIDIRECTIONAL SYNC
```bash
# FORBIDDEN:
python sync.py --reconcile-db --reconcile-fs

# ✅ CORRECT:
python sync_channel_artifacts.py --dry-run
python sync_channel_artifacts.py --sync  # Filesystem → DB only
```

### ❌ NO AUTO-RESOLVE
```python
# FORBIDDEN:
if hash_mismatch:
    overwrite_db_record()  # ← NEVER

# ✅ CORRECT:
if hash_mismatch:
    write_divergence_artifact()
    exit(1)  # Require manual review
```

---

## 🎓 TRAINING CHECKLIST

Before using the import system:

- [ ] Read and understand event sourcing architecture
- [ ] Understand kill conditions and divergence handling
- [ ] Know content_id allocation strategy
- [ ] Able to run all 4 scripts in dry-run mode
- [ ] Able to read and interpret divergence artifacts
- [ ] Understand why AUTO_INCREMENT and UUID forbidden
- [ ] Understand why bidirectional sync removed
- [ ] Know when to STOP and escalate to LILITH

---

## 📞 ESCALATION PATH

### Problem: Divergence Detected
1. Print divergence artifact details
2. Escalate to LILITH (actor_id: 2) for review
3. Do NOT attempt auto-recovery

### Problem: Import Fails
1. Check journal status
2. Check consumer status
3. Review error messages
4. Write summary to Channel 42

### Problem: unsure about ID allocation
1. Run `generate_content_id.py` in test mode
2. Verify output format matches doctrine
3. Ask ATHENA (actor_id: 11) for guidance

---

**END OF OPERATIONAL GUIDE**

Revision: 4.0.88  
Authority: ATHENA  
Last Updated: 20260326

