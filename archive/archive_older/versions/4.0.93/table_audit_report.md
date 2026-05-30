# Database Table Audit Report - 4.0.93+

Generated: 2026-03-30 14:13:52

## Summary

- Tables audited: 169
- Doctrine violations: 5
- Missing documentation: 52
- Missing PRDs: 157

## Detailed Results

### lupo_action_authorization

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_actor_actions

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 8
- **Doctrine Compliance**: ✅

### lupo_actor_apps

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 4
- **Doctrine Compliance**: ✅

### lupo_actor_auth_users

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: FOUND
- **Columns**: 12
- **Doctrine Compliance**: ✅

### lupo_actor_availability_status

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_actor_capabilities

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 13
- **Doctrine Compliance**: ✅

### lupo_actor_channel_roles

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 17
- **Doctrine Compliance**: ✅

### lupo_actor_channels

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 16
- **Doctrine Compliance**: ✅

### lupo_actor_collections

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 13
- **Doctrine Compliance**: ✅

### lupo_actor_conflicts

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 16
- **Doctrine Compliance**: ✅

### lupo_actor_departments

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_actor_handshakes

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 12
- **Doctrine Compliance**: ✅

### lupo_actor_history

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 14
- **Doctrine Compliance**: ✅

### lupo_actor_instances

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: FOUND
- **Columns**: 7
- **Doctrine Compliance**: ✅

### lupo_actor_lease_sessions

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: FOUND
- **Columns**: 7
- **Doctrine Compliance**: ✅

### lupo_actor_moods

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 6
- **Doctrine Compliance**: ✅

### lupo_actor_reply_templates

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_actors

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 32
- **Doctrine Violations**:
  - Primary key actor_name is not BIGINT

### lupo_agent_context_snapshots

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 22
- **Doctrine Compliance**: ✅

### lupo_agent_dependencies

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 8
- **Doctrine Compliance**: ✅

### lupo_agent_experiences

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 7
- **Doctrine Violations**:
  - Primary key link_id is not BIGINT

### lupo_agent_external_events

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 6
- **Doctrine Compliance**: ✅

### lupo_agent_faucet_credentials

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 6
- **Doctrine Violations**:
  - Primary key agent_faucet_credential_id is not BIGINT

### lupo_agent_faucets

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 24
- **Doctrine Compliance**: ✅

### lupo_agent_files

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 14
- **Doctrine Compliance**: ✅

### lupo_agent_heartbeats

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 8
- **Doctrine Compliance**: ✅

### lupo_agent_tool_calls

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 26
- **Doctrine Compliance**: ✅

### lupo_agent_versions

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 13
- **Doctrine Compliance**: ✅

### lupo_agents

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 36
- **Doctrine Compliance**: ✅

### lupo_aliases

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 5
- **Doctrine Compliance**: ✅

### lupo_analytics_campaign_vars

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_answers

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 7
- **Doctrine Compliance**: ✅

### lupo_anubis_events

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 7
- **Doctrine Compliance**: ✅

### lupo_anubis_log

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 15
- **Doctrine Compliance**: ✅

### lupo_anubis_operations

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_anubis_processing_log

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 7
- **Doctrine Compliance**: ✅

### lupo_anubis_quarantine

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 13
- **Doctrine Compliance**: ✅

### lupo_anubis_queue

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 18
- **Doctrine Compliance**: ✅

### lupo_anubis_recovery_attempts

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_anubis_redirects

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 7
- **Doctrine Compliance**: ✅

### lupo_api_clients

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_api_rate_limits

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_api_token_logs

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_api_tokens

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 16
- **Doctrine Compliance**: ✅

### lupo_api_webhooks

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 13
- **Doctrine Compliance**: ✅

### lupo_atoms

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_audit_log

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 12
- **Doctrine Compliance**: ✅

### lupo_auth_audit_log

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 12
- **Doctrine Compliance**: ✅

### lupo_auth_providers

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 12
- **Doctrine Compliance**: ✅

### lupo_auth_rate_limits

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 4
- **Doctrine Compliance**: ✅

### lupo_auth_user_departments

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 10
- **Doctrine Compliance**: ✅

### lupo_auth_users

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 19
- **Doctrine Compliance**: ✅

