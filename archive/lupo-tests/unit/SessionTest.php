<?php
/**
 * Unit tests for App\Auth\Session — User Agent normalization (plain PHP, no PHPUnit).
 *
 * Run: php lupo-tests/unit/SessionTest.php
 *
 * @package Lupopedia
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    define('LUPOPEDIA_CONFIG_LOADED', true);
}
if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}

require_once __DIR__ . '/../../app/auth/Session.php';

$passed = 0;
$failed = 0;

/**
 * @param mixed $expected
 * @param mixed $actual
 * @param string $message
 */
function session_test_assert_equals($expected, $actual, $message)
{
    global $passed, $failed;
    if ($expected === $actual) {
        echo '[PASS] ' . $message . "\n";
        $passed++;
    } else {
        echo '[FAIL] ' . $message . ' (expected: ' . var_export($expected, true) . ', got: ' . var_export($actual, true) . ")\n";
        $failed++;
    }
}

/**
 * @param int $expected
 * @param mixed $actual
 * @param string $message
 */
function session_test_assert_length($expected, $actual, $message)
{
    global $passed, $failed;
    $len = strlen((string) $actual);
    if ($len === $expected) {
        echo '[PASS] ' . $message . "\n";
        $passed++;
    } else {
        echo '[FAIL] ' . $message . ' (expected length ' . $expected . ', got ' . $len . ")\n";
        $failed++;
    }
}

echo "=== Session::normalizeUserAgent() unit tests ===\n\n";

$raw = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36';
$expected = 'mozilla50windowsnt100win64x64applewebkit53736';
session_test_assert_equals($expected, \App\Auth\Session::normalizeUserAgent($raw), 'testNormalizeUserAgentFiltersNonAlphanumeric — Mozilla example');

session_test_assert_length(200, \App\Auth\Session::normalizeUserAgent(str_repeat('a', 300)), 'testNormalizeUserAgentTruncatesTo200');

$onlySymbols = '!@#$%^&*()_+{}|:<>?~`-=[]' . "\\" . ';' . "'" . ',./';
session_test_assert_equals('', \App\Auth\Session::normalizeUserAgent($onlySymbols), 'testNormalizeUserAgentRemovesSpecialChars');

session_test_assert_equals('mozillafirefoxchrome', \App\Auth\Session::normalizeUserAgent('Mozilla Firefox Chrome'), 'testNormalizeUserAgentLowercases');

echo "\n";
echo 'Results: ' . $passed . ' passed, ' . $failed . " failed\n";
exit($failed > 0 ? 1 : 0);
