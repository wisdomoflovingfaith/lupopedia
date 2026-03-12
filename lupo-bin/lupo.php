#!/usr/bin/env php
<?php
// VERSION: 4.0.68

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(dirname(__FILE__)) . '/');
}
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', ABSPATH);
}
require_once ABSPATH . 'lupopedia-config.php';

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db && class_exists('DatabaseFactory')) {
    try {
        $db = DatabaseFactory::getConnection();
    } catch (Exception $e) {
        $db = null;
    }
}

$argv = isset($GLOBALS['argv']) ? $GLOBALS['argv'] : array();
$command = isset($argv[1]) ? $argv[1] : 'help';
$need_db = ($command !== 'whoami' && $command !== 'context' && $command !== 'help' && $command !== 'docs' && $command !== 'version' && $command !== 'doctor' && $command !== 'doctor-context' && $command !== 'auth' && $command !== 'who' && $command !== 'actor-context' && $command !== 'skills');
if (!$db && $need_db) {
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

try {
    switch ($command) {
        case 'context':
            $whoami_verbose = true;
        case 'whoami':
            require_once ABSPATH . 'lupo-includes/classes/ContextKernel.php';
            require_once ABSPATH . 'lupo-includes/classes/DialogHeaderValidator.php';
            $kernel = ContextKernel::getInstance();
            $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
            $kernel->bootstrap($db, $table_prefix, $state_file, ABSPATH, $authService);
            $ctx = $kernel->getContext();
            $system_version = function_exists('get_lupo_version') ? get_lupo_version() : (defined('LUPOPEDIA_VERSION') ? LUPOPEDIA_VERSION : '4.0.72');
            lupo_validate_flare_headers($ctx, $system_version);
            DialogHeaderValidator::validate($ctx);
            $verbose = isset($whoami_verbose) ? $whoami_verbose : false;
            if (!$verbose && isset($argv[2]) && $argv[2] === '--verbose') {
                $verbose = true;
            }
            if ($verbose) {
                $json = array(
                    'actor_name' => $ctx['actor_name'],
                    'actor_id' => isset($ctx['actor_id']) ? (int) $ctx['actor_id'] : 0,
                    'actor_type' => isset($ctx['actor_type']) ? $ctx['actor_type'] : 'system',
                    'actor_nature' => isset($ctx['actor_nature']) ? $ctx['actor_nature'] : 'system',
                    'agent_name' => isset($ctx['agent_name']) ? $ctx['agent_name'] : 'none',
                    'human_actor_name' => isset($ctx['human_actor_name']) ? $ctx['human_actor_name'] : 'none',
                    'human_actor_id' => isset($ctx['human_actor_id']) ? (int) $ctx['human_actor_id'] : 0,
                    'paired_actor_id' => isset($ctx['paired_actor_id']) ? (int) $ctx['paired_actor_id'] : 0,
                    'paired_actor_name' => isset($ctx['paired_actor_name']) && $ctx['paired_actor_name'] !== '' ? $ctx['paired_actor_name'] : '',
                    'session_mode' => isset($ctx['session_mode']) ? $ctx['session_mode'] : 'system',
                    'department_id' => isset($ctx['department_id']) ? (int) $ctx['department_id'] : 0,
                    'channel_id' => (int) $ctx['channel_id'],
                    'thread_id' => isset($ctx['thread_id']) ? (int) $ctx['thread_id'] : 0,
                    'federation_node_id' => (int) $ctx['federation_node_id'],
                    'workspace' => $ctx['workspace'],
                    'session_id' => $ctx['session_id'],
                    'context_source' => isset($ctx['context_source']) ? $ctx['context_source'] : $ctx['source']
                );
                echo json_encode($json, JSON_UNESCAPED_SLASHES) . "\n";
            } else {
                $human_id = isset($ctx['human_actor_name']) ? $ctx['human_actor_name'] : 'none';
                $human_actor_id = isset($ctx['human_actor_id']) ? (int) $ctx['human_actor_id'] : 0;
                $agent_name = isset($ctx['agent_name']) ? $ctx['agent_name'] : 'none';
                $agent_id = ($agent_name !== 'none') ? (int) $ctx['actor_id'] : 0;
                echo "Human Identity: " . $human_id . ($human_actor_id > 0 ? " (" . $human_actor_id . ")" : "") . "\n";
                echo "Active Agent: " . $agent_name . ($agent_id > 0 ? " (" . $agent_id . ")" : "") . "\n\n";
                echo "Session Mode: " . (isset($ctx['session_mode']) ? $ctx['session_mode'] : 'system') . "\n";
                echo "Actor Type: " . (isset($ctx['actor_type']) ? $ctx['actor_type'] : 'system') . "\n\n";
                echo "Department: " . (isset($ctx['department_id']) ? (int) $ctx['department_id'] : 0) . "\n";
                echo "Channel: " . (int) $ctx['channel_id'] . "\n";
                echo "Thread: " . (isset($ctx['thread_id']) ? (int) $ctx['thread_id'] : 0) . "\n";
                echo "Federation Node: " . (int) $ctx['federation_node_id'] . "\n\n";
                echo "Workspace:\n" . $ctx['workspace'] . "\n\n";
                echo "Session:\n" . ($ctx['session_id'] !== '' ? $ctx['session_id'] : '(none)') . "\n\n";
                echo "Context Source:\n" . (isset($ctx['context_source']) ? $ctx['context_source'] : $ctx['source']) . "\n";
            }
            $session_md_path = ABSPATH . (defined('LUPO_DATABASE_DIR') ? LUPO_DATABASE_DIR : 'lupo-database') . '/session.md';
            $issues = $kernel->validate($db, $table_prefix, $session_md_path);
            if (!empty($issues)) {
                echo "\nKERNEL ISSUE:\n";
                foreach ($issues as $issue) {
                    echo "  * " . $issue . "\n";
                }
                echo "  Run: php lupo-bin/lupo.php doctor-context [--repair]\n";
            }
            break;
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
                $min_id = ($type === 'human' || $type === 'user') ? 1000 : 100;
                $actor_id = function_exists('lupo_findpuka') ? lupo_findpuka($db, $t, 'actor_id', $min_id) : null;
                if ($actor_id === null) {
                    $stmt = $db->prepare("SELECT MAX(actor_id) as max_id FROM {$t}");
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $actor_id = $row['max_id'] ? (int) $row['max_id'] + 1 : $min_id;
                }
                $now = (int) gmdate('YmdHis');
                $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
                $stmt = $db->prepare("INSERT INTO {$t} (actor_id, actor_name, name, actor_type, slug, is_active, is_deleted, created_ymdhis, updated_ymdhis) VALUES (:id, :name, :name, :type, :slug, 1, 0, :created, :updated)");
                $stmt->execute(array('id' => $actor_id, 'name' => $name, 'type' => $type, 'slug' => $slug, 'created' => $now, 'updated' => $now));
                echo "Registered new actor: $name (ID: $actor_id)\n";
            }
            save_local_actor($actor_id, $name);
            echo "Local identity saved to .lupo_actor\n";
            break;
        case 'actors':
            $type = isset($argv[2]) ? trim($argv[2]) : '';
            $t = $table_prefix . 'actors';
            $sql = "SELECT actor_id, actor_name, name, actor_type FROM {$t} WHERE is_deleted = 0";
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
                $disp = isset($r['actor_name']) ? $r['actor_name'] : $r['name'];
                echo "  [" . $r['actor_id'] . "] " . $disp . " (" . $r['actor_type'] . ")\n";
            }
            break;
        case 'switch':
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

            $t_msg = $table_prefix . 'dialog_messages';
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

            $t_msg = $table_prefix . 'dialog_messages';
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
            $stmt = $db->prepare("SELECT task_id, title, task_status FROM {$t} WHERE owner_actor_id = :aid AND is_deleted = 0 ORDER BY created_ymdhis DESC");
            $stmt->execute(array('aid' => $actor['actor_id']));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "Your Tasks:\n";
            if (empty($rows))
                echo "  (None)\n";
            foreach ($rows as $r)
                echo "  [" . $r['task_id'] . "] " . $r['title'] . " (Status: " . (isset($r['task_status']) ? $r['task_status'] : '—') . ")\n";
            break;
        case 'system-status':
            // System Agent command: get_system_status()
            $actor = get_local_actor();
            if (!$actor || $actor['actor_id'] != 0) {
                die("Error: Unauthorized access - System Agent identity required.\n");
            }
            echo "=== System Status ===\n";
            echo "Lupopedia Version: " . (defined('LUPOPEDIA_VERSION') ? LUPOPEDIA_VERSION : 'Unknown') . "\n";
            echo "Database: " . get_class($db) . "\n";
            echo "Table Prefix: " . $table_prefix . "\n";
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM {$table_prefix}actors WHERE is_deleted = 0");
            $stmt->execute();
            $actor_count = $stmt->fetchColumn();
            echo "Active Actors: " . $actor_count . "\n";
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM {$table_prefix}channels WHERE is_deleted = 0");
            $stmt->execute();
            $channel_count = $stmt->fetchColumn();
            echo "Active Channels: " . $channel_count . "\n";
            echo "System Path: " . ABSPATH . "\n";
            echo "Config Path: " . ABSPATH . 'lupopedia-config.php' . "\n";
            echo "Timestamp: " . gmdate('Y-m-d H:i:s UTC') . "\n";
            echo "===================\n";

            // Log operation
            logOperation(0, 'system-status', array('timestamp' => gmdate('YmdHis')));
            break;
        case 'coordinate-task':
            // System Agent command: coordinate_task(task_id, parameters)
            $actor = get_local_actor();
            if (!$actor || $actor['actor_id'] != 0) {
                die("Error: Unauthorized access - System Agent identity required.\n");
            }
            $task_id = isset($argv[2]) ? (int) $argv[2] : 0;
            if ($task_id <= 0) {
                die("Error: Task ID required. Usage: coordinate-task <task_id>\n");
            }

            // Additional validation: range check for positive integers
            if ($task_id > 999999) {
                die("Error: Task ID out of valid range (1-999999).\n");
            }

            $t = $table_prefix . 'tasks';
            $stmt = $db->prepare("SELECT task_id, title, status_id FROM {$t} WHERE task_id = :tid AND is_deleted = 0");
            $stmt->execute(array('tid' => $task_id));
            $task = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$task) {
                die("Error: Task not found.\n");
            }
            echo "=== Task Coordination ===\n";
            echo "Task ID: " . $task['task_id'] . "\n";
            echo "Title: " . $task['title'] . "\n";
            echo "Status: " . $task['status_id'] . "\n";
            echo "Coordinator: System Agent (ID: 0)\n";
            echo "Action: Task coordination initiated\n";
            echo "Timestamp: " . gmdate('Y-m-d H:i:s UTC') . "\n";
            echo "========================\n";

            // Log operation
            logOperation(0, 'coordinate-task', array('task_id' => $task_id, 'title' => $task['title']));
            break;
        case 'health-check':
            // System Agent command: health_check()
            $actor = get_local_actor();
            if (!$actor || $actor['actor_id'] != 0) {
                die("Error: Unauthorized access - System Agent identity required.\n");
            }
            echo "=== System Health Check ===\n";
            $checks = array();

            // Database connectivity
            $checks['database'] = $db ? 'PASS' : 'FAIL';

            // Configuration file
            $checks['config'] = file_exists(ABSPATH . 'lupopedia-config.php') ? 'PASS' : 'FAIL';

            // Version file
            $checks['version'] = file_exists(ABSPATH . 'lupo-includes/version.php') ? 'PASS' : 'FAIL';

            // Write permissions
            $checks['writable'] = is_writable(ABSPATH) ? 'PASS' : 'FAIL';

            // Database table access
            try {
                $stmt = $db->prepare("SELECT 1 FROM {$table_prefix}actors LIMIT 1");
                $stmt->execute();
                $checks['table_access'] = 'PASS';
            } catch (Exception $e) {
                $checks['table_access'] = 'FAIL';
            }

            foreach ($checks as $check => $status) {
                echo strtoupper($check) . ": " . $status . "\n";
            }

            $all_pass = array_reduce($checks, function ($carry, $item) {
                return $carry && $item === 'PASS';
            }, true);
            echo "Overall Status: " . ($all_pass ? 'HEALTHY' : 'ISSUES DETECTED') . "\n";
            echo "Timestamp: " . gmdate('Y-m-d H:i:s UTC') . "\n";
            echo "========================\n";

            // Log operation
            logOperation(0, 'health-check', array('results' => $checks, 'overall' => $all_pass ? 'HEALTHY' : 'ISSUES DETECTED'));
            break;
        case 'rules':
            require_once ABSPATH . 'lupo-includes/classes/RuleEvaluator.php';
            $evaluator = new RuleEvaluator($db);
            $sub = isset($argv[2]) ? $argv[2] : '';
            $target_table = isset($argv[3]) ? $argv[3] : 'channels';
            $target_id = isset($argv[4]) ? (int) $argv[4] : 42;
            if ($sub === '--check') {
                $rules = $evaluator->getRulesForTarget($target_table, $target_id);
                echo "Rules for {$target_table}:{$target_id}\n";
                if (empty($rules)) {
                    echo "  (none)\n";
                } else {
                    foreach ($rules as $r) {
                        $p = isset($r['priority']) ? $r['priority'] : 0;
                        echo "  - " . (isset($r['rule_name']) ? $r['rule_name'] : $r['rule_id']) . " (priority {$p})\n";
                    }
                }
            } elseif ($sub === '--evaluate') {
                $context = array();
                if (isset($argv[5]) && $argv[5] !== '') {
                    $decoded = json_decode($argv[5], true);
                    if (is_array($decoded)) {
                        $context = $decoded;
                    }
                }
                $results = $evaluator->evaluateRules($target_table, $target_id, $context);
                echo "Evaluation results:\n";
                foreach ($results as $i => $r) {
                    if ($i === 'schema' || $i === 'information_schema') {
                        continue;
                    }
                    $pass = isset($r['passed']) && $r['passed'] ? 'PASS' : 'FAIL';
                    $note = isset($r['note']) ? $r['note'] : (isset($r['error']) ? $r['error'] : '');
                    echo "  [{$i}] {$pass}" . ($note !== '' ? " - {$note}" : '') . "\n";
                }
                if (isset($results['schema'])) {
                    $s = $results['schema'];
                    echo "  [schema] foreign_keys: " . (isset($s['foreign_keys']['passed']) && $s['foreign_keys']['passed'] ? 'PASS' : 'FAIL') . (isset($s['foreign_keys']['violations']) && !empty($s['foreign_keys']['violations']) ? ' (' . implode(', ', array_slice($s['foreign_keys']['violations'], 0, 5)) . ')' : '') . "\n";
                    echo "  [schema] triggers: " . (isset($s['triggers']['passed']) && $s['triggers']['passed'] ? 'PASS' : 'FAIL') . " (count=" . (isset($s['triggers']['count']) ? $s['triggers']['count'] : 0) . ")\n";
                    echo "  [schema] timestamps: " . (isset($s['timestamps']['passed']) && $s['timestamps']['passed'] ? 'PASS' : 'FAIL') . "\n";
                    echo "  [schema] auto_increment: " . (isset($s['auto_increment']['passed']) && $s['auto_increment']['passed'] ? 'PASS' : 'INFO (allowed)') . "\n";
                }
                if (isset($results['information_schema'])) {
                    $is = $results['information_schema'];
                    $pass = isset($is['passed']) && $is['passed'] ? 'PASS' : 'FAIL';
                    echo "  [information_schema] {$pass}" . (isset($is['violations']) && !empty($is['violations']) ? ' - violations: ' . implode(', ', array_slice($is['violations'], 0, 10)) : '') . "\n";
                }
            } else {
                echo "Usage: php lupo.php rules --check [target_table] [target_id]\n";
                echo "       php lupo.php rules --evaluate [target_table] [target_id] [context_json]\n";
                echo "Example: php lupo.php rules --check channels 42\n";
            }
            break;
        case 'skills':
            require_once ABSPATH . 'lupo-includes/classes/SkillService.php';
            $skillService = new SkillService($db);
            $sub = isset($argv[2]) ? $argv[2] : '';
            if ($sub === '--actor') {
                $actor_id = isset($argv[3]) ? (int) $argv[3] : 1;
                $skills = $skillService->getActorSkills($actor_id);
                echo "Skills for Actor {$actor_id}:\n";
                if (empty($skills)) {
                    echo "  (none)\n";
                } else {
                    foreach ($skills as $s) {
                        $proficiency = isset($s['proficiency']) ? " (" . $s['proficiency'] . ")" : '';
                        echo "  - " . (isset($s['name']) ? $s['name'] : '') . $proficiency . "\n";
                    }
                }
            } elseif ($sub === '--check') {
                $actor_id = isset($argv[3]) ? (int) $argv[3] : 1;
                $skill_name = isset($argv[4]) ? $argv[4] : '';
                $min_proficiency = isset($argv[5]) && $argv[5] !== '' ? $argv[5] : null;
                if ($skill_name === '') {
                    echo "Usage: php lupo.php skills --check [actor_id] <skill_name> [min_proficiency]\n";
                    break;
                }
                $has = $skillService->hasSkill($actor_id, $skill_name, $min_proficiency);
                echo $has ? "Actor has skill\n" : "Actor does not have skill\n";
            } else {
                echo "Usage: php lupo.php skills --actor [actor_id]\n";
                echo "       php lupo.php skills --check [actor_id] <skill_name> [min_proficiency]\n";
                echo "Example: php lupo.php skills --actor 1\n";
                echo "         php lupo.php skills --check 1 lupopedia-headers expert\n";
            }
            break;
        case 'update-config':
            // System Agent command: update_configuration(config_data)
            $actor = get_local_actor();
            if (!$actor || $actor['actor_id'] != 0) {
                die("Error: Unauthorized access - System Agent identity required.\n");
            }
            $config_key = isset($argv[2]) ? trim($argv[2]) : '';
            $config_value = isset($argv[3]) ? trim($argv[3]) : '';
            if ($config_key === '' || $config_value === '') {
                die("Error: Key and value required. Usage: update-config <key> <value>\n");
            }

            // Validate config key format
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $config_key)) {
                die("Error: Invalid config key format. Use alphanumeric and underscore only.\n");
            }

            $t = $table_prefix . 'system_config';
            $now = (int) gmdate('YmdHis');

            try {
                $db->beginTransaction();

                $stmt = $db->prepare("SELECT config_id FROM {$t} WHERE config_key = :key AND is_deleted = 0");
                $stmt->execute(array('key' => $config_key));
                $existing = $stmt->fetchColumn();

                if ($existing) {
                    $stmt = $db->prepare("UPDATE {$t} SET config_value = :value, updated_ymdhis = :updated WHERE config_key = :key");
                    $stmt->execute(array('value' => $config_value, 'updated' => $now, 'key' => $config_key));
                    echo "Configuration updated: $config_key = $config_value\n";
                } else {
                    $id = function_exists('lupo_findpuka') ? lupo_findpuka($db, $t, 'config_id', 1) : null;
                    if ($id === null) {
                        $stmt = $db->prepare("SELECT MAX(config_id) FROM {$t}");
                        $stmt->execute();
                        $id = (int) $stmt->fetchColumn() + 1;
                    }
                    $stmt = $db->prepare("INSERT INTO {$t} (config_id, config_key, config_value, created_ymdhis, updated_ymdhis) VALUES (:id, :key, :value, :created, :updated)");
                    $stmt->execute(array('id' => $id, 'key' => $config_key, 'value' => $config_value, 'created' => $now, 'updated' => $now));
                    echo "Configuration created: $config_key = $config_value\n";
                }

                $db->commit();

                // Log operation
                logOperation(0, 'update-config', array('key' => $config_key, 'value' => $config_value, 'action' => $existing ? 'update' : 'create'));

            } catch (Exception $e) {
                $db->rollBack();
                echo "Error: Configuration update failed - " . $e->getMessage() . "\n";
            }
            break;
        case 'see':
            $url = isset($argv[2]) ? $argv[2] : '';
            if (!$url) {
                die("Error: URL or path required. Usage: see <url_or_path> [--cat|--open|--json|--reindex]\n");
            }
            $flags = "";
            for ($i = 3; $i < count($argv); $i++) {
                $flags .= " " . escapeshellarg($argv[$i]);
            }
            $is_windows = (DIRECTORY_SEPARATOR === '\\');
            $python = $is_windows ? 'python' : 'python3';
            $script = escapeshellarg(ABSPATH . 'lupo-tools/flare_see.py');
            $command = "$python $script " . escapeshellarg($url) . $flags;
            passthru($command, $return_var);
            exit($return_var);
            break;
        case 'version':
            $ver = function_exists('get_lupo_version') ? get_lupo_version() : '4.0.72';
            echo "Lupopedia version " . $ver . "\n";
            echo "Documentation: docs/version.md\n";
            break;
        case 'auth':
        case 'who':
            lupo_show_auth_info($db, $table_prefix, $state_file, ABSPATH);
            break;
        case 'actor-context':
            lupo_show_actor_context($db, $table_prefix, $state_file, ABSPATH);
            break;
        case 'doctor':
            $doctor_script = ABSPATH . 'lupo-agents/doctor/doctor.php';
            if (file_exists($doctor_script)) {
                include $doctor_script;
            } else {
                lupo_doctor_health_check(ABSPATH, $db, $table_prefix, $state_file);
            }
            break;
        case 'doctor-context':
            $doctor_context_script = ABSPATH . 'lupo-agents/doctor/doctor-context.php';
            if (file_exists($doctor_context_script)) {
                include $doctor_context_script;
            } else {
                require_once ABSPATH . 'lupo-includes/classes/ContextKernel.php';
                $argv_doc = isset($GLOBALS['argv']) ? $GLOBALS['argv'] : array();
                lupo_doctor_context(ABSPATH, $db, $table_prefix, $state_file, $argv_doc);
            }
            break;
        case 'docs':
            $topic = isset($argv[2]) ? trim($argv[2]) : '';
            $base = ABSPATH . 'docs/';
            if ($topic !== '') {
                $path = $base . $topic . '.md';
                if (file_exists($path)) {
                    echo $path . "\n";
                } else {
                    echo "Documentation not found for: " . $topic . "\n";
                    echo "Hub: " . $base . "HELP.md\n";
                }
            } else {
                echo $base . "HELP.md\n";
            }
            break;
        case 'help':
        default:
            $help_arg = isset($argv[2]) ? trim($argv[2]) : '';
            require_once ABSPATH . 'lupo-includes/classes/HelpRenderer.php';
            $ctx = null;
            if (file_exists(ABSPATH . 'lupo-includes/classes/ContextKernel.php')) {
                require_once ABSPATH . 'lupo-includes/classes/ContextKernel.php';
                $kernel = ContextKernel::getInstance();
                $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
                $ctx = $kernel->bootstrap($db, $table_prefix, $state_file, ABSPATH, $authService);
            }
            $help = new HelpRenderer($ctx);
            if ($help_arg === '--quick') {
                $help->showQuickRef();
            } elseif ($help_arg === '--web') {
                $help->openWebHelp();
            } elseif ($help_arg !== '') {
                if ($help_arg === 'whoami') {
                    lupo_help_whoami();
                } elseif ($help_arg === 'context') {
                    lupo_help_context();
                } else {
                    $help->showTopicHelp($help_arg);
                }
            } else {
                $help->showMainHelp();
            }
            break;
    }
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}

