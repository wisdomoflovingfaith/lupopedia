<?php

namespace App\Http\Controllers\Admin;

use App\Auth\AuthManager;
use App\Auth\UnifiedSessionHandler;

/**
 * Admin authentication controller — plain PHP, PDO. No Laravel.
 * Constructor: ($db, AuthManager $authManager = null, UnifiedSessionHandler $sessionHandler = null).
 * Tables: lupo_crafty_user_mapping (PK crafty_user_mapping_id), lupo_sessions, lupo_auth_audit_log. All timestamps BIGINT YmdHis UTC.
 */

if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

if (!class_exists('timestamp_ymdhis')) {
    $path = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'class-timestamp_ymdhis.php';
    if (is_file($path)) {
        require_once $path;
    }
}

class AuthenticationController
{
    /** @var \PDO_DB */
    private $db;

    /** @var AuthManager */
    protected $authManager;

    /** @var UnifiedSessionHandler */
    protected $sessionHandler;

    public function __construct($db, AuthManager $authManager = null, UnifiedSessionHandler $sessionHandler = null)
    {
        $this->db = $db;
        $this->sessionHandler = $sessionHandler ?? new UnifiedSessionHandler($db);
        $this->authManager = $authManager ?? new AuthManager($db, $this->sessionHandler);
    }

    protected function tablePrefix(): string
    {
        return LUPO_TABLE_PREFIX;
    }

    private function nowYmdHis(): int
    {
        return (int) gmdate('YmdHis');
    }

    /**
     * Get dashboard data (for view or JSON). No Laravel view/response.
     */
    public function getIndexData(): array
    {
        return [
            'mappings' => $this->getUserMappings(),
            'unmappedUsers' => $this->getUnmappedUsers(),
            'stats' => $this->getAuthenticationStats(),
        ];
    }

    /**
     * Get mapping form data. Role-based; no operator list.
     */
    public function getMappingData(): array
    {
        return [
            'lupoUsers' => $this->getLupopediaUsers(),
            'existingMappings' => $this->getUserMappings(),
        ];
    }

