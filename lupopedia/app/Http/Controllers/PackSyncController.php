<?php
/**
 * Pack Synchronization Controller
 *
 * HTTP endpoint for Pack synchronization operations.
 *
 * @package Lupopedia
 * @version 4.0.112
 * @author Captain Wolfie
 */

namespace App\Http\Controllers;

use App\Services\Pack\PackSyncService;

/**
 * PackSyncController
 *
 * Handles Pack synchronization API requests.
 */
class PackSyncController
{
    /**
     * Run full synchronization endpoint
     *
     * @return string JSON response with sync report
     */
    public function run(): string
    {
        try {
            $service = new PackSyncService();
            $report = $service->run();

            header('Content-Type: application/json');
            return json_encode($report, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            return json_encode([
                'status' => 'error',
                'message' => 'Synchronization failed: ' . $e->getMessage(),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Synchronize emotions endpoint
     *
     * @return string JSON response with emotional sync report
     */
    public function emotions(): string
    {
        try {
            $service = new PackSyncService();
            $report = $service->emotions();

            $result = [
                'status' => 'ok',
                'report' => $report,
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ];

            header('Content-Type: application/json');
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            return json_encode([
                'status' => 'error',
                'message' => 'Emotional synchronization failed: ' . $e->getMessage(),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Synchronize behavior endpoint
     *
     * @return string JSON response with behavioral sync report
     */
    public function behavior(): string
    {
        try {
            $service = new PackSyncService();
            $report = $service->behavior();

            $result = [
                'status' => 'ok',
                'report' => $report,
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ];

            header('Content-Type: application/json');
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            return json_encode([
                'status' => 'error',
                'message' => 'Behavioral synchronization failed: ' . $e->getMessage(),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Synchronize memory endpoint
     *
     * @return string JSON response with memory sync report
     */
    public function memory(): string
    {
        try {
            $service = new PackSyncService();
            $report = $service->memory();

            $result = [
                'status' => 'ok',
                'report' => $report,
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ];

            header('Content-Type: application/json');
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            return json_encode([
                'status' => 'error',
                'message' => 'Memory synchronization failed: ' . $e->getMessage(),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ], JSON_PRETTY_PRINT);
        }
    }
}
