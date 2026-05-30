---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/02_B-i_CHANNELS_DB_OVERVIEW.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/02_B-i_CHANNELS_DB_OVERVIEW.md
  status: active
  when_updated: '20260513053336'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/02-channels-db-overview.toon
  atoms_toon: null
  transcript_jsonl: 0/development/channels-db-overview
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: prd
  prd_cluster: 00_A-i_02_B-i
  title: PRD 02 -- Channels Core Overview
  summary: Channel projection lupo_sessions visitor source no livehelp_users dialog session endpoints agent write-only release line tasks transport AI preservation additive.
---
# PRD 02 -- Channels Core Overview

> **Split navigation (PRD 02 family):** This file is **core channel doctrine** (projection, presence, agent rules). DDL and color YAML: **[02_channels_db_design.md](02_channels_db_design.md)**. UI and implementation surfaces: **[02_channels_mockups_modules.md](02_channels_mockups_modules.md)**.

---

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

## Projection and Presence Model (Normative)

This section anchors **Crafty Syntax-derived routing** in Lupopedia and replaces prior drift toward a **single global feed** visible to every human on the channel.

### Channel, storage, projection, presence

| Concept | Definition |
|---|---|
| **Channel** | Shared **routing context** and **governed semantic container** for many threads (namespace, membership, policy). **`channel_key`** scopes artifacts and routing within a **federation node** (domain); it is **not** a chat-room name alone. Membership lists who may participate; it does **not** grant message-body visibility. |
| **`lupo_dialog_messages`** | **Shared storage** for all routed lines. A total chronological order exists in the table; **no** UI is required to render that full order to every participant. |
| **Projection** | **Filtered participant view**: a row is visible when the viewer matches an **endpoint** of the route -- **`from_actor_id` / `to_actor_id`** (actors, operators, mapped personas) **and/or** **`from_session_id` / `to_session_id`** (live-help visitors keyed from **`lupo_sessions`**). Same Crafty **saidfrom** / **saidto** intent, **dual** column shape per **02_channels_db_design.md** Dual Endpoints. |
| **Presence** | **Awareness only** -- who is joined, online, or idle on the channel. Presence does **not** imply read access to unrelated parties' message bodies. |

### Default visibility rule

By default, a participant sees only messages where **either** `from_actor_id` or `to_actor_id` equals that participant's actor id **when they are an actor-backed party**, **or** `from_session_id` or `to_session_id` equals that participant's browser **`session_id`** when they are a **visitor session** party, **plus** any rows explicitly defined as **broadcast to that participant class** by product policy (for example reserved `to_actor_id` values). **Do not** widen visibility from channel membership alone.

**Normative anti-pattern:** Do **not** assume channel membership implies message visibility.

### Presence vs projection

- **Visitors** do not see each other's traffic unless explicitly routed (rare; product-defined); default visibility uses **`from_session_id` / `to_session_id`**, not actor ids, for visitor parties.
- **Actors** do not see unrelated visitor pairs or unrelated actor conversations unless those rows are endpoints for that actor.
- A **human operator** who handles two visitors sees **two merged projections** because both streams are routed **to/from that operator** -- not because the channel is globally readable.

### Elevated monitoring (optional, non-default)

**Captain / supervisor / audit** surfaces may implement **elevated visibility** (wider than default projection). That mode is **privileged, explicit, off by default**, and **not** implied by observer-class tab styling or dark tabs.

### THOTH and alerts (constitutional, not UI omniscience)

**THOTH [ALERT]** and related enforcement remain constitutional per coordination doctrine and **PRD 82**. That path uses **transcript, routing, and enforcement surfaces** -- it is **not** modeled here as "every THOTH tab renders every channel row." UI projection rules still apply unless a separate **elevated audit** product mode is enabled.

### Release line: 4.1.3 baseline, 4.1.5-4.1.9 layered development, 4.2.0 first public release

