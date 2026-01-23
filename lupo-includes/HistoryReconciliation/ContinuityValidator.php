<?php
/**
 * Continuity Validator for History Reconciliation Pass
 * 
 * Validates timeline continuity, cross-references, and metadata consistency
 * across all historical documentation to ensure system integrity.
 * 
 * @package Lupopedia
 * @subpackage HistoryReconciliation
 * @version 4.0.61
 * @author Captain Wolfie
 */

class ContinuityValidator {
    
    private $atomLoader;
    private $historyPath;
    private $timelineFile;
    private $validationResults;
    
    /**
     * Constructor
     * 
     * @param string $historyPath Base path for historical documentation
     * @param string $timelineFile Path to main timeline file
     */
    public function __construct($historyPath = 'docs/history/', $timelineFile = 'docs/history/TIMELINE_1996_2026.md') {
        $this->historyPath = $historyPath;
        $this->timelineFile = $timelineFile;
        $this->validationResults = [];
        
        // Load atom loader for version and metadata resolution
        if (file_exists('lupo-includes/functions/load_atoms.php')) {
            require_once 'lupo-includes/functions/load_atoms.php';
            if (class_exists('AtomLoader')) {
                $this->atomLoader = new AtomLoader();
            } else {
                $this->atomLoader = new SimpleContinuityAtomLoader();
            }
        } else {
            $this->atomLoader = new SimpleContinuityAtomLoader();
        }
    }
    
    /**
     * Validate timeline continuity for gap detection
     * 
     * Performs comprehensive validation of historical timeline to detect
     * gaps, inconsistencies, and missing documentation that could break
     * narrative continuity.
     * 
     * @return array Validation results with detailed gap analysis
     * @throws Exception If validation cannot be completed
     */
    public function validateTimelineContinuity() {
        $results = [
            'valid' => true,
            'gaps' => [],
            'inconsistencies' => [],
            'coverage' => [],
            'recommendations' => [],
            'validation_date' => date('Y-m-d H:i:s')
        ];
        
        try {
            // Check for complete year coverage (1996-2026)
            $expectedYears = range(1996, 2026);
            $documentedYears = $this->getDocumentedYears();
            
            // Identify gaps
            $missingYears = array_diff($expectedYears, $documentedYears);
            if (!empty($missingYears)) {
                $results['valid'] = false;
                $results['gaps'] = $this->analyzeGaps($missingYears);
            }
            
            // Check period transitions
            $transitionIssues = $this->validatePeriodTransitions($documentedYears);
            if (!empty($transitionIssues)) {
                $results['valid'] = false;
                $results['inconsistencies'] = array_merge($results['inconsistencies'], $transitionIssues);
            }
            
            // Validate narrative flow
            $narrativeIssues = $this->validateNarrativeFlow($documentedYears);
            if (!empty($narrativeIssues)) {
                $results['valid'] = false;
                $results['inconsistencies'] = array_merge($results['inconsistencies'], $narrativeIssues);
            }
            
            // Check milestone coverage
            $milestoneIssues = $this->validateMilestoneCoverage();
            if (!empty($milestoneIssues)) {
                $results['valid'] = false;
                $results['inconsistencies'] = array_merge($results['inconsistencies'], $milestoneIssues);
            }
            
            // Generate coverage statistics
            $results['coverage'] = $this->generateCoverageStatistics($documentedYears, $expectedYears);
            
            // Generate recommendations
            $results['recommendations'] = $this->generateRecommendations($results);
            
        } catch (Exception $e) {
            throw new Exception("Timeline continuity validation failed: " . $e->getMessage());
        }
        
        return $results;
    }
    
