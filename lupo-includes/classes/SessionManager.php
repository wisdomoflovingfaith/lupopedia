<?php
/**
 * SessionManager — Session lifecycle and idle timeout
 *
 * When loaded/run, checks the current PHP session against lupo_sessions:
 * - If a session row exists and (now - last_seen_ymdhis) <= idle threshold: update last_seen_ymdhis.
 * - If a session row exists and (now - last_seen_ymdhis) > idle threshold: set is_active = 0, is_expired = 1.
 * - If no session row exists: nothing to do.
 *
 * Uses App\Auth\Session when provided (OOP). No procedural helpers.
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

        $now = $this->session->utcTimestamp();
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
        if (class_exists('timestamp_ymdhis')) {
            return timestamp_ymdhis::diffInSeconds($toYmdhis, $fromYmdhis);
        }
        $s = str_pad((string) $fromYmdhis, 14, '0', STR_PAD_LEFT);
        $e = str_pad((string) $toYmdhis, 14, '0', STR_PAD_LEFT);
        $eEpoch = gmmktime(
            (int) substr($e, 8, 2),
            (int) substr($e, 10, 2),
            (int) substr($e, 12, 2),
            (int) substr($e, 4, 2),
            (int) substr($e, 6, 2),
            (int) substr($e, 0, 4)
        );
        $sEpoch = gmmktime(
            (int) substr($s, 8, 2),
            (int) substr($s, 10, 2),
            (int) substr($s, 12, 2),
            (int) substr($s, 4, 2),
            (int) substr($s, 6, 2),
            (int) substr($s, 0, 4)
        );
        return $eEpoch - $sEpoch;
    }
}
