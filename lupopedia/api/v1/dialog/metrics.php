<?php
/**
 * Dialog System Metrics Endpoint
 * 
 * GET /api/v1/dialog/metrics
 * 
 * Returns real-time performance metrics and statistics.
 */

require_once __DIR__ . '/../../../lupopedia-config.php';

header('Content-Type: application/json');

try {
    require_once LUPO_INCLUDES_DIR . '/class-pdo_db.php';
    $db = new PDO_DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_TYPE);
    
    // Get message statistics
    $stats = $db->fetchRow("
        SELECT 
            COUNT(*) as total_messages,
            COUNT(CASE WHEN created_ymdhis > :last_hour THEN 1 END) as messages_last_hour,
            AVG(LENGTH(message_text)) as avg_message_length,
            COUNT(DISTINCT from_actor_id) as unique_senders,
            COUNT(DISTINCT to_actor_id) as unique_recipients
        FROM lupo_dialog_doctrine
        WHERE is_deleted = 0
    ", ['last_hour' => gmdate('YmdHis', time() - 3600)]);
    
    // Get thread statistics
    $threadStats = $db->fetchRow("
        SELECT 
            COUNT(*) as total_threads,
            COUNT(CASE WHEN created_ymdhis > :last_hour THEN 1 END) as threads_last_hour
        FROM lupo_dialog_threads
        WHERE is_deleted = 0
    ", ['last_hour' => gmdate('YmdHis', time() - 3600)]);
    
    // Get mood distribution
    $moodDist = $db->fetchAll("
        SELECT 
            mood_rgb,
            COUNT(*) as count
        FROM lupo_dialog_doctrine
        WHERE is_deleted = 0
        GROUP BY mood_rgb
        ORDER BY count DESC
        LIMIT 10
    ");
    
    $metrics = [
        'timestamp' => gmdate('YmdHis'),
        'messages' => [
            'total' => (int)($stats['total_messages'] ?? 0),
            'last_hour' => (int)($stats['messages_last_hour'] ?? 0),
            'avg_length' => round((float)($stats['avg_message_length'] ?? 0), 2),
            'unique_senders' => (int)($stats['unique_senders'] ?? 0),
            'unique_recipients' => (int)($stats['unique_recipients'] ?? 0)
        ],
        'threads' => [
            'total' => (int)($threadStats['total_threads'] ?? 0),
            'last_hour' => (int)($threadStats['threads_last_hour'] ?? 0)
        ],
        'mood_distribution' => array_map(function($row) {
            return [
                'mood_rgb' => $row['mood_rgb'],
                'count' => (int)$row['count']
            ];
        }, $moodDist)
    ];
    
    echo json_encode($metrics, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'timestamp' => gmdate('YmdHis')
    ], JSON_PRETTY_PRINT);
}

?>
