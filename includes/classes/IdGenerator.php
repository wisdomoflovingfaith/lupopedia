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
 * @version 4.0.96
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

        // Registry archetype check: ensure the target table participates in the
        // full trust ladder before promotion. Skip if TrustLadderRegistry is not
        // loaded (graceful degradation for callers that pre-date the registry).
        // TODO(LEGACY-GAP): Once TrustLadderRegistry is universally deployed,
        //   make this a hard check (remove the class_exists guard).
        if (class_exists('TrustLadderRegistry', false)) {
            $tablePrefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
            $fullTable   = $tablePrefix . $table;
            try {
                $participates = TrustLadderRegistry::getParticipates($fullTable);
                if ($participates !== 'full') {
                    throw new RuntimeException(
                        "toCanonicalIdSafe: table '{$fullTable}' has participates='{$participates}'. "
                        . "Only 'full' ladder tables support canonical promotion via toCanonicalIdSafe(). "
                        . 'TODO(LEGACY-GAP): Verify archetype in TRUST_LADDER_REGISTRY.md.'
                    );
                }
            } catch (TrustLadderException $e) {
                // Registry lookup failed (table not registered). Fail closed in production.
                throw new RuntimeException(
                    "toCanonicalIdSafe: cannot promote — registry check failed for '{$fullTable}': "
                    . $e->getMessage()
                );
            }
        }
        // $tablePrefix / $fullTable may already be set by the registry block above; avoid recomputing.
        if (!isset($tablePrefix)) {
            $tablePrefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        }
        if (!isset($fullTable)) {
            $fullTable = $tablePrefix . $table;
        }

        $currentId = self::toCanonicalId($stagingId);

        // Backoff constants (per LEGACY_GAP_ANSWERS.md Q1):
        //   BASE  = 50 ms  → grows exponentially with each collision
        //   CAP   = 5000 ms → ceiling prevents indefinite stall under saturation
        //   JITTER = 0–100 ms → randomised per-attempt to spread concurrent retriers
        // Delay formula: min(BASE_US * 2^(attempt-1), CAP_US) + rand(0, JITTER_US)
        // Applied only on retry (attempt > 0); first attempt is always immediate.
        $backoffBaseUs   = 50000;    // 50 ms in microseconds
        $backoffCapUs    = 5000000;  // 5000 ms ceiling
        $backoffJitterUs = 100000;   // 100 ms jitter ceiling

        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            if ($attempt > 0) {
                // Exponential delay: 50ms, 100ms, 200ms, 400ms … capped at 5000ms.
                $expUs    = (int) min($backoffBaseUs * (2 ** ($attempt - 1)), $backoffCapUs);
                $jitterUs = mt_rand(0, $backoffJitterUs);
                usleep($expUs + $jitterUs);
            }

            // H-01: SELECT FOR UPDATE locking to prevent TOCTOU race condition
            // Start transaction and lock the target row to prevent concurrent insertion
            $db->beginTransaction();
            try {
                $sql = 'SELECT 1 AS x FROM ' . $db->quoteIdentifier($fullTable)
                    . ' WHERE ' . $db->quoteIdentifier($pkColumn) . ' = :id LIMIT 1 FOR UPDATE';
                $row = $db->fetchRow($sql, array('id' => $currentId));
                if ($row === null || $row === false) {
                    self::validateTrustLadderPk($currentId, $table . '.' . $pkColumn, true);
                    
                    // Commit transaction and return the safe ID
                    $db->commit();
                    return (string) $currentId;
                }
                // Row exists - release lock and continue to next attempt
                $db->commit();
            } catch (Exception $e) {
                // Rollback on any error to release the lock
                $db->rollBack();
                throw $e;
            }

            $idStr = (string) $currentId;
            if (strlen($idStr) !== 18) {
                break;
            }
            $prefix14  = substr($idStr, 0, 14);
            $suffix    = ((int) substr($idStr, 14, 4) + 1) % 10000;
            $currentId = $prefix14 . str_pad((string) $suffix, 4, '0', STR_PAD_LEFT);
        }

        // Log suffix exhaustion before throwing (per LEGACY_GAP_ANSWERS.md Q2).
        // Best-effort: a logging failure must never suppress the original exception.
        self::logSuffixExhaustion($db, $stagingId, $fullTable, $pkColumn, $maxRetries);

        throw new RuntimeException(
            'Unable to generate unique canonical ID after ' . (int) $maxRetries
            . ' attempts for staging ID ' . (string) $stagingId . ' in table ' . $fullTable
        );
    }

    /**
     * Log a suffix-exhaustion event to lupo_unified_log (critical trust-ladder alert).
     * Falls back to error_log() if the DB table is absent or the write fails.
     * Never throws — caller must not be interrupted by a logging failure.
     *
     * @param object     $db        PDO_DB connection (already open)
     * @param int|string $stagingId Original staging ID passed to toCanonicalIdSafe()
     * @param string     $fullTable Fully-prefixed table name
     * @param string     $pkColumn  PK column name
     * @param int        $maxRetries Number of attempts that were exhausted
     */
    private static function logSuffixExhaustion($db, $stagingId, $fullTable, $pkColumn, $maxRetries)
    {
        $stagingStr     = (string) $stagingId;
        $timestampPrefix = strlen($stagingStr) >= 14 ? substr($stagingStr, 0, 14) : $stagingStr;

        $actorId = 0;
        if (isset($_SESSION['actor_id']) && is_numeric($_SESSION['actor_id'])) {
            $actorId = (int) $_SESSION['actor_id'];
        }

        $context = json_encode(array(
            'staging_id'       => $stagingStr,
            'table'            => $fullTable,
            'pk_column'        => $pkColumn,
            'timestamp_prefix' => $timestampPrefix,
            'max_retries'      => (int) $maxRetries,
        ));

        $message = 'Canonical ID suffix exhaustion: all ' . (int) $maxRetries
            . ' suffix slots tried for prefix ' . $timestampPrefix
            . ' in ' . $fullTable . '.' . $pkColumn;

        if (!class_exists('timestamp_ymdhis', false)) {
            $tsFile = __DIR__ . '/TimestampYmdhis.php';
            if (is_file($tsFile)) {
                require_once $tsFile;
            }
        }
        $now = class_exists('timestamp_ymdhis', false)
            ? (int) timestamp_ymdhis::now()
            : (int) gmdate('YmdHis');

        try {
            $tablePrefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
            $logTable    = $db->quoteIdentifier($tablePrefix . 'unified_log');
            $sql = 'INSERT INTO ' . $logTable
                . ' (actor_id, log_type, log_level, log_message, log_context, created_ymdhis)'
                . ' VALUES (:actor_id, :log_type, :log_level, :log_message, :log_context, :created_ymdhis)';
            $db->execute($sql, array(
                'actor_id'       => $actorId,
                'log_type'       => 'trust_ladder',
                'log_level'      => 'critical',
                'log_message'    => 'Canonical ID suffix exhaustion',
                'log_context'    => $context,
                'created_ymdhis' => $now,
            ));
        } catch (\Throwable $e) {
            // Table absent, method missing, or write failed — fall back to PHP error_log().
            error_log('[LUPOPEDIA trust_ladder critical] ' . $message . ' | context=' . $context);
        }
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
     * True if $digitStr compares less than $boundStr as non-negative integers.
     *
     * @param string $digitStr
     * @param string $boundStr
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
     * Check if id is in seed/reserved space (0–999,999 inclusive).
     *
     * @param int|string $id
     * @return bool
     */
    public static function isReservedSpace($id)
    {
        $idStr = trim((string) $id);
        if ($idStr === '' || !ctype_digit($idStr)) {
            return false;
        }
        if (!self::numericStringLessThan($idStr, '1000000')) {
            return false;
        }

        return true;
    }

    /**
     * Check if id/context pair is an authorized seed entry.
     *
     * Current sources:
     * - database/lupopedia/actors/registry.json for actor_id seeds
     * - docs/doctrine/TRUST_LADDER_REGISTRY.md (literal mention fallback)
     *
     * @param string $idStr
     * @param string|null $context
     * @return bool
     */
    private static function isRegisteredSeed($idStr, $context)
    {
        $ctx = strtolower((string) $context);

        // Actor seeds are registry-backed and deterministic.
        if ($ctx !== '' && (strpos($ctx, 'actors.actor_id') !== false || strpos($ctx, 'lupo_actors.actor_id') !== false)) {
            $registryPath = dirname(__DIR__, 2) . '/database/lupopedia/actors/registry.json';
            if (is_file($registryPath)) {
                $json = @file_get_contents($registryPath);
                if ($json !== false) {
                    $data = json_decode($json, true);
                    if (is_array($data) && isset($data['actors']) && is_array($data['actors'])) {
                        foreach ($data['actors'] as $actor) {
                            if (is_array($actor) && isset($actor['actor_id']) && (string) $actor['actor_id'] === $idStr) {
                                return true;
                            }
                        }
                    }
                }
            }
        }

        // Fallback: literal registration mention in trust ladder registry doctrine.
        $docPath = dirname(__DIR__, 2) . '/docs/doctrine/TRUST_LADDER_REGISTRY.md';
        if (is_file($docPath)) {
            $doc = @file_get_contents($docPath);
            if ($doc !== false) {
                if ($ctx !== '' && strpos($doc, $ctx) !== false && strpos($doc, $idStr) !== false) {
                    return true;
                }
                if (strpos($doc, '|' . $idStr . '|') !== false || strpos($doc, '`' . $idStr . '`') !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Validate PK shape for Chronological Trust Ladder: reserved-space seed gating, or
     * 18-digit ids whose first four digits are a calendar year in 1000–9999 and remainder is numeric.
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

        // RULE 1: Seed/reserved space (0-999,999) requires explicit registry authorization.
        if (self::isReservedSpace($idStr)) {
            if (self::isRegisteredSeed($idStr, $context)) {
                return true;
            }

            if ($throw) {
                throw new InvalidArgumentException(
                    'ID ' . $idStr . ' is in seed range (0-999,999) but not registered in TRUST_LADDER_REGISTRY.md'
                    . ($context !== null && $context !== '' ? ' for ' . $context : '')
                );
            }
            return false;
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
