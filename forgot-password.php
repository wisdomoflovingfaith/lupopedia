<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: page
  when_updated: "20260406011853"
  file_path_from_root: "forgot-password.php"
  web_path: "http://www.lupopedia.com/lupopedia/forgot-password.php"
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
  purpose: "Password reset request; portable SQL; UNTRUSTED post/server; no session; lupo_t + LupoLocale."
  tags: ["auth", "password", "reset", "email", "locale"]
---
*/

$UNTRUSTED = array(
    'post' => (isset($_POST) && is_array($_POST)) ? $_POST : array(),
    'server' => (isset($_SERVER) && is_array($_SERVER)) ? $_SERVER : array(),
);

$lupoRoot = __DIR__;
require_once $lupoRoot . '/includes/classes/LupopediaConfigResolver.php';
$lupoPub = LupopediaConfigResolver::publicPathFromRequest($lupoRoot);
$lupoCfgPath = LupopediaConfigResolver::resolve($lupoRoot, $lupoPub);
if ($lupoCfgPath === null) {
    $lupoScript = isset($UNTRUSTED['server']['SCRIPT_NAME']) ? $UNTRUSTED['server']['SCRIPT_NAME'] : '';
    $lupoBase = rtrim(dirname($lupoScript), '/');
    header('Location: ' . ($lupoBase === '' || $lupoBase === '.' ? '/install.php' : $lupoBase . '/install.php'));
    exit;
}
require_once $lupoCfgPath;

require_once $lupoRoot . '/includes/classes/LupoLocale.php';
LupoLocale::bootstrap($lupoRoot);
require_once $lupoRoot . '/includes/i18n.php';

$error = '';
$success = '';

$request_method = isset($UNTRUSTED['server']['REQUEST_METHOD']) ? $UNTRUSTED['server']['REQUEST_METHOD'] : '';

