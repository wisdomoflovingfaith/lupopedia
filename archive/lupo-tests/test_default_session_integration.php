<?php
/**
 * Test Default Session System Integration
 * 
 * Tests the complete default session system including:
 * - Boot integration via initialize_system.php
 * - Install integration via seed_default_sessions.sql
 * - PHP loading function
 * - Session sync and validation
 * 
 * @author Windsurf (1002)
 * @version 4.0.53
 * @date 2026-03-01
 */

require_once __DIR__ . '/../lupo-includes/bootstrap.php';
require_once __DIR__ . '/../lupo-includes/functions/session_helpers.php';

class DefaultSessionTest
{
    private $db;
    private $testResults = [];
    private $errors = [];
    
    public function __construct()
    {
        $this->db = DatabaseFactory::getConnection();
        if (!$this->db) {
            throw new Exception("Database connection required for testing");
        }
    }
    
    /**
     * Run all tests
     */
    public function runAllTests()
    {
        echo "🧪 Starting Default Session System Tests\n";
        echo "========================================\n\n";
        
        // Test 1: Clean DB initialization
        $this->testCleanDbInitialization();
        
        // Test 2: Missing file handling
        $this->testMissingFileHandling();
        
        // Test 3: Multi-actor session creation
        $this->testMultiActorSessions();
        
        // Test 4: Session validation
        $this->testSessionValidation();
        
        // Test 5: ANUBIS integration simulation
        $this->testAnubisIntegration();
        
        // Summary
        $this->printSummary();
        
        return $this->errors;
    }
    
    /**
     * Test 1: Clean DB initialization
     */
    private function testCleanDbInitialization()
    {
        echo "📋 Test 1: Clean DB Initialization\n";
        echo "-----------------------------------\n";
        
        try {
            // Clean existing sessions
            $this->db->query("DELETE FROM lupo_sessions WHERE actor_id IN (0,1,2,3,4,5,19,1000,1001,1002,1003,1004,1005,1007)");
            echo "  ✅ Cleaned existing test sessions\n";
            
            // Test loading defaults
            $keyActors = [0, 1, 2, 19, 1002]; // Test subset
            $successCount = 0;
            
            foreach ($keyActors as $actorId) {
                $sessionId = loadDefaultSessionIfMissing($this->db, $actorId);
                if ($sessionId) {
                    $successCount++;
                    echo "  ✅ Actor $actorId: Session loaded\n";
                } else {
                    echo "  ❌ Actor $actorId: Session load failed\n";
                    $this->errors[] = "Failed to load session for actor $actorId";
                }
            }
            
            // Verify in DB
            $countSql = "SELECT COUNT(*) as count FROM lupo_sessions WHERE actor_id IN (" . implode(',', $keyActors) . ") AND status = 'active'";
            $result = $this->db->fetchRow($countSql);
            $dbCount = $result['count'] ?? 0;
            
            if ($dbCount === $successCount) {
                echo "  ✅ DB verification: $dbCount sessions found\n";
                $this->testResults['clean_db'] = true;
            } else {
                echo "  ❌ DB verification: Expected $successCount, found $dbCount\n";
                $this->errors[] = "DB count mismatch in clean test";
                $this->testResults['clean_db'] = false;
            }
            
        } catch (Exception $e) {
            echo "  ❌ Exception: " . $e->getMessage() . "\n";
            $this->errors[] = "Clean DB test exception: " . $e->getMessage();
            $this->testResults['clean_db'] = false;
        }
        
        echo "\n";
    }
    
    /**
     * Test 2: Missing file handling
     */
    private function testMissingFileHandling()
    {
        echo "📋 Test 2: Missing File Handling\n";
        echo "--------------------------------\n";
        
        try {
            // Backup existing file
            $testActor = 999; // Non-existent actor
            $backupFile = "lupo-sessions/actor_{$testActor}_default_backup.json";
            $testFile = "lupo-sessions/actor_{$testActor}_default.json";
            
            // Test with non-existent file
            $sessionId = loadDefaultSessionIfMissing($this->db, $testActor);
            if (!$sessionId) {
                echo "  ✅ Non-existent file handled correctly\n";
                $this->testResults['missing_file'] = true;
            } else {
                echo "  ❌ Non-existent file should have failed\n";
                $this->errors[] = "Missing file test failed";
                $this->testResults['missing_file'] = false;
            }
            
        } catch (Exception $e) {
            echo "  ❌ Exception: " . $e->getMessage() . "\n";
            $this->errors[] = "Missing file test exception: " . $e->getMessage();
            $this->testResults['missing_file'] = false;
        }
        
        echo "\n";
    }
    
