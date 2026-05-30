#!/usr/bin/env php
<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.0.99"
#   lupopedia.schema: implementation
#   when_updated: "20260412162124"
#   file_path_from_root: "lupo-scripts/validate_trust_ladder_pk.php"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/validate_trust_ladder_pk.php"
#   last_modified_utc: "20260412162124"
#   federation_node_id: 0
#   channel_key: "development"
#   trust_tier: "canonical"
#   memory_key: "lupo-memory/development/canonical/1026/04/validate-trust-ladder-pk.toon"
#   artifact_type: implementation
#   artifact_kind: tool
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   title: "Trust Ladder PK Validator"
#   status: "complete"
#   parent_pk_id: "43"
#   summary: "Validates 18-digit PK year segments on trust-ladder tables (CLI complement to IdGenerator)."
#   module: null
#   dialog_transcript: "0/development/validate-trust-ladder-pk"
# ---------------------------------------------------------------------
/**
 * validate_trust_ladder_pk.php
 *
 * Validates 18-digit PK year segments on trust-ladder tables against a calendar year.
 * Complements IdGenerator::validateTrustLadderPk() (shape + seed registry).
 *
 * Usage (repo root):
 *   php lupo-scripts/validate_trust_ladder_pk.php [--calendar-year=YYYY] [--no-db]
 *
 * Exit 0 = no errors; 1 = errors (CI).
 */

$repoRoot = dirname(__DIR__);
require_once $repoRoot . '/lupo-includes/classes/IdGenerator.php';

/**
 * @param int|string $pk
 * @param string     $trustTier canonical|staging|seed_only
 * @param int        $calendarYear
 *
 * @return array array(bool ok, string message)
 */
function validateTrustLadderPkYearTier($pk, $trustTier, $calendarYear)
{
    $pkStr = (string) $pk;
    $tier   = (string) $trustTier;
    if ($tier === 'seed_only') {
        if (IdGenerator::validateTrustLadderPk($pkStr, 'cli.seed', false)) {
            return array(true, 'ok');
        }

        return array(false, 'seed_only PK failed IdGenerator::validateTrustLadderPk: ' . $pkStr);
    }
    $len = strlen($pkStr);
    if ($len !== 18 || !ctype_digit($pkStr)) {
        return array(false, 'PK must be 18 digits for tier ' . $tier . ': ' . $pkStr);
    }
    $pkYear = (int) substr($pkStr, 0, 4);
    if ($tier === 'canonical') {
        $expected = (int) $calendarYear - 1000;
        if ($pkYear !== $expected) {
            return array(
                false,
                'canonical PK ' . $pkStr . ' has year segment ' . $pkYear . ', expected ' . $expected
                . ' (calendar ' . $calendarYear . ' - 1000). Hint: Did you forget to subtract 1000? See PRD 38 §8.1.',
            );
        }

        return array(true, 'ok');
    }
    if ($tier === 'staging') {
        if ($pkYear !== (int) $calendarYear) {
            return array(
                false,
                'staging PK ' . $pkStr . ' has year ' . $pkYear . ', expected ' . $calendarYear,
            );
        }

        return array(true, 'ok');
    }

    return array(false, 'unknown trust tier: ' . $tier);
}

/**
 * Infer trust tier from PK shape for memory / generator tables (no trust_tier column on nodes).
 *
 * @param string $pkStr
 *
 * @return string canonical|staging|seed_only|unknown
 */
function inferTrustTierFromPk($pkStr)
{
    $pkStr = (string) $pkStr;
    $len   = strlen($pkStr);
    if ($len < 18 || !ctype_digit($pkStr)) {
        if ($pkStr !== '' && ctype_digit($pkStr) && (int) $pkStr <= 999999) {
            return 'seed_only';
        }

        return 'unknown';
    }
    $y = (int) substr($pkStr, 0, 4);
    if ($y >= 2000 && $y <= 2999) {
        return 'staging';
    }
    if ($y >= 1000 && $y <= 1999) {
        return 'canonical';
    }

    return 'unknown';
}

