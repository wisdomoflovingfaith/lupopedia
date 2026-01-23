<?php
/**
 * ⚠️ DEPRECATED: Emotional Geometry Engine
 *
 * DEPRECATED as of 4.4.x - replaced by 2-Actor RGB Mood Model
 * See: doctrine/EMOTIONAL_GEOMETRY_DOCTRINE.md for current canonical model
 *
 * Previous scalar and 5-tuple emotional models (4.0.x–4.2.x) are deprecated
 * and replaced by the 2-actor RGB mood geometry.
 *
 * Core engine for emotional vector normalization, intensity calculation, and affinity computation.
 * Implements emotional geometry doctrine for Pack Architecture.
 *
 * Canonical Axis Mapping:
 * - R (Red) = +1 axis (positive pole)
 * - G (Green) = 0 axis (neutral plane)
 * - B (Blue) = -1 axis (negative pole)
 *
 * @package Lupopedia
 * @version 4.0.114
 * @author Captain Wolfie
 */

namespace Lupopedia\EmotionalGeometry;

/**
 * ⚠️ DEPRECATED: EmotionalGeometryEngine
 *
 * DEPRECATED as of 4.4.x - replaced by 2-Actor RGB Mood Model
 * This class implements the deprecated Pack Architecture emotional geometry system.
 * New implementations should use the 2-Actor RGB Mood Model instead.
 *
 * Previous approach: Complex [-9999, 9999] range with normalization formulas
 * New approach: Simple discrete {-1, 0, 1} values with actor relationships
 *
 * Provides core emotional geometry calculations for Pack Architecture.
 */
class EmotionalGeometryEngine
{
    /** @var int Minimum RGB value */
    private const RGB_MIN = -9999;

    /** @var int Maximum RGB value */
    private const RGB_MAX = 9999;

    /** @var float Normalized range minimum */
    private const NORMALIZED_MIN = -1.0;

    /** @var float Normalized range maximum */
    private const NORMALIZED_MAX = 1.0;

    /**
     * Normalize RGB vector to [-1, 1] range
     *
     * Canonical axis mapping: R = +1, G = 0, B = -1
     *
     * @param array|string $rgb RGB values (array ['r' => int, 'g' => int, 'b' => int] or hex string)
     * @return array Normalized vector ['r' => float, 'g' => float, 'b' => float]
     */
    public function normalizeVector($rgb): array
    {
        $rgbArray = $this->parseRGB($rgb);

        $normalized = [
            'r' => $this->normalizeComponent($rgbArray['r']),
            'g' => $this->normalizeComponent($rgbArray['g']),
            'b' => $this->normalizeComponent($rgbArray['b']),
        ];

        return $normalized;
    }

    /**
     * Normalize a single RGB component
     *
     * @param int $component RGB component value
     * @return float Normalized value in [-1, 1] range
     */
    private function normalizeComponent(int $component): float
    {
        // Clamp to valid range
        $clamped = max(self::RGB_MIN, min(self::RGB_MAX, $component));

        // Normalize: map [-9999, 9999] to [-1, 1]
        $range = self::RGB_MAX - self::RGB_MIN;
        $normalized = (($clamped - self::RGB_MIN) / $range) * (self::NORMALIZED_MAX - self::NORMALIZED_MIN) + self::NORMALIZED_MIN;

        return round($normalized, 6);
    }

    /**
     * Calculate intensity (magnitude) of emotional vector
     *
     * @param array|string $rgb RGB values
     * @return float Intensity value (0.0 to sqrt(3))
     */
    public function calculateIntensity($rgb): float
    {
        $normalized = $this->normalizeVector($rgb);

        // Magnitude = sqrt(r^2 + g^2 + b^2)
        $magnitude = sqrt(
            pow($normalized['r'], 2) +
            pow($normalized['g'], 2) +
            pow($normalized['b'], 2)
        );

        return round($magnitude, 6);
    }

    /**
     * Calculate affinity (cosine similarity) between two emotional vectors
     *
     * @param array|string $vectorA First RGB vector
     * @param array|string $vectorB Second RGB vector
     * @return float Affinity value (-1.0 to 1.0)
     */
    public function calculateAffinity($vectorA, $vectorB): float
    {
        $normalizedA = $this->normalizeVector($vectorA);
        $normalizedB = $this->normalizeVector($vectorB);

        // Dot product
        $dotProduct = (
            $normalizedA['r'] * $normalizedB['r'] +
            $normalizedA['g'] * $normalizedB['g'] +
            $normalizedA['b'] * $normalizedB['b']
        );

        // Magnitudes
        $magnitudeA = $this->calculateIntensity($vectorA);
        $magnitudeB = $this->calculateIntensity($vectorB);

        // Avoid division by zero
        if ($magnitudeA == 0 || $magnitudeB == 0) {
            return 0.0;
        }

        // Cosine similarity = dot product / (magnitudeA * magnitudeB)
        $affinity = $dotProduct / ($magnitudeA * $magnitudeB);

        return round($affinity, 6);
    }

    /**
     * Encode RGB emotional geometry into canonical structure
     *
     * @param array|string $rgb RGB values
     * @return array Encoded structure
     */
    public function encode($rgb): array
    {
        $rgbArray = $this->parseRGB($rgb);
        $normalized = $this->normalizeVector($rgb);
        $intensity = $this->calculateIntensity($rgb);

        return [
            'r' => $rgbArray['r'],
            'g' => $rgbArray['g'],
            'b' => $rgbArray['b'],
            'normalized' => $normalized,
            'intensity' => $intensity,
        ];
    }

    /**
     * Decode encoded emotional geometry to raw RGB values
     *
     * @param array $encoded Encoded structure
     * @return array Raw RGB values ['r' => int, 'g' => int, 'b' => int]
     */
    public function decode(array $encoded): array
    {
        return [
            'r' => $encoded['r'] ?? 0,
            'g' => $encoded['g'] ?? 0,
            'b' => $encoded['b'] ?? 0,
        ];
    }

    /**
     * Parse RGB input (array or hex string) to array format
     *
     * @param array|string $rgb RGB input
     * @return array Parsed RGB array ['r' => int, 'g' => int, 'b' => int]
     */
    private function parseRGB($rgb): array
    {
        if (is_array($rgb)) {
            return [
                'r' => (int)($rgb['r'] ?? $rgb[0] ?? 0),
                'g' => (int)($rgb['g'] ?? $rgb[1] ?? 0),
                'b' => (int)($rgb['b'] ?? $rgb[2] ?? 0),
            ];
        }

        if (is_string($rgb)) {
            // Remove # if present
            $rgb = ltrim($rgb, '#');

            // Parse hex string (e.g., "FF0000" or "ff0000")
            if (strlen($rgb) == 6 && ctype_xdigit($rgb)) {
                return [
                    'r' => hexdec(substr($rgb, 0, 2)),
                    'g' => hexdec(substr($rgb, 2, 2)),
                    'b' => hexdec(substr($rgb, 4, 2)),
                ];
            }

            // Try to parse as comma-separated values
            $parts = explode(',', $rgb);
            if (count($parts) == 3) {
                return [
                    'r' => (int)trim($parts[0]),
                    'g' => (int)trim($parts[1]),
                    'b' => (int)trim($parts[2]),
                ];
            }
        }

        // Default to neutral (0, 0, 0)
        return ['r' => 0, 'g' => 0, 'b' => 0];
    }
}
