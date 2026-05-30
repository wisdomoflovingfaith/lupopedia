<?php
/**
 * DoctrineIngester - Scans and parses Channel 0 broadcast doctrines
 * 
 * Recursively scans the channels/0/broadcasts/ directory for .md files,
 * parses FLIP headers to extract doctrine metadata, and stores results
 * in memory. Handles errors gracefully with logging and continues processing.
 * 
 * Usage:
 *   $ingester = new DoctrineIngester($flipParser, $logger);
 *   $ingester->scanBroadcastDirectory('channels/0/broadcasts');
 *   $doctrines = $ingester->getIngestedDoctrines();
 *   $count = $ingester->getDoctrineCount();
 * 
 * @package Lupopedia\Services\Initialization
 * @since 4.0.44
 */
class DoctrineIngester implements DoctrineIngesterInterface
{
    /**
     * FLIP header parser instance
     * 
     * @var FLIPHeaderParserInterface
     */
    private $flipParser;
    
    /**
     * Logger instance
     * 
     * @var InitializationLoggerInterface
     */
    private $logger;
    
    /**
     * Array of ingested doctrine metadata
     * 
     * @var array
     */
    private $doctrines;
    /** @var object|null */
    private $headerValidator;
    
    /**
     * Constructor
     * 
     * @param FLIPHeaderParserInterface $flipParser FLIP header parser
     * @param InitializationLoggerInterface $logger Logger instance
     */
    public function __construct(FLIPHeaderParserInterface $flipParser, InitializationLoggerInterface $logger)
    {
        $this->flipParser = $flipParser;
        $this->logger = $logger;
        $this->doctrines = array();
        $this->headerValidator = null;
    }
    
    /**
     * Scan the Channel 0 broadcasts directory recursively for .md files
     * 
     * Finds all .md files in the broadcast directory and subdirectories,
     * parses each file to extract doctrine metadata, and stores results.
     * Continues processing even if individual files fail.
     * 
     * @param string $broadcastPath Path to channels/0/broadcasts/ directory
     * @return void
     * @throws DoctrineIngestionException If directory cannot be scanned
     */
    public function scanBroadcastDirectory($broadcastPath)
    {
        // Verify directory exists
        if (!is_dir($broadcastPath)) {
            throw new DoctrineIngestionException(
                ErrorMessages::directoryNotFound($broadcastPath, 'DoctrineIngester')
            );
        }
        
        // Verify directory is readable
        if (!is_readable($broadcastPath)) {
            throw new DoctrineIngestionException(
                ErrorMessages::directoryNotReadable($broadcastPath, 'DoctrineIngester')
            );
        }
        
        $this->logger->info(
            "Starting doctrine ingestion scan",
            array('path' => $broadcastPath)
        );
        
        // Find all .md files recursively
        $files = $this->findMarkdownFiles($broadcastPath);
        
        $this->logger->info(
            "Found markdown files",
            array('count' => count($files))
        );
        
        // Parse each file
        foreach ($files as $filePath) {
            try {
                $doctrine = $this->parseBroadcast($filePath);
                
                // Only store if we got valid doctrine metadata
                if (!empty($doctrine)) {
                    $this->doctrines[] = $doctrine;
                }
            } catch (DoctrineIngestionException $e) {
                // Log error and continue with next file
                $this->logger->warning(
                    "Failed to parse broadcast file",
                    array(
                        'file' => $filePath,
                        'error' => $e->getMessage()
                    )
                );
            }
        }
        
        $this->logger->info(
            "Doctrine ingestion complete",
            array('total_doctrines' => count($this->doctrines))
        );
    }
    
    /**
     * Parse a single broadcast file and extract doctrine metadata
     * 
     * Reads the file, parses the FLIP header, and extracts doctrine-specific
     * metadata including doctrine_number, title, system_version, enforcement_scope,
     * and constraints. Handles missing or invalid headers gracefully.
     * 
     * @param string $filePath Path to broadcast file
     * @return array Doctrine metadata (doctrine_number, title, system_version, etc.)
     * @throws DoctrineIngestionException If file cannot be parsed
     */
    public function parseBroadcast($filePath)
    {
        // Verify file exists
        if (!file_exists($filePath)) {
            throw new DoctrineIngestionException(
                ErrorMessages::fileNotFound($filePath, 'DoctrineIngester')
            );
        }
        
        // Verify file is readable
        if (!is_readable($filePath)) {
            throw new DoctrineIngestionException(
                ErrorMessages::fileNotReadable($filePath, 'DoctrineIngester')
            );
        }
        
        // Read file content
        $content = @file_get_contents($filePath);
        if ($content === false) {
            throw new DoctrineIngestionException(
                ErrorMessages::fileReadFailed($filePath, 'DoctrineIngester')
            );
        }
        
        // Parse FLIP header
        $header = $this->flipParser->parse($content);

        if (empty($header)) {
            throw new DoctrineIngestionException(
                'Header validation failed: ' . json_encode(array(
                    'valid' => false,
                    'errors' => array('Missing or malformed header block.')
                ))
            );
        }

        $canonicalHeader = $this->extractCanonicalHeader($header);
        $validationResult = $this->getHeaderValidator()->validate($canonicalHeader);
        if (!isset($validationResult['valid']) || !$validationResult['valid']) {
            throw new DoctrineIngestionException(
                'Header validation failed: ' . json_encode($validationResult)
            );
        }
        
        // Extract doctrine metadata from header
        $doctrine = $this->extractDoctrineMetadata($filePath, $header, $content);
        
        return $doctrine;
    }

