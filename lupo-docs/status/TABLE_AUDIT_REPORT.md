# Lupopedia Table Audit Report (March 2026)

This report lists all 170 tables in the Lupopedia database, grouped by namespace/prefix, with a 1–2 sentence description for each. Tables that are likely obsolete or require review are marked with **⚠️ ATTENTION**.

---

## 1. Actor System (`lupo_actor_`, `lupo_actors`)

- **lupo_actors**: Master table for all actors (human, AI, system), storing identity, type, and configuration.
- **lupo_actor_actions**: Logs actions performed by actors on entities, with metadata and type.
- **lupo_actor_apps**: Maps actors to their available apps or tools.
- **lupo_actor_auth_users**: Links actors to auth users, defining roles and routing priorities.
- **lupo_actor_availability_status**: Tracks actor online/offline status per channel.
- **lupo_actor_capabilities**: Lists capabilities/permissions for each actor, including limits and approval requirements.
- **lupo_actor_channels**: Associates actors with channels, including preferences and dialog output.
- **lupo_actor_channel_roles**: Assigns roles to actors within channels, including protocol and handshake status.
- **lupo_actor_collections**: Maps actors to collections, with access level and trust metadata.
- **lupo_actor_conflicts**: Records conflicts between actors, their type, summary, and resolution status.
- **lupo_actor_departments**: Links actors to departments and roles within them.
- **lupo_actor_handshakes**: Tracks handshake events for actors, including constraints and context.
- **lupo_actor_history**: Stores actor achievements, impact, and historical metrics.
- **lupo_actor_instances**: Instances of actors created from templates, department-scoped.
- **lupo_actor_lease_sessions**: Tracks leasing sessions where an auth user controls an actor.
- **lupo_actor_moods**: Stores RGB mood values for actors, supporting emotional state tracking.
- **lupo_actor_reply_templates**: Predefined reply templates for actors, by context.
- **lupo_actor_templates**: Templates for creating new actor instances from agents.

## 2. Agent & AI Subsystem (`lupo_agent_`, `lupo_agents`)

- **lupo_agents**: Master table for all AI agents, with configuration, model, and provider info.
- **lupo_agent_context_snapshots**: Stores serialized context snapshots for agent sessions.
- **lupo_agent_dependencies**: Tracks agent-to-agent dependencies and requirements.
- **lupo_agent_experiences**: Records agent experiences, intensity, and context.
- **lupo_agent_external_events**: Logs external events received by agents.
- **lupo_agent_faucets**: Defines agent execution surfaces (IDE/API), with model and provider.
- **lupo_agent_faucet_credentials**: Stores credentials for agent faucets (API keys, etc).
- **lupo_agent_files**: Tracks files associated with agents (uploads, configs).
- **lupo_agent_heartbeats**: Monitors agent liveness/status via heartbeat pings.
- **lupo_agent_tool_calls**: Logs tool calls made by agents, with input/output and cost.
- **lupo_agent_versions**: Tracks agent version history and semver details.

## 3. Anubis Operations (`lupo_anubis_`)

- **lupo_anubis_events**: Logs Anubis system events for auditing and recovery.
- **lupo_anubis_log**: Main log for Anubis operations, including severity and resolution.
- **lupo_anubis_operations**: Tracks background operations, targets, and details.
- **lupo_anubis_processing_log**: Logs processing steps for Anubis queue items.
- **lupo_anubis_quarantine**: Stores quarantined files and reasons for isolation.
- **lupo_anubis_queue**: Main queue for Anubis background processing.
- **lupo_anubis_recovery_attempts**: Tracks recovery attempts for queued items.
- **lupo_anubis_redirects**: Maps old IDs to new IDs after recovery or migration.

## 4. API & Authentication (`lupo_api_`, `lupo_auth_`)

- **lupo_api_clients**: Registered API clients with secrets and scopes.
- **lupo_api_rate_limits**: Tracks API rate limits per token, actor, or IP.
- **lupo_api_token_logs**: Logs API token usage and endpoint access.
- **lupo_api_tokens**: Stores issued API tokens and their metadata.
- **lupo_api_webhooks**: Registered webhooks for event notifications.
- **lupo_auth_audit_log**: Logs authentication events and errors.
- **lupo_auth_providers**: External auth providers (OAuth, SSO, etc).
- **lupo_auth_rate_limits**: Rate limits for authentication attempts.
- **lupo_auth_users**: Master table for all auth users (login credentials, profile).
- **lupo_auth_user_departments**: Maps auth users to departments and roles.

