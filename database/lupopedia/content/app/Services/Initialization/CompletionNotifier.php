<?php
/**
 * CompletionNotifier - Posts completion or failure notifications to Channel 42
 * 
 * Creates notification message files in Channel 42 threads to communicate
 * initialization workflow completion status. Posts success notifications when
 * validation passes, or failure notifications when validation fails. Messages
 * include FLIP headers with proper metadata and reference audit reports and
 * system logs.
 * 
 * Usage:
 *   $notifier = new CompletionNotifier($timestampHelper, $logger);
 *   $messagePath = $notifier->postCompletion(
 *       'channels/42/threads/DEVELOPMENT_CYCLE_4_0_44',
 *       true,
 *       'docs/status/kiro_status_directory_audit_4_0_44.md',
 *       'docs/status/kiro_4_0_44_cycle_initialization_log.md',
 *       array()
 *   );
 * 
 * @package Lupopedia\Services\Initialization
 * @since 4.0.44
 */
class CompletionNotifier implements CompletionNotifierInterface
{
    /**
     * Timestamp helper instance
     * 
     * @var TimestampHelperInterface
     */
    private $timestampHelper;
    
    /**
     * Logger instance
     * 
     * @var InitializationLoggerInterface
     */
    private $logger;
    
    /**
     * Constructor
     * 
     * @param TimestampHelperInterface $timestampHelper Timestamp helper
     * @param InitializationLoggerInterface $logger Logger instance
     */
    public function __construct(
        TimestampHelperInterface $timestampHelper,
        InitializationLoggerInterface $logger
    ) {
        $this->timestampHelper = $timestampHelper;
        $this->logger = $logger;
    }
    
    /**
     * Post completion notification to Channel 42
     * 
     * Creates a completion message if validation passed, or a failure
     * notification if validation failed.
     * 
     * @param string $threadPath Path to thread directory
     * @param bool $success True if initialization succeeded, false otherwise
     * @param string $auditReportPath Path to audit report file
     * @param string $systemLogPath Path to system log file
     * @param array $validationErrors Array of validation errors (if failed)
     * @return string Path to created notification file
     * @throws InitializationException If notification posting fails
     */
    public function postCompletion($threadPath, $success, $auditReportPath, $systemLogPath, $validationErrors)
    {
        $this->logger->info(
            "Starting completion notification post to Channel 42",
            array(
                'thread_path' => $threadPath,
                'success' => $success
            )
        );
        
        try {
            // Get current timestamp for message
            $timestamp = $this->timestampHelper->getCurrentUTC();
            
            // Generate message filename
            $filename = $this->generateFilename($timestamp);
            $messagePath = rtrim($threadPath, '/') . '/' . $filename;
            
            // Build message content based on success/failure
            if ($success) {
                $content = $this->buildSuccessMessage(
                    $timestamp,
                    $auditReportPath,
                    $systemLogPath
                );
            } else {
                $content = $this->buildFailureMessage(
                    $timestamp,
                    $auditReportPath,
                    $systemLogPath,
                    $validationErrors
                );
            }
            
            // Ensure thread directory exists
            $this->ensureDirectoryExists($threadPath);
            
            // Write message to file
            $result = @file_put_contents($messagePath, $content);
            if ($result === false) {
                throw new InitializationException(
                    ErrorMessages::fileWriteFailed($messagePath, 'CompletionNotifier')
                );
            }
            
            $this->logger->info(
                "Completion notification posted successfully to Channel 42",
                array(
                    'message_path' => $messagePath,
                    'size_bytes' => strlen($content),
                    'success' => $success
                )
            );
            
            return $messagePath;
            
        } catch (InitializationException $e) {
            $this->logger->error(
                "Completion notification posting failed",
                array('error' => $e->getMessage())
            );
            throw $e;
        } catch (Exception $e) {
            $this->logger->error(
                "Unexpected error during completion notification posting",
                array('error' => $e->getMessage())
            );
            throw new InitializationException(
                ErrorMessages::genericError('CompletionNotifier', 'Completion notification posting', $e->getMessage())
            );
        }
    }
    
    /**
     * Generate message filename
     * 
     * Creates filename following pattern:
     * YYYYMMDDHHMMSS_42_1001_initialization_complete.md
     * 
     * @param string $timestamp Timestamp in YYYYMMDDHHMMSS format
     * @return string Message filename
     */
    private function generateFilename($timestamp)
    {
        return "{$timestamp}_42_1001_initialization_complete.md";
    }
    
