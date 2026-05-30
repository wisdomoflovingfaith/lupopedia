<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "scripts/migrate_channel42_threads_to_db.php"
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
  file_path_from_root: "scripts/migrate_channel42_threads_to_db.php"
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
 * Migrate Channel 42 JSON threads to lupo_dialog_* tables.
 *
 * Reads JSON thread files from channels/42/threads/ (excluding archive/)
 * and inserts into lupo_dialog_threads and lupo_dialog_messages.
 * Optionally moves processed files to channels/42/threads/archive/.
 *
 * Usage: php scripts/migrate_channel42_threads_to_db.php [--no-archive]
 *
 * @package Lupopedia
 * @version 4.0.69
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
require_once $base . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'classes/pdo_db.php';

$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$dsn = (defined('DB_TYPE') ? DB_TYPE : 'mysql') . ":host=" . (defined('DB_HOST') ? DB_HOST : 'localhost') . ";dbname=" . (defined('DB_NAME') ? DB_NAME : '') . ";charset=utf8mb4";
$pdo = new PDO($dsn, defined('DB_USER') ? DB_USER : '', defined('DB_PASSWORD') ? DB_PASSWORD : '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$db = new PDO_DB($pdo);

$threads_dir = $base . DIRECTORY_SEPARATOR . 'channels' . DIRECTORY_SEPARATOR . '42' . DIRECTORY_SEPARATOR . 'threads';
$archive_dir = $threads_dir . DIRECTORY_SEPARATOR . 'archive';
$do_archive = true;
if (isset($argv[1]) && $argv[1] === '--no-archive') {
    $do_archive = false;
}

if (!is_dir($threads_dir)) {
    echo "Threads dir not found: {$threads_dir}\n";
    exit(0);
}
if ($do_archive && !is_dir($archive_dir)) {
    mkdir($archive_dir, 0755, true);
}

$t_threads = $db->quoteIdentifier($table_prefix . 'dialog_threads');
$t_messages = $db->quoteIdentifier($table_prefix . 'dialog_messages');

$thread_files = array();
foreach (array($threads_dir, $threads_dir . DIRECTORY_SEPARATOR . '4.0.x') as $dir) {
    if (!is_dir($dir)) {
        continue;
    }
    $files = glob($dir . DIRECTORY_SEPARATOR . '*.json');
    if ($files) {
        $thread_files = array_merge($thread_files, $files);
    }
}

echo "Found " . count($thread_files) . " thread file(s) to migrate.\n";

foreach ($thread_files as $file) {
    if (strpos($file, DIRECTORY_SEPARATOR . 'archive' . DIRECTORY_SEPARATOR) !== false) {
        continue;
    }
    echo "Processing: " . basename($file) . "\n";

    $content = file_get_contents($file);
    $thread_data = json_decode($content, true);
    if (!$thread_data) {
        echo "  Invalid JSON, skipping.\n";
        continue;
    }

    $thread_id_str = isset($thread_data['thread_id']) ? $thread_data['thread_id'] : basename($file, '.json');
    $version = isset($thread_data['version']) ? $thread_data['version'] : 'unknown';
    $title = isset($thread_data['title']) ? $thread_data['title'] : (isset($thread_data['purpose']) ? substr($thread_data['purpose'], 0, 255) : 'Thread ' . $thread_id_str);
    $messages = isset($thread_data['messages']) ? $thread_data['messages'] : array();

    $now = gmdate('YmdHis');
    $last_ymdhis = $now;
    foreach ($messages as $m) {
        $ts = isset($m['timestamp_ymdhis']) ? $m['timestamp_ymdhis'] : (isset($m['created_ymdhis']) ? $m['created_ymdhis'] : $now);
        if ($ts > $last_ymdhis) {
            $last_ymdhis = $ts;
        }
    }

    $row = $db->fetchRow("SELECT COALESCE(MAX(dialog_thread_id), 0) + 1 AS n FROM {$t_threads}", array());
    $next_thread_id = $row && isset($row['n']) ? (int) $row['n'] : 1;

    $db->query(
        "INSERT INTO {$t_threads} (dialog_thread_id, title, last_message_ymdhis, federation_node_id, channel_id, project_slug, task_name, created_by_actor_id, status, created_ymdhis, updated_ymdhis, is_deleted) " .
        "VALUES (:id, :title, :last_msg, 1, 42, 'channel-42', :task_name, 1, 'Open', :created, :updated, 0)",
        array(
            'id' => $next_thread_id,
            'title' => substr($title, 0, 255),
            'last_msg' => $last_ymdhis,
            'task_name' => substr($version, 0, 255),
            'created' => isset($thread_data['created_ymdhis']) ? $thread_data['created_ymdhis'] : $now,
            'updated' => isset($thread_data['updated_ymdhis']) ? $thread_data['updated_ymdhis'] : $now
        )
    );

    $msg_count = 0;
    foreach ($messages as $m) {
        $msg_count++;
        $content_text = isset($m['content']) ? $m['content'] : '';
        if (strlen($content_text) > 1000) {
            $content_text = substr($content_text, 0, 997) . '...';
        }
        $from_actor = isset($m['actor_id']) ? (int) $m['actor_id'] : 1;
        $ts = isset($m['timestamp_ymdhis']) ? $m['timestamp_ymdhis'] : (isset($m['created_ymdhis']) ? $m['created_ymdhis'] : $now);

        $mrow = $db->fetchRow("SELECT COALESCE(MAX(dialog_message_id), 0) + 1 AS n FROM {$t_messages}", array());
        $next_msg_id = $mrow && isset($mrow['n']) ? (int) $mrow['n'] : 1;

        $db->query(
            "INSERT INTO {$t_messages} (dialog_message_id, message_id, dialog_thread_id, channel_id, from_actor_id, to_actor_id, read_by_actor_id, read_by_actor_utc, message_text, message_type, created_ymdhis, updated_ymdhis, is_deleted) " .
            "VALUES (:mid, 0, :tid, 42, :from_actor, 0, 0, 0, :msg_text, 'text', :created, :updated, 0)",
            array(
                'mid' => $next_msg_id,
                'tid' => $next_thread_id,
                'from_actor' => $from_actor,
                'msg_text' => $content_text,
                'created' => $ts,
                'updated' => $ts
            )
        );
    }

    echo "  Migrated " . count($messages) . " message(s), thread_id={$next_thread_id}\n";

    if ($do_archive) {
        $rel = substr($file, strlen($threads_dir) + 1);
        $archive_path = $archive_dir . DIRECTORY_SEPARATOR . str_replace(DIRECTORY_SEPARATOR, '_', $rel);
        if (file_exists($archive_path)) {
            @unlink($archive_path);
        }
        rename($file, $archive_path);
    }
}

echo "Migration complete.\n";
