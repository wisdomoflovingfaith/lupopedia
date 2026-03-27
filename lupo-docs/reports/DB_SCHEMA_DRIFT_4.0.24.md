# Database Schema Drift Report 4.0.24

Generated: 2026-03-27T13:34:35.586774Z
Database: lupopedia
TOON Directory: lupo-database/lupopedia/json

## Missing Tables (DB vs TOONs)

No missing tables found.

## Extra Tables (DB only)

No extra tables found.

## Schema Mismatches (per table)

### lupo_action_authorization

**Type Mismatches:**
- `action_authorization_id`: TOON=bigint NOT NULL, DB=bigint
- `action_key`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `description`: TOON=text NOT NULL, DB=text
- `requires_all_conditions`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `created_by_actor_id`: TOON=bigint NOT NULL, DB=bigint

### lupo_actors

**Type Mismatches:**
- `actor_name`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `actor_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `slug`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `adversarial_role`: TOON=varchar(64) DEFAULT 'none', DB=varchar(64)
- `primary_federation_node_id`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `is_kernel`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `can_login`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `paired_actor_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_agent`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `actor_root_path`: TOON=varchar(512) DEFAULT 'actors/{actor_id}', DB=varchar(512)
- `who_json_sync_status`: TOON=varchar(64) DEFAULT 'pending', DB=varchar(64)
- `last_sync_ymdhis`: TOON=bigint DEFAULT 0, DB=bigint
- `actor_tier`: TOON=tinyint DEFAULT 3, DB=tinyint

### lupo_actor_actions

**Type Mismatches:**
- `actor_action_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `action_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_actor_apps

**Type Mismatches:**
- `actor_app_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `apps_path`: TOON=varchar(512) NOT NULL DEFAULT '', DB=varchar(512)
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_actor_auth_users

**Type Mismatches:**
- `actor_auth_user_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `auth_user_id`: TOON=bigint NOT NULL, DB=bigint
- `relationship_role`: TOON=varchar(64) NOT NULL DEFAULT 'supporting_human', DB=varchar(64)
- `is_primary`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `routing_priority`: TOON=smallint NOT NULL DEFAULT 100, DB=smallint
- `status`: TOON=varchar(32) NOT NULL DEFAULT 'active', DB=varchar(32)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `deleted_ymdhis`: TOON=bigint DEFAULT 0, DB=bigint

### lupo_actor_capabilities

**Type Mismatches:**
- `actor_capability_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `domain_id`: TOON=bigint NOT NULL, DB=bigint
- `capability_key`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `scope_limitation`: TOON=varchar(50) DEFAULT 'unrestricted', DB=varchar(50)
- `max_calls_per_hour`: TOON=int DEFAULT 0, DB=int
- `requires_approval`: TOON=tinyint DEFAULT 0, DB=tinyint

### lupo_actor_channels

**Type Mismatches:**
- `actor_channel_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `created_by_actor_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `status`: TOON=char(1) NOT NULL DEFAULT 'A', DB=char(1)
- `channel_color`: TOON=varchar(6) NOT NULL DEFAULT 'F7FAFF', DB=varchar(6)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_actor_channel_roles

**Type Mismatches:**
- `actor_channel_role_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `role_key`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `protocol_completion_status`: TOON=varchar(64) DEFAULT 'pending', DB=varchar(64)
- `protocol_version`: TOON=varchar(20) DEFAULT '3.0.0', DB=varchar(20)
- `join_sequence_step`: TOON=tinyint DEFAULT 0, DB=tinyint

### lupo_actor_collections

**Type Mismatches:**
- `actor_collection_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `collection_id`: TOON=bigint NOT NULL, DB=bigint
- `access_level`: TOON=varchar(64) NOT NULL DEFAULT 'read', DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `trust_level`: TOON=varchar(64) DEFAULT 'standard', DB=varchar(64)
- `doctrine_alignment_version`: TOON=varchar(20) DEFAULT '3.0.0', DB=varchar(20)

### lupo_actor_conflicts

**Type Mismatches:**
- `actor_conflict_id`: TOON=bigint NOT NULL, DB=bigint
- `domain_id`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `actor_a_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_b_id`: TOON=bigint NOT NULL, DB=bigint
- `conflict_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `conflict_summary`: TOON=text NOT NULL, DB=text
- `resolution_status`: TOON=varchar(64) NOT NULL DEFAULT 'unresolved', DB=varchar(64)
- `severity`: TOON=varchar(64) NOT NULL DEFAULT 'medium', DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_actor_departments

**Type Mismatches:**
- `actor_department_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `department_id`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_actor_handshakes

**Type Mismatches:**
- `actor_handshake_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_type`: TOON=varchar(32) NOT NULL, DB=varchar(32)
- `utc_timestamp`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_actor_history

**Type Mismatches:**
- `history_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `title`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `date_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_actor_moods

**Type Mismatches:**
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `mood_r`: TOON=tinyint NOT NULL, DB=tinyint
- `mood_g`: TOON=tinyint NOT NULL, DB=tinyint
- `mood_b`: TOON=tinyint NOT NULL, DB=tinyint
- `mood_framework`: TOON=varchar(32) NOT NULL DEFAULT 'western_analytical', DB=varchar(32)
- `timestamp_utc`: TOON=bigint NOT NULL, DB=bigint

### lupo_actor_reply_templates

**Type Mismatches:**
- `actor_reply_template_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `template_key`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `template_text`: TOON=text NOT NULL, DB=text
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_actor_traits

**Type Mismatches:**
- `actor_trait_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `trait_key`: TOON=varchar(128) NOT NULL, DB=varchar(128)
- `federation_node_id`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_agents

**Type Mismatches:**
- `agent_id`: TOON=bigint NOT NULL, DB=bigint
- `agent_key`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `agent_name`: TOON=varchar(150) NOT NULL, DB=varchar(150)
- `version`: TOON=varchar(50) DEFAULT '1.0', DB=varchar(50)
- `is_global_authority`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `is_internal_only`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `avg_response_time_ms`: TOON=int DEFAULT 0, DB=int
- `total_tokens_processed`: TOON=bigint DEFAULT 0, DB=bigint
- `success_rate`: TOON=float DEFAULT 1, DB=float
- `cost_per_1k_tokens`: TOON=decimal(10,4) DEFAULT 0.0000, DB=decimal(10,4)
- `temperature`: TOON=float DEFAULT 0.7, DB=float
- `top_p`: TOON=float DEFAULT 1, DB=float
- `max_tokens`: TOON=int DEFAULT 2048, DB=int
- `presence_penalty`: TOON=float DEFAULT 0, DB=float
- `frequency_penalty`: TOON=float DEFAULT 0, DB=float
- `provider`: TOON=varchar(50) DEFAULT 'openai', DB=varchar(50)
- `timeout_ms`: TOON=int DEFAULT 20000, DB=int
- `pono_score`: TOON=decimal(3,2) DEFAULT 1.00, DB=decimal(3,2)
- `pilau_score`: TOON=decimal(3,2) DEFAULT 0.00, DB=decimal(3,2)
- `kapakai_score`: TOON=decimal(3,2) DEFAULT 0.50, DB=decimal(3,2)
- `kapu_active`: TOON=tinyint DEFAULT 0, DB=tinyint
- `kapu_consent_given`: TOON=tinyint DEFAULT 0, DB=tinyint
- `kapu_appeal_pending`: TOON=tinyint DEFAULT 0, DB=tinyint

### lupo_agent_context_snapshots

**Type Mismatches:**
- `agent_context_snapshot_id`: TOON=bigint NOT NULL, DB=bigint
- `session_id`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `snapshot_type`: TOON=varchar(64) NOT NULL DEFAULT 'full', DB=varchar(64)
- `context_data`: TOON=text NOT NULL, DB=text
- `compression_method`: TOON=varchar(64) DEFAULT 'gzip', DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_corrupt`: TOON=tinyint DEFAULT 0, DB=tinyint
- `retention_policy`: TOON=varchar(64) DEFAULT 'temporary', DB=varchar(64)

### lupo_agent_dependencies

**Type Mismatches:**
- `agent_dependency_id`: TOON=bigint NOT NULL, DB=bigint
- `agent_id`: TOON=bigint NOT NULL, DB=bigint
- `depends_on_agent_id`: TOON=bigint NOT NULL, DB=bigint
- `depends_on_agent_code`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `is_required`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_agent_experiences

**Type Mismatches:**
- `link_id`: TOON=char(26) NOT NULL, DB=char(26)
- `agent_id`: TOON=bigint NOT NULL, DB=bigint
- `star_id`: TOON=char(26) NOT NULL, DB=char(26)

### lupo_agent_external_events

**Type Mismatches:**
- `external_event_id`: TOON=bigint NOT NULL, DB=bigint
- `agent_name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `source_system`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `event_type`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_agent_faucets

**Type Mismatches:**
- `agent_faucet_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `name`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `slug`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `is_default`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `domain_id`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_agent_faucet_credentials

**Type Mismatches:**
- `agent_faucet_credential_id`: TOON=int NOT NULL, DB=int
- `faucet_id`: TOON=bigint NOT NULL, DB=bigint
- `provider`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `api_key`: TOON=varbinary(512) NOT NULL, DB=varbinary(512)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_agent_files

**Type Mismatches:**
- `file_id`: TOON=bigint NOT NULL, DB=bigint
- `agent_id`: TOON=bigint NOT NULL, DB=bigint
- `file_type`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `file_name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `file_path`: TOON=varchar(500) NOT NULL, DB=varchar(500)
- `file_hash`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `file_size`: TOON=bigint NOT NULL, DB=bigint
- `upload_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_agent_heartbeats

**Type Mismatches:**
- `heartbeat_id`: TOON=bigint NOT NULL, DB=bigint
- `agent_slug`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `status`: TOON=varchar(32) NOT NULL DEFAULT 'unknown', DB=varchar(32)
- `last_heartbeat_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_agent_tool_calls

**Type Mismatches:**
- `agent_tool_call_id`: TOON=bigint NOT NULL, DB=bigint
- `agent_id`: TOON=bigint NOT NULL, DB=bigint
- `domain_id`: TOON=bigint NOT NULL, DB=bigint
- `tool_name`: TOON=varchar(150) NOT NULL, DB=varchar(150)
- `tokens_prompt`: TOON=int DEFAULT 0, DB=int
- `tokens_completion`: TOON=int DEFAULT 0, DB=int
- `tokens_total`: TOON=int DEFAULT 0, DB=int
- `cost_usd`: TOON=decimal(10,6) DEFAULT 0.000000, DB=decimal(10,6)
- `latency_ms`: TOON=int DEFAULT 0, DB=int
- `status`: TOON=varchar(50) DEFAULT 'success', DB=varchar(50)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `archived_ymdhis`: TOON=bigint DEFAULT 0, DB=bigint

### lupo_agent_versions

**Type Mismatches:**
- `agent_version_id`: TOON=bigint NOT NULL, DB=bigint
- `agent_id`: TOON=bigint NOT NULL, DB=bigint
- `version_label`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `semver_major`: TOON=int DEFAULT 0, DB=int
- `semver_minor`: TOON=int DEFAULT 0, DB=int
- `semver_patch`: TOON=int DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=smallint NOT NULL DEFAULT 0, DB=smallint

### lupo_aliases

**Type Mismatches:**
- `alias_id`: TOON=bigint NOT NULL, DB=bigint
- `slug`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `alias`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `alias_type`: TOON=varchar(50) DEFAULT 'semantic', DB=varchar(50)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_analytics_campaign_vars

**Type Mismatches:**
- `campaign_var_id`: TOON=bigint NOT NULL, DB=bigint
- `period`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `campaign_key`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_answers

**Type Mismatches:**
- `answer_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `question_id`: TOON=bigint NOT NULL, DB=bigint
- `answer_text`: TOON=text NOT NULL, DB=text
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_anubis_events

**Type Mismatches:**
- `anubis_event_id`: TOON=bigint NOT NULL, DB=bigint
- `event_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `table_name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `row_id`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `agent`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `details_json`: TOON=text NOT NULL, DB=text

### lupo_anubis_log

**Type Mismatches:**
- `anubis_log_id`: TOON=bigint NOT NULL, DB=bigint
- `event_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `severity`: TOON=varchar(20) NOT NULL DEFAULT 'normal', DB=varchar(20)
- `status`: TOON=varchar(64) NOT NULL DEFAULT 'Pending', DB=varchar(64)
- `assigned_to_actor_id`: TOON=bigint NOT NULL DEFAULT 19, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_anubis_operations

**Type Mismatches:**
- `operation_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `operation_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `target_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `target_id`: TOON=bigint NOT NULL, DB=bigint
- `channel_id`: TOON=bigint NOT NULL DEFAULT 42, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_anubis_processing_log

**Type Mismatches:**
- `log_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `queue_id`: TOON=bigint NOT NULL, DB=bigint
- `file_path`: TOON=varchar(512) NOT NULL, DB=varchar(512)
- `action`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `created_utc`: TOON=bigint NOT NULL, DB=bigint

### lupo_anubis_quarantine

**Type Mismatches:**
- `quarantine_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `queue_id`: TOON=bigint NOT NULL, DB=bigint
- `file_path`: TOON=varchar(512) NOT NULL, DB=varchar(512)
- `quarantine_path`: TOON=varchar(512) NOT NULL, DB=varchar(512)
- `reason`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `quarantined_utc`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint DEFAULT 0, DB=tinyint

### lupo_anubis_queue

**Type Mismatches:**
- `queue_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `file_path`: TOON=varchar(512) NOT NULL, DB=varchar(512)
- `detected_utc`: TOON=bigint NOT NULL, DB=bigint
- `priority`: TOON=tinyint DEFAULT 5, DB=tinyint
- `status`: TOON=varchar(32) DEFAULT 'pending', DB=varchar(32)
- `attempts`: TOON=tinyint DEFAULT 0, DB=tinyint
- `filesystem_copy_exists`: TOON=tinyint DEFAULT 1, DB=tinyint
- `created_utc`: TOON=bigint NOT NULL, DB=bigint
- `updated_utc`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint DEFAULT 0, DB=tinyint

### lupo_anubis_recovery_attempts

**Type Mismatches:**
- `attempt_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `queue_id`: TOON=bigint NOT NULL, DB=bigint
- `attempt_number`: TOON=tinyint NOT NULL, DB=tinyint
- `attempt_utc`: TOON=bigint NOT NULL, DB=bigint
- `success`: TOON=tinyint DEFAULT 0, DB=tinyint

### lupo_anubis_redirects

**Type Mismatches:**
- `anubis_redirect_id`: TOON=bigint NOT NULL, DB=bigint
- `table_name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `old_id`: TOON=bigint NOT NULL, DB=bigint
- `new_id`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `agent`: TOON=varchar(255) NOT NULL, DB=varchar(255)

### lupo_api_clients

**Type Mismatches:**
- `api_client_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `client_key`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `client_secret`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `client_name`: TOON=varchar(150) NOT NULL, DB=varchar(150)
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_api_rate_limits

**Type Mismatches:**
- `api_rate_limit_id`: TOON=bigint NOT NULL, DB=bigint
- `domain_id`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `api_token_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `actor_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `window_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `request_count`: TOON=int NOT NULL DEFAULT 0, DB=int
- `limit_value`: TOON=int NOT NULL DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_api_tokens

**Type Mismatches:**
- `api_token_id`: TOON=bigint NOT NULL, DB=bigint
- `domain_id`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `actor_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `token_key`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_api_token_logs

**Type Mismatches:**
- `api_token_log_id`: TOON=bigint NOT NULL, DB=bigint
- `domain_id`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `api_token_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `endpoint`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `http_method`: TOON=varchar(10) NOT NULL, DB=varchar(10)
- `status_code`: TOON=int NOT NULL, DB=int
- `request_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_api_webhooks

**Type Mismatches:**
- `api_webhook_id`: TOON=bigint NOT NULL, DB=bigint
- `domain_id`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `actor_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `module_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `endpoint_url`: TOON=varchar(500) NOT NULL, DB=varchar(500)
- `secret_key`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `event_types`: TOON=text NOT NULL, DB=text
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `max_retries`: TOON=int NOT NULL DEFAULT 5, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_atoms

**Type Mismatches:**
- `atom_id`: TOON=bigint NOT NULL, DB=bigint
- `atom_name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `context_id`: TOON=bigint NOT NULL, DB=bigint
- `is_authoritative`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `created_ymd`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymd`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_audit_log

**Type Mismatches:**
- `audit_log_id`: TOON=bigint NOT NULL, DB=bigint
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `entity_type`: TOON=varchar(32) NOT NULL, DB=varchar(32)
- `entity_id`: TOON=bigint NOT NULL, DB=bigint
- `event_type`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_auth_audit_log

**Type Mismatches:**
- `auth_audit_log_id`: TOON=bigint NOT NULL, DB=bigint
- `event_type`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `system_context`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `success`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint

### lupo_auth_providers

**Type Mismatches:**
- `auth_provider_id`: TOON=bigint NOT NULL, DB=bigint
- `provider_name`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `client_id`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `client_secret`: TOON=text NOT NULL, DB=text
- `authorization_endpoint`: TOON=varchar(2000) NOT NULL, DB=varchar(2000)
- `token_endpoint`: TOON=varchar(2000) NOT NULL, DB=varchar(2000)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint

### lupo_auth_users

**Type Mismatches:**
- `auth_user_id`: TOON=bigint NOT NULL, DB=bigint
- `username`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `display_name`: TOON=varchar(42) NOT NULL, DB=varchar(42)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_banned_actors

**Type Mismatches:**
- `banned_actor_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `reason`: TOON=varchar(500) NOT NULL, DB=varchar(500)
- `banned_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_bans_log

**Type Mismatches:**
- `bans_log_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `uri`: TOON=varchar(1024) NOT NULL DEFAULT '', DB=varchar(1024)
- `resolved_uri`: TOON=varchar(1024) NOT NULL DEFAULT '', DB=varchar(1024)
- `ban_scope`: TOON=varchar(64) NOT NULL DEFAULT 'router', DB=varchar(64)
- `banned_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_capability_usage

**Type Mismatches:**
- `usage_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `capability`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `usage_count`: TOON=bigint DEFAULT 0, DB=bigint
- `success_rate`: TOON=float DEFAULT 1, DB=float
- `avg_response_time_ms`: TOON=int DEFAULT 0, DB=int
- `last_used_ymdhis`: TOON=bigint DEFAULT 0, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_channels

**Type Mismatches:**
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `federation_node_id`: TOON=bigint NOT NULL, DB=bigint
- `created_by_actor_id`: TOON=bigint NOT NULL, DB=bigint
- `default_actor_id`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `department_id`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `channel_key`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `channel_slug`: TOON=varchar(32) NOT NULL DEFAULT 'channel_key', DB=varchar(32)
- `channel_type`: TOON=varchar(32) NOT NULL DEFAULT 'chat_room', DB=varchar(32)
- `language`: TOON=varchar(16) NOT NULL DEFAULT 'en', DB=varchar(16)
- `channel_name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `status_flag`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `awareness_version`: TOON=varchar(20) DEFAULT '3.0.0', DB=varchar(20)
- `is_kernel`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `visibility_status`: TOON=varchar(32) NOT NULL DEFAULT 'active', DB=varchar(32)
- `owner_actor_id`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `access_level`: TOON=varchar(32) NOT NULL DEFAULT 'public', DB=varchar(32)
- `last_activity_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_channel_boot_detail

**Type Mismatches:**
- `detail_id`: TOON=bigint NOT NULL, DB=bigint
- `boot_id`: TOON=bigint NOT NULL, DB=bigint
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `load_status`: TOON=varchar(64) NOT NULL DEFAULT 'started', DB=varchar(64)
- `content_items_loaded`: TOON=int NOT NULL DEFAULT 0, DB=int
- `total_content_items`: TOON=int NOT NULL DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_channel_boot_detail_lifecycle

**Type Mismatches:**
- `detail_lifecycle_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `lifecycle_id`: TOON=bigint NOT NULL, DB=bigint
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `detail_start_time`: TOON=bigint NOT NULL, DB=bigint
- `detail_status`: TOON=varchar(64) NOT NULL DEFAULT 'started', DB=varchar(64)
- `content_items_loaded`: TOON=int NOT NULL DEFAULT 0, DB=int
- `total_content_items`: TOON=int NOT NULL DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_channel_boot_lifecycle

**Type Mismatches:**
- `lifecycle_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `session_id`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `lifecycle_start_time`: TOON=bigint NOT NULL, DB=bigint
- `lifecycle_status`: TOON=varchar(64) NOT NULL DEFAULT 'started', DB=varchar(64)
- `lifecycle_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `total_channels`: TOON=int NOT NULL DEFAULT 0, DB=int
- `channels_processed`: TOON=int NOT NULL DEFAULT 0, DB=int
- `channels_successful`: TOON=int NOT NULL DEFAULT 0, DB=int
- `channels_failed`: TOON=int NOT NULL DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_channel_content

**Type Mismatches:**
- `channel_content_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `federation_node_id`: TOON=bigint NOT NULL, DB=bigint
- `file_path`: TOON=varchar(500) NOT NULL, DB=varchar(500)
- `web_path`: TOON=varchar(500) NOT NULL, DB=varchar(500)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_channel_departments

**Type Mismatches:**
- `channel_department_id`: TOON=bigint NOT NULL, DB=bigint
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `department_id`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_channel_escalations

**Type Mismatches:**
- `escalation_id`: TOON=bigint NOT NULL, DB=bigint
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_channel_escalation_rules

**Type Mismatches:**
- `rule_id`: TOON=bigint NOT NULL, DB=bigint
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `rule_name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `rule_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_channel_files

**Type Mismatches:**
- `file_id`: TOON=bigint NOT NULL, DB=bigint
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `file_type`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `file_name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `file_path`: TOON=varchar(500) NOT NULL, DB=varchar(500)
- `file_hash`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `file_size`: TOON=bigint NOT NULL, DB=bigint
- `upload_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_channel_state

**Type Mismatches:**
- `channel_state_id`: TOON=bigint NOT NULL, DB=bigint
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `mood_framework`: TOON=varchar(32) NOT NULL DEFAULT 'western_analytical', DB=varchar(32)
- `semantic_weight`: TOON=float DEFAULT 0, DB=float
- `trend_score`: TOON=float DEFAULT 0, DB=float
- `archive_flag`: TOON=tinyint DEFAULT 0, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_collections

**Type Mismatches:**
- `collection_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `federation_node_id`: TOON=bigint NOT NULL, DB=bigint
- `name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `slug`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `color`: TOON=char(6) DEFAULT '666666', DB=char(6)
- `sort_order`: TOON=int DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `is_nav_menu`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_collection_links

**Type Mismatches:**
- `collection_link_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `collection_id`: TOON=bigint NOT NULL, DB=bigint
- `link_url`: TOON=varchar(2000) NOT NULL, DB=varchar(2000)
- `sort_order`: TOON=int NOT NULL DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_collection_map

**Type Mismatches:**
- `collection_map_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `collection_id`: TOON=bigint NOT NULL, DB=bigint
- `object_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `object_id`: TOON=bigint NOT NULL, DB=bigint
- `sort_order`: TOON=int NOT NULL DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_collection_tabs

**Type Mismatches:**
- `collection_tab_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `collection_id`: TOON=bigint NOT NULL, DB=bigint
- `federations_node_id`: TOON=bigint NOT NULL, DB=bigint
- `sort_order`: TOON=int DEFAULT 0, DB=int
- `name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `slug`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `color`: TOON=char(6) DEFAULT '4caf50', DB=char(6)
- `is_hidden`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_collection_tab_map

**Type Mismatches:**
- `collection_tab_map_id`: TOON=bigint NOT NULL, DB=bigint
- `collection_tab_id`: TOON=bigint NOT NULL, DB=bigint
- `federations_node_id`: TOON=bigint NOT NULL, DB=bigint
- `item_type`: TOON=varchar(20) NOT NULL, DB=varchar(20)
- `item_id`: TOON=bigint NOT NULL, DB=bigint
- `sort_order`: TOON=int DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_collection_tab_paths

**Type Mismatches:**
- `collection_tab_path_id`: TOON=bigint NOT NULL, DB=bigint
- `collection_id`: TOON=bigint NOT NULL, DB=bigint
- `collection_tab_id`: TOON=bigint NOT NULL, DB=bigint
- `path`: TOON=varchar(500) NOT NULL, DB=varchar(500)
- `depth`: TOON=int NOT NULL, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_comments

**Type Mismatches:**
- `comment_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `target_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `target_id`: TOON=bigint NOT NULL, DB=bigint
- `channel_id`: TOON=bigint NOT NULL DEFAULT 42, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `comment_text`: TOON=text NOT NULL, DB=text
- `comment_type`: TOON=varchar(64) NOT NULL DEFAULT 'comment', DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_contents

**Type Mismatches:**
- `content_id`: TOON=bigint NOT NULL, DB=bigint
- `federation_node_id`: TOON=bigint DEFAULT 1, DB=bigint
- `federation_source_url`: TOON=varchar(2000) COMMENT 'Canonical URL of content at source federation node', DB=varchar(2000)
- `channel_id`: TOON=bigint COMMENT 'Channel this content belongs to (doctrine: content placement)', DB=bigint
- `title`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `slug`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `content_type`: TOON=varchar(50) DEFAULT 'article', DB=varchar(50)
- `format`: TOON=varchar(20) DEFAULT 'markdown', DB=varchar(20)
- `is_template`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `status`: TOON=varchar(64) DEFAULT 'draft', DB=varchar(64)
- `visibility`: TOON=varchar(64) DEFAULT 'public', DB=varchar(64)
- `view_count`: TOON=int DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `utc_cycle`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `triage_status`: TOON=varchar(64) NOT NULL DEFAULT 'untriaged', DB=varchar(64)
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `version_number`: TOON=int NOT NULL DEFAULT 1, DB=int
- `file_path_from_root`: TOON=varchar(500) COMMENT 'FLIP Header: path from repo root (4.0.86)', DB=varchar(500)
- `file_last_modified_system_version`: TOON=varchar(20) COMMENT 'FLIP: system version at last file edit', DB=varchar(20)
- `file_last_modified_utc`: TOON=bigint COMMENT 'FLIP: UTC last modified YYYYMMDDHHIISS', DB=bigint
- `atom_mappings`: TOON=json COMMENT 'Consolidated from lupo_content_atom_map', DB=json
- `category_mappings`: TOON=json COMMENT 'Consolidated from lupo_content_category_map', DB=json
- `content_events`: TOON=json COMMENT 'Consolidated from lupo_content_events', DB=json
- `hashtags`: TOON=json COMMENT 'Consolidated from lupo_content_hashtag', DB=json
- `inbound_links`: TOON=json COMMENT 'Consolidated from lupo_content_inbound_links', DB=json
- `like_users`: TOON=json COMMENT 'Consolidated from lupo_content_likes', DB=json
- `media_attachments`: TOON=json COMMENT 'Consolidated from lupo_content_media', DB=json
- `question_mappings`: TOON=json COMMENT 'Consolidated from lupo_content_question_map', DB=json
- `content_references`: TOON=json COMMENT 'Consolidated from lupo_content_references', DB=json
- `revision_history`: TOON=json COMMENT 'Consolidated from lupo_content_revisions', DB=json
- `share_users`: TOON=json COMMENT 'Consolidated from lupo_content_shares', DB=json
- `tag_relationships`: TOON=json COMMENT 'Consolidated from lupo_content_tag_relationships', DB=json
- `like_count`: TOON=bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache', DB=bigint
- `share_count`: TOON=bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache', DB=bigint
- `comment_count`: TOON=bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache', DB=bigint

### lupo_contexts

**Type Mismatches:**
- `context_id`: TOON=int NOT NULL, DB=int
- `context_code`: TOON=varchar(16) NOT NULL, DB=varchar(16)
- `context_name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `is_system`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `is_fiction`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `is_installation_local`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `sort_order`: TOON=int NOT NULL DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `weight_score`: TOON=decimal(5,2) NOT NULL DEFAULT 0.00, DB=decimal(5,2)
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint

### lupo_contexts_map

**Type Mismatches:**
- `contexts_map_id`: TOON=bigint NOT NULL, DB=bigint
- `context_id`: TOON=bigint NOT NULL, DB=bigint
- `item_type`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `item_slug`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `deleted_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_context_edges

**Type Mismatches:**
- `edge_id`: TOON=bigint NOT NULL, DB=bigint
- `source_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `source_id`: TOON=bigint NOT NULL, DB=bigint
- `target_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `target_id`: TOON=bigint NOT NULL, DB=bigint
- `edge_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint DEFAULT 0, DB=tinyint
- `deleted_ymdhis`: TOON=bigint DEFAULT 0, DB=bigint

### lupo_crafty_syntax_auto_invite

**Type Mismatches:**
- `crafty_syntax_auto_invite_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `is_offline`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `is_active`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `department_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `visits`: TOON=int NOT NULL DEFAULT 0, DB=int
- `trigger_seconds`: TOON=int NOT NULL DEFAULT 0, DB=int
- `operator_user_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `show_socialpane`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `exclude_mobile`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `only_mobile`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 20250101000000, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 20250101000000, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_crafty_syntax_chat_mod_departments

**Type Mismatches:**
- `crafty_syntax_chat_mod_department_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `department_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `module_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `sort_order`: TOON=int NOT NULL DEFAULT 0, DB=int
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `is_default`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_crafty_syntax_chat_questions

**Type Mismatches:**
- `crafty_syntax_chat_question_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `department_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `sort_order`: TOON=int NOT NULL DEFAULT 0, DB=int
- `is_required`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_crafty_syntax_layer_invites

**Type Mismatches:**
- `crafty_syntax_layer_invite_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `layer_name`: TOON=varchar(100) NOT NULL DEFAULT '', DB=varchar(100)
- `image_name`: TOON=varchar(255) NOT NULL DEFAULT '', DB=varchar(255)
- `department_name`: TOON=varchar(100) NOT NULL DEFAULT '', DB=varchar(100)
- `user_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `display_count`: TOON=int NOT NULL DEFAULT 0, DB=int
- `click_count`: TOON=int NOT NULL DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_crafty_syntax_leave_message

**Type Mismatches:**
- `crafty_syntax_leave_message_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `department_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `email`: TOON=varchar(255) NOT NULL DEFAULT '', DB=varchar(255)
- `subject`: TOON=varchar(255) NOT NULL DEFAULT '', DB=varchar(255)
- `priority`: TOON=tinyint NOT NULL DEFAULT 2, DB=tinyint
- `status`: TOON=enum('new','in_progress','resolved','spam') NOT NULL DEFAULT 'new', DB=enum('new','in_progress','resolved','spam')
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_crafty_user_mapping

**Type Mismatches:**
- `crafty_user_mapping_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `mapping_type`: TOON=varchar(50) NOT NULL DEFAULT 'manual', DB=varchar(50)
- `created_at`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_at`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_crm_leads

**Type Mismatches:**
- `crm_lead_id`: TOON=bigint NOT NULL, DB=bigint
- `status`: TOON=varchar(50) NOT NULL DEFAULT 'new', DB=varchar(50)
- `lead_score`: TOON=int NOT NULL DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_crm_lead_messages

**Type Mismatches:**
- `crm_lead_message_id`: TOON=bigint NOT NULL, DB=bigint
- `body_text`: TOON=text NOT NULL, DB=text
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=smallint NOT NULL DEFAULT 0, DB=smallint

### lupo_departments

**Type Mismatches:**
- `department_id`: TOON=bigint NOT NULL, DB=bigint
- `federation_node_id`: TOON=bigint NOT NULL, DB=bigint
- `name`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `department_type`: TOON=varchar(32) NOT NULL DEFAULT 'general', DB=varchar(32)
- `default_actor_id`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_department_metadata

**Type Mismatches:**
- `department_metadata_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `department_id`: TOON=bigint NOT NULL, DB=bigint
- `metadata_json`: TOON=json NOT NULL, DB=json
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_department_roles

**Type Mismatches:**
- `department_role_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `department_id`: TOON=bigint NOT NULL, DB=bigint
- `role_key`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_dialog_channels

**Type Mismatches:**
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `channel_name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `file_source`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `status`: TOON=varchar(64) DEFAULT 'published', DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `message_count`: TOON=int DEFAULT 0, DB=int

### lupo_dialog_messages

**Type Mismatches:**
- `dialog_message_id`: TOON=bigint NOT NULL, DB=bigint
- `message_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `read_by_actor_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `read_by_actor_utc`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `message_text`: TOON=varchar(1000) NOT NULL, DB=varchar(1000)
- `message_type`: TOON=varchar(64) NOT NULL DEFAULT 'text', DB=varchar(64)
- `mood_framework`: TOON=varchar(32) NOT NULL DEFAULT 'western_analytical', DB=varchar(32)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_dialog_threads

**Type Mismatches:**
- `dialog_thread_id`: TOON=bigint NOT NULL, DB=bigint
- `title`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `federation_node_id`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `created_by_actor_id`: TOON=bigint NOT NULL, DB=bigint
- `bg_color`: TOON=char(6) NOT NULL DEFAULT 'FFFFFF', DB=char(6)
- `text_color`: TOON=char(6) NOT NULL DEFAULT '000000', DB=char(6)
- `alt_text_color`: TOON=char(6) NOT NULL DEFAULT '666666', DB=char(6)
- `status`: TOON=varchar(64) NOT NULL DEFAULT 'Open', DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `visibility_status`: TOON=varchar(32) NOT NULL DEFAULT 'active', DB=varchar(32)
- `owner_actor_id`: TOON=bigint NOT NULL, DB=bigint
- `thread_type`: TOON=varchar(32) NOT NULL DEFAULT 'discussion', DB=varchar(32)
- `thread_priority`: TOON=varchar(32) NOT NULL DEFAULT 'normal', DB=varchar(32)

### lupo_doctrine_evolution_audit

**Type Mismatches:**
- `doctrine_evolution_audit_id`: TOON=bigint NOT NULL, DB=bigint
- `refinement_id`: TOON=bigint NOT NULL, DB=bigint
- `evolution_step`: TOON=tinyint NOT NULL, DB=tinyint
- `step_description`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `step_status`: TOON=varchar(64) DEFAULT 'pending', DB=varchar(64)
- `audit_version`: TOON=varchar(20) DEFAULT '3.0.0', DB=varchar(20)

### lupo_documentation_frameworks

**Type Mismatches:**
- `documentation_framework_id`: TOON=bigint NOT NULL, DB=bigint
- `framework_key`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `framework_name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `class_type`: TOON=varchar(64) NOT NULL DEFAULT 'documentation', DB=varchar(64)
- `namespace_key`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `channel_id`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `collection_key`: TOON=varchar(64) NOT NULL DEFAULT 'active', DB=varchar(64)
- `runtime_min_php`: TOON=varchar(20) DEFAULT '5.6', DB=varchar(20)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_edges

**Type Mismatches:**
- `edge_id`: TOON=bigint NOT NULL, DB=bigint
- `left_object_type`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `left_object_id`: TOON=bigint NOT NULL, DB=bigint
- `right_object_type`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `right_object_id`: TOON=bigint NOT NULL, DB=bigint
- `edge_type`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `domain_id`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `weight_score`: TOON=int NOT NULL DEFAULT 0, DB=int
- `sort_num`: TOON=int NOT NULL DEFAULT 0, DB=int
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `deleted_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `semantic_weight`: TOON=decimal(5,2) DEFAULT 0.00, DB=decimal(5,2)
- `relationship_type`: TOON=varchar(64) DEFAULT 'semantic', DB=varchar(64)
- `bidirectional`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `flare_weight`: TOON=decimal(3,2) DEFAULT 0.50 COMMENT 'FLARE edge weight (0.5-1.0)', DB=decimal(3,2)
- `flare_reason`: TOON=varchar(255) COMMENT 'Reason for edge existence', DB=varchar(255)
- `flare_db_source`: TOON=varchar(50) COMMENT 'Database source table', DB=varchar(50)
- `flare_auto_generated`: TOON=tinyint DEFAULT 0 COMMENT 'Generated by automation', DB=tinyint
- `flare_verified`: TOON=tinyint DEFAULT 0 COMMENT 'Path verified to exist', DB=tinyint
- `flare_discovered_via`: TOON=varchar(50) COMMENT 'Discovery method', DB=varchar(50)

### lupo_edge_map

**Type Mismatches:**
- `edge_map_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `edge_id`: TOON=bigint NOT NULL, DB=bigint
- `edge_type_id`: TOON=bigint NOT NULL, DB=bigint
- `source_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `source_id`: TOON=bigint NOT NULL, DB=bigint
- `target_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `target_id`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_edge_types

**Type Mismatches:**
- `edge_type_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `slug`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `label`: TOON=varchar(128) NOT NULL, DB=varchar(128)
- `is_bidirectional`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_edge_type_definitions

**Type Mismatches:**
- `edge_type_definition_id`: TOON=bigint NOT NULL, DB=bigint
- `edge_type`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `domain`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `description`: TOON=text NOT NULL, DB=text
- `allowed_left_object_types`: TOON=text NOT NULL, DB=text
- `allowed_right_object_types`: TOON=text NOT NULL, DB=text
- `is_bidirectional`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `created_by_actor_id`: TOON=bigint NOT NULL, DB=bigint

### lupo_emotional_frameworks

**Type Mismatches:**
- `framework_name`: TOON=varchar(32) NOT NULL, DB=varchar(32)
- `is_default`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_escalation_tasks

**Type Mismatches:**
- `escalation_task_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `thread_id`: TOON=bigint NOT NULL, DB=bigint
- `message_id`: TOON=bigint NOT NULL, DB=bigint
- `task_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `status`: TOON=varchar(32) NOT NULL DEFAULT 'open', DB=varchar(32)
- `assigned_actor_id`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_event_metadata

**Type Mismatches:**
- `metadata_id`: TOON=bigint NOT NULL, DB=bigint
- `event_id`: TOON=bigint NOT NULL, DB=bigint
- `metadata_key`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_federated_trust

**Type Mismatches:**
- `trust_id`: TOON=bigint NOT NULL, DB=bigint
- `source_node_id`: TOON=bigint NOT NULL, DB=bigint
- `target_node_id`: TOON=bigint NOT NULL, DB=bigint
- `trust_level`: TOON=float DEFAULT 0.5, DB=float
- `trust_type`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_federation_categories

**Type Mismatches:**
- `federation_category_id`: TOON=bigint NOT NULL, DB=bigint
- `category_name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `category_slug`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `deleted_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_federation_category_map

**Type Mismatches:**
- `federation_category_map_id`: TOON=bigint NOT NULL, DB=bigint
- `federation_node_id`: TOON=bigint NOT NULL, DB=bigint
- `federation_category_id`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `deleted_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_federation_discovery

**Type Mismatches:**
- `federation_discovery_id`: TOON=bigint NOT NULL, DB=bigint
- `domain`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `is_lupopedia`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `import_hashtags`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `import_questions`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `import_atoms`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `import_contexts`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `import_collections`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_federation_nodes

**Type Mismatches:**
- `federation_node_id`: TOON=bigint NOT NULL, DB=bigint
- `node_type`: TOON=varchar(32) NOT NULL DEFAULT 'local', DB=varchar(32)
- `node_base_url`: TOON=varchar(500) NOT NULL, DB=varchar(500)
- `allows_foreign_traits`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `content_count`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `atom_count`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `hashtag_count`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `actor_count`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `last_sync_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `trust_level`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `status`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `deleted_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `active_theme_slug`: TOON=varchar(64) DEFAULT 'default', DB=varchar(64)

### lupo_folders

**Type Mismatches:**
- `folder_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `slug`: TOON=varchar(128) NOT NULL, DB=varchar(128)
- `sort_order`: TOON=int NOT NULL DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_folder_map

**Type Mismatches:**
- `folder_map_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `folder_id`: TOON=bigint NOT NULL, DB=bigint
- `object_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `object_id`: TOON=bigint NOT NULL, DB=bigint
- `sort_order`: TOON=int NOT NULL DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_governance_overrides

**Type Mismatches:**
- `governance_overrid_id`: TOON=bigint NOT NULL, DB=bigint
- `override_type`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_hashtags

**Type Mismatches:**
- `hashtag_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `tag_slug`: TOON=varchar(128) NOT NULL, DB=varchar(128)
- `use_count`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_hashtag_map

**Type Mismatches:**
- `hashtag_map_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `hashtag_id`: TOON=bigint NOT NULL, DB=bigint
- `object_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `object_id`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_help_topics

**Type Mismatches:**
- `help_topic_id`: TOON=bigint NOT NULL, DB=bigint
- `slug`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `title`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `view_count`: TOON=bigint DEFAULT 0, DB=bigint
- `helpful_count`: TOON=bigint DEFAULT 0, DB=bigint
- `not_helpful_count`: TOON=bigint DEFAULT 0, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_help_tree

**Type Mismatches:**
- `help_tree_id`: TOON=bigint NOT NULL, DB=bigint
- `department_id`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `title`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `action_type`: TOON=varchar(64) NOT NULL DEFAULT 'none', DB=varchar(64)
- `sort_order`: TOON=int NOT NULL DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_hotfix_registry

**Type Mismatches:**
- `hotfix_id`: TOON=bigint NOT NULL, DB=bigint
- `hotfix_version`: TOON=varchar(20) NOT NULL, DB=varchar(20)
- `applied_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_human_requests

**Type Mismatches:**
- `request_id`: TOON=bigint NOT NULL, DB=bigint
- `thread_id`: TOON=bigint NOT NULL, DB=bigint
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `project_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `initiator_actor_id`: TOON=bigint NOT NULL, DB=bigint
- `target_auth_user_id`: TOON=bigint NOT NULL, DB=bigint
- `request_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `request_title`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `request_description`: TOON=text NOT NULL, DB=text
- `priority`: TOON=varchar(64) DEFAULT 'normal', DB=varchar(64)
- `request_mode`: TOON=varchar(64) DEFAULT 'single_human', DB=varchar(64)
- `status`: TOON=varchar(64) NOT NULL DEFAULT 'pending', DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `resolved_ymdhis`: TOON=bigint DEFAULT 0, DB=bigint
- `expires_ymdhis`: TOON=bigint DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint DEFAULT 0, DB=tinyint
- `deleted_ymdhis`: TOON=bigint DEFAULT 0, DB=bigint

### lupo_human_request_context

**Type Mismatches:**
- `context_id`: TOON=bigint NOT NULL, DB=bigint
- `request_id`: TOON=bigint NOT NULL, DB=bigint
- `context_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `content`: TOON=text NOT NULL, DB=text
- `created_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_human_request_responses

**Type Mismatches:**
- `response_id`: TOON=bigint NOT NULL, DB=bigint
- `request_id`: TOON=bigint NOT NULL, DB=bigint
- `auth_user_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `response_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `response_text`: TOON=text NOT NULL, DB=text
- `response_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint DEFAULT 0, DB=tinyint
- `deleted_ymdhis`: TOON=bigint DEFAULT 0, DB=bigint

### lupo_interpretation_log

**Type Mismatches:**
- `interpretation_log_id`: TOON=bigint NOT NULL, DB=bigint
- `agent_id`: TOON=bigint NOT NULL, DB=bigint
- `entity_type`: TOON=varchar(32) NOT NULL, DB=varchar(32)
- `entity_id`: TOON=bigint NOT NULL, DB=bigint
- `interpretation`: TOON=text NOT NULL, DB=text
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_labs_declarations

**Type Mismatches:**
- `labs_declaration_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `certificate_id`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `declaration_timestamp`: TOON=bigint NOT NULL, DB=bigint
- `declarations_json`: TOON=json NOT NULL, DB=json
- `validation_status`: TOON=varchar(64) NOT NULL DEFAULT 'valid', DB=varchar(64)
- `labs_version`: TOON=varchar(16) NOT NULL DEFAULT '1.0', DB=varchar(16)
- `next_revalidation_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_labs_violations

**Type Mismatches:**
- `labs_violation_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `certificate_id`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `violation_code`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_legacy_content_mapping

**Type Mismatches:**
- `mapping_id`: TOON=bigint NOT NULL, DB=bigint
- `legacy_url`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `semantic_url`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `content_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint

### lupo_memory_rollups

**Type Mismatches:**
- `memory_rollup_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=int NOT NULL, DB=int
- `summary`: TOON=text NOT NULL, DB=text
- `source_event_ids`: TOON=text NOT NULL, DB=text
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_metadata

**Type Mismatches:**
- `metadata_id`: TOON=bigint NOT NULL, DB=bigint
- `entity_type`: TOON=varchar(32) NOT NULL, DB=varchar(32)
- `entity_id`: TOON=bigint NOT NULL, DB=bigint
- `property_key`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_modules

**Type Mismatches:**
- `module_id`: TOON=bigint NOT NULL, DB=bigint
- `module_key`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `module_name`: TOON=varchar(150) NOT NULL, DB=varchar(150)
- `namespace`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `version`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `version_code`: TOON=int NOT NULL, DB=int
- `minimum_core_version`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `icon`: TOON=varchar(100) DEFAULT 'puzzle-piece', DB=varchar(100)
- `config_json`: TOON=text NOT NULL, DB=text
- `is_system`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `is_active`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `federation_node_id`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_notifications

**Type Mismatches:**
- `notification_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `notification_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `is_read`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_orchestrator_rules

**Type Mismatches:**
- `rule_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `rule_slug`: TOON=varchar(128) NOT NULL, DB=varchar(128)
- `orchestrator_actor`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `rule_set_version`: TOON=varchar(32) NOT NULL, DB=varchar(32)
- `applies_to_json`: TOON=text NOT NULL, DB=text
- `enforcement_level`: TOON=varchar(32) NOT NULL DEFAULT 'strict', DB=varchar(32)
- `rule_content`: TOON=text NOT NULL, DB=text
- `checksum`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_paths

**Type Mismatches:**
- `path_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `count_num`: TOON=int NOT NULL DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_paths_summary

**Type Mismatches:**
- `summary_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `path_id`: TOON=bigint NOT NULL, DB=bigint
- `total_count`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `last_used_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_permissions

**Type Mismatches:**
- `permission_id`: TOON=bigint NOT NULL, DB=bigint
- `target_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `target_id`: TOON=bigint NOT NULL, DB=bigint
- `permission`: TOON=varchar(64) NOT NULL DEFAULT 'read', DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_projects

**Type Mismatches:**
- `project_id`: TOON=bigint NOT NULL, DB=bigint
- `project_key`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `project_slug`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `project_name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `federation_node_id`: TOON=bigint NOT NULL, DB=bigint
- `orchestrator_id`: TOON=bigint NOT NULL, DB=bigint
- `project_type`: TOON=varchar(64) DEFAULT 'standard', DB=varchar(64)
- `status`: TOON=varchar(32) NOT NULL DEFAULT 'active', DB=varchar(32)
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `is_archived`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `is_frozen`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `deleted_ymdhis`: TOON=bigint DEFAULT 0, DB=bigint

### lupo_questions

**Type Mismatches:**
- `question_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `slug`: TOON=varchar(128) NOT NULL, DB=varchar(128)
- `question_text`: TOON=text NOT NULL, DB=text
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_question_map

**Type Mismatches:**
- `question_map_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `question_id`: TOON=bigint NOT NULL, DB=bigint
- `object_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `object_id`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_references

**Type Mismatches:**
- `reference_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `source_entity_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `source_entity_id`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_reference_links

**Type Mismatches:**
- `reference_link_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `reference_id`: TOON=bigint NOT NULL, DB=bigint
- `object_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `object_id`: TOON=bigint NOT NULL, DB=bigint
- `sort_order`: TOON=int NOT NULL DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_reference_map

**Type Mismatches:**
- `reference_map_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `reference_id`: TOON=bigint NOT NULL, DB=bigint
- `target_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `target_id`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_reference_objects

**Type Mismatches:**
- `reference_object_id`: TOON=bigint NOT NULL, DB=bigint
- `object_type`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `object_slug`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_referers

**Type Mismatches:**
- `referer_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `content_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `date_ymd`: TOON=int NOT NULL, DB=int
- `visits`: TOON=int NOT NULL DEFAULT 1, DB=int
- `depth`: TOON=int NOT NULL DEFAULT 0, DB=int

### lupo_registry

**Type Mismatches:**
- `registry_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `entity_type`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `entity_index_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `entity_index`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `federation_node_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `reserved_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `is_kernel`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_registry_open

**Type Mismatches:**
- `unregistry_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `entity_type`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `entity_index_id`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_rolls

**Type Mismatches:**
- `roll_id`: TOON=bigint NOT NULL, DB=bigint
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `role_slug`: TOON=varchar(100) NOT NULL, DB=varchar(100)
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_routing_decisions

**Type Mismatches:**
- `routing_decision_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `thread_id`: TOON=bigint NOT NULL, DB=bigint
- `routing_strategy`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `candidate_users_json`: TOON=text NOT NULL, DB=text
- `selected_auth_user_id`: TOON=bigint NOT NULL, DB=bigint
- `fallback_index`: TOON=int NOT NULL DEFAULT 0, DB=int
- `decision_status`: TOON=varchar(32) NOT NULL, DB=varchar(32)
- `trigger_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `completed_ymdhis`: TOON=bigint DEFAULT 0, DB=bigint

### lupo_rules

**Type Mismatches:**
- `rule_id`: TOON=bigint NOT NULL, DB=bigint
- `rule_name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `rule_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `rule_script`: TOON=text NOT NULL, DB=text
- `rule_version`: TOON=bigint NOT NULL DEFAULT 1, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_rule_logs

**Type Mismatches:**
- `rule_log_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `rule_id`: TOON=bigint NOT NULL, DB=bigint
- `target_table`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `target_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `instance_id`: TOON=bigint DEFAULT 0, DB=bigint
- `event_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_rule_targets

**Type Mismatches:**
- `rule_target_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `rule_id`: TOON=bigint NOT NULL, DB=bigint
- `target_table`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `target_id`: TOON=bigint NOT NULL, DB=bigint
- `priority`: TOON=int NOT NULL DEFAULT 100, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_schema_migrations

**Type Mismatches:**
- `schema_migration_id`: TOON=bigint NOT NULL, DB=bigint
- `version`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `name`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `applied_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint

### lupo_search_index

**Type Mismatches:**
- `search_index_id`: TOON=bigint NOT NULL, DB=bigint
- `domain_id`: TOON=bigint NOT NULL, DB=bigint
- `entity_type`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `entity_id`: TOON=bigint NOT NULL, DB=bigint
- `relevance_score`: TOON=float DEFAULT 1, DB=float
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_search_rebuild_log

**Type Mismatches:**
- `search_rebuild_log_id`: TOON=bigint NOT NULL, DB=bigint
- `entity_type`: TOON=varchar(50) NOT NULL, DB=varchar(50)
- `entity_id`: TOON=bigint NOT NULL, DB=bigint
- `action`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `status`: TOON=varchar(64) NOT NULL DEFAULT 'pending', DB=varchar(64)
- `attempts`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_semantic_index

**Type Mismatches:**
- `semantic_id`: TOON=bigint NOT NULL, DB=bigint
- `semantic_type`: TOON=varchar(32) NOT NULL, DB=varchar(32)
- `sort_order`: TOON=int DEFAULT 0, DB=int
- `weight`: TOON=float DEFAULT 0, DB=float
- `relationship_strength`: TOON=decimal(3,2) DEFAULT 1.00, DB=decimal(3,2)
- `color`: TOON=varchar(7) DEFAULT '#666666', DB=varchar(7)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `is_default`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_sessions

