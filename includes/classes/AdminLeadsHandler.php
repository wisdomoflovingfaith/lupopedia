<?php
/**
 * Admin Leads section handler. Lists CRM leads from lupo_crm_leads.
 * PHP 5.3+ compatible; no namespaces. Uses PDO_DB and table prefix.
 */

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

class AdminLeadsHandler {

    /**
     * Render the admin Leads section HTML (list only).
     *
     * @param object $db    PDO_DB instance
     * @param string $prefix Table prefix (e.g. lupo_)
     * @param string $base  Base URL path
     * @return string HTML
     */
    public static function render($db, $prefix, $base) {
        $t = $db->quoteIdentifier($prefix . 'crm_leads');
        $leads = $db->fetchAll(
            "SELECT crm_lead_id, email, first_name, last_name, source, status, lead_score, assigned_to, created_ymdhis FROM {$t} WHERE (is_deleted = 0 OR is_deleted IS NULL) ORDER BY created_ymdhis DESC LIMIT 500",
            array()
        );
        ob_start();
        include LUPOPEDIA_PATH . '/includes/themes/default/layouts/admin_sections/leads.php';
        return ob_get_clean();
    }
}
