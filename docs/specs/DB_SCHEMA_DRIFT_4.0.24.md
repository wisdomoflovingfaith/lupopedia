---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
X-Lupo-File-Path: docs/specs/DB_SCHEMA_DRIFT_4.0.24.md
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
  canonical: /specs/DB_SCHEMA_DRIFT_4.0.24
  aliases:
    - /docs/DB_SCHEMA_DRIFT_4.0.24
    - /qa/DB+SCHEMA+DRIFT+4.0.24
  slug: DB_SCHEMA_DRIFT_4.0.24
  slug_encoding: underscore
  base_path: /specs
  url_pattern: "/{base}/{slug}"
---

# Database Schema Drift Report 4.0.24

Generated: 2026-02-21T03:04:41.908869Z
Database: lupopedia
TOON Directory: docs/toons

## Missing Tables (DB vs TOONs)

The following tables are defined in TOONs but missing from the database:

- **lupo_actor_actions** - Defined in `lupo_actor_actions.toon.json`
- **lupo_actor_aliases** - Defined in `lupo_actor_aliases.toon.json`
- **lupo_actor_capabilities** - Defined in `lupo_actor_capabilities.toon.json`
- **lupo_actor_channels** - Defined in `lupo_actor_channels.toon.json`
- **lupo_actor_channel_roles** - Defined in `lupo_actor_channel_roles.toon.json`
- **lupo_actor_collections** - Defined in `lupo_actor_collections.toon.json`
- **lupo_actor_conflicts** - Defined in `lupo_actor_conflicts.toon.json`
- **lupo_actor_departments** - Defined in `lupo_actor_departments.toon.json`
- **lupo_actor_edges** - Defined in `lupo_actor_edges.toon.json`
- **lupo_actor_events** - Defined in `lupo_actor_events.toon.json`
- **lupo_actor_handshakes** - Defined in `lupo_actor_handshakes.toon.json`
- **lupo_actor_meta** - Defined in `lupo_actor_meta.toon.json`
- **lupo_actor_moods** - Defined in `lupo_actor_moods.toon.json`
- **lupo_actor_object_edges** - Defined in `lupo_actor_object_edges.toon.json`
- **lupo_actor_persona_relationships** - Defined in `lupo_actor_persona_relationships.toon.json`
- **lupo_actor_properties** - Defined in `lupo_actor_properties.toon.json`
- **lupo_actor_reply_templates** - Defined in `lupo_actor_reply_templates.toon.json`
- **lupo_actor_truth_edges** - Defined in `lupo_actor_truth_edges.toon.json`
- **lupo_agents** - Defined in `lupo_agents.toon.json`
- **lupo_agent_context_snapshots** - Defined in `lupo_agent_context_snapshots.toon.json`
- **lupo_agent_dependencies** - Defined in `lupo_agent_dependencies.toon.json`
- **lupo_agent_experiences** - Defined in `lupo_agent_experiences.toon.json`
- **lupo_agent_external_events** - Defined in `lupo_agent_external_events.toon.json`
- **lupo_agent_faucets** - Defined in `lupo_agent_faucets.toon.json`
- **lupo_agent_faucet_credentials** - Defined in `lupo_agent_faucet_credentials.toon.json`
- **lupo_agent_files** - Defined in `lupo_agent_files.toon.json`
- **lupo_agent_heartbeats** - Defined in `lupo_agent_heartbeats.toon.json`
- **lupo_agent_properties** - Defined in `lupo_agent_properties.toon.json`
- **lupo_agent_tool_calls** - Defined in `lupo_agent_tool_calls.toon.json`
- **lupo_agent_versions** - Defined in `lupo_agent_versions.toon.json`
- **lupo_aliases** - Defined in `lupo_aliases.toon.json`
- **lupo_analytics_campaign_vars** - Defined in `lupo_analytics_campaign_vars.toon.json`
- **lupo_analytics_referers_periods** - Defined in `lupo_analytics_referers_periods.toon.json`
- **lupo_analytics_visits** - Defined in `lupo_analytics_visits.toon.json`
- **lupo_analytics_visits_daily** - Defined in `lupo_analytics_visits_daily.toon.json`
- **lupo_analytics_visits_monthly** - Defined in `lupo_analytics_visits_monthly.toon.json`
- **lupo_analytics_visits_periods** - Defined in `lupo_analytics_visits_periods.toon.json`
- **lupo_anubis_deletion_log** - Defined in `lupo_anubis_deletion_log.toon.json`
- **lupo_anubis_events** - Defined in `lupo_anubis_events.toon.json`
- **lupo_anubis_mirrored** - Defined in `lupo_anubis_mirrored.toon.json`
- **lupo_anubis_orphaned** - Defined in `lupo_anubis_orphaned.toon.json`
- **lupo_anubis_redirects** - Defined in `lupo_anubis_redirects.toon.json`
- **lupo_anubis_revised** - Defined in `lupo_anubis_revised.toon.json`
- **lupo_api_clients** - Defined in `lupo_api_clients.toon.json`
- **lupo_api_rate_limits** - Defined in `lupo_api_rate_limits.toon.json`
- **lupo_api_tokens** - Defined in `lupo_api_tokens.toon.json`
- **lupo_api_token_logs** - Defined in `lupo_api_token_logs.toon.json`
- **lupo_api_webhooks** - Defined in `lupo_api_webhooks.toon.json`
- **lupo_artifacts** - Defined in `lupo_artifacts.toon.json`
- **lupo_atoms** - Defined in `lupo_atoms.toon.json`
- **lupo_audit_log** - Defined in `lupo_audit_log.toon.json`
- **lupo_auth_audit_log** - Defined in `lupo_auth_audit_log.toon.json`
- **lupo_auth_providers** - Defined in `lupo_auth_providers.toon.json`
- **lupo_auth_users** - Defined in `lupo_auth_users.toon.json`
- **lupo_banned_actors** - Defined in `lupo_banned_actors.toon.json`
- **lupo_bans_log** - Defined in `lupo_bans_log.toon.json`
- **lupo_calibration_impacts** - Defined in `lupo_calibration_impacts.toon.json`
- **lupo_channel_boot_detail** - Defined in `lupo_channel_boot_detail.toon.json`
- **lupo_channel_boot_log** - Defined in `lupo_channel_boot_log.toon.json`
- **lupo_channel_escalations** - Defined in `lupo_channel_escalations.toon.json`
- **lupo_channel_escalation_rules** - Defined in `lupo_channel_escalation_rules.toon.json`
- **lupo_channel_files** - Defined in `lupo_channel_files.toon.json`
- **lupo_channel_logs** - Defined in `lupo_channel_logs.toon.json`
- **lupo_channel_log_types** - Defined in `lupo_channel_log_types.toon.json`
- **lupo_channel_state** - Defined in `lupo_channel_state.toon.json`
- **lupo_cip_analytics** - Defined in `lupo_cip_analytics.toon.json`
- **lupo_cip_propagation_tracking** - Defined in `lupo_cip_propagation_tracking.toon.json`
- **lupo_cip_trends** - Defined in `lupo_cip_trends.toon.json`
- **lupo_collections** - Defined in `lupo_collections.toon.json`
- **lupo_collection_tabs** - Defined in `lupo_collection_tabs.toon.json`
- **lupo_collection_tab_map** - Defined in `lupo_collection_tab_map.toon.json`
- **lupo_collection_tab_paths** - Defined in `lupo_collection_tab_paths.toon.json`
- **lupo_contents** - Defined in `lupo_contents.toon.json`
- **lupo_contexts** - Defined in `lupo_contexts.toon.json`
- **lupo_contexts_map** - Defined in `lupo_contexts_map.toon.json`
- **lupo_crafty_syntax_auto_invite** - Defined in `lupo_crafty_syntax_auto_invite.toon.json`
- **lupo_crafty_syntax_chat_mod_departments** - Defined in `lupo_crafty_syntax_chat_mod_departments.toon.json`
- **lupo_crafty_syntax_chat_questions** - Defined in `lupo_crafty_syntax_chat_questions.toon.json`
- **lupo_crafty_syntax_layer_invites** - Defined in `lupo_crafty_syntax_layer_invites.toon.json`
- **lupo_crafty_syntax_leave_message** - Defined in `lupo_crafty_syntax_leave_message.toon.json`
- **lupo_crafty_user_mapping** - Defined in `lupo_crafty_user_mapping.toon.json`
- **lupo_crm_leads** - Defined in `lupo_crm_leads.toon.json`
- **lupo_crm_lead_messages** - Defined in `lupo_crm_lead_messages.toon.json`
- **lupo_departments** - Defined in `lupo_departments.toon.json`
- **lupo_department_metadata** - Defined in `lupo_department_metadata.toon.json`
- **lupo_department_roles** - Defined in `lupo_department_roles.toon.json`
- **lupo_dialog_channels** - Defined in `lupo_dialog_channels.toon.json`
- **lupo_dialog_messages** - Defined in `lupo_dialog_messages.toon.json`
- **lupo_dialog_threads** - Defined in `lupo_dialog_threads.toon.json`
- **lupo_doctrine_evolution_audit** - Defined in `lupo_doctrine_evolution_audit.toon.json`
- **lupo_doctrine_refinements** - Defined in `lupo_doctrine_refinements.toon.json`
- **lupo_documents** - Defined in `lupo_documents.toon.json`
- **lupo_document_chunks** - Defined in `lupo_document_chunks.toon.json`
- **lupo_document_embeddings** - Defined in `lupo_document_embeddings.toon.json`
- **lupo_edges** - Defined in `lupo_edges.toon.json`
- **lupo_edge_types** - Defined in `lupo_edge_types.toon.json`
- **lupo_emotional_constellations** - Defined in `lupo_emotional_constellations.toon.json`
- **lupo_emotional_frameworks** - Defined in `lupo_emotional_frameworks.toon.json`
- **lupo_emotional_geometry_calibrations** - Defined in `lupo_emotional_geometry_calibrations.toon.json`
- **lupo_emotional_stars** - Defined in `lupo_emotional_stars.toon.json`
- **lupo_emotional_translations** - Defined in `lupo_emotional_translations.toon.json`
- **lupo_entity_edges** - Defined in `lupo_entity_edges.toon.json`
- **lupo_entity_properties** - Defined in `lupo_entity_properties.toon.json`
- **lupo_event_log** - Defined in `lupo_event_log.toon.json`
- **lupo_event_metadata** - Defined in `lupo_event_metadata.toon.json`
- **lupo_federation_categories** - Defined in `lupo_federation_categories.toon.json`
- **lupo_federation_category_map** - Defined in `lupo_federation_category_map.toon.json`
- **lupo_federation_discovery** - Defined in `lupo_federation_discovery.toon.json`
- **lupo_federation_nodes** - Defined in `lupo_federation_nodes.toon.json`
- **lupo_governance_overrides** - Defined in `lupo_governance_overrides.toon.json`
- **lupo_gov_events** - Defined in `lupo_gov_events.toon.json`
- **lupo_gov_event_actor_edges** - Defined in `lupo_gov_event_actor_edges.toon.json`
- **lupo_gov_event_conflicts** - Defined in `lupo_gov_event_conflicts.toon.json`
- **lupo_gov_event_dependencies** - Defined in `lupo_gov_event_dependencies.toon.json`
- **lupo_gov_event_references** - Defined in `lupo_gov_event_references.toon.json`
- **lupo_gov_timeline_nodes** - Defined in `lupo_gov_timeline_nodes.toon.json`
- **lupo_gov_valuations** - Defined in `lupo_gov_valuations.toon.json`
- **lupo_hashtags** - Defined in `lupo_hashtags.toon.json`
- **lupo_help_topics** - Defined in `lupo_help_topics.toon.json`
- **lupo_help_tree** - Defined in `lupo_help_tree.toon.json`
- **lupo_hotfix_registry** - Defined in `lupo_hotfix_registry.toon.json`
- **lupo_human_history_meta** - Defined in `lupo_human_history_meta.toon.json`
- **lupo_interface_translations** - Defined in `lupo_interface_translations.toon.json`
- **lupo_interpretation_log** - Defined in `lupo_interpretation_log.toon.json`
- **lupo_kapu_events** - Defined in `lupo_kapu_events.toon.json`
- **lupo_kapu_restoration_paths** - Defined in `lupo_kapu_restoration_paths.toon.json`
- **lupo_labs_declarations** - Defined in `lupo_labs_declarations.toon.json`
- **lupo_labs_violations** - Defined in `lupo_labs_violations.toon.json`
- **lupo_legacy_content_mapping** - Defined in `lupo_legacy_content_mapping.toon.json`
- **lupo_memory_events** - Defined in `lupo_memory_events.toon.json`
- **lupo_memory_rollups** - Defined in `lupo_memory_rollups.toon.json`
- **lupo_meta_log_events** - Defined in `lupo_meta_log_events.toon.json`
- **lupo_metrics_archive_legacy** - Defined in `lupo_metrics_archive_legacy.toon.json`
- **lupo_modules** - Defined in `lupo_modules.toon.json`
- **lupo_modules_departments** - Defined in `lupo_modules_departments.toon.json`
- **lupo_mood_assignments** - Defined in `lupo_mood_assignments.toon.json`
- **lupo_mood_registry** - Defined in `lupo_mood_registry.toon.json`
- **lupo_multi_agent_critique_sync** - Defined in `lupo_multi_agent_critique_sync.toon.json`
- **lupo_notifications** - Defined in `lupo_notifications.toon.json`
- **lupo_pack_role_registry** - Defined in `lupo_pack_role_registry.toon.json`
- **lupo_permissions** - Defined in `lupo_permissions.toon.json`
- **lupo_persona_dialogue_patterns** - Defined in `lupo_persona_dialogue_patterns.toon.json`
- **lupo_persona_profiles** - Defined in `lupo_persona_profiles.toon.json`
- **lupo_reference_cited_by** - Defined in `lupo_reference_cited_by.toon.json`
- **lupo_reference_objects** - Defined in `lupo_reference_objects.toon.json`
- **lupo_relationships** - Defined in `lupo_relationships.toon.json`
- **lupo_search_index** - Defined in `lupo_search_index.toon.json`
- **lupo_search_rebuild_log** - Defined in `lupo_search_rebuild_log.toon.json`
- **lupo_semantic_categories** - Defined in `lupo_semantic_categories.toon.json`
- **lupo_semantic_content_views** - Defined in `lupo_semantic_content_views.toon.json`
- **lupo_semantic_navigation_overview** - Defined in `lupo_semantic_navigation_overview.toon.json`
- **lupo_semantic_overlays** - Defined in `lupo_semantic_overlays.toon.json`
- **lupo_semantic_paths** - Defined in `lupo_semantic_paths.toon.json`
- **lupo_semantic_relationships** - Defined in `lupo_semantic_relationships.toon.json`
- **lupo_semantic_search_index** - Defined in `lupo_semantic_search_index.toon.json`
- **lupo_semantic_tags** - Defined in `lupo_semantic_tags.toon.json`
- **lupo_semantic_translations** - Defined in `lupo_semantic_translations.toon.json`
- **lupo_sessions** - Defined in `lupo_sessions.toon.json`
- **lupo_session_events** - Defined in `lupo_session_events.toon.json`
- **lupo_system_config** - Defined in `lupo_system_config.toon.json`
- **lupo_system_events** - Defined in `lupo_system_events.toon.json`
- **lupo_system_health_snapshots** - Defined in `lupo_system_health_snapshots.toon.json`
- **lupo_system_logs** - Defined in `lupo_system_logs.toon.json`
- **lupo_tab_events** - Defined in `lupo_tab_events.toon.json`
- **lupo_temporal_coherence_snapshots** - Defined in `lupo_temporal_coherence_snapshots.toon.json`
- **lupo_tldnr** - Defined in `lupo_tldnr.toon.json`
- **lupo_truth_answers** - Defined in `lupo_truth_answers.toon.json`
- **lupo_truth_evidence** - Defined in `lupo_truth_evidence.toon.json`
- **lupo_truth_questions** - Defined in `lupo_truth_questions.toon.json`
- **lupo_truth_questions_map** - Defined in `lupo_truth_questions_map.toon.json`
- **lupo_truth_relations** - Defined in `lupo_truth_relations.toon.json`
- **lupo_truth_sources** - Defined in `lupo_truth_sources.toon.json`
- **lupo_truth_topics** - Defined in `lupo_truth_topics.toon.json`
- **lupo_analytics_paths** - Defined in `lupo_analytics_paths.toon.json`
- **lupo_referers** - Defined in `lupo_referers.toon.json`
- **lupo_registry** - Defined in `lupo_registry.toon.json`
- **lupo_truth_items** - Defined in `lupo_truth_items.toon.json`
- **lupo_registry_open** - Defined in `lupo_registry_open.toon.json`
- **lupo_visits** - Defined in `lupo_visits.toon.json`
- **lupo_uploads** - Defined in `lupo_uploads.toon.json`
- **lupo_user_comments** - Defined in `lupo_user_comments.toon.json`
- **lupo_world_events** - Defined in `lupo_world_events.toon.json`
- **lupo_world_registry** - Defined in `lupo_world_registry.toon.json`

