<?php
/**
 * Propagate canonical root rules to IDE agent outputs.
 *
 * Usage:
 *   php lupo-scripts/propagate_agent_rules.php
 *   php lupo-scripts/propagate_agent_rules.php --target=idea
 *   php lupo-scripts/propagate_agent_rules.php --target=jetbrains
 *   php lupo-scripts/propagate_agent_rules.php --target=cursor
 *   php lupo-scripts/propagate_agent_rules.php --target=kiro
 */

$repoRoot = dirname(__DIR__);
$rootDir = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-rules' . DIRECTORY_SEPARATOR . 'root';
$cursorDir = $repoRoot . DIRECTORY_SEPARATOR . '.cursor';
$cursorRulesDir = $cursorDir . DIRECTORY_SEPARATOR . 'rules';
$ideaDir = $repoRoot . DIRECTORY_SEPARATOR . '.idea';
$kiroDir = $repoRoot . DIRECTORY_SEPARATOR . '.kiro';
$windsurfDir = $repoRoot . DIRECTORY_SEPARATOR . '.windsurf';

$target = 'all';
foreach ($argv as $arg) {
    if (strpos($arg, '--target=') === 0) {
        $target = strtolower(substr($arg, 9));
    }
}
if ($target === 'jetbrains') {
    $target = 'idea';
}
$validTargets = array('all', 'cursor', 'idea', 'jetbrains', 'kiro', 'windsurf');
if (!in_array($target, $validTargets, true)) {
    fwrite(STDERR, "Unsupported target '$target'. Valid targets: all, cursor, idea, jetbrains, kiro, windsurf\n");
    exit(1);
}

function ensure_dir($dir)
{
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

function extract_front_matter_and_body($content)
{
    $frontMatter = '';
    $body = $content;
    if (preg_match('/\A---\R(.*?)\R---\R?/s', $content, $matches)) {
        $frontMatter = $matches[1];
        $body = substr($content, strlen($matches[0]));
    }
    return array($frontMatter, $body);
}

function extract_rules_block($frontMatter)
{
    if (preg_match('/(?ms)^lupopedia\.rules:\s*\R(.*?)(?=^[A-Za-z0-9_.-]+:\s*|\z)/', $frontMatter, $matches)) {
        return $matches[1];
    }
    return '';
}

function extract_yaml_scalar($block, $field, $default)
{
    $patterns = array(
        '/' . preg_quote($field, '/') . ':\s*"([^"]*)"/',
        '/' . preg_quote($field, '/') . ":\s*'([^']*)'/",
        '/' . preg_quote($field, '/') . ':\s*([^\r\n#]+)/'
    );
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $block, $matches)) {
            return trim($matches[1]);
        }
    }
    return $default;
}

function build_rules_from_root($rootDir)
{
    $files = glob($rootDir . DIRECTORY_SEPARATOR . '*.md');
    sort($files, SORT_STRING);
    $rules = array();
    $warnings = array();
    $processed = 0;

    foreach ($files as $file) {
        $base = basename($file, '.md');
        if ($base === 'README') {
            continue;
        }
        $processed++;

        $content = file_get_contents($file);
        list($frontMatter, $body) = extract_front_matter_and_body($content);
        $rulesBlock = extract_rules_block($frontMatter);
        if ($rulesBlock === '') {
            $warnings[] = "WARN: missing lupopedia.rules block in lupo-rules/root/$base.md (skipped)";
            continue;
        }

        $ruleId = extract_yaml_scalar($rulesBlock, 'rule_id', 'UNKNOWN');
        $ruleText = extract_yaml_scalar($rulesBlock, 'rule_text', 'Unknown Rule');
        $ruleScope = extract_yaml_scalar($rulesBlock, 'scope', 'all_agents');
        $ruleCategory = extract_yaml_scalar($rulesBlock, 'category', 'uncategorized');
        $ruleStatus = extract_yaml_scalar($rulesBlock, 'status', 'active');

        if ($ruleId === 'UNKNOWN') {
            $warnings[] = "WARN: missing rule_id in lupo-rules/root/$base.md (using UNKNOWN)";
        }
        if ($ruleText === 'Unknown Rule') {
            $warnings[] = "WARN: missing rule_text in lupo-rules/root/$base.md (using fallback)";
        }

        $rules[] = array(
            'id' => $ruleId,
            'text' => $ruleText,
            'enforcement' => 'error',
            'scope' => array($ruleScope),
            'slug' => $base,
            'source_path' => 'lupo-rules/root/' . $base . '.md',
            'category' => $ruleCategory,
            'status' => $ruleStatus,
            'body' => ltrim($body)
        );
    }

    return array($rules, $processed, $warnings);
}

