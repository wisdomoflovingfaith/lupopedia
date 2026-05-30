<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "lupo-scripts/run_all_evolution_migrations.php"
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
  file_path_from_root: "lupo-scripts/run_all_evolution_migrations.php"
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
require_once dirname(__FILE__) . '/../lupopedia-config.php';
$db = DatabaseFactory::getConnection();

$files = array(
    'database/migrations/dev_20260308_multi_agent_evolution.sql',
    'database/migrations/dev_20260308_base_agent_tables.sql'
);

foreach ($files as $file) {
    echo "Running $file...\n";
    $sql = file_get_contents(ABSPATH . $file);
    foreach (explode(';', $sql) as $q) {
        $q = trim($q);
        if (empty($q))
            continue;
        try {
            $db->query($q);
        } catch (Exception $e) {
            // Ignore duplicates
            if (strpos($e->getMessage(), 'already exists') === false && strpos($e->getMessage(), 'Duplicate') === false) {
                echo "Error: " . $e->getMessage() . "\n";
            }
        }
    }
}
echo "All migrations finished.\n";
