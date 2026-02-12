⧉ WOLFIE v2.6 ⧉
nav: mechanical | mythic | docs

## NAVIGATION (grep-first)
pkg: lupopedia
mod: release
asp: requirements
purpose: Canonical list of required tables for Lupopedia 4.1.0 release

modified: 2026-02-02
epoch: wolfie-winter-2026
signature: cascade

## RELATIONS
→ TABLE_CEILING_DEFENSE_PLAN.md
→ craftysyntax_to_lupopedia_mysql.sql

## DOCS
@requires: Table consolidation plan
@requires: Migration scripts
@note: Target release date: 2026-02-22

---

# Required Tables for Lupopedia 4.1.0 Release

**Version:** 4.1.0  
**Target Release Date:** 2026-02-22  
**Table Ceiling:** 222 (hard limit)  
**Current Status:** Requirements definition phase  

**Required Crafty Syntax Compatibility Tables:** 21  
**Required Lupopedia Core Tables:** 201  
**Optional Tables:** 39  
**Total Tables:** 261  
**Ceiling Status:** 39 over ceiling - requires consolidation

**Session table:** The single session table is `{prefix}sessions`. The table `{prefix}unified_sessions` is obsolete and has been removed from the install; its logic was merged into `{prefix}sessions`.

**Roles:** Roles are channel-scoped only. The only role table is `{prefix}channel_roles` (actor_id + channel_id → role_type). The table `{prefix}actor_roles` is **DROPPED**; do not create or reference it. Use `{prefix}channel_roles` with default channel_id = 1 for system-wide permissions.

**Organizational scope:** The sole organizational and permission-bearing unit is the **department**. Tables `{prefix}groups` and `{prefix}actor_group_membership` are **REMOVED**; do not create or reference them. Use `{prefix}departments` and `{prefix}actor_departments` for scope. Permissions (`{prefix}permissions`) use `department_id` (not group_id); permission is satisfied if user_id OR department_id (actor's departments) OR channel_roles grant.

---

## Required Crafty Syntax Compatibility Tables

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
- lupo_truth_questions
- lupo_unified_analytics_paths
- lupo_unified_referers
- lupo_unified_visits

---

## Required Lupopedia Core Tables

- lupo_actor_actions
- lupo_actor_capabilities
- lupo_actor_channel_roles
- lupo_actor_channels
- lupo_actor_collections
- lupo_actor_conflicts
- lupo_actor_edges
- lupo_actor_events
- lupo_actor_handshakes
- lupo_actor_meta
- lupo_actor_moods
- lupo_actor_object_edges
- lupo_actor_persona_relationships
- lupo_actor_properties
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
- lupo_agent_properties
- lupo_agent_registry
- lupo_agent_tool_calls
- lupo_agent_versions
- lupo_agents
- lupo_analytics_campaign_vars
- lupo_analytics_visits
- lupo_anubis_deletion_log
- lupo_anubis_events
- lupo_api_clients
- lupo_api_rate_limits
- lupo_api_token_logs
- lupo_api_tokens
- lupo_api_webhooks
- lupo_artifacts
- lupo_atoms
- lupo_auth_audit_log
- lupo_auth_providers
- lupo_calibration_impacts
- lupo_channel_boot_detail
- lupo_channel_boot_log
- lupo_channel_files
- lupo_channel_state
- lupo_channels
- lupo_cip_analytics
- lupo_cip_propagation_tracking
- lupo_cip_trends
- lupo_collection_tab_map
- lupo_collection_tab_paths
- lupo_content_atom_map
- lupo_content_category_map
- lupo_content_engagement_summary
- lupo_content_events
- lupo_content_hashtag
- lupo_content_inbound_links
- lupo_content_likes
- lupo_content_media
- lupo_content_question_map
- lupo_content_references
- lupo_content_revisions
- lupo_content_shares
- lupo_content_tag_relationships
- lupo_contents
- lupo_contexts
- lupo_contexts_map
- lupo_contexts_old
- lupo_crafty_syntax_chat_mod_departments
- lupo_crafty_user_mapping
- lupo_crm_leads
- lupo_dialog_channels
- lupo_dialog_messages
- lupo_dialog_threads
- lupo_document_chunks
- lupo_document_embeddings
- lupo_documents
- lupo_edge_types
- lupo_edges
- lupo_emotional_constellations
- lupo_emotional_frameworks
- lupo_emotional_geometry_calibrations
- lupo_emotional_stars
- lupo_emotional_translations
- lupo_entity_edges
- lupo_entity_properties
- lupo_event_log
- lupo_event_metadata
- lupo_federation_categories
- lupo_federation_category_map
- lupo_federation_discovery
- lupo_file_edges
- lupo_files
- lupo_filesystem_migration_log
- lupo_hashtags
- lupo_help_topics
- lupo_help_tree
- lupo_human_history_meta
- lupo_integration_test_results
- lupo_interface_translations
- lupo_interpretation_log
- lupo_kapu_events
- lupo_kapu_restoration_paths
- lupo_labs_declarations
- lupo_labs_violations
- lupo_memory_events
- lupo_memory_rollups
- lupo_meta_log_events
- lupo_metrics_archive_legacy
- lupo_modules
- lupo_modules_departments
- lupo_mood_assignments
- lupo_mood_registry
- lupo_notifications
- lupo_permissions
- lupo_reference_cited_by
- lupo_reference_objects
- lupo_relationships
- lupo_search_index
- lupo_search_rebuild_log
- lupo_semantic_categories
- lupo_semantic_content_views
- lupo_semantic_navigation_overview
- lupo_semantic_overlays
- lupo_semantic_paths
- lupo_semantic_relationships
- lupo_semantic_search_index
- lupo_semantic_tags
- lupo_semantic_translations
- lupo_session_events
- lupo_sessions
- lupo_system_config
- lupo_system_events
- lupo_system_health_snapshots
- lupo_system_logs
- lupo_tab_events
- lupo_temporal_coherence_snapshots
- lupo_truth_evidence
- lupo_truth_questions_map
- lupo_truth_relations
- lupo_truth_sources
- lupo_truth_topics
- lupo_unified_registry
- lupo_unified_truth_items
- lupo_unified_websites
- lupo_user_comments
- lupo_world_events
- lupo_world_registry

---

## Optional Tables (Derived from TOON Scan)

- lupo_aliases
- lupo_analytics_referers_periods
- lupo_analytics_visits_daily
- lupo_analytics_visits_monthly
- lupo_analytics_visits_periods
- lupo_anubis_mirrored
- lupo_anubis_orphaned
- lupo_anubis_redirects
- lupo_anubis_revised
- lupo_gov_event_actor_edges
- lupo_gov_event_conflicts
- lupo_gov_event_dependencies
- lupo_gov_event_references
- lupo_gov_events
- lupo_gov_timeline_nodes
- lupo_gov_valuations
- lupo_governance_overrides
- lupo_hotfix_registry
- lupo_legacy_content_mapping
- lupo_memory_debug_log
- lupo_multi_agent_critique_sync
- lupo_narrative_fragments
- lupo_pack_role_registry
- lupo_persona_dialogue_patterns
- lupo_persona_profiles
- lupo_test_performance_metrics
- lupo_tldnr
