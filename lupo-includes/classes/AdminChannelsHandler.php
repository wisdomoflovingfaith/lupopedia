<?php
/**
 * Admin Channels section handler. Lists channels from lupo_channels.
 * PHP 5.3+ compatible; no namespaces. Uses PDO_DB and table prefix.
 */

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

class AdminChannelsHandler
{

    public static function render($db, $prefix, $base)
    {
        $threshold = date('YmdHis', strtotime('-24 hours'));
        $tChannels = $db->quoteIdentifier($prefix . 'channels');
        $tDialog = $db->quoteIdentifier($prefix . 'dialog_doctrine');
        $tThreads = $db->quoteIdentifier($prefix . 'dialog_threads');
        $tTickets = $db->quoteIdentifier($prefix . 'tickets');

        $channels = $db->fetchAll(
            "SELECT channel_id, channel_key, channel_name, channel_type, channel_slug, status_flag, department_id FROM {$tChannels} WHERE (is_deleted = 0 OR is_deleted IS NULL) ORDER BY channel_id LIMIT 500",
            array()
        );

        foreach ($channels as &$ch) {
            $cid = (int) $ch['channel_id'];

            // Active Actors (24h)
            $ch['active_actors_24h'] = (int) $db->fetchColumn(
                "SELECT COUNT(DISTINCT from_actor_id) FROM {$tDialog} WHERE channel_id = ? AND created_ymdhis >= ?",
                array($cid, $threshold)
            );

            // Thread Count
            $ch['thread_count'] = (int) $db->fetchColumn(
                "SELECT COUNT(*) FROM {$tThreads} WHERE channel_id = ? AND (is_deleted = 0 OR is_deleted IS NULL)",
                array($cid)
            );

            // Ticket Count
            $ch['ticket_count'] = (int) $db->fetchColumn(
                "SELECT COUNT(*) FROM {$tTickets} WHERE channel_id = ? AND (is_deleted = 0 OR is_deleted IS NULL)",
                array($cid)
            );

            // Open Ticket Count
            $ch['open_tickets'] = (int) $db->fetchColumn(
                "SELECT COUNT(*) FROM {$tTickets} WHERE channel_id = ? AND status = 'open' AND (is_deleted = 0 OR is_deleted IS NULL)",
                array($cid)
            );

            // Last Activity
            $lastMsg = (int) $db->fetchColumn("SELECT MAX(created_ymdhis) FROM {$tDialog} WHERE channel_id = ?", array($cid));
            $lastTkt = (int) $db->fetchColumn("SELECT MAX(updated_ymdhis) FROM {$tTickets} WHERE channel_id = ?", array($cid));
            $ch['last_activity'] = max($lastMsg, $lastTkt);
        }

        ob_start();
        include LUPOPEDIA_PATH . '/lupo-includes/themes/default/layouts/admin_sections/channels.php';
        return ob_get_clean();
    }

}
