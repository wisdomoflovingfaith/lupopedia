<?php
/**
 * LEXA Rules Enforcement Validation Test
 * 
 * Validates that LEXA rule artifacts exist, are properly formatted,
 * and match canonical root rules. LEXA (actor_id 24) is the 
 * Law Enforcement eXecution Agent - Boundary Keeper.
 */

$repoRoot = dirname(__DIR__, 2);
$lexaDir = $repoRoot . DIRECTORY_SEPARATOR . '.lexa';
$lexaRulesDir = $lexaDir . DIRECTORY_SEPARATOR . 'rules';
$rootDir = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-rules' . DIRECTORY_SEPARATOR . 'root';

echo "=== LEXA Rules Enforcement Validation ===\n";
echo "LEXA (actor_id 24) - Law Enforcement eXecution Agent\n\n";

$tests = array();
$failures = 0;

// Test 1: Directory structure exists
$tests['lexa_directory_exists'] = is_dir($lexaDir);
$tests['lexa_rules_directory_exists'] = is_dir($lexaRulesDir);

if (!$tests['lexa_directory_exists']) {
    echo "FAIL: .lexa directory does not exist\n";
    $failures++;
} else {
    echo "PASS: .lexa directory exists\n";
}

if (!$tests['lexa_rules_directory_exists']) {
    echo "FAIL: .lexa/rules directory does not exist\n";
    $failures++;
} else {
    echo "PASS: .lexa/rules directory exists\n";
}

// Test 2: JSON rules index exists and is valid
$rulesJsonPath = $lexaDir . DIRECTORY_SEPARATOR . 'lupopedia_rules.json';
$tests['rules_json_exists'] = file_exists($rulesJsonPath);

if (!$tests['rules_json_exists']) {
    echo "FAIL: lupopedia_rules.json does not exist\n";
    $failures++;
} else {
    echo "PASS: lupopedia_rules.json exists\n";
    
    $jsonContent = file_get_contents($rulesJsonPath);
    $rulesData = json_decode($jsonContent, true);
    
    if ($rulesData === null) {
        echo "FAIL: lupopedia_rules.json is not valid JSON\n";
        $failures++;
        $tests['rules_json_valid'] = false;
    } else {
        echo "PASS: lupopedia_rules.json is valid JSON\n";
        $tests['rules_json_valid'] = true;
        
        if (isset($rulesData['rules']) && is_array($rulesData['rules'])) {
            echo "PASS: JSON contains rules array with " . count($rulesData['rules']) . " rules\n";
            $tests['rules_array_present'] = true;
        } else {
            echo "FAIL: JSON does not contain valid rules array\n";
            $failures++;
            $tests['rules_array_present'] = false;
        }
    }
}

// Test 3: README.md exists and contains LEXA-specific content
$readmePath = $lexaDir . DIRECTORY_SEPARATOR . 'README.md';
$tests['readme_exists'] = file_exists($readmePath);

if (!$tests['readme_exists']) {
    echo "FAIL: README.md does not exist\n";
    $failures++;
} else {
    echo "PASS: README.md exists\n";
    
    $readmeContent = file_get_contents($readmePath);
    if (strpos($readmeContent, 'LEXA') !== false && strpos($readmeContent, 'actor_id 24') !== false) {
        echo "PASS: README.md contains LEXA identity (actor_id 24)\n";
        $tests['readme_lexa_identity'] = true;
    } else {
        echo "FAIL: README.md missing LEXA identity\n";
        $failures++;
        $tests['readme_lexa_identity'] = false;
    }
    
    if (strpos($readmeContent, 'Law Enforcement eXecution Agent') !== false) {
        echo "PASS: README.md contains LEXA full designation\n";
        $tests['readme_lexa_designation'] = true;
    } else {
        echo "FAIL: README.md missing LEXA full designation\n";
        $failures++;
        $tests['readme_lexa_designation'] = false;
    }
}