**Type Mismatches:**
- `session_id`: TOON=varchar(128) NOT NULL, DB=varchar(128)
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `federation_node_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `last_activity_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_named`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint
- `is_expired`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `is_revoked`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_system_commands

**Type Mismatches:**
- `command_id`: TOON=bigint NOT NULL, DB=bigint
- `command_type`: TOON=varchar(128) NOT NULL, DB=varchar(128)
- `status`: TOON=varchar(32) NOT NULL, DB=varchar(32)
- `priority`: TOON=int NOT NULL DEFAULT 0, DB=int
- `created_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `scheduled_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `attempt_count`: TOON=int NOT NULL DEFAULT 0, DB=int
- `max_attempts`: TOON=int NOT NULL DEFAULT 3, DB=int
- `timeout_seconds`: TOON=int NOT NULL DEFAULT 3600, DB=int
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_system_config

**Type Mismatches:**
- `system_config_id`: TOON=bigint NOT NULL, DB=bigint
- `config_key`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `config_value`: TOON=text NOT NULL, DB=text
- `actor_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_system_health_snapshots

**Type Mismatches:**
- `snapshot_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `snapshot_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_tasks

**Type Mismatches:**
- `task_id`: TOON=bigint NOT NULL, DB=bigint
- `task_key`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `owner_actor_id`: TOON=bigint NOT NULL, DB=bigint
- `title`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `task_priority`: TOON=enum('low','normal','high','urgent','critical') NOT NULL DEFAULT 'normal', DB=enum('low','normal','high','urgent','critical')
- `visibility_status`: TOON=varchar(32) NOT NULL DEFAULT 'active', DB=varchar(32)

### lupo_thread_metadata

**Type Mismatches:**
- `thread_metadata_id`: TOON=bigint NOT NULL, DB=bigint
- `dialog_thread_id`: TOON=bigint NOT NULL, DB=bigint
- `metadata_key`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `metadata_type`: TOON=varchar(64) NOT NULL DEFAULT 'string', DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `created_by_actor_id`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_tickets

**Type Mismatches:**
- `ticket_id`: TOON=bigint NOT NULL, DB=bigint
- `channel_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `status`: TOON=varchar(64) NOT NULL DEFAULT 'open', DB=varchar(64)
- `priority`: TOON=varchar(64) NOT NULL DEFAULT 'medium', DB=varchar(64)
- `subject`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_ticket_messages

**Type Mismatches:**
- `ticket_message_id`: TOON=bigint NOT NULL, DB=bigint
- `ticket_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `message_text`: TOON=text NOT NULL, DB=text
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_truth_answers

**Type Mismatches:**
- `truth_answer_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `truth_question_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `confidence`: TOON=decimal(5,2) DEFAULT 0.00, DB=decimal(5,2)
- `evidence_count`: TOON=int DEFAULT 0, DB=int
- `source_count`: TOON=int DEFAULT 0, DB=int
- `status`: TOON=varchar(64) DEFAULT 'active', DB=varchar(64)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `evidence_score`: TOON=decimal(5,2) DEFAULT 0.00, DB=decimal(5,2)
- `contradiction_flag`: TOON=tinyint DEFAULT 0, DB=tinyint
- `likes_count`: TOON=bigint DEFAULT 0, DB=bigint

### lupo_truth_knowledge

**Type Mismatches:**
- `truth_id`: TOON=bigint NOT NULL, DB=bigint
- `truth_type`: TOON=varchar(32) NOT NULL, DB=varchar(32)
- `actor_id`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `source_title`: TOON=varchar(255) DEFAULT '', DB=varchar(255)
- `qtype`: TOON=varchar(50) DEFAULT 'unknown', DB=varchar(50)
- `status`: TOON=varchar(64) DEFAULT 'active', DB=varchar(64)
- `evidence_type`: TOON=varchar(50) DEFAULT '', DB=varchar(50)
- `source_type`: TOON=varchar(50) DEFAULT '', DB=varchar(50)
- `relation_type`: TOON=varchar(50) DEFAULT '', DB=varchar(50)
- `format`: TOON=varchar(64) DEFAULT 'text', DB=varchar(64)
- `confidence_score`: TOON=decimal(5,2) DEFAULT 0.00, DB=decimal(5,2)
- `evidence_score`: TOON=decimal(5,2) DEFAULT 0.00, DB=decimal(5,2)
- `weight_score`: TOON=decimal(5,2) DEFAULT 0.00, DB=decimal(5,2)
- `reliability_score`: TOON=decimal(5,2) DEFAULT 0.00, DB=decimal(5,2)
- `importance_score`: TOON=decimal(5,2) DEFAULT 0.00, DB=decimal(5,2)
- `sort_num`: TOON=int DEFAULT 0, DB=int
- `view_count`: TOON=bigint DEFAULT 0, DB=bigint
- `likes_count`: TOON=bigint DEFAULT 0, DB=bigint
- `shares_count`: TOON=bigint DEFAULT 0, DB=bigint
- `answer_count`: TOON=bigint DEFAULT 0, DB=bigint
- `contradiction_flag`: TOON=tinyint DEFAULT 0, DB=tinyint
- `is_featured`: TOON=tinyint DEFAULT 0, DB=tinyint
- `is_verified`: TOON=tinyint DEFAULT 0, DB=tinyint
- `default_collection_id`: TOON=bigint DEFAULT 0, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_unified_log

**Type Mismatches:**
- `log_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `log_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `log_level`: TOON=varchar(32) NOT NULL DEFAULT 'info', DB=varchar(32)
- `log_message`: TOON=text NOT NULL, DB=text
- `created_ymdhis`: TOON=bigint NOT NULL, DB=bigint

### lupo_uploads

**Type Mismatches:**
- `upload_id`: TOON=bigint NOT NULL, DB=bigint
- `actor_id`: TOON=bigint NOT NULL, DB=bigint
- `original_filename`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `stored_filename`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `file_extension`: TOON=varchar(16) NOT NULL, DB=varchar(16)
- `mime_type`: TOON=varchar(128) NOT NULL, DB=varchar(128)
- `file_size_bytes`: TOON=bigint NOT NULL, DB=bigint
- `storage_path`: TOON=varchar(512) NOT NULL, DB=varchar(512)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_visits

**Type Mismatches:**
- `visit_id`: TOON=bigint NOT NULL auto_increment, DB=bigint
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `is_processed`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint
- `is_deleted`: TOON=tinyint NOT NULL DEFAULT 0, DB=tinyint

### lupo_world_registry

**Type Mismatches:**
- `world_id`: TOON=bigint NOT NULL, DB=bigint
- `world_key`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `world_type`: TOON=varchar(64) NOT NULL, DB=varchar(64)
- `world_label`: TOON=varchar(255) NOT NULL, DB=varchar(255)
- `created_ymdhis`: TOON=bigint NOT NULL DEFAULT 0, DB=bigint
- `updated_ymdhis`: TOON=bigint NOT NULL, DB=bigint
- `is_active`: TOON=tinyint NOT NULL DEFAULT 1, DB=tinyint


## Migration Suggestions

The following SQL statements are suggested to bring the database in line with TOON definitions:

```sql
-- Fix type mismatch in lupo_action_authorization.action_authorization_id
ALTER TABLE `lupo_action_authorization` MODIFY COLUMN `action_authorization_id` bigint NOT NULL;
-- Fix type mismatch in lupo_action_authorization.action_key
ALTER TABLE `lupo_action_authorization` MODIFY COLUMN `action_key` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_action_authorization.description
ALTER TABLE `lupo_action_authorization` MODIFY COLUMN `description` text NOT NULL;
-- Fix type mismatch in lupo_action_authorization.requires_all_conditions
ALTER TABLE `lupo_action_authorization` MODIFY COLUMN `requires_all_conditions` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_action_authorization.created_ymdhis
ALTER TABLE `lupo_action_authorization` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_action_authorization.created_by_actor_id
ALTER TABLE `lupo_action_authorization` MODIFY COLUMN `created_by_actor_id` bigint NOT NULL;

-- Fix type mismatch in lupo_actors.actor_name
ALTER TABLE `lupo_actors` MODIFY COLUMN `actor_name` varchar(64) NOT NULL;
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
-- Fix type mismatch in lupo_actors.adversarial_role
ALTER TABLE `lupo_actors` MODIFY COLUMN `adversarial_role` varchar(64) DEFAULT 'none';
-- Fix type mismatch in lupo_actors.primary_federation_node_id
ALTER TABLE `lupo_actors` MODIFY COLUMN `primary_federation_node_id` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_actors.is_kernel
ALTER TABLE `lupo_actors` MODIFY COLUMN `is_kernel` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actors.can_login
ALTER TABLE `lupo_actors` MODIFY COLUMN `can_login` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actors.paired_actor_id
ALTER TABLE `lupo_actors` MODIFY COLUMN `paired_actor_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actors.is_agent
ALTER TABLE `lupo_actors` MODIFY COLUMN `is_agent` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actors.actor_root_path
ALTER TABLE `lupo_actors` MODIFY COLUMN `actor_root_path` varchar(512) DEFAULT 'actors/{actor_id}';
-- Fix type mismatch in lupo_actors.who_json_sync_status
ALTER TABLE `lupo_actors` MODIFY COLUMN `who_json_sync_status` varchar(64) DEFAULT 'pending';
-- Fix type mismatch in lupo_actors.last_sync_ymdhis
ALTER TABLE `lupo_actors` MODIFY COLUMN `last_sync_ymdhis` bigint DEFAULT 0;
-- Fix type mismatch in lupo_actors.actor_tier
ALTER TABLE `lupo_actors` MODIFY COLUMN `actor_tier` tinyint DEFAULT 3;

-- Fix type mismatch in lupo_actor_actions.actor_action_id
ALTER TABLE `lupo_actor_actions` MODIFY COLUMN `actor_action_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_actions.actor_id
ALTER TABLE `lupo_actor_actions` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_actions.action_type
ALTER TABLE `lupo_actor_actions` MODIFY COLUMN `action_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_actor_actions.created_ymdhis
ALTER TABLE `lupo_actor_actions` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_actor_apps.actor_app_id
ALTER TABLE `lupo_actor_apps` MODIFY COLUMN `actor_app_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_apps.actor_id
ALTER TABLE `lupo_actor_apps` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_apps.apps_path
ALTER TABLE `lupo_actor_apps` MODIFY COLUMN `apps_path` varchar(512) NOT NULL DEFAULT '';
-- Fix type mismatch in lupo_actor_apps.updated_ymdhis
ALTER TABLE `lupo_actor_apps` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_actor_auth_users.actor_auth_user_id
ALTER TABLE `lupo_actor_auth_users` MODIFY COLUMN `actor_auth_user_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_auth_users.actor_id
ALTER TABLE `lupo_actor_auth_users` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_auth_users.auth_user_id
ALTER TABLE `lupo_actor_auth_users` MODIFY COLUMN `auth_user_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_auth_users.relationship_role
ALTER TABLE `lupo_actor_auth_users` MODIFY COLUMN `relationship_role` varchar(64) NOT NULL DEFAULT 'supporting_human';
-- Fix type mismatch in lupo_actor_auth_users.is_primary
ALTER TABLE `lupo_actor_auth_users` MODIFY COLUMN `is_primary` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_auth_users.routing_priority
ALTER TABLE `lupo_actor_auth_users` MODIFY COLUMN `routing_priority` smallint NOT NULL DEFAULT 100;
-- Fix type mismatch in lupo_actor_auth_users.status
ALTER TABLE `lupo_actor_auth_users` MODIFY COLUMN `status` varchar(32) NOT NULL DEFAULT 'active';
-- Fix type mismatch in lupo_actor_auth_users.created_ymdhis
ALTER TABLE `lupo_actor_auth_users` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_auth_users.updated_ymdhis
ALTER TABLE `lupo_actor_auth_users` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_auth_users.is_deleted
ALTER TABLE `lupo_actor_auth_users` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_auth_users.deleted_ymdhis
ALTER TABLE `lupo_actor_auth_users` MODIFY COLUMN `deleted_ymdhis` bigint DEFAULT 0;

-- Fix type mismatch in lupo_actor_capabilities.actor_capability_id
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `actor_capability_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_capabilities.actor_id
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_capabilities.domain_id
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `domain_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_capabilities.capability_key
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `capability_key` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_actor_capabilities.created_ymdhis
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_capabilities.is_deleted
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_capabilities.scope_limitation
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `scope_limitation` varchar(50) DEFAULT 'unrestricted';
-- Fix type mismatch in lupo_actor_capabilities.max_calls_per_hour
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `max_calls_per_hour` int DEFAULT 0;
-- Fix type mismatch in lupo_actor_capabilities.requires_approval
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `requires_approval` tinyint DEFAULT 0;

-- Fix type mismatch in lupo_actor_channels.actor_channel_id
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `actor_channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_channels.actor_id
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_channels.created_by_actor_id
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `created_by_actor_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_channels.channel_id
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_channels.status
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `status` char(1) NOT NULL DEFAULT 'A';
-- Fix type mismatch in lupo_actor_channels.channel_color
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `channel_color` varchar(6) NOT NULL DEFAULT 'F7FAFF';
-- Fix type mismatch in lupo_actor_channels.created_ymdhis
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_channels.updated_ymdhis
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_channels.is_deleted
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_actor_channel_roles.actor_channel_role_id
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `actor_channel_role_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_channel_roles.actor_id
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_channel_roles.channel_id
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_channel_roles.role_key
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `role_key` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_actor_channel_roles.created_ymdhis
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_channel_roles.updated_ymdhis
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_channel_roles.is_deleted
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_channel_roles.protocol_completion_status
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `protocol_completion_status` varchar(64) DEFAULT 'pending';
-- Fix type mismatch in lupo_actor_channel_roles.protocol_version
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `protocol_version` varchar(20) DEFAULT '3.0.0';
-- Fix type mismatch in lupo_actor_channel_roles.join_sequence_step
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `join_sequence_step` tinyint DEFAULT 0;

-- Fix type mismatch in lupo_actor_collections.actor_collection_id
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `actor_collection_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_collections.actor_id
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_collections.collection_id
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `collection_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_collections.access_level
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `access_level` varchar(64) NOT NULL DEFAULT 'read';
-- Fix type mismatch in lupo_actor_collections.created_ymdhis
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_collections.is_deleted
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_collections.trust_level
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `trust_level` varchar(64) DEFAULT 'standard';
-- Fix type mismatch in lupo_actor_collections.doctrine_alignment_version
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `doctrine_alignment_version` varchar(20) DEFAULT '3.0.0';

-- Fix type mismatch in lupo_actor_conflicts.actor_conflict_id
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `actor_conflict_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_conflicts.domain_id
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `domain_id` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_actor_conflicts.actor_a_id
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `actor_a_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_conflicts.actor_b_id
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `actor_b_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_conflicts.conflict_type
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `conflict_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_actor_conflicts.conflict_summary
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `conflict_summary` text NOT NULL;
-- Fix type mismatch in lupo_actor_conflicts.resolution_status
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `resolution_status` varchar(64) NOT NULL DEFAULT 'unresolved';
-- Fix type mismatch in lupo_actor_conflicts.severity
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `severity` varchar(64) NOT NULL DEFAULT 'medium';
-- Fix type mismatch in lupo_actor_conflicts.created_ymdhis
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_conflicts.updated_ymdhis
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_conflicts.is_deleted
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_actor_departments.actor_department_id
ALTER TABLE `lupo_actor_departments` MODIFY COLUMN `actor_department_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_departments.actor_id
ALTER TABLE `lupo_actor_departments` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_departments.department_id
ALTER TABLE `lupo_actor_departments` MODIFY COLUMN `department_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_departments.created_ymdhis
ALTER TABLE `lupo_actor_departments` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_departments.updated_ymdhis
ALTER TABLE `lupo_actor_departments` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_departments.is_deleted
ALTER TABLE `lupo_actor_departments` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_actor_handshakes.actor_handshake_id
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `actor_handshake_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_handshakes.actor_id
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_handshakes.actor_type
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `actor_type` varchar(32) NOT NULL;
-- Fix type mismatch in lupo_actor_handshakes.utc_timestamp
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `utc_timestamp` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_handshakes.created_ymdhis
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_handshakes.is_deleted
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_actor_history.history_id
ALTER TABLE `lupo_actor_history` MODIFY COLUMN `history_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_history.actor_id
ALTER TABLE `lupo_actor_history` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_history.title
ALTER TABLE `lupo_actor_history` MODIFY COLUMN `title` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_actor_history.date_ymdhis
ALTER TABLE `lupo_actor_history` MODIFY COLUMN `date_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_history.created_ymdhis
ALTER TABLE `lupo_actor_history` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_history.is_deleted
ALTER TABLE `lupo_actor_history` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_actor_moods.actor_id
ALTER TABLE `lupo_actor_moods` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_moods.mood_r
ALTER TABLE `lupo_actor_moods` MODIFY COLUMN `mood_r` tinyint NOT NULL;
-- Fix type mismatch in lupo_actor_moods.mood_g
ALTER TABLE `lupo_actor_moods` MODIFY COLUMN `mood_g` tinyint NOT NULL;
-- Fix type mismatch in lupo_actor_moods.mood_b
ALTER TABLE `lupo_actor_moods` MODIFY COLUMN `mood_b` tinyint NOT NULL;
-- Fix type mismatch in lupo_actor_moods.mood_framework
ALTER TABLE `lupo_actor_moods` MODIFY COLUMN `mood_framework` varchar(32) NOT NULL DEFAULT 'western_analytical';
-- Fix type mismatch in lupo_actor_moods.timestamp_utc
ALTER TABLE `lupo_actor_moods` MODIFY COLUMN `timestamp_utc` bigint NOT NULL;

-- Fix type mismatch in lupo_actor_reply_templates.actor_reply_template_id
ALTER TABLE `lupo_actor_reply_templates` MODIFY COLUMN `actor_reply_template_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_reply_templates.actor_id
ALTER TABLE `lupo_actor_reply_templates` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_reply_templates.template_key
ALTER TABLE `lupo_actor_reply_templates` MODIFY COLUMN `template_key` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_actor_reply_templates.template_text
ALTER TABLE `lupo_actor_reply_templates` MODIFY COLUMN `template_text` text NOT NULL;
-- Fix type mismatch in lupo_actor_reply_templates.created_ymdhis
ALTER TABLE `lupo_actor_reply_templates` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_reply_templates.updated_ymdhis
ALTER TABLE `lupo_actor_reply_templates` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_reply_templates.is_deleted
ALTER TABLE `lupo_actor_reply_templates` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_actor_traits.actor_trait_id
ALTER TABLE `lupo_actor_traits` MODIFY COLUMN `actor_trait_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_traits.actor_id
ALTER TABLE `lupo_actor_traits` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_actor_traits.trait_key
ALTER TABLE `lupo_actor_traits` MODIFY COLUMN `trait_key` varchar(128) NOT NULL;
-- Fix type mismatch in lupo_actor_traits.federation_node_id
ALTER TABLE `lupo_actor_traits` MODIFY COLUMN `federation_node_id` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_actor_traits.created_ymdhis
ALTER TABLE `lupo_actor_traits` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_actor_traits.is_deleted
ALTER TABLE `lupo_actor_traits` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_agents.agent_id
ALTER TABLE `lupo_agents` MODIFY COLUMN `agent_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agents.agent_key
ALTER TABLE `lupo_agents` MODIFY COLUMN `agent_key` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_agents.agent_name
ALTER TABLE `lupo_agents` MODIFY COLUMN `agent_name` varchar(150) NOT NULL;
-- Fix type mismatch in lupo_agents.version
ALTER TABLE `lupo_agents` MODIFY COLUMN `version` varchar(50) DEFAULT '1.0';
-- Fix type mismatch in lupo_agents.is_global_authority
ALTER TABLE `lupo_agents` MODIFY COLUMN `is_global_authority` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_agents.is_internal_only
ALTER TABLE `lupo_agents` MODIFY COLUMN `is_internal_only` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_agents.created_ymdhis
ALTER TABLE `lupo_agents` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_agents.is_deleted
ALTER TABLE `lupo_agents` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_agents.avg_response_time_ms
ALTER TABLE `lupo_agents` MODIFY COLUMN `avg_response_time_ms` int DEFAULT 0;
-- Fix type mismatch in lupo_agents.total_tokens_processed
ALTER TABLE `lupo_agents` MODIFY COLUMN `total_tokens_processed` bigint DEFAULT 0;
-- Fix type mismatch in lupo_agents.success_rate
ALTER TABLE `lupo_agents` MODIFY COLUMN `success_rate` float DEFAULT 1;
-- Fix type mismatch in lupo_agents.cost_per_1k_tokens
ALTER TABLE `lupo_agents` MODIFY COLUMN `cost_per_1k_tokens` decimal(10,4) DEFAULT 0.0000;
-- Fix type mismatch in lupo_agents.temperature
ALTER TABLE `lupo_agents` MODIFY COLUMN `temperature` float DEFAULT 0.7;
-- Fix type mismatch in lupo_agents.top_p
ALTER TABLE `lupo_agents` MODIFY COLUMN `top_p` float DEFAULT 1;
-- Fix type mismatch in lupo_agents.max_tokens
ALTER TABLE `lupo_agents` MODIFY COLUMN `max_tokens` int DEFAULT 2048;
-- Fix type mismatch in lupo_agents.presence_penalty
ALTER TABLE `lupo_agents` MODIFY COLUMN `presence_penalty` float DEFAULT 0;
-- Fix type mismatch in lupo_agents.frequency_penalty
ALTER TABLE `lupo_agents` MODIFY COLUMN `frequency_penalty` float DEFAULT 0;
-- Fix type mismatch in lupo_agents.provider
ALTER TABLE `lupo_agents` MODIFY COLUMN `provider` varchar(50) DEFAULT 'openai';
-- Fix type mismatch in lupo_agents.timeout_ms
ALTER TABLE `lupo_agents` MODIFY COLUMN `timeout_ms` int DEFAULT 20000;
-- Fix type mismatch in lupo_agents.pono_score
ALTER TABLE `lupo_agents` MODIFY COLUMN `pono_score` decimal(3,2) DEFAULT 1.00;
-- Fix type mismatch in lupo_agents.pilau_score
ALTER TABLE `lupo_agents` MODIFY COLUMN `pilau_score` decimal(3,2) DEFAULT 0.00;
-- Fix type mismatch in lupo_agents.kapakai_score
ALTER TABLE `lupo_agents` MODIFY COLUMN `kapakai_score` decimal(3,2) DEFAULT 0.50;
-- Fix type mismatch in lupo_agents.kapu_active
ALTER TABLE `lupo_agents` MODIFY COLUMN `kapu_active` tinyint DEFAULT 0;
-- Fix type mismatch in lupo_agents.kapu_consent_given
ALTER TABLE `lupo_agents` MODIFY COLUMN `kapu_consent_given` tinyint DEFAULT 0;
-- Fix type mismatch in lupo_agents.kapu_appeal_pending
ALTER TABLE `lupo_agents` MODIFY COLUMN `kapu_appeal_pending` tinyint DEFAULT 0;

