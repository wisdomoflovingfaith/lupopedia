<?php
/**
 * Pack Behavior Controller
 *
 * HTTP endpoint for Pack behavioral operations.
 *
 * @package Lupopedia
 * @version 4.0.109
 * @author Captain Wolfie
 */

namespace App\Http\Controllers;

use App\Services\Pack\PackBehaviorService;

/**
 * PackBehaviorController
 *
 * Handles Pack behavioral API requests.
 */
class PackBehaviorController
{
    /**
     * Profile endpoint
     *
     * @return string JSON response with behavior profile
     */
    public function profile(): string
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

            $service = new PackBehaviorService();
            $profile = $service->getProfile($agentId);

            $result = [
                'status' => 'ok',
                'profile' => $profile,
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ];

            header('Content-Type: application/json');
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            return json_encode([
                'status' => 'error',
                'message' => 'Profile retrieval failed: ' . $e->getMessage(),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Compatibility endpoint
     *
     * @return string JSON response with behavioral compatibility
     */
    public function compatibility(): string
    {
        try {
            // Get agent IDs from request
            $input = json_decode(file_get_contents('php://input'), true) ?? [];
            $fromAgent = $input['from_agent'] ?? $_GET['from_agent'] ?? null;
            $toAgent = $input['to_agent'] ?? $_GET['to_agent'] ?? null;

            if ($fromAgent === null || $toAgent === null) {
                header('Content-Type: application/json');
                http_response_code(400);
                return json_encode([
                    'status' => 'error',
                    'message' => 'Both from_agent and to_agent are required',
                ], JSON_PRETTY_PRINT);
            }

            $service = new PackBehaviorService();
            $compatibility = $service->compatibility($fromAgent, $toAgent);

            $result = [
                'status' => 'ok',
                'compatibility' => $compatibility,
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ];

            header('Content-Type: application/json');
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            return json_encode([
                'status' => 'error',
                'message' => 'Compatibility calculation failed: ' . $e->getMessage(),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ], JSON_PRETTY_PRINT);
        }
    }
}
