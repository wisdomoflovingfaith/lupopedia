<?php
/**
 * ContinuityValidator Test Suite
 * 
 * Comprehensive testing for historical continuity validation
 * 
 * @package Lupopedia
 * @version 4.0.61
 * @author Captain Wolfie
 */

require_once __DIR__ . '/../lupo-includes/classes/ContinuityValidator.php';

class ContinuityValidatorTest {
    
    private $validator;
    private $testHistoryPath;
    private $testResults = [];
    
    public function __construct() {
        $this->testHistoryPath = __DIR__ . '/../test-data/history';
        $this->validator = new ContinuityValidator($this->testHistoryPath);
        $this->setupTestData();
    }
    
    /**
     * Run all tests
     */
    public function runAllTests() {
        echo "=== ContinuityValidator Test Suite ===\n\n";
        
        $this->testTimelineGapValidation();
        $this->testCrossReferenceValidation();
        $this->testNarrativeContinuity();
        $this->testHiatusIntegrity();
        $this->testVersionConsistency();
        $this->testEmotionalGeometry();
        $this->testErrorHandling();
        
        $this->printResults();
    }
    
    /**
     * Test timeline gap validation
     */
    public function testTimelineGapValidation() {
        echo "Testing Timeline Gap Validation...\n";
        
        // Test complete timeline
        $result = $this->validator->validateContinuity();
        $this->assert($result['status'] === 'passed', 'Complete timeline should pass');
        
        // Test missing active year
        $this->removeTestFile('1996-2013/1998.md');
        $result = $this->validator->validateContinuity();
        $this->assert($result['status'] === 'failed', 'Missing active year should fail');
        $this->restoreTestFile('1996-2013/1998.md');
        
        // Test missing hiatus file
        $this->removeTestFile('2014-2025/hiatus.md');
        $result = $this->validator->validateContinuity();
        $this->assert($result['status'] === 'failed', 'Missing hiatus file should fail');
        $this->restoreTestFile('2014-2025/hiatus.md');
        
        $this->testResults['timeline_gaps'] = 'PASSED';
        echo "✓ Timeline Gap Validation tests passed\n\n";
    }
    
    /**
     * Test cross-reference validation
     */
    public function testCrossReferenceValidation() {
        echo "Testing Cross-Reference Validation...\n";
        
        // Test valid cross-references
        $result = $this->validator->validateContinuity();
        $this->assert(empty($result['errors']), 'Valid cross-references should not generate errors');
        
        // Test broken cross-reference
        $this->addBrokenReference('2014.md', '`nonexistent.md`');
        $result = $this->validator->validateContinuity();
        $this->assert($result['status'] === 'failed', 'Broken cross-reference should fail');
        $this->removeBrokenReference('2014.md');
        
        // Test missing back-reference
        $this->removeBackReference('hiatus.md', '2014.md');
        $result = $this->validator->validateContinuity();
        $this->assert(!empty($result['warnings']), 'Missing back-reference should generate warning');
        $this->restoreBackReference('hiatus.md', '2014.md');
        
        $this->testResults['cross_references'] = 'PASSED';
        echo "✓ Cross-Reference Validation tests passed\n\n";
    }
    
    /**
     * Test narrative continuity
     */
    public function testNarrativeContinuity() {
        echo "Testing Narrative Continuity...\n";
        
        // Test Crafty Syntax era narrative
        $this->validateCraftySyntaxNarrative();
        
        // Test hiatus narrative
        $this->validateHiatusNarrative();
        
        // Test resurgence narrative
        $this->validateResurgenceNarrative();
        
        // Test era transitions
        $this->validateEraTransitions();
        
        $this->testResults['narrative_continuity'] = 'PASSED';
        echo "✓ Narrative Continuity tests passed\n\n";
    }
    
    /**
     * Test hiatus integrity
     */
    public function testHiatusIntegrity() {
        echo "Testing Hiatus Integrity...\n";
        
        // Test hiatus file has no events
        $hiatusContent = file_get_contents($this->testHistoryPath . '/2014-2025/hiatus.md');
        $this->assert(!$this->hasEventsOrAchievements($hiatusContent), 'Hiatus file should not contain events');
        
        // Test hiatus file has personal tragedy narrative
        $this->assert($this->hasPersonalTragedyNarrative($hiatusContent), 'Hiatus file should have personal tragedy narrative');
        
        // Test hiatus file has recovery journey
        $this->assert($this->hasRecoveryJourney($hiatusContent), 'Hiatus file should have recovery journey');
        
        // Test hiatus file has sensitive content markers
        $this->assert($this->hasSensitiveContentMarkers($hiatusContent), 'Hiatus file should have sensitive content markers');
        
        $this->testResults['hiatus_integrity'] = 'PASSED';
        echo "✓ Hiatus Integrity tests passed\n\n";
    }
    
    /**
     * Test version consistency
     */
    public function testVersionConsistency() {
        echo "Testing Version Consistency...\n";
        
        // Test all files have consistent version
        $result = $this->validator->validateContinuity();
        $versionWarnings = array_filter($result['warnings'], function($w) {
            return strpos($w, 'Version mismatch') !== false;
        });
        $this->assert(empty($versionWarnings), 'All files should have consistent version');
        
        // Test version mismatch detection
        $this->changeFileVersion('2014.md', '4.0.50');
        $result = $this->validator->validateContinuity();
        $versionWarnings = array_filter($result['warnings'], function($w) {
            return strpos($w, 'Version mismatch') !== false;
        });
        $this->assert(!empty($versionWarnings), 'Version mismatch should generate warning');
        $this->restoreFileVersion('2014.md');
        
        $this->testResults['version_consistency'] = 'PASSED';
        echo "✓ Version Consistency tests passed\n\n";
    }
    
    /**
     * Test emotional geometry integration
     */
    public function testEmotionalGeometry() {
        echo "Testing Emotional Geometry Integration...\n";
        
        // Test hiatus file has emotional geometry
        $hiatusContent = file_get_contents($this->testHistoryPath . '/2014-2025/hiatus.md');
        $this->assert(preg_match('/(grief.*axis|emotional.*geometry)/i', $hiatusContent), 'Hiatus file should have emotional geometry');
        
        // Test emotional geometry validation
        $result = $this->validator->validateContinuity();
        $emotionalWarnings = array_filter($result['warnings'], function($w) {
            return strpos($w, 'emotional geometry') !== false;
        });
        $this->assert(empty($emotionalWarnings), 'Emotional geometry should be properly integrated');
        
        $this->testResults['emotional_geometry'] = 'PASSED';
        echo "✓ Emotional Geometry Integration tests passed\n\n";
    }
    
    /**
     * Test error handling
     */
    public function testErrorHandling() {
        echo "Testing Error Handling...\n";
        
        // Test missing history directory
        $badValidator = new ContinuityValidator('/nonexistent/path');
        $result = $badValidator->validateContinuity();
        $this->assert($result['status'] === 'failed', 'Missing directory should fail gracefully');
        
        // Test corrupted file handling
        $this->createCorruptedFile('2014-2025/corrupted.md');
        $result = $this->validator->validateContinuity();
        $this->assert($result['status'] === 'failed', 'Corrupted file should fail gracefully');
        $this->removeCorruptedFile('2014-2025/corrupted.md');
        
        // Test empty file handling
        $this->createEmptyFile('2014-2025/empty.md');
        $result = $this->validator->validateContinuity();
        $this->assert(!empty($result['warnings']), 'Empty file should generate warnings');
        $this->removeEmptyFile('2014-2025/empty.md');
        
        $this->testResults['error_handling'] = 'PASSED';
        echo "✓ Error Handling tests passed\n\n";
    }
    
    /**
     * Helper methods for testing
     */
    
    private function setupTestData() {
        // Create test directory structure
        $this->createTestDirectory('1996-2013');
        $this->createTestDirectory('2014-2025');
        $this->createTestDirectory('2025-2026');
        
        // Create test files
        $this->createTestFile('1996-2013/1996.md', $this->getCraftySyntaxContent(1996));
        $this->createTestFile('1996-2013/2013.md', $this->getCraftySyntaxContent(2013));
        $this->createTestFile('2014-2025/2014.md', $this->getHiatusStartContent());
        $this->createTestFile('2014-2025/hiatus.md', $this->getHiatusContent());
        $this->createTestFile('2025-2026/2025.md', $this->getResurgenceContent(2025));
        $this->createTestFile('2025-2026/2026.md', $this->getResurgenceContent(2026));
    }
    
    private function createTestDirectory($path) {
        $fullPath = $this->testHistoryPath . '/' . $path;
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0755, true);
        }
    }
    
    private function createTestFile($path, $content) {
        $fullPath = $this->testHistoryPath . '/' . $path;
        file_put_contents($fullPath, $content);
    }
    
    private function getCraftySyntaxContent($year) {
        return "---\nwolfie.headers: explicit architecture\nfile.last_modified_system_version: 4.0.61\n---\n\n# Year {$year}\n\n## Events\n- Crafty Syntax development continued\n- Live help system improvements\n\n## Achievements\n- Version updates released\n";
    }
    
    private function getHiatusStartContent() {
        return "---\nwolfie.headers: explicit architecture\nfile.last_modified_system_version: 4.0.61\n---\n\n# Year 2014 - The Pivot Point\n\n## Personal Event\n- Wife passed away\n- Creative work paused\n\n## Cross-Reference\nSee `hiatus.md` for complete documentation\n";
    }
    
    private function getHiatusContent() {
        return "---\nwolfie.headers: explicit architecture\nfile.last_modified_system_version: 4.0.61\n---\n\n# 2014-2025: Hiatus Period\n\n## Overview\nPersonal recovery and reflection period\n\n## Key Details\n- Key Note: Eric's wife passed away, leading to extended break\n- No milestones or implementations during this time\n\n## System Impact\n- Foundation preserved through dormancy\n- Emotional geometry: grief axis integration\n\n## Cross-Reference\nSee `2014.md` for pivot point details\n";
    }
    
    private function getResurgenceContent($year) {
        return "---\nwolfie.headers: explicit architecture\nfile.last_modified_system_version: 4.0.61\n---\n\n# Year {$year}\n\n## Events\n- Lupopedia development accelerated\n- WOLFIE architecture implemented\n\n## Achievements\n- Semantic OS components built\n- Integration with previous work\n\n## Cross-Reference\nSee `hiatus.md` for context\n";
    }
    
    private function removeTestFile($path) {
        $fullPath = $this->testHistoryPath . '/' . $path;
        if (file_exists($fullPath)) {
            rename($fullPath, $fullPath . '.backup');
        }
    }
    
    private function restoreTestFile($path) {
        $fullPath = $this->testHistoryPath . '/' . $path;
        $backupPath = $fullPath . '.backup';
        if (file_exists($backupPath)) {
            rename($backupPath, $fullPath);
        }
    }
    
    private function addBrokenReference($file, $reference) {
        $fullPath = $this->testHistoryPath . '/' . $file;
        $content = file_get_contents($fullPath);
        $content .= "\n\nBroken reference: {$reference}";
        file_put_contents($fullPath, $content);
    }
    
    private function removeBrokenReference($file) {
        $fullPath = $this->testHistoryPath . '/' . $file;
        $content = file_get_contents($fullPath);
        $content = preg_replace('/\n\nBroken reference: `[^`]+\.md`/', '', $content);
        file_put_contents($fullPath, $content);
    }
    
    private function removeBackReference($fromFile, $toFile) {
        $fullPath = $this->testHistoryPath . '/' . $fromFile;
        $content = file_get_contents($fullPath);
        $content = preg_replace('/See `' . preg_quote($toFile) . '` for/', '', $content);
        file_put_contents($fullPath, $content);
    }
    
    private function restoreBackReference($fromFile, $toFile) {
        $fullPath = $this->testHistoryPath . '/' . $fromFile;
        $content = file_get_contents($fullPath);
        $content .= "\n\nSee `{$toFile}` for context";
        file_put_contents($fullPath, $content);
    }
    
    private function changeFileVersion($file, $version) {
        $fullPath = $this->testHistoryPath . '/' . $file;
        $content = file_get_contents($fullPath);
        $content = preg_replace('/file\.last_modified_system_version: [\d.]+/', "file.last_modified_system_version: {$version}", $content);
        file_put_contents($fullPath, $content);
    }
    
    private function restoreFileVersion($file) {
        $fullPath = $this->testHistoryPath . '/' . $file;
        $content = file_get_contents($fullPath);
        $content = preg_replace('/file\.last_modified_system_version: [\d.]+/', "file.last_modified_system_version: 4.0.61", $content);
        file_put_contents($fullPath, $content);
    }
    
    private function createCorruptedFile($path) {
        $fullPath = $this->testHistoryPath . '/' . $path;
        file_put_contents($fullPath, "Invalid markdown content\n\n---\nIncomplete frontmatter");
    }
    
    private function removeCorruptedFile($path) {
        $fullPath = $this->testHistoryPath . '/' . $path;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
    
    private function createEmptyFile($path) {
        $fullPath = $this->testHistoryPath . '/' . $path;
        file_put_contents($fullPath, "");
    }
    
    private function removeEmptyFile($path) {
        $fullPath = $this->testHistoryPath . '/' . $path;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
    
    private function validateCraftySyntaxNarrative() {
        $content = file_get_contents($this->testHistoryPath . '/1996-2013/2013.md');
        $this->assert($this->hasCraftySyntaxContent($content), 'Crafty Syntax era should have CS content');
    }
    
    private function validateHiatusNarrative() {
        $content = file_get_contents($this->testHistoryPath . '/2014-2025/hiatus.md');
        $this->assert($this->hasPersonalTragedyNarrative($content), 'Hiatus should have personal tragedy narrative');
        $this->assert($this->hasRecoveryJourney($content), 'Hiatus should have recovery journey');
    }
    
    private function validateResurgenceNarrative() {
        $content = file_get_contents($this->testHistoryPath . '/2025-2026/2025.md');
        $this->assert($this->hasLupopediaDevelopment($content), 'Resurgence should have Lupopedia development');
        $this->assert($this->referencesPreviousWork($content), 'Resurgence should reference previous work');
    }
    
    private function validateEraTransitions() {
        $hiatusContent = file_get_contents($this->testHistoryPath . '/2014-2025/hiatus.md');
        $resurgenceContent = file_get_contents($this->testHistoryPath . '/2025-2026/2025.md');
        
        $this->assert(strpos($hiatusContent, '2014.md') !== false, 'Hiatus should reference 2014');
        $this->assert(strpos($resurgenceContent, 'hiatus.md') !== false, 'Resurgence should reference hiatus');
    }
    
    // Helper methods from ContinuityValidator
    private function hasEventsOrAchievements($content) {
        return preg_match('/(##\s*(Events|Achievements|Milestones))/i', $content);
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
    
    private function assert($condition, $message) {
        if (!$condition) {
            throw new Exception("Assertion failed: {$message}");
        }
    }
    
    private function printResults() {
        echo "=== Test Results ===\n";
        
        $passed = 0;
        $total = count($this->testResults);
        
        foreach ($this->testResults as $test => $result) {
            echo "{$test}: {$result}\n";
            if ($result === 'PASSED') {
                $passed++;
            }
        }
        
        echo "\nSummary: {$passed}/{$total} test suites passed\n";
        
        if ($passed === $total) {
            echo "🎉 All tests passed!\n";
        } else {
            echo "⚠️  Some tests failed. Review output above.\n";
        }
    }
}

// Run tests if called directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    try {
        $test = new ContinuityValidatorTest();
        $test->runAllTests();
    } catch (Exception $e) {
        echo "Test execution failed: " . $e->getMessage() . "\n";
        exit(1);
    }
}
