<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: page
  when_updated: "20260406034550"
  file_path_from_root: "login.php"
  web_path: "http://www.lupopedia.com/lupopedia/login.php"
  questions_toon: null
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "page"
  artifact_kind: "auth"
  purpose: "Login page; Model A session via bootstrap App\\Auth\\Session (lupo_sessions); $UNTRUSTED boundary for request input"
  tags: ["auth", "login", "session", "model_a", "untrusted"]
---
*/

/**
 * Login Page — auth_user to actor mapping; session authority from bootstrap (not session_start here).
 */

$UNTRUSTED = array(
    'server' => (isset($_SERVER) && is_array($_SERVER)) ? $_SERVER : array(),
    'get' => (isset($_GET) && is_array($_GET)) ? $_GET : array(),
    'post' => (isset($_POST) && is_array($_POST)) ? $_POST : array(),
);

// Load config (WordPress-style paths: install dir, parent, legacy DOCUMENT_ROOT — see LupopediaConfigResolver)
$lupoRoot = __DIR__;
$lupoPub = '/' . basename($lupoRoot);
require_once $lupoRoot . '/includes/classes/LupopediaConfigResolver.php';
$lupoCfgPath = LupopediaConfigResolver::resolve($lupoRoot, $lupoPub);
if ($lupoCfgPath === null) {
    $scriptName = isset($UNTRUSTED['server']['SCRIPT_NAME']) ? $UNTRUSTED['server']['SCRIPT_NAME'] : '';
    $dir = str_replace('\\', '/', dirname($scriptName));
    $lupoBase = rtrim($dir, '/');
    header('Location: ' . ($lupoBase === '' ? '/install.php' : $lupoBase . '/install.php'));
    exit;
}
require_once $lupoCfgPath;

if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $lupoRoot);
}
if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    define('LUPOPEDIA_PUBLIC_PATH', $lupoPub);
}

// Same session cookie path + Model A wiring as admin.php (canonicalCookiePath uses LUPOPEDIA_PUBLIC_PATH).
require_once $lupoRoot . '/includes/bootstrap.php';

require_once $lupoRoot . '/includes/classes/LupoLocale.php';
LupoLocale::bootstrap($lupoRoot);
require_once $lupoRoot . '/includes/i18n.php';

require_once __DIR__ . '/includes/classes/AuthService.php';

