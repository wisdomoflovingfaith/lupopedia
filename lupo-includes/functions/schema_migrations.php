<?php
/**
 * Schema migration tracking — check and record one-time migrations in lupo_schema_migrations.
 * Use when running one-time migration SQL so reruns can be detected and prevented.
 *
 * @package Lupopedia
 * @version 4.0.67
 */

if (!function_exists('lupo_schema_migration_applied')) {
    /**
     * Check whether a versioned migration is already recorded.
     *
     * @param PDO_DB $db Database connection (PDO_DB)
     * @param string $version Migration version key (e.g. '20260309' or '20260309_root_doctrine')
     * @param string|null $table_prefix Table prefix (default LUPO_TABLE_PREFIX or 'lupo_')
     * @return bool True if a row exists for this version
     */
    function lupo_schema_migration_applied($db, $version, $table_prefix = null)
    {
        if ($table_prefix === null) {
            $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        }
        $table = $table_prefix . 'schema_migrations';
        $row = $db->fetchRow(
            'SELECT 1 FROM ' . $db->quoteIdentifier($table) . ' WHERE version = :version',
            array('version' => $version)
        );
        return is_array($row) && !empty($row);
    }
}

if (!function_exists('lupo_schema_migration_record')) {
    /**
     * Record a migration as applied (call after successfully running migration SQL).
     * Allocates schema_migration_id via COALESCE(MAX(id),0)+1; no AUTO_INCREMENT.
     *
     * @param PDO_DB $db Database connection (PDO_DB)
     * @param string $version Migration version key (must be unique)
     * @param string $name Human-readable name (e.g. 'root_doctrine_content_channel_actor_apps')
     * @param string|null $table_prefix Table prefix (default LUPO_TABLE_PREFIX or 'lupo_')
     * @return bool True if insert succeeded
     */
    function lupo_schema_migration_record($db, $version, $name, $table_prefix = null)
    {
        if ($table_prefix === null) {
            $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        }
        $table = $table_prefix . 'schema_migrations';
        $applied_ymdhis = gmdate('YmdHis');
        $next_id = $db->fetchOne(
            'SELECT COALESCE(MAX(schema_migration_id), 0) + 1 FROM ' . $db->quoteIdentifier($table),
            array()
        );
        if ($next_id === false || $next_id === null) {
            $next_id = 1;
        }
        $next_id = (int) $next_id;
        $data = array(
            'schema_migration_id' => $next_id,
            'version' => $version,
            'name' => $name,
            'applied_ymdhis' => $applied_ymdhis
        );
        $db->insert($table, $data);
        return true;
    }
}
