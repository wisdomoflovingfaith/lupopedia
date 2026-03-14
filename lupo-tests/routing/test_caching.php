<?php
/**
 * T8 Unit tests: Caching layer (hit/miss, auth vs anon key). No DB; CSV/file cache only.
 * Run from repo root: php tests/routing/test_caching.php
 * PHP 5.3-compatible; no frameworks. Exit 0 = PASS, non-zero = FAIL.
 */
$repo_root = dirname(dirname(__DIR__));
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $repo_root);
}
require_once $repo_root . '/lupo-includes/classes/UrlResolver.php';

$fail = 0;
$resolver = new UrlResolver(null, 'lupo_', $repo_root, true, false);

// Invalidate first so we start clean
$resolver->invalidateAllCaches();

// Resolve same path twice: second call should be cache hit (same result, no CSV log in second call)
$path = 'doctrine/FLIP/FLIP_DOCTRINE';
$out1 = $resolver->resolve($path, false);
$out2 = $resolver->resolve($path, false);
if ($out1 === null && $out2 === null) {
    // Both null is OK (e.g. no CSV)
} elseif ($out1 !== null && $out2 !== null) {
    $c1 = isset($out1['content_id']) ? $out1['content_id'] : 0;
    $c2 = isset($out2['content_id']) ? $out2['content_id'] : 0;
    if ($c1 !== $c2 || (isset($out1['canonical']) && isset($out2['canonical']) && $out1['canonical'] !== $out2['canonical'])) {
        echo "FAIL cache: second resolve should match first\n";
        $fail++;
    }
}

// Auth vs anon: different cache keys so both get correct result; resolve as anon then as auth
$out_anon = $resolver->resolve($path, false);
$out_auth = $resolver->resolve($path, true);
if ($out_anon !== null && $out_auth !== null) {
    if (isset($out_anon['content_id']) && isset($out_auth['content_id']) && $out_anon['content_id'] !== $out_auth['content_id']) {
        echo "FAIL auth vs anon: same path should yield same content_id\n";
        $fail++;
    }
}

$resolver->invalidateAllCaches();

if ($fail > 0) {
    echo "FAIL (${fail} assertions)\n";
    exit(1);
}
echo "PASS\n";
exit(0);
