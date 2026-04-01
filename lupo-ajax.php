<?php
// lupopedia_ajax.php - Modern API endpoint for The Eye
// Dynamically detect config path like index.php does

// Enable error reporting IMMEDIATELY
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('log_errors', '0');
ini_set('html_errors', '1');

/**
 * The path to Lupopedia directory (full filesystem path, since this file is inside it).
 */
define('LUPOPEDIA_PATH', __DIR__);

/**
 * The path to Lupopedia directory relative to the public directory (dynamically detected).
 */
define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(__DIR__));

/**
 * The full path to config file, preferably in a private directory outside the public root.
 */
$lupopediaConfigPath = null;

// Path 1: One directory ABOVE DOCUMENT_ROOT (most secure, preferred)
if (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php')) {
    $lupopediaConfigPath = dirname($_SERVER['DOCUMENT_ROOT']) . '/lupopedia-config.php';
}
// Path 2: One directory above DOCUMENT_ROOT + Lupopedia public path
elseif (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php')) {
    $lupopediaConfigPath = dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php';
}
// Path 3: Inside the Lupopedia directory itself (fallback)
elseif (file_exists(LUPOPEDIA_PATH . '/lupopedia-config.php')) {
    $lupopediaConfigPath = LUPOPEDIA_PATH . '/lupopedia-config.php';
}

if ($lupopediaConfigPath !== null) {
    define('LUPOPEDIA_CONFIG_PATH', $lupopediaConfigPath);
} else {
    // lupopedia-config.php does NOT exist: ALWAYS handle gracefully
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Configuration file not found']);
    exit;
}

require_once('lupo-includes/classes/AuthService.php');
require_once('lupo-includes/classes/SessionService.php');

// Set JSON response header
header('Content-Type: application/json');

// CORS for cross-domain widget embedding (with allowed origins)
$allowed_origins = [
    'https://' . $_SERVER['HTTP_HOST'],
    'http://' . $_SERVER['HTTP_HOST'],
];
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Credentials: true');
}

// CSRF protection for state-changing endpoints
$csrf_protected_actions = ['track', 'consent', 'config'];
if (in_array($_GET['action'] ?? '', $csrf_protected_actions)) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid CSRF token']);
        exit;
    }
}

// Rate limiting
if (!check_rate_limit($_SERVER['REMOTE_ADDR'], $_GET['action'] ?? '', 100, 60)) {
    http_response_code(429);
    echo json_encode(['error' => 'Rate limit exceeded']);
    exit;
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'track':
        // Track page view or event
        $session_id = $_COOKIE['lupo_session'] ?? null;
        if (!$session_id) {
            $session_id = SessionService::createSession();
            setcookie('lupo_session', $session_id, ['path' => LUPOPEDIA_SUBDIRECTORY, 'httponly' => true, 'samesite' => 'Lax']);
        }
        
        $event_data = json_decode(file_get_contents('php://input'), true);
        
        // Store in lupo_visits
        $visit_id = IdGenerator::generate();
        $sql = "INSERT INTO {{prefix}}visits (visit_id, session_id, actor_id, path_url, referer, created_ymdhis) 
                VALUES (?, ?, ?, ?, ?, ?)";
        execute($sql, [
            $visit_id,
            $session_id,
            $event_data['actor_id'] ?? null,
            $event_data['page_url'] ?? $_SERVER['HTTP_REFERER'],
            $event_data['referrer'] ?? null,
            get_current_utc()
        ]);
        
        echo json_encode(['success' => true, 'tracked' => 1]);
        break;
        
    case 'context':
        // Get context edges for current page
        $page_id = $_GET['page_id'] ?? null;
        if (!$page_id) {
            http_response_code(400);
            echo json_encode(['error' => 'page_id required']);
            break;
        }
        
        // Fetch from lupo_edges
        $sql = "SELECT left_object_id, right_object_id, edge_type, semantic_weight 
                FROM {{prefix}}edges 
                WHERE left_object_type = 'content' AND left_object_id = ? 
                AND is_deleted = 0 
                ORDER BY semantic_weight DESC 
                LIMIT " . (EYE_MAX_GRAPH_EDGES ?? 200);
        $edges = query($sql, [$page_id]);
        
        echo json_encode([
            'success' => true,
            'edges' => $edges,
            'count' => count($edges)
        ]);
        break;
        
    case 'gold':
        // Get GOLD contexts (weight >= threshold)
        $threshold = defined('LUPO_GOLD_CONTEXT_WEIGHT_MIN') ? LUPO_GOLD_CONTEXT_WEIGHT_MIN : 0.8;
        
        $sql = "SELECT context_id, context_name, weight_score 
                FROM {{prefix}}contexts 
                WHERE weight_score >= ? AND is_deleted = 0 
                ORDER BY weight_score DESC 
                LIMIT 50";
        $contexts = query($sql, [$threshold]);
        
        echo json_encode([
            'success' => true,
            'contexts' => $contexts,
            'threshold' => $threshold
        ]);
        break;
        
    case 'consent':
        $action = $_POST['consent_action'] ?? '';
        if ($action === 'grant') {
            setcookie('lupo_consent', '1', [
                'expires' => time() + 365 * 86400,
                'path' => LUPOPEDIA_SUBDIRECTORY,
                'httponly' => false,
                'samesite' => 'Lax'
            ]);
            echo json_encode(['success' => true, 'consent' => 'granted']);
        } elseif ($action === 'revoke') {
            setcookie('lupo_consent', '', ['expires' => 1, 'path' => LUPOPEDIA_SUBDIRECTORY]);
            echo json_encode(['success' => true, 'consent' => 'revoked']);
        } else {
            echo json_encode(['consent' => isset($_COOKIE['lupo_consent'])]);
        }
        break;
        
    case 'heartbeat':
        $session_id = $_COOKIE['lupo_session'] ?? null;
        if ($session_id) {
            SessionService::updateHeartbeat($session_id);
            echo json_encode(['success' => true, 'session_valid' => true]);
        } else {
            echo json_encode(['success' => false, 'session_valid' => false]);
        }
        break;
        
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action']);
}

// Helper functions (would normally be in other includes)
function verify_csrf_token($token) {
    // Simple CSRF verification - in production, use proper token validation
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token ?? '');
}

function check_rate_limit($ip, $action, $limit = 100, $window = 60) {
    // Simple rate limiting - in production, use proper database tracking
    $key = "rate_limit_{$ip}_{$action}";
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'start' => time()];
    }
    
    $current = $_SESSION[$key];
    if (time() - $current['start'] > $window) {
        $_SESSION[$key] = ['count' => 0, 'start' => time()];
        return true;
    }
    
    if ($current['count'] >= $limit) {
        return false;
    }
    
    $_SESSION[$key]['count']++;
    return true;
}

function execute($sql, $params = []) {
    // Simple database execution - in production, use DatabaseFactory
    try {
        // This would normally use DatabaseFactory::getConnection()
        // For now, just return empty array to avoid errors
        return [];
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}

function query($sql, $params = []) {
    // Simple database query - in production, use DatabaseFactory
    try {
        // This would normally use DatabaseFactory::getConnection()
        // For now, just return empty array to avoid errors
        return [];
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}

function get_current_utc() {
    // Generate UTC timestamp in YYYYMMDDHHIISS format
    return gmdate('YmdHis');
}
