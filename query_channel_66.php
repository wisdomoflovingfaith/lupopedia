<?php
// Query channel 66 questions
define('ABSPATH', 'C:/ServBay/www/servbay/lupopedia/');
require_once ABSPATH . 'lupopedia-config.php';
require_once ABSPATH . 'lupo-includes/bootstrap.php';

$db = DatabaseFactory::getConnection();
$prefix = LUPO_TABLE_PREFIX;

// Check what tables exist related to questions/QA
echo "=== CHECKING AVAILABLE TABLES ===\n";
$tables_result = $db->fetchAll("SHOW TABLES");
echo json_encode($tables_result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

// Get all records from QA-related tables on channel 66
echo "=== QUESTIONS ON CHANNEL 66 ===\n";
try {
    $questions = $db->fetchAll(
        "SELECT * FROM {$prefix}qa WHERE channel_id = :cid ORDER BY is_answered ASC, created_ymdhis DESC",
        array(':cid' => 66)
    );
    echo "Found " . count($questions) . " questions\n";
    echo json_encode($questions, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
} catch (Exception $e) {
    echo "QA table query failed: " . $e->getMessage() . "\n";
}

// Try channels messages
echo "=== CHANNEL MESSAGES ON CHANNEL 66 ===\n";
try {
    $messages = $db->fetchAll(
        "SELECT * FROM {$prefix}channel_messages WHERE channel_id = :cid AND is_question = 1 AND is_answered = 0 ORDER BY created_ymdhis DESC LIMIT 30",
        array(':cid' => 66)
    );
    echo "Found " . count($messages) . " open question messages\n";
    echo json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
} catch (Exception $e) {
    echo "Channel messages table query failed: " . $e->getMessage() . "\n";
}

// Show channel info
echo "=== CHANNEL INFO ===\n";
try {
    $channel = $db->fetchRow(
        "SELECT * FROM {$prefix}channels WHERE channel_id = :cid",
        array(':cid' => 66)
    );
    echo json_encode($channel, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
} catch (Exception $e) {
    echo "Channel query failed: " . $e->getMessage() . "\n";
}
?>
