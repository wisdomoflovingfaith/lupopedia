---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: null
  file_path_from_root: "docs/versions/4.0.88/DIVERGENCE_ARTIFACT_SCHEMA.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: specification
  artifact_kind: null
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
# Divergence Artifact Schema
## ATHENA DIRECTIVE 4.0.88 — Operational Specification

### Overview

**Divergence artifacts** are JSON documents created when the event journal system detects a discrepancy between expected and actual state. They serve as **kill conditions** and **manual resolution guides**.

**Core principle**: *Silent corruption is forbidden.* Every divergence writes an artifact before exiting, giving operators complete context for recovery.

---

## File Structure

```
database/divergences/
    20260326155650_FILE_NOT_FOUND.json
    20260326155651_HASH_MISMATCH.json
    20260326155652_DUPLICATE_CONTENT_ID.json
```

**Naming convention**: `{YYYYMMDDHHIISS}_{DIVERGENCE_TYPE}.json`

- `YYYYMMDDHHIISS` = UTC timestamp when divergence detected
- `DIVERGENCE_TYPE` = `FILE_NOT_FOUND` | `HASH_MISMATCH` | `DUPLICATE_CONTENT_ID`

---

## Complete Schema

### Root Keys

```json
{
  "kill_condition": "string",
  "timestamp": "string (YYYYMMDDHHIISS UTC)",
  "actor_id": "integer",
  "divergence_type": "string",
  "severity": "string",
  "file_path": "string",
  "content_id": "integer",
  "expected_state": "object",
  "actual_state": "object",
  "resolution_steps": "array of strings",
  "manual_review_required": true,
  "escalation_contact": "string",
  "_details": "object (debugging)"
}
```

### Detailed Field Specification

#### `kill_condition` (string, REQUIRED)

The state divergence that triggered this artifact.

**Valid values**:
- `FILE_NOT_FOUND` — Journal references a file that no longer exists on filesystem
- `HASH_MISMATCH` — File exists but SHA256 hash doesn't match journal entry
- `DUPLICATE_CONTENT_ID` — Proposed content_id already exists in database

**Example**:
```json
"kill_condition": "FILE_NOT_FOUND"
```

---

#### `timestamp` (string YYYYMMDDHHIISS UTC, REQUIRED)

When the divergence was detected (not when original event was created).

**Format**: `YYYYMMDDHHIISS` UTC — e.g., `20260326155650`

**Never**: 
- Local timezone
- ISO8601
- Unix epoch seconds
- Human-readable dates

**Example**:
```json
"timestamp": "20260326155650"
```

---

#### `actor_id` (integer, REQUIRED)

Which actor (agent/service) detected this divergence. Used for audit trail.

**Typical values**:
- `0` = System (synchronous check during import)
- `102` = Cursor (orchestration agent)
- `15` = HERMES (messaging router)
- `2` = LILITH (critic/monitor, if she detected it)

**Example**:
```json
"actor_id": 102
```

---

#### `divergence_type` (string, REQUIRED)

Canonical type code.

**Valid values**:
- `FILE_NOT_FOUND`
- `HASH_MISMATCH`
- `DUPLICATE_CONTENT_ID`

Must match filename. Used for filtering and scripting.

**Example**:
```json
"divergence_type": "HASH_MISMATCH"
```

---

#### `severity` (string, REQUIRED)

Operational severity classification.

**Valid values**:
- `CRITICAL` — Schema integrity violated, manual intervention required (HASH_MISMATCH, DUPLICATE_CONTENT_ID)
- `RECOVERABLE` — Environment issue, recovery procedure exists (FILE_NOT_FOUND if backup available)

**Usage**: Operators prioritize CRITICAL divergences first.

**Example**:
```json
"severity": "RECOVERABLE"
```

---

#### `file_path` (string, REQUIRED)

Filesystem path to the problematic artifact (relative to project root).

**Format**: Forward slashes only, no spaces, ASCII lowercase.

**Example**:
```json
"file_path": "channels/42/threads/1003/20260326_message.md"
```

---

#### `content_id` (integer, REQUIRED)

The content_id that was being allocated when divergence occurred.

Used to:
- Query database for related records
- Audit allocation history
- Debug sequence allocation issues

**Example**:
```json
"content_id": 120260326155650000001000042
```

---

#### `expected_state` (object, REQUIRED)

What the journal entry said should exist.

