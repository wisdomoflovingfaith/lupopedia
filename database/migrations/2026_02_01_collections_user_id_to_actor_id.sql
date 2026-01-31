-- Migration: Change lupo_collections.user_id to actor_id
-- Date: 2026-02-01
-- Purpose: Align collections table with canonical actor model
-- Doctrine: No foreign keys, no triggers, no stored procedures
-- TOON Source: docs/toons/lupo_collections.toon.json (needs update after this migration)

-- Change user_id column to actor_id in lupo_collections
ALTER TABLE lupo_collections
CHANGE COLUMN user_id actor_id bigint COMMENT 'Owner actor of this collection, if actor-owned';

-- Update index name from idx_user to idx_actor
ALTER TABLE lupo_collections
DROP INDEX idx_user;

ALTER TABLE lupo_collections
ADD INDEX idx_actor (actor_id);

-- Migration complete
-- Note: TOON file docs/toons/lupo_collections.toon.json must be updated to reflect actor_id
