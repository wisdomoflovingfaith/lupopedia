<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "scripts/set_actor_status.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175911"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
lupopedia.headers:
  when_updated: "20260324175617"
  file_path_from_root: "scripts/set_actor_status.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175617"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
 * Quick script to set actor status for Cursor and Kiro
 * Sets them to offline with token limits reset on March 3rd
 */

// Define path and load config
define('LUPOPEDIA_PATH', dirname(__DIR__));
define('LUPOPEDIA_ABSPATH', LUPOPEDIA_PATH);

// Load config
$config_file = LUPOPEDIA_PATH . '/lupopedia-config.php';
if (file_exists($config_file)) {
    require_once $config_file;
} else {
    die("Config file not found: $config_file\n");
}

// Try direct database connection
try {
    $db = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASSWORD,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage() . "\n");
}

$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$now = gmdate('YmdHis');

// Actors to update
$actors_to_update = array(
    1002 => array(
        'name' => 'Cursor IDE',
        'online_status' => 'offline',
        'token_limit' => '1000000', // 1M tokens
        'token_reset_date' => '20260303',
        'status_message' => 'Token limit reached. Reset scheduled for March 3, 2026.'
    ),
    1000 => array(
        'name' => 'Kiro',
        'online_status' => 'offline', 
        'token_limit' => '1000000', // 1M tokens
        'token_reset_date' => '20260303',
        'status_message' => 'Token limit reached. Reset scheduled for March 3, 2026.'
    )
);

echo "Updating actor status for Cursor and Kiro...\n\n";

