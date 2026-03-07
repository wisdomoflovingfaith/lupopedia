<?php

namespace App\Auth;

/**
 * Auth service — current user, login/admin gates, identity accessors.
 * Uses Session as the only session source, PDO_DB, LUPO_TABLE_PREFIX.
 * No Laravel, no middleware. All logic in application code.
 */

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

class AuthService
{
    /** @var Session */
    private $session;

    /** @var \PDO_DB */
    private $db;

    /** @var AuthRoleResolver */
    private $roleResolver;

    public function __construct(Session $session, $db, AuthRoleResolver $roleResolver)
    {
        $this->session = $session;
        $this->db = $db;
        $this->roleResolver = $roleResolver;
    }

    /**
     * Current user array or false if not logged in.
     * Shape: actor_id, auth_user_id, username, display_name, email, is_admin.
     *
     * @return array|false
     */
    public function getCurrentUser()
    {
        $actorId = $this->session->validateSession();
        if (!$actorId) {
            return false;
        }

        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $a = $this->db->quoteIdentifier($prefix . 'actors');
        $au = $this->db->quoteIdentifier($prefix . 'auth_users');

        $user = $this->db->fetchRow(
            "SELECT a.actor_id, a.actor_source_id as auth_user_id, au.username, au.display_name, au.email, au.is_active as user_is_active
             FROM {$a} a
             JOIN {$au} au ON a.actor_source_id = au.auth_user_id
             WHERE a.actor_id = :actor_id AND a.actor_source_type = 'user'
               AND (a.is_deleted = 0 OR a.is_deleted IS NULL)
               AND (au.is_deleted = 0 OR au.is_deleted IS NULL)
             LIMIT 1",
            array('actor_id' => $actorId)
        );
        if (!$user || (int) (isset($user['user_is_active']) ? $user['user_is_active'] : 0) !== 1) {
            return false;
        }

        // Hybrid Actor Security Gate (4.0.29 Centralization)
        try {
            if (class_exists('\HybridActorSecurityService')) {
                \HybridActorSecurityService::assertActorOperational($user['actor_id'], 'session_validation');
            }
        } catch (\SecurityException $e) {
            return false;
        }

        $user['is_admin'] = $this->roleResolver->isAdmin((int) $user['actor_id']);
        return array(
            'actor_id' => (int) $user['actor_id'],
            'auth_user_id' => (int) $user['auth_user_id'],
            'username' => $user['username'],
            'display_name' => $user['display_name'],
            'email' => $user['email'],
            'is_admin' => $user['is_admin'],
        );
    }

    /**
     * Same as getCurrentUser() but returns null when not logged in (for templates).
     *
     * @return array|null
     */
    public function getCurrentUserData()
    {
        $user = $this->getCurrentUser();
        return $user !== false ? $user : null;
    }

    /**
     * Get auth user row by auth_user_id (for use by ActorService / Antigravity context).
     *
     * @param int $authUserId
     * @return array|null Row from lupo_auth_users or null
     */
    public function getUserByAuthUserId($authUserId)
    {
        $authUserId = (int) $authUserId;
        if ($authUserId <= 0) {
            return null;
        }
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $au = $this->db->quoteIdentifier($prefix . 'auth_users');
        $row = $this->db->fetchRow(
            "SELECT * FROM {$au} WHERE auth_user_id = :id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('id' => $authUserId)
        );
        return is_array($row) ? $row : null;
    }

    /**
     * Get auth user row by actor_id (join lupo_actors where actor_source_type = 'user').
     *
     * @param int $actorId
     * @return array|null Row from lupo_auth_users or null
     */
    public function getUserByActorId($actorId)
    {
        $actorId = (int) $actorId;
        if ($actorId <= 0) {
            return null;
        }
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $a = $this->db->quoteIdentifier($prefix . 'actors');
        $au = $this->db->quoteIdentifier($prefix . 'auth_users');
        $row = $this->db->fetchRow(
            "SELECT au.* FROM {$au} au
             INNER JOIN {$a} a ON a.actor_source_id = au.auth_user_id AND (a.actor_source_type = 'user' OR a.actor_source_type = 'lupo_auth_users')
             WHERE a.actor_id = :actor_id AND (a.is_deleted = 0 OR a.is_deleted IS NULL) AND (au.is_deleted = 0 OR au.is_deleted IS NULL) LIMIT 1",
            array('actor_id' => $actorId)
        );
        return is_array($row) ? $row : null;
    }

    /**
     * Get auth user row by actor_name.
     *
     * @param string $actorName
     * @return array|null Row from lupo_auth_users or null
     */
    public function getUserByActorName($actorName)
    {
        if (!is_string($actorName) || trim($actorName) === '') {
            return null;
        }
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $a = $this->db->quoteIdentifier($prefix . 'actors');
        $au = $this->db->quoteIdentifier($prefix . 'auth_users');
        $row = $this->db->fetchRow(
            "SELECT au.* FROM {$au} au
             INNER JOIN {$a} a ON a.actor_source_id = au.auth_user_id AND (a.actor_source_type = 'user' OR a.actor_source_type = 'lupo_auth_users')
             WHERE a.actor_name = :actor_name AND (a.is_deleted = 0 OR a.is_deleted IS NULL) AND (au.is_deleted = 0 OR au.is_deleted IS NULL) LIMIT 1",
            array('actor_name' => trim($actorName))
        );
        return is_array($row) ? $row : null;
    }

    /**
     * Whether the current session has a logged-in user.
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->getCurrentUser() !== false;
    }

    /**
     * Current username or empty string.
     *
     * @return string
     */
    public function getUsername()
    {
        $user = $this->getCurrentUser();
        return $user ? (string) (isset($user['username']) ? $user['username'] : '') : '';
    }

    /**
     * Current display name (or username) or empty string.
     *
     * @return string
     */
    public function getDisplayName()
    {
        $user = $this->getCurrentUser();
        return $user ? (string) (isset($user['display_name']) ? $user['display_name'] : (isset($user['username']) ? $user['username'] : '')) : '';
    }

    /**
     * Whether the given actor has admin role (delegates to AuthRoleResolver).
     *
     * @param int $actorId
     * @return bool
     */
    public function isAdmin($actorId)
    {
        return $this->roleResolver->isAdmin($actorId);
    }

    /**
     * Whether the given actor has any channel role (e.g. for operator UI).
     *
     * @param int $actorId
     * @return bool
     */
    public function hasAnyChannelRole($actorId)
    {
        return $this->roleResolver->hasAnyChannelRole($actorId);
    }

    /**
     * Whether the actor has admin-level permission for a channel (3-layer: channel → department → system).
     *
     * @param int $actorId
     * @param int $channelId
     * @return bool
     */
    public function hasAdminForChannel($actorId, $channelId)
    {
        return $this->roleResolver->hasAdminForChannel((int) $actorId, (int) $channelId);
    }

    /**
     * Require user to be logged in; redirect to login with redirect param if not.
     * Exits on redirect. Also redirects if password_change_required in session.
     */
    public function requireLogin()
    {
        $user = $this->getCurrentUser();
        if ($user === false) {
            $redirectUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
            $this->session->start();
            $_SESSION['login_redirect'] = $redirectUrl;
            $loginUrl = (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/login?redirect=' . urlencode($redirectUrl);
            if (function_exists('lupo_safe_redirect')) {
                lupo_safe_redirect($loginUrl, 2, 'Please log in to continue.');
            } else {
                header('Location: ' . $loginUrl);
                exit;
            }
        }
        $this->session->start();
        if (!empty($_SESSION['password_change_required'])) {
            $changeUrl = (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/change-password';
            if (function_exists('lupo_safe_redirect')) {
                lupo_safe_redirect($changeUrl, 2, 'Password change required. Redirecting...');
            } else {
                header('Location: ' . $changeUrl);
                exit;
            }
        }
    }

    /**
     * Require admin; calls requireLogin() then returns 403 if not admin.
     */
    public function requireAdmin()
    {
        $this->requireLogin();
        $user = $this->getCurrentUser();
        if ($user === false || empty($user['is_admin'])) {
            header('HTTP/1.1 403 Forbidden');
            echo '<h1>403 Forbidden</h1><p>You do not have permission to access this page.</p>';
            exit;
        }
    }
}
