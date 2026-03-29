<?php
/**
 * validate_lupopedia_headers.php — LUPOPEDIA HEADERS validation (aligned with validate_lupopedia_headers.py).
 *
 * Usage:
 *   php lupo-scripts/validate_lupopedia_headers.php <path/to/file.md>
 *   php lupo-scripts/validate_lupopedia_headers.php <path/to/file.md> --check-db
 *
 * Legacy: validate_lupopedia_headers($path) returns array of error strings (warnings omitted).
 */

/**
 * @param string $path
 * @return array Error strings (empty if valid)
 */
function validate_lupopedia_headers($path)
{
    $repoRoot = dirname(__DIR__);
    if (!class_exists('HeaderDbSync', false)) {
        $root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : $repoRoot;
        $classFile = $root . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'HeaderDbSync.php';
        if (is_file($classFile)) {
            require_once $classFile;
        }
    }
    if (!class_exists('HeaderDbSync')) {
        return array('HeaderDbSync class not found');
    }
    $r = HeaderDbSync::validateFile($path, $repoRoot, false);
    return $r['errors'];
}

if (php_sapi_name() === 'cli' && isset($argv) && isset($argv[1]) && $argv[1] !== '--check-db') {
    $path = $argv[1];
    $checkDb = in_array('--check-db', $argv, true);

    $base = dirname(__DIR__);
    $config = $base . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
    if (is_file($config)) {
        require_once $config;
        if (defined('LUPOPEDIA_PATH')) {
            require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'class-pdo_db.php';
            require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'class-DatabaseFactory.php';
        }
    }
    $hdrPath = $base . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'HeaderDbSync.php';
    if (!is_file($hdrPath)) {
        fwrite(STDERR, "ERROR: HeaderDbSync.php not found.\n");
        exit(1);
    }
    require_once $hdrPath;

    $r = HeaderDbSync::validateFile($path, $base, $checkDb);

    if (count($r['errors']) > 0) {
        echo "ERRORS:\n";
        foreach ($r['errors'] as $e) {
            echo '  [ERROR] ' . $e . "\n";
        }
    }
    if (count($r['warnings']) > 0) {
        echo "WARNINGS:\n";
        foreach ($r['warnings'] as $w) {
            echo '  [WARN] ' . $w . "\n";
        }
    }
    if (count($r['errors']) === 0 && count($r['warnings']) === 0) {
        echo "OK: All validations passed\n";
    }

    exit(count($r['errors']) > 0 ? 1 : 0);
}
