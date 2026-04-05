<?php
/**
 * Forgot Password Page - Allows users to request password reset
 */

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

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        $error = 'Please enter your email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Load required classes
        require_once LUPOPEDIA_PATH . '/lupo-includes/classes/DatabaseFactory.php';
        
        $db = DatabaseFactory::getConnection();
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        
        // Find user by email
        $user = $db->fetchRow(
            "SELECT auth_user_id, username, display_name FROM {$prefix}auth_users 
             WHERE email = :email AND is_active = 1 AND is_deleted = 0",
            ['email' => $email]
        );
        
        if (!$user) {
            // Don't reveal if email exists or not for security
            $success = 'If an account with that email exists, a password reset link has been sent.';
        } else {
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expiry = gmdate('YmdHis', strtotime('+1 hour'));
            
            // Store reset token
            $db->execute(
                "INSERT INTO {$prefix}password_resets (auth_user_id, token, expiry_ymdhis, created_ymdhis) 
                 VALUES (:auth_user_id, :token, :expiry, :now)
                 ON DUPLICATE KEY UPDATE token = :token, expiry_ymdhis = :expiry, created_ymdhis = :now",
                [
                    'auth_user_id' => $user['auth_user_id'],
                    'token' => $token,
                    'expiry' => $expiry,
                    'now' => gmdate('YmdHis')
                ]
            );
            
            // Send reset email
            $resetLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . 
                       '://' . $_SERVER['HTTP_HOST'] . LUPOPEDIA_PUBLIC_PATH . "/reset-password.php?token=" . $token;
            
            $subject = 'LUPOPEDIA Password Reset Request';
            $message = "Hello {$user['display_name']},\n\n";
            $message .= "You requested a password reset for your LUPOPEDIA account.\n\n";
            $message .= "Click the following link to reset your password:\n";
            $message .= $resetLink . "\n\n";
            $message .= "This link will expire in 1 hour.\n\n";
            $message .= "If you didn't request this reset, please ignore this email.\n\n";
            $message .= "Best regards,\nLUPOPEDIA Team";
            
            // Try to send email using PHPMailer
            try {
                // Load PHPMailer
                require_once LUPOPEDIA_PATH . '/lupo-includes/PHPMailer/src/PHPMailer.php';
                require_once LUPOPEDIA_PATH . '/lupo-includes/PHPMailer/src/SMTP.php';
                require_once LUPOPEDIA_PATH . '/lupo-includes/PHPMailer/src/Exception.php';
                
                $mail = new PHPMailer\PHPMailer\PHPMailer();
                
                // Configure mailer
                $mail->isSMTP();
                $mail->Host = defined('SMTP_HOST') ? SMTP_HOST : 'localhost';
                $mail->SMTPAuth = defined('SMTP_AUTH') && SMTP_AUTH;
                $mail->Username = defined('SMTP_USERNAME') ? SMTP_USERNAME : '';
                $mail->Password = defined('SMTP_PASSWORD') ? SMTP_PASSWORD : '';
                $mail->SMTPSecure = defined('SMTP_SECURE') ? SMTP_SECURE : '';
                $mail->Port = defined('SMTP_PORT') ? SMTP_PORT : 587;
                
                $mail->setFrom(defined('SMTP_FROM') ? SMTP_FROM : 'noreply@lupopedia.com', 'LUPOPEDIA');
                $mail->addAddress($email, $user['display_name']);
                
                $mail->Subject = $subject;
                $mail->Body = $message;
                $mail->AltBody = strip_tags($message);
                
                if ($mail->send()) {
                    $success = 'If an account with that email exists, a password reset link has been sent.';
                } else {
                    $error = 'Failed to send reset email. Please try again later.';
                }
                
            } catch (Exception $e) {
                // Fallback to mail() function
                if (mail($email, $subject, $message, "From: LUPOPEDIA <noreply@lupopedia.com>")) {
                    $success = 'If an account with that email exists, a password reset link has been sent.';
                } else {
                    $error = 'Failed to send reset email. Please try again later.';
                }
            }
        }
    }
}

$base = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - LUPOPEDIA</title>
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

        .forgot-password-container {
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

        @media (max-width: 600px) {
            .forgot-password-container {
                margin: 10px;
            }
            
            .brand-section, .form-section {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <div class="brand-section">
            <div class="brand-logo">
                <img src="<?= LUPOPEDIA_PUBLIC_PATH ?>/lupo-images/logo.png" alt="LUPOPEDIA">
            </div>
            <div class="brand-text">
                Multi-Agent Coordination System<br>
                Password Recovery
            </div>
        </div>

        <div class="form-section">
            <h1 class="form-title">Forgot Password</h1>
            <p class="form-subtitle">Enter your email to receive a password reset link</p>

            <?php if ($error): ?>
                <div class="error-message">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success-message">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="Enter your email address"
                        required
                        autofocus
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    >
                </div>

                <button type="submit" class="submit-button">
                    Send Reset Link
                </button>
            </form>

            <div class="back-link">
                <a href="<?= $base ?>/login.php">← Back to Login</a>
            </div>
        </div>
    </div>

    <script>
        // Auto-focus on email field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });

        // Handle form submission with loading state
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitButton = document.querySelector('.submit-button');
            submitButton.textContent = 'Sending...';
            submitButton.disabled = true;
        });
    </script>
</body>
</html>
