---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  version_when_written: "4.0.94"
  when_updated: "20260404174956"
  file_path_from_root: "lupo-docs/prd/37_kairos_channel_memory_consolidation.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/37_kairos_channel_memory_consolidation.md"
  last_modified_utc: "20260404174956"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-kairos-memory"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "prd"
  artifact_kind: "system_agent_behavior"
  purpose: "Define KAIROS as channel-oriented memory consolidation: ingest multi-actor chat, merge observations, resolve contradictions with recency-first truth and edge-weighted evidence"
  tags:
  - "prd"
  - "kairos"
  - "memory"
  - "channels"
  - "edges"
  - "consolidation"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/prd/02_channels_discussions.md"
      type: references
      weight: 1.0
      reason: "Channel and thread scope"
    - to: "lupo-docs/prd/17_decisions_format.md"
      type: references
      weight: 1.0
      reason: "THREAD_INDEX and thread artifact naming — §10 temporal discipline"
    - to: "lupo-docs/prd/31_implementation_folder_guidelines.md"
      type: references
      weight: 1.0
      reason: "Implementation status/ — add-status tooling; index-first reading"
    - to: "lupo-docs/prd/04_tags_metadata.md"
      type: references
      weight: 0.95
      reason: "lupo_edges semantic graph"
    - to: "lupo-docs/prd/07_agents_faucets.md"
      type: references
      weight: 1.0
      reason: "KAIROS lupo_agents row; internal system agent"
    - to: "lupo-docs/prd/18_channel_chat_display.md"
      type: references
      weight: 1.0
      reason: "Web chat; dialog message source for observations"
    - to: "lupo-docs/prd/36_rose_multi_persona_synthetic_dialog.md"
      type: references
      weight: 0.7
      reason: "Synthetic dialog provenance affects observation trust"
    - to: "lupo-database/lupopedia/toon/lupo_actor_memory.toon"
      type: references
      weight: 1.0
      reason: "Observation and consolidated memory rows"
    - to: "lupo-database/lupopedia/toon/lupo_edges.toon"
      type: references
      weight: 1.0
      reason: "Consolidation and contradiction edges"
    - to: "lupo-database/lupopedia/toon/lupo_dialog_messages.toon"
      type: references
      weight: 1.0
      reason: "Canonical chat row shape for ingest provenance"
    - to: "app/Services/Kairos/KairosConsolidationService.php"
      type: references
      weight: 1.0
      reason: "Current PHP consolidation implementation"
lupopedia.footer:
  last_verified: "20260404174956"
  verified_by:
    identity_type: "agent"
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent"
    department_id_delta: 0
  verified_via:
    type: "direct"
    faucet_slug: "cursor"
  orchestrator: "cursor:root"
  next_action:
    - "Channel-scoped observation ingest from lupo_dialog_messages (all actors in channel)"
    - "Implement recency-first winner for same topic_key contradictions + edge-weight overrides"
    - "Optional: link memory rows to dialog_message_id via context_json or edges"
---

# file: PRD 37 — KAIROS channel memory consolidation — web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/prd/37_kairos_channel_memory_consolidation.md](http://www.lupopedia.com/lupopedia/lupo-docs/prd/37_kairos_channel_memory_consolidation.md)

# PRD 37: KAIROS channel memory consolidation

## 1. Overview

**Problem.** Channel chat mixes **many actors** (humans, facets, synthetic persona lines). Operators and agents need a **durable, de-duplicated memory** of **what the channel collectively said**, not only a scrolling transcript. **KAIROS** is the **system agent** responsible for **taking notes** (observations), **consolidating** them into stronger memory rows, **surfacing contradictions**, and preferring **coherent truth** under explicit rules.

**Namespace purpose.** This PRD defines **product behavior**, **truth rules**, and **data contracts** for KAIROS in the **web chat** context. Runtime orchestration is **PHP-first** (`KairosConsolidationService`, `POST api/lupo-kairos/tick`); LLM is optional assist only where doctrine allows—never the sole authority for stored memory.

**KAIROS identity (tooling).** Configuration lives under **`lupo-agents/kairos/`**; registry **`kairos`** maps to **`actor_id` 115** for **edge attribution** (`lupo_edges.actor_id`) and service defaults. KAIROS does **not** post chat bubbles as part of this PRD; it **writes `lupo_actor_memory` and `lupo_edges`**.

### 1.1 Correct mental model

