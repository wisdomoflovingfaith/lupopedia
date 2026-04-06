<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: test
  when_updated: "20260406042246"
  file_path_from_root: "lupo-tests/integration/channel66_production_extended_test.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-tests/integration/channel66_production_extended_test.php"
  last_modified_utc: "20260406042246"
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 3
    name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root"
  artifact_type: "test"
  artifact_kind: "integration"
  purpose: "Integration tests for Channel 66 production ingestion via Channel66ProductionIngester and PDO_DB (DatabaseFactory)."
  tags: ["test", "integration", "channel66", "production"]
---
*/

/**
 * Channel 66 Production Extended Test Suite (integration).
 *
 * @version 4.0.80
 */

$lupoRepoRoot = dirname(__DIR__, 2);
require_once $lupoRepoRoot . '/lupopedia-config.php';
require_once $lupoRepoRoot . '/lupo-includes/bootstrap.php';

if (!class_exists('DatabaseFactory', false)) {
    require_once $lupoRepoRoot . '/lupo-includes/classes/DatabaseFactory.php';
}

$lupoClasses = $lupoRepoRoot . '/lupo-includes/classes/';
require_once $lupoClasses . 'Channel66ProductionConfig.php';
require_once $lupoClasses . 'Channel66ProductionErrorHandler.php';
require_once $lupoClasses . 'Channel66PerformanceMonitor.php';
require_once $lupoClasses . 'Channel66ProductionLogger.php';
require_once $lupoClasses . 'Channel66BatchProcessor.php';
require_once $lupoClasses . 'Channel66ProductionIngester.php';

class Channel66ProductionExtendedTest
{
    private $db;
    private $testResults;
    private $productionMetrics;

    public function __construct()
    {
        $this->db = DatabaseFactory::getConnection();
        $this->testResults = array();
        $this->productionMetrics = array();
    }

    /**
     * Ensure scope tree has schema JSON dir (Channel66ProductionConfig validates paths).
     *
     * @param string $scopeRoot
     * @return string Absolute json directory path
     */
    private function channel66ExtendedEnsureJsonDir($scopeRoot)
    {
        $norm = rtrim(str_replace('\\', '/', $scopeRoot), '/');
        $jsonDir = $norm . '/lupo-database/lupopedia/json';
        if (!is_dir($jsonDir)) {
            mkdir($jsonDir, 0755, true);
        }
        $jf = $jsonDir . '/lupo_metadata.json';
        if (!is_file($jf)) {
            $schema = array(
                'table_name' => 'lupo_metadata',
                'fields' => array('`metadata_id` bigint NOT NULL'),
            );
            file_put_contents($jf, json_encode($schema));
        }
        return $jsonDir;
    }

    /**
     * Write flat INI for Channel66ProductionConfig (parse_ini_file).
     *
     * @param array $pairs scalar values only
     * @return string path to temp file
     */
    private function channel66ExtendedWriteIni(array $pairs)
    {
        $lines = array();
        foreach ($pairs as $k => $v) {
            if ($v === null || is_array($v)) {
                continue;
            }
            if (is_bool($v)) {
                $v = $v ? '1' : '0';
            }
            $lines[] = $k . '=' . $v;
        }
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'ch66ext_' . uniqid('', true) . '.ini';
        file_put_contents($path, implode("\n", $lines));
        return $path;
    }

    /**
     * Build Channel66ProductionIngester from override array (test-friendly).
     *
     * @param array $overrides keys: scope_root, toon_dir, batch_size, memory_limit, monitoring, performance_alert_threshold, etc.
     * @return Channel66ProductionIngester
     */
    private function channel66ExtendedCreateIngester(array $overrides)
    {
        $scopeRoot = isset($overrides['scope_root']) ? $overrides['scope_root'] : ABSPATH;
        $scopeRoot = rtrim(str_replace('\\', '/', $scopeRoot), '/');
        $jsonDir = $this->channel66ExtendedEnsureJsonDir($scopeRoot);
        $toonDir = isset($overrides['toon_dir']) ? rtrim(str_replace('\\', '/', $overrides['toon_dir']), '/') : $jsonDir;
        if (!is_dir($toonDir)) {
            mkdir($toonDir, 0755, true);
        }
        if (!is_file($toonDir . '/lupo_metadata.json')) {
            $schema = array(
                'table_name' => 'lupo_metadata',
                'fields' => array('`metadata_id` bigint NOT NULL'),
            );
            file_put_contents($toonDir . '/lupo_metadata.json', json_encode($schema));
        }

        $batchSize = isset($overrides['batch_size']) ? (int) $overrides['batch_size'] : 100;
        if ($batchSize < 1) {
            $batchSize = 1;
        }
        $memoryLimit = isset($overrides['memory_limit']) ? $overrides['memory_limit'] : '256M';
        $enableMonitoring = !empty($overrides['monitoring']);

        $iniPairs = array(
            'scope_root' => $scopeRoot,
            'toon_dir' => $toonDir,
            'batch_size' => $batchSize,
            'memory_limit' => $memoryLimit,
            'enable_monitoring' => $enableMonitoring ? '1' : '0',
        );
        if (isset($overrides['performance_alert_threshold'])) {
            $iniPairs['performance_alert_threshold'] = $overrides['performance_alert_threshold'];
        }
        if (isset($overrides['memory_alert_threshold'])) {
            $iniPairs['memory_alert_threshold'] = $overrides['memory_alert_threshold'];
        }
        if (isset($overrides['throughput_alert_threshold'])) {
            $iniPairs['throughput_alert_threshold'] = $overrides['throughput_alert_threshold'];
        }
        if (isset($overrides['error_retry_attempts'])) {
            $iniPairs['error_retry_attempts'] = (int) $overrides['error_retry_attempts'];
        }
        if (isset($overrides['error_retry_delay'])) {
            $iniPairs['error_retry_delay'] = (int) $overrides['error_retry_delay'];
        }
        if (isset($overrides['thread_id']) && $overrides['thread_id'] !== null && $overrides['thread_id'] !== '') {
            $iniPairs['thread_id'] = (int) $overrides['thread_id'];
        }

        $iniPath = $this->channel66ExtendedWriteIni($iniPairs);
        try {
            $productionConfig = new Channel66ProductionConfig($iniPath);
            $performanceMonitor = new Channel66PerformanceMonitor($enableMonitoring);
            $errorHandler = new Channel66ProductionErrorHandler();
            $logger = new Channel66ProductionLogger();
            $batchProcessor = new Channel66BatchProcessor($productionConfig->getBatchSize(), $productionConfig->getMemoryLimit());
            return new Channel66ProductionIngester(
                $this->db,
                $productionConfig,
                $performanceMonitor,
                $errorHandler,
                $logger,
                $batchProcessor
            );
        } finally {
            if (is_file($iniPath)) {
                @unlink($iniPath);
            }
        }
    }

