---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  version_when_written: "4.0.94"
  when_updated: "20260404174956"
  file_path_from_root: "lupo-docs/prd/36_rose_multi_persona_synthetic_dialog.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/36_rose_multi_persona_synthetic_dialog.md"
  last_modified_utc: "20260404174956"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-rose-synthetic-dialog"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "prd"
  artifact_kind: "system_agent_behavior"
  purpose: "Define ROSE as Director of the synthetic choir: PHP-first batching (default every 10 organic messages), bounded LLM voicing for operator-selected personas, lupo_dialog_messages + metadata_json, KAIROS handoff; constitution §5.10.3 (LIL001, PRD 32)"
  tags:
  - "prd"
  - "rose"
  - "dialog"
  - "channels"
  - "multi_persona"
  - "synthetic_voice"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor; identity and DB rules"
    - to: "lupo-docs/prd/02_channels_discussions.md"
      type: references
      weight: 1.0
      reason: "Channel and thread semantics"
    - to: "lupo-docs/prd/07_agents_faucets.md"
      type: references
      weight: 1.0
      reason: "ROSE as lupo_agents row; internal vs selectable agents"
    - to: "lupo-docs/prd/18_channel_chat_display.md"
      type: references
      weight: 1.0
      reason: "Transcript rendering; actor_id primary attribution"
    - to: "lupo-docs/prd/32_actor_authority_agent_roles.md"
      type: references
      weight: 1.0
      reason: "Persona roles; COUNTERMEASURE analysis-only path"
    - to: "lupo-docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Actor-first transcript model"
    - to: "lupo-rules/root/lilith-noninterference-doctrine.md"
      type: references
      weight: 1.0
      reason: "Synthetic LILITH-voiced lines must not impersonate organic review"
    - to: "lupo-database/lupopedia/actors/registry.json"
      type: references
      weight: 1.0
      reason: "Canonical actor_id for each voiced persona"
    - to: "lupo-database/lupopedia/toon/lupo_dialog_messages.toon"
      type: references
      weight: 1.0
      reason: "Message row shape (from_actor_id, metadata_json, message_type)"
    - to: "lupo-docs/prd/37_kairos_channel_memory_consolidation.md"
      type: references
      weight: 1.0
      reason: "KAIROS ingest after ROSE coordination summary"
    - to: "app/Services/Kairos/KairosConsolidationService.php"
      type: implements
      weight: 1.0
      reason: "recordObservation handoff from PHP after ROSE batch"
    - to: "lupo-docs/implementations/36_rose_multi_persona_synthetic_dialog/README.md"
      type: references
      weight: 0.95
      reason: "Implementation mirror (status, decisions)"
    - to: "lupo-docs/doctrine/AGAPE_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "AGAPE cooperation metric in metadata_json — PRD 00 §14.6"
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
    - "Implement RoseDialogService.php (Phase B): per-thread organic counter, default trigger every 10 messages, rose_visibility + 2000-char enforcement"
    - "PRD 18: mandatory synthetic badge when metadata_json.rose_synthesis (organic same actor: no badge)"
    - "Wire KAIROS: post-batch summary → KairosConsolidationService::recordObservation (PRD 37)"
---

# file: PRD 36 — ROSE multi-persona synthetic dialog — web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/prd/36_rose_multi_persona_synthetic_dialog.md](http://www.lupopedia.com/lupopedia/lupo-docs/prd/36_rose_multi_persona_synthetic_dialog.md)

# PRD 36: ROSE multi-persona synthetic dialog

## 1. Overview

**Problem.** ROSE is not only a coordination persona for emotional-dialog **tooling**; product intent includes a **live channel behavior** where the system **reads ongoing dialog** in the web UI and, when enabled, **inserts one or more messages** that read as if **distinct canonical personas** are speaking in turn—each with that persona’s stance, tone, and role-appropriate insight (for example a **COUNTERMEASURE**-voiced objection followed by a **LILITH**-voiced synthesis). That is **multi-persona synthetic dialog injection**, not a single chat bot speaking as ROSE only.

