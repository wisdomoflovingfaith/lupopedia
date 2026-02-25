<?php
/**
 * TimestampHelper - Utility class for UTC timestamp generation and validation
 * 
 * Provides methods for generating and validating timestamps in YYYYMMDDHHMMSS format.
 * All timestamps are in UTC timezone to ensure consistency across the system.
 * 
 * Usage:
 *   $helper = new TimestampHelper();
 *   $now = $helper->getCurrentUTC(); // "20260224153045"
 *   $isValid = $helper->isValidTimestamp($now); // true
 *   $display = $helper->formatForDisplay($now); // "2026-02-24 15:30:45 UTC"
 * 
 * @package Lupopedia\Services\Initialization
 * @since 4.0.44
 */
class TimestampHelper implements TimestampHelperInterface
{
    /**
     * Get current UTC timestamp in YYYYMMDDHHMMSS format
     * 
     * Uses gmdate() to ensure UTC timezone regardless of server configuration.
     * Format: YYYYMMDDHHMMSS (14 digits, no separators)
     * Example: 20260224153045 represents 2026-02-24 15:30:45 UTC
     * 
     * @return string Current UTC timestamp in YYYYMMDDHHMMSS format
     */
    public function getCurrentUTC()
    {
        return gmdate('YmdHis');
    }
    
    /**
     * Validate timestamp format
     * 
     * Checks if timestamp:
     * 1. Matches YYYYMMDDHHMMSS pattern (14 digits)
     * 2. Represents a valid date/time (e.g., not 20261399999999)
     * 
     * @param string $timestamp Timestamp to validate
     * @return bool True if valid YYYYMMDDHHMMSS timestamp, false otherwise
     */
    public function isValidTimestamp($timestamp)
    {
        // Check if timestamp is a string of exactly 14 digits
        if (!is_string($timestamp) || !preg_match('/^\d{14}$/', $timestamp)) {
            return false;
        }
        
        // Extract components
        $year = (int) substr($timestamp, 0, 4);
        $month = (int) substr($timestamp, 4, 2);
        $day = (int) substr($timestamp, 6, 2);
        $hour = (int) substr($timestamp, 8, 2);
        $minute = (int) substr($timestamp, 10, 2);
        $second = (int) substr($timestamp, 12, 2);
        
        // Validate date components
        if (!checkdate($month, $day, $year)) {
            return false;
        }
        
        // Validate time components
        if ($hour < 0 || $hour > 23) {
            return false;
        }
        if ($minute < 0 || $minute > 59) {
            return false;
        }
        if ($second < 0 || $second > 59) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Format timestamp for human-readable display
     * 
     * Converts YYYYMMDDHHMMSS format to ISO-like format with UTC indicator.
     * Example: "20260224153045" becomes "2026-02-24 15:30:45 UTC"
     * 
     * @param string $timestamp Timestamp in YYYYMMDDHHMMSS format
     * @return string Human-readable timestamp with UTC indicator
     */
    public function formatForDisplay($timestamp)
    {
        // Validate timestamp first
        if (!$this->isValidTimestamp($timestamp)) {
            return 'Invalid timestamp';
        }
        
        // Extract components
        $year = substr($timestamp, 0, 4);
        $month = substr($timestamp, 4, 2);
        $day = substr($timestamp, 6, 2);
        $hour = substr($timestamp, 8, 2);
        $minute = substr($timestamp, 10, 2);
        $second = substr($timestamp, 12, 2);
        
        // Format as YYYY-MM-DD HH:MM:SS UTC
        return sprintf(
            '%s-%s-%s %s:%s:%s UTC',
            $year,
            $month,
            $day,
            $hour,
            $minute,
            $second
        );
    }
}
