<?php
/**
 * Channel 66 Production Logger
 * 
 * Production-grade logging with rotation, structured formatting,
 * and analysis capabilities.
 * 
 * @version 4.0.80
 * @author HEPHAESTUS (actor_id 3)
 */

class Channel66ProductionLogger
{
    private $logFile;
    private $logLevel;
    private $maxFileSize;
    private $maxFiles;
    private $currentLogFile;
    private $buffer;
    private $bufferSize = 100;
    
    public function __construct($logLevel = 'INFO', $maxFileSize = '100M', $maxFiles = 10)
    {
        $this->logLevel = strtoupper($logLevel);
        $this->maxFileSize = $this->parseSize($maxFileSize);
        $this->maxFiles = $maxFiles;
        $this->buffer = array();
        $this->initializeLog();
    }
    
    /**
     * Initialize logging system
     */
    private function initializeLog()
    {
        $logDir = ABSPATH . 'lupo-logs/admin/';
        
        if (!is_dir($logDir)) {
            if (!mkdir($logDir, 0755, true)) {
                throw new Exception("Failed to create log directory: {$logDir}");
            }
        }
        
        $this->rotateLogFiles($logDir);
        $this->currentLogFile = $this->getNewLogFile($logDir);
    }
    
    /**
     * Log migration summary
     */
    public function logMigrationSummary($result)
    {
        $summary = array(
            'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'type' => 'migration_summary',
            'thread_id' => $result['thread_id'] ?? 'all',
            'files_discovered' => $result['files_discovered'],
            'files_processed' => $result['files_processed'],
            'files_ingested' => $result['files_ingested'],
            'files_rejected' => $result['files_rejected'],
            'files_conflict_flagged' => $result['files_conflict_flagged'],
            'batches_processed' => $result['batches_processed'],
            'batches_failed' => $result['batches_failed'],
            'peak_memory_mb' => $result['peak_memory_mb'],
            'avg_files_per_second' => $result['avg_files_per_second'],
            'total_runtime_seconds' => $result['total_runtime_seconds'],
            'errors' => $result['errors'] ?? array()
        );
        
        $this->writeLog('INFO', $summary);
    }
    
    /**
     * Log file processing outcome
     */
    public function logFileOutcome($file, $outcome)
    {
        $logEntry = array(
            'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'type' => 'file_outcome',
            'file' => basename($file),
            'file_path_from_root' => $this->getRepoRelativePath($file),
            'validation_status' => $outcome['validation_status'] ?? 'unknown',
            'entity_id' => $outcome['entity_id'] ?? null,
            'reject_type' => $outcome['reject_type'] ?? null,
            'conflict_type' => $outcome['conflict_type'] ?? null,
            'warning_codes' => $outcome['warning_codes'] ?? null,
            'processing_time_ms' => $outcome['processing_time_ms'] ?? null
        );
        
        $this->writeLog('INFO', $logEntry);
    }
    
    /**
     * Log error with context
     */
    public function logError($errorEntry)
    {
        $logEntry = array(
            'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'type' => 'error',
            'error_type' => $errorEntry['error_type'],
            'message' => $errorEntry['message'],
            'context' => $errorEntry['context'] ?? array(),
            'stack_trace' => $errorEntry['stack_trace'] ?? null
        );
        
        $this->writeLog('ERROR', $logEntry);
    }
    
    /**
     * Log performance metrics
     */
    public function logPerformance($metrics)
    {
        $logEntry = array(
            'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'type' => 'performance',
            'metrics' => $metrics
        );
        
        $this->writeLog('INFO', $logEntry);
    }
    
    /**
     * Log batch start
     */
    public function logBatchStart($batchNumber, $totalBatches, $fileCount)
    {
        $logEntry = array(
            'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'type' => 'batch_start',
            'batch_number' => $batchNumber,
            'total_batches' => $totalBatches,
            'file_count' => $fileCount
        );
        
        $this->writeLog('INFO', $logEntry);
    }
    
    /**
     * Log batch completion
     */
    public function logBatchEnd($batchNumber, $duration, $success, $error = null)
    {
        $logEntry = array(
            'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'type' => 'batch_end',
            'batch_number' => $batchNumber,
            'duration_ms' => round($duration * 1000, 2),
            'success' => $success,
            'error' => $error
        );
        
        $this->writeLog($INFO', $logEntry);
    }
    
    /**
     * Write log entry to file
     */
    private function writeLog($level, $data)
    {
        if (!$this->shouldLog($level)) {
            return;
        }
        
        $logLine = json_encode(array_merge($data, array('level' => $level))) . "\n";
        
        // Buffer for performance
        $this->buffer[] = $logLine;
        
        if (count($this->buffer) >= $this->bufferSize) {
            $this->flushBuffer();
        }
    }
    
    /**
     * Flush buffer to file
     */
    private function flushBuffer()
    {
        if (empty($this->buffer)) {
            return;
        }
        
        if (!file_put_contents($this->currentLogFile, implode('', $this->buffer), FILE_APPEND | LOCK_EX)) {
            throw new Exception("Failed to write to log file: {$this->currentLogFile}");
        }
        
        $this->buffer = array();
        
        // Check if we need to rotate
        if (filesize($this->currentLogFile) > $this->maxFileSize) {
            $this->rotateLogFiles();
        }
    }
    
    /**
     * Flush any remaining buffer
     */
    public function __destruct()
    {
        if (!empty($this->buffer)) {
            $this->flushBuffer();
        }
    }
    
    /**
     * Check if we should log this level
     */
    private function shouldLog($level)
    {
        $levels = array('ERROR' => 0, 'WARNING' => 1, 'INFO' => 2, 'DEBUG' => 3);
        
        return isset($levels[$level]) && $levels[$level] <= $levels[$this->logLevel];
    }
    
    /**
     * Rotate log files
     */
    private function rotateLogFiles($logDir)
    {
        $existingFiles = glob($logDir . 'channel66_production_*.jsonl');
        
        // Sort by modification time
        usort($existingFiles, function($a, $b) {
            return filemtime($a) - filemtime($b);
        });
        
        // Remove old files if we have too many
        if (count($existingFiles) >= $this->maxFiles) {
            $filesToRemove = array_slice($existingFiles, 0, count($existingFiles) - $this->maxFiles + 1);
            foreach ($filesToRemove as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
        
        $this->currentLogFile = $this->getNewLogFile($logDir);
    }
    
    /**
     * Get new log file name
     */
    private function getNewLogFile($logDir)
    {
        $timestamp = gmdate('YmdHis');
        return $logDir . 'channel66_production_' . $timestamp . '.jsonl';
    }
    
    /**
     * Parse size string to bytes
     */
    private function parseSize($size)
    {
        $size = strtoupper(trim($size));
        $units = array('K' => 1024, 'M' => 1024*1024, 'G' => 1024*1024*1024);
        
        if (preg_match('/^(\d+)([KMG])?$/', $size, $matches)) {
            $value = (int)$matches[1];
            $unit = isset($matches[2]) ? $matches[2] : 'B';
            
            return isset($units[$unit]) ? $value * $units[$unit] : $value;
        }
        
        // Default to 100MB
        return 100 * 1024 * 1024;
    }
    
    /**
     * Get repo-relative path
     */
    private function getRepoRelativePath($absolutePath)
    {
        $repoRoot = ABSPATH;
        if (strpos($absolutePath, $repoRoot) === 0) {
            return $absolutePath;
        }
        
        return ltrim(substr($absolutePath, strlen($repoRoot)), '/\\');
    }
}
