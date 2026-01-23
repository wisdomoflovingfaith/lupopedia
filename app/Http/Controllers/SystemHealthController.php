<?php
/**
 * System Health Controller
 *
 * Provides HTTP endpoint for system health diagnostics.
 *
 * @package Lupopedia
 * @version 4.0.106
 * @author Captain Wolfie
 */

namespace App\Http\Controllers;

use App\Services\System\SystemHealthService;
use PDO;

/**
 * SystemHealthController
 *
 * Handles system health check requests.
 */
class SystemHealthController
{
    /**
     * Health check endpoint
     *
     * @return string JSON response with system health status
     */
    public function health(): string
    {
        try {
            // Get database connection if available
            $db = null;
            if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER') && defined('DB_PASS')) {
                try {
                    $db = new PDO(
                        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                        DB_USER,
                        DB_PASS,
                        [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                            PDO::ATTR_EMULATE_PREPARES => false
                        ]
                    );
                } catch (\Exception $e) {
                    // Database connection failed, continue without it
                    $db = null;
                }
            }

            $healthService = new SystemHealthService($db);

            $health = [
                'database_schema' => $healthService->checkDatabaseSchema(),
                'agent_registry' => $healthService->checkAgentRegistry(),
                'quantum_subsystem' => $healthService->checkQuantumSubsystem(),
                'kip_subsystem' => $healthService->checkKIPSubsystem(),
                'limits_subsystem' => $healthService->checkLimitsSubsystem(),
                'pack_readiness' => $healthService->checkPackReadiness(),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
                'version' => '4.0.106',
            ];

            header('Content-Type: application/json');
            return json_encode($health, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            return json_encode([
                'status' => 'error',
                'message' => 'Health check failed: ' . $e->getMessage(),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ], JSON_PRETTY_PRINT);
        }
    }
}