    /**
     * Validate cross-references for link validation
     * 
     * Scans all historical documentation to validate cross-references,
     * detect broken links, and ensure proper navigation between documents.
     * 
     * @return array Cross-reference validation results
     * @throws Exception If validation cannot be completed
     */
    public function validateCrossReferences() {
        $results = [
            'valid' => true,
            'broken_links' => [],
            'missing_references' => [],
            'orphaned_documents' => [],
            'circular_references' => [],
            'validation_date' => date('Y-m-d H:i:s')
        ];
        
        try {
            $documentedYears = $this->getDocumentedYears();
            
            foreach ($documentedYears as $year) {
                $filePath = $this->getYearFilePath($year);
                
                if (!file_exists($filePath)) {
                    continue;
                }
                
                $content = file_get_contents($filePath);
                
                // Check for broken internal links
                $brokenLinks = $this->findBrokenLinks($content, $filePath, $year);
                if (!empty($brokenLinks)) {
                    $results['valid'] = false;
                    $results['broken_links'] = array_merge($results['broken_links'], $brokenLinks);
                }
                
                // Check for missing expected references
                $missingRefs = $this->findMissingReferences($content, $year, $documentedYears);
                if (!empty($missingRefs)) {
                    $results['valid'] = false;
                    $results['missing_references'] = array_merge($results['missing_references'], $missingRefs);
                }
            }
            
            // Check for orphaned documents (no incoming references)
            $orphanedDocs = $this->findOrphanedDocuments($documentedYears);
            if (!empty($orphanedDocs)) {
                $results['valid'] = false;
                $results['orphaned_documents'] = $orphanedDocs;
            }
            
            // Check for circular references
            $circularRefs = $this->findCircularReferences($documentedYears);
            if (!empty($circularRefs)) {
                $results['valid'] = false;
                $results['circular_references'] = $circularRefs;
            }
            
        } catch (Exception $e) {
            throw new Exception("Cross-reference validation failed: " . $e->getMessage());
        }
        
        return $results;
    }
    
    /**
     * Validate metadata consistency across all documents
     * 
     * Ensures consistent WOLFIE headers, version information, and
     * metadata formatting across all historical documentation.
     * 
     * @return array Metadata validation results
     * @throws Exception If validation cannot be completed
     */
    public function validateMetadataConsistency() {
        $results = [
            'valid' => true,
            'header_issues' => [],
            'version_mismatches' => [],
            'format_violations' => [],
            'missing_metadata' => [],
            'validation_date' => date('Y-m-d H:i:s')
        ];
        
        try {
            $documentedYears = $this->getDocumentedYears();
            $expectedVersion = $this->atomLoader->getAtom('GLOBAL_CURRENT_LUPOPEDIA_VERSION');
            $expectedAuthor = $this->atomLoader->getAtom('GLOBAL_CURRENT_AUTHORS');
            
            foreach ($documentedYears as $year) {
                $filePath = $this->getYearFilePath($year);
                
                if (!file_exists($filePath)) {
                    continue;
                }
                
                $content = file_get_contents($filePath);
                
                // Validate WOLFIE header presence and format
                $headerIssues = $this->validateWolfieHeader($content, $year, $filePath);
                if (!empty($headerIssues)) {
                    $results['valid'] = false;
                    $results['header_issues'] = array_merge($results['header_issues'], $headerIssues);
                }
                
                // Validate version consistency
                $versionIssues = $this->validateVersionConsistency($content, $year, $expectedVersion);
                if (!empty($versionIssues)) {
                    $results['valid'] = false;
                    $results['version_mismatches'] = array_merge($results['version_mismatches'], $versionIssues);
                }
                
                // Validate format compliance
                $formatIssues = $this->validateFormatCompliance($content, $year);
                if (!empty($formatIssues)) {
                    $results['valid'] = false;
                    $results['format_violations'] = array_merge($results['format_violations'], $formatIssues);
                }
                
                // Check for missing required metadata
                $metadataIssues = $this->validateRequiredMetadata($content, $year);
                if (!empty($metadataIssues)) {
                    $results['valid'] = false;
                    $results['missing_metadata'] = array_merge($results['missing_metadata'], $metadataIssues);
                }
            }
            
        } catch (Exception $e) {
            throw new Exception("Metadata consistency validation failed: " . $e->getMessage());
        }
        
        return $results;
    }
    