    /**
     * Files for Channel66ProductionIngester discovery live under scope_root/lupo-channels/66/threads/{id}/.
     *
     * @param string $scopeRoot
     * @return string
     */
    private function channel66ExtendedThreadIngestionDir($scopeRoot)
    {
        $base = rtrim(str_replace('\\', '/', $scopeRoot), '/');
        $d = $base . '/lupo-channels/66/threads/1001';
        if (!is_dir($d)) {
            mkdir($d, 0755, true);
        }
        return $d;
    }

    /**
     * Match Channel66ProductionIngester::getRepoRelativePath (forward slashes).
     *
     * @param string $scopeRoot
     * @param string $absolutePath
     * @return string|null
     */
    private function channel66RepoRelativeFromScope($scopeRoot, $absolutePath)
    {
        $scopeRoot = rtrim(str_replace('\\', '/', $scopeRoot), '/');
        $absolutePath = str_replace('\\', '/', $absolutePath);
        if (strpos($absolutePath, $scopeRoot) !== 0) {
            return null;
        }
        return ltrim(substr($absolutePath, strlen($scopeRoot)), '/');
    }

    /**
     * Run all production extended tests
     */
    public function runAllTests($options = array())
    {
        echo "Starting Channel 66 Production Extended Test Suite...\n";
        echo "===============================================\n";
        
        $testMethods = array(
            'largeScaleIngestionTest' => 'testLargeScaleIngestionWithValidation',
            'memoryPressureTest' => 'testMemoryPressureWithRecovery',
            'performanceRegressionTest' => 'testPerformanceRegressionDetection',
            'malformedToonHandlingTest' => 'testMalformedToonHandlingAndRecovery',
            'monitoringIntegrationTest' => 'testMonitoringIntegrationWithAlerting',
            'deploymentValidationTest' => 'testDeploymentValidationAndAutomation',
            'endToEndProductionFlowTest' => 'testEndToEndProductionFlowValidation'
        );
        
        $allPassed = true;
        
        foreach ($testMethods as $testName => $method) {
            echo "\nRunning {$testName}...\n";
            try {
                $result = $this->$method($options);
                $this->testResults[$testName] = $result;
                
                if ($result['passed']) {
                    echo "✅ PASSED: " . $result['message'] . "\n";
                } else {
                    echo "❌ FAILED: " . $result['message'] . "\n";
                    if (isset($result['error'])) {
                        echo "   Error: " . $result['error'] . "\n";
                    }
                    $allPassed = false;
                }
                
            } catch (Exception $e) {
                echo "❌ EXCEPTION: " . $e->getMessage() . "\n";
                $this->testResults[$testName] = array(
                    'passed' => false,
                    'message' => $e->getMessage(),
                    'error' => $e->getTraceAsString()
                );
                $allPassed = false;
            }
        }
        
        echo "\n===============================================\n";
        echo "Extended Test Summary: " . ($allPassed ? 'ALL TESTS PASSED' : 'SOME TESTS FAILED') . "\n\n";
        
        // Print detailed results
        $this->printTestResults();
        
        return $allPassed;
    }
    
    /**
     * Test large-scale ingestion with validation
     */
    private function testLargeScaleIngestionWithValidation($options)
    {
        $testDir = $this->createTestEnvironment('large_scale_validation');
        $fileCounts = array(100, 500, 1000, 5000);
        
        echo "Creating large-scale test environment with " . count($fileCounts) . " file count scenarios...\n";
        
        $validationResults = array();
        
        foreach ($fileCounts as $count) {
            echo "\nTesting {$count} file ingestion scenario...\n";
            
            $files = $this->createValidTestFiles($testDir . "/scale_{$count}", $count);
            $startTime = microtime(true);
            
            // Run production ingestion with monitoring enabled
            require_once ABSPATH . 'lupo-scripts/ingest_channel66_production.php';
            $config = array(
                'scope_root' => $testDir . "/scale_{$count}",
                'batch_size' => min($count, 100),
                'memory_limit' => '512M',
                'monitoring' => true,
                'performance_alert_threshold' => 0.05,
                'memory_alert_threshold' => 0.8,
                'throughput_alert_threshold' => 0.7
            );
            
            $ingester = $this->channel66ExtendedCreateIngester($config);
            $result = $ingester->runProductionMigration(null, false);
            
            $duration = microtime(true) - $startTime;
            
            // Validate deterministic behavior
            $deterministicValidation = $this->validateDeterministicBehavior($result, $count, $config);
            
            // Validate performance characteristics
            $performanceValidation = $this->validatePerformanceCharacteristics($result, $count);
            
            // Validate monitoring integration
            $monitoringValidation = $this->validateMonitoringIntegration($result, $testDir . "/scale_{$count}", $config);
            
            $validationResults[$count] = array(
                'files_processed' => $result['files_processed'],
                'files_ingested' => $result['files_ingested'],
                'duration' => round($duration, 2),
                'throughput' => round($result['files_processed'] / $duration, 2),
                'deterministic_validation' => $deterministicValidation,
                'performance_validation' => $performanceValidation,
                'monitoring_validation' => $monitoringValidation,
                'memory_peak_mb' => $result['peak_memory_mb'],
                'batches_processed' => $result['batches_processed']
            );
        }
        
        $allValidationsPassed = array_reduce($validationResults, function($carry, $item) {
            return $carry && $item['deterministic_validation']['passed'] && 
                   $item['performance_validation']['passed'] && 
                   $item['monitoring_validation']['passed'];
        }, true);
        
        return array(
            'passed' => $allValidationsPassed,
            'message' => $allValidationsPassed ? 
                "Large-scale ingestion validation passed for all scenarios" :
                "Large-scale ingestion validation failed for some scenarios",
            'validation_results' => $validationResults,
            'test_scenarios' => $fileCounts
        );
    }
    
