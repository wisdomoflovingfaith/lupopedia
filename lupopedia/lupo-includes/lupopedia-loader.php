<?php
 
/**
 * wolfie.header.identity: lupopedia-loader
 * wolfie.header.placement: /lupo-includes/lupopedia-loader.php
 * wolfie.header.version: lupopedia_current_version
 *
 * wolfie.header.dialog:
 *   speaker: Wolfie
 *   target: Lupopedia Loader
 *   message: "Added the initial orchestrator structure for loading modules, UI, agents, and semantic subsystems."
 *   mood: 339988
 *
 * wolfie.header.lineage:
 *   parent: bootstrap.php
 *   purpose: "Central system orchestrator; loads all core subsystems in deterministic order."
 
 * @package Lupopedia
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. lupopedia-loader.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * 1. Load Core Functions
 * ---------------------------------------------------------
 * Note: Session and auth helpers are already loaded in bootstrap.php
 * to ensure sessions start early and current_user() is available globally.
 */
$core_functions = ABSPATH . LUPO_INCLUDES_DIR . '/functions-core.php';
if (file_exists($core_functions)) {
    require_once $core_functions;
}

/**
 * Load Auth UI Helpers (for header/template integration)
 */
$auth_ui_helpers = ABSPATH . LUPO_INCLUDES_DIR . '/functions/auth-ui-helpers.php';
if (file_exists($auth_ui_helpers)) {
    require_once $auth_ui_helpers;
}

/**
 * ---------------------------------------------------------
 * 2. Load Module System
 * ---------------------------------------------------------
 */
$module_loader = ABSPATH . LUPO_INCLUDES_DIR . '/modules/module-loader.php';
$module_registry = ABSPATH . LUPO_INCLUDES_DIR . '/modules/module-registry.php';

if (file_exists($module_registry)) {
    require_once $module_registry;
}

if (file_exists($module_loader)) {
    require_once $module_loader;
}

/**
 * Load all active modules
 */
if (function_exists('lupo_load_active_modules')) {
    lupo_load_active_modules();
}

/**
 * ---------------------------------------------------------
 * 3. Load Semantic Engine
 * ---------------------------------------------------------
 */
$semantic_loader = ABSPATH . LUPO_INCLUDES_DIR . '/semantic/semantic-loader.php';
if (file_exists($semantic_loader)) {
    require_once $semantic_loader;
}

/**
 * ---------------------------------------------------------
 * 4. Load Agent Subsystem
 * ---------------------------------------------------------
 */
$agent_loader = ABSPATH . LUPO_INCLUDES_DIR . '/agents/agent-loader.php';
if (file_exists($agent_loader)) {
    require_once $agent_loader;
}

/**
 * ---------------------------------------------------------
 * 5. Load UI Subsystem
 * ---------------------------------------------------------
 */
$ui_loader = ABSPATH . LUPO_INCLUDES_DIR . '/ui/ui-loader.php';
if (file_exists($ui_loader)) {
    require_once $ui_loader;
}

/**
 * ---------------------------------------------------------
 * 6. Load REST API
 * ---------------------------------------------------------
 */
$rest_loader = ABSPATH . LUPO_INCLUDES_DIR . '/rest-api/rest-loader.php';
if (file_exists($rest_loader)) {
    require_once $rest_loader;
}

?>