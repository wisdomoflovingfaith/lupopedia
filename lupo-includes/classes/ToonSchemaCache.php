<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: class
  when_updated: "20260406044907"
  file_path_from_root: "lupo-includes/classes/ToonSchemaCache.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-includes/classes/ToonSchemaCache.php"
  last_modified_utc: "20260406044907"
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "class"
  artifact_kind: "code"
  purpose: "Schema reference JSON cache for bounded authority (canonical lupo-database/lupopedia/json); legacy class name retained for callers"
  tags: ["schema", "json", "deprecated_toon_path", "cache", "prd00"]
---
*/

/**
 * Cache for table schema reference JSON (column list) used by bounded-header authority.
 *
 * @deprecated New work MUST read canonical files under lupo-database/lupopedia/json/
 *             (e.g. lupo_metadata.json). Legacy `.toon` / `.toon.json` trees are deprecated
 *             for new work per PRD 00 section 6. Table docs: lupo-docs/database/lupopedia/tables/active/.
 *             This class name is retained for existing callers; prefer removing direct TOON/YAML paths.
 *             Removal targeted for 4.1.0 packaging goals.
 */
class ToonSchemaCache
{
    /** @var array */
    private $cache = array();

    /** @var string|null */
    private $preferredSchemaDir;

    /**
     * @param string|null $preferredSchemaDir Optional directory containing *.json schema reference files
     *                                        (config may still use legacy key "toon_dir").
     */
    public function __construct($preferredSchemaDir = null)
    {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('DEPRECATED: ToonSchemaCache used – migrate to JSON schema files under lupo-database/lupopedia/json/. This class will be removed in 4.1.0.');
        }
        if ($preferredSchemaDir !== null && $preferredSchemaDir !== '') {
            $this->preferredSchemaDir = rtrim((string)$preferredSchemaDir, '/\\');
        } else {
            $this->preferredSchemaDir = null;
        }
    }

    /**
     * Repository root: parent of lupo-includes.
     *
     * @return string
     */
    private static function repoRoot()
    {
        return dirname(dirname(__DIR__));
    }

    /**
     * Resolve first existing path to {table}.json schema reference.
     *
     * @param string $passedDir Directory from ingest config (may be scope root — then we fall back).
     * @param string $tableName Table base name (e.g. lupo_metadata).
     * @return string|null Absolute path or null.
     */
    private function resolveJsonPath($passedDir, $tableName)
    {
        $tableName = preg_replace('/[^a-z0-9_]/', '', (string)$tableName);
        if ($tableName === '') {
            return null;
        }

        $candidates = array();

        if ($passedDir !== null && $passedDir !== '') {
            $d = rtrim((string)$passedDir, '/\\');
            if (is_dir($d)) {
                $candidates[] = $d . DIRECTORY_SEPARATOR . $tableName . '.json';
            }
        }

        if ($this->preferredSchemaDir !== null && $this->preferredSchemaDir !== '') {
            $d = $this->preferredSchemaDir;
            if (is_dir($d)) {
                $candidates[] = $d . DIRECTORY_SEPARATOR . $tableName . '.json';
            }
        }

        $candidates[] = self::repoRoot() . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR
            . 'lupopedia' . DIRECTORY_SEPARATOR . 'json' . DIRECTORY_SEPARATOR . $tableName . '.json';

        foreach ($candidates as $p) {
            if (is_file($p)) {
                return $p;
            }
        }

        return null;
    }

    /**
     * Load schema reference JSON for a table and parse it.
     * Cache key includes mtime so edits invalidate the cache.
     *
     * Method name retained for callers; implementation uses JSON only (no yaml_parse, no .toon).
     *
     * @param string $schemaDir Directory hint (ingest "toon_dir") or empty; canonical json path is tried last.
     * @param string $tableName e.g. lupo_metadata
     * @return array|null Decoded schema array (expects "fields" list) or null on failure.
     */
    public function loadToonTable($schemaDir, $tableName)
    {
        $jsonPath = $this->resolveJsonPath($schemaDir, $tableName);
        if ($jsonPath === null) {
            return null;
        }

        $mtime = @filemtime($jsonPath);
        if ($mtime === false) {
            $mtime = 0;
        }

        $cacheKey = $jsonPath . ':' . (string)$mtime;
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $raw = @file_get_contents($jsonPath);
        if ($raw === false) {
            return null;
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return null;
        }

        $this->cache[$cacheKey] = $decoded;
        return $decoded;
    }

    /**
     * Extract column names from schema reference structure (JSON or legacy TOON-shaped array).
     * Expects $schema["fields"] as list of strings like '`column_name` bigint NOT NULL'.
     *
     * @param array $schema
     * @return array list of column names as strings
     */
    public function extractFieldNames($schema)
    {
        if (!is_array($schema) || !isset($schema['fields']) || !is_array($schema['fields'])) {
            return array();
        }

        $out = array();
        foreach ($schema['fields'] as $f) {
            if (!is_string($f)) {
                continue;
            }
            if (preg_match('/`([^`]+)`/', $f, $m)) {
                $out[] = $m[1];
            } else {
                $parts = preg_split('/\s+/', trim($f));
                if (!empty($parts[0])) {
                    $out[] = trim($parts[0], '`');
                }
            }
        }
        return $out;
    }
}
