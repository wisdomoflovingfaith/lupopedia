# Documentation Validation Report

- Generated on: 2026-03-24 17:48:17 UTC
## Summary
- Total Checked: 244
- Total Failing Entries: 197

- Cutoff UTC for footer freshness: 2026-03-01 00:00:00

## Directory: active

### [MISSING_DOCS]
- `lupo_actor_apps`
- `lupo_analytics_campaign_vars`
- `lupo_answers`
- `lupo_anubis_log`
- `lupo_anubis_operations`
- `lupo_channel_boot_detail`
- `lupo_channel_departments`
- `lupo_collection_links`
- `lupo_collection_map`
- `lupo_documentation_frameworks`
- `lupo_edge_type_definitions`
- `lupo_edge_types`
- `lupo_escalation_tasks`
- `lupo_federated_trust`
- `lupo_federation_discovery`
- `lupo_folder_map`
- `lupo_folders`
- `lupo_hashtag_map`
- `lupo_human_request_context`
- `lupo_human_request_responses`
- `lupo_human_requests`
- `lupo_paths`
- `lupo_question_map`
- `lupo_questions`
- `lupo_reference_cited_by`
- `lupo_reference_links`
- `lupo_reference_map`
- `lupo_reference_objects`
- `lupo_references`
- `lupo_rolls`
- `lupo_routing_decisions`
- `lupo_rule_logs`
- `lupo_rule_targets`
- `lupo_schema_migrations`
- `lupo_system_health_snapshots`
- `lupo_thread_metadata`
- `lupo_world_registry`

### [EXTRA_DOCS_WITHOUT_TOON]
- `lupo_actor_aliases`
- `lupo_actor_events`
- `lupo_actor_object_edges`
- `lupo_actor_persona_relationships`
- `lupo_actor_relationship_rules`
- `lupo_actor_truth_edges`
- `lupo_analytics_events`
- `lupo_analytics_paths`
- `lupo_analytics_referers_periods`
- `lupo_analytics_visits`
- `lupo_analytics_visits_daily`
- `lupo_analytics_visits_monthly`
- `lupo_channel_boot_log`
- `lupo_channel_log_types`
- `lupo_channel_logs`
- `lupo_channel_tables_overview`
- `lupo_document_embeddings`
- `lupo_emotional_constellations`
- `lupo_emotional_stars`
- `lupo_emotional_translations`
- `lupo_entity_properties`
- `lupo_event_log`
- `lupo_gov_event_actor_edges`
- `lupo_gov_event_conflicts`
- `lupo_gov_event_dependencies`
- `lupo_gov_event_references`
- `lupo_gov_events`
- `lupo_gov_timeline_nodes`
- `lupo_gov_valuations`
- `lupo_human_history_meta`
- `lupo_interface_translations`
- `lupo_kapu_events`
- `lupo_kapu_restoration_paths`
- `lupo_llm_performance`
- `lupo_memory_events`
- `lupo_meta_log_events`
- `lupo_metrics_archive_legacy`
- `lupo_mood_assignments`
- `lupo_mood_registry`
- `lupo_pack_role_registry`
- `lupo_persona_dialogue_patterns`
- `lupo_persona_profiles`
- `lupo_session_events`
- `lupo_session_recovery`
- `lupo_system_events`
- `lupo_system_logs`
- `lupo_tab_events`
- `lupo_task_assignments`
- `lupo_task_events`
- `lupo_task_priorities`
- `lupo_task_statuses`
- `lupo_task_types`
- `lupo_tasks`
- `lupo_temporal_coherence_snapshots`
- `lupo_tldnr`
- `lupo_world_events`

### lupo_action_authorization: [OK]

### lupo_actor_actions: [OK]

### lupo_actor_aliases: [FAIL]
- Missing TOON: `lupo_actor_aliases.toon`
- Missing or invalid YAML frontmatter

### lupo_actor_auth_users: [OK]

### lupo_actor_capabilities: [FAIL]
- Missing column in doc: `actor_capability_id`
- Missing column in doc: `approval_agent_id`
- Missing column in doc: `capability_description`
- Missing column in doc: `capability_key`
- Missing column in doc: `domain_id`
- Missing column in doc: `max_calls_per_hour`
- Missing column in doc: `requires_approval`
- Missing column in doc: `scope_limitation`
- Extra column in doc: `capability_id`
- Extra column in doc: `capability_name`
- Extra column in doc: `capability_type`
- Extra column in doc: `is_active`
- Extra column in doc: `scope`

### lupo_actor_channel_roles: [FAIL]
- Missing or unreadable `| Column | Type |` table

### lupo_actor_channels: [FAIL]
- Missing column in doc: `actor_name`

### lupo_actor_collections: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_actor_conflicts: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_actor_departments: [FAIL]
- Missing or unreadable `| Column | Type |` table

### lupo_actor_edges: [FAIL]
- Missing or unreadable `| Column | Type |` table
- Missing or invalid YAML frontmatter

### lupo_actor_events: [FAIL]
- Missing TOON: `lupo_actor_events.toon`

### lupo_actor_handshakes: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_actor_history: [FAIL]
- Missing or unreadable `| Column | Type |` table
- Missing or invalid YAML frontmatter

### lupo_actor_moods: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_actor_object_edges: [FAIL]
- Missing TOON: `lupo_actor_object_edges.toon`
- Missing or invalid YAML frontmatter