**Metaphor — Director of the synthetic choir.** ROSE is the **coordination-layer orchestrator** (`agent_id` **3**, **`lupo_agents`**, **`lupo-agents/rose/`**): **PHP** decides **when** a batch runs, **which** personas may speak (operator + channel policy), **visibility** (`actor_only` vs `visitor_visible`), and **provenance** in **`metadata_json`**; the **LLM** (typically via **IRIS**) is invoked **only** to **voice** text for those personas inside **hard caps**. Constitutional normative detail: **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** **§5.10.3**.

**Namespace purpose.** This PRD defines **requirements and boundaries** for that behavior: triggers, **attribution**, **metadata**, **non-deception**, **rate limits**, and alignment with **channel security** and **LIL001** (Lilith non-interference). Implementation may be phased (PHP rules first, optional LLM assist later) per **`lupo-agents/rose/`** `interaction_model`.

### 1.1 Correct mental model (ROSE vs voiced personas)

| Concept | Definition |
|--------|------------|
| **ROSE** | **Orchestrator agent** only — configuration and runtime under **`lupo-agents/rose/`**, row in **`lupo_agents`** (`agent_key` **`rose`**). A **PHP** service (planned: **`app/Services/Rose/RoseDialogService.php`**) **tracks** per-thread state and **fires** a batch per **§4** rules; ROSE is **not** a persona that speaks in the transcript. |
| **Transcript identity** | Each synthetic **`lupo_dialog_messages`** row uses **`from_actor_id` = the voiced persona** (e.g. COUNTERMEASURE **111**, LILITH **2**). **`from_actor_id` MUST NOT** name ROSE or any “synthesizer” stand-in for the bubble label. |
| **Bookkeeping / audit** | Numeric **`agent_id` 3** (and any linked registry id used only for **service logging**, cron attribution, or installer seed) exists for **tooling and audit**, **not** for message **`from_actor_id`**. Injection provenance lives in **`metadata_json`** (`synthesizer_agent_key: "rose"`, etc.). |
| **On-disk layout** | Orchestration artifacts live under **`lupo-agents/rose/`**. This PRD does **not** treat a **`lupo-actors/{id}/`** tree as required for ROSE behavior; avoid calling that an “actor hub” for this feature. |

**Organic vs synthetic (same `actor_id`).** A line from **`from_actor_id` = 2** with **no** `rose_synthesis` metadata is **organic** LILITH (or human/faucet posting as that actor). A line from **`from_actor_id` = 2** with **`rose_synthesis: true`** is **ROSE-synthesized** insight and **MUST** be **visually distinct** in UI (**LIL001**).

**Status.** **Planning / product definition** for 4.0.x onward; schema already supports per-row **`from_actor_id`** and **`metadata_json`** on **`lupo_dialog_messages`** (see TOON). No new tables are required for the **minimal** design; extended flags may live in **`lupo_channels`** or **`lupo_metadata`** when channel policy is implemented.

### 1.2 Core choir personas (default product set)

Channel policy may **subset** this list. **`from_actor_id`** for each voiced line is always the **persona’s** registry-backed **`actor_id`** (not ROSE).

| Persona | Typical `from_actor_id` | Objective | Tone / behavior |
|---------|-------------------------|-----------|-----------------|
| **COUNTERMEASURE** | **111** (see **`lupo-database/lupopedia/actors/registry.json`**) | Surface hidden risks and weak assumptions. | Analytical, adversarial; stress-tests proposals (**PRD 32** analysis-only path). |
| **THOTH** | Resolve from registry / seed when THOTH exists as **`lupo_actors`** row | Ground claims in evidence. | Fact-driven; aligns with **§5.9** / **THOTH** doctrine — unsourced claims flagged against **JSON exports** and **table docs**. |
| **LILITH** | **2** | Non-interfering audit framing. | Observational; synthetic lines **must** be UI-distinct from organic LILITH review (**LIL001**). |

### 1.3 ROSE as switchboard operator (simple routing pattern)

**Canonical routing column:** **`lupo_dialog_messages.to_actor_id`** (NULL = broadcast). Same semantics as **“said-to”** / **`said_to_actor_id`** in routing-only doctrine (**PRD 18**).

#### Message routing

