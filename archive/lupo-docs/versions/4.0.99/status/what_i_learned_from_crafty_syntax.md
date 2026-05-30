---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: documentation
  when_updated: "20260414120000"
  file_path_from_root: "lupo-docs/versions/4.0.99/status/what_i_learned_from_crafty_syntax.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.99/status/what_i_learned_from_crafty_syntax.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "lupo-memory/development/canonical/1026/04/what-i-learned-from-crafty-syntax.toon"
  artifact_type: documentation
  artifact_kind: analysis
  thread_id: "channels-discussions"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: "What I Learned from Crafty Syntax — Legacy Code Analysis & Migration Doctrine"
  status: "active"
  parent_pk_id: "2"
  summary: "Structured analysis of Crafty Syntax reference code: fallback patterns, AJAX/XMLHTTP architecture, table migrations, and what Lupopedia should preserve, adapt, or replace."
  module: null
  dialog_transcript: "0/development/channels-discussions"
---

# What I Learned from Crafty Syntax

**Author**: Actor 116 (Claude Code)
**Date**: 20260413
**Status**: ACTIVE

---

## 1. Overview

### 1.1 Source material reviewed

The following files in `craftysyntax-reference/` were read in full:

| File | Lines | Role |
|------|-------|------|
| `xmlhttp.php` | 355 | Unified AJAX endpoint — both operator and visitor paths |
| `admin_chat_xmlhttp.php` | 316 | Admin chat frame: XMLHTTP message renderer + polling loop |
| `admin_chat_bot.php` | 933+ | Admin compose frame: form, tab logic, image polling |

Additional reference files identified (not read in full but confirmed present):
`admin_chat_refresh.php`, `user_chat_refresh.php`, `user_chat_xmlhttp.php`,
`is_flush.php`, `is_xmlhttp.php`, `admin_connect.php`, `functions.php`,
`admin_image.php`, and 80+ other PHP files in `craftysyntax-reference/`.

The migration SQL was read from:
`lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql` (1916 lines)

Doctrine documents read:
- `lupo-docs/doctrine/migrations/crafty_syntax_ancestral_intent.md`
- `lupo-docs/doctrine/migrations/livehelp_migrations_readme.md`
- `lupo-docs/doctrine/migrations/generated/README.md`

### 1.2 Functional areas studied

- Dual-mode AJAX/refresh architecture (progressive enhancement)
- Message delivery pipeline: insert, fetch, render
- Typing indicator system (LAYER / writediv)
- Image pixel polling for change detection
- Operator tab management (multi-chat UI)
- Channel/people enumeration (peoplestring mechanism)
- Session identity and operator state tracking
- Timestamp collision avoidance
- Content sanitization patterns
- All 34 legacy table mappings into Lupopedia

### 1.3 Why these areas matter to Lupopedia

Lupopedia's dialog subsystem (channels/index.php, DialogMvpService, post-message.php)
is a direct architectural descendant of these files. Every PRD 02 design decision —
the one-column feed, the AJAX compose path, the channel concept, the actor model —
has roots in what Crafty Syntax built and validated over 20 years.

Reading the source is the only way to understand why certain Lupopedia patterns exist,
what they replaced, and where they diverged intentionally.

---

## 2. What Crafty Syntax Did Well

### 2.1 The UNTRUSTED input doctrine

Crafty Syntax used a global `$UNTRUSTED` array throughout all PHP files (visible in
`xmlhttp.php` line 52, `admin_chat_xmlhttp.php` line 46-60, `admin_chat_bot.php` line 66).
All user-controlled input was gated through this array before use.

This is sound security thinking that predates common PHP best practices by years.
Lupopedia inherits this pattern (channels/index.php uses `$UNTRUSTED['get']` and
`$UNTRUSTED['server']`).

### 2.2 Timestamp as the universal sort key and collision guard

`livehelp_messages.timeof` used `date("YmdHis")` — a packed YYYYMMDDHHIISS integer
stored as a varchar. Every query used `timeof > $lasttimeof` to fetch only new messages.

This is the direct ancestor of Lupopedia's BIGINT timestamp doctrine. The key difference:
Lupopedia promotes this to a DEFINE with explicit UTC semantics and a dedicated class
(`timestamp_ymdhis`). Crafty used server local time; Lupopedia enforces UTC.

**Timestamp collision detection loop** (`xmlhttp.php` lines 122-128):
```php
$query = "SELECT timeof FROM livehelp_messages WHERE timeof='$timeof'";
$rs = $mydatabase->query($query);
while($rs->numrows() != 0){
    if(function_exists('sleep')){ sleep(1); $timeof = date("YmdHis"); } else { $timeof++; }
    $rs = $mydatabase->query($query);
}
```

This loop deliberately retries to avoid timestamp collisions. The comment says:
"a performance issue but actually done on purpose to discourage people making hosted
solutions with multiple chats all using the same system."

Whatever the stated motivation, this is a real concurrency guard. Lupopedia's IdGenerator
addresses the same problem differently: 18-digit IDs with 4-digit random suffix, making
collisions statistically negligible without polling loops.

### 2.3 Delta-fetch architecture (HTMLtimeof + LAYERtimeof)

`admin_chat_xmlhttp.php` maintains two independent timestamp cursors:
- `HTMLtimeof`: tracks the highest timestamp seen for rendered chat messages
- `LAYERtimeof`: tracks the highest timestamp seen for the typing overlay

The polling call sends both to the server:
```
whattodo=messages&HTML=HTMLtimeof&LAYER=LAYERtimeof
```

The server returns ONLY messages newer than each cursor. This is efficient: no full
re-render, no diff computation, append-only DOM updates.

Lupopedia's channels/index.php currently does a FULL page reload via redirect on POST
and fetches all messages on every load. The Crafty delta-fetch approach is strictly more
efficient and should be studied when implementing Lupopedia's live-feed polling.

### 2.4 Image pixel change detection (admin_chat_bot.php lines 220-298)

Crafty used a polling mechanism separate from the XMLHTTP message stream to detect
whether the user list had changed. It loaded a 1-pixel GIF from `admin_image.php`:

```javascript
var u = 'admin_image.php?randu=' + randu + '&what=usercheck&showvisitors='
        + defaultshowvisitors + '&peoplestring_test=' + peoplestring;
cscontrol.src = u;
cscontrol.onload = lookatimage;
```

The server returned either a 55-pixel-wide image (meaning "reload the user list") or
a different size (meaning "no change"). The client checked `cscontrol.width == 55`.

This is a clever binary signal: no JSON parsing, no text response handling. Pure image
width as a change-detection signal. It works because browsers always fire `onload` for
images, even in old IE, making it universally compatible.

Lupopedia's equivalent is the channel member sidebar, refreshed only on channel change.
This technique is obsolete for Lupopedia but reveals the thinking behind it.

