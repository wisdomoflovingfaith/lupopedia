---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/versions/4.1.0/open_questions.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.1.0/open_questions.md"
  status: "active"
  when_updated: "20260415102813"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/version-4-1-0-open-questions.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/4_1_0_open_questions"
  artifact_type: documentation
  artifact_kind: guide
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: "Lupopedia 4.1.0 open questions"
  summary: "Canonical open questions for Lupopedia 4.1.0 — merged from root and status/open_questions.md. OQ-01 to OQ-06 legacy format; OQ-07 onwards current format."
---
# Open Questions — Lupopedia 4.1.0

Rolled over from version 4.0.99 to 4.1.0
No changes to content
Migration date current UTC `20260415074141`

## Rules (READ FIRST)

- APPEND ONLY. Do NOT rewrite or delete prior entries.
- OQ-01 to OQ-06: legacy format — WHEN / FILE / TYPE / WHAT / WHY / IDEAL / BLOCKING (rolled over from 4.0.99)
- OQ-07 onwards: current format — WHEN / WHO / AREA / STATUS / QUESTION / WHY THIS MATTERS / OPTIONS / CAN WORK CONTINUE
- Merged from `docs/versions/4.1.0/status/open_questions.md` on 20260415101800; that file is now deleted.

---

## OQ-01

**WHEN:** 20260414120000

**FILE / AREA:** `includes/classes/DialogMvpService.php` — `createDialogMessage()`

**TYPE:** database_change_idea

**WHAT:** The UPDATE query after inserting a message bumps `last_message_ymdhis` and `updated_ymdhis`
on `lupo_dialog_threads`. PDO with `ATTR_EMULATE_PREPARES=false` (MySQL native prepared statements)
does not allow a named parameter to appear twice in the same query. The original code used `:ts`
in both `SET` clauses, causing SQLSTATE[HY093].

Fixed by: using `:ts_lm` and `:ts_up` as distinct parameter names bound to the same `$now` value.

**WHY:** HY093 fires on every message send. The channel command center was silently returning
`ok: false` on AJAX sends whenever a message was actually saved (the INSERT succeeded but the
UPDATE threw, the exception propagated out of the try block and the endpoint returned error JSON).
Users saw the message not appear in their own feed until the next poll cycle.

**IDEAL FUTURE CHANGE:** Audit all `$db->query()` calls in the codebase for repeated named
parameters. A wrapper-level check in `PDO_DB::query()` could detect duplicate named params at
dev time (scan regex `/:([a-z_]+)/gi`, flag repeats before executing). Add to PDO_DB test suite.

**BLOCKING:** Was blocking — now fixed.

---

## OQ-02

**WHEN:** 20260414120000

**FILE / AREA:** `includes/classes/DialogMvpService.php` — `createDialogMessage()`

**TYPE:** schema_improvement

**WHAT:** The `$insert_row` array for `lupo_dialog_messages` contains 21 columns including
`message_id` (duplicate of `dialog_message_id`), `source_faucet_slug`, `source_faucet_instance_id`,
`read_by_actor_id`, `read_by_actor_utc`, `message_body` (duplicate of `message_text`), and
`mood_framework`. If any of these columns do not exist in the actual table, the INSERT fails
with "Unknown column" (MySQL error 1054). The actual schema must be verified against
`database/lupopedia/json/lupo_dialog_messages.json`.

**WHY:** Column mismatches produce runtime errors that are swallowed by the catch block and
returned as `ok: false` to the AJAX caller. Silent failure in a command center is unacceptable.

**IDEAL FUTURE CHANGE:** Add a startup schema validator (dev-mode only) that compares
`SHOW COLUMNS FROM lupo_dialog_messages` against the JSON schema definition. Run once per
deploy, not per request. Flag columns present in code but absent in DB.

**BLOCKING:** Non-blocking if schema is correct. Potentially blocking if table is missing
columns. Verify with `SHOW COLUMNS FROM lupo_dialog_messages`.

---

## OQ-03

**WHEN:** 20260414120000

**FILE / AREA:** `channels/index.php` — thread resolution

**TYPE:** implementation_note

**WHAT:** The page creates one `dialog_thread` per day (`$today_thread_key = gmdate('Y-m-d')`).
New messages within the same UTC calendar day go into the same thread. Messages on different
days go into different threads. The initial page load only queries messages from ALL threads
in the channel (`WHERE m.channel_id = :cid`), so cross-day messages are shown correctly.

The thread-per-day model means there is always exactly one "current thread" per channel per
day. This is intentional but undocumented.

**WHY:** Matters for any feature that queries by thread (e.g., thread summary, THOTH thread
context). The daily boundary is UTC, so at midnight UTC a new thread begins, which may be
mid-afternoon in some operator timezones.

**IDEAL FUTURE CHANGE:** Document this in PRD 02 as canonical behavior. Optionally expose
the thread boundary in the command center header so operators know when the day rolled.

**BLOCKING:** Non-blocking.

---

## OQ-04

**WHEN:** 20260414120000

**FILE / AREA:** `api/dialog/fetch-messages.php` — polling endpoint

**TYPE:** implementation_note

**WHAT:** The fetch-messages endpoint has no authentication gate. Any request with a valid
`channel_id` returns all non-deleted messages for that channel newer than `after_time`. There
is no actor_id check, no session check, no channel access check.

For the development channel this is acceptable (internal tool). For future channels with
restricted visibility (`visibility_status != 'active'`), this leaks messages to unauthenticated
callers.

**WHY:** The command center is currently internal-only. But the architecture will need this
fixed before any restricted-visibility channel is deployed.

**IDEAL FUTURE CHANGE:** Add session validation in fetch-messages.php:
1. Validate `$GLOBALS['lupo_session']->validateSession()` returns a non-null actor_id.
2. Check `lupo_actor_channels` for membership OR check `lupo_channels.visibility_status = 'public'`.
3. Return 403 JSON on failure.

**BLOCKING:** Non-blocking for current internal use. Blocking before restricted channels are added.

---

## OQ-05

**WHEN:** 20260414120000

**FILE / AREA:** `channels/index.php` — DOM reload guard

**TYPE:** implementation_note

**WHAT:** The DOM reload guard triggers at 500 DOM lines (`DOM_RELOAD_THRESHOLD = 500`).
After reload, the page re-fetches all messages from the DB (LIMIT 200). If there are more than
200 messages in the channel since the epoch, older messages are lost from view until the user
navigates back manually. There is no "load older messages" feature.

**WHY:** The command center is a live feed, not an archive viewer. Losing old messages from
view on reload is by design. But operators might want to scroll back. This should be a conscious
product decision, not an accidental limitation.

**IDEAL FUTURE CHANGE:** Add a "load older" button in the feed that fetches `before_time` cursor
messages. Separately, consider raising or removing the initial 200-message LIMIT on page load
(replace with a cursor-based paginator).

**BLOCKING:** Non-blocking.

---

## OQ-06

**WHEN:** 20260414120000

**FILE / AREA:** `channels/index.php` — channel resolution fallback

**TYPE:** ambiguity