**Schema**:
```json
{
  "file_hash": "string (SHA256 hex)",
  "file_size": "integer (bytes)",
  "file_exists": "boolean",
  "content_id_unique": "boolean"
}
```

**Details**:

- **`file_hash`** (string): SHA256 hex digest from journal entry. Always 64 characters if valid.
  ```json
  "file_hash": "d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2"
  ```

- **`file_size`** (integer): Expected file size in bytes from journal.
  ```json
  "file_size": 4096
  ```

- **`file_exists`** (boolean): Always `true` — journal would never record a file that never existed.
  ```json
  "file_exists": true
  ```

- **`content_id_unique`** (boolean): Always `true` — content IDs are assumed unique at allocation.
  ```json
  "content_id_unique": true
  ```

**Example**:
```json
"expected_state": {
  "file_hash": "abc123...xyz",
  "file_size": 4096,
  "file_exists": true,
  "content_id_unique": true
}
```

---

#### `actual_state` (object, REQUIRED)

What we actually found on the filesystem and in the database.

**Schema**:
```json
{
  "file_exists": "boolean",
  "file_hash": "string|null (SHA256 hex, or error message, or null if DNE)",
  "file_size": "integer|null (bytes, or null if DNE)",
  "content_id_exists_in_db": "boolean|null"
}
```

**Details**:

- **`file_exists`** (boolean): Did we find the file on disk?
  ```json
  "file_exists": false
  ```

- **`file_hash`** (string|null): 
  - If file found: SHA256 hex digest (64 chars)
  - If file not found: `null`
  - If error reading: Error message string (e.g., "Permission denied")
  ```json
  "file_hash": "def456...uvw"
  ```

- **`file_size`** (integer|null):
  - If file found: Size in bytes
  - If file not found: `null`
  ```json
  "file_size": 4200
  ```

- **`content_id_exists_in_db`** (boolean|null):
  - `true` if content_id found in database
  - `false` if not found
  - `null` if DB query failed or not performed

**Example**:
```json
"actual_state": {
  "file_exists": false,
  "file_hash": null,
  "file_size": null,
  "content_id_exists_in_db": false
}
```

---

#### `resolution_steps` (array of strings, REQUIRED)

Ordered list of instructions for manual recovery.

**Format**: Each step is a numbered action with explanation.

**Principles**:
- Assume operator is trained but stressed (clear language)
- Include command examples with exact syntax
- Link to authentication/authorization requirements
- Provide multiple options when recovery paths exist
- Always end with "verify if resolved" step

**Example for FILE_NOT_FOUND**:
```json
"resolution_steps": [
  "1. Verify /path/to/file.md still exists on the filesystem",
  "2. If file was deleted, restore from backup: cp /backup/path/file.md /path/to/file.md",
  "3. Re-run sync with --retry flag: python sync_channel_artifacts.py --sync --retry",
  "4. Monitor database/divergences/ for new divergences",
  "5. If divergence clears, sync completed successfully"
]
```

**Example for HASH_MISMATCH**:
```json
"resolution_steps": [
  "1. File was modified after journal entry was created",
  "2. Expected hash (from journal): abc123...xyz",
  "3. Actual hash (current file):     def456...uvw",
  "4. Options:",
  "   a) Restore from backup (filesystem authority principle)",
  "      Command: cp /backup/channels/42/threads/1003/file.md channels/42/threads/1003/file.md",
  "   b) Regenerate journal entry with current file state",
  "      Command: python generate_content_id.py allocate channels/42/threads/1003/file.md",
  "5. After choosing a-b, re-run sync: python sync_channel_artifacts.py --sync --retry"
]
```

---

#### `manual_review_required` (boolean, REQUIRED)

Always `true` for initial divergences.

May be set to `false` by an operator after manual review/approval when:
- Operator has inspected actual_state vs expected_state
- Operator understands the divergence
- Operator has approved automatic recovery

Used by automation to:
- Skip escalation if already reviewed
- Audit who approved what recovery

**Example**:
```json
"manual_review_required": true
```

---

#### `escalation_contact` (string, REQUIRED)

Who to contact if the divergence cannot be resolved with provided steps.

**Standard values**:
- `LILITH (actor_id=2) — LIL001 non-interference protocol applies` (default)
- `ATHENA (actor_id=5) — Strategy and wisdom`
- `THOTH (actor_id=9) — Records and analysis`
- `MAAT (actor_id=7) — Truth and justice`

