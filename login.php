<?php
/**
 * Login Page - Simple implementation for auth_user to actor mapping
 */

// Load config
require_once __DIR__ . '/lupopedia-config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
        $error = 'Please enter both username and password.';
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lupopedia</title>
    <script src="<?php echo htmlspecialchars($pub); ?>/lupo-includes/js/lupo-layers.js"></script>
    <style>
        body {
            background-color: #1a1a1a;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .login-header {
            position: absolute;
            width: 200px;
            height: auto;
            left: 50%;
            margin-left: -100px;
            z-index: 1;
            top: 300px;
        }

        .login-logo {
            width: 100%;
            display: block;
        }

        .login-container {
            position: absolute;
            width: 380px;
            max-width: calc(100vw - 32px);
            background-color: #ffffff;
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            left: 50%;
            top: 200px;
            margin-left: -190px;
            z-index: 2;
            box-sizing: border-box;
        }

        @media (max-width: 420px) {
            .login-container {
                left: 50%;
                margin-left: 0;
                transform: translateX(-50%);
                width: calc(100vw - 24px);
                padding: 28px 20px;
            }
        }

        .login-form h2 {
            text-align: center;
            margin-bottom: 8px;
            color: #333;
        }

        .login-form .form-subtitle {
            text-align: center;
            color: #666;
            font-size: 0.9em;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 12px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .input-group input:focus {
            outline: none;
            border-color: #333;
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
        }

        .login-btn:hover:not(:disabled) {
            background-color: #222;
        }

        .login-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
            border: 1px solid #f5c6cb;
            font-size: 0.9em;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
            border: 1px solid #c3e6cb;
            font-size: 0.9em;
        }

        .forgot-password {
            text-align: center;
            margin-top: 18px;
        }

        .forgot-password a {
            color: #555;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password a:hover {
            color: #333;
            text-decoration: underline;
        }
    </style>
</head>
<body onload="initApp()">

    <div id="headerDiv" class="login-header">
        <img src="<?php echo htmlspecialchars($pub); ?>/lupo-images/logo.png" alt="Lupopedia" class="login-logo">
    </div>

    <div id="loginDiv" class="login-container">
        <form class="login-form" method="POST" action="">
            <h2>Sign In</h2>
            <p class="form-subtitle">Lupopedia</p>

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
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Enter username"
                    required
                    autofocus
                    value="<?php echo htmlspecialchars($postedUsername); ?>"
                >
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter password"
                    required
                >
            </div>
            <button type="submit" class="login-btn">Login</button>

            <div class="forgot-password">
                <a href="<?php echo htmlspecialchars($pub); ?>/forgot-password.php">Forgot your password?</a>
            </div>
        </form>
    </div>

    <script>
        function initApp() {
            if (typeof LupoLayer === 'undefined') {
                return;
            }
            var wolf = new LupoLayer('headerDiv');
            var loginBox = new LupoLayer('loginDiv');
            if (!wolf.elm) {
                return;
            }
            wolf.onSlideEnd = function () {
                wolf.setZ(10);
                if (loginBox.elm) {
                    loginBox.setZ(5);
                }
            };
            var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            var centerX = (screenWidth / 2) - 100;
            var peeringY = 150;
            wolf.slideTo(centerX, peeringY, 600);
        }

        document.addEventListener('DOMContentLoaded', function() {
            var form = document.querySelector('.login-form');
            if (!form) {
                return;
            }
            form.addEventListener('submit', function() {
                var submitButton = document.querySelector('.login-btn');
                if (submitButton) {
                    submitButton.textContent = 'Signing In...';
                    submitButton.disabled = true;
                }
            });
        });
    </script>
</body>
</html>
