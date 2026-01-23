<?php
/**
 * CLI Tool for Dialog Channel Migration - Big Rock 2
 * 
 * Command-line interface for executing the dialog channel migration process.
 * Migrates all .md dialog files to database-backed dialog_channels system.
 * 
 * Usage:
 *   php migrate_dialog_channels.php [--dry-run] [--dialogs-path=path]
 * 
 * @package Lupopedia
 * @version 4.0.66
 * @author Captain Wolfie
 */

// Include required files
require_once 'lupopedia-config.php';
require_once 'lupo-includes/DialogChannelMigration/MigrationOrchestrator.php';

// Parse command line arguments
$options = getopt('', ['dry-run', 'dialogs-path:', 'help']);

if (isset($options['help'])) {
    showHelp();
    exit(0);
}

$dryRun = isset($options['dry-run']);
$dialogsPath = $options['dialogs-path'] ?? 'dialogs';

echo "=== DIALOG CHANNEL MIGRATION TOOL ===\n";
echo "Big Rock 2: Dialog Channel Migration\n";
echo "Version: 4.0.66\n\n";

try {
    // Initialize database connection
    echo "Connecting to database...\n";
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    echo "✅ Database connected successfully\n\n";
    
    // LIMITS enforcement (dry-run mode in 4.0.103)
    // Check schema ceiling before migration (non-blocking, logs warnings only)
    if (file_exists(__DIR__ . '/lupo-includes/functions/limits_logger.php')) {
        require_once __DIR__ . '/lupo-includes/functions/limits_logger.php';
        global $db;
        // Estimate new tables: dialog_channels, dialog_messages, dialog_message_bodies, dialog_threads (if not exist)
        $estimatedNewTables = 4;
        safe_check_table_count($estimatedNewTables);
    }
    
    // Initialize migration orchestrator
    $orchestrator = new MigrationOrchestrator($db);
    
    // Execute migration
    $report = $orchestrator->executeMigration($dialogsPath, $dryRun);
    
    // Display results
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "MIGRATION REPORT\n";
    echo str_repeat("=", 60) . "\n";
    
    echo "\n📊 STATISTICS:\n";
    foreach ($report['statistics'] as $key => $value) {
        $label = ucwords(str_replace('_', ' ', $key));
        echo sprintf("%-20s: %d\n", $label, $value);
    }
    
    if (!empty($report['warnings'])) {
        echo "\n⚠️ WARNINGS (" . count($report['warnings']) . "):\n";
        foreach ($report['warnings'] as $warning) {
            echo "- {$warning}\n";
        }
    }
    
    if (!empty($report['errors'])) {
        echo "\n❌ ERRORS (" . count($report['errors']) . "):\n";
        foreach ($report['errors'] as $error) {
            echo "- {$error}\n";
        }
    }
    
    echo "\n📋 SUMMARY:\n";
    echo $report['summary'] . "\n";
    
    echo "\n🎯 MIGRATION STATUS: " . ($report['success'] ? "✅ SUCCESS" : "❌ FAILED") . "\n";
    
    if ($dryRun) {
        echo "\n💡 This was a DRY RUN - no data was actually migrated.\n";
        echo "Run without --dry-run to execute the actual migration.\n";
    } else {
        if ($report['success']) {
            echo "\n🎉 Dialog Channel Migration completed successfully!\n";
            echo "All dialog files have been migrated to the database.\n";
        } else {
            echo "\n⚠️ Migration completed with errors. Please review the error log.\n";
        }
    }
    
    // Save report to file
    $reportFile = 'migration_report_' . date('Y-m-d_H-i-s') . '.json';
    file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT));
    echo "\n📄 Detailed report saved to: {$reportFile}\n";
    
} catch (Exception $e) {
    echo "\n❌ CRITICAL ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

/**
 * Show help information
 */
function showHelp() {
    echo "Dialog Channel Migration Tool - Big Rock 2\n\n";
    echo "USAGE:\n";
    echo "  php migrate_dialog_channels.php [OPTIONS]\n\n";
    echo "OPTIONS:\n";
    echo "  --dry-run              Run migration simulation without database changes\n";
    echo "  --dialogs-path=PATH    Path to dialogs directory (default: 'dialogs')\n";
    echo "  --help                 Show this help message\n\n";
    echo "EXAMPLES:\n";
    echo "  php migrate_dialog_channels.php --dry-run\n";
    echo "  php migrate_dialog_channels.php --dialogs-path=custom/dialogs\n";
    echo "  php migrate_dialog_channels.php\n\n";
    echo "DESCRIPTION:\n";
    echo "  This tool migrates all .md dialog files to the database-backed\n";
    echo "  dialog_channels system. It preserves all metadata from WOLFIE\n";
    echo "  headers and maintains message ordering and content.\n\n";
    echo "  The migration creates two tables:\n";
    echo "  - lupo_dialog_channels: Channel metadata from WOLFIE headers\n";
    echo "  - lupo_dialog_messages: Individual messages with 272-char limit\n\n";
}
?>