ROSE (or the **PHP** router feeding it) monitors **`to_actor_id`** to decide **whether** to auto-invoke a skillset — **not** who may **read** the thread:

| **`to_actor_id`** | ROSE / router action (product defaults) |
|-------------------|----------------------------------------|
| **NULL** | **Broadcast** — no specific auto-responder; general channel traffic. |
| **LILITH (`actor_id` 2)** | **MAY** trigger LILITH-facing pipeline when policy enables it. |
| **THOTH, MAAT, other service actors** | **MAY** trigger the mapped skillset — **resolve every `actor_id` from `lupo-database/lupopedia/actors/registry.json`** (do not hardcode stale ids in client bundles). |
| **Human / operator actor** | **No** implied auto-reply; human responds manually unless a separate rule applies. |

#### Context reading

ROSE **SHALL** read the **full channel thread** — all messages for the same **`channel_id`** + **`dialog_thread_id`** (per product scope) — **regardless of `to_actor_id`**, before emitting synthetic lines. This preserves **complete conversation history** for grounded multi-persona output.

#### Synthetic row fields (routing + attribution)

For each **ROSE-synthesized** insert (**§4**):

- **`from_actor_id`** = the **voiced persona** (e.g. COUNTERMEASURE **111**).
- **`to_actor_id`** **MAY** point to the **`from_actor_id`** of the **organic message that triggered** the batch (or another explicit addressee) so the UI can show **who is being answered** — or **NULL** for broadcast-style synthesis. **Visibility rules unchanged:** channel members still see the row (**PRD 18**).
- **`metadata_json.rose_synthesis`**: **true** (mandatory provenance; **PRD 18** synthetic cue).

**No** **`mention_actor_ids`** JSON column; **no** hiding rows from channel members based on **`to_actor_id`**.

This output complies with Lupopedia Constitutional Root Rules.

---

## 2. Goals

1. **Observable multi-voice transcript.** When the feature is on, visitors and operators see **separate bubbles or lines** per **voiced persona**, each keyed to a **real `actor_id`** from **`lupo-database/lupopedia/actors/registry.json`** (e.g. COUNTERMEASURE **111**, LILITH **2**), not free-text labels alone.
2. **Grounded in thread context.** The **ROSE orchestrator** (**`lupo_agents`** key **`rose`**, **`lupo-agents/rose/`**) consumes **recent messages** in the same **`channel_id`** / **`dialog_thread_id`** (per product rules) before emitting a **batch** of synthetic lines.
3. **Explicit synthetic provenance.** Every inserted row MUST be identifiable as **ROSE-synthesized** via **`metadata_json`** (and optionally **`message_type`**) so the UI and auditors can tell **synthetic persona voice** from **organic** posts by that actor (e.g. a human or faucet posting as LILITH).
4. **No client-side spoofing.** Injected lines are created **only** through **server-side** services using **`DatabaseFactory` / `lupo_get_db()`** and existing channel message insert paths; the client MUST NOT supply **`from_actor_id`** for arbitrary personas for this feature.
5. **Governance-aware content.** Voiced lines MUST respect **PRD 32** role boundaries (e.g. COUNTERMEASURE **analysis / challenge**, not approval); ROSE does not override **WOLFIE** decision authority.

---

## 3. Non-goals (this PRD)

- **Replacing** normal user or faucet posting or **act-as** flows (**PRD 05**, **PRD 18**).
- **Implying** that synthetic LILITH lines are **official QA sign-off** or **blocking review** (**LIL001**): synthetic lines are **illustrative / advisory** unless separately governed.
- **Automatic channel membership changes** or **permission elevation** for personas “voiced” by ROSE.
- **Cross-channel** synthesis without explicit configuration.

---

## 4. User-visible behavior