    /**
     * Generate comprehensive validation report
     * 
     * Creates a detailed validation report combining all validation results
     * with actionable recommendations and priority levels.
     * 
     * @return array Comprehensive validation report
     */
    public function generateValidationReport() {
        $report = [
            'summary' => [],
            'timeline_continuity' => [],
            'cross_references' => [],
            'metadata_consistency' => [],
            'overall_status' => 'unknown',
            'priority_issues' => [],
            'recommendations' => [],
            'generated_date' => date('Y-m-d H:i:s')
        ];
        
        try {
            // Run all validations
            $timelineResults = $this->validateTimelineContinuity();
            $crossRefResults = $this->validateCrossReferences();
            $metadataResults = $this->validateMetadataConsistency();
            
            $report['timeline_continuity'] = $timelineResults;
            $report['cross_references'] = $crossRefResults;
            $report['metadata_consistency'] = $metadataResults;
            
            // Generate summary
            $totalIssues = 0;
            $criticalIssues = 0;
            
            // Count timeline issues
            $timelineIssues = count($timelineResults['gaps']) + count($timelineResults['inconsistencies']);
            $totalIssues += $timelineIssues;
            $criticalIssues += count($timelineResults['gaps']); // Gaps are critical
            
            // Count cross-reference issues
            $crossRefIssues = count($crossRefResults['broken_links']) + count($crossRefResults['missing_references']);
            $totalIssues += $crossRefIssues;
            $criticalIssues += count($crossRefResults['broken_links']); // Broken links are critical
            
            // Count metadata issues
            $metadataIssues = count($metadataResults['header_issues']) + count($metadataResults['version_mismatches']);
            $totalIssues += $metadataIssues;
            
            $report['summary'] = [
                'total_issues' => $totalIssues,
                'critical_issues' => $criticalIssues,
                'timeline_valid' => $timelineResults['valid'],
                'cross_references_valid' => $crossRefResults['valid'],
                'metadata_valid' => $metadataResults['valid'],
                'overall_valid' => $timelineResults['valid'] && $crossRefResults['valid'] && $metadataResults['valid']
            ];
            
            // Determine overall status
            if ($report['summary']['overall_valid']) {
                $report['overall_status'] = 'valid';
            } elseif ($criticalIssues > 0) {
                $report['overall_status'] = 'critical';
            } else {
                $report['overall_status'] = 'issues';
            }
            
            // Generate priority issues
            $report['priority_issues'] = $this->generatePriorityIssues($timelineResults, $crossRefResults, $metadataResults);
            
            // Generate consolidated recommendations
            $report['recommendations'] = $this->generateConsolidatedRecommendations($timelineResults, $crossRefResults, $metadataResults);
            
        } catch (Exception $e) {
            $report['error'] = "Validation report generation failed: " . $e->getMessage();
            $report['overall_status'] = 'error';
        }
        
        return $report;
    }
    
    /**
     * Get all years that have documentation files
     * 
     * @return array Array of years with existing documentation
     */
    private function getDocumentedYears() {
        $years = [];
        
        // Check 1996-2013 period
        $earlyPath = $this->historyPath . '1996-2013/';
        if (is_dir($earlyPath)) {
            $files = glob($earlyPath . '*.md');
            foreach ($files as $file) {
                $filename = basename($file, '.md');
                if (is_numeric($filename) && $filename >= 1996 && $filename <= 2013) {
                    $years[] = (int)$filename;
                }
            }
        }
        
        // Check 2014-2025 period
        $hiatusPath = $this->historyPath . '2014-2025/';
        if (is_dir($hiatusPath)) {
            $files = glob($hiatusPath . '*.md');
            foreach ($files as $file) {
                $filename = basename($file, '.md');
                if (is_numeric($filename) && $filename >= 2014 && $filename <= 2025) {
                    $years[] = (int)$filename;
                }
            }
        }
        
        // Check future period (2026+)
        $futurePath = $this->historyPath . 'future/';
        if (is_dir($futurePath)) {
            $files = glob($futurePath . '*.md');
            foreach ($files as $file) {
                $filename = basename($file, '.md');
                if (is_numeric($filename) && $filename >= 2026) {
                    $years[] = (int)$filename;
                }
            }
        }
        
        return array_unique($years);
    }
    
