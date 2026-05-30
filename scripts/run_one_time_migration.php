<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "scripts/run_one_time_migration.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175911"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
lupopedia.headers:
  when_updated: "20260324175617"
  file_path_from_root: "scripts/run_one_time_migration.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175617"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
 * Run a one-time migration SQL file with lupo_schema_migrations check/record.
 * Prevents reapplication when the migration version is already recorded.
 *
 * Usage: php scripts/run_one_time_migration.php <path_to.sql> <version> [name]
 * Example: php scripts/run_one_time_migration.php database/migrations/20260309_root_doctrine_content_channel_actor_apps.sql 20260309 root_doctrine_content_channel_actor_apps
 *
 * @package Lupopedia
 * @version 4.0.67
 */

$base = dirname(__DIR__);
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $base);
}
$config = $base . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
if (!is_file($config)) {
    fwrite(STDERR, "Config not found: lupopedia-config.php\n");
    exit(1);
}
require_once $config;
require_once $base . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'classes/pdo_db.php';
require_once $base . DIRECTORY_SEPARATOR . 'install_wizard_classes.php';
require_once $base . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'schema_migrations.php';

$path = isset($argv[1]) ? $argv[1] : '';
$version = isset($argv[2]) ? trim($argv[2]) : '';
$name = isset($argv[3]) ? trim($argv[3]) : $version;

if ($path === '' || $version === '') {
    fwrite(STDERR, "Usage: php run_one_time_migration.php <path_to.sql> <version> [name]\n");
    exit(1);
}

$fullPath = $path;
if (strpos($fullPath, DIRECTORY_SEPARATOR) !== 0 && strpos($fullPath, ':') !== 1) {
    $fullPath = $base . DIRECTORY_SEPARATOR . $path;
}
if (!is_file($fullPath) || !is_readable($fullPath)) {
    fwrite(STDERR, "Migration file not found or not readable: " . $fullPath . "\n");
    exit(1);
}

$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$dsn = (defined('DB_TYPE') ? DB_TYPE : 'mysql') . ":host=" . (defined('DB_HOST') ? DB_HOST : 'localhost') . ";dbname=" . (defined('DB_NAME') ? DB_NAME : '') . ";charset=utf8mb4";
$migrate_opts = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
if (extension_loaded('pdo_mysql')) {
    $migrate_opts[PDO::MYSQL_ATTR_USE_BUFFERED_QUERY] = true;
}
$pdo = new PDO($dsn, defined('DB_USER') ? DB_USER : '', defined('DB_PASSWORD') ? DB_PASSWORD : '', $migrate_opts);
$db = new PDO_DB($pdo);

if (function_exists('lupo_schema_migration_applied') && lupo_schema_migration_applied($db, $version, $table_prefix)) {
    echo "Migration already applied (version={$version}). Skipping.\n";
    exit(0);
}

$log = array();
$ok = InstallWizardSqlRunner::runSqlFile($pdo, $fullPath, $log, $table_prefix);
if (!$ok) {
    fwrite(STDERR, "Migration SQL execution reported failure. Check log.\n");
    foreach ($log as $entry) {
        if (is_array($entry) && isset($entry[0]) && $entry[0] === 'error') {
            fwrite(STDERR, "  " . (isset($entry[1]) ? $entry[1] : '') . "\n");
        }
    }
    exit(1);
}

if (function_exists('lupo_schema_migration_record')) {
    lupo_schema_migration_record($db, $version, $name, $table_prefix);
    echo "Migration recorded (version={$version}, name={$name}).\n";
}
echo "Done.\n";
exit(0);
