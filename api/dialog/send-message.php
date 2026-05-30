<?php
/**
 * Dialog System API Endpoint
 * 
 * POST /api/dialog/send-message.php
 * 
 * Accepts dialog messages and routes them through the DialogManager.
 * 
 * Expected POST data:
 * {
 *   "actor_id": int,
 *   "to_actor": int|null,
 *   "content": string,
 *   "mood_vector": "XXYYZZ" (optional, defaults to "666666"),
 *   "thread_id": int|null (optional),
 *   "message_type": "text|command|system|error" (optional, defaults to "text")
 * }
 * 
 * Returns JSON:
 * {
 *   "success": bool,
 *   "response_message_id": int,
 *   "response_text": string,
 *   "from_actor": int,
 *   "to_actor": int,
 *   "error": string (if success=false)
 * }
 */

// Define LUPOPEDIA_PATH before loading config (required for bootstrap)
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', dirname(dirname(__DIR__)));
}
if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(dirname(dirname(__DIR__))));
}

// Load Lupopedia bootstrap
// Path: api/dialog/send-message.php -> lupopedia-config.php (2 levels up)
require_once __DIR__ . '/../../lupopedia-config.php';

// Set JSON response header
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed. Use POST.'
    ]);
    exit;
}

try {
    // Get JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!$data) {
        throw new Exception('Invalid JSON input');
    }

    // Validate required fields
    if (!isset($data['actor_id']) || !isset($data['content'])) {
        throw new Exception('Missing required fields: actor_id and content are required');
    }

    // Hybrid Actor Security Gate (4.0.29 Centralization)
    try {
        if (class_exists('HybridActorSecurityService')) {
            HybridActorSecurityService::assertActorOperational((int) $data['actor_id'], 'api_send_message');
        }
    } catch (SecurityException $e) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Access denied: Actor is non-operational.']);
        exit;
    }

    // Build message packet
    $packet = [
        'actor_id' => (int) $data['actor_id'],
        'to_actor' => isset($data['to_actor']) ? (int) $data['to_actor'] : null,
        'content' => trim($data['content']),
        'mood_vector' => $data['mood_vector'] ?? '666666',
        'thread_id' => isset($data['thread_id']) ? (int) $data['thread_id'] : null,
        'message_type' => $data['message_type'] ?? 'text'
    ];

    // Validate content is not empty
    if (empty($packet['content'])) {
        throw new Exception('Content cannot be empty');
    }

    // Validate mood_vector format
    if (!preg_match('/^[0-9A-Fa-f]{6}$/', $packet['mood_vector'])) {
        throw new Exception('Invalid mood_vector format. Must be 6 hex digits (XXYYZZ)');
    }

    $db = DatabaseFactory::getConnection();

    // Initialize DialogManager
    require_once LUPO_INCLUDES_DIR . '/classes/dialog-manager.php';
    $dialogManager = new DialogManager($db);

    // Handle the message
    $response = $dialogManager->handleMessage($packet);

    // Return success response
    echo json_encode([
        'success' => true,
        'response_message_id' => $response['response_message_id'],
        'response_text' => $response['response_text'],
        'from_actor' => $response['from_actor'],
        'to_actor' => $response['to_actor']
    ]);

} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
