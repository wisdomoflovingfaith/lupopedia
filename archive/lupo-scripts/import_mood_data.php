<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "lupo-scripts/import_mood_data.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175911"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
lupopedia.headers:
  when_updated: "20260324175617"
  file_path_from_root: "lupo-scripts/import_mood_data.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175617"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
 * DB-Canonical Mood Data Import Pipeline
 * 
 * Imports mood-related data into canonical database tables with strict validation,
 * idempotency, atomicity, and deterministic behavior.
 * 
 * Usage: php import_mood_data.php [import_directory]
 * Default import directory: lupo-uploads/mood_data/
 * 
 * @param string $import_dir Directory containing mood data files
 * @return array Structured result with statistics and any errors
 */

// Load bootstrap and dependencies
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'bootstrap.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'lupo-app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'Validation' . DIRECTORY_SEPARATOR . 'HeaderValidationService.php';

/**
 * Main import function for mood data.
 * 
 * @param string $import_dir Directory containing mood data files
 * @return array Structured result
 */
function import_mood_data($import_dir = null) {
    $result = array(
        'success' => true,
        'files_scanned' => 0,
        'files_imported' => 0,
        'files_skipped' => 0,
        'files_rejected' => 0,
        'rejections' => array(),
        'errors' => array()
    );
    
    // Set default import directory
    if ($import_dir === null) {
        $import_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lupo-uploads' . DIRECTORY_SEPARATOR . 'mood_data';
    }
    
    // Validate import directory
    if (!is_dir($import_dir)) {
        $result['success'] = false;
        $result['errors'][] = 'Import directory not found: ' . $import_dir;
        return $result;
    }
    
    // Initialize database connection
    $db = initialize_database();
    if (!$db) {
        $result['success'] = false;
        $result['errors'][] = 'Database connection failed';
        return $result;
    }
    
    // Initialize validation service
    $actorService = isset($GLOBALS['lupo_actor_service']) ? $GLOBALS['lupo_actor_service'] : null;
    $validator = new \App\Services\Validation\HeaderValidationService($actorService);
    
    // Discover files in deterministic order
    $files = discover_mood_files($import_dir);
    $result['files_scanned'] = count($files);
    
    echo "Scanning {$result['files_scanned']} files in: " . basename($import_dir) . "\n";
    
    // Process each file
    foreach ($files as $file) {
        $file_result = process_mood_file($file, $validator, $db);
        
        if ($file_result['status'] === 'imported') {
            $result['files_imported']++;
            echo "  ✓ Imported: " . basename($file) . "\n";
        } elseif ($file_result['status'] === 'skipped') {
            $result['files_skipped']++;
            echo "  ⏭ Skipped: " . basename($file) . " ({$file_result['reason']})\n";
        } elseif ($file_result['status'] === 'rejected') {
            $result['files_rejected']++;
            $result['rejections'][] = array(
                'file' => basename($file),
                'reason' => $file_result['reason']
            );
            echo "  ✗ Rejected: " . basename($file) . " ({$file_result['reason']})\n";
        } else {
            $result['errors'][] = "Unexpected status for " . basename($file) . ": " . $file_result['status'];
        }
    }
    
    echo "\nImport complete:\n";
    echo "  Scanned: {$result['files_scanned']}\n";
    echo "  Imported: {$result['files_imported']}\n";
    echo "  Skipped: {$result['files_skipped']}\n";
    echo "  Rejected: {$result['files_rejected']}\n";
    
    if (!empty($result['errors'])) {
        echo "  Errors: " . count($result['errors']) . "\n";
        $result['success'] = false;
    }
    
    return $result;
}

/**
 * Initialize database connection using Lupopedia conventions.
 * 
 * @return PDO|null Database connection or null on failure
 */
function initialize_database() {
    try {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        );
        
        return new PDO($dsn, DB_USER, DB_PASSWORD, $options);
    } catch (PDOException $e) {
        echo "Database connection failed: " . $e->getMessage() . "\n";
        return null;
    }
}

/**
 * Discover mood data files in deterministic lexical order.
 * 
 * @param string $import_dir Directory to scan
 * @return array Array of file paths
 */
function discover_mood_files($import_dir) {
    $files = array();
    
    // Scan for supported file types
    $iterator = new DirectoryIterator($import_dir);
    
    foreach ($iterator as $fileinfo) {
        if ($fileinfo->isDot() || !$fileinfo->isFile()) {
            continue;
        }
        
        $filename = $fileinfo->getFilename();
        $extension = strtolower($fileinfo->getExtension());
        
        // Only process supported file types
        if (in_array($extension, array('json', 'yaml', 'yml', 'md'))) {
            $files[] = $fileinfo->getPathname();
        }
    }
    
    // Sort deterministically (lexical order)
    sort($files, SORT_STRING);
    
    return $files;
}

/**
 * Process a single mood file.
 * 
 * @param string $file Path to file
 * @param HeaderValidationService $validator Header validator
 * @param PDO $db Database connection
 * @return array Processing result
 */
