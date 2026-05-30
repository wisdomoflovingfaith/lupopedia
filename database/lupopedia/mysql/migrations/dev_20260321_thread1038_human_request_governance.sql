-- Thread 1038 governance alignment (development migration)
-- Scope: Existing 4.0.x installs that already include lupo_human_requests
-- Doctrine note: Canonical source remains install/install_new_lupopedia.sql.

ALTER TABLE lupo_human_requests
  ADD COLUMN request_mode VARCHAR(64) DEFAULT 'single_human';

ALTER TABLE lupo_human_requests
  ADD COLUMN resolved_ymdhis BIGINT DEFAULT 0;

ALTER TABLE lupo_human_requests
  ADD COLUMN expires_ymdhis BIGINT DEFAULT 0;

CREATE INDEX idx_status_expires ON lupo_human_requests(status, expires_ymdhis);

-- Normalize unknown statuses into deterministic lifecycle values.
UPDATE lupo_human_requests
SET status = 'pending'
WHERE status IS NULL OR status = '';

UPDATE lupo_human_requests
SET status = 'draft'
WHERE status = 'queued';

UPDATE lupo_human_requests
SET status = 'resolved'
WHERE status = 'closed';
