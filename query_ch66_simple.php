<?php
// Query channel 66 questions
define('ABSPATH', 'C:/ServBay/www/servbay/lupopedia/');
require_once ABSPATH . 'lupopedia-config.php';
require_once ABSPATH . 'lupo-includes/bootstrap.php';

$db = DatabaseFactory::getConnection();
$prefix = LUPO_TABLE_PREFIX;

// Get all questions from channel 66
echo "\n\n=== ALL QUESTIONS FROM CHANNEL 66 ===\n";
try {
    $questions = $db->fetchAll(
        "SELECT * FROM {$prefix}questions WHERE channel_id = :cid ORDER BY is_answered ASC, created_ymdhis DESC",
        array(':cid' => 66)
    );
    echo "Found " . count($questions) . " questions\n\n";
    
    if (count($questions) > 0) {
        foreach ($questions as $q) {
            echo json_encode($q, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
        }
    }
} catch (Exception $e) {
    echo "Questions table query failed: " . $e->getMessage() . "\n";
}

// Get channel info
echo "\n\n=== CHANNEL 66 INFO ===\n";
try {
    $channel = $db->fetchRow(
        "SELECT * FROM {$prefix}channels WHERE channel_id = :cid",
        array(':cid' => 66)
    );
    if ($channel) {
        echo json_encode($channel, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
    } else {
        echo "Channel 66 not found\n";
    }
} catch (Exception $e) {
    echo "Channel query failed: " . $e->getMessage() . "\n";
}
?>
