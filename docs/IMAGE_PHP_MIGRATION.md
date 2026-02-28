# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\IMAGE_PHP_MIGRATION.md"
  file_hash: "c96895d2f5e6c917297e45c9b60a7c2997ae76240c31bcd7cd05d68aebf08643"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\IMAGE_PHP_MIGRATION.md"
  file_hash: "e6b27beb69eb8ac3f244ec726b091906476e0feea35e8b1192769fd25c0a8d42"
  file_path_from_root: "docs\IMAGE_PHP_MIGRATION.md"
  file_hash: "8777a40228616168ba1fff49b78471d79a4e75e390e0f805ef8411bfbd77203d"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "image.php Migration — Summary & Query Mapping"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "image_php_migrationmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

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