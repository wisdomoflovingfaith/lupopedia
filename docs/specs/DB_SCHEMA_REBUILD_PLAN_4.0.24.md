---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
X-Lupo-File-Path: docs/specs/DB_SCHEMA_REBUILD_PLAN_4.0.24.md
X-Lupo-Version: "4.0.27"
X-Lupo-UTC-Timestamp: "20260222162242"
X-Lupo-Channel: 42   # ANUBIS adoption channel
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool"
X-Lupo-Location: "Sioux Falls, South Dakota, US"
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
web:
  canonical: /specs/DB_SCHEMA_REBUILD_PLAN_4.0.24
  aliases:
    - /docs/DB_SCHEMA_REBUILD_PLAN_4.0.24
    - /qa/DB+SCHEMA+REBUILD+PLAN+4.0.24
  slug: DB_SCHEMA_REBUILD_PLAN_4.0.24
  slug_encoding: underscore
  base_path: /specs
  url_pattern: "/{base}/{slug}"
---

# Database Schema Rebuild Plan 4.0.24

Generated: 2026-02-21T03:08:58.783486Z
TOON Directory: docs/toons
Output Directory: docs/specs/sql

## Crisis Assessment

**TOON Files: 185 tables defined**
**Database: 2 tables exist**
**Missing: 183 tables**

## Existing Tables

The following tables currently exist in the database:

- **lupo_actors** - 10 columns
- **lupo_channels** - 9 columns

## Core Tables to Create First

Critical tables that must exist before any other operations:

- **lupo_dialog_channels** - Essential for system operations
- **lupo_dialog_messages** - Essential for system operations
- **lupo_unified_registry** - Essential for system operations
- **lupo_actor_channels** - Essential for system operations
- **lupo_banned_actors** - Essential for system operations
- **lupo_system_events** - Essential for system operations
- **lupo_actor_departments** - Essential for system operations

## All Remaining Tables by Batch

The following tables will be created in dependency-aware batches:

### Batch 2 (50 tables)

- **lupo_actor_actions**
- **lupo_actor_aliases**
- **lupo_actor_capabilities**
- **lupo_actor_channel_roles**
- **lupo_actor_collections**
- **lupo_actor_conflicts**
- **lupo_actor_edges**
- **lupo_actor_events**
- **lupo_actor_handshakes**
- **lupo_actor_meta**
- **lupo_actor_moods**
- **lupo_actor_object_edges**
- **lupo_actor_persona_relationships**
- **lupo_actor_properties**
- **lupo_actor_reply_templates**
- **lupo_actor_truth_edges**
- **lupo_actors**
- **lupo_agent_context_snapshots**
- **lupo_agent_dependencies**
- **lupo_agent_experiences**
- **lupo_agent_external_events**
- **lupo_agent_faucet_credentials**
- **lupo_agent_faucets**
- **lupo_agent_files**
- **lupo_agent_heartbeats**
- **lupo_agent_properties**
- **lupo_agent_tool_calls**
- **lupo_agent_versions**
- **lupo_agents**
- **lupo_aliases**
- **lupo_analytics_campaign_vars**
- **lupo_analytics_referers_periods**
- **lupo_analytics_visits**
- **lupo_analytics_visits_daily**
- **lupo_analytics_visits_monthly**
- **lupo_analytics_visits_periods**
- **lupo_anubis_deletion_log**
- **lupo_anubis_events**
- **lupo_anubis_mirrored**
- **lupo_anubis_orphaned**
- **lupo_anubis_redirects**
- **lupo_anubis_revised**
- **lupo_api_clients**
- **lupo_api_rate_limits**
- **lupo_api_token_logs**
- **lupo_api_tokens**
- **lupo_api_webhooks**
- **lupo_artifacts**
- **lupo_atoms**
- **lupo_audit_log**

### Batch 3 (50 tables)

