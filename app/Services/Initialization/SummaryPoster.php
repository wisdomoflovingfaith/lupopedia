<?php
/**
 * SummaryPoster - Creates concise summary messages in Channel 42
 * 
 * Generates summary message files in Channel 42 threads to communicate
 * initialization outcomes. Messages are limited to 1000 characters and
 * include FLIP headers with proper metadata. Summaries cover doctrine
 * ingestion results, audit outcomes, risks, and next steps.
 * 
 * Usage:
 *   $poster = new SummaryPoster($timestampHelper, $logger);
 *   $messagePath = $poster->postSummary(
 *       'channels/42/threads/DEVELOPMENT_CYCLE_4_0_44',
 *       25,
 *       array('retain' => 10, 'archive' => 5, 'deprecate' => 3),
 *       array('No critical risks identified'),
 *       array('Review audit report', 'Begin development work')
 *   );
 * 
 * @package Lupopedia\Services\Initialization
 * @since 4.0.44
 */
class SummaryPoster implements SummaryPosterInterface
{
    /**
     * Maximum message content length in characters
     */
    const MAX_MESSAGE_LENGTH = 1000;
    
    /**
     * Truncation suffix when message exceeds max length
     */
    const TRUNCATION_SUFFIX = '... (see full report)';
    
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
     * Post initialization summary to Channel 42
     * 
     * Creates a message file in the specified thread with a concise summary
     * of initialization outcomes (max 1000 characters).
     * 
     * @param string $threadPath Path to thread directory
     * @param int $doctrineCount Number of doctrines ingested
     * @param array $dispositionCounts Disposition category counts
     * @param array $risks Array of critical risks or anomalies
     * @param array $nextSteps Array of recommended next steps
     * @return string Path to created message file
     * @throws InitializationException If posting fails
     */
    public function postSummary($threadPath, $doctrineCount, $dispositionCounts, $risks, $nextSteps)
    {
        $this->logger->info(
            "Starting summary post to Channel 42",
            array('thread_path' => $threadPath)
        );
        
        try {
            // Get current timestamp for message
            $timestamp = $this->timestampHelper->getCurrentUTC();
            
            // Generate message filename
            $filename = $this->generateFilename($timestamp);
            $messagePath = rtrim($threadPath, '/') . '/' . $filename;
            
            // Build message content
            $content = $this->buildMessageContent(
                $timestamp,
                $doctrineCount,
                $dispositionCounts,
                $risks,
                $nextSteps
            );
            
            // Ensure thread directory exists
            $this->ensureDirectoryExists($threadPath);
            
            // Write message to file
            $result = @file_put_contents($messagePath, $content);
            if ($result === false) {
                throw new InitializationException(
                    ErrorMessages::fileWriteFailed($messagePath, 'SummaryPoster')
                );
            }
            
            $this->logger->info(
                "Summary posted successfully to Channel 42",
                array(
                    'message_path' => $messagePath,
                    'size_bytes' => strlen($content)
                )
            );
            
            return $messagePath;
            
        } catch (InitializationException $e) {
            $this->logger->error(
                "Summary posting failed",
                array('error' => $e->getMessage())
            );
            throw $e;
        } catch (Exception $e) {
            $this->logger->error(
                "Unexpected error during summary posting",
                array('error' => $e->getMessage())
            );
            throw new InitializationException(
                ErrorMessages::genericError('SummaryPoster', 'Summary posting', $e->getMessage())
            );
        }
    }
    
    /**
     * Generate message filename
     * 
     * Creates filename following pattern:
     * YYYYMMDDHHMMSS_42_1001_initialization_summary.md
     * 
     * @param string $timestamp Timestamp in YYYYMMDDHHMMSS format
     * @return string Message filename
     */
    private function generateFilename($timestamp)
    {
        return "{$timestamp}_42_1001_initialization_summary.md";
    }
    
    /**
     * Build complete message content
     * 
     * Assembles FLIP header and message body, enforcing 1000 character limit.
     * 
     * @param string $timestamp Message timestamp
     * @param int $doctrineCount Number of doctrines ingested
     * @param array $dispositionCounts Disposition category counts
     * @param array $risks Array of critical risks or anomalies
     * @param array $nextSteps Array of recommended next steps
     * @return string Complete message content
     */
    private function buildMessageContent($timestamp, $doctrineCount, $dispositionCounts, $risks, $nextSteps)
    {
        $sections = array();
        
        // FLIP Header
        $sections[] = $this->buildFLIPHeader($timestamp);
        
        // Message body
        $messageBody = $this->buildMessageBody(
            $doctrineCount,
            $dispositionCounts,
            $risks,
            $nextSteps
        );
        
        // Enforce character limit on message body only (not including FLIP header)
        $messageBody = $this->enforceCharacterLimit($messageBody);
        
        $sections[] = $messageBody;
        
        return implode("\n", $sections);
    }
    