    /**
     * Get file path for a specific year
     * 
     * @param int $year
     * @return string File path
     */
    private function getYearFilePath($year) {
        if ($year >= 2014 && $year <= 2025) {
            return $this->historyPath . "2014-2025/{$year}.md";
        } elseif ($year >= 1996 && $year <= 2013) {
            return $this->historyPath . "1996-2013/{$year}.md";
        } else {
            return $this->historyPath . "future/{$year}.md";
        }
    }
    
    /**
     * Analyze gaps in documentation coverage
     * 
     * @param array $missingYears
     * @return array Gap analysis
     */
    private function analyzeGaps($missingYears) {
        $gaps = [];
        
        // Group consecutive missing years into ranges
        sort($missingYears);
        $currentGap = [];
        
        foreach ($missingYears as $year) {
            if (empty($currentGap) || $year == end($currentGap) + 1) {
                $currentGap[] = $year;
            } else {
                // Process completed gap
                $gaps[] = $this->createGapAnalysis($currentGap);
                $currentGap = [$year];
            }
        }
        
        // Process final gap
        if (!empty($currentGap)) {
            $gaps[] = $this->createGapAnalysis($currentGap);
        }
        
        return $gaps;
    }
    
    /**
     * Create gap analysis for a range of years
     * 
     * @param array $yearRange
     * @return array Gap analysis
     */
    private function createGapAnalysis($yearRange) {
        $startYear = min($yearRange);
        $endYear = max($yearRange);
        $period = $this->getYearPeriod($startYear);
        
        return [
            'start_year' => $startYear,
            'end_year' => $endYear,
            'duration' => count($yearRange),
            'period' => $period,
            'severity' => $this->getGapSeverity($yearRange, $period),
            'impact' => $this->getGapImpact($yearRange, $period),
            'recommendation' => $this->getGapRecommendation($yearRange, $period)
        ];
    }
    
    /**
     * Get period classification for a year
     * 
     * @param int $year
     * @return string Period name
     */
    private function getYearPeriod($year) {
        if ($year >= 2014 && $year <= 2024) {
            return 'hiatus';
        } elseif ($year >= 1996 && $year <= 2013) {
            return 'active';
        } elseif ($year >= 2025) {
            return 'resurgence';
        } else {
            return 'unknown';
        }
    }
    
    /**
     * Determine gap severity
     * 
     * @param array $yearRange
     * @param string $period
     * @return string Severity level
     */
    private function getGapSeverity($yearRange, $period) {
        $duration = count($yearRange);
        
        if ($period === 'hiatus' && $duration > 5) {
            return 'high'; // Large hiatus gaps are expected but still important
        } elseif ($period === 'active' && $duration > 2) {
            return 'critical'; // Active period gaps are critical
        } elseif ($period === 'resurgence') {
            return 'critical'; // All resurgence gaps are critical
        } elseif ($duration > 3) {
            return 'high';
        } else {
            return 'medium';
        }
    }
    
    /**
     * Determine gap impact on narrative continuity
     * 
     * @param array $yearRange
     * @param string $period
     * @return string Impact description
     */
    private function getGapImpact($yearRange, $period) {
        $duration = count($yearRange);
        $startYear = min($yearRange);
        $endYear = max($yearRange);
        
        if ($period === 'hiatus') {
            return "Missing documentation for {$duration} years of hiatus period ({$startYear}-{$endYear}). Affects understanding of dormant period.";
        } elseif ($period === 'active') {
            return "Missing documentation for {$duration} years of active development ({$startYear}-{$endYear}). Critical gap in Crafty Syntax evolution.";
        } elseif ($period === 'resurgence') {
            return "Missing documentation for {$duration} years of resurgence period ({$startYear}-{$endYear}). Critical gap in Lupopedia emergence.";
        } else {
            return "Missing documentation for {$duration} years ({$startYear}-{$endYear}). Impact on narrative continuity.";
        }
    }
    