| Concept | Definition |
|--------|------------|
| **Observation** | A **`lupo_actor_memory`** row with **`memory_type` = `kairos_observation`**, capturing a **atomic note** (often derived from dialog or manual seed), with **`context_json.kairos`** carrying **confidence**, **topic_key**, **department_id**, and **provenance** (e.g. source **`dialog_message_id`**, **`channel_id`**, **`from_actor_id`**). |
| **Consolidated memory** | A row with **`memory_type` = `kairos_memory`**, produced by merging **multiple** observations that **normalize** to the same text; linked via **`kairos_consolidates_from`** edges. |
| **Contradiction** | Two or more consolidated (or candidate) memories sharing a **`topic_key`** but **different** normalized **`memory_value`**; recorded with **`kairos_contradicts`** edges on **`lupo_edges`**. Resolution **policy** is defined below (recency + edges). |
| **Channel scope** | **Target:** memory represents **the channel as a whole** (all participating actors). **Current implementation note:** `KairosConsolidationService::consolidateMemories($actorId, …)` operates on observations keyed by a **single `actor_id`** (typically the **logged-in session actor**). **Full channel-wide consolidation** requires **ingest** that writes observations with **shared channel topic keys** and/or a **channel-scoped consolidation pass**—see §9. |

This output complies with Lupopedia Constitutional Root Rules.

---

## 2. Goals

1. **Background “note taking.”** When KAIROS is **enabled** on a channel view (e.g. periodic tick from **`chat-display.js`**), the system **updates memory artifacts** without blocking the live transcript (**PRD 18**).
2. **Multi-actor awareness (target).** Consolidated understanding reflects **contributions from all actors** in the channel, not only the viewer’s **`actor_id`**, once ingest is wired to **`lupo_dialog_messages`** per **`channel_id`**.
3. **De-duplication.** Multiple near-identical observations collapse into **one** consolidated row with **raised confidence** (existing PHP pattern: merge by normalized text).
4. **Contradiction visibility.** Conflicting statements on the **same `topic_key`** produce **`kairos_contradicts`** edges so UIs and downstream agents can **see tension**, not silent overwrite.
5. **Truth ordering — recency first.** When policies must choose a **canonical** line for a topic, **prefer the most recently substantiated claim** (by **`created_ymdhis` / `updated_ymdhis`** of the **evidence** or observation), unless **edge-validity** overrides (§5).
6. **Edge-informed validity.** Treat **`lupo_edges`** (and future typed links from memory to **dialog rows**, **truth artifacts**, **files**) as **evidence**: **higher-weight**, **verified**, or **human-attested** edges can **outrank** raw recency for **tie-break** and **confidence** adjustment.

---

## 3. Non-goals

- **Replacing** the transcript; memory is a **derived index**, not the legal log of who said what.
- **Automatic deletion** of chat messages or **hard deletes** of memory (soft delete only per doctrine).
- **Claiming** KAIROS output as **human legal commitment** or **immutable fact** without review surfaces.
- **Cross-channel** global brain without explicit **federation / scope** rules (**PRD 34** deferred patterns).

---

## 4. User-visible behavior (web chat)

1. **Opt-in.** Channel or deployment config enables KAIROS ticks (e.g. **`kairosTickIntervalMs` > 0** in **`channel.php` / chat config**). Default may be **off** or a **long** interval.
2. **No chat spam.** KAIROS does **not** inject dialog lines by default; optional future “memory summary” messages are **out of scope** unless a separate PRD adds them.
3. **Operator surfaces (future).** Admin or channel tools may show **consolidated memories**, **contradiction graph**, and **confidence**—exact UI is implementation follow-up.

---

## 5. Truth and contradiction policy

### 5.1 Recency as higher truth (default)

For a fixed **`topic_key`**:

1. Sort candidate claims by **evidence time**: newest **`created_ymdhis`** (or **`updated_ymdhis`** when the row was **re-affirmed**) **wins** as **default canonical** for **downstream consumers** that need a single string.
2. **Older** conflicting consolidated rows remain **stored** but marked **superseded** or **lower rank** in **`context_json.kairos`** (implementation-specific field names to be stabilized in Phase B).

### 5.2 Edge-based overrides

Adjust effective truth using **`lupo_edges`** (see TOON):

- **`weight_score`**, **`semantic_weight`**, **`flare_verified`**, **`relationship_type`**, and **`properties`** MAY **boost** or **demote** a claim **relative to recency**.
- Example policy fragment: a memory linked by an edge to a **verified** truth record **outranks** a newer but **unlinked** chat-derived observation **if** semantic weight exceeds a **configured threshold**.
- **KAIROS-specific edges today:** **`kairos_consolidates_from`**, **`kairos_contradicts`**. **Future:** edges from **`actor_memory`** to **`dialog_message`** (or **`metadata`**) as **evidence_of**.

