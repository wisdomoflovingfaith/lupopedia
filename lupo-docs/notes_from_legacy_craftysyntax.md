# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/notes_from_legacy_craftysyntax.md"
  version_when_written: "4.0.84"
  file_hash: "e504e691f612f3bbc9d6be55503ca53105b45708487d256757d2c70e596adf7b"
  last_updated_utc: "20260228155738"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\notes_from_legacy_craftysyntax.md"
  file_hash: "1459f5b40dc9f61d10d1d95f0a9b3b8e4e38ab58384f7b32e06e51f8f8153ce7"
  file_path_from_root: "lupo-docs\notes_from_legacy_craftysyntax.md"
  file_hash: "416b470a988b84d032628929e628d7ac9815c771031100abf71322182f10cbb5"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Notes from Legacy Crafty Syntax Codebase"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "notes_from_legacy_craftysyntaxmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Notes from Legacy Crafty Syntax Codebase

**Source:** `lupo-legacy/craftysyntax/`  
**Purpose:** Authoritative reference for rebuilding the operator interface in Lupopedia. Extract only—no modernization or rewriting. Document legacy logic exactly as it worked.

---

## 1. Unified Multi-Thread Message Stream (Interleaved by Timestamp)

- **Single scrolling stream:** The operator sees one continuous message area. All messages from all threads (channels) the operator is on are shown in **one list ordered by `timeof`** (timestamp).
- **Implementation:** `functions.php` → `showmessages($myid, $typeof, $aftertime, $seechannel, ...)`.
  - With `$seechannel == ""`: messages are selected from **all channels** the operator is on, via JOIN with `livehelp_operator_channels` where `livehelp_operator_channels.user_id = $myid`, and `livehelp_messages.channel = livehelp_operator_channels.channel`.
  - Query: `ORDER BY timeof` (ascending). So messages from different channels are **interleaved by time**.
  - With `$seechannel != ""`: only that channel’s messages are shown, still `ORDER BY timeof`.
- **Visitor side:** For visitors, the query restricts to `saidto = $myid OR channel = $seechannel`, same ordering by `timeof`.
- **Important:** There are no separate per-thread panels in the legacy UI. One stream, one order: `timeof`.

**Legacy → Lupopedia Mapping (per migration doctrine)**  
- `livehelp_messages` → **DROPPED** (ephemeral buffer; no import). Replacement: **lupo_dialog_messages** (and **lupo_dialog_threads**). Durable message/transcript data comes from **livehelp_transcripts** → **lupo_dialog_threads** + **lupo_dialog_messages**. For the operator stream: use **lupo_dialog_messages** with `ORDER BY created_ymdhis ASC` (legacy `timeof`).  
- `livehelp_operator_channels` → **DROPPED**. Replacement: **lupo_actor_channels** (actor–channel membership), **lupo_dialog_threads** (threads), **lupo_channels**. “All channels the operator is on” → query **lupo_actor_channels** for that operator’s `actor_id` and join to **lupo_dialog_messages** / **lupo_dialog_threads** by `channel_id` and thread.

---

## 2. Background Color per Thread

- **Per-channel (thread) color:** Each operator–channel association has a **channel color** used as the background for that thread’s messages in the stream.
- **Schema:** `livehelp_operator_channels` has `channelcolor` (and `txtcolor`, `txtcolor_alt` for text). In `showmessages()`, the SELECT includes `livehelp_operator_channels.channelcolor` (and txtcolor/txtcolor_alt).
- **Rendering:** Each message row is wrapped in a table with `$channelcolor` applied as `bgcolor=` (e.g. `$tablestart = "<table ... $channelcolor>..."`). So in the single stream, each message block is colored by its **channel’s** `channelcolor` from the operator_channels row for that operator and channel.
- **Tabs (admin_chat_bot.php):** Tab strips and bottom border also use `channelcolor` for the active channel and for grouping (e.g. `bgcolor="#<?php echo $txtcolor; ?>"` where `$txtcolor` is the channel’s color). Color can be changed per channel via `chat_color.php` (e.g. `openwindow('chat_color.php?id=...')`).

**Legacy → Lupopedia Mapping (per migration doctrine)**  
- `livehelp_operator_channels` (channelcolor, txtcolor, txtcolor_alt) → **DROPPED**. Replacement: thread-level UI colors in **lupo_dialog_threads** (e.g. `bg_color`, `text_color` or metadata) and/or **lupo_actor_channels** / channel metadata. Per-message block color in the unified stream → use the thread’s color from **lupo_dialog_threads** (or equivalent) for that message’s thread.

