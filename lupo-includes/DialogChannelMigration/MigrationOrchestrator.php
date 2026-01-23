<?php
/**
 * Migration Orchestrator for Big Rock 2: Dialog Channel Migration
 * 
 * Coordinates the complete migration process from .md files to database.
 * Provides CLI interface, progress reporting, and comprehensive error handling.
 * 
 * @package Lupopedia
 * @subpackage DialogChannelMigration
 * @version 4.0.102
 * @author Captain Wolfie
 */

require_once 'DialogParser.php';
require_once 'ChannelBuilder.php';
require_once 'MessageBuilder.php';

class MigrationOrchestrator {
    
    private $db;
    private $parser;
    private $channelBuilder;
    private $messageBuilder;
    private $errors = [];
    private $warnings = [];
    private $stats = [];
    
    /**
     * Constructor
     * 
     * @param PDO $database Database connection
     */
    public function __construct($database) {
        $this->db = $database;
        $this->parser = new DialogParser();
        $this->channelBuilder = new ChannelBuilder($database);
        $this->messageBuilder = new MessageBuilder($database);
        
        $this->initializeStats();
    }
    
    /**
     * Execute complete migration process
     * 
     * @param string $dialogsPath Path to dialogs directory
     * @param bool $dryRun If true, don't actually insert data
     * @return array Migration results
     */
    public function executeMigration($dialogsPath = 'dialogs', $dryRun = false) {
        $this->log("=== DIALOG CHANNEL MIGRATION STARTED ===");
        $this->log("Mode: " . ($dryRun ? "DRY RUN" : "LIVE MIGRATION"));
        $this->log("Dialogs Path: {$dialogsPath}");
        
        try {
            // Step 1: Scan dialog files
            $this->log("\n1. Scanning dialog files...");
            $dialogFiles = $this->scanDialogFiles($dialogsPath);
            $this->stats['files_found'] = count($dialogFiles);
            $this->log("Found " . count($dialogFiles) . " dialog files");
            
            // Step 2: Create database tables if needed
            if (!$dryRun) {
                $this->log("\n2. Ensuring database schema...");
                $this->ensureDatabaseSchema();
            }
            
            // Step 3: Process each file
            $this->log("\n3. Processing dialog files...");
            foreach ($dialogFiles as $file) {
                $this->processDialogFile($file, $dryRun);
            }
            
            // Step 4: Generate migration report
            $this->log("\n4. Generating migration report...");
            $report = $this->generateMigrationReport();
            
            $this->log("\n=== MIGRATION COMPLETED ===");
            return $report;
            
        } catch (Exception $e) {
            $this->errors[] = "Migration failed: " . $e->getMessage();
            $this->log("ERROR: " . $e->getMessage());
            return $this->generateMigrationReport();
        }
    }
    
    /**
     * Scan for dialog files
     * 
     * @param string $dialogsPath Path to dialogs directory
     * @return array Array of file paths
     */
    private function scanDialogFiles($dialogsPath) {
        if (!is_dir($dialogsPath)) {
            throw new Exception("Dialogs directory not found: {$dialogsPath}");
        }
        
        $files = glob($dialogsPath . '/*.md');
        if (empty($files)) {
            $this->warnings[] = "No .md files found in {$dialogsPath}";
        }
        
        return $files;
    }
    
    /**
     * Ensure database schema exists
     */
    private function ensureDatabaseSchema() {
        try {
            // Check if tables exist
            $channelsExists = $this->tableExists('lupo_dialog_channels');
            $messagesExists = $this->tableExists('lupo_dialog_doctrine');
            
            if (!$channelsExists || !$messagesExists) {
                $this->log("Creating database schema...");
                $this->createDatabaseSchema();
            } else {
                $this->log("Database schema already exists");
            }
            
        } catch (Exception $e) {
            throw new Exception("Failed to ensure database schema: " . $e->getMessage());
        }
    }
    
    /**
     * Check if table exists
     * 
     * @param string $tableName Table name to check
     * @return bool True if exists
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
    
    /**
     * Create database schema
     */
    private function createDatabaseSchema() {
        $schemaFile = 'database/schema/dialog_system_schema.sql';
        
        if (!file_exists($schemaFile)) {
            throw new Exception("Schema file not found: {$schemaFile}");
        }
        
        $sql = file_get_contents($schemaFile);
        
        // Execute schema creation
        try {
            $this->db->exec($sql);
            $this->log("Database schema created successfully");
        } catch (Exception $e) {
            throw new Exception("Failed to create schema: " . $e->getMessage());
        }
    }
    