/**
 * Show current authenticated user (for Antigravity / conflict resolution).
 */
function lupo_show_auth_info($db, $table_prefix, $state_file, $abspath)
{
    require_once $abspath . 'lupo-includes/classes/ContextKernel.php';
    require_once $abspath . 'lupo-includes/classes/AntigravityContext.php';

    $kernel = ContextKernel::getInstance();
    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    $kernel->bootstrap($db, $table_prefix, $state_file, $abspath, $authService);

    $ag = new AntigravityContext(null, $authService);
    $auth = $ag->getAuthUser();
    if ($auth && isset($auth['username'])) {
        echo "Authenticated User:\n";
        echo "  Username: " . (isset($auth['username']) ? $auth['username'] : '') . "\n";
        echo "  Display Name: " . (isset($auth['display_name']) ? $auth['display_name'] : '') . "\n";
        echo "  User ID: " . (isset($auth['user_id']) ? $auth['user_id'] : '') . "\n";
        echo "  Email: " . (isset($auth['email']) ? $auth['email'] : '') . "\n";
        echo "  Role: " . (isset($auth['role']) ? $auth['role'] : 'user') . "\n";
    } else {
        echo "Not authenticated.\n";
    }
}

/**
 * Show full actor context with auth (for Antigravity).
 */
