<?php
/**
 * ErrorMessages - Centralized error message templates with remediation guidance
 * 
 * This class provides consistent, helpful error messages for all initialization
 * workflow components. Each error message includes:
 * - Clear description of what went wrong
 * - Specific context (file paths, values, etc.)
 * - Remediation steps to fix the issue
 * - Formatted output for CLI display
 * 
 * Usage:
 *   $msg = ErrorMessages::directoryNotFound('/path/to/dir');
 *   throw new InitializationException($msg);
 *   
 *   // Or for CLI output:
 *   echo ErrorMessages::formatForCLI($msg);
 * 
 * @package Lupopedia\Services\Initialization
 * @since 4.0.44
 */
class ErrorMessages
{
    /**
     * Directory not found error
     * 
     * @param string $path Directory path that was not found
     * @param string $component Component name (e.g., "DoctrineIngester")
     * @return string Formatted error message
     */
    public static function directoryNotFound($path, $component = 'Initialization')
    {
        return self::buildMessage(
            "{$component}: Directory not found",
            "The required directory does not exist: {$path}",
            array(
                "Verify the path is correct",
                "Check that Lupopedia is properly installed",
                "Ensure you're running the script from the correct location",
                "If this is a fresh install, run the installation wizard first"
            )
        );
    }
    
    /**
     * Directory not readable error
     * 
     * @param string $path Directory path that cannot be read
     * @param string $component Component name
     * @return string Formatted error message
     */
    public static function directoryNotReadable($path, $component = 'Initialization')
    {
        return self::buildMessage(
            "{$component}: Directory not readable",
            "Permission denied when trying to read directory: {$path}",
            array(
                "Check directory permissions (should be readable by web server user)",
                "On Unix/Linux: chmod 755 {$path}",
                "On Windows: Right-click > Properties > Security > ensure Read permissions",
                "Verify the web server user has access to parent directories"
            )
        );
    }
    
    /**
     * File not found error
     * 
     * @param string $path File path that was not found
     * @param string $component Component name
     * @return string Formatted error message
     */
    public static function fileNotFound($path, $component = 'Initialization')
    {
        return self::buildMessage(
            "{$component}: File not found",
            "The required file does not exist: {$path}",
            array(
                "Verify the file path is correct",
                "Check that the file hasn't been moved or deleted",
                "If this is a Channel 0 broadcast, ensure doctrines are properly installed",
                "If this is a status file, it may have been archived or removed"
            )
        );
    }
    
    /**
     * File not readable error
     * 
     * @param string $path File path that cannot be read
     * @param string $component Component name
     * @return string Formatted error message
     */
    public static function fileNotReadable($path, $component = 'Initialization')
    {
        return self::buildMessage(
            "{$component}: File not readable",
            "Permission denied when trying to read file: {$path}",
            array(
                "Check file permissions (should be readable by web server user)",
                "On Unix/Linux: chmod 644 {$path}",
                "On Windows: Right-click > Properties > Security > ensure Read permissions",
                "Verify the file is not locked by another process"
            )
        );
    }
    
    /**
     * File read failed error
     * 
     * @param string $path File path that failed to read
     * @param string $component Component name
     * @return string Formatted error message
     */
    public static function fileReadFailed($path, $component = 'Initialization')
    {
        return self::buildMessage(
            "{$component}: File read failed",
            "Failed to read file content: {$path}",
            array(
                "Check that the file is not corrupted",
                "Verify sufficient disk space is available",
                "Ensure the file is not locked by another process",
                "Try reading the file manually to verify it's accessible",
                "Check system logs for I/O errors"
            )
        );
    }
    
    /**
     * File write failed error
     * 
     * @param string $path File path that failed to write
     * @param string $component Component name
     * @return string Formatted error message
     */
    public static function fileWriteFailed($path, $component = 'Initialization')
    {
        return self::buildMessage(
            "{$component}: File write failed",
            "Failed to write file: {$path}",
            array(
                "Check directory permissions (should be writable by web server user)",
                "Verify sufficient disk space is available",
                "Ensure the parent directory exists",
                "On Unix/Linux: chmod 755 on parent directory",
                "On Windows: Right-click parent directory > Properties > Security > ensure Write permissions"
            )
        );
    }
    
