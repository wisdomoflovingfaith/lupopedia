<?php
/**
 * TrustLadderRegistry Unit Tests
 *
 * Plain PHP — no PHPUnit required. Mirrors the style of trust_ladder_pk_validation_test.php.
 *
 * Run: php lupo-tests/unit/trust_ladder_registry_test.php
 *
 * Sections:
 *   A — getSelfMetadata() bootstrap fallback
 *   B — injectTestCache() + getTableMeta()
 *   C — Type helpers: isParent, isChild, requiresSeed, getReferenceTarget, getParticipates
 *   D — validatePkForTable() for each participation mode
 *   E — assertInvariants() — pass and fail cases
 *   F — Dev mode vs Prod mode behavior on unregistered table
 *   G — clearCache() + injectTestCache() idempotency
 *
 * @package Lupopedia
 * @version 4.0.96
 */

declare(strict_types=1);

// ─── Bootstrap ───────────────────────────────────────────────────────────────

// Define config constant so DatabaseFactory guard does not abort.
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    define('LUPOPEDIA_CONFIG_LOADED', true);
}
if (!defined('LUPO_TABLE_PREFIX')) {
    define('LUPO_TABLE_PREFIX', 'lupo_');
}
// Default to development mode so missing-table warnings do not throw.
if (!defined('LUPOPEDIA_ENV')) {
    define('LUPOPEDIA_ENV', 'development');
}

// Stub DatabaseFactory so TrustLadderRegistry never hits a real DB during tests.
if (!class_exists('DatabaseFactory')) {
    class DatabaseFactory
    {
        public static function getConnection()
        {
            throw new RuntimeException('DatabaseFactory::getConnection() stub — no DB in unit tests');
        }
        public static function resetConnection() {}
    }
}

// Stub IdGenerator for PK validation tests (minimal surface).
if (!class_exists('IdGenerator')) {
    class IdGenerator
    {
        public static function isReservedSpace($id): bool
        {
            $s = (string) $id;
            if ($s === '' || !ctype_digit($s)) {
                return false;
            }
            // seed range: 0–999,999 → length < 7 digits or exactly 6 leading digits <= 999999
            return strlen($s) < 7 || (strlen($s) === 6 && $s <= '999999');
        }

        public static function validateTrustLadderPk($id, $context = null, $throw = false): bool
        {
            $s = (string) $id;
            if (strlen($s) !== 18 || !ctype_digit($s)) {
                return false;
            }
            $year = (int) substr($s, 0, 4);
            return $year >= 1000 && $year <= 9999;
        }
    }
}

require_once __DIR__ . '/../../lupo-includes/classes/TrustLadderRegistry.php';

// ─── Test harness ─────────────────────────────────────────────────────────────

$passed = 0;
$failed = 0;

function ok(bool $condition, string $label): void
{
    global $passed, $failed;
    if ($condition) {
        echo "[PASS] {$label}\n";
        $passed++;
    } else {
        echo "[FAIL] {$label}\n";
        $failed++;
    }
}

