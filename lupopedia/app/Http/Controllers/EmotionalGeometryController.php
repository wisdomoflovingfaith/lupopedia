<?php
/**
 * ⚠️ DEPRECATED: Emotional Geometry Controller
 *
 * DEPRECATED as of 4.4.x - replaced by 2-Actor RGB Mood Model
 * See: doctrine/EMOTIONAL_GEOMETRY_DOCTRINE.md for current canonical model
 *
 * Previous scalar and 5-tuple emotional models (4.0.x–4.2.x) are deprecated
 * and replaced by the 2-actor RGB mood geometry.
 *
 * HTTP endpoint for emotional geometry operations.
 *
 * @package Lupopedia
 * @version 4.0.108
 * @author Captain Wolfie
 */

namespace App\Http\Controllers;

use App\Services\EmotionalGeometry\EmotionalGeometryService;

/**
 * ⚠️ DEPRECATED: EmotionalGeometryController
 *
 * DEPRECATED as of 4.4.x - replaced by 2-Actor RGB Mood Model
 * This controller implements the deprecated Pack Architecture emotional geometry system.
 * New implementations should use the 2-Actor RGB Mood Model instead.
 *
 * Handles emotional geometry API requests.
 */
class EmotionalGeometryController
{
    /**
     * Affinity endpoint
     *
     * @return string JSON response with affinity and intensity metrics
     */
    public function affinity(): string
    {
        try {
            // Get RGB vectors from request
            $input = json_decode(file_get_contents('php://input'), true) ?? [];

            $rgbA = $input['vector_a'] ?? null;
            $rgbB = $input['vector_b'] ?? null;

            if ($rgbA === null || $rgbB === null) {
                header('Content-Type: application/json');
                http_response_code(400);
                return json_encode([
                    'status' => 'error',
                    'message' => 'Both vector_a and vector_b are required',
                ], JSON_PRETTY_PRINT);
            }

            $service = new EmotionalGeometryService();

            // Calculate metrics
            $affinity = $service->affinity($rgbA, $rgbB);
            $intensityA = $service->intensity($rgbA);
            $intensityB = $service->intensity($rgbB);
            $intensityDelta = abs($intensityA - $intensityB);

            // Encode vectors
            $encodedA = $service->encode($rgbA);
            $encodedB = $service->encode($rgbB);

            $result = [
                'status' => 'ok',
                'affinity' => $affinity,
                'intensity' => [
                    'vector_a' => $intensityA,
                    'vector_b' => $intensityB,
                    'delta' => $intensityDelta,
                ],
                'vectors' => [
                    'a' => $encodedA,
                    'b' => $encodedB,
                ],
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ];

            header('Content-Type: application/json');
            return json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            return json_encode([
                'status' => 'error',
                'message' => 'Affinity calculation failed: ' . $e->getMessage(),
                'timestamp' => gmdate('Y-m-d H:i:s UTC'),
            ], JSON_PRETTY_PRINT);
        }
    }
}
