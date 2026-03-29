---
lupopedia.headers:
  lupopedia.schema: documentation_index
  file_path_from_root: lupo-docs/database/lupopedia/tables/TABLE_INDEX.md
  web_path: http://www.lupopedia.com/database/lupopedia/tables/TABLE_INDEX
  last_modified_utc: '20260325125423'
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
  when_updated: '20260325125423'
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: '20260325125423'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: Database Table Index â€” delegation: junie:root â€” web_path: http://www.lupopedia.com/database/lupopedia/tables/TABLE_INDEX
# Database Table Index (v4.0.86)

This index provides a comprehensive list of all 169 database tables in the Lupopedia system, categorized by their operational status. All tables follow the **Lupopedia Database Doctrine**: No Foreign Keys, No Datetime/Timestamp (BIGINT YYYYMMDDHHIISS only), and No Triggers.

## ðŸš€ Active Tables (Canonical Model)

These tables are the core of the **Unified Identity Model** and the **Semantic OS**. They are present in `install_new_lupopedia.sql` and have active TOON definitions.

### Identity Core (Actors, Agents, Users, Departments)
- [lupo_actors](active/lupo_actors.md) â€” Unified actor identity (paired by department).
- [lupo_agents](active/lupo_agents.md) â€” AI agent behavioral metadata.
- [lupo_auth_users](active/lupo_auth_users.md) â€” Human authentication credentials.
- [lupo_departments](active/lupo_departments.md) â€” Organizational units for orchestration.
- [lupo_actor_auth_users](active/lupo_actor_auth_users.md) â€” Actor-to-Human pairing (m:n).
- [lupo_actor_departments](active/lupo_actor_departments.md) â€” Actor department membership.
- [lupo_department_roles](active/lupo_department_roles.md) â€” Actor roles within departments.
- [lupo_agent_faucets](active/lupo_agent_faucets.md) â€” Execution surfaces (e.g., Cursor, Windsurf).
- [lupo_agent_faucet_credentials](active/lupo_agent_faucet_credentials.md) â€” Credentials for faucets.

### Coordination & Orchestration
- [lupo_channels](active/lupo_channels.md) â€” Workspaces for coordination.
- [lupo_actor_channels](active/lupo_actor_channels.md) â€” Channel membership.
- [lupo_dialog_threads](active/lupo_dialog_threads.md) â€” Containers for dialog.
- [lupo_dialog_messages](active/lupo_dialog_messages.md) â€” Individual messages.
- [lupo_tasks](active/lupo_tasks.md) â€” Work items and statuses.
- [lupo_projects](active/lupo_projects.md) â€” Namespace boundaries.

### Semantic Layer & Edges
- [lupo_edges](active/lupo_edges.md) â€” Relationships between entities.
- [lupo_edge_types](active/lupo_edge_types.md) â€” Canonical runtime edge type registry.
- [lupo_edge_type_definitions](active/lupo_edge_type_definitions.md) â€” Edge type constraint/semantics registry.
- [lupo_artifacts](active/lupo_artifacts.md) â€” File-based semantic objects.
- [lupo_metadata](active/lupo_metadata.md) â€” Property key-value pairs.
- [lupo_atoms](active/lupo_atoms.md) â€” System-wide constants and atoms.
- [lupo_edge_surface_usage_baseline_4_0_87](active/lupo_edge_surface_usage_baseline_4_0_87.md) â€” 4.0.87 usage map, callsite matrix, baseline counts, duplicate-candidate baseline, and header edge-type clarification.

### Log & Audit (Unified Logging)
- [lupo_unified_log](active/lupo_unified_log.md) â€” Consolidated system events.
- [lupo_audit_log](active/lupo_audit_log.md) â€” Administrative audit trail.
- [lupo_auth_audit_log](active/lupo_auth_audit_log.md) â€” Security and login audit.

## ðŸ› ï¸ In Development Tables

These tables represent upcoming features or layers currently under active implementation.

- [lupo_anubis_operations](in_development/lupo_anubis_operations.md)
- [lupo_agent_experience](in_development/lupo_agent_experience.md)
- [lupo_action_authorization](in_development/lupo_action_authorization.md)
- [lupo_channel_boot_lifecycle](in_development/lupo_channel_boot_lifecycle.md)

## ðŸ“ Planning & Draft Tables

These tables are defined in TOONs but are not yet implemented in the core runtime.

- [lupo_federated_trust](planning/lupo_federated_trust.md)
- [lupo_orchestrator_rules](planning/lupo_orchestrator_rules.md)

---
*Note: This index covers 169 tables. For specific column details and join patterns, refer to the individual Markdown files.*

