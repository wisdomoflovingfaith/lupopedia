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

/**
 * Lupopedia Deterministic ID Generator (Signed-Safe)
 * Actor: HEPHAESTUS (102)
 * Doctrine: Postgres Compatibility, No UNSIGNED required.
 */
class IdGenerator
{
    private const EPOCH = 1704067200000; // Custom epoch (ms)
    private static $lastTimestamp = -1;
    private static $sequence = 0;

    /**
     * Generate a 63-bit signed-safe unique ID (Snowflake-inspired)
     * [0 (1 bit)] [Timestamp (41 bits)] [Node (10 bits)] [Sequence (11 bits)]
     * @param int $nodeId
     * @return string
     */
    public static function generate($nodeId = 0)
    {
        $timestamp = self::timeGen();
        if ($timestamp === self::$lastTimestamp) {
            self::$sequence = (self::$sequence + 1) & 2047; // 11 bits
            if (self::$sequence === 0) {
                // Sequence overflow, wait for next ms
                do {
                    $timestamp = self::timeGen();
                } while ($timestamp <= self::$lastTimestamp);
            }
        } else {
            self::$sequence = 0;
        }
        self::$lastTimestamp = $timestamp;

        // 63-bit Forge: [0][41][10][11]
        $id = (($timestamp - self::EPOCH) << 21)
            | (($nodeId & 0x3FF) << 11)
            | (self::$sequence & 0x7FF);

        // Ensure positive signed 64-bit integer (63 bits)
        $id = $id & 0x7FFFFFFFFFFFFFFF;
        return (string) $id;
    }

    private static function timeGen()
    {
        // Return current time in ms
        return (int) (microtime(true) * 1000);
    }
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