**WHAT:** If the requested `channel_key` is not found, the code falls back to `channel_id = 42`
(hardcoded). If channel 42 does not exist, it attempts to INSERT it. If that also fails, the
fallback UPDATE is tried. This is a magic number (42) embedded in the channel resolution logic.

**WHY:** Magic numbers violate the "No Magic Numbers" pet peeve. The fallback channel ID should
be a `DEFINE` constant, or the fallback should be resolved by looking for a channel with
`channel_key = 'lupopedia-development'` and inserting if not found (no hardcoded numeric ID).

**IDEAL FUTURE CHANGE:** Replace `$channel_id = 42` and `WHERE channel_id = 42` with:
`define('LUPO_DEFAULT_CHANNEL_KEY', 'lupopedia-development')` and resolve by key only.
Remove all hardcoded numeric channel IDs from PHP. Add to CLAUDE.md pet peeves.

**BLOCKING:** Non-blocking for existing setup. Blocking if default channel is ever moved.

---

## OQ-07: Canonical atoms_toon namespace root

**WHEN:** 20260415091500
**WHO:** AUGGIE
**AREA:** scripts/validate_lupopedia_headers_universal.py + PRD 16
**STATUS:** open

**QUESTION:**
What is the canonical directory namespace for `.atoms.toon` files (especially for non-PRD artifacts)? Current implementation allows paths under existing structures (e.g. headers/canonical/1026/...), but we may want a dedicated `/atoms/` segment for clarity.

**WHY THIS MATTERS:**
Prevents future collision risk and makes immutable constraints visually distinct from descriptive memory nodes.

**OPTIONS:**
1. Keep current flexible path discipline (headers/, development/, etc.) and defer dedicated namespace
2. Require `/memory/atoms/canonical/1026/...` for all atoms_toon
3. Transitional alias logic in validator

**CAN WORK CONTINUE:** yes (Phase 1 pointer validation works regardless)

---

## OQ-08: Should 4.1.0 plan.md also exist at project root?

**WHEN:** 20260415094500
**WHO:** WOLF
**AREA:** Documentation structure
**STATUS:** open

**QUESTION:**
Should the canonical active plan live only in `docs/versions/4.1.0/plan.md`, or should there also be a root-level planning file (e.g. `plan.md` or `4.1.0-plan.md` at repo root) aligned to Codex markdown structure?

**WHY THIS MATTERS:**
Agents need one clear source of truth and should not drift between version-local and root-level files.

**OPTIONS:**
1. Keep only version-local plan.md
2. Keep version-local + root-level pointer/summary
3. Move canonical plan to root and keep version file as release snapshot

**CAN WORK CONTINUE:** yes

---

## OQ-09: Polling endpoint URL pattern — PRD vs actual code

**WHEN:** 20260415100644
**WHO:** AUGGIE
**AREA:** channels/index.php + PRD 02 API Endpoints section
**STATUS:** open

**QUESTION:**
PRD 02 documents the live feed polling endpoint as `GET /api/chat/messages`. `channels/index.php` polls `GET /api/dialog/fetch-messages.php`. These are different URL namespaces and conventions. Which is canonical? Is `/api/chat/messages` an aspirational spec or a future refactor target?

**WHY THIS MATTERS:**
Any new client code (agent wrappers, mobile clients, HERMES) written against PRD 02 will point at the wrong URL. API documentation and client code diverge immediately.

**OPTIONS:**
1. Adopt `/api/dialog/fetch-messages.php` as canonical; update PRD 02
2. Build a `/api/chat/messages` alias that proxies to the real endpoint
3. Declare PRD 02 API section as aspirational; note actual URL in a separate implementation note

**CAN WORK CONTINUE:** yes (code works, but new agent clients will use wrong URL)

---

## OQ-10: Poll query scope — channel-wide vs thread-scoped

**WHEN:** 20260415100644
**WHO:** AUGGIE
**AREA:** channels/index.php L188-200 vs PRD 02 Transport Model Doctrine
**STATUS:** open

**QUESTION:**
The initial message load in `channels/index.php` queries `WHERE m.channel_id = :cid` with no `thread_id` filter, returning messages across ALL threads in the channel (last 200 by time). PRD 02's Transport Model Doctrine shows the server query filtering on BOTH `channel_id` AND `thread_id`. Is the one-column feed intentionally channel-wide (all threads mixed), or should it be thread-scoped? The poll URL also does not include `thread_id`.

**WHY THIS MATTERS:**
A channel-wide feed means the "current thread" concept has no effect on what users see. Thread colors and thread keys become cosmetic-only. If the feed is supposed to be thread-scoped, the implementation is wrong.

**OPTIONS:**
1. Channel-wide is correct; remove thread-scoping from PRD 02 doctrine
2. Add thread filter to initial load and poll; add thread selector to UI
3. Channel-wide for base view; optional thread filter via query param

**CAN WORK CONTINUE:** yes

---

## OQ-11: Tasks sidebar element `tasks-list` is not in the HTML

**WHEN:** 20260415100644
**WHO:** AUGGIE
**AREA:** channels/index.php L452-495 (task UI JS)
**STATUS:** open

**QUESTION:**
`channels/index.php` defines `fetchTasks()` and `renderTasks()` in JavaScript and calls them on load (L494-495), but `var tasksListEl = document.getElementById('tasks-list')` returns `null` because no element with that id is rendered in the HTML template. The task panel silently does nothing. Is this a missing template fragment or is the feature intentionally stubbed?

**WHY THIS MATTERS:**
PRD 02 mandates a Tasks tab in the sidebar. The code appears to be wired for it but the HTML is absent. Any agent or user looking at the channel page has no task visibility.

**OPTIONS:**
1. Add `<div id="tasks-list">` to the channel sidebar HTML
2. Remove the dead JS code until the Tasks tab is actually built
3. Document as known stub in PRD 02

**CAN WORK CONTINUE:** yes (chat works; task panel does not)

---

## OQ-12: HERMES `[task]` syntax parsing — not implemented in channels/index.php

**WHEN:** 20260415100644
**WHO:** AUGGIE
**AREA:** channels/index.php L170-185 vs PRD 02 HERMES Routing Rules
**STATUS:** open

**QUESTION:**
PRD 02 extensively documents HERMES routing: when a human posts `[task] who: CURSOR what: fix header`, HERMES intercepts the message, creates a task queue entry, and routes it to the agent. `channels/index.php` saves raw message text to the database with no parsing, no HERMES call, no task creation. There is no HERMES implementation anywhere visible in this controller. Does HERMES exist as code? Where does `[task]` syntax get parsed?

**WHY THIS MATTERS:**
The entire task assignment workflow described in PRD 02 depends on HERMES. If it does not exist, agents never receive tasks via chat commands. The `[task]` command is decorative.

**OPTIONS:**
1. Implement HERMES message parser in `DialogMvpService::createDialogMessage()` or as post-save hook
2. Build standalone `api/hermes/route.php` called after message save
3. Document HERMES as future phase; mark PRD 02 HERMES sections as pending

**CAN WORK CONTINUE:** yes (raw messages work; task routing does not)

---

## OQ-13: THOTH hardcoded color exception not documented in PRD 02

