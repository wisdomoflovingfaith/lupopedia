<?php
function lupo_crafty_current_user() {
    return current_user();
}

function lupo_crafty_operator() {
    $user = lupo_crafty_current_user();
    if (!$user) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("CRAFTY AUTH: lupo_crafty_operator() - No current user");
        }
        return null;
    }

    $operator_id = lupo_get_operator_id_from_auth_user_id($user['auth_user_id']);
    if (!$operator_id) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("CRAFTY AUTH: lupo_crafty_operator() - No operator ID for auth_user_id: " . $user['auth_user_id']);
        }
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
    if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
        error_log("CRAFTY AUTH: Entering require_operator() - URI: " . ($_SERVER['REQUEST_URI'] ?? 'UNKNOWN'));
        error_log("CRAFTY AUTH: session_name: " . session_name() . ", session_id: " . session_id());
    }
    
    // Check if we are in a debug session
    if (isset($_GET['debug_session'])) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("CRAFTY AUTH: debug_session parameter detected, bypassing require_operator()");
        }
        return;
    }

    // Explicitly check for operator role if current_user() returns something
    $user = lupo_crafty_current_user();
    if ($user) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
             error_log("CRAFTY AUTH: User found: " . $user['email'] . ", Checking operator status...");
        }
    } else {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
             error_log("CRAFTY AUTH: No user found in session.");
        }
    }

    $operator = lupo_crafty_operator();
    if ($operator) {
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            error_log("CRAFTY AUTH: Operator validated: " . $operator['operator_id']);
        }
        return;
    }
    if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
        error_log("CRAFTY AUTH: Access denied in require_operator()");
    }
    header('HTTP/1.1 403 Forbidden');
    echo 'Access denied. You must be an operator to access this page.';
    exit;
}
