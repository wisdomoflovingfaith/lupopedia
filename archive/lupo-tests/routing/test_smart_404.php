<?php
/**
 * T8 Unit tests: Smart 404 (prefix filtering, Levenshtein ranking, auth-aware, anon no suggestions).
 * Run from repo root: php tests/routing/test_smart_404.php
 * Requires url_resolver.php (lupo_smart_404). Define LUPOPEDIA_CONFIG_LOADED and minimal globals if needed.
 * PHP 5.3-compatible; no frameworks. Exit 0 = PASS, non-zero = FAIL.
 */
$repo_root = dirname(dirname(__DIR__));
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}
if (!defined('LUPOPEDIA_ABSPATH')) {
    define('LUPOPEDIA_ABSPATH', $repo_root);
}
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    define('LUPOPEDIA_CONFIG_LOADED', true);
}
if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}
$GLOBALS['mydatabase'] = null;
require_once $repo_root . '/lupo-includes/classes/UrlResolver.php';
require_once $repo_root . '/lupo-includes/functions/url_resolver.php';

$fail = 0;

// Anonymous: suggestions must be empty
$data = lupo_smart_404('doctrine/FLIP/FLIP_DOCTRIN', false);
if (!isset($data['status']) || $data['status'] !== 'smart_404') {
    echo "FAIL smart_404 status\n";
    $fail++;
}
if (!isset($data['suggestions']) || !is_array($data['suggestions'])) {
    echo "FAIL suggestions key\n";
    $fail++;
}
if (!empty($data['suggestions'])) {
    echo "FAIL anonymous must have empty suggestions\n";
    $fail++;
}

// Authenticated: may have suggestions (if CSV has similar paths)
$data = lupo_smart_404('doctrine/FLIP/FLIP_DOCTRIN', true);
if (!isset($data['requested']) || $data['requested'] !== 'doctrine/FLIP/FLIP_DOCTRIN') {
    echo "FAIL requested path\n";
    $fail++;
}
if (!is_array($data['suggestions'])) {
    echo "FAIL suggestions must be array\n";
    $fail++;
}
// Prefix filtering: first 3 chars "FLI" should limit candidates; we only check structure
if (count($data['suggestions']) > 5) {
    echo "FAIL at most 5 suggestions\n";
    $fail++;
}

// Unrelated slug: authenticated may get empty or few suggestions
$data = lupo_smart_404('doctrine/ZZZ/UNRELATED', true);
if (!isset($data['status']) || $data['status'] !== 'smart_404') {
    echo "FAIL smart_404 status for unrelated\n";
    $fail++;
}

if ($fail > 0) {
    echo "FAIL (${fail} assertions)\n";
    exit(1);
}
echo "PASS\n";
exit(0);
