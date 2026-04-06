<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: class
  when_updated: "20260406001643"
  file_path_from_root: "lupo-includes/classes/UrlResolver.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-includes/classes/UrlResolver.php"
  last_modified_utc: "20260406001643"
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "class"
  artifact_kind: "routing"
  purpose: "Web path resolution (DB lupo_contents, flip_headers.csv, docs/*.md); path-anchored FS reads; PDO_DB via DatabaseFactory only."
  tags: ["routing", "url", "security", "timestamp_ymdhis", "pdo_db"]
---
*/

/**
 * UrlResolver — Runtime web path resolution (4.0.18).
 *
 * Three-tier source: (1) DB lupo_contents by file_path_from_root/custom_path,
 * (2) exports/flip_headers.csv, (3) parse .md FLIP headers from filesystem.
 * Normalizes slugs per slug_encoding (underscore, plus, percent). Resolves
 * canonical vs alias; redirect vs serve from config.
 *
 * PHP 5.3 compatible: array() only, no ??, no typed properties/return types.
 * DB tier uses DatabaseFactory::getConnection() only (PDO_DB). Filesystem tier
 * anchors reads under repo root (realpath). Request paths use $UNTRUSTED boundary.
 * Doctrine: docs/channels/doctrine/WEB_ROUTING_DOCTRINE_4_0_18.md §2.1, §4.2.
 *
 * @package Lupopedia
 */

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

class UrlResolver {

    /** @var string Table prefix (e.g. lupo_) */
    private $prefix;
    /** @var string Repo root path (LUPOPEDIA_PATH or LUPOPEDIA_ABSPATH) */
    private $repo_root;
    /** @var string|false Normalized realpath of repo root, or false if unavailable */
    private $repo_root_real;
    /** @var string Path to exports/flip_headers.csv */
    private $csv_path;
    /** @var array|null Cached path map from CSV (normalized_path => row); null until loaded */
    private $csv_map;
    /** @var bool When true, log warning on fallback source */
    private $log_fallback;
    /** @var bool Alias paths: true = 302 redirect to canonical; false = serve same content */
    private $alias_redirect;
    /** @var int Default TTL for resolver result cache (seconds) */
    private $cache_ttl;
    /** @var string Directory for file-based cache when APCu unavailable */
    private $cache_dir;

    /**
     * @param string $prefix Table prefix (e.g. lupo_)
     * @param string $repo_root Full path to repo root
     * @param bool $alias_redirect If true, caller should redirect alias to canonical
     * @param bool $log_fallback If true, error_log when using CSV or .md fallback
     */
    public function __construct($prefix, $repo_root, $alias_redirect = true, $log_fallback = true) {
        $this->prefix = $prefix;
        $this->repo_root = rtrim(str_replace('\\', '/', $repo_root), '/');
        $repoOs = str_replace('/', DIRECTORY_SEPARATOR, $this->repo_root);
        $rp = realpath($repoOs);
        $this->repo_root_real = ($rp !== false) ? rtrim(str_replace('\\', '/', $rp), '/') : false;
        $this->csv_path = $this->repo_root . '/exports/flip_headers.csv';
        $this->csv_map = null;
        $this->log_fallback = $log_fallback;
        $this->alias_redirect = $alias_redirect;
        $this->cache_ttl = 3600;
        $this->cache_dir = $this->repo_root . '/cache/resolved';
    }

    /**
     * Reject traversal / null-byte / encoded-dot patterns on raw HTTP-derived input.
     *
     * @param string $raw
     * @return bool
     */
    private function isSafeRequestPath($raw) {
        $s = (string) $raw;
        if ($s === '') {
            return false;
        }
        if (strpos($s, "\0") !== false) {
            return false;
        }
        if (preg_match('/%2e%2e|%2E%2E/i', $s)) {
            return false;
        }
        $dec = rawurldecode($s);
        if (strpos($dec, '..') !== false) {
            return false;
        }
        return true;
    }

    /**
     * True if normalized absolute file path (forward slashes) is inside repo root.
     *
     * @param string $pathRealNormalized
     * @return bool
     */
    private function pathIsUnderRepo($pathRealNormalized) {
        if ($this->repo_root_real === false || $this->repo_root_real === '') {
            return false;
        }
        $base = rtrim((string) $this->repo_root_real, '/');
        $path = rtrim(str_replace('\\', '/', $pathRealNormalized), '/');
        if ($path === $base) {
            return true;
        }
        $need = $base . '/';
        return (strlen($path) > strlen($base) && strpos($path, $need) === 0);
    }

