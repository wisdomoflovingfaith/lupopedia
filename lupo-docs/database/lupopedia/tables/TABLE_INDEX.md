---
lupopedia.headers:
  lupopedia.schema: documentation_index
  file_path_from_root: lupo-docs/database/lupopedia/tables/TABLE_INDEX.md
  web_path: http://www.lupopedia.com/database/lupopedia/tables/TABLE_INDEX
  last_modified_utc: '20260324'
  channel_id: 42
  actor_id: 108
  actor_name: junie
  faucet_name: jetbrains
  delegation_chain: junie:root
  artifact_type: database_documentation
  artifact_kind: index
  purpose: Consolidated index of all 169 database tables (v4.0.86) categorized by
    status and model alignment.
  tags:
  - database
  - index
  - v4.0.86
  - table_registry
  when_updated: '20260324174654'
lupopedia.footer:
  last_verified: '20260324000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: Database Table Index — delegation: junie:root — web_path: http://www.lupopedia.com/database/lupopedia/tables/TABLE_INDEX
# Database Table Index (v4.0.86)

This index provides a comprehensive list of all 169 database tables in the Lupopedia system, categorized by their operational status. All tables follow the **Lupopedia Database Doctrine**: No Foreign Keys, No Datetime/Timestamp (BIGINT YYYYMMDDHHIISS only), and No Triggers.

## 🚀 Active Tables (Canonical Model)

These tables are the core of the **Unified Identity Model** and the **Semantic OS**. They are present in `install_new_lupopedia.sql` and have active TOON definitions.

### Identity Core (Actors, Agents, Users, Departments)
- [lupo_actors](active/lupo_actors.md) — Unified actor identity (paired by department).
- [lupo_agents](active/lupo_agents.md) — AI agent behavioral metadata.
- [lupo_auth_users](active/lupo_auth_users.md) — Human authentication credentials.
- [lupo_departments](active/lupo_departments.md) — Organizational units for orchestration.
- [lupo_actor_auth_users](active/lupo_actor_auth_users.md) — Actor-to-Human pairing (m:n).
- [lupo_actor_departments](active/lupo_actor_departments.md) — Actor department membership.
- [lupo_department_roles](active/lupo_department_roles.md) — Actor roles within departments.
- [lupo_agent_faucets](active/lupo_agent_faucets.md) — Execution surfaces (e.g., Cursor, Windsurf).
- [lupo_agent_faucet_credentials](active/lupo_agent_faucet_credentials.md) — Credentials for faucets.

### Coordination & Orchestration
- [lupo_channels](active/lupo_channels.md) — Workspaces for coordination.
- [lupo_actor_channels](active/lupo_actor_channels.md) — Channel membership.
- [lupo_dialog_threads](active/lupo_dialog_threads.md) — Containers for dialog.
- [lupo_dialog_messages](active/lupo_dialog_messages.md) — Individual messages.
- [lupo_tasks](active/lupo_tasks.md) — Work items and statuses.
- [lupo_projects](active/lupo_projects.md) — Namespace boundaries.

### Semantic Layer & Edges
- [lupo_edges](active/lupo_edges.md) — Relationships between entities.
- [lupo_artifacts](active/lupo_artifacts.md) — File-based semantic objects.
- [lupo_metadata](active/lupo_metadata.md) — Property key-value pairs.
- [lupo_atoms](active/lupo_atoms.md) — System-wide constants and atoms.

### Log & Audit (Unified Logging)
- [lupo_unified_log](active/lupo_unified_log.md) — Consolidated system events.
- [lupo_audit_log](active/lupo_audit_log.md) — Administrative audit trail.
- [lupo_auth_audit_log](active/lupo_auth_audit_log.md) — Security and login audit.

## 🛠️ In Development Tables

These tables represent upcoming features or layers currently under active implementation.

- [lupo_anubis_operations](in_development/lupo_anubis_operations.md)
- [lupo_agent_experience](in_development/lupo_agent_experience.md)
- [lupo_action_authorization](in_development/lupo_action_authorization.md)
- [lupo_channel_boot_lifecycle](in_development/lupo_channel_boot_lifecycle.md)

## 📝 Planning & Draft Tables

These tables are defined in TOONs but are not yet implemented in the core runtime.

- [lupo_federated_trust](planning/lupo_federated_trust.md)
- [lupo_calibration_impacts](planning/lupo_calibration_impacts.md)
- [lupo_orchestrator_rules](planning/lupo_orchestrator_rules.md)

---
*Note: This index covers 169 tables. For specific column details and join patterns, refer to the individual Markdown files.*
