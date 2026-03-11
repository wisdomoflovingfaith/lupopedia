-- Migration: Add faucet_class to lupo_agent_faucets (4.0.69).
-- Purpose: Distinguish IDE faucets (Cursor, Kiro, Antigravity, Windsurf) from LLM faucets (OpenAI API, DeepSeek API).
-- Doctrine: ActorFaucetOntology.md. Values: 'ide' | 'llm' | NULL (legacy).
-- Idempotent: use IF NOT EXISTS / check before ALTER where supported.

-- Add column (MySQL/MariaDB: no IF NOT EXISTS for columns; run once)
ALTER TABLE lupo_agent_faucets ADD COLUMN faucet_class varchar(32) DEFAULT NULL;

-- Index for filtering by faucet class
CREATE INDEX lupo_agent_faucets_idx_faucet_class ON lupo_agent_faucets (faucet_class);

-- Record migration (use INSERT IGNORE with unique version to avoid duplicate)
INSERT IGNORE INTO lupo_schema_migrations (schema_migration_id, version, name, applied_ymdhis)
VALUES (2026031002, '20260310_faucet_class', 'faucet_class', 20260310120000);
