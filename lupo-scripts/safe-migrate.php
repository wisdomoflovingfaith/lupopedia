#!/usr/bin/env php
<?php
/**
 * Safe migration runner (DB009).
 * All database migrations MUST be run through this script – never via direct mysql CLI.
 *
 * Usage: php lupo-scripts/safe-migrate.php <path/to/migration.sql>
 * Run from repository root.
 *
 * @see lupo-docs/doctrine/SAFE_MIGRATION_DOCTRINE.md
 * @see lupo-rules/root/safe-database-operations-doctrine.md
 */

$repo_root = dirname(__DIR__);
if (!defined('ABSPATH')) {
    define('ABSPATH', $repo_root . DIRECTORY_SEPARATOR);
}
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root . DIRECTORY_SEPARATOR);
}

$config = $repo_root . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
if (!file_exists($config)) {
    fwrite(STDERR, "Error: Config not found. Expect lupopedia-config.php in repo root.\n");
    exit(1);
}
require_once $config;
require_once $repo_root . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'bootstrap.php';

$migration_file = isset($argv[1]) ? trim($argv[1]) : '';
if ($migration_file === '') {
    fwrite(STDERR, "Usage: php lupo-scripts/safe-migrate.php <path/to/migration.sql>\n");
    exit(1);
}

$path = $migration_file;
if ($path[0] !== '/' && $path[0] !== '\\' && (strlen($path) < 2 || $path[1] !== ':')) {
    $path = $repo_root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
}
$real = realpath($path);
if ($real === false || !file_exists($real)) {
    fwrite(STDERR, "Error: Migration file not found or not readable: " . $migration_file . "\n");
    exit(1);
}
$repo_real = realpath($repo_root);
if ($repo_real === false || strpos($real, $repo_real) !== 0) {
    fwrite(STDERR, "Error: Migration file must be inside the repository.\n");
    exit(1);
}

$environment = 'development';
if (isset($GLOBALS['lupo_db_config']['environment'])) {
    $environment = $GLOBALS['lupo_db_config']['environment'];
}

if (strtolower($environment) === 'production') {
    echo "PRODUCTION environment detected.\n";
    echo "Type 'yes' to continue, anything else to abort: ";
    $line = fgets(STDIN);
    if (trim($line) !== 'yes') {
        fwrite(STDERR, "Aborted.\n");
        exit(1);
    }
}

$sql = file_get_contents($real);
if ($sql === false) {
    fwrite(STDERR, "Error: Could not read migration file.\n");
    exit(1);
}

$statements = array_filter(
    array_map('trim', explode(';', $sql)),
    function ($stmt) {
        $s = trim($stmt);
        return $s !== '' && strlen($s) > 2;
    }
);

$dangerous = array();
$patterns = array(
    '/DROP\s+DATABASE/i' => 'DROP DATABASE',
    '/DROP\s+TABLE/i'    => 'DROP TABLE',
    '/TRUNCATE\s+/i'     => 'TRUNCATE',
);
foreach ($statements as $stmt) {
    foreach ($patterns as $pattern => $label) {
        if (preg_match($pattern, $stmt)) {
            $dangerous[$label] = true;
        }
    }
}
$dangerous = array_keys($dangerous);
if (!empty($dangerous)) {
    echo "Dangerous operations detected: " . implode(', ', $dangerous) . "\n";
    echo "Type 'yes' to continue, anything else to abort: ";
    $line = fgets(STDIN);
    if (trim($line) !== 'yes') {
        fwrite(STDERR, "Aborted.\n");
        exit(1);
    }
}

$actor_id = 'cli';
$state_file = $repo_root . DIRECTORY_SEPARATOR . '.lupo_actor';
if (file_exists($state_file) && is_readable($state_file)) {
    $json = @file_get_contents($state_file);
    if ($json !== false) {
        $data = json_decode($json, true);
        if (is_array($data) && isset($data['actor_id'])) {
            $actor_id = (string) $data['actor_id'];
        }
    }
}

