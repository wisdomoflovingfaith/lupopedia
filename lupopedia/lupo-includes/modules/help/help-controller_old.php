<?php
/**
 * wolfie.header.identity: help-controller
 * wolfie.header.placement: /lupo-includes/modules/help/help-controller.php
 * wolfie.header.version: 4.1.1
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Created help controller for handling help system routes: index, topic, search. Integrates with help-model for database access. Returns rendered HTML from views."
 *   mood: "00FF00"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. help-controller.php cannot be called directly.");
}

// Load required helpers
require_once(__DIR__ . '/help-model.php');

/**
 * Help Controller
 *
 * Handles help system routes:
 * - /help (GET: show help index)
 * - /help/{slug} (GET: show specific topic)
 * - /help/search (GET: search help topics)
 *
 * @package Lupopedia
 * @subpackage Modules
 */

/**
 * Handle help routes
 *
 * @param string $slug The route slug
 * @return string Rendered HTML output
 */
function help_handle_slug($slug) {
    // Normalize slug - remove leading slash, convert to lowercase
    $slug = ltrim(strtolower($slug), '/');
    $slug = preg_replace('/\.php$/', '', $slug); // Remove .php extension

    // Route to appropriate handler
    if ($slug === 'help' || $slug === 'help/') {
        return help_index();
    } elseif ($slug === 'help/search') {
        return help_search_controller();
    } elseif (preg_match('/^help\/(.+)$/', $slug, $matches)) {
        return help_topic($matches[1]);
    }

    // No match
    return '';
}

/**
 * Handle help index (list all topics)
 *
 * @return string Rendered HTML output
 */
function help_index() {
    $category = $_GET['category'] ?? null;
    $topics = help_get_all_by_category($category);
    $categories = help_get_categories();

    ob_start();
    include __DIR__ . '/views/index.php';
    return ob_get_clean();
}

/**
 * Handle help topic view (single topic by slug)
 *
 * @param string $slug The topic slug
 * @return string Rendered HTML output
 */
function help_topic($slug) {
    $topic = help_get_by_slug($slug);

    if (!$topic) {
        http_response_code(404);
        ob_start();
        include __DIR__ . '/views/topic.php';
        return ob_get_clean();
    }

    ob_start();
    include __DIR__ . '/views/topic.php';
    return ob_get_clean();
}

<?php

<?php

/**
 * Handle help search
 *
 * @return string Rendered HTML output
 */
// Include the help model which contains help_search function
require_once __DIR__ . '/help-model.php';

function help_search_controller() {
    $query = $_GET['q'] ?? '';
    $results = [];

    if (!empty($query)) {
        $results = help_search($query);
    }

    ob_start();
    include __DIR__ . '/views/search.php';
    return ob_get_clean();
}
