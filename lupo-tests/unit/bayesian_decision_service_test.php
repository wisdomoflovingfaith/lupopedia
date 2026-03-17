<?php

/**
 * Unit tests for BayesianDecisionService
 * 
 * Tests core Bayesian probability calculations, validation, and evidence handling.
 */

require_once dirname(__FILE__) . '/../../lupo-includes/bootstrap.php';

class BayesianDecisionServiceTest {
    
    private $service;
    
    public function __construct() {
        $this->service = new BayesianDecisionService();
    }
    
    /**
     * Test probability validation with valid values
     */
    public function testAssertProbabilityValidValues() {
        // Test valid boundaries
        $result = $this->service->assertProbability(0.0, 'test');
        $this->assertEquals(0.0, $result, 'Lower bound 0.0 should pass');
        
        $result = $this->service->assertProbability(1.0, 'test');
        $this->assertEquals(1.0, $result, 'Upper bound 1.0 should pass');
        
        $result = $this->service->assertProbability(0.5, 'test');
        $this->assertEquals(0.5, $result, 'Middle value 0.5 should pass');
        
        echo "✓ Probability validation with valid values passed\n";
    }
    
    /**
     * Test probability validation with invalid values
     */
    public function testAssertProbabilityInvalidValues() {
        try {
            $this->service->assertProbability(-0.1, 'test');
            $this->fail('Should throw exception for negative probability');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContains('must be between 0.0 and 1.0', $e->getMessage());
        }
        
        try {
            $this->service->assertProbability(1.1, 'test');
            $this->fail('Should throw exception for probability > 1.0');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContains('must be between 0.0 and 1.0', $e->getMessage());
        }
        
        try {
            $this->service->assertProbability('invalid', 'test');
            $this->fail('Should throw exception for non-numeric probability');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContains('must be numeric', $e->getMessage());
        }
        
        echo "✓ Probability validation with invalid values passed\n";
    }
    
    /**
     * Test posterior calculation with known values
     */
    public function testCalculatePosterior() {
        // Known case: prior 0.5, likelihood 0.8, evidence probability 0.5
        // Expected: posterior = (0.5 * 0.8) / 0.5 = 0.8
        $posterior = $this->service->calculatePosterior(0.5, 0.8, 0.5);
        $this->assertEqualsWithDelta(0.8, $posterior, 0.001, 'Posterior calculation should match expected value');
        
        // Edge case: very small evidence probability
        $posterior = $this->service->calculatePosterior(0.3, 0.9, 0.1);
        $expected = (0.3 * 0.9) / 0.1; // Should be 2.7, but normalized to 1.0
        $this->assertEquals(1.0, $posterior, 'Posterior should normalize to 1.0 when > 1.0');
        
        echo "✓ Posterior calculation tests passed\n";
    }
    
    /**
     * Test evidence combination
     */
    public function testCombineEvidenceSequential() {
        $prior = 0.5;
        $likelihoods = [0.7, 0.6, 0.8];
        $evidenceProb = 0.9;
        
        $result = $this->service->combineEvidenceSequential($prior, $likelihoods, $evidenceProb);
        
        // Should apply each likelihood sequentially
        // After first: (0.5 * 0.7) / 0.9 = 0.389
        // After second: (0.389 * 0.6) / 0.9 = 0.259
        // After third: (0.259 * 0.8) / 0.9 = 0.230
        $expected = 0.230;
        
        $this->assertEqualsWithDelta($expected, $result, 0.001, 'Sequential evidence combination should match expected value');
        
        echo "✓ Evidence combination tests passed\n";
    }
    
    /**
     * Test influence processing
     */
    public function testApplyInfluences() {
        $basePosterior = 0.7;
        
        // Test with no influences
        $result = $this->service->applyInfluences($basePosterior, []);
        $this->assertEquals($basePosterior, $result, 'No influences should return base posterior');
        
        // Test with weighted influences
        $influences = [
            [
                'source_decision_id' => 1,
                'influence_weight' => 0.8
                'source_probability' => 0.6
            ],
            [
                'source_decision_id' => 2,
                'influence_weight' => 0.4,
                'source_probability' => 0.9
            ]
        ];
        
        $result = $this->service->applyInfluences($basePosterior, $influences);
        
        // Weighted average: (0.6*0.8 + 0.9*0.4) / (0.8 + 0.4) = (0.48 + 0.36) / 1.2 = 0.7
        // Final blend: 0.5 * 0.7 + 0.5 * 0.7 = 0.35 + 0.35 = 0.7
        $expected = 0.7;
        
        $this->assertEqualsWithDelta($expected, $result, 0.001, 'Weighted influence calculation should match expected value');
        
        echo "✓ Influence processing tests passed\n";
    }
    
    /**
     * Helper assertion with delta for floating point comparison
     */
    private function assertEqualsWithDelta($expected, $actual, $delta, $message) {
        if (abs($expected - $actual) <= $delta) {
            $this->assertEquals($expected, $actual, $message);
        } else {
            $this->assertTrue(abs($expected - $actual) <= $delta, 
                "$message (expected: $expected, actual: $actual, delta: $delta)");
        }
    }
    
    /**
     * Run all tests
     */
    public function runAllTests() {
        echo "Running BayesianDecisionService unit tests...\n\n";
        
        $this->testAssertProbabilityValidValues();
        $this->testAssertProbabilityInvalidValues();
        $this->testCalculatePosterior();
        $this->testCombineEvidenceSequential();
        $this->testApplyInfluences();
        
        echo "\n✓ All BayesianDecisionService tests completed successfully!\n";
    }
}

// Run tests if this file is executed directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    $test = new BayesianDecisionServiceTest();
    $test->runAllTests();
}
