<?php
/**
 * channel66_bounded_authority_ingestion_p0_test.php
 *
 * Runs Channel 66 Thread 1001 bounded-authority P0 ingestion against fixture files.
 * Tests real DB projection when DB is available; otherwise SKIPs.
 */

function test_assert($cond, $msg)
{
    global $passed, $failed;
    if ($cond) {
        $passed++;
        echo "[PASS] " . $msg . "\n";
    } else {
        $failed++;
        echo "[FAIL] " . $msg . "\n";
    }
}

$passed = 0;
$failed = 0;

$repoRoot = dirname(dirname(__DIR__));
$config = $repoRoot . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
if (!is_file($config)) {
    echo "SKIP: lupopedia-config.php not found.\n";
    exit(0);
}
require_once $config;

$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
if (!$db) {
    echo "SKIP: No database connection (GLOBALS['mydatabase'] missing). Run with a seeded DB.\n";
    exit(0);
}

require_once $repoRoot . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Channel66HeaderIngester.php';
require_once $repoRoot . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'ToonSchemaCache.php';

$tablePrefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$metadataTable = $tablePrefix . 'metadata';

$fixtureDir = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-tests' . DIRECTORY_SEPARATOR . 'fixtures'
    . DIRECTORY_SEPARATOR . 'channel66_ingestion' . DIRECTORY_SEPARATOR . 'thread1001';

$toonCanonicalDir = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'toon';
$toonCanonicalFile = $toonCanonicalDir . DIRECTORY_SEPARATOR . 'lupo_metadata.toon';
if (!is_file($toonCanonicalFile)) {
    echo "SKIP: Canonical lupo_metadata.toon not found.\n";
    exit(0);
}

function normalizePathSlashes($s)
{
    $s = str_replace('\\', '/', (string)$s);
    while (strpos($s, '//') !== false) {
        $s = str_replace('//', '/', $s);
    }
    return $s;
}

function mkTempDir()
{
    $base = sys_get_temp_dir();
    $dir = $base . DIRECTORY_SEPARATOR . 'lupo_chan66_test_' . uniqid('', true);
    @mkdir($dir, 0777, true);
    return $dir;
}

function filePathFromRootForFixture($fileName)
{
    return 'lupo-channels/66/threads/1001/' . $fileName;
}

function computeEntityIdForTest($filePathFromRoot)
{
    $hex = substr(md5((string)$filePathFromRoot), 0, 15);
    return (int)hexdec($hex);
}

function fetchOne($db, $sql, $params)
{
    $row = $db->fetchRow($sql, $params);
    if ($row && is_array($row)) {
        return $row;
    }
    return null;
}

function findRootMetadataId($db, $metadataTable, $entityId)
{
    $sql = "SELECT metadata_id FROM {$metadataTable}
            WHERE entity_type = 'channel66_artifact' AND entity_id = :eid AND domain_id IS NULL
              AND class_name = 'lupopedia_header_root' AND property_key = '__root__' AND is_deleted = 0
            LIMIT 1";
    $row = $db->fetchRow($sql, array('eid' => $entityId));
    if ($row && isset($row['metadata_id'])) {
        return (int)$row['metadata_id'];
    }
    return null;
}

function fetchPropertyValue($db, $metadataTable, $entityId, $propertyKey)
{
    $sql = "SELECT property_value, class_name, meta_type, parent_metadata_id FROM {$metadataTable}
            WHERE entity_type = 'channel66_artifact' AND entity_id = :eid AND domain_id IS NULL
              AND property_key = :pk AND is_deleted = 0
            LIMIT 1";
    $row = $db->fetchRow($sql, array('eid' => $entityId, 'pk' => $propertyKey));
    if ($row && isset($row['property_value'])) {
        return $row['property_value'];
    }
    return null;
}

function blockExists($db, $metadataTable, $entityId, $rootMetadataId, $blockPropertyKey)
{
    $sql = "SELECT metadata_id FROM {$metadataTable}
            WHERE entity_type = 'channel66_artifact' AND entity_id = :eid AND domain_id IS NULL
              AND parent_metadata_id = :rid AND class_name = 'lupopedia_block'
              AND property_key = :bk AND is_deleted = 0
            LIMIT 1";
    $row = $db->fetchRow($sql, array('eid' => $entityId, 'rid' => $rootMetadataId, 'bk' => $blockPropertyKey));
    return ($row && isset($row['metadata_id']));
}

function edgeExistsAndValue($db, $metadataTable, $entityId, $edgePropertyKey)
{
    $sql = "SELECT property_value FROM {$metadataTable}
            WHERE entity_type = 'channel66_artifact' AND entity_id = :eid AND domain_id IS NULL
              AND class_name = 'lupopedia_edge' AND property_key = :ek AND is_deleted = 0
            LIMIT 1";
    $row = $db->fetchRow($sql, array('eid' => $entityId, 'ek' => $edgePropertyKey));
    return ($row && isset($row['property_value'])) ? $row['property_value'] : null;
}

