---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "docs/versions/4.1.2/status/questions_answers.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.1.2/status/questions_answers.md"
  status: "active"
  when_updated: "20260415212000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/version-4-1-2-questions-answers.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/4_1_2_questions_answers"
  artifact_type: status
  artifact_kind: questions_answers
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: "2"
  content_slug: "version-4-1-2-questions-answers"
  default_collection_id: null
  lupopedia.schema: status
  title: "Lupopedia 4.1.2 — Channel Interface Questions & Answers"
  summary: "Classifies channel interface questions as ANSWERED (derivable from blog/doctrine/code) or OPEN (unresolved, logged in open_questions.md). Covers: multi-channel routing, Target Actor Tabs, active recipient switching, enter key behavior, screenshot intent, recent files scope, left panel placement, task rendering, OQ-56/57 resolved (personas are first-class actors at actor_id 10,000+)."
---

# Channel Interface Questions & Answers — 4.1.2

**Source documents:**
- `content/federation_node/0/captains_log/20260415_the_stickynote_interface.md` (updated blog)
- `docs/prd/02_channels_discussions.md` (now updated)
- `channels/index.php` (current implementation)
- `docs/versions/4.1.2/status/auggie_sticky_note_channel_review.md` (gap analysis)

**Format:**
- ANSWERED entries cite their source.
- OPEN entries reference their OQ number in `open_questions.md`.

---

## PART 1: ANSWERED FROM CURRENT DOCTRINE / BLOG / CODE

---

### Q: Is the UI single-channel or multi-channel?

**ANSWER: Multi-channel.**

The operator works across multiple channels simultaneously (blog writing, documentation, validators, database design, PRD on atoms). The UI must support channel switching without losing work context.

**Source:** Blog §"The Departmentalized Brain (Updated)" — "I work in channels and contexts. Parallel. Simultaneous." `channels/index.php` already has a channel dropdown that redirects. PRD 02 (updated) §"Multi-Channel Routing."

---

### Q: Can the same message be routed to more than one channel?

**ANSWER: Yes. This is the "Send to Other Channel" feature.**

The same message can be sent to N channels for different treatment. This is first-class routing, not copy-paste. Each routing creates a `lupo_routing_events` record.

**Source:** Blog §"The Routing Reality" — "I send the same message to two different channels: Blog writing (for narrative) and Documentation (for PRD updates)." PRD 02 (updated) §"Multi-Channel Routing."

---

### Q: Is actor-context switching a first-class workflow requirement?

**ANSWER: Yes. Context tabs are a functional requirement, not cosmetic.**

The operator switches between CAPTAIN, DEVIN, ERIC, and LEXA operating modes within a single session. Each tab changes: who messages are posted from, which recent files are shown, which tasks are shown.

**Source:** Blog §"The Context Tabs (Why They Matter)" — "The context tabs at the bottom let me switch with one click... This is not cosmetic. This is functional." PRD 02 (updated) §"Context Tabs / Active Actor-Context Switching."

---

### Q: Is Enter-to-send safe by default for this workflow?

**ANSWER: Enter = Send is the default. A toggle must exist for drafting.**

Enter-to-send is already implemented in `channels/index.php` (lines 440–445). However, the operator needs a toggle (DRAFT mode) that swaps Enter to newline for multi-line prompt composition. Shift+Enter always inserts a newline in both modes.

**Source:** Blog §"The Screenshot Features — D. Enter Key Toggle" — "I cannot type in a chat box that sends immediately... I need a toggle." `channels/index.php` line 441: `if (e.key === 'Enter' && !e.shiftKey)`. PRD 02 (updated) §"Enter Key Toggle."

---

### Q: Is the screenshot prescribing final UI layout, or illustrating direction?

**ANSWER: Direction only. The screenshot is operator intent — not final doctrine.**

The ASCII mockup in the blog shows the desired direction. Specific pixel layout, exact element positioning, and visual styling are not prescribed. The functional requirements derived from the screenshot ARE binding.