**WHEN:** 20260415100644
**WHO:** AUGGIE
**AREA:** channels/index.php L256-258 vs PRD 02 Thread Color section
**STATUS:** open

**QUESTION:**
`channels/index.php` hardcodes actor_id 26 (THOTH) to `#8B0000` background and `#FFD700` text, bypassing thread-based colors entirely. PRD 02's color system defines no exceptions for any actor. Is actor_id 26 definitively THOTH? Is this bypass intentional doctrine? Should it be in the PRD as a named exception?

**WHY THIS MATTERS:**
If this exception is intentional, PRD 02 is incomplete — it must document the "monitoring agent override" rule. If THOTH's actor_id ever changes, the hardcoded `26` becomes a silent bug.

**OPTIONS:**
1. Add THOTH exception to PRD 02 color section; make actor_id a named constant
2. Move THOTH color to `lupo_agent_colors` table (the optional override system PRD 02 already defines)
3. Document as implementation detail only

**CAN WORK CONTINUE:** yes

---

## OQ-14: `DialogMvpService` vs global functions — which is the canonical PHP API?

**WHEN:** 20260415100644
**WHO:** AUGGIE
**AREA:** channels/index.php vs PRD 02 Implementation Patterns section
**STATUS:** open

**QUESTION:**
PRD 02's Implementation Patterns section documents global PHP functions: `create_thread()`, `insert_message()`, `render_messages()`, `get_next_thread_color()`. `channels/index.php` uses none of these — it uses `DialogMvpService::createDialogThread()`, `DialogMvpService::createDialogMessage()`, `DialogMvpService::ensureChannelMembership()`, `DialogMvpService::getChannelMembers()`, `DialogMvpService::getAllChannels()`. These are different signatures and abstractions. Which is the canonical layer?

**WHY THIS MATTERS:**
Agents writing new channel-related code need to know which API to call. PRD 02 points them at global functions that the main controller does not use. `DialogMvpService` has no PRD documentation.

**OPTIONS:**
1. Update PRD 02 to document `DialogMvpService` as the canonical service class; deprecate global function examples
2. Keep global functions as the PRD-facing API; wrap them inside `DialogMvpService`
3. Document both layers with explicit usage scope

**CAN WORK CONTINUE:** yes

---

## OQ-15: `message_type = 'text'` conflicts with PRD 02 message type enum

**WHEN:** 20260415100644
**WHO:** AUGGIE
**AREA:** channels/index.php L175 vs PRD 02 render_messages() + Anti-Patterns
**STATUS:** open

**QUESTION:**
`channels/index.php` creates all human-typed messages with `message_type = 'text'` (L175). PRD 02's `render_messages()` function and CSS class logic only handle: `stdout`, `stderr`, `task`, `system`. The type `'text'` falls into the `default` branch (`chat-stdout` class). Is `'text'` an intentional type for human messages that PRD 02 omits, or should human messages be `'text'` and the PRD's enum updated?

**WHY THIS MATTERS:**
Any code that filters or renders messages by type (e.g., showing only stderr alerts) will behave incorrectly if human messages are type `'text'` but the enum only lists `stdout`/`stderr`/`task`/`system`.

**OPTIONS:**
1. Add `'text'` as an official type to PRD 02 enum; define its rendering rule
2. Change human message creation to use `'stdout'` or a new `'human'` type
3. Document default fallback behavior explicitly in PRD 02

**CAN WORK CONTINUE:** yes

---

## OQ-16: Hardcoded `'666666'` color in `DialogMvpService::createDialogMessage()`

**WHEN:** 20260415100644
**WHO:** AUGGIE
**AREA:** channels/index.php L175
**STATUS:** open

**QUESTION:**
`DialogMvpService::createDialogMessage($db, $thread_id, $actor_id, $text, 'text', $to_actor, '666666', null)` passes `'666666'` as the 7th argument. This appears to be an author color override. PRD 02 says all message colors come from the thread's assigned color palette. What is this parameter? Does it override the thread color for this message? Is `null` as the 8th argument a background color? Neither parameter is documented in PRD 02.

**WHY THIS MATTERS:**
`'666666'` hardcoded in the controller means every human-typed message uses grey text regardless of thread color assignment. This directly contradicts PRD 02's thread-based color doctrine.

**OPTIONS:**
1. Pass `null` for both color params to allow thread color to apply; document parameter intent in PRD 02
2. Add `createDialogMessage()` signature to PRD 02 with parameter names
3. Remove color params from the call site; move color resolution entirely into the service

**CAN WORK CONTINUE:** yes (works visually, but violates color doctrine)

---

## OQ-17: Initial message load limit: 200 in code vs 1000 in PRD 02

**WHEN:** 20260415100644
**WHO:** AUGGIE
**AREA:** channels/index.php L198 vs PRD 02 Frontend Performance section
**STATUS:** open

**QUESTION:**
`channels/index.php` loads the last 200 messages on initial page render (`LIMIT 200`). PRD 02 Frontend Performance states "Chat history in memory: Last 1000 messages maximum." Which is correct? 200 messages loads faster; 1000 may stress the DOM before the reload guard can fire at the 500-line threshold.

**WHY THIS MATTERS:**
If the initial load is 200 and the DOM reload threshold is 500, the page reloads after only 300 polled messages. If initial load is 1000, the page reloads immediately on first poll (since `domLineCount` starts at 1000, already above threshold 500).

**OPTIONS:**
1. Keep 200 initial load; update PRD 02 to reflect this
2. Set initial load to match DOM_RELOAD_THRESHOLD - buffer (e.g., 450)
3. Start `domLineCount` at 0 regardless of server-rendered count; only count polled lines

**CAN WORK CONTINUE:** yes

---

## OQ-18: `layers.js` dependency — undocumented; PRD 02 forbids framework polyfills

**WHEN:** 20260415100644
**WHO:** AUGGIE
**AREA:** channels/index.php L298 vs PRD 02 "No Framework Assumptions"
**STATUS:** open

**QUESTION:**
`channels/index.php` loads `<script src="<?= $base ?>/includes/js/layers.js"></script>` before the inline polling JS. PRD 02 says the polling loop MUST be plain JavaScript with no framework polyfills. What is `layers.js`? Is it a utility library, a framework shim, or part of the admin layout? Is it required for the channel page or is it loaded by the admin layout template unconditionally?

**WHY THIS MATTERS:**
If `layers.js` provides polyfills or overrides `fetch`, the transport model behavior may differ across environments. PRD 02's "no framework" rule is a constitutional constraint.

**OPTIONS:**
1. Document `layers.js` in PRD 02 as the approved utility layer
2. Confirm it is admin-layout-only boilerplate and does not affect channel JS
3. Remove the explicit script tag if the admin layout already loads it

**CAN WORK CONTINUE:** yes

---

## OQ-19: Direct message (`to_actor_id != 0`) rendering not specified in PRD 02

**WHEN:** 20260415100644
**WHO:** AUGGIE
**AREA:** channels/index.php L270-276 (compose form) vs PRD 02 HERMES Routing
**STATUS:** open

