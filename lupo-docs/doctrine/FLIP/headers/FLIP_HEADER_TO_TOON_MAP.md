# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/FLIP/headers/FLIP_HEADER_TO_TOON_MAP.md"
  file_hash: "32c8f2003276a17740d649f1dfedc0d1c6d5e9a0bab19cd418e94473268d924b"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "lupo-docs\doctrine\FLIP\headers\FLIP_HEADER_TO_TOON_MAP.md"
  file_hash: "7f41bca0d6ce08e4500a5d976cffb913d82966003040fc709d0682a7d68a9278"
  file_path_from_root: "lupo-docs\doctrine\FLIP\headers\FLIP_HEADER_TO_TOON_MAP.md"
  file_hash: "134aa60a216d507785621ea2c3487af1830308c62f4632bef56fe26069a6c763"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FLIP_HEADER_TO_TOON_MAP.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "flip", "headers", "flip_header_to_toon_mapmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: "lupo-docs/doctrine/FLIP/headers/FLIP_HEADER_TO_TOON_MAP.md"
system_version: "4.0.35"
channel_id: 42
mood_rgb: "00FFFF"
actor_id: 1003
lupo_agent: "antigravity"
purpose: "Canonical mapping of FLIP headers to TOON schema definitions"
---

# FLIP HEADER TO TOON MAP

**Version:** 4.0.35  
**Status:** Active  
**Authority:** Antigravity (actor_id 1003)  
**Channel:** 42 (Development Coordination)

---

## CORE PRINCIPLE: SEMANTIC BRIDGING

In the Lupopedia **Semantic OS**, files carry their own truth through the **File-Level Inference Protocol (FLIP)**. However, this truth must eventually align with the database's **TOON Schema**. 

This document provides the canonical mapping from **FLIP Header Keys** (used in YAML headers) to their corresponding **TOON Tables and Fields** (used in the database).

---

## CANONICAL MAPPING TABLE

