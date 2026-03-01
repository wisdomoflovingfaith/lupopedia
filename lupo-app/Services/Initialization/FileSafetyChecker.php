<?php
/**
 * FileSafetyChecker - Tracks file operations and verifies no deletions occur
 * 
 * This class monitors all file operations during the initialization workflow
 * to ensure that no files are automatically deleted. It tracks create, read,
 * update, and delete operations, providing verification that only safe
 * operations (create and read) occurred during initialization.
 * 
 * The initialization workflow must never delete files automatically, even if
 * errors occur. This class enforces that doctrine by tracking all file
 * operations and providing verification methods.
 * 
 * Usage:
 *   $checker = new FileSafetyChecker($logger);
 *   
 *   // Track operations as they occur
 *   $checker->trackOperation('create', 'docs/status/report.md');
 *   $checker->trackOperation('read', 'channels/0/broadcasts/doctrine_001.md');
 *   
 *   // Verify no deletes occurred
 *   $isValid = $checker->verifyNoDeletes(); // Returns true
 *   
 *   // Get all operations for logging
 *   $operations = $checker->getOperations();
 *   
 *   // Get delete operations (if any)
 *   $deletes = $checker->getDeleteOperations(); // Returns empty array if none
 * 
 * @package Lupopedia\Services\Initialization
 * @since 4.0.44
 */
class FileSafetyChecker implements FileSafetyCheckerInterface
{
    /**
     * Logger instance
     * 
     * @var InitializationLoggerInterface
     */
    private $logger;
    
    /**
     * Array of tracked file operations
     * 
     * Each operation is an array with keys:
     * - operation: "create", "read", "update", or "delete"
     * - file_path: Path to the file
     * - timestamp: When the operation was tracked (YYYYMMDDHHMMSS)
     * 
     * @var array
     */
    private $operations;
    
    /**
     * Count of operations by type
     * 
     * @var array
     */
    private $operationCounts;
    
    /**
     * Constructor
     * 
     * @param InitializationLoggerInterface $logger Logger instance
     */
    public function __construct(InitializationLoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->operations = array();
        $this->operationCounts = array(
            'create' => 0,
            'read' => 0,
            'update' => 0,
            'delete' => 0
        );
    }
    
    /**
     * Track a file operation
     * 
     * Records a file operation with timestamp for later verification.
     * Valid operation types are: "create", "read", "update", "delete"
     * 
     * @param string $operation Operation type: "create", "read", "update", "delete"
     * @param string $filePath Path to file (relative or absolute)
     * @return void
     */
    public function trackOperation($operation, $filePath)
    {
        // Normalize operation to lowercase
        $operation = strtolower($operation);
        
        // Validate operation type
        $validOperations = array('create', 'read', 'update', 'delete');
        if (!in_array($operation, $validOperations)) {
            $this->logger->warning(
                "Invalid operation type tracked: {$operation}",
                array('file_path' => $filePath)
            );
            return;
        }
        
        // Get current timestamp
        $timestamp = gmdate('YmdHis');
        
        // Record operation
        $operationRecord = array(
            'operation' => $operation,
            'file_path' => $filePath,
            'timestamp' => $timestamp
        );
        
        $this->operations[] = $operationRecord;
        
        // Update operation count
        if (isset($this->operationCounts[$operation])) {
            $this->operationCounts[$operation]++;
        }
        
        // Log delete operations immediately as warnings
        if ($operation === 'delete') {
            $this->logger->warning(
                "DELETE operation tracked: {$filePath}",
                array('timestamp' => $timestamp)
            );
        }
        
        // Log all operations at debug level
        $this->logger->info(
            "File operation tracked: {$operation}",
            array(
                'file_path' => $filePath,
                'timestamp' => $timestamp
            )
        );
    }
    
