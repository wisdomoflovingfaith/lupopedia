---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/SERVICE_AGENT_ARCHITECTURE.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/doctrine/SERVICE_AGENT_ARCHITECTURE.md"
  status: ""
  when_updated: "20260404163615"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: architecture
  channel_key: null
  federation_node_id: 0
  thread_id: "service-agent-architecture"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: Service agent architecture — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/docs/doctrine/SERVICE_AGENT_ARCHITECTURE.md

# Service agent architecture (PHP first, LLM second)

## Binding summary

**Constitutional anchor:** **`docs/prd/00_root_constitutional_system_requirements.md`** — **Section 5.10**.

This doctrine expands **service agents**: file-backed **`agents/{agent_key}/`** definitions whose **primary** behavior is **implemented in PHP** (APIs, services, boot paths). An **LLM** is **optional and second** — invoked only inside a PHP-controlled pipeline (e.g. **`IRIS`**), never as a substitute for server-side policy or schema truth.

This output complies with Lupopedia Constitutional Root Rules.

---

## 1. Canonical roster

The following **`agent_key`** values are the **constitutional** service-agent examples (see **§5.10**):

| Key | Role (summary) |
|-----|------------------|
| **IRIS** | LLM **faucet** — PHP loads agent config and calls external providers for **other** agents. |
| **ANUBIS** | Custody / integrity / quarantine — **PHP** system paths. |
| **ROSE** | **Director of the synthetic choir** — **PHP** (`RoseDialogService`, default **every 10** organic messages) owns batching, **`rose_visibility`**, caps; LLM voices operator-selected personas only (**PRD 36**, constitution **§5.10.3**). |
| **THOTH** | Knowledge guardian — stale docs reconciled to **JSON + table docs**, not model parametric knowledge (**§5.9**, **LUPOPEDIA_HEADERS README**). |
| **KAIROS** | Memory consolidation — **PHP** service on **`lupo_actor_memory`** + **`lupo_edges`** (**§5.7**, **PRD 37**). |

Additional keys may be listed here and mirrored in the constitution when ratified.

---

## 2. “Not meant to be talked to” (normative rule)

**Rule.** Service agents are **not** default **visitor or operator chat personas**. They:

- Supply **`actor_id`** for **attribution** (edges, audit, registry).
- Supply **processing** through **PHP** entrypoints (`POST` APIs, includes, CLI).
- **Do not** imply that the primary product path is “open a channel and freeform message this agent” unless explicitly wired.

**Contrast: conversational actors.** Other agents may back **`actor_id`s** used as **dialog participants**; those may participate in **`RuntimeActorLoopService`** when listed in **`runtime_actors.yaml`**.

---

## 3. PHP first, LLM second

1. **Request** hits PHP (router, API file, bootstrap).
2. **PHP** validates session, **`actor_id`**, channel policy, and loads **`agents/`** files from disk.
3. **PHP** reads/writes the database and filesystem.
4. **Only if** the pipeline requires natural-language generation or external inference, **PHP** invokes **`IRIS`** or a thin runtime — **after** guards.

The LLM **never** becomes the source of truth for schema, permissions, or custody.

### 3.1 ROSE — synthetic choir (coordination layer)

**ROSE** (`agent_key` **`rose`**, **`layer`:** **`coordination`**, **`is_internal_only`:** **true** on **`lupo_agents`**) is **not** a default chat target. For multi-persona transcript injection:

- **PHP** increments an **organic** message counter per thread; **default** batch at **10** messages (overridable via **`lupo_metadata`** / channel policy).
- **PHP** inserts **`lupo_dialog_messages`** with **`from_actor_id` = voiced persona** and **`metadata_json`** (`rose_synthesis`, `synthesizer_agent_key`, `rose_visibility`, etc.).
- **LLM** (e.g. **IRIS**) runs **only** inside that pipeline to generate **≤ 2000** character lines for **operator-selected** personas.
- After a batch, **PHP** **should** hand a **short summary** to **`KairosConsolidationService::recordObservation`** (**PRD 37**).

