<?php
/**
 * Lupopedia Deterministic ID Generator
 * Actor: HEPHAESTUS (102)
 * Doctrine: No Auto-Increment, Application-Layer Sovereignty.
 */
class Lupo_Id_Generator {
    // Lupopedia Epoch: 2024-01-01 00:00:00 UTC (milliseconds)
    private const EPOCH = 1704067200000;
    private static $lastTimestamp = -1;
    private static $sequence = 0;

    public static function generate($nodeId = 0) {
        $timestamp = self::timeGen();

        if (self::$lastTimestamp == $timestamp) {
            self::$sequence = (self::$sequence + 1) & 4095;
            if (self::$sequence == 0) {
                $timestamp = self::tilNextMillis(self::$lastTimestamp);
            }
        } else {
            self::$sequence = 0;
        }

        self::$lastTimestamp = $timestamp;

        // [Timestamp: 42 bits] [Node: 10 bits] [Sequence: 12 bits]
        $id = (($timestamp - self::EPOCH) << 22)
            | (($nodeId & 0x3FF) << 12)
            | (self::$sequence & 0xFFF);

        return (string) sprintf('%u', $id); // Unsigned BIGINT string
    }

    private static function timeGen() {
        return (int)(microtime(true) * 1000);
    }

    private static function tilNextMillis($lastTimestamp) {
        $timestamp = self::timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = self::timeGen();
        }
        return $timestamp;
    }

    /**
     * Converts current time to the required BIGINT YYYYMMDDHHIISS
     * Doctrine #4: No TIMESTAMP types.
     */
    public static function getLupoTimestamp() {
        return (int) gmdate('YmdHis');
    }
}