---

## 3. Typing-Preview Bubble (Visitor Typing in Real Time)

- **Storage:** Typing preview is stored in the **same messages table** as normal chat: `livehelp_messages` with `typeof='writediv'`. One row per (channel, saidfrom) is used as the “typing” state; it is updated or cleared.
- **Writer (visitor/operator):** In `admin_chat_bot.php`, JS runs `sayingwhat()` every 5 seconds. If `previewsetting` &lt; 3 and comment length &gt; 2, it POSTs to `admin_image.php` with `what=startedtyping`, `channelsplit`, `fromwho`, `sayingwhat` (escaped draft text or “nullstring” to clear). On submit, JS also sends `sayingwhat: 'nullstring'` to clear the typing row.
- **admin_image.php (what=startedtyping):**  
  - Parses `channelsplit` → channel and saidto.  
  - If no writediv row exists for this channel + saidfrom: `INSERT INTO livehelp_messages (..., typeof) VALUES (..., 'writediv')`.  
  - Else: `UPDATE livehelp_messages SET timeof=..., message=... WHERE typeof='writediv' AND channel=? AND saidfrom=?`.
- **Reader (operator):**  
  - **Refresh mode:** `admin_chat_refresh.php` calls `showmessages($operator_id, "writediv", $timeofDHTML, $see)` and renders the writediv content into a **floating layer** (“UserIsTypingDiv”). JS array `whatissaid[jsrn]` holds per-sender typing HTML; `update_typing()` concatenates them and shows/hides the layer.  
  - **XMLHttp mode:** `xmlhttp.php` with `whattodo=messages` returns both HTML messages and writediv content. Client parses; LAYER-type lines update `whatissaid[jsrn]` and then `update_typing()`.  
- **Preview options (previewsetting):** 1 = show full text, 2 = “is typing” + dots, 3 = no preview, 4 = off. When `show_typing != "Y"`, default is 4.  
- **Clearing:** On send, `admin_chat_bot.php` runs `DELETE FROM livehelp_messages WHERE typeof='writediv'` (global clear of all typing rows).

**Legacy → Lupopedia Mapping (per migration doctrine)**  
- `livehelp_messages` with `typeof='writediv'` → **DROPPED** (no import; table was ephemeral). Typing preview in Lupopedia: implement via ephemeral store (e.g. file-based or cache), **not** as rows in **lupo_dialog_messages**. Preserve behavior: one typing state per (channel/thread, sender); clear all typing on send; reader sees floating preview (e.g. from API polling).

---

## 4. Fallback Chain (Chat / Message Updates)

Legacy uses a **chatmode** config (`CSLH_Config['chatmode']`, default `"flush-xmlhttp-refresh"`) and several mechanisms that form a fallback chain:

1. **Bottom frame auto-refresh (default for “connection” frame)**  
   - **Entry:** `admin_connect.php` redirects to a chat view based on first token of `chatmode`: `xmlhttp` → `is_xmlhttp.php`, `flush` → `is_flush.php`, else → **`admin_chat_refresh.php`**.  
   - **admin_chat_refresh.php:** Full page reload on a timer. Either:  
     - **META refresh:** `content="refreshrate; URL=admin_chat_refresh.php"` (if `refreshrate != 1`), or  
     - **JS timer:** `setTimeout('csgetimage()', 3500)` which loads an image; on load, `lookatimage()` checks image width; if 55 or 0 → `refreshit()` → `window.location.replace("admin_chat_refresh.php")`.  
   - So the **connection** frame (message stream) either refreshes by META or by **image-size polling** (see below) that triggers a full frame reload.

2. **XMLHttpRequest polling**  
   - **admin_chat_xmlhttp.php:** Renders initial HTML with `showmessages(..., $timeof, $see)` in a `<span id="currentchat">`. Then JS runs `update_xmlhttp()` every ~2100 ms: GET to `xmlhttp.php?op=yes&whattodo=messages&HTML=...&LAYER=...`.  
   - **xmlhttp.php (whattodo=messages):** Calls `showmessages($myid, "", $UNTRUSTED['HTML'], ...)` and `showmessages($myid, "writediv", $UNTRUSTED['LAYER'], ...)` with **deliminated** output (JS array of messages). Response is parsed in `ExecRes(textstring)`; new HTML is appended to `currentchat`, LAYER updates `whatissaid[jsrn]` and `update_typing()`.  
   - So the **connection** frame can be the XHR-based chat view instead of refresh, when chatmode starts with `xmlhttp`.