function process_mood_file($file, $validator, $db) {
    // Phase 1: Parse header
    $header = parse_file_header($file);
    if (!$header) {
        return array('status' => 'rejected', 'reason' => 'Failed to parse header');
    }
    
    // Phase 2: Validate header using existing validation layer
    $validation = $validator->validate($header);
    if (!isset($validation['valid']) || !$validation['valid']) {
        return array('status' => 'rejected', 'reason' => 'Header validation failed: ' . implode(', ', $validation['errors']));
    }
    
    // Phase 3: Parse body/data payload
    $payload = parse_file_payload($file, $header);
    if (!$payload) {
        return array('status' => 'rejected', 'reason' => 'Failed to parse payload');
    }
    
    // Phase 4: Compute idempotency key
    $idempotency_key = compute_idempotency_key($file, $header, $payload);
    
    // Phase 5: Check if already imported (idempotency)
    if (is_already_imported($db, $idempotency_key)) {
        return array('status' => 'skipped', 'reason' => 'Already imported (idempotency)');
    }
    
    // Phase 6-9: Atomic transaction
    return import_with_transaction($db, $header, $payload, $idempotency_key);
}

/**
 * Parse header from file.
 * 
 * @param string $file File path
 * @return array|null Header data or null on failure
 */
function parse_file_header($file) {
    $content = file_get_contents($file);
    if ($content === false) {
        return null;
    }
    
    // Look for YAML header block
    if (preg_match('/^---\s*\n(.*?)\n---\s*\n/s', $content, $matches)) {
        $yaml_content = $matches[1];
        
        // Simple YAML parsing (basic key-value pairs)
        $header = array();
        $lines = explode("\n", $yaml_content);
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }
            
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $header[trim($key)] = trim($value);
            }
        }
        
        return $header;
    }
    
    return null;
}

/**
 * Parse payload/body from file.
 * 
 * @param string $file File path
 * @param array $header Parsed header
 * @return array|null Payload data or null on failure
 */
function parse_file_payload($file, $header) {
    $content = file_get_contents($file);
    if ($content === false) {
        return null;
    }
    
    // Remove header block
    $content = preg_replace('/^---\s*\n.*?\n---\s*\n/s', '', $content, 1);
    
    // Determine parsing method based on file type
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    
    switch ($extension) {
        case 'json':
            $payload = json_decode($content, true);
            return (json_last_error() === JSON_ERROR_NONE) ? $payload : null;
            
        case 'yaml':
        case 'yml':
            // Simple YAML parsing for mood data
            return parse_simple_yaml($content);
            
        case 'md':
            // Parse mood data from markdown
            return parse_mood_markdown($content);
            
        default:
            return null;
    }
}

/**
 * Parse simple YAML for mood data.
 * 
 * @param string $content YAML content
 * @return array Parsed data
 */
function parse_simple_yaml($content) {
    $data = array();
    $lines = explode("\n", $content);
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        
        if (strpos($line, ':') !== false) {
            list($key, $value) = explode(':', $line, 2);
            $data[trim($key)] = trim($value);
        }
    }
    
    return $data;
}

/**
 * Parse mood data from markdown format.
 * 
 * @param string $content Markdown content
 * @return array Parsed mood data
 */
function parse_mood_markdown($content) {
    $data = array();
    
    // Look for mood_RGB pattern
    if (preg_match('/mood_RGB:\s*([0-9A-Fa-f]{6})/', $content, $matches)) {
        $hex = $matches[1];
        $data['mood_r'] = hexdec(substr($hex, 0, 2));
        $data['mood_g'] = hexdec(substr($hex, 2, 2));
        $data['mood_b'] = hexdec(substr($hex, 4, 2));
    }
    
    // Look for mood_framework
    if (preg_match('/mood_framework:\s*([^\s\n]+)/', $content, $matches)) {
        $data['mood_framework'] = trim($matches[1]);
    }
    
    // Look for actor_id
    if (preg_match('/actor_id:\s*(\d+)/', $content, $matches)) {
        $data['actor_id'] = (int) $matches[1];
    }
    
    return $data;
}

/**
 * Compute idempotency key for import tracking.
 * 
 * @param string $file File path
 * @param array $header Header data
 * @param array $payload Payload data
 * @return string Idempotency key
 */
function compute_idempotency_key($file, $header, $payload) {
    $key_data = array(
        'file' => basename($file),
        'modified' => filemtime($file),
        'size' => filesize($file),
        'header_hash' => md5(json_encode($header)),
        'payload_hash' => md5(json_encode($payload))
    );
    
    return 'mood_import_' . md5(json_encode($key_data));
}

/**
 * Check if import was already processed.
 * 
 * @param PDO $db Database connection
 * @param string $idempotency_key Idempotency key
 * @return bool True if already imported
 */
function is_already_imported($db, $idempotency_key) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM lupo_import_tracking WHERE import_key = ?");
    $stmt->execute(array($idempotency_key));
    return $stmt->fetchColumn() > 0;
}

/**
 * Import mood data with atomic transaction.
 * 
 * @param PDO $db Database connection
 * @param array $header Header data
 * @param array $payload Payload data
 * @param string $idempotency_key Idempotency key
 * @return array Processing result
 */