### 2.5 The peoplestring fingerprint

The `peoplestring` pattern (`admin_chat_bot.php` lines 148-185, `xmlhttp.php` lines 55-85)
encoded the current state of all active users as a single string:

```php
$peoplestring = "users";
while($visitor = ...){
    $visitor_string = $visitor['sessionid'] . $visitor['status'];
    $user = "_" . preg_replace('/[^A-Za-z0-9]/', '', $visitor_string);
    $peoplestring .= $user;
}
```

The client held a copy of this string. On each poll, the server recomputed it and
compared. If different (width == 55), the list was refreshed.

This is a cheap, stateless change detection fingerprint. No DB query comparison needed
by the client — string comparison on the server side only. Effectively a poor-man's
ETag for a live user list.

### 2.6 Dual typing preview modes

The `previewsetting` dropdown had 4 options:
1. Full text preview ("see what they are typing in real time")
2. "is typing..." indicator only
3. No typing indicator
4. Default (forced if `show_typing != "Y"`)

This user-configurable typing visibility is thoughtful UX. The operator could choose their
level of intrusion. Setting 3 = no typing preview = faster for operators who found it
distracting.

Lupopedia PRD 02 has a simpler model (task parser shows intent but no real-time typing).
The Crafty typing system reveals that typing awareness was a valued feature.

### 2.7 Operator tab system for multi-chat

`admin_chat_bot.php` rendered color-coded tabs for each active operator channel,
linked by `channelsplit` (format: `channel__saidto`). Adjacent tabs on the same
channel were visually linked. Each tab had its own chat frame.

This solved a real operator workflow problem: managing multiple simultaneous chats.
Lupopedia's actor model (actor_channels, channel memberships) provides the
infrastructure for this but PRD 02 does not yet have a multi-tab UI.

### 2.8 safeSubmit: double-submit prevention

`admin_chat_bot.php` lines 514-533:
```javascript
function safeSubmit(f) {
    document.chatter.alt_what.value = "send";
    for (i=1; i<f.elements.length; i++) {
        if (f.elements[i].type == 'submit') { f.elements[i].disabled = true; }
    }
    f.submit();
    blockmessage = 1;
    safeSubmit = blockIt;  // replace self with no-op
    return false;
}
function blockIt(f) { return false; }
```

After submit, `safeSubmit` replaces itself with `blockIt`. This prevents double-submit
at the function level rather than just disabling the button. Lupopedia's AJAX handler
should implement equivalent protection.

---

## 3. Fallbacks and Resilience Patterns

### 3.1 The transport negotiation model (corrected)

**Prior interpretation (incomplete):** the fallback ladder was described as a static
sequence of files that the client tried in order. This is wrong.

**Correct model:** the transport system is an active, session-start negotiation chain.
The client probes for capabilities at session startup using detection scripts:
- `is_xmlhttp.php` — probes for XMLHttpRequest support
- `is_flush.php` — probes for server-flush (streaming) support

The order of probing and the modes considered are governed by `$CSLH_Config['chatmode']`
from `livehelp_config`. This is a *configured chain*, not a hardcoded sequence. An
operator can explicitly restrict or extend the mode order via config.

**One-way promotion:** once XMLHTTP capability is proven via a successful probe, the
session is promoted to XMLHTTP mode and locked. The session does NOT bounce back to
refresh mode during normal operation. This lock-in is written to the DB:

```php
if(!(empty($UNTRUSTED['setchattype']))){
    $query = "UPDATE livehelp_users SET chattype='xmlhttp' WHERE sessionid='...'";
}
```

**Session lock-in via `chattype`:** the `livehelp_users.chattype` column records the
proven transport mode for the session. This prevents "mode bouncing" — all subsequent
requests from that session use the locked mode. The modern (2023) codebase hardcodes
`chattype = "xmlhttp"` on every request, effectively treating XMLHTTP as the
unconditional baseline, but preserving the infrastructure for the full negotiation path.

**No runtime mode switching:** once a session is locked to a transport mode, it stays
there. Runtime bouncing between XMLHTTP and refresh would corrupt the client's cursor
state (HTMLtimeof, LAYERtimeof) and produce duplicate or dropped messages.

The paired file names in the reference directory confirm the two endpoints exist:

| XMLHTTP path | Refresh path |
|---|---|
| `admin_chat_xmlhttp.php` | `admin_chat_refresh.php` |
| `user_chat_xmlhttp.php` | `user_chat_refresh.php` |

Config keys that govern negotiation:
- `is_flush.php` / `$CSLH_Config['use_flush']` — flush/streaming capability probe
- `is_xmlhttp.php` — XMLHTTP capability probe
- `admin_refresh` — refresh interval; also acts as mode indicator (non-zero = refresh path active)

The `livehelp_users.chattype` column stored which mode the client used. In
`admin_chat_xmlhttp.php` line 63-65:
```php
if(!(empty($UNTRUSTED['setchattype']))){
    $query = "UPDATE livehelp_users SET chattype='xmlhttp' WHERE sessionid='...'";
}
```

And in `admin_chat_bot.php` line 44-46:
```php
if($chattype!="xmlhttp"){
    $chattype = "xmlhttp";
}
```

The modern versions (2023) forced `chattype = "xmlhttp"`, effectively retiring refresh
mode. But the infrastructure for both paths remained.

### 3.2 Config-gated refresh intervals

The `admin_refresh` config key (values 10, 15, 20, 25, 30, 35 seconds) controlled
the page-refresh polling interval. Both `xmlhttp.php` and `admin_chat_bot.php`
checked this value:

```php
if($CSLH_Config['admin_refresh']==10){$defaultshowvisitors=0; }
if($CSLH_Config['admin_refresh']==15){$defaultshowvisitors=0; }
// ... etc
```

When `admin_refresh` was set to any interval value, `defaultshowvisitors` was set to 0,
hiding visitors from the XMLHTTP people list. This suggests that in refresh mode, the
visitor list was rendered server-side on each page load, while in XMLHTTP mode it was
fetched asynchronously.

The `refreshrate` config key (separate from `admin_refresh`) controlled the visitor-side
polling frequency.

### 3.3 The flush pathway

`is_flush.php` and `$CSLH_Config['use_flush']` indicate a third delivery mode:
server-sent output flushing. `admin_chat_xmlhttp.php` line 73:

```php
if(($CSLH_Config['use_flush'] == "no") || ($UNTRUSTED['offset'] != "")){ $timeof = $oldtime; }
```

When flush was disabled, the timeof window expanded back 30 minutes to ensure messages
were not missed. When flush was enabled, only messages since page load were shown.

Flush mode used PHP's `ob_flush()` / `flush()` to push content incrementally to the
browser — a predecessor to Server-Sent Events. This required specific server support and
was unreliable on shared hosting. The config allowed it to be disabled cleanly.

