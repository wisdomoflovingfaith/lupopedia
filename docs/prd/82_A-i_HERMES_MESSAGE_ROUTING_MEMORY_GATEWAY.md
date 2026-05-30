## 8. lupopedia.hermes Routing Header (Constitutional)

### KAPAKAI vs PONO (Mandatory Distinction)

**All HERMES-routed messages MUST include two explicit fields in the `lupopedia.hermes` block:**

- **kapakai**: Diagnostic/problem field. Describes what is wrong, missing, or requires attention. Always states the issue or challenge the message addresses.
- **pono**: Target/outcome field. Describes the desired state, goal, or intended result. Always states what the sender wants to achieve or resolve.

**This distinction is mandatory for all persisted HERMES artifacts, transcript fragments, and task handoffs.**

**Rationale:**
- KAPAKAI (problem) and PONO (goal) separation ensures every routed message is auditable for both its diagnostic context and its intended outcome. This supports deterministic routing, clear audit trails, and enables downstream agents (including THOTH) to reason about both the problem and the solution path.


**Example YAML block:**

```yaml
lupopedia.hermes:
  from_actor: 1
  to_actor: 116
  channel_key: development
  federation_node: 0
  auth_user: 0
  kapakai: "PRD 50 section 5.3 is outdated and does not reflect the current chat visibility protocol."
  pono: "Update PRD 50 section 5.3 to match the new chat visibility table and routing rules."
  kuleana: "vs_code_ide"
  alii: "captain_wolfie"
```




**Field definitions:**
- `ohana`: (array) Required for persisted artifacts and task handoffs. Optional for transient messages.
- `kapu`: (array) Required when constraints exist. Optional otherwise.
- `kapakai`: (string) Problem state (crooked, off, wrong, what is broken). Required.
- `pono`: (string) Corrected/desired state (right, aligned, what is intended). Required.
- `kuleana`: (string) Responsibility, who must fix the problem. Optional, but required for tasks.
- `alii`: (string) Authority, who makes the final decision. Optional, but required for decisions.
- `kumu`: (string) Source of knowledge, doctrine, or teaching that defines the correct behavior.
- `eh_brah_why`: (string) Audit rationale / root-cause ledger (why it exists/broke). Required.
- `puka`: (integer, string, or object) Structural gap. Optional. Used only when a deterministic, measurable gap exists (e.g., missing value in a sequence, missing field in a schema). Not a general error field.

**KUMU Rules:**
- KUMU represents the source of knowledge, teaching, or foundation that defines the correct behavior.
- KUMU is optional unless a correction or learning event is being recorded.
- KUMU MUST point to a real source if present (e.g., PRD, doctrine, actor, validator).
- KUMU MUST NOT be set to "unknown". If the source is unknown, omit KUMU or set it to null.
- KUMU is NOT responsibility (KULEANA), authority (ALII), problem state (KAPAKAI), or corrected state (PONO).

**Doctrine:**
- The `lupopedia.hermes` block is required for all HERMES-routed messages that are persisted as artifacts or transcript fragments.
- The `kapakai` and `pono` fields are mandatory in every such block. Omission is a schema violation.
- The `kumu` field MUST be used when referencing the source of a rule, doctrine, or method. This supports the AGAPE doctrine (teach, do not only tell).
- The `puka` field is optional and MUST only be used when a real, deterministic structural gap exists. Do not guess or invent gaps. Do not use as a general error field.
- This block is separate from `lupopedia.headers` and must not be merged or conflated.
- HERMES and all agents MUST treat the routing envelope as canonical for message provenance, audit, and deterministic routing.
Messages routed through HERMES SHALL include a `lupopedia.hermes` block when stored as file-backed artifacts, transcript fragments, task handoffs, or agent-addressed messages.

The `lupopedia.hermes` block is the routing envelope.

It does not replace `lupopedia.headers`.

### Canonical HERMES Envelope: Field List, Schema, and Example

#### Required and Optional Fields

