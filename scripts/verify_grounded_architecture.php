<?php
/**
 * wolfie.header.identity: verify-grounded-architecture
 * wolfie.header.placement: /scripts/verify_grounded_architecture.php
 * wolfie.header.version: 4.1.1
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Created reality check script to verify grounded architecture documentation matches actual implementation. Checks database tables, PHP classes, doctrine files, and temporal helpers."
 *   mood: "00FF00"
 */

/**
 * Grounded Architecture Verification Script
 * 
 * Verifies that documented architecture matches actual implementation:
 * - Database tables (lupo_actors, lupo_agents, etc.)
 * - PHP classes (TemporalHelper, DoctrineValidator, etc.)
 * - Doctrine files
 * - TOON schema files
 * 
 * Run: php scripts/verify_grounded_architecture.php
 */

require_once __DIR__ . '/../lupopedia-config.php';
require_once __DIR__ . '/../lupo-includes/bootstrap.php';

$report = [
    'timestamp' => date('YmdHis'),
    'checks' => [],
    'summary' => [
        'passed' => 0,
        'failed' => 0,
        'warnings' => 0
    ]
];

// Check 1: Database Tables
function checkDatabaseTables($db) {
    global $report;
    
    $required_tables = [
        'lupo_actors',
        'lupo_agents',
        'lupo_actor_actions',
        'lupo_actor_capabilities',
        'lupo_actor_channels',
        'lupo_auth_users'
    ];
    
    $check = [
        'name' => 'Database Tables',
        'required' => $required_tables,
        'found' => [],
        'missing' => [],
        'status' => 'unknown'
    ];
    
    try {
        $stmt = $db->query("SHOW TABLES LIKE 'lupo_%'");
        $existing_tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($required_tables as $table) {
            if (in_array($table, $existing_tables)) {
                $check['found'][] = $table;
            } else {
                $check['missing'][] = $table;
            }
        }
        
        if (empty($check['missing'])) {
            $check['status'] = 'passed';
            $report['summary']['passed']++;
        } else {
            $check['status'] = 'failed';
            $report['summary']['failed']++;
        }
    } catch (Exception $e) {
        $check['status'] = 'error';
        $check['error'] = $e->getMessage();
        $report['summary']['failed']++;
    }
    
    $report['checks']['database_tables'] = $check;
}

// Check 2: Foreign Key Constraints (should be zero)
function checkForeignKeys($db) {
    global $report;
    
    $check = [
        'name' => 'Foreign Key Constraints',
        'expected' => 0,
        'found' => 0,
        'status' => 'unknown'
    ];
    
    try {
        $db_name = DB_NAME;
        $sql = "SELECT 
            TABLE_NAME,
            COLUMN_NAME,
            CONSTRAINT_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE REFERENCED_TABLE_SCHEMA = :db_name 
        AND TABLE_SCHEMA = :db_name
        AND TABLE_NAME LIKE 'lupo_%'";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([':db_name' => $db_name]);
        $fks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $check['found'] = count($fks);
        $check['details'] = $fks;
        
        if ($check['found'] === 0) {
            $check['status'] = 'passed';
            $report['summary']['passed']++;
        } else {
            $check['status'] = 'warning';
            $check['message'] = "Found {$check['found']} foreign key constraints (doctrine requires zero)";
            $report['summary']['warnings']++;
        }
    } catch (Exception $e) {
        $check['status'] = 'error';
        $check['error'] = $e->getMessage();
        $report['summary']['failed']++;
    }
    
    $report['checks']['foreign_keys'] = $check;
}

// Check 3: Timestamp Format in Tables
function checkTimestampFormat($db) {
    global $report;
    
    $check = [
        'name' => 'Timestamp Format (BIGINT YYYYMMDDHHIISS)',
        'tables_checked' => [],
        'compliant' => [],
        'non_compliant' => [],
        'status' => 'unknown'
    ];
    
    try {
        $stmt = $db->query("SHOW TABLES LIKE 'lupo_%'");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($tables as $table) {
            $check['tables_checked'][] = $table;
            
            $desc_stmt = $db->query("DESCRIBE `{$table}`");
            $columns = $desc_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $has_timestamps = false;
            $timestamps_correct = true;
            
            foreach ($columns as $col) {
                if (preg_match('/.*(created|updated|deleted).*ymdhis.*/i', $col['Field'])) {
                    $has_timestamps = true;
                    // Check if it's BIGINT (doctrine requirement)
                    if (!preg_match('/bigint/i', $col['Type'])) {
                        $timestamps_correct = false;
                    }
                }
            }
            
            if ($has_timestamps) {
                if ($timestamps_correct) {
                    $check['compliant'][] = $table;
                } else {
                    $check['non_compliant'][] = $table;
                }
            }
        }
        
        if (empty($check['non_compliant'])) {
            $check['status'] = 'passed';
            $report['summary']['passed']++;
        } else {
            $check['status'] = 'warning';
            $report['summary']['warnings']++;
        }
    } catch (Exception $e) {
        $check['status'] = 'error';
        $check['error'] = $e->getMessage();
        $report['summary']['failed']++;
    }
    
    $report['checks']['timestamp_format'] = $check;
}

