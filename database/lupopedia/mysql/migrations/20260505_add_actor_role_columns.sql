-- Migration: Add agent role and context columns to actors
-- PRD: 15_A-i_ACTORS.md section 6
-- Version: 4.1.7
-- Date: 2026-05-05

ALTER TABLE {{prefix}}actors ADD COLUMN agent_role VARCHAR(32) NOT NULL DEFAULT 'watcher';
ALTER TABLE {{prefix}}actors ADD COLUMN agent_blueprint_path VARCHAR(512) NULL;
ALTER TABLE {{prefix}}actors ADD COLUMN current_channel_key VARCHAR(255) NULL;
ALTER TABLE {{prefix}}actors ADD COLUMN current_thread_id VARCHAR(255) NULL;

CREATE INDEX {{prefix}}actors_idx_role ON {{prefix}}actors (agent_role);
CREATE INDEX {{prefix}}actors_idx_channel ON {{prefix}}actors (current_channel_key);
