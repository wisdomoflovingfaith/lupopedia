<?php
require_once 'install_wizard_classes.php';

function log_msg($msg) {
    echo "[" . date('Y-m-d H:i:s') . "] $msg\n";
}

try {
    $db_vars = [
        'host' => 'localhost',
        'port' => '3306',
        'name' => 'lupopedia',
        'user' => 'root',
        'password' => 'ServBay.dev',
        'charset' => 'utf8mb4',
        'type' => 'mysql'
    ];
    
    $pdo = InstallWizardDb::connectPdo($db_vars);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $mysqlDir = 'database/lupopedia/mysql';
    $log = [];
    $table_prefix = 'lupo_';
    
    // 1. Install Schema
    log_msg("Installing new schema...");
    InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . '/install/install_new_lupopedia.sql', $log, $table_prefix);
    
    // 2. Seeds
    log_msg("Loading seeds...");
    $seeds = [
        '/seed/seed_registry_comprehensive_4.0.45.sql',
        '/seed/seed_registry_additional_csv_entities_4.0.45.sql',
        '/seed/seed_registry_open_4.0.45.sql',
        '/seed/seed_actors_agents_4.0.45.sql',
        '/seed/seed_rules_doctrine_4.0.68.sql',
        '/seed/seed_skills_4.0.68.sql',
        '/seed/seed_lupo_metadata_changelog_headers_4.0.68.sql',
        '/seed/seed_actor_1_cursor_rules_4.0.68.sql',
        '/seed/seed_fallback_rule_4.0.69.sql',
        '/seed/seed_traits_edge_types_action_auth_4.0.69.sql',
        '/seed/seed_default_sessions.sql',
        '/seed/seed_flare_content_4.0.57.sql',
        '/seed/seed_flare_apply_content_4.0.57.sql',
        '/seed/seed_docs_web_content_4.0.57.sql'
    ];
    
    foreach ($seeds as $seed) {
        log_msg("Running $seed...");
        InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . $seed, $log, $table_prefix);
    }
    
    // 3. Reserved Channels
    log_msg("Creating reserved channels...");
    InstallWizardChannels::createReservedSystemChannels($pdo, $log);
    
    // 4. Import from Crafty
    log_msg("Importing from Crafty Syntax 3.7.5...");
    // Set auto_increment for human actors
    $pdo->exec("ALTER TABLE {$table_prefix}actors AUTO_INCREMENT = 10000");
    InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . '/import/import_from_old_crafty_syntax.sql', $log, $table_prefix);
    
    // 5. Migrations
    log_msg("Running migrations...");
    $migrations = [
        '/migrations/anubis_queue_tables_4.0.53.sql',
        '/migrations/20260301_anubis_database_primacy_updates.sql'
    ];
    foreach ($migrations as $mig) {
        log_msg("Running $mig...");
        InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . $mig, $log, $table_prefix);
    }
    
    log_msg("Upgrade process complete.");
    
    // Output log summary
    $errors = 0;
    foreach ($log as $entry) {
        if ($entry[0] === 'error') {
            echo "ERROR: " . $entry[1] . "\n";
            $errors++;
        }
    }
    echo "Total errors: $errors\n";
    
} catch (Exception $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
}
