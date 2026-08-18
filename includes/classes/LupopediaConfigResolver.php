<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "includes/classes/LupopediaConfigResolver.php"
#   web_path: "https://www.lupopedia.com/lupopedia/includes/classes/LupopediaConfigResolver.php"
#   status: "active"
#   when_updated: "20260417074010"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/lupopediaconfigresolver-php.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/lupopediaconfigresolver-php"
#   artifact_type: "class"
#   artifact_kind: "resolver"
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "42"
#   content_slug: "lupopediaconfigresolver-php"
#   default_collection_id: null
#   lupopedia.schema: "implementation"
#   title: "LupopediaConfigResolver.php — config location and protection resolver"
#   summary: "Resolves shared-host config discovery and write targets, then protects lupopedia-config.php with chmod 0600 and modern plus legacy .htaccess deny rules."
# ---------------------------------------------------------------------
/**
 * Locate and optionally choose write path for lupopedia-config.php.
 *
 * Lupopedia is always deployed in a subdirectory URL (e.g. example.com/lupopedia/).
 * Discovery order:
 * 1. Web document root (preferred for autoinstaller deployment).
 * 2. One level above the web document root (legacy shared-hosting layout).
 * 3. dirname(DOCUMENT_ROOT) + public path segment + lupopedia-config.php (legacy sibling layout).
 * 4. Lupopedia install directory (LUPOPEDIA_PATH).
 * 5. Parent of install directory, only if that parent is not another Lupopedia tree
 *    (absence of includes/bootstrap.php in the parent — WordPress-style guard).
 *
 * When DOCUMENT_ROOT is unset (e.g. some CLI), steps 1–2 are skipped.
 *
 * @package Lupopedia
 */
/*
 * Filesystem Rule (canonical):
 * - lupopedia-config.php MUST be written INSIDE the web-accessible directory (DOCUMENT_ROOT or public path).
 * - It MUST be protected: chmod 0600 and .htaccess deny rules (modern Require all denied + legacy fallback comment).
 * - All other files (memory/, channels/, app/, includes/, etc.) live ABOVE the web root.
 * - memory_path and channels_path in config MUST resolve ABOVE web root using dirname(__DIR__) or LUPOPEDIA_PATH.
 */
class LupopediaConfigResolver
{

    /**
     * Reject stream wrappers and remote URLs so require() cannot load remote code.
     *
     * @param string $path Candidate filesystem path.
     * @return bool True if path is non-empty and looks like a local path only.
     */
    public static function isSafeLocalConfigPath($path)
    {
        if (!is_string($path) || $path === '') {
            return false;
        }
        if (strpos($path, "\0") !== false) {
            return false;
        }
        if (preg_match('#^[a-zA-Z][a-zA-Z0-9+.+-]*://#', $path)) {
            return false;
        }
        return true;
    }

