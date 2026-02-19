<?php
/**
 * Admin Channels section handler. Lists channels from lupo_channels.
 * PHP 5.3+ compatible; no namespaces. Uses PDO_DB and table prefix.
 */

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

class AdminChannelsHandler {

    /**
     * Render the admin Channels section HTML (list only).
     *
     * @param object $db    PDO_DB instance
     * @param string $prefix Table prefix (e.g. lupo_)
     * @param string $base  Base URL path
     * @return string HTML
     */
    public static function render($db, $prefix, $base) {
        $t = $db->quoteIdentifier($prefix . 'channels');
        $channels = $db->fetchAll(
            "SELECT channel_id, channel_key, channel_name, channel_type, channel_slug, status_flag, department_id, created_ymdhis FROM {$t} WHERE (is_deleted = 0 OR is_deleted IS NULL) ORDER BY channel_id LIMIT 500",
            array()
        );
        ob_start();
        include LUPOPEDIA_PATH . '/lupo-includes/themes/default/layouts/admin_sections/channels.php';
        return ob_get_clean();
    }
}
