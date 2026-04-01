<?php
/**
 * Reset Password Page - Allows users to reset their password with a token
 */

// Load config
require_once __DIR__ . '/lupopedia-config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$token = $_GET['token'] ?? '';
$error = '';
$success = '';

// Validate token
if (empty($token)) {
    $error = 'Invalid or missing reset token.';
} else {
    // Load required classes
    require_once LUPOPEDIA_PATH . '/lupo-includes/classes/DatabaseFactory.php';
    
    $db = DatabaseFactory::getConnection();
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    
    // Check if token exists and is valid
    $reset = $db->fetchRow(
        "SELECT pr.auth_user_id, pr.expiry_ymdhis, au.username 
         FROM {$prefix}password_resets pr
         INNER JOIN {$prefix}auth_users au ON pr.auth_user_id = au.auth_user_id
         WHERE pr.token = :token AND pr.is_deleted = 0",
        ['token' => $token]
    );
    
    if (!$reset) {
        $error = 'Invalid or expired reset token.';
    } elseif (gmdate('YmdHis') > $reset['expiry_ymdhis']) {
        $error = 'Reset token has expired. Please request a new one.';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($password)) {
        $error = 'Please enter a new password.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } else {
        // Update user password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        $db->execute(
            "UPDATE {$prefix}auth_users 
             SET password_hash = :password_hash, updated_ymdhis = :now 
             WHERE auth_user_id = :auth_user_id",
            [
                'password_hash' => $passwordHash,
                'now' => gmdate('YmdHis'),
                'auth_user_id' => $reset['auth_user_id']
            ]
        );
        
        // Mark reset token as used
        $db->execute(
            "UPDATE {$prefix}password_resets 
             SET is_deleted = 1, updated_ymdhis = :now 
             WHERE token = :token",
            [
                'now' => gmdate('YmdHis'),
                'token' => $token
            ]
        );
        
        $success = 'Your password has been reset successfully. You can now login with your new password.';
    }
}

$base = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - LUPOPEDIA</title>
    <link rel="icon" type="image/x-icon" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/favicon.ico">
    <link rel="stylesheet" href="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-includes/css/main.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .reset-password-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
            margin: 20px;
        }

        .brand-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .brand-logo img {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
        }

        .brand-text {
            font-size: 14px;
            opacity: 0.9;
            line-height: 1.4;
        }

        .form-section {
            padding: 40px 30px;
        }

        .form-title {
            color: #333;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
            text-align: center;
        }

        .form-subtitle {
            color: #666;
            font-size: 16px;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.2s ease;
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .submit-button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .submit-button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .submit-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s ease;
        }

        .back-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .password-requirements {
            background: #f8f9fa;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
            font-size: 14px;
            color: #666;
        }

        .password-requirements ul {
            margin: 8px 0 0 0;
            padding-left: 20px;
        }

        .password-requirements li {
            margin-bottom: 4px;
        }

        @media (max-width: 600px) {
            .reset-password-container {
                margin: 10px;
            }
            
            .brand-section, .form-section {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="reset-password-container">
        <div class="brand-section">
            <div class="brand-logo">
                <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/logo.png" alt="LUPOPEDIA">
            </div>
            <div class="brand-text">
                Multi-Agent Coordination System<br>
                Password Reset
            </div>
        </div>

        <div class="form-section">
            <h1 class="form-title">Reset Password</h1>
            <p class="form-subtitle">Enter your new password below</p>

            <?php if ($error): ?>
                <div class="error-message">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success-message">
                    <?= htmlspecialchars($success) ?>
                </div>
                <div class="back-link">
                    <a href="<?= $base ?>/login.php">Go to Login →</a>
                </div>
            <?php else: ?>
                <div class="password-requirements">
                    <strong>Password Requirements:</strong>
                    <ul>
                        <li>At least 8 characters long</li>
                        <li>Contains both letters and numbers</li>
                        <li>Recommended: Include special characters</li>
                    </ul>
                </div>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="Enter your new password"
                            required
                            minlength="8"
                            autofocus
                        >
                    </div>

                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            class="form-input" 
                            placeholder="Confirm your new password"
                            required
                            minlength="8"
                        >
                    </div>

                    <button type="submit" class="submit-button">
                        Reset Password
                    </button>
                </form>

                <div class="back-link">
                    <a href="<?= $base ?>/login.php">← Back to Login</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Auto-focus on password field
        document.addEventListener('DOMContentLoaded', function() {
            if (!document.querySelector('.success-message')) {
                document.getElementById('password').focus();
            }
        });

        // Handle form submission with loading state
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitButton = document.querySelector('.submit-button');
            submitButton.textContent = 'Resetting...';
            submitButton.disabled = true;
        });

        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>
