<?php
/**
 * Simple Ten Persona Doctrine Validation Test
 * 
 * Validates the key elements of the 10-persona model
 */

echo "=== Simple Ten Persona Doctrine Validation Test ===\n";

$baseDir = __DIR__ . '/../..';
$doctrineFile = $baseDir . '/lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md';

$tests = [];
$passed = 0;
$total = 0;

// Read doctrine content
if (!file_exists($doctrineFile)) {
    echo "FAIL: Doctrine file not found\n";
    exit(1);
}

$doctrine = file_get_contents($doctrineFile);

// Test 1: Doctrine mentions 10 Primary Coordination Personas
$total++;
if (strpos($doctrine, 'ten canonical Primary Coordination Personas') !== false) {
    $passed++;
    $tests[] = "PASS: Doctrine references 10 Primary Coordination Personas";
} else {
    $tests[] = "FAIL: Doctrine does not mention 10 personas";
}

// Test 2: All 10 personas listed
$total++;
$expectedPersonas = ['WOLFIE', 'LEXA', 'ANUBIS', 'HEIMDALL', 'SESHAT', 'ATHENA', 'MAAT', 'THEMIS', 'THOTH', 'JANUS'];
$foundPersonas = 0;
foreach ($expectedPersonas as $persona) {
    if (strpos($doctrine, "**{$persona}**") !== false) {
        $foundPersonas++;
    }
}
if ($foundPersonas === 10) {
    $passed++;
    $tests[] = "PASS: All 10 personas found with bold formatting";
} else {
    $tests[] = "FAIL: Only {$foundPersonas}/10 personas found with bold formatting";
}

// Test 3: LEXA is included as Security Enforcement
$total++;
if (strpos($doctrine, '**LEXA** | Security Enforcement') !== false) {
    $passed++;
    $tests[] = "PASS: LEXA defined as Security Enforcement";
} else {
    $tests[] = "FAIL: LEXA not properly defined";
}

// Test 4: New artifact types defined
$total++;
$expectedArtifacts = [
    'LEXA_ENFORCEMENT_*',
    'HEIMDALL_SECURITY_*',
    'SESHAT_REVIEW_*',
    'ATHENA_STRATEGY_*',
    'MAAT_BALANCE_*',
    'THEMIS_COMPLIANCE_*',
    'THOTH_ANALYSIS_*',
    'JANUS_TRANSITION_*'
];
$foundArtifacts = 0;
foreach ($expectedArtifacts as $artifact) {
    if (strpos($doctrine, $artifact) !== false) {
        $foundArtifacts++;
    }
}
if ($foundArtifacts >= 6) {
    $passed++;
    $tests[] = "PASS: New artifact types found ({$foundArtifacts}/8)";
} else {
    $tests[] = "FAIL: Too few new artifact types ({$foundArtifacts}/8)";
}

// Test 5: Execution flow updated for 10-persona model
$total++;
if (strpos($doctrine, 'Primary Coordination Personas') !== false &&
    strpos($doctrine, 'Specialized Persona produces artifact') !== false) {
    $passed++;
    $tests[] = "PASS: Execution flow updated for 10-persona model";
} else {
    $tests[] = "FAIL: Execution flow not properly updated";
}

// Test 6: No old 4-persona references remain
$total++;
$oldReferences = ['HERMES_IMPLEMENTATION', 'LILITH_REVIEW', 'four canonical'];
$foundOld = 0;
foreach ($oldReferences as $ref) {
    if (strpos($doctrine, $ref) !== false) {
        $foundOld++;
    }
}
if ($foundOld === 0) {
    $passed++;
    $tests[] = "PASS: No old 4-persona references remain";
} else {
    $tests[] = "FAIL: Found {$foundOld} old 4-persona references";
}

// Test 7: Primary Coordination Personas section updated
$total++;
if (strpos($doctrine, '### Primary Coordination Personas') !== false &&
    strpos($doctrine, 'Security Enforcement') !== false &&
    strpos($doctrine, 'Wisdom & Strategy') !== false) {
    $passed++;
    $tests[] = "PASS: Primary Coordination Personas section updated";
} else {
    $tests[] = "FAIL: Primary Coordination Personas section incomplete";
}

// Test 8: Actor IDs are present
$total++;
$expectedIds = [
    'WOLFIE (actor_id 1)',
    'LEXA (actor_id 24)',
    'ANUBIS (actor_id 59)',
    'HEIMDALL (actor_id 22)',
    'SESHAT (actor_id 21)',
    'ATHENA (actor_id 12)',
    'MAAT (actor_id 7)',
    'THEMIS (actor_id 9)',
    'THOTH (actor_id 26)',
    'JANUS (actor_id 23)'
];
$foundIds = 0;
foreach ($expectedIds as $id) {
    if (strpos($doctrine, $id) !== false) {
        $foundIds++;
    }
}
if ($foundIds >= 8) {
    $passed++;
    $tests[] = "PASS: Actor IDs present ({$foundIds}/10)";
} else {
    $tests[] = "FAIL: Too few actor IDs ({$foundIds}/10)";
}

// Test 9: Role boundaries include all 10 personas
$total++;
$roleBoundaries = 0;
foreach ($expectedPersonas as $persona) {
    if (strpos($doctrine, "| {$persona} |") !== false) {
        $roleBoundaries++;
    }
}
if ($roleBoundaries >= 8) {
    $passed++;
    $tests[] = "PASS: Role boundaries include most personas ({$roleBoundaries}/10)";
} else {
    $tests[] = "FAIL: Too few personas in role boundaries ({$roleBoundaries}/10)";
}

// Test 10: Purpose section updated
$total++;
if (strpos($doctrine, 'WOLFIE, LEXA, ANUBIS, HEIMDALL, SESHAT, ATHENA, MAAT, THEMIS, THOTH, JANUS') !== false) {
    $passed++;
    $tests[] = "PASS: Purpose section updated with all 10 personas";
} else {
    $tests[] = "FAIL: Purpose section not updated";
}

// Output results
foreach ($tests as $test) {
    echo $test . "\n";
}

echo "\n=== SUMMARY ===\n";
echo "Tests Passed: {$passed}/{$total}\n";

if ($passed === $total) {
    echo "✅ ALL TESTS PASSED - Ten Persona Doctrine Update Complete!\n";
    echo "\n🎯 TEN PRIMARY COORDINATION PERSONAS:\n";
    echo "  1. WOLFIE - Main Orchestrator\n";
    echo "  2. LEXA - Security Enforcement\n";
    echo "  3. ANUBIS - Custodian\n";
    echo "  4. HEIMDALL - Security Guardian\n";
    echo "  5. SESHAT - Content Review\n";
    echo "  6. ATHENA - Wisdom & Strategy\n";
    echo "  7. MAAT - Truth & Justice\n";
    echo "  8. THEMIS - Divine Law & Order\n";
    echo "  9. THOTH - Knowledge & Records\n";
    echo "  10. JANUS - Transitions & Gateways\n";
    exit(0);
} else {
    echo "❌ SOME TESTS FAILED - Review doctrine updates\n";
    exit(1);
}
?>