    /**
     * Build canonical header shape for HeaderValidationService from parser output.
     *
     * @param array $parsedHeader
     * @return array
     */
    private function extractCanonicalHeader($parsedHeader)
    {
        $h = array();

        if (isset($parsedHeader['lupopedia.headers']) && is_array($parsedHeader['lupopedia.headers'])) {
            $h = $parsedHeader['lupopedia.headers'];
        } else {
            $h = $parsedHeader;
        }

        $out = array();
        $keys = array(
            'version_when_written',
            'file_path_from_root',
            'last_modified_utc',
            'channel_id',
            'thread_id',
            'actor_id',
            'actor_name',
            'artifact_type',
            'artifact_kind',
        );

        foreach ($keys as $k) {
            if (isset($h[$k])) {
                $out[$k] = $h[$k];
            }
        }

        return $out;
    }

    /**
     * @return \App\Services\Validation\HeaderValidationService
     */
    private function getHeaderValidator()
    {
        if ($this->headerValidator !== null) {
            return $this->headerValidator;
        }

        $base = defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : dirname(dirname(dirname(dirname(dirname(__DIR__))))) . DIRECTORY_SEPARATOR;
        $appDir = defined('LUPO_APP_DIR')
            ? LUPO_APP_DIR
            : 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'lupo-app';
        $servicePath = rtrim($base, '/\\') . DIRECTORY_SEPARATOR . $appDir . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'Validation' . DIRECTORY_SEPARATOR . 'HeaderValidationService.php';
        if (file_exists($servicePath)) {
            require_once $servicePath;
        }

        $actorService = isset($GLOBALS['lupo_actor_service']) ? $GLOBALS['lupo_actor_service'] : null;
        $this->headerValidator = new \App\Services\Validation\HeaderValidationService($actorService);
        return $this->headerValidator;
    }
    
    /**
     * Get all ingested doctrines
     * 
     * @return array Array of doctrine metadata arrays
     */
    public function getIngestedDoctrines()
    {
        return $this->doctrines;
    }
    
    /**
     * Get total count of successfully ingested doctrines
     * 
     * @return int Count of doctrines
     */
    public function getDoctrineCount()
    {
        return count($this->doctrines);
    }
    
