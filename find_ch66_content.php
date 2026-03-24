<?php
define('ABSPATH', 'C:/ServBay/www/servbay/lupopedia/');
require_once ABSPATH . 'lupopedia-config.php';
require_once ABSPATH . 'lupo-includes/bootstrap.php';

$db = DatabaseFactory::getConnection();
$prefix = LUPO_TABLE_PREFIX;

// Check dialog_channels 
echo "=== DIALOG_CHANNELS TABLE STRUCTURE ===\n";
try {
    $structure = $db->fetchAll("DESCRIBE {$prefix}dialog_channels");
    echo json_encode($structure, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Check dialog_messages
echo "\n=== DIALOG_MESSAGES TABLE STRUCTURE ===\n";
try {
    $structure = $db->fetchAll("DESCRIBE {$prefix}dialog_messages");
    echo json_encode($structure, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Check channel_content
echo "\n=== CHANNEL_CONTENT TABLE STRUCTURE ===\n";
try {
    $structure = $db->fetchAll("DESCRIBE {$prefix}channel_content");
    echo json_encode($structure, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Get messages from dialog_channels/messages for channel 66
echo "\n=== DIALOG MESSAGES FOR CHANNEL 66 ===\n";
try {
    $messages = $db->fetchAll(
        "SELECT * FROM {$prefix}dialog_channels WHERE channel_id = :cid LIMIT 5",
        array(':cid' => 66)
    );
    echo "Found " . count($messages) . " dialog channel entries\n";
    if (count($messages) > 0) {
        echo json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Check what's in channel_content for channel 66
echo "\n=== CHANNEL CONTENT FOR CHANNEL 66 ===\n";
try {
    $content = $db->fetchAll(
        "SELECT * FROM {$prefix}channel_content WHERE channel_id = :cid LIMIT 10",
        array(':cid' => 66)
    );
    echo "Found " . count($content) . " content items\n";
    if (count($content) > 0) {
        echo json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