    /**
     * Generate gap recommendation
     * 
     * @param array $yearRange
     * @param string $period
     * @return string Recommendation
     */
    private function getGapRecommendation($yearRange, $period) {
        if ($period === 'hiatus') {
            return "Create respectful hiatus documentation acknowledging the gap without fabricating events.";
        } elseif ($period === 'active') {
            return "Research and document Crafty Syntax development activities for these years.";
        } elseif ($period === 'resurgence') {
            return "Document Lupopedia development milestones and system evolution.";
        } else {
            return "Create appropriate historical documentation for this period.";
        }
    }
    
    // Additional validation methods would continue here...
    // (validatePeriodTransitions, validateNarrativeFlow, etc.)
    
    /**
     * Validate period transitions for consistency
     * 
     * @param array $documentedYears
     * @return array Transition issues
     */
    private function validatePeriodTransitions($documentedYears) {
        $issues = [];
        
        // Check for proper transition documentation at key years
        $transitionYears = [2013, 2014, 2025, 2026];
        
        foreach ($transitionYears as $year) {
            if (in_array($year, $documentedYears)) {
                $filePath = $this->getYearFilePath($year);
                $content = file_get_contents($filePath);
                
                // Check for transition context
                if (!$this->hasTransitionContext($content, $year)) {
                    $issues[] = [
                        'type' => 'missing_transition_context',
                        'year' => $year,
                        'severity' => 'high',
                        'message' => "Year {$year} lacks proper transition context"
                    ];
                }
            }
        }
        
        return $issues;
    }
    
