-- Migration: lupo_base_agent_tables.sql
-- Purpose: Add missing tables requested for multi-agent evolution.
-- Doctrine: No FKs, BIGINT UTC timestamps, explicit columns.

CREATE TABLE IF NOT EXISTS lupo_threads (
    thread_id varchar(128) NOT NULL,
    channel_id bigint NOT NULL,
    version varchar(32) NOT NULL DEFAULT '4.0.x',
    title varchar(255) DEFAULT NULL,
    created_ymdhis bigint NOT NULL,
    updated_ymdhis bigint NOT NULL,
    is_deleted tinyint NOT NULL DEFAULT 0,
    PRIMARY KEY (thread_id)
);

CREATE TABLE IF NOT EXISTS lupo_messages (
    message_id bigint NOT NULL,
    thread_id varchar(128) NOT NULL,
    actor_id bigint NOT NULL,
    content mediumtext NOT NULL,
    created_ymdhis bigint NOT NULL,
    updated_ymdhis bigint NOT NULL,
    is_deleted tinyint NOT NULL DEFAULT 0,
    PRIMARY KEY (message_id)
);

CREATE INDEX IF NOT EXISTS lupo_threads_idx_channel ON lupo_threads (channel_id);
CREATE INDEX IF NOT EXISTS lupo_messages_idx_thread ON lupo_messages (thread_id);
CREATE INDEX IF NOT EXISTS lupo_messages_idx_actor ON lupo_messages (actor_id);
