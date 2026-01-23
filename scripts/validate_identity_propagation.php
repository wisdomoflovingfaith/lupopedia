<?php
/**
 * Identity Propagation Validator
 * 
 * Validates that Captain Wolfie identity is consistently propagated
 * throughout the system.
 * 
 * @package Lupopedia
 * @version 4.0.20
 * @author Captain Wolfie
 */

class IdentityPropagationValidator {
    private $checks = [];
    private $baseDir;
    
    public function __construct($baseDir = null) {
        $this->baseDir = $baseDir ?: dirname(__DIR__);
    }
    
    /**
     * Run all validation checks
     * 
     * @return array Validation results
     */
    public function runAll(): array {
        $this->checkDialogHeaders();
        $this->checkDocumentation();
        $this->checkConstants();
        $this->checkLogging();
        
        return [
            'total_checks' => count($this->checks),
            'passed' => array_filter($this->checks, fn($c) => $c['passed']),
            'failed' => array_filter($this->checks, fn($c) => !$c['passed']),
        ];
    }
    
    /**
     * Check dialog headers for consistency
     */
    private function checkDialogHeaders() {
        $dialogsDir = $this->baseDir . '/dialogs';
        $files = glob($dialogsDir . '/*.md');
        $inconsistent = [];
        $total = count($files);
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            
            // Check if file has Captain_Wolfie speaker
            if (strpos($content, 'speaker: Captain_Wolfie') !== false) {
                // Must have author_type: human
                if (strpos($content, 'author_type: human') === false) {
                    $inconsistent[] = basename($file);
                }
            }
            
            // Check for old speaker names
            if (preg_match('/speaker:\s*(WOLFIE|Wolfie|Eric)\b/i', $content)) {
                $inconsistent[] = basename($file) . ' (old speaker name)';
            }
        }
        
        $this->checks[] = [
            'name' => 'Dialog Headers',
            'passed' => empty($inconsistent),
            'details' => empty($inconsistent) 
                ? "All $total dialog files consistent" 
                : count($inconsistent) . " inconsistent files: " . implode(', ', array_slice($inconsistent, 0, 5)),
            'files' => $inconsistent
        ];
    }
    
    /**
     * Check documentation for architect references
     */
    private function checkDocumentation() {
        $docsDir = $this->baseDir . '/docs';
        $files = glob($docsDir . '/**/*.md', GLOB_BRACE);
        $missingArchitect = [];
        $hasCaptainWolfie = 0;
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            
            if (strpos($content, 'Captain Wolfie') !== false) {
                $hasCaptainWolfie++;
                // Check if architect field is present in header
                if (strpos($content, 'architect:') === false && 
                    strpos($content, 'author:') !== false) {
                    // Author exists but architect might be missing
                    // This is acceptable if author is Captain Wolfie
                    if (strpos($content, 'author: Captain Wolfie') === false) {
                        $missingArchitect[] = basename($file);
                    }
                }
            }
        }
        
        $this->checks[] = [
            'name' => 'Documentation',
            'passed' => empty($missingArchitect),
            'details' => "$hasCaptainWolfie files reference Captain Wolfie, " . 
                        (empty($missingArchitect) ? 'all consistent' : count($missingArchitect) . ' may need architect field'),
            'files' => $missingArchitect
        ];
    }
    
    /**
     * Check constants file
     */
    private function checkConstants() {
        $constantsFile = $this->baseDir . '/config/constants.php';
        $passed = false;
        $details = 'Constants file not found';
        
        if (file_exists($constantsFile)) {
            $content = file_get_contents($constantsFile);
            $hasArchitectPersona = strpos($content, "ARCHITECT_PERSONA") !== false;
            $hasArchitectSignature = strpos($content, "ARCHITECT_SIGNATURE") !== false;
            $hasCaptainWolfie = strpos($content, "Captain Wolfie") !== false;
            
            $passed = $hasArchitectPersona && $hasArchitectSignature && $hasCaptainWolfie;
            $details = $passed 
                ? 'All architect constants defined'
                : 'Missing: ' . 
                  (!$hasArchitectPersona ? 'ARCHITECT_PERSONA ' : '') .
                  (!$hasArchitectSignature ? 'ARCHITECT_SIGNATURE ' : '') .
                  (!$hasCaptainWolfie ? 'Captain Wolfie reference' : '');
        }
        
        $this->checks[] = [
            'name' => 'Constants',
            'passed' => $passed,
            'details' => $details
        ];
    }
    
    /**
     * Check logging system
     */
    private function checkLogging() {
        $loggerFile = $this->baseDir . '/lupo-includes/system/logging/ArchitectLogger.php';
        $passed = file_exists($loggerFile);
        $details = $passed ? 'ArchitectLogger class exists' : 'ArchitectLogger class not found';
        
        if ($passed) {
            $content = file_get_contents($loggerFile);
            $hasSignature = strpos($content, '[CW]') !== false;
            $hasArchitectPersona = strpos($content, 'ARCHITECT_PERSONA') !== false;
            
            $passed = $hasSignature && $hasArchitectPersona;
            $details = $passed 
                ? 'ArchitectLogger properly configured'
                : 'Missing: ' . 
                  (!$hasSignature ? '[CW] signature ' : '') .
                  (!$hasArchitectPersona ? 'ARCHITECT_PERSONA constant' : '');
        }
        
        $this->checks[] = [
            'name' => 'Logging',
            'passed' => $passed,
            'details' => $details
        ];
    }
    
    /**
     * Print validation results
     */
    public function printResults() {
        $results = $this->runAll();
        
        echo "\n";
        echo "========================================\n";
        echo "Identity Propagation Validation\n";
        echo "========================================\n\n";
        
        foreach ($results['passed'] as $check) {
            echo "✓ {$check['name']}: {$check['details']}\n";
        }
        
        if (!empty($results['failed'])) {
            echo "\n";
            foreach ($results['failed'] as $check) {
                echo "✗ {$check['name']}: {$check['details']}\n";
            }
        }
        
        echo "\n";
        echo "Total checks: {$results['total_checks']}\n";
        echo "Passed: " . count($results['passed']) . "\n";
        echo "Failed: " . count($results['failed']) . "\n";
        echo "\n";
    }
}

// Run validation if executed directly
if (php_sapi_name() === 'cli') {
    $validator = new IdentityPropagationValidator();
    $validator->printResults();
}