## Extra Tables (DB only)

No extra tables found.

## Schema Mismatches (per table)

### lupo_actors

**Missing Columns:**
- `actor_source_id` (bigint) - 
- `actor_source_type` (varchar(50)) - 
- `adversarial_role` (varchar(64) DEFAULT 'none') - 
- `adversarial_oversight_actor_id` (bigint) - 
- `avatar_hash` (varchar(64)) - 

**Type Mismatches:**
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_type`: TOON=varchar(64) NOT NULL, DB=varchar(50)
- `slug`: TOON=varchar(255) NOT NULL, DB=varchar(100)
- `name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint(1)
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint(1)
- `metadata`: TOON=text, DB=json

### lupo_channels

**Missing Columns:**
- `federation_node_id` (bigint NOT NULL) - 
- `created_by_actor_id` (bigint NOT NULL) - 
- `default_actor_id` (bigint NOT NULL DEFAULT 1) - 
- `department_id` (bigint NOT NULL DEFAULT 1) - 
- `channel_slug` (varchar(32) NOT NULL DEFAULT 'channel_key') - 
- `channel_type` (varchar(32) NOT NULL DEFAULT 'chat_room') - 
- `language` (varchar(16) NOT NULL DEFAULT 'en') - 
- `website_link` (varchar(512)) - 
- `metadata_json` (text) - 
- `status_flag` (tinyint NOT NULL DEFAULT 1) - 
- `end_ymdhis` (bigint) - 
- `duration_seconds` (int) - 
- `aal_metadata_json` (json) - 
- `fleet_composition_json` (json) - 
- `awareness_version` (varchar(20) DEFAULT '3.0.0') - 
- `channel_number` (int) - 
- `parent_channel_id` (bigint) - 
- `is_kernel` (tinyint NOT NULL DEFAULT 0) - 
- `boot_sequence_order` (int) - 