    /**
     * Build success message content
     * 
     * Creates completion message with FLIP header stating initialization
     * completed successfully, referencing audit report and system log,
     * confirming no files were deleted, and inviting team review.
     * 
     * @param string $timestamp Message timestamp
     * @param string $auditReportPath Path to audit report file
     * @param string $systemLogPath Path to system log file
     * @return string Complete message content
     */
    private function buildSuccessMessage($timestamp, $auditReportPath, $systemLogPath)
    {
        $sections = array();
        
        // FLIP Header
        $sections[] = $this->buildFLIPHeader($timestamp);
        
        // Message body
        $body = array();
        
        $body[] = "# 4.0.44 Development Cycle Initialization Complete\n";
        
        $body[] = "**Status:** SUCCESS\n";
        
        $body[] = "The 4.0.44 development cycle initialization has completed successfully. All workflow tasks executed without critical errors.\n";
        
        $body[] = "## Initialization Outcomes\n";
        
        $body[] = "- Channel 0 doctrine broadcasts ingested";
        $body[] = "- Development thread created in Channel 42";
        $body[] = "- Status directory audit completed";
        $body[] = "- Comprehensive audit report generated";
        $body[] = "- System initialization log created";
        $body[] = "- All validation checks passed\n";
        
        $body[] = "## Important Notes\n";
        
        $body[] = "**No files were deleted automatically during this initialization process.** All historical status files remain in place. The audit report provides recommendations for file disposition, but no automatic cleanup was performed.\n";
        
        $body[] = "## Generated Artifacts\n";
        
        $body[] = "- **Audit Report:** `{$auditReportPath}`";
        $body[] = "- **System Log:** `{$systemLogPath}`\n";
        
        $body[] = "## Next Steps\n";
        
        $body[] = "Team members are invited to review the audit report to understand the status directory evaluation and recommended file dispositions. The report includes:";
        $body[] = "- File disposition classifications (retain/archive/deprecate)";
        $body[] = "- Version-based rationale for each classification";
        $body[] = "- Risk assessment for deprecated files";
        $body[] = "- Recommended actions for each category\n";
        
        $body[] = "Development work for version 4.0.44 may now proceed.\n";
        
        $body[] = "---";
        $body[] = "*Posted by KIRO (Actor 1001) — " . $this->timestampHelper->formatForDisplay($timestamp) . "*";
        
        $sections[] = implode("\n", $body);
        
        return implode("\n", $sections);
    }
    
    /**
     * Build failure message content
     * 
     * Creates failure notification with FLIP header stating initialization
     * failed, listing validation errors, and providing guidance for resolution.
     * 
     * @param string $timestamp Message timestamp
     * @param string $auditReportPath Path to audit report file
     * @param string $systemLogPath Path to system log file
     * @param array $validationErrors Array of validation errors
     * @return string Complete message content
     */
    private function buildFailureMessage($timestamp, $auditReportPath, $systemLogPath, $validationErrors)
    {
        $sections = array();
        
        // FLIP Header
        $sections[] = $this->buildFLIPHeader($timestamp);
        
        // Message body
        $body = array();
        
        $body[] = "# 4.0.44 Development Cycle Initialization Failed\n";
        
        $body[] = "**Status:** FAILURE\n";
        
        $body[] = "The 4.0.44 development cycle initialization encountered validation errors and did not complete successfully.\n";
        
        $body[] = "## Validation Errors\n";
        
        if (empty($validationErrors)) {
            $body[] = "- Unknown validation failure (no specific errors reported)\n";
        } else {
            foreach ($validationErrors as $error) {
                $body[] = "- {$error}";
            }
            $body[] = "";
        }
        
        $body[] = "## Important Notes\n";
        
        $body[] = "**No files were deleted automatically during this initialization process.** All historical status files remain in place.\n";
        
        $body[] = "## Generated Artifacts\n";
        
        $body[] = "The following artifacts may have been created (check for existence):";
        $body[] = "- **Audit Report:** `{$auditReportPath}`";
        $body[] = "- **System Log:** `{$systemLogPath}`\n";
        
        $body[] = "## Recommended Actions\n";
        
        $body[] = "1. Review the system log for detailed error information";
        $body[] = "2. Address the validation errors listed above";
        $body[] = "3. Re-run the initialization workflow";
        $body[] = "4. Contact the development team if errors persist\n";
        
        $body[] = "---";
        $body[] = "*Posted by KIRO (Actor 1001) — " . $this->timestampHelper->formatForDisplay($timestamp) . "*";
        
        $sections[] = implode("\n", $body);
        
        return implode("\n", $sections);
    }
    
    /**
     * Build FLIP header
     * 
     * Creates YAML front-matter with actor_id 1001, channel_id 42,
     * and message_type notification.
     * 
     * @param string $timestamp Timestamp in YYYYMMDDHHMMSS format
     * @return string FLIP header YAML block
     */
    private function buildFLIPHeader($timestamp)
    {
        $filename = $this->generateFilename($timestamp);
        
        $header = array(
            "---",
            "flip.header: {",
            "  file_path_from_root: \"channels/42/threads/DEVELOPMENT_CYCLE_4_0_44/{$filename}\",",
            "  actor_id: 1001,",
            "  channel_id: 42,",
            "  system_version: \"4.0.44\",",
            "  created_ymdhis: {$timestamp},",
            "  message_type: \"notification\",",
            "  visibility: \"system\",",
            "  priority: \"high\"",
            "}",
            "---",
            ""
        );
        
        return implode("\n", $header);
    }
    
    /**
     * Ensure directory exists
     * 
     * Creates directory if it doesn't exist, including parent directories.
     * 
     * @param string $directory Directory path
     * @return void
     * @throws InitializationException If directory cannot be created
     */
    private function ensureDirectoryExists($directory)
    {
        if (is_dir($directory)) {
            return;
        }
        
        $result = @mkdir($directory, 0755, true);
        if (!$result) {
            throw new InitializationException(
                ErrorMessages::directoryCreationFailed($directory, 'CompletionNotifier')
            );
        }
    }
}