    /**
     * Normalize request path for lookup: trim slashes, no leading/trailing slash.
     *
     * @param string $path Request path (e.g. /doctrine/FLIP/FLIP_DOCTRINE or doctrine/FLIP/FLIP_DOCTRINE)
     * @return string Normalized path (e.g. doctrine/FLIP/FLIP_DOCTRINE)
     */
    public static function normalizePath($path) {
        $path = trim((string) $path, "/ \t\n\r");
        $path = str_replace('\\', '/', $path);
        return $path;
    }

    /**
     * Normalize slug segment per encoding: underscore, plus, percent.
     * Used to compare request slug to stored slug when encoding differs.
     *
     * @param string $slug Slug segment (e.g. FLIP+DOCTRINE or FLIP_DOCTRINE)
     * @param string $encoding One of: underscore, plus, percent
     * @return string Normalized for comparison (e.g. FLIP_DOCTRINE)
     */
    public static function normalizeSlug($slug, $encoding = 'underscore') {
        $slug = trim((string) $slug);
        if ($encoding === 'plus') {
            $slug = str_replace('_', '+', $slug);
            return $slug;
        }
        if ($encoding === 'percent') {
            $slug = str_replace('_', '%20', $slug);
            $slug = str_replace('+', '%20', $slug);
            return $slug;
        }
        $slug = str_replace('+', '_', $slug);
        $slug = str_replace('%20', '_', $slug);
        return $slug;
    }

    /**
     * Resolve a request path to content_id, file_path, and canonical.
     * Results are cached (APCu or file) with auth-aware key. Use invalidateAllCaches() when CSV/install changes.
     *
     * @param string $request_path Request path (e.g. doctrine/FLIP/FLIP_DOCTRINE or /docs/FLIP_DOCTRINE)
     * @param bool|null $is_authenticated True/false for cache key; null = auto-detect from session/auth
     * @return array|null Associative array: content_id, file_path, canonical, is_alias, source, slug_encoding; or null if not found
     */
    public function resolve($request_path, $is_authenticated = null) {
        $UNTRUSTED = array('path' => $request_path);
        if (!$this->isSafeRequestPath($UNTRUSTED['path'])) {
            return null;
        }
        $path = self::normalizePath($UNTRUSTED['path']);
        if ($path === '') {
            return null;
        }
        if (strpos($path, '..') !== false) {
            return null;
        }
        $segments = explode('/', $path);
        foreach ($segments as $seg) {
            if ($seg === '..') {
                return null;
            }
        }

        if ($is_authenticated === null) {
            $is_authenticated = $this->detectAuthenticated();
        }
        $cache_key = 'resolved_' . md5($path . ($is_authenticated ? '_auth' : '_anon'));
        $cached = $this->getCached($cache_key);
        if ($cached !== null && is_array($cached)) {
            return $cached;
        }

        $out = $this->resolveFromDb($path);
        if ($out !== null) {
            $this->setCached($cache_key, $out, $this->cache_ttl);
            return $out;
        }

        $out = $this->resolveFromCsv($path);
        if ($out !== null) {
            $this->setCached($cache_key, $out, $this->cache_ttl);
            return $out;
        }

        $out = $this->resolveFromFilesystem($path);
        if ($out !== null) {
            $this->setCached($cache_key, $out, $this->cache_ttl);
        }
        return $out;
    }

    /**
     * Detect whether the current request is authenticated (for cache key separation).
     *
     * @return bool
     */
    private function detectAuthenticated() {
        $auth = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
        if ($auth && is_object($auth) && method_exists($auth, 'getCurrentUser')) {
            $user = $auth->getCurrentUser();
            return is_array($user) && !empty($user);
        }
        if (function_exists('current_user')) {
            $user = current_user();
            return is_array($user) && !empty($user);
        }
        return false;
    }

