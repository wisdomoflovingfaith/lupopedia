<?php
/**
 * wolfie.header.identity: functions-core
 * wolfie.header.placement: /includes/functions-core.php
 * wolfie.header.version: lupopedia_current_version
 * wolfie.header.dialog:
 *   speaker: Wolfie
 *   target: functions-core
 *   message: "Created initial skeleton for core functions subsystem."
 * wolfie.header.mood.label: focused
 * wolfie.header.mood.rgb: "336699"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. functions-core.php cannot be called directly.");
}

// Core utility functions
$actor_helpers = __DIR__ . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR . 'actor-helpers.php';
if (file_exists($actor_helpers)) {
    require_once $actor_helpers;
}
?>
