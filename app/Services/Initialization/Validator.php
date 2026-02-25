<?php
/**
 * Validator - Validates initialization workflow outputs
 * 
 * This class runs comprehensive validation checks to ensure the initialization
 * workflow completed successfully. It verifies doctrine ingestion, thread creation,
 * audit reports, summary messages, system logs, and file safety.
 * 
 * Validation Checks:
 * - At least 20 Channel 0 broadcasts were read
 * - Thread directory exists
 * - thread.json contains all required fields
 * - Audit report file exists
 * - Channel 42 summary is ≤1000 characters
 * - System log file exists
 * - No files were automatically deleted
 * 
 * Usage:
 *   $validator = new Validator($logger, '/path/to/lupopedia');
 *   $context = array(
 *       'doctrine_count' => 25,
 *       'thread_id' => 'DEVELOPMENT_CYCLE_4_0_44',
 *       'thread_metadata' => $metadata,
 *       'audit_report_path' => 'docs/status/kiro_status_directory_audit_4_0_44.md',
 *       'summary_path' => 'channels/42/threads/DEVELOPMENT_CYCLE_4_0_44/20260224153045_42_1001_initialization_summary.md',
 *       'log_path' => 'docs/status/kiro_4_0_44_cycle_initialization_log.md',
 *       'files_deleted' => array()
 *   );
 *   $summary = $validator->validateInitialization($context);
 * 
 * @package Lupopedia\Services\Initialization
 * @since 4.0.44
 */
class Validator implements ValidatorInterface
{
    /**
     * @var InitializationLoggerInterface Logger instance
     */
    private $logger;
    
    /**
     * @var string Base path to Lupopedia installation
     */
    private $basePath;
    
    /**
     * @var array Validation errors
     */
    private $errors;
    
    /**
     * @var bool Validation status
     */
    private $isValid;
    
    /**
     * Constructor
     * 
     * @param InitializationLoggerInterface $logger Logger instance
     * @param string $basePath Base path to Lupopedia installation
     */
    public function __construct(InitializationLoggerInterface $logger, $basePath)
    {
        $this->logger = $logger;
        $this->basePath = rtrim($basePath, '/\\');
        $this->errors = array();
        $this->isValid = false;
    }
    
    /**
     * Validate initialization workflow outputs
     * 
     * Runs all validation checks and generates a summary with pass/fail
     * status for each check.
     * 
     * @param array $context Validation context with paths and data to validate
     * @return array Validation summary with pass/fail status for each check
     * @throws ValidationException If critical validation fails
     */
    public function validateInitialization($context)
    {
        $this->logger->info('Starting validation checks');
        $this->errors = array();
        
        $checks = array();
        
        // Requirement 7.1: Verify at least 20 Channel 0 broadcasts were read
        $checks['doctrine_count'] = $this->validateDoctrineCount($context);
        
        // Requirement 7.2: Verify thread directory exists
        $checks['thread_directory'] = $this->validateThreadDirectory($context);
        
        // Requirement 7.3: Verify thread.json contains all required fields
        $checks['thread_metadata'] = $this->validateThreadMetadata($context);
        
        // Requirement 7.4: Verify audit report file exists
        $checks['audit_report'] = $this->validateAuditReport($context);
        
        // Requirement 7.5: Verify Channel 42 summary is ≤1000 characters
        $checks['summary_length'] = $this->validateSummaryLength($context);
        
        // Requirement 7.6: Verify system log file exists
        $checks['system_log'] = $this->validateSystemLog($context);
        
        // Requirement 7.7: Verify no files were automatically deleted
        $checks['file_safety'] = $this->validateFileSafety($context);
        
        // Determine overall validation status
        $this->isValid = $this->allChecksPassed($checks);
        
        if ($this->isValid) {
            $this->logger->info('All validation checks passed');
        } else {
            $this->logger->error('Validation failed', array('errors' => $this->errors));
        }
        
        // Requirement 7.9: Generate validation summary with pass/fail status
        return array(
            'is_valid' => $this->isValid,
            'checks' => $checks,
            'errors' => $this->errors,
            'timestamp' => gmdate('YmdHis')
        );
    }
    
    /**
     * Check if validation passed
     * 
     * @return bool True if all checks passed, false otherwise
     */
    public function isValid()
    {
        return $this->isValid;
    }
    
