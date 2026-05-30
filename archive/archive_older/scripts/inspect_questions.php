<?php
define('ABSPATH', 'C:/ServBay/www/servbay/lupopedia/');
require_once ABSPATH . 'lupopedia-config.php';
require_once ABSPATH . 'includes/bootstrap.php';

$db = DatabaseFactory::getConnection();
$prefix = LUPO_TABLE_PREFIX;

// Describe the questions table
echo "=== QUESTIONS TABLE STRUCTURE ===\n";
try {
    $structure = $db->fetchAll("DESCRIBE {$prefix}questions");
    echo json_encode($structure, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Get all questions without channel_id filter
echo "\n=== ALL QUESTIONS (UP TO 10) ===\n";
try {
    $questions = $db->fetchAll(
        "SELECT * FROM {$prefix}questions LIMIT 10"
    );
    echo "Found " . count($questions) . " questions\n";
    foreach ($questions as $q) {
        echo json_encode($q, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Check question_map table
echo "\n=== QUESTION_MAP TABLE STRUCTURE ===\n";
try {
    $structure = $db->fetchAll("DESCRIBE {$prefix}question_map");
    echo json_encode($structure, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Get questions from question_map for channel 66
echo "\n=== QUESTIONS FROM QUESTION_MAP FOR CHANNEL 66 ===\n";
try {
    $questions = $db->fetchAll(
        "SELECT * FROM {$prefix}question_map WHERE channel_id = :cid",
        array(':cid' => 66)
    );
    echo "Found " . count($questions) . " mapped questions\n";
    foreach ($questions as $q) {
        echo json_encode($q, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
