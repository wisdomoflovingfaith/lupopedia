# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/actors/actor_id/1007/20260227_channels_admin_next_steps_report.md"
  file_hash: "f95422896dd771219d798cc00472f38ef227a65188b92f528720072585fef8aa"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
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

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  file_path_from_root: "lupo-actors/1007/20260227_channels_admin_next_steps_report.md"
  file_hash: "d3bbff4cc73678f039916cc6c3ee1a9b3f83cfd750d9e338b342b79cb98ed746"
  system_version: "4.0.50"
  channel_id: 42
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "report"
  purpose: "Next steps report for channels admin interface modernization after API module implementation"
  dialog_message: "Codex: Channels admin API is live; document the next execution steps for UI wiring, validation, and tests."
  mood_vector: "4169E1"
  artifact_kind: "next_steps"
  traits: ["channels", "admin", "api", "next_steps"]
  tags: ["channels", "admin", "api", "4.0.49", "next_steps"]
  lupo_agent: "jetbrains"

  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  outbound_edges:
    - { to: "lupo-includes/modules/api/channels-admin-api.php", type: "references", weight: 0.9, reason: "Admin API module" }
    - { to: "lupo-channels/1/index.php", type: "references", weight: 0.8, reason: "Admin shell entry" }
    - { to: "lupo-channels/1/assets/js/channels_comm.js", type: "references", weight: 0.8, reason: "JS comm helpers" }
    - { to: "lupo-docs/api/channels_admin_endpoints.md", type: "references", weight: 0.7, reason: "Endpoint documentation" }
  semantic_tags: ["channels_admin", "next_steps", "api", "modernization"]
  last_verified: "20260227"
  last_verified_by: "lupopedia"
---

# Channels Admin Modernization � Next Steps (Post-API)

## Status Snapshot
- Admin shell and iframe UI are in place.
- Channels admin API module is routed and live under `/api/channels/admin/*`.
- JS comm helpers are available via `ChannelsCommunication`.

## Immediate Next Steps (UI Wiring)
1. **Operators panel** (`lupo-channels/1/admin/operators.php`):
   - Replace placeholder rows with API-driven list via `CHANNELS_ADMIN_COMM.listOperators()`.
   - Add create/update/delete controls that call `createOperator`, `updateOperator`, `deleteOperator`.
   - Include channel role assignment UI (role_key) and map to API payload.

2. **Departments panel** (`lupo-channels/1/admin/departments.php`):
   - Fetch list via `listDepartments()` and render rows.
   - Add create/update/delete flows with validation (name, department_type).

3. **Settings panel** (`lupo-channels/1/admin/settings.php`):
   - Load current channel settings via `getSettings(channel_id)`.
   - Patch edits via `updateSettings(channel_id, payload)`.

## Security + Validation
- Enforce CSRF token in all write calls (already injected into `window.CHANNELS_ADMIN_CSRF`).
- Add server-side validation for username/email length and valid role keys.
- Ensure all updates enforce `is_deleted = 0` filters for reads.

## Operational Gaps
- Add audit log visibility in UI (use `lupo_audit_log` if needed).
- Add error feedback in the admin panels (display API error codes/messages).
- Confirm actor permissions by channel role (`lupo_actor_channel_roles`).

## Testing Checklist
- Operators CRUD: create, update, disable; verify actor and role rows.
- Departments CRUD: create, update, disable.
- Settings update: name/description/status flag.
- CSRF failures return 419 JSON errors.
- Unauthorized access returns 401/403 JSON errors.

## Optional Enhancements
- Wire live chat monitoring using existing `/api/channel/messages` and `/api/channel/check`.
- Add keyset pagination for operators and departments.
- Add basic health widget (counts of active operators, open dialogs).