**Extra Columns:**
- `is_active` (tinyint(1))

**Type Mismatches:**
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `channel_key`: TOON=varchar(64) NOT NULL, DB=varchar(50)
- `channel_name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint(1)


## Migration Suggestions

The following SQL statements are suggested to bring the database in line with TOON definitions:

```sql
-- Add missing table: lupo_actor_actions
-- TODO: Implement CREATE TABLE statement for lupo_actor_actions

-- Add missing table: lupo_actor_aliases
-- TODO: Implement CREATE TABLE statement for lupo_actor_aliases

-- Add missing table: lupo_actor_capabilities
-- TODO: Implement CREATE TABLE statement for lupo_actor_capabilities

-- Add missing table: lupo_actor_channels
-- TODO: Implement CREATE TABLE statement for lupo_actor_channels

-- Add missing table: lupo_actor_channel_roles
-- TODO: Implement CREATE TABLE statement for lupo_actor_channel_roles

-- Add missing table: lupo_actor_collections
-- TODO: Implement CREATE TABLE statement for lupo_actor_collections

-- Add missing table: lupo_actor_conflicts
-- TODO: Implement CREATE TABLE statement for lupo_actor_conflicts

-- Add missing table: lupo_actor_departments
-- TODO: Implement CREATE TABLE statement for lupo_actor_departments

