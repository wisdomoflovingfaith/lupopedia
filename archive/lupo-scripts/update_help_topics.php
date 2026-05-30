<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "lupo-scripts/update_help_topics.php"
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
  file_path_from_root: "lupo-scripts/update_help_topics.php"
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
 * wolfie.header.identity: update-help-topics
 * wolfie.header.placement: /scripts/update_help_topics.php
 * wolfie.header.version: 3.1.1
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Created script to update help topics using UPSERT (INSERT ... ON DUPLICATE KEY UPDATE). Handles existing entries gracefully by updating them instead of failing."
 *   mood: "00FF00"
 */

/**
 * Update Help Topics Script
 * 
 * Updates help topics using UPSERT (INSERT ... ON DUPLICATE KEY UPDATE)
 * This handles existing entries gracefully by updating them instead of failing.
 * 
 * Run: php scripts/update_help_topics.php
 */

require_once __DIR__ . '/../lupopedia-config.php';
require_once __DIR__ . '/../lupo-includes/bootstrap.php';

echo "🔄 Updating help topics...\n\n";

// Get database connection
if (empty($GLOBALS['mydatabase'])) {
    die("❌ Database connection not available.\n");
}

$db = $GLOBALS['mydatabase'];

// Get table prefix
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

// Read the seed SQL file
$seed_file = __DIR__ . '/../database/migrations/4_1_1_seed_help_topics.sql';
if (!file_exists($seed_file)) {
    die("❌ Seed file not found: {$seed_file}\n");
}

$seed_sql = file_get_contents($seed_file);

// Replace INSERT with INSERT ... ON DUPLICATE KEY UPDATE
$upsert_sql = preg_replace(
    '/INSERT INTO `[^`]+` \(`[^`]+`\) VALUES\s*\(([^;]+)\);?/s',
    'INSERT INTO `' . $table_prefix . 'help_topics` (`slug`, `title`, `content_html`, `category`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`) VALUES $1 ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `content_html` = VALUES(`content_html`), `category` = VALUES(`category`), `updated_ymdhis` = VALUES(`updated_ymdhis`);',
    $seed_sql
);

// Remove comments and empty lines for cleaner execution
$upsert_sql = preg_replace('/^--.*$/m', '', $upsert_sql);
$upsert_sql = preg_replace('/^\s*$/m', '', $upsert_sql);

// Split by semicolon and execute each statement
$statements = array_filter(array_map('trim', explode(';', $upsert_sql)));

$updated = 0;
$inserted = 0;
$errors = 0;

foreach ($statements as $statement) {
    if (empty($statement) || !preg_match('/INSERT/i', $statement)) {
        continue;
    }
    
    try {
        // Check if topic exists
        if (preg_match("/'([^']+)'/", $statement, $matches)) {
            $slug = $matches[1];
            $check_sql = "SELECT COUNT(*) FROM `{$table_prefix}help_topics` WHERE slug = ?";
            $stmt = $db->prepare($check_sql);
            $stmt->execute([$slug]);
            $exists = $stmt->fetchColumn() > 0;
            
            $db->exec($statement);
            
            if ($exists) {
                echo "  ✅ Updated: {$slug}\n";
                $updated++;
            } else {
                echo "  ➕ Inserted: {$slug}\n";
                $inserted++;
            }
        }
    } catch (PDOException $e) {
        echo "  ❌ Error: " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n==========================================\n";
echo "Summary:\n";
echo "  ➕ Inserted: {$inserted}\n";
echo "  ✅ Updated: {$updated}\n";
if ($errors > 0) {
    echo "  ❌ Errors: {$errors}\n";
}
echo "==========================================\n\n";

if ($errors === 0) {
    echo "✅ Help topics updated successfully!\n";
} else {
    echo "⚠️  Completed with {$errors} error(s).\n";
}