// If already logged in, send MD5-migration users to password change first (flag in lupo_sessions.metadata)
$authGate = isset($GLOBALS['lupo_auth_service']) ? $GLOBALS['lupo_auth_service'] : null;
$dbMeta = DatabaseFactory::getConnection();
$sessionMeta = lupo_session_metadata_current($dbMeta);
if ($authGate && $authGate->isLoggedIn()) {
    if (!empty($sessionMeta['password_change_required'])) {
        $cpw_url = function_exists('lupo_change_password_url')
            ? lupo_change_password_url()
            : (rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/index.php?' . http_build_query(array('slug' => 'change-password')));
        header('Location: ' . $cpw_url);
        exit;
    }
    header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/admin.php');
    exit;
}

$error = '';
$success = '';

// Handle form submission (all request input via $UNTRUSTED — PRD 00 §17.8)
if (isset($UNTRUSTED['server']['REQUEST_METHOD']) && $UNTRUSTED['server']['REQUEST_METHOD'] === 'POST') {
    $username = isset($UNTRUSTED['post']['username']) ? $UNTRUSTED['post']['username'] : '';
    $password = isset($UNTRUSTED['post']['password']) ? $UNTRUSTED['post']['password'] : '';
    $redirect = isset($UNTRUSTED['get']['redirect']) ? $UNTRUSTED['get']['redirect'] : null;
    // If referer contains 'install', always redirect to admin.php after login
    $referer = isset($UNTRUSTED['server']['HTTP_REFERER']) ? $UNTRUSTED['server']['HTTP_REFERER'] : '';
    $forceAdminRedirect = ($referer !== '' && stripos($referer, 'install') !== false);
    if (empty($username) || empty($password)) {
        $error = lupo_t('login.error_both_required', 'Please enter both username and password.');
    } else {
        $authService = new AuthService();
        $result = $authService->handleLogin($username, $password, $redirect);
        if (isset($result['error'])) {
            $error = $result['error'];
        } elseif (!empty($result['needs_password_change'])) {
            $cpw_url = function_exists('lupo_change_password_url')
                ? lupo_change_password_url()
                : (rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/index.php?' . http_build_query(array('slug' => 'change-password')));
            header('Location: ' . $cpw_url);
            exit;
        } elseif (isset($result['needs_agent_selection'])) {
            // Redirect to agent selection page
            header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/select_agent.php');
            exit;
        } elseif (isset($result['redirect'])) {
            if ($forceAdminRedirect) {
                header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/admin.php');
            } else {
                header('Location: ' . $result['redirect']);
            }
            exit;
        }
    }
}

$pub = LUPOPEDIA_PUBLIC_PATH;
$postedUsername = isset($UNTRUSTED['post']['username']) ? $UNTRUSTED['post']['username'] : '';
$lupoCurrentLocale = LupoLocale::getLocale();

?>
<!DOCTYPE html>
<html lang="<?= LupoLocale::htmlLang() ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(lupo_t('login.html_title', 'Login - Lupopedia')); ?></title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($pub); ?>/templates/login/style.css">
    <script src="<?php echo htmlspecialchars($pub); ?>/includes/js/lupo-layers.js"></script>
    <script>
        window.lupoLoginSigningText = <?php echo json_encode(lupo_t('login.submit_pending', 'Signing In...')); ?>;
    </script>
    <script src="<?php echo htmlspecialchars($pub); ?>/templates/login/script.js"></script>
</head>

<body onload="initApp()">

    <div id="headerDiv" class="login-header">
        <img src="<?php echo htmlspecialchars($pub); ?>/images/wolfoverlogin.png"
            alt="<?php echo htmlspecialchars(lupo_t('login.logo_alt', 'Lupopedia')); ?>" class="login-logo">
    </div>

    <div id="loginDiv" class="login-container">
        <form class="login-form" method="POST" action="">
            <?php if ($error !== ''): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success !== ''): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <div class="input-group">
                <label for="lupo_locale"><?php echo htmlspecialchars(lupo_t('login.language', 'Language')); ?></label>
                <select class="lupo-locale-select" id="lupo_locale" name="lupo_locale">
                    <option value="en" <?php echo ($lupoCurrentLocale === 'en') ? ' selected' : ''; ?>>
                        <?php echo htmlspecialchars(lupo_t('login.locale_en', 'English')); ?></option>
                </select>
            </div>
            <div class="input-group">
                <label for="username"><?php echo htmlspecialchars(lupo_t('login.username', 'Username')); ?></label>
                <input type="text" id="username" name="username"
                    placeholder="<?php echo htmlspecialchars(lupo_t('login.username_placeholder', 'Enter username')); ?>"
                    required autofocus value="<?php echo htmlspecialchars($postedUsername); ?>">
            </div>
            <div class="input-group">
                <label for="password"><?php echo htmlspecialchars(lupo_t('login.password', 'Password')); ?></label>
                <input type="password" id="password" name="password"
                    placeholder="<?php echo htmlspecialchars(lupo_t('login.password_placeholder', 'Enter password')); ?>"
                    required>
            </div>
            <button type="submit"
                class="login-btn"><?php echo htmlspecialchars(lupo_t('login.submit', 'Login')); ?></button>

            <div class="forgot-password">
                <a
                    href="<?php echo htmlspecialchars($pub); ?>/forgot-password.php"><?php echo htmlspecialchars(lupo_t('login.forgot_password', 'Forgot your password?')); ?></a>
            </div>
        </form>
    </div>

</body>

</html>