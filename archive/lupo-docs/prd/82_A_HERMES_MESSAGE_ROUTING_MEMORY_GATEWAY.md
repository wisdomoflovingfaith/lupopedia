---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/82_A_HERMES_MESSAGE_ROUTING_MEMORY_GATEWAY.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/82_A_HERMES_MESSAGE_ROUTING_MEMORY_GATEWAY.md"
  status: active
  when_updated: "20260422232349"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/82_hermes_memory_gateway.toon
  atoms_toon: lupo-memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/hermes-memory-gateway
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_00_C_82_A
  title: "PRD 82 -- HERMES: Message Routing & Memory Gateway"
  summary: "Full specification for HERMES (actor_id 15) as both a message router and a Memory Gateway. Covers transcript_jsonl append protocol, staging memory toon extraction, promotion flagging for THOTH, Trust Ladder integration (PRD 43), Observer vs Active Actor doctrine integration (PRD 02), and HERMES API endpoints."
---
# PRD 82 -- HERMES: Message Routing & Memory Gateway

## Change History

- **2026-04-18 (final polish)**: ??3 / ??3.1 ??? **`task_assignee_id`** is the **authoritative** assignee field for **`HermesService::route()`**; routing uses **only** that parameter plus **`DialogMvpService::isTaskAssigneeAuthorized()`** when **`auth_user_id > 0`**; no parsing from message body; **`who:`** / free-form assignment deprecated. ??3.2 / ??4.1 / ??5 / ??8 ??? single canonical transcript path only: **`lupo-memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl`**. ??8 ??? **`route`** / **`createPendingTask`** match **`HermesService.php`**; normative **`appendTranscript($federation_node_id, $channel_key, $prd_cluster, $message_data)`** defines the transcript contract; timestamp rule uses packed UTC via **`timestamp_ymdhis::now()`**. Deduped non-participation wording in ??1 / ??9.
- **2026-04-18 (task routing, no free-form who)**: ??3, ??3.1, ??3.3 ??? operator tasks **MUST** supply assignee as an explicit parameter to **`HermesService::route()`** (`task_assignee_id`), not by parsing **`who:`** / **`what:`** from message text. Body is **`[task] {description}`** only. Department + channel authorization uses **`DialogMvpService::isTaskAssigneeAuthorized()`** (same rules as channels UI). Aligns with `channels/index.php` + `app/Services/HermesService.php`.
- **2026-04-18 (transcript path)**: ??3.2 and ??4.1 ??? transcript JSONL is **only** under **`lupo-memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl`**; removed `lupo-channels/{channel_id}/transcript.jsonl` mirror. **`appendTranscript`** MUST use this canonical path (??3.3 table + ??8 pattern aligned).
- **2026-04-18**: ??3 agent resolution: DB match plus optional **`registry.json` `agents`** fallback; ??3.3 namespace note for `\timestamp_ymdhis` / `\IdGenerator` in `App\Services\HermesService`. Aligns with reference implementation and CLI routing test.
- **2026-04-16 (v1)**: Initial creation. Elevates HERMES from HERMES_DOCTRINE.md routing-only spec to full Memory Gateway specification. Covers transcript JSONL protocol, staging toon extraction, promotion flagging, Trust Ladder integration, Observer doctrine integration, and API surface. Actor: Claude Code (actor_id 116).

---

## Table of Contents

