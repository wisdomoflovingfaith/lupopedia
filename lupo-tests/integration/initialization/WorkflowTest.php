<?php
/**
 * Integration Test for 4.0.44 Initialization Workflow
 * 
 * Tests the complete initialization workflow end-to-end
 * 
 * @package Tests\Integration\Initialization
 * @since 4.0.44
 */

class InitializationWorkflowTest
{
    private $testResults = array();
    private $testCount = 0;
    private $passedCount = 0;
    private $testDir;
    private $originalDir;
    
    public function __construct()
    {
        $this->originalDir = getcwd();
        $this->testDir = sys_get_temp_dir() . '/lupopedia_test_' . time();
    }
    
    public function runAllTests()
    {
        echo "Running Initialization Workflow Integration Tests...\n";
        echo "================================================\n\n";
        
        $this->testCliScriptExists();
        $this->testDryRunExecution();
        $this->testHelpOutput();
        $this->testErrorHandling();
        
        $this->cleanup();
        $this->printResults();
    }
    
    private function testCliScriptExists()
    {
        $this->testCount++;
        echo "Test 1: CLI Script Exists\n";
        
        $scriptPath = __DIR__ . '/../../../bin/kiro_initialize_4_0_44.php';
        
        if (file_exists($scriptPath)) {
            echo "  ✓ CLI script exists at expected location\n";
            $this->passedCount++;
            $this->testResults[] = "CLI_SCRIPT_EXISTS: PASS";
        } else {
            echo "  ✗ CLI script not found at: " . $scriptPath . "\n";
            $this->testResults[] = "CLI_SCRIPT_EXISTS: FAIL - Script not found";
        }
        
        echo "\n";
    }
    
    private function testDryRunExecution()
    {
        $this->testCount++;
        echo "Test 2: Dry Run Execution\n";
        
        $scriptPath = __DIR__ . '/../../../bin/kiro_initialize_4_0_44.php';
        
        if (!file_exists($scriptPath)) {
            echo "  ✗ Skipping test - CLI script not found\n";
            $this->testResults[] = "DRY_RUN_EXECUTION: SKIP - Script not found";
            echo "\n";
            return;
        }
        
        // Change to project root
        chdir(dirname(__DIR__) . '/../../..');
        
        // Execute dry run
        $output = array();
        $returnCode = 0;
        $command = 'php ' . $scriptPath . ' --dry-run 2>&1';
        
        exec($command, $output, $returnCode);
        
        $outputText = implode("\n", $output);
        
        if (strpos($outputText, 'Lupopedia 4.0.44 Initialization Workflow') !== false) {
            echo "  ✓ Dry run executes and shows expected header\n";
            $this->passedCount++;
            $this->testResults[] = "DRY_RUN_EXECUTION: PASS";
        } else {
            echo "  ✗ Dry run output unexpected or failed\n";
            echo "    Output: " . substr($outputText, 0, 200) . "...\n";
            $this->testResults[] = "DRY_RUN_EXECUTION: FAIL - Unexpected output";
        }
        
        echo "\n";
    }
    
    private function testHelpOutput()
    {
        $this->testCount++;
        echo "Test 3: Help Output\n";
        
        $scriptPath = __DIR__ . '/../../../bin/kiro_initialize_4_0_44.php';
        
        if (!file_exists($scriptPath)) {
            echo "  ✗ Skipping test - CLI script not found\n";
            $this->testResults[] = "HELP_OUTPUT: SKIP - Script not found";
            echo "\n";
            return;
        }
        
        // Execute help command
        $output = array();
        $returnCode = 0;
        $command = 'php ' . $scriptPath . ' --help 2>&1';
        
        exec($command, $output, $returnCode);
        
        $outputText = implode("\n", $output);
        
        if (strpos($outputText, 'Usage:') !== false || strpos($outputText, 'help') !== false) {
            echo "  ✓ Help command provides usage information\n";
            $this->passedCount++;
            $this->testResults[] = "HELP_OUTPUT: PASS";
        } else {
            echo "  ✗ Help command not working or no help available\n";
            $this->testResults[] = "HELP_OUTPUT: FAIL - No help output";
        }
        
        echo "\n";
    }
    
    private function testErrorHandling()
    {
        $this->testCount++;
        echo "Test 4: Error Handling\n";
        
        $scriptPath = __DIR__ . '/../../../bin/kiro_initialize_4_0_44.php';
        
        if (!file_exists($scriptPath)) {
            echo "  ✗ Skipping test - CLI script not found\n";
            $this->testResults[] = "ERROR_HANDLING: SKIP - Script not found";
            echo "\n";
            return;
        }
        
        // Test with invalid option
        $output = array();
        $returnCode = 0;
        $command = 'php ' . $scriptPath . ' --invalid-option 2>&1';
        
        exec($command, $output, $returnCode);
        
        $outputText = implode("\n", $output);
        
        // Should either show error message or exit with non-zero code
        if ($returnCode !== 0 || strpos($outputText, 'ERROR') !== false || strpos($outputText, 'error') !== false) {
            echo "  ✓ Error handling works (invalid option detected)\n";
            $this->passedCount++;
            $this->testResults[] = "ERROR_HANDLING: PASS";
        } else {
            echo "  ✗ Error handling may not be working properly\n";
            $this->testResults[] = "ERROR_HANDLING: FAIL - No error detection";
        }
        
        echo "\n";
    }
    
    private function cleanup()
    {
        // Return to original directory
        chdir($this->originalDir);
        
        // Clean up test directory if created
        if (is_dir($this->testDir)) {
            $this->removeDirectory($this->testDir);
        }
    }
    
    private function removeDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }
    
    private function printResults()
    {
        echo "Integration Test Results Summary\n";
        echo "===============================\n";
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
            echo "🎉 ALL INTEGRATION TESTS PASSED!\n";
            return 0;
        } else {
            echo "❌ SOME INTEGRATION TESTS FAILED!\n";
            return 1;
        }
    }
}

// Run the tests
if (php_sapi_name() === 'cli') {
    $test = new InitializationWorkflowTest();
    exit($test->runAllTests());
} else {
    die("This test must be run from the command line.\n");
}
?>
