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
            $currentPath = $_SERVER['REQUEST_URI'] ?? '';
            if (strpos($currentPath, 'legacy') !== false || strpos($currentPath, 'crafty') !== false) {
                $systemContext = 'crafty_syntax';
                $contextText = 'Crafty Syntax Live Help';
            }
            ?>

            <div class="system-context">
                <div class="context-label">System Context:</div>
                <div class="context-value"><?php echo htmlspecialchars($contextText); ?></div>
            </div>

            <form method="POST" action="<?php echo e(route('unified.login')); ?>">
                <?php echo csrf_field(); ?>

                <input type="hidden" name="system_context" value="<?php echo htmlspecialchars($systemContext); ?>">
                <?php if (!empty($redirect)): ?>
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
                <?php endif; ?>

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
