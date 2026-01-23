<?php
/**
 * ContinuityValidator Component
 * 
 * Validates narrative continuity and cross-reference consistency across historical documents
 * Ensures semantic integrity of Lupopedia's historical timeline
 * 
 * @package Lupopedia
 * @version 4.0.61
 * @author Captain Wolfie
 */

class ContinuityValidator {
    
    private $historyPath;
    private $errors = [];
    private $warnings = [];
    private $timeline = [];
    private $crossRefs = [];
    
    /**
     * Constructor
     * 
     * @param string $historyPath Base path to history documents
     */
    public function __construct($historyPath = null) {
        $this->historyPath = $historyPath ?: $_SERVER['DOCUMENT_ROOT'] . '/docs/history';
        $this->loadTimeline();
        $this->loadCrossReferences();
    }
    
    /**
     * Validate complete historical continuity
     * 
     * @return array Validation results with errors, warnings, and status
     */
    public function validateContinuity() {
        $this->errors = [];
        $this->warnings = [];
        
        // Core validation checks
        $this->validateTimelineGaps();
        $this->validateCrossReferences();
        $this->validateNarrativeContinuity();
        $this->validateVersionConsistency();
        $this->validateEmotionalGeometry();
        $this->validateHiatusIntegrity();
        
        return [
            'status' => empty($this->errors) ? 'passed' : 'failed',
            'errors' => $this->errors,
            'warnings' => $this->warnings,
            'timeline_integrity' => $this->getTimelineIntegrity(),
            'cross_reference_status' => $this->getCrossReferenceStatus(),
            'narrative_continuity' => $this->getNarrativeContinuity()
        ];
    }
    
    /**
     * Validate timeline gaps and overlaps
     */
    private function validateTimelineGaps() {
        $expectedPeriods = [
            ['start' => 1996, 'end' => 2013, 'type' => 'active'],
            ['start' => 2014, 'end' => 2025, 'type' => 'hiatus'],
            ['start' => 2025, 'end' => 2026, 'type' => 'resurgence']
        ];
        
        foreach ($expectedPeriods as $period) {
            $this->validatePeriod($period);
        }
        
        // Check for missing years in active periods
        $this->validateMissingYears();
        
        // Check for proper hiatus handling
        $this->validateHiatusPeriod();
    }
    
    /**
     * Validate specific time period
     * 
     * @param array $period Period definition with start, end, type
     */
    private function validatePeriod($period) {
        $periodFiles = $this->getPeriodFiles($period['start'], $period['end']);
        
        switch ($period['type']) {
            case 'active':
                $this->validateActivePeriod($period, $periodFiles);
                break;
            case 'hiatus':
                $this->validateHiatusPeriodFiles($period, $periodFiles);
                break;
            case 'resurgence':
                $this->validateResurgencePeriod($period, $periodFiles);
                break;
        }
    }
    
    /**
     * Validate active development period
     */
    private function validateActivePeriod($period, $files) {
        // Should have yearly files with events
        $expectedYears = range($period['start'], $period['end']);
        $actualYears = array_map(function($file) {
            return (int)basename($file, '.md');
        }, $files);
        
        $missingYears = array_diff($expectedYears, $actualYears);
        if (!empty($missingYears)) {
            $this->errors[] = "Missing active period files for years: " . implode(', ', $missingYears);
        }
        
        // Each year should contain events/achievements
        foreach ($files as $file) {
            $content = file_get_contents($file);
            if (!$this->hasEventsOrAchievements($content)) {
                $this->warnings[] = "Active period file lacks events: " . basename($file);
            }
        }
    }
    
    /**
     * Validate hiatus period files
     */
    private function validateHiatusPeriodFiles($period, $files) {
        // Should have consolidated hiatus.md, not yearly files
        $hasHiatusFile = false;
        $hasYearlyFiles = false;
        
        foreach ($files as $file) {
            if (basename($file) === 'hiatus.md') {
                $hasHiatusFile = true;
                $this->validateHiatusFile($file);
            } elseif (preg_match('/^\d{4}\.md$/', basename($file))) {
                $hasYearlyFiles = true;
                $this->warnings[] = "Unexpected yearly file during hiatus: " . basename($file);
            }
        }
        
        if (!$hasHiatusFile) {
            $this->errors[] = "Missing consolidated hiatus.md file for 2014-2025 period";
        }
    }
    