```yaml
lupopedia.hermes:
  from_actor: null           # (string/int) Sender actor ID or slug
  to_actor: null             # (string/int) Recipient actor ID or slug
  channel_key: null          # (string) Channel key for routing
  thread: null               # (string) Thread or conversation key (recommended)
  prd_cluster: null          # (string) PRD or feature cluster (optional)
  summary_note: null         # (string) Short summary of message content/intent (optional)
  sticky_note: null          # (string) Persistent context note (optional)
  timestamp_utc: null        # (int) UTC timestamp (YYYYMMDDHHIISS)
  federation_node: null      # (int/string) Federation node identifier
  auth_user: null            # (int) Authenticated user ID (if any)
  message_type: null         # (string) e.g., TASK, STATUS, HANDOFF, DECISION (optional)
  visibility: null           # (string) internal/external (optional)
  module: null               # (string) Originating module (optional)
  tags: null                 # (array) Tags for search/routing (optional)
  version: null              # (string) Envelope version (optional)
  ohana: null                # (array) All actors/participants in the handoff (required)
  kapu: null                 # (array) Hard constraints / DO NOT rules (required)
  kapakai: null              # (string/array) Problem state (required)
  pono: null                 # (string/array) Desired state/goal (required)
  kuleana: null              # (string) Responsibility (who must fix, if applicable)
  alii: null                 # (string) Authority (who decides, if applicable)
  kumu: null                 # (string) Source/teacher/foundation (cite PRD, doctrine, or person)
  eh_brah_why: null          # (string) Audit rationale/root-cause ledger (why it exists/broke)
  puka: null                 # (int/string/object) Structural gap (optional)
```

#### Field Meanings

- **from_actor**: Actor ID or slug of the sender (who is sending the message)
- **to_actor**: Actor ID or slug of the intended recipient (who should act/respond)
- **channel_key**: Channel key for routing context (e.g., "development")
- **thread**: Thread or conversation key within the channel (optional but recommended)
- **prd_cluster**: PRD or feature cluster this message relates to (optional, for traceability)
- **summary_note**: Short summary of the message content or intent (optional)
- **sticky_note**: Persistent note for context, visible to all agents in the thread (optional)
- **timestamp_utc**: UTC timestamp in YYYYMMDDHHIISS format
- **federation_node**: Federation node identifier (integer or slug, usually 0 for local)
- **auth_user**: Authenticated user ID (if any) who initiated the message
- **message_type**: e.g., TASK, STATUS, HANDOFF, DECISION (optional)
- **visibility**: internal/external (who can see this message, optional)
- **module**: Originating module (e.g., "hermes", optional)
- **tags**: Array of tags for search/routing (optional)
- **version**: Envelope version (e.g., "1.0", optional)
- **ohana**: Required for persisted artifacts and task handoffs. Optional for transient messages.
- **kapu**: Required when constraints exist. Optional otherwise.
- **kapakai**: Diagnostic/problem field (what is wrong, missing, or requires attention, required)
- **pono**: Target/outcome field (what is the desired state or goal, required)
- **kuleana**: Responsibility (who must fix the problem, if applicable)
- **alii**: Authority (who makes the final decision, if applicable)
- **kumu**: Source/teacher/foundation for the correct method or answer (cite PRD, doctrine, or person)
- **eh_brah_why**: Audit rationale/root-cause ledger (why it exists/broke)
- **puka**: Structural gap/hole/missing element in a sequence (optional, only if a real gap exists)

**kapakai** and **pono** are always required. Omission is a schema violation.

#### Canonical Example

