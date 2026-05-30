<?php
/**
 * Interface for initialization workflow logging
 * 
 * Defines the contract for structured logging during initialization
 * with support for different log levels.
 * 
 * @package Lupopedia\Services\Initialization\Interfaces
 * @since 4.0.44
 */
interface InitializationLoggerInterface
{
    /**
     * Log a message
     * 
     * @param string $level Log level: "INFO", "WARNING", or "ERROR"
     * @param string $message Log message
     * @param array $context Additional context data (optional)
     * @return void
     */
    public function log($level, $message, $context);
    
    /**
     * Log an info message
     * 
     * @param string $message Log message
     * @param array $context Additional context data (optional)
     * @return void
     */
    public function info($message, $context);
    
    /**
     * Log a warning message
     * 
     * @param string $message Log message
     * @param array $context Additional context data (optional)
     * @return void
     */
    public function warning($message, $context);
    
    /**
     * Log an error message
     * 
     * @param string $message Log message
     * @param array $context Additional context data (optional)
     * @return void
     */
    public function error($message, $context);
    
    /**
     * Get all log entries
     * 
     * @return array Array of log entry arrays
     */
    public function getEntries();
    
    /**
     * Clear all log entries
     * 
     * @return void
     */
    public function clear();
}
