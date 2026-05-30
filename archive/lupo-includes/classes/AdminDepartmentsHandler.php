<?php
/**
 * Admin Departments section handler. Lists departments from lupo_departments.
 * PHP 5.3+ compatible; no namespaces. Uses PDO_DB and table prefix.
 */

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

class AdminDepartmentsHandler {

    /**
     * Render the admin Departments section HTML (list only).
     *
     * @param object $db    PDO_DB instance
     * @param string $prefix Table prefix (e.g. lupo_)
     * @param string $base  Base URL path
     * @return string HTML
     */
    public static function render($db, $prefix, $base) {
        $t = $db->quoteIdentifier($prefix . 'departments');
        $departments = $db->fetchAll(
            "SELECT department_id, name, description, department_type, default_actor_id, created_ymdhis FROM {$t} WHERE (is_deleted = 0 OR is_deleted IS NULL) ORDER BY department_id LIMIT 500",
            array()
        );
        ob_start();
        include LUPOPEDIA_PATH . '/lupo-includes/themes/default/layouts/admin_sections/departments.php';
        return ob_get_clean();
    }
}
