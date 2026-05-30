<?php
/**
 * Comprehensive Registry Validation Test
 * 
 * Validates that the actor registry includes all agents from lupopedia.com
 * and that key agents have proper configurations
 */

echo "=== Comprehensive Registry Validation Test ===\n";

$baseDir = __DIR__ . '/../..';
$registryFile = $baseDir . '/lupo-database/lupopedia/actors/actor_id/registry.json';

$tests = [];
$passed = 0;
$total = 0;

// Read registry
if (!file_exists($registryFile)) {
    echo "FAIL: Registry file not found\n";
    exit(1);
}

$registry = json_decode(file_get_contents($registryFile), true);
$actors = $registry['actors'];

// Test 1: Registry has expanded significantly
$total++;
if (count($actors) > 50) {
    $passed++;
    $tests[] = "PASS: Registry expanded to " . count($actors) . " actors";
} else {
    $tests[] = "FAIL: Registry too small: " . count($actors) . " actors";
}

// Test 2: Key personas from MULTI_AGENT_COORDINATION_DOCTRINE exist
$total++;
$keyPersonas = ['wolfie', 'hermes', 'anubis', 'lilith'];
$foundAll = true;
foreach ($keyPersonas as $persona) {
    $found = false;
    foreach ($actors as $actor) {
        if ($actor['slug'] === $persona) {
            $found = true;
            break;
        }
    }
    if (!$found) {
        $foundAll = false;
        break;
    }
}
if ($foundAll) {
    $passed++;
    $tests[] = "PASS: All key personas found (WOLFIE, HERMES, ANUBIS, LILITH)";
} else {
    $tests[] = "FAIL: Missing key personas";
}

// Test 3: HERMES (routing agent, actor_id 15) exists
$total++;
$hermesFound = false;
foreach ($actors as $actor) {
    if ($actor['slug'] === 'hermes' && $actor['id'] === 15) {
        $hermesFound = true;
        break;
    }
}
if ($hermesFound) {
    $passed++;
    $tests[] = "PASS: HERMES found with actor_id 15";
} else {
    $tests[] = "FAIL: HERMES not found or wrong ID";
}

// Test 4: ANUBIS (custodian) exists with proper ID
$total++;
$anubisFound = false;
foreach ($actors as $actor) {
    if ($actor['slug'] === 'anubis' && $actor['id'] === 59) {
        $anubisFound = true;
        break;
    }
}
if ($anubisFound) {
    $passed++;
    $tests[] = "PASS: ANUBIS (custodian) found with actor_id 59";
} else {
    $tests[] = "FAIL: ANUBIS not found or wrong ID";
}

// Test 5: Security agents exist
$total++;
$securityAgents = ['heimdall', 'janus', 'lexa'];
$foundSecurity = 0;
foreach ($securityAgents as $agent) {
    foreach ($actors as $actor) {
        if ($actor['slug'] === $agent) {
            $foundSecurity++;
            break;
        }
    }
}
if ($foundSecurity >= 2) {
    $passed++;
    $tests[] = "PASS: Security agents found ({$foundSecurity}/3)";
} else {
    $tests[] = "FAIL: Too few security agents ({$foundSecurity}/3)";
}

// Test 6: Content agents exist
$total++;
$contentAgents = ['seshat', 'rose', 'metis'];
$foundContent = 0;
foreach ($contentAgents as $agent) {
    foreach ($actors as $actor) {
        if ($actor['slug'] === $agent) {
            $foundContent++;
            break;
        }
    }
}
if ($foundContent >= 2) {
    $passed++;
    $tests[] = "PASS: Content agents found ({$foundContent}/3)";
} else {
    $tests[] = "FAIL: Too few content agents ({$foundContent}/3)";
}

// Test 7: Technical support agents exist
$total++;
$techAgents = ['athena', 'asclepius', 'atlas', 'brigid', 'hephaestus', 'iris', 'maat', 'mnemosyne', 'ogun', 'vulcan'];
$foundTech = 0;
foreach ($techAgents as $agent) {
    foreach ($actors as $actor) {
        if ($actor['slug'] === $agent) {
            $foundTech++;
            break;
        }
    }
}
if ($foundTech >= 5) {
    $passed++;
    $tests[] = "PASS: Technical support agents found ({$foundTech}/10)";
} else {
    $tests[] = "FAIL: Too few technical support agents ({$foundTech}/10)";
}