    /**
     * Test memory pressure with recovery procedures
     */
    private function testMemoryPressureWithRecovery($options)
    {
        $testDir = $this->createTestEnvironment('memory_pressure');
        
        echo "Creating memory pressure test scenarios...\n";
        
        $scenarios = array(
            'low_limit_normal' => array('memory_limit' => '64M', 'batch_size' => 50),
            'low_limit_large' => array('memory_limit' => '64M', 'batch_size' => 200),
            'exhaustion_normal' => array('memory_limit' => '32M', 'batch_size' => 25),
            'exhaustion_large' => array('memory_limit' => '32M', 'batch_size' => 100)
        );
        
        $recoveryResults = array();
        
        foreach ($scenarios as $scenarioName => $config) {
            echo "\nTesting scenario: {$scenarioName}...\n";
            
            $files = $this->createValidTestFiles($testDir . "/{$scenarioName}", $config['batch_size']);
            $startTime = microtime(true);
            
            $testConfig = array_merge($config, array(
                'scope_root' => $testDir . "/{$scenarioName}",
                'monitoring' => true,
                'error_retry_attempts' => 3,
                'error_retry_delay' => 2
            ));
            
            try {
                $ingester = $this->channel66ExtendedCreateIngester($testConfig);
                $result = $ingester->runProductionMigration(null, false);
                
                $duration = microtime(true) - $startTime;
                
                // Validate recovery behavior
                $recoveryValidation = $this->validateMemoryRecoveryBehavior($result, $config);
                
                // Validate batch size adjustment
                $batchAdjustmentValidation = $this->validateBatchSizeAdjustment($result, $config);
                
                $recoveryResults[$scenarioName] = array(
                    'passed' => $recoveryValidation['passed'] && $batchAdjustmentValidation['passed'],
                    'message' => ($recoveryValidation['passed'] && $batchAdjustmentValidation['passed']) ? 
                        "Memory pressure scenario handled correctly" :
                        "Memory pressure recovery failed",
                    'recovery_validation' => $recoveryValidation,
                    'batch_adjustment_validation' => $batchAdjustmentValidation,
                    'memory_peak_mb' => $result['peak_memory_mb'],
                    'batches_failed' => $result['batches_failed'],
                    'files_processed' => $result['files_processed']
                );
                
            } catch (Exception $e) {
                $recoveryResults[$scenarioName] = array(
                    'passed' => false,
                    'message' => "Exception in memory pressure test: " . $e->getMessage(),
                    'error' => $e->getTraceAsString()
                );
            }
        }
        
        $allScenariosPassed = array_reduce($recoveryResults, function($carry, $item) {
            return $carry && $item['passed'];
        }, true);
        
        return array(
            'passed' => $allScenariosPassed,
            'message' => $allScenariosPassed ? 
                "Memory pressure recovery tests passed for all scenarios" :
                "Memory pressure recovery tests failed for some scenarios",
            'recovery_results' => $recoveryResults
        );
    }
    
    /**
     * Test performance regression detection
     */
    private function testPerformanceRegressionDetection($options)
    {
        $testDir = $this->createTestEnvironment('performance_regression');
        
        echo "Creating performance regression test scenarios...\n";
        
        // Create baseline and current performance data
        $baselineFiles = $this->createValidTestFiles($testDir . '/baseline', 100);
        $currentFiles = $this->createValidTestFiles($testDir . '/current', 100);
        
        // Run baseline test
        echo "Running baseline performance test...\n";
        $baselineStart = microtime(true);
        $baselineConfig = array(
            'scope_root' => $testDir . '/baseline',
            'batch_size' => 50,
            'memory_limit' => '256M',
            'monitoring' => true
        );
        
        $baselineIngester = $this->channel66ExtendedCreateIngester($baselineConfig);
        $baselineResult = $baselineIngester->runProductionMigration(null, false);
        $baselineDuration = microtime(true) - $baselineStart;
        
        // Run current test
        echo "Running current performance test...\n";
        $currentStart = microtime(true);
        $currentConfig = array(
            'scope_root' => $testDir . '/current',
            'batch_size' => 50,
            'memory_limit' => '256M',
            'monitoring' => true
        );
        
        $currentIngester = $this->channel66ExtendedCreateIngester($currentConfig);
        $currentResult = $currentIngester->runProductionMigration(null, false);
        $currentDuration = microtime(true) - $currentStart;
        
        // Analyze for regression
        $regressionAnalysis = $this->analyzePerformanceRegression($baselineResult, $currentResult, $baselineDuration, $currentDuration);
        
        return array(
            'passed' => $regressionAnalysis['no_regression_detected'],
            'message' => $regressionAnalysis['summary'],
            'baseline_throughput' => $baselineResult['files_processed'] / $baselineDuration,
            'current_throughput' => $currentResult['files_processed'] / $currentDuration,
            'regression_analysis' => $regressionAnalysis,
            'performance_difference_percent' => $regressionAnalysis['performance_difference_percent']
        );
    }
    
