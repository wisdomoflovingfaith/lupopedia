<?php

namespace App\Auth;

/**
 * Auth role resolution — channel roles (lupo_channel_roles) and permissions (lupo_permissions).
 * Permission is satisfied if: user_id match OR department_id match (actor's departments) OR channel role.
 * Default channel for system-wide behavior is channel_id = 1.
 * Uses PDO_DB and LUPO_TABLE_PREFIX. No DB-side logic. No group tables.
 */

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

class AuthRoleResolver
{
    /** @var \PDO_DB */
    private $db;

    /** @var int Default channel for "global" admin/operator checks */
    private $defaultChannelId = 1;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Whether the actor has admin role (default channel).
     * 1) lupo_channel_roles (channel_id = 1, role_type IN ('captain', 'administrator'))
     * 2) Fallback: lupo_permissions (owner on admin module for auth_user via user_id)
     * 3) Fallback: lupo_permissions (owner on admin module for any of actor's departments via department_id)
     *
     * @param int $actorId Actor ID to check
     * @return bool
     */
    public function isAdmin($actorId)
    {
        if ($actorId <= 0) {
            return false;
        }

        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $cr = $this->db->quoteIdentifier($prefix . 'channel_roles');

        $row = $this->db->fetchRow(
            "SELECT 1 FROM {$cr} WHERE actor_id = :actor_id AND channel_id = :channel_id 
             AND role_type IN ('captain', 'administrator') AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('actor_id' => $actorId, 'channel_id' => $this->defaultChannelId)
        );
        if ($row) {
            return true;
        }

        $authUserId = $this->getAuthUserIdFromActorId($actorId);
        if (!$authUserId) {
            return false;
        }

        $mod = $this->db->quoteIdentifier($prefix . 'modules');
        $adminModule = $this->db->fetchRow(
            "SELECT module_id FROM {$mod} WHERE module_key = 'admin' AND is_active = 1 AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array()
        );
        if (!$adminModule) {
            return false;
        }

        $perm = $this->db->quoteIdentifier($prefix . 'permissions');
        $count = $this->db->fetchRow(
            "SELECT 1 FROM {$perm} WHERE target_type = 'module' AND target_id = :module_id 
             AND user_id = :user_id AND permission = 'owner' AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('module_id' => $adminModule['module_id'], 'user_id' => $authUserId)
        );
        if ($count !== null) {
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
        $cr = $this->db->quoteIdentifier($prefix . 'channel_roles');
        $row = $this->db->fetchRow(
            "SELECT 1 FROM {$cr} WHERE actor_id = :actor_id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
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
     * Get auth_user_id from actor_id (actor_source_id where actor_source_type = 'user').
     *
     * @param int $actorId
     * @return int|null
     */
    private function getAuthUserIdFromActorId($actorId)
    {
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $t = $this->db->quoteIdentifier($prefix . 'actors');
        $row = $this->db->fetchRow(
            "SELECT actor_source_id as auth_user_id FROM {$t} WHERE actor_id = :actor_id 
             AND actor_source_type = 'user' AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('actor_id' => $actorId)
        );
        return $row ? (int) $row['auth_user_id'] : null;
    }
}
