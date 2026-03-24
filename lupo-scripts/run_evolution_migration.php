<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "lupo-scripts/run_evolution_migration.php"
  last_modified_utc: "20260324175911"
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
  file_path_from_root: "lupo-scripts/run_evolution_migration.php"
  last_modified_utc: "20260324175617"
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
 * Migration Runner for Multi-Agent Evolution
 */
require_once dirname(__FILE__) . '/../lupopedia-config.php';

$db = DatabaseFactory::getConnection();
$sqlFile = ABSPATH . 'database/migrations/dev_20260308_multi_agent_evolution.sql';

if (!is_file($sqlFile)) {
    die("Migration file not found: $sqlFile\n");
}

echo "Running evolution migration...\n";

$sql = file_get_contents($sqlFile);
// Split by semicolon (Doctrine: simple sequential execution for migrations)
$queries = explode(';', $sql);

foreach ($queries as $query) {
    $query = trim($query);
    if (empty($query))
        continue;

    try {
        $db->query($query);
    } catch (Exception $e) {
        // Soft error: ignore "table already exists" or "column already exists" for idempotency
        if (strpos($e->getMessage(), 'already exists') === false && strpos($e->getMessage(), 'Duplicate column name') === false) {
            echo "Error in query: " . substr($query, 0, 50) . "...\n";
            echo "Message: " . $e->getMessage() . "\n";
        }
    }
}

echo "Migration complete.\n";
?>
