<?php
/**
 * Pack Mood Coherence Service
 *
 * Calculates coherence between two actors using the 2-actor RGB mood model.
 * Implements compare-notes protocol threshold detection.
 *
 * Coherence formula:
 * - d = |Ra - Rb| + |Ga - Gb| + |Ba - Bb|  (Manhattan distance)
 * - c = 1 - (d / 6)  (normalized to 0.0-1.0 range)
 *
 * Thresholds:
 * - c > 0.9: Too aligned (echo chamber risk)
 * - c < 0.3: Too divergent (fragmentation risk)
 * - 0.3 ≤ c ≤ 0.9: Healthy divergence
 *
 * See: doctrine/EMOTIONAL_GEOMETRY_DOCTRINE.md for canonical 2-actor RGB model
 *
 * @package Lupopedia
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 * @author GLOBAL_CURRENT_AUTHORS
 */

namespace App\Services;

use InvalidArgumentException;

/**
 * PackMoodCoherenceService
 *
 * Computes coherence between two actors and determines if compare-notes protocol is needed.
 */
class PackMoodCoherenceService
{
    /** @var ActorMoodService */
    private $moodService;

    /** @var float High coherence threshold (echo chamber risk) */
    private const THRESHOLD_HIGH = 0.9;

    /** @var float Low coherence threshold (fragmentation risk) */
    private const THRESHOLD_LOW = 0.3;

    /** @var int Maximum Manhattan distance in discrete {-1,0,1} space */
    private const MAX_DISTANCE = 6;

    /**
     * Constructor
     *
     * @param ActorMoodService $moodService Actor mood service instance
     */
    public function __construct(ActorMoodService $moodService)
    {
        $this->moodService = $moodService;
    }

    /**
     * Compute coherence between two actors
     *
     * Uses Manhattan distance for discrete mood space calculation:
     * - d = |Ra - Rb| + |Ga - Gb| + |Ba - Bb|
     * - c = 1 - (d / 6)
     *
     * @param int $actorAId First actor ID
     * @param int $actorBId Second actor ID
     * @return array|null Coherence data array or null if either actor has no mood
     *   Format: [
     *     'actor_a_id' => int,
     *     'actor_b_id' => int,
     *     'mood_a' => ['r' => int, 'g' => int, 'b' => int],
     *     'mood_b' => ['r' => int, 'g' => int, 'b' => int],
     *     'distance' => int,
     *     'coherence' => float,
     *     'status' => string  ('too_aligned'|'too_divergent'|'healthy')
     *   ]
     * @throws InvalidArgumentException If actor IDs are invalid
     */
    public function computeCoherence(int $actorAId, int $actorBId): ?array
    {
        // Validate actor IDs
        if ($actorAId <= 0 || $actorBId <= 0) {
            throw new InvalidArgumentException('Actor IDs must be positive integers');
        }

        // Get latest moods for both actors
        $moodA = $this->moodService->getLatestMood($actorAId);
        $moodB = $this->moodService->getLatestMood($actorBId);

        // Return null if either actor has no mood records
        if ($moodA === null || $moodB === null) {
            return null;
        }

        // Extract RGB values
        $Ra = $moodA['mood_r'];
        $Ga = $moodA['mood_g'];
        $Ba = $moodA['mood_b'];

        $Rb = $moodB['mood_r'];
        $Gb = $moodB['mood_g'];
        $Bb = $moodB['mood_b'];

        // Compute Manhattan distance
        // d = |Ra - Rb| + |Ga - Gb| + |Ba - Bb|
        $distance = abs($Ra - $Rb) + abs($Ga - $Gb) + abs($Ba - $Bb);

        // Normalize to coherence score (0.0 - 1.0)
        // c = 1 - (d / 6)
        $coherence = 1.0 - ($distance / self::MAX_DISTANCE);

        // Determine status based on thresholds
        $status = $this->determineStatus($coherence);

        return [
            'actor_a_id' => $actorAId,
            'actor_b_id' => $actorBId,
            'mood_a' => [
                'r' => $Ra,
                'g' => $Ga,
                'b' => $Ba
            ],
            'mood_b' => [
                'r' => $Rb,
                'g' => $Gb,
                'b' => $Bb
            ],
            'distance' => $distance,
            'coherence' => round($coherence, 4),
            'status' => $status
        ];
    }

    /**
     * Check if compare-notes protocol is needed
     *
     * Returns true if coherence is outside healthy range (too aligned or too divergent).
     *
     * @param int $actorAId First actor ID
     * @param int $actorBId Second actor ID
     * @return bool True if compare-notes protocol should be triggered
     * @throws InvalidArgumentException If actor IDs are invalid
     */
    public function compareNotesNeeded(int $actorAId, int $actorBId): bool
    {
        $coherenceData = $this->computeCoherence($actorAId, $actorBId);

        // If no mood data available, no action needed
        if ($coherenceData === null) {
            return false;
        }

        $status = $coherenceData['status'];

        // Trigger compare-notes if too aligned or too divergent
        return $status === 'too_aligned' || $status === 'too_divergent';
    }

    /**
     * Determine coherence status based on thresholds
     *
     * @param float $coherence Coherence value (0.0 - 1.0)
     * @return string Status: 'too_aligned', 'too_divergent', or 'healthy'
     */
    private function determineStatus(float $coherence): string
    {
        if ($coherence > self::THRESHOLD_HIGH) {
            return 'too_aligned';
        }

        if ($coherence < self::THRESHOLD_LOW) {
            return 'too_divergent';
        }

        return 'healthy';
    }

    /**
     * Get coherence thresholds
     *
     * Useful for documentation and UI display.
     *
     * @return array Threshold configuration
     */
    public function getThresholds(): array
    {
        return [
            'high' => self::THRESHOLD_HIGH,
            'low' => self::THRESHOLD_LOW,
            'healthy_range' => [
                'min' => self::THRESHOLD_LOW,
                'max' => self::THRESHOLD_HIGH
            ]
        ];
    }
}
