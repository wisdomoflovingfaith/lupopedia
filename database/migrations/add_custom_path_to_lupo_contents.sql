-- Add custom_path to lupo_contents (run via PDO; no Laravel). Run once.
-- Table prefix: from config (LUPO_TABLE_PREFIX / lupopedia-config.php). Shown as lupo_ here; substitute if different.

ALTER TABLE lupo_contents
    ADD COLUMN custom_path VARCHAR(255) NULL
        COMMENT 'Semantic routing override; not a filesystem path'
        AFTER slug;

CREATE UNIQUE INDEX idx_custom_path ON lupo_contents (custom_path);
