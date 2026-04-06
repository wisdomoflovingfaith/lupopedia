<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: functions
  when_updated: "20260406030925"
  file_path_from_root: "lupo-includes/functions/auth-helpers.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-includes/functions/auth-helpers.php"
  last_modified_utc: "20260406030925"
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "functions"
  artifact_kind: "auth_helpers"
  purpose: "Authentication helpers; thin wrappers around AuthService/ActorService; login redirect via lupo_sessions metadata (not PHP session superglobal)."
  tags: ["auth", "helpers", "session", "compatibility"]
---
*/

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. auth-helpers.php cannot be called directly.");
}
$session_compat_path = (defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'session-compat-5.6.php';
if (is_file($session_compat_path)) {
    require_once $session_compat_path;
}

/**
 * Authentication Helper Functions
 * 
 * Provides user authentication, authorization, and actor linkage functions.
 * Handles the relationship between auth_users, actors, roles, and permissions.
 * 
 * @package Lupopedia
 * @subpackage Functions
 */

// Session is provided by bootstrap as $GLOBALS['lupo_session'] (App\Auth\Session). No procedural session helpers.

// Ensure redirect helpers are loaded
if (!function_exists('lupo_safe_redirect')) {
    require_once(__DIR__ . DIRECTORY_SEPARATOR . 'redirect-helpers.php');
}

/**
 * Get actor ID from auth user ID (thin wrapper — logic in ActorService).
 *
 * @param int $auth_user_id Auth user ID
 * @return int|false Actor ID on success, false if not found
 */
function lupo_get_actor_id_from_auth_user_id($auth_user_id) {
    $s = isset($GLOBALS['lupo_actor_service']) ? $GLOBALS['lupo_actor_service'] : null;
    return $s ? $s->getActorIdFromAuthUserId((int) $auth_user_id) : false;
}

/**
 * Create actor record for authenticated user (thin wrapper — logic in ActorService).
 *
 * @param int $auth_user_id Auth user ID
 * @param string $email Email address (used for slug generation)
 * @param string $display_name Display name (used for name)
 * @return int|false Actor ID on success, false on failure
 */
function lupo_create_actor_for_auth_user($auth_user_id, $email, $display_name) {
    $s = isset($GLOBALS['lupo_actor_service']) ? $GLOBALS['lupo_actor_service'] : null;
    $disp = isset($display_name) ? (string) $display_name : '';
    return $s ? $s->createActorForAuthUser((int) $auth_user_id, (string) $email, $disp) : false;
}

/**
 * Check if actor slug exists (thin wrapper — logic in ActorService).
 *
 * @param string $slug Actor slug to check
 * @return bool True if slug exists, false otherwise
 */
function lupo_actor_slug_exists($slug) {
    $s = isset($GLOBALS['lupo_actor_service']) ? $GLOBALS['lupo_actor_service'] : null;
    return $s ? $s->actorSlugExists((string) $slug) : false;
}

/**
 * Get current authenticated user information (thin wrapper — logic in AuthService).
 *
 * @return array|false User data array or false if not logged in
 */
function current_user() {
    $s = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    return $s ? $s->getCurrentUser() : false;
}

/**
 * Check if an actor has admin role (thin wrapper — logic in AuthRoleResolver).
 *
 * @param int $actor_id Actor ID to check
 * @return bool True if admin, false otherwise
 */
function lupo_is_admin($actor_id) {
    $s = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    return $s ? $s->isAdmin((int) $actor_id) : false;
}

/**
 * Check if an actor has admin-level permission for a channel (3-layer: channel → department → system).
 *
 * @param int $actor_id   Actor ID to check
 * @param int $channel_id Channel ID
 * @return bool
 */
function lupo_has_admin_for_channel($actor_id, $channel_id) {
    $s = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    return $s ? $s->hasAdminForChannel((int) $actor_id, (int) $channel_id) : false;
}

/**
 * Whether department_id is the reserved system department (0). Not user-selectable; cannot edit/delete.
 *
 * @param int $department_id
 * @return bool
 */
function lupo_is_system_department($department_id) {
    return (int) $department_id === 0;
}

/**
 * Get auth_user_id from actor_id (thin wrapper — logic in ActorService).
 *
 * @param int $actor_id Actor ID
 * @return int|false Auth user ID on success, false otherwise
 */
function lupo_get_auth_user_id_from_actor_id($actor_id) {
    $s = isset($GLOBALS['lupo_actor_service']) ? $GLOBALS['lupo_actor_service'] : null;
    return $s ? $s->getAuthUserIdFromActorId((int) $actor_id) : false;
}

/**
 * Require user to be logged in (thin wrapper — logic in AuthService).
 *
 * @return void Exits script if not logged in
 */
function require_login() {
    $s = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    if ($s) {
        $s->requireLogin();
        return;
    }
    $user = current_user();
    if (!$user) {
        global $UNTRUSTED;
        if (!isset($UNTRUSTED) || !is_array($UNTRUSTED)) {
            $UNTRUSTED = array();
        }
        if (!isset($UNTRUSTED['server']) || !is_array($UNTRUSTED['server'])) {
            $UNTRUSTED['server'] = (isset($_SERVER) && is_array($_SERVER)) ? $_SERVER : array();
        }
        $redirect_url = isset($UNTRUSTED['server']['REQUEST_URI']) ? $UNTRUSTED['server']['REQUEST_URI'] : '/';

        $db = null;
        if (class_exists('DatabaseFactory', false)) {
            try {
                $db = DatabaseFactory::getConnection();
            } catch (Exception $e) {
                $db = null;
            }
        }
        if ($db && function_exists('lupo_session_metadata_merge_current')) {
            lupo_session_metadata_merge_current($db, array('login_redirect' => $redirect_url));
        }

        $login_url = (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/login?redirect=' . urlencode($redirect_url);
        lupo_safe_redirect($login_url, 2, 'Please log in to continue.');
    }
}

/**
 * Require user to be admin (thin wrapper — logic in AuthService).
 *
 * @return void Exits script if not admin
 */
function require_admin() {
    $s = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
    if ($s) {
        $s->requireAdmin();
        return;
    }
    require_login();
    $user = current_user();
    if (!$user || empty($user['is_admin'])) {
        header('HTTP/1.1 403 Forbidden');
        echo '<h1>403 Forbidden</h1><p>You do not have permission to access this page.</p>';
        exit;
    }
}

/**
 * Model A: decoded lupo_sessions.metadata for the current PHP session id (transient flags only).
 *
 * @param mixed $db PDO_DB
 * @return array
 */
function lupo_session_metadata_current($db)
{
    if (!$db) {
        return array();
    }
    if (!class_exists('App\\Auth\\Session', false) && defined('LUPOPEDIA_PATH')) {
        require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'Session.php';
    }
    if (!class_exists('App\\Auth\\Session', false)) {
        return array();
    }
    $app = new App\Auth\Session($db);
    $sid = $app->getSessionId();
    if (!$sid) {
        return array();
    }
    return App\Auth\Session::getDecodedMetadata($db, $sid);
}

/**
 * Merge patch into lupo_sessions.metadata for current PHP session (null values remove keys).
 *
 * @param mixed $db PDO_DB
 * @param array $patch
 * @return bool
 */
function lupo_session_metadata_merge_current($db, array $patch)
{
    if (!$db) {
        return false;
    }
    if (!class_exists('App\\Auth\\Session', false) && defined('LUPOPEDIA_PATH')) {
        require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'Session.php';
    }
    if (!class_exists('App\\Auth\\Session', false)) {
        return false;
    }
    $app = new App\Auth\Session($db);
    $sid = $app->getSessionId();
    if (!$sid) {
        return false;
    }
    return App\Auth\Session::mergeSessionMetadata($db, $sid, $patch);
}

?>
