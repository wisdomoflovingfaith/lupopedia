<?php
/**
 * @wolfie.headers {
 *   file_path_from_root: "install.php",
 *   system_version: "4.0.85",
 *   channel_id: 42,
 *   mood_rgb: "FF6347",
 *   purpose: "Main installer and upgrade wizard for Lupopedia - handles fresh install and Crafty Syntax 3.7.5 upgrade",
 *   last_modified_utc: "20260330",
 *   delegation_chain: "1001:10000",
 *   actor_id: 1001,
 *   lupo_agent: "kiro",
 *   artifact_type: "installer",
 *   artifact_kind: "upgrade_wizard",
 *   traits: ["critical", "crafty_syntax", "upgrade_path", "p0", "installer"],
 *   hashtags: ["#installer", "#crafty_syntax", "#upgrade", "#wizard", "#critical"],
 *   engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260224" },
 *   graph_stats: { inbound_count: 8, outbound_count: 12, centrality_score: 0.98 }
 * }
 * 
 * @flip.footer {
 *   inbound_edges: [
 *     { from: "README.md", type: "references", weight: 1.0, hashtag: "#installation" },
 *     { from: "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "implements", weight: 1.0, hashtag: "#migration" },
 *     { from: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "executes", weight: 1.0, hashtag: "#schema" },
 *     { from: "install/seed_lupopedia_4_1_0.sql", type: "executes", weight: 1.0, hashtag: "#seed" },
 *     { from: "lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql", type: "executes", weight: 1.0, hashtag: "#import" },
 *     { from: "lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql", type: "detects", weight: 0.9, hashtag: "#legacy" },
 *     { from: "lupo-install/index.php", type: "includes", weight: 0.9, hashtag: "#ui" },
 *     { from: "lupo-install/wizard.php", type: "includes", weight: 0.9, hashtag: "#wizard" }
 *   ],
 *   outbound_edges: [
 *     { to: "lupopedia-config.php", type: "generates", weight: 1.0, hashtag: "#config" },
 *     { to: "lupo-includes/version.php", type: "requires", weight: 1.0, hashtag: "#version" },
 *     { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "executes", weight: 1.0, hashtag: "#schema" },
 *     { to: "install/seed_lupopedia_4_1_0.sql", type: "executes", weight: 1.0, hashtag: "#seed" },
 *     { to: "lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql", type: "executes", weight: 1.0, hashtag: "#import" },
 *     { to: "lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql", type: "detects", weight: 0.9, hashtag: "#legacy" },
 *     { to: "lupo-app/Services/CraftyMigrationService.php", type: "uses", weight: 0.8, hashtag: "#migration" },
 *     { to: "lupo-app/Services/CraftyConfigTransformer.php", type: "uses", weight: 0.8, hashtag: "#config" },
 *     { to: "lupo-install/index.php", type: "includes", weight: 0.9, hashtag: "#ui" },
 *     { to: "lupo-install/wizard.php", type: "includes", weight: 0.9, hashtag: "#wizard" },
 *     { to: "index.php", type: "redirects_to", weight: 0.7, hashtag: "#completion" },
 *     { to: "admin.php", type: "redirects_to", weight: 0.6, hashtag: "#admin" }
 *   ],
 *   referenced_by_actors: [1001, 1002, 10000],
 *   references: {
 *     by_files: ["README.md", "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"],
 *     by_actors: [1001, 10000]
 *   },
 *   semantic_tags: ["installer", "upgrade_wizard", "crafty_syntax_3_7_5", "identity_normalization", "reserved_channels"],
 *   enrichment: { llm_inferred_edges: [], federated_metrics: {} },
 *   version: "4.0.85",
 *   last_verified_utc: "20260330",
 *   last_verified_by: "GitHub Copilot"
 * }
 */