3. **Image-size polling**  
   - **admin_chat_refresh.php:** Inline img or JS-created `Image` loads `admin_image.php?what=messagecheck&message_test=...`.  
   - **admin_image.php (what=messagecheck):** `SELECT timeof FROM livehelp_messages WHERE typeof != 'writediv' AND timeof > $message_test`. If no rows → return `controlimage_noaction.gif` (small); if any row → return `controlimage_action.gif` (width 55).  
   - **Client:** `lookatimage()` checks width; if 55 (or 0) → call `refreshit()` → full frame reload. So **image-size polling** is the “trigger” for refresh in the connection frame when not using XHR.  
   - Same pattern in **admin_chat_bot.php** for **users/tabs**: `csgetimage()` / `tabgetimage()` load `admin_image.php?what=usercheck` or `what=tabscheck` with `peoplestring_test`. If server-side peoplestring differs from client, response is the 55px image → client refreshes users frame or bot frame.

4. **Full page refresh**  
   - **admin_chat_bot.php:** `shouldireload()` (e.g. after 15+ refreshes in `ExecRes`) or when `window.parent.connection.sleeping` or user “force refresh” calls `forcerefreshit()`: `window.parent.connection.location.replace("admin_connect.php")` and/or `window.location.replace("admin_chat_bot.php")`. So the **connection** frame is sent back to `admin_connect.php` (which may redirect again to refresh or xmlhttp), and the **bottom** frame can reload.  
   - **admin_connect.php:** If operator has no channels (`mychannels->numrows() == 0`), it prints “no one online” and sets `sleeping=true`; no redirect to chat. Otherwise it redirects to the appropriate chat script (refresh or xmlhttp).

**Summary order of use:**  
- Connection frame: either **XHR polling** (xmlhttp chat view) or **refresh** view, which uses **image-size polling** to decide when to **reload the frame** (and optionally META refresh).  
- Users list / tabs: **image-size polling** to detect change; on change, **reload** users or bot frame.  
- **Full page refresh** is the fallback when things are stale or user forces refresh (e.g. `admin_connect.php` + bot reload).

**Legacy → Lupopedia Mapping (per migration doctrine)**  
- `livehelp_messages` (messagecheck: `timeof > $message_test`) → **lupo_dialog_messages**: use `created_ymdhis > after_ymdhis` for “new messages” check (image-size equivalent or JSON `refresh` flag).  
- Chatmode / refreshrate config → **lupo_modules.config_json** (module_id = 1) per **livehelp_config_migration.md**; read chatmode, refreshrate from that config.

---

## 5. Operator Cockpit Workflow (Seeing 2–6 Chats at Once)

- **Entry:** `live.php` (operator “live” view). Frameset:  
  - **Rows:** 52, *, 155 (top bar, main, bottom).  
  - **Top:** `admin_options.php` (topofit).  
  - **Main:** cols = *, 317. Left: rows 32, * → **admin_rooms.php** (rooms), **admin_connect.php** (connection = message stream). Right: **admin_users.php** (users).  
  - **Bottom:** **admin_chat_bot.php** (bottomof = composer + chat tabs).

- **Tabs = “2–6 chats”:** The bottom frame (**admin_chat_bot.php**) builds **one tab per channel** the operator is on. Query:  
  `SELECT ... FROM livehelp_operator_channels, livehelp_users WHERE ... livehelp_operator_channels.user_id = $myid`.  
  So the number of tabs = number of rows in `livehelp_operator_channels` for this operator (typically one tab per visitor/channel; often 2–6 in practice).

- **Tab content:** Each tab is a link to `admin_chat_bot.php?channelsplit=channel__userid`. Selecting a tab sets the active conversation (channel + saidto). The **same** connection frame shows the **unified** message stream for all channels; the composer is for the **selected** channel/user.

- **External windows:** Operator can open an “external” window for a channel (`externalwindow(channelsplit)`) or close it (`stopwindow(...)`). `externalchats` on the user row is a comma list of channel IDs that are in external windows.

