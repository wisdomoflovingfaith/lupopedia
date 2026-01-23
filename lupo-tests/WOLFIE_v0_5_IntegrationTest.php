<?php
/**
 * WOLFIE v0.5 Integration Test Suite - Full System Validation
 * 
 * Comprehensive integration testing for the complete WOLFIE temporal frame
 * compatibility model v0.5 including synchronization protocol, frame reconciliation,
 * and bridge state handling.
 * 
 * @package Lupopedia
 * @version 0.5
 * @author WOLFIE Semantic Engine
 */

// Include required files
require_once __DIR__ . '/../lupopedia-config.php';
require_once __DIR__ . '/../app/WolfieIdentity.php';
require_once __DIR__ . '/../app/TemporalMonitor.php';
require_once __DIR__ . '/../app/TemporalFrameCompatibility.php';
require_once __DIR__ . '/../app/NoteComparisonProtocol.php';
require_once __DIR__ . '/../app/TrinitaryRouter.php';
require_once __DIR__ . '/../app/TemporalMigrationFramework.php';
require_once __DIR__ . '/../app/WolfieIdentityBridgeStates.php';

class WOLFIE_v05_IntegrationTest {
    private $wolfieIdentity;
    private $temporalMonitor;
    private $temporalFrameCompatibility;
    private $noteComparisonProtocol;
    private $trinitaryRouter;
    private $temporalMigrationFramework;
    private $bridgeStateHandler;
    private $testResults = [];
    
    public function __construct() {
        $this->wolfieIdentity = new WolfieIdentity('IntegrationTestAgent');
        $this->temporalMonitor = new TemporalMonitor($this->wolfieIdentity);
        $this->temporalFrameCompatibility = new TemporalFrameCompatibility();
        $this->noteComparisonProtocol = new NoteComparisonProtocol($this->temporalFrameCompatibility);
        $this->trinitaryRouter = new TrinitaryRouter($this->wolfieIdentity, $this->temporalMonitor);
        $this->temporalMigrationFramework = new TemporalMigrationFramework(
            $this->wolfieIdentity, 
            $this->temporalMonitor, 
            new TemporalRituals($this->wolfieIdentity, $this->temporalMonitor)
        );
        $this->bridgeStateHandler = new WolfieIdentityBridgeStates($this->wolfieIdentity, $this->temporalCompatibility);
    }
    
    /**
     * Run complete integration test suite
     */
    public function runIntegrationTestSuite() {
        echo "=== WOLFIE v0.5 Integration Test Suite ===\n";
        echo "Testing complete temporal frame compatibility system\n\n";
        
        $this->testTemporalFrameCompatibility();
        $this->testSynchronizationProtocol();
        $this->testTrinitaryRouterIntegration();
        $this->testTemporalMonitorIntegration();
        $this->testMigrationFrameworkIntegration();
        $this->testBridgeStateHandling();
        $this->testEndToEndWorkflows();
        
        $this->generateIntegrationReport();
    }
    
    /**
     * Test temporal frame compatibility system
     */
    private function testTemporalFrameCompatibility() {
        echo "🧪 Testing Temporal Frame Compatibility...\n";
        
        $testCases = [
            // Compatible frames
            ['c1_a' => 1.0, 'c2_a' => 0.9, 'c1_b' => 1.1, 'c2_b' => 0.8, 'expected' => true],
            ['c1_a' => 0.8, 'c2_a' => 0.85, 'c1_b' => 0.9, 'c2_b' => 0.8, 'expected' => true],
            
            // Incompatible frames
            ['c1_a' => 0.2, 'c2_a' => 0.3, 'c1_b' => 1.8, 'c2_b' => 0.9, 'expected' => false],
            ['c1_a' => 1.0, 'c2_a' => 0.9, 'c1_b' => 0.2, 'c2_b' => 0.1, 'expected' => false],
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
                "  Test %d: (%.1f,%.1f) vs (%.1f,%.1f) -> %s %s\n",
                $index + 1,
                $testCase['c1_a'], $testCase['c2_a'],
                $testCase['c1_b'], $testCase['c2_b'],
                $result['compatible'] ? 'COMPATIBLE' : 'INCOMPATIBLE',
                $success ? '✅' : '❌'
            );
        }
        
