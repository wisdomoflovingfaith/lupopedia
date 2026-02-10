<?php

namespace App\Support;

/**
 * Log LIMITS violations to storage/logs/lupopedia_limits.log
 */
class LimitsLogger
{
    /**
     * Log a limits violation.
     *
     * @param string $violationType e.g. "version_freeze", "table_count", "weekend_mode"
     * @param string $message
     * @param array $context
     */
    public static function logViolation(string $violationType, string $message, array $context = []): void
    {
        $logDir = defined('LUPOPEDIA_PATH') ? (LUPOPEDIA_PATH . '/storage/logs') : (__DIR__ . '/../../storage/logs');
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        $logFile = $logDir . '/lupopedia_limits.log';
        $timestamp = gmdate('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' | Context: ' . json_encode($context) : '';
        @file_put_contents($logFile, "[{$timestamp}] LIMITS_VIOLATION [{$violationType}] {$message}{$contextStr}\n", FILE_APPEND);
    }
}
