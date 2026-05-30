<?php
/**
 * Ban-at-Gate helpers (4.0.18 T7). Router-level persona ban check and logging.
 * Uses lupo_banned_actors; optional logging to lupo_bans_log (skipped if table missing).
 * PHP 5.3-compatible: array() only, no ?? .
 *
 * @package Lupopedia
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}

/**
 * Check whether an actor_id is banned (present in lupo_banned_actors, is_deleted = 0).
 *
 * @param int $actor_id Actor ID to check
 * @return bool True if banned, false otherwise
 */
function lupo_is_actor_banned($actor_id) {
    $actor_id = (int) $actor_id;
    if ($actor_id <= 0) {
        return false;
    }
    $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
    if (!$db || !method_exists($db, 'fetchRow')) {
        return false;
    }
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $table = $prefix . 'banned_actors';
    $qtable = $db->quoteIdentifier($table);
    $row = $db->fetchRow(
        "SELECT 1 FROM " . $qtable . " WHERE actor_id = :actor_id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
        array('actor_id' => $actor_id)
    );
    return is_array($row) && !empty($row);
}

/**
 * Get the current request's actor_id from auth/session (logged-in user).
 *
 * @return int|null Actor ID if logged in, null otherwise
 */
function lupo_get_current_actor_id() {
    $auth = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    if ($auth && is_object($auth) && method_exists($auth, 'getCurrentUser')) {
        $user = $auth->getCurrentUser();
        if (is_array($user) && isset($user['actor_id'])) {
            return (int) $user['actor_id'];
        }
    }
    if (function_exists('current_user')) {
        $user = current_user();
        if (is_array($user) && isset($user['actor_id'])) {
            return (int) $user['actor_id'];
        }
    }
    $session = isset($GLOBALS['lupo_session']) ? $GLOBALS['lupo_session'] : null;
    if ($session && is_object($session) && method_exists($session, 'validateSession')) {
        $actor_id = $session->validateSession();
        return $actor_id ? (int) $actor_id : null;
    }
    return null;
}

/**
 * Log a router ban event to lupo_bans_log. If the table does not exist, skip silently.
 * Columns: actor_id, uri, resolved_uri, ban_scope, banned_ymdhis, user_agent, ip_address.
 *
 * @param int    $actor_id     Banned actor_id
 * @param string $uri          Requested path (e.g. REQUEST_URI or slug)
 * @param string $resolved_uri Resolved canonical or alias path
 */
function lupo_log_ban_event($actor_id, $uri, $resolved_uri) {
    $db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
    if (!$db || !method_exists($db, 'insert')) {
        return;
    }
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $table = $prefix . 'bans_log';
    $now = gmdate('YmdHis');
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? substr((string) $_SERVER['HTTP_USER_AGENT'], 0, 500) : '';
    $ip_address = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';
    $data = array(
        'actor_id' => (int) $actor_id,
        'uri' => is_string($uri) ? substr($uri, 0, 1024) : '',
        'resolved_uri' => is_string($resolved_uri) ? substr($resolved_uri, 0, 1024) : '',
        'ban_scope' => 'router',
        'banned_ymdhis' => $now,
        'user_agent' => $user_agent,
        'ip_address' => $ip_address,
    );
    try {
        $db->insert($table, $data);
    } catch (Exception $e) {
        // Table may not exist; skip logging silently.
    }
}
