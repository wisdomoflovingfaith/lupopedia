<?php
/**
 * Dialog helper functions - PHP 5.3 compatible
 * 
 * @package Lupopedia
 */

if (!function_exists('lupo_get_dialog_verifier')) {
    /**
     * Get DialogMessageVerifier instance
     * 
     * @return DialogMessageVerifier|null
     */
    function lupo_get_dialog_verifier() {
        global $mydatabase;
        if (!$mydatabase) {
            return null;
        }
        static $verifier = null;
        if ($verifier === null) {
            require_once LUPOPEDIA_PATH . '/includes/classes/DialogMessageVerifier.php';
            $verifier = new DialogMessageVerifier($mydatabase);
        }
        return $verifier;
    }
}

if (!function_exists('lupo_verify_dialog_counts')) {
    /**
     * Verify dialog message counts and return report
     * 
     * @return array
     */
    function lupo_verify_dialog_counts() {
        $verifier = lupo_get_dialog_verifier();
        if (!$verifier) {
            return array('error' => 'Database connection not available');
        }
        return $verifier->generateVerificationReport();
    }
}

if (!function_exists('lupo_list_dialog_messages')) {
    /**
     * List all dialog messages with optional limit
     * 
     * @param int $limit Maximum messages to return
     * @param int $offset Offset for pagination
     * @return array
     */
    function lupo_list_dialog_messages($limit = 0, $offset = 0) {
        $verifier = lupo_get_dialog_verifier();
        if (!$verifier) {
            return array();
        }
        return $verifier->getAllMessages($limit, $offset);
    }
}

if (!function_exists('lupo_get_messages_by_origin')) {
    /**
     * Get messages that originated from a specific actor (via forwarded_for)
     * 
     * @param int $actor_id
     * @return array
     */
    function lupo_get_messages_by_origin($actor_id) {
        $verifier = lupo_get_dialog_verifier();
        if (!$verifier) {
            return array();
        }
        return $verifier->getMessagesByOrigin($actor_id);
    }
}

if (!function_exists('lupo_display_dialog_report')) {
    /**
     * Display verification report as HTML
     * 
     * @return string
     */
    function lupo_display_dialog_report() {
        $verifier = lupo_get_dialog_verifier();
        if (!$verifier) {
            return '<p>Database connection not available</p>';
        }
        return $verifier->renderReportHtml();
    }
}
