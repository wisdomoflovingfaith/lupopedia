<?php
/**
 * Interface for timestamp utilities
 * 
 * Defines the contract for generating and validating UTC timestamps
 * in YYYYMMDDHHMMSS format.
 * 
 * @package Lupopedia\Services\Initialization\Interfaces
 * @since 4.0.44
 */
interface TimestampHelperInterface
{
    /**
     * Get current UTC timestamp in YYYYMMDDHHMMSS format
     * 
     * @return string Current UTC timestamp (e.g., "20260224153045")
     */
    public function getCurrentUTC();
    
    /**
     * Validate timestamp format
     * 
     * Checks if timestamp matches YYYYMMDDHHMMSS pattern and represents
     * a valid date/time.
     * 
     * @param string $timestamp Timestamp to validate
     * @return bool True if valid, false otherwise
     */
    public function isValidTimestamp($timestamp);
    
    /**
     * Format timestamp for human-readable display
     * 
     * @param string $timestamp Timestamp in YYYYMMDDHHMMSS format
     * @return string Human-readable timestamp (e.g., "2026-02-24 15:30:45 UTC")
     */
    public function formatForDisplay($timestamp);
}
