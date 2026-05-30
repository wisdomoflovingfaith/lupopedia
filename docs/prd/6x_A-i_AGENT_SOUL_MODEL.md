---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/6x_A-i_AGENT_SOUL_MODEL.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/6x_A-i_AGENT_SOUL_MODEL.md
  status: draft
  when_updated: '20260513033046'
  trust_tier: experimental
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 57_A-i_6x_A-i
  title: PRD 6x — Agent Soul Model
  summary: Defines optional AGAPE, PONO, KAPU, KULEANA, and 1/137 soul-layer meaning for agents; optional filenames align PRD 07. KAPAKAI is not a soul file here.
---

# PRD 6x — Agent Soul Model

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

Define an **optional extension model** for agent introspection and behavioral alignment using conceptual dimensions:

- AGAPE (learned)
- PONO (desired)
- KAPU (forbidden)
- KULEANA (responsibility scope)
- 1/137 (intrinsic constant; optional `1in137.md`)

This model is **not required** for all agents and must not override existing doctrine.

**Non-soul file name:** **KAPAKAI** is not a soul file in this PRD. Do not introduce `soul_kapakai.md` (or equivalent) as part of this model.

---

## Core Rule

```text
Doctrine defines files.
Files do not define doctrine.
```

---

## Relationship to Existing Doctrine

* PRD 07 defines:

  ```text
  soul.txt (OPTIONAL)
  soul_agape.md, soul_pono.md, soul_kapu.md, soul_kuleana.md, 1in137.md, state.jsonl (OPTIONAL; names and layout only in PRD 07)
  ```

* PRD 6x **extends**, but does NOT replace, `soul.txt`.

* PRD 57 defines AGAPE conceptually.
  PRD 6x defines **file-level representation (optional)**.

---

## Soul Dimensions

### AGAPE (Learned State)

Represents accumulated understanding from:

* WHY files (PRD 98)
* audits
* failures
* interactions

AGAPE is reflective, not prescriptive.

---

### KAPU (Forbidden State)

Represents absolute constraints:

* constitutional violations
* system-breaking actions
* domain breaches

KAPU is binding and non-negotiable.

---

### PONO (Desired State)

Represents directional intent:

* correct behavior
* alignment goals
* system harmony

PONO guides but does not override KAPU.

---

### KULEANA (Responsibility scope)

Represents accountable scope and obligations the agent acknowledges:

* bounded duties in channel, thread, or role context
* explicit ownership of what the agent must carry (and what is out of scope)
* alignment between stated responsibility and permitted execution surfaces

KULEANA is introspective and committal for documentation; it does not grant authority beyond existing doctrine, routing, or policy.

---

## 1/137 File (Intrinsic Constant)

### Purpose

Defines an agent's intrinsic, non-derivable behavioral constant.

This represents a property that:

- cannot be derived from AGAPE, KAPU, PONO, or KULEANA
- does not change through learning
- does not respond to directives
- remains stable across all system states

---

### Characteristics

The 1/137 file:

- is immutable once defined
- represents edge-case behavior or inherent bias
- exists outside the learning and correction loop
- must be human-defined and intentional

---

### Constraints

The system MUST NOT:

- auto-generate a 1/137 file
- infer it from behavior
- modify it through learning
- apply it to all agents

---

### Eligibility

Only agents with meaningful intrinsic invariants may include a 1/137 file.

Examples may include:
- high-level reasoning agents
- personality-driven agents
- anomaly-detection agents

Low-level or deterministic agents MUST NOT include one.

---

### File Format

Optional file:

1in137.md

Content must describe:

- the invariant
- its effect on behavior
- when it manifests

---

### Boundary Rule

The 1/137 file MUST NOT:

- affect routing decisions
- modify database state
- override KAPU constraints
- introduce hidden execution logic

It is introspective only.

---

### Core Principle

```text
1/137 = non-derived constant
```

---

## File Schema (OPTIONAL)

Agents MAY include (filenames align **PRD 07** optional root files):

```text
soul_agape.md
soul_pono.md
soul_kapu.md
soul_kuleana.md
1in137.md
```

Optional `state.jsonl` is defined only in **PRD 07** (runtime append log; not a soul-semantics file).

ONLY IF:

* the agent explicitly requires introspection
* the agent participates in learning or adaptive behavior
* a supervising PRD or agent role defines usage

---

## Scope Restrictions

The following are NOT allowed:

```text
- mass creation across all agents
- placeholder or empty files
- auto-generation without context
- assuming all agents require soul files
```

---

## Lifecycle Rules

* Soul files are **written intentionally**, not generated blindly
* Updates must be traceable to:

  * WHY files
  * audits
  * defined events
* Files must remain human-readable and deterministic

---

## Agent Eligibility

Default:

```text
Agents do NOT require soul files
```

Eligible agents may include:

* ROSE (emotional synthesis)
* LILITH (audit memory)
* OEDIPA (pattern learning — if extended)
* WOLFIE (meta reflection)

Non-eligible by default:

* low-level execution agents
* stateless system agents
* routing-only agents (e.g., HERMES core)

---

## Boundary Rule

Soul files:

```text
MUST NOT:
- affect routing
- affect HERMES fields
- modify system execution
- introduce hidden state
```

They are:

```text
introspective artifacts only
```

---

## Enforcement

If any process attempts to:

```text
- create soul files without PRD backing
- mass-apply soul schema
- populate content automatically
```

The system MUST:

```text
STOP
REPORT "DOCTRINE NOT FOUND"
```

---

## Future Extension

PRD 6x may later define:

* structured AGAPE extraction from WHY files
* audit-driven KAPU reinforcement
* PONO drift correction loops

These are NOT part of initial implementation.

---

## Status

```text
Experimental — not system-wide
Adoption must be explicit and controlled
```
