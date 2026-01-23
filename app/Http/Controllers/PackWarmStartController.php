<?php
/**
 * Pack Warm Start Controller
 *
 * HTTP endpoint for Pack Architecture warm-start initialization.
 *
 * @package Lupopedia
 * @version 4.0.107
 * @author Captain Wolfie
 */

namespace App\Http\Controllers;

use App\Services\Pack\PackWarmStartService;

/**
 * PackWarmStartController
 *
 * Handles Pack warm-start requests.
 */
class PackWarmStartController
{
    /**
     * Warm-start endpoint
     *
     * @return string JSON response with warm-start status
     */
    public function warmStart(): string
    {
        try {
            $service = new PackWarmStartService();
            $result = $service->warmStart();

            header('Content-Type: application/json');
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            return json_encode([
                'status' => 'error',
                'message' => 'Warm-start failed: ' . $e->getMessage(),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ], JSON_PRETTY_PRINT);
        }
    }
}
