<?php
/*
 * lupopedia.headers:
 *   header_format_version: "4.1.4"
 *   file_path_from_root: "install.php"
 *   web_path: "https://www.lupopedia.com/lupopedia/install.php"
 *   status: "complete"
 *   when_updated: "20260417115311"
 *   trust_tier: "canonical"
 *   questions_toon: null
 *   memory_toon: "lupo-memory/development/canonical/1026/04/install-php.toon"
 *   atoms_toon: null
 *   transcript_jsonl: "0/development/install-php"
 *   artifact_type: implementation
 *   artifact_kind: tool
 *   channel_key: "production"
 *   federation_node_id: 0
 *   thread_id: null
 *   content_id: null
 *   content_parent_id: "42"
 *   default_collection_id: null
 *   lupopedia.schema: implementation
 *   prd_cluster: "00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_16_B_ATOMS_16_C_HEADERS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE"
 *   title: "install.php -- Lupopedia install and upgrade wizard"
 *   summary: "Main installer and upgrade wizard for fresh installs and Crafty Syntax 3.7.5 migration, including API key collection, config generation, and protected web-root config."
 */
/*
 * Filesystem Rule (canonical per AGENTS.md and LupopediaConfigResolver):
 * - lupopedia-config.php MUST be written INSIDE the web-accessible directory and protected (chmod 0600 + .htaccess deny).
 * - All other files (lupo-memory/, lupo-channels/, app/, lupo-includes/, etc.) live ABOVE the web root.
 * - memory_path and channels_path in generated config MUST resolve ABOVE web root.
 */

/**
 * Lupopedia Install / Upgrade Wizard (version from version.php / atoms)
 *
 * Two valid states only: New install | Upgrade from Crafty Syntax 3.7.5.
 * No Lupopedia → Lupopedia upgrade. Installer writes protected config in web root and keeps memory/channels above web root.
 *
 * Pre-flight: PHP 5.3+, pdo_mysql, json required; project root writable. Optional: mbstring, curl, openssl, fileinfo (warn only).
 * Fallback philosophy: degrade gracefully; do not block unless absolutely required. No GD requirement (image.php uses raw output).
 *
 * A. Detect: livehelp_* tables exist → upgrade; else → new install.
 * B. New install: install_new_lupopedia.sql (DDL), then consolidated seed: install/seed_lupopedia_4_1_0.sql if built, else mysql/seed/seed_4.1.0.sql.
 * C. Upgrade: Identity Normalization (required) → validate all emails unique → update livehelp_users
 *    → then install → seed → import → drop → config. Normalization runs before any Lupopedia SQL:
 *    Crafty uses username/password; Lupopedia uses email/password with unique email. Operators get
 *    email stays as email; username becomes slug for operators; non-operators keep valid email or get slug from username. Empty/invalid
 *    emails and duplicates are validated; the wizard blocks until all conflicts are resolved and
 *    livehelp_users is updated only after admin confirms. The importer runs only after normalization.
 * D. Reserved system channels (0, 1, 42, 51): created immediately after detect upgrade (install+seed+reserved),
 *    before identity normalization, before importer SQL, before personal channel/role creation. Constitutional rule.
 * E. Upgrade run step: import → personal channels and captain roles → drop → config (install/seed/reserved already done at credentials).
 * F. New install run step: install → seed → reserved channels → config.
 * G. Upgrade SQL order: create_reserved_system_channels (at credentials) → normalize → import → personal channels/roles → drop.
 * H. Write lupopedia-config.php. Redirect to login.php.
 *
 * @package Lupopedia
 * @see docs/doctrine/migrations/ Installation SQL Rule
 */

/**
 * INSTALLER SQL SOURCE OF TRUTH
 * All installer-critical SQL must reside under:
 *   database/lupopedia/mysql/
 * Do not load SQL from database/migrations/.
 */
/**
 * Installer entry: set so optional checks can detect wizard context (auto-installers ship config without running this file).
 */
if (!defined('LUPO_INSTALLING')) {
    define('LUPO_INSTALLING', true);
}


// Installer runtime base path for canonical SQL/assets in this source tree.
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', __DIR__);
}
// LUPO_DATABASE_DIR: from config if available; only if missing, default to repo-local database/
if (!defined('LUPO_DATABASE_DIR')) {
    define('LUPO_DATABASE_DIR', LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'database');
}
// LUPO_MYSQL_DIR: always derived from LUPO_DATABASE_DIR (no trailing slash; paths built with DIRECTORY_SEPARATOR)
if (!defined('LUPO_MYSQL_DIR')) {
    define('LUPO_MYSQL_DIR', LUPO_DATABASE_DIR . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'mysql');
}
if (!is_dir(LUPO_MYSQL_DIR)) {
    die('MySQL installer directory not found at LUPO_MYSQL_DIR: ' . LUPO_MYSQL_DIR);
}
// Consolidated seed: prefer install/seed_lupopedia_4_1_0.sql (merged; {{prefix}}). Fallback seed may hardcode lupo_.
if (!defined('LUPO_CONSOLIDATED_SEED_FILE')) {
    $lupo_merged_seed = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'seed_lupopedia_4_1_0.sql';
    $lupo_seed_410 = LUPO_MYSQL_DIR . DIRECTORY_SEPARATOR . 'seed' . DIRECTORY_SEPARATOR . 'seed_4.1.0.sql';
    $lupo_consolidated = (is_file($lupo_merged_seed) && is_readable($lupo_merged_seed))
        ? $lupo_merged_seed
        : $lupo_seed_410;
    if ($lupo_consolidated === $lupo_seed_410 && is_file($lupo_seed_410) && is_readable($lupo_seed_410)) {
        error_log(
            'Lupopedia installer: using fallback mysql/seed/seed_4.1.0.sql; ship install/seed_lupopedia_4_1_0.sql in releases for custom table prefix.'
        );
    }
    define('LUPO_CONSOLIDATED_SEED_FILE', $lupo_consolidated);
}

// Version for wizard UI. Canonical source is GLOBAL_CURRENT_LUPOPEDIA_VERSION in atoms.
$lupo_wizard_version = null;
$atoms_candidates = array(
    LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'global_atoms.yaml',
    LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'global_atoms.yaml',
    LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'global_atoms.yaml',
);
$atoms_content = null;
foreach ($atoms_candidates as $atoms_file) {
    if (is_file($atoms_file)) {
        $atoms_content = file_get_contents($atoms_file);
        break;
    }
}
if ($atoms_content !== null && preg_match('/^GLOBAL_CURRENT_LUPOPEDIA_VERSION:\s*["\']?([0-9.]+)["\']?/m', $atoms_content, $matches)) {
    $lupo_wizard_version = $matches[1];
}

// Fallback to version.php only when atoms are unavailable.
$version_php = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'version.php';
if ($lupo_wizard_version === null && is_file($version_php)) {
    require_once $version_php;
}
if ($lupo_wizard_version === null && defined('LUPOPEDIA_VERSION')) {
    $lupo_wizard_version = LUPOPEDIA_VERSION;
}
if ($lupo_wizard_version === null) {
    $lupo_wizard_version = '0.0.0';
}

/**
 * PHP 5.3-safe random bytes. Uses random_bytes() when available (PHP 7+), else openssl_random_pseudo_bytes, else mt_rand fallback.
 */
function lupo_random_bytes($length)
{
    if (function_exists('random_bytes')) {
        return random_bytes($length);
    }
    if (function_exists('openssl_random_pseudo_bytes')) {
        $bytes = openssl_random_pseudo_bytes($length);
        return $bytes !== false ? $bytes : lupo_random_bytes_fallback($length);
    }
    return lupo_random_bytes_fallback($length);
}

function lupo_random_bytes_fallback($length)
{
    $bytes = '';
    for ($i = 0; $i < $length; $i++) {
        $bytes .= chr(mt_rand(0, 255));
    }
    return $bytes;
}

/**
 * PHP 5.3-safe random int in range. Uses random_int() when available (PHP 7+), else mt_rand.
 */
function lupo_random_int($min, $max)
{
    if (function_exists('random_int')) {
        return random_int($min, $max);
    }
    return mt_rand($min, $max);
}

/**
 * PHP 5.3-safe timing-safe string comparison. Uses hash_equals() when available (PHP 5.6+), else constant-time loop.
 */
function lupo_hash_equals($a, $b)
{
    if (function_exists('hash_equals')) {
        return hash_equals($a, $b);
    }
    if (strlen($a) !== strlen($b)) {
        return false;
    }
    $len = strlen($a);
    $result = 0;
    for ($i = 0; $i < $len; $i++) {
        $result |= ord($a[$i]) ^ ord($b[$i]);
    }
    return $result === 0;
}

/**
 * Trim API key input without altering token content.
 *
 * @param mixed $value
 * @return string
 */
function lupo_trim_api_key($value)
{
    return trim((string) $value);
}

/**
 * Parse provider order from comma-separated input.
 * Keeps only known provider keys and preserves first-seen order.
 *
 * @param string $raw
 * @return array
 */
function lupo_parse_provider_order($raw, $allowedProviders = array())
{
    $defaultAllowed = array('gemini', 'deepseek', 'groq', 'anthropic', 'grok', 'openai');
    $allowed = !empty($allowedProviders) ? $allowedProviders : $defaultAllowed;
    $parts = preg_split('/[\s,]+/', strtolower((string) $raw));
    $result = array();
    foreach ($parts as $part) {
        $p = trim($part);
        if ($p === '' || !in_array($p, $allowed, true) || in_array($p, $result, true)) {
            continue;
        }
        $result[] = $p;
    }
    if (empty($result)) {
        $result = array('gemini', 'deepseek', 'groq');
    }
    return $result;
}

/**
 * Read existing provider config from generated lupopedia-config.php without including it.
 * Returns null when no config block is found.
 *
 * @param string $path
 * @return array|null
 */
function lupo_read_existing_api_provider_config($path)
{
    if ($path === null || !is_file($path) || !is_readable($path)) {
        return null;
    }
    $content = @file_get_contents($path);
    if ($content === false) {
        return null;
    }
    if (!preg_match('/LUPO_API_PROVIDER_CONFIG_START(.*?)LUPO_API_PROVIDER_CONFIG_END/s', $content, $blockMatch)) {
        return null;
    }
    $block = $blockMatch[1];
    if (!preg_match('/\$GLOBALS\[[\'"]LUPO_API_PROVIDER_CONFIG[\'"]\]\s*=\s*(array\s*\(.*\));/s', $block, $arrayMatch)) {
        return null;
    }
    $arrayCode = $arrayMatch[1];
    $parsed = @eval('return ' . $arrayCode . ';');
    if (is_array($parsed)) {
        return $parsed;
    }
    return null;
}

require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'install_wizard_classes.php';
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'LupopediaConfigResolver.php';
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'InstallWizardMdImporter.php';
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'InstallWizardAgentLoader.php';
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'InstallWizardLLMDefaults.php';
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'InstallWizardLLMConfigLoader.php';
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'classes/pdo_db.php';

// ----- Pre-flight checks (PHP 5.3+ compatible; minimal and fallback-friendly)
$preflight_blocking = array();
$preflight_warnings = array();
$php_ver = phpversion();
if (!version_compare($php_ver, '5.3.0', '>=')) {
    $preflight_blocking[] = 'PHP 5.3 or higher is required. Current: ' . $php_ver;
}
if (!extension_loaded('pdo_mysql')) {
    $preflight_blocking[] = 'The pdo_mysql extension is required.';
}
if (!extension_loaded('mysqli')) {
    $preflight_blocking[] = 'The mysqli extension is required for the install wizard (WordPress-style DB driver).';
}
if (!extension_loaded('json')) {
    $preflight_blocking[] = 'The json extension is required.';
}
$config_dir = LUPOPEDIA_PATH;
if (!is_writable($config_dir)) {
    $preflight_blocking[] = 'Project root is not writable; the installer cannot write the config file.';
}
if (!extension_loaded('mbstring')) {
    $preflight_warnings[] = 'mbstring is not loaded; ASCII-safe fallbacks will be used.';
}
if (!extension_loaded('curl')) {
    $preflight_warnings[] = 'curl is not loaded (optional).';
}
if (!extension_loaded('openssl')) {
    $preflight_warnings[] = 'openssl is not loaded (optional).';
}
if (!extension_loaded('fileinfo')) {
    $preflight_warnings[] = 'fileinfo is not loaded (optional).';
}

if (!empty($preflight_blocking)) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Lupopedia — Pre-flight check failed</title></head><body>';
    echo '<h1>Lupopedia ' . htmlspecialchars($lupo_wizard_version) . ' — Pre-flight check failed</h1><p>Installation cannot continue:</p><ul>';
    foreach ($preflight_blocking as $msg) {
        echo '<li>' . htmlspecialchars($msg) . '</li>';
    }
    echo '</ul><p>Please fix the above and reload this page. Lupopedia requires PHP 5.3+, pdo_mysql, mysqli, json, and a writable project root.</p></body></html>';
    exit;
}

session_start();

// Only treat as installed if lupopedia-config.php exists (WordPress-style multi-path resolve) and defines LUPOPEDIA_CONFIG_LOADED.
// Do NOT treat config.php or other files as installed; do not redirect during install.
$lupoWizardPublicPath = '/' . basename(LUPOPEDIA_PATH);
$configPath = LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, $lupoWizardPublicPath);
$forceReinstall = isset($_GET['force_reinstall']) && $_GET['force_reinstall'] === '1';
if ($configPath !== null && is_file($configPath) && !$forceReinstall) {
    require_once $configPath;
    if (defined('LUPOPEDIA_CONFIG_LOADED') && LUPOPEDIA_CONFIG_LOADED) {
        $scriptName = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
        $dir = str_replace('\\', '/', dirname($scriptName));
        $base = rtrim($dir, '/');
        header('Location: ' . ($base === '' ? '/login.php' : $base . '/login.php'));
        exit;
    }
}

$step = isset($_GET['step']) ? $_GET['step'] : (isset($_POST['step']) ? $_POST['step'] : 'welcome');

// Download log endpoint (no HTML)
if ($step === 'download_log') {
    header('Content-Type: text/plain; charset=utf-8');
    header('Content-Disposition: attachment; filename="lupopedia-wizard-log.txt"');
    $which = isset($_GET['which']) ? $_GET['which'] : 'run';
    $out = array();
    if ($which === 'bootstrap' && !empty($_SESSION['lupo_bootstrap_log'])) {
        foreach ($_SESSION['lupo_bootstrap_log'] as $e) {
            $out[] = '[' . $e[0] . '] ' . (isset($e[2]) ? $e[2] . ' ' : '') . $e[1];
        }
    } elseif ($which === 'run' && !empty($_SESSION['lupo_run_log'])) {
        foreach ($_SESSION['lupo_run_log'] as $e) {
            $out[] = '[' . $e[0] . '] ' . (isset($e[2]) ? $e[2] . ' ' : '') . $e[1];
        }
    } elseif ($which === 'config' && !empty($_SESSION['lupo_config_log'])) {
        foreach ($_SESSION['lupo_config_log'] as $e) {
            $out[] = '[' . $e[0] . '] ' . (isset($e[2]) ? $e[2] . ' ' : '') . $e[1];
        }
    } else {
        $out[] = 'No log available.';
    }
    echo implode("\n", $out);
    exit;
}

