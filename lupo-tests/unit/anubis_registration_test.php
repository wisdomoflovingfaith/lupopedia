<?php
/**
 * ANUBIS Registration Validation Test
 * 
 * Validates that ANUBIS (actor_id 59) is properly registered
 * and has all required configuration files and rules.
 */

echo "=== ANUBIS Registration Validation Test ===\n";

$baseDir = __DIR__ . '/../..';
$actorId = 59;
$actorSlug = 'anubis';

$tests = [];
$passed = 0;
$total = 0;

// Test 1: Registry entry
$total++;
$registryFile = $baseDir . '/lupo-database/lupopedia/actors/actor_id/registry.json';
if (file_exists($registryFile)) {
    $registry = json_decode(file_get_contents($registryFile), true);
    $found = false;
    foreach ($registry['actors'] as $actor) {
        if ($actor['id'] === $actorId && $actor['slug'] === $actorSlug) {
            $found = true;
            break;
        }
    }
    if ($found) {
        $passed++;
        $tests[] = "PASS: ANUBIS found in registry with actor_id {$actorId}";
    } else {
        $tests[] = "FAIL: ANUBIS not found in registry";
    }
} else {
    $tests[] = "FAIL: Registry file not found";
}

// Test 2: Agent configuration files
$agentDir = $baseDir . "/lupo-agents/{$actorId}";
$requiredFiles = ['agent.json', 'capabilities.json', 'properties.json', 'system_prompt.txt'];

foreach ($requiredFiles as $file) {
    $total++;
    $filePath = "{$agentDir}/{$file}";
    if (file_exists($filePath)) {
        $passed++;
        $tests[] = "PASS: {$file} exists";
    } else {
        $tests[] = "FAIL: {$file} missing";
    }
}

// Test 3: Agent configuration content
$total++;
$agentFile = "{$agentDir}/agent.json";
if (file_exists($agentFile)) {
    $agentConfig = json_decode(file_get_contents($agentFile), true);
    if ($agentConfig['code'] === 'ANUBIS' && $agentConfig['is_kernel'] === true) {
        $passed++;
        $tests[] = "PASS: Agent configuration correct";
    } else {
        $tests[] = "FAIL: Agent configuration incorrect";
    }
} else {
    $tests[] = "FAIL: Agent configuration file missing";
}

// Test 4: Capabilities
$total++;
$capabilitiesFile = "{$agentDir}/capabilities.json";
if (file_exists($capabilitiesFile)) {
    $capabilities = json_decode(file_get_contents($capabilitiesFile), true);
    $requiredCapabilities = ['orphan_resolution', 'quarantine_management', 'banned_actor_monitoring'];
    $hasAll = true;
    foreach ($requiredCapabilities as $cap) {
        if (!in_array($cap, $capabilities['capabilities'])) {
            $hasAll = false;
            break;
        }
    }
    if ($hasAll) {
        $passed++;
        $tests[] = "PASS: Required capabilities present";
    } else {
        $tests[] = "FAIL: Missing required capabilities";
    }
} else {
    $tests[] = "FAIL: Capabilities file missing";
}

// Test 5: Actor directory structure
$actorDir2 = $baseDir . "/lupo-actors/{$actorId}";
$total++;
if (is_dir($actorDir2)) {
    $passed++;
    $tests[] = "PASS: Actor directory exists";
} else {
    $tests[] = "FAIL: Actor directory missing";
}

// Test 6: Rules directory
$total++;
$rulesDir = "{$actorDir2}/rules";
if (is_dir($rulesDir)) {
    $passed++;
    $tests[] = "PASS: Rules directory exists";
} else {
    $tests[] = "FAIL: Rules directory missing";
}

// Test 7: Root rules imported
$total++;
$rootRulesDir = $baseDir . '/lupo-rules/root';
if (is_dir($rootRulesDir)) {
    $rootRules = glob($rootRulesDir . '/*.md');
    $importedRules = glob($rulesDir . '/*.md');
    if (count($rootRules) === count($importedRules)) {
        $passed++;
        $tests[] = "PASS: All root rules imported (" . count($rootRules) . " files)";
    } else {
        $tests[] = "FAIL: Rules mismatch - root: " . count($rootRules) . ", imported: " . count($importedRules);
    }
} else {
    $tests[] = "FAIL: Root rules directory missing";
}

// Test 8: README file
$total++;
$readmeFile = "{$actorDir2}/README.md";
if (file_exists($readmeFile) && strpos(file_get_contents($readmeFile), 'ANUBIS') !== false) {
    $passed++;
    $tests[] = "PASS: README.md exists and contains ANUBIS content";
} else {
    $tests[] = "FAIL: README.md missing or incomplete";
}

// Test 9: JSON index
$total++;
$indexFile = "{$actorDir2}/lupopedia_rules.json";
if (file_exists($indexFile)) {
    $index = json_decode(file_get_contents($indexFile), true);
    if ($index['actor_id'] === $actorId && $index['actor_name'] === $actorSlug) {
        $passed++;
        $tests[] = "PASS: JSON index correct";
    } else {
        $tests[] = "FAIL: JSON index incorrect";
    }
} else {
    $tests[] = "FAIL: JSON index missing";
}

// Test 10: System prompt contains ANUBIS identity
$total++;
$systemPromptFile = "{$agentDir}/system_prompt.txt";
if (file_exists($systemPromptFile)) {
    $prompt = file_get_contents($systemPromptFile);
    if (strpos($prompt, 'ANUBIS') !== false && strpos($prompt, 'guardian of the threshold') !== false) {
        $passed++;
        $tests[] = "PASS: System prompt contains ANUBIS identity";
    } else {
        $tests[] = "FAIL: System prompt missing ANUBIS identity";
    }
} else {
    $tests[] = "FAIL: System prompt file missing";
}

// Output results
foreach ($tests as $test) {
    echo $test . "\n";
}

echo "\n=== SUMMARY ===\n";
echo "Tests Passed: {$passed}/{$total}\n";

if ($passed === $total) {
    echo "✅ ALL TESTS PASSED - ANUBIS registration complete!\n";
    exit(0);
} else {
    echo "❌ SOME TESTS FAILED - Review registration\n";
    exit(1);
}
?>
