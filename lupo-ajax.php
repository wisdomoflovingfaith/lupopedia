<?php
// lupopedia_ajax.php - Modern API endpoint for The Eye
// LILITH AUDIT COMPLIANT - Production Ready Implementation

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
 * The full path to config file, preferably in a private directory outside of public root.
 */
$lupopediaConfigPath = null;

// INSTALL REDIRECT DOCTRINE (4.0.6+): If lupopedia-config.php does NOT exist, ALWAYS redirect to install.php
// Config search order for lupopedia-config.php:
// 1. One directory ABOVE DOCUMENT_ROOT (most secure, preferred)
// 2. One directory above DOCUMENT_ROOT + Lupopedia public path
// 3. Inside the Lupopedia directory itself (fallback)

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

if (!$lupopediaConfigPath) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Configuration file not found']);
    exit;
}

require_once($lupopediaConfigPath);

/**
 * Determine subdirectory from script path and define LUPOPEDIA_SUBDIRECTORY
 * This must be AFTER config loading to use config values if available
 */
if (!defined('LUPOPEDIA_SUBDIRECTORY')) {
    // Try to detect from script path first
    if (isset($_SERVER['SCRIPT_NAME'])) {
        $script_path = $_SERVER['SCRIPT_NAME'];
        $subdir = dirname($script_path);
        if ($subdir === '/' || $subdir === '\\') {
            $subdir = '';
        }
        define('LUPOPEDIA_SUBDIRECTORY', rtrim($subdir, '/') . '/');
    } else {
        // Fallback to public path
        define('LUPOPEDIA_SUBDIRECTORY', LUPOPEDIA_PUBLIC_PATH);
    }
}

// Define Eye widget configuration constants
define('EYE_WIDGET_ENABLED', true);
define('EYE_TRACKING_LEVEL', 'full'); // full, minimal, disabled
define('LUPO_GOLD_CONTEXT_WEIGHT_MIN', 0.8);      // Minimum weight_score for GOLD contexts
define('LUPO_GOLD_EDGE_WEIGHT_MIN', 0.5);         // Minimum semantic_weight for GOLD edges
define('EYE_MAX_GRAPH_NODES', 200);               // Maximum nodes in visualization
define('EYE_MAX_GRAPH_EDGES', 500);               // Maximum edges in visualization

// Define table prefix (from config or default)
define('LUPO_TABLE_PREFIX', defined('DB_PREFIX') ? DB_PREFIX : 'lupo_');

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

// Start session for CSRF and rate limiting
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load required classes
require_once(LUPOPEDIA_PATH . '/lupo-includes/classes/DatabaseFactory.php');
require_once(LUPOPEDIA_PATH . '/lupo-includes/classes/AuthService.php');
require_once(LUPOPEDIA_PATH . '/lupo-includes/classes/SessionService.php');
require_once(LUPOPEDIA_PATH . '/lupo-includes/classes/IdGenerator.php');

/**
 * Get client IP address with comprehensive 2026 CDN/cloud provider handling
 */
