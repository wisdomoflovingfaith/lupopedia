<?php
/**
 * Dialog System Health Check Endpoint
 * 
 * GET /api/v1/dialog/health
 * 
 * Returns system health status and metrics.
 */

// Define LUPOPEDIA_PATH before loading config (required for bootstrap)
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(dirname(dirname(__DIR__))));
}
require_once LUPOPEDIA_PATH . '/includes/classes/LupopediaConfigResolver.php';
if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    define('LUPOPEDIA_PUBLIC_PATH', LupopediaConfigResolver::publicPathFromRequest(LUPOPEDIA_PATH));
}

require_once __DIR__ . '/../../../lupopedia-config.php';

header('Content-Type: application/json');

try {
    $db = DatabaseFactory::getConnection();

    // Check database connectivity
    $dbTest = $db->fetchOne("SELECT 1");
    $dbHealthy = ($dbTest === 1);

    // Check table existence
    $tablesExist = true;
    $requiredTables = ['lupo_dialog_threads', 'lupo_dialog_messages'];
    foreach ($requiredTables as $table) {
        $exists = $db->fetchOne("
            SELECT COUNT(*) 
            FROM information_schema.tables 
            WHERE table_schema = DATABASE() 
            AND table_name = :table
        ", ['table' => $table]);
        if (!$exists) {
            $tablesExist = false;
            break;
        }
    }

    // Get basic metrics
    $threadCount = $db->fetchOne("SELECT COUNT(*) FROM lupo_dialog_threads WHERE is_deleted = 0") ?: 0;
    $messageCount = $db->fetchOne("SELECT COUNT(*) FROM lupo_dialog_messages WHERE is_deleted = 0") ?: 0;

    $health = [
        'status' => ($dbHealthy && $tablesExist) ? 'healthy' : 'degraded',
        'timestamp' => gmdate('YmdHis'),
        'database' => [
            'connected' => $dbHealthy,
            'tables_exist' => $tablesExist
        ],
        'metrics' => [
            'threads' => (int) $threadCount,
            'messages' => (int) $messageCount
        ]
    ];

    http_response_code($health['status'] === 'healthy' ? 200 : 503);
    echo json_encode($health, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(503);
    echo json_encode([
        'status' => 'error',
        'error' => $e->getMessage(),
        'timestamp' => gmdate('YmdHis')
    ], JSON_PRETTY_PRINT);
}

?>