    /**
     * Create user mapping. Input: lupo_user_id (auth_user_id), mapping_type, notes. crafty_operator_id deprecated.
     */
    public function storeMapping(array $input, $currentUserId = null): array
    {
        $lupoUserId = $input['lupo_user_id'] ?? null;
        $mappingType = $input['mapping_type'] ?? 'manual';
        $notes = $input['notes'] ?? null;

        if (!$lupoUserId) {
            return ['success' => false, 'message' => 'lupo_user_id required'];
        }

        try {
            $this->authManager->createUserMapping($lupoUserId, null, $mappingType, $notes);
            $this->authManager->logAuthEvent('user_mapping_created', $lupoUserId, null, 'admin', true, null, null);
            return ['success' => true, 'message' => 'User mapping created successfully'];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    /**
     * Delete mapping by crafty_user_mapping_id.
     */
    public function deleteMapping($craftyUserMappingId): array
    {
        $t = $this->tablePrefix() . 'crafty_user_mapping';
        $mapping = $this->db->fetchRow(
            'SELECT * FROM ' . $this->db->quoteIdentifier($t) . ' WHERE crafty_user_mapping_id = :id',
            ['id' => $craftyUserMappingId]
        );
        if (!$mapping) {
            return ['success' => false, 'message' => 'Mapping not found'];
        }
        $this->db->delete($t, 'crafty_user_mapping_id = :id', ['id' => $craftyUserMappingId]);
        $this->authManager->logAuthEvent('user_mapping_deleted', $mapping['lupo_user_id'], null, 'admin', true, null, null);
        return ['success' => true, 'message' => 'Mapping deleted'];
    }

    /**
     * Get user mappings. Joins lupo_auth_users only (no legacy operator tables).
     */
    public function getUserMappings(): array
    {
        $p = $this->tablePrefix();
        $map = $p . 'crafty_user_mapping';
        $auth = $p . 'auth_users';
        $sql = 'SELECT m.*, u.email AS lupo_email, u.display_name AS lupo_name '
            . 'FROM ' . $this->db->quoteIdentifier($map) . ' m '
            . 'LEFT JOIN ' . $this->db->quoteIdentifier($auth) . ' u ON u.auth_user_id = m.lupo_user_id '
            . 'ORDER BY m.created_at DESC';
        return $this->db->fetchAll($sql, []);
    }

    public function getUnmappedUsers(): array
    {
        $p = $this->tablePrefix();
        $map = $p . 'crafty_user_mapping';
        $auth = $p . 'auth_users';
        $sql = 'SELECT u.auth_user_id AS id, u.email, u.display_name AS name FROM ' . $this->db->quoteIdentifier($auth) . ' u '
            . 'LEFT JOIN ' . $this->db->quoteIdentifier($map) . ' m ON m.lupo_user_id = u.auth_user_id '
            . 'WHERE u.is_active = 1 AND (u.is_deleted = 0 OR u.is_deleted IS NULL) AND m.lupo_user_id IS NULL';
        return $this->db->fetchAll($sql, []);
    }

    /**
     * Stats. Uses lupo_sessions (not unified_sessions) and lupo_auth_audit_log. Timestamps YmdHis.
     */
    public function getAuthenticationStats(): array
    {
        $p = $this->tablePrefix();
        $now = $this->nowYmdHis();
        $sessionsTable = $p . 'sessions';
        $mapTable = $p . 'crafty_user_mapping';
        $authTable = $p . 'auth_users';
        $totalMappings = (int) $this->db->fetchOne('SELECT COUNT(*) FROM ' . $this->db->quoteIdentifier($mapTable), []);
        $activeSessions = (int) $this->db->fetchOne(
            'SELECT COUNT(*) FROM ' . $this->db->quoteIdentifier($sessionsTable) . ' WHERE expires_ymdhis IS NULL OR expires_ymdhis > :now',
            ['now' => $now]
        );
        $totalLupoUsers = (int) $this->db->fetchOne('SELECT COUNT(*) FROM ' . $this->db->quoteIdentifier($authTable) . ' WHERE is_active = 1 AND (is_deleted = 0 OR is_deleted IS NULL)', []);
        $mappedUsers = (int) $this->db->fetchOne('SELECT COUNT(DISTINCT lupo_user_id) FROM ' . $this->db->quoteIdentifier($mapTable) . ' WHERE lupo_user_id IS NOT NULL', []);
        return [
            'total_mappings' => $totalMappings,
            'active_sessions' => $activeSessions,
            'total_lupo_users' => $totalLupoUsers,
            'mapped_users' => $mappedUsers,
        ];
    }

    /**
     * Active sessions from lupo_sessions (join lupo_auth_users on actor_id = auth_user_id).
     */
    public function getActiveSessions(): array
    {
        $now = $this->nowYmdHis();
        $p = $this->tablePrefix();
        $s = $p . 'sessions';
        $auth = $p . 'auth_users';
        $sql = 'SELECT s.*, u.email AS user_email, u.display_name AS user_name '
            . 'FROM ' . $this->db->quoteIdentifier($s) . ' s '
            . 'LEFT JOIN ' . $this->db->quoteIdentifier($auth) . ' u ON u.auth_user_id = s.actor_id '
            . 'WHERE (s.expires_ymdhis IS NULL OR s.expires_ymdhis > :now) ORDER BY s.updated_ymdhis DESC';
        return $this->db->fetchAll($sql, ['now' => $now]);
    }

    /**
     * Session stats. created_at in lupo_auth_audit_log is BIGINT YmdHis.
     */
    public function getSessionStats(): array
    {
        $now = $this->nowYmdHis();
        if (class_exists('timestamp_ymdhis')) {
            $dayAgo = timestamp_ymdhis::subtractSeconds($now, 86400);
            $weekAgo = timestamp_ymdhis::subtractSeconds($now, 604800);
            $monthAgo = timestamp_ymdhis::subtractSeconds($now, 2592000);
        } else {
            $dayAgo = $now - 1000000;
            $weekAgo = $now - 7000000;
            $monthAgo = $now - 30000000;
        }
        $p = $this->tablePrefix();
        $sessionsTable = $p . 'sessions';
        $logTable = $p . 'auth_audit_log';
        $totalActive = (int) $this->db->fetchOne(
            'SELECT COUNT(*) FROM ' . $this->db->quoteIdentifier($sessionsTable) . ' WHERE expires_ymdhis IS NULL OR expires_ymdhis > :now',
            ['now' => $now]
        );
        $last24h = (int) $this->db->fetchOne(
            'SELECT COUNT(*) FROM ' . $this->db->quoteIdentifier($logTable) . ' WHERE created_at > :cut',
            ['cut' => $dayAgo]
        );
        $last7d = (int) $this->db->fetchOne(
            'SELECT COUNT(*) FROM ' . $this->db->quoteIdentifier($logTable) . ' WHERE created_at > :cut',
            ['cut' => $weekAgo]
        );
        $last30d = (int) $this->db->fetchOne(
            'SELECT COUNT(*) FROM ' . $this->db->quoteIdentifier($logTable) . ' WHERE created_at > :cut',
            ['cut' => $monthAgo]
        );
        $byContext = [];
        $rows = $this->db->fetchAll(
            'SELECT system_context, COUNT(*) AS cnt FROM ' . $this->db->quoteIdentifier($sessionsTable)
            . ' WHERE expires_ymdhis IS NULL OR expires_ymdhis > :now GROUP BY system_context',
            ['now' => $now]
        );
        foreach ($rows as $r) {
            $byContext[$r['system_context'] ?? 'lupopedia'] = (int) $r['cnt'];
        }
        return [
            'total_active' => $totalActive,
            'last_24h' => $last24h,
            'last_7d' => $last7d,
            'last_30d' => $last30d,
            'by_context' => $byContext,
        ];
    }

    /**
     * Recent auth activity. lupo_auth_audit_log has created_at (YmdHis).
     */
    public function getRecentAuthenticationActivity(int $limit = 50): array
    {
        $p = $this->tablePrefix();
        $log = $p . 'auth_audit_log';
        $auth = $p . 'auth_users';
        $sql = 'SELECT l.*, u.email AS user_email '
            . 'FROM ' . $this->db->quoteIdentifier($log) . ' l '
            . 'LEFT JOIN ' . $this->db->quoteIdentifier($auth) . ' u ON u.auth_user_id = l.user_id '
            . 'ORDER BY l.created_at DESC LIMIT ' . (int) $limit;
        return $this->db->fetchAll($sql, []);
    }

    public function getLastSyncTime()
    {
        $log = $this->tablePrefix() . 'auth_audit_log';
        $row = $this->db->fetchRow(
            'SELECT created_at FROM ' . $this->db->quoteIdentifier($log) . ' WHERE event_type = :ev ORDER BY created_at DESC LIMIT 1',
            ['ev' => 'user_synchronization']
        );
        return $row ? (int) $row['created_at'] : null;
    }

    public function getSyncStatistics(): array
    {
        $log = $this->tablePrefix() . 'auth_audit_log';
        $total = (int) $this->db->fetchOne(
            'SELECT COUNT(*) FROM ' . $this->db->quoteIdentifier($log) . ' WHERE event_type = :ev',
            ['ev' => 'user_synchronization']
        );
        $success = (int) $this->db->fetchOne(
            'SELECT COUNT(*) FROM ' . $this->db->quoteIdentifier($log) . ' WHERE event_type = :ev AND success = 1',
            ['ev' => 'user_synchronization']
        );
        $failed = (int) $this->db->fetchOne(
            'SELECT COUNT(*) FROM ' . $this->db->quoteIdentifier($log) . ' WHERE event_type = :ev AND success = 0',
            ['ev' => 'user_synchronization']
        );
        return ['total_syncs' => $total, 'successful_syncs' => $success, 'failed_syncs' => $failed];
    }

    public function getLupopediaUsers(): array
    {
        $auth = $this->db->quoteIdentifier($this->tablePrefix() . 'auth_users');
        return $this->db->fetchAll(
            "SELECT auth_user_id AS id, email, display_name AS name FROM $auth WHERE is_active = 1 AND (is_deleted = 0 OR is_deleted IS NULL) ORDER BY email",
            []
        );
    }
}