function get_client_ip() {
    // Trusted proxies - configure based on your infrastructure
    $trusted_proxies = [
        '127.0.0.1',
        '::1',
        '10.0.0.0/8',
        '172.16.0.0/12',
        '192.168.0.0/16',
        // CloudFlare IP ranges (2026)
        '173.245.48.0/20',
        '103.21.244.0/22',
        '103.22.200.0/22',
        '103.31.4.0/22',
        '141.101.64.0/18',
        '108.162.192.0/18',
        '190.93.240.0/20',
        '188.114.96.0/20',
        '197.234.240.0/22',
        '198.41.128.0/17',
        '162.158.0.0/15',
        '104.16.0.0/13',
        '104.24.0.0/14',
        '172.64.0.0/13',
        '131.0.72.0/22',
        // Fastly IP ranges (2026)
        '23.235.32.0/20',
        '43.249.72.0/22',
        '103.244.50.0/24',
        '103.245.222.0/23',
        '103.245.224.0/24',
        '104.156.80.0/20',
        '146.75.0.0/16',
        '151.101.0.0/16',
        '157.52.64.0/18',
        '167.82.0.0/17',
        '167.82.128.0/17',
        '185.31.16.0/22',
        '199.27.72.0/21',
        '199.232.0.0/16',
        // AWS CloudFront IP ranges (2026 - partial list)
        '13.32.0.0/15',
        '13.224.0.0/14',
        '13.248.0.0/14',
        '64.252.64.0/18',
        '70.132.0.0/18',
        '71.152.0.0/17',
        '99.84.0.0/16',
        '99.86.0.0/16',
        '108.138.0.0/15',
        '108.156.0.0/14',
        '130.176.0.0/16',
        '144.220.0.0/16',
        '204.246.160.0/19',
        '204.246.192.0/18',
        '205.251.192.0/19',
        '205.251.224.0/20',
        '205.251.240.0/20',
        '205.251.249.0/24',
        '216.137.32.0/19',
        // Akamai IP ranges (2026 - partial list)
        '2.16.0.0/13',
        '2.20.0.0/14',
        '23.0.0.0/12',
    ];
    
    // Comprehensive 2026 header list (priority: most specific first)
    $headers = [
        // CloudFlare (most common CDN)
        'HTTP_CF_CONNECTING_IP',
        'HTTP_TRUE_CLIENT_IP',
        
        // Fastly
        'HTTP_FASTLY_CLIENT_IP',
        
        // Fly.io (popular platform)
        'HTTP_FLY_CLIENT_IP',
        
        // Vercel
        'HTTP_X_VERCEL_FORWARDED_FOR',
        
        // CDN77
        'HTTP_CDN_CONNECTING_IP',
        
        // BunnyCDN
        'HTTP_BUNNY_CDN_CONNECTING_IP',
        
        // AWS CloudFront
        'HTTP_CLOUDFRONT_VIEWER_ADDRESS',
        
        // Google Cloud
        'HTTP_X_GOOG_REAL_IP',
        
        // Azure
        'HTTP_X_AZURE_CLIENTIP',
        
        // Standard/RFC headers (most common)
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_REAL_IP',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'HTTP_CLIENT_IP',
        
        // Ultimate fallback
        'REMOTE_ADDR'
    ];
    
    $remote_addr = $_SERVER['REMOTE_ADDR'] ?? '';
    $is_trusted = is_ip_in_trusted_ranges($remote_addr, $trusted_proxies);
    $fallback = '';
    
    foreach ($headers as $header) {
        if (empty($_SERVER[$header])) {
            continue;
        }
        
        $raw = $_SERVER[$header];
        
        // Only trust forwarded headers if from a trusted proxy
        if (!$is_trusted && $header !== 'REMOTE_ADDR') {
            continue;
        }
        
        // Handle special cases
        if ($header === 'HTTP_CLOUDFRONT_VIEWER_ADDRESS') {
            // CloudFront format: IP:port
            $parts = explode(':', $raw, 2);
            $candidates = [trim($parts[0])];
        } elseif ($header === 'HTTP_X_FORWARDED_FOR' || $header === 'HTTP_FORWARDED_FOR') {
            $candidates = array_map('trim', explode(',', $raw));
        } elseif ($header === 'HTTP_FORWARDED') {
            // RFC 7239 format: for=192.0.2.43, for="[2001:db8:cafe::17]"
            $candidates = extract_forwarded_ips($raw);
        } else {
            $candidates = [trim($raw)];
        }
        
        foreach ($candidates as $candidate) {
            if (empty($candidate)) {
                continue;
            }
            
            // Remove brackets from IPv6 addresses
            $candidate = trim($candidate, '[]');
            
            // Prefer public IPs (not private/reserved)
            if (filter_var($candidate, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $candidate;
            }
            
            // Fallback to any valid IP
            if ($fallback === '' && filter_var($candidate, FILTER_VALIDATE_IP)) {
                $fallback = $candidate;
            }
        }
    }
    
    return $fallback ?: $remote_addr;
}

/**
 * Extract IPs from RFC 7239 Forwarded header
 */
function extract_forwarded_ips($forwarded_header) {
    // RFC 7239: Forwarded: for=192.0.2.43, for="[2001:db8:cafe::17]"
    $ips = [];
    preg_match_all('/for\s*=\s*"?([^",\s]+)"?/', $forwarded_header, $matches);
    foreach ($matches[1] as $match) {
        $ips[] = trim($match, '"');
    }
    return $ips;
}

/**
 * Check if IP is in trusted CIDR ranges
 */
function is_ip_in_trusted_ranges($ip, $ranges) {
    if (empty($ip)) return false;
    
    foreach ($ranges as $range) {
        if (strpos($range, '/') === false) {
            if ($ip === $range) return true;
            continue;
        }
        
        list($subnet, $mask) = explode('/', $range);
        $ip_long = ip2long($ip);
        $subnet_long = ip2long($subnet);
        $mask_long = ~((1 << (32 - (int)$mask)) - 1);
        
        if (($ip_long & $mask_long) == ($subnet_long & $mask_long)) {
            return true;
        }
    }
    return false;
}

// CSRF protection for state-changing endpoints
$csrf_protected_actions = ['track', 'consent', 'config'];

// Rate limiting configuration
$rate_limits = [
    'track' => ['limit' => 100, 'window' => 60],      // 100 req/min per session
    'consent' => ['limit' => 10, 'window' => 300],    // 10 req/min per IP
    'config' => ['limit' => 5, 'window' => 300],      // 5 req/min per IP
    'heartbeat' => ['limit' => 10, 'window' => 60]     // 10 req/min per session
];

/**
 * Verify CSRF token
 */
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token ?? '');
}

/**
 * Check rate limiting with persistent storage
 */