### lupo_actor_persona_relationships: [FAIL]
- Missing TOON: `lupo_actor_persona_relationships.toon`
- Missing or invalid YAML frontmatter

### lupo_actor_relationship_rules: [FAIL]
- Missing TOON: `lupo_actor_relationship_rules.toon`
- Missing or unreadable `| Column | Type |` table
- Missing or invalid YAML frontmatter

### lupo_actor_reply_templates: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_actor_traits: [OK]

### lupo_actor_truth_edges: [FAIL]
- Missing TOON: `lupo_actor_truth_edges.toon`
- Missing or invalid YAML frontmatter

### lupo_actors: [OK]

### lupo_agent_context_snapshots: [OK]

### lupo_agent_dependencies: [OK]

### lupo_agent_experiences: [OK]

### lupo_agent_external_events: [OK]

### lupo_agent_faucet_credentials: [OK]

### lupo_agent_faucets: [FAIL]
- Missing column in doc: `faucet_class`

### lupo_agent_files: [OK]

### lupo_agent_heartbeats: [FAIL]
- Missing column in doc: `updated_ymdhis`

### lupo_agent_tool_calls: [FAIL]
- Missing column in doc: `archived_ymdhis`
- Missing column in doc: `deleted_ymdhis`
- Missing column in doc: `is_deleted`
- Missing column in doc: `updated_ymdhis`

### lupo_agent_versions: [OK]

### lupo_agents: [OK]

### lupo_aliases: [FAIL]
- Missing column in doc: `created_ymdhis`
- Extra column in doc: `created_at`

### lupo_analytics_events: [FAIL]
- Missing TOON: `lupo_analytics_events.toon`

### lupo_analytics_paths: [FAIL]
- Missing TOON: `lupo_analytics_paths.toon`
- Missing or invalid YAML frontmatter

### lupo_analytics_referers_periods: [FAIL]
- Missing TOON: `lupo_analytics_referers_periods.toon`
- Missing or invalid YAML frontmatter

### lupo_analytics_visits: [FAIL]
- Missing TOON: `lupo_analytics_visits.toon`

### lupo_analytics_visits_daily: [FAIL]
- Missing TOON: `lupo_analytics_visits_daily.toon`
- Missing or invalid YAML frontmatter

### lupo_analytics_visits_monthly: [FAIL]
- Missing TOON: `lupo_analytics_visits_monthly.toon`
- Missing or invalid YAML frontmatter

### lupo_anubis_events: [FAIL]
- Missing or unreadable `| Column | Type |` table

### lupo_anubis_processing_log: [FAIL]
- Missing or unreadable `| Column | Type |` table

### lupo_anubis_quarantine: [FAIL]
- Missing or unreadable `| Column | Type |` table

### lupo_anubis_queue: [FAIL]
- Missing or unreadable `| Column | Type |` table

### lupo_anubis_recovery_attempts: [FAIL]
- Missing or unreadable `| Column | Type |` table

### lupo_anubis_redirects: [FAIL]
- Missing or unreadable `| Column | Type |` table

### lupo_api_clients: [OK]

### lupo_api_rate_limits: [OK]

### lupo_api_token_logs: [OK]

### lupo_api_tokens: [FAIL]
- Missing column in doc: `deleted_ymdhis`
- Missing column in doc: `is_deleted`
- Missing column in doc: `updated_ymdhis`

### lupo_api_webhooks: [OK]

### lupo_artifact_chunks: [OK]

### lupo_artifacts: [OK]

### lupo_atoms: [OK]

### lupo_audit_log: [OK]

### lupo_auth_audit_log: [FAIL]
- Missing column in doc: `auth_audit_log_id`
- Missing column in doc: `crafty_operator_id`
- Missing column in doc: `created_at`
- Missing column in doc: `error_message`
- Missing column in doc: `event_data`
- Missing column in doc: `event_type`
- Missing column in doc: `ip_address`
- Missing column in doc: `success`
- Missing column in doc: `system_context`
- Missing column in doc: `updated_at`
- Missing column in doc: `user_agent`
- Missing column in doc: `user_id`
- Extra column in doc: ``actor_id``
- Extra column in doc: ``actor_type``
- Extra column in doc: ``audit_log_id``
- Extra column in doc: ``auth_provider_id``
- Extra column in doc: ``created_ymdhis``
- Extra column in doc: ``details_json``
- Extra column in doc: ``device_fingerprint``
- Extra column in doc: ``event_type``
- Extra column in doc: ``failure_reason``
- Extra column in doc: ``ip_address``
- Extra column in doc: ``location_city``
- Extra column in doc: ``location_country``
- Extra column in doc: ``provider_key``
- Extra column in doc: ``request_id``
- Extra column in doc: ``risk_score``
- Extra column in doc: ``session_id``
- Extra column in doc: ``success``
- Extra column in doc: ``user_agent``

### lupo_auth_providers: [OK]

### lupo_auth_users: [OK]

### lupo_banned_actors: [FAIL]
- Missing column in doc: `actor_name`

### lupo_bans_log: [OK]

### lupo_calibration_impacts: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_capability_usage: [OK]

