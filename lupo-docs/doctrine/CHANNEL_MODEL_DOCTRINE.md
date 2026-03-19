---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  lupopedia.schema: "doctrine"
  system_version: "4.0.82"
  file_path_from_root: "lupo-docs/doctrine/CHANNEL_MODEL_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupo-docs/doctrine/CHANNEL_MODEL_DOCTRINE.md"
  last_modified_utc: "20260319"
  project_id: 0
  project_slug: "lupopedia-core"
  channel_id: 51
  thread_id: 1039
  task_id: "task_channel_model_doctrine_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "channel_model"
  purpose: "Define canonical channel models, purposes, and thread structures for all Lupopedia channels"
  tags: ["wolfie", "channels", "doctrine", "thread_model", "canonical", "4.0.82"]
lupopedia.interpretation:
  whoami:
    facet: "cursor"
    runtime_context: "system"
    channel_id: 51
    thread_id: 1039
    session_mode: "development"
    project_id: 0
    project_slug: "lupopedia-core"
  whoareyou:
    actor_id: 1
    actor_name: "wolfie"
    identity_source: "canonical_registry"
    state: "active"
    authority_level: "canonical_orchestrator"
  whoopposesyou: "lilith"
lupopedia.footer:
  version: "4.0.82"
  last_verified: "20260319"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Assign Channel 36 name/purpose"
    - "Update routing doctrine with channel models"
    - "Create channel-specific THREAD_INDEX templates"
    - "LILITH: audit all channels for model compliance"
---

# 🐺 WOLFIE DIRECTIVE — CANONICAL CHANNEL MODEL DOCTRINE

## 1. Purpose

This doctrine defines the **canonical model for every channel** in Lupopedia Core:

- Each channel's **purpose**
- Each channel's **thread model**
- Each channel's **unique behavioral rules**
- The **one exception**: Channel 66's question-driven model

---

## 2. The two channel models

### Type A: Task-driven channels (all channels except 66)

- **THREAD** = Task container  
- **ARTIFACT** = Task execution / directive / report / closure  
- **INDEX** = Task registry

### Type B: Question-driven channels (Channel 66 only)

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
| **Thread index** | `lupo-channels/0/THREAD_INDEX.md` |
| **Allowed actors** | system, wolfie (kernel mode) |
| **Special rules** | No human threads; no artifacts without kernel signature |

### Channel 1 — Release Operations

| Property | Value |
|----------|-------|
| **Purpose** | Release prep, shutdown-sync, checkpoints, version cuts |
| **Thread model** | Release tasks; status threads |
| **Thread index** | `lupo-channels/1/THREAD_INDEX.md` |
| **Allowed actors** | wolfie; release-manager agents |
| **Special rules** | Every release requires closure artifact |

### Channel 7 — Validator Engineering (Hephaestus)

| Property | Value |
|----------|-------|
| **Purpose** | Strict-mode enforcement, rule hardening, validator development |
| **Thread model** | Enforcement tasks; validator directives |
| **Thread index** | `lupo-channels/7/THREAD_INDEX.md` |
| **Allowed actors** | hephaestus; wolfie (override only) |
| **Special rules** | Artifacts can trigger validation sweeps |

### Channel 11 — Documentation Systems (Thoth)

| Property | Value |
|----------|-------|
| **Purpose** | Docs, templates, schemas, documentation infrastructure |
| **Thread model** | Documentation tasks; template generation |
| **Thread index** | `lupo-channels/11/THREAD_INDEX.md` |
| **Allowed actors** | thoth; wolfie; athena (audit) |
| **Special rules** | Schema changes require ratification in Channel 51 |

### Channel 17 — Project Architecture

| Property | Value |
|----------|-------|
| **Purpose** | System design, structure, architecture decisions |
| **Thread model** | Architectural tasks; design proposals |
| **Thread index** | `lupo-channels/17/THREAD_INDEX.md` |
| **Allowed actors** | wolfie; athena; lilith (adversarial review) |
| **Special rules** | Major architectural changes require Channel 51 ratification |

### Channel 23 — Migration & Upgrade

| Property | Value |
|----------|-------|
| **Purpose** | Data migrations, schema upgrades, system normalization |
| **Thread model** | Migration tasks; upgrade directives |
| **Thread index** | `lupo-channels/23/THREAD_INDEX.md` |
| **Allowed actors** | hermes; wolfie |
| **Special rules** | Every migration must have rollback plan |

### Channel 31 — External AI / Faucet

| Property | Value |
|----------|-------|
| **Purpose** | External AI integrations; faucet operations |
| **Thread model** | Integration tasks; external routing |
| **Thread index** | `lupo-channels/31/THREAD_INDEX.md` |
| **Allowed actors** | hermes; external agents (wrapped) |
| **Special rules** | External traffic must pass hermetic validation |

### Channel 36 — Unassigned (pending)

| Property | Value |
|----------|-------|
| **Purpose** | **PENDING ASSIGNMENT** |
| **Thread model** | **PENDING ASSIGNMENT** |
| **Thread index** | `lupo-channels/36/THREAD_INDEX.md` |
| **Allowed actors** | **PENDING ASSIGNMENT** |
| **Special rules** | **PENDING ASSIGNMENT** |

### Channel 42 — Protocol Development

| Property | Value |
|----------|-------|
| **Purpose** | Core protocol development; system evolution |
| **Thread model** | Development tasks; protocol directives |
| **Thread index** | `lupo-channels/42/THREAD_INDEX.md` |
| **Allowed actors** | wolfie; protocol agents |
| **Special rules** | Protocol changes require test harness validation |

### Channel 51 — Doctrine Council

| Property | Value |
|----------|-------|
| **Purpose** | Doctrine definition; constitutional updates; rule ratification |
| **Thread model** | Doctrine tasks; ratification threads |
| **Thread index** | `lupo-channels/51/THREAD_INDEX.md` |
| **Allowed actors** | wolfie (orchestrator); athena (audit) |
| **Special rules** | Doctrines must be ratified here before activation |

### Channel 66 — QA / Adversarial Review (the exception)

| Property | Value |
|----------|-------|
| **Purpose** | Contradiction hunting; adversarial testing; quality assurance |
| **Thread model** | **QUESTION-DRIVEN** — every thread is a question |
| **Thread index** | `lupo-channels/66/THREAD_INDEX.md` |
| **Allowed actors** | lilith (primary), wolfie, athena, hermes |
| **Special rules** | Thread = question container; artifact = answer/attack/evidence/review/closure; no artifact without question context; adversarial framing required; artifacts not answering/attacking/providing evidence for the thread question are subject to immediate quarantine; **only channel with question model** |

### Channel 88 — Research / Experiments

| Property | Value |
|----------|-------|
| **Purpose** | Experiments, prototypes, research initiatives |
| **Thread model** | Experimental tasks; research notes |
| **Thread index** | `lupo-channels/88/THREAD_INDEX.md` |
| **Allowed actors** | wolfie; research agents |
| **Special rules** | Experiments cannot modify production without Channel 23 migration |

### Channel 666 — ANUBIS Quarantine

| Property | Value |
|----------|-------|
| **Purpose** | Orphaned files; unknown recipients; quarantine operations |
| **Thread model** | Quarantine tasks; recovery operations |
| **Thread index** | `lupo-channels/666/THREAD_INDEX.md` |
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