- **Flow:** Operator sees rooms bar, one scrolling message stream (all channels interleaved), a user list (visitors/chatters), and a bottom strip with tabs + composer. Selecting a tab only changes the target of the composer and the highlight; the stream stays unified.

**Legacy → Lupopedia Mapping (per migration doctrine)**  
- `livehelp_operator_channels` (tabs = one per channel for this operator) → **lupo_actor_channels** (actor–channel membership) and **lupo_dialog_threads** (one tab per thread in the operator’s channel/workspace). Build tabs from threads the operator sees (e.g. threads in channels where actor is in **lupo_actor_channels**).  
- `livehelp_users` (externalchats, onchannel, etc.) → **lupo_auth_users** (identity only); routing/UI state like externalchats → implement via **lupo_actors** / **lupo_operators** or app state, not stored in auth table per **livehelp_users_migration.md**.

---

## 6. SQL Logic in admin_connect.php, admin_users.php, admin_chat_bot.php

### admin_connect.php

- **Session / operator:**  
  `SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'`  
  → `$myid`, `$channel`, etc.
- **Clear to “now”:** If `$UNTRUSTED['clear'] == "now"`:  
  `SELECT * FROM livehelp_messages ORDER BY timeof DESC` → take latest `timeof`; set `$timeof = $message['timeof'] - 2` (and offset/starttimeof) so next load shows from “now”.
- **“No one talking”:**  
  `SELECT ... FROM livehelp_operator_channels, livehelp_users WHERE ... livehelp_operator_channels.user_id = $myid`.  
  If no rows: print “no one online”, set `sleeping=true`, exit. Otherwise redirect to chat script.
- **Chatmode:** From `$CSLH_Config['chatmode']` (e.g. `flush-xmlhttp-refresh`), first token picks script: `xmlhttp` → `is_xmlhttp.php`, `flush` → `is_flush.php`, default → `admin_chat_refresh.php`. Redirect to that script with `see`, etc.

### admin_users.php

- **Redirect only:** No direct SQL. If `$CSLH_Config['admin_refresh'] == "AJAX"` → `admin_users_xmlhttp.php`, else → `admin_users_refresh.php`. Redirect after 1200 ms.

### admin_chat_bot.php

- **Operator:**  
  `SELECT user_id, onchannel, showtype, externalchats, chattype, username FROM livehelp_users WHERE sessionid='...'`.
- **Send message (whattodo=send):**  
  - Optional: `UPDATE livehelp_users SET username='...' WHERE isoperator='N' AND user_id=$saidto`.  
  - If visitor status != 'chat': `UPDATE livehelp_users SET status='request' WHERE user_id=$saidto`.  
  - `DELETE FROM livehelp_messages WHERE typeof='writediv'`.  
  - Uniqueness: loop `SELECT timeof FROM livehelp_messages WHERE timeof='$timeof'`; while rows exist, sleep(1) and recompute `$timeof` (or increment).  
  - `INSERT INTO livehelp_messages (message, channel, timeof, saidfrom, saidto) VALUES (...)`.
- **Tabs / channels:**  
  `SELECT ... FROM livehelp_operator_channels, livehelp_users WHERE ... livehelp_operator_channels.user_id = $myid` (and for each tab, optional check for common channel with other operators).  
- **Visitors list (peoplestring):**  
  Either `SELECT * FROM livehelp_users ORDER BY user_id DESC` (showvisitors=1) or `SELECT * FROM livehelp_users WHERE status='chat' ORDER BY user_id DESC`; then `SELECT * FROM livehelp_operator_channels ORDER BY user_id DESC`. Build `peoplestring` from sessionid+status and user_id for change detection.
- **Quick notes / URLs:**  
  `SELECT * FROM livehelp_quick WHERE typeof!='URL'` and `typeof='URL'` for dropdowns.