### lupo_channel_boot_detail_lifecycle: [FAIL]
- Missing column in doc: `channel_id`
- Missing column in doc: `content_items_loaded`
- Missing column in doc: `created_ymdhis`
- Missing column in doc: `detail_duration_ms`
- Missing column in doc: `detail_end_time`
- Missing column in doc: `detail_lifecycle_id`
- Missing column in doc: `detail_start_time`
- Missing column in doc: `detail_status`
- Missing column in doc: `error_message`
- Missing column in doc: `lifecycle_id`
- Missing column in doc: `total_content_items`
- Extra column in doc: ``channel_id``
- Extra column in doc: ``content_items_loaded``
- Extra column in doc: ``created_ymdhis``
- Extra column in doc: ``detail_duration_ms``
- Extra column in doc: ``detail_end_time``
- Extra column in doc: ``detail_lifecycle_id``
- Extra column in doc: ``detail_start_time``
- Extra column in doc: ``detail_status``
- Extra column in doc: ``error_message``
- Extra column in doc: ``lifecycle_id``
- Extra column in doc: ``total_content_items``

### lupo_channel_boot_lifecycle: [FAIL]
- Missing or unreadable `| Column | Type |` table
- Missing or invalid YAML frontmatter

### lupo_channel_boot_log: [FAIL]
- Missing TOON: `lupo_channel_boot_log.toon`
- Missing or invalid YAML frontmatter

### lupo_channel_content: [FAIL]
- Missing column in doc: `channel_content_id`
- Missing column in doc: `channel_id`
- Missing column in doc: `created_ymdhis`
- Missing column in doc: `federation_node_id`
- Missing column in doc: `file_path`
- Missing column in doc: `is_deleted`
- Missing column in doc: `metadata_json`
- Missing column in doc: `updated_ymdhis`
- Missing column in doc: `web_path`
- Extra column in doc: ``channel_content_id``
- Extra column in doc: ``channel_id``
- Extra column in doc: ``federation_node_id``

### lupo_channel_escalation_rules: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_channel_escalations: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_channel_files: [FAIL]
- Missing or unreadable `| Column | Type |` table

### lupo_channel_log_types: [FAIL]
- Missing TOON: `lupo_channel_log_types.toon`
- Missing or invalid YAML frontmatter

### lupo_channel_logs: [FAIL]
- Missing TOON: `lupo_channel_logs.toon`
- Missing or invalid YAML frontmatter

### lupo_channel_state: [FAIL]
- Missing column in doc: `active_actors_json`
- Missing column in doc: `archive_flag`
- Missing column in doc: `context_vector`
- Missing column in doc: `decay_policy`
- Missing column in doc: `edge_visibility`
- Missing column in doc: `emotional_state_json`
- Missing column in doc: `last_activity_ymdhis`
- Missing column in doc: `layers_enabled_json`
- Missing column in doc: `metadata_json`
- Missing column in doc: `mood_framework`
- Missing column in doc: `observer_actors_json`
- Missing column in doc: `operational_mode`
- Missing column in doc: `recent_topics_json`
- Missing column in doc: `retention_policy`
- Missing column in doc: `routing_rules`
- Missing column in doc: `semantic_weight`
- Missing column in doc: `speaker_actors_json`
- Missing column in doc: `trend_score`
- Extra column in doc: `deleted_ymdhis`
- Extra column in doc: `federation_node_id`
- Extra column in doc: `is_deleted`
- Extra column in doc: `state_key`
- Extra column in doc: `state_type`
- Extra column in doc: `state_value`
- Extra column in doc: `updated_by_actor_id`

### lupo_channel_tables_overview: [FAIL]
- Missing TOON: `lupo_channel_tables_overview.toon`
- Missing or unreadable `| Column | Type |` table
- Missing or invalid YAML frontmatter

### lupo_channels: [FAIL]
- Missing column in doc: `access_level`
- Missing column in doc: `channel_config`
- Missing column in doc: `last_activity_ymdhis`
- Missing column in doc: `owner_actor_id`
- Missing column in doc: `visibility_status`

### lupo_cip_analytics: [OK]

### lupo_cip_propagation_tracking: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_cip_trends: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_collection_tab_map: [OK]

### lupo_collection_tab_paths: [OK]

### lupo_collection_tabs: [OK]

### lupo_collections: [OK]

### lupo_comments: [OK]

### lupo_contents: [FAIL]
- Missing column in doc: `atom_mappings`
- Missing column in doc: `body`
- Missing column in doc: `category_mappings`
- Missing column in doc: `comment_count`
- Missing column in doc: `content`
- Missing column in doc: `content_events`
- Missing column in doc: `content_references`
- Missing column in doc: `content_sections`
- Missing column in doc: `content_url`
- Missing column in doc: `custom_path`
- Missing column in doc: `default_collection_id`
- Missing column in doc: `department_id`
- Missing column in doc: `description`
- Missing column in doc: `dialog_notes`
- Missing column in doc: `federation_node_id`
- Missing column in doc: `federation_source_url`
- Missing column in doc: `file_last_modified_system_version`
- Missing column in doc: `file_last_modified_utc`
- Missing column in doc: `file_path_from_root`
- Missing column in doc: `hashtags`
- Missing column in doc: `inbound_links`
- Missing column in doc: `is_active`
- Missing column in doc: `is_template`
- Missing column in doc: `like_count`
- Missing column in doc: `like_users`
- Missing column in doc: `media_attachments`
- Missing column in doc: `question_mappings`
- Missing column in doc: `revision_history`
- Missing column in doc: `seo_keywords`
- Missing column in doc: `share_count`
- Missing column in doc: `share_users`
- Missing column in doc: `source_title`
- Missing column in doc: `source_url`
- Missing column in doc: `tag_relationships`
- Missing column in doc: `tags`
- Missing column in doc: `triage_notes`
- Missing column in doc: `triage_status`
- Missing column in doc: `utc_cycle`
- Missing column in doc: `version_number`
- Missing column in doc: `view_count`
- Extra column in doc: `body / content`

