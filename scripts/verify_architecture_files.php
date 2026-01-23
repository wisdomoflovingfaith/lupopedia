<?php
/**
 * wolfie.header.identity: verify-architecture-files
 * wolfie.header.placement: /scripts/verify_architecture_files.php
 * wolfie.header.version: 4.1.1
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Created file-only verification script to check PHP classes, TOON files, and doctrine files without requiring database connection."
 *   mood: "00FF00"
 */

/**
 * File-Based Architecture Verification
 * 
 * Verifies file structure matches documented architecture:
 * - PHP classes exist
 * - TOON schema files exist
 * - Doctrine files exist
 * - Agent directories exist
 */

$base_dir = __DIR__ . '/..';
$report = [
    'timestamp' => date('YmdHis'),
    'checks' => [],
    'summary' => [
        'passed' => 0,
        'missing' => 0,
        'warnings' => 0
    ]
];

echo "🔍 Grounded Architecture File Verification\n";
echo "==========================================\n\n";

// Check 1: PHP Classes
echo "✓ PHP Classes:\n";
$required_classes = [
    'TemporalTruthMonitor' => 'lupo-includes/classes/TemporalTruthMonitor.php',
    'GenesisDoctrineValidator' => 'lupo-includes/classes/GenesisDoctrineValidator.php',
    'LABSValidator' => 'lupo-includes/classes/LABSValidator.php',
    'ReverseShakaUTC2026' => 'lupo-includes/classes/ReverseShakaUTC2026.php',
    'ContinuityValidator' => 'lupo-includes/classes/ContinuityValidator.php',
    'ColorProtocol' => 'lupo-includes/classes/ColorProtocol.php'
];

$found_classes = [];
$missing_classes = [];

foreach ($required_classes as $class_name => $file_path) {
    $full_path = $base_dir . '/' . $file_path;
    if (file_exists($full_path)) {
        echo "  ✅ {$class_name}\n";
        $found_classes[] = $class_name;
    } else {
        echo "  ❌ {$class_name} (missing: {$file_path})\n";
        $missing_classes[] = $class_name;
    }
}

$report['checks']['php_classes'] = [
    'found' => count($found_classes),
    'missing' => count($missing_classes),
    'status' => empty($missing_classes) ? 'passed' : 'warning'
];

if (empty($missing_classes)) {
    $report['summary']['passed']++;
} else {
    $report['summary']['warnings']++;
}

echo "\n";

// Check 2: TOON Schema Files
echo "✓ TOON Schema Files:\n";
$toon_dir = $base_dir . '/database/toon_data';
$required_toons = [
    'lupo_actors.toon',
    'lupo_agents.toon',
    'lupo_actor_actions.toon',
    'lupo_labs_declarations.toon'
];

$found_toons = [];
$missing_toons = [];

if (is_dir($toon_dir)) {
    $all_toons = glob($toon_dir . '/*.toon');
    echo "  Total TOON files: " . count($all_toons) . "\n";
    
    foreach ($required_toons as $toon) {
        $full_path = $toon_dir . '/' . $toon;
        if (file_exists($full_path)) {
            echo "  ✅ {$toon}\n";
            $found_toons[] = $toon;
        } else {
            echo "  ❌ {$toon} (missing)\n";
            $missing_toons[] = $toon;
        }
    }
} else {
    echo "  ❌ TOON directory not found: {$toon_dir}\n";
    $report['summary']['missing']++;
}

$report['checks']['toon_files'] = [
    'found' => count($found_toons),
    'missing' => count($missing_toons),
    'total_toons' => isset($all_toons) ? count($all_toons) : 0,
    'status' => empty($missing_toons) ? 'passed' : 'warning'
];

if (empty($missing_toons)) {
    $report['summary']['passed']++;
} else {
    $report['summary']['warnings']++;
}

echo "\n";

// Check 3: Doctrine Files
echo "✓ Doctrine Files:\n";
$doctrine_dir = $base_dir . '/docs/doctrine';

if (is_dir($doctrine_dir)) {
    $doctrine_files = glob($doctrine_dir . '/*.md');
    echo "  Total doctrine files: " . count($doctrine_files) . "\n";
    
    $key_doctrines = [
        'VERSION_DOCTRINE.md',
        'WOLFIE_HEADER_DOCTRINE.md',
        'DATABASE_DOCTRINE.md'
    ];
    
    foreach ($key_doctrines as $doctrine) {
        $full_path = $doctrine_dir . '/' . $doctrine;
        if (file_exists($full_path)) {
            echo "  ✅ {$doctrine}\n";
        } else {
            echo "  ⚠️  {$doctrine} (not found)\n";
        }
    }
    
    $report['checks']['doctrine_files'] = [
        'count' => count($doctrine_files),
        'status' => 'passed'
    ];
    $report['summary']['passed']++;
} else {
    echo "  ❌ Doctrine directory not found\n";
    $report['summary']['missing']++;
}

echo "\n";

// Check 4: Agent Directories
echo "✓ Agent Directories:\n";
$agents_dir = $base_dir . '/lupo-agents';

if (is_dir($agents_dir)) {
    $agent_dirs = glob($agents_dir . '/*', GLOB_ONLYDIR);
    echo "  Total agent directories: " . count($agent_dirs) . "\n";
    
    // Check for core agents (0, 1, 2)
    $core_agents = ['0', '1', '2'];
    foreach ($core_agents as $agent_id) {
        $agent_path = $agents_dir . '/' . $agent_id;
        if (is_dir($agent_path)) {
            echo "  ✅ Agent {$agent_id}\n";
        } else {
            echo "  ⚠️  Agent {$agent_id} (missing)\n";
        }
    }
    
    $report['checks']['agent_directories'] = [
        'count' => count($agent_dirs),
        'status' => 'passed'
    ];
    $report['summary']['passed']++;
} else {
    echo "  ❌ Agents directory not found\n";
    $report['summary']['missing']++;
}

echo "\n";

// Check 5: Global Atoms
echo "✓ Global Configuration:\n";
$global_atoms = $base_dir . '/config/global_atoms.yaml';
if (file_exists($global_atoms)) {
    echo "  ✅ global_atoms.yaml\n";
    $atoms_content = file_get_contents($global_atoms);
    if (preg_match('/GLOBAL_CURRENT_LUPOPEDIA_VERSION:\s*"([^"]+)"/', $atoms_content, $matches)) {
        echo "     Version: {$matches[1]}\n";
    }
    $report['summary']['passed']++;
} else {
    echo "  ❌ global_atoms.yaml (missing)\n";
    $report['summary']['missing']++;
}

echo "\n";

// Summary
echo "==========================================\n";
echo "SUMMARY\n";
echo "==========================================\n";
echo "✅ Passed checks: {$report['summary']['passed']}\n";
echo "⚠️  Warnings: {$report['summary']['warnings']}\n";
echo "❌ Missing: {$report['summary']['missing']}\n";
echo "\n";
echo "📄 Architecture verification complete.\n";
echo "   Timestamp: {$report['timestamp']}\n";