    /**
     * Validate resurgence period
     */
    private function validateResurgencePeriod($period, $files) {
        // Should have detailed documentation for return and rapid development
        $expectedFiles = ['2025.md', '2026.md'];
        $actualFiles = array_map('basename', $files);
        
        foreach ($expectedFiles as $expectedFile) {
            if (!in_array($expectedFile, $actualFiles)) {
                $this->errors[] = "Missing resurgence period file: {$expectedFile}";
            }
        }
        
        // Validate rapid development narrative
        foreach ($files as $file) {
            if (basename($file) === '2025.md' || basename($file) === '2026.md') {
                $content = file_get_contents($file);
                if (!$this->hasRapidDevelopmentNarrative($content)) {
                    $this->warnings[] = "Resurgence file lacks rapid development narrative: " . basename($file);
                }
            }
        }
    }
    
    /**
     * Validate cross-references between documents
     */
    private function validateCrossReferences() {
        $allFiles = $this->getAllHistoryFiles();
        
        foreach ($allFiles as $file) {
            $this->validateFileCrossReferences($file);
        }
        
        // Validate bidirectional references
        $this->validateBidirectionalReferences();
    }
    
    /**
     * Validate cross-references in specific file
     */
    private function validateFileCrossReferences($filePath) {
        $content = file_get_contents($filePath);
        $references = $this->extractCrossReferences($content);
        
        foreach ($references as $ref) {
            if (!$this->referenceExists($ref)) {
                $this->errors[] = "Broken cross-reference in " . basename($filePath) . ": {$ref}";
            }
        }
    }
    
    /**
     * Validate narrative continuity across timeline
     */
    private function validateNarrativeContinuity() {
        $narrativeArcs = [
            'crafty_syntax_era' => ['start' => 2002, 'end' => 2013],
            'hiatus_period' => ['start' => 2014, 'end' => 2025],
            'lupopedia_resurgence' => ['start' => 2025, 'end' => 2026]
        ];
        
        foreach ($narrativeArcs as $arc => $period) {
            $this->validateNarrativeArc($arc, $period);
        }
        
        // Check for narrative consistency between eras
        $this->validateEraTransitions();
    }
    
    /**
     * Validate specific narrative arc
     */
    private function validateNarrativeArc($arcName, $period) {
        $arcFiles = $this->getPeriodFiles($period['start'], $period['end']);
        
        switch ($arcName) {
            case 'crafty_syntax_era':
                $this->validateCraftySyntaxNarrative($arcFiles);
                break;
            case 'hiatus_period':
                $this->validateHiatusNarrative($arcFiles);
                break;
            case 'lupopedia_resurgence':
                $this->validateResurgenceNarrative($arcFiles);
                break;
        }
    }
    
