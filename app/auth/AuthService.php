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
            ['actor_id' => $actorId]
        );
        if (!$user || (int) ($user['user_is_active'] ?? 0) !== 1) {
            return false;
        }

        $user['is_admin'] = $this->roleResolver->isAdmin((int) $user['actor_id']);
        return [
            'actor_id' => (int) $user['actor_id'],
            'auth_user_id' => (int) $user['auth_user_id'],
            'username' => $user['username'],
            'display_name' => $user['display_name'],
            'email' => $user['email'],
            'is_admin' => $user['is_admin'],
        ];
    }

    /**
     * Same as getCurrentUser() but returns null when not logged in (for templates).
     *
     * @return array|null
     */
    public function getCurrentUserData(): ?array
    {
        $user = $this->getCurrentUser();
        return $user !== false ? $user : null;
    }

    /**
     * Whether the current session has a logged-in user.
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->getCurrentUser() !== false;
    }

    /**
     * Current username or empty string.
     *
     * @return string
     */
    public function getUsername(): string
    {
        $user = $this->getCurrentUser();
        return $user ? (string) ($user['username'] ?? '') : '';
    }

    /**
     * Current display name (or username) or empty string.
     *
     * @return string
     */
    public function getDisplayName(): string
    {
        $user = $this->getCurrentUser();
        return $user ? (string) ($user['display_name'] ?? $user['username'] ?? '') : '';
    }

    /**
     * Whether the given actor has admin role (delegates to AuthRoleResolver).
     *
     * @param int $actorId
     * @return bool
     */
    public function isAdmin(int $actorId): bool
    {
        return $this->roleResolver->isAdmin($actorId);
    }

    /**
     * Whether the given actor has any channel role (e.g. for operator UI).
     *
     * @param int $actorId
     * @return bool
     */
    public function hasAnyChannelRole(int $actorId): bool
    {
        return $this->roleResolver->hasAnyChannelRole($actorId);
    }

    /**
     * Require user to be logged in; redirect to login with redirect param if not.
     * Exits on redirect. Also redirects if password_change_required in session.
     */
    public function requireLogin(): void
    {
        $user = $this->getCurrentUser();
        if ($user === false) {
            $redirectUrl = $_SERVER['REQUEST_URI'] ?? '/';
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
    public function requireAdmin(): void
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
