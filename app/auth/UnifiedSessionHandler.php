<?php

namespace App\Auth;

/**
 * Unified session handler — plain PHP, PDO, doctrine timestamps (BIGINT YmdHis UTC).
 * No Laravel. Requires: LUPO_TABLE_PREFIX, PDO_DB.
 *
 * Table: {prefix}sessions (same as App\Auth\Session). Method table() returns LUPO_TABLE_PREFIX . 'sessions'.
 * This handler is a thin wrapper: it performs its own INSERT/SELECT/UPDATE/DELETE on that table and manages
 * the "unified" cookie name (LUPO_TABLE_PREFIX . 'unified_session') and system_context. It does NOT use
 * the legacy unified_sessions table. Optional future refactor: delegate all DB work to Session and only
 * handle cookie + system_context. See docs/SESSIONS_VS_UNIFIED_SESSIONS_INVESTIGATION.md §5.1a C.
 *
 * Columns used: session_id, federation_node_id, actor_id, ip_address, user_agent, session_data, system_context,
 * name_key, is_named, last_seen_ymdhis, expires_ymdhis, created_ymdhis, updated_ymdhis.
 */

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

class UnifiedSessionHandler
{
    const CONTEXT_LUPOPEDIA = 'lupopedia';
    const CONTEXT_CRAFTY_SYNTAX = 'crafty_syntax';
    const CONTEXT_UNIFIED = 'unified';

    /** @var \PDO_DB */
    private $db;

    /** Session lifetime in minutes. Define LUPO_SESSION_LIFETIME_MINUTES or default 120. */
    private $sessionLifetimeMinutes;

    public function __construct($db)
    {
        $this->db = $db;
        $this->sessionLifetimeMinutes = defined('LUPO_SESSION_LIFETIME_MINUTES') ? (int) LUPO_SESSION_LIFETIME_MINUTES : 120;
    }

    private function table(): string
    {
        return LUPO_TABLE_PREFIX . 'sessions';
    }

    /** Current time as YmdHis bigint (UTC). Doctrine: BIGINT(14) YYYYMMDDHHIISS. */
    private function nowYmdHis(): int
    {
        return (int) gmdate('YmdHis');
    }

    /** Add minutes to a YmdHis timestamp (UTC). */
    private function addMinutesYmdHis(int $ymdhis, int $minutes): int
    {
        $s = str_pad((string) $ymdhis, 14, '0', STR_PAD_LEFT);
        $dt = \DateTime::createFromFormat('YmdHis', $s, new \DateTimeZone('UTC'));
        if (!$dt) {
            return $ymdhis;
        }
        $dt->modify('+' . (int) $minutes . ' minutes');
        return (int) $dt->format('YmdHis');
    }

    /**
     * Create or update session in lupo_sessions.
     * $sessionId: from PHP session_id() (caller must session_start() if needed).
     */
    public function createUnifiedSession($userId, $systemContext, $sessionData = [], $sessionId = null)
    {
        if ($sessionId === null && function_exists('session_id')) {
            $sessionId = session_id();
        }
        if ($sessionId === null || $sessionId === '') {
            return null;
        }

        $now = $this->nowYmdHis();
        $expiresYmdHis = $this->addMinutesYmdHis($now, $this->sessionLifetimeMinutes);
        $table = $this->table();

        $existing = $this->db->fetchRow(
            'SELECT session_id FROM ' . $this->db->quoteIdentifier($table) . ' WHERE session_id = :sid',
            ['sid' => $sessionId]
        );

        $row = [
            'federation_node_id' => 1,
            'actor_id' => $userId !== null ? (int) $userId : 0,
            'ip_address' => '',
            'user_agent' => '',
            'session_data' => is_string($sessionData) ? $sessionData : json_encode($sessionData),
            'last_seen_ymdhis' => $now,
            'expires_ymdhis' => $expiresYmdHis,
            'updated_ymdhis' => $now,
            'name_key' => null,
            'is_named' => 0,
        ];
        if ($this->hasSystemContextColumn()) {
            $row['system_context'] = $systemContext;
        }

        if ($existing) {
            $this->db->update($table, $row, 'session_id = :sid', ['sid' => $sessionId]);
        } else {
            $row['session_id'] = $sessionId;
            $row['created_ymdhis'] = $now;
            $this->db->insert($table, $row);
        }

        $this->setUnifiedCookie($sessionId, $systemContext);
        return $sessionId;
    }