**QUESTION:**
The compose form has a `<select name="to_actor_id">` allowing directed messages. PRD 02 documents `@AGENT message` syntax and states directed messages go to specific monitoring agents (THOTH, VISH), but does not define how `to_actor_id != 0` messages are rendered differently in the feed. The current template renders all messages identically regardless of `to_actor_id`. Is a directed message supposed to look different? Who can see it?

**WHY THIS MATTERS:**
If directed messages have no visual distinction, the `to_actor_id` field is invisible to users — they cannot tell if a message was directed or broadcast. This also affects the "no private DMs between agents" constraint.

**OPTIONS:**
1. Add `[@ RECIPIENT]` tag to message rendering when `to_actor_id != 0`
2. Document `to_actor_id` as DB-only metadata (for audit log), not for visual rendering
3. Add filtering capability: show only messages directed to me / sent by me

**CAN WORK CONTINUE:** yes

---

## OQ-20: Thread = today's date only — no historical thread navigation in UI

**WHEN:** 20260415100644
**WHO:** AUGGIE
**AREA:** channels/index.php L109 (`$today_thread_key = gmdate('Y-m-d')`)
**STATUS:** open

**QUESTION:**
`channels/index.php` always creates/uses today's date as the thread_key with no UI to view or switch historical threads. PRD 02 documents a thread-based color system and a tab navigation system with thread selection, but the current controller locks you to today. Is date-per-day thread auto-creation the intended UX? If so, how do users review yesterday's conversation?

**WHY THIS MATTERS:**
The date-per-thread model accumulates one thread per channel per day automatically. With no navigation UI, threads older than today are unreachable from the main channel view.

**OPTIONS:**
1. Add thread date picker or "Previous" nav to channel sidebar; read thread_key from `$_GET`
2. Keep today-only design; add read-only archive view at `/channels/?channel=X&thread=2026-04-14`
3. Document date-per-thread as the deliberate model in PRD 02; spec the historical access pattern

**CAN WORK CONTINUE:** yes

---

## OQ-21: `lupo_agent_colors` table — specified in PRD 02 but not used anywhere

**WHEN:** 20260415100644
**WHO:** AUGGIE
**AREA:** PRD 02 "Agent-Specific Color Override (Optional)" section vs channels/index.php
**STATUS:** open

**QUESTION:**
PRD 02 defines the `lupo_agent_colors` table schema as the mechanism for per-agent color overrides. `channels/index.php` never queries this table. Agent colors (other than the THOTH hardcode) are determined solely by thread assignment. Is `lupo_agent_colors` a planned future table (not yet in the install schema) or does it exist but is simply not wired into the controller?

**WHY THIS MATTERS:**
If the table does not exist in `install_new_lupopedia.sql`, agents that try to write to it will get a fatal DB error. If it does exist but is never read, the optional override system is permanently inactive.

**OPTIONS:**
1. Confirm table existence in install schema; wire into message rendering as true override
2. Remove the table spec from PRD 02 until the feature is ready
3. Keep as documented-but-inactive optional feature with explicit "not yet implemented" note in PRD 02

**CAN WORK CONTINUE:** yes

---

## OQ-22: `channel_id` column on `lupo_dialog_messages` — denormalization not documented

**WHEN:** 20260415100644
**WHO:** AUGGIE
**AREA:** channels/index.php L197 (`WHERE m.channel_id = :cid`) vs PRD 02 DB schemas
**STATUS:** open

**QUESTION:**
The initial message query filters `WHERE m.channel_id = :cid` directly on `lupo_dialog_messages`. In a normalized schema, messages belong to threads, and threads belong to channels — `channel_id` on messages is a denormalization. PRD 02's `lupo_dialog_messages` schema excerpt (in `insert_message()`) does not include a `channel_id` column. Does the actual table have `channel_id` as a denormalized column? Is this intentional for query performance, or is the controller query wrong?

**WHY THIS MATTERS:**
If `channel_id` is on messages, the channel-level feed query is fast (no JOIN to threads). If it is not, the query in `channels/index.php` fails silently (or throws an error) and messages are never loaded.

**OPTIONS:**
1. Add `channel_id` to PRD 02's message schema with a note on the intentional denormalization
2. Rewrite the query to JOIN through `dialog_threads` to get channel scope
3. Confirm install schema and reconcile with PRD 02

**CAN WORK CONTINUE:** depends on DB — if column is missing, page is broken

---

## OQ-23: `?memory_key=` URL parameter still uses deprecated v3 field name

**WHEN:** 20260415101800
**WHO:** AUGGIE
**AREA:** content/index.php L70-72
**STATUS:** open

**QUESTION:**
`content/index.php` reads `$_GET['memory_key']` and passes it into `content_show_by_slug()` as a widget hint. In v4.1.0 the field is `memory_toon`, not `memory_key`. Any external link or agent script that passes `?memory_key=` will work; anyone building new integrations against PRD 16 who uses `?memory_toon=` will silently get no widget context. Which query param name is the canonical public URL API?

**WHY THIS MATTERS:**
Public URLs are harder to change than code. If `?memory_key=` becomes the de-facto standard in bookmarks, agent scripts, and external links, renaming it later will break existing references.

**OPTIONS:**
1. Accept both `memory_key` and `memory_toon` params; prefer `memory_toon`; log deprecation warning for `memory_key`
2. Rename to `memory_toon` only; update all callers
3. Document `?memory_key=` as the stable public API; note that the internal field name differs

**CAN WORK CONTINUE:** yes (works with old name; new integrations use wrong name)

---

## OQ-24: Two parallel slug-lookup functions — `content_lookup_by_slug` vs `content_get_by_slug`

**WHEN:** 20260415101800
**WHO:** AUGGIE
**AREA:** includes/modules/content/content-controller.php
**STATUS:** open

**QUESTION:**
`content_show_by_slug()` calls `content_lookup_by_slug()`. `content_handle_slug()` calls `content_get_by_slug()`. Both resolve a slug against `lupo_contents`, but they are different functions with different names. Are they identical in behavior, or do they differ in columns fetched, visibility filtering, or error handling? Which is the canonical lookup and why do both exist?

**WHY THIS MATTERS:**
Duplicate slug resolvers drift silently. If one adds a visibility check, a caching layer, or a new column fetch and the other doesn't, content displayed via different entry points will be inconsistent. Agents or new controllers that pick the wrong function get different data.

**OPTIONS:**
1. Consolidate into one `content_lookup_by_slug()` canonical function; delete `content_get_by_slug()`
2. Document the distinction explicitly (e.g., `content_get_by_slug()` is legacy, `content_lookup_by_slug()` is canonical)
3. Keep both but ensure they delegate to a shared private function

**CAN WORK CONTINUE:** yes

---

## OQ-25: Two parallel content-show paths — `content_show_by_slug` vs `content_handle_slug`

**WHEN:** 20260415101800
**WHO:** AUGGIE
**AREA:** includes/modules/content/content-controller.php
**STATUS:** open

**QUESTION:**
Both `content_show_by_slug()` and `content_handle_slug()` render a content page from a slug. `content/index.php` calls `content_show_by_slug()`; `module-loader.php` also calls `content_show_by_slug()`. When does `content_handle_slug()` get called? It has a richer rendering pipeline (remote content fetch, section extraction, semantic context, prev/next nav) that `content_show_by_slug()` appears to lack or implement differently. If `content_handle_slug()` is the full implementation, why does `content_show_by_slug()` exist as a separate function?

