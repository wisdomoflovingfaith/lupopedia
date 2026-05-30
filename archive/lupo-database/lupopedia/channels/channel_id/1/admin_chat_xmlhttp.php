<?php
//===========================================================================
//* --                LUPOPEDIA Live Help XML HTTP Interface    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// ----------------------------------------------------------------------------

require_once("../../lupo-includes/bootstrap.php");

// Validate session and get actor info
$session_id = $_SESSION['session_id'] ?? '';
$actor_id = getCurrentActorId($session_id);

if (!$actor_id) {
    header('HTTP/1.0 401 Unauthorized');
    echo json_encode(['status' => 'error', 'message' => 'Invalid session']);
    exit;
}

// Set security headers
setSecurityHeaders();

// Get channel information
$channel_id = intval($_GET['channel_id'] ?? 1);
$channel_info = getChannelInfo($channel_id);

if (!$channel_info) {
    header('HTTP/1.0 404 Not Found');
    echo json_encode(['status' => 'error', 'message' => 'Channel not found']);
    exit;
}

// Handle different request types
$action = $_GET['action'] ?? 'get_messages';
$offset = intval($_GET['offset'] ?? 0);

switch ($action) {
    case 'get_messages':
        $messages = getChannelMessages($channel_id, $offset);
        echo json_encode(['status' => 'success', 'data' => $messages]);
        break;
        
    case 'send_message':
        // Validate CSRF token
        if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token']);
            exit;
        }
        
        $message = $_POST['message'] ?? '';
        if (empty($message)) {
            header('HTTP/1.0 400 Bad Request');
            echo json_encode(['status' => 'error', 'message' => 'Message cannot be empty']);
            exit;
        }
        
        $result = sendMessageToChannel($channel_id, $actor_id, $message);
        echo json_encode(['status' => $result ? 'success' : 'error']);
        break;
        
    case 'get_online_users':
        $users = getOnlineUsers($channel_id);
        echo json_encode(['status' => 'success', 'data' => $users]);
        break;
        
    case 'get_channel_info':
        echo json_encode(['status' => 'success', 'data' => $channel_info]);
        break;
        
    case 'update_status':
        // Update user's online status
        updateSessionActivity($session_id, $actor_id);
        echo json_encode(['status' => 'success', 'message' => 'Status updated']);
        break;
        
    default:
        header('HTTP/1.0 400 Bad Request');
        echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
        break;
}

/**
 * Get channel messages with pagination
 */
function getChannelMessages($channelId, $offset = 0) {
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    $sql = "SELECT dm.*, da.name as from_actor_name, da2.name as to_actor_name
              FROM {$table_prefix}dialog_messages dm
              LEFT JOIN {$table_prefix}actors da ON dm.from_actor_id = da.actor_id
              LEFT JOIN {$table_prefix}actors da2 ON dm.to_actor_id = da2.actor_id
              WHERE dm.channel_id = :channel_id 
              AND dm.is_deleted = 0
              ORDER BY dm.created_ymdhis ASC 
              LIMIT 50 OFFSET :offset";
    
    $messages = $db->fetchAll($sql, [
        'channel_id' => $channelId,
        'offset' => $offset
    ]);
    
    // Format messages for frontend
    $formatted_messages = [];
    foreach ($messages as $message) {
        $formatted_messages[] = [
            'dialog_message_id' => $message['dialog_message_id'],
            'from_actor_id' => $message['from_actor_id'],
            'to_actor_id' => $message['to_actor_id'],
            'from_actor_name' => $message['from_actor_name'],
            'to_actor_name' => $message['to_actor_name'],
            'message_text' => $message['message_text'],
            'message_type' => $message['message_type'],
            'created_ymdhis' => $message['created_ymdhis'],
            'formatted_time' => formatTimestamp($message['created_ymdhis']),
            'is_system' => $message['message_type'] === 'system'
        ];
    }
    
    return $formatted_messages;
}

