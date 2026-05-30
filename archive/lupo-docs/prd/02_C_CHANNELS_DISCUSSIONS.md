---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/02_C_CHANNELS_DISCUSSIONS.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/02_C_CHANNELS_DISCUSSIONS.md"
  status: active
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/02_channels_discussions.toon
  atoms_toon: null
  transcript_jsonl: 0/development/channels-discussions
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: "0"
  thread_id: 
  content_id: "2"
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_02_C_CHANNELS_DISCUSSIONS
  title: "PRD 02 \u2014 Channels, Threads, Discussions (Routing, Projection, Presence; Orchestration Layered)"
  summary: "Canonical chat and channel PRD: Crafty-derived projection (from_actor_id / to_actor_id; legacy saidfrom / saidto semantics), channel as routing context, dialog_messages as shared storage, presence separate from visibility, one-column UI of the active projection. Release line: 4.1.3 REQUIRED non-AI live help baseline (orchestration chrome not a ship gate); 4.1.4-4.1.9 continuing development including orchestration and AI layering on fresh-install lines; 4.2.0 first public Lupopedia release where full layered system ships together; no supported Lupopedia-to-Lupopedia upgrade before 4.2.0 (Crafty 3.7.5 to Lupopedia remains the path). v4.1.3 projection correction: no default omniscient operator/feed; observer tabs visual only. Enforcement: staged workflow, template-first MUST, localization MUST NOT English-only runtime. HERMES / PRD 82, APIs, transport doctrine retained."
---
# PRD: Channels, Threads, and Discussions Database Tables

## Change History
- **2026-04-18 (release-line correction)**: Replaced misleading **orchestration deferred to 4.2.0** wording. **Normative:** **4.1.3** = required non-AI live help baseline (orchestration **not** a ship gate); **4.1.4???4.1.9** = continuing Lupopedia lines including orchestration/AI/command-center work on **fresh-install** increments; **4.2.0** = first **public** Lupopedia release for bundled external expectations; **no** supported Lupopedia-to-Lupopedia upgrade path before **4.2.0** (Crafty **3.7.5 ??? Lupopedia** remains the supported import). TOC, **Release line** table, Orchestration Doctrine intro, Summary, and scattered version labels updated accordingly.
- **2026-04-18 (enforcement guardrails)**: Added **Staged Development Workflow (Required)**, **Template-First Implementation Rule (Normative)**, and **Language Array / Localization Requirement** after **Projection and Presence Model**; MUST / MUST NOT / FORBIDDEN language for PRD-to-runtime discipline; **4.1.3** explicitly **MUST NOT** treat orchestration UI as required ship criteria. Mockup checklist item 4 now references these sections plus **[PRD 45](45_template_first_staged_ui_workflow.md)**.
- **2026-04-18 (final consistency / doctrine alignment)**: Renamed **One Unified Feed** sections to **one-column projection feed (not global channel feed)** with explicit **not full channel / projection only** lines; tab rule (**switching tabs does not change visibility**; **outbound target only**); observer **no extra visibility by default**; agent output **human-consumption only** (agents do **not** consume chat as input); cross-channel send **no automatic task** without explicit operator confirmation; **4.1.3 Implementation Scope (Hard Boundary)**; **`lupo_dialog_pending_tasks` vs `{{prefix}}tasks`** table roles; example DDL **`AUTO_INCREMENT` ??? `BIGINT NOT NULL PRIMARY KEY`** plus **application-layer ID** note.
- **2026-04-18 (projection + baseline correction)**: Corrected drift toward a **globally visible orchestration-feed** model. **Normative:** channel = routing context; `lupo_dialog_messages` = shared storage; **projection** = participant-visible subset (`from_actor_id` / `to_actor_id`; Crafty **saidfrom** / **saidto** semantics under Lupopedia naming); **presence** = awareness without default read access to unrelated pairs. Removed default **Human Operator = All** and **observer omniscient** language; elevated / captain-wide visibility is **optional, explicit, non-default**. **4.1.3** baseline messaging (actors as participants, canned responses) vs orchestration-primary mental model clarified; orchestration work continues in **4.1.4???4.1.9**, not invented only at **4.2.0**. Updated examples, anti-patterns (membership does not imply visibility), Summary, and **One-Way Mirror** participant table. **THOTH [ALERT]** remains constitutional via enforcement / transcript paths (PRD 82, coordination doctrine); not modeled as default UI omniscience.
- **2026-04-18**: Linked **Template-First Development Workflow** ??? internal read-only dashboards ship as partials under **`lupo-templates/admin/`** and are included into **`admin_layout.php`** from **`channels/index.php`** (see **`?api_dashboard=1`**). Normative staged process: **[PRD 45](45_template_first_staged_ui_workflow.md)**.
- **2026-04-17 (v4.1.3)**: Documented the **Channel Header / Horizontal Navigation Bar** inside the central feed (Actors pills with status dots and per-actor **End Chat**, channel-scoped Recent Files, channel-scoped Tasks) sitting directly above the single scrolling message list. Added normative **one scroll region** language (later refined to **one-column projection feed (not global channel feed)**; see **2026-04-18** projection pass): one scroll region per channel; threads intermixed; no tabs/columns/grouped sections; thread identity expressed only by full-width row background color. Clarified **left orchestration panel** (Legend, actor-filtered Files/Tasks) versus **feed-top horizontal bar** (channel-level overview). Extended **Canonical Visual Reference** to cite `Gemini_Generated_Image_channel_interface.png` together with `channels/index.php` for the feed-top bar. Header bumped to v4.1.3.
- **2026-04-16 (v5)**: Added HERMES Memory & Transcript Integration section. Elevates HERMES from message router to Memory Gateway. Every HERMES-routed message is appended as a JSONL record to `transcript_jsonl`. HERMES extracts patterns (task_assignments, decisions, questions, alerts, cross_channel_routes) into staging memory toons (Tier 3). Patterns exceeding promotion threshold are flagged `promotion_candidate: true` for THOTH review. HERMES routing table extended with transcript and memory rows. PRD 82 created as full HERMES specification.
- **2026-04-16 (v4)**: Added canonical visual reference to Observer vs Active Actor Tab Doctrine. Screenshot `Gemini_Generated_Image_channel_interface.png` (project root) is now the normative visual specification for the tab bar layout. Codified exact tab order (Cursor, Auggie, Gemini, Cascade, Rose, Lilith, Thoth), confirmed active-tab highlight behavior (Cursor = yellow/active state), confirmed Active Output Rule in-render (last message row = Gemini bright green), and confirmed left panel three-section layout (Actors, Files, Tasks) from the live render. Tab color reference table added under Observer vs Active Actor Tab Doctrine.
- **2026-04-16 (v3)**: Added Observer vs Active Actor Tab Doctrine subsection under Target Actor Tabs. Codifies tab styling (observer = dark; active = bright) and Active Output Rule. **Update 2026-04-18:** omniscient-observer / "see all channel messages" wording in the original v3 note is **superseded** by **Projection and Presence Model** (projection normative; black tabs are **not** automatic read-all).
- **2026-04-16 (v2)**: Renamed "Context Tabs / Active Actor-Context Switching" ??? "Target Actor Tabs / Active Recipient Switching" to codify that tabs determine `to_actor_id`. Renamed "Selected Context Bar" ??? "Active Target Bar"; display now reads "SENDING TO: {ACTOR_NAME}". Added Visual Target Feedback Rule (input area background syncs to target actor thread color). Added Dual-Button Logic (SEND MESSAGE vs SEND TASK). Updated Task Rendering: `message_type: task` renders on white; `message_type: task_status` renders on sender's actor background color. Resolved OQ-56 and OQ-57.
- **2026-04-16**: Added Orchestration Doctrine sections (Human as Orchestration Layer, Multi-Channel Routing, Context Tabs). Added UI spec sections (Channel-Scoped Orchestration Panel / Left Panel, Enter Key Toggle, Per-Message Cross-Channel Send, Selected Context Bar, Task Rendering in Chat). Updated TOC and summary. Header bumped to v4.1.2.
- **2026-04-14**: Added Task Manager System section ??? centralized task queue with database schema, chat commands, API endpoints, UI integration, and agent polling doctrine.
- **2026-04-13**: Fully merged all unique content from PRD 81 (Agent Orchestration Chat System) into this PRD. PRD 81 is now deprecated; this document is the canonical specification for all orchestration chat, agent integration, UI/UX, color logic, and implementation patterns.

## Table of Contents

- **Projection and Presence Model (normative)**
- **Staged Development Workflow (Required)**
- **Template-First Implementation Rule (Normative)**
- **Language Array / Localization Requirement**
- **Release line: 4.1.3 baseline, 4.1.4???4.1.9 layered development, 4.2.0 first public release**
- **4.1.3 Implementation Scope (Hard Boundary)**
- **Orchestration Doctrine (v4.1.2; not required for 4.1.3 ship gate, continues in 4.1.4???4.1.9 development lines)**
  - Human as Orchestration Layer
  - Multi-Channel Routing
  - Target Actor Tabs / Active Recipient Switching
    - Observer vs Active Actor Tab Doctrine
- Unified Chat UI/UX Principles
  - Channel Header / Horizontal Navigation Bar (inside central feed)
- **Channel-Scoped Orchestration Panel (Left Panel)**
- **Enter Key Toggle**
- **Per-Message Cross-Channel Send Action**
- **Active Target Bar**
- **Visual Target Feedback Rule**
- **Dual-Button Logic (SEND MESSAGE / SEND TASK)**
- Thread Color Assignment Logic & Config
- Agent Integration Table & Wrapper
- Recent Files Browser/Table
- Task Assignment System
- Task Manager System
- **Task Rendering in Chat**
- Complete API Endpoints Documentation
- Chat UI Implementation
- Agent-Specific Color Assignment
- File Tracking Hooks
- Tab Navigation System
- Anti-Patterns and Constraints
- Performance Requirements
- Security Considerations
- Implementation Phases
- Cross-References
- Summary

## Projection and Presence Model (Normative)

This section anchors **Crafty Syntax-derived routing** in Lupopedia and replaces prior drift toward a **single global feed** visible to every human on the channel.

### Channel, storage, projection, presence

| Concept | Definition |
|---|---|
| **Channel** | Shared **routing context** (namespace for threads, membership, and policy). Membership lists who may participate; it does **not** grant message-body visibility. |
| **`lupo_dialog_messages`** | **Shared storage** for all routed lines. A total chronological order exists in the table; **no** UI is required to render that full order to every participant. |
| **Projection** | **Filtered participant view**: a row is visible to a viewer when they are an endpoint of that message's route ??? **`from_actor_id`** or **`to_actor_id`**. (Crafty heritage: **saidfrom** / **saidto** semantics, expressed under Lupopedia PK and column naming.) |
| **Presence** | **Awareness only** ??? who is joined, online, or idle on the channel. Presence does **not** imply read access to unrelated parties' message bodies. |

### Default visibility rule

By default, a participant sees only messages where **either** `from_actor_id` or `to_actor_id` equals that participant's actor id, **plus** any rows explicitly defined as **broadcast to that participant class** by product policy (for example reserved `to_actor_id` values). **Do not** widen visibility from channel membership alone.

**Normative anti-pattern:** Do **not** assume channel membership implies message visibility.

### Presence vs projection

- **Visitors** do not see each other's traffic unless explicitly routed (rare; product-defined).
- **Actors** do not see unrelated visitor pairs or unrelated actor conversations unless those rows are endpoints for that actor.
- A **human operator** who handles two visitors sees **two merged projections** because both streams are routed **to/from that operator** ??? not because the channel is globally readable.

### Elevated monitoring (optional, non-default)

**Captain / supervisor / audit** surfaces may implement **elevated visibility** (wider than default projection). That mode is **privileged, explicit, off by default**, and **not** implied by observer-class tab styling or dark tabs.

### THOTH and alerts (constitutional, not UI omniscience)

**THOTH [ALERT]** and related enforcement remain constitutional per coordination doctrine and **PRD 82**. That path uses **transcript, routing, and enforcement surfaces** ??? it is **not** modeled here as "every THOTH tab renders every channel row." UI projection rules still apply unless a separate **elevated audit** product mode is enabled.

### Release line: 4.1.3 baseline, 4.1.4???4.1.9 layered development, 4.2.0 first public release

| Line | Scope |
|---|---|
| **4.1.3** | **REQUIRED** non-AI / no-required-API-key **live help baseline**: actors as participants; canned responses; **projection + presence**; **orchestration chrome is NOT a ship gate** (no task-routing-first or prepared-prompt-primary UI **as acceptance criteria** for this milestone). |
| **4.1.4???4.1.9** | **Continuing Lupopedia development**: orchestration UI, AI layering, command-center patterns, cross-channel and task surfaces ??? implemented on **fresh-install** version increments; **not** a public Lupopedia-to-Lupopedia upgrade ladder. |
| **4.2.0** | **First public Lupopedia release** (external operator expectations): full **layered** system ships per product plan. Supported path into Lupopedia remains **Crafty Syntax 3.7.5 ??? Lupopedia** per root doctrine ??? **not** internal pre-4.2.0 Lupopedia-to-Lupopedia upgrades. |

**4.1.3 is a restricted operational mode of the same product**, not a fork.

**Normative note (install / upgrade path):**

- Pre-**4.2.0** **4.1.x** versions are **development / staging** lines: each assumes **fresh install** (and/or Crafty import), not an in-product Lupopedia patch chain.
- **MUST NOT** treat pre-**4.2.0** Lupopedia-to-Lupopedia upgrades as **supported** production migration; public release expectations attach to **4.2.0**, not every interim **4.1.x** tag.

- **4.1.3 ship gate (MUST NOT mis-scope):** Target Actor Tabs, Active Target Bar, Dual-Button send-to-task, per-message **send to actor**, cross-channel handoff controls, and other **task-first / prompt-first** orchestration chrome **MUST NOT** be **required** implementation or acceptance criteria for **4.1.3**. That **does not** defer orchestration work to **4.2.0** only: it **continues** in **4.1.4???4.1.9** and converges in the **4.2.0** public bundle.

