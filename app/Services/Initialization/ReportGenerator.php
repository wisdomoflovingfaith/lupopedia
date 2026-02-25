<?php
/**
 * ReportGenerator - Creates comprehensive Markdown audit reports
 * 
 * Generates detailed audit reports documenting status directory audit results.
 * Reports include FLIP headers, executive summaries, file disposition tables,
 * recommendations, and risk assessments. All reports are formatted as valid
 * Markdown for human readability.
 * 
 * Usage:
 *   $generator = new ReportGenerator($timestampHelper, $logger);
 *   $reportPath = $generator->generateAuditReport(
 *       $auditResults,
 *       $dispositionCounts,
 *       'docs/status/kiro_status_directory_audit_4_0_44.md'
 *   );
 * 
 * @package Lupopedia\Services\Initialization
 * @since 4.0.44
 */
class ReportGenerator implements ReportGeneratorInterface
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
     * Generate comprehensive audit report
     * 
     * Creates a Markdown report with FLIP header, executive summary,
     * file disposition table, recommendations, and risk assessment.
     * 
     * @param array $auditResults Array of file audit data
     * @param array $dispositionCounts Disposition category counts
     * @param string $outputPath Path where report should be written
     * @return string Path to generated report
     * @throws ReportGenerationException If report generation fails
     */
    public function generateAuditReport($auditResults, $dispositionCounts, $outputPath)
    {
        $this->logger->info(
            "Starting audit report generation",
            array('output_path' => $outputPath)
        );
        
        try {
            // Get current timestamp for report
            $auditTimestamp = $this->timestampHelper->getCurrentUTC();
            
            // Build report content
            $content = $this->buildReportContent(
                $auditResults,
                $dispositionCounts,
                $auditTimestamp
            );
            
            // Ensure output directory exists
            $this->ensureDirectoryExists(dirname($outputPath));
            
            // Write report to file
            $result = @file_put_contents($outputPath, $content);
            if ($result === false) {
                throw new ReportGenerationException(
                    ErrorMessages::fileWriteFailed($outputPath, 'ReportGenerator')
                );
            }
            
            $this->logger->info(
                "Audit report generated successfully",
                array(
                    'output_path' => $outputPath,
                    'size_bytes' => strlen($content)
                )
            );
            
            return $outputPath;
            
        } catch (ReportGenerationException $e) {
            $this->logger->error(
                "Report generation failed",
                array('error' => $e->getMessage())
            );
            throw $e;
        } catch (Exception $e) {
            $this->logger->error(
                "Unexpected error during report generation",
                array('error' => $e->getMessage())
            );
            throw new ReportGenerationException(
                ErrorMessages::genericError('ReportGenerator', 'Report generation', $e->getMessage())
            );
        }
    }

    /**
     * Build complete report content
     * 
     * Assembles all report sections into a single Markdown document.
     * 
     * @param array $auditResults Array of file audit data
     * @param array $dispositionCounts Disposition category counts
     * @param string $auditTimestamp Timestamp of audit in YYYYMMDDHHMMSS format
     * @return string Complete report content
     */
    private function buildReportContent($auditResults, $dispositionCounts, $auditTimestamp)
    {
        $sections = array();
        
        // FLIP Header
        $sections[] = $this->buildFLIPHeader($auditTimestamp);
        
        // Title
        $sections[] = "# Status Directory Audit Report — Version 4.0.44\n";
        
        // Executive Summary
        $sections[] = $this->buildExecutiveSummary($auditResults, $dispositionCounts, $auditTimestamp);
        
        // File Disposition Table
        $sections[] = $this->buildDispositionTable($auditResults);
        
        // Recommendations
        $sections[] = $this->buildRecommendations($dispositionCounts);
        
        // Risk Assessment
        $sections[] = $this->buildRiskAssessment($auditResults, $dispositionCounts);
        
        // Footer
        $sections[] = $this->buildFooter($auditTimestamp);
        
        return implode("\n", $sections);
    }
    
    /**
     * Build FLIP header
     * 
     * Creates YAML front-matter with actor_id 1001, system_version 4.0.44,
     * and current timestamp.
     * 
     * @param string $timestamp Timestamp in YYYYMMDDHHMMSS format
     * @return string FLIP header YAML block
     */
    private function buildFLIPHeader($timestamp)
    {
        $displayTime = $this->timestampHelper->formatForDisplay($timestamp);
        
        $header = array(
            "---",
            "flip.header: {",
            "  file_path_from_root: \"docs/status/kiro_status_directory_audit_4_0_44.md\",",
            "  actor_id: 1001,",
            "  system_version: \"4.0.44\",",
            "  created_ymdhis: {$timestamp},",
            "  last_modified_utc: {$timestamp},",
            "  artifact_kind: \"audit_report\",",
            "  message_type: \"documentation\",",
            "  visibility: \"system\",",
            "  priority: \"high\"",
            "}",
            "---",
            ""
        );
        
        return implode("\n", $header);
    }
    
    /**
     * Build executive summary section
     * 
     * Summarizes total files scanned and disposition counts.
     * 
     * @param array $auditResults Array of file audit data
     * @param array $dispositionCounts Disposition category counts
     * @param string $timestamp Audit timestamp
     * @return string Executive summary section
     */
    private function buildExecutiveSummary($auditResults, $dispositionCounts, $timestamp)
    {
        $totalFiles = count($auditResults);
        $displayTime = $this->timestampHelper->formatForDisplay($timestamp);
        
        $retain = isset($dispositionCounts['retain']) ? $dispositionCounts['retain'] : 0;
        $archive = isset($dispositionCounts['archive']) ? $dispositionCounts['archive'] : 0;
        $deprecate = isset($dispositionCounts['deprecate']) ? $dispositionCounts['deprecate'] : 0;
        
        $summary = array(
            "## Executive Summary\n",
            "**Audit Timestamp:** {$displayTime}\n",
            "**Audited By:** KIRO (Actor ID 1001)\n",
            "**Total Files Scanned:** {$totalFiles}\n",
            "### Disposition Summary\n",
            "- **Retain:** {$retain} files (relevant for 4.0.44 development)",
            "- **Archive:** {$archive} files (historical reference, move to archive/)",
            "- **Deprecate:** {$deprecate} files (obsolete, safe to remove)\n",
            "### Purpose\n",
            "This audit evaluates all status files in docs/status/ to determine their relevance for the 4.0.44 development cycle. Files are classified based on version metadata extracted from FLIP headers and content analysis.\n"
        );
        
        return implode("\n", $summary);
    }

    /**
     * Build file disposition table
     * 
     * Creates a Markdown table listing all files with their version,
     * disposition, and rationale.
     * 
     * @param array $auditResults Array of file audit data
     * @return string File disposition table section
     */
    private function buildDispositionTable($auditResults)
    {
        $table = array(
            "## File Disposition Table\n",
            "| Filename | Version | Disposition | Rationale |",
            "|----------|---------|-------------|-----------|"
        );
        
        // Sort results by disposition (retain, archive, deprecate) then filename
        $sortedResults = $this->sortAuditResults($auditResults);
        
        foreach ($sortedResults as $result) {
            $filename = $this->escapeMarkdown($result['filename']);
            $version = isset($result['version']) && $result['version'] !== null 
                ? $this->escapeMarkdown($result['version']) 
                : 'Unknown';
            $disposition = ucfirst($result['disposition']);
            $rationale = $this->escapeMarkdown($result['rationale']);
            
            $table[] = "| {$filename} | {$version} | {$disposition} | {$rationale} |";
        }
        
        $table[] = "";
        
        return implode("\n", $table);
    }
    
    /**
     * Build recommendations section
     * 
     * Provides specific actions for each disposition category.
     * 
     * @param array $dispositionCounts Disposition category counts
     * @return string Recommendations section
     */
    private function buildRecommendations($dispositionCounts)
    {
        $retain = isset($dispositionCounts['retain']) ? $dispositionCounts['retain'] : 0;
        $archive = isset($dispositionCounts['archive']) ? $dispositionCounts['archive'] : 0;
        $deprecate = isset($dispositionCounts['deprecate']) ? $dispositionCounts['deprecate'] : 0;
        
        $recommendations = array(
            "## Recommendations\n",
            "### Retain Files ({$retain} files)\n",
            "**Action:** No action required. These files are relevant for 4.0.44 development.",
            "- Keep in docs/status/ for active reference",
            "- Review during development cycle for accuracy",
            "- Update as needed during 4.0.44 work\n",
            "### Archive Files ({$archive} files)\n",
            "**Action:** Move to archive directory for historical reference.",
            "- Create docs/status/archive/ if it doesn't exist",
            "- Move archived files to docs/status/archive/",
            "- Preserve file metadata and timestamps",
            "- Update any references in active documentation\n",
            "### Deprecate Files ({$deprecate} files)\n",
            "**Action:** Review and remove obsolete files.",
            "- Verify files are truly obsolete before deletion",
            "- Check for any external references or dependencies",
            "- Create backup before deletion if uncertain",
            "- Document deletion in CHANGELOG.md\n",
            "### General Guidelines\n",
            "- Never delete files automatically without human review",
            "- Preserve FLIP headers when moving or archiving files",
            "- Update file paths in any cross-references",
            "- Maintain audit trail of all file operations\n"
        );
        
        return implode("\n", $recommendations);
    }

    /**
     * Build risk assessment section
     * 
     * Identifies potential issues with deprecated files and provides
     * risk mitigation guidance.
     * 
     * @param array $auditResults Array of file audit data
     * @param array $dispositionCounts Disposition category counts
     * @return string Risk assessment section
     */
    private function buildRiskAssessment($auditResults, $dispositionCounts)
    {
        $deprecate = isset($dispositionCounts['deprecate']) ? $dispositionCounts['deprecate'] : 0;
        $retain = isset($dispositionCounts['retain']) ? $dispositionCounts['retain'] : 0;
        
        $assessment = array(
            "## Risk Assessment\n",
            "### Deprecated Files Risk\n"
        );
        
        if ($deprecate > 0) {
            $assessment[] = "**Risk Level:** Medium";
            $assessment[] = "**Issue:** {$deprecate} files reference versions 4.0.34 or earlier and may be obsolete.\n";
            $assessment[] = "**Mitigation:**";
            $assessment[] = "- Review each deprecated file before deletion";
            $assessment[] = "- Check for references in active code or documentation";
            $assessment[] = "- Verify no critical information will be lost";
            $assessment[] = "- Create backup archive before deletion\n";
        } else {
            $assessment[] = "**Risk Level:** Low";
            $assessment[] = "**Issue:** No deprecated files identified.\n";
        }
        
        $assessment[] = "### Version Metadata Gaps\n";
        
        // Count files without version metadata
        $noVersionCount = 0;
        foreach ($auditResults as $result) {
            if (!isset($result['version']) || $result['version'] === null) {
                $noVersionCount++;
            }
        }
        
        if ($noVersionCount > 0) {
            $assessment[] = "**Risk Level:** Low";
            $assessment[] = "**Issue:** {$noVersionCount} files lack version metadata and were defaulted to 'retain'.\n";
            $assessment[] = "**Mitigation:**";
            $assessment[] = "- Review files without version metadata manually";
            $assessment[] = "- Add FLIP headers with system_version where appropriate";
            $assessment[] = "- Verify files are actually relevant for 4.0.44\n";
        } else {
            $assessment[] = "**Risk Level:** None";
            $assessment[] = "**Issue:** All files have version metadata.\n";
        }
        
        $assessment[] = "### Data Loss Prevention\n";
        $assessment[] = "**Risk Level:** Low";
        $assessment[] = "**Issue:** Audit process is read-only; no automatic deletions occur.\n";
        $assessment[] = "**Mitigation:**";
        $assessment[] = "- All file operations require manual approval";
        $assessment[] = "- Audit report provides clear rationale for each disposition";
        $assessment[] = "- Backup recommended before any file operations\n";
        
        return implode("\n", $assessment);
    }
    
    /**
     * Build report footer
     * 
     * Adds metadata and signature to report.
     * 
     * @param string $timestamp Report generation timestamp
     * @return string Footer section
     */
    private function buildFooter($timestamp)
    {
        $displayTime = $this->timestampHelper->formatForDisplay($timestamp);
        
        $footer = array(
            "---\n",
            "**Report Generated:** {$displayTime}",
            "**Generated By:** KIRO Initialization System (Actor ID 1001)",
            "**System Version:** 4.0.44",
            "**Report Type:** Status Directory Audit\n",
            "*This report was automatically generated by the Lupopedia initialization workflow.*",
            "*All file operations require manual review and approval before execution.*"
        );
        
        return implode("\n", $footer);
    }

    /**
     * Sort audit results by disposition then filename
     * 
     * Groups results by disposition (retain, archive, deprecate) and
     * sorts alphabetically within each group.
     * 
     * @param array $auditResults Array of file audit data
     * @return array Sorted audit results
     */
    private function sortAuditResults($auditResults)
    {
        // Define disposition priority
        $dispositionPriority = array(
            'retain' => 1,
            'archive' => 2,
            'deprecate' => 3
        );
        
        // Sort using usort with custom comparison
        $sorted = $auditResults;
        usort($sorted, function($a, $b) use ($dispositionPriority) {
            // Compare by disposition first
            $aPriority = isset($dispositionPriority[$a['disposition']]) 
                ? $dispositionPriority[$a['disposition']] 
                : 999;
            $bPriority = isset($dispositionPriority[$b['disposition']]) 
                ? $dispositionPriority[$b['disposition']] 
                : 999;
            
            if ($aPriority !== $bPriority) {
                return $aPriority - $bPriority;
            }
            
            // If same disposition, sort by filename
            return strcmp($a['filename'], $b['filename']);
        });
        
        return $sorted;
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
     * @throws ReportGenerationException If directory cannot be created
     */
    private function ensureDirectoryExists($directory)
    {
        if (is_dir($directory)) {
            return;
        }
        
        $result = @mkdir($directory, 0755, true);
        if (!$result) {
            throw new ReportGenerationException(
                ErrorMessages::directoryCreationFailed($directory, 'ReportGenerator')
            );
        }
    }
}
