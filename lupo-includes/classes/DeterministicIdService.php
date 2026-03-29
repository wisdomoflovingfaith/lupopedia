<?php
/**
 * DeterministicIdService
 *
 * Generates deterministic BIGINT identifiers for doctrine-compliant paths
 * without AUTO_INCREMENT. Designed for high-throughput inserts by caching
 * per-table/per-second counters in memory.
 *
 * Format (18 digits):
 * [1-digit version][14-digit UTC ymdhis][3-digit sequence]
 *
 * Notes:
 * - Use gmdate('YmdHis') timestamps only.
 * - For deterministic replay, pass explicit timestamp and sequence.
 */
class DeterministicIdService
{
    const FORMAT_VERSION = 1;
    const MAX_SEQUENCE = 999;

    /** @var array<string,int> */
    private static $baseByTable = array();

    /** @var array<string,int> */
    private static $counterByKey = array();

    /**
     * Allocate deterministic ID with low-overhead in-memory sequence tracking.
     *
     * @param string $tableName
     * @param string|null $ymdhis 14-digit UTC timestamp
     * @return int
     */
    public static function allocate($tableName, $ymdhis = null)
    {
        if ($ymdhis === null) {
            $ymdhis = gmdate('YmdHis');
        }

        self::assertYmdhis($ymdhis);

        $tableName = (string)$tableName;
        if ($tableName === '') {
            throw new InvalidArgumentException('Table name is required for deterministic ID allocation.');
        }

        if (!isset(self::$baseByTable[$tableName])) {
            // Stable table-specific offset to reduce cross-table same-second collisions.
            self::$baseByTable[$tableName] = abs(crc32($tableName)) % 100;
        }

        $key = $tableName . '|' . $ymdhis;
        if (!isset(self::$counterByKey[$key])) {
            self::$counterByKey[$key] = self::$baseByTable[$tableName];
        } else {
            self::$counterByKey[$key]++;
        }

        if (self::$counterByKey[$key] > self::MAX_SEQUENCE) {
            throw new RuntimeException('Deterministic ID sequence overflow for table ' . $tableName . ' at ' . $ymdhis);
        }

        $sequence = str_pad((string)self::$counterByKey[$key], 3, '0', STR_PAD_LEFT);
        $id = sprintf('%d%s%s', self::FORMAT_VERSION, $ymdhis, $sequence);

        // 18-digit numeric string fits signed BIGINT range for current format.
        return (int)$id;
    }

    /**
     * Build deterministic ID from explicit inputs (replay/testing helper).
     *
     * @param string $ymdhis
     * @param int $sequence
     * @return int
     */
    public static function fromParts($ymdhis, $sequence)
    {
        self::assertYmdhis($ymdhis);
        $sequence = (int)$sequence;
        if ($sequence < 0 || $sequence > self::MAX_SEQUENCE) {
            throw new InvalidArgumentException('Sequence must be between 0 and ' . self::MAX_SEQUENCE);
        }
        $id = sprintf('%d%s%03d', self::FORMAT_VERSION, $ymdhis, $sequence);
        return (int)$id;
    }

    /**
     * Clear internal counters (for tests and controlled batch boundaries).
     *
     * @return void
     */
    public static function resetCounters()
    {
        self::$counterByKey = array();
    }

    /**
     * @param string $ymdhis
     * @return void
     */
    private static function assertYmdhis($ymdhis)
    {
        if (!is_string($ymdhis) || !preg_match('/^[0-9]{14}$/', $ymdhis)) {
            throw new InvalidArgumentException('Timestamp must be UTC YmdHis (14 digits).');
        }
    }
}
