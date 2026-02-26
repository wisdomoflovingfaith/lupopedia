#!/usr/bin/env php
<?php
// VERSION: 4.0.46

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(dirname(__FILE__)) . '/');
}
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', ABSPATH);
}
require_once ABSPATH . 'lupopedia-config.php';

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db) {
    $db = DatabaseFactory::getConnection();
}
if (!$db) {
    die("Error: Database connection failed.\n");
}

$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$state_file = ABSPATH . '.lupo_actor';

function get_local_actor()
{
    global $state_file;
    if (file_exists($state_file)) {
        return json_decode(file_get_contents($state_file), true);
    }
    return null;
}
function save_local_actor($actor_id, $name)
{
    global $state_file;
    file_put_contents($state_file, json_encode(array('actor_id' => $actor_id, 'name' => $name)));
}

$argv = isset($GLOBALS['argv']) ? $GLOBALS['argv'] : array();
$command = isset($argv[1]) ? $argv[1] : 'help';

try {
    switch ($command) {
        case 'register':
            $name = isset($argv[2]) ? trim($argv[2]) : '';
            $type = isset($argv[3]) ? trim($argv[3]) : 'system_tool';
            if ($name === '')
                die("Error: Name is required.\n");
            $t = $table_prefix . 'actors';
            $stmt = $db->prepare("SELECT actor_id FROM {$t} WHERE name = :name AND actor_type = :type AND is_deleted = 0 LIMIT 1");
            $stmt->execute(array('name' => $name, 'type' => $type));
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($existing) {
                $actor_id = (int) $existing['actor_id'];
                echo "Actor already exists with ID: $actor_id\n";
            } else {
                $min_id = ($type === 'human' || $type === 'user') ? 10000 : 1000;
                $actor_id = function_exists('lupo_findpuka') ? lupo_findpuka($db, $t, 'actor_id', $min_id) : null;
                if ($actor_id === null) {
                    $stmt = $db->prepare("SELECT MAX(actor_id) as max_id FROM {$t}");
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $actor_id = $row['max_id'] ? (int) $row['max_id'] + 1 : $min_id;
                }
                $now = (int) gmdate('YmdHis');
                $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
                $stmt = $db->prepare("INSERT INTO {$t} (actor_id, name, actor_type, slug, is_active, is_deleted, created_ymdhis, updated_ymdhis) VALUES (:id, :name, :type, :slug, 1, 0, :created, :updated)");
                $stmt->execute(array('id' => $actor_id, 'name' => $name, 'type' => $type, 'slug' => $slug, 'created' => $now, 'updated' => $now));
                echo "Registered new actor: $name (ID: $actor_id)\n";
            }
            save_local_actor($actor_id, $name);
            echo "Local identity saved to .lupo_actor\n";
            break;
        case 'whoami':
            $actor = get_local_actor();
            if (!$actor)
                echo "Not registered.\n";
            else
                echo "Current Actor: " . $actor['name'] . " (ID: " . $actor['actor_id'] . ")\n";
            break;
        case 'actors':
            $type = isset($argv[2]) ? trim($argv[2]) : '';
            $t = $table_prefix . 'actors';
            $sql = "SELECT actor_id, name, actor_type FROM {$t} WHERE is_deleted = 0";
            $params = array();
            if ($type !== '') {
                $sql .= " AND actor_type = :type";
                $params['type'] = $type;
            }
            $sql .= " ORDER BY actor_id ASC";
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "Registered Actors:\n";
            foreach ($rows as $r) {
                echo "  [" . $r['actor_id'] . "] " . $r['name'] . " (" . $r['actor_type'] . ")\n";
            }
            break;
        case 'use':
            $actor_id = isset($argv[2]) ? (int) $argv[2] : 0;
            if ($actor_id <= 0)
                die("Error: Invalid actor_id.\n");
            $t = $table_prefix . 'actors';
            $stmt = $db->prepare("SELECT name FROM {$t} WHERE actor_id = :id AND is_deleted = 0");
            $stmt->execute(array('id' => $actor_id));
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$res)
                die("Error: Actor not found.\n");
            save_local_actor($actor_id, $res['name']);
            echo "Now acting as: " . $res['name'] . " (ID: $actor_id)\n";
            break;
        case 'channels':
            $t = $table_prefix . 'channels';
            $stmt = $db->prepare("SELECT channel_id, channel_name, channel_key FROM {$t} WHERE is_deleted = 0 ORDER BY channel_id ASC");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "Available Channels:\n";
            foreach ($rows as $r)
                echo "  [" . $r['channel_id'] . "] " . $r['channel_name'] . " (" . $r['channel_key'] . ")\n";
            break;
        case 'join':
            $actor = get_local_actor();
            if (!$actor)
                die("Error: Not registered.\n");
            $channel_id = isset($argv[2]) && $argv[2] !== '' ? (int) $argv[2] : -1;
            if ($channel_id < 0)
                die("Error: Invalid channel_id.\n");
            $t = $table_prefix . 'actor_channels';
            $now = (int) gmdate('YmdHis');
            $stmt = $db->prepare("SELECT actor_channel_id FROM {$t} WHERE actor_id = :aid AND channel_id = :cid AND is_deleted = 0");
            $stmt->execute(array('aid' => $actor['actor_id'], 'cid' => $channel_id));
            if ($stmt->fetch())
                echo "Already joined channel $channel_id\n";
            else {
                $id = function_exists('lupo_findpuka') ? lupo_findpuka($db, $t, 'actor_channel_id', 1) : null;
                $stmt = $db->prepare("INSERT INTO {$t} (actor_channel_id, actor_id, channel_id, status, created_ymdhis, updated_ymdhis) VALUES (:id, :aid, :cid, 'A', :created, :updated)");
                $stmt->execute(array('id' => $id, 'aid' => $actor['actor_id'], 'cid' => $channel_id, 'created' => $now, 'updated' => $now));
                echo "Joined channel $channel_id\n";
            }
            break;

        case 'threads':
            $channel_id = isset($argv[2]) && $argv[2] !== '' ? (int) $argv[2] : -1;
            if ($channel_id < 0)
                die("Error: Invalid channel_id.\n");

            $t = $table_prefix . 'dialog_threads';
            $stmt = $db->prepare("SELECT dialog_thread_id, task_name, summary_text, status FROM {$t} WHERE channel_id = :cid AND is_deleted = 0 ORDER BY updated_ymdhis DESC");
            $stmt->execute(array('cid' => $channel_id));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "Threads in Channel $channel_id:\n";
            if (empty($rows))
                echo "  (No threads found)\n";
            foreach ($rows as $r) {
                $name = $r['task_name'] ? $r['task_name'] : ($r['summary_text'] ? substr($r['summary_text'], 0, 30) . '...' : 'Unnamed Thread');
                echo "  [" . $r['dialog_thread_id'] . "] " . $name . " (Status: " . $r['status'] . ")\n";
            }
            break;
        case 'messages':
            $channel_id = isset($argv[2]) && $argv[2] !== '' ? (int) $argv[2] : -1;
            $thread_id = isset($argv[3]) ? (int) $argv[3] : 0;
            if ($channel_id < 0)
                die("Error: Invalid channel_id.\n");

            $t_msg = $table_prefix . 'dialog_doctrine';
            $t_act = $table_prefix . 'actors';
            $sql = "SELECT m.message_text, m.created_ymdhis, a.name as actor_name, m.dialog_thread_id 
                    FROM {$t_msg} m 
                    LEFT JOIN {$t_act} a ON a.actor_id = m.from_actor_id 
                    WHERE m.channel_id = :cid AND m.is_deleted = 0";
            $params = array('cid' => $channel_id);

            if ($thread_id > 0) {
                $sql .= " AND m.dialog_thread_id = :tid";
                $params['tid'] = $thread_id;
            }

            $sql .= " ORDER BY m.created_ymdhis DESC LIMIT 20";

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $rows = array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));

            if ($thread_id > 0) {
                echo "Recent messages in Channel $channel_id, Thread $thread_id:\n";
            } else {
                echo "Recent messages in Channel $channel_id (Broadcast):\n";
            }

            if (empty($rows))
                echo "  (No messages found)\n";
            foreach ($rows as $r) {
                $time = substr($r['created_ymdhis'], 0, 4) . "-" . substr($r['created_ymdhis'], 4, 2) . "-" . substr($r['created_ymdhis'], 6, 2) . " " . substr($r['created_ymdhis'], 8, 2) . ":" . substr($r['created_ymdhis'], 10, 2);
                $actor_name = $r['actor_name'] ? $r['actor_name'] : 'System/Legacy';
                $thread_info = ($thread_id == 0 && $r['dialog_thread_id'] > 0) ? " [T:" . $r['dialog_thread_id'] . "]" : "";
                echo "[$time]$thread_info <" . $actor_name . "> " . $r['message_text'] . "\n";
            }
            break;
        case 'send':
            $actor = get_local_actor();
            if (!$actor)
                die("Error: Not registered.\n");
            $channel_id = isset($argv[2]) && $argv[2] !== '' ? (int) $argv[2] : -1;
            $text = isset($argv[3]) ? trim($argv[3]) : '';
            $thread_id = isset($argv[4]) ? (int) $argv[4] : 0;

            if ($channel_id < 0 || $text === '')
                die("Error: Invalid input. Usage: send <channel_id> <msg> [thread_id]\n");

            $t_msg = $table_prefix . 'dialog_doctrine';
            $now = (int) gmdate('YmdHis');
            $id = function_exists('lupo_findpuka') ? lupo_findpuka($db, $t_msg, 'dialog_message_id', 1) : null;
            if ($id === null) {
                $stmt = $db->prepare("SELECT MAX(dialog_message_id) FROM {$t_msg}");
                $stmt->execute();
                $id = (int) $stmt->fetchColumn() + 1;
            }
            $stmt = $db->prepare("INSERT INTO {$t_msg} (dialog_message_id, channel_id, from_actor_id, message_text, message_type, dialog_thread_id, created_ymdhis, updated_ymdhis) VALUES (:id, :cid, :aid, :txt, 'text', :tid, :created, :updated)");
            $stmt->execute(array('id' => $id, 'cid' => $channel_id, 'aid' => $actor['actor_id'], 'txt' => $text, 'tid' => $thread_id, 'created' => $now, 'updated' => $now));
            echo "Message sent (ID: $id, Thread: $thread_id)\n";
            break;
        case 'nodes':
            $t = $table_prefix . 'federation_nodes';
            $stmt = $db->prepare("SELECT federation_node_id, node_name, node_base_url FROM {$t} WHERE is_deleted = 0 ORDER BY federation_node_id ASC");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "Federation Nodes:\n";
            foreach ($rows as $r) {
                echo "  [" . $r['federation_node_id'] . "] " . $r['node_name'] . " (" . $r['node_base_url'] . ")\n";
            }
            break;
        case 'artifacts':
            $node_id = isset($argv[2]) ? (int) $argv[2] : 1;
            $t = $table_prefix . 'artifacts';
            $stmt = $db->prepare("SELECT artifact_id, entity_type, created_ymdhis FROM {$t} WHERE federation_node_id = :nid AND is_deleted = 0 ORDER BY created_ymdhis DESC LIMIT 50");
            $stmt->execute(array('nid' => $node_id));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "Artifacts for Node $node_id:\n";
            if (empty($rows))
                echo "  (No artifacts found)\n";
            foreach ($rows as $r) {
                echo "  [" . $r['artifact_id'] . "] " . $r['entity_type'] . " (Created: " . $r['created_ymdhis'] . ")\n";
            }
            break;
        case 'tasks':
            $actor = get_local_actor();
            if (!$actor)
                die("Error: Not registered.\n");
            $t = $table_prefix . 'tasks';
            $stmt = $db->prepare("SELECT task_id, title, status_id FROM {$t} WHERE owner_actor_id = :aid AND is_deleted = 0 ORDER BY created_ymdhis DESC");
            $stmt->execute(array('aid' => $actor['actor_id']));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "Your Tasks:\n";
            if (empty($rows))
                echo "  (None)\n";
            foreach ($rows as $r)
                echo "  [" . $r['task_id'] . "] " . $r['title'] . " (Status: " . $r['status_id'] . ")\n";
            break;
        case 'help':
        default:
            echo "Lupopedia CLI v4.0.46\n";
            echo "Usage: php bin/lupo.php <command> [args]\n\n";
            echo "Commands:\n";
            echo "  register <name> <type>             Register this environment as an actor\n";
            echo "  whoami                             Show current actor identity\n";
            echo "  actors [type]                      List registered actors\n";
            echo "  use <actor_id>                     Switch local identity to an existing actor\n";
            echo "  channels                           List available channels\n";
            echo "  threads <channel_id>               List threads in a channel\n";
            echo "  join <channel_id>                  Join a channel\n";
            echo "  messages <channel_id> [thread_id]  List last 20 messages in a channel/thread\n";
            echo "  send <channel_id> <msg> [thread_id] Send a message to a channel/thread\n";
            echo "  nodes                              List federation nodes\n";
            echo "  artifacts <node_id>                List artifacts by federation node\n";
            echo "  tasks                              List your active tasks\n";
            break;
    }
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