    /**
     * Get value from cache (APCu or file). T6.
     *
     * @param string $key Cache key (e.g. resolved_<md5>)
     * @return array|null Cached result array or null
     */
    private function getCached($key) {
        if (function_exists('apcu_fetch')) {
            $v = apcu_fetch($key);
            return is_array($v) ? $v : null;
        }
        $file = $this->cache_dir . '/' . $key;
        if (!is_file($file) || !is_readable($file)) {
            return null;
        }
        $raw = @file_get_contents($file);
        if ($raw === false) {
            return null;
        }
        $data = @json_decode($raw, true);
        if (!is_array($data) || !isset($data['value'])) {
            return null;
        }
        if (!isset($data['expires_ymdhis'])) {
            @unlink($file);
            return null;
        }
        if (!class_exists('timestamp_ymdhis', false)) {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/TimestampYmdhis.php';
        }
        $expired = ((int) $data['expires_ymdhis'] < timestamp_ymdhis::now());
        if ($expired) {
            @unlink($file);
            return null;
        }
        return is_array($data['value']) ? $data['value'] : null;
    }

    /**
     * Store value in cache (APCu or file). T6.
     *
     * @param string $key Cache key
     * @param array $value Resolver result array
     * @param int $ttl Seconds (default 3600)
     */
    private function setCached($key, $value, $ttl = 3600) {
        if (!is_array($value)) {
            return;
        }
        if (function_exists('apcu_store')) {
            apcu_store($key, $value, $ttl);
            $keys = apcu_fetch('resolved_keys');
            if (!is_array($keys)) {
                $keys = array();
            }
            $keys[$key] = true;
            apcu_store('resolved_keys', $keys, 0);
            return;
        }
        $dir = $this->cache_dir;
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        if (!is_dir($dir) || !is_writable($dir)) {
            return;
        }
        $file = $dir . '/' . $key;
        if (!class_exists('timestamp_ymdhis', false)) {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/TimestampYmdhis.php';
        }
        $expiresPacked = timestamp_ymdhis::addSeconds(timestamp_ymdhis::now(), (int) $ttl);
        $data = array('expires_ymdhis' => $expiresPacked, 'value' => $value);
        @file_put_contents($file, json_encode($data), LOCK_EX);
    }

    /**
     * Invalidate all resolver caches. Call when flip_headers.csv changes, after flip_header_audit.py, or after installer/seed. T6.
     */
    public function invalidateAllCaches() {
        if (function_exists('apcu_fetch')) {
            $keys = apcu_fetch('resolved_keys');
            if (is_array($keys)) {
                foreach (array_keys($keys) as $k) {
                    if ($k !== 'resolved_keys' && strpos($k, 'resolved_') === 0) {
                        apcu_delete($k);
                    }
                }
                apcu_delete('resolved_keys');
            }
            return;
        }
        $dir = $this->cache_dir;
        if (!is_dir($dir)) {
            return;
        }
        $files = @glob($dir . '/resolved_*');
        if (is_array($files)) {
            foreach ($files as $f) {
                if (is_file($f)) {
                    @unlink($f);
                }
            }
        }
    }