- **lupo_auth_audit_log**
- **lupo_auth_providers**
- **lupo_auth_users**
- **lupo_bans_log**
- **lupo_calibration_impacts**
- **lupo_channel_boot_detail**
- **lupo_channel_boot_log**
- **lupo_channel_escalation_rules**
- **lupo_channel_escalations**
- **lupo_channel_files**
- **lupo_channel_log_types**
- **lupo_channel_logs**
- **lupo_channel_state**
- **lupo_channels**
- **lupo_cip_analytics**
- **lupo_cip_propagation_tracking**
- **lupo_cip_trends**
- **lupo_collection_tab_map**
- **lupo_collection_tab_paths**
- **lupo_collection_tabs**
- **lupo_collections**
- **lupo_contents**
- **lupo_contexts**
- **lupo_contexts_map**
- **lupo_crafty_syntax_auto_invite**
- **lupo_crafty_syntax_chat_mod_departments**
- **lupo_crafty_syntax_chat_questions**
- **lupo_crafty_syntax_layer_invites**
- **lupo_crafty_syntax_leave_message**
- **lupo_crafty_user_mapping**
- **lupo_crm_lead_messages**
- **lupo_crm_leads**
- **lupo_department_metadata**
- **lupo_department_roles**
- **lupo_departments**
- **lupo_dialog_threads**
- **lupo_doctrine_evolution_audit**
- **lupo_doctrine_refinements**
- **lupo_document_chunks**
- **lupo_document_embeddings**
- **lupo_documents**
- **lupo_edge_types**
- **lupo_edges**
- **lupo_emotional_constellations**
- **lupo_emotional_frameworks**
- **lupo_emotional_geometry_calibrations**
- **lupo_emotional_stars**
- **lupo_emotional_translations**
- **lupo_entity_edges**
- **lupo_entity_properties**

### Batch 4 (50 tables)

- **lupo_event_log**
- **lupo_event_metadata**
- **lupo_federation_categories**
- **lupo_federation_category_map**
- **lupo_federation_discovery**
- **lupo_federation_nodes**
- **lupo_gov_event_actor_edges**
- **lupo_gov_event_conflicts**
- **lupo_gov_event_dependencies**
- **lupo_gov_event_references**
- **lupo_gov_events**
- **lupo_gov_timeline_nodes**
- **lupo_gov_valuations**
- **lupo_governance_overrides**
- **lupo_hashtags**
- **lupo_help_topics**
- **lupo_help_tree**
- **lupo_hotfix_registry**
- **lupo_human_history_meta**
- **lupo_interface_translations**
- **lupo_interpretation_log**
- **lupo_kapu_events**
- **lupo_kapu_restoration_paths**
- **lupo_labs_declarations**
- **lupo_labs_violations**
- **lupo_legacy_content_mapping**
- **lupo_memory_events**
- **lupo_memory_rollups**
- **lupo_meta_log_events**
- **lupo_metrics_archive_legacy**
- **lupo_modules**
- **lupo_modules_departments**
- **lupo_mood_assignments**
- **lupo_mood_registry**
- **lupo_multi_agent_critique_sync**
- **lupo_notifications**
- **lupo_pack_role_registry**
- **lupo_permissions**
- **lupo_persona_dialogue_patterns**
- **lupo_persona_profiles**
- **lupo_reference_cited_by**
- **lupo_reference_objects**
- **lupo_relationships**
- **lupo_search_index**
- **lupo_search_rebuild_log**
- **lupo_semantic_categories**
- **lupo_semantic_content_views**
- **lupo_semantic_navigation_overview**
- **lupo_semantic_overlays**
- **lupo_semantic_paths**

### Batch 5 (28 tables)

- **lupo_semantic_relationships**
- **lupo_semantic_search_index**
- **lupo_semantic_tags**
- **lupo_semantic_translations**
- **lupo_session_events**
- **lupo_sessions**
- **lupo_system_config**
- **lupo_system_health_snapshots**
- **lupo_system_logs**
- **lupo_tab_events**
- **lupo_temporal_coherence_snapshots**
- **lupo_tldnr**
- **lupo_truth_answers**
- **lupo_truth_evidence**
- **lupo_truth_questions**
- **lupo_truth_questions_map**
- **lupo_truth_relations**
- **lupo_truth_sources**
- **lupo_truth_topics**
- **lupo_unified_analytics_paths**
- **lupo_unified_referers**
- **lupo_unified_truth_items**
- **lupo_unified_unregistry**
- **lupo_unified_visits**
- **lupo_uploads**
- **lupo_user_comments**
- **lupo_world_events**
- **lupo_world_registry**

## Execution Order and Dependencies

1. Execute core migration first
2. Execute batch migrations in order
3. Verify each table after creation
4. Update system configuration

---
*Database Schema Rebuild Plan 4.0.24*
