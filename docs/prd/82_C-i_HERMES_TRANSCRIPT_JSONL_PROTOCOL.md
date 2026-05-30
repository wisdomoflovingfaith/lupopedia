---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/82_C-i_HERMES_TRANSCRIPT_JSONL_PROTOCOL.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/82_C-i_HERMES_TRANSCRIPT_JSONL_PROTOCOL.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/82_hermes_transcript_protocol.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/hermes-transcript-protocol
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_16_B-i_16_C-i_82_A-i_82_C-i
  title: 'PRD 82_C: HERMES Transcript JSONL Protocol'
  summary: Canonical specification for HERMES transcript JSONL protocol, file storage paths, record structure, and API read behavior.
---
# PRD 82_C: HERMES Transcript JSONL Protocol

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## Purpose

This PRD defines the canonical transcript JSONL protocol for HERMES, including file storage paths, record structure, append rules, and API read behavior. The transcript is the single source of truth for all HERMES-routed messages.

---

## Table of Contents

1. [Transcript Storage Path (Canonical Only)](#1-transcript-storage-path-canonical-only)
2. [transcript_jsonl Header Field Resolution](#2-transcript_jsonl-header-field-resolution)
3. [Canonical JSONL Record Structure](#3-canonical-jsonl-record-structure)
4. [appendTranscript Rules](#4-appendtranscript-rules)
5. [File-Only Storage (No Database)](#5-file-only-storage-no-database)
6. [Concurrent Writer Safety](#6-concurrent-writer-safety)
7. [Transcript API Read Behavior](#7-transcript-api-read-behavior)
8. [Implementation Contract](#8-implementation-contract)

---

## 1. Transcript Storage Path (Canonical Only)

HERMES `appendTranscript` writes JSONL with **no database I/O** to exactly **one** filesystem path. There is **no** alternate transcript root, **no** `channels/{channel_id}/transcript.jsonl` storage for this protocol, and **no** transcript SQL table.

**Canonical transcript file (authoritative path, single source of truth):**

```
memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl
```

**Path Components:**
- `{federation_node_id}`: Federation node identifier (integer, usually 0 for local)
- `{channel_key}`: Channel key (e.g., "development", "testing")
- `{prd_cluster}`: PRD cluster identifier (e.g., "hermes-memory-gateway")

**Critical Rules:**
- This is the **single source of truth** for the transcript
- No mirror path exists
- No database table
- Just the file
- `{federation_node_id}`, `{channel_key}`, and `{prd_cluster}` are the only path segments
- Do NOT substitute `channel_id` integers in this pathname

**HERMES `appendTranscript` MUST write to this canonical path.**

---

## 2. transcript_jsonl Header Field Resolution

The `transcript_jsonl` field in every channel artifact header is a lookup slug:

```
{federation_node_id}/{channel_key}/{prd_cluster}
```

HERMES resolves this slug to the canonical transcript file path:

```
memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl
```

**Resolution Example:**
- Header `transcript_jsonl`: "0/development/hermes-memory-gateway"
- Resolves to: `memory/transcripts/0/development/hermes-memory-gateway.jsonl`

---

## 3. Canonical JSONL Record Structure

HERMES appends one JSON object per line for every routed message. The file grows indefinitely within a thread; it is never truncated by HERMES.

**Canonical JSONL record structure:**

```json
{"ts":20260416143316,"from_actor_id":1,"to_actor_id":116,"message_text":"[task] update PRD 50 section 5.3","message_type":"task","routing_provenance":"hermes:task-router"}
```

**Field definitions:**

| Field | Type | Rule |
|---|---|---|
| `ts` | `BIGINT` UTC `YYYYMMDDHHIISS` | Set from `tick.py`; never guessed; never epoch |
| `from_actor_id` | `INT` | Resolved from session/auth server-side; never from client input |
| `to_actor_id` | `INT` | Resolved by HERMES routing; 0 = broadcast/chat-stream-only |
| `message_text` | `string` | Full message body verbatim |
| `message_type` | `string enum` | `task`, `alert`, `decision`, `question`, `stdout`, `stderr`, `directed`, `system` |
| `routing_provenance` | `string` | HERMES routing rule matched: `hermes:task-router`, `hermes:alert`, `hermes:decision`, `hermes:question`, `hermes:monitor-relay`, `hermes:stdout`, `hermes:stderr`, `hermes:directed`, `hermes:system`, `hermes:error` |

**Constitutional constraints:**
- Each line must be valid JSON
- One object per line
- No array wrapper
- No trailing comma
- File encoding: UTF-8
- Line endings: LF only

---

## 4. appendTranscript Rules

### Method Signature

```php
public function appendTranscript(
    $federation_node_id,
    $channel_key,
    $prd_cluster,
    array $message_data
) { ... }
```

### Required Behavior

**File Creation:**
- Create directories if they do not exist: `mkdir($dir, 0755, true)`
- Create file if it does not exist
- Always append, never overwrite

**Write Operation:**
```php
$line = json_encode($message_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
file_put_contents($path, $line . "\n", FILE_APPEND | LOCK_EX);
```

**Locking:**
- MUST use `LOCK_EX` for exclusive file lock
- Ensures concurrent writer safety
- Prevents corrupted writes

**Path Validation:**
- MUST validate path resolves only to canonical transcript directory
- MUST prevent path traversal attacks
- MUST ensure `{federation_node_id}`, `{channel_key}`, `{prd_cluster}` are safe

**Error Handling:**
- Log write failures but do not fail routing
- Continue with message processing even if transcript write fails
- Record failure in routing decision if needed

---

## 5. File-Only Storage (No Database)

**Constitutional constraints:**
- Transcript JSONL files are file-based artifacts, NOT database tables
- Do NOT create a `lupo_transcripts` table
- HERMES is the sole writer
- No other actor or process may append to a `transcript_jsonl` file

**Rationale:**
- File-based storage provides immutable append-only semantics
- No database schema migrations required
- Simple backup and archival
- Direct file access for tools and scripts
- No SQL injection risk

**Performance Considerations:**
- Append-only writes are fast
- File locks prevent corruption
- Large files can be streamed for reading
- File system caching provides good performance

---

## 6. Concurrent Writer Safety

**Locking Strategy:**
- Use `FILE_APPEND | LOCK_EX` flags
- `LOCK_EX` provides exclusive lock during write
- Lock is automatically released after write

**Concurrency Scenarios:**
1. **Single Writer**: Normal case - HERMES is the only writer
2. **Multiple HERMES Instances**: Lock ensures serialized writes
3. **Reader During Write**: Readers can read existing content, new line appears atomically

**Race Condition Prevention:**
- File append operation is atomic on most filesystems
- Lock ensures entire line is written without interruption
- No partial lines or corrupted JSON

**Performance Impact:**
- Lock overhead is minimal for append-only operations
- Contention only occurs with extremely high concurrency
- Lock is held for microseconds per write

---

## 7. Transcript API Read Behavior

### GET /api/hermes/transcript/{channel_key}/{prd_cluster}

Returns the last N lines of the transcript JSONL for the given thread. Default N = 100.

**Query parameters:**

| Parameter | Type | Default | Description |
|---|---|---|---|
| `limit` | `INT` | 100 | Number of lines to return (most recent first) |
| `from_ts` | `BIGINT` | 0 | Return only records with `ts >= from_ts` |
| `message_type` | `string` | (all) | Filter by message type |

**Response:** JSON array of JSONL records, ordered newest-first.

**Implementation Notes:**
- Read file from end for efficiency with large files
- Apply filters in memory for simplicity
- Return empty array if file doesn't exist
- Validate channel_key and prd_cluster parameters

**Example Response:**
```json
[
  {"ts":20260416143316,"from_actor_id":1,"to_actor_id":116,"message_text":"[task] update PRD 50","message_type":"task","routing_provenance":"hermes:task-router"},
  {"ts":20260416143200,"from_actor_id":116,"to_actor_id":0,"message_text":"Task completed","message_type":"directed","routing_provenance":"hermes:directed"}
]
```

### GET /api/hermes/transcript/{channel_key}/{prd_cluster}/raw

Returns the raw transcript file content for download or streaming.

**Response:** Raw JSONL file content with proper `Content-Type: application/x-ndjson` header.

---

## 8. Implementation Contract

### PHP Implementation Requirements

**Namespace:**
```php
namespace App\Services;
```

**Method Implementation:**
```php
/**
 * Append one JSONL line to the canonical transcript file only:
 * memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl
 * No SQL. Creates directories as needed. FILE_APPEND | LOCK_EX.
 */
public function appendTranscript(
    $federation_node_id,
    $channel_key,
    $prd_cluster,
    array $message_data
) {
    // Build canonical path
    $path = "memory/transcripts/{$federation_node_id}/{$channel_key}/{$prd_cluster}.jsonl";
    
    // Create directory if needed
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Encode and append
    $line = json_encode($message_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $result = file_put_contents($path, $line . "\n", FILE_APPEND | LOCK_EX);
    
    return $result !== false;
}
```

**Validation Requirements:**
- MUST validate path is within allowed transcript directory
- MUST prevent directory traversal: `../`, `..\`, etc.
- MUST validate parameters are non-empty strings
- MUST ensure `message_data` contains required fields

**Error Logging:**
- Log write failures with full context
- Include file path, error message, and message_data
- Do not expose sensitive data in logs

**Performance Requirements:**
- Method must complete within 100ms for normal operation
- Must handle files up to 100MB without performance degradation
- Memory usage must remain constant regardless of file size

### File System Requirements

**Directory Structure:**
```
memory/
└── transcripts/
    ├── {federation_node_id}/
    │   └── {channel_key}/
    │       └── {prd_cluster}.jsonl
    └── ...
```

**Permissions:**
- Directory: 0755 (rwxr-xr-x)
- File: 0644 (rw-r--r--)
- Web server must have write access to transcript directory

**Backup Considerations:**
- Transcript files should be included in regular backups
- Consider log rotation for very active channels
- Archive old transcripts to separate storage

---

## Cross-References

- **[PRD 82_A-i](docs/prd/82_A-i_HERMES_MESSAGE_ROUTING_MEMORY_GATEWAY.md)** -- HERMES overview and system integration
- **[PRD 82_B-i](docs/prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md)** -- Routing header and semantic fields
- **[PRD 82_D-i](docs/prd/82_D-i_HERMES_TASK_ROUTING_AND_PENDING_TASKS.md)** -- Task routing and pending tasks
- **[PRD 82_E-i](docs/prd/82_E-i_HERMES_IMPLEMENTATION_CONTRACT.md)** -- PHP implementation contract
- **[PRD 43](docs/prd/43_A-i_PARENT_CHILD_TRUST_LADDER.md)** -- Trust Ladder and memory tiers
- **[PRD 16_C-i](docs/prd/16_C-i_LUPOPEDIA_HEADERS.md)** -- Header field definitions

---

**Constitutional Rules:**
- HERMES is the sole writer to transcript files
- No database storage for transcripts
- Canonical path only - no mirrors or alternates
- Append-only - never modify existing lines
- File must be created if it does not exist
- UTF-8 encoding with LF line endings only
- One JSON object per line - no arrays, no trailing commas