// Start over: clear wizard session and redirect to welcome
if (isset($_POST['action']) && $_POST['action'] === 'start_over') {
    foreach (array('lupo_install_db_vars', 'lupo_install_type', 'lupo_install_mode_choice', 'lupo_install_mode_warning', 'lupo_install_livehelp_tables', 'lupo_drop_livehelp_tables', 'lupo_normalize_applied', 'lupo_normalize_count', 'lupo_operator_channel_map', 'lupo_bootstrap_log', 'lupo_run_log', 'lupo_run_done', 'lupo_import_run', 'lupo_wizard_audit_log', 'lupo_config_log', 'lupo_config_site_name', 'lupo_config_base_url', 'lupo_config_admin_email', 'lupo_config_timezone', 'lupo_config_default_language', 'lupo_config_support_email', 'lupo_config_default_visitor_channel', 'lupo_config_enable_ai_channels', 'lupo_config_admin_password', 'lupo_api_key_anthropic', 'lupo_api_key_gemini', 'lupo_api_key_grok', 'lupo_api_key_deepseek', 'lupo_api_key_groq', 'lupo_api_key_openai', 'lupo_api_budget_anthropic', 'lupo_api_budget_gemini', 'lupo_api_budget_grok', 'lupo_api_budget_deepseek', 'lupo_api_budget_groq', 'lupo_api_budget_openai', 'lupo_api_custom_names', 'lupo_api_custom_keys', 'lupo_api_custom_budgets', 'lupo_api_config_target_dir', 'lupo_api_fallback_order', 'lupo_api_key_mode', 'lupo_csrf_token') as $k) {
        unset($_SESSION[$k]);
    }
    header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=welcome');
    exit;
}
$errors = array();
$log = array();
// CSRF: invalidate POST handling (except start_over) if token missing or wrong
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['action']) || $_POST['action'] !== 'start_over') && !InstallWizardSecurity::validateCsrf()) {
    $errors[] = 'Invalid security token. Please try again.';
}

// ----- Step: welcome
if ($step === 'welcome') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'continue' && empty($errors)) {
        header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=credentials');
        exit;
    }
}

// ----- Step: credentials (form or detect)
if ($step === 'credentials') {
    $db_vars = InstallWizardCredentials::getDbCredentials();
    $selected_install_mode = isset($_POST['install_mode']) ? trim((string) $_POST['install_mode']) : (isset($_SESSION['lupo_install_mode_choice']) ? $_SESSION['lupo_install_mode_choice'] : 'new');
    if ($selected_install_mode !== 'new' && $selected_install_mode !== 'upgrade') {
        $selected_install_mode = 'new';
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $db_vars !== null && empty($errors)) {
        if (!isset($_POST['install_mode']) || ($_POST['install_mode'] !== 'new' && $_POST['install_mode'] !== 'upgrade')) {
            $errors[] = 'Please choose install mode: New install or Upgrade existing.';
        }
        $raw_prefix = isset($_POST['table_prefix']) ? trim((string) $_POST['table_prefix']) : 'lupo_';
        if ($raw_prefix === '') {
            $raw_prefix = 'lupo_';
        }
        if (!preg_match('/^[a-z0-9_]+$/', $raw_prefix)) {
            $errors[] = 'Table prefix may only contain lowercase letters, digits, and underscores.';
        }
    }
    if ($step === 'credentials' && $_SERVER['REQUEST_METHOD'] === 'POST' && $db_vars !== null && empty($errors)) {
        try {
            $pdo = InstallWizardDb::connectPdo($db_vars);
            $_SESSION['lupo_install_db_vars'] = $db_vars;
            $table_prefix = isset($_POST['table_prefix']) ? trim((string) $_POST['table_prefix']) : 'lupo_';
            if ($table_prefix === '' || !preg_match('/^[a-z0-9_]+$/', $table_prefix)) {
                $table_prefix = 'lupo_';
            }
            $_SESSION['lupo_table_prefix'] = $table_prefix;
            if (!defined('LUPO_TABLE_PREFIX')) {
                define('LUPO_TABLE_PREFIX', $table_prefix);
            }
            $_SESSION['lupo_drop_livehelp_tables'] = isset($_POST['drop_livehelp_tables']) && $_POST['drop_livehelp_tables'] === '1';
            $_SESSION['lupo_install_mode_choice'] = isset($_POST['install_mode']) ? trim((string) $_POST['install_mode']) : 'new';
            unset($_SESSION['lupo_install_mode_warning']);
            // Check for Crafty Syntax installation for upgrade mode
            $selected_mode = isset($_SESSION['lupo_install_mode_choice']) ? $_SESSION['lupo_install_mode_choice'] : 'new';
            
            // Auto-detect Crafty Syntax if upgrade mode is selected
            if ($selected_mode === 'upgrade') {
                if (!InstallWizardCredentials::craftyConfigExists()) {
                    $errors[] = 'Crafty Syntax installation not found. Cannot perform upgrade.';
                } else {
                    $_SESSION['lupo_install_type'] = 'upgrade';
                    $_SESSION['lupo_install_mode_warning'] = 'Crafty Syntax upgrade detected. Existing data will be preserved.';
                }
            } else {
                $_SESSION['lupo_install_type'] = 'new';
                $_SESSION['lupo_install_mode_warning'] = 'New installation selected.';
            }
            
            if (!empty($errors)) {
                throw new RuntimeException(InstallWizardLogger::safeErrorMessage('validation'));
            }
            
            // Detect livehelp tables for upgrade mode
            if ($_SESSION['lupo_install_type'] === 'upgrade') {
                try {
                    $pdo = InstallWizardDb::connectPdo($db_vars);
                    $_SESSION['lupo_install_livehelp_tables'] = InstallWizardDb::detectLivehelpTables($pdo);
                } catch (Exception $e) {
                    $_SESSION['lupo_install_livehelp_tables'] = array();
                }
            } else {
                $_SESSION['lupo_install_livehelp_tables'] = array();
            }
            $base = (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.');
            // Handle both new install and upgrade paths
            $credentials_redirect = false;
            // Base installer complete - signal to wizard
            $_SESSION['lupo_base_install_complete'] = true;
            if (isset($_SESSION['lupo_install_type']) && $_SESSION['lupo_install_type'] === 'upgrade') {
                $mysqlDir = LUPO_MYSQL_DIR;
                if (!defined('LUPO_TABLE_PREFIX') && isset($_SESSION['lupo_table_prefix'])) {
                    define('LUPO_TABLE_PREFIX', $_SESSION['lupo_table_prefix']);
                }
                $bootstrapLog = array();
                $table_prefix = isset($_SESSION['lupo_table_prefix']) ? $_SESSION['lupo_table_prefix'] : 'lupo_';
                try {
                    $install_ok = InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'install_new_lupopedia.sql', $bootstrapLog, $table_prefix);
                    if (!$install_ok) {
                        $sqlDetail = InstallWizardSqlRunner::formatLastSqlFailureForException($bootstrapLog);
                        throw new RuntimeException('Critical schema install failed (install_new_lupopedia.sql). Stop and fix SQL errors before continuing.' . ($sqlDetail !== '' ? ' ' . $sqlDetail : ''));
                    }
                    if (!is_file(LUPO_CONSOLIDATED_SEED_FILE)) {
                        throw new RuntimeException('Consolidated seed not found: ' . LUPO_CONSOLIDATED_SEED_FILE);
                    }
                    $seed_ok = InstallWizardSqlRunner::runSqlFile($pdo, LUPO_CONSOLIDATED_SEED_FILE, $bootstrapLog, $table_prefix);
                    if (!$seed_ok) {
                        $sqlDetail = InstallWizardSqlRunner::formatLastSqlFailureForException($bootstrapLog);
                        throw new RuntimeException('Critical seed failed (seed_4.1.0.sql). Stop and fix SQL errors before continuing.' . ($sqlDetail !== '' ? ' ' . $sqlDetail : ''));
                    }
                    InstallWizardChannels::createReservedSystemChannels($pdo, $bootstrapLog);
                    $_SESSION['lupo_bootstrap_log'] = $bootstrapLog;
                    header('Location: ' . $base . '/install.php?step=bootstrap');
                    $credentials_redirect = true;
                } catch (RuntimeException $e) {
                    $errors[] = $e->getMessage();
                    $bootstrapLog[] = InstallWizardLogger::logEntry('error', $e->getMessage());
                    $_SESSION['lupo_bootstrap_log'] = $bootstrapLog;
                }
            } else {
                header('Location: ' . $base . '/install.php?step=confirm');
                $credentials_redirect = true;
            }
            if ($credentials_redirect) {
                exit;
            }
        } catch (RuntimeException $e) {
            if (empty($errors)) {
                $errors[] = InstallWizardLogger::safeErrorMessage('validation');
            }
            error_log('Lupopedia install credentials validation: ' . $e->getMessage());
        } catch (Exception $e) {
            $errors[] = 'Database connection failed. Check host, database name, user, and password.';
            error_log('Lupopedia install credentials: ' . $e->getMessage());
        }
    } elseif ($db_vars !== null && $_SERVER['REQUEST_METHOD'] !== 'POST' && isset($_SESSION['lupo_install_type'])) {
        // Only redirect when we have already connected and stored install type (prevents loop when config.php exists but form not yet submitted).
        $base = (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.');
        $install_type = $_SESSION['lupo_install_type'];
        if ($install_type === 'upgrade') {
            header('Location: ' . $base . '/install.php?step=bootstrap');
        } else {
            header('Location: ' . $base . '/install.php?step=confirm');
        }
        exit;
    }
}

// ----- Step: bootstrap (upgrade only — show result of install+seed+reserved, then continue to normalize)
if ($step === 'bootstrap') {
    $install_type = isset($_SESSION['lupo_install_type']) ? $_SESSION['lupo_install_type'] : null;
    if ($install_type !== 'upgrade') {
        header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=confirm');
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'continue' && empty($errors)) {
        if (function_exists('error_log')) {
            error_log('Lupopedia wizard: bootstrap POST accepted, redirecting to step=normalize');
        }
        $_SESSION['lupo_wizard_step'] = 'normalize';
        header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=normalize');
        exit;
    }
}

// ----- Step: normalize (upgrade only — identity normalization before import)
$normalize_identities = array();
$normalize_duplicates = array();
$normalize_warnings = array();
$normalize_validation_by_id = array();
$normalize_applied = false;
if ($step === 'normalize') {
    $install_type = isset($_SESSION['lupo_install_type']) ? $_SESSION['lupo_install_type'] : null;
    if ($install_type !== 'upgrade') {
        header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=confirm');
        exit;
    }
    $db_vars = isset($_SESSION['lupo_install_db_vars']) ? $_SESSION['lupo_install_db_vars'] : null;
    if ($db_vars === null) {
        header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=credentials');
        exit;
    }
    try {
        $pdo = InstallWizardDb::connectPdo($db_vars);
    } catch (Exception $e) {
        $errors[] = 'Database connection failed. Check your credentials.';
        error_log('Lupopedia install normalize: ' . $e->getMessage());
        $step = 'credentials';
    }
    if ($step === 'normalize') {
        $users = InstallWizardNormalize::loadCraftyUsers($pdo);
        $normalize_identities = InstallWizardNormalize::computeProposedIdentities($users);
        $normalize_duplicates = InstallWizardNormalize::findDuplicateEmailGroups($normalize_identities);
        $normalize_warnings = InstallWizardNormalize::collectNormalizeWarnings($normalize_identities);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'apply_normalization' && empty($errors)) {
            if (empty($normalize_identities)) {
                $_SESSION['lupo_normalize_applied'] = true;
                header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=confirm');
                exit;
            }
            $resolved = array();
            foreach ($normalize_identities as $row) {
                $id = $row['user_id'];
                $resolved[$id] = trim((string) (isset($_POST['email_' . $id]) ? $_POST['email_' . $id] : $row['proposed_email']));
                if ($resolved[$id] === '') {
                    $resolved[$id] = $row['proposed_email'];
                }
            }
            $validation = InstallWizardNormalize::validateResolvedEmails($normalize_identities, $resolved);
            if (!empty($validation['errors'])) {
                foreach (array_slice($validation['errors'], 0, 5) as $msg) {
                    $errors[] = $msg;
                }
                if (count($validation['errors']) > 5) {
                    $errors[] = 'Fix all invalid or duplicate emails below before continuing.';
                }
                $normalize_validation_by_id = $validation['by_id'];
            } else {
                InstallWizardNormalize::applyNormalizationToLivehelp($pdo, $normalize_identities, $resolved);
                $normalize_applied = true;
                $_SESSION['lupo_normalize_applied'] = true;
                $_SESSION['lupo_normalize_count'] = count($normalize_identities);
                header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=confirm');
                exit;
            }
        }
    }
}

// ----- Step: confirm (show install type and "Run" button)
if ($step === 'confirm') {
    $install_type = isset($_SESSION['lupo_install_type']) ? $_SESSION['lupo_install_type'] : null;
    $livehelp_tables = isset($_SESSION['lupo_install_livehelp_tables']) ? $_SESSION['lupo_install_livehelp_tables'] : array();
    if ($install_type === null) {
        header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=credentials');
        exit;
    }
    if ($install_type === 'upgrade' && empty($_SESSION['lupo_normalize_applied'])) {
        header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=normalize');
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'run' && empty($errors)) {
        if (function_exists('error_log')) {
            error_log('Lupopedia wizard: confirm POST action=run accepted, setting step=run');
        }
        $step = 'run';
        // Process run in same request so SQL actually executes (no redirect that could strip POST)
    }
}

