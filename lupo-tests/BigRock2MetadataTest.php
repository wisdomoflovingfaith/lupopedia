<?php
/**
 * Big Rock 2: Dialog Channel Migration - Metadata Extraction Test
 * 
 * Tests the metadata extraction and dialog integration for Big Rock 2
 * Validates that metadata is properly extracted from .md files
 * Tests emotional intelligence integration and cross-reference suggestions
 * 
 * @version 4.0.66
 * @author GLOBAL_CURRENT_AUTHORS
 */

require_once __DIR__ . '/../lupo-includes/classes/MetadataExtractor.php';
require_once __DIR__ . '/../lupo-includes/classes/DialogHistoryManager.php';

class BigRock2MetadataTest {
    
    private $metadataExtractor;
    private $dialogManager;
    private $testResults = [];
    
    public function __construct() {
        $this->metadataExtractor = new MetadataExtractor();
        $this->dialogManager = new DialogHistoryManager();
    }
    
    /**
     * Run all Big Rock 2 metadata tests
     */
    public function runAllTests(): array {
        echo "🚀 Big Rock 2: Dialog Channel Migration - Metadata Extraction Tests\n";
        echo str_repeat("=", 70) . "\n\n";
        
        $this->testMetadataExtraction();
        $this->testCrossReferenceIntelligence();
        $this->testEmotionalGeometryExtraction();
        $this->testSensitivityDetection();
        $this->testDialogIntegration();
        $this->testEraMetadata();
        $this->testDialogQueries();
        
        $this->printTestSummary();
        return $this->testResults;
    }
    
    /**
     * Test metadata extraction from .md files
     */
    private function testMetadataExtraction(): void {
        echo "📋 Testing Metadata Extraction...\n";
        
        try {
            $metadata = $this->metadataExtractor->extractAllMetadata();
            
            $this->assert(!empty($metadata), "Metadata extraction should return data");
            $this->assert(count($metadata) > 30, "Should extract metadata from all .md files");
            
            // Test specific file metadata
            $sampleFile = null;
            foreach ($metadata as $filePath => $data) {
                if (strpos($filePath, '2002.md') !== false) {
                    $sampleFile = $data;
                    break;
                }
            }
            
            $this->assert($sampleFile !== null, "Should find 2002.md metadata");
            $this->assert(isset($sampleFile['frontmatter']), "Should extract YAML frontmatter");
            $this->assert(isset($sampleFile['content']), "Should extract content metadata");
            $this->assert(isset($sampleFile['emotional_geometry']), "Should extract emotional geometry");
            $this->assert(isset($sampleFile['cross_references']), "Should extract cross-references");
            
            $this->testResults['metadata_extraction'] = 'PASS';
            echo "✅ Metadata Extraction: PASS\n";
            
        } catch (Exception $e) {
            $this->testResults['metadata_extraction'] = 'FAIL: ' . $e->getMessage();
            echo "❌ Metadata Extraction: FAIL - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Test cross-reference intelligence
     */
    private function testCrossReferenceIntelligence(): void {
        echo "🔗 Testing Cross-Reference Intelligence...\n";
        
        try {
            // Test suggestions for 2002 (critical year)
            $suggestions = $this->metadataExtractor->getCrossReferenceSuggestions('2002');
            
            $this->assert(is_array($suggestions), "Should return suggestions array");
            $this->assert(isset($suggestions['forward']), "Should have forward suggestions");
            $this->assert(isset($suggestions['backward']), "Should have backward suggestions");
            
            // Test suggestions for hiatus period (high sensitivity)
            $hiatusSuggestions = $this->metadataExtractor->getCrossReferenceSuggestions('2014');
            $this->assert(is_array($hiatusSuggestions), "Should handle hiatus period suggestions");
            
            $this->testResults['cross_reference_intelligence'] = 'PASS';
            echo "✅ Cross-Reference Intelligence: PASS\n";
            
        } catch (Exception $e) {
            $this->testResults['cross_reference_intelligence'] = 'FAIL: ' . $e->getMessage();
            echo "❌ Cross-Reference Intelligence: FAIL - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Test emotional geometry extraction
     */
    private function testEmotionalGeometryExtraction(): void {
        echo "🎨 Testing Emotional Geometry Extraction...\n";
        
        try {
            // Test emotional context for active development year
            $activeContext = $this->metadataExtractor->getEmotionalContext('2002');
            $this->assert(isset($activeContext['era']), "Should identify era");
            $this->assert($activeContext['era'] === 'active_development', "Should identify active development era");
            
            // Test emotional context for hiatus year
            $hiatusContext = $this->metadataExtractor->getEmotionalContext('2014');
            $this->assert($hiatusContext['era'] === 'hiatus', "Should identify hiatus era");
            $this->assert($hiatusContext['sensitivity']['handling_required'], "Should detect sensitivity");
            
            $this->testResults['emotional_geometry_extraction'] = 'PASS';
            echo "✅ Emotional Geometry Extraction: PASS\n";
            
        } catch (Exception $e) {
            $this->testResults['emotional_geometry_extraction'] = 'FAIL: ' . $e->getMessage();
            echo "❌ Emotional Geometry Extraction: FAIL - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Test sensitivity detection
     */
    private function testSensitivityDetection(): void {
        echo "🔒 Testing Sensitivity Detection...\n";
        
        try {
            // Test hiatus period sensitivity
            $hiatusContext = $this->metadataExtractor->getEmotionalContext('2014');
            $this->assert($hiatusContext['sensitivity']['level'] === 'high', "Should detect high sensitivity");
            $this->assert(in_array('personal_tragedy', $hiatusContext['sensitivity']['topics']), "Should identify personal tragedy");
            
            // Test active development period (low sensitivity)
            $activeContext = $this->metadataExtractor->getEmotionalContext('2002');
            $this->assert($activeContext['sensitivity']['level'] === 'low', "Should detect low sensitivity for active period");
            
            $this->testResults['sensitivity_detection'] = 'PASS';
            echo "✅ Sensitivity Detection: PASS\n";
            
        } catch (Exception $e) {
            $this->testResults['sensitivity_detection'] = 'FAIL: ' . $e->getMessage();
            echo "❌ Sensitivity Detection: FAIL - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Test dialog integration
     */
    private function testDialogIntegration(): void {
        echo "💬 Testing Dialog Integration...\n";
        
        try {
            // Test dialog manager initialization
            $this->assert($this->dialogManager !== null, "Dialog manager should be initialized");
            
            // Test metadata enhancement
            $response = $this->dialogManager->processQuery("What happened in 2002?");
            $this->assert(isset($response['metadata']['metadata_enhanced']), "Should indicate metadata enhancement");
            $this->assert($response['metadata']['metadata_enhanced'] === true, "Should have metadata enhancement enabled");
            $this->assert($response['metadata']['extracted_metadata_count'] > 0, "Should show extracted metadata count");
            
            $this->testResults['dialog_integration'] = 'PASS';
            echo "✅ Dialog Integration: PASS\n";
            
        } catch (Exception $e) {
            $this->testResults['dialog_integration'] = 'FAIL: ' . $e->getMessage();
            echo "❌ Dialog Integration: FAIL - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Test era metadata
     */
    private function testEraMetadata(): void {
        echo "📅 Testing Era Metadata...\n";
        
        try {
            // Test active development era
            $response = $this->dialogManager->processQuery("Tell me about the active development period");
            $this->assert(isset($response['metadata']['sensitivity_level']), "Should include sensitivity level");
            
            // Test hiatus era
            $hiatusResponse = $this->dialogManager->processQuery("What happened during the hiatus?");
            $this->assert($hiatusResponse['metadata']['sensitivity_level'] === 'high', "Should detect high sensitivity for hiatus");
            
            $this->testResults['era_metadata'] = 'PASS';
            echo "✅ Era Metadata: PASS\n";
            
        } catch (Exception $e) {
            $this->testResults['era_metadata'] = 'FAIL: ' . $e->getMessage();
            echo "❌ Era Metadata: FAIL - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Test dialog queries with metadata enhancement
     */
    private function testDialogQueries(): void {
        echo "🤖 Testing Enhanced Dialog Queries...\n";
        
        try {
            $testQueries = [
                "What happened in 2002?" => ['year' => '2002', 'era' => 'active_development'],
                "Tell me about Crafty Syntax" => ['topic' => 'crafty_syntax'],
                "What happened during 2014?" => ['year' => '2014', 'era' => 'hiatus', 'sensitivity' => 'high'],
                "Show me the timeline" => ['type' => 'timeline_overview']
            ];
            
            foreach ($testQueries as $query => $expected) {
                $response = $this->dialogManager->processQuery($query);
                
                $this->assert(isset($response['content']), "Should return content for: $query");
                $this->assert(isset($response['cross_references']), "Should include cross-references");
                $this->assert(isset($response['metadata']['metadata_enhanced']), "Should be metadata enhanced");
                
                if (isset($expected['sensitivity']) && $expected['sensitivity'] === 'high') {
                    $this->assert($response['metadata']['sensitivity_level'] === 'high', "Should detect high sensitivity");
                }
            }
            
            $this->testResults['dialog_queries'] = 'PASS';
            echo "✅ Enhanced Dialog Queries: PASS\n";
            
        } catch (Exception $e) {
            $this->testResults['dialog_queries'] = 'FAIL: ' . $e->getMessage();
            echo "❌ Enhanced Dialog Queries: FAIL - " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    /**
     * Simple assertion helper
     */
    private function assert(bool $condition, string $message): void {
        if (!$condition) {
            throw new Exception("Assertion failed: $message");
        }
    }
    
    /**
     * Print test summary
     */
    private function printTestSummary(): void {
        echo "📊 Test Summary\n";
        echo str_repeat("-", 50) . "\n";
        
        $totalTests = count($this->testResults);
        $passedTests = array_filter($this->testResults, function($result) {
            return strpos($result, 'PASS') === 0;
        });
        $passedCount = count($passedTests);
        
        echo "Total Tests: $totalTests\n";
        echo "Passed: $passedCount\n";
        echo "Failed: " . ($totalTests - $passedCount) . "\n\n";
        
        echo "Detailed Results:\n";
        foreach ($this->testResults as $test => $result) {
            $status = strpos($result, 'PASS') === 0 ? '✅' : '❌';
            echo "$status $test: $result\n";
        }
        
        if ($passedCount === $totalTests) {
            echo "\n🎉 All Big Rock 2 Metadata Tests PASSED!\n";
            echo "✅ Dialog Channel Migration is ready for production!\n";
        } else {
            echo "\n⚠️  Some tests failed. Review and fix issues before deployment.\n";
        }
        
        echo "\n" . str_repeat("=", 70) . "\n";
    }
}

// Run tests if this file is accessed directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    $test = new BigRock2MetadataTest();
    $test->runAllTests();
}
