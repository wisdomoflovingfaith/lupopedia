<?php
/**
 * Pack Memory Controller
 *
 * HTTP endpoint for Pack memory operations.
 *
 * @package Lupopedia
 * @version 4.0.110
 * @author Captain Wolfie
 */

namespace App\Http\Controllers;

use App\Services\Pack\PackMemoryService;

/**
 * PackMemoryController
 *
 * Handles Pack memory API requests.
 */
class PackMemoryController
{
    /**
     * Episodic memory endpoint
     *
     * @return string JSON response with episodic memory
     */
    public function episodic(): string
    {
        try {
            // Get agent ID from request
            $input = json_decode(file_get_contents('php://input'), true) ?? [];
            $agentId = $input['agent_id'] ?? $_GET['agent_id'] ?? null;
            $limit = isset($input['limit']) ? (int)$input['limit'] : (isset($_GET['limit']) ? (int)$_GET['limit'] : null);

            if ($agentId === null) {
                header('Content-Type: application/json');
                http_response_code(400);
                return json_encode([
                    'status' => 'error',
                    'message' => 'agent_id is required',
                ], JSON_PRETTY_PRINT);
            }

            $service = new PackMemoryService();
            $events = $service->episodic($agentId, $limit);

            $result = [
                'status' => 'ok',
                'agent_id' => $agentId,
                'events' => $events,
                'count' => count($events),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ];

            header('Content-Type: application/json');
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            return json_encode([
                'status' => 'error',
                'message' => 'Episodic memory retrieval failed: ' . $e->getMessage(),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Emotional memory endpoint
     *
     * @return string JSON response with emotional memory
     */
    public function emotional(): string
    {
        try {
            // Get agent ID from request
            $input = json_decode(file_get_contents('php://input'), true) ?? [];
            $agentId = $input['agent_id'] ?? $_GET['agent_id'] ?? null;
            $limit = isset($input['limit']) ? (int)$input['limit'] : (isset($_GET['limit']) ? (int)$_GET['limit'] : null);

            if ($agentId === null) {
                header('Content-Type: application/json');
                http_response_code(400);
                return json_encode([
                    'status' => 'error',
                    'message' => 'agent_id is required',
                ], JSON_PRETTY_PRINT);
            }

            $service = new PackMemoryService();
            $snapshots = $service->emotional($agentId, $limit);

            $result = [
                'status' => 'ok',
                'agent_id' => $agentId,
                'snapshots' => $snapshots,
                'count' => count($snapshots),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ];

            header('Content-Type: application/json');
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            return json_encode([
                'status' => 'error',
                'message' => 'Emotional memory retrieval failed: ' . $e->getMessage(),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Behavioral memory endpoint
     *
     * @return string JSON response with behavioral memory
     */
    public function behavioral(): string
    {
        try {
            // Get agent ID from request
            $input = json_decode(file_get_contents('php://input'), true) ?? [];
            $agentId = $input['agent_id'] ?? $_GET['agent_id'] ?? null;
            $limit = isset($input['limit']) ? (int)$input['limit'] : (isset($_GET['limit']) ? (int)$_GET['limit'] : null);

            if ($agentId === null) {
                header('Content-Type: application/json');
                http_response_code(400);
                return json_encode([
                    'status' => 'error',
                    'message' => 'agent_id is required',
                ], JSON_PRETTY_PRINT);
            }

            $service = new PackMemoryService();
            $snapshots = $service->behavioral($agentId, $limit);

            $result = [
                'status' => 'ok',
                'agent_id' => $agentId,
                'snapshots' => $snapshots,
                'count' => count($snapshots),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ];

            header('Content-Type: application/json');
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            return json_encode([
                'status' => 'error',
                'message' => 'Behavioral memory retrieval failed: ' . $e->getMessage(),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Handoff memory endpoint
     *
     * @return string JSON response with handoff memory
     */
    public function handoffs(): string
    {
        try {
            // Get agent ID from request
            $input = json_decode(file_get_contents('php://input'), true) ?? [];
            $agentId = $input['agent_id'] ?? $_GET['agent_id'] ?? null;

            if ($agentId === null) {
                header('Content-Type: application/json');
                http_response_code(400);
                return json_encode([
                    'status' => 'error',
                    'message' => 'agent_id is required',
                ], JSON_PRETTY_PRINT);
            }

            $service = new PackMemoryService();
            $handoffs = $service->handoffs($agentId);

            $result = [
                'status' => 'ok',
                'agent_id' => $agentId,
                'handoffs' => $handoffs,
                'count' => count($handoffs),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ];

            header('Content-Type: application/json');
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            return json_encode([
                'status' => 'error',
                'message' => 'Handoff memory retrieval failed: ' . $e->getMessage(),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ], JSON_PRETTY_PRINT);
        }
    }
}