/**
 * Send message to channel with timestamp-based ID generation
 */
function sendMessageToChannel($channelId, $actorId, $message) {
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    // Generate timestamp-based dialog_message_id
    $current_ymdhis = gmdate('YmdHis');
    $max_id_result = $db->fetch("SELECT MAX(dialog_message_id) as max_id FROM {$table_prefix}dialog_messages");
    $max_id = $max_id_result['max_id'] ?? 0;
    
    $dialog_message_id = ($max_id < $current_ymdhis) ? $current_ymdhis : $max_id + 1;
    
    // Create or get dialog thread
    $dialog_thread_id = getOrCreateDialogThread($channelId, $actorId);
    
    $sql = "INSERT INTO {$table_prefix}dialog_messages 
              (dialog_message_id, dialog_thread_id, channel_id, from_actor_id, to_actor_id, 
               message_text, message_type, created_ymdhis, updated_ymdhis, is_deleted)
              VALUES 
              (:dialog_message_id, :dialog_thread_id, :channel_id, :from_actor_id, :to_actor_id,
               :message_text, :message_type, :created_ymdhis, :updated_ymdhis, 0)";
    
    $result = $db->insert($sql, [
        'dialog_message_id' => $dialog_message_id,
        'dialog_thread_id' => $dialog_thread_id,
        'channel_id' => $channelId,
        'from_actor_id' => $actorId,
        'to_actor_id' => 0, // Broadcast to channel
        'message_text' => substr($message, 0, 1000), // Limit to message_text size
        'message_type' => 'text',
        'created_ymdhis' => $current_ymdhis,
        'updated_ymdhis' => $current_ymdhis
    ]);
    
    if ($result) {
        // Update session activity
        updateSessionActivity($_SESSION['session_id'], $actor_id);
        
        // Broadcast to WebSocket clients if available
        broadcastToWebSocketClients($channelId, [
            'type' => 'new_message',
            'dialog_message_id' => $dialog_message_id,
            'from_actor_id' => $actorId,
            'message_text' => substr($message, 0, 1000),
            'created_ymdhis' => $current_ymdhis
        ]);
    }
    
    return $result;
}

/**
 * Get or create dialog thread for channel
 */
function getOrCreateDialogThread($channelId, $actorId) {
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    // Check if default thread exists for this channel
    $sql = "SELECT dialog_thread_id FROM {$table_prefix}dialog_threads 
              WHERE channel_id = :channel_id AND is_deleted = 0 
              ORDER BY created_ymdhis ASC LIMIT 1";
    
    $result = $db->fetch($sql, ['channel_id' => $channelId]);
    
    if ($result) {
        return $result['dialog_thread_id'];
    }
    
    // Create new thread if none exists
    $current_ymdhis = gmdate('YmdHis');
    $dialog_thread_id = generateDialogThreadId($current_ymdhis);
    
    $sql = "INSERT INTO {$table_prefix}dialog_threads 
              (dialog_thread_id, federation_node_id, channel_id, created_by_actor_id, 
               summary_text, metadata_json, created_ymdhis, updated_ymdhis, is_deleted)
              VALUES 
              (:dialog_thread_id, 1, :channel_id, :created_by_actor_id, 
               :summary_text, :metadata_json, :created_ymdhis, :updated_ymdhis, 0)";
    
    $db->insert($sql, [
        'dialog_thread_id' => $dialog_thread_id,
        'channel_id' => $channelId,
        'created_by_actor_id' => $actorId,
        'summary_text' => "Channel {$channelId} Main Thread",
        'metadata_json' => json_encode(['type' => 'channel_main', 'auto_created' => true]),
        'created_ymdhis' => $current_ymdhis,
        'updated_ymdhis' => $current_ymdhis
    ]);
    
    return $dialog_thread_id;
}

/**
 * Generate timestamp-based dialog_thread_id
 */
function generateDialogThreadId($current_ymdhis) {
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    $max_id_result = $db->fetch("SELECT MAX(dialog_thread_id) as max_id FROM {$table_prefix}dialog_threads");
    $max_id = $max_id_result['max_id'] ?? 0;
    
    return ($max_id < $current_ymdhis) ? $current_ymdhis : $max_id + 1;
}

/**
 * Get online users for channel
 */
function getOnlineUsers($channelId) {
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    $sql = "SELECT DISTINCT s.actor_id, a.name, a.isonline, s.last_seen_ymdhis
              FROM {$table_prefix}sessions s
              LEFT JOIN {$table_prefix}actors a ON s.actor_id = a.actor_id
              WHERE s.channel_id = :channel_id 
              AND s.is_deleted = 0 
              AND s.last_seen_ymdhis > (UNIX_TIMESTAMP() - 300) // Active in last 5 minutes
              ORDER BY s.last_seen_ymdhis DESC";
    
    $users = $db->fetchAll($sql, ['channel_id' => $channelId]);
    
    $online_users = [];
    foreach ($users as $user) {
        $online_users[] = [
            'actor_id' => $user['actor_id'],
            'name' => $user['name'],
            'is_online' => true,
            'last_seen' => formatTimestamp($user['last_seen_ymdhis'])
        ];
    }
    
    return $online_users;
}

/**
 * Broadcast message to WebSocket clients (placeholder for future implementation)
 */
function broadcastToWebSocketClients($channelId, $message) {
    // This would integrate with a WebSocket server
    // For now, we'll log the broadcast
    error_log("WebSocket Broadcast to channel {$channelId}: " . json_encode($message));
}

/**
 * Format timestamp for display
 */
function formatTimestamp($ymdhis) {
    if (strlen($ymdhis) !== 14) {
        return $ymdhis;
    }
    
    $year = substr($ymdhis, 0, 4);
    $month = substr($ymdhis, 4, 2);
    $day = substr($ymdhis, 6, 2);
    $hour = substr($ymdhis, 8, 2);
    $minute = substr($ymdhis, 10, 2);
    $second = substr($ymdhis, 12, 2);
    
    return "{$year}-{$month}-{$day} {$hour}:{$minute}:{$second}";
}

/**
 * Get current actor ID from session
 */
function getCurrentActorId($session_id) {
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    $sql = "SELECT actor_id FROM {$table_prefix}sessions 
              WHERE session_id = :session_id AND is_deleted = 0";
    
    $result = $db->fetch($sql, ['session_id' => $session_id]);
    return $result ? $result['actor_id'] : 0;
}

/**
 * Update session activity
 */
function updateSessionActivity($session_id, $actor_id) {
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    $current_ymdhis = gmdate('YmdHis');
    
    $sql = "UPDATE {$table_prefix}sessions 
              SET last_seen_ymdhis = :last_seen, updated_ymdhis = :updated
              WHERE session_id = :session_id AND actor_id = :actor_id";
    
    return $db->update($sql, [
        'last_seen' => $current_ymdhis,
        'updated' => $current_ymdhis,
        'session_id' => $session_id,
        'actor_id' => $actor_id
    ]);
}

/**
 * Get channel information
 */
function getChannelInfo($channelId) {
    $db = DatabaseFactory::getConnection();
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    $sql = "SELECT * FROM {$table_prefix}channels 
              WHERE channel_id = :channel_id AND is_deleted = 0";
    
    return $db->fetch($sql, ['channel_id' => $channelId]);
}

/**
 * Set security headers
 */
function setSecurityHeaders() {
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; frame-src 'self'; connect-src 'self' ws: wss:");
    header("X-Frame-Options: SAMEORIGIN");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: strict-origin-when-cross-origin");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, CSRF-Token");
}

/**
 * Validate CSRF token
 */
function validateCSRFToken($token) {
    $session_token = $_SESSION['csrf_token'] ?? '';
    return hash_equals($session_token, $token);
}
?>