    private function hasSystemContextColumn(): bool
    {
        static $has = null;
        if ($has !== null) {
            return $has;
        }
        $t = $this->table();
        $row = $this->db->fetchRow(
            "SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :t AND COLUMN_NAME = 'system_context'",
            ['t' => $t]
        );
        $has = (bool) $row;
        return $has;
    }

    /**
     * Get session data. Returns: user_id (actor_id), system_context, session_data, expires_ymdhis (int or null).
     */
    public function getUnifiedSession($sessionId)
    {
        $now = $this->nowYmdHis();
        $table = $this->table();
        $sql = 'SELECT * FROM ' . $this->db->quoteIdentifier($table)
            . ' WHERE session_id = :sid AND (expires_ymdhis IS NULL OR expires_ymdhis > :now)';
        $session = $this->db->fetchRow($sql, ['sid' => $sessionId, 'now' => $now]);

        if ($session) {
            $systemContext = self::CONTEXT_LUPOPEDIA;
            if (isset($session['system_context']) && $session['system_context'] !== null && $session['system_context'] !== '') {
                $systemContext = $session['system_context'];
            }
            return [
                'user_id' => !empty($session['actor_id']) ? (int) $session['actor_id'] : null,
                'system_context' => $systemContext,
                'session_data' => !empty($session['session_data']) ? json_decode($session['session_data'], true) : [],
                'expires_ymdhis' => isset($session['expires_ymdhis']) ? (int) $session['expires_ymdhis'] : null,
                'session_id' => $session['session_id'],
                'name_key' => isset($session['name_key']) ? (string) $session['name_key'] : null,
                'is_named' => isset($session['is_named']) ? (int) $session['is_named'] : 0,
            ];
        }

        return null;
    }

    public function migrateExistingSession($userId, $legacyContext, $sessionId = null)
    {
        if ($sessionId === null && function_exists('session_id')) {
            $sessionId = session_id();
        }
        $existing = $sessionId ? $this->getUnifiedSession($sessionId) : null;
        if (!$existing) {
            return $this->createUnifiedSession($userId, $legacyContext, [], $sessionId);
        }
        return $sessionId;
    }

    private function cookieName(): string
    {
        return LUPO_TABLE_PREFIX . 'unified_session';
    }

    private function setUnifiedCookie($sessionId, $systemContext)
    {
        $name = $this->cookieName();
        $value = json_encode([
            'session_id' => $sessionId,
            'context' => $systemContext,
            'ymdhis' => $this->nowYmdHis(),
        ]);
        $lifetime = $this->sessionLifetimeMinutes * 60;
        setcookie($name, $value, time() + $lifetime, '/', '', true, true);
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

    public function destroyUnifiedSession($sessionId)
    {
        $table = $this->table();
        $this->db->delete($table, 'session_id = :sid', ['sid' => $sessionId]);
        setcookie($this->cookieName(), '', time() - 3600, '/', '', true, true);
    }

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
        $now = $this->nowYmdHis();
        $table = $this->table();
        $this->db->delete($table, 'expires_ymdhis IS NOT NULL AND expires_ymdhis <= :now', ['now' => $now]);
    }

    public function getActiveSessionsForUser($userId)
    {
        $now = $this->nowYmdHis();
        $table = $this->table();
        return $this->db->fetchAll(
            'SELECT * FROM ' . $this->db->quoteIdentifier($table)
            . ' WHERE actor_id = :uid AND (expires_ymdhis IS NULL OR expires_ymdhis > :now) ORDER BY updated_ymdhis DESC',
            ['uid' => $userId, 'now' => $now]
        );
    }

    public function validateSessionIntegrity($sessionId)
    {
        return $this->getUnifiedSession($sessionId) !== null;
    }
}