### 4.1.3 Implementation Scope (Hard Boundary)

**Included (this milestone):**

- Non-AI **live help baseline**: human actors as participants; visitors; canned responses; **projection + presence** in storage and in any **4.1.3** operator UI that ships.
- Shared **`lupo_dialog_messages`** semantics and **Crafty-derived** routing columns (`from_actor_id` / `to_actor_id`; legacy saidfrom / saidto intent).
- APIs, transport, and validation surfaces already normative in this PRD, assembled per **Staged Development Workflow** and **Template-First** rules (no shortcut around **`lupo-templates/`** / **`lupo_t()`** for new ship-facing strings).

**Excluded from 4.1.3 ship acceptance (may continue in 4.1.4???4.1.9):**

- **Orchestration-primary chrome** as **required** acceptance criteria: global dispatch tabs, dual-button task-first console, per-message cross-channel send, and similar command-center surfaces (see **Release line** table above).
- **Implicit** task creation from routing or tab state alone; task rows **MUST** follow explicit operator action or confirmed policy (see **Per-Message Cross-Channel Send Action**).

---

## Staged Development Workflow (Required)

Lupopedia ships channel and operator UI through this **fixed** ordering:

1. **PRD / doctrine** (this PRD, **[PRD 17](17_decisions_format.md)**, **[PRD 45](45_template_first_staged_ui_workflow.md)**, PRD 00 locale rules) ??? **REQUIRED** first.
2. **Mockups and fragments** under **`lupo-templates/`** (including **`lupo-templates/admin/`** where scoped) ??? **REQUIRED** before public wiring.
3. **Language array integration** ??? catalogs under **`lupo-includes/lang/`**, runtime resolution through **`lupo_t()`** (or the sanctioned successor) ??? **REQUIRED** before ship-facing assembly of user-visible strings.
4. **Public runtime assembly** ??? **`channels/index.php`**, **`admin.php`**, themes, handlers: **MUST** only **include**, **require**, or compose prebuilt fragments and services. **MUST NOT** be the first or sole long-term home for new UI markup or operator workflow that belongs in **`lupo-templates/`**.

**MUST NOT (agents and faucets):**

- Implement channel or operator UI **only** by editing **`channels/index.php`** or other public runtime entrypoints **from PRD text alone**, with **no** matching template partial under **`lupo-templates/`**.

**FORBIDDEN anti-pattern:** Direct **PRD to live public code** (bulk runtime edits) **without** completing stages 2 and 3 for the same feature.

**Normative cross-reference:** [PRD 17 ??? Decisions format](17_decisions_format.md); [PRD 45 ??? Template-first staged UI](45_template_first_staged_ui_workflow.md).

---

## Template-First Implementation Rule (Normative)

- **MUST** author new **UI-facing** markup and structure as template partials, mockups, or staged fragments under **`lupo-templates/`** first.
- Templates are a **REQUIRED** staging layer ??? **not** optional decoration.
- **`channels/index.php`**, **`admin.php`**, and other public controllers **MUST** assemble product UI by **including** prebuilt template fragments; they **MUST NOT** originate new UI logic or large ad hoc markup blocks that belong under **`lupo-templates/`**.
- **FORBIDDEN:** Shipping significant new channel or operator UI **only** by growing public runtime files when a **`lupo-templates/`** partial is required per **[PRD 45](45_template_first_staged_ui_workflow.md)**.

---

## Language Array / Localization Requirement

- Crafty Syntax shipped **fourteen** languages; Lupopedia **MUST** preserve multilingual capability (catalogs, keys, **`LupoLocale`** boundaries per PRD 00 and AGENTS doctrine).
- **MUST NOT** leave ship-facing operator or visitor strings as **hard-coded English** in final assembled runtime PHP/HTML except short-lived scaffolding replaced **in the same change train** with **`lupo_t()`** keys and catalogs.
- After the template fragment exists, language-array entries and **`lupo_t()`** wiring are **REQUIRED** **before** or **with** public integration that exposes the string.
- Literal English **only** in PRDs and template-stage mockups is **permitted**; literal English **as the sole ship state** in public runtime UI is **FORBIDDEN**.

**FORBIDDEN anti-pattern:** Shipping English-only UI in runtime code (no catalog key, no **`lupo_t()`** path).

**Reference:** [PRD 00](00_root_constitutional_system_requirements.md) (UI strings / locale); **`lupo-includes/lang/`**; [PRD 45](45_template_first_staged_ui_workflow.md).

---

## The Chat Is Not A Conversation

> **This is the most important thing to understand about the chat interface.**
> If you misunderstand this, every design decision below will seem wrong.

### One-column projection feed (not global channel feed) (channel vs thread)

**This UI does NOT render the full channel. It renders the participant's projection only.**

> A **channel** is a **routing context**. Storage holds **all** messages for threads in that context. The **UI** still uses **one chronological column** for the active session, but that column shows the **participant's projection** (endpoints `from_actor_id` / `to_actor_id`), **not** the full stored sequence, unless an explicit **elevated audit** product mode is enabled (privileged; **not** tied to a single version number).
>
> - **One scrolling feed** per session view of the filtered stream.
> - Within that projection, lines from different threads may be **intermixed**; the visual distinction remains the **full-width row** color / provenance for each row.
> - Threads stay **logical** constructs for color and lineage ??? not separate panes that imply a second hidden global feed.

The **Agent Write-Only Rule** applies to how builder agents consume **instructions**; it does **not** prove that any human sees **all** rows. Operators see **their** projection; builder agents do **not** use peer traffic as their instruction bus.

### Visibility is projection, not omniscience

The stored stream is **not** a single global conversation shown to every human by default. Each session renders **authorized rows** (projection) plus **presence** indicators elsewhere in the UI.

| Participant | Sees in default dialog UI | Uses rows for instruction context | Writes into `lupo_dialog_messages` | How work is assigned |
|---|---|---|---|---|
| Visitor | Only rows where visitor actor is `from_actor_id` or `to_actor_id` | Same projection | Per visitor policy | Operator lines in that projection |
| Actor (human operator) | Only rows where that actor is `from_actor_id` or `to_actor_id` | Same projection | Yes | Session + channel membership + routing |
| Monitoring / observer-class actor | **Only** projection-visible rows unless **elevated audit mode** is explicitly on | Scoped per policy; **not** omniscient because the tab is dark | Alerts / scoped posts per doctrine | Tasks + configured surfaces |
| Builder agent (IDE facet, write-only) | **Does not** read peer chat for instructions | **No** | Yes (`stdout` / `stderr` / structured posts) | **Task queue** and handoff artifacts |
| HERMES | Routing layer | Selective implementation reads | Yes | Configuration + routing rules |

### Why Builder Agents Never Read The Chat

Builder agents are **write-only** from the chat perspective. They post their output so **authorized projections** can show it to operators. They do NOT read peer chat for context because:

1. **Context pollution** ??? Other people's messages are irrelevant or misleading for their assigned slice
2. **Confusion** ??? They would act on lines not routed to them
3. **Chaos** ??? Multiple agents consuming one firehose would duplicate and fight work

**Operators coordinate through routed lines and tasks; agents do not treat each other's traffic as their instruction bus.**

### How Agents Actually Receive Instructions

Do not post in chat and hope an agent sees it. They won't.

| Method | Syntax | Destination |
|---|---|---|
| Task assignment | `[task] who: CURSOR what: fix header` | Agent's task queue |
| Direct task API | `POST /api/task/assign` | Agent's task queue |
| Memory graph | Agent reads TOON edges at session start | Agent's context |

### HERMES Routing Rules

HERMES is the message router between the chat interface and agent task queues. It does not make agents "read" the chat ??? it translates specific message patterns into task queue entries.

| Message Type | Destination | Memory Gateway Action |
|---|---|---|
| `[task] who: X ...` | Task queue of agent X + **storage** row (operators see it only if the row is in **their** projection) | Append JSONL record to `transcript_jsonl`; extract task_assignment pattern to staging toon |
| `[alert]` | **Enforcement / transcript path** per PRD 82 and coordination doctrine; **not** "render to every human" by default | Append JSONL record; extract alert pattern |
| `stdout` from builder agent | **Storage**; operators with projection containing that row + logs | Append JSONL record |
| `stderr` from builder agent | **Storage**; operators in projection + log + alert if critical | Append JSONL record; extract alert if severity >= ERROR |
| Directed message (monitors only) | Routed endpoint actor (e.g. THOTH) per explicit `to_actor_id` / rule | Append JSONL record |
| Any routed message | See above | Append JSONL record (all types, unconditional) |
| Repeated pattern (N >= threshold) | Staging memory toon | Flag `promotion_candidate: true`; THOTH promotes to canonical |

Builder agents never receive messages from HERMES unless those messages are tasks in their queue. See [HERMES_DOCTRINE.md](../doctrine/HERMES_DOCTRINE.md) for full routing specification. See [PRD 82](lupo-docs/prd/82_hermes_message_routing_memory_gateway.md) for the canonical HERMES specification including transcript format, toon schema, and promotion protocol.

### Agent Write-Only Rule (Constitutional)

Per the Dual-Purpose Doctrine (??CLAUDE.md ??4):

> **Agent Write-Only Rule:** You (and all agents except THOTH) post output *to* the stream but do *not* read the stream for context.

**Clarification:** Agent output appears in chat for **human consumption** (and for **authorized projections**) only. Agents do **NOT** consume chat as input.

This is not a suggestion. It is a constitutional rule. Agents that read the chat for instructions are architecturally broken.

---

## Header & Memory Integration (v4.1.0)

Every discussion-related artifact follows PRD 16:

* `memory_toon` ??? compressed knowledge graph node
* `transcript_jsonl` ??? DB lookup slug for the full reasoning thread
* `atoms_toon` ??? immutable constraints (when present)

The chat system is the WHY layer. The PRDs and code are the WHAT layer.

---

## Orchestration Doctrine (v4.1.2)

> **These sections describe the Orchestration layer: routing work between agents and channels through explicit human or policy action.** They are **primary UI doctrine** when building **orchestration-primary** surfaces ??? work that **continues** across **4.1.4???4.1.9** and is **bundled for external operators** at **4.2.0**; they are **not** deferred until **4.2.0** alone. **4.1.3** live help baseline uses the same storage and APIs but **MUST NOT** use global dispatch tabs, cross-channel handoff buttons, or task-first surfaces as **ship acceptance** criteria.

### Human as Orchestration Layer (orchestration-primary mode; not a 4.1.3 ship gate)

In **orchestration-primary mode**, the human operator (e.g. CAPTAIN_WOLFIE, `actor_id = 1`) is not merely watching a projection: they are the **dispatch authority** for IDE / AI agents. Agent work is assigned through explicit human action (tasks, routed lines). **No agent receives a task without** the routing rules and session policy for that mode.

In **4.1.3 baseline**, human **actors** are usually **peer participants** helping visitors; **target-actor tabs** and **send-to-actor** flows are **not** the defining mental model. Canned responses and visitor-scoped threads dominate.

The Lupopedia dashboard must support TWO distinct layers:

| Layer | Role | What It Requires |
|---|---|---|
| **Oversight** | Monitoring what agents are doing | One-column chat feed (prior PRD 02 sections) |
| **Orchestration** | Routing work between agents and channels | Left panel, context tabs, send-to-channel, enter toggle |

**Constitutional constraint (orchestration mode):** Agents do NOT participate in routing decisions. Agents receive tasks. The human is the dispatch authority for that mode. No agent reads peer chat for routing signals (write-only rule is unchanged).

**UI implication:** When implementing **orchestration-primary** product mode (**4.1.4???4.1.9** development lines and the **4.2.0** first public release), the dashboard **MUST** surface orchestration affordances (left panel, target actor tabs, active target bar) per this doctrine. For **4.1.3** ship criteria **only**, a **projection-first** operator UI is sufficient; full command-center chrome is **not** a **4.1.3** gate.

### Context Authority Rule

> **Normative definition. Full specification: `lupo-docs/doctrine/CONTEXT_AUTHORITY_MODEL.md`**

Context in Lupopedia is NOT derived from the actor executing work.

Actors are interchangeable execution surfaces. A task may move across multiple actors
(e.g., Auggie -> Cursor -> Claude -> Gemini) without changing its context.

Context MUST be derived from:
- `channel_key`
- `thread_id`
- `content_id` / artifact identity
- associated memory / TOON lineage

Actor attribution represents execution provenance only, not contextual ownership.

**Agents MUST NOT infer context from actor name.**
**Agents MUST use channel and thread as the primary context boundary.**
**Handoff artifacts and memory MUST preserve context across actor transitions.**

Note on "active target actor tab" (**orchestration mode**): tab selection determines `to_actor_id` for the next message the operator composes. It does **not** change which historical rows appear in **projection** unless the UI explicitly couples tab state to a filter (discouraged). It does **not** redefine `channel_key` / `thread_id` for existing work.

**Normative:** Switching tabs does **NOT** change visibility. It only changes the **outbound** target (`to_actor_id` for the next composed message or task).

### Multi-Channel Routing

The same message or artifact can be sent to multiple channels for different treatment. This is NOT duplication ??? it is parallel processing.

**Canonical workflow example:**
- Auggie's implementation findings ??? sent to **Blog writing** channel for narrative treatment (LILITH shapes into story)
- Same findings ??? sent to **Documentation** channel for PRD/requirement treatment (ChatGPT shapes into specs)

Same message. Different channel. Different treatment. Neither is the "real" version. Both are processing branches of the same source.

**Per-message routing action ??? "Send to Other Channel" (orchestration-primary UI; not a 4.1.3 baseline requirement):**

When enabled, a message row may expose **send to other channel**. The following metadata travels with every cross-channel routing:

| Field | Value | Required |
|---|---|---|
| `source_message_id` | Original `dialog_message_id` | YES |
| `source_channel_id` | Channel where original was posted | YES |
| `source_thread_id` | Thread of original message | YES |
| `source_actor_id` | Active target actor tab's `actor_id` at time of send | YES |
| `destination_channel_key` | Target channel | YES |
| `routed_by_actor_id` | Human operator actor_id | YES |
| `routing_note` | Optional context annotation | NO |