function buildScopeRootWithFixture($fixtureDir, $fixtureFileName)
{
    $tempRoot = mkTempDir();
    $destThreadDir = $tempRoot . DIRECTORY_SEPARATOR . 'lupo-channels' . DIRECTORY_SEPARATOR . '66'
        . DIRECTORY_SEPARATOR . 'threads' . DIRECTORY_SEPARATOR . '1001';
    @mkdir($destThreadDir, 0777, true);
    $src = $fixtureDir . DIRECTORY_SEPARATOR . $fixtureFileName;
    $dst = $destThreadDir . DIRECTORY_SEPARATOR . $fixtureFileName;
    if (!@copy($src, $dst)) {
        throw new Exception('Failed copying fixture: ' . $fixtureFileName);
    }
    return array($tempRoot, $dst);
}

function buildToonDirVariant($toonCanonicalFile, $variant)
{
    $tempRoot = mkTempDir();
    $toonDir = $tempRoot . DIRECTORY_SEPARATOR . 'toon';
    @mkdir($toonDir, 0777, true);
    $toonPath = $toonDir . DIRECTORY_SEPARATOR . 'lupo_metadata.toon';
    $raw = file_get_contents($toonCanonicalFile);
    if ($raw === false) {
        throw new Exception('Failed reading canonical toon');
    }
    if ($variant === 'missing_schema_ref') {
        // Deterministically remove the exact schema_ref field entry line from fields:.
        $raw = str_replace("- '`schema_ref` varchar(64)'\r\n", "", $raw);
        $raw = str_replace("- '`schema_ref` varchar(64)'\n", "", $raw);
        $raw = str_replace("- '`schema_ref` varchar(64)'", "", $raw);
    }
    file_put_contents($toonPath, $raw);
    @touch($toonPath, time() - 10);
    return $toonDir;
}

function runIngestOnSingleFixture($db, $toonDir, $scopeRoot, $concurrencyHookCallable = null)
{
    $ingester = new Channel66HeaderIngester($db);
    if ($concurrencyHookCallable !== null) {
        $GLOBALS['LUPO_CHANNEL66_CONCURRENT_EDIT_HOOK'] = $concurrencyHookCallable;
    } else {
        unset($GLOBALS['LUPO_CHANNEL66_CONCURRENT_EDIT_HOOK']);
    }

    $summary = $ingester->ingest(array(
        'mode' => 'p0',
        'thread_id' => 1001,
        'scope_root' => $scopeRoot,
        'toon_dir' => $toonDir,
    ));
    return $summary;
}

echo "Running Channel 66 Thread 1001 bounded-authority ingestion P0 tests...\n";

// 1) Valid ingest
list($scopeRoot, $dst) = buildScopeRootWithFixture($fixtureDir, 'valid_ingest_thread1001.md');
$toonDirOk = buildToonDirVariant($toonCanonicalFile, 'ok');
$summary = runIngestOnSingleFixture($db, $toonDirOk, $scopeRoot);
$eid = computeEntityIdForTest(filePathFromRootForFixture('valid_ingest_thread1001.md'));
$rootId = findRootMetadataId($db, $metadataTable, $eid);
test_assert($rootId !== null, 'valid ingest: root exists');
test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'validation_status') === 'ingested', 'valid ingest: validation_status=ingested');
test_assert(blockExists($db, $metadataTable, $eid, $rootId, 'lupopedia.headers'), 'valid ingest: lupopedia.headers block exists');

// 2) Malformed YAML reject
list($scopeRoot, $dst) = buildScopeRootWithFixture($fixtureDir, 'malformed_yaml_thread1001.md');
$summary = runIngestOnSingleFixture($db, $toonDirOk, $scopeRoot);
$eid = computeEntityIdForTest(filePathFromRootForFixture('malformed_yaml_thread1001.md'));
$rootId = findRootMetadataId($db, $metadataTable, $eid);
test_assert($rootId !== null, 'malformed yaml reject: root exists');
test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'validation_status') === 'rejected', 'malformed yaml reject: validation_status=rejected');
test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'reject_type') === 'malformed_yaml', 'malformed yaml reject: reject_type=malformed_yaml');
test_assert(!blockExists($db, $metadataTable, $eid, $rootId, 'lupopedia.headers'), 'malformed yaml reject: no lupopedia.headers block');

