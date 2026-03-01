<?php
/**
 * Interface for doctrine ingestion from Channel 0 broadcasts
 * 
 * Defines the contract for scanning Channel 0 broadcast directory,
 * parsing doctrine files, and extracting doctrine metadata.
 * 
 * @package Lupopedia\Services\Initialization\Interfaces
 * @since 4.0.44
 */
interface DoctrineIngesterInterface
{
    /**
     * Scan the Channel 0 broadcasts directory recursively for .md files
     * 
     * @param string $broadcastPath Path to channels/0/broadcasts/ directory
     * @return void
     * @throws DoctrineIngestionException If directory cannot be scanned
     */
    public function scanBroadcastDirectory($broadcastPath);
    
    /**
     * Parse a single broadcast file and extract doctrine metadata
     * 
     * @param string $filePath Path to broadcast file
     * @return array Doctrine metadata (doctrine_number, title, system_version, etc.)
     * @throws DoctrineIngestionException If file cannot be parsed
     */
    public function parseBroadcast($filePath);
    
    /**
     * Get all ingested doctrines
     * 
     * @return array Array of doctrine metadata arrays
     */
    public function getIngestedDoctrines();
    
    /**
     * Get total count of successfully ingested doctrines
     * 
     * @return int Count of doctrines
     */
    public function getDoctrineCount();
}