function import_with_transaction($db, $header, $payload, $idempotency_key) {
    try {
        $db->beginTransaction();
        
        // Validate required payload fields
        if (!validate_mood_payload($payload)) {
            $db->rollBack();
            return array('status' => 'rejected', 'reason' => 'Invalid payload data');
        }
        
        // Insert mood data
        $mood_result = insert_mood_data($db, $header, $payload);
        if (!$mood_result) {
            $db->rollBack();
            return array('status' => 'rejected', 'reason' => 'Failed to insert mood data');
        }
        
        // Record import tracking
        $tracking_result = record_import_tracking($db, $idempotency_key, $header);
        if (!$tracking_result) {
            $db->rollBack();
            return array('status' => 'rejected', 'reason' => 'Failed to record import tracking');
        }
        
        $db->commit();
        return array('status' => 'imported', 'reason' => 'Successfully imported');
        
    } catch (Exception $e) {
        $db->rollBack();
        return array('status' => 'rejected', 'reason' => 'Transaction failed: ' . $e->getMessage());
    }
}

/**
 * Validate mood payload data.
 * 
 * @param array $payload Payload data
 * @return bool True if valid
 */
function validate_mood_payload($payload) {
    // Required fields
    if (!isset($payload['actor_id']) || !is_numeric($payload['actor_id'])) {
        return false;
    }
    
    // Mood Vector values (optional, defaults to 0)
    $mood_r = isset($payload['mood_r']) ? (int) $payload['mood_r'] : 0;
    $mood_g = isset($payload['mood_g']) ? (int) $payload['mood_g'] : 0;
    $mood_b = isset($payload['mood_b']) ? (int) $payload['mood_b'] : 0;
    
    // Validate RGB ranges (0-255)
    if ($mood_r < 0 || $mood_r > 255 || $mood_g < 0 || $mood_g > 255 || $mood_b < 0 || $mood_b > 255) {
        return false;
    }
    
    // Mood framework (optional, defaults to 'western_analytical')
    $mood_framework = isset($payload['mood_framework']) ? $payload['mood_framework'] : 'western_analytical';
    if (strlen($mood_framework) > 32) {
        return false;
    }
    
    return true;
}

/**
 * Insert mood data into canonical table.
 * 
 * @param PDO $db Database connection
 * @param array $header Header data
 * @param array $payload Payload data
 * @return bool True on success
 */
function insert_mood_data($db, $header, $payload) {
    $actor_id = (int) $payload['actor_id'];
    $mood_r = isset($payload['mood_r']) ? (int) $payload['mood_r'] : 0;
    $mood_g = isset($payload['mood_g']) ? (int) $payload['mood_g'] : 0;
    $mood_b = isset($payload['mood_b']) ? (int) $payload['mood_b'] : 0;
    $mood_framework = isset($payload['mood_framework']) ? $payload['mood_framework'] : 'western_analytical';
    $timestamp_utc = gmdate('YmdHis');
    
    // Use INSERT IGNORE for idempotency within the same transaction
    $sql = "INSERT IGNORE INTO lupo_actor_moods 
            (actor_id, mood_r, mood_g, mood_b, mood_framework, timestamp_utc) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    return $stmt->execute(array($actor_id, $mood_r, $mood_g, $mood_b, $mood_framework, $timestamp_utc));
}

/**
 * Record import tracking for idempotency.
 * 
 * @param PDO $db Database connection
 * @param string $idempotency_key Idempotency key
 * @param array $header Header data
 * @return bool True on success
 */
function record_import_tracking($db, $idempotency_key, $header) {
    $file_path = isset($header['file_path_from_root']) ? $header['file_path_from_root'] : '';
    $actor_id = isset($header['actor_id']) ? (int) $header['actor_id'] : 0;
    $timestamp_utc = gmdate('YmdHis');
    
    // Create tracking table if it doesn't exist (doctrine-compliant)
    $create_table_sql = "
        CREATE TABLE IF NOT EXISTS lupo_import_tracking (
            tracking_id BIGINT PRIMARY KEY AUTO_INCREMENT,
            import_key VARCHAR(255) NOT NULL,
            file_path VARCHAR(500) NOT NULL,
            actor_id BIGINT NOT NULL,
            timestamp_utc BIGINT NOT NULL,
            is_deleted TINYINT(1) DEFAULT 0,
            deleted_ymdhis BIGINT NULL,
            UNIQUE KEY idx_import_key (import_key, is_deleted),
            INDEX idx_timestamp (timestamp_utc, is_deleted)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ";
    
    $db->exec($create_table_sql);
    
    $sql = "INSERT IGNORE INTO lupo_import_tracking 
            (import_key, file_path, actor_id, timestamp_utc) 
            VALUES (?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    return $stmt->execute(array($idempotency_key, $file_path, $actor_id, $timestamp_utc));
}

// Main execution
if (php_sapi_name() === 'cli') {
    $import_dir = isset($argv[1]) ? $argv[1] : null;
    $result = import_mood_data($import_dir);
    
    // Exit with appropriate code
    exit($result['success'] ? 0 : 1);
}

return array('function' => 'import_mood_data');
