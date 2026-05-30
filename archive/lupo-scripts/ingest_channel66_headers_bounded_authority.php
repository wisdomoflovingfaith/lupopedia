<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "lupo-scripts/ingest_channel66_headers_bounded_authority.php"
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
  file_path_from_root: "lupo-scripts/ingest_channel66_headers_bounded_authority.php"
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
 * ingest_channel66_headers_bounded_authority.php
 *
 * CLI runner for Channel 66 Thread 1001 P0 bounded-authority LUPOPEDIA HEADERS ingestion.
 *
 * Usage:
 *   php lupo-scripts/ingest_channel66_headers_bounded_authority.php --mode=p0 --thread-id=1001 --scope-root=<path> --toon-dir=<path>
 */

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__DIR__) . '/');
}
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', ABSPATH);
}

require_once ABSPATH . 'lupopedia-config.php';

require_once ABSPATH . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Channel66HeaderIngester.php';

$args = array();
foreach ($argv as $k => $v) {
    if ($k === 0) {
        continue;
    }
    if (strpos($v, '--') === 0) {
        $parts = explode('=', substr($v, 2), 2);
        if (count($parts) === 2) {
            $args[$parts[0]] = $parts[1];
        } else {
            $args[substr($v, 2)] = true;
        }
    }
}

$mode = isset($args['mode']) ? (string)$args['mode'] : 'p0';
$threadId = isset($args['thread-id']) ? (int)$args['thread-id'] : 1001;
$scopeRoot = isset($args['scope-root']) ? (string)$args['scope-root'] : ABSPATH;
$toonDir = isset($args['toon-dir']) ? (string)$args['toon-dir'] : (ABSPATH . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'toon');

try {
    $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
    if (!$db && class_exists('DatabaseFactory')) {
        $db = DatabaseFactory::getConnection();
    }
    if (!$db) {
        die("Error: no DB connection available.\n");
    }

    $ingester = new Channel66HeaderIngester($db);
    $summary = $ingester->ingest(array(
        'mode' => $mode,
        'thread_id' => $threadId,
        'scope_root' => $scopeRoot,
        'toon_dir' => $toonDir,
    ));

    echo "Channel 66 Thread " . $threadId . " ingestion complete.\n";
    echo "Total: " . $summary['total'] . "\n";
    echo "Ingested: " . $summary['ingested'] . "\n";
    echo "Rejected: " . $summary['rejected'] . "\n";
    echo "Conflict flagged: " . $summary['conflict_flagged'] . "\n";
    exit(0);
} catch (Exception $e) {
    fwrite(STDERR, "Ingestion failed: " . $e->getMessage() . "\n");
    exit(1);
}