    /**
     * Tier 1: Resolve from lupo_contents by file_path_from_root or custom_path.
     *
     * @param string $path Normalized request path
     * @return array|null Result or null
     */
    private function resolveFromDb($path) {
        if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
            return null;
        }
        if (!class_exists('DatabaseFactory', false)) {
            $df = LUPOPEDIA_PATH . '/lupo-includes/classes/DatabaseFactory.php';
            if (is_file($df)) {
                require_once $df;
            }
        }
        if (!class_exists('DatabaseFactory', false)) {
            return null;
        }
        try {
            $db = DatabaseFactory::getConnection();
        } catch (Exception $e) {
            if ($this->log_fallback) {
                error_log('UrlResolver: DB tier skipped (no connection): ' . $e->getMessage());
            }
            return null;
        }
        if (!($db instanceof PDO_DB)) {
            return null;
        }
        try {
            $table = $db->quoteIdentifier($this->prefix . 'contents');
            $fp_docs = 'docs/' . $path . '.md';
            $fp_raw = $path . '.md';
            $sql = "SELECT content_id, file_path_from_root FROM " . $table
                . " WHERE (file_path_from_root = :fp_docs OR file_path_from_root = :fp_raw OR custom_path = :path)"
                . " AND (is_deleted = 0 OR is_deleted IS NULL) AND (is_active = 1 OR is_active IS NULL) LIMIT 1";
            $params = array('fp_docs' => $fp_docs, 'fp_raw' => $fp_raw, 'path' => $path);
            $row = $db->fetchRow($sql, $params);
            if ($row && isset($row['content_id']) && isset($row['file_path_from_root'])) {
                $canonical = '/' . str_replace('.md', '', $row['file_path_from_root']);
                if (strpos($canonical, '/docs/') === 0) {
                    $canonical = substr($canonical, 5);
                }
                $canonical = '/' . ltrim($canonical, '/');
                return array(
                    'content_id' => (int) $row['content_id'],
                    'file_path' => $row['file_path_from_root'],
                    'canonical' => $canonical,
                    'is_alias' => false,
                    'source' => 'db',
                    'slug_encoding' => 'underscore',
                    'alias_redirect' => $this->alias_redirect,
                );
            }
        } catch (Exception $e) {
            if ($this->log_fallback) {
                error_log('UrlResolver: DB tier failed: ' . $e->getMessage());
            }
        }
        return null;
    }

    /**
     * Tier 2: Load CSV and resolve from web_canonical / web_aliases.
     * Logs warning when this fallback is used.
     *
     * @param string $path Normalized request path
     * @return array|null Result or null
     */
    private function resolveFromCsv($path) {
        $map = $this->getCsvMap();
        if ($map === null) {
            if ($this->log_fallback) {
                error_log('UrlResolver: Fallback 1 (CSV) used; exports/flip_headers.csv missing or unreadable.');
            }
            return null;
        }

        $path_with_slash = '/' . $path;
        $row = null;
        if (isset($map[$path])) {
            $row = $map[$path];
        } elseif (isset($map[$path_with_slash])) {
            $row = $map[$path_with_slash];
        } else {
            $path_lower = strtolower($path);
            foreach (array_keys($map) as $k) {
                if (strtolower($k) === $path_lower || strtolower($k) === strtolower($path_with_slash)) {
                    $row = $map[$k];
                    break;
                }
            }
        }
        if ($row === null) {
            return null;
        }
        if (!$row) {
            return null;
        }

        if ($this->log_fallback) {
            error_log('UrlResolver: Fallback 1 (CSV) used for path: ' . $path);
        }

        $canonical = isset($row['_canonical']) ? $row['_canonical'] : (isset($row['web_canonical']) ? trim($row['web_canonical'], '/') : '');
        if ($canonical !== '' && $canonical[0] !== '/') {
            $canonical = '/' . $canonical;
        }
        $is_alias = (isset($row['_is_alias']) && $row['_is_alias']);
        $file_path = isset($row['file_path_from_root']) ? $row['file_path_from_root'] : '';
        $content_id = isset($row['content_id']) ? (int) $row['content_id'] : 0;

        return array(
            'content_id' => $content_id,
            'file_path' => $file_path,
            'canonical' => $canonical,
            'is_alias' => $is_alias,
            'source' => 'csv',
            'slug_encoding' => isset($row['web_slug_encoding']) ? $row['web_slug_encoding'] : 'underscore',
            'alias_redirect' => $this->alias_redirect,
        );
    }

    /**
     * Build path → row map from CSV. Index by normalized canonical and each alias.
     *
     * @return array|null Map normalized_path => row (with _is_alias set for alias entries), or null on error
     */
    private function getCsvMap() {
        if ($this->csv_map !== null) {
            return $this->csv_map;
        }
        if (!is_file($this->csv_path) || !is_readable($this->csv_path)) {
            $this->csv_map = null;
            return null;
        }
        $fh = @fopen($this->csv_path, 'r');
        if ($fh === false) {
            $this->csv_map = null;
            return null;
        }
        $map = array();
        $header = null;
        $idx_file_path = 0;
        $idx_content_id = 6;
        $idx_web_canonical = 9;
        $idx_web_aliases = 10;
        $idx_web_slug = 11;
        $idx_web_slug_encoding = 12;
        $idx_web_base_path = 13;
        $idx_web_url_pattern = 14;
        $row_num = 0;
        while (($cells = fgetcsv($fh, 0, ',')) !== false) {
            $row_num++;
            if ($row_num === 1) {
                continue;
            }
            if ($row_num === 2) {
                $header = $cells;
                foreach ($header as $i => $h) {
                    if ($h === 'file_path_from_root') {
                        $idx_file_path = $i;
                    }
                    if ($h === 'content_id') {
                        $idx_content_id = $i;
                    }
                    if ($h === 'web_canonical') {
                        $idx_web_canonical = $i;
                    }
                    if ($h === 'web_aliases') {
                        $idx_web_aliases = $i;
                    }
                    if ($h === 'web_slug') {
                        $idx_web_slug = $i;
                    }
                    if ($h === 'web_slug_encoding') {
                        $idx_web_slug_encoding = $i;
                    }
                    if ($h === 'web_base_path') {
                        $idx_web_base_path = $i;
                    }
                    if ($h === 'web_url_pattern') {
                        $idx_web_url_pattern = $i;
                    }
                }
                continue;
            }
            $file_path = isset($cells[$idx_file_path]) ? trim($cells[$idx_file_path]) : '';
            $content_id = isset($cells[$idx_content_id]) ? $cells[$idx_content_id] : 0;
            $web_canonical = isset($cells[$idx_web_canonical]) ? trim($cells[$idx_web_canonical], '/') : '';
            if ($web_canonical !== '' && $web_canonical[0] !== '/') {
                $web_canonical = '/' . $web_canonical;
            }
            $web_aliases = isset($cells[$idx_web_aliases]) ? $cells[$idx_web_aliases] : '';
            $web_slug = isset($cells[$idx_web_slug]) ? $cells[$idx_web_slug] : '';
            $web_slug_encoding = isset($cells[$idx_web_slug_encoding]) ? $cells[$idx_web_slug_encoding] : 'underscore';
            $web_base_path = isset($cells[$idx_web_base_path]) ? $cells[$idx_web_base_path] : '';
            $web_url_pattern = isset($cells[$idx_web_url_pattern]) ? $cells[$idx_web_url_pattern] : '';

            $row = array(
                'file_path_from_root' => $file_path,
                'content_id' => $content_id,
                'web_canonical' => $web_canonical,
                'web_slug' => $web_slug,
                'web_slug_encoding' => $web_slug_encoding,
                'web_base_path' => $web_base_path,
                'web_url_pattern' => $web_url_pattern,
            );

            $canonical_key = ltrim($web_canonical, '/');
            $map[$canonical_key] = $row;
            $map[$web_canonical] = $row;

            $aliases = array_filter(explode('|', $web_aliases));
            foreach ($aliases as $a) {
                $a = trim($a, '/');
                if ($a === '') {
                    continue;
                }
                $alias_row = $row;
                $alias_row['_is_alias'] = true;
                $alias_row['_canonical'] = $web_canonical;
                $map[$a] = $alias_row;
                $map['/' . $a] = $alias_row;
            }
        }
        fclose($fh);
        $this->csv_map = $map;
        return $this->csv_map;
    }

    /**
     * Tier 3: Parse .md FLIP headers from filesystem. Only for paths under docs/.
     * Logs warning when this fallback is used.
     *
     * @param string $path Normalized request path
     * @return array|null Result or null
     */
    private function resolveFromFilesystem($path) {
        if ($this->repo_root_real === false) {
            return null;
        }
        $candidates = array(
            'docs/' . $path . '.md',
            $path . '.md',
        );
        foreach ($candidates as $rel) {
            if (strpos($rel, '..') !== false) {
                continue;
            }
            $rel = str_replace('\\', '/', $rel);
            $full = str_replace('/', DIRECTORY_SEPARATOR, $this->repo_root . '/' . $rel);
            $realFull = realpath($full);
            if ($realFull === false) {
                continue;
            }
            $realNorm = str_replace('\\', '/', $realFull);
            if (!$this->pathIsUnderRepo($realNorm)) {
                continue;
            }
            if (!is_file($realFull) || !is_readable($realFull)) {
                continue;
            }
            $content = file_get_contents($realFull, false, null, 0, 8192);
            if ($content === false) {
                continue;
            }
            $meta = $this->parseFlipWebBlock($content);
            if ($meta !== null) {
                if ($this->log_fallback) {
                    error_log('UrlResolver: Fallback 2 (filesystem .md parse) used for path: ' . $path);
                }
                return array(
                    'content_id' => isset($meta['content_id']) ? (int) $meta['content_id'] : 0,
                    'file_path' => $rel,
                    'canonical' => isset($meta['canonical']) ? $meta['canonical'] : '/' . $path,
                    'is_alias' => false,
                    'source' => 'md',
                    'slug_encoding' => isset($meta['slug_encoding']) ? $meta['slug_encoding'] : 'underscore',
                    'alias_redirect' => $this->alias_redirect,
                );
            }
        }
        return null;
    }

    /**
     * Parse optional web: block from FLIP header (first YAML block).
     *
     * @param string $content File content (first 8K)
     * @return array|null Associative array with canonical, slug_encoding, etc., or null
     */
    private function parseFlipWebBlock($content) {
        if (strpos($content, '---') !== 0) {
            return null;
        }
        $end = strpos($content, '---', 3);
        if ($end === false) {
            return null;
        }
        $block = substr($content, 3, $end - 3);
        if (strpos($block, 'web:') === false && strpos($block, 'web :') === false) {
            return null;
        }
        $canonical = null;
        $slug_encoding = 'underscore';
        $lines = explode("\n", $block);
        $in_web = false;
        $indent = 0;
        foreach ($lines as $line) {
            $trimmed = ltrim($line, " \t");
            if ($trimmed === '') {
                continue;
            }
            if (preg_match('/^web\s*:\s*$/', $trimmed)) {
                $in_web = true;
                $indent = strlen($line) - strlen($trimmed);
                continue;
            }
            if ($in_web) {
                $line_indent = strlen($line) - strlen(ltrim($line, " \t"));
                if ($line_indent <= $indent && $trimmed[0] !== ' ' && $trimmed[0] !== '-') {
                    break;
                }
                if (preg_match('/^\s*canonical\s*:\s*(.+)$/', $line, $m)) {
                    $canonical = trim($m[1], " \t\"'");
                }
                if (preg_match('/^\s*slug_encoding\s*:\s*(.+)$/', $line, $m)) {
                    $slug_encoding = trim($m[1], " \t\"'");
                }
            }
        }
        if ($canonical === null) {
            return null;
        }
        if ($canonical !== '' && $canonical[0] !== '/') {
            $canonical = '/' . $canonical;
        }
        return array('canonical' => $canonical, 'slug_encoding' => $slug_encoding);
    }

    /**
     * Invalidate CSV cache (e.g. after flip_header_audit.py or CSV update).
     */
    public function invalidateCsvCache() {
        $this->csv_map = null;
    }

    /**
     * Get candidate canonical paths for Smart 404 suggestions. Auth-aware: pass $is_authenticated false to exclude
     * any paths that might be considered private (currently all CSV paths are treated as public). T4.
     *
     * @param int $limit Max number of candidates (default 100)
     * @param string|null $first_char If set, only paths whose last segment starts with this prefix (e.g. 3 chars; case-insensitive)
     * @param bool $is_authenticated If false, caller may filter private paths; currently unused (all public)
     * @return array List of normalized canonical paths (no leading slash)
     */
    public function getCandidateCanonicalPaths($limit = 100, $first_char = null, $is_authenticated = true) {
        $map = $this->getCsvMap();
        if ($map === null) {
            return array();
        }
        $seen = array();
        $candidates = array();
        foreach ($map as $key => $row) {
            if (isset($row['_is_alias']) && $row['_is_alias']) {
                $canonical = isset($row['_canonical']) ? trim($row['_canonical'], '/') : '';
            } else {
                $canonical = isset($row['web_canonical']) ? trim($row['web_canonical'], '/') : '';
                if ($canonical !== '' && $canonical[0] === '/') {
                    $canonical = ltrim($canonical, '/');
                }
            }
            if ($canonical === '' || isset($seen[$canonical])) {
                continue;
            }
            $seen[$canonical] = true;
            if ($first_char !== null && $first_char !== '') {
                $parts = explode('/', $canonical);
                $last = end($parts);
                $prefix_len = strlen($first_char);
                if ($last === '' || $prefix_len === 0 || strlen($last) < $prefix_len || strtolower(substr($last, 0, $prefix_len)) !== strtolower($first_char)) {
                    continue;
                }
            }
            $candidates[] = $canonical;
            if (count($candidates) >= $limit) {
                break;
            }
        }
        return $candidates;
    }
}