if ($request_method === 'POST') {
    $email = isset($UNTRUSTED['post']['email']) ? trim((string) $UNTRUSTED['post']['email']) : '';

    if ($email === '') {
        $error = lupo_t('forgot.error.email_required', 'Please enter your email address.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = lupo_t('forgot.error.email_invalid', 'Please enter a valid email address.');
    } else {
        require_once LUPOPEDIA_PATH . '/includes/classes/DatabaseFactory.php';

        $db = DatabaseFactory::getConnection();
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

        $user = $db->fetchRow(
            "SELECT auth_user_id, username, display_name FROM {$prefix}auth_users 
             WHERE email = :email AND is_active = 1 AND is_deleted = 0",
            array('email' => $email)
        );

        if (!$user) {
            $success = lupo_t('forgot.success_sent', 'If an account with that email exists, a password reset link has been sent.');
        } else {
            $token = bin2hex(random_bytes(32));
            if (!class_exists('timestamp_ymdhis', false)) {
                require_once $lupoRoot . '/includes/classes/TimestampYmdhis.php';
            }
            $nowPacked = timestamp_ymdhis::now();
            $expiry = (int) timestamp_ymdhis::addHours((int) $nowPacked, 1);

            $existing = $db->fetchRow(
                "SELECT password_reset_id FROM {$prefix}password_resets 
                 WHERE auth_user_id = :auth_user_id AND is_deleted = 0 
                 ORDER BY created_ymdhis DESC LIMIT 1",
                array('auth_user_id' => $user['auth_user_id'])
            );

            if ($existing && isset($existing['password_reset_id'])) {
                $db->query(
                    "UPDATE {$prefix}password_resets SET token = :token, expiry_ymdhis = :expiry, created_ymdhis = :now, updated_ymdhis = :now2 
                     WHERE password_reset_id = :prid",
                    array(
                        'token' => $token,
                        'expiry' => $expiry,
                        'now' => (int) $nowPacked,
                        'now2' => (int) $nowPacked,
                        'prid' => $existing['password_reset_id'],
                    )
                );
            } else {
                $nextRow = $db->fetchRow(
                    "SELECT COALESCE(MAX(password_reset_id), 0) AS m FROM {$prefix}password_resets"
                );
                $nextId = ($nextRow && isset($nextRow['m'])) ? ((int) $nextRow['m'] + 1) : 1;
                $db->query(
                    "INSERT INTO {$prefix}password_resets (password_reset_id, auth_user_id, token, expiry_ymdhis, created_ymdhis, updated_ymdhis, is_deleted) 
                     VALUES (:password_reset_id, :auth_user_id, :token, :expiry_ymdhis, :created_ymdhis, :updated_ymdhis, :is_deleted)",
                    array(
                        'password_reset_id' => $nextId,
                        'auth_user_id' => $user['auth_user_id'],
                        'token' => $token,
                        'expiry_ymdhis' => $expiry,
                        'created_ymdhis' => (int) $nowPacked,
                        'updated_ymdhis' => null,
                        'is_deleted' => 0,
                    )
                );
            }

            $httpsOn = isset($UNTRUSTED['server']['HTTPS']) && $UNTRUSTED['server']['HTTPS'] !== '' && $UNTRUSTED['server']['HTTPS'] !== 'off';
            $scheme = $httpsOn ? 'https' : 'http';
            $host = isset($UNTRUSTED['server']['HTTP_HOST']) ? (string) $UNTRUSTED['server']['HTTP_HOST'] : '';
            $resetLink = $scheme . '://' . $host . LUPOPEDIA_PUBLIC_PATH . '/reset-password.php?token=' . rawurlencode($token);

            $displayName = isset($user['display_name']) ? (string) $user['display_name'] : '';
            $subject = lupo_t('forgot.mail_subject', 'Lupopedia password reset request');

            $greeting = sprintf(lupo_t('forgot.mail_greeting', 'Hello %s,'), $displayName);
            $message = $greeting . "\n\n";
            $message .= lupo_t('forgot.mail_intro', 'You requested a password reset for your Lupopedia account.') . "\n\n";
            $message .= lupo_t('forgot.mail_click', 'Click the following link to reset your password:') . "\n";
            $message .= $resetLink . "\n\n";
            $message .= lupo_t('forgot.mail_expiry', 'This link will expire in 1 hour.') . "\n\n";
            $message .= lupo_t('forgot.mail_ignore', 'If you did not request this reset, please ignore this email.') . "\n\n";
            $message .= lupo_t('forgot.mail_signature', 'Best regards,') . "\n";
            $message .= lupo_t('forgot.mail_team', 'Lupopedia');

            $fromLabel = lupo_t('forgot.mail_from_label', 'Lupopedia');

            try {
                require_once LUPOPEDIA_PATH . '/includes/PHPMailer/src/PHPMailer.php';
                require_once LUPOPEDIA_PATH . '/includes/PHPMailer/src/SMTP.php';
                require_once LUPOPEDIA_PATH . '/includes/PHPMailer/src/Exception.php';

                $mail = new PHPMailer\PHPMailer\PHPMailer();

                $mail->isSMTP();
                $mail->Host = defined('SMTP_HOST') ? SMTP_HOST : 'localhost';
                $mail->SMTPAuth = defined('SMTP_AUTH') && SMTP_AUTH;
                $mail->Username = defined('SMTP_USERNAME') ? SMTP_USERNAME : '';
                $mail->Password = defined('SMTP_PASSWORD') ? SMTP_PASSWORD : '';
                $mail->SMTPSecure = defined('SMTP_SECURE') ? SMTP_SECURE : '';
                $mail->Port = defined('SMTP_PORT') ? SMTP_PORT : 587;

                $mail->setFrom(defined('SMTP_FROM') ? SMTP_FROM : 'noreply@lupopedia.com', $fromLabel);
                $mail->addAddress($email, $displayName);

                $mail->Subject = $subject;
                $mail->Body = $message;
                $mail->AltBody = strip_tags($message);

                if ($mail->send()) {
                    $success = lupo_t('forgot.success_sent', 'If an account with that email exists, a password reset link has been sent.');
                } else {
                    $error = lupo_t('forgot.error_mail_failed', 'Failed to send reset email. Please try again later.');
                }
            } catch (Exception $e) {
                $mailHeaders = 'From: ' . $fromLabel . ' <noreply@lupopedia.com>';
                if (mail($email, $subject, $message, $mailHeaders)) {
                    $success = lupo_t('forgot.success_sent', 'If an account with that email exists, a password reset link has been sent.');
                } else {
                    $error = lupo_t('forgot.error_mail_failed', 'Failed to send reset email. Please try again later.');
                }
            }
        }
    }
}

$base = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';
$form_email = isset($UNTRUSTED['post']['email']) ? (string) $UNTRUSTED['post']['email'] : '';
$js_sending = json_encode(lupo_t('forgot.button_sending', 'Sending...'));
?>
<!DOCTYPE html>
<html lang="<?php echo LupoLocale::htmlLang(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(lupo_t('forgot.title', 'Forgot Password - Lupopedia')); ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo htmlspecialchars(LUPOPEDIA_PUBLIC_PATH); ?>/favicon.ico">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(LUPOPEDIA_PUBLIC_PATH); ?>/includes/css/main.css">
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
                <img src="<?php echo htmlspecialchars(LUPOPEDIA_PUBLIC_PATH); ?>/images/logo.png" alt="<?php echo htmlspecialchars(lupo_t('forgot.logo_alt', 'Lupopedia')); ?>">
            </div>
            <div class="brand-text">
                <?php echo htmlspecialchars(lupo_t('forgot.brand_tagline', 'Multi-Agent Coordination System')); ?><br>
                <?php echo htmlspecialchars(lupo_t('forgot.brand_recovery', 'Password Recovery')); ?>
            </div>
        </div>

        <div class="form-section">
            <h1 class="form-title"><?php echo htmlspecialchars(lupo_t('forgot.form_title', 'Forgot Password')); ?></h1>
            <p class="form-subtitle"><?php echo htmlspecialchars(lupo_t('forgot.form_subtitle', 'Enter your email to receive a password reset link')); ?></p>

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

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email" class="form-label"><?php echo htmlspecialchars(lupo_t('forgot.email_label', 'Email Address')); ?></label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        placeholder="<?php echo htmlspecialchars(lupo_t('forgot.email_placeholder', 'Enter your email address')); ?>"
                        required
                        autofocus
                        value="<?php echo htmlspecialchars($form_email); ?>"
                    >
                </div>

                <button type="submit" class="submit-button">
                    <?php echo htmlspecialchars(lupo_t('forgot.button_send', 'Send Reset Link')); ?>
                </button>
            </form>

            <div class="back-link">
                <a href="<?php echo htmlspecialchars($base . '/login.php'); ?>">&larr; <?php echo htmlspecialchars(lupo_t('forgot.back_to_login', 'Back to Login')); ?></a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var el = document.getElementById('email');
            if (el) {
                el.focus();
            }
        });

        var form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                var submitButton = document.querySelector('.submit-button');
                if (submitButton) {
                    submitButton.textContent = <?php echo $js_sending; ?>;
                    submitButton.disabled = true;
                }
            });
        }
    </script>
</body>
</html>
