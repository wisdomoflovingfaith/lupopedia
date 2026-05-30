---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "lupo-docs/versions/4.1.2/status/auggie_sticky_note_channel_review.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.1.2/status/auggie_sticky_note_channel_review.md"
  status: "active"
  when_updated: "20260415224500"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/auggie-sticky-note-channel-review.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/auggie_sticky_note_channel_review"
  artifact_type: status
  artifact_kind: gap_analysis
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: "2"
  content_slug: "auggie-sticky-note-channel-review"
  default_collection_id: null
  lupopedia.schema: status
  title: "Channel Interface Gap Review — Sticky Note Interface vs PRD 02 (v4.1.2)"
  summary: "Design-to-implementation gap analysis. Identifies Notepad Gap, Sticky Note Gap, and Routing Gap between real operator workflow and PRD 02 doctrine. Produces prioritized implementation list (P0–P2), exact PRD 02 additions, and open questions OQ-47 through OQ-55 for the 4.1.2 channel interface build."
---

# Channel Interface Gap Review — Sticky Note Interface vs PRD 02
**Version:** 4.1.2 | **Reviewer:** AUGGIE | **Date:** 20260415

---

## I. EXECUTIVE SUMMARY

**PRD 02 survival status: PARTIAL**

PRD 02's core doctrine is architecturally correct:
- Chat = one-way monitoring mirror ✅
- Builder agents = write-only ✅
- HERMES routes tasks, not chat messages ✅
- Task queue is the agent instruction channel ✅

PRD 02 is insufficient because it models the **OVERSIGHT layer** only. The real workflow requires a second layer — **ORCHESTRATION** — that PRD 02 does not define. The human is not merely watching agents; the human IS the routing layer. Every task assignment flows through manual human judgment, manual text composition, and manual copy-paste. PRD 02 has no spec for any of that.

**Status Dashboard:**

| Surface | Status | Blocking? |
|---|---|---|
| Digital Sticky Notes | ❌ NOT STARTED — no schema, no API, no UI | No |
| Operator Scratchpad (Notepad replacement) | ❌ NOT STARTED — no schema, no API, no UI | **YES — P0** |
| Assignment / Routing System | ⚠️ PARTIAL — `lupo_dialog_pending_tasks` exists; HERMES unbuilt; no pipeline model | **YES — P0** |
| "What I gave to whom" history | ❌ NOT STARTED — no handoff record, no routing event model | **YES — P0** |
| Agent State Model (awake/sleeping/throttled/failed) | ❌ NOT STARTED — no vocabulary, no schema, no polling | **YES — P0** |

---

## II. THE NOTEPAD GAP

**Root cause:** The chat input box is a **send-immediately** surface. There is no drafting state.

**What the operator actually needs during prompt composition:**
1. Hold partial text for minutes or hours without sending it.
2. Edit iteratively without token cost or agent confusion.
3. Compose in parallel: "prompt for Auggie" and "prompt for Gemini" exist simultaneously.
4. Capture external agent output as a buffer before deciding where to route it.
5. Promote a draft to a task or chat message when ready.

**What PRD 02 provides:** A textarea that sends on submit. Nothing else.

**Missing surfaces — exact gaps:**

| Gap | What Is Missing | Implementation Surface |
|---|---|---|
| **Operator Scratchpad** | Persistent private text buffer for actor_id=1. Scoped to operator. Not visible to agents. Not a channel or thread. Saves state across sessions. | DB table `lupo_operator_scratchpad` (rows: scratchpad_id, actor_id, title, body, created_ymdhis, updated_ymdhis, is_deleted) + UI panel |
| **Safe-Edit Buffer** | Scratchpad must support N concurrent named drafts (e.g., "Auggie prompt v2", "Gemini refinement"). Single-draft model is insufficient. | `title` field in scratchpad table + list view in UI |
| **Promote-to-Task** | Button that takes scratchpad content and creates a `lupo_dialog_pending_tasks` row without copy-paste. | API endpoint `POST /api/scratchpad/promote-to-task` |
| **Promote-to-Message** | Button that takes scratchpad content and posts it to active channel/thread. | API call to `POST /api/chat/send` with body from scratchpad |

