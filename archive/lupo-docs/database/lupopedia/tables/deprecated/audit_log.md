---
lupopedia.headers:
  file_path_from_root: "lupo-docs/database/lupopedia/tables/deprecated/audit_log.md"
  file_hash: "5c7488a1f39139af1c714488f099c35c4a95b5080cf29df2198c5ffce922b609"
  system_version: "4.0.50"
  channel_id: 0
  actor_id: 1006
  created_ymdhis: 20260226204058
  updated_ymdhis: 20260226204058
  artifact_type: "table_documentation"
  purpose: "General audit trail for actor and system actions"
  lupo_agent: "gemini-cli"

lupopedia.edges:
  file_path_from_root: "lupo-docs\database\lupopedia\tables\audit_log.md"
  outbound_edges:
- { to: "lupo-docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.8, reason: "Actor identity attribution" }
    - { to: "lupo-database/lupopedia/toon/lupo_audit_log.toon.json", type: "schema_reference", weight: 1.0 }
  semantic_tags: ["audit", "log", "security", "history"]

  delegation_chain: null
  needs_review: ["delegation_chain"]
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260226"
  last_verified_by: "gemini-cli"
---

# Database Documentation: lupo_audit_log
## Version: 4.0.46
## Date: 2026-02-26

### 1. Overview
Purpose: **Audit trail** for actor and system actions: entity_type, entity_id, event_type, optional payload_json, and timestamps. Used for operator history, login events, and other auditable actions. No foreign keys; entity references are application-managed.

**Schema:** See `lupo-database/lupopedia/toon/lupo_audit_log.toon.json`. Column names (e.g. entity_type, entity_id, event_type, payload_json, created_ymdhis) must match the TOON.

### 2. Core Workflows

- **Operator history:** Legacy â€œoperator did Xâ€ events map to entity_type = 'actor', entity_id = operator actor_id, event_type = action type, payload_json for session or context.
- **Compliance and debugging:** Queries by entity_id or event_type for support and audits.
- **Timestamps:** BIGINT UTC YmdHis set in application code.

### 3. Mapping from Crafty Syntax

**Legacy table:** `livehelp_operator_history`.

**Migration:** `lupo-docs/doctrine/migrations/livehelp_operator_history_migration.md`, `import_from_old_crafty_syntax.sql`. Legacy opid â†’ entity_id, entity_type = 'actor', event_type and payload from legacy action/session. livehelp_operator_history â†’ IMPORTED â†’ DROPPED.

---
*Maintained by GEMINI (Actor 1006)*

