<?php
/**
 * Architect Logger
 * 
 * Logs architect actions with consistent signature and identity.
 * 
 * @package Lupopedia
 * @version 4.0.20
 * @author Captain Wolfie
 */

class ArchitectLogger {
    /**
     * Log an architect action
     * 
     * @param string $action Action description
     * @param array $context Additional context data
     * @return void
     */
    public static function logArchitectAction(string $action, array $context = []) {
        $logEntry = [
            'timestamp' => microtime(true),
            'datetime' => gmdate('Y-m-d H:i:s'),
            'architect' => ARCHITECT_PERSONA,
            'signature' => ARCHITECT_SIGNATURE,
            'action' => $action,
            'context' => $context
        ];
        
        // Log to error log with JSON format
        error_log('[CW] ' . json_encode($logEntry));
        
        // Also log to file if configured
        $logFile = defined('ARCHITECT_LOG_FILE') ? ARCHITECT_LOG_FILE : null;
        if ($logFile && is_writable(dirname($logFile))) {
            file_put_contents(
                $logFile,
                json_encode($logEntry) . "\n",
                FILE_APPEND
            );
        }
    }
    
    /**
     * Log architect decision
     * 
     * @param string $decision Decision description
     * @param array $rationale Rationale for decision
     * @return void
     */
    public static function logArchitectDecision(string $decision, array $rationale = []) {
        self::logArchitectAction('decision', [
            'decision' => $decision,
            'rationale' => $rationale
        ]);
    }
    
    /**
     * Log architect doctrine update
     * 
     * @param string $doctrine Doctrine name
     * @param string $change Description of change
     * @return void
     */
    public static function logDoctrineUpdate(string $doctrine, string $change) {
        self::logArchitectAction('doctrine_update', [
            'doctrine' => $doctrine,
            'change' => $change
        ]);
    }
}
