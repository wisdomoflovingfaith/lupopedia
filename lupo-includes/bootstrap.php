<?php
/**
 * Bootstrap file for loading the lupopedia . sets the constants and connection to the database 
 *
 * @package lupopedia
 */
 
 
// is config loaded
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
   print "LUPOPEDIA_CONFIG_LOADED is not defined this file is loaded after the config is loaded it can not be called out of order ";
    exit;
}

// Load version information
$version_path = __DIR__ . DIRECTORY_SEPARATOR . 'version.php';
if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
    echo "<!-- DEBUG: Trying to load version from: " . htmlspecialchars($version_path) . " -->\n";
    echo "<!-- DEBUG: Current working directory: " . htmlspecialchars(getcwd()) . " -->\n";
    echo "<!-- DEBUG: DIRECTORY_SEPARATOR: " . htmlspecialchars(DIRECTORY_SEPARATOR) . " -->\n";
}
require_once($version_path);

/*
 * The error_reporting() function can be disabled in php.ini it is wrapped in a function_exists() check.
 */
if ( function_exists( 'error_reporting' ) ) {
	/*
	 * Initialize error reporting to a known set of levels.
	 *
	 * This will be adapted in wp_debug_mode() located in wp-includes/load.php based on WP_DEBUG.
	 * @see https://www.php.net/manual/en/errorfunc.constants.php List of known error levels.
	 */
    if(LUPOPEDIA_DEBUG){
	// Enable all error reporting and display for debugging
	// Remove @ to ensure errors are actually set (not suppressed)
	// Note: E_STRICT is deprecated in PHP 8+, so we use E_ALL only
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	ini_set('log_errors', '0'); // Don't log, just display
	ini_set('html_errors', '1'); // Format errors as HTML
    } else {
	// Production: show all errors except deprecated and strict (E_STRICT deprecated in PHP 8+)
	error_reporting( E_ALL & ~E_DEPRECATED );
	ini_set('display_errors', '0');
	ini_set('log_errors', '1'); // Log errors in production
    }
}
 


 // Include the database class
    require_once(__DIR__ . DIRECTORY_SEPARATOR . 'class-pdo_db.php');
    
    try {
        // PDO Database Connection
        $dsn = '';
        
        // Set up DSN based on database typeutf8mb4
        switch (DB_TYPE) {
            case 'mysql':
                $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
                if (!empty(DB_PORT)) {
                    $dsn .= ";port=" . DB_PORT;
                }
                // PDO connection options for MySQL
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_STRINGIFY_FETCHES => false
                ];
                // Only add MySQL-specific constants if driver is available
                if (defined('PDO::MYSQL_ATTR_INIT_COMMAND')) {
                    $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8mb4";
                }
                break;
                
            case 'pgsql':
                $dsn = "pgsql:host=".DB_HOST."dbname=".DB_NAME;
                if (!empty(DB_PORT)) {
                    $dsn .= ";port=" . DB_PORT;
                }
                // PDO connection options for PostgreSQL
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_STRINGIFY_FETCHES => false
                ];
                break;
                
            default:
                throw new Exception("Unsupported database type: " . DB_TYPE . " Must be 'mysql' or 'pgsql'");
        }
        
        // Create PDO instance
        $mydatabase = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
        
        // Set timezone to UTC
        if (DB_TYPE === 'mysql') {
            $mydatabase->exec("SET time_zone = '+00:00'");
        } else {
            $mydatabase->exec("SET timezone = 'UTC'");
        }
        
        // Set the database connection in the global scope
        $GLOBALS['mydatabase'] = $mydatabase;
        
        // Verify the connection works with a simple query
        $test = $mydatabase->query("SELECT 1");
        
    } catch (Exception $e) {
        // Log the detailed error
        error_log('Database connection error: ' . $e->getMessage());
        
        // Show a user-friendly error message with more details
        $errorMsg = 'Database connection error: ' . $e->getMessage();
        if (strpos($e->getMessage(), 'Access denied') !== false) {
            $errorMsg .= "\n\nPlease check your database username and password in config.php";
        } elseif (strpos($e->getMessage(), 'Unknown database') !== false) {
            $errorMsg .= "\n\nThe database '{$database}' does not exist. Please create it first.";
        } elseif (strpos($e->getMessage(), 'Connection refused') !== false) {
            $errorMsg .= "\n\nCould not connect to the database server. Please check if MySQL is running.";
        }
        
        if (!headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error');
        }
        die(nl2br(htmlspecialchars($errorMsg)));
    }
 
// Security Headers
if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    
    // Set secure session cookie parameters
    $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'] ?? '',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

// Timezone
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set('UTC');
}

/**
 * ---------------------------------------------------------
 * Load Session and Auth Helpers (Early Initialization)
 * ---------------------------------------------------------
 * Load authentication helpers before module loader to ensure
 * sessions are initialized and current_user() is available globally.
 */
$session_helpers = __DIR__ . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'session-helpers.php';
if (file_exists($session_helpers)) {
    require_once $session_helpers;
}

$auth_helpers = __DIR__ . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'auth-helpers.php';
if (file_exists($auth_helpers)) {
    require_once $auth_helpers;
}

// Start session early in request lifecycle
if (function_exists('lupo_start_session')) {
    lupo_start_session();
    
    // Debug logging for session start
    if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
        $session_id = session_id();
        error_log("SESSION: Session started - ID: " . substr($session_id, 0, 8) . "...");
    }
    
    // Validate and refresh session activity if user is logged in
    if (function_exists('lupo_validate_session')) {
        $actor_id = lupo_validate_session();
        if ($actor_id) {
            // Session is valid, activity timestamp updated by validate_session()
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log("SESSION: Session validated - Actor ID: " . $actor_id);
            }
        } else {
            // Session invalid or expired
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log("SESSION: Session invalid or expired");
            }
        }
    }
}

require_once ABSPATH . LUPO_INCLUDES_DIR . '/lupopedia-loader.php';
?>