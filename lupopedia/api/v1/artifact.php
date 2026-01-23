<?php
/**
 * Lupopedia Minimal REST API — Artifact Registry
 *
 * POST /api/v1/artifact — Register a new artifact
 * GET  /api/v1/artifact?artifact_id= — Retrieve an artifact
 *
 * Doctrine: stateless, UTC-driven, BIGINT, no FK, artifact-first.
 *
 * @package Lupopedia\API
 * @version 4.1.4
 */

require_once __DIR__ . '/../../lupopedia-config.php';
require_once LUPO_INCLUDES_DIR . '/class-pdo_db.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$types = ['dialog', 'changelog', 'schema', 'lore', 'humor', 'protocol', 'fork_justification'];

try {
    $db = new PDO_DB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_TYPE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database unavailable', 'utc_timestamp' => gmdate('YmdHis')], JSON_PRETTY_PRINT);
    exit;
}

if ($method === 'POST') {

    $raw = file_get_contents('php://input');
    $in  = json_decode($raw, true) ?: [];

    $actor_id = isset($in['actor_id']) ? (int) (is_numeric($in['actor_id']) ? $in['actor_id'] : preg_replace('/\D/', '', (string)$in['actor_id'])) : 0;
    $utc      = isset($in['utc_timestamp']) ? (string)$in['utc_timestamp'] : gmdate('YmdHis');
    $type     = isset($in['type']) ? (string)$in['type'] : 'dialog';
    $content  = isset($in['content']) ? (string)$in['content'] : '';

    $utc_num = (int) preg_replace('/\D/', '', $utc);
    if ($utc_num <= 0) {
        $utc_num = (int) gmdate('YmdHis');
    }
    if (!in_array($type, $types, true)) {
        $type = 'dialog';
    }

    // fork_justification: content must be JSON with reason, blockedby, proposed_branch
    if ($type === 'fork_justification') {
        $decoded = json_decode($content, true);
        if (!is_array($decoded) || !isset($decoded['reason'], $decoded['blockedby'], $decoded['proposed_branch'])) {
            http_response_code(400);
            echo json_encode([
                'error' => 'fork_justification requires content JSON with reason, blockedby, proposed_branch',
                'utc_timestamp' => gmdate('YmdHis'),
            ], JSON_PRETTY_PRINT);
            exit;
        }
        $content = json_encode($decoded);
    }

    $now = (int) gmdate('YmdHis');

    $artifact_id = $db->insert('lupo_artifacts', [
        'actor_id'       => $actor_id,
        'utc_timestamp'  => $utc_num,
        'type'           => $type,
        'content'        => $content,
        'created_ymdhis' => $now,
        'is_deleted'     => 0,
    ]);

    http_response_code(200);
    echo json_encode([
        'status'      => 'stored',
        'artifact_id' => (int) $artifact_id,
    ], JSON_PRETTY_PRINT);
    exit;
}

if ($method === 'GET') {

    $id = isset($_GET['artifact_id']) ? (int) $_GET['artifact_id'] : 0;
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'artifact_id required', 'utc_timestamp' => gmdate('YmdHis')], JSON_PRETTY_PRINT);
        exit;
    }

    $row = $db->fetchRow(
        "SELECT artifact_id, utc_timestamp, type, content FROM lupo_artifacts WHERE artifact_id = :id AND is_deleted = 0",
        ['id' => $id]
    );

    if (!$row) {
        http_response_code(404);
        echo json_encode(['error' => 'artifact not found', 'artifact_id' => $id, 'utc_timestamp' => gmdate('YmdHis')], JSON_PRETTY_PRINT);
        exit;
    }

    http_response_code(200);
    echo json_encode([
        'artifact_id'   => (int) $row['artifact_id'],
        'utc_timestamp' => (string) $row['utc_timestamp'],
        'type'          => (string) $row['type'],
        'content'       => (string) $row['content'],
    ], JSON_PRETTY_PRINT);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed', 'utc_timestamp' => gmdate('YmdHis')], JSON_PRETTY_PRINT);
