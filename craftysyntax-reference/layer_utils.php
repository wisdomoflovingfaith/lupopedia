<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Live Help                ~~    -- *
//===========================================================================
// Helper utilities for working with layer invite imagemap files.
// Provides sanitisation so that only trusted markup is ingested from disk.
//===========================================================================

if (!defined('IS_SECURE')) {
    exit;
}

if (!function_exists('cslh_safe_imagemap_from_file')) {
    /**
     * Loads a layer invite imagemap from disk and removes any unsafe content.
     *
     * The function rejects PHP tags, inline JavaScript/event handlers, and any
     * attempts to reference external protocols. Only <map>/<area> markup is
     * preserved.
     *
     * @param string $fullPath Absolute/relative path to the imagemap txt file.
     *
     * @return string Sanitised imagemap markup. Empty string when the file
     *                cannot be read or contains unsafe content.
     */
    function cslh_safe_imagemap_from_file($fullPath)
    {
        if (!is_file($fullPath) || !is_readable($fullPath)) {
            return '';
        }

        $raw = file_get_contents($fullPath);
        if ($raw === false) {
            return '';
        }

        // Remove null bytes and normalise line endings.
        $raw = str_replace("\0", '', $raw);

        // Reject any PHP opening/closing tags outright.
        if (preg_match('/<\?(php)?/i', $raw) || strpos($raw, '?>') !== false) {
            error_log("CRAFTY SYNTAX blocked a layer invite containing PHP tags: {$fullPath}");
            return '';
        }

        // Block javascript:, data:, vbscript:, as well as inline event handlers.
        if (preg_match('/(?:javascript|vbscript|data)\s*:/i', $raw) ||
            preg_match('/on\w+\s*=/i', $raw)) {
            error_log("CRAFTY SYNTAX blocked a layer invite containing script handlers: {$fullPath}");
            return '';
        }

        // Allow only MAP/AREA markup – everything else is stripped.
        $allowedTags = '<map><MAP><area><AREA>';
        $sanitised = strip_tags($raw, $allowedTags);

        // Trim excessive whitespace and limit to a sensible size.
        $sanitised = trim($sanitised);
        if (strlen($sanitised) > 10000) {
            $sanitised = substr($sanitised, 0, 10000);
        }

        return $sanitised;
    }
}



