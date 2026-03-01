<?php
/**
 * InitializationLogger - Structured logging for initialization workflow
 * 
 * This class provides structured logging capabilities for the initialization
 * workflow with support for different log levels (INFO, WARNING, ERROR).
 * Log entries are stored in memory and can be retrieved for file output.
 * 
 * Usage:
 *   $logger = new InitializationLogger();
 *   $logger->info('Starting initialization workflow');
 *   $logger->warning('Missing FLIP header in file', array('file' => 'example.md'));
 *   $logger->error('Failed to create directory', array('path' => '/some/path'));
 *   $entries = $logger->getEntries();
 * 
 * @package Lupopedia\Services\Initialization
 * @since 4.0.44
 */
class InitializationLogger implements InitializationLoggerInterface
{
    /**
     * @var array Log entries buffer
     */
    private $entries;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->entries = array();
    }
    
    /**
     * Log a message
     * 
     * @param string $level Log level: "INFO", "WARNING", or "ERROR"
     * @param string $message Log message
     * @param array $context Additional context data (optional)
     * @return void
     */
    public function log($level, $message, $context = array())
    {
        $entry = array(
            'timestamp' => gmdate('YmdHis'),
            'level' => strtoupper($level),
            'message' => $message,
            'context' => $context
        );
        
        $this->entries[] = $entry;
    }
    
    /**
     * Log an info message
     * 
     * @param string $message Log message
     * @param array $context Additional context data (optional)
     * @return void
     */
    public function info($message, $context = array())
    {
        $this->log('INFO', $message, $context);
    }
    
    /**
     * Log a warning message
     * 
     * @param string $message Log message
     * @param array $context Additional context data (optional)
     * @return void
     */
    public function warning($message, $context = array())
    {
        $this->log('WARNING', $message, $context);
    }
    
    /**
     * Log an error message
     * 
     * @param string $message Log message
     * @param array $context Additional context data (optional)
     * @return void
     */
    public function error($message, $context = array())
    {
        $this->log('ERROR', $message, $context);
    }
    
    /**
     * Get all log entries
     * 
     * @return array Array of log entry arrays
     */
    public function getEntries()
    {
        return $this->entries;
    }
    
    /**
     * Clear all log entries
     * 
     * @return void
     */
    public function clear()
    {
        $this->entries = array();
    }
}
