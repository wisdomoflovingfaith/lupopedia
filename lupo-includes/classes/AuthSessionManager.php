<?php
/**
 * Auth Session Manager
 * Handles auth_user to actor mapping and session creation
 * Corrected implementation for actual database schema
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. AuthSessionManager.php cannot be called directly.");
}

// Load PHP 5.6 polyfills if needed
if (version_compare(PHP_VERSION, '7.0.0', '<')) {
    require_once __DIR__ . '/../functions/php56_polyfills.php';
}

class AuthSessionManager
{
    private $db;
    private $table_prefix;

    public function __construct()
    {
        $this->db = DatabaseFactory::getConnection();
        $this->table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    }

    /**
     * Get actor linked to auth_user via lupo_actor_auth_users mapping table
     * OR directly via auth_user_id field in lupo_actors table
     */
    public function getActorForAuthUser($auth_user_id)
    {
        // First try the mapping table (preferred method)
        $sql = "SELECT a.* 
                FROM {$this->table_prefix}actors a
                INNER JOIN {$this->table_prefix}actor_auth_users aau ON a.actor_id = aau.actor_id
                WHERE aau.auth_user_id = :auth_user_id 
                AND aau.status = 'active'
                AND aau.is_deleted = 0
                AND a.is_deleted = 0 
                ORDER BY aau.is_primary DESC, aau.routing_priority ASC
                LIMIT 1";
        
        $actor = $this->db->fetchRow($sql, ['auth_user_id' => $auth_user_id]);
        
        // If not found in mapping table, try direct auth_user_id link
        if (!$actor) {
            $sql = "SELECT a.* 
                    FROM {$this->table_prefix}actors a
                    WHERE a.auth_user_id = :auth_user_id 
                    AND a.is_active = 1 
                    AND a.is_deleted = 0
                    ORDER BY a.actor_id ASC
                    LIMIT 1";
            
            $actor = $this->db->fetchRow($sql, ['auth_user_id' => $auth_user_id]);
        }
        
        // Clean up slugified actor names (one-time fix)
        if ($actor && strpos($actor['actor_name'], '@') !== false && strpos($actor['actor_name'], '-at-') !== false) {
            // This is an old slugified name, clean it up
            $user_sql = "SELECT display_name FROM {$this->table_prefix}auth_users WHERE auth_user_id = :auth_user_id";
            $user = $this->db->fetchRow($user_sql, ['auth_user_id' => $auth_user_id]);
            $display_name = $user ? $user['display_name'] : 'User';
            
            // Try to get agent name from the slug
            $agent_name = 'Agent';
            if (preg_match('/_([a-z0-9-]+)$/', $actor['actor_name'], $matches)) {
                $agent_sql = "SELECT agent_name FROM {$this->table_prefix}agents WHERE agent_id = :agent_id";
                $agent = $this->db->fetchRow($agent_sql, ['agent_id' => $matches[1]]);
                if ($agent) {
                    $agent_name = $agent['agent_name'];
                }
            }
            
            // Create a clean name
            $clean_name = $agent_name;
            if ($display_name && $display_name !== 'User') {
                $clean_name .= ' ' . $display_name;
            }
            
            // Update the actor name
            $this->db->update(
                "{$this->table_prefix}actors",
                ['actor_name' => $clean_name],
                'actor_id = :actor_id',
                ['actor_id' => $actor['actor_id']]
            );
            
            // Update the actor name in session
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['actor_name'] = $clean_name;
            
            // Update the actor in the result
            $actor['actor_name'] = $clean_name;
        }
        
        return $actor;
    }

    /**
     * Get available agents (not currently linked to an active session)
     */
    public function getAvailableAgents()
    {
        // Get agents that are not currently assigned to an active session
        $sql = "SELECT a.agent_id, a.agent_key, a.agent_name, a.description
                FROM {$this->table_prefix}agents a
                WHERE a.is_deleted = 0
                AND a.agent_id NOT IN (
                    SELECT s.actor_id FROM {$this->table_prefix}sessions s 
                    WHERE s.is_active = 1 AND s.is_expired = 0 AND s.is_deleted = 0
                )
                ORDER BY a.agent_name ASC";
        return $this->db->fetchAll($sql, []);
    }

    /**
     * Create new actor for auth_user from selected agent
     */
    public function createActorFromAgent($auth_user_id, $agent_id, $username, $department_id = null)
    {
        $now = gmdate('YmdHis');
        
        // If no department specified, get user's primary department
        if ($department_id === null) {
            $department_id = $this->getUserDepartment($auth_user_id);
        }
        
        // Get agent details
        $sql = "SELECT agent_name, description FROM {$this->table_prefix}agents 
                WHERE agent_id = :agent_id AND is_deleted = 0";
        $agent = $this->db->fetchRow($sql, ['agent_id' => $agent_id]);
        
        if (!$agent) {
            return false;
        }
        
        // Get user display_name for better naming
        $sql = "SELECT display_name FROM {$this->table_prefix}auth_users 
                WHERE auth_user_id = :auth_user_id";
        $user = $this->db->fetchRow($sql, ['auth_user_id' => $auth_user_id]);
        $display_name = $user ? $user['display_name'] : 'User';
        
        // Create actor name based on agent name, add display_name if already exists
        $base_actor_name = $agent['agent_name'];
        $actor_name = $base_actor_name;
        
        // Check if this actor name already exists for this user
        $sql = "SELECT COUNT(*) as count FROM {$this->table_prefix}actors 
                WHERE actor_name = :actor_name AND is_deleted = 0";
        $count = $this->db->fetchOne($sql, ['actor_name' => $actor_name]);
        
        if ($count > 0) {
            // Name exists, append display_name
            $actor_name = $base_actor_name . ' ' . $display_name;
            
            // Check again with the new name
            $sql = "SELECT COUNT(*) as count FROM {$this->table_prefix}actors 
                    WHERE actor_name = :actor_name AND is_deleted = 0";
            $count = $this->db->fetchOne($sql, ['actor_name' => $actor_name]);
            
            if ($count > 0) {
                // Still exists, add a number
                $counter = 1;
                do {
                    $test_name = $base_actor_name . ' ' . $display_name . ' ' . $counter;
                    $count = $this->db->fetchOne($sql, ['actor_name' => $test_name]);
                    $counter++;
                } while ($count > 0);
                $actor_name = $test_name;
            }
        }
        
        // Generate slug from actor name
        $slug = strtolower(preg_replace('/[^a-z0-9-]/', '-', $actor_name));
        $slug = trim($slug, '-');
        $slug = substr($slug, 0, 255); // Ensure it fits in varchar(255)
        
        // Generate deterministic actor_id (YYYYMMDDHHIISS + 4 random) with collision checks.
        $actor_id = $this->generateDeterministicActorId();
        if ($actor_id === false) {
            return false;
        }
        
        // Insert the actor using PDO_DB insert method
        $this->db->insert(
            "{$this->table_prefix}actors",
            [
                'actor_id' => $actor_id,
                'actor_name' => $actor_name,
                'slug' => $slug,
                'name' => $agent['agent_name'],
                'actor_type' => 'human_agent',
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'is_active' => 1,
                'is_deleted' => 0,
                'can_login' => 1,
                'is_agent' => 1,
                'actor_source_id' => $auth_user_id,
                'actor_source_type' => 'user'
            ]
        );
        
        // Create mapping in lupo_actor_departments
        $this->createActorDepartmentMapping($actor_id, $department_id);
        
        // Create mapping in lupo_actor_auth_users
        $this->createActorAuthUserMapping($actor_id, $auth_user_id);
        
        return $actor_id;
    }

    /**
     * Create mapping in lupo_actor_departments table
     */
    private function createActorDepartmentMapping($actor_id, $department_id)
    {
        $now = gmdate('YmdHis');
        $mapping_id = $this->getNextMappingId();
        
        // Insert the mapping using PDO_DB insert method
        $this->db->insert(
            "{$this->table_prefix}actor_departments",
            [
                'actor_department_id' => $mapping_id,
                'actor_id' => $actor_id,
                'department_id' => $department_id,
                'role_key' => 'member',
                'title' => 'Member',
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'is_deleted' => 0
            ]
        );
    }

    /**
     * Create mapping in lupo_actor_auth_users table
     */
    private function createActorAuthUserMapping($actor_id, $auth_user_id)
    {
        $now = gmdate('YmdHis');
        $mapping_id = $this->getNextMappingId();
        
        // Insert the mapping using PDO_DB insert method
        $this->db->insert(
            "{$this->table_prefix}actor_auth_users",
            [
                'actor_auth_user_id' => $mapping_id,
                'actor_id' => $actor_id,
                'auth_user_id' => $auth_user_id,
                'relationship_role' => 'supporting_human',
                'is_primary' => 1,
                'routing_priority' => 100,
                'status' => 'active',
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'is_deleted' => 0
            ]
        );
    }

    /**
     * Create session for actor
     */
    public function createSession($actor_id, $actor_name)
    {
        $session_id = session_id() ?: bin2hex(random_bytes(32));
        $now = gmdate('YmdHis');
        $expires = gmdate('YmdHis', strtotime('+8 hours'));
        
        // Insert the session using PDO_DB insert method
        $this->db->insert(
            "{$this->table_prefix}sessions",
            [
                'session_id' => $session_id,
                'actor_id' => $actor_id,
                'actor_name' => $actor_name,
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'last_activity_ymdhis' => $now,
                'is_active' => 1,
                'is_expired' => 0,
                'is_revoked' => 0,
                'is_deleted' => 0,
                'expires_ymdhis' => $expires,
                'security_level' => 'standard',
                'status' => 'active'
            ]
        );
        
        // Set PHP session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['actor_id'] = $actor_id;
        $_SESSION['actor_name'] = $actor_name;
        $_SESSION['auth_user_id'] = $this->getAuthUserId($actor_id);
        
        return $session_id;
    }

    /**
     * Generate deterministic actor_id with collision retries.
     *
     * Format: YYYYMMDDHHIISS + 4 random digits.
     * Ensures generated IDs are from 2026 onward by validating the year prefix.
     *
     * @return string|false
     */
    private function generateDeterministicActorId()
    {
        $table = $this->table_prefix . 'actors';
        $max_attempts = 25;
        $attempt = 0;

        while ($attempt < $max_attempts) {
            if (class_exists('IdGenerator')) {
                $candidate = IdGenerator::generate();
            } else {
                $candidate = gmdate('YmdHis') . str_pad((string) mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
            }

            if (!is_string($candidate) || !preg_match('/^[0-9]{18}$/', $candidate)) {
                $attempt++;
                usleep(5000);
                continue;
            }

            if (substr($candidate, 0, 4) < '2026') {
                $attempt++;
                usleep(5000);
                continue;
            }

            $exists = $this->db->fetchOne(
                "SELECT 1 FROM {$table} WHERE actor_id = :actor_id LIMIT 1",
                array('actor_id' => $candidate)
            );
            if (!$exists) {
                return $candidate;
            }

            $attempt++;
            usleep(5000);
        }

        return false;
    }

    /**
     * Get next mapping_id
     */
    private function getNextMappingId()
    {
        $sql = "SELECT MAX(actor_auth_user_id) as max_id FROM {$this->table_prefix}actor_auth_users";
        $result = $this->db->fetchRow($sql, []);
        return ($result['max_id'] ?? 0) + 1;
    }

    /**
     * Get auth_user_id from actor
     */
    private function getAuthUserId($actor_id)
    {
        $sql = "SELECT actor_source_id FROM {$this->table_prefix}actors 
                WHERE actor_id = :actor_id AND actor_source_type = 'user'";
        $result = $this->db->fetchRow($sql, ['actor_id' => $actor_id]);
        return $result['actor_source_id'] ?? null;
    }

    /**
     * Authenticate user credentials
     */
    public function authenticateUser($username, $password)
    {
        $sql = "SELECT auth_user_id, username, display_name, email, password_hash
                FROM {$this->table_prefix}auth_users
                WHERE username = :username 
                AND is_active = 1 
                AND is_deleted = 0
                LIMIT 1";
        
        $user = $this->db->fetchRow($sql, ['username' => $username]);
        
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }
        
        // Update last login using PDO_DB update method
        $this->db->update(
            "{$this->table_prefix}auth_users",
            ['last_login_ymdhis' => gmdate('YmdHis')],
            'auth_user_id = :auth_user_id',
            ['auth_user_id' => $user['auth_user_id']]
        );
        
        return $user;
    }
    
    /**
     * Get current active actor ID from session
     */
    public function getActiveActorId()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['actor_id']) ? (int) $_SESSION['actor_id'] : 0;
    }
    
    /**
     * Update active actor in session
     */
    public function updateActiveActor($actor_id)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Get actor details
        $sql = "SELECT actor_name, actor_type FROM {$this->table_prefix}actors 
                WHERE actor_id = :actor_id AND is_active = 1 AND is_deleted = 0";
        $actor = $this->db->fetchRow($sql, ['actor_id' => $actor_id]);
        
        if ($actor) {
            $_SESSION['actor_id'] = $actor_id;
            $_SESSION['actor_name'] = $actor['actor_name'];
            $_SESSION['actor_type'] = $actor['actor_type'];
            
            // Update session record in database
            $session_id = session_id();
            $now = gmdate('YmdHis');
            
            // Update session using PDO_DB update method
            $this->db->update(
                "{$this->table_prefix}sessions",
                [
                    'actor_id' => $actor_id,
                    'updated_ymdhis' => $now
                ],
                'session_id = :session_id',
                ['session_id' => $session_id]
            );
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Get actors that user can act as (filtered by department using mapping tables)
     */
    public function getActorsUserCanActAs($auth_user_id, $isAdmin = false)
    {
        // Get user's departments
        $user_departments = $this->getUserDepartments($auth_user_id);

        $is_root_user = false;
        $department_ids = array();
        foreach ($user_departments as $dept) {
            $department_ids[] = $dept['department_id'];
            if ((int) $dept['department_id'] === 0) {
                $is_root_user = true;
            }
        }

        // Global admin (e.g. module owner) without auth_user_departments rows still needs the root actor list.
        if (!$is_root_user && $isAdmin) {
            $is_root_user = true;
        }

        if (empty($user_departments) && !$is_root_user) {
            return array();
        }
        
        // If user is in department 0 (root), they can see all actors
        if ($is_root_user) {
            $sql = "SELECT a.actor_id, a.actor_name, a.name, a.actor_type, a.department_id
                    FROM {$this->table_prefix}actors a
                    WHERE a.is_active = 1 
                    AND a.is_deleted = 0
                    AND a.is_agent = 1
                    AND (
                        a.actor_id NOT IN (
                            SELECT DISTINCT s.actor_id 
                            FROM {$this->table_prefix}sessions s
                            WHERE s.session_id != :current_session
                            AND s.actor_id IS NOT NULL
                            AND s.created_ymdhis > :expiry_time
                            AND (s.is_deleted = 0 OR s.is_deleted IS NULL)
                        )
                        OR a.actor_id = :current_actor
                    )
                    ORDER BY a.actor_type, a.name";
            
            $params = [
                'current_session' => session_id() ?: '',
                'current_actor' => $this->getActiveActorId(),
                'expiry_time' => gmdate('YmdHis', strtotime('-24 hours'))
            ];
            
            return $this->db->fetchAll($sql, $params);
        }
        
        // Normal user: only see actors in their departments
        $dept_placeholders = str_repeat('?,', count($department_ids) - 1) . '?';
        
        $sql = "SELECT DISTINCT a.actor_id, a.actor_name, a.name, a.actor_type, a.department_id
                FROM {$this->table_prefix}actors a
                INNER JOIN {$this->table_prefix}actor_departments ad ON a.actor_id = ad.actor_id
                WHERE a.is_active = 1 
                AND a.is_deleted = 0
                AND a.is_agent = 1
                AND ad.department_id IN ({$dept_placeholders})
                AND ad.is_deleted = 0
                AND (
                    a.actor_id NOT IN (
                        SELECT DISTINCT s.actor_id 
                        FROM {$this->table_prefix}sessions s
                        WHERE s.session_id != :current_session
                        AND s.actor_id IS NOT NULL
                        AND s.created_ymdhis > :expiry_time
                        AND (s.is_deleted = 0 OR s.is_deleted IS NULL)
                    )
                    OR a.actor_id = :current_actor
                )
                ORDER BY a.actor_type, a.name";
        
        $params = array_merge($department_ids, [
            'current_session' => session_id() ?: '',
            'current_actor' => $this->getActiveActorId(),
            'expiry_time' => gmdate('YmdHis', strtotime('-24 hours'))
        ]);
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get user's department from auth_user_id using mapping table
     */
    private function getUserDepartment($auth_user_id)
    {
        // Get primary department from auth_user_departments mapping table
        $sql = "SELECT aud.department_id 
                FROM {$this->table_prefix}auth_user_departments aud
                WHERE aud.auth_user_id = :auth_user_id 
                AND aud.is_primary = 1
                AND aud.is_deleted = 0
                LIMIT 1";
        $result = $this->db->fetchRow($sql, ['auth_user_id' => $auth_user_id]);
        
        if ($result) {
            return $result['department_id'];
        }
        
        // If no primary department, get any department
        $sql = "SELECT aud.department_id 
                FROM {$this->table_prefix}auth_user_departments aud
                WHERE aud.auth_user_id = :auth_user_id 
                AND aud.is_deleted = 0
                ORDER BY aud.created_ymdhis ASC
                LIMIT 1";
        $result = $this->db->fetchRow($sql, ['auth_user_id' => $auth_user_id]);
        
        if ($result) {
            return $result['department_id'];
        }
        
        // Default to department 0 (root) for new users
        return 0;
    }

    /**
     * Get all departments for a user
     */
    public function getUserDepartments($auth_user_id)
    {
        $sql = "SELECT d.*, aud.is_primary, aud.role_key, aud.title
                FROM {$this->table_prefix}auth_user_departments aud
                INNER JOIN {$this->table_prefix}departments d ON aud.department_id = d.department_id
                WHERE aud.auth_user_id = :auth_user_id 
                AND aud.is_deleted = 0
                AND d.is_deleted = 0
                ORDER BY aud.is_primary DESC, d.name";
        
        return $this->db->fetchAll($sql, ['auth_user_id' => $auth_user_id]);
    }

    /**
     * Release all active leases for a user (set status = 'released', is_primary = 0)
     */
    public function releaseAllLeasesForUser($auth_user_id)
    {
        $now = gmdate('YmdHis');
        $sql = "UPDATE {$this->table_prefix}actor_auth_users
                SET status = 'released', is_primary = 0, updated_ymdhis = :now
                WHERE auth_user_id = :auth_user_id AND status = 'active' AND is_deleted = 0";
        $this->db->query($sql, array('now' => $now, 'auth_user_id' => $auth_user_id));
    }
}
