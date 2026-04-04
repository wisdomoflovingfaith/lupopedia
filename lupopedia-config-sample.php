<?php
/**
 * Lupopedia configuration sample — copy to lupopedia-config.php and edit,
 * or let Softaculous / Installatron replace the [[softdb*]] placeholders.
 *
 * @package Lupopedia
 */
$lupo_prefix = 'lupo-';
define('DB_TYPE', 'mysql');
define('DB_NAME', '[[softdb]]');
define('DB_USER', '[[softdbuser]]');
define('DB_PASSWORD', '[[softdbpass]]');
define('DB_HOST', '[[softdbhost]]');
define('DB_PORT', '3306');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', 'utf8mb4_unicode_ci');
define('AUTH_KEY', 'put your unique phrase here');
define('SECURE_AUTH_KEY', 'put your unique phrase here');
define('LOGGED_IN_KEY', 'put your unique phrase here');
define('NONCE_KEY', 'put your unique phrase here');
define('AUTH_SALT', 'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT', 'put your unique phrase here');
define('NONCE_SALT', 'put your unique phrase here');
define('LUPOPEDIA_DEBUG', false);
define('LUPOPEDIA_ENV', 'production');
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}
define('LUPO_PREFIX', $lupo_prefix);
define('LUPO_ADMIN_DIR', LUPO_PREFIX . 'admin');
define('LUPO_INCLUDES_DIR', LUPO_PREFIX . 'includes');
define('LUPO_CONTENT_DIR', LUPO_PREFIX . 'content');
define('LUPO_ACTORS_DIR', LUPO_PREFIX . 'actors');
define('LUPO_CHANNELS_DIR', LUPO_PREFIX . 'channels');
define('LUPO_PROMPTS_SUBDIR', 'prompts');
define('LUPO_UPLOADS_DIR', LUPO_CONTENT_DIR . '/uploads');
define('LUPO_PLUGINS_DIR', LUPO_CONTENT_DIR . '/plugins');
define('LUPO_THEMES_DIR', LUPO_CONTENT_DIR . '/themes');
if (!defined('LUPO_DATABASE_DIR')) {
    define('LUPO_DATABASE_DIR', LUPO_PREFIX . 'database');
}
define('LUPO_APP_DIR', 'lupo-database/lupopedia/content/lupo-app');
$table_prefix = 'lupo_';
if (!preg_match('/^[a-z0-9_]+$/', $table_prefix)) {
    die('Invalid table prefix');
}
define('LUPO_TABLE_PREFIX', $table_prefix);
if (!defined('LUPOPEDIA_ABSPATH')) {
    define('LUPOPEDIA_ABSPATH', ABSPATH);
}
if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(dirname(__FILE__)));
}
if (!defined('LUPOPEDIA_URL')) {
    define('LUPOPEDIA_URL', LUPOPEDIA_PUBLIC_PATH);
}
define('LUPOPEDIA_CONFIG_LOADED', true);
define('LUPOPEDIA_SITE_NAME', 'Lupopedia');
if (!defined('LUPOPEDIA_BASE_URL')) {
    define('LUPOPEDIA_BASE_URL', rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/');
}
define('LUPOPEDIA_ADMIN_EMAIL', 'admin@example.com');
define('LUPOPEDIA_TIMEZONE', 'UTC');
define('LUPOPEDIA_LANGUAGE', 'en');
define('LUPOPEDIA_SUPPORT_EMAIL', 'admin@example.com');
define('LUPOPEDIA_DEFAULT_VISITOR_CHANNEL', '1');
define('LUPOPEDIA_ENABLE_AI_CHANNELS', true);

require_once ABSPATH . LUPO_INCLUDES_DIR . '/bootstrap.php';