### lupo_context_edges: [OK]

### lupo_contexts: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_contexts_map: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_crafty_syntax_auto_invite: [OK]

### lupo_crafty_syntax_chat_mod_departments: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_crafty_syntax_chat_questions: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_crafty_syntax_layer_invites: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_crafty_syntax_leave_message: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_crafty_user_mapping: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_crm_lead_messages: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_crm_leads: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_decision_edges: [OK]

### lupo_decision_evidence: [FAIL]
- Missing column in doc: `channel_id`
- Missing column in doc: `confidence`
- Missing column in doc: `created_ymdhis`
- Missing column in doc: `decision_evidence_id`
- Missing column in doc: `decision_id`
- Missing column in doc: `deleted_ymdhis`
- Missing column in doc: `evidence_source`
- Missing column in doc: `evidence_type`
- Missing column in doc: `evidence_value`
- Missing column in doc: `federation_node_id`
- Missing column in doc: `is_deleted`
- Missing column in doc: `likelihood`
- Missing column in doc: `project_id`
- Missing column in doc: `status`
- Missing column in doc: `updated_ymdhis`
- Extra column in doc: ``channel_id``
- Extra column in doc: ``decision_evidence_id``
- Extra column in doc: ``decision_id``
- Extra column in doc: ``project_id``

### lupo_decision_influences: [OK]

### lupo_decisions: [FAIL]
- Missing column in doc: `abandoned_ymdhis`
- Missing column in doc: `actor_id`
- Missing column in doc: `channel_id`
- Missing column in doc: `created_by_actor_id`
- Missing column in doc: `created_ymdhis`
- Missing column in doc: `decision_id`
- Missing column in doc: `decision_key`
- Missing column in doc: `decision_status`
- Missing column in doc: `decision_type`
- Missing column in doc: `deleted_ymdhis`
- Missing column in doc: `depth`
- Missing column in doc: `federation_node_id`
- Missing column in doc: `is_deleted`
- Missing column in doc: `origin_decision_id`
- Missing column in doc: `parent_decision_id`
- Missing column in doc: `probability`
- Missing column in doc: `probability_lower`
- Missing column in doc: `probability_model`
- Missing column in doc: `probability_upper`
- Missing column in doc: `project_id`
- Missing column in doc: `pruned_ymdhis`
- Missing column in doc: `root_decision_id`
- Missing column in doc: `session_id`
- Missing column in doc: `state_snapshot_id`
- Missing column in doc: `updated_ymdhis`
- Extra column in doc: ``actor_id``
- Extra column in doc: ``channel_id``
- Extra column in doc: ``decision_id``
- Extra column in doc: ``project_id``
- Extra column in doc: ``session_id``

### lupo_department_metadata: [OK]

### lupo_department_roles: [OK]

### lupo_departments: [OK]

### lupo_dialog_channels: [FAIL]
- Missing or unreadable `| Column | Type |` table
- Missing or invalid YAML frontmatter

### lupo_dialog_messages: [OK]

### lupo_dialog_threads: [FAIL]
- Missing column in doc: `assigned_actor_id`
- Missing column in doc: `owner_actor_id`
- Missing column in doc: `thread_lineage`
- Missing column in doc: `thread_priority`
- Missing column in doc: `thread_type`
- Missing column in doc: `visibility_status`

### lupo_doctrine_evolution_audit: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_document_embeddings: [FAIL]
- Missing TOON: `lupo_document_embeddings.toon`
- Missing or invalid YAML frontmatter

### lupo_edge_map: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_edges: [OK]

### lupo_emotional_constellations: [FAIL]
- Missing TOON: `lupo_emotional_constellations.toon`
- Missing or invalid YAML frontmatter

### lupo_emotional_frameworks: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_emotional_geometry_calibrations: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_emotional_stars: [FAIL]
- Missing TOON: `lupo_emotional_stars.toon`
- Missing or invalid YAML frontmatter

### lupo_emotional_translations: [FAIL]
- Missing TOON: `lupo_emotional_translations.toon`
- Missing or invalid YAML frontmatter

### lupo_entity_properties: [FAIL]
- Missing TOON: `lupo_entity_properties.toon`
- Missing or invalid YAML frontmatter

### lupo_event_log: [FAIL]
- Missing TOON: `lupo_event_log.toon`
- Missing or invalid YAML frontmatter

### lupo_event_metadata: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_federation_categories: [FAIL]
- Missing or unreadable `| Column | Type |` table

### lupo_federation_category_map: [FAIL]
- Missing or unreadable `| Column | Type |` table

### lupo_federation_nodes: [OK]

### lupo_gov_event_actor_edges: [FAIL]
- Missing TOON: `lupo_gov_event_actor_edges.toon`
- Missing or invalid YAML frontmatter