-- Add missing table: lupo_actor_edges
-- TODO: Implement CREATE TABLE statement for lupo_actor_edges

-- Add missing table: lupo_actor_events
-- TODO: Implement CREATE TABLE statement for lupo_actor_events

-- Add missing table: lupo_actor_handshakes
-- TODO: Implement CREATE TABLE statement for lupo_actor_handshakes

-- Add missing table: lupo_actor_meta
-- TODO: Implement CREATE TABLE statement for lupo_actor_meta

-- Add missing table: lupo_actor_moods
-- TODO: Implement CREATE TABLE statement for lupo_actor_moods

-- Add missing table: lupo_actor_object_edges
-- TODO: Implement CREATE TABLE statement for lupo_actor_object_edges

-- Add missing table: lupo_actor_persona_relationships
-- TODO: Implement CREATE TABLE statement for lupo_actor_persona_relationships

-- Add missing table: lupo_actor_properties
-- TODO: Implement CREATE TABLE statement for lupo_actor_properties

-- Add missing table: lupo_actor_reply_templates
-- TODO: Implement CREATE TABLE statement for lupo_actor_reply_templates

-- Add missing table: lupo_actor_truth_edges
-- TODO: Implement CREATE TABLE statement for lupo_actor_truth_edges

-- Add missing table: lupo_agents
-- TODO: Implement CREATE TABLE statement for lupo_agents

-- Add missing table: lupo_agent_context_snapshots
-- TODO: Implement CREATE TABLE statement for lupo_agent_context_snapshots

-- Add missing table: lupo_agent_dependencies
-- TODO: Implement CREATE TABLE statement for lupo_agent_dependencies

-- Add missing table: lupo_agent_experiences
-- TODO: Implement CREATE TABLE statement for lupo_agent_experiences

-- Add missing table: lupo_agent_external_events
-- TODO: Implement CREATE TABLE statement for lupo_agent_external_events

-- Add missing table: lupo_agent_faucets
-- TODO: Implement CREATE TABLE statement for lupo_agent_faucets

-- Add missing table: lupo_agent_faucet_credentials
-- TODO: Implement CREATE TABLE statement for lupo_agent_faucet_credentials

-- Add missing table: lupo_agent_files
-- TODO: Implement CREATE TABLE statement for lupo_agent_files