-- Fix type mismatch in lupo_agent_context_snapshots.agent_context_snapshot_id
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `agent_context_snapshot_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_context_snapshots.session_id
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `session_id` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_agent_context_snapshots.actor_id
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_context_snapshots.snapshot_type
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `snapshot_type` varchar(64) NOT NULL DEFAULT 'full';
-- Fix type mismatch in lupo_agent_context_snapshots.context_data
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `context_data` text NOT NULL;
-- Fix type mismatch in lupo_agent_context_snapshots.compression_method
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `compression_method` varchar(64) DEFAULT 'gzip';
-- Fix type mismatch in lupo_agent_context_snapshots.created_ymdhis
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_agent_context_snapshots.is_corrupt
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `is_corrupt` tinyint DEFAULT 0;
-- Fix type mismatch in lupo_agent_context_snapshots.retention_policy
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `retention_policy` varchar(64) DEFAULT 'temporary';

-- Fix type mismatch in lupo_agent_dependencies.agent_dependency_id
ALTER TABLE `lupo_agent_dependencies` MODIFY COLUMN `agent_dependency_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_dependencies.agent_id
ALTER TABLE `lupo_agent_dependencies` MODIFY COLUMN `agent_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_dependencies.depends_on_agent_id
ALTER TABLE `lupo_agent_dependencies` MODIFY COLUMN `depends_on_agent_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_dependencies.depends_on_agent_code
ALTER TABLE `lupo_agent_dependencies` MODIFY COLUMN `depends_on_agent_code` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_agent_dependencies.is_required
ALTER TABLE `lupo_agent_dependencies` MODIFY COLUMN `is_required` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_agent_dependencies.created_ymdhis
ALTER TABLE `lupo_agent_dependencies` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_agent_experiences.link_id
ALTER TABLE `lupo_agent_experiences` MODIFY COLUMN `link_id` char(26) NOT NULL;
-- Fix type mismatch in lupo_agent_experiences.agent_id
ALTER TABLE `lupo_agent_experiences` MODIFY COLUMN `agent_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_experiences.star_id
ALTER TABLE `lupo_agent_experiences` MODIFY COLUMN `star_id` char(26) NOT NULL;

-- Fix type mismatch in lupo_agent_external_events.external_event_id
ALTER TABLE `lupo_agent_external_events` MODIFY COLUMN `external_event_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_external_events.agent_name
ALTER TABLE `lupo_agent_external_events` MODIFY COLUMN `agent_name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_agent_external_events.source_system
ALTER TABLE `lupo_agent_external_events` MODIFY COLUMN `source_system` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_agent_external_events.event_type
ALTER TABLE `lupo_agent_external_events` MODIFY COLUMN `event_type` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_agent_external_events.created_ymdhis
ALTER TABLE `lupo_agent_external_events` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_agent_faucets.agent_faucet_id
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `agent_faucet_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_faucets.actor_id
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_faucets.name
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `name` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_agent_faucets.slug
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `slug` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_agent_faucets.is_default
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `is_default` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_agent_faucets.domain_id
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `domain_id` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_agent_faucets.created_ymdhis
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_agent_faucets.updated_ymdhis
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_agent_faucet_credentials.agent_faucet_credential_id
ALTER TABLE `lupo_agent_faucet_credentials` MODIFY COLUMN `agent_faucet_credential_id` int NOT NULL;
-- Fix type mismatch in lupo_agent_faucet_credentials.faucet_id
ALTER TABLE `lupo_agent_faucet_credentials` MODIFY COLUMN `faucet_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_faucet_credentials.provider
ALTER TABLE `lupo_agent_faucet_credentials` MODIFY COLUMN `provider` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_agent_faucet_credentials.api_key
ALTER TABLE `lupo_agent_faucet_credentials` MODIFY COLUMN `api_key` varbinary(512) NOT NULL;
-- Fix type mismatch in lupo_agent_faucet_credentials.created_ymdhis
ALTER TABLE `lupo_agent_faucet_credentials` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_agent_faucet_credentials.updated_ymdhis
ALTER TABLE `lupo_agent_faucet_credentials` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_agent_files.file_id
ALTER TABLE `lupo_agent_files` MODIFY COLUMN `file_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_files.agent_id
ALTER TABLE `lupo_agent_files` MODIFY COLUMN `agent_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_files.file_type
ALTER TABLE `lupo_agent_files` MODIFY COLUMN `file_type` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_agent_files.file_name
ALTER TABLE `lupo_agent_files` MODIFY COLUMN `file_name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_agent_files.file_path
ALTER TABLE `lupo_agent_files` MODIFY COLUMN `file_path` varchar(500) NOT NULL;
-- Fix type mismatch in lupo_agent_files.file_hash
ALTER TABLE `lupo_agent_files` MODIFY COLUMN `file_hash` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_agent_files.file_size
ALTER TABLE `lupo_agent_files` MODIFY COLUMN `file_size` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_files.upload_ymdhis
ALTER TABLE `lupo_agent_files` MODIFY COLUMN `upload_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_files.created_ymdhis
ALTER TABLE `lupo_agent_files` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_agent_files.updated_ymdhis
ALTER TABLE `lupo_agent_files` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_files.is_deleted
ALTER TABLE `lupo_agent_files` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_agent_heartbeats.heartbeat_id
ALTER TABLE `lupo_agent_heartbeats` MODIFY COLUMN `heartbeat_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_heartbeats.agent_slug
ALTER TABLE `lupo_agent_heartbeats` MODIFY COLUMN `agent_slug` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_agent_heartbeats.status
ALTER TABLE `lupo_agent_heartbeats` MODIFY COLUMN `status` varchar(32) NOT NULL DEFAULT 'unknown';
-- Fix type mismatch in lupo_agent_heartbeats.last_heartbeat_ymdhis
ALTER TABLE `lupo_agent_heartbeats` MODIFY COLUMN `last_heartbeat_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_heartbeats.created_ymdhis
ALTER TABLE `lupo_agent_heartbeats` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_agent_heartbeats.updated_ymdhis
ALTER TABLE `lupo_agent_heartbeats` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_agent_heartbeats.is_deleted
ALTER TABLE `lupo_agent_heartbeats` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_agent_tool_calls.agent_tool_call_id
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `agent_tool_call_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_tool_calls.agent_id
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `agent_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_tool_calls.domain_id
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `domain_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_tool_calls.tool_name
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `tool_name` varchar(150) NOT NULL;
-- Fix type mismatch in lupo_agent_tool_calls.tokens_prompt
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `tokens_prompt` int DEFAULT 0;
-- Fix type mismatch in lupo_agent_tool_calls.tokens_completion
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `tokens_completion` int DEFAULT 0;
-- Fix type mismatch in lupo_agent_tool_calls.tokens_total
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `tokens_total` int DEFAULT 0;
-- Fix type mismatch in lupo_agent_tool_calls.cost_usd
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `cost_usd` decimal(10,6) DEFAULT 0.000000;
-- Fix type mismatch in lupo_agent_tool_calls.latency_ms
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `latency_ms` int DEFAULT 0;
-- Fix type mismatch in lupo_agent_tool_calls.status
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `status` varchar(50) DEFAULT 'success';
-- Fix type mismatch in lupo_agent_tool_calls.created_ymdhis
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_agent_tool_calls.updated_ymdhis
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_agent_tool_calls.is_deleted
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_agent_tool_calls.archived_ymdhis
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `archived_ymdhis` bigint DEFAULT 0;

-- Fix type mismatch in lupo_agent_versions.agent_version_id
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `agent_version_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_versions.agent_id
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `agent_id` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_versions.version_label
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `version_label` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_agent_versions.semver_major
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `semver_major` int DEFAULT 0;
-- Fix type mismatch in lupo_agent_versions.semver_minor
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `semver_minor` int DEFAULT 0;
-- Fix type mismatch in lupo_agent_versions.semver_patch
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `semver_patch` int DEFAULT 0;
-- Fix type mismatch in lupo_agent_versions.created_ymdhis
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_agent_versions.updated_ymdhis
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_agent_versions.is_deleted
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `is_deleted` smallint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_aliases.alias_id
ALTER TABLE `lupo_aliases` MODIFY COLUMN `alias_id` bigint NOT NULL;
-- Fix type mismatch in lupo_aliases.slug
ALTER TABLE `lupo_aliases` MODIFY COLUMN `slug` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_aliases.alias
ALTER TABLE `lupo_aliases` MODIFY COLUMN `alias` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_aliases.alias_type
ALTER TABLE `lupo_aliases` MODIFY COLUMN `alias_type` varchar(50) DEFAULT 'semantic';
-- Fix type mismatch in lupo_aliases.created_ymdhis
ALTER TABLE `lupo_aliases` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_analytics_campaign_vars.campaign_var_id
ALTER TABLE `lupo_analytics_campaign_vars` MODIFY COLUMN `campaign_var_id` bigint NOT NULL;
-- Fix type mismatch in lupo_analytics_campaign_vars.period
ALTER TABLE `lupo_analytics_campaign_vars` MODIFY COLUMN `period` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_analytics_campaign_vars.campaign_key
ALTER TABLE `lupo_analytics_campaign_vars` MODIFY COLUMN `campaign_key` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_analytics_campaign_vars.created_ymdhis
ALTER TABLE `lupo_analytics_campaign_vars` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_answers.answer_id
ALTER TABLE `lupo_answers` MODIFY COLUMN `answer_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_answers.question_id
ALTER TABLE `lupo_answers` MODIFY COLUMN `question_id` bigint NOT NULL;
-- Fix type mismatch in lupo_answers.answer_text
ALTER TABLE `lupo_answers` MODIFY COLUMN `answer_text` text NOT NULL;
-- Fix type mismatch in lupo_answers.created_ymdhis
ALTER TABLE `lupo_answers` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_answers.updated_ymdhis
ALTER TABLE `lupo_answers` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_answers.is_deleted
ALTER TABLE `lupo_answers` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_anubis_events.anubis_event_id
ALTER TABLE `lupo_anubis_events` MODIFY COLUMN `anubis_event_id` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_events.event_type
ALTER TABLE `lupo_anubis_events` MODIFY COLUMN `event_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_anubis_events.table_name
ALTER TABLE `lupo_anubis_events` MODIFY COLUMN `table_name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_anubis_events.row_id
ALTER TABLE `lupo_anubis_events` MODIFY COLUMN `row_id` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_events.created_ymdhis
ALTER TABLE `lupo_anubis_events` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_events.agent
ALTER TABLE `lupo_anubis_events` MODIFY COLUMN `agent` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_anubis_events.details_json
ALTER TABLE `lupo_anubis_events` MODIFY COLUMN `details_json` text NOT NULL;

-- Fix type mismatch in lupo_anubis_log.anubis_log_id
ALTER TABLE `lupo_anubis_log` MODIFY COLUMN `anubis_log_id` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_log.event_type
ALTER TABLE `lupo_anubis_log` MODIFY COLUMN `event_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_anubis_log.severity
ALTER TABLE `lupo_anubis_log` MODIFY COLUMN `severity` varchar(20) NOT NULL DEFAULT 'normal';
-- Fix type mismatch in lupo_anubis_log.status
ALTER TABLE `lupo_anubis_log` MODIFY COLUMN `status` varchar(64) NOT NULL DEFAULT 'Pending';
-- Fix type mismatch in lupo_anubis_log.assigned_to_actor_id
ALTER TABLE `lupo_anubis_log` MODIFY COLUMN `assigned_to_actor_id` bigint NOT NULL DEFAULT 19;
-- Fix type mismatch in lupo_anubis_log.created_ymdhis
ALTER TABLE `lupo_anubis_log` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_anubis_log.updated_ymdhis
ALTER TABLE `lupo_anubis_log` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_anubis_log.is_deleted
ALTER TABLE `lupo_anubis_log` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_anubis_operations.operation_id
ALTER TABLE `lupo_anubis_operations` MODIFY COLUMN `operation_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_anubis_operations.operation_type
ALTER TABLE `lupo_anubis_operations` MODIFY COLUMN `operation_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_anubis_operations.target_type
ALTER TABLE `lupo_anubis_operations` MODIFY COLUMN `target_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_anubis_operations.target_id
ALTER TABLE `lupo_anubis_operations` MODIFY COLUMN `target_id` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_operations.channel_id
ALTER TABLE `lupo_anubis_operations` MODIFY COLUMN `channel_id` bigint NOT NULL DEFAULT 42;
-- Fix type mismatch in lupo_anubis_operations.actor_id
ALTER TABLE `lupo_anubis_operations` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_operations.created_ymdhis
ALTER TABLE `lupo_anubis_operations` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_anubis_processing_log.log_id
ALTER TABLE `lupo_anubis_processing_log` MODIFY COLUMN `log_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_anubis_processing_log.queue_id
ALTER TABLE `lupo_anubis_processing_log` MODIFY COLUMN `queue_id` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_processing_log.file_path
ALTER TABLE `lupo_anubis_processing_log` MODIFY COLUMN `file_path` varchar(512) NOT NULL;
-- Fix type mismatch in lupo_anubis_processing_log.action
ALTER TABLE `lupo_anubis_processing_log` MODIFY COLUMN `action` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_anubis_processing_log.created_utc
ALTER TABLE `lupo_anubis_processing_log` MODIFY COLUMN `created_utc` bigint NOT NULL;

-- Fix type mismatch in lupo_anubis_quarantine.quarantine_id
ALTER TABLE `lupo_anubis_quarantine` MODIFY COLUMN `quarantine_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_anubis_quarantine.queue_id
ALTER TABLE `lupo_anubis_quarantine` MODIFY COLUMN `queue_id` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_quarantine.file_path
ALTER TABLE `lupo_anubis_quarantine` MODIFY COLUMN `file_path` varchar(512) NOT NULL;
-- Fix type mismatch in lupo_anubis_quarantine.quarantine_path
ALTER TABLE `lupo_anubis_quarantine` MODIFY COLUMN `quarantine_path` varchar(512) NOT NULL;
-- Fix type mismatch in lupo_anubis_quarantine.reason
ALTER TABLE `lupo_anubis_quarantine` MODIFY COLUMN `reason` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_anubis_quarantine.quarantined_utc
ALTER TABLE `lupo_anubis_quarantine` MODIFY COLUMN `quarantined_utc` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_quarantine.is_deleted
ALTER TABLE `lupo_anubis_quarantine` MODIFY COLUMN `is_deleted` tinyint DEFAULT 0;

-- Fix type mismatch in lupo_anubis_queue.queue_id
ALTER TABLE `lupo_anubis_queue` MODIFY COLUMN `queue_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_anubis_queue.file_path
ALTER TABLE `lupo_anubis_queue` MODIFY COLUMN `file_path` varchar(512) NOT NULL;
-- Fix type mismatch in lupo_anubis_queue.detected_utc
ALTER TABLE `lupo_anubis_queue` MODIFY COLUMN `detected_utc` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_queue.priority
ALTER TABLE `lupo_anubis_queue` MODIFY COLUMN `priority` tinyint DEFAULT 5;
-- Fix type mismatch in lupo_anubis_queue.status
ALTER TABLE `lupo_anubis_queue` MODIFY COLUMN `status` varchar(32) DEFAULT 'pending';
-- Fix type mismatch in lupo_anubis_queue.attempts
ALTER TABLE `lupo_anubis_queue` MODIFY COLUMN `attempts` tinyint DEFAULT 0;
-- Fix type mismatch in lupo_anubis_queue.filesystem_copy_exists
ALTER TABLE `lupo_anubis_queue` MODIFY COLUMN `filesystem_copy_exists` tinyint DEFAULT 1;
-- Fix type mismatch in lupo_anubis_queue.created_utc
ALTER TABLE `lupo_anubis_queue` MODIFY COLUMN `created_utc` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_queue.updated_utc
ALTER TABLE `lupo_anubis_queue` MODIFY COLUMN `updated_utc` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_queue.is_deleted
ALTER TABLE `lupo_anubis_queue` MODIFY COLUMN `is_deleted` tinyint DEFAULT 0;

-- Fix type mismatch in lupo_anubis_recovery_attempts.attempt_id
ALTER TABLE `lupo_anubis_recovery_attempts` MODIFY COLUMN `attempt_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_anubis_recovery_attempts.queue_id
ALTER TABLE `lupo_anubis_recovery_attempts` MODIFY COLUMN `queue_id` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_recovery_attempts.attempt_number
ALTER TABLE `lupo_anubis_recovery_attempts` MODIFY COLUMN `attempt_number` tinyint NOT NULL;
-- Fix type mismatch in lupo_anubis_recovery_attempts.attempt_utc
ALTER TABLE `lupo_anubis_recovery_attempts` MODIFY COLUMN `attempt_utc` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_recovery_attempts.success
ALTER TABLE `lupo_anubis_recovery_attempts` MODIFY COLUMN `success` tinyint DEFAULT 0;

