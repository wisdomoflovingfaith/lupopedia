<?php
/**
 * Interface for generating audit reports
 * 
 * Defines the contract for creating comprehensive Markdown reports
 * documenting status directory audit results.
 * 
 * @package Lupopedia\Services\Initialization\Interfaces
 * @since 4.0.44
 */
interface ReportGeneratorInterface
{
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
    public function generateAuditReport($auditResults, $dispositionCounts, $outputPath);
}