$log_dir = $repo_root . DIRECTORY_SEPARATOR . 'lupo-logs' . DIRECTORY_SEPARATOR . 'migrations';
if (!is_dir($log_dir)) {
    @mkdir($log_dir, 0755, true);
}
$log_file = $log_dir . DIRECTORY_SEPARATOR . gmdate('Y-m-d') . '.jsonl';

$log_entry = array(
    'timestamp'   => gmdate('YmdHis'),
    'actor_id'    => $actor_id,
    'migration'   => basename($real),
    'environment' => $environment,
    'status'      => 'started',
);
file_put_contents($log_file, json_encode($log_entry) . "\n", FILE_APPEND | LOCK_EX);

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db && class_exists('DatabaseFactory')) {
    try {
        $db = DatabaseFactory::getConnection();
    } catch (Exception $e) {
        $db = null;
    }
}
if (!$db) {
    $log_entry['status'] = 'failed';
    $log_entry['error'] = 'No database connection';
    file_put_contents($log_file, json_encode($log_entry) . "\n", FILE_APPEND | LOCK_EX);
    fwrite(STDERR, "Error: Database connection failed.\n");
    exit(1);
}

$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$max_tables = 199;
$total_tables = 0;
try {
    $pdo = method_exists($db, 'getPdo') ? $db->getPdo() : null;
    $driver = $pdo ? $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) : 'mysql';
    $pref = $table_prefix . '%';

    if ($driver === 'pgsql') {
        $sql = "SELECT COUNT(*) FROM information_schema.tables
                WHERE table_schema = 'public' AND table_name LIKE :pref";
        $total_tables = (int) $db->fetchOne($sql, array(':pref' => $pref));
    } elseif ($driver === 'sqlite') {
        $sql = "SELECT COUNT(*) FROM sqlite_master
                WHERE type='table' AND name LIKE :pref";
        $total_tables = (int) $db->fetchOne($sql, array(':pref' => $pref));
    } else {
        // mysql / mariadb default
        $sql = "SELECT COUNT(*) FROM information_schema.tables
                WHERE table_schema = DATABASE() AND table_name LIKE :pref";
        $total_tables = (int) $db->fetchOne($sql, array(':pref' => $pref));
    }
} catch (Exception $e) {
    // Fail open: if we cannot compute the count, don't block migrations.
    $total_tables = 0;
}

// SYSTEM_LIMITS enforcement: block schema changes when table count hits/exceeds 199.
if ($total_tables >= $max_tables) {
    $log_entry['status'] = 'failed';
    $log_entry['error'] = 'SYSTEM_LIMITS: schema change blocked (total_tables=' . $total_tables . ' >= ' . $max_tables . ')';
    $log_entry['completed_at'] = gmdate('YmdHis');
    file_put_contents($log_file, json_encode($log_entry) . "\n", FILE_APPEND | LOCK_EX);
    fwrite(STDERR, "Error: Schema change blocked by SYSTEM_LIMITS (tables >= 199).\n");
    exit(1);
}

$ok = true;
try {
    if (method_exists($db, 'beginTransaction')) {
        $db->beginTransaction();
    }
    foreach ($statements as $stmt) {
        $stmt = trim($stmt);
        if ($stmt === '') {
            continue;
        }
        $db->exec($stmt);
    }
    if (method_exists($db, 'commit')) {
        $db->commit();
    }
} catch (Exception $e) {
    $ok = false;
    if (method_exists($db, 'rollBack')) {
        try {
            $db->rollBack();
        } catch (Exception $rb) {
            // ignore
        }
    }
    $log_entry['status'] = 'failed';
    $log_entry['error'] = $e->getMessage();
    file_put_contents($log_file, json_encode($log_entry) . "\n", FILE_APPEND | LOCK_EX);
    fwrite(STDERR, "Migration failed: " . $e->getMessage() . "\n");
    exit(1);
}

$log_entry['status'] = 'completed';
$log_entry['completed_at'] = gmdate('YmdHis');
file_put_contents($log_file, json_encode($log_entry) . "\n", FILE_APPEND | LOCK_EX);
echo "Migration completed successfully.\n";
exit(0);
