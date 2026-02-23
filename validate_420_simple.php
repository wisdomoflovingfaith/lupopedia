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
    
    // Task 1: Verify Channel 420 exists and count messages
    $stmt = $db->prepare('SELECT COUNT(*) AS message_count FROM lupo_dialog_messages WHERE channel_id = 420');
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $message_count = $result['message_count'];
    echo "Channel 420 message count: $message_count\n";
    
    if ($message_count == 0) {
        echo "FAIL: Channel 420 has no messages — aborting archive generation.\n";
        exit(1);
    } elseif ($message_count < 25) {
        echo "Only $message_count messages available; using all.\n";
    }
    
    // Task 2: Verify required actors exist
    $stmt = $db->prepare('SELECT actor_id, name, actor_status, actor_type FROM lupo_actors WHERE actor_id IN (59, 2038, 24, 420, 10000)');
    $stmt->execute();
    $actors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $required_actors = [59, 2038, 24, 420, 10000];
    $found_actors = [];
    
    foreach ($actors as $actor) {
        $found_actors[] = $actor['actor_id'];
        echo "Actor {$actor['actor_id']}: {$actor['name']} ({$actor['actor_type']}) - {$actor['actor_status']}\n";
    }
    
    $missing_actors = array_diff($required_actors, $found_actors);
    if (!empty($missing_actors)) {
        echo "FAIL: Missing actor IDs: " . implode(', ', $missing_actors) . "\n";
        exit(1);
    }
    
    echo "PRE-TASK VALIDATION: PASS\n";
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
}
