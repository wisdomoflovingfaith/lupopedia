<?php
define('ABSPATH', 'C:/ServBay/www/servbay/lupopedia/');
require_once ABSPATH . 'lupopedia-config.php';
require_once ABSPATH . 'includes/bootstrap.php';

$db = DatabaseFactory::getConnection();
$prefix = LUPO_TABLE_PREFIX;

// Get messages from dialog_messages for channel 66
echo "=== DIALOG MESSAGES FOR CHANNEL 66 ===\n";
try {
    $messages = $db->fetchAll(
        "SELECT * FROM {$prefix}dialog_messages 
         WHERE channel_id = :cid AND is_deleted = 0
         ORDER BY created_ymdhis DESC",
        array(':cid' => 66)
    );
    
    echo "Found " . count($messages) . " messages\n\n";
    
    if (count($messages) > 0) {
        foreach ($messages as $msg) {
            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Get some statistics
echo "\n=== STATISTICS ===\n";
try {
    $stats = $db->fetchRow(
        "SELECT COUNT(*) as total, 
                SUM(CASE WHEN message_type='question' THEN 1 ELSE 0 END) as questions_count,
                SUM(CASE WHEN message_type='answer' THEN 1 ELSE 0 END) as answers_count
         FROM {$prefix}dialog_messages 
         WHERE channel_id = :cid AND is_deleted = 0",
        array(':cid' => 66)
    );
    
    echo json_encode($stats, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
