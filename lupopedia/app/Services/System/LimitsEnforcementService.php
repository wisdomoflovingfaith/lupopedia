<?php
/**
 * Limits Enforcement Service
 * 
 * Enforces LUP0_LIMITS_DOCTRINE_v1.0 rules:
 * - Database table count ceiling (185 tables)
 * - Weekend version freeze (Days 0, 5, 6 UTC)
 * - Branch limits (max 2 weekend branches)
 * - Weekend behavioral limits
 * 
 * @package App\Services\System
 * @version 4.1.12
 * @author Captain Wolfie
 * @see LIMITS.md
 */

namespace App\Services\System;

use PDO;
use Exception;

class LimitsEnforcementService
{
    protected $db;
    protected const MAX_TABLES = 185;
    protected const CURRENT_BASELINE = 181;
    protected const WEEKEND_DAYS = [0, 5, 6]; // Sunday, Friday, Saturday (UTC)
    protected const MAX_WEEKEND_BRANCHES = 2;

    /**
     * Constructor
     * 
     * @param PDO|PDO_DB $db Database connection instance
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Check if current UTC day is a weekend day (0, 5, 6)
     * 
     * @return bool True if current day is weekend (Sunday, Friday, Saturday)
     */
    public function isWeekendDay(): bool
    {
        try {
            // Use TerminalAI_005 (UTC_TIMEKEEPER) to get current UTC time
            $utcAgent = new \App\TerminalAI\Agents\TerminalAI_005();
            $utcResponse = $utcAgent->handle('what_is_current_utc_time_yyyymmddhhiiss');
            
            // Parse response: "current_utc_time_yyyymmddhhiiss: YYYYMMDDHHIISS"
            if (preg_match('/current_utc_time_yyyymmddhhiiss:\s*(\d{14})/', $utcResponse, $matches)) {
                $utcTimestamp = $matches[1];
                $dayOfWeek = (int)date('w', strtotime(substr($utcTimestamp, 0, 8)));
                return in_array($dayOfWeek, self::WEEKEND_DAYS);
            }
            
            // Fallback: use PHP's UTC time
            $dayOfWeek = (int)gmdate('w');
            return in_array($dayOfWeek, self::WEEKEND_DAYS);
            
        } catch (Exception $e) {
            // On error, default to non-weekend (safer for production)
            error_log("LimitsEnforcementService::isWeekendDay() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if table count would exceed ceiling
     * 
     * @param int $proposedNewTables Number of new tables to be created
     * @return array ['allowed' => bool, 'current_count' => int, 'message' => string]
     */
    public function checkTableCount(int $proposedNewTables = 0): array
    {
        try {
            // Get PDO instance if PDO_DB wrapper is used
            $pdo = ($this->db instanceof \PDO_DB) ? $this->db->getPdo() : $this->db;
            
            // Count current tables
            $sql = "SELECT COUNT(*) as table_count 
                    FROM information_schema.tables 
                    WHERE table_schema = DATABASE() 
                    AND table_type = 'BASE TABLE'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $currentCount = (int)$result['table_count'];
            
            $proposedCount = $currentCount + $proposedNewTables;
            
            if ($proposedCount > self::MAX_TABLES) {
                return [
                    'allowed' => false,
                    'current_count' => $currentCount,
                    'proposed_count' => $proposedCount,
                    'max_allowed' => self::MAX_TABLES,
                    'message' => "Table count would exceed ceiling: {$currentCount} + {$proposedNewTables} = {$proposedCount} > " . self::MAX_TABLES
                ];
            }
            
            return [
                'allowed' => true,
                'current_count' => $currentCount,
                'proposed_count' => $proposedCount,
                'max_allowed' => self::MAX_TABLES,
                'message' => "Table count check passed: {$currentCount} + {$proposedNewTables} = {$proposedCount} <= " . self::MAX_TABLES
            ];
            
        } catch (Exception $e) {
            error_log("LimitsEnforcementService::checkTableCount() error: " . $e->getMessage());
            // On error, reject to be safe
            return [
                'allowed' => false,
                'current_count' => 0,
                'proposed_count' => 0,
                'max_allowed' => self::MAX_TABLES,
                'message' => "Error checking table count: " . $e->getMessage()
            ];
        }
    }

    /**
     * Check if version bump is allowed (weekend freeze enforcement)
     * 
     * @param string $currentVersion Current version (e.g., "4.0.101")
     * @param string $proposedVersion Proposed version (e.g., "4.0.102" or "4.1.0")
     * @return array ['allowed' => bool, 'reason' => string]
     */
    public function checkVersionBump(string $currentVersion, string $proposedVersion): array
    {
        if ($this->isWeekendDay()) {
            // Parse versions
            $currentParts = explode('.', $currentVersion);
            $proposedParts = explode('.', $proposedVersion);
            
            $currentMajor = (int)($currentParts[0] ?? 0);
            $currentMinor = (int)($currentParts[1] ?? 0);
            $proposedMajor = (int)($proposedParts[0] ?? 0);
            $proposedMinor = (int)($proposedParts[1] ?? 0);
            
            // Check for minor version lock (4.0.x → 4.1.x forbidden on weekend)
            if ($currentMajor === $proposedMajor && $currentMinor === 0 && $proposedMinor > 0) {
                return [
                    'allowed' => false,
                    'reason' => 'WEEKEND VERSION FREEZE: Minor version lock prevents 4.0.x → 4.1.x on weekend days (Days 0, 5, 6 UTC)'
                ];
            }
            
            // Patch bumps allowed on weekend (non-binding notes only)
            if ($currentMajor === $proposedMajor && $currentMinor === $proposedMinor) {
                return [
                    'allowed' => true,
                    'reason' => 'Patch-level bump allowed on weekend (non-binding notes only)'
                ];
            }
            
            // Any other version change forbidden on weekend
            return [
                'allowed' => false,
                'reason' => 'WEEKEND VERSION FREEZE: No version bumps beyond current minor version on weekend days (Days 0, 5, 6 UTC)'
            ];
        }
        
        // Non-weekend: version bumps allowed
        return [
            'allowed' => true,
            'reason' => 'Version bump allowed (not a weekend day)'
        ];
    }

    /**
     * Check if branch creation is allowed (weekend branch limits)
     * 
     * @param string $branchName Proposed branch name
     * @return array ['allowed' => bool, 'reason' => string]
     */
    public function checkBranchCreation(string $branchName): array
    {
        if (!$this->isWeekendDay()) {
            // Non-weekend: branch creation allowed
            return [
                'allowed' => true,
                'reason' => 'Branch creation allowed (not a weekend day)'
            ];
        }
        
        // Weekend: check branch naming and count
        $isWeekendBranch = preg_match('/^weekend_experiment_[12]$/', $branchName);
        
        if (!$isWeekendBranch) {
            return [
                'allowed' => false,
                'reason' => 'WEEKEND BRANCH LIMIT: Weekend branches must be named weekend_experiment_1 or weekend_experiment_2'
            ];
        }
        
        // Note: Actual branch count checking would require Git integration
        // For now, we only enforce naming convention
        // TODO: Integrate with Git to check actual branch count
        
        return [
            'allowed' => true,
            'reason' => 'Weekend branch name valid (actual count check requires Git integration)'
        ];
    }

    /**
     * Check if structural changes are allowed (weekend behavioral limits)
     * 
     * @param string $changeType Type of change: 'schema', 'doctrine', 'architecture', 'version', 'table', 'agent', 'pack'
     * @return array ['allowed' => bool, 'reason' => string]
     */
    public function checkStructuralChange(string $changeType): array
    {
        if (!$this->isWeekendDay()) {
            return [
                'allowed' => true,
                'reason' => 'Structural changes allowed (not a weekend day)'
            ];
        }
        
        $forbiddenTypes = ['schema', 'doctrine', 'architecture', 'version', 'table', 'agent', 'pack'];
        
        if (in_array(strtolower($changeType), $forbiddenTypes)) {
            return [
                'allowed' => false,
                'reason' => "WEEKEND BEHAVIORAL LIMIT: Structural changes ({$changeType}) forbidden on weekend days (Days 0, 5, 6 UTC). Weekend mode is for creativity, humor, lore, and non-binding prototypes only."
            ];
        }
        
        return [
            'allowed' => true,
            'reason' => 'Change type allowed on weekend'
        ];
    }

    /**
     * Validate all limits for a migration operation
     * 
     * @param int $proposedNewTables Number of new tables
     * @return array ['allowed' => bool, 'violations' => array, 'message' => string]
     */
    public function validateMigration(int $proposedNewTables = 0): array
    {
        $violations = [];
        
        // Check table count
        $tableCheck = $this->checkTableCount($proposedNewTables);
        if (!$tableCheck['allowed']) {
            $violations[] = $tableCheck['message'];
        }
        
        // Check weekend structural changes
        $structuralCheck = $this->checkStructuralChange('table');
        if (!$structuralCheck['allowed']) {
            $violations[] = $structuralCheck['reason'];
        }
        
        $allowed = empty($violations);
        
        return [
            'allowed' => $allowed,
            'violations' => $violations,
            'message' => $allowed 
                ? 'Migration validation passed' 
                : 'Migration validation failed: ' . implode('; ', $violations),
            'table_check' => $tableCheck,
            'structural_check' => $structuralCheck
        ];
    }

    /**
     * Get current UTC day of week (0=Sunday, 6=Saturday)
     * 
     * @return int Day of week (0-6)
     */
    public function getCurrentUTCDay(): int
    {
        try {
            $utcAgent = new \App\TerminalAI\Agents\TerminalAI_005();
            $utcResponse = $utcAgent->handle('what_is_current_utc_time_yyyymmddhhiiss');
            
            if (preg_match('/current_utc_time_yyyymmddhhiiss:\s*(\d{14})/', $utcResponse, $matches)) {
                $utcTimestamp = $matches[1];
                return (int)date('w', strtotime(substr($utcTimestamp, 0, 8)));
            }
            
            return (int)gmdate('w');
            
        } catch (Exception $e) {
            error_log("LimitsEnforcementService::getCurrentUTCDay() error: " . $e->getMessage());
            return (int)gmdate('w');
        }
    }
}
