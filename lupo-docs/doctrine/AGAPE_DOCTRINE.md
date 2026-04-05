---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260404173921"
  file_path_from_root: "lupo-docs/doctrine/AGAPE_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/AGAPE_DOCTRINE.md"
  last_modified_utc: "20260404173921"
  federation_node_id: 0
  channel_id: 42
  thread_id: "agape-technical-doctrine"
  artifact_type: doctrine
  artifact_kind: constitutional_companion
  author:
    type: actor
    id: 102
    name: CURSOR
  delegation_chain: "cursor:root"
  purpose: "Canonical expansion of constitutional §14.6 AGAPE — technical resilience, LILITH review prompts, ROSE cooperation metadata, validator phrase bans"
  tags:
    - agape
    - resilience
    - environment_awareness
    - validators
    - lilith
    - rose
    - kairos
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Constitutional §14.6 binding summary"
    - to: "lupo-docs/prd/36_rose_multi_persona_synthetic_dialog.md"
      type: references
      weight: 1.0
      reason: "ROSE synthetic metadata — agape_cooperation_metric"
    - to: "lupo-docs/prd/37_kairos_channel_memory_consolidation.md"
      type: references
      weight: 0.95
      reason: "Agentic Guidance class — consolidation when new truth is learned"
    - to: "lupo-docs/doctrine/CASCADE_FALLBACK_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Practical Empathy expressed as fallback ladders"
    - to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md"
      type: references
      weight: 1.0
      reason: "Sentimental phrase flagging for validators"
    - to: "lupo-rules/root/lilith-noninterference-doctrine.md"
      type: references
      weight: 0.95
      reason: "LILITH review alignment questions"
lupopedia.footer:
  last_verified: "20260404"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: "cursor:root"
  next_action:
    - "Implement optional agape_cooperation_metric in RoseDialogService when Phase B ships"
    - "Temporal discipline: PRD 37 §10; dated status via scaffold_implementation.py add-status"
---
# file: AGAPE doctrine — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/AGAPE_DOCTRINE.md

# AGAPE doctrine (Agentic Guidance And Practical Empathy)

## Binding anchor

**Constitutional law:** **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** — **§14.6**.

This file is the **canonical expansion**: definitions, **LILITH** review prompts, **ROSE** `metadata_json` keys, and **validator** expectations. It does not replace the constitution.

**AGAPE** is a **non-sentimental technical philosophy** governing **system resilience** and **inter-actor cooperation**. It is **not** therapeutic language, marketing warmth, or a substitute for measurable engineering.

This output complies with Lupopedia Constitutional Root Rules.

---

## 1. Terms (technical)

### 1.1 Agentic Guidance

The system’s ability to **autonomously identify** logic gaps, outdated doctrine, or technical debt and to surface **actionable** improvement paths for the **runtime** or **human operator** (documentation updates, refactors, installer checks, schema alignment).

**KAIROS alignment:** Consolidation and archival when the platform has **learned something new** that should persist for the operator’s benefit is an **Agentic Guidance** behavior class — see **PRD 37** and **`KairosConsolidationService`**.

### 1.2 Practical Empathy

**Deterministic** understanding of **environmental constraints** (shared hosting, OS path quirks, PHP version bands, missing extensions, channel visibility rules) and the **contextual state** of other actors (membership, offline fallbacks, federation scope).

It is **expressed** through **graceful degradation** and **fallback ladders** — e.g. **`function_exists()`**, **`extension_loaded()`**, documented manual config paths when the wizard cannot write, and **PDO_DB**-safe failure surfaces — not through “tone.”

### 1.3 Temporal awareness (Practical Empathy)

Understanding the **previous state** of an implementation thread (what was true **before** this edit) is part of **Practical Empathy** for other actors and for **future** readers: it prevents **wrong conclusions** when files are opened **out of chronological order**.

- **Agents MUST** establish **temporal ordering** before treating a **`status/`** or **`decisions/`** artifact as **current** truth.
- **Agents MUST** distinguish **`supersedes`** (replacement) from **`references`** (continuation) in **`lupopedia.edges`**.
- **Agents MUST** read **`THREAD_INDEX.md`** in that folder **first** when present (**PRD 17**, **PRD 31**).

