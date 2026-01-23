<?php
/**
 * wolfie.header.identity: redirect-helpers
 * wolfie.header.placement: /lupo-includes/functions/redirect-helpers.php
 * wolfie.header.version: 4.0.9
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Created safe redirect helper function for version 4.0.9. Handles 'headers already sent' errors by checking if headers are sent before using header() redirect. If headers are already sent, falls back to meta refresh, JavaScript redirect, and clickable link (old-school approach that always works)."
 *   mood: "00FF00"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. redirect-helpers.php cannot be called directly.");
}

/**
 * Safe redirect function that handles "headers already sent" errors
 * 
 * Attempts header() redirect first. If headers are already sent,
 * falls back to meta refresh + JavaScript + clickable link.
 * 
 * @param string $url URL to redirect to (absolute or relative)
 * @param int $delay Delay in seconds for meta refresh (default: 3)
 * @param string $message Optional message to display (default: "Redirecting...")
 * @return void Exits after redirect
 */
function lupo_safe_redirect($url, $delay = 3, $message = null) {
    // Handle URLs that start with '/' but need LUPOPEDIA_PUBLIC_PATH prefix
    // CRITICAL: Lupopedia is always in a subdirectory, never at web root
    if (strpos($url, 'http') !== 0) {
        // Not a full HTTP URL - ensure it includes LUPOPEDIA_PUBLIC_PATH
        if (defined('LUPOPEDIA_PUBLIC_PATH') && LUPOPEDIA_PUBLIC_PATH !== '/') {
            // Check if URL already includes the public path
            $has_public_path = (strpos($url, LUPOPEDIA_PUBLIC_PATH) === 0);
            
            if (!$has_public_path) {
                // URL doesn't include public path - prepend it
                if (strpos($url, '/') === 0) {
                    // URL starts with '/' - prepend public path
                    $url = LUPOPEDIA_PUBLIC_PATH . $url;
                } else {
                    // URL doesn't start with '/' - prepend public path with /
                    $url = LUPOPEDIA_PUBLIC_PATH . '/' . ltrim($url, '/');
                }
            }
            // If URL already has public path, use it as-is
        } else {
            // LUPOPEDIA_PUBLIC_PATH not defined or is root - ensure URL starts with /
            if (strpos($url, '/') !== 0) {
                $url = '/' . ltrim($url, '/');
            }
        }
    }
    
    // Sanitize URL (prevent open redirect)
    $url = filter_var($url, FILTER_SANITIZE_URL);
    if (empty($url) || (strpos($url, 'http') === 0 && strpos($url, $_SERVER['HTTP_HOST'] ?? '') === false)) {
        // Invalid or external URL - redirect to home with public path
        $url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/' : '/';
    }
    
    // Try header() redirect first (fastest, cleanest)
    if (!headers_sent()) {
        header('Location: ' . $url);
        exit;
    }
    
    // Headers already sent - use fallback (old-school approach)
    // This always works, even if output has started
    $default_message = $message ?: 'Redirecting...';
    $escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    $escaped_message = htmlspecialchars($default_message, ENT_QUOTES, 'UTF-8');
    
    // Output HTML with meta refresh, JavaScript, and clickable link
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="' . (int)$delay . ';url=' . $escaped_url . '">
    <title>' . $escaped_message . '</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: #f5f5f5;
        }
        .redirect-container {
            text-align: center;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 500px;
        }
        .redirect-message {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 1.5rem;
        }
        .redirect-link {
            display: inline-block;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: background 0.2s;
        }
        .redirect-link:hover {
            background: #0056b3;
        }
        .redirect-url {
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #666;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="redirect-container">
        <div class="redirect-message">' . $escaped_message . '</div>
        <a href="' . $escaped_url . '" class="redirect-link">Click here if you are not redirected automatically</a>
        <div class="redirect-url">' . $escaped_url . '</div>
    </div>
    <script>
        // JavaScript redirect as additional fallback
        setTimeout(function() {
            window.location.href = ' . json_encode($url, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ';
        }, ' . ($delay * 1000) . ');
    </script>
</body>
</html>';
    exit;
}
