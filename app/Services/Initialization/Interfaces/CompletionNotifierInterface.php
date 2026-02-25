<?php
/**
 * Interface for posting completion notifications to Channel 42
 * 
 * Defines the contract for creating completion or failure notification
 * messages in Channel 42 after initialization workflow completes.
 * 
 * @package Lupopedia\Services\Initialization\Interfaces
 * @since 4.0.44
 */
interface CompletionNotifierInterface
{
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
    public function postCompletion($threadPath, $success, $auditReportPath, $systemLogPath, $validationErrors);
}