function lupo_show_actor_context($db, $table_prefix, $state_file, $abspath)
{
    require_once $abspath . 'lupo-includes/classes/ContextKernel.php';
    require_once $abspath . 'lupo-includes/classes/AntigravityContext.php';

    $kernel = ContextKernel::getInstance();
    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    $kernel->bootstrap($db, $table_prefix, $state_file, $abspath, $authService);

    $ag = new AntigravityContext(null, $authService);
    $actor = $ag->getActor();
    $auth = $ag->getAuthUser();
    echo "Actor Context:\n";
    echo "  Name: " . (isset($actor['name']) ? $actor['name'] : 'none') . "\n";
    echo "  ID: " . (isset($actor['id']) ? $actor['id'] : 'none') . "\n";
    echo "  Type: " . (isset($actor['type']) ? $actor['type'] : 'none') . "\n";
    echo "  Paired: " . (isset($actor['paired_actor_id']) && $actor['paired_actor_id'] > 0 ? $actor['paired_actor_id'] : 'none') . "\n";
    echo "\n";
    echo "Auth Status: " . ($auth ? 'authenticated' : 'not authenticated') . "\n";
    if ($auth && isset($auth['username'])) {
        echo "  As: " . $auth['username'] . "\n";
    }
}

/**
 * System health check: database, registry file, session file, and kernel context validation.
 *
 * @param string $abspath Project root
 * @param object|null $db PDO_DB or null
 * @param string $table_prefix Table prefix (e.g. lupo_)
 * @param string $state_file Path to .lupo_actor
 */