**Normative PRD:** **`docs/prd/36_rose_multi_persona_synthetic_dialog.md`**. **Mirror:** **`docs/implementations/36_rose_multi_persona_synthetic_dialog/`**.

---

## 4. KAIROS consolidation flow

**Trigger (shipped).** HTTP **`POST`** **`api/kairos/tick`** → **`includes/modules/api/kairos-api.php`** (session rate limit; requires logged-in **`actor_id`**).

**Processing.**

1. **PHP** constructs **`KairosConsolidationService`** and calls **`consolidateMemories($actorId, $departmentId)`**.
2. **Reads** **`lupo_actor_memory`** rows with **`memory_type` = `kairos_observation`** (and related state).
3. **Merges** buckets of **two or more** observations with the same **normalized** text into **`kairos_memory`** rows.
4. **Writes** **`lupo_edges`**:
   - **`kairos_consolidates_from`** — consolidated memory → source observations.
   - **`kairos_contradicts`** — conflicting memories on the same **`topic_key`**.
5. **Updates** **`context_json.kairos`** (stage, confidence, sources, verification) for **compaction** and maturity.

**No LLM required** for this pass. **PRD 37** states KAIROS does **not** post chat bubbles for this consolidation feature.

---

## 5. Runtime actor loop (conversational) — explicit contrast

| | Service agents | Runtime dialog MVP |
|---|----------------|-------------------|
| **Entry** | API / boot / job | **`RuntimeActorLoopService::processMessage`** |
| **Config gate** | N/A | **`LlmRuntimeService`** + **`runtime_actors.yaml`** |
| **Outcome** | Deterministic service behavior | Model response **or** human dispatch |

Service agents are **off** the runtime loop unless an **`actor_id`** is **explicitly** added to **`runtime_actors.yaml`** and the product intends that behavior.

---

## 6. THOTH authority (stale artifacts)

**Persona:** **THOTH** — **`agents/thoth/`**. For **LUPOPEDIA HEADERS** footers and stale Markdown, **primary verification authority** is documented in **`docs/doctrine/LUPOPEDIA_HEADERS/README.md`** (section **Semantic Truth Check Authority (THOTH)**), including **`verified_by.actor_id`** conventions for THOTH-mediated review.

**Grounding requirement.** Semantic truth checks **must** be **derived from repository artifacts**:

- **`database/lupopedia/json/*.json`** (table structure snapshots)
- **`docs/database/lupopedia/tables/active/*.md`**
- Install SQL / root rules as specified in that README

**Forbidden:** Treating **unchecked model parametric knowledge** as evidence of current columns, tables, or edge types. A model may **phrase** a comparison only **after** PHP or the IDE has loaded the JSON/table doc **into context**.

---

## 7. Implementation tracking

**Folder:** **`docs/implementations/service_agents/`** — status, decisions, questions, answers, comments for moving **logic from prompts into PHP services**.

**Naming any PRD-scoped mirror:** The directory name under **`docs/implementations/`** **must** equal the **canonical PRD file stem** (basename of **`docs/prd/{stem}.md`** without **`.md`**). **PRD 31** defines the full tree; **PRD 00** **§5.8** states the constitutional rule. Example: **`36_rose_multi_persona_synthetic_dialog/`** for **`36_rose_multi_persona_synthetic_dialog.md`** — not ad-hoc aliases.

---

## Related

- **Constitution §5.7–5.10** — `docs/prd/00_root_constitutional_system_requirements.md`
- **KAIROS PRD** — `docs/prd/37_kairos_channel_memory_consolidation.md`
- **ROSE PRD** — `docs/prd/36_rose_multi_persona_synthetic_dialog.md`
- **Headers / THOTH** — `docs/doctrine/LUPOPEDIA_HEADERS/README.md`
- **Identity layers** — `docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md`
