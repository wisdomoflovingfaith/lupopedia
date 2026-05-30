<?php
/**
 * Hybrid Actor Security Service
 * 
 * Centralized security gate for hybrid actors (Actor 420 and similar)
 * Enforces operational status across all entry points
 * 
 * @package Lupopedia\App\Services
 * @version 4.0.29
 * @author Captain Wolfie Stoned - Lupopedia LLC 2026
 */

class HybridActorSecurityService
{
    /**
     * Assert that an actor is operational and allowed to perform actions
     * 
     * @param int $actor_id The actor ID to check
     * @param string $context The context of the check (e.g., 'api', 'admin', 'cron')
     * @throws SecurityException If actor is not operational
     * @return array Actor data if operational
     */
    public static function assertActorOperational($actor_id, $context = 'general')
    {
        $db = DatabaseFactory::getConnection();
        
        // Get actor with all relevant fields
        $actor = $db->fetch(
            "SELECT actor_id, actor_type, slug, name, is_active, is_deleted, 
                    actor_attributes, metadata_json, created_ymdhis, updated_ymdhis
             FROM lupo_actors 
             WHERE actor_id = :actor_id",
            array('actor_id' => $actor_id)
        );
        
        if (!$actor) {
            self::logSecurityEvent("Access denied for non-existent actor $actor_id", $context);
            throw new SecurityException("Access denied", 403);
        }
        
        // Check soft delete
        if ($actor['is_deleted'] == 1) {
            self::logSecurityEvent("Access denied for deleted actor $actor_id", $context);
            throw new SecurityException("Access denied", 403);
        }
        
        // Check legacy actor_status
        if ($actor['is_active'] != 1) {
            self::logSecurityEvent("Access denied for inactive actor $actor_id", $context);
            throw new SecurityException("Access denied", 403);
        }
        
        // Check JSON attributes if present
        $jsonAttrs = json_decode($actor['actor_attributes'] ?? '{}', true);
        if ($jsonAttrs) {
            $jsonStatus = $jsonAttrs['status'] ?? null;
            $jsonType = $jsonAttrs['type'] ?? null;
            
            // Hybrid actors require explicit active status
            if ($jsonType === 'hybrid' && $jsonStatus !== 'active') {
                self::logSecurityEvent("Access denied for non-operational hybrid actor $actor_id (status: $jsonStatus)", $context);
                throw new SecurityException("Access denied", 403);
            }
            
            // Security level restrictions
            $securityLevel = $jsonAttrs['security_level'] ?? null;
            if ($securityLevel === 'restricted') {
                self::logSecurityEvent("Access denied for restricted actor $actor_id", $context);
                throw new SecurityException("Access denied", 403);
            }
        }
        
        // Log successful access for audit
        self::logSecurityEvent("Access granted for operational actor $actor_id", $context, 'info');
        
        return $actor;
    }
    
    /**
     * Check if an actor is a hybrid actor
     * 
     * @param int $actor_id The actor ID to check
     * @return bool True if actor is hybrid type
     */
    public static function isHybridActor($actor_id)
    {
        $db = DatabaseFactory::getConnection();
        
        $actor = $db->fetch(
            "SELECT actor_attributes FROM lupo_actors WHERE actor_id = :actor_id",
            array('actor_id' => $actor_id)
        );
        
        if (!$actor) {
            return false;
        }
        
        $jsonAttrs = json_decode($actor['actor_attributes'] ?? '{}', true);
        return ($jsonAttrs['type'] ?? null) === 'hybrid';
    }
    
    /**
     * Get actor security status
     * 
     * @param int $actor_id The actor ID to check
     * @return array Security status information
     */
    public static function getActorSecurityStatus($actor_id)
    {
        $db = DatabaseFactory::getConnection();
        
        $actor = $db->fetch(
            "SELECT actor_id, actor_type, slug, name, is_active, is_deleted, 
                    actor_attributes, metadata_json
             FROM lupo_actors 
             WHERE actor_id = :actor_id",
            array('actor_id' => $actor_id)
        );
        
        if (!$actor) {
            return array(
                'status' => 'not_found',
                'operational' => false,
                'reason' => 'Actor does not exist'
            );
        }
        
        $jsonAttrs = json_decode($actor['actor_attributes'] ?? '{}', true);
        $jsonStatus = $jsonAttrs['status'] ?? null;
        $jsonType = $jsonAttrs['type'] ?? null;
        $securityLevel = $jsonAttrs['security_level'] ?? null;
        
        $operational = (
            $actor['is_deleted'] == 0 &&
            $actor['is_active'] == 1 &&
            ($jsonType !== 'hybrid' || $jsonStatus === 'active') &&
            $securityLevel !== 'restricted'
        );
        
        return array(
            'actor_id' => $actor_id,
            'type' => $jsonType,
            'status' => $jsonStatus,
            'security_level' => $securityLevel,
            'is_active' => $actor['is_active'],
            'is_deleted' => $actor['is_deleted'],
            'operational' => $operational,
            'reason' => $operational ? 'Actor is operational' : 'Actor is not operational'
        );
    }
    
    /**
     * Log security events for audit trail
     * 
     * @param string $message The security event message
     * @param string $context The context (api, admin, cron, etc.)
     * @param string $level The log level (info, warning, error)
     */
    private static function logSecurityEvent($message, $context, $level = 'warning')
    {
        $timestamp = gmdate('Y-m-d H:i:s');
        $logEntry = "[$timestamp] [$level] [$context] HybridActorSecurity: $message\n";
        
        // Log to file
        $logFile = LUPOPEDIA_PATH . '/lupo-logs/hybrid_actor_security.log';
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
        
        // Also log to system log if available
        if (function_exists('error_log')) {
            error_log("HybridActorSecurity: $message");
        }
    }
}

/**
 * Security Exception for Hybrid Actor Security
 */
class SecurityException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