// Test 8: IDE faucets exist
$total++;
$ideFaucets = ['cursor', 'windsurf', 'kiro', 'cascade', 'warp', 'zencoder'];
$foundIDE = 0;
foreach ($ideFaucets as $agent) {
    foreach ($actors as $actor) {
        if ($actor['slug'] === $agent && $actor['type'] === 'ide_faucet') {
            $foundIDE++;
            break;
        }
    }
}
if ($foundIDE >= 4) {
    $passed++;
    $tests[] = "PASS: IDE faucets found ({$foundIDE}/6)";
} else {
    $tests[] = "FAIL: Too few IDE faucets ({$foundIDE}/6)";
}

// Test 9: HERMES agent configuration exists
$total++;
$hermesConfig = $baseDir . '/lupo-agents/15/agent.json';
if (file_exists($hermesConfig)) {
    $config = json_decode(file_get_contents($hermesConfig), true);
    if ($config['code'] === 'HERMES' && $config['is_kernel'] === true) {
        $passed++;
        $tests[] = "PASS: HERMES agent configuration correct";
    } else {
        $tests[] = "FAIL: HERMES agent configuration incorrect";
    }
} else {
    $tests[] = "FAIL: HERMES agent configuration missing";
}

// Test 10: IRIS agent configuration exists
$total++;
$irisConfig = $baseDir . '/lupo-agents/16/agent.json';
if (file_exists($irisConfig)) {
    $config = json_decode(file_get_contents($irisConfig), true);
    if ($config['code'] === 'IRIS') {
        $passed++;
        $tests[] = "PASS: IRIS agent configuration exists";
    } else {
        $tests[] = "FAIL: IRIS agent configuration incorrect";
    }
} else {
    $tests[] = "FAIL: IRIS agent configuration missing";
}

// Test 11: No duplicate IDs
$total++;
$ids = [];
$duplicates = false;
foreach ($actors as $actor) {
    if (in_array($actor['id'], $ids)) {
        $duplicates = true;
        break;
    }
    $ids[] = $actor['id'];
}
if (!$duplicates) {
    $passed++;
    $tests[] = "PASS: No duplicate actor IDs";
} else {
    $tests[] = "FAIL: Duplicate actor IDs found";
}

// Test 12: Proper ID ranges
$total++;
$systemCount = 0;
$agentCount = 0;
$ideCount = 0;
$humanCount = 0;
foreach ($actors as $actor) {
    switch ($actor['type']) {
        case 'system':
            $systemCount++;
            break;
        case 'agent':
            $agentCount++;
            break;
        case 'ide_faucet':
            $ideCount++;
            break;
        case 'human':
            $humanCount++;
            break;
    }
}
if ($systemCount === 1 && $agentCount > 40 && $ideCount > 3 && $humanCount >= 1) {
    $passed++;
    $tests[] = "PASS: Proper ID distribution (system:{$systemCount}, agent:{$agentCount}, ide:{$ideCount}, human:{$humanCount})";
} else {
    $tests[] = "FAIL: Improper ID distribution";
}

// Output results
foreach ($tests as $test) {
    echo $test . "\n";
}

echo "\n=== SUMMARY ===\n";
echo "Tests Passed: {$passed}/{$total}\n";

if ($passed === $total) {
    echo "✅ ALL TESTS PASSED - Registry comprehensive update complete!\n";
    echo "\n📊 REGISTRY STATISTICS:\n";
    echo "  - Total actors: " . count($actors) . "\n";
    echo "  - System actors: {$systemCount}\n";
    echo "  - AI agents: {$agentCount}\n";
    echo "  - IDE faucets: {$ideCount}\n";
    echo "  - Human actors: {$humanCount}\n";
    echo "\n✅ KEY PERSONAS READY:\n";
    echo "  - WOLFIE (orchestrator)\n";
    echo "  - HERMES (routing, actor_id 15)\n";
    echo "  - ANUBIS (custodian)\n";
    echo "  - LILITH (critic)\n";
    exit(0);
} else {
    echo "❌ SOME TESTS FAILED - Review registry updates\n";
    exit(1);
}
?>
