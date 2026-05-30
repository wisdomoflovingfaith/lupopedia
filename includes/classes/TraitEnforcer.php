<?php
/**
 * TraitEnforcer — check actor traits and action authorization (4.0.73).
 * PDO_DB only; PHP 5.3 compatible. No namespace.
 * Use LUPO_TABLE_PREFIX for all table names.
 *
 * @package Lupopedia
 * @version 4.0.73
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}

class TraitEnforcer
{
    /** @var PDO_DB */
    private $db;

    /** @var string */
    private $tablePrefix;

    /** @var array|null Cache: actor_id => array(trait_key => true) per federation_node_id */
    private $traitCache = array();

    /**
     * @param PDO_DB|null $db Database connection (default: lupo_get_db())
     */
    public function __construct($db = null)
    {
        $this->db = $db ? $db : (function_exists('lupo_get_db') ? lupo_get_db() : null);
        $this->tablePrefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    }

    /**
     * Check if actor has the given trait (optionally scoped to federation node).
     *
     * @param int $actor_id
     * @param string $trait_key
     * @param int $federation_node_id Default 1
     * @return bool
     */
    public function actorHasTrait($actor_id, $trait_key, $federation_node_id = 1)
    {
        if (!$this->db || $actor_id === null || $trait_key === '') {
            return false;
        }
        $cacheKey = (int) $actor_id . '_' . (int) $federation_node_id;
        if (!isset($this->traitCache[$cacheKey])) {
            $this->traitCache[$cacheKey] = array();
        }
        if (isset($this->traitCache[$cacheKey][$trait_key])) {
            return true;
        }
        $t = $this->tablePrefix . 'actor_traits';
        $sql = "SELECT 1 FROM " . $this->db->quoteIdentifier($t) . "
                WHERE actor_id = :actor_id AND trait_key = :trait_key
                AND (federation_node_id = :fnid OR federation_node_id = 1)
                AND (is_deleted = 0 OR is_deleted IS NULL)
                LIMIT 1";
        $params = array(
            'actor_id' => (int) $actor_id,
            'trait_key' => $trait_key,
            'fnid' => (int) $federation_node_id
        );
        $row = $this->db->fetchRow($sql, $params);
        if ($row) {
            $this->traitCache[$cacheKey][$trait_key] = true;
            return true;
        }
        return false;
    }

    /**
     * Check if action is authorized for actor (and optional channel context).
     * Loads action from lupo_action_authorization; checks required_trait_keys (any match),
     * required_role_keys (any match in channel), and requires_all_conditions.
     *
     * @param int $actor_id
     * @param string $action_key e.g. 'dialog.send_message', 'channel.create'
     * @param int|null $channel_id Optional; required for role checks
     * @return bool
     */
    public function isActionAuthorized($actor_id, $action_key, $channel_id = null)
    {
        if (!$this->db || $actor_id === null || $action_key === '') {
            return false;
        }
        $at = $this->tablePrefix . 'action_authorization';
        $sql = "SELECT action_authorization_id, required_trait_keys, required_capabilities,
                required_role_keys, requires_all_conditions
                FROM " . $this->db->quoteIdentifier($at) . "
                WHERE action_key = :action_key AND 1=1 LIMIT 1";
        $row = $this->db->fetchRow($sql, array('action_key' => $action_key));
        if (!$row) {
            return true;
        }
        $requiresAll = !empty($row['requires_all_conditions']);
        $traitKeys = $this->parseJsonColumn($row['required_trait_keys']);
        $roleKeys = $this->parseJsonColumn($row['required_role_keys']);
        $capKeys = $this->parseJsonColumn($row['required_capabilities']);

        $hasTrait = false;
        if (!empty($traitKeys)) {
            foreach ($traitKeys as $tk) {
                if ($this->actorHasTrait($actor_id, $tk, 1)) {
                    $hasTrait = true;
                    break;
                }
            }
            if (!$hasTrait && $requiresAll) {
                return false;
            }
            if (!$hasTrait && empty($roleKeys) && empty($capKeys)) {
                return false;
            }
        }

        $hasRole = false;
        if (!empty($roleKeys) && $channel_id !== null && (int) $channel_id > 0) {
            $acr = $this->tablePrefix . 'actor_channel_roles';
            $placeholders = array();
            $params = array('actor_id' => (int) $actor_id, 'channel_id' => (int) $channel_id);
            foreach ($roleKeys as $i => $rk) {
                $key = 'rk' . $i;
                $placeholders[] = ':' . $key;
                $params[$key] = $rk;
            }
            $sql = "SELECT 1 FROM " . $this->db->quoteIdentifier($acr) . "
                    WHERE actor_id = :actor_id AND channel_id = :channel_id
                    AND role_key IN (" . implode(', ', $placeholders) . ")
                    AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1";
            $r = $this->db->fetchRow($sql, $params);
            if ($r) {
                $hasRole = true;
            }
            if (!$hasRole && $requiresAll) {
                return false;
            }
        } elseif (!empty($roleKeys) && (int) $channel_id <= 0) {
            if ($requiresAll) {
                return false;
            }
        }

        if ($requiresAll) {
            return ($hasTrait || empty($traitKeys)) && ($hasRole || empty($roleKeys));
        }
        if (empty($traitKeys) && empty($roleKeys) && empty($capKeys)) {
            return true;
        }
        return $hasTrait || $hasRole;
    }

    /**
     * @param string|null $json
     * @return array
     */
    private function parseJsonColumn($json)
    {
        if ($json === null || $json === '') {
            return array();
        }
        $dec = json_decode($json, true);
        return is_array($dec) ? $dec : array();
    }
}
