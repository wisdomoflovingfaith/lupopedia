<?php
/**
 * ID Generator - Simplified for Catch-and-Retry Pattern
 * 
 * Generates timestamp-based unique IDs.
 * Collision handling is done by DatabaseFactory::insertWithRetry()
 * using catch-and-retry pattern to avoid TOCTOU race conditions.
 * 
 * Format: YYYYMMDDHHIISS + 4-digit sequence
 * Example: 202603281200000001
 * 
 * @package Lupopedia
 * @version 4.0.89
 */

class IdGenerator
{
    /**
     * Last timestamp used for ID generation
     * @var string
     */
    private static $last_timestamp = '';
    
    /**
     * Sequence number for the current timestamp
     * @var int
     */
    private static $sequence = 0;
    
    /**
     * Generate a timestamp-based ID
     * 
     * This method just generates IDs. Collision detection and retry
     * is handled by DatabaseFactory::insertWithRetry() to avoid
     * TOCTOU race conditions.
     * 
     * @return string Generated ID
     */
    /**
     * Generate timestamp + 4-digit random content_id (optionally check DB for collision)
     * @param PDO_DB|null $db Optional DB connection for collision check
     * @param int $retryCount
     * @param int $maxRetries
     * @return int
     */
    public static function generate($db = null, $retryCount = 0, $maxRetries = 3)
    {
        $timestamp = gmdate('YmdHis');
        $random = random_int(1, 9999);
        $contentId = (int)($timestamp . str_pad($random, 4, '0', STR_PAD_LEFT));
        // If DB provided, check for collision
        if ($db && $retryCount < $maxRetries) {
            $row = $db->fetchRow('SELECT content_id FROM lupo_contents WHERE content_id = :cid', array('cid' => $contentId));
            if ($row) {
                // Collision! Retry
                return self::generate($db, $retryCount + 1, $maxRetries);
            }
        }
        return $contentId;
    }
    
    /**
     * Generate ID without sequence reset (for performance)
     * 
     * Use this when you're sure no collisions will occur
     * or when collision handling is done at the database layer.
     * 
     * @return string Generated ID
     */
    public static function generateFast()
    {
        $timestamp = gmdate('YmdHis');
        
        // Reset sequence if timestamp changed
        if ($timestamp !== self::$last_timestamp) {
            self::$last_timestamp = $timestamp;
            self::$sequence = 0;
        }
        
        self::$sequence++;
        
        // If we exceed 9,999 in one second, wait 10ms and retry
        if (self::$sequence > 9999) {
            usleep(10000); // 10 milliseconds
            return self::generateFast();
        }
        
        return $timestamp . str_pad(self::$sequence, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Reset the generator state (useful for testing)
     */
    public static function reset()
    {
        self::$last_timestamp = '';
        self::$sequence = 0;
    }
    
    /**
     * Get current timestamp without generating ID
     * 
     * @return string Current timestamp in YYYYMMDDHHIISS format
     */
    public static function getCurrentTimestamp()
    {
        return gmdate('YmdHis');
    }
    
    /**
     * Validate ID format
     * 
     * @param string $id ID to validate
     * @return bool True if valid format
     */
    public static function validateFormat($id)
    {
        return preg_match('/^\d{17}$/', $id) && 
               substr($id, 0, 14) >= '20000101000000' && 
               substr($id, 0, 14) <= '20991223595959' &&
               substr($id, 14, 4) >= '0001' && 
               substr($id, 14, 4) <= '9999';
    }
    
    /**
     * Extract timestamp from ID
     * 
     * @param string $id ID to extract from
     * @return string Timestamp part (YYYYMMDDHHIISS)
     */
    public static function extractTimestamp($id)
    {
        return substr($id, 0, 14);
    }
    
    /**
     * Extract sequence from ID
     * 
     * @param string $id ID to extract from
     * @return string Sequence part (4-digit)
     */
    public static function extractSequence($id)
    {
        return substr($id, 14, 4);
    }
}
