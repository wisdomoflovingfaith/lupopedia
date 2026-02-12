<?php
/**
 * LIMITS Violation Logger
 * 
 * Logs LIMITS violations to storage/logs/lupopedia_limits.log
 * Used in dry-run enforcement mode (3.0.103).
 * 
 * @package Lupopedia
 * @version 3.0.0
 * @author CASCADE
 */

/**
 * Log LIMITS violation (thin wrapper — App\Support\LimitsLogger).
 *
 * @param string $violationType e.g. "version_freeze", "table_count", "weekend_mode"
 * @param string $message Violation message
 * @param array $context Additional context
 */
function log_limits_violation($violationType, $message, $context = []) {
    if (class_exists('App\Support\LimitsLogger')) {
        \App\Support\LimitsLogger::logViolation((string) $violationType, (string) $message, is_array($context) ? $context : []);
        return;
    }
    $logDir = __DIR__ . '/../../storage/logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    $logEntry = '[' . gmdate('Y-m-d H:i:s') . "] LIMITS_VIOLATION [{$violationType}] {$message}" . (!empty($context) ? ' | Context: ' . json_encode($context) : '') . "\n";
    @file_put_contents($logDir . '/lupopedia_limits.log', $logEntry, FILE_APPEND);
}

/**
 * Safe version bump check (thin wrapper — LimitsEnforcementService + LimitsLogger).
 *
 * @param string $currentVersion Current version
 * @param string $newVersion Proposed new version
 * @return bool True if check passed, false if violation logged
 */
function safe_check_version_bump($currentVersion, $newVersion) {
    try {
        $db = $GLOBALS['mydatabase'] ?? null;
        if (!$db) {
            return true;
        }
        if (!class_exists('App\Services\System\LimitsEnforcementService')) {
            $path = __DIR__ . '/../../app/Services/System/LimitsEnforcementService.php';
            if (file_exists($path)) {
                require_once $path;
            }
        }
        if (!class_exists('App\Services\System\LimitsEnforcementService')) {
            return true;
        }
        $limitsService = new \App\Services\System\LimitsEnforcementService($db);
        $result = $limitsService->checkVersionBump((string) $currentVersion, (string) $newVersion);
        if (is_array($result) && empty($result['allowed'])) {
            log_limits_violation('version_freeze', $result['reason'] ?? 'Version bump not allowed', ['current_version' => $currentVersion, 'new_version' => $newVersion]);
            return false;
        }
        return true;
    } catch (\Exception $e) {
        log_limits_violation('version_freeze', $e->getMessage(), ['current_version' => $currentVersion, 'new_version' => $newVersion]);
        return false;
    }
}

/**
 * Safe table count check (thin wrapper — LimitsEnforcementService).
 *
 * @param int $proposedNewTables Number of new tables
 * @return bool True if check passed, false if violation logged
 */
function safe_check_table_count($proposedNewTables = 0) {
    try {
        $db = $GLOBALS['mydatabase'] ?? null;
        if (!$db) {
            return true;
        }
        if (!class_exists('App\Services\System\LimitsEnforcementService')) {
            $path = __DIR__ . '/../../app/Services/System/LimitsEnforcementService.php';
            if (file_exists($path)) {
                require_once $path;
            }
        }
        if (!class_exists('App\Services\System\LimitsEnforcementService')) {
            return true;
        }
        $limitsService = new \App\Services\System\LimitsEnforcementService($db);
        $result = $limitsService->checkTableCount((int) $proposedNewTables);
        if (is_array($result) && isset($result['allowed']) && !$result['allowed']) {
            log_limits_violation('table_count', $result['message'] ?? 'Table count exceeds ceiling', $result);
            return false;
        }
        return true;
    } catch (\Exception $e) {
        log_limits_violation('table_count', $e->getMessage(), ['proposed_tables' => $proposedNewTables]);
        return false;
    }
}

/**
 * Safe weekend mode check (thin wrapper — LimitsEnforcementService).
 *
 * @return bool True if check passed, false if violation logged
 */
function safe_check_weekend_mode() {
    try {
        $db = $GLOBALS['mydatabase'] ?? null;
        if (!$db) {
            return true;
        }
        if (!class_exists('App\Services\System\LimitsEnforcementService')) {
            $path = __DIR__ . '/../../app/Services/System/LimitsEnforcementService.php';
            if (file_exists($path)) {
                require_once $path;
            }
        }
        if (!class_exists('App\Services\System\LimitsEnforcementService')) {
            return true;
        }
        $limitsService = new \App\Services\System\LimitsEnforcementService($db);
        if ($limitsService->isWeekendDay()) {
            log_limits_violation('weekend_mode', 'Operation attempted during weekend freeze (UTC Days 0, 5, 6)', [
                'current_utc_day' => method_exists($limitsService, 'getCurrentUTCDay') ? $limitsService->getCurrentUTCDay() : null,
            ]);
            return false;
        }
        return true;
    } catch (\Exception $e) {
        log_limits_violation('weekend_mode', $e->getMessage());
        return false;
    }
}
