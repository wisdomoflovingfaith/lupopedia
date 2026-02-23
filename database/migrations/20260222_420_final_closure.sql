-- 20260222_420_final_closure.sql
-- Purpose: Final 4.0.29 closure for Channel 420
-- Type: Migration (atomic, idempotent, schema-correct)
-- Doctrine-aligned: No mythology, no recursion, no fallback channels

START TRANSACTION;

-- 1) Insert final declaration message (idempotent)
INSERT INTO lupo_dialog_messages (
    dialog_message_id,
    dialog_thread_id,
    channel_id,
    from_actor_id,
    message_text,
    message_type,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
)
SELECT
    67, 1, 420, 420,
    'CAPTAIN STONED LUPOPEDIA WOLFIE — FINAL DECLARATION BEFORE CHANNEL 420 ARCHIVE.',
    'final',
    20260222000000,
    20260222000000,
    0
WHERE NOT EXISTS (
    SELECT 1 FROM lupo_dialog_messages
    WHERE dialog_message_id = 67 AND channel_id = 420
);

-- 2) Archive Channel 420
UPDATE lupo_channels
SET 
    channel_status = 'archived',
    featured = 1,
    updated_ymdhis = 20260222000000
WHERE channel_id = 420;

COMMIT;