### lupo_gov_event_conflicts: [FAIL]
- Missing TOON: `lupo_gov_event_conflicts.toon`
- Missing or invalid YAML frontmatter

### lupo_gov_event_dependencies: [FAIL]
- Missing TOON: `lupo_gov_event_dependencies.toon`
- Missing or invalid YAML frontmatter

### lupo_gov_event_references: [FAIL]
- Missing TOON: `lupo_gov_event_references.toon`
- Missing or invalid YAML frontmatter

### lupo_gov_events: [FAIL]
- Missing TOON: `lupo_gov_events.toon`
- Missing or invalid YAML frontmatter

### lupo_gov_timeline_nodes: [FAIL]
- Missing TOON: `lupo_gov_timeline_nodes.toon`
- Missing or invalid YAML frontmatter

### lupo_gov_valuations: [FAIL]
- Missing TOON: `lupo_gov_valuations.toon`
- Missing or invalid YAML frontmatter

### lupo_governance_overrides: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_hashtags: [FAIL]
- Missing column in doc: `label`
- Missing column in doc: `tag_slug`
- Missing column in doc: `use_count`
- Extra column in doc: `description`
- Extra column in doc: `hashtag_slug`
- Extra column in doc: `meta_json`
- Missing or invalid YAML frontmatter

### lupo_help_topics: [OK]

### lupo_help_tree: [OK]

### lupo_hotfix_registry: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_human_history_meta: [FAIL]
- Missing TOON: `lupo_human_history_meta.toon`
- Missing or invalid YAML frontmatter

### lupo_interface_translations: [FAIL]
- Missing TOON: `lupo_interface_translations.toon`
- Missing or invalid YAML frontmatter

### lupo_interpretation_log: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_kapu_events: [FAIL]
- Missing TOON: `lupo_kapu_events.toon`
- Missing or invalid YAML frontmatter

### lupo_kapu_restoration_paths: [FAIL]
- Missing TOON: `lupo_kapu_restoration_paths.toon`
- Missing or invalid YAML frontmatter

### lupo_labs_declarations: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_labs_violations: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_legacy_content_mapping: [OK]

### lupo_llm_performance: [FAIL]
- Missing TOON: `lupo_llm_performance.toon`
- Missing or unreadable `| Column | Type |` table
- Missing or invalid YAML frontmatter

### lupo_memory_events: [FAIL]
- Missing TOON: `lupo_memory_events.toon`
- Missing or invalid YAML frontmatter

### lupo_memory_rollups: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_meta_log_events: [FAIL]
- Missing TOON: `lupo_meta_log_events.toon`
- Missing or invalid YAML frontmatter

### lupo_metadata: [FAIL]
- Missing column in doc: `channel_id`
- Missing column in doc: `class_name`
- Missing column in doc: `created_ymdhis`
- Missing column in doc: `deleted_ymdhis`
- Missing column in doc: `domain_id`
- Missing column in doc: `entity_id`
- Missing column in doc: `entity_type`
- Missing column in doc: `is_deleted`
- Missing column in doc: `meta_type`
- Missing column in doc: `metadata_id`
- Missing column in doc: `parent_metadata_id`
- Missing column in doc: `property_key`
- Missing column in doc: `property_value`
- Missing column in doc: `schema_ref`
- Missing column in doc: `updated_ymdhis`
- Extra column in doc: ``domain_id``
- Extra column in doc: ``entity_id``
- Extra column in doc: ``entity_type``
- Extra column in doc: ``metadata_id``
- Extra column in doc: ``property_key``

### lupo_metrics_archive_legacy: [FAIL]
- Missing TOON: `lupo_metrics_archive_legacy.toon`
- Missing or invalid YAML frontmatter

### lupo_modules: [OK]

### lupo_mood_assignments: [FAIL]
- Missing TOON: `lupo_mood_assignments.toon`
- Missing or invalid YAML frontmatter

### lupo_mood_registry: [FAIL]
- Missing TOON: `lupo_mood_registry.toon`
- Missing or invalid YAML frontmatter

### lupo_multi_agent_critique_sync: [FAIL]
- Missing or unreadable `| Column | Type |` table

### lupo_notifications: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_orchestrator_rules: [FAIL]
- Missing column in doc: `applies_to_json`
- Missing column in doc: `checksum`
- Missing column in doc: `enforcement_level`
- Missing column in doc: `is_active`
- Missing column in doc: `orchestrator_actor`
- Missing column in doc: `rule_content`
- Missing column in doc: `rule_id`
- Missing column in doc: `rule_set_version`
- Missing column in doc: `rule_slug`
- Missing column in doc: `updated_ymdhis`
- Extra column in doc: ``orchestrator_actor``
- Extra column in doc: ``rule_id``
- Extra column in doc: ``rule_set_version``
- Extra column in doc: ``rule_slug``

### lupo_pack_role_registry: [FAIL]
- Missing TOON: `lupo_pack_role_registry.toon`
- Missing or invalid YAML frontmatter

### lupo_paths_summary: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_permissions: [FAIL]
- Missing column in doc: `department_id`
- Missing column in doc: `permission`
- Missing column in doc: `target_id`
- Missing column in doc: `target_type`
- Missing column in doc: `user_id`
- Extra column in doc: `actor_id`
- Extra column in doc: `granted_by_actor_id`
- Extra column in doc: `permission_action`
- Extra column in doc: `permission_name`
- Extra column in doc: `resource_id`
- Extra column in doc: `resource_type`
- Extra column in doc: `scope`