| Line | Scope |
|---|---|
| **4.1.3** | **REQUIRED** non-AI / no-required-API-key **live help baseline**: actors as participants; canned responses; **projection + presence**; **orchestration chrome is NOT a ship gate** (no task-routing-first or prepared-prompt-primary UI **as acceptance criteria** for this milestone). |
| **4.1.5-4.1.9** | **Continuing Lupopedia development**: orchestration UI, AI layering, command-center patterns, cross-channel and task surfaces -- implemented on **fresh-install** version increments; **not** a public Lupopedia-to-Lupopedia upgrade ladder. |
| **4.2.0** | **First public Lupopedia release** (external operator expectations): full **layered** system ships per product plan. Supported path into Lupopedia remains **Crafty Syntax 3.7.5 -> Lupopedia** per root doctrine -- **not** internal pre-4.2.0 Lupopedia-to-Lupopedia upgrades. |

**4.1.3 is a restricted operational mode of the same product**, not a fork.

**Normative note (install / upgrade path):**

- Pre-**4.2.0** **4.1.x** versions are **development / staging** lines: each assumes **fresh install** (and/or Crafty import), not an in-product Lupopedia patch chain.
- **MUST NOT** treat pre-**4.2.0** Lupopedia-to-Lupopedia upgrades as **supported** production migration; public release expectations attach to **4.2.0**, not every interim **4.1.x** tag.

- **4.1.3 ship gate (MUST NOT mis-scope):** Target Actor Tabs, Active Target Bar, Dual-Button send-to-task, per-message **send to actor**, cross-channel handoff controls, and other **task-first / prompt-first** orchestration chrome **MUST NOT** be **required** implementation or acceptance criteria for **4.1.3**. That **does not** defer orchestration work to **4.2.0** only: it **continues** in **4.1.5-4.1.9** and converges in the **4.2.0** public bundle.

## The Chat Is Not A Conversation

> **This is the most important thing to understand about the chat interface.**
> If you misunderstand this, every design decision below will seem wrong.

### One-column projection feed (not global channel feed) (channel vs thread)

**This UI does NOT render the full channel. It renders the participant's projection only.**

> A **channel** is a **routing context**. Storage holds **all** messages for threads in that context. The **UI** still uses **one chronological column** for the active session, but that column shows the **participant's projection** (endpoints `from_actor_id` / `to_actor_id` **and/or** `from_session_id` / `to_session_id`), **not** the full stored sequence, unless an explicit **elevated audit** product mode is enabled (privileged; **not** tied to a single version number).
>
> - **One scrolling feed** per session view of the filtered stream.
> - Within that projection, lines from different threads may be **intermixed**; the visual distinction remains the **full-width row** color / provenance for each row.
> - Threads stay **logical** constructs for color and lineage -- not separate panes that imply a second hidden global feed.

The **Agent Write-Only Rule** applies to how builder agents consume **instructions**; it does **not** prove that any human sees **all** rows. Operators see **their** projection; builder agents do **not** use peer traffic as their instruction bus.

### Channel Projection with Mixed Participants

