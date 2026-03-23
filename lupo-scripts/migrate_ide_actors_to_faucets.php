<?php
/**
 * Migrate IDE Actors to Faucets
 * 
 * Reclassifies existing IDE-named actors as faucets while preserving
 * historical data and maintaining deterministic actor IDs.
 * 
 * Phase 2 Implementation - HEPHAESTUS role execution through Windsurf faucet
 * 
 * @package Scripts
 * @version 4.0.86
 */

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'bootstrap.php';

/**
 * Main migration function.
 * 
 * @param PDO $db Database connection
 * @return array Migration results
 */
function migrate_ide_actors_to_faucets($db) {
    $result = array(
        'success' => true,
        'actors_processed' => 0,
        'actors_updated' => 0,
        'actors_skipped' => 0,
        'errors' => array()
    );
    
    echo "Starting IDE actor to faucet migration...\n";
    
    // Step 1: Read all .metadata.yaml files
    $metadataFiles = find_metadata_files();
    echo "Found " . count($metadataFiles) . " metadata files\n";
    
    // Step 2: Process each metadata file
    foreach ($metadataFiles as $metadataFile) {
        $processResult = process_metadata_file($db, $metadataFile);
        
        if ($processResult['updated']) {
            $result['actors_updated']++;
        } elseif ($processResult['skipped']) {
            $result['actors_skipped']++;
        } else {
            $result['errors'][] = $processResult['error'];
        }
        
        $result['actors_processed']++;
        
        echo "  " . ($processResult['updated'] ? 'UPDATED' : ($processResult['skipped'] ? 'SKIPPED' : 'ERROR')) . 
             ": " . basename($metadataFile) . "\n";
    }
    
    echo "\nMigration complete:\n";
    echo "  Processed: {$result['actors_processed']}\n";
    echo "  Updated: {$result['actors_updated']}\n";
    echo "  Skipped: {$result['actors_skipped']}\n";
    echo "  Errors: " . count($result['errors']) . "\n";
    
    if (!empty($result['errors'])) {
        $result['success'] = false;
        echo "Migration completed with errors.\n";
    } else {
        echo "Migration completed successfully.\n";
    }
    
    return $result;
}

/**
 * Find all .metadata.yaml files in lupo-actors directory.
 * 
 * @return array Array of file paths
 */
function find_metadata_files() {
    $metadataFiles = array();
    $actorsDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lupo-actors';
    
    if (!is_dir($actorsDir)) {
        echo "Error: lupo-actors directory not found\n";
        return $metadataFiles;
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($actorsDir),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $fileInfo) {
        if ($fileInfo->isFile() && $fileInfo->getFilename() === '.metadata.yaml') {
            $metadataFiles[] = $fileInfo->getPathname();
        }
    }
    
    sort($metadataFiles);
    return $metadataFiles;
}

/**
 * Process a single metadata file and update actor record.
 * 
 * @param PDO $db Database connection
 * @param string $metadataFile Path to metadata file
 * @return array Processing result
 */
