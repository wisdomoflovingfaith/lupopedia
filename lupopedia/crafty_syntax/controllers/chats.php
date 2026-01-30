<?php
function lupo_crafty_chats_index() {
    lupo_crafty_render('placeholder', [
        'title' => 'Chats',
        'message' => 'Active chats will load here from dialog threads and messages.'
    ]);
}

function lupo_crafty_escalate_chat() {
    header('Content-Type: application/json');

    $department_id = isset($_POST['department_id']) ? (int)$_POST['department_id'] : (isset($_GET['department_id']) ? (int)$_GET['department_id'] : 0);
    $channel_id = isset($_POST['channel_id']) ? (int)$_POST['channel_id'] : (isset($_GET['channel_id']) ? (int)$_GET['channel_id'] : 0);
    $thread_id = isset($_POST['thread_id']) ? (int)$_POST['thread_id'] : (isset($_GET['thread_id']) ? (int)$_GET['thread_id'] : 0);
    $reason = isset($_POST['escalation_reason']) ? trim((string)$_POST['escalation_reason']) : (isset($_GET['escalation_reason']) ? trim((string)$_GET['escalation_reason']) : '');

    if ($thread_id <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'thread_id is required']);
        return;
    }

    $db = lupo_crafty_db();
    if (!$db) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'database unavailable']);
        return;
    }

    $operator_id = lupo_crafty_select_operator($department_id, $channel_id);
    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    if (!$operator_id) {
        $operator_id = lupo_crafty_select_fallback_operator($db, $table_prefix);
    }

    if (!$operator_id) {
        http_response_code(409);
        echo json_encode(['operator_id' => null, 'status' => 'unassigned']);
        return;
    }

    $payload = [
        'operator_id' => (int)$operator_id,
        'department_id' => $department_id ?: null,
        'channel_id' => $channel_id ?: null,
        'escalation_reason' => $reason !== '' ? $reason : 'manual',
        'timestamp_ymdhis' => lupo_utc_timestamp()
    ];

    lupo_crafty_update_thread_escalation($db, $table_prefix, $thread_id, $payload);
    lupo_crafty_increment_operator_load($db, $table_prefix, $operator_id);

    echo json_encode(['operator_id' => (int)$operator_id, 'status' => 'assigned']);
}