    /**
     * Process a single dialog file
     * 
     * @param string $filePath Path to dialog file
     * @param bool $dryRun Dry run mode
     */
    private function processDialogFile($filePath, $dryRun) {
        $fileName = basename($filePath);
        $this->log("Processing: {$fileName}");
        
        try {
            // Parse the file
            $parsed = $this->parser->parseDialogFile($filePath);
            
            // Track parsing results
            $this->stats['files_processed']++;
            if (!empty($parsed['errors'])) {
                $this->errors = array_merge($this->errors, $parsed['errors']);
                $this->stats['files_with_errors']++;
            }
            if (!empty($parsed['warnings'])) {
                $this->warnings = array_merge($this->warnings, $parsed['warnings']);
            }
            
            $messageCount = count($parsed['messages']);
            $this->stats['total_messages'] += $messageCount;
            
            $this->log("  - Parsed {$messageCount} messages");
            
            if (!$dryRun) {
                // Create channel
                $channelId = $this->channelBuilder->createChannel($parsed['metadata'], $fileName);
                
                if ($channelId) {
                    $this->stats['channels_created']++;
                    $this->log("  - Created channel ID: {$channelId}");
                    
                    // Create messages
                    $messagesCreated = $this->messageBuilder->createMessages($channelId, $parsed['messages']);
                    $this->stats['messages_created'] += $messagesCreated;
                    $this->log("  - Created {$messagesCreated} messages");
                    
                    // Update channel message count
                    $this->channelBuilder->updateMessageCount($channelId, $messagesCreated);
                    
                } else {
                    $this->errors = array_merge($this->errors, $this->channelBuilder->getErrors());
                    $this->stats['channels_failed']++;
                }
            } else {
                $this->log("  - DRY RUN: Would create channel with {$messageCount} messages");
            }
            
        } catch (Exception $e) {
            $this->errors[] = "Failed to process {$fileName}: " . $e->getMessage();
            $this->stats['files_failed']++;
        }
    }
    
    /**
     * Generate migration report
     * 
     * @return array Migration report
     */
    private function generateMigrationReport() {
        return [
            'success' => empty($this->errors),
            'statistics' => $this->stats,
            'errors' => $this->errors,
            'warnings' => $this->warnings,
            'summary' => $this->generateSummary(),
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Generate summary text
     * 
     * @return string Summary text
     */
    private function generateSummary() {
        $summary = [];
        
        $summary[] = "Migration Summary:";
        $summary[] = "- Files found: " . $this->stats['files_found'];
        $summary[] = "- Files processed: " . $this->stats['files_processed'];
        $summary[] = "- Files failed: " . $this->stats['files_failed'];
        $summary[] = "- Channels created: " . $this->stats['channels_created'];
        $summary[] = "- Messages created: " . $this->stats['messages_created'];
        $summary[] = "- Total errors: " . count($this->errors);
        $summary[] = "- Total warnings: " . count($this->warnings);
        
        $successRate = $this->stats['files_found'] > 0 
            ? round(($this->stats['files_processed'] / $this->stats['files_found']) * 100, 1)
            : 0;
        $summary[] = "- Success rate: {$successRate}%";
        
        return implode("\n", $summary);
    }
    
    /**
     * Initialize statistics
     */
    private function initializeStats() {
        $this->stats = [
            'files_found' => 0,
            'files_processed' => 0,
            'files_failed' => 0,
            'files_with_errors' => 0,
            'channels_created' => 0,
            'channels_failed' => 0,
            'messages_created' => 0,
            'total_messages' => 0
        ];
    }
    
    /**
     * Log message
     * 
     * @param string $message Message to log
     */
    private function log($message) {
        echo $message . "\n";
    }
    
    /**
     * Get migration statistics
     * 
     * @return array Statistics array
     */
    public function getStatistics() {
        return $this->stats;
    }
    
    /**
     * Get errors
     * 
     * @return array Array of error messages
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Get warnings
     * 
     * @return array Array of warning messages
     */
    public function getWarnings() {
        return $this->warnings;
    }
    
    /**
     * Clear all messages and stats
     */
    public function reset() {
        $this->errors = [];
        $this->warnings = [];
        $this->initializeStats();
        $this->parser->clearMessages();
        $this->channelBuilder->clearErrors();
        $this->messageBuilder->clearMessages();
    }
}