<?php
/**
 * FILE: includes/theme/theme-loader.php
 * TYPE: php
 *
 * Theme Loader subsystem. Resolves active theme from federation node, and provides
 * layout/component resolution (active → default → core UI) per Theme Directory Doctrine §19.
 * Installation Path Doctrine: all paths use LUPOPEDIA_PATH / LUPOPEDIA_PUBLIC_PATH only.
 * Read-only; no DB writes. No references to legacy/wordpress or legacy/craftysyntax.
 */

/**
 * Return the active theme slug for the current federation node.
 * Reads from lupo_federation_nodes.active_theme_slug (seeded with "default").
 * No DB writes. If missing or empty, returns "default".
 *
 * @return string Theme slug (e.g. "default", "basic")
 */
function lupo_get_active_theme_slug() {
    static $slug = null;
    if ($slug !== null) {
        return $slug;
    }
    $slug = 'default';
    $db = function_exists('lupo_get_db') ? lupo_get_db() : null;
    if ($db === null) {
        return $slug;
    }
    $node_id = defined('LUPO_DEFAULT_NODE_ID') ? (int) LUPO_DEFAULT_NODE_ID : 0;
    try {
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $tbl = $prefix . 'federation_nodes';
        $stmt = $db->prepare('SELECT active_theme_slug FROM ' . $tbl . ' WHERE federation_node_id = :nid AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1');
        $stmt->execute([':nid' => $node_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && isset($row['active_theme_slug']) && trim((string) $row['active_theme_slug']) !== '') {
            $slug = trim($row['active_theme_slug']);
        }
    } catch (Exception $e) {
        // Leave slug as "default"
    }
    return $slug;
}

/**
 * Return the absolute filesystem path for a file in the active theme directory.
 * Uses LUPOPEDIA_PATH only. No hardcoded installation paths.
 *
 * @param string $relative_path Path relative to theme root (e.g. "layouts/main_layout.php")
 * @return string Absolute path (e.g. .../includes/themes/default/layouts/main_layout.php)
 */
function lupo_theme_path($relative_path) {
    $base = rtrim(LUPOPEDIA_PATH, DIRECTORY_SEPARATOR);
    $slug = lupo_get_active_theme_slug();
    $rel = ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relative_path), DIRECTORY_SEPARATOR);
    return $base . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $slug . DIRECTORY_SEPARATOR . $rel;
}

/**
 * Return the public URL for a theme asset.
 * Uses LUPOPEDIA_PUBLIC_PATH only. $relative_path is relative to theme assets (e.g. "images/logo.png").
 *
 * @param string $relative_path Path relative to theme assets directory
 * @return string Public URL for the asset
 */
function lupo_theme_public_path($relative_path) {
    $base = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';
    $slug = lupo_get_active_theme_slug();
    $rel = ltrim($relative_path, '/');
    return $base . '/themes/' . $slug . '/assets/' . $rel;
}

/**
 * Resolve layout file path. Resolution order:
 * 1. themes/<active>/layouts/<file>
 * 2. themes/default/layouts/<file>
 * 3. includes/ui/layouts/<file>
 *
 * @param string $layout_filename Filename only (e.g. "main_layout.php")
 * @return string Resolved absolute path, or empty string if not found
 */
function lupo_resolve_layout($layout_filename) {
    $base = rtrim(LUPOPEDIA_PATH, DIRECTORY_SEPARATOR);
    $ds = DIRECTORY_SEPARATOR;
    $themes_base = $base . $ds . 'includes' . $ds . 'themes';
    $ui_layouts = $base . $ds . 'includes' . $ds . 'ui' . $ds . 'layouts' . $ds . $layout_filename;
    $active = lupo_get_active_theme_slug();
    $candidates = [
        $themes_base . $ds . $active . $ds . 'layouts' . $ds . $layout_filename,
        $themes_base . $ds . 'default' . $ds . 'layouts' . $ds . $layout_filename,
        $ui_layouts,
    ];
    foreach ($candidates as $path) {
        if (file_exists($path) && is_readable($path)) {
            return $path;
        }
    }
    return '';
}

/**
 * Resolve component file path. Resolution order:
 * 1. themes/<active>/components/<file>
 * 2. themes/default/components/<file>
 * 3. includes/ui/components/<file>
 *
 * @param string $component_filename Filename only (e.g. "topbar.php")
 * @return string Resolved absolute path, or empty string if not found
 */
function lupo_resolve_component($component_filename) {
    $base = rtrim(LUPOPEDIA_PATH, DIRECTORY_SEPARATOR);
    $ds = DIRECTORY_SEPARATOR;
    $themes_base = $base . $ds . 'includes' . $ds . 'themes';
    $ui_components = $base . $ds . 'includes' . $ds . 'ui' . $ds . 'components' . $ds . $component_filename;
    $active = lupo_get_active_theme_slug();
    $candidates = [
        $themes_base . $ds . $active . $ds . 'components' . $ds . $component_filename,
        $themes_base . $ds . 'default' . $ds . 'components' . $ds . $component_filename,
        $ui_components,
    ];
    foreach ($candidates as $path) {
        if (file_exists($path) && is_readable($path)) {
            return $path;
        }
    }
    return '';
}

/**
 * Include a layout file with context. Resolves via lupo_resolve_layout(), extracts $context
 * into variables, sets LUPO_UI_PATH to the theme root of the resolved layout, then includes.
 *
 * @param string $layout_filename Filename only (e.g. "main_layout.php")
 * @param array  $context         Key-value context for the layout
 * @return void
 */
function lupo_theme_include_layout($layout_filename, $context = []) {
    $resolved = lupo_resolve_layout($layout_filename);
    if ($resolved === '') {
        return;
    }
    $theme_root = dirname(dirname($resolved));
    if (!defined('LUPO_UI_PATH')) {
        define('LUPO_UI_PATH', $theme_root);
    }
    if (is_array($context)) {
        extract($context, EXTR_SKIP);
    }
    include $resolved;
}

/**
 * Include a component file with context. Resolves via lupo_resolve_component(), sets
 * LUPO_UI_PATH to the theme root so sub-includes (e.g. topbar) resolve,
 * extracts $context into variables, then includes.
 *
 * @param string $component_filename Filename only (e.g. "topbar.php")
 * @param array  $context            Key-value context for the component (default [])
 * @return void
 */
function lupo_theme_include_component($component_filename, $context = []) {
    $resolved = lupo_resolve_component($component_filename);
    if ($resolved === '') {
        return;
    }
    $theme_root = dirname(dirname($resolved));
    if (!defined('LUPO_UI_PATH')) {
        define('LUPO_UI_PATH', $theme_root);
    }
    if (is_array($context)) {
        extract($context, EXTR_SKIP);
    }
    include $resolved;
}
