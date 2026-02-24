-- ============================================================
-- ADD FORWARDING COLUMNS TO DIALOG MESSAGES (Lupopedia 4.0.24)
-- ============================================================
-- Purpose: Add X-Lupo-Forwarded-For attribution support to dialog messages
-- Doctrine: PHP 5.3 compatible, PDO_DB only, registry/unregistry doctrine
-- ============================================================

-- Add forwarding attribution columns to lupo_dialog_doctrine
ALTER TABLE lupo_dialog_doctrine 
ADD COLUMN forwarded_by_actor_id bigint DEFAULT NULL COMMENT 'Actor ID that forwarded this message',
ADD COLUMN original_sender_actor_id bigint DEFAULT NULL COMMENT 'Original sender actor ID before forwarding';

-- Add indexes for forwarding columns
CREATE INDEX idx_dialog_messages_forwarded_by_actor_id ON lupo_dialog_doctrine (forwarded_by_actor_id);
CREATE INDEX idx_dialog_messages_original_sender_actor_id ON lupo_dialog_doctrine (original_sender_actor_id);

-- Add foreign key constraints (optional, for data integrity)
-- Note: These may be commented out if FK constraints are not used in this system
-- ALTER TABLE lupo_dialog_doctrine 
-- ADD CONSTRAINT fk_dialog_messages_forwarded_by_actor_id 
-- FOREIGN KEY (forwarded_by_actor_id) REFERENCES lupo_actors(actor_id) 
-- ON DELETE SET NULL;

-- ALTER TABLE lupo_dialog_doctrine 
-- ADD CONSTRAINT fk_dialog_messages_original_sender_actor_id 
-- FOREIGN KEY (original_sender_actor_id) REFERENCES lupo_actors(actor_id) 
-- ON DELETE SET NULL;

-- ============================================================
-- SEED DATA: Forwarding example message
-- ============================================================

SET @now = 20260220000000;

-- Example message showing forwarding attribution
INSERT IGNORE INTO lupo_dialog_doctrine (
    `message_id`, `thread_id`, `actor_id`, `message_type`, `content`, 
    `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`,
    `forwarded_by_actor_id`, `original_sender_actor_id`
) VALUES (
    25, 1003, 2, 'system', 
    'Forwarded message: Original message from DeepSeek LILITH (actor_id 2038) forwarded by Windsurf IDE (actor_id 2) to channel 42. This demonstrates X-Lupo-Forwarded-For attribution headers.', 
    20260220000000, 20260220000000, 0, NULL,
    2, 2038
) ON DUPLICATE KEY UPDATE 
    content = VALUES(content), 
    forwarded_by_actor_id = VALUES(forwarded_by_actor_id), 
    original_sender_actor_id = VALUES(original_sender_actor_id),
    updated_ymdhis = 20260220000000, 
    is_deleted = 0;

-- ============================================================
-- END FORWARDING COLUMNS MIGRATION
-- ============================================================
