<?php
/**
 * Bootstrap file for loading the lupopedia . sets the constants and connection to the database 
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
if (function_exists('error_reporting')) {
    /*
     * Initialize error reporting to a known set of levels.
     *
     * This will be adapted in wp_debug_mode() located in wp-includes/load.php based on WP_DEBUG.
     * @see https://www.php.net/manual/en/errorfunc.constants.php List of known error levels.
     */
    if (LUPOPEDIA_DEBUG) {
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
        error_reporting(E_ALL & ~E_DEPRECATED);
        ini_set('display_errors', '0');
        ini_set('log_errors', '1'); // Log errors in production
    }
}



// Include the database factory and PDO_DB wrapper (Doctrine: all DB access via DatabaseFactory + PDO_DB)
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'class-pdo_db.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'class-DatabaseFactory.php');

try {
    $mydatabase = DatabaseFactory::getConnection();
    $GLOBALS['mydatabase'] = $mydatabase;
    $mydatabase->fetchRow('SELECT 1');
} catch (Exception $e) {
    // Log the detailed error
    error_log('Database connection error: ' . $e->getMessage());

    // Show a user-friendly error message with more details
    $errorMsg = 'Database connection error: ' . $e->getMessage();
    if (strpos($e->getMessage(), 'Access denied') !== false) {
        $errorMsg .= "\n\nPlease check your database username and password in lupopedia-config.php";
    } elseif (strpos($e->getMessage(), 'Unknown database') !== false) {
        $errorMsg .= "\n\nThe database '" . (defined('DB_NAME') ? DB_NAME : '') . "' does not exist. Please create it first.";
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

    // Set secure session cookie parameters only before session is started (PHP 5.3: 5-arg form; no samesite)
    $session_not_started = function_exists('session_status') ? (session_status() === PHP_SESSION_NONE) : (session_id() === '');
    if ($session_not_started) {
        $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
        $domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        session_set_cookie_params(0, '/', $domain, $secure, true);
    }
}

// Timezone
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set('UTC');
}

/**
 * ---------------------------------------------------------
 * Session (OOP) and Auth Helpers (Early Initialization)
 * ---------------------------------------------------------
 * Session class replaces procedural session helpers. One instance per request.
 */
$app_auth = LUPOPEDIA_ABSPATH . LUPO_APP_DIR . DIRECTORY_SEPARATOR . 'auth';
if (file_exists($app_auth . DIRECTORY_SEPARATOR . 'SessionHandler.php')) {
    require_once $app_auth . DIRECTORY_SEPARATOR . 'SessionHandler.php';
}
if (file_exists($app_auth . DIRECTORY_SEPARATOR . 'Session.php')) {
    require_once $app_auth . DIRECTORY_SEPARATOR . 'Session.php';
}
if (file_exists($app_auth . DIRECTORY_SEPARATOR . 'AuthRoleResolver.php')) {
    require_once $app_auth . DIRECTORY_SEPARATOR . 'AuthRoleResolver.php';
}
if (file_exists($app_auth . DIRECTORY_SEPARATOR . 'AuthService.php')) {
    require_once $app_auth . DIRECTORY_SEPARATOR . 'AuthService.php';
}

$auth_helpers = __DIR__ . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'auth-helpers.php';
if (file_exists($auth_helpers)) {
    require_once $auth_helpers;
}

$reserved_id_helpers = __DIR__ . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'reserved-id-helpers.php';
if (file_exists($reserved_id_helpers)) {
    require_once $reserved_id_helpers;
}

$session_manager_class = __DIR__ . DIRECTORY_SEPARATOR . 'class-SessionManager.php';
if (file_exists($session_manager_class)) {
    require_once $session_manager_class;
}

$session_handler_class = LUPOPEDIA_ABSPATH . LUPO_APP_DIR . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'UnifiedSessionHandler.php';
if (file_exists($session_handler_class)) {
    require_once $session_handler_class;
}

// Create Session instance; handler delegates all DB to Session (thin wrapper: cookie + system_context only)
$lupo_session = null;
if (class_exists('App\Auth\Session') && isset($GLOBALS['mydatabase'])) {
    $handler = new \App\Auth\SessionHandler($GLOBALS['mydatabase']);
    $lupo_session = new \App\Auth\Session($GLOBALS['mydatabase'], $handler);
    $handler->setSession($lupo_session);
    $GLOBALS['lupo_session'] = $lupo_session;

    // Auth domain: AuthRoleResolver + AuthService (replaces procedural auth helpers)
    if (class_exists('App\Auth\AuthRoleResolver') && class_exists('App\Auth\AuthService')) {
        $lupo_auth_role_resolver = new \App\Auth\AuthRoleResolver($GLOBALS['mydatabase']);
        $GLOBALS['lupo_auth_service'] = new \App\Auth\AuthService($lupo_session, $GLOBALS['mydatabase'], $lupo_auth_role_resolver);
    }
}