## 5. Communication & Channels (`lupo_channel_`, `lupo_channels`, `lupo_dialog_`)

- **lupo_channels**: Master table for all channels (discussion spaces).
- **lupo_channel_boot_detail** ⚠️ ATTENTION: Stores details about "booting" a channel. Likely obsolete if channels are now dialog-based.
- **lupo_channel_boot_detail_lifecycle** ⚠️ ATTENTION: Tracks lifecycle events for channel boot details. Review for obsolescence.
- **lupo_channel_boot_lifecycle** ⚠️ ATTENTION: Records overall lifecycle of channel boot operations. Review for obsolescence.
- **lupo_channel_content**: Maps content files to channels.
- **lupo_channel_departments**: Associates channels with departments.
- **lupo_channel_escalation_rules**: Defines escalation rules for channels.
- **lupo_channel_escalations**: Logs escalation events within channels.
- **lupo_channel_files**: Tracks files shared in channels.
- **lupo_channel_state**: Stores current state/status of channels.
- **lupo_dialog_channels**: Dialog-based channels for threaded discussions.
- **lupo_dialog_messages**: Messages within dialog threads.
- **lupo_dialog_threads**: Threaded discussions within dialog channels.

## 6. Collection & Content Management (`lupo_collection_`, `lupo_collections`, `lupo_content`)

- **lupo_collections**: Master table for all collections (groupings of content).
- **lupo_collection_links**: Links between collections and other objects.
- **lupo_collection_map**: Maps collections to their contents.
- **lupo_collection_tabs**: Tabs within collections for organization.
- **lupo_collection_tab_map**: Maps tabs to collections.
- **lupo_collection_tab_paths**: Stores paths for collection tabs.
- **lupo_contents**: Main content storage table.
- **lupo_comments**: Comments on content or collections.

## 7. Context & Semantics (`lupo_context_`, `lupo_contexts`, `lupo_semantic_`, `lupo_edges`)

- **lupo_contexts**: Master table for all semantic contexts.
- **lupo_contexts_map**: Maps contexts to related objects.
- **lupo_context_cards**: Stores context cards (semantic summaries).
- **lupo_context_edges**: Edges between contexts (semantic relationships).
- **lupo_edge_map**: Maps edges to their types and objects.
- **lupo_edges**: Master table for all semantic edges.
- **lupo_edge_types**: Types of semantic edges.
- **lupo_edge_type_definitions**: Definitions and allowed types for edges.
- **lupo_semantic_index**: Index for semantic search and retrieval.

## 8. Federation Services (`lupo_federation_`, `lupo_federated_`)

- **lupo_federated_trust**: Trust relationships between federation nodes.
- **lupo_federation_categories**: Categories shared across federation.
- **lupo_federation_category_map**: Maps categories to federation nodes.
- **lupo_federation_discovery**: Tracks discovered federation nodes.
- **lupo_federation_nodes**: Master table for all federation nodes.

## 9. System & Orchestration (`lupo_system_`, `lupo_orchestrator_`)

- **lupo_system_commands**: Stores system-level commands for orchestration.
- **lupo_system_config**: System configuration key-value store.
- **lupo_system_health_snapshots**: Snapshots of system health and schema.
- **lupo_orchestrator_rules**: Orchestration rules for system actors.
- **lupo_governance_overrides**: Manual overrides for governance and policy.

## 10. Evidence & Truth (`lupo_truth_`)

- **lupo_truth_answers**: Stores answers to truth questions.
- **lupo_truth_context_map**: Maps truth questions to contexts.
- **lupo_truth_evidence**: Stores evidence supporting truth answers.
- **lupo_truth_followers**: Tracks followers of truth questions.
- **lupo_truth_questions**: Master table for all truth questions.

## 11. Other/Utility Tables