**Scratchpad is NOT a channel.** It does not appear in the message feed. Agents cannot see it. It is private state for the operator.

**Decision required:** OQ-47 (storage model), OQ-54 (multi-draft support).

---

## III. THE STICKY NOTE GAP

**Root cause:** The system has no model for the operator's mental state map — who has what, what is in-flight, what is blocked.

**State currently tracked by yellow sticky notes (not by the system):**

| State | What Operator Tracks | System Equivalent | Gap |
|---|---|---|---|
| Agent → Task mapping | "Auggie: validator enhancement" | `lupo_dialog_pending_tasks.task_description` | Exists in DB but has no UI surface showing per-agent current task |
| Prompt handoff record | "I sent X to Gemini, got Y back, sent Y to Auggie" | Nothing | No routing event model |
| Expected output | "Auggie should produce a PRD update" | Nothing | No `output_spec` field on tasks |
| Agent status | "Cursor passed out (throttled)" | Nothing | No `lupo_agent_status` table, no vocabulary |
| Channel status | "Channel A blocked on schema" | Nothing | Channels have no active state |
| External agent context | "Grok has the PRD merge context" | Nothing | External agents have no actor IDs |

**Agent Status Vocabulary — does not exist in PRD 02 or any schema:**

Required vocabulary (to be formalized in PRD 02 §new):
- `ACTIVE` — agent received a task and is currently executing
- `IDLE` — agent has no current task, awaiting assignment
- `SLEEPING` — context window exhausted or session expired (not recoverable without restart)
- `THROTTLED` — API rate limit hit; will recover automatically
- `FAILED` — task execution error; requires human intervention
- `UNKNOWN` — no heartbeat received within polling window

**Required schema — missing:**
```sql
CREATE TABLE lupo_agent_status (
    actor_id BIGINT NOT NULL PRIMARY KEY,
    status ENUM('ACTIVE','IDLE','SLEEPING','THROTTLED','FAILED','UNKNOWN') DEFAULT 'UNKNOWN',
    current_task_id BIGINT NULL,           -- FK lupo_dialog_pending_tasks
    last_heartbeat_ymdhis BIGINT NULL,
    status_note TEXT NULL,                 -- human-readable reason
    updated_ymdhis BIGINT NOT NULL,
    FOREIGN KEY (current_task_id) REFERENCES lupo_dialog_pending_tasks(task_id)
);
```

**Digital Sticky Notes — required schema (first-class entities):**
```sql
CREATE TABLE lupo_sticky_notes (
    note_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    actor_id BIGINT NOT NULL,              -- owner (always actor_id=1 for now)
    channel_key VARCHAR(64) NULL,          -- NULL = global; set = channel-scoped
    body TEXT NOT NULL,
    color VARCHAR(7) DEFAULT '#FEFDCD',    -- default yellow
    is_pinned TINYINT DEFAULT 0,
    created_ymdhis BIGINT NOT NULL,
    updated_ymdhis BIGINT NOT NULL,
    is_deleted TINYINT DEFAULT 0
);
```

**Decision required:** OQ-52 (sticky notes as first-class DB entities), OQ-51 (agent status polling model), OQ-48 (external agent representation).

---

## IV. THE ROUTING GAP

**Root cause:** The system has no data model for "a refined output from agent A becoming a task for agent B."

**The real routing chain (9 steps from blog — none are in the system):**

```
[Gemini web: design]
      ↓ operator copies output
[Notepad: buffer + compose]
      ↓ operator composes prompt
[ChatGPT: refine prompt]
      ↓ operator copies refined prompt
[Auggie terminal: implement]
      ↓ agent writes file
[lupo-content: output file with header]
      ↓ file becomes next conversation seed
[next agent / channel: picks up]
```

