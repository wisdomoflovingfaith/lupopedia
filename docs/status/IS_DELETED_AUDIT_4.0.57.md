# is_deleted Filter Audit (v4.0.57)

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/status/IS_DELETED_AUDIT_4.0.57
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "report"
  file_path_from_root: "docs/status/IS_DELETED_AUDIT_4.0.57.md"
  last_modified_utc: "20260306"
  system_version: "4.0.57"
  channel_id: 42
  actor_id: 1003
  purpose: "Application audit: SELECTs on tables with is_deleted (R4)"
  artifact_type: "report"
  artifact_kind: "analysis"
  lupo_agent: "cursor"
flare.footer:
  last_verified: "20260306"
  last_verified_by: "cursor"
---

## Scope

- **lupo-includes/** (PHP): SELECTs and JOINs on tables that have `is_deleted` (and optionally `deleted_ymdhis`).
- **app/** (PHP): No direct `is_deleted` references found; DB access is via lupo-includes or services.

## Findings

All reviewed SELECT/JOIN usages in **lupo-includes** that reference tables with `is_deleted` include a filter:

- `is_deleted = 0`, or  
- `(is_deleted = 0 OR is_deleted IS NULL)` (for backward compatibility where column may be nullable).

**Sample files audited:** AdminActorStatusHandler, AdminUsersHandler, AdminRegistryHandler, AdminChannelsHandler, AdminDepartmentsHandler, AdminLeadsHandler, module-loader, auth-controller, content-model, content-controller, edge-controller, channel-send-api, actors-controller, channels-admin-api, operator-accept-visitor-api, operator-pending-visitors-api, crafty_syntax (livehelp-js, visitor-session-helper, visitor-chat-stream, choosedepartment), ANUBIS_Resolver, upload-handler, ban_gate, help-model, truth-controller, list-controller, iris, Migration models, EmergentRoleDiscovery, etc.

**Conclusion:** No gaps identified. Soft-delete consistency is maintained in the audited paths. No schema change required.

## Recommendation

Continue to add `WHERE is_deleted = 0` (or equivalent) for any new SELECTs on tables that define `is_deleted`. Prefer a single convention per table (0 = not deleted; NULL not used for new tables per doctrine).

---
**Report generated:** 2026-03-06 | **Actor ID:** 1003 (Cursor)
