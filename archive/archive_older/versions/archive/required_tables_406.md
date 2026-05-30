# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/versions/REQUIRED_TABLES_4.0.6.md"
  file_hash: "1d070e647f8a96e8ccf37aed4d1d8aa0c9896a912c9435daf6356365b01d8fa2"
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
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\REQUIRED_TABLES_4.0.6.md"
  file_hash: "f6f319c7be39f92eb5f81f3a22fb5548571c18cba830e47dffeb252559de3f3d"
  file_path_from_root: "docs\REQUIRED_TABLES_4.0.6.md"
  file_hash: "b2ee2dcdab61c0f67faa82f25bc09e4c2b8e8079222eb6c0b27820d644113367"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Required Tables for Lupopedia 4.0.6 (Patch-Only)"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "required_tables_406md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Required Tables for Lupopedia 4.0.6 (Patch-Only)

**Version:** 4.0.6 (patch-only; no 4.1.x in this document)  
**Canonical install:** `database/migrations/install_new_lupopedia.sql`  
**Future-features definitions:** `database/migrations/future_features_lupopedia.sql`  
**Upgrade path:** Crafty Syntax 3.7.5 → Lupopedia 4.0.x (ONLY supported path)

---

## Doctrine: Required vs Future Features

- **Required tables** = All tables referenced in `import_from_old_crafty_syntax.sql` **plus** all tables used by active PHP (repositories, services, controllers, models), the wizard/installer, seed logic, and runtime features that are actually implemented. Required tables are created only by `install_new_lupopedia.sql`. No required table may be removed or renamed; no required table may be moved into `future_features_lupopedia.sql`.
- **Future features tables** = Tables that are **not** required: not referenced in the importer, not used by active PHP/seed/wizard/runtime. Their `CREATE TABLE` (and indexes) live in `future_features_lupopedia.sql`. They are not created during standard install; they are reserved for future development.
- **Importer protection:** No table that appears in `import_from_old_crafty_syntax.sql` may ever be removed or moved to `future_features_lupopedia.sql`.

---

## Session, Roles, and Scope

