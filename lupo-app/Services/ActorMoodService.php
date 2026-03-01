<?php
/**
 * Actor Mood Service
 *
 * Manages actor mood logging and retrieval using the 2-actor RGB mood model.
 * Moods are discrete values: R, G, B ∈ {-1, 0, 1}
 *
 * See: doctrine/EMOTIONAL_GEOMETRY_DOCTRINE.md for canonical 2-actor RGB mood model
 * See: docs/appendix/COUNTING_IN_LIGHT.md for RGB axis semantics
 *
 * @package Lupopedia
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 * @author GLOBAL_CURRENT_AUTHORS
 */

namespace App\Services;

use Exception;
use InvalidArgumentException;

/**
 * ActorMoodService
 *
 * Logs and retrieves actor moods using discrete RGB values (-1, 0, 1).
 *
 * NOTE: Uses table `actor_moods` with columns:
 * - actor_id (BIGINT UNSIGNED, NOT NULL)
 * - mood_r (TINYINT, NOT NULL) - Strife/Conflict axis
 * - mood_g (TINYINT, NOT NULL) - Harmony/Cohesion axis
 * - mood_b (TINYINT, NOT NULL) - Memory/Persistence axis
 * - timestamp_utc (BIGINT UNSIGNED, NOT NULL) - YYYYMMDDHHIISS format
 *
 * Table schema defined in: database/toon_data/actor_moods.toon
 */
class ActorMoodService
{
    /** @var object Database connection (PDO or PDO_DB) */
    private $db;

    /** @var array Valid mood values */
    private const VALID_MOOD_VALUES = [-1, 0, 1];

    /**
     * Constructor
     *
     * @param object $db Database connection instance (PDO or PDO_DB)
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Log actor mood
     *
     * Validates discrete RGB mood values and inserts a row into actor_moods.
     *
     * @param int $actorId Actor ID
     * @param int $moodR Red axis (Strife/Conflict) ∈ {-1, 0, 1}
     * @param int $moodG Green axis (Harmony/Cohesion) ∈ {-1, 0, 1}
     * @param int $moodB Blue axis (Memory/Persistence) ∈ {-1, 0, 1}
     * @param int|null $timestampUtc Optional UTC timestamp (YYYYMMDDHHIISS), defaults to current UTC
     * @return void
     * @throws InvalidArgumentException If mood values are invalid
     * @throws Exception If database insert fails
     */
    public function logMood(int $actorId, int $moodR, int $moodG, int $moodB, ?int $timestampUtc = null): void
    {
        // Validate actor ID
        if ($actorId <= 0) {
            throw new InvalidArgumentException('Actor ID must be positive');
        }

        // Validate mood values (must be -1, 0, or 1)
        $this->validateMoodValue($moodR, 'mood_r');
        $this->validateMoodValue($moodG, 'mood_g');
        $this->validateMoodValue($moodB, 'mood_b');

        // Use current UTC if not provided
        if ($timestampUtc === null) {
            $timestampUtc = (int) gmdate('YmdHis');
        }

        // Validate timestamp format (must be 14 digits)
        if ($timestampUtc <= 0 || strlen((string) $timestampUtc) !== 14) {
            throw new InvalidArgumentException('Timestamp must be in YYYYMMDDHHIISS format (14 digits)');
        }

        // Insert mood record
        $data = [
            'actor_id' => $actorId,
            'mood_r' => $moodR,
            'mood_g' => $moodG,
            'mood_b' => $moodB,
            'timestamp_utc' => $timestampUtc
        ];

        try {
            $this->db->insert('actor_moods', $data);
        } catch (Exception $e) {
            throw new Exception('Database error logging mood: ' . $e->getMessage());
        }
    }

    /**
     * Get latest mood for actor
     *
     * Returns the most recent non-deleted mood record for the specified actor.
     *
     * @param int $actorId Actor ID
     * @return array|null Mood record array or null if none exists
     *   Format: ['actor_id' => int, 'mood_r' => int, 'mood_g' => int, 'mood_b' => int, 'timestamp_utc' => int]
     * @throws InvalidArgumentException If actor ID is invalid
     */
    public function getLatestMood(int $actorId): ?array
    {
        if ($actorId <= 0) {
            throw new InvalidArgumentException('Actor ID must be positive');
        }

        $sql = "
            SELECT
                actor_id,
                mood_r,
                mood_g,
                mood_b,
                timestamp_utc
            FROM actor_moods
            WHERE actor_id = :actor_id
            ORDER BY timestamp_utc DESC
            LIMIT 1
        ";

        $params = ['actor_id' => $actorId];

        try {
            $row = $this->db->fetchRow($sql, $params);

            if (!$row) {
                return null;
            }

            return [
                'actor_id' => (int) $row['actor_id'],
                'mood_r' => (int) $row['mood_r'],
                'mood_g' => (int) $row['mood_g'],
                'mood_b' => (int) $row['mood_b'],
                'timestamp_utc' => (int) $row['timestamp_utc']
            ];
        } catch (Exception $e) {
            throw new Exception('Database error fetching latest mood: ' . $e->getMessage());
        }
    }

    /**
     * Get mood history for actor
     *
     * Returns the last N non-deleted mood records for the specified actor,
     * ordered by timestamp descending (most recent first).
     *
     * @param int $actorId Actor ID
     * @param int $limit Maximum number of records to return (default: 50)
     * @return array Array of mood records
     *   Each record: ['actor_id' => int, 'mood_r' => int, 'mood_g' => int, 'mood_b' => int, 'timestamp_utc' => int]
     * @throws InvalidArgumentException If actor ID or limit is invalid
     */
    public function getMoodHistory(int $actorId, int $limit = 50): array
    {
        if ($actorId <= 0) {
            throw new InvalidArgumentException('Actor ID must be positive');
        }

        if ($limit <= 0) {
            throw new InvalidArgumentException('Limit must be positive');
        }

        // Cap limit at reasonable maximum
        $limit = min($limit, 1000);

        $sql = "
            SELECT
                actor_id,
                mood_r,
                mood_g,
                mood_b,
                timestamp_utc
            FROM actor_moods
            WHERE actor_id = :actor_id
            ORDER BY timestamp_utc DESC
            LIMIT :limit
        ";

        $params = [
            'actor_id' => $actorId,
            'limit' => $limit
        ];

        try {
            $rows = $this->db->fetchAll($sql, $params);

            if (!$rows) {
                return [];
            }

            // Normalize data types
            $history = [];
            foreach ($rows as $row) {
                $history[] = [
                    'actor_id' => (int) $row['actor_id'],
                    'mood_r' => (int) $row['mood_r'],
                    'mood_g' => (int) $row['mood_g'],
                    'mood_b' => (int) $row['mood_b'],
                    'timestamp_utc' => (int) $row['timestamp_utc']
                ];
            }

            return $history;
        } catch (Exception $e) {
            throw new Exception('Database error fetching mood history: ' . $e->getMessage());
        }
    }

    /**
     * Validate mood value
     *
     * Ensures value is one of {-1, 0, 1} as required by 2-actor RGB mood doctrine.
     *
     * @param int $value Mood value to validate
     * @param string $fieldName Field name for error message
     * @throws InvalidArgumentException If value is not in {-1, 0, 1}
     */
    private function validateMoodValue(int $value, string $fieldName): void
    {
        if (!in_array($value, self::VALID_MOOD_VALUES, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid %s: must be -1, 0, or 1 (got %d). See EMOTIONAL_GEOMETRY_DOCTRINE.md',
                    $fieldName,
                    $value
                )
            );
        }
    }
}