function write_cursor_outputs($cursorDir, $cursorRulesDir, $rules)
{
    ensure_dir($cursorDir);
    ensure_dir($cursorRulesDir);

    $cursorJson = array('rules' => array());
    foreach ($rules as $rule) {
        $cursorJson['rules'][] = array(
            'id' => $rule['id'],
            'text' => $rule['text'],
            'enforcement' => $rule['enforcement'],
            'scope' => $rule['scope']
        );

        $body = str_replace('../../../', '../../', $rule['body']);
        $lines = explode("\n", trim($body));
        $filtered = array();
        $skipIdentity = true;
        foreach ($lines as $line) {
            if ($skipIdentity && preg_match('/^#\s*file:\s*/', $line)) {
                $skipIdentity = false;
                continue;
            }
            $filtered[] = $line;
        }
        $finalBody = implode("\n", $filtered);
        $mdc = "---\n";
        $mdc .= 'description: ' . str_replace('"', '\"', $rule['text']) . "\n";
        $mdc .= "alwaysApply: true\n";
        $mdc .= "---\n\n";
        $mdc .= $finalBody . "\n";
        file_put_contents($cursorRulesDir . DIRECTORY_SEPARATOR . $rule['slug'] . '.mdc', $mdc);
    }

    file_put_contents(
        $cursorDir . DIRECTORY_SEPARATOR . 'lupopedia_rules.json',
        json_encode($cursorJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    );
}

function write_kiro_outputs($kiroDir, $rules)
{
    ensure_dir($kiroDir);
    $kiroJson = array('rules' => array());
    foreach ($rules as $rule) {
        $kiroJson['rules'][] = array(
            'id' => $rule['id'],
            'text' => $rule['text'],
            'enforcement' => $rule['enforcement'],
            'scope' => $rule['scope']
        );
    }
    file_put_contents(
        $kiroDir . DIRECTORY_SEPARATOR . 'lupopedia_rules.json',
        json_encode($kiroJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    );
}

function write_windsurf_outputs($windsurfDir, $rules)
{
    ensure_dir($windsurfDir);
    
    // Create .windsurf/rules subdirectory
    $windsurfRulesDir = $windsurfDir . DIRECTORY_SEPARATOR . 'rules';
    ensure_dir($windsurfRulesDir);
    
    // Write .windsurf/lupopedia_rules.json
    $windsurfJson = array('rules' => array());
    foreach ($rules as $rule) {
        $windsurfJson['rules'][] = array(
            'id' => $rule['id'],
            'text' => $rule['text'],
            'enforcement' => $rule['enforcement'],
            'scope' => $rule['scope']
        );
    }
    file_put_contents(
        $windsurfDir . DIRECTORY_SEPARATOR . 'lupopedia_rules.json',
        json_encode($windsurfJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    );
    
    // Write individual rule files with LUPOPEDIA HEADERS
    foreach ($rules as $rule) {
        $body = str_replace('../../../', '../../', $rule['body']);
        
        $mdc = "---\n";
        $mdc .= "lupopedia.init:\n";
        $mdc .= "  file_identity: \"" . $rule['slug'] . ".md\"\n";
        $mdc .= "  artifact_type: \"windsurf_rule\"\n";
        $mdc .= "  artifact_kind: \"doctrine\"\n";
        $mdc .= "  namespace: \"windsurf\"\n";
        $mdc .= "  system_version: \"4.0.75\"\n";
        $mdc .= "  orchestrator_actor: \"windsurf\"\n";
        $mdc .= "  delegation_chain: \"windsurf:captain\"\n";
        $mdc .= "\n";
        $mdc .= "lupopedia.headers:\n";
        $mdc .= "  actor_id: 101\n";
        $mdc .= "  actor_name: \"windsurf\"\n";
        $mdc .= "  delegation_chain: \"windsurf:captain\"\n";
        $mdc .= "  lupopedia.version: \"4.0.75\"\n";
        $mdc .= "  lupopedia.schema: \"windsurf_rule\"\n";
        $mdc .= "  file_path_from_root: \".windsurf/rules/" . $rule['slug'] . ".md\"\n";
        $mdc .= "  last_modified_utc: \"" . date('Ymd') . "\"\n";
        $mdc .= "  system_version: \"4.0.75\"\n";
        $mdc .= "  source_path: \"lupo-rules/root/" . $rule['slug'] . ".md\"\n";
        $mdc .= "  artifact_type: \"rule\"\n";
        $mdc .= "  artifact_kind: \"windsurf_doctrine\"\n";
        $mdc .= "  purpose: \"Windsurf-specific rule derived from canonical root rule\"\n";
        $mdc .= "\n";
        $mdc .= "lupopedia.rules:\n";
        $mdc .= "  comment: \"Rule declaration and provenance block\"\n";
        $mdc .= "  declares:\n";
        $mdc .= "    - rule_id: \"" . $rule['id'] . "\"\n";
        $mdc .= "      rule_text: \"" . str_replace('"', '\\"', $rule['text']) . "\"\n";
        $mdc .= "      scope: \"" . (is_array($rule['scope']) ? implode(', ', $rule['scope']) : $rule['scope']) . "\"\n";
        $mdc .= "      category: \"" . $rule['category'] . "\"\n";
        $mdc .= "      status: \"" . $rule['status'] . "\"\n";
        $mdc .= "  imports: []\n";
        $mdc .= "  overrides: []\n";
        $mdc .= "  provenance:\n";
        $mdc .= "    authored_by: \"wolfie\"\n";
        $mdc .= "    authored_date: \"" . date('Ymd') . "\"\n";
        $mdc .= "    last_reviewed_by: \"windsurf\"\n";
        $mdc .= "    last_reviewed_date: \"" . date('Ymd') . "\"\n";
        $mdc .= "    version: \"1.0\"\n";
        $mdc .= "    status: \"active\"\n";
        $mdc .= "lupopedia.footer:\n";
        $mdc .= "  version: \"4.0.75\"\n";
        $mdc .= "  last_verified: \"" . date('Ymd') . "\"\n";
        $mdc .= "  last_verified_by: \"windsurf\"\n";
        $mdc .= "  orchestrator: \"windsurf\"\n";
        $mdc .= "  next_action:\n";
        $mdc .= "    - \"Keep in sync with canonical root rules\"\n";
        $mdc .= "---\n\n";
        $mdc .= $body . "\n";
        
        file_put_contents($windsurfRulesDir . DIRECTORY_SEPARATOR . $rule['slug'] . '.md', $mdc);
    }
    
    // Write .windsurf/README.md
    $readme = "---\n";
    $readme .= "lupopedia.init:\n";
    $readme .= "  file_identity: \"README.md\"\n";
    $readme .= "  artifact_type: \"windsurf_guide\"\n";
    $readme .= "  artifact_kind: \"documentation\"\n";
    $readme .= "  namespace: \"windsurf\"\n";
    $readme .= "  system_version: \"4.0.75\"\n";
    $readme .= "  orchestrator_actor: \"windsurf\"\n";
    $readme .= "  delegation_chain: \"windsurf:captain\"\n";
    $readme .= "\n";
    $readme .= "lupopedia.headers:\n";
    $readme .= "  actor_id: 101\n";
    $readme .= "  actor_name: \"windsurf\"\n";
    $readme .= "  delegation_chain: \"windsurf:captain\"\n";
    $readme .= "  lupopedia.version: \"4.0.75\"\n";
    $readme .= "  lupopedia.schema: \"windsurf_guide\"\n";
    $readme .= "  file_path_from_root: \".windsurf/README.md\"\n";
    $readme .= "  last_modified_utc: \"" . date('Ymd') . "\"\n";
    $readme .= "  system_version: \"4.0.75\"\n";
    $readme .= "  artifact_type: \"guide\"\n";
    $readme .= "  artifact_kind: \"documentation\"\n";
    $readme .= "  purpose: \"Guide for Windsurf rule system and propagation\"\n";
    $readme .= "\n";
    $readme .= "lupopedia.footer:\n";
    $readme .= "  version: \"4.0.75\"\n";
    $readme .= "  last_verified: \"" . date('Ymd') . "\"\n";
    $readme .= "  last_verified_by: \"windsurf\"\n";
    $readme .= "  orchestrator: \"windsurf\"\n";
    $readme .= "  next_action:\n";
    $readme .= "    - \"Run propagation: php lupo-scripts/propagate_agent_rules.php --target=windsurf\"\n";
    $readme .= "---\n\n";
    $readme .= "# Windsurf Rules Guide\n\n";
    $readme .= "This directory contains Windsurf-specific rule artifacts derived from canonical root rules.\n\n";
    $readme .= "## Files\n\n";
    $readme .= "- **lupopedia_rules.json** - Machine-readable rule index\n";
    $readme .= "- **rules/** - Individual rule files with LUPOPEDIA HEADERS\n\n";
    $readme .= "## Propagation\n\n";
    $readme .= "Run: `php lupo-scripts/propagate_agent_rules.php --target=windsurf`\n\n";
    $readme .= "## Source\n\n";
    $readme .= "All rules are derived from canonical root rules in `lupo-rules/root/`.\n";
    $readme .= "See [lupo-rules/root/README.md](../../../lupo-rules/root/README.md) for canonical rule documentation.\n";
    
    file_put_contents($windsurfDir . DIRECTORY_SEPARATOR . 'README.md', $readme);
}

function write_idea_outputs($ideaDir, $rules)
{
    ensure_dir($ideaDir);
    $xml = "<component name=\"LupopediaRules\">\n";
    $xml .= "  <rules>\n";
    foreach ($rules as $rule) {
        $scope = isset($rule['scope'][0]) ? $rule['scope'][0] : 'all_agents';
        $xml .= '    <rule id="' . htmlspecialchars($rule['id'], ENT_QUOTES, 'UTF-8') . '" enforcement="' . htmlspecialchars($rule['enforcement'], ENT_QUOTES, 'UTF-8') . '">' . "\n";
        $xml .= '      <text>' . htmlspecialchars($rule['text'], ENT_QUOTES, 'UTF-8') . "</text>\n";
        $xml .= '      <scope>' . htmlspecialchars($scope, ENT_QUOTES, 'UTF-8') . "</scope>\n";
        $xml .= '      <source_path>' . htmlspecialchars($rule['source_path'], ENT_QUOTES, 'UTF-8') . "</source_path>\n";
        $xml .= '      <category>' . htmlspecialchars($rule['category'], ENT_QUOTES, 'UTF-8') . "</category>\n";
        $xml .= '      <status>' . htmlspecialchars($rule['status'], ENT_QUOTES, 'UTF-8') . "</status>\n";
        $xml .= "    </rule>\n";
    }
    $xml .= "  </rules>\n";
    $xml .= "</component>\n";
    file_put_contents($ideaDir . DIRECTORY_SEPARATOR . 'lupopedia_rules.xml', $xml);
}

list($rules, $processedCount, $warnings) = build_rules_from_root($rootDir);

if ($target === 'all' || $target === 'cursor') {
    write_cursor_outputs($cursorDir, $cursorRulesDir, $rules);
}
if ($target === 'all' || $target === 'kiro') {
    write_kiro_outputs($kiroDir, $rules);
}
if ($target === 'all' || $target === 'idea') {
    write_idea_outputs($ideaDir, $rules);
}
if ($target === 'all' || $target === 'windsurf') {
    write_windsurf_outputs($windsurfDir, $rules);
}

foreach ($warnings as $warning) {
    fwrite(STDERR, $warning . "\n");
}

$parsedCount = count($rules);
echo "Processed $processedCount root files; parsed $parsedCount rules; warnings: " . count($warnings) . "; target: $target\n";
