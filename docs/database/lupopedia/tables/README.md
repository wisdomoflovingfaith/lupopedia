# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\database\lupopedia\tables\README.md"
  file_hash: "6efa3c090319077177df11150127dd8fe7e6ba7bd69023e66dc3154ba386cc2b"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for README.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "database", "lupopedia", "tables", "readmemd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
flare.headers: {
  file_path_from_root: "docs/database/lupopedia/tables/README.md",
  system_version: "4.0.48",
  channel_id: 42,
  actor_id: 1003,
  last_modified_utc: "20260227",
  delegation_chain: "10000:1003",
  artifact_type: "documentation",
  purpose: "Index and overview for Lupopedia database table documentation",
  mood_rgb: "00FF00",
  traits: ["canonical", "documentation", "index", "v4.0.48", "history-update"],
  tags: ["database", "schema", "documentation", "index", "history-update"],
  lupo_agent: "antigravity"
}
flare.edges: {
  outbound_edges: [
    { to: "docs/channels/appendix/HISTORY.md", type: "references", weight: 1.0 },
    { to: "docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 1.0 }
  ],
  semantic_tags: ["database_index", "schema_documentation"]
}
flare.footer: {
  last_verified_utc: "20260227",
  last_verified_by: "antigravity"
}
---

# Lupopedia Database Tables (Doctrine)

This folder contains **per-table doctrine** for Lupopedia tables that are migration targets or central to the Crafty Syntax 3.7.5 → Lupopedia 4.0.x upgrade path. Each file describes the table’s purpose, how it is used, and how it was mapped from legacy Crafty Syntax tables.

**Schema source of truth:** `docs/toons/*.toon.json` (TOON files). Column names, types, and keys must match the TOONs.

> [!NOTE]
> For historical context on why legacy tables are deprecated and the project's evolution from 2002, see the [Full Project History](../../../channels/appendix/HISTORY.md).

---

## Migration Mapping Reference

Legacy mapping details were previously in `docs/doctrine/migrations/` but have been consolidated here for technical reference. The central index is **[MIGRATION_MAPPING_REFERENCE.md](MIGRATION_MAPPING_REFERENCE.md)**.

---

## 3-level permission model (no lupo_operators)

Lupopedia does **not** have a `lupo_operators` table. Permissions use a **3-level role system**:

1. **Channel roles** — `lupo_actor_channel_roles` (role_key: captain, administrator, monitor). Channel-scoped.
2. **Department roles** — `lupo_department_roles`. Department-scoped.
3. **System** — department_id = 0 (global admin). Reserved.

Resolution order: channel → department → system. See **[operator_to_roles_migration.md](operator_to_roles_migration.md)** and **docs/audits/OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md**.

---

## Table docs in this folder

| Table (lupo_*) | Doc file | Legacy source(s) |
|----------------|----------|-------------------|
| lupo_auth_users | [auth_users.md](auth_users.md) | livehelp_users |
| lupo_actors | [lupo_actors.md](lupo_actors.md) | livehelp_users (operators only) |
| lupo_actor_departments | [actor_departments.md](actor_departments.md) | livehelp_operator_departments |
| lupo_actor_channel_roles | [actor_channel_roles.md](actor_channel_roles.md) | (new; legacy operator–channel assignment replaced) |
| lupo_departments | [departments.md](departments.md) | livehelp_departments |
| lupo_channels | [channels.md](channels.md) | (new functionality) |
| lupo_sessions | [sessions.md](sessions.md) | (replaces livehelp_sessions) |
| lupo_dialog_threads | [lupo_dialog_threads.md](lupo_dialog_threads.md) | livehelp_transcripts |
| lupo_dialog_messages | [lupo_dialog_messages.md](lupo_dialog_messages.md) | livehelp_transcripts |
| lupo_crm_leads | [crm_leads.md](crm_leads.md) | livehelp_leads |
| lupo_crm_lead_messages | [crm_lead_messages.md](crm_lead_messages.md) | livehelp_emails |
| lupo_audit_log | [audit_log.md](audit_log.md) | livehelp_operator_history |
| lupo_crafty_syntax_auto_invite | [crafty_syntax_auto_invite.md](crafty_syntax_auto_invite.md) | livehelp_autoinvite |
| lupo_actor_reply_templates | [actor_reply_templates.md](actor_reply_templates.md) | livehelp_quick |
| lupo_federation_nodes | [federation_nodes.md](federation_nodes.md) | livehelp_websites |

---
*Maintained by Antigravity (Actor 1003)*
