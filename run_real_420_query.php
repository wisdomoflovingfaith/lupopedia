<?php
// Define required constants
define('LUPOPEDIA_PATH', dirname(__FILE__));
define('LUPOPEDIA_PUBLIC_PATH', '/lupopedia');

// Load config
if (file_exists(LUPOPEDIA_PATH . '/lupopedia-config.php')) {
    require_once LUPOPEDIA_PATH . '/lupopedia-config.php';
} else {
    die("Config file not found\n");
}

// Load database
require_once LUPOPEDIA_PATH . '/lupo-includes/class-pdo_db.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/class-DatabaseFactory.php';

try {
    $db = DatabaseFactory::getConnection();
    
    echo "=== REAL CHANNEL 420 MESSAGES ===\n\n";
    
    $stmt = $db->prepare('SELECT dialog_message_id, from_actor_id, channel_id, dialog_thread_id, message_text, message_type, created_ymdhis FROM lupo_dialog_messages WHERE channel_id = 420 ORDER BY dialog_message_id ASC');
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($messages) . " messages in Channel 420:\n\n";
    
    if (empty($messages)) {
        echo "No messages found. This means the closure migration hasn't been run yet.\n";
        echo "Message 67 will be inserted by: database/migrations/20260222_420_final_closure.sql\n\n";
        echo "Expected Message 67 content:\n";
        echo "dialog_message_id: 67\n";
        echo "from_actor_id: 420\n";
        echo "channel_id: 420\n";
        echo "dialog_thread_id: 1\n";
        echo "message_text: CAPTAIN STONED LUPOPEDIA WOLFIE — FINAL DECLARATION BEFORE CHANNEL 420 ARCHIVE\n";
        echo "message_type: final\n";
        echo "created_ymdhis: 20260222000000\n";
    } else {
        foreach ($messages as $message) {
            echo "dialog_message_id: " . $message['dialog_message_id'] . "\n";
            echo "from_actor_id: " . $message['from_actor_id'] . "\n";
            echo "channel_id: " . $message['channel_id'] . "\n";
            echo "dialog_thread_id: " . $message['dialog_thread_id'] . "\n";
            echo "message_text: " . $message['message_text'] . "\n";
            echo "message_type: " . $message['message_type'] . "\n";
            echo "created_ymdhis: " . $message['created_ymdhis'] . "\n";
            echo "---\n\n";
        }
    }
    
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
}
