<?php
#   lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "lupo-includes/classes/TimestampYmdhis.php"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-includes/classes/TimestampYmdhis.php"
#   status: "active"
#   when_updated: "20260420080000"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/timestamp-ymdhis.toon"
#   atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
#   transcript_jsonl: "0/development/timestamp-ymdhis"
#   artifact_type: implementation
#   artifact_kind: library
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   content_slug: "timestamp-ymdhis"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "TimestampYmdhis.php — Unified Doctrine-Aligned Timestamp Utility"
#   summary: "Calendar UTC helpers for packed decimal timestamps stored as BIGINT in YYYYMMDDHHIISS format."
/**
 * timestamp_ymdhis — Unified Doctrine-Aligned Timestamp Utility
 *
 * PURPOSE:
 *   Calendar UTC helpers for packed decimal timestamps stored as BIGINT
 *   in the format YYYYMMDDHHIISS (fourteen digits, zero-padded).
 *
 * DOCTRINE:
 *   - No DATETIME / TIMESTAMP columns for these clocks
 *   - No Unix epoch (or milliseconds) as the canonical *persisted* clock
 *   - Always UTC
 *
 * YEAR 2038:
 *   The *database* value is not Unix time — it is a calendar label (e.g.
 *   20401231235959 sorts and compares correctly as an integer through year 9999
 *   in a BIGINT column. There is no Y2038 in *storage*.
 *
 *   PHP arithmetic here uses UTC DateTime + modify() / diff(), not gmmktime(),
 *   so conversion does not depend on a 32-bit time_t bridge. Tiered doctrine
 *   (PRD 00 §4 Option 4): production MUST use 64-bit PHP 7.4+; legacy 32-bit /
 *   PHP 5.6 hosts are transitional — see runtimePackedUtcIntSafe().
 *
 * PUBLIC API (all static; packed int = YYYYMMDDHHIISS UTC unless noted):
 *
 *   Core
 *     now()                      Current UTC as packed int.
 *     explode(int $ts)           Packed int → array year, month, day, hour, minute, second.
 *     implode(array $c)          Component array → packed int.
 */
# ---------------------------------------------------------------------
#
#   Arithmetic
#     addSeconds($ts, $seconds)  Add/subtract seconds (calendar-correct).
#     subtractSeconds($ts, $n)   addSeconds($ts, -$n).
#     addMinutes($ts, $n)        addSeconds($ts, $n * 60).
#     addHours($ts, $n)          addSeconds($ts, $n * 3600).
#     diffInSeconds($a, $b)      Signed seconds (a − b): time at a minus time at b.
#
#   Comparison
#     isBefore($a, $b)           $a < $b.
#     isAfter($a, $b)            $a > $b.
#     isBetween($ts, $start, $end)  $start <= $ts <= $end.
#
#   Formatting / parse
#     toHuman($ts)               "YYYY-MM-DD HH:MM:SS UTC".
#     fromHuman(string $str)     Parse string as UTC DateTime → packed; invalid → 0.
#     convert_bigint_to_iso8601($ts)   Packed → YYYY-MM-DDTHH:MM:SSZ.
#     convert_iso8601_to_bigint($iso)  ISO8601 UTC string → packed; invalid → 0.
#
#   Intervals (arrays: ['start' => packed, 'end' => packed])
#     interval($start, $end)     Build interval array.
#     overlaps($a, $b)           Ranges overlap.
#     intersection($a, $b)       Overlap range or null.
#     shift($interval, $seconds) Move both ends by seconds.
#     expand($interval, $seconds) Grow symmetrically (start earlier, end later).
#
#   Internal
#     dateTimeFromPacked($ts)    private — UTC DateTime from packed, or false.
#
# ---------------------------------------------------------------------

class timestamp_ymdhis
{
    /**
     * Build a UTC DateTime from a packed YmdHis value, or false if invalid.
     *
     * @param int $ts
     * @return \DateTime|false
     */
    private static function dateTimeFromPacked($ts)
    {
        $c = self::explode((int) $ts);

        return \DateTime::createFromFormat(
            '!Y-m-d H:i:s',
            sprintf(
                '%04d-%02d-%02d %02d:%02d:%02d',
                $c['year'],
                $c['month'],
                $c['day'],
                $c['hour'],
                $c['minute'],
                $c['second']
            ),
            new \DateTimeZone('UTC')
        );
    }

    /* ============================================================
     * CORE
     * ============================================================ */

    /**
     * True when PHP can hold fourteen-digit packed UTC in a signed integer (64-bit).
     * On 32-bit PHP, packed "now" exceeds PHP_INT_MAX even in the 2020s — use 64-bit PHP 7.4+ for production (PRD 00 §4).
     *
     * @return bool
     */
    public static function runtimePackedUtcIntSafe()
    {
        return defined('PHP_INT_SIZE') && (int) PHP_INT_SIZE >= 8;
    }

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
        $dt = self::dateTimeFromPacked($ts);
        if ($dt === false) {
            return (int) $ts;
        }

        $delta = (int) $seconds;
        if ($delta >= 0) {
            $dt->modify('+' . $delta . ' seconds');
        } else {
            $dt->modify((string) $delta . ' seconds');
        }

        return (int) $dt->format('YmdHis');
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
        $da = self::dateTimeFromPacked($a);
        $db = self::dateTimeFromPacked($b);
        if ($da === false || $db === false) {
            return 0;
        }

        // Order matches legacy gmmktime(a) - gmmktime(b): seconds from $b to $a.
        $iv = $db->diff($da);
        $days = $iv->days;
        if ($days === false) {
            return (int) $da->getTimestamp() - (int) $db->getTimestamp();
        }

        $sec = ($days * 86400) + ($iv->h * 3600) + ($iv->i * 60) + $iv->s;

        return $iv->invert ? -$sec : $sec;
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
        try {
            $dt = new \DateTime(trim($str), new \DateTimeZone('UTC'));
        } catch (\Exception $e) {
            return 0;
        }

        return (int) $dt->format('YmdHis');
    }

    /**
     * Convert BIGINT(14) YmdHis to ISO8601 UTC string (YYYY-MM-DDTHH:MM:SSZ).
     */
    public static function convert_bigint_to_iso8601(int $ts): string
    {
        $c = self::explode($ts);
        return sprintf(
            '%04d-%02d-%02dT%02d:%02d:%02dZ',
            $c['year'], $c['month'], $c['day'],
            $c['hour'], $c['minute'], $c['second']
        );
    }

    /**
     * Convert ISO8601 string to BIGINT(14) YmdHis (UTC).
     */
    public static function convert_iso8601_to_bigint(string $iso): int
    {
        try {
            $dt = new \DateTime($iso, new \DateTimeZone('UTC'));
        } catch (\Exception $e) {
            return 0;
        }

        return (int) $dt->format('YmdHis');
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
