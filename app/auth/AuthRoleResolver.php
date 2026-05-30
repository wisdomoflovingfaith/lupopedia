<?php

namespace App\Auth;

/**
 * Auth role resolution — 3-layer permission model:
 * 1. Channel roles (lupo_actor_channel_roles: captain, administrator, monitor)
 * 2. Department roles (lupo_department_roles: administrator for channel's department)
 * 3. System roles (department_id = 0: administrator = global admin)
 *
 * Resolution order: channel → department → system. If any match → permission granted.
 * Default channel for global admin is channel_id = 1.
 * Uses PDO_DB and LUPO_TABLE_PREFIX. No DB-side logic. No group tables.
 */

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

/** Reserved department_id for system/global roles; not user-selectable. */
if (!defined('LUPO_SYSTEM_DEPARTMENT_ID')) {
    define('LUPO_SYSTEM_DEPARTMENT_ID', 0);
}

class AuthRoleResolver
{
    /** @var \PDO_DB */
    private $db;

    /** @var int Default channel for "global" admin checks */
    private $defaultChannelId = 1;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Whether the actor has admin role (global; uses default channel 1).
     * 3-layer resolution order:
     * 1) Channel roles (channel 1: captain/administrator)
     * 2) Department roles (channel 1's department: administrator)
     * 3) System roles (department 0: administrator = global admin)
     * 4) Fallback: lupo_permissions (owner on admin module)
     *
     * @param int $actorId Actor ID to check
     * @return bool
     */
    public function isAdmin($actorId)
    {
        return $this->hasAdminForChannel($actorId, $this->defaultChannelId);
    }

    /**
     * Whether the actor has admin-level permission for a channel.
     * 3-layer resolution order:
     * 1) Channel roles (captain/administrator/monitor in this channel)
     * 2) Department roles (administrator in this channel's department)
     * 3) System roles (department 0: administrator = global admin)
     *
     * @param int $actorId   Actor ID to check
     * @param int $channelId Channel ID (uses channel 1 for global admin)
     * @return bool
     */
    public function hasAdminForChannel($actorId, $channelId)
    {
        if ($actorId <= 0) {
            return false;
        }

        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $acr = $this->db->quoteIdentifier($prefix . 'actor_channel_roles');
        $dr = $this->db->quoteIdentifier($prefix . 'department_roles');

        // Layer 1: Channel roles (captain, administrator, monitor)
        $row = $this->db->fetchRow(
            "SELECT 1 FROM {$acr} WHERE actor_id = :actor_id AND channel_id = :channel_id 
             AND role_key IN ('captain', 'administrator', 'monitor') AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('actor_id' => $actorId, 'channel_id' => $channelId)
        );
        if ($row) {
            return true;
        }

        // Layer 2: Department roles for this channel's department
        $deptId = $this->getDepartmentIdForChannel($channelId);
        if ($deptId !== null && $deptId >= 0) {
            $row = $this->db->fetchRow(
                "SELECT 1 FROM {$dr} WHERE actor_id = :actor_id AND department_id = :dept_id 
                 AND role_key = 'administrator' AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
                array('actor_id' => $actorId, 'dept_id' => $deptId)
            );
            if ($row) {
                return true;
            }
        }

        // Layer 3: System roles (department 0 = global admin)
        $row = $this->db->fetchRow(
            "SELECT 1 FROM {$dr} WHERE actor_id = :actor_id AND department_id = 0 
             AND role_key = 'administrator' AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('actor_id' => $actorId)
        );
        if ($row) {
            return true;
        }

        // For global admin (channel 1): fallback to lupo_permissions
        if ($channelId == $this->defaultChannelId) {
            if ($this->hasAdminViaPermissions($actorId)) {
                return true;
            }
            if ($this->systemHasNoAdmins()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Fallback: lupo_permissions owner on admin module (user_id or department_id).
     *
     * @param int $actorId
     * @return bool
     */
    private function hasAdminViaPermissions($actorId)
    {
        $authUserId = $this->getAuthUserIdFromActorId($actorId);
        if (!$authUserId) {
            return false;
        }
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $mod = $this->db->quoteIdentifier($prefix . 'modules');
        $adminModule = $this->db->fetchRow(
            "SELECT module_id FROM {$mod} WHERE module_key = 'admin' AND is_active = 1 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array()
        );
        if (!$adminModule) {
            return false;
        }
        $perm = $this->db->quoteIdentifier($prefix . 'permissions');
        $row = $this->db->fetchRow(
            "SELECT 1 FROM {$perm} WHERE target_type = 'module' AND target_id = :module_id 
             AND user_id = :user_id AND permission = 'owner' AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('module_id' => $adminModule['module_id'], 'user_id' => $authUserId)
        );
        if ($row !== null) {
            return true;
        }
        $departmentIds = $this->getDepartmentIdsForActor($actorId);
        if (!empty($departmentIds)) {
            $placeholders = array();
            $params = array('module_id' => $adminModule['module_id']);
            foreach (array_values($departmentIds) as $i => $did) {
                $key = 'dept_' . $i;
                $placeholders[] = ':' . $key;
                $params[$key] = $did;
            }
            $inList = implode(', ', $placeholders);
            $row = $this->db->fetchRow(
                "SELECT 1 FROM {$perm} WHERE target_type = 'module' AND target_id = :module_id 
                 AND department_id IN ({$inList}) AND permission = 'owner' AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
                $params
            );
            if ($row !== null) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get department_id for a channel. Returns null if channel not found.
     *
     * @param int $channelId
     * @return int|null
     */
    private function getDepartmentIdForChannel($channelId)
    {
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $ch = $this->db->quoteIdentifier($prefix . 'channels');
        $row = $this->db->fetchRow(
            "SELECT department_id FROM {$ch} WHERE channel_id = :channel_id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('channel_id' => $channelId)
        );
        return ($row !== null && isset($row['department_id'])) ? (int) $row['department_id'] : null;
    }

    /**
     * When the install has no admin roles yet, grant channel 1 captain + system administrator to this actor.
     *
     * @param int $actorId
     * @return void
     */
    public function ensureBootstrapAdminForActor($actorId)
    {
        $actorId = (int) $actorId;
        if ($actorId <= 0 || $this->isAdmin($actorId) || !$this->systemHasNoAdmins()) {
            return;
        }
        $this->grantChannelRoleIfMissing($actorId, $this->defaultChannelId, 'captain');
        $this->grantSystemAdministratorIfMissing($actorId);
    }

    /**
     * Whether the system has zero users with admin role (actor_channel_roles or permissions).
     * Used for bootstrap-first-admin fallback.
     *
     * @return bool
     */
    private function systemHasNoAdmins()
    {
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $dr = $this->db->quoteIdentifier($prefix . 'department_roles');
        $row = $this->db->fetchRow(
            "SELECT 1 FROM {$dr} WHERE department_id = 0 AND role_key = 'administrator' 
             AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array()
        );
        if ($row !== null) {
            return false;
        }
        $acr = $this->db->quoteIdentifier($prefix . 'actor_channel_roles');
        $row = $this->db->fetchRow(
            "SELECT 1 FROM {$acr} WHERE channel_id = :channel_id AND role_key IN ('captain', 'administrator') 
             AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('channel_id' => $this->defaultChannelId)
        );
        if ($row !== null) {
            return false;
        }
        $mod = $this->db->quoteIdentifier($prefix . 'modules');
        $adminModule = $this->db->fetchRow(
            "SELECT module_id FROM {$mod} WHERE module_key = 'admin' AND is_active = 1 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array()
        );
        if ($adminModule) {
            $perm = $this->db->quoteIdentifier($prefix . 'permissions');
            $row = $this->db->fetchRow(
                "SELECT 1 FROM {$perm} WHERE target_type = 'module' AND target_id = :module_id 
                 AND permission = 'owner' AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
                array('module_id' => $adminModule['module_id'])
            );
            if ($row !== null) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param int $actorId
     * @param int $channelId
     * @param string $roleKey
     * @return void
     */
    private function grantChannelRoleIfMissing($actorId, $channelId, $roleKey)
    {
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $acr = $this->db->quoteIdentifier($prefix . 'actor_channel_roles');
        $row = $this->db->fetchRow(
            "SELECT 1 FROM {$acr} WHERE actor_id = :actor_id AND channel_id = :channel_id AND role_key = :role_key AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('actor_id' => (int) $actorId, 'channel_id' => (int) $channelId, 'role_key' => (string) $roleKey)
        );
        if ($row !== null) {
            return;
        }
        if (!class_exists('IdGenerator', false) && defined('LUPOPEDIA_PATH')) {
            require_once LUPOPEDIA_PATH . '/includes/classes/IdGenerator.php';
        }
        if (!class_exists('IdGenerator', false)) {
            return;
        }
        $now = (int) gmdate('YmdHis');
        $pk = IdGenerator::generate();
        $this->db->insert($prefix . 'actor_channel_roles', array(
            'actor_channel_role_id' => $pk,
            'actor_id' => (int) $actorId,
            'actor_name' => null,
            'channel_id' => (int) $channelId,
            'role_key' => (string) $roleKey,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
            'deleted_ymdhis' => null,
        ));
    }

    /**
     * @param int $actorId
     * @return void
     */
    private function grantSystemAdministratorIfMissing($actorId)
    {
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $dr = $this->db->quoteIdentifier($prefix . 'department_roles');
        $row = $this->db->fetchRow(
            "SELECT 1 FROM {$dr} WHERE actor_id = :actor_id AND department_id = 0 AND role_key = 'administrator' AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('actor_id' => (int) $actorId)
        );
        if ($row !== null) {
            return;
        }
        if (!class_exists('IdGenerator', false) && defined('LUPOPEDIA_PATH')) {
            require_once LUPOPEDIA_PATH . '/includes/classes/IdGenerator.php';
        }
        if (!class_exists('IdGenerator', false)) {
            return;
        }
        $now = (int) gmdate('YmdHis');
        $pk = IdGenerator::generate();
        $this->db->insert($prefix . 'department_roles', array(
            'department_role_id' => $pk,
            'actor_id' => (int) $actorId,
            'department_id' => 0,
            'role_key' => 'administrator',
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
            'deleted_ymdhis' => null,
        ));
    }

    /**
     * Whether the actor has any channel role (any channel). Used for "is_operator" UI.
     *
     * @param int $actorId Actor ID
     * @return bool
     */
    public function hasAnyChannelRole($actorId)
    {
        if ($actorId <= 0) {
            return false;
        }
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $acr = $this->db->quoteIdentifier($prefix . 'actor_channel_roles');
        $row = $this->db->fetchRow(
            "SELECT 1 FROM {$acr} WHERE actor_id = :actor_id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('actor_id' => $actorId)
        );
        return $row !== null;
    }

    /**
     * Get department_id list for actor from lupo_actor_departments.
     *
     * @param int $actorId Actor ID
     * @return array List of department_id (integers)
     */
    private function getDepartmentIdsForActor($actorId)
    {
        if ($actorId <= 0) {
            return array();
        }
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $t = $this->db->quoteIdentifier($prefix . 'actor_departments');
        $rows = $this->db->fetchAll(
            "SELECT department_id FROM {$t} WHERE actor_id = :actor_id AND (is_deleted = 0 OR is_deleted IS NULL)",
            array('actor_id' => $actorId)
        );
        $out = array();
        foreach ($rows as $r) {
            if (isset($r['department_id']) && $r['department_id'] !== null && $r['department_id'] !== '') {
                $out[] = (int) $r['department_id'];
            }
        }
        return $out;
    }

    /**
     * Get auth_user_id from actor_id (actor_source_id where actor_source_type is user-type).
     * Accepts 'user' and 'lupo_auth_users' so imported Crafty operators (stored as lupo_auth_users) resolve.
     *
     * @param int $actorId
     * @return int|null
     */
    private function getAuthUserIdFromActorId($actorId)
    {
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $authTable = $prefix . 'auth_users';
        $t = $this->db->quoteIdentifier($prefix . 'actors');
        $row = $this->db->fetchRow(
            "SELECT actor_source_id as auth_user_id FROM {$t} WHERE actor_id = :actor_id 
             AND (actor_source_type = 'user' OR actor_source_type = :auth_table) AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('actor_id' => $actorId, 'auth_table' => $authTable)
        );
        return $row ? (int) $row['auth_user_id'] : null;
    }
}
