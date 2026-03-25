<?php
/**
 * Unit Test for DoctrineIngester Component
 * 
 * Tests the doctrine ingestion functionality of the initialization workflow
 * 
 * @package Tests\Unit\Initialization
 * @since 4.0.44
 */

// Include the component to test
require_once __DIR__ . '/../../../lupo-app/Services/Initialization/DoctrineIngester.php';

class DoctrineIngesterTest
{
    private $testResults = array();
    private $testCount = 0;
    private $passedCount = 0;
    
    public function runAllTests()
    {
        echo "Running DoctrineIngester Unit Tests...\n";
        echo "====================================\n\n";
        
        $this->testConstructor();
        $this->testIngestDoctrines();
        $this->testParseFlipHeader();
        $this->testValidateDoctrine();
        $this->testGenerateSummary();
        
        $this->printResults();
    }
    
    private function testConstructor()
    {
        $this->testCount++;
        echo "Test 1: Constructor\n";
        
        try {
            $ingester = new DoctrineIngester();
            if ($ingester instanceof DoctrineIngester) {
                echo "  ✓ Constructor creates valid instance\n";
                $this->passedCount++;
                $this->testResults[] = "CONSTRUCTOR: PASS";
            } else {
                echo "  ✗ Constructor failed to create instance\n";
                $this->testResults[] = "CONSTRUCTOR: FAIL - Invalid instance";
            }
        } catch (Exception $e) {
            echo "  ✗ Constructor threw exception: " . $e->getMessage() . "\n";
            $this->testResults[] = "CONSTRUCTOR: FAIL - Exception: " . $e->getMessage();
        }
        
        echo "\n";
    }
    
    private function testIngestDoctrines()
    {
        $this->testCount++;
        echo "Test 2: Ingest Doctrines\n";
        
        try {
            $ingester = new DoctrineIngester();
            $result = $ingester->ingestDoctrines('channels/0/broadcasts/');
            
            if (is_array($result) && isset($result['doctrines'])) {
                echo "  ✓ IngestDoctrines returns valid array structure\n";
                $this->passedCount++;
                $this->testResults[] = "INGEST_DOCTRINES: PASS";
            } else {
                echo "  ✗ IngestDoctrines returned invalid structure\n";
                $this->testResults[] = "INGEST_DOCTRINES: FAIL - Invalid structure";
            }
        } catch (Exception $e) {
            echo "  ✗ IngestDoctrines threw exception: " . $e->getMessage() . "\n";
            $this->testResults[] = "INGEST_DOCTRINES: FAIL - Exception: " . $e->getMessage();
        }
        
        echo "\n";
    }
    
    private function testParseFlipHeader()
    {
        $this->testCount++;
        echo "Test 3: Parse FLIP Header\n";
        
        try {
            $ingester = new DoctrineIngester();
            
            $testHeader = "---\nwolfie.headers: {\n  system_version: \"4.0.44\",\n  channel_id: 0\n}\n---\n";
            
            $parsed = $ingester->parseFlipHeader($testHeader);
            
            if (is_array($parsed) && isset($parsed['system_version']) && $parsed['system_version'] === '4.0.44') {
                echo "  ✓ ParseFlipHeader correctly parses FLIP header\n";
                $this->passedCount++;
                $this->testResults[] = "PARSE_FLIP_HEADER: PASS";
            } else {
                echo "  ✗ ParseFlipHeader failed to parse correctly\n";
                $this->testResults[] = "PARSE_FLIP_HEADER: FAIL - Incorrect parsing";
            }
        } catch (Exception $e) {
            echo "  ✗ ParseFlipHeader threw exception: " . $e->getMessage() . "\n";
            $this->testResults[] = "PARSE_FLIP_HEADER: FAIL - Exception: " . $e->getMessage();
        }
        
        echo "\n";
    }
    
    private function testValidateDoctrine()
    {
        $this->testCount++;
        echo "Test 4: Validate Doctrine\n";
        
        try {
            $ingester = new DoctrineIngester();
            
            $testDoctrine = array(
                'system_version' => '4.0.44',
                'channel_id' => 0,
                'actor_id' => 10000,
                'created_ymdhis' => '20260224192000'
            );
            
            $isValid = $ingester->validateDoctrine($testDoctrine);
            
            if ($isValid === true) {
                echo "  ✓ ValidateDoctrine correctly validates valid doctrine\n";
                $this->passedCount++;
                $this->testResults[] = "VALIDATE_DOCTRINE: PASS";
            } else {
                echo "  ✗ ValidateDoctrine failed to validate valid doctrine\n";
                $this->testResults[] = "VALIDATE_DOCTRINE: FAIL - Invalid validation";
            }
        } catch (Exception $e) {
            echo "  ✗ ValidateDoctrine threw exception: " . $e->getMessage() . "\n";
            $this->testResults[] = "VALIDATE_DOCTRINE: FAIL - Exception: " . $e->getMessage();
        }
        
        echo "\n";
    }
    
    private function testGenerateSummary()
    {
        $this->testCount++;
        echo "Test 5: Generate Summary\n";
        
        try {
            $ingester = new DoctrineIngester();
            
            $testData = array(
                'doctrines' => array(
                    array('title' => 'Test Doctrine 1', 'version' => '4.0.44'),
                    array('title' => 'Test Doctrine 2', 'version' => '4.0.44')
                ),
                'summary' => array(
                    'total_count' => 2,
                    'valid_count' => 2,
                    'invalid_count' => 0
                )
            );
            
            $summary = $ingester->generateSummary($testData);
            
            if (is_string($summary) && strlen($summary) > 0) {
                echo "  ✓ GenerateSummary creates valid summary string\n";
                $this->passedCount++;
                $this->testResults[] = "GENERATE_SUMMARY: PASS";
            } else {
                echo "  ✗ GenerateSummary failed to create summary\n";
                $this->testResults[] = "GENERATE_SUMMARY: FAIL - Empty or invalid summary";
            }
        } catch (Exception $e) {
            echo "  ✗ GenerateSummary threw exception: " . $e->getMessage() . "\n";
            $this->testResults[] = "GENERATE_SUMMARY: FAIL - Exception: " . $e->getMessage();
        }
        
        echo "\n";
    }
    
    private function printResults()
    {
        echo "Test Results Summary\n";
        echo "===================\n";
        echo "Tests Run: " . $this->testCount . "\n";
        echo "Tests Passed: " . $this->passedCount . "\n";
        echo "Tests Failed: " . ($this->testCount - $this->passedCount) . "\n";
        echo "Success Rate: " . round(($this->passedCount / $this->testCount) * 100, 1) . "%\n\n";
        
        echo "Detailed Results:\n";
        foreach ($this->testResults as $result) {
            echo "  " . $result . "\n";
        }
        
        echo "\n";
        
        if ($this->passedCount === $this->testCount) {
            echo "🎉 ALL TESTS PASSED!\n";
            return 0;
        } else {
            echo "❌ SOME TESTS FAILED!\n";
            return 1;
        }
    }
}

// Run the tests
if (php_sapi_name() === 'cli') {
    $test = new DoctrineIngesterTest();
    exit($test->runAllTests());
} else {
    die("This test must be run from the command line.\n");
}
?>