**WHY THIS MATTERS:**
Content served via `content/index.php` may be missing features (prev/next, section anchors, semantic context) that content served through other routes gets. Users see different page quality for the same slug depending on entry point.

**OPTIONS:**
1. Make `content_show_by_slug()` a thin wrapper that delegates to `content_handle_slug()`
2. Delete `content_show_by_slug()` and update all callers to use `content_handle_slug()`
3. Document the intentional feature split; update PRD 73 to reflect two rendering tiers

**CAN WORK CONTINUE:** yes (both work; content quality differs by entry point)


---

## OQ-26: `lupo_collection_tab_map` schema missing from PRD 73

**WHEN:** 20260415101800
**WHO:** AUGGIE
**AREA:** docs/prd/73_collections_navigation.md §Table Details vs content-controller.php L1071-1077
**STATUS:** open

**QUESTION:**
PRD 73 lists `lupo_collection_tab_map` in the tables summary ("Maps tabs to entities") but provides no column specification. `content-controller.php` queries it with columns `collection_tab_id`, `item_id`, `item_type`, and `sort_order`. None of these are documented. PRD 73 only shows full schema for `lupo_collections`, `lupo_collection_tabs`, `lupo_paths`, and `lupo_folder_map`. The three bridge tables (`lupo_collection_links`, `lupo_collection_map`, `lupo_collection_tab_map`, `lupo_collection_tab_paths`) have no column specs at all.

**WHY THIS MATTERS:**
Without a documented schema, any developer adding a new item to a tab must reverse-engineer the table from live DB or guess from the code. If the install SQL diverges from what the code expects, the JOIN at L1073 silently returns no rows.

**OPTIONS:**
1. Add full column specs for all four bridge tables to PRD 73
2. Extract bridge table schemas from the install SQL and add them to PRD 73
3. Move bridge table specs to a dedicated "join tables" section with install SQL excerpts

**CAN WORK CONTINUE:** yes (if DB matches code assumptions)

---

## OQ-27: `default_collection_id` column on `lupo_contents` — cross-namespace dependency undocumented

**WHEN:** 20260415101800
**WHO:** AUGGIE
**AREA:** content-controller.php L303-304 vs PRD 73 Cross-Namespace Dependencies table
**STATUS:** open

**QUESTION:**
`content_show_by_slug()` reads `$content['default_collection_id']` from the `lupo_contents` row and uses it to resolve collection tabs for the page chrome. This means `lupo_contents` has a `default_collection_id` column that couples it to `lupo_collections`. Neither PRD 73's cross-namespace dependency table nor (presumably) PRD 06 documents this column. Who sets `default_collection_id` on a content row? Is it set at import time, manually by an editor, or auto-assigned?

**WHY THIS MATTERS:**
Content with `default_collection_id = NULL` (or 0) gets no collection chrome — no tabs, no nav context. If this is the default for all imported content, the collection system is invisible on most pages.

**OPTIONS:**
1. Add `default_collection_id` to PRD 06 (lupo_contents schema) with documentation of who sets it and when
2. Add it to PRD 73's cross-namespace dependency table with a note on the coupling direction
3. Replace the column approach with a lookup: derive collection from `lupo_collection_tab_map` at render time

**CAN WORK CONTINUE:** yes (falls back to no chrome when null/0)

---

## OQ-28: `lupo_resolve_collection_tabs_for_chrome(0)` — behavior when collection_id is 0 undefined

**WHEN:** 20260415101800
**WHO:** AUGGIE
**AREA:** content-controller.php (library index and content_show_by_slug fallback)
**STATUS:** open

**QUESTION:**
Both `content_show_library_index()` and `content_show_by_slug()` (when `default_collection_id` is absent) call `lupo_resolve_collection_tabs_for_chrome(0)` — passing `0` as the collection_id. PRD 73 defines collections by positive BIGINT IDs. What does `0` mean to this function? Does it return a global/default collection, return empty arrays, or throw? The behavior of the entire tab chrome for pages without an explicit collection assignment depends on this.

**WHY THIS MATTERS:**
If `0` returns empty tabs, the library index and slug-routed content with no collection both show a chromeless page. If `0` returns a "catch-all" global collection, that behavior is undocumented and fragile.

**OPTIONS:**
1. Document `0` as "no collection — return empty tabs" and update PRD 73
2. Define a reserved `collection_id = 0` as a system-level global nav collection
3. Change callers to pass `null` and handle the null case explicitly in the function

**CAN WORK CONTINUE:** yes

---

## OQ-29: PRD 73 §8 sync strategy is entirely aspirational — no implementation marker

**WHEN:** 20260415101800
**WHO:** AUGGIE
**AREA:** docs/prd/73_collections_navigation.md §8 (Sync Strategy)
**STATUS:** open

**QUESTION:**
PRD 73 §8 ("Sync Strategy: Human UI Collections ↔ AI Memory Collections") is a full multi-section specification for `CollectionMemorySyncService`, `AICollectionDiscoveryService`, `CollectionApprovalService`, and `MemoryEdgeAnalyzer`. None of these classes exist in the codebase. The section is presented at the same level as the working table schemas with no "NOT YET IMPLEMENTED" or "PHASE 2+" marker.

**WHY THIS MATTERS:**
Any agent reading PRD 73 as implementation guidance will spend time looking for service classes that do not exist. The aspirational spec blends with the working spec, making it impossible to know what is live.

**OPTIONS:**
1. Add a clear `> **Status: NOT IMPLEMENTED — Phase 2 target**` callout at the top of §8
2. Move §8 to a separate `73b_collections_ai_sync.md` PRD marked as `status: planned`
3. Add `atoms_toon` constraints that lock §8 as read-only until Phase 2 is approved

**CAN WORK CONTINUE:** yes (working tables are unaffected)

---

## OQ-30: `lupo_rolls` table — no schema, no usage, no definition beyond its name

**WHEN:** 20260415101800
**WHO:** AUGGIE
**AREA:** docs/prd/73_collections_navigation.md §Tables in This Namespace
**STATUS:** open

**QUESTION:**
PRD 73 lists `lupo_rolls` ("Roll-based content organization — alternative organization method") in the table summary but provides zero column specifications, no indexes, no usage pattern, no code example, and no explanation of what a "roll" is. The content controller does not reference it. Does this table exist in the install schema? What problem does it solve that `lupo_collections` does not?

**WHY THIS MATTERS:**
A table name in a canonical PRD creates the expectation that it exists and is used. If `lupo_rolls` does not exist, the table summary misleads implementors. If it does exist, any content it holds is invisible to the entire controller layer.

**OPTIONS:**
1. Add full column spec and at least one usage example to PRD 73
2. Remove `lupo_rolls` from PRD 73 until it has a specification
3. Mark it explicitly as "reserved / future" with a single-line description

**CAN WORK CONTINUE:** yes

---

