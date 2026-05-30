<?php
/**
 * Web path resolution helper (4.0.18).
 * Provides lupo_resolve_web_path() using UrlResolver (DB → CSV → .md).
 * Doctrine: docs/channels/doctrine/WEB_ROUTING_DOCTRINE_4_0_18.md §2.1, §4.2.
 *
 * @package Lupopedia
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    return;
}

if (!class_exists('UrlResolver')) {
    $resolver_class = defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : (defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : '');
    $resolver_class = rtrim(str_replace('\\', '/', $resolver_class), '/') . '/includes/classes/UrlResolver.php';
    if (is_file($resolver_class)) {
        require_once $resolver_class;
    }
}

/**
 * Resolve a web request path to content_id, file_path, and canonical.
 * Uses three-tier source: DB (lupo_contents) → CSV (flip_headers.csv) → .md FLIP parse.
 * Logs a warning when using CSV or .md fallback.
 *
 * @param string $request_path Request path (e.g. doctrine/FLIP/FLIP_DOCTRINE or /docs/FLIP_DOCTRINE)
 * @return array|null Associative array: content_id, file_path, canonical, is_alias, source, slug_encoding, alias_redirect; or null if not found
 */
function lupo_resolve_web_path($request_path) {
    if (!class_exists('UrlResolver')) {
        return null;
    }
    $repo_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : (defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : '');
    if ($repo_root === '') {
        return null;
    }
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    $alias_redirect = true;
    $log_fallback = true;
    $resolver = new UrlResolver($prefix, $repo_root, $alias_redirect, $log_fallback);
    return $resolver->resolve($request_path);
}

/**
 * Get a UrlResolver instance (e.g. for cache invalidation or multiple lookups).
 *
 * @return UrlResolver|null
 */
function lupo_get_url_resolver() {
    if (!class_exists('UrlResolver')) {
        return null;
    }
    $repo_root = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : (defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : '');
    if ($repo_root === '') {
        return null;
    }
    $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    return new UrlResolver($prefix, $repo_root, true, true);
}

/**
 * Invalidate all web path resolver caches (APCu and/or file). Call after flip_headers.csv change, flip_header_audit.py run, or installer/seed. T6.
 */
function lupo_invalidate_web_path_cache() {
    $resolver = lupo_get_url_resolver();
    if ($resolver && method_exists($resolver, 'invalidateAllCaches')) {
        $resolver->invalidateAllCaches();
    }
    if ($resolver && method_exists($resolver, 'invalidateCsvCache')) {
        $resolver->invalidateCsvCache();
    }
}

/**
 * Smart 404 for web paths (doctrine/qa/docs/flp). Returns data for template; suggestions only when authenticated.
 * Prefix-optimized: filter candidates by first 3 chars of slug, fall back to full list if fewer than 5. T4.
 *
 * @param string $request_path The path that did not resolve (e.g. doctrine/FLIP/FLIP_DOCTRIN)
 * @param bool $is_authenticated If true, compute and return suggestions; if false, suggestions are empty
 * @return array array('status' => 'smart_404', 'suggestions' => array(), 'requested' => $request_path)
 */
function lupo_smart_404($request_path, $is_authenticated) {
    $path = UrlResolver::normalizePath($request_path);
    $slug = $path !== '' ? basename(str_replace('\\', '/', $path)) : '';
    if ($slug === '') {
        $slug = $path;
    }
    $out = array(
        'status' => 'smart_404',
        'suggestions' => array(),
        'requested' => $request_path,
    );
    $resolver = lupo_get_url_resolver();
    if (!$resolver || !method_exists($resolver, 'getCandidateCanonicalPaths')) {
        return $out;
    }
    $prefix = strlen($slug) >= 3 ? substr($slug, 0, 3) : (strlen($slug) > 0 ? $slug : null);
    $candidates = $resolver->getCandidateCanonicalPaths(100, $prefix, $is_authenticated);
    if (count($candidates) < 5) {
        $candidates = $resolver->getCandidateCanonicalPaths(100, null, $is_authenticated);
    }
    if (!$is_authenticated || empty($candidates)) {
        return $out;
    }
    $request_slug_compare = strlen($slug) > 255 ? substr($slug, 0, 255) : $slug;
    $scored = array();
    foreach ($candidates as $canonical) {
        $c_parts = explode('/', $canonical);
        $c_slug = end($c_parts);
        if ($c_slug === '') {
            $c_slug = $canonical;
        }
        $c_slug_compare = strlen($c_slug) > 255 ? substr($c_slug, 0, 255) : $c_slug;
        $dist = function_exists('levenshtein') ? levenshtein($request_slug_compare, $c_slug_compare) : 999;
        $scored[] = array('path' => $canonical, 'distance' => $dist);
    }
    usort($scored, function ($a, $b) {
        return $a['distance'] - $b['distance'];
    });
    $top = array_slice($scored, 0, 5);
    foreach ($top as $s) {
        $out['suggestions'][] = $s['path'];
    }
    return $out;
}
