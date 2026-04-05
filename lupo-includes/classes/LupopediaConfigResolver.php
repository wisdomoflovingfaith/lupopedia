<?php

/**
 * Locate and optionally choose write path for lupopedia-config.php.
 *
 * Lupopedia is always deployed in a subdirectory URL (e.g. example.com/lupopedia/).
 * Discovery order:
 * 1. One level above the web document root (preferred on shared hosting).
 * 2. dirname(DOCUMENT_ROOT) + public path segment + lupopedia-config.php (legacy sibling layout).
 * 3. Lupopedia install directory (LUPOPEDIA_PATH).
 * 4. Parent of install directory, only if that parent is not another Lupopedia tree
 *    (absence of lupo-includes/bootstrap.php in the parent — WordPress-style guard).
 *
 * When DOCUMENT_ROOT is unset (e.g. some CLI), steps 1–2 are skipped.
 *
 * @package Lupopedia
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
     * @param string $lupopediaPath Absolute path to Lupopedia root (directory containing index.php).
     * @param string $publicPath    URL path segment e.g. /lupopedia (leading slash optional).
     * @return string|null Absolute path to existing lupopedia-config.php, or null.
     */
    public static function resolve($lupopediaPath, $publicPath)
    {
        $root = rtrim(str_replace('\\', DIRECTORY_SEPARATOR, $lupopediaPath), DIRECTORY_SEPARATOR);
        $candidates = array();

        $docRoot = '';
        if (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'] !== '') {
            $docRoot = rtrim(str_replace('\\', DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT']), DIRECTORY_SEPARATOR);
        }
        if ($docRoot !== '') {
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

        $candidates[] = $root . DIRECTORY_SEPARATOR . 'lupopedia-config.php';

        $parent = dirname($root);
        if ($parent !== $root && $parent !== '' && $parent !== '.') {
            $parentBootstrap = $parent . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'bootstrap.php';
            if (!@is_file($parentBootstrap)) {
                $candidates[] = $parent . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
            }
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
    public static function defaultWriteTargets($lupopediaPath)
    {
        $root = rtrim(str_replace('\\', DIRECTORY_SEPARATOR, $lupopediaPath), DIRECTORY_SEPARATOR);
        $sampleInRoot = $root . DIRECTORY_SEPARATOR . 'lupopedia-config-sample.php';
        if (@is_file($sampleInRoot)) {
            $configPath = $root . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
            return array($root, $configPath);
        }
        $parent = dirname($root);
        $sampleInParent = $parent . DIRECTORY_SEPARATOR . 'lupopedia-config-sample.php';
        $parentBootstrap = $parent . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'bootstrap.php';
        if ($parent !== $root && $parent !== '' && $parent !== '.' && @is_file($sampleInParent) && !@is_file($parentBootstrap)) {
            $configPath = $parent . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
            return array($parent, $configPath);
        }
        $configPath = $root . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
        return array($root, $configPath);
    }
}
