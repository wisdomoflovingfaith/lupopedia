<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: class
  when_updated: "20260406012647"
  file_path_from_root: "app/auth/Session.php"
  web_path: "http://www.lupopedia.com/lupopedia/app/auth/Session.php"
  last_modified_utc: "20260406012647"
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "class"
  artifact_kind: "auth_session"
  purpose: "Canonical Session class under app/auth; Model A lupo_sessions; createEmbedSession for Eye cookie without PHP session rotation."
  tags: ["session", "auth", "pdo_db", "lupo_sessions", "timestamp_ymdhis"]
---
*/

namespace App\Auth;

/**
 * Session — Model A: DB-backed session authority.
 *
 * Browser stores only session_id. All identity (actor_id, roles, CSRF) lives in lupo_sessions.
 * Never use $_SESSION['actor_id']. Resolve via Session::loadById($db, session_id()); $session->actor_id.
 *
 * Table: lupo_sessions (see install_new_lupopedia.sql): session_id, actor_id, federation_node_id, ip_hash,
 * ua_hash, csrf_token, last_activity_ymdhis, last_seen_ymdhis, created_ymdhis, updated_ymdhis, name_key, is_named, …
 *
 * Session token exception (constitutional): session_id is a non-numeric cryptographic string (random_bytes),
 * not a BIGINT from IdGenerator — security tokens are out of scope for numeric PK doctrine on entities.
 *
 * Cookie expiry: setcookie() uses Unix time per HTTP/browser API only; stored session times remain packed UTC BIGINT.
 *
 * $_SESSION clearing here is revocation cleanup only; authority remains lupo_sessions rows, not superglobal payload.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded. Session cannot be used directly.');
}
$session_compat_path = '';
if (defined('LUPOPEDIA_PATH')) {
    $session_compat_path = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'session-compat-5.6.php';
}
if ($session_compat_path === '' || !is_file($session_compat_path)) {
    $repoRoot = dirname(dirname(__DIR__));
    $session_compat_path = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'session-compat-5.6.php';
}
if (is_file($session_compat_path)) {
    require_once $session_compat_path;
}

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

if (!defined('LUPO_DEFAULT_NODE_ID')) {
    define('LUPO_DEFAULT_NODE_ID', 1);
}

class Session
{
    /** @var \PDO_DB|null */
    private $db;

    /** @var SessionHandler|null */
    private $sessionHandler;

    /** @var string */
    private $table;

    /** @var Session|null Loaded session after validateSession() or createSession() */
    private $current;

    /** @var string */
    public $session_id;

    /** @var int */
    public $actor_id;

    /** @var int */
    public $federation_node_id;

    /** @var string|null */
    public $ip_hash;

    /** @var string|null */
    public $ua_hash;

    /** @var string|null */
    public $csrf_token;

    /** @var int */
    public $last_activity_ymdhis;

    /** @var int */
    public $created_ymdhis;

    /** @var int */
    public $updated_ymdhis;

    /** @var int|null Packed UTC last_seen_ymdhis from DB (heartbeat); null if never set */
    public $last_seen_ymdhis = null;

    /** @var string|null */
    public $name_key;

    /** @var int */
    public $is_named = 0;

    /**
     * §17.8 — Untrusted server superglobal (fingerprint inputs only; hashed before persistence).
     *
     * @return array
     */
    private static function untrustedServerArray()
    {
        return (isset($_SERVER) && is_array($_SERVER)) ? $_SERVER : array();
    }

    /**
     * §17.8 — Untrusted cookie superglobal (session name lookup for logout cleanup only).
     *
     * @return array
     */
    private static function untrustedCookieArray()
    {
        return (isset($_COOKIE) && is_array($_COOKIE)) ? $_COOKIE : array();
    }

    /**
     * @return array Keys: ip (string), user_agent (string)
     */
    private static function untrustedFingerprintSources()
    {
        $srv = self::untrustedServerArray();
        return array(
            'ip' => isset($srv['REMOTE_ADDR']) ? (string) $srv['REMOTE_ADDR'] : '',
            'user_agent' => isset($srv['HTTP_USER_AGENT']) ? (string) $srv['HTTP_USER_AGENT'] : '',
        );
    }

    /**
     * @param string $ip
     * @param string $user_agent
     * @return array Keys: ip_hash, ua_hash
     */
    private static function hashFingerprint($ip, $user_agent)
    {
        if (function_exists('hash') && function_exists('hash_algos') && in_array('sha256', hash_algos())) {
            return array(
                'ip_hash' => hash('sha256', $ip),
                'ua_hash' => hash('sha256', $user_agent),
            );
        }
        return array(
            'ip_hash' => md5($ip),
            'ua_hash' => md5($user_agent),
        );
    }

    public function __construct($db, $sessionHandler = null)
    {
        $this->db = $db;
        $this->sessionHandler = $sessionHandler;
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $this->table = $table_prefix . 'sessions';
    }

    /**
     * Load session from DB by id. Returns Session instance with identity data or null.
     *
     * @param \PDO_DB $db
     * @param string $session_id
     * @return Session|null
     */
    public static function loadById($db, $session_id)
    {
        if (!$session_id || !($db instanceof \PDO_DB)) {
            return null;
        }
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $table = $table_prefix . 'sessions';
        $t = $db->quoteIdentifier($table);
        $row = $db->fetchRow(
            "SELECT session_id, actor_id, federation_node_id, ip_hash, ua_hash, csrf_token, last_activity_ymdhis, last_seen_ymdhis, created_ymdhis, updated_ymdhis, name_key, is_named, metadata FROM $t WHERE session_id = :sid LIMIT 1",
            array('sid' => $session_id)
        );
        if (!$row) {
            return null;
        }
        $s = new self($db, null);
        $s->session_id = $row['session_id'];
        $s->actor_id = (int) $row['actor_id'];
        $s->federation_node_id = (int) (isset($row['federation_node_id']) ? $row['federation_node_id'] : 0);
        $s->ip_hash = isset($row['ip_hash']) ? $row['ip_hash'] : null;
        $s->ua_hash = isset($row['ua_hash']) ? $row['ua_hash'] : null;
        $s->csrf_token = isset($row['csrf_token']) ? $row['csrf_token'] : null;
        $s->last_activity_ymdhis = (int) $row['last_activity_ymdhis'];
        $s->last_seen_ymdhis = (isset($row['last_seen_ymdhis']) && $row['last_seen_ymdhis'] !== null && $row['last_seen_ymdhis'] !== '')
            ? (int) $row['last_seen_ymdhis'] : null;
        $s->created_ymdhis = (int) $row['created_ymdhis'];
        $s->updated_ymdhis = (int) $row['updated_ymdhis'];
        $s->name_key = isset($row['name_key']) ? $row['name_key'] : null;
        $s->is_named = isset($row['is_named']) ? (int) $row['is_named'] : 0;
        return $s;
    }

    /**
     * Decode lupo_sessions.metadata JSON for a session_id (Model A: transient flags, not actor authority).
     *
     * @param \PDO_DB $db
     * @param string $session_id
     * @return array
     */
    public static function getDecodedMetadata($db, $session_id)
    {
        if (!$session_id || !($db instanceof \PDO_DB)) {
            return array();
        }
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $table = $table_prefix . 'sessions';
        $t = $db->quoteIdentifier($table);
        $row = $db->fetchRow(
            "SELECT metadata FROM $t WHERE session_id = :sid LIMIT 1",
            array('sid' => $session_id)
        );
        if (!$row || !isset($row['metadata']) || $row['metadata'] === null || $row['metadata'] === '') {
            return array();
        }
        $raw = $row['metadata'];
        if (is_array($raw)) {
            return $raw;
        }
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            return is_array($decoded) ? $decoded : array();
        }
        return array();
    }

    /**
     * Merge keys into lupo_sessions.metadata (JSON). Null patch values remove keys.
     *
     * @param \PDO_DB $db
     * @param string $session_id
     * @param array $patch
     * @return bool
     */
    public static function mergeSessionMetadata($db, $session_id, array $patch)
    {
        if (!$session_id || !($db instanceof \PDO_DB)) {
            return false;
        }
        if (!class_exists('\\timestamp_ymdhis', false) && defined('LUPOPEDIA_PATH')) {
            require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'TimestampYmdhis.php';
        }
        $now = class_exists('\\timestamp_ymdhis', false) ? (int) \timestamp_ymdhis::now() : (int) gmdate('YmdHis');
        $meta = self::getDecodedMetadata($db, $session_id);
        foreach ($patch as $k => $v) {
            if ($v === null) {
                unset($meta[$k]);
            } else {
                $meta[$k] = $v;
            }
        }
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $table = $table_prefix . 'sessions';
        $db->update(
            $table,
            array(
                'metadata' => json_encode($meta),
                'updated_ymdhis' => $now,
            ),
            'session_id = :sid',
            array('sid' => $session_id)
        );
        return true;
    }

    /**
     * Insert lupo_sessions row only (e.g. Eye `lupo_session` cookie). Does not rotate PHP session id.
     *
     * @param \PDO_DB $db
     * @param int $actor_id
     * @return Session|null
     */
    public static function createEmbedSession($db, $actor_id)
    {
        if (!($db instanceof \PDO_DB)) {
            return null;
        }
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $table = $table_prefix . 'sessions';
        $now = (int) gmdate('YmdHis');
        $session_id = bin2hex(random_bytes(32));
        if (strlen($session_id) > 128) {
            $session_id = substr($session_id, 0, 128);
        }
        $fp = self::untrustedFingerprintSources();
        $hashes = self::hashFingerprint($fp['ip'], $fp['user_agent']);
        $ip_hash = $hashes['ip_hash'];
        $ua_hash = $hashes['ua_hash'];
        $csrf_token = bin2hex(random_bytes(32));
        if (strlen($csrf_token) > 128) {
            $csrf_token = substr($csrf_token, 0, 128);
        }
        $node_id = defined('LUPO_DEFAULT_NODE_ID') ? (int) LUPO_DEFAULT_NODE_ID : 1;
        $data = array(
            'session_id' => $session_id,
            'actor_id' => (int) $actor_id,
            'federation_node_id' => $node_id,
            'ip_hash' => $ip_hash,
            'ua_hash' => $ua_hash,
            'csrf_token' => $csrf_token,
            'last_activity_ymdhis' => $now,
            'last_seen_ymdhis' => $now,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'name_key' => null,
            'is_named' => 0
        );
        try {
            $db->insert($table, $data);
        } catch (\Exception $e) {
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log('Session::createEmbedSession: ' . $e->getMessage());
            }
            return null;
        }
        $s = new self($db, null);
        $s->session_id = $session_id;
        $s->actor_id = (int) $actor_id;
        $s->federation_node_id = $node_id;
        $s->ip_hash = $ip_hash;
        $s->ua_hash = $ua_hash;
        $s->csrf_token = $csrf_token;
        $s->last_activity_ymdhis = $now;
        $s->last_seen_ymdhis = $now;
        $s->created_ymdhis = $now;
        $s->updated_ymdhis = $now;
        $s->name_key = null;
        $s->is_named = 0;
        return $s;
    }

    /**
     * Create new session row and bind to PHP session (session rotation on login).
     *
     * @param \PDO_DB $db
     * @param int $actor_id
     * @return Session|null
     */
    public static function create($db, $actor_id)
    {
        $s = self::createEmbedSession($db, $actor_id);
        if (!$s) {
            return null;
        }
        if (function_exists('session_status') && session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
            session_id($s->session_id);
            session_start();
        }
        return $s;
    }

    /**
     * Update last_activity_ymdhis, last_seen_ymdhis, and updated_ymdhis in DB (packed UTC).
     */
    public function touch()
    {
        if (!$this->db || !($this->db instanceof \PDO_DB)) {
            return false;
        }
        if (!class_exists('\\timestamp_ymdhis', false) && defined('LUPOPEDIA_PATH')) {
            require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'TimestampYmdhis.php';
        }
        $now = class_exists('\\timestamp_ymdhis', false) ? \timestamp_ymdhis::now() : (int) gmdate('YmdHis');
        $this->db->update(
            $this->table,
            array(
                'last_activity_ymdhis' => $now,
                'last_seen_ymdhis' => $now,
                'updated_ymdhis' => $now
            ),
            'session_id = :sid',
            array('sid' => $this->session_id)
        );
        $this->last_activity_ymdhis = $now;
        $this->last_seen_ymdhis = $now;
        $this->updated_ymdhis = $now;
        return true;
    }

    /**
     * Delete session row and clear PHP session (revocation).
     * Internal implementation used by destroySession/markExpired.
     */
    public function destroyInternal()
    {
        if ($this->db && $this->db instanceof \PDO_DB) {
            $this->db->delete($this->table, 'session_id = :sid', array('sid' => $this->session_id));
        }
        if (function_exists('session_status') && session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = array();
            $untrusted_cookies = self::untrustedCookieArray();
            $sn = session_name();
            if (isset($untrusted_cookies[$sn])) {
                setcookie($sn, '', time() - 3600, '/');
            }
            session_destroy();
        }
        return true;
    }

    /**
     * Rotate to a new session id: create new row, delete old, bind PHP to new id.
     */
    public function rotate()
    {
        if (!$this->db || !($this->db instanceof \PDO_DB)) {
            return null;
        }
        $newSession = self::create($this->db, $this->actor_id);
        if (!$newSession) {
            return null;
        }
        $this->db->delete($this->table, 'session_id = :sid', array('sid' => $this->session_id));
        $this->session_id = $newSession->session_id;
        $this->csrf_token = $newSession->csrf_token;
        $this->last_activity_ymdhis = $newSession->last_activity_ymdhis;
        $this->created_ymdhis = $newSession->created_ymdhis;
        $this->updated_ymdhis = $newSession->updated_ymdhis;
        return $newSession;
    }

    public function utcTimestamp()
    {
        if (class_exists('\\timestamp_ymdhis', false)) {
            return \timestamp_ymdhis::now();
        }
        return (int) gmdate('YmdHis');
    }

    public function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            return session_start();
        }
        return true;
    }

    public function getSessionId()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_name('PHPSESSID');
        }
        $this->start();
        return session_id();
    }

    /**
     * Create new session for actor (login). Session rotation; DB is source of truth.
     *
     * @param int $actorId
     * @param string $authMethod unused in Model A
     * @param string $authProvider unused in Model A
     * @return string|false session_id on success
     */
    public function createSession($actorId, $authMethod = 'password', $authProvider = 'local')
    {
        $created = self::create($this->db, $actorId);
        if (!$created) {
            return false;
        }
        $this->current = $created;
        return $created->session_id;
    }

    /**
     * Validate current PHP session: load from DB, touch, return actor_id or false.
     *
     * @param string|null $sessionId
     * @return int|false actor_id if valid
     */
    public function validateSession($sessionId = null)
    {
        if ($sessionId === null) {
            $sessionId = $this->getSessionId();
        }
        if (!$sessionId) {
            return false;
        }
        $loaded = self::loadById($this->db, $sessionId);
        if (!$loaded) {
            return false;
        }
        $loaded->touch();
        $this->current = $loaded;
        return $loaded->actor_id;
    }

    /**
     * Get actor_id of current loaded session (after validateSession or createSession).
     *
     * @return int|null
     */
    public function getActorId()
    {
        return $this->current ? $this->current->actor_id : null;
    }

    /**
     * Get CSRF token from current session (from DB).
     *
     * @return string|null
     */
    public function getCsrfToken()
    {
        if ($this->current && $this->current->csrf_token) {
            return $this->current->csrf_token;
        }
        $sid = $this->getSessionId();
        $loaded = self::loadById($this->db, $sid);
        if ($loaded) {
            $this->current = $loaded;
            return $loaded->csrf_token;
        }
        return null;
    }

    /**
     * Destroy current session (logout).
     *
     * @param string|null $sessionId
     * @return bool
     */
    public function destroySession($sessionId = null)
    {
        if ($sessionId === null) {
            $sessionId = $this->getSessionId();
        }
        if (!$sessionId) {
            return false;
        }
        $loaded = self::loadById($this->db, $sessionId);
        if ($loaded) {
            return $loaded->destroyInternal();
        }
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = array();
            $untrusted_cookies = self::untrustedCookieArray();
            $sn = session_name();
            if (isset($untrusted_cookies[$sn])) {
                setcookie($sn, '', time() - 3600, '/');
            }
            session_destroy();
        }
        return true;
    }

    /** Alias for destroySession for Model A. */
    public function destroy($sessionId = null)
    {
        return $this->destroySession($sessionId);
    }

    /** Update activity (alias for touch on current). */
    public function updateActivity($sessionId = null)
    {
        if ($sessionId === null) {
            $sessionId = $this->getSessionId();
        }
        $loaded = $sessionId ? self::loadById($this->db, $sessionId) : null;
        if ($loaded) {
            $this->current = $loaded;
            return $loaded->touch();
        }
        return false;
    }

    public function getSessionHandler()
    {
        return $this->sessionHandler;
    }

    public function getDb()
    {
        return $this->db;
    }

    public function getNameKey()
    {
        return $this->current ? $this->current->name_key : null;
    }

    public function getIsNamed()
    {
        return $this->current ? $this->current->is_named : 0;
    }

    /**
     * Set visitor name (name_key, is_named). Model A: stored in DB.
     */
    public function setNameKey($nameKey)
    {
        $sessionId = $this->getSessionId();
        if (!$sessionId || !$this->db || !($this->db instanceof \PDO_DB)) {
            return false;
        }
        $nameKey = substr($nameKey, 0, 100);
        $now = (int) gmdate('YmdHis');
        $this->db->update(
            $this->table,
            array('name_key' => $nameKey, 'is_named' => 1, 'updated_ymdhis' => $now),
            'session_id = :sid',
            array('sid' => $sessionId)
        );
        if ($this->current) {
            $this->current->name_key = $nameKey;
            $this->current->is_named = 1;
        }
        return true;
    }

    /**
     * Last activity timestamp for session (for idle/expiry checks).
     */
    public function getLastSeenYmdhis($sessionId = null)
    {
        if ($sessionId === null) {
            $sessionId = $this->getSessionId();
        }
        $loaded = $sessionId ? self::loadById($this->db, $sessionId) : null;
        if ($loaded) {
            $this->current = $loaded;
            return $loaded->last_activity_ymdhis;
        }
        return null;
    }

    /**
     * Revoke session (delete row). Model A: revocation is DB-driven.
     */
    public function markExpired($sessionId)
    {
        $loaded = $sessionId ? self::loadById($this->db, $sessionId) : null;
        if ($loaded) {
            return $loaded->destroyInternal();
        }
        return false;
    }

    /**
     * Unified compat: ensure session row exists for this id/actor; touch or insert with existing sessionId. No session_data in Model A.
     */
    public function createOrUpdateForUnified($sessionId, $userId, $systemContext, $sessionData = array())
    {
        if (!$sessionId || !$this->db || !($this->db instanceof \PDO_DB)) {
            return null;
        }
        if (strlen($sessionId) > 128) {
            $sessionId = substr($sessionId, 0, 128);
        }
        $loaded = self::loadById($this->db, $sessionId);
        if ($loaded) {
            $loaded->touch();
            return $sessionId;
        }
        $now = (int) gmdate('YmdHis');
        $fp = self::untrustedFingerprintSources();
        $hashes = self::hashFingerprint($fp['ip'], $fp['user_agent']);
        $ip_hash = $hashes['ip_hash'];
        $ua_hash = $hashes['ua_hash'];
        $csrf_token = bin2hex(random_bytes(32));
        if (strlen($csrf_token) > 128) {
            $csrf_token = substr($csrf_token, 0, 128);
        }
        $node_id = defined('LUPO_DEFAULT_NODE_ID') ? (int) LUPO_DEFAULT_NODE_ID : 1;
        $actor_id = $userId !== null ? (int) $userId : 0;
        $data = array(
            'session_id' => $sessionId,
            'actor_id' => $actor_id,
            'federation_node_id' => $node_id,
            'ip_hash' => $ip_hash,
            'ua_hash' => $ua_hash,
            'csrf_token' => $csrf_token,
            'last_activity_ymdhis' => $now,
            'last_seen_ymdhis' => $now,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'name_key' => null,
            'is_named' => 0,
        );
        try {
            $this->db->insert($this->table, $data);
        } catch (\Exception $e) {
            return null;
        }
        return $sessionId;
    }

    /**
     * Unified compat: return session shape (user_id, session_id, name_key, is_named). No session_data in Model A.
     */
    public function getSessionForUnified($sessionId)
    {
        $loaded = $sessionId ? self::loadById($this->db, $sessionId) : null;
        if (!$loaded) {
            return null;
        }
        return array(
            'user_id' => $loaded->actor_id,
            'system_context' => 'lupopedia',
            'session_data' => array(),
            'expires_ymdhis' => null,
            'session_id' => $loaded->session_id,
            'name_key' => $loaded->name_key,
            'is_named' => $loaded->is_named,
        );
    }
}