1. [What HERMES Is](#1-what-hermes-is)
2. [HERMES Identity](#2-hermes-identity)
3. [Message Routing (Canonical)](#3-message-routing-canonical)
4. [Memory Gateway Role](#4-memory-gateway-role)
   - 4.1 [Transcript JSONL Protocol](#41-transcript-jsonl-protocol)
   - 4.2 [Staging Memory Toon Protocol](#42-staging-memory-toon-protocol)
   - 4.3 [Promotion Flagging for THOTH](#43-promotion-flagging-for-thoth)
5. [Trust Ladder Integration](#5-trust-ladder-integration)
6. [Observer vs Active Actor Integration](#6-observer-vs-active-actor-integration)
7. [API Endpoints](#7-api-endpoints)
8. [PHP Implementation Pattern](#8-php-implementation-pattern)
9. [What HERMES Is Not](#9-what-hermes-is-not)
10. [Cross-References](#10-cross-references)

---

## 1. What HERMES Is

HERMES (**H**euristic **E**vent **R**outing & **M**essaging **E**xchange **S**ystem, actor_id 15) is a two-role system component:

**Role 1 -- Message Router:** Intercepts messages that match specific patterns and routes them to the correct destination (task queue, monitoring agents, chat stream, system log). HERMES does not make builder agents "read" the chat. It translates chat-side syntax into task-queue entries so builder agents receive instructions through their proper input channel.

**Role 2 -- Memory Gateway:** Every message that passes through HERMES becomes a permanent record in two memory layers:
  - **Transcript layer:** a flat JSONL file at the **canonical transcript path** (??3.2, ??4.1), appended once per routed message.
  - **Staging memory layer:** structured pattern records in staging memory toons (Tier 3 in the Trust Ladder, PRD 43) that THOTH may promote to canonical.

HERMES does not participate in conversations, does not reason, and does not hold dialogue on its own initiative. HERMES classifies, routes, and records.

---

## 2. HERMES Identity

| Field | Value |
|---|---|
| `actor_id` | 15 |
| Full name | Heuristic Event Routing & Messaging Exchange System |
| Agent key | `hermes` |
| Trust tier (as writer) | `staging` (HERMES writes to staging only; THOTH promotes to canonical) |
| Observer doctrine class | System component -- not a chat actor; does not hold a tab |
| Primary channel | `development` (default; operates on any active channel) |
| MVP script | `lupo-scripts/draft_hermes_prompt_from_artifact.py` |
| Full-auto phase | Deferred to Phase 3 |

---

## 3. Message Routing (Canonical)

This section is the normative routing table. See `lupo-docs/doctrine/HERMES_DOCTRINE.md` for narrative detail.

**Authoritative task assignment (normative):** **`task_assignee_id`** is the **only** assignee input to **`HermesService::route()`** for **`[task]`** rows. Routing does **not** parse assignee identity from the message body. Deprecated patterns such as **`who:`** / free-form name assignment are **not** used for normative UI or API traffic; routing depends **only** on **`task_assignee_id`** plus the authorization check in **`DialogMvpService::isTaskAssigneeAuthorized()`** when **`auth_user_id > 0`**.

| Message Pattern | Source | Destination | Chat Visibility | Memory Gateway Action |
|---|---|---|---|---|
| `[task] {description}` + explicit **`task_assignee_id`** (UI/POST) | Human operator | Task queue of assignee actor | Yes (routing confirmation) | Append JSONL; extract `task_assignment` pattern |
| `[task] {description}` without valid assignee id or failing department/channel check | Human operator | HERMES error (no pending task) | Yes (routing failure note) | Append JSONL with `routing_provenance: hermes:error` |
| `[alert] ...` | Any agent | Chat stream (all humans + monitoring agents) | Yes | Append JSONL; extract `alert` pattern |
| `[decision] ...` | Any actor | Chat stream + memory | Yes | Append JSONL; extract `decision` pattern |
| `[question] ...` | Any actor | Chat stream + open questions | Yes | Append JSONL; extract `question` pattern |
| `stdout` from builder | Builder agent | Chat (human monitoring) + agent log | Yes | Append JSONL |
| `stderr` from builder | Builder agent | Chat + log + alert if severity >= ERROR | Yes | Append JSONL; extract `alert` if critical |
| Directed message | Human operator | Chat stream only | Yes | Append JSONL |
| Cross-channel send | Human operator | Target channel thread | Yes (in source channel) | Append JSONL; extract `cross_channel_route` pattern |
| System event | HERMES itself | Chat stream + system log | Yes | Append JSONL with `from_actor_id: 15` |

**HERMES routing resolution order:**
1. Pattern match against `[task]` prefix; **assignee is never parsed from the message body** for normative UI or API traffic. Callers pass **`task_assignee_id`** into **`route()`**; when **`auth_user_id > 0`**, **`DialogMvpService::isTaskAssigneeAuthorized()`** enforces department + channel scope (admin bypass optional). If **`task_assignee_id <= 0`**, routing is **`hermes:error`** (no pending task).
2. Pattern match against `[alert]`, `[decision]`, `[question]` prefixes.
3. Message type classification (`stdout`, `stderr`, `system`).
4. Default: directed message to chat stream.

**Deprecated (do not use in new UI):** Free-form **`[task] who: X what: Y`** ??? humans must not type assignee names; the channels composer uses **`task_to_actor_id`** (dropdown of authorized actors) plus a description field. **`resolveActorKey()`** remains in **`HermesService`** for non-chat tooling if needed; it is **not** part of the **`[task]`** branch in **`route()`**.

### 3.1 Routing patterns (implementation matrix)

HERMES matches incoming message bodies against these patterns (first match wins within the same priority band; see ??3 resolution order above). For **`[task]`**, the **routing decision** for assignee uses **`task_assignee_id`** from the **`route()`** parameter list only; the body carries **`[task] {description}`** only.

| Pattern | Action | Destination |
|---------|--------|-------------|
| `[task] {description}` + authoritative **`task_assignee_id`** (parameter) | Create pending task | `lupo_dialog_pending_tasks` (assignee = **`task_assignee_id`** after **`isTaskAssigneeAuthorized()`** when **`auth_user_id > 0`**) |
| `[alert]` | Post alert | Chat stream (all humans + monitoring agents) |
| `[decision]` | Log decision | `lupo_routing_events` when that table exists in the install; otherwise routing decision only (no DB row) until schema is present |

#### 3.3 Additional Orchestration Tables (4.1.2+)

**Note:** The following tables are defined in JSON mirror and migration files but may not be in the main installer. Status uncertain - could be deprecated, superseded, or optional add-ons.

- **`lupo_routing_events`** - Logs routing decisions made by HERMES (see row above)
  - Primary key: routing_event_id (following RULE 93.PK_NAMING)
- **`lupo_agent_status`** - Tracks agent status (ACTIVE, IDLE, SLEEPING, THROTTLED, FAILED, UNKNOWN, MANUAL) with heartbeat timestamps
  - Primary key: agent_status_id (following RULE 93.PK_NAMING)
- **`lupo_operator_scratchpad`** - Temporary scratchpad content for operators with promotion flags
  - Primary key: scratchpad_id (following RULE 93.PK_NAMING)
- **`lupo_sticky_notes`** - Digital sticky notes for channels with color coding and pinning
  - Primary key: sticky_note_id (following RULE 93.PK_NAMING)

**Source:** `lupo-database/lupopedia/mysql/migrations/4_1_2_orchestration_tables.sql`

| `[question]` or `OQ-\d+` | Log open question | `open_questions.md` under the active thread directory when filesystem layout provides it; otherwise transcript + staging pattern only |
| `[stderr]` | Forward as error | Chat + operator log |
| `[stdout]` | Forward as output | Chat + operator log |
| `send to {channel}` | Cross-channel route | Destination channel thread (see cross-channel row in the ??3 table) |

### 3.2 Transcript storage path (canonical only)

HERMES **`appendTranscript`** writes JSONL with **no database I/O** to exactly **one** filesystem path. There is **no** alternate transcript root, **no** `lupo-channels/{channel_id}/transcript.jsonl` storage for this protocol, and **no** transcript SQL table.

**Canonical transcript file (authoritative path, single source of truth):**

`lupo-memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl`

See ??4.1 for how the header **`transcript_jsonl`** slug maps to this path. Each line is one JSON object; UTF-8; LF; append with **`FILE_APPEND | LOCK_EX`**. **`{federation_node_id}`**, **`{channel_key}`**, and **`{prd_cluster}`** are the only path segments; do not substitute **`channel_id`** integers in this pathname.

### 3.3 HermesService contract (PHP)

Minimum methods on **`app/Services/HermesService.php`**:

| Method | Responsibility |
|--------|------------------|
| **`route($message, $from_actor_id, $to_actor_id, $channel_id, $task_assignee_id = 0, $auth_user_id = 0, $task_scope_admin_bypass = false)`** | Parse prefixes (`[task]`, `[alert]`, ???). For **`[task]`**, use **`$task_assignee_id`** as assignee (no body parsing); validate with **`DialogMvpService::isTaskAssigneeAuthorized`** when **`$auth_user_id > 0`**. Return a routing decision array (`action`, `task_target_actor_id`, `routing_provenance`, `message_type`, `destination`, `raw_message`, `ok`, `task_summary`). |
| **`appendTranscript($federation_node_id, $channel_key, $prd_cluster, $message_data)`** | Write one JSONL line to **`lupo-memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl`**; `FILE_APPEND \| LOCK_EX`; create directories; **no** SQL. |
| **`createPendingTask($routing_decision, $message_id)`** | Insert into **`lupo_dialog_pending_tasks`** using **`IdGenerator::generate()`** for **`task_id`**, **`timestamp_ymdhis::now()`** for **`created_ymdhis` / `updated_ymdhis`**, **`PDO_DB::insert`**. Executes only when **`action === 'task'`**, **`task_target_actor_id > 0`**, and **`routing_provenance !== 'hermes:error'`**; may synthesize **`message_id`** via **`IdGenerator`** when the argument is **`<= 0`** (see implementation). |

**Concurrency:** Transcript writes **MUST** survive concurrent writers (exclusive file lock on append).

**PHP namespace:** `HermesService` is declared under **`App\Services`**. Any reference to global classes **`timestamp_ymdhis`** or **`IdGenerator`** **MUST** use a leading backslash (for example **`\timestamp_ymdhis::now()`**, **`\IdGenerator::generate()`**) so PHP does not resolve them under `App\Services\???` (which can trigger autoload stalls).

---

## 4. Memory Gateway Role

### 4.1 Transcript JSONL Protocol

The `transcript_jsonl` field in every channel artifact header is a lookup slug:

```
{federation_node_id}/{channel_key}/{prd_cluster}
```

HERMES resolves this slug to the canonical transcript file path:

```
lupo-memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl
```

This is the **single source of truth** for the transcript. No mirror path exists. No database table. Just the file.

**HERMES `appendTranscript` MUST write to this canonical path.**

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
- Transcript JSONL files are file-based artifacts, NOT database tables. Do not create a `lupo_transcripts` table.
- HERMES is the sole writer. No other actor or process may append to a `transcript_jsonl` file.
- Each line must be valid JSON. One object per line. No array wrapper. No trailing comma.
- File encoding: UTF-8. Line endings: LF only.
- File must be created if it does not exist. Directory must be created if it does not exist.

### 4.2 Staging Memory Toon Protocol

After writing the JSONL record, HERMES evaluates the message for pattern extraction. Patterns are written to staging memory toons (Trust Ladder Tier 3).

**Staging toon path:**

```
lupo-memory/{channel_key}/staging/{YYYY}/{MM}/{prd_cluster}.toon
```

Where `{YYYY}` is the calendar year (e.g. `2026`). Note: staging uses calendar year, not the 1000-offset canonical form.

Example: `lupo-memory/development/staging/2026/04/hermes-memory-gateway.toon`

**Extractable pattern types:**

| Pattern Type | Trigger Condition | Required Fields |
|---|---|---|
| `task_assignment` | `message_type: task` and routing succeeded | `from_actor_id`, `to_actor_id`, `summary` (first 120 chars of `message_text`) |
| `decision` | `[decision]` prefix or THOTH acknowledgement line | `from_actor_id`, `summary` |
| `question` | `[question]` prefix or OQ reference (`OQ-NNN`) | `from_actor_id`, `summary` |
| `alert` | `[alert]` prefix or `message_type: alert` | `from_actor_id`, `to_actor_id`, `summary` |
| `cross_channel_route` | Message sent to a non-primary channel via cross-channel send | `from_channel_key`, `to_channel_key`, `from_actor_id`, `summary` |

**Staging toon canonical structure:**

```json
{
  "type": "staging_memory",
  "channel_key": "development",
  "prd_cluster": "hermes-memory-gateway",
  "trust_tier": "staging",
  "when_updated": 20260416143316,
  "source_actor_id": 15,
  "patterns": [
    {
      "pattern_type": "task_assignment",
      "ts": 20260416143316,
      "from_actor_id": 1,
      "to_actor_id": 116,
      "summary": "Update PRD 50 section 5.3 with chat visibility table",
      "occurrence_count": 1,
      "promotion_candidate": false
    }
  ]
}
```

**Toon update rules:**
- If the staging toon file already exists, HERMES reads it, appends the new pattern entry, increments `occurrence_count` for duplicate patterns, and rewrites the file.
- Two patterns are considered duplicates if they share the same `pattern_type`, `from_actor_id`, `to_actor_id`, and normalized `summary` (lowercase, whitespace-collapsed, first 80 chars).
- HERMES sets `when_updated` to the current UTC on every toon write.

### 4.3 Promotion Flagging for THOTH

When a pattern's `occurrence_count` reaches the promotion threshold (default: 3), HERMES sets `promotion_candidate: true` on that pattern record in the staging toon.

**Promotion threshold configuration:**

The threshold is defined as a `DEFINE` constant in the HERMES configuration:

```php
define('HERMES_PROMOTION_THRESHOLD', 3);
```

This value may be overridden per channel via `lupo_channel_config` (future ??? deferred to Phase 3). In Phase 1 (MVP), the global constant applies to all channels.

**THOTH promotion workflow:**

Upon detecting `promotion_candidate: true` in a staging toon, THOTH (actor_id 26):

1. Reads the staging pattern and all 1026 (canonical) nodes for the channel.
2. Checks for contradictions against canonical nodes using the Anchored Truth Doctrine (PRD 02 ??"Anchored Truth Doctrine: The Sieve and THOTH [ALERT] Protocol").
3. **If no contradiction:** Promotes via KAIROS / `MemoryPromotionService`:
   - Writes canonical toon to `lupo-memory/{channel_key}/canonical/1026/{MM}/{prd_cluster}.toon`
   - Adds idempotent edge `promoted_to` from staging pattern to canonical node.
   - Sets `promotion_candidate: false` and adds `promoted_at: {ts}` on the staging record.
4. **If contradiction:** Raises `THOTH [ALERT]` to chat stream. Promotion is blocked until resolved.

**Idempotency rule:** If a pattern has already been promoted (edge `promoted_to` exists), THOTH skips it silently. HERMES must not re-flag already-promoted patterns.

---

## 5. Trust Ladder Integration

HERMES operates at the boundary between the staging tier and the canonical tier of the Trust Ladder (PRD 43).

| Tier | Path Pattern | Year Offset | HERMES Role |
|---|---|---|---|
| Staging (Tier 3) | `lupo-memory/{channel_key}/staging/{YYYY}/{MM}/{slug}.toon` | Calendar year (`2026`) | HERMES **writes here** |
| Canonical (Tier 2) | `lupo-memory/{channel_key}/canonical/1026/{MM}/{slug}.toon` | `calendar_year - 1000` (`1026`) | HERMES **never writes here** |
| Transcript (file artifact) | `lupo-memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl` | No year offset (file-based) | HERMES **appends here** |

**Constitutional rules from PRD 43:**
- Canonical IDs encode `calendar_year - 1000` in the first 4 digits (2026 -> 1026). Staging uses calendar year (2026).
- THOTH is the sole promoter from staging to canonical. No other actor may write to the 1026 path.
- A Captain's Amendment (WOLFIE approval) is required to modify a canonical node after promotion.
- KAIROS / `MemoryPromotionService` handles the physical promotion with idempotent edge types: `promoted_to`, `consolidated_into`, `merged_into`.

---

## 6. Observer vs Active Actor Integration

HERMES operates in the background of the channel UI. It is not a tab actor and holds no position in the tab bar.

**HERMES and the Observer Doctrine (PRD 02 ??"Observer vs Active Actor Tab Doctrine"):**

| Rule | HERMES Behavior |
|---|---|
| Observer Actors (LILITH, ROSE, THOTH) have omniscient visibility | HERMES routes `[alert]` and `[decision]` messages to all monitoring agents, consistent with observer omniscience |
| Active Actors (CURSOR, AUGGIE, GEMINI, CASCADE) have actor-scoped visibility | HERMES does not route general chat to active builder agents; it routes only their task queue entries |
| Agent Write-Only Rule | HERMES intercepts at the protocol layer; it does not use chat history as a routing signal source |
| Active Output Rule | HERMES does not affect the Active Output Rule (last-message color inheritance). HERMES records are internal; they do not post visible messages to the chat feed |

**Interaction with THOTH:**
THOTH (Observer Actor, actor_id 26) is the consumer of HERMES promotion flags. THOTH reads staging toons independently of the chat stream. The HERMES ??? THOTH promotion pipeline does not cross the chat stream; it is a memory-layer operation.

---

## 7. API Endpoints

All HERMES API endpoints are internal system endpoints. They are not exposed to the public web. Authentication requires a valid session with `is_admin` or `actor_id: 15` system authority (PRD 32).

### POST /api/hermes/route

Routes a message through HERMES. Creates the `lupo_tasks` row if the message is a `[task]`, appends the JSONL transcript record, and updates the staging toon.

**Request body:**

```json
{
  "from_actor_id": 1,
  "channel_key": "development",
  "prd_cluster": "hermes-memory-gateway",
  "message_text": "[task] update PRD 50",
  "task_assignee_actor_id": 116,
  "message_type": "task"
}
```

**Response:**

```json
{
  "status": "ok",
  "routing_provenance": "hermes:task-router",
  "to_actor_id": 116,
  "task_id": 10000000000000042,
  "transcript_appended": true,
  "staging_toon_updated": true
}
```

**Error response (routing failure):**

```json
{
  "status": "error",
  "routing_provenance": "hermes:error",
  "message": "Agent key 'UNKNOWN' not found in lupo_actors",
  "transcript_appended": true
}
```

Note: even on routing failure, the JSONL record is appended with `routing_provenance: hermes:error`. The transcript is always written.

### GET /api/hermes/transcript/{channel_key}/{prd_cluster}

Returns the last N lines of the transcript JSONL for the given thread. Default N = 100.

**Query parameters:**

| Parameter | Type | Default | Description |
|---|---|---|---|
| `limit` | `INT` | 100 | Number of lines to return (most recent first) |
| `from_ts` | `BIGINT` | 0 | Return only records with `ts >= from_ts` |
| `message_type` | `string` | (all) | Filter by message type |

**Response:** JSON array of JSONL records, ordered newest-first.

### GET /api/hermes/staging/{channel_key}/{prd_cluster}

Returns the current staging memory toon for the given thread.

**Response:** The toon JSON object as-is.

### POST /api/hermes/promote/{channel_key}/{prd_cluster}

THOTH-only endpoint. Triggers promotion evaluation for all `promotion_candidate: true` patterns in the named staging toon.

**Authorization:** Requires `actor_id: 26` (THOTH) or `is_admin: true`.

**Response:**

```json
{
  "status": "ok",
  "promoted": 2,
  "blocked": 0,
  "alerts_raised": 0
}
```

---

## 8. PHP Implementation Pattern

HERMES is implemented as **`App\Services\HermesService`** in **`app/Services/HermesService.php`**. It does not depend on any external library. It uses **`PDO_DB`** for **`lupo_dialog_pending_tasks`** inserts and plain PHP file I/O for transcript JSONL at the **canonical path** in ??3.2 / ??4.1 **only**. Staging toon updates are specified in ??4.2; physical writes may live in **`HermesService`** or adjacent services, but transcript storage remains file-only and path-authoritative as above.

**Normative public methods (aligned with ??3.3):**

```php
namespace App\Services;

class HermesService {

    /**
     * Routing decision only (no transcript write inside this method).
     * task_assignee_id: authoritative assignee for [task]; no body parsing.
     * Authorization: DialogMvpService::isTaskAssigneeAuthorized($db, $channel_id, $auth_user_id, $task_assignee_id, $task_scope_admin_bypass) when $auth_user_id > 0.
     */
    public function route(
        $message,
        $from_actor_id,
        $to_actor_id,
        $channel_id,
        $task_assignee_id = 0,
        $auth_user_id = 0,
        $task_scope_admin_bypass = false
    ) { ... }

    /**
     * Append one JSONL line to the canonical transcript file only:
     * lupo-memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl
     * No SQL. Creates directories as needed. FILE_APPEND | LOCK_EX.
     */
    public function appendTranscript(
        $federation_node_id,
        $channel_key,
        $prd_cluster,
        array $message_data
    ) { ... }

    /**
     * Insert pending task when routing decision action is task and routing is not hermes:error.
     * task_id: IdGenerator::generate(); timestamps: timestamp_ymdhis::now() (string or int per table DDL).
     */
    public function createPendingTask(array $routing_decision, $message_id) { ... }
}
```

**Private helpers (reference implementation):** **`resolveActorKey()`**, **`resolveActorFromAgentsRegistry()`**, **`loadAgentsRegistryActorMap()`** ??? optional **`lupo-database/.../actors/actor_id/registry.json`** **`agents`** map when **`lupo_actors`** has no match (not used for **`[task]`** assignee; **`task_assignee_id`** remains authoritative for that branch).

**Timestamp rule:** Use **`timestamp_ymdhis::now()`** (packed UTC) for routing decision timestamps and task row timestamps per constitutional BIGINT UTC doctrine. Do not use Unix epoch for stored fields.

**File I/O rules:**
- **`appendTranscript`:** `file_put_contents($path, $line . "\n", FILE_APPEND | LOCK_EX)` where **`$path`** resolves **only** to **`lupo-memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl`**.
- Staging toon writes (??4.2): `json_encode($toon, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)` as specified there.
- Create directories with **`mkdir($dir, 0755, true)`** when missing.
- Never store transcript lines in SQL. **`INSERT`** applies to **`lupo_dialog_pending_tasks`** only, not JSONL.

---

## 9. What HERMES Is Not

- **Not a conversation partner.** Operational detail is stated in ??1; HERMES does not post chat traffic on its own initiative except routing confirmation notes and **`hermes:error`** notifications where specified.
- **Not a general-purpose relay.** HERMES only intercepts messages matching the defined pattern table in ??3.
- **Not a broadcast channel.** HERMES does not forward arbitrary messages to all agents.
- **Not a substitute for direct task queue writes.** Direct `POST /api/task/assign` bypasses HERMES; this is valid and does not break the system. However, messages sent this way are NOT appended to the HERMES transcript.
- **Not ANUBIS.** ANUBIS (PRD 07) handles file intake and orphan resolution. HERMES handles message routing and memory recording. They operate in separate domains.
- **Not HEPHAESTUS.** HEPHAESTUS and other builder agents execute implementation work. HERMES routes work to them; it does not implement anything.
- **Not a canonical writer.** HERMES writes to staging tier only. Canonical tier is THOTH + KAIROS domain.

---

## 10. Cross-References

- **[PRD 02 ??HERMES Routing Rules](lupo-docs/prd/02_channels_discussions.md)** -- Compact routing table; HERMES Memory & Transcript Integration section
- **[PRD 02 ??Observer vs Active Actor Tab Doctrine](lupo-docs/prd/02_channels_discussions.md)** -- Observer/Active class definitions HERMES respects
- **[PRD 43 -- Trust Ladder](lupo-docs/prd/43_parent_child_trust_ladder.md)** -- Staging vs canonical tier definitions; year offset rule; KAIROS promotion
- **[PRD 10 -- lupo_tasks schema](lupo-docs/prd/10_task_queue_schema.md)** -- Task queue table HERMES writes to for `[task]` messages
- **[PRD 32 -- Actor authority](lupo-docs/prd/32_actor_authority.md)** -- HERMES acts under system authority, not operator authority
- **[PRD 07 -- ANUBIS](lupo-docs/prd/07_anubis.md)** -- ANUBIS routing role (distinct; ANUBIS handles file intake, HERMES handles message routing)
- **[HERMES_DOCTRINE.md](lupo-docs/doctrine/HERMES_DOCTRINE.md)** -- Narrative routing doctrine; task queue as sole builder agent input; visibility doctrine
- **[PRD 50 ??5.3](lupo-docs/prd/50_agent_coordination_protocol.md)** -- Coordination protocol view of chat visibility

This PRD is the canonical specification for HERMES as a system component. HERMES_DOCTRINE.md remains the narrative routing reference; PRD 82 is the normative implementation specification.