**Canonical specification:** **PRD 37** — **Section 10** (*Temporal discipline / anti-backwards reads*). **Tooling:** **`scaffold_implementation.py add-status`**.

---

## 2. LILITH review: AGAPE alignment (analysis framework)

Under **AGAPE**, reviewers (including **LILITH**, **actor_id 2**) MUST **not** score artifacts on “empathy,” “love,” or “supportiveness.”

**Replace** such checks with:

1. **Environment:** Does this code **probe** and **branch** on the real deployment surface (extensions, permissions, server software, subdirectory URLs), or does it assume a perfect workstation?
2. **Fallbacks:** Does it provide **unconditional** degradation paths so the system **survives** on constrained hosts (clear errors, alternate code paths, filesystem fallback per **DB008** where applicable)?
3. **Inter-actor truth:** Does it respect **channel membership**, **visibility**, and **LIL001** (synthetic vs organic attribution) without conflating personas?

A “yes” cluster indicates **AGAPE-aligned** engineering; sentiment is **out of scope** for pass/fail.

---

## 3. ROSE synthetic dialogue: cooperation metric (`metadata_json`)

When **ROSE** inserts synthetic **`lupo_dialog_messages`** rows (**PRD 36**), **AGAPE** is a **cooperation metric**: it measures how well the **voiced persona’s** text reflects the **human operator’s current state and dependencies** so the line delivers **useful guidance**, not **agreeable noise**.

**Optional** keys (PHP may set post-generation or after human/LILITH review):

| Key | Type | Meaning |
|-----|------|--------|
| **`agape_cooperation_metric`** | object | Structured assessment; see §3.1 |
| **`agape_cooperation_rationale`** | string | Short audit note (why the metric was chosen) |

Omission is allowed until **Phase B** implements scoring; when present, clients MUST NOT treat these keys as visitor-facing “mood” — they are **provenance / QA**.

### 3.1 Suggested shape for `agape_cooperation_metric`

All fields optional; expand in implementation only with **PRD** and **TOON** review:

- **`addresses_operator_dependencies`** (boolean) — text names concrete constraints the operator faces (e.g. shared hosting, PHP band, channel policy) when context supplies them.
- **`actionable_next_step`** (boolean) — text proposes a **deterministic** next action (config key, file to edit, doctrine pointer), not generic praise.
- **`agreeable_noise_risk`** (string enum) — `low` \| `medium` \| `high` — **high** means the line is pleasant but **empty of technical utility** under review.

Validators and **LILITH** may flag **`agreeable_noise_risk: high`** for regeneration or rejection on internal channels.

---

## 4. Validator and product-language prohibitions

The following MUST **NOT** be used as **acceptance criteria**, **definition of done**, or **pass/fail** semantics for Lupopedia artifacts or IDE rule packs:

- **“made with love”** (any casing)
- **“supportive tone”**
- **“emotional validation”**

**Validators** MUST **flag** occurrences as **constitutional violations** against **§14.6** (sentimental framing of **technical** quality). **THOTH** / reviewers replace such criteria with **observable** checks (headers, schema, fallback paths, tests).

Historical documents may still contain legacy phrasing; **new** artifacts MUST NOT introduce these strings as requirements.

---

## 5. Relationship to `lupo_agents` key **agape** (705)

The **`lupo-agents/agape/`** pack names the **AGAPE** coordination lens. Its **runtime meaning** is this doctrine — **environment-aware resilience** and **self-teaching** documentation/code paths — **not** “universal love” as a product feature string.

---

## 6. References

| Topic | Location |
|--------|----------|
| Constitutional summary | **PRD 00** §14.6 |
| Multi-environment patterns | **PRD 00** §15 |
| WOLFIE survival rules | **`lupo-rules/root/WOLFIE_DOCTRINE.md`** |
| ROSE synthetic contract | **PRD 36** |
| KAIROS consolidation | **PRD 37**, **`KairosConsolidationService`** |
| Header validators | **`lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md`** |