function lupo_doctor_health_check($abspath, $db, $table_prefix = '', $state_file = '')
{
    $db_dir = defined('LUPO_DATABASE_DIR') ? LUPO_DATABASE_DIR : 'lupo-database';
    $registry_path = $abspath . $db_dir . '/lupopedia/actors/registry.json';
    $session_md = $abspath . $db_dir . '/session.md';
    if ($table_prefix === '' && defined('LUPO_TABLE_PREFIX')) {
        $table_prefix = LUPO_TABLE_PREFIX;
    }
    if ($table_prefix === '') {
        $table_prefix = 'lupo_';
    }
    if ($state_file === '') {
        $state_file = $abspath . '.lupo_actor';
    }
    $args = isset($GLOBALS['argv']) ? $GLOBALS['argv'] : array();
    $check_actors = in_array('--check-actors', $args);
    echo "Lupopedia doctor — health check\n";
    echo str_repeat("-", 50) . "\n";
    $ok = 0;
    $fail = 0;
    if ($db) {
        try {
            $db->fetchRow('SELECT 1');
            echo "  [OK] Database connection\n";
            $ok++;
        } catch (Exception $e) {
            echo "  [FAIL] Database: " . $e->getMessage() . "\n";
            $fail++;
        }
    } else {
        echo "  [SKIP] Database not connected (whoami/context still work via session.md)\n";
    }
    if (file_exists($registry_path) && is_readable($registry_path)) {
        echo "  [OK] Registry: " . $registry_path . "\n";
        $ok++;
    } else {
        echo "  [WARN] Registry missing or unreadable: " . $registry_path . "\n";
        $fail++;
    }
    if (file_exists($session_md) && is_readable($session_md)) {
        echo "  [OK] Session file: " . $session_md . "\n";
        $ok++;
    } else {
        echo "  [WARN] Session file missing (optional for CLI fallback): " . $session_md . "\n";
    }
    if (file_exists($abspath . 'lupo-includes/classes/ContextKernel.php')) {
        require_once $abspath . 'lupo-includes/classes/ContextKernel.php';
        $kernel = ContextKernel::getInstance();
        $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
        $kernel->bootstrap($db, $table_prefix, $state_file, $abspath, $authService);
        $issues = $kernel->validate($db, $table_prefix, $session_md);
        if (empty($issues)) {
            echo "  [OK] Context kernel: no identity drift\n";
            $ok++;
        } else {
            echo "  [WARN] Context kernel: " . count($issues) . " issue(s)\n";
            foreach ($issues as $issue) {
                echo "    * " . $issue . "\n";
            }
            echo "  Run: php lupo-bin/lupo.php doctor-context [--repair]\n";
            $fail++;
        }
    }
    if ($check_actors) {
        require_once $abspath . 'lupo-includes/classes/DoctorService.php';
        $kernel = ContextKernel::getInstance();
        $doctor = new DoctorService($kernel, $db, $table_prefix, $state_file, $abspath);
        echo "  [INFO] Checking actors (workspace/namespace consistency)...\n";
        $actor_issues = $doctor->checkActors();
        if (empty($actor_issues)) {
            echo "  [OK] Actors: all passed consistency checks.\n";
            $ok++;
        } else {
            foreach ($actor_issues as $iss) {
                echo "  [FAIL] Actor: " . $iss . "\n";
                $fail++;
            }
        }
    }

    echo str_repeat("-", 50) . "\n";
    echo "Summary: " . $ok . " system(s) OK";
    if ($fail > 0) {
        echo ", " . $fail . " issue(s)";
    }
    echo ".\n";
}

