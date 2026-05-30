<?php
/**
 * Terminal AI Integration Tests
 * 
 * Verifies Terminal AI agents execute correctly.
 * READ-ONLY tests - does not modify database.
 * 
 * @package Lupopedia
 * @version 3.0.106
 * @author CASCADE
 */

require_once __DIR__ . '/../../app/TerminalAI/Agents/TerminalAI_001.php';
require_once __DIR__ . '/../../app/TerminalAI/Agents/TerminalAI_005.php';
require_once __DIR__ . '/../../app/TerminalAI/Services/TerminalAIService.php';

class TerminalAITest {
    
    private $errors = [];
    
    /**
     * Verify TerminalAI_001 executes commands
     */
    public function testTerminalAI001Execution() {
        $agent = new TerminalAI_001();
        
        if (!method_exists($agent, 'handle')) {
            $this->errors[] = "TerminalAI_001::handle() method not found";
            return false;
        }
        
        $testInput = "test_command";
        $result = $agent->handle($testInput);
        
        if (empty($result)) {
            $this->errors[] = "TerminalAI_001::handle() returned empty result";
            return false;
        }
        
        if (strpos($result, $testInput) === false) {
            $this->errors[] = "TerminalAI_001::handle() should echo input";
            return false;
        }
        
        return true;
    }
    
    /**
     * Verify TerminalAI_005 returns UTC timestamps
     */
    public function testTerminalAI005UTCTimestamps() {
        $agent = new TerminalAI_005();
        
        if (!method_exists($agent, 'handle')) {
            $this->errors[] = "TerminalAI_005::handle() method not found";
            return false;
        }
        
        $testInput = "what_is_current_utc_time_yyyymmddhhiiss";
        $result = $agent->handle($testInput);
        
        if (strpos($result, 'error') !== false) {
            $this->errors[] = "TerminalAI_005::handle() returned error for valid input";
            return false;
        }
        
        // Extract timestamp from result
        if (preg_match('/(\d{14})/', $result, $matches)) {
            $timestamp = $matches[1];
            
            // Verify format is YYYYMMDDHHIISS
            if (strlen($timestamp) !== 14) {
                $this->errors[] = "TerminalAI_005 timestamp format incorrect (expected 14 digits)";
                return false;
            }
            
            // Verify it's a reasonable date (not in the past or too far future)
            $year = substr($timestamp, 0, 4);
            if ($year < 2020 || $year > 2030) {
                $this->errors[] = "TerminalAI_005 timestamp year out of range: {$year}";
                return false;
            }
        } else {
            $this->errors[] = "TerminalAI_005::handle() did not return valid timestamp format";
            return false;
        }
        
        // Test invalid input
        $invalidResult = $agent->handle("invalid_command");
        if (strpos($invalidResult, 'error') === false) {
            $this->errors[] = "TerminalAI_005::handle() should return error for invalid input";
            return false;
        }
        
        return true;
    }
    
    /**
     * Verify TerminalAIService dispatches agents correctly
     */
    public function testTerminalAIServiceDispatch() {
        $service = new TerminalAIService();
        
        if (!method_exists($service, 'execute')) {
            $this->errors[] = "TerminalAIService::execute() method not found";
            return false;
        }
        
        $testCommand = "test_command";
        $result = $service->execute($testCommand);
        
        if (empty($result)) {
            $this->errors[] = "TerminalAIService::execute() returned empty result";
            return false;
        }
        
        // Verify utc() method exists
        if (!method_exists($service, 'utc')) {
            $this->errors[] = "TerminalAIService::utc() method not found";
            return false;
        }
        
        $utcResult = $service->utc();
        if (empty($utcResult)) {
            $this->errors[] = "TerminalAIService::utc() returned empty result";
            return false;
        }
        
        return true;
    }
    
    /**
     * Run all tests
     */
    public function runAllTests() {
        $results = [
            'terminal_ai_001_execution' => $this->testTerminalAI001Execution(),
            'terminal_ai_005_utc_timestamps' => $this->testTerminalAI005UTCTimestamps(),
            'terminal_ai_service_dispatch' => $this->testTerminalAIServiceDispatch()
        ];
        
        return [
            'passed' => count(array_filter($results)) === count($results),
            'results' => $results,
            'errors' => $this->errors
        ];
    }
    
    /**
     * Get errors
     */
    public function getErrors() {
        return $this->errors;
    }
}
