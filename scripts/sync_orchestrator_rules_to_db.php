<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "scripts/sync_orchestrator_rules_to_db.php"
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
  file_path_from_root: "scripts/sync_orchestrator_rules_to_db.php"
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
 * Sync Orchestrator Rules to DB (v4.0.73)
 * Reads every .md file in rules/root/, computes checksum, inserts/updates lupo_orchestrator_rules.
 * Run after migration 20260313_lupo_orchestrator_rules.sql.
 *
 * Usage: php scripts/sync_orchestrator_rules_to_db.php
 *
 * @package Lupopedia
 * @version 4.0.73
 */

$base = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
$configPath = $base . 'lupopedia-config.php';
if (!is_file($configPath)) {
    fwrite(STDERR, "Config not found: lupopedia-config.php\n");
    exit(1);
}
require_once $configPath;

$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$rules_table = $table_prefix . 'orchestrator_rules';
$rules_dir = $base . 'rules' . DIRECTORY_SEPARATOR . 'root' . DIRECTORY_SEPARATOR;

if (!is_dir($rules_dir)) {
    fwrite(STDERR, "Rules directory not found: rules/root/\n");
    exit(1);
}

$db = DatabaseFactory::getConnection();
$now = gmdate('YmdHis');
$default_applies = json_encode(array('audit', 'code-gen', 'db-sync', 'migration', 'header-sync'));
$version = '4.0.73+';
$actor = 'any';
$enforcement = 'strict';

$files = glob($rules_dir . '*.md');
if (empty($files)) {
    echo "No .md files in rules/root/\n";
    exit(0);
}

foreach ($files as $path) {
    $content = file_get_contents($path);
    if ($content === false) {
        fwrite(STDERR, "Could not read: $path\n");
        continue;
    }
    $checksum = md5($content);
    $rule_slug = basename($path, '.md');
    $rule_content = $content;

    $existing = $db->fetchRow(
        "SELECT rule_id, checksum FROM " . $db->quoteIdentifier($rules_table) . " WHERE rule_slug = :slug",
        array('slug' => $rule_slug)
    );

    if ($existing && $existing['checksum'] === $checksum) {
        echo "[SKIP] $rule_slug (unchanged)\n";
        continue;
    }

    $data = array(
        'rule_slug' => $rule_slug,
        'orchestrator_actor' => $actor,
        'rule_set_version' => $version,
        'applies_to_json' => $default_applies,
        'enforcement_level' => $enforcement,
        'rule_content' => $rule_content,
        'checksum' => $checksum,
        'is_active' => 1,
        'updated_ymdhis' => $now,
    );

    if ($existing) {
        $db->update($rules_table, $data, 'rule_id = :id', array('id' => $existing['rule_id']));
        echo "[UPDATE] $rule_slug\n";
    } else {
        $db->insert($rules_table, $data);
        echo "[INSERT] $rule_slug\n";
    }
}

echo "Sync complete.\n";