    /**
     * Web path of this install (no trailing slash), from SCRIPT_NAME.
     *
     * basename(install dir) is not enough. Nested hosts such as
     * https://localhost/colorlex.com/lupopedia/login.php must resolve to
     * /colorlex.com/lupopedia, not /lupopedia.
     *
     * @param string $lupopediaPath Absolute path to Lupopedia root.
     * @return string e.g. /lupopedia or /colorlex.com/lupopedia
     */
    public static function publicPathFromRequest($lupopediaPath)
    {
        $root = rtrim(str_replace(array('\\', '/'), '/', (string) $lupopediaPath), '/');
        $installName = basename($root);
        if ($installName === '' || $installName === '.' || $installName === '..') {
            $installName = 'lupopedia';
        }

        $script = '';
        if (isset($_SERVER['SCRIPT_NAME']) && is_string($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME'] !== '') {
            $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
        }
        if ($script !== '') {
            $dir = rtrim(dirname($script), '/');
            $parts = explode('/', $dir);
            $idx = -1;
            foreach ($parts as $i => $part) {
                if ($part === $installName) {
                    $idx = $i;
                }
            }
            if ($idx >= 0) {
                $slice = array_slice($parts, 0, $idx + 1);
                $pub = implode('/', $slice);
                if ($pub === '' || $pub[0] !== '/') {
                    $pub = '/' . ltrim($pub, '/');
                }
                return rtrim($pub, '/');
            }
            if ($dir !== '' && $dir !== '/' && $dir !== '.') {
                return $dir;
            }
        }

        return '/' . $installName;
    }

    /**
     * @param string $lupopediaPath Absolute path to Lupopedia root (directory containing index.php).
     * @param string $publicPath    URL path segment e.g. /lupopedia (leading slash optional).
     * @return string|null Absolute path to existing lupopedia-config.php, or null.
     */
    public static function resolve($lupopediaPath, $publicPath)
    {
        $root = rtrim(str_replace('\\', DIRECTORY_SEPARATOR, $lupopediaPath), DIRECTORY_SEPARATOR);
        $candidates = array();

        // Subdirectory OS: the install's own config wins over a leftover file
        // at DOCUMENT_ROOT (e.g. after moving into colorlex.com/lupopedia/).
        $candidates[] = $root . DIRECTORY_SEPARATOR . 'lupopedia-config.php';

        $docRoot = '';
        if (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'] !== '') {
            $docRoot = rtrim(str_replace('\\', DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT']), DIRECTORY_SEPARATOR);
        }
        if ($docRoot !== '') {
            $candidates[] = $docRoot . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
            $aboveDoc = dirname($docRoot);
            if ($aboveDoc !== $docRoot && $aboveDoc !== '' && $aboveDoc !== '.') {
                $candidates[] = $aboveDoc . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
                $pub = is_string($publicPath) ? $publicPath : ('/' . basename($root));
                if ($pub !== '' && $pub[0] !== '/') {
                    $pub = '/' . $pub;
                }
                $rel = trim($pub, '/');
                if ($rel !== '') {
                    $candidates[] = $aboveDoc . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel) . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
                }
            }
        }

        $parent = dirname($root);
        if ($parent !== $root && $parent !== '' && $parent !== '.') {
            $parentBootstrap = $parent . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'bootstrap.php';
            if (!@is_file($parentBootstrap)) {
                $candidates[] = $parent . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
            }
        }

        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log('[LupopediaConfigResolver] resolve candidates: ' . implode(', ', $candidates));
        }

        $seen = array();
        foreach ($candidates as $p) {
            if ($p === '' || isset($seen[$p])) {
                continue;
            }
            $seen[$p] = true;
            if (@is_file($p) && self::isSafeLocalConfigPath($p)) {
                return $p;
            }
        }
        return null;
    }

    /**
     * WordPress setup-config.php rule: write next to sample — ABSPATH if sample is in ABSPATH,
     * else parent directory (develop layout).
     *
     * @param string $lupopediaPath Install root (LUPOPEDIA_PATH).
     * @return array Two elements: (string) directory that must be writable, (string) full path to lupopedia-config.php to create.
     */
    public static function defaultWriteTargets($lupopediaPath, $preferredConfigDir = null)
    {
        $root = rtrim(str_replace('\\', DIRECTORY_SEPARATOR, $lupopediaPath), DIRECTORY_SEPARATOR);
        $normalizedPreferred = '';
        if (is_string($preferredConfigDir) && trim($preferredConfigDir) !== '') {
            $normalizedPreferred = rtrim(str_replace('\\', DIRECTORY_SEPARATOR, trim($preferredConfigDir)), DIRECTORY_SEPARATOR);
        }
        if ($normalizedPreferred !== '' && @is_dir($normalizedPreferred)) {
            $configPath = $normalizedPreferred . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
            return array($normalizedPreferred, $configPath);
        }
        $configPath = $root . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
        return array($root, $configPath);
    }

    /**
     * Protect an existing lupopedia-config.php file with restrictive permissions and
     * deny-rule .htaccess in the same directory.
     *
     * @param string $configPath Absolute path to lupopedia-config.php
     * @return bool True when file exists and chmod was attempted; false when file is missing/invalid
     */
    public static function protectConfigFile($configPath)
    {
        if (!is_string($configPath) || $configPath === '' || !@is_file($configPath)) {
            return false;
        }

        @chmod($configPath, 0600);

        $configDir = dirname($configPath);
        $htaccessPath = $configDir . DIRECTORY_SEPARATOR . '.htaccess';
        $rule = "<Files \"lupopedia-config.php\">\n    Require all denied\n    # Legacy Apache 2.2 fallback:\n    # Order Allow,Deny\n    # Deny from all\n</Files>\n";

        $existing = @file_get_contents($htaccessPath);
        if ($existing === false) {
            @file_put_contents($htaccessPath, $rule);
        } elseif (strpos($existing, 'lupopedia-config.php') === false) {
            @file_put_contents($htaccessPath, rtrim($existing) . "\n\n" . $rule);
        }

        return true;
    }
}