// ----- Step: run SQL, write config, redirect (only on POST from confirm form)
if ($step === 'run') {
    // Allow up to 5 minutes for install/seed/import (avoids white screen from PHP timeout).
    @set_time_limit(300);
    $db_vars = isset($_SESSION['lupo_install_db_vars']) ? $_SESSION['lupo_install_db_vars'] : null;
    $install_type = isset($_SESSION['lupo_install_type']) ? $_SESSION['lupo_install_type'] : 'new';
    $livehelp_tables = isset($_SESSION['lupo_install_livehelp_tables']) ? $_SESSION['lupo_install_livehelp_tables'] : array();
    if ($db_vars === null) {
        header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=credentials');
        exit;
    }
    // If GET, only redirect to confirm when run has not completed yet (allows showing result after refresh)
    $run_is_get_with_result = false;
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        if (!empty($_SESSION['lupo_run_done']) && !empty($_SESSION['lupo_run_log'])) {
            $log = $_SESSION['lupo_run_log'];
            $run_is_get_with_result = true;
        } else {
            header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=confirm');
            exit;
        }
    }
    if (!$run_is_get_with_result) {
        try {
            $pdo = InstallWizardDb::connectPdo($db_vars);
        } catch (Exception $e) {
            $errors[] = 'Database connection failed. Check your credentials.';
            error_log('Lupopedia install run step: ' . $e->getMessage());
            $step = 'confirm';
        }
    }
    if ($step === 'run' && !$run_is_get_with_result) {
        // Doctrine enforcement: upgrade must have normalized.
        if ($install_type === 'upgrade' && empty($_SESSION['lupo_normalize_applied'])) {
            header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=normalize');
            exit;
        }

        if (!defined('LUPO_TABLE_PREFIX') && isset($_SESSION['lupo_table_prefix'])) {
            define('LUPO_TABLE_PREFIX', $_SESSION['lupo_table_prefix']);
        }
        $table_prefix = isset($_SESSION['lupo_table_prefix']) ? $_SESSION['lupo_table_prefix'] : 'lupo_';
        $mysqlDir = LUPO_MYSQL_DIR;
        // Base installer: No import script - only install_new_lupopedia.sql
        // Base install: run install → seed → reserved channels → config.

        try {
            if ($install_type === 'new') {
                $dept_table = $table_prefix . 'departments';
                $schema_ok = false;
                try {
                    $schema_ok = InstallWizardDb::tableExists($pdo, $dept_table);
                } catch (Exception $e) {
                    $schema_ok = false;
                }
                if (!$schema_ok) {
                    $log[] = InstallWizardLogger::logEntry('ok', 'Schema missing (e.g. tables dropped); running install and consolidated seed first.');
                    $install_ok = InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'install_new_lupopedia.sql', $log, $table_prefix);
                    if (!$install_ok) {
                        $sqlDetail = InstallWizardSqlRunner::formatLastSqlFailureForException($log);
                        throw new RuntimeException('Critical schema install failed (install_new_lupopedia.sql). Stop and fix SQL errors before continuing.' . ($sqlDetail !== '' ? ' ' . $sqlDetail : ''));
                    }
                    if (!is_file(LUPO_CONSOLIDATED_SEED_FILE)) {
                        throw new RuntimeException('Consolidated seed not found: ' . LUPO_CONSOLIDATED_SEED_FILE);
                    }
                    $seed_ok = InstallWizardSqlRunner::runSqlFile($pdo, LUPO_CONSOLIDATED_SEED_FILE, $log, $table_prefix);
                    if (!$seed_ok) {
                        $sqlDetail = InstallWizardSqlRunner::formatLastSqlFailureForException($log);
                        throw new RuntimeException('Critical seed failed (seed_4.1.0.sql). Stop and fix SQL errors before continuing.' . ($sqlDetail !== '' ? ' ' . $sqlDetail : ''));
                    }
                    InstallWizardChannels::createReservedSystemChannels($pdo, $log);
                } elseif (!empty($_SESSION['lupo_bootstrap_log'])) {
                    $log = array_merge($log, $_SESSION['lupo_bootstrap_log']);
                }
                $log[] = InstallWizardLogger::logEntry('ok', '--- Run step: import → personal channels and captain roles → drop ---');
                InstallWizardDepartments::ensureSystemDepartment($pdo, $log);
                InstallWizardChannels::ensureReservedChannels($pdo, $log);
            }

            if ($install_type === 'new') {
                $install_ok = InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'install_new_lupopedia.sql', $log, $table_prefix);
                if (!$install_ok) {
                    $sqlDetail = InstallWizardSqlRunner::formatLastSqlFailureForException($log);
                    throw new RuntimeException('Critical schema install failed (install_new_lupopedia.sql). Stop and fix SQL errors before continuing.' . ($sqlDetail !== '' ? ' ' . $sqlDetail : ''));
                }
                if (!is_file(LUPO_CONSOLIDATED_SEED_FILE)) {
                    throw new RuntimeException('Consolidated seed not found: ' . LUPO_CONSOLIDATED_SEED_FILE);
                }
                $seed_ok = InstallWizardSqlRunner::runSqlFile($pdo, LUPO_CONSOLIDATED_SEED_FILE, $log, $table_prefix);
                if (!$seed_ok) {
                    $sqlDetail = InstallWizardSqlRunner::formatLastSqlFailureForException($log);
                    throw new RuntimeException('Critical seed failed (seed_4.1.0.sql). Stop and fix SQL errors before continuing.' . ($sqlDetail !== '' ? ' ' . $sqlDetail : ''));
                }
                InstallWizardDepartments::ensureSystemDepartment($pdo, $log);
                InstallWizardChannels::createReservedSystemChannels($pdo, $log);

                InstallWizardAgentLoader::importAllAgentPacks($pdo, $log, $table_prefix);

                // Import MD files from channels/0/broadcasts/
                InstallWizardMdImporter::importAllMdFiles($pdo, $log, $table_prefix);
                $log[] = InstallWizardLogger::logEntry('ok', 'New install: lupo_crafty_syntax_* tables are empty; import_from_old_crafty_syntax.sql runs only on upgrade from Crafty Syntax 3.7.5.');
            }

            // Base installer: No import logic - legacy import moved to optional wizard step

            if ($install_type !== 'new') {
                InstallWizardAgentLoader::importAllAgentPacks($pdo, $log, $table_prefix);
            }

            // Import MD files from channels/0/broadcasts/ (for both new install and upgrade)
            InstallWizardMdImporter::importAllMdFiles($pdo, $log, $table_prefix);

            // 4.0.93+: registry_open allocation removed (deterministic explicit IDs doctrine).
            // Keep no-op here intentionally to avoid touching removed tables during install.
            // 4.0.20: Ensure Stoned Wolfie (AI + human) banned test identities exist after import/seed.
            InstallWizardBannedIdentities::ensureStonedWolfieBannedIdentities($pdo, $log, $table_prefix);
            // 4.0.93+: single-seed runtime doctrine.
            // ANUBIS schema/tables are canonical in install_new_lupopedia.sql.

            // Activations Block — session-backed "running" for personas that use heartbeat/session semantics.
            // ANUBIS (actor_id 19) is a custodial PHP + queue-table subsystem, not a session-backed chat actor;
            // seed may still define lupo_actors row 19 for attribution in logs — we do not fabricate a session here.
            // IRIS and similar integration agents are likewise not activated via ensureActorActive.
            require_once LUPOPEDIA_PATH . '/includes/functions/ai_activation.php';
            $core_actors = array(0, 1, 2, 111); // SYSTEM, CAPTAIN (wolfie), LILITH, COUNTERMEASURE
            $log[] = InstallWizardLogger::logEntry('ok', '--- Activating CORE AI Agents (session-backed where applicable) ---');
            $pdo_actor = InstallWizardDb::connectPdoBuffered($db_vars);
            $actor_db = new PDO_DB($pdo_actor);
            foreach ($core_actors as $actor_id) {
                if (ensureActorActive($actor_id, $actor_db, 'initial_install_activation')) {
                    $log[] = InstallWizardLogger::logEntry('ok', "Activated Actor ID: $actor_id");
                } else {
                    $log[] = InstallWizardLogger::logEntry('skip', "Warning: Could not activate Actor ID: $actor_id (non-critical)");
                }
            }

            // ANUBIS schema health: queue tables must exist; no synthetic actor session required.
            $required_anubis_tables = array(
                'anubis_queue',
                'anubis_processing_log',
                'anubis_recovery_attempts',
                'anubis_quarantine',
            );
            foreach ($required_anubis_tables as $table) {
                $full_table = $table_prefix . $table;
                if (!InstallWizardDb::tableExists($pdo, $full_table)) {
                    throw new RuntimeException("ANUBIS table $full_table missing - cannot proceed");
                }
            }
            $log[] = InstallWizardLogger::logEntry('ok', 'ANUBIS queue tables verified (custodial subsystem; no actor session activation).');

            // BONES + SCOTTY monitoring tables (canonical in install_new_lupopedia.sql SECTION 12).
            $required_bones_tables = array(
                'health_events',
                'sleep_log',
                'pain_log',
                'med_effects',
                'energy_state',
                'captains_log_health',
            );
            foreach ($required_bones_tables as $table) {
                $full_table = $table_prefix . $table;
                if (!InstallWizardDb::tableExists($pdo, $full_table)) {
                    throw new RuntimeException("BONES table $full_table missing - cannot proceed");
                }
            }
            $log[] = InstallWizardLogger::logEntry('ok', 'BONES health-state tables verified (6 tables).');

            $required_scotty_tables = array(
                'ai_token_usage',
                'ai_llm_load',
                'ai_mcp_health',
                'ai_resource_usage',
                'ai_io_pressure',
                'ai_error_log',
                'ai_engineering_log',
                'ai_agent_message_traffic',
            );
            foreach ($required_scotty_tables as $table) {
                $full_table = $table_prefix . $table;
                if (!InstallWizardDb::tableExists($pdo, $full_table)) {
                    throw new RuntimeException("SCOTTY table $full_table missing - cannot proceed");
                }
            }
            $log[] = InstallWizardLogger::logEntry('ok', 'SCOTTY AI systems monitoring tables verified (8 tables).');

            $log[] = InstallWizardLogger::logEntry('ok', 'Run complete.');
            $_SESSION['lupo_run_log'] = $log;
            $_SESSION['lupo_run_done'] = true;
            // Do not redirect; show run result with progress bar and log, then user clicks "Continue to Config"
        } catch (RuntimeException $e) {
            $errors[] = $e->getMessage();
            $log[] = InstallWizardLogger::logEntry('error', $e->getMessage());
            $_SESSION['lupo_run_log'] = $log;
            // Clear run_done flag to allow retry
            unset($_SESSION['lupo_run_done']);
            $step = 'confirm';
        }
    }
}

// ----- Step: config (site settings form; then continue to API keys)
$config_errors = array();
$config_values = array('site_name' => '', 'base_url' => '', 'admin_email' => '', 'timezone' => 'UTC', 'default_language' => 'en', 'support_email' => '', 'default_visitor_channel' => '1', 'enable_ai_channels' => '1');
$api_key_errors = array();
$api_key_values = array(
    'anthropic' => '',
    'gemini' => '',
    'grok' => '',
    'deepseek' => '',
    'groq' => '',
    'openai' => '',
    'budget_anthropic' => '15',
    'budget_gemini' => '15',
    'budget_grok' => '15',
    'budget_deepseek' => '15',
    'budget_groq' => '15',
    'budget_openai' => '15',
    'fallback_order' => 'gemini,deepseek,groq',
);
$api_custom_names = array();
$api_custom_keys = array();
$api_custom_budgets = array();
$api_key_mode = 'overwrite';
$api_existing_provider_config = null;
$llm_model_values = InstallWizardLLMDefaults::formDisplayDefaults();
$api_existing_config_path = LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, $lupoWizardPublicPath);
$api_config_target_dir = isset($_SESSION['lupo_api_config_target_dir']) ? (string) $_SESSION['lupo_api_config_target_dir'] : (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'] !== '' ? (string) $_SERVER['DOCUMENT_ROOT'] : LUPOPEDIA_PATH);
if ($api_existing_config_path !== null) {
    $api_existing_provider_config = lupo_read_existing_api_provider_config($api_existing_config_path);
}

if ($step === 'config') {
    if (empty($_SESSION['lupo_run_done'])) {
        header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=confirm');
        exit;
    }
    $baseSuggested = rtrim(dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : ''), '/');
    $basePathForUrl = ($baseSuggested === '') ? '' : ltrim($baseSuggested, '/');
    $defaultBaseUrl = ($basePathForUrl === '') ? '/' : '/' . $basePathForUrl . '/';
    $config_values = array(
        'site_name' => trim(strip_tags((string) (isset($_POST['site_name']) ? $_POST['site_name'] : (isset($_SESSION['lupo_config_site_name']) ? $_SESSION['lupo_config_site_name'] : 'Lupopedia')))),
        'base_url' => trim((string) (isset($_POST['base_url']) ? $_POST['base_url'] : (isset($_SESSION['lupo_config_base_url']) ? $_SESSION['lupo_config_base_url'] : $defaultBaseUrl))),
        'admin_email' => trim((string) (isset($_POST['admin_email']) ? $_POST['admin_email'] : (isset($_SESSION['lupo_config_admin_email']) ? $_SESSION['lupo_config_admin_email'] : 'captain@lupopedia.com'))),
        'timezone' => trim((string) (isset($_POST['timezone']) ? $_POST['timezone'] : (isset($_SESSION['lupo_config_timezone']) ? $_SESSION['lupo_config_timezone'] : 'UTC'))),
        'default_language' => trim((string) (isset($_POST['default_language']) ? $_POST['default_language'] : (isset($_SESSION['lupo_config_default_language']) ? $_SESSION['lupo_config_default_language'] : 'en'))),
        'support_email' => trim((string) (isset($_POST['support_email']) ? $_POST['support_email'] : (isset($_SESSION['lupo_config_support_email']) ? $_SESSION['lupo_config_support_email'] : ''))),
        'default_visitor_channel' => '1',
        'enable_ai_channels' => '1',
    );
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'continue_api_keys' && empty($errors)) {
        $config_errors = array();
        if ($config_values['site_name'] === '') {
            $config_errors[] = 'Site name is required and cannot be empty.';
        }
        $baseUrlRaw = $config_values['base_url'];
        $baseUrl = rtrim($baseUrlRaw, '/');
        if ($baseUrl !== '') {
            $baseUrl .= '/';
        }
        $config_values['base_url'] = $baseUrl;
        if (strpos($baseUrlRaw, 'http://') === 0 || strpos($baseUrlRaw, 'https://') === 0) {
            // full URL: must be http or https
        } elseif (substr($baseUrlRaw, 0, 1) === '/') {
            // path is ok
        } else {
            $config_errors[] = 'Base URL must start with / or be a full http/https URL.';
        }
        if (trim($config_values['admin_email']) === '') {
            $config_errors[] = 'Admin email is required.';
        } elseif (!InstallWizardNormalize::isValidEmail($config_values['admin_email'])) {
            $config_errors[] = 'Admin email must be a valid email address.';
        }
        $validTimezones = timezone_identifiers_list();
        if (!in_array($config_values['timezone'], $validTimezones, true)) {
            $config_errors[] = 'Please select a valid PHP timezone.';
        }
        if ($config_values['support_email'] !== '' && !InstallWizardNormalize::isValidEmail($config_values['support_email'])) {
            $config_errors[] = 'Support email must be a valid email address or empty.';
        }
        $install_type_for_config = isset($_SESSION['lupo_install_type']) ? $_SESSION['lupo_install_type'] : 'new';
        $admin_password = isset($_POST['admin_password']) ? (string) $_POST['admin_password'] : '';
        $admin_password_confirm = isset($_POST['admin_password_confirm']) ? (string) $_POST['admin_password_confirm'] : '';
        if ($install_type_for_config === 'new') {
            if ($admin_password === '') {
                $config_errors[] = 'Main admin password is required for new installs.';
            } elseif (strlen($admin_password) < 8) {
                $config_errors[] = 'Main admin password must be at least 8 characters.';
            } elseif ($admin_password !== $admin_password_confirm) {
                $config_errors[] = 'Main admin password and confirmation do not match.';
            }
        } elseif ($admin_password !== '' || $admin_password_confirm !== '') {
            if (strlen($admin_password) < 8) {
                $config_errors[] = 'Main admin password must be at least 8 characters.';
            } elseif ($admin_password !== $admin_password_confirm) {
                $config_errors[] = 'Main admin password and confirmation do not match.';
            }
        }
        if (empty($config_errors) && $install_type_for_config === 'upgrade') {
            $db_vars = isset($_SESSION['lupo_install_db_vars']) ? $_SESSION['lupo_install_db_vars'] : null;
            if ($db_vars !== null) {
                try {
                    $pdoConfig = InstallWizardDb::connectPdo($db_vars);
                    $tbl = (defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_') . 'auth_users';
                    $stmt = $pdoConfig->prepare('SELECT 1 FROM ' . $tbl . ' WHERE email = ? LIMIT 1');
                    if ($stmt && $stmt->execute(array($config_values['admin_email'])) && $stmt->fetch()) {
                        $config_errors[] = 'Admin email is already used by a migrated user. Choose a different email.';
                    }
                } catch (Exception $e) {
                    // table may not exist on new install; skip collision check
                }
            }
        }
        if (empty($config_errors)) {
            $_SESSION['lupo_config_site_name'] = $config_values['site_name'];
            $_SESSION['lupo_config_base_url'] = $config_values['base_url'];
            $_SESSION['lupo_config_admin_email'] = $config_values['admin_email'];
            $_SESSION['lupo_config_timezone'] = $config_values['timezone'];
            $_SESSION['lupo_config_default_language'] = $config_values['default_language'];
            $_SESSION['lupo_config_support_email'] = $config_values['support_email'];
            $_SESSION['lupo_config_default_visitor_channel'] = $config_values['default_visitor_channel'];
            $_SESSION['lupo_config_enable_ai_channels'] = $config_values['enable_ai_channels'];
            $_SESSION['lupo_config_admin_password'] = $admin_password;
            header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=api_keys');
            exit;
        }
    }
}