    /**
     * Find all .md files recursively in directory
     * 
     * @param string $directory Directory to scan
     * @return array Array of file paths
     */
    private function findMarkdownFiles($directory)
    {
        $files = array();
        
        // Use RecursiveDirectoryIterator for recursive scanning
        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    $directory,
                    RecursiveDirectoryIterator::SKIP_DOTS
                ),
                RecursiveIteratorIterator::SELF_FIRST
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile() && $this->isMarkdownFile($file->getPathname())) {
                    $files[] = $file->getPathname();
                }
            }
        } catch (Exception $e) {
            throw new DoctrineIngestionException(
                ErrorMessages::directoryScanFailed($directory, $e->getMessage(), 'DoctrineIngester')
            );
        }
        
        return $files;
    }
    
    /**
     * Check if file is a markdown file (.md extension)
     * 
     * @param string $filePath File path
     * @return bool True if markdown file, false otherwise
     */
    private function isMarkdownFile($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        return $extension === 'md';
    }
    
    /**
     * Extract doctrine metadata from FLIP header
     * 
     * @param string $filePath File path
     * @param array $header Parsed FLIP header
     * @param string $content File content
     * @return array Doctrine metadata
     */
    private function extractDoctrineMetadata($filePath, $header, $content)
    {
        $doctrine = array();
        
        // Extract file name for reference
        $doctrine['file_path'] = $filePath;
        $doctrine['file_name'] = basename($filePath);
        
        // Extract doctrine number from filename or content
        $doctrine['doctrine_number'] = $this->extractDoctrineNumber($filePath, $content);
        
        // Extract title from content (first heading)
        $doctrine['title'] = $this->extractTitle($content);
        
        // Extract system_version from header
        $doctrine['system_version'] = $this->getHeaderField($header, 'system_version', 'unknown');
        
        // Extract enforcement_scope (default to 'system' for Channel 0 broadcasts)
        $doctrine['enforcement_scope'] = $this->getHeaderField($header, 'enforcement_scope', 'system');
        
        // Extract constraints from content
        $doctrine['constraints'] = $this->extractConstraints($content);
        
        // Extract additional metadata
        $doctrine['actor_id'] = $this->getHeaderField($header, 'actor_id', null);
        $doctrine['channel_id'] = $this->getHeaderField($header, 'channel_id', 0);
        $doctrine['broadcast_type'] = $this->getHeaderField($header, 'broadcast_type', 'doctrine');
        $doctrine['priority'] = $this->getHeaderField($header, 'priority', 'normal');
        $doctrine['created_ymdhis'] = $this->getHeaderField($header, 'created_ymdhis', null);
        
        return $doctrine;
    }
    
    /**
     * Extract metadata from content when FLIP header is missing
     * 
     * @param string $filePath File path
     * @param string $content File content
     * @return array Doctrine metadata
     */
    private function extractMetadataFromContent($filePath, $content)
    {
        $doctrine = array();
        
        $doctrine['file_path'] = $filePath;
        $doctrine['file_name'] = basename($filePath);
        $doctrine['doctrine_number'] = $this->extractDoctrineNumber($filePath, $content);
        $doctrine['title'] = $this->extractTitle($content);
        $doctrine['system_version'] = 'unknown';
        $doctrine['enforcement_scope'] = 'system';
        $doctrine['constraints'] = $this->extractConstraints($content);
        $doctrine['actor_id'] = null;
        $doctrine['channel_id'] = 0;
        $doctrine['broadcast_type'] = 'doctrine';
        $doctrine['priority'] = 'normal';
        $doctrine['created_ymdhis'] = null;
        
        return $doctrine;
    }
    
    /**
     * Extract doctrine number from filename or content
     * 
     * Looks for patterns like:
     * - "Doctrine #1" in content
     * - "cw_0001" in filename
     * - Numeric prefix in filename
     * 
     * @param string $filePath File path
     * @param string $content File content
     * @return string Doctrine number or 'unknown'
     */
    private function extractDoctrineNumber($filePath, $content)
    {
        // Try to extract from content first (e.g., "Doctrine #1")
        if (preg_match('/Doctrine\s+#(\d+)/i', $content, $matches)) {
            return $matches[1];
        }
        
        // Try to extract from filename (e.g., "cw_0001_...")
        $filename = basename($filePath);
        if (preg_match('/cw_(\d+)/', $filename, $matches)) {
            return $matches[1];
        }
        
        // Try to extract numeric prefix from filename
        if (preg_match('/^(\d+)/', $filename, $matches)) {
            return $matches[1];
        }
        
        return 'unknown';
    }
    
    /**
     * Extract title from content (first heading)
     * 
     * @param string $content File content
     * @return string Title or 'Untitled Doctrine'
     */
    private function extractTitle($content)
    {
        // Look for first markdown heading (# Title)
        if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
            return trim($matches[1]);
        }
        
        // Look for heading without # (e.g., "TITLE" in all caps)
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line) && !preg_match('/^---/', $line)) {
                // Skip FLIP header lines
                if (preg_match('/^[A-Z\s]+$/', $line) && strlen($line) > 5) {
                    return $line;
                }
            }
        }
        
        return 'Untitled Doctrine';
    }
    
    /**
     * Extract constraints from content
     * 
     * Looks for numbered lists, bullet points, or paragraphs
     * that describe constraints or rules.
     * 
     * @param string $content File content
     * @return array Array of constraint strings
     */
    private function extractConstraints($content)
    {
        $constraints = array();
        
        // Remove FLIP header
        $content = preg_replace('/^---.*?---/s', '', $content);
        
        // Look for numbered lists (1. constraint)
        if (preg_match_all('/^\d+\.\s+(.+)$/m', $content, $matches)) {
            $constraints = array_merge($constraints, $matches[1]);
        }
        
        // Look for bullet points (- constraint or * constraint)
        if (preg_match_all('/^[\-\*]\s+(.+)$/m', $content, $matches)) {
            $constraints = array_merge($constraints, $matches[1]);
        }
        
        // If no structured constraints found, return first paragraph
        if (empty($constraints)) {
            $lines = explode("\n", trim($content));
            $paragraph = '';
            foreach ($lines as $line) {
                $line = trim($line);
                if (!empty($line) && !preg_match('/^#/', $line)) {
                    $paragraph .= $line . ' ';
                    if (strlen($paragraph) > 200) {
                        break;
                    }
                }
            }
            if (!empty($paragraph)) {
                $constraints[] = trim($paragraph);
            }
        }
        
        return $constraints;
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
