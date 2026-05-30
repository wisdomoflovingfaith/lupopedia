<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "lupo-bin/agent_poll_tasks.php"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-bin/agent_poll_tasks.php"
#   status: "active"
#   when_updated: "20260416130413"
#   trust_tier: "staging"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/staging/2026/04/agent-poll-tasks.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/agent-poll-tasks"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "82"
#   content_slug: "agent-poll-tasks"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Agent Poll Tasks CLI"
#   summary: "Polls HERMES-routed pending tasks from lupo_dialog_pending_tasks and optionally claims them."
# ---------------------------------------------------------------------
if (php_sapi_name() !== 'cli') {
    echo "CLI only.\n";
    exit(1);
}

$root = dirname(__DIR__);
define('LUPOPEDIA_PATH', $root);
define('LUPOPEDIA_PUBLIC_PATH', '/' . basename($root));

require_once LUPOPEDIA_PATH . '/lupo-includes/classes/LupopediaConfigResolver.php';
$cfg = LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, LUPOPEDIA_PUBLIC_PATH);
define('LUPOPEDIA_CONFIG_PATH', $cfg ?: LUPOPEDIA_PATH . '/lupopedia-config.php');
require_once LUPOPEDIA_CONFIG_PATH;

require_once LUPOPEDIA_PATH . '/lupo-includes/classes/DatabaseFactory.php';
require_once LUPOPEDIA_PATH . '/lupo-includes/classes/TimestampYmdhis.php';

if ($argc < 2) {
    echo "Usage: php lupo-bin/agent_poll_tasks.php <actor_id> [channel_id] [--claim]\n";
    exit(1);
}

$actor_id = (int) $argv[1];
$channel_id = ($argc >= 3 && strpos((string) $argv[2], '--') !== 0) ? (int) $argv[2] : 0;
$claim = in_array('--claim', $argv, true);

if ($actor_id <= 0) {
    echo "Error: actor_id must be > 0\n";
    exit(1);
}

$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$table = $prefix . 'dialog_pending_tasks';

$sql = "SELECT task_id, message_id, channel_id, assignee_actor_id, creator_actor_id, task_body, status, priority, created_ymdhis, updated_ymdhis, completed_ymdhis
        FROM {$table}
        WHERE assignee_actor_id = :assignee_actor_id
          AND status = :status";
$params = array(
    'assignee_actor_id' => $actor_id,
    'status' => 'pending'
);

if ($channel_id > 0) {
    $sql .= " AND channel_id = :channel_id";
    $params['channel_id'] = $channel_id;
}

$sql .= " ORDER BY created_ymdhis ASC";
$tasks = $db->fetchAll($sql, $params);

if (!is_array($tasks)) {
    $tasks = array();
}

$now = (int) timestamp_ymdhis::now();
if ($claim && count($tasks) > 0) {
    foreach ($tasks as $t) {
        $db->execute(
            "UPDATE {$table}
             SET status = :new_status, updated_ymdhis = :updated_ymdhis
             WHERE task_id = :task_id AND status = :old_status",
            array(
                'new_status' => 'in_progress',
                'updated_ymdhis' => $now,
                'task_id' => (int) $t['task_id'],
                'old_status' => 'pending'
            )
        );
    }
    foreach ($tasks as $idx => $t) {
        $tasks[$idx]['status'] = 'in_progress';
        $tasks[$idx]['updated_ymdhis'] = $now;
    }
}

$out = array(
    'ok' => true,
    'actor_id' => $actor_id,
    'channel_id' => $channel_id,
    'claimed' => $claim ? true : false,
    'count' => count($tasks),
    'tasks' => $tasks
);

echo json_encode($out, JSON_UNESCAPED_SLASHES) . "\n";