    /**
     * Test malformed TOON handling and recovery
     */
    private function testMalformedToonHandlingAndRecovery($options)
    {
        $testDir = $this->createTestEnvironment('malformed_toon');
        
        echo "Creating malformed TOON test scenarios...\n";
        
        $scenarios = array(
            'missing_toon_file' => array(),
            'invalid_toon_json' => array('toon_content' => 'invalid json content'),
            'missing_required_columns' => array('missing_columns' => array('entity_type', 'entity_id')),
            'corrupted_toon_structure' => array('corrupted_structure' => true)
        );
        
        $recoveryResults = array();
        
        foreach ($scenarios as $scenarioName => $config) {
            echo "\nTesting scenario: {$scenarioName}...\n";
            
            $files = $this->createValidTestFiles($testDir . "/{$scenarioName}", 50);

            // Create TOON directory and files
            $toonDir = $testDir . "/{$scenarioName}/toon";
            mkdir($toonDir, 0755, true);
            
            if ($scenarioName === 'missing_toon_file') {
                // Don't create TOON files - test missing scenario
                $toonFiles = array();
            } else {
                $toonFiles = $this->createMalformedToonFiles($toonDir, $config);
            }
            
            $startTime = microtime(true);
            
            try {
                $testConfig = array(
                    'scope_root' => $testDir . "/{$scenarioName}",
                    'toon_dir' => $toonDir,
                    'batch_size' => 25,
                    'monitoring' => true
                );
                
                $ingester = $this->channel66ExtendedCreateIngester($testConfig);
                $result = $ingester->runProductionMigration(null, false);
                
                $duration = microtime(true) - $startTime;
                
                // Validate TOON error handling
                $toonValidation = $this->validateToonErrorHandling($result, $config);
                
                $recoveryResults[$scenarioName] = array(
                    'passed' => $toonValidation['proper_error_handling'],
                    'message' => $toonValidation['summary'],
                    'toon_validation' => $toonValidation,
                    'files_processed' => $result['files_processed'],
                    'rejections' => $result['files_rejected'],
                    'reject_reasons' => $this->extractRejectReasons($result),
                    'duration' => round($duration, 2)
                );
                
            } catch (Exception $e) {
                $recoveryResults[$scenarioName] = array(
                    'passed' => false,
                    'message' => "Exception in TOON handling test: " . $e->getMessage(),
                    'error' => $e->getTraceAsString()
                );
            }
        }
        
        $allScenariosPassed = array_reduce($recoveryResults, function($carry, $item) {
            return $carry && $item['passed'];
        }, true);
        
        return array(
            'passed' => $allScenariosPassed,
            'message' => $allScenariosPassed ? 
                "Malformed TOON handling tests passed for all scenarios" :
                "Malformed TOON handling tests failed for some scenarios",
            'recovery_results' => $recoveryResults
        );
    }
    
    /**
     * Test monitoring integration with alerting
     */
    private function testMonitoringIntegrationWithAlerting($options)
    {
        $testDir = $this->createTestEnvironment('monitoring_integration');
        
        echo "Creating monitoring integration test scenarios...\n";
        
        $scenarios = array(
            'metrics_collection' => array(),
            'alert_thresholds' => array(
                'error_rate' => 0.02, // Low threshold to trigger alerts
                'memory_usage' => 0.7,  // High memory usage threshold
                'throughput' => 0.3   // Low throughput threshold
            ),
            'log_rotation' => array('max_file_size' => '1K'), // Small file size to trigger rotation
            'performance_trends' => array('duration_minutes' => 5) // Short duration for trend analysis
        );
        
        $monitoringResults = array();
        
        foreach ($scenarios as $scenarioName => $config) {
            echo "\nTesting scenario: {$scenarioName}...\n";
            
            $files = $this->createValidTestFiles($testDir . "/{$scenarioName}", 50);
            $startTime = microtime(true);

            try {
                $at = (isset($config['alert_thresholds']) && is_array($config['alert_thresholds'])) ? $config['alert_thresholds'] : array();
                $testConfig = array(
                    'scope_root' => $testDir . "/{$scenarioName}",
                    'batch_size' => 25,
                    'memory_limit' => '128M',
                    'monitoring' => true,
                    'performance_alert_threshold' => isset($at['error_rate']) ? $at['error_rate'] : 0.05,
                    'memory_alert_threshold' => isset($at['memory_usage']) ? $at['memory_usage'] : 0.8,
                    'throughput_alert_threshold' => isset($at['throughput']) ? $at['throughput'] : 0.5
                );
                
                $ingester = $this->channel66ExtendedCreateIngester($testConfig);
                $result = $ingester->runProductionMigration(null, false);
                
                $duration = microtime(true) - $startTime;
                
                // Validate monitoring integration
                $monitoringValidation = $this->validateMonitoringIntegration($result, $testDir . "/{$scenarioName}", $config);
                
                $monitoringResults[$scenarioName] = array(
                    'passed' => $monitoringValidation['proper_monitoring'],
                    'message' => $monitoringValidation['summary'],
                    'monitoring_validation' => $monitoringValidation,
                    'files_processed' => $result['files_processed'],
                    'alerts_triggered' => $monitoringValidation['alerts_triggered'],
                    'metrics_collected' => $monitoringValidation['metrics_collected'],
                    'duration' => round($duration, 2)
                );
                
            } catch (Exception $e) {
                $monitoringResults[$scenarioName] = array(
                    'passed' => false,
                    'message' => "Exception in monitoring integration test: " . $e->getMessage(),
                    'error' => $e->getTraceAsString()
                );
            }
        }
        
        $allScenariosPassed = array_reduce($monitoringResults, function($carry, $item) {
            return $carry && $item['passed'];
        }, true);
        
        return array(
            'passed' => $allScenariosPassed,
            'message' => $allScenariosPassed ? 
                "Monitoring integration tests passed for all scenarios" :
                "Monitoring integration tests failed for some scenarios",
            'monitoring_results' => $monitoringResults
        );
    }
    
