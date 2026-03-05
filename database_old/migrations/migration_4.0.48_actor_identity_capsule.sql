-- Migration: Actor Identity Capsule v4.0.48
-- Author: Windsurf IDE (1001)
-- Date: 2026-02-27
-- Purpose: Bridge filesystem-first Actor Directory with Lupopedia Database
-- Table Count Impact: +6 tables (216/222 total - within ceiling)

-- NOTE: Founder-level doctrine requires table ceiling compliance
-- Current: 210 tables, Remaining: 12 slots
-- This migration uses 6 slots, leaving 6 for future features

-- =====================================================
-- NEW TABLES FOR ACTOR IDENTITY CAPSULE SYSTEM
-- =====================================================

-- Table 1: lupo_actor_history
-- Stores structured actor achievement and contribution history
CREATE TABLE lupo_actor_history (
    history_id BIGINT NOT NULL,
    actor_id BIGINT NOT NULL,
    achievement_id VARCHAR(100),
    title VARCHAR(255) NOT NULL,
    description TEXT,
    impact TEXT,
    date_ymdhis BIGINT NOT NULL DEFAULT 0,
    channel_id BIGINT,
    tags JSON,
    metrics JSON,
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT
);

-- Table 2: lupo_actor_relationship_rules
-- Defines governance rules for actor interactions
CREATE TABLE lupo_actor_relationship_rules (
    rule_id BIGINT NOT NULL,
    source_actor_id BIGINT NOT NULL,
    target_actor_id BIGINT NOT NULL,
    relationship_type VARCHAR(100) NOT NULL,
    rule_type VARCHAR(50) NOT NULL,
    conditions JSON,
    actions JSON,
    weight FLOAT DEFAULT 1,
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT
);

-- Table 3: lupo_capability_usage
-- Tracks actor capability utilization and performance
CREATE TABLE lupo_capability_usage (
    usage_id BIGINT NOT NULL,
    actor_id BIGINT NOT NULL,
    capability VARCHAR(100) NOT NULL,
    usage_count BIGINT DEFAULT 0,
    success_rate FLOAT DEFAULT 1,
    avg_response_time_ms INT DEFAULT 0,
    last_used_ymdhis BIGINT DEFAULT 0,
    performance_metrics JSON,
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT
);

-- Table 4: lupo_llm_performance
-- Monitors LLM module performance across actors
CREATE TABLE lupo_llm_performance (
    performance_id BIGINT NOT NULL,
    actor_id BIGINT NOT NULL,
    llm_module VARCHAR(100) NOT NULL,
    provider VARCHAR(50),
    total_tokens BIGINT DEFAULT 0,
    avg_response_time_ms INT DEFAULT 0,
    success_rate FLOAT DEFAULT 1,
    cost_per_1k_tokens DECIMAL(10,4) DEFAULT 0.0000,
    quality_score FLOAT DEFAULT 1,
    last_used_ymdhis BIGINT DEFAULT 0,
    performance_data JSON,
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT
);

-- Table 5: lupo_federated_trust
-- Manages trust relationships between federated nodes
CREATE TABLE lupo_federated_trust (
    trust_id BIGINT NOT NULL,
    source_node_id BIGINT NOT NULL,
    target_node_id BIGINT NOT NULL,
    trust_level FLOAT DEFAULT 0.5,
    trust_type VARCHAR(50) NOT NULL,
    capabilities JSON,
    restrictions JSON,
    last_verified_ymdhis BIGINT DEFAULT 0,
    verification_method VARCHAR(100),
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT
);

-- Table 6: lupo_session_recovery
-- Enables session state recovery across restarts
CREATE TABLE lupo_session_recovery (
    recovery_id BIGINT NOT NULL,
    actor_id BIGINT NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    session_data JSON,
    state_snapshot JSON,
    context_data JSON,
    last_activity_ymdhis BIGINT DEFAULT 0,
    recovery_attempts INT DEFAULT 0,
    max_recovery_attempts INT DEFAULT 3,
    created_ymdhis BIGINT NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT,
    is_deleted TINYINT NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT
);

-- =====================================================
-- ENHANCEMENTS TO EXISTING TABLES
-- =====================================================

