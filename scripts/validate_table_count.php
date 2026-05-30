#!/usr/bin/env php
<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.0.99"
#   lupopedia.schema: implementation
#   when_updated: "20260412163505"
#   file_path_from_root: "scripts/validate_table_count.php"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/validate_table_count.php"
#   last_modified_utc: "20260412163505"
#   federation_node_id: 0
#   channel_key: "development"
#   trust_tier: "canonical"
#   memory_key: "memory/development/canonical/1026/04/validate-table-count.toon"
#   artifact_type: implementation
#   artifact_kind: tool
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   title: "Table Count Validator"
#   status: "complete"
#   parent_pk_id: "00"
#   summary: "Counts BASE TABLE rows in the configured schema; enforces constitutional <=199 limit (tmp heuristics excluded)."
#   module: null
#   dialog_transcript: "0/development/validate-table-count"
# ---------------------------------------------------------------------
/**
 * validate_table_count.php — count BASE TABLE rows in the configured schema (<=199 target).
 *
 * Excludes: tmp_ prefix, _tmp suffix (heuristic non-canonical tables).
 *
 * Usage (repo root, after lupopedia-config.php exists):
 *   php scripts/validate_table_count.php
 */

$repoRoot = dirname(__DIR__);
if (!defined('ABSPATH')) {
    define('ABSPATH', $repoRoot . DIRECTORY_SEPARATOR);
}
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repoRoot . DIRECTORY_SEPARATOR);
}
$cfg = $repoRoot . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
if (!is_file($cfg)) {
    fwrite(STDERR, "[SKIP] No lupopedia-config.php — cannot count tables.\n");
    exit(0);
}
require_once $cfg;
require_once $repoRoot . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'bootstrap.php';
if (!isset($GLOBALS['mydatabase'])) {
    fwrite(STDERR, "[SKIP] No database handle after bootstrap.\n");
    exit(0);
}

$db = $GLOBALS['mydatabase'];
if (!defined('DB_NAME')) {
    fwrite(STDERR, "DB_NAME not defined.\n");
    exit(1);
}
$schema = DB_NAME;

$sql = 'SELECT TABLE_NAME AS t FROM information_schema.tables '
    . 'WHERE table_schema = :schema AND table_type = :tt';
$rows = $db->fetchAll($sql, array('schema' => $schema, 'tt' => 'BASE TABLE'));
if (!is_array($rows)) {
    fwrite(STDERR, "Query failed.\n");
    exit(1);
}
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$count  = 0;
foreach ($rows as $row) {
    if (!isset($row['t'])) {
        continue;
    }
    $t = (string) $row['t'];
    if (strpos($t, $prefix) !== 0) {
        continue;
    }
    if (strpos($t, 'tmp_') === 0) {
        continue;
    }
    $len = strlen($t);
    if ($len >= 4 && substr($t, -4) === '_tmp') {
        continue;
    }
    $count++;
}

if ($count > 199) {
    fwrite(STDERR, "ERROR: {$count} {$prefix}* tables exceeds constitutional limit of 199. Consolidate before adding more.\n");
    exit(1);
}

echo "validate_table_count: OK ({$prefix}* tables = {$count}, limit 199)\n";
exit(0);
