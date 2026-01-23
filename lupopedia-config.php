<?php
/**
 * Lupopedia Configuration File
 * 
 * This file contains the base configuration for Lupopedia.
 * 
 * @package Lupopedia
 */

// ** Directory and URL settings ** //
// NOTE: Directory prefix can use hyphens (for filesystem paths)
// Example: 'lupo-' creates directories like 'lupo-includes/', 'lupo-admin/'
$lupo_prefix = 'lupo-';

// ** Database settings ** //
define('DB_TYPE', 'mysql');
define('DB_NAME', 'lupopedia');
define('DB_USER', 'root');
define('DB_PORT', '3306');
define('DB_PASSWORD', 'ServBay.dev');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', 'utf8mb4_unicode_ci');

// ** Authentication unique keys and salts ** //
// You can generate these at: https://api.wordpress.org/secret-key/1.1/salt/
define('AUTH_KEY',         '*pN7L9Dx7]iA<$EVR@5kg>d`sCjtD2r{QO}[>0]5T-t67<SPZUxnvre-Ojk<Aya?');
define('SECURE_AUTH_KEY',  '<8q:bXn/{q59cY6P`|s?CHV[oG7iR(GP.AEu&<PjJ:>:XVAZ)]A{(l{yur!5MFF[');
define('LOGGED_IN_KEY',    '-MA>+MSiof|1+Cht=^6!!>-m<$*I7C-AnK,o26^uYbJHK4^V5p|/_mmEJ0$fB1r!');
define('NONCE_KEY',        '4l6%K%605-ZncU!+FDFK|//SifB:,-t3{caVR1+9ym`SSzS!Cer|Al=m5t/(+%/E');
define('AUTH_SALT',        '7x{*0lRwzw8>w8g3 [||b]Wss:P)BXkXBtJ}AZ05]R7<g$iX-kYO$^VhmSjzIL*Y');
define('SECURE_AUTH_SALT', 'n,3/Z#1UJh Sh5Z7v&v~LI5+H:3 $KU{.y]:,dw3N-ACq,d6`#M1*3.DJA%Q=%w|');
define('LOGGED_IN_SALT',   '6=vAOu}.PD?-~?Z<Yp6_BDb2Z;`Y%NLuRaHw#@ur;4:Kd-LpIZc*.)5Ju1O#(0$.');
define('NONCE_SALT',       '}d3Ut1)x[]GB*Z5Qi{++ixe(i(-h%a^q+,O:DA}2d,wB7wDP<m>q%~gCU)Mi:y0(');

// ** Application settings ** //
define('LUPOPEDIA_DEBUG', true);
define('LUPOPEDIA_ENV', 'development'); // 'development', 'staging', or 'production'

// ** LLM Provider Configuration ** //
// Set your API key here or via environment variable
define('OPENAI_API_KEY', getenv('OPENAI_API_KEY') ?: '');
define('DEEPSEEK_API_KEY', getenv('DEEPSEEK_API_KEY') ?: '');
define('LLM_PROVIDER', getenv('LLM_PROVIDER') ?: 'openai'); // 'openai', 'deepseek', 'anthropic'
define('LLM_MODEL', getenv('LLM_MODEL') ?: 'gpt-4o-mini'); // Default model

// ** Define directory constants ** //
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

// ** Core directories ** //
define('LUPO_PREFIX', $lupo_prefix);
define('LUPO_ADMIN_DIR', LUPO_PREFIX . 'admin');
define('LUPO_INCLUDES_DIR', LUPO_PREFIX . 'includes');
define('LUPO_CONTENT_DIR', LUPO_PREFIX . 'content');

// ** Content directories ** //
define('LUPO_UPLOADS_DIR', LUPO_CONTENT_DIR . '/uploads');
define('LUPO_PLUGINS_DIR', LUPO_CONTENT_DIR . '/plugins');
define('LUPO_THEMES_DIR', LUPO_CONTENT_DIR . '/themes');

// ** Set up the database table prefix ** //
// CRITICAL: Table prefix MUST contain only lowercase letters, numbers, and underscores
// Hyphens, spaces, uppercase, symbols, unicode, and emoji are FORBIDDEN
// This ensures portability, predictability, SQL-safety, and filesystem-safety
$table_prefix = 'lupo_';

// Validate table prefix (MANDATORY validation rule)
if (!preg_match('/^[a-z0-9_]+$/', $table_prefix)) {
    die("CRITICAL ERROR: Invalid table prefix '{$table_prefix}'. Table prefixes MUST contain only lowercase letters, numbers, and underscores. Hyphens, spaces, uppercase, symbols, unicode, and emoji are FORBIDDEN.");
}

define('LUPO_TABLE_PREFIX', $table_prefix);

// ** Absolute path to the Lupopedia directory ** //
if (!defined('LUPOPEDIA_ABSPATH')) {
    define('LUPOPEDIA_ABSPATH', ABSPATH);
}

// ** Public path (URL path to Lupopedia directory) ** //
if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(dirname(__FILE__)));
}

// ** Public URL (for web access) ** //
if (!defined('LUPOPEDIA_URL')) {
    define('LUPOPEDIA_URL', LUPOPEDIA_PUBLIC_PATH);
}

define('LUPOPEDIA_CONFIG_LOADED', true);
// ** Load the main bootstrap file ** //
require_once ABSPATH . LUPO_INCLUDES_DIR . '/bootstrap.php';

?>