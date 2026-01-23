<?php
/**
 * Lupopedia Minimal REST API — Timeline Query
 *
 * GET /api/v1/timeline?utc_day=YYYYMMDD
 *
 * Returns all artifacts for a given UTC day. Timeline-driven.
 *
 * @package Lupopedia\API
 * @version 4.1.4
 */

require_once __DIR__ . '/../../lupopedia-config.php';
require_once LUPO_INCLUDES_DIR . '/class-pdo_db.php';

header('Content-Type: application/json');

$day = isset($_GET['utc_day']) ? (string) preg_replace('/\D/', '', $_GET['utc_day']) : '';
if (strlen($day) !== 8) {
    $day = gmdate('Ymd');
}

$start = (int) ($day . '000000');
$end   = (int) ($day . '235959');

try {
    $db   = new PDO_DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_TYPE);
    $rows = $db->fetchAll(
        "SELECT artifact_id, type, utc_timestamp FROM lupo_artifacts 
         WHERE utc_timestamp BETWEEN :start AND :end AND is_deleted = 0 
         ORDER BY utc_timestamp ASC",
        ['start' => $start, 'end' => $end]
    );
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage(), 'utc_timestamp' => gmdate('YmdHis')], JSON_PRETTY_PRINT);
    exit;
}

$artifacts = array_map(function ($r) {
    return [
        'artifact_id'   => (int) $r['artifact_id'],
        'type'          => (string) $r['type'],
        'utc_timestamp' => (string) $r['utc_timestamp'],
    ];
}, $rows);

echo json_encode([
    'utc_day'   => $day,
    'artifacts' => $artifacts,
], JSON_PRETTY_PRINT);