Only the last two steps (agent writes file, file tracking) are partially modeled. Steps 1–5 are completely invisible to the system.

**Three missing models:**

### A. Assignment Model

**Defined:** Only `actor_id=1` (WOLFIE) assigns tasks to agents via `lupo_dialog_pending_tasks`. This is correct.

**Undefined:**
- External web agents (ChatGPT, Grok, Gemini web, DeepSeek web) have no `actor_id`. They cannot be the "source" of a routing chain in any DB record.
- No distinction between "direct assignment" (human types task) and "pipeline assignment" (output of previous agent → input to next agent).
- No model for "I'm routing the output of Task #42 into Task #43."

### B. Routing Object — completely absent

Required: a typed routing event that captures:
```sql
CREATE TABLE lupo_routing_events (
    routing_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    source_type ENUM('scratchpad','file','task_output','external') NOT NULL,
    source_scratchpad_id BIGINT NULL,          -- FK lupo_operator_scratchpad
    source_file_path VARCHAR(512) NULL,        -- file_path_from_root
    source_task_id BIGINT NULL,               -- FK lupo_dialog_pending_tasks (prior task)
    source_external_actor_label VARCHAR(64) NULL, -- e.g., 'chatgpt-web', 'grok-web'
    destination_actor_id BIGINT NOT NULL,      -- FK lupo_actors
    destination_task_id BIGINT NULL,          -- FK lupo_dialog_pending_tasks (created task)
    routed_by_actor_id BIGINT NOT NULL,       -- always actor_id=1
    routed_ymdhis BIGINT NOT NULL,
    note TEXT NULL
);
```

### C. External Agent Registry — completely absent

The real workflow involves 5+ external agents (ChatGPT, Grok, Gemini web, DeepSeek/LILITH web, Copilot) that are part of every routing chain but invisible to the system.

Required: `actor_type='external_web'` records in `lupo_actors` (or a separate `lupo_external_agents` table). These records have no task queue and no write access, but they CAN be referenced as `source_external_actor_label` in routing events.

**Decision required:** OQ-49, OQ-50, OQ-53.

---

## V. PRIORITIZED IMPLEMENTATION LIST

### P0 — Required for 4.1.2 to function

| # | Feature | Why Required | Surface | Dependencies | Blocking |
|---|---|---|---|---|---|
| P0-1 | **Operator Scratchpad** (DB + UI panel) | Operator cannot compose prompts without Notepad; system cannot replace analog workflow without this | UI panel + `lupo_operator_scratchpad` table + `POST /api/scratchpad/save`, `/api/scratchpad/list`, `/api/scratchpad/promote-to-task` | None | YES — blocks P0-3, P0-4 |
| P0-2 | **Agent Status Vocabulary + `lupo_agent_status` table** | Dashboard is meaningless without knowing which agents are active/sleeping/failed | DB schema + status update API `POST /api/agent/status` + read endpoint `GET /api/agent/status` | `lupo_actors` must be complete | YES — blocks agent dashboard |
| P0-3 | **Routing Event Model (`lupo_routing_events`)** | Without this, "what I gave to whom" is never recorded; the core orchestration gap is not closed | DB schema + `POST /api/routing/create` | P0-1 (scratchpad_id), task table | YES |
| P0-4 | **External Agent Registry** | ChatGPT, Grok, Gemini web are real actors in the routing chain; they must be representable in routing events | `lupo_actors` rows with `actor_type='external_web'` OR new `lupo_external_agents` table | OQ-48 resolution | YES — blocks P0-3 source attribution |

### P1 — Major Workflow Improvements