foreach ($actors_to_update as $actor_id => $data) {
    echo "Processing {$data['name']} (ID: $actor_id)...\n";
    
    // Check if actor exists
    $actor = $db->fetchRow(
        "SELECT actor_id, name FROM {$table_prefix}actors WHERE actor_id = :actor_id AND is_deleted = 0 LIMIT 1",
        array('actor_id' => $actor_id)
    );
    
    if (!$actor) {
        echo "  ERROR: Actor not found\n\n";
        continue;
    }
    
    echo "  Found: {$actor['name']}\n";
    
    // Update online status
    $existing = $db->fetchRow(
        "SELECT metadata_id FROM {$table_prefix}metadata 
         WHERE entity_type = 'actor' AND entity_id = :actor_id AND property_key = 'online_status' AND is_deleted = 0 LIMIT 1",
        array('actor_id' => $actor_id, 'property_key' => 'online_status')
    );
    
    if ($existing && isset($existing['metadata_id'])) {
        $db->update(
            $table_prefix . 'metadata',
            array('property_value' => $data['online_status'], 'updated_ymdhis' => $now),
            'metadata_id = :metadata_id',
            array('metadata_id' => $existing['metadata_id'])
        );
        echo "  ✓ Updated online status to: {$data['online_status']}\n";
    } else {
        $next_id = (int) $db->fetchOne("SELECT COALESCE(MAX(metadata_id), 0) + 1 FROM {$table_prefix}metadata", array());
        $db->insert($table_prefix . 'metadata', array(
            'metadata_id' => $next_id,
            'entity_type' => 'actor',
            'entity_id' => $actor_id,
            'domain_id' => null,
            'meta_type' => null,
            'property_key' => 'online_status',
            'property_value' => $data['online_status'],
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
        ));
        echo "  ✓ Set online status to: {$data['online_status']}\n";
    }
    
    // Update token limit
    $existing = $db->fetchRow(
        "SELECT metadata_id FROM {$table_prefix}metadata 
         WHERE entity_type = 'actor' AND entity_id = :actor_id AND property_key = 'token_limit' AND is_deleted = 0 LIMIT 1",
        array('actor_id' => $actor_id, 'property_key' => 'token_limit')
    );
    
    if ($existing && isset($existing['metadata_id'])) {
        $db->update(
            $table_prefix . 'metadata',
            array('property_value' => $data['token_limit'], 'updated_ymdhis' => $now),
            'metadata_id = :metadata_id',
            array('metadata_id' => $existing['metadata_id'])
        );
        echo "  ✓ Updated token limit to: {$data['token_limit']}\n";
    } else {
        $next_id = (int) $db->fetchOne("SELECT COALESCE(MAX(metadata_id), 0) + 1 FROM {$table_prefix}metadata", array());
        $db->insert($table_prefix . 'metadata', array(
            'metadata_id' => $next_id,
            'entity_type' => 'actor',
            'entity_id' => $actor_id,
            'domain_id' => null,
            'meta_type' => null,
            'property_key' => 'token_limit',
            'property_value' => $data['token_limit'],
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
        ));
        echo "  ✓ Set token limit to: {$data['token_limit']}\n";
    }
    
    // Update token reset date
    $existing = $db->fetchRow(
        "SELECT metadata_id FROM {$table_prefix}metadata 
         WHERE entity_type = 'actor' AND entity_id = :actor_id AND property_key = 'token_reset_date' AND is_deleted = 0 LIMIT 1",
        array('actor_id' => $actor_id, 'property_key' => 'token_reset_date')
    );
    
    if ($existing && isset($existing['metadata_id'])) {
        $db->update(
            $table_prefix . 'metadata',
            array('property_value' => $data['token_reset_date'], 'updated_ymdhis' => $now),
            'metadata_id = :metadata_id',
            array('metadata_id' => $existing['metadata_id'])
        );
        echo "  ✓ Updated token reset date to: {$data['token_reset_date']}\n";
    } else {
        $next_id = (int) $db->fetchOne("SELECT COALESCE(MAX(metadata_id), 0) + 1 FROM {$table_prefix}metadata", array());
        $db->insert($table_prefix . 'metadata', array(
            'metadata_id' => $next_id,
            'entity_type' => 'actor',
            'entity_id' => $actor_id,
            'domain_id' => null,
            'meta_type' => null,
            'property_key' => 'token_reset_date',
            'property_value' => $data['token_reset_date'],
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
        ));
        echo "  ✓ Set token reset date to: {$data['token_reset_date']}\n";
    }
    
    // Update status message
    $existing = $db->fetchRow(
        "SELECT metadata_id FROM {$table_prefix}metadata 
         WHERE entity_type = 'actor' AND entity_id = :actor_id AND property_key = 'status_message' AND is_deleted = 0 LIMIT 1",
        array('actor_id' => $actor_id, 'property_key' => 'status_message')
    );
    
    if ($existing && isset($existing['metadata_id'])) {
        $db->update(
            $table_prefix . 'metadata',
            array('property_value' => $data['status_message'], 'updated_ymdhis' => $now),
            'metadata_id = :metadata_id',
            array('metadata_id' => $existing['metadata_id'])
        );
        echo "  ✓ Updated status message\n";
    } else {
        $next_id = (int) $db->fetchOne("SELECT COALESCE(MAX(metadata_id), 0) + 1 FROM {$table_prefix}metadata", array());
        $db->insert($table_prefix . 'metadata', array(
            'metadata_id' => $next_id,
            'entity_type' => 'actor',
            'entity_id' => $actor_id,
            'domain_id' => null,
            'meta_type' => null,
            'property_key' => 'status_message',
            'property_value' => $data['status_message'],
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
        ));
        echo "  ✓ Set status message\n";
    }
    
    echo "  ✓ Status updated successfully!\n\n";
}

echo "Actor status update complete!\n";
echo "\nYou can now manage actor status at: https://localhost/lupopedia/admin.php?section=actor_status\n";
echo "\nCurrent status:\n";
echo "- Cursor IDE (1002): Offline, 1M tokens, reset March 3, 2026\n";
echo "- Kiro (1000): Offline, 1M tokens, reset March 3, 2026\n";
