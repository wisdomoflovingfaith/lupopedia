<?php
/**
 * Thread 1043 Iteration 1 — Step 3: Run Lupopedia install/upgrade (CLI)
 * Replicates install.php upgrade flow for Crafty Syntax 3.7.5 → Lupopedia 4.0.85.
 * Actor: HEPHAESTUS. Delete after use.
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
@set_time_limit(600);

// ---- Setup paths (mirrors install.php exactly) ----
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', __DIR__);
}
if (!defined('LUPO_DATABASE_DIR')) {
    define('LUPO_DATABASE_DIR', LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-database');
}
if (!defined('LUPO_MYSQL_DIR')) {
    define('LUPO_MYSQL_DIR', LUPO_DATABASE_DIR . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'mysql');
}
if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

$mysqlDir = LUPO_MYSQL_DIR;
$table_prefix = 'lupo_';

if (!is_dir($mysqlDir)) {
    echo "FATAL: LUPO_MYSQL_DIR not found: $mysqlDir\n";
    exit(1);
}

// ---- Load wizard classes ----
$wiz_classes = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'install_wizard_classes.php';
$wiz_importer = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-install' . DIRECTORY_SEPARATOR . 'InstallWizardMdImporter.php';
$pdo_class = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'class-pdo_db.php';

foreach (array($wiz_classes, $wiz_importer, $pdo_class) as $f) {
    if (!is_file($f)) {
        echo "FATAL: Required file not found: $f\n";
        exit(1);
    }
    require_once $f;
    echo "LOADED: " . basename($f) . "\n";
}

// Load AI activation helper (needed by Activations Block)
$ai_activation = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'ai_activation.php';
if (is_file($ai_activation)) {
    require_once $ai_activation;
    echo "LOADED: ai_activation.php\n";
} else {
    echo "WARN: ai_activation.php not found — activation block will be skipped\n";
}

// ---- DB connection ----
$db_vars = array(
    'host' => 'localhost',
    'port' => '3306',
    'name' => 'lupopedia',
    'user' => 'root',
    'password' => 'ServBay.dev',
    'charset' => 'utf8mb4',
    'collate' => 'utf8mb4_unicode_ci',
);

echo "\nCONNECTING to database...\n";
try {
    $pdo = InstallWizardDb::connectPdo($db_vars);
    echo "CONNECT: OK\n";
} catch (PDOException $e) {
    echo "CONNECT_FAIL: " . $e->getMessage() . "\n";
    exit(1);
}

// ---- Detect livehelp_ tables (upgrade detection) ----
$livehelp_tables = InstallWizardDb::detectLivehelpTables($pdo);
$install_type = count($livehelp_tables) > 0 ? 'upgrade' : 'new';
echo "\nDETECT_MODE: $install_type\n";
echo "LIVEHELP_TABLES_FOUND: " . count($livehelp_tables) . "\n";
foreach ($livehelp_tables as $t) { echo "  LIVEHELP: $t\n"; }

// ---- Pre-install table count ----
$pre_lupo = $pdo->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='lupopedia' AND table_name LIKE 'lupo_%'")->fetchColumn();
echo "\nPRE_INSTALL_LUPO_COUNT: $pre_lupo\n";

// ====================================================================
// UPGRADE PATH
// ====================================================================
$log = array();

echo "\n";
echo "=====================================\n";
echo "PHASE A: BOOTSTRAP (install+seed+reserved)\n";
echo "=====================================\n";

// A1: install_new_lupopedia.sql
$install_sql = $mysqlDir . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'install_new_lupopedia.sql';
echo "RUNNING: install_new_lupopedia.sql\n";
$r = InstallWizardSqlRunner::runSqlFile($pdo, $install_sql, $log, $table_prefix);
echo "RESULT: " . ($r ? "OK" : "FAIL") . "\n";

// A2: Seed files (exact sequence from install.php)
$seeds_core = array(
    'seed_registry_comprehensive_4.0.45.sql',
    'seed_registry_additional_csv_entities_4.0.45.sql',
    'seed_registry_open_4.0.45.sql',
    'seed_actors_agents_4.0.45.sql',
);
foreach ($seeds_core as $sf) {
    $path = $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . DIRECTORY_SEPARATOR . $sf;
    if (is_file($path)) {
        echo "RUNNING SEED: $sf\n";
        $r = InstallWizardSqlRunner::runSqlFile($pdo, $path, $log, $table_prefix);
        echo "RESULT: " . ($r ? "OK" : "FAIL") . "\n";
    } else {
        echo "MISSING_SEED: $sf\n";
        $log[] = InstallWizardLogger::logEntry('error', "Seed file missing: $sf");
    }
}

// A3: 4.0.68 seeds
$seed_4_0_68 = array('seed_rules_doctrine_4.0.68.sql', 'seed_skills_4.0.68.sql', 'seed_lupo_metadata_changelog_headers_4.0.68.sql', 'seed_actor_1_cursor_rules_4.0.68.sql');
foreach ($seed_4_0_68 as $sf) {
    $path = $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . DIRECTORY_SEPARATOR . $sf;
    if (is_file($path)) {
        echo "RUNNING SEED 4.0.68: $sf\n";
        $r = InstallWizardSqlRunner::runSqlFile($pdo, $path, $log, $table_prefix);
        echo "RESULT: " . ($r ? "OK" : "FAIL") . "\n";
    }
}

// A4: 4.0.69 seeds
$seed_4_0_69 = array('seed_fallback_rule_4.0.69.sql', 'seed_traits_edge_types_action_auth_4.0.69.sql');
foreach ($seed_4_0_69 as $sf) {
    $path = $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . DIRECTORY_SEPARATOR . $sf;
    if (is_file($path)) {
        echo "RUNNING SEED 4.0.69: $sf\n";
        $r = InstallWizardSqlRunner::runSqlFile($pdo, $path, $log, $table_prefix);
        echo "RESULT: " . ($r ? "OK" : "FAIL") . "\n";
    }
}

// A5: 4.0.74 seeds
$seed_4_0_74 = array('seed_projects.sql');
foreach ($seed_4_0_74 as $sf) {
    $path = $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . DIRECTORY_SEPARATOR . $sf;
    if (is_file($path)) {
        echo "RUNNING SEED 4.0.74: $sf\n";
        $r = InstallWizardSqlRunner::runSqlFile($pdo, $path, $log, $table_prefix);
        echo "RESULT: " . ($r ? "OK" : "FAIL") . "\n";
    }
}

// A6: Reserved system channels (constitutional requirement)
echo "\nCREATING RESERVED CHANNELS (0,1,42,51)...\n";
InstallWizardChannels::createReservedSystemChannels($pdo, $log);
echo "RESERVED_CHANNELS: done\n";

echo "\n";
echo "=====================================\n";
echo "PHASE B: IDENTITY NORMALIZATION\n";
echo "=====================================\n";

$users = InstallWizardNormalize::loadCraftyUsers($pdo);
$identities = InstallWizardNormalize::computeProposedIdentities($users);
$duplicates = InstallWizardNormalize::findDuplicateEmailGroups($identities);
$warnings = InstallWizardNormalize::collectNormalizeWarnings($identities);

echo "CRAFTY_USERS_TOTAL: " . count($users) . "\n";
echo "OPERATORS_TO_NORMALIZE: " . count($identities) . "\n";
echo "DUPLICATE_EMAIL_GROUPS: " . count($duplicates) . "\n";
foreach ($warnings as $w) { echo "  WARN: $w\n"; }

if (!empty($identities)) {
    // Build resolved map — use proposed_email for all (auto-apply)
    $resolved = array();
    foreach ($identities as $row) {
        $id = $row['user_id'];
        $resolved[$id] = $row['proposed_email'];
    }

    // Check for duplicates — if any, generate unique slugs
    $validation = InstallWizardNormalize::validateResolvedEmails($identities, $resolved);
    if (!empty($validation['errors'])) {
        echo "NORMALIZE_VALIDATION_ERRORS: " . count($validation['errors']) . "\n";
        foreach ($validation['errors'] as $err) { echo "  ERR: $err\n"; }
        // Auto-resolve duplicates by appending user_id
        $seen = array();
        foreach ($identities as $row) {
            $id = $row['user_id'];
            $email = strtolower($resolved[$id]);
            if (isset($seen[$email])) {
                // Make unique by appending user_id
                $resolved[$id] = $row['user_id'] . '-at-lupopedia-com';
            }
            $seen[$email] = $id;
        }
        echo "NORMALIZE: auto-resolved duplicates by appending user_id\n";
    }

    echo "APPLYING NORMALIZATION...\n";
    foreach ($identities as $row) {
        echo "  NORMALIZE_USER: id={$row['user_id']} old_email={$row['email']} proposed={$resolved[$row['user_id']]}\n";
    }
    InstallWizardNormalize::applyNormalizationToLivehelp($pdo, $identities, $resolved);
    echo "NORMALIZE_APPLIED: " . count($identities) . " users\n";
    $log[] = InstallWizardLogger::logEntry('ok', 'Identity normalization applied: ' . count($identities) . ' operators.');
} else {
    echo "NORMALIZE_SKIP: no operators to normalize\n";
    $log[] = InstallWizardLogger::logEntry('skip', 'No Crafty operators to normalize.');
}

echo "\n";
echo "=====================================\n";
echo "PHASE C: RUN STEP\n";
echo "=====================================\n";

// C1: Ensure schema (should exist from phase A but defend)
$dept_table = $table_prefix . 'departments';
$st = $pdo->query("SHOW TABLES LIKE " . $pdo->quote($dept_table));
$schema_ok = $st && $st->fetch() !== false;
echo "SCHEMA_CHECK (departments table): " . ($schema_ok ? "EXISTS" : "MISSING") . "\n";

if (!$schema_ok) {
    echo "WARN: schema missing — re-running install+seeds\n";
    $r = InstallWizardSqlRunner::runSqlFile($pdo, $install_sql, $log, $table_prefix);
    echo "REINSTALL_RESULT: " . ($r ? "OK" : "FAIL") . "\n";
}

// C2: Ensure departments and reserved channels
echo "ENSURING_SYSTEM_DEPARTMENT...\n";
InstallWizardDepartments::ensureSystemDepartment($pdo, $log);
InstallWizardChannels::ensureReservedChannels($pdo, $log);
echo "RESERVED_CHANNELS_VERIFIED\n";

// C3: Import from Crafty Syntax
$importSql = $mysqlDir . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'import_from_old_crafty_syntax.sql';
if (count($livehelp_tables) > 0) {
    // Set auto_increment for actors to start human actors at 10000
    $actors_table = '`' . $table_prefix . 'actors`';
    try {
        $stmt = $pdo->query("SELECT COALESCE(MAX(actor_id), 0) AS mx FROM $actors_table LIMIT 1");
        $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
        $max = $row && isset($row['mx']) ? (int) $row['mx'] : 0;
        $nextId = $max >= 10000 ? $max + 1 : 10000;
        $pdo->exec("ALTER TABLE $actors_table AUTO_INCREMENT = " . (int) $nextId);
        echo "AUTO_INCREMENT: set lupo_actors to $nextId\n";
        $log[] = InstallWizardLogger::logEntry('ok', "Set lupo_actors AUTO_INCREMENT to $nextId (human actors from 10000).");
    } catch (PDOException $e) {
        echo "WARN: AUTO_INCREMENT set failed: " . $e->getMessage() . "\n";
        $log[] = InstallWizardLogger::logEntry('error', 'Could not set actor_id minimum: ' . $e->getMessage());
    }

    echo "RUNNING: import_from_old_crafty_syntax.sql\n";
    $importOk = InstallWizardSqlRunner::runSqlFile($pdo, $importSql, $log, $table_prefix);
    echo "IMPORT_RESULT: " . ($importOk ? "OK" : "FAIL") . "\n";
    $log[] = InstallWizardLogger::logEntry($importOk ? 'ok' : 'error', 'import_from_old_crafty_syntax.sql: ' . ($importOk ? 'complete' : 'failures found'));
} else {
    echo "IMPORT_SKIP: no livehelp tables\n";
    $log[] = InstallWizardLogger::logEntry('skip', 'No livehelp_ tables; import skipped.');
}

// C4: Ensure system department (again, import TRUNCATEs departments)
echo "ENSURING_SYSTEM_DEPARTMENT_AFTER_IMPORT...\n";
InstallWizardDepartments::ensureSystemDepartment($pdo, $log);

// C5: Operator channels
echo "CREATING_OPERATOR_CHANNELS...\n";
$operatorChannelMap = InstallWizardChannels::createOperatorChannels($pdo, $log);
echo "OPERATOR_CHANNELS_CREATED: " . count($operatorChannelMap) . "\n";
InstallWizardChannels::ensureOperatorChannels($pdo, $log);

// C6: Drop livehelp tables (upgrade option — we DO drop them per directive)
echo "DROPPING_LIVEHELP_TABLES...\n";
$dropSql = $mysqlDir . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'drop_old_crafty_syntax_tables.sql';
if (is_file($dropSql)) {
    $r = InstallWizardSqlRunner::runSqlFile($pdo, $dropSql, $log);
    echo "DROP_LIVEHELP_SQL_RESULT: " . ($r ? "OK" : "FAIL") . "\n";
    $log[] = InstallWizardLogger::logEntry('ok', 'Legacy Crafty Syntax tables dropped via drop_old_crafty_syntax_tables.sql.');
} else {
    echo "WARN: drop_old_crafty_syntax_tables.sql not found — dropping manually\n";
    $remaining_lh = InstallWizardDb::detectLivehelpTables($pdo);
    if (!empty($remaining_lh)) {
        InstallWizardSqlRunner::dropLivehelpTables($pdo, $remaining_lh, $log);
    }
}
$remaining_lh = InstallWizardDb::detectLivehelpTables($pdo);
echo "LIVEHELP_REMAINING_AFTER_DROP: " . count($remaining_lh) . "\n";
if (!empty($remaining_lh)) {
    echo "WARN: still remaining — dropping\n";
    InstallWizardSqlRunner::dropLivehelpTables($pdo, $remaining_lh, $log);
}

echo "\n";
echo "=====================================\n";
echo "PHASE D: POST-IMPORT SEEDS\n";
echo "=====================================\n";

// MD importer
if (class_exists('InstallWizardMdImporter')) {
    echo "RUNNING: InstallWizardMdImporter::importAllMdFiles\n";
    InstallWizardMdImporter::importAllMdFiles($pdo, $log, $table_prefix);
    echo "MD_IMPORT: done\n";
} else {
    echo "WARN: InstallWizardMdImporter not available\n";
}

// Registry open (unregistry gaps)
if (class_exists('InstallWizardUnregistry')) {
    echo "RUNNING: seedUnregistryFromGaps\n";
    InstallWizardUnregistry::seedUnregistryFromGaps($pdo, $log, InstallWizardUnregistry::DEFAULT_MAX_CAP);
    echo "UNREGISTRY: done\n";
}

// Banned identities
if (class_exists('InstallWizardBannedIdentities')) {
    echo "RUNNING: ensureStonedWolfieBannedIdentities\n";
    InstallWizardBannedIdentities::ensureStonedWolfieBannedIdentities($pdo, $log, $table_prefix);
    echo "BANNED_IDENTITIES: done\n";
}

// ANUBIS seeds
$anubis_seeds = array('anubis_queue_tables_4.0.53.sql', '20260301_anubis_database_primacy_updates.sql');
foreach ($anubis_seeds as $sf) {
    $path = $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . DIRECTORY_SEPARATOR . $sf;
    if (is_file($path)) {
        echo "RUNNING ANUBIS SEED: $sf\n";
        $r = InstallWizardSqlRunner::runSqlFile($pdo, $path, $log, $table_prefix);
        echo "RESULT: " . ($r ? "OK" : "FAIL") . "\n";
    }
}

// Default sessions
$sess_seed = $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . DIRECTORY_SEPARATOR . 'seed_default_sessions.sql';
if (is_file($sess_seed)) {
    echo "RUNNING SEED: seed_default_sessions.sql\n";
    $r = InstallWizardSqlRunner::runSqlFile($pdo, $sess_seed, $log, $table_prefix);
    echo "RESULT: " . ($r ? "OK" : "FAIL") . "\n";
}

// FLARE content seeds
foreach (array('seed_flare_content_4.0.57.sql', 'seed_flare_apply_content_4.0.57.sql', 'seed_docs_web_content_4.0.57.sql') as $sf) {
    $path = $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . DIRECTORY_SEPARATOR . $sf;
    if (is_file($path)) {
        echo "RUNNING SEED: $sf\n";
        $r = InstallWizardSqlRunner::runSqlFile($pdo, $path, $log, $table_prefix);
        echo "RESULT: " . ($r ? "OK" : "FAIL") . "\n";
    }
}

// Re-run 4.0.68-4.0.74 seeds after content seeds (idempotent per install.php)
foreach (array_merge($seed_4_0_68, $seed_4_0_69, $seed_4_0_74) as $sf) {
    $path = $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . DIRECTORY_SEPARATOR . $sf;
    if (is_file($path)) {
        $r = InstallWizardSqlRunner::runSqlFile($pdo, $path, $log, $table_prefix);
        // Not echoing each re-run (idempotent)
    }
}

// Activations Block (core actors 0, 1, 2, 19)
if (function_exists('ensureActorActive')) {
    echo "\nRUNNING: Activations Block (actors 0,1,2,19)\n";
    $core_actors = array(0, 1, 2, 19);
    foreach ($core_actors as $actor_id) {
        $actor_db = new PDO_DB($pdo);
        $result = ensureActorActive($actor_id, $actor_db, 'initial_install_activation');
        $log[] = InstallWizardLogger::logEntry($result ? 'ok' : 'error', "Actor $actor_id activation: " . ($result ? "OK" : "FAIL"));
        echo "ACTIVATE_ACTOR_$actor_id: " . ($result ? "OK" : "FAIL") . "\n";
    }
}

echo "\n";
echo "=====================================\n";
echo "FULL LOG DUMP\n";
echo "=====================================\n";
foreach ($log as $entry) {
    if (is_array($entry)) {
        $level = isset($entry[0]) ? $entry[0] : 'info';
        $msg   = isset($entry[1]) ? $entry[1] : '';
        $ctx   = isset($entry[2]) ? ' [' . $entry[2] . ']' : '';
        echo "[" . strtoupper($level) . "]$ctx $msg\n";
    } else {
        echo $entry . "\n";
    }
}

echo "\n";
echo "=====================================\n";
echo "POST-INSTALL TABLE VERIFICATION\n";
echo "=====================================\n";
$post_lupo = $pdo->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='lupopedia' AND table_name LIKE 'lupo_%'")->fetchColumn();
echo "POST_INSTALL_LUPO_COUNT: $post_lupo\n";

$post_all = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "POST_INSTALL_ALL_TABLE_COUNT: " . count($post_all) . "\n";
foreach (array_values($post_all) as $t) { echo "  TABLE: $t\n"; }

echo "\nINSTALL_COMPLETE: " . ($post_lupo > 0 ? "OK — $post_lupo lupo_ tables exist" : "FAIL — 0 lupo_ tables") . "\n";
