---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/database/audit_log.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
---

# lupo_audit_log

**Purpose:** **Audit trail** for actor and system actions: entity_type, entity_id, event_type, optional payload_json, and timestamps. Used for operator history, login events, and other auditable actions. No foreign keys; entity references are application-managed.

**Schema:** See `docs/toons/lupo_audit_log.toon.json`. Column names (e.g. entity_type, entity_id, event_type, payload_json, created_ymdhis) must match the TOON.

---

## Use and need

- **Operator history:** Legacy “operator did X” events map to entity_type = 'actor', entity_id = operator actor_id, event_type = action type, payload_json for session or context.
- **Compliance and debugging:** Queries by entity_id or event_type for support and audits.
- **Timestamps:** BIGINT UTC YmdHis set in application code.

---

## Mapping from Crafty Syntax

**Legacy table:** `livehelp_operator_history`.

**Migration:** `docs/doctrine/migrations/livehelp_operator_history_migration.md`, `import_from_old_crafty_syntax.sql`. Legacy opid → entity_id, entity_type = 'actor', event_type and payload from legacy action/session. livehelp_operator_history → IMPORTED → DROPPED.