    /**
     * Build FLIP header
     * 
     * Creates YAML front-matter with actor_id 1001, channel_id 42,
     * and message_type post.
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
            "  message_type: \"post\",",
            "  visibility: \"system\",",
            "  priority: \"high\"",
            "}",
            "---",
            ""
        );
        
        return implode("\n", $header);
    }
    
    /**
     * Build message body
     * 
     * Creates concise summary of initialization outcomes.
     * 
     * @param int $doctrineCount Number of doctrines ingested
     * @param array $dispositionCounts Disposition category counts
     * @param array $risks Array of critical risks or anomalies
     * @param array $nextSteps Array of recommended next steps
     * @return string Message body content
     */
    private function buildMessageBody($doctrineCount, $dispositionCounts, $risks, $nextSteps)
    {
        $body = array();
        
        // Title
        $body[] = "# 4.0.44 Initialization Summary\n";
        
        // Doctrine ingestion results
        $body[] = "## Doctrine Ingestion";
        $body[] = "Successfully loaded **{$doctrineCount} doctrines** from Channel 0 broadcasts.\n";
        
        // Audit outcomes
        $body[] = "## Status Directory Audit";
        $retain = isset($dispositionCounts['retain']) ? $dispositionCounts['retain'] : 0;
        $archive = isset($dispositionCounts['archive']) ? $dispositionCounts['archive'] : 0;
        $deprecate = isset($dispositionCounts['deprecate']) ? $dispositionCounts['deprecate'] : 0;
        
        $body[] = "- **Retain:** {$retain} files (relevant for 4.0.44)";
        $body[] = "- **Archive:** {$archive} files (historical reference)";
        $body[] = "- **Deprecate:** {$deprecate} files (obsolete)\n";
        
        // Risks
        $body[] = "## Critical Risks";
        if (empty($risks)) {
            $body[] = "No critical risks identified.\n";
        } else {
            foreach ($risks as $risk) {
                $body[] = "- {$risk}";
            }
            $body[] = "";
        }
        
        // Next steps
        $body[] = "## Next Steps";
        if (empty($nextSteps)) {
            $body[] = "Review audit report and begin development work.\n";
        } else {
            foreach ($nextSteps as $step) {
                $body[] = "- {$step}";
            }
            $body[] = "";
        }
        
        // Footer
        $body[] = "---";
        $body[] = "*Posted by KIRO (Actor 1001) — See full audit report in docs/status/*";
        
        return implode("\n", $body);
    }
    
    /**
     * Enforce character limit on message body
     * 
     * Truncates message to 1000 characters if needed, appending
     * "... (see full report)" suffix.
     * 
     * @param string $messageBody Message body content
     * @return string Message body within character limit
     */
    private function enforceCharacterLimit($messageBody)
    {
        if (strlen($messageBody) <= self::MAX_MESSAGE_LENGTH) {
            return $messageBody;
        }
        
        // Calculate available space for content
        $availableLength = self::MAX_MESSAGE_LENGTH - strlen(self::TRUNCATION_SUFFIX);
        
        // Truncate and append suffix
        $truncated = substr($messageBody, 0, $availableLength);
        
        // Try to truncate at last complete word to avoid cutting mid-word
        $lastSpace = strrpos($truncated, ' ');
        if ($lastSpace !== false && $lastSpace > ($availableLength * 0.9)) {
            $truncated = substr($truncated, 0, $lastSpace);
        }
        
        $this->logger->warning(
            ErrorMessages::summaryTooLong(strlen($messageBody), self::MAX_MESSAGE_LENGTH),
            array(
                'original_length' => strlen($messageBody),
                'truncated_length' => strlen($truncated . self::TRUNCATION_SUFFIX)
            )
        );
        
        return $truncated . self::TRUNCATION_SUFFIX;
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
                ErrorMessages::directoryCreationFailed($directory, 'SummaryPoster')
            );
        }
    }
}
