<?php
/**
 * lupopedia.headers:
 *   header_format_version: "4.1.0"
 *   file_path_from_root: "content/index.php"
 *   web_path: "https://www.lupopedia.com/lupopedia/content/index.php"
 *   status: "complete"
 *   when_updated: "20260409202544"
 *   trust_tier: "canonical"
 *   questions_toon: null
 *   memory_toon: "lupo-memory/development/canonical/1026/04/content-index.toon"
 *   atoms_toon: null
 *   transcript_jsonl: "0/development/content-index"
 *   artifact_type: implementation
 *   artifact_kind: documentation
 *   channel_key: "development"
 *   federation_node_id: 0
 *   thread_id: ""
 *   content_id: null
 *   pk_id: null
 *   pk_slug: ""
 *   parent_pk_id: "06"
 *   lupopedia.schema: implementation
 *   title: "Content front controller"
 *   summary: "Physical-directory front controller for lupo_contents (no mod_rewrite); supports ?slug= and library index routing."
 */
/**
 * Physical front controller under /content/ — shared hosting without mod_rewrite.
 * Use: …/content/index.php?slug=my-page  or …/content/index.php for the library index.
 *
 * @package Lupopedia
 */

// Safe default for front controller: never expose errors before config is loaded.
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

define('LUPOPEDIA_PATH', dirname(__DIR__));
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'LupopediaConfigResolver.php';
define('LUPOPEDIA_PUBLIC_PATH', LupopediaConfigResolver::publicPathFromRequest(LUPOPEDIA_PATH));
$lupoCfg = LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, LUPOPEDIA_PUBLIC_PATH);

if ($lupoCfg === null || !LupopediaConfigResolver::isSafeLocalConfigPath($lupoCfg)) {
    $installUrl = rtrim(dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : ''), '/') . '/install.php';
    if ($installUrl === '/install.php') {
        $installUrl = '/install.php';
    }
    header('Location: ' . $installUrl);
    exit;
}

define('LUPOPEDIA_CONFIG_PATH', $lupoCfg);
require_once LUPOPEDIA_CONFIG_PATH;

if (!defined('LUPOPEDIA_CONFIG_LOADED') || !LUPOPEDIA_CONFIG_LOADED) {
    header('HTTP/1.1 500 Internal Server Error');
    exit;
}

// Enable visible PHP errors only when the canonical debug flag is explicitly on.
if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
}

require_once LUPOPEDIA_PATH . '/includes/modules/content/content-controller.php';

$slug = '';
if (isset($_GET['slug']) && $_GET['slug'] !== '') {
    $slug = is_string($_GET['slug']) ? trim($_GET['slug']) : '';
}

$widget_hints = array();
if (isset($_GET['artifact_type']) && is_string($_GET['artifact_type']) && $_GET['artifact_type'] !== '') {
    $widget_hints['artifact_type'] = $_GET['artifact_type'];
}
if (isset($_GET['memory_key']) && is_string($_GET['memory_key']) && $_GET['memory_key'] !== '') {
    $widget_hints['memory_key'] = $_GET['memory_key'];
}

if ($slug !== '') {
    echo content_show_by_slug($slug, $widget_hints);
} else {
    echo content_show_library_index();
}