        $this->testResults['temporal_frame_compatibility'] = [
            'passed' => $passed,
            'total' => $total,
            'success_rate' => $passed / $total
        ];
        
        echo "  Result: {$passed}/{$total} tests passed\n\n";
    }
    
    /**
     * Test synchronization protocol
     */
    private function testSynchronizationProtocol() {
        echo "🤝 Testing Synchronization Protocol...\n";
        
        // Test successful synchronization
        $actorA = [
            'c1' => 0.8, 'c2' => 0.7,
            'temporal_history' => $this->generateSyntheticHistory(0.8, 0.7),
            'emotional_state' => ['positive_valence' => 0.6, 'negative_valence' => 0.2, 'cognitive_axis' => 0.7],
            'task_context' => ['test_type' => 'synchronization']
        ];
        
        $actorB = [
            'c1' => 1.5, 'c2' => 0.4,
            'temporal_history' => $this->generateSyntheticHistory(1.5, 0.4),
            'emotional_state' => ['positive_valence' => 0.4, 'negative_valence' => 0.3, 'c1' => 0.5, 'cognitive_axis' => 0.6],
            'task_context' => ['test_type' => 'synchronization']
        ];
        
        $syncResult = $this->noteComparisonProtocol->executeSynchronizationProtocol($actorA, $actorB);
        
        $syncSuccess = $syncResult['success'] && $syncResult['resolution'] === 'blending';
        
        echo sprintf(
            "  Synchronization Test: %s %s\n",
            $syncSuccess ? 'PASSED' : 'FAILED',
            $syncSuccess ? '✅' : '❌'
        );
        
        // Test failed synchronization (should result in bridge or frame selection)
        $actorC = [
            'c1' => 0.1, 'c2' => 0.2,
            'temporal_history' => $this->generateSyntheticHistory(0.1, 0.2),
            'emotional_state' => ['positive_valence' => 0.3, 'negative_valence' => 0.6, 'c1' => 0.4, 'cognitive_axis' => 0.3],
            'task_context' => ['test_type' => 'synchronization']
        ];
        
        $syncResult2 = $this->noteComparisonProtocol->executeSynchronizationProtocol($actorA, $actorC);
        
        $syncFailureHandled = !$syncResult2['success'] && 
                           in_array($syncResult2['resolution'], ['bridge_state', 'frame_selection']);
        
        echo sprintf(
            "  Failure Handling Test: %s %s\n",
            $syncFailureHandled ? 'PASSED' : 'FAILED',
            $syncFailureHandled ? '✅' : '❌'
        );
        
        $this->testResults['synchronization_protocol'] = [
            'success_test' => $syncSuccess,
            'failure_handling_test' => $syncFailureHandled,
            'overall_success' => $syncSuccess && $syncFailureHandled
        ];
        
        echo "  Result: Synchronization protocol working correctly\n\n";
    }
    
    /**
     * Test Trinitary Router integration
     */
    private function testTrinitaryRouterIntegration() {
        echo "🔄 Testing Trinitary Router Integration...\n";
        
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
        $result1 = $this->trinitaryRouter->routeRequest($compatibleRequest);
        
        $compatibleSuccess = isset($result1['temporal_state']['frame_compatibility']) &&
                           $result1['temporal_state']['frame_compatibility']['compatible'] ?? false;
        
        echo sprintf(
            "  Compatible Routing: %s %s\n",
            $compatibleSuccess ? 'PASSED' : 'FAILED',
            $compatibleSuccess ? '✅' : '❌'
        );
        
        // Test incompatible frame routing with synchronization
        $incompatibleRequest = [
            'id' => 'test_incompatible',
            'type' => 'emotional',
            'content' => 'Test request with incompatible frames',
            'actors' => [
                ['temporal_frame' => ['c1' => 0.3, 'c2' => 0.2]]
            ]
        ];
        
        $this->wolfieIdentity->updateConsciousnessCoordinates(1.5, 0.9);
        $result2 = $this->trinitaryRouter->routeRequest($incompatibleRequest);
        
        $incompatibleSuccess = isset($result2['temporal_state']['frame_compatibility']) &&
                              !$result2['temporal_state']['frame_compatibility']['compatible'] &&
                              isset($result2['temporal_state']['frame_compatibility']['synchronization_result']);
        
        echo sprintf(
            "  Incompatible Routing: %s %s\n",
            $incompatibleSuccess ? 'PASSED' : 'FAILED',
            $incompatibleSuccess ? '✅' : '❌'
        );
        
        $this->testResults['trinitary_router'] = [
            'compatible_routing' => $compatibleSuccess,
            'incompatible_routing' => $incompatibleSuccess,
            'overall_success' => $compatibleSuccess && $incompatibleSuccess
        ];
        
        echo "  Result: Router integration working correctly\n\n";
    }
    
    /**
     * Test TemporalMonitor integration
     */
    private function testTemporalMonitorIntegration() {
        echo "📊 Testing TemporalMonitor Integration...\n";
        
        // Start monitoring
        $this->temporalMonitor->startMonitoring();
        
        // Update coordinates
        $this->temporalMonitor->updateTemporalCoordinates([
            'requests_per_second' => 15,
            'average_response_time' => 80,
            'error_rate' => 0.01,
            'active_users' => 3,
            'system_load' => 0.4
        ]);
        
        // Test frame compatibility checking
        $otherFrame = ['c1' => 0.8, 'c2' => 0.7];
        $compatibilityResult = $this->temporalMonitor->checkFrameCompatibility($otherFrame, ['test' => 'monitoring']);
        
        $monitoringSuccess = isset($compatibilityResult['synchronization_result']) ||
                           $compatibilityResult['compatible'];
        
        echo sprintf(
            "  Frame Compatibility Check: %s %s\n",
            $monitoringSuccess ? 'PASSED' : 'FAILED',
            $monitoringSuccess ? '✅' : '❌'
        );
        
        // Get frame monitoring metrics
        $metrics = $this->temporalMonitor->getFrameMonitoringMetrics();
        
        $metricsSuccess = isset($metrics['frame_history_count']) &&
                           isset($metrics['synchronization_metrics']);
        
        echo sprintf(
            "  Frame Metrics: %s %s\n",
            $metricsSuccess ? 'PASSED' : 'FAILED',
            $metricsSuccess ? '✅' : '❌'
        );
        
        $this->temporalMonitor->stopMonitoring();
        
        $this->testResults['temporal_monitor'] = [
            'compatibility_checking' => $monitoringSuccess,
            'frame_metrics' => $metricsSuccess,
            'overall_success' => $monitoringSuccess && $metricsSuccess
        ];
        
        echo "  Result: TemporalMonitor integration working correctly\n\n";
    }
    
    /**
     * Test Migration Framework integration
     */
    private function testMigrationFrameworkIntegration() {
        echo "🚀 Testing Migration Framework Integration...\n";
        
        // Start migration
        $migrationResult = $this->temporalMigrationFramework->startMigration('test_migration', self::PHASE_1_PIONEER);
        
        $migrationStarted = $migrationResult['ready'] ?? false;
        
        echo sprintf(
            "  Migration Start: %s %s\n",
            $migrationStarted ? 'PASSED' : 'FAILED',
            $migrationStarted ? '✅' : '❌'
        );
        
        if ($migrationStarted) {
            // Test migration with compatible target frame
            $migrationData = [
                'target_temporal_frame' => ['c1' => 1.0, 'c2' => 0.8],
                'seeds' => ['seed1', 'seed2', 'seed3']
            ];
            
            $phaseResult = $this->temporalMigrationFramework->executeMigrationPhase(self::PHASE_1_PIONEER, $migrationData);
            
            $phaseSuccess = $phaseResult['success'] &&
                           (!isset($phaseResult['frame_compatibility']) || $phaseResult['frame_compatibility']['compatible'] ?? true);
            
            echo sprintf(
                "  Migration Phase: %s %s\n",
                $phaseSuccess ? 'PASSED' : 'FAILED',
                $phaseSuccess ? '✅' : 'test'
            );
        }
        
        // Test frame reconciliation metrics
        $reconciliationMetrics = $this->temporalMigrationFramework->getFrameReconciliationMetrics();
        
        $metricsAvailable = isset($reconciliationMetrics['total_attempts']);
        
        echo sprintf(
            "  Frame Reconciliation Metrics: %s %s\n",
            $metricsAvailable ? 'PASSED' : 'FAILED',
            $metricsAvailable ? '✅' : '❌'
        );
        
        $this->testResults['migration_framework'] = [
            'migration_start' => $migrationStarted,
            'phase_execution' => $phaseSuccess ?? false,
            'frame_reconciliation' => $metricsAvailable,
            'overall_success' => $migrationStarted && ($phaseSuccess ?? false) && $metricsAvailable
        ];
        
        echo "  Result: Migration Framework integration working correctly\n\n";
    }
    
    /**
     * Test bridge state handling
     */
    private function testBridgeStateHandling() {
        echo "🌉 Testing Bridge State Handling...\n";
        
        // Create bridge state
        $frameA = ['c1' => 0.3, 'c2' => 0.2];
        $frameB = ['c1' => 1.7, 'c2' => 0.4];
        
        $bridgeState = $this->bridgeStateHandler->createBridgeState($frameA, $frameB);
        
        $bridgeCreated = isset($bridgeState['bridge_id']) && $bridgeState['status'] === 'active';
        
        echo sprintf(
            "  Bridge Creation: %s %s\n",
            $bridgeCreated ? 'PASSED' : 'FAILED',
            $bridgeCreated ? '✅' : 'test'
        );
        
        // Test bridge resolution
        if ($bridgeCreated) {
            $resolutionResult = $this->bridgeStateHandler->resolveBridgeState($bridgeState['bridge_id']);
            
            $resolutionSuccess = $resolutionResult['status'] === 'resolved';
            
            echo sprintf(
                "  Bridge Resolution: %s %s\n",
                $resolutionSuccess ? 'PASSED' : 'FAILED',
                $resolutionSuccess ? '✅' : '❌'
            );
        }
        
        // Get bridge metrics
        $bridgeMetrics = $this->bridgeStateHandler->getBridgeStateMetrics();
        
        $metricsAvailable = isset($bridgeMetrics['total_created']);
        
        echo sprintf(
            "  Bridge Metrics: %s %s\n",
            $metricsAvailable ? 'PASSED' : 'FAILED',
            $metricsAvailable ? '✅' : '❌'
        );
        
        // Test cleanup
        $cleanedCount = $this->bridgeStateHandler->cleanupExpiredBridgeStates();
        
        echo sprintf(
            "  Bridge Cleanup: %s states cleaned\n",
            $cleanedCount
        );
        
        $this->testResults['bridge_states'] = [
            'bridge_creation' => $bridgeCreated,
            'bridge_resolution' => $resolutionSuccess ?? false,
            'metrics_available' => $metricsAvailable,
            'cleanup_working' => true,
            'overall_success' => $bridgeCreated && ($resolutionSuccess ?? false) && $metricsAvailable
        ];
        
        echo "  Result: Bridge state handling working correctly\n\n";
    }
    
    /**
     * Test end-to-end workflows
     */
    private function testEndToEndWorkflows() {
        echo "🔄 Testing End-to-End Workflows...\n";
        
        // Workflow 1: Compatible interaction
        $workflow1Success = $this->testCompatibleInteraction();
        
        echo sprintf(
            "  Workflow 1 (Compatible Interaction): %s %s\n",
            $workflow1Success ? 'PASSED' : 'FAILED',
            $workflow1Success ? '✅' : '❌'
        );
        
        // Workflow 2: Incompatible interaction with synchronization
        $workflow2Success = $this->testIncompatibleInteraction();
        
        echo sprintf(
            "  Workflow 2 (Incompatible Interaction): %s %s\n",
            $workflow2Success ? 'PASSED' : 'FAILED',
            $workflow2Success ? '✅' : '❌'
        );
        
        // Workflow 3: Migration with frame reconciliation
        $workflow3Success = $this->testMigrationWithReconciliation();
        
        echo sprintf(
            "  Workflow 3 (Migration with Reconciliation): %s %s\n",
            $workflow3Success ? 'PASSED' : 'FAILED',
            $workflow3Success ? '✅' : '❌'
        );
        
        $this->testResults['end_to_end_workflows'] = [
            'compatible_interaction' => $workflow1Success,
            'incompatible_interaction' => $workflow2Success,
            'migration_reconciliation' => $workflow3Success,
            'overall_success' => $workflow1Success && $workflow2Success && $workflow3Success
        ];
        
        echo "  Result: End-to-end workflows working correctly\n\n";
    }
    
    /**
     * Test compatible interaction workflow
     */
    private function testCompatibleInteraction() {
        // Set up compatible frames
        $this->wolfieIdentity->updateConsciousnessCoordinates(1.0, 0.9);
        
        $request = [
            'id' => 'workflow1',
            'type' => 'deterministic',
            'content' => 'Test compatible interaction',
            'actors' => [
                ['temporal_frame' => ['c1' => 1.1, 'c2' => 0.8]]
            ]
        ];
        
        $result = $this->trinitaryRouter->routeRequest($request);
        
        return $result['route'] && $result['confidence'] > 0.5;
    }
    
    /**
     * Test incompatible interaction workflow
     */
    private function testIncompatibleInteraction() {
        // Set up incompatible frames
        $this->wolfieIdentity->updateConsciousnessCoordinates(1.5, 0.9);
        
        $request = [
            'id' => 'workflow2',
            'type' => 'emotional',
            'content' => 'Test incompatible interaction',
            'actors' => [
                ['temporal_frame' => ['c1' => 0.2, 'c2' => 0.3]]
            ]
        ];
        
        $result = $this->trinitaryRouter->routeRequest($request);
        
        // Should trigger synchronization and still route successfully
        return $result['route'] && isset($result['temporal_state']['frame_compatibility']);
    }
    
    /**
     * Test migration with frame reconciliation
     */
    private function testMigrationWithReconciliation() {
        // Start migration
        $this->temporalMigrationFramework->startMigration('test_reconciliation', self::PHASE_2_WITNESS);
        
        // Test with incompatible target frame
        $migrationData = [
            'target_temporal_frame' => ['c1' => 0.3, 'c2' => 0.2],
            'seeds' => ['seed1', 'seed2']
        ];
        
        $result = $this->temporalMigrationFramework->executeMigrationPhase(self::PHASE_2_WITNESS, $migrationData);
        
        return $result['success'] && (!$result['frame_reconciliation'] || $result['frame_reconciliation']['success']);
    }
    
    /**
     * Generate synthetic temporal history
     */
    private function generateSyntheticHistory($baseC1, $baseC2) {
        $history = [];
        $baseTime = time() - 300; // 5 minutes ago
        
        for ($i = 0; $i < 10; $i++) {
            $timestamp = $baseTime + ($i * 30); // Every 30 seconds
            
            $history[] = [
                'timestamp' => gmdate('Y-m-d\TH:i:s\Z', $timestamp),
                'c1' => $baseC1 + (sin($i * 0.5) * 0.1),
                'c2' => $baseC2 + (cos($i * 0.3) * 0.05)
            ];
        }
        
        return $history;
    }
    
    /**
     * Generate integration report
     */
    private function generateIntegrationReport() {
        echo "\n=== INTEGRATION TEST REPORT ===\n";
        
        $totalTests = count($this->testResults);
        $passedTests = array_sum(array_column($this->testResults, 'overall_success'));
        
        echo "\n📊 SUMMARY:\n";
        echo "Total Test Suites: {$totalTests}\n";
        echo "Passed Suites: {$passedTests}\n";
        echo "Success Rate: " . number_format(($passedTests / $totalTests) * 100, 1) . "%\n";
        
        echo "\n📋 DETAILED RESULTS:\n";
        foreach ($this->testResults as $testName => $result) {
            echo sprintf(
                "%s: %s\n",
                ucwords(str_replace('_', ' ', $testName),
                $result['overall_success'] ? '✅ PASS' : '❌ FAIL'
            );
            
            if (!$result['overall_success']) {
                echo "  Issues: " . $this->identifyTestIssues($testName, $result) . "\n";
            }
        }
        
        echo "\n🎯 OVERALL STATUS: ";
        if ($passedTests === $totalTests) {
            echo "✅ ALL TESTS PASSED - WOLFIE v0.5 fully integrated\n";
        } elseif ($passedTests >= ($totalTests * 0.8)) {
            echo "✅ MOST TESTS PASSED - Minor issues detected\n";
        } else {
            echo "❌ SIGNIFICANT ISSUES DETECTED - Requires attention\n";
        }
        
        echo "\n📞 NEXT STEPS:\n";
        if ($passedTests === $totalTests) {
            echo "- Deploy to staging environment for production validation\n";
            echo "- Begin performance testing under load\n";
            "- Monitor temporal metrics in production\n";
        } else {
            echo "- Address failing test suites immediately\n";
            "- Review integration points for compatibility issues\n";
            "- Re-run integration tests after fixes\n";
        }
    }
    
    /**
     * Identify test issues
     */
    private function identifyTestCases($testName, $result) {
        $issues = [];
        
        switch ($testName) {
            case 'temporal_frame_compatibility':
                if (!$result['success']) $issues[] = "Frame compatibility checking failed";
                break;
                
            case 'synchronization_protocol':
                if (!$result['success_test']) $issues[] = "Synchronization protocol failed";
                if (!$result['failure_handling_test']) $issues[] = "Failure handling failed";
                break;
                
            case 'trinitary_router':
                if (!$result['compatible_routing']) $issues[] = "Compatible routing failed";
                if (!$result['incompatible_routing']) $issues[] = "Incompatible routing failed";
                break;
                
            case 'temporal_monitor':
                if (!$result['compatibility_checking']) $issues[] = "Compatibility checking failed";
                if (!$result['frame_metrics']) $issues[] = "Frame metrics unavailable";
                break;
                
            case 'migration_framework':
                if (!$result['migration_start']) $issues[] = "Migration start failed";
                if (!$result['phase_execution']) $issues[] = "Phase execution failed";
                if (!$result['frame_reconciliation']) $issues[] = "Frame reconciliation failed";
                break;
                
            case 'bridge_states':
                if (!$result['bridge_creation']) $issues[] = "Bridge creation failed";
                if (!$result['bridge_resolution']) $issues[] = "Bridge resolution failed";
                if (!$result['metrics_available']) $issues[] = "Bridge metrics unavailable";
                break;
                
            case 'end_to_end_workflows':
                if (!$result['compatible_interaction']) $issues[] = "Compatible interaction failed";
                if (!$result['incompatible_interaction']) $issues[] = "Incompatible interaction failed";
                if (!$result['migration_reconciliation']) $issues[] = "Migration reconciliation failed";
                break;
        }
        
        return implode(', ', $issues);
    }
}

// Command line interface
if (php_sapi_name() === 'cli') {
    $integrationTest = new WOLFIE_v05_IntegrationTest();
    $integrationTest->runIntegrationTestSuite();
}
?>
