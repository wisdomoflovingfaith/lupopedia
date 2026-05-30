<?php
/**
 * Channel 66 Performance Monitor
 * 
 * Real-time performance monitoring and metrics collection
 * for production-grade ingestion.
 * 
 * @version 4.0.80
 * @author HEPHAESTUS (actor_id 3)
 */

class Channel66PerformanceMonitor
{
    private $enableMonitoring;
    private $startTime;
    private $batchStartTime;
    private $metrics;
    private $fileCounts;
    
    public function __construct($enableMonitoring = true)
    {
        $this->enableMonitoring = $enableMonitoring;
        $this->initializeMetrics();
    }
    
    /**
     * Start migration monitoring
     */
    public function startMigration()
    {
        if (!$this->enableMonitoring) {
            return;
        }
        
        $this->startTime = microtime(true);
        $this->initializeMetrics();
        
        $this->metrics['migration_start_memory'] = memory_get_usage(true);
        $this->metrics['migration_start_time'] = gmdate('Y-m-d\TH:i:s\Z');
    }
    
    /**
     * Start batch monitoring
     */
    public function startBatch($batchNumber, $totalBatches)
    {
        if (!$this->enableMonitoring) {
            return;
        }
        
        $this->batchStartTime = microtime(true);
        $this->metrics['current_batch'] = $batchNumber;
        $this->metrics['total_batches'] = $totalBatches;
        $this->metrics['batch_start_memory'] = memory_get_usage(true);
    }
    
    /**
     * End batch monitoring
     */
    public function endBatch()
    {
        if (!$this->enableMonitoring) {
            return;
        }
        
        if ($this->batchStartTime) {
            $batchDuration = microtime(true) - $this->batchStartTime;
            $this->metrics['batch_duration_ms'][] = round($batchDuration * 1000, 2);
            $this->batchStartTime = null;
        }
    }
    
    /**
     * Record file processing metrics
     */
    public function recordFileProcessed($file, $outcome)
    {
        if (!$this->enableMonitoring) {
            return;
        }
        
        if (!isset($this->fileCounts[$outcome])) {
            $this->fileCounts[$outcome] = 0;
        }
        
        $this->fileCounts[$outcome]++;
        
        // Track memory usage
        $currentMemory = memory_get_usage(true);
        $this->metrics['peak_memory'] = max($this->metrics['peak_memory'] ?? 0, $currentMemory);
    }
    
    /**
     * Record custom metric
     */
    public function recordMetric($name, $value)
    {
        if (!$this->enableMonitoring) {
            return;
        }
        
        $this->metrics[$name] = $value;
    }
    
    /**
     * End migration monitoring
     */
    public function endMigration()
    {
        if (!$this->enableMonitoring) {
            return;
        }
        
        $endTime = microtime(true);
        $totalDuration = $endTime - $this->startTime;
        
        $this->metrics['migration_end_memory'] = memory_get_usage(true);
        $this->metrics['migration_end_time'] = gmdate('Y-m-d\TH:i:s\Z');
        $this->metrics['total_duration_seconds'] = round($totalDuration, 2);
        
        // Calculate averages
        if (!empty($this->metrics['batch_duration_ms'])) {
            $this->metrics['avg_batch_duration_ms'] = round(array_sum($this->metrics['batch_duration_ms']) / count($this->metrics['batch_duration_ms']), 2);
        }
        
        // Calculate throughput
        $totalFiles = array_sum($this->fileCounts);
        if ($totalFiles > 0 && $this->metrics['total_duration_seconds'] > 0) {
            $this->metrics['files_per_second'] = round($totalFiles / $this->metrics['total_duration_seconds'], 2);
            $this->metrics['files_per_minute'] = round($totalFiles / ($this->metrics['total_duration_seconds'] / 60), 2);
            $this->metrics['files_per_hour'] = round($totalFiles / ($this->metrics['total_duration_seconds'] / 3600), 2);
        }
    }
    