### 5.3 Synthetic dialog (**PRD 36**)

Observations derived from messages with **`metadata_json.rose_synthesis: true`** MUST carry **lower default confidence** than organic dialog unless **independently verified** by edges or human action.

---

## 6. Data model (TOON-aligned)

**`lupo_actor_memory`** (`lupo-database/lupopedia/toon/lupo_actor_memory.toon`):

- **`memory_id`**, **`actor_id`**, **`memory_type`**, **`memory_key`**, **`memory_value`**, **`context_json`**, UTC BIGINT timestamps, soft delete.

**`lupo_edges`** (`lupo-database/lupopedia/toon/lupo_edges.toon`):

- **`edge_type`**: at minimum **`kairos_consolidates_from`**, **`kairos_contradicts`** (existing PHP constants).
- **`left_object_type` / `right_object_type`**: use **`actor_memory`** for memory-to-memory links; future **`dialog_message`** endpoints as needed.
- **`channel_id` / `channel_key`**: SHOULD be populated when the edge is **channel-scoped** (currently often null in code—**gap**).

**`lupo_dialog_messages`:** source for **ingest** provenance (**`dialog_message_id`**, **`from_actor_id`**, **`channel_id`**, **`created_ymdhis`**, **`metadata_json`**).

---

## 7. Security and authority

1. **API:** **`POST api/lupo-kairos/tick`** requires **authenticated** session; uses **current user’s `actor_id`** for consolidation scope today—document **narrowing** vs **channel admin** path when channel-wide jobs are added.
2. **No client-forged memory.** Observations MUST be created **server-side** with validated **`channel_id`** membership checks where applicable.
3. **PDO_DB only**; explicit IDs per constitutional rules.

---

## 8. Configuration surface

- **Tick interval** (client): **`kairosTickIntervalMs`**; server rate limit (e.g. **90s** session throttle in **`kairos-api.php`**).
- **Department filter:** optional **`department_id`** in JSON body (existing).
- **Future:** **`channel_id`** on tick for **channel-scoped** consolidation job (operator or cron).

---

## 9. Implementation phases (dependency order)

1. **Phase A — Provenance in `context_json`**  
   Every **`kairos_observation`** SHOULD record **`channel_id`**, **`source_dialog_message_id`**, **`source_actor_id`**, and **`topic_key`** when derived from chat.
2. **Phase B — Ingest pipeline**  
   Server job: new dialog messages (since cursor) → **`recordObservation`** or batch insert — **for all actors** in the channel (or per deployment policy).
3. **Phase C — Channel consolidation pass**  
   Extend service (or sibling class) to **bucket / merge / contradict** on **`channel_id` + `topic_key`**, not only **one `actor_id`**.
4. **Phase D — Recency winner + edge scoring**  
   Implement §5.1–5.2 in code: **canonical pointer** in **`context_json`** or auxiliary rollup table (**`lupo_memory_rollups`** TOON exists—investigate fit before use).
5. **Phase E — UI**  
   Operator visibility for memories and contradictions.

**Completion criteria (MVP for this PRD).** A test channel with **two actors** stating conflicting facts on the **same `topic_key`** yields a **`kairos_contradicts`** edge **and** a **deterministic canonical** choice per §5 when the tick runs; provenance links back to **dialog** rows.

---

## 10. Temporal discipline / anti-backwards reads

### 10.1 The problem

A **file system** is ordered by name or modification time. **Conversation and implementation truth** are not necessarily linear. An agent that reads **`status/`** (or **`decisions/`**, **`answers/`**) in **directory listing order** may see the **end** of a thread before the **why**, and treat stale text as current.

### 10.2 Index-first reading (normative for agents and tooling)

1. **Parse `THREAD_INDEX.md` first** in each typed subfolder (**`status/`**, **`decisions/`**, **`questions/`**, **`answers/`**, **`comments/`**) before relying on individual dated artifacts (**PRD 17**, **PRD 31**).
2. **Use the index** to determine **authoritative reading order** and **what exists** — not raw filesystem sort.
3. **Follow `lupopedia.edges`** for **lineage** between artifacts — not filename patterns alone.

### 10.3 Freshness sorting hierarchy (fallback when index is missing or incomplete)

When **`THREAD_INDEX.md`** does not establish order, use this **deterministic** hierarchy:

| Priority | Source | Format | Example |
|----------|--------|--------|---------|
| 1 | UTC in filename | `YYYYMMDD_HHIISS` prefix | `20260404_120000_STATUS_....md` |
| 2 | Header `when_updated` | 14-digit `YYYYMMDDHHIISS` | `when_updated: "20260404120000"` |
| 3 | Header `last_modified_utc` | 14-digit `YYYYMMDDHHIISS` | `last_modified_utc: "20260404120000"` |

