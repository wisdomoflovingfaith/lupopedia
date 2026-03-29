<?php
/**
 * Live Help HTTP Request Handler
 * 
 * Routes incoming live help requests:
 * - POST /livehelp.php?action=send_message — Visitor/operator sends chat message
 * - POST /livehelp.php?action=operator_accept — Operator accepts incoming chat invitation
 * - POST /livehelp.php?action=operator_decline — Operator declines chat invitation
 * - POST /livehelp.php?action=end_chat — Operator ends active chat
 * - GET /livehelp.php?action=poll_messages&chat_id=X — Poll for new messages (fallback)
 * - GET /livehelp.php?action=get_chat_status&chat_id=X — Get current chat status
 */

// Ensure DB connection and auth are available
if (!isset($GLOBALS['mydatabase']) || !isset($_SESSION)) {
    http_response_code(500);
    exit(json_encode(['error' => 'System not initialized']));
}

$db = $GLOBALS['mydatabase'];
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

// Load service classes
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/ActorAvailabilityService.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/ChatRoutingService.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/ChatService.php';

// Get action
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

// Route action
switch ($action) {
    case 'send_message':
        handle_send_message();
        break;
    case 'operator_accept':
        handle_operator_accept();
        break;
    case 'operator_decline':
        handle_operator_decline();
        break;
    case 'end_chat':
        handle_end_chat();
        break;
    case 'poll_messages':
        handle_poll_messages();
        break;
    case 'get_chat_status':
        handle_get_chat_status();
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action: ' . $action]);
        break;
}

// ===== HTTP HANDLERS =====

/**
 * Handle message sending (visitor or operator)
 * 
 * POST /livehelp.php?action=send_message
 * Body: {
 *   "channel_id": 1,
 *   "chat_collection_id": 123,
 *   "message_body": "...",
 *   "message_type": "chat"  // optional: chat, system, etc.
 * }
 */
function handle_send_message()
{
    global $db, $table_prefix;
    
    // Check CSRF token
    if (!check_csrf_token()) {
        http_response_code(403);
        exit(json_encode(['error' => 'CSRF validation failed']));
    }
    
    // Validate input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['channel_id']) || !isset($input['chat_collection_id']) || !isset($input['message_body'])) {
        http_response_code(400);
        exit(json_encode(['error' => 'Missing required fields']));
    }
    
    $channel_id = (int) $input['channel_id'];
    $chat_collection_id = (int) $input['chat_collection_id'];
    $message_body = trim($input['message_body']);
    $message_type = isset($input['message_type']) ? $input['message_type'] : 'chat';
    
    // Validate message not empty
    if (empty($message_body)) {
        http_response_code(400);
        exit(json_encode(['error' => 'Message cannot be empty']));
    }
    
    // Get current actor from session
    $current_actor_id = isset($_SESSION['actor_id']) ? (int) $_SESSION['actor_id'] : 0;
    
    if (!$current_actor_id) {
        http_response_code(401);
        exit(json_encode(['error' => 'Not authenticated']));
    }
    
    // Verify actor has access to channel (membership check)
    $membership = $db->fetchRow(
        "SELECT actor_channel_id FROM {$table_prefix}actor_channels 
         WHERE actor_id = :actor_id 
         AND channel_id = :channel_id 
         AND is_deleted = 0",
        ['actor_id' => $current_actor_id, 'channel_id' => $channel_id]
    );
    
    // Allow if member OR if guest actor in unprotected chat (TODO: enhance auth)
    if (!$membership) {
        http_response_code(403);
        exit(json_encode(['error' => 'No access to this channel']));
    }
    
    // Allocate message ID
    $message_id = allocate_message_id($db, $table_prefix);
    
    // Insert message into channel_messages
    $now = gmdate('YmdHis');
    $result = $db->insert(
        $table_prefix . 'channel_messages',
        [
            'channel_message_id' => $message_id,
            'channel_id' => $channel_id,
            'thread_id' => $chat_collection_id,  // Link to chat collection
            'actor_id' => $current_actor_id,
            'message_body' => $message_body,
            'message_type' => $message_type,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
        ]
    );
    
    if ($result) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message_id' => $message_id,
            'created_ymdhis' => $now,
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to insert message']);
    }
}

/**
 * Handle operator accepting a chat invitation
 * 
 * POST /livehelp.php?action=operator_accept
 * Body: {
 *   "operator_actor_id": 50,
 *   "chat_collection_id": 123
 * }
 */
function handle_operator_accept()
{
    global $db, $table_prefix;
    
    if (!check_csrf_token()) {
        http_response_code(403);
        exit(json_encode(['error' => 'CSRF validation failed']));
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['operator_actor_id']) || !isset($input['chat_collection_id'])) {
        http_response_code(400);
        exit(json_encode(['error' => 'Missing required fields']));
    }
    
    $operator_actor_id = (int) $input['operator_actor_id'];
    $chat_collection_id = (int) $input['chat_collection_id'];
    
    // Verify operator is logged in (session actor matches)
    $session_actor = isset($_SESSION['actor_id']) ? (int) $_SESSION['actor_id'] : 0;
    
    if ($session_actor !== $operator_actor_id) {
        http_response_code(403);
        exit(json_encode(['error' => 'Cannot accept chats for other operators']));
    }
    
    // Use ChatService
    $chat_service = new ChatService($db, $table_prefix);
    $result = $chat_service->acceptChat($operator_actor_id, $chat_collection_id);
    
    if ($result) {
        http_response_code(200);
        echo json_encode(['success' => true, 'chat_collection_id' => $chat_collection_id]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to accept chat']);
    }
}