/**
 * Lupopedia Install / Upgrade Wizard (version from version.php / atoms)
 *
 * Two valid states only: New install | Upgrade from Crafty Syntax 3.7.5.
 * No Lupopedia → Lupopedia upgrade. Project root is webroot; no /public folder.
 *
 * Pre-flight: PHP 5.3+, pdo_mysql, json required; project root writable. Optional: mbstring, curl, openssl, fileinfo (warn only).
 * Fallback philosophy: degrade gracefully; do not block unless absolutely required. No GD requirement (image.php uses raw output).
 *
 * A. Detect: livehelp_* tables exist → upgrade; else → new install.
 * B. New install: install_new_lupopedia.sql (includes all required tables, e.g. lupo_bans_log for Ban at Gate audit), then install/seed_lupopedia_4_1_0.sql (consolidated seed).
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
 * H. Write lupopedia-config.php. Redirect to login.
 *
 * @package Lupopedia
 * @see docs/doctrine/migrations/ Installation SQL Rule
 */

/**
 * INSTALLER SQL SOURCE OF TRUTH
 * All installer-critical SQL must reside under:
 *   lupo-database/lupopedia/mysql/
 * Do not load SQL from database/migrations/.
 */

// Project root is webroot for the app (subfolder-install doctrine)
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', __DIR__);
}
// LUPO_DATABASE_DIR: from config if available; only if missing, default to repo-local lupo-database/
if (!defined('LUPO_DATABASE_DIR')) {
    define('LUPO_DATABASE_DIR', LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-database');
}
// LUPO_MYSQL_DIR: always derived from LUPO_DATABASE_DIR (no trailing slash; paths built with DIRECTORY_SEPARATOR)
if (!defined('LUPO_MYSQL_DIR')) {
    define('LUPO_MYSQL_DIR', LUPO_DATABASE_DIR . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'mysql');
}
if (!is_dir(LUPO_MYSQL_DIR)) {
    die('MySQL installer directory not found at LUPO_MYSQL_DIR: ' . LUPO_MYSQL_DIR);
}
if (!defined('LUPO_CONSOLIDATED_SEED_FILE')) {
    define('LUPO_CONSOLIDATED_SEED_FILE', LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'seed_lupopedia_4_1_0.sql');
}

// Version for wizard UI. Canonical source is GLOBAL_CURRENT_LUPOPEDIA_VERSION in atoms.
$lupo_wizard_version = null;
$atoms_candidates = array(
    LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-config' . DIRECTORY_SEPARATOR . 'global_atoms.yaml',
    LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-config' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'global_atoms.yaml',
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
$version_php = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'version.php';
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

require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'install_wizard_classes.php';
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-install' . DIRECTORY_SEPARATOR . 'InstallWizardMdImporter.php';
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'class-pdo_db.php';

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
    echo '</ul><p>Please fix the above and reload this page. Lupopedia requires PHP 5.3+, pdo_mysql, json, and a writable project root.</p></body></html>';
    exit;
}

session_start();

// Only treat as installed if lupopedia-config.php exists and defines LUPOPEDIA_CONFIG_LOADED.
// Do NOT treat config.php or other files as installed; do not redirect during install.
$configInRoot = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
if (is_file($configInRoot)) {
    $configPath = $configInRoot;
} else {
    $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '';
    $configAbove = $docRoot !== '' ? dirname($docRoot) . DIRECTORY_SEPARATOR . 'lupopedia-config.php' : null;
    $configPath = ($configAbove !== null && is_file($configAbove)) ? $configAbove : null;
}
$forceReinstall = isset($_GET['force_reinstall']) && $_GET['force_reinstall'] === '1';
if ($configPath !== null && is_file($configPath) && !$forceReinstall) {
    require_once $configPath;
    if (defined('LUPOPEDIA_CONFIG_LOADED') && LUPOPEDIA_CONFIG_LOADED) {
        $base = rtrim(dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : ''), '/');
        header('Location: ' . ($base === '' ? '/login' : $base . '/login'));
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
    foreach (array('lupo_install_db_vars', 'lupo_install_type', 'lupo_install_mode_choice', 'lupo_install_mode_warning', 'lupo_install_livehelp_tables', 'lupo_drop_livehelp_tables', 'lupo_normalize_applied', 'lupo_normalize_count', 'lupo_operator_channel_map', 'lupo_bootstrap_log', 'lupo_run_log', 'lupo_run_done', 'lupo_import_run', 'lupo_wizard_audit_log', 'lupo_config_log', 'lupo_config_site_name', 'lupo_config_base_url', 'lupo_config_admin_email', 'lupo_config_timezone', 'lupo_config_default_language', 'lupo_config_support_email', 'lupo_config_default_visitor_channel', 'lupo_config_enable_ai_channels', 'lupo_csrf_token') as $k) {
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
            // Detect livehelp_ tables using the connection from step 1 credentials (no earlier connection; no config file required).
            $livehelp_tables = InstallWizardDb::detectLivehelpTables($pdo);
            $detected_upgrade = (count($livehelp_tables) > 0 || InstallWizardCredentials::craftyConfigExists());
            $selected_mode = isset($_SESSION['lupo_install_mode_choice']) ? $_SESSION['lupo_install_mode_choice'] : 'new';
            if ($selected_mode === 'upgrade' && count($livehelp_tables) === 0) {
                $errors[] = 'Upgrade existing was selected, but no livehelp_* tables were found in this database. Check DB credentials or choose New install.';
            }
            if (!empty($errors)) {
                throw new RuntimeException(InstallWizardLogger::safeErrorMessage('validation'));
            }
            $_SESSION['lupo_install_type'] = $selected_mode;
            if ($selected_mode === 'new' && $detected_upgrade) {
                $_SESSION['lupo_install_mode_warning'] = 'New install was selected even though legacy Crafty markers were detected (livehelp_* tables and/or Crafty config.php). Import will be skipped by design.';
            } elseif ($selected_mode === 'upgrade' && !$detected_upgrade) {
                $_SESSION['lupo_install_mode_warning'] = 'Upgrade existing was selected, but no automatic Crafty markers were detected. Import may skip if no legacy data exists.';
            }
            $_SESSION['lupo_install_livehelp_tables'] = $livehelp_tables;
            $base = (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.');
            if ($_SESSION['lupo_install_type'] === 'upgrade') {
                // Constitutional: reserved system channels (0, 1, 42, 51) must exist before normalization.
                // Run install + seed + reserved channels immediately after detect upgrade, then go to normalize.
                if (!defined('LUPO_TABLE_PREFIX') && isset($_SESSION['lupo_table_prefix'])) {
                    define('LUPO_TABLE_PREFIX', $_SESSION['lupo_table_prefix']);
                }
                $mysqlDir = LUPO_MYSQL_DIR;
                if (!defined('LUPO_TABLE_PREFIX') && isset($_SESSION['lupo_table_prefix'])) {
                    define('LUPO_TABLE_PREFIX', $_SESSION['lupo_table_prefix']);
                }
                $bootstrapLog = array();
                $table_prefix = isset($_SESSION['lupo_table_prefix']) ? $_SESSION['lupo_table_prefix'] : 'lupo_';
                try {
                    $install_ok = InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'install_new_lupopedia.sql', $bootstrapLog, $table_prefix);
                    if (!$install_ok) {
                        throw new RuntimeException('Critical schema install failed (install_new_lupopedia.sql). Stop and fix SQL errors before continuing.');
                    }
                    if (!is_file(LUPO_CONSOLIDATED_SEED_FILE)) {
                        throw new RuntimeException('Consolidated seed not found: ' . LUPO_CONSOLIDATED_SEED_FILE);
                    }
                    $seed_ok = InstallWizardSqlRunner::runSqlFile($pdo, LUPO_CONSOLIDATED_SEED_FILE, $bootstrapLog, $table_prefix);
                    if (!$seed_ok) {
                        throw new RuntimeException('Critical seed failed (seed_lupopedia_4_1_0.sql). Stop and fix SQL errors before continuing.');
                    }
                    InstallWizardChannels::createReservedSystemChannels($pdo, $bootstrapLog);
                    $_SESSION['lupo_bootstrap_log'] = $bootstrapLog;
                    header('Location: ' . $base . '/install.php?step=bootstrap');
                } catch (RuntimeException $e) {
                    $errors[] = $e->getMessage();
                    $bootstrapLog[] = InstallWizardLogger::logEntry('error', $e->getMessage());
                    $_SESSION['lupo_bootstrap_log'] = $bootstrapLog;
                }
            } else {
                header('Location: ' . $base . '/install.php?step=confirm');
            }
            exit;
        } catch (RuntimeException $e) {
            if (empty($errors)) {
                $errors[] = InstallWizardLogger::safeErrorMessage('validation');
            }
            error_log('Lupopedia install credentials validation: ' . $e->getMessage());
        } catch (PDOException $e) {
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
    } catch (PDOException $e) {
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
        } catch (PDOException $e) {
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
        $importSql = $mysqlDir . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'import_from_old_crafty_syntax.sql';
        // Upgrade: install/seed/reserved were already run after detect upgrade (before normalize). Only import → personal channels/roles → drop → config.
        // New install: run install → seed → reserved channels → config.

        try {
            if ($install_type === 'upgrade') {
                $dept_table = $table_prefix . 'departments';
                $schema_ok = false;
                try {
                    $st = $pdo->query("SHOW TABLES LIKE " . $pdo->quote($dept_table));
                    $schema_ok = $st && $st->fetch() !== false;
                } catch (PDOException $e) {
                    $schema_ok = false;
                }
                if (!$schema_ok) {
                    $log[] = InstallWizardLogger::logEntry('ok', 'Schema missing (e.g. tables dropped); running install and consolidated seed first.');
                    $install_ok = InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'install_new_lupopedia.sql', $log, $table_prefix);
                    if (!$install_ok) {
                        throw new RuntimeException('Critical schema install failed (install_new_lupopedia.sql). Stop and fix SQL errors before continuing.');
                    }
                    if (!is_file(LUPO_CONSOLIDATED_SEED_FILE)) {
                        throw new RuntimeException('Consolidated seed not found: ' . LUPO_CONSOLIDATED_SEED_FILE);
                    }
                    $seed_ok = InstallWizardSqlRunner::runSqlFile($pdo, LUPO_CONSOLIDATED_SEED_FILE, $log, $table_prefix);
                    if (!$seed_ok) {
                        throw new RuntimeException('Critical seed failed (seed_lupopedia_4_1_0.sql). Stop and fix SQL errors before continuing.');
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
                    throw new RuntimeException('Critical schema install failed (install_new_lupopedia.sql). Stop and fix SQL errors before continuing.');
                }
                if (!is_file(LUPO_CONSOLIDATED_SEED_FILE)) {
                    throw new RuntimeException('Consolidated seed not found: ' . LUPO_CONSOLIDATED_SEED_FILE);
                }
                $seed_ok = InstallWizardSqlRunner::runSqlFile($pdo, LUPO_CONSOLIDATED_SEED_FILE, $log, $table_prefix);
                if (!$seed_ok) {
                    throw new RuntimeException('Critical seed failed (seed_lupopedia_4_1_0.sql). Stop and fix SQL errors before continuing.');
                }
                InstallWizardDepartments::ensureSystemDepartment($pdo, $log);
                InstallWizardChannels::createReservedSystemChannels($pdo, $log);

                // Import MD files from channels/0/broadcasts/
                InstallWizardMdImporter::importAllMdFiles($pdo, $log, $table_prefix);
                $log[] = InstallWizardLogger::logEntry('ok', 'New install: lupo_crafty_syntax_* tables are empty; import_from_old_crafty_syntax.sql runs only on upgrade from Crafty Syntax 3.7.5.');
            }

            if ($install_type === 'upgrade') {
                // Import runs only after identity normalization has been applied to livehelp_users.
                // Skip import when no legacy tables exist (e.g. upgrade detected via config but DB has no livehelp_*).
                $livehelp_tables_for_import = isset($_SESSION['lupo_install_livehelp_tables']) ? $_SESSION['lupo_install_livehelp_tables'] : array();
                if (!empty($_SESSION['lupo_import_run'])) {
                    $log[] = InstallWizardLogger::logEntry('skip', 'Import already completed (idempotent skip).');
                } elseif (empty($livehelp_tables_for_import)) {
                    $log[] = InstallWizardLogger::logEntry('skip', 'Skipped: no legacy livehelp_* tables in database; nothing to import. (Import only runs when upgrading from Crafty Syntax 3.7.5 with existing livehelp_* data.)');
                    $_SESSION['lupo_import_run'] = true;
                } else {
                    // RESERVED ID DOCTRINE: actor_id 0-9999 = system/AI only; human actors start at 10000.
                    // Ensure next lupo_actors.actor_id is at least 10000 so imported Crafty users get IDs >= 10000.
                    $actors_table = (defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_') . 'actors';
                    $actors_quoted = '`' . str_replace('`', '``', $actors_table) . '`';
                    try {
                        $stmt = $pdo->query("SELECT COALESCE(MAX(actor_id), 0) AS mx FROM " . $actors_quoted . " LIMIT 1");
                        $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
                        $max = $row && isset($row['mx']) ? (int) $row['mx'] : 0;
                        $nextId = $max >= 10000 ? $max + 1 : 10000;
                        $pdo->exec("ALTER TABLE " . $actors_quoted . " AUTO_INCREMENT = " . (int) $nextId);
                        $log[] = InstallWizardLogger::logEntry('ok', 'Set lupo_actors AUTO_INCREMENT to ' . $nextId . ' (human actors from 10000).');
                    } catch (PDOException $e) {
                        $log[] = InstallWizardLogger::logEntry('error', 'Could not set actor_id minimum (see server log).');
                        error_log('Lupopedia wizard AUTO_INCREMENT: ' . $e->getMessage());
                    }
                    $log[] = InstallWizardLogger::logEntry('ok', 'Running import_from_old_crafty_syntax.sql (converts livehelp_* to utf8mb4_unicode_ci, then migrates data).');
                    $importOk = InstallWizardSqlRunner::runSqlFile($pdo, $importSql, $log, $table_prefix);
                    if ($importOk) {
                        $log[] = InstallWizardLogger::logEntry('ok', 'Import complete.');
                    } else {
                        $log[] = InstallWizardLogger::logEntry('error', 'Import reported failures. Check for "SQL failed" entries above. Legacy livehelp_* tables may not have been converted to utf8mb4_unicode_ci.');
                    }
                    $_SESSION['lupo_import_run'] = true;
                }
                // Import TRUNCATEs lupo_departments; ensure system (0) and default (1) exist before channel creation.
                InstallWizardDepartments::ensureSystemDepartment($pdo, $log);

                // Doctrine: every imported Crafty operator (actor) gets a personal channel and captain in lupo_actor_channel_roles; Crafty admins get captain on channel 1 (Administration).
                // Must run after import so lupo_actors exist; before drop. Mapping stored for importer/wizard.
                $operatorChannelMap = InstallWizardChannels::createOperatorChannels($pdo, $log);
                $_SESSION['lupo_operator_channel_map'] = $operatorChannelMap;
                InstallWizardChannels::ensureOperatorChannels($pdo, $log);

                // Optional: drop legacy livehelp_* tables after import (user choice at credentials; default unchecked).
                if (!empty($_SESSION['lupo_drop_livehelp_tables'])) {
                    $dropSql = $mysqlDir . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'drop_old_crafty_syntax_tables.sql';
                    InstallWizardSqlRunner::runSqlFile($pdo, $dropSql, $log);
                    $log[] = InstallWizardLogger::logEntry('ok', 'Legacy Crafty Syntax tables dropped.');
                    $remaining = InstallWizardDb::detectLivehelpTables($pdo);
                    if (!empty($remaining)) {
                        InstallWizardSqlRunner::dropLivehelpTables($pdo, $remaining, $log);
                        $log[] = InstallWizardLogger::logEntry('ok', 'Dropped remaining legacy tables: ' . implode(', ', $remaining));
                    }
                } else {
                    $log[] = InstallWizardLogger::logEntry('skip', 'Skipped: drop deprecated livehelp_* tables (option unchecked at credentials).');
                }
            }

            // Import MD files from channels/0/broadcasts/ (for both new install and upgrade)
            InstallWizardMdImporter::importAllMdFiles($pdo, $log, $table_prefix);

            // 4.0.93+: registry_open allocation removed (deterministic explicit IDs doctrine).
            // Keep no-op here intentionally to avoid touching removed tables during install.
            // 4.0.20: Ensure Stoned Wolfie (AI + human) banned test identities exist after import/seed.
            InstallWizardBannedIdentities::ensureStonedWolfieBannedIdentities($pdo, $log, $table_prefix);
            // 4.0.93+: single-seed runtime doctrine.
            // ANUBIS schema/tables are canonical in install_new_lupopedia.sql.

            // Activations Block
            require_once LUPOPEDIA_PATH . '/lupo-includes/functions/ai_activation.php';
            $core_actors = array(0, 1, 2, 19); // SYSTEM, CAPTAIN WOLFIE, LILITH, ANUBIS
            $log[] = InstallWizardLogger::logEntry('ok', '--- Activating CORE AI Agents ---');
            foreach ($core_actors as $actor_id) {
                $actor_db = new PDO_DB($pdo); // Wrap PDO for our helper
                if (ensureActorActive($actor_id, $actor_db, 'initial_install_activation')) {
                    $log[] = InstallWizardLogger::logEntry('ok', "Activated Actor ID: $actor_id");

                    // For ANUBIS, verify queue tables exist
                    if ($actor_id === 19) {
                        $required_tables = array(
                            'anubis_queue',
                            'anubis_processing_log',
                            'anubis_recovery_attempts',
                            'anubis_quarantine'
                        );
                        foreach ($required_tables as $table) {
                            $full_table = $table_prefix . $table;
                            $res = $pdo->query("SHOW TABLES LIKE '$full_table'")->fetch();
                            if (!$res) {
                                throw new RuntimeException("ANUBIS table $full_table missing - cannot proceed");
                            }
                        }
                        $log[] = InstallWizardLogger::logEntry('ok', "ANUBIS queue tables verified.");
                    }
                } else {
                    // ANUBIS is critical for orphan processing
                    if ($actor_id === 19) {
                        throw new RuntimeException("CRITICAL: Failed to activate ANUBIS (19). Installation halted.");
                    }
                    $log[] = InstallWizardLogger::logEntry('skip', "Warning: Could not activate Actor ID: $actor_id (non-critical)");
                }
            }

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

// ----- Step: config (site settings form, then write config)
$config_errors = array();
$config_values = array('site_name' => '', 'base_url' => '', 'admin_email' => '', 'timezone' => 'UTC', 'default_language' => 'en', 'support_email' => '', 'default_visitor_channel' => '1', 'enable_ai_channels' => '1');
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
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'write_config' && empty($errors)) {
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
                } catch (PDOException $e) {
                    // table may not exist on new install; skip collision check
                }
            }
        }
        if (empty($config_errors)) {
            $db_vars = isset($_SESSION['lupo_install_db_vars']) ? $_SESSION['lupo_install_db_vars'] : null;
            if ($db_vars !== null) {
                $writeLog = array();
                $table_prefix = isset($_SESSION['lupo_table_prefix']) ? $_SESSION['lupo_table_prefix'] : 'lupo_';
                $admin_password_for_create = isset($_POST['admin_password']) ? (string) $_POST['admin_password'] : '';
                $should_create_main_admin = ($install_type_for_config === 'new' && $admin_password_for_create !== '') || ($install_type_for_config === 'upgrade' && $admin_password_for_create !== '');
                if ($should_create_main_admin) {
                    try {
                        $pdoConfig = InstallWizardDb::connectPdo($db_vars);
                        if (!InstallWizardMainAdmin::createMainAdmin($pdoConfig, $table_prefix, $config_values['admin_email'], $admin_password_for_create, $writeLog)) {
                            $config_errors[] = 'Could not create main admin user. Check the log.';
                        }
                    } catch (PDOException $e) {
                        $config_errors[] = 'Database connection failed when creating main admin: ' . $e->getMessage();
                        $writeLog[] = InstallWizardLogger::logEntry('error', $e->getMessage());
                    }
                }
                if (empty($config_errors)) {
                    $options = array(
                        'site_name' => $config_values['site_name'],
                        'base_url' => $config_values['base_url'],
                        'admin_email' => $config_values['admin_email'],
                        'timezone' => $config_values['timezone'],
                        'default_language' => $config_values['default_language'],
                        'table_prefix' => $table_prefix,
                    );
                    if ($config_values['support_email'] !== '') {
                        $options['support_email'] = $config_values['support_email'];
                    }
                    if ($config_values['default_visitor_channel'] !== '') {
                        $options['default_visitor_channel'] = $config_values['default_visitor_channel'];
                    }
                    if ($config_values['enable_ai_channels'] === '1') {
                        $options['enable_ai_channels'] = true;
                    }
                    $configPath = InstallWizardConfigWriter::writeConfig($db_vars, $writeLog, $options);
                }
                if (empty($config_errors) && isset($configPath) && $configPath !== null) {
                    $_SESSION['lupo_config_log'] = $writeLog;
                    unset($_SESSION['lupo_install_db_vars'], $_SESSION['lupo_install_type'], $_SESSION['lupo_install_mode_choice'], $_SESSION['lupo_install_mode_warning'], $_SESSION['lupo_install_livehelp_tables'], $_SESSION['lupo_normalize_applied'], $_SESSION['lupo_operator_channel_map'], $_SESSION['lupo_bootstrap_log'], $_SESSION['lupo_run_done']);
                    header('Location: ' . (dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '') ?: '.') . '/install.php?step=complete');
                    exit;
                }
                if (empty($config_errors)) {
                    $config_errors[] = 'Could not write config file.';
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
    foreach (array('lupo_install_db_vars', 'lupo_install_type', 'lupo_install_mode_choice', 'lupo_install_mode_warning', 'lupo_install_livehelp_tables', 'lupo_normalize_applied', 'lupo_normalize_count', 'lupo_operator_channel_map', 'lupo_run_done', 'lupo_config_site_name', 'lupo_config_base_url', 'lupo_config_admin_email', 'lupo_config_timezone', 'lupo_config_default_language') as $k) {
        unset($_SESSION[$k]);
    }
    if (empty($complete_log) && is_file(LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupopedia-config.php')) {
        $complete_log = array(array('ok', 'Installation completed.'));
    }
    $complete_config_log = isset($_SESSION['lupo_config_log']) ? $_SESSION['lupo_config_log'] : array();
    $loginUrl = rtrim(dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : ''), '/');
    $loginUrl = ($loginUrl === '' ? '' : $loginUrl . '/') . 'login';
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
            <p><strong>Requirements:</strong> PHP 5.3+, PDO MySQL, JSON extension, writable project root, and a
                MySQL/MariaDB database. For upgrade: existing Crafty Syntax 3.7.5 data.</p>
            <div class="log-section">
                <h4>System diagnostics</h4>
                <ul class="diagnostics-list" style="list-style:none; padding:0; margin:0.5rem 0;">
                    <li class="diag-ok">&#10003; PHP <?php echo htmlspecialchars(phpversion()); ?> (5.3+ required)</li>
                    <li class="diag-ok">&#10003; pdo_mysql</li>
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
                    <li>Redirect to login</li>
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
                    <li>Run <code>lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql</code></li>
                    <li>Run consolidated seed <code>install/seed_lupopedia_4_1_0.sql</code> (single runtime seed)</li>
                    <li>Create reserved system channels (0, 1, 42, 51)</li>
                    <li>Write <code>lupopedia-config.php</code></li>
                    <li>Redirect to login</li>
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
                    ? array('Install', 'Seed', 'Reserved', 'Import', 'Operator lupo-channels', 'Drop')
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
                        <h4>Bootstrap (install + seed + reserved lupo-channels)</h4>
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
        </div>
    <?php elseif ($step === 'config'): ?>
        <div class="wizard-card">
            <h2>Site configuration</h2>
            <p>Set site options. These will be written to <code>lupopedia-config.php</code> in the project root.</p>
            <?php foreach ($config_errors as $e): ?>
                <p class="err"><?php echo htmlspecialchars($e); ?></p>
            <?php endforeach; ?>
            <form method="post" action="<?php echo htmlspecialchars($baseUrl . 'install.php?step=config'); ?>">
                <input type="hidden" name="lupo_csrf"
                    value="<?php echo htmlspecialchars(InstallWizardSecurity::getCsrfToken()); ?>">
                <input type="hidden" name="step" value="config">
                <input type="hidden" name="action" value="write_config">
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
                <p class="diag-ok">AI agent lupo-channels: enabled.</p>
                <p style="margin-top:1rem;"><button type="submit">Write config and finish</button></p>
            </form>
        </div>

    <?php elseif ($step === 'complete'): ?>
        <div class="wizard-card step success">
            <h2>Installation complete</h2>
            <p>Lupopedia has been installed successfully. <code>lupopedia-config.php</code> has been written to the project
                root.</p>
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
                    style="background:#f5f5f5; padding:0.5rem; border-radius:4px; font-family:monospace; font-size:0.85rem;">python3 lupo-scripts/run_system_commands.py</pre>
                <p><strong>Windows (WSL):</strong></p>
                <pre
                    style="background:#f5f5f5; padding:0.5rem; border-radius:4px; font-family:monospace; font-size:0.85rem;">wsl python3 /mnt/c/ServBay/www/servbay/lupopedia/lupo-scripts/run_system_commands.py</pre>
                <p style="font-size:0.85rem; color:#666;">The runner will import channels and artifacts. You can run it now
                    or later.</p>
            </div>
            <p>
                <a href="<?php echo htmlspecialchars($baseUrl . $loginUrl); ?>" class="btn">Go to Login</a>
                <a href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=download_log&which=bootstrap'); ?>"
                    class="btn btn-secondary" download>Download bootstrap log</a>
                <a href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=download_log&which=run'); ?>"
                    class="btn btn-secondary" download>Download run log</a>
                <a href="<?php echo htmlspecialchars($baseUrl . 'install.php?step=download_log&which=config'); ?>"
                    class="btn btn-secondary" download>Download config log</a>
            </p>
        </div>
    <?php endif; ?>

    <p style="margin-top:2rem; font-size:0.9rem; color:#666;">Project root is the webroot. No /public folder. Lupopedia
        is always installed in a subfolder of the server document root.</p>
</body>

</html>