### lupo_persona_dialogue_patterns: [FAIL]
- Missing TOON: `lupo_persona_dialogue_patterns.toon`
- Missing or invalid YAML frontmatter

### lupo_persona_profiles: [FAIL]
- Missing TOON: `lupo_persona_profiles.toon`
- Missing or invalid YAML frontmatter

### lupo_projects: [FAIL]
- Missing column in doc: `created_by_actor_id`
- Missing column in doc: `created_ymdhis`
- Missing column in doc: `default_channel_id`
- Missing column in doc: `deleted_ymdhis`
- Missing column in doc: `description`
- Missing column in doc: `federation_node_id`
- Missing column in doc: `github_repository`
- Missing column in doc: `is_active`
- Missing column in doc: `is_archived`
- Missing column in doc: `is_deleted`
- Missing column in doc: `is_frozen`
- Missing column in doc: `metadata_json`
- Missing column in doc: `orchestrator_id`
- Missing column in doc: `project_id`
- Missing column in doc: `project_key`
- Missing column in doc: `project_name`
- Missing column in doc: `project_slug`
- Missing column in doc: `project_type`
- Missing column in doc: `status`
- Missing column in doc: `updated_by_actor_id`
- Missing column in doc: `updated_ymdhis`
- Extra column in doc: ``project_id``
- Extra column in doc: ``project_key``
- Extra column in doc: ``project_name``
- Extra column in doc: ``project_slug``

### lupo_referers: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_registry: [OK]

### lupo_registry_open: [FAIL]
- Missing or unreadable `| Column | Type |` table

### lupo_rules: [FAIL]
- Missing column in doc: `created_ymdhis`
- Missing column in doc: `deleted_ymdhis`
- Missing column in doc: `is_deleted`
- Missing column in doc: `rule_description`
- Missing column in doc: `rule_id`
- Missing column in doc: `rule_name`
- Missing column in doc: `rule_script`
- Missing column in doc: `rule_type`
- Missing column in doc: `rule_version`
- Missing column in doc: `updated_ymdhis`
- Extra column in doc: ``rule_description``
- Extra column in doc: ``rule_id``
- Extra column in doc: ``rule_name``
- Extra column in doc: ``rule_script``
- Extra column in doc: ``rule_type``
- Extra column in doc: ``rule_version``

### lupo_search_index: [FAIL]
- Missing column in doc: `body_text`
- Missing column in doc: `domain_id`
- Missing column in doc: `entity_id`
- Missing column in doc: `entity_type`
- Missing column in doc: `keywords_text`
- Missing column in doc: `relevance_score`
- Missing column in doc: `search_index_id`
- Missing column in doc: `search_metadata`
- Missing column in doc: `title_text`
- Extra column in doc: `content_id`
- Extra column in doc: `content_type`
- Extra column in doc: `index_id`
- Extra column in doc: `language_code`
- Extra column in doc: `search_terms`
- Extra column in doc: `semantic_tags`
- Extra column in doc: `weight`

### lupo_search_rebuild_log: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_semantic_index: [FAIL]
- Missing column in doc: `color`
- Missing column in doc: `created_by`
- Missing column in doc: `description`
- Missing column in doc: `entity_id`
- Missing column in doc: `entity_type`
- Missing column in doc: `is_active`
- Missing column in doc: `is_default`
- Missing column in doc: `json_data`
- Missing column in doc: `language_code`
- Missing column in doc: `layer`
- Missing column in doc: `name`
- Missing column in doc: `parent_id`
- Missing column in doc: `relationship_strength`
- Missing column in doc: `semantic_type`
- Missing column in doc: `slug`
- Missing column in doc: `sort_order`
- Missing column in doc: `source_content_id`
- Missing column in doc: `source_page_id`
- Missing column in doc: `target_content_id`
- Missing column in doc: `target_page_id`
- Missing column in doc: `template_path`
- Missing column in doc: `text_value`
- Missing column in doc: `timeframe`
- Missing column in doc: `title`
- Missing column in doc: `weight`
- Extra column in doc: `confidence`
- Extra column in doc: `created_by_actor_id`
- Extra column in doc: `relationship_type`
- Extra column in doc: `source_id`
- Extra column in doc: `source_type`
- Extra column in doc: `target_id`
- Extra column in doc: `target_type`

### lupo_session_events: [FAIL]
- Missing TOON: `lupo_session_events.toon`

### lupo_session_recovery: [FAIL]
- Missing TOON: `lupo_session_recovery.toon`

### lupo_sessions: [FAIL]
- Missing column in doc: `actor_name`
- Missing column in doc: `csrf_token`
- Missing column in doc: `ip_hash`
- Missing column in doc: `last_activity_ymdhis`
- Missing column in doc: `status`
- Missing column in doc: `ua_hash`
- Extra column in doc: `auth_method`
- Extra column in doc: `auth_provider`
- Extra column in doc: `channel_id`
- Extra column in doc: `deleted_ymdhis`
- Extra column in doc: `device_id`
- Extra column in doc: `device_type`
- Extra column in doc: `ip_address`
- Extra column in doc: `is_authenticated`
- Extra column in doc: `login_ymdhis`
- Extra column in doc: `session_data`
- Extra column in doc: `user_agent`