// ----- Step: api_keys (collect personal API keys, then write config)
if ($step === 'api_keys') {
    if (empty($_SESSION['lupo_run_done'])) {
        header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=confirm');
        exit;
    }
    if (!isset($_SESSION['lupo_config_site_name']) || !isset($_SESSION['lupo_config_admin_email'])) {
        header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=config');
        exit;
    }

    $api_key_values = array(
        'anthropic' => lupo_trim_api_key(isset($_POST['api_anthropic']) ? $_POST['api_anthropic'] : (isset($_SESSION['lupo_api_key_anthropic']) ? $_SESSION['lupo_api_key_anthropic'] : '')),
        'gemini' => lupo_trim_api_key(isset($_POST['api_gemini']) ? $_POST['api_gemini'] : (isset($_SESSION['lupo_api_key_gemini']) ? $_SESSION['lupo_api_key_gemini'] : '')),
        'grok' => lupo_trim_api_key(isset($_POST['api_grok']) ? $_POST['api_grok'] : (isset($_SESSION['lupo_api_key_grok']) ? $_SESSION['lupo_api_key_grok'] : '')),
        'deepseek' => lupo_trim_api_key(isset($_POST['api_deepseek']) ? $_POST['api_deepseek'] : (isset($_SESSION['lupo_api_key_deepseek']) ? $_SESSION['lupo_api_key_deepseek'] : '')),
        'groq' => lupo_trim_api_key(isset($_POST['api_groq']) ? $_POST['api_groq'] : (isset($_SESSION['lupo_api_key_groq']) ? $_SESSION['lupo_api_key_groq'] : '')),
        'openai' => lupo_trim_api_key(isset($_POST['api_openai']) ? $_POST['api_openai'] : (isset($_SESSION['lupo_api_key_openai']) ? $_SESSION['lupo_api_key_openai'] : '')),
        'budget_anthropic' => trim((string) (isset($_POST['api_budget_anthropic']) ? $_POST['api_budget_anthropic'] : (isset($_SESSION['lupo_api_budget_anthropic']) ? $_SESSION['lupo_api_budget_anthropic'] : '15'))),
        'budget_gemini' => trim((string) (isset($_POST['api_budget_gemini']) ? $_POST['api_budget_gemini'] : (isset($_SESSION['lupo_api_budget_gemini']) ? $_SESSION['lupo_api_budget_gemini'] : '15'))),
        'budget_grok' => trim((string) (isset($_POST['api_budget_grok']) ? $_POST['api_budget_grok'] : (isset($_SESSION['lupo_api_budget_grok']) ? $_SESSION['lupo_api_budget_grok'] : '15'))),
        'budget_deepseek' => trim((string) (isset($_POST['api_budget_deepseek']) ? $_POST['api_budget_deepseek'] : (isset($_SESSION['lupo_api_budget_deepseek']) ? $_SESSION['lupo_api_budget_deepseek'] : '15'))),
        'budget_groq' => trim((string) (isset($_POST['api_budget_groq']) ? $_POST['api_budget_groq'] : (isset($_SESSION['lupo_api_budget_groq']) ? $_SESSION['lupo_api_budget_groq'] : '15'))),
        'budget_openai' => trim((string) (isset($_POST['api_budget_openai']) ? $_POST['api_budget_openai'] : (isset($_SESSION['lupo_api_budget_openai']) ? $_SESSION['lupo_api_budget_openai'] : '15'))),
        'fallback_order' => trim((string) (isset($_POST['fallback_order']) ? $_POST['fallback_order'] : (isset($_SESSION['lupo_api_fallback_order']) ? $_SESSION['lupo_api_fallback_order'] : 'gemini,deepseek,groq'))),
    );
    $api_custom_names = isset($_POST['custom_provider_name']) && is_array($_POST['custom_provider_name']) ? $_POST['custom_provider_name'] : (isset($_SESSION['lupo_api_custom_names']) && is_array($_SESSION['lupo_api_custom_names']) ? $_SESSION['lupo_api_custom_names'] : array());
    $api_custom_keys = isset($_POST['custom_provider_key']) && is_array($_POST['custom_provider_key']) ? $_POST['custom_provider_key'] : (isset($_SESSION['lupo_api_custom_keys']) && is_array($_SESSION['lupo_api_custom_keys']) ? $_SESSION['lupo_api_custom_keys'] : array());
    $api_custom_budgets = isset($_POST['custom_provider_budget']) && is_array($_POST['custom_provider_budget']) ? $_POST['custom_provider_budget'] : (isset($_SESSION['lupo_api_custom_budgets']) && is_array($_SESSION['lupo_api_custom_budgets'] ) ? $_SESSION['lupo_api_custom_budgets'] : array());
    $api_config_target_dir = trim((string) (isset($_POST['config_target_dir']) ? $_POST['config_target_dir'] : (isset($_SESSION['lupo_api_config_target_dir']) ? $_SESSION['lupo_api_config_target_dir'] : $api_config_target_dir)));

    $llm_model_post = InstallWizardLLMDefaults::parseModelPost($_POST);
    $llm_model_values = InstallWizardLLMDefaults::buildModelsConfig($llm_model_post);
    if (isset($_SESSION['lupo_llm_models']) && is_array($_SESSION['lupo_llm_models']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        $llm_model_values = InstallWizardLLMDefaults::buildModelsConfig($_SESSION['lupo_llm_models']);
    }

    $api_key_mode = isset($_POST['api_key_mode']) ? trim((string) $_POST['api_key_mode']) : (isset($_SESSION['lupo_api_key_mode']) ? $_SESSION['lupo_api_key_mode'] : ((is_array($api_existing_provider_config) ? 'keep' : 'overwrite')));
    if ($api_key_mode !== 'keep' && $api_key_mode !== 'overwrite') {
        $api_key_mode = is_array($api_existing_provider_config) ? 'keep' : 'overwrite';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'write_config' && empty($errors)) {
        $api_key_errors = array();
        $providerOrderAllowed = array('gemini', 'deepseek', 'groq', 'anthropic', 'grok', 'openai');
        $customProvidersSanitized = array();
        $customProviderCount = max(count($api_custom_names), count($api_custom_keys), count($api_custom_budgets));
        for ($i = 0; $i < $customProviderCount; $i++) {
            $rawName = isset($api_custom_names[$i]) ? trim((string) $api_custom_names[$i]) : '';
            $rawKey = isset($api_custom_keys[$i]) ? lupo_trim_api_key($api_custom_keys[$i]) : '';
            $rawBudget = isset($api_custom_budgets[$i]) ? trim((string) $api_custom_budgets[$i]) : '15';
            if ($rawName === '' && $rawKey === '') {
                continue;
            }
            $slug = strtolower($rawName);
            $slug = preg_replace('/[^a-z0-9_]/', '', str_replace(array('-', ' '), '_', $slug));
            if ($slug === '' || in_array($slug, $providerOrderAllowed, true)) {
                $api_key_errors[] = 'Custom provider names must be unique and contain letters/numbers (not reserved names).';
                continue;
            }
            if (!is_numeric($rawBudget) || (float) $rawBudget < 0) {
                $api_key_errors[] = 'Custom provider budget must be a number >= 0.';
                continue;
            }
            $customProvidersSanitized[$slug] = array(
                'name' => $rawName,
                'api_key' => $rawKey,
                'budget' => (float) $rawBudget,
                'enabled' => ($rawKey !== ''),
            );
            $providerOrderAllowed[] = $slug;
        }

        $providerOrder = lupo_parse_provider_order($api_key_values['fallback_order'], $providerOrderAllowed);
        if (empty($providerOrder)) {
            $api_key_errors[] = 'Fallback order is required.';
        }
        $budgetFields = array(
            'budget_gemini' => 'Gemini',
            'budget_deepseek' => 'DeepSeek',
            'budget_groq' => 'Groq',
            'budget_anthropic' => 'Anthropic',
            'budget_grok' => 'Grok',
            'budget_openai' => 'OpenAI',
        );
        foreach ($budgetFields as $field => $label) {
            if (!is_numeric($api_key_values[$field]) || (float) $api_key_values[$field] < 0) {
                $api_key_errors[] = $label . ' budget must be a number >= 0.';
            }
        }
        $llmProviders = array('deepseek', 'gemini', 'groq', 'anthropic', 'grok', 'openai');
        foreach ($llmProviders as $llmProvider) {
            $tempField = 'model_' . $llmProvider . '_temperature';
            $maxField = 'model_' . $llmProvider . '_max_tokens';
            if (isset($_POST[$tempField]) && $_POST[$tempField] !== '' && !is_numeric($_POST[$tempField])) {
                $api_key_errors[] = ucfirst($llmProvider) . ' temperature must be numeric.';
            }
            if (isset($_POST[$maxField]) && $_POST[$maxField] !== '' && (!is_numeric($_POST[$maxField]) || (int) $_POST[$maxField] < 1)) {
                $api_key_errors[] = ucfirst($llmProvider) . ' max tokens must be an integer >= 1.';
            }
        }
        $db_vars = isset($_SESSION['lupo_install_db_vars']) ? $_SESSION['lupo_install_db_vars'] : null;
        if ($db_vars === null) {
            $api_key_errors[] = 'Database credentials are missing from session. Restart installer.';
        }
        if ($api_config_target_dir === '' || !is_dir($api_config_target_dir)) {
            $api_key_errors[] = 'Config target directory does not exist.';
        } elseif (!is_writable($api_config_target_dir)) {
            $api_key_errors[] = 'The selected config directory is not writable. Some shared hosts do not allow writing outside the web root. You may instead place lupopedia-config.php in the web root if server constraints require it.';
        }

        $_SESSION['lupo_api_key_anthropic'] = $api_key_values['anthropic'];
        $_SESSION['lupo_api_key_gemini'] = $api_key_values['gemini'];
        $_SESSION['lupo_api_key_grok'] = $api_key_values['grok'];
        $_SESSION['lupo_api_key_deepseek'] = $api_key_values['deepseek'];
        $_SESSION['lupo_api_key_groq'] = $api_key_values['groq'];
        $_SESSION['lupo_api_key_openai'] = $api_key_values['openai'];
        $_SESSION['lupo_api_budget_anthropic'] = $api_key_values['budget_anthropic'];
        $_SESSION['lupo_api_budget_gemini'] = $api_key_values['budget_gemini'];
        $_SESSION['lupo_api_budget_grok'] = $api_key_values['budget_grok'];
        $_SESSION['lupo_api_budget_deepseek'] = $api_key_values['budget_deepseek'];
        $_SESSION['lupo_api_budget_groq'] = $api_key_values['budget_groq'];
        $_SESSION['lupo_api_budget_openai'] = $api_key_values['budget_openai'];
        $_SESSION['lupo_api_custom_names'] = $api_custom_names;
        $_SESSION['lupo_api_custom_keys'] = $api_custom_keys;
        $_SESSION['lupo_api_custom_budgets'] = $api_custom_budgets;
        $_SESSION['lupo_api_config_target_dir'] = $api_config_target_dir;
        $_SESSION['lupo_api_fallback_order'] = implode(',', $providerOrder);
        $_SESSION['lupo_api_key_mode'] = $api_key_mode;
        $_SESSION['lupo_llm_models'] = $llm_model_post;

        if (empty($api_key_errors)) {
            $writeLog = array();
            $table_prefix = isset($_SESSION['lupo_table_prefix']) ? $_SESSION['lupo_table_prefix'] : 'lupo_';
            $install_type_for_config = isset($_SESSION['lupo_install_type']) ? $_SESSION['lupo_install_type'] : 'new';
            $admin_password_for_create = isset($_SESSION['lupo_config_admin_password']) ? (string) $_SESSION['lupo_config_admin_password'] : '';
            $should_create_main_admin = ($install_type_for_config === 'new' && $admin_password_for_create !== '') || ($install_type_for_config === 'upgrade' && $admin_password_for_create !== '');

            if ($should_create_main_admin) {
                try {
                    $pdoConfig = InstallWizardDb::connectPdo($db_vars);
                    if (!InstallWizardMainAdmin::createMainAdmin($pdoConfig, $table_prefix, $_SESSION['lupo_config_admin_email'], $admin_password_for_create, $writeLog)) {
                        $api_key_errors[] = 'Could not create main admin user. Check the log.';
                    } elseif (!InstallWizardMainAdmin::repairMainAdminInstall($pdoConfig, $table_prefix, $writeLog)) {
                        $api_key_errors[] = 'Could not verify main admin privileges. Check the log.';
                    }
                    // Create red team user and pair with WOLFIE
                    if (!InstallWizardMainAdmin::createRedTeamUser($pdoConfig, $table_prefix, $writeLog)) {
                        $api_key_errors[] = 'Could not create red team user. Check the log.';
                    }
                } catch (Exception $e) {
                    $api_key_errors[] = 'Database connection failed when creating main admin: ' . $e->getMessage();
                    $writeLog[] = InstallWizardLogger::logEntry('error', $e->getMessage());
                }
            }

            if (empty($api_key_errors)) {
                $modelsConfig = InstallWizardLLMDefaults::buildModelsConfig($llm_model_post);
                $providerConfig = array(
                    'provider_order' => $providerOrder,
                    'request_class_order' => array(
                        'default' => $providerOrder,
                        'complex' => array('deepseek', 'anthropic', 'gemini', 'openai'),
                        'audit' => array('deepseek', 'gemini', 'anthropic', 'openai'),
                    ),
                    'monthly_budget_cap_usd' => 45.0,
                    'premium_provider_block_threshold_usd' => 40.0,
                    'premium_providers' => array('openai', 'anthropic'),
                    'fallback_order' => $providerOrder,
                    'config_version' => '2026.04',
                    'memory_path' => '__DIR__/memory/',
                    'channels_path' => '__DIR__/channels/',
                    'models' => $modelsConfig,
                    'llm_defaults' => InstallWizardLLMDefaults::globalLlmDefaults(),
                    'providers' => array(
                        'anthropic' => array('enabled' => ($api_key_values['anthropic'] !== ''), 'api_key' => $api_key_values['anthropic'], 'key' => $api_key_values['anthropic'], 'budget' => (float) $api_key_values['budget_anthropic'], 'display_name' => 'Anthropic'),
                        'gemini' => array('enabled' => ($api_key_values['gemini'] !== ''), 'api_key' => $api_key_values['gemini'], 'key' => $api_key_values['gemini'], 'budget' => (float) $api_key_values['budget_gemini'], 'display_name' => 'Google Gemini'),
                        'grok' => array('enabled' => ($api_key_values['grok'] !== ''), 'api_key' => $api_key_values['grok'], 'key' => $api_key_values['grok'], 'budget' => (float) $api_key_values['budget_grok'], 'display_name' => 'Grok xAI'),
                        'deepseek' => array('enabled' => ($api_key_values['deepseek'] !== ''), 'api_key' => $api_key_values['deepseek'], 'key' => $api_key_values['deepseek'], 'budget' => (float) $api_key_values['budget_deepseek'], 'display_name' => 'DeepSeek'),
                        'groq' => array('enabled' => ($api_key_values['groq'] !== ''), 'api_key' => $api_key_values['groq'], 'key' => $api_key_values['groq'], 'budget' => (float) $api_key_values['budget_groq'], 'display_name' => 'Groq'),
                        'openai' => array('enabled' => ($api_key_values['openai'] !== ''), 'api_key' => $api_key_values['openai'], 'key' => $api_key_values['openai'], 'budget' => (float) $api_key_values['budget_openai'], 'display_name' => 'OpenAI'),
                    ),
                    'storage_notice' => 'Keys are stored only on this server and used exclusively by this installation.',
                );
                foreach ($customProvidersSanitized as $customKey => $customValues) {
                    $providerConfig['providers'][$customKey] = array(
                        'enabled' => $customValues['enabled'],
                        'api_key' => $customValues['api_key'],
                        'key' => $customValues['api_key'],
                        'budget' => $customValues['budget'],
                        'display_name' => $customValues['name'],
                    );
                }
                if ($api_key_mode === 'keep' && is_array($api_existing_provider_config)) {
                    $providerConfig = $api_existing_provider_config;
                    $writeLog[] = InstallWizardLogger::logEntry('ok', 'Kept existing API key configuration from current lupopedia-config.php.');
                }

                $options = array(
                    'site_name' => isset($_SESSION['lupo_config_site_name']) ? $_SESSION['lupo_config_site_name'] : 'Lupopedia',
                    'base_url' => isset($_SESSION['lupo_config_base_url']) ? $_SESSION['lupo_config_base_url'] : '/',
                    'admin_email' => isset($_SESSION['lupo_config_admin_email']) ? $_SESSION['lupo_config_admin_email'] : 'captain@lupopedia.com',
                    'timezone' => isset($_SESSION['lupo_config_timezone']) ? $_SESSION['lupo_config_timezone'] : 'UTC',
                    'default_language' => isset($_SESSION['lupo_config_default_language']) ? $_SESSION['lupo_config_default_language'] : 'en',
                    'table_prefix' => $table_prefix,
                    'api_provider_config' => $providerConfig,
                    'config_target_dir' => $api_config_target_dir,
                );
                if (isset($_SESSION['lupo_config_support_email']) && $_SESSION['lupo_config_support_email'] !== '') {
                    $options['support_email'] = $_SESSION['lupo_config_support_email'];
                }
                if (isset($_SESSION['lupo_config_default_visitor_channel']) && $_SESSION['lupo_config_default_visitor_channel'] !== '') {
                    $options['default_visitor_channel'] = $_SESSION['lupo_config_default_visitor_channel'];
                }
                if (isset($_SESSION['lupo_config_enable_ai_channels']) && $_SESSION['lupo_config_enable_ai_channels'] === '1') {
                    $options['enable_ai_channels'] = true;
                }

                $configPath = InstallWizardConfigWriter::writeConfig($db_vars, $writeLog, $options);
                if ($configPath !== null) {
                    try {
                        if (!isset($pdoConfig)) {
                            $pdoConfig = InstallWizardDb::connectPdo($db_vars);
                        }
                        InstallWizardLLMConfigLoader::seedAgentLLMConfigs($pdoConfig, $writeLog, $table_prefix, $providerConfig);
                    } catch (Exception $e) {
                        $writeLog[] = InstallWizardLogger::logEntry('error', 'agent_llm_configs seed failed: ' . $e->getMessage());
                    }
                    require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'InstallWizardHtaccessWriter.php';
                    InstallWizardHtaccessWriter::ensureRuntimeDirectories(LUPOPEDIA_PATH, $writeLog);
                    InstallWizardHtaccessWriter::writeDistributionHtaccess(LUPOPEDIA_PATH, $writeLog);
                    $writeLog[] = InstallWizardLogger::logEntry('ok', 'API keys were saved locally. Keys are not displayed by the installer.');
                    $_SESSION['lupo_config_log'] = $writeLog;
                    unset($_SESSION['lupo_install_db_vars'], $_SESSION['lupo_install_type'], $_SESSION['lupo_install_mode_choice'], $_SESSION['lupo_install_mode_warning'], $_SESSION['lupo_install_livehelp_tables'], $_SESSION['lupo_normalize_applied'], $_SESSION['lupo_operator_channel_map'], $_SESSION['lupo_bootstrap_log'], $_SESSION['lupo_run_done']);
                    header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=complete');
                    exit;
                }
                if (empty($api_key_errors)) {
                    $api_key_errors[] = 'Could not write config file.';
                }
            }
        }
    }
}

// ----- Step: complete (success screen; clear wizard session but keep logs for download)
if ($step === 'complete') {
    $complete_log = isset($_SESSION['lupo_run_log']) ? $_SESSION['lupo_run_log'] : array();
    $complete_install_type = isset($_SESSION['lupo_install_type']) ? $_SESSION['lupo_install_type'] : 'new';
    $complete_normalize_count = (int) (isset($_SESSION['lupo_normalize_count']) ? $_SESSION['lupo_normalize_count'] : 0);
    $tmp_map = isset($_SESSION['lupo_operator_channel_map']) ? $_SESSION['lupo_operator_channel_map'] : null;
    $complete_operator_channels = is_array($tmp_map) ? count($tmp_map) : 0;
    $tmp_tables = isset($_SESSION['lupo_install_livehelp_tables']) ? $_SESSION['lupo_install_livehelp_tables'] : null;
    $complete_legacy_dropped = is_array($tmp_tables) ? count($tmp_tables) : 0;
    foreach (array('lupo_install_db_vars', 'lupo_install_type', 'lupo_install_mode_choice', 'lupo_install_mode_warning', 'lupo_install_livehelp_tables', 'lupo_normalize_applied', 'lupo_normalize_count', 'lupo_operator_channel_map', 'lupo_run_done', 'lupo_config_site_name', 'lupo_config_base_url', 'lupo_config_admin_email', 'lupo_config_timezone', 'lupo_config_default_language', 'lupo_config_support_email', 'lupo_config_default_visitor_channel', 'lupo_config_enable_ai_channels', 'lupo_config_admin_password', 'lupo_api_key_anthropic', 'lupo_api_key_gemini', 'lupo_api_key_grok', 'lupo_api_key_deepseek', 'lupo_api_key_groq', 'lupo_api_key_openai', 'lupo_api_budget_anthropic', 'lupo_api_budget_gemini', 'lupo_api_budget_grok', 'lupo_api_budget_deepseek', 'lupo_api_budget_groq', 'lupo_api_budget_openai', 'lupo_api_custom_names', 'lupo_api_custom_keys', 'lupo_api_custom_budgets', 'lupo_api_config_target_dir', 'lupo_api_fallback_order', 'lupo_api_key_mode', 'lupo_llm_models') as $k) {
        unset($_SESSION[$k]);
    }
    if (empty($complete_log) && LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, $lupoWizardPublicPath) !== null) {
        $complete_log = array(array('ok', 'Installation completed.'));
    }
    $complete_config_log = isset($_SESSION['lupo_config_log']) ? $_SESSION['lupo_config_log'] : array();
    // Relative to install folder (same as $baseUrl . …); avoids duplicating SCRIPT_NAME in the href.
    $loginUrl = 'login.php';
}

