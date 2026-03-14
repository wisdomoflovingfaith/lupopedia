<?php
/**
 * Trigger Replacement Integration Tests
 * 
 * Verifies trigger replacement services handle soft-delete filtering correctly.
 * READ-ONLY tests - does not modify database.
 * 
 * @package Lupopedia
 * @version 3.0.106
 * @author CASCADE
 */

require_once __DIR__ . '/../../app/Services/TriggerReplacements/DialogMessagesInsertService.php';
require_once __DIR__ . '/../../app/Services/TriggerReplacements/DialogMessagesDeleteService.php';

class TriggerReplacementTest {
    
    private $db;
    private $insertService;
    private $deleteService;
    private $errors = [];
    
    public function __construct($database_connection) {
        $this->db = $database_connection;
        $this->insertService = new DialogMessagesInsertService($this->db);
        $this->deleteService = new DialogMessagesDeleteService($this->db);
    }
    
    /**
     * Verify DialogMessagesInsertService soft-delete filtering
     */
    public function testInsertServiceSoftDeleteFiltering() {
        $reflection = new ReflectionClass($this->insertService);
        
        if (!$reflection->hasMethod('executeAfterInsert')) {
            $this->errors[] = "DialogMessagesInsertService::executeAfterInsert() method not found";
            return false;
        }
        
        // Read the method source to verify it includes is_deleted = 0
        $method = $reflection->getMethod('executeAfterInsert');
        $filename = $method->getFileName();
        $startLine = $method->getStartLine();
        $endLine = $method->getEndLine();
        
        $source = file_get_contents($filename);
        $lines = explode("\n", $source);
        $methodSource = implode("\n", array_slice($lines, $startLine - 1, $endLine - $startLine + 1));
        
        if (strpos($methodSource, 'is_deleted = 0') === false && 
            strpos($methodSource, "is_deleted = 0") === false) {
            $this->errors[] = "executeAfterInsert() should filter soft-deleted messages (is_deleted = 0)";
            return false;
        }
        
        return true;
    }
    
    /**
     * Verify DialogMessagesDeleteService soft-delete filtering
     */
    public function testDeleteServiceSoftDeleteFiltering() {
        $reflection = new ReflectionClass($this->deleteService);
        
        if (!$reflection->hasMethod('executeAfterDelete')) {
            $this->errors[] = "DialogMessagesDeleteService::executeAfterDelete() method not found";
            return false;
        }
        
        // Read the method source to verify it includes is_deleted = 0
        $method = $reflection->getMethod('executeAfterDelete');
        $filename = $method->getFileName();
        $startLine = $method->getStartLine();
        $endLine = $method->getEndLine();
        
        $source = file_get_contents($filename);
        $lines = explode("\n", $source);
        $methodSource = implode("\n", array_slice($lines, $startLine - 1, $endLine - $startLine + 1));
        
        if (strpos($methodSource, 'is_deleted = 0') === false && 
            strpos($methodSource, "is_deleted = 0") === false) {
            $this->errors[] = "executeAfterDelete() should filter soft-deleted messages (is_deleted = 0)";
            return false;
        }
        
        return true;
    }
    
    /**
     * Verify updated_ymdhis updates correctly
     */
    public function testUpdatedYmdhisUpdates() {
        // Check that both services update updated_ymdhis field
        $insertReflection = new ReflectionClass($this->insertService);
        $deleteReflection = new ReflectionClass($this->deleteService);
        
        $insertMethod = $insertReflection->getMethod('executeAfterInsert');
        $deleteMethod = $deleteReflection->getMethod('executeAfterDelete');
        
        $insertSource = file_get_contents($insertMethod->getFileName());
        $deleteSource = file_get_contents($deleteMethod->getFileName());
        
        $insertLines = explode("\n", $insertSource);
        $deleteLines = explode("\n", $deleteSource);
        
        $insertMethodSource = implode("\n", array_slice($insertLines, 
            $insertMethod->getStartLine() - 1, 
            $insertMethod->getEndLine() - $insertMethod->getStartLine() + 1));
        
        $deleteMethodSource = implode("\n", array_slice($deleteLines, 
            $deleteMethod->getStartLine() - 1, 
            $deleteMethod->getEndLine() - $deleteMethod->getStartLine() + 1));
        
        // Check for updated_ymdhis or modified_timestamp updates
        $insertHasUpdate = (strpos($insertMethodSource, 'updated_ymdhis') !== false || 
                           strpos($insertMethodSource, 'modified_timestamp') !== false);
        $deleteHasUpdate = (strpos($deleteMethodSource, 'updated_ymdhis') !== false || 
                           strpos($deleteMethodSource, 'modified_timestamp') !== false);
        
        if (!$insertHasUpdate) {
            $this->errors[] = "executeAfterInsert() should update updated_ymdhis or modified_timestamp";
            return false;
        }
        
        if (!$deleteHasUpdate) {
            $this->errors[] = "executeAfterDelete() should update updated_ymdhis or modified_timestamp";
            return false;
        }
        
        return true;
    }
    
    /**
     * Run all tests
     */
    public function runAllTests() {
        $results = [
            'insert_service_soft_delete' => $this->testInsertServiceSoftDeleteFiltering(),
            'delete_service_soft_delete' => $this->testDeleteServiceSoftDeleteFiltering(),
            'updated_ymdhis_updates' => $this->testUpdatedYmdhisUpdates()
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