1. **Toggle / policy.** A channel (or deployment) setting **enables** ROSE multi-persona injection (default **off** until implemented).
2. **Trigger (normative default).** **PHP** maintains a **per-`dialog_thread_id`** (or product-defined scope) counter of **organic** messages since the last completed ROSE batch. When the count reaches **10**, PHP **may** start a **ROSE pass** if policy allows. The integer **10** is the **default product constant**; **`lupo_metadata`** (or channel row) **may** override. **Additional** triggers (time idle, operator “invite perspectives,” future semantic drift) **may** be layered in implementation but **must not** replace PHP authority. The model **must not** decide **when** to fire.
3. **Persona selection.** The **logged-in human operator** (and channel **allowed persona set**) determines **which** choir personas are **voiced** in that batch. The LLM generates **text only** for those selections.
4. **Output.** The server inserts **one or more** **`lupo_dialog_messages`** rows in **chronological order**, each with:
   - **`from_actor_id`** = the **persona being voiced** (registry-backed).
   - **`message_text` / `message_body`** = short, role-appropriate content (emotional / analytical nuance per persona).
   - **`metadata_json`** including at least:
     - **`rose_synthesis`**: true  
     - **`rose_batch_id`**: shared BIGINT or string for one multi-voice “turn”  
     - **`synthesizer_agent_key`**: `"rose"`  
     - **`rose_visibility`**: **`actor_only`** (operator / internal coaching) or **`visitor_visible`** (transparent audit on visitor-facing surfaces) — **PHP** sets this; the model does not.
     - Optional: **`voiced_persona_slug`**, **`mood_framework`**, **`trigger_reason`**
     - Optional (**AGAPE cooperation metric**, **PRD 00 §14.6**): **`agape_cooperation_metric`** (object) and/or **`agape_cooperation_rationale`** (string) — measures whether the voiced line reflects the **operator’s state and dependencies** for **actionable** guidance vs **agreeable noise**; not sentiment scoring. Field shapes: **`lupo-docs/doctrine/AGAPE_DOCTRINE.md`** §3.
5. **Length cap.** Each synthetic **`message_text`** (or canonical body field) **MUST** be **≤ 2000** characters (UTF-8 code units unless a later PRD narrows encoding).
6. **Rendering (mandatory).** **PRD 18** chat UI **MUST** show a **secondary cue** (badge, icon, or sub-label such as “synthetic” / “rose-generated”) on every row where **`metadata_json.rose_synthesis`** is true. **Primary** strip still uses the **voiced** **`actor_id`** (name, colors) so readers know **which persona** the line reflects. **Organic** posts by the **same** `actor_id` (no `rose_synthesis`) **MUST NOT** show that badge—so **LILITH organic review** and **LILITH synthetic insight** are **never** visually identical (**LIL001**). Rows with **`rose_visibility: actor_only`** **MUST NOT** be shown to visitors (enforced server-side on read APIs and in UI).

---

## 5. Data model (canonical; TOON-aligned)

Use existing **`lupo_dialog_messages`** fields (**`lupo-database/lupopedia/toon/lupo_dialog_messages.toon`**):

| Field | Use |
|--------|-----|
| **`dialog_message_id`** | Application-allocated PK |
| **`channel_id`**, **`dialog_thread_id`** | Scope |
| **`from_actor_id`** | **Voiced persona only** (registry-backed, e.g. **111**, **2**). **Forbidden:** any id meaning “ROSE as speaker” or synthesizer-only identity. |
| **`message_type`** | Optional dedicated value (e.g. `rose_synthetic_persona`) for filtering |
| **`metadata_json`** | **Required** ROSE provenance keys (see §4) |
| **`created_ymdhis`**, **`updated_ymdhis`** | UTC BIGINT per doctrine |
| **`source_faucet_slug`** | May identify server pipeline (e.g. `rose-engine`) |

**Provenance** is carried only in **`metadata_json`** (and optional **`message_type`**), not by posting as a separate “ROSE” bubble.

---

## 6. Security, authority, and LIL001

