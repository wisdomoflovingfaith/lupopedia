#!/usr/bin/env php
<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.0.99"
#   lupopedia.schema: implementation
#   when_updated: "20260412162124"
#   file_path_from_root: "scripts/backfill_canonical_offsets.php"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/backfill_canonical_offsets.php"
#   last_modified_utc: "20260412162124"
#   federation_node_id: 0
#   channel_key: "development"
#   trust_tier: "canonical"
#   memory_key: "memory/development/canonical/1026/04/backfill-canonical-offsets.toon"
#   artifact_type: implementation
#   artifact_kind: tool
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   title: "Backfill canonical memory PK offsets"
#   status: "complete"
#   parent_pk_id: "38"
#   summary: "CLI wrapper for MemoryPromotionService staging to canonical PK band moves."
#   module: null
#   dialog_transcript: "0/development/backfill-canonical-offsets"
# ---------------------------------------------------------------------
/**
 * backfill_canonical_offsets.php
 *
 * CLI wrapper around MemoryPromotionService for staging → canonical PK band moves.
 * Default: dry-run listing candidate staging memory_node_id values.
 *
 * Usage (repo root):
 *   php scripts/backfill_canonical_offsets.php --dry-run
 *   php scripts/backfill_canonical_offsets.php --apply --actor-id=1 [--limit=N]
 */

$repoRoot = dirname(__DIR__);
if (!defined('ABSPATH')) {
    define('ABSPATH', $repoRoot . DIRECTORY_SEPARATOR);
}
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repoRoot . DIRECTORY_SEPARATOR);
}

$dryRun  = true;
$apply   = false;
$limit   = 100;
$actorId = 1;
foreach (array_slice($argv, 1) as $arg) {
    if ($arg === '--dry-run') {
        $dryRun = true;
        $apply  = false;
    }
    if ($arg === '--apply') {
        $apply  = true;
        $dryRun = false;
    }
    if (strpos($arg, '--limit=') === 0) {
        $limit = (int) substr($arg, 8);
    }
    if (strpos($arg, '--actor-id=') === 0) {
        $actorId = (int) substr($arg, strlen('--actor-id='));
    }
}

$config = $repoRoot . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
if (!is_file($config)) {
    fwrite(STDERR, "lupopedia-config.php not found — cannot run backfill.\n");
    exit(1);
}
require_once $config;
require_once $repoRoot . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'bootstrap.php';

if (!isset($GLOBALS['mydatabase'])) {
    fwrite(STDERR, "Database connection missing after bootstrap.\n");
    exit(1);
}

$db          = $GLOBALS['mydatabase'];
$tablePrefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$nodesTable  = $tablePrefix . 'memory_nodes';
$edgesTable  = $tablePrefix . 'memory_edges';

$sql = 'SELECT memory_node_id FROM ' . $db->quoteIdentifier($nodesTable)
    . ' WHERE is_deleted = 0 ORDER BY memory_node_id ASC LIMIT 10000';

try {
    $rows = $db->fetchAll($sql, array());
} catch (Exception $e) {
    fwrite(STDERR, 'Query failed: ' . $e->getMessage() . "\n");
    exit(1);
}

$candidates = array();
foreach ($rows as $row) {
    if (!isset($row['memory_node_id'])) {
        continue;
    }
    $sid = (string) $row['memory_node_id'];
    if (strlen($sid) !== 18 || !ctype_digit($sid)) {
        continue;
    }
    $y = (int) substr($sid, 0, 4);
    if ($y < 2000) {
        continue;
    }
    if (count($candidates) >= $limit) {
        break;
    }
    $chk = $db->fetchRow(
        'SELECT memory_edge_id FROM ' . $db->quoteIdentifier($edgesTable)
        . ' WHERE from_memory_node_id = :sid AND edge_type = :etype AND is_deleted = 0 LIMIT 1',
        array('sid' => $sid, 'etype' => 'promoted_to')
    );
    if ($chk === null || $chk === false) {
        $candidates[] = $sid;
    }
}

echo 'Candidates (staging band, no promoted_to edge, limit ' . (int) $limit . '): ' . count($candidates) . "\n";
foreach ($candidates as $sid) {
    echo '  ' . $sid . "\n";
}

if (!$apply) {
    echo "Dry-run only. Pass --apply --actor-id={id} to run MemoryPromotionService::promoteStagingToCanonical per row.\n";
    exit(0);
}

if ($actorId <= 0) {
    fwrite(STDERR, "--actor-id must be > 0 when using --apply\n");
    exit(1);
}

require_once $repoRoot . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'Kairos' . DIRECTORY_SEPARATOR . 'MemoryPromotionService.php';

$svc = new MemoryPromotionService($db, $tablePrefix);
$ok  = 0;
$fail = 0;
foreach ($candidates as $sid) {
    try {
        $res = $svc->promoteStagingToCanonical($sid, $actorId);
        echo 'PROMOTED ' . $sid . ' => ' . $res['canonical_memory_node_id'] . ' status=' . $res['status'] . "\n";
        $ok++;
    } catch (Exception $e) {
        fwrite(STDERR, 'FAIL ' . $sid . ': ' . $e->getMessage() . "\n");
        $fail++;
    }
}

echo "Done. ok={$ok} fail={$fail}\n";
exit($fail > 0 ? 1 : 0);
