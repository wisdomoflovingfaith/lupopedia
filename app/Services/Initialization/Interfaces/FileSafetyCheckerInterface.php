<?php
/**
 * Interface for file safety verification
 * 
 * Defines the contract for tracking file operations and ensuring
 * no files are deleted during initialization.
 * 
 * @package Lupopedia\Services\Initialization\Interfaces
 * @since 4.0.44
 */
interface FileSafetyCheckerInterface
{
    /**
     * Track a file operation
     * 
     * @param string $operation Operation type: "create", "read", "update", "delete"
     * @param string $filePath Path to file
     * @return void
     */
    public function trackOperation($operation, $filePath);
    
    /**
     * Verify no delete operations occurred
     * 
     * @return bool True if no deletes, false if deletes detected
     */
    public function verifyNoDeletes();
    
    /**
     * Get all tracked operations
     * 
     * @return array Array of operation records
     */
    public function getOperations();
    
    /**
     * Get delete operations (if any)
     * 
     * @return array Array of delete operation records
     */
    public function getDeleteOperations();
}
