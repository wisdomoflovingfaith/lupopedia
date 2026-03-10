# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\FLIP\headers\UNIVERSAL_ID_TOON_MAP.md"
  file_hash: "aaecddd9f12a11c1084c7dbf66c0f5bb0e1fb5a4e55d7d59681a59d13a20b6ad"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\doctrine\FLIP\headers\UNIVERSAL_ID_TOON_MAP.md"
  file_hash: "ffbbab59a8c868405c80f04b13d341b6cf52572c8cb857c15eff72fb708e6cc5"
  file_path_from_root: "docs\doctrine\FLIP\headers\UNIVERSAL_ID_TOON_MAP.md"
  file_hash: "0a5d5680ee70b936bae609f1bc17a05f453eadf75093dfb3bb36c106729e2900"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for UNIVERSAL_ID_TOON_MAP.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "flip", "headers", "universal_id_toon_mapmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: "docs/doctrine/FLIP/headers/UNIVERSAL_ID_TOON_MAP.md"
system_version: "4.0.35"
purpose: "Universal mapping of all TOON ID columns for FLIP inference"
actor_id: 1003
lupo_agent: "antigravity"
---

# UNIVERSAL ID TOON MAP

This document lists every column ending in `_id` across the entire **TOON Schema**, mapping them to their source tables for use in **FLIP Header** inference and database synchronization.

