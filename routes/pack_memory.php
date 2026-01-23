<?php
/**
 * Pack Memory Routes
 *
 * Routes for Pack memory endpoints (episodic, emotional, behavioral, handoffs)
 *
 * @package Lupopedia
 * @version 4.0.110
 * @author Captain Wolfie
 */

// Pack memory episodic route
if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/pack/memory/episodic') {
    require_once __DIR__ . '/../app/Http/Controllers/PackMemoryController.php';
    $controller = new \App\Http\Controllers\PackMemoryController();
    echo $controller->episodic();
    exit;
}

// Pack memory emotional route
if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/pack/memory/emotional') {
    require_once __DIR__ . '/../app/Http/Controllers/PackMemoryController.php';
    $controller = new \App\Http\Controllers\PackMemoryController();
    echo $controller->emotional();
    exit;
}

// Pack memory behavioral route
if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/pack/memory/behavioral') {
    require_once __DIR__ . '/../app/Http/Controllers/PackMemoryController.php';
    $controller = new \App\Http\Controllers\PackMemoryController();
    echo $controller->behavioral();
    exit;
}

// Pack memory handoffs route
if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/pack/memory/handoffs') {
    require_once __DIR__ . '/../app/Http/Controllers/PackMemoryController.php';
    $controller = new \App\Http\Controllers\PackMemoryController();
    echo $controller->handoffs();
    exit;
}