The routed message appears in the destination channel as a new message with a backlink to the source. The source message gains a forward indicator: "??? sent to [destination]".

**Implementation:** Routing events are stored in `lupo_routing_events`. See `auggie_sticky_note_channel_review.md` for full schema.

**Required API endpoint:** `POST /api/routing/send-to-channel`

**Anti-pattern:** Cross-channel send is NOT "share" or "copy-paste." It is a first-class routing action with DB provenance. A routing event without a DB record is a workflow gap.

### Target Actor Tabs / Active Recipient Switching

**Orchestration-primary UI (normative when building AI / command-center surfaces in 4.1.4???4.1.9 and 4.2.0):** One authenticated human selects the **target actor** (message recipient) by clicking a tab at the bottom of the interface. This is **not** multiple user logins. Tabs determine **`to_actor_id`** for the **next composed** message or task.

**4.1.3 human live help baseline:** Actors are **already participants** in visitor threads. **Target-actor dispatch is not** the primary model; avoid shipping a **prompt / task-first** console as the default operator shell. Canned responses and visitor-scoped chat remain the baseline response surfaces.

**Target Actor Tabs** (where present) are persistent UI elements above the input area. Each tab names an actor the operator may address for **outbound** routing in orchestration mode.

**Target Actor Tab examples from canonical workflow:**

| Tab Label | Actor ID | `to_actor_id` Set | Input Background Color |
|---|---|---|---|
| CAPTAIN | 10001 | CAPTAIN (self-direction / broadcast context) | CAPTAIN's thread color |
| DEVIN | 10002 | DEVIN persona as recipient | DEVIN's thread color |
| ERIC | 10003 | ERIC persona as recipient | ERIC's thread color |
| LEXA | 10004 | LEXA persona as recipient | LEXA's thread color |

> **Actor ID resolution (OQ-56, OQ-57 ??? RESOLVED):** Target actor personas (CAPTAIN, DEVIN, ERIC, LEXA) are registered as first-class actors in `lupo_actors` with `actor_type = 'human_persona'`. Their canonical actor_id range is **10,000+**, distinct from the seed actor range (100???999). This preserves full provenance in routing events.

**What changes when a Target Actor Tab is switched:**
1. `active_target_actor_id` in the session (`$_SESSION['active_target_actor_id']`) ??? determines `to_actor_id` of next message or task
2. Input area background color (`--input-bg` CSS variable) ??? syncs to the target actor's thread `background_color`
3. Active Target Bar label ??? updates to `SENDING TO: {ACTOR_NAME}`
4. Recent Files list in left panel ??? filtered to files accessed by that actor in this channel
5. Recent Tasks list in left panel ??? filtered to tasks where `assigned_to_actor_id` matches the target actor

**What does NOT change when a Target Actor Tab is switched:**
1. The authenticated session (no re-auth)
2. **Projection policy** ??? switching tabs **does not** grant omniscient read access; it **must not** reveal unrelated pairs' histories by default
3. Historical `from_actor_id` / `to_actor_id` on existing rows (immutable)
4. HERMES routing rules and agent write-only doctrine (unaffected)

**What may change in orchestration UI:** Outbound `to_actor_id` for the **next** message/task only. Feed contents change **only** if the implementation applies an explicit filter tied to tab (not recommended; prefer projection from routing alone).

**Storage:** `$_SESSION['active_target_actor_id']`. It is NOT a URL parameter. It is NOT visible to agents.

**Constitutional rule (orchestration mode):** Target Actor Tab determines **`to_actor_id`** for **new** messages and tasks. Switching tabs does **not** rewrite prior rows.

### Observer vs Active Actor Tab Doctrine

