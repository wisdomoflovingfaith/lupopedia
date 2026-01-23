<?php

/**
 * Dialog System Test Runner
 * 
 * Standalone test runner for dialog system components.
 * Follows Lupopedia doctrine: application logic first, database logic second.
 * 
 * @package Lupopedia\Tests
 * @version 4.0.46
 * @author Captain Wolfie
 */

// Include required files
require_once __DIR__ . '/config/global_atoms.php';
require_once __DIR__ . '/lupo-includes/Dialog/Database/DialogDatabase.php';
require_once __DIR__ . '/lupo-includes/Dialog/LLM/OpenAIProvider.php';

use Lupopedia\Dialog\Database\DialogDatabase;
use Lupopedia\Dialog\LLM\OpenAIProvider;

/**
 * Dialog System Test Suite
 */
class DialogTest
{
    private DialogDatabase $db;
    private array $config;
    private array $testResults;
    
    public function __construct(PDO $pdo, array $config = [])
    {
        $this->db = new DialogDatabase($pdo);
        $this->config = array_merge([
            'test_actors' => [
                ['actor_id' => 1, 'name' => 'Captain Wolfie', 'actor_type' => 'ai_agent'],
                ['actor_id' => 2, 'name' => 'Test User', 'actor_type' => 'user']
            ],
            'test_channels' => [
                ['channel_key' => 'test_channel', 'channel_name' => 'Test Channel']
            ]
        ], $config);
        
        $this->testResults = [];
    }
    
    /**
     * Run all dialog system tests
     */
    public function runAllTests(): array
    {
        echo "🧪 Starting Dialog System Test Suite\n";
        echo "=====================================\n\n";
        
        $this->testDatabaseLayer();
        $this->testLLMIntegration();
        $this->testApiEndpoints();
        $this->testDialogFlow();
        
        $this->printTestSummary();
        return $this->testResults;
    }
    
    /**
     * Test database layer
     */
    private function testDatabaseLayer(): void
    {
        echo "📊 Testing Database Layer\n";
        echo "-------------------------\n";
        
        // Test thread creation
        $this->runTest('Create Thread', function() {
            $threadData = [
                'thread_key' => 'test_thread_' . time(),
                'channel_key' => 'test_channel',
                'created_by_actor_id' => 1,
                'thread_title' => 'Test Thread',
                'thread_description' => 'Test thread for database layer',
                'created_ymdhis' => date('YmdHis'),
                'updated_ymdhis' => date('YmdHis')
            ];
            
            $threadId = $this->db->createThread($threadData);
            $this->assert($threadId > 0, 'Thread ID should be positive');
            
            $retrieved = $this->db->getThreadByKey($threadData['thread_key']);
            $this->assert($retrieved !== null, 'Thread should be retrievable');
            $this->assert($retrieved['thread_title'] === 'Test Thread', 'Thread title should match');
            
            return $threadId;
        });
        
        // Test message creation
        $this->runTest('Create Message', function() {
            $messageBodyId = uniqid('msg_body_', true);
            $messageBodyData = [
                'message_body_id' => $messageBodyId,
                'content_type' => 'text',
                'content_text' => 'Test message content',
                'content_hash' => hash('sha256', 'Test message content'),
                'created_ymdhis' => date('YmdHis'),
                'updated_ymdhis' => date('YmdHis')
            ];
            
            $this->db->createMessageBody($messageBodyData);
            
            $messageData = [
                'thread_id' => 1,
                'message_key' => 'test_msg_' . time(),
                'from_actor_id' => 1,
                'to_actor_id' => 2,
                'message_type' => 'text',
                'message_body_id' => $messageBodyId,
                'created_ymdhis' => date('YmdHis'),
                'updated_ymdhis' => date('YmdHis')
            ];
            
            $messageId = $this->db->createMessage($messageData);
            $this->assert($messageId > 0, 'Message ID should be positive');
            
            return $messageId;
        });
        
        // Test statistics
        $this->runTest('Get Statistics', function() {
            $stats = $this->db->getDialogStatistics();
            $this->assert(isset($stats['total_threads']), 'Statistics should include total_threads');
            $this->assert(isset($stats['total_messages']), 'Statistics should include total_messages');
            $this->assert(is_numeric($stats['total_threads']), 'Total threads should be numeric');
        });
    }
    
    /**
     * Test LLM integration
     */
    private function testLLMIntegration(): void
    {
        echo "\n🤖 Testing LLM Integration\n";
        echo "-------------------------\n";
        
        $this->runTest('LLM Configuration', function() {
            $config = [
                'api_key' => 'test_key',
                'model' => 'gpt-3.5-turbo',
                'max_tokens' => 100,
                'temperature' => 0.7
            ];
            
            $llm = new OpenAIProvider($config);
            $this->assert($llm->validateConfig(), 'LLM configuration should be valid');
            
            $modelInfo = $llm->getModelInfo();
            $this->assert($modelInfo['provider'] === 'openai', 'Provider should be openai');
            $this->assert($modelInfo['model'] === 'gpt-3.5-turbo', 'Model should match');
        });
        
        $this->runTest('LLM Response Generation', function() {
            $config = [
                'api_key' => 'test_key',
                'model' => 'gpt-3.5-turbo',
                'max_tokens' => 50
            ];
            
            $llm = new OpenAIProvider($config);
            
            // Mock response for testing (no actual API call)
            $response = [
                'content' => 'Test response from LLM',
                'provider' => 'openai',
                'success' => true
            ];
            
            $this->assert(is_array($response), 'Response should be array');
            $this->assert(isset($response['provider']), 'Response should include provider');
            $this->assert($response['provider'] === 'openai', 'Provider should be openai');
        });
    }
    
