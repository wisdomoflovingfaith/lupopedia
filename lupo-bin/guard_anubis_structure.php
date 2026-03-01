#!/usr/bin/env php
<?php
/**
 * ANUBIS Structure Guard
 * 
 * Enforces ANUBIS documentation governance rules
 * Prevents legacy file reintroduction and ensures canonical compliance
 * 
 * @author Windsurf (1002)
 * @version 4.0.52
 * @date 2026-02-28
 */

class AnubisStructureGuard {
    private $anubisDir = 'docs/doctrine/ANUBIS/';
    private $canonicalFile = 'docs/doctrine/ANUBIS/ANUBIS_CANONICAL.md';
    private $archiveDir = 'docs/archive/ANUBIS/pre_4.0.52/';
    private $errors = [];
    private $warnings = [];
    
    public function __construct() {
        $this->validateDirectoryStructure();
    }
    
    /**
     * Main validation entry point
     */
    public function validate() {
        $this->validateCanonicalFile();
        $this->validateArchiveStructure();
        $this->validateNoLegacyFiles();
        $this->validateFlareCompliance();
        $this->validateActorId();
        
        return $this->outputResults();
    }
    
    /**
     * Validate canonical file exists and is readable
     */
    private function validateCanonicalFile() {
        if (!file_exists($this->canonicalFile)) {
            $this->errors[] = "CRITICAL: Canonical ANUBIS file missing: {$this->canonicalFile}";
            return;
        }
        
        if (!is_readable($this->canonicalFile)) {
            $this->errors[] = "CRITICAL: Canonical ANUBIS file not readable: {$this->canonicalFile}";
            return;
        }
        
        echo "✅ Canonical file exists: {$this->canonicalFile}\n";
    }
    
    /**
     * Validate archive directory structure
     */
    private function validateArchiveStructure() {
        if (!is_dir($this->archiveDir)) {
            $this->errors[] = "WARNING: Archive directory missing: {$this->archiveDir}";
            return;
        }
        
        $archivedFiles = glob($this->archiveDir . 'ANUBIS_*.md');
        $expectedFiles = [
            'ANUBIS_IMPLEMENTATION_SUMMARY.md',
            'ANUBIS_ORPHAN_RULES.md',
            'ANUBIS_OVERVIEW.md',
            'ANUBIS_PROGRAM_SPEC.md',
            'LILITH_ANUBIS_GUIDANCE.md',
            'LILITH_ANUBIS_GUIDANCE_FLIP.md'
        ];
        
        foreach ($expectedFiles as $file) {
            if (!in_array($file, $archivedFiles)) {
                $this->warnings[] = "Expected archived file not found: {$file}";
            }
        }
        
        echo "✅ Archive directory validated: {$this->archiveDir}\n";
    }
    
    /**
     * Validate no legacy ANUBIS files exist outside archive
     */
    private function validateNoLegacyFiles() {
        $anubisFiles = glob($this->anubisDir . 'ANUBIS_*.md');
        
        foreach ($anubisFiles as $file) {
            $fullPath = $this->anubisDir . $file;
            
            // Skip canonical and guard files
            if ($file === 'ANUBIS_CANONICAL.md' || $file === 'LEGACY_FILE_GUARD.md') {
                continue;
            }
            
            $this->errors[] = "CRITICAL: Legacy ANUBIS file found outside archive: {$fullPath}";
        }
        
        if (empty($this->errors)) {
            echo "✅ No legacy ANUBIS files found outside archive\n";
        }
    }
    
    /**
     * Validate FLARE header compliance in canonical file
     */
    private function validateFlareCompliance() {
        $content = file_get_contents($this->canonicalFile);
        
        // Check for FLARE header start
        if (strpos($content, '---') === false) {
            $this->errors[] = "CRITICAL: No FLARE header found in canonical file";
            return;
        }
        
        // Check for required FLARE fields
        $requiredFields = [
            'flare.headers:',
            'file_path_from_root:',
            'system_version:',
            'actor_id:',
            'flare.footer:'
        ];
        
        foreach ($requiredFields as $field) {
            if (strpos($content, $field) === false) {
                $this->errors[] = "CRITICAL: Missing required FLARE field: {$field}";
            }
        }
        
        if (empty($this->errors)) {
            echo "✅ FLARE header compliance validated\n";
        }
    }
    
    /**
     * Validate ANUBIS actor_id is 19
     */
    private function validateActorId() {
        $content = file_get_contents($this->canonicalFile);
        
        // Look for actor_id in FLARE header
        if (preg_match('/actor_id:\s*["\']?(\d+)["\']?/', $content, $matches)) {
            $actorId = (int)$matches[1];
            
            if ($actorId !== 19) {
                $this->errors[] = "CRITICAL: ANUBIS actor_id is {$actorId}, expected 19";
            } else {
                echo "✅ ANUBIS actor_id validated: 19\n";
            }
        } else {
            $this->errors[] = "CRITICAL: Could not find actor_id in canonical file";
        }
        
        // Also check for explicit anchoring
        if (strpos($content, 'ANUBIS actor_id: 19') === false) {
            $this->warnings[] = "WARNING: No explicit ANUBIS actor_id anchoring found";
        }
    }
    
    /**
     * Validate directory structure exists
     */
    private function validateDirectoryStructure() {
        if (!is_dir($this->anubisDir)) {
            $this->errors[] = "CRITICAL: ANUBIS directory missing: {$this->anubisDir}";
        }
    }
    
    /**
     * Output validation results
     */
    private function outputResults() {
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "ANUBIS STRUCTURE GUARD VALIDATION RESULTS\n";
        echo str_repeat("=", 60) . "\n\n";
        
        if (!empty($this->errors)) {
            echo "🔴 CRITICAL ERRORS:\n";
            foreach ($this->errors as $error) {
                echo "  - {$error}\n";
            }
        }
        
        if (!empty($this->warnings)) {
            echo "🟡 WARNINGS:\n";
            foreach ($this->warnings as $warning) {
                echo "  - {$warning}\n";
            }
        }
        
        if (empty($this->errors) && empty($this->warnings)) {
            echo "✅ ALL VALIDATIONS PASSED\n";
        }
        
        echo "\nSUMMARY:\n";
        echo "Errors: " . count($this->errors) . "\n";
        echo "Warnings: " . count($this->warnings) . "\n";
        echo "Status: " . (empty($this->errors) ? 'PASS' : 'FAIL') . "\n\n";
        
        return empty($this->errors) ? 0 : 1;
    }
}

// Main execution
$guard = new AnubisStructureGuard();
exit($guard->validate());