**Legacy → Lupopedia Mapping (per migration doctrine)**  
- `livehelp_users` (session, operator lookup) → **lupo_auth_users** (identity: username, display_name, email, password_hash, last_login_ymdhis); session/auth via Lupopedia auth/session; operator/visitor roles and channel state via **lupo_actors**, **lupo_operators**, **lupo_actor_channels**.  
- `livehelp_messages` (clear to now, last timeof, INSERT, writediv delete) → **lupo_dialog_messages** (created_ymdhis, from_actor_id, to_actor_id, message_text, dialog_thread_id, channel_id). Use `created_ymdhis` for “clear to now” and uniqueness.  
- `livehelp_operator_channels` (mychannels, tabs, “no one talking”) → **lupo_actor_channels** (actor_id, channel_id) and **lupo_dialog_threads**; “no one talking” = no rows for this operator in **lupo_actor_channels** (or no active threads).  
- `livehelp_quick` → **lupo_actor_reply_templates**: `id` → actor_reply_template_id, `user` → actor_id, `name` → template_key, `message` → template_text, `typeof` → usage_context. Quick notes/URLs dropdowns = query **lupo_actor_reply_templates** by actor_id and usage_context.

---

## 7. How Threads, Messages, Visitors, and Operators Were Tracked

- **Threads = channels:** A “thread” is a **channel**: `livehelp_messages.channel` and `livehelp_operator_channels.channel`. One channel = one conversation (typically one visitor + one or more operators).  
- **livehelp_operator_channels:** Links operator (`user_id`) to channel (`channel`) and the other party (`userid` = visitor/user on that channel). Holds `channelcolor`, `txtcolor`, `txtcolor_alt` for that thread.  
- **Messages:** `livehelp_messages`: `channel`, `timeof`, `saidfrom`, `saidto`, `message`, `typeof` ('' or 'writediv'). Ordered by `timeof` for the unified stream.  
- **Visitors:** `livehelp_users` with `isoperator='N'`. Identified by `sessionid`; `status` can be 'Visiting', 'request', 'chat', 'offline', etc. `onchannel` is the channel they’re on (-1 if none).  
- **Operators:** `livehelp_users` with `isoperator='Y'`. Same table; `onchannel` and `livehelp_operator_channels` define which channels they’re on.  
- **Presence (high level):** `lastaction` updated on pings; `isonline` ('Y'/'N'); `status` (e.g. 'chat', 'request', 'offline'). See §9.

**Legacy → Lupopedia Mapping (per migration doctrine)**  
- “Thread” = legacy channel (one conversation) → **lupo_dialog_threads** (one row per conversation/thread); **lupo_dialog_messages** has `dialog_thread_id`, `channel_id`, `from_actor_id`, `to_actor_id`, `created_ymdhis` (legacy timeof).  
- `livehelp_operator_channels` → **DROPPED**. Replacement: **lupo_actor_channels** (operator–channel and visitor–channel membership), **lupo_dialog_threads** (thread identity and colors), **lupo_actor_presence** (presence).  
- `livehelp_users` (visitors: isoperator='N'; operators: isoperator='Y') → **lupo_auth_users** (identity); **lupo_actors** (unified identity); **lupo_operators** (operator-specific); **lupo_actor_properties** (presence/behavior). Session/routing fields (sessionid, status, onchannel) not imported; use **lupo_sessions**, **lupo_actor_presence**, **lupo_actor_channels** for presence and “who is on which channel”.

---

## 8. How Invites Worked

- **Layer invite (anti–pop-up):** Operator can send an invite that appears as a **layer** (DIV) on the visitor page instead of a popup.  
  - `livehelp_layerinvites`: `layerid`, `name`, `imagename`, `imagemap`, `department`, `user`.  
  - Visitor JS (e.g. livehelp_js.php): When server signals “invite”, client shows a DIV (`layerinvite_<?php echo $department; ?>`) with content from `getlayerinvite` (image + map). Image map can call `openLiveHelp()` etc.  
  - Operator side: “Invite” action sets a flag/towhat=invited so the visitor script shows the layer.
- **Auto-invite:** `livehelp_autoinvite`: rules (e.g. referer, page count, page URL, department). When a visitor matches, system can auto-invite (e.g. show layer or trigger chat).  
  - Visitor script checks `livehelp_autoinvite` (e.g. `isactive='Y'`) and conditions; operator preference `auto_invite` on `livehelp_users`.  
- **Department-level:** Departments have `layerinvite` (e.g. image name). Per-operator `layerinvite` (int) and `auto_invite` (char) control whether that operator participates in layer/auto invites.

