---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/channel_model_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/channel_model_doctrine.md
  status: ''
  when_updated: '20260513053336'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: channel_model
  channel_key: null
  federation_node_id: null
  thread_key: 1039
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# WOLFIE DIRECTIVE -- CANONICAL CHANNEL MODEL DOCTRINE

## 1. Purpose

This doctrine defines the **canonical model for every channel** in Lupopedia Core:

- Each channel's **purpose**
- Each channel's **thread model**
- Each channel's **unique behavioral rules**
- The **one exception**: Channel 66's question-driven model

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

---

## 2. The two channel models

### Type A: Task-driven channels (all channels except 66)

- **THREAD** = Task container  
- **ARTIFACT** = Task execution / directive / report / closure  
- **INDEX** = Task registry

### Type Z: Question-driven channels (Channel 66 only)

- **THREAD** = Question container  
- **ARTIFACT** = Answer / attack / evidence / review / closure  
- **INDEX** = Question registry

**Hard boundary:** No other channel may use the question-driven model.

---

## 3. Canonical channel registry

### Channel 0 — System Kernel

| Property | Value |
|----------|-------|
| **Purpose** | Bootstrap, system invariants, kernel operations |
| **Thread model** | Kernel tasks only |
| **Thread index** | `channels/0/THREAD_INDEX.md` |
| **Allowed actors** | system, wolfie (kernel mode) |
| **Special rules** | No human threads; no artifacts without kernel signature |

### Channel 1 — Release Operations

| Property | Value |
|----------|-------|
| **Purpose** | Release prep, shutdown-sync, checkpoints, version cuts |
| **Thread model** | Release tasks; status threads |
| **Thread index** | `channels/1/THREAD_INDEX.md` |
| **Allowed actors** | wolfie; release-manager agents |
| **Special rules** | Every release requires closure artifact |

### Channel 7 — Validator Engineering (Hephaestus)

| Property | Value |
|----------|-------|
| **Purpose** | Strict-mode enforcement, rule hardening, validator development |
| **Thread model** | Enforcement tasks; validator directives |
| **Thread index** | `channels/7/THREAD_INDEX.md` |
| **Allowed actors** | hephaestus; wolfie (override only) |
| **Special rules** | Artifacts can trigger validation sweeps |

### Channel 11 — Documentation Systems (Thoth)

| Property | Value |
|----------|-------|
| **Purpose** | Docs, templates, schemas, documentation infrastructure |
| **Thread model** | Documentation tasks; template generation |
| **Thread index** | `channels/11/THREAD_INDEX.md` |
| **Allowed actors** | thoth; wolfie; athena (audit) |
| **Special rules** | Schema changes require ratification in Channel 51 |

### Channel 17 — Project Architecture

| Property | Value |
|----------|-------|
| **Purpose** | System design, structure, architecture decisions |
| **Thread model** | Architectural tasks; design proposals |
| **Thread index** | `channels/17/THREAD_INDEX.md` |
| **Allowed actors** | wolfie; athena; lilith (adversarial review) |
| **Special rules** | Major architectural changes require Channel 51 ratification |

### Channel 23 — Migration & Upgrade

| Property | Value |
|----------|-------|
| **Purpose** | Data migrations, schema upgrades, system normalization |
| **Thread model** | Migration tasks; upgrade directives |
| **Thread index** | `channels/23/THREAD_INDEX.md` |
| **Allowed actors** | hermes; wolfie |
| **Special rules** | Every migration must have rollback plan |

### Channel 31 — External AI / Faucet

| Property | Value |
|----------|-------|
| **Purpose** | External AI integrations; faucet operations |
| **Thread model** | Integration tasks; external routing |
| **Thread index** | `channels/31/THREAD_INDEX.md` |
| **Allowed actors** | hermes; external agents (wrapped) |
| **Special rules** | External traffic must pass hermetic validation |

### Channel 36 — Unassigned (pending)

| Property | Value |
|----------|-------|
| **Purpose** | **PENDING ASSIGNMENT** |
| **Thread model** | **PENDING ASSIGNMENT** |
| **Thread index** | `channels/36/THREAD_INDEX.md` |
| **Allowed actors** | **PENDING ASSIGNMENT** |
| **Special rules** | **PENDING ASSIGNMENT** |

### Channel 42 — Protocol Development

| Property | Value |
|----------|-------|
| **Purpose** | Core protocol development; system evolution |
| **Thread model** | Development tasks; protocol directives |
| **Thread index** | `channels/42/THREAD_INDEX.md` |
| **Allowed actors** | wolfie; protocol agents |
| **Special rules** | Protocol changes require test harness validation |

### Channel 51 — Doctrine Council

| Property | Value |
|----------|-------|
| **Purpose** | Doctrine definition; constitutional updates; rule ratification |
| **Thread model** | Doctrine tasks; ratification threads |
| **Thread index** | `channels/51/THREAD_INDEX.md` |
| **Allowed actors** | wolfie (orchestrator); athena (audit) |
| **Special rules** | Doctrines must be ratified here before activation |