// Test 4: Individual rule files exist and have proper headers
$rootFiles = glob($rootDir . DIRECTORY_SEPARATOR . '*.md');
$rootRuleFiles = array_filter($rootFiles, function($file) {
    return basename($file, '.md') !== 'README';
});

$expectedRuleCount = count($rootRuleFiles);
$actualRuleFiles = glob($lexaRulesDir . DIRECTORY_SEPARATOR . '*.md');
$actualRuleCount = count($actualRuleFiles);

echo "Expected root rules: $expectedRuleCount\n";
echo "Actual LEXA rule files: $actualRuleCount\n";

if ($actualRuleCount === $expectedRuleCount) {
    echo "PASS: All root rules have LEXA counterparts\n";
    $tests['rule_files_complete'] = true;
} else {
    echo "FAIL: Missing LEXA rule files\n";
    $failures++;
    $tests['rule_files_complete'] = false;
}

// Test 5: Sample rule file validation
if (!empty($actualRuleFiles)) {
    $sampleRulePath = $actualRuleFiles[0];
    $sampleContent = file_get_contents($sampleRulePath);
    
    // Check for LUPOPEDIA HEADERS
    if (preg_match('/^---\s*\n.*?lupopedia\.headers:/s', $sampleContent)) {
        echo "PASS: Sample rule file has LUPOPEDIA HEADERS\n";
        $tests['sample_has_headers'] = true;
    } else {
        echo "FAIL: Sample rule file missing LUPOPEDIA HEADERS\n";
        $failures++;
        $tests['sample_has_headers'] = false;
    }
    
    // Check for actor_id 24
    if (strpos($sampleContent, 'actor_id: 24') !== false) {
        echo "PASS: Sample rule file has correct actor_id (24)\n";
        $tests['sample_correct_actor_id'] = true;
    } else {
        echo "FAIL: Sample rule file has wrong actor_id\n";
        $failures++;
        $tests['sample_correct_actor_id'] = false;
    }
    
    // Check for actor_name lexa
    if (strpos($sampleContent, 'actor_name: "lexa"') !== false) {
        echo "PASS: Sample rule file has correct actor_name (lexa)\n";
        $tests['sample_correct_actor_name'] = true;
    } else {
        echo "FAIL: Sample rule file has wrong actor_name\n";
        $failures++;
        $tests['sample_correct_actor_name'] = false;
    }
    
    // Check for delegation_chain
    if (strpos($sampleContent, 'delegation_chain: "lexa:captain"') !== false) {
        echo "PASS: Sample rule file has correct delegation_chain\n";
        $tests['sample_correct_delegation'] = true;
    } else {
        echo "FAIL: Sample rule file has wrong delegation_chain\n";
        $failures++;
        $tests['sample_correct_delegation'] = false;
    }
}

// Test 6: Boundary Keeper specific validation
if (isset($rulesData) && isset($rulesData['rules'])) {
    $boundaryKeeperRules = array_filter($rulesData['rules'], function($rule) {
        return in_array($rule['id'], ['DB001', 'DB006', 'DB008', 'ACT001']) || 
               strpos($rule['text'], 'boundary') !== false ||
               strpos($rule['text'], 'integrity') !== false;
    });
    
    if (!empty($boundaryKeeperRules)) {
        echo "PASS: LEXA has boundary enforcement rules (" . count($boundaryKeeperRules) . " rules)\n";
        $tests['boundary_rules_present'] = true;
    } else {
        echo "FAIL: LEXA missing boundary enforcement rules\n";
        $failures++;
        $tests['boundary_rules_present'] = false;
    }
}

// Summary
echo "\n=== VALIDATION SUMMARY ===\n";
echo "Tests run: " . count($tests) . "\n";
echo "Failures: $failures\n";

if ($failures === 0) {
    echo "SUCCESS: LEXA rules enforcement validation PASSED\n";
    echo "LEXA (actor_id 24) is ready for boundary enforcement duties\n";
    exit(0);
} else {
    echo "FAILURE: LEXA rules enforcement validation FAILED\n";
    echo "LEXA requires attention before boundary enforcement deployment\n";
    exit(1);
}