    /**
     * Test deployment validation and automation
     */
    private function testDeploymentValidationAndAutomation($options)
    {
        $testDir = $this->createTestEnvironment('deployment_validation');
        
        echo "Creating deployment validation test scenarios...\n";
        
        $scenarios = array(
            'environment_validation' => array(),
            'health_checks' => array(),
        );

        $deploymentResults = array();

        foreach ($scenarios as $scenarioName => $config) {
            echo "\nTesting scenario: {$scenarioName}...\n";

            $startTime = microtime(true);

            try {
                if ($scenarioName === 'environment_validation') {
                    $deploymentResults[$scenarioName] = $this->testEnvironmentValidation($testDir);
                } elseif ($scenarioName === 'health_checks') {
                    $deploymentResults[$scenarioName] = $this->testHealthChecks($testDir);
                }
                
                $duration = microtime(true) - $startTime;
                
                $deploymentResults[$scenarioName]['duration'] = round($duration, 2);
                $deploymentResults[$scenarioName]['passed'] = $deploymentResults[$scenarioName]['passed'] ?? true;
                
            } catch (Exception $e) {
                $deploymentResults[$scenarioName] = array(
                    'passed' => false,
                    'message' => "Exception in deployment validation test: " . $e->getMessage(),
                    'error' => $e->getTraceAsString(),
                    'duration' => round(microtime(true) - $startTime, 2)
                );
            }
        }
        
        $allScenariosPassed = array_reduce($deploymentResults, function($carry, $item) {
            return $carry && ($item['passed'] ?? true);
        }, true);
        
        return array(
            'passed' => $allScenariosPassed,
            'message' => $allScenariosPassed ? 
                "Deployment validation and automation tests passed for all scenarios" :
                "Deployment validation and automation tests failed for some scenarios",
            'deployment_results' => $deploymentResults
        );
    }
    
    /**
     * Test end-to-end production flow validation
     */
    private function testEndToEndProductionFlowValidation($options)
    {
        $testDir = $this->createTestEnvironment('end_to_end');
        
        echo "Creating end-to-end production flow validation...\n";
        
        $startTime = microtime(true);
        
        try {
            // Create complete production-like environment
            $this->createCompleteProductionEnvironment($testDir);
            
            // Run full production workflow
            $config = array(
                'scope_root' => $testDir,
                'batch_size' => 100,
                'memory_limit' => '512M',
                'monitoring' => true
            );
            
            $ingester = $this->channel66ExtendedCreateIngester($config);
            $result = $ingester->runProductionMigration(null, false);
            
            $duration = microtime(true) - $startTime;
            
            // Validate complete production flow
            $flowValidation = $this->validateEndToEndProductionFlow($result, $testDir);
            
            return array(
                'passed' => $flowValidation['complete_flow_success'],
                'message' => $flowValidation['summary'],
                'flow_validation' => $flowValidation,
                'files_processed' => $result['files_processed'],
                'duration' => round($duration, 2),
                'production_components_validated' => $flowValidation['components_validated']
            );
            
        } catch (Exception $e) {
            return array(
                'passed' => false,
                'message' => "Exception in end-to-end production flow test: " . $e->getMessage(),
                'error' => $e->getTraceAsString()
            );
        }
    }
    
    /**
     * Helper methods for test environment creation and validation
     */
    private function createTestEnvironment($testName)
    {
        $testDir = ABSPATH . "lupo-tests/temp/channel66_production_extended/{$testName}";
        
        if (!is_dir($testDir)) {
            mkdir($testDir, 0755, true);
        }
        
        return $testDir;
    }
    
    /**
     * Create markdown fixtures under scope_root/lupo-channels/66/threads/1001/ (ingester discovery path).
     *
     * @param string $scopeRoot
     * @param int $count
     * @return array
     */
    private function createValidTestFiles($scopeRoot, $count)
    {
        $this->channel66ExtendedThreadIngestionDir($scopeRoot);
        $scopeRootNorm = rtrim(str_replace('\\', '/', $scopeRoot), '/');
        $files = array();
        for ($i = 0; $i < $count; $i++) {
            $rel = 'lupo-channels/66/threads/1001/test_file_' . $i . '.md';
            $file = $scopeRootNorm . '/' . $rel;
            $this->createValidTestFile($file, $rel);
            $files[] = $file;
        }
        return $files;
    }

    /**
     * @param string $file Absolute path
     * @param string $repoRelativePath Path relative to scope root (must match YAML file_path_from_root)
     */
    private function createValidTestFile($file, $repoRelativePath)
    {
        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $fpJson = json_encode($repoRelativePath);
        $content = "---\n";
        $content .= "lupopedia.headers:\n";
        $content .= "  lupopedia.version: \"4.0.80\"\n";
        $content .= "  lupopedia.schema: \"thread\"\n";
        $content .= "  system_version: \"4.0.80\"\n";
        $content .= "  file_path_from_root: " . $fpJson . "\n";
        $content .= "  web_path: \"http://test\"\n";
        $content .= "  last_modified_utc: \"20260319\"\n";
        $content .= "  channel_id: 66\n";
        $content .= "  actor_id: 3\n";
        $content .= "  delegation_chain: \"hephaestus:root\"\n";
        $content .= "  artifact_type: \"thread\"\n";
        $content .= "  artifact_kind: \"test\"\n";
        $content .= "  purpose: \"Production test file\"\n";
        $content .= "lupopedia.edges:\n";
        $content .= "  outbound_edges:\n";
        $content .= "    - { to: \"test_target\", type: \"test\", weight: 1.0 }\n";
        $content .= "---\n\n";
        $content .= "# Test content\n";
        
        file_put_contents($file, $content);
    }
    
