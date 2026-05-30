---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "lupo-docs/versions/4.1.2/status/channel_interface_implementation_plan.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.1.2/status/channel_interface_implementation_plan.md"
  status: "active"
  when_updated: "20260415212000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/channel-interface-implementation-plan.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/channel_interface_implementation_plan"
  artifact_type: status
  artifact_kind: implementation_plan
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: "2"
  content_slug: "channel-interface-implementation-plan"
  default_collection_id: null
  lupopedia.schema: status
  title: "Channel Interface Implementation Plan — 4.1.2"
  summary: "Staged P0/P1/P2 implementation plan for the Lupopedia channel orchestration dashboard. Covers doctrine updates, Q&A resolution, mockup validation, data model decisions, and production UI implementation. Derived from blog, PRD 02 (updated), gap analysis, and Q&A artifact."
---

# Channel Interface Implementation Plan — 4.1.2

**Purpose:** Ordered, staged plan for building the Lupopedia orchestration dashboard.
**NOT a code implementation document.** This plan orders the work. Code follows after each stage is validated.

**Source artifacts:**
- Blog: `lupo-content/federation_node/0/captains_log/20260415_the_stickynote_interface.md`
- PRD 02 (updated): `lupo-docs/prd/02_channels_discussions.md`
- Gap analysis: `lupo-docs/versions/4.1.2/status/auggie_sticky_note_channel_review.md`
- Q&A: `lupo-docs/versions/4.1.2/status/questions_answers.md`
- Open questions: `lupo-docs/versions/4.1.2/status/open_questions.md`

---

## Stage 0: Doctrine + Planning (COMPLETE as of 20260416)

**What this covers:** All doctrine, spec, and planning work. Must be done before any production code.

| Item | Artifact | Status |
|---|---|---|
| Gap analysis written | `auggie_sticky_note_channel_review.md` | ✅ DONE |
| PRD 02 updated with orchestration doctrine | `02_channels_discussions.md` | ✅ DONE |
| Questions classified (answered vs open) | `questions_answers.md` | ✅ DONE |
| Open questions logged (OQ-47 through OQ-57) | `open_questions.md` | ✅ DONE |
| Mockup created | `channels/mockup.htm` | ✅ DONE |
| Implementation plan written | this file | ✅ DONE |

**Blocking decisions still open:** OQ-47, OQ-48, OQ-50, OQ-51, OQ-52, OQ-56, OQ-57
These must be resolved before P0 production implementation. See Stage 1.

---

## Stage 1: Open Question Resolution

**What this covers:** Resolving the blocking open questions. No code written yet.

**Method:** Captain WOLFIE reviews each OQ and issues a decision. Decisions are recorded as STATUS UPDATE entries in `open_questions.md`.

**Blocking OQs (must resolve before P0 code):**

| OQ | Question | Unblocks |
|---|---|---|
| OQ-47 | Where does the operator scratchpad live? | Scratchpad DB schema |
| OQ-48 | How are external agents represented? | External agent table/column |
| OQ-50 | Routing as explicit objects or inferred? | `lupo_routing_events` schema |
| OQ-51 | Agent status: polling vs. self-report vs. manual? | `lupo_agent_status` schema |
| OQ-52 | Sticky notes as first-class DB entities? | `lupo_sticky_notes` schema |
| OQ-56 | Are context personas registered `lupo_actors` rows? | Context tab actor_id model |
| OQ-57 | What actor_id range for context personas? | Persona ID assignment |

**Non-blocking OQs (can proceed without these):**
OQ-49, OQ-53, OQ-54, OQ-55 — resolve in Stage 2 or later.

---

## Stage 2: Data Model Decisions + Schema

**What this covers:** Writing the DB schemas for new tables (after OQ resolutions from Stage 1).
**Output:** SQL migration files in `lupo-database/lupopedia/mysql/`.

