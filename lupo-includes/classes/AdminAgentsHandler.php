<?php
/**
 * Admin Agents section handler. Lists all actors that are agents with IDE detection and metrics.
 * PHP 5.3+ compatible; no namespaces. Uses PDO_DB and table prefix.
 */

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

class AdminAgentsHandler
{

    /**
     * Render the admin Agents section HTML.
     */
    public static function render($db, $prefix, $base)
    {
        $one_day_ago = date('YmdHis', time() - 86400);

        $t_actors = $db->quoteIdentifier($prefix . 'actors');
        $t_registry = $db->quoteIdentifier($prefix . 'registry');
        $t_dialog = $db->quoteIdentifier($prefix . 'dialog_doctrine');
        $t_tickets = $db->quoteIdentifier($prefix . 'tickets');
        $t_ticket_msgs = $db->quoteIdentifier($prefix . 'ticket_messages');

        // Query pulls all actors that are agents (by type or registry)
        // includes 24h actions, thread count, and unified ticket activity
        $sql = "
            SELECT 
                a.actor_id,
                a.name AS agent_name,
                a.actor_type AS agent_type,
                a.is_active,
                a.created_ymdhis,
                (SELECT MAX(created_ymdhis) FROM {$t_dialog} WHERE from_actor_id = a.actor_id) as last_active_ymdhis,
                (SELECT COUNT(*) FROM {$t_dialog} WHERE from_actor_id = a.actor_id AND created_ymdhis >= '{$one_day_ago}') as actions_24h,
                (SELECT COUNT(DISTINCT dialog_thread_id) FROM {$t_dialog} WHERE from_actor_id = a.actor_id) as thread_count,
                (SELECT COUNT(DISTINCT ticket_id) FROM (
                    SELECT ticket_id FROM {$t_tickets} WHERE actor_id = a.actor_id
                    UNION ALL
                    SELECT ticket_id FROM {$t_ticket_msgs} WHERE actor_id = a.actor_id
                ) AS ticket_activity) as ticket_count,
                r.metadata_json as registry_metadata
            FROM {$t_actors} a
            LEFT JOIN {$t_registry} r ON (r.entity_type = 'actor' AND r.entity_index_id = a.actor_id)
            WHERE a.actor_type = 'agent'
               OR a.actor_id IN (1001, 1002, 1003)
               OR a.is_agent = 1
               OR r.entity_type = 'agent'
               OR (r.entity_type = 'actor' AND r.metadata_json LIKE '%\"agent_role\":%')
            ORDER BY a.actor_id ASC
            LIMIT 1000
        ";

        $rows = $db->fetchAll($sql);
        $agents = array();

        foreach ($rows as $row) {
            // Determine if it's an IDE agent
            $is_ide = 'No';
            if (in_array((int) $row['actor_id'], array(1001, 1002, 1003))) {
                $is_ide = 'Yes';
            } else {
                if (!empty($row['registry_metadata'])) {
                    $meta = json_decode($row['registry_metadata'], true);
                    if (isset($meta['agent_role']) && $meta['agent_role'] === 'ide') {
                        $is_ide = 'Yes';
                    }
                }
            }

            $row['is_ide_agent'] = $is_ide;
            // Map table data to view expectations if names differ
            $agents[] = $row;
        }

        ob_start();
        include LUPOPEDIA_PATH . '/lupo-includes/themes/default/layouts/admin_sections/agents.php';
        return ob_get_clean();
    }

    /**
     * Get complete list of agents with metrics and IDE detection
     *
     * @param object $db    PDO_DB instance
     * @param string $prefix Table prefix
     * @return array Agent list with metrics
     */
    private static function getAgentList($db, $prefix)
    {
        $actorsTable = $db->quoteIdentifier($prefix . 'actors');
        $agentRegistryTable = $db->quoteIdentifier($prefix . 'agent_registry');
        $dialogTable = $db->quoteIdentifier($prefix . 'dialog_doctrine');
        $ticketMessagesTable = $db->quoteIdentifier($prefix . 'ticket_messages');

        // Calculate 24h ago timestamp
        $twentyFourHoursAgo = gmdate('YmdHis', time() - 86400);

        $sql = "
            SELECT 
                a.actor_id,
                a.agent_name,
                a.agent_type,
                a.is_agent,
                a.created_ymdhis,
                a.last_active_ymdhis,
                ar.agent_role,
                ar.agent_capabilities,
                ar.status as registry_status,
                CASE 
                    WHEN ar.agent_role = 'ide' THEN 'Yes'
                    WHEN a.actor_id IN (1001, 1002, 1003) THEN 'Yes'
                    ELSE 'No'
                END as is_ide_agent,
                (
                    SELECT COUNT(*) 
                    FROM {$dialogTable} 
                    WHERE from_actor_id = a.actor_id 
                    AND created_ymdhis > {$twentyFourHoursAgo}
                ) as actions_24h,
                (
                    SELECT COUNT(DISTINCT dialog_thread_id) 
                    FROM {$dialogTable} 
                    WHERE from_actor_id = a.actor_id OR to_actor_id = a.actor_id
                ) as thread_count,
                (
                    SELECT COUNT(DISTINCT ticket_id) 
                    FROM {$ticketMessagesTable} 
                    WHERE actor_id = a.actor_id
                ) as ticket_count
            FROM {$actorsTable} a
            LEFT JOIN {$agentRegistryTable} ar ON a.actor_id = ar.agent_id
            WHERE a.is_agent = 1 OR ar.agent_id IS NOT NULL
            ORDER BY a.actor_id
            LIMIT 500
        ";

        return $db->fetchAll($sql, array());
    }

    /**
     * Get individual agent metrics
     *
     * @param object $db    PDO_DB instance
     * @param string $prefix Table prefix
     * @param int $actorId Actor ID
     * @return array Agent metrics
     */
    public static function getAgentMetrics($db, $prefix, $actorId)
    {
        $dialogTable = $db->quoteIdentifier($prefix . 'dialog_doctrine');
        $ticketMessagesTable = $db->quoteIdentifier($prefix . 'ticket_messages');
        $twentyFourHoursAgo = gmdate('YmdHis', time() - 86400);

        $sql = "
            SELECT 
                (
                    SELECT COUNT(*) 
                    FROM {$dialogTable} 
                    WHERE from_actor_id = :actor_id 
                    AND created_ymdhis > :twenty_four_hours_ago
                ) as actions_24h,
                (
                    SELECT COUNT(DISTINCT dialog_thread_id) 
                    FROM {$dialogTable} 
                    WHERE from_actor_id = :actor_id OR to_actor_id = :actor_id
                ) as thread_count,
                (
                    SELECT COUNT(DISTINCT ticket_id) 
                    FROM {$ticketMessagesTable} 
                    WHERE actor_id = :actor_id
                ) as ticket_count
        ";

        return $db->fetchRow($sql, array(
            'actor_id' => $actorId,
            'twenty_four_hours_ago' => $twentyFourHoursAgo
        ));
    }

    /**
     * Check if actor is an IDE agent
     *
     * @param object $db    PDO_DB instance
     * @param string $prefix Table prefix
     * @param int $actorId Actor ID
     * @return boolean True if IDE agent
     */
    public static function isIDEAgent($db, $prefix, $actorId)
    {
        $agentRegistryTable = $db->quoteIdentifier($prefix . 'agent_registry');

        $sql = "
            SELECT agent_role 
            FROM {$agentRegistryTable} 
            WHERE agent_id = :actor_id 
            AND agent_role = 'ide'
            LIMIT 1
        ";

        $result = $db->fetchRow($sql, array('actor_id' => $actorId));

        // Check registry first, then fallback to known IDE actor IDs
        if ($result) {
            return true;
        }

        // Fallback to known IDE actor IDs
        return in_array($actorId, array(1001, 1002, 1003));
    }

    /**
     * Get agent status based on last activity
     *
     * @param int $lastActive Last active timestamp
     * @return string Agent status
     */
    public static function getAgentStatus($lastActive)
    {
        if (!$lastActive || $lastActive == 0) {
            return 'archived';
        }

        $now = time();
        $lastActiveTime = strtotime($lastActive . ' UTC');

        if ($lastActiveTime === false) {
            return 'archived';
        }

        $hoursSinceActive = ($now - $lastActiveTime) / 3600;

        if ($hoursSinceActive <= 24) {
            return 'active';
        } elseif ($hoursSinceActive <= 168) { // 7 days
            return 'dormant';
        } else {
            return 'archived';
        }
    }
}