**Legacy → Lupopedia Mapping (per migration doctrine)**  
- `livehelp_layerinvites` → **lupo_crafty_syntax_layer_invites**: name→layer_name, imagename→image_name, imagemap→image_map, department→department_name, user→user_id. Compatibility table for legacy layer-invite behavior.  
- `livehelp_autoinvite` → **lupo_crafty_syntax_auto_invite**: idnum→crafty_syntax_auto_invite_id, isactive→is_active, department→department_id, message, page→page_url, referer→referrer_url, typeof→invite_type, seconds→trigger_seconds, user_id→operator_user_id, etc.  
- Department layerinvite / operator auto_invite → **lupo_departments** + **lupo_department_metadata** (per **livehelp_departments_migration.md**) and operator/actor settings as defined in schema.

---

## 9. How Presence Worked

- **lastaction:** BIGINT(14), format YmdHis. Updated on:  
  - Visitor/operator ping (e.g. `admin_image.php` userstat: `UPDATE livehelp_users SET lastaction='$rightnow' WHERE sessionid=...`).  
  - Sending a message, opening chat, etc.  
  Used to detect “recently active” and timeouts.
- **isonline:** 'Y' or 'N'. Set on login (e.g. auth/login) and when going offline (e.g. `admin_rooms.php`: `UPDATE ... SET isonline='N', status='offline' ...`).
- **status:** e.g. 'Visiting', 'request', 'chat', 'offline', 'DHTML'.  
  - Visitor: 'request' when they want to chat; operator sets to 'chat' when connected.  
  - Operator: 'online' when available; 'offline' when they set status N.
- **chataction:** Timestamp updated when the user is in a chat view (e.g. xmlhttp whattodo=messages: `UPDATE livehelp_users SET chataction='$nowtime' WHERE user_id=... AND status='chat'`).
- **Session validation:** `validate_session($identity)` and `update_session($identity)` ensure the session is valid and extend/update user row (expires, lastaction, etc.).  
- **Alive check (admin_image.php what=alive):** `SELECT ... FROM livehelp_users, livehelp_operator_departments WHERE ... isonline='Y' AND isoperator='Y'` to list departments with at least one online operator.

**Legacy → Lupopedia Mapping (per migration doctrine)**  
- `livehelp_users` (lastaction, isonline, status, chataction, session validation) → **lupo_auth_users** holds only identity (e.g. last_login_ymdhis). Presence/activity: **lupo_actor_presence**, **lupo_actor_properties**, **lupo_sessions** (per **livehelp_users_migration.md**, **livehelp_operator_channels_migration.md**). Session validation → Lupopedia auth/session; “alive” / online operators → **lupo_operators** + **lupo_actor_presence** (or equivalent).  
- `livehelp_operator_departments` (alive check join) → **lupo_actor_departments** (actor_id, department_id, title). “Departments with at least one online operator” → join **lupo_operators** / **lupo_actor_presence** with **lupo_actor_departments**.

---

## 10. Other Legacy Behaviors to Preserve

- **Clear / “clear to now”:** `clear=now` or `cleartonow=1` sets `timeof` to (last message time) − 2 so the next load shows “from now” (effectively clearing old messages from view).
- **Single writediv clear on send:** On operator send, **all** writediv rows are deleted (`DELETE FROM livehelp_messages WHERE typeof='writediv'`), not only the current channel. Typing preview is global clear on send.
- **Timestamp uniqueness:** Before INSERT of a message, legacy loops until `timeof` is unique in `livehelp_messages` (sleep(1) and recompute or increment) to avoid collisions in hosted setups.
- **PUSH URLs:** Message can contain `[PUSH]url[/PUSH]`; for visitor this becomes `openwindow('url','popwindow')`; for operator the tags are stripped and link shown.
- **Transfer:** `[transfer]url[/transfer]` in message to visitor triggers `window.parent.location.replace('url')` and displays “..transfered..”.
- **Visitor message filter:** For visitors, `showmessages` only returns rows where `saidto = $myid OR channel = $seechannel`.
- **Operator channels filter:** For operators, messages are restricted to channels in `livehelp_operator_channels` for that operator (`user_id = $myid`).
- **jsrn:** User row has `jsrn` (small int); used as array index in client for `whatissaid[jsrn]` (typing preview per sender).
- **Security / visits:** In admin_connect and admin_chat_refresh, when operator has no channels, every 35th “visit” increments a `visits` counter and opens an external URL (security check).
- **Refreshrate:** Config `refreshrate` (seconds) used in META refresh in admin_chat_refresh when not 1.
- **Rooms (admin_rooms.php):** Operator can set status (online/offline), show_arrival, user_alert, typing_alert, auto_invite; updates `livehelp_users` accordingly.
- **Rename visitor:** Operator can set `newusername` for a visitor (UPDATE livehelp_users SET username=... WHERE isoperator='N' AND user_id=...).
- **Quick notes / URLs:** `livehelp_quick` (typeof 'URL' or other) for canned messages and link push; access filtered by note_access(visibility, department, user).
- **Sounds:** alertchat, alerttyping, alertinsite (wav/mp3) for new chat, typing, in-site; played from bottom frame (e.g. shoulditype(), typing alert checkbox).
- **Link/email conversion:** Messages get www/http links converted to `<a href=... target=_blank>` unless allowHTML or [PUSH]; smilies converted if smilies=YES.
- **Color per channel:** Stored in livehelp_operator_channels (channelcolor, txtcolor, txtcolor_alt); editable via chat_color.php for that channel row.

