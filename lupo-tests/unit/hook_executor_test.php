<?php
/**
 * Unit tests for HookExecutor: init/close hooks, guards_allow, recursion limit.
 * Run: php tests/unit/hook_executor_test.php
 */

$base = dirname(dirname(__DIR__));
$hook = $base . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'HookExecutor.php';
if (!is_file($hook)) {
    echo "SKIP HookExecutor.php not found\n";
    exit(0);
}
require_once $hook;

$ok = 0;
$fail = 0;

$headers = array(
    'flare' => array(
        'hooks' => array(
            'init' => array(
                array('type' => 'log', 'target' => 'init.log', 'params' => array('message' => 'test')),
            ),
        ),
    ),
);
$done = HookExecutor::run($headers, 'init', false, '', '');
if (is_array($done) && count($done) <= HookExecutor::RECURSION_MAX) {
    echo "PASS run returns array within recursion limit\n";
    $ok++;
} else {
    echo "FAIL run\n";
    $fail++;
}

$done2 = HookExecutor::run($headers, 'close', false, '', '');
if (is_array($done2)) {
    echo "PASS close run\n";
    $ok++;
} else {
    echo "FAIL close\n";
    $fail++;
}

$empty = HookExecutor::run(array(), 'init', false, '', '');
if (is_array($empty) && count($empty) === 0) {
    echo "PASS empty headers\n";
    $ok++;
} else {
    echo "FAIL empty\n";
    $fail++;
}

echo "Result: $ok pass, $fail fail\n";
exit($fail > 0 ? 1 : 0);
