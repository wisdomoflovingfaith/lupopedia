<?php
function lupo_crafty_current_user() {
    return current_user();
}

function lupo_crafty_operator() {
    $user = lupo_crafty_current_user();
    if (!$user) {
        return null;
    }

    $operator_id = lupo_get_operator_id_from_auth_user_id($user['auth_user_id']);
    if (!$operator_id) {
        return null;
    }

    $db = lupo_crafty_db();
    if (!$db) {
        return null;
    }

    $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $sql = "SELECT o.*, d.name AS department_name
            FROM {$table_prefix}operators o
            LEFT JOIN {$table_prefix}departments d
              ON d.department_id = o.department_id
            WHERE o.operator_id = :operator_id
              AND o.is_active = 1
            LIMIT 1";

    $stmt = $db->prepare($sql);
    $stmt->execute([':operator_id' => (int)$operator_id]);
    $operator = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$operator) {
        return null;
    }

    $operator['operator_id'] = (int)$operator['operator_id'];
    $operator['actor_id'] = (int)$operator['actor_id'];
    $operator['auth_user_id'] = (int)$operator['auth_user_id'];
    return $operator;
}

function require_operator() {
    $operator = lupo_crafty_operator();
    if ($operator) {
        return;
    }
    header('HTTP/1.1 403 Forbidden');
    echo 'Access denied.';
    exit;
}
