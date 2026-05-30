<?php

namespace App\Services;

class RoleEnforcementGuard
{
    /** @var \PDO_DB */
    private $db;

    /** @var string */
    private $prefix;

    public function __construct($db)
    {
        $this->db = $db;
        $this->prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    }

    public function canPerform($actorId, $action)
    {
        $actorId = (int) $actorId;
        if ($actorId <= 0 || !is_string($action) || $action === '') {
            return false;
        }

        $table = $this->db->quoteIdentifier($this->prefix . 'actors');
        $row = $this->db->fetchRow(
            "SELECT agent_role FROM {$table} WHERE actor_id = :actor_id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('actor_id' => $actorId)
        );

        if (!is_array($row) || !isset($row['agent_role'])) {
            return false;
        }

        $role = (string) $row['agent_role'];
        $allowedActions = array(
            'watcher' => array('observe', 'analyze', 'report', 'escalate'),
            'messenger' => array('communicate', 'write', 'read', 'relay'),
            'censer' => array('validate', 'filter', 'reject', 'enforce'),
            'reaper' => array('test', 'break', 'fuzz')
        );

        if (!isset($allowedActions[$role])) {
            return false;
        }

        return in_array($action, $allowedActions[$role], true);
    }

    public function isChannelAllowed($actorId, $channelKey)
    {
        $actorId = (int) $actorId;
        if ($actorId <= 0 || !is_string($channelKey) || $channelKey === '') {
            return false;
        }

        $table = $this->db->quoteIdentifier($this->prefix . 'actors');
        $row = $this->db->fetchRow(
            "SELECT current_channel_key FROM {$table} WHERE actor_id = :actor_id AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1",
            array('actor_id' => $actorId)
        );

        if (!is_array($row)) {
            return false;
        }

        if (!isset($row['current_channel_key']) || $row['current_channel_key'] === null || $row['current_channel_key'] === '') {
            return true;
        }

        return $row['current_channel_key'] === $channelKey;
    }
}