- **lupo_action_authorization**: Defines authorization requirements for actions.
- **lupo_aliases**: Stores aliases for slugs and semantic objects.
- **lupo_analytics_campaign_vars**: Analytics campaign variables and metadata.
- **lupo_answers**: Stores answers to questions (non-truth system).
- **lupo_audit_log**: General audit log for entity events.
- **lupo_banned_actors**: Tracks banned actors and reasons.
- **lupo_bans_log**: Logs ban events and scopes.
- **lupo_capability_usage**: Tracks usage statistics for actor capabilities.
- **lupo_crafty_syntax_auto_invite**: Legacy auto-invite logic from Crafty Syntax.
- **lupo_crafty_syntax_chat_mod_departments**: Legacy chat module departments.
- **lupo_crafty_syntax_chat_questions**: Legacy chat questions for Crafty Syntax.
- **lupo_crafty_syntax_layer_invites**: Legacy layer invites from Crafty Syntax.
- **lupo_crafty_syntax_leave_message**: Legacy leave message table.
- **lupo_crafty_user_mapping**: Maps Crafty Syntax users to Lupopedia users.
- **lupo_crm_leads**: CRM lead records.
- **lupo_crm_lead_messages**: Messages associated with CRM leads.
- **lupo_department_actor_pools**: Pools of actors available to departments.
- **lupo_department_metadata**: Metadata for departments.
- **lupo_department_roles**: Roles assigned to actors within departments.
- **lupo_departments**: Master table for all departments.
- **lupo_dialog_channels/messages/threads**: Dialog system for threaded discussions.
- **lupo_doctrine_evolution_audit**: Tracks doctrine evolution steps and audits.
- **lupo_documentation_frameworks**: Documentation framework definitions.
- **lupo_emotional_frameworks**: Emotional frameworks for mood tracking.
- **lupo_escalation_tasks**: Tasks created during escalation events.
- **lupo_event_metadata**: Metadata for events.
- **lupo_folder_map**: Maps folders to objects.
- **lupo_hashtag_map/hashtags**: Hashtag system for tagging and search.
- **lupo_help_topics/tree**: Help topics and their hierarchy.
- **lupo_hotfix_registry**: Registry of applied hotfixes.
- **lupo_human_requests/context/responses**: Human request/response system.
- **lupo_interpretation_log**: Logs interpretation events by agents.
- **lupo_labs_declarations/violations**: Labs declarations and violations.
- **lupo_legacy_content_mapping**: Maps legacy content URLs to semantic URLs.
- **lupo_magic_link_tokens**: Magic link tokens for authentication.
- **lupo_memory_rollups**: Summarized memory events for actors.
- **lupo_metadata**: Arbitrary metadata for entities.
- **lupo_modules**: Registered modules and their configs.
- **lupo_notifications**: System notifications for actors.
- **lupo_password_resets**: Password reset tokens and status.
- **lupo_paths/paths_summary**: Tracks navigation paths and summaries.
- **lupo_permissions**: Permissions for users and departments.
- **lupo_projects**: Project records and metadata.
- **lupo_question_map/questions**: Question system (non-truth).
- **lupo_reference_links/map/objects/references**: Reference and citation system.
- **lupo_referers/referers_daily**: Tracks referer URLs and daily stats.
- **lupo_rolls**: Role assignments for actors in channels.
- **lupo_routing_decisions**: Routing decisions for tasks and threads.
- **lupo_rule_logs/targets/rules**: Rule system for automation and policy.
- **lupo_schema_migrations**: Schema migration history.
- **lupo_search_index/rebuild_log**: Search index and rebuild logs.
- **lupo_sessions**: Session tracking for actors.
- **lupo_smilies**: Emoji and smilie definitions.
- **lupo_tasks**: Task management system.
- **lupo_thread_metadata**: Metadata for dialog threads.
- **lupo_ticket_messages/tickets**: Ticketing system for support.
- **lupo_two_factor_audit**: Two-factor authentication audit log.
- **lupo_unified_log**: Unified log for system events.
- **lupo_uploads**: File uploads and metadata.
- **lupo_visits/visits_daily**: Visit tracking and daily stats.
- **lupo_votes**: Voting system for objects.
- **lupo_world_registry**: Registry of world objects and metadata.

---

**Legend:**
- **⚠️ ATTENTION**: Table is likely obsolete or requires review for current architecture.

This report is current as of March 30, 2026.