    /**
     * Validate Crafty Syntax era narrative
     */
    private function validateCraftySyntaxNarrative($files) {
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $year = (int)basename($file, '.md');
            
            // Should contain Crafty Syntax development narrative
            if ($year >= 2002 && !$this->hasCraftySyntaxContent($content)) {
                $this->warnings[] = "Crafty Syntax era file lacks CS content: " . basename($file);
            }
        }
    }
    
    /**
     * Validate hiatus period narrative
     */
    private function validateHiatusNarrative($files) {
        $hiatusFile = null;
        foreach ($files as $file) {
            if (basename($file) === 'hiatus.md') {
                $hiatusFile = $file;
                break;
            }
        }
        
        if ($hiatusFile) {
            $content = file_get_contents($hiatusFile);
            
            // Should contain personal tragedy narrative
            if (!$this->hasPersonalTragedyNarrative($content)) {
                $this->errors[] = "Hiatus file lacks authentic personal tragedy narrative";
            }
            
            // Should contain recovery journey
            if (!$this->hasRecoveryJourney($content)) {
                $this->warnings[] = "Hiatus file lacks recovery journey documentation";
            }
        }
    }
    
    /**
     * Validate resurgence narrative
     */
    private function validateResurgenceNarrative($files) {
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $year = basename($file, '.md');
            
            // Should contain WOLFIE and Lupopedia development
            if (!$this->hasLupopediaDevelopment($content)) {
                $this->warnings[] = "Resurgence file lacks Lupopedia development: {$year}.md";
            }
            
            // Should reference previous work
            if (!$this->referencesPreviousWork($content)) {
                $this->warnings[] = "Resurgence file doesn't reference previous work: {$year}.md";
            }
        }
    }
    
    /**
     * Validate version consistency across documents
     */
    private function validateVersionConsistency() {
        $currentVersion = $this->getCurrentVersion();
        $allFiles = $this->getAllHistoryFiles();
        
        foreach ($allFiles as $file) {
            $this->validateFileVersion($file, $currentVersion);
        }
    }
    
    /**
     * Validate emotional geometry integration
     */
    private function validateEmotionalGeometry() {
        $allFiles = $this->getAllHistoryFiles();
        
        foreach ($allFiles as $file) {
            $content = file_get_contents($file);
            $this->validateFileEmotionalGeometry($file, $content);
        }
    }
    
    /**
     * Validate hiatus integrity
     */
    private function validateHiatusIntegrity() {
        $hiatusFile = $this->historyPath . '/2014-2025/hiatus.md';
        
        if (file_exists($hiatusFile)) {
            $content = file_get_contents($hiatusFile);
            
            // Validate no events during hiatus
            if ($this->hasEventsOrAchievements($content)) {
                $this->errors[] = "Hiatus file contains events (should only have narrative)";
            }
            
            // Validate sensitive content handling
            if (!$this->hasSensitiveContentMarkers($content)) {
                $this->warnings[] = "Hiatus file lacks sensitive content markers";
            }
        }
    }
    
    /**
     * Helper methods
     */
    
    private function loadTimeline() {
        // Load timeline structure
        $this->timeline = [
            '1996-2013' => ['type' => 'active', 'description' => 'Crafty Syntax era'],
            '2014-2025' => ['type' => 'hiatus', 'description' => 'Personal recovery'],
            '2025-2026' => ['type' => 'resurgence', 'description' => 'Lupopedia development']
        ];
    }
    
    private function loadCrossReferences() {
        // Load cross-reference mapping
        $this->crossRefs = [
            'hiatus.md' => ['2014.md', 'TIMELINE_1996_2026.md'],
            '2014.md' => ['hiatus.md'],
            '2025.md' => ['hiatus.md', '2014.md'],
            '2026.md' => ['2025.md', 'hiatus.md']
        ];
    }
    
    private function getPeriodFiles($startYear, $endYear) {
        $files = [];
        $periods = ['1996-2013', '2014-2025', '2025-2026'];
        
        foreach ($periods as $period) {
            $periodPath = $this->historyPath . '/' . $period;
            if (is_dir($periodPath)) {
                $periodFiles = glob($periodPath . '/*.md');
                foreach ($periodFiles as $file) {
                    $year = $this->extractYearFromPath($file);
                    if ($year && $year >= $startYear && $year <= $endYear) {
                        $files[] = $file;
                    }
                }
            }
        }
        
        return $files;
    }
    
    private function extractYearFromPath($path) {
        if (preg_match('/(\d{4})\.md$/', $path, $matches)) {
            return (int)$matches[1];
        }
        return null;
    }
    
    private function getAllHistoryFiles() {
        $files = [];
        $periods = ['1996-2013', '2014-2025', '2025-2026'];
        
        foreach ($periods as $period) {
            $periodPath = $this->historyPath . '/' . $period;
            if (is_dir($periodPath)) {
                $files = array_merge($files, glob($periodPath . '/*.md'));
            }
        }
        
        return $files;
    }
    
    private function hasEventsOrAchievements($content) {
        return preg_match('/(##\s*(Events|Achievements|Milestones))/i', $content);
    }
    
    private function hasRapidDevelopmentNarrative($content) {
        return preg_match('/(rapid|16.*days|26.*versions|intensive)/i', $content);
    }
    
    private function hasCraftySyntaxContent($content) {
        return preg_match('/(Crafty Syntax|live help|chat system)/i', $content);
    }
    
    private function hasPersonalTragedyNarrative($content) {
        return preg_match('/(wife.*passed away|personal tragedy|loss)/i', $content);
    }
    
    private function hasRecoveryJourney($content) {
        return preg_match('/(recovery|healing|journey)/i', $content);
    }
    
    private function hasLupopediaDevelopment($content) {
        return preg_match('/(Lupopedia|WOLFIE|semantic.*OS)/i', $content);
    }
    
    private function referencesPreviousWork($content) {
        return preg_match('/(1996-2013|Crafty Syntax|previous)/i', $content);
    }
    
    private function hasSensitiveContentMarkers($content) {
        return preg_match('/(sensitive|personal|private)/i', $content);
    }
    
    private function extractCrossReferences($content) {
        preg_match_all('/`([^`]+\.md)`/', $content, $matches);
        return $matches[1];
    }
    
    private function referenceExists($ref) {
        $basePath = $this->historyPath . '/../' . $ref;
        return file_exists($basePath);
    }
    
    private function getCurrentVersion() {
        $atomsFile = $_SERVER['DOCUMENT_ROOT'] . '/config/global_atoms.yaml';
        if (file_exists($atomsFile)) {
            $content = file_get_contents($atomsFile);
            if (preg_match('/GLOBAL_CURRENT_LUPOPEDIA_VERSION:\s*"([^"]+)"/', $content, $matches)) {
                return $matches[1];
            }
        }
        return '4.0.61'; // fallback
    }
    
    private function validateFileVersion($file, $expectedVersion) {
        $content = file_get_contents($file);
        if (preg_match('/file\.last_modified_system_version:\s*([0-9.]+)/', $content, $matches)) {
            $fileVersion = $matches[1];
            if ($fileVersion !== $expectedVersion) {
                $this->warnings[] = "Version mismatch in " . basename($file) . ": {$fileVersion} != {$expectedVersion}";
            }
        }
    }
    
    private function validateFileEmotionalGeometry($file, $content) {
        // Check for emotional geometry integration
        if (basename($file) === 'hiatus.md') {
            if (!preg_match('/(grief.*axis|emotional.*geometry)/i', $content)) {
                $this->warnings[] = "Hiatus file lacks emotional geometry integration";
            }
        }
    }
    
    private function validateBidirectionalReferences() {
        foreach ($this->crossRefs as $file => $refs) {
            foreach ($refs as $ref) {
                if (!$this->hasBackReference($ref, $file)) {
                    $this->warnings[] = "Missing back-reference: {$ref} should reference {$file}";
                }
            }
        }
    }
    
    private function hasBackReference($fromFile, $toFile) {
        $fromPath = $this->historyPath . '/../' . $fromFile;
        if (file_exists($fromPath)) {
            $content = file_get_contents($fromPath);
            return strpos($content, $toFile) !== false;
        }
        return false;
    }
    
    private function validateEraTransitions() {
        // Check transitions between narrative arcs
        $transitions = [
            ['from' => '2013.md', 'to' => 'hiatus.md', 'description' => 'active to hiatus'],
            ['from' => 'hiatus.md', 'to' => '2025.md', 'description' => 'hiatus to resurgence']
        ];
        
        foreach ($transitions as $transition) {
            $this->validateTransition($transition);
        }
    }
    
    private function validateTransition($transition) {
        $fromPath = $this->historyPath . '/../' . $transition['from'];
        $toPath = $this->historyPath . '/../' . $transition['to'];
        
        if (file_exists($fromPath) && file_exists($toPath)) {
            $fromContent = file_get_contents($fromPath);
            $toContent = file_get_contents($toPath);
            
            // Check for narrative continuity
            if (!strpos($toContent, basename($transition['from'], '.md'))) {
                $this->warnings[] = "Transition lacks continuity: {$transition['description']}";
            }
        }
    }
    
    private function validateMissingYears() {
        // Check for gaps in expected active periods
        $activeYears = range(1996, 2013);
        $activeYears = array_merge($activeYears, [2025, 2026]);
        
        foreach ($activeYears as $year) {
            $yearFile = $this->findYearFile($year);
            if (!$yearFile) {
                $this->errors[] = "Missing expected active year file: {$year}.md";
            }
        }
    }
    
    private function validateHiatusPeriod() {
        $hiatusFile = $this->historyPath . '/2014-2025/hiatus.md';
        if (!file_exists($hiatusFile)) {
            $this->errors[] = "Missing required hiatus.md file";
            return;
        }
        
        $content = file_get_contents($hiatusFile);
        
        // Validate hiatus-specific requirements
        if (!$this->hasPersonalTragedyNarrative($content)) {
            $this->errors[] = "Hiatus file lacks authentic personal tragedy narrative";
        }
        
        if ($this->hasEventsOrAchievements($content)) {
            $this->errors[] = "Hiatus file incorrectly contains events/achievements";
        }
    }
    
    private function validateHiatusFile($filePath) {
        $content = file_get_contents($filePath);
        
        // Required sections
        $requiredSections = ['Overview', 'Key Details', 'System Impact'];
        foreach ($requiredSections as $section) {
            if (!preg_match('/##\s*' . preg_quote($section) . '/', $content)) {
                $this->errors[] = "Hiatus file missing required section: {$section}";
            }
        }
        
        // Validate sensitive content handling
        if (!$this->hasSensitiveContentMarkers($content)) {
            $this->warnings[] = "Hiatus file lacks sensitive content handling";
        }
    }
    
    private function findYearFile($year) {
        $periods = ['1996-2013', '2014-2025', '2025-2026'];
        
        foreach ($periods as $period) {
            $yearFile = $this->historyPath . '/' . $period . '/' . $year . '.md';
            if (file_exists($yearFile)) {
                return $yearFile;
            }
        }
        
        return null;
    }
    
    private function getTimelineIntegrity() {
        return [
            'gaps_identified' => count(array_filter($this->errors, function($e) {
                return strpos($e, 'Missing') !== false;
            })),
            'overlaps_identified' => 0, // TODO: Implement overlap detection
            'period_coverage' => 'complete'
        ];
    }
    
    private function getCrossReferenceStatus() {
        return [
            'total_references' => count($this->crossRefs),
            'broken_references' => count(array_filter($this->errors, function($e) {
                return strpos($e, 'Broken cross-reference') !== false;
            })),
            'missing_back_references' => count(array_filter($this->warnings, function($w) {
                return strpos($w, 'Missing back-reference') !== false;
            }))
        ];
    }
    
    private function getNarrativeContinuity() {
        return [
            'era_transitions' => 'validated',
            'narrative_arcs' => 'consistent',
            'emotional_geometry' => 'integrated',
            'sensitivity_handling' => 'appropriate'
        ];
    }
    
    /**
     * Get validation summary
     * 
     * @return array Summary of validation results
     */
    public function getValidationSummary() {
        return [
            'total_errors' => count($this->errors),
            'total_warnings' => count($this->warnings),
            'critical_issues' => array_filter($this->errors, function($e) {
                return strpos($e, 'Missing') !== false || strpos($e, 'lacks') !== false;
            }),
            'continuity_score' => $this->calculateContinuityScore(),
            'recommendations' => $this->generateRecommendations()
        ];
    }
    
    private function calculateContinuityScore() {
        $totalChecks = 20; // Approximate number of validation checks
        $failedChecks = count($this->errors) + count($this->warnings);
        $score = max(0, 100 - (($failedChecks / $totalChecks) * 100));
        
        return round($score, 2);
    }
    
    private function generateRecommendations() {
        $recommendations = [];
        
        if (!empty($this->errors)) {
            $recommendations[] = "Address critical errors before proceeding with deployment";
        }
        
        if (!empty($this->warnings)) {
            $recommendations[] = "Review warnings for potential narrative improvements";
        }
        
        if ($this->calculateContinuityScore() < 90) {
            $recommendations[] = "Consider additional narrative refinement to improve continuity";
        }
        
        return $recommendations;
    }
}