// Start session and run idle check then validate
if ($lupo_session !== null) {
    $lupo_session->start();
    // Default collection_id to 0 so saved-collections-container has an active collection on first load
    if (!isset($_SESSION['collection_id']) || $_SESSION['collection_id'] === '') {
        $_SESSION['collection_id'] = 0;
    }
    if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
        error_log("SESSION: Session started - ID: " . substr($lupo_session->getSessionId(), 0, 8) . "...");
    }
    try {
        $sessionManager = new SessionManager($lupo_session);
        $sessionManager->tick();
        $actor_id = $lupo_session->validateSession();
        if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
            if ($actor_id) {
                error_log("SESSION: Session validated - Actor ID: " . $actor_id);
            } else {
                error_log("SESSION: Session invalid or expired");
            }
        }
    } catch (\PDOException $e) {
        $msg = $e->getMessage();
        if (strpos($msg, "doesn't exist") !== false) {
            if (!headers_sent() && php_sapi_name() !== 'cli') {
                $install_path = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/install.php' : '/install.php';
                header('Location: ' . $install_path);
                exit;
            }
        }
        throw $e;
    } catch (Exception $e) {
        throw $e;
    }
}

// Actor domain: ActorService (replaces procedural actor/identity helpers)
$app_services = LUPOPEDIA_ABSPATH . LUPO_APP_DIR . DIRECTORY_SEPARATOR . 'Services';
$app_support = LUPOPEDIA_ABSPATH . LUPO_APP_DIR . DIRECTORY_SEPARATOR . 'Support';
if (isset($GLOBALS['mydatabase'])) {
    if (file_exists($app_services . DIRECTORY_SEPARATOR . 'ActorService.php')) {
        require_once $app_services . DIRECTORY_SEPARATOR . 'ActorService.php';
        if (class_exists('App\Services\ActorService')) {
            $GLOBALS['lupo_actor_service'] = new \App\Services\ActorService($GLOBALS['mydatabase']);
        }
    }
    if (file_exists($app_services . DIRECTORY_SEPARATOR . 'CollectionZeroService.php')) {
        require_once $app_services . DIRECTORY_SEPARATOR . 'CollectionZeroService.php';
        if (class_exists('App\Services\CollectionZeroService')) {
            $GLOBALS['lupo_collection_zero_service'] = new \App\Services\CollectionZeroService($GLOBALS['mydatabase']);
        }
    }
    if (file_exists($app_services . DIRECTORY_SEPARATOR . 'CollectionTabsService.php')) {
        require_once $app_services . DIRECTORY_SEPARATOR . 'CollectionTabsService.php';
        if (class_exists('App\Services\CollectionTabsService')) {
            $GLOBALS['lupo_collection_tabs_service'] = new \App\Services\CollectionTabsService($GLOBALS['mydatabase']);
        }
    }
    if (file_exists($app_services . DIRECTORY_SEPARATOR . 'SavedCollectionsService.php')) {
        require_once $app_services . DIRECTORY_SEPARATOR . 'SavedCollectionsService.php';
        if (class_exists('App\Services\SavedCollectionsService')) {
            $GLOBALS['lupo_saved_collections_service'] = new \App\Services\SavedCollectionsService($GLOBALS['mydatabase']);
        }
    }
    if (file_exists($app_services . DIRECTORY_SEPARATOR . 'UploadService.php')) {
        require_once $app_services . DIRECTORY_SEPARATOR . 'UploadService.php';
        if (class_exists('App\Services\UploadService')) {
            $GLOBALS['lupo_upload_service'] = new \App\Services\UploadService();
        }
    }
}
if (file_exists($app_support . DIRECTORY_SEPARATOR . 'AtomLoader.php')) {
    require_once $app_support . DIRECTORY_SEPARATOR . 'AtomLoader.php';
    require_once $app_support . DIRECTORY_SEPARATOR . 'VersionUtils.php';
    if (class_exists('App\Support\AtomLoader')) {
        $GLOBALS['lupo_atom_loader'] = new \App\Support\AtomLoader();
    }
}
if (file_exists($app_support . DIRECTORY_SEPARATOR . 'RedirectUtils.php')) {
    require_once $app_support . DIRECTORY_SEPARATOR . 'RedirectUtils.php';
}
if (file_exists($app_support . DIRECTORY_SEPARATOR . 'LimitsLogger.php')) {
    require_once $app_support . DIRECTORY_SEPARATOR . 'LimitsLogger.php';
}

require_once ABSPATH . LUPO_INCLUDES_DIR . '/lupopedia-loader.php';
?>