| FLIP Header Key | TOON Table | TOON Field | Description |
|-----------------|------------|------------|-------------|
| `actor_id` | `lupo_actors` | `actor_id` | Canonical identity of the actor (AI or Human). |
| `channel_id` | `lupo_channels` | `channel_id` | Unique identifier for the communication channel. |
| `thread_id` | `lupo_dialog_threads` | `dialog_thread_id` | Unique identifier for a conversation thread. |
| `project_slug` | `lupo_dialog_threads` | `project_slug` | Short identifier for the project scope. |
| `task_name` | `lupo_dialog_threads` | `task_name` | Name of the task associated with the file/thread. |
| `registry_id` | `lupo_registry` | `registry_id` | Unique identifier for an entry in the global registry. |
| `entity_index_id` | `lupo_registry` | `entity_index` | The primary key of the entity in its source table. |
| `department_id` | `lupo_departments` | `department_id` | Organizational grouping of the artifact or actor. |
| `module_id` | `lupo_modules` | `module_id` | Functional grouping of the artifact. |
| `file_path_from_root` | `lupo_contents` | `file_path_from_root` | Repository-relative path used as a unique content key. |
| `lupo_agent` | `lupo_actors` | `slug` | Human-readable unique key for an agent. |
| `mood_rgb` | `lupo_dialog_messages` | `mood_rgb` | 6-character hex color representing emotional state. |
| `system_version` | `lupo_contents` | `file_last_modified_system_version` | System version at time of last file edit. |
| `last_modified_utc` | `lupo_contents` | `file_last_modified_utc` | Exact UTC timestamp of last modification. |
| `created_utc` | `lupo_contents` | `created_ymdhis` | Exact UTC timestamp of creation. |
| `is_kernel` | `lupo_registry` | `is_kernel` | Boolean flag (0/1) indicating system core status. |
| `actor_action_id` | `lupo_actor_actions` | `actor_action_id` | Primary Key for actor action. |
| `actor_capability_id` | `lupo_actor_capabilities` | `actor_capability_id` | Primary Key for actor capability. |
| `actor_channel_id` | `lupo_actor_channels` | `actor_channel_id` | Primary Key for actor channel. |
| `actor_channel_role_id` | `lupo_actor_channel_roles` | `actor_channel_role_id` | Primary Key for actor channel role. |
| `actor_collection_id` | `lupo_actor_collections` | `actor_collection_id` | Primary Key for actor collection. |
| `actor_conflict_id` | `lupo_actor_conflicts` | `actor_conflict_id` | Primary Key for actor conflict. |
| `actor_department_id` | `lupo_actor_departments` | `actor_department_id` | Primary Key for actor department. |
| `actor_edge_id` | `lupo_actor_edges` | `actor_edge_id` | Primary Key for actor edge. |
| `actor_event_id` | `lupo_actor_events` | `actor_event_id` | Primary Key for actor event. |
| `actor_handshake_id` | `lupo_actor_handshakes` | `actor_handshake_id` | Primary Key for actor handshake. |
| `actor_meta_id` | `lupo_actor_meta` | `actor_meta_id` | Primary Key for actor meta. |
| `actor_object_edge_id` | `lupo_actor_object_edges` | `actor_object_edge_id` | Primary Key for actor object edge. |
| `actor_property_id` | `lupo_actor_properties` | `actor_property_id` | Primary Key for actor property. |
| `actor_reply_template_id` | `lupo_actor_reply_templates` | `actor_reply_template_id` | Primary Key for actor reply template. |
| `actor_truth_edge_id` | `lupo_actor_truth_edges` | `actor_truth_edge_id` | Primary Key for actor truth edge. |
| `agent_context_snapshot_id` | `lupo_agent_context_snapshots` | `agent_context_snapshot_id` | Primary Key for agent context snapshot. |
| `agent_dependency_id` | `lupo_agent_dependencies` | `agent_dependency_id` | Primary Key for agent dependency. |
| `agent_faucet_credential_id` | `lupo_agent_faucet_credentials` | `agent_faucet_credential_id` | Primary Key for agent faucet credential. |
| `agent_faucet_id` | `lupo_agent_faucets` | `agent_faucet_id` | Primary Key for agent faucet. |
| `agent_id` | `lupo_agents` | `agent_id` | Primary Key for agent. |
| `agent_property_id` | `lupo_agent_properties` | `agent_property_id` | Primary Key for agent property. |
| `agent_tool_call_id` | `lupo_agent_tool_calls` | `agent_tool_call_id` | Primary Key for agent tool call. |
| `agent_version_id` | `lupo_agent_versions` | `agent_version_id` | Primary Key for agent version. |
| `alias_id` | `lupo_actor_aliases`, `lupo_aliases` | `alias_id` | Primary Key for alias. |
| `analytics_path_id` | `lupo_analytics_paths` | `analytics_path_id` | Primary Key for analytics path. |
| `analytics_referers_period_id` | `lupo_analytics_referers_periods` | `analytics_referers_period_id` | Primary Key for analytics referers period. |
| `analytics_visit_id` | `lupo_analytics_visits` | `analytics_visit_id` | Primary Key for analytics visit. |
| `analytics_visits_daily_id` | `lupo_analytics_visits_daily` | `analytics_visits_daily_id` | Primary Key for analytics visits daily. |
| `analytics_visits_monthly_id` | `lupo_analytics_visits_monthly` | `analytics_visits_monthly_id` | Primary Key for analytics visits monthly. |
| `analytics_visits_period_id` | `lupo_analytics_visits_periods` | `analytics_visits_period_id` | Primary Key for analytics visits period. |
| `anubis_deletion_id` | `lupo_anubis_deletion_log` | `anubis_deletion_id` | Primary Key for anubis deletion. |
| `anubis_event_id` | `lupo_anubis_events` | `anubis_event_id` | Primary Key for anubis event. |
| `anubis_mirrored_id` | `lupo_anubis_mirrored` | `anubis_mirrored_id` | Primary Key for anubis mirrored. |
| `anubis_orphaned_id` | `lupo_anubis_orphaned` | `anubis_orphaned_id` | Primary Key for anubis orphaned. |
| `anubis_redirect_id` | `lupo_anubis_redirects` | `anubis_redirect_id` | Primary Key for anubis redirect. |
| `anubis_revised_id` | `lupo_anubis_revised` | `anubis_revised_id` | Primary Key for anubis revised. |
| `api_client_id` | `lupo_api_clients` | `api_client_id` | Primary Key for api client. |
| `api_rate_limit_id` | `lupo_api_rate_limits` | `api_rate_limit_id` | Primary Key for api rate limit. |
| `api_token_id` | `lupo_api_tokens` | `api_token_id` | Primary Key for api token. |
| `api_token_log_id` | `lupo_api_token_logs` | `api_token_log_id` | Primary Key for api token log. |
| `api_webhook_id` | `lupo_api_webhooks` | `api_webhook_id` | Primary Key for api webhook. |
| `artifact_id` | `lupo_artifacts` | `artifact_id` | Primary Key for artifact. |
| `atom_id` | `lupo_atoms` | `atom_id` | Primary Key for atom. |
| `audit_log_id` | `lupo_audit_log` | `audit_log_id` | Primary Key for audit log. |
| `auth_audit_log_id` | `lupo_auth_audit_log` | `auth_audit_log_id` | Primary Key for auth audit log. |
| `auth_provider_id` | `lupo_auth_providers` | `auth_provider_id` | Primary Key for auth provider. |
| `auth_user_id` | `lupo_auth_users` | `auth_user_id` | Primary Key for auth user. |
| `banned_actor_id` | `lupo_banned_actors` | `banned_actor_id` | Primary Key for banned actor. |
| `bans_log_id` | `lupo_bans_log` | `bans_log_id` | Primary Key for bans log. |
| `boot_id` | `lupo_channel_boot_log` | `boot_id` | Primary Key for boot. |
| `calibration_impact_id` | `lupo_calibration_impacts` | `calibration_impact_id` | Primary Key for calibration impact. |
| `campaign_var_id` | `lupo_analytics_campaign_vars` | `campaign_var_id` | Primary Key for campaign var. |
| `category_id` | `lupo_semantic_categories` | `category_id` | Primary Key for category. |
| `channel_log_id` | `lupo_channel_logs` | `channel_log_id` | Primary Key for channel log. |
| `channel_state_id` | `lupo_channel_state` | `channel_state_id` | Primary Key for channel state. |
| `cip_analytics_id` | `lupo_cip_analytics` | `cip_analytics_id` | Primary Key for cip analytics. |
| `cip_propagation_tracking_id` | `lupo_cip_propagation_tracking` | `cip_propagation_tracking_id` | Primary Key for cip propagation tracking. |
| `cip_trend_id` | `lupo_cip_trends` | `cip_trend_id` | Primary Key for cip trend. |
| `collection_id` | `lupo_collections` | `collection_id` | Primary Key for collection. |
| `collection_tab_id` | `lupo_collection_tabs` | `collection_tab_id` | Primary Key for collection tab. |
| `collection_tab_map_id` | `lupo_collection_tab_map` | `collection_tab_map_id` | Primary Key for collection tab map. |
| `collection_tab_path_id` | `lupo_collection_tab_paths` | `collection_tab_path_id` | Primary Key for collection tab path. |
| `constellation_id` | `lupo_emotional_constellations` | `constellation_id` | Primary Key for constellation. |
| `content_id` | `lupo_contents` | `content_id` | Primary Key for content. |
| `context_id` | `lupo_contexts` | `context_id` | Primary Key for context. |
| `contexts_map_id` | `lupo_contexts_map` | `contexts_map_id` | Primary Key for contexts map. |
| `crafty_syntax_auto_invite_id` | `lupo_crafty_syntax_auto_invite` | `crafty_syntax_auto_invite_id` | Primary Key for crafty syntax auto invite. |
| `crafty_syntax_chat_mod_department_id` | `lupo_crafty_syntax_chat_mod_departments` | `crafty_syntax_chat_mod_department_id` | Primary Key for crafty syntax chat mod department. |
| `crafty_syntax_chat_question_id` | `lupo_crafty_syntax_chat_questions` | `crafty_syntax_chat_question_id` | Primary Key for crafty syntax chat question. |
| `crafty_syntax_layer_invite_id` | `lupo_crafty_syntax_layer_invites` | `crafty_syntax_layer_invite_id` | Primary Key for crafty syntax layer invite. |
| `crafty_syntax_leave_message_id` | `lupo_crafty_syntax_leave_message` | `crafty_syntax_leave_message_id` | Primary Key for crafty syntax leave message. |
| `crafty_user_mapping_id` | `lupo_crafty_user_mapping` | `crafty_user_mapping_id` | Primary Key for crafty user mapping. |
| `crm_lead_id` | `lupo_crm_leads` | `crm_lead_id` | Primary Key for crm lead. |
| `crm_lead_message_id` | `lupo_crm_lead_messages` | `crm_lead_message_id` | Primary Key for crm lead message. |
| `department_metadata_id` | `lupo_department_metadata` | `department_metadata_id` | Primary Key for department metadata. |
| `department_role_id` | `lupo_department_roles` | `department_role_id` | Primary Key for department role. |
| `detail_id` | `lupo_channel_boot_detail` | `detail_id` | Primary Key for detail. |
| `dialog_message_id` | `lupo_dialog_messages` | `dialog_message_id` | Primary Key for dialog message. |
| `dialog_thread_id` | `lupo_dialog_threads` | `dialog_thread_id` | Primary Key for dialog thread. |
| `doctrine_evolution_audit_id` | `lupo_doctrine_evolution_audit` | `doctrine_evolution_audit_id` | Primary Key for doctrine evolution audit. |
| `doctrine_refinement_id` | `lupo_doctrine_refinements` | `doctrine_refinement_id` | Primary Key for doctrine refinement. |
| `document_chunk_id` | `lupo_document_chunks` | `document_chunk_id` | Primary Key for document chunk. |
| `document_embedding_id` | `lupo_document_embeddings` | `document_embedding_id` | Primary Key for document embedding. |
| `document_id` | `lupo_documents` | `document_id` | Primary Key for document. |
| `edge_id` | `lupo_edges`, `lupo_gov_event_actor_edges` | `edge_id` | Primary Key for edge. |
| `edge_type_id` | `lupo_edge_types` | `edge_type_id` | Primary Key for edge type. |
| `emotional_geometry_calibration_id` | `lupo_emotional_geometry_calibrations` | `emotional_geometry_calibration_id` | Primary Key for emotional geometry calibration. |
| `entity_edge_id` | `lupo_entity_edges` | `entity_edge_id` | Primary Key for entity edge. |
| `entity_property_id` | `lupo_entity_properties` | `entity_property_id` | Primary Key for entity property. |
| `entity_type` | `lupo_unregistry` | `entity_type` | Primary Key for entity type. |
| `escalation_id` | `lupo_channel_escalations` | `escalation_id` | Primary Key for escalation. |
| `event_id` | `lupo_event_log`, `lupo_meta_log_events` | `event_id` | Primary Key for event. |
| `external_event_id` | `lupo_agent_external_events` | `external_event_id` | Primary Key for external event. |
| `federation_category_id` | `lupo_federation_categories` | `federation_category_id` | Primary Key for federation category. |
| `federation_category_map_id` | `lupo_federation_category_map` | `federation_category_map_id` | Primary Key for federation category map. |
| `federation_discovery_id` | `lupo_federation_discovery` | `federation_discovery_id` | Primary Key for federation discovery. |
| `federation_node_id` | `lupo_federation_nodes` | `federation_node_id` | Primary Key for federation node. |
| `file_id` | `lupo_agent_files`, `lupo_channel_files` | `file_id` | Primary Key for file. |
| `framework_name` | `lupo_emotional_frameworks` | `framework_name` | Primary Key for framework name. |
| `gov_event_conflict_id` | `lupo_gov_event_conflicts` | `gov_event_conflict_id` | Primary Key for gov event conflict. |
| `gov_event_dependency_id` | `lupo_gov_event_dependencies` | `gov_event_dependency_id` | Primary Key for gov event dependency. |
| `gov_event_id` | `lupo_gov_events` | `gov_event_id` | Primary Key for gov event. |
| `governance_overrid_id` | `lupo_governance_overrides` | `governance_overrid_id` | Primary Key for governance overrid. |
| `hashtag_id` | `lupo_hashtags` | `hashtag_id` | Primary Key for hashtag. |
| `heartbeat_id` | `lupo_agent_heartbeats` | `heartbeat_id` | Primary Key for heartbeat. |
| `help_topic_id` | `lupo_help_topics` | `help_topic_id` | Primary Key for help topic. |
| `help_tree_id` | `lupo_help_tree` | `help_tree_id` | Primary Key for help tree. |
| `hotfix_id` | `lupo_hotfix_registry` | `hotfix_id` | Primary Key for hotfix. |
| `import_registry_id` | `lupo_registry_import` | `import_registry_id` | Primary Key for import registry. |
| `interface_translation_id` | `lupo_interface_translations` | `interface_translation_id` | Primary Key for interface translation. |
| `interpretation_log_id` | `lupo_interpretation_log` | `interpretation_log_id` | Primary Key for interpretation log. |
| `kapu_id` | `lupo_kapu_events` | `kapu_id` | Primary Key for kapu. |
| `labs_declaration_id` | `lupo_labs_declarations` | `labs_declaration_id` | Primary Key for labs declaration. |
| `labs_violation_id` | `lupo_labs_violations` | `labs_violation_id` | Primary Key for labs violation. |
| `link_id` | `lupo_agent_experiences` | `link_id` | Primary Key for link. |
| `log_id` | `lupo_system_logs` | `log_id` | Primary Key for log. |
| `log_type_id` | `lupo_channel_log_types` | `log_type_id` | Primary Key for log type. |
| `mapping_id` | `lupo_legacy_content_mapping` | `mapping_id` | Primary Key for mapping. |
| `memory_event_id` | `lupo_memory_events` | `memory_event_id` | Primary Key for memory event. |
| `memory_rollup_id` | `lupo_memory_rollups` | `memory_rollup_id` | Primary Key for memory rollup. |
| `meta_id` | `lupo_human_history_meta` | `meta_id` | Primary Key for meta. |
| `metadata_id` | `lupo_event_metadata` | `metadata_id` | Primary Key for metadata. |
| `metric_id` | `lupo_metrics_archive_legacy` | `metric_id` | Primary Key for metric. |
| `module_department_id` | `lupo_modules_departments` | `module_department_id` | Primary Key for module department. |
| `mood_assignment_id` | `lupo_mood_assignments` | `mood_assignment_id` | Primary Key for mood assignment. |
| `mood_id` | `lupo_mood_registry` | `mood_id` | Primary Key for mood. |
| `multi_agent_critique_sync_id` | `lupo_multi_agent_critique_sync` | `multi_agent_critique_sync_id` | Primary Key for multi agent critique sync. |
| `navigation_id` | `lupo_semantic_navigation_overview` | `navigation_id` | Primary Key for navigation. |
| `notification_id` | `lupo_notifications` | `notification_id` | Primary Key for notification. |
| `pack_role_registry_id` | `lupo_pack_role_registry` | `pack_role_registry_id` | Primary Key for pack role registry. |
| `path_id` | `lupo_kapu_restoration_paths` | `path_id` | Primary Key for path. |
| `pattern_id` | `lupo_persona_dialogue_patterns` | `pattern_id` | Primary Key for pattern. |
| `permission_id` | `lupo_permissions` | `permission_id` | Primary Key for permission. |
| `persona_id` | `lupo_persona_profiles` | `persona_id` | Primary Key for persona. |
| `reference_cited_by_id` | `lupo_reference_cited_by` | `reference_cited_by_id` | Primary Key for reference cited by. |
| `reference_id` | `lupo_gov_event_references` | `reference_id` | Primary Key for reference. |
| `reference_object_id` | `lupo_reference_objects` | `reference_object_id` | Primary Key for reference object. |
| `referer_id` | `lupo_referers` | `referer_id` | Primary Key for referer. |
| `relationship_id` | `lupo_actor_persona_relationships`, `lupo_relationships`, `lupo_semantic_relationships` | `relationship_id` | Primary Key for relationship. |
| `rule_id` | `lupo_channel_escalation_rules` | `rule_id` | Primary Key for rule. |
| `search_index_id` | `lupo_search_index`, `lupo_semantic_search_index` | `search_index_id` | Primary Key for search index. |
| `search_rebuild_log_id` | `lupo_search_rebuild_log` | `search_rebuild_log_id` | Primary Key for search rebuild log. |
| `semantic_overlay_id` | `lupo_semantic_overlays` | `semantic_overlay_id` | Primary Key for semantic overlay. |
| `semantic_path_id` | `lupo_semantic_paths` | `semantic_path_id` | Primary Key for semantic path. |
| `semantic_translation_id` | `lupo_semantic_translations` | `semantic_translation_id` | Primary Key for semantic translation. |
| `semantic_view_id` | `lupo_semantic_content_views` | `semantic_view_id` | Primary Key for semantic view. |
| `session_event_id` | `lupo_session_events` | `session_event_id` | Primary Key for session event. |
| `session_id` | `lupo_sessions` | `session_id` | Primary Key for session. |
| `snapshot_id` | `lupo_temporal_coherence_snapshots` | `snapshot_id` | Primary Key for snapshot. |
| `star_id` | `lupo_emotional_stars` | `star_id` | Primary Key for star. |
| `system_config_id` | `lupo_system_config` | `system_config_id` | Primary Key for system config. |
| `system_event_id` | `lupo_system_events` | `system_event_id` | Primary Key for system event. |
| `tab_event_id` | `lupo_tab_events` | `tab_event_id` | Primary Key for tab event. |
| `tag_id` | `lupo_semantic_tags` | `tag_id` | Primary Key for tag. |
| `timeline_node_id` | `lupo_gov_timeline_nodes` | `timeline_node_id` | Primary Key for timeline node. |
| `tldnr_id` | `lupo_tldnr` | `tldnr_id` | Primary Key for tldnr. |
| `translation_id` | `lupo_emotional_translations` | `translation_id` | Primary Key for translation. |
| `truth_answer_id` | `lupo_truth_answers` | `truth_answer_id` | Primary Key for truth answer. |
| `truth_evidence_id` | `lupo_truth_evidence` | `truth_evidence_id` | Primary Key for truth evidence. |
| `truth_item_id` | `lupo_truth_items` | `truth_item_id` | Primary Key for truth item. |
| `truth_question_id` | `lupo_truth_questions` | `truth_question_id` | Primary Key for truth question. |
| `truth_questions_map_id` | `lupo_truth_questions_map` | `truth_questions_map_id` | Primary Key for truth questions map. |
| `truth_relation_id` | `lupo_truth_relations` | `truth_relation_id` | Primary Key for truth relation. |
| `truth_sourc_id` | `lupo_truth_sources` | `truth_sourc_id` | Primary Key for truth sourc. |
| `truth_topic_id` | `lupo_truth_topics` | `truth_topic_id` | Primary Key for truth topic. |
| `upload_id` | `lupo_uploads` | `upload_id` | Primary Key for upload. |
| `user_comment_id` | `lupo_user_comments` | `user_comment_id` | Primary Key for user comment. |
| `valuation_id` | `lupo_gov_valuations` | `valuation_id` | Primary Key for valuation. |
| `visit_id` | `lupo_visits` | `visit_id` | Primary Key for visit. |
| `world_event_id` | `lupo_world_events` | `world_event_id` | Primary Key for world event. |
| `world_id` | `lupo_world_registry` | `world_id` | Primary Key for world. |

---

## BLOCK-LEVEL MAPPINGS

The following mappings apply to nested keys within standard blocks.

| Block | Header Key | TOON Table | TOON Field |
|-------|------------|------------|------------|
| `dialog` | `thread_id` | `lupo_dialog_threads` | `dialog_thread_id` |
| `flip.footer` | `referenced_by_threads` | `lupo_edges` | `(various)` |
| `flip.footer` | `referenced_by_channels` | `lupo_edges` | `(various)` |

---

---

## ALIASES & DEPRECATED KEYS

The following aliases are supported by the inference engine but mapped to the canonical fields above.

| Header Alias | Canonical Key | Mapping Rule |
|--------------|---------------|--------------|
| `X-Lupo-Actor-ID` | `actor_id` | Maps to `lupo_actors.actor_id`. |
| `X-Lupo-Channel` | `channel_id` | Maps to `lupo_channels.channel_id`. |
| `X-Lupo-File-Path` | `file_path_from_root` | Maps to `lupo_contents.file_path_from_root`. |
| `wolfie_version` | `system_version` | **DEPRECATED**. Use `system_version`. |
| `last_modified`| `last_modified_utc` | Maps to `lupo_contents.file_last_modified_utc`. |

---

## PROTOCOL RULES

1. **Inference First**: When an agent reads a file, the value in the header is the **absolute truth** for that session, regardless of what's currently in the database.
2. **Naming Convention**: Header keys use snake_case (e.g., `channel_id`), while some TOON fields use different suffixes. This map is the source of truth for the bridge.
3. **Empty Values**: If a header key is present but empty, it implies the value is `NULL` or unknown. Do not default back to DB state unless explicitly performing a "Sync" operation.
4. **Consistency**: When updating a file, ensure the Header Key values match the TOON fields if you intend to write back to the database.

---

## CONCLUSION

This mapping enables the Lupopedia OS to maintain a decentralized, file-sovereign architecture while remaining synchronized with its relational memory layer.

**Map is Law.**

---

**MAP ACTIVE**  
**Version:** 4.0.35  
**Effective:** 20260224  
**Maintained By:** Antigravity  

**END OF DOCUMENT**
