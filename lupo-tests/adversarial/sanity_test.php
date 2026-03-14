<?php
/**
 * Sanity test for adversarial harness: one known-good vector (missing CSRF → 403).
 * Run from repo root: php tests/adversarial/sanity_test.php [BASE_URL]
 * Exit 0 if system correctly returns 403 for POST without CSRF; 1 if harness or target broken.
 * SKIP (exit 0) if curl extension not available.
 */
if (!function_exists('curl_init')) {
    echo "SKIP sanity test requires PHP curl extension\n";
    exit(0);
}
$repo = dirname(dirname(__DIR__));
require_once $repo . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'adversarial' . DIRECTORY_SEPARATOR . 'StonedWolfieHarness.php';

$baseUrl = isset($argv[1]) ? $argv[1] : (getenv('LUPOPEDIA_BASE_URL') ? getenv('LUPOPEDIA_BASE_URL') : 'http://localhost/lupopedia');
$harness = new StonedWolfieHarness($baseUrl);

$result = $harness->runVector('csrf_missing');
if ($result['passed'] && $result['actual'] === 403) {
    echo "PASS sanity: missing CSRF returns 403\n";
    exit(0);
}
if ($result['actual'] === 0 || $result['actual'] === -1) {
    echo "SKIP sanity: server unreachable at " . $baseUrl . " (run with server up to verify)\n";
    exit(0);
}
echo "FAIL sanity: missing CSRF expected 403, got " . $result['actual'] . "\n";
exit(1);