| # | Feature | Why Required | Surface | Dependencies | Blocking |
|---|---|---|---|---|---|
| P1-1 | **Digital Sticky Notes** (`lupo_sticky_notes` + UI) | Replaces physical notes; channel-scoped, persistent, always-visible in dashboard sidebar | DB schema + `POST /api/notes/save`, `GET /api/notes/list` + sidebar UI | None | No |
| P1-2 | **Channel Status Model** | Channels need active state (blocked/active/waiting) to show on operator dashboard | Add `status` field to `lupo_channels` + update API | None | No |
| P1-3 | **"What I Gave to Whom" History Panel** | Operator needs UI view of routing events sorted by time | UI tab or panel reading `lupo_routing_events` | P0-3 | No |
| P1-4 | **Agent Status Dashboard Panel** | Visual display of all agents and their current status | UI panel reading `lupo_agent_status` | P0-2 | No |
| P1-5 | **HERMES `[task]` syntax implementation** | Defined in PRD 02 (OQ-12 open since 4.1.0) — still unbuilt; required for any command-driven routing | Chat message parser + HERMES router service | `lupo_dialog_pending_tasks` | No |
| P1-6 | **Scratchpad → Task promote button** | Closes the copy-paste gap between drafting and assignment | UI button + `POST /api/scratchpad/promote-to-task` | P0-1 | No |

### P2 — Automation / Optimization

| # | Feature | Why Required | Surface | Dependencies | Blocking |
|---|---|---|---|---|---|
| P2-1 | **Agent heartbeat / status auto-detection** | Currently status must be set manually; polling would detect SLEEPING/THROTTLED automatically | Agent wrapper posts heartbeat; cron detects silence → sets UNKNOWN | P0-2 | No |
| P2-2 | **Pipeline replay** | Re-send a previous routing chain without copy-paste | UI action on routing event + promotes to new task | P0-3 | No |
| P2-3 | **Channel block auto-detection** | When all tasks in a channel are blocked, flag channel as blocked automatically | Cron job reading task statuses per channel | P0-2, P1-2 | No |

---

## VI. PRD 02 UPDATES REQUIRED

The following are EXACT additions to `lupo-docs/prd/02_channels_discussions.md`. Each is a new section or a targeted amendment to an existing section.

---

### ADD: New Section — "Human as Orchestration Layer" (insert after "The One-Way Mirror")

```markdown
## Human as Orchestration Layer

The human operator (CAPTAIN_WOLFIE, actor_id=1) is not merely an observer of the chat stream.
The human IS the routing layer. All agent work flows through the human. No agent receives a task
without human judgment and human dispatch.

This means the Lupopedia dashboard must support TWO distinct roles:

| Role | Layer | What It Requires |
|---|---|---|
| Oversight | Monitoring layer | Chat feed (defined above) |
| Orchestration | Routing layer | Scratchpad, agent status, routing history |

PRD 02 previously specified only the Oversight layer. The Orchestration layer is equally required
and must be designed with equal rigor.

**Key constraint:** The orchestration layer is HUMAN-ONLY. Agents do not participate in routing
decisions. Agents receive tasks. They do not route each other.
```

---

### ADD: New Section — "Operator Scratchpad" (insert after "Human as Orchestration Layer")

```markdown
## Operator Scratchpad

The Operator Scratchpad is a persistent, private text buffer for actor_id=1. It is NOT a channel,
NOT a thread, and NOT visible to any agent.

**Purpose:** Enables the operator to compose prompts, hold intermediate text, iterate on wording,
and stage content before routing it to an agent task.

**Constitutional rule:** The scratchpad is the only in-system surface where the operator composes
text that has not yet been dispatched. It is the digital replacement for Notepad.exe.

**DB Schema:**
```sql
CREATE TABLE lupo_operator_scratchpad (
    scratchpad_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    actor_id BIGINT NOT NULL,
    title VARCHAR(255) NOT NULL DEFAULT 'Untitled',
    body LONGTEXT NOT NULL DEFAULT '',
    created_ymdhis BIGINT NOT NULL,
    updated_ymdhis BIGINT NOT NULL,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    INDEX idx_actor_updated (actor_id, updated_ymdhis DESC)
);
```

**API Endpoints:**
- `GET /api/scratchpad/list?actor_id=1` — list all non-deleted drafts
- `POST /api/scratchpad/save` — create or update draft
- `DELETE /api/scratchpad/{id}` — soft delete
- `POST /api/scratchpad/promote-to-task` — create `lupo_dialog_pending_tasks` row from body

**UI Requirements:**
- Scratchpad panel is accessible from the main dashboard without navigating away from chat.
- Multiple drafts are shown as a list. Each has a title and last-updated timestamp.
- "Promote to Task" button triggers `POST /api/scratchpad/promote-to-task`.
- "Send to Chat" button calls `POST /api/chat/send` with scratchpad body.
```