-- Add filesystem synchronization columns to lupo_actors
ALTER TABLE lupo_actors ADD COLUMN actor_root_path VARCHAR(512) DEFAULT 'actors/{actor_id}';
ALTER TABLE lupo_actors ADD COLUMN who_json_sync_status VARCHAR(64) DEFAULT 'pending';
ALTER TABLE lupo_actors ADD COLUMN last_sync_ymdhis BIGINT DEFAULT 0;

-- =====================================================
-- INDEXES FOR NEW TABLES
-- =====================================================

-- Indexes for lupo_actor_history
CREATE INDEX lupo_actor_history_idx_actor_id ON lupo_actor_history(actor_id);
CREATE INDEX lupo_actor_history_idx_date_ymdhis ON lupo_actor_history(date_ymdhis);
CREATE INDEX lupo_actor_history_idx_channel_id ON lupo_actor_history(channel_id);
CREATE INDEX lupo_actor_history_idx_is_deleted ON lupo_actor_history(is_deleted);

-- Indexes for lupo_actor_relationship_rules
CREATE INDEX lupo_actor_relationship_rules_idx_source_target ON lupo_actor_relationship_rules(source_actor_id, target_actor_id);
CREATE INDEX lupo_actor_relationship_rules_idx_relationship_type ON lupo_actor_relationship_rules(relationship_type);
CREATE INDEX lupo_actor_relationship_rules_idx_rule_type ON lupo_actor_relationship_rules(rule_type);
CREATE INDEX lupo_actor_relationship_rules_idx_is_deleted ON lupo_actor_relationship_rules(is_deleted);

-- Indexes for lupo_capability_usage
CREATE INDEX lupo_capability_usage_idx_actor_capability ON lupo_capability_usage(actor_id, capability);
CREATE INDEX lupo_capability_usage_idx_capability ON lupo_capability_usage(capability);
CREATE INDEX lupo_capability_usage_idx_last_used ON lupo_capability_usage(last_used_ymdhis);
CREATE INDEX lupo_capability_usage_idx_is_deleted ON lupo_capability_usage(is_deleted);

-- Indexes for lupo_llm_performance
CREATE INDEX lupo_llm_performance_idx_actor_module ON lupo_llm_performance(actor_id, llm_module);
CREATE INDEX lupo_llm_performance_idx_provider ON lupo_llm_performance(provider);
CREATE INDEX lupo_llm_performance_idx_last_used ON lupo_llm_performance(last_used_ymdhis);
CREATE INDEX lupo_llm_performance_idx_is_deleted ON lupo_llm_performance(is_deleted);

-- Indexes for lupo_federated_trust
CREATE INDEX lupo_federated_trust_idx_source_target ON lupo_federated_trust(source_node_id, target_node_id);
CREATE INDEX lupo_federated_trust_idx_trust_type ON lupo_federated_trust(trust_type);
CREATE INDEX lupo_federated_trust_idx_last_verified ON lupo_federated_trust(last_verified_ymdhis);
CREATE INDEX lupo_federated_trust_idx_is_deleted ON lupo_federated_trust(is_deleted);

-- Indexes for lupo_session_recovery
CREATE INDEX lupo_session_recovery_idx_actor_id ON lupo_session_recovery(actor_id);
CREATE INDEX lupo_session_recovery_idx_session_id ON lupo_session_recovery(session_id);
CREATE INDEX lupo_session_recovery_idx_last_activity ON lupo_session_recovery(last_activity_ymdhis);
CREATE INDEX lupo_session_recovery_idx_is_deleted ON lupo_session_recovery(is_deleted);

-- =====================================================
-- DOCTRINE COMPLIANCE NOTES
-- =====================================================

/*
1. No foreign keys enforced (database is dumb storage)
2. All timestamps are BIGINT YYYYMMDDHHIISS UTC format
3. Soft deletes implemented with is_deleted/deleted_ymdhis
4. Integer types only (no UNSIGNED, no BOOLEAN)
5. JSON fields for flexible metadata storage
6. Explicit column inserts required (no DEFAULT values for critical fields)
7. PostgreSQL/MySQL/MariaDB compatibility maintained
8. Table ceiling respected: 216/222 tables (6 slots remaining)
9. Filesystem-first design: actors/ directory is source of truth
10. Identity capsule portability: full actor reconstruction from directory
*/
