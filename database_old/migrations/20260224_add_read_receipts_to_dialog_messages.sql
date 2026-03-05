-- Add read receipt columns to dialog messages
-- Version: 4.0.42
-- Required for Thread Dialog System traceability

USE lupopedia;

ALTER TABLE lupo_dialog_doctrine 
ADD COLUMN read_by_actor_id bigint NOT NULL DEFAULT 0 COMMENT 'ID of actor who read the message' AFTER to_actor_id,
ADD COLUMN read_by_actor_utc bigint NOT NULL DEFAULT 0 COMMENT 'UTC timestamp when message was read' AFTER read_by_actor_id;

-- Optional: Add indexes for performance if we ever query by read status
CREATE INDEX idx_read_by_actor ON lupo_dialog_doctrine(read_by_actor_id);
CREATE INDEX idx_read_utc ON lupo_dialog_doctrine(read_by_actor_utc);
