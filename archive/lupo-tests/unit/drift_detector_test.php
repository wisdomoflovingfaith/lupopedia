<?php
/**
 * Unit tests for DriftDetector: conflict detection, fs_wins.
 * Run: php tests/unit/drift_detector_test.php
 */

$base = dirname(dirname(__DIR__));
$drift = $base . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'DriftDetector.php';
if (!is_file($drift)) {
    echo "SKIP DriftDetector.php not found\n";
    exit(0);
}
require_once $drift;

$ok = 0;
$fail = 0;

$headers_fs_new = array('flare' => array('headers' => array('last_modified_utc' => '20260306120000')));
$db_old = array('last_modified_ymdhis' => '20260305120000');
$r = DriftDetector::detect($headers_fs_new, '', $db_old);
if (isset($r['fs_wins']) && $r['fs_wins'] === true) {
    echo "PASS fs_wins when FS newer\n";
    $ok++;
} else {
    echo "FAIL fs_wins\n";
    $fail++;
}

$headers_fs_old = array('flare' => array('headers' => array('last_modified_utc' => '20260305120000')));
$db_new = array('last_modified_ymdhis' => '20260306120000');
$r2 = DriftDetector::detect($headers_fs_old, '', $db_new);
if (isset($r2['fs_wins']) && $r2['fs_wins'] === false && isset($r2['conflict'])) {
    echo "PASS conflict when DB newer\n";
    $ok++;
} else {
    echo "FAIL conflict detection\n";
    $fail++;
}

echo "Result: $ok pass, $fail fail\n";
exit($fail > 0 ? 1 : 0);
