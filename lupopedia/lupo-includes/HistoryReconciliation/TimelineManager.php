<?php
/**
 * Timeline Manager for History Reconciliation Pass
 * 
 * Manages chronological validation, timeline file updates, and cross-reference
 * generation for the historical documentation system.
 * 
 * @package Lupopedia
 * @subpackage HistoryReconciliation
 * @version 4.0.61
 * @author Captain Wolfie
 */

class TimelineManager {
    
    private $atomLoader;
    private $timelineFile;
    private $historyPath;
    
    /**
     * Constructor
     * 
     * @param string $timelineFile Path to main timeline file
     * @param string $historyPath Base path for historical documentation
     */
    public function __construct($timelineFile = 'docs/history/TIMELINE_1996_2026.md', $historyPath = 'docs/history/') {
        $this->timelineFile = $timelineFile;
        $this->historyPath = $historyPath;
        
        // Load atom loader for version and metadata resolution
        if (file_exists('lupo-includes/functions/load_atoms.php')) {
            require_once 'lupo-includes/functions/load_atoms.php';
            if (class_exists('AtomLoader')) {
                $this->atomLoader = new AtomLoader();
            } else {
                $this->atomLoader = new SimpleAtomLoader();
            }
        } else {
            // Fallback - create simple atom loader inline
            $this->atomLoader = new SimpleAtomLoader();
        }
    }
    
