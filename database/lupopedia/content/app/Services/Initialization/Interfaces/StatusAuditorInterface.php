<?php
/**
 * Interface for auditing status directory files
 * 
 * Defines the contract for scanning, parsing, and classifying status files
 * based on version relevance.
 * 
 * @package Lupopedia\Services\Initialization\Interfaces
 * @since 4.0.44
 */
interface StatusAuditorInterface
{
    /**
     * Scan the status directory for all .md and .log files
     * 
     * @param string $statusPath Path to docs/status/ directory
     * @return void
     * @throws StatusAuditException If directory cannot be scanned
     */
    public function scanStatusDirectory($statusPath);
    
    /**
     * Audit a single status file
     * 
     * Reads the file, parses FLIP header, extracts version, and classifies
     * the file as retain, archive, or deprecate.
     * 
     * @param string $filePath Path to status file
     * @return array File audit data (filename, version, disposition, rationale)
     * @throws StatusAuditException If file cannot be audited
     */
    public function auditFile($filePath);
    
    /**
     * Get all audit results
     * 
     * @return array Array of file audit data arrays
     */
    public function getAuditResults();
    
    /**
     * Get disposition counts
     * 
     * @return array Associative array with keys: retain, archive, deprecate
     */
    public function getDispositionCounts();
}