-- Add missing table: lupo_agent_heartbeats
-- TODO: Implement CREATE TABLE statement for lupo_agent_heartbeats

-- Add missing table: lupo_agent_properties
-- TODO: Implement CREATE TABLE statement for lupo_agent_properties

-- Add missing table: lupo_agent_tool_calls
-- TODO: Implement CREATE TABLE statement for lupo_agent_tool_calls

-- Add missing table: lupo_agent_versions
-- TODO: Implement CREATE TABLE statement for lupo_agent_versions

-- Add missing table: lupo_aliases
-- TODO: Implement CREATE TABLE statement for lupo_aliases

-- Add missing table: lupo_analytics_campaign_vars
-- TODO: Implement CREATE TABLE statement for lupo_analytics_campaign_vars

-- Add missing table: lupo_analytics_referers_periods
-- TODO: Implement CREATE TABLE statement for lupo_analytics_referers_periods

-- Add missing table: lupo_analytics_visits
-- TODO: Implement CREATE TABLE statement for lupo_analytics_visits

-- Add missing table: lupo_analytics_visits_daily
-- TODO: Implement CREATE TABLE statement for lupo_analytics_visits_daily

-- Add missing table: lupo_analytics_visits_monthly
-- TODO: Implement CREATE TABLE statement for lupo_analytics_visits_monthly

-- Add missing table: lupo_analytics_visits_periods
-- TODO: Implement CREATE TABLE statement for lupo_analytics_visits_periods

-- Add missing table: lupo_anubis_deletion_log
-- TODO: Implement CREATE TABLE statement for lupo_anubis_deletion_log

-- Add missing table: lupo_anubis_events
-- TODO: Implement CREATE TABLE statement for lupo_anubis_events

-- Add missing table: lupo_anubis_mirrored
-- TODO: Implement CREATE TABLE statement for lupo_anubis_mirrored

-- Add missing table: lupo_anubis_orphaned
-- TODO: Implement CREATE TABLE statement for lupo_anubis_orphaned

-- Add missing table: lupo_anubis_redirects
-- TODO: Implement CREATE TABLE statement for lupo_anubis_redirects

-- Add missing table: lupo_anubis_revised
-- TODO: Implement CREATE TABLE statement for lupo_anubis_revised

-- Add missing table: lupo_api_clients
-- TODO: Implement CREATE TABLE statement for lupo_api_clients

-- Add missing table: lupo_api_rate_limits
-- TODO: Implement CREATE TABLE statement for lupo_api_rate_limits

-- Add missing table: lupo_api_tokens
-- TODO: Implement CREATE TABLE statement for lupo_api_tokens

-- Add missing table: lupo_api_token_logs
-- TODO: Implement CREATE TABLE statement for lupo_api_token_logs

-- Add missing table: lupo_api_webhooks
-- TODO: Implement CREATE TABLE statement for lupo_api_webhooks

-- Add missing table: lupo_artifacts
-- TODO: Implement CREATE TABLE statement for lupo_artifacts

-- Add missing table: lupo_atoms
-- TODO: Implement CREATE TABLE statement for lupo_atoms

-- Add missing table: lupo_audit_log
-- TODO: Implement CREATE TABLE statement for lupo_audit_log

-- Add missing table: lupo_auth_audit_log
-- TODO: Implement CREATE TABLE statement for lupo_auth_audit_log

-- Add missing table: lupo_auth_providers
-- TODO: Implement CREATE TABLE statement for lupo_auth_providers

-- Add missing table: lupo_auth_users
-- TODO: Implement CREATE TABLE statement for lupo_auth_users

-- Add missing table: lupo_banned_actors
-- TODO: Implement CREATE TABLE statement for lupo_banned_actors

-- Add missing table: lupo_bans_log
-- TODO: Implement CREATE TABLE statement for lupo_bans_log

-- Add missing table: lupo_calibration_impacts
-- TODO: Implement CREATE TABLE statement for lupo_calibration_impacts

-- Add missing table: lupo_channel_boot_detail
-- TODO: Implement CREATE TABLE statement for lupo_channel_boot_detail

-- Add missing table: lupo_channel_boot_log
-- TODO: Implement CREATE TABLE statement for lupo_channel_boot_log

-- Add missing table: lupo_channel_escalations
-- TODO: Implement CREATE TABLE statement for lupo_channel_escalations

-- Add missing table: lupo_channel_escalation_rules
-- TODO: Implement CREATE TABLE statement for lupo_channel_escalation_rules

-- Add missing table: lupo_channel_files
-- TODO: Implement CREATE TABLE statement for lupo_channel_files

-- Add missing table: lupo_channel_logs
-- TODO: Implement CREATE TABLE statement for lupo_channel_logs

-- Add missing table: lupo_channel_log_types
-- TODO: Implement CREATE TABLE statement for lupo_channel_log_types

-- Add missing table: lupo_channel_state
-- TODO: Implement CREATE TABLE statement for lupo_channel_state

-- Add missing table: lupo_cip_analytics
-- TODO: Implement CREATE TABLE statement for lupo_cip_analytics

-- Add missing table: lupo_cip_propagation_tracking
-- TODO: Implement CREATE TABLE statement for lupo_cip_propagation_tracking

-- Add missing table: lupo_cip_trends
-- TODO: Implement CREATE TABLE statement for lupo_cip_trends

-- Add missing table: lupo_collections
-- TODO: Implement CREATE TABLE statement for lupo_collections

-- Add missing table: lupo_collection_tabs
-- TODO: Implement CREATE TABLE statement for lupo_collection_tabs

-- Add missing table: lupo_collection_tab_map
-- TODO: Implement CREATE TABLE statement for lupo_collection_tab_map

-- Add missing table: lupo_collection_tab_paths
-- TODO: Implement CREATE TABLE statement for lupo_collection_tab_paths

-- Add missing table: lupo_contents
-- TODO: Implement CREATE TABLE statement for lupo_contents

-- Add missing table: lupo_contexts
-- TODO: Implement CREATE TABLE statement for lupo_contexts

-- Add missing table: lupo_contexts_map
-- TODO: Implement CREATE TABLE statement for lupo_contexts_map

-- Add missing table: lupo_crafty_syntax_auto_invite
-- TODO: Implement CREATE TABLE statement for lupo_crafty_syntax_auto_invite

-- Add missing table: lupo_crafty_syntax_chat_mod_departments
-- TODO: Implement CREATE TABLE statement for lupo_crafty_syntax_chat_mod_departments

-- Add missing table: lupo_crafty_syntax_chat_questions
-- TODO: Implement CREATE TABLE statement for lupo_crafty_syntax_chat_questions

-- Add missing table: lupo_crafty_syntax_layer_invites
-- TODO: Implement CREATE TABLE statement for lupo_crafty_syntax_layer_invites

-- Add missing table: lupo_crafty_syntax_leave_message
-- TODO: Implement CREATE TABLE statement for lupo_crafty_syntax_leave_message

-- Add missing table: lupo_crafty_user_mapping
-- TODO: Implement CREATE TABLE statement for lupo_crafty_user_mapping

-- Add missing table: lupo_crm_leads
-- TODO: Implement CREATE TABLE statement for lupo_crm_leads

-- Add missing table: lupo_crm_lead_messages
-- TODO: Implement CREATE TABLE statement for lupo_crm_lead_messages

-- Add missing table: lupo_departments
-- TODO: Implement CREATE TABLE statement for lupo_departments

-- Add missing table: lupo_department_metadata
-- TODO: Implement CREATE TABLE statement for lupo_department_metadata

-- Add missing table: lupo_department_roles
-- TODO: Implement CREATE TABLE statement for lupo_department_roles

-- Add missing table: lupo_dialog_channels
-- TODO: Implement CREATE TABLE statement for lupo_dialog_channels

-- Add missing table: lupo_dialog_messages
-- TODO: Implement CREATE TABLE statement for lupo_dialog_messages

-- Add missing table: lupo_dialog_threads
-- TODO: Implement CREATE TABLE statement for lupo_dialog_threads

-- Add missing table: lupo_doctrine_evolution_audit
-- TODO: Implement CREATE TABLE statement for lupo_doctrine_evolution_audit

-- Add missing table: lupo_doctrine_refinements
-- TODO: Implement CREATE TABLE statement for lupo_doctrine_refinements

-- Add missing table: lupo_documents
-- TODO: Implement CREATE TABLE statement for lupo_documents

-- Add missing table: lupo_document_chunks
-- TODO: Implement CREATE TABLE statement for lupo_document_chunks

-- Add missing table: lupo_document_embeddings
-- TODO: Implement CREATE TABLE statement for lupo_document_embeddings

-- Add missing table: lupo_edges
-- TODO: Implement CREATE TABLE statement for lupo_edges

-- Add missing table: lupo_edge_types
-- TODO: Implement CREATE TABLE statement for lupo_edge_types

-- Add missing table: lupo_emotional_constellations
-- TODO: Implement CREATE TABLE statement for lupo_emotional_constellations

-- Add missing table: lupo_emotional_frameworks
-- TODO: Implement CREATE TABLE statement for lupo_emotional_frameworks

-- Add missing table: lupo_emotional_geometry_calibrations
-- TODO: Implement CREATE TABLE statement for lupo_emotional_geometry_calibrations

-- Add missing table: lupo_emotional_stars
-- TODO: Implement CREATE TABLE statement for lupo_emotional_stars

-- Add missing table: lupo_emotional_translations
-- TODO: Implement CREATE TABLE statement for lupo_emotional_translations

-- Add missing table: lupo_entity_edges
-- TODO: Implement CREATE TABLE statement for lupo_entity_edges

-- Add missing table: lupo_entity_properties
-- TODO: Implement CREATE TABLE statement for lupo_entity_properties

-- Add missing table: lupo_event_log
-- TODO: Implement CREATE TABLE statement for lupo_event_log

-- Add missing table: lupo_event_metadata
-- TODO: Implement CREATE TABLE statement for lupo_event_metadata

-- Add missing table: lupo_federation_categories
-- TODO: Implement CREATE TABLE statement for lupo_federation_categories

-- Add missing table: lupo_federation_category_map
-- TODO: Implement CREATE TABLE statement for lupo_federation_category_map

-- Add missing table: lupo_federation_discovery
-- TODO: Implement CREATE TABLE statement for lupo_federation_discovery

-- Add missing table: lupo_federation_nodes
-- TODO: Implement CREATE TABLE statement for lupo_federation_nodes

-- Add missing table: lupo_governance_overrides
-- TODO: Implement CREATE TABLE statement for lupo_governance_overrides

-- Add missing table: lupo_gov_events
-- TODO: Implement CREATE TABLE statement for lupo_gov_events

-- Add missing table: lupo_gov_event_actor_edges
-- TODO: Implement CREATE TABLE statement for lupo_gov_event_actor_edges

-- Add missing table: lupo_gov_event_conflicts
-- TODO: Implement CREATE TABLE statement for lupo_gov_event_conflicts

-- Add missing table: lupo_gov_event_dependencies
-- TODO: Implement CREATE TABLE statement for lupo_gov_event_dependencies

-- Add missing table: lupo_gov_event_references
-- TODO: Implement CREATE TABLE statement for lupo_gov_event_references

-- Add missing table: lupo_gov_timeline_nodes
-- TODO: Implement CREATE TABLE statement for lupo_gov_timeline_nodes

