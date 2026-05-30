<?php
define('ABSPATH', 'C:/ServBay/www/servbay/lupopedia/');
require_once ABSPATH . 'lupopedia-config.php';
require_once ABSPATH . 'includes/bootstrap.php';

$db = DatabaseFactory::getConnection();
$prefix = LUPO_TABLE_PREFIX;

// Count total messages
echo "=== CHANNEL 66: MESSAGE COUNT ===\n";
try {
    $result = $db->fetchRow(
        "SELECT COUNT(*) as total FROM {$prefix}dialog_messages WHERE channel_id = :cid",
        array(':cid' => 66)
    );
    echo "Total messages: " . $result['total'] . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Get only question-type messages (assuming message_type might indicate questions)
echo "=== UNIQUE MESSAGE TYPES ===\n";
try {
    $types = $db->fetchAll(
        "SELECT DISTINCT message_type, COUNT(*) as count FROM {$prefix}dialog_messages 
         WHERE channel_id = :cid AND is_deleted = 0
         GROUP BY message_type",
        array(':cid' => 66)
    );
    
    foreach ($types as $t) {
        echo "- {$t['message_type']}: {$t['count']}\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Get only "question" type messages - showing first 20
echo "=== QUESTION-TYPE MESSAGES (FIRST 20) ===\n";
try {
    $questions = $db->fetchAll(
        "SELECT dialog_message_id, from_actor_id, message_text, message_type, created_ymdhis 
         FROM {$prefix}dialog_messages 
         WHERE channel_id = :cid AND message_type = 'question' AND is_deleted = 0
         ORDER BY created_ymdhis DESC
         LIMIT 20",
        array(':cid' => 66)
    );
    
    echo "Found: " . count($questions) . " question-type messages\n\n";
    
    foreach ($questions as $q) {
        $timestamp = $q['created_ymdhis'];
        // Convert YYYYMMDDHHIISS to readable format
        $year = substr($timestamp, 0, 4);
        $month = substr($timestamp, 4, 2);
        $day = substr($timestamp, 6, 2);
        $hour = substr($timestamp, 8, 2);
        $min = substr($timestamp, 10, 2);
        $sec = substr($timestamp, 12, 2);
        
        echo "ID: {$q['dialog_message_id']} | Actor: {$q['from_actor_id']} | {$year}-{$month}-{$day} {$hour}:{$min}:{$sec}\n";
        echo "Message: " . substr($q['message_text'], 0, 200) . "\n";
        if (strlen($q['message_text']) > 200) {
            echo "  (TRUNCATED - full length: " . strlen($q['message_text']) . " chars)\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