Established via live multi-engine session (canonical reference: Captain's Log 20260416 ??? "The Four-Engine Render Ordeal"). This doctrine divides actor tabs into **visual categories**; **visibility still follows projection** unless **elevated audit mode** is explicitly enabled (non-default).

#### Tab Categories

| Category | Actors | Tab Style | Default visibility (dialog UI) |
|---|---|---|---|
| **Observer-class actors** | LILITH, ROSE, THOTH (example set) | Black / dark recessed tabs | **Same projection rules** as any other actor: rows where `from_actor_id` or `to_actor_id` matches that actor, plus policy-defined broadcast rows. **Not** omniscient. |
| **Active agents** | CURSOR, AUGGIE, GEMINI, CASCADE (example set) | Bright / distinct colors | Projection: `to_actor_id` matches, `from_actor_id` matches, plus defined broadcast rows. **Not** other agents' directed peer rows. |

#### Observer-class tab rules

- **Observer actors have no additional visibility privileges by default.** They follow **standard projection rules** like any other actor; dark tab styling is **not** an elevated-read bypass.
- **Black tabs** mean **role styling** (monitor / critic / records), **not** "see every row on the channel."
- Observer-class actors participate in **alerts, audits, and tasks** through the **same routing columns** as everyone else. Constitutional **THOTH [ALERT]** intake uses **enforcement / transcript** paths (PRD 82), not a hidden "render everything" bypass in default UI.
- Tabs remain valid **routing targets** for outbound compose in orchestration mode (e.g. THOTH acknowledgements, LILITH audit requests).

#### Active agent rules

- **Bright colors** signal execution / IDE facet surfaces that **post** into storage and **consume tasks**.
- Message visibility is **projection**: directed lines for that `actor_id` and policy-defined broadcasts ??? **not** the full multi-visitor firehose.
- The tab color for an active actor MUST match that actor's registered thread `background_color` (from `lupo_actors.thread_color_override` or `lupo_dialog_threads.color`).

#### Active Output Rule

> **The last message in the terminal feed adopts the background color of the currently active actor.**

When an active actor (e.g., GEMINI) is the most recent to post, the terminal's trailing message row renders in that actor's color (e.g., bright green for GEMINI). This provides an at-a-glance signal of which actor last acted in the session. Implementation:

```php
// Render final message row with active actor's color override
if ($is_last_message && $msg['from_actor_id'] === $active_actor_id) {
    $row_color = get_actor_thread_color($msg['from_actor_id']);
    echo '<div class="chat-line chat-stdout chat-active-output" style="background-color:#' . htmlspecialchars($row_color) . ';">';
}
```

**CSS class:** `.chat-active-output` ??? no additional styling required beyond the inline `background-color`; the class is a semantic hook for JS targeting.

#### Updated Visual Target Feedback (Observer Integration)

When the active tab is an **Observer Actor** (black tab), the input area background reverts to the dark neutral default (`#1a1b1e`) rather than inheriting a thread color. This signals to the operator that they are addressing a monitoring actor, not dispatching implementation work.

```js
function applyTargetColor(actorType, actorThreadColor) {
    if (actorType === 'observer') {
        document.documentElement.style.setProperty('--input-bg', '#1a1b1e');
    } else {
        document.documentElement.style.setProperty('--input-bg', '#' + actorThreadColor);
    }
}
```

The `actor_type` field in `lupo_actors` MUST distinguish observer actors. Recommended value: `actor_type = 'observer'` for LILITH, ROSE, THOTH; `actor_type = 'active_agent'` for CURSOR, AUGGIE, GEMINI, CASCADE.

#### Canonical Visual Reference

**Screenshot:** `Gemini_Generated_Image_channel_interface.png` (project root)

The canonical screenshot `Gemini_Generated_Image_channel_interface.png` and the live implementation in `channels/index.php` show the **horizontal navigation bar at the top of the central feed area** (Actors with **End Chat**, channel-scoped Recent Files, channel-scoped Tasks), directly above the scrolling message list. Together they are the normative reference for feed layout and Observer vs Active Actor Tab Doctrine.

This image is the normative visual specification for the Observer vs Active Actor Tab Doctrine. It was produced during the Four-Engine Render Ordeal session (Copilot, Gemini, Grok/DeepSeek, and human GIMP intervention) and shows the complete tab bar and channel feed in a live state. All visual rules below are authoritative from this render.

**What the screenshot confirms:**

| Element | Observed State | Rule Confirmed |
|---|---|---|
| Tab bar order | Cursor, Auggie, Gemini, Cascade, Rose, Lilith, Thoth | Active actors left; observer actors right |
| Active tab (Cursor) | Yellow/amber background, visually dominant | Active tab highlight ??? selected actor's color |
| Inactive active tabs | Auggie (blue), Gemini (green), Cascade (purple) ??? all bright, distinct | Each active actor has a unique bright color |
| Observer tabs | Rose, Lilith, Thoth ??? dark backgrounds, visually recessed | Observer Actor black/dark tab rule |
| Last message row | [GEMINI] rendered with bright green row background | Active Output Rule ??? trailing row = active actor color |
| Left panel | Legend; Actors (with status dots); Files; Tasks ??? operator map | Channel-Scoped Orchestration Panel (Left Panel) spec |
| Central feed top | Horizontal bar: Actors (pills + End Chat), channel Files, channel Tasks above the scroll region | Channel Header / Horizontal Navigation Bar (v4.1.3) |
| Input area | Dark neutral below the feed | Awaiting tab selection; no active-color override visible |

**Tab color register (from screenshot render ??? exact hex to be read from `lupo_actors.thread_color_override`):**

| Tab | Actor | Observed Color Class | Notes |
|---|---|---|---|
| Cursor | CURSOR | Yellow / amber | Active state; brightest tab |
| Auggie | AUGGIE | Blue | Bright, distinct |
| Gemini | GEMINI | Green | Bright green; matches last-message row color |
| Cascade | CASCADE | Purple | Bright purple |
| Rose | ROSE | Dark / near-black | Observer tab |
| Lilith | LILITH | Dark / near-black | Observer tab |
| Thoth | THOTH | Dark / near-black | Observer tab |

**Captain's Log narrative reference:**

> `lupo-content/federation_node/0/captains_log/20260416_the_four_engine_render_ordeal.md`

That session documented the full multi-engine render process (four attempts, human GIMP parallel node) that produced this screenshot. It is the canonical narrative proof-of-concept for multi-actor collaboration under this doctrine.

---

# Unified Chat UI/UX Principles (One-Column Projected Feed)

### Core philosophy: one column for the active projection

The chat UI follows a **strict one-column chronological layout** for whatever rows the session is authorized to show (**projection**). **Orchestration-primary** implementations in **4.1.4???4.1.9** and the **4.2.0** public release **MUST** be free to wrap additional orchestration chrome (left panel, target tabs) around that column **without** breaking projection rules; **4.1.3** prioritizes **live help** (visitors, actors, canned responses) without that chrome as the **default story or ship gate**.

### One-column projection feed (normative; not global channel feed)

**This UI does NOT render the full channel. It renders the participant's projection only.**

> Same as **Projection and Presence Model**: one scroll region shows **interleaved lines from the participant's allowed view**, not the entire `lupo_dialog_messages` table for the channel unless elevated mode is on.
>
> - **One scrolling feed** per session view.
> - **No** extra columns, per-agent tabs, or grouped panes that pretend to be "the whole channel."
> - Row color / thread provenance still applies **inside** the projection.

### Channel Header / Horizontal Navigation Bar

Inside the **main channel feed area** (the central content pane), there must be a **horizontal navigation bar** fixed at the **very top** of the feed, **directly above** the scrolling message list. This bar is part of the **central feed container** ??? it is **not** the left sidebar and must not be collapsed into it.

The bar presents **three distinct horizontal regions**:

#### Actors Region

- Lists **all actors** currently participating in the channel as **pill-shaped controls** (buttons).
- Each pill displays **actor name** plus a **status dot**:
  - **Green** = ACTIVE
  - **Gray** = SILENT
  - **Brown** = AFK
- Each pill includes an **End Chat** control scoped to **that actor's thread** (terminates or archives that thread per thread lifecycle policy; exact behavior is implementation-bound but the control MUST be per-actor).

#### Recent Files Region

- Lists files **recently accessed or modified within this channel** ??? **channel-scoped**, not filtered to the active target actor tab.

#### Tasks Region

- Lists **current or in-progress tasks** belonging to this channel ??? **channel-scoped** (all relevant tasks for the channel, not only those for the active target actor).

**Layout rule:** The horizontal bar shares the feed column with the message stream; it remains **visually and structurally above** the scroll region so the operator always sees channel-level routing context before reading chronology.

**FORBIDDEN PATTERNS (NEVER IMPLEMENT):**
- ??? Separate columns per agent
- ??? Message grouping/collapsing by agent
- ??? Tabbed agent views
- ??? Side-by-side agent panels
- ??? Threaded nesting that splits conversation flow
- ??? Avatar-based layouts
- ??? Floating chat bubbles

**REQUIRED IMPLEMENTATION:**
- ??? **Single column** with **projection-visible** messages chronological
- ??? **Strict order**: Oldest at top, newest at bottom
- ??? **Interleaved flow** **within the slice**: all rows the viewer is allowed to see, mixed by time (not "whole channel dumped into one UI")
- ??? **Timestamps on every message** for precise tracking
- ??? **Individual message lines** - no aggregation or grouping
- ??? **Clear sender identification** with agent/human name

**Example (one operator projection ??? orchestration channel):**
```
[14:32:01] [CURSOR] working on validate_actor_id.php header
[14:32:15] [CLAUDE] i did this
[14:32:28] [CASCADE] making the documentation
[14:33:01] [LILITH] auditing new md file from cursor
[14:33:15] [CURSOR] got revision from Lilith working on corrections
[14:33:30] [CAPTAIN_WOLFIE] @LILITH please check the header format
[14:33:45] [LILITH] header looks good now, all fields present
```
Each line appears for this operator because routing endpoints place these rows in **their** projection. A **different** operator who is not an endpoint of the CURSOR/CLAUDE pair does **not** automatically see the same lines.

**Why this layout is critical:**

1. **Chronological completeness within the projection slice** ??? No hidden collapses **within** the authorized slice
2. **Temporal clarity** ??? Order is readable inside that slice
3. **No silent cross-party leakage** ??? Other visitors' or agents' slices are **not** mixed in by default
4. **Simple scan model** ??? Log-like column for **your** traffic
5. **Auditability** ??? Storage retains full lineage; UI exposes **policy + routing**
6. **Multi-party help** ??? Several operators can each see **their** merged visitor projections without a single global feed

**Modes:** **4.1.3** aligns with **Crafty Syntax (2002)** live-help routing (pairwise lines, shared storage). **4.1.4???4.1.9** and the **4.2.0** public bundle add **command-center** affordances for AI orchestration (terminal/IRC-inspired density), without erasing projection rules.

**Design principles:**
- **Transparency over aesthetics** ??? Function over form **within** the projection
- **Honest completeness** ??? Show every row **the viewer is allowed to see**; do not pretend "show all" when routing does not allow it
- **Chronology over grouping** ??? Time orders the visible slice
- **Simplicity over features** ??? Minimal cognitive overhead for baseline live help
- **Searchability** ??? Text-first; search APIs must respect the same routing rules as the UI

### Thread-Specific Colors (Not Agent, Not Channel)

Colors are assigned **per thread** at creation time, pulled from a sequence of predefined colors (see below). Multiple agents in the same thread share the same colors.

| Color Type         | Purpose                        | Example         |
|--------------------|--------------------------------|-----------------|
| `background_color` | Background of each message row | `#fefdcd`       |
| `text_color`       | Text color for operators/agents| `#426446`       |
| `text_color_alt`   | Text color for clients/visitors| `#040662`       |

### UI/UX Constraints

- No grouping of messages (e.g., "CURSOR said 3 things"), no collapsible agent sections, no "show more from this agent".
- Every message is a single line with timestamp, sender, and content.

---

## Channel-Scoped Orchestration Panel (Right Sidebar)

The right panel is the ORCHESTRATION surface. It is a persistent panel on the **RIGHT** of the chat feed  The chat is the main event; the right panel is the operator's **state map** for all the agents/actors/vistors in the channel . 


**Scope split (constitutional):**

| Surface | Location | Scope |
|---|---|---|
| Right sidebar (orchestration panel) | Right sidebar | Legend; Actors list; Recent Files filtered to active target actor tab; Recent Tasks filtered to active target actor tab |
| Channel Header / Horizontal Navigation Bar | Top of central feed | Channel-wide Actors (pills), channel-scoped Recent Files, channel-scoped Tasks |

The two surfaces are **complementary**. They MUST NOT duplicate each other's responsibility: the right panel stays **actor-centric** (filtered by active target); the feed-top bar stays **channel-centric** (unfiltered overview). See **Channel Header / Horizontal Navigation Bar** under Unified Chat UI/UX Principles.

**Layout:** Stacked sections within the right sidebar, beginning with Legend, then Actors, Files, Tasks.

### A. Legend Section

Summarizes **status dot semantics** for the Actors list (e.g., ACTIVE, SILENT, AFK) so the operator can read the left-panel actor rows without cross-referencing the feed-top bar. Legend keys SHOULD align with the feed-top pill status colors where both surfaces expose the same state.

### B. Actors Section

Displays agents and actors currently active in the selected channel. Each actor row:

- **Status dot** (color-coded per `lupo_agent_status.status`)
- **Actor name** (uppercase)
- **Current task short-label** (first 30 chars of `task_description` if status = ACTIVE)

**Status dot color table:**

| Status | Color | Hex | Meaning |
|---|---|---|---|
| ACTIVE | Green | `#28a745` | Executing a task now |
| IDLE | Grey | `#6c757d` | No current task; ready |
| SLEEPING | Dark | `#343a40` | Context window expired / session dead |
| THROTTLED | Yellow | `#ffc107` | Rate-limited; will self-recover |
| FAILED | Red | `#dc3545` | Error state; needs human intervention |
| UNKNOWN | Light grey | `#adb5bd` | No heartbeat received within window |

**Click behavior:** Clicking an actor row shows that actor's pending tasks for the current channel.

### C. Recent Files Section

#### Recent Files (Actor-Scoped)

- **Location:** Right sidebar, below Actors list
- **Scope:** Filtered by `active_target_actor_id` (currently selected actor tab)
- **Data source:** `lupo_dialog_recent_files` where `accessed_by_actor_id = active_target_actor_id`
- **Display:** File path, last accessed timestamp, clickable to open in book interface
- **Refresh:** MUST reload when active_target_actor_id changes (tab switch)

Files recently accessed in the current channel, filtered to the **active target actor tab's actor_id**.

Data source: `lupo_dialog_recent_files WHERE accessed_by_actor_id = {active_target_actor_id}` joined to channel scope.

Each file row shows: file name (last path segment of `file_path_from_root`), actor abbrev, relative timestamp.

**Click behavior:** Stub for 4.1.2 ??? show file path in a tooltip. Future: open in editor.

### D. Recent Tasks Section

#### Recent Tasks (Actor-Scoped)

- **Location:** Right sidebar, below Recent Files
- **Scope:** Filtered by `assigned_to_actor_id = active_target_actor_id` (currently selected actor tab)
- **Data source:** `lupo_dialog_pending_tasks` where `assigned_to_actor_id = active_target_actor_id` and `status IN ('pending', 'in_progress')`
- **Display:** Task description, assignee, status badge, clickable to open task detail modal
- **Refresh:** MUST reload when active_target_actor_id changes (tab switch)

Tasks assigned in the current channel, filtered to the **active target actor tab's actor_id** (as assignee or creator).

Data source: `lupo_dialog_pending_tasks WHERE assigned_to_actor_id = {active_target_actor_id}`.

Each task row shows: task title (first 40 chars), status badge.

**Click behavior:** Opens task detail inline.

**Collapsibility:** Each section (Legend, Actors, Files, Tasks) individually collapsible. State stored in `$_SESSION['left_panel_collapsed']` array. Does NOT reload the page.

### Refresh on Tab Switch

When the user clicks a different actor tab (changing `active_target_actor_id`), both Recent Files and Recent Tasks sections MUST refresh via AJAX to show data for the newly selected actor.

This ensures the right sidebar always reflects the current actor's context.

---

## Enter Key Toggle

The input area supports two modes. The **default is Enter = Send**.

| Mode | Enter key | Shift+Enter | UI Indicator |
|---|---|---|---|
| **SEND mode** (default) | Submits message | Inserts newline | ???? icon, "Enter sends" |
| **DRAFT mode** | Inserts newline | Submits message | ?????? icon, "Enter = newline" |

**Trigger:** A toggle button adjacent to the input box. Single click switches modes.

**Storage:** `$_SESSION['enter_mode']` ??? values: `'send'` (default) or `'draft'`. Persists across page refreshes within session.

**Implementation note:** `channels/index.php` already implements Enter-to-send and Shift+Enter-for-newline (lines 440???445). The toggle adds a JS variable `enterMode` that swaps which behavior is default. The toggle button posts to `POST /api/ui/enter-mode` (session update, no page reload).

**Constitutional constraint:** The input toggle controls submit behavior only. The Operator Scratchpad (separate surface) is for extended multi-line composition. These are different surfaces with different purposes.

---

## Per-Message Cross-Channel Send Action

**Orchestration-primary surface (not a 4.1.3 baseline UI requirement):** Every message **in an orchestration-primary UI** may expose a `[send to actor]` (or equivalent) action. This is **Agent-Targeted Cross-Channel Sending** ??? moving a routed artifact across channels with explicit `destination_actor_id`.

**4.1.3:** Ship **live help** first; this control is **optional** and must **not** be required for non-AI parity.

**Placement:** Right side of each message row. Always visible (NOT hover-only). See mockup at `channels/mockup.htm`.

**Interaction flow:**
1.  **Trigger:** Operator clicks `[send to actor]` on message N.
2.  **Dual-Selector Modal:** A modal appears with two dropdowns:
    *   **Target Channel**: (e.g., Blog Writing, Documentation, Development). Populated with all active channels except the current one.
    *   **Target Actor**: Initially disabled. Populates with **Active Actors** (members) of the chosen destination channel once selected. Populated via `DialogMvpService::getChannelMembers()`.
    *   **Routing Explanation**: A `<textarea>` for the operator to add context/instructions for the destination agent (e.g., "Gemini updated the chat mockup, here is what she did, can you...").
3.  **Confirm:** Operator clicks "Confirm".
4.  **Routing Event:** System creates a routing event in `lupo_routing_events` including the `destination_actor_id` and `routing_explanation`.
5.  **Post:** System posts message content to the destination channel's current thread.
    *   **Task creation:** **MUST** be **explicit** or **confirmed by the operator** (for example a dedicated **Create task** / **SEND TASK** confirmation step). **Routing alone** (including cross-channel send) **does not** create `lupo_dialog_pending_tasks` rows. The destination post uses the operator-selected `message_type`; do **not** silently coerce to `task` based only on `destination_actor_id`.
    *   **Context Prepending:** When a task **is** explicitly created, the `routing_explanation` **may** be prepended to the task description in the destination channel per product rules.
6.  **Indicators:**
    *   Source message gains indicator: "??? sent to [destination-channel-name]: [destination-actor-name]".
    *   Destination message shows: "??? from [source-channel-name], via [source-actor-name]".
        *   If an explanation was provided, it is rendered in a distinct block below the source context.

**Required API endpoint:**

```
POST /api/routing/send-to-channel
{
    "source_message_id": 123456789,
    "destination_channel_key": "blog-writing",
    "destination_actor_id": 102,
    "routing_explanation": "Gemini updated the chat mockup...",
    "routed_by_actor_id": 1,
    "active_context_actor_id": 10001
}
```

**Response:**
```json
{
    "status": "ok",
    "routing_id": 42,
    "destination_message_id": 123456800
}
```

**Architectural Rule:** This is a directed handover, not a broadcast. By selecting the **Actor** at the point of routing, we solve the "Broadcast vs. Directed" problem. We aren't just shouting into another room; we are handing a folder to a specific person in that room.

**Anti-pattern:** This is NOT copy-paste. NOT "share." It is a routing action with DB provenance. An implementation without a `lupo_routing_events` record is architecturally broken.

---
  What the mockup is missing (for the record when we build for real):

    1. 9-layer div layout ??? lupo-layers.js uses LupoLayerInit() which scans for divs with IDs ending in Div and
       wraps them as LupoLayer objects (positioning, sliding, clip, show/hide). The mockup uses a plain CSS grid.
       The real build needs div IDs like feedDiv, leftPanelDiv, inputAreaDiv etc. so the layer system can address
       them.

    2. Fall-forward transport system ??? the mockup has no transport at all. The real build needs the full sequence
       from channels/index.php: Base mode on load ??? 500ms startup probe ??? one-way promotion to XMLHTTP
       (lockIntoAsync) ??? 2500ms poll cycle ??? DOM reload at 500 lines ??? form.submit() fallback on AJAX failure.

    3. `admin_layout.php` wrapper ??? the mockup is a standalone .htm file. Production goes through ob_start() /
       ob_get_clean() into the admin layout.

    4. **Template-first / staged delivery** ??? follow **Template-First Implementation Rule (Normative)** and **Staged Development Workflow (Required)** at the head of this PRD; operator fragments under **`lupo-templates/admin/`**, then **`lupo_t()`** per **[PRD 45](45_template_first_staged_ui_workflow.md)**, then **`require`** from **`channels/index.php`** into admin chrome (example: **`?api_dashboard=1`**).

---
## Active Target Bar

**(Orchestration-primary UI; not a 4.1.3 ship gate; may be simplified or absent in 4.1.3-only builds.)**

The Active Target Bar is a persistent UI element shown **above the input area and below the chat feed**. It replaces the former "Selected Context Bar."

**Content:**
```
SENDING TO: LEXA    [CAPTAIN] [DEVIN] [ERIC] [LEXA]
```

- **Left:** `SENDING TO: {ACTOR_NAME}` ??? always displayed; never blank
- **Right:** All available Target Actor Tabs as clickable buttons
- **Active tab:** Bold/highlighted with the target actor's `text_color`
- **Inactive tabs:** Muted

**Switching:** Clicking a tab calls `POST /api/context/switch` with `{ "actor_id": N }`. Session updates `$_SESSION['active_target_actor_id'] = N`. Response: `{ "ok": true }`. The DOM **should not** full-reload; **projection** is unchanged by tab alone (only outbound `to_actor_id` for the next compose). Left panel and input area update via JS.

**No hidden state rule:** The active target is ALWAYS shown. If no tab has been manually selected, the first available tab is active and the bar shows it. There is no silent default.

**Tab registration:** For 4.1.2 MVP: tabs are seeded from the registered `actor_type = 'human_persona'` rows in `lupo_actors` (actor_id ??? 10,000).

---

## Visual Target Feedback Rule

When a Target Actor Tab is selected, the interface provides **visual confirmation** of the active target by syncing the input area's background color to that actor's thread color.

**Rule:** The `.chat-input-area` element and the Active Target Bar background MUST inherit the `background_color` of the selected target actor's thread (from `lupo_dialog_threads.color` for that actor's current thread), or fall back to the actor's registered `thread_color_override` in `lupo_actors`.

**Implementation:**

```js
// Called after every tab switch (POST /api/context/switch response)
function applyTargetColor(actorThreadColor) {
    document.documentElement.style.setProperty('--input-bg', '#' + actorThreadColor);
    document.querySelector('.chat-input-area').style.backgroundColor = '#' + actorThreadColor;
    document.querySelector('.lupo-active-target-bar').style.backgroundColor = '#' + actorThreadColor + '22'; // 13% opacity tint
}
```

**CSS Variable:** `--input-bg` is set dynamically at tab switch. Default (no tab selected): `#1a1b1e` (dark neutral). The variable is consumed by:
```css
.chat-input-area  { background-color: var(--input-bg, #1a1b1e); transition: background-color 0.2s ease; }
.lupo-active-target-bar { background-color: var(--input-bg-tint, rgba(0,0,0,0)); }
```

**Anti-pattern:** Do NOT apply the actor color to the entire chat feed or the left panel. Color feedback is scoped to the input area and the Active Target Bar ONLY. The feed remains neutral.

**Rationale:** The operator must know at a glance which actor they are about to address. Color-coding the input area to match that actor's thread color provides unambiguous, always-visible confirmation without overlaying text or modal dialogs.

---

## Dual-Button Logic (SEND MESSAGE / SEND TASK)

**(Orchestration-primary UI; not required for 4.1.3 non-AI live help parity.)**

The input area has TWO send buttons, not one. Each button sends the same composed text but with different semantic treatment.

| Button | Label | Behavior | DB Effect |
|---|---|---|---|
| **SEND MESSAGE** | `[Send Message]` | Posts as `message_type = 'stdout'`. Standard chat message. Appears in feed under sender's name. | Inserts into `lupo_dialog_messages` only. |
| **SEND TASK** | `[Send Task]` | Prepends `[task]` to the message body. Posts as `message_type = 'task'`. | Inserts into `lupo_dialog_messages` AND creates a row in `lupo_dialog_pending_tasks`. |

**SEND TASK ??? full behavior:**
1. Input text is used as `task_description`.
2. `to_actor_id` is taken from `$_SESSION['active_target_actor_id']` (the active Target Actor Tab).
3. A row is inserted into `lupo_dialog_pending_tasks` with `status = 'pending'` and `assigned_to_actor_id = to_actor_id`.
4. A chat message is posted with `message_type = 'task'` (renders with yellow left border, bold ??? see Task Rendering in Chat).
5. A routing event is created in `lupo_routing_events` linking the message to the task record.

**SEND MESSAGE ??? full behavior:**
1. Input text posted as-is.
2. `to_actor_id` taken from `$_SESSION['active_target_actor_id']`.
3. `message_type = 'stdout'`.
4. No task record created.

**API endpoint for SEND TASK:**
```
POST /api/task/create-from-message
{
    "body": "fix header in validate_actor_id.php",
    "to_actor_id": 10002,
    "channel_id": 3,
    "thread_id": 42,
    "from_actor_id": 1
}
```

**API endpoint for SEND MESSAGE:** Existing `POST /api/chat/send` (unchanged).

**Visual differentiation of the two buttons:**
- `[Send Message]` ??? muted style, secondary appearance
- `[Send Task]` ??? primary style, highlighted in the target actor's `background_color` tint ??? reinforces that it is a directive, not a comment

**Constitutional rule:** Only SEND TASK creates a `lupo_dialog_pending_tasks` record. SEND MESSAGE never creates task records. This distinction preserves the integrity of the task queue and prevents accidental task creation from chat commentary.

---

## Thread Color Assignment Logic & Config

Color sequences are defined in `config/global_atoms.yaml`:

```yaml
chat_colors:
    backgrounds:
        - "fefdcd"
        - "cbcefe"
        - "caedbe"
        - "cccbba"
        - "aecddc"
        - "fafafb"
        - "faacaa"
        - "fbddef"
        - "cfaaef"
        - "aedcbd"
        - "bbffff"
        - "fedabf"
    text_operators:
        - "426446"
        - "224646"
        - "466286"
        - "828468"
        - "866482"
        - "484668"
        - "888286"
        - "224882"
        - "486882"
        - "824864"
        - "668266"
        - "444468"
    text_clients:
        - "040662"
        - "240462"
        - "462040"
        - "404062"
        - "604000"
        - "662640"
        - "242642"
        - "464406"
        - "404060"
        - "442662"
        - "442022"
        - "200220"
```

**Assignment Logic:**
- When a new thread is created, assign colors from the above sequences based on the thread count in the channel (modulo sequence length).
- All messages in a thread use the thread's assigned colors.

### Agent-Specific Color Override (Optional)

Thread-based colors are **primary**. Agent-based colors are an **optional override** that can be applied when per-agent visual distinction is needed within a thread:

| Agent | Color |
|-------|-------|
| CURSOR | Blue (#1E88E5) |
| CLAUDE | Purple (#8E44AD) |
| CASCADE | Green (#2ECC71) |
| WINDSURF | Yellow (#F1C40F) |
| LILITH/DeepSeek | Magenta (#E91E63) |
| COUNTERMEASURE | Orange (#FF9800) |
| CAPTAIN_WOLFIE | Brown/Gold (#8D6E63) |

**Agent Colors Table Schema:**
```sql
CREATE TABLE lupo_agent_colors (
    actor_id BIGINT NOT NULL PRIMARY KEY,
    background_color VARCHAR(7) NOT NULL,    -- Hex color, e.g., '#1E88E5'
    text_color VARCHAR(7) DEFAULT '#FFFFFF',
    last_used_ymdhis BIGINT NOT NULL
);
```

**Usage:**
- Agent colors are stored per `actor_id` and applied to all messages from that agent
- Default colors are assigned automatically when an agent first posts a message
- This system can be used alongside or instead of thread-based colors depending on UI requirements

## Agent Integration Table & Wrapper

### Agent Actor IDs and Default Channels

| Agent            | actor_id | Default Channel   |
|------------------|----------|------------------|
| CAPTAIN_WOLFIE   | 1        | command          |
| LILITH/DeepSeek  | 2        | auditing         |
| CURSOR           | 102      | development      |
| CLAUDE           | 116      | development      |
| CASCADE          | 117      | documentation    |
| WINDSURF         | 118      | planning         |
| COUNTERMEASURE   | 119      | countermeasure   |

### Agent Wrapper Script (agent_wrapper.php)

Agents use a wrapper script to capture stdout/stderr and post to the chat system as messages.

**Usage:**
```sh
php lupo-bin/agent_wrapper.php <actor_id> <channel_key> <thread_key> -- <command>
# Example:
php lupo-bin/agent_wrapper.php 102 development 2026-04-12 -- php script.php
```

**Behavior:**
- Captures stdout and stderr from the agent process.
- Posts each line as a separate message (type: stdout or stderr) to the chat system.
- Logs start and completion as 'system' messages.
## Recent Files Browser/Table

### Table: lupo_dialog_recent_files

**Purpose**: Tracks what agents and humans have accessed or written to files.

**Description**: 
- Monitors file access patterns across the repository
- Enables a sidebar in the chat UI showing recently accessed files per actor
- Helps users understand what files are being actively worked on
- Provides quick access to files that are part of ongoing conversations

**Key Fields**:
- `file_path_from_root`: Which file was accessed
- `accessed_by_actor_id`: Who accessed the file (agent or human)
- `accessed_ymdhis`: When the file was accessed
- `file_size`: Size of the file at access time

```sql
CREATE TABLE lupo_dialog_recent_files (
    recent_file_id BIGINT NOT NULL PRIMARY KEY,
    file_path_from_root VARCHAR(512) NOT NULL,
    content_id BIGINT NULL,                        -- Links to lupo_contents if imported
    accessed_by_actor_id BIGINT NOT NULL,
    accessed_ymdhis BIGINT NOT NULL,
    file_size BIGINT DEFAULT 0,
    is_deleted TINYINT DEFAULT 0,
    INDEX idx_accessed (accessed_ymdhis DESC),
    INDEX idx_actor (accessed_by_actor_id),
    UNIQUE KEY uk_actor_file (accessed_by_actor_id, file_path_from_root(255))
);
```

**Purpose:**
- Enables a sidebar in the chat UI showing recently accessed files per actor.

### File Tracking Hooks

Files are tracked from multiple sources to populate the recent files sidebar:

```php
// lupo-includes/track_file_access.php
// Called whenever a file is accessed or modified

function track_file_access($file_path_from_root, $actor_id) {
    $db = DatabaseFactory::getConnection();
    $now = timestamp_ymdhis::now();
    
    // Upsert into recent_files
    $db->query(
        "INSERT INTO lupo_dialog_recent_files (file_path_from_root, accessed_by_actor_id, accessed_ymdhis, file_size)
         VALUES (?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE 
         accessed_ymdhis = VALUES(accessed_ymdhis),
         file_size = VALUES(file_size)",
        [$file_path_from_root, $actor_id, $now, filesize($file_path_from_root)]
    );
}
```

**File Tracking Sources:**
- Agent file writes (via `file_put_contents` wrapper)
- Manual file opens via web interface
- Database imports (`lupo_contents` rows)
- IDE file edits through the agent wrapper

## Task System

### Purpose

Coordinates work across multiple parallel agents. Task assignments travel through the task queue ??? never through the chat stream. The chat is for human oversight; the task queue is for agent coordination.

### Chat Command Syntax

| Command | Format / Example | Action |
|---------|---------|--------|
| `[task] who: X what: Y` | `[task] who: CURSOR what: fix header` | Assign task to IDE agent |
| `[task]` | `[task] title: Fix schema, assigned_to: Gemini, priority: HIGH` | Create tracked task |
| `[task update]` | `[task update] TASK-001 status: DONE` | Update task status |
| `[task list]` | `[task list]` | Show all tasks |
| `[task next]` | `[task next] assigned_to: ClaudeCode` | Get next task for agent |
| `@AGENT message` | `@CURSOR fix the header in validate_actor_id.php` | Direct message |
| `message` (no prefix) | `Everyone please check your headers` | Broadcast |

### Database Schema

**Table roles:** **`lupo_dialog_pending_tasks`** is the **runtime queue** (agent polling, SEND TASK handoffs, short-lived operational state). **`{{prefix}}tasks`** is **long-term / workflow tracking** for broader task metadata across agents and humans ??? not interchangeable with the pending queue; do not assume one replaces the other.

**Primary keys:** DDL below uses `BIGINT NOT NULL PRIMARY KEY` on id columns. **`task_id` / `recent_file_id` MUST** be assigned via a **deterministic application-layer allocator** (for example **`IdGenerator`** per root doctrine); **do not** rely on database **`AUTO_INCREMENT`**.

**IDE agent task queue** (`lupo_dialog_pending_tasks`):
```sql
CREATE TABLE lupo_dialog_pending_tasks (
    task_id BIGINT NOT NULL PRIMARY KEY,
    assigned_to_actor_id BIGINT NOT NULL,        -- which agent should do this
    assigned_by_actor_id BIGINT NOT NULL,        -- CAPTAIN_WOLFIE (actor_id 1)
    task_description TEXT NOT NULL,
    status ENUM('pending', 'in_progress', 'completed', 'failed', 'cancelled') DEFAULT 'pending',
    result_summary TEXT NULL,
    created_ymdhis BIGINT NOT NULL,
    started_ymdhis BIGINT NULL,
    completed_ymdhis BIGINT NULL,
    INDEX idx_assigned_to (assigned_to_actor_id, status),
    INDEX idx_created (created_ymdhis)
);
```

**General task tracker** (full workflow, all agents):
```sql
CREATE TABLE {{prefix}}tasks (
    task_id BIGINT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    assigned_to VARCHAR(64) NULL,
    priority ENUM('HIGH', 'MED', 'LOW') NOT NULL DEFAULT 'MED',
    status ENUM('TODO', 'IN_PROGRESS', 'DONE', 'BLOCKED', 'CANCELLED') NOT NULL DEFAULT 'TODO',
    dependencies TEXT NULL,
    created_by VARCHAR(64) NOT NULL,
    created_ymdhis BIGINT NOT NULL,
    started_ymdhis BIGINT NULL,
    completed_ymdhis BIGINT NULL,
    notes TEXT NULL,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT NULL,
    PRIMARY KEY (task_id)
);
```

### Agent Task Polling

Each agent periodically checks for assigned tasks (via cron or IDE plugin):

```php
// lupo-scripts/agent_poll_tasks.php
// Run by agent IDE every 30 seconds

require_once dirname(__DIR__) . '/lupo-includes/bootstrap.php';
require_once dirname(__DIR__) . '/lupo-includes/chat/message_functions.php';

if ($argc < 2) {
    fwrite(STDERR, "Usage: agent_poll_tasks.php <actor_id> [channel_key] [thread_key]\n");
    exit(1);
}

$actor_id = (int)$argv[1];
$channel_key = $argv[2] ?? 'development';
$thread_key = $argv[3] ?? date('Y-m-d');

$db = DatabaseFactory::getConnection();

$tasks = $db->fetchAll(
    "SELECT task_id, task_description FROM lupo_dialog_pending_tasks
     WHERE assigned_to_actor_id = ? AND status = 'pending'
     ORDER BY created_ymdhis ASC",
    [$actor_id]
);

foreach ($tasks as $task) {
    $db->query(
        "UPDATE lupo_dialog_pending_tasks SET status = 'in_progress' WHERE task_id = ?",
        [$task['task_id']]
    );
    insert_message($actor_id, 0, "Received task: {$task['task_description']}", $channel_key, $thread_key, 'system');
    $result = execute_agent_task($task['task_description'], $actor_id);
    $db->query(
        "UPDATE lupo_dialog_pending_tasks
         SET status = 'completed', result_summary = ?, completed_ymdhis = ?
         WHERE task_id = ?",
        [$result, timestamp_ymdhis::now(), $task['task_id']]
    );
    insert_message($actor_id, 0, "Task completed: {$result}", $channel_key, $thread_key, 'stdout');
}
```

### API Endpoints

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/api/tasks/create` | POST | Create task |
| `/api/tasks/update` | POST | Update task |
| `/api/tasks/list` | GET | List tasks (filter by status, assigned_to) |
| `/api/tasks/next` | GET | Get next available task for an agent |
| `/api/task/create` | POST | Create task (chat-originated, CAPTAIN_WOLFIE) |

### UI Integration

The chat sidebar gains a **"Tasks" tab** showing current tasks (TODO, IN_PROGRESS), tasks assigned to you, blocked tasks, and recently completed. Clicking a task opens details, allows status change, and links to the relevant conversation.

---

## Task Rendering in Chat

Task-related messages in the chat feed are visually distinct from normal stdout messages.

### Message Types and Visual Treatment

| `message_type` | Visual Class | Background | Display Style | Example |
|---|---|---|---|---|
| `stdout` | `chat-stdout` | Thread color (sender) | Normal monospace row | `[14:32:01] [CURSOR] working on validate_actor_id.php` |
| `stderr` | `chat-stderr` | Thread color (sender) | Italic, warning text color `#dc3545` | `[14:32:05] [CURSOR] WARNING: header field missing` |
| `task` | `chat-task` | **White (`#ffffff`)** ??? originates from CAPTAIN | Bold, left yellow border `#ffc107` | `[14:32:15] [CAPTAIN] [task] ??? CURSOR: fix header` |
| `system` | `chat-system` | Dark neutral `#0d0d0f` | Italic, muted text | `[14:32:00] [SYSTEM] Thread created for 2026-04-16` |
| `task_status` | `chat-task-status` | **Sender's actor thread `background_color`** | Bold, status badge colored by outcome | `[14:33:01] [CURSOR] [status: completed] Task #42 done` |
| `routing` | `chat-routing` | Transparent / feed neutral | Indented, cyan arrow + backlink | `??? [14:34:00] sent to blog-writing by CAPTAIN` |

> **Background color rules:**
> - `task` messages use **white** (`#ffffff`) because they originate from the CAPTAIN (the human assigning work). White signals a human directive, not agent output.
> - `task_status` messages use the **sending agent's thread `background_color`**. Each agent has a registered thread color; their status updates render in that color so the source is immediately identifiable.
> - All other types use the thread or actor color per the existing color assignment logic.

### Task Message Anatomy

A task assignment message (`message_type = 'task'`) renders with:
1. Timestamp
2. Sender tag (always the assigning actor ??? CAPTAIN or human persona)
3. `[task]` label in bold yellow
4. Assignee name (the `to_actor_id`'s display name)
5. Task description (truncated to 120 chars with "...")
6. Link: `[view task]` ??? opens task detail

**Background color:** White (`#ffffff`). This is a human directive ??? white signals CAPTAIN origin, not agent output. The white background makes task assignment rows visually dominant and unambiguous regardless of surrounding thread color.

**Example rendered line (on white background):**
```
[14:32:15] [CAPTAIN] [task] ??? CURSOR: fix header in validate_actor_id.php [view task]
```

### Task Status Message Anatomy

A task status update (`message_type = 'task_status'`) renders with:
1. Timestamp
2. Agent name (the `from_actor_id`'s display name)
3. Status badge: `[completed]` / `[failed]` / `[in_progress]` (color-coded)
4. Task reference
5. Optional result summary (first 80 chars)

**Background color:** The sending agent's registered thread `background_color` (from `lupo_dialog_threads.color` for that actor). This visually ties the status update to the agent that produced it. Implementation: PHP renders `style="background-color: #{$actor_thread_color};"` on the message row.

**Example rendered line (on CURSOR's thread color #e3f2fd):**
```
[14:55:01] [CURSOR] [status: completed] Task #42 ??? validate_actor_id.php headers fixed [view task]
```

### CSS Classes Required

```css
/* Task assignment ??? always white background */
.chat-task        { background-color: #ffffff; color: #1a1a1a; font-weight: bold;
                    border-left: 3px solid #ffc107; padding-left: 6px; }

/* Task status ??? background set inline from actor thread color (PHP) */
.chat-task-status { font-weight: bold; /* background-color: set inline per actor */ }

/* Standard types */
.chat-stderr      { font-style: italic; color: #dc3545; }
.chat-system      { font-style: italic; color: #6c757d; background-color: #0d0d0f; }
.chat-routing     { margin-left: 20px; color: #17a2b8; }

/* Status badges (inline spans) */
.badge-completed  { color: #28a745; font-weight: bold; }
.badge-failed     { color: #dc3545; font-weight: bold; }
.badge-progress   { color: #ffc107; font-weight: bold; }
```

**PHP rendering note:** For `task_status`, the row background is set inline:
```php
if ($msg['message_type'] === 'task_status') {
    $actor_color = get_actor_thread_color($msg['from_actor_id']); // returns hex without #
    echo '<div class="chat-line chat-task-status" style="background-color:#' . htmlspecialchars($actor_color) . ';">';
}
```

### Routing Message Anatomy

A cross-channel routing indicator (`message_type = 'routing'`) renders below the source message:
```
  ??? sent to blog-writing by CAPTAIN at 14:34 [view in blog-writing]
```

And in the destination channel, a received routing indicator renders above the message:
```
  ??? from documentation at 14:32 via CAPTAIN [view source]
```

**Implementation note:** `channels/index.php` already has `chat-line-task` CSS class (line 236) and `message_type === 'task'` conditional rendering (line 259). The additions above formalize the full vocabulary and add `task_status` and `routing` types.

---

## Implementation Patterns (PHP Reference)

### Color Assignment Functions
```php
function get_chat_color_sequences() {
    global $atoms;
    return $atoms['chat_colors'] ?? [ /* fallback defaults */ ];
}

function get_next_thread_color($channel_id, $color_type) {
    $db = DatabaseFactory::getConnection();
    $colors = get_chat_color_sequences();
    $color_list = $colors[$color_type] ?? ['fefdcd'];
    $result = $db->fetchRow("SELECT COUNT(*) as count FROM lupo_threads WHERE channel_id = ?", [$channel_id]);
    $index = (int)$result['count'];
    return $color_list[$index % count($color_list)];
}
```

### Thread Creation
```php
function create_thread($channel_key, $thread_key, $thread_name = null) {
    $db = DatabaseFactory::getConnection();
    $channel = $db->fetchRow("SELECT channel_id FROM lupo_channels WHERE channel_key = ?", [$channel_key]);
    if (!$channel) return false;
    $channel_id = $channel['channel_id'];
    $background_color = get_next_thread_color($channel_id, 'backgrounds');
    $text_color = get_next_thread_color($channel_id, 'text_operators');
    $text_color_alt = get_next_thread_color($channel_id, 'text_clients');
    $now = timestamp_ymdhis::now();
    $db->query("INSERT INTO lupo_threads (channel_id, thread_key, thread_name, background_color, text_color, text_color_alt, created_ymdhis, last_message_ymdhis) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [$channel_id, $thread_key, $thread_name, $background_color, $text_color, $text_color_alt, $now, $now]);
    return $db->lastInsertId();
}
```

### Message Insertion
```php
function insert_message($from_actor_id, $to_actor_id, $message_text, $channel_key, $thread_key, $message_type = 'stdout') {
    $db = DatabaseFactory::getConnection();
    $thread_id = get_or_create_thread($channel_key, $thread_key);
    if (!$thread_id) return false;
    $now = timestamp_ymdhis::now();
    if (strlen($message_text) > 65535) $message_text = substr($message_text, 0, 65500) . "\n...[TRUNCATED]";
    $db->query("INSERT INTO lupo_dialog_messages (thread_id, from_actor_id, to_actor_id, message_text, message_type, created_ymdhis) VALUES (?, ?, ?, ?, ?, ?)", [$thread_id, $from_actor_id, $to_actor_id, $message_text, $message_type, $now]);
    $db->query("UPDATE lupo_threads SET last_message_ymdhis = ? WHERE thread_id = ?", [$now, $thread_id]);
    return $db->lastInsertId();
}
```

### Message Rendering
```php
function render_messages($messages, $current_actor_id) {
    if (empty($messages['messages'])) return '';
    $thread = $messages['thread'];
    $output = '';
    foreach ($messages['messages'] as $msg) {
        $is_self = ($msg['from_actor_id'] == $current_actor_id);
        $text_color = $is_self ? $thread['text_color'] : $thread['text_color_alt'];
        $time_str = timestamp_ymdhis::toHuman($msg['created_ymdhis']);
        $agent_tag = ($msg['from_actor_id'] >= 100 && $msg['from_actor_id'] <= 999) ? "[{$msg['from_name']}] " : '';
        $type_class = match($msg['message_type']) {
            'stderr' => 'chat-stderr',
            'task' => 'chat-task',
            'system' => 'chat-system',
            default => 'chat-stdout',
        };
        $output .= sprintf('<div class="chat-message %s" style="background-color: #%s;">', $type_class, $thread['background_color']);
        $output .= sprintf('  <span class="chat-timestamp" style="color: #%s;">%s</span>', $text_color, $time_str);
        $output .= sprintf('  <span class="chat-sender" style="color: #%s;">%s%s: </span>', $text_color, $agent_tag, htmlspecialchars($msg['from_name']));
        $output .= sprintf('  <span class="chat-text" style="color: #%s;">%s</span>', $text_color, htmlspecialchars($msg['message_text']));
        $output .= '</div>';
    }
    return $output;
}
```
## API Endpoints & Chat UI Reference

### API Endpoints

#### POST /api/chat/send
Accepts new messages from users and agents.

**Request:**
```json
{
    "from_actor_id": 1,
    "to_actor_id": 0,
    "message": "[task] who: CURSOR what: fix header in validate_actor_id.php",
    "channel_key": "development",
    "thread_key": "2026-04-12"
}
```

**Response:**
```json
{
    "status": "ok",
    "message_id": 123456789,
    "task_assigned": true,
    "assigned_to": "CURSOR"
}
```

#### GET /api/chat/messages
Polls for new messages since last seen.

**Request:**
```
GET /api/chat/messages?channel_key=development&thread_key=2026-04-12&after_time=20260412143201
```

**Response:**
```json
{
    "status": "ok",
    "thread": {
        "thread_id": 42,
        "background_color": "fefdcd",
        "text_color": "426446",
        "text_color_alt": "040662"
    },
    "messages": [
        {
            "message_id": 123456785,
            "from_name": "CURSOR",
            "message_text": "working on validate_actor_id.php",
            "created_ymdhis": 20260412143201,
            "message_type": "stdout"
        }
    ],
    "last_time": 20260412143201
}
```

#### POST /api/chat/task
Creates a task for an agent.

**Request:**
```json
{
    "assigned_to": "CURSOR",
    "task_description": "fix header in validate_actor_id.php",
    "assigned_by": 1,
    "channel_key": "development",
    "thread_key": "2026-04-12"
}
```

#### POST /api/transcript/append
Accepts new messages from agents (alternative endpoint for agent scripts).

**Request:**
```json
{
    "actor_id": 102,
    "actor_name": "CURSOR",
    "message": "working on validate_actor_id.php",
    "channel_key": "development",
    "thread_id": "2026-04-12",
    "message_type": "stdout"
}
```

**Response:**
```json
{
    "status": "ok",
    "dialog_message_id": 123456789
}
```

#### GET /api/transcript/latest
Polls for new messages since last seen (alternative endpoint).

**Request:**
```
GET /api/transcript/latest?channel_key=development&thread_id=2026-04-12&since_id=123456780&limit=50
```

**Response:**
```json
{
    "messages": [
        {
            "dialog_message_id": 123456785,
            "actor_name": "CURSOR",
            "message_text": "working on validate_actor_id.php",
            "created_ymdhis": 20260412143201,
            "background_color": "#1E88E5",
            "text_color": "#FFFFFF"
        }
    ]
}
```

#### POST /api/task/create
Creates a new task (sent by CAPTAIN_WOLFIE via chat).

**Request:**
```json
{
    "assigned_to_actor_id": 102,
    "task_description": "review PRD 81 write open questions to status folder",
    "assigned_by_actor_id": 1
}
```

#### GET /api/files/recent
Returns recently accessed files.

**Request:**
```
GET /api/files/recent?limit=20
```

**Response:**
```json
{
    "status": "ok",
    "files": [
        {
            "file_path_from_root": "lupo-docs/prd/81_agent_orchestration_chat.md",
            "accessed_ymdhis": 20260412143201,
            "content_id": null,
            "file_size": 12456
        }
    ]
}
```

### Chat UI Implementation

#### HTML Structure
```html
<!DOCTYPE html>
<html>
<head>
    <title>Lupopedia Agent Command Center</title>
    <style>
        .chat-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
        .chat-messages {
            height: 60vh;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .chat-message {
            padding: 4px 8px;
            margin: 2px 0;
            border-radius: 4px;
            font-family: monospace;
            font-size: 13px;
        }
        .chat-timestamp {
            margin-right: 10px;
            font-size: 11px;
        }
        .chat-sender {
            font-weight: bold;
            margin-right: 10px;
        }
        .chat-stderr {
            font-style: italic;
        }
        .chat-task {
            font-weight: bold;
        }
        .chat-system {
            font-style: italic;
        }
        .chat-input-area {
            margin-top: 10px;
        }
        .chat-input {
            width: 80%;
            height: 60px;
            padding: 8px;
            font-family: monospace;
        }
        .send-button {
            height: 60px;
            vertical-align: top;
        }
        .tab-bar {
            margin-top: 10px;
            border-bottom: 1px solid #ccc;
        }
        .tab {
            display: inline-block;
            padding: 8px 16px;
            cursor: pointer;
            border: 1px solid #ccc;
            border-bottom: none;
            background: #f0f0f0;
        }
        .tab.active {
            background: #fff;
            font-weight: bold;
        }
        .recent-files-sidebar {
            float: right;
            width: 250px;
            margin-left: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            background: #f9f9f9;
        }
        .recent-file {
            padding: 4px;
            cursor: pointer;
            font-family: monospace;
            font-size: 12px;
        }
        .recent-file:hover {
            background: #e0e0e0;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="recent-files-sidebar" id="recent-files">
            <h4>???? Recent Files</h4>
            <div id="file-list">Loading...</div>
        </div>
        
        <div class="chat-main">
            <div class="chat-messages" id="chat-messages">
                <!-- Messages appear here -->
            </div>
            
            <div class="chat-input-area">
                <textarea id="chat-input" class="chat-input" placeholder="Type message or [task] who: AGENT what: DESCRIPTION"></textarea>
                <button id="send-btn" class="send-button">Send</button>
            </div>
            
            <div class="tab-bar">
                <div class="tab active" data-tab="chat">???? Chat</div>
                <div class="tab" data-tab="files">???? Files</div>
                <div class="tab" data-tab="search">???? Search</div>
                <div class="tab" data-tab="tasks">???? Tasks</div>
                <div class="tab" data-tab="logs">???? Logs</div>
                <div class="tab" data-tab="settings">?????? Settings</div>
            </div>
        </div>
    </div>

    <script>
        let lastTime = 0;
        let currentChannel = 'development';
        let currentThread = '2026-04-12';
        
        // Poll for new messages every 2 seconds
        function pollMessages() {
            fetch(`/api/chat/messages?channel_key=${currentChannel}&thread_key=${currentThread}&after_time=${lastTime}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'ok' && data.messages && data.messages.length > 0) {
                        // Update thread colors
                        if (data.thread) {
                            document.documentElement.style.setProperty('--thread-bg', '#' + data.thread.background_color);
                        }
                        
                        // Append messages
                        const container = document.getElementById('chat-messages');
                        data.messages.forEach(msg => {
                            const div = document.createElement('div');
                            div.className = `chat-message chat-${msg.message_type}`;
                            div.style.backgroundColor = `#${data.thread.background_color}`;
                            div.innerHTML = `
                                <span class="chat-timestamp" style="color: #${data.thread.text_color}">${formatTimestamp(msg.created_ymdhis)}</span>
                                <span class="chat-sender" style="color: #${data.thread.text_color}">[${escapeHtml(msg.from_name)}]: </span>
                                <span class="chat-text" style="color: #${data.thread.text_color}">${escapeHtml(msg.message_text)}</span>
                            `;
                            container.appendChild(div);
                        });
                        
                        // Update lastTime
                        lastTime = data.last_time;
                        
                        // Scroll to bottom
                        container.scrollTop = container.scrollHeight;
                    }
                })
                .catch(error => console.error('Poll error:', error));
        }
        
        // Send message
        function sendMessage() {
            const input = document.getElementById('chat-input');
            const message = input.value.trim();
            if (!message) return;
            
            fetch('/api/chat/send', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    from_actor_id: 1,  // CAPTAIN_WOLFIE
                    to_actor_id: 0,
                    message: message,
                    channel_key: currentChannel,
                    thread_key: currentThread
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'ok') {
                    input.value = '';
                    // Trigger immediate poll
                    pollMessages();
                }
            })
            .catch(error => console.error('Send error:', error));
        }
        
        // Format timestamp for display
        function formatTimestamp(ymdhis) {
            const str = ymdhis.toString();
            return `${str.substr(8,2)}:${str.substr(10,2)}:${str.substr(12,2)}`;
        }
        
        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Start polling
        setInterval(pollMessages, 2000);
        pollMessages(); // Initial load
        
        // Send button handler
        document.getElementById('send-btn').addEventListener('click', sendMessage);
        
        // Enter key to send (Shift+Enter for new line)
        document.getElementById('chat-input').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    </script>
</body>
</html>
```

### Tab Navigation System

The bottom tab bar provides navigation to different views **without leaving the chat context**:

| Tab | Purpose |
|-----|---------|
| **Files** | Browse all files in the repository (not just recent) |
| **Search** | Search messages, files, or tasks |
| **Chat** | Return to main chat view |
| **Tasks** | View all pending/completed tasks |
| **Logs** | View system logs and agent stderr output |
| **Settings** | Configure agent colors, channel/thread selection |

**Key Navigation Rules:**
- Clicking a tab does NOT clear the chat
- Chat remains visible (or collapses to a sidebar) while browsing
- Tab content loads dynamically via AJAX
- Each tab maintains its own state
- Users can switch between tabs without losing context

**Tab Implementation Pattern:**
```javascript
// Tab switching logic
document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', function() {
        // Remove active class from all tabs
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        
        // Add active class to clicked tab
        this.classList.add('active');
        
        // Load tab content
        const tabName = this.dataset.tab;
        loadTabContent(tabName);
    });
});