| Schema Item | Table | Depends On |
|---|---|---|
| Operator scratchpad | `lupo_operator_scratchpad` | OQ-47 |
| Agent status | `lupo_agent_status` | OQ-51 |
| Routing events | `lupo_routing_events` | OQ-50 |
| External agent registry | `lupo_actors` extension or new table | OQ-48 |
| Sticky notes | `lupo_sticky_notes` | OQ-52 |
| Context tab sessions | `$_SESSION` or `lupo_operator_contexts` | OQ-56 |

**Validation:** Each schema must be reviewed against `install_new_lupopedia.sql` (source of truth) and PRD 99 table count limits.

---

## Stage 3: Mockup Validation

**What this covers:** Captain reviews `channels/mockup.htm` against the blog screenshot and signs off on layout before any production code is written.

**Validation checklist:**
- [ ] Left panel present on LEFT side (not right)
- [ ] Actors section with status dots visible
- [ ] Recent files section channel+context scoped
- [ ] Recent tasks section visible
- [ ] `[send to other channel]` button on EVERY message row (always visible, not hover)
- [ ] Target Actor Tabs (CAPTAIN | DEVIN | ERIC | LEXA) at bottom above input
- [ ] Active Target Bar shows "SENDING TO: {ACTOR_NAME}"
- [ ] Input area background color syncs to selected target actor's thread color
- [ ] Enter toggle button visible with mode indicator
- [ ] Task messages render on WHITE background (visually distinct from agent output)
- [ ] Task status messages render on sender agent's thread background color
- [ ] SEND MESSAGE and SEND TASK buttons both present and distinct
- [ ] Routing messages show backlinks

**Output:** Captain signs off ("mockup approved — proceed to P0") or requests changes.

---

## P0 — Required for 4.1.2 to Function

**Prerequisite:** Stage 1 (OQ resolutions) and Stage 3 (mockup approval) complete.

### P0-A: DB Schema Migration
Write and run SQL for all new tables (scratchpad, agent_status, routing_events, sticky_notes).

**Files:** `lupo-database/lupopedia/mysql/migrations/4_1_2_orchestration_tables.sql`
**Validation:** Run against dev DB; confirm table count within PRD 99 limits.

### P0-B: API Endpoints (new)

| Endpoint | Method | Purpose |
|---|---|---|
| `/api/routing/send-to-channel` | POST | Cross-channel message routing (requires `destination_actor_id` and `routing_explanation`) |
| `/api/context/switch` | POST | Switch active context tab (session update) |
| `/api/agent/status` | GET | Get all agent statuses |
| `/api/agent/status/set` | POST | Manual agent status override |
| `/api/scratchpad/list` | GET | List operator's scratchpad drafts |
| `/api/scratchpad/save` | POST | Save/update scratchpad draft |
| `/api/scratchpad/promote-to-task` | POST | Promote draft to pending task |
| `/api/ui/enter-mode` | POST | Toggle Enter key mode (session update) |

**Files:** `lupo-api/routing/`, `lupo-api/context/`, `lupo-api/agent/`, `lupo-api/scratchpad/`

### P0-C: channels/index.php Extensions

Extend (NOT rewrite) `channels/index.php`:

| Feature | What Changes | Preserves |
|---|---|---|
| Grid layout | 3-column: left panel + feed + (no right sidebar) | All existing PHP logic |
| Left panel HTML | Add `<div class="lupo-left-panel">` with actors/files/tasks | All existing CSS |
| Target Actor Tabs | Add `<div class="lupo-active-target-bar">` above input | CSRF, AJAX poll |
| Active Target Bar | Replace "Selected Context Bar" — display "SENDING TO: {NAME}" | Enter key JS |
| Dynamic CSS `--input-bg` | JS sets `--input-bg` CSS variable on tab switch to actor thread color | All existing colors |
| SEND MESSAGE + SEND TASK | Two buttons replace single send button | Existing `sendMessage()` fn |
| Enter toggle | Add toggle button + JS `enterMode` var | Existing enter handler |
| Per-message routing | Add `[send to actor]` button; triggers **Dual-Selector Modal** (Channel + Actor) | Message rendering loop |
| Modal Logic | JS dynamic lookup of actors via `DialogMvpService::getChannelMembers()` on channel selection | Existing modal framework |
| Task rendering | White bg for `task` type; actor thread bg for `task_status`; extend `$class` logic | THOTH color logic |