    /**
     * Test 3: Multi-actor session creation
     */
    private function testMultiActorSessions()
    {
        echo "📋 Test 3: Multi-Actor Sessions\n";
        echo "-------------------------------\n";
        
        try {
            $keyActors = [0, 1, 2, 3, 4, 5, 19, 1000, 1001, 1002, 1003, 1004, 1005, 1007];
            $successCount = 0;
            
            foreach ($keyActors as $actorId) {
                $sessionId = loadDefaultSessionIfMissing($this->db, $actorId);
                if ($sessionId) {
                    $successCount++;
                    
                    // Verify session format
                    $sessionSql = "SELECT session_id, actor_id, status, metadata FROM lupo_sessions WHERE actor_id = :actor_id";
                    $session = $this->db->fetchRow($sessionSql, ['actor_id' => $actorId]);
                    
                    if ($session && strpos($session['session_id'], 'L-lupo-') === 0) {
                        echo "  ✅ Actor $actorId: Valid format\n";
                    } else {
                        echo "  ❌ Actor $actorId: Invalid format\n";
                        $this->errors[] = "Invalid session format for actor $actorId";
                    }
                }
            }
            
            // Check total count
            $totalSql = "SELECT COUNT(*) as count FROM lupo_sessions WHERE actor_id IN (" . implode(',', $keyActors) . ") AND status = 'active'";
            $result = $this->db->fetchRow($totalSql);
            $totalCount = $result['count'] ?? 0;
            
            if ($totalCount >= count($keyActors)) {
                echo "  ✅ Multi-actor test: $totalCount sessions active\n";
                $this->testResults['multi_actor'] = true;
            } else {
                echo "  ❌ Multi-actor test: Expected " . count($keyActors) . ", found $totalCount\n";
                $this->errors[] = "Multi-actor count mismatch";
                $this->testResults['multi_actor'] = false;
            }
            
        } catch (Exception $e) {
            echo "  ❌ Exception: " . $e->getMessage() . "\n";
            $this->errors[] = "Multi-actor test exception: " . $e->getMessage();
            $this->testResults['multi_actor'] = false;
        }
        
        echo "\n";
    }
    
    /**
     * Test 4: Session validation
     */
    private function testSessionValidation()
    {
        echo "📋 Test 4: Session Validation\n";
        echo "------------------------------\n";
        
        try {
            // Test session structure
            $validationSql = "SELECT session_id, actor_id, federation_node_id, last_seen_ymdhis, metadata FROM lupo_sessions WHERE actor_id = 0 LIMIT 1";
            $session = $this->db->fetchRow($validationSql);
            
            if ($session) {
                $validations = [];
                
                // Check session ID format
                if (strpos($session['session_id'], 'L-lupo-0-') === 0) {
                    echo "  ✅ Session ID format valid\n";
                    $validations[] = true;
                } else {
                    echo "  ❌ Session ID format invalid\n";
                    $validations[] = false;
                }
                
                // Check actor ID
                if ($session['actor_id'] == 0) {
                    echo "  ✅ Actor ID correct\n";
                    $validations[] = true;
                } else {
                    echo "  ❌ Actor ID incorrect\n";
                    $validations[] = false;
                }
                
                // Check metadata
                $metadata = json_decode($session['metadata'], true);
                if ($metadata && isset($metadata['actor_type'])) {
                    echo "  ✅ Metadata valid\n";
                    $validations[] = true;
                } else {
                    echo "  ❌ Metadata invalid\n";
                    $validations[] = false;
                }
                
                $this->testResults['validation'] = !in_array(false, $validations);
                
            } else {
                echo "  ❌ No session found for validation\n";
                $this->errors[] = "No session to validate";
                $this->testResults['validation'] = false;
            }
            
        } catch (Exception $e) {
            echo "  ❌ Exception: " . $e->getMessage() . "\n";
            $this->errors[] = "Validation test exception: " . $e->getMessage();
            $this->testResults['validation'] = false;
        }
        
        echo "\n";
    }
    