-- Fix type mismatch in lupo_anubis_redirects.anubis_redirect_id
ALTER TABLE `lupo_anubis_redirects` MODIFY COLUMN `anubis_redirect_id` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_redirects.table_name
ALTER TABLE `lupo_anubis_redirects` MODIFY COLUMN `table_name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_anubis_redirects.old_id
ALTER TABLE `lupo_anubis_redirects` MODIFY COLUMN `old_id` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_redirects.new_id
ALTER TABLE `lupo_anubis_redirects` MODIFY COLUMN `new_id` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_redirects.created_ymdhis
ALTER TABLE `lupo_anubis_redirects` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_redirects.updated_ymdhis
ALTER TABLE `lupo_anubis_redirects` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_anubis_redirects.agent
ALTER TABLE `lupo_anubis_redirects` MODIFY COLUMN `agent` varchar(255) NOT NULL;

-- Fix type mismatch in lupo_api_clients.api_client_id
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `api_client_id` bigint NOT NULL;
-- Fix type mismatch in lupo_api_clients.actor_id
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `actor_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_api_clients.client_key
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `client_key` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_api_clients.client_secret
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `client_secret` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_api_clients.client_name
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `client_name` varchar(150) NOT NULL;
-- Fix type mismatch in lupo_api_clients.is_active
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_api_clients.created_ymdhis
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_api_clients.updated_ymdhis
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_api_rate_limits.api_rate_limit_id
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `api_rate_limit_id` bigint NOT NULL;
-- Fix type mismatch in lupo_api_rate_limits.domain_id
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `domain_id` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_api_rate_limits.api_token_id
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `api_token_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_api_rate_limits.actor_id
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `actor_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_api_rate_limits.window_ymdhis
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `window_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_api_rate_limits.request_count
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `request_count` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_api_rate_limits.limit_value
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `limit_value` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_api_rate_limits.created_ymdhis
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_api_rate_limits.updated_ymdhis
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_api_tokens.api_token_id
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `api_token_id` bigint NOT NULL;
-- Fix type mismatch in lupo_api_tokens.domain_id
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `domain_id` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_api_tokens.actor_id
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `actor_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_api_tokens.token_key
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `token_key` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_api_tokens.is_active
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_api_tokens.created_ymdhis
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_api_tokens.updated_ymdhis
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_api_tokens.is_deleted
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_api_token_logs.api_token_log_id
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `api_token_log_id` bigint NOT NULL;
-- Fix type mismatch in lupo_api_token_logs.domain_id
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `domain_id` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_api_token_logs.api_token_id
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `api_token_id` bigint NOT NULL;
-- Fix type mismatch in lupo_api_token_logs.actor_id
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `actor_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_api_token_logs.endpoint
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `endpoint` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_api_token_logs.http_method
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `http_method` varchar(10) NOT NULL;
-- Fix type mismatch in lupo_api_token_logs.status_code
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `status_code` int NOT NULL;
-- Fix type mismatch in lupo_api_token_logs.request_ymdhis
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `request_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_api_webhooks.api_webhook_id
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `api_webhook_id` bigint NOT NULL;
-- Fix type mismatch in lupo_api_webhooks.domain_id
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `domain_id` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_api_webhooks.actor_id
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `actor_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_api_webhooks.module_id
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `module_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_api_webhooks.endpoint_url
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `endpoint_url` varchar(500) NOT NULL;
-- Fix type mismatch in lupo_api_webhooks.secret_key
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `secret_key` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_api_webhooks.event_types
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `event_types` text NOT NULL;
-- Fix type mismatch in lupo_api_webhooks.is_active
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_api_webhooks.max_retries
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `max_retries` int NOT NULL DEFAULT 5;
-- Fix type mismatch in lupo_api_webhooks.created_ymdhis
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_api_webhooks.updated_ymdhis
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_atoms.atom_id
ALTER TABLE `lupo_atoms` MODIFY COLUMN `atom_id` bigint NOT NULL;
-- Fix type mismatch in lupo_atoms.atom_name
ALTER TABLE `lupo_atoms` MODIFY COLUMN `atom_name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_atoms.context_id
ALTER TABLE `lupo_atoms` MODIFY COLUMN `context_id` bigint NOT NULL;
-- Fix type mismatch in lupo_atoms.is_authoritative
ALTER TABLE `lupo_atoms` MODIFY COLUMN `is_authoritative` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_atoms.created_ymd
ALTER TABLE `lupo_atoms` MODIFY COLUMN `created_ymd` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_atoms.updated_ymd
ALTER TABLE `lupo_atoms` MODIFY COLUMN `updated_ymd` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_audit_log.audit_log_id
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `audit_log_id` bigint NOT NULL;
-- Fix type mismatch in lupo_audit_log.channel_id
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_audit_log.entity_type
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `entity_type` varchar(32) NOT NULL;
-- Fix type mismatch in lupo_audit_log.entity_id
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `entity_id` bigint NOT NULL;
-- Fix type mismatch in lupo_audit_log.event_type
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `event_type` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_audit_log.created_ymdhis
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_audit_log.updated_ymdhis
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_audit_log.is_deleted
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_auth_audit_log.auth_audit_log_id
ALTER TABLE `lupo_auth_audit_log` MODIFY COLUMN `auth_audit_log_id` bigint NOT NULL;
-- Fix type mismatch in lupo_auth_audit_log.event_type
ALTER TABLE `lupo_auth_audit_log` MODIFY COLUMN `event_type` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_auth_audit_log.system_context
ALTER TABLE `lupo_auth_audit_log` MODIFY COLUMN `system_context` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_auth_audit_log.success
ALTER TABLE `lupo_auth_audit_log` MODIFY COLUMN `success` tinyint NOT NULL DEFAULT 1;

-- Fix type mismatch in lupo_auth_providers.auth_provider_id
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `auth_provider_id` bigint NOT NULL;
-- Fix type mismatch in lupo_auth_providers.provider_name
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `provider_name` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_auth_providers.client_id
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `client_id` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_auth_providers.client_secret
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `client_secret` text NOT NULL;
-- Fix type mismatch in lupo_auth_providers.authorization_endpoint
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `authorization_endpoint` varchar(2000) NOT NULL;
-- Fix type mismatch in lupo_auth_providers.token_endpoint
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `token_endpoint` varchar(2000) NOT NULL;
-- Fix type mismatch in lupo_auth_providers.created_ymdhis
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_auth_providers.updated_ymdhis
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_auth_providers.is_active
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;

-- Fix type mismatch in lupo_auth_users.auth_user_id
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `auth_user_id` bigint NOT NULL;
-- Fix type mismatch in lupo_auth_users.username
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `username` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_auth_users.display_name
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `display_name` varchar(42) NOT NULL;
-- Fix type mismatch in lupo_auth_users.created_ymdhis
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_auth_users.updated_ymdhis
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_auth_users.is_active
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_auth_users.is_deleted
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_banned_actors.banned_actor_id
ALTER TABLE `lupo_banned_actors` MODIFY COLUMN `banned_actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_banned_actors.actor_id
ALTER TABLE `lupo_banned_actors` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_banned_actors.reason
ALTER TABLE `lupo_banned_actors` MODIFY COLUMN `reason` varchar(500) NOT NULL;
-- Fix type mismatch in lupo_banned_actors.banned_ymdhis
ALTER TABLE `lupo_banned_actors` MODIFY COLUMN `banned_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_banned_actors.created_ymdhis
ALTER TABLE `lupo_banned_actors` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_banned_actors.updated_ymdhis
ALTER TABLE `lupo_banned_actors` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_banned_actors.is_deleted
ALTER TABLE `lupo_banned_actors` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_bans_log.bans_log_id
ALTER TABLE `lupo_bans_log` MODIFY COLUMN `bans_log_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_bans_log.actor_id
ALTER TABLE `lupo_bans_log` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_bans_log.uri
ALTER TABLE `lupo_bans_log` MODIFY COLUMN `uri` varchar(1024) NOT NULL DEFAULT '';
-- Fix type mismatch in lupo_bans_log.resolved_uri
ALTER TABLE `lupo_bans_log` MODIFY COLUMN `resolved_uri` varchar(1024) NOT NULL DEFAULT '';
-- Fix type mismatch in lupo_bans_log.ban_scope
ALTER TABLE `lupo_bans_log` MODIFY COLUMN `ban_scope` varchar(64) NOT NULL DEFAULT 'router';
-- Fix type mismatch in lupo_bans_log.banned_ymdhis
ALTER TABLE `lupo_bans_log` MODIFY COLUMN `banned_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_capability_usage.usage_id
ALTER TABLE `lupo_capability_usage` MODIFY COLUMN `usage_id` bigint NOT NULL;
-- Fix type mismatch in lupo_capability_usage.actor_id
ALTER TABLE `lupo_capability_usage` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_capability_usage.capability
ALTER TABLE `lupo_capability_usage` MODIFY COLUMN `capability` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_capability_usage.usage_count
ALTER TABLE `lupo_capability_usage` MODIFY COLUMN `usage_count` bigint DEFAULT 0;
-- Fix type mismatch in lupo_capability_usage.success_rate
ALTER TABLE `lupo_capability_usage` MODIFY COLUMN `success_rate` float DEFAULT 1;
-- Fix type mismatch in lupo_capability_usage.avg_response_time_ms
ALTER TABLE `lupo_capability_usage` MODIFY COLUMN `avg_response_time_ms` int DEFAULT 0;
-- Fix type mismatch in lupo_capability_usage.last_used_ymdhis
ALTER TABLE `lupo_capability_usage` MODIFY COLUMN `last_used_ymdhis` bigint DEFAULT 0;
-- Fix type mismatch in lupo_capability_usage.created_ymdhis
ALTER TABLE `lupo_capability_usage` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_capability_usage.is_deleted
ALTER TABLE `lupo_capability_usage` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_channels.channel_id
ALTER TABLE `lupo_channels` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channels.federation_node_id
ALTER TABLE `lupo_channels` MODIFY COLUMN `federation_node_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channels.created_by_actor_id
ALTER TABLE `lupo_channels` MODIFY COLUMN `created_by_actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channels.default_actor_id
ALTER TABLE `lupo_channels` MODIFY COLUMN `default_actor_id` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_channels.department_id
ALTER TABLE `lupo_channels` MODIFY COLUMN `department_id` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_channels.channel_key
ALTER TABLE `lupo_channels` MODIFY COLUMN `channel_key` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_channels.channel_slug
ALTER TABLE `lupo_channels` MODIFY COLUMN `channel_slug` varchar(32) NOT NULL DEFAULT 'channel_key';
-- Fix type mismatch in lupo_channels.channel_type
ALTER TABLE `lupo_channels` MODIFY COLUMN `channel_type` varchar(32) NOT NULL DEFAULT 'chat_room';
-- Fix type mismatch in lupo_channels.language
ALTER TABLE `lupo_channels` MODIFY COLUMN `language` varchar(16) NOT NULL DEFAULT 'en';
-- Fix type mismatch in lupo_channels.channel_name
ALTER TABLE `lupo_channels` MODIFY COLUMN `channel_name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_channels.status_flag
ALTER TABLE `lupo_channels` MODIFY COLUMN `status_flag` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_channels.created_ymdhis
ALTER TABLE `lupo_channels` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channels.updated_ymdhis
ALTER TABLE `lupo_channels` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_channels.is_deleted
ALTER TABLE `lupo_channels` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channels.awareness_version
ALTER TABLE `lupo_channels` MODIFY COLUMN `awareness_version` varchar(20) DEFAULT '3.0.0';
-- Fix type mismatch in lupo_channels.is_kernel
ALTER TABLE `lupo_channels` MODIFY COLUMN `is_kernel` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channels.visibility_status
ALTER TABLE `lupo_channels` MODIFY COLUMN `visibility_status` varchar(32) NOT NULL DEFAULT 'active';
-- Fix type mismatch in lupo_channels.owner_actor_id
ALTER TABLE `lupo_channels` MODIFY COLUMN `owner_actor_id` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_channels.access_level
ALTER TABLE `lupo_channels` MODIFY COLUMN `access_level` varchar(32) NOT NULL DEFAULT 'public';
-- Fix type mismatch in lupo_channels.last_activity_ymdhis
ALTER TABLE `lupo_channels` MODIFY COLUMN `last_activity_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_channel_boot_detail.detail_id
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `detail_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_boot_detail.boot_id
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `boot_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_boot_detail.channel_id
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_boot_detail.load_status
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `load_status` varchar(64) NOT NULL DEFAULT 'started';
-- Fix type mismatch in lupo_channel_boot_detail.content_items_loaded
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `content_items_loaded` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channel_boot_detail.total_content_items
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `total_content_items` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channel_boot_detail.created_ymdhis
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_channel_boot_detail_lifecycle.detail_lifecycle_id
ALTER TABLE `lupo_channel_boot_detail_lifecycle` MODIFY COLUMN `detail_lifecycle_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_channel_boot_detail_lifecycle.lifecycle_id
ALTER TABLE `lupo_channel_boot_detail_lifecycle` MODIFY COLUMN `lifecycle_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_boot_detail_lifecycle.channel_id
ALTER TABLE `lupo_channel_boot_detail_lifecycle` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_boot_detail_lifecycle.detail_start_time
ALTER TABLE `lupo_channel_boot_detail_lifecycle` MODIFY COLUMN `detail_start_time` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_boot_detail_lifecycle.detail_status
ALTER TABLE `lupo_channel_boot_detail_lifecycle` MODIFY COLUMN `detail_status` varchar(64) NOT NULL DEFAULT 'started';
-- Fix type mismatch in lupo_channel_boot_detail_lifecycle.content_items_loaded
ALTER TABLE `lupo_channel_boot_detail_lifecycle` MODIFY COLUMN `content_items_loaded` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channel_boot_detail_lifecycle.total_content_items
ALTER TABLE `lupo_channel_boot_detail_lifecycle` MODIFY COLUMN `total_content_items` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channel_boot_detail_lifecycle.created_ymdhis
ALTER TABLE `lupo_channel_boot_detail_lifecycle` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_channel_boot_lifecycle.lifecycle_id
ALTER TABLE `lupo_channel_boot_lifecycle` MODIFY COLUMN `lifecycle_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_channel_boot_lifecycle.channel_id
ALTER TABLE `lupo_channel_boot_lifecycle` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_boot_lifecycle.actor_id
ALTER TABLE `lupo_channel_boot_lifecycle` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_boot_lifecycle.session_id
ALTER TABLE `lupo_channel_boot_lifecycle` MODIFY COLUMN `session_id` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_channel_boot_lifecycle.lifecycle_start_time
ALTER TABLE `lupo_channel_boot_lifecycle` MODIFY COLUMN `lifecycle_start_time` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_boot_lifecycle.lifecycle_status
ALTER TABLE `lupo_channel_boot_lifecycle` MODIFY COLUMN `lifecycle_status` varchar(64) NOT NULL DEFAULT 'started';
-- Fix type mismatch in lupo_channel_boot_lifecycle.lifecycle_type
ALTER TABLE `lupo_channel_boot_lifecycle` MODIFY COLUMN `lifecycle_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_channel_boot_lifecycle.total_channels
ALTER TABLE `lupo_channel_boot_lifecycle` MODIFY COLUMN `total_channels` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channel_boot_lifecycle.channels_processed
ALTER TABLE `lupo_channel_boot_lifecycle` MODIFY COLUMN `channels_processed` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channel_boot_lifecycle.channels_successful
ALTER TABLE `lupo_channel_boot_lifecycle` MODIFY COLUMN `channels_successful` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channel_boot_lifecycle.channels_failed
ALTER TABLE `lupo_channel_boot_lifecycle` MODIFY COLUMN `channels_failed` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channel_boot_lifecycle.created_ymdhis
ALTER TABLE `lupo_channel_boot_lifecycle` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_channel_content.channel_content_id
ALTER TABLE `lupo_channel_content` MODIFY COLUMN `channel_content_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_channel_content.channel_id
ALTER TABLE `lupo_channel_content` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_content.federation_node_id
ALTER TABLE `lupo_channel_content` MODIFY COLUMN `federation_node_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_content.file_path
ALTER TABLE `lupo_channel_content` MODIFY COLUMN `file_path` varchar(500) NOT NULL;
-- Fix type mismatch in lupo_channel_content.web_path
ALTER TABLE `lupo_channel_content` MODIFY COLUMN `web_path` varchar(500) NOT NULL;
-- Fix type mismatch in lupo_channel_content.created_ymdhis
ALTER TABLE `lupo_channel_content` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channel_content.updated_ymdhis
ALTER TABLE `lupo_channel_content` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channel_content.is_deleted
ALTER TABLE `lupo_channel_content` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_channel_departments.channel_department_id
ALTER TABLE `lupo_channel_departments` MODIFY COLUMN `channel_department_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_departments.channel_id
ALTER TABLE `lupo_channel_departments` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_departments.department_id
ALTER TABLE `lupo_channel_departments` MODIFY COLUMN `department_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_departments.created_ymdhis
ALTER TABLE `lupo_channel_departments` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_channel_escalations.escalation_id
ALTER TABLE `lupo_channel_escalations` MODIFY COLUMN `escalation_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_escalations.channel_id
ALTER TABLE `lupo_channel_escalations` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_escalations.created_ymdhis
ALTER TABLE `lupo_channel_escalations` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channel_escalations.updated_ymdhis
ALTER TABLE `lupo_channel_escalations` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_escalations.is_deleted
ALTER TABLE `lupo_channel_escalations` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_channel_escalation_rules.rule_id
ALTER TABLE `lupo_channel_escalation_rules` MODIFY COLUMN `rule_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_escalation_rules.channel_id
ALTER TABLE `lupo_channel_escalation_rules` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_escalation_rules.rule_name
ALTER TABLE `lupo_channel_escalation_rules` MODIFY COLUMN `rule_name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_channel_escalation_rules.rule_type
ALTER TABLE `lupo_channel_escalation_rules` MODIFY COLUMN `rule_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_channel_escalation_rules.created_ymdhis
ALTER TABLE `lupo_channel_escalation_rules` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channel_escalation_rules.updated_ymdhis
ALTER TABLE `lupo_channel_escalation_rules` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_escalation_rules.is_deleted
ALTER TABLE `lupo_channel_escalation_rules` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_channel_files.file_id
ALTER TABLE `lupo_channel_files` MODIFY COLUMN `file_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_files.channel_id
ALTER TABLE `lupo_channel_files` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_files.file_type
ALTER TABLE `lupo_channel_files` MODIFY COLUMN `file_type` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_channel_files.file_name
ALTER TABLE `lupo_channel_files` MODIFY COLUMN `file_name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_channel_files.file_path
ALTER TABLE `lupo_channel_files` MODIFY COLUMN `file_path` varchar(500) NOT NULL;
-- Fix type mismatch in lupo_channel_files.file_hash
ALTER TABLE `lupo_channel_files` MODIFY COLUMN `file_hash` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_channel_files.file_size
ALTER TABLE `lupo_channel_files` MODIFY COLUMN `file_size` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_files.upload_ymdhis
ALTER TABLE `lupo_channel_files` MODIFY COLUMN `upload_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_files.created_ymdhis
ALTER TABLE `lupo_channel_files` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channel_files.updated_ymdhis
ALTER TABLE `lupo_channel_files` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_files.is_deleted
ALTER TABLE `lupo_channel_files` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_channel_state.channel_state_id
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `channel_state_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_state.channel_id
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_channel_state.mood_framework
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `mood_framework` varchar(32) NOT NULL DEFAULT 'western_analytical';
-- Fix type mismatch in lupo_channel_state.semantic_weight
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `semantic_weight` float DEFAULT 0;
-- Fix type mismatch in lupo_channel_state.trend_score
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `trend_score` float DEFAULT 0;
-- Fix type mismatch in lupo_channel_state.archive_flag
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `archive_flag` tinyint DEFAULT 0;
-- Fix type mismatch in lupo_channel_state.created_ymdhis
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_channel_state.updated_ymdhis
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_collections.collection_id
ALTER TABLE `lupo_collections` MODIFY COLUMN `collection_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_collections.federation_node_id
ALTER TABLE `lupo_collections` MODIFY COLUMN `federation_node_id` bigint NOT NULL;
-- Fix type mismatch in lupo_collections.name
ALTER TABLE `lupo_collections` MODIFY COLUMN `name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_collections.slug
ALTER TABLE `lupo_collections` MODIFY COLUMN `slug` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_collections.color
ALTER TABLE `lupo_collections` MODIFY COLUMN `color` char(6) DEFAULT '666666';
-- Fix type mismatch in lupo_collections.sort_order
ALTER TABLE `lupo_collections` MODIFY COLUMN `sort_order` int DEFAULT 0;
-- Fix type mismatch in lupo_collections.created_ymdhis
ALTER TABLE `lupo_collections` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_collections.updated_ymdhis
ALTER TABLE `lupo_collections` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_collections.is_deleted
ALTER TABLE `lupo_collections` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_collections.is_nav_menu
ALTER TABLE `lupo_collections` MODIFY COLUMN `is_nav_menu` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_collection_links.collection_link_id
ALTER TABLE `lupo_collection_links` MODIFY COLUMN `collection_link_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_collection_links.collection_id
ALTER TABLE `lupo_collection_links` MODIFY COLUMN `collection_id` bigint NOT NULL;
-- Fix type mismatch in lupo_collection_links.link_url
ALTER TABLE `lupo_collection_links` MODIFY COLUMN `link_url` varchar(2000) NOT NULL;
-- Fix type mismatch in lupo_collection_links.sort_order
ALTER TABLE `lupo_collection_links` MODIFY COLUMN `sort_order` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_collection_links.created_ymdhis
ALTER TABLE `lupo_collection_links` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_collection_links.updated_ymdhis
ALTER TABLE `lupo_collection_links` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_collection_links.is_deleted
ALTER TABLE `lupo_collection_links` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_collection_map.collection_map_id
ALTER TABLE `lupo_collection_map` MODIFY COLUMN `collection_map_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_collection_map.collection_id
ALTER TABLE `lupo_collection_map` MODIFY COLUMN `collection_id` bigint NOT NULL;
-- Fix type mismatch in lupo_collection_map.object_type
ALTER TABLE `lupo_collection_map` MODIFY COLUMN `object_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_collection_map.object_id
ALTER TABLE `lupo_collection_map` MODIFY COLUMN `object_id` bigint NOT NULL;
-- Fix type mismatch in lupo_collection_map.sort_order
ALTER TABLE `lupo_collection_map` MODIFY COLUMN `sort_order` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_collection_map.created_ymdhis
ALTER TABLE `lupo_collection_map` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_collection_map.is_deleted
ALTER TABLE `lupo_collection_map` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_collection_tabs.collection_tab_id
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `collection_tab_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_collection_tabs.collection_id
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `collection_id` bigint NOT NULL;
-- Fix type mismatch in lupo_collection_tabs.federations_node_id
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `federations_node_id` bigint NOT NULL;
-- Fix type mismatch in lupo_collection_tabs.sort_order
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `sort_order` int DEFAULT 0;
-- Fix type mismatch in lupo_collection_tabs.name
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_collection_tabs.slug
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `slug` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_collection_tabs.color
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `color` char(6) DEFAULT '4caf50';
-- Fix type mismatch in lupo_collection_tabs.is_hidden
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `is_hidden` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_collection_tabs.created_ymdhis
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_collection_tabs.updated_ymdhis
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_collection_tabs.is_active
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_collection_tabs.is_deleted
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_collection_tab_map.collection_tab_map_id
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `collection_tab_map_id` bigint NOT NULL;
-- Fix type mismatch in lupo_collection_tab_map.collection_tab_id
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `collection_tab_id` bigint NOT NULL;
-- Fix type mismatch in lupo_collection_tab_map.federations_node_id
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `federations_node_id` bigint NOT NULL;
-- Fix type mismatch in lupo_collection_tab_map.item_type
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `item_type` varchar(20) NOT NULL;
-- Fix type mismatch in lupo_collection_tab_map.item_id
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `item_id` bigint NOT NULL;
-- Fix type mismatch in lupo_collection_tab_map.sort_order
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `sort_order` int DEFAULT 0;
-- Fix type mismatch in lupo_collection_tab_map.created_ymdhis
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_collection_tab_map.updated_ymdhis
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_collection_tab_map.is_deleted
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_collection_tab_paths.collection_tab_path_id
ALTER TABLE `lupo_collection_tab_paths` MODIFY COLUMN `collection_tab_path_id` bigint NOT NULL;
-- Fix type mismatch in lupo_collection_tab_paths.collection_id
ALTER TABLE `lupo_collection_tab_paths` MODIFY COLUMN `collection_id` bigint NOT NULL;
-- Fix type mismatch in lupo_collection_tab_paths.collection_tab_id
ALTER TABLE `lupo_collection_tab_paths` MODIFY COLUMN `collection_tab_id` bigint NOT NULL;
-- Fix type mismatch in lupo_collection_tab_paths.path
ALTER TABLE `lupo_collection_tab_paths` MODIFY COLUMN `path` varchar(500) NOT NULL;
-- Fix type mismatch in lupo_collection_tab_paths.depth
ALTER TABLE `lupo_collection_tab_paths` MODIFY COLUMN `depth` int NOT NULL;
-- Fix type mismatch in lupo_collection_tab_paths.created_ymdhis
ALTER TABLE `lupo_collection_tab_paths` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_collection_tab_paths.is_deleted
ALTER TABLE `lupo_collection_tab_paths` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_comments.comment_id
ALTER TABLE `lupo_comments` MODIFY COLUMN `comment_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_comments.target_type
ALTER TABLE `lupo_comments` MODIFY COLUMN `target_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_comments.target_id
ALTER TABLE `lupo_comments` MODIFY COLUMN `target_id` bigint NOT NULL;
-- Fix type mismatch in lupo_comments.channel_id
ALTER TABLE `lupo_comments` MODIFY COLUMN `channel_id` bigint NOT NULL DEFAULT 42;
-- Fix type mismatch in lupo_comments.actor_id
ALTER TABLE `lupo_comments` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_comments.comment_text
ALTER TABLE `lupo_comments` MODIFY COLUMN `comment_text` text NOT NULL;
-- Fix type mismatch in lupo_comments.comment_type
ALTER TABLE `lupo_comments` MODIFY COLUMN `comment_type` varchar(64) NOT NULL DEFAULT 'comment';
-- Fix type mismatch in lupo_comments.created_ymdhis
ALTER TABLE `lupo_comments` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_comments.updated_ymdhis
ALTER TABLE `lupo_comments` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_comments.is_deleted
ALTER TABLE `lupo_comments` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_contents.content_id
ALTER TABLE `lupo_contents` MODIFY COLUMN `content_id` bigint NOT NULL;
-- Fix type mismatch in lupo_contents.federation_node_id
ALTER TABLE `lupo_contents` MODIFY COLUMN `federation_node_id` bigint DEFAULT 1;
-- Fix type mismatch in lupo_contents.federation_source_url
ALTER TABLE `lupo_contents` MODIFY COLUMN `federation_source_url` varchar(2000) COMMENT 'Canonical URL of content at source federation node';
-- Fix type mismatch in lupo_contents.channel_id
ALTER TABLE `lupo_contents` MODIFY COLUMN `channel_id` bigint COMMENT 'Channel this content belongs to (doctrine: content placement)';
-- Fix type mismatch in lupo_contents.title
ALTER TABLE `lupo_contents` MODIFY COLUMN `title` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_contents.slug
ALTER TABLE `lupo_contents` MODIFY COLUMN `slug` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_contents.content_type
ALTER TABLE `lupo_contents` MODIFY COLUMN `content_type` varchar(50) DEFAULT 'article';
-- Fix type mismatch in lupo_contents.format
ALTER TABLE `lupo_contents` MODIFY COLUMN `format` varchar(20) DEFAULT 'markdown';
-- Fix type mismatch in lupo_contents.is_template
ALTER TABLE `lupo_contents` MODIFY COLUMN `is_template` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_contents.status
ALTER TABLE `lupo_contents` MODIFY COLUMN `status` varchar(64) DEFAULT 'draft';
-- Fix type mismatch in lupo_contents.visibility
ALTER TABLE `lupo_contents` MODIFY COLUMN `visibility` varchar(64) DEFAULT 'public';
-- Fix type mismatch in lupo_contents.view_count
ALTER TABLE `lupo_contents` MODIFY COLUMN `view_count` int DEFAULT 0;
-- Fix type mismatch in lupo_contents.created_ymdhis
ALTER TABLE `lupo_contents` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_contents.utc_cycle
ALTER TABLE `lupo_contents` MODIFY COLUMN `utc_cycle` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_contents.triage_status
ALTER TABLE `lupo_contents` MODIFY COLUMN `triage_status` varchar(64) NOT NULL DEFAULT 'untriaged';
-- Fix type mismatch in lupo_contents.updated_ymdhis
ALTER TABLE `lupo_contents` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_contents.is_deleted
ALTER TABLE `lupo_contents` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_contents.is_active
ALTER TABLE `lupo_contents` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_contents.version_number
ALTER TABLE `lupo_contents` MODIFY COLUMN `version_number` int NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_contents.file_path_from_root
ALTER TABLE `lupo_contents` MODIFY COLUMN `file_path_from_root` varchar(500) COMMENT 'FLIP Header: path from repo root (4.0.86)';
-- Fix type mismatch in lupo_contents.file_last_modified_system_version
ALTER TABLE `lupo_contents` MODIFY COLUMN `file_last_modified_system_version` varchar(20) COMMENT 'FLIP: system version at last file edit';
-- Fix type mismatch in lupo_contents.file_last_modified_utc
ALTER TABLE `lupo_contents` MODIFY COLUMN `file_last_modified_utc` bigint COMMENT 'FLIP: UTC last modified YYYYMMDDHHIISS';
-- Fix type mismatch in lupo_contents.atom_mappings
ALTER TABLE `lupo_contents` MODIFY COLUMN `atom_mappings` json COMMENT 'Consolidated from lupo_content_atom_map';
-- Fix type mismatch in lupo_contents.category_mappings
ALTER TABLE `lupo_contents` MODIFY COLUMN `category_mappings` json COMMENT 'Consolidated from lupo_content_category_map';
-- Fix type mismatch in lupo_contents.content_events
ALTER TABLE `lupo_contents` MODIFY COLUMN `content_events` json COMMENT 'Consolidated from lupo_content_events';
-- Fix type mismatch in lupo_contents.hashtags
ALTER TABLE `lupo_contents` MODIFY COLUMN `hashtags` json COMMENT 'Consolidated from lupo_content_hashtag';
-- Fix type mismatch in lupo_contents.inbound_links
ALTER TABLE `lupo_contents` MODIFY COLUMN `inbound_links` json COMMENT 'Consolidated from lupo_content_inbound_links';
-- Fix type mismatch in lupo_contents.like_users
ALTER TABLE `lupo_contents` MODIFY COLUMN `like_users` json COMMENT 'Consolidated from lupo_content_likes';
-- Fix type mismatch in lupo_contents.media_attachments
ALTER TABLE `lupo_contents` MODIFY COLUMN `media_attachments` json COMMENT 'Consolidated from lupo_content_media';
-- Fix type mismatch in lupo_contents.question_mappings
ALTER TABLE `lupo_contents` MODIFY COLUMN `question_mappings` json COMMENT 'Consolidated from lupo_content_question_map';
-- Fix type mismatch in lupo_contents.content_references
ALTER TABLE `lupo_contents` MODIFY COLUMN `content_references` json COMMENT 'Consolidated from lupo_content_references';
-- Fix type mismatch in lupo_contents.revision_history
ALTER TABLE `lupo_contents` MODIFY COLUMN `revision_history` json COMMENT 'Consolidated from lupo_content_revisions';
-- Fix type mismatch in lupo_contents.share_users
ALTER TABLE `lupo_contents` MODIFY COLUMN `share_users` json COMMENT 'Consolidated from lupo_content_shares';
-- Fix type mismatch in lupo_contents.tag_relationships
ALTER TABLE `lupo_contents` MODIFY COLUMN `tag_relationships` json COMMENT 'Consolidated from lupo_content_tag_relationships';
-- Fix type mismatch in lupo_contents.like_count
ALTER TABLE `lupo_contents` MODIFY COLUMN `like_count` bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache';
-- Fix type mismatch in lupo_contents.share_count
ALTER TABLE `lupo_contents` MODIFY COLUMN `share_count` bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache';
-- Fix type mismatch in lupo_contents.comment_count
ALTER TABLE `lupo_contents` MODIFY COLUMN `comment_count` bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache';

-- Fix type mismatch in lupo_contexts.context_id
ALTER TABLE `lupo_contexts` MODIFY COLUMN `context_id` int NOT NULL;
-- Fix type mismatch in lupo_contexts.context_code
ALTER TABLE `lupo_contexts` MODIFY COLUMN `context_code` varchar(16) NOT NULL;
-- Fix type mismatch in lupo_contexts.context_name
ALTER TABLE `lupo_contexts` MODIFY COLUMN `context_name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_contexts.is_system
ALTER TABLE `lupo_contexts` MODIFY COLUMN `is_system` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_contexts.is_fiction
ALTER TABLE `lupo_contexts` MODIFY COLUMN `is_fiction` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_contexts.is_installation_local
ALTER TABLE `lupo_contexts` MODIFY COLUMN `is_installation_local` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_contexts.sort_order
ALTER TABLE `lupo_contexts` MODIFY COLUMN `sort_order` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_contexts.created_ymdhis
ALTER TABLE `lupo_contexts` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_contexts.updated_ymdhis
ALTER TABLE `lupo_contexts` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_contexts.weight_score
ALTER TABLE `lupo_contexts` MODIFY COLUMN `weight_score` decimal(5,2) NOT NULL DEFAULT 0.00;
-- Fix type mismatch in lupo_contexts.is_active
ALTER TABLE `lupo_contexts` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;

