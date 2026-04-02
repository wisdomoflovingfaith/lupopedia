<?php
/**
 * Logout handler
 */

// Load config - try multiple paths
$configPaths = [
    __DIR__ . '/lupopedia-config.php',
    __DIR__ . '/../lupopedia-config.php',
    dirname(__DIR__) . '/lupopedia-config.php',
    __DIR__ . '/config.php',  // fallback
    __DIR__ . '/../config.php'  // fallback
];

$configLoaded = false;
foreach ($configPaths as $configPath) {
    if (file_exists($configPath)) {
        require_once $configPath;
        $configLoaded = true;
        break;
    }
}

if (!$configLoaded) {
    die("Configuration file not found. Please ensure lupo-config.php exists.");
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load required classes
require_once __DIR__ . '/lupo-includes/classes/DatabaseFactory.php';
require_once __DIR__ . '/lupo-includes/classes/AuthService.php';


// Ensure auth_user_id is in session (if not, try to resolve from actor_id)
if (!isset($_SESSION['auth_user_id']) && isset($_SESSION['actor_id'])) {
    require_once __DIR__ . '/lupo-includes/classes/AuthSessionManager.php';
    $leaseManager = new AuthSessionManager();
    $actor_id = $_SESSION['actor_id'];
    $auth_user_id = $leaseManager->getAuthUserId($actor_id);
    if ($auth_user_id) {
        $_SESSION['auth_user_id'] = $auth_user_id;
    }
}

$authService = new AuthService();
$authService->logout();

// Redirect to login page
header('Location: /lupopedia/login.php');
exit;
