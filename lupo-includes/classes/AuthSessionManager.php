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

require_once __DIR__ . '/../security/password-hash.php';

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
     * Get agent templates available for creating a new actor (no session exclusivity).
     */
    public function getAvailableAgents()
    {
        // Templates for "create new actor from agent" (select-actor.php): exclude system-only rows.
        // lupo_agents.is_internal_only = 1 means tooling / PHP-first system agents — not an end-user persona template.
        // Fresh installs: seed_4.1.0.sql sets is_internal_only for ROSE, HERMES, IRIS, ANUBIS, HEIMDALL, KAIROS (registry agent_id map).
        $sql = "SELECT a.agent_id, a.agent_key, a.agent_name, a.description
                FROM {$this->table_prefix}agents a
                WHERE a.is_deleted = 0
                AND a.is_internal_only = 0
                ORDER BY a.agent_name ASC";
        return $this->db->fetchAll($sql, array());
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
        
        // Get agent details (reject internal/system agents — not for human actor creation)
        $sql = "SELECT agent_name, description FROM {$this->table_prefix}agents 
                WHERE agent_id = :agent_id AND is_deleted = 0 AND is_internal_only = 0";
        $agent = $this->db->fetchRow($sql, array('agent_id' => $agent_id));
        
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
        
        if (!$user || !lupo_verify_password($password, $user['password_hash'])) {
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
     * Update active actor in session (only if user is allowed to act as that actor).
     */
    public function updateActiveActor($actor_id)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $auth_user_id = isset($_SESSION['auth_user_id']) ? (int) $_SESSION['auth_user_id'] : 0;
        if ($auth_user_id <= 0 && isset($GLOBALS['lupo_auth_service']) && is_object($GLOBALS['lupo_auth_service'])) {
            $cu = $GLOBALS['lupo_auth_service']->getCurrentUser();
            if (is_array($cu) && isset($cu['auth_user_id'])) {
                $auth_user_id = (int) $cu['auth_user_id'];
            }
        }
        if ($auth_user_id <= 0) {
            return false;
        }

        $isAdminFlag = false;
        if (isset($_SESSION['is_admin'])) {
            $isAdminFlag = (bool) $_SESSION['is_admin'];
        }
        if (!$isAdminFlag) {
            $isAdminFlag = $this->authUserHasModuleOwnerPermission($auth_user_id);
        }

        $allowed = $this->getActorsUserCanActAs($auth_user_id, $isAdminFlag);
        $ok = false;
        foreach ($allowed as $row) {
            if (isset($row['actor_id']) && (int) $row['actor_id'] === (int) $actor_id) {
                $ok = true;
                break;
            }
        }
        if (!$ok) {
            return false;
        }

        $sql = "SELECT actor_name, actor_type FROM {$this->table_prefix}actors 
                WHERE actor_id = :actor_id AND is_active = 1 AND is_deleted = 0";
        $actor = $this->db->fetchRow($sql, array('actor_id' => $actor_id));

        if ($actor) {
            $_SESSION['actor_id'] = $actor_id;
            $_SESSION['actor_name'] = $actor['actor_name'];
            $_SESSION['actor_type'] = $actor['actor_type'];

            $session_id = session_id();
            $now = gmdate('YmdHis');

            $this->db->update(
                "{$this->table_prefix}sessions",
                array(
                    'actor_id' => $actor_id,
                    'updated_ymdhis' => $now
                ),
                'session_id = :session_id',
                array('session_id' => $session_id)
            );

            return true;
        }

        return false;
    }
    
    /**
     * Get actors that user can act as (department scope; optional per-actor creator/root-only flag).
     * Multiple users may use the same actor concurrently — no session exclusivity.
     *
     * Actors are resolved via lupo_actor_departments (not lupo_actors.auth_user_id alone).
     * Includes human operators and agent personas in scope (is_agent filter removed).
     *
     * @param int         $auth_user_id
     * @param bool        $isAdmin              global admin / elevated list (treated like root for scope)
     * @param int|null    $department_id_filter optional: limit to this department_id (root: any dept;
     *                                            non-root: must be one of the user’s departments). null = all allowed depts.
     */
    public function getActorsUserCanActAs($auth_user_id, $isAdmin = false, $department_id_filter = null)
    {
        $auth_user_id = (int) $auth_user_id;
        // Elevated operator convention (PRD 01 / seed): full active actor list without department join.
        if ($auth_user_id === 10000) {
            $actorCols = "a.actor_id, a.actor_name, a.name, a.actor_type, a.department_id, a.web_restrict_act_as_creator_or_root, a.actor_source_id, a.actor_source_type";
            $sql = "SELECT DISTINCT {$actorCols}
                FROM {$this->table_prefix}actors a
                WHERE a.is_active = 1
                AND (a.is_deleted = 0 OR a.is_deleted IS NULL)
                ORDER BY a.actor_type, a.name";
            $rows = $this->db->fetchAll($sql, array());
            return $this->filterActorsForWebActAsRestriction($rows, $auth_user_id, true);
        }

        $user_departments = $this->getUserDepartments($auth_user_id);

        $in_root_department = false;
        $department_ids = array();
        foreach ($user_departments as $dept) {
            $department_ids[] = (int) $dept['department_id'];
            if ((int) $dept['department_id'] === 0) {
                $in_root_department = true;
            }
        }

        $is_root_user = $in_root_department;
        if (!$is_root_user && $isAdmin) {
            $is_root_user = true;
        }

        if (empty($user_departments) && !$is_root_user) {
            return array();
        }

        $bypass_creator_restrict = $in_root_department || $isAdmin || $this->authUserHasModuleOwnerPermission($auth_user_id);

        $actorCols = "a.actor_id, a.actor_name, a.name, a.actor_type, a.department_id, a.web_restrict_act_as_creator_or_root, a.actor_source_id, a.actor_source_type";

        $filter_dept = null;
        if ($department_id_filter !== null && $department_id_filter !== '') {
            $filter_dept = (int) $department_id_filter;
        }

        $adDeleted = '(ad.is_deleted = 0 OR ad.is_deleted IS NULL)';

        if ($is_root_user) {
            if ($filter_dept !== null) {
                $sql = "SELECT DISTINCT {$actorCols}
                    FROM {$this->table_prefix}actors a
                    INNER JOIN {$this->table_prefix}actor_departments ad ON a.actor_id = ad.actor_id AND {$adDeleted}
                    WHERE a.is_active = 1
                    AND (a.is_deleted = 0 OR a.is_deleted IS NULL)
                    AND ad.department_id = :did
                    ORDER BY a.actor_type, a.name";
                $rows = $this->db->fetchAll($sql, array('did' => $filter_dept));
            } else {
                $sql = "SELECT DISTINCT {$actorCols}
                    FROM {$this->table_prefix}actors a
                    INNER JOIN {$this->table_prefix}actor_departments ad ON a.actor_id = ad.actor_id AND {$adDeleted}
                    WHERE a.is_active = 1
                    AND (a.is_deleted = 0 OR a.is_deleted IS NULL)
                    ORDER BY a.actor_type, a.name";
                $rows = $this->db->fetchAll($sql, array());
            }
        } else {
            if (empty($department_ids)) {
                return array();
            }
            if ($filter_dept !== null) {
                if (!in_array($filter_dept, $department_ids, true)) {
                    return array();
                }
                $sql = "SELECT DISTINCT {$actorCols}
                    FROM {$this->table_prefix}actors a
                    INNER JOIN {$this->table_prefix}actor_departments ad ON a.actor_id = ad.actor_id AND {$adDeleted}
                    WHERE a.is_active = 1
                    AND (a.is_deleted = 0 OR a.is_deleted IS NULL)
                    AND ad.department_id = :did
                    ORDER BY a.actor_type, a.name";
                $rows = $this->db->fetchAll($sql, array('did' => $filter_dept));
            } else {
                $dept_placeholders = str_repeat('?,', count($department_ids) - 1) . '?';
                $sql = "SELECT DISTINCT {$actorCols}
                    FROM {$this->table_prefix}actors a
                    INNER JOIN {$this->table_prefix}actor_departments ad ON a.actor_id = ad.actor_id AND {$adDeleted}
                    WHERE a.is_active = 1
                    AND (a.is_deleted = 0 OR a.is_deleted IS NULL)
                    AND ad.department_id IN ({$dept_placeholders})
                    ORDER BY a.actor_type, a.name";
                $rows = $this->db->fetchAll($sql, $department_ids);
            }
        }

        return $this->filterActorsForWebActAsRestriction($rows, $auth_user_id, $bypass_creator_restrict);
    }

    /**
     * When lupo_actors.web_restrict_act_as_creator_or_root = 1, only the creating auth user
     * (actor_source_id + actor_source_type user/lupo_auth_users) or elevated operators may act as this actor.
     */
    private function filterActorsForWebActAsRestriction($rows, $auth_user_id, $bypass_creator_restrict)
    {
        $out = array();
        foreach ($rows as $row) {
            if ($this->authUserPassesWebActAsRestriction($row, $auth_user_id, $bypass_creator_restrict)) {
                $out[] = $row;
            }
        }
        return $out;
    }

    /**
     * @param array $actorRow
     * @param int   $auth_user_id
     * @param bool  $bypass_creator_restrict root dept, admin flag, or module owner
     */
    private function authUserPassesWebActAsRestriction($actorRow, $auth_user_id, $bypass_creator_restrict)
    {
        $auth_user_id = (int) $auth_user_id;
        $flag = isset($actorRow['web_restrict_act_as_creator_or_root']) ? (int) $actorRow['web_restrict_act_as_creator_or_root'] : 0;
        if ($flag !== 1) {
            return true;
        }
        if ($bypass_creator_restrict) {
            return true;
        }
        $srcId = isset($actorRow['actor_source_id']) ? (int) $actorRow['actor_source_id'] : 0;
        $srcType = isset($actorRow['actor_source_type']) ? (string) $actorRow['actor_source_type'] : '';
        if ($srcId === $auth_user_id && ($srcType === 'user' || $srcType === 'lupo_auth_users')) {
            return true;
        }
        return false;
    }

    /**
     * Module owner permission (legacy permissions table) — treated like elevated for act-as restrictions.
     */
    private function authUserHasModuleOwnerPermission($auth_user_id)
    {
        $auth_user_id = (int) $auth_user_id;
        if ($auth_user_id <= 0) {
            return false;
        }
        $perm = $this->db->fetchRow(
            "SELECT 1 AS ok FROM {$this->table_prefix}permissions WHERE user_id = :uid AND permission = 'owner' AND target_type = 'module' AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('uid' => $auth_user_id)
        );
        return !empty($perm);
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
     * Legacy hook: previously cleared "leases" on logout. Actor↔auth mappings stay active; concurrent web use is allowed.
     */
    public function releaseAllLeasesForUser($auth_user_id)
    {
        return;
    }
}