// ----- Output HTML
$baseUrl = rtrim(dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : ''), '/');
if ($baseUrl === '') {
    $baseUrl = '';
} else {
    $baseUrl = $baseUrl . '/';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupopedia <?php echo htmlspecialchars($lupo_wizard_version); ?> — Install / Upgrade</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            max-width: 680px;
            margin: 2rem auto;
            padding: 0 1.25rem;
            color: #1a1a1a;
        }

        h1 {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }

        .wizard-progress {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1.5rem;
        }

        .wizard-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .wizard-card h2 {
            font-size: 1.2rem;
            margin-top: 0;
            margin-bottom: 0.5rem;
        }

        .wizard-card p {
            margin: 0.5rem 0;
        }

        .step {
            background: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 1.25rem;
            margin: 1.25rem 0;
        }

        .step.success {
            border-color: #198754;
            background: #d1e7dd;
        }

        .step.error {
            border-color: #dc3545;
            background: #f8d7da;
        }

        .step.warning {
            border-color: #ffc107;
            background: #fff3cd;
        }

        ul {
            margin: 0.5rem 0 0 1.25rem;
        }

        label {
            display: block;
            margin-top: 0.75rem;
            font-weight: 500;
        }

        input[type=text],
        input[type=password],
        input[type=email],
        select {
            width: 100%;
            max-width: 22rem;
            padding: 0.4rem 0.5rem;
            margin-top: 0.25rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button,
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
            margin-right: 0.5rem;
            margin-top: 0.5rem;
        }

        button:hover,
        .btn:hover {
            background: #0b5ed7;
            color: #fff;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #bb2d3b;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.85rem;
        }

        .log {
            font-family: monospace;
            font-size: 0.85rem;
            max-height: 20rem;
            overflow: auto;
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 0.75rem;
            margin-top: 0.5rem;
            border-radius: 4px;
        }

        .log .ok {
            color: #4ec9b0;
        }

        .log .ok::before {
            content: "\2713 ";
            color: #198754;
        }

        .log .skip {
            color: #dcdcaa;
        }

        .log .skip::before {
            content: "\26A0 ";
            color: #ffc107;
        }

        .log .error {
            color: #f48771;
        }

        .log .error::before {
            content: "\2717 ";
            color: #dc3545;
        }

        .log-section {
            margin-top: 1rem;
        }

        .progress-bar {
            height: 10px;
            background: #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
            margin: 1rem 0;
        }

        .progress-fill {
            height: 100%;
            background: #198754;
            transition: width 0.3s;
        }

        .run-steps {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin: 1rem 0;
            font-size: 0.9rem;
        }

        .run-steps span {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            background: #f0f0f0;
        }

        .run-steps span.done {
            background: #d1e7dd;
            color: #0f5132;
        }

        .run-steps span.skip {
            background: #fff3cd;
            color: #664d03;
        }

        .run-steps span.fail {
            background: #f8d7da;
            color: #842029;
        }

        .log-section h4 {
            font-size: 0.95rem;
            margin-bottom: 0.35rem;
        }

        .err {
            color: #dc3545;
            margin-top: 0.5rem;
        }

        .diag-ok {
            color: #198754;
            margin: 0.25rem 0;
        }

        .diag-warn {
            color: #856404;
            background: #fff3cd;
            padding: 0.25rem 0.5rem;
            margin: 0.25rem 0;
            border-radius: 4px;
        }

        .normalize-row-error {
            background: #f8d7da !important;
        }

        .normalize-row-dup {
            background: #fff3cd !important;
        }

        .normalize-warnings {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 0.75rem;
            margin: 0.75rem 0;
            font-size: 0.9rem;
        }

        .normalize-row-err {
            font-size: 0.8rem;
            color: #dc3545;
            margin-top: 0.25rem;
        }

        [title] {
            cursor: help;
        }

        .slug-tip {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.25rem;
        }

        .lupo-step-wrap {
            position: relative;
        }

        .lupo-processing-overlay {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 4px;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .lupo-processing-overlay.visible {
            display: flex;
        }

        .lupo-processing-overlay span {
            font-size: 1.1rem;
            color: #0d6efd;
            font-weight: 500;
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <h1>Lupopedia <?php echo htmlspecialchars($lupo_wizard_version); ?> — Install / Upgrade Wizard</h1>
    <p class="wizard-progress">Step <?php echo InstallWizardSteps::getCurrentStepIndex($step); ?> of
        <?php echo InstallWizardSteps::getTotalSteps(); ?>
    </p>

    <?php if ($step === 'welcome'): ?>
        <div class="wizard-card">
            <h2>Welcome</h2>
            <p>This wizard will install Lupopedia <?php echo htmlspecialchars($lupo_wizard_version); ?> or upgrade from
                Crafty Syntax 3.7.5. Two valid states only: <strong>New install</strong> or <strong>Upgrade</strong>. No
                Lupopedia→Lupopedia upgrade.</p>
            <p><strong>Important:</strong> Lupopedia must be installed in a subdirectory of your web root (e.g.,
                /lupopedia/). The project folder itself is the web-accessible directory. All URLs and paths will be relative
                to this subdirectory using LUPOPEDIA_PUBLIC_PATH.</p>
            <p><strong>Correct URL examples:</strong> https://example.com/lupopedia/ or https://localhost/lupopedia/</p>
            <p><strong>Requirements:</strong> PHP 5.3+, PDO MySQL, mysqli, JSON extension, writable project root, and a
                MySQL/MariaDB database. For upgrade: existing Crafty Syntax 3.7.5 data.</p>
            <div class="log-section">
                <h4>System diagnostics</h4>
                <ul class="diagnostics-list" style="list-style:none; padding:0; margin:0.5rem 0;">
                    <li class="diag-ok">&#10003; PHP <?php echo htmlspecialchars(phpversion()); ?> (5.3+ required)</li>
                    <li class="diag-ok">&#10003; pdo_mysql</li>
                    <li class="diag-ok">&#10003; mysqli</li>
                    <li class="diag-ok">&#10003; json</li>
                    <li class="diag-ok">&#10003; Project root writable</li>
                    <?php foreach ($preflight_warnings as $w): ?>
                        <li class="diag-warn">&#9888; <?php echo htmlspecialchars($w); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <form method="post" action="<?php echo htmlspecialchars($baseUrl . 'install.php?step=welcome'); ?>">
                <input type="hidden" name="lupo_csrf"
                    value="<?php echo htmlspecialchars(InstallWizardSecurity::getCsrfToken()); ?>">
                <input type="hidden" name="step" value="welcome">
                <input type="hidden" name="action" value="continue">
                <button type="submit">Continue</button>
            </form>
        </div>

    <?php elseif ($step === 'credentials'): ?>
        <div class="step lupo-step-wrap" id="lupo-credentials-step">
            <div class="lupo-processing-overlay" id="lupo-processing-overlay" aria-live="polite"><span>Processing…</span>
            </div>
            <h2>Database credentials</h2>
            <p>Enter connection details (or ensure Crafty Syntax <code>config.php</code> is in a standard location to
                auto-detect).</p>
            <p class="slug-tip" style="margin-bottom:0.5rem;">If you have the legacy Crafty Syntax app in an
                <code>/lh/</code> folder and see a white screen or &quot;Access denied&quot; from
                <code>lh/config_cslh.php</code>, that app uses its own config: edit <code>lh/config_cslh.php</code> (or
                <code>lh/config.php</code>) so its MySQL user and password match this database, or temporarily rename the
                <code>lh</code> folder during install.
            </p>
            <?php foreach ($errors as $e): ?>
                <p class="err"><?php echo htmlspecialchars($e); ?></p>
            <?php endforeach; ?>
            <?php if (!empty($errors)): ?>
                <p>
                <form method="post" action="<?php echo htmlspecialchars($baseUrl . 'install.php'); ?>" style="display:inline;">
                    <input type="hidden" name="action" value="start_over"><button type="submit" class="btn btn-secondary">Start
                        over</button>
                </form>
                </p>
            <?php endif; ?>
            <form method="post" action="<?php echo htmlspecialchars($baseUrl . 'install.php?step=credentials'); ?>"
                id="lupo-credentials-form">
                <input type="hidden" name="lupo_csrf"
                    value="<?php echo htmlspecialchars(InstallWizardSecurity::getCsrfToken()); ?>">
                <input type="hidden" name="step" value="credentials">
                <label>Host <input type="text" name="db_host"
                        value="<?php echo htmlspecialchars(isset($_POST['db_host']) ? $_POST['db_host'] : 'localhost'); ?>"
                        required></label>
                <label>Port <input type="text" name="db_port"
                        value="<?php echo htmlspecialchars(isset($_POST['db_port']) ? $_POST['db_port'] : '3306'); ?>"></label>
                <label>Database <input type="text" name="db_name"
                        value="<?php echo htmlspecialchars(isset($_POST['db_name']) ? $_POST['db_name'] : ''); ?>"
                        required></label>
                <label>User <input type="text" name="db_user"
                        value="<?php echo htmlspecialchars(isset($_POST['db_user']) ? $_POST['db_user'] : ''); ?>"
                        required></label>
                <label>Password <input type="password" name="db_password" value=""></label>
                <fieldset style="margin:0.75rem 0 0.5rem 0; padding:0.75rem; border:1px solid #ddd; border-radius:6px;">
                    <legend style="padding:0 0.25rem;">Install mode</legend>
                    <label style="display:block; margin-bottom:0.5rem;">
                        <input type="radio" name="install_mode" value="new" <?php echo $selected_install_mode === 'new' ? 'checked' : ''; ?>>
                        New install
                    </label>
                    <label style="display:block;">
                        <input type="radio" name="install_mode" value="upgrade" <?php echo $selected_install_mode === 'upgrade' ? 'checked' : ''; ?>>
                        Upgrade existing (Crafty Syntax 3.7.5 data)
                    </label>
                    <p class="slug-tip" style="margin:0.5rem 0 0 0;">This selection controls the installer path. Upgrade requires legacy <code>livehelp_*</code> tables in the selected database.</p>
                </fieldset>
                <label>Table prefix <input type="text" name="table_prefix"
                        value="<?php echo htmlspecialchars(isset($_POST['table_prefix']) ? $_POST['table_prefix'] : 'lupo_'); ?>"
                        placeholder="lupo_" title="Lowercase letters, digits, underscores only. Default: lupo_"></label>
                <label style="display:block; margin-top:0.75rem;"><input type="checkbox" name="drop_livehelp_tables"
                        value="1" <?php echo (isset($_POST['drop_livehelp_tables']) && $_POST['drop_livehelp_tables'] === '1') ? ' checked' : ''; ?>> Drop deprecated Crafty (<code>livehelp_*</code>) tables after import</label>
                <p class="slug-tip" style="margin-top:0.25rem;">Only applies to upgrades. Unchecked by default; leave
                    unchecked to keep legacy tables for reference or re-import.</p>
                <p style="margin-top:1rem;"><button type="submit" id="lupo-connect-btn">Connect and continue</button></p>
            </form>
            <p><a href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=welcome'); ?>"
                    class="btn btn-secondary">Back</a></p>
        </div>
        <script>
            (function () {
                var form = document.getElementById('lupo-credentials-form');
                var btn = document.getElementById('lupo-connect-btn');
                var overlay = document.getElementById('lupo-processing-overlay');
                if (form && btn && overlay) {
                    form.onsubmit = function () {
                        btn.disabled = true;
                        overlay.className = 'lupo-processing-overlay visible';
                        return true;
                    };
                }
            })();
        </script>

    <?php elseif ($step === 'bootstrap'): ?>
        <div class="wizard-card step success">
            <h2>Bootstrap complete</h2>
            <?php foreach ($errors as $e): ?>
                <p class="err"><?php echo htmlspecialchars($e); ?></p>
            <?php endforeach; ?>
            <p>Schema (install + seed) and reserved system channels (0, 1, 42, 51) have been created. Continue to identity
                normalization.</p>
            <?php if (!empty($_SESSION['lupo_bootstrap_log'])): ?>
                <div class="log-section">
                    <h4>Bootstrap log</h4>
                    <pre class="log"><?php
                    foreach ($_SESSION['lupo_bootstrap_log'] as $entry) {
                        $c = $entry[0];
                        $t = htmlspecialchars($entry[1]);
                        $ts = isset($entry[2]) ? htmlspecialchars($entry[2]) . ' ' : '';
                        echo "<span class=\"{$c}\">[{$c}] {$ts}{$t}</span>\n";
                    }
                    ?></pre>
                </div>
            <?php endif; ?>
            <form method="post" action="<?php echo htmlspecialchars($baseUrl . 'install.php?step=bootstrap'); ?>">
                <input type="hidden" name="lupo_csrf"
                    value="<?php echo htmlspecialchars(InstallWizardSecurity::getCsrfToken()); ?>">
                <input type="hidden" name="step" value="bootstrap">
                <input type="hidden" name="action" value="continue">
                <button type="submit">Continue to Identity Normalization</button>
            </form>
        </div>

    <?php elseif ($step === 'normalize'): ?>
        <div class="step warning">
            <h2>Identity normalization</h2>
            <p><strong>Reserved system channels (0, 1, 42, 51) have been created.</strong> Department 0 is the global system
                department (not department 1). Channel 1 is the Administration channel. Next: resolve identities for
                <strong>Crafty operators only</strong> (isoperator = Y). Visitor sessions are not changed. Crafty Syntax
                uses username/password; Lupopedia uses email/password and requires a unique email per operator.
                <strong>Email</strong> is kept as the real email (e.g. <code>johndoe@domainname.com</code>).
                <strong>Username</strong> for operators becomes slug format (e.g. <code>johndoe</code> →
                <code>johndoe-at-domainname-com</code>). You can correct any proposed email below. All emails must be unique
                and valid before you can continue.
            </p>
            <?php if (!empty($normalize_warnings)): ?>
                <div class="normalize-warnings">
                    <strong>Warnings</strong>
                    <ul style="margin:0.25rem 0 0 1.25rem;">
                        <?php foreach ($normalize_warnings as $w): ?>
                            <li><?php echo htmlspecialchars($w); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php foreach ($errors as $e): ?>
                <p class="err"><?php echo htmlspecialchars($e); ?></p>
            <?php endforeach; ?>
            <?php if (!empty($normalize_identities)): ?>
                <form method="post" action="<?php echo htmlspecialchars($baseUrl . 'install.php?step=normalize'); ?>">
                    <input type="hidden" name="lupo_csrf"
                        value="<?php echo htmlspecialchars(InstallWizardSecurity::getCsrfToken()); ?>">
                    <input type="hidden" name="step" value="normalize">
                    <input type="hidden" name="action" value="apply_normalization">
                    <p><strong>Operators only — proposed slug and resolved email</strong></p>
                    <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
                        <thead>
                            <tr style="border-bottom:1px solid #ccc;">
                                <th style="text-align:left;">ID</th>
                                <th style="text-align:left;">Username</th>
                                <th style="text-align:left;">Current email</th>
                                <th style="text-align:left;">Op</th>
                                <th style="text-align:left;">Proposed slug</th>
                                <th style="text-align:left;">Resolved email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $duplicate_indices = array();
                            foreach ($normalize_duplicates as $indices) {
                                foreach ($indices as $i) {
                                    $duplicate_indices[$i] = true;
                                }
                            }
                            foreach ($normalize_identities as $i => $row):
                                $isDup = isset($duplicate_indices[$i]);
                                $rowErrors = isset($normalize_validation_by_id[$row['user_id']]) ? $normalize_validation_by_id[$row['user_id']] : array();
                                $hasError = $isDup || !empty($rowErrors);
                                ?>
                                <tr style="border-bottom:1px solid #eee;"
                                    class="<?php echo !empty($rowErrors) ? 'normalize-row-error' : ($isDup ? 'normalize-row-dup' : ''); ?>">
                                    <td><?php echo (int) $row['user_id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo $row['isoperator'] ? 'Y' : 'N'; ?></td>
                                    <td><code style="font-size:0.8rem;"
                                            title="Slug rules: @ → -at-, . → -dot-, lowercase, a-z 0-9 and hyphen only; suffix -at-domainname-com"><?php echo htmlspecialchars($row['proposed_email']); ?></code><br><span
                                            class="slug-tip">Slug: @→-at- .→-dot-</span></td>
                                    <td>
                                        <input type="text" id="email_<?php echo (int) $row['user_id']; ?>"
                                            name="email_<?php echo (int) $row['user_id']; ?>"
                                            value="<?php echo htmlspecialchars(isset($_POST['email_' . $row['user_id']]) ? $_POST['email_' . $row['user_id']] : $row['proposed_email']); ?>"
                                            style="width:100%; max-width:18rem;" required
                                            data-proposed="<?php echo htmlspecialchars($row['proposed_email']); ?>"
                                            data-id="<?php echo (int) $row['user_id']; ?>">
                                        <div style="margin-top:0.25rem;">
                                            <button type="button" class="btn btn-sm btn-secondary"
                                                onclick="var i=document.getElementById('email_<?php echo (int) $row['user_id']; ?>'); i.value=i.getAttribute('data-proposed');">Use
                                                slug</button>
                                            <button type="button" class="btn btn-sm btn-secondary"
                                                onclick="document.getElementById('email_<?php echo (int) $row['user_id']; ?>').value='archived-<?php echo (int) $row['user_id']; ?>@removed.local';">Mark
                                                archived</button>
                                        </div>
                                        <?php if (!empty($rowErrors)): ?>
                                            <div class="normalize-row-err"><?php echo htmlspecialchars(implode(' ', $rowErrors)); ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (!empty($normalize_duplicates)): ?>
                        <p class="err" style="margin-top:1rem;"><strong>Duplicate emails detected.</strong> Each account must have a
                            unique email. Keep one as primary and assign new unique emails to the others, or use e.g.
                            <code>archived-123@removed.local</code> for accounts to exclude.
                        </p>
                    <?php endif; ?>
                    <p style="margin-top:1rem;"><strong>livehelp_users is updated only when you click &quot;Apply normalization
                            and continue&quot;.</strong> Fix any empty, invalid, or duplicate emails above first.</p>
                    <p style="margin-top:1rem;"><button type="submit">Apply normalization and continue</button></p>
                </form>
            <?php else: ?>
                <p>No users found in <code>livehelp_users</code>. You can continue to the next step.</p>
                <form method="post" action="<?php echo htmlspecialchars($baseUrl . 'install.php?step=normalize'); ?>"
                    style="display:inline;">
                    <input type="hidden" name="lupo_csrf"
                        value="<?php echo htmlspecialchars(InstallWizardSecurity::getCsrfToken()); ?>">
                    <input type="hidden" name="step" value="normalize">
                    <input type="hidden" name="action" value="apply_normalization">
                    <button type="submit" class="btn">Continue to confirm</button>
                </form>
            <?php endif; ?>
            <p><a href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=credentials'); ?>" class="btn">Back to
                    credentials</a></p>
        </div>

    <?php elseif ($step === 'confirm'): ?>
        <div class="step lupo-step-wrap <?php echo (isset($_SESSION['lupo_install_type']) ? $_SESSION['lupo_install_type'] : '') === 'upgrade' ? 'warning' : ''; ?>"
            id="lupo-confirm-step">
            <div class="lupo-processing-overlay" id="lupo-run-overlay" aria-live="polite"><span>Running installation… This
                    may take a minute.</span></div>
            <h2>Confirm</h2>
            <?php if (!empty($errors)): ?>
                <div class="step error" style="margin-bottom:1rem;">
                    <p><strong>Something went wrong:</strong></p>
                    <ul class="err" style="list-style:disc; margin-left:1.5rem;">
                        <?php foreach ($errors as $e): ?>
                            <li><?php echo htmlspecialchars($e); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <p>Fix the issue and click &ldquo;Run installation&rdquo; again, or <a
                            href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=credentials'); ?>">go back to
                            credentials</a>.</p>
                </div>
            <?php endif; ?>
            <?php if ((isset($_SESSION['lupo_install_type']) ? $_SESSION['lupo_install_type'] : '') === 'upgrade'): ?>
                <p><strong>Upgrade from Crafty Syntax 3.7.5</strong></p>
                <?php if (!empty($_SESSION['lupo_install_mode_warning'])): ?>
                    <p class="slug-tip"><strong>Notice:</strong> <?php echo htmlspecialchars($_SESSION['lupo_install_mode_warning']); ?></p>
                <?php endif; ?>
                <p>Reserved system channels (0, 1, 42, 51) and schema were created before normalization. Identity normalization
                    applied. The wizard will now:</p>
                <ol>
                    <li>Set <code>lupo_actors</code> so the next actor_id is at least 10000 (reserved ID doctrine)</li>
                    <li>Run <code>import_from_old_crafty_syntax.sql</code></li>
                    <li>Create personal channels and assign captain roles (<code>lupo_channels</code>,
                        <code>lupo_actor_channel_roles</code>)
                    </li>
                    <?php if (!empty($_SESSION['lupo_drop_livehelp_tables'])): ?>
                        <li>Run <code>drop_old_crafty_syntax_tables.sql</code> (drop deprecated <code>livehelp_*</code> tables)</li>
                    <?php else: ?>
                        <li>Skip dropping legacy <code>livehelp_*</code> tables (option unchecked at credentials)</li>
                    <?php endif; ?>
                    <li>Write <code>lupopedia-config.php</code></li>
                    <li>Redirect to login.php</li>
                </ol>
                <p style="font-size:0.9rem; color:#666;"><strong>Doctrine:</strong> Crafty Syntax users are migrated into the
                    Lupopedia actor system. Actor IDs 0–9999 are reserved for system and AI agents. Human actors begin at ID
                    10000.</p>
                <p style="font-size:0.9rem; color:#666;">Already done: Create reserved system channels (0, 1, 42, 51) before
                    normalization → Identity normalization.</p>
            <?php else: ?>
                <p><strong>New install</strong></p>
                <?php if (!empty($_SESSION['lupo_install_mode_warning'])): ?>
                    <p class="slug-tip"><strong>Notice:</strong> <?php echo htmlspecialchars($_SESSION['lupo_install_mode_warning']); ?></p>
                <?php endif; ?>
                <p>You selected New install. The wizard will:</p>
                <ol>
                    <li>Run <code>database/lupopedia/mysql/install/install_new_lupopedia.sql</code></li>
                    <li>Run consolidated seed (<code>install/seed_lupopedia_4_1_0.sql</code> if present, else <code>mysql/seed/seed_4.1.0.sql</code>)</li>
                    <li>Create reserved system channels (0, 1, 42, 51)</li>
                    <li>Write <code>lupopedia-config.php</code></li>
                    <li>Redirect to login.php</li>
                </ol>
            <?php endif; ?>
            <form method="post" action="<?php echo htmlspecialchars($baseUrl . 'install.php?step=confirm'); ?>"
                id="lupo-run-form">
                <input type="hidden" name="lupo_csrf"
                    value="<?php echo htmlspecialchars(InstallWizardSecurity::getCsrfToken()); ?>">
                <input type="hidden" name="step" value="confirm">
                <input type="hidden" name="action" value="run">
                <p style="margin-top:1rem;"><button type="submit" id="lupo-run-btn">Run installation</button></p>
            </form>
            <script>
                (function () {
                    var form = document.getElementById('lupo-run-form');
                    var btn = document.getElementById('lupo-run-btn');
                    var overlay = document.getElementById('lupo-run-overlay');
                    if (form && btn && overlay) {
                        form.onsubmit = function () {
                            btn.disabled = true;
                            overlay.className = 'lupo-processing-overlay visible';
                            return true;
                        };
                    }
                })();
            </script>
            <p><a href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=credentials'); ?>" class="btn">Back to
                    credentials</a></p>
            <?php if (!empty($errors)): ?>
                <p>
                <form method="post" action="<?php echo htmlspecialchars($baseUrl . 'install.php'); ?>" style="display:inline;">
                    <input type="hidden" name="action" value="start_over"><button type="submit" class="btn btn-danger">Start
                        over</button>
                </form>
                </p>
            <?php endif; ?>
            <?php if ((isset($_SESSION['lupo_install_type']) ? $_SESSION['lupo_install_type'] : '') === 'upgrade'): ?>
                <p><a href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=normalize'); ?>" class="btn">Back to
                        identity normalization</a></p>
            <?php endif; ?>
        </div>

    <?php elseif ($step === 'run'): ?>
        <div class="wizard-card step <?php echo !empty($errors) ? 'error' : 'success'; ?>">
            <h2>Run result</h2>
            <?php if (!empty($log)): ?>
                <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"
                    title="Run complete">
                    <div class="progress-fill" style="width:100%;"></div>
                </div>
                <?php
                $hasErr = false;
                foreach ($log as $e) {
                    if ((isset($e[0]) ? $e[0] : '') === 'error') {
                        $hasErr = true;
                        break;
                    }
                }
                $stepClass = $hasErr ? 'fail' : 'done';
                $labels = (isset($_SESSION['lupo_install_type']) ? $_SESSION['lupo_install_type'] : '') === 'upgrade'
                    ? array('Install', 'Seed', 'Reserved', 'Import', 'Operator channels', 'Drop')
                    : ['Install', 'Seed', 'Reserved'];
                ?>
                <div class="run-steps">
                    <?php foreach ($labels as $l): ?>
                        <span class="<?php echo $stepClass; ?>">&#10003; <?php echo htmlspecialchars($l); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <?php foreach ($errors as $e): ?>
                    <p class="err"><?php echo htmlspecialchars($e); ?></p>
                <?php endforeach; ?>
                <p>
                    <a href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=confirm'); ?>" class="btn">Retry step</a>
                    <a href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=download_log&which=run'); ?>"
                        class="btn btn-secondary" download>Download log</a>
                <form method="post" action="<?php echo htmlspecialchars($baseUrl . 'install.php'); ?>" style="display:inline;">
                    <input type="hidden" name="action" value="start_over">
                    <button type="submit" class="btn btn-secondary">Start over</button>
                </form>
                </p>
            <?php endif; ?>
            <?php if (!empty($log)): ?>
                <?php
                $runSep = '--- Run step';
                $bootstrapPart = array();
                $runPart = array();
                $seen = false;
                foreach ($log as $entry) {
                    $msg = isset($entry[1]) ? $entry[1] : '';
                    if (strpos($msg, $runSep) === 0) {
                        $seen = true;
                        continue;
                    }
                    if ($seen) {
                        $runPart[] = $entry;
                    } else {
                        $bootstrapPart[] = $entry;
                    }
                }
                $logLine = function ($e) {
                    $c = $e[0];
                    $t = htmlspecialchars($e[1]);
                    $ts = isset($e[2]) ? htmlspecialchars($e[2]) . ' ' : '';
                    return "<span class=\"{$c}\">[{$c}] {$ts}{$t}</span>";
                };
                if (!empty($bootstrapPart)): ?>
                    <div class="log-section">
                        <h4>Bootstrap (install + seed + reserved channels)</h4>
                        <pre class="log"><?php foreach ($bootstrapPart as $e) {
                            echo $logLine($e) . "\n";
                        } ?></pre>
                    </div>
                <?php endif;
                if (!empty($runPart)): ?>
                    <div class="log-section">
                        <h4>Import, personal channels and roles, drop legacy</h4>
                        <pre class="log"><?php foreach ($runPart as $e) {
                            echo $logLine($e) . "\n";
                        } ?></pre>
                    </div>
                <?php endif;
                if (empty($bootstrapPart) && empty($runPart)): ?>
                    <pre class="log"><?php foreach ($log as $e) {
                        echo $logLine($e) . "\n";
                    } ?></pre>
                <?php endif; ?>
            <?php endif; ?>
            <p><strong>Run complete.</strong> Continue to set site options and write config.</p>
            <p>
                <a href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=config'); ?>" class="btn">Continue to
                    Config</a>
                <a href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=confirm'); ?>"
                    class="btn btn-secondary">Back to confirm</a>
                <a href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=download_log&which=run'); ?>"
                    class="btn btn-secondary" download>Download run log</a>
            </p>
    <?php elseif ($step === 'config'): ?>
        <div class="wizard-card">
            <h2>Site configuration</h2>
            <p>Set site options first. API key collection is the next step. The wizard writes <code>lupopedia-config.php</code> after the API key step.</p>
            <p class="slug-tip">When you submit this step, the wizard also creates runtime directories (<code>cache/</code>, <code>logs/</code>, <code>uploads/</code>, <code>tmp/</code>) if they don't exist. On Apache with a writable docroot, writes <code>.htaccess</code> rewrite rules (Softaculous packages omit hidden files so FTP does not skip them).</p>
            <?php foreach ($config_errors as $e): ?>
                <p class="err"><?php echo htmlspecialchars($e); ?></p>
            <?php endforeach; ?>
            <form method="post" action="<?php echo htmlspecialchars($baseUrl . 'install.php?step=config'); ?>">
                <input type="hidden" name="lupo_csrf"
                    value="<?php echo htmlspecialchars(InstallWizardSecurity::getCsrfToken()); ?>">
                <input type="hidden" name="step" value="config">
                <input type="hidden" name="action" value="continue_api_keys">
                <label>Site name <input type="text" name="site_name"
                        value="<?php echo htmlspecialchars($config_values['site_name']); ?>" required></label>
                <label>Base URL (must end with /) <input type="text" name="base_url"
                        value="<?php echo htmlspecialchars($config_values['base_url']); ?>"
                        placeholder="/path/to/lupopedia/" required></label>
                <label>Admin email <input type="email" name="admin_email"
                        value="<?php echo htmlspecialchars($config_values['admin_email']); ?>" required></label>
                <?php $is_new_install = (isset($_SESSION['lupo_install_type']) ? $_SESSION['lupo_install_type'] : '') === 'new'; ?>
                <label>Admin password (user id 10000, captain on channels 0 &amp; 42, global admin) <input type="password"
                        name="admin_password" value="" minlength="8" <?php echo $is_new_install ? 'required' : ''; ?>
                        placeholder="At least 8 characters"></label>
                <label>Confirm password <input type="password" name="admin_password_confirm" value="" minlength="8" <?php echo $is_new_install ? 'required' : ''; ?> placeholder="Same as above"></label>
                <?php if (!$is_new_install): ?>
                    <p class="slug-tip">On upgrade, password is optional; if set, the main admin user (10000) will be created or
                        updated with this email and password.</p>
                <?php endif; ?>
                <label>Timezone
                    <select name="timezone">
                        <?php
                        $tzs = array('UTC', 'America/New_York', 'America/Chicago', 'America/Denver', 'America/Los_Angeles', 'Europe/London', 'Europe/Paris', 'Europe/Berlin', 'Asia/Tokyo', 'Australia/Sydney');
                        foreach ($tzs as $tz):
                            ?>
                            <option value="<?php echo htmlspecialchars($tz); ?>" <?php echo $config_values['timezone'] === $tz ? 'selected' : ''; ?>><?php echo htmlspecialchars($tz); ?></option><?php endforeach; ?>
                    </select>
                </label>
                <label>Default language <input type="text" name="default_language"
                        value="<?php echo htmlspecialchars($config_values['default_language']); ?>" required></label>
                <label>Support email (optional) <input type="email" name="support_email"
                        value="<?php echo htmlspecialchars($config_values['support_email']); ?>" placeholder=""></label>
                <input type="hidden" name="default_visitor_channel" value="1">
                <p class="diag-ok">Default channel for new visitors: 1 (department 1).</p>
                <input type="hidden" name="enable_ai_channels" value="1">
                <p class="diag-ok">AI agent channels: enabled.</p>
                <p style="margin-top:1rem;"><button type="submit">Continue to API keys</button></p>
            </form>
            <p><a href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=run'); ?>" class="btn btn-secondary">Back to run result</a></p>
        </div>

    <?php elseif ($step === 'api_keys'): ?>
        <div class="wizard-card">
            <h2>API keys &amp; budget configuration</h2>
            <p>Each installation manages its own keys and budgets independently. Lupopedia will rotate providers and respect your monthly caps.</p>
            <p class="slug-tip">Security: use provider-scoped keys where available. Do not commit generated config files to version control.</p>
            <?php foreach ($api_key_errors as $e): ?>
                <p class="err"><?php echo htmlspecialchars($e); ?></p>
            <?php endforeach; ?>
            <?php if ($api_existing_config_path !== null && is_file($api_existing_config_path)): ?>
                <div class="step warning">
                    <p><strong>Existing config detected:</strong> <code><?php echo htmlspecialchars($api_existing_config_path); ?></code></p>
                    <p class="slug-tip">Choose whether to keep existing API keys or overwrite them with values entered below.</p>
                </div>
            <?php endif; ?>
            <form method="post" action="<?php echo htmlspecialchars($baseUrl . 'install.php?step=api_keys'); ?>">
                <input type="hidden" name="lupo_csrf"
                    value="<?php echo htmlspecialchars(InstallWizardSecurity::getCsrfToken()); ?>">
                <input type="hidden" name="step" value="api_keys">
                <input type="hidden" name="action" value="write_config">

                <?php if ($api_existing_config_path !== null && is_file($api_existing_config_path)): ?>
                    <fieldset style="margin:0.75rem 0 0.5rem 0; padding:0.75rem; border:1px solid #ddd; border-radius:6px;">
                        <legend style="padding:0 0.25rem;">API key mode</legend>
                        <label style="display:block; margin-bottom:0.5rem;">
                            <input type="radio" name="api_key_mode" value="keep" <?php echo $api_key_mode === 'keep' ? 'checked' : ''; ?>>
                            Keep existing keys from current <code>lupopedia-config.php</code>
                        </label>
                        <label style="display:block;">
                            <input type="radio" name="api_key_mode" value="overwrite" <?php echo $api_key_mode === 'overwrite' ? 'checked' : ''; ?>>
                            Overwrite keys using values entered below
                        </label>
                    </fieldset>
                <?php else: ?>
                    <input type="hidden" name="api_key_mode" value="overwrite">
                <?php endif; ?>

                <label>Config write directory (web root)
                    <input type="text" name="config_target_dir" value="<?php echo htmlspecialchars($api_config_target_dir); ?>" placeholder="<?php echo isset($_SERVER['DOCUMENT_ROOT']) ? htmlspecialchars((string) $_SERVER['DOCUMENT_ROOT']) : ''; ?>">
                </label>
                <p class="slug-tip">Only <code>lupopedia-config.php</code> is written here. The application and runtime data stay above web root.</p>

                <label>Google Gemini API key (default provider)
                    <input type="password" name="api_gemini" value="<?php echo htmlspecialchars($api_key_values['gemini']); ?>" autocomplete="off">
                </label>
                <label>Gemini monthly budget cap (USD, 0 = unlimited/free-tier only)
                    <input type="number" step="0.01" min="0" name="api_budget_gemini" value="<?php echo htmlspecialchars($api_key_values['budget_gemini']); ?>">
                </label>

                <label>DeepSeek API key (default provider)
                    <input type="password" name="api_deepseek" value="<?php echo htmlspecialchars($api_key_values['deepseek']); ?>" autocomplete="off">
                </label>
                <label>DeepSeek monthly budget cap (USD, 0 = unlimited/free-tier only)
                    <input type="number" step="0.01" min="0" name="api_budget_deepseek" value="<?php echo htmlspecialchars($api_key_values['budget_deepseek']); ?>">
                </label>

                <label>Groq API key (default provider)
                    <input type="password" name="api_groq" value="<?php echo htmlspecialchars($api_key_values['groq']); ?>" autocomplete="off">
                </label>
                <label>Groq monthly budget cap (USD, 0 = unlimited/free-tier only)
                    <input type="number" step="0.01" min="0" name="api_budget_groq" value="<?php echo htmlspecialchars($api_key_values['budget_groq']); ?>">
                </label>

                <label>Anthropic (Claude) API key (optional)
                    <input type="password" name="api_anthropic" value="<?php echo htmlspecialchars($api_key_values['anthropic']); ?>" autocomplete="off">
                </label>
                <label>Anthropic monthly budget cap (USD, 0 = unlimited/free-tier only)
                    <input type="number" step="0.01" min="0" name="api_budget_anthropic" value="<?php echo htmlspecialchars($api_key_values['budget_anthropic']); ?>">
                </label>

                <label>Grok / xAI API key (optional)
                    <input type="password" name="api_grok" value="<?php echo htmlspecialchars($api_key_values['grok']); ?>" autocomplete="off">
                </label>
                <label>Grok monthly budget cap (USD, 0 = unlimited/free-tier only)
                    <input type="number" step="0.01" min="0" name="api_budget_grok" value="<?php echo htmlspecialchars($api_key_values['budget_grok']); ?>">
                </label>

                <label>OpenAI API key (optional last-resort fallback)
                    <input type="password" name="api_openai" value="<?php echo htmlspecialchars($api_key_values['openai']); ?>" autocomplete="off">
                </label>
                <label>OpenAI monthly budget cap (USD, 0 = unlimited/free-tier only)
                    <input type="number" step="0.01" min="0" name="api_budget_openai" value="<?php echo htmlspecialchars($api_key_values['budget_openai']); ?>">
                </label>

                <div class="log-section">
                    <h4>Additional providers</h4>
                    <p class="slug-tip">Add optional providers (for example OpenRouter, GitHub Models, etc.). Name should be lowercase letters/numbers/underscore friendly.</p>
                    <div id="custom-provider-list">
                        <?php
                        $customProviderRows = max(count($api_custom_names), 1);
                        for ($cp = 0; $cp < $customProviderRows; $cp++):
                            $cpName = isset($api_custom_names[$cp]) ? trim((string) $api_custom_names[$cp]) : '';
                            $cpKey = isset($api_custom_keys[$cp]) ? trim((string) $api_custom_keys[$cp]) : '';
                            $cpBudget = isset($api_custom_budgets[$cp]) ? trim((string) $api_custom_budgets[$cp]) : '15';
                            if ($customProviderRows === 1 && $cpName === '' && $cpKey === '') {
                                $cpBudget = '15';
                            }
                            ?>
                            <div class="custom-provider-row" style="border:1px solid #ddd; padding:0.75rem; margin-bottom:0.75rem; border-radius:6px;">
                                <label>Provider name
                                    <input type="text" name="custom_provider_name[]" value="<?php echo htmlspecialchars($cpName); ?>" placeholder="openrouter">
                                </label>
                                <label>API key
                                    <input type="password" name="custom_provider_key[]" value="<?php echo htmlspecialchars($cpKey); ?>" autocomplete="off">
                                </label>
                                <label>Monthly budget cap (USD, 0 = unlimited)
                                    <input type="number" step="0.01" min="0" name="custom_provider_budget[]" value="<?php echo htmlspecialchars($cpBudget); ?>">
                                </label>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <button type="button" id="add-custom-provider" class="btn btn-secondary">Add provider</button>
                </div>

                <fieldset style="margin:1rem 0; padding:0.75rem; border:1px solid #ddd; border-radius:6px;">
                    <legend style="padding:0 0.25rem;">LLM model configuration</legend>
                    <p class="slug-tip">Set model names per provider and request class. These values are written to lupopedia-config.php and used to seed agent_llm_configs.</p>
                    <?php
                    $llmUiProviders = array(
                        'gemini' => 'Google Gemini',
                        'deepseek' => 'DeepSeek',
                        'groq' => 'Groq',
                        'anthropic' => 'Anthropic',
                        'grok' => 'Grok xAI',
                        'openai' => 'OpenAI',
                    );
                    foreach ($llmUiProviders as $llmSlug => $llmLabel):
                        $llmRow = isset($llm_model_values[$llmSlug]) && is_array($llm_model_values[$llmSlug]) ? $llm_model_values[$llmSlug] : array();
                        ?>
                        <div style="border:1px solid #eee; padding:0.75rem; margin-bottom:0.75rem; border-radius:6px;">
                            <strong><?php echo htmlspecialchars($llmLabel); ?></strong>
                            <label>Default model
                                <input type="text" name="model_<?php echo htmlspecialchars($llmSlug); ?>_default" value="<?php echo htmlspecialchars(isset($llmRow['default']) ? $llmRow['default'] : ''); ?>">
                            </label>
                            <label>Complex requests model
                                <input type="text" name="model_<?php echo htmlspecialchars($llmSlug); ?>_complex" value="<?php echo htmlspecialchars(isset($llmRow['complex']) ? $llmRow['complex'] : ''); ?>">
                            </label>
                            <label>Audit requests model
                                <input type="text" name="model_<?php echo htmlspecialchars($llmSlug); ?>_audit" value="<?php echo htmlspecialchars(isset($llmRow['audit']) ? $llmRow['audit'] : ''); ?>">
                            </label>
                            <label>Temperature (0-2)
                                <input type="number" step="0.01" min="0" max="2" name="model_<?php echo htmlspecialchars($llmSlug); ?>_temperature" value="<?php echo htmlspecialchars(isset($llmRow['temperature']) ? $llmRow['temperature'] : '0.7'); ?>">
                            </label>
                            <label>Max tokens
                                <input type="number" step="1" min="1" name="model_<?php echo htmlspecialchars($llmSlug); ?>_max_tokens" value="<?php echo htmlspecialchars(isset($llmRow['max_tokens']) ? $llmRow['max_tokens'] : '2048'); ?>">
                            </label>
                            <label style="display:block; margin-top:0.5rem;">
                                <input type="checkbox" name="model_<?php echo htmlspecialchars($llmSlug); ?>_reasoning_mode" value="1" <?php echo (!empty($llmRow['reasoning_mode'])) ? 'checked' : ''; ?>>
                                Prefer reasoning model when provider supports it (e.g. deepseek-reasoner)
                            </label>
                        </div>
                    <?php endforeach; ?>
                </fieldset>

                <label>Fallback order (comma-separated provider keys)
                    <input type="text" name="fallback_order" value="<?php echo htmlspecialchars($api_key_values['fallback_order']); ?>" placeholder="gemini,deepseek,groq,anthropic,grok,openai,custom_provider">
                </label>

                <div class="log-section">
                    <h4>Provider dashboards</h4>
                    <ul>
                        <li><a href="https://console.anthropic.com/" target="_blank" rel="noopener">Anthropic console</a></li>
                        <li><a href="https://aistudio.google.com/" target="_blank" rel="noopener">Gemini API key manager</a></li>
                        <li><a href="https://console.groq.com/" target="_blank" rel="noopener">Groq console</a></li>
                        <li><a href="https://platform.deepseek.com/" target="_blank" rel="noopener">DeepSeek platform</a></li>
                        <li><a href="https://platform.openai.com/" target="_blank" rel="noopener">OpenAI platform</a></li>
                    </ul>
                </div>
                <p style="margin-top:1rem;"><button type="submit">Write config and finish</button></p>
            </form>
            <p><a href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=config'); ?>" class="btn btn-secondary">Back to site configuration</a></p>
            <script>
                (function () {
                    var btn = document.getElementById('add-custom-provider');
                    var list = document.getElementById('custom-provider-list');
                    if (!btn || !list) {
                        return;
                    }
                    btn.onclick = function () {
                        var wrapper = document.createElement('div');
                        wrapper.className = 'custom-provider-row';
                        wrapper.style.border = '1px solid #ddd';
                        wrapper.style.padding = '0.75rem';
                        wrapper.style.marginBottom = '0.75rem';
                        wrapper.style.borderRadius = '6px';
                        wrapper.innerHTML = '' +
                            '<label>Provider name<input type="text" name="custom_provider_name[]" placeholder="openrouter"></label>' +
                            '<label>API key<input type="password" name="custom_provider_key[]" autocomplete="off"></label>' +
                            '<label>Monthly budget cap (USD, 0 = unlimited)<input type="number" step="0.01" min="0" name="custom_provider_budget[]" value="15"></label>';
                        list.appendChild(wrapper);
                    };
                })();
            </script>
        </div>

    <?php elseif ($step === 'complete'): ?>
        <div class="wizard-card step success">
            <h2>Installation complete</h2>
            <p>Lupopedia has been installed successfully. <code>lupopedia-config.php</code> was written to your configured web directory and protected with restrictive permissions.</p>
            <p class="slug-tip"><strong>Security warning:</strong> <code>lupopedia-config.php</code> contains API keys. Do not make it publicly accessible.</p>
            <p class="slug-tip">On <strong>Apache</strong>, the wizard also writes <code>.htaccess</code> (document root) and
                <code>database/.htaccess</code> when the filesystem allows. Softaculous-style zip packages omit these hidden files so FTP uploads do not skip them.
                On <strong>Nginx</strong> or <strong>IIS</strong>, map the same intent in <code>nginx.conf</code> or <code>web.config</code> (rewrite to <code>index.php</code>, deny direct access to <code>includes</code> PHP, block web reads of <code>database/</code>); use your hoster&rsquo;s documentation or a working Apache tree as reference.</p>
            <div class="log-section">
                <h4>Summary</h4>
                <ul style="list-style:none; padding:0;">
                    <li><strong>Install type:</strong> <?php echo htmlspecialchars($complete_install_type); ?></li>
                    <li class="diag-ok"><strong>Config:</strong> <code>lupopedia-config.php</code> is active; Crafty
                        <code>config.php</code> has been removed so only one config remains.
                    </li>
                    <?php if ($complete_install_type === 'upgrade'): ?>
                        <li><strong>Users normalized:</strong> <?php echo (int) $complete_normalize_count; ?></li>
                        <li><strong>Personal channels created:</strong> <?php echo (int) $complete_operator_channels; ?></li>
                        <li><strong>Legacy tables dropped:</strong> <?php echo (int) $complete_legacy_dropped; ?></li>
                    <?php endif; ?>
                    <li><strong>Completed:</strong> <?php echo htmlspecialchars(date('c')); ?></li>
                </ul>
            </div>
            <?php if (!empty($complete_log)): ?>
                <div class="log-section">
                    <h4>Run log</h4>
                    <pre class="log"><?php foreach ($complete_log as $e) {
                        $c = $e[0];
                        $t = htmlspecialchars($e[1]);
                        $ts = isset($e[2]) ? htmlspecialchars($e[2]) . ' ' : '';
                        echo "<span class=\"{$c}\">[{$c}] {$ts}{$t}</span>\n";
                    } ?></pre>
                </div>
            <?php endif; ?>
            <?php if (!empty($complete_config_log)): ?>
                <div class="log-section">
                    <h4>Config log</h4>
                    <pre class="log"><?php foreach ($complete_config_log as $e) {
                        $c = $e[0];
                        $t = htmlspecialchars($e[1]);
                        $ts = isset($e[2]) ? htmlspecialchars($e[2]) . ' ' : '';
                        echo "<span class=\"{$c}\">[{$c}] {$ts}{$t}</span>\n";
                    } ?></pre>
                </div>
            <?php endif; ?>
            <div class="step warning" style="margin-top:1.5rem;">
                <h4>⚠️ Background Jobs Queued</h4>
                <p>Post-install tasks have been queued in the system_commands table. Run the system command runner to
                    process them:</p>
                <p><strong>Linux/macOS:</strong></p>
                <pre
                    style="background:#f5f5f5; padding:0.5rem; border-radius:4px; font-family:monospace; font-size:0.85rem;">python3 scripts/run_system_commands.py</pre>
                <p><strong>Windows (WSL):</strong></p>
                <pre
                    style="background:#f5f5f5; padding:0.5rem; border-radius:4px; font-family:monospace; font-size:0.85rem;">wsl python3 /mnt/c/ServBay/www/servbay/lupopedia/scripts/run_system_commands.py</pre>
                <p style="font-size:0.85rem; color:#666;">The runner will import channels and artifacts. You can run it now
                    or later.</p>
            </div>
            <p>
                <a href="<?php echo htmlspecialchars($baseUrl . $loginUrl); ?>" class="btn">Go to Login</a>
                <a href="<?php echo htmlspecialchars($baseUrl . 'admin.php'); ?>" class="btn btn-secondary">Open Admin</a>
                <a href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=download_log&which=bootstrap'); ?>"
                    class="btn btn-secondary" download>Download bootstrap log</a>
                <a href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=download_log&which=run'); ?>"
                    class="btn btn-secondary" download>Download run log</a>
                <a href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=download_log&which=config'); ?>"
                    class="btn btn-secondary" download>Download config log</a>
            </p>
        </div>
    <?php endif; ?>

    <p style="margin-top:2rem; font-size:0.9rem; color:#666;">Lupopedia installs in a subfolder. The installer writes
        <code>lupopedia-config.php</code> in the web-accessible directory (protected) and resolves memory/channels above web root.</p>
</body>

</html>
