<?php
/**
 * Lupopedia ID Generator
 *
 * Generates 63-bit signed-safe BIGINT IDs in the format:
 * YYYYMMDDHHIISS + 4-digit random suffix
 *
 * All primary keys MUST be generated using this class.
 * No AUTO_INCREMENT, sequences, or database-side ID generation.
 *
 * @package Lupopedia
 * @version 4.0.94
 */

class IdGenerator
{
    /**
     * Generate a 63-bit signed-safe unique ID
     *
     * Format: YYYYMMDDHHIISS + 4-digit random suffix
     * Example: 202603301022000001
     *
     * @return string 18-digit BIGINT-safe ID
     */
    public static function generate()
    {
        if (!class_exists('timestamp_ymdhis', false)) {
            require_once __DIR__ . '/TimestampYmdhis.php';
        }
        // UTC packed clock via doctrine API (arithmetic elsewhere uses DateTime, not epoch)
        $timestamp = (string) timestamp_ymdhis::now();

        // 4-digit suffix (0000–9999): prefer CSPRNG bytes; last resort mt_rand if OpenSSL unavailable
        $suffixNum = null;
        if (function_exists('openssl_random_pseudo_bytes')) {
            $raw = openssl_random_pseudo_bytes(2);
            if ($raw !== false && strlen($raw) === 2) {
                $suffixNum = ((ord($raw[0]) << 8) | ord($raw[1])) % 10000;
            }
        }
        if ($suffixNum === null) {
            $suffixNum = mt_rand(0, 9999);
        }
        $suffix = str_pad((string) $suffixNum, 4, '0', STR_PAD_LEFT);

        return $timestamp . $suffix;
    }

    /**
     * Validate ID format
     *
     * @param string $id
     * @return bool
     */
    public static function validateFormat($id)
    {
        // Must be exactly 18 digits
        if (!preg_match('/^\d{18}$/', $id)) {
            return false;
        }

        // Extract timestamp portion
        $ts = substr($id, 0, 14);

        // Validate timestamp range (2000–2099)
        if ($ts < '20000101000000' || $ts > '20991231235959') {
            return false;
        }

        return true;
    }

    /**
     * Extract timestamp (YYYYMMDDHHIISS)
     *
     * @param string $id
     * @return string
     */
    public static function extractTimestamp($id)
    {
        return substr($id, 0, 14);
    }

    /**
     * Extract random suffix (0000–9999)
     *
     * @param string $id
     * @return string
     */
    public static function extractSuffix($id)
    {
        return substr($id, 14, 4);
    }

    /**
     * Subtract 1000 from embedded calendar year when year >= 2000 (Chronological Trust Ladder).
     * Short numeric ids (e.g. install seeds) are returned unchanged as strings.
     *
     * @param int|string $stagingId
     * @return string
     */
    public static function toCanonicalId($stagingId)
    {
        $idStr = (string) $stagingId;
        if (strlen($idStr) < 4) {
            return $idStr;
        }
        $year = (int) substr($idStr, 0, 4);
        if ($year >= 2000) {
            $newYear = $year - 1000;

            return (string) ($newYear . substr($idStr, 4));
        }

        return $idStr;
    }

    /**
     * Promote staging id to canonical, ensuring PK is not already used in the table.
     * On collision, increments the last 4 digits (suffix) modulo 10000 up to $maxRetries.
     *
     * @param int|string $stagingId
     * @param string $table Short table name without prefix (e.g. memory_nodes)
     * @param string $pkColumn Primary key column name
     * @param int $maxRetries
     * @return string 18-digit canonical id as string (safe for 32-bit PHP)
     */
    public static function toCanonicalIdSafe($stagingId, $table, $pkColumn = 'memory_node_id', $maxRetries = 10)
    {
        if (!class_exists('DatabaseFactory', false)) {
            require_once __DIR__ . '/DatabaseFactory.php';
        }
        $db = DatabaseFactory::getConnection();
        $tablePrefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $fullTable = $tablePrefix . $table;

        $currentId = self::toCanonicalId($stagingId);

        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            $sql = 'SELECT 1 AS x FROM ' . $db->quoteIdentifier($fullTable)
                . ' WHERE ' . $db->quoteIdentifier($pkColumn) . ' = :id LIMIT 1';
            $row = $db->fetchRow($sql, array('id' => $currentId));
            if ($row === null || $row === false) {
                self::validateTrustLadderPk($currentId, $table . '.' . $pkColumn, true);

                return (string) $currentId;
            }

            $idStr = (string) $currentId;
            if (strlen($idStr) !== 18) {
                break;
            }
            $prefix14 = substr($idStr, 0, 14);
            $suffix = (int) substr($idStr, 14, 4);
            $suffix = ($suffix + 1) % 10000;
            $currentId = $prefix14 . str_pad((string) $suffix, 4, '0', STR_PAD_LEFT);
        }

