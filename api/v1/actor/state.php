<?php
/**
 * Lupopedia Minimal REST API — Actor State Snapshot
 *
 * GET /api/v1/actor/state?actor_id=
 *
 * Returns the actor's last declared handshake and purpose.
 *
 * @package Lupopedia\API
 * @version 4.1.4
 */

require_once __DIR__ . '/../../../lupopedia-config.php';
require_once LUPO_INCLUDES_DIR . '/class-pdo_db.php';

header('Content-Type: application/json');

$actor_id = isset($_GET['actor_id']) ? (int) $_GET['actor_id'] : 0;
if ($actor_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'actor_id required', 'utc_timestamp' => gmdate('YmdHis')], JSON_PRETTY_PRINT);
    exit;
}

try {
    $db  = new PDO_DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_TYPE);
    $row = $db->fetchRow(
        "SELECT actor_id, actor_type, utc_timestamp, purpose, constraints_json, forbidden_actions_json 
         FROM lupo_actor_handshakes 
         WHERE actor_id = :aid AND is_deleted = 0 
         ORDER BY utc_timestamp DESC 
         LIMIT 1",
        ['aid' => $actor_id]
    );
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage(), 'utc_timestamp' => gmdate('YmdHis')], JSON_PRETTY_PRINT);
    exit;
}

if (!$row) {
    http_response_code(404);
    echo json_encode(['error' => 'actor state not found', 'actor_id' => $actor_id, 'utc_timestamp' => gmdate('YmdHis')], JSON_PRETTY_PRINT);
    exit;
}

$constraints = json_decode($row['constraints_json'] ?? '[]', true);
$forbidden   = json_decode($row['forbidden_actions_json'] ?? '[]', true);
if (!is_array($constraints)) {
    $constraints = [];
}
if (!is_array($forbidden)) {
    $forbidden = [];
}

echo json_encode([
    'actor_id'          => (int) $row['actor_id'],
    'actor_type'        => (string) $row['actor_type'],
    'lasthandshakeutc'  => (string) $row['utc_timestamp'],
    'purpose'           => (string) ($row['purpose'] ?? ''),
    'constraints'       => $constraints,
    'forbidden_actions' => $forbidden,
], JSON_PRETTY_PRINT);
