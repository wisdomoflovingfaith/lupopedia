<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "lupopedia-config-sample.php"
#   web_path: "https://www.lupopedia.com/lupopedia/lupopedia-config-sample.php"
#   status: "active"
#   when_updated: "20260417072645"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/lupopedia-config-sample-php.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/lupopedia-config-sample-php"
#   artifact_type: "configuration"
#   artifact_kind: "template"
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   parent_pk_id: ""
#   lupopedia.schema: "implementation"
#   title: "lupopedia-config-sample.php — shared-host config template"
#   summary: "Shared-host configuration template used by installers with DB placeholders, salt placeholders, session policy hints, and runtime provider-chain defaults."
# ---------------------------------------------------------------------
/**
 * Lupopedia configuration sample — copy to lupopedia-config.php and edit,
 * or let Softaculous / Installatron replace the [[softdb*]] placeholders.
 *
 * @package Lupopedia
 */
/*
 * SECURITY NOTICE:
 * This file contains database credentials and API keys. It is placed inside the web-accessible directory
 * but MUST be protected: chmod 0600 + .htaccess rule denying direct access.
 * Filesystem rule: All Lupopedia files (lupo-memory/, lupo-channels/, app/, etc.) live ABOVE the web root.
 * ONLY this config file lives inside the web directory.
 * memory_path and channels_path MUST resolve ABOVE web root (use dirname(__DIR__) or LUPOPEDIA_PATH).
 */

// Autoinstaller note: The wizard populates provider keys/budgets and generates strong salts during install.
// Keep placeholders here so Softaculous/Installatron and manual setup flows remain compatible.
$lupo_prefix = '';
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
if (!defined('LUPO_SESSION_SAMESITE')) {
    define('LUPO_SESSION_SAMESITE', 'Lax');
}
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}
define('LUPO_PREFIX', $lupo_prefix);
define('LUPO_ADMIN_DIR', 'admin');
define('LUPO_INCLUDES_DIR', 'includes');
define('LUPO_CONTENT_DIR', 'content');
define('LUPO_ACTORS_DIR', 'actors');
define('LUPO_CHANNELS_DIR', 'channels');
define('LUPO_PROMPTS_SUBDIR', 'prompts');
define('LUPO_UPLOADS_DIR', LUPO_CONTENT_DIR . '/uploads');
define('LUPO_PLUGINS_DIR', LUPO_CONTENT_DIR . '/plugins');
define('LUPO_THEMES_DIR', LUPO_CONTENT_DIR . '/themes');
if (!defined('LUPO_DATABASE_DIR')) {
    define('LUPO_DATABASE_DIR', 'database');
}
// Legacy fallback path; modern runtime prefers app/ when available.
define('LUPO_APP_DIR', 'database/lupopedia/content/app');
$table_prefix = 'lupo_';
if (!preg_match('/^[a-z0-9_]+$/', $table_prefix)) {
    die('Invalid table prefix');
}
define('LUPO_TABLE_PREFIX', $table_prefix);
if (!defined('LUPOPEDIA_ABSPATH')) {
    define('LUPOPEDIA_ABSPATH', ABSPATH);
}
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', rtrim(ABSPATH, '/'));
}
if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(dirname(__FILE__)));
}
if (!defined('LUPOPEDIA_URL')) {
    define('LUPOPEDIA_URL', LUPOPEDIA_PUBLIC_PATH);
}
define('LUPOPEDIA_CONFIG_LOADED', true);

// Optional runtime provider chain configuration (BYOK per install).
// Installer writes this block automatically when API keys are entered.
$GLOBALS['LUPO_API_PROVIDER_CONFIG'] = array(
    'provider_order' => array('gemini', 'deepseek', 'groq'),
    'request_class_order' => array(
        'default' => array('gemini', 'deepseek', 'groq'),
        'complex' => array('deepseek', 'anthropic', 'gemini', 'openai'),
        'audit' => array('deepseek', 'gemini', 'anthropic', 'openai'),
    ),
    'fallback_order' => array('gemini', 'deepseek', 'groq'),
    'monthly_budget_cap_usd' => 45.0,
    'premium_provider_block_threshold_usd' => 40.0,
    'premium_providers' => array('openai', 'anthropic'),
    'config_version' => '2026.04',
    'memory_path' => dirname(__DIR__) . '/memory/',
    'channels_path' => dirname(__DIR__) . '/channels/',
    'providers' => array(
        'gemini' => array('enabled' => true, 'key' => '', 'api_key' => '', 'budget' => 15.0, 'name' => 'Google Gemini'),
        'deepseek' => array('enabled' => true, 'key' => '', 'api_key' => '', 'budget' => 15.0, 'name' => 'DeepSeek'),
        'groq' => array('enabled' => true, 'key' => '', 'api_key' => '', 'budget' => 15.0, 'name' => 'Groq'),
        'anthropic' => array('enabled' => false, 'key' => '', 'api_key' => '', 'budget' => 15.0, 'name' => 'Anthropic'),
        'grok' => array('enabled' => false, 'key' => '', 'api_key' => '', 'budget' => 15.0, 'name' => 'Grok xAI'),
        'openai' => array('enabled' => false, 'key' => '', 'api_key' => '', 'budget' => 15.0, 'name' => 'OpenAI'),
    ),
);
$GLOBALS['LUPO_API_PROVIDER_CONFIG_SOURCE'] = 'config_sample';
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

// API Keys for External LLM Services
// WARNING: Never echo, log, or expose these values in responses
$lupopedia_api_keys = [
    'chatgpt'      => '',
    'deepseek'     => '',
    'grok'         => '',
    'gemini'       => '',
    'copilot_vscode' => '',
];
// Federation shared secret for peer-to-peer node authentication.
// Each installation MUST generate its own secret.
// Never expose or log this value.
define('LUPO_FEDERATION_SHARED_SECRET', '');

// Optional: shared secret for POST api/transcript/append when no session cookie (automation).
// define('LUPO_TRANSCRIPT_API_TOKEN', 'generate-a-long-random-string');

require_once ABSPATH . LUPO_INCLUDES_DIR . '/bootstrap.php';
