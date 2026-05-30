<?php
/**
 * Channel 66 Batch Processor
 * 
 * Efficient batch processing for large file sets with memory management
 * and progress tracking.
 * 
 * @version 4.0.80
 * @author HEPHAESTUS (actor_id 3)
 */

class Channel66BatchProcessor
{
    private $batchSize;
    private $memoryLimit;
    private $peakMemory = 0;
    
    public function __construct($batchSize = 100, $memoryLimit = '256M')
    {
        $this->batchSize = $batchSize;
        $this->memoryLimit = $this->parseMemoryLimit($memoryLimit);
    }
    
    /**
     * Create batches from file list with deterministic ordering
     */
    public function createBatches($files)
    {
        // Sort files lexicographically for deterministic ordering
        sort($files);
        
        return array_chunk($files, $this->batchSize);
    }
    
    /**
     * Enforce memory limit with garbage collection
     */
    public function enforceMemoryLimit()
    {
        $currentMemory = memory_get_usage(true);
        $this->peakMemory = max($this->peakMemory, $currentMemory);
        
        $memoryLimitBytes = $this->memoryLimit;
        
        if ($currentMemory > $memoryLimitBytes) {
            // Force garbage collection
            gc_collect_cycles();
            
            // Check again after collection
            $newMemory = memory_get_usage(true);
            if ($newMemory > $memoryLimitBytes) {
                $memoryMb = round($newMemory / 1024 / 1024, 2);
                $limitMb = round($memoryLimitBytes / 1024 / 1024, 2);
                throw new Exception("Memory limit exceeded: {$memoryMb}MB > {$limitMb}MB");
            }
        }
    }
    
    /**
     * Get peak memory usage in MB
     */
    public function getPeakMemoryMb()
    {
        return round($this->peakMemory / 1024 / 1024, 2);
    }
    
    /**
     * Parse memory limit string to bytes
     */
    private function parseMemoryLimit($limit)
    {
        // Parse formats like "256M", "1G", etc.
        $limit = strtoupper(trim($limit));
        $units = array('K' => 1024, 'M' => 1024*1024, 'G' => 1024*1024*1024);
        
        if (preg_match('/^(\d+)([KMG])?$/', $limit, $matches)) {
            $value = (int)$matches[1];
            $unit = isset($matches[2]) ? $matches[2] : 'B';
            
            return isset($units[$unit]) ? $value * $units[$unit] : $value;
        }
        
        // Default to 256MB if invalid format
        return 256 * 1024 * 1024;
    }
    
    /**
     * Get memory usage statistics
     */
    public function getMemoryStats()
    {
        $current = memory_get_usage(true);
        $peak = $this->peakMemory;
        
        return array(
            'current_mb' => round($current / 1024 / 1024, 2),
            'peak_mb' => round($peak / 1024 / 1024, 2),
            'limit_mb' => round($this->memoryLimit / 1024 / 1024, 2),
            'utilization_percent' => round(($current / $this->memoryLimit) * 100, 1)
        );
    }
}
