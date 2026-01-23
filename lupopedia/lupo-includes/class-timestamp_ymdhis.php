<?php

/**
 * timestamp_ymdhis — Unified Doctrine-Aligned Timestamp Utility
 *
 * PURPOSE:
 *   Provide MySQL-like timestamp/datetime functions for BIGINT(14)
 *   UTC timestamps in the format YYYYMMDDHHIISS.
 *
 * DOCTRINE:
 *   - No DATETIME
 *   - No TIMESTAMP
 *   - No epoch storage
 *   - Always UTC
 *   - Always BIGINT(14)
 */

class timestamp_ymdhis
{
    /* ============================================================
     * CORE
     * ============================================================ */

    public static function now(): int
    {
        return (int) gmdate('YmdHis');
    }

    public static function explode(int $ts): array
    {
        $s = str_pad((string)$ts, 14, '0', STR_PAD_LEFT);

        return [
            'year'   => (int) substr($s, 0, 4),
            'month'  => (int) substr($s, 4, 2),
            'day'    => (int) substr($s, 6, 2),
            'hour'   => (int) substr($s, 8, 2),
            'minute' => (int) substr($s, 10, 2),
            'second' => (int) substr($s, 12, 2),
        ];
    }

    public static function implode(array $c): int
    {
        return (int) sprintf(
            '%04d%02d%02d%02d%02d%02d',
            $c['year'], $c['month'], $c['day'],
            $c['hour'], $c['minute'], $c['second']
        );
    }

    /* ============================================================
     * ARITHMETIC (MySQL-style)
     * ============================================================ */

    public static function addSeconds(int $ts, int $seconds): int
    {
        $c = self::explode($ts);

        $epoch = gmmktime(
            $c['hour'], $c['minute'], $c['second'],
            $c['month'], $c['day'], $c['year']
        );

        return (int) gmdate('YmdHis', $epoch + $seconds);
    }

    public static function subtractSeconds(int $ts, int $seconds): int
    {
        return self::addSeconds($ts, -$seconds);
    }

    public static function addMinutes(int $ts, int $minutes): int
    {
        return self::addSeconds($ts, $minutes * 60);
    }

    public static function addHours(int $ts, int $hours): int
    {
        return self::addSeconds($ts, $hours * 3600);
    }

    public static function diffInSeconds(int $a, int $b): int
    {
        $ca = self::explode($a);
        $cb = self::explode($b);

        $ea = gmmktime(
            $ca['hour'], $ca['minute'], $ca['second'],
            $ca['month'], $ca['day'], $ca['year']
        );

        $eb = gmmktime(
            $cb['hour'], $cb['minute'], $cb['second'],
            $cb['month'], $cb['day'], $cb['year']
        );

        return $ea - $eb;
    }

    /* ============================================================
     * COMPARISON
     * ============================================================ */

    public static function isBefore(int $a, int $b): bool
    {
        return $a < $b;
    }

    public static function isAfter(int $a, int $b): bool
    {
        return $a > $b;
    }

    public static function isBetween(int $ts, int $start, int $end): bool
    {
        return ($ts >= $start && $ts <= $end);
    }

    /* ============================================================
     * FORMATTING
     * ============================================================ */

    public static function toHuman(int $ts): string
    {
        $c = self::explode($ts);

        return sprintf(
            '%04d-%02d-%02d %02d:%02d:%02d UTC',
            $c['year'], $c['month'], $c['day'],
            $c['hour'], $c['minute'], $c['second']
        );
    }

    public static function fromHuman(string $str): int
    {
        return (int) gmdate('YmdHis', strtotime($str . ' UTC'));
    }

    /* ============================================================
     * INTERVAL HELPERS
     * ============================================================ */

    public static function interval(int $start, int $end): array
    {
        return ['start' => $start, 'end' => $end];
    }

    public static function overlaps(array $a, array $b): bool
    {
        return !($a['end'] < $b['start'] || $b['end'] < $a['start']);
    }

    public static function intersection(array $a, array $b): ?array
    {
        if (!self::overlaps($a, $b)) {
            return null;
        }

        return [
            'start' => max($a['start'], $b['start']),
            'end'   => min($a['end'], $b['end'])
        ];
    }

    public static function shift(array $interval, int $seconds): array
    {
        return [
            'start' => self::addSeconds($interval['start'], $seconds),
            'end'   => self::addSeconds($interval['end'], $seconds)
        ];
    }

    public static function expand(array $interval, int $seconds): array
    {
        return [
            'start' => self::subtractSeconds($interval['start'], $seconds),
            'end'   => self::addSeconds($interval['end'], $seconds)
        ];
    }
}

?>