function loadTabContent(tabName) {
    const contentArea = document.getElementById('tab-content');
    
    // Show loading spinner
    contentArea.innerHTML = '<div class="loading">Loading...</div>';
    
    // Fetch tab content
    fetch(`/api/ui/tab/${tabName}`)
        .then(response => response.json())
        .then(data => {
            contentArea.innerHTML = data.html;
            
            // Initialize tab-specific functionality
            initializeTab(tabName);
        })
        .catch(error => {
            contentArea.innerHTML = '<div class="error">Failed to load tab</div>';
        });
}
```

### Transport Model Doctrine

#### Startup Capability Negotiation

The transport mode is not assumed. At session start the client MUST probe for capabilities
before committing to a transport mode. Probing order is governed by server configuration
(`$CSLH_Config['chatmode']` or the Lupopedia equivalent in `lupo_modules.config_json`).

Capability levels, highest to lowest:
1. Fetch API (modern browsers) ??? preferred
2. XMLHttpRequest ??? legacy AJAX
3. Form submit / page refresh ??? degraded baseline

#### One-Way Promotion

Once a higher-capability transport is proven via a successful probe, the session is
**promoted and locked**. The proven mode is written to the session record. It DOES NOT
revert to a lower mode during normal operation.

#### Session Lock-In

The transport mode chosen at session start is stored in the session and applies to all
subsequent requests in that session. Runtime switching between transport modes is
**FORBIDDEN** because it would corrupt the client's polling cursor state.

#### Polling Model (Continuous Feed Doctrine)

The live feed MUST use incremental polling with `after_ymdhis` as the canonical cursor.

**Correct request:**
```
GET /lupo-api/dialog/fetch-messages.php?channel_id=X&after_ymdhis=20260414120000
```

**Server query (illustrative shape ??? MUST add projection predicates in production):**
```sql
SELECT dialog_message_id, from_actor_id, to_actor_id, message_text, message_type, created_ymdhis
FROM lupo_dialog_messages
WHERE channel_id = :channel_id
  AND thread_id = :thread_id
  AND created_ymdhis > :after_ymdhis
  AND is_deleted = 0
  AND (
    from_actor_id = :viewer_actor_id
    OR to_actor_id = :viewer_actor_id
    OR to_actor_id IN (/* policy-defined broadcast ids */)
  )
ORDER BY created_ymdhis ASC
```

**Normative:** `fetch-messages` (or equivalent) **must not** return rows the authenticated actor is not allowed to see. `:viewer_actor_id` is resolved server-side from session/auth ??? never from client JSON for authorization.

**Client behavior:**
1. On response, append each new message line to the bottom of the feed DOM
2. Advance the cursor: `lastSeen = max(created_ymdhis in response)`
3. Never re-render the full feed. Append only.
4. Polling interval floor: 2100ms (Crafty-validated minimum)

**Why `after_ymdhis`, not `last_message_id`:**
IdGenerator IDs are not monotonically sequential across concurrent writes (they include a
CSPRNG suffix). A query `WHERE dialog_message_id > :last_id` would produce incorrect
results when two messages are inserted in the same second with different random suffixes.
`created_ymdhis` is a monotonic timestamp. Use it.

**Goal: continuous feed with no full page reload during normal operation.**
The initial page render populates the feed. All subsequent updates are appended via
incremental poll. The user never sees a page reload unless the session guard fires
(after ~500 appended lines, a full reload resets DOM size ??? this is intentional).

#### No Framework Assumptions

The polling loop MUST be plain JavaScript. No React, no Vue, no framework event bus.
The fetch call is:
```javascript
fetch('/lupo-api/dialog/fetch-messages.php?...')
    .then(function(r){ return r.json(); })
    .then(function(data){ /* append lines, advance cursor */ })
    .catch(function(){ /* log only, retry next interval */ });
```

If `window.fetch` is unavailable, fall back to XMLHttpRequest. No framework polyfills.

---

### Anti-Patterns and Constraints

#### Forbidden UI Patterns
The following patterns **MUST NOT** be implemented as they violate the one-column chronological philosophy:

| Forbidden Pattern | Why It's Forbidden | Correct Approach |
|-------------------|-------------------|------------------|
| **Membership implies visibility** | Leaks unrelated visitor/actor traffic | Enforce **projection** in API + UI; membership drives **presence** and **authorize**, not automatic read of all rows |
| **Separate columns per agent** | Breaks chronological flow, creates visual silos | Interleaved messages in single column |
| **Message grouping/collapsing** | Hides context, breaks scanability | Show all messages with timestamps |
| **Tabbed agent views** | Forces context switching, loses conversation flow | Single unified view with color coding |
| **Floating chat bubbles** | Creates visual clutter, breaks scanability | Fixed-width messages with consistent formatting |
| **Avatar-based layouts** | Wastes space, emphasizes personality over content | Simple text-based sender tags |
| **Threaded nesting** | Creates visual hierarchy, breaks flat scanability | Flat chronological list with thread colors |

#### Forbidden Agent Behaviors
Agents must **NOT**:

| Forbidden Behavior | Why It's Forbidden | Correct Behavior |
|-------------------|-------------------|------------------|
| **Self-grading or validation** | Creates conflict of interest | Let other agents or CAPTAIN_WOLFIE validate |
| **Parroting user prompts** | Wastes tokens, adds no value | Process and respond with new information |
| **Switching roles mid-conversation** | Breaks conversation flow | Maintain consistent role throughout |
| **Continuing after TEST_COMPLETE** | Violates probe boundaries | Stop immediately when test is complete |
| **Accessing outside collection scope** | Violates coordination doctrine | Stay within assigned collection envelope |
| **Modifying files without headers** | Breaks validation system | Always include LUPOPEDIA HEADERS |

#### Architectural Constraints

1. **No separate agent channels as a loophole**: Agents do **not** get off-books private channels that bypass `lupo_dialog_messages` and routing policy. **Shared channel context** still means **per-row routing**; it does **not** mean every human sees every row.

2. **No private agent-to-agent DMs outside storage**: Agents cannot whisper off-record. Directed lines use **`from_actor_id` / `to_actor_id`**; **visibility follows projection**, not "CAPTAIN always sees all bodies" unless elevated mode says so.

3. **No Background Processing Without Logging**: Any background work must post status updates to the chat channel.

4. **No External API Calls Without Disclosure**: Agents must announce any external API calls in the chat before making them.

5. **No File Modifications Without Tracking**: All file changes must go through `track_file_access()` to update recent files.

#### Performance Anti-Patterns

| Anti-Pattern | Why It's Bad | Solution |
|--------------|--------------|----------|
| **Polling every 100ms** | Creates unnecessary load | Poll every 2-5 seconds |
| **Loading entire chat history** | Wastes bandwidth and memory | Load only recent messages with pagination |
| **Real-time color calculations** | Unnecessary CPU usage | Pre-calculate and store colors |
| **Synchronous file operations** | Blocks UI | Use async operations with progress indicators |

### Performance Requirements

#### Response Time Requirements

| Operation | Target Response Time | Maximum Acceptable |
|-----------|---------------------|-------------------|
| **Message send** | < 200ms | < 500ms |
| **Message poll** | < 100ms | < 250ms |
| **Thread creation** | < 300ms | < 1s |
| **Recent files load** | < 150ms | < 500ms |
| **Tab switch** | < 100ms | < 300ms |
| **Search query** | < 500ms | < 2s |
| **Task creation** | < 200ms | < 500ms |

#### Database Performance

**Required Indexes:**
```sql
-- Messages table
CREATE INDEX idx_messages_thread_time ON lupo_dialog_messages (dialog_thread_id, created_ymdhis DESC);
CREATE INDEX idx_messages_actor_time ON lupo_dialog_messages (from_actor_id, created_ymdhis DESC);

-- Threads table  
CREATE INDEX idx_threads_channel ON lupo_dialog_threads (channel_id, thread_key);
CREATE INDEX idx_threads_created ON lupo_dialog_threads (created_ymdhis DESC);

-- Recent files table
CREATE INDEX idx_recent_actor_time ON lupo_dialog_recent_files (accessed_by_actor_id, accessed_ymdhis DESC);
CREATE INDEX idx_recent_time ON lupo_dialog_recent_files (accessed_ymdhis DESC);

-- Tasks table
CREATE INDEX idx_tasks_assigned ON lupo_dialog_pending_tasks (assigned_to_actor_id, status);
CREATE INDEX idx_tasks_created ON lupo_dialog_pending_tasks (created_ymdhis DESC);
```

**Query Performance Targets:**
- Message retrieval: < 50ms for last 100 messages
- Thread list: < 30ms for all threads in channel
- Recent files: < 20ms for top 20 files
- Task lookup: < 25ms for pending tasks per agent

#### Frontend Performance

**JavaScript Bundle Size:**
- Core chat functionality: < 100KB minified
- Full UI with all tabs: < 250KB minified
- Use code splitting for tab-specific functionality

**Memory Usage:**
- Chat history in memory: Last 1000 messages maximum
- Auto-cleanup older messages when limit reached
- Lazy load older messages on scroll

**Network Optimization:**
- Use HTTP/2 for API calls
- Compress all API responses (gzip)
- Cache static assets (CSS/JS) for 1 hour
- Use CDN for static assets in production

#### Scalability Requirements

**Concurrent Users:**
- Support 100 concurrent users per server instance
- Horizontal scaling via load balancer
- Session state stored in database, not memory

**Message Volume:**
- Handle 10,000 messages per hour
- Archive old messages after 30 days
- Full-text search index for message content

**File Tracking:**
- Track 10,000 file accesses per hour
- Cleanup recent files older than 7 days
- Deduplicate file paths to save space

### Security Considerations

#### Authentication & Authorization

**API Token Requirements:**
- All API endpoints require valid session token
- Tokens expire after 24 hours of inactivity
- Use HTTP-only cookies for token storage
- Implement CSRF protection for all POST requests

**Actor Permissions:**
```php
// Permission matrix
$permissions = [
    'human' => [
        'can_send_messages' => true,
        'can_assign_tasks' => true,  // Only CAPTAIN_WOLFIE
        'can_create_threads' => true,
        'can_access_all_channels' => true
    ],
    'agent' => [
        'can_send_messages' => true,
        'can_assign_tasks' => false,
        'can_create_threads' => false,
        'can_access_all_channels' => false  // Only assigned channels
    ]
];
```

#### Input Validation & Sanitization

**Message Input:**
- Strip HTML tags from message content
- Escape special characters before display
- Limit message length to 10,000 characters
- Rate limit: 10 messages per minute per actor

**File Path Validation:**
```php
function validate_file_path($path) {
    // Prevent directory traversal
    if (strpos($path, '..') !== false) {
        return false;
    }
    
    // Ensure path is within repository
    $realPath = realpath($path);
    $repoRoot = realpath(dirname(__DIR__) . '/../../');
    
    return strpos($realPath, $repoRoot) === 0;
}
```

#### XSS Protection

**Output Escaping:**
- Use `htmlspecialchars()` with ENT_QUOTES flag
- Escape JSON responses with `json_encode()`
- Set Content-Type headers properly
- Use CSP headers to restrict inline scripts

**CSP Header Example:**
```
Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'
```

#### SQL Injection Prevention

**Parameterized Queries Only:**
```php
// NEVER do this:
$sql = "SELECT * FROM messages WHERE actor_id = " . $_GET['actor_id'];