### 3.4 The sendTypingFallback pattern (admin_chat_bot.php lines 427-430)

This is the most interesting progressive enhancement pattern in the codebase.
The typing indicator used `fetch()` API with an explicit fallback:

```javascript
function sendTypingRequest(endpoint, params){
    var body = buildFormBody(params);
    if (window.fetch){
        fetch(endpoint, { method: 'POST', ... }).catch(function(){
            sendTypingFallback(endpoint, body);
        });
    } else {
        sendTypingFallback(endpoint, body);
    }
}

function sendTypingFallback(endpoint, body){
    var randu = Math.round(Math.random()*99999);
    cscontrol.src = endpoint + '?' + body + '&legacy=1&rand=' + randu;
}
```

If `window.fetch` exists, use it. If the fetch fails (network error, browser support),
fall back to loading the URL as an image src.

This is textbook progressive enhancement: the feature degrades to a no-op image request
(harmless to the server, no visible output to the user) rather than failing with an
error. Lupopedia's current AJAX removes the fallback per debug requirements (correct
during diagnosis), but the Crafty pattern is the right long-term approach.

### 3.5 The EXIT signal in message polling

`xmlhttp.php` lines 332-340:
```php
if (empty($UNTRUSTED['op'])){
    $sqlquery = "SELECT user_id FROM livehelp_users WHERE user_id=".intval($myid)." AND status='chat'";
    $alive = $mydatabase->query($sqlquery);
    if($alive->numrows() == 0){
        $aftertime = date("YmdHis");
        $string = "messages[0] = new Array(); messages[0][0]=$aftertime; messages[0][1]=$jsrn; messages[0][2]=\"EXIT\"; messages[0][3]=\"\"; messages[0][4]=\"\";";
        print $string;
        exit;
    }
}
```

If the visitor's chat session had ended (no longer status='chat'), the server returned
a synthetic EXIT message. The client parsed this and redirected or closed the chat.

This is a push-from-server approach to session management: instead of the client needing
to know when to stop polling, the server told the client to exit. Lupopedia's equivalent
would be a sentinel message_type (e.g., `'system'` with `message_text = '[SESSION_END]'`)
that clients watch for.

### 3.6 Degraded mode with the ping action

`xmlhttp.php` lines 287-290:
```php
if($UNTRUSTED['whattodo'] == "ping"){
    echo 'OK';
    exit;
}
```

The `ping` action was a health-check endpoint that returned exactly `OK`. It had no
side effects. This allowed the client to verify the server was responding before
attempting more complex operations. Simple, correct, reusable.

---

## 4. Innovations Worth Preserving

### 4.1 The dual-cursor delta-fetch (HTMLtimeof + LAYERtimeof)

The two independent timestamp cursors — one for visible chat content, one for the
transient typing layer — allowed the server to return two different classes of data
in a single round-trip response without mixing them. Each cursor advanced independently.

The cursor used in every poll request is a 14-digit UTC value (`YYYYMMDDHHIISS`) — the
`after_time` parameter. The server query is:
```sql
SELECT ... FROM livehelp_messages WHERE timeof > '$after_time' ORDER BY timeof ASC
```

This is not a message ID cursor. It is a timestamp cursor. The client advances the
cursor on each successful poll by storing the highest `timeof` received in the response.

**Correction to prior analysis**: `last_message_id` was cited as an alternative or
preferred cursor. This is wrong for Lupopedia's PRD 02 model. The correct cursor is
`after_ymdhis` (a 14-digit UTC BIGINT), consistent with the timestamp doctrine. Message
IDs may be non-sequential (IdGenerator uses CSPRNG suffix); `after_ymdhis` is always
monotonically increasing and sortable without an index lookup by ID range.

**Lupopedia application**: When implementing live polling for channels/index.php, the
AJAX request MUST send `after_ymdhis=<14-digit-UTC>`. The server returns only messages
with `created_ymdhis > after_ymdhis`, sorted ascending. The client appends each line
to the bottom of the feed (oldest-at-top, newest-at-bottom) and advances its cursor.
This produces a continuous feed with no full page reload. The cursor is a JS variable
initialized from the highest `created_ymdhis` in the initial page render and optionally
persisted to `lupo_dialog_read_log`.

### 4.2 The whattodo dispatch pattern

`xmlhttp.php` is a single endpoint that handles multiple operations via a `whattodo`
parameter: `peoplestring`, `donetyping`, `visitors`, `ping`, `wantstochat`, `messages`.

Each operation is a focused, stateless read or write. This is essentially a manual
router before PHP frameworks existed. It keeps network round-trips low (one endpoint,
many capabilities) while keeping the code inspectable.

**Lupopedia application**: `lupo-api/dialog/post-message.php` is already a focused
endpoint. The pattern suggests adding a corresponding `lupo-api/dialog/fetch-messages.php`
for the polling path, rather than routing through channels/index.php.

### 4.3 People/state fingerprinting before polling

Before making expensive queries, Crafty compared a fingerprint string (`peoplestring`)
of the current user list with the server-computed value. Only if they differed was the
full user list refreshed.

**Lupopedia application**: For the channel member sidebar, the client could send a hash
of the current members list. If unchanged, the server returns a lightweight `{changed:false}`.
This avoids rendering and injecting a new member list on every poll.

### 4.4 The wantstochat handshake (lines 302-325)

Before connecting a visitor to a chat session, the server returned one of three text
tokens: `TIMEOUT`, `CONNECTED`, or `LIGHTS-ARE-ON-BUT-NOBODY-IS-HOME`. The client
polled this endpoint repeatedly until the state changed.

This is a clean state machine with a human-readable protocol. The literal string
`LIGHTS-ARE-ON-BUT-NOBODY-IS-HOME` is memorable and unambiguous: no operator is
watching this channel right now.

**Lupopedia application**: The Lupopedia runtime actor loop (`RuntimeActorLoopService`)
processes messages asynchronously. A similar state endpoint could report:
`PROCESSING`, `QUEUED`, `DONE`, or `NO_AGENT` — tokens the AJAX client could poll
to update the UI without a full page reload.

### 4.5 The `refreshes > 15` self-reload guard (admin_chat_bot.php lines 199-200)

```javascript
refreshes++;
if(refreshes>15){ shouldireload(); }
```

After 15 XMLHTTP polling cycles without a page reload, the `shouldireload()` function
was called. This prevented memory leaks from long-running JS pages (a real concern in
pre-V8 browsers) and ensured the page state was periodically reset.

**Lupopedia application**: For a live-updating channels/index.php feed, implement a
session reload limit (e.g., after 500 appended lines, reload the page). This keeps
DOM size bounded without sacrificing continuity.

### 4.6 The chattype column as client capability flag