### Channel 66 — QA / Adversarial Review (the exception)

| Property | Value |
|----------|-------|
| **Purpose** | Contradiction hunting; adversarial testing; quality assurance |
| **Thread model** | **QUESTION-DRIVEN** — every thread is a question |
| **Thread index** | `channels/66/THREAD_INDEX.md` |
| **Allowed actors** | lilith (primary), wolfie, athena, hermes |
| **Special rules** | Thread = question container; artifact = answer/attack/evidence/review/closure; no artifact without question context; adversarial framing required; artifacts not answering/attacking/providing evidence for the thread question are subject to immediate quarantine; **only channel with question model** |

### Channel 88 — Research / Experiments

| Property | Value |
|----------|-------|
| **Purpose** | Experiments, prototypes, research initiatives |
| **Thread model** | Experimental tasks; research notes |
| **Thread index** | `channels/88/THREAD_INDEX.md` |
| **Allowed actors** | wolfie; research agents |
| **Special rules** | Experiments cannot modify production without Channel 23 migration |

### Channel 666 — ANUBIS Quarantine

| Property | Value |
|----------|-------|
| **Purpose** | Orphaned files; unknown recipients; quarantine operations |
| **Thread model** | Quarantine tasks; recovery operations |
| **Thread index** | `channels/666/THREAD_INDEX.md` |
| **Allowed actors** | anubis; wolfie (override) |
| **Special rules** | Quarantine requires human review before deletion |

---

## 4. Channel model enforcement (validator rules)

```yaml
channel_model_enforcement:
  - rule: "Channel 66 MUST use question model"
    severity: "blocking"
    validator: "hephaestus"
  - rule: "All other channels MUST NOT use question model"
    severity: "blocking"
    validator: "hephaestus"
  - rule: "Thread index MUST reflect channel model"
    severity: "blocking"
    validator: "hephaestus"
  - rule: "Artifact channel_id MUST match purpose"
    severity: "warning"
    validator: "hephaestus"
  - rule: "Cross-channel references allowed but must be explicit"
    severity: "info"
    validator: "hermes"
```

### Explicit cross-channel reference format (required when referencing other channels)

When a Channel 66 (or any channel) artifact references content in another channel, it must include **one explicit** reference token in the body:

- `Channel:<channel_id> Thread:<thread_id>`

Example: `Channel:42 Thread:1001`

### LILITH adversarial tests (spec)

```yaml
adversarial_tests:
  - "Attempt to create question thread in Channel 51"
  - "Attempt to route QA artifacts to non-66 channels"
  - "Attempt to close Channel 66 thread without answering question"
  - "Attempt to use Channel 66 for non-adversarial summaries"
```

---

## 5. Thread index templates

### Task-driven channel template (all except 66)

```markdown
# 📋 Channel [X] — [NAME] THREAD INDEX

last_updated: YYYYMMDD

| Thread ID | Task ID | Purpose | Status | Key Artifacts |
|-----------|---------|---------|--------|---------------|
| [ID] | [task_id] | [purpose] | [active|closed] | [artifact_list] |
```

### Question-driven channel template (Channel 66 only)

```markdown
# 🧠 Channel 66 — QA / Adversarial Review THREAD INDEX

last_updated: YYYYMMDD

| Thread ID | Task ID | Canonical Question | Status | Key Artifacts |
|-----------|---------|-------------------|--------|--------------|
| [ID] | [task_id] | [explicit question] | [active|closed] | [artifact_list] |
```

---

## 6. Channel 36 immediate action (candidate purposes)

| Option | Purpose | Rationale |
|---|---|---|
| A | Agent Training | New agent onboarding, training scenarios |
| B | Simulation Environment | System simulation, what-if analysis |
| C | Performance Monitoring | System metrics, performance analysis |
| D | Incident Response | Live incident handling, emergency protocols |

Recommendation: Channel 36 = **Agent Training**.

---

## 7. Canonical rules summary

- **Rule 1 — Channel purpose is inviolable:** Purpose changes require Channel 51 ratification.
- **Rule 2 — Thread model matches channel:** Channel 66 → question model only; all others → task model only.
- **Rule 3 — Thread index is source of truth:** Every channel’s `THREAD_INDEX.md` must reflect its model and list active threads.
- **Rule 4 — Artifacts respect channel boundaries:** artifact `channel_id` must match purpose; cross-channel references must be explicit.
- **Rule 5 — Channel 66 uniqueness is protected:** enforced by validator; tested by LILITH; never copied to other channels.

---

## 8. Ratification (stub)

```yaml
ratification:
  channel: 51
  thread: 1039
  date: "2026-03-19"
  ratifier: "wolfie"
  authority: "canonical_orchestrator"
  status: "active"
  replaces: "informal_channel_understandings"
```