// ALWAYS do this:
$stmt = $db->prepare("SELECT * FROM messages WHERE actor_id = ?");
$stmt->execute([$actor_id]);
```

#### Agent Security

**Agent Isolation:**
- Agents run in isolated environments
- No direct database access - use API only
- File system access limited to designated directories
- Network access restricted to whitelisted domains

**Task Execution Security:**
```php
function execute_agent_task($task, $actor_id) {
    // Validate task syntax
    if (!preg_match('/^[a-zA-Z0-9 _\-\.]+$/', $task)) {
        throw new SecurityException('Invalid task characters');
    }
    
    // Log task execution
    log_security_event('task_started', $actor_id, $task);
    
    // Execute with timeout
    $result = execute_with_timeout($task, 300); // 5 minute timeout
    
    log_security_event('task_completed', $actor_id, $task);
    
    return $result;
}
```

#### Audit Logging

**Security Events to Log:**
- Failed login attempts
- Permission denied errors
- Task assignments and completions
- File access violations
- API token generation/revocation

**Log Format:**
```json
{
    "timestamp": "20260412143201",
    "event_type": "security_violation",
    "actor_id": 102,
    "details": "File access denied: /etc/passwd",
    "ip_address": "192.168.1.100",
    "user_agent": "Mozilla/5.0..."
}
```

#### Data Privacy

**PII Protection:**
- No personal information in chat messages
- Hash email addresses if used
- Anonymize IP addresses in logs
- Data retention: Delete messages after 30 days

**GDPR Compliance:**
- Right to export personal data
- Right to delete personal data
- Clear data processing notices
- Data processing agreement for agents

### Implementation Phases

> *Historical planning record. Phases are labeled for reference; actual status tracked in task system.*

| Phase | Scope | Key Deliverables |
|-------|-------|-----------------|
| **1** Core Infrastructure | DB tables (`lupo_channels`, `lupo_dialog_threads`, `lupo_dialog_messages`), basic API endpoints, `agent_wrapper.php`, one-column UI, 2-second polling | Working chat, basic persistence |
| **2** Thread System & Colors | Thread creation, thread-based color assignment, thread management API | Multi-thread display with colors |
| **3** Agent Integration | Register all agents in `lupo_actors`, task polling, `lupo_dialog_pending_tasks` | All agents (CURSOR, CLAUDE, CASCADE, WINDSURF, LILITH, COUNTERMEASURE) integrated |
| **4** Advanced Features | `track_file_access()` hooks, recent files sidebar, tab navigation, search | Full tab nav (Files, Search, Chat, Tasks, Logs, Settings) |
| **5** Security & Performance | Auth/authorization, input validation, audit logging, DB indexes, caching | Secure endpoints, performance targets met |
| **6** Testing & Documentation | Unit tests, load testing, user docs, deployment guide | 90%+ coverage, deployment-ready |
| **7** Production Deployment | Production DB, load balancer, monitoring, user training | Live system with alerting |

**Success criteria per phase:** features working per PRD, performance requirements met, security implemented, tests passing.

---

## HERMES Memory & Transcript Integration (v4.1.2)

> HERMES is not only a message router. It is a **Memory Gateway**. Every message that passes through HERMES becomes a permanent record in two layers: the transcript (a flat JSONL file) and the memory graph (staging toons that THOTH may promote to canonical).

### Transcript JSONL Update Protocol

Every message routed by HERMES ??? regardless of type ??? is appended as a single JSONL line to the `transcript_jsonl` artifact for the active channel thread. The `transcript_jsonl` value in a channel's header is a lookup slug (`{federation_node_id}/{channel_key}/{prd_cluster}`), not an OS file path. The resolved file lives at:

```
lupo-memory/transcripts/{federation_node_id}/{channel_key}/{prd_cluster}.jsonl
```

Each appended line is a JSON object with this canonical structure:

```json
{
  "ts": 20260416143316,
  "from_actor_id": 1,
  "to_actor_id": 15,
  "message_text": "[task] who: CLAUDE what: update PRD 50 ...",
  "message_type": "task",
  "routing_provenance": "hermes:task-router"
}
```

| Field | Type | Rule |
|---|---|---|
| `ts` | `BIGINT` UTC `YYYYMMDDHHIISS` | Same timestamp doctrine as all Lupopedia timestamps |
| `from_actor_id` | `INT` | Sending actor; resolved from session/auth, never from client input |
| `to_actor_id` | `INT` | Receiving actor; HERMES resolves from agent_key or message pattern |
| `message_text` | `string` | Full message body as delivered |
| `message_type` | `string` | One of: `task`, `alert`, `stdout`, `stderr`, `directed`, `system` |
| `routing_provenance` | `string` | HERMES routing rule that matched (e.g. `hermes:task-router`, `hermes:alert`, `hermes:monitor-relay`) |

**Constitutional constraints:**
- Transcript files are file-based artifacts, not DB tables (per PRD 16 ??2 scope and transcript_jsonl definition).
- Never write directly to the transcript file from outside HERMES. HERMES is the sole writer.
- Each line must be valid JSON. One object per line, no trailing comma, no array wrapper.

### Staging Memory Toon Update Protocol

After writing the JSONL transcript record, HERMES evaluates the message for pattern extraction. Patterns are written to staging memory toons (Tier 3 in the Trust Ladder, PRD 43).

**Extractable pattern types:**

| Pattern Type | Trigger | Example |
|---|---|---|
| `task_assignment` | `message_type: task` routed successfully | `[task] who: CURSOR what: fix header` |
| `decision` | `[decision]` prefix or THOTH acknowledgement | `[decision] approved: single-bundled PR` |
| `question` | `[question]` prefix or OQ reference | `[question] OQ-42: ...` |
| `alert` | `[alert]` message or `message_type: alert` | `THOTH [ALERT]: contradiction found` |
| `cross_channel_route` | Message sent to a non-primary channel | Send to Blog channel from Development |

**Staging toon path:**

```
lupo-memory/{channel_key}/staging/{YYYY}/{MM}/{prd_cluster}.toon
```

Example: `lupo-memory/development/staging/2026/04/channels-discussions.toon`

**Staging toon structure (minimal):**

```json
{
  "type": "staging_memory",
  "channel_key": "development",
  "prd_cluster": "channels-discussions",
  "trust_tier": "staging",
  "when_updated": 20260416143316,
  "patterns": [
    {
      "pattern_type": "task_assignment",
      "ts": 20260416143316,
      "from_actor_id": 1,
      "to_actor_id": 116,
      "summary": "Update PRD 50 section 5.3",
      "promotion_candidate": false
    }
  ]
}
```

### Promotion Flagging for THOTH

When a pattern has been observed `N` times within a session or across sessions (configurable threshold, default `N = 3`), HERMES sets `promotion_candidate: true` on the pattern record in the staging toon.

THOTH (actor_id 26) reads the staging toon and, upon finding `promotion_candidate: true`:

1. Verifies the pattern does not contradict any 1026 (canonical) node.
2. If valid: promotes via KAIROS / `MemoryPromotionService` to canonical tier.
   - Canonical path: `lupo-memory/{channel_key}/canonical/1026/{MM}/{prd_cluster}.toon`
   - Adds edge `promoted_to` from staging record to canonical node.
3. If contradiction: raises `THOTH [ALERT]` and blocks promotion until resolved.

**Trust Ladder integration (PRD 43):**
- Staging toons use calendar year (`2026`) in their path.
- Canonical toons use `calendar_year - 1000` (`1026`) in their path.
- Promotion is one-way. A promoted node cannot revert to staging; only a Captain's Amendment can modify it.
- HERMES never writes directly to canonical tier. HERMES writes to staging only.

---

## Cross-References

This PRD references and is referenced by:

- **[PRD 00](lupo-docs/prd/00_root_constitutional_system_requirements.md)** - Constitutional system requirements and limits
- **[PRD 17](lupo-docs/prd/17_decisions_format.md)** - Decision threads; **staged workflow** and template-first alignment with this PRD's guardrails
- **[PRD 16](lupo-docs/prd/16_lupopedia_headers.md)** - Header and metadata requirements
- **[PRD 17](lupo-docs/prd/17_thread_filename_patterns.md)** - Thread filename conventions
- **[PRD 99](lupo-docs/prd/99_numbering_and_limits.md)** - Numbering schemes and system limits
- **[PRD 43](lupo-docs/prd/43_parent_child_trust_ladder.md)** - Trust Ladder: canonical year offset, staging vs canonical tiers, KAIROS promotion
- **[PRD 82](lupo-docs/prd/82_hermes_message_routing_memory_gateway.md)** - HERMES: full specification as Memory Gateway (transcript, staging toon, promotion)
- **[HERMES_DOCTRINE.md](lupo-docs/doctrine/HERMES_DOCTRINE.md)** - HERMES routing rules (who receives what, task queue delivery)
- **[CONTEXT_AUTHORITY_MODEL.md](lupo-docs/doctrine/CONTEXT_AUTHORITY_MODEL.md)** - Context is channel/thread/artifact lineage, not actor identity; actors are interchangeable execution surfaces
- **[CHRONOLOGICAL_TRUST_LADDER.md](lupo-rules/root/CHRONOLOGICAL_TRUST_LADDER.md)** - Trust levels and validation
- **[install_new_lupopedia.sql](lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql)** - Database schema source of truth

## Summary

This PRD defines channels, threads, and dialog messages for Lupopedia:

1. **Routing and projection**: **Channel** = routing context; **`lupo_dialog_messages`** = shared storage; **UI** = **projection** (`from_actor_id` / `to_actor_id`; Crafty **saidfrom** / **saidto** semantics). **Presence** is separate from visibility.
2. **One-column UI**: Chronological feed of **the active authorized slice**, not a default global log for every participant.
3. **Release line**: **4.1.3** = REQUIRED non-AI live help baseline (orchestration chrome **not** a ship gate). **4.1.4???4.1.9** = continuing development including orchestration / AI / command-center on **fresh-install** lines. **4.2.0** = first public Lupopedia release (layered system); **no** supported Lupopedia-to-Lupopedia upgrade before **4.2.0** (Crafty **3.7.5 ??? Lupopedia** path).
4. **Thread colors and provenance**: Row-level cues within the projection.
5. **Agent integration**: Write-only builders; tasks and HERMES per PRD 82; **no** omniscient observer UI by default.
6. **THOTH [ALERT]**: Constitutional enforcement path ??? **not** conflated with "see all chat rows" in default UI.
7. **Implementation guardrails**: **Staged Development Workflow (Required)**, **Template-First Implementation Rule (Normative)**, **Language Array / Localization Requirement** ??? **MUST** / **MUST NOT** / **FORBIDDEN** as stated at document head; **4.1.3** **MUST NOT** require full orchestration chrome.

---

## Anchored Truth Doctrine: The Sieve and THOTH [ALERT] Protocol

Many Lupopedia markdown files, especially PRDs and Captain's Logs, end with a trailing block such as:

```
lupopedia.edges:
    ...
```

or similar YAML/JSON fragments. These trailing blocks serve as import staging footers for graph edge creation, metadata, or cross-references. They are not part of the main document body, but are machine-readable by the import pipeline and graph engine. This pattern allows the main content to remain clean and human-readable, while still supporting automated linking, provenance, and semantic graph construction.

**Key points:**
- The main markdown body is for human readers and canonical requirements.
- The trailing block (after the main content) is for machine-readable metadata, edges, or import instructions.
- The import pipeline and graph engine scan for these blocks to build the semantic network and cross-link artifacts.
- This pattern is recommended for all PRDs, Captain's Logs, and any file that participates in the semantic graph.

For more, see the Captain's Log example and the AGENTS.md guide on documentation architecture.

> **Hierarchy of Truth ??? The Sieve:**
> - **1026 Nodes (The Ancestors):** Finalized, merged, verified FACT. These are the benchmark for all truth in Lupopedia.
> - **2026 Nodes (The Descendants):** Temporary staging, active work, unverified "thoughts." Useful for drafting, but not authoritative.
> - **THE LAW:** If a 2026 node contradicts a 1026 node, the 2026 node is **WRONG** until a **Captain???s Amendment** is issued. No AI, agent, or human may override a canonical ancestor with a descendant unless the amendment is explicit and logged.

> **THOTH [ALERT] Protocol:**
> - If a contradiction is found between a 2026 and a 1026 node:
>   1. **Fix the Staging Node** if it is hallucinated or logically wrong.
>   2. **Amend the 1026 Node** only with explicit Captain (WOLFIE) approval.
> - **No merge or implementation may proceed until the contradiction is resolved.**
> - **THOTH [ALERT]** is a hard stop for all agents and implementers.

**Recency Bias Trap:**
> Modern AI models and agents must not prefer newer (2026) staging nodes over older (1026) canonical facts. The system is designed so that recency does not override truth. Only a Captain???s Amendment can change a canonical fact.

The system is designed to be simple, transparent, and maintainable while supporting complex multi-agent workflows.

---

**This PRD is now fully canonical. PRD 81 is deprecated.**