---

### ADD: New Section — "Agent Status Model" (insert after "Agent Integration Table & Wrapper")

```markdown
## Agent Status Model

Builder agents have a lifecycle that is invisible to the system without explicit tracking.
The operator currently uses sticky notes to track who is active, sleeping, or failed.

**Status Vocabulary (canonical):**

| Status | Meaning | How Set |
|---|---|---|
| `ACTIVE` | Agent is executing a task right now | Set when task status → `in_progress` |
| `IDLE` | Agent has no current task | Set when task status → `completed` or `cancelled` |
| `SLEEPING` | Context window exhausted / session expired | Set manually or by heartbeat timeout |
| `THROTTLED` | API rate limit hit; will recover | Set by agent wrapper on rate-limit error |
| `FAILED` | Task execution error; human intervention required | Set when task status → `failed` |
| `UNKNOWN` | No heartbeat received within polling window | Default; set by timeout cron |

**DB Schema:**
```sql
CREATE TABLE lupo_agent_status (
    actor_id BIGINT NOT NULL PRIMARY KEY,
    status ENUM('ACTIVE','IDLE','SLEEPING','THROTTLED','FAILED','UNKNOWN') NOT NULL DEFAULT 'UNKNOWN',
    current_task_id BIGINT NULL,
    last_heartbeat_ymdhis BIGINT NULL,
    status_note TEXT NULL,
    updated_ymdhis BIGINT NOT NULL
);
```

**Update Rules:**
- Status is updated by: (a) task state transitions, (b) agent wrapper heartbeat, (c) manual operator override via UI.
- Agents do NOT self-report SLEEPING — the heartbeat timeout cron detects silence and sets UNKNOWN.
- WOLFIE can manually override any agent status via `POST /api/agent/status/set`.

**API Endpoints:**
- `GET /api/agent/status` — list all agent statuses
- `POST /api/agent/status/set` — manual override (actor_id=1 only)
- `POST /api/agent/heartbeat` — agent wrapper posts every 60 seconds
```

---

### ADD: New Section — "External Agent Registry" (insert after "Agent Status Model")

```markdown
## External Agent Registry

The real workflow involves external web-based agents (ChatGPT, Grok, Gemini web, DeepSeek/LILITH
web, Copilot, etc.) that participate in prompt refinement and routing chains but have no system
presence. These agents must be representable in routing events.

**Representation:** External agents are registered as `actor_type='external_web'` rows in
`lupo_actors` (or a separate `lupo_external_agents` table — see OQ-48).

**Constraints:**
- External agents have no task queue. They cannot receive `lupo_dialog_pending_tasks` rows.
- External agents have no write access to any Lupopedia endpoint.
- External agents can only appear as `source_external_actor_label` in `lupo_routing_events`.
- External agents DO have a human-readable label and optional notes field for context.

**Known external agents (seed records):**
| Label | Description |
|---|---|
| `chatgpt-web` | ChatGPT (OpenAI) web interface |
| `grok-web` | Grok (xAI) web interface |
| `gemini-web` | Gemini (Google) web interface |
| `deepseek-web` | DeepSeek web interface (LILITH's external surface) |
| `copilot-web` | Microsoft Copilot web interface |
```

---

### ADD: New Section — "Routing Model" (insert after "External Agent Registry")

