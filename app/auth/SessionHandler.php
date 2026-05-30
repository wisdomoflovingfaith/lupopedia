<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: class
  when_updated: "20260423230000"
  file_path_from_root: "app/auth/SessionHandler.php"
  artifact_type: "class"
  artifact_kind: "auth_session_handler"
  purpose: "Thin Session-aware handler; holds $db + back-reference to App\\Auth\\Session.  Used exclusively by bootstrap.php to wire $lupo_session."
  tags: ["session", "auth", "handler", "bootstrap"]
---
*/

namespace App\Auth;

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded. SessionHandler cannot be used directly.');
}

/**
 * SessionHandler -- bootstrap glue between PDO_DB and App\Auth\Session.
 *
 * bootstrap.php creates this first, passes it to Session as $sessionHandler,
 * then calls setSession() to complete the back-reference.
 * No PHP session save-handler interface is implemented here; all session
 * authority lives in lupo_sessions rows via App\Auth\Session (Model A).
 */
class SessionHandler
{
    /** @var \PDO_DB */
    private $db;

    /** @var Session|null */
    private $session;

    /**
     * @param \PDO_DB $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Back-reference to the Session object that owns this handler.
     * Called by bootstrap after both objects are constructed.
     *
     * @param Session $session
     * @return void
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return Session|null
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @return \PDO_DB
     */
    public function getDb()
    {
        return $this->db;
    }
}
