<?php
/**
 * Cascade Rules Enforcement Test
 *
 * Validates .cascade rule artifacts against canonical root rules.
 * PHP 5.6 compatible, framework-free.
 *
 * Usage:
 *   php lupo-tests/unit/cascade_rules_enforcement.php
 */

$repoRoot = dirname(dirname(__DIR__));
$cascadeDir = $repoRoot . DIRECTORY_SEPARATOR . '.cascade';
$rootDir = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-rules' . DIRECTORY_SEPARATOR . 'root';

// Test results
$tests = array(
    'passed' => 0,
    'failed' => 0,
    'warnings' => 0,
    'errors' => array()
);

function test_result($test, $message, $details = '')
{
    global $tests;
    $tests[$test ? 'passed' : 'failed']++;
    echo ($test ? "PASS" : "FAIL") . ": $message";
    if ($details) {
        echo " ($details)";
    }
    echo "\n";
}

function test_cascade_artifacts_exist()
{
    global $tests, $cascadeDir;

    $jsonFile = $cascadeDir . DIRECTORY_SEPARATOR . 'lupopedia_rules.json';
    $readmeFile = $cascadeDir . DIRECTORY_SEPARATOR . 'README.md';
    $rulesDir = $cascadeDir . DIRECTORY_SEPARATOR . 'rules';

    $jsonPath = realpath($jsonFile);
    $readmePath = realpath($readmeFile);
    $rulesPath = realpath($rulesDir);

    if (!$jsonPath || !file_exists($jsonPath)) {
        $tests['errors'][] = "Missing .cascade/lupopedia_rules.json at path: " . $jsonFile;
        return false;
    }

    if (!$readmePath || !file_exists($readmePath)) {
        $tests['errors'][] = "Missing .cascade/README.md at path: " . $readmeFile;
        return false;
    }

    if (!$rulesPath || !is_dir($rulesPath)) {
        $tests['errors'][] = "Missing .cascade/rules/ directory at path: " . $rulesDir;
        return false;
    }

    test_result(true, "Cascade artifacts exist and are accessible");
    return true;
}

function test_cascade_json_parsable()
{
    global $tests, $cascadeDir;

    $jsonFile = $cascadeDir . DIRECTORY_SEPARATOR . 'lupopedia_rules.json';
    $jsonPath = realpath($jsonFile);

    if (!$jsonPath || !file_exists($jsonPath)) {
        $tests['errors'][] = "Cannot access .cascade/lupopedia_rules.json at path: " . $jsonFile;
        return false;
    }

    $content = file_get_contents($jsonPath);
    $json = json_decode($content, true);

    if ($json === null) {
        $tests['errors'][] = "Cannot parse .cascade/lupopedia_rules.json";
        return false;
    }

    if (!isset($json['rules']) || !is_array($json['rules'])) {
        $tests['errors'][] = "Invalid structure in .cascade/lupopedia_rules.json";
        return false;
    }

    test_result(true, "Cascade JSON is parsable and valid");
    return true;
}

function test_no_duplicate_rule_ids()
{
    global $tests, $cascadeDir;

    $jsonFile = $cascadeDir . DIRECTORY_SEPARATOR . 'lupopedia_rules.json';
    $content = file_get_contents($jsonFile);
    $json = json_decode($content, true);

    $ruleIds = array();
    foreach ($json['rules'] as $rule) {
        if (!isset($rule['id'])) {
            $tests['errors'][] = "Rule missing 'id' field in .cascade/lupopedia_rules.json";
            return false;
        }

        $ruleId = $rule['id'];
        if (isset($ruleIds[$ruleId])) {
            $tests['errors'][] = "Duplicate rule ID '$ruleId' found in .cascade/lupopedia_rules.json";
            return false;
        }
        $ruleIds[$ruleId] = true;
    }

    test_result(true, "No duplicate rule IDs found in Cascade rules");
    return true;
}

