# Open Questions — Lupopedia 4.0.99

## Rules (READ FIRST)

- APPEND ONLY. Do NOT rewrite or delete prior entries.
- Format: WHEN / FILE / TYPE / WHAT / WHY / IDEAL / BLOCKING

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
