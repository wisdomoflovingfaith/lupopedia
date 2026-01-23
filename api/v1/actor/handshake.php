<?php
/**
 * Lupopedia Minimal REST API — Actor Handshake
 *
 * POST /api/v1/actor/handshake
 *
 * Establish identity, timeline, and constraints.
 * Doctrine: stateless, UTC-driven, BIGINT, no FK.
 *
 * @package Lupopedia\API
 * @version 4.1.4
 */

require_once __DIR__ . '/../../../lupopedia-config.php';
require_once LUPO_INCLUDES_DIR . '/class-pdo_db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed', 'utc_timestamp' => gmdate('YmdHis')], JSON_PRETTY_PRINT);
    exit;
}

$raw = file_get_contents('php://input');
$in  = json_decode($raw, true) ?: [];

$actor_id   = isset($in['actor_id'])   ? (is_numeric($in['actor_id']) ? (int)$in['actor_id'] : (int) preg_replace('/\D/', '', (string)$in['actor_id'])) : 0;
$actor_type = isset($in['actor_type']) ? (string)$in['actor_type'] : '';
$utc        = isset($in['utc_timestamp']) ? (string)$in['utc_timestamp'] : gmdate('YmdHis');
$purpose    = isset($in['purpose'])    ? (string)$in['purpose'] : '';
$context    = isset($in['context'])    ? (string)$in['context'] : '';
$constraints = isset($in['constraints']) && is_array($in['constraints']) ? $in['constraints'] : [];
$forbidden  = isset($in['forbidden_actions']) && is_array($in['forbidden_actions']) ? $in['forbidden_actions'] : [];

$valid = ['human', 'ai', 'system'];
if (!in_array($actor_type, $valid, true)) {
    $actor_type = 'system';
}

$utc_num = (int) preg_replace('/\D/', '', $utc);
if ($utc_num <= 0) {
    $utc_num = (int) gmdate('YmdHis');
}

if (isset($in['expires_utc']) && is_numeric(preg_replace('/\D/', '', (string)$in['expires_utc']))) {
    $expires_utc = (int) preg_replace('/\D/', '', (string)$in['expires_utc']);
} else {
    $utc_str   = sprintf('%014d', $utc_num);
    $ts        = gmmktime((int)substr($utc_str, 8, 2), (int)substr($utc_str, 10, 2), (int)substr($utc_str, 12, 2), (int)substr($utc_str, 4, 2), (int)substr($utc_str, 6, 2), (int)substr($utc_str, 0, 4));
    $expires_utc = (int) gmdate('YmdHis', $ts + 86400);
}

$now = (int) gmdate('YmdHis');

try {
    $db = new PDO_DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_TYPE);

    $id = $db->insert('lupo_actor_handshakes', [
        'actor_id'             => $actor_id,
        'actor_type'           => $actor_type,
        'utc_timestamp'        => $utc_num,
        'purpose'              => $purpose,
        'constraints_json'     => json_encode($constraints),
        'forbidden_actions_json' => json_encode($forbidden),
        'context'              => $context,
        'expires_utc'          => $expires_utc,
        'created_ymdhis'       => $now,
        'is_deleted'           => 0,
    ]);

    http_response_code(200);
    echo json_encode([
        'status'        => 'accepted',
        'session_id'    => (int) $id,
        'expires_utc'   => (string) $expires_utc,
    ], JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error'         => $e->getMessage(),
        'utc_timestamp' => gmdate('YmdHis'),
    ], JSON_PRETTY_PRINT);
}
