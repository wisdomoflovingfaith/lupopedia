<?php
/**
 * Visitor JS bundle — semantic bridge (PRD 28 / Eye) between host site and Lupopedia install.
 *
 * Embed:
 *   <script src="<?php echo htmlspecialchars(rtrim(LUPOPEDIA_PUBLIC_PATH,'/').'/lupopedia_js.php'); ?>" defer></script>
 *
 * Why config + session only (not lupo-includes/bootstrap.php):
 * Full bootstrap loads DB, auth, HTML error paths, and security headers suited for pages — not for a
 * single application/javascript response. This file loads only lupopedia-config.php so constants and
 * paths are real without doubling request weight or risking wrong Content-Type side effects.
 *
 * Exposes window.LUPO_BOOTSTRAP (canonical) and window.LUPO_CONFIG (Gemini-style alias: baseUrl, apiUrl, themeColor).
 * Real track endpoint: {publicPath}/ajax.php?action=track (JSON POST + CSRF), not api/track.php.
 *
 * @package Lupopedia
 */

$lupoRoot = __DIR__;
$lupoPubGuess = '/' . basename($lupoRoot);

require_once $lupoRoot . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'LupopediaConfigResolver.php';

$lupopediaConfigPath = LupopediaConfigResolver::resolve($lupoRoot, $lupoPubGuess);

$scheme = 'http';
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    $scheme = 'https';
}
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
$origin = ($host !== '') ? ($scheme . '://' . $host) : '';

$pub = $lupoPubGuess;
if ($pub !== '' && $pub[0] !== '/') {
    $pub = '/' . $pub;
}
$pub = rtrim($pub, '/');

$ajaxUrl = ($origin !== '' && $pub !== '') ? ($origin . $pub . '/ajax.php') : '';
$bundleUrl = ($origin !== '' && $pub !== '') ? ($origin . $pub . '/lupopedia_js.php') : '';

$includeCommandBar = true;
if (isset($_GET['command_bar']) && $_GET['command_bar'] === '0') {
    $includeCommandBar = false;
}

$tracking = true;
if (isset($_GET['tracking']) && $_GET['tracking'] === '0') {
    $tracking = false;
}

header('Content-Type: application/javascript; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-cache, must-revalidate');

$jsonFlags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
if (defined('JSON_UNESCAPED_SLASHES')) {
    $jsonFlags |= JSON_UNESCAPED_SLASHES;
}

$configOk = false;
if ($lupopediaConfigPath !== null && LupopediaConfigResolver::isSafeLocalConfigPath($lupopediaConfigPath)) {
    require_once $lupopediaConfigPath;
    if (defined('LUPOPEDIA_CONFIG_LOADED') && LUPOPEDIA_CONFIG_LOADED) {
        $configOk = true;
    }
}

if ($configOk && defined('LUPOPEDIA_PUBLIC_PATH')) {
    $pub = rtrim((string) LUPOPEDIA_PUBLIC_PATH, '/');
    if ($pub !== '' && $pub[0] !== '/') {
        $pub = '/' . $pub;
    }
    $ajaxUrl = ($origin !== '') ? ($origin . $pub . '/ajax.php') : '';
    $bundleUrl = ($origin !== '') ? ($origin . $pub . '/lupopedia_js.php') : '';
}

$fsRoot = $lupoRoot;
if ($configOk && defined('LUPOPEDIA_PATH')) {
    $fsRoot = LUPOPEDIA_PATH;
}

$bootstrap = array(
    'configured'   => $configOk,
    'publicPath'   => ($pub === '') ? '/' : $pub,
    'origin'       => $origin,
    'ajaxUrl'      => $ajaxUrl,
    'bundleUrl'    => $bundleUrl,
    'tracking'     => $configOk && $tracking,
    'commandBar'   => $configOk && $includeCommandBar,
    'theme'        => 'lupopedia_blue',
    'themeColors'  => array(
        /* WOLFIE / Web-1.0 system blue — matches blueeye.gif energy; no glass */
        'chrome'      => '#0000FF',
        'chromeDark'  => '#000080',
        'chromeLight' => '#3333FF',
        'chromeText'  => '#ffffff',
    ),
);

if ($configOk) {
    if (function_exists('session_status')) {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }
    } elseif (!isset($_SESSION)) {
        @session_start();
    }
}

echo 'window.LUPO_BOOTSTRAP = ' . json_encode($bootstrap, $jsonFlags) . ";\n";
echo '(function(b){window.LUPO_CONFIG={configured:!!b.configured,baseUrl:b.publicPath||"",publicPath:b.publicPath||"",apiUrl:b.ajaxUrl||"",ajaxUrl:b.ajaxUrl||"",bundleUrl:b.bundleUrl||"",origin:b.origin||"",tracking:!!b.tracking,commandBar:!!b.commandBar,theme:b.theme||"lupopedia_blue",themeColor:(b.themeColors&&b.themeColors.chrome)?b.themeColors.chrome:"#0000FF",themeColors:b.themeColors||{}};})(window.LUPO_BOOTSTRAP);' . "\n";

$layersPath = $fsRoot . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'lupo-layers.js';
$monitorPath = $fsRoot . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'lupo-monitor.js';

$emit = function ($absPath) use ($fsRoot) {
    $real = @realpath($absPath);
    if ($real === false || !is_file($real)) {
        echo "\n/* Missing bundle part: " . basename($absPath) . " */\n";
        return;
    }
    $jsRoot = @realpath($fsRoot . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'js');
    if ($jsRoot === false || strpos(str_replace('\\', '/', $real), str_replace('\\', '/', $jsRoot) . '/') !== 0) {
        echo "\n/* Refused path outside includes/js */\n";
        return;
    }
    echo "\n/* --- " . basename($real) . " --- */\n";
    readfile($real);
};

$emit($layersPath);
$emit($monitorPath);