**KAIROS** and other consolidators **SHALL** sort ingest candidates by this hierarchy before merging or choosing canonical rows. **SHALL NOT** use filesystem **mtime** as primary ordering.

### 10.4 Edge semantics for implementation / status lineage

| Edge type | Meaning | Use when |
|-----------|---------|----------|
| **`supersedes`** | New artifact **replaces** prior truth | Prior text is **invalid** or **obsolete** (e.g. retracted status, replaced schema decision) |
| **`references`** | New artifact **continues** prior thread | Prior remains **valid**; this **adds** context (typical **incremental status**) |
| **`has_answer` / `has_question`** | Q&A thread linkage | Per **PRD 17** sibling artifacts |

**Guidance:** **`supersedes`** is **not** friction — it signals **replacement**. **`references`** signals **extension**. Agents **MUST** distinguish them when writing headers.

### 10.5 Required behavior for KAIROS (documentation contract)

- **SHALL** treat **`THREAD_INDEX.md`** and header **`when_updated`** / **`lupopedia.edges`** as **stronger** ordering signals than directory order when ingesting **file-backed** provenance into **`lupo_actor_memory`** (where applicable).
- **SHALL** respect **`supersedes`** edges: **do not** treat superseded file content as **current** truth when a successor exists in the same lineage.
- **SHALL** use **`references`** edges to **walk** conversation or implementation threads.
- **SHALL NOT** rely on **filesystem modification time** as the **primary** sort key for truth.

**Tooling:** **`python lupo-scripts/scaffold_implementation.py add-status`** creates a dated **`status/`** file, optional **`references`** / **`supersedes`** edge to the **prior** status artifact, and appends **`status/THREAD_INDEX.md`**.

### 10.6 Channel chat context reading (multi-actor simple pattern)

**Applies when** KAIROS (or tooling) consumes **`lupo_dialog_messages`** rows for consolidation — alongside §10.1–10.5 for **file-backed** artifacts.

#### Full thread context

KAIROS **SHALL** read **all** messages in the relevant **`channel_id`** + **`dialog_thread_id`** scope **regardless of `to_actor_id`**. **Rationale:** “who said what to whom” requires the **entire** transcript, not only lines addressed to the consolidator.

**Canonical routing column:** **`to_actor_id`** (NULL = broadcast). **Synonym in doctrine:** **said-to** / **`said_to_actor_id`** — **routing only**, **not** visibility (**PRD 18**, **PRD 36** §1.3).

#### Lineage within chat rows

- **Today:** Ordering and threading **primarily** use **`dialog_thread_id`**, **`created_ymdhis`**, and (where populated) **`message_id`** / UI reply metadata — see TOON.  
- **Future (optional DDL):** A first-class **`parent_dialog_message_id`** on **`lupo_dialog_messages`** **may** be added in **install SQL** when ratified; until then **do not** assume that column exists in TOON. KAIROS **MAY** still use **`metadata_json`** subkeys for **provenance** when the product defines them, without introducing a **`mention_actor_ids`** column.

#### Use of **`to_actor_id`** in consolidation

KAIROS **SHALL** use **`to_actor_id`** to:

1. Interpret **conversation flow** (addressed vs broadcast).  
2. Inform **relationship / expectation** signals (who was expected to answer).  
3. **Not** to **exclude** messages from memory merge when the reader is a channel member.

#### File-backed temporal discipline (unchanged)

For **implementation-folder** markdown, KAIROS **SHALL** still apply **§10.2–10.5** (**`THREAD_INDEX.md` first**, **`supersedes` / `references`**, freshness hierarchy). **SHALL NOT** use filesystem **mtime** as primary order (**§10.3**).

---

## 11. Open questions

1. Should **channel memory** rows live under a **dedicated system `actor_id`** (e.g. **115** only) vs **per human actor** memory partitions?
2. **Retention:** cap observations per channel / TTL?
3. **GDPR / deletion:** how **soft delete** propagates from dialog to memory?

---

## 12. References (summary)

- **Implementation:** `app/Services/Kairos/KairosConsolidationService.php`, `lupo-includes/modules/api/kairos-api.php`, `lupo-ui/js/chat-display.js`  
- **Agents:** `lupo-agents/kairos/agent.json`, **PRD 07**  
- **Chat:** **PRD 18**  
- **Edges namespace:** **PRD 04**  
- **Synthetic dialog interplay:** **PRD 36**