-- Add missing table: lupo_gov_valuations
-- TODO: Implement CREATE TABLE statement for lupo_gov_valuations

-- Add missing table: lupo_hashtags
-- TODO: Implement CREATE TABLE statement for lupo_hashtags

-- Add missing table: lupo_help_topics
-- TODO: Implement CREATE TABLE statement for lupo_help_topics

-- Add missing table: lupo_help_tree
-- TODO: Implement CREATE TABLE statement for lupo_help_tree

-- Add missing table: lupo_hotfix_registry
-- TODO: Implement CREATE TABLE statement for lupo_hotfix_registry

-- Add missing table: lupo_human_history_meta
-- TODO: Implement CREATE TABLE statement for lupo_human_history_meta

-- Add missing table: lupo_interface_translations
-- TODO: Implement CREATE TABLE statement for lupo_interface_translations

-- Add missing table: lupo_interpretation_log
-- TODO: Implement CREATE TABLE statement for lupo_interpretation_log

-- Add missing table: lupo_kapu_events
-- TODO: Implement CREATE TABLE statement for lupo_kapu_events

-- Add missing table: lupo_kapu_restoration_paths
-- TODO: Implement CREATE TABLE statement for lupo_kapu_restoration_paths

-- Add missing table: lupo_labs_declarations
-- TODO: Implement CREATE TABLE statement for lupo_labs_declarations

-- Add missing table: lupo_labs_violations
-- TODO: Implement CREATE TABLE statement for lupo_labs_violations

-- Add missing table: lupo_legacy_content_mapping
-- TODO: Implement CREATE TABLE statement for lupo_legacy_content_mapping

-- Add missing table: lupo_memory_events
-- TODO: Implement CREATE TABLE statement for lupo_memory_events

-- Add missing table: lupo_memory_rollups
-- TODO: Implement CREATE TABLE statement for lupo_memory_rollups

-- Add missing table: lupo_meta_log_events
-- TODO: Implement CREATE TABLE statement for lupo_meta_log_events

-- Add missing table: lupo_metrics_archive_legacy
-- TODO: Implement CREATE TABLE statement for lupo_metrics_archive_legacy

-- Add missing table: lupo_modules
-- TODO: Implement CREATE TABLE statement for lupo_modules

-- Add missing table: lupo_modules_departments
-- TODO: Implement CREATE TABLE statement for lupo_modules_departments

-- Add missing table: lupo_mood_assignments
-- TODO: Implement CREATE TABLE statement for lupo_mood_assignments

-- Add missing table: lupo_mood_registry
-- TODO: Implement CREATE TABLE statement for lupo_mood_registry

-- Add missing table: lupo_multi_agent_critique_sync
-- TODO: Implement CREATE TABLE statement for lupo_multi_agent_critique_sync

-- Add missing table: lupo_notifications
-- TODO: Implement CREATE TABLE statement for lupo_notifications

-- Add missing table: lupo_pack_role_registry
-- TODO: Implement CREATE TABLE statement for lupo_pack_role_registry

-- Add missing table: lupo_permissions
-- TODO: Implement CREATE TABLE statement for lupo_permissions

-- Add missing table: lupo_persona_dialogue_patterns
-- TODO: Implement CREATE TABLE statement for lupo_persona_dialogue_patterns

-- Add missing table: lupo_persona_profiles
-- TODO: Implement CREATE TABLE statement for lupo_persona_profiles

-- Add missing table: lupo_reference_cited_by
-- TODO: Implement CREATE TABLE statement for lupo_reference_cited_by

-- Add missing table: lupo_reference_objects
-- TODO: Implement CREATE TABLE statement for lupo_reference_objects

-- Add missing table: lupo_relationships
-- TODO: Implement CREATE TABLE statement for lupo_relationships

-- Add missing table: lupo_search_index
-- TODO: Implement CREATE TABLE statement for lupo_search_index

-- Add missing table: lupo_search_rebuild_log
-- TODO: Implement CREATE TABLE statement for lupo_search_rebuild_log

-- Add missing table: lupo_semantic_categories
-- TODO: Implement CREATE TABLE statement for lupo_semantic_categories

-- Add missing table: lupo_semantic_content_views
-- TODO: Implement CREATE TABLE statement for lupo_semantic_content_views

-- Add missing table: lupo_semantic_navigation_overview
-- TODO: Implement CREATE TABLE statement for lupo_semantic_navigation_overview

-- Add missing table: lupo_semantic_overlays
-- TODO: Implement CREATE TABLE statement for lupo_semantic_overlays

-- Add missing table: lupo_semantic_paths
-- TODO: Implement CREATE TABLE statement for lupo_semantic_paths

-- Add missing table: lupo_semantic_relationships
-- TODO: Implement CREATE TABLE statement for lupo_semantic_relationships

-- Add missing table: lupo_semantic_search_index
-- TODO: Implement CREATE TABLE statement for lupo_semantic_search_index

-- Add missing table: lupo_semantic_tags
-- TODO: Implement CREATE TABLE statement for lupo_semantic_tags

-- Add missing table: lupo_semantic_translations
-- TODO: Implement CREATE TABLE statement for lupo_semantic_translations

-- Add missing table: lupo_sessions
-- TODO: Implement CREATE TABLE statement for lupo_sessions

-- Add missing table: lupo_session_events
-- TODO: Implement CREATE TABLE statement for lupo_session_events

-- Add missing table: lupo_system_config
-- TODO: Implement CREATE TABLE statement for lupo_system_config

-- Add missing table: lupo_system_events
-- TODO: Implement CREATE TABLE statement for lupo_system_events

-- Add missing table: lupo_system_health_snapshots
-- TODO: Implement CREATE TABLE statement for lupo_system_health_snapshots

-- Add missing table: lupo_system_logs
-- TODO: Implement CREATE TABLE statement for lupo_system_logs

-- Add missing table: lupo_tab_events
-- TODO: Implement CREATE TABLE statement for lupo_tab_events

-- Add missing table: lupo_temporal_coherence_snapshots
-- TODO: Implement CREATE TABLE statement for lupo_temporal_coherence_snapshots

-- Add missing table: lupo_tldnr
-- TODO: Implement CREATE TABLE statement for lupo_tldnr

-- Add missing table: lupo_truth_answers
-- TODO: Implement CREATE TABLE statement for lupo_truth_answers

-- Add missing table: lupo_truth_evidence
-- TODO: Implement CREATE TABLE statement for lupo_truth_evidence

-- Add missing table: lupo_truth_questions
-- TODO: Implement CREATE TABLE statement for lupo_truth_questions

-- Add missing table: lupo_truth_questions_map
-- TODO: Implement CREATE TABLE statement for lupo_truth_questions_map

-- Add missing table: lupo_truth_relations
-- TODO: Implement CREATE TABLE statement for lupo_truth_relations

-- Add missing table: lupo_truth_sources
-- TODO: Implement CREATE TABLE statement for lupo_truth_sources

-- Add missing table: lupo_truth_topics
-- TODO: Implement CREATE TABLE statement for lupo_truth_topics

-- Add missing table: lupo_analytics_paths
-- TODO: Implement CREATE TABLE statement for lupo_analytics_paths

-- Add missing table: lupo_referers
-- TODO: Implement CREATE TABLE statement for lupo_referers

-- Add missing table: lupo_registry
-- TODO: Implement CREATE TABLE statement for lupo_registry

-- Add missing table: lupo_truth_items
-- TODO: Implement CREATE TABLE statement for lupo_truth_items

-- Add missing table: lupo_registry_open
-- TODO: Implement CREATE TABLE statement for lupo_registry_open

-- Add missing table: lupo_visits
-- TODO: Implement CREATE TABLE statement for lupo_visits

-- Add missing table: lupo_uploads
-- TODO: Implement CREATE TABLE statement for lupo_uploads

-- Add missing table: lupo_user_comments
-- TODO: Implement CREATE TABLE statement for lupo_user_comments

-- Add missing table: lupo_world_events
-- TODO: Implement CREATE TABLE statement for lupo_world_events

-- Add missing table: lupo_world_registry
-- TODO: Implement CREATE TABLE statement for lupo_world_registry

-- Add missing column to lupo_actors
ALTER TABLE `lupo_actors` ADD COLUMN `actor_source_id` bigint COMMENT '';
-- Add missing column to lupo_actors
ALTER TABLE `lupo_actors` ADD COLUMN `actor_source_type` varchar(50) COMMENT '';
-- Add missing column to lupo_actors
ALTER TABLE `lupo_actors` ADD COLUMN `adversarial_role` varchar(64) DEFAULT 'none' COMMENT '';
-- Add missing column to lupo_actors
ALTER TABLE `lupo_actors` ADD COLUMN `adversarial_oversight_actor_id` bigint COMMENT '';
-- Add missing column to lupo_actors
ALTER TABLE `lupo_actors` ADD COLUMN `avatar_hash` varchar(64) COMMENT '';
-- Fix type mismatch in lupo_actors.actor_id
ALTER TABLE `lupo_actors` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actors.actor_type
ALTER TABLE `lupo_actors` MODIFY COLUMN `actor_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_actors.slug
ALTER TABLE `lupo_actors` MODIFY COLUMN `slug` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_actors.name
ALTER TABLE `lupo_actors` MODIFY COLUMN `name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_actors.created_ymdhis
ALTER TABLE `lupo_actors` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actors.updated_ymdhis
ALTER TABLE `lupo_actors` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_actors.is_active
ALTER TABLE `lupo_actors` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_actors.is_deleted
ALTER TABLE `lupo_actors` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actors.metadata
ALTER TABLE `lupo_actors` MODIFY COLUMN `metadata` text;

-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `federation_node_id` bigint NOT NULL COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `created_by_actor_id` bigint NOT NULL COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `default_actor_id` bigint NOT NULL DEFAULT 1 COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `department_id` bigint NOT NULL DEFAULT 1 COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `channel_slug` varchar(32) NOT NULL DEFAULT 'channel_key' COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `channel_type` varchar(32) NOT NULL DEFAULT 'chat_room' COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `language` varchar(16) NOT NULL DEFAULT 'en' COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `website_link` varchar(512) COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `metadata_json` text COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `status_flag` tinyint NOT NULL DEFAULT 1 COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `end_ymdhis` bigint COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `duration_seconds` int COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `aal_metadata_json` json COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `fleet_composition_json` json COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `awareness_version` varchar(20) DEFAULT '3.0.0' COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `channel_number` int COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `parent_channel_id` bigint COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `is_kernel` tinyint NOT NULL DEFAULT 0 COMMENT '';
-- Add missing column to lupo_channels
ALTER TABLE `lupo_channels` ADD COLUMN `boot_sequence_order` int COMMENT '';
-- Fix type mismatch in lupo_channels.channel_id
ALTER TABLE `lupo_channels` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channels.channel_key
ALTER TABLE `lupo_channels` MODIFY COLUMN `channel_key` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_channels.channel_name
ALTER TABLE `lupo_channels` MODIFY COLUMN `channel_name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_channels.created_ymdhis
ALTER TABLE `lupo_channels` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channels.updated_ymdhis
ALTER TABLE `lupo_channels` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_channels.is_deleted
ALTER TABLE `lupo_channels` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

```

---
*Database Schema Drift Report 4.0.24*
