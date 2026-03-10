<?php
/**
 * TOON Validator
 *
 * Validates database schema against TOON files without using information_schema.
 * Uses SHOW TABLES and SHOW CREATE TABLE only (shared-hosting safe).
 *
 * @package Lupopedia
 * @version 4.0.68
 */

class ToonValidator
{
    /** @var PDO_DB */
    private $db;
    /** @var string */
    private $table_prefix;
    /** @var string */
    private $toon_dir;

    public function __construct($db = null)
    {
        $this->db = $db ? $db : (class_exists('DatabaseFactory') ? DatabaseFactory::getConnection() : null);
        $this->table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $base = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : (defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : dirname(dirname(__DIR__)));
        $this->toon_dir = $base . DIRECTORY_SEPARATOR . 'lupo-docs' . DIRECTORY_SEPARATOR . 'toons';
    }

    /**
     * Get all tables in database using SHOW TABLES (no information_schema)
     *
     * @return array list of table names
     */
    public function getDatabaseTables()
    {
        if (!$this->db) {
            return array();
        }
        try {
            $stmt = $this->db->query('SHOW TABLES', array());
            $rows = $stmt->fetchAll(PDO::FETCH_NUM);
            $tables = array();
            foreach ($rows as $r) {
                $tables[] = $r[0];
            }
            return $tables;
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Get table structure using SHOW CREATE TABLE (no information_schema)
     *
     * @param string $table
     * @return string CREATE TABLE statement or empty string
     */
    public function getTableStructure($table)
    {
        if (!$this->db) {
            return '';
        }
        try {
            $qtable = $this->db->quoteIdentifier($table);
            $stmt = $this->db->query('SHOW CREATE TABLE ' . $qtable, array());
            $row = $stmt->fetch(PDO::FETCH_NUM);
            return isset($row[1]) ? $row[1] : '';
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Load TOON file for a table (.toon.json in lupo-docs/toons)
     *
     * @param string $table
     * @return array|null decoded JSON or null
     */
    public function loadToonFile($table)
    {
        $file = $this->toon_dir . DIRECTORY_SEPARATOR . $table . '.toon.json';
        if (!file_exists($file)) {
            return null;
        }
        $content = file_get_contents($file);
        $decoded = json_decode($content, true);
        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Check for FOREIGN KEY in CREATE TABLE statement (excludes comments)
     *
     * @param string $create_sql
     * @return int 1 if found, 0 otherwise
     */
    public function checkForeignKeys($create_sql)
    {
        $sql = $this->stripSqlComments($create_sql);
        return preg_match('/FOREIGN\s+KEY/i', $sql) ? 1 : 0;
    }

    /**
     * Count triggers using SHOW TRIGGERS (may not be available on all hosts)
     *
     * @return int
     */
    public function checkTriggers()
    {
        if (!$this->db) {
            return 0;
        }
        try {
            $stmt = $this->db->query('SHOW TRIGGERS', array());
            $rows = $stmt->fetchAll();
            return count($rows);
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Strip SQL comments to avoid false positives in regex checks
     *
     * @param string $sql
     * @return string
     */
    private function stripSqlComments($sql)
    {
        $s = preg_replace('#/\*.*?\*/#s', '', $sql);
        $s = preg_replace('#--[^\n]*#', '', $s);
        return $s;
    }

    /**
     * Count DATETIME/TIMESTAMP column type definitions (excludes comments)
     *
     * @param string $create_sql
     * @return int
     */
    public function checkTimestampColumns($create_sql)
    {
        $sql = $this->stripSqlComments($create_sql);
        preg_match_all('/\b(DATETIME|TIMESTAMP)\s*(?:\([^)]*\))?/i', $sql, $matches);
        return isset($matches[0]) ? count($matches[0]) : 0;
    }

    /**
     * Check for AUTO_INCREMENT in CREATE TABLE statement
     *
     * @param string $create_sql
     * @return int 1 if found, 0 otherwise
     */
    public function checkAutoIncrement($create_sql)
    {
        return preg_match('/AUTO_INCREMENT/i', $create_sql) ? 1 : 0;
    }

    /**
     * Validate entire database against TOON files (no information_schema).
     * Doctrine: AUTO_INCREMENT is allowed (app may supply explicit IDs); report as info only.
     * Triggers reported once globally, not per table.
     *
     * @return array table => [ has_toon, foreign_keys, timestamp_columns ], plus _triggers_global
     */
    public function validateDatabase()
    {
        $tables = $this->getDatabaseTables();
        $results = array();
        $triggerCount = $this->checkTriggers();
        foreach ($tables as $table) {
            $toon = $this->loadToonFile($table);
            $create_sql = $this->getTableStructure($table);
            $results[$table] = array(
                'has_toon' => ($toon !== null),
                'foreign_keys' => $this->checkForeignKeys($create_sql),
                'timestamp_columns' => $this->checkTimestampColumns($create_sql),
            );
        }
        $results['_triggers_global'] = array('count' => $triggerCount);
        return $results;
    }
}