function process_metadata_file($db, $metadataFile) {
    // Read and parse metadata file
    $metadata = read_metadata_file($metadataFile);
    if (!$metadata) {
        return array('updated' => false, 'skipped' => false, 'error' => 'Failed to read metadata: ' . basename($metadataFile));
    }
    
    // Extract actor directory path
    $actorDir = dirname($metadataFile);
    $actorSlug = basename($actorDir);
    
    // Parse actor_id from directory name if numeric
    $actorId = null;
    if (is_numeric($actorSlug)) {
        $actorId = (int)$actorSlug;
    } elseif (isset($metadata['actor_id'])) {
        $actorId = (int)$metadata['actor_id'];
    }
    
    if (!$actorId) {
        return array('updated' => false, 'skipped' => true, 'error' => 'No actor_id found');
    }
    
    // Check if this is an IDE actor that needs reclassification
    $ideActors = array('cursor', 'windsurf', 'vscode', 'antigravity');
    $isIdeActor = in_array($actorSlug, $ideActors);
    
    // Get current actor record
    $currentRecord = get_actor_record($db, $actorId);
    if (!$currentRecord) {
        return array('updated' => false, 'skipped' => true, 'error' => 'Actor record not found: ' . $actorId);
    }
    
    // Check if update is needed
    $needsUpdate = false;
    $updateFields = array();
    
    if ($isIdeActor) {
        // This is an IDE actor - reclassify as faucet
        if ($currentRecord['actor_type'] !== 'faucet') {
            $updateFields['actor_type'] = 'faucet';
            $needsUpdate = true;
        }
        
        // Update name to preserve IDE identity as display name
        if ($currentRecord['name'] !== $actorSlug) {
            $updateFields['name'] = $actorSlug;
            $needsUpdate = true;
        }
        
        // Update slug to preserve IDE identity
        if ($currentRecord['slug'] !== $actorSlug) {
            $updateFields['slug'] = $actorSlug;
            $needsUpdate = true;
        }
        
    } else {
        // This is a canonical actor - ensure it's not marked as faucet
        if ($currentRecord['actor_type'] === 'faucet') {
            $updateFields['actor_type'] = 'actor';
            $needsUpdate = true;
        }
    }
    
    // Preserve historical data
    $updateFields['paired_actor_id'] = isset($metadata['paired_actor_id']) ? (int)$metadata['paired_actor_id'] : null;
    $updateFields['display_name'] = isset($metadata['display_name']) ? $metadata['display_name'] : $metadata['name'];
    $updateFields['description'] = isset($metadata['description']) ? $metadata['description'] : null;
    $updateFields['workspace_path'] = isset($metadata['workspace_path']) ? $metadata['workspace_path'] : null;
    $updateFields['php_namespace'] = isset($metadata['php_namespace']) ? $metadata['php_namespace'] : null;
    
    // Add updated timestamp
    $updateFields['updated_ymdhis'] = gmdate('YmdHis');
    
    if (!$needsUpdate) {
        return array('updated' => false, 'skipped' => true, 'error' => null);
    }
    
    // Perform update
    $updateResult = update_actor_record($db, $actorId, $updateFields);
    
    if ($updateResult) {
        return array('updated' => true, 'skipped' => false, 'error' => null);
    } else {
        return array('updated' => false, 'skipped' => false, 'error' => 'Database update failed');
    }
}

/**
 * Read and parse YAML metadata file.
 * 
 * @param string $metadataFile Path to metadata file
 * @return array|null Parsed metadata or null on failure
 */
function read_metadata_file($metadataFile) {
    if (!file_exists($metadataFile)) {
        return null;
    }
    
    $content = file_get_contents($metadataFile);
    if ($content === false) {
        return null;
    }
    
    // Simple YAML parsing for our specific structure
    $metadata = array();
    $lines = explode("\n", $content);
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        
        if (strpos($line, ':') !== false) {
            list($key, $value) = explode(':', $line, 2);
            $metadata[trim($key)] = trim($value);
        }
    }
    
    return $metadata;
}

/**
 * Get current actor record from database.
 * 
 * @param PDO $db Database connection
 * @param int $actorId Actor ID
 * @return array|null Actor record or null
 */
function get_actor_record($db, $actorId) {
    $stmt = $db->prepare("SELECT * FROM lupo_actors WHERE actor_id = ? AND is_deleted = 0");
    $stmt->execute(array($actorId));
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Update actor record in database.
 * 
 * @param PDO $db Database connection
 * @param int $actorId Actor ID
 * @param array $fields Fields to update
 * @return bool Success status
 */
function update_actor_record($db, $actorId, $fields) {
    if (empty($fields)) {
        return true;
    }
    
    $setClauses = array();
    $values = array();
    
    foreach ($fields as $field => $value) {
        $setClauses[] = "$field = ?";
        $values[] = $value;
    }
    
    $values[] = $actorId;
    
    $sql = "UPDATE lupo_actors SET " . implode(', ', $setClauses) . " WHERE actor_id = ? AND is_deleted = 0";
    
    try {
        $stmt = $db->prepare($sql);
        return $stmt->execute($values);
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage() . "\n";
        return false;
    }
}

// Main execution
if (php_sapi_name() === 'cli') {
    // Initialize database connection
    try {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        );
        
        $db = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
        
        // Run migration
        $result = migrate_ide_actors_to_faucets($db);
        
        exit($result['success'] ? 0 : 1);
        
    } catch (PDOException $e) {
        echo "Database connection failed: " . $e->getMessage() . "\n";
        exit(1);
    }
}

return array('function' => 'migrate_ide_actors_to_faucets');
