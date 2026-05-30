<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "scripts/run_one_time_sql.php"
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
  file_path_from_root: "scripts/run_one_time_sql.php"
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
 * One-Time SQL Runner (Minimal)
 * Runs a SQL file and ignores common "already exists" errors for idempotency.
 * 
 * Usage: php scripts/run_one_time_sql.php <path_to_sql_file>
 *
 * @package Lupopedia
 * @version 4.0.73
 */

require_once dirname(__FILE__) . '/../lupopedia-config.php';

$sqlFile = isset($argv[1]) ? $argv[1] : '';

if (empty($sqlFile)) {
    die("Usage: php scripts/run_one_time_sql.php <path_to_sql_file>\n");
}

$base = defined('ABSPATH') ? ABSPATH : dirname(__DIR__) . '/';
$fullPath = $sqlFile;
if (!is_file($fullPath)) {
    $fullPath = $base . $sqlFile;
}

if (!is_file($fullPath)) {
    die("SQL file not found: $sqlFile\n");
}

$db = DatabaseFactory::getConnection();

echo "Running one-time SQL: $sqlFile\n";

$sql = file_get_contents($fullPath);
// Strip comments and split by semicolon
$sql = preg_replace('/^\s*--[^\n]*\n/m', "\n", $sql);
$queries = explode(';', $sql);

foreach ($queries as $query) {
    $query = trim($query);
    if (empty($query)) {
        continue;
    }

    try {
        $db->query($query);
        echo "[OK] " . substr(preg_replace('/\s+/', ' ', $query), 0, 80) . "...\n";
    } catch (Exception $e) {
        $msg = $e->getMessage();
        // Soft error: ignore "already exists" errors for idempotency
        $isSoftError = (
            stripos($msg, 'already exists') !== false || 
            stripos($msg, 'Duplicate column name') !== false ||
            stripos($msg, 'Duplicate key name') !== false ||
            stripos($msg, 'Duplicate entry') !== false
        );

        if ($isSoftError) {
            echo "[SKIP] " . substr(preg_replace('/\s+/', ' ', $query), 0, 80) . "... (Reason: AlreadyExists)\n";
        } else {
            echo "[ERROR] " . substr(preg_replace('/\s+/', ' ', $query), 0, 80) . "...\n";
            echo "Message: " . $msg . "\n";
        }
    }
}

echo "SQL execution complete.\n";
?>
