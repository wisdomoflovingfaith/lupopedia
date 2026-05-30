---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/82_hermes_routing_header.toon
  atoms_toon: memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/hermes-routing-header
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_16_B-i_16_C-i_82_A-i_82_B-i
  title: 'PRD 82_Z: HERMES Routing Header and Hawaiian Semantics'
  summary: Canonical specification for lupopedia.hermes routing header, Hawaiian semantic fields, temporal operators, and pidgin language safety layer.
---
# PRD 82_Z: HERMES Routing Header and Hawaiian Semantics

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

This PRD defines the canonical `lupopedia.hermes` routing header structure, Hawaiian semantic fields, temporal flow-control operators, and pidgin language safety layer for HERMES message routing.

---

## Table of Contents

1. [lupopedia.hermes Routing Header](#1-lupopediahermes-routing-header)
2. [Hawaiian Semantic Fields](#2-hawaiian-semantic-fields)
3. [Temporal Flow-Control Operators](#3-temporal-flow-control-operators)
4. [talk_story Exploratory Container](#4-talk_story-exploratory-container)
5. [Pidgin Language Safety Layer](#5-pidgin-language-safety-layer)
6. [Field Extraction Rules](#6-field-extraction-rules)
7. [Implementation Contract](#7-implementation-contract)

---

## 1. lupopedia.hermes Routing Header

### KAPAKAI vs PONO (Mandatory Distinction)

**All HERMES-routed messages MUST include two explicit fields in the `lupopedia.hermes` block:**

- **kapakai**: Diagnostic/problem field. Describes what is wrong, missing, or requires attention. Always states the issue or challenge the message addresses.
- **pono**: Target/outcome field. Describes the desired state, goal, or intended result. Always states what the sender wants to achieve or resolve.

**This distinction is mandatory for all persisted HERMES artifacts, transcript fragments, and task handoffs.**

**Rationale:**
- KAPAKAI (problem) and PONO (goal) separation ensures every routed message is auditable for both its diagnostic context and its intended outcome. This supports deterministic routing, clear audit trails, and enables downstream agents (including THOTH) to reason about both the problem and the solution path.

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
- **ohana**: List of all actors/participants involved in the handoff (required)
- **kapu**: List of explicit prohibitions or constraints (DO NOT rules, required)
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

---

## 2. Hawaiian Semantic Fields

### Field Definitions

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

### Special Field Rules

#### PUKA (Structural Gap)

PUKA represents a structural gap in a sequence or system.

Used for:
- ID continuity checks
- cluster ordering validation
- schema completeness

Example block:

kapakai:
  - "Layer IDs inconsistent"
puka: 5
pono: "Layer IDs continuous"

#### KUMU (Teacher / Source / Foundation)

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

---

## 3. Temporal Flow-Control Operators

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

---

### English Politeness Markers as Routing Flags

English politeness markers may signal executable intent in English-trained AI models.

These markers do NOT replace Lupopedia constitutional fields.

They do NOT define `kuleana`.

They act as routing flags that may switch semantic mode from `talk_story` to executable intent when used in directive context.

#### Common Routing Flag Phrases

The following phrases may signal executable intent:

- please
- could you
- would you
- I need you to
- can you

#### Layer Distinction

`kuleana` is the constitutional responsibility field.

English politeness markers are lexical routing signals.

A politeness marker may activate executable interpretation, but it MUST NOT be stored as the `kuleana` value.

Correct distinction:

```
kuleana = who must act
please = signal that action is intended
```

#### Routing Rule

When a message contains an English politeness marker in a directive context:

1. HERMES MAY classify the message as executable rather than `talk_story` 
2. The agent MUST look for or infer `kuleana` from context
3. If `kuleana` is ambiguous, the agent MUST ask for clarification
4. The politeness marker itself MUST NOT be stored as a constitutional field value

#### Example

**Without politeness marker:**
```
"Give me the status report"
→ May be interpreted as talk_story or executable depending on context
```

**With politeness marker:**
```
"Please give me the status report"
→ Routing flag activates executable intent
→ HERMES classifies as task request
→ Agent must resolve kuleana
```

**With explicit kuleana:**
```
kuleana: windsurf_ide
"Please update the headers"
→ Politeness marker provides routing signal
→ kuleana field provides exact responsibility
```

#### Relation to talk_story

`talk_story` is the safe ambiguity container. It is non-executable.

When a politeness marker is present in a directive context, the message SHOULD NOT be treated as `talk_story` unless other signals override.

This does NOT remove `talk_story` as a valid mode. It adds a routing hint for English-trained models.

#### Constitutional Note

This rule documents observed behavior in English-trained AI models. It does NOT change Lupopedia's constitutional field semantics.

`kuleana` remains the authoritative field for responsibility assignment.

Politeness markers are routing hints only.

---

## 4. talk_story Exploratory Container

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

---

## 5. Pidgin Language Safety Layer

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

* person / human being
* in Lupopedia, non-AI human system role
* MUST NOT be confused with kanak
* MUST NOT be used to infer ethnicity or cultural identity in system routing

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

---

## 6. Field Extraction Rules

### KAPAKAI Extraction Rules

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

### PONO Extraction Rules

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

### KAPU Extraction Rules

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

### Non-Invention Rule (CRITICAL)

**HERMES MUST NOT invent kapakai, pono, or kapu values.**

**FORBIDDEN VALUES**:
- "Message routed"
- "Handled"
- "Processed"
- "Completed"
- true/false
- Generic success messages
- Inferred meanings without explicit input

### EXPRESSIVE LANGUAGE IGNORE RULE

HERMES MUST ignore expressive language during semantic extraction.

The following MUST NOT be interpreted as semantic fields:

- brah
- gerr
- eh
- other tone-only expressions

These words:
- MUST NOT populate kapakai, pono, kuleana, or any semantic field
- MUST NOT influence routing decisions
- MUST be treated as noise for extraction purposes

Only SYSTEM VOCABULARY terms may influence routing or semantic interpretation.

**VALIDATION**:
- Any implementation generating these values violates PRD 82
- Semantic fields must reflect explicit input or be null
- No inference, no assumption, no generation of defaults

### Null Behavior

**FIELDS MAY BE NULL** when:
- No deterministic value can be extracted
- Field is not applicable to the message type
- No explicit input provided

**REQUIRED = MUST EXIST, NOT MUST BE POPULATED**:
- `kapakai: null` is valid
- `pono: null` is valid
- Missing the field entirely is invalid

### Valid/Invalid Examples

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

### PUKA Clarification (STRICT)

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

---

## 7. Implementation Contract

### HERMES Implementation Contract for Semantic Fields

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

## Cross-References

- **[PRD 82_A-i](docs/prd/82_A-i_HERMES_MESSAGE_ROUTING_MEMORY_GATEWAY.md)** -- HERMES overview and system integration
- **[PRD 82_C-i](docs/prd/82_C-i_HERMES_TRANSCRIPT_JSONL_PROTOCOL.md)** -- Transcript JSONL protocol specification
- **[PRD 82_D-i](docs/prd/82_D-i_HERMES_TASK_ROUTING_AND_PENDING_TASKS.md)** -- Task routing and pending tasks
- **[PRD 82_E-i](docs/prd/82_E-i_HERMES_IMPLEMENTATION_CONTRACT.md)** -- PHP implementation contract
- **[PRD 16_C-i](docs/prd/16_C-i_LUPOPEDIA_HEADERS.md)** -- Lupopedia header implementation details
- **[PRD 50](docs/prd/50_A-i_AGENT_COORDINATION_PROTOCOL.md)** -- Agent coordination protocol

---

**Doctrine:**
- The `lupopedia.hermes` block is required for all HERMES-routed messages that are persisted as artifacts or transcript fragments.
- The `kapakai` and `pono` fields are mandatory in every such block. Omission is a schema violation.
- The `kumu` field MUST be used when referencing the source of a rule, doctrine, or method. This supports the AGAPE doctrine (teach, do not only tell).
- The `puka` field is optional and MUST only be used when a real, deterministic structural gap exists. Do not guess or invent gaps. Do not use as a general error field.
- This block is separate from `lupopedia.headers` and must not be merged or conflated.
- HERMES and all agents MUST treat the routing envelope as canonical for message provenance, audit, and deterministic routing.