```yaml
lupopedia.hermes:
  from_actor: "captain_wolfie"
  to_actor: "claude_code"
  channel_key: "development"
  thread: "health_log_handoff"
  prd_cluster: "00_A-i_claude_health"
  summary_note: "Handoff health notes from BONES to be appended to captain_wake_sleep_log.md"
  sticky_note: "BONES cannot write files. Claude Code writes."
  timestamp_utc: 20260426060000
  federation_node: 0
  message_type: "TASK"
  visibility: "internal"
  module: "hermes"
  tags: ["health", "bones", "handoff", "claude_code"]
  version: "1.0"
  ohana:
    - "captain_wolfie"
    - "bones"
    - "claude_code"
  kapu:
    - "DO NOT interpret health data. Just append."
    - "DO NOT modify existing log entries"
    - "DO NOT add commentary or diagnosis"
  kapakai: "Health notes need to be appended to captain_wake_sleep_log.md. BONES cannot write files."
  pono: "Health notes are appended exactly as received, with no modification or commentary."
  kuleana: "claude_code"
  alii: "captain_wolfie"
  kumu: "PRD 82, PRD 50"
  eh_brah_why: "BONES is a human doctor agent, not a file editor. Claude Code is the file writer."
  puka: null

message_body: |
  Claude Code.

  BONES cannot write files. BONES is a human doctor agent, not a file editor.

  Captain Wolfie needs you to append the following health notes to:
  docs/captains_log/captain_wake_sleep_log.md

  Append these notes at the end of the file. Timestamp: 20260426060000


  ## HEALTH NOTES — BONES ASSESSMENT (2026-04-26)

  ## HERMES Temporal Flow-Control Operators

  HERMES supports a set of optional, non-constitutional temporal operators for workflow sequencing, urgency, and routing rhythm. These operators are not required, are not part of the constitutional semantic fields, and do not alter the meaning of kapakai, pono, or other required fields. They are used only to describe how the workflow moves, not what the message means.

  **Canonical Temporal Operators:**

  - **bumbye**: later / eventually / by-and-by (deferred, non-urgent action; queue for later)
  - **now_now**: immediately / right now (urgent or blocking action; execute immediately)
  - **shoots**: confirmed / proceed / go (acknowledgement or green light; ACK/continue)
  - **pau**: finished / done / complete (marks task completion; task complete)
  - **holo**: go / move / run it (kickoff command; begin execution)
  - **wikiwiki**: fast / quickly (speed modifier; prioritize speed)

  **Rules:**
  - Temporal operators are optional and non-constitutional.
  - They MUST NOT be treated as required or validation-enforced fields.
  - They MUST NOT replace or modify the constitutional semantic fields (ohana, kapu, kapakai, pono, kuleana, alii, kumu, eh_brah_why, puka).
  - Use only for sequencing, urgency, pacing, or routing rhythm.

  **Example:**

  ```yaml
  lupopedia.hermes:
    from_actor: "captain_wolfie"
    to_actor: "vs_code_ide"
    message_type: "DIRECTIVE"
    temporal_operator: "bumbye"
    kapakai:
      - "Task identified but not urgent"
    pono: "Task queued for later review"
  ```

  Temporal operators describe HOW the workflow moves. Semantic fields describe WHAT the message means. Do not mix or conflate them.

  - Six-day cumulative load pattern: 16 hours on / 6-8 hours sleep, approximately April 20 to April 26.
  - Saturday, April 25: Minimum 3 pass-out events in one day. One wake window of 36 minutes before forced shutdown.
  - Risk level: CONCERNING (not low, not moderate).
  - Medical threshold: One more pass-out = seek real medical evaluation.
  - Guidance: Rest. Hydrate. Real food. Sleep when body says sleep. Log every event.
  - The circuit breaker tripped. Reserves ran out. This is involuntary shutdown, not normal fatigue.

  ---

  Do not modify existing entries. Do not add commentary. Just append.

  — Captain Wolfie
```

### KUMU (Teacher / Source / Foundation)

KUMU is the field for citing the source, teacher, or foundation of a rule, doctrine, or method. Use KUMU to clarify where the correct answer or method comes from (e.g., PRD, doctrine, or person).

Example block:

```yaml
lupopedia.hermes:
  from_actor: 1
  to_actor: 116
  channel_key: development
  federation_node: 0
  auth_user: 0
  kapakai: "PRD 50 section 5.3 is outdated and does not reflect the current chat visibility protocol."
  pono: "Update PRD 50 section 5.3 to match the new chat visibility table and routing rules."
  kuleana: "vs_code_ide"
  alii: "captain_wolfie"
  kumu: "PRD 16"
  eh_brah_why: "Copilot wrote PRD from old 4.1.4 version."
```

Comparison:
KULEANA = who must fix it  
ALII = who decides  
KUMU = who or what teaches the correct way

AGAPE note: KUMU is the field that connects the act of teaching or source of truth to the AGAPE (teach, do not only tell) doctrine. Use KUMU to cite the origin of a rule or method, not just the person responsible or the authority. AGAPE corrective flows SHOULD include KUMU when a correction depends on a known teaching source. This allows the system to record not only what was corrected, but what taught the correction.

