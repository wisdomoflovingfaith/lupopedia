<?php
function lupo_crafty_operator_overview() {
    $db = lupo_crafty_db();
    $user = lupo_crafty_current_user();
    $operator = lupo_crafty_operator();

    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

    $status = [
        'status' => 'offline',
        'last_seen_ymdhis' => null,
        'active_chat_count' => 0,
        'max_chat_capacity' => 0,
    ];

    $stmt = $db->prepare("SELECT status, last_seen_ymdhis, active_chat_count, max_chat_capacity
        FROM {$table_prefix}operator_status
        WHERE operator_id = :operator_id
        LIMIT 1");
    $stmt->execute([':operator_id' => (int)$operator['operator_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $status = $row;
    }

    $active_threads = 0;
    $stmt = $db->query("SELECT COUNT(*) AS count
        FROM {$table_prefix}dialog_threads
        WHERE status IN ('Open', 'Ongoing')
          AND is_deleted = 0");
    $active_threads = (int)$stmt->fetchColumn();

    $stmt = $db->query("SELECT dialog_thread_id, summary_text, status, updated_ymdhis
        FROM {$table_prefix}dialog_threads
        WHERE is_deleted = 0
        ORDER BY updated_ymdhis DESC
        LIMIT 10");
    $recent_threads = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->query("SELECT department_id, name
        FROM {$table_prefix}departments
        WHERE is_deleted = 0
        ORDER BY name ASC
        LIMIT 10");
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->query("SELECT dialog_thread_id, summary_text, updated_ymdhis
        FROM {$table_prefix}dialog_threads
        WHERE status = 'Closed'
          AND is_deleted = 0
        ORDER BY updated_ymdhis DESC
        LIMIT 5");
    $recent_transcripts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $emotional = lupo_crafty_emotional_snapshot($operator);
    $expertise_snapshot = lupo_crafty_operator_expertise_snapshot();

    lupo_crafty_render('operator_overview', [
        'user' => $user,
        'operator' => $operator,
        'status' => $status,
        'active_threads' => $active_threads,
        'recent_threads' => $recent_threads,
        'recent_transcripts' => $recent_transcripts,
        'departments' => $departments,
        'emotional' => $emotional,
        'expertise_snapshot' => $expertise_snapshot,
    ]);
}
