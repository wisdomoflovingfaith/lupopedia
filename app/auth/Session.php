<?php

namespace App\Auth;

/**
 * Session — OOP session management. Replaces procedural session helpers.
 *
 * Constructor injection: ($db, $sessionHandler). Uses PDO/PDO_DB and UnifiedSessionHandler.
 * Doctrine: BIGINT UTC YmdHis for all timestamps; no DB-side logic.
 * Table: lupo_sessions (TOON). Columns after security_level: name_key (VARCHAR(100) NULL), is_named (TINYINT default 0).
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded. Session cannot be used directly.');
}
$session_compat_path = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'session-compat-5.3.php' : dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'session-compat-5.3.php';
if (is_file($session_compat_path)) {
    require_once $session_compat_path;
}

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

if (!defined('LUPO_SESSION_LIFETIME')) {
    define('LUPO_SESSION_LIFETIME', 86400);
}

if (!defined('LUPO_DEFAULT_NODE_ID')) {
    define('LUPO_DEFAULT_NODE_ID', 1);
}

class Session
{
    /** @var \PDO|\PDO_DB */
    private $db;

    /** @var UnifiedSessionHandler */
    private $sessionHandler;

    /** @var string */
    private $table;

    /** @var string|null name_key from lupo_sessions (visitor/customer name). Populated when session is loaded. */
    private $name_key;

    /** @var int is_named from lupo_sessions (0 or 1). Populated when session is loaded. */
    private $is_named = 0;

    public function __construct($db, $sessionHandler)
    {
        $this->db = $db;
        $this->sessionHandler = $sessionHandler;
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $this->table = $table_prefix . 'sessions';
    }

    /**
     * Current UTC timestamp as BIGINT YmdHis.
     */
    public function utcTimestamp()
    {
        if (class_exists('timestamp_ymdhis')) {
            return timestamp_ymdhis::now();
        }
        return (int) gmdate('YmdHis');
    }

    /**
     * Start PHP session if not already started.
     */
    public function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            return session_start();
        }
        return true;
    }

    /**
     * Current PHP session ID (starts session if needed).
     *
     * @return string|false
     */
    public function getSessionId()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_name('PHPSESSID');
        }
        $this->start();
        return session_id();
    }

    /**
     * Client IP (handles X-Forwarded-For, X-Real-IP).
     */
    public function getClientIp()
    {
        $keys = array('HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');
        foreach ($keys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $parts = explode(',', $ip);
                    $ip = trim($parts[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        return '0.0.0.0';
    }

    public function getUserAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }

    /**
     * Device type from user agent: desktop, mobile, tablet, bot, other.
     */
    public function detectDeviceType($userAgent = null)
    {
        $ua = $userAgent !== null ? $userAgent : $this->getUserAgent();
        if ($ua === '') {
            return 'other';
        }
        $bot = array('bot', 'crawler', 'spider', 'scraper', 'curl', 'wget');
        foreach ($bot as $p) {
            if (stripos($ua, $p) !== false) {
                return 'bot';
            }
        }
        $mobile = array('mobile', 'android', 'iphone', 'ipod', 'blackberry', 'windows phone');
        foreach ($mobile as $p) {
            if (stripos($ua, $p) !== false) {
                return 'mobile';
            }
        }
        $tablet = array('ipad', 'tablet', 'playbook');
        foreach ($tablet as $p) {
            if (stripos($ua, $p) !== false) {
                return 'tablet';
            }
        }
        return 'desktop';
    }

    /**
     * Create or upgrade session in lupo_sessions for the current PHP session.
     *
     * @param int $actorId 0 for anonymous
     * @return string|false Session ID on success
     */
    public function createSession($actorId, $authMethod = 'password', $authProvider = 'local')
    {
        $this->start();
        $sessionId = session_id();
        if ($sessionId === '' || $sessionId === false) {
            return false;
        }
        if (strlen($sessionId) > 255) {
            $sessionId = substr($sessionId, 0, 255);
        }
        $now = $this->utcTimestamp();
        if (class_exists('timestamp_ymdhis')) {
            $expires = \timestamp_ymdhis::addSeconds($now, LUPO_SESSION_LIFETIME);
        } else {
            $s = str_pad((string) $now, 14, '0', STR_PAD_LEFT);
            $epoch = gmmktime(
                (int) substr($s, 8, 2),
                (int) substr($s, 10, 2),
                (int) substr($s, 12, 2),
                (int) substr($s, 4, 2),
                (int) substr($s, 6, 2),
                (int) substr($s, 0, 4)
            );
            $expires = (int) gmdate('YmdHis', $epoch + LUPO_SESSION_LIFETIME);
        }
        $ip = $this->getClientIp();
        $userAgent = $this->getUserAgent();
        $deviceType = $this->detectDeviceType($userAgent);
        $securityLevel = ($authMethod === 'api_key') ? 'high' : (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'high' : 'medium');

        if (!($this->db instanceof \PDO_DB)) {
            return false;
        }
        $existing = $this->db->fetchRow(
            "SELECT session_id, actor_id, name_key, is_named FROM {$this->table} WHERE session_id = :sid LIMIT 1",
            array('sid' => $sessionId)
        );
        $nameKey = null;
        $isNamed = 0;
        if ($existing) {
            $nameKey = isset($existing['name_key']) ? (string) $existing['name_key'] : null;
            $isNamed = isset($existing['is_named']) ? (int) $existing['is_named'] : 0;
        }

        try {
            $data = array(
                'actor_id' => $actorId,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'device_type' => $deviceType,
                'auth_method' => $authMethod,
                'auth_provider' => $authProvider,
                'security_level' => $securityLevel,
                'name_key' => $nameKey,
                'is_named' => $isNamed,
                'is_active' => 1,
                'is_expired' => 0,
                'is_revoked' => 0,
                'login_ymdhis' => $now,
                'last_seen_ymdhis' => $now,
                'expires_ymdhis' => $expires,
                'updated_ymdhis' => $now,
            );
            if ($existing) {
                $this->db->update($this->table, $data, 'session_id = :sid', array('sid' => $sessionId));
            } else {
                $data['session_id'] = $sessionId;
                $data['federation_node_id'] = LUPO_DEFAULT_NODE_ID;
                $data['created_ymdhis'] = $now;
                $data['is_deleted'] = 0;
                $this->db->insert($this->table, $data);
            }
            $this->name_key = $nameKey;
            $this->is_named = $isNamed;
            return $sessionId;
        } catch (\Exception $e) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log('Session::createSession: ' . $e->getMessage());
            }
            return false;
        }
    }

    /**
     * Validate session: exists, active, not expired, not revoked. Updates last_seen if valid.
     *
     * @param string|null $sessionId Defaults to current PHP session
     * @return int|false Actor ID if valid
     */
    public function validateSession($sessionId = null)
    {
        if ($sessionId === null) {
            $sessionId = $this->getSessionId();
        }
        if ($sessionId === '' || $sessionId === false) {
            return false;
        }
        $now = $this->utcTimestamp();
        if (!($this->db instanceof \PDO_DB)) {
            return false;
        }
        $session = $this->db->fetchRow(
            "SELECT actor_id, is_active, is_expired, is_revoked, expires_ymdhis, name_key, is_named FROM {$this->table} WHERE session_id = :sid AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('sid' => $sessionId)
        );
        if (!$session) {
            return false;
        }
        $this->name_key = isset($session['name_key']) ? (string) $session['name_key'] : null;
        $this->is_named = isset($session['is_named']) ? (int) $session['is_named'] : 0;
        if ((int) $session['is_active'] !== 1 || (int) $session['is_expired'] === 1 || (int) $session['is_revoked'] === 1) {
            return false;
        }
        $expires = (int) $session['expires_ymdhis'];
        if ($expires > 0 && $expires < $now) {
            $this->markExpired($sessionId);
            return false;
        }
        $this->updateActivity($sessionId);
        return (int) $session['actor_id'];
    }

    /**
     * Get last_seen_ymdhis for a session (for idle checks).
     *
     * @param string|null $sessionId
     * @return int|null
     */
    public function getLastSeenYmdhis($sessionId = null)
    {
        if ($sessionId === null) {
            $sessionId = $this->getSessionId();
        }
        if ($sessionId === '' || $sessionId === false) {
            return null;
        }
        if (!($this->db instanceof \PDO_DB)) {
            return null;
        }
        $row = $this->db->fetchRow(
            "SELECT last_seen_ymdhis, name_key, is_named FROM {$this->table} WHERE session_id = :sid AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('sid' => $sessionId)
        );
        if ($row) {
            $this->name_key = isset($row['name_key']) ? (string) $row['name_key'] : null;
            $this->is_named = isset($row['is_named']) ? (int) $row['is_named'] : 0;
        }
        return $row && isset($row['last_seen_ymdhis']) ? (int) $row['last_seen_ymdhis'] : null;
    }

    /**
     * Name a session (e.g. visitor/customer name in Crafty Syntax). Sets is_named = 1 and name_key = $nameKey.
     *
     * @param string $nameKey Display name (stored in name_key; max 100 chars per TOON)
     * @return bool
     */
    public function setNameKey($nameKey)
    {
        $sessionId = $this->getSessionId();
        if ($sessionId === '' || $sessionId === false) {
            return false;
        }
        $nameKey = substr($nameKey, 0, 100);
        $now = $this->utcTimestamp();
        if (!($this->db instanceof \PDO_DB)) {
            return false;
        }
        try {
            $this->db->update(
                $this->table,
                array('name_key' => $nameKey, 'is_named' => 1, 'updated_ymdhis' => $now),
                'session_id = :sid AND (is_deleted = 0 OR is_deleted IS NULL)',
                array('sid' => $sessionId)
            );
            $this->name_key = $nameKey;
            $this->is_named = 1;
            return true;
        } catch (\Exception $e) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log('Session::setNameKey: ' . $e->getMessage());
            }
            return false;
        }
    }

    /** @return string|null */
    public function getNameKey()
    {
        return $this->name_key;
    }

    /** @return int 0 or 1 */
    public function getIsNamed()
    {
        return $this->is_named;
    }

    /**
     * Update last_seen_ymdhis and updated_ymdhis for session.
     *
     * @param string|null $sessionId
     */
    public function updateActivity($sessionId = null)
    {
        if ($sessionId === null) {
            $sessionId = $this->getSessionId();
        }
        if ($sessionId === '' || $sessionId === false) {
            return false;
        }
        $now = $this->utcTimestamp();
        if (!($this->db instanceof \PDO_DB)) {
            return false;
        }
        $this->db->update(
            $this->table,
            array('last_seen_ymdhis' => $now, 'updated_ymdhis' => $now),
            'session_id = :sid AND (is_deleted = 0 OR is_deleted IS NULL)',
            array('sid' => $sessionId)
        );
        return true;
    }

    /**
     * Mark session as expired (is_active = 0, is_expired = 1).
     *
     * @param string $sessionId
     */
    public function markExpired($sessionId)
    {
        $now = $this->utcTimestamp();
        if (!($this->db instanceof \PDO_DB)) {
            return false;
        }
        $this->db->update(
            $this->table,
            array('is_expired' => 1, 'is_active' => 0, 'updated_ymdhis' => $now),
            'session_id = :sid AND (is_deleted = 0 OR is_deleted IS NULL)',
            array('sid' => $sessionId)
        );
        return true;
    }

    /**
     * Mark session inactive/revoked and destroy PHP session.
     *
     * @param string|null $sessionId
     */
    public function destroy($sessionId = null)
    {
        if ($sessionId === null) {
            $sessionId = $this->getSessionId();
        }
        if ($sessionId === '' || $sessionId === false) {
            return false;
        }
        $now = $this->utcTimestamp();
        if ($this->db instanceof \PDO_DB) {
            $this->db->update(
                $this->table,
                array('is_active' => 0, 'is_revoked' => 1, 'updated_ymdhis' => $now),
                'session_id = :sid AND (is_deleted = 0 OR is_deleted IS NULL)',
                array('sid' => $sessionId)
            );
        }
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = array();
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 3600, '/');
            }
            session_destroy();
        }
        return true;
    }

    /**
     * Get session row by session ID (for legacy/Crafty callers). Uses PDO_DB fetchRow.
     *
     * @param string $sessionId
     * @return array|null Row with keys e.g. session_id, actor_id, last_seen_ymdhis, name_key, is_named
     */
    public function getSessionRow($sessionId)
    {
        if (!($this->db instanceof \PDO_DB)) {
            return null;
        }
        $t = $this->db->quoteIdentifier($this->table);
        $row = $this->db->fetchRow(
            "SELECT session_id, actor_id, last_seen_ymdhis, name_key, is_named FROM $t WHERE session_id = :sid AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('sid' => $sessionId)
        );
        if ($row) {
            $this->name_key = isset($row['name_key']) ? (string) $row['name_key'] : null;
            $this->is_named = isset($row['is_named']) ? (int) $row['is_named'] : 0;
        }
        return $row;
    }

    /**
     * Session lifetime in minutes for unified/cookie flow. LUPO_SESSION_LIFETIME_MINUTES or 120.
     */
    private function getSessionLifetimeMinutes()
    {
        return defined('LUPO_SESSION_LIFETIME_MINUTES') ? (int) LUPO_SESSION_LIFETIME_MINUTES : 120;
    }

    /** Add minutes to a YmdHis timestamp (UTC). */
    private function addMinutesYmdHis($ymdhis, $minutes)
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
     * Whether lupo_sessions has system_context column (migration add_system_context_to_lupo_sessions).
     */
    public function hasSystemContextColumn()
    {
        static $has = null;
        if ($has !== null) {
            return $has;
        }
        if (!($this->db instanceof \PDO_DB)) {
            return false;
        }
        $t = $this->table;
        $row = $this->db->fetchRow(
            "SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :t AND COLUMN_NAME = 'system_context'",
            array('t' => $t)
        );
        $has = (bool) $row;
        return $has;
    }

    /**
     * Create or update a session row for the unified/cookie flow (actor_id, system_context, session_data, expires).
     * Used by UnifiedSessionHandler; does not set cookie. Table: {prefix}sessions only.
     *
     * @param string      $sessionId     PHP session_id()
     * @param int|null    $userId        actor_id (0 for anonymous)
     * @param string      $systemContext CONTEXT_LUPOPEDIA | CONTEXT_CRAFTY_SYNTAX | CONTEXT_UNIFIED
     * @param array|string $sessionData  JSON-encoded or array
     * @return string|null session_id on success
     */
    public function createOrUpdateForUnified($sessionId, $userId, $systemContext, $sessionData = array())
    {
        if ($sessionId === '' || !($this->db instanceof \PDO_DB)) {
            return null;
        }
        $now = $this->utcTimestamp();
        $expiresYmdHis = $this->addMinutesYmdHis($now, $this->getSessionLifetimeMinutes());
        $t = $this->db->quoteIdentifier($this->table);

        $existing = $this->db->fetchRow(
            'SELECT session_id FROM ' . $t . ' WHERE session_id = :sid',
            array('sid' => $sessionId)
        );

        $row = array(
            'federation_node_id' => defined('LUPO_DEFAULT_NODE_ID') ? (int) LUPO_DEFAULT_NODE_ID : 1,
            'actor_id' => $userId !== null ? (int) $userId : 0,
            'ip_address' => '',
            'user_agent' => '',
            'session_data' => is_string($sessionData) ? $sessionData : json_encode($sessionData),
            'last_seen_ymdhis' => $now,
            'expires_ymdhis' => $expiresYmdHis,
            'updated_ymdhis' => $now,
            'name_key' => null,
            'is_named' => 0,
        );
        if ($this->hasSystemContextColumn()) {
            $row['system_context'] = $systemContext;
        }

        if ($existing) {
            $this->db->update($this->table, $row, 'session_id = :sid', array('sid' => $sessionId));
        } else {
            $row['session_id'] = $sessionId;
            $row['created_ymdhis'] = $now;
            $this->db->insert($this->table, $row);
        }
        return $sessionId;
    }

    /**
     * Get session in unified shape: user_id (actor_id), system_context, session_data, expires_ymdhis, session_id, name_key, is_named.
     * Returns null if not found or expired. Table: {prefix}sessions only.
     */
    public function getSessionForUnified($sessionId)
    {
        if (!($this->db instanceof \PDO_DB)) {
            return null;
        }
        $now = $this->utcTimestamp();
        $t = $this->db->quoteIdentifier($this->table);
        $session = $this->db->fetchRow(
            'SELECT * FROM ' . $t . ' WHERE session_id = :sid AND (expires_ymdhis IS NULL OR expires_ymdhis > :now)',
            array('sid' => $sessionId, 'now' => $now)
        );
        if (!$session) {
            return null;
        }
        $systemContext = UnifiedSessionHandler::CONTEXT_LUPOPEDIA;
        if (isset($session['system_context']) && $session['system_context'] !== null && $session['system_context'] !== '') {
            $systemContext = $session['system_context'];
        }
        return array(
            'user_id' => !empty($session['actor_id']) ? (int) $session['actor_id'] : null,
            'system_context' => $systemContext,
            'session_data' => !empty($session['session_data']) ? json_decode($session['session_data'], true) : array(),
            'expires_ymdhis' => isset($session['expires_ymdhis']) ? (int) $session['expires_ymdhis'] : null,
            'session_id' => $session['session_id'],
            'name_key' => isset($session['name_key']) ? (string) $session['name_key'] : null,
            'is_named' => isset($session['is_named']) ? (int) $session['is_named'] : 0,
        );
    }

    /**
     * Delete session row by session_id (used by unified logout). Table: {prefix}sessions.
     */
    public function deleteSessionRow($sessionId)
    {
        if (!($this->db instanceof \PDO_DB)) {
            return;
        }
        $this->db->delete($this->table, 'session_id = :sid', array('sid' => $sessionId));
    }

    /**
     * Remove expired session rows (expires_ymdhis <= now). Table: {prefix}sessions.
     */
    public function cleanupExpiredSessions()
    {
        if (!($this->db instanceof \PDO_DB)) {
            return;
        }
        $now = $this->utcTimestamp();
        $this->db->delete($this->table, 'expires_ymdhis IS NOT NULL AND expires_ymdhis <= :now', array('now' => $now));
    }

    /**
     * Active (non-expired) session rows for an actor. Table: {prefix}sessions.
     *
     * @param int $userId actor_id
     * @return array[] rows
     */
    public function getActiveSessionsForUser($userId)
    {
        if (!($this->db instanceof \PDO_DB)) {
            return array();
        }
        $now = $this->utcTimestamp();
        $t = $this->db->quoteIdentifier($this->table);
        return $this->db->fetchAll(
            'SELECT * FROM ' . $t . ' WHERE actor_id = :uid AND (expires_ymdhis IS NULL OR expires_ymdhis > :now) ORDER BY updated_ymdhis DESC',
            array('uid' => $userId, 'now' => $now)
        );
    }

    /**
     * Whether a session exists and is not expired (unified shape check).
     */
    public function validateSessionIntegrity($sessionId)
    {
        return $this->getSessionForUnified($sessionId) !== null;
    }

    public function getSessionHandler()
    {
        return $this->sessionHandler;
    }

    public function getDb()
    {
        return $this->db;
    }
}
