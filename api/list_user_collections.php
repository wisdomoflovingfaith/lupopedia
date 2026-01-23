<?php
/**
 * API endpoint to list collections for the current user
 * 
 * Returns collections the user has access to in JSON format.
 * Used by the Collections dropdown component.
 */

// Load config
$config_paths = [
    dirname(dirname(__DIR__)) . '/lupopedia-config.php',
    dirname(__DIR__) . '/lupopedia-config.php',
    __DIR__ . '/../lupopedia-config.php'
];

$config_loaded = false;
foreach ($config_paths as $config_path) {
    if (file_exists($config_path)) {
        require_once $config_path;
        $config_loaded = true;
        break;
    }
}

if (!$config_loaded) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Config file not found']);
    exit;
}

// Load bootstrap
if (file_exists(LUPOPEDIA_PATH . '/lupo-includes/bootstrap.php')) {
    require_once LUPOPEDIA_PATH . '/lupo-includes/bootstrap.php';
}

// Get current user ID
$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

// Query collections
global $table_prefix;

if (empty($GLOBALS['mydatabase'])) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Database connection not available']);
    exit;
}

$db = $GLOBALS['mydatabase'];
$collections = [];

try {
    // For viewing: return all collections (permissions apply to editing only)
    // For userId = 0 (not logged in), return all collections for public viewing
    if ($userId == 0) {
        // Return all collections for public viewing
        $sql = "SELECT collection_id, name, description, slug
                FROM {$table_prefix}collections
                WHERE is_deleted = 0
                ORDER BY name ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $collections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // For logged-in users: can filter by permissions if needed, but for viewing return all
        // Check lupo_actor_collections for user's collections
        $sql = "SELECT DISTINCT c.collection_id, c.name, c.description, c.slug
                FROM {$table_prefix}collections c
                LEFT JOIN {$table_prefix}actor_collections ac ON c.collection_id = ac.collection_id AND ac.actor_id = :user_id
                WHERE c.is_deleted = 0
                  AND (ac.actor_id IS NOT NULL OR :user_id = 0)
                ORDER BY c.name ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $collections = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // If no collections found via actor_collections, fall back to all collections (public viewing)
        if (empty($collections)) {
            $sql = "SELECT collection_id, name, description, slug
                    FROM {$table_prefix}collections
                    WHERE is_deleted = 0
                    ORDER BY name ASC";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $collections = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'collections' => $collections
    ]);
    
} catch (PDOException $e) {
    error_log('List user collections error: ' . $e->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