    /**
     * Validate chronological consistency across all historical documentation
     * 
     * Checks for gaps, overlaps, and inconsistencies in the historical timeline
     * to ensure complete coverage from 1996-2026.
     * 
     * @return array Validation results with any issues found
     * @throws Exception If validation cannot be completed
     */
    public function validateChronology() {
        $issues = [];
        $yearsCovered = [];
        
        try {
            // Check for complete year coverage (1996-2026)
            $expectedYears = range(1996, 2026);
            $actualYears = $this->getDocumentedYears();
            
            // Find missing years
            $missingYears = array_diff($expectedYears, $actualYears);
            if (!empty($missingYears)) {
                $issues[] = [
                    'type' => 'missing_years',
                    'severity' => 'error',
                    'message' => 'Missing documentation for years: ' . implode(', ', $missingYears),
                    'years' => $missingYears
                ];
            }
            
            // Find extra/unexpected years
            $extraYears = array_diff($actualYears, $expectedYears);
            if (!empty($extraYears)) {
                $issues[] = [
                    'type' => 'extra_years',
                    'severity' => 'warning',
                    'message' => 'Unexpected documentation for years: ' . implode(', ', $extraYears),
                    'years' => $extraYears
                ];
            }
            
            // Validate chronological order in timeline file
            $timelineOrder = $this->validateTimelineOrder();
            if (!empty($timelineOrder)) {
                $issues = array_merge($issues, $timelineOrder);
            }
            
            // Validate period consistency (hiatus, active, resurgence)
            $periodIssues = $this->validatePeriodConsistency();
            if (!empty($periodIssues)) {
                $issues = array_merge($issues, $periodIssues);
            }
            
            // Validate cross-references
            $crossRefIssues = $this->validateCrossReferences();
            if (!empty($crossRefIssues)) {
                $issues = array_merge($issues, $crossRefIssues);
            }
            
        } catch (Exception $e) {
            throw new Exception("Chronology validation failed: " . $e->getMessage());
        }
        
        return [
            'valid' => empty($issues),
            'issues' => $issues,
            'years_documented' => $actualYears,
            'validation_date' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Update the main timeline file with new content and cross-references
     * 
     * Regenerates the timeline file with current historical data, ensuring
     * proper chronological order and complete cross-references.
     * 
     * @param array $updates Optional updates to apply to timeline
     * @return string Path to updated timeline file
     * @throws Exception If update fails
     */
    public function updateTimelineFile($updates = []) {
        try {
            // Load current timeline content
            $currentContent = '';
            if (file_exists($this->timelineFile)) {
                $currentContent = file_get_contents($this->timelineFile);
            }
            
            // Generate new timeline content
            $newContent = $this->generateTimelineContent($updates);
            
            // Create backup of existing file
            if (file_exists($this->timelineFile)) {
                $backupPath = $this->timelineFile . '.backup.' . date('Ymd_His');
                copy($this->timelineFile, $backupPath);
            }
            
            // Ensure directory exists
            $directory = dirname($this->timelineFile);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Write updated content
            if (file_put_contents($this->timelineFile, $newContent) === false) {
                throw new Exception("Failed to write timeline file: {$this->timelineFile}");
            }
            
            return $this->timelineFile;
            
        } catch (Exception $e) {
            throw new Exception("Timeline update failed: " . $e->getMessage());
        }
    }
    
    /**
     * Generate cross-references between historical documents
     * 
     * Creates navigation links and references between related historical
     * documents to improve discoverability and context.
     * 
     * @return array Generated cross-references
     */
    public function generateCrossReferences() {
        $crossRefs = [];
        
        try {
            // Get all documented years
            $years = $this->getDocumentedYears();
            sort($years);
            
            foreach ($years as $year) {
                $yearRefs = [];
                
                // Previous/Next year navigation
                $prevYear = $year - 1;
                $nextYear = $year + 1;
                
                if (in_array($prevYear, $years)) {
                    $yearRefs['previous'] = [
                        'year' => $prevYear,
                        'path' => $this->getYearFilePath($prevYear),
                        'title' => "{$prevYear} Historical Record"
                    ];
                }
                
                if (in_array($nextYear, $years)) {
                    $yearRefs['next'] = [
                        'year' => $nextYear,
                        'path' => $this->getYearFilePath($nextYear),
                        'title' => "{$nextYear} Historical Record"
                    ];
                }
                
                // Period-based references
                $period = $this->getYearPeriod($year);
                $yearRefs['period'] = $period;
                $yearRefs['period_overview'] = $this->getPeriodOverviewPath($period);
                
                // Timeline reference
                $yearRefs['timeline'] = [
                    'path' => $this->timelineFile,
                    'anchor' => "year-{$year}",
                    'title' => "Full Timeline (1996-2026)"
                ];
                
                $crossRefs[$year] = $yearRefs;
            }
            
        } catch (Exception $e) {
            error_log("Cross-reference generation failed: " . $e->getMessage());
        }
        
        return $crossRefs;
    }
    
    /**
     * Generate comprehensive history index for navigation
     * 
     * Creates a master index file that provides organized navigation
     * to all historical documentation with period groupings and milestones.
     * 
     * @return string Path to generated history index file
     * @throws Exception If index generation fails
     */
    public function generateHistoryIndex() {
        try {
            $indexPath = $this->historyPath . 'HISTORY_INDEX.md';
            $version = $this->atomLoader->getAtom('GLOBAL_CURRENT_LUPOPEDIA_VERSION');
            $author = $this->atomLoader->getAtom('GLOBAL_CURRENT_AUTHORS');
            
            $content = [];
            
            // WOLFIE Header
            $content[] = "---";
            $content[] = "wolfie.headers: explicit architecture with structured clarity for every file.";
            $content[] = "file.last_modified_system_version: {$version}";
            $content[] = "header_atoms:";
            $content[] = "  - GLOBAL_CURRENT_LUPOPEDIA_VERSION";
            $content[] = "  - GLOBAL_CURRENT_AUTHORS";
            $content[] = "dialog:";
            $content[] = "  speaker: WOLFIE";
            $content[] = "  target: @historians";
            $content[] = "  mood_RGB: \"0066CC\"";
            $content[] = "  message: \"Master navigation index for complete Lupopedia historical documentation\"";
            $content[] = "tags:";
            $content[] = "  categories: [\"documentation\", \"history\", \"navigation\"]";
            $content[] = "  collections: [\"historical-records\", \"master-index\"]";
            $content[] = "  channels: [\"public\", \"historical\"]";
            $content[] = "file:";
            $content[] = "  title: \"Lupopedia History Index\"";
            $content[] = "  description: \"Master navigation index for all historical documentation\"";
            $content[] = "  version: {$version}";
            $content[] = "  status: published";
            $content[] = "  author: {$author}";
            $content[] = "---";
            $content[] = "";
            
            // Title and Overview
            $content[] = "# Lupopedia History Index";
            $content[] = "";
            $content[] = "## Overview";
            $content[] = "";
            $content[] = "This index provides comprehensive navigation to all Lupopedia historical";
            $content[] = "documentation, organized by periods and milestones from 1996-2026.";
            $content[] = "";
            
            // Quick Navigation
            $content[] = "## Quick Navigation";
            $content[] = "";
            $content[] = "- 📊 [Complete Timeline](TIMELINE_1996_2026.md) - Chronological overview";
            $content[] = "- 🏗️ [Active Period (1996-2013)](1996-2013/README.md) - Crafty Syntax era";
            $content[] = "- 💤 [Hiatus Period (2014-2025)](2014-2025/README.md) - Gap years";
            $content[] = "- 🚀 [Resurgence Period (2025-2026)](future/README.md) - Lupopedia emergence";
            $content[] = "";
            
            // Generate period-based navigation
            $periods = $this->generatePeriodNavigation();
            $content = array_merge($content, $periods);
            
            // Generate milestone tracking
            $milestones = $this->generateMilestoneIndex();
            $content = array_merge($content, $milestones);
            
            // Generate year-by-year index
            $yearIndex = $this->generateYearIndex();
            $content = array_merge($content, $yearIndex);
            
            // Footer
            $content[] = "";
            $content[] = "---";
            $content[] = "";
            $content[] = "*Generated: " . date('Y-m-d H:i:s') . "*";
            $content[] = "*Version: {$version}*";
            $content[] = "*Coverage: 1996-2026 (30 years)*";
            
            // Write index file
            $fullContent = implode("\n", $content);
            
            // Ensure directory exists
            $directory = dirname($indexPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            if (file_put_contents($indexPath, $fullContent) === false) {
                throw new Exception("Failed to write history index: {$indexPath}");
            }
            
            return $indexPath;
            
        } catch (Exception $e) {
            throw new Exception("History index generation failed: " . $e->getMessage());
        }
    }
    
    /**
     * Update cross-references in existing documentation files
     * 
     * Scans all historical documentation and updates cross-reference
     * sections to ensure accurate navigation between documents.
     * 
     * @return array Results of cross-reference updates
     * @throws Exception If update process fails
     */
    public function updateCrossReferences() {
        $results = [
            'updated' => [],
            'errors' => [],
            'skipped' => []
        ];
        
        try {
            $crossRefs = $this->generateCrossReferences();
            $years = $this->getDocumentedYears();
            
            foreach ($years as $year) {
                $filePath = $this->getYearFilePath($year);
                
                if (!file_exists($filePath)) {
                    $results['skipped'][] = [
                        'year' => $year,
                        'reason' => 'File does not exist'
                    ];
                    continue;
                }
                
                try {
                    $content = file_get_contents($filePath);
                    $updatedContent = $this->insertCrossReferences($content, $crossRefs[$year]);
                    
                    if ($content !== $updatedContent) {
                        // Create backup
                        $backupPath = $filePath . '.backup.' . date('Ymd_His');
                        copy($filePath, $backupPath);
                        
                        // Write updated content
                        file_put_contents($filePath, $updatedContent);
                        
                        $results['updated'][] = [
                            'year' => $year,
                            'file' => $filePath,
                            'backup' => $backupPath
                        ];
                    } else {
                        $results['skipped'][] = [
                            'year' => $year,
                            'reason' => 'No changes needed'
                        ];
                    }
                    
                } catch (Exception $e) {
                    $results['errors'][] = [
                        'year' => $year,
                        'error' => $e->getMessage()
                    ];
                }
            }
            
        } catch (Exception $e) {
            throw new Exception("Cross-reference update failed: " . $e->getMessage());
        }
        
        return $results;
    }
    
    /**
     * Track and generate timeline milestones
     * 
     * Identifies and tracks major milestones across the historical timeline
     * for enhanced navigation and context understanding.
     * 
     * @return array Timeline milestones with metadata
     */
    public function generateTimelineMilestones() {
        $milestones = [];
        
        try {
            // Define major milestones
            $majorMilestones = [
                1996 => [
                    'title' => 'Project Origins',
                    'description' => 'Early development begins',
                    'type' => 'origin',
                    'significance' => 'high'
                ],
                2002 => [
                    'title' => 'Crafty Syntax Launch',
                    'description' => 'First public release of Crafty Syntax Live Help',
                    'type' => 'launch',
                    'significance' => 'critical'
                ],
                2013 => [
                    'title' => 'Active Development End',
                    'description' => 'Last version before hiatus period',
                    'type' => 'transition',
                    'significance' => 'high'
                ],
                2014 => [
                    'title' => 'Hiatus Period Begins',
                    'description' => 'Development suspended following personal tragedy',
                    'type' => 'hiatus_start',
                    'significance' => 'critical'
                ],
                2025 => [
                    'title' => 'The Return',
                    'description' => 'Eric returns with WOLFIE emergence',
                    'type' => 'resurrection',
                    'significance' => 'critical'
                ],
                2026 => [
                    'title' => 'Lupopedia Sprint',
                    'description' => '16 days, 26 versions - semantic OS complete',
                    'type' => 'transformation',
                    'significance' => 'critical'
                ]
            ];
            
            // Add documented milestones
            $years = $this->getDocumentedYears();
            foreach ($majorMilestones as $year => $milestone) {
                if (in_array($year, $years)) {
                    $milestone['documented'] = true;
                    $milestone['file_path'] = $this->getYearFilePath($year);
                } else {
                    $milestone['documented'] = false;
                    $milestone['file_path'] = null;
                }
                
                $milestone['year'] = $year;
                $milestone['period'] = $this->getYearPeriod($year);
                $milestones[] = $milestone;
            }
            
            // Add version milestones for 2026
            if (in_array(2026, $years)) {
                $versionMilestones = [
                    '4.0.0' => 'Foundation established',
                    '4.0.19' => 'Schema federation complete',
                    '4.0.35' => 'Migration orchestrator complete',
                    '4.0.50' => 'System stabilization',
                    '4.0.61' => 'History Reconciliation Pass'
                ];
                
                foreach ($versionMilestones as $version => $description) {
                    $milestones[] = [
                        'year' => 2026,
                        'title' => "Version {$version}",
                        'description' => $description,
                        'type' => 'version',
                        'significance' => 'medium',
                        'documented' => true,
                        'period' => 'resurgence',
                        'version' => $version
                    ];
                }
            }
            
            // Sort by year and significance
            usort($milestones, function($a, $b) {
                if ($a['year'] !== $b['year']) {
                    return $a['year'] - $b['year'];
                }
                
                $significance = ['critical' => 3, 'high' => 2, 'medium' => 1, 'low' => 0];
                return $significance[$b['significance']] - $significance[$a['significance']];
            });
            
        } catch (Exception $e) {
            error_log("Milestone generation failed: " . $e->getMessage());
        }
        
        return $milestones;
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
     * Validate chronological order in timeline file
     * 
     * @return array Issues found with timeline order
     */
    private function validateTimelineOrder() {
        $issues = [];
        
        if (!file_exists($this->timelineFile)) {
            $issues[] = [
                'type' => 'missing_timeline',
                'severity' => 'error',
                'message' => 'Main timeline file does not exist: ' . $this->timelineFile
            ];
            return $issues;
        }
        
        $content = file_get_contents($this->timelineFile);
        
        // Extract year mentions and check order
        preg_match_all('/\*\*(\d{4})\*\*/', $content, $matches);
        $timelineYears = array_map('intval', $matches[1]);
        
        $sortedYears = $timelineYears;
        sort($sortedYears);
        
        if ($timelineYears !== $sortedYears) {
            $issues[] = [
                'type' => 'timeline_order',
                'severity' => 'warning',
                'message' => 'Years in timeline file are not in chronological order',
                'expected' => $sortedYears,
                'actual' => $timelineYears
            ];
        }
        
        return $issues;
    }
    
    /**
     * Validate period consistency (hiatus, active, resurgence)
     * 
     * @return array Issues found with period definitions
     */
    private function validatePeriodConsistency() {
        $issues = [];
        
        // Define expected periods
        $periods = [
            'active' => range(1996, 2013),
            'hiatus' => range(2014, 2024),
            'resurgence' => [2025, 2026]
        ];
        
        foreach ($periods as $periodName => $expectedYears) {
            foreach ($expectedYears as $year) {
                $filePath = $this->getYearFilePath($year);
                if (file_exists($filePath)) {
                    $content = file_get_contents($filePath);
                    $actualPeriod = $this->extractPeriodFromContent($content);
                    
                    if ($actualPeriod && $actualPeriod !== $periodName) {
                        $issues[] = [
                            'type' => 'period_mismatch',
                            'severity' => 'warning',
                            'message' => "Year {$year} marked as '{$actualPeriod}' but expected '{$periodName}'",
                            'year' => $year,
                            'expected' => $periodName,
                            'actual' => $actualPeriod
                        ];
                    }
                }
            }
        }
        
        return $issues;
    }
    
    /**
     * Validate cross-references between documents
     * 
     * @return array Issues found with cross-references
     */
    private function validateCrossReferences() {
        $issues = [];
        
        $years = $this->getDocumentedYears();
        
        foreach ($years as $year) {
            $filePath = $this->getYearFilePath($year);
            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                
                // Check for broken internal links
                preg_match_all('/\[([^\]]+)\]\(([^)]+)\)/', $content, $matches);
                for ($i = 0; $i < count($matches[0]); $i++) {
                    $linkText = $matches[1][$i];
                    $linkPath = $matches[2][$i];
                    
                    // Skip external links
                    if (strpos($linkPath, 'http') === 0) {
                        continue;
                    }
                    
                    // Check if internal link exists
                    $fullPath = dirname($filePath) . '/' . $linkPath;
                    if (!file_exists($fullPath)) {
                        $issues[] = [
                            'type' => 'broken_link',
                            'severity' => 'error',
                            'message' => "Broken link in {$year}.md: '{$linkText}' -> '{$linkPath}'",
                            'year' => $year,
                            'link_text' => $linkText,
                            'link_path' => $linkPath
                        ];
                    }
                }
            }
        }
        
        return $issues;
    }
    
    /**
     * Generate complete timeline content
     * 
     * @param array $updates
     * @return string Generated timeline content
     */
    private function generateTimelineContent($updates = []) {
        $version = $this->atomLoader->getAtom('GLOBAL_CURRENT_LUPOPEDIA_VERSION');
        $author = $this->atomLoader->getAtom('GLOBAL_CURRENT_AUTHORS');
        
        $content = [];
        
        // WOLFIE Header
        $content[] = "---";
        $content[] = "wolfie.headers: explicit architecture with structured clarity for every file.";
        $content[] = "file.last_modified_system_version: {$version}";
        $content[] = "header_atoms:";
        $content[] = "  - GLOBAL_CURRENT_LUPOPEDIA_VERSION";
        $content[] = "  - GLOBAL_CURRENT_AUTHORS";
        $content[] = "dialog:";
        $content[] = "  speaker: WOLFIE";
        $content[] = "  target: @historians";
        $content[] = "  mood_RGB: \"0066CC\"";
        $content[] = "  message: \"Complete historical timeline from Crafty Syntax origins to Lupopedia v4.1.0\"";
        $content[] = "tags:";
        $content[] = "  categories: [\"documentation\", \"history\", \"timeline\"]";
        $content[] = "  collections: [\"historical-records\", \"master-timeline\"]";
        $content[] = "  channels: [\"public\", \"historical\"]";
        $content[] = "file:";
        $content[] = "  title: \"Lupopedia Historical Timeline (1996-2026)\"";
        $content[] = "  description: \"Complete chronological timeline from Crafty Syntax origins through Lupopedia v4.1.0\"";
        $content[] = "  version: {$version}";
        $content[] = "  status: published";
        $content[] = "  author: {$author}";
        $content[] = "---";
        $content[] = "";
        
        // Title and Overview
        $content[] = "# Lupopedia Historical Timeline (1996-2026)";
        $content[] = "";
        $content[] = "## Overview";
        $content[] = "";
        $content[] = "This timeline documents the complete evolution of Lupopedia from its origins";
        $content[] = "as Crafty Syntax Live Help (2002) through the 2014-2025 hiatus period";
        $content[] = "to the 2025-2026 resurgence and transformation into a semantic operating system.";
        $content[] = "";
        
        // Generate chronological entries
        $years = $this->getDocumentedYears();
        sort($years);
        
        $content[] = "## Timeline";
        $content[] = "";
        
        foreach ($years as $year) {
            $yearContent = $this->generateYearTimelineEntry($year);
            $content = array_merge($content, $yearContent);
        }
        
        // Footer
        $content[] = "";
        $content[] = "---";
        $content[] = "";
        $content[] = "*Generated: " . date('Y-m-d') . "*";
        $content[] = "*Version: {$version}*";
        $content[] = "*Complete timeline: 1996-2026*";
        
        return implode("\n", $content);
    }
    
    /**
     * Generate timeline entry for a specific year
     * 
     * @param int $year
     * @return array Timeline content lines for the year
     */
    private function generateYearTimelineEntry($year) {
        $content = [];
        $period = $this->getYearPeriod($year);
        $filePath = $this->getYearFilePath($year);
        
        $content[] = "### **{$year}** ({$period})";
        
        if (file_exists($filePath)) {
            // Extract key information from year file
            $yearContent = file_get_contents($filePath);
            $summary = $this->extractYearSummary($yearContent);
            
            if ($summary) {
                $content[] = $summary;
            } else {
                $content[] = $this->getDefaultYearSummary($year, $period);
            }
            
            // Add link to detailed documentation
            $relativePath = str_replace($this->historyPath, '', $filePath);
            $content[] = "";
            $content[] = "📖 [Detailed Documentation]({$relativePath})";
        } else {
            $content[] = $this->getDefaultYearSummary($year, $period);
        }
        
        $content[] = "";
        
        return $content;
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
     * Get period overview file path
     * 
     * @param string $period
     * @return string Path to period overview
     */
    private function getPeriodOverviewPath($period) {
        switch ($period) {
            case 'active':
                return $this->historyPath . '1996-2013/README.md';
            case 'hiatus':
                return $this->historyPath . '2014-2025/README.md';
            case 'resurgence':
                return $this->historyPath . 'future/README.md';
            default:
                return $this->historyPath . 'README.md';
        }
    }
    
    /**
     * Extract period information from file content
     * 
     * @param string $content
     * @return string|null Period name
     */
    private function extractPeriodFromContent($content) {
        if (strpos($content, 'hiatus-period') !== false) {
            return 'hiatus';
        } elseif (strpos($content, 'active-development') !== false) {
            return 'active';
        } elseif (strpos($content, 'resurgence-period') !== false) {
            return 'resurgence';
        }
        return null;
    }
    
    /**
     * Extract summary from year file content
     * 
     * @param string $content
     * @return string|null Summary text
     */
    private function extractYearSummary($content) {
        // Look for overview section
        if (preg_match('/## Overview\s*\n\n([^#]+)/', $content, $matches)) {
            $summary = trim($matches[1]);
            // Take first sentence or two
            $sentences = explode('.', $summary);
            return trim($sentences[0] . '.');
        }
        return null;
    }
    
    /**
     * Get default summary for a year
     * 
     * @param int $year
     * @param string $period
     * @return string Default summary
     */
    private function getDefaultYearSummary($year, $period) {
        switch ($period) {
            case 'hiatus':
                return "Part of the 2014-2025 hiatus period following personal tragedy.";
            case 'active':
                return "Active Crafty Syntax development period.";
            case 'resurgence':
                if ($year == 2025) {
                    return "Return year - WOLFIE emergence and system resurrection.";
                } elseif ($year == 2026) {
                    return "Lupopedia development sprint - 16 days, 26 versions.";
                }
                return "Resurgence period - active Lupopedia development.";
            default:
                return "Historical record year.";
        }
    }
    
    /**
     * Generate period-based navigation section
     * 
     * @return array Content lines for period navigation
     */
    private function generatePeriodNavigation() {
        $content = [];
        $years = $this->getDocumentedYears();
        
        $content[] = "## By Period";
        $content[] = "";
        
        // Active Period (1996-2013)
        $activeYears = array_filter($years, function($year) { return $year >= 1996 && $year <= 2013; });
        $content[] = "### 🏗️ Active Period (1996-2013) - Crafty Syntax Era";
        $content[] = "";
        if (!empty($activeYears)) {
            sort($activeYears);
            $content[] = "**Documented Years:** " . implode(', ', $activeYears);
            $content[] = "";
            foreach ($activeYears as $year) {
                $relativePath = str_replace($this->historyPath, '', $this->getYearFilePath($year));
                $content[] = "- [{$year}]({$relativePath}) - " . $this->getDefaultYearSummary($year, 'active');
            }
        } else {
            $content[] = "*No documentation available for this period.*";
        }
        $content[] = "";
        
        // Hiatus Period (2014-2025)
        $hiatusYears = array_filter($years, function($year) { return $year >= 2014 && $year <= 2024; });
        $content[] = "### 💤 Hiatus Period (2014-2024) - Gap Years";
        $content[] = "";
        if (!empty($hiatusYears)) {
            sort($hiatusYears);
            $content[] = "**Documented Years:** " . implode(', ', $hiatusYears);
            $content[] = "";
            foreach ($hiatusYears as $year) {
                $relativePath = str_replace($this->historyPath, '', $this->getYearFilePath($year));
                $content[] = "- [{$year}]({$relativePath}) - " . $this->getDefaultYearSummary($year, 'hiatus');
            }
        } else {
            $content[] = "*Documentation needed for this period.*";
        }
        $content[] = "";
        
        // Resurgence Period (2025-2026)
        $resurgenceYears = array_filter($years, function($year) { return $year >= 2025; });
        $content[] = "### 🚀 Resurgence Period (2025-2026) - Lupopedia Emergence";
        $content[] = "";
        if (!empty($resurgenceYears)) {
            sort($resurgenceYears);
            $content[] = "**Documented Years:** " . implode(', ', $resurgenceYears);
            $content[] = "";
            foreach ($resurgenceYears as $year) {
                $relativePath = str_replace($this->historyPath, '', $this->getYearFilePath($year));
                $content[] = "- [{$year}]({$relativePath}) - " . $this->getDefaultYearSummary($year, 'resurgence');
            }
        } else {
            $content[] = "*Documentation needed for this period.*";
        }
        $content[] = "";
        
        return $content;
    }
    
    /**
     * Generate milestone index section
     * 
     * @return array Content lines for milestone index
     */
    private function generateMilestoneIndex() {
        $content = [];
        $milestones = $this->generateTimelineMilestones();
        
        $content[] = "## Major Milestones";
        $content[] = "";
        
        foreach ($milestones as $milestone) {
            if ($milestone['type'] === 'version') {
                continue; // Skip version milestones in main list
            }
            
            $icon = $this->getMilestoneIcon($milestone['type']);
            $status = $milestone['documented'] ? '✅' : '📋';
            
            $content[] = "### {$icon} {$milestone['year']} - {$milestone['title']} {$status}";
            $content[] = "";
            $content[] = $milestone['description'];
            $content[] = "";
            
            if ($milestone['documented'] && $milestone['file_path']) {
                $relativePath = str_replace($this->historyPath, '', $milestone['file_path']);
                $content[] = "📖 [View Documentation]({$relativePath})";
                $content[] = "";
            }
        }
        
        return $content;
    }
    
    /**
     * Generate year-by-year index section
     * 
     * @return array Content lines for year index
     */
    private function generateYearIndex() {
        $content = [];
        $years = $this->getDocumentedYears();
        sort($years);
        
        $content[] = "## Complete Year Index";
        $content[] = "";
        
        if (empty($years)) {
            $content[] = "*No historical documentation found.*";
            $content[] = "";
            return $content;
        }
        
        $content[] = "| Year | Period | Status | Description |";
        $content[] = "|------|--------|--------|-------------|";
        
        // Show all years 1996-2026, marking which are documented
        for ($year = 1996; $year <= 2026; $year++) {
            $period = $this->getYearPeriod($year);
            $documented = in_array($year, $years);
            $status = $documented ? '✅ Documented' : '📋 Needed';
            $description = $this->getDefaultYearSummary($year, $period);
            
            if ($documented) {
                $relativePath = str_replace($this->historyPath, '', $this->getYearFilePath($year));
                $yearLink = "[{$year}]({$relativePath})";
            } else {
                $yearLink = $year;
            }
            
            $content[] = "| {$yearLink} | {$period} | {$status} | {$description} |";
        }
        
        $content[] = "";
        $content[] = "**Summary:**";
        $content[] = "- Total Years: 31 (1996-2026)";
        $content[] = "- Documented: " . count($years);
        $content[] = "- Needed: " . (31 - count($years));
        $content[] = "";
        
        return $content;
    }
    
    /**
     * Insert cross-references into document content
     * 
     * @param string $content Original document content
     * @param array $crossRefs Cross-reference data for this document
     * @return string Updated content with cross-references
     */
    private function insertCrossReferences($content, $crossRefs) {
        // Look for existing cross-reference section
        $crossRefPattern = '/## Cross-References.*?(?=##|$)/s';
        
        // Generate new cross-reference section
        $newCrossRefSection = $this->generateCrossReferenceSection($crossRefs);
        
        if (preg_match($crossRefPattern, $content)) {
            // Replace existing section
            $content = preg_replace($crossRefPattern, $newCrossRefSection, $content);
        } else {
            // Add new section before footer
            $footerPattern = '/---\s*\n\s*\*Generated:/';
            if (preg_match($footerPattern, $content)) {
                $content = preg_replace($footerPattern, $newCrossRefSection . "\n---\n\n*Generated:", $content);
            } else {
                // Append to end
                $content .= "\n\n" . $newCrossRefSection;
            }
        }
        
        return $content;
    }
    
    /**
     * Generate cross-reference section content
     * 
     * @param array $crossRefs Cross-reference data
     * @return string Cross-reference section content
     */
    private function generateCrossReferenceSection($crossRefs) {
        $content = [];
        
        $content[] = "## Cross-References";
        $content[] = "";
        
        // Navigation links
        $content[] = "### Navigation";
        $content[] = "";
        
        if (isset($crossRefs['previous'])) {
            $prevPath = str_replace($this->historyPath, '', $crossRefs['previous']['path']);
            $content[] = "← **Previous:** [{$crossRefs['previous']['year']}]({$prevPath})";
        }
        
        if (isset($crossRefs['next'])) {
            $nextPath = str_replace($this->historyPath, '', $crossRefs['next']['path']);
            $content[] = "→ **Next:** [{$crossRefs['next']['year']}]({$nextPath})";
        }
        
        $content[] = "";
        
        // Period and timeline links
        $content[] = "### Context";
        $content[] = "";
        $content[] = "- **Period:** " . ucfirst($crossRefs['period']);
        
        if (isset($crossRefs['period_overview'])) {
            $overviewPath = str_replace($this->historyPath, '', $crossRefs['period_overview']);
            $content[] = "- **Period Overview:** [README]({$overviewPath})";
        }
        
        if (isset($crossRefs['timeline'])) {
            $timelinePath = str_replace($this->historyPath, '', $crossRefs['timeline']['path']);
            $content[] = "- **Full Timeline:** [{$crossRefs['timeline']['title']}]({$timelinePath})";
        }
        
        $content[] = "";
        
        return implode("\n", $content);
    }
    
    /**
     * Get icon for milestone type
     * 
     * @param string $type Milestone type
     * @return string Icon emoji
     */
    private function getMilestoneIcon($type) {
        $icons = [
            'origin' => '🌱',
            'launch' => '🚀',
            'transition' => '🔄',
            'hiatus_start' => '💤',
            'resurrection' => '⚡',
            'transformation' => '🦋',
            'version' => '📦'
        ];
        
        return $icons[$type] ?? '📍';
    }
}

/**
 * Simple AtomLoader fallback class
 */
class SimpleAtomLoader {
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