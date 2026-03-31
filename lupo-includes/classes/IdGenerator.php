<?php
/**
 * Lupopedia ID Generator
 *
 * Generates 63-bit signed-safe BIGINT IDs in the format:
 * YYYYMMDDHHIISS + 4-digit random suffix
 *
 * All primary keys MUST be generated using this class.
 * No AUTO_INCREMENT, sequences, or database-side ID generation.
 *
 * @package Lupopedia
 * @version 4.0.93
 */

class IdGenerator
{
    /**
     * Generate a 63-bit signed-safe unique ID
     *
     * Format: YYYYMMDDHHIISS + 4-digit random suffix
     * Example: 202603301022000001
     *
     * @return string 18-digit BIGINT-safe ID
     */
    public static function generate()
    {
        // UTC timestamp in Lupopedia doctrine format
        $timestamp = gmdate('YmdHis');

        // 4-digit random suffix (0000–9999)
        $suffix = mt_rand(0, 9999);
        $suffix = str_pad($suffix, 4, '0', STR_PAD_LEFT);

        // Concatenate into final ID
        return $timestamp . $suffix;
    }

    /**
     * Validate ID format
     *
     * @param string $id
     * @return bool
     */
    public static function validateFormat($id)
    {
        // Must be exactly 18 digits
        if (!preg_match('/^\d{18}$/', $id)) {
            return false;
        }

        // Extract timestamp portion
        $ts = substr($id, 0, 14);

        // Validate timestamp range (2000–2099)
        if ($ts < '20000101000000' || $ts > '20991231235959') {
            return false;
        }

        return true;
    }

    /**
     * Extract timestamp (YYYYMMDDHHIISS)
     *
     * @param string $id
     * @return string
     */
    public static function extractTimestamp($id)
    {
        return substr($id, 0, 14);
    }

    /**
     * Extract random suffix (0000–9999)
     *
     * @param string $id
     * @return string
     */
    public static function extractSuffix($id)
    {
        return substr($id, 14, 4);
    }
}