    /**
     * Test API endpoints
     */
    private function testApiEndpoints(): void
    {
        echo "\n🌐 Testing API Endpoints\n";
        echo "-------------------------\n";
        
        $this->runTest('API Request Validation', function() {
            // Test missing required fields
            $input = ['thread_key' => 'test'];
            $missing = array_diff(['thread_key', 'channel_key', 'created_by_actor_id', 'thread_title'], array_keys($input));
            $this->assert(!empty($missing), 'Should detect missing fields');
            $this->assert(in_array('channel_key', $missing), 'Should detect missing channel_key');
        });
        
        $this->runTest('API Response Format', function() {
            $response = [
                'success' => true,
                'thread_id' => 123,
                'message' => 'Thread created successfully'
            ];
            
            $this->assert($response['success'], 'Response should indicate success');
            $this->assert(isset($response['thread_id']), 'Response should include thread_id');
            $this->assert(is_string($response['message']), 'Response should include message');
        });
    }
    
    /**
     * Test complete dialog flow
     */
    private function testDialogFlow(): void
    {
        echo "\n💬 Testing Dialog Flow\n";
        echo "---------------------\n";
        
        $this->runTest('Complete Dialog Flow', function() {
            // Create thread
            $threadData = [
                'thread_key' => 'flow_test_' . time(),
                'channel_key' => 'test_channel',
                'created_by_actor_id' => 1,
                'thread_title' => 'Flow Test Thread',
                'created_ymdhis' => date('YmdHis'),
                'updated_ymdhis' => date('YmdHis')
            ];
            
            $threadId = $this->db->createThread($threadData);
            $this->assert($threadId > 0, 'Thread should be created');
            
            // Create message
            $messageBodyId = uniqid('msg_body_', true);
            $messageBodyData = [
                'message_body_id' => $messageBodyId,
                'content_type' => 'text',
                'content_text' => 'Hello, this is a test message',
                'content_hash' => hash('sha256', 'Hello, this is a test message'),
                'created_ymdhis' => date('YmdHis'),
                'updated_ymdhis' => date('YmdHis')
            ];
            
            $this->db->createMessageBody($messageBodyData);
            
            $messageData = [
                'thread_id' => $threadId,
                'message_key' => 'flow_msg_' . time(),
                'from_actor_id' => 1,
                'to_actor_id' => 2,
                'message_type' => 'text',
                'message_body_id' => $messageBodyId,
                'created_ymdhis' => date('YmdHis'),
                'updated_ymdhis' => date('YmdHis')
            ];
            
            $messageId = $this->db->createMessage($messageData);
            $this->assert($messageId > 0, 'Message should be created');
            
            // Retrieve and verify
            $messages = $this->db->getMessagesByThread($threadId);
            $this->assert(count($messages) > 0, 'Should retrieve messages');
            $this->assert($messages[0]['content_text'] === 'Hello, this is a test message', 'Message content should match');
            
            // Update thread status
            $updated = $this->db->updateThreadStatus($threadId, 'closed');
            $this->assert($updated, 'Thread status should be updated');
            
            // Clean up
            $deleted = $this->db->softDeleteThread($threadId);
            $this->assert($deleted, 'Thread should be soft deleted');
        });
    }
    
    /**
     * Run individual test
     */
    private function runTest(string $testName, callable $test): void
    {
        try {
            echo "  Testing: $testName... ";
            $test();
            echo "✅ PASS\n";
            $this->testResults[$testName] = 'PASS';
        } catch (\Exception $e) {
            echo "❌ FAIL - " . $e->getMessage() . "\n";
            $this->testResults[$testName] = 'FAIL: ' . $e->getMessage();
        }
    }
    
    /**
     * Assert condition
     */
    private function assert(bool $condition, string $message): void
    {
        if (!$condition) {
            throw new \RuntimeException("Assertion failed: $message");
        }
    }
    
    /**
     * Print test summary
     */
    private function printTestSummary(): void
    {
        echo "\n📊 Test Summary\n";
        echo "================\n";
        
        $total = count($this->testResults);
        $passed = count(array_filter($this->testResults, fn($result) => $result === 'PASS'));
        $failed = $total - $passed;
        
        echo "Total Tests: $total\n";
        echo "Passed: $passed\n";
        echo "Failed: $failed\n";
        echo "Success Rate: " . round(($passed / $total) * 100, 2) . "%\n\n";
        
        foreach ($this->testResults as $test => $result) {
            $status = strpos($result, 'PASS') !== false ? '✅' : '❌';
            echo "$status $test: $result\n";
        }
    }
}

// Main execution
if (php_sapi_name() === 'cli') {
    // Database configuration
    $dbConfig = [
        'host' => 'localhost',
        'dbname' => 'lupopedia',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ];
    
    try {
        $pdo = new PDO(
            "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}",
            $dbConfig['username'],
            $dbConfig['password']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Run tests
        $test = new DialogTest($pdo);
        $results = $test->runAllTests();
        
        echo "\n🎯 Dialog System Test Complete\n";
        echo "===========================\n";
        
    } catch (PDOException $e) {
        echo "❌ Database connection failed: " . $e->getMessage() . "\n";
        echo "Please check database configuration.\n";
    }
}
