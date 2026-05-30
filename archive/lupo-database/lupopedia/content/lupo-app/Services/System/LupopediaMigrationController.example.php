<?php
/**
 * Example: wiring LupopediaMigrationController into CLI or admin-only endpoint
 *
 * Prerequisites:
 * - lupo_migration_log table exists (run 4_2_2_create_lupo_migration_log.sql)
 * - PDO connection to lupopedia database
 *
 * Usage:
 *   php -f run_migration.php -- migration_file.sql
 *   # or from an admin-only web endpoint that reads the migration body
 */

// -----------------------------------------------------------------------------
// 1) Create PDO connection (adjust DSN, user, password for your environment)
// -----------------------------------------------------------------------------
$dsn = 'mysql:host=127.0.0.1;dbname=lupopedia;charset=utf8mb4';
$user = 'lupopedia_user';
$pass = 'secret';

$pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// -----------------------------------------------------------------------------
// 2) Load migration SQL (CLI: from file; web: from request body after auth)
// -----------------------------------------------------------------------------
$sql = '';
if (php_sapi_name() === 'cli') {
    $file = $argv[1] ?? null;
    if (!$file || !is_readable($file)) {
        fwrite(STDERR, "Usage: php run_migration.php <migration.sql>\n");
        exit(1);
    }
    $sql = file_get_contents($file);
} else {
    // Admin-only HTTP: require auth, read raw body
    // if (!yourAuth()->isAdmin()) { http_response_code(403); exit; }
    $sql = file_get_contents('php://input') ?: '';
}
$sql = trim($sql);
if ($sql === '') {
    fwrite(STDERR, "Empty migration.\n");
    exit(1);
}

// -----------------------------------------------------------------------------
// 3) Instantiate controller and execute
// -----------------------------------------------------------------------------
require_once __DIR__ . '/LupopediaMigrationController.php';

use App\Services\System\LupopediaMigrationController;

$controller = new LupopediaMigrationController();

try {
    $controller->executeMigration($pdo, $sql);
    if (php_sapi_name() === 'cli') {
        echo "Migration executed successfully.\n";
    } else {
        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'message' => 'Migration executed successfully.']);
    }
    exit(0);
} catch (\RuntimeException $e) {
    if (php_sapi_name() === 'cli') {
        fwrite(STDERR, "Blocked: " . $e->getMessage() . "\n");
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    }
    exit(1);
} catch (\PDOException $e) {
    if (php_sapi_name() === 'cli') {
        fwrite(STDERR, "Execution failed: " . $e->getMessage() . "\n");
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'error' => 'Execution failed: ' . $e->getMessage()]);
    }
    exit(1);
}