## OQ-31: `item_count` cached column — no increment/decrement logic documented; triggers prohibited

**WHEN:** 20260415101800
**WHO:** AUGGIE
**AREA:** docs/prd/73_collections_navigation.md — `lupo_collections.item_count`; `lupo_folder_map.item_count`
**STATUS:** open

**QUESTION:**
Both `lupo_collections` and `lupo_folder_map` have an `item_count INT DEFAULT 0` described as "Cached item count." Lupopedia's constitutional rules prohibit DB triggers and stored procedures. No application-layer code in `content-controller.php` increments or decrements `item_count`. Who maintains this cache?

**WHY THIS MATTERS:**
A stale `item_count` produces incorrect UI badges (e.g., "0 items" on a full collection). Any code that skips rendering an empty collection based on `item_count = 0` will silently hide real content.

**OPTIONS:**
1. Document the application-layer hook (e.g., in `CollectionService::addItem()`) that must update `item_count`
2. Remove `item_count` from both schemas; compute live with `SELECT COUNT(*)` at render time
3. Add a nightly batch recalculation script and document it in PRD 73

**CAN WORK CONTINUE:** yes (if nothing reads item_count for rendering decisions)

---

## OQ-32: `lupo_paths` table — routing table or navigation chrome? Conflicts with module-loader regex

**WHEN:** 20260415101800
**WHO:** AUGGIE
**AREA:** docs/prd/73_collections_navigation.md §lupo_paths vs includes/modules/module-loader.php
**STATUS:** open

**QUESTION:**
PRD 73 defines `lupo_paths` with `path_pattern VARCHAR(512)` described as "URL pattern or route." This implies it is a DB-driven routing table. But `module-loader.php` uses hardcoded `preg_match` expressions to route slugs. The content controller never queries `lupo_paths` for routing decisions. Is `lupo_paths` meant to replace or augment the module-loader regex routing, or is it purely navigation chrome?

**WHY THIS MATTERS:**
If `lupo_paths` is a routing table, the module-loader's hardcoded regex is the wrong layer and `lupo_paths` should drive dispatch. If it is navigation chrome, the word "route" in `path_type` and `path_pattern` is misleading.

**OPTIONS:**
1. Clarify PRD 73: `lupo_paths` is navigation metadata only; rename `path_pattern` to `display_url` or `nav_href`
2. Wire `lupo_paths` into module-loader as the canonical routing registry; remove hardcoded regex
3. Document the current split: module-loader owns routing; `lupo_paths` owns nav link registry

**CAN WORK CONTINUE:** yes

---

## OQ-33: `content/index.php` header is v2 format — not migrated to v4.1.0

**WHEN:** 20260415101800
**WHO:** AUGGIE
**AREA:** content/index.php L2-22
**STATUS:** open

**QUESTION:**
`content/index.php` has a PHP block-comment header using `header_format_version: 2` with non-v4.1.0 fields: `channel_id: 42` (numeric, not `channel_key`), `author:` block, `delegation_chain: "cursor:root"`, `purpose:`, `tags:`. It was correctly excluded from the root .md migration (it is a .php file), but it remains out of compliance with PRD 16's v4.1.0 spec for PHP file headers.

**WHY THIS MATTERS:**
The universal validator will flag this file. Agents reading the header for context get wrong channel (numeric id vs string key) and non-canonical field names.

**OPTIONS:**
1. Migrate the PHP block-comment header to v4.1.0 format following PRD 16 PHP header conventions
2. Add `content/index.php` to the validator's exclusion list until migrated
3. Document PHP header migration as a separate task from .md migration

**CAN WORK CONTINUE:** yes

---

## OQ-34: `error_reporting(E_ALL)` and `display_errors = 1` hardcoded in content/index.php

**WHEN:** 20260415101800
**WHO:** AUGGIE
**AREA:** content/index.php L32-34
**STATUS:** open

**QUESTION:**
`content/index.php` hardcodes `error_reporting(E_ALL)`, `ini_set('display_errors', '1')`, and `ini_set('display_startup_errors', '1')` unconditionally. On a production shared-hosting deployment (the stated purpose of this file), they leak PHP error messages, stack traces, and internal path information to unauthenticated visitors.

**WHY THIS MATTERS:**
Information disclosure via displayed errors is a direct security vulnerability. On shared hosting, this can expose DB credentials (if a DB error is thrown), file system paths, and class names useful for further attacks.

**OPTIONS:**
1. Guard behind `defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG` before setting these ini values
2. Remove all three lines; rely on server-level `php.ini` for error display control
3. Replace with a Lupopedia-standard debug guard used consistently across all front controllers

**CAN WORK CONTINUE:** yes (security risk, not a functional breakage)

---

<!-- ============================================================ -->
<!-- mood_vector doctrine review — OQ-35 through OQ-40              -->
<!-- Canonical refs: docs/doctrine/counting_in_light.md,    -->
<!-- docs/doctrine/mood_vector_doctrine.md,                    -->
<!-- docs/doctrine/rose_doctrine.md                         -->
<!-- ============================================================ -->

## OQ-35: CORRECTION OF OQ-16 — `'666666'` is the mood_vector semantic neutral default, not a display color

**WHEN:** 20260415102457
**WHO:** AUGGIE
**AREA:** docs/doctrine/counting_in_light.md + mood_vector_doctrine.md vs channels/index.php L175
**STATUS:** open — supersedes OQ-16

**QUESTION:**
OQ-16 incorrectly frames `'666666'` in `DialogMvpService::createDialogMessage(..., '666666', null)` as "an author color override contradicting thread-based color doctrine." This is a category error. `mood_vector` and thread display colors are **entirely separate systems**. `'666666'` is the canonical `mood_vector` default token meaning "neutral coordination" (Counting-in-Light: R=0x66 low strife, G=0x66 low harmony, B=0x66 low memory depth). Passing `'666666'` as the 7th argument is correct per the MOOD_VECTOR_DOCTRINE. The 8th `null` is `mood_label` — acceptable for human messages that are not ROSE-origin commentary. The actual ambiguity is that PRD 02 documents neither the `mood_vector` nor the `mood_label` parameters of `createDialogMessage()`, leaving agents unable to distinguish display-color from semantic-vector concerns.

**WHY THIS MATTERS:**
Any agent who reads OQ-16 and acts on it will try to remove or replace `'666666'` on the grounds that it violates display-color doctrine — breaking the semantic mood transport for every human-origin message. The correction must be on record.

**OPTIONS:**
1. Close OQ-16 as "resolved — misidentification"; add `mood_vector` / `mood_label` param docs to PRD 02
2. Add a note to PRD 02's channel section explicitly distinguishing thread display colors from `mood_vector`

**CAN WORK CONTINUE:** yes

---

## OQ-36: PRD 02 documents neither `mood_vector` nor `mood_label` parameters of `createDialogMessage()`

**WHEN:** 20260415102457
**WHO:** AUGGIE
**AREA:** PRD 02 Implementation Patterns section vs MOOD_VECTOR_DOCTRINE
**STATUS:** open