    /**
     * Directory creation failed error
     * 
     * @param string $path Directory path that failed to create
     * @param string $component Component name
     * @return string Formatted error message
     */
    public static function directoryCreationFailed($path, $component = 'Initialization')
    {
        return self::buildMessage(
            "{$component}: Directory creation failed",
            "Failed to create directory: {$path}",
            array(
                "Check parent directory permissions (should be writable)",
                "Verify sufficient disk space is available",
                "Ensure the parent directory exists",
                "On Unix/Linux: chmod 755 on parent directory",
                "Try creating the directory manually to test permissions"
            )
        );
    }
    
    /**
     * Thread already exists error
     * 
     * @param string $threadId Thread ID that already exists
     * @return string Formatted error message
     */
    public static function threadAlreadyExists($threadId)
    {
        return self::buildMessage(
            "ThreadCreator: Thread already exists",
            "A thread with this ID already exists: {$threadId}",
            array(
                "This is not necessarily an error - the thread may have been created previously",
                "Verify the existing thread has the correct structure",
                "If you need to recreate the thread, manually delete the existing directory first",
                "Check channels/42/threads/{$threadId}/ to inspect the existing thread"
            )
        );
    }
    
    /**
     * Invalid timestamp format error
     * 
     * @param string $timestamp Invalid timestamp value
     * @param string $expected Expected format description
     * @return string Formatted error message
     */
    public static function invalidTimestamp($timestamp, $expected = 'YYYYMMDDHHMMSS')
    {
        return self::buildMessage(
            "TimestampHelper: Invalid timestamp format",
            "Timestamp '{$timestamp}' does not match expected format: {$expected}",
            array(
                "Timestamps must be in UTC format: YYYYMMDDHHMMSS (e.g., 20260224153045)",
                "Ensure you're using gmdate('YmdHis') to generate timestamps",
                "Do not use epoch seconds, ISO8601, or DATETIME formats",
                "Verify the timestamp is exactly 14 digits"
            )
        );
    }
    
    /**
     * Insufficient doctrines loaded error
     * 
     * @param int $count Number of doctrines actually loaded
     * @param int $minimum Minimum required doctrines
     * @return string Formatted error message
     */
    public static function insufficientDoctrines($count, $minimum = 20)
    {
        return self::buildMessage(
            "Validator: Insufficient doctrines loaded",
            "Only {$count} doctrines were loaded, but at least {$minimum} are required",
            array(
                "Check that channels/0/broadcasts/ directory contains doctrine files",
                "Verify doctrine files have .md extension",
                "Review initialization log for doctrine parsing errors",
                "Ensure Channel 0 broadcasts are properly installed",
                "Check that doctrine files have valid FLIP headers"
            )
        );
    }
    
    /**
     * Missing FLIP header warning
     * 
     * @param string $path File path with missing header
     * @return string Formatted warning message
     */
    public static function missingFLIPHeader($path)
    {
        return self::buildMessage(
            "FLIPHeaderParser: Missing FLIP header",
            "File lacks a valid FLIP header: {$path}",
            array(
                "This is a warning - processing will continue with default values",
                "Consider adding a FLIP header to this file for better metadata tracking",
                "FLIP headers should be YAML blocks at the start of the file",
                "See existing files for FLIP header examples"
            ),
            'WARNING'
        );
    }
    
    /**
     * Malformed FLIP header warning
     * 
     * @param string $path File path with malformed header
     * @param string $error YAML parsing error
     * @return string Formatted warning message
     */
    public static function malformedFLIPHeader($path, $error)
    {
        return self::buildMessage(
            "FLIPHeaderParser: Malformed FLIP header",
            "FLIP header in file {$path} has invalid YAML syntax: {$error}",
            array(
                "This is a warning - processing will continue with default values",
                "Check YAML syntax in the FLIP header block",
                "Ensure proper indentation (use spaces, not tabs)",
                "Verify the header is enclosed in --- delimiters",
                "Use a YAML validator to check syntax"
            ),
            'WARNING'
        );
    }
    
    /**
     * Directory scan failed error
     * 
     * @param string $path Directory that failed to scan
     * @param string $error Underlying error message
     * @param string $component Component name
     * @return string Formatted error message
     */
    public static function directoryScanFailed($path, $error, $component = 'Initialization')
    {
        return self::buildMessage(
            "{$component}: Directory scan failed",
            "Failed to scan directory {$path}: {$error}",
            array(
                "Check directory permissions and accessibility",
                "Verify the directory structure is not corrupted",
                "Ensure no symbolic links are broken",
                "Check system logs for filesystem errors",
                "Try listing directory contents manually to verify access"
            )
        );
    }
    
