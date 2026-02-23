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
    
    echo "=== TASK 0 - QUICK VALIDATION QUERIES ===\n\n";
    
    // 1) Message count and final message
    echo "1) Channel 420 Message Count and Final Message:\n";
    $stmt = $db->prepare('SELECT dialog_message_id, channel_id, from_actor_id, message_text, created_ymdhis FROM lupo_dialog_messages WHERE channel_id = 420 ORDER BY created_ymdhis ASC');
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Total messages: " . count($messages) . "\n";
    
    if (count($messages) > 0) {
        $final_message = end($messages);
        echo "Final message ID: " . $final_message['dialog_message_id'] . "\n";
        echo "Final message text: " . $final_message['message_text'] . "\n";
        echo "Expected: CAPTAIN STONED LUPOPEDIA WOLFIE — FINAL DECLARATION BEFORE CHANNEL 420 ARCHIVE\n";
        
        if ($final_message['message_text'] === 'CAPTAIN STONED LUPOPEDIA WOLFIE — FINAL DECLARATION BEFORE CHANNEL 420 ARCHIVE') {
            echo "✅ Final message matches expected text\n";
        } else {
            echo "❌ Final message does NOT match expected text\n";
        }
    }
    
    echo "\n";
    
    // 2) Actor 420 status
    echo "2) Actor 420 Status:\n";
    $stmt = $db->prepare('SELECT actor_id, name, actor_type, is_active, is_deleted, metadata_json FROM lupo_actors WHERE actor_id = 420');
    $stmt->execute();
    $actor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($actor) {
        echo "Actor ID: " . $actor['actor_id'] . "\n";
        echo "Name: " . $actor['name'] . "\n";
        echo "Type: " . $actor['actor_type'] . "\n";
        echo "Active: " . ($actor['is_active'] ? 'YES' : 'NO') . "\n";
        echo "Deleted: " . ($actor['is_deleted'] ? 'YES' : 'NO') . "\n";
        
        // Check metadata for hybrid/banned status
        $metadata = json_decode($actor['metadata_json'], true);
        if (isset($metadata['actor_attributes'])) {
            echo "Actor Attributes: " . json_encode($metadata['actor_attributes']) . "\n";
        }
        
        if ($actor['is_active'] == 0 && $actor['is_deleted'] == 1) {
            echo "✅ Actor 420 is properly inactive and deleted\n";
        } else {
            echo "❌ Actor 420 status is unexpected\n";
        }
    } else {
        echo "❌ Actor 420 not found\n";
    }
    
    echo "\n=== VALIDATION COMPLETE ===\n";
    
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
}