**QUESTION:**
`createDialogMessage()` takes `mood_vector` and `mood_label` as its 7th and 8th parameters. PRD 02's Implementation Patterns section documents the function only as a message-storage primitive with no mention of these two fields. Any agent implementing a new caller — a HERMES router, an agent wrapper script, a synthetic dialog emitter — has no PRD guidance on what value to pass for `mood_vector`, when to provide a `mood_label`, or that these fields exist at all. The Counting-in-Light doctrine lives in `docs/doctrine/` with no cross-reference from PRD 02.

**WHY THIS MATTERS:**
New callers will either omit the params (causing DB errors if the schema requires them) or pass arbitrary hex strings as if they were display colors, polluting the semantic mood record with non-canonical values.

**OPTIONS:**
1. Add a `mood_vector` / `mood_label` parameter table to PRD 02's `createDialogMessage()` section; link to Counting-in-Light doctrine
2. Add a brief "Mood Transport" section to PRD 02 explaining that `mood_vector` is semantic (not display) and that `'666666'` is the correct default for non-ROSE human messages
3. Move the function documentation to a separate service-layer PRD that owns `DialogMvpService`

**CAN WORK CONTINUE:** yes (current callers are correct; new callers are at risk)

---

## OQ-37: How does `mood_vector` surface in channel message rendering — displayed or purely metadata?

**WHEN:** 20260415102457
**WHO:** AUGGIE
**AREA:** channels/index.php message render loop vs MOOD_VECTOR_DOCTRINE "Implementation surfaces"
**STATUS:** open

**QUESTION:**
MOOD_VECTOR_DOCTRINE lists `api/v1/dialog/metrics.php` as a consumer of `mood_vector` for telemetry, and `Caduceus::computeCurrents()` as the routing-influence consumer. But `channels/index.php` renders the chat feed — does it read `mood_vector` from the poll response and use it for anything (CSS class, icon, sidebar indicator), or does the chat UI completely ignore it? The poll response likely includes a `msg_mood` field; if it is silently discarded in the render loop, the semantic layer is present in the DB but invisible in the primary human-facing interface.

**WHY THIS MATTERS:**
If `mood_vector` is never surfaced in the channel UI, it exists purely for machine consumers (CADUCEUS, HERMES, metrics). That is a valid design choice — but it means the "mood" system is invisible to human operators reading the channel, including WOLFIE trying to read room state at a glance.

**OPTIONS:**
1. Document explicitly in PRD 02 that the channel UI does NOT render `mood_vector` (machine-only field)
2. Add a subtle `mood_label` tooltip or border tint to messages where `mood_vector` is non-neutral
3. Add a mood indicator column to the channel sidebar summary

**CAN WORK CONTINUE:** yes

---

## OQ-38: ROSE cannot do DB writes but produces mood-bearing output — who stores it?

**WHEN:** 20260415102457
**WHO:** AUGGIE
**AREA:** rose_doctrine.md §5 (no_db_writes) + §4a (mood_RGB emission)
**STATUS:** open

**QUESTION:**
ROSE doctrine §5 is explicit: `no_db_writes`. ROSE doctrine §4a says ROSE is the persona most likely to emit long-form interpretive commentary with `mood_RGB` and `mood_label`. But `mood_vector` and `mood_label` are stored columns in `lupo_dialog_messages`. If ROSE cannot write to the DB, how does a ROSE-origin message — with its `mood_RGB` vector and `mood_label` — get stored? Does ROSE post to the chat send endpoint (which writes on its behalf), does HERMES intercept and store, or does a human operator paste ROSE output into the channel manually? The pipeline `ROSE → ATHENA → HEPHAESTUS → LILITH` describes doctrine decisions, not message storage.

**WHY THIS MATTERS:**
If ROSE posts via the chat endpoint, then `no_db_writes` means "ROSE does not call DB functions directly" — not "ROSE output is never persisted." That distinction needs to be documented. Without it, any agent interpreting ROSE's constraint as "ROSE cannot post messages" will suppress ROSE output entirely.

**OPTIONS:**
1. Clarify in ROSE doctrine that `no_db_writes` = no direct DB calls; posting via chat API endpoint is permitted
2. Document the canonical storage path for ROSE-origin messages (e.g., ROSE → chat endpoint → `DialogMvpService::createDialogMessage()`)
3. Add a ROSE-specific `actor_id` to `lupo_actors` so ROSE messages are identifiable by actor rather than by mood vector alone

**CAN WORK CONTINUE:** yes (ROSE output currently manual/ad hoc)

---

## OQ-39: Fallback `'666666'` vs explicit neutral — indistinguishable in storage

**WHEN:** 20260415102457
**WHO:** AUGGIE
**AREA:** mood_vector_doctrine.md §Limitations vs channels/index.php L175
**STATUS:** open

**QUESTION:**
MOOD_VECTOR_DOCTRINE explicitly notes as a known limitation: "Fallback `666666` vs explicit neutral is not distinguished in storage alone." `channels/index.php` always passes `'666666'` for every human-typed message. This means every human message in `lupo_dialog_messages` has `mood_vector = '666666'`. Future code that queries "which messages were explicitly marked neutral coordination?" cannot distinguish those from "which messages were never assigned a mood at all." Should unset mood be stored as `NULL` (meaning "no mood was assessed") and `'666666'` be reserved for messages where neutral was an explicit editorial choice?

**WHY THIS MATTERS:**
Metrics, CADUCEUS routing, and ROSE retrospectives that aggregate `mood_vector` values will be polluted by `'666666'` from all human messages regardless of actual semantic state. A channel with 200 human messages will look uniformly neutral even during a crisis thread.

**OPTIONS:**
1. Store `NULL` for `mood_vector` on human messages from the channel UI; let consumers treat `NULL` as "unassessed, assume neutral"
2. Keep `'666666'` as default but add a boolean `mood_explicit TINYINT DEFAULT 0` column to distinguish set vs defaulted
3. Accept the limitation as a known constraint; document in MOOD_VECTOR_DOCTRINE that `'666666'` from human channel messages is always a default, never an assertion

**CAN WORK CONTINUE:** yes

---

## OQ-40: Canonical mood_vector token enforcement — no validator blocks non-canonical hex

**WHEN:** 20260415102457
**WHO:** AUGGIE
**AREA:** mood_vector_doctrine.md §Core rules + §Limitations
**STATUS:** open

**QUESTION:**
MOOD_VECTOR_DOCTRINE names five canonical decision-safe tokens: `FF0000`, `00FF00`, `666666`, `B1B1B1`, `88FF88`. It also acknowledges as a current limitation: "No validator enforces canonical-token-only emission for all artifacts." The doctrine distinguishes canonical tokens (authoritative for gates/directives/audits) from the continuous vector (numeric influence only). But any caller can write `mood_vector = 'ABCDEF'` and the system will store it, potentially routing on it or using it for audit gates as if it were a canonical token. Is there a planned enforcement layer? MOOD_VECTOR_DOCTRINE says "future work" but provides no timeline or owner.

**WHY THIS MATTERS:**
Without enforcement, a misconfigured agent could emit `mood_vector = 'FF0000'` (critical error / maximum strife) on routine messages, distorting CADUCEUS currents and HERMES routing. Conversely, a valid blocking state could be masked by an incorrect `'666666'` default, silently neutralizing the signal.

