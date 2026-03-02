<?php
/**
 * System Health Service
 *
 * Provides diagnostic checks for all system subsystems.
 * Non-blocking, pure diagnostics with no exceptions.
 * PHP 5.3 compatible.
 *
 * @package Lupopedia
 * @version 4.0.53
 * @author Captain Wolfie
 */

namespace App\Services\System;

/**
 * SystemHealthService
 *
 * Performs health checks on all system subsystems.
 */
class SystemHealthService
{
    /** @var object|null Database connection (PDO_DB) */
    private $db;

    /**
     * Constructor
     *
     * @param object|null $db Database connection (optional)
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
    public function checkDatabaseSchema()
    {
        try {
            if (!$this->db) {
                return array(
                    'status' => 'warning',
                    'message' => 'Database connection not available',
                );
            }

            // Basic schema check - verify core tables exist (dynamic prefix)
            $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
            $coreTables = array($prefix . 'actors', $prefix . 'dialog_channels', $prefix . 'dialog_doctrine');
            $missingTables = array();

            foreach ($coreTables as $table) {
                $stmt = $this->db->query("SHOW TABLES LIKE '" . str_replace("'", "''", $table) . "'");
                if (!$stmt || $stmt->rowCount() === 0) {
                    $missingTables[] = $table;
                }
            }

            if (!empty($missingTables)) {
                return array(
                    'status' => 'error',
                    'message' => 'Missing core tables: ' . implode(', ', $missingTables),
                );
            }

            return array(
                'status' => 'ok',
                'message' => 'Database schema healthy',
            );
        } catch (\Exception $e) {
            return array(
                'status' => 'error',
                'message' => 'Schema check failed: ' . $e->getMessage(),
            );
        }
    }

    /**
     * Check agent registry health
     *
     * @return array Status array with 'status' and 'message' keys
     */
    public function checkAgentRegistry()
    {
        try {
            // Check if unified registry table exists (canonical for agents, channels, modules)
            if (!$this->db) {
                return array(
                    'status' => 'warning',
                    'message' => 'Database connection not available',
                );
            }

            $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
            $regTable = $prefix . 'registry';
            $stmt = $this->db->query("SHOW TABLES LIKE '" . str_replace("'", "''", $regTable) . "'");
            if (!$stmt || $stmt->rowCount() === 0) {
                return array(
                    'status' => 'warning',
                    'message' => 'Unified registry table not found',
                );
            }

            return array(
                'status' => 'ok',
                'message' => 'Unified registry (reserved index ledger) present',
            );
        } catch (\Exception $e) {
            return array(
                'status' => 'error',
                'message' => 'Unified registry check failed: ' . $e->getMessage(),
            );
        }
    }

    /**
     * Check KIP subsystem health
     *
     * @return array Status array with 'status' and 'message' keys
     */
    public function checkKIPSubsystem()
    {
        try {
            $kipEnginePath = LUPOPEDIA_ABSPATH . LUPO_INCLUDES_DIR . '/KIP/KIPEngine.php';
            if (!file_exists($kipEnginePath)) {
                return array(
                    'status' => 'error',
                    'message' => 'KIPEngine not found',
                );
            }

            return array(
                'status' => 'ok',
                'message' => 'KIP subsystem healthy',
            );
        } catch (\Exception $e) {
            return array(
                'status' => 'error',
                'message' => 'KIP subsystem check failed: ' . $e->getMessage(),
            );
        }
    }

    /**
     * Check Pack Architecture readiness
     *
     * @return array Status array with 'status' and 'message' keys
     */
    public function checkPackReadiness()
    {
        try {
            $packRegistryPath = LUPOPEDIA_ABSPATH . LUPO_INCLUDES_DIR . '/Pack/PackRegistry.php';
            if (!file_exists($packRegistryPath)) {
                return array(
                    'status' => 'warning',
                    'message' => 'PackRegistry not found (Pack Architecture not yet activated)',
                );
            }

            return array(
                'status' => 'ok',
                'message' => 'Pack Architecture pre-activation complete',
            );
        } catch (\Exception $e) {
            return array(
                'status' => 'error',
                'message' => 'Pack readiness check failed: ' . $e->getMessage(),
            );
        }
    }

    /**
     * Check AI agents status
     *
     * @return array Status array with 'status', 'message', and 'agents' keys
     */
    public function checkAIAgentsStatus()
    {
        try {
            if (!$this->db) {
                return array(
                    'status' => 'warning',
                    'message' => 'Database connection not available',
                );
            }

            // Ensure ai_activation functions are available
            $aiActivationPath = LUPOPEDIA_ABSPATH . LUPO_INCLUDES_DIR . '/functions/ai_activation.php';
            if (file_exists($aiActivationPath)) {
                require_once $aiActivationPath;
            } else {
                return array(
                    'status' => 'error',
                    'message' => 'AI activation helper not found',
                );
            }

            $agents = array(
                0 => 'SYSTEM',
                1 => 'CAPTAIN WOLFIE',
                2 => 'LILITH',
                19 => 'ANUBIS'
            );

            $results = array();
            $allRunning = true;
            $runningCount = 0;

            foreach ($agents as $id => $name) {
                $running = isActorAIRunning($id, $this->db);
                $results[$id] = array(
                    'name' => $name,
                    'status' => $running ? 'running' : 'offline'
                );
                if ($running) {
                    $runningCount++;
                } else {
                    $allRunning = false;
                }
            }

            return array(
                'status' => $allRunning ? 'ok' : ($runningCount > 0 ? 'warning' : 'error'),
                'message' => "AI Agents: $runningCount/" . count($agents) . " running",
                'agents' => $results
            );
        } catch (\Exception $e) {
            return array(
                'status' => 'error',
                'message' => 'AI status check failed: ' . $e->getMessage(),
            );
        }
    }
}
