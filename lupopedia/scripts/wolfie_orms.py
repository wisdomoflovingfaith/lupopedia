def select_one_from_integration_test_results(db):
    sql = """
        SELECT
            test_result_id,
            test_suite,
            test_case,
            expected_result,
            actual_result,
            status,
            error_message,
            execution_time_ms,
            created_ymdhis
        FROM integration_test_results
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_actor_actions(db):
    sql = """
        SELECT
            actor_action_id,
            actor_id,
            action_type,
            entity_type,
            entity_id,
            description,
            metadata_json,
            created_ymdhis
        FROM lupo_actor_actions
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_actor_capabilities(db):
    sql = """
        SELECT
            actor_capability_id,
            actor_id,
            domain_id,
            capability_key,
            capability_description,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis,
            scope_limitation,
            max_calls_per_hour,
            requires_approval,
            approval_agent_id
        FROM lupo_actor_capabilities
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_actor_channel_roles(db):
    sql = """
        SELECT
            actor_channel_role_id,
            actor_id,
            channel_id,
            role_key,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis,
            handshake_metadata_json,
            awareness_snapshot_json,
            protocol_completion_status,
            protocol_version,
            join_sequence_step,
            handshake_completed_ymdhis,
            awareness_completed_ymdhis,
            cjp_completed_ymdhis
        FROM lupo_actor_channel_roles
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_actor_channels(db):
    sql = """
        SELECT
            actor_channel_id,
            channel_id,
            actor_id,
            channel_type,
            channel_name,
            description,
            metadata_json,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_actor_channels
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_actor_collections(db):
    sql = """
        SELECT
            actor_collection_id,
            actor_id,
            collection_name,
            collection_type,
            description,
            metadata_json,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_actor_collections
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_actor_conflicts(db):
    sql = """
        SELECT
            actor_conflict_id,
            actor_id,
            conflict_type,
            conflict_description,
            resolution_status,
            metadata_json,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_actor_conflicts
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_actor_departments(db):
    sql = """
        SELECT
            actor_department_id,
            department_name,
            description,
            parent_department_id,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_actor_departments
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_actor_edges(db):
    sql = """
        SELECT
            actor_edge_id,
            source_actor_id,
            target_actor_id,
            edge_type,
            edge_weight,
            metadata_json,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_actor_edges
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_actor_group_membership(db):
    sql = """
        SELECT
            actor_group_membership_id,
            actor_id,
            group_id,
            role,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_actor_group_membership
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_actor_handshakes(db):
    sql = """
        SELECT
            actor_handshake_id,
            actor_id,
            handshake_type,
            handshake_data,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_actor_handshakes
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_actor_meta(db):
    sql = """
        SELECT
            actor_meta_id,
            actor_id,
            meta_key,
            meta_value,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_actor_meta
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_actor_moods(db):
    sql = """
        SELECT
            actor_mood_id,
            actor_id,
            mood_type,
            mood_intensity,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_actor_moods
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_actor_properties(db):
    sql = """
        SELECT
            actor_property_id,
            actor_id,
            property_key,
            property_value,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_actor_properties
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_actor_reply_templates(db):
    sql = """
        SELECT
            actor_reply_template_id,
            actor_id,
            template_name,
            template_content,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_actor_reply_templates
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_actor_roles(db):
    sql = """
        SELECT
            actor_role_id,
            role_name,
            description,
            permissions,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_actor_roles
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_actors(db):
    sql = """
        SELECT
            actor_id,
            actor_type,
            actor_name,
            description,
            metadata_json,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_actors
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_agent_context_snapshots(db):
    sql = """
        SELECT
            agent_context_snapshot_id,
            agent_id,
            context_data,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_agent_context_snapshots
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_agent_dependencies(db):
    sql = """
        SELECT
            agent_dependency_id,
            agent_id,
            dependency_type,
            dependency_data,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_agent_dependencies
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_agent_external_events(db):
    sql = """
        SELECT
            agent_external_event_id,
            agent_id,
            event_type,
            event_data,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_agent_external_events
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_agent_faucet_credentials(db):
    sql = """
        SELECT
            agent_faucet_credential_id,
            agent_id,
            credential_type,
            credential_data,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_agent_faucet_credentials
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_agent_faucets(db):
    sql = """
        SELECT
            agent_faucet_id,
            agent_id,
            faucet_type,
            faucet_data,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_agent_faucets
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_agent_heartbeats(db):
    sql = """
        SELECT
            agent_heartbeat_id,
            agent_id,
            heartbeat_data,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_agent_heartbeats
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_agent_properties(db):
    sql = """
        SELECT
            agent_property_id,
            agent_id,
            property_key,
            property_value,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_agent_properties
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_agent_registry(db):
    sql = """
        SELECT
            agent_registry_id,
            agent_id,
            registry_type,
            registry_data,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_agent_registry
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_agent_tool_calls(db):
    sql = """
        SELECT
            agent_tool_call_id,
            agent_id,
            tool_name,
            tool_parameters,
            tool_result,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_agent_tool_calls
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_agent_versions(db):
    sql = """
        SELECT
            agent_version_id,
            agent_id,
            version_number,
            version_data,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_agent_versions
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_agents(db):
    sql = """
        SELECT
            agent_id,
            agent_type,
            agent_name,
            description,
            metadata_json,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_agents
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_analytics_campaign_vars_daily(db):
    sql = """
        SELECT
            analytics_campaign_var_daily_id,
            campaign_id,
            var_name,
            var_value,
            date_ymd,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_analytics_campaign_vars_daily
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_analytics_campaign_vars_monthly(db):
    sql = """
        SELECT
            analytics_campaign_var_monthly_id,
            campaign_id,
            var_name,
            var_value,
            date_ym,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_analytics_campaign_vars_monthly
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_analytics_referers_daily(db):
    sql = """
        SELECT
            analytics_referer_daily_id,
            referer_url,
            visit_count,
            date_ymd,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_analytics_referers_daily
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_analytics_referers_monthly(db):
    sql = """
        SELECT
            analytics_referer_monthly_id,
            referer_url,
            visit_count,
            date_ym,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_analytics_referers_monthly
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_analytics_visits(db):
    sql = """
        SELECT
            analytics_visit_id,
            visit_id,
            session_id,
            ip_address,
            user_agent,
            referer_url,
            landing_page,
            exit_page,
            visit_duration,
            page_views,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_analytics_visits
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_analytics_visits_daily(db):
    sql = """
        SELECT
            analytics_visit_daily_id,
            date_ymd,
            visit_count,
            unique_visitors,
            page_views,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_analytics_visits_daily
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_analytics_visits_monthly(db):
    sql = """
        SELECT
            analytics_visit_monthly_id,
            date_ym,
            visit_count,
            unique_visitors,
            page_views,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_analytics_visits_monthly
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_anubis_events(db):
    sql = """
        SELECT
            anubis_event_id,
            event_type,
            details,
            created_ymdhis
        FROM lupo_anubis_events
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_anubis_orphaned(db):
    sql = """
        SELECT
            anubis_orphan_id,
            orphan_type,
            orphan_data,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_anubis_orphaned
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_anubis_redirects(db):
    sql = """
        SELECT
            anubis_redirect_id,
            source_path,
            target_path,
            redirect_type,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_anubis_redirects
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_api_clients(db):
    sql = """
        SELECT
            api_client_id,
            client_name,
            client_secret,
            permissions,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_api_clients
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_api_rate_limits(db):
    sql = """
        SELECT
            api_rate_limit_id,
            client_id,
            endpoint,
            limit_count,
            window_minutes,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_api_rate_limits
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_api_token_logs(db):
    sql = """
        SELECT
            api_token_log_id,
            token_id,
            action,
            ip_address,
            user_agent,
            created_ymdhis
        FROM lupo_api_token_logs
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_api_tokens(db):
    sql = """
        SELECT
            api_token_id,
            client_id,
            token_hash,
            expires_at,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_api_tokens
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_api_webhooks(db):
    sql = """
        SELECT
            api_webhook_id,
            client_id,
            webhook_url,
            webhook_events,
            secret_key,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_api_webhooks
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_artifacts(db):
    sql = """
        SELECT
            artifact_id,
            artifact_type,
            artifact_name,
            artifact_data,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_artifacts
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_atoms(db):
    sql = """
        SELECT
            atom_id,
            atom_type,
            atom_name,
            atom_data,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_atoms
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_audit_log(db):
    sql = """
        SELECT
            audit_log_id,
            action,
            table_name,
            record_id,
            old_values,
            new_values,
            actor_id,
            created_ymdhis
        FROM lupo_audit_log
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_auth_providers(db):
    sql = """
        SELECT
            auth_provider_id,
            provider_name,
            provider_type,
            config_data,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_auth_providers
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_auth_users(db):
    sql = """
        SELECT
            auth_user_id,
            username,
            email,
            password_hash,
            provider_id,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_auth_users
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_calibration_impacts(db):
    sql = """
        SELECT
            calibration_impact_id,
            calibration_id,
            impact_type,
            impact_value,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_calibration_impacts
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_channels(db):
    sql = """
        SELECT
            channel_id,
            channel_type,
            channel_name,
            description,
            metadata_json,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_channels
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_content(db):
    sql = """
        SELECT
            content_id,
            content_type,
            title,
            slug,
            body_text,
            metadata_json,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_content
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_content_revisions(db):
    sql = """
        SELECT
            content_revision_id,
            content_id,
            revision_number,
            title,
            body_text,
            author_id,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_content_revisions
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_content_tags(db):
    sql = """
        SELECT
            content_tag_id,
            content_id,
            tag_id,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_content_tags
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_domains(db):
    sql = """
        SELECT
            domain_id,
            domain_name,
            domain_slug,
            description,
            config_json,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_domains
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_emotional_states(db):
    sql = """
        SELECT
            emotional_state_id,
            actor_id,
            emotion_type,
            intensity,
            context_json,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_emotional_states
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_federation_nodes(db):
    sql = """
        SELECT
            federation_node_id,
            node_name,
            node_type,
            endpoint_url,
            auth_token,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_federation_nodes
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_file_attachments(db):
    sql = """
        SELECT
            file_attachment_id,
            content_id,
            filename,
            file_path,
            file_size,
            mime_type,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_file_attachments
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_media(db):
    sql = """
        SELECT
            media_id,
            media_type,
            media_name,
            file_path,
            thumbnail_path,
            metadata_json,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_media
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_migrations(db):
    sql = """
        SELECT
            migration_id,
            migration_name,
            migration_version,
            applied_at,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_migrations
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_permissions(db):
    sql = """
        SELECT
            permission_id,
            permission_key,
            permission_name,
            description,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_permissions
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_roles(db):
    sql = """
        SELECT
            role_id,
            role_name,
            role_slug,
            description,
            permissions_json,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_roles
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_semantic_content(db):
    sql = """
        SELECT
            semantic_content_id,
            content_type,
            title,
            slug,
            body_text,
            metadata_json,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_semantic_content
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_semantic_content_views(db):
    sql = """
        SELECT
            semantic_view_id,
            view_name,
            view_type,
            title,
            description,
            template_path,
            created_ymdhis,
            updated_ymdhis,
            is_active,
            is_default
        FROM lupo_semantic_content_views
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_semantic_navigation_overview(db):
    sql = """
        SELECT
            navigation_id,
            title,
            description,
            navigation_tree,
            content_categories,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_semantic_navigation_overview
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_semantic_relationships(db):
    sql = """
        SELECT
            relationship_id,
            source_content_id,
            target_content_id,
            relationship_type,
            relationship_strength,
            created_ymdhis
        FROM lupo_semantic_relationships
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_semantic_search_index(db):
    sql = """
        SELECT
            search_index_id,
            index_name,
            index_type,
            description,
            index_data,
            created_ymdhis,
            updated_ymdhis,
            is_active
        FROM lupo_semantic_search_index
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_semantic_tags(db):
    sql = """
        SELECT
            tag_id,
            tag_name,
            tag_slug,
            description,
            color,
            created_ymdhis,
            updated_ymdhis,
            is_active
        FROM lupo_semantic_tags
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_semantic_translations(db):
    sql = """
        SELECT
            semantic_translation_id,
            language_code,
            entity_type,
            entity_id,
            translated_text,
            created_ymdhis,
            updated_ymdhis,
            created_by,
            is_deleted,
            deleted_ymdhis
        FROM lupo_semantic_translations
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_sessions(db):
    sql = """
        SELECT
            session_id,
            federation_node_id,
            actor_id,
            ip_address,
            user_agent,
            device_id,
            device_type,
            auth_method,
            auth_provider,
            security_level,
            is_active,
            is_expired,
            is_revoked,
            session_data,
            metadata,
            login_ymdhis,
            last_seen_ymdhis,
            expires_ymdhis,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_sessions
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_system_config(db):
    sql = """
        SELECT
            system_config_id,
            config_key,
            config_value,
            actor_id,
            created_ymdhis,
            updated_ymdhis
        FROM lupo_system_config
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_system_events(db):
    sql = """
        SELECT
            system_event_id,
            event_type,
            event_message,
            event_context,
            actor_id,
            created_ymdhis
        FROM lupo_system_events
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_system_health_snapshots(db):
    sql = """
        SELECT
            health_id,
            table_count,
            table_ceiling,
            schema_state,
            sync_integrity,
            emotional_r,
            emotional_g,
            emotional_b,
            emotional_t,
            created_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_system_health_snapshots
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_system_logs(db):
    sql = """
        SELECT
            log_id,
            event_type,
            severity,
            actor_slug,
            message,
            context_json,
            created_ymdhis,
            is_deleted,
            deleted_ymdhis,
            recursion_depth,
            observation_latency_ms,
            temporal_anomaly_score
        FROM lupo_system_logs
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_temporal_coherence_snapshots(db):
    sql = """
        SELECT
            snapshot_id,
            utc_anchor,
            observation_latency_ms,
            recursion_depth,
            self_awareness_score,
            timestamp_integrity,
            created_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_temporal_coherence_snapshots
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_tldnr(db):
    sql = """
        SELECT
            tldnr_id,
            slug,
            title,
            content_text,
            topic_type,
            topic_reference,
            system_version,
            category,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_tldnr
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_truth_evidence(db):
    sql = """
        SELECT
            truth_evidence_id,
            truth_answer_id,
            actor_id,
            evidence_text,
            evidence_type,
            weight_score,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_truth_evidence
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_truth_questions_map(db):
    sql = """
        SELECT
            truth_questions_map_id,
            truth_question_id,
            object_type,
            object_id,
            actor_id,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_truth_questions_map
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_truth_relations(db):
    sql = """
        SELECT
            truth_relation_id,
            left_object_type,
            left_object_id,
            right_object_type,
            right_object_id,
            relation_type,
            actor_id,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_truth_relations
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_truth_sources(db):
    sql = """
        SELECT
            truth_sourc_id,
            truth_evidence_id,
            actor_id,
            source_url,
            source_title,
            source_type,
            reliability_score,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_truth_sources
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_truth_topics(db):
    sql = """
        SELECT
            truth_topic_id,
            topic_name,
            slug,
            topic_description,
            actor_id,
            weight_score,
            importance_score,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM lupo_truth_topics
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_lupo_user_comments(db):
    sql = """
        SELECT
            user_comment_id,
            domain_id,
            user_id,
            content_id,
            parent_comment_id,
            comment_text,
            user_agent,
            ip_hash,
            is_deleted,
            deleted_ymdhis,
            created_ymdhis,
            updated_ymdhis
        FROM lupo_user_comments
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_migration_alerts(db):
    sql = """
        SELECT
            alert_id,
            batch_id,
            file_id,
            alert_type,
            alert_title,
            alert_message,
            alert_status,
            escalation_level,
            properties,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM migration_alerts
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_migration_batches(db):
    sql = """
        SELECT
            batch_id,
            batch_name,
            batch_status,
            epoch_from,
            epoch_to,
            total_files,
            processed_files,
            failed_files,
            started_ymdhis,
            completed_ymdhis,
            properties,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM migration_batches
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_migration_dependencies(db):
    sql = """
        SELECT
            dependency_id,
            file_id,
            depends_on_file_id,
            dependency_type,
            dependency_description,
            sort_order,
            properties,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM migration_dependencies
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_migration_files(db):
    sql = """
        SELECT
            file_id,
            batch_id,
            file_path,
            file_type,
            file_status,
            file_hash,
            epoch_from,
            epoch_to,
            processing_time_ms,
            error_message,
            retry_count,
            sort_order,
            properties,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM migration_files
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_migration_progress(db):
    sql = """
        SELECT
            progress_id,
            batch_id,
            current_phase,
            total_phases,
            current_phase_index,
            files_in_phase,
            files_completed_in_phase,
            percentage_complete,
            estimated_remaining_seconds,
            properties,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM migration_progress
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_migration_rollback_log(db):
    sql = """
        SELECT
            rollback_id,
            batch_id,
            file_id,
            rollback_classification,
            rollback_reason,
            rollback_status,
            files_affected,
            rollback_time_ms,
            error_message,
            properties,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM migration_rollback_log
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_migration_system_state(db):
    sql = """
        SELECT
            state_id,
            batch_id,
            system_status,
            freeze_reason,
            affected_components,
            user_message,
            properties,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM migration_system_state
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_migration_validation_log(db):
    sql = """
        SELECT
            validation_id,
            batch_id,
            file_id,
            validation_phase,
            validation_type,
            validation_status,
            validation_result,
            execution_time_ms,
            properties,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        FROM migration_validation_log
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_test_performance_metrics(db):
    sql = """
        SELECT
            test_id,
            test_category,
            test_name,
            execution_time_ms,
            memory_usage_mb,
            cpu_usage_percent,
            success_rate,
            error_count,
            created_ymdhis
        FROM test_performance_metrics
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_unified_analytics_paths(db):
    sql = """
        SELECT
            analytics_path_id,
            period,
            visit_content_id,
            exit_content_id,
            metadata_json,
            created_ymdhis
        FROM unified_analytics_paths
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_unified_dialog_messages(db):
    sql = """
        SELECT
            dialog_message_id,
            thread_id,
            actor_id,
            created_ymdhis,
            updated_ymdhis,
            metadata_json,
            body_text
        FROM unified_dialog_messages
        LIMIT 1;
    """
    return db.query(sql)

def select_one_from_unified_truth_items(db):
    sql = """
        SELECT
            truth_item_id,
            item_type,
            name,
            slug,
            body_text,
            metadata_json,
            created_ymdhis
        FROM unified_truth_items
        LIMIT 1;
    """
    return db.query(sql)
