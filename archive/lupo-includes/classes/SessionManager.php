<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: class
  when_updated: "20260408160633"
  file_path_from_root: "lupo-includes/classes/SessionManager.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-includes/classes/SessionManager.php"
  questions_toon: null
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "class"
  artifact_kind: "service"
  purpose: "Per-request hook for probabilistic lupo_sessions GC; idle expiry lives in App\\Auth\\Session::validateSession()."
  tags: ["session", "gc", "timestamp_ymdhis", "lupo_sessions"]
---
*/

/**
 * SessionManager — lightweight wrapper for shared-hosting session row cleanup
 *
 * Idle timeout and touch are handled in App\Auth\Session::validateSession() (isExpired + touch).
 * tick() only invokes Session::maybeProbabilisticGarbageCollect() once per request (before validate)
 * so stale rows are swept without cron. Retained for future session hooks (e.g. warnings).
 *
 * @package Lupopedia
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded. SessionManager cannot be used directly.');
}

/** @deprecated Retained for compatibility; idle limits use LUPO_SESSION_ANONYMOUS_IDLE_MINUTES / Session::maxIdleSecondsForIsNamed */
if (!defined('LUPO_SESSION_IDLE_TIMEOUT_SECONDS')) {
    define('LUPO_SESSION_IDLE_TIMEOUT_SECONDS', 20 * 60);
}

class SessionManager
{
    /** @var \App\Auth\Session|null */
    private $session;

    /** @var int Unused; kept for constructor BC */
    private $idleTimeoutSeconds;

    /**
     * @param \App\Auth\Session|null $session
     * @param int|null $idleTimeoutSeconds Optional legacy (ignored by tick)
     */
    public function __construct($session = null, $idleTimeoutSeconds = null)
    {
        $this->session = $session;
        $this->idleTimeoutSeconds = $idleTimeoutSeconds !== null
            ? (int) $idleTimeoutSeconds
            : (int) LUPO_SESSION_IDLE_TIMEOUT_SECONDS;
    }

    /**
     * Probabilistic garbage collection for old lupo_sessions rows (no per-session idle logic here).
     *
     * @return bool True if GC was eligible to run (may still no-op on probability/lock)
     */
    public function tick()
    {
        if (!$this->session) {
            return false;
        }
        $db = $this->session->getDb();
        if (!$db || !($db instanceof \PDO_DB)) {
            return false;
        }
        \App\Auth\Session::maybeProbabilisticGarbageCollect($db);
        return true;
    }
}