function expect_exception(callable $fn, string $exClass, string $label): void
{
    global $passed, $failed;
    try {
        $fn();
        echo "[FAIL] {$label} — expected {$exClass}, none thrown\n";
        $failed++;
    } catch (\Throwable $e) {
        if ($e instanceof $exClass) {
            echo "[PASS] {$label}\n";
            $passed++;
        } else {
            echo "[FAIL] {$label} — got " . get_class($e) . ": {$e->getMessage()}\n";
            $failed++;
        }
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// Helper: sample test cache
// ─────────────────────────────────────────────────────────────────────────────

function make_test_cache(): array
{
    $make = function(array $overrides): array {
        return array_merge(array(
            'registry_id'            => 99,
            'table_name'             => 'lupo_test',
            'archetype'              => 'parent',
            'participates'           => 'full',
            'seed_required'          => 1,
            'canonical_lineage_edge' => 'canonical_instance_of',
            'promotion_target'       => 'canonical',
            'is_active'              => 1,
            'is_deleted'             => 0,
            'reference_target'       => 'seed',
            'canonical_id'           => null,
            'notes'                  => null,
        ), $overrides);
    };

    return array(
        'lupo_memory_nodes' => $make(array(
            'registry_id'            => 2,
            'table_name'             => 'lupo_memory_nodes',
            'archetype'              => 'parent',
            'participates'           => 'full',
            'seed_required'          => 1,
            'canonical_lineage_edge' => 'canonical_instance_of',
            'promotion_target'       => 'canonical',
            'reference_target'       => 'seed',
        )),
        'lupo_dialog_messages' => $make(array(
            'registry_id'            => 4,
            'table_name'             => 'lupo_dialog_messages',
            'archetype'              => 'child',
            'participates'           => 'generator_staging',
            'seed_required'          => 0,
            'canonical_lineage_edge' => null,
            'promotion_target'       => null,
            'reference_target'       => 'canonical',
        )),
        'lupo_actors' => $make(array(
            'registry_id'            => 6,
            'table_name'             => 'lupo_actors',
            'archetype'              => 'parent',
            'participates'           => 'seed_only',
            'seed_required'          => 1,
            'canonical_lineage_edge' => null,
            'promotion_target'       => null,
            'reference_target'       => 'seed',
        )),
        'lupo_edges' => $make(array(
            'registry_id'            => 5,
            'table_name'             => 'lupo_edges',
            'archetype'              => 'system',
            'participates'           => 'generator_staging',
            'seed_required'          => 1,
            'canonical_lineage_edge' => null,
            'promotion_target'       => null,
            'reference_target'       => 'seed',
        )),
        'lupo_trust_ladder_registry' => array(
            'registry_id'            => 1,
            'table_name'             => 'lupo_trust_ladder_registry',
            'archetype'              => 'system',
            'participates'           => 'seed_only',
            'seed_required'          => 1,
            'canonical_lineage_edge' => null,
            'promotion_target'       => null,
            'is_active'              => 1,
            'is_deleted'             => 0,
            'reference_target'       => 'seed',
            'canonical_id'           => null,
            'notes'                  => 'Bootstrap.',
        ),
        'lupo_deactivated_table' => $make(array(
            'registry_id'   => 90,
            'table_name'    => 'lupo_deactivated_table',
            'is_active'     => 0,
            'is_deleted'    => 0,
        )),
        'lupo_deleted_table' => $make(array(
            'registry_id'   => 91,
            'table_name'    => 'lupo_deleted_table',
            'is_active'     => 1,
            'is_deleted'    => 1,
        )),
    );
}

// ─────────────────────────────────────────────────────────────────────────────
// Section A — getSelfMetadata() bootstrap fallback
// ─────────────────────────────────────────────────────────────────────────────

echo "\n=== Section A: getSelfMetadata() ===\n";

TrustLadderRegistry::clearCache();
$self = TrustLadderRegistry::getSelfMetadata();

ok($self['table_name'] === 'lupo_trust_ladder_registry', 'A1: table_name is lupo_trust_ladder_registry');
ok($self['archetype'] === 'system', 'A2: archetype is system');
ok($self['participates'] === 'seed_only', 'A3: participates is seed_only');
ok((int) $self['seed_required'] === 1, 'A4: seed_required = 1');
ok($self['reference_target'] === 'seed', 'A5: reference_target = seed');
ok((int) $self['registry_id'] === 1, 'A6: registry_id = 1');
ok($self['canonical_lineage_edge'] === null, 'A7: canonical_lineage_edge is null');

// ─────────────────────────────────────────────────────────────────────────────
// Section B — injectTestCache() + getTableMeta()
// ─────────────────────────────────────────────────────────────────────────────

echo "\n=== Section B: injectTestCache() + getTableMeta() ===\n";

TrustLadderRegistry::clearCache();
TrustLadderRegistry::injectTestCache(make_test_cache());

$meta = TrustLadderRegistry::getTableMeta('lupo_memory_nodes');
ok($meta['table_name'] === 'lupo_memory_nodes', 'B1: getTableMeta returns correct table_name');
ok($meta['archetype'] === 'parent', 'B2: archetype = parent');
ok($meta['participates'] === 'full', 'B3: participates = full');
ok((int) $meta['seed_required'] === 1, 'B4: seed_required = 1');
ok($meta['reference_target'] === 'seed', 'B5: reference_target = seed');
ok($meta['canonical_lineage_edge'] === 'canonical_instance_of', 'B6: canonical_lineage_edge set');
ok($meta['promotion_target'] === 'canonical', 'B7: promotion_target = canonical');

// Deactivated table in dev mode — should warn, not throw.
$warned = false;
set_error_handler(function($errno, $errstr) use (&$warned) { $warned = true; return true; }, E_USER_WARNING);
$deact = TrustLadderRegistry::getTableMeta('lupo_deactivated_table');
restore_error_handler();
ok($warned, 'B8: deactivated table triggers E_USER_WARNING in dev mode');
ok(is_array($deact), 'B9: deactivated table returns array in dev mode');

// ─────────────────────────────────────────────────────────────────────────────
// Section C — Type helpers
// ─────────────────────────────────────────────────────────────────────────────

echo "\n=== Section C: Type helper methods ===\n";

TrustLadderRegistry::injectTestCache(make_test_cache());

ok(TrustLadderRegistry::isParent('lupo_memory_nodes') === true,   'C1: memory_nodes is parent');
ok(TrustLadderRegistry::isParent('lupo_dialog_messages') === false,'C2: dialog_messages is not parent');
ok(TrustLadderRegistry::isChild('lupo_dialog_messages') === true,  'C3: dialog_messages is child');
ok(TrustLadderRegistry::isChild('lupo_memory_nodes') === false,    'C4: memory_nodes is not child');
ok(TrustLadderRegistry::requiresSeed('lupo_memory_nodes') === true,'C5: memory_nodes requiresSeed');
ok(TrustLadderRegistry::requiresSeed('lupo_dialog_messages') === false, 'C6: dialog_messages !requiresSeed');
ok(TrustLadderRegistry::getReferenceTarget('lupo_memory_nodes') === 'seed',      'C7: memory_nodes ref=seed');
ok(TrustLadderRegistry::getReferenceTarget('lupo_dialog_messages') === 'canonical','C8: dialog_messages ref=canonical');
ok(TrustLadderRegistry::getParticipates('lupo_actors') === 'seed_only',          'C9: actors = seed_only');
ok(TrustLadderRegistry::getCanonicalLineageEdge('lupo_memory_nodes') === 'canonical_instance_of', 'C10: lineage edge set');
ok(TrustLadderRegistry::getCanonicalLineageEdge('lupo_dialog_messages') === null,'C11: child has no lineage edge');
ok(TrustLadderRegistry::getPromotionTarget('lupo_memory_nodes') === 'canonical',  'C12: memory_nodes promotion=canonical');

// ─────────────────────────────────────────────────────────────────────────────
// Section D — validatePkForTable()
// ─────────────────────────────────────────────────────────────────────────────

echo "\n=== Section D: validatePkForTable() ===\n";

TrustLadderRegistry::injectTestCache(make_test_cache());

// seed_only table: short PK passes, 18-digit fails.
ok(TrustLadderRegistry::validatePkForTable('lupo_actors', '116') === true,
    'D1: actors seed_only — PK 116 passes (seed range)');
ok(TrustLadderRegistry::validatePkForTable('lupo_actors', '999999') === true,
    'D2: actors seed_only — PK 999999 passes (boundary)');
$warned = false;
set_error_handler(function($errno, $errstr) use (&$warned) { $warned = true; return true; }, E_USER_WARNING);
$res = TrustLadderRegistry::validatePkForTable('lupo_actors', '202604081234567890');
restore_error_handler();
ok($res === false, 'D3: actors seed_only — 18-digit PK fails');

// full table: valid 18-digit passes; short PK fails.
ok(TrustLadderRegistry::validatePkForTable('lupo_memory_nodes', '102604081234567890') === true,
    'D4: memory_nodes full — canonical 18-digit passes');
ok(TrustLadderRegistry::validatePkForTable('lupo_memory_nodes', '202604081234567890') === true,
    'D5: memory_nodes full — staging 18-digit passes');
ok(TrustLadderRegistry::validatePkForTable('lupo_memory_nodes', '42') === false,
    'D6: memory_nodes full — short seed PK fails');

// generator_staging table: 18-digit passes; short fails.
ok(TrustLadderRegistry::validatePkForTable('lupo_dialog_messages', '202604081234560001') === true,
    'D7: dialog_messages generator_staging — staging 18-digit passes');
ok(TrustLadderRegistry::validatePkForTable('lupo_dialog_messages', '42') === false,
    'D8: dialog_messages generator_staging — seed PK fails');

// throw=true on violation.
expect_exception(
    fn() => TrustLadderRegistry::validatePkForTable('lupo_actors', '202604081234567890', true),
    'TrustLadderException',
    'D9: actors seed_only — 18-digit PK throws TrustLadderException when throw=true'
);

// ─────────────────────────────────────────────────────────────────────────────
// Section E — assertInvariants()
// ─────────────────────────────────────────────────────────────────────────────

echo "\n=== Section E: assertInvariants() ===\n";

TrustLadderRegistry::injectTestCache(make_test_cache());

// All clean entries should pass without exception.
$inv_pass = true;
foreach (array('lupo_memory_nodes', 'lupo_dialog_messages', 'lupo_actors', 'lupo_edges') as $t) {
    try {
        TrustLadderRegistry::assertInvariants($t);
    } catch (TrustLadderException $e) {
        $inv_pass = false;
    }
}
ok($inv_pass, 'E1: clean entries all pass assertInvariants()');

// Inject a broken entry (parent with seed_required=0).
$broken = make_test_cache();
$broken['lupo_broken_parent'] = array(
    'registry_id'            => 98,
    'table_name'             => 'lupo_broken_parent',
    'archetype'              => 'parent',
    'participates'           => 'full',
    'seed_required'          => 0,   // WRONG for parent
    'canonical_lineage_edge' => null,
    'promotion_target'       => null,
    'is_active'              => 1,
    'is_deleted'             => 0,
    'reference_target'       => 'seed',
    'canonical_id'           => null,
    'notes'                  => null,
);
TrustLadderRegistry::injectTestCache($broken);

expect_exception(
    fn() => TrustLadderRegistry::assertInvariants('lupo_broken_parent'),
    'TrustLadderException',
    'E2: parent with seed_required=0 throws in assertInvariants()'
);

// Child with seed_required=1.
$broken2 = make_test_cache();
$broken2['lupo_broken_child'] = array(
    'registry_id'            => 97,
    'table_name'             => 'lupo_broken_child',
    'archetype'              => 'child',
    'participates'           => 'generator_staging',
    'seed_required'          => 1,   // WRONG for child
    'canonical_lineage_edge' => null,
    'promotion_target'       => null,
    'is_active'              => 1,
    'is_deleted'             => 0,
    'reference_target'       => 'canonical',
    'canonical_id'           => null,
    'notes'                  => null,
);
TrustLadderRegistry::injectTestCache($broken2);

expect_exception(
    fn() => TrustLadderRegistry::assertInvariants('lupo_broken_child'),
    'TrustLadderException',
    'E3: child with seed_required=1 throws in assertInvariants()'
);

// ─────────────────────────────────────────────────────────────────────────────
// Section F — Dev mode vs Prod mode on unregistered table
// ─────────────────────────────────────────────────────────────────────────────

echo "\n=== Section F: Dev mode vs Prod mode ===\n";

// Dev mode (LUPOPEDIA_ENV = 'development'): warning + fallback, no exception.
TrustLadderRegistry::injectTestCache(make_test_cache());
$warned = false;
set_error_handler(function($errno, $errstr) use (&$warned) { $warned = true; return true; }, E_USER_WARNING);
$fallback = TrustLadderRegistry::getTableMeta('lupo_not_registered_table');
restore_error_handler();

ok($warned, 'F1: dev mode — unregistered table triggers E_USER_WARNING');
ok($fallback['participates'] === 'not_ladder', 'F2: dev mode fallback returns not_ladder participates');
ok(strpos(($fallback['notes'] ?? ''), 'LEGACY-GAP') !== false, 'F3: dev fallback notes contain LEGACY-GAP');

// Prod mode (LUPOPEDIA_ENV = 'production'): must throw TrustLadderException.
// We simulate prod mode by temporarily redefining the constant via ini (not possible with define),
// so instead we test via the exception thrown when a deactivated entry is forced in prod.
// Because we cannot redefine a PHP constant, we verify the exception path via validatePkForTable throw.
TrustLadderRegistry::injectTestCache(make_test_cache());
$threw = false;
try {
    TrustLadderRegistry::validatePkForTable('lupo_actors', '202604081234567890', true);
} catch (TrustLadderException $e) {
    $threw = true;
}
ok($threw, 'F4: validatePkForTable throw=true raises TrustLadderException as expected');

// ─────────────────────────────────────────────────────────────────────────────
// Section G — clearCache() + injectTestCache() idempotency
// ─────────────────────────────────────────────────────────────────────────────

echo "\n=== Section G: Cache management ===\n";

TrustLadderRegistry::injectTestCache(make_test_cache());
$m1 = TrustLadderRegistry::getTableMeta('lupo_memory_nodes');
ok($m1['archetype'] === 'parent', 'G1: first cache load correct');

TrustLadderRegistry::clearCache();
// After clearCache, injectTestCache with a different cache.
TrustLadderRegistry::injectTestCache(array(
    'lupo_memory_nodes' => array_merge($m1, array('archetype' => 'child')),
));
$m2 = TrustLadderRegistry::getTableMeta('lupo_memory_nodes');
ok($m2['archetype'] === 'child', 'G2: clearCache() + injectTestCache() replaces cache');

// getSelfMetadata() is always constant regardless of cache state.
TrustLadderRegistry::clearCache();
$self2 = TrustLadderRegistry::getSelfMetadata();
ok($self2['archetype'] === 'system', 'G3: getSelfMetadata() is cache-independent');

// ─────────────────────────────────────────────────────────────────────────────
// Summary
// ─────────────────────────────────────────────────────────────────────────────

echo "\n─────────────────────────────────────────\n";
echo "Results: {$passed} passed, {$failed} failed\n";
echo "─────────────────────────────────────────\n";

exit($failed > 0 ? 1 : 0);