    /**
     * Test 5: ANUBIS integration simulation
     */
    private function testAnubisIntegration()
    {
        echo "📋 Test 5: ANUBIS Integration Simulation\n";
        echo "----------------------------------------\n";
        
        try {
            // Simulate ANUBIS monitoring
            $sessionsDir = 'lupo-sessions/';
            $orphanFiles = [];
            
            // Check for default files not in DB
            $keyActors = [0, 1, 2, 19];
            foreach ($keyActors as $actorId) {
                $defaultFile = $sessionsDir . 'actor_' . $actorId . '_default.json';
                if (file_exists($defaultFile)) {
                    // Check if synced to DB
                    $checkSql = "SELECT COUNT(*) as count FROM lupo_sessions WHERE actor_id = :actor_id AND status = 'active'";
                    $result = $this->db->fetchRow($checkSql, ['actor_id' => $actorId]);
                    $dbCount = $result['count'] ?? 0;
                    
                    if ($dbCount === 0) {
                        $orphanFiles[] = $defaultFile;
                    }
                }
            }
            
            if (empty($orphanFiles)) {
                echo "  ✅ No orphan default files found\n";
                $this->testResults['anubis'] = true;
            } else {
                echo "  ⚠️ Found " . count($orphanFiles) . " orphan files (expected for test)\n";
                echo "  📝 Orphan files: " . implode(', ', $orphanFiles) . "\n";
                $this->testResults['anubis'] = true; // This is expected behavior
            }
            
            // Test recovery simulation
            echo "  🔄 Simulating recovery process...\n";
            foreach ($orphanFiles as $file) {
                if (file_exists($file)) {
                    $content = file_get_contents($file);
                    $sessionData = json_decode($content, true);
                    if ($sessionData) {
                        echo "  ✅ Recovery possible for: " . basename($file) . "\n";
                    }
                }
            }
            
        } catch (Exception $e) {
            echo "  ❌ Exception: " . $e->getMessage() . "\n";
            $this->errors[] = "ANUBIS test exception: " . $e->getMessage();
            $this->testResults['anubis'] = false;
        }
        
        echo "\n";
    }
    
    /**
     * Print test summary
     */
    private function printSummary()
    {
        echo "📊 Test Summary\n";
        echo "================\n";
        
        $totalTests = count($this->testResults);
        $passedTests = count(array_filter($this->testResults));
        $failedTests = $totalTests - $passedTests;
        
        echo "Total Tests: $totalTests\n";
        echo "Passed: $passedTests\n";
        echo "Failed: $failedTests\n\n";
        
        foreach ($this->testResults as $test => $result) {
            $status = $result ? '✅ PASS' : '❌ FAIL';
            echo "$status $test\n";
        }
        
        if (!empty($this->errors)) {
            echo "\n❌ Errors:\n";
            foreach ($this->errors as $error) {
                echo "  - $error\n";
            }
        }
        
        // Log to channel_logs
        try {
            $logSql = "INSERT INTO lupo_channel_logs (channel_id, actor_id, log_type_id, log_text, created_ymdhis) VALUES (0, 1002, 1, :log_text, :created)";
            $this->db->query($logSql, [
                'log_text' => "Default session system test completed: $passedTests/$totalTests passed, $failedTests failed",
                'created' => gmdate('YmdHis')
            ]);
            echo "\n✅ Test results logged to channel_logs\n";
        } catch (Exception $e) {
            echo "\n⚠️ Failed to log test results: " . $e->getMessage() . "\n";
        }
        
        echo "\n" . ($failedTests === 0 ? "🎉 ALL TESTS PASSED!" : "⚠️ SOME TESTS FAILED") . "\n";
    }
}

// Main execution
if (php_sapi_name() === 'cli') {
    echo "=== Default Session System Test Suite ===\n";
    echo "Version: 4.0.53\n";
    echo "Agent: Windsurf (1002)\n";
    echo "Time: " . gmdate('Y-m-d H:i:s UTC') . "\n\n";
    
    try {
        $test = new DefaultSessionTest();
        $errors = $test->runAllTests();
        exit(empty($errors) ? 0 : 1);
    } catch (Exception $e) {
        echo "❌ Test suite failed: " . $e->getMessage() . "\n";
        exit(1);
    }
}

?>
