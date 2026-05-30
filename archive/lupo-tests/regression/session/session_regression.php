<?php
/**
 * Regression: Session — session-compat, Session class path, no fatal when loaded.
 * Run from repo root: php tests/regression/session/session_regression.php
 * Does not start session or test expiry; verifies files and minimal load. PHP 5.3-compatible.
 */
$repo_root = dirname(dirname(dirname(__DIR__)));
$fail = 0;

$session_compat = $repo_root . '/lupo-includes/functions/session-compat-5.3.php';
if (!is_file($session_compat)) {
    echo "FAIL missing: $session_compat\n";
    $fail++;
} else {
    $out = array();
    exec('php -l ' . escapeshellarg($session_compat) . ' 2>&1', $out, $ret);
    if ($ret !== 0) {
        echo "FAIL syntax session-compat-5.3.php\n";
        $fail++;
    }
}

$session_class = $repo_root . '/app/auth/Session.php';
if (!is_file($session_class)) {
    echo "FAIL missing: $session_class\n";
    $fail++;
} else {
    $out = array();
    exec('php -l ' . escapeshellarg($session_class) . ' 2>&1', $out, $ret);
    if ($ret !== 0) {
        echo "FAIL syntax Session.php\n";
        $fail++;
    }
}

// session-helpers.php is deprecated but may exist
$session_helpers = $repo_root . '/lupo-includes/functions/session-helpers.php';
if (is_file($session_helpers)) {
    exec('php -l ' . escapeshellarg($session_helpers) . ' 2>&1', $out, $ret);
    if ($ret !== 0) {
        echo "FAIL syntax session-helpers.php\n";
        $fail++;
    }
}

if ($fail > 0) {
    echo "REGRESSION SESSION: $fail fail\n";
    exit(1);
}
echo "PASS session regression (session files exist and valid syntax)\n";
exit(0);