**Source:** Blog §"The Screenshot (What I Want)" — shows a sketch, not a wireframe specification. Task prompt §7 "Constraints" — "treat it [the screenshot] as operator intent and workflow evidence."

---

### Q: Are context tabs equivalent to separate authenticated users?

**ANSWER: No. One authenticated user. Multiple operational modes.**

The authenticated session is WOLFIE (actor_id=1). Context tabs represent operational personas (CAPTAIN, DEVIN, ERIC, LEXA) that the human adopts within a single session. No re-authentication. No separate login.

**Source:** Blog §"The Context Tabs (Why They Matter)" — "This is not 'multiple logins.' This is one human wearing different hats." `ContextResolver.php` already models `session_mode` and `paired_actor_id` — the existing dual-identity system (human_direct / hybrid / autonomous_agent) is related infrastructure.

---

### Q: Where is the left panel — left or right?

**ANSWER: Left.**

The orchestration panel goes on the LEFT of the chat feed. The blog explicitly states "Not on the right. On the left. Because the chat is the main event, but orchestration needs its own space." The current `channels/index.php` has the sidebar on the right (`grid-template-columns: 1fr 280px`). This must change.

**Source:** Blog §"The Screenshot Features — A. Left Panel (Orchestration Panel)" — "Not on the right. On the left." PRD 02 (updated) §"Channel-Scoped Orchestration Panel."

---

### Q: Should recent files be channel-scoped, actor-scoped, or both?

**ANSWER: Both — channel-scoped AND context-tab-actor-scoped.**

Recent files in the left panel are filtered to: files accessed in the current channel AND by the currently active context tab's actor_id. Switching context tabs updates the recent files list.

**Source:** Blog §"The Header File Trick" — "When a file is accessed in a channel by a specific actor (context tab), it goes in that channel+actor's recent files list." Blog §"The Screenshot Features — A. Left Panel" — "Files accessed in this channel, scoped to current context tab." PRD 02 (updated) §"Channel-Scoped Orchestration Panel — B. Recent Files Section."

---

### Q: What metadata must travel with a "send to other channel" action?

**ANSWER: 6 required fields (source_message_id, source_channel_id, source_thread_id, source_actor_id, destination_channel_key, routed_by_actor_id).**

See PRD 02 (updated) §"Multi-Channel Routing" table. The source_actor_id is the active context tab's actor_id at time of routing — this preserves provenance.

**Source:** PRD 02 (updated) §"Multi-Channel Routing." `lupo_routing_events` schema in `auggie_sticky_note_channel_review.md`.

---

### Q: Does the chat feed reload when context tabs switch?

**ANSWER: No. The feed does not reload. Only the left panel updates.**

Switching context tabs updates `$_SESSION['active_context_actor_id']` via `POST /api/context/switch`. The JS then re-fetches the left panel content (recent files, recent tasks) for the new context. The chat feed itself is unchanged — all messages remain visible.

**Source:** PRD 02 (updated) §"Context Tabs" and §"Selected Context Bar." Constitutional constraint: switching hats doesn't change what you can see — only what you act as.

---

### Q: What already exists in channels/index.php that must be preserved?

**ANSWER: Channel dropdown, one-column feed, AJAX polling, CSRF, Enter/Shift+Enter, THOTH color, task message CSS class, member list.**

Specifically:
- Channel dropdown (line 283–287): works, keep
- AJAX polling with `after_time` cursor (lines 392–413): correct per doctrine, keep
- Transport mode negotiation / lock-in (lines 333–376): correct, keep
- DOM reload threshold 500 lines (line 317): correct per doctrine, keep
- `chat-line-task` CSS and `message_type === 'task'` conditional (lines 236, 259): keep, extend
- THOTH special color treatment (lines 257–258): keep
- CSRF protection (lines 171, 267): keep
- `layers.js` dependency (line 298): keep (OQ-18 remains open)

