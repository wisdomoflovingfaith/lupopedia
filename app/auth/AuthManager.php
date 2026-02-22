<?php

namespace App\Auth;

/**
 * Auth manager — plain PHP, PDO, doctrine timestamps (BIGINT YmdHis UTC).
 * No Laravel. Requires: LUPO_TABLE_PREFIX, PDO_DB. Tables: lupo_auth_users, lupo_crafty_user_mapping (legacy), lupo_auth_audit_log (see install).
 * All authentication uses lupo_auth_users and role-based authorization; no legacy Crafty operator/user tables.
 */

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

class AuthManager
{
    /** @var \PDO_DB */
    private $db;

    /** @var SessionHandler */
    protected $sessionHandler;

    public function __construct($db, SessionHandler $sessionHandler = null)
    {
        $this->db = $db;
        $this->sessionHandler = $sessionHandler !== null ? $sessionHandler : new SessionHandler($db);
    }

    public function checkUnifiedAuth()
    {
        $unifiedSession = $this->sessionHandler->getUnifiedSessionFromCookie();
        if (!$unifiedSession || empty($unifiedSession['session_id'])) {
            return false;
        }
        return $this->sessionHandler->validateSessionIntegrity($unifiedSession['session_id']);
    }

    public function getUnifiedUser()
    {
        $unifiedSession = $this->sessionHandler->getUnifiedSessionFromCookie();
        if (!$unifiedSession) {
            return null;
        }
        $user = $this->getUserById(isset($unifiedSession['user_id']) ? $unifiedSession['user_id'] : null);
        if ($user) {
            return array(
                'user' => $user,
                'context' => isset($unifiedSession['system_context']) ? $unifiedSession['system_context'] : SessionHandler::CONTEXT_LUPOPEDIA,
                'source' => 'session',
            );
        }
        return null;
    }

    private function getUserById($userId)
    {
        if (!$userId) {
            return null;
        }
        $t = $this->db->quoteIdentifier(LUPO_TABLE_PREFIX . 'auth_users');
        $row = $this->db->fetchRow(
            "SELECT auth_user_id, email, display_name, username FROM $t WHERE auth_user_id = :id AND (is_deleted = 0 OR is_deleted IS NULL)",
            array('id' => $userId)
        );
        if (!$row) {
            return null;
        }
        $name = isset($row['display_name']) ? $row['display_name'] : (isset($row['username']) ? $row['username'] : '');
        return (object) array(
            'id' => $row['auth_user_id'],
            'email' => $row['email'],
            'name' => $name,
            'type' => 'lupopedia',
        );
    }

    /**
     * Get permissions for the current user. Uses lupo_auth_users and lupo_actor_channel_roles (channel-scoped roles only).
     * Default channel for system-wide permissions is channel_id = 1.
     */
    public function getUserPermissions()
    {
        $unifiedUser = $this->getUnifiedUser();
        if (!$unifiedUser) {
            return array();
        }
        $permissions = array('basic_access');
        $user = $unifiedUser['user'];
        if (!isset($user->id)) {
            return $permissions;
        }
        $t = $this->db->quoteIdentifier(LUPO_TABLE_PREFIX . 'actors');
        $actor = $this->db->fetchRow(
            "SELECT actor_id FROM $t WHERE actor_source_type = 'auth_user' AND actor_source_id = :id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('id' => $user->id)
        );
        if ($actor) {
            $roleT = $this->db->quoteIdentifier(LUPO_TABLE_PREFIX . 'actor_channel_roles');
            $roles = $this->db->fetchAll(
                "SELECT role_key FROM $roleT WHERE actor_id = :aid AND channel_id = :cid AND (is_deleted = 0 OR is_deleted IS NULL)",
                array('aid' => $actor['actor_id'], 'cid' => 1)
            );
            foreach ($roles as $r) {
                $roleKey = isset($r['role_key']) ? $r['role_key'] : '';
                if (in_array($roleKey, array('captain', 'administrator'), true)) {
                    $permissions[] = 'admin_access';
                    $permissions[] = 'user_management';
                    $permissions[] = 'system_configuration';
                } elseif ($roleKey === 'editor') {
                    $permissions[] = 'content_editing';
                    $permissions[] = 'collection_management';
                } elseif (in_array($roleKey, array('monitor', 'administrator'), true)) {
                    $permissions[] = 'chat_support';
                    $permissions[] = 'visitor_tracking';
                }
            }
        }
        return array_unique($permissions);
    }

    public function validateAccess($resource, $action = 'read')
    {
        $permissions = $this->getUserPermissions();
        $permissionMap = array(
            'admin' => array('admin_access'),
            'users' => array('user_management'),
            'collections' => array('collection_management', 'content_editing'),
            'chat' => array('chat_support'),
            'analytics' => array('analytics_access'),
        );
        if (isset($permissionMap[$resource])) {
            foreach ($permissionMap[$resource] as $required) {
                if (in_array($required, $permissions)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Get legacy user mapping by lupo user id only (operator-based mapping deprecated).
     */
    public function getUserMapping($userId, $operatorId = null)
    {
        if ($userId === null || $userId === '') {
            return null;
        }
        $t = LUPO_TABLE_PREFIX . 'crafty_user_mapping';
        $row = $this->db->fetchRow(
            'SELECT * FROM ' . $this->db->quoteIdentifier($t) . ' WHERE lupo_user_id = :uid LIMIT 1',
            array('uid' => $userId)
        );
        return $row ? (object) $row : null;
    }

    /**
     * Legacy: create mapping record. Operator IDs deprecated; crafty_operator_id may be null.
     */
    public function createUserMapping($userId, $operatorId = null, $mappingType = 'manual', $notes = null)
    {
        $now = (int) gmdate('YmdHis');
        $t = LUPO_TABLE_PREFIX . 'crafty_user_mapping';
        return $this->db->insert($t, array(
            'lupo_user_id' => $userId,
            'crafty_operator_id' => $operatorId,
            'mapping_type' => $mappingType,
            'notes' => $notes,
            'created_at' => $now,
            'updated_at' => $now,
        ));
    }

    /**
     * Log authentication event. Timestamps: BIGINT YmdHis UTC (created_at, updated_at in lupo_auth_audit_log).
     * $requestInfo: array with 'ip', 'user_agent' (or null to use $_SERVER).
     */
    /**
     * Log authentication event. operatorId deprecated (legacy column, pass null).
     */
    public function logAuthEvent($eventType, $userId = null, $operatorId = null, $systemContext = null, $success = true, $errorMessage = null, $requestInfo = null)
    {
        if ($requestInfo === null) {
            $requestInfo = array(
                'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
                'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
            );
        }
        $now = (int) gmdate('YmdHis');
        $t = LUPO_TABLE_PREFIX . 'auth_audit_log';
        $this->db->insert($t, array(
            'user_id' => $userId,
            'crafty_operator_id' => null,
            'event_type' => $eventType,
            'system_context' => $systemContext !== null ? $systemContext : '',
            'ip_address' => isset($requestInfo['ip']) ? $requestInfo['ip'] : '',
            'user_agent' => isset($requestInfo['user_agent']) ? $requestInfo['user_agent'] : '',
            'success' => $success ? 1 : 0,
            'error_message' => $errorMessage,
            'created_at' => $now,
            'updated_at' => $now,
        ));
    }
}
