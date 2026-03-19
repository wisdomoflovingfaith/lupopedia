<?php
/**
 * ToonSchemaCache
 * Loads and caches TOON schema files for bounded-authority validation.
 * PHP 5.6+ compatible (no namespaces, no typed properties).
 */

class ToonSchemaCache
{
    /** @var array */
    private $cache = array();

    /**
     * Load a TOON file for a table (e.g. lupo_metadata.toon) and parse it.
     * Cache key includes mtime to guarantee invalidation on TOON changes.
     *
     * @param string $toonDir
     * @param string $tableName
     * @return array|null Parsed TOON array or null on failure.
     */
    public function loadToonTable($toonDir, $tableName)
    {
        $toonDir = rtrim($toonDir, DIRECTORY_SEPARATOR);
        $toonPath = $toonDir . DIRECTORY_SEPARATOR . $tableName . '.toon';
        if (!is_file($toonPath)) {
            return null;
        }
        $mtime = @filemtime($toonPath);
        if ($mtime === false) {
            $mtime = 0;
        }

        $cacheKey = $toonPath . ':' . (string)$mtime;
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $raw = @file_get_contents($toonPath);
        if ($raw === false) {
            return null;
        }

        if (!function_exists('yaml_parse')) {
            return null;
        }

        $decoded = @yaml_parse($raw);
        if (!is_array($decoded)) {
            return null;
        }

        $this->cache[$cacheKey] = $decoded;
        return $decoded;
    }

    /**
     * Extract field/column names from a TOON structure.
     * TOON "fields" typically looks like: '`column_name` bigint NOT NULL'
     *
     * @param array $toon
     * @return array list of column names as strings
     */
    public function extractFieldNames($toon)
    {
        if (!is_array($toon) || !isset($toon['fields']) || !is_array($toon['fields'])) {
            return array();
        }

        $out = array();
        foreach ($toon['fields'] as $f) {
            if (!is_string($f)) {
                continue;
            }
            if (preg_match('/`([^`]+)`/', $f, $m)) {
                $out[] = $m[1];
            } else {
                // Fallback: first token before whitespace
                $parts = preg_split('/\s+/', trim($f));
                if (!empty($parts[0])) {
                    $out[] = trim($parts[0], '`');
                }
            }
        }
        return $out;
    }
}