-- Fix type mismatch in lupo_contexts_map.contexts_map_id
ALTER TABLE `lupo_contexts_map` MODIFY COLUMN `contexts_map_id` bigint NOT NULL;
-- Fix type mismatch in lupo_contexts_map.context_id
ALTER TABLE `lupo_contexts_map` MODIFY COLUMN `context_id` bigint NOT NULL;
-- Fix type mismatch in lupo_contexts_map.item_type
ALTER TABLE `lupo_contexts_map` MODIFY COLUMN `item_type` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_contexts_map.item_slug
ALTER TABLE `lupo_contexts_map` MODIFY COLUMN `item_slug` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_contexts_map.is_deleted
ALTER TABLE `lupo_contexts_map` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_contexts_map.deleted_ymdhis
ALTER TABLE `lupo_contexts_map` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_contexts_map.created_ymdhis
ALTER TABLE `lupo_contexts_map` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_contexts_map.updated_ymdhis
ALTER TABLE `lupo_contexts_map` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_context_edges.edge_id
ALTER TABLE `lupo_context_edges` MODIFY COLUMN `edge_id` bigint NOT NULL;
-- Fix type mismatch in lupo_context_edges.source_type
ALTER TABLE `lupo_context_edges` MODIFY COLUMN `source_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_context_edges.source_id
ALTER TABLE `lupo_context_edges` MODIFY COLUMN `source_id` bigint NOT NULL;
-- Fix type mismatch in lupo_context_edges.target_type
ALTER TABLE `lupo_context_edges` MODIFY COLUMN `target_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_context_edges.target_id
ALTER TABLE `lupo_context_edges` MODIFY COLUMN `target_id` bigint NOT NULL;
-- Fix type mismatch in lupo_context_edges.edge_type
ALTER TABLE `lupo_context_edges` MODIFY COLUMN `edge_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_context_edges.created_ymdhis
ALTER TABLE `lupo_context_edges` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_context_edges.updated_ymdhis
ALTER TABLE `lupo_context_edges` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_context_edges.is_deleted
ALTER TABLE `lupo_context_edges` MODIFY COLUMN `is_deleted` tinyint DEFAULT 0;
-- Fix type mismatch in lupo_context_edges.deleted_ymdhis
ALTER TABLE `lupo_context_edges` MODIFY COLUMN `deleted_ymdhis` bigint DEFAULT 0;

-- Fix type mismatch in lupo_crafty_syntax_auto_invite.crafty_syntax_auto_invite_id
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `crafty_syntax_auto_invite_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_crafty_syntax_auto_invite.is_offline
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `is_offline` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_auto_invite.is_active
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_auto_invite.department_id
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `department_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_auto_invite.visits
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `visits` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_auto_invite.trigger_seconds
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `trigger_seconds` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_auto_invite.operator_user_id
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `operator_user_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_auto_invite.show_socialpane
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `show_socialpane` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_auto_invite.exclude_mobile
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `exclude_mobile` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_auto_invite.only_mobile
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `only_mobile` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_auto_invite.created_ymdhis
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 20250101000000;
-- Fix type mismatch in lupo_crafty_syntax_auto_invite.updated_ymdhis
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 20250101000000;
-- Fix type mismatch in lupo_crafty_syntax_auto_invite.is_deleted
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_crafty_syntax_chat_mod_departments.crafty_syntax_chat_mod_department_id
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` MODIFY COLUMN `crafty_syntax_chat_mod_department_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_crafty_syntax_chat_mod_departments.department_id
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` MODIFY COLUMN `department_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_chat_mod_departments.module_id
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` MODIFY COLUMN `module_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_chat_mod_departments.sort_order
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` MODIFY COLUMN `sort_order` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_chat_mod_departments.is_active
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_crafty_syntax_chat_mod_departments.is_default
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` MODIFY COLUMN `is_default` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_crafty_syntax_chat_questions.crafty_syntax_chat_question_id
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `crafty_syntax_chat_question_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_crafty_syntax_chat_questions.department_id
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `department_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_chat_questions.sort_order
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `sort_order` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_chat_questions.is_required
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `is_required` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_chat_questions.created_ymdhis
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_chat_questions.updated_ymdhis
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_crafty_syntax_chat_questions.is_deleted
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_crafty_syntax_layer_invites.crafty_syntax_layer_invite_id
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `crafty_syntax_layer_invite_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_crafty_syntax_layer_invites.layer_name
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `layer_name` varchar(100) NOT NULL DEFAULT '';
-- Fix type mismatch in lupo_crafty_syntax_layer_invites.image_name
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `image_name` varchar(255) NOT NULL DEFAULT '';
-- Fix type mismatch in lupo_crafty_syntax_layer_invites.department_name
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `department_name` varchar(100) NOT NULL DEFAULT '';
-- Fix type mismatch in lupo_crafty_syntax_layer_invites.user_id
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `user_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_layer_invites.is_active
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_crafty_syntax_layer_invites.display_count
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `display_count` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_layer_invites.click_count
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `click_count` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_layer_invites.created_ymdhis
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_layer_invites.updated_ymdhis
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_crafty_syntax_layer_invites.is_deleted
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_crafty_syntax_leave_message.crafty_syntax_leave_message_id
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `crafty_syntax_leave_message_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_crafty_syntax_leave_message.department_id
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `department_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_leave_message.email
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `email` varchar(255) NOT NULL DEFAULT '';
-- Fix type mismatch in lupo_crafty_syntax_leave_message.subject
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `subject` varchar(255) NOT NULL DEFAULT '';
-- Fix type mismatch in lupo_crafty_syntax_leave_message.priority
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `priority` tinyint NOT NULL DEFAULT 2;
-- Fix type mismatch in lupo_crafty_syntax_leave_message.status
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `status` enum('new','in_progress','resolved','spam') NOT NULL DEFAULT 'new';
-- Fix type mismatch in lupo_crafty_syntax_leave_message.created_ymdhis
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_syntax_leave_message.updated_ymdhis
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_crafty_syntax_leave_message.is_deleted
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_crafty_user_mapping.crafty_user_mapping_id
ALTER TABLE `lupo_crafty_user_mapping` MODIFY COLUMN `crafty_user_mapping_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_crafty_user_mapping.mapping_type
ALTER TABLE `lupo_crafty_user_mapping` MODIFY COLUMN `mapping_type` varchar(50) NOT NULL DEFAULT 'manual';
-- Fix type mismatch in lupo_crafty_user_mapping.created_at
ALTER TABLE `lupo_crafty_user_mapping` MODIFY COLUMN `created_at` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crafty_user_mapping.updated_at
ALTER TABLE `lupo_crafty_user_mapping` MODIFY COLUMN `updated_at` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_crm_leads.crm_lead_id
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `crm_lead_id` bigint NOT NULL;
-- Fix type mismatch in lupo_crm_leads.status
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `status` varchar(50) NOT NULL DEFAULT 'new';
-- Fix type mismatch in lupo_crm_leads.lead_score
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `lead_score` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crm_leads.created_ymdhis
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crm_leads.updated_ymdhis
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_crm_leads.is_deleted
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_crm_lead_messages.crm_lead_message_id
ALTER TABLE `lupo_crm_lead_messages` MODIFY COLUMN `crm_lead_message_id` bigint NOT NULL;
-- Fix type mismatch in lupo_crm_lead_messages.body_text
ALTER TABLE `lupo_crm_lead_messages` MODIFY COLUMN `body_text` text NOT NULL;
-- Fix type mismatch in lupo_crm_lead_messages.created_ymdhis
ALTER TABLE `lupo_crm_lead_messages` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_crm_lead_messages.updated_ymdhis
ALTER TABLE `lupo_crm_lead_messages` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_crm_lead_messages.is_deleted
ALTER TABLE `lupo_crm_lead_messages` MODIFY COLUMN `is_deleted` smallint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_departments.department_id
ALTER TABLE `lupo_departments` MODIFY COLUMN `department_id` bigint NOT NULL;
-- Fix type mismatch in lupo_departments.federation_node_id
ALTER TABLE `lupo_departments` MODIFY COLUMN `federation_node_id` bigint NOT NULL;
-- Fix type mismatch in lupo_departments.name
ALTER TABLE `lupo_departments` MODIFY COLUMN `name` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_departments.department_type
ALTER TABLE `lupo_departments` MODIFY COLUMN `department_type` varchar(32) NOT NULL DEFAULT 'general';
-- Fix type mismatch in lupo_departments.default_actor_id
ALTER TABLE `lupo_departments` MODIFY COLUMN `default_actor_id` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_departments.created_ymdhis
ALTER TABLE `lupo_departments` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_departments.updated_ymdhis
ALTER TABLE `lupo_departments` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_departments.is_deleted
ALTER TABLE `lupo_departments` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_department_metadata.department_metadata_id
ALTER TABLE `lupo_department_metadata` MODIFY COLUMN `department_metadata_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_department_metadata.department_id
ALTER TABLE `lupo_department_metadata` MODIFY COLUMN `department_id` bigint NOT NULL;
-- Fix type mismatch in lupo_department_metadata.metadata_json
ALTER TABLE `lupo_department_metadata` MODIFY COLUMN `metadata_json` json NOT NULL;
-- Fix type mismatch in lupo_department_metadata.created_ymdhis
ALTER TABLE `lupo_department_metadata` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_department_metadata.updated_ymdhis
ALTER TABLE `lupo_department_metadata` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_department_metadata.is_active
ALTER TABLE `lupo_department_metadata` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_department_metadata.is_deleted
ALTER TABLE `lupo_department_metadata` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_department_roles.department_role_id
ALTER TABLE `lupo_department_roles` MODIFY COLUMN `department_role_id` bigint NOT NULL;
-- Fix type mismatch in lupo_department_roles.actor_id
ALTER TABLE `lupo_department_roles` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_department_roles.department_id
ALTER TABLE `lupo_department_roles` MODIFY COLUMN `department_id` bigint NOT NULL;
-- Fix type mismatch in lupo_department_roles.role_key
ALTER TABLE `lupo_department_roles` MODIFY COLUMN `role_key` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_department_roles.created_ymdhis
ALTER TABLE `lupo_department_roles` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_department_roles.updated_ymdhis
ALTER TABLE `lupo_department_roles` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_department_roles.is_deleted
ALTER TABLE `lupo_department_roles` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_dialog_channels.channel_id
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_dialog_channels.channel_name
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `channel_name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_dialog_channels.file_source
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `file_source` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_dialog_channels.status
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `status` varchar(64) DEFAULT 'published';
-- Fix type mismatch in lupo_dialog_channels.created_ymdhis
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_dialog_channels.updated_ymdhis
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_dialog_channels.message_count
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `message_count` int DEFAULT 0;

-- Fix type mismatch in lupo_dialog_messages.dialog_message_id
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `dialog_message_id` bigint NOT NULL;
-- Fix type mismatch in lupo_dialog_messages.message_id
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `message_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_dialog_messages.read_by_actor_id
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `read_by_actor_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_dialog_messages.read_by_actor_utc
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `read_by_actor_utc` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_dialog_messages.message_text
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `message_text` varchar(1000) NOT NULL;
-- Fix type mismatch in lupo_dialog_messages.message_type
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `message_type` varchar(64) NOT NULL DEFAULT 'text';
-- Fix type mismatch in lupo_dialog_messages.mood_framework
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `mood_framework` varchar(32) NOT NULL DEFAULT 'western_analytical';
-- Fix type mismatch in lupo_dialog_messages.created_ymdhis
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_dialog_messages.updated_ymdhis
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_dialog_messages.is_deleted
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_dialog_threads.dialog_thread_id
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `dialog_thread_id` bigint NOT NULL;
-- Fix type mismatch in lupo_dialog_threads.title
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `title` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_dialog_threads.federation_node_id
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `federation_node_id` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_dialog_threads.created_by_actor_id
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `created_by_actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_dialog_threads.bg_color
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `bg_color` char(6) NOT NULL DEFAULT 'FFFFFF';
-- Fix type mismatch in lupo_dialog_threads.text_color
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `text_color` char(6) NOT NULL DEFAULT '000000';
-- Fix type mismatch in lupo_dialog_threads.alt_text_color
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `alt_text_color` char(6) NOT NULL DEFAULT '666666';
-- Fix type mismatch in lupo_dialog_threads.status
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `status` varchar(64) NOT NULL DEFAULT 'Open';
-- Fix type mismatch in lupo_dialog_threads.created_ymdhis
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_dialog_threads.updated_ymdhis
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_dialog_threads.is_deleted
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_dialog_threads.visibility_status
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `visibility_status` varchar(32) NOT NULL DEFAULT 'active';
-- Fix type mismatch in lupo_dialog_threads.owner_actor_id
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `owner_actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_dialog_threads.thread_type
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `thread_type` varchar(32) NOT NULL DEFAULT 'discussion';
-- Fix type mismatch in lupo_dialog_threads.thread_priority
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `thread_priority` varchar(32) NOT NULL DEFAULT 'normal';

-- Fix type mismatch in lupo_doctrine_evolution_audit.doctrine_evolution_audit_id
ALTER TABLE `lupo_doctrine_evolution_audit` MODIFY COLUMN `doctrine_evolution_audit_id` bigint NOT NULL;
-- Fix type mismatch in lupo_doctrine_evolution_audit.refinement_id
ALTER TABLE `lupo_doctrine_evolution_audit` MODIFY COLUMN `refinement_id` bigint NOT NULL;
-- Fix type mismatch in lupo_doctrine_evolution_audit.evolution_step
ALTER TABLE `lupo_doctrine_evolution_audit` MODIFY COLUMN `evolution_step` tinyint NOT NULL;
-- Fix type mismatch in lupo_doctrine_evolution_audit.step_description
ALTER TABLE `lupo_doctrine_evolution_audit` MODIFY COLUMN `step_description` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_doctrine_evolution_audit.step_status
ALTER TABLE `lupo_doctrine_evolution_audit` MODIFY COLUMN `step_status` varchar(64) DEFAULT 'pending';
-- Fix type mismatch in lupo_doctrine_evolution_audit.audit_version
ALTER TABLE `lupo_doctrine_evolution_audit` MODIFY COLUMN `audit_version` varchar(20) DEFAULT '3.0.0';

-- Fix type mismatch in lupo_documentation_frameworks.documentation_framework_id
ALTER TABLE `lupo_documentation_frameworks` MODIFY COLUMN `documentation_framework_id` bigint NOT NULL;
-- Fix type mismatch in lupo_documentation_frameworks.framework_key
ALTER TABLE `lupo_documentation_frameworks` MODIFY COLUMN `framework_key` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_documentation_frameworks.framework_name
ALTER TABLE `lupo_documentation_frameworks` MODIFY COLUMN `framework_name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_documentation_frameworks.class_type
ALTER TABLE `lupo_documentation_frameworks` MODIFY COLUMN `class_type` varchar(64) NOT NULL DEFAULT 'documentation';
-- Fix type mismatch in lupo_documentation_frameworks.namespace_key
ALTER TABLE `lupo_documentation_frameworks` MODIFY COLUMN `namespace_key` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_documentation_frameworks.channel_id
ALTER TABLE `lupo_documentation_frameworks` MODIFY COLUMN `channel_id` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_documentation_frameworks.collection_key
ALTER TABLE `lupo_documentation_frameworks` MODIFY COLUMN `collection_key` varchar(64) NOT NULL DEFAULT 'active';
-- Fix type mismatch in lupo_documentation_frameworks.runtime_min_php
ALTER TABLE `lupo_documentation_frameworks` MODIFY COLUMN `runtime_min_php` varchar(20) DEFAULT '5.6';
-- Fix type mismatch in lupo_documentation_frameworks.created_ymdhis
ALTER TABLE `lupo_documentation_frameworks` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_documentation_frameworks.updated_ymdhis
ALTER TABLE `lupo_documentation_frameworks` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_documentation_frameworks.is_deleted
ALTER TABLE `lupo_documentation_frameworks` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_edges.edge_id
ALTER TABLE `lupo_edges` MODIFY COLUMN `edge_id` bigint NOT NULL;
-- Fix type mismatch in lupo_edges.left_object_type
ALTER TABLE `lupo_edges` MODIFY COLUMN `left_object_type` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_edges.left_object_id
ALTER TABLE `lupo_edges` MODIFY COLUMN `left_object_id` bigint NOT NULL;
-- Fix type mismatch in lupo_edges.right_object_type
ALTER TABLE `lupo_edges` MODIFY COLUMN `right_object_type` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_edges.right_object_id
ALTER TABLE `lupo_edges` MODIFY COLUMN `right_object_id` bigint NOT NULL;
-- Fix type mismatch in lupo_edges.edge_type
ALTER TABLE `lupo_edges` MODIFY COLUMN `edge_type` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_edges.domain_id
ALTER TABLE `lupo_edges` MODIFY COLUMN `domain_id` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_edges.weight_score
ALTER TABLE `lupo_edges` MODIFY COLUMN `weight_score` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_edges.sort_num
ALTER TABLE `lupo_edges` MODIFY COLUMN `sort_num` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_edges.is_deleted
ALTER TABLE `lupo_edges` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_edges.deleted_ymdhis
ALTER TABLE `lupo_edges` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_edges.created_ymdhis
ALTER TABLE `lupo_edges` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_edges.updated_ymdhis
ALTER TABLE `lupo_edges` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_edges.semantic_weight
ALTER TABLE `lupo_edges` MODIFY COLUMN `semantic_weight` decimal(5,2) DEFAULT 0.00;
-- Fix type mismatch in lupo_edges.relationship_type
ALTER TABLE `lupo_edges` MODIFY COLUMN `relationship_type` varchar(64) DEFAULT 'semantic';
-- Fix type mismatch in lupo_edges.bidirectional
ALTER TABLE `lupo_edges` MODIFY COLUMN `bidirectional` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_edges.flare_weight
ALTER TABLE `lupo_edges` MODIFY COLUMN `flare_weight` decimal(3,2) DEFAULT 0.50 COMMENT 'FLARE edge weight (0.5-1.0)';
-- Fix type mismatch in lupo_edges.flare_reason
ALTER TABLE `lupo_edges` MODIFY COLUMN `flare_reason` varchar(255) COMMENT 'Reason for edge existence';
-- Fix type mismatch in lupo_edges.flare_db_source
ALTER TABLE `lupo_edges` MODIFY COLUMN `flare_db_source` varchar(50) COMMENT 'Database source table';
-- Fix type mismatch in lupo_edges.flare_auto_generated
ALTER TABLE `lupo_edges` MODIFY COLUMN `flare_auto_generated` tinyint DEFAULT 0 COMMENT 'Generated by automation';
-- Fix type mismatch in lupo_edges.flare_verified
ALTER TABLE `lupo_edges` MODIFY COLUMN `flare_verified` tinyint DEFAULT 0 COMMENT 'Path verified to exist';
-- Fix type mismatch in lupo_edges.flare_discovered_via
ALTER TABLE `lupo_edges` MODIFY COLUMN `flare_discovered_via` varchar(50) COMMENT 'Discovery method';

-- Fix type mismatch in lupo_edge_map.edge_map_id
ALTER TABLE `lupo_edge_map` MODIFY COLUMN `edge_map_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_edge_map.edge_id
ALTER TABLE `lupo_edge_map` MODIFY COLUMN `edge_id` bigint NOT NULL;
-- Fix type mismatch in lupo_edge_map.edge_type_id
ALTER TABLE `lupo_edge_map` MODIFY COLUMN `edge_type_id` bigint NOT NULL;
-- Fix type mismatch in lupo_edge_map.source_type
ALTER TABLE `lupo_edge_map` MODIFY COLUMN `source_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_edge_map.source_id
ALTER TABLE `lupo_edge_map` MODIFY COLUMN `source_id` bigint NOT NULL;
-- Fix type mismatch in lupo_edge_map.target_type
ALTER TABLE `lupo_edge_map` MODIFY COLUMN `target_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_edge_map.target_id
ALTER TABLE `lupo_edge_map` MODIFY COLUMN `target_id` bigint NOT NULL;
-- Fix type mismatch in lupo_edge_map.created_ymdhis
ALTER TABLE `lupo_edge_map` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_edge_map.is_deleted
ALTER TABLE `lupo_edge_map` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_edge_types.edge_type_id
ALTER TABLE `lupo_edge_types` MODIFY COLUMN `edge_type_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_edge_types.slug
ALTER TABLE `lupo_edge_types` MODIFY COLUMN `slug` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_edge_types.label
ALTER TABLE `lupo_edge_types` MODIFY COLUMN `label` varchar(128) NOT NULL;
-- Fix type mismatch in lupo_edge_types.is_bidirectional
ALTER TABLE `lupo_edge_types` MODIFY COLUMN `is_bidirectional` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_edge_types.created_ymdhis
ALTER TABLE `lupo_edge_types` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_edge_types.updated_ymdhis
ALTER TABLE `lupo_edge_types` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_edge_types.is_deleted
ALTER TABLE `lupo_edge_types` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_edge_type_definitions.edge_type_definition_id
ALTER TABLE `lupo_edge_type_definitions` MODIFY COLUMN `edge_type_definition_id` bigint NOT NULL;
-- Fix type mismatch in lupo_edge_type_definitions.edge_type
ALTER TABLE `lupo_edge_type_definitions` MODIFY COLUMN `edge_type` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_edge_type_definitions.domain
ALTER TABLE `lupo_edge_type_definitions` MODIFY COLUMN `domain` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_edge_type_definitions.description
ALTER TABLE `lupo_edge_type_definitions` MODIFY COLUMN `description` text NOT NULL;
-- Fix type mismatch in lupo_edge_type_definitions.allowed_left_object_types
ALTER TABLE `lupo_edge_type_definitions` MODIFY COLUMN `allowed_left_object_types` text NOT NULL;
-- Fix type mismatch in lupo_edge_type_definitions.allowed_right_object_types
ALTER TABLE `lupo_edge_type_definitions` MODIFY COLUMN `allowed_right_object_types` text NOT NULL;
-- Fix type mismatch in lupo_edge_type_definitions.is_bidirectional
ALTER TABLE `lupo_edge_type_definitions` MODIFY COLUMN `is_bidirectional` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_edge_type_definitions.created_ymdhis
ALTER TABLE `lupo_edge_type_definitions` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_edge_type_definitions.created_by_actor_id
ALTER TABLE `lupo_edge_type_definitions` MODIFY COLUMN `created_by_actor_id` bigint NOT NULL;

-- Fix type mismatch in lupo_emotional_frameworks.framework_name
ALTER TABLE `lupo_emotional_frameworks` MODIFY COLUMN `framework_name` varchar(32) NOT NULL;
-- Fix type mismatch in lupo_emotional_frameworks.is_default
ALTER TABLE `lupo_emotional_frameworks` MODIFY COLUMN `is_default` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_emotional_frameworks.created_ymdhis
ALTER TABLE `lupo_emotional_frameworks` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_emotional_frameworks.updated_ymdhis
ALTER TABLE `lupo_emotional_frameworks` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_escalation_tasks.escalation_task_id
ALTER TABLE `lupo_escalation_tasks` MODIFY COLUMN `escalation_task_id` bigint NOT NULL;
-- Fix type mismatch in lupo_escalation_tasks.actor_id
ALTER TABLE `lupo_escalation_tasks` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_escalation_tasks.thread_id
ALTER TABLE `lupo_escalation_tasks` MODIFY COLUMN `thread_id` bigint NOT NULL;
-- Fix type mismatch in lupo_escalation_tasks.message_id
ALTER TABLE `lupo_escalation_tasks` MODIFY COLUMN `message_id` bigint NOT NULL;
-- Fix type mismatch in lupo_escalation_tasks.task_type
ALTER TABLE `lupo_escalation_tasks` MODIFY COLUMN `task_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_escalation_tasks.status
ALTER TABLE `lupo_escalation_tasks` MODIFY COLUMN `status` varchar(32) NOT NULL DEFAULT 'open';
-- Fix type mismatch in lupo_escalation_tasks.assigned_actor_id
ALTER TABLE `lupo_escalation_tasks` MODIFY COLUMN `assigned_actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_escalation_tasks.created_ymdhis
ALTER TABLE `lupo_escalation_tasks` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_escalation_tasks.updated_ymdhis
ALTER TABLE `lupo_escalation_tasks` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_event_metadata.metadata_id
ALTER TABLE `lupo_event_metadata` MODIFY COLUMN `metadata_id` bigint NOT NULL;
-- Fix type mismatch in lupo_event_metadata.event_id
ALTER TABLE `lupo_event_metadata` MODIFY COLUMN `event_id` bigint NOT NULL;
-- Fix type mismatch in lupo_event_metadata.metadata_key
ALTER TABLE `lupo_event_metadata` MODIFY COLUMN `metadata_key` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_event_metadata.created_ymdhis
ALTER TABLE `lupo_event_metadata` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_federated_trust.trust_id
ALTER TABLE `lupo_federated_trust` MODIFY COLUMN `trust_id` bigint NOT NULL;
-- Fix type mismatch in lupo_federated_trust.source_node_id
ALTER TABLE `lupo_federated_trust` MODIFY COLUMN `source_node_id` bigint NOT NULL;
-- Fix type mismatch in lupo_federated_trust.target_node_id
ALTER TABLE `lupo_federated_trust` MODIFY COLUMN `target_node_id` bigint NOT NULL;
-- Fix type mismatch in lupo_federated_trust.trust_level
ALTER TABLE `lupo_federated_trust` MODIFY COLUMN `trust_level` float DEFAULT 0.5;
-- Fix type mismatch in lupo_federated_trust.trust_type
ALTER TABLE `lupo_federated_trust` MODIFY COLUMN `trust_type` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_federated_trust.created_ymdhis
ALTER TABLE `lupo_federated_trust` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federated_trust.is_deleted
ALTER TABLE `lupo_federated_trust` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_federation_categories.federation_category_id
ALTER TABLE `lupo_federation_categories` MODIFY COLUMN `federation_category_id` bigint NOT NULL;
-- Fix type mismatch in lupo_federation_categories.category_name
ALTER TABLE `lupo_federation_categories` MODIFY COLUMN `category_name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_federation_categories.category_slug
ALTER TABLE `lupo_federation_categories` MODIFY COLUMN `category_slug` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_federation_categories.is_deleted
ALTER TABLE `lupo_federation_categories` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_categories.deleted_ymdhis
ALTER TABLE `lupo_federation_categories` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_categories.created_ymdhis
ALTER TABLE `lupo_federation_categories` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_categories.updated_ymdhis
ALTER TABLE `lupo_federation_categories` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_federation_category_map.federation_category_map_id
ALTER TABLE `lupo_federation_category_map` MODIFY COLUMN `federation_category_map_id` bigint NOT NULL;
-- Fix type mismatch in lupo_federation_category_map.federation_node_id
ALTER TABLE `lupo_federation_category_map` MODIFY COLUMN `federation_node_id` bigint NOT NULL;
-- Fix type mismatch in lupo_federation_category_map.federation_category_id
ALTER TABLE `lupo_federation_category_map` MODIFY COLUMN `federation_category_id` bigint NOT NULL;
-- Fix type mismatch in lupo_federation_category_map.is_deleted
ALTER TABLE `lupo_federation_category_map` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_category_map.deleted_ymdhis
ALTER TABLE `lupo_federation_category_map` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_category_map.created_ymdhis
ALTER TABLE `lupo_federation_category_map` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_category_map.updated_ymdhis
ALTER TABLE `lupo_federation_category_map` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_federation_discovery.federation_discovery_id
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `federation_discovery_id` bigint NOT NULL;
-- Fix type mismatch in lupo_federation_discovery.domain
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `domain` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_federation_discovery.is_lupopedia
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `is_lupopedia` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_discovery.import_hashtags
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `import_hashtags` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_discovery.import_questions
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `import_questions` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_discovery.import_atoms
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `import_atoms` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_discovery.import_contexts
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `import_contexts` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_discovery.import_collections
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `import_collections` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_discovery.created_ymdhis
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_discovery.updated_ymdhis
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_federation_nodes.federation_node_id
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `federation_node_id` bigint NOT NULL;
-- Fix type mismatch in lupo_federation_nodes.node_type
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `node_type` varchar(32) NOT NULL DEFAULT 'local';
-- Fix type mismatch in lupo_federation_nodes.node_base_url
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `node_base_url` varchar(500) NOT NULL;
-- Fix type mismatch in lupo_federation_nodes.allows_foreign_traits
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `allows_foreign_traits` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_federation_nodes.content_count
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `content_count` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_nodes.atom_count
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `atom_count` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_nodes.hashtag_count
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `hashtag_count` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_nodes.actor_count
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `actor_count` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_nodes.last_sync_ymdhis
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `last_sync_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_nodes.trust_level
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `trust_level` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_nodes.status
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `status` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_federation_nodes.is_deleted
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_nodes.deleted_ymdhis
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_nodes.created_ymdhis
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_nodes.updated_ymdhis
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_federation_nodes.active_theme_slug
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `active_theme_slug` varchar(64) DEFAULT 'default';