        throw new RuntimeException(
            'Unable to generate unique canonical ID after ' . (int) $maxRetries
            . ' attempts for staging ID ' . (string) $stagingId . ' in table ' . $fullTable
        );
    }

    /**
     * Convert a low install seed actor id to a deterministic living-canonical 18-digit id (PRD 41).
     * Format: 100000000000000000 + seed (embedded year 1000 band).
     *
     * @param int|string $seedId Install seed id (e.g. 1, 19, 116)
     * @return string 18-digit id
     */
    public static function seedActorToCanonicalId($seedId)
    {
        $s = trim((string) $seedId);
        if ($s === '' || !preg_match('/^[0-9]+$/', $s)) {
            throw new InvalidArgumentException('seedActorToCanonicalId: seed id must be a non-negative decimal integer');
        }
        if ((string) (int) $s !== $s) {
            throw new InvalidArgumentException('seedActorToCanonicalId: use normalized decimal without leading zeros');
        }
        $seedInt = (int) $s;
        if ($seedInt < 0) {
            throw new InvalidArgumentException('seedActorToCanonicalId: seed id must be non-negative');
        }

        if (function_exists('bcadd')) {
            return bcadd('100000000000000000', (string) $seedInt, 0);
        }
        if (PHP_INT_SIZE === 8) {
            return (string) (100000000000000000 + $seedInt);
        }

        return self::addSmallNonNegativeIntToDecimalString('100000000000000000', $seedInt);
    }

    /**
     * Add a small non-negative integer to an 18-digit decimal string (32-bit PHP without bcmath).
     *
     * @param string $dec18
     * @param int $add
     * @return string
     */
    private static function addSmallNonNegativeIntToDecimalString($dec18, $add)
    {
        $add = (int) $add;
        if ($add < 0) {
            throw new InvalidArgumentException('addSmallNonNegativeIntToDecimalString: add must be non-negative');
        }
        $chars = str_split($dec18);
        $len = count($chars);
        if ($len !== 18) {
            throw new InvalidArgumentException('addSmallNonNegativeIntToDecimalString: expected 18-digit base');
        }
        $carry = $add;
        for ($i = $len - 1; $i >= 0; $i--) {
            $sum = (int) $chars[$i] + $carry;
            $chars[$i] = (string) ($sum % 10);
            $carry = (int) floor($sum / 10);
        }
        if ($carry !== 0) {
            throw new InvalidArgumentException('seedActorToCanonicalId: overflow');
        }

        return implode('', $chars);
    }

    /**
     * True if $digitStr compares less than $boundStr as non-negative integers (no (int) cast of long ids).
     *
     * @param string $digitStr
     * @param string $boundStr e.g. '2026'
     * @return bool
     */
    private static function numericStringLessThan($digitStr, $boundStr)
    {
        $pad = max(strlen($digitStr), strlen($boundStr));
        $a = str_pad($digitStr, $pad, '0', STR_PAD_LEFT);
        $b = str_pad($boundStr, $pad, '0', STR_PAD_LEFT);

        return strcmp($a, $b) < 0;
    }

    /**
     * Validate PK shape for Chronological Trust Ladder: install-seed exempt band, or 18-digit
     * ids whose first four digits are a calendar year in 1000–9999 and remainder is numeric.
     *
     * Does not replace validateFormat() — that remains for raw IdGenerator::generate() output (2000–2099 clock).
     *
     * @param int|string $id
     * @param string|null $context
     * @param bool $throw If true, throws InvalidArgumentException on failure
     * @return bool
     */
    public static function validateTrustLadderPk($id, $context = null, $throw = false)
    {
        $idStr = (string) $id;
        $len = strlen($idStr);

        // Seed band: short ids, numeric < 2026 — compare as padded digit strings (avoid (int) overflow on 32-bit)
        if ($len < 18 && $len > 0 && ctype_digit($idStr) && self::numericStringLessThan($idStr, '2026')) {
            return true;
        }

        if ($len !== 18) {
            if ($throw) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Trust ladder PK must be 18 digits (or short seed id), got length %d%s',
                        $len,
                        $context !== null && $context !== '' ? ' for ' . $context : ''
                    )
                );
            }

            return false;
        }

        if (!ctype_digit($idStr)) {
            if ($throw) {
                throw new InvalidArgumentException(
                    'Trust ladder PK must be numeric' . ($context !== null && $context !== '' ? ' for ' . $context : '')
                );
            }

            return false;
        }

        $year = (int) substr($idStr, 0, 4);
        if ($year < 1000 || $year > 9999) {
            if ($throw) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Trust ladder PK embedded year %d is out of range (1000-9999)%s',
                        $year,
                        $context !== null && $context !== '' ? ' for ' . $context : ''
                    )
                );
            }

            return false;
        }

        $rest = substr($idStr, 4);
        if (strlen($rest) !== 14 || !ctype_digit($rest)) {
            if ($throw) {
                throw new InvalidArgumentException(
                    'Trust ladder PK suffix must be 14 digits' . ($context !== null && $context !== '' ? ' for ' . $context : '')
                );
            }

            return false;
        }

        return true;
    }
}