**Rule:** No production code until Stage 3 (mockup approval) is complete.

### P0-D: Left Panel API

Wire left panel to live data:
- Actors list: `GET /api/agent/status` (new) + `DialogMvpService::getChannelMembers()` (existing)
- Recent files: `GET /api/files/recent?channel_id=X&actor_id=Y` (extend existing `/api/files/recent`)
- Recent tasks: `GET /api/tasks/list?assigned_to=Y&channel_id=X` (extend existing tasks API)

**Polling:** Left panel refreshes every 10 seconds (same interval as existing task fetch in `channels/index.php`).

---

## P1 — Major Workflow Improvements

**Prerequisite:** P0 complete and stable.

| Item | Description | Depends On |
|---|---|---|
| P1-1 | Digital Sticky Notes panel in left panel | `lupo_sticky_notes` schema (OQ-52) |
| P1-2 | Channel Status Model (blocked/active/waiting) | `lupo_channels` status field |
| P1-3 | Routing History panel ("What I gave to whom") | `lupo_routing_events` (P0-A) |
| P1-4 | HERMES `[task]` syntax implementation | OQ-12 (rolled over from 4.1.0) |
| P1-5 | External agent actors in actors list | OQ-48 resolution |
| P1-6 | Operator Scratchpad panel | OQ-47 + P0-A + P0-B |
| P1-7 | Scratchpad → Task promote button | P1-6 |
| P1-8 | Atomic Logging Buffer | **In Progress** |

---

## P2 — Automation / Optimization

**Prerequisite:** P1 complete.

| Item | Description |
|---|---|
| P2-1 | Agent heartbeat auto-detection (SLEEPING/THROTTLED detection) |
| P2-2 | Pipeline replay (re-route a previous routing chain) |
| P2-3 | Channel block auto-detection from task states |
| P2-4 | Context tab config per-operator (`lupo_operator_contexts` table) |
| P2-5 | DOM threshold increase from 500 → tunable config value |

---

## Decision Log

| Date | Decision | Made By | Impact |
|---|---|---|---|
| 20260416 | Enter = Send is default; DRAFT mode toggle added | PRD 02 §Enter Key Toggle | P0-C |
| 20260416 | Left panel on LEFT side (not right) | Blog + PRD 02 confirmed | P0-C grid layout |
| 20260416 | Target Actor Tab switch does NOT reload feed | PRD 02 §Active Target Bar | P0-C JS |
| 20260416 | Cross-channel send is a DB routing event, NOT copy-paste | PRD 02 §Per-Message Cross-Channel Send | P0-A + P0-B |
| 20260416 | Agent status dot colors formalized (6-state vocabulary) | PRD 02 §Left Panel | P0-D |
| 20260416 | Tabs renamed: "Context Tabs" → "Target Actor Tabs"; determine `to_actor_id` | PRD 02 §Target Actor Tabs | P0-C |
| 20260416 | Active Target Bar replaces Selected Context Bar; shows "SENDING TO: {NAME}" | PRD 02 §Active Target Bar | P0-C |
| 20260416 | Input area background syncs to target actor thread color via `--input-bg` CSS var | PRD 02 §Visual Target Feedback Rule | P0-C |
| 20260416 | Dual-button: SEND MESSAGE (stdout) vs SEND TASK (creates task record) | PRD 02 §Dual-Button Logic | P0-B + P0-C |
| 20260416 | task message_type → white bg (#ffffff); task_status → sender actor thread color | PRD 02 §Task Rendering in Chat | P0-C |
| 20260416 | OQ-56 resolved: personas are first-class actors in lupo_actors (actor_type=human_persona) | Captain WOLFIE | P0-A |
| 20260416 | OQ-57 resolved: human_persona actor_id range is 10,000+ (CAPTAIN=10001, DEVIN=10002, ERIC=10003, LEXA=10004) | Captain WOLFIE | P0-A |
| 20260415 | OQ-58 resolved: the Atomic Merge script is the canonical solution for overlapping agent timestamps | Lead Architect | P1-8 |
