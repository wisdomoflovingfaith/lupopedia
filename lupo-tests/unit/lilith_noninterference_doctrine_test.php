<?php
/**
 * Lilith non-interference doctrine test (4.0.79).
 *
 * Verifies that lupo-rules/root/lilith-noninterference-doctrine.md exists,
 * contains LIL001 rule and non-interference requirements.
 *
 * Run: php lupo-tests/unit/lilith_noninterference_doctrine_test.php
 * Exit: 0 on pass, 1 on failure.
 */

$repoRoot = dirname(dirname(__DIR__));
$doctrinePath = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-rules' . DIRECTORY_SEPARATOR . 'root' . DIRECTORY_SEPARATOR . 'lilith-noninterference-doctrine.md';

$passed = 0;
$failed = 0;

function assert_true($cond, $msg) {
    global $passed, $failed;
    if ($cond) {
        $passed++;
        echo "PASS: $msg\n";
    } else {
        $failed++;
        echo "FAIL: $msg\n";
    }
}

assert_true(is_file($doctrinePath), 'lilith-noninterference-doctrine.md exists');

if (!is_file($doctrinePath)) {
    echo "\nSummary: 0 passed, 1 failed\n";
    exit(1);
}

$content = file_get_contents($doctrinePath);
assert_true(strpos($content, 'LIL001') !== false, 'Contains rule ID LIL001');
assert_true(strpos($content, 'non-interfering') !== false, 'Describes Lilith as non-interfering');
assert_true(stripos($content, 'must not modify other agents') !== false || stripos($content, 'MUST NOT modify') !== false, 'States must not modify other agents\' work without context');
assert_true(stripos($content, 'must not block') !== false || stripos($content, 'MUST NOT block') !== false, 'States must not block or delay other agents');
assert_true(stripos($content, 'attributable') !== false || stripos($content, 'permissions for other agents') !== false, 'Outputs attributable or permissions unchanged');
assert_true(strpos($content, 'lupopedia.rules:') !== false, 'Has lupopedia.rules block for propagation');

echo "\nSummary: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);
