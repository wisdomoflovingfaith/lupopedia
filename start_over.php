<?php
// start_over.php -- Clear session and cache for a clean Lupopedia install
// Usage: Run this script in your browser or via CLI before reinstalling

// 1. Clear PHP session
session_start();
$_SESSION = array();
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}
session_destroy();

// 2. Remove all files in lupo-cache/ and lupo-tmp/ (except .htaccess)
function clear_dir($dir) {
    if (!is_dir($dir)) return;
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || $file === '.htaccess') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            clear_dir($path);
            @rmdir($path);
        } else {
            @unlink($path);
        }
    }
}
clear_dir(__DIR__ . '/lupo-cache');
clear_dir(__DIR__ . '/lupo-tmp');

// 3. Optionally clear PHP opcode cache (if enabled)
if (function_exists('opcache_reset')) {
    @opcache_reset();
}

// 4. Output result
header('Content-Type: text/plain');
echo "Lupopedia session and cache cleared.\nYou may now drop all tables and remove lupopedia-config.php manually.\nProceed with a fresh install.";
