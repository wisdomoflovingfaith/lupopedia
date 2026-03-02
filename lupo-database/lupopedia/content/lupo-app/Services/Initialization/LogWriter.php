<?php
/**
 * LogWriter - Creates detailed system initialization logs
 * 
 * Generates comprehensive log files documenting all initialization activities
 * with complete audit trail. Logs include FLIP headers, timestamps, activity
 * listings, anomalies, and SHA-256 checksums for critical files.
 * 
 * Usage:
 *   $writer = new LogWriter($timestampHelper, $logger);
 *   $logPath = $writer->writeLog(
 *       'docs/status/kiro_4_0_44_cycle_initialization_log.md',
 *       $startTime,
 *       $endTime,
 *       $channelsScanned,
 *       $threadsCreated,
 *       $doctrinesLoaded,
 *       $filesAudited,
 *       $anomalies,
 *       $checksums
 *   );
 * 
 * @package Lupopedia\Services\Initialization
 * @since 4.0.44
 */
class LogWriter implements LogWriterInterface
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
     * Write detailed system initialization log
     * 
     * Creates a comprehensive log file with FLIP header, timestamps,
     * activity listings, anomalies, and checksums.
     * 
     * @param string $outputPath Path where log should be written
     * @param string $startTime Initialization start timestamp (YYYYMMDDHHMMSS)
     * @param string $endTime Initialization end timestamp (YYYYMMDDHHMMSS)
     * @param array $channelsScanned Array of channel scan data
     * @param array $threadsCreated Array of thread creation data
     * @param array $doctrinesLoaded Array of doctrine metadata
     * @param array $filesAudited Array of file audit data
     * @param array $anomalies Array of anomaly descriptions
     * @param array $checksums Array of file checksums
     * @return string Path to created log file
     * @throws LogWriterException If log writing fails
     */
    public function writeLog($outputPath, $startTime, $endTime, $channelsScanned, $threadsCreated, $doctrinesLoaded, $filesAudited, $anomalies, $checksums)
    {
        $this->logger->info(
            "Starting system log generation",
            array('output_path' => $outputPath)
        );
        
        try {
            // Validate timestamps
            if (!$this->timestampHelper->isValidTimestamp($startTime)) {
                throw new LogWriterException(
                    ErrorMessages::invalidTimestamp($startTime)
                );
            }
            if (!$this->timestampHelper->isValidTimestamp($endTime)) {
                throw new LogWriterException(
                    ErrorMessages::invalidTimestamp($endTime)
                );
            }
            
            // Build log content
            $content = $this->buildLogContent(
                $startTime,
                $endTime,
                $channelsScanned,
                $threadsCreated,
                $doctrinesLoaded,
                $filesAudited,
                $anomalies,
                $checksums
            );
            
            // Ensure output directory exists
            $this->ensureDirectoryExists(dirname($outputPath));
            
            // Write log to file
            $result = @file_put_contents($outputPath, $content);
            if ($result === false) {
                throw new LogWriterException(
                    ErrorMessages::fileWriteFailed($outputPath, 'LogWriter')
                );
            }
            
            $this->logger->info(
                "System log generated successfully",
                array(
                    'output_path' => $outputPath,
                    'size_bytes' => strlen($content)
                )
            );
            
            return $outputPath;
            
        } catch (LogWriterException $e) {
            $this->logger->error(
                "Log writing failed",
                array('error' => $e->getMessage())
            );
            throw $e;
        } catch (Exception $e) {
            $this->logger->error(
                "Unexpected error during log writing",
                array('error' => $e->getMessage())
            );
            throw new LogWriterException(
                ErrorMessages::genericError('LogWriter', 'Log writing', $e->getMessage())
            );
        }
    }
    
    /**
     * Build complete log content
     * 
     * Assembles all log sections into a single Markdown document.
     * 
     * @param string $startTime Initialization start timestamp
     * @param string $endTime Initialization end timestamp
     * @param array $channelsScanned Array of channel scan data
     * @param array $threadsCreated Array of thread creation data
     * @param array $doctrinesLoaded Array of doctrine metadata
     * @param array $filesAudited Array of file audit data
     * @param array $anomalies Array of anomaly descriptions
     * @param array $checksums Array of file checksums
     * @return string Complete log content
     */
    private function buildLogContent($startTime, $endTime, $channelsScanned, $threadsCreated, $doctrinesLoaded, $filesAudited, $anomalies, $checksums)
    {
        $sections = array();
        
        // FLIP Header
        $sections[] = $this->buildFLIPHeader($startTime, $endTime);
        
        // Title
        $sections[] = "# System Initialization Log — Version 4.0.44\n";
        
        // Overview
        $sections[] = $this->buildOverview($startTime, $endTime);
        
        // Channels Scanned
        $sections[] = $this->buildChannelsSection($channelsScanned);
        
        // Threads Created
        $sections[] = $this->buildThreadsSection($threadsCreated);
        
        // Doctrines Loaded
        $sections[] = $this->buildDoctrinesSection($doctrinesLoaded);
        
        // Status Files Audited
        $sections[] = $this->buildFilesAuditedSection($filesAudited);
        
        // Anomalies
        $sections[] = $this->buildAnomaliesSection($anomalies);
        
        // Checksums
        $sections[] = $this->buildChecksumsSection($checksums);
        
        // Footer
        $sections[] = $this->buildFooter($endTime);
        
        return implode("\n", $sections);
    }
    
    /**
     * Build FLIP header
     * 
     * Creates YAML front-matter with actor_id 1001, system_version 4.0.44,
     * artifact_kind log, and initialization timestamps.
     * 
     * @param string $startTime Initialization start timestamp
     * @param string $endTime Initialization end timestamp
     * @return string FLIP header YAML block
     */
    private function buildFLIPHeader($startTime, $endTime)
    {
        $header = array(
            "---",
            "flip.header: {",
            "  file_path_from_root: \"docs/status/kiro_4_0_44_cycle_initialization_log.md\",",
            "  actor_id: 1001,",
            "  system_version: \"4.0.44\",",
            "  created_ymdhis: {$endTime},",
            "  last_modified_utc: {$endTime},",
            "  artifact_kind: \"log\",",
            "  message_type: \"documentation\",",
            "  visibility: \"system\",",
            "  priority: \"high\",",
            "  initialization_start_ymdhis: {$startTime},",
            "  initialization_end_ymdhis: {$endTime}",
            "}",
            "---",
            ""
        );
        
        return implode("\n", $header);
    }
    
    /**
     * Build overview section
     * 
     * Summarizes initialization workflow execution with timestamps and duration.
     * 
     * @param string $startTime Initialization start timestamp
     * @param string $endTime Initialization end timestamp
     * @return string Overview section
     */
    private function buildOverview($startTime, $endTime)
    {
        $startDisplay = $this->timestampHelper->formatForDisplay($startTime);
        $endDisplay = $this->timestampHelper->formatForDisplay($endTime);
        
        // Calculate duration
        $duration = $this->calculateDuration($startTime, $endTime);
        
        $overview = array(
            "## Overview\n",
            "**Initialization Start:** {$startDisplay}",
            "**Initialization End:** {$endDisplay}",
            "**Duration:** {$duration}",
            "**Executed By:** KIRO (Actor ID 1001)",
            "**System Version:** 4.0.44\n",
            "This log documents the complete initialization workflow for the Lupopedia 4.0.44 development cycle. The workflow includes doctrine ingestion from Channel 0, development thread creation in Channel 42, status directory auditing, and comprehensive reporting.\n"
        );
        
        return implode("\n", $overview);
    }
    
    /**
     * Build channels scanned section
     * 
     * Lists all channels scanned with file counts.
     * 
     * @param array $channelsScanned Array of channel scan data
     * @return string Channels section
     */
    private function buildChannelsSection($channelsScanned)
    {
        $section = array(
            "## Channels Scanned\n"
        );
        
        if (empty($channelsScanned)) {
            $section[] = "*No channels were scanned during this initialization.*\n";
        } else {
            $section[] = "| Channel ID | Channel Name | Files Found | Status |";
            $section[] = "|------------|--------------|-------------|--------|";
            
            foreach ($channelsScanned as $channel) {
                $channelId = isset($channel['channel_id']) ? $channel['channel_id'] : 'Unknown';
                $channelName = isset($channel['channel_name']) ? $this->escapeMarkdown($channel['channel_name']) : 'Unknown';
                $fileCount = isset($channel['file_count']) ? $channel['file_count'] : 0;
                $status = isset($channel['status']) ? $this->escapeMarkdown($channel['status']) : 'Unknown';
                
                $section[] = "| {$channelId} | {$channelName} | {$fileCount} | {$status} |";
            }
            
            $section[] = "";
        }
        
        return implode("\n", $section);
    }
    
    /**
     * Build threads created section
     * 
     * Lists all threads created with thread_id and title.
     * 
     * @param array $threadsCreated Array of thread creation data
     * @return string Threads section
     */
    private function buildThreadsSection($threadsCreated)
    {
        $section = array(
            "## Threads Created\n"
        );
        
        if (empty($threadsCreated)) {
            $section[] = "*No threads were created during this initialization.*\n";
        } else {
            $section[] = "| Thread ID | Title | Channel | Status |";
            $section[] = "|-----------|-------|---------|--------|";
            
            foreach ($threadsCreated as $thread) {
                $threadId = isset($thread['thread_id']) ? $this->escapeMarkdown($thread['thread_id']) : 'Unknown';
                $title = isset($thread['title']) ? $this->escapeMarkdown($thread['title']) : 'Unknown';
                $channelId = isset($thread['channel_id']) ? $thread['channel_id'] : 'Unknown';
                $status = isset($thread['status']) ? $this->escapeMarkdown($thread['status']) : 'Unknown';
                
                $section[] = "| {$threadId} | {$title} | {$channelId} | {$status} |";
            }
            
            $section[] = "";
        }
        
        return implode("\n", $section);
    }
    
    /**
     * Build doctrines loaded section
     * 
     * Lists all doctrines loaded with doctrine_number and title.
     * 
     * @param array $doctrinesLoaded Array of doctrine metadata
     * @return string Doctrines section
     */
    private function buildDoctrinesSection($doctrinesLoaded)
    {
        $section = array(
            "## Doctrines Loaded\n"
        );
        
        if (empty($doctrinesLoaded)) {
            $section[] = "*No doctrines were loaded during this initialization.*\n";
        } else {
            $totalDoctrines = count($doctrinesLoaded);
            $section[] = "**Total Doctrines Loaded:** {$totalDoctrines}\n";
            $section[] = "| Doctrine Number | Title | System Version | Enforcement Scope |";
            $section[] = "|-----------------|-------|----------------|-------------------|";
            
            foreach ($doctrinesLoaded as $doctrine) {
                $doctrineNumber = isset($doctrine['doctrine_number']) ? $this->escapeMarkdown($doctrine['doctrine_number']) : 'Unknown';
                $title = isset($doctrine['title']) ? $this->escapeMarkdown($doctrine['title']) : 'Unknown';
                $systemVersion = isset($doctrine['system_version']) ? $this->escapeMarkdown($doctrine['system_version']) : 'Unknown';
                $enforcementScope = isset($doctrine['enforcement_scope']) ? $this->escapeMarkdown($doctrine['enforcement_scope']) : 'Unknown';
                
                $section[] = "| {$doctrineNumber} | {$title} | {$systemVersion} | {$enforcementScope} |";
            }
            
            $section[] = "";
        }
        
        return implode("\n", $section);
    }
    
    /**
     * Build status files audited section
     * 
     * Lists all status files audited with disposition.
     * 
     * @param array $filesAudited Array of file audit data
     * @return string Files audited section
     */
    private function buildFilesAuditedSection($filesAudited)
    {
        $section = array(
            "## Status Files Audited\n"
        );
        
        if (empty($filesAudited)) {
            $section[] = "*No status files were audited during this initialization.*\n";
        } else {
            $totalFiles = count($filesAudited);
            
            // Count dispositions
            $dispositionCounts = array(
                'retain' => 0,
                'archive' => 0,
                'deprecate' => 0
            );
            
            foreach ($filesAudited as $file) {
                $disposition = isset($file['disposition']) ? $file['disposition'] : 'unknown';
                if (isset($dispositionCounts[$disposition])) {
                    $dispositionCounts[$disposition]++;
                }
            }
            
            $section[] = "**Total Files Audited:** {$totalFiles}";
            $section[] = "**Retain:** {$dispositionCounts['retain']} files";
            $section[] = "**Archive:** {$dispositionCounts['archive']} files";
            $section[] = "**Deprecate:** {$dispositionCounts['deprecate']} files\n";
            $section[] = "| Filename | Version | Disposition |";
            $section[] = "|----------|---------|-------------|";
            
            foreach ($filesAudited as $file) {
                $filename = isset($file['filename']) ? $this->escapeMarkdown($file['filename']) : 'Unknown';
                $version = isset($file['version']) && $file['version'] !== null ? $this->escapeMarkdown($file['version']) : 'Unknown';
                $disposition = isset($file['disposition']) ? ucfirst($file['disposition']) : 'Unknown';
                
                $section[] = "| {$filename} | {$version} | {$disposition} |";
            }
            
            $section[] = "";
        }
        
        return implode("\n", $section);
    }
    
    /**
     * Build anomalies section
     * 
     * Documents any anomalies encountered during initialization.
     * 
     * @param array $anomalies Array of anomaly descriptions
     * @return string Anomalies section
     */
    private function buildAnomaliesSection($anomalies)
    {
        $section = array(
            "## Anomalies Encountered\n"
        );
        
        if (empty($anomalies)) {
            $section[] = "*No anomalies were encountered during this initialization.*\n";
        } else {
            $totalAnomalies = count($anomalies);
            $section[] = "**Total Anomalies:** {$totalAnomalies}\n";
            
            foreach ($anomalies as $index => $anomaly) {
                $anomalyNumber = $index + 1;
                
                if (is_array($anomaly)) {
                    $type = isset($anomaly['type']) ? $anomaly['type'] : 'Unknown';
                    $description = isset($anomaly['description']) ? $anomaly['description'] : 'No description';
                    $severity = isset($anomaly['severity']) ? $anomaly['severity'] : 'Unknown';
                    
                    $section[] = "### Anomaly {$anomalyNumber}: {$type}\n";
                    $section[] = "**Severity:** {$severity}";
                    $section[] = "**Description:** {$description}\n";
                } else {
                    $section[] = "### Anomaly {$anomalyNumber}\n";
                    $section[] = $anomaly . "\n";
                }
            }
        }
        
        return implode("\n", $section);
    }
    
    /**
     * Build checksums section
     * 
     * Lists SHA-256 checksums for critical files created during initialization.
     * 
     * @param array $checksums Array of file checksums
     * @return string Checksums section
     */
    private function buildChecksumsSection($checksums)
    {
        $section = array(
            "## File Checksums (SHA-256)\n"
        );
        
        if (empty($checksums)) {
            $section[] = "*No checksums were generated during this initialization.*\n";
        } else {
            $section[] = "SHA-256 checksums for critical files created during initialization:\n";
            $section[] = "| File Path | SHA-256 Checksum |";
            $section[] = "|-----------|------------------|";
            
            foreach ($checksums as $filePath => $checksum) {
                $escapedPath = $this->escapeMarkdown($filePath);
                $escapedChecksum = $this->escapeMarkdown($checksum);
                
                $section[] = "| {$escapedPath} | {$escapedChecksum} |";
            }
            
            $section[] = "";
        }
        
        return implode("\n", $section);
    }
    
    /**
     * Build log footer
     * 
     * Adds metadata and signature to log.
     * 
     * @param string $endTime Log generation timestamp
     * @return string Footer section
     */
    private function buildFooter($endTime)
    {
        $displayTime = $this->timestampHelper->formatForDisplay($endTime);
        
        $footer = array(
            "---\n",
            "**Log Generated:** {$displayTime}",
            "**Generated By:** KIRO Initialization System (Actor ID 1001)",
            "**System Version:** 4.0.44",
            "**Log Type:** System Initialization Log\n",
            "*This log was automatically generated by the Lupopedia initialization workflow.*",
            "*All operations documented in this log have been completed successfully.*"
        );
        
        return implode("\n", $footer);
    }
    
    /**
     * Calculate duration between two timestamps
     * 
     * @param string $startTime Start timestamp in YYYYMMDDHHMMSS format
     * @param string $endTime End timestamp in YYYYMMDDHHMMSS format
     * @return string Human-readable duration
     */
    private function calculateDuration($startTime, $endTime)
    {
        // Convert timestamps to Unix timestamps for calculation
        $startUnix = $this->timestampToUnix($startTime);
        $endUnix = $this->timestampToUnix($endTime);
        
        $durationSeconds = $endUnix - $startUnix;
        
        if ($durationSeconds < 0) {
            return "Invalid duration (end before start)";
        }
        
        $hours = floor($durationSeconds / 3600);
        $minutes = floor(($durationSeconds % 3600) / 60);
        $seconds = $durationSeconds % 60;
        
        $parts = array();
        if ($hours > 0) {
            $parts[] = "{$hours} hour" . ($hours !== 1 ? 's' : '');
        }
        if ($minutes > 0) {
            $parts[] = "{$minutes} minute" . ($minutes !== 1 ? 's' : '');
        }
        if ($seconds > 0 || empty($parts)) {
            $parts[] = "{$seconds} second" . ($seconds !== 1 ? 's' : '');
        }
        
        return implode(', ', $parts);
    }
    
    /**
     * Convert YYYYMMDDHHMMSS timestamp to Unix timestamp
     * 
     * @param string $timestamp Timestamp in YYYYMMDDHHMMSS format
     * @return int Unix timestamp
     */
    private function timestampToUnix($timestamp)
    {
        $year = (int) substr($timestamp, 0, 4);
        $month = (int) substr($timestamp, 4, 2);
        $day = (int) substr($timestamp, 6, 2);
        $hour = (int) substr($timestamp, 8, 2);
        $minute = (int) substr($timestamp, 10, 2);
        $second = (int) substr($timestamp, 12, 2);
        
        return gmmktime($hour, $minute, $second, $month, $day, $year);
    }
    
    /**
     * Escape Markdown special characters
     * 
     * Escapes pipe characters and other Markdown syntax that could
     * break table formatting.
     * 
     * @param string $text Text to escape
     * @return string Escaped text
     */
    private function escapeMarkdown($text)
    {
        if ($text === null) {
            return '';
        }
        
        // Escape pipe characters for table cells
        $text = str_replace('|', '\\|', $text);
        
        // Escape newlines (replace with space)
        $text = str_replace(array("\r\n", "\r", "\n"), ' ', $text);
        
        return $text;
    }
    
    /**
     * Ensure directory exists
     * 
     * Creates directory if it doesn't exist, including parent directories.
     * 
     * @param string $directory Directory path
     * @return void
     * @throws LogWriterException If directory cannot be created
     */
    private function ensureDirectoryExists($directory)
    {
        if (is_dir($directory)) {
            return;
        }
        
        $result = @mkdir($directory, 0755, true);
        if (!$result) {
            throw new LogWriterException(
                ErrorMessages::directoryCreationFailed($directory, 'LogWriter')
            );
        }
    }
}