// 3) Missing required field structural reject
list($scopeRoot, $dst) = buildScopeRootWithFixture($fixtureDir, 'missing_required_field_thread1001.md');
$summary = runIngestOnSingleFixture($db, $toonDirOk, $scopeRoot);
$eid = computeEntityIdForTest(filePathFromRootForFixture('missing_required_field_thread1001.md'));
$rootId = findRootMetadataId($db, $metadataTable, $eid);
test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'reject_type') === 'structural_validation_failure', 'missing required fields: reject_type=structural_validation_failure');
test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'validation_status') === 'rejected', 'missing required fields: validation_status=rejected');
test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'validation_warnings') !== null, 'missing required fields: validation_warnings present');
test_assert(!blockExists($db, $metadataTable, $eid, $rootId, 'lupopedia.headers'), 'missing required fields: no authoritative header block');

// 4) Incompatible version reject (major mismatch)
list($scopeRoot, $dst) = buildScopeRootWithFixture($fixtureDir, 'incompatible_version_thread1001.md');
$summary = runIngestOnSingleFixture($db, $toonDirOk, $scopeRoot);
$eid = computeEntityIdForTest(filePathFromRootForFixture('incompatible_version_thread1001.md'));
$rootId = findRootMetadataId($db, $metadataTable, $eid);
test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'reject_type') === 'version_incompatible', 'incompatible version: reject_type=version_incompatible');
test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'version_scenario') !== null, 'incompatible version: version_scenario set');
test_assert(!blockExists($db, $metadataTable, $eid, $rootId, 'lupopedia.headers'), 'incompatible version: no authoritative header block');

// 5) Deprecated minor newer version warn
list($scopeRoot, $dst) = buildScopeRootWithFixture($fixtureDir, 'deprecated_minor_newer_version_thread1001.md');
$summary = runIngestOnSingleFixture($db, $toonDirOk, $scopeRoot);
$eid = computeEntityIdForTest(filePathFromRootForFixture('deprecated_minor_newer_version_thread1001.md'));
$rootId = findRootMetadataId($db, $metadataTable, $eid);
test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'validation_status') === 'ingested', 'deprecated version warn: validation_status=ingested');
$warningJson = fetchPropertyValue($db, $metadataTable, $eid, 'warning_codes');
test_assert($warningJson !== null, 'deprecated version warn: warning_codes present');
$warningArr = json_decode($warningJson, true);
test_assert(is_array($warningArr) && count($warningArr) === 1 && $warningArr[0] === 'deprecated_version_minor_newer', 'deprecated version warn: warning_codes matches');
test_assert(blockExists($db, $metadataTable, $eid, $rootId, 'lupopedia.headers'), 'deprecated version warn: headers block present');

// 6) TOON conflict reject (missing schema_ref column)
$toonDirMissing = buildToonDirVariant($toonCanonicalFile, 'missing_schema_ref');
list($scopeRoot, $dst) = buildScopeRootWithFixture($fixtureDir, 'toon_missing_column_thread1001.md');
$summary = runIngestOnSingleFixture($db, $toonDirMissing, $scopeRoot);
$eid = computeEntityIdForTest(filePathFromRootForFixture('toon_missing_column_thread1001.md'));
$rootId = findRootMetadataId($db, $metadataTable, $eid);
test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'reject_type') === 'toon_conflict', 'TOON conflict: reject_type=toon_conflict');
test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'toon_error_code') !== null, 'TOON conflict: toon_error_code present');
test_assert(!blockExists($db, $metadataTable, $eid, $rootId, 'lupopedia.headers'), 'TOON conflict: no authoritative header block');

// 7) Missing edge target continues, edge_target_verified=0
list($scopeRoot, $dst) = buildScopeRootWithFixture($fixtureDir, 'missing_edge_target_thread1001.md');
$summary = runIngestOnSingleFixture($db, $toonDirOk, $scopeRoot);
$eid = computeEntityIdForTest(filePathFromRootForFixture('missing_edge_target_thread1001.md'));
$rootId = findRootMetadataId($db, $metadataTable, $eid);
test_assert(blockExists($db, $metadataTable, $eid, $rootId, 'lupopedia.edges'), 'missing edge target: lupopedia.edges block exists');
$edgeJson = edgeExistsAndValue($db, $metadataTable, $eid, 'edge_0');
test_assert($edgeJson !== null, 'missing edge target: edge_0 node exists');
$edgeObj = json_decode($edgeJson, true);
test_assert(is_array($edgeObj) && isset($edgeObj['edge_target_verified']) && (int)$edgeObj['edge_target_verified'] === 0, 'missing edge target: edge_target_verified=0');

// 8) Concurrent edit: conflict_flagged without overwriting authoritative blocks
list($scopeRoot, $dst) = buildScopeRootWithFixture($fixtureDir, 'concurrent_edit_thread1001.md');
$eid = computeEntityIdForTest(filePathFromRootForFixture('concurrent_edit_thread1001.md'));
$rootId = null;
$summary = runIngestOnSingleFixture($db, $toonDirOk, $scopeRoot, null);
$rootId = findRootMetadataId($db, $metadataTable, $eid);
test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'validation_status') === 'ingested', 'concurrent edit: first run ingested');
test_assert(blockExists($db, $metadataTable, $eid, $rootId, 'lupopedia.headers'), 'concurrent edit: baseline headers block present');

