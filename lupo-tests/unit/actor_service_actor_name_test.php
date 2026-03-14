<?php
/**
 * Unit tests for ActorService actor_name primary key (getActorByName, resolveActor, getActorName, validateDelegationChain).
 * Run from project root: php tests/unit/actor_service_actor_name_test.php
 * Uses registry only (no DB required).
 */

$base = dirname(dirname(__DIR__));
if (!defined('LUPOPEDIA_ABSPATH')) {
    define('LUPOPEDIA_ABSPATH', $base . DIRECTORY_SEPARATOR);
}
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $base);
}

$actorServicePath = $base . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'lupo-app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'ActorService.php';
if (!is_file($actorServicePath)) {
    echo "SKIP ActorService.php not found\n";
    exit(0);
}

require_once $actorServicePath;

$db = null;
$service = new \App\Services\ActorService($db);

$ok = 0;
$fail = 0;

// getActorByName('cursor')
$actor = $service->getActorByName('cursor');
if ($actor !== null && isset($actor['actor_name']) && $actor['actor_name'] === 'cursor' && isset($actor['actor_id']) && (int) $actor['actor_id'] === 1003) {
    echo "PASS getActorByName('cursor')\n";
    $ok++;
} else {
    echo "FAIL getActorByName('cursor')\n";
    $fail++;
}

// getActorByName('lilith') -> 2038
$actor = $service->getActorByName('lilith');
if ($actor !== null && isset($actor['actor_id']) && (int) $actor['actor_id'] === 2038) {
    echo "PASS getActorByName('lilith') actor_id 2038\n";
    $ok++;
} else {
    echo "FAIL getActorByName('lilith')\n";
    $fail++;
}

// getActorById(42) -> antigravity
$actor = $service->getActorById(42);
if ($actor !== null && isset($actor['actor_name']) && $actor['actor_name'] === 'antigravity') {
    echo "PASS getActorById(42) actor_name antigravity\n";
    $ok++;
} else {
    echo "FAIL getActorById(42)\n";
    $fail++;
}

// resolveActor('captain')
$actor = $service->resolveActor('captain');
if ($actor !== null && isset($actor['actor_name']) && $actor['actor_name'] === 'captain') {
    echo "PASS resolveActor('captain')\n";
    $ok++;
} else {
    echo "FAIL resolveActor('captain')\n";
    $fail++;
}

// resolveActor(10000)
$actor = $service->resolveActor(10000);
if ($actor !== null && isset($actor['actor_name']) && $actor['actor_name'] === 'captain') {
    echo "PASS resolveActor(10000)\n";
    $ok++;
} else {
    echo "FAIL resolveActor(10000)\n";
    $fail++;
}

// getActorName('antigravity')
$name = $service->getActorName('antigravity');
if ($name === 'antigravity') {
    echo "PASS getActorName('antigravity')\n";
    $ok++;
} else {
    echo "FAIL getActorName('antigravity')\n";
    $fail++;
}

// getActorDir('lilith') -> lupo-actors/lilith (name-based)
$dir = $service->getActorDir('lilith');
if ($dir !== null && $dir !== '' && strpos($dir, 'lilith') !== false) {
    echo "PASS getActorDir('lilith')\n";
    $ok++;
} else {
    echo "FAIL getActorDir('lilith') got: " . (string) $dir . "\n";
    $fail++;
}

// getActorDir('antigravity') -> lupo-actors/antigravity
$dir = $service->getActorDir('antigravity');
if ($dir !== null && $dir !== '' && strpos($dir, 'antigravity') !== false) {
    echo "PASS getActorDir('antigravity')\n";
    $ok++;
} else {
    echo "FAIL getActorDir('antigravity') got: " . (string) $dir . "\n";
    $fail++;
}

// validateDelegationChain
if ($service->validateDelegationChain('lilith:cursor:captain') === true) {
    echo "PASS validateDelegationChain('lilith:cursor:captain')\n";
    $ok++;
} else {
    echo "FAIL validateDelegationChain\n";
    $fail++;
}
if ($service->validateDelegationChain('unknown:captain') === false) {
    echo "PASS validateDelegationChain invalid\n";
    $ok++;
} else {
    echo "FAIL validateDelegationChain invalid\n";
    $fail++;
}

echo "\nTotal: $ok pass, $fail fail\n";
exit($fail > 0 ? 1 : 0);