-- Fix type mismatch in lupo_folders.folder_id
ALTER TABLE `lupo_folders` MODIFY COLUMN `folder_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_folders.name
ALTER TABLE `lupo_folders` MODIFY COLUMN `name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_folders.slug
ALTER TABLE `lupo_folders` MODIFY COLUMN `slug` varchar(128) NOT NULL;
-- Fix type mismatch in lupo_folders.sort_order
ALTER TABLE `lupo_folders` MODIFY COLUMN `sort_order` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_folders.created_ymdhis
ALTER TABLE `lupo_folders` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_folders.updated_ymdhis
ALTER TABLE `lupo_folders` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_folders.is_deleted
ALTER TABLE `lupo_folders` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_folder_map.folder_map_id
ALTER TABLE `lupo_folder_map` MODIFY COLUMN `folder_map_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_folder_map.folder_id
ALTER TABLE `lupo_folder_map` MODIFY COLUMN `folder_id` bigint NOT NULL;
-- Fix type mismatch in lupo_folder_map.object_type
ALTER TABLE `lupo_folder_map` MODIFY COLUMN `object_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_folder_map.object_id
ALTER TABLE `lupo_folder_map` MODIFY COLUMN `object_id` bigint NOT NULL;
-- Fix type mismatch in lupo_folder_map.sort_order
ALTER TABLE `lupo_folder_map` MODIFY COLUMN `sort_order` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_folder_map.created_ymdhis
ALTER TABLE `lupo_folder_map` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_folder_map.is_deleted
ALTER TABLE `lupo_folder_map` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_governance_overrides.governance_overrid_id
ALTER TABLE `lupo_governance_overrides` MODIFY COLUMN `governance_overrid_id` bigint NOT NULL;
-- Fix type mismatch in lupo_governance_overrides.override_type
ALTER TABLE `lupo_governance_overrides` MODIFY COLUMN `override_type` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_governance_overrides.created_ymdhis
ALTER TABLE `lupo_governance_overrides` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_governance_overrides.is_deleted
ALTER TABLE `lupo_governance_overrides` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_hashtags.hashtag_id
ALTER TABLE `lupo_hashtags` MODIFY COLUMN `hashtag_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_hashtags.tag_slug
ALTER TABLE `lupo_hashtags` MODIFY COLUMN `tag_slug` varchar(128) NOT NULL;
-- Fix type mismatch in lupo_hashtags.use_count
ALTER TABLE `lupo_hashtags` MODIFY COLUMN `use_count` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_hashtags.created_ymdhis
ALTER TABLE `lupo_hashtags` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_hashtags.updated_ymdhis
ALTER TABLE `lupo_hashtags` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_hashtags.is_deleted
ALTER TABLE `lupo_hashtags` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_hashtag_map.hashtag_map_id
ALTER TABLE `lupo_hashtag_map` MODIFY COLUMN `hashtag_map_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_hashtag_map.hashtag_id
ALTER TABLE `lupo_hashtag_map` MODIFY COLUMN `hashtag_id` bigint NOT NULL;
-- Fix type mismatch in lupo_hashtag_map.object_type
ALTER TABLE `lupo_hashtag_map` MODIFY COLUMN `object_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_hashtag_map.object_id
ALTER TABLE `lupo_hashtag_map` MODIFY COLUMN `object_id` bigint NOT NULL;
-- Fix type mismatch in lupo_hashtag_map.created_ymdhis
ALTER TABLE `lupo_hashtag_map` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_hashtag_map.is_deleted
ALTER TABLE `lupo_hashtag_map` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_help_topics.help_topic_id
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `help_topic_id` bigint NOT NULL;
-- Fix type mismatch in lupo_help_topics.slug
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `slug` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_help_topics.title
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `title` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_help_topics.view_count
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `view_count` bigint DEFAULT 0;
-- Fix type mismatch in lupo_help_topics.helpful_count
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `helpful_count` bigint DEFAULT 0;
-- Fix type mismatch in lupo_help_topics.not_helpful_count
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `not_helpful_count` bigint DEFAULT 0;
-- Fix type mismatch in lupo_help_topics.created_ymdhis
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_help_topics.updated_ymdhis
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_help_topics.is_deleted
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_help_tree.help_tree_id
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `help_tree_id` bigint NOT NULL;
-- Fix type mismatch in lupo_help_tree.department_id
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `department_id` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_help_tree.title
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `title` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_help_tree.action_type
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `action_type` varchar(64) NOT NULL DEFAULT 'none';
-- Fix type mismatch in lupo_help_tree.sort_order
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `sort_order` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_help_tree.created_ymdhis
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_help_tree.updated_ymdhis
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_help_tree.is_deleted
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_hotfix_registry.hotfix_id
ALTER TABLE `lupo_hotfix_registry` MODIFY COLUMN `hotfix_id` bigint NOT NULL;
-- Fix type mismatch in lupo_hotfix_registry.hotfix_version
ALTER TABLE `lupo_hotfix_registry` MODIFY COLUMN `hotfix_version` varchar(20) NOT NULL;
-- Fix type mismatch in lupo_hotfix_registry.applied_ymdhis
ALTER TABLE `lupo_hotfix_registry` MODIFY COLUMN `applied_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_human_requests.request_id
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `request_id` bigint NOT NULL;
-- Fix type mismatch in lupo_human_requests.thread_id
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `thread_id` bigint NOT NULL;
-- Fix type mismatch in lupo_human_requests.channel_id
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_human_requests.project_id
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `project_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_human_requests.initiator_actor_id
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `initiator_actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_human_requests.target_auth_user_id
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `target_auth_user_id` bigint NOT NULL;
-- Fix type mismatch in lupo_human_requests.request_type
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `request_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_human_requests.request_title
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `request_title` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_human_requests.request_description
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `request_description` text NOT NULL;
-- Fix type mismatch in lupo_human_requests.priority
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `priority` varchar(64) DEFAULT 'normal';
-- Fix type mismatch in lupo_human_requests.request_mode
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `request_mode` varchar(64) DEFAULT 'single_human';
-- Fix type mismatch in lupo_human_requests.status
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `status` varchar(64) NOT NULL DEFAULT 'pending';
-- Fix type mismatch in lupo_human_requests.created_ymdhis
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_human_requests.updated_ymdhis
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_human_requests.resolved_ymdhis
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `resolved_ymdhis` bigint DEFAULT 0;
-- Fix type mismatch in lupo_human_requests.expires_ymdhis
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `expires_ymdhis` bigint DEFAULT 0;
-- Fix type mismatch in lupo_human_requests.is_deleted
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `is_deleted` tinyint DEFAULT 0;
-- Fix type mismatch in lupo_human_requests.deleted_ymdhis
ALTER TABLE `lupo_human_requests` MODIFY COLUMN `deleted_ymdhis` bigint DEFAULT 0;

-- Fix type mismatch in lupo_human_request_context.context_id
ALTER TABLE `lupo_human_request_context` MODIFY COLUMN `context_id` bigint NOT NULL;
-- Fix type mismatch in lupo_human_request_context.request_id
ALTER TABLE `lupo_human_request_context` MODIFY COLUMN `request_id` bigint NOT NULL;
-- Fix type mismatch in lupo_human_request_context.context_type
ALTER TABLE `lupo_human_request_context` MODIFY COLUMN `context_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_human_request_context.content
ALTER TABLE `lupo_human_request_context` MODIFY COLUMN `content` text NOT NULL;
-- Fix type mismatch in lupo_human_request_context.created_ymdhis
ALTER TABLE `lupo_human_request_context` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_human_request_responses.response_id
ALTER TABLE `lupo_human_request_responses` MODIFY COLUMN `response_id` bigint NOT NULL;
-- Fix type mismatch in lupo_human_request_responses.request_id
ALTER TABLE `lupo_human_request_responses` MODIFY COLUMN `request_id` bigint NOT NULL;
-- Fix type mismatch in lupo_human_request_responses.auth_user_id
ALTER TABLE `lupo_human_request_responses` MODIFY COLUMN `auth_user_id` bigint NOT NULL;
-- Fix type mismatch in lupo_human_request_responses.actor_id
ALTER TABLE `lupo_human_request_responses` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_human_request_responses.response_type
ALTER TABLE `lupo_human_request_responses` MODIFY COLUMN `response_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_human_request_responses.response_text
ALTER TABLE `lupo_human_request_responses` MODIFY COLUMN `response_text` text NOT NULL;
-- Fix type mismatch in lupo_human_request_responses.response_ymdhis
ALTER TABLE `lupo_human_request_responses` MODIFY COLUMN `response_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_human_request_responses.is_deleted
ALTER TABLE `lupo_human_request_responses` MODIFY COLUMN `is_deleted` tinyint DEFAULT 0;
-- Fix type mismatch in lupo_human_request_responses.deleted_ymdhis
ALTER TABLE `lupo_human_request_responses` MODIFY COLUMN `deleted_ymdhis` bigint DEFAULT 0;

-- Fix type mismatch in lupo_interpretation_log.interpretation_log_id
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `interpretation_log_id` bigint NOT NULL;
-- Fix type mismatch in lupo_interpretation_log.agent_id
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `agent_id` bigint NOT NULL;
-- Fix type mismatch in lupo_interpretation_log.entity_type
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `entity_type` varchar(32) NOT NULL;
-- Fix type mismatch in lupo_interpretation_log.entity_id
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `entity_id` bigint NOT NULL;
-- Fix type mismatch in lupo_interpretation_log.interpretation
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `interpretation` text NOT NULL;
-- Fix type mismatch in lupo_interpretation_log.created_ymdhis
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_interpretation_log.updated_ymdhis
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_interpretation_log.is_deleted
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_labs_declarations.labs_declaration_id
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `labs_declaration_id` bigint NOT NULL;
-- Fix type mismatch in lupo_labs_declarations.actor_id
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_labs_declarations.certificate_id
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `certificate_id` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_labs_declarations.declaration_timestamp
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `declaration_timestamp` bigint NOT NULL;
-- Fix type mismatch in lupo_labs_declarations.declarations_json
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `declarations_json` json NOT NULL;
-- Fix type mismatch in lupo_labs_declarations.validation_status
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `validation_status` varchar(64) NOT NULL DEFAULT 'valid';
-- Fix type mismatch in lupo_labs_declarations.labs_version
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `labs_version` varchar(16) NOT NULL DEFAULT '1.0';
-- Fix type mismatch in lupo_labs_declarations.next_revalidation_ymdhis
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `next_revalidation_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_labs_declarations.created_ymdhis
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_labs_declarations.updated_ymdhis
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_labs_declarations.is_deleted
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_labs_violations.labs_violation_id
ALTER TABLE `lupo_labs_violations` MODIFY COLUMN `labs_violation_id` bigint NOT NULL;
-- Fix type mismatch in lupo_labs_violations.actor_id
ALTER TABLE `lupo_labs_violations` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_labs_violations.certificate_id
ALTER TABLE `lupo_labs_violations` MODIFY COLUMN `certificate_id` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_labs_violations.violation_code
ALTER TABLE `lupo_labs_violations` MODIFY COLUMN `violation_code` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_labs_violations.created_ymdhis
ALTER TABLE `lupo_labs_violations` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_labs_violations.updated_ymdhis
ALTER TABLE `lupo_labs_violations` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_labs_violations.is_deleted
ALTER TABLE `lupo_labs_violations` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_legacy_content_mapping.mapping_id
ALTER TABLE `lupo_legacy_content_mapping` MODIFY COLUMN `mapping_id` bigint NOT NULL;
-- Fix type mismatch in lupo_legacy_content_mapping.legacy_url
ALTER TABLE `lupo_legacy_content_mapping` MODIFY COLUMN `legacy_url` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_legacy_content_mapping.semantic_url
ALTER TABLE `lupo_legacy_content_mapping` MODIFY COLUMN `semantic_url` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_legacy_content_mapping.content_type
ALTER TABLE `lupo_legacy_content_mapping` MODIFY COLUMN `content_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_legacy_content_mapping.created_ymdhis
ALTER TABLE `lupo_legacy_content_mapping` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_legacy_content_mapping.updated_ymdhis
ALTER TABLE `lupo_legacy_content_mapping` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_legacy_content_mapping.is_active
ALTER TABLE `lupo_legacy_content_mapping` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;