```markdown
## Routing Model

A routing event is a typed record that captures: what content, from what source, was dispatched to
which agent as which task.

**This is NOT HERMES.** HERMES routes chat messages to task queues. The routing model records
the provenance of task content — where did the instructions come from, and what chain of agents
processed them before reaching this task.

**DB Schema:**
```sql
CREATE TABLE lupo_routing_events (
    routing_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    source_type ENUM('scratchpad','file','task_output','external','manual') NOT NULL,
    source_scratchpad_id BIGINT NULL,
    source_file_path VARCHAR(512) NULL,
    source_task_id BIGINT NULL,
    source_external_actor_label VARCHAR(64) NULL,
    destination_actor_id BIGINT NOT NULL,
    destination_task_id BIGINT NULL,
    routed_by_actor_id BIGINT NOT NULL DEFAULT 1,
    routed_ymdhis BIGINT NOT NULL,
    note TEXT NULL,
    INDEX idx_routed_time (routed_ymdhis DESC),
    INDEX idx_destination (destination_actor_id, routed_ymdhis DESC)
);
```

**Routing event is created when:**
- Operator clicks "Promote to Task" from scratchpad
- Operator uses `[task]` command syntax in chat (HERMES creates routing_event alongside task)
- Operator manually creates task via API (caller supplies source metadata)

**Routing event is NOT created for:**
- Agent-internal work (agent updating its own files)
- Monitoring messages (stdout/stderr posts to chat)
```

---

### AMEND: "Digital Sticky Notes" — add to existing content

```markdown
## Digital Sticky Notes

Sticky notes are first-class DB entities. They are NOT channel messages, NOT scratchpad entries.
They are persistent, channel-scoped operator annotations.

**DB Schema:**
```sql
CREATE TABLE lupo_sticky_notes (
    note_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    actor_id BIGINT NOT NULL,
    channel_key VARCHAR(64) NULL,
    body TEXT NOT NULL,
    color VARCHAR(7) NOT NULL DEFAULT '#FEFDCD',
    is_pinned TINYINT NOT NULL DEFAULT 0,
    created_ymdhis BIGINT NOT NULL,
    updated_ymdhis BIGINT NOT NULL,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    INDEX idx_actor_channel (actor_id, channel_key, is_deleted)
);
```

**UI:** Sticky notes appear in a sidebar panel on the dashboard. They are always visible regardless
of which channel/thread is active. Pinned notes appear at the top. Each note shows channel scope.
```

---

### AMEND: Clarification to "The One-Way Mirror" table

Add row to the existing participant table:

```
| Human Operator | All | Yes | Yes | Direct typing + Scratchpad |
```

Change footnote to read:

> The chat is for humans to WATCH agents work, not for agents to talk to each other. The SCRATCHPAD
> is for humans to COMPOSE before dispatching. These are distinct surfaces. Do not conflate them.

---

## VII. OPEN QUESTIONS

All ambiguity from this review is logged in `lupo-docs/versions/4.1.2/status/open_questions.md` as OQ-47 through OQ-55.

See that file for full format entries. Questions by number:

- **OQ-47**: Where does the operator scratchpad live? (DB table vs. flat file vs. hybrid)
- **OQ-48**: How are external agents (ChatGPT, Grok, etc.) represented? (`lupo_actors` with `actor_type` vs. separate `lupo_external_agents` table)
- **OQ-49**: What defines "active context" for a channel vs. "routing target"?
- **OQ-50**: Should routing be explicit objects (`lupo_routing_events`) or inferred from message chain?
- **OQ-51**: How is agent status determined — heartbeat polling vs. self-reporting vs. operator-manual?
- **OQ-52**: Should sticky notes be first-class DB entities (recommended above) or channel messages with a `note` message_type?
- **OQ-53**: What is the data model for a prompt pipeline / handoff record? (Is `lupo_routing_events` sufficient, or does it need a multi-hop chain model?)
- **OQ-54**: Should the operator scratchpad support multiple concurrent named drafts, or is one active draft sufficient?
- **OQ-55**: What triggers a "channel blocked" state? Who can unblock it? Is this human-set only or auto-detected from task states?
