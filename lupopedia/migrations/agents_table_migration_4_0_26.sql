-- Agents Table Migration - Version 4.0.26
-- Creates agents table in core schema for identity anchoring

USE lupopedia;

-- agents - Identity anchoring table for agent management
CREATE TABLE agents (
    agent_id BIGINT(14) PRIMARY KEY DEFAULT 0,
    agent_name VARCHAR(100) NOT NULL,
    agent_type ENUM('human', 'ai', 'system', 'service') NOT NULL DEFAULT 'ai',
    agent_persona VARCHAR(100),
    agent_status ENUM('active', 'inactive', 'suspended', 'archived') NOT NULL DEFAULT 'active',
    agent_capabilities JSON,
    agent_preferences JSON,
    agent_signature VARCHAR(50),
    agent_color_rgb VARCHAR(7),
    agent_description TEXT,
    created_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT(14) NOT NULL DEFAULT 0,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT(14) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Captain Wolfie as anchor agent
INSERT INTO agents 
(agent_id, agent_name, agent_type, agent_persona, agent_status, agent_capabilities, agent_preferences, agent_signature, agent_color_rgb, agent_description, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES
(
    1,
    'Captain Wolfie',
    'human',
    'Captain_Wolfie',
    'active',
    '{"architecture": true, "doctrine": true, "migration": true, "identity": true}',
    '{"mood_rgb": "00FF00", "speaker_style": "direct", "communication_mode": "architectural"}',
    '[CW]',
    '00FF00',
    'System architect and kernel authority for Lupopedia semantic operating system',
    20260115172300,
    20260115172300,
    0,
    NULL
);