Escalation follows doctrine (actors can't block others, just provide analysis).

**Example**:
```json
"escalation_contact": "LILITH (actor_id=2) — LIL001 non-interference protocol applies"
```

---

#### `_details` (object, OPTIONAL)

Raw debugging information. Use prefix `_` to indicate internal/implementation detail.

**Typical contents**:
- Exception stack traces
- Database query results
- Filesystem error codes
- Configuration state when divergence occurred

**Example**:
```json
"_details": {
  "exception": "FileNotFoundError: [Errno 2] No such file or directory: 'channels/42/threads/1003/file.md'",
  "python_traceback": "...",
  "filesystem_check_time": "20260326155649"
}
```

---

## Complete Example: FILE_NOT_FOUND

```json
{
  "kill_condition": "FILE_NOT_FOUND",
  "timestamp": "20260326155650",
  "actor_id": 102,
  "divergence_type": "FILE_NOT_FOUND",
  "severity": "RECOVERABLE",
  "file_path": "channels/42/threads/1003/20260326_message.md",
  "content_id": 120260326155650000001000042,
  "expected_state": {
    "file_hash": "abc123def456abc123def456abc123def456abc123def456abc123def456abc1",
    "file_size": 4096,
    "file_exists": true,
    "content_id_unique": true
  },
  "actual_state": {
    "file_exists": false,
    "file_hash": null,
    "file_size": null,
    "content_id_exists_in_db": false
  },
  "resolution_steps": [
    "1. Verify channels/42/threads/1003/20260326_message.md exists on filesystem",
    "2. If file was deleted intentionally, update the channel manifest",
    "3. If file was deleted by accident, restore from backup:",
    "   docker cp backup-20260325:/backups/channels/42/1003/20260326_message.md channels/42/threads/1003/20260326_message.md",
    "4. Verify file hash matches expected (abc123...abc1):",
    "   sha256sum channels/42/threads/1003/20260326_message.md",
    "5. Re-run sync to resume import:",
    "   python scripts/sync_channel_artifacts.py --sync",
    "6. Check divergences directory — if empty, sync completed successfully"
  ],
  "manual_review_required": true,
  "escalation_contact": "LILITH (actor_id=2) — LIL001 non-interference protocol applies",
  "_details": {
    "reason": "File was deleted after journal entry was created"
  }
}
```

---

## Complete Example: HASH_MISMATCH

```json
{
  "kill_condition": "HASH_MISMATCH",
  "timestamp": "20260326155651",
  "actor_id": 102,
  "divergence_type": "HASH_MISMATCH",
  "severity": "CRITICAL",
  "file_path": "channels/42/threads/1003/edited_message.md",
  "content_id": 120260326155651000002000043,
  "expected_state": {
    "file_hash": "aaa111bbb222ccc333ddd444eee555fff666ggg777hhh888iii999jjj000kkk",
    "file_size": 2048,
    "file_exists": true,
    "content_id_unique": true
  },
  "actual_state": {
    "file_exists": true,
    "file_hash": "zzz999yyy888xxx777www666vvv555uuu444ttt333sss222rrr111qqq000ppp",
    "file_size": 2100,
    "content_id_exists_in_db": false
  },
  "resolution_steps": [
    "1. CRITICAL: File was modified AFTER journal entry was created",
    "2. This violates filesystem authority principle",
    "3. Current file hash:  zzz999...000ppp",
    "4. Expected hash:       aaa111...000kkk",
    "5. Options:",
    "   a) Restore original version (respects filesystem authority):",
    "      git checkout HEAD channels/42/threads/1003/edited_message.md",
    "   b) Regenerate as new journal entry (treats current file as new artifact):",
    "      python scripts/generate_content_id.py allocate channels/42/threads/1003/edited_message.md lupo_dialog_messages 102",
    "6. CHOOSE (a) or (b) above. Do not proceed without choosing one.",
    "7. After choice, re-run: python scripts/sync_channel_artifacts.py --sync"
  ],
  "manual_review_required": true,
  "escalation_contact": "LILITH (actor_id=2) — LIL001 non-interference protocol applies",
  "_details": {
    "expected_hash": "aaa111bbb222ccc333ddd444eee555fff666ggg777hhh888iii999jjj000kkk",
    "actual_hash": "zzz999yyy888xxx777www666vvv555uuu444ttt333sss222rrr111qqq000ppp",
    "reason": "HASH_MISMATCH"
  }
}
```

---

## Complete Example: DUPLICATE_CONTENT_ID

```json
{
  "kill_condition": "DUPLICATE_CONTENT_ID",
  "timestamp": "20260326155652",
  "actor_id": 0,
  "divergence_type": "DUPLICATE_CONTENT_ID",
  "severity": "CRITICAL",
  "file_path": "channels/42/threads/1005/new_message.md",
  "content_id": 120260321155652000001000050,
  "expected_state": {
    "file_hash": "fff111ggg222hhh333iii444jjj555kkk666lll777mmm888nnn999ooo000ppp",
    "file_size": 1024,
    "file_exists": true,
    "content_id_unique": true
  },
  "actual_state": {
    "file_exists": true,
    "file_hash": "fff111ggg222hhh333iii444jjj555kkk666lll777mmm888nnn999ooo000ppp",
    "file_size": 1024,
    "content_id_exists_in_db": true
  },
  "resolution_steps": [
    "1. CRITICAL: Content ID 120260321155652000001000050 already exists in database",
    "2. This indicates either:",
    "   a) Parallel imports created same content_id (thread-safety issue)",
    "   b) System clock was reset (timestamp component reused)",
    "   c) Sequence allocation overflowed and wrapped (rare, should be impossible)",
    "3. Check database for existing entry:",
    "   SELECT * FROM lupo_dialog_messages WHERE content_id = 120260321155652000001000050;",
    "4. Review existing entry. If it's:",
    "   - SAME FILE (identical hash): Delete old entry (duplicate), re-run sync",
    "     DELETE FROM lupo_dialog_messages WHERE content_id = 120260321155652000001000050 AND created_ymdhis < 20260326155652;",
    "   - DIFFERENT FILE (different hash): Regenerate new content_id with collision detection",
    "     python scripts/generate_content_id.py allocate channels/42/threads/1005/new_message.md",
    "5. Verify system clock is correct: date -u",
    "6. After resolution, re-run: python scripts/sync_channel_artifacts.py --sync"
  ],
  "manual_review_required": true,
  "escalation_contact": "LILITH (actor_id=2) — LIL001 non-interference protocol applies",
  "_details": {
    "reason": "DUPLICATE_CONTENT_ID",
    "suggested_investigation": "Check if parallel imports are running without coordination"
  }
}
```

---

## Divergence Handling Workflow

### 1. Detection Phase
```
Event consumers scan artifacts
    → Compute expected_state from journal
    → Find actual_state on filesystem/DB
    → Compare
    → If mismatch: Write divergence artifact
```

### 2. Storage Phase
```
Divergence artifacts written to: database/divergences/{timestamp}_{type}.json
(One artifact per divergence event)
```

### 3. Alert Phase
```
Consumer logs kill_condition and severity to stderr
Example: "[DIVERGENCE] KILL_CONDITION=HASH_MISMATCH SEVERITY=CRITICAL"
```

### 4. Manual Review Phase
```
Operator:
  1. ls database/divergences/
  2. cat database/divergences/{filename}.json | jq '.resolution_steps[]'
  3. Execute steps from resolution_steps array
  4. Verify conditions no longer diverge
  5. Remove artifact (optional: archive instead)
```

### 5. Retry Phase
```
python scripts/sync_channel_artifacts.py --sync
    → Rechecks all files
    → Finds no divergences (if operator fixed them)
    → Continues with import
```

---

## Auditing Divergences

### Find all divergences since date
```bash
find database/divergences -name "202603*" -type f
```

### Count by type
```bash
ls -1 database/divergences/ | cut -d_ -f2 | cut -d. -f1 | sort | uniq -c
```

### Find CRITICAL severity
```bash
for f in database/divergences/*.json; do
  severity=$(jq '.severity' "$f" | tr -d '"')
  if [ "$severity" = "CRITICAL" ]; then
    echo "CRITICAL: $f"
  fi
done
```

### Extract resolution steps
```bash
jq '.resolution_steps[]' database/divergences/20260326155651_HASH_MISMATCH.json
```

---

## See Also

- [DATABASE_IMPORT_OPERATIONAL_GUIDE.md](DATABASE_IMPORT_OPERATIONAL_GUIDE.md) — Operational workflow
- [ATHENA_DIRECTIVE_IMPLEMENTATION_STATUS.md](ATHENA_DIRECTIVE_IMPLEMENTATION_STATUS.md) — Implementation status
- [event_journal_consumer.py](../../scripts/event_journal_consumer.py) — Code that generates these artifacts
