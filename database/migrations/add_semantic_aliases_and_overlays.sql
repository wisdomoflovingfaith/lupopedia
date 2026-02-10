-- lupo_aliases and lupo_semantic_overlays (run via PDO; no Laravel).
-- Table prefix: defined in config (LUPO_TABLE_PREFIX / lupopedia-config.php). Shown as lupo_ here; substitute your configured prefix if different.
-- Doctrine: PK = singular table name + _id (never bare "id"); timestamps = BIGINT YmdHis UTC set in PHP; no UNSIGNED.
-- If you already have these tables with PK "id" and created_at TIMESTAMP, run a one-time fix to rename id->alias_id / id->semantic_overlay_id and add created_ymdhis; then drop old columns.

CREATE TABLE IF NOT EXISTS lupo_aliases (
    alias_id bigint NOT NULL,
    slug varchar(255) NOT NULL,
    alias varchar(255) NOT NULL,
    alias_type varchar(50) DEFAULT 'semantic',
    created_ymdhis bigint NOT NULL DEFAULT 0,
    PRIMARY KEY (alias_id),
    UNIQUE KEY uniq_alias (alias),
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE lupo_aliases CHANGE alias_id alias_id bigint NOT NULL AUTO_INCREMENT;

CREATE TABLE IF NOT EXISTS lupo_semantic_overlays (
    semantic_overlay_id bigint NOT NULL,
    slug varchar(255) NOT NULL,
    overlay_key varchar(255) NOT NULL,
    overlay_value text NOT NULL,
    context varchar(255) DEFAULT NULL,
    created_ymdhis bigint NOT NULL DEFAULT 0,
    PRIMARY KEY (semantic_overlay_id),
    INDEX idx_slug (slug),
    INDEX idx_context (context)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE lupo_semantic_overlays CHANGE semantic_overlay_id semantic_overlay_id bigint NOT NULL AUTO_INCREMENT;
