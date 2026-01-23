<?php
/**
 * LIMITS Violation Logger
 * 
 * Logs LIMITS violations to storage/logs/lupopedia_limits.log
 * Used in dry-run enforcement mode (4.0.103).
 * 
 * @package Lupopedia
 * @version 4.0.106
 * @author CASCADE
 */

/**
 * Log LIMITS violation to file
 * 
 * @param string $violationType Type of violation (e.g., "version_freeze", "table_count", "weekend_mode")
 * @param string $message Violation message
 * @param array $context Additional context data
 */
function log_limits_violation($violationType, $message, $context = []) {
    $logDir = __DIR__ . '/../../storage/logs';
    $logFile = $logDir . '/lupopedia_limits.log';
    
    // Ensure log directory exists
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    
    $timestamp = gmdate('Y-m-d H:i:s');
    $contextJson = !empty($context) ? ' | Context: ' . json_encode($context) : '';
    $logEntry = "[{$timestamp}] LIMITS_VIOLATION [{$violationType}] {$message}{$contextJson}\n";
    
    @file_put_contents($logFile, $logEntry, FILE_APPEND);
}

/**
 * Safe version bump check (dry-run mode)
 * 
 * @param string $currentVersion Current version
 * @param string $newVersion Proposed new version
 * @return bool True if check passed (or dry-run mode), false if violation logged
 */
function safe_check_version_bump($currentVersion, $newVersion) {
    try {
        // Load LimitsEnforcementService if available
        $limitsServicePath = __DIR__ . '/../../app/Services/System/LimitsEnforcementService.php';
        if (!file_exists($limitsServicePath)) {
            return true; // Service not available, skip check
        }
        
        require_once $limitsServicePath;
        
        // Get database connection (if available)
        global $db;
        if (!isset($db)) {
            return true; // No DB connection, skip check
        }
        
        $limitsService = new \App\Services\System\LimitsEnforcementService($db);
        $limitsService->checkVersionBump($currentVersion, $newVersion);
        return true;
        
    } catch (Exception $e) {
        // Dry-run mode: log violation but don't block
        log_limits_violation('version_freeze', $e->getMessage(), [
            'current_version' => $currentVersion,
            'new_version' => $newVersion
        ]);
        return false; // Violation logged, but execution continues
    }
}

/**
 * Safe table count check (dry-run mode)
 * 
 * @param int $proposedNewTables Number of new tables
 * @return bool True if check passed (or dry-run mode), false if violation logged
 */
function safe_check_table_count($proposedNewTables = 0) {
    try {
        $limitsServicePath = __DIR__ . '/../../app/Services/System/LimitsEnforcementService.php';
        if (!file_exists($limitsServicePath)) {
            return true;
        }
        
        require_once $limitsServicePath;
        
        global $db;
        if (!isset($db)) {
            return true;
        }
        
        $limitsService = new \App\Services\System\LimitsEnforcementService($db);
        $result = $limitsService->checkTableCount($proposedNewTables);
        
        if (is_array($result) && isset($result['allowed']) && !$result['allowed']) {
            log_limits_violation('table_count', $result['message'] ?? 'Table count exceeds ceiling', $result);
            return false;
        }
        
        return true;
        
    } catch (Exception $e) {
        log_limits_violation('table_count', $e->getMessage(), ['proposed_tables' => $proposedNewTables]);
        return false;
    }
}

/**
 * Safe weekend mode check (dry-run mode)
 * 
 * @return bool True if check passed (or dry-run mode), false if violation logged
 */
function safe_check_weekend_mode() {
    try {
        $limitsServicePath = __DIR__ . '/../../app/Services/System/LimitsEnforcementService.php';
        if (!file_exists($limitsServicePath)) {
            return true;
        }
        
        require_once $limitsServicePath;
        
        global $db;
        if (!isset($db)) {
            return true;
        }
        
        $limitsService = new \App\Services\System\LimitsEnforcementService($db);
        $isWeekend = $limitsService->isWeekendDay();
        
        if ($isWeekend) {
            log_limits_violation('weekend_mode', 'Operation attempted during weekend freeze (UTC Days 0, 5, 6)', [
                'current_utc_day' => $limitsService->getCurrentUTCDay()
            ]);
            return false;
        }
        
        return true;
        
    } catch (Exception $e) {
        log_limits_violation('weekend_mode', $e->getMessage());
        return false;
    }
}