### lupo_banned_actors

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_bans_log

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 8
- **Doctrine Compliance**: ✅

### lupo_capability_usage

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 12
- **Doctrine Compliance**: ✅

### lupo_channel_boot_detail

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_channel_boot_detail_lifecycle

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_channel_boot_lifecycle

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 16
- **Doctrine Compliance**: ✅

### lupo_channel_content

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_channel_departments

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 4
- **Doctrine Compliance**: ✅

### lupo_channel_escalation_rules

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 10
- **Doctrine Compliance**: ✅

### lupo_channel_escalations

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_channel_files

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 14
- **Doctrine Compliance**: ✅

### lupo_channel_state

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 22
- **Doctrine Compliance**: ✅

### lupo_channels

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 32
- **Doctrine Compliance**: ✅

### lupo_collection_links

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 8
- **Doctrine Compliance**: ✅

### lupo_collection_map

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 7
- **Doctrine Compliance**: ✅

### lupo_collection_tab_map

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_collection_tab_paths

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_collection_tabs

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 19
- **Doctrine Compliance**: ✅

### lupo_collections

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 19
- **Doctrine Compliance**: ✅

### lupo_comments

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 14
- **Doctrine Compliance**: ✅

### lupo_contents

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 54
- **Doctrine Compliance**: ✅

### lupo_context_cards

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_context_edges

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_contexts

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: FOUND
- **Columns**: 14
- **Doctrine Compliance**: ✅

### lupo_contexts_map

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_crafty_syntax_auto_invite

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 18
- **Doctrine Compliance**: ✅

### lupo_crafty_syntax_chat_mod_departments

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 6
- **Doctrine Compliance**: ✅

### lupo_crafty_syntax_chat_questions

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 13
- **Doctrine Compliance**: ✅

### lupo_crafty_syntax_layer_invites

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 13
- **Doctrine Compliance**: ✅

### lupo_crafty_syntax_leave_message

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 18
- **Doctrine Compliance**: ✅

### lupo_crafty_user_mapping

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 7
- **Doctrine Compliance**: ✅

### lupo_crm_lead_messages

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_crm_leads

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 14
- **Doctrine Compliance**: ✅

### lupo_department_actor_pools

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 5
- **Doctrine Compliance**: ✅

### lupo_department_metadata

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 8
- **Doctrine Compliance**: ✅

### lupo_department_roles

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 8
- **Doctrine Compliance**: ✅

### lupo_departments

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_dialog_channels

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 18
- **Doctrine Compliance**: ✅

### lupo_dialog_messages

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 20
- **Doctrine Compliance**: ✅

### lupo_dialog_threads

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 28
- **Doctrine Compliance**: ✅

### lupo_doctrine_evolution_audit

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_documentation_frameworks

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 19
- **Doctrine Compliance**: ✅

### lupo_edge_map

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_edge_type_definitions

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 10
- **Doctrine Compliance**: ✅

### lupo_edge_types

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 8
- **Doctrine Compliance**: ✅

### lupo_edges

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: FOUND
- **Columns**: 29
- **Doctrine Compliance**: ✅

### lupo_emotional_frameworks

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 5
- **Doctrine Violations**:
  - Primary key framework_name is not BIGINT

### lupo_escalation_tasks

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_event_metadata

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 5
- **Doctrine Compliance**: ✅

### lupo_federated_trust

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 13
- **Doctrine Compliance**: ✅

### lupo_federation_categories

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_federation_category_map

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 8
- **Doctrine Compliance**: ✅

### lupo_federation_discovery

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 20
- **Doctrine Compliance**: ✅

### lupo_federation_nodes

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 21
- **Doctrine Compliance**: ✅

### lupo_folder_map

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 8
- **Doctrine Compliance**: ✅

### lupo_governance_overrides

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 13
- **Doctrine Compliance**: ✅

### lupo_hashtag_map

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 7
- **Doctrine Compliance**: ✅

### lupo_hashtags

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 8
- **Doctrine Compliance**: ✅

### lupo_help_topics

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 14
- **Doctrine Compliance**: ✅

### lupo_help_tree

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 13
- **Doctrine Compliance**: ✅

### lupo_hotfix_registry

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 6
- **Doctrine Compliance**: ✅

### lupo_human_request_context

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 7
- **Doctrine Compliance**: ✅

