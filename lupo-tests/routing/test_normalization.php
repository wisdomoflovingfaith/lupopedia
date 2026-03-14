<?php
/**
 * T8 Unit tests: UrlResolver normalization (normalizePath, normalizeSlug, slug_encoding).
 * Run from repo root: php tests/routing/test_normalization.php
 * PHP 5.3-compatible; no frameworks. Exit 0 = PASS, non-zero = FAIL.
 */
$repo_root = dirname(dirname(__DIR__));
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}
require_once $repo_root . '/lupo-includes/classes/UrlResolver.php';

$fail = 0;

// normalizePath
if (UrlResolver::normalizePath('/doctrine/FLIP/FOO') !== 'doctrine/FLIP/FOO') { echo "FAIL normalizePath leading slash\n"; $fail++; }
if (UrlResolver::normalizePath('doctrine/FLIP/FOO/') !== 'doctrine/FLIP/FOO') { echo "FAIL normalizePath trailing slash\n"; $fail++; }
if (UrlResolver::normalizePath('  doctrine/FLIP  ') !== 'doctrine/FLIP') { echo "FAIL normalizePath trim\n"; $fail++; }
if (UrlResolver::normalizePath('') !== '') { echo "FAIL normalizePath empty\n"; $fail++; }

// normalizeSlug underscore (default)
if (UrlResolver::normalizeSlug('FLIP_DOCTRINE', 'underscore') !== 'FLIP_DOCTRINE') { echo "FAIL normalizeSlug underscore identity\n"; $fail++; }
if (UrlResolver::normalizeSlug('FLIP+DOCTRINE', 'underscore') !== 'FLIP_DOCTRINE') { echo "FAIL normalizeSlug plus to underscore\n"; $fail++; }
if (UrlResolver::normalizeSlug('FLIP%20DOCTRINE', 'underscore') !== 'FLIP_DOCTRINE') { echo "FAIL normalizeSlug percent to underscore\n"; $fail++; }

// normalizeSlug plus
if (UrlResolver::normalizeSlug('FLIP_DOCTRINE', 'plus') !== 'FLIP+DOCTRINE') { echo "FAIL normalizeSlug underscore to plus\n"; $fail++; }

// normalizeSlug percent
if (UrlResolver::normalizeSlug('FLIP_DOCTRINE', 'percent') !== 'FLIP%20DOCTRINE') { echo "FAIL normalizeSlug underscore to percent\n"; $fail++; }

if ($fail > 0) {
    echo "FAIL (${fail} assertions)\n";
    exit(1);
}
echo "PASS\n";
exit(0);
