---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260331120000"
  file_path_from_root: "lupo-docs/versions/4.0.93/decisions/20260331_120000_DECISION_completed_Channel_Chat_Display_API_PRD_and_Standalone_UI.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/decisions/20260331_120000_DECISION_completed_Channel_Chat_Display_API_PRD_and_Standalone_UI.md"
  last_modified_utc: "20260331120000"
  federation_node_id: 0
  channel_id: 42
  thread_id: "D-26"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "decisions"
  purpose: "Channel Chat Display — API, PRD, and Standalone UI"
  tags:
  - "decisions"
  - "legacy"
  - "version-4.0.93"
lupopedia.footer:
  last_verified: "20260331120000"
  verified_by:
    identity_type: "actor"
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
---

# D-26: Channel Chat Display — API, PRD, and Standalone UI

## Type
**Decision** (LILITH audit / implementation thread)

## Status
**Completed**

## Author
**CURSOR** (actor_id 102), **LILITH** (actor_id 2) audit sign-off in thread

## Date
2026-03-31

### Context
PRD `lupo-docs/prd/18_channel_chat_display.md` needed alignment with existing `channels-api.php` and subdirectory-aware URLs. LILITH required extending the canonical API with legacy-friendly transport (`format=buffer`, `format=image`) rather than duplicating a separate `lupo-api/chat/messages.php`.

### Decision
- **Canonical JSON API** remains `GET`/`POST` `api/lupo-channels/{channel_id}/messages` (`lupo-includes/modules/api/channels-api.php`).
- **GET extensions:** `format=json` (default), `format=buffer` (plain body JSON for iframe reads), `format=image` (HTTP 302 to `lupo-ui/images/digitN.gif` with `whatplace` or `position` = hundreds|tens|ones; optional `image_metric=time|count`). **GET** also supports `thread_id` and returns `dialog_thread_id` on messages; list query filters `is_deleted = 0`.
- **Standalone minimalist page:** root `channel.php` (bootstrap via `lupopedia-config.php`), pretty paths `channel-chat/{id}/` and `channel-chat/{id}/thread/{id}/` in `.htaccess`. **Do not** rewrite `/channels/{id}/` away from `index.php` (preserves existing 3-panel `channels-controller` UI).
- **Client:** `lupo-ui/js/chat-display.js` (ES3-safe transport chain), `lupo-ui/js/chat-display-legacy.js` (helpers), `lupo-ui/css/chat-display.css`. Digit GIF assets live under `lupo-ui/images/` (operator replaced placeholders with legacy artwork).
- **Routing:** `module-loader.php` adds `channels/{id}/thread/{id}` → `channels_handle_show($channel_id, $thread_id)`.

### Consequences
- Single message API surface for VSX and browser chat; PRD documents `LUPOPEDIA_PUBLIC_PATH` and fallbacks.
- Legacy digit protocol compatible with Crafty-style filename detection after redirect.

### Comments
*2026-03-31 CURSOR*: No database schema migration in this thread; TOON-aligned columns only.

---
