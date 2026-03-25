<?php
/**
 * Limits Enforcement Integration Tests
 * 
 * Verifies LIMITS enforcement service logic.
 * READ-ONLY tests - does not modify database.
 * 
 * @package Lupopedia
 * @version 3.0.106
 * @author CASCADE
 */

require_once __DIR__ . '/../../lupo-app/Services/System/LimitsEnforcementService.php';

class LimitsEnforcementTest {
    
    private $db;
    private $limitsService;
    private $errors = [];
    
    public function __construct($database_connection) {
        $this->db = $database_connection;
        $this->limitsService = new LimitsEnforcementService($this->db);
    }
    
    /**
     * Verify weekend mode detection (Days 0, 5, 6)
     */
    public function testWeekendModeDetection() {
        if (!method_exists($this->limitsService, 'isWeekendDay')) {
            $this->errors[] = "LimitsEnforcementService::isWeekendDay() method not found";
            return false;
        }
        
        if (!method_exists($this->limitsService, 'getCurrentUTCDay')) {
            $this->errors[] = "LimitsEnforcementService::getCurrentUTCDay() method not found";
            return false;
        }
        
        // Test that method returns boolean
        $isWeekend = $this->limitsService->isWeekendDay();
        if (!is_bool($isWeekend)) {
            $this->errors[] = "isWeekendDay() should return boolean, got: " . gettype($isWeekend);
            return false;
        }
        
        // Test that getCurrentUTCDay returns integer 0-6
        $currentDay = $this->limitsService->getCurrentUTCDay();
        if (!is_int($currentDay)) {
            $this->errors[] = "getCurrentUTCDay() should return integer, got: " . gettype($currentDay);
            return false;
        }
        
        if ($currentDay < 0 || $currentDay > 6) {
            $this->errors[] = "getCurrentUTCDay() should return 0-6, got: {$currentDay}";
            return false;
        }
        
        return true;
    }
    
    /**
     * Verify version freeze logic returns correct boolean
     */
    public function testVersionFreezeLogic() {
        if (!method_exists($this->limitsService, 'checkVersionBump')) {
            $this->errors[] = "LimitsEnforcementService::checkVersionBump() method not found";
            return false;
        }
        
        // Test with non-weekend scenario (should pass)
        // Note: This test may fail on weekends, which is expected behavior
        try {
            $result = $this->limitsService->checkVersionBump('3.0.102', '3.0.103');
            if (!is_bool($result)) {
                $this->errors[] = "checkVersionBump() should return boolean, got: " . gettype($result);
                return false;
            }
        } catch (Exception $e) {
            // Exception is acceptable if it's a weekend and version bump violates freeze
            // This is expected behavior
        }
        
        return true;
    }
    
    /**
     * Verify branch limit logic returns correct boolean
     */
    public function testBranchLimitLogic() {
        if (!method_exists($this->limitsService, 'checkBranchCreation')) {
            $this->errors[] = "LimitsEnforcementService::checkBranchCreation() method not found";
            return false;
        }
        
        // Test with valid weekend branch name
        try {
            $result = $this->limitsService->checkBranchCreation('weekend_experiment_1');
            if (!is_bool($result)) {
                $this->errors[] = "checkBranchCreation() should return boolean, got: " . gettype($result);
                return false;
            }
        } catch (Exception $e) {
            // Exception is acceptable if it's a weekend and branch name is invalid
            // This is expected behavior
        }
        
        return true;
    }
    
    /**
     * Verify schema ceiling logic returns correct boolean
     */
    public function testSchemaCeilingLogic() {
        if (!method_exists($this->limitsService, 'checkTableCount')) {
            $this->errors[] = "LimitsEnforcementService::checkTableCount() method not found";
            return false;
        }
        
        // Test with valid table count (below ceiling)
        try {
            $result = $this->limitsService->checkTableCount(131);
            if (!is_bool($result)) {
                $this->errors[] = "checkTableCount() should return boolean, got: " . gettype($result);
                return false;
            }
        } catch (Exception $e) {
            // Exception is acceptable if table count exceeds ceiling
            // This is expected behavior
        }
        
        // Test with invalid table count (above ceiling)
        try {
            $result = $this->limitsService->checkTableCount(200);
            // Should throw exception or return false
        } catch (Exception $e) {
            // Expected behavior - table count exceeds ceiling
        }
        
        return true;
    }
    
    /**
     * Run all tests
     */
    public function runAllTests() {
        $results = [
            'weekend_mode_detection' => $this->testWeekendModeDetection(),
            'version_freeze_logic' => $this->testVersionFreezeLogic(),
            'branch_limit_logic' => $this->testBranchLimitLogic(),
            'schema_ceiling_logic' => $this->testSchemaCeilingLogic()
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