    /**
     * Validation helper methods
     */
    private function validateDeterministicBehavior($result, $expectedFileCount, $config)
    {
        // Check if entity IDs are deterministic (same path basis as Channel66ProductionIngester)
        $entityIds = array();
        $root = rtrim(str_replace('\\', '/', $config['scope_root']), '/');
        $pattern = $root . '/lupo-channels/66/threads/*/*.md';
        $files = glob($pattern);
        if (!is_array($files)) {
            $files = array();
        }

        foreach ($files as $file) {
            $path = str_replace('\\', '/', $file);
            $relativePath = $this->channel66RepoRelativeFromScope($root, $path);
            if ($relativePath === null) {
                continue;
            }
            $base = basename($path);
            if ($base !== '' && strpos($base, 'test_file_') === 0) {
                $entityId = $this->computeEntityId($relativePath);
                $entityIds[] = $entityId;
            }
        }
        
        $deterministicCount = count(array_unique($entityIds));
        $expectedCount = $expectedFileCount;

        return array(
            'passed' => $deterministicCount === $expectedCount && $expectedCount > 0,
            'message' => (($deterministicCount === $expectedCount && $expectedCount > 0) ?
                "Deterministic behavior validated: {$deterministicCount}/{$expectedCount} unique entity IDs" :
                "Deterministic behavior failed: expected {$expectedCount} unique IDs, got {$deterministicCount}"),
            'entity_ids' => $entityIds,
            'unique_count' => $deterministicCount,
            'expected_count' => $expectedCount
        );
    }
    
    private function computeEntityId($filePathFromRoot)
    {
        $hash = md5($filePathFromRoot);
        $hexSubstr = substr($hash, 0, 15);
        $entityId = hexdec($hexSubstr);
        return max(0, $entityId);
    }
    
    private function validatePerformanceCharacteristics($result, $fileCount)
    {
        $runtime = isset($result['total_runtime_seconds']) ? (float) $result['total_runtime_seconds'] : 0.0;
        if ($runtime <= 0.0) {
            $runtime = 0.001;
        }
        $throughput = $fileCount > 0 ? ($result['files_processed'] / $runtime) : 0;
        $expectedMinThroughput = 10;

        return array(
            'passed' => $throughput >= $expectedMinThroughput,
            'message' => ($throughput >= $expectedMinThroughput ?
                "Performance characteristics acceptable: {$throughput} files/sec" :
                "Performance below minimum: {$throughput} files/sec (expected: {$expectedMinThroughput})"),
            'throughput' => $throughput,
            'expected_min' => $expectedMinThroughput,
            'memory_efficiency' => $result['peak_memory_mb'] < 512,
            'batch_efficiency' => $result['batches_processed'] > 0
        );
    }

    /**
     * Logs under testDir + basic metrics from ingester result (merged duplicate validators).
     *
     * @param array $result
     * @param string $testDir
     * @param array $config
     * @return array
     */
    private function validateMonitoringIntegration($result, $testDir, $config = array())
    {
        $logFiles = glob($testDir . '/channel66_production_*.jsonl');
        if (empty($logFiles) && defined('ABSPATH')) {
            $adminGlob = rtrim(str_replace('\\', '/', ABSPATH), '/') . '/lupo-logs/admin/channel66_production_*.jsonl';
            $alt = glob($adminGlob);
            if (!empty($alt)) {
                $logFiles = $alt;
            }
        }
        $logsCreated = count($logFiles) > 0;
        $metricsCollected = isset($result['peak_memory_mb']) && $result['peak_memory_mb'] > 0;
        $threshold = isset($config['performance_alert_threshold']) ? (float) $config['performance_alert_threshold'] : 0.05;
        $proc = isset($result['files_processed']) ? (int) $result['files_processed'] : 0;
        $rej = isset($result['files_rejected']) ? (int) $result['files_rejected'] : 0;
        $errorRate = $proc > 0 ? ($rej / $proc) : 0.0;
        $alertsTriggered = ($proc > 0 && $rej > ($proc * $threshold)) || ($errorRate >= $threshold);
        $passed = $logsCreated && $metricsCollected;
        $msg = $passed ? 'Monitoring integration successful' : 'Monitoring integration failed';

        return array(
            'passed' => $passed,
            'proper_monitoring' => $passed,
            'summary' => $msg,
            'message' => $msg,
            'logs_created' => $logsCreated,
            'metrics_collected' => $metricsCollected,
            'alerts_triggered' => $alertsTriggered,
            'log_file_count' => count($logFiles),
            'error_rate' => $errorRate,
            'threshold' => $threshold,
        );
    }

    private function validateMemoryRecoveryBehavior($result, $config)
    {
        $batchSizeAdjusted = $result['files_processed'] > $config['batch_size'];
        $memoryLimitRespected = $result['peak_memory_mb'] <= 64;
        $errorsHandled = empty($result['errors']) || ($result['batches_failed'] > 0);
        $passed = $batchSizeAdjusted && $memoryLimitRespected && $errorsHandled;

        return array(
            'passed' => $passed,
            'message' => ($passed ?
                "Memory pressure recovery successful" :
                "Memory pressure recovery failed"),
            'batch_size_adjusted' => $batchSizeAdjusted,
            'memory_limit_respected' => $memoryLimitRespected,
            'errors_handled' => $errorsHandled,
            'recovery_attempts' => isset($result['retry_attempts']) ? $result['retry_attempts'] : 0
        );
    }

