<?php
/**
 * Pack Behavior Routes
 *
 * Routes for Pack behavioral endpoints (profile, compatibility, etc.)
 *
 * @package Lupopedia
 * @version 4.0.109
 * @author Captain Wolfie
 */

// Pack behavior profile route
if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/pack/behavior/profile') {
    require_once __DIR__ . '/../app/Http/Controllers/PackBehaviorController.php';
    $controller = new \App\Http\Controllers\PackBehaviorController();
    echo $controller->profile();
    exit;
}

// Pack behavior compatibility route
if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/pack/behavior/compatibility') {
    require_once __DIR__ . '/../app/Http/Controllers/PackBehaviorController.php';
    $controller = new \App\Http\Controllers\PackBehaviorController();
    echo $controller->compatibility();
    exit;
}