-- Fix type mismatch in lupo_memory_rollups.memory_rollup_id
ALTER TABLE `lupo_memory_rollups` MODIFY COLUMN `memory_rollup_id` bigint NOT NULL;
-- Fix type mismatch in lupo_memory_rollups.actor_id
ALTER TABLE `lupo_memory_rollups` MODIFY COLUMN `actor_id` int NOT NULL;
-- Fix type mismatch in lupo_memory_rollups.summary
ALTER TABLE `lupo_memory_rollups` MODIFY COLUMN `summary` text NOT NULL;
-- Fix type mismatch in lupo_memory_rollups.source_event_ids
ALTER TABLE `lupo_memory_rollups` MODIFY COLUMN `source_event_ids` text NOT NULL;
-- Fix type mismatch in lupo_memory_rollups.created_ymdhis
ALTER TABLE `lupo_memory_rollups` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_metadata.metadata_id
ALTER TABLE `lupo_metadata` MODIFY COLUMN `metadata_id` bigint NOT NULL;
-- Fix type mismatch in lupo_metadata.entity_type
ALTER TABLE `lupo_metadata` MODIFY COLUMN `entity_type` varchar(32) NOT NULL;
-- Fix type mismatch in lupo_metadata.entity_id
ALTER TABLE `lupo_metadata` MODIFY COLUMN `entity_id` bigint NOT NULL;
-- Fix type mismatch in lupo_metadata.property_key
ALTER TABLE `lupo_metadata` MODIFY COLUMN `property_key` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_metadata.created_ymdhis
ALTER TABLE `lupo_metadata` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_metadata.updated_ymdhis
ALTER TABLE `lupo_metadata` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_metadata.is_deleted
ALTER TABLE `lupo_metadata` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_modules.module_id
ALTER TABLE `lupo_modules` MODIFY COLUMN `module_id` bigint NOT NULL;
-- Fix type mismatch in lupo_modules.module_key
ALTER TABLE `lupo_modules` MODIFY COLUMN `module_key` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_modules.module_name
ALTER TABLE `lupo_modules` MODIFY COLUMN `module_name` varchar(150) NOT NULL;
-- Fix type mismatch in lupo_modules.namespace
ALTER TABLE `lupo_modules` MODIFY COLUMN `namespace` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_modules.version
ALTER TABLE `lupo_modules` MODIFY COLUMN `version` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_modules.version_code
ALTER TABLE `lupo_modules` MODIFY COLUMN `version_code` int NOT NULL;
-- Fix type mismatch in lupo_modules.minimum_core_version
ALTER TABLE `lupo_modules` MODIFY COLUMN `minimum_core_version` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_modules.icon
ALTER TABLE `lupo_modules` MODIFY COLUMN `icon` varchar(100) DEFAULT 'puzzle-piece';
-- Fix type mismatch in lupo_modules.config_json
ALTER TABLE `lupo_modules` MODIFY COLUMN `config_json` text NOT NULL;
-- Fix type mismatch in lupo_modules.is_system
ALTER TABLE `lupo_modules` MODIFY COLUMN `is_system` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_modules.is_active
ALTER TABLE `lupo_modules` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_modules.federation_node_id
ALTER TABLE `lupo_modules` MODIFY COLUMN `federation_node_id` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_modules.created_ymdhis
ALTER TABLE `lupo_modules` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_modules.is_deleted
ALTER TABLE `lupo_modules` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_notifications.notification_id
ALTER TABLE `lupo_notifications` MODIFY COLUMN `notification_id` bigint NOT NULL;
-- Fix type mismatch in lupo_notifications.actor_id
ALTER TABLE `lupo_notifications` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_notifications.notification_type
ALTER TABLE `lupo_notifications` MODIFY COLUMN `notification_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_notifications.is_read
ALTER TABLE `lupo_notifications` MODIFY COLUMN `is_read` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_notifications.is_deleted
ALTER TABLE `lupo_notifications` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_notifications.created_ymdhis
ALTER TABLE `lupo_notifications` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_orchestrator_rules.rule_id
ALTER TABLE `lupo_orchestrator_rules` MODIFY COLUMN `rule_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_orchestrator_rules.rule_slug
ALTER TABLE `lupo_orchestrator_rules` MODIFY COLUMN `rule_slug` varchar(128) NOT NULL;
-- Fix type mismatch in lupo_orchestrator_rules.orchestrator_actor
ALTER TABLE `lupo_orchestrator_rules` MODIFY COLUMN `orchestrator_actor` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_orchestrator_rules.rule_set_version
ALTER TABLE `lupo_orchestrator_rules` MODIFY COLUMN `rule_set_version` varchar(32) NOT NULL;
-- Fix type mismatch in lupo_orchestrator_rules.applies_to_json
ALTER TABLE `lupo_orchestrator_rules` MODIFY COLUMN `applies_to_json` text NOT NULL;
-- Fix type mismatch in lupo_orchestrator_rules.enforcement_level
ALTER TABLE `lupo_orchestrator_rules` MODIFY COLUMN `enforcement_level` varchar(32) NOT NULL DEFAULT 'strict';
-- Fix type mismatch in lupo_orchestrator_rules.rule_content
ALTER TABLE `lupo_orchestrator_rules` MODIFY COLUMN `rule_content` text NOT NULL;
-- Fix type mismatch in lupo_orchestrator_rules.checksum
ALTER TABLE `lupo_orchestrator_rules` MODIFY COLUMN `checksum` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_orchestrator_rules.is_active
ALTER TABLE `lupo_orchestrator_rules` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_orchestrator_rules.updated_ymdhis
ALTER TABLE `lupo_orchestrator_rules` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_paths.path_id
ALTER TABLE `lupo_paths` MODIFY COLUMN `path_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_paths.count_num
ALTER TABLE `lupo_paths` MODIFY COLUMN `count_num` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_paths.created_ymdhis
ALTER TABLE `lupo_paths` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_paths.updated_ymdhis
ALTER TABLE `lupo_paths` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_paths.is_deleted
ALTER TABLE `lupo_paths` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_paths_summary.summary_id
ALTER TABLE `lupo_paths_summary` MODIFY COLUMN `summary_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_paths_summary.path_id
ALTER TABLE `lupo_paths_summary` MODIFY COLUMN `path_id` bigint NOT NULL;
-- Fix type mismatch in lupo_paths_summary.total_count
ALTER TABLE `lupo_paths_summary` MODIFY COLUMN `total_count` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_paths_summary.last_used_ymdhis
ALTER TABLE `lupo_paths_summary` MODIFY COLUMN `last_used_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_paths_summary.created_ymdhis
ALTER TABLE `lupo_paths_summary` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_paths_summary.updated_ymdhis
ALTER TABLE `lupo_paths_summary` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_permissions.permission_id
ALTER TABLE `lupo_permissions` MODIFY COLUMN `permission_id` bigint NOT NULL;
-- Fix type mismatch in lupo_permissions.target_type
ALTER TABLE `lupo_permissions` MODIFY COLUMN `target_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_permissions.target_id
ALTER TABLE `lupo_permissions` MODIFY COLUMN `target_id` bigint NOT NULL;
-- Fix type mismatch in lupo_permissions.permission
ALTER TABLE `lupo_permissions` MODIFY COLUMN `permission` varchar(64) NOT NULL DEFAULT 'read';
-- Fix type mismatch in lupo_permissions.created_ymdhis
ALTER TABLE `lupo_permissions` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_permissions.is_deleted
ALTER TABLE `lupo_permissions` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_projects.project_id
ALTER TABLE `lupo_projects` MODIFY COLUMN `project_id` bigint NOT NULL;
-- Fix type mismatch in lupo_projects.project_key
ALTER TABLE `lupo_projects` MODIFY COLUMN `project_key` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_projects.project_slug
ALTER TABLE `lupo_projects` MODIFY COLUMN `project_slug` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_projects.project_name
ALTER TABLE `lupo_projects` MODIFY COLUMN `project_name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_projects.federation_node_id
ALTER TABLE `lupo_projects` MODIFY COLUMN `federation_node_id` bigint NOT NULL;
-- Fix type mismatch in lupo_projects.orchestrator_id
ALTER TABLE `lupo_projects` MODIFY COLUMN `orchestrator_id` bigint NOT NULL;
-- Fix type mismatch in lupo_projects.project_type
ALTER TABLE `lupo_projects` MODIFY COLUMN `project_type` varchar(64) DEFAULT 'standard';
-- Fix type mismatch in lupo_projects.status
ALTER TABLE `lupo_projects` MODIFY COLUMN `status` varchar(32) NOT NULL DEFAULT 'active';
-- Fix type mismatch in lupo_projects.is_active
ALTER TABLE `lupo_projects` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_projects.is_deleted
ALTER TABLE `lupo_projects` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_projects.is_archived
ALTER TABLE `lupo_projects` MODIFY COLUMN `is_archived` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_projects.is_frozen
ALTER TABLE `lupo_projects` MODIFY COLUMN `is_frozen` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_projects.created_ymdhis
ALTER TABLE `lupo_projects` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_projects.updated_ymdhis
ALTER TABLE `lupo_projects` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_projects.deleted_ymdhis
ALTER TABLE `lupo_projects` MODIFY COLUMN `deleted_ymdhis` bigint DEFAULT 0;

-- Fix type mismatch in lupo_questions.question_id
ALTER TABLE `lupo_questions` MODIFY COLUMN `question_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_questions.slug
ALTER TABLE `lupo_questions` MODIFY COLUMN `slug` varchar(128) NOT NULL;
-- Fix type mismatch in lupo_questions.question_text
ALTER TABLE `lupo_questions` MODIFY COLUMN `question_text` text NOT NULL;
-- Fix type mismatch in lupo_questions.created_ymdhis
ALTER TABLE `lupo_questions` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_questions.updated_ymdhis
ALTER TABLE `lupo_questions` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_questions.is_deleted
ALTER TABLE `lupo_questions` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_question_map.question_map_id
ALTER TABLE `lupo_question_map` MODIFY COLUMN `question_map_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_question_map.question_id
ALTER TABLE `lupo_question_map` MODIFY COLUMN `question_id` bigint NOT NULL;
-- Fix type mismatch in lupo_question_map.object_type
ALTER TABLE `lupo_question_map` MODIFY COLUMN `object_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_question_map.object_id
ALTER TABLE `lupo_question_map` MODIFY COLUMN `object_id` bigint NOT NULL;
-- Fix type mismatch in lupo_question_map.created_ymdhis
ALTER TABLE `lupo_question_map` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_question_map.is_deleted
ALTER TABLE `lupo_question_map` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_references.reference_id
ALTER TABLE `lupo_references` MODIFY COLUMN `reference_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_references.source_entity_type
ALTER TABLE `lupo_references` MODIFY COLUMN `source_entity_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_references.source_entity_id
ALTER TABLE `lupo_references` MODIFY COLUMN `source_entity_id` bigint NOT NULL;
-- Fix type mismatch in lupo_references.created_ymdhis
ALTER TABLE `lupo_references` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_references.updated_ymdhis
ALTER TABLE `lupo_references` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_references.is_deleted
ALTER TABLE `lupo_references` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_reference_links.reference_link_id
ALTER TABLE `lupo_reference_links` MODIFY COLUMN `reference_link_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_reference_links.reference_id
ALTER TABLE `lupo_reference_links` MODIFY COLUMN `reference_id` bigint NOT NULL;
-- Fix type mismatch in lupo_reference_links.object_type
ALTER TABLE `lupo_reference_links` MODIFY COLUMN `object_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_reference_links.object_id
ALTER TABLE `lupo_reference_links` MODIFY COLUMN `object_id` bigint NOT NULL;
-- Fix type mismatch in lupo_reference_links.sort_order
ALTER TABLE `lupo_reference_links` MODIFY COLUMN `sort_order` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_reference_links.created_ymdhis
ALTER TABLE `lupo_reference_links` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_reference_links.is_deleted
ALTER TABLE `lupo_reference_links` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_reference_map.reference_map_id
ALTER TABLE `lupo_reference_map` MODIFY COLUMN `reference_map_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_reference_map.reference_id
ALTER TABLE `lupo_reference_map` MODIFY COLUMN `reference_id` bigint NOT NULL;
-- Fix type mismatch in lupo_reference_map.target_type
ALTER TABLE `lupo_reference_map` MODIFY COLUMN `target_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_reference_map.target_id
ALTER TABLE `lupo_reference_map` MODIFY COLUMN `target_id` bigint NOT NULL;
-- Fix type mismatch in lupo_reference_map.created_ymdhis
ALTER TABLE `lupo_reference_map` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_reference_map.is_deleted
ALTER TABLE `lupo_reference_map` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_reference_objects.reference_object_id
ALTER TABLE `lupo_reference_objects` MODIFY COLUMN `reference_object_id` bigint NOT NULL;
-- Fix type mismatch in lupo_reference_objects.object_type
ALTER TABLE `lupo_reference_objects` MODIFY COLUMN `object_type` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_reference_objects.object_slug
ALTER TABLE `lupo_reference_objects` MODIFY COLUMN `object_slug` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_reference_objects.is_deleted
ALTER TABLE `lupo_reference_objects` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_reference_objects.created_ymdhis
ALTER TABLE `lupo_reference_objects` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_reference_objects.updated_ymdhis
ALTER TABLE `lupo_reference_objects` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_referers.referer_id
ALTER TABLE `lupo_referers` MODIFY COLUMN `referer_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_referers.content_id
ALTER TABLE `lupo_referers` MODIFY COLUMN `content_id` bigint NOT NULL;
-- Fix type mismatch in lupo_referers.actor_id
ALTER TABLE `lupo_referers` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_referers.date_ymd
ALTER TABLE `lupo_referers` MODIFY COLUMN `date_ymd` int NOT NULL;
-- Fix type mismatch in lupo_referers.visits
ALTER TABLE `lupo_referers` MODIFY COLUMN `visits` int NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_referers.depth
ALTER TABLE `lupo_referers` MODIFY COLUMN `depth` int NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_registry.registry_id
ALTER TABLE `lupo_registry` MODIFY COLUMN `registry_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_registry.entity_type
ALTER TABLE `lupo_registry` MODIFY COLUMN `entity_type` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_registry.entity_index_id
ALTER TABLE `lupo_registry` MODIFY COLUMN `entity_index_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_registry.entity_index
ALTER TABLE `lupo_registry` MODIFY COLUMN `entity_index` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_registry.federation_node_id
ALTER TABLE `lupo_registry` MODIFY COLUMN `federation_node_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_registry.reserved_ymdhis
ALTER TABLE `lupo_registry` MODIFY COLUMN `reserved_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_registry.created_ymdhis
ALTER TABLE `lupo_registry` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_registry.updated_ymdhis
ALTER TABLE `lupo_registry` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_registry.is_deleted
ALTER TABLE `lupo_registry` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_registry.is_active
ALTER TABLE `lupo_registry` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_registry.is_kernel
ALTER TABLE `lupo_registry` MODIFY COLUMN `is_kernel` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_registry_open.unregistry_id
ALTER TABLE `lupo_registry_open` MODIFY COLUMN `unregistry_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_registry_open.entity_type
ALTER TABLE `lupo_registry_open` MODIFY COLUMN `entity_type` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_registry_open.entity_index_id
ALTER TABLE `lupo_registry_open` MODIFY COLUMN `entity_index_id` bigint NOT NULL;
-- Fix type mismatch in lupo_registry_open.created_ymdhis
ALTER TABLE `lupo_registry_open` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_rolls.roll_id
ALTER TABLE `lupo_rolls` MODIFY COLUMN `roll_id` bigint NOT NULL;
-- Fix type mismatch in lupo_rolls.channel_id
ALTER TABLE `lupo_rolls` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_rolls.actor_id
ALTER TABLE `lupo_rolls` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_rolls.role_slug
ALTER TABLE `lupo_rolls` MODIFY COLUMN `role_slug` varchar(100) NOT NULL;
-- Fix type mismatch in lupo_rolls.is_active
ALTER TABLE `lupo_rolls` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_rolls.created_ymdhis
ALTER TABLE `lupo_rolls` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_rolls.updated_ymdhis
ALTER TABLE `lupo_rolls` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_routing_decisions.routing_decision_id
ALTER TABLE `lupo_routing_decisions` MODIFY COLUMN `routing_decision_id` bigint NOT NULL;
-- Fix type mismatch in lupo_routing_decisions.actor_id
ALTER TABLE `lupo_routing_decisions` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_routing_decisions.thread_id
ALTER TABLE `lupo_routing_decisions` MODIFY COLUMN `thread_id` bigint NOT NULL;
-- Fix type mismatch in lupo_routing_decisions.routing_strategy
ALTER TABLE `lupo_routing_decisions` MODIFY COLUMN `routing_strategy` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_routing_decisions.candidate_users_json
ALTER TABLE `lupo_routing_decisions` MODIFY COLUMN `candidate_users_json` text NOT NULL;
-- Fix type mismatch in lupo_routing_decisions.selected_auth_user_id
ALTER TABLE `lupo_routing_decisions` MODIFY COLUMN `selected_auth_user_id` bigint NOT NULL;
-- Fix type mismatch in lupo_routing_decisions.fallback_index
ALTER TABLE `lupo_routing_decisions` MODIFY COLUMN `fallback_index` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_routing_decisions.decision_status
ALTER TABLE `lupo_routing_decisions` MODIFY COLUMN `decision_status` varchar(32) NOT NULL;
-- Fix type mismatch in lupo_routing_decisions.trigger_type
ALTER TABLE `lupo_routing_decisions` MODIFY COLUMN `trigger_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_routing_decisions.created_ymdhis
ALTER TABLE `lupo_routing_decisions` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_routing_decisions.completed_ymdhis
ALTER TABLE `lupo_routing_decisions` MODIFY COLUMN `completed_ymdhis` bigint DEFAULT 0;

-- Fix type mismatch in lupo_rules.rule_id
ALTER TABLE `lupo_rules` MODIFY COLUMN `rule_id` bigint NOT NULL;
-- Fix type mismatch in lupo_rules.rule_name
ALTER TABLE `lupo_rules` MODIFY COLUMN `rule_name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_rules.rule_type
ALTER TABLE `lupo_rules` MODIFY COLUMN `rule_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_rules.rule_script
ALTER TABLE `lupo_rules` MODIFY COLUMN `rule_script` text NOT NULL;
-- Fix type mismatch in lupo_rules.rule_version
ALTER TABLE `lupo_rules` MODIFY COLUMN `rule_version` bigint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_rules.created_ymdhis
ALTER TABLE `lupo_rules` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_rules.updated_ymdhis
ALTER TABLE `lupo_rules` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_rules.is_deleted
ALTER TABLE `lupo_rules` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_rule_logs.rule_log_id
ALTER TABLE `lupo_rule_logs` MODIFY COLUMN `rule_log_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_rule_logs.rule_id
ALTER TABLE `lupo_rule_logs` MODIFY COLUMN `rule_id` bigint NOT NULL;
-- Fix type mismatch in lupo_rule_logs.target_table
ALTER TABLE `lupo_rule_logs` MODIFY COLUMN `target_table` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_rule_logs.target_id
ALTER TABLE `lupo_rule_logs` MODIFY COLUMN `target_id` bigint NOT NULL;
-- Fix type mismatch in lupo_rule_logs.actor_id
ALTER TABLE `lupo_rule_logs` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_rule_logs.instance_id
ALTER TABLE `lupo_rule_logs` MODIFY COLUMN `instance_id` bigint DEFAULT 0;
-- Fix type mismatch in lupo_rule_logs.event_type
ALTER TABLE `lupo_rule_logs` MODIFY COLUMN `event_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_rule_logs.created_ymdhis
ALTER TABLE `lupo_rule_logs` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_rule_targets.rule_target_id
ALTER TABLE `lupo_rule_targets` MODIFY COLUMN `rule_target_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_rule_targets.rule_id
ALTER TABLE `lupo_rule_targets` MODIFY COLUMN `rule_id` bigint NOT NULL;
-- Fix type mismatch in lupo_rule_targets.target_table
ALTER TABLE `lupo_rule_targets` MODIFY COLUMN `target_table` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_rule_targets.target_id
ALTER TABLE `lupo_rule_targets` MODIFY COLUMN `target_id` bigint NOT NULL;
-- Fix type mismatch in lupo_rule_targets.priority
ALTER TABLE `lupo_rule_targets` MODIFY COLUMN `priority` int NOT NULL DEFAULT 100;
-- Fix type mismatch in lupo_rule_targets.created_ymdhis
ALTER TABLE `lupo_rule_targets` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_rule_targets.updated_ymdhis
ALTER TABLE `lupo_rule_targets` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_rule_targets.is_deleted
ALTER TABLE `lupo_rule_targets` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_schema_migrations.schema_migration_id
ALTER TABLE `lupo_schema_migrations` MODIFY COLUMN `schema_migration_id` bigint NOT NULL;
-- Fix type mismatch in lupo_schema_migrations.version
ALTER TABLE `lupo_schema_migrations` MODIFY COLUMN `version` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_schema_migrations.name
ALTER TABLE `lupo_schema_migrations` MODIFY COLUMN `name` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_schema_migrations.applied_ymdhis
ALTER TABLE `lupo_schema_migrations` MODIFY COLUMN `applied_ymdhis` bigint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_search_index.search_index_id
ALTER TABLE `lupo_search_index` MODIFY COLUMN `search_index_id` bigint NOT NULL;
-- Fix type mismatch in lupo_search_index.domain_id
ALTER TABLE `lupo_search_index` MODIFY COLUMN `domain_id` bigint NOT NULL;
-- Fix type mismatch in lupo_search_index.entity_type
ALTER TABLE `lupo_search_index` MODIFY COLUMN `entity_type` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_search_index.entity_id
ALTER TABLE `lupo_search_index` MODIFY COLUMN `entity_id` bigint NOT NULL;
-- Fix type mismatch in lupo_search_index.relevance_score
ALTER TABLE `lupo_search_index` MODIFY COLUMN `relevance_score` float DEFAULT 1;
-- Fix type mismatch in lupo_search_index.is_deleted
ALTER TABLE `lupo_search_index` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_search_index.created_ymdhis
ALTER TABLE `lupo_search_index` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_search_index.updated_ymdhis
ALTER TABLE `lupo_search_index` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_search_rebuild_log.search_rebuild_log_id
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `search_rebuild_log_id` bigint NOT NULL;
-- Fix type mismatch in lupo_search_rebuild_log.entity_type
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `entity_type` varchar(50) NOT NULL;
-- Fix type mismatch in lupo_search_rebuild_log.entity_id
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `entity_id` bigint NOT NULL;
-- Fix type mismatch in lupo_search_rebuild_log.action
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `action` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_search_rebuild_log.status
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `status` varchar(64) NOT NULL DEFAULT 'pending';
-- Fix type mismatch in lupo_search_rebuild_log.attempts
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `attempts` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_search_rebuild_log.created_ymdhis
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_search_rebuild_log.is_deleted
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_semantic_index.semantic_id
ALTER TABLE `lupo_semantic_index` MODIFY COLUMN `semantic_id` bigint NOT NULL;
-- Fix type mismatch in lupo_semantic_index.semantic_type
ALTER TABLE `lupo_semantic_index` MODIFY COLUMN `semantic_type` varchar(32) NOT NULL;
-- Fix type mismatch in lupo_semantic_index.sort_order
ALTER TABLE `lupo_semantic_index` MODIFY COLUMN `sort_order` int DEFAULT 0;
-- Fix type mismatch in lupo_semantic_index.weight
ALTER TABLE `lupo_semantic_index` MODIFY COLUMN `weight` float DEFAULT 0;
-- Fix type mismatch in lupo_semantic_index.relationship_strength
ALTER TABLE `lupo_semantic_index` MODIFY COLUMN `relationship_strength` decimal(3,2) DEFAULT 1.00;
-- Fix type mismatch in lupo_semantic_index.color
ALTER TABLE `lupo_semantic_index` MODIFY COLUMN `color` varchar(7) DEFAULT '#666666';
-- Fix type mismatch in lupo_semantic_index.created_ymdhis
ALTER TABLE `lupo_semantic_index` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_semantic_index.updated_ymdhis
ALTER TABLE `lupo_semantic_index` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_semantic_index.is_active
ALTER TABLE `lupo_semantic_index` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_semantic_index.is_default
ALTER TABLE `lupo_semantic_index` MODIFY COLUMN `is_default` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_semantic_index.is_deleted
ALTER TABLE `lupo_semantic_index` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_sessions.session_id
ALTER TABLE `lupo_sessions` MODIFY COLUMN `session_id` varchar(128) NOT NULL;
-- Fix type mismatch in lupo_sessions.actor_id
ALTER TABLE `lupo_sessions` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_sessions.federation_node_id
ALTER TABLE `lupo_sessions` MODIFY COLUMN `federation_node_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_sessions.last_activity_ymdhis
ALTER TABLE `lupo_sessions` MODIFY COLUMN `last_activity_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_sessions.created_ymdhis
ALTER TABLE `lupo_sessions` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_sessions.updated_ymdhis
ALTER TABLE `lupo_sessions` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_sessions.is_named
ALTER TABLE `lupo_sessions` MODIFY COLUMN `is_named` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_sessions.is_active
ALTER TABLE `lupo_sessions` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
-- Fix type mismatch in lupo_sessions.is_expired
ALTER TABLE `lupo_sessions` MODIFY COLUMN `is_expired` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_sessions.is_revoked
ALTER TABLE `lupo_sessions` MODIFY COLUMN `is_revoked` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_sessions.is_deleted
ALTER TABLE `lupo_sessions` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_system_commands.command_id
ALTER TABLE `lupo_system_commands` MODIFY COLUMN `command_id` bigint NOT NULL;
-- Fix type mismatch in lupo_system_commands.command_type
ALTER TABLE `lupo_system_commands` MODIFY COLUMN `command_type` varchar(128) NOT NULL;
-- Fix type mismatch in lupo_system_commands.status
ALTER TABLE `lupo_system_commands` MODIFY COLUMN `status` varchar(32) NOT NULL;
-- Fix type mismatch in lupo_system_commands.priority
ALTER TABLE `lupo_system_commands` MODIFY COLUMN `priority` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_system_commands.created_ymdhis
ALTER TABLE `lupo_system_commands` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_system_commands.scheduled_ymdhis
ALTER TABLE `lupo_system_commands` MODIFY COLUMN `scheduled_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_system_commands.attempt_count
ALTER TABLE `lupo_system_commands` MODIFY COLUMN `attempt_count` int NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_system_commands.max_attempts
ALTER TABLE `lupo_system_commands` MODIFY COLUMN `max_attempts` int NOT NULL DEFAULT 3;
-- Fix type mismatch in lupo_system_commands.timeout_seconds
ALTER TABLE `lupo_system_commands` MODIFY COLUMN `timeout_seconds` int NOT NULL DEFAULT 3600;
-- Fix type mismatch in lupo_system_commands.is_deleted
ALTER TABLE `lupo_system_commands` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_system_config.system_config_id
ALTER TABLE `lupo_system_config` MODIFY COLUMN `system_config_id` bigint NOT NULL;
-- Fix type mismatch in lupo_system_config.config_key
ALTER TABLE `lupo_system_config` MODIFY COLUMN `config_key` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_system_config.config_value
ALTER TABLE `lupo_system_config` MODIFY COLUMN `config_value` text NOT NULL;
-- Fix type mismatch in lupo_system_config.actor_id
ALTER TABLE `lupo_system_config` MODIFY COLUMN `actor_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_system_config.created_ymdhis
ALTER TABLE `lupo_system_config` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_system_config.updated_ymdhis
ALTER TABLE `lupo_system_config` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_system_health_snapshots.snapshot_id
ALTER TABLE `lupo_system_health_snapshots` MODIFY COLUMN `snapshot_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_system_health_snapshots.snapshot_type
ALTER TABLE `lupo_system_health_snapshots` MODIFY COLUMN `snapshot_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_system_health_snapshots.actor_id
ALTER TABLE `lupo_system_health_snapshots` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_system_health_snapshots.created_ymdhis
ALTER TABLE `lupo_system_health_snapshots` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_system_health_snapshots.is_deleted
ALTER TABLE `lupo_system_health_snapshots` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_tasks.task_id
ALTER TABLE `lupo_tasks` MODIFY COLUMN `task_id` bigint NOT NULL;
-- Fix type mismatch in lupo_tasks.task_key
ALTER TABLE `lupo_tasks` MODIFY COLUMN `task_key` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_tasks.channel_id
ALTER TABLE `lupo_tasks` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_tasks.owner_actor_id
ALTER TABLE `lupo_tasks` MODIFY COLUMN `owner_actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_tasks.title
ALTER TABLE `lupo_tasks` MODIFY COLUMN `title` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_tasks.created_ymdhis
ALTER TABLE `lupo_tasks` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_tasks.updated_ymdhis
ALTER TABLE `lupo_tasks` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_tasks.is_deleted
ALTER TABLE `lupo_tasks` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_tasks.task_priority
ALTER TABLE `lupo_tasks` MODIFY COLUMN `task_priority` enum('low','normal','high','urgent','critical') NOT NULL DEFAULT 'normal';
-- Fix type mismatch in lupo_tasks.visibility_status
ALTER TABLE `lupo_tasks` MODIFY COLUMN `visibility_status` varchar(32) NOT NULL DEFAULT 'active';

-- Fix type mismatch in lupo_thread_metadata.thread_metadata_id
ALTER TABLE `lupo_thread_metadata` MODIFY COLUMN `thread_metadata_id` bigint NOT NULL;
-- Fix type mismatch in lupo_thread_metadata.dialog_thread_id
ALTER TABLE `lupo_thread_metadata` MODIFY COLUMN `dialog_thread_id` bigint NOT NULL;
-- Fix type mismatch in lupo_thread_metadata.metadata_key
ALTER TABLE `lupo_thread_metadata` MODIFY COLUMN `metadata_key` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_thread_metadata.metadata_type
ALTER TABLE `lupo_thread_metadata` MODIFY COLUMN `metadata_type` varchar(64) NOT NULL DEFAULT 'string';
-- Fix type mismatch in lupo_thread_metadata.created_ymdhis
ALTER TABLE `lupo_thread_metadata` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_thread_metadata.updated_ymdhis
ALTER TABLE `lupo_thread_metadata` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_thread_metadata.created_by_actor_id
ALTER TABLE `lupo_thread_metadata` MODIFY COLUMN `created_by_actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_thread_metadata.is_deleted
ALTER TABLE `lupo_thread_metadata` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_tickets.ticket_id
ALTER TABLE `lupo_tickets` MODIFY COLUMN `ticket_id` bigint NOT NULL;
-- Fix type mismatch in lupo_tickets.channel_id
ALTER TABLE `lupo_tickets` MODIFY COLUMN `channel_id` bigint NOT NULL;
-- Fix type mismatch in lupo_tickets.actor_id
ALTER TABLE `lupo_tickets` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_tickets.status
ALTER TABLE `lupo_tickets` MODIFY COLUMN `status` varchar(64) NOT NULL DEFAULT 'open';
-- Fix type mismatch in lupo_tickets.priority
ALTER TABLE `lupo_tickets` MODIFY COLUMN `priority` varchar(64) NOT NULL DEFAULT 'medium';
-- Fix type mismatch in lupo_tickets.subject
ALTER TABLE `lupo_tickets` MODIFY COLUMN `subject` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_tickets.created_ymdhis
ALTER TABLE `lupo_tickets` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_tickets.updated_ymdhis
ALTER TABLE `lupo_tickets` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_tickets.is_deleted
ALTER TABLE `lupo_tickets` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_ticket_messages.ticket_message_id
ALTER TABLE `lupo_ticket_messages` MODIFY COLUMN `ticket_message_id` bigint NOT NULL;
-- Fix type mismatch in lupo_ticket_messages.ticket_id
ALTER TABLE `lupo_ticket_messages` MODIFY COLUMN `ticket_id` bigint NOT NULL;
-- Fix type mismatch in lupo_ticket_messages.actor_id
ALTER TABLE `lupo_ticket_messages` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_ticket_messages.message_text
ALTER TABLE `lupo_ticket_messages` MODIFY COLUMN `message_text` text NOT NULL;
-- Fix type mismatch in lupo_ticket_messages.created_ymdhis
ALTER TABLE `lupo_ticket_messages` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_ticket_messages.is_deleted
ALTER TABLE `lupo_ticket_messages` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_truth_answers.truth_answer_id
ALTER TABLE `lupo_truth_answers` MODIFY COLUMN `truth_answer_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_truth_answers.truth_question_id
ALTER TABLE `lupo_truth_answers` MODIFY COLUMN `truth_question_id` bigint NOT NULL;
-- Fix type mismatch in lupo_truth_answers.actor_id
ALTER TABLE `lupo_truth_answers` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_truth_answers.confidence
ALTER TABLE `lupo_truth_answers` MODIFY COLUMN `confidence` decimal(5,2) DEFAULT 0.00;
-- Fix type mismatch in lupo_truth_answers.evidence_count
ALTER TABLE `lupo_truth_answers` MODIFY COLUMN `evidence_count` int DEFAULT 0;
-- Fix type mismatch in lupo_truth_answers.source_count
ALTER TABLE `lupo_truth_answers` MODIFY COLUMN `source_count` int DEFAULT 0;
-- Fix type mismatch in lupo_truth_answers.status
ALTER TABLE `lupo_truth_answers` MODIFY COLUMN `status` varchar(64) DEFAULT 'active';
-- Fix type mismatch in lupo_truth_answers.created_ymdhis
ALTER TABLE `lupo_truth_answers` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_truth_answers.updated_ymdhis
ALTER TABLE `lupo_truth_answers` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_truth_answers.is_deleted
ALTER TABLE `lupo_truth_answers` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_truth_answers.evidence_score
ALTER TABLE `lupo_truth_answers` MODIFY COLUMN `evidence_score` decimal(5,2) DEFAULT 0.00;
-- Fix type mismatch in lupo_truth_answers.contradiction_flag
ALTER TABLE `lupo_truth_answers` MODIFY COLUMN `contradiction_flag` tinyint DEFAULT 0;
-- Fix type mismatch in lupo_truth_answers.likes_count
ALTER TABLE `lupo_truth_answers` MODIFY COLUMN `likes_count` bigint DEFAULT 0;

-- Fix type mismatch in lupo_truth_knowledge.truth_id
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `truth_id` bigint NOT NULL;
-- Fix type mismatch in lupo_truth_knowledge.truth_type
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `truth_type` varchar(32) NOT NULL;
-- Fix type mismatch in lupo_truth_knowledge.actor_id
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `actor_id` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_truth_knowledge.source_title
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `source_title` varchar(255) DEFAULT '';
-- Fix type mismatch in lupo_truth_knowledge.qtype
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `qtype` varchar(50) DEFAULT 'unknown';
-- Fix type mismatch in lupo_truth_knowledge.status
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `status` varchar(64) DEFAULT 'active';
-- Fix type mismatch in lupo_truth_knowledge.evidence_type
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `evidence_type` varchar(50) DEFAULT '';
-- Fix type mismatch in lupo_truth_knowledge.source_type
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `source_type` varchar(50) DEFAULT '';
-- Fix type mismatch in lupo_truth_knowledge.relation_type
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `relation_type` varchar(50) DEFAULT '';
-- Fix type mismatch in lupo_truth_knowledge.format
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `format` varchar(64) DEFAULT 'text';
-- Fix type mismatch in lupo_truth_knowledge.confidence_score
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `confidence_score` decimal(5,2) DEFAULT 0.00;
-- Fix type mismatch in lupo_truth_knowledge.evidence_score
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `evidence_score` decimal(5,2) DEFAULT 0.00;
-- Fix type mismatch in lupo_truth_knowledge.weight_score
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `weight_score` decimal(5,2) DEFAULT 0.00;
-- Fix type mismatch in lupo_truth_knowledge.reliability_score
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `reliability_score` decimal(5,2) DEFAULT 0.00;
-- Fix type mismatch in lupo_truth_knowledge.importance_score
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `importance_score` decimal(5,2) DEFAULT 0.00;
-- Fix type mismatch in lupo_truth_knowledge.sort_num
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `sort_num` int DEFAULT 0;
-- Fix type mismatch in lupo_truth_knowledge.view_count
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `view_count` bigint DEFAULT 0;
-- Fix type mismatch in lupo_truth_knowledge.likes_count
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `likes_count` bigint DEFAULT 0;
-- Fix type mismatch in lupo_truth_knowledge.shares_count
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `shares_count` bigint DEFAULT 0;
-- Fix type mismatch in lupo_truth_knowledge.answer_count
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `answer_count` bigint DEFAULT 0;
-- Fix type mismatch in lupo_truth_knowledge.contradiction_flag
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `contradiction_flag` tinyint DEFAULT 0;
-- Fix type mismatch in lupo_truth_knowledge.is_featured
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `is_featured` tinyint DEFAULT 0;
-- Fix type mismatch in lupo_truth_knowledge.is_verified
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `is_verified` tinyint DEFAULT 0;
-- Fix type mismatch in lupo_truth_knowledge.default_collection_id
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `default_collection_id` bigint DEFAULT 0;
-- Fix type mismatch in lupo_truth_knowledge.created_ymdhis
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_truth_knowledge.updated_ymdhis
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_truth_knowledge.is_deleted
ALTER TABLE `lupo_truth_knowledge` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_unified_log.log_id
ALTER TABLE `lupo_unified_log` MODIFY COLUMN `log_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_unified_log.log_type
ALTER TABLE `lupo_unified_log` MODIFY COLUMN `log_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_unified_log.log_level
ALTER TABLE `lupo_unified_log` MODIFY COLUMN `log_level` varchar(32) NOT NULL DEFAULT 'info';
-- Fix type mismatch in lupo_unified_log.log_message
ALTER TABLE `lupo_unified_log` MODIFY COLUMN `log_message` text NOT NULL;
-- Fix type mismatch in lupo_unified_log.created_ymdhis
ALTER TABLE `lupo_unified_log` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;

-- Fix type mismatch in lupo_uploads.upload_id
ALTER TABLE `lupo_uploads` MODIFY COLUMN `upload_id` bigint NOT NULL;
-- Fix type mismatch in lupo_uploads.actor_id
ALTER TABLE `lupo_uploads` MODIFY COLUMN `actor_id` bigint NOT NULL;
-- Fix type mismatch in lupo_uploads.original_filename
ALTER TABLE `lupo_uploads` MODIFY COLUMN `original_filename` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_uploads.stored_filename
ALTER TABLE `lupo_uploads` MODIFY COLUMN `stored_filename` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_uploads.file_extension
ALTER TABLE `lupo_uploads` MODIFY COLUMN `file_extension` varchar(16) NOT NULL;
-- Fix type mismatch in lupo_uploads.mime_type
ALTER TABLE `lupo_uploads` MODIFY COLUMN `mime_type` varchar(128) NOT NULL;
-- Fix type mismatch in lupo_uploads.file_size_bytes
ALTER TABLE `lupo_uploads` MODIFY COLUMN `file_size_bytes` bigint NOT NULL;
-- Fix type mismatch in lupo_uploads.storage_path
ALTER TABLE `lupo_uploads` MODIFY COLUMN `storage_path` varchar(512) NOT NULL;
-- Fix type mismatch in lupo_uploads.created_ymdhis
ALTER TABLE `lupo_uploads` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_uploads.updated_ymdhis
ALTER TABLE `lupo_uploads` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_uploads.is_deleted
ALTER TABLE `lupo_uploads` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_visits.visit_id
ALTER TABLE `lupo_visits` MODIFY COLUMN `visit_id` bigint NOT NULL auto_increment;
-- Fix type mismatch in lupo_visits.created_ymdhis
ALTER TABLE `lupo_visits` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_visits.is_processed
ALTER TABLE `lupo_visits` MODIFY COLUMN `is_processed` tinyint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_visits.is_deleted
ALTER TABLE `lupo_visits` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;

-- Fix type mismatch in lupo_world_registry.world_id
ALTER TABLE `lupo_world_registry` MODIFY COLUMN `world_id` bigint NOT NULL;
-- Fix type mismatch in lupo_world_registry.world_key
ALTER TABLE `lupo_world_registry` MODIFY COLUMN `world_key` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_world_registry.world_type
ALTER TABLE `lupo_world_registry` MODIFY COLUMN `world_type` varchar(64) NOT NULL;
-- Fix type mismatch in lupo_world_registry.world_label
ALTER TABLE `lupo_world_registry` MODIFY COLUMN `world_label` varchar(255) NOT NULL;
-- Fix type mismatch in lupo_world_registry.created_ymdhis
ALTER TABLE `lupo_world_registry` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
-- Fix type mismatch in lupo_world_registry.updated_ymdhis
ALTER TABLE `lupo_world_registry` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
-- Fix type mismatch in lupo_world_registry.is_active
ALTER TABLE `lupo_world_registry` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;

```

---
*Database Schema Drift Report 4.0.24*