- **Session table:** `{prefix}sessions`. The table `{prefix}sessions` is obsolete and has been removed from the install.
- **Roles (3-layer model):** (1) Channel roles (`{prefix}actor_channel_roles`: captain, administrator, monitor); (2) Department roles (`{prefix}department_roles`: administrator for channel's department); (3) System roles (department_id = 0: administrator = global admin). Resolution order: channel → department → system. If any match → permission granted. **NO lupo_channel_roles** (removed in 4.0.6).
- **Organizational scope:** The sole organizational unit is the **department**. Use `{prefix}departments` and `{prefix}actor_departments`. Department 0 is reserved (system department); not user-selectable. Permissions (`{prefix}permissions`) use `department_id`; do not use `{prefix}groups` or `{prefix}actor_group_membership` (removed).

---

## Required Crafty Syntax Compatibility Tables (Importer)

These tables are targets of `import_from_old_crafty_syntax.sql`. They **must** remain in `install_new_lupopedia.sql` and must never be moved to future_features.

- lupo_actor_departments
- lupo_actor_reply_templates
- lupo_audit_log
- lupo_auth_users
- lupo_collection_tabs
- lupo_collections
- lupo_crafty_syntax_auto_invite
- lupo_crafty_syntax_chat_questions
- lupo_crafty_syntax_layer_invites
- lupo_crafty_syntax_leave_message
- lupo_crm_lead_messages
- lupo_department_metadata
- lupo_departments
- lupo_dialog_messages
- lupo_dialog_threads
- lupo_federation_nodes
- lupo_truth_answers
- lupo_truth_knowledge
- lupo_analytics_paths
- lupo_referers
- lupo_visits

*(lupo_crm_leads and lupo_modules are also importer targets; they appear in Required Lupopedia Core below.)*

---

## Required Lupopedia Core Tables

*(List derived from `database/lupopedia/mysql/install/install_new_lupopedia.sql`. Table count = number of CREATE TABLE statements in that file. lupo_channel_roles removed in 4.0.6; lupo_system_health_snapshots removed from install, DDL in future_features.)*

- lupo_actor_actions
- lupo_actor_capabilities
- lupo_actor_channel_roles
- lupo_actor_channels
- lupo_actor_collections
- lupo_actor_conflicts
- lupo_actor_departments
- lupo_actor_edges
- lupo_actor_handshakes
- lupo_actor_history
- lupo_actor_moods
- lupo_actor_object_edges
- lupo_actor_persona_relationships
- lupo_actor_relationship_rules
- lupo_actor_reply_templates
- lupo_actor_truth_edges
- lupo_actors
- lupo_agent_context_snapshots
- lupo_agent_dependencies
- lupo_agent_experiences
- lupo_agent_external_events
- lupo_agent_faucet_credentials
- lupo_agent_faucets
- lupo_agent_files
- lupo_agent_heartbeats
- lupo_agent_tool_calls
- lupo_agent_versions
- lupo_agents
- lupo_analytics_campaign_vars
- lupo_analytics_paths
- lupo_analytics_referers_periods
- lupo_analytics_visits
- lupo_analytics_visits_daily
- lupo_analytics_visits_monthly
- lupo_anubis_deletion_log
- lupo_anubis_events
- lupo_anubis_log
- lupo_anubis_mirrored
- lupo_anubis_redirects
- lupo_anubis_revised
- lupo_api_clients
- lupo_api_rate_limits
- lupo_api_token_logs
- lupo_api_tokens
- lupo_api_webhooks
- lupo_artifact_chunks
- lupo_artifacts
- lupo_atoms
- lupo_audit_log
- lupo_auth_audit_log
- lupo_auth_providers
- lupo_auth_users
- lupo_banned_actors
- lupo_bans_log
- lupo_calibration_impacts
- lupo_capability_usage
- lupo_channel_boot_detail
- lupo_channel_boot_detail_lifecycle
- lupo_channel_boot_lifecycle
- lupo_channel_boot_log
- lupo_channel_content
- lupo_channel_escalation_rules
- lupo_channel_escalations
- lupo_channel_files
- lupo_channel_state
- lupo_channels
- lupo_cip_analytics
- lupo_cip_propagation_tracking
- lupo_cip_trends
- lupo_collection_tab_map
- lupo_collection_tab_paths
- lupo_collection_tabs
- lupo_collections
- lupo_comments
- lupo_contents
- lupo_contexts
- lupo_contexts_map
- lupo_crafty_syntax_auto_invite
- lupo_crafty_syntax_chat_mod_departments
- lupo_crafty_syntax_chat_questions
- lupo_crafty_syntax_layer_invites
- lupo_crafty_syntax_leave_message
- lupo_crafty_user_mapping
- lupo_crm_lead_messages
- lupo_crm_leads
- lupo_department_metadata
- lupo_department_roles
- lupo_departments
- lupo_dialog_channels
- lupo_dialog_messages
- lupo_dialog_threads
- lupo_doctrine_evolution_audit
- lupo_document_embeddings
- lupo_edges
- lupo_emotional_constellations
- lupo_emotional_frameworks
- lupo_emotional_geometry_calibrations
- lupo_emotional_stars
- lupo_emotional_translations
- lupo_entity_properties
- lupo_event_metadata
- lupo_federated_trust
- lupo_federation_categories
- lupo_federation_category_map
- lupo_federation_discovery
- lupo_federation_nodes
- lupo_flare_headers
- lupo_gov_event_actor_edges
- lupo_gov_event_conflicts
- lupo_gov_event_dependencies
- lupo_gov_event_references
- lupo_gov_events
- lupo_gov_timeline_nodes
- lupo_gov_valuations
- lupo_governance_overrides
- lupo_hashtags
- lupo_help_topics
- lupo_help_tree
- lupo_hotfix_registry
- lupo_human_history_meta
- lupo_interface_translations
- lupo_interpretation_log
- lupo_kapu_events
- lupo_kapu_restoration_paths
- lupo_labs_declarations
- lupo_labs_violations
- lupo_legacy_content_mapping
- lupo_llm_performance
- lupo_memory_rollups
- lupo_metadata
- lupo_metrics_archive_legacy
- lupo_modules
- lupo_modules_departments
- lupo_mood_assignments
- lupo_mood_registry
- lupo_multi_agent_critique_sync
- lupo_notifications
- lupo_pack_role_registry
- lupo_permissions
- lupo_persona_dialogue_patterns
- lupo_persona_profiles
- lupo_reference_cited_by
- lupo_reference_objects
- lupo_referers
- lupo_registry
- lupo_registry_import
- lupo_registry_open
- lupo_search_index
- lupo_search_rebuild_log
- lupo_semantic_index
- lupo_session_recovery
- lupo_sessions
- lupo_system_commands
- lupo_system_config
- lupo_task_assignments
- lupo_task_dependencies
- lupo_tasks
- lupo_ticket_messages
- lupo_tickets
- lupo_truth_answers
- lupo_truth_knowledge
- lupo_unified_log
- lupo_uploads
- lupo_visits
- lupo_world_registry

---

## Future Features Tables (in future_features_lupopedia.sql)

These tables are **not** created by `install_new_lupopedia.sql`. Their definitions live in `database/lupopedia/mysql/install/future_features_lupopedia.sql` only; run that file separately if needed.

- lupo_aliases
- lupo_anubis_orphaned
- lupo_system_health_snapshots
- lupo_temporal_coherence_snapshots
- lupo_tldnr

---

*See also: `docs/audits/FUTURE_FEATURES_AND_REQUIRED_TABLES_ALIGNMENT_SUMMARY.md`.*