$cal = (int) gmdate('Y');
$useDb = true;
foreach (array_slice($argv, 1) as $arg) {
    if ($arg === '--no-db') {
        $useDb = false;
    } elseif (strpos($arg, '--calendar-year=') === 0) {
        $cal = (int) substr($arg, strlen('--calendar-year='));
    }
}

$st = validateTrustLadderPkYearTier(
    (string) ((int) $cal - 1000) . '04081200001234',
    'canonical',
    $cal
);
if (!$st[0]) {
    fwrite(STDERR, "Self-test failed (canonical): " . $st[1] . "\n");
    exit(1);
}
$st2 = validateTrustLadderPkYearTier(
    (string) $cal . '04081200001234',
    'staging',
    $cal
);
if (!$st2[0]) {
    fwrite(STDERR, "Self-test failed (staging): " . $st2[1] . "\n");
    exit(1);
}

$errors = array();

if ($useDb) {
    $cfg = $repoRoot . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
    if (!is_file($cfg)) {
        fwrite(STDERR, "[WARN] lupopedia-config.php missing — skipping DB scan (use --no-db to silence).\n");
    } else {
        if (!defined('ABSPATH')) {
            define('ABSPATH', $repoRoot . DIRECTORY_SEPARATOR);
        }
        if (!defined('LUPOPEDIA_PATH')) {
            define('LUPOPEDIA_PATH', $repoRoot . DIRECTORY_SEPARATOR);
        }
        require_once $cfg;
        require_once $repoRoot . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'bootstrap.php';
        if (!isset($GLOBALS['mydatabase'])) {
            fwrite(STDERR, "[WARN] Database not available after bootstrap — skipping DB scan.\n");
        } else {
            /** @var object $db */
            $db           = $GLOBALS['mydatabase'];
            $tablePrefix  = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
            $scan         = array(
                $tablePrefix . 'memory_nodes'     => 'memory_node_id',
                $tablePrefix . 'memory_edges'     => 'memory_edge_id',
                $tablePrefix . 'dialog_messages'  => 'dialog_message_id',
                $tablePrefix . 'edges'            => 'edge_id',
            );
            foreach ($scan as $table => $col) {
                $sql = 'SELECT ' . $db->quoteIdentifier($col) . ' AS pk FROM ' . $db->quoteIdentifier($table) . ' WHERE is_deleted = 0 LIMIT 5000';
                try {
                    $rows = $db->fetchAll($sql, array());
                } catch (Exception $e) {
                    fwrite(STDERR, "[WARN] Skip table " . $table . ': ' . $e->getMessage() . "\n");
                    continue;
                }
                foreach ($rows as $row) {
                    if (!isset($row['pk'])) {
                        continue;
                    }
                    $pkStr = (string) $row['pk'];
                    $tier  = inferTrustTierFromPk($pkStr);
                    if ($tier === 'unknown') {
                        continue;
                    }
                    if ($tier === 'seed_only') {
                        if (!IdGenerator::validateTrustLadderPk($pkStr, $table . '.' . $col, false)) {
                            $errors[] = $table . '.' . $col . ' seed PK ' . $pkStr . ' failed validateTrustLadderPk';
                        }
                        continue;
                    }
                    if ($table === $tablePrefix . 'dialog_messages' || $table === $tablePrefix . 'edges') {
                        $tier = 'staging';
                    }
                    $chk = validateTrustLadderPkYearTier($pkStr, $tier, $cal);
                    if (!$chk[0]) {
                        $errors[] = $table . '.' . $col . ': ' . $chk[1];
                    }
                }
            }
        }
    }
}

if (!empty($errors)) {
    fwrite(STDERR, implode("\n", $errors) . "\n");
    exit(1);
}

echo "validate_trust_ladder_pk: OK (calendar year " . $cal . ")\n";
exit(0);