function test_rules_correspond_to_canonical()
{
    global $tests, $cascadeDir, $rootDir;

    $jsonFile = $cascadeDir . DIRECTORY_SEPARATOR . 'lupopedia_rules.json';
    $content = file_get_contents($jsonFile);
    $cascadeRules = json_decode($content, true);

    $files = glob($rootDir . DIRECTORY_SEPARATOR . '*.md');
    $canonicalRules = array();
    foreach ($files as $file) {
        $base = basename($file, '.md');
        if ($base === 'README') continue;

        $content = file_get_contents($file);
        if (preg_match('/\A---\R(.*?)\R---\R?/s', $content, $matches)) {
            $frontMatter = $matches[1];
            if (preg_match('/(?ms)^lupopedia\.rules:\s*\R(.*?)(?=^[A-Za-z0-9_.-]+:\s*|\z)/', $frontMatter, $matches)) {
                $rulesBlock = $matches[1];
                if (preg_match('/rule_id:\s*"([^"]*)"/', $rulesBlock, $idMatch)) {
                    $canonicalRules[$idMatch[1]] = true;
                }
            }
        }
    }

    $missingCanonical = array();
    foreach ($cascadeRules['rules'] as $rule) {
        if (!isset($rule['id'])) continue;
        if (!isset($canonicalRules[$rule['id']])) {
            $missingCanonical[] = $rule['id'];
        }
    }

    if (!empty($missingCanonical)) {
        $tests['errors'][] = "Cascade rules not found in canonical root: " . implode(', ', $missingCanonical);
        return false;
    }

    test_result(true, "All Cascade rules correspond to canonical root rules");
    return true;
}

function test_rule_files_have_headers()
{
    global $tests, $cascadeDir;

    $rulesDir = $cascadeDir . DIRECTORY_SEPARATOR . 'rules';
    $files = glob($rulesDir . DIRECTORY_SEPARATOR . '*.md');

    foreach ($files as $file) {
        $content = file_get_contents($file);
        if (!preg_match('/\A---\R.*?\R---\R?/s', $content)) {
            $tests['errors'][] = "Rule file missing LUPOPEDIA HEADERS: " . basename($file);
            return false;
        }

        if (!preg_match('/lupopedia\.init:/', $content)) {
            $tests['errors'][] = "Rule file missing lupopedia.init block: " . basename($file);
            return false;
        }

        if (!preg_match('/lupopedia\.headers:/', $content)) {
            $tests['errors'][] = "Rule file missing lupopedia.headers block: " . basename($file);
            return false;
        }

        if (!preg_match('/lupopedia\.rules:/', $content)) {
            $tests['errors'][] = "Rule file missing lupopedia.rules block: " . basename($file);
            return false;
        }

        if (!preg_match('/lupopedia\.footer:/', $content)) {
            $tests['errors'][] = "Rule file missing lupopedia.footer block: " . basename($file);
            return false;
        }
    }

    test_result(true, "All Cascade rule files have proper LUPOPEDIA HEADERS");
    return true;
}

function test_no_cross_target_contamination()
{
    global $tests, $cascadeDir;

    $rulesDir = $cascadeDir . DIRECTORY_SEPARATOR . 'rules';
    $files = glob($rulesDir . DIRECTORY_SEPARATOR . '*.md');

    foreach ($files as $file) {
        $content = file_get_contents($file);

        $forbiddenPatterns = array(
            '/\.cursor\//',
            '/\.kiro\//',
            '/\.idea\//',
            '/\.antigravity\//',
            '/\.windsurf\//'
        );

        foreach ($forbiddenPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                $tests['errors'][] = "Cross-target contamination found in " . basename($file) . ": " . $pattern;
                return false;
            }
        }
    }

    test_result(true, "No cross-target contamination in Cascade artifacts");
    return true;
}

// Run all tests
echo "Cascade Rules Enforcement Test\n";
echo "==============================\n\n";

test_cascade_artifacts_exist();
test_cascade_json_parsable();
test_no_duplicate_rule_ids();
test_rules_correspond_to_canonical();
test_rule_files_have_headers();
test_no_cross_target_contamination();

echo "\nTest Summary:\n";
echo "- Passed: " . $tests['passed'] . "\n";
echo "- Failed: " . $tests['failed'] . "\n";

if (!empty($tests['errors'])) {
    echo "\nErrors:\n";
    foreach ($tests['errors'] as $error) {
        echo "- $error\n";
    }
    exit(1);
} else {
    echo "\nAll tests passed!\n";
    exit(0);
}
