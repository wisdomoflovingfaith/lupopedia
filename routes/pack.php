<?php
/**
 * Pack Routes
 *
 * Routes for Pack Architecture endpoints (warm-start, handoff, etc.)
 *
 * @package Lupopedia
 * @version 4.0.107
 * @author Captain Wolfie
 */

// Pack warm-start route
if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/pack/warm-start') {
    require_once __DIR__ . '/../app/Http/Controllers/PackWarmStartController.php';
    $controller = new \App\Http\Controllers\PackWarmStartController();
    echo $controller->warmStart();
    exit;
}
