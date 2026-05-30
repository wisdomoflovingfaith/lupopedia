<?php
/**
 * Pop-out visit graph for a single path_url (Crafty graph.php?type=visit parity).
 * Maps: item recno + livehelp_visits_monthly.pageurl -> lupo_visits.path_url (via base64 GET visit_u).
 */

define('LUPOPEDIA_PATH', __DIR__);
define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(__DIR__));

require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'LupopediaConfigResolver.php';
$lupoResolvedCfg = LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, LUPOPEDIA_PUBLIC_PATH);
if ($lupoResolvedCfg !== null) {
    define('LUPOPEDIA_CONFIG_PATH', $lupoResolvedCfg);
} elseif (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', dirname($_SERVER['DOCUMENT_ROOT']) . '/config.php');
} elseif (file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', dirname($_SERVER['DOCUMENT_ROOT']) . LUPOPEDIA_PUBLIC_PATH . '/config.php');
} elseif (@file_exists(LUPOPEDIA_PATH . '/config.php')) {
    define('LUPOPEDIA_CONFIG_PATH', LUPOPEDIA_PATH . '/config.php');
} else {
    header('HTTP/1.0 500 Internal Server Error');
    echo 'Configuration not found.';
    exit;
}

require_once LUPOPEDIA_CONFIG_PATH;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once LUPOPEDIA_PATH . '/includes/classes/LupoLocale.php';
LupoLocale::bootstrap(LUPOPEDIA_PATH);
require_once LUPOPEDIA_PATH . '/includes/i18n.php';

$authService = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
if ($authService) {
    $authService->requireLogin();
} else {
    if (!function_exists('require_login')) {
        require_once LUPOPEDIA_PATH . '/includes/functions/auth-helpers.php';
    }
    require_login();
}

$user = $authService ? $authService->getCurrentUser() : (function_exists('current_user') ? current_user() : array());
$isAdmin = ($user !== false && !empty($user)) && !empty($user['is_admin']);
if (!$isAdmin) {
    header('HTTP/1.0 403 Forbidden');
    echo 'Access denied.';
    exit;
}

$pathUrl = '';
if (isset($_GET['visit_u']) && is_string($_GET['visit_u'])) {
    $pathUrl = lupo_data_graph_decode_visit_u($_GET['visit_u']);
}

$y = (int) gmdate('Y');
$m = (int) gmdate('n');
if (isset($_GET['visit_year']) && is_numeric($_GET['visit_year'])) {
    $yy = (int) $_GET['visit_year'];
    if ($yy >= 2000 && $yy <= 2100) {
        $y = $yy;
    }
}
if (isset($_GET['visit_month']) && is_numeric($_GET['visit_month'])) {
    $mm = (int) $_GET['visit_month'];
    if ($mm >= 1 && $mm <= 12) {
        $m = $mm;
    }
}

$actorId = 0;
if (isset($_GET['visit_actor']) && is_numeric($_GET['visit_actor'])) {
    $actorId = (int) $_GET['visit_actor'];
    if ($actorId < 0) {
        $actorId = 0;
    }
}

if ($pathUrl === '') {
    header('HTTP/1.0 400 Bad Request');
    echo 'Missing or invalid visit_u.';
    exit;
}

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db) {
    header('HTTP/1.0 500 Internal Server Error');
    echo 'Database not available.';
    exit;
}

$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$base = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';

require_once LUPOPEDIA_PATH . '/includes/classes/AdminDataVisitGraphHandler.php';
header('Content-Type: text/html; charset=utf-8');
echo AdminDataVisitGraphHandler::renderDocument($db, $prefix, $base, $pathUrl, $y, $m, $actorId);
exit;

/**
 * @param string $raw
 * @return string
 */
function lupo_data_graph_decode_visit_u($raw)
{
    $s = trim((string) $raw);
    if ($s === '') {
        return '';
    }
    $bin = base64_decode($s, true);
    if ($bin === false || $bin === '') {
        return '';
    }
    if (strlen($bin) > 2048) {
        return '';
    }
    if (strpos($bin, "\0") !== false) {
        return '';
    }
    return $bin;
}
