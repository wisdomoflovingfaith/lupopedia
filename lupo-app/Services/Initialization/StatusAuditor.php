<?php
/**
 * StatusAuditor - Audits status directory files for version relevance
 * 
 * Scans the docs/status/ directory for .md and .log files, parses FLIP headers
 * to extract version metadata, and classifies each file as retain, archive, or
 * deprecate using the VersionClassifier. Handles errors gracefully with logging
 * and continues processing.
 * 
 * Usage:
 *   $auditor = new StatusAuditor($flipParser, $classifier, $logger);
 *   $auditor->scanStatusDirectory('docs/status');
 *   $results = $auditor->getAuditResults();
 *   $counts = $auditor->getDispositionCounts();
 * 
 * @package Lupopedia\Services\Initialization
 * @since 4.0.44
 */
class StatusAuditor implements StatusAuditorInterface
{
    /**
     * FLIP header parser instance
     * 
     * @var FLIPHeaderParserInterface
     */
    private $flipParser;
    
    /**
     * Version classifier instance
     * 
     * @var VersionClassifierInterface
     */
    private $classifier;
    
    /**
     * Logger instance
     * 
     * @var InitializationLoggerInterface
     */
    private $logger;
    
    /**
     * Array of audit results
     * 
     * Each result contains:
     * - filename: Base filename
     * - file_path: Full file path
     * - version: Extracted version string or null
     * - disposition: "retain", "archive", or "deprecate"
     * - rationale: Human-readable explanation
     * 
     * @var array
     */
    private $auditResults;
    
    /**
     * Constructor
     * 
     * @param FLIPHeaderParserInterface $flipParser FLIP header parser
     * @param VersionClassifierInterface $classifier Version classifier
     * @param InitializationLoggerInterface $logger Logger instance
     */
    public function __construct(
        FLIPHeaderParserInterface $flipParser,
        VersionClassifierInterface $classifier,
        InitializationLoggerInterface $logger
    ) {
        $this->flipParser = $flipParser;
        $this->classifier = $classifier;
        $this->logger = $logger;
        $this->auditResults = array();
    }
    
    /**
     * Scan the status directory for all .md and .log files
     * 
     * Finds all .md and .log files in the status directory (non-recursive),
     * audits each file to extract version and classify disposition, and stores
     * results. Continues processing even if individual files fail.
     * 
     * @param string $statusPath Path to docs/status/ directory
     * @return void
     * @throws StatusAuditException If directory cannot be scanned
     */
    public function scanStatusDirectory($statusPath)
    {
        // Verify directory exists
        if (!is_dir($statusPath)) {
            throw new StatusAuditException(
                ErrorMessages::directoryNotFound($statusPath, 'StatusAuditor')
            );
        }
        
        // Verify directory is readable
        if (!is_readable($statusPath)) {
            throw new StatusAuditException(
                ErrorMessages::directoryNotReadable($statusPath, 'StatusAuditor')
            );
        }
        
        $this->logger->info(
            "Starting status directory audit",
            array('path' => $statusPath)
        );
        
        // Find all .md and .log files
        $files = $this->findStatusFiles($statusPath);
        
        $this->logger->info(
            "Found status files",
            array('count' => count($files))
        );
        
        // Audit each file
        foreach ($files as $filePath) {
            try {
                $result = $this->auditFile($filePath);
                
                // Store result
                if (!empty($result)) {
                    $this->auditResults[] = $result;
                }
            } catch (StatusAuditException $e) {
                // Log error and continue with next file
                // Default to retain for files that cannot be read
                $this->logger->warning(
                    "Failed to audit status file, defaulting to retain",
                    array(
                        'file' => $filePath,
                        'error' => $e->getMessage()
                    )
                );
                
                // Add a default result for the failed file
                $this->auditResults[] = array(
                    'filename' => basename($filePath),
                    'file_path' => $filePath,
                    'version' => null,
                    'disposition' => 'retain',
                    'rationale' => 'File read error; defaulting to retain for safety'
                );
            }
        }
        
        $this->logger->info(
            "Status directory audit complete",
            array('total_files' => count($this->auditResults))
        );
    }
    
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
    public function auditFile($filePath)
    {
        // Verify file exists
        if (!file_exists($filePath)) {
            throw new StatusAuditException(
                ErrorMessages::fileNotFound($filePath, 'StatusAuditor')
            );
        }
        
        // Verify file is readable
        if (!is_readable($filePath)) {
            throw new StatusAuditException(
                ErrorMessages::fileNotReadable($filePath, 'StatusAuditor')
            );
        }
        
        // Read file content
        $content = @file_get_contents($filePath);
        if ($content === false) {
            throw new StatusAuditException(
                ErrorMessages::fileReadFailed($filePath, 'StatusAuditor')
            );
        }
        
        // Extract version from content
        $version = $this->classifier->extractVersion($content);
        
        // Classify file based on version
        $disposition = $this->classifier->classifyFile($version);
        
        // Get rationale for classification
        $rationale = $this->classifier->getRationale($version, $disposition);
        
        // Build audit result
        $result = array(
            'filename' => basename($filePath),
            'file_path' => $filePath,
            'version' => $version,
            'disposition' => $disposition,
            'rationale' => $rationale
        );
        
        // Extract additional metadata if available
        $result = $this->enrichWithMetadata($result, $content);
        
        return $result;
    }
    
