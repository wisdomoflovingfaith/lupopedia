<?php
/**
 * Channel 66 Production Ingester
 * 
 * Production-grade ingestion for Channel 66 with batch processing,
 * performance monitoring, and enhanced error handling.
 * 
 * @version 4.0.80
 * @author HEPHAESTUS (actor_id 3)
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Channel66ProductionConfig.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Channel66ProductionErrorHandler.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Channel66PerformanceMonitor.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Channel66ProductionLogger.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Channel66BatchProcessor.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'lupo-app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'Validation' . DIRECTORY_SEPARATOR . 'HeaderValidationService.php';

class Channel66ProductionIngester
{
    private $db;
    private $config;
    private $performanceMonitor;
    private $errorHandler;
    private $logger;
    private $batchProcessor;
    private $headerValidator;
    
    public function __construct($db, Channel66ProductionConfig $config, 
                                Channel66PerformanceMonitor $performanceMonitor,
                                Channel66ProductionErrorHandler $errorHandler,
                                Channel66ProductionLogger $logger,
                                Channel66BatchProcessor $batchProcessor)
    {
        $this->db = $db;
        $this->config = $config;
        $this->performanceMonitor = $performanceMonitor;
        $this->errorHandler = $errorHandler;
        $this->logger = $logger;
        $this->batchProcessor = $batchProcessor;
        $actorService = isset($GLOBALS['lupo_actor_service']) ? $GLOBALS['lupo_actor_service'] : null;
        $this->headerValidator = new \App\Services\Validation\HeaderValidationService($actorService);
    }
    
    /**
     * Run production migration for Channel 66
     */
    public function runProductionMigration($threadId = null, $dryRun = false)
    {
        $this->performanceMonitor->startMigration();
        
        try {
            $result = array(
                'files_discovered' => 0,
                'files_processed' => 0,
                'files_ingested' => 0,
                'files_rejected' => 0,
                'files_conflict_flagged' => 0,
                'batches_processed' => 0,
                'batches_failed' => 0,
                'peak_memory_mb' => 0,
                'avg_files_per_second' => 0,
                'total_runtime_seconds' => 0,
                'errors' => array()
            );
            
            // Discover files for full Channel 66 or specific thread
            $files = $this->discoverChannelFiles($threadId);
            $result['files_discovered'] = count($files);
            
            if (empty($files)) {
                $this->logger->logInfo("No files found for processing");
                return $result;
            }
            
            // Process files in batches
            $this->performanceMonitor->recordMetric('files_discovered', count($files));
            
            $batches = $this->batchProcessor->createBatches($files);
            $result['batches_processed'] = count($batches);
            
            foreach ($batches as $batchIndex => $batch) {
                $this->performanceMonitor->startBatch($batchIndex + 1, count($batches));
                
                try {
                    $batchResult = $this->processBatch($batch, $dryRun);
                    
                    if ($batchResult['success']) {
                        $result['files_processed'] += $batchResult['files_processed'];
                        $result['files_ingested'] += $batchResult['files_ingested'];
                        $result['files_rejected'] += $batchResult['files_rejected'];
                        $result['files_conflict_flagged'] += $batchResult['files_conflict_flagged'];
                    } else {
                        $result['batches_failed']++;
                        $error = "Batch " . ($batchIndex + 1) . " failed: " . $batchResult['error'];
                        $result['errors'][] = $error;
                        $this->errorHandler->handleError($error, $batch, $batchIndex);
                    }
                    
                } catch (Exception $e) {
                    $result['batches_failed']++;
                    $error = "Batch " . ($batchIndex + 1) . " exception: " . $e->getMessage();
                    $result['errors'][] = $error;
                    $this->errorHandler->handleException($e, $batch, $batchIndex);
                }
                
                $this->performanceMonitor->endBatch();
                $this->batchProcessor->enforceMemoryLimit();
            }
            
            // Calculate final metrics
            $result['total_runtime_seconds'] = $this->performanceMonitor->getTotalRuntime();
            $result['peak_memory_mb'] = $this->performanceMonitor->getPeakMemoryMb();
            $result['avg_files_per_second'] = $result['total_runtime_seconds'] > 0 ? 
                round($result['files_processed'] / $result['total_runtime_seconds'], 2) : 0;
            
            $this->performanceMonitor->endMigration();
            
            // Log migration summary
            $this->logger->logMigrationSummary($result);
            
            return $result;
            
        } catch (Exception $e) {
            $this->performanceMonitor->endMigration();
            $this->errorHandler->handleCriticalException($e);
            throw $e;
        }
    }
    
    /**
     * Discover files for Channel 66 (all threads or specific thread)
     */
    private function discoverChannelFiles($threadId)
    {
        $channelPath = $this->config->getScopeRoot() . '/lupo-channels/66';
        
        if ($threadId === null) {
            // All threads under Channel 66
            $threadsPath = $channelPath . '/threads';
            if (!is_dir($threadsPath)) {
                throw new Exception("Channel 66 threads directory not found: {$threadsPath}");
            }

            $files = array();
            $threadDirs = glob($threadsPath . '/*', GLOB_ONLYDIR);

            foreach ($threadDirs as $threadDir) {
                $threadFiles = $this->discoverThreadFiles($threadDir);
                $files = array_merge($files, $threadFiles);
            }
        } else {
            // Single thread
            $threadPath = $channelPath . '/threads/' . $threadId;
            $files = $this->discoverThreadFiles($threadPath);
        }
        
        // Deterministic lexicographic ordering
        sort($files);
        return $files;
    }
    
    /**
     * Discover files in a specific thread directory
     */
    private function discoverThreadFiles($threadDir)
    {
        if (!is_dir($threadDir)) {
            return array();
        }
        
        $files = array();
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($threadDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'md') {
                $files[] = $file->getPathname();
            }
        }
        
        sort($files);
        return $files;
    }
    
    /**
     * Process a single batch of files
     */
    private function processBatch($batch, $dryRun = false)
    {
        $batchResult = array(
            'success' => true,
            'files_processed' => 0,
            'files_ingested' => 0,
            'files_rejected' => 0,
            'files_conflict_flagged' => 0,
            'error' => null
        );
        
        foreach ($batch as $file) {
            try {
                $outcome = $dryRun ? 
                    $this->validateFileOnly($file) : 
                    $this->processFile($file);
                
                $batchResult['files_processed']++;
                
                switch ($outcome['validation_status']) {
                    case 'ingested':
                        $batchResult['files_ingested']++;
                        break;
                    case 'rejected':
                        $batchResult['files_rejected']++;
                        break;
                    case 'conflict_flagged':
                        $batchResult['files_conflict_flagged']++;
                        break;
                }
            } catch (Exception $e) {
                // Re-throw to stop batch processing
                throw $e;
            }
        }
        
        return $batchResult;
    }
    
    /**
     * Validate file without database writes (for dry run)
     */
    private function validateFileOnly($filePath)
    {
        // Get repo-relative path
        $repoRelativePath = $this->getRepoRelativePath($filePath);
        
        // Extract and parse YAML
        $yamlData = $this->extractYamlFrontMatter($filePath);
        if (!$yamlData) {
            return array(
                'validation_status' => 'rejected',
                'reject_type' => 'malformed_yaml'
            );
        }
        
        // Apply all P0 validations (without DB writes)
        $validation = $this->validateP0Requirements($yamlData, $repoRelativePath);
        
        return $validation;
    }
    
    /**
     * Compute deterministic entity ID with file locking
     */
    private function processFile($filePath)
    {
        // Get repo-relative path
        $repoRelativePath = $this->getRepoRelativePath($filePath);

        $yamlData = $this->extractYamlFrontMatter($filePath);
        if (!$yamlData) {
            return array(
                'valid' => false,
                'validation_status' => 'rejected',
                'reject_type' => 'malformed_yaml',
                'errors' => array('Malformed header: unable to parse YAML frontmatter.')
            );
        }

        // P0 + mandatory header gate.
        $validation = $this->validateP0Requirements($yamlData, $repoRelativePath);
        if (!isset($validation['valid']) || !$validation['valid']) {
            return $validation;
        }

        $initialMtime = @filemtime($filePath);
        clearstatcache();
        $currentMtime = @filemtime($filePath);
        if ($initialMtime !== $currentMtime) {
            return array(
                'valid' => false,
                'validation_status' => 'conflict_flagged',
                'conflict_type' => 'concurrent_edit',
                'conflict_reason' => 'file_mtime_changed'
            );
        }

        $entityId = $this->computeDeterministicEntityId($repoRelativePath);
        $this->projectToDatabase($entityId, $yamlData, $validation);

        return array(
            'valid' => true,
            'validation_status' => 'ingested',
            'entity_id' => $entityId
        );
    }
    
    /**
     * Validate P0 requirements (structural, version, TOON, actor)
     */
    private function validateP0Requirements($yamlData, $repoRelativePath)
    {
        // Load existing P0 validator for reuse
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'BoundedHeaderAuthorityValidator.php';
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'ToonSchemaCache.php';
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'HeaderFieldPreservationMatrix.php';
        
        $toonCache = new ToonSchemaCache($this->config->getToonDir());
        $validator = new BoundedHeaderAuthorityValidator($toonCache);
        $preservationMatrix = new HeaderFieldPreservationMatrix();
        
        // Structural validation
        if (!isset($yamlData['lupopedia.headers'])) {
            return array(
                'valid' => false,
                'validation_status' => 'rejected',
                'reject_type' => 'structural_validation_failure',
                'validation_warnings' => array('missing_lupopedia_headers_block')
            );
        }

        $headerValidation = $this->headerValidator->validate($yamlData['lupopedia.headers']);
        if (!isset($headerValidation['valid']) || !$headerValidation['valid']) {
            return array(
                'valid' => false,
                'validation_status' => 'rejected',
                'reject_type' => 'header_validation_failed',
                'errors' => isset($headerValidation['errors']) ? $headerValidation['errors'] : array('header_validation_failed')
            );
        }
        
        // Required fields validation
        $requiredFields = array(
            'lupopedia.version',
            'lupopedia.schema',
            'file_path_from_root',
            'web_path',
            'last_modified_utc',
            'system_version',
            'channel_id',
            'actor_id',
            'delegation_chain',
            'artifact_type',
            'artifact_kind',
            'purpose'
        );
        
        $headersBlock = $yamlData['lupopedia.headers'];
        $missingFields = array();
        
        foreach ($requiredFields as $field) {
            if (!isset($headersBlock[$field])) {
                $missingFields[] = $field;
            }
        }
        
        if (!empty($missingFields)) {
            return array(
                'valid' => false,
                'validation_status' => 'rejected',
                'reject_type' => 'structural_validation_failure',
                'validation_warnings' => $missingFields
            );
        }
        
        // Identity binding validation
        if (isset($headersBlock['file_path_from_root']) && 
            $headersBlock['file_path_from_root'] !== $repoRelativePath) {
            return array(
                'valid' => false,
                'validation_status' => 'rejected',
                'reject_type' => 'structural_validation_failure',
                'validation_warnings' => array('file_path_from_root_mismatch')
            );
        }
        
        // Version compatibility validation
        $versionResult = $validator->validateVersionCompatibility($yamlData);
        if (!$versionResult['valid']) {
            return array(
                'valid' => false,
                'validation_status' => 'rejected',
                'reject_type' => 'version_incompatible',
                'version_scenario' => $versionResult['scenario']
            );
        }
        
        // TOON validation
        $toonResult = $validator->validateToonSchema();
        if (!$toonResult['valid']) {
            return array(
                'valid' => false,
                'validation_status' => 'rejected',
                'reject_type' => 'toon_conflict',
                'toon_error_code' => $toonResult['error_code']
            );
        }
        
        // Actor validation
        $actorResult = $validator->validateActorExists($yamlData);
        if (!$actorResult['valid']) {
            return array(
                'valid' => false,
                'validation_status' => 'rejected',
                'reject_type' => 'unknown_actor_id'
            );
        }
        
        return array('valid' => true);
    }
    
    /**
     * Project validated file to database
     */
    private function projectToDatabase($entityId, $yamlData, $validation)
    {
        // Load existing projection class for reuse
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'Channel66HeaderProjection.php';
        
        $projection = new Channel66HeaderProjection($this->db);
        
        // Apply field preservation matrix
        $preservationMatrix = new HeaderFieldPreservationMatrix();
        $classifiedFields = $preservationMatrix->classifyFields($yamlData['lupopedia.headers']);
        
        // Project with validation status
        $projection->projectProduction(
            $entityId,
            $yamlData,
            $classifiedFields,
            $validation['validation_status'],
            isset($validation['warning_codes']) ? $validation['warning_codes'] : array()
        );
    }
    
    /**
     * Get repo-relative path from absolute path
     */
    private function getRepoRelativePath($absolutePath)
    {
        $scopeRoot = $this->config->getScopeRoot();
        if (strpos($absolutePath, $scopeRoot) !== 0) {
            throw new Exception("File path is outside scope root: {$absolutePath}");
        }
        
        return ltrim(substr($absolutePath, strlen($scopeRoot)), '/\\');
    }
    
    /**
     * Compute deterministic entity ID
     */
    private function computeDeterministicEntityId($filePathFromRoot)
    {
        $hash = md5($filePathFromRoot);
        $hexSubstr = substr($hash, 0, 15);
        $entityId = hexdec($hexSubstr);
        
        return max(0, $entityId);
    }
    
    /**
     * Extract YAML front matter from markdown file
     */
    private function extractYamlFrontMatter($filePath)
    {
        $content = file_get_contents($filePath);
        if ($content === false) {
            return null;
        }
        
        $lines = explode("\n", $content);
        
        if (count($lines) < 2 || trim($lines[0]) !== '---') {
            return null;
        }
        
        $yamlBlock = '';
        for ($i = 1; $i < count($lines); $i++) {
            if (preg_match('/^---\s*$/', trim($lines[$i]))) {
                break;
            }
            $yamlBlock .= $lines[$i] . "\n";
        }
        
        if (!function_exists('yaml_parse')) {
            throw new Exception('yaml_parse function not available');
        }
        
        $parsed = yaml_parse($yamlBlock);
        if ($parsed === false) {
            return null;
        }
        
        return $parsed;
    }
}
