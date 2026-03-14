<?php
/**
 * Run adversarial harness (4.0.20 T4). Usage: php tests/adversarial/run.php [BASE_URL]
 * Default BASE_URL: http://localhost/lupopedia
 * Exit 0 if all vectors pass or server unreachable (skip); 1 if any required vector fails.
 * Requires curl extension; exits 0 with SKIP message if curl not available.
 */
if (!function_exists('curl_init')) {
    echo "SKIP adversarial tests require PHP curl extension\n";
    exit(0);
}
$repo = dirname(dirname(__DIR__));
require_once $repo . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'adversarial' . DIRECTORY_SEPARATOR . 'StonedWolfieHarness.php';

$baseUrl = isset($argv[1]) ? $argv[1] : (getenv('LUPOPEDIA_BASE_URL') ? getenv('LUPOPEDIA_BASE_URL') : 'http://localhost/lupopedia');
$harness = new StonedWolfieHarness($baseUrl);

echo "Adversarial harness base URL: " . $baseUrl . "\n";
$results = $harness->runAll();

$pass = 0;
$fail = 0;
foreach ($results as $r) {
    if ($r['passed']) {
        $pass++;
        echo "PASS " . $r['vector'] . " (expected " . $r['expected'] . ", got " . $r['actual'] . ")\n";
    } else {
        $fail++;
        echo "FAIL " . $r['vector'] . " (expected " . $r['expected'] . ", got " . $r['actual'] . ")\n";
    }
}

echo "\nAdversarial summary: PASS=$pass FAIL=$fail\n";
exit($fail > 0 ? 1 : 0);
