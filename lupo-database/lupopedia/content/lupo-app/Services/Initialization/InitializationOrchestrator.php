<?php
/**
 * InitializationOrchestrator - Coordinates complete initialization workflow
 * 
 * This class orchestrates all initialization components in the correct sequence,
 * implementing a "continue on error" strategy that logs errors and proceeds with
 * remaining tasks. It tracks timestamps, collects results from all components,
 * passes results between components as needed, and generates a final status
 * report listing successes and failures.
 * 
 * Workflow Sequence:
 * 1. Doctrine ingestion from Channel 0
 * 2. Development thread creation in Channel 42
 * 3. Status directory audit
 * 4. Audit report generation
 * 5. Channel 42 summary posting
 * 6. System log writing
 * 7. Validation
 * 8. Completion notification
 * 
 * Usage:
 *   $orchestrator = new InitializationOrchestrator(
 *       $flipParser,
 *       $timestampHelper,
 *       $classifier,
 *       $logger,
 *       '/path/to/lupopedia'
 *   );
 *   $results = $orchestrator->run();
 *   $isSuccessful = $orchestrator->isSuccessful();
 * 
 * @package Lupopedia\Services\Initialization
 * @since 4.0.44
 */
class InitializationOrchestrator implements InitializationOrchestratorInterface
{
    /**
     * @var FLIPHeaderParserInterface FLIP header parser
     */
    private $flipParser;
    
    /**
     * @var TimestampHelperInterface Timestamp helper
     */
    private $timestampHelper;
    
    /**
     * @var VersionClassifierInterface Version classifier
     */
    private $classifier;
    
    /**
     * @var InitializationLoggerInterface Logger instance
     */
    private $logger;
    
    /**
     * @var string Base path to Lupopedia installation
     */
    private $basePath;
    
    /**
     * @var array Workflow execution results
     */
    private $results;
    
    /**
     * @var bool Overall success status
     */
    private $isSuccessful;
    
    /**
     * @var string Workflow start timestamp
     */
    private $startTime;
    
    /**
     * @var string Workflow end timestamp
     */
    private $endTime;
    
    /**
     * @var FileSafetyCheckerInterface File safety checker
     */
    private $fileSafetyChecker;
    
    /**
     * Constructor
     * 
     * @param FLIPHeaderParserInterface $flipParser FLIP header parser
     * @param TimestampHelperInterface $timestampHelper Timestamp helper
     * @param VersionClassifierInterface $classifier Version classifier
     * @param InitializationLoggerInterface $logger Logger instance
     * @param string $basePath Base path to Lupopedia installation
     */
    public function __construct(
        FLIPHeaderParserInterface $flipParser,
        TimestampHelperInterface $timestampHelper,
        VersionClassifierInterface $classifier,
        InitializationLoggerInterface $logger,
        $basePath
    ) {
        $this->flipParser = $flipParser;
        $this->timestampHelper = $timestampHelper;
        $this->classifier = $classifier;
        $this->logger = $logger;
        $this->basePath = rtrim($basePath, '/\\');
        $this->results = array();
        $this->isSuccessful = false;
        $this->startTime = null;
        $this->endTime = null;
        
        // Initialize file safety checker
        $this->fileSafetyChecker = new FileSafetyChecker($logger);
    }
    
    /**
     * Execute the complete initialization workflow
     * 
     * Coordinates all components in the correct sequence:
     * 1. Doctrine ingestion from Channel 0
     * 2. Development thread creation in Channel 42
     * 3. Status directory audit
     * 4. Audit report generation
     * 5. Channel 42 summary posting
     * 6. System log writing
     * 7. Validation
     * 8. Completion notification
     * 
     * Implements "continue on error" strategy - logs errors and proceeds
     * with remaining tasks.
     * 
     * @return array Final status report with successes and failures
     * @throws InitializationException If critical failure prevents continuation
     */
    public function run()
    {
        // Record start time
        $this->startTime = $this->timestampHelper->getCurrentUTC();
        
        $this->logger->info(
            'Starting initialization workflow',
            array('start_time' => $this->startTime)
        );
        
        // Initialize results structure
        $this->results = array(
            'start_time' => $this->startTime,
            'end_time' => null,
            'steps' => array(),
            'successes' => array(),
            'failures' => array(),
            'overall_status' => 'in_progress'
        );
        
        // Execute workflow steps in sequence
        $this->executeDoctrineIngestion();
        $this->executeThreadCreation();
        $this->executeStatusAudit();
        $this->executeReportGeneration();
        $this->executeSummaryPosting();
        $this->executeLogWriting();
        $this->executeValidation();
        $this->executeCompletionNotification();
        
        // Record end time
        $this->endTime = $this->timestampHelper->getCurrentUTC();
        $this->results['end_time'] = $this->endTime;
        
        // Determine overall success
        $this->isSuccessful = empty($this->results['failures']);
        $this->results['overall_status'] = $this->isSuccessful ? 'success' : 'partial_success';
        
        $this->logger->info(
            'Initialization workflow complete',
            array(
                'end_time' => $this->endTime,
                'overall_status' => $this->results['overall_status'],
                'successes' => count($this->results['successes']),
                'failures' => count($this->results['failures'])
            )
        );
        
        return $this->results;
    }
    