1. **Channel posting doctrine.** Inserts MUST satisfy the same **membership / admin** rules as other automated posters: the **server-side ROSE pipeline** (system/cron/service account) MUST be authorized like any automated poster—**membership** on the channel and/or **admin** path documented for **internal** channels only. That authorization is **not** “ROSE appears as `from_actor_id`”; it is **permission to insert rows** whose **`from_actor_id`** are **voiced personas**.
2. **No false human attribution.** Synthetic rows MUST NOT copy **`auth_user`** semantics as if a human typed them; **PRD 18** already centers **`actor_id`**—keep **`auth_user`-linked fields** (if any on insert path) **null or system** per implementation audit.
3. **LILITH.** Synthetic lines with **`from_actor_id` = 2** MUST be labeled **synthetic** in UI and metadata so they **do not** read as **LILITH’s organic non-interfering review** (**LIL001**). Organic LILITH posts retain their normal semantics.
4. **COUNTERMEASURE.** Voiced content stays within **analysis / red-team** framing per **PRD 32**; ROSE does not grant **approval** powers.

---

## 7. KAIROS handoff (memory consolidation)

After a ROSE batch completes, **PHP** **SHOULD** pass a **short coordination summary** (plain text or structured chunk describing outcomes / open risks / agreed next steps) to **`KairosConsolidationService::recordObservation`** for the **session subject `actor_id`** (and optional **`department_id`** / **`topic_key`** per **PRD 37**). That feeds **`kairos_observation`** rows for later consolidation—**not** a second LLM-owned persistence path. The LLM does **not** substitute for this handoff.

---

## 8. Rate limits and safety

- **Per-message cap:** **2000** characters on synthetic body text (**§4**).
- **Cap** messages per ROSE batch (e.g. 2–5).
- **Cooldown** between batches per channel.
- **Content filters** (length, forbidden patterns) before insert.
- **Kill switch** at channel and site level.

---

## 9. Configuration surface (product)

- **Per-channel** `rose_multi_persona_enabled` (or equivalent in **`lupo_metadata`** / channel row).
- **Allowed persona set** (subset of registry **eligible** for synthesis on that channel).
- **Optional:** require **operator** role to enable on visitor-facing channels.

---

## 10. Implementation phases (dependency order)

1. **Phase A — Metadata contract**  
   Document and validate **`metadata_json`** shape for ROSE rows; extend channel message API to **return** flags to the client for rendering.
2. **Phase B — Server orchestrator**  
   **`app/Services/Rose/RoseDialogService.php`** (name normative): per-thread **organic** counter (default **every 10** messages), operator + policy **persona set**, **`metadata_json`** including **`rose_visibility`**, **2000**-char enforcement, optional **IRIS**-mediated LLM generation → validated **multi-line** insert (**PDO_DB** only).
3. **Phase C — UI**  
   **PRD 18** strip: **mandatory** synthetic badge (or equivalent) when `rose_synthesis` is set; **no** badge for organic lines; optional visual grouping by **`rose_batch_id`**.
4. **Phase D — LLM assist (optional)**  
   Supplemental generation per **`lupo-agents/rose/properties.json`** `llm_role`, with **hard** schema validation before insert.

**Completion criteria.** Phase C done when a test channel shows **two distinct `from_actor_id` values** in one ROSE batch, **each** with **visible synthetic cue**, and an **organic** line from one of those same personas **without** the cue—plus **no** client ability to forge provenance fields.

---

## 11. Open questions

1. Should **ROSE** remain **`is_internal_only: true`** on **`lupo_agents`** while still driving **visible** transcript lines (recommended: **yes**—orchestrator internal; **messages** are normal dialog rows)?
2. **Visitor-facing** channels: require **explicit consent** copy when synthetic personas are enabled?
3. **Thread granularity:** ROSE pass scoped to **`dialog_thread_id`** only, or whole **`channel_id`**?

---

## 12. References (summary)

- **LILITH audit (incorporated):** conditional approval items above addressed in §1.1, §4, §5, §6 (2026-04-04 UTC).
- **Chat display / attribution:** [18_channel_chat_display.md](18_channel_chat_display.md)  
- **Agents / ROSE row:** [07_agents_faucets.md](07_agents_faucets.md), **`lupo-agents/rose/agent.json`**  
- **Authority / COUNTERMEASURE:** [32_actor_authority_agent_roles.md](32_actor_authority_agent_roles.md)  
- **Lilith non-interference:** `lupo-rules/root/lilith-noninterference-doctrine.md`  
- **Schema TOON:** `lupo-database/lupopedia/toon/lupo_dialog_messages.toon`