### lupo_human_request_responses

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 12
- **Doctrine Compliance**: ✅

### lupo_human_requests

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 24
- **Doctrine Compliance**: ✅

### lupo_interpretation_log

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_labs_declarations

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 13
- **Doctrine Compliance**: ✅

### lupo_labs_violations

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_legacy_content_mapping

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 8
- **Doctrine Compliance**: ✅

### lupo_magic_link_tokens

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 6
- **Doctrine Compliance**: ✅

### lupo_memory_rollups

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 5
- **Doctrine Compliance**: ✅

### lupo_metadata

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 15
- **Doctrine Compliance**: ✅

### lupo_modules

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 27
- **Doctrine Compliance**: ✅

### lupo_notifications

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 12
- **Doctrine Compliance**: ✅

### lupo_orchestrator_rules

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 10
- **Doctrine Compliance**: ✅

### lupo_password_resets

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 7
- **Doctrine Compliance**: ✅

### lupo_paths

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 15
- **Doctrine Compliance**: ✅

### lupo_paths_summary

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 6
- **Doctrine Compliance**: ✅

### lupo_permissions

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 10
- **Doctrine Compliance**: ✅

### lupo_projects

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 21
- **Doctrine Compliance**: ✅

### lupo_question_map

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 6
- **Doctrine Compliance**: ✅

### lupo_questions

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 7
- **Doctrine Compliance**: ✅

### lupo_reference_links

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 8
- **Doctrine Compliance**: ✅

### lupo_reference_map

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 6
- **Doctrine Compliance**: ✅

### lupo_reference_objects

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_references

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 10
- **Doctrine Compliance**: ✅

### lupo_referers

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: FOUND
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_referers_daily

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_rolls

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 8
- **Doctrine Compliance**: ✅

### lupo_routing_decisions

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 14
- **Doctrine Compliance**: ✅

### lupo_rule_logs

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_rule_targets

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 10
- **Doctrine Compliance**: ✅

### lupo_rules

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 10
- **Doctrine Compliance**: ✅

### lupo_schema_migrations

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 4
- **Doctrine Compliance**: ✅

### lupo_search_index

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 13
- **Doctrine Compliance**: ✅

### lupo_search_rebuild_log

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_semantic_index

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 30
- **Doctrine Compliance**: ✅

### lupo_sessions

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 22
- **Doctrine Violations**:
  - Primary key session_id is not BIGINT

### lupo_smilies

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 9
- **Doctrine Compliance**: ✅

### lupo_system_commands

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 22
- **Doctrine Compliance**: ✅

### lupo_system_config

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 6
- **Doctrine Compliance**: ✅

### lupo_system_health_snapshots

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 10
- **Doctrine Compliance**: ✅

### lupo_tasks

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 25
- **Doctrine Compliance**: ✅

### lupo_thread_metadata

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 10
- **Doctrine Compliance**: ✅

### lupo_ticket_messages

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 7
- **Doctrine Compliance**: ✅

### lupo_tickets

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_truth_answers

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: FOUND
- **Columns**: 21
- **Doctrine Compliance**: ✅

### lupo_truth_context_map

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: FOUND
- **Columns**: 12
- **Doctrine Compliance**: ✅

### lupo_truth_evidence

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: FOUND
- **Columns**: 21
- **Doctrine Compliance**: ✅

### lupo_truth_followers

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: FOUND
- **Columns**: 8
- **Doctrine Compliance**: ✅

### lupo_truth_questions

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: FOUND
- **Columns**: 25
- **Doctrine Compliance**: ✅

### lupo_two_factor_audit

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 6
- **Doctrine Compliance**: ✅

### lupo_unified_log

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_uploads

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 14
- **Doctrine Compliance**: ✅

### lupo_visits

- **Status**: OK
- **Documentation**: FOUND
- **PRDs**: MISSING
- **Columns**: 20
- **Doctrine Compliance**: ✅

### lupo_visits_daily

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 11
- **Doctrine Compliance**: ✅

### lupo_votes

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: FOUND
- **Columns**: 10
- **Doctrine Compliance**: ✅

### lupo_world_registry

- **Status**: OK
- **Documentation**: MISSING
- **PRDs**: MISSING
- **Columns**: 8
- **Doctrine Compliance**: ✅