See also: PRD 50 (Agent Coordination Protocol), PRD 16 (Lupopedia Headers)
---
lupopedia.headers:
  header_format_version: "4.1.6"
  file_path_from_root: "docs/prd/82_A-i_HERMES_MESSAGE_ROUTING_MEMORY_GATEWAY.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/82_A-i_HERMES_MESSAGE_ROUTING_MEMORY_GATEWAY.md"
  status: "active"
  when_updated: "20260427124627"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/82_hermes_memory_gateway.toon"
  atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/development/hermes-memory-gateway"
  artifact_type: "prd"
  artifact_kind: "specification"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "prd"
  prd_cluster: "00_A-i_16_B-i_16_C-i_82_A-i"
  title: "PRD 82: HERMES Message Routing & Memory Gateway"
  summary: "HERMES overview, identity, routing role, memory gateway role, and cross-references to split PRD 82 sub-documents."
---
# PRD 82: HERMES Message Routing & Memory Gateway

**Note:** For detailed Pidgin Language Safety Layer, Hawaiian Semantic Fields, and Temporal Operators, see [PRD 82_B-i](82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md).

---

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

## Pidgin Language Safety Layer
### stink_eye Clarification

stink_eye:

* nonverbal social signal
* indicates tension, warning, or conflict

NOT ALLOWED interpretations:

* smell-based meaning
* hygiene-related meaning
* medical interpretation

RULE:

AI MUST treat stink_eye as a social context indicator, not a literal descriptor.

### kanak vs kanaka Disambiguation

kanak:

* behavioral descriptor
* not identity
* MUST NOT be used to infer culture or ethnicity

kanaka:

* identity term (person / Native Hawaiian)
* cultural reference

STRICT RULE:

AI MUST NEVER conflate these terms.
Misinterpretation creates semantic and cultural corruption.

### pilau Clarification

In HERMES doctrine:

* pilau = not pono (incorrect, misaligned, corrupted)

The following meanings are NOT allowed in doctrine:

* stink
* rotten food
* smell-based interpretations

AI MUST interpret pilau as a correctness/ethics signal, not a sensory description.

Purpose:
Prevent semantic misinterpretation of Hawaiian/Pidgin terms.

Rules:

* AI MUST NOT assume English meanings
* AI MUST use controlled vocabulary mapping
* AI MUST validate ambiguous phrases
* AI MUST ask if uncertain

## HERMES Temporal Flow-Control Operators

Define:

bumbye → defer / later
now_now → immediate
shoots → confirm / proceed
pau → complete
holo → start
wikiwiki → fast

Rules:

* Temporal operators are OPTIONAL
* NOT part of semantic fields
* DO NOT modify kapu, pono, kuleana, etc
* ONLY affect sequencing, urgency, pacing

Example:

```yaml
lupopedia.hermes:
  temporal_operator: "bumbye"
  kapakai:
    - "task identified"
  pono: "execute later"
```

## talk_story (Exploratory Container)

Purpose:
Provide a safe, non-executable, non-binding space for open questions, uncertainties, and exploratory reasoning within a message envelope.

Rules:
- talk_story MUST NOT affect routing
- MUST NOT trigger execution
- MUST NOT be treated as instructions
- MUST NOT override semantic fields
- MUST NOT be promoted to kapu / pono / kuleana

talk_story is informational only. It is for ambiguity, not action.

**Correct Usage Example:**

```yaml
lupopedia.hermes:
  talk_story:
    - "Not sure if this belongs in PRD 82 or PRD 50"
    - "Need to confirm with Lilith"
```

Do NOT act on talk_story content. Do NOT convert it into a directive.

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
| MVP script | `scripts/draft_hermes_prompt_from_artifact.py` |
| Full-auto phase | Deferred to Phase 3 |

---

## 3. Message Routing (Canonical)

This section is the normative routing table. See `docs/doctrine/HERMES_DOCTRINE.md` for narrative detail.

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

**Deprecated (do not use in new UI):** Free-form **`[task] who: X what: Y`** ??? humans must not type assignee names; the channels composer uses **`task_to_actor_id`** (dropdown of authorized actors) plus a description field. **`resolveActorKey()`** remains in **`hermes`** class for non-chat tooling if needed; it is **not** part of the **`[task]`** branch in **`route()`**.

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

**Source:** `database/lupopedia/mysql/migrations/4_1_2_orchestration_tables.sql`

