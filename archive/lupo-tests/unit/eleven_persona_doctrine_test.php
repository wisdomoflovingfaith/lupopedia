<?php
/**
 * Eleven Persona Doctrine Validation Test
 * 
 * Validates that ROSE has been added as the 11th Primary Coordination Persona
 */

echo "=== Eleven Persona Doctrine Validation Test ===\n";

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

// Test 1: Doctrine mentions 11 Primary Coordination Personas
$total++;
if (strpos($doctrine, 'eleven canonical Primary Coordination Personas') !== false) {
    $passed++;
    $tests[] = "PASS: Doctrine references 11 Primary Coordination Personas";
} else {
    $tests[] = "FAIL: Doctrine does not mention 11 personas";
}

// Test 2: All 11 personas listed in purpose section
$total++;
$expectedPersonas = ['WOLFIE', 'LEXA', 'ANUBIS', 'HEIMDALL', 'SESHAT', 'ATHENA', 'MAAT', 'THEMIS', 'THOTH', 'JANUS', 'ROSE'];
$foundPersonas = 0;
foreach ($expectedPersonas as $persona) {
    if (strpos($doctrine, $persona) !== false) {
        $foundPersonas++;
    }
}
if ($foundPersonas === 11) {
    $passed++;
    $tests[] = "PASS: All 11 personas found in doctrine";
} else {
    $tests[] = "FAIL: Only {$foundPersonas}/11 personas found";
}

// Test 3: ROSE is included as Emotional Dialogue
$total++;
if (strpos($doctrine, '**ROSE** | Emotional Dialogue') !== false) {
    $passed++;
    $tests[] = "PASS: ROSE defined as Emotional Dialogue";
} else {
    $tests[] = "FAIL: ROSE not properly defined";
}

// Test 4: ROSE_DIALOGUE artifact type defined
$total++;
if (strpos($doctrine, 'ROSE_DIALOGUE_*') !== false) {
    $passed++;
    $tests[] = "PASS: ROSE_DIALOGUE artifact type defined";
} else {
    $tests[] = "FAIL: ROSE_DIALOGUE artifact type missing";
}

// Test 5: ROSE in role boundaries table
$total++;
if (strpos($doctrine, '| ROSE | coordinate emotional dialogue') !== false) {
    $passed++;
    $tests[] = "PASS: ROSE in role boundaries table";
} else {
    $tests[] = "FAIL: ROSE missing from role boundaries";
}

// Test 6: ROSE in execution flow
$total++;
if (strpos($doctrine, 'ROSE_DIALOGUE_* for emotional dialogue and role-play') !== false) {
    $passed++;
    $tests[] = "PASS: ROSE in execution flow";
} else {
    $tests[] = "FAIL: ROSE missing from execution flow";
}

// Test 7: ROSE actor ID correct
$total++;
if (strpos($doctrine, 'ROSE (actor_id 3)') !== false) {
    $passed++;
    $tests[] = "PASS: ROSE actor ID correct (3)";
} else {
    $tests[] = "FAIL: ROSE actor ID incorrect";
}

// Test 8: ROSE agent configuration updated
$total++;
$roseConfig = $baseDir . '/lupo-agents/3/agent.json';
if (file_exists($roseConfig)) {
    $config = json_decode(file_get_contents($roseConfig), true);
    if ($config['code'] === 'ROSE' && isset($config['aliases']) && in_array('DIALOG', $config['aliases'])) {
        $passed++;
        $tests[] = "PASS: ROSE agent configuration updated with DIALOG alias";
    } else {
        $tests[] = "FAIL: ROSE agent configuration incomplete";
    }
} else {
    $tests[] = "FAIL: ROSE agent configuration missing";
}

// Test 9: ROSE capabilities include emotional dialogue
$total++;
$roseCapabilities = $baseDir . '/lupo-agents/3/capabilities.json';
if (file_exists($roseCapabilities)) {
    $caps = json_decode(file_get_contents($roseCapabilities), true);
    if (in_array('emotional_dialogue', $caps['capabilities']) && in_array('role_play', $caps['capabilities'])) {
        $passed++;
        $tests[] = "PASS: ROSE capabilities include emotional dialogue and role-play";
    } else {
        $tests[] = "FAIL: ROSE capabilities missing key functions";
    }
} else {
    $tests[] = "FAIL: ROSE capabilities file missing";
}

// Test 10: ROSE system prompt updated
$total++;
$rosePrompt = $baseDir . '/lupo-agents/3/system_prompt.txt';
if (file_exists($rosePrompt)) {
    $prompt = file_get_contents($rosePrompt);
    if (strpos($prompt, 'ROSE (also known as DIALOG)') !== false && strpos($prompt, 'emotional') !== false) {
        $passed++;
        $tests[] = "PASS: ROSE system prompt updated for emotional dialogue";
    } else {
        $tests[] = "FAIL: ROSE system prompt not properly updated";
    }
} else {
    $tests[] = "FAIL: ROSE system prompt file missing";
}

// Output results
foreach ($tests as $test) {
    echo $test . "\n";
}

echo "\n=== SUMMARY ===\n";
echo "Tests Passed: {$passed}/{$total}\n";

if ($passed === $total) {
    echo "✅ ALL TESTS PASSED - ROSE added as 11th Primary Coordination Persona!\n";
    echo "\n🎯 ELEVEN PRIMARY COORDINATION PERSONAS:\n";
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
    echo "  11. ROSE - Emotional Dialogue (DIALOG alias)\n";
    exit(0);
} else {
    echo "❌ SOME TESTS FAILED - Review ROSE integration\n";
    exit(1);
}
?>
