<?php
/**
 * MULTI_AGENT_COORDINATION_DOCTRINE validation (4.0.80+)
 *
 * HERMES = Routing & Messaging Infrastructure (not primary implementer).
 * HEPHAESTUS = Implementer. ANUBIS = Custodian.
 */

echo "=== MULTI_AGENT_COORDINATION_DOCTRINE Validation Test ===\n";

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

function t(&$tests, &$passed, &$total, $ok, $passMsg, $failMsg) {
    $total++;
    if ($ok) {
        $passed++;
        $tests[] = 'PASS: ' . $passMsg;
    } else {
        $tests[] = 'FAIL: ' . $failMsg;
    }
}

t($tests, $passed, $total,
    strpos($doctrine, '| **Routing & Messaging** |') !== false && strpos($doctrine, 'HERMES') !== false,
    'HERMES listed under Routing & Messaging agent type',
    'HERMES not in Routing & Messaging row');

t($tests, $passed, $total,
    strpos($doctrine, '| **Implementer** |') !== false && strpos($doctrine, 'HEPHAESTUS') !== false,
    'HEPHAESTUS listed as Implementer agent type',
    'HEPHAESTUS not listed as Implementer');

t($tests, $passed, $total,
    strpos($doctrine, '### 5.3 HERMES') !== false &&
    strpos($doctrine, 'Heuristic Event Routing & Messaging Exchange System') !== false,
    'HERMES persona §5.3 defines full routing name',
    'HERMES §5.3 or full name missing');

t($tests, $passed, $total,
    strpos($doctrine, 'own primary implementation execution') !== false &&
    strpos($doctrine, 'HEPHAESTUS') !== false,
    'HERMES CANNOT owns implementation; HEPHAESTUS named',
    'HERMES/HEPHAESTUS implementation boundary missing');

t($tests, $passed, $total,
    strpos($doctrine, '### 5.2 ANUBIS') !== false && strpos($doctrine, 'Custodian') !== false,
    'ANUBIS custodian persona present',
    'ANUBIS custodian block missing');

t($tests, $passed, $total,
    strpos($doctrine, 'ANUBIS_IMPLEMENTATION') === false,
    'No ANUBIS_IMPLEMENTATION string in doctrine',
    'Obsolete ANUBIS_IMPLEMENTATION still present');

t($tests, $passed, $total,
    strpos($doctrine, 'HERMES_IMPLEMENTATION') === false,
    'No HERMES_IMPLEMENTATION in doctrine (obsolete prefix)',
    'HERMES_IMPLEMENTATION still in doctrine');

t($tests, $passed, $total,
    strpos($doctrine, '**Routing (when applicable)**') !== false &&
    strpos($doctrine, 'HEPHAESTUS') !== false &&
    strpos($doctrine, 'HERMES does not substitute for implementer') !== false,
    'Orchestration flow: HERMES routes, HEPHAESTUS executes',
    'Orchestration flow routing/implementer split missing');

t($tests, $passed, $total,
    strpos($doctrine, '| **Routing handoff** |') !== false,
    'Context table includes Routing handoff row',
    'Routing handoff context row missing');

t($tests, $passed, $total,
    strpos($doctrine, '## 13. Custody & Integrity (ANUBIS)') !== false,
    'Section 13 ANUBIS custody exists',
    'Section 13 missing');

t($tests, $passed, $total,
    strpos($doctrine, '## 14. Enforcement') !== false,
    'Section 14 Enforcement exists',
    'Section 14 missing');

t($tests, $passed, $total,
    strpos($doctrine, 'CHANNEL_ARTIFACT_ROUTING_DOCTRINE') !== false,
    'COM001 references CHANNEL_ARTIFACT_ROUTING_DOCTRINE',
    'Channel artifact routing doctrine reference missing');

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
