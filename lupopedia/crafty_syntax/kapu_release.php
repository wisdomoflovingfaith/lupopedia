<?php
/**
 * Kapu Protocol - Release Handler
 *
 * Handles POST request to release kapu (return to normal capacity)
 */

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/includes/kapu.php';

// Must be POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Method not allowed');
}

// Get current operator
$operator = lupo_crafty_operator();
if (!$operator) {
    header('HTTP/1.1 403 Forbidden');
    exit('Operator not found');
}

$operator_id = $operator['operator_id'];

// Release kapu protocol
$result = lupo_crafty_release_kapu($operator_id);

// Redirect back to overview with message
$redirect_url = lupo_crafty_base_url();
$message = urlencode($result['message']);
$status = $result['success'] ? 'success' : 'error';

header("Location: {$redirect_url}?kapu_status={$status}&message={$message}");
exit;