**OPTIONS:**
1. Add `mood_vector` validation to `api/dialog/send-message.php` — accept any hex but flag non-canonical values with a WARN log
2. Add `mood_vector` validation to `DialogMvpService::createDialogMessage()` — reject non-hex or add a `mood_vector_is_canonical` boolean column
3. Build a canonical token registry in `lupo_mood_registry` (already exists in schema per archived tables) and enforce via lookup at write time

**CAN WORK CONTINUE:** yes (no enforcement is the current state; risk is known)

---

## OQ-41: PRD truth resolution for OQ-10 — polling scope is thread-scoped, not channel-wide

**WHEN:** 20260415102813
**WHO:** CURSOR
**AREA:** PRD 02 (`/api/chat/messages` contract + SQL sample with `AND thread_id = :thread_id`)
**STATUS:** answered_from_prd

**QUESTION:**
Can OQ-10 be answered from PRD truth? Yes. PRD 02 models polling as thread-scoped. The documented request includes `thread_key`, and the SQL sample explicitly filters by thread. The canonical intended behavior is thread-scoped polling.

**WHY THIS MATTERS:**
This removes ambiguity for client builders and avoids mixed channel/thread behavior across implementations.

**FOLLOW-UP QUESTIONS (implementation/database/design):**
1. **Implementation:** Should legacy channel-wide endpoint behavior be retained behind an explicit `scope=channel` flag for back-compat?
2. **Database:** Is there a guaranteed index path for thread+time scans in the active install schema for `lupo_dialog_messages`?
3. **Design:** Should PRD 02 add one explicit sentence: "Default poll scope is current thread only"?

**CAN WORK CONTINUE:** yes

---

## OQ-42: PRD truth resolution for OQ-17 — baseline feed retention target is 1000 messages

**WHEN:** 20260415102813
**WHO:** CURSOR
**AREA:** PRD 02 performance and chat history target ("Last 1000 messages maximum")
**STATUS:** answered_from_prd

**QUESTION:**
Can OQ-17 be answered from PRD truth? Yes. PRD 02 explicitly states a 1000-message history target. Current 200-limit implementations should be treated as temporary runtime divergence until aligned.

**WHY THIS MATTERS:**
This gives one canonical number for UI and API behavior and prevents silent truncation expectations from drifting.

**FOLLOW-UP QUESTIONS (implementation/database/design):**
1. **Implementation:** Should initial load be raised directly to 1000, or switched to cursor paging with first window 200 and "load older" until 1000?
2. **Database:** Are existing message-time indexes sufficient for 1000-row fetches under 2-second polling load?
3. **Design:** Should PRD 02 define whether 1000 is per thread or per channel aggregate when rendering unified views?

**CAN WORK CONTINUE:** yes

---

## OQ-43: PRD truth resolution for OQ-21 — `lupo_agent_colors` is optional override, not required runtime path

**WHEN:** 20260415102813
**WHO:** CURSOR
**AREA:** PRD 02 color doctrine section (`lupo_agent_colors` as optional override alongside thread colors)
**STATUS:** answered_from_prd

**QUESTION:**
Can OQ-21 be answered from PRD truth? Yes. PRD 02 states thread-based colors are primary and `lupo_agent_colors` is an optional override. Non-use in current code is PRD-compatible.

**WHY THIS MATTERS:**
This reframes OQ-21 from "missing implementation bug" to "optional feature not currently activated."

**FOLLOW-UP QUESTIONS (implementation/database/design):**
1. **Implementation:** Do we want a feature flag to enable per-agent color override for selected channels?
2. **Database:** Should seed include baseline rows in `lupo_agent_colors`, or leave table empty until opt-in?
3. **Design:** Should operator UI expose a "thread colors only vs agent override" toggle?

**CAN WORK CONTINUE:** yes

---

## OQ-44: PRD truth resolution for OQ-23 — canonical key is `memory_toon`; `memory_key` is legacy alias only

**WHEN:** 20260415102813
**WHO:** CURSOR
**AREA:** PRD 16 v4.1.0 field rename (`memory_key` -> `memory_toon`)
**STATUS:** answered_from_prd

**QUESTION:**
Can OQ-23 be answered from PRD truth? Yes. PRD 16 v4.1.0 makes `memory_toon` canonical and treats `memory_key` as deprecated/legacy compatibility input. New URLs and new client integrations should use `memory_toon`.

**WHY THIS MATTERS:**
This prevents more deprecated surface area from being introduced during 4.1.0 migration work.

**FOLLOW-UP QUESTIONS (implementation/database/design):**
1. **Implementation:** Should `?memory_key=` remain accepted with a deprecation warning until 4.1.x end?
2. **Database:** Are all read/write paths now fully migrated to `memory_toon` column naming where applicable?
3. **Design:** Should docs include one migration table mapping old query params to new ones for agents?

**CAN WORK CONTINUE:** yes

---

## OQ-45: PRD truth resolution for OQ-26 — `lupo_collection_tab_map` is defined in PRD 73 core namespace table set

**WHEN:** 20260415102813
**WHO:** CURSOR
**AREA:** PRD 73 namespace table inventory and table specification blocks
**STATUS:** answered_from_prd

**QUESTION:**
Can OQ-26 be answered from PRD truth? Yes. PRD 73 explicitly includes `lupo_collection_tab_map` in the core table set and gives it a defined primary key (`tab_map_id`) and purpose (mapping tabs to content/entities).

**WHY THIS MATTERS:**
This confirms the schema is intentionally part of collections navigation, not an accidental implementation-only artifact.

**FOLLOW-UP QUESTIONS (implementation/database/design):**
1. **Implementation:** Which active service/controller path is canonical for writing `lupo_collection_tab_map` rows?
2. **Database:** Does install schema currently include all PRD 73 tab-map indexes exactly as documented?
3. **Design:** Should PRD 73 add one end-to-end "tab creation -> tab map write -> render" flow to remove ambiguity?

**CAN WORK CONTINUE:** yes

---

## OQ-46: PRD truth clarification for OQ-29 — PRD 73 sync section is planned architecture, not baseline runtime requirement

**WHEN:** 20260415102813
**WHO:** CURSOR
**AREA:** PRD 73 sync strategy section (future-facing service/API planning)
**STATUS:** answered_from_prd_with_followup

**QUESTION:**
Can OQ-29 be answered from PRD truth? Partially yes. PRD 73 presents sync material as planned architecture and phased future work, while core collection tables and navigation behavior are baseline. So lack of current sync implementation is not a baseline contract violation.

**WHY THIS MATTERS:**
This separates immediate-required behavior from roadmap behavior and reduces false "broken implementation" flags.

**FOLLOW-UP QUESTIONS (implementation/database/design):**
1. **Implementation:** Should PRD 73 label each sync subsection with explicit phase tags (`planned`, `experimental`, `active`)?
2. **Database:** Are any new sync-only tables required, or should sync use existing memory-edge tables only?
3. **Design:** Should sync APIs be moved into a companion PRD to keep 73 focused on current collections runtime?

**CAN WORK CONTINUE:** yes
