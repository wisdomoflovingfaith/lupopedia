<?php
/**
 * Kiro rules enforcement test.
 *
 * Validates that .kiro/lupopedia_rules.json and .kiro/rules/*.md exist,
 * are parseable, have no duplicate rule IDs, and that each rule has a
 * corresponding .md file. Standalone PHP 5.6 compatible; no test framework.
 *
 * Usage: php lupo-tests/unit/kiro_rules_enforcement.php
 * Exit: 0 on pass, 1 on failure.
 */

$repoRoot = dirname(dirname(__DIR__));
$kiroJsonPath = $repoRoot . DIRECTORY_SEPARATOR . '.kiro' . DIRECTORY_SEPARATOR . 'lupopedia_rules.json';
$kiroRulesDir = $repoRoot . DIRECTORY_SEPARATOR . '.kiro' . DIRECTORY_SEPARATOR . 'rules';

$failures = array();
$rules = array();

// 1. JSON exists and is loadable
if (!is_file($kiroJsonPath)) {
    $failures[] = '.kiro/lupopedia_rules.json does not exist';
} else {
    $raw = file_get_contents($kiroJsonPath);
    if ($raw === false) {
        $failures[] = 'Could not read .kiro/lupopedia_rules.json';
    } else {
        $data = json_decode($raw, true);
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $failures[] = 'Invalid JSON in .kiro/lupopedia_rules.json: ' . json_last_error_msg();
        } elseif (!isset($data['rules']) || !is_array($data['rules'])) {
            $failures[] = '.kiro/lupopedia_rules.json must have a top-level "rules" array';
        } else {
            $rules = $data['rules'];
            if (count($rules) === 0) {
                $failures[] = 'rules array is empty';
            }
            $seenIds = array();
            foreach ($rules as $index => $rule) {
                if (!is_array($rule)) {
                    $failures[] = "Rule at index $index is not an array";
                    continue;
                }
                $required = array('id', 'text', 'enforcement', 'scope');
                foreach ($required as $key) {
                    if (!array_key_exists($key, $rule)) {
                        $failures[] = "Rule at index $index missing field: $key";
                    }
                }
                if (array_key_exists('id', $rule)) {
                    $id = $rule['id'];
                    if (isset($seenIds[$id])) {
                        $failures[] = "Duplicate rule_id: $id";
                    }
                    $seenIds[$id] = true;
                }
                $slug = isset($rule['slug']) ? $rule['slug'] : (isset($rule['source_path']) ? basename($rule['source_path'], '.md') : null);
                if ($slug !== null && $slug !== '') {
                    $mdPath = $kiroRulesDir . DIRECTORY_SEPARATOR . $slug . '.md';
                    if (!is_file($mdPath)) {
                        $failures[] = "Missing .kiro/rules/$slug.md for rule " . (isset($rule['id']) ? $rule['id'] : "index $index");
                    }
                } else {
                    $failures[] = "Rule at index $index has no slug or source_path to derive .md filename";
                }
            }
        }
    }
}

if (count($rules) > 0 && !is_dir($kiroRulesDir)) {
    $failures[] = '.kiro/rules/ directory does not exist';
}

if (count($failures) > 0) {
    fwrite(STDERR, "KIRO RULES ENFORCEMENT: FAIL\n");
    foreach ($failures as $f) {
        fwrite(STDERR, "  - " . $f . "\n");
    }
    exit(1);
}

$ruleCount = isset($rules) ? count($rules) : 0;
echo "KIRO RULES ENFORCEMENT: PASS\n";
echo "  Rules in .kiro/lupopedia_rules.json: $ruleCount\n";
echo "  All entries have id, text, enforcement, scope; no duplicate IDs.\n";
echo "  Corresponding .kiro/rules/<slug>.md files present.\n";
exit(0);