### lupo_system_commands: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_system_config: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_system_events: [FAIL]
- Missing TOON: `lupo_system_events.toon`
- Missing or invalid YAML frontmatter

### lupo_system_logs: [FAIL]
- Missing TOON: `lupo_system_logs.toon`

### lupo_tab_events: [FAIL]
- Missing TOON: `lupo_tab_events.toon`
- Missing or invalid YAML frontmatter

### lupo_task_assignments: [FAIL]
- Missing TOON: `lupo_task_assignments.toon`
- Missing or invalid YAML frontmatter

### lupo_task_events: [FAIL]
- Missing TOON: `lupo_task_events.toon`
- Missing or invalid YAML frontmatter

### lupo_task_priorities: [FAIL]
- Missing TOON: `lupo_task_priorities.toon`
- Missing or invalid YAML frontmatter

### lupo_task_statuses: [FAIL]
- Missing TOON: `lupo_task_statuses.toon`
- Missing or invalid YAML frontmatter

### lupo_task_types: [FAIL]
- Missing TOON: `lupo_task_types.toon`
- Missing or invalid YAML frontmatter

### lupo_tasks: [FAIL]
- Missing TOON: `lupo_tasks.toon`

### lupo_temporal_coherence_snapshots: [FAIL]
- Missing TOON: `lupo_temporal_coherence_snapshots.toon`
- Missing or invalid YAML frontmatter

### lupo_ticket_messages: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_tickets: [FAIL]
- Missing or invalid YAML frontmatter

### lupo_tldnr: [FAIL]
- Missing TOON: `lupo_tldnr.toon`
- Missing or invalid YAML frontmatter

### lupo_truth_answers: [OK]

### lupo_truth_knowledge: [OK]

### lupo_unified_log: [OK]

### lupo_uploads: [FAIL]
- Missing or unreadable `| Column | Type |` table

### lupo_visits: [FAIL]
- Missing column in doc: `deleted_ymdhis`
- Missing column in doc: `enter_table`
- Missing column in doc: `entercontentid`
- Missing column in doc: `exit_table`
- Missing column in doc: `exitcontentid`
- Missing column in doc: `instance_id`
- Missing column in doc: `is_deleted`
- Missing column in doc: `is_processed`
- Missing column in doc: `path_url`
- Missing column in doc: `session_id`
- Missing column in doc: `transition_metadata`
- Missing column in doc: `transition_type`
- Extra column in doc: `content_id`
- Extra column in doc: `date_ymd`
- Extra column in doc: `depth`
- Extra column in doc: `metadata_json`
- Extra column in doc: `page_domain`
- Extra column in doc: `page_path`
- Extra column in doc: `page_url`
- Extra column in doc: `updated_ymdhis`
- Extra column in doc: `visits`

### lupo_world_events: [FAIL]
- Missing TOON: `lupo_world_events.toon`
- Missing or invalid YAML frontmatter

## Directory: planning

### README: [FAIL]
- Missing TOON: `README.toon`
- Missing or unreadable `| Column | Type |` table

### table_lupo_actor_aliases.toon: [FAIL]
- Missing TOON: `table_lupo_actor_aliases.toon.toon`

### table_lupo_actor_object_edges.toon: [FAIL]
- Missing TOON: `table_lupo_actor_object_edges.toon.toon`

### table_lupo_actor_persona_relationships.toon: [FAIL]
- Missing TOON: `table_lupo_actor_persona_relationships.toon.toon`

### table_lupo_actor_relationship_rules.toon: [FAIL]
- Missing TOON: `table_lupo_actor_relationship_rules.toon.toon`

### table_lupo_actor_truth_edges.toon: [FAIL]
- Missing TOON: `table_lupo_actor_truth_edges.toon.toon`

### table_lupo_aliases.toon: [FAIL]
- Missing TOON: `table_lupo_aliases.toon.toon`

### table_lupo_analytics_referers_periods.toon: [FAIL]
- Missing TOON: `table_lupo_analytics_referers_periods.toon.toon`

### table_lupo_anubis_deletion_log.toon: [FAIL]
- Missing TOON: `table_lupo_anubis_deletion_log.toon.toon`

### table_lupo_anubis_mirrored.toon: [FAIL]
- Missing TOON: `table_lupo_anubis_mirrored.toon.toon`

### table_lupo_anubis_orphaned.toon: [FAIL]
- Missing TOON: `table_lupo_anubis_orphaned.toon.toon`

### table_lupo_anubis_revised.toon: [FAIL]
- Missing TOON: `table_lupo_anubis_revised.toon.toon`

### table_lupo_channel_boot_log.toon: [FAIL]
- Missing TOON: `table_lupo_channel_boot_log.toon.toon`

### table_lupo_comments.toon: [FAIL]
- Missing TOON: `table_lupo_comments.toon.toon`

### table_lupo_document_embeddings.toon: [FAIL]
- Missing TOON: `table_lupo_document_embeddings.toon.toon`

### table_lupo_documentation_frameworks.toon: [FAIL]
- Missing TOON: `table_lupo_documentation_frameworks.toon.toon`

### table_lupo_emotional_constellations.toon: [FAIL]
- Missing TOON: `table_lupo_emotional_constellations.toon.toon`