| ID Key | Tables | Description |
|--------|--------|-------------|
| `actor_a_id` | lupo_actor_conflicts | |
| `actor_action_id` | lupo_actor_actions | |
| `actor_b_id` | lupo_actor_conflicts | |
| `actor_capability_id` | lupo_actor_capabilities | |
| `actor_channel_id` | lupo_actor_channels | |
| `actor_channel_role_id` | lupo_actor_channel_roles | |
| `actor_collection_id` | lupo_actor_collections | |
| `actor_conflict_id` | lupo_actor_conflicts | |
| `actor_department_id` | lupo_actor_departments | |
| `actor_edge_id` | lupo_actor_edges | |
| `actor_event_id` | lupo_actor_events | |
| `actor_handshake_id` | lupo_actor_handshakes | |
| `actor_id` | lupo_actor_actions, lupo_actor_aliases, lupo_actor_capabilities, lupo_actor_channel_roles, lupo_actor_channels, lupo_actor_collections, lupo_actor_departments, lupo_actor_events, lupo_actor_handshakes, lupo_actor_meta, lupo_actor_moods, lupo_actor_object_edges, lupo_actor_persona_relationships, lupo_actor_properties, lupo_actor_reply_templates, lupo_actor_truth_edges, lupo_actors, lupo_agent_context_snapshots, lupo_agent_faucets, lupo_agent_properties, lupo_analytics_visits, lupo_api_clients, lupo_api_rate_limits, lupo_api_token_logs, lupo_api_tokens, lupo_api_webhooks, lupo_artifacts, lupo_banned_actors, lupo_bans_log, lupo_channel_boot_log, lupo_channel_escalations, lupo_channel_logs, lupo_collections, lupo_contents, lupo_crm_lead_messages, lupo_department_roles, lupo_edges, lupo_gov_event_actor_edges, lupo_labs_declarations, lupo_labs_violations, lupo_memory_events, lupo_memory_rollups, lupo_meta_log_events, lupo_notifications, lupo_referers, lupo_session_events, lupo_sessions, lupo_system_config, lupo_system_events, lupo_tab_events, lupo_truth_answers, lupo_truth_evidence, lupo_truth_questions, lupo_truth_questions_map, lupo_truth_relations, lupo_truth_sources, lupo_truth_topics, lupo_uploads, lupo_visits, lupo_world_events | |
| `metadata_id` | lupo_metadata | |
| `actor_object_edge_id` | lupo_actor_object_edges | |
| `metadata_id` | lupo_metadata | |
| `actor_reply_template_id` | lupo_actor_reply_templates | |
| `actor_source_id` | lupo_actors | |
| `actor_truth_edge_id` | lupo_actor_truth_edges | |
| `adversarial_oversight_actor_id` | lupo_actors | |
| `agent_context_snapshot_id` | lupo_agent_context_snapshots | |
| `agent_dependency_id` | lupo_agent_dependencies | |
| `agent_faucet_credential_id` | lupo_agent_faucet_credentials | |
| `agent_faucet_id` | lupo_agent_faucets | |
| `agent_id` | lupo_agent_dependencies, lupo_agent_experiences, lupo_agent_files, lupo_agent_tool_calls, lupo_agent_versions, lupo_agents, lupo_governance_overrides, lupo_interpretation_log, lupo_kapu_events, lupo_kapu_restoration_paths, lupo_multi_agent_critique_sync, lupo_pack_role_registry | |
| `agent_property_id` | lupo_agent_properties | |
| `agent_tool_call_id` | lupo_agent_tool_calls | |
| `agent_version_id` | lupo_agent_versions | |
| `alias_id` | lupo_actor_aliases, lupo_aliases | |
| `analytics_path_id` | lupo_analytics_paths | |
| `analytics_referers_period_id` | lupo_analytics_referers_periods | |
| `analytics_visit_id` | lupo_analytics_visits | |
| `analytics_visits_daily_id` | lupo_analytics_visits_daily | |
| `analytics_visits_monthly_id` | lupo_analytics_visits_monthly | |
| `analytics_visits_period_id` | lupo_analytics_visits_periods | |
| `anubis_deletion_id` | lupo_anubis_deletion_log | |
| `anubis_event_id` | lupo_anubis_events | |
| `anubis_mirrored_id` | lupo_anubis_mirrored | |
| `anubis_orphaned_id` | lupo_anubis_orphaned | |
| `anubis_redirect_id` | lupo_anubis_redirects | |
| `anubis_revised_id` | lupo_anubis_revised | |
| `api_client_id` | lupo_api_clients | |
| `api_key_id` | lupo_agents | |
| `api_rate_limit_id` | lupo_api_rate_limits | |
| `api_token_id` | lupo_api_rate_limits, lupo_api_token_logs, lupo_api_tokens | |
| `api_token_log_id` | lupo_api_token_logs | |
| `api_webhook_id` | lupo_api_webhooks | |
| `applied_by_actor_id` | lupo_hotfix_registry | |
| `approval_agent_id` | lupo_actor_capabilities | |
| `artifact_id` | lupo_artifacts | |
| `atom_id` | lupo_atoms | |
| `audit_log_id` | lupo_audit_log | |
| `auth_audit_log_id` | lupo_auth_audit_log | |
| `auth_provider_id` | lupo_auth_providers | |
| `auth_user_id` | lupo_auth_users | |
| `author_actor_id` | lupo_help_topics | |
| `banned_actor_id` | lupo_banned_actors | |
| `banned_by_actor_id` | lupo_banned_actors | |
| `bans_log_id` | lupo_bans_log | |
| `boot_id` | lupo_channel_boot_detail, lupo_channel_boot_log | |
| `calibration_id` | lupo_calibration_impacts | |
| `calibration_impact_id` | lupo_calibration_impacts | |
| `campaign_var_id` | lupo_analytics_campaign_vars | |
| `category_id` | lupo_semantic_categories | |
| `certificate_id` | lupo_labs_declarations, lupo_labs_violations | |
| `channel_id` | lupo_actor_channel_roles, lupo_actor_channels, lupo_audit_log, lupo_channel_boot_detail, lupo_channel_escalation_rules, lupo_channel_escalations, lupo_channel_files, lupo_channel_logs, lupo_channel_state, lupo_channels, lupo_dialog_channels, lupo_dialog_messages, lupo_dialog_threads, lupo_edges, lupo_notifications, lupo_sessions, lupo_uploads | |
| `channel_log_id` | lupo_channel_logs | |
| `channel_state_id` | lupo_channel_state | |
| `chunk_id` | lupo_document_embeddings | |
| `cip_analytics_id` | lupo_cip_analytics, lupo_emotional_geometry_calibrations | |
| `cip_event_id` | lupo_cip_propagation_tracking, lupo_doctrine_refinements, lupo_multi_agent_critique_sync | |
| `cip_propagation_tracking_id` | lupo_cip_propagation_tracking | |
| `cip_trend_id` | lupo_cip_trends | |
| `client_id` | lupo_auth_providers | |
| `collection_id` | lupo_actor_collections, lupo_collection_tab_paths, lupo_collection_tabs, lupo_collections | |
| `collection_tab_id` | lupo_collection_tab_map, lupo_collection_tab_paths, lupo_collection_tabs | |
| `collection_tab_map_id` | lupo_collection_tab_map | |
| `collection_tab_parent_id` | lupo_collection_tabs | |
| `collection_tab_path_id` | lupo_collection_tab_paths | |
| `conflicts_with_event_id` | lupo_gov_event_conflicts | |
| `constellation_id` | lupo_emotional_constellations | |
| `content_id` | lupo_analytics_referers_periods, lupo_analytics_visits, lupo_analytics_visits_daily, lupo_analytics_visits_monthly, lupo_analytics_visits_periods, lupo_contents, lupo_help_tree, lupo_legacy_content_mapping, lupo_reference_cited_by, lupo_referers, lupo_user_comments, lupo_visits | |
| `content_parent_id` | lupo_contents | |
| `context_id` | lupo_agent_experiences, lupo_atoms, lupo_contexts, lupo_contexts_map | |
| `contexts_map_id` | lupo_contexts_map | |
| `crafty_operator_id` | lupo_auth_audit_log, lupo_crafty_user_mapping | |
| `crafty_syntax_auto_invite_id` | lupo_crafty_syntax_auto_invite | |
| `crafty_syntax_chat_mod_department_id` | lupo_crafty_syntax_chat_mod_departments | |
| `crafty_syntax_chat_question_id` | lupo_crafty_syntax_chat_questions | |
| `crafty_syntax_layer_invite_id` | lupo_crafty_syntax_layer_invites | |
| `crafty_syntax_leave_message_id` | lupo_crafty_syntax_leave_message | |
| `crafty_user_mapping_id` | lupo_crafty_user_mapping | |
| `created_by_actor_id` | lupo_channels, lupo_dialog_threads | |
| `crm_lead_id` | lupo_crm_leads | |
| `crm_lead_message_id` | lupo_crm_lead_messages | |
| `default_actor_id` | lupo_channels, lupo_departments | |
| `default_collection_id` | lupo_contents, lupo_truth_questions | |
| `default_department_id` | lupo_federation_nodes | |
| `department_id` | lupo_actor_departments, lupo_analytics_referers_periods, lupo_analytics_visits_daily, lupo_analytics_visits_monthly, lupo_analytics_visits_periods, lupo_channels, lupo_collection_tabs, lupo_collections, lupo_contents, lupo_crafty_syntax_auto_invite, lupo_crafty_syntax_chat_mod_departments, lupo_crafty_syntax_chat_questions, lupo_crafty_syntax_leave_message, lupo_department_metadata, lupo_department_roles, lupo_departments, lupo_help_tree, lupo_modules_departments, lupo_permissions | |
| `department_metadata_id` | lupo_department_metadata | |
| `department_role_id` | lupo_department_roles | |
| `depends_on_agent_id` | lupo_agent_dependencies | |
| `depends_on_event_id` | lupo_gov_event_dependencies | |
| `detail_id` | lupo_channel_boot_detail | |
| `device_id` | lupo_sessions | |
| `dialog_message_id` | lupo_dialog_messages | |
| `dialog_thread_id` | lupo_dialog_messages, lupo_dialog_threads | |
| `doctrine_evolution_audit_id` | lupo_doctrine_evolution_audit | |
| `doctrine_refinement_id` | lupo_doctrine_refinements | |
| `document_chunk_id` | lupo_document_chunks | |
| `document_embedding_id` | lupo_document_embeddings | |
| `document_id` | lupo_document_chunks, lupo_documents | |
| `domain_id` | lupo_actor_capabilities, lupo_actor_conflicts, lupo_actor_edges, lupo_agent_faucets, lupo_agent_properties, lupo_agent_tool_calls, lupo_api_rate_limits, lupo_api_token_logs, lupo_api_tokens, lupo_api_webhooks, lupo_documents, lupo_entity_edges, lupo_entity_properties, lupo_search_index, lupo_user_comments | |
| `edge_id` | lupo_edges, lupo_gov_event_actor_edges | |
| `edge_type_id` | lupo_edge_types | |
| `emotional_geometry_calibration_id` | lupo_emotional_geometry_calibrations | |
| `entity_edge_id` | lupo_entity_edges | |
| `entity_id` | lupo_actor_actions, lupo_audit_log, lupo_entity_properties, lupo_interpretation_log, lupo_search_index, lupo_search_rebuild_log, lupo_semantic_translations | |
| `entity_index_id` | lupo_registry_import | |
| `entity_property_id` | lupo_entity_properties | |
| `escalated_to_actor_id` | lupo_channel_escalations | |
| `escalated_to_operator_id` | lupo_dialog_threads | |
| `escalation_id` | lupo_channel_escalations | |
| `event_id` | lupo_cip_analytics, lupo_event_log, lupo_event_metadata, lupo_meta_log_events | |
| `external_event_id` | lupo_agent_external_events | |
| `faucet_id` | lupo_agent_faucet_credentials, lupo_agent_tool_calls | |
| `federation_category_id` | lupo_federation_categories, lupo_federation_category_map | |
| `federation_category_map_id` | lupo_federation_category_map | |
| `federation_discovery_id` | lupo_federation_discovery | |
| `federation_node_id` | lupo_channels, lupo_contents, lupo_departments, lupo_dialog_threads, lupo_federation_category_map, lupo_federation_nodes, lupo_modules, lupo_registry, lupo_sessions, lupo_unregistry | |
| `federations_node_id` | lupo_analytics_visits, lupo_collection_tab_map, lupo_collection_tabs, lupo_collections | |
| `file_id` | lupo_agent_files, lupo_channel_files | |
| `from_actor_id` | lupo_dialog_messages, lupo_notifications | |
| `from_page_id` | lupo_analytics_paths | |
| `gov_event_conflict_id` | lupo_gov_event_conflicts | |
| `gov_event_dependency_id` | lupo_gov_event_dependencies | |
| `gov_event_id` | lupo_gov_event_actor_edges, lupo_gov_event_conflicts, lupo_gov_event_dependencies, lupo_gov_event_references, lupo_gov_events, lupo_gov_timeline_nodes, lupo_gov_valuations | |
| `governance_overrid_id` | lupo_governance_overrides | |
| `hashtag_id` | lupo_hashtags | |
| `heartbeat_id` | lupo_agent_heartbeats | |
| `help_topic_id` | lupo_help_topics | |
| `help_tree_id` | lupo_help_tree | |
| `hotfix_id` | lupo_hotfix_registry | |
| `import_registry_id` | lupo_registry_import | |
| `imposed_by_actor_id` | lupo_kapu_events | |
| `interface_translation_id` | lupo_interface_translations | |
| `interpretation_log_id` | lupo_interpretation_log | |
| `item_id` | lupo_collection_tab_map | |
| `kapu_companion_agent_id` | lupo_kapu_restoration_paths | |
| `kapu_id` | lupo_kapu_events | |
| `labs_declaration_id` | lupo_labs_declarations | |
| `labs_violation_id` | lupo_labs_violations | |
| `lead_id` | lupo_crm_lead_messages | |
| `left_object_id` | lupo_edges, lupo_truth_relations | |
| `link_id` | lupo_agent_experiences | |
| `log_id` | lupo_system_logs | |
| `log_type_id` | lupo_channel_log_types, lupo_channel_logs | |
| `lupo_user_id` | lupo_crafty_user_mapping | |
| `mapping_id` | lupo_legacy_content_mapping | |
| `memory_event_id` | lupo_memory_events | |
| `memory_rollup_id` | lupo_memory_rollups | |
| `message_id` | lupo_agent_tool_calls | |
| `meta_id` | lupo_human_history_meta | |
| `metadata_id` | lupo_event_metadata | |
| `metric_id` | lupo_metrics_archive_legacy | |
| `module_department_id` | lupo_modules_departments | |
| `module_id` | lupo_api_webhooks, lupo_crafty_syntax_chat_mod_departments, lupo_modules, lupo_modules_departments | |
| `mood_assignment_id` | lupo_mood_assignments | |
| `mood_id` | lupo_mood_assignments, lupo_mood_registry | |
| `multi_agent_critique_sync_id` | lupo_multi_agent_critique_sync | |
| `navigation_id` | lupo_semantic_navigation_overview | |
| `new_id` | lupo_anubis_redirects | |
| `notification_id` | lupo_notifications | |
| `object_id` | lupo_truth_questions_map | |
| `old_id` | lupo_anubis_redirects | |
| `operator_user_id` | lupo_crafty_syntax_auto_invite | |
| `original_id` | lupo_anubis_mirrored | |
| `orphan_id` | lupo_anubis_orphaned | |
| `pack_role_registry_id` | lupo_pack_role_registry | |
| `parent_call_id` | lupo_agent_tool_calls | |
| `parent_category_id` | lupo_semantic_categories | |
| `parent_channel_id` | lupo_channels | |
| `parent_comment_id` | lupo_user_comments | |
| `parent_context_id` | lupo_contexts | |
| `parent_id` | lupo_analytics_referers_periods, lupo_collections, lupo_help_tree | |
| `parent_node_id` | lupo_gov_timeline_nodes | |
| `parent_snapshot_id` | lupo_agent_context_snapshots | |
| `path_id` | lupo_kapu_restoration_paths | |
| `pattern_id` | lupo_persona_dialogue_patterns | |
| `permission_id` | lupo_permissions | |
| `persona_id` | lupo_actor_persona_relationships, lupo_persona_dialogue_patterns, lupo_persona_profiles | |
| `previous_version_id` | lupo_agent_versions | |
| `provider_id` | lupo_auth_users | |
| `record_id` | lupo_anubis_deletion_log | |
| `reference_cited_by_id` | lupo_reference_cited_by | |
| `reference_id` | lupo_gov_event_references | |
| `reference_object_id` | lupo_reference_cited_by, lupo_reference_objects | |
| `referer_content_id` | lupo_analytics_referers_periods, lupo_referers | |
| `referer_id` | lupo_referers | |
| `refinement_id` | lupo_doctrine_evolution_audit | |
| `registry_id` | lupo_registry | |
| `related_tool_call_id` | lupo_agent_context_snapshots | |
| `relationship_id` | lupo_actor_persona_relationships, lupo_relationships, lupo_semantic_relationships | |
| `replacement_id` | lupo_anubis_deletion_log | |
| `resolved_to_local_id` | lupo_registry_import | |
| `right_object_id` | lupo_edges, lupo_truth_relations | |
| `row_id` | lupo_anubis_events, lupo_anubis_revised, lupo_mood_assignments | |
| `rule_id` | lupo_channel_escalation_rules | |
| `search_index_id` | lupo_search_index, lupo_semantic_search_index | |
| `search_rebuild_log_id` | lupo_search_rebuild_log | |
| `semantic_overlay_id` | lupo_semantic_overlays | |
| `semantic_path_id` | lupo_semantic_paths | |
| `semantic_translation_id` | lupo_semantic_translations | |
| `semantic_view_id` | lupo_semantic_content_views | |
| `session_event_id` | lupo_session_events | |
| `session_id` | lupo_actor_events, lupo_agent_context_snapshots, lupo_analytics_visits, lupo_channel_boot_log, lupo_session_events, lupo_sessions, lupo_tab_events | |
| `snapshot_id` | lupo_temporal_coherence_snapshots | |
| `source_actor_id` | lupo_actor_edges | |
| `source_content_id` | lupo_semantic_relationships | |
| `source_entity_id` | lupo_entity_edges | |
| `source_federation_node_id` | lupo_registry_import | |
| `source_id` | lupo_relationships | |
| `source_page_id` | lupo_semantic_paths | |
| `star_id` | lupo_agent_experiences, lupo_emotional_stars | |
| `system_config_id` | lupo_system_config | |
| `system_event_id` | lupo_system_events | |
| `tab_event_id` | lupo_tab_events | |
| `tab_id` | lupo_actor_events, lupo_session_events, lupo_tab_events | |
| `table_id` | lupo_audit_log | |
| `tag_id` | lupo_semantic_tags | |
| `target_actor_id` | lupo_actor_edges | |
| `target_content_id` | lupo_semantic_relationships | |
| `target_entity_id` | lupo_entity_edges | |
| `target_id` | lupo_actor_object_edges, lupo_permissions, lupo_relationships | |
| `target_page_id` | lupo_semantic_paths | |
| `thread_id` | lupo_agent_tool_calls, lupo_channel_escalations | |
| `timeline_node_id` | lupo_gov_timeline_nodes | |
| `tldnr_id` | lupo_tldnr | |
| `to_actor_id` | lupo_dialog_messages, lupo_notifications | |
| `to_page_id` | lupo_analytics_paths | |
| `translation_id` | lupo_emotional_translations | |
| `truth_answer_id` | lupo_truth_answers, lupo_truth_evidence | |
| `truth_evidence_id` | lupo_truth_evidence, lupo_truth_sources | |
| `truth_item_id` | lupo_actor_truth_edges, lupo_truth_items | |
| `truth_question_id` | lupo_truth_answers, lupo_truth_questions, lupo_truth_questions_map | |
| `truth_question_parent_id` | lupo_truth_questions | |
| `truth_questions_map_id` | lupo_truth_questions_map | |
| `truth_relation_id` | lupo_truth_relations | |
| `truth_sourc_id` | lupo_truth_sources | |
| `truth_topic_id` | lupo_truth_topics | |
| `upload_id` | lupo_uploads | |
| `user_comment_id` | lupo_user_comments | |
| `user_id` | lupo_auth_audit_log, lupo_collection_tabs, lupo_crafty_syntax_layer_invites, lupo_permissions, lupo_user_comments | |
| `utc_group_id` | lupo_gov_events | |
| `valuation_id` | lupo_gov_valuations | |
| `visit_id` | lupo_visits | |
| `world_event_id` | lupo_world_events | |
| `world_id` | lupo_actor_events, lupo_session_events, lupo_tab_events, lupo_world_events, lupo_world_registry | |