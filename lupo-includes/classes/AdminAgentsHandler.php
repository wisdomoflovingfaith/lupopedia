<?php
/**
 * Admin Agents section handler. Lists agents from lupo_agents.
 * PHP 5.3+ compatible; no namespaces. Uses PDO_DB and table prefix.
 */

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

class AdminAgentsHandler {

    /**
     * Render the admin Agents section HTML (list only).
     *
     * @param object $db    PDO_DB instance
     * @param string $prefix Table prefix (e.g. lupo_)
     * @param string $base  Base URL path
     * @return string HTML
     */
    public static function render($db, $prefix, $base) {
        $t = $db->quoteIdentifier($prefix . 'agents');
        $agents = $db->fetchAll(
            "SELECT agent_id, agent_key, agent_name, archetype, version, is_deleted, created_ymdhis FROM {$t} WHERE (is_deleted = 0 OR is_deleted IS NULL) ORDER BY agent_id LIMIT 500",
            array()
        );
        ob_start();
        include LUPOPEDIA_PATH . '/lupo-includes/themes/default/layouts/admin_sections/agents.php';
        return ob_get_clean();
    }
}