### table_lupo_emotional_stars.toon: [FAIL]
- Missing TOON: `table_lupo_emotional_stars.toon.toon`

### table_lupo_emotional_translations.toon: [FAIL]
- Missing TOON: `table_lupo_emotional_translations.toon.toon`

### table_lupo_entity_properties.toon: [FAIL]
- Missing TOON: `table_lupo_entity_properties.toon.toon`

### table_lupo_federated_trust.toon: [FAIL]
- Missing TOON: `table_lupo_federated_trust.toon.toon`

### table_lupo_federation_discovery.toon: [FAIL]
- Missing TOON: `table_lupo_federation_discovery.toon.toon`

### table_lupo_flare_headers.toon: [FAIL]
- Missing TOON: `table_lupo_flare_headers.toon.toon`

### table_lupo_gov_event_actor_edges.toon: [FAIL]
- Missing TOON: `table_lupo_gov_event_actor_edges.toon.toon`

### table_lupo_gov_event_conflicts.toon: [FAIL]
- Missing TOON: `table_lupo_gov_event_conflicts.toon.toon`

### table_lupo_gov_event_dependencies.toon: [FAIL]
- Missing TOON: `table_lupo_gov_event_dependencies.toon.toon`

### table_lupo_gov_event_references.toon: [FAIL]
- Missing TOON: `table_lupo_gov_event_references.toon.toon`

### table_lupo_gov_events.toon: [FAIL]
- Missing TOON: `table_lupo_gov_events.toon.toon`

### table_lupo_gov_timeline_nodes.toon: [FAIL]
- Missing TOON: `table_lupo_gov_timeline_nodes.toon.toon`

### table_lupo_gov_valuations.toon: [FAIL]
- Missing TOON: `table_lupo_gov_valuations.toon.toon`

### table_lupo_hashtags.toon: [FAIL]
- Missing TOON: `table_lupo_hashtags.toon.toon`

### table_lupo_hotfix_registry.toon: [FAIL]
- Missing TOON: `table_lupo_hotfix_registry.toon.toon`

### table_lupo_human_history_meta.toon: [FAIL]
- Missing TOON: `table_lupo_human_history_meta.toon.toon`

### table_lupo_interface_translations.toon: [FAIL]
- Missing TOON: `table_lupo_interface_translations.toon.toon`

### table_lupo_kapu_events.toon: [FAIL]
- Missing TOON: `table_lupo_kapu_events.toon.toon`

### table_lupo_kapu_restoration_paths.toon: [FAIL]
- Missing TOON: `table_lupo_kapu_restoration_paths.toon.toon`

### table_lupo_legacy_content_mapping.toon: [FAIL]
- Missing TOON: `table_lupo_legacy_content_mapping.toon.toon`

### table_lupo_llm_performance.toon: [FAIL]
- Missing TOON: `table_lupo_llm_performance.toon.toon`

### table_lupo_metrics_archive_legacy.toon: [FAIL]
- Missing TOON: `table_lupo_metrics_archive_legacy.toon.toon`

### table_lupo_modules_departments.toon: [FAIL]
- Missing TOON: `table_lupo_modules_departments.toon.toon`

### table_lupo_mood_assignments.toon: [FAIL]
- Missing TOON: `table_lupo_mood_assignments.toon.toon`

### table_lupo_mood_registry.toon: [FAIL]
- Missing TOON: `table_lupo_mood_registry.toon.toon`

### table_lupo_pack_role_registry.toon: [FAIL]
- Missing TOON: `table_lupo_pack_role_registry.toon.toon`

### table_lupo_persona_dialogue_patterns.toon: [FAIL]
- Missing TOON: `table_lupo_persona_dialogue_patterns.toon.toon`

### table_lupo_persona_profiles.toon: [FAIL]
- Missing TOON: `table_lupo_persona_profiles.toon.toon`

### table_lupo_reference_cited_by.toon: [FAIL]
- Missing TOON: `table_lupo_reference_cited_by.toon.toon`

### table_lupo_reference_objects.toon: [FAIL]
- Missing TOON: `table_lupo_reference_objects.toon.toon`

### table_lupo_registry_import.toon: [FAIL]
- Missing TOON: `table_lupo_registry_import.toon.toon`

### table_lupo_search_index.toon: [FAIL]
- Missing TOON: `table_lupo_search_index.toon.toon`

### table_lupo_session_recovery.toon: [FAIL]
- Missing TOON: `table_lupo_session_recovery.toon.toon`

### table_lupo_system_health_snapshots.toon: [FAIL]
- Missing TOON: `table_lupo_system_health_snapshots.toon.toon`

### table_lupo_task_assignments.toon: [FAIL]
- Missing TOON: `table_lupo_task_assignments.toon.toon`

### table_lupo_task_dependencies.toon: [FAIL]
- Missing TOON: `table_lupo_task_dependencies.toon.toon`

### table_lupo_temporal_coherence_snapshots.toon: [FAIL]
- Missing TOON: `table_lupo_temporal_coherence_snapshots.toon.toon`

### table_lupo_tldnr.toon: [FAIL]
- Missing TOON: `table_lupo_tldnr.toon.toon`

### table_lupo_unified_log.toon: [FAIL]
- Missing TOON: `table_lupo_unified_log.toon.toon`