    /**
     * Get validation errors
     * 
     * @return array Array of validation error messages
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * Validate doctrine count
     * 
     * Requirement 7.1: Verify at least 20 Channel 0 broadcasts were read
     * 
     * @param array $context Validation context
     * @return array Check result with status and message
     */
    private function validateDoctrineCount($context)
    {
        $count = isset($context['doctrine_count']) ? (int)$context['doctrine_count'] : 0;
        
        if ($count >= 20) {
            $this->logger->info('Doctrine count validation passed', array('count' => $count));
            return array(
                'status' => 'pass',
                'message' => 'At least 20 Channel 0 broadcasts were read (' . $count . ' found)',
                'requirement' => '7.1'
            );
        } else {
            $error = ErrorMessages::insufficientDoctrines($count, 20);
            $this->errors[] = $error;
            $this->logger->error($error);
            return array(
                'status' => 'fail',
                'message' => $error,
                'requirement' => '7.1'
            );
        }
    }
    
    /**
     * Validate thread directory exists
     * 
     * Requirement 7.2: Verify thread directory exists
     * 
     * @param array $context Validation context
     * @return array Check result with status and message
     */
    private function validateThreadDirectory($context)
    {
        $threadId = isset($context['thread_id']) ? $context['thread_id'] : '';
        
        if (empty($threadId)) {
            $error = ErrorMessages::validationFailed('thread_directory', 'Thread ID not provided in validation context');
            $this->errors[] = $error;
            $this->logger->error($error);
            return array(
                'status' => 'fail',
                'message' => $error,
                'requirement' => '7.2'
            );
        }
        
        $threadPath = $this->basePath . '/channels/42/threads/' . $threadId;
        
        if (is_dir($threadPath)) {
            $this->logger->info('Thread directory validation passed', array('path' => $threadPath));
            return array(
                'status' => 'pass',
                'message' => 'Thread directory exists: ' . $threadPath,
                'requirement' => '7.2'
            );
        } else {
            $error = ErrorMessages::validationFailed('thread_directory', 'Thread directory does not exist: ' . $threadPath);
            $this->errors[] = $error;
            $this->logger->error($error);
            return array(
                'status' => 'fail',
                'message' => $error,
                'requirement' => '7.2'
            );
        }
    }
    
    /**
     * Validate thread metadata
     * 
     * Requirement 7.3: Verify thread.json contains all required fields
     * 
     * @param array $context Validation context
     * @return array Check result with status and message
     */
    private function validateThreadMetadata($context)
    {
        $metadata = isset($context['thread_metadata']) ? $context['thread_metadata'] : array();
        
        $requiredFields = array(
            'thread_id',
            'title',
            'type',
            'priority',
            'visibility',
            'created_ymdhis',
            'created_by_actor_id',
            'channel_id'
        );
        
        $missingFields = array();
        foreach ($requiredFields as $field) {
            if (!isset($metadata[$field]) || $metadata[$field] === '') {
                $missingFields[] = $field;
            }
        }
        
        if (empty($missingFields)) {
            $this->logger->info('Thread metadata validation passed');
            return array(
                'status' => 'pass',
                'message' => 'thread.json contains all required fields',
                'requirement' => '7.3'
            );
        } else {
            $error = ErrorMessages::validationFailed('thread_metadata', 'thread.json missing required fields: ' . implode(', ', $missingFields));
            $this->errors[] = $error;
            $this->logger->error($error);
            return array(
                'status' => 'fail',
                'message' => $error,
                'requirement' => '7.3'
            );
        }
    }
    
    /**
     * Validate audit report exists
     * 
     * Requirement 7.4: Verify audit report file exists
     * 
     * @param array $context Validation context
     * @return array Check result with status and message
     */
    private function validateAuditReport($context)
    {
        $reportPath = isset($context['audit_report_path']) ? $context['audit_report_path'] : '';
        
        if (empty($reportPath)) {
            $error = 'Audit report path not provided in validation context';
            $this->errors[] = $error;
            $this->logger->error($error);
            return array(
                'status' => 'fail',
                'message' => $error,
                'requirement' => '7.4'
            );
        }
        
        $fullPath = $this->basePath . '/' . $reportPath;
        
        if (file_exists($fullPath) && is_file($fullPath)) {
            $this->logger->info('Audit report validation passed', array('path' => $fullPath));
            return array(
                'status' => 'pass',
                'message' => 'Audit report file exists: ' . $reportPath,
                'requirement' => '7.4'
            );
        } else {
            $error = 'Audit report file does not exist: ' . $reportPath;
            $this->errors[] = $error;
            $this->logger->error($error);
            return array(
                'status' => 'fail',
                'message' => $error,
                'requirement' => '7.4'
            );
        }
    }
    
    /**
     * Validate summary length
     * 
     * Requirement 7.5: Verify Channel 42 summary is ≤1000 characters
     * 
     * @param array $context Validation context
     * @return array Check result with status and message
     */
    private function validateSummaryLength($context)
    {
        $summaryPath = isset($context['summary_path']) ? $context['summary_path'] : '';
        
        if (empty($summaryPath)) {
            $error = 'Summary path not provided in validation context';
            $this->errors[] = $error;
            $this->logger->error($error);
            return array(
                'status' => 'fail',
                'message' => $error,
                'requirement' => '7.5'
            );
        }
        
        $fullPath = $this->basePath . '/' . $summaryPath;
        
        if (!file_exists($fullPath)) {
            $error = 'Summary file does not exist: ' . $summaryPath;
            $this->errors[] = $error;
            $this->logger->error($error);
            return array(
                'status' => 'fail',
                'message' => $error,
                'requirement' => '7.5'
            );
        }
        
        $content = @file_get_contents($fullPath);
        if ($content === false) {
            $error = 'Failed to read summary file: ' . $summaryPath;
            $this->errors[] = $error;
            $this->logger->error($error);
            return array(
                'status' => 'fail',
                'message' => $error,
                'requirement' => '7.5'
            );
        }
        
        // Extract content after FLIP header (between --- markers)
        $parts = preg_split('/^---$/m', $content, 3);
        $messageContent = isset($parts[2]) ? trim($parts[2]) : $content;
        
        $length = strlen($messageContent);
        
        if ($length <= 1000) {
            $this->logger->info('Summary length validation passed', array('length' => $length));
            return array(
                'status' => 'pass',
                'message' => 'Channel 42 summary is ' . $length . ' characters (≤1000)',
                'requirement' => '7.5'
            );
        } else {
            $error = 'Channel 42 summary exceeds 1000 characters: ' . $length . ' characters';
            $this->errors[] = $error;
            $this->logger->error($error);
            return array(
                'status' => 'fail',
                'message' => $error,
                'requirement' => '7.5'
            );
        }
    }
    
    /**
     * Validate system log exists
     * 
     * Requirement 7.6: Verify system log file exists
     * 
     * @param array $context Validation context
     * @return array Check result with status and message
     */
    private function validateSystemLog($context)
    {
        $logPath = isset($context['log_path']) ? $context['log_path'] : '';
        
        if (empty($logPath)) {
            $error = 'System log path not provided in validation context';
            $this->errors[] = $error;
            $this->logger->error($error);
            return array(
                'status' => 'fail',
                'message' => $error,
                'requirement' => '7.6'
            );
        }
        
        $fullPath = $this->basePath . '/' . $logPath;
        
        if (file_exists($fullPath) && is_file($fullPath)) {
            $this->logger->info('System log validation passed', array('path' => $fullPath));
            return array(
                'status' => 'pass',
                'message' => 'System log file exists: ' . $logPath,
                'requirement' => '7.6'
            );
        } else {
            $error = 'System log file does not exist: ' . $logPath;
            $this->errors[] = $error;
            $this->logger->error($error);
            return array(
                'status' => 'fail',
                'message' => $error,
                'requirement' => '7.6'
            );
        }
    }
    
    /**
     * Validate file safety
     * 
     * Requirement 7.7: Verify no files were automatically deleted
     * 
     * @param array $context Validation context
     * @return array Check result with status and message
     */
    private function validateFileSafety($context)
    {
        $filesDeleted = isset($context['files_deleted']) ? $context['files_deleted'] : array();
        
        if (empty($filesDeleted)) {
            $this->logger->info('File safety validation passed');
            return array(
                'status' => 'pass',
                'message' => 'No files were automatically deleted',
                'requirement' => '7.7'
            );
        } else {
            $error = 'Files were automatically deleted: ' . implode(', ', $filesDeleted);
            $this->errors[] = $error;
            $this->logger->error($error);
            return array(
                'status' => 'fail',
                'message' => $error,
                'requirement' => '7.7'
            );
        }
    }
    
    /**
     * Check if all validation checks passed
     * 
     * @param array $checks Array of check results
     * @return bool True if all checks passed, false otherwise
     */
    private function allChecksPassed($checks)
    {
        foreach ($checks as $check) {
            if ($check['status'] !== 'pass') {
                return false;
            }
        }
        return true;
    }
}
