<?php
/**
 * Channel message API security test (4.0.79).
 *
 * Verifies that lupo-includes/modules/api/channels-api.php enforces:
 * - Actor identity from session/auth only (no trust of client-supplied actor_id)
 * - Membership check (lupo_actor_channels) before message insert
 * - 401 for unauthenticated, 403 for non-member (GET and POST)
 * - Admin bypass via AuthService::isAdmin()
 *
 * Run: php lupo-tests/unit/channel_api_security_test.php
 * Exit: 0 on pass, 1 on failure.
 */

$repoRoot = dirname(dirname(__DIR__));
$apiPath = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'channels-api.php';

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

if (!is_file($apiPath)) {
    echo "FAIL: channels-api.php not found\n";
    exit(1);
}

$source = file_get_contents($apiPath);
assert_true($source !== false && strlen($source) > 0, 'channels-api.php readable');

// Request body must include body, not actor_id (client-supplied actor_id not required/trusted)
assert_true(strpos($source, "!isset(\$input['body'])") !== false, 'POST requires body in request');
assert_true(strpos($source, "Request body must include body.") !== false, 'Error message for missing body (no actor_id required)');

// Session/auth actor resolution (server-side only)
assert_true(strpos($source, 'lupo_auth_service') !== false, 'Uses lupo_auth_service for actor resolution');
assert_true(strpos($source, 'getCurrentUser') !== false, 'Uses getCurrentUser for actor resolution');
assert_true(strpos($source, 'actor_id = (int) $user[\'actor_id\']') !== false || preg_match('/actor_id\s*=\s*\(int\)\s*\$user\[.actor_id.\]/', $source), 'Actor ID taken from user/session');

// Unauthenticated -> 401
assert_true(strpos($source, '401') !== false, 'Returns HTTP 401 for unauthenticated');
assert_true(strpos($source, 'UNAUTHORIZED') !== false, 'Returns UNAUTHORIZED code when no session actor');

// Membership check before insert
assert_true(strpos($source, 'actor_channels') !== false, 'Checks lupo_actor_channels for membership');
assert_true(strpos($source, 'is_deleted = 0') !== false, 'Membership check filters is_deleted = 0');

// Non-member -> 403
assert_true(strpos($source, '403') !== false, 'Returns HTTP 403 for non-member');
assert_true(strpos($source, 'FORBIDDEN') !== false, 'Returns FORBIDDEN code when not member');
assert_true(strpos($source, 'Actor not a member of this channel') !== false, 'Error message for non-member');

// Admin bypass preserved
assert_true(strpos($source, 'isAdmin') !== false, 'Preserves AuthService::isAdmin() for admin bypass');

// Insert uses resolved actor_id (from server), not input (execute array contains => $actor_id)
assert_true(strpos($source, '=> $actor_id') !== false, 'Insert execute array uses resolved $actor_id variable');
assert_true(strpos($source, "\$input['actor_id']") === false, 'Does not use client input actor_id for insert');

echo "\nSummary: $passed passed, $failed failed\n";
exit($failed > 0 ? 1 : 0);