**Legacy → Lupopedia Mapping (per migration doctrine)**  
- `livehelp_messages` (clear to now, writediv clear, timestamp uniqueness, operator/visitor filter) → **lupo_dialog_messages**; use `created_ymdhis` for ordering and “clear to now”; preserve timestamp-uniqueness and global typing-clear behavior in application logic.  
- `livehelp_operator_channels` (operator channels filter, color per channel) → **lupo_actor_channels** (membership); thread/channel colors from **lupo_dialog_threads** (e.g. bg_color) or channel/actor metadata.  
- `livehelp_users` (rooms settings, rename visitor, visits, config) → **lupo_auth_users** (identity); operator settings and presence → **lupo_operators**, **lupo_actor_properties**, **lupo_actor_departments**. Rename visitor → update **lupo_actors** (or auth_users display name) for that actor.  
- `livehelp_quick` (quick notes/URLs, note_access) → **lupo_actor_reply_templates** (template_key, template_text, usage_context, actor_id). Filter by visibility/department/user in application layer (no legacy note_access table; implement via roles/departments).  
- Config (refreshrate, show_typing, etc.) → **lupo_modules.config_json** (module_id = 1), per **livehelp_config_migration.md**.

---

## Combined mapping index (quick reference)

| Legacy table | Lupopedia / replacement |
|--------------|-------------------------|
| livehelp_messages | **DROPPED**. Use **lupo_dialog_messages** (created_ymdhis, from_actor_id, to_actor_id, message_text, dialog_thread_id, channel_id). Durable history from livehelp_transcripts → lupo_dialog_threads + lupo_dialog_messages. |
| livehelp_operator_channels | **DROPPED**. Use **lupo_actor_channels**, **lupo_dialog_threads**, **lupo_channels**, **lupo_actor_presence**, metadata_json for colors. |
| livehelp_users | **lupo_auth_users** (identity); **lupo_actors**, **lupo_operators**, **lupo_actor_properties** (presence/behavior). Session/routing not in auth table. |
| livehelp_quick | **lupo_actor_reply_templates** (id→actor_reply_template_id, user→actor_id, name→template_key, message→template_text, typeof→usage_context). |
| livehelp_layerinvites | **lupo_crafty_syntax_layer_invites** (layer_name, image_name, image_map, department_name, user_id, etc.). |
| livehelp_autoinvite | **lupo_crafty_syntax_auto_invite** (crafty_syntax_auto_invite_id, is_active, department_id, message, page_url, referrer_url, invite_type, trigger_seconds, operator_user_id, etc.). |
| livehelp_departments | **lupo_departments** + **lupo_department_metadata** (core identity vs UI/behavior in metadata). |
| livehelp_operator_departments | **lupo_actor_departments** (actor_id, department_id, title). |
| livehelp_config | **lupo_modules.config_json** (module_id = 1). |
| livehelp_transcripts | **lupo_dialog_threads** + **lupo_dialog_messages** (one thread per transcript, one message with full transcript). |
| livehelp_channels | **DROPPED**. Replaced by **lupo_channels** (real channels) and **lupo_dialog_threads**; operator workspace is UI + these tables. |

---

*End of notes. This document is the combined source of truth: legacy behavior (extracted from the codebase) and new schema mapping (from lupo-docs/doctrine/migrations/*). Use both when rebuilding the Lupopedia operator interface and any feature that originated in Crafty Syntax.*