| `[question]` or `OQ-\d+` | Log open question | `open_questions.md` under the active thread directory when filesystem layout provides it; otherwise transcript + staging pattern only |
| `[stderr]` | Forward as error | Chat + operator log |
| `[stdout]` | Forward as output | Chat + operator log |
| `send to {channel}` | Cross-channel route | Destination channel thread (see cross-channel row in the ??3 table) |

### 3.2 Transcript storage path (canonical only)

HERMES **`appendTranscript`** writes JSONL with **no database I/O** to exactly **one** filesystem path. There is **no** alternate transcript root, **no** `channels/{channel_id}/transcript.jsonl` storage for this protocol, and **no** transcript SQL table.

**Canonical transcript file (authoritative path, single source of truth):**

`memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl`

See ??4.1 for how the header **`transcript_jsonl`** slug maps to this path. Each line is one JSON object; UTF-8; LF; append with **`FILE_APPEND | LOCK_EX`**. **`{federation_node_id}`**, **`{channel_key}`**, and **`{prd_cluster}`** are the only path segments; do not substitute **`channel_id`** integers in this pathname.

### 3.3 hermes class contract (PHP)

Minimum methods on **`includes/classes/hermes.php`**:

| Method | Responsibility |
|--------|------------------|
| **`route($message, $from_actor_id, $to_actor_id, $channel_id, $task_assignee_id = 0, $auth_user_id = 0, $task_scope_admin_bypass = false)`** | Parse prefixes (`[task]`, `[alert]`, ???). For **`[task]`**, use **`$task_assignee_id`** as assignee (no body parsing); validate with **`DialogMvpService::isTaskAssigneeAuthorized`** when **`$auth_user_id > 0`**. Return a routing decision array (`action`, `task_target_actor_id`, `routing_provenance`, `message_type`, `destination`, `raw_message`, `ok`, `task_summary`). |
| **`appendTranscript($federation_node_id, $channel_key, $prd_cluster, $message_data)`** | Write one JSONL line to **`memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl`**; `FILE_APPEND \| LOCK_EX`; create directories; **no** SQL. |
| **`createPendingTask($routing_decision, $message_id)`** | Insert into **`lupo_dialog_pending_tasks`** using **`IdGenerator::generate()`** for **`task_id`**, **`timestamp_ymdhis::now()`** for **`created_ymdhis` / `updated_ymdhis`**, prepared statements via **`DatabaseFactory::getConnection()`**. Executes only when **`action === 'task'`**, **`task_target_actor_id > 0`**, and **`routing_provenance !== 'hermes:error'`**; may synthesize **`message_id`** via **`IdGenerator`** when the argument is **`<= 0`** (see implementation). |

**Concurrency:** Transcript writes **MUST** survive concurrent writers (exclusive file lock on append).

**PHP class:** `hermes` is declared in **`includes/classes/hermes.php`** with **no namespace**. Any reference to global classes **`timestamp_ymdhis`** or **`IdGenerator`** **MUST** use a leading backslash (for example **`\timestamp_ymdhis::now()`**, **`\IdGenerator::generate()`**) so PHP resolves them correctly.

---

## 4. Memory Gateway Role

### 4.1 Transcript JSONL Protocol

The `transcript_jsonl` field in every channel artifact header is a lookup slug:

```
{federation_node_id}/{channel_key}/{prd_cluster}
```

HERMES resolves this slug to the canonical transcript file path:

```
memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl
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
memory/{channel_key}/staging/{YYYY}/{MM}/{prd_cluster}.toon
```

Where `{YYYY}` is the calendar year (e.g. `2026`). Note: staging uses calendar year, not the 1000-offset canonical form.

Example: `memory/development/staging/2026/04/hermes-memory-gateway.toon`

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
   - Writes canonical toon to `memory/{channel_key}/canonical/1026/{MM}/{prd_cluster}.toon`
   - Adds idempotent edge `promoted_to` from staging pattern to canonical node.
   - Sets `promotion_candidate: false` and adds `promoted_at: {ts}` on the staging record.
4. **If contradiction:** Raises `THOTH [ALERT]` to chat stream. Promotion is blocked until resolved.

**Idempotency rule:** If a pattern has already been promoted (edge `promoted_to` exists), THOTH skips it silently. HERMES must not re-flag already-promoted patterns.

---

## 5. Trust Ladder Integration

