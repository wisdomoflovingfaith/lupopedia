<?php
/**
 * Emotional Geometry Routes
 *
 * Routes for emotional geometry endpoints (affinity, intensity, etc.)
 *
 * @package Lupopedia
 * @version 4.0.108
 * @author Captain Wolfie
 */

// Emotional geometry affinity route
if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/emotion/affinity') {
    require_once __DIR__ . '/../app/Http/Controllers/EmotionalGeometryController.php';
    $controller = new \App\Http\Controllers\EmotionalGeometryController();
    echo $controller->affinity();
    exit;
}
