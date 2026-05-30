<?php
/**
 * Admin diagnostics — local-only, dev-only JSON logging for admin flows.
 * 4.0.20: permission traces, CSRF traces, session introspection, admin action audit.
 * Logs: lupo-logs/admin/YYYY-MM-DD.jsonl; rotation at >1MB with flock. PHP 5.3+ compatible.
 */

/**
 * Core writer: append one JSON line. Rotates when file > 1MB (flock to prevent races).
 *
 * @param string $type Log entry type (e.g. permission_check, csrf, session, admin_action)
 * @param array  $data Key-value data to merge with type and timestamp
 */
function lupo_diag_write($type, $data) {
    $dir = defined('LUPOPEDIA_PATH')
        ? LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-logs' . DIRECTORY_SEPARATOR . 'admin'
        : dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'lupo-logs' . DIRECTORY_SEPARATOR . 'admin';
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    $logfile = $dir . DIRECTORY_SEPARATOR . date('Y-m-d') . '.jsonl';

    $max = 1024 * 1024; // 1MB
    if (file_exists($logfile) && @filesize($logfile) > $max) {
        $fp = @fopen($logfile, 'r+');
        if ($fp && flock($fp, LOCK_EX)) {
            $rotated = $logfile . '.1';
            @rename($logfile, $rotated);
            flock($fp, LOCK_UN);
        }
        if ($fp) {
            fclose($fp);
        }
    }

    $entry = json_encode(array_merge(
        array('type' => $type, 'timestamp' => gmdate('c')),
        $data
    )) . "\n";
    @file_put_contents($logfile, $entry, FILE_APPEND | LOCK_EX);
}

/**
 * Log a permission check.
 *
 * @param int    $actor_id  Actor ID (0 if unknown)
 * @param array  $role_list List of role keys (e.g. captain, administrator)
 * @param string $resource  Resource identifier (e.g. "admin", "admin.users")
 * @param bool   $allowed   Whether access was allowed
 */
function lupo_diag_permission_check($actor_id, $role_list, $resource, $allowed) {
    $roles = is_array($role_list) ? $role_list : array();
    lupo_diag_write('permission_check', array(
        'actor_id' => (int) $actor_id,
        'roles' => $roles,
        'resource' => (string) $resource,
        'allowed' => (bool) $allowed,
    ));
}

/**
 * Log CSRF validation result.
 *
 * @param int  $actor_id      Actor ID (0 if unknown)
 * @param bool $token_present Whether a token was submitted
 * @param bool $token_valid   Whether the token matched session
 */
function lupo_diag_csrf($actor_id, $token_present, $token_valid) {
    lupo_diag_write('csrf', array(
        'actor_id' => (int) $actor_id,
        'token_present' => (bool) $token_present,
        'token_valid' => (bool) $token_valid,
    ));
}

/**
 * Log session introspection (dev-only; IP is sensitive).
 *
 * @param int    $actor_id   Actor ID (0 if unknown)
 * @param int    $session_age Age in seconds (0 if unknown)
 * @param string $ip         Client IP (dev-only)
 */
function lupo_diag_session($actor_id, $session_age, $ip) {
    lupo_diag_write('session', array(
        'actor_id' => (int) $actor_id,
        'session_age' => (int) $session_age,
        'ip' => (string) $ip,
    ));
}

/**
 * Log an admin action (e.g. save profile, save permissions).
 *
 * @param int        $actor_id   Actor who performed the action
 * @param string     $action     Action name (e.g. save_profile, save_permissions)
 * @param string     $target_type Target type (e.g. auth_user, actor_channel_role)
 * @param int|string $target_id  Target ID
 * @param bool       $success    Whether the action succeeded
 */
function lupo_diag_admin_action($actor_id, $action, $target_type, $target_id, $success) {
    lupo_diag_write('admin_action', array(
        'actor_id' => (int) $actor_id,
        'action' => (string) $action,
        'target_type' => (string) $target_type,
        'target_id' => $target_id,
        'success' => (bool) $success,
    ));
}