**Source:** `channels/index.php` full read. Task prompt §"IMPORTANT CONSTRAINTS — Do NOT start production implementation."

---

### Q: Are Target Actor Tab personas (CAPTAIN, DEVIN, ERIC, LEXA) registered as first-class actors in `lupo_actors`? (OQ-56)

**ANSWER: Yes. First-class registered actors with `actor_type = 'human_persona'`.**

The tabs represent human personas that operate as distinct actors in the system. Each is registered in `lupo_actors` as `actor_type = 'human_persona'`. This preserves full provenance in routing events (per-persona message history, per-persona recent files filtering, routing event attribution).

Session-label-only was rejected because it would break routing event provenance ("who was DEVIN when they routed this?" would be unanswerable).

**Source:** Captain's decision (20260416180000). PRD 02 (updated) §"Target Actor Tabs / Active Recipient Switching." OQ-56 resolved.

---

### Q: What is the canonical actor_id range for human persona actors? (OQ-57)

**ANSWER: 10,000 and above. CAPTAIN = 10001, DEVIN = 10002, ERIC = 10003, LEXA = 10004.**

The `max_seed_actors: 999` constitutional limit applies only to SEED-class system actors (agents, services). Human persona actors are a **separate class** (`actor_type = 'human_persona'`) with a non-overlapping range of 10,000+. The blog's reference to "CAPTAIN (actor_id 10000)" is confirmed as canonical. No PRD 99 amendment is required.

| Persona | actor_id |
|---|---|
| CAPTAIN | 10001 |
| DEVIN | 10002 |
| ERIC | 10003 |
| LEXA | 10004 |

**Source:** Captain's decision (20260416180000). PRD 02 (updated) §"Target Actor Tabs." OQ-57 resolved.

---

### Q: Does cross-channel routing target a channel or a specific agent?

**ANSWER: A specific Actor within a target channel.**

This is the **Agent-Targeted Cross-Channel Sending** doctrine. Instead of a broadcast to a channel, the operator selects both a **Destination Channel** and a specific **Target Actor** (populated from that channel's members). This "hand-shoves" the context directly into the target agent's task queue.

**Interaction:** Click `[send to other channel]` → Select Channel → Select Actor → Confirm.
**Metadata:** The routing event must include `destination_actor_id`.
**Side-effect:** If a specific actor is targeted (not broadcast), the message type is automatically set to `task` in the destination.

**Source:** Operator Refinement (20260415213000). PRD 02 (updated) §"Per-Message Cross-Channel Send Action." Implementation Plan §P0-B, P0-C.

The following questions cannot be answered from current doctrine or blog evidence. Each is logged in `open_questions.md`.

---

| # | Question | OQ Reference |
|---|---|---|
| 1 | Where does the operator scratchpad live? (DB vs. file vs. hybrid) | OQ-47 |
| 2 | How are external agents (ChatGPT, Grok, etc.) represented in the actor model? | OQ-48 |
| 3 | What defines "active context" for a channel vs. "routing target"? | OQ-49 |
| 4 | Should routing be explicit objects or inferred from message chain? | OQ-50 |
| 5 | How is agent status determined — polling vs. self-reporting vs. manual? | OQ-51 |
| 6 | Should sticky notes be first-class DB entities? | OQ-52 |
| 7 | What is the data model for a multi-hop prompt pipeline? | OQ-53 |
| 8 | Should the operator scratchpad support multiple concurrent named drafts? | OQ-54 |
| 9 | What triggers a "channel blocked" state, and who can unblock it? | OQ-55 |

**Previously open — now resolved:**
- OQ-56 ✅ Target actor personas are first-class registered actors in `lupo_actors` (`actor_type = 'human_persona'`). See Part 1 above.
- OQ-57 ✅ Human persona actor_id range is 10,000+. CAPTAIN=10001, DEVIN=10002, ERIC=10003, LEXA=10004. See Part 1 above.