    /**
     * Get total runtime in seconds
     */
    public function getTotalRuntime()
    {
        return $this->metrics['total_duration_seconds'] ?? 0;
    }
    
    /**
     * Get peak memory usage in MB
     */
    public function getPeakMemoryMb()
    {
        $peakBytes = $this->metrics['peak_memory'] ?? 0;
        return round($peakBytes / 1024 / 1024, 2);
    }
    
    /**
     * Get all metrics
     */
    public function getMetrics()
    {
        return $this->metrics;
    }
    
    /**
     * Get file processing counts
     */
    public function getFileCounts()
    {
        return $this->fileCounts;
    }
    
    /**
     * Check performance alerts
     */
    public function checkAlerts($thresholds = array())
    {
        if (!$this->enableMonitoring) {
            return array();
        }
        
        $alerts = array();
        $totalFiles = array_sum($this->fileCounts);
        
        // Default thresholds
        $defaultThresholds = array(
            'error_rate' => 0.05, // 5%
            'memory_usage' => 0.8,  // 80%
            'throughput' => 0.5    // 50% of expected (100 files/min)
        );
        
        $thresholds = array_merge($defaultThresholds, $thresholds);
        
        // Error rate alert
        if (isset($thresholds['error_rate']) && $totalFiles > 0) {
            $errorCount = ($this->fileCounts['rejected'] ?? 0) + ($this->fileCounts['conflict_flagged'] ?? 0);
            $errorRate = $errorCount / $totalFiles;
            
            if ($errorRate > $thresholds['error_rate']) {
                $alerts[] = array(
                    'type' => 'error_rate',
                    'message' => "Error rate {$this->formatPercent($errorRate)} exceeds threshold {$this->formatPercent($thresholds['error_rate'])}",
                    'current' => $errorRate,
                    'threshold' => $thresholds['error_rate']
                );
            }
        }
        
        // Memory usage alert
        if (isset($thresholds['memory_usage'])) {
            $peakMemory = $this->getPeakMemoryMb();
            $memoryLimit = $this->getMemoryLimitMb();
            
            if ($memoryLimit > 0) {
                $utilization = $peakMemory / $memoryLimit;
                
                if ($utilization > $thresholds['memory_usage']) {
                    $alerts[] = array(
                        'type' => 'memory_usage',
                        'message' => "Memory usage {$this->formatPercent($utilization)} exceeds threshold {$this->formatPercent($thresholds['memory_usage'])}",
                        'current' => $utilization,
                        'threshold' => $thresholds['memory_usage'],
                        'current_mb' => $peakMemory,
                        'limit_mb' => $memoryLimit
                    );
                }
            }
        }
        
        // Throughput alert
        if (isset($thresholds['throughput']) && $this->metrics['files_per_minute'] > 0) {
            $throughput = $this->metrics['files_per_minute'];
            $expectedThroughput = 100; // Expected 100 files/min
            
            if ($throughput < ($expectedThroughput * $thresholds['throughput'])) {
                $alerts[] = array(
                    'type' => 'throughput',
                    'message' => "Throughput {$throughput} files/min below threshold " . round($expectedThroughput * $thresholds['throughput']) . " files/min",
                    'current' => $throughput,
                    'threshold' => round($expectedThroughput * $thresholds['throughput']),
                    'expected' => $expectedThroughput
                );
            }
        }
        
        return $alerts;
    }
    
    /**
     * Initialize metrics array
     */
    private function initializeMetrics()
    {
        $this->metrics = array();
        $this->fileCounts = array(
            'ingested' => 0,
            'rejected' => 0,
            'conflict_flagged' => 0
        );
    }
    
    /**
     * Format percentage for display
     */
    private function formatPercent($value)
    {
        return round($value * 100, 1) . '%';
    }
    
    /**
     * Get memory limit in MB
     */
    private function getMemoryLimitMb()
    {
        $memoryLimit = ini_get('memory_limit');
        return $memoryLimit ? round($memoryLimit / 1024 / 1024, 2) : 0;
    }
}
