<?php
/**
 * Validation Tool for Big Rock 2: Dialog Channel Migration
 * 
 * Validates migration accuracy and data integrity between .md files and database.
 * Provides comprehensive validation reports with pass/fail status.
 * 
 * @package Lupopedia
 * @subpackage DialogChannelMigration
 * @version 4.0.102
 * @author Captain Wolfie
 */

require_once 'DialogParser.php';

class ValidationTool {
    
    private $db;
    private $parser;
    private $errors = [];
    private $warnings = [];
    
    /**
     * Constructor
     * 
     * @param PDO $database Database connection
     */
    public function __construct($database) {
        $this->db = $database;
        $this->parser = new DialogParser();
    }
    
    /**
     * Validate complete migration
     * 
     * @param string $dialogsPath Path to dialogs directory
     * @return array Validation report
     */
    public function validateMigration($dialogsPath = 'dialogs') {
        $this->clearMessages();
        
        $report = [
            'valid' => true,
            'file_validations' => [],
            'database_integrity' => [],
            'summary' => [],
            'errors' => [],
            'warnings' => [],
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        try {
            // Step 1: Validate database schema
            $schemaValid = $this->validateDatabaseSchema();
            $report['database_integrity']['schema_valid'] = $schemaValid;
            
            if (!$schemaValid) {
                $report['valid'] = false;
                $this->errors[] = "Database schema validation failed";
            }
            
            // Step 2: Validate each file against database
            $dialogFiles = glob($dialogsPath . '/*.md');
            $totalFiles = count($dialogFiles);
            $validFiles = 0;
            
            foreach ($dialogFiles as $filePath) {
                $fileValidation = $this->validateFile($filePath);
                $report['file_validations'][] = $fileValidation;
                
                if ($fileValidation['valid']) {
                    $validFiles++;
                } else {
                    $report['valid'] = false;
                }
            }
            
            // Step 3: Check for orphaned database entries
            $orphanCheck = $this->checkOrphanedEntries($dialogFiles);
            $report['database_integrity']['orphaned_channels'] = $orphanCheck['orphaned_channels'];
            $report['database_integrity']['orphaned_messages'] = $orphanCheck['orphaned_messages'];
            
            if ($orphanCheck['has_orphans']) {
                $report['valid'] = false;
                $this->errors[] = "Found orphaned database entries";
            }
            
            // Step 4: Generate summary
            $report['summary'] = [
                'total_files' => $totalFiles,
                'valid_files' => $validFiles,
                'invalid_files' => $totalFiles - $validFiles,
                'success_rate' => $totalFiles > 0 ? round(($validFiles / $totalFiles) * 100, 1) : 0,
                'total_channels' => $this->countChannels(),
                'total_messages' => $this->countMessages()
            ];
            
            $report['errors'] = $this->errors;
            $report['warnings'] = $this->warnings;
            
        } catch (Exception $e) {
            $report['valid'] = false;
            $this->errors[] = "Validation failed: " . $e->getMessage();
            $report['errors'] = $this->errors;
        }
        
        return $report;
    }
    
    /**
     * Validate database schema
     * 
     * @return bool True if schema is valid
     */
    private function validateDatabaseSchema() {
        try {
            // Check if required tables exist
            $requiredTables = ['lupo_dialog_channels', 'lupo_dialog_doctrine'];
            
            foreach ($requiredTables as $table) {
                if (!$this->tableExists($table)) {
                    $this->errors[] = "Required table missing: {$table}";
                    return false;
                }
            }
            
            // Check table structure
            if (!$this->validateTableStructure('lupo_dialog_channels', $this->getChannelsTableStructure())) {
                return false;
            }
            
            if (!$this->validateTableStructure('lupo_dialog_doctrine', $this->getMessagesTableStructure())) {
                return false;
            }
            
            return true;
            
        } catch (Exception $e) {
            $this->errors[] = "Schema validation error: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Validate individual file against database
     * 
     * @param string $filePath Path to dialog file
     * @return array File validation result
     */
    private function validateFile($filePath) {
        $fileName = basename($filePath);
        $channelName = $this->generateChannelName($fileName);
        
        $validation = [
            'file_name' => $fileName,
            'channel_name' => $channelName,
            'valid' => true,
            'file_exists' => file_exists($filePath),
            'channel_exists' => false,
            'message_count_match' => false,
            'metadata_match' => false,
            'errors' => [],
            'warnings' => []
        ];
        
        try {
            if (!$validation['file_exists']) {
                $validation['valid'] = false;
                $validation['errors'][] = "File not found: {$filePath}";
                return $validation;
            }
            
            // Parse file
            $parsed = $this->parser->parseDialogFile($filePath);
            $fileMessageCount = count($parsed['messages']);
            
            // Check if channel exists in database
            $channel = $this->getChannelByName($channelName);
            $validation['channel_exists'] = ($channel !== false);
            
            if (!$validation['channel_exists']) {
                $validation['valid'] = false;
                $validation['errors'][] = "Channel not found in database: {$channelName}";
                return $validation;
            }
            
            // Validate message count
            $dbMessageCount = $this->countChannelMessages($channel['channel_id']);
            $validation['message_count_match'] = ($fileMessageCount === $dbMessageCount);
            $validation['file_message_count'] = $fileMessageCount;
            $validation['db_message_count'] = $dbMessageCount;
            
            if (!$validation['message_count_match']) {
                $validation['valid'] = false;
                $validation['errors'][] = "Message count mismatch: file={$fileMessageCount}, db={$dbMessageCount}";
            }
            
            // Validate metadata
            $metadataMatch = $this->validateChannelMetadata($parsed['metadata'], $channel);
            $validation['metadata_match'] = $metadataMatch['valid'];
            
            if (!$metadataMatch['valid']) {
                $validation['valid'] = false;
                $validation['errors'] = array_merge($validation['errors'], $metadataMatch['errors']);
            }
            
            // Add any parsing warnings
            if (!empty($parsed['warnings'])) {
                $validation['warnings'] = array_merge($validation['warnings'], $parsed['warnings']);
            }
            
        } catch (Exception $e) {
            $validation['valid'] = false;
            $validation['errors'][] = "Validation error: " . $e->getMessage();
        }
        
        return $validation;
    }
    
    /**
     * Check for orphaned database entries
     * 
     * @param array $dialogFiles Array of dialog file paths
     * @return array Orphan check results
     */
    private function checkOrphanedEntries($dialogFiles) {
        $result = [
            'has_orphans' => false,
            'orphaned_channels' => [],
            'orphaned_messages' => []
        ];
        
        try {
            // Get all channels from database
            $channels = $this->getAllChannels();
            
            // Create map of expected channel names
            $expectedChannels = [];
            foreach ($dialogFiles as $filePath) {
                $fileName = basename($filePath);
                $channelName = $this->generateChannelName($fileName);
                $expectedChannels[$channelName] = true;
            }
            
            // Check for orphaned channels
            foreach ($channels as $channel) {
                if (!isset($expectedChannels[$channel['channel_name']])) {
                    $result['orphaned_channels'][] = $channel;
                    $result['has_orphans'] = true;
                }
            }
            
            // Check for orphaned messages (messages without valid channels)
            $orphanedMessages = $this->findOrphanedMessages();
            if (!empty($orphanedMessages)) {
                $result['orphaned_messages'] = $orphanedMessages;
                $result['has_orphans'] = true;
            }
            
        } catch (Exception $e) {
            $this->errors[] = "Orphan check error: " . $e->getMessage();
        }
        
        return $result;
    }
    
    /**
     * Validate channel metadata
     * 
     * @param array $fileMetadata Metadata from file
     * @param array $dbChannel Channel data from database
     * @return array Validation result
     */
    private function validateChannelMetadata($fileMetadata, $dbChannel) {
        $result = ['valid' => true, 'errors' => []];
        
        // Check key fields
        $fieldsToCheck = ['title', 'description', 'speaker', 'target', 'mood_rgb', 'status', 'author'];
        
        foreach ($fieldsToCheck as $field) {
            $fileValue = $fileMetadata[$field] ?? null;
            $dbValue = $dbChannel[$field] ?? null;
            
            // Normalize values for comparison
            $fileValue = $this->normalizeValue($fileValue);
            $dbValue = $this->normalizeValue($dbValue);
            
            if ($fileValue !== $dbValue) {
                $result['valid'] = false;
                $result['errors'][] = "Metadata mismatch for {$field}: file='{$fileValue}', db='{$dbValue}'";
            }
        }
        
        return $result;
    }
    
    /**
     * Helper methods
     */
    
    private function tableExists($tableName) {
        try {
            $sql = "SHOW TABLES LIKE :table_name";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['table_name' => $tableName]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    private function validateTableStructure($tableName, $expectedColumns) {
        try {
            $sql = "DESCRIBE {$tableName}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $actualColumns = [];
            foreach ($columns as $column) {
                $actualColumns[$column['Field']] = $column['Type'];
            }
            
            foreach ($expectedColumns as $columnName => $columnType) {
                if (!isset($actualColumns[$columnName])) {
                    $this->errors[] = "Missing column in {$tableName}: {$columnName}";
                    return false;
                }
            }
            
            return true;
            
        } catch (Exception $e) {
            $this->errors[] = "Table structure validation error: " . $e->getMessage();
            return false;
        }
    }
    
    private function getChannelsTableStructure() {
        return [
            'channel_id' => 'bigint',
            'channel_name' => 'varchar',
            'file_source' => 'varchar',
            'title' => 'varchar',
            'description' => 'text',
            'speaker' => 'varchar',
            'target' => 'varchar',
            'mood_rgb' => 'varchar',
            'categories' => 'json',
            'collections' => 'json',
            'channels' => 'json',
            'tags' => 'json',
            'version' => 'varchar',
            'status' => 'enum',
            'author' => 'varchar',
            'created_timestamp' => 'bigint',
            'modified_timestamp' => 'bigint',
            'message_count' => 'int',
            'metadata_json' => 'json'
        ];
    }
    
    private function getMessagesTableStructure() {
        return [
            'dialog_message_id' => 'bigint',
            'dialog_thread_id' => 'bigint',
            'channel_id' => 'bigint',
            'from_actor_id' => 'bigint',
            'to_actor_id' => 'bigint',
            'message_text' => 'varchar',
            'message_type' => 'enum',
            'metadata_json' => 'json',
            'mood_rgb' => 'char',
            'weight' => 'decimal',
            'created_ymdhis' => 'bigint',
            'updated_ymdhis' => 'bigint',
            'is_deleted' => 'tinyint',
            'deleted_ymdhis' => 'bigint'
        ];
    }
    
    private function generateChannelName($fileName) {
        $name = preg_replace('/\.md$/', '', $fileName);
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
        $name = preg_replace('/_+/', '_', $name);
        return strtolower(trim($name, '_'));
    }
    
    private function getChannelByName($channelName) {
        try {
            $sql = "SELECT * FROM lupo_dialog_channels WHERE channel_name = :channel_name";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['channel_name' => $channelName]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }
    
    private function getAllChannels() {
        try {
            $sql = "SELECT * FROM lupo_dialog_channels ORDER BY created_timestamp DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function countChannels() {
        try {
            $sql = "SELECT COUNT(*) FROM lupo_dialog_channels";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function countMessages() {
        try {
            $sql = "SELECT COUNT(*) FROM lupo_dialog_doctrine";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function countChannelMessages($channelId) {
        try {
            $sql = "SELECT COUNT(*) FROM lupo_dialog_doctrine WHERE channel_id = :channel_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['channel_id' => $channelId]);
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function findOrphanedMessages() {
        try {
            $sql = "SELECT m.* FROM lupo_dialog_doctrine m 
                    LEFT JOIN lupo_dialog_channels c ON m.channel_id = c.channel_id 
                    WHERE c.channel_id IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function normalizeValue($value) {
        if ($value === null || $value === '') return null;
        return trim(strip_tags($value));
    }
    
    private function clearMessages() {
        $this->errors = [];
        $this->warnings = [];
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function getWarnings() {
        return $this->warnings;
    }
}