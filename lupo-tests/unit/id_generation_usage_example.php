<?php
/**
 * Example: Correct ID Generation Usage
 * 
 * Demonstrates how to properly use IdGenerator::generate()
 * for all primary keys in Lupopedia
 */

require_once __DIR__ . '/../../lupo-includes/classes/IdGenerator.php';

echo "=== ID Generation Usage Example ===\n\n";

// Example 1: Insert a new actor
echo "Example 1: Insert a new actor\n";
$actor_id = IdGenerator::generate();
echo "Generated actor_id: $actor_id\n";

// In real code, you would use DatabaseFactory to insert:
/*
$db = DatabaseFactory::getConnection();
$db->insert('lupo_actors', [
    'actor_id' => $actor_id,
    'actor_name' => 'Test Actor',
    'actor_type' => 'agent',
    'status' => 'active',
    'created_ymdhis' => gmdate('YmdHis'),
    'updated_ymdhis' => gmdate('YmdHis'),
]);
echo "Actor inserted with ID: $actor_id\n";
*/

// Example 2: Insert related records
echo "\nExample 2: Insert related records\n";
$capability_id = IdGenerator::generate();
echo "Generated capability_id: $capability_id\n";
echo "Using same actor_id for foreign key: $actor_id\n";

// In real code:
/*
$db->insert('lupo_agent_capabilities', [
    'capability_id' => $capability_id,
    'actor_id' => $actor_id,  // Foreign key to the actor
    'capability' => 'test_capability',
    'created_ymdhis' => gmdate('YmdHis'),
]);
*/

// Example 3: Multiple related inserts
echo "\nExample 3: Multiple related inserts\n";
$channel_id = IdGenerator::generate();
$thread_id = IdGenerator::generate();
$message_id = IdGenerator::generate();

echo "Generated channel_id: $channel_id\n";
echo "Generated thread_id: $thread_id\n";
echo "Generated message_id: $message_id\n";

// In real code:
/*
$db->insert('lupo_channels', [
    'channel_id' => $channel_id,
    'channel_name' => 'Test Channel',
    'created_ymdhis' => gmdate('YmdHis'),
]);

$db->insert('lupo_dialog_threads', [
    'thread_id' => $thread_id,
    'channel_id' => $channel_id,  // Foreign key
    'thread_title' => 'Test Thread',
    'created_ymdhis' => gmdate('YmdHis'),
]);

$db->insert('lupo_dialog_messages', [
    'message_id' => $message_id,
    'thread_id' => $thread_id,    // Foreign key
    'actor_id' => $actor_id,       // Foreign key
    'message_content' => 'Test message',
    'created_ymdhis' => gmdate('YmdHis'),
]);
*/

echo "\n=== Key Points ===\n";
echo "✅ Generate ID BEFORE the INSERT\n";
echo "✅ Use the generated ID as primary key\n";
echo "✅ Use the same ID for all related foreign key references\n";
echo "✅ Never use AUTO_INCREMENT or database-side ID generation\n";
echo "✅ All IDs are 63-bit signed-safe BIGINTs\n";
echo "✅ Format: YYYYMMDDHHIISS + 4-digit random suffix\n";

echo "\n=== Complete ===\n";
