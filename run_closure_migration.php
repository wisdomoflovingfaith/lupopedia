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

    echo "=== RUNNING CLOSURE MIGRATION ===\n\n";

    // Read and execute migrations
    $migrations = [
        LUPOPEDIA_PATH . '/database/migrations/20260222_420_final_closure.sql',
        LUPOPEDIA_PATH . '/database/migrations/migrate_refinements_to_tickets_4.0.42.sql',
        LUPOPEDIA_PATH . '/database/migrations/migrate_documents_to_artifacts_4.0.42.sql'
    ];

    foreach ($migrations as $file) {
        if (!file_exists($file)) {
            echo "Warning: Migration file not found: $file\n";
            continue;
        }

        echo "Executing: " . basename($file) . "... ";
        $sql = file_get_contents($file);
        $result = $db->exec($sql);

        if ($result === false) {
            echo "FAILED\n";
            die("Migration execution failed for $file\n");
        }
        echo "SUCCESS\n";
    }

    echo "\nAll migrations executed successfully\n\n";


    // Verify results
    $stmt = $db->prepare('SELECT COUNT(*) FROM lupo_dialog_messages WHERE channel_id = 420');
    $stmt->execute();
    $count = $stmt->fetchColumn();

    echo "Messages in Channel 420 after migration: " . $count . "\n\n";

    if ($count > 0) {
        $stmt = $db->prepare('SELECT dialog_message_id, from_actor_id, message_text, created_ymdhis FROM lupo_dialog_messages WHERE channel_id = 420 ORDER BY dialog_message_id ASC');
        $stmt->execute();
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($messages as $message) {
            echo "Message ID: " . $message['dialog_message_id'] . "\n";
            echo "From Actor: " . $message['from_actor_id'] . "\n";
            echo "Text: " . $message['message_text'] . "\n";
            echo "Created: " . $message['created_ymdhis'] . "\n";
            echo "---\n\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
