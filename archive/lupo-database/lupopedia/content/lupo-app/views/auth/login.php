<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lupopedia Unified Authentication</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            min-height: 500px;
            display: flex;
        }

        .login-form-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand-section {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .brand-logo {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .brand-text {
            font-size: 1.1em;
            line-height: 1.6;
            opacity: 0.9;
        }

        .form-title {
            font-size: 2em;
            color: #333;
            margin-bottom: 10px;
        }

        .form-subtitle {
            color: #666;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .login-button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .login-button:hover {
            transform: translateY(-2px);
        }

        .system-context {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }

        .context-label {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 5px;
        }

        .context-value {
            font-weight: 600;
            color: #333;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .help-links {
            margin-top: 20px;
            text-align: center;
        }

        .help-links a {
            color: #667eea;
            text-decoration: none;
            margin: 0 10px;
        }

        .help-links a:hover {
            text-decoration: underline;
        }

        .oauth-divider {
            display: flex;
            align-items: center;
            margin: 30px 0 20px 0;
            color: #999;
            font-size: 14px;
        }

        .oauth-divider::before,
        .oauth-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e1e5e9;
        }

        .oauth-divider::before {
            margin-right: 10px;
        }

        .oauth-divider::after {
            margin-left: 10px;
        }

        .oauth-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }

        .oauth-button {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 5px;
            background: white;
            color: #333;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .oauth-button:hover {
            border-color: #667eea;
            background: #f8f9fa;
            transform: translateY(-1px);
        }

        .oauth-button svg {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        .oauth-button.google {
            border-color: #4285f4;
        }

        .oauth-button.google:hover {
            background: #4285f4;
            color: white;
        }

        .oauth-button.github {
            border-color: #333;
        }

        .oauth-button.github:hover {
            background: #333;
            color: white;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 400px;
            }

            .brand-section {
                padding: 30px 20px;
            }

            .login-form-section {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-section">
            <div class="brand-logo">LUPOPEDIA</div>
            <div class="brand-text">
                Unified Authentication System<br>
                Access both Lupopedia and Crafty Syntax with a single login
            </div>
        </div>

        <div class="login-form-section">
            <h1 class="form-title">Welcome Back</h1>
            <p class="form-subtitle">Sign in to access your account</p>

            <?php if(session('error')): ?>
                <div class="error-message">
                    <?php echo e(session('error')); ?>
                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="success-message">
                    <?php echo e(session('success')); ?>
                </div>
            <?php endif; ?>

            <?php
            // Detect system context from referrer or request
            $systemContext = 'lupopedia';
            $contextText = 'Lupopedia';
            
            if (isset($_SERVER['HTTP_REFERER'])) {
                $referer = $_SERVER['HTTP_REFERER'];
                if (strpos($referer, 'crafty_syntax') !== false || strpos($referer, 'livehelp') !== false) {
                    $systemContext = 'crafty_syntax';
                    $contextText = 'Crafty Syntax Live Help';
                }
            }
            
            // Check if we're coming from a legacy path
            $currentPath = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
            if (strpos($currentPath, 'legacy') !== false || strpos($currentPath, 'crafty') !== false) {
                $systemContext = 'crafty_syntax';
                $contextText = 'Crafty Syntax Live Help';
            }
            ?>

            <div class="system-context">
                <div class="context-label">System Context:</div>
                <div class="context-value"><?php echo htmlspecialchars($contextText); ?></div>
            </div>

            <?php
            // Check if OAuth providers are configured
            $oauthConfigured = false;
            $googleConfigured = defined('OAUTH_GOOGLE_CLIENT_ID') && !empty(OAUTH_GOOGLE_CLIENT_ID);
            $githubConfigured = defined('OAUTH_GITHUB_CLIENT_ID') && !empty(OAUTH_GITHUB_CLIENT_ID);
            $oauthConfigured = $googleConfigured || $githubConfigured;
            
            if ($oauthConfigured):
                $oauth_google_href = function_exists('lupo_index_slug_url') ? lupo_index_slug_url('oauth/login/google') : (rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/index.php?' . http_build_query(array('slug' => 'oauth/login/google')));
                $oauth_github_href = function_exists('lupo_index_slug_url') ? lupo_index_slug_url('oauth/login/github') : (rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/index.php?' . http_build_query(array('slug' => 'oauth/login/github')));
            ?>
            <div class="oauth-buttons">
                <?php if ($googleConfigured): ?>
                <a href="<?php echo htmlspecialchars($oauth_google_href, ENT_QUOTES, 'UTF-8'); ?>" class="oauth-button google">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continue with Google
                </a>
                <?php endif; ?>
                
                <?php if ($githubConfigured): ?>
                <a href="<?php echo htmlspecialchars($oauth_github_href, ENT_QUOTES, 'UTF-8'); ?>" class="oauth-button github">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.17 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.167 22 16.418 22 12c0-5.523-4.477-10-10-10z"/>
                    </svg>
                    Continue with GitHub
                </a>
                <?php endif; ?>
            </div>

            <div class="oauth-divider">or sign in with email</div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('unified.login')); ?>">
                <?php echo csrf_field(); ?>
                
                <input type="hidden" name="system_context" value="<?php echo htmlspecialchars($systemContext); ?>">

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
                        value="<?php echo e(old('email')); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Enter your password"
                        required
                    >
                </div>

                <button type="submit" class="login-button">
                    Sign In
                </button>
            </form>

            <div class="help-links">
                <a href="<?php echo $systemContext === 'crafty_syntax' ? '/legacy/craftysyntax/lostsheep.php' : '/password/reset'; ?>">
                    Forgot Password?
                </a>
                
                <?php if($systemContext === 'crafty_syntax'): ?>
                    <a href="/legacy/craftysyntax/login.php">Legacy Login</a>
                <?php else: ?>
                    <a href="/register">Create Account</a>
                <?php endif; ?>
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
            const submitButton = document.querySelector('.login-button');
            submitButton.textContent = 'Signing In...';
            submitButton.disabled = true;
        });
    </script>
</body>
</html>