    /**
     * Validation check failed error
     * 
     * @param string $checkName Name of the validation check
     * @param string $reason Reason for failure
     * @return string Formatted error message
     */
    public static function validationFailed($checkName, $reason)
    {
        return self::buildMessage(
            "Validator: Validation check failed",
            "Validation check '{$checkName}' failed: {$reason}",
            array(
                "Review the initialization log for details about this failure",
                "Check that all workflow steps completed successfully",
                "Verify all required files were created",
                "Re-run the initialization workflow if needed",
                "See docs/status/ for detailed audit reports"
            )
        );
    }
    
    /**
     * Summary message too long warning
     * 
     * @param int $length Actual message length
     * @param int $maxLength Maximum allowed length
     * @return string Formatted warning message
     */
    public static function summaryTooLong($length, $maxLength = 1000)
    {
        return self::buildMessage(
            "SummaryPoster: Summary message too long",
            "Summary message is {$length} characters (max {$maxLength}), will be truncated",
            array(
                "This is a warning - the message will be automatically truncated",
                "Full details are available in the audit report and system log",
                "The truncated message will include a reference to the full report",
                "No action required - this is normal for complex initialization results"
            ),
            'WARNING'
        );
    }
    
    /**
     * Generic initialization error
     * 
     * @param string $component Component name
     * @param string $operation Operation that failed
     * @param string $error Error details
     * @return string Formatted error message
     */
    public static function genericError($component, $operation, $error)
    {
        return self::buildMessage(
            "{$component}: Operation failed",
            "{$operation} failed: {$error}",
            array(
                "Review the error message for specific details",
                "Check the initialization log for more context",
                "Verify system requirements are met",
                "Ensure proper permissions on all directories",
                "Contact support if the issue persists"
            )
        );
    }
    
    /**
     * Build a formatted error message with remediation steps
     * 
     * @param string $title Error title
     * @param string $description Error description
     * @param array $remediationSteps Array of remediation step strings
     * @param string $level Error level (ERROR or WARNING)
     * @return string Formatted error message
     */
    private static function buildMessage($title, $description, $remediationSteps, $level = 'ERROR')
    {
        $message = "[{$level}] {$title}\n\n";
        $message .= "{$description}\n\n";
        
        if (!empty($remediationSteps)) {
            $message .= "Remediation steps:\n";
            foreach ($remediationSteps as $index => $step) {
                $stepNum = $index + 1;
                $message .= "  {$stepNum}. {$step}\n";
            }
        }
        
        return rtrim($message);
    }
    
    /**
     * Format error message for CLI output
     * 
     * Adds color codes and formatting for terminal display.
     * 
     * @param string $message Error message
     * @param bool $useColors Whether to use ANSI color codes
     * @return string Formatted message for CLI
     */
    public static function formatForCLI($message, $useColors = true)
    {
        if (!$useColors) {
            return $message;
        }
        
        // ANSI color codes
        $red = "\033[31m";
        $yellow = "\033[33m";
        $cyan = "\033[36m";
        $reset = "\033[0m";
        $bold = "\033[1m";
        
        // Color ERROR lines in red
        $message = preg_replace(
            '/\[ERROR\]/',
            $red . $bold . '[ERROR]' . $reset,
            $message
        );
        
        // Color WARNING lines in yellow
        $message = preg_replace(
            '/\[WARNING\]/',
            $yellow . $bold . '[WARNING]' . $reset,
            $message
        );
        
        // Color "Remediation steps:" in cyan
        $message = preg_replace(
            '/Remediation steps:/',
            $cyan . $bold . 'Remediation steps:' . $reset,
            $message
        );
        
        return $message;
    }
    
    /**
     * Extract just the error description (without remediation steps)
     * 
     * Useful for exception messages where full formatting isn't needed.
     * 
     * @param string $message Full error message
     * @return string Just the description part
     */
    public static function extractDescription($message)
    {
        // Split on "Remediation steps:" and take first part
        $parts = explode('Remediation steps:', $message);
        return trim($parts[0]);
    }
}
