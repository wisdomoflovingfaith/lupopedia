<?php
/**
 * Single-Field Versioning Model Test Suite
 * 
 * Comprehensive tests for WOLFIE's single-field versioning model
 * 
 * @version 1.0
 * @author HEPHAESTUS (actor_id 3)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../lupo-includes/functions/version_resolver.php';
require_once __DIR__ . '/../../lupo-includes/classes/SingleFieldVersioningValidator.php';
require_once __DIR__ . '/../../lupo-includes/classes/LupopediaArtifactTemplateGenerator.php';

/**
 * Single-Field Versioning Model Test Suite
 */
class SingleFieldVersioningTestSuite
{
    private $testResults = array();
    private $testCount = 0;
    private $passedCount = 0;
    
    public function runAllTests()
    {
        echo "=== Single-Field Versioning Model Test Suite ===\n\n";
        
        $this->testVersionResolver();
        $this->testSingleFieldValidation();
        $this->testTemplateGenerator();
        $this->testProjectionIntegration();
        $this->testLegacyHandling();
        
        $this->printSummary();
    }
    
    /**
     * Test version resolver functionality
     */
    private function testVersionResolver()
    {
        echo "Test 1: Version Resolver\n";
        
        // Test primary source (LUPEDIA_VERSION)
        $version1 = get_lupopedia_system_version();
        $this->assertTest(
            "Primary version resolution",
            $version1 === '4.0.83',
            "Expected '4.0.83', got '{$version1}'"
        );
        
        // Test that schema version function does NOT exist
        $this->assertTest(
            "Schema version function eliminated",
            !function_exists('get_lupopedia_schema_version'),
            "Schema version function should not exist in single-field model"
        );
        
        echo "\n";
    }
    
    /**
     * Test single-field validation
     */
    private function testSingleFieldValidation()
    {
        echo "Test 2: Single-Field Validation\n";
        
        $validator = new SingleFieldVersioningValidator();
        
        // Test valid new artifact
        $validHeaders = array(
            'version_when_written' => '4.0.83'
        );
        
        $result = $validator->validateSingleFieldVersioning($validHeaders, false);
        $this->assertTest(
            "Valid new artifact validation",
            $result['valid'],
            "Expected valid, got: " . ($result['valid'] ? 'VALID' : 'INVALID')
        );
        
        // Test missing version_when_written
        $missingField = array(
            // Missing version_when_written
        );
        
        $result = $validator->validateSingleFieldVersioning($missingField, false);
        $this->assertTest(
            "Missing version_when_written",
            !$result['valid'],
            "Expected invalid, got: " . ($result['valid'] ? 'VALID' : 'INVALID')
        );
        
        // Test forbidden fields presence
        $forbiddenFields = array(
            'version_when_written' => '4.0.83',
            'lupopedia.version' => '1.0', // Should be rejected
            'system_version' => '4.0.83'  // Should be rejected
        );
        
        $result = $validator->validateSingleFieldVersioning($forbiddenFields, false);
        $this->assertTest(
            "Forbidden fields rejection",
            !$result['valid'],
            "Expected invalid (forbidden fields present), got: " . ($result['valid'] ? 'VALID' : 'INVALID')
        );
        
        echo "\n";
    }
    
    /**
     * Test template generator
     */
    private function testTemplateGenerator()
    {
        echo "Test 3: Template Generator\n";
        
        $generator = new LupopediaArtifactTemplateGenerator();
        
        $config = array(
            'file_path_from_root' => 'test/artifact.md',
            'web_path' => 'http://test/artifact',
            'project_id' => 0,
            'project_slug' => 'test',
            'channel_id' => 66,
            'thread_id' => 1005,
            'task_id' => 'test_001',
            'actor_id' => 3,
            'actor_name' => 'hephaestus',
            'delegation_chain' => 'hephaestus:root',
            'artifact_type' => 'thread',
            'artifact_kind' => 'test',
            'purpose' => 'Test artifact for single-field versioning model',
            'title' => 'Test Artifact',
            'description' => 'Test artifact demonstrating single-field versioning model',
            'traits' => array('test', 'versioning'),
            'tags' => array('test', 'versioning'),
            'message_type' => 'test',
            'edges' => array(),
            'next_actions' => array('Test completion')
        );
        
        $content = $generator->generateArtifact($config);
        
        // Validate generated content
        $this->assertTest(
            "Template generation validation",
            !empty($content),
            "Expected non-empty content, got empty"
        );
        
        // Check that content includes only version_when_written
        $this->assertTest(
            "Template includes version_when_written",
            strpos($content, 'version_when_written: "4.0.83"') !== false,
            "Template missing version_when_written field"
        );
        
        // Check that content excludes forbidden fields
        $this->assertTest(
            "Template excludes lupopedia.version",
            strpos($content, 'lupopedia.version:') === false,
            "Template should not contain lupopedia.version field"
        );
        
        $this->assertTest(
            "Template excludes system_version",
            strpos($content, 'system_version:') === false,
            "Template should not contain system_version field"
        );
        
        echo "\n";
    }
    
    /**
     * Test projection integration
     */
    private function testProjectionIntegration()
    {
        echo "Test 4: Projection Integration\n";
        
        // Test that version resolver is available
        $this->assertTest(
            "Version resolver available",
            function_exists('get_lupopedia_system_version'),
            "get_lupopedia_system_version function not available"
        );
        
        // Test that schema version function is NOT available
        $this->assertTest(
            "Schema version function eliminated",
            !function_exists('get_lupopedia_schema_version'),
            "Schema version function should not exist in single-field model"
        );
        
        echo "\n";
    }
    
    /**
     * Test legacy artifact handling
     */
    private function testLegacyHandling()
    {
        echo "Test 5: Legacy Artifact Handling\n";
        
        $validator = new SingleFieldVersioningValidator();
        
        // Test legacy artifact with old version fields (should warn)
        $legacyOld = array(
            'lupopedia.version' => '1.0',
            'system_version' => '4.0.80',
            'version_when_written' => '4.0.83'
        );
        
        $result = $validator->validateSingleFieldVersioning($legacyOld, true);
        $this->assertTest(
            "Legacy artifact with old versioning",
            $result['compliance_level'] === 'WARN',
            "Expected WARN compliance level, got: " . $result['compliance_level'] . " - " . $validator->getValidationSummary($result)
        );
        
        // Test legacy artifact missing version_when_written (should warn)
        $legacyMissing = array(
            'lupopedia.version' => '1.0',
            'system_version' => '4.0.83'
            // Missing version_when_written
        );
        
        $result = $validator->validateSingleFieldVersioning($legacyMissing, true);
        $this->assertTest(
            "Legacy artifact missing version_when_written",
            !$result['valid'] && !empty($result['warnings']),
            "Expected warnings for missing version_when_written, got: " . $validator->getValidationSummary($result)
        );
        
        echo "\n";
    }
    
    /**
     * Assert test result
     */
    private function assertTest($testName, $condition, $message)
    {
        $this->testCount++;
        if ($condition) {
            $this->passedCount++;
            echo "  ✅ {$testName}: PASS\n";
        } else {
            echo "  ❌ {$testName}: FAIL - {$message}\n";
        }
    }
    
    /**
     * Print test summary
     */
    private function printSummary()
    {
        echo "\n=== TEST SUMMARY ===\n";
        echo "Tests Run: {$this->testCount}\n";
        echo "Tests Passed: {$this->passedCount}\n";
        echo "Success Rate: " . round(($this->passedCount / $this->testCount) * 100, 1) . "%\n";
        
        if ($this->passedCount === $this->testCount) {
            echo "🎉 ALL TESTS PASSED - Single-field versioning model is operational\n";
        } else {
            echo "⚠️  SOME TESTS FAILED - Implementation needs fixes\n";
        }
    }
}

// Run tests if this file is executed directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $testSuite = new SingleFieldVersioningTestSuite();
    $testSuite->runAllTests();
}
?>
