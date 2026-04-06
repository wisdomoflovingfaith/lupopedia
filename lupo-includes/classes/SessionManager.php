<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: class
  when_updated: "20260406000223"
  file_path_from_root: "lupo-includes/classes/SessionManager.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-includes/classes/SessionManager.php"
  last_modified_utc: "20260406000223"
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "class"
  artifact_kind: "service"
  purpose: "Session idle timeout via lupo_sessions; compares packed UTC now to last-seen (see Session::getLastSeenYmdhis)."
  tags: ["session", "idle", "timeout", "timestamp_ymdhis", "lupo_sessions"]
---
*/

/**
 * SessionManager — Session lifecycle and idle timeout
 *
 * When loaded/run, checks the current PHP session against lupo_sessions:
 * - Loads last activity reference via App\\Auth\\Session::getLastSeenYmdhis() (prefers last_seen_ymdhis, else last_activity_ymdhis).
 * - If (now - reference) <= idle threshold: updateActivity() (touch).
 * - If over threshold: markExpired() (revokes row per Session::destroyInternal).
 * - If no session row: no-op.
 *
 * Packed UTC "now" uses timestamp_ymdhis::now() (not Unix epoch).
 *
 * @package Lupopedia
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded. SessionManager cannot be used directly.');
}

/** Default idle timeout in seconds (20 minutes) */
if (!defined('LUPO_SESSION_IDLE_TIMEOUT_SECONDS')) {
    define('LUPO_SESSION_IDLE_TIMEOUT_SECONDS', 20 * 60);
}

class SessionManager
{
    /** @var \App\Auth\Session|null */
    private $session;

    /** @var int Idle timeout in seconds */
    private $idleTimeoutSeconds;

    /**
     * @param \App\Auth\Session|null $session Session instance (required for OOP path)
     * @param int|null $idleTimeoutSeconds Optional (default LUPO_SESSION_IDLE_TIMEOUT_SECONDS)
     */
    public function __construct($session = null, $idleTimeoutSeconds = null)
    {
        $this->session = $session;
        $this->idleTimeoutSeconds = $idleTimeoutSeconds !== null
            ? (int) $idleTimeoutSeconds
            : (int) LUPO_SESSION_IDLE_TIMEOUT_SECONDS;
    }

    /**
     * Run session check: update last_seen if within idle window; else mark expired.
     * Call once per request after PHP session is started.
     *
     * @return bool True if session was updated (last_seen), false otherwise
     */
    public function tick()
    {
        if (!$this->session) {
            return false;
        }

        $this->session->start();
        $sessionId = $this->session->getSessionId();
        if ($sessionId === '' || $sessionId === false) {
            return false;
        }

        $lastSeen = $this->session->getLastSeenYmdhis($sessionId);
        if ($lastSeen === null) {
            return false;
        }

        if (!class_exists('timestamp_ymdhis', false)) {
            require_once __DIR__ . '/TimestampYmdhis.php';
        }
        $now = timestamp_ymdhis::now();
        $diffSeconds = $this->diffSeconds($now, $lastSeen);

        if ($diffSeconds > $this->idleTimeoutSeconds) {
            $this->session->markExpired($sessionId);
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log('SessionManager: session marked inactive/expired (idle > ' . $this->idleTimeoutSeconds . 's) - ' . substr($sessionId, 0, 8) . '...');
            }
            return false;
        }

        $this->session->updateActivity($sessionId);
        return true;
    }

    /**
     * Seconds from $from to $to (to - from).
     */
    private function diffSeconds($toYmdhis, $fromYmdhis)
    {
        if (!class_exists('timestamp_ymdhis', false)) {
            require_once __DIR__ . '/TimestampYmdhis.php';
        }
        return timestamp_ymdhis::diffInSeconds((int) $toYmdhis, (int) $fromYmdhis);
    }
}
