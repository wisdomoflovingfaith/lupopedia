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

    /** @var UnifiedSessionHandler */
    protected $sessionHandler;

    public function __construct($db, UnifiedSessionHandler $sessionHandler = null)
    {
        $this->db = $db;
        $this->sessionHandler = $sessionHandler ?? new UnifiedSessionHandler($db);
    }

    public function checkUnifiedAuth(): bool
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
        $user = $this->getUserById($unifiedSession['user_id'] ?? null);
        if ($user) {
            return [
                'user' => $user,
                'context' => $unifiedSession['system_context'] ?? UnifiedSessionHandler::CONTEXT_LUPOPEDIA,
                'source' => 'unified_session',
            ];
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
            ['id' => $userId]
        );
        if (!$row) {
            return null;
        }
        return (object) [
            'id' => $row['auth_user_id'],
            'email' => $row['email'],
            'name' => $row['display_name'] ?? $row['username'] ?? '',
            'type' => 'lupopedia',
        ];
    }

    /**
     * Get permissions for the current user. Uses lupo_auth_users and actor/role tables when available.
     */
    public function getUserPermissions(): array
    {
        $unifiedUser = $this->getUnifiedUser();
        if (!$unifiedUser) {
            return [];
        }
        $permissions = ['basic_access'];
        $user = $unifiedUser['user'];
        if (!isset($user->id)) {
            return $permissions;
        }
        $t = $this->db->quoteIdentifier(LUPO_TABLE_PREFIX . 'actors');
        $actor = $this->db->fetchRow(
            "SELECT actor_id FROM $t WHERE actor_source_type = 'auth_user' AND actor_source_id = :id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            ['id' => $user->id]
        );
        if ($actor) {
            $roleT = $this->db->quoteIdentifier(LUPO_TABLE_PREFIX . 'actor_channel_roles');
            $roles = $this->db->fetchAll(
                "SELECT role_key FROM $roleT WHERE actor_id = :aid",
                ['aid' => $actor['actor_id']]
            );
            foreach ($roles as $r) {
                $key = $r['role_key'] ?? '';
                if ($key === 'admin') {
                    $permissions[] = 'admin_access';
                    $permissions[] = 'user_management';
                    $permissions[] = 'system_configuration';
                } elseif ($key === 'editor') {
                    $permissions[] = 'content_editing';
                    $permissions[] = 'collection_management';
                } elseif ($key === 'operator' || $key === 'support') {
                    $permissions[] = 'chat_support';
                    $permissions[] = 'visitor_tracking';
                }
            }
        }
        return array_unique($permissions);
    }

    public function validateAccess(string $resource, string $action = 'read'): bool
    {
        $permissions = $this->getUserPermissions();
        $permissionMap = [
            'admin' => ['admin_access'],
            'users' => ['user_management'],
            'collections' => ['collection_management', 'content_editing'],
            'chat' => ['chat_support'],
            'analytics' => ['analytics_access'],
        ];
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
            ['uid' => $userId]
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
        return $this->db->insert($t, [
            'lupo_user_id' => $userId,
            'crafty_operator_id' => $operatorId,
            'mapping_type' => $mappingType,
            'notes' => $notes,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * Log authentication event. Timestamps: BIGINT YmdHis UTC (created_at, updated_at in lupo_auth_audit_log).
     * $requestInfo: array with 'ip', 'user_agent' (or null to use $_SERVER).
     */
    /**
     * Log authentication event. operatorId deprecated (legacy column, pass null).
     */
    public function logAuthEvent($eventType, $userId = null, $operatorId = null, $systemContext = null, $success = true, $errorMessage = null, array $requestInfo = null): void
    {
        if ($requestInfo === null) {
            $requestInfo = [
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            ];
        }
        $now = (int) gmdate('YmdHis');
        $t = LUPO_TABLE_PREFIX . 'auth_audit_log';
        $this->db->insert($t, [
            'user_id' => $userId,
            'crafty_operator_id' => null,
            'event_type' => $eventType,
            'system_context' => $systemContext ?? '',
            'ip_address' => $requestInfo['ip'] ?? '',
            'user_agent' => $requestInfo['user_agent'] ?? '',
            'success' => $success ? 1 : 0,
            'error_message' => $errorMessage,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