The **main chat view** is a **projection**, not ownership of a single thread. It **merges** messages from **every thread** in the channel that the viewer is allowed to see, **sorted by time**, with **per-thread color coding** on each row (current UI reference: Captain's Log / channel screenshots in **02_channels_mockups_modules.md**).

**Same channel, mixed thread types:**

- **Actor-only threads** -- AI orchestration, internal team lines, tasks (**`from_actor_id` / `to_actor_id`**; session endpoints typically NULL).
- **Visitor-involved threads** -- live help (**`from_session_id` / `to_session_id`** where the visitor is a party; operators use actor endpoints on the other side).

**Visibility (extends Default visibility rule):**

- An **actor** sees rows where **`from_actor_id`** or **`to_actor_id`** matches them, **or** where they are the operator counterparty to a visitor line (**`from_session_id` / `to_session_id`** match sessions they are chatting with, per routing policy).
- A **visitor** (browser **`session_id`**) sees **only** rows where **`from_session_id`** or **`to_session_id`** equals their **`session_id`**.

**Orchestration preservation:** Agents may post into **hybrid** threads; HERMES, task queues, and write-only doctrine remain in force. DDL for **`lupo_sessions`** visitor columns and **`lupo_dialog_messages`** session endpoints: **02_channels_db_design.md** Visitor Model and Dual Endpoints.

**Visitor lifecycle (unchanged):** **browsing** **->** **invited** **->** **chatting** **->** **stopped** -- see **Visitor Management** below.

**Visitor list transport:** XMLHTTP (or fetch) **primary**, image ping **fallback**, meta refresh **last** -- see **Transport fall-forward** under Visitor Management and **02_channels_mockups_modules.md** Visitors Section.

### Visitor Background Color (Per Viewing Actor)

Visitor-party **row background** tint in the **operator** projection follows **02_channels_db_design.md** **Visitor Row Background Color (Per Viewing Actor)**. Colors are **per logged-in operator `actor_id` + visitor `session_id` + `channel_id`**, allocated at **Accept Chat** insert time ( **`from_actor_id` -> `to_session_id`** on the accept row per **Dual Endpoints**), **not** on visitor arrival alone. **Projection** visibility for that operator still follows **Default visibility rule**; color state does **not** widen read access.

### Outbound Compose Target Modes (VISITOR / USER / AGENT)

The **bottom** compose surface on **`channels/index.php`** uses a **mode dropdown** plus a **tab row** to set the **next** outbound routing target: **VISITOR** mode routes via **`to_session_id`**; **USER** and **AGENT** modes route via **`to_actor_id`**, subject to the same endpoint and projection rules as today. Full UI labels, tab population rules, and placement relative to the legacy Active Target Bar: **02_channels_mockups_modules.md** **Dynamic Target Selector (Send message to:)**.

### Visibility is projection, not omniscience

The stored stream is **not** a single global conversation shown to every human by default. Each session renders **authorized rows** (projection) plus **presence** indicators elsewhere in the UI.

| Participant | Sees in default dialog UI | Uses rows for instruction context | Writes into `lupo_dialog_messages` | How work is assigned |
|---|---|---|---|---|
| Visitor | Only rows where the visitor's **`session_id`** matches **`from_session_id`** or **`to_session_id`** (visitor is **not** assumed to be a `lupo_actors` row unless product explicitly maps one) | Same projection | Per visitor policy | Operator lines hit **`to_actor_id`** / **`from_actor_id`** against visitor **`to_session_id`** / **`from_session_id`** |
| Actor (human operator) | Rows where that actor is `from_actor_id` or `to_actor_id`, **plus** rows where that actor is the counterparty to the operator's current visitor sessions via session endpoints | Same projection | Yes | Session + channel membership + routing |
| Monitoring / observer-class actor | **Only** projection-visible rows unless **elevated audit mode** is explicitly on | Scoped per policy; **not** omniscient because the tab is dark | Alerts / scoped posts per doctrine | Tasks + configured surfaces |
| Builder agent (IDE facet, write-only) | **Does not** read peer chat for instructions | **No** | Yes (`stdout` / `stderr` / structured posts) | **Task queue** and handoff artifacts |
| HERMES | Routing layer | Selective implementation reads | Yes | Configuration + routing rules |

### Why Builder Agents Never Read The Chat

Builder agents are **write-only** from the chat perspective. They post their output so **authorized projections** can show it to operators. They do NOT read peer chat for context because:

1. **Context pollution** -- Other people's messages are irrelevant or misleading for their assigned slice
2. **Confusion** -- They would act on lines not routed to them
3. **Chaos** -- Multiple agents consuming one firehose would duplicate and fight work

**Operators coordinate through routed lines and tasks; agents do not treat each other's traffic as their instruction bus.**

### How Agents Actually Receive Instructions

Do not post in chat and hope an agent sees it. They won't.

| Method | Syntax | Destination |
|---|---|---|
| Task assignment | `[task] who: CURSOR what: fix header` | Agent's task queue |
| Direct task API | `POST /api/task/assign` | Agent's task queue |
| Memory graph | Agent reads TOON edges at session start | Agent's context |

### HERMES Routing Rules

HERMES is the message router between the chat interface and agent task queues. It does not make agents "read" the chat -- it translates specific message patterns into task queue entries.

| Message Type | Destination | Memory Gateway Action |
|---|---|---|
| `[task] who: X ...` | Task queue of agent X + **storage** row (operators see it only if the row is in **their** projection) | Append JSONL record to `transcript_jsonl`; extract task_assignment pattern to staging toon |
| `[alert]` | **Enforcement / transcript path** per PRD 82 and coordination doctrine; **not** "render to every human" by default | Append JSONL record; extract alert pattern |
| `stdout` from builder agent | **Storage**; operators with projection containing that row + logs | Append JSONL record |
| `stderr` from builder agent | **Storage**; operators in projection + log + alert if critical | Append JSONL record; extract alert if severity >= ERROR |
| Directed message (monitors only) | Routed endpoint actor (e.g. THOTH) per explicit `to_actor_id` / rule | Append JSONL record |
| Any routed message | See above | Append JSONL record (all types, unconditional) |
| Repeated pattern (N >= threshold) | Staging memory toon | Flag `promotion_candidate: true`; THOTH promotes to canonical |

Builder agents never receive messages from HERMES unless those messages are tasks in their queue. See [HERMES_DOCTRINE.md](../doctrine/HERMES_DOCTRINE.md) for full routing specification. See [PRD 82](82_hermes_message_routing_memory_gateway.md) for the canonical HERMES specification including transcript format, toon schema, and promotion protocol.

### Agent Write-Only Rule (Constitutional)

Per the Dual-Purpose Doctrine (CLAUDE.md section 4):

> **Agent Write-Only Rule:** You (and all agents except THOTH) post output *to* the stream but do *not* read the stream for context.

**Clarification:** Agent output appears in chat for **human consumption** (and for **authorized projections**) only. Agents do **NOT** consume chat as input.

This is not a suggestion. It is a constitutional rule. Agents that read the chat for instructions are architecturally broken.

---
## Header & Memory Integration (v4.1.0)

Every discussion-related artifact follows PRD 16:

* `memory_toon` -> compressed knowledge graph node
* `transcript_jsonl` -> DB lookup slug for the full reasoning thread
* `atoms_toon` -> immutable constraints (when present)

The chat system is the WHY layer. The PRDs and code are the WHAT layer.

---
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

## Visitor Management (Crafty Syntax Core Feature)

Human live help preserves **Crafty Syntax 3.7.5** visitor list, invitation, and operator-department scoping semantics. DDL detail: **02_channels_db_design.md** Visitor Model. UI: **02_channels_mockups_modules.md** Visitors Section.

### Single source: `lupo_sessions` (not `livehelp_users`)

Lupopedia **does not** ship a parallel **`livehelp_users`** runtime table. Anonymous visitor **state** is carried on **`lupo_sessions`** (see **02_channels_db_design.md** **Sessions as the visitor source of truth**). Legacy Crafty **`livehelp_users`** rows are **import transforms** only. Admin-style surfaces MUST query **`lupo_sessions`** (+ **`lupo_actors`** joins), not resurrect **`livehelp_users`** as authoritative storage.

### Department and channel scope

- Each **visitor** row is in **exactly one** department at a time (`department_id` single-valued).
- Each visitor has **at most one** active live-help **`channel_id`** at a time for routing (see **02_channels_db_design.md** Visitor Model **Channel binding**). **Browsing** MAY omit channel until invite; **invited** / **chatting** bind **exactly one** channel until an explicit transition ends it.
- **Operators** may belong to **multiple** departments.
- An operator sees **all** visitors from **any** department they are assigned to (union of department scopes).

### Right sidebar visitor list (department union, not channel-limited)

**Normative:** The **Visitors** panel in the **right navigation sidebar** on **`channels/index.php`** lists **all** **`lupo_sessions`** visitors whose **`livehelp_department`** matches **any** department row in **`lupo_actor_departments`** for the **logged-in operator `actor_id`**. Scope is the **union** of that operator's department memberships. **Do not** restrict this list to visitors whose **`onchannel`** equals the **currently viewed `channel_id`** unless a **separate** product mode explicitly defines a "channel-only" filter; the default normative list is **department-wide**, not channel-page-local.

**Intent from messages:** Sidebar **browsing / wants to chat / actively chatting** labels for a visitor **MAY** be derived from **`N`** = count of **`lupo_dialog_messages`** rows with **`from_session_id`** or **`to_session_id`** equal to that visitor's **`session_id`**: **`N = 0`** browsing; **`N = 1`** wants to chat (await accept); **`N >= 2`** actively chatting. Reconcile with **`visitor_status`** column per **02_channels_db_design.md** Message-count-based visitor intent.

**Cross-ref:** **Dual Endpoints** and **Projection** rules unchanged.

### Operator sidecar UI split (cross-reference, additive)

When the operator console **splits** **Browsing Visitors**, **Wants Chat**, **Users**, **Agents**, **Recent Files**, and **Recent Tasks** in the right sidebar (baseline **`templates/channels/index.php`**), message-count and peer-count rules map to controls per **02_channels_mockups_modules.md** **Right sidebar chrome** (**E.a** through **E.f**) and **Auxiliary composer controls** under **Dynamic Target Selector**. The **Additive UI alignment** paragraph in this file remains the **single-list** summary; template-first layouts MAY use the split without changing projection SQL.

### Visitor status flow (Crafty Syntax heritage)

1. **browsing** -- Visitor is on the site; not yet invited.
2. **invited** -- Operator sent an invitation (layer, popup, or insite); waiting for acceptance.
3. **chatting** -- Visitor accepted; active chat session (may be **conference** with multiple operators on the same visitor thread per routing policy).
4. **stopped** / **offline** -- Visitor left, declined, timed out, or session ended.

Exact state strings and transitions are defined in application logic and DDL; UI MUST NOT invent parallel state machines that contradict the DB.

**Additive UI alignment:** When the **message-count derivation** (**`N`** on session endpoints) is used in the sidebar, map actions as follows without breaking projection doctrine: **`N = 0`** **Browsing** **->** **[Invite]**; **`N = 1`** **Wants to chat** **->** **[Accept Chat]**; **`N >= 2`** **Actively chatting** **->** **[Join Chat]** and **[Stop]** (same semantics as **02_channels_mockups_modules.md** Visitors Section message-count row). **`visitor_status`** on **`lupo_sessions`** remains the DDL-backed lifecycle field; keep counts and column in sync per implementation policy.

### Operator actions on visitors

| Action | Effect | Legacy Crafty reference (import / behavior only) |
|--------|--------|--------------------------------------------------|
| View visitors | Renders department-scoped list with status and actions | `admin_users_refresh.php`, `admin_users_xmlhttp.php` |
| Initiate chat / Invite | Opens invitation path; sets visitor to **invited**; may create or bind thread and **single** `channel_id` per product rules | `admin_users_*.php`, `admin_chat_bot.php` tab flows |
| Send layer invite | Default or configured invite surface (layer); may be swapped for popup or insite per product | `layer.php` |
| Join chat | Operator joins an existing active chat (conference allowed) | `admin_users_refresh.php` patterns |
| Stop / Leave | Operator leaves chat; other operators may continue in conference mode | Conference handling in operator channel joins |
| Ignore | Temporarily hide visitor for this operator session | `ignorelist` style parameters |
| View details | Referer, pages visited, time online | `details.php`, `seepages()` class patterns |

### Auto-invite (optional, classic Crafty)

Operators MAY enable **auto_invite**. When enabled, new visitors in scope trigger an automatic invitation path and optional sound alert (`user_alert` class semantics). Product MUST rate-limit and respect department scope.

### Transport fall-forward (normative)

The operator console MUST support **degraded transport** without silent failure, mirroring Crafty fall-forward:

1. **Primary:** AJAX / XMLHTTP polling (legacy: `xmlhttp.js`, `ExecRes`, `admin_chat_xmlhttp.php` / `admin_users_xmlhttp.php` class endpoints).
2. **Fallback 1:** Image-based **new data** signal (legacy: `csgetimage()` / `lookatimage()`, `admin_image.php` width check, `peoplestring` hash for change detection).
3. **Fallback 2:** `<meta http-equiv="refresh">` or equivalent timed reload (legacy: `admin_refresh` style configuration).

Degradation MUST be **automatic** and **ordered**: try primary; on failure or capability denial, fall back in sequence. Lupopedia exposes the same ordering on modern surfaces (fetch / XHR first, then image ping, then timed full refresh) unless a PRD documents a narrower host profile. Visitor list polling uses this ladder; it does **not** replace or throttle HERMES, task polling, or agent transports unless an explicit host profile documents shared backoff.

### AI preservation (additive)

Visitor management is **additive** only. **HERMES**, **PRD 82** memory gateway paths, **task queues**, **Target Actor Tabs**, agent write-only doctrine, orchestration chrome (where shipped), and IDE facet routing remain **fully supported** and run **concurrent** with visitor live help. Visitor features MUST NOT remove, gate, or degrade those surfaces except where a later PRD defines an explicit, scoped interaction.

## Task System

### Purpose

Coordinates work across multiple parallel agents. Task assignments travel through the task queue -- never through the chat stream. The chat is for human oversight; the task queue is for agent coordination.

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

### Agent Task Polling

Each agent periodically checks for assigned tasks (via cron or IDE plugin):

```php
// scripts/agent_poll_tasks.php
// Run by agent IDE every 30 seconds

require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/chat/message_functions.php';

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
### Target Actor Tabs / Active Recipient Switching

**Orchestration-primary UI (normative when building AI / command-center surfaces in 4.1.5-4.1.9 and 4.2.0):** One authenticated human selects the **target actor** (message recipient) by clicking a tab at the bottom of the interface. This is **not** multiple user logins. Tabs determine **`to_actor_id`** for the **next composed** message or task.

**4.1.3 human live help baseline:** Actors are **already participants** in visitor threads. **Target-actor dispatch is not** the primary model; avoid shipping a **prompt / task-first** console as the default operator shell. Canned responses and visitor-scoped chat remain the baseline response surfaces.

**Target Actor Tabs** (where present) are persistent UI elements above the input area. Each tab names an actor the operator may address for **outbound** routing in orchestration mode.

**Target Actor Tab examples from canonical workflow:**

| Tab Label | Actor ID | `to_actor_id` Set | Input Background Color |
|---|---|---|---|
| CAPTAIN | 10001 | CAPTAIN (self-direction / broadcast context) | CAPTAIN's thread color |
| DEVIN | 10002 | DEVIN persona as recipient | DEVIN's thread color |
| ERIC | 10003 | ERIC persona as recipient | ERIC's thread color |
| LEXA | 10004 | LEXA persona as recipient | LEXA's thread color |

> **Actor ID resolution (OQ-56, OQ-57 -- RESOLVED):** Target actor personas (CAPTAIN, DEVIN, ERIC, LEXA) are registered as first-class actors in `lupo_actors` with `actor_type = 'human_persona'`. Their canonical actor_id range is **10,000+**, distinct from the seed actor range (100-999). This preserves full provenance in routing events.

**What changes when a Target Actor Tab is switched:**
1. `active_target_actor_id` in the session (`$_SESSION['active_target_actor_id']`) -- determines `to_actor_id` of next message or task
2. Input area background color (`--input-bg` CSS variable) -- syncs to the target actor's thread `background_color`
3. Active Target Bar label -- updates to `SENDING TO: {ACTOR_NAME}`
4. Recent Files list in left panel -- filtered to files accessed by that actor in this channel
5. Recent Tasks list in left panel -- filtered to tasks where `assigned_to_actor_id` matches the target actor

**What does NOT change when a Target Actor Tab is switched:**
1. The authenticated session (no re-auth)
2. **Projection policy** -- switching tabs **does not** grant omniscient read access; it **must not** reveal unrelated pairs' histories by default
3. Historical `from_actor_id` / `to_actor_id` on existing rows (immutable)
4. HERMES routing rules and agent write-only doctrine (unaffected)

**What may change in orchestration UI:** Outbound `to_actor_id` for the **next** message/task only. Feed contents change **only** if the implementation applies an explicit filter tied to tab (not recommended; prefer projection from routing alone).

**Storage:** `$_SESSION['active_target_actor_id']`. It is NOT a URL parameter. It is NOT visible to agents.

**Constitutional rule (orchestration mode):** Target Actor Tab determines **`to_actor_id`** for **new** messages and tasks. Switching tabs does **not** rewrite prior rows.

### Observer vs Active Actor Tab Doctrine

Established via live multi-engine session (canonical reference: Captain's Log 20260416 -- "The Four-Engine Render Ordeal"). This doctrine divides actor tabs into **visual categories**; **visibility still follows projection** unless **elevated audit mode** is explicitly enabled (non-default).

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
- Message visibility is **projection**: directed lines for that `actor_id` and policy-defined broadcasts -- **not** the full multi-visitor firehose.
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

**CSS class:** `.chat-active-output` -- no additional styling required beyond the inline `background-color`; the class is a semantic hook for JS targeting.

## Anchored Truth Doctrine: The Sieve and THOTH [ALERT] Protocol

> **Hierarchy of Truth -- The Sieve:**
> - **1026 Nodes (The Ancestors):** Finalized, merged, verified FACT. These are the benchmark for all truth in Lupopedia.
> - **2026 Nodes (The Descendants):** Temporary staging, active work, unverified "thoughts." Useful for drafting, but not authoritative.
> - **THE LAW:** If a 2026 node contradicts a 1026 node, the 2026 node is **WRONG** until a **Captain's Amendment** is issued. No AI, agent, or human may override a canonical ancestor with a descendant unless the amendment is explicit and logged.

> **THOTH [ALERT] Protocol:**
> - If a contradiction is found between a 2026 and a 1026 node:
>   1. **Fix the Staging Node** if it is hallucinated or logically wrong.
>   2. **Amend the 1026 Node** only with explicit Captain (WOLFIE) approval.
> - **No merge or implementation may proceed until the contradiction is resolved.**
> - **THOTH [ALERT]** is a hard stop for all agents and implementers.

**Recency Bias Trap:**
> Modern AI models and agents must not prefer newer (2026) staging nodes over older (1026) canonical facts. The system is designed so that recency does not override truth. Only a Captain's Amendment can change a canonical fact.

The system is designed to be simple, transparent, and maintainable while supporting complex multi-agent workflows.

## Common Predictive Text / AI Misconceptions (Memory Guardrails)

Frequent AI hallucinations this PRD counters:
- "Main chat view is a single thread" -> FALSE. It is a time-sorted projection mixing multiple threads (color-coded).
- "All participants live in lupo_actors" -> FALSE. Operators/agents = actors; visitors = lupo_sessions only.
- "said_from/said_to only reference actors" -> FALSE. Messages are polymorphic: from_actor_id + from_session_id, to_actor_id + to_session_id.
- "Visitor management is separate from orchestration" -> FALSE. Visitors share channels/threads with actors and agents.
- "Old Crafty 1:1 model still applies" -> FALSE. New model supports multiple humans + multiple visitors + multiple agents on one channel.
- "Projection = thread" -> FALSE. Projection = filtered time-sorted view across many threads.

These guardrails are binding for all future code and documentation in this scope.

### Strengthened guardrails (additive)

Frequent AI hallucinations this PRD counters (fuller phrasing):
- "Main chat view is a single thread" -> FALSE. It is a time-sorted projection mixing multiple threads with per-thread color coding.
- "All participants live in lupo_actors" -> FALSE. Operators/agents = actors; visitors = lupo_sessions only.
- "said_from/said_to only reference actors" -> FALSE. Messages are polymorphic: from_actor_id + from_session_id, to_actor_id + to_session_id.
- "Visitor management is separate from orchestration" -> FALSE. Visitors share channels/threads with actors and agents.
- "Old Crafty Syntax 1:1 model still applies" -> FALSE. New model supports multiple humans + multiple visitors + multiple agents on one channel.
- "Projection = thread" -> FALSE. Projection = filtered time-sorted view across many threads.

**Endpoint visibility:** Projection visibility = endpoint match only, not full channel membership granting all stored rows.

**Concurrency:** Visitor management and AI orchestration run concurrently on the same channels.

These guardrails are binding for all future code generation and documentation in this PRD scope.

