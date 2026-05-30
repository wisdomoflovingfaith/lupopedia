<?php
define('ABSPATH', 'C:/ServBay/www/servbay/lupopedia/');
require_once ABSPATH . 'lupopedia-config.php';
require_once ABSPATH . 'includes/bootstrap.php';

$db = DatabaseFactory::getConnection();
$prefix = LUPO_TABLE_PREFIX;

// Get ALL messages from channel 66 with full details
echo "=== ALL MESSAGES ON CHANNEL 66 ===\n";
echo "================================================\n\n";

try {
    $messages = $db->fetchAll(
        "SELECT dialog_message_id, from_actor_id, message_type, message_text, message_body, 
                metadata_json, created_ymdhis
         FROM {$prefix}dialog_messages 
         WHERE channel_id = :cid AND is_deleted = 0
         ORDER BY created_ymdhis DESC",
        array(':cid' => 66)
    );
    
    echo "Total messages: " . count($messages) . "\n\n";
    
    foreach ($messages as $i => $msg) {
        $num = $i + 1;
        $timestamp = $msg['created_ymdhis'];
        $year = substr($timestamp, 0, 4);
        $month = substr($timestamp, 4, 2);
        $day = substr($timestamp, 6, 2);
        $hour = substr($timestamp, 8, 2);
        $min = substr($timestamp, 10, 2);
        $sec = substr($timestamp, 12, 2);
        
        echo "[$num] ========================================\n";
        echo "Message ID: {$msg['dialog_message_id']}\n";
        echo "Type: {$msg['message_type']}\n";
        echo "From Actor: {$msg['from_actor_id']}\n";
        echo "Created: {$year}-{$month}-{$day} {$hour}:{$min}:{$sec}\n";
        echo "\n--- Message Text ---\n";
        echo $msg['message_text'] . "\n";
        
        if (!empty($msg['message_body'])) {
            echo "\n--- Message Body ---\n";
            echo substr($msg['message_body'], 0, 500) . "\n";
            if (strlen($msg['message_body']) > 500) {
                echo "  (... truncated, full length: " . strlen($msg['message_body']) . " chars)\n";
            }
        }
        
        if (!empty($msg['metadata_json'])) {
            echo "\n--- Metadata ---\n";
            $meta = json_decode($msg['metadata_json'], true);
            echo json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        }
        
        echo "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