/**
 * Context doctor: validate identity stack via ContextKernel and optionally repair session.md drift.
 * Outputs human-readable OK/WARN/FAIL. See prompts/cursor/20260306_context_doctor.md.
 *
 * @param string $abspath Project root
 * @param object|null $db PDO_DB or null
 * @param string $table_prefix Table prefix (e.g. lupo_)
 * @param string $state_file Path to .lupo_actor
 * @param array $argv CLI argv (for --repair)
 */
function lupo_doctor_context($abspath, $db, $table_prefix, $state_file, $argv = array())
{
    $db_dir = defined('LUPO_DATABASE_DIR') ? LUPO_DATABASE_DIR : 'lupo-database';
    $session_md = $abspath . $db_dir . '/session.md';
    $registry_path = $abspath . $db_dir . '/lupopedia/actors/registry.json';
    $do_repair = in_array('--repair', $argv);

    echo "Lupopedia doctor-context — identity stack check\n";
    if ($do_repair) {
        echo "  (--repair: will sync session.md to kernel/DB when drift detected)\n";
    }
    echo str_repeat("-", 50) . "\n";

    $ok = 0;
    $warn = 0;
    $fail = 0;

    $kernel = ContextKernel::getInstance();
    $authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    $kernel->bootstrap($db, $table_prefix, $state_file, $abspath, $authService);
    $ctx = $kernel->getContext();

    $effective = isset($ctx['actor_name']) ? $ctx['actor_name'] : 'system';
    $human = isset($ctx['human_actor_name']) ? $ctx['human_actor_name'] : 'none';
    $agent = isset($ctx['agent_name']) ? $ctx['agent_name'] : 'none';
    $mode = isset($ctx['session_mode']) ? $ctx['session_mode'] : 'system';
    $src = isset($ctx['context_source']) ? $ctx['context_source'] : (isset($ctx['source']) ? $ctx['source'] : 'default');

    // 1. Session file
    if (file_exists($session_md) && is_readable($session_md)) {
        echo "  [OK] Session file: " . $session_md . "\n";
        $ok++;
    } else {
        echo "  [WARN] Session file missing or unreadable: " . $session_md . "\n";
        $warn++;
    }

    // 2. DB session (if DB available)
    if ($db && $table_prefix !== '') {
        echo "  [OK] DB session: actor_name=" . $effective . ", actor_id=" . (isset($ctx['actor_id']) ? (int) $ctx['actor_id'] : 0) . "\n";
        $ok++;
    } else {
        echo "  [SKIP] Database not connected; DB session check skipped\n";
    }

    // 3. Registry
    if (file_exists($registry_path) && is_readable($registry_path)) {
        $reg_json = json_decode(file_get_contents($registry_path), true);
        if (is_array($reg_json) && isset($reg_json['actors'])) {
            echo "  [OK] Registry: " . $registry_path . " (" . count($reg_json['actors']) . " actors)\n";
            $ok++;
        } else {
            echo "  [WARN] Registry file invalid or empty\n";
            $warn++;
        }
    } else {
        echo "  [WARN] Registry missing or unreadable: " . $registry_path . "\n";
        $warn++;
    }

    // 4. Resolved context (from kernel)
    echo "  [OK] Resolved context: effective=" . $effective . ", human=" . $human . ", agent=" . $agent . ", session_mode=" . $mode . ", source=" . $src . "\n";
    $ok++;

    $issues = $kernel->validate($db, $table_prefix, $session_md);
    if (!empty($issues)) {
        foreach ($issues as $issue) {
            echo "  [WARN] " . $issue . "\n";
            $warn++;
        }
    }
    if (strpos($src, 'conflict') !== false) {
        echo "  [WARN] Session file and DB conflict detected; DB used as canonical\n";
        $warn++;
    }
    if (!empty($ctx['conflicts'])) {
        foreach ($ctx['conflicts'] as $c) {
            $f = isset($c['field']) ? $c['field'] : '?';
            $fv = isset($c['file_value']) ? $c['file_value'] : '?';
            $dv = isset($c['db_value']) ? $c['db_value'] : '?';
            echo "  [CONFLICT] {$f}: session.md={$fv} vs DB={$dv} (" . (isset($c['resolution']) ? $c['resolution'] : 'database_wins') . ")\n";
        }
    }

    $repaired = false;
    if ($do_repair && ($src === 'lupo_sessions (session.md ignored due to conflict)' || strpos($src, 'conflict') !== false || !empty($issues))) {
        $content = "actor_name: " . $effective . "\n";
        $content .= "actor_id: " . (isset($ctx['actor_id']) ? (int) $ctx['actor_id'] : 0) . "\n";
        $content .= "session_id: " . (isset($ctx['session_id']) && $ctx['session_id'] !== '' ? $ctx['session_id'] : '') . "\n";
        $content .= "channel_id: " . (isset($ctx['channel_id']) ? (int) $ctx['channel_id'] : 0) . "\n";
        $content .= "federation_node_id: " . (isset($ctx['federation_node_id']) ? (int) $ctx['federation_node_id'] : 0) . "\n";
        $content .= "context_source: lupo_sessions\n";
        $dir = dirname($session_md);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        if (file_put_contents($session_md, $content) !== false) {
            echo "  [REPAIR] session.md updated to match kernel/DB (" . $session_md . ")\n";
            $repaired = true;
        } else {
            echo "  [FAIL] Could not write session.md for repair\n";
            $fail++;
        }
    }

    echo str_repeat("-", 50) . "\n";
    echo "Done. " . $ok . " check(s) passed";
    if ($warn > 0) {
        echo ", " . $warn . " warning(s)";
    }
    if ($fail > 0) {
        echo ", " . $fail . " failure(s)";
    }
    if ($repaired) {
        echo "; session.md repaired";
    }
    echo ".\n";
}

