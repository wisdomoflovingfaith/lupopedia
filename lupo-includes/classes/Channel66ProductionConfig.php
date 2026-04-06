<?php
/**
 * Channel 66 Production Configuration Manager
 * 
 * Validates and manages runtime configuration for production ingestion.
 * 
 * @version 4.0.80
 * @author HEPHAESTUS (actor_id 3)
 */

class Channel66ProductionConfig
{
    private $config;
    private $scopeRoot;
    private $toonDir;
    private $batchSize;
    private $memoryLimit;
    private $threadId;
    
    public function __construct($configFile = null)
    {
        $this->loadConfiguration($configFile);
        $this->validateConfiguration();
    }
    
    /**
     * Get scope root directory
     */
    public function getScopeRoot()
    {
        return $this->scopeRoot;
    }
    
    /**
     * Schema reference JSON directory (config key remains toon_dir for backward compatibility).
     * Canonical: lupo-database/lupopedia/json/
     */
    public function getToonDir()
    {
        return $this->toonDir;
    }
    
    /**
     * Get batch size
     */
    public function getBatchSize()
    {
        return $this->batchSize;
    }
    
    /**
     * Get memory limit
     */
    public function getMemoryLimit()
    {
        return $this->memoryLimit;
    }
    
    /**
     * Get thread ID (null for all threads)
     */
    public function getThreadId()
    {
        return $this->threadId;
    }
    
    /**
     * Load configuration from file or defaults
     */
    private function loadConfiguration($configFile)
    {
        // Default configuration
        $this->config = array(
            'scope_root' => defined('ABSPATH') ? ABSPATH : getcwd(),
            'toon_dir' => defined('ABSPATH') ? ABSPATH . 'lupo-database/lupopedia/json/' : 'lupo-database/lupopedia/json/',
            'batch_size' => 100,
            'memory_limit' => '256M',
            'thread_id' => null,
            'max_execution_time' => 3600, // 1 hour
            'error_retry_attempts' => 3,
            'error_retry_delay' => 5, // seconds
            'enable_monitoring' => true,
            'log_level' => 'INFO',
            'performance_alert_threshold' => 0.05, // 5% error rate
            'memory_alert_threshold' => 0.8, // 80% of limit
            'throughput_alert_threshold' => 0.5 // 50% of expected throughput
        );
        
        // Load from file if provided
        if ($configFile && is_file($configFile)) {
            $fileConfig = parse_ini_file($configFile);
            if ($fileConfig !== false) {
                $this->config = array_merge($this->config, $fileConfig);
            }
        }
        
        // Extract configuration values
        $this->scopeRoot = rtrim($this->config['scope_root'], '/\\');
        $this->toonDir = rtrim($this->config['toon_dir'], '/\\');
        $this->batchSize = (int)$this->config['batch_size'];
        $this->memoryLimit = $this->config['memory_limit'];
        $this->threadId = isset($this->config['thread_id']) ? $this->config['thread_id'] : null;
    }
    
    /**
     * Validate configuration including Thread 1002 authority constraints
     */
    public function validateConfiguration()
    {
        $errors = array();
        
        // Validate scope root
        if (!is_dir($this->scopeRoot)) {
            $errors[] = 'Scope root directory does not exist: ' . $this->scopeRoot;
        }
        
        // Validate schema reference JSON directory (config key: toon_dir)
        if (!is_dir($this->toonDir)) {
            $errors[] = 'Schema JSON directory does not exist: ' . $this->toonDir;
        }
        
        // Validate batch size
        if (!is_int($this->batchSize) || $this->batchSize <= 0) {
            $errors[] = 'Batch size must be positive integer: ' . $this->batchSize;
        }
        
        // Validate memory limit
        $memoryLimit = $this->parseMemoryLimit($this->memoryLimit);
        if ($memoryLimit <= 0) {
            $errors[] = 'Invalid memory limit: ' . $this->memoryLimit;
        }
        
        // Validate Thread 1002 authority constraints
        $thread1002Violations = $this->validateThread1002Authority();
        if (!empty($thread1002Violations)) {
            $errors = array_merge($errors, $thread1002Violations);
        }
        
        // Validate thread ID if specified
        if ($this->threadId !== null && (!is_int($this->threadId) || $this->threadId < 1)) {
            $errors[] = "Invalid thread ID: {$this->threadId}";
        }
        
        if (!empty($errors)) {
            throw new Exception("Configuration validation failed:\n" . implode("\n", $errors));
        }
        
        return empty($errors) ? true : false;
    }
    
    /**
     * Validate configuration against Thread 1002 authority constraints
     */
    private function validateThread1002Authority()
    {
        $violations = array();
        
        // Check if configuration allows P0 rejection override
        if (isset($this->config['allow_p0_retry']) && $this->config['allow_p0_retry']) {
            $violations[] = 'P0 retry override not allowed - violates Thread 1002 authority';
        }
        
        // Check if configuration allows non-deterministic behavior
        if (isset($this->config['allow_non_deterministic']) && $this->config['allow_non_deterministic']) {
            $violations[] = 'Non-deterministic behavior not allowed - violates Thread 1002 authority';
        }
        
        // Check if configuration allows concurrent processing without locking
        if (isset($this->config['allow_concurrent_without_lock']) && $this->config['allow_concurrent_without_lock']) {
            $violations[] = 'Concurrent processing without locking not allowed - violates Thread 1002 authority';
        }
        
        return $violations;
    }
    
    /**
     * Parse memory limit string to bytes
     */
    private function parseMemoryLimit($memoryLimit)
    {
        $memoryLimit = trim($memoryLimit);
        $unit = substr($memoryLimit, -1);
        $value = (int)substr($memoryLimit, 0, -1);
        
        switch ($unit) {
            case 'K':
                return $value * 1024;
            case 'M':
                return $value * 1024 * 1024;
            case 'G':
                return $value * 1024 * 1024 * 1024;
            case 'T':
                return $value * 1024 * 1024 * 1024 * 1024;
            default:
                return $value;
        }
    }
    
    /**
     * Get configuration as array
     */
    public function toArray()
    {
        return $this->config;
    }
    
    /**
     * Get specific configuration value
     */
    public function get($key, $default = null)
    {
        return isset($this->config[$key]) ? $this->config[$key] : $default;
    }
}