/**
 * Handle operator declining a chat invitation
 * 
 * POST /livehelp.php?action=operator_decline
 * Body: {
 *   "operator_actor_id": 50,
 *   "chat_collection_id": 123,
 *   "reason": "Too busy"  // optional
 * }
 */
function handle_operator_decline()
{
    global $db, $table_prefix;
    
    if (!check_csrf_token()) {
        http_response_code(403);
        exit(json_encode(['error' => 'CSRF validation failed']));
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['operator_actor_id']) || !isset($input['chat_collection_id'])) {
        http_response_code(400);
        exit(json_encode(['error' => 'Missing required fields']));
    }
    
    $operator_actor_id = (int) $input['operator_actor_id'];
    $chat_collection_id = (int) $input['chat_collection_id'];
    $reason = isset($input['reason']) ? $input['reason'] : '';
    
    // Verify operator is logged in
    $session_actor = isset($_SESSION['actor_id']) ? (int) $_SESSION['actor_id'] : 0;
    
    if ($session_actor !== $operator_actor_id) {
        http_response_code(403);
        exit(json_encode(['error' => 'Cannot decline chats for other operators']));
    }
    
    // Use ChatService
    $chat_service = new ChatService($db, $table_prefix);
    $result = $chat_service->declineChat($operator_actor_id, $chat_collection_id, $reason);
    
    if ($result) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Chat re-routed to next available operator']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to decline chat']);
    }
}

/**
 * Handle ending a chat
 * 
 * POST /livehelp.php?action=end_chat
 * Body: {
 *   "chat_collection_id": 123,
 *   "reason": "operator_ended"  // optional
 * }
 */
function handle_end_chat()
{
    global $db, $table_prefix;
    
    if (!check_csrf_token()) {
        http_response_code(403);
        exit(json_encode(['error' => 'CSRF validation failed']));
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['chat_collection_id'])) {
        http_response_code(400);
        exit(json_encode(['error' => 'Missing chat_collection_id']));
    }
    
    $chat_collection_id = (int) $input['chat_collection_id'];
    $reason = isset($input['reason']) ? $input['reason'] : 'operator_ended';
    
    // Use ChatService
    $chat_service = new ChatService($db, $table_prefix);
    $result = $chat_service->endChat($chat_collection_id, $reason);
    
    if ($result) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Chat ended']);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to end chat']);
    }
}

/**
 * Handle polling for new messages (fallback, not WebSocket)
 * 
 * GET /livehelp.php?action=poll_messages&chat_id=123&since=20260326120000
 */
function handle_poll_messages()
{
    global $db, $table_prefix;
    
    $chat_id = isset($_GET['chat_id']) ? (int) $_GET['chat_id'] : 0;
    $since = isset($_GET['since']) ? $_GET['since'] : '';
    
    if (!$chat_id) {
        http_response_code(400);
        exit(json_encode(['error' => 'Missing chat_id']));
    }
    
    // Build query
    $query = "SELECT channel_message_id, actor_id, message_body, message_type, created_ymdhis 
              FROM {$table_prefix}channel_messages 
              WHERE thread_id = :thread_id 
              AND is_deleted = 0";
    $params = ['thread_id' => $chat_id];
    
    if ($since) {
        $query .= " AND created_ymdhis > :since";
        $params['since'] = $since;
    }
    
    $query .= " ORDER BY created_ymdhis ASC LIMIT 50";
    
    $messages = $db->fetchAll($query, $params);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'messages' => $messages ?: [],
        'count' => count($messages),
    ]);
}

/**
 * Handle getting chat status
 * 
 * GET /livehelp.php?action=get_chat_status&chat_id=123
 */
function handle_get_chat_status()
{
    global $db, $table_prefix;
    
    $chat_id = isset($_GET['chat_id']) ? (int) $_GET['chat_id'] : 0;
    
    if (!$chat_id) {
        http_response_code(400);
        exit(json_encode(['error' => 'Missing chat_id']));
    }
    
    $chat = $db->fetchRow(
        "SELECT metadata FROM {$table_prefix}collections 
         WHERE collection_id = :id 
         AND is_deleted = 0",
        ['id' => $chat_id]
    );
    
    if (!$chat) {
        http_response_code(404);
        exit(json_encode(['error' => 'Chat not found']));
    }
    
    $metadata = json_decode($chat['metadata'], true) ?: [];
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'status' => $metadata['status'] ?? 'unknown',
        'metadata' => $metadata,
    ]);
}

// ===== HELPERS =====

/**
 * Check CSRF token in request
 * 
 * @return bool True if valid
 */
function check_csrf_token()
{
    // TODO: Implement CSRF token validation
    // For now, accept all requests (add proper validation in Phase 5)
    return true;
}

/**
 * Allocate unique message ID
 * 
 * @param PDO_DB $db
 * @param string $table_prefix
 * @return int
 */
function allocate_message_id($db, $table_prefix)
{
    if (class_exists('DeterministicIdService')) {
        $service = new DeterministicIdService($db, $table_prefix);
        return $service->allocateId('channel_messages');
    }
    
    $last = $db->fetchRow(
        "SELECT MAX(channel_message_id) as max_id FROM {$table_prefix}channel_messages"
    );
    
    return ($last && $last['max_id']) ? (int) $last['max_id'] + 1 : 1001;
}
