<?php
/**
 * Dialog System Integration Tests
 * 
 * Verifies schema alignment and MessageBuilder operations for dialog system.
 * READ-ONLY tests - does not modify database.
 * 
 * @package Lupopedia
 * @version 3.0.106
 * @author CASCADE
 */

require_once __DIR__ . '/../../lupo-includes/DialogChannelMigration/MessageBuilder.php';
require_once __DIR__ . '/../../lupo-includes/DialogChannelMigration/ValidationTool.php';

class DialogSystemTest {
    
    private $db;
    private $messageBuilder;
    private $validationTool;
    private $errors = [];
    
    public function __construct($database_connection) {
        $this->db = $database_connection;
        $this->messageBuilder = new MessageBuilder($this->db);
        $this->validationTool = new ValidationTool($this->db);
    }
    
    /**
     * Verify schema alignment for dialog tables
     */
    public function testSchemaAlignment() {
        $expectedColumns = [
            'dialog_message_id',
            'channel_id',
            'dialog_thread_id',
            'from_actor_id',
            'to_actor_id',
            'message_text',
            'mood_rgb',
            'message_type',
            'metadata_json',
            'created_ymdhis',
            'updated_ymdhis',
            'is_deleted',
            'deleted_ymdhis',
            'weight'
        ];
        
        $actualStructure = $this->validationTool->getMessagesTableStructure();
        $actualColumns = array_keys($actualStructure);
        
        $missingColumns = array_diff($expectedColumns, $actualColumns);
        $extraColumns = array_diff($actualColumns, $expectedColumns);
        
        if (!empty($missingColumns)) {
            $this->errors[] = "Missing columns: " . implode(', ', $missingColumns);
            return false;
        }
        
        if (!empty($extraColumns)) {
            $this->errors[] = "Unexpected columns: " . implode(', ', $extraColumns);
            return false;
        }
        
        // Verify message_text is VARCHAR(1000)
        if ($actualStructure['message_text']['type'] !== 'varchar' || 
            $actualStructure['message_text']['length'] !== 1000) {
            $this->errors[] = "message_text should be VARCHAR(1000), found: " . 
                $actualStructure['message_text']['type'] . "(" . 
                $actualStructure['message_text']['length'] . ")";
            return false;
        }
        
        return true;
    }
    
    /**
     * Verify MessageBuilder INSERT operation structure
     */
    public function testMessageBuilderInsertStructure() {
        // Test that MessageBuilder uses correct column names
        $reflection = new ReflectionClass($this->messageBuilder);
        
        // Check that createMessage method exists
        if (!$reflection->hasMethod('createMessage')) {
            $this->errors[] = "MessageBuilder::createMessage() method not found";
            return false;
        }
        
        // Verify method uses correct parameters
        $method = $reflection->getMethod('createMessage');
        $parameters = $method->getParameters();
        
        if (count($parameters) < 2) {
            $this->errors[] = "MessageBuilder::createMessage() should accept channelId and messageData";
            return false;
        }
        
        return true;
    }
    
    /**
     * Verify soft-delete behavior (is_deleted = 0 filtering)
     */
    public function testSoftDeleteFiltering() {
        // Check that getChannelMessages filters soft-deleted records
        $reflection = new ReflectionClass($this->messageBuilder);
        
        if (!$reflection->hasMethod('getChannelMessages')) {
            $this->errors[] = "MessageBuilder::getChannelMessages() method not found";
            return false;
        }
        
        // Read the method source to verify it includes is_deleted = 0
        $method = $reflection->getMethod('getChannelMessages');
        $filename = $method->getFileName();
        $startLine = $method->getStartLine();
        $endLine = $method->getEndLine();
        
        $source = file_get_contents($filename);
        $lines = explode("\n", $source);
        $methodSource = implode("\n", array_slice($lines, $startLine - 1, $endLine - $startLine + 1));
        
        if (strpos($methodSource, 'is_deleted = 0') === false && 
            strpos($methodSource, "is_deleted = 0") === false) {
            $this->errors[] = "getChannelMessages() should filter soft-deleted records (is_deleted = 0)";
            return false;
        }
        
        return true;
    }
    
    /**
     * Verify ValidationTool schema structure
     */
    public function testValidationToolSchemaStructure() {
        $reflection = new ReflectionClass($this->validationTool);
        
        if (!$reflection->hasMethod('getMessagesTableStructure')) {
            $this->errors[] = "ValidationTool::getMessagesTableStructure() method not found";
            return false;
        }
        
        $structure = $this->validationTool->getMessagesTableStructure();
        
        // Verify required columns exist
        $requiredColumns = ['dialog_message_id', 'from_actor_id', 'to_actor_id', 
                           'created_ymdhis', 'updated_ymdhis', 'is_deleted'];
        
        foreach ($requiredColumns as $column) {
            if (!isset($structure[$column])) {
                $this->errors[] = "ValidationTool structure missing column: {$column}";
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Run all tests
     */
    public function runAllTests() {
        $results = [
            'schema_alignment' => $this->testSchemaAlignment(),
            'message_builder_insert' => $this->testMessageBuilderInsertStructure(),
            'soft_delete_filtering' => $this->testSoftDeleteFiltering(),
            'validation_tool_structure' => $this->testValidationToolSchemaStructure()
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
