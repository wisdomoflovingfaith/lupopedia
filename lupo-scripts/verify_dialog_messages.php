#!/usr/bin/env php
<?php
/**
 * CLI script to verify dialog messages
 * 
 * Usage: php scripts/verify_dialog_messages.php
 */

require_once dirname(__DIR__) . '/lupo-includes/bootstrap-cli.php';

require_once LUPOPEDIA_PATH . '/lupo-includes/functions/dialog-helpers.php';

$report = lupo_verify_dialog_counts();

echo "DIALOG MESSAGE VERIFICATION REPORT\n";
echo "==================================\n";
echo "Total messages: " . $report['total_messages'] . "\n";
echo "Total threads: " . $report['total_threads'] . "\n\n";

echo "Channel counts:\n";
foreach ($report['channels'] as $ch => $data) {
    $match = $data['match'] ? '✓' : '✗';
    echo "  Channel {$ch}: actual={$data['actual_count']}, stored={$data['stored_count']} {$match}\n";
}

echo "\nMessages with X-Lupo-Forwarded-For:\n";
if (empty($report['messages_with_forwarded_for'])) {
    echo "  None found\n";
} else {
    foreach ($report['messages_with_forwarded_for'] as $msg) {
        echo "  ID {$msg['id']}: forwarded_for={$msg['forwarded_for']} (from actor {$msg['from_actor']}, channel {$msg['channel']})\n";
    }
}

// Get all messages from 420
$messages_420 = lupo_get_messages_by_origin(420);
echo "\nMessages originating from 420: " . count($messages_420) . "\n";

exit(0);