    /**
     * Verify no delete operations occurred
     * 
     * Checks all tracked operations to ensure no delete operations
     * were recorded during the initialization workflow.
     * 
     * @return bool True if no deletes, false if deletes detected
     */
    public function verifyNoDeletes()
    {
        $deleteCount = $this->operationCounts['delete'];
        
        if ($deleteCount === 0) {
            $this->logger->info(
                'File safety verification passed: no delete operations detected',
                array(
                    'total_operations' => count($this->operations),
                    'operation_counts' => $this->operationCounts
                )
            );
            return true;
        } else {
            $deleteOps = $this->getDeleteOperations();
            $deletedFiles = array();
            foreach ($deleteOps as $op) {
                $deletedFiles[] = $op['file_path'];
            }
            
            $this->logger->error(
                "File safety verification FAILED: {$deleteCount} delete operation(s) detected",
                array(
                    'deleted_files' => $deletedFiles,
                    'operation_counts' => $this->operationCounts
                )
            );
            return false;
        }
    }
    
    /**
     * Get all tracked operations
     * 
     * Returns complete list of all file operations tracked during
     * initialization, including operation type, file path, and timestamp.
     * 
     * @return array Array of operation records
     */
    public function getOperations()
    {
        return $this->operations;
    }
    
    /**
     * Get delete operations (if any)
     * 
     * Filters tracked operations to return only delete operations.
     * Used for reporting and validation.
     * 
     * @return array Array of delete operation records
     */
    public function getDeleteOperations()
    {
        $deleteOps = array();
        
        foreach ($this->operations as $operation) {
            if ($operation['operation'] === 'delete') {
                $deleteOps[] = $operation;
            }
        }
        
        return $deleteOps;
    }
    
    /**
     * Get operation counts by type
     * 
     * Returns summary of how many operations of each type were tracked.
     * 
     * @return array Associative array with operation types as keys and counts as values
     */
    public function getOperationCounts()
    {
        return $this->operationCounts;
    }
    
    /**
     * Get count of specific operation type
     * 
     * @param string $operation Operation type: "create", "read", "update", "delete"
     * @return int Count of operations of that type
     */
    public function getOperationCount($operation)
    {
        $operation = strtolower($operation);
        
        if (isset($this->operationCounts[$operation])) {
            return $this->operationCounts[$operation];
        }
        
        return 0;
    }
    
    /**
     * Check if only safe operations occurred
     * 
     * Verifies that only "create" and "read" operations were tracked,
     * with no "update" or "delete" operations.
     * 
     * @return bool True if only safe operations, false otherwise
     */
    public function verifySafeOperationsOnly()
    {
        $updateCount = $this->operationCounts['update'];
        $deleteCount = $this->operationCounts['delete'];
        
        if ($updateCount === 0 && $deleteCount === 0) {
            $this->logger->info(
                'Safe operations verification passed: only create and read operations detected',
                array('operation_counts' => $this->operationCounts)
            );
            return true;
        } else {
            $this->logger->warning(
                'Safe operations verification: update or delete operations detected',
                array('operation_counts' => $this->operationCounts)
            );
            return false;
        }
    }
    
    /**
     * Get summary of file operations
     * 
     * Returns a human-readable summary of all tracked operations
     * for inclusion in reports.
     * 
     * @return string Summary text
     */
    public function getSummary()
    {
        $total = count($this->operations);
        $creates = $this->operationCounts['create'];
        $reads = $this->operationCounts['read'];
        $updates = $this->operationCounts['update'];
        $deletes = $this->operationCounts['delete'];
        
        $summary = "File Operations Summary:\n";
        $summary .= "- Total operations tracked: {$total}\n";
        $summary .= "- Create operations: {$creates}\n";
        $summary .= "- Read operations: {$reads}\n";
        $summary .= "- Update operations: {$updates}\n";
        $summary .= "- Delete operations: {$deletes}\n";
        
        if ($deletes > 0) {
            $summary .= "\nWARNING: Delete operations detected!\n";
            $deleteOps = $this->getDeleteOperations();
            foreach ($deleteOps as $op) {
                $summary .= "  - {$op['file_path']} (at {$op['timestamp']})\n";
            }
        } else {
            $summary .= "\nNo delete operations detected (safe).\n";
        }
        
        return $summary;
    }
    
    /**
     * Reset all tracked operations
     * 
     * Clears all tracked operations and resets counters.
     * Useful for testing or re-running initialization.
     * 
     * @return void
     */
    public function reset()
    {
        $this->operations = array();
        $this->operationCounts = array(
            'create' => 0,
            'read' => 0,
            'update' => 0,
            'delete' => 0
        );
        
        $this->logger->info('File safety checker reset');
    }
}
