<?php
/**
 * Auth Service - Compatible with existing App\Auth\AuthService
 * Handles authentication and actor resolution
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. AuthService.php cannot be called directly.");
}

// Legacy MD5 (Crafty import) + bcrypt verification and upgrade flags
require_once __DIR__ . '/../security/password-hash.php';

// Include AuthSessionManager
require_once __DIR__ . '/AuthSessionManager.php';

class AuthService
{
    private $db;
    private $table_prefix;

    public function __construct()
    {
        $this->db = DatabaseFactory::getConnection();
        $this->table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    }

    /**
     * Authenticate user with username and password
     */
    public function authenticate($username, $password)
    {
        $sql = "SELECT auth_user_id, username, display_name, email, password_hash
                FROM {$this->table_prefix}auth_users
                WHERE username = :username 
                AND is_active = 1 
                AND is_deleted = 0
                LIMIT 1";
        
        $user = $this->db->fetchRow($sql, ['username' => $username]);

        if (!$user || !lupo_verify_password($password, $user['password_hash'])) {
            return false;
        }

        // Update last login
        $this->db->update(
            "{$this->table_prefix}auth_users",
            ['last_login_ymdhis' => gmdate('YmdHis')],
            'auth_user_id = :auth_user_id',
            ['auth_user_id' => $user['auth_user_id']]
        );

        return $user;
    }

    /**
     * Handle login and actor resolution
     */
    public function handleLogin($username, $password, $redirect = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $auth_user = $this->authenticate($username, $password);

        if (!$auth_user) {
            return array('error' => 'Invalid credentials');
        }

        $stored_hash = isset($auth_user['password_hash']) ? $auth_user['password_hash'] : '';
        $needs_password_change = lupo_password_needs_upgrade($stored_hash);
        unset($auth_user['password_hash']);
        if ($needs_password_change) {
            $_SESSION['password_change_required'] = true;
            $_SESSION['password_change_user_id'] = $auth_user['auth_user_id'];
        }

        $sessionManager = new AuthSessionManager();

        // Check if user has an existing actor
        $existing_actor = $sessionManager->getActorForAuthUser($auth_user['auth_user_id']);

        if ($existing_actor) {
            $sessionManager->createSession($existing_actor['actor_id'], $existing_actor['actor_name']);
            if ($needs_password_change) {
                $_SESSION['password_change_actor_id'] = $existing_actor['actor_id'];
                return array('success' => true, 'needs_password_change' => true);
            }
            $default_admin = (defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/admin.php' : '/admin.php');
            return array('success' => true, 'redirect' => $redirect ? $redirect : $default_admin);
        }

        // No actor found — need agent selection (password change after actor picked in select_agent.php)
        $agents = $sessionManager->getAvailableAgents();

        $_SESSION['pending_auth_user_id'] = $auth_user['auth_user_id'];
        $_SESSION['pending_username'] = $username;

        return array(
            'success' => true,
            'needs_agent_selection' => true,
            'agents' => $agents,
            'redirect' => '/lupopedia/select_agent.php'
        );
    }

    /**
     * Get current user from session
     */
    public function getCurrentUser()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['actor_id'])) {
            return false;
        }
        
        $actor_id = $_SESSION['actor_id'];
        
        $sql = "SELECT a.actor_id, a.actor_name, a.actor_source_id as auth_user_id, 
                       au.username, au.display_name, au.email
                FROM {$this->table_prefix}actors a
                INNER JOIN {$this->table_prefix}auth_users au ON a.actor_source_id = au.auth_user_id
                WHERE a.actor_id = :actor_id 
                AND a.actor_source_type = 'user'
                AND a.is_deleted = 0 
                AND au.is_deleted = 0
                LIMIT 1";
        
        $user = $this->db->fetchRow($sql, ['actor_id' => $actor_id]);
        
        if (!$user) {
            return false;
        }
        
        return [
            'actor_id' => (int) $user['actor_id'],
            'auth_user_id' => (int) $user['auth_user_id'],
            'username' => $user['username'],
            'display_name' => $user['display_name'],
            'email' => $user['email'],
        ];
    }

    /**
     * Check if user is logged in
     */
    public function isLoggedIn()
    {
        return $this->getCurrentUser() !== false;
    }

    /**
     * Logout user
     */
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Mark session as expired in database
        if (isset($_SESSION['actor_id'])) {
            $this->db->update(
                "{$this->table_prefix}sessions",
                [
                    'is_expired' => 1,
                    'updated_ymdhis' => gmdate('YmdHis')
                ],
                'session_id = :session_id',
                ['session_id' => session_id()]
            );
        }
        session_destroy();
        return true;
    }
}