    /**
     * Get all audit results
     * 
     * @return array Array of file audit data arrays
     */
    public function getAuditResults()
    {
        return $this->auditResults;
    }
    
    /**
     * Get disposition counts
     * 
     * @return array Associative array with keys: retain, archive, deprecate
     */
    public function getDispositionCounts()
    {
        $counts = array(
            'retain' => 0,
            'archive' => 0,
            'deprecate' => 0
        );
        
        foreach ($this->auditResults as $result) {
            $disposition = $result['disposition'];
            if (isset($counts[$disposition])) {
                $counts[$disposition]++;
            }
        }
        
        return $counts;
    }
    
    /**
     * Find all .md and .log files in status directory
     * 
     * Scans directory non-recursively for status files.
     * 
     * @param string $directory Directory to scan
     * @return array Array of file paths
     */
    private function findStatusFiles($directory)
    {
        $files = array();
        
        try {
            $iterator = new DirectoryIterator($directory);
            
            foreach ($iterator as $file) {
                if ($file->isFile() && $this->isStatusFile($file->getPathname())) {
                    $files[] = $file->getPathname();
                }
            }
        } catch (Exception $e) {
            throw new StatusAuditException(
                ErrorMessages::directoryScanFailed($directory, $e->getMessage(), 'StatusAuditor')
            );
        }
        
        return $files;
    }
    
    /**
     * Check if file is a status file (.md or .log extension)
     * 
     * @param string $filePath File path
     * @return bool True if status file, false otherwise
     */
    private function isStatusFile($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        return $extension === 'md' || $extension === 'log';
    }
    
    /**
     * Enrich audit result with additional metadata from FLIP header
     * 
     * Extracts created_ymdhis, last_modified_utc, actor_id if present.
     * 
     * @param array $result Base audit result
     * @param string $content File content
     * @return array Enriched audit result
     */
    private function enrichWithMetadata($result, $content)
    {
        // Parse FLIP header if present
        if ($this->flipParser->hasHeader($content)) {
            $header = $this->flipParser->parse($content);
            
            // Extract timestamps
            $result['created_ymdhis'] = $this->getHeaderField($header, 'created_ymdhis', null);
            $result['last_modified_utc'] = $this->getHeaderField($header, 'last_modified_utc', null);
            
            // Extract actor_id
            $result['actor_id'] = $this->getHeaderField($header, 'actor_id', null);
        } else {
            $result['created_ymdhis'] = null;
            $result['last_modified_utc'] = null;
            $result['actor_id'] = null;
        }
        
        return $result;
    }
    
    /**
     * Get field from header with default value
     * 
     * Handles nested header structures (e.g., wolfie.headers.system_version)
     * 
     * @param array $header Parsed header
     * @param string $fieldName Field name
     * @param mixed $default Default value
     * @return mixed Field value or default
     */
    private function getHeaderField($header, $fieldName, $default)
    {
        // Check direct field
        if (isset($header[$fieldName])) {
            return $header[$fieldName];
        }
        
        // Check nested structures (wolfie.headers, flip.footer, etc.)
        $nestedKeys = array('wolfie.headers', 'flip.header', 'flip.footer');
        foreach ($nestedKeys as $nestedKey) {
            if (isset($header[$nestedKey]) && is_array($header[$nestedKey])) {
                if (isset($header[$nestedKey][$fieldName])) {
                    return $header[$nestedKey][$fieldName];
                }
            }
        }
        
        return $default;
    }
}
