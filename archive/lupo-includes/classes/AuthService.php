<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: class
  when_updated: "20260406023848"
  file_path_from_root: "lupo-includes/classes/AuthService.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-includes/classes/AuthService.php"
  questions_toon: null
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "class"
  artifact_kind: "auth"
  purpose: "Authentication service; Model A session via App\\Auth\\Session (no $_SESSION authority flags); transient login flags in lupo_sessions.metadata."
  tags: ["auth", "session", "login", "logout", "model_a"]
---
*/

/**
 * Auth Service — login, logout, current user from lupo_sessions (Model A).
 *
 * PHP session cookie holds only session_id; actor authority and transient flags (password change, pending agent)
 * live in the database row (metadata JSON), not $_SESSION.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. AuthService.php cannot be called directly.");
}

require_once __DIR__ . '/../security/password-hash.php';
require_once __DIR__ . '/AuthSessionManager.php';

if (!class_exists('App\\Auth\\Session', false) && defined('LUPOPEDIA_PATH')) {
    require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'Session.php';
}

class AuthService
{
    private $db;
    private $table_prefix;

    public function __construct()
    {
        $this->db = DatabaseFactory::getConnection();
        $this->table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    }

    /**
     * @return \App\Auth\Session
     */
    private function appSession()
    {
        return new \App\Auth\Session($this->db);
    }

    private function packedNowUtc()
    {
        if (!class_exists('timestamp_ymdhis', false) && defined('LUPOPEDIA_PATH')) {
            require_once LUPOPEDIA_PATH . '/lupo-includes/classes/TimestampYmdhis.php';
        }
        if (class_exists('timestamp_ymdhis', false)) {
            return (string) timestamp_ymdhis::now();
        }
        return gmdate('YmdHis');
    }

    /**
     * Authenticate user with username and password
     */
    public function authenticate($username, $password)
    {
        $sql = "SELECT auth_user_id, username, display_name, email, password_hash
                FROM {$this->table_prefix}auth_users
                WHERE username = :username 
                AND is_active = 1 
                AND is_deleted = 0
                LIMIT 1";

        $user = $this->db->fetchRow($sql, array('username' => $username));

        if (!$user || !lupo_verify_password($password, $user['password_hash'])) {
            return false;
        }

        $this->db->update(
            "{$this->table_prefix}auth_users",
            array('last_login_ymdhis' => $this->packedNowUtc()),
            'auth_user_id = :auth_user_id',
            array('auth_user_id' => $user['auth_user_id'])
        );

        return $user;
    }

    /**
     * Handle login and actor resolution
     */
    public function handleLogin($username, $password, $redirect = null)
    {
        error_log("SESSION_DEBUG: TOP OF AuthService->handleLogin called for username=$username");
        $appSession = $this->appSession();
        $appSession->getSessionId();

        $auth_user = $this->authenticate($username, $password);

        if (!$auth_user) {
            return array('error' => 'Invalid credentials');
        }

        $stored_hash = isset($auth_user['password_hash']) ? $auth_user['password_hash'] : '';
        $needs_password_change = lupo_password_needs_upgrade($stored_hash);
        unset($auth_user['password_hash']);

        $sessionManager = new AuthSessionManager();

        $existing_actor = $sessionManager->getActorForAuthUser($auth_user['auth_user_id']);
        if ($existing_actor) {
            error_log("SESSION_DEBUG: handleLogin found existing_actor: actor_id=" . $existing_actor['actor_id'] . ", actor_name=" . $existing_actor['actor_name']);
            $started = $sessionManager->createSession($existing_actor['actor_id'], $existing_actor['actor_name']);
            if ($started === false) {
                error_log("SESSION_DEBUG: handleLogin - createSession returned false for actor_id=" . $existing_actor['actor_id']);
                return array('error' => 'Could not start session');
            }
            $sid = is_string($started) ? $started : session_id();
            if ($needs_password_change) {
                \App\Auth\Session::mergeSessionMetadata($this->db, $sid, array(
                    'password_change_required' => true,
                    'password_change_user_id' => (int) $auth_user['auth_user_id'],
                    'password_change_actor_id' => (int) $existing_actor['actor_id'],
                ));
                if ($redirect !== null && $redirect !== '') {
                    \App\Auth\Session::mergeSessionMetadata($this->db, $sid, array(
                        'login_redirect' => (string) $redirect,
                    ));
                }
                return array('success' => true, 'needs_password_change' => true);
            }
            $default_admin = (defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/admin.php' : '/admin.php');
            error_log("SESSION_DEBUG: handleLogin - session created and login successful for actor_id=" . $existing_actor['actor_id']);
            return array('success' => true, 'redirect' => $redirect ? $redirect : $default_admin);
        } else {
            error_log("SESSION_DEBUG: handleLogin - NO existing_actor found for auth_user_id=" . $auth_user['auth_user_id']);
        }

        $agents = $sessionManager->getAvailableAgents();

        $pendingSession = \App\Auth\Session::create($this->db, 0);
        if (!$pendingSession) {
            return array('error' => 'Could not start session');
        }

        $metaPatch = array(
            'pending_auth_user_id' => (int) $auth_user['auth_user_id'],
            'pending_username' => (string) $username,
        );
        if ($needs_password_change) {
            $metaPatch['password_change_required'] = true;
            $metaPatch['password_change_user_id'] = (int) $auth_user['auth_user_id'];
        }
        if ($redirect !== null && $redirect !== '') {
            $metaPatch['login_redirect'] = (string) $redirect;
        }
        \App\Auth\Session::mergeSessionMetadata($this->db, $pendingSession->session_id, $metaPatch);

        $base = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';

        return array(
            'success' => true,
            'needs_agent_selection' => true,
            'agents' => $agents,
            'redirect' => $base . '/select_agent.php',
        );
    }

    /**
     * Get current user from session (lupo_sessions + actors join)
     */
    public function getCurrentUser()
    {
        $sid = $this->appSession()->getSessionId();
        if ($sid === '' || $sid === false) {
            return false;
        }

        $valid = $this->db->fetchRow(
            "SELECT actor_id FROM {$this->table_prefix}sessions WHERE session_id = :sid AND is_active = 1 AND is_expired = 0 AND is_revoked = 0 AND is_deleted = 0 LIMIT 1",
            array('sid' => $sid)
        );
        if (!$valid || !isset($valid['actor_id'])) {
            return false;
        }

        $actor_id = (int) $valid['actor_id'];
        if ($actor_id === 0) {
            return false;
        }

        $sql = "SELECT a.actor_id, a.actor_name, a.actor_source_id as auth_user_id, 
                       au.username, au.display_name, au.email
                FROM {$this->table_prefix}actors a
                INNER JOIN {$this->table_prefix}auth_users au ON a.actor_source_id = au.auth_user_id
                WHERE a.actor_id = :actor_id 
                AND (a.actor_source_type = 'user' OR a.actor_source_type = 'lupo_auth_users')
                AND (a.is_deleted = 0 OR a.is_deleted IS NULL)
                AND (au.is_deleted = 0 OR au.is_deleted IS NULL)
                LIMIT 1";

        $user = $this->db->fetchRow($sql, array('actor_id' => $actor_id));

        if (!$user) {
            return false;
        }

        return array(
            'actor_id' => (int) $user['actor_id'],
            'auth_user_id' => (int) $user['auth_user_id'],
            'username' => $user['username'],
            'display_name' => $user['display_name'],
            'email' => $user['email'],
        );
    }

    /**
     * Check if user is logged in
     */
    public function isLoggedIn()
    {
        return $this->getCurrentUser() !== false;
    }

    /**
     * Logout user — mark session expired in DB; clear PHP session cookie (no authority in $_SESSION).
     */
    public function logout()
    {
        $sid = $this->appSession()->getSessionId();
        if ($sid !== '' && $sid !== false) {
            $this->db->update(
                "{$this->table_prefix}sessions",
                array(
                    'is_expired' => 1,
                    'updated_ymdhis' => $this->packedNowUtc(),
                ),
                'session_id = :session_id',
                array('session_id' => $sid)
            );
        }
        if (function_exists('session_status') && session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = array();
            $sn = session_name();
            $cookies = (isset($_COOKIE) && is_array($_COOKIE)) ? $_COOKIE : array();
            if (isset($cookies[$sn])) {
                setcookie($sn, '', time() - 3600, '/');
            }
            session_destroy();
        }
        return true;
    }
}
