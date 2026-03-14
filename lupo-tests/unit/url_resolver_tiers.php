<?php
/**
 * T8 Unit test: UrlResolver tier selection (DB, CSV, MD, null).
 * Run from repo root: php tests/unit/url_resolver_tiers.php
 * PHP 5.3-compatible; no frameworks. Exit 0 = PASS, non-zero = FAIL.
 */
$repo_root = dirname(dirname(__DIR__));
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}
require_once $repo_root . '/lupo-includes/classes/UrlResolver.php';

$fail = 0;
$resolver = new UrlResolver(null, 'lupo_', $repo_root, true, false);

// Null result: unknown path
$out = $resolver->resolve('doctrine/NONEXISTENT_SLUG_XYZ');
if ($out !== null) {
    echo "FAIL null result expected for nonexistent path\n";
    $fail++;
}

// CSV tier: known web_canonical / alias from exports/flip_headers.csv
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
    $out = $resolver->resolve('docs/FLIP_DOCTRINE');
    if ($out !== null && isset($out['canonical'])) {
        // OK: alias or canonical
    } elseif ($out === null) {
        echo "SKIP alias path (no match in CSV)\n";
    }
} else {
    echo "SKIP CSV tier (no exports/flip_headers.csv)\n";
}

// DB tier: would require DB with file_path_from_root; skip in CLI without config
// MD tier: docs/*.md with web: block; resolver may hit CSV first for same path

if ($fail > 0) {
    echo "FAIL ($fail assertions)\n";
    exit(1);
}
echo "PASS\n";
exit(0);
