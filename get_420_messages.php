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
    
    echo "=== CHANNEL 420 MESSAGE RETRIEVAL ===\n\n";
    
    $stmt = $db->prepare('SELECT dialog_message_id, from_actor_id, channel_id, dialog_thread_id, message_text, message_type, created_ymdhis FROM lupo_dialog_messages WHERE channel_id = 420 ORDER BY dialog_message_id ASC');
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($messages) . " messages in Channel 420:\n\n";
    
    foreach ($messages as $message) {
        echo "Message ID: " . $message['dialog_message_id'] . "\n";
        echo "From Actor: " . $message['from_actor_id'] . "\n";
        echo "Channel: " . $message['channel_id'] . "\n";
        echo "Thread: " . $message['dialog_thread_id'] . "\n";
        echo "Type: " . $message['message_type'] . "\n";
        echo "Created: " . $message['created_ymdhis'] . "\n";
        echo "Text: " . $message['message_text'] . "\n";
        echo "---\n\n";
    }
    
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
}
