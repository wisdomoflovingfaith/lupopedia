<?php
/**
 * Operator Login Endpoint
 * Authenticates operator using lupo_actors table
 */

require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['username']) || !isset($input['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'username and password required']);
    exit;
}

$db = DatabaseFactory::getConnection();
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

// Find actor with matching username and is_agent flag
$sql = "SELECT actor_id, actor_name, actor_type, metadata_json 
        FROM {$table_prefix}actors 
        WHERE actor_name = :username 
        AND is_agent = 1 
        AND is_deleted = 0";
$actor = $db->fetchOne($sql, ['username' => $input['username']]);

if (!$actor) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid credentials']);
    exit;
}

// TODO: Add password verification (will need password field in actors or separate auth table)

// Create session
$session_id = session_id() ?: bin2hex(random_bytes(16));
$now = gmdate('YmdHis');

$sql = "INSERT INTO {$table_prefix}sessions 
        (session_id, actor_id, created_ymdhis, updated_ymdhis) 
        VALUES (:session_id, :actor_id, :now, :now)";
$db->execute($sql, [
    'session_id' => $session_id,
    'actor_id' => $actor['actor_id'],
    'now' => $now
]);

session_id($session_id);
session_start();
$_SESSION['operator_id'] = $actor['actor_id'];
$_SESSION['operator_name'] = $actor['actor_name'];

echo json_encode([
    'success' => true,
    'actor_id' => $actor['actor_id'],
    'actor_name' => $actor['actor_name'],
    'session_id' => $session_id
]);
