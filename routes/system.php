<?php
/**
 * System Routes
 *
 * Routes for system-level endpoints (health checks, diagnostics, etc.)
 *
 * @package Lupopedia
 * @version 4.0.106
 * @author Captain Wolfie
 */

// System health check route
if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/system/health') {
    require_once __DIR__ . '/../app/Http/Controllers/SystemHealthController.php';
    $controller = new \App\Http\Controllers\SystemHealthController();
    echo $controller->health();
    exit;
}