`livehelp_users.chattype` recorded whether a given session had confirmed XMLHttpRequest
support. The server could then emit the correct response format (XMLHTTP vs refresh)
based on what the client could receive.

**Lupopedia application**: The actor/session model can store similar capability metadata.
When a client connects, it could send a feature-flags header (`supports-sse: true`,
`supports-fetch: true`). The server stores these in `lupo_sessions` and adapts its
response format accordingly.

---

## 5. What Must Change for Lupopedia

### 5.1 Remove framesets

Crafty's admin UI was built entirely with HTML framesets. The main admin layout was a
frameset with frames named `rooms`, `bottomof`, `users`, `connection`, `chat`.
JavaScript frequently accessed parent frames: `window.parent.rooms.document.mine.*`,
`window.parent.bottomof.shouldifocus()`.

Framesets are removed from HTML5. Lupopedia uses a single-page layout with CSS grid
(`channel-live-wrapper` in channels/index.php). All cross-frame communication patterns
from Crafty are irrelevant and must not be replicated.

### 5.2 No raw, non-prepared SQL

Crafty SQL was universally non-prepared. Examples from xmlhttp.php:
```php
$sqlquery = "SELECT user_id,onchannel FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
$query = "INSERT INTO livehelp_messages (message,channel,timeof,saidfrom,saidto) VALUES ('".filter_sql($comment)."',".intval($channel).",'$timeof',...)";
```

The `filter_sql()` and `cslh_escape()` functions provided ad-hoc SQL injection
mitigation. This was the state of the art in 2003-2007 PHP, but it is fundamentally
insufficient. String escaping can be bypassed. Lupopedia uses PDO with named prepared
statements exclusively — no exceptions.

### 5.3 Actor/agent-aware architecture

Crafty Syntax knew only two entity types: operators (isoperator='Y') and visitors
(isoperator='N'). Lupopedia has actors (humans and agents), where agents can be
AI actors with specific roles (THOTH as ALERT monitor, ROSE as mood semantic engine,
specialist agents as task workers).

Every Crafty pattern that says "is this an operator?" must be re-interpreted in
Lupopedia as "what is this actor's role and channel membership?"

Key translation:
- `isoperator='Y'` → actor exists in `lupo_actors` with `is_agent=0` (human) or specific agent role
- `isoperator='N'` → anonymous session (not in `lupo_actors` at all; exists only in `lupo_sessions`)
- `onchannel` → `lupo_actor_channels.channel_id` (explicit membership)

### 5.4 Channel/thread semantics