// Check 4: PHP Classes
function checkPHPClasses() {
    global $report;
    
    $required_classes = [
        'TemporalTruthMonitor' => 'lupo-includes/classes/TemporalTruthMonitor.php',
        'GenesisDoctrineValidator' => 'lupo-includes/classes/GenesisDoctrineValidator.php',
        'LABSValidator' => 'lupo-includes/classes/LABSValidator.php',
        'ReverseShakaUTC2026' => 'lupo-includes/classes/ReverseShakaUTC2026.php'
    ];
    
    $check = [
        'name' => 'PHP Classes',
        'required' => array_keys($required_classes),
        'found' => [],
        'missing' => [],
        'status' => 'unknown'
    ];
    
    foreach ($required_classes as $class_name => $file_path) {
        $full_path = __DIR__ . '/../' . $file_path;
        if (file_exists($full_path)) {
            $check['found'][] = $class_name;
        } else {
            $check['missing'][] = $class_name;
        }
    }
    
    if (empty($check['missing'])) {
        $check['status'] = 'passed';
        $report['summary']['passed']++;
    } else {
        $check['status'] = 'warning';
        $report['summary']['warnings']++;
    }
    
    $report['checks']['php_classes'] = $check;
}

// Check 5: TOON Schema Files
function checkTOONFiles() {
    global $report;
    
    $toon_dir = __DIR__ . '/../database/toon_data';
    $required_toons = [
        'lupo_actors.toon',
        'lupo_agents.toon',
        'lupo_actor_actions.toon'
    ];
    
    $check = [
        'name' => 'TOON Schema Files',
        'required' => $required_toons,
        'found' => [],
        'missing' => [],
        'total_toons' => 0,
        'status' => 'unknown'
    ];
    
    if (is_dir($toon_dir)) {
        $all_toons = glob($toon_dir . '/*.toon');
        $check['total_toons'] = count($all_toons);
        
        foreach ($required_toons as $toon) {
            if (file_exists($toon_dir . '/' . $toon)) {
                $check['found'][] = $toon;
            } else {
                $check['missing'][] = $toon;
            }
        }
    }
    
    if (empty($check['missing'])) {
        $check['status'] = 'passed';
        $report['summary']['passed']++;
    } else {
        $check['status'] = 'warning';
        $report['summary']['warnings']++;
    }
    
    $report['checks']['toon_files'] = $check;
}

// Check 6: Doctrine Files
function checkDoctrineFiles() {
    global $report;
    
    $doctrine_dir = __DIR__ . '/../docs/doctrine';
    $check = [
        'name' => 'Doctrine Files',
        'found' => [],
        'count' => 0,
        'status' => 'unknown'
    ];
    
    if (is_dir($doctrine_dir)) {
        $doctrine_files = glob($doctrine_dir . '/*.md');
        $check['count'] = count($doctrine_files);
        $check['found'] = array_map('basename', $doctrine_files);
    }
    
    if ($check['count'] > 0) {
        $check['status'] = 'passed';
        $report['summary']['passed']++;
    } else {
        $check['status'] = 'warning';
        $report['summary']['warnings']++;
    }
    
    $report['checks']['doctrine_files'] = $check;
}

// Run all checks
echo "🔍 Grounded Architecture Verification\n";
echo "=====================================\n\n";

try {
    $db = $GLOBALS['mydatabase'];
    
    checkDatabaseTables($db);
    checkForeignKeys($db);
    checkTimestampFormat($db);
    checkPHPClasses();
    checkTOONFiles();
    checkDoctrineFiles();
    
    // Print report
    foreach ($report['checks'] as $check_name => $check) {
        echo "✓ {$check['name']}: ";
        
        switch ($check['status']) {
            case 'passed':
                echo "✅ PASSED\n";
                break;
            case 'failed':
                echo "❌ FAILED\n";
                break;
            case 'warning':
                echo "⚠️  WARNING\n";
                break;
            default:
                echo "❓ UNKNOWN\n";
        }
        
        if (isset($check['missing']) && !empty($check['missing'])) {
            echo "   Missing: " . implode(', ', $check['missing']) . "\n";
        }
        if (isset($check['non_compliant']) && !empty($check['non_compliant'])) {
            echo "   Non-compliant: " . count($check['non_compliant']) . " tables\n";
        }
        if (isset($check['found']) && is_numeric($check['found'])) {
            echo "   Found: {$check['found']}\n";
        }
        if (isset($check['count'])) {
            echo "   Files: {$check['count']}\n";
        }
        echo "\n";
    }
    
    // Summary
    echo "=====================================\n";
    echo "SUMMARY\n";
    echo "=====================================\n";
    echo "✅ Passed: {$report['summary']['passed']}\n";
    echo "⚠️  Warnings: {$report['summary']['warnings']}\n";
    echo "❌ Failed: {$report['summary']['failed']}\n";
    echo "\n";
    
    // Output JSON report
    file_put_contents(__DIR__ . '/../storage/logs/architecture_verification_' . date('YmdHis') . '.json', 
        json_encode($report, JSON_PRETTY_PRINT));
    
    echo "📄 Full report saved to: storage/logs/architecture_verification_" . date('YmdHis') . ".json\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