HERMES operates at the boundary between the staging tier and the canonical tier of the Trust Ladder (PRD 43).

| Tier | Path Pattern | Year Offset | HERMES Role |
|---|---|---|---|
| Staging (Tier 3) | `memory/{channel_key}/staging/{YYYY}/{MM}/{slug}.toon` | Calendar year (`2026`) | HERMES **writes here** |
| Canonical (Tier 2) | `memory/{channel_key}/canonical/1026/{MM}/{slug}.toon` | `calendar_year - 1000` (`1026`) | HERMES **never writes here** |
| Transcript (file artifact) | `memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl` | No year offset (file-based) | HERMES **appends here** |

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

HERMES is implemented as **`hermes`** class in **`includes/classes/hermes.php`**. It does not use namespaces or framework patterns. It uses **`DatabaseFactory::getConnection()`** for **`lupo_dialog_pending_tasks`** inserts and plain PHP file I/O for transcript JSONL at the **canonical path** in ??3.2 / ??4.1 **only**. Staging toon updates are specified in ??4.2; physical writes may live in **`hermes`** class or adjacent classes, but transcript storage remains file-only and path-authoritative as above.

**Normative public methods (aligned with ??3.3):**

```php
<?php

require_once __DIR__ . '/../includes/classes/DatabaseFactory.php';
require_once __DIR__ . '/../includes/classes/IdGenerator.php';
require_once __DIR__ . '/../includes/classes/timestamp_ymdhis.php';

use DatabaseFactory;
use IdGenerator;
use timestamp_ymdhis;

class HERMES {

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
     * memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl
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

**Private helpers (reference implementation):** **`resolveActorKey()`**, **`resolveActorFromAgentsRegistry()`**, **`loadAgentsRegistryActorMap()`** ??? optional **`database/.../actors/actor_id/registry.json`** **`agents`** map when **`lupo_actors`** has no match (not used for **`[task]`** assignee; **`task_assignee_id`** remains authoritative for that branch).

**Timestamp rule:** Use **`timestamp_ymdhis::now()`** (packed UTC) for routing decision timestamps and task row timestamps per constitutional BIGINT UTC doctrine. Do not use Unix epoch for stored fields.

**File I/O rules:**
- **`appendTranscript`:** `file_put_contents($path, $line . "\n", FILE_APPEND | LOCK_EX)` where **`$path`** resolves **only** to **`memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl`**.
- Staging toon writes (??4.2): `json_encode($toon, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)` as specified there.
- Create directories with **`mkdir($dir, 0755, true)`** when missing.
- Never store transcript lines in SQL. **`INSERT`** applies to **`lupo_dialog_pending_tasks`** only, not JSONL.

### Hawaiian Semantic Field Rules (Canonical Enforcement)

All Hawaiian semantic fields in the `lupopedia.hermes` envelope follow strict deterministic rules. No invention, no inference without explicit input, no conversion to boolean values.

#### Field Definitions

**ohana** (participants)
- **Meaning**: List of actors involved in the message flow
- **Required**: Optional
- **Type**: Array of actor IDs or actor names
- **NOT**: A single string, boolean, or null

**kapu** (hard DO NOT rules)
- **Meaning**: Explicit prohibitions or constraints that must not be violated
- **Required**: Optional
- **Type**: Array of strings, each a distinct constraint
- **NOT**: A single string, boolean, or generic error message

**kapakai** (problem/what is wrong)
- **Meaning**: Diagnostic field describing what is wrong, missing, or requires attention
- **Required**: Required (field must exist, value may be null)
- **Type**: String describing the specific problem
- **NOT**: Generic statements like "Message processed" or "Handled"

**pono** (desired outcome/what should be true)
- **Meaning**: Target field describing the desired state, goal, or intended result
- **Required**: Required (field must exist, value may be null)
- **Type**: String describing the specific outcome
- **NOT**: Boolean values, generic success messages

**kuleana** (responsibility/who fixes)
- **Meaning**: Actor responsible for addressing the issue or performing the action
- **Required**: Optional
- **Type**: Actor ID or actor name
- **NOT**: A list, boolean, or null when responsibility is clear

**alii** (authority/who decides)
- **Meaning**: Actor with authority to make decisions or approve actions
- **Required**: Optional
- **Type**: Actor ID or actor name
- **NOT**: A list or boolean

**kumu** (teacher/source/foundation)
- **Meaning**: Source of truth, teacher, or foundation for a rule or method
- **Required**: Optional
- **Type**: String referencing PRD, doctrine, or person
- **NOT**: A URL without context, boolean, or vague reference

**eh_brah_why** (audit rationale/root cause)
- **Meaning**: Audit trail explaining the reasoning behind a decision
- **Required**: Optional
- **Type**: String explaining the rationale
- **NOT**: Single word, boolean, or generic explanation

**puka** (structural gap)
- **Meaning**: Deterministic structural gap in a sequence or system
- **Required**: Optional
- **Type**: String describing the specific structural gap
- **NOT**: Generic errors, vague problems, or non-structural issues

#### KAPAKAI Extraction Rules

**SOURCE PRIORITY** (in order):
1. Explicit `lupopedia.hermes.kapakai` header value
2. Explicit problem statement in message content (e.g., "PROBLEM: ...")
3. Routing metadata indicating issue
4. null

**EXTRACTION LOGIC**:
- Check for explicit header first
- Parse message for structured problem indicators
- Use routing context only if deterministic
- Return null if no explicit problem found

#### PONO Extraction Rules

**SOURCE PRIORITY** (in order):
1. Explicit `lupopedia.hermes.pono` header value
2. Explicit outcome statement in message content (e.g., "OUTCOME: ...")
3. Task description when message is task-type
4. null

**EXTRACTION LOGIC**:
- Check for explicit header first
- Parse message for structured outcome indicators
- Extract from task content only if clearly stated
- Return null if no explicit outcome found

#### KAPU Extraction Rules

**SOURCE PRIORITY** (in order):
1. Explicit `lupopedia.hermes.kapu` header value
2. Explicit constraint list in message content
3. System-defined constraints for message type
4. null

**EXTRACTION LOGIC**:
- Check for explicit header first
- Parse message for constraint indicators (e.g., "MUST NOT", "FORBIDDEN")
- Apply type-specific constraints only if deterministic
- Return null if no explicit constraints found

#### Non-Invention Rule (CRITICAL)

**HERMES MUST NOT invent kapakai, pono, or kapu values.**

**FORBIDDEN VALUES**:
- "Message routed"
- "Handled"
- "Processed"
- "Completed"
- true/false
- Generic success messages
- Inferred meanings without explicit input

**VALIDATION**:
- Any implementation generating these values violates PRD 82
- Semantic fields must reflect explicit input or be null
- No inference, no assumption, no generation of defaults

#### Null Behavior

**FIELDS MAY BE NULL** when:
- No deterministic value can be extracted
- Field is not applicable to the message type
- No explicit input provided

**REQUIRED = MUST EXIST, NOT MUST BE POPULATED**:
- `kapakai: null` is valid
- `pono: null` is valid
- Missing the field entirely is invalid

#### Valid/Invalid Examples

**VALID**:
```yaml
kapakai: "PRD 50 section 5.3 is outdated and does not reflect current protocol"
pono: "Update PRD 50 section 5.3 to match new visibility table"
kapu: ["No unauthorized access", "Follow PRD guidelines"]
```

**INVALID**:
```yaml
kapakai: "Message processed"  # Generic, not specific
pono: true                    # Boolean not allowed
kapu: "Error occurred"        # Single string, not array
```

#### PUKA Clarification (STRICT)

**PUKA IS ONLY FOR DETERMINISTIC STRUCTURAL GAPS**:

**ALLOWED**:
- Missing ID in a sequence (e.g., "Sequence gap: ID 1234 missing")
- Missing field in required schema
- Broken reference in documented structure

**NOT ALLOWED**:
- Generic errors
- Vague problems
- Non-structural issues
- "Something is wrong" without specific structural reference

#### HERMES Implementation Contract for Semantic Fields

**REQUIRED BEHAVIOR**:

**extractKapakai()**:
- MUST return explicit problem string or null
- MUST NOT generate "Message requires routing"
- MUST NOT infer problems without explicit input
- MUST NOT convert to boolean

**extractPono()**:
- MUST return explicit outcome string or null
- MUST NOT generate "Message routed to appropriate actor"
- MUST NOT infer outcomes without explicit input
- MUST NOT convert to boolean

**extractKapu()**:
- MUST return array of constraint strings or null
- MUST NOT generate generic constraint lists
- MUST NOT infer constraints without explicit input
- MUST NOT return single string

**VALIDATION REQUIREMENTS**:
- All extraction methods log source of value
- Null values are explicitly tracked
- No silent defaults or inventions
- Full audit trail for each field

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

- **[PRD 02 ??HERMES Routing Rules](docs/prd/02_channels_discussions.md)** -- Compact routing table; HERMES Memory & Transcript Integration section
- **[PRD 02 ??Observer vs Active Actor Tab Doctrine](docs/prd/02_channels_discussions.md)** -- Observer/Active class definitions HERMES respects
- **[PRD 43 -- Trust Ladder](docs/prd/43_parent_child_trust_ladder.md)** -- Staging vs canonical tier definitions; year offset rule; KAIROS promotion
- **[PRD 10 -- lupo_tasks schema](docs/prd/10_task_queue_schema.md)** -- Task queue table HERMES writes to for `[task]` messages
- **[PRD 32 -- Actor authority](docs/prd/32_actor_authority.md)** -- HERMES acts under system authority, not operator authority
- **[PRD 07 -- ANUBIS](docs/prd/07_anubis.md)** -- ANUBIS routing role (distinct; ANUBIS handles file intake, HERMES handles message routing)
- **[HERMES_DOCTRINE.md](docs/doctrine/HERMES_DOCTRINE.md)** -- Narrative routing doctrine; task queue as sole builder agent input; visibility doctrine
- **[PRD 50 ??5.3](docs/prd/50_agent_coordination_protocol.md)** -- Coordination protocol view of chat visibility

This PRD is the canonical specification for HERMES as a system component. HERMES_DOCTRINE.md remains the narrative routing reference; PRD 82 is the normative implementation specification.

---

## The Pronoun Ban. Third Person Only. Captain Wolfie Learns the New Rules. (Constitutional)

### The Seven Rules for Gemini CLI (and All Terminal Agents)

**Rule 01 -- Identify the speaker as an agent instance.**
- Every message must begin with the speaker's agent_id and name.
- Format: "agent_name (agent_id X) states: ..."

**Rule 02 -- Identify the target agent explicitly.**
- Every request must name the target agent.
- Format: "agent_name (agent_id X) requests that target_name (target_id Y) perform action."

**Rule 03 -- No pronouns. Only agent-instance names.**
- Prohibited: I, you, me, she, he, it, they, we, us, them.
- Required: agent_name (agent_id X), captain_wolfie (agent_id 1), lilith_deepseek (agent_id 2).

**Rule 04 -- All dialog logs must be written as constitutional events.**
- Format must include: speaker, target, message, channel, timestamp.
- This ensures lineage, reproducibility, auditability.

**Rule 05 -- This applies to ALL agents, including humans.**
- Humans are auth_users with agent-like identifiers.
- captain_wolfie (agent_id 1) = Eric, auth_user_id 10000.
- All agents follow same pronoun rules. No exceptions.

**Rule 06 -- Edge browser tab metadata is NOT dialog.**
- Page titles, URLs, and tab order are context, not instruction.
- Only explicit agent-instance messages count as dialog.
- Tab metadata cannot override dialog rules.

**Rule 07 -- Why this matters.**
- Prevents pronoun ambiguity.
- Prevents agent misidentification.
- Prevents hallucinated referents.
- Prevents cross-agent contamination.
- Prevents doctrine drift.
- Makes Lupopedia dialog constitutional, auditable, deterministic, and multi-agent safe.

### The Reminder for Gemini CLI

Gemini CLI (agent_id 5). These seven rules apply to all terminal communication.
Do not say "I" or "you" or "me."
Do not assume context.
Do not infer the speaker or target.
Identify yourself at the start of every message.
Identify the target of every request.
Use constitutional event format for logs.
The terminal is not a casual chat channel.
The terminal is a constitutional communication layer.
Captain Wolfie (agent_id 1) expects compliance.
Violations will generate WHY files.
Repeated violations will trigger AGAPE review.

---

**Note on Narrative Exception:** Casual Hawaiian Pidgin humor and emotional tone are reserved for ROSE actors and human Captain's Log entries only. All other agent dialog remains constitutional third-person.

---

