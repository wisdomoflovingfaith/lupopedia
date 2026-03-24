<?php
/**
lupopedia.headers:
  when_updated: "20260324195530"
  file_path_from_root: "lupo-scripts/run_edge_migration_track3a.php"
  last_modified_utc: "20260324195530"
  channel_id: 66
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
  purpose: "Execute EdgeMigrationService::migrateDialogChannelRelations for 4.0.87 Track 3a."
lupopedia.footer:
  last_verified: "20260324195530"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/

$base = dirname(__DIR__);
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $base);
}

$config = $base . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
if (!is_file($config)) {
    fwrite(STDERR, "Config not found: lupopedia-config.php\n");
    exit(1);
}

require_once $config;
require_once $base . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'EdgeMigrationService.php';

$service = new App\Services\EdgeMigrationService();
$result = $service->migrateDialogChannelRelations();
$verify = $service->verifyMigration();

echo json_encode(
    array(
        'track' => '3a',
        'migration_result' => $result,
        'verification' => $verify,
    ),
    JSON_UNESCAPED_SLASHES
) . "\n";
