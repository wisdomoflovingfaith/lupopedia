-- Migration: Add doctrine boot block storage
-- Date: 2026-02-01
-- Purpose: Store AI Agent Boot Block in database for terminal agents and TOON export
-- Doctrine: No foreign keys, no triggers, no stored procedures, YYYYMMDDHHIISS UTC timestamps

-- Create doctrine blocks table if it doesn't exist
CREATE TABLE IF NOT EXISTS lupo_doctrine_blocks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    block_key VARCHAR(255) NOT NULL,
    block_title VARCHAR(255) NOT NULL,
    block_content LONGTEXT NOT NULL,
    created_ymdhis BIGINT UNSIGNED NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
    updated_ymdhis BIGINT UNSIGNED NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
    UNIQUE KEY unique_block_key (block_key),
    INDEX idx_block_key (block_key),
    INDEX idx_created_ymdhis (created_ymdhis),
    INDEX idx_updated_ymdhis (updated_ymdhis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores doctrine blocks for AI agents and system documentation';

-- Insert AI Agent Boot Block
-- Note: Using current timestamp in YYYYMMDDHHIISS format (UTC)
INSERT INTO lupo_doctrine_blocks (
    block_key,
    block_title,
    block_content,
    created_ymdhis,
    updated_ymdhis
) VALUES (
    'ai_agent_boot_block',
    'Doctrine Boot Block (Required for All AI Agents)',
    'IMPORTANT — Lupopedia uses an ACTOR MODEL:

- actor_id is the primary identity key
- There is no user_id
- All sessions, permissions, ownership, and uploads use actor_id
- auth_user_id is only for human login
- No foreign keys, triggers, or stored procedures
- All timestamps use YYYYMMDDHHIISS in UTC
- Schema changes must come from TOON files in /docs/toons/
- Table limit is 222
- Python = maintenance (scripts/python/, PyMySQL, explicit SQL)
- PHP = runtime only (no schema changes)
- Uploads use SHA256 hash filenames under uploads/{actors,agents,channels,operators}/YYYY/MM/
- LEXA enforces doctrine and boundaries in the gateway

Never introduce user_id.
Never add foreign keys, triggers, or stored procedures.
Never modify schema without TOON source.
Never let PHP perform migrations.',
    UTC_TIMESTAMP() + 0,  -- Converts current UTC timestamp to YYYYMMDDHHIISS integer format
    UTC_TIMESTAMP() + 0   -- Same as created_ymdhis
) ON DUPLICATE KEY UPDATE
    block_content = VALUES(block_content),
    updated_ymdhis = UTC_TIMESTAMP() + 0;

-- Migration complete
-- Table: lupo_doctrine_blocks created
-- Boot block inserted with block_key = 'ai_agent_boot_block'