/**
 * Detailed help for whoami (dual-identity context). See docs/lupopedia_whoami_readme.md Section 4.
 */
function lupo_help_whoami()
{
    $ver = function_exists('get_lupo_version') ? get_lupo_version() : '4.0.72';
    echo "Lupopedia CLI v" . $ver . " — whoami (dual-identity context)\n";
    echo str_repeat("=", 60) . "\n\n";
    echo "WHOAMI displays the current execution context with three identity layers:\n\n";
    echo "  1. Effective Actor — The actor that owns the session (from lupo_sessions.actor_name or session.md).\n";
    echo "  2. Human Identity — Derived from lupo_actors.paired_actor_id when the effective actor is an agent.\n";
    echo "     If the effective actor is human, human identity = that actor. Never stored in the session table.\n";
    echo "  3. Active Agent — The active agent persona; = effective actor when actor_type is agent/ide_agent, else 'none'.\n\n";
    echo "Session Mode (derived):\n";
    echo "  actor_type = human       -> human_direct\n";
    echo "  actor_type = agent and paired_actor_id = 0 -> autonomous_agent\n";
    echo "  actor_type = agent and paired_actor_id > 0 -> hybrid\n";
    echo "  actor_type = system      -> system\n\n";
    echo "Examples:\n";
    echo "  Hybrid (e.g. Cursor):     Human Identity: root (1000), Active Agent: cursor (102), Session Mode: hybrid\n";
    echo "  Human direct (root):    Human Identity: root (1000), Active Agent: none, Session Mode: human_direct\n";
    echo "  Autonomous (e.g. Lilith): Human Identity: none, Active Agent: lilith (2), Session Mode: autonomous_agent\n";
    echo "  System:                   Human Identity: none, Active Agent: none, Session Mode: system\n\n";
    echo "Full reference: docs/lupopedia_whoami_readme.md (Section 4 – Dual-Identity Context)\n";
}

