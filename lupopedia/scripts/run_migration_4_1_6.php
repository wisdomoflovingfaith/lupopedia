<?php
/**
 * Execute Migration 4.1.6 - LABS Declarations Tables
 * 
 * Creates lupo_labs_declarations and lupo_labs_violations tables
 * 
 * @package Lupopedia
 * @version 4.1.6
 * @author CAPTAIN_WOLFIE
 * @governance LABS-001 Doctrine v1.0
 */

require_once __DIR__ . '/../lupopedia-config.php';
require_once __DIR__ . '/../lupo-includes/bootstrap.php';

// Load migration SQL
$migration_file = __DIR__ . '/../database/migrations/4.1.6_create_labs_declarations_table.sql';

if (!file_exists($migration_file)) {
    die("ERROR: Migration file not found: {$migration_file}\n");
}

echo "=== Lupopedia Migration 4.1.6 - LABS Declarations Tables ===\n\n";
echo "Reading migration file...\n";

$sql = file_get_contents($migration_file);

// Remove comments and split into individual statements
$statements = array_filter(
    array_map('trim', explode(';', $sql)),
    function($stmt) {
        return !empty($stmt) && 
               !preg_match('/^--/', $stmt) && 
               !preg_match('/^\/\*/', $stmt) &&
               strlen($stmt) > 10;
    }
);

echo "Found " . count($statements) . " SQL statements to execute.\n\n";

try {
    $db = $mydatabase; // From bootstrap.php
    
    echo "Executing migration...\n";
    $success_count = 0;
    $error_count = 0;
    
    foreach ($statements as $index => $statement) {
        if (empty(trim($statement))) {
            continue;
        }
        
        try {
            $db->exec($statement);
            $success_count++;
            echo "  ✓ Statement " . ($index + 1) . " executed successfully\n";
        } catch (PDOException $e) {
            $error_count++;
            echo "  ✗ Statement " . ($index + 1) . " failed: " . $e->getMessage() . "\n";
            
            // Check if table already exists (not a fatal error)
            if (strpos($e->getMessage(), 'already exists') !== false) {
                echo "    (Table may already exist - this is OK)\n";
            }
        }
    }
    
    echo "\n=== Migration Summary ===\n";
    echo "Successful: {$success_count}\n";
    echo "Errors: {$error_count}\n";
    
    if ($error_count === 0) {
        echo "\n✓ Migration 4.1.6 completed successfully!\n";
        echo "LABS declarations and violations tables are now active.\n";
    } else {
        echo "\n⚠ Migration completed with errors. Please review above.\n";
    }
    
    // Verify tables exist
    echo "\n=== Verifying Tables ===\n";
    $tables_to_check = ['lupo_labs_declarations', 'lupo_labs_violations'];
    
    foreach ($tables_to_check as $table) {
        try {
            $stmt = $db->query("SHOW TABLES LIKE '{$table}'");
            if ($stmt->rowCount() > 0) {
                echo "  ✓ Table '{$table}' exists\n";
                
                // Count columns
                $stmt = $db->query("SHOW COLUMNS FROM `{$table}`");
                $column_count = $stmt->rowCount();
                echo "    ({$column_count} columns)\n";
            } else {
                echo "  ✗ Table '{$table}' NOT found\n";
            }
        } catch (PDOException $e) {
            echo "  ✗ Error checking table '{$table}': " . $e->getMessage() . "\n";
        }
    }
    
} catch (Exception $e) {
    die("FATAL ERROR: " . $e->getMessage() . "\n");
}

echo "\n=== Migration Complete ===\n";
