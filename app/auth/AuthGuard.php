<?php

namespace App\Auth;

/**
 * Auth guard — plain PHP, PDO. No Laravel, no middleware.
 * Constructor: ($db). Call isAllowed(), updateUserActivity(), getUnifiedUser() from your front controller.
 */

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

class AuthGuard
{
    /** @var \PDO_DB */
    private $db;

    /** @var UnifiedSessionHandler */
    protected $sessionHandler;

    /** @var AuthManager */
    protected $authManager;

    public function __construct($db)
    {
        $this->db = $db;
        $this->sessionHandler = new UnifiedSessionHandler($db);
        $this->authManager = new AuthManager($db, $this->sessionHandler);
    }

    /**
     * Detect system context from path. $pathOrRequest: string path or object with path().
     */
    public function detectSystemContext($pathOrRequest = null)
    {
        return $this->sessionHandler->detectSystemContext($pathOrRequest);
    }

    /**
     * Check if request is authenticated.
     */
    public function isAllowed()
    {
        return $this->authManager->checkUnifiedAuth();
    }

    /**
     * Get unified user or null.
     */
    public function getUnifiedUser()
    {
        return $this->authManager->getUnifiedUser();
    }

    /**
     * Log authentication activity. $requestInfo: array with ip, user_agent (or null for $_SERVER).
     */
    public function logAuthenticationActivity($unifiedUser, $systemContext, $requestInfo = null)
    {
        if ($requestInfo === null) {
            $requestInfo = array(
                'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
                'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
            );
        }
        $userId = isset($unifiedUser['user']->id) ? $unifiedUser['user']->id : null;
        $this->authManager->logAuthEvent('middleware_access', $userId, null, $systemContext, true, null, $requestInfo);
    }

    /**
     * Update session last_seen_ymdhis in lupo_sessions via Session. $sessionId from session_id().
     */
    public function updateUserActivity($sessionId, $unifiedUser, $systemContext)
    {
        $this->sessionHandler->updateSessionActivity($sessionId);
    }

    public function getSessionHandler()
    {
        return $this->sessionHandler;
    }

    public function getAuthManager()
    {
        return $this->authManager;
    }
}
