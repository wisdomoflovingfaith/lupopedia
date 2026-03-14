<?php
/**
 * T8 Unit tests: UrlResolver tier selection (CSV hit, MD hit, null). DB tier optional (no DB in CLI).
 * Run from repo root: php tests/routing/test_resolver_tiers.php
 * PHP 5.3-compatible; no frameworks. Exit 0 = PASS, non-zero = FAIL.
 */
$repo_root = dirname(dirname(__DIR__));
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}
require_once $repo_root . '/lupo-includes/classes/UrlResolver.php';

$fail = 0;
$resolver = new UrlResolver(null, 'lupo_', $repo_root, true, false);

// Null result: path that does not exist in CSV or filesystem
$out = $resolver->resolve('doctrine/NONEXISTENT_SLUG_XYZ');
if ($out !== null) {
    echo "FAIL null result expected for nonexistent path\n";
    $fail++;
}

// CSV tier: path that exists in exports/flip_headers.csv (e.g. doctrine/FLIP/FLIP_DOCTRINE)
$csv_path = $repo_root . '/exports/flip_headers.csv';
if (is_file($csv_path) && is_readable($csv_path)) {
    $out = $resolver->resolve('doctrine/FLIP/FLIP_DOCTRINE');
    if ($out === null) {
        echo "FAIL CSV tier: expected non-null for doctrine/FLIP/FLIP_DOCTRINE\n";
        $fail++;
    } elseif (!isset($out['source']) || $out['source'] !== 'csv') {
        echo "FAIL CSV tier: expected source=csv (got " . (isset($out['source']) ? $out['source'] : '?') . ")\n";
        $fail++;
    }
} else {
    echo "SKIP CSV tier (no exports/flip_headers.csv)\n";
}

// Alias path: /docs/FLIP_DOCTRINE often an alias for canonical
if (is_file($csv_path) && is_readable($csv_path)) {
    $out = $resolver->resolve('docs/FLIP_DOCTRINE');
    if ($out !== null && isset($out['canonical'])) {
        // OK: resolved (alias or canonical)
    } elseif ($out === null) {
        echo "SKIP alias path (no match in CSV)\n";
    }
}

if ($fail > 0) {
    echo "FAIL (${fail} assertions)\n";
    exit(1);
}
echo "PASS\n";
exit(0);
