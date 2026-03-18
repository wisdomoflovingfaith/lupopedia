<?php
/**
 * MULTI_AGENT doctrine smoke test — aligned to current file shape (4.0.80+).
 */

echo "=== Doctrine Comprehensive Update Validation Test ===\n";

$baseDir = __DIR__ . '/../..';
$doctrineFile = $baseDir . '/lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md';

$tests = array();
$passed = 0;
$total = 0;

if (!file_exists($doctrineFile)) {
    echo "FAIL: Doctrine file not found\n";
    exit(1);
}

$doctrine = file_get_contents($doctrineFile);

function tc(&$tests, &$passed, &$total, $ok, $passMsg, $failMsg) {
    $total++;
    if ($ok) {
        $passed++;
        $tests[] = 'PASS: ' . $passMsg;
    } else {
        $tests[] = 'FAIL: ' . $failMsg;
    }
}

tc($tests, $passed, $total,
    strpos($doctrine, '## 1. Purpose') !== false,
    'Section 1 Purpose',
    'Missing ## 1. Purpose');

tc($tests, $passed, $total,
    strpos($doctrine, '## 2. Agent Identity & Registration') !== false,
    'Section 2 Agent Identity',
    'Missing Section 2');

tc($tests, $passed, $total,
    strpos($doctrine, 'lupo-agents/{actor_id}/') !== false,
    'Registration mentions lupo-agents/{actor_id}/',
    'Registration path missing');

tc($tests, $passed, $total,
    strpos($doctrine, 'Channel 42') !== false || strpos($doctrine, 'channel 42') !== false,
    'Channel 42 coordination workspace referenced',
    'Channel 42 not referenced');

tc($tests, $passed, $total,
    strpos($doctrine, 'lupo-channels/42/') !== false,
    'lupo-channels/42/ path documented',
    'lupo-channels/42/ missing');

tc($tests, $passed, $total,
    strpos($doctrine, 'WOLFIE') !== false && strpos($doctrine, 'HERMES') !== false,
    'WOLFIE and HERMES mentioned',
    'WOLFIE/HERMES missing');

tc($tests, $passed, $total,
    strpos($doctrine, 'HEPHAESTUS') !== false && strpos($doctrine, 'Implementer') !== false,
    'HEPHAESTUS / Implementer documented',
    'HEPHAESTUS implementer missing');

tc($tests, $passed, $total,
    strpos($doctrine, 'HERMES_IMPLEMENTATION') === false,
    'HERMES_IMPLEMENTATION not canonical in doctrine',
    'HERMES_IMPLEMENTATION still in doctrine');

tc($tests, $passed, $total,
    strpos($doctrine, 'ANUBIS_IMPLEMENTATION') === false,
    'No ANUBIS_IMPLEMENTATION',
    'ANUBIS_IMPLEMENTATION present');

tc($tests, $passed, $total,
    strpos($doctrine, '## 7. Channel Authority Model') !== false,
    'Section 7 Channel Authority',
    'Section 7 missing');

tc($tests, $passed, $total,
    strpos($doctrine, '## 14. Enforcement') !== false,
    'Section 14 Enforcement',
    'Section 14 missing');

tc($tests, $passed, $total,
    strpos($doctrine, '## 13.') !== false && strpos($doctrine, '## 14.') !== false,
    'Sections 13 and 14 present',
    'Sections 13/14 missing');

$markers = array('## 1.', '## 5.', '## 10.', '## 14.');
$found = 0;
foreach ($markers as $m) {
    if (strpos($doctrine, $m) !== false) {
        $found++;
    }
}
tc($tests, $passed, $total, $found >= 3, 'Core section markers present', 'Too few section markers');

foreach ($tests as $test) {
    echo $test . "\n";
}

echo "\n=== SUMMARY ===\n";
echo "Tests Passed: {$passed}/{$total}\n";

if ($passed === $total) {
    echo "OK ALL TESTS PASSED\n";
    exit(0);
}
echo "SOME TESTS FAILED\n";
exit(1);
