<?php
/**
 * System Health Service
 *
 * Provides diagnostic checks for all system subsystems.
 * Non-blocking, pure diagnostics with no exceptions.
 *
 * @package Lupopedia
 * @version 4.0.106
 * @author Captain Wolfie
 */

namespace App\Services\System;

use PDO;

/**
 * SystemHealthService
 *
 * Performs health checks on all system subsystems.
 */
class SystemHealthService
{
    /** @var PDO|null Database connection */
    private $db;

    /**
     * Constructor
     *
     * @param PDO|null $db Database connection (optional)
     */
    public function __construct($db = null)
    {
        $this->db = $db;
    }

    /**
     * Check database schema health
     *
     * @return array Status array with 'status' and 'message' keys
     */
    public function checkDatabaseSchema(): array
    {
        try {
            if (!$this->db) {
                return [
                    'status' => 'warning',
                    'message' => 'Database connection not available',
                ];
            }

            // Basic schema check - verify core tables exist
            $coreTables = ['lupo_actors', 'lupo_dialog_channels', 'lupo_dialog_doctrine'];
            $missingTables = [];

            foreach ($coreTables as $table) {
                $stmt = $this->db->query("SHOW TABLES LIKE '{$table}'");
                if ($stmt->rowCount() === 0) {
                    $missingTables[] = $table;
                }
            }

            if (!empty($missingTables)) {
                return [
                    'status' => 'error',
                    'message' => 'Missing core tables: ' . implode(', ', $missingTables),
                ];
            }

            return [
                'status' => 'ok',
                'message' => 'Database schema healthy',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Schema check failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check agent registry health
     *
     * @return array Status array with 'status' and 'message' keys
     */
    public function checkAgentRegistry(): array
    {
        try {
            // Check if agent registry table exists
            if (!$this->db) {
                return [
                    'status' => 'warning',
                    'message' => 'Database connection not available',
                ];
            }

            $stmt = $this->db->query("SHOW TABLES LIKE 'lupo_agent_registry'");
            if ($stmt->rowCount() === 0) {
                return [
                    'status' => 'warning',
                    'message' => 'Agent registry table not found',
                ];
            }

            return [
                'status' => 'ok',
                'message' => 'Agent registry healthy',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Agent registry check failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check quantum subsystem health
     *
     * @return array Status array with 'status' and 'message' keys
     */
    public function checkQuantumSubsystem(): array
    {
        try {
            $quantumManagerPath = __DIR__ . '/../../../lupo-includes/Quantum/QuantumStateManager.php';
            if (!file_exists($quantumManagerPath)) {
                return [
                    'status' => 'error',
                    'message' => 'QuantumStateManager not found',
                ];
            }

            $quantumSnapshotPath = __DIR__ . '/../../../lupo-includes/Quantum/QuantumStateSnapshot.php';
            if (!file_exists($quantumSnapshotPath)) {
                return [
                    'status' => 'error',
                    'message' => 'QuantumStateSnapshot not found',
                ];
            }

            return [
                'status' => 'ok',
                'message' => 'Quantum subsystem healthy',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Quantum subsystem check failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check KIP subsystem health
     *
     * @return array Status array with 'status' and 'message' keys
     */
    public function checkKIPSubsystem(): array
    {
        try {
            $kipEnginePath = __DIR__ . '/../../../lupo-includes/KIP/KIPEngine.php';
            if (!file_exists($kipEnginePath)) {
                return [
                    'status' => 'error',
                    'message' => 'KIPEngine not found',
                ];
            }

            $kipValidatorPath = __DIR__ . '/../../../lupo-includes/KIP/KIPValidator.php';
            if (!file_exists($kipValidatorPath)) {
                return [
                    'status' => 'error',
                    'message' => 'KIPValidator not found',
                ];
            }

            return [
                'status' => 'ok',
                'message' => 'KIP subsystem healthy',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'KIP subsystem check failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check limits subsystem health
     *
     * @return array Status array with 'status' and 'message' keys
     */
    public function checkLimitsSubsystem(): array
    {
        try {
            $limitsServicePath = __DIR__ . '/LimitsEnforcementService.php';
            if (!file_exists($limitsServicePath)) {
                return [
                    'status' => 'error',
                    'message' => 'LimitsEnforcementService not found',
                ];
            }

            $limitsMdPath = __DIR__ . '/../../../LIMITS.md';
            if (!file_exists($limitsMdPath)) {
                return [
                    'status' => 'warning',
                    'message' => 'LIMITS.md not found',
                ];
            }

            return [
                'status' => 'ok',
                'message' => 'Limits subsystem healthy',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Limits subsystem check failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check Pack Architecture readiness
     *
     * @return array Status array with 'status' and 'message' keys
     */
    public function checkPackReadiness(): array
    {
        try {
            $packRegistryPath = __DIR__ . '/../../../lupo-includes/Pack/PackRegistry.php';
            if (!file_exists($packRegistryPath)) {
                return [
                    'status' => 'warning',
                    'message' => 'PackRegistry not found (Pack Architecture not yet activated)',
                ];
            }

            $packContextPath = __DIR__ . '/../../../lupo-includes/Pack/PackContext.php';
            if (!file_exists($packContextPath)) {
                return [
                    'status' => 'warning',
                    'message' => 'PackContext not found (Pack Architecture not yet activated)',
                ];
            }

            $packHandoffPath = __DIR__ . '/../../../lupo-includes/Pack/PackHandoffProtocol.php';
            if (!file_exists($packHandoffPath)) {
                return [
                    'status' => 'warning',
                    'message' => 'PackHandoffProtocol not found (Pack Architecture not yet activated)',
                ];
            }

            return [
                'status' => 'ok',
                'message' => 'Pack Architecture pre-activation complete (ready for 4.1.0)',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Pack readiness check failed: ' . $e->getMessage(),
            ];
        }
    }
}