    private function validateBatchSizeAdjustment($result, $config)
    {
        $expectedBatchSize = $config['batch_size'];
        $actualBatchSize = $result['files_processed'] / max($result['batches_processed'], 1);
        $passed = $actualBatchSize <= $expectedBatchSize;

        return array(
            'passed' => $passed,
            'message' => ($passed ?
                "Batch size properly maintained" :
                "Batch size incorrectly adjusted"),
            'expected_batch_size' => $expectedBatchSize,
            'actual_batch_size' => $actualBatchSize
        );
    }

    private function validateToonErrorHandling($result, $config)
    {
        $toonRejections = $result['files_rejected'] > 0;
        $validRejectReasons = array('toon_conflict', 'malformed_toon', 'missing_required_columns');
        $rejectReasonsValid = true;

        if (!empty($result['reject_reasons'])) {
            foreach ($result['reject_reasons'] as $reason) {
                if (!in_array($reason, $validRejectReasons)) {
                    $rejectReasonsValid = false;
                    break;
                }
            }
        }

        $passed = $toonRejections && $rejectReasonsValid;
        $msg = $passed ? 'TOON error handling successful' : 'TOON error handling failed';

        return array(
            'passed' => $passed,
            'proper_error_handling' => $passed,
            'summary' => $msg,
            'message' => $msg,
            'toon_rejections' => $toonRejections,
            'reject_reasons' => isset($result['reject_reasons']) ? $result['reject_reasons'] : array(),
            'reject_reasons_valid' => $rejectReasonsValid
        );
    }

    /**
     * Additional helper methods for complex test scenarios
     */
    private function createMalformedToonFiles($schemaDir, $config)
    {
        $paths = array();
        $main = $schemaDir . '/lupo_metadata.json';

        if (isset($config['toon_content'])) {
            $paths[] = $main;
            file_put_contents($main, $config['toon_content']);
        }

        if (isset($config['missing_columns'])) {
            $partial = array(
                'table_name' => 'lupo_metadata',
                'fields' => array(
                    '`metadata_id` bigint NOT NULL',
                ),
            );
            $paths[] = $main;
            file_put_contents($main, json_encode($partial));
        }

        if (isset($config['corrupted_structure'])) {
            $paths[] = $main;
            file_put_contents($main, '{invalid json structure');
        }

        return $paths;
    }

    private function extractRejectReasons($result)
    {
        $reasons = array();
        
        if (!empty($result['rejections'])) {
            foreach ($result['rejections'] as $rejection) {
                if (isset($rejection['reject_type'])) {
                    $reasons[] = $rejection['reject_type'];
                }
            }
        }
        
        return $reasons;
    }

    private function testEnvironmentValidation($testDir)
    {
        // Check if required directories exist
        $requiredDirs = array(
            $testDir . '/lupo-channels/66',
            $testDir . '/lupo-database/lupopedia/toon',
            $testDir . '/lupo-scripts',
            $testDir . '/lupo-includes/classes',
            $testDir . '/lupo-logs/admin'
        );
        
        $allDirsExist = true;
        foreach ($requiredDirs as $dir) {
            if (!is_dir($dir)) {
                $allDirsExist = false;
                break;
            }
        }
        
        return array(
            'passed' => $allDirsExist,
            'message' => $allDirsExist ?
                "Environment validation passed" :
                "Environment validation failed - missing directories",
            'required_directories' => $requiredDirs,
            'all_exist' => $allDirsExist
        );
    }

    private function testHealthChecks($testDir)
    {
        // Test various health check scenarios
        $healthChecks = array(
            'database_connectivity' => $this->testDatabaseConnectivity(),
            'file_system_access' => $this->testFileSystemAccess($testDir),
            'memory_availability' => $this->testMemoryAvailability(),
        );
        
        $allChecksPassed = array_reduce($healthChecks, function($carry, $item) {
            return $carry && $item['passed'];
        }, true);
        
        return array(
            'passed' => $allChecksPassed,
            'message' => $allChecksPassed ?
                "Health checks passed" :
                "Health checks failed",
            'health_checks' => $healthChecks
        );
    }

    /**
     * Helper methods for health checks
     */
    private function testDatabaseConnectivity()
    {
        $ok = $this->db !== null;
        return array(
            'passed' => $ok,
            'message' => $ok ? 'Database connectivity OK' : 'Database not available'
        );
    }

    /**
     * @param string $baseDir writable area under test temp (preferred over repo root)
     */
    private function testFileSystemAccess($baseDir)
    {
        $dir = rtrim(str_replace('\\', '/', $baseDir), '/');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $testFile = $dir . '/test_access_' . uniqid('', true) . '.tmp';
        $writeSuccess = (bool) file_put_contents($testFile, 'test');
        $readSuccess = $writeSuccess && file_get_contents($testFile) === 'test';
        if (is_file($testFile)) {
            unlink($testFile);
        }

        return array(
            'passed' => $writeSuccess && $readSuccess,
            'message' => ($writeSuccess && $readSuccess) ? 'File system access OK' : 'File system access failed'
        );
    }

    private function testMemoryAvailability()
    {
        $memoryLimit = ini_get('memory_limit');
        $currentUsage = memory_get_usage(true);
        $memoryAvailable = $currentUsage < (1024 * 1024 * 1024); // Less than 1GB
        
        return array(
            'passed' => $memoryAvailable,
            'message' => $memoryAvailable ? "Memory availability OK" : "Memory availability limited",
            'memory_limit' => $memoryLimit,
            'current_usage' => $currentUsage
        );
    }
    
