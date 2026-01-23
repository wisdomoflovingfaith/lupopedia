<?php
/**
 * Pack Synchronization Routes
 *
 * Routes for Pack synchronization endpoints (run, emotions, behavior, memory)
 *
 * @package Lupopedia
 * @version 4.0.112
 * @author Captain Wolfie
 */

// Pack sync run route
if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/pack/sync/run') {
    require_once __DIR__ . '/../app/Http/Controllers/PackSyncController.php';
    $controller = new \App\Http\Controllers\PackSyncController();
    echo $controller->run();
    exit;
}

// Pack sync emotions route
if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/pack/sync/emotions') {
    require_once __DIR__ . '/../app/Http/Controllers/PackSyncController.php';
    $controller = new \App\Http\Controllers\PackSyncController();
    echo $controller->emotions();
    exit;
}

// Pack sync behavior route
if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/pack/sync/behavior') {
    require_once __DIR__ . '/../app/Http/Controllers/PackSyncController.php';
    $controller = new \App\Http\Controllers\PackSyncController();
    echo $controller->behavior();
    exit;
}

// Pack sync memory route
if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/pack/sync/memory') {
    require_once __DIR__ . '/../app/Http/Controllers/PackSyncController.php';
    $controller = new \App\Http\Controllers\PackSyncController();
    echo $controller->memory();
    exit;
}
