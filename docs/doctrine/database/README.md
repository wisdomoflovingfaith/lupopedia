# Lupopedia Database Tables (Doctrine)

This folder contains **per-table doctrine** for Lupopedia tables that are migration targets or central to the Crafty Syntax 3.7.5 → Lupopedia 4.0.x upgrade path. Each file describes the table’s purpose, how it is used, and how it was mapped from legacy Crafty Syntax tables.

**Schema source of truth:** `docs/toons/*.toon.json` (TOON files). Column names, types, and keys must match the TOONs. Cursor must not guess or invent schema; see `.cursor/rules/toon-source-of-truth.mdc`.

**Legacy mapping source:** `docs/doctrine/migrations/`. Each migration file (e.g. `livehelp_users_migration.md`) describes one or more legacy tables and their replacement in Lupopedia. The index is **MIGRATION_MAPPING_REFERENCE.md**.

---

## 3-level permission model (no lupo_operators)

Lupopedia does **not** have a `lupo_operators` table. Permissions use a **3-level role system**:

1. **Channel roles** — `lupo_actor_channel_roles` (role_key: captain, administrator, monitor). Channel-scoped.
2. **Department roles** — `lupo_department_roles`. Department-scoped.
3. **System** — department_id = 0 (global admin). Reserved.

Resolution order: channel → department → system. See **docs/doctrine/migrations/operator_to_roles_migration.md** and **docs/audits/OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md**.

---

## Table docs in this folder

| Table (lupo_*) | Doc file | Legacy source(s) |
|----------------|----------|-------------------|
| lupo_auth_users | [auth_users.md](auth_users.md) | livehelp_users |
| lupo_actors | [actors.md](actors.md) | livehelp_users (operators only); anonymous users are not in actors (sessions only) |
| lupo_actor_departments | [actor_departments.md](actor_departments.md) | livehelp_operator_departments |
| lupo_actor_channel_roles | [actor_channel_roles.md](actor_channel_roles.md) | (replaces operator–channel assignment; no direct legacy table) |
| lupo_departments | [departments.md](departments.md) | livehelp_departments |
| lupo_channels | [channels.md](channels.md) | (new; legacy livehelp_channels / livehelp_operator_channels dropped) |
| lupo_sessions | [sessions.md](sessions.md) | (replaces livehelp_sessions; no import) |
| lupo_dialog_threads | [dialog_threads.md](dialog_threads.md) | livehelp_transcripts |
| lupo_dialog_messages | [dialog_messages.md](dialog_messages.md) | livehelp_transcripts |
| lupo_crm_leads | [crm_leads.md](crm_leads.md) | livehelp_leads |
| lupo_crm_lead_messages | [crm_lead_messages.md](crm_lead_messages.md) | livehelp_emails |
| lupo_audit_log | [audit_log.md](audit_log.md) | livehelp_operator_history |
| lupo_crafty_syntax_auto_invite | [crafty_syntax_auto_invite.md](crafty_syntax_auto_invite.md) | livehelp_autoinvite |
| lupo_actor_reply_templates | [actor_reply_templates.md](actor_reply_templates.md) | livehelp_quick |
| lupo_federation_nodes | [federation_nodes.md](federation_nodes.md) | livehelp_websites |

For the full mapping list see **docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md**.
