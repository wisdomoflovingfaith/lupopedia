<?php
//===========================================================================
//* --                LUPOPEDIA Configuration File                -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// ----------------------------------------------------------------------------

// Define critical path constants FIRST before any includes
define('LUPOPEDIA_PATH', dirname(__FILE__) . '/');
define('LUPOPEDIA_ABSPATH', str_replace('\\', '/', dirname(__FILE__)));

// Define include directory
define('LUPO_INCLUDES_DIR', LUPOPEDIA_ABSPATH . 'lupo-includes/');

// Define public path for web access
if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    $script_name = basename($_SERVER['SCRIPT_NAME'], '.php');
    $script_dir = dirname($_SERVER['SCRIPT_NAME']);
    
    // Calculate path from web root to this file
    $web_path_to_config = str_replace('\\', '/', str_replace(dirname($_SERVER['SCRIPT_FILENAME']), '', $script_dir));
    
    // Remove common subdirectory patterns
    $patterns = ['/lupo-includes/', '/lupo-tests/', '/lupo-admin/', '/database/', '/docs/', '/scripts/', '/tools/', '/legacy/', '/channels/', '/uploads/'];
    $clean_path = $web_path_to_config;
    
    foreach ($patterns as $pattern) {
        if (strpos($clean_path, $pattern) !== false) {
            $clean_path = substr($clean_path, 0, strpos($clean_path, $pattern) + strlen($pattern));
        }
    }
    
    define('LUPOPEDIA_PUBLIC_PATH', '/' . trim($clean_path, '/') . '/');
}

// Database configuration
if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

// Debug mode
if (!defined('LUPOPEDIA_DEBUG')) {
    define('LUPOPEDIA_DEBUG', false);
}

// Version information
if (!defined('LUPOPEDIA_VERSION')) {
    define('LUPOPEDIA_VERSION', '4.0.47');
}

// Mark config as loaded
define('LUPOPEDIA_CONFIG_LOADED', true);

//===========================================================================
// Database Configuration
//===========================================================================

// Default database settings (can be overridden by config)
$GLOBALS['lupo_db_config'] = array(
    'host' => 'localhost',
    'database' => 'lupopedia',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'prefix' => LUPO_TABLE_PREFIX
);

//===========================================================================
// Security Configuration
//===========================================================================

// Security settings
$GLOBALS['lupo_security_config'] = array(
    'session_timeout' => 3600, // 1 hour in seconds
    'max_login_attempts' => 5,
    'password_min_length' => 8,
    'require_https' => false, // Set to true in production
    'csrf_token_expiry' => 3600,
    'allowed_origins' => array(
        'localhost',
        '127.0.0.1',
        '::1'
    )
);

//===========================================================================
// Application Configuration
//===========================================================================

// Application settings
$GLOBALS['lupo_app_config'] = array(
    'default_timezone' => 'UTC',
    'date_format' => 'Y-m-d H:i:s',
    'max_upload_size' => 10485760, // 10MB in bytes
    'allowed_extensions' => array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt', 'md'),
    'cache_enabled' => true,
    'cache_duration' => 3600 // 1 hour
);

//===========================================================================
// Channel Configuration
//===========================================================================

// Default channel settings
$GLOBALS['lupo_channel_config'] = array(
    'default_channel_id' => 1,
    'max_message_length' => 1000,
    'message_refresh_interval' => 3000, // 3 seconds
    'max_users_per_channel' => 100,
    'typing_indicator_timeout' => 5000 // 5 seconds
);

//===========================================================================
// Actor Configuration
//===========================================================================

// Actor system settings
$GLOBALS['lupo_actor_config'] = array(
    'human_id_start' => 10000,
    'system_id_end' => 9999,
    'default_avatar' => 'images/default-avatar.png',
    'guest_name_prefix' => 'Visitor_',
    'session_timeout' => 1800 // 30 minutes
);

?>