Crafty's `channel` column in `livehelp_messages` was a simple integer channel ID
corresponding to a chat session. It was a temporary routing key, not a durable concept.
Messages were **deleted after session end** (confirmed by migration SQL comment at
line 534: "crafty did not store any of the messages after the chat ended so this table
is empty unless there was active chats").

Lupopedia promotes channels to first-class durable entities with:
- `lupo_channels` (channel_id, channel_key, channel_name, visibility_status)
- `lupo_dialog_threads` (thread per date/topic within a channel)
- `lupo_dialog_messages` (permanent record, is_deleted soft-delete only)

No Lupopedia messages are ever hard-deleted on session end. The entire history is
preserved in threads.

### 5.5 Timestamp doctrine alignment

Crafty used server local time (`date("YmdHis")` without UTC). In shared hosting
environments, this meant timestamps were timezone-dependent. Lupopedia mandates UTC
BIGINT YYYYMMDDHHIISS via `timestamp_ymdhis::now()`. Any Crafty timestamp imported
via migration SQL must be treated as approximate.

The import SQL handles this by using `DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S')`
for created/updated timestamps, and converting legacy UNIX timestamps with
`DATE_FORMAT(FROM_UNIXTIME(u.lastaction), '%Y%m%d%H%i%S')`.

### 5.6 ID doctrine: no MAX+1, no sequential integers

Crafty used MySQL AUTO_INCREMENT for all primary keys. Lupopedia mandates
`IdGenerator::generate()` — an 18-digit string combining YYYYMMDDHHIISS + 4-digit
CSPRNG suffix. No AUTO_INCREMENT, no MAX+1, no bare `id` column names.

The import SQL handles this by mapping legacy integer PKs to Lupopedia-format IDs.
For example, `livehelp_users.user_id` becomes `(10000 + user_id)` for actor_id,
placing all migrated humans in the human actor ID range (>=10000).

### 5.7 Config moved out of livehelp_config

`livehelp_config` was a single-row table with ~50 flat columns. The migration converts
this to a JSON object inserted into `lupo_modules.config_json WHERE module_id = 1`.

This removes the need for schema changes when adding new config values. But it also
means that any Lupopedia code reading config must query through the modules table
and parse JSON, not query individual columns. Runtime PHP must not assume a flat config
table exists.

---

## 6. Table Mapping and Migration Understanding

All evidence drawn from `import_from_old_crafty_syntax.sql` and migration doctrine docs.
Per-table migration detail files are located at:
`lupo-docs/database/lupopedia/tables/migrations/` (see `livehelp_migrations_readme.md`).

### 6.1 Core chat tables

| Legacy table | Lupopedia target | Notes |
|---|---|---|
| `livehelp_messages` | `lupo_dialog_messages` | Volatile in Crafty (deleted post-session). Migration: deprecated + comment only. No data imported because table was empty at import time by design. |
| `livehelp_transcripts` | `lupo_dialog_threads` + `lupo_dialog_messages` | Each transcript row becomes one thread + one message. The `transcript` blob (HTML string) becomes `message_body`. This is a lossy import: intra-session individual turns are not separable from the blob. |

Critical nuance: `livehelp_messages` was a **live-session buffer**, not an archive.
Messages were inserted during an active chat and read by polling. After the chat ended,
they were discarded. `livehelp_transcripts` was the archive: a post-session HTML dump.

Lupopedia inverts this: `lupo_dialog_messages` is permanent. Every message written
during a session stays. `lupo_dialog_threads` organizes them into durable conversations.

### 6.2 User and operator tables

| Legacy table | Lupopedia target | Notes |
|---|---|---|
| `livehelp_users` | `lupo_auth_users` + `lupo_actors` | Operators (isoperator='Y') become auth_users with actor_id = (10000 + user_id). Anonymous visitors become sessions only — NOT inserted into lupo_actors. |
| `livehelp_operator_channels` | `lupo_channels` | Active chat routing assignments map to channel memberships. |
| `livehelp_operator_departments` | `lupo_actor_departments` | Department assignments preserved. actor_id remapped to (10000 + user_id). |
| `livehelp_operator_history` | `lupo_audit_log` | Login/logout events become audit records. |

The `(10000 + user_id)` remapping is the ID range doctrine in action: agent IDs
0-9999 are reserved for system/AI actors (seeded). Human actors start at 10000.

### 6.3 Structural and config tables

| Legacy table | Lupopedia target | Notes |
|---|---|---|
| `livehelp_config` | `lupo_modules.config_json` (module_id=1) | Entire flat config row serialized to JSON. ~50 keys preserved. |
| `livehelp_departments` | `lupo_departments` + `lupo_department_metadata` | Core fields in departments table. Presentation/behavior fields in department_metadata as JSON. |
| `livehelp_websites` | `lupo_federation_nodes` | Each Crafty website becomes a federation node. node_id 0 is reserved for lupopedia.com. |
| `livehelp_sessions` | DROPPED | Crafty sessions were PHP session data stored in a table. Lupopedia uses lupo_sessions for actor session tracking — a different purpose and schema. |

### 6.4 CRM and lead tables

| Legacy table | Lupopedia target | Notes |
|---|---|---|
| `livehelp_leads` | `lupo_crm_leads` | Direct migration. date_entered preserved as created_ymdhis. |
| `livehelp_emails` | `lupo_crm_lead_messages` | Email records mapped to CRM messages. All assigned to lead_id=1 (broadcast lead). |
| `livehelp_emailque` | NOT migrated | Email queue out of scope for this migration. Table deprecated with comment. |
| `livehelp_leavemessage` | `lupo_crafty_syntax_leave_message` | Preserved in a Crafty-namespaced table. Fields with no Lupopedia equivalent (phone, name, ip_address, user_agent) populated with NULL. |

### 6.5 Auto-invite and layer tables

| Legacy table | Lupopedia target | Notes |
|---|---|---|
| `livehelp_autoinvite` | `lupo_crafty_syntax_auto_invite` | Y/N flags converted to TINYINT 1/0. user_id remapped to (10000 + user_id). |
| `livehelp_layerinvites` | `lupo_crafty_syntax_layer_invites` | Layer invite images and clickcounts preserved. user_id remapped. |

### 6.6 Analytics tables

| Legacy table | Lupopedia target | Notes |
|---|---|---|
| `livehelp_visit_track` | Lupopedia visit system | Path/page tracking preserved in federated visit schema. |
| `livehelp_identity_daily` | DROPPED | Rolled-up daily aggregates. Lupopedia computes analytics from raw events. |
| `livehelp_identity_monthly` | DROPPED | Same — analytics are recomputed, not imported from aggregates. |
| `livehelp_keywords_daily` | DROPPED | Same pattern. |
| `livehelp_keywords_monthly` | DROPPED | Same pattern. |
| `livehelp_paths_monthly` | Lupopedia path analytics | Path data preserved with timestamp conversion. |

### 6.7 The migration idempotency pattern

The import SQL is designed for repeatable execution:
- `ALTER TABLE ... ENGINE=InnoDB` — safe to re-run
- `TRUNCATE target; INSERT ...` — full re-import on re-run
- `INSERT ... ON DUPLICATE KEY UPDATE` — upsert safety on constraint collisions
- `INSERT IGNORE` — skip rather than fail on duplicate keys

All `created_ymdhis` and `updated_ymdhis` in imported rows default to
`DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S')` — the moment of migration,
not the original Crafty timestamp. Original timestamps are preserved where
a direct source column exists (e.g., `starttime`, `dateof`).

---

## 7. Specific Findings from Required Files

### 7.1 xmlhttp.php

**What it does**:
A single PHP endpoint serving as the AJAX handler for both operators (`op=yes`)
and visitors. It dispatches on `$UNTRUSTED['whattodo']` to one of six actions:

| Action | Purpose | Returns |
|---|---|---|
| `peoplestring` | Build fingerprint of active user states | Text string: `users_<session><status>...` |
| `donetyping` | Insert message into livehelp_messages | GIF image (1px transparent) |
| `visitors` | Build operator visitor tracking list | HTML string |
| `ping` | Health check | Text: `OK` |
| `wantstochat` | Check if operator is on channel | Text: `TIMEOUT`, `CONNECTED`, or `LIGHTS-ARE-ON-BUT-NOBODY-IS-HOME` |
| `messages` | Fetch new messages since last cursor | JS array assignments string |

**Fallback behavior**:
No explicit fallback within xmlhttp.php itself — it is the fallback's target. The
fallback logic lives in the calling frame (admin_chat_bot.php / user chat page).
If xmlhttp.php is unreachable, the calling frame either degrades to page refresh
(refresh path) or shows an error. The `ping` action allows clients to probe availability
before attempting more complex calls.

**Design pattern revealed**:
The `donetyping` action returns a GIF image rather than JSON or text. This is because
in the 2003-era AJAX model, returning an image was the safest cross-browser way to
trigger an `onload` callback without browser security restrictions on XMLHttpRequest.
The image pixel is a 1x1 transparent GIF — a response body format compatible with every
browser that existed.

The return value semantics are: the fact that the image loaded means success. No body
parsing needed. This is still a valid pattern when XMLHttpRequest is unavailable.

**Lupopedia lessons**:
- Single-purpose dispatch endpoint: adopt `whattodo`-style routing for `lupo-api/dialog/`
- The `ping` action is worth adding to `post-message.php` or a dedicated health endpoint
- Message inserts should return a minimal success signal, not a full rendered response
- The `EXIT` signal (synthetic message with `message_type="EXIT"`) is worth porting as
  a server-initiated session termination mechanism in Lupopedia agent responses

**Non-prepared SQL present** (line 40, 66, 93, 122, 130, 134, 151, 175, 191, 198, 211, 306, 317, 333, 344):
All queries use string interpolation. This is the primary technical debt in this file.
Every query must be rewritten with PDO named parameters in any Lupopedia equivalent.

### 7.2 admin_chat_bot.php

**What it does**:
The operator compose frame. Handles message sending (whattodo=send), renders the
operator tab bar (active chat sessions), provides the message composition form,
and runs two parallel polling loops via JavaScript.

**Fallback behavior**:
Two polling systems run concurrently and serve as mutual fallbacks:

1. **XMLHTTP message stream** (via `admin_chat_xmlhttp.php`): real-time message
   delivery at 2.1-second intervals.
2. **Image pixel polling** (via `admin_image.php`): change detection for the user
   list at 4-7 second intervals. If the image signals a change, `window.parent.users.refreshit()`
   reloads the users frame.

If `update_xmlhttp()` fails or stops responding, the user list frame still refreshes
via the image poll. If the image poll stops, messages still arrive via XMLHTTP.
The two systems are independent and each can sustain basic functionality without the
other.

The `shouldireload()` function adds a third layer: if the operator is typing and
the reload guard fires (after N refreshes), it waits for them to stop typing before
reloading. This prevents data loss on network instability.

**Design pattern revealed — fetch() with image fallback** (lines 410-430):
```javascript
function sendTypingRequest(endpoint, params){
    if (window.fetch){
        fetch(endpoint, {...}).catch(function(){
            sendTypingFallback(endpoint, body);
        });
    } else {
        sendTypingFallback(endpoint, body);
    }
}
function sendTypingFallback(endpoint, body){
    cscontrol.src = endpoint + '?' + body + '&legacy=1&rand=' + randu;
}
```

This is modern progressive enhancement: try `fetch()` first, fall back to image
src request on failure or absence. The image src request has no visible output for
the user but fires the correct server-side action. The `&legacy=1` flag tells the
server it arrived via the fallback path.

Lupopedia's channels/index.php should implement this same pattern once the debug
phase (console.error only) is complete. The fallback path should be the form's
native submit, not another fetch.

**Lupopedia lessons**:
- The tab system maps directly to Lupopedia's actor_channels model
- The `channelsplit` format (`channel__saidto`) is the precursor to Lupopedia's
  `(channel_id, to_actor_id)` addressing
- The `safeSubmit` → `blockIt` self-replacement is the cleanest double-submit prevention
  seen in the codebase; worth porting directly
- The `refreshes > 15` reload guard is worth implementing as a DOM size limit in
  Lupopedia's live feed

**Non-prepared SQL present**: Line 94, 99, 107, 125, 133, 178, 572.

### 7.3 admin_chat_xmlhttp.php

**What it does**:
The XMLHTTP message display frame. Renders the initial chat history as a server-side
`<span id="currentchat">` block, then starts a JavaScript polling loop that appends
new messages asynchronously at 2.1-second intervals.

**Fallback behavior**:
The initial page load populates the chat history synchronously via `showmessages()`.
Even if JavaScript is disabled or XMLHttpRequest fails, the operator sees the chat
history as of page load. No messages after page load would arrive, but the operator
could reload the page manually to see new messages — a graceful degradation to
manual-refresh behavior.

The `cleartonow` parameter controls what the initial cursor values are:
- `cleartonow=1`: HTMLtimeof/LAYERtimeof initialized to current time (skip history, show only new)
- Default: both initialized to 0 (show all messages in the `showmessages()` window)

The `$timeof - 2` trick (line 79): when loading the "clear to now" state after a full
refresh, the timeof is set 2 seconds before the last message's timestamp. This ensures
that messages sent in the 2-second window before the reload are not lost.

**Design pattern revealed — eval-based response parsing**:
```javascript
function ExecRes(textstring){
    var messages = new Array();
    textstring = textstring + " ok =1; ";
    try { eval(textstring); } catch(error4){}
    for (var i=0; i<messages.length; i++){
        res_timeof  = messages[i][0];
        res_jsrn    = messages[i][1];
        res_typeof  = messages[i][2];
        res_message = messages[i][3];
        ...
    }
}
```

The server returns a JavaScript array literal string like:
```
messages[0] = new Array();
messages[0][0] = 20260413190000;
messages[0][1] = 5;
messages[0][2] = "HTML";
messages[0][3] = "unescape(%5B19%3A00%3A00%5D+%5BWOLFIE%5D+hello)";
```

The client `eval()`s this string to populate the `messages` array. This is the
pre-JSON response format that Crafty used for its entire lifetime. It is equivalent
to JSON but expressed as executable JavaScript.

The `res_typeof` field distinguishes between two data streams:
- `"HTML"`: a rendered chat line to append to `#currentchat`
- `"LAYER"`: a typing preview string to display in the floating overlay
- `"EXIT"`: a session termination signal

The `jsrn` field (JavaScript Random Number?) identifies which user sent the message,
used to suppress echo for operators viewing their own typing preview.

**Lupopedia lessons**:
- Response format must be JSON (not eval), but the structural idea — message arrays
  with a typed field distinguishing data streams — translates directly to:
  `[{"type":"message","timeof":20260413190000,"line":"..."},{"type":"system","timeof":...}]`
- The dual-cursor pattern (HTMLtimeof + LAYERtimeof) should inform Lupopedia's
  polling endpoint design: separate cursors for different message categories
- The `jsrn` self-echo suppression maps to Lupopedia's `from_actor_id` check on the
  client side: "did I send this? If so, the feed already has it."
- The 2100ms polling interval is the Crafty-validated floor for real-time chat feel
  without excessive server load. Lupopedia's polling interval should use this as a
  reference minimum.

---

## 8. DHTML / Layer Model — DynAPI Ancestry and lupo-layers.js

### 8.1 The DynAPI lineage

Crafty Syntax's floating overlay system (`LAYER` messages, `writediv`, `admin_image.php`
pixel polling) is not merely "old JavaScript." It is directly descended from the
**DynAPI** era of DHTML (Dynamic HTML), circa 1998-2004. DynAPI defined a cross-browser
abstraction layer for "Dynamic Layers" — absolutely positioned, movable, showable,
and hideable elements — that worked across Netscape 4, IE 4, and early IE 5.

The defining characteristic of this lineage is that UI elements are conceived as
**movable objects** with a specific API surface:
- `moveTo(x, y)` — reposition
- `show()` / `hide()` — visibility toggle
- `setHTML(content)` — content injection

These are not CSS class toggles or React state updates. They are direct positional
commands to a layer object, matching how DHTML libraries controlled the browser's
rendering engine in 1999.

Crafty's `LAYER` message type in the polling response was the mechanism for updating
this overlay: the server sent content for the typing preview layer, and the client
wrote it into the correct element using `writediv` (the layer's content setter).

### 8.2 lupo-layers.js as conceptual continuation

`lupo-layers.js` is the **direct architectural heir** of this DynAPI lineage. Its
purpose is not to replicate DynAPI's browser-compat shims (those are obsolete). Its
purpose is to preserve the **behavioral contract**:

- A "layer" is a movable, showable UI element
- It is positioned and controlled programmatically, not via CSS class state
- It has a stable API: `moveTo`, `show`, `hide`
- It does not require a framework

**Implementation strategy (Lupopedia doctrine):**

When modernizing the layer system, the **engine** must be replaced (drop `document.all`,
drop `eval()`, drop Netscape 4 `document.layers`), but the **behavior** must be
preserved:

```javascript
// Legacy (DynAPI / Crafty era) — REPLACE this:
document.all['writediv'].innerHTML = typingText;
eval("document.layers['writediv'].document.write(typingText)");

// Modern (lupo-layers.js) — use bracket notation + standard DOM:
document.getElementById('writediv').innerHTML = typingText;
// Layer control via the same surface:
layer.moveTo(x, y);
layer.show();
layer.hide();
```

The API surface (`moveTo`, `show`, `hide`) MUST be preserved so that existing call sites
do not need to be rewritten. Modernizing the engine underneath the API boundary is the
correct strategy. Do NOT redesign the API just because the implementation is being updated.

### 8.3 Why this matters for Lupopedia

The typing overlay, the floating invite layer (`livehelp_layerinvites`), and any future
HUD-style elements in Lupopedia share this ancestry. Any developer who encounters these
elements and asks "why isn't this a React component / Bootstrap modal / CSS-only overlay"
must understand the answer: the DynAPI heritage is intentional, the survival requirement
is real, and the API surface is stable by design.

The "no CSS/JS frameworks" rule in CLAUDE.md is the doctrine-level expression of this
same constraint. Vanilla JS + minimal DOM = survives shared hosting, survives browser
updates, survives a decade without a maintainer.

---

## 9. Recommendations for Lupopedia

### 9.1 KEEP (already aligned)

| Pattern | Evidence | Lupopedia status |
|---|---|---|
| UNTRUSTED input array | xmlhttp.php line 52; channels/index.php | Implemented |
| BIGINT YYYYMMDDHHIISS timestamps | Crafty `timeof` → Lupopedia doctrine | Implemented |
| Append-only message feed (oldest at top) | admin_chat_xmlhttp.php `innerHTML +` | PRD 02 mandated |
| Delta-fetch with timestamp cursor | HTMLtimeof/LAYERtimeof pattern | Not yet in Lupopedia polling |
| Channel-scoped actor membership | livehelp_operator_channels | Implemented (lupo_actor_channels) |
| Soft-delete doctrine | NOT in Crafty (no soft deletes) | Lupopedia addition — keep |
| Named columns in INSERT | Implicit in Crafty (positional VALUES) | Lupopedia doctrine — enforced |

### 9.2 ADAPT (pattern is right, implementation needs updating)

| Pattern | What to adapt | Notes |
|---|---|---|
| sendTypingRequest + image fallback | Replace image fallback with form.submit() for Lupopedia | Current JS has console.error only (debug mode). Restore graceful fallback after debugging is complete. |
| safeSubmit → blockIt self-replacement | Add to Lupopedia compose form | Prevents double-submit. Trivial to port verbatim. |
| The EXIT signal | Emit as message_type='system' with sentinel text | Allows agent responses to signal session state changes to the client. |
| refreshes > 15 guard | Implement as max DOM line count (~500) | Prevents memory growth in long-running sessions. |
| chattype capability flag | Store as session metadata in lupo_sessions | Replace with `supports_fetch` / `supports_sse` flags for future SSE support. |

### 9.3 REPLACE (Crafty approach is obsolete for Lupopedia)

| Pattern | What replaces it | Why |
|---|---|---|
| eval() for response parsing | JSON.parse() | Security and clarity. JSON is the doctrine. |
| Non-prepared SQL with filter_sql() | PDO named prepared statements | Crafty's string escaping is insufficient and unsupported in Lupopedia. |
| Framesets (rooms, bottomof, users, connection) | CSS grid (channel-live-wrapper) | HTML5 removes framesets. Already implemented in channels/index.php. |
| AUTO_INCREMENT PKs | IdGenerator::generate() | Lupopedia ID doctrine. Already implemented. |
| livehelp_config flat table | lupo_modules.config_json | Already migrated in import SQL. |
| Image pixel polling (csgetimage) | AJAX JSON endpoint | No need for GIF-width binary signals when JSON is available. |
| livehelp_sessions (PHP session data) | lupo_sessions (actor session model) | Different semantic scope; Lupopedia sessions are actor-bound, not PHP-session-bound. |

### 9.4 MODERNIZE (valid idea, needs Lupopedia-doctrine implementation)

| Pattern | Modernization path | Priority |
|---|---|---|
| Delta-fetch polling (HTMLtimeof) | GET `/lupo-api/dialog/fetch-messages.php?channel_id=X&since_ymdhis=Y` returning `[{dialog_message_id, from_actor_id, message_text, message_type, created_ymdhis}]` | HIGH — current live feed requires page reload on every message |
| People/state fingerprinting | Include `actor_hash` in fetch-messages response; client compares and conditionally refreshes sidebar | MEDIUM |
| wantstochat state polling | Add `status` field to dialog thread API responses | MEDIUM — relevant for agent availability signaling |
| Typing indicator | AJAX POST to a `typing` endpoint when textarea changes; display in feed as system event or overlay | LOW — PRD 02 does not specify typing indicators |
| Configurable polling interval | Read from lupo_modules config_json; default 2100ms per Crafty-validated floor | LOW |

### 9.5 UI Doctrine — PRD 02 Command Center constraints

The Crafty Syntax admin chat UI was a monochrome, single-column, time-ordered operator
console. This is not coincidence — it reflects the operational reality of a support
system: operators need a chronological stream, not a social feed.

**PRD 02 derives the following hard constraints from this lineage:**

| Constraint | Rationale |
|---|---|
| Single column layout | The entire stream — human messages, agent responses, system events — lives in ONE chronological feed. No side-by-side agent columns. No tabs-per-agent. |
| Interleaved timeline | All actors (humans, AI agents, system events) appear in strict timestamp order in the same stream. There is no grouping of messages by actor. |
| No chat bubbles | Bubbles imply a two-party conversation. The Lupopedia stream is a multi-actor command log. Bubbles are a visual lie about the data model. |
| No grouped messages | Consecutive messages from the same actor are NOT visually grouped (no "continued" markers, no avatar clusters). Each message is a discrete timestamped event. |
| Oldest at top, newest at bottom | Scroll direction is chronological. New messages append at bottom. No reverse-chronological feeds. |
| Monospace font | The feed is a command log, not a social interface. Monospace preserves alignment and makes structured output (tables, code, status lines) readable. |

**Enforcement:** any UI pattern that violates these constraints — bubbles, grouped messages,
side-by-side agent columns, tabs-per-agent, reverse-chronological scroll — violates PRD 02
and must be flagged by THOTH [ALERT].

The Crafty ancestry makes the reason clear: this is a **command center**, not a messaging
app. The architecture enforces operator discipline. The UI must not soften that.

### 9.6 Shared-hosting survival doctrine — ASCII safety, no logic abstraction

**ASCII-only data mandate:**

Crafty Syntax survived on shared hosting environments where charset settings were
unpredictable, PHP versions were frozen, and database collations were random. The
`filter_sql()` / `cslh_escape()` functions were partly about SQL injection, but also
about stripping encoding landmines from user input before it hit the database.

Lupopedia inherits and formalizes this as:
- All TOON data, JSON config, and header fields MUST be ASCII-safe
- No emoji, no multi-byte characters, no box-drawing in data/config files
- This is not aesthetic — it is a survival requirement for hostile hosting environments
  where UTF-8 handling cannot be guaranteed at every layer

**DB = storage only, application layer = logic:**

Crafty had no stored procedures, no FK constraints, no triggers. This was not laziness
— it was a constraint imposed by cheap shared MySQL hosts of 2003-2008 that silently
dropped or ignored those features. Lupopedia elevates this to an explicit doctrine:
- The DB is a dumb key-value + row store
- All logic lives in PHP application code
- No triggers, no stored procedures, no FK enforcement in the schema
- This keeps the system portable and auditable

**Survival over fashion:**

Crafty ran for 20 years on code that violated every "modern" software engineering
principle. It had no autoloader, no DI container, no test suite, no type annotations.
It worked because:
1. Each function did one thing
2. The data model was flat and queryable
3. The polling logic was 30 lines and stateless
4. There were no abstractions to fail

**Lupopedia doctrine consequence: do NOT abstract away proven simple logic.**

The risk is not complexity — it is premature abstraction. When a working 30-line polling
loop is "refactored" into a service layer with events and handlers and a registry, it
gains 10 potential failure points. The Crafty lineage is a standing reminder that:
- Simple code survives
- Layered abstractions fail in unexpected ways on shared hosting
- The smallest working change is the correct change
- If the logic is 5 lines and clear, do not wrap it in a class

This does not mean "write bad code." It means resist the urge to generalize. Write for
the specific case. The specific case will outlast the abstraction.

---

## Appendix A: Key column name translations

| Crafty Syntax | Lupopedia |
|---|---|
| `livehelp_*.user_id` (integer) | `lupo_actors.actor_id` = (10000 + user_id) |
| `livehelp_messages.timeof` | `lupo_dialog_messages.created_ymdhis` |
| `livehelp_messages.saidfrom` | `lupo_dialog_messages.from_actor_id` |
| `livehelp_messages.saidto` | `lupo_dialog_messages.to_actor_id` |
| `livehelp_messages.channel` | `lupo_dialog_messages.channel_id` |
| `livehelp_messages.message` | `lupo_dialog_messages.message_text` |
| `livehelp_transcripts.transcript` | `lupo_dialog_messages.message_body` (blob import) |
| `livehelp_users.isoperator` | `lupo_actors.is_agent` (inverted: Y=operator=human=0) |
| `livehelp_users.onchannel` | `lupo_actor_channels.channel_id` |
| `livehelp_users.lastaction` | `lupo_sessions.last_activity_ymdhis` |
| `livehelp_users.chattype` | (session metadata — no direct Lupopedia column yet) |
| `livehelp_departments.recno` | `lupo_departments.department_id` |
| `livehelp_websites.id` | `lupo_federation_nodes.federation_node_id` |
| `livehelp_config.*` | `lupo_modules.config_json` (JSON object, module_id=1) |
| `livehelp_operator_channels.user_id` | `lupo_actor_channels.actor_id` |

---

## Appendix B: The livehelp_messages volatility doctrine

This is the single most important data model difference between Crafty Syntax and
Lupopedia. It must be internalized by every implementer.

**In Crafty Syntax**, `livehelp_messages` was a live chat buffer:
- Messages were inserted during active chat sessions
- They were queried by the XMLHTTP polling loop to deliver real-time updates
- After the chat session ended (operator closed the chat), messages were discarded
- The `DELETE FROM livehelp_messages WHERE typeof='writediv'` in xmlhttp.php's
  `donetyping` action shows that LAYER (typing preview) messages were deleted immediately
- Post-session transcript was saved to `livehelp_transcripts` as an HTML blob

The migration SQL explicitly notes this (line 534):
> "crafty did not store any of the messages after the chat ended so this table is empty
> unless there was active chats and lupopedia stores them in threads and messages
> attached to channels"

**In Lupopedia**, `lupo_dialog_messages` is a permanent durable record:
- Every message is stored indefinitely
- Messages are never deleted (soft-delete only via is_deleted flag)
- There is no separate "transcript" concept — the message stream IS the transcript
- lupo_dialog_threads organizes messages into conversations
- The single blog import from livehelp_transcripts is a one-time lossy migration;
  future sessions will be stored at full message granularity

This distinction must be preserved. Code that assumes messages are temporary
(e.g., any cleanup job that purges old dialog_messages rows) violates Lupopedia doctrine.

---

## Delta Integration Notes

**Version:** Gemini delta consolidated — 20260414
**Author:** Actor 116 (Claude Code)

### What changed from the original version

**Section 3.1 (Transport Model) — rewritten:**
The original described a "two-tier refresh architecture" as if it were a static file
pair. This was incomplete. The section now correctly describes an active, session-start
capability negotiation chain governed by `$CSLH_Config['chatmode']`, with one-way
promotion and session lock-in via the `chattype` column. No runtime mode bouncing.

**Section 4.1 (Delta-fetch cursor) — corrected:**
The original suggested `last_message_id` as an acceptable or preferred cursor. This was
wrong. The correct cursor is `after_ymdhis` (14-digit UTC BIGINT), consistent with the
timestamp doctrine and the Crafty `timeof > '$after_time'` query pattern. Message IDs
from IdGenerator are not monotonically sequential and cannot serve as range cursors.

**Section 8 (DHTML / Layer Model) — added new:**
The original document had no coverage of the DynAPI / DHTML layer ancestry or its
implications for `lupo-layers.js`. The new section explains:
- The DynAPI lineage and why layers are "movable objects," not CSS components
- The `moveTo` / `show` / `hide` API surface must be preserved
- Modernization means replacing the engine (no `document.all`, no `eval()`), not
  redesigning the API

**Section 9.5 (UI Doctrine — PRD 02 constraints) — added new:**
The original described the feed as a "unified stream" but did not codify the specific
prohibitions. The new section explicitly states: no bubbles, no grouped messages, no
side-by-side agent columns, no tabs-per-agent, interleaved timeline only.

**Section 9.6 (Shared-hosting survival doctrine) — added new:**
The original document had no section on ASCII-only mandate, no-abstractions rule, or
the "survival over fashion" doctrine. These are now explicitly stated with Crafty
lineage as evidence.

### What Gemini clarified that changed the analysis

1. The fallback ladder is a *configured negotiation chain*, not a static file sequence.
2. `after_time` (14-digit UTC) is the canonical polling cursor — not message ID.
3. The `lupo-layers.js` / DHTML ancestry has a specific API contract to preserve.
4. The PRD 02 "command center" philosophy has specific prohibitions (no bubbles, no grouping)
   that must be stated as constraints, not just described as a "unified stream."
5. ASCII-only and no-logic-abstraction rules derive directly from the Crafty lineage and
   must be stated as survival requirements, not style preferences.

### What was corrected

- "Static fallback ladder" corrected to "dynamic session-locked negotiation chain."
- `last_message_id` removed as a suggested cursor; `after_ymdhis` is canonical.
- "Modernizing logic" guidance was generic; replaced with specific DHTML adaptation
  pattern (replace engine, preserve API surface).
- UI section strengthened from "unified stream description" to explicit PRD 02 prohibition list.
