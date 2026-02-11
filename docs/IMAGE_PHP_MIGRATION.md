# image.php Migration — Summary & Query Mapping

## Summary

**File:** `image.php` in project root (no `/public` folder). Companion to `livehelp_js.php`. Replicates legacy `legacy/craftysyntax/image.php` behavior for the actions used by the generated JavaScript.

**Legacy behavior preserved:**

- **Parameters:** Same GET parameters: `what`, `cmd`, `department`, `hide`, `towhat`, `whatplace`, `page`, `pageid`, `title`, `referer`, `cslhVISITOR`, `leaveamessage`, `xy` (getcredit). Default `what=getstate`.
- **Actions implemented:** `getstate`, `getcredit`, `userstat`, `getlayerinvite`, `changestat`, `browse`. Other legacy actions (pingchat, channelcheck, talkative, messagecheck, usercheck, alive, donetyping, startedtyping) depend on removed tables and are not implemented; the JS from livehelp_js.php only calls getstate, getcredit, userstat, getlayerinvite, changestat, browse.
- **Online/offline logic:** Same as livehelp_js.php: “anyone online” = at least one row from `lupo_sessions` (recent `last_seen_ymdhis`, 20 min) joined to `lupo_actor_channel_roles` (role_key in captain/monitor/operator) and `lupo_channels` (optional department filter). No `livehelp_users` or `livehelp_operator_departments`.
- **Icon selection:** getstate: online → livehelp.gif, offline → livehelp3.gif (or leaveamessage). getcredit: by `xy` (L/W/Y/Z/N) and hide; creditline from `lupo_departments.settings_json` when xy=N. Same image filenames as legacy (livehelp.gif, livehelp2–5.gif, livehelp3.gif, blank.gif, browse.gif, requestchat.gif, requestDHTML.gif, digit0–9.gif).
- **Output:** All responses send an image with correct headers (Content-Type image/gif, no-cache). Images are read from `LUPOPEDIA_PATH . '/images/' . basename(...)` — no absolute paths, no `/public`.

## Legacy query → new table mapping

| Legacy source | Legacy behavior | New table(s) / behavior |
|---------------|-----------------|--------------------------|
| livehelp_users + livehelp_operator_departments | “Anyone online” (isonline, isoperator, department) | **lupo_sessions**, **lupo_actor_channel_roles**, **lupo_channels** — recent session + role (captain/monitor/operator) + channel.department_id |
| livehelp_users (sessionid → user_id, status, sessiondata) | getlayerinvite: session data for invite id; userstat: status; changestat: update status | **lupo_sessions** — session_id, session_data, last_seen_ymdhis. invite/status stored in session_data (key=value). |
| livehelp_departments (creditline, recno) | getcredit: creditline for xy; leaveamessage from department | **lupo_departments** — department_id, settings_json (creditline, leaveamessage). |
| livehelp_visit_track | userstat: insert/compare page visits | Not implemented (no TOON for visit track). last_seen_ymdhis on lupo_sessions updated instead. |
| livehelp_users (lastaction) | userstat: update lastaction | **lupo_sessions** — last_seen_ymdhis, updated_ymdhis updated on userstat. |

All DB access uses PDO via `DatabaseFactory::getConnection()`, `$LUPO_TABLE_PREFIX`, and prepared statements. No references to `livehelp_*` or `lupo_operator_*` tables.

## Table/column names vs docs/toons

- **lupo_sessions:** session_id, actor_id, session_data, is_active, is_expired, last_seen_ymdhis, updated_ymdhis, is_deleted.
- **lupo_actor_channel_roles:** actor_id, channel_id, role_key, is_deleted.
- **lupo_channels:** channel_id, department_id, is_deleted.
- **lupo_departments:** department_id, settings_json, is_deleted.

All match `docs/toons/*.toon.json`. No removed or deprecated tables used.

## Paths

- No `/public` folder. No absolute webroot paths. All image paths built from `LUPOPEDIA_PATH` (project root) and `images/` subfolder. `LUPOPEDIA_PUBLIC_PATH` is defined the same way as in livehelp_js.php for consistency; image.php does not output URLs, only serves files from disk under project root.
