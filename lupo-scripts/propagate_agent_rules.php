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
 *   php lupo-scripts/propagate_agent_rules.php --target=cascade
 */

$repoRoot = dirname(__DIR__);
$rootDir = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-rules' . DIRECTORY_SEPARATOR . 'root';
$cursorDir = $repoRoot . DIRECTORY_SEPARATOR . '.cursor';
$cursorRulesDir = $cursorDir . DIRECTORY_SEPARATOR . 'rules';
$ideaDir = $repoRoot . DIRECTORY_SEPARATOR . '.idea';
$kiroDir = $repoRoot . DIRECTORY_SEPARATOR . '.kiro';
$windsurfDir = $repoRoot . DIRECTORY_SEPARATOR . '.windsurf';
$cascadeDir = $repoRoot . DIRECTORY_SEPARATOR . '.cascade';
$lilithDir = $repoRoot . DIRECTORY_SEPARATOR . '.lilith';
$lexaDir = $repoRoot . DIRECTORY_SEPARATOR . '.lexa';

$target = 'all';
foreach ($argv as $arg) {
    if (strpos($arg, '--target=') === 0) {
        $target = strtolower(substr($arg, 9));
    }
}
if ($target === 'jetbrains') {
    $target = 'idea';
}
$validTargets = array('all', 'cascade', 'cursor', 'idea', 'jetbrains', 'kiro', 'windsurf', 'lilith', 'lexa');
if (!in_array($target, $validTargets, true)) {
    fwrite(STDERR, "Unsupported target '$target'. Valid targets: all, cascade, cursor, idea, jetbrains, kiro, windsurf, lilith, lexa\n");
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
            'scope' => $rule['scope'],
            'source_path' => $rule['source_path'],
            'slug' => $rule['slug']
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
    $kiroRulesDir = $kiroDir . DIRECTORY_SEPARATOR . 'rules';
    ensure_dir($kiroRulesDir);

    $kiroJson = array('rules' => array());
    foreach ($rules as $rule) {
        $kiroJson['rules'][] = array(
            'id' => $rule['id'],
            'text' => $rule['text'],
            'enforcement' => $rule['enforcement'],
            'scope' => $rule['scope'],
            'source_path' => $rule['source_path'],
            'slug' => $rule['slug'],
            'category' => $rule['category'],
            'status' => $rule['status']
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
        $mdc .= "lupopedia.headers:\n";
        $mdc .= "  actor_id: 100\n";
        $mdc .= "  actor_name: \"kiro\"\n";
        $mdc .= "  delegation_chain: \"kiro:root\"\n";
        $mdc .= "  lupopedia.version: \"4.0.76\"\n";
        $mdc .= "  lupopedia.schema: \"kiro_rule\"\n";
        $mdc .= "  file_path_from_root: \".kiro/rules/" . $rule['slug'] . ".md\"\n";
        $mdc .= "  last_modified_utc: \"" . date('Ymd') . "\"\n";
        $mdc .= "  system_version: \"4.0.76\"\n";
        $mdc .= "  source_path: \"lupo-rules/root/" . $rule['slug'] . ".md\"\n";
        $mdc .= "  artifact_type: \"rule\"\n";
        $mdc .= "  artifact_kind: \"kiro_doctrine\"\n";
        $mdc .= "---\n\n";
        $mdc .= $finalBody . "\n";
        
        file_put_contents($kiroRulesDir . DIRECTORY_SEPARATOR . $rule['slug'] . '.md', $mdc);
    }
    file_put_contents(
        $kiroDir . DIRECTORY_SEPARATOR . 'lupopedia_rules.json',
        json_encode($kiroJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    );

    // Write .kiro/README.md
    $readme = "---\n";
    $readme .= "lupopedia.headers:\n";
    $readme .= "  actor_id: 100\n";
    $readme .= "  actor_name: \"kiro\"\n";
    $readme .= "  delegation_chain: \"kiro:root\"\n";
    $readme .= "  lupopedia.version: \"4.0.76\"\n";
    $readme .= "  lupopedia.schema: \"kiro_guide\"\n";
    $readme .= "  file_path_from_root: \".kiro/README.md\"\n";
    $readme .= "  last_modified_utc: \"" . date('Ymd') . "\"\n";
    $readme .= "  system_version: \"4.0.76\"\n";
    $readme .= "  artifact_type: \"guide\"\n";
    $readme .= "  artifact_kind: \"documentation\"\n";
    $readme .= "  purpose: \"Guide for Kiro rule system and propagation\"\n";
    $readme .= "---\n\n";
    $readme .= "# Kiro Rules Guide\n\n";
    $readme .= "This directory contains Kiro-specific rule artifacts derived from canonical root rules.\n\n";
    $readme .= "## Files\n\n";
    $readme .= "- **lupopedia_rules.json** - Machine-readable rule index\n";
    $readme .= "- **rules/** - Individual rule files with LUPOPEDIA HEADERS\n\n";
    $readme .= "## Propagation\n\n";
    $readme .= "Run: `php lupo-scripts/propagate_agent_rules.php --target=kiro`\n\n";
    $readme .= "## Source\n\n";
    $readme .= "All rules are derived from canonical root rules in `lupo-rules/root/`.\n";
    $readme .= "See [lupo-rules/root/README.md](../lupo-rules/root/README.md) for canonical rule documentation.\n";
    
    file_put_contents($kiroDir . DIRECTORY_SEPARATOR . 'README.md', $readme);
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
            'scope' => $rule['scope'],
            'source_path' => $rule['source_path'],
            'slug' => $rule['slug'],
            'category' => $rule['category'],
            'status' => $rule['status']
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
        $mdc .= "  system_version: \"4.0.76\"\n";
        $mdc .= "  orchestrator_actor: \"windsurf\"\n";
        $mdc .= "  delegation_chain: \"windsurf:captain\"\n";
        $mdc .= "\n";
        $mdc .= "lupopedia.headers:\n";
        $mdc .= "  actor_id: 101\n";
        $mdc .= "  actor_name: \"windsurf\"\n";
        $mdc .= "  delegation_chain: \"windsurf:captain\"\n";
        $mdc .= "  lupopedia.version: \"4.0.76\"\n";
        $mdc .= "  lupopedia.schema: \"windsurf_rule\"\n";
        $mdc .= "  file_path_from_root: \".windsurf/rules/" . $rule['slug'] . ".md\"\n";
        $mdc .= "  last_modified_utc: \"" . date('Ymd') . "\"\n";
        $mdc .= "  system_version: \"4.0.76\"\n";
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
        $mdc .= "  version: \"4.0.76\"\n";
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
    $readme .= "  system_version: \"4.0.76\"\n";
    $readme .= "  orchestrator_actor: \"windsurf\"\n";
    $readme .= "  delegation_chain: \"windsurf:captain\"\n";
    $readme .= "\n";
    $readme .= "lupopedia.headers:\n";
    $readme .= "  actor_id: 101\n";
    $readme .= "  actor_name: \"windsurf\"\n";
    $readme .= "  delegation_chain: \"windsurf:captain\"\n";
    $readme .= "  lupopedia.version: \"4.0.76\"\n";
    $readme .= "  lupopedia.schema: \"windsurf_guide\"\n";
    $readme .= "  file_path_from_root: \".windsurf/README.md\"\n";
    $readme .= "  last_modified_utc: \"" . date('Ymd') . "\"\n";
    $readme .= "  system_version: \"4.0.76\"\n";
    $readme .= "  artifact_type: \"guide\"\n";
    $readme .= "  artifact_kind: \"documentation\"\n";
    $readme .= "  purpose: \"Guide for Windsurf rule system and propagation\"\n";
    $readme .= "\n";
    $readme .= "lupopedia.footer:\n";
    $readme .= "  version: \"4.0.76\"\n";
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

function write_lilith_outputs($lilithDir, $rules)
{
    ensure_dir($lilithDir);
    $lilithRulesDir = $lilithDir . DIRECTORY_SEPARATOR . 'rules';
    ensure_dir($lilithRulesDir);

    $lilithJson = array('rules' => array());
    foreach ($rules as $rule) {
        $lilithJson['rules'][] = array(
            'id' => $rule['id'],
            'text' => $rule['text'],
            'enforcement' => $rule['enforcement'],
            'scope' => $rule['scope'],
            'source_path' => $rule['source_path'],
            'slug' => $rule['slug'],
            'category' => $rule['category'],
            'status' => $rule['status']
        );

        $body = str_replace('../../../', '../../', $rule['body']);

        $mdc = "---\n";
        $mdc .= "lupopedia.headers:\n";
        $mdc .= "  actor_id: 2\n";
        $mdc .= "  actor_name: \"lilith\"\n";
        $mdc .= "  delegation_chain: \"lilith:root\"\n";
        $mdc .= "  lupopedia.version: \"4.0.79\"\n";
        $mdc .= "  lupopedia.schema: \"lilith_rule\"\n";
        $mdc .= "  file_path_from_root: \".lilith/rules/" . $rule['slug'] . ".md\"\n";
        $mdc .= "  last_modified_utc: \"" . date('Ymd') . "\"\n";
        $mdc .= "  system_version: \"4.0.79\"\n";
        $mdc .= "  source_path: \"lupo-rules/root/" . $rule['slug'] . ".md\"\n";
        $mdc .= "  artifact_type: \"rule\"\n";
        $mdc .= "  artifact_kind: \"lilith_doctrine\"\n";
        $mdc .= "  purpose: \"Lilith-specific review and dissent rule derivative\"\n";
        $mdc .= "---\n\n";
        $mdc .= $body . "\n";

        file_put_contents($lilithRulesDir . DIRECTORY_SEPARATOR . $rule['slug'] . '.md', $mdc);
    }

    file_put_contents(
        $lilithDir . DIRECTORY_SEPARATOR . 'lupopedia_rules.json',
        json_encode($lilithJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    );

    $readme = "---\n";
    $readme .= "lupopedia.headers:\n";
    $readme .= "  actor_id: 2\n";
    $readme .= "  actor_name: \"lilith\"\n";
    $readme .= "  delegation_chain: \"lilith:root\"\n";
    $readme .= "  lupopedia.version: \"4.0.79\"\n";
    $readme .= "  lupopedia.schema: \"lilith_guide\"\n";
    $readme .= "  file_path_from_root: \".lilith/README.md\"\n";
    $readme .= "  last_modified_utc: \"" . date('Ymd') . "\"\n";
    $readme .= "  system_version: \"4.0.79\"\n";
    $readme .= "  artifact_type: \"guide\"\n";
    $readme .= "  artifact_kind: \"documentation\"\n";
    $readme .= "  purpose: \"Lilith rule propagation status and guidance\"\n";
    $readme .= "---\n\n";
    $readme .= "# Lilith Rules Guide\n\n";
    $readme .= "This directory contains Lilith-specific rule artifacts and non-interference policy derived from canonical root rules in `lupo-rules/root/`.\n\n";
    $readme .= "## Propagation\n\n";
    $readme .= "Run: `php lupo-scripts/propagate_agent_rules.php --target=lilith`\n\n";
    $readme .= "## Source\n\n";
    $readme .= "All rules are derived from canonical root rules in `lupo-rules/root/`.\n";
    $readme .= "See [lupo-rules/root/README.md](../lupo-rules/root/README.md) for canonical rule documentation.\n";

    file_put_contents($lilithDir . DIRECTORY_SEPARATOR . 'README.md', $readme);
}

function write_cascade_outputs($cascadeDir, $rules)
{
    ensure_dir($cascadeDir);
    $cascadeRulesDir = $cascadeDir . DIRECTORY_SEPARATOR . 'rules';
    ensure_dir($cascadeRulesDir);

    $cascadeJson = array('rules' => array());
    foreach ($rules as $rule) {
        $cascadeJson['rules'][] = array(
            'id' => $rule['id'],
            'text' => $rule['text'],
            'enforcement' => $rule['enforcement'],
            'scope' => $rule['scope'],
            'source_path' => $rule['source_path'],
            'slug' => $rule['slug'],
            'category' => $rule['category'],
            'status' => $rule['status']
        );
    }
    file_put_contents(
        $cascadeDir . DIRECTORY_SEPARATOR . 'lupopedia_rules.json',
        json_encode($cascadeJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    );

    foreach ($rules as $rule) {
        $body = str_replace('../../../', '../../', $rule['body']);
        $mdc = "---\n";
        $mdc .= "lupopedia.init:\n";
        $mdc .= "  file_identity: \"" . $rule['slug'] . ".md\"\n";
        $mdc .= "  artifact_type: \"cascade_rule\"\n";
        $mdc .= "  artifact_kind: \"doctrine\"\n";
        $mdc .= "  namespace: \"cascade\"\n";
        $mdc .= "  system_version: \"4.0.76\"\n";
        $mdc .= "  orchestrator_actor: \"cascade\"\n";
        $mdc .= "  delegation_chain: \"cascade:captain\"\n";
        $mdc .= "\n";
        $mdc .= "lupopedia.headers:\n";
        $mdc .= "  actor_id: 105\n";
        $mdc .= "  actor_name: \"cascade\"\n";
        $mdc .= "  delegation_chain: \"cascade:captain\"\n";
        $mdc .= "  lupopedia.version: \"4.0.76\"\n";
        $mdc .= "  lupopedia.schema: \"cascade_rule\"\n";
        $mdc .= "  file_path_from_root: \".cascade/rules/" . $rule['slug'] . ".md\"\n";
        $mdc .= "  last_modified_utc: \"" . date('Ymd') . "\"\n";
        $mdc .= "  system_version: \"4.0.76\"\n";
        $mdc .= "  source_path: \"lupo-rules/root/" . $rule['slug'] . ".md\"\n";
        $mdc .= "  artifact_type: \"rule\"\n";
        $mdc .= "  artifact_kind: \"cascade_doctrine\"\n";
        $mdc .= "  purpose: \"Cascade-specific rule derived from canonical root rule\"\n";
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
        $mdc .= "    last_reviewed_by: \"cascade\"\n";
        $mdc .= "    last_reviewed_date: \"" . date('Ymd') . "\"\n";
        $mdc .= "    version: \"1.0\"\n";
        $mdc .= "    status: \"active\"\n";
        $mdc .= "lupopedia.footer:\n";
        $mdc .= "  version: \"4.0.76\"\n";
        $mdc .= "  last_verified: \"" . date('Ymd') . "\"\n";
        $mdc .= "  last_verified_by: \"cascade\"\n";
        $mdc .= "  orchestrator: \"cascade\"\n";
        $mdc .= "  next_action:\n";
        $mdc .= "    - \"Keep in sync with canonical root rules\"\n";
        $mdc .= "---\n\n";
        $mdc .= $body . "\n";
        file_put_contents($cascadeRulesDir . DIRECTORY_SEPARATOR . $rule['slug'] . '.md', $mdc);
    }

    $readme = "---\n";
    $readme .= "lupopedia.init:\n";
    $readme .= "  file_identity: \"README.md\"\n";
    $readme .= "  artifact_type: \"cascade_guide\"\n";
    $readme .= "  artifact_kind: \"documentation\"\n";
    $readme .= "  namespace: \"cascade\"\n";
    $readme .= "  system_version: \"4.0.76\"\n";
    $readme .= "  orchestrator_actor: \"cascade\"\n";
    $readme .= "  delegation_chain: \"cascade:captain\"\n";
    $readme .= "\n";
    $readme .= "lupopedia.headers:\n";
    $readme .= "  actor_id: 105\n";
    $readme .= "  actor_name: \"cascade\"\n";
    $readme .= "  delegation_chain: \"cascade:captain\"\n";
    $readme .= "  lupopedia.version: \"4.0.76\"\n";
    $readme .= "  lupopedia.schema: \"cascade_guide\"\n";
    $readme .= "  file_path_from_root: \".cascade/README.md\"\n";
    $readme .= "  last_modified_utc: \"" . date('Ymd') . "\"\n";
    $readme .= "  system_version: \"4.0.76\"\n";
    $readme .= "  artifact_type: \"guide\"\n";
    $readme .= "  artifact_kind: \"documentation\"\n";
    $readme .= "  purpose: \"Guide for Cascade rule system and propagation\"\n";
    $readme .= "\n";
    $readme .= "lupopedia.footer:\n";
    $readme .= "  version: \"4.0.76\"\n";
    $readme .= "  last_verified: \"" . date('Ymd') . "\"\n";
    $readme .= "  last_verified_by: \"cascade\"\n";
    $readme .= "  orchestrator: \"cascade\"\n";
    $readme .= "  next_action:\n";
    $readme .= "    - \"Run propagation: php lupo-scripts/propagate_agent_rules.php --target=cascade\"\n";
    $readme .= "---\n\n";
    $readme .= "# Cascade Rules Guide\n\n";
    $readme .= "This directory contains Cascade-specific rule artifacts derived from canonical root rules.\n\n";
    $readme .= "## Files\n\n";
    $readme .= "- **lupopedia_rules.json** - Machine-readable rule index\n";
    $readme .= "- **rules/** - Individual rule files with LUPOPEDIA HEADERS\n\n";
    $readme .= "## Propagation\n\n";
    $readme .= "Run: `php lupo-scripts/propagate_agent_rules.php --target=cascade`\n\n";
    $readme .= "## Source\n\n";
    $readme .= "All rules are derived from canonical root rules in `lupo-rules/root/`.\n";
    $readme .= "See [lupo-rules/root/README.md](../../../lupo-rules/root/README.md) for canonical rule documentation.\n";
    file_put_contents($cascadeDir . DIRECTORY_SEPARATOR . 'README.md', $readme);
}

function write_lexa_outputs($lexaDir, $rules)
{
    ensure_dir($lexaDir);
    $lexaRulesDir = $lexaDir . DIRECTORY_SEPARATOR . 'rules';
    ensure_dir($lexaRulesDir);

    $lexaJson = array('rules' => array());
    foreach ($rules as $rule) {
        $lexaJson['rules'][] = array(
            'id' => $rule['id'],
            'text' => $rule['text'],
            'enforcement' => $rule['enforcement'],
            'scope' => $rule['scope'],
            'source_path' => $rule['source_path'],
            'slug' => $rule['slug'],
            'category' => $rule['category'],
            'status' => $rule['status']
        );
    }
    file_put_contents(
        $lexaDir . DIRECTORY_SEPARATOR . 'lupopedia_rules.json',
        json_encode($lexaJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    );

    foreach ($rules as $rule) {
        $body = str_replace('../../../', '../../', $rule['body']);
        $mdc = "---\n";
        $mdc .= "lupopedia.init:\n";
        $mdc .= "  file_identity: \"" . $rule['slug'] . ".md\"\n";
        $mdc .= "  artifact_type: \"lexa_rule\"\n";
        $mdc .= "  artifact_kind: \"doctrine\"\n";
        $mdc .= "  namespace: \"lexa\"\n";
        $mdc .= "  system_version: \"4.0.76\"\n";
        $mdc .= "  orchestrator_actor: \"lexa\"\n";
        $mdc .= "  delegation_chain: \"lexa:captain\"\n";
        $mdc .= "\n";
        $mdc .= "lupopedia.headers:\n";
        $mdc .= "  actor_id: 24\n";
        $mdc .= "  actor_name: \"lexa\"\n";
        $mdc .= "  delegation_chain: \"lexa:captain\"\n";
        $mdc .= "  lupopedia.version: \"4.0.76\"\n";
        $mdc .= "  lupopedia.schema: \"lexa_rule\"\n";
        $mdc .= "  file_path_from_root: \".lexa/rules/" . $rule['slug'] . ".md\"\n";
        $mdc .= "  last_modified_utc: \"" . date('Ymd') . "\"\n";
        $mdc .= "  system_version: \"4.0.76\"\n";
        $mdc .= "  source_path: \"lupo-rules/root/" . $rule['slug'] . ".md\"\n";
        $mdc .= "  artifact_type: \"rule\"\n";
        $mdc .= "  artifact_kind: \"lexa_doctrine\"\n";
        $mdc .= "  purpose: \"LEXA-specific rule derived from canonical root rule - Boundary Keeper enforcement\"\n";
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
        $mdc .= "    last_reviewed_by: \"lexa\"\n";
        $mdc .= "    last_reviewed_date: \"" . date('Ymd') . "\"\n";
        $mdc .= "    version: \"1.0\"\n";
        $mdc .= "    status: \"active\"\n";
        $mdc .= "lupopedia.footer:\n";
        $mdc .= "  version: \"4.0.76\"\n";
        $mdc .= "  last_verified: \"" . date('Ymd') . "\"\n";
        $mdc .= "  last_verified_by: \"lexa\"\n";
        $mdc .= "  orchestrator: \"lexa\"\n";
        $mdc .= "  next_action:\n";
        $mdc .= "    - \"Keep in sync with canonical root rules\"\n";
        $mdc .= "---\n\n";
        $mdc .= $body . "\n";
        file_put_contents($lexaRulesDir . DIRECTORY_SEPARATOR . $rule['slug'] . '.md', $mdc);
    }

    $readme = "---\n";
    $readme .= "lupopedia.init:\n";
    $readme .= "  file_identity: \"README.md\"\n";
    $readme .= "  artifact_type: \"lexa_guide\"\n";
    $readme .= "  artifact_kind: \"documentation\"\n";
    $readme .= "  namespace: \"lexa\"\n";
    $readme .= "  system_version: \"4.0.76\"\n";
    $readme .= "  orchestrator_actor: \"lexa\"\n";
    $readme .= "  delegation_chain: \"lexa:captain\"\n";
    $readme .= "\n";
    $readme .= "lupopedia.headers:\n";
    $readme .= "  actor_id: 24\n";
    $readme .= "  actor_name: \"lexa\"\n";
    $readme .= "  delegation_chain: \"lexa:captain\"\n";
    $readme .= "  lupopedia.version: \"4.0.76\"\n";
    $readme .= "  lupopedia.schema: \"lexa_guide\"\n";
    $readme .= "  file_path_from_root: \".lexa/README.md\"\n";
    $readme .= "  last_modified_utc: \"" . date('Ymd') . "\"\n";
    $readme .= "  system_version: \"4.0.76\"\n";
    $readme .= "  artifact_type: \"guide\"\n";
    $readme .= "  artifact_kind: \"documentation\"\n";
    $readme .= "  purpose: \"Guide for LEXA rule system and boundary enforcement\"\n";
    $readme .= "\n";
    $readme .= "lupopedia.footer:\n";
    $readme .= "  version: \"4.0.76\"\n";
    $readme .= "  last_verified: \"" . date('Ymd') . "\"\n";
    $readme .= "  last_verified_by: \"lexa\"\n";
    $readme .= "  orchestrator: \"lexa\"\n";
    $readme .= "  next_action:\n";
    $readme .= "    - \"Run propagation: php lupo-scripts/propagate_agent_rules.php --target=lexa\"\n";
    $readme .= "---\n\n";
    $readme .= "# LEXA Rules Guide\n\n";
    $readme .= "This directory contains LEXA-specific rule artifacts derived from canonical root rules.\n\n";
    $readme .= "LEXA (actor_id 24) is the Law Enforcement eXecution Agent - Boundary Keeper and Security Enforcer.\n\n";
    $readme .= "## Files\n\n";
    $readme .= "- **lupopedia_rules.json** - Machine-readable rule index\n";
    $readme .= "- **rules/** - Individual rule files with LUPOPEDIA HEADERS\n\n";
    $readme .= "## Propagation\n\n";
    $readme .= "Run: `php lupo-scripts/propagate_agent_rules.php --target=lexa`\n\n";
    $readme .= "## Source\n\n";
    $readme .= "All rules are derived from canonical root rules in `lupo-rules/root/`.\n";
    $readme .= "See [lupo-rules/root/README.md](../../../lupo-rules/root/README.md) for canonical rule documentation.\n";
    file_put_contents($lexaDir . DIRECTORY_SEPARATOR . 'README.md', $readme);
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
if ($target === 'all' || $target === 'cascade') {
    write_cascade_outputs($cascadeDir, $rules);
}
if ($target === 'all' || $target === 'lilith') {
    write_lilith_outputs($lilithDir, $rules);
}
if ($target === 'all' || $target === 'lexa') {
    write_lexa_outputs($lexaDir, $rules);
}

foreach ($warnings as $warning) {
    fwrite(STDERR, $warning . "\n");
}

$parsedCount = count($rules);
echo "Processed $processedCount root files; parsed $parsedCount rules; warnings: " . count($warnings) . "; target: $target\n";
