<?php
/**
 * Login Page - Simple implementation for auth_user to actor mapping
 */

// Load config (WordPress-style paths: install dir, parent, legacy DOCUMENT_ROOT — see LupopediaConfigResolver)
$lupoRoot = __DIR__;
$lupoPub = '/' . basename($lupoRoot);
require_once $lupoRoot . '/lupo-includes/classes/LupopediaConfigResolver.php';
$lupoCfgPath = LupopediaConfigResolver::resolve($lupoRoot, $lupoPub);
if ($lupoCfgPath === null) {
    $lupoBase = rtrim(dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : ''), '/');
    header('Location: ' . ($lupoBase === '' ? '/install.php' : $lupoBase . '/install.php'));
    exit;
}
require_once $lupoCfgPath;

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $lupoRoot . '/lupo-includes/classes/LupoLocale.php';
LupoLocale::bootstrap($lupoRoot);
require_once $lupoRoot . '/lupo-includes/lupo-i18n.php';

// If already logged in, send MD5-migration users to password change first
if (isset($_SESSION['actor_id'])) {
    if (!empty($_SESSION['password_change_required'])) {
        header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/change-password');
        exit;
    }
    header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/admin.php');
    exit;
}

// Load required classes
require_once __DIR__ . '/lupo-includes/classes/DatabaseFactory.php';
require_once __DIR__ . '/lupo-includes/classes/AuthService.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : null;
    // If referer contains 'install', always redirect to admin.php after login
    $forceAdminRedirect = false;
    if (!empty($_SERVER['HTTP_REFERER']) && stripos($_SERVER['HTTP_REFERER'], 'install') !== false) {
        $forceAdminRedirect = true;
    }
    if (empty($username) || empty($password)) {
        $error = lupo_t('login.error_both_required', 'Please enter both username and password.');
    } else {
        $authService = new AuthService();
        $result = $authService->handleLogin($username, $password, $redirect);
        if (isset($result['error'])) {
            $error = $result['error'];
        } elseif (!empty($result['needs_password_change'])) {
            if ($redirect !== null && $redirect !== '') {
                $_SESSION['login_redirect'] = $redirect;
            }
            header('Location: ' . LUPOPEDIA_PUBLIC_PATH . '/change-password');
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
$postedUsername = isset($_POST['username']) ? $_POST['username'] : '';
$lupoCurrentLocale = LupoLocale::getLocale();

?>
<!DOCTYPE html>
<html lang="<?= LupoLocale::htmlLang() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(lupo_t('login.html_title', 'Login - Lupopedia')); ?></title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($pub); ?>/lupo-templates/login/style.css">
    <script src="<?php echo htmlspecialchars($pub); ?>/lupo-includes/js/lupo-layers.js"></script>
    <script>
        window.lupoLoginSigningText = <?php echo json_encode(lupo_t('login.submit_pending', 'Signing In...')); ?>;
    </script>
    <script src="<?php echo htmlspecialchars($pub); ?>/lupo-templates/login/script.js"></script>
</head>
<body onload="initApp()">

    <div id="headerDiv" class="login-header">
        <img src="<?php echo htmlspecialchars($pub); ?>/lupo-images/wolfoverlogin.png" alt="<?php echo htmlspecialchars(lupo_t('login.logo_alt', 'Lupopedia')); ?>" class="login-logo">
    </div>

    <div id="loginDiv" class="login-container">
        <form class="login-form" method="POST" action="">
            <h2><?php echo htmlspecialchars(lupo_t('login.sign_in', 'Sign In')); ?></h2>
            <p class="form-subtitle"><?php echo htmlspecialchars(lupo_t('login.subtitle', 'Lupopedia')); ?></p>

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
                    <option value="en"<?php echo ($lupoCurrentLocale === 'en') ? ' selected' : ''; ?>><?php echo htmlspecialchars(lupo_t('login.locale_en', 'English')); ?></option>
                </select>
            </div>
            <div class="input-group">
                <label for="username"><?php echo htmlspecialchars(lupo_t('login.username', 'Username')); ?></label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="<?php echo htmlspecialchars(lupo_t('login.username_placeholder', 'Enter username')); ?>"
                    required
                    autofocus
                    value="<?php echo htmlspecialchars($postedUsername); ?>"
                >
            </div>
            <div class="input-group">
                <label for="password"><?php echo htmlspecialchars(lupo_t('login.password', 'Password')); ?></label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="<?php echo htmlspecialchars(lupo_t('login.password_placeholder', 'Enter password')); ?>"
                    required
                >
            </div>
            <button type="submit" class="login-btn"><?php echo htmlspecialchars(lupo_t('login.submit', 'Login')); ?></button>

            <div class="forgot-password">
                <a href="<?php echo htmlspecialchars($pub); ?>/forgot-password.php"><?php echo htmlspecialchars(lupo_t('login.forgot_password', 'Forgot your password?')); ?></a>
            </div>
        </form>
    </div>

</body>
</html>
