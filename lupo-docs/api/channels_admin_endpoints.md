# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\api\channels_admin_endpoints.md"
  file_hash: "700c7f1526c46af05ecd990261b2d9d0dd95885214e3d83c3159bbd39dd82e95"
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
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  file_path_from_root: "lupo-docs/api/channels_admin_endpoints.md"
  file_hash: "22619a63a66ed3efe03a8eecb6c2511400896dc1c0cb99e2cdbfeeaecb09afa6"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "api"
  purpose: "Proposed channels admin API endpoints for operator, department, and settings management"
  dialog_message: "Channels admin API module implemented with operator, department, and settings endpoints."
  mood_rgb: "4169E1"
  artifact_kind: "api_reference"
  traits: ["api", "channels", "admin_interface"]
  tags: ["api", "channels", "admin", "4.0.49"]
  lupo_agent: "codex-ide"

  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  outbound_edges:
    - { to: "lupo-includes/modules/module-loader.php", type: "references", weight: 0.8, reason: "API routing" }
    - { to: "lupo-channels/1/assets/js/channels_comm.js", type: "references", weight: 0.7, reason: "JS comm layer" }
  semantic_tags: ["api", "channels", "admin"]
  last_verified: "20260227"
  last_verified_by: "codex-ide"
---

# Channels Admin API Endpoints (Implemented)

**Status:** Implemented in `lupo-includes/modules/api/channels-admin-api.php` and routed via `lupo-includes/modules/module-loader.php`.

## Base Path
`/api/channels/admin/`

## Operators
- **GET** `/api/channels/admin/operators?channel_id=1&limit=25&offset=0`
- **POST** `/api/channels/admin/operators`
- **PUT** `/api/channels/admin/operators/{auth_user_id}`
- **DELETE** `/api/channels/admin/operators/{auth_user_id}`

## Departments
- **GET** `/api/channels/admin/departments`
- **POST** `/api/channels/admin/departments`
- **PUT** `/api/channels/admin/departments/{department_id}`
- **DELETE** `/api/channels/admin/departments/{department_id}`

## Settings
- **GET** `/api/channels/admin/settings/{channel_id}`
- **PUT** `/api/channels/admin/settings/{channel_id}`

## Security
- Session-based auth via `lupo_sessions`
- Actor role check in `lupo_actor_channels` or admin role
- CSRF token required for write operations

## Notes
- All endpoints must respect `is_deleted = 0` filters by default.
- Use `updated_ymdhis` for mutation timestamps.
- No foreign keys, triggers, or stored procedures.
