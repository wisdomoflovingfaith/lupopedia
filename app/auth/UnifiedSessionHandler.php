<?php

namespace App\Auth;

/**
 * Unified session handler — thin wrapper for cookie + context only.
 *
 * Table: obsolete. This handler does NOT use {prefix}unified_sessions (dropped).
 * All session storage uses {prefix}sessions via App\Auth\Session. This class:
 * - Reads/writes the unified cookie (LUPO_TABLE_PREFIX . 'unified_session')
 * - Detects system_context from path (lupopedia vs crafty_syntax)
 * - Delegates all DB operations to Session (createOrUpdateForUnified, getSessionForUnified,
 *   deleteSessionRow, cleanupExpiredSessions, getActiveSessionsForUser, validateSessionIntegrity).
 *
 * Requires Session to be set via setSession() (bootstrap) or available as $GLOBALS['lupo_session'].
 */

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

class UnifiedSessionHandler
{
    const CONTEXT_LUPOPEDIA = 'lupopedia';
    const CONTEXT_CRAFTY_SYNTAX = 'crafty_syntax';
    const CONTEXT_UNIFIED = 'unified';

    /** @var Session|null Injected by bootstrap so all DB is delegated to Session. */
    private $session;

    /** Session lifetime in minutes for cookie. LUPO_SESSION_LIFETIME_MINUTES or 120. */
    private $sessionLifetimeMinutes;

    public function __construct($db = null)
    {
        $this->sessionLifetimeMinutes = defined('LUPO_SESSION_LIFETIME_MINUTES') ? (int) LUPO_SESSION_LIFETIME_MINUTES : 120;
    }

    /**
     * Set Session instance so all DB operations are delegated. Called by bootstrap after creating Session.
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * Session to use for DB. Prefer injected session; fallback to global (e.g. AuthController without setSession).
     */
    private function getSession()
    {
        if ($this->session !== null) {
            return $this->session;
        }
        return isset($GLOBALS['lupo_session']) && $GLOBALS['lupo_session'] instanceof Session ? $GLOBALS['lupo_session'] : null;
    }

    private function cookieName()
    {
        return LUPO_TABLE_PREFIX . 'unified_session';
    }

    private function setUnifiedCookie($sessionId, $systemContext)
    {
        $name = $this->cookieName();
        $value = json_encode(array(
            'session_id' => $sessionId,
            'context' => $systemContext,
            'ymdhis' => (int) gmdate('YmdHis'),
        ));
        $lifetime = $this->sessionLifetimeMinutes * 60;
        setcookie($name, $value, time() + $lifetime, '/', '', true, true);
    }

    /**
     * Create or update session in lupo_sessions via Session, then set cookie.
     */
    public function createUnifiedSession($userId, $systemContext, $sessionData = array(), $sessionId = null)
    {
        if ($sessionId === null && function_exists('session_id')) {
            $sessionId = session_id();
        }
        if ($sessionId === null || $sessionId === '') {
            return null;
        }
        $session = $this->getSession();
        if (!$session) {
            return null;
        }
        $session->createOrUpdateForUnified($sessionId, $userId, $systemContext, $sessionData);
        $this->setUnifiedCookie($sessionId, $systemContext);
        return $sessionId;
    }

    /**
     * Get session data from lupo_sessions via Session. Returns unified shape: user_id, system_context, session_data, etc.
     */
    public function getUnifiedSession($sessionId)
    {
        $session = $this->getSession();
        return $session ? $session->getSessionForUnified($sessionId) : null;
    }

    public function migrateExistingSession($userId, $legacyContext, $sessionId = null)
    {
        if ($sessionId === null && function_exists('session_id')) {
            $sessionId = session_id();
        }
        $existing = $sessionId ? $this->getUnifiedSession($sessionId) : null;
        if (!$existing) {
            return $this->createUnifiedSession($userId, $legacyContext, array(), $sessionId);
        }
        return $sessionId;
    }

    public function getUnifiedSessionFromCookie()
    {
        $name = $this->cookieName();
        $cookieValue = isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
        if ($cookieValue) {
            $data = json_decode($cookieValue, true);
            if (!empty($data['session_id'])) {
                return $this->getUnifiedSession($data['session_id']);
            }
        }
        return null;
    }

    /**
     * Delete session row via Session and clear cookie.
     */
    public function destroyUnifiedSession($sessionId)
    {
        $session = $this->getSession();
        if ($session) {
            $session->deleteSessionRow($sessionId);
        }
        setcookie($this->cookieName(), '', time() - 3600, '/', '', true, true);
    }

    /**
     * Detect system context from path (no DB). lupopedia vs crafty_syntax.
     */
    public function detectSystemContext($pathOrRequest = null)
    {
        $path = '';
        if (is_string($pathOrRequest)) {
            $path = $pathOrRequest;
        } elseif (is_object($pathOrRequest) && method_exists($pathOrRequest, 'path')) {
            $path = $pathOrRequest->path();
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $path = (string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        }
        if (strpos($path, 'livehelp') !== false || strpos($path, 'crafty_syntax') !== false || strpos($path, 'legacy/') === 0) {
            return self::CONTEXT_CRAFTY_SYNTAX;
        }
        return self::CONTEXT_LUPOPEDIA;
    }

    public function cleanupExpiredSessions()
    {
        $session = $this->getSession();
        if ($session) {
            $session->cleanupExpiredSessions();
        }
    }

    public function getActiveSessionsForUser($userId)
    {
        $session = $this->getSession();
        return $session ? $session->getActiveSessionsForUser($userId) : array();
    }

    public function validateSessionIntegrity($sessionId)
    {
        $session = $this->getSession();
        return $session ? $session->validateSessionIntegrity($sessionId) : false;
    }

    /**
     * Delegate session activity update (last_seen_ymdhis, updated_ymdhis) to Session.
     */
    public function updateSessionActivity($sessionId)
    {
        $session = $this->getSession();
        return $session ? $session->updateActivity($sessionId) : false;
    }
}