/**
 * Detailed help for context (JSON output). See docs/lupopedia_whoami_readme.md.
 */
function lupo_help_context()
{
    $ver = function_exists('get_lupo_version') ? get_lupo_version() : '4.0.72';
    echo "Lupopedia CLI v" . $ver . " — context (JSON runtime context)\n";
    echo str_repeat("=", 60) . "\n\n";
    echo "CONTEXT outputs a flat JSON object of the full runtime context (same data as whoami --verbose).\n\n";
    echo "Resolution order (ContextResolver):\n";
    echo "  1. session.md (first-class) — lupo-database/session.md when present; then enrich from DB/registry.\n";
    echo "  2. lupo_sessions (when session.md absent) — actor_name, actor_id, channel_id, federation_node_id, session_id; actor_type and paired_actor_id from lupo_actors.\n";
    echo "  3. Defaults — actor_name: system, agent_name: none, human_actor_name: none, session_mode: system.\n\n";
    echo "Sample JSON:\n";
    echo "{\n";
    echo "  \"actor_name\": \"cursor\",\n";
    echo "  \"actor_id\": 102,\n";
    echo "  \"human_actor_name\": \"root\",\n";
    echo "  \"human_actor_id\": 1000,\n";
    echo "  \"agent_name\": \"cursor\",\n";
    echo "  \"actor_type\": \"ide_faucet\",\n";
    echo "  \"paired_actor_id\": 1000,\n";
    echo "  \"session_mode\": \"hybrid\",\n";
    echo "  \"channel_id\": 42,\n";
    echo "  \"federation_node_id\": 0,\n";
    echo "  \"workspace\": \"/lupo-actors/cursor/\",\n";
    echo "  \"session_id\": \"sess_a82f9c1b\",\n";
    echo "  \"context_source\": \"lupo_sessions\"\n";
    echo "}\n\n";
    echo "Full reference: docs/lupopedia_whoami_readme.md\n";
}