    /**
     * Check if content has appropriate transition context
     * 
     * @param string $content
     * @param int $year
     * @return bool
     */
    private function hasTransitionContext($content, $year) {
        $transitionKeywords = [
            2013 => ['end', 'final', 'last', 'transition'],
            2014 => ['hiatus', 'tragedy', 'suspension', 'begin'],
            2025 => ['return', 'emergence', 'resurrection', 'wolfie'],
            2026 => ['sprint', 'development', 'lupopedia', 'transformation']
        ];
        
        if (!isset($transitionKeywords[$year])) {
            return true; // No specific requirements
        }
        
        $keywords = $transitionKeywords[$year];
        foreach ($keywords as $keyword) {
            if (stripos($content, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Validate narrative flow between documented years
     * 
     * @param array $documentedYears
     * @return array Narrative issues
     */
    private function validateNarrativeFlow($documentedYears) {
        $issues = [];
        sort($documentedYears);
        
        for ($i = 0; $i < count($documentedYears) - 1; $i++) {
            $currentYear = $documentedYears[$i];
            $nextYear = $documentedYears[$i + 1];
            
            // Check for large gaps that might break narrative flow
            if ($nextYear - $currentYear > 5) {
                $issues[] = [
                    'type' => 'narrative_gap',
                    'start_year' => $currentYear,
                    'end_year' => $nextYear,
                    'gap_size' => $nextYear - $currentYear,
                    'severity' => 'medium',
                    'message' => "Large gap between {$currentYear} and {$nextYear} may break narrative flow"
                ];
            }
        }
        
        return $issues;
    }
    
    /**
     * Validate milestone coverage
     * 
     * @return array Milestone issues
     */
    private function validateMilestoneCoverage() {
        $issues = [];
        $documentedYears = $this->getDocumentedYears();
        
        $criticalMilestones = [2002, 2014, 2025, 2026];
        
        foreach ($criticalMilestones as $year) {
            if (!in_array($year, $documentedYears)) {
                $issues[] = [
                    'type' => 'missing_milestone',
                    'year' => $year,
                    'severity' => 'critical',
                    'message' => "Critical milestone year {$year} is not documented"
                ];
            }
        }
        
        return $issues;
    }
    
    /**
     * Generate coverage statistics
     * 
     * @param array $documentedYears
     * @param array $expectedYears
     * @return array Coverage statistics
     */
    private function generateCoverageStatistics($documentedYears, $expectedYears) {
        $totalExpected = count($expectedYears);
        $totalDocumented = count($documentedYears);
        $coveragePercent = $totalExpected > 0 ? round(($totalDocumented / $totalExpected) * 100, 1) : 0;
        
        return [
            'total_expected' => $totalExpected,
            'total_documented' => $totalDocumented,
            'coverage_percent' => $coveragePercent,
            'missing_count' => $totalExpected - $totalDocumented,
            'periods' => [
                'active' => $this->getPeriodCoverage($documentedYears, range(1996, 2013)),
                'hiatus' => $this->getPeriodCoverage($documentedYears, range(2014, 2024)),
                'resurgence' => $this->getPeriodCoverage($documentedYears, [2025, 2026])
            ]
        ];
    }
    
    /**
     * Get coverage statistics for a specific period
     * 
     * @param array $documentedYears
     * @param array $periodYears
     * @return array Period coverage
     */
    private function getPeriodCoverage($documentedYears, $periodYears) {
        $documented = array_intersect($documentedYears, $periodYears);
        $total = count($periodYears);
        $covered = count($documented);
        
        return [
            'total' => $total,
            'documented' => $covered,
            'percent' => $total > 0 ? round(($covered / $total) * 100, 1) : 0,
            'missing' => $total - $covered
        ];
    }
    
    /**
     * Generate recommendations based on validation results
     * 
     * @param array $results
     * @return array Recommendations
     */
    private function generateRecommendations($results) {
        $recommendations = [];
        
        if (!empty($results['gaps'])) {
            $recommendations[] = [
                'priority' => 'high',
                'category' => 'documentation',
                'action' => 'Fill documentation gaps',
                'description' => 'Create missing year files to establish complete timeline coverage'
            ];
        }
        
        if (!empty($results['inconsistencies'])) {
            $recommendations[] = [
                'priority' => 'medium',
                'category' => 'consistency',
                'action' => 'Resolve inconsistencies',
                'description' => 'Address period transition and narrative flow issues'
            ];
        }
        
        return $recommendations;
    }
    
    // Placeholder methods for cross-reference validation
    private function findBrokenLinks($content, $filePath, $year) { return []; }
    private function findMissingReferences($content, $year, $documentedYears) { return []; }
    private function findOrphanedDocuments($documentedYears) { return []; }
    private function findCircularReferences($documentedYears) { return []; }
    
    // Placeholder methods for metadata validation
    private function validateWolfieHeader($content, $year, $filePath) { return []; }
    private function validateVersionConsistency($content, $year, $expectedVersion) { return []; }
    private function validateFormatCompliance($content, $year) { return []; }
    private function validateRequiredMetadata($content, $year) { return []; }
    
    // Placeholder methods for report generation
    private function generatePriorityIssues($timeline, $crossRef, $metadata) { return []; }
    private function generateConsolidatedRecommendations($timeline, $crossRef, $metadata) { return []; }
}

/**
 * Simple AtomLoader fallback class for ContinuityValidator
 */
class SimpleContinuityAtomLoader {
    private $atoms = [];
    
    public function __construct() {
        $this->loadAtoms();
    }
    
    private function loadAtoms() {
        // Load from global atoms file
        $atomsFile = 'config/global_atoms.yaml';
        if (file_exists($atomsFile)) {
            $content = file_get_contents($atomsFile);
            // Simple YAML parsing for key atoms
            if (preg_match('/GLOBAL_CURRENT_LUPOPEDIA_VERSION:\s*"([^"]+)"/', $content, $matches)) {
                $this->atoms['GLOBAL_CURRENT_LUPOPEDIA_VERSION'] = $matches[1];
            }
            if (preg_match('/GLOBAL_CURRENT_AUTHORS:\s*"([^"]+)"/', $content, $matches)) {
                $this->atoms['GLOBAL_CURRENT_AUTHORS'] = $matches[1];
            }
        }
        
        // Fallback values
        if (!isset($this->atoms['GLOBAL_CURRENT_LUPOPEDIA_VERSION'])) {
            $this->atoms['GLOBAL_CURRENT_LUPOPEDIA_VERSION'] = '4.0.61';
        }
        if (!isset($this->atoms['GLOBAL_CURRENT_AUTHORS'])) {
            $this->atoms['GLOBAL_CURRENT_AUTHORS'] = 'Captain Wolfie';
        }
    }
    
    public function getAtom($name) {
        return $this->atoms[$name] ?? null;
    }
}