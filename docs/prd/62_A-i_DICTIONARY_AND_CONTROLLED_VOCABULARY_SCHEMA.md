---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/62_A-i_DICTIONARY_AND_CONTROLLED_VOCABULARY_SCHEMA.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/62_A-i_DICTIONARY_AND_CONTROLLED_VOCABULARY_SCHEMA.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/dictionary-schema.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd/62_dictionary_schema
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_16_C-i_62_A-i
  title: PRD 62 — Dictionary and Controlled Vocabulary Schema
  summary: Defines the canonical structure, rules, and enforcement model for all dictionary and vocabulary entries used in Lupopedia hybrid dialect.
---

# PRD 62 — Dictionary and Controlled Vocabulary Schema

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

Define a **deterministic, non-drifting schema** for all vocabulary used in Lupopedia:

- Hawaiian terms (constitutional)
- Pidgin terms (behavioral / communication)
- Hybrid system terms (cockaroach, kapakai, etc.)

This prevents:
- semantic drift
- duplicate definitions
- conflicting meanings
- AI misinterpretation

---

## Core Principle

```text
Vocabulary is SYSTEM STATE, not documentation fluff.
```

---

## Entry Types

Each word MUST belong to exactly one type:

```text
1. CONSTITUTIONAL   → kapu, pono, ohana, kuleana
2. BEHAVIORAL       → lolo, batu, pilau, kanak
3. ROLE             → kanaka, haole, mainlander
4. TECHNICAL-SLANG  → cockaroach
5. TEMPORAL         → bumbye, now_now, pau
```

---

## Vocabulary Classes (MANDATORY DISTINCTION)

All language used in Lupopedia MUST be classified into one of two classes:

### 1. SYSTEM VOCABULARY (Deterministic)

These terms have fixed, non-negotiable meanings and directly affect system behavior.

Rules:
- MUST be defined in the dictionary
- MUST map to a single TYPE
- MUST NOT drift in meaning
- MUST be used consistently across all PRDs and agents
- MAY influence routing, validation, or execution

Examples:
- kapakai → problem state
- pono → resolved state
- kanaka → non-AI human role
- kuleana → responsibility field
- cockaroach → unauthorized system takeover behavior

These terms are part of the system's semantic control layer.

---

### 2. EXPRESSIVE LANGUAGE (Non-Deterministic)

These terms provide tone, emphasis, or personality but DO NOT affect system behavior.

Rules:
- MUST NOT influence routing decisions
- MUST NOT populate semantic fields (kapakai, pono, kuleana, etc.)
- MUST NOT be used as TYPE-bearing dictionary entries
- MAY appear in message_body or talk_story only
- MUST be ignored by HERMES extraction logic

Examples:
- brah → informal address / tone
- gerr → emphasis / frustration signal
- eh → conversational marker

These terms are human-layer only and are intentionally non-operational.

---

## Enforcement Rule

If a term affects:

- routing
- responsibility
- validation
- execution

→ it MUST be SYSTEM VOCABULARY

If a term affects only:

- tone
- emotion
- conversational style

→ it MUST be EXPRESSIVE LANGUAGE

Mixing these classes is a schema violation.

---

## Canonical Entry Format (MANDATORY)

ALL entries MUST follow this structure:

```markdown
* term — short meaning

TYPE:
<one of the 5 types>

MEANING:
exact definition (single source of truth)

USAGE:
how the system uses the term (if different from natural language)

EXAMPLES:
- example
- example

SYSTEM NOTE:
constraints, restrictions, or interpretation rules
```

---

## Example (CORRECT)

```markdown
* cockaroach — sneaky override behavior

TYPE:
TECHNICAL-SLANG

MEANING:
to sneak, steal, creep around, or override control

USAGE:
framework or system attempting to take over namespace or execution

EXAMPLES:
- "Framework stay cockaroaching my includes"

SYSTEM NOTE:
represents unauthorized control behavior; must not be normalized
```

---

## Forbidden Patterns (KAPU)

```text
DO NOT:
- define the same word twice
- mix bullet and header formats
- create multiple meanings without TYPE separation
- leave partial definitions
- mix table + paragraph hybrid structures
- introduce slang without classification
```

---

## Structural Rules

### 1. Single Source of Truth

```text
Each term may appear ONLY ONCE in canonical dictionary.
```

---

### 2. No Semantic Drift

```text
All references in PRDs must align with dictionary definition.
```

---

### 3. Controlled Expansion

New terms MUST:

```text
- declare TYPE
- include EXAMPLES
- include SYSTEM NOTE
```

---

### 4. No Mixed Schema

```text
Either:
- ALL entries use bullet format
OR
- ALL entries use structured blocks

Never both.
```

(Current standard: BULLET + STRUCTURED SUBFIELDS)

---

## Relationship to HERMES

Dictionary terms map directly to HERMES semantics:

```text
kapakai → problem state
pono → resolved state
kanaka → human actor (non-AI)
cockaroach → unauthorized system takeover behavior
```

---

## Enforcement Model

### Validator (future)

Must check:

```text
- duplicate terms
- missing TYPE
- missing SYSTEM NOTE
- inconsistent formatting
```

---

## kapakai

```text
Vocabulary drift leads to:
- inconsistent AI behavior
- misinterpretation of commands
- broken routing logic
```

---

## pono

```text
Single, deterministic vocabulary layer
All agents interpret terms identically
No ambiguity
```

---

## eh_brah_why

```text
Dictionary evolved organically without schema
Multiple edits introduced duplication, contradiction, and format drift
```

---

## kumu

```text
PRD 00 — no ambiguity
PRD 16 — structured headers
PRD 82 — semantic routing depends on exact meaning
```

---

## Final Rule

```text
If a word matters to the system,
it MUST exist in the dictionary schema.

If it is not in the dictionary,
it does not exist.
```
