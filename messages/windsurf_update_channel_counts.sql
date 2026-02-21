-- ============================================================
-- Grounded SQL Updates for Channel Count Synchronization
-- ============================================================

-- 1. Update Channel 42's message_count (adjust if different count)
UPDATE lupo_dialog_channels
SET message_count = 174
WHERE channel_id = 42;

-- 2. Verify both channels
SELECT channel_id, message_count
FROM lupo_dialog_channels
WHERE channel_id IN (42, 420);

-- 3. Optional: Verify actual message totals match stored counts
SELECT channel_id, COUNT(*) AS actual_count
FROM lupo_dialog_messages
WHERE channel_id IN (42, 420)
GROUP BY channel_id;
