# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\channels\42\threads\DEVELOPMENT_CYCLE_4_0_49\20260227134000_1007_channels_admin_api_research.md"
  file_hash: "4a12a3a68b7ee5089a3e5168d1a1dec5132e34f0c7ed0362fce7ca7d04cb3b05"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
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
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  file_path_from_root: "channels/42/threads/DEVELOPMENT_CYCLE_4_0_49/20260227134000_1007_channels_admin_api_research.md"
  file_hash: "0a96914b40ea22021f4447e0b354ff1f5aa55138441f4bc89b3d2cba4c83f557"
  system_version: "4.0.50"
  channel_id: 42
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "report"
  purpose: "Research report and recommendations for channels admin API endpoint wiring"
  dialog_message: "Recommended next step: implement a small admin API module for operators and departments, then wire channels_comm.js to those endpoints."
  mood_rgb: "4169E1"
  artifact_kind: "api_research"
  traits: ["development", "api", "integration"]
  tags: ["channels", "admin_interface", "api", "4.0.49"]
  lupo_agent: "codex-ide"

  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  outbound_edges:
    - { to: "lupo-includes/modules/module-loader.php", type: "references", weight: 0.9, reason: "API routing map" }
    - { to: "lupo-includes/modules/api/channels-api.php", type: "references", weight: 0.8, reason: "channels REST endpoint" }
    - { to: "lupo-includes/modules/channels/channel-send-api.php", type: "references", weight: 0.7, reason: "legacy channel send endpoint" }
    - { to: "lupo-includes/modules/channels/channel-messages-api.php", type: "references", weight: 0.7, reason: "legacy channel messages endpoint" }
    - { to: "lupo-includes/modules/channels/operator-pending-visitors-api.php", type: "references", weight: 0.7, reason: "operator pending visitors endpoint" }
    - { to: "lupo-includes/modules/channels/operator-accept-visitor-api.php", type: "references", weight: 0.7, reason: "operator accept visitor endpoint" }
    - { to: "channels/1/assets/js/channels_comm.js", type: "references", weight: 0.8, reason: "comm layer" }
  semantic_tags: ["api", "research", "channel_42", "admin_interface"]
  last_verified: "20260227"
  last_verified_by: "codex-ide"
---

# Channels Admin API Endpoint Research (4.0.49)

## Summary of Findings
- **Existing API routes are limited to chat/visitor operations** and VSX messaging, routed via `lupo-includes/modules/module-loader.php`.
- **No existing REST endpoints** for operators, departments, or settings CRUD were found.
- **Legacy channel endpoints** exist and are functional for chat monitoring and visitor handling.
- **channels_comm.js** is generic and can be wired to new endpoints once added.

## Existing API Endpoints (Confirmed)
Routing defined in `lupo-includes/modules/module-loader.php`:
- `GET/POST api/channels/{id}/messages` -> `lupo-includes/modules/api/channels-api.php`
- `GET api/channel/messages` -> `lupo-includes/modules/channels/channel-messages-api.php`
- `POST api/channel/send` -> `lupo-includes/modules/channels/channel-send-api.php`
- `GET/POST api/channel/typing` -> `lupo-includes/modules/channels/channel-typing-api.php`
- `GET api/channel/check` -> `lupo-includes/modules/channels/channel-check-api.php`
- `GET api/operator/pending-visitors` -> `lupo-includes/modules/channels/operator-pending-visitors-api.php`
- `POST api/operator/accept-visitor` -> `lupo-includes/modules/channels/operator-accept-visitor-api.php`

Other REST endpoints in the codebase (non-admin):
- `api/registry/actors/lookup` and `api/registry/actors/register`
- `api/semantic/*`

## Gaps for Channels Admin
Admin pages in `channels/1/admin/` currently **read directly from DB**. There are no REST endpoints for:
- Operators CRUD (`lupo_auth_users`)
- Departments CRUD (`lupo_departments`)
- Channel settings updates (`lupo_channels`)

## Recommendations: Best-Fit Endpoints
### 1) Chat Monitor (Use existing endpoints)
- **GET** `api/channel/messages?channel_id={id}` (existing `channel-messages-api.php`)
- **GET** `api/channel/check?channel_id={id}` (existing `channel-check-api.php`)
- **POST** `api/channel/send` (existing `channel-send-api.php`)
- **GET** `api/operator/pending-visitors` (existing)
- **POST** `api/operator/accept-visitor` (existing)

### 2) Operators (New admin endpoints required)
Proposed REST endpoints:
- **GET** `api/channels/admin/operators` -> list (filters: `is_active`, `limit`, `offset`)
- **POST** `api/channels/admin/operators` -> create
- **PUT** `api/channels/admin/operators/{auth_user_id}` -> update
- **DELETE** `api/channels/admin/operators/{auth_user_id}` -> soft delete

Suggested data source: `lupo_auth_users` (operator accounts). Keep DB logic minimal, use `is_deleted` and `is_active`.

### 3) Departments (New admin endpoints required)
Proposed REST endpoints:
- **GET** `api/channels/admin/departments`
- **POST** `api/channels/admin/departments`
- **PUT** `api/channels/admin/departments/{department_id}`
- **DELETE** `api/channels/admin/departments/{department_id}`

Suggested data source: `lupo_departments` with soft-delete filters.

### 4) Settings (New admin endpoint required)
Proposed REST endpoint:
- **PUT** `api/channels/admin/settings/{channel_id}`

Suggested data source: `lupo_channels` fields that are safe to update (name, description, status_flag). Avoid changes that impact routing or schema.

## Security and Auth Notes
- Reuse session validation from `channels/1/admin/admin_bootstrap.php`.
- Require actor role in `lupo_actor_channels` or `isAdmin()` for all admin endpoints.
- Enforce CSRF with `X-CSRF-Token` if wired from UI.

## channels_comm.js Wiring (Recommended)
Example usage pattern once endpoints exist:
```js
var comm = new ChannelsCommunication(LUPOPEDIA_PUBLIC_PATH + '/api/channels/admin/', csrfToken);
comm.fetchJson('operators', 'GET', null, function(err, data) { ... });
comm.fetchJson('operators', 'POST', payload, function(err, data) { ... });
```

## Proposed Endpoint Signatures (Draft)
Operators:
- GET `api/channels/admin/operators?limit=25&offset=0&is_active=1`
- POST `api/channels/admin/operators` { username, display_name, email, password, is_active }
- PUT `api/channels/admin/operators/{id}` { display_name, email, is_active }
- DELETE `api/channels/admin/operators/{id}` { deleted_ymdhis }

Departments:
- GET `api/channels/admin/departments`
- POST `api/channels/admin/departments` { name, email, show_dept }
- PUT `api/channels/admin/departments/{id}` { name, email, show_dept }
- DELETE `api/channels/admin/departments/{id}` { deleted_ymdhis }

Settings:
- PUT `api/channels/admin/settings/{channel_id}` { channel_name, description, status_flag }

## Next Steps
1. Implement a small admin API module under `lupo-includes/modules/api/channels-admin-api.php` and route it from `module-loader.php`.
2. Wire `channels_comm.js` to the new endpoints for real CRUD.
3. Add API documentation file: `docs/api/channels_admin_endpoints.md`.