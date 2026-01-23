<?php
/**
 * Temporal Frame Compatibility Test Suite - WOLFIE Router v0.5 Validation
 * 
 * Comprehensive testing for temporal frame compatibility model v0.5
 * including compatibility testing, frame selection, and bridge states.
 * 
 * @package Lupopedia
 * @version 0.5
 * @author WOLFIE Semantic Engine
 */

// Include required files
require_once __DIR__ . '/../lupopedia-config.php';
require_once __DIR__ . '/../app/TemporalFrameCompatibility.php';
require_once __DIR__ . '/../app/WolfieIdentity.php';
require_once __DIR__ . '/../app/TemporalMonitor.php';
require_once __DIR__ . '/../app/TrinitaryRouter.php';

class TemporalFrameCompatibilityTest {
    private $temporalFrameCompatibility;
    private $wolfieIdentity;
    private $temporalMonitor;
    private $trinitaryRouter;
    private $testResults = [];
    
    public function __construct() {
        $this->temporalFrameCompatibility = new TemporalFrameCompatibility();
        $this->wolfieIdentity = new WolfieIdentity('TestAgent');
        $this->temporalMonitor = new TemporalMonitor($this->wolfieIdentity);
        $this->trinitaryRouter = new TrinitaryRouter($this->wolfieIdentity, $this->temporalMonitor);
    }
    
    /**
     * Run complete test suite
     */
    public function runTestSuite() {
        echo "=== WOLFIE Temporal Frame Compatibility Test Suite v0.5 ===\n";
        echo "Testing temporal frame compatibility model implementation\n\n";
        
        $this->testCompatibilityThreshold();
        $this->testCompatibleFrameBlending();
        $this->testIncompatibleFrameSelection();
        $this->testBridgeStateCreation();
        $this->testFrameAwareRouting();
        $this->testMigrationWithFrameReconciliation();
        $this->testEdgeCases();
        
        $this->generateTestReport();
    }
    
    /**
     * Test compatibility threshold behavior
     */
    private function testCompatibilityThreshold() {
        echo "🧪 Testing Compatibility Threshold...\n";
        
        $testCases = [
            // Identical frames (should be compatible)
            ['c1_a' => 1.0, 'c2_a' => 0.9, 'c1_b' => 1.0, 'c2_b' => 0.9, 'expected' => true],
            
            // Close frames (should be compatible)
            ['c1_a' => 1.0, 'c2_a' => 0.9, 'c1_b' => 1.1, 'c2_b' => 0.8, 'expected' => true],
            
            // Just within threshold (should be compatible)
            ['c1_a' => 1.0, 'c2_a' => 0.9, 'c1_b' => 1.3, 'c2_b' => 0.7, 'expected' => true],
            
            // Just outside threshold (should be incompatible)
            ['c1_a' => 1.0, 'c2_a' => 0.9, 'c1_b' => 1.4, 'c2_b' => 0.7, 'expected' => false],
            
            // Very different frames (should be incompatible)
            ['c1_a' => 0.2, 'c2_a' => 0.3, 'c1_b' => 1.8, 'c2_b' => 0.9, 'expected' => false],
        ];
        
        $passed = 0;
        $total = count($testCases);
        
        foreach ($testCases as $index => $testCase) {
            $result = $this->temporalFrameCompatibility->isTemporalFrameCompatible(
                $testCase['c1_a'], $testCase['c2_a'],
                $testCase['c1_b'], $testCase['c2_b']
            );
            
            $success = $result['compatible'] === $testCase['expected'];
            if ($success) $passed++;
            
            echo sprintf(
                "  Test %d: c1=(%.1f,%.1f) c2=(%.1f,%.1f) -> %s %s\n",
                $index + 1,
                $testCase['c1_a'], $testCase['c1_b'],
                $testCase['c2_a'], $testCase['c2_b'],
                $result['compatible'] ? 'COMPATIBLE' : 'INCOMPATIBLE',
                $success ? '✅' : '❌'
            );
        }
        
        $this->testResults['compatibility_threshold'] = [
            'passed' => $passed,
            'total' => $total,
            'success_rate' => $passed / $total
        ];
        
        echo "  Result: {$passed}/{$total} tests passed\n\n";
    }
    
    /**
     * Test blending for compatible frames
     */
    private function testCompatibleFrameBlending() {
        echo "🧪 Testing Compatible Frame Blending...\n";
        
        $testCases = [
            // Simple compatible frames
            [
                'c1_a' => 1.0, 'c2_a' => 0.9,
                'c1_b' => 1.1, 'c2_b' => 0.8,
                'weights' => ['actor_a' => 0.5, 'actor_b' => 0.5],
                'expected_c1' => 1.05,
                'expected_c2' => 0.85
            ],
            
            // Weighted blending
            [
                'c1_a' => 0.8, 'c2_a' => 0.7,
                'c1_b' => 1.2, 'c2_b' => 0.9,
                'weights' => ['actor_a' => 0.3, 'actor_b' => 0.7],
                'expected_c1' => 1.08,
                'expected_c2' => 0.84
            ],
        ];
        
        $passed = 0;
        $total = count($testCases);
        
        foreach ($testCases as $index => $testCase) {
            try {
                $result = $this->temporalFrameCompatibility->blendTemporalStates(
                    $testCase['c1_a'], $testCase['c2_a'],
                    $testCase['c1_b'], $testCase['c2_b'],
                    $testCase['weights']
                );
                
                $c1_match = abs($result['c1'] - $testCase['expected_c1']) < 0.01;
                $c2_match = abs($result['c2'] - $testCase['expected_c2']) < 0.01;
                $success = $c1_match && $c2_match;
                
                if ($success) $passed++;
                
                echo sprintf(
                    "  Test %d: Blend (%.1f,%.1f)+(%.1f,%.1f) -> (%.2f,%.2f) %s\n",
                    $index + 1,
                    $testCase['c1_a'], $testCase['c2_a'],
                    $testCase['c1_b'], $testCase['c2_b'],
                    $result['c1'], $result['c2'],
                    $success ? '✅' : '❌'
                );
                
            } catch (Exception $e) {
                echo sprintf(
                    "  Test %d: Exception - %s ❌\n",
                    $index + 1,
                    $e->getMessage()
                );
            }
        }
        
        // Test blending incompatible frames (should fail)
        try {
            $result = $this->temporalFrameCompatibility->blendTemporalStates(
                0.2, 0.3, 1.8, 0.9
            );
            echo "  Incompatible blend test: Should have failed but didn't ❌\n";
        } catch (Exception $e) {
            echo "  Incompatible blend test: Correctly failed ✅\n";
            $passed++;
            $total++;
        }
        
        $this->testResults['compatible_blending'] = [
            'passed' => $passed,
            'total' => $total,
            'success_rate' => $passed / $total
        ];
        
        echo "  Result: {$passed}/{$total} tests passed\n\n";
    }
    
    /**
     * Test frame selection for incompatible frames
     */
    private function testIncompatibleFrameSelection() {
        echo "🧪 Testing Incompatible Frame Selection...\n";
        
        $testCases = [
            // Higher c2 should be selected
            [
                'c1_a' => 1.0, 'c2_a' => 0.9,
                'c1_b' => 1.1, 'c2_b' => 0.7,
                'expected_actor' => 'actor_a',
                'reason' => 'Higher temporal coherence'
            ],
            
            // Higher c2 should be selected (reverse)
            [
                'c1_a' => 1.0, 'c2_a' => 0.6,
                'c1_b' => 1.1, 'c2_b' => 0.8,
                'expected_actor' => 'actor_b',
                'reason' => 'Higher temporal coherence'
            ],
        ];
        
        $passed = 0;
        $total = count($testCases);
        
        foreach ($testCases as $index => $testCase) {
            $result = $this->temporalFrameCompatibility->selectTemporalFrame(
                $testCase['c1_a'], $testCase['c2_a'],
                $testCase['c1_b'], $testCase['c2_b']
            );
            
            $success = $result['selected_actor'] === $testCase['expected_actor'];
            if ($success) $passed++;
            
            echo sprintf(
                "  Test %d: Select (%.1f,%.1f) vs (%.1f,%.1f) -> %s %s\n",
                $index + 1,
                $testCase['c1_a'], $testCase['c2_a'],
                $testCase['c1_b'], $testCase['c2_b'],
                $result['selected_actor'],
                $success ? '✅' : '❌'
            );
        }
        
        $this->testResults['frame_selection'] = [
            'passed' => $passed,
            'total' => $total,
            'success_rate' => $passed / $total
        ];
        
        echo "  Result: {$passed}/{$total} tests passed\n\n";
    }
    
    /**
     * Test bridge state creation
     */
    private function testBridgeStateCreation() {
        echo "🧪 Testing Bridge State Creation...\n";
        
        $bridgeState = $this->temporalFrameCompatibility->createBridgeState(
            0.8, 0.7, 1.6, 0.4
        );
        
        $tests = [
            'mode' => ['bridge', $bridgeState['mode']],
            'c1_modes_count' => [2, count($bridgeState['c1_modes'])],
            'c2_modes_count' => [2, count($bridgeState['c2_modes'])],
            'resolution_required' => [true, $bridgeState['resolution_required']],
            'c1_modes_values' => [[0.8, 1.6], $bridgeState['c1_modes']],
            'c2_modes_values' => [[0.7, 0.4], $bridgeState['c2_modes']],
        ];
        
        $passed = 0;
        $total = count($tests);
        
        foreach ($tests as $testName => [$expected, $actual]) {
            $success = $expected === $actual;
            if ($success) $passed++;
            
            echo sprintf(
                "  %s: Expected %s, got %s %s\n",
                $testName,
                json_encode($expected),
                json_encode($actual),
                $success ? '✅' : '❌'
            );
        }
        
        $this->testResults['bridge_creation'] = [
            'passed' => $passed,
            'total' => $total,
            'success_rate' => $passed / $total
        ];
        
        echo "  Result: {$passed}/{$total} tests passed\n\n";
    }
    
    /**
     * Test frame-aware routing
     */
    private function testFrameAwareRouting() {
        echo "🧪 Testing Frame-Aware Routing...\n";
        
        // Test compatible frame routing
        $compatibleRequest = [
            'id' => 'test_compatible',
            'type' => 'deterministic',
            'content' => 'Test request with compatible frames',
            'actors' => [
                ['temporal_frame' => ['c1' => 1.0, 'c2' => 0.9]]
            ]
        ];
        
        $this->wolfieIdentity->updateConsciousnessCoordinates(1.1, 0.8);
        $result = $this->trinitaryRouter->routeRequest($compatibleRequest);
        
        $compatibleSuccess = isset($result['temporal_state']['frame_compatibility']) &&
                           $result['temporal_state']['frame_compatibility']['compatible'] ?? false;
        
        echo sprintf(
            "  Compatible routing: %s %s\n",
            $compatibleSuccess ? 'PASSED' : 'FAILED',
            $compatibleSuccess ? '✅' : '❌'
        );
        
        // Test incompatible frame routing
        $incompatibleRequest = [
            'id' => 'test_incompatible',
            'type' => 'emotional',
            'content' => 'Test request with incompatible frames',
            'actors' => [
                ['temporal_frame' => ['c1' => 0.2, 'c2' => 0.3]]
            ]
        ];
        
        $this->wolfieIdentity->updateConsciousnessCoordinates(1.5, 0.9);
        $result = $this->trinitaryRouter->routeRequest($incompatibleRequest);
        
        $incompatibleSuccess = isset($result['temporal_state']['frame_compatibility']) &&
                              !$result['temporal_state']['frame_compatibility']['compatible'] &&
                              isset($result['temporal_state']['frame_compatibility']['frame_selection']);
        
        echo sprintf(
            "  Incompatible routing: %s %s\n",
            $incompatibleSuccess ? 'PASSED' : 'FAILED',
            $incompatibleSuccess ? '✅' : '❌'
        );
        
        $passed = ($compatibleSuccess ? 1 : 0) + ($incompatibleSuccess ? 1 : 0);
        $total = 2;
        
        $this->testResults['frame_aware_routing'] = [
            'passed' => $passed,
            'total' => $total,
            'success_rate' => $passed / $total
        ];
        
        echo "  Result: {$passed}/{$total} tests passed\n\n";
    }
    
    /**
     * Test migration with frame reconciliation
     */
    private function testMigrationWithFrameReconciliation() {
        echo "🧪 Testing Migration with Frame Reconciliation...\n";
        
        $testCases = [
            // Compatible migration
            [
                'incoming' => ['temporal_frame' => ['c1' => 1.0, 'c2' => 0.9]],
                'existing' => ['temporal_frame' => ['c1' => 1.1, 'c2' => 0.8]],
                'expected_resolution' => 'blended'
            ],
            
            // Incompatible migration
            [
                'incoming' => ['temporal_frame' => ['c1' => 0.3, 'c2' => 0.4]],
                'existing' => ['temporal_frame' => ['c1' => 1.5, 'c2' => 0.9]],
                'expected_resolution' => 'frame_selected'
            ],
        ];
        
        $passed = 0;
        $total = count($testCases);
        
        foreach ($testCases as $index => $testCase) {
            $result = $this->temporalFrameCompatibility->migrateWithFrameReconciliation(
                $testCase['incoming'],
                $testCase['existing']
            );
            
            $success = $result['resolution'] === $testCase['expected_resolution'];
            if ($success) $passed++;
            
            echo sprintf(
                "  Test %d: Migration -> %s %s\n",
                $index + 1,
                $result['resolution'],
                $success ? '✅' : '❌'
            );
        }
        
        $this->testResults['migration_reconciliation'] = [
            'passed' => $passed,
            'total' => $total,
            'success_rate' => $passed / $total
        ];
        
        echo "  Result: {$passed}/{$total} tests passed\n\n";
    }
    
    /**
     * Test edge cases
     */
    private function testEdgeCases() {
        echo "🧪 Testing Edge Cases...\n";
        
        $edgeCases = [
            // Zero values
            ['c1_a' => 0.0, 'c2_a' => 0.0, 'c1_b' => 0.0, 'c2_b' => 0.0],
            
            // Maximum values
            ['c1_a' => 2.0, 'c2_a' => 1.0, 'c1_b' => 2.0, 'c2_b' => 1.0],
            
            // Mixed optimal and pathological
            ['c1_a' => 1.0, 'c2_a' => 0.9, 'c1_b' => 0.1, 'c2_b' => 0.1],
        ];
        
        $passed = 0;
        $total = count($edgeCases);
        
        foreach ($edgeCases as $index => $case) {
            try {
                $compatibility = $this->temporalFrameCompatibility->isTemporalFrameCompatible(
                    $case['c1_a'], $case['c2_a'],
                    $case['c1_b'], $case['c2_b']
                );
                
                // Just ensure it doesn't crash
                $success = true;
                if ($success) $passed++;
                
                echo sprintf(
                    "  Edge case %d: (%.1f,%.1f) vs (%.1f,%.1f) -> %s ✅\n",
                    $index + 1,
                    $case['c1_a'], $case['c2_a'],
                    $case['c1_b'], $case['c2_b'],
                    $compatibility['compatible'] ? 'COMPATIBLE' : 'INCOMPATIBLE'
                );
                
            } catch (Exception $e) {
                echo sprintf(
                    "  Edge case %d: Exception - %s ❌\n",
                    $index + 1,
                    $e->getMessage()
                );
            }
        }
        
        $this->testResults['edge_cases'] = [
            'passed' => $passed,
            'total' => $total,
            'success_rate' => $passed / $total
        ];
        
        echo "  Result: {$passed}/{$total} tests passed\n\n";
    }
    
    /**
     * Generate comprehensive test report
     */
    private function generateTestReport() {
        echo "=== TEST REPORT ===\n";
        
        $totalPassed = 0;
        $totalTests = 0;
        
        foreach ($this->testResults as $testName => $result) {
            $totalPassed += $result['passed'];
            $totalTests += $result['total'];
            
            echo sprintf(
                "%s: %d/%d tests passed (%.1f%%)\n",
                ucwords(str_replace('_', ' ', $testName)),
                $result['passed'],
                $result['total'],
                $result['success_rate'] * 100
            );
        }
        
        $overallSuccessRate = $totalTests > 0 ? ($totalPassed / $totalTests) * 100 : 0;
        
        echo "\nOVERALL: {$totalPassed}/{$totalTests} tests passed ({$overallSuccessRate:.1f}%)\n";
        
        if ($overallSuccessRate >= 90) {
            echo "🎉 EXCELLENT: Temporal Frame Compatibility v0.5 implementation is robust!\n";
        } elseif ($overallSuccessRate >= 75) {
            echo "✅ GOOD: Implementation mostly working with minor issues.\n";
        } else {
            echo "⚠️  NEEDS WORK: Implementation has significant issues.\n";
        }
        
        // Recommendations
        echo "\n📋 RECOMMENDATIONS:\n";
        
        if (isset($this->testResults['frame_aware_routing']['success_rate']) && 
            $this->testResults['frame_aware_routing']['success_rate'] < 1.0) {
            echo "- Review TrinitaryRouter frame compatibility integration\n";
        }
        
        if (isset($this->testResults['migration_reconciliation']['success_rate']) && 
            $this->testResults['migration_reconciliation']['success_rate'] < 1.0) {
            echo "- Check migration framework frame reconciliation logic\n";
        }
        
        if ($overallSuccessRate >= 90) {
            echo "- Ready for production deployment\n";
            echo "- Consider adding performance benchmarks\n";
        } else {
            echo "- Address failing tests before deployment\n";
            echo "- Review temporal frame compatibility algorithm\n";
        }
        
        echo "\n🎯 NEXT STEPS:\n";
        echo "1. Fix any failing tests\n";
        echo "2. Run integration tests with full WOLFIE system\n";
        echo "3. Validate performance under load\n";
        echo "4. Deploy to staging environment\n";
        echo "5. Monitor temporal frame compatibility in production\n";
    }
}

// Command line interface
if (php_sapi_name() === 'cli') {
    $testSuite = new TemporalFrameCompatibilityTest();
    $testSuite->runTestSuite();
}
?>