/**
 * Validate required FLARE headers for context; print WARNING for missing. Does not crash.
 * @param array $ctx Resolved context (actor_name, channel_id, federation_node_id, etc.)
 * @param string $system_version Lupopedia version string
 */
function lupo_validate_flare_headers($ctx, $system_version)
{
    $required = array('flare.version', 'flare.schema', 'actor_name', 'channel_id', 'federation_node_id', 'system_version', 'last_modified_utc');
    $have = array(
        'actor_name' => isset($ctx['actor_name']) && $ctx['actor_name'] !== '',
        'channel_id' => isset($ctx['channel_id']),
        'federation_node_id' => isset($ctx['federation_node_id']),
        'system_version' => $system_version !== '',
        'last_modified_utc' => true
    );
    if (!$have['actor_name']) {
        echo "WARNING: Missing required FLARE header: actor_name\n";
    }
    if (!$have['channel_id']) {
        echo "WARNING: Missing required FLARE header: channel_id\n";
    }
    if (!$have['federation_node_id']) {
        echo "WARNING: Missing required FLARE header: federation_node_id\n";
    }
    if (!$have['system_version']) {
        echo "WARNING: Missing required FLARE header: system_version\n";
    }
}

// Audit logging function with log level control
function logOperation($actor_id, $command, $details)
{
    global $db, $table_prefix;

    // Check log level environment variable (default: INFO)
    $log_level = getenv('LUPO_LOG_LEVEL') ?: 'INFO';
    $log_levels = array('DEBUG' => 0, 'INFO' => 1, 'WARNING' => 2, 'ERROR' => 3);
    $current_level = isset($log_levels[$log_level]) ? $log_levels[$log_level] : 1;

    // Only log INFO level and above by default
    $command_level = isset($log_levels['INFO']) ? $log_levels['INFO'] : 1;
    if ($current_level > $command_level) {
        return; // Skip logging if below threshold
    }

    try {
        $t = $table_prefix . 'audit_log';
        $now = (int) gmdate('YmdHis');
        $id = function_exists('lupo_findpuka') ? lupo_findpuka($db, $t, 'audit_log_id', 1) : null;
        if ($id === null) {
            $stmt = $db->prepare("SELECT MAX(audit_log_id) FROM {$t}");
            $stmt->execute();
            $id = (int) $stmt->fetchColumn() + 1;
        }

        // Handle JSON serialization with proper escaping
        $details_json = json_encode($details, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($details_json === false) {
            $details_json = json_encode(array('error' => 'JSON serialization failed', 'original_type' => gettype($details)));
        }

        // Truncate if too large (prevent database issues)
        if (strlen($details_json) > 65535) {
            $details_json = json_encode(array(
                'truncated' => true,
                'original_size' => strlen($details_json),
                'command' => $command,
                'timestamp' => $now
            ));
        }

        $stmt = $db->prepare("INSERT INTO {$t} (audit_log_id, actor_id, command, details_json, created_ymdhis) VALUES (:id, :aid, :cmd, :details, :created)");
        $stmt->execute(array(
            'id' => $id,
            'aid' => $actor_id,
            'cmd' => $command,
            'details' => $details_json,
            'created' => $now
        ));
    } catch (Exception $e) {
        // Silent fail for logging to avoid disrupting main operation
        error_log("Audit log failed: " . $e->getMessage());
    }
}