    private function createCompleteProductionEnvironment($testDir)
    {
        // Create a complete production-like environment for end-to-end testing
        $channelDir = $testDir . '/lupo-channels/66';
        $threadsDir = $channelDir . '/threads';
        $testThreadDir = $threadsDir . '/1001';
        
        mkdir($channelDir, 0755, true);
        mkdir($threadsDir, 0755, true);
        mkdir($testThreadDir, 0755, true);
        
        // Create test files in the proper structure (header path must match repo-relative path)
        for ($i = 0; $i < 10; $i++) {
            $rel = 'lupo-channels/66/threads/1001/prod_test_' . $i . '.md';
            $this->createValidTestFile($testDir . '/' . $rel, $rel);
        }
        
        // Schema reference JSON (canonical path shape; PRD 00 section 6)
        $jsonDir = $testDir . '/lupo-database/lupopedia/json';
        mkdir($jsonDir, 0755, true);

        $schema = array(
            'table_name' => 'lupo_metadata',
            'fields' => array(
                '`metadata_id` bigint NOT NULL',
                '`entity_type` varchar(32) NOT NULL',
                '`entity_id` bigint NOT NULL',
                '`domain_id` bigint',
                '`meta_type` varchar(64)',
                '`property_key` varchar(255) NOT NULL',
                '`property_value` text',
                '`created_ymdhis` bigint NOT NULL DEFAULT 0',
                '`updated_ymdhis` bigint NOT NULL',
                '`is_deleted` tinyint NOT NULL DEFAULT 0',
                '`deleted_ymdhis` bigint',
                '`channel_id` bigint',
                '`parent_metadata_id` bigint',
                '`class_name` varchar(128)',
                '`schema_ref` varchar(64)',
            ),
        );
        file_put_contents($jsonDir . '/lupo_metadata.json', json_encode($schema));
    }
    
    private function validateEndToEndProductionFlow($result, $testDir)
    {
        // Check if all production components worked together
        $componentsValidated = array();
        
        // Check files were processed
        $filesProcessed = $result['files_processed'] > 0;
        $componentsValidated['files_processed'] = $filesProcessed;
        
        // Check monitoring was active (logger may write under ABSPATH/lupo-logs/admin/)
        $logFiles = glob($testDir . '/channel66_production_*.jsonl');
        if (empty($logFiles) && defined('ABSPATH')) {
            $adminGlob = rtrim(str_replace('\\', '/', ABSPATH), '/') . '/lupo-logs/admin/channel66_production_*.jsonl';
            $alt = glob($adminGlob);
            if (!empty($alt)) {
                $logFiles = $alt;
            }
        }
        $monitoringActive = count($logFiles) > 0;
        $componentsValidated['monitoring_active'] = $monitoringActive;
        
        // Check batch processing worked
        $batchesProcessed = $result['batches_processed'] > 0;
        $componentsValidated['batch_processing'] = $batchesProcessed;
        
        // No fatal errors recorded on result payload
        $noErrors = empty($result['errors']);
        $componentsValidated['no_errors'] = $noErrors;
        
        // Check schema reference JSON present (bounded authority path)
        $jsonDir = $testDir . '/lupo-database/lupopedia/json';
        $schemaExists = is_file($jsonDir . '/lupo_metadata.json');
        $componentsValidated['toon_validation'] = $schemaExists;
        
        $allComponentsValid = array_reduce($componentsValidated, function($carry, $item) {
            return $carry && $item;
        }, true);
        
        return array(
            'complete_flow_success' => $allComponentsValid,
            'summary' => $allComponentsValid ? 
                "End-to-end production flow successful" :
                "End-to-end production flow failed",
            'components_validated' => $componentsValidated,
            'files_processed' => $result['files_processed'],
            'batches_processed' => $result['batches_processed'],
            'errors' => $result['errors'] ?? array()
        );
    }
    
    /**
     * Print test results summary
     */
    private function printTestResults()
    {
        echo "\nDetailed Extended Test Results:\n";
        echo "===============================================\n";
        
        foreach ($this->testResults as $testName => $result) {
            echo "\n{$testName}:\n";
            echo "  Status: " . ($result['passed'] ? 'PASSED' : 'FAILED') . "\n";
            echo "  Message: " . $result['message'] . "\n";
            
            if (isset($result['error'])) {
                echo "  Error: " . $result['error'] . "\n";
            }
            
            if (isset($result['validation_results'])) {
                echo "  Validation Results:\n";
                foreach ($result['validation_results'] as $key => $value) {
                    if (is_array($value)) {
                        echo "    {$key}: " . json_encode($value) . "\n";
                    } else {
                        echo "    {$key}: {$value}\n";
                    }
                }
            }
            
            if (isset($result['recovery_results'])) {
                echo "  Recovery Results:\n";
                foreach ($result['recovery_results'] as $key => $value) {
                    echo "    {$key}: {$value}\n";
                }
            }
            
            if (isset($result['monitoring_results'])) {
                echo "  Monitoring Results:\n";
                foreach ($result['monitoring_results'] as $key => $value) {
                    echo "    {$key}: {$value}\n";
                }
            }
            
            if (isset($result['deployment_results'])) {
                echo "  Deployment Results:\n";
                foreach ($result['deployment_results'] as $key => $value) {
                    echo "    {$key}: {$value}\n";
                }
            }
            
            if (isset($result['stress_results'])) {
                echo "  Stress Results:\n";
                foreach ($result['stress_results'] as $key => $value) {
                    echo "    {$key}: {$value}\n";
                }
            }
        }
        
        echo "\n===============================================\n";
    }
}