    /**
     * Get workflow execution results
     * 
     * @return array Detailed results from each workflow step
     */
    public function getResults()
    {
        return $this->results;
    }
    
    /**
     * Check if workflow completed successfully
     * 
     * @return bool True if all critical steps succeeded, false otherwise
     */
    public function isSuccessful()
    {
        return $this->isSuccessful;
    }
    
    /**
     * Execute Step 1: Doctrine ingestion from Channel 0
     * 
     * Scans channels/0/broadcasts/ directory for doctrine broadcasts,
     * parses FLIP headers, and extracts doctrine metadata.
     * 
     * @return void
     */
    private function executeDoctrineIngestion()
    {
        $stepName = 'doctrine_ingestion';
        $this->logger->info('Starting Step 1: Doctrine Ingestion');
        
        try {
            // Create DoctrineIngester instance
            $ingester = new DoctrineIngester($this->flipParser, $this->logger);
            
            // Scan broadcast directory
            $broadcastPath = $this->basePath . '/channels/0/broadcasts';
            $ingester->scanBroadcastDirectory($broadcastPath);
            
            // Get results
            $doctrines = $ingester->getIngestedDoctrines();
            $doctrineCount = $ingester->getDoctrineCount();
            
            // Track read operations for each doctrine file
            foreach ($doctrines as $doctrine) {
                if (isset($doctrine['file_path'])) {
                    $this->fileSafetyChecker->trackOperation('read', $doctrine['file_path']);
                }
            }
            
            // Store results
            $this->results['steps'][$stepName] = array(
                'status' => 'success',
                'doctrine_count' => $doctrineCount,
                'doctrines' => $doctrines
            );
            
            $this->results['successes'][] = $stepName;
            
            $this->logger->info(
                'Step 1 completed successfully',
                array('doctrine_count' => $doctrineCount)
            );
            
        } catch (Exception $e) {
            // Log error and continue (continue on error strategy)
            $this->logger->error(
                'Step 1 failed: Doctrine Ingestion',
                array('error' => $e->getMessage())
            );
            
            $this->results['steps'][$stepName] = array(
                'status' => 'failed',
                'error' => $e->getMessage(),
                'doctrine_count' => 0,
                'doctrines' => array()
            );
            
            $this->results['failures'][] = array(
                'step' => $stepName,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Execute Step 2: Development thread creation in Channel 42
     * 
     * Creates a new thread directory and thread.json metadata file
     * for the 4.0.44 development cycle.
     * 
     * @return void
     */
    private function executeThreadCreation()
    {
        $stepName = 'thread_creation';
        $this->logger->info('Starting Step 2: Thread Creation');
        
        try {
            // Create ThreadCreator instance
            $creator = new ThreadCreator($this->timestampHelper, $this->basePath);
            
            // Create thread
            $threadId = 'DEVELOPMENT_CYCLE_4_0_45';
            $title = 'Crafty Syntax / Lupopedia Development — Version 4.0.45';
            $actorId = 1001; // KIRO
            $channelId = 42; // Development channel
            
            $metadata = $creator->createThread($threadId, $title, $actorId, $channelId);
            
            // Track file creation
            $threadJsonPath = "channels/42/threads/{$threadId}/thread.json";
            $this->fileSafetyChecker->trackOperation('create', $threadJsonPath);
            
            // Store results
            $this->results['steps'][$stepName] = array(
                'status' => 'success',
                'thread_id' => $threadId,
                'thread_metadata' => $metadata
            );
            
            $this->results['successes'][] = $stepName;
            
            $this->logger->info(
                'Step 2 completed successfully',
                array('thread_id' => $threadId)
            );
            
        } catch (Exception $e) {
            // Log error and continue
            $this->logger->error(
                'Step 2 failed: Thread Creation',
                array('error' => $e->getMessage())
            );
            
            $this->results['steps'][$stepName] = array(
                'status' => 'failed',
                'error' => $e->getMessage(),
                'thread_id' => null,
                'thread_metadata' => array()
            );
            
            $this->results['failures'][] = array(
                'step' => $stepName,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Execute Step 3: Status directory audit
     * 
     * Scans docs/status/ directory for .md and .log files, extracts
     * version metadata, and classifies files as retain/archive/deprecate.
     * 
     * @return void
     */
    private function executeStatusAudit()
    {
        $stepName = 'status_audit';
        $this->logger->info('Starting Step 3: Status Directory Audit');
        
        try {
            // Create StatusAuditor instance
            $auditor = new StatusAuditor(
                $this->flipParser,
                $this->classifier,
                $this->logger
            );
            
            // Scan status directory
            $statusPath = $this->basePath . '/docs/status';
            $auditor->scanStatusDirectory($statusPath);
            
            // Get results
            $auditResults = $auditor->getAuditResults();
            $dispositionCounts = $auditor->getDispositionCounts();
            
            // Track read operations for each audited file
            foreach ($auditResults as $result) {
                if (isset($result['file_path'])) {
                    $this->fileSafetyChecker->trackOperation('read', $result['file_path']);
                }
            }
            
            // Store results
            $this->results['steps'][$stepName] = array(
                'status' => 'success',
                'audit_results' => $auditResults,
                'disposition_counts' => $dispositionCounts
            );
            
            $this->results['successes'][] = $stepName;
            
            $this->logger->info(
                'Step 3 completed successfully',
                array(
                    'total_files' => count($auditResults),
                    'disposition_counts' => $dispositionCounts
                )
            );
            
        } catch (Exception $e) {
            // Log error and continue
            $this->logger->error(
                'Step 3 failed: Status Directory Audit',
                array('error' => $e->getMessage())
            );
            
            $this->results['steps'][$stepName] = array(
                'status' => 'failed',
                'error' => $e->getMessage(),
                'audit_results' => array(),
                'disposition_counts' => array()
            );
            
            $this->results['failures'][] = array(
                'step' => $stepName,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Execute Step 4: Audit report generation
     * 
     * Generates comprehensive Markdown audit report with FLIP header,
     * executive summary, file disposition table, recommendations, and
     * risk assessment.
     * 
     * @return void
     */
    private function executeReportGeneration()
    {
        $stepName = 'report_generation';
        $this->logger->info('Starting Step 4: Audit Report Generation');
        
        try {
            // Get audit results from previous step
            $auditResults = isset($this->results['steps']['status_audit']['audit_results'])
                ? $this->results['steps']['status_audit']['audit_results']
                : array();
            
            $dispositionCounts = isset($this->results['steps']['status_audit']['disposition_counts'])
                ? $this->results['steps']['status_audit']['disposition_counts']
                : array();
            
            // Create ReportGenerator instance
            $generator = new ReportGenerator($this->timestampHelper, $this->logger);
            
            // Generate report
            $reportPath = 'docs/status/kiro_status_directory_audit_4_0_44.md';
            $fullReportPath = $this->basePath . '/' . $reportPath;
            
            $generatedPath = $generator->generateAuditReport(
                $auditResults,
                $dispositionCounts,
                $fullReportPath
            );
            
            // Track file creation
            $this->fileSafetyChecker->trackOperation('create', $reportPath);
            
            // Store results
            $this->results['steps'][$stepName] = array(
                'status' => 'success',
                'report_path' => $reportPath
            );
            
            $this->results['successes'][] = $stepName;
            
            $this->logger->info(
                'Step 4 completed successfully',
                array('report_path' => $reportPath)
            );
            
        } catch (Exception $e) {
            // Log error and continue
            $this->logger->error(
                'Step 4 failed: Audit Report Generation',
                array('error' => $e->getMessage())
            );
            
            $this->results['steps'][$stepName] = array(
                'status' => 'failed',
                'error' => $e->getMessage(),
                'report_path' => null
            );
            
            $this->results['failures'][] = array(
                'step' => $stepName,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Execute Step 5: Channel 42 summary posting
     * 
     * Posts concise summary message (≤1000 characters) to Channel 42
     * thread summarizing doctrine ingestion, audit outcomes, risks,
     * and next steps.
     * 
     * @return void
     */
    private function executeSummaryPosting()
    {
        $stepName = 'summary_posting';
        $this->logger->info('Starting Step 5: Channel 42 Summary Posting');
        
        try {
            // Get data from previous steps
            $doctrineCount = isset($this->results['steps']['doctrine_ingestion']['doctrine_count'])
                ? $this->results['steps']['doctrine_ingestion']['doctrine_count']
                : 0;
            
            $dispositionCounts = isset($this->results['steps']['status_audit']['disposition_counts'])
                ? $this->results['steps']['status_audit']['disposition_counts']
                : array();
            
            $threadId = isset($this->results['steps']['thread_creation']['thread_id'])
                ? $this->results['steps']['thread_creation']['thread_id']
                : 'DEVELOPMENT_CYCLE_4_0_45';
            
            // Identify risks
            $risks = $this->identifyRisks();
            
            // Define next steps
            $nextSteps = array(
                'Review audit report in docs/status/',
                'Address any deprecated files if needed',
                'Begin 4.0.45 development work'
            );
            
            // Create SummaryPoster instance
            $poster = new SummaryPoster($this->timestampHelper, $this->logger);
            
            // Post summary
            $threadPath = $this->basePath . '/channels/42/threads/' . $threadId;
            
            $messagePath = $poster->postSummary(
                $threadPath,
                $doctrineCount,
                $dispositionCounts,
                $risks,
                $nextSteps
            );
            
            // Track file creation
            $relativeMessagePath = str_replace($this->basePath . '/', '', $messagePath);
            $this->fileSafetyChecker->trackOperation('create', $relativeMessagePath);
            
            // Store results
            $this->results['steps'][$stepName] = array(
                'status' => 'success',
                'summary_path' => str_replace($this->basePath . '/', '', $messagePath)
            );
            
            $this->results['successes'][] = $stepName;
            
            $this->logger->info(
                'Step 5 completed successfully',
                array('message_path' => $messagePath)
            );
            
        } catch (Exception $e) {
            // Log error and continue
            $this->logger->error(
                'Step 5 failed: Channel 42 Summary Posting',
                array('error' => $e->getMessage())
            );
            
            $this->results['steps'][$stepName] = array(
                'status' => 'failed',
                'error' => $e->getMessage(),
                'summary_path' => null
            );
            
            $this->results['failures'][] = array(
                'step' => $stepName,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Execute Step 6: System log writing
     * 
     * Writes detailed system initialization log with FLIP header,
     * timestamps, activity listings, anomalies, and checksums.
     * 
     * @return void
     */
    private function executeLogWriting()
    {
        $stepName = 'log_writing';
        $this->logger->info('Starting Step 6: System Log Writing');
        
        try {
            // Prepare log data from previous steps
            $channelsScanned = $this->prepareChannelsData();
            $threadsCreated = $this->prepareThreadsData();
            $doctrinesLoaded = $this->prepareDoctrinesData();
            $filesAudited = $this->prepareFilesAuditedData();
            $anomalies = $this->prepareAnomaliesData();
            $checksums = $this->prepareChecksumsData();
            
            // Create LogWriter instance
            $writer = new LogWriter($this->timestampHelper, $this->logger);
            
            // Write log
            $logPath = 'docs/status/kiro_4_0_44_cycle_initialization_log.md';
            $fullLogPath = $this->basePath . '/' . $logPath;
            
            $generatedPath = $writer->writeLog(
                $fullLogPath,
                $this->startTime,
                $this->timestampHelper->getCurrentUTC(),
                $channelsScanned,
                $threadsCreated,
                $doctrinesLoaded,
                $filesAudited,
                $anomalies,
                $checksums
            );
            
            // Track file creation
            $this->fileSafetyChecker->trackOperation('create', $logPath);
            
            // Store results
            $this->results['steps'][$stepName] = array(
                'status' => 'success',
                'log_path' => $logPath
            );
            
            $this->results['successes'][] = $stepName;
            
            $this->logger->info(
                'Step 6 completed successfully',
                array('log_path' => $logPath)
            );
            
        } catch (Exception $e) {
            // Log error and continue
            $this->logger->error(
                'Step 6 failed: System Log Writing',
                array('error' => $e->getMessage())
            );
            
            $this->results['steps'][$stepName] = array(
                'status' => 'failed',
                'error' => $e->getMessage(),
                'log_path' => null
            );
            
            $this->results['failures'][] = array(
                'step' => $stepName,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Execute Step 7: Validation
     * 
     * Validates all initialization outputs to ensure workflow completed
     * successfully. Checks doctrine count, thread directory, thread metadata,
     * audit report, summary length, system log, and file safety.
     * 
     * @return void
     */
    private function executeValidation()
    {
        $stepName = 'validation';
        $this->logger->info('Starting Step 7: Validation');
        
        try {
            // Create Validator instance
            $validator = new Validator($this->logger, $this->basePath);
            
            // Prepare validation context
            $context = array(
                'doctrine_count' => isset($this->results['steps']['doctrine_ingestion']['doctrine_count'])
                    ? $this->results['steps']['doctrine_ingestion']['doctrine_count']
                    : 0,
                'thread_id' => isset($this->results['steps']['thread_creation']['thread_id'])
                    ? $this->results['steps']['thread_creation']['thread_id']
                    : null,
                'thread_metadata' => isset($this->results['steps']['thread_creation']['thread_metadata'])
                    ? $this->results['steps']['thread_creation']['thread_metadata']
                    : array(),
                'audit_report_path' => isset($this->results['steps']['report_generation']['report_path'])
                    ? $this->results['steps']['report_generation']['report_path']
                    : null,
                'summary_path' => isset($this->results['steps']['summary_posting']['summary_path'])
                    ? $this->results['steps']['summary_posting']['summary_path']
                    : null,
                'log_path' => isset($this->results['steps']['log_writing']['log_path'])
                    ? $this->results['steps']['log_writing']['log_path']
                    : null,
                'files_deleted' => $this->getDeletedFiles()
            );
            
            // Run validation
            $validationSummary = $validator->validateInitialization($context);
            
            // Store results
            $this->results['steps'][$stepName] = array(
                'status' => $validationSummary['is_valid'] ? 'success' : 'failed',
                'validation_summary' => $validationSummary
            );
            
            if ($validationSummary['is_valid']) {
                $this->results['successes'][] = $stepName;
                
                $this->logger->info('Step 7 completed successfully');
            } else {
                $this->results['failures'][] = array(
                    'step' => $stepName,
                    'error' => 'Validation checks failed',
                    'errors' => $validator->getErrors()
                );
                
                $this->logger->warning(
                    'Step 7 completed with validation failures',
                    array('errors' => $validator->getErrors())
                );
            }
            
        } catch (Exception $e) {
            // Log error and continue
            $this->logger->error(
                'Step 7 failed: Validation',
                array('error' => $e->getMessage())
            );
            
            $this->results['steps'][$stepName] = array(
                'status' => 'failed',
                'error' => $e->getMessage(),
                'validation_summary' => array()
            );
            
            $this->results['failures'][] = array(
                'step' => $stepName,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Execute Step 8: Completion notification
     * 
     * Posts completion or failure notification to Channel 42 based on
     * validation results. References audit report and system log.
     * 
     * @return void
     */
    private function executeCompletionNotification()
    {
        $stepName = 'completion_notification';
        $this->logger->info('Starting Step 8: Completion Notification');
        
        try {
            // Determine success status from validation
            $validationPassed = isset($this->results['steps']['validation']['validation_summary']['is_valid'])
                ? $this->results['steps']['validation']['validation_summary']['is_valid']
                : false;
            
            // Get validation errors if any
            $validationErrors = isset($this->results['steps']['validation']['validation_summary']['errors'])
                ? $this->results['steps']['validation']['validation_summary']['errors']
                : array();
            
            // Get file paths
            $auditReportPath = isset($this->results['steps']['report_generation']['report_path'])
                ? $this->results['steps']['report_generation']['report_path']
                : 'docs/status/kiro_status_directory_audit_4_0_44.md';
            
            $systemLogPath = isset($this->results['steps']['log_writing']['log_path'])
                ? $this->results['steps']['log_writing']['log_path']
                : 'docs/status/kiro_4_0_44_cycle_initialization_log.md';
            
            $threadId = isset($this->results['steps']['thread_creation']['thread_id'])
                ? $this->results['steps']['thread_creation']['thread_id']
                : 'DEVELOPMENT_CYCLE_4_0_45';
            
            // Create CompletionNotifier instance
            $notifier = new CompletionNotifier($this->timestampHelper, $this->logger);
            
            // Post completion notification
            $threadPath = $this->basePath . '/channels/42/threads/' . $threadId;
            
            $messagePath = $notifier->postCompletion(
                $threadPath,
                $validationPassed,
                $auditReportPath,
                $systemLogPath,
                $validationErrors
            );
            
            // Track file creation
            $relativeMessagePath = str_replace($this->basePath . '/', '', $messagePath);
            $this->fileSafetyChecker->trackOperation('create', $relativeMessagePath);
            
            // Store results
            $this->results['steps'][$stepName] = array(
                'status' => 'success',
                'notification_path' => str_replace($this->basePath . '/', '', $messagePath),
                'validation_passed' => $validationPassed
            );
            
            $this->results['successes'][] = $stepName;
            
            $this->logger->info(
                'Step 8 completed successfully',
                array(
                    'message_path' => $messagePath,
                    'validation_passed' => $validationPassed
                )
            );
            
        } catch (Exception $e) {
            // Log error and continue
            $this->logger->error(
                'Step 8 failed: Completion Notification',
                array('error' => $e->getMessage())
            );
            
            $this->results['steps'][$stepName] = array(
                'status' => 'failed',
                'error' => $e->getMessage(),
                'notification_path' => null
            );
            
            $this->results['failures'][] = array(
                'step' => $stepName,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Identify critical risks from workflow results
     * 
     * Analyzes workflow results to identify risks or anomalies that
     * should be highlighted in the summary.
     * 
     * @return array Array of risk descriptions
     */
    private function identifyRisks()
    {
        $risks = array();
        
        // Check doctrine count
        $doctrineCount = isset($this->results['steps']['doctrine_ingestion']['doctrine_count'])
            ? $this->results['steps']['doctrine_ingestion']['doctrine_count']
            : 0;
        
        if ($doctrineCount < 20) {
            $risks[] = "Low doctrine count: only {$doctrineCount} doctrines loaded (expected at least 20)";
        }
        
        // Check for deprecated files
        $dispositionCounts = isset($this->results['steps']['status_audit']['disposition_counts'])
            ? $this->results['steps']['status_audit']['disposition_counts']
            : array();
        
        $deprecateCount = isset($dispositionCounts['deprecate']) ? $dispositionCounts['deprecate'] : 0;
        
        if ($deprecateCount > 0) {
            $risks[] = "{$deprecateCount} deprecated files identified (review before deletion)";
        }
        
        // Check for any step failures
        if (!empty($this->results['failures'])) {
            $failureCount = count($this->results['failures']);
            $risks[] = "{$failureCount} workflow step(s) failed (see system log for details)";
        }
        
        return $risks;
    }
    
    /**
     * Prepare channels data for system log
     * 
     * @return array Array of channel scan data
     */
    private function prepareChannelsData()
    {
        $channels = array();
        
        // Channel 0 (broadcasts)
        $doctrineCount = isset($this->results['steps']['doctrine_ingestion']['doctrine_count'])
            ? $this->results['steps']['doctrine_ingestion']['doctrine_count']
            : 0;
        
        $doctrineStatus = isset($this->results['steps']['doctrine_ingestion']['status'])
            ? $this->results['steps']['doctrine_ingestion']['status']
            : 'unknown';
        
        $channels[] = array(
            'channel_id' => 0,
            'channel_name' => 'System Broadcasts',
            'file_count' => $doctrineCount,
            'status' => $doctrineStatus
        );
        
        return $channels;
    }
    
    /**
     * Prepare threads data for system log
     * 
     * @return array Array of thread creation data
     */
    private function prepareThreadsData()
    {
        $threads = array();
        
        if (isset($this->results['steps']['thread_creation']['thread_metadata'])) {
            $metadata = $this->results['steps']['thread_creation']['thread_metadata'];
            
            $threads[] = array(
                'thread_id' => isset($metadata['thread_id']) ? $metadata['thread_id'] : 'Unknown',
                'title' => isset($metadata['title']) ? $metadata['title'] : 'Unknown',
                'channel_id' => isset($metadata['channel_id']) ? $metadata['channel_id'] : 42,
                'status' => isset($this->results['steps']['thread_creation']['status'])
                    ? $this->results['steps']['thread_creation']['status']
                    : 'unknown'
            );
        }
        
        return $threads;
    }
    
    /**
     * Prepare doctrines data for system log
     * 
     * @return array Array of doctrine metadata
     */
    private function prepareDoctrinesData()
    {
        $doctrines = isset($this->results['steps']['doctrine_ingestion']['doctrines'])
            ? $this->results['steps']['doctrine_ingestion']['doctrines']
            : array();
        
        return $doctrines;
    }
    
    /**
     * Prepare files audited data for system log
     * 
     * @return array Array of file audit data
     */
    private function prepareFilesAuditedData()
    {
        $files = isset($this->results['steps']['status_audit']['audit_results'])
            ? $this->results['steps']['status_audit']['audit_results']
            : array();
        
        return $files;
    }
    
    /**
     * Prepare anomalies data for system log
     * 
     * Collects all errors and warnings from workflow execution.
     * 
     * @return array Array of anomaly descriptions
     */
    private function prepareAnomaliesData()
    {
        $anomalies = array();
        
        // Add failures as anomalies
        foreach ($this->results['failures'] as $failure) {
            $anomalies[] = array(
                'type' => 'Workflow Step Failure',
                'description' => isset($failure['step']) 
                    ? "Step '{$failure['step']}' failed: " . $failure['error']
                    : $failure['error'],
                'severity' => 'high'
            );
        }
        
        // Extract warnings from logger
        $logEntries = $this->logger->getEntries();
        foreach ($logEntries as $entry) {
            if ($entry['level'] === 'WARNING') {
                $anomalies[] = array(
                    'type' => 'Warning',
                    'description' => $entry['message'],
                    'severity' => 'medium'
                );
            }
        }
        
        return $anomalies;
    }
    
    /**
     * Prepare checksums data for system log
     * 
     * Generates SHA-256 checksums for critical files created during
     * initialization.
     * 
     * @return array Array of file checksums (path => checksum)
     */
    private function prepareChecksumsData()
    {
        $checksums = array();
        
        // Checksum audit report
        if (isset($this->results['steps']['report_generation']['report_path'])) {
            $reportPath = $this->results['steps']['report_generation']['report_path'];
            $fullPath = $this->basePath . '/' . $reportPath;
            
            if (file_exists($fullPath)) {
                $checksums[$reportPath] = hash_file('sha256', $fullPath);
            }
        }
        
        // Checksum system log
        if (isset($this->results['steps']['log_writing']['log_path'])) {
            $logPath = $this->results['steps']['log_writing']['log_path'];
            $fullPath = $this->basePath . '/' . $logPath;
            
            if (file_exists($fullPath)) {
                $checksums[$logPath] = hash_file('sha256', $fullPath);
            }
        }
        
        // Checksum thread.json
        if (isset($this->results['steps']['thread_creation']['thread_id'])) {
            $threadId = $this->results['steps']['thread_creation']['thread_id'];
            $threadJsonPath = "channels/42/threads/{$threadId}/thread.json";
            $fullPath = $this->basePath . '/' . $threadJsonPath;
            
            if (file_exists($fullPath)) {
                $checksums[$threadJsonPath] = hash_file('sha256', $fullPath);
            }
        }
        
        return $checksums;
    }
    
    /**
     * Get list of deleted files from file safety checker
     * 
     * Extracts file paths from delete operations tracked by the
     * file safety checker.
     * 
     * @return array Array of deleted file paths
     */
    private function getDeletedFiles()
    {
        $deletedFiles = array();
        
        $deleteOps = $this->fileSafetyChecker->getDeleteOperations();
        
        foreach ($deleteOps as $op) {
            if (isset($op['file_path'])) {
                $deletedFiles[] = $op['file_path'];
            }
        }
        
        return $deletedFiles;
    }
}