$absFile = $dst;
$touched = false;
$hook = function($f) use (&$touched, $absFile) {
    if ($touched) {
        return;
    }
    if (normalizePathSlashes($f) !== normalizePathSlashes($absFile)) {
        return;
    }
    $touched = true;
    @touch($f, time() + 20);
};

$summary = runIngestOnSingleFixture($db, $toonDirOk, $scopeRoot, $hook);
$rootId2 = findRootMetadataId($db, $metadataTable, $eid);
test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'validation_status') === 'conflict_flagged', 'concurrent edit: validation_status=conflict_flagged');
test_assert(blockExists($db, $metadataTable, $eid, $rootId2, 'lupopedia.headers'), 'concurrent edit: headers block still present after conflict');

// 9) Field preservation matrix behavior
list($scopeRoot, $dst) = buildScopeRootWithFixture($fixtureDir, 'field_preservation_matrix_thread1001.md');
$summary = runIngestOnSingleFixture($db, $toonDirOk, $scopeRoot);
$eid = computeEntityIdForTest(filePathFromRootForFixture('field_preservation_matrix_thread1001.md'));
$rootId = findRootMetadataId($db, $metadataTable, $eid);

test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'file_path_from_root') !== null, 'field preservation: lossless field stored');
test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'display__actor_name') !== null, 'field preservation: display__actor_name present for lossy field');
test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'actor_name') === null, 'field preservation: actor_name (lossy key) not stored directly');

$tagsJson = fetchPropertyValue($db, $metadataTable, $eid, 'tags');
test_assert($tagsJson !== null, 'field preservation: tags present');
$tagsArr = json_decode($tagsJson, true);
test_assert(is_array($tagsArr) && count($tagsArr) === 2 && $tagsArr[0] === 'a' && $tagsArr[1] === 'z', 'field preservation: tags normalized (sorted unique)');

test_assert(fetchPropertyValue($db, $metadataTable, $eid, '__private_note') === null, 'field preservation: __private_note never-projected omitted');

// 10) Cache invalidation: ToonSchemaCache reload when mtime changes
$toonDirForCache = buildToonDirVariant($toonCanonicalFile, 'ok');
list($scopeRoot, $dst) = buildScopeRootWithFixture($fixtureDir, 'cache_invalidation_thread1001.md');
$eid = computeEntityIdForTest(filePathFromRootForFixture('cache_invalidation_thread1001.md'));

$sharedCache = new ToonSchemaCache();
$ingester = new Channel66HeaderIngester($db);
$ingester->setToonSchemaCache($sharedCache);

// First run: ok
unset($GLOBALS['LUPO_CHANNEL66_CONCURRENT_EDIT_HOOK']);
$ingester->ingest(array(
    'mode' => 'p0',
    'thread_id' => 1001,
    'scope_root' => $scopeRoot,
    'toon_dir' => $toonDirForCache,
));
$rootId = findRootMetadataId($db, $metadataTable, $eid);
test_assert(blockExists($db, $metadataTable, $eid, $rootId, 'lupopedia.headers'), 'cache invalidation: first run headers block present');

// Modify toon file in place to remove schema_ref line and update mtime.
$toonPath = $toonDirForCache . DIRECTORY_SEPARATOR . 'lupo_metadata.toon';
$raw = file_get_contents($toonPath);
// Deterministically remove the exact schema_ref field entry line.
$raw = str_replace("- '`schema_ref` varchar(64)'\r\n", "", $raw);
$raw = str_replace("- '`schema_ref` varchar(64)'\n", "", $raw);
$raw = str_replace("- '`schema_ref` varchar(64)'", "", $raw);
file_put_contents($toonPath, $raw);
@touch($toonPath, time() + 5);

// Second run: should reject toon_conflict.
$ingester->ingest(array(
    'mode' => 'p0',
    'thread_id' => 1001,
    'scope_root' => $scopeRoot,
    'toon_dir' => $toonDirForCache,
));
$rootId2 = findRootMetadataId($db, $metadataTable, $eid);
test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'validation_status') === 'rejected', 'cache invalidation: second run rejected');
test_assert(fetchPropertyValue($db, $metadataTable, $eid, 'reject_type') === 'toon_conflict', 'cache invalidation: reject_type=toon_conflict');
test_assert(!blockExists($db, $metadataTable, $eid, $rootId2, 'lupopedia.headers'), 'cache invalidation: headers block absent after toon_conflict');

echo "\nSummary: " . $passed . " passed, " . $failed . " failed\n";
exit($failed > 0 ? 1 : 0);

