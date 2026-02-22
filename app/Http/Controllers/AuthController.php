<?php

namespace App\Http\Controllers;

use App\Auth\SessionHandler;

/**
 * Auth controller — plain PHP, PDO. No Laravel.
 * Constructor: ($db, SessionHandler $sessionHandler = null).
 * All timestamps BIGINT YmdHis UTC.
 */

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

class AuthController
{
    /** @var \PDO_DB */
    private $db;

    /** @var SessionHandler */
    protected $sessionHandler;

    public function __construct($db, SessionHandler $sessionHandler = null)
    {
        $this->db = $db;
        $this->sessionHandler = $sessionHandler ?? new SessionHandler($db);
    }

    /**
     * Unified login. $input: email, password. $requestInfo: ip, user_agent (optional).
     * Returns ['success' => bool, 'message' => string?, 'user_id' => ?, 'user_type' => ?, 'user_data' => ?, 'redirect' => ?].
     */
    public function unifiedLogin(array $input, array $requestInfo = null): array
    {
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        if (!$email || !$password) {
            return ['success' => false, 'message' => 'Email and password required'];
        }

        $path = isset($_SERVER['REQUEST_URI']) ? (string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '';
        $systemContext = $this->sessionHandler->detectSystemContext($path);

        $result = $this->attemptLupopediaAuth($email, $password);
        if (!$result['success']) {
            return array_merge($result, ['redirect' => '/login']);
        }

        $now = (int) gmdate('YmdHis');
        $sessionData = [
            'login_ymdhis' => $now,
            'ip_address' => $requestInfo['ip'] ?? $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $requestInfo['user_agent'] ?? $_SERVER['HTTP_USER_AGENT'] ?? '',
            'original_context' => $systemContext,
        ];
        $sessionId = $this->sessionHandler->createUnifiedSession(
            $result['user_id'],
            $systemContext,
            $sessionData
        );
        $this->updateLastLogin($result['user_id']);

        return [
            'success' => true,
            'user_id' => $result['user_id'],
            'user_type' => $result['user_type'] ?? 'lupopedia',
            'user_data' => $result['user_data'] ?? null,
            'redirect' => '/dashboard',
        ];
    }

    private function attemptLupopediaAuth(string $email, string $password): array
    {
        $t = $this->db->quoteIdentifier(LUPO_TABLE_PREFIX . 'auth_users');
        $row = $this->db->fetchRow(
            "SELECT auth_user_id, email, display_name, password_hash FROM $t WHERE email = :email AND is_active = 1 AND (is_deleted = 0 OR is_deleted IS NULL)",
            ['email' => $email]
        );
        if (!$row) {
            return ['success' => false, 'message' => 'User not found'];
        }
        if (password_verify($password, $row['password_hash'] ?? '')) {
            return [
                'success' => true,
                'user_id' => $row['auth_user_id'],
                'user_type' => 'lupopedia',
                'user_data' => (object) $row,
            ];
        }
        return ['success' => false, 'message' => 'Invalid password'];
    }

    /**
     * Unified logout. $sessionId from session_id(). Returns ['redirect' => url].
     */
    public function unifiedLogout(string $sessionId = null): array
    {
        if ($sessionId === null && function_exists('session_id')) {
            $sessionId = session_id();
        }
        if ($sessionId) {
            $this->sessionHandler->destroyUnifiedSession($sessionId);
        }
        $path = isset($_SERVER['REQUEST_URI']) ? (string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '';
        $systemContext = $this->sessionHandler->detectSystemContext($path);
        return ['redirect' => '/login'];
    }

    /**
     * Update last login for lupo_auth_users (last_login_ymdhis).
     */
    private function updateLastLogin($userId): void
    {
        if ($userId === null || $userId === '') {
            return;
        }
        $now = (int) gmdate('YmdHis');
        $t = LUPO_TABLE_PREFIX . 'auth_users';
        $this->db->update($t, ['last_login_ymdhis' => $now], 'auth_user_id = :id', ['id' => $userId]);
    }

    /**
     * Get session info for JSON. Returns array with user_id, system_context, expires_ymdhis.
     */
    public function getSessionInfo(string $sessionId = null): array
    {
        if ($sessionId === null && function_exists('session_id')) {
            $sessionId = session_id();
        }
        $unified = $sessionId ? $this->sessionHandler->getUnifiedSession($sessionId) : null;
        if (!$unified) {
            return ['error' => 'No active session'];
        }
        return [
            'user_id' => $unified['user_id'],
            'system_context' => $unified['system_context'],
            'expires_ymdhis' => $unified['expires_ymdhis'] ?? null,
        ];
    }

    public function validateSession(string $sessionId = null): array
    {
        if ($sessionId === null && function_exists('session_id')) {
            $sessionId = session_id();
        }
        $valid = $sessionId ? $this->sessionHandler->validateSessionIntegrity($sessionId) : false;
        return ['valid' => $valid];
    }
}