function check_rate_limit($ip, $action, $limit = null, $window = 60) {
    if ($limit === null) {
        $config = $rate_limits[$action] ?? ['limit' => 100, 'window' => 60];
        $limit = $config['limit'];
        $window = $config['window'];
    }
    
    $key = "rate_limit_{$ip}_{$action}";
    
    // Initialize if not set
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'start' => time(), 'blocked' => false];
    }
    
    $current = $_SESSION[$key];
    
    // Reset window if expired
    if (time() - $current['start'] > $window) {
        $_SESSION[$key] = ['count' => 0, 'start' => time(), 'blocked' => $current['blocked']];
    }
    
    // Check if currently blocked
    if ($current['blocked']) {
        return false;
    }
    
    // Check limit
    if ($current['count'] >= $limit) {
        return false;
    }
    
    $_SESSION[$key]['count']++;
    return true;
}

/**
 * Get database connection with proper error handling
 */
function get_db_connection() {
    static $conn = null;
    if ($conn === null) {
        try {
            $conn = DatabaseFactory::getConnection();
        } catch (Exception $e) {
            error_log("Database connection failed: " . $e->getMessage());
            return null;
        }
    }
    return $conn;
}

/**
 * Execute SQL with proper error handling
 */
function execute($sql, $params = []) {
    $conn = get_db_connection();
    if (!$conn) {
        return false;
    }
    
    try {
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("SQL prepare failed: " . $conn->error);
            return false;
        }
        
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        
        if (!$stmt->execute()) {
            error_log("SQL execute failed: " . $stmt->error);
            return false;
        }
        
        return true;
    } catch (Exception $e) {
        error_log("SQL execution error: " . $e->getMessage());
        return false;
    }
}

/**
 * Query database with proper error handling
 */
function query($sql, $params = []) {
    $conn = get_db_connection();
    if (!$conn) {
        return [];
    }
    
    try {
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("SQL prepare failed: " . $conn->error);
            return [];
        }
        
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        
        if (!$stmt->execute()) {
            error_log("SQL execute failed: " . $stmt->error);
            return [];
        }
        
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        error_log("SQL query error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get current UTC timestamp in YYYYMMDDHHIISS format
 */
function get_current_utc() {
    return gmdate('YmdHis');
}

/**
 * Replace table prefix in SQL
 */
function replace_prefix($sql) {
    return str_replace('{{prefix}}', LUPO_TABLE_PREFIX, $sql);
}

// Get and validate action
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'csrf_token':
        // Generate CSRF token for JavaScript
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        echo json_encode(['csrf_token' => $_SESSION['csrf_token']]);
        break;
        
    case 'track':
        // CSRF protection
        if (in_array($action, $csrf_protected_actions)) {
            $csrf_token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
            if (!verify_csrf_token($csrf_token)) {
                http_response_code(403);
                echo json_encode(['error' => 'Invalid CSRF token']);
                exit;
            }
        }
        
        // Rate limiting
        if (!check_rate_limit(get_client_ip(), $action)) {
            http_response_code(429);
            echo json_encode(['error' => 'Rate limit exceeded', 'retry_after' => $rate_limits[$action]['window'] ?? 60]);
            exit;
        }
        
        // Get or create session
        $session_id = $_COOKIE['lupo_session'] ?? null;
        if (!$session_id) {
            require_once(LUPOPEDIA_PATH . '/lupo-includes/classes/SessionService.php');
            $session_id = SessionService::createSession();
            setcookie('lupo_session', $session_id, [
                'expires' => time() + 86400 * 30, // 30 days
                'path' => LUPOPEDIA_SUBDIRECTORY,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }
        
        // Validate and sanitize input
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = [];
        }
        
        $page_url = filter_var($input['page_url'] ?? $_SERVER['HTTP_REFERER'] ?? '', FILTER_SANITIZE_URL);
        $actor_id = isset($input['actor_id']) ? intval($input['actor_id']) : null;
        $referrer = filter_var($input['referrer'] ?? '', FILTER_SANITIZE_URL);
        
        // Store in lupo_visits
        $visit_id = IdGenerator::generate();
        $sql = replace_prefix("INSERT INTO {{prefix}}visits (visit_id, session_id, actor_id, path_url, referer, created_ymdhis) VALUES (?, ?, ?, ?, ?, ?)");
        
        if (!execute($sql, [$visit_id, $session_id, $actor_id, $page_url, $referrer, get_current_utc()])) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to track visit']);
            exit;
        }
        
        echo json_encode(['success' => true, 'tracked' => 1, 'visit_id' => $visit_id]);
        break;
        
    case 'context':
        // Input validation
        $page_id = isset($_GET['page_id']) ? intval($_GET['page_id']) : null;
        if (!$page_id || $page_id <= 0) {
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
        echo json_encode(['error' => 'Unknown action: ' . htmlspecialchars($action)]);
}
