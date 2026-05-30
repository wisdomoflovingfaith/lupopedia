<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "scripts/audit_schema_doctrine.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175911"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
lupopedia.headers:
  when_updated: "20260324175617"
  file_path_from_root: "scripts/audit_schema_doctrine.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175617"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
 * LUPO Schema Doctrine Audit — canonical validator for Lupopedia database doctrine.
 *
 * Uses TOON files in database/lupopedia/toon/ (generated from the database) as the
 * schema source. Does not use information_schema or a live DB connection for structure.
 *
 * Checks:
 * - doctrine_metadata in TOONs: no_foreign_keys, no_triggers (violation if false/missing for lupo_*)
 * - DATETIME/TIMESTAMP columns (violation)
 * - Time-like columns that should be BIGINT (violation/warning)
 * - Tables missing is_deleted where soft-delete is expected (violation)
 *
 * Run: php scripts/audit_schema_doctrine.php [--json-only]
 * Output: console report + artifacts/reports/schema_doctrine_audit.json
 */

if (php_sapi_name() !== 'cli') {
    die('CLI only.');
}

$baseDir = dirname(__DIR__);
$toonDir = $baseDir . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'toon';
$jsonDir = $baseDir . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'json';

if (!is_dir($toonDir)) {
    fwrite(STDERR, "TOON directory not found: {$toonDir}\n");
    exit(1);
}

$jsonOnly = in_array('--json-only', isset($argv) ? $argv : array());

$report = array(
    'generated_utc' => (int) gmdate('YmdHis'),
    'schema_source' => 'database/lupopedia/toon',
    'summary' => array(
        'tables_checked' => 0,
        'columns_checked' => 0,
        'foreign_keys_found' => 0,
        'triggers_found' => 0,
        'procedures_found' => 0,
        'functions_found' => 0,
        'datetime_timestamp_violations' => 0,
        'bigint_time_violations' => 0,
        'soft_delete_violations' => 0,
        'soft_delete_warnings' => 0,
        'doctrine_metadata_violations' => 0,
        'warnings' => 0
    ),
    'violations' => array(
        'foreign_keys' => array(),
        'triggers' => array(),
        'procedures' => array(),
        'functions' => array(),
        'forbidden_temporal_types' => array(),
        'bigint_time_columns' => array(),
        'soft_delete' => array(),
        'doctrine_metadata' => array()
    ),
    'warnings' => array(),
    'exemptions' => array()
);

$timeColumnPatterns = array(
    'created_ymdhis', 'updated_ymdhis', 'deleted_ymdhis', 'started_ymdhis', 'completed_ymdhis',
    'last_modified_utc', 'last_message_ymdhis', 'read_by_actor_utc', 'reserved_ymdhis',
    'expires_ymdhis', 'last_seen_ymdhis', 'handshake_completed_ymdhis', 'awareness_completed_ymdhis',
    'cjp_completed_ymdhis', 'escalation_timestamp', 'end_ymdhis'
);
$timeLikeSuffixes = array('_ymdhis', '_utc', '_timestamp', 'created_at', 'updated_at', 'deleted_at');

$tablesRequiringSoftDelete = array(
    'lupo_actors', 'lupo_agents', 'lupo_dialog_messages', 'lupo_dialog_threads', 'lupo_tasks',
    'lupo_contents', 'lupo_sessions', 'lupo_registry', 'lupo_channels', 'lupo_auth_users',
    'lupo_actor_channel_roles', 'lupo_metadata', 'lupo_visits', 'lupo_actor_collections'
);
$softDeleteLikelyPatterns = array('lupo_dialog_', 'lupo_task', 'lupo_content', 'lupo_agent', 'lupo_actor_channel', 'lupo_crm_', 'lupo_visit');
$softDeleteExemptPrefixes = array('livehelp_');
$softDeleteExemptTables = array('lupo_actor_moods', 'lupo_channel_log_types', 'lupo_task_statuses', 'lupo_task_priorities', 'lupo_event_metadata', 'lupo_department_metadata');

/**
 * Parse a TOON field string "`colname` type ..." to get column name and data type.
 * @param string $fieldDef
 * @return array|null array('name' => ..., 'type' => ..., 'full' => ...) or null
 */
function parseToonField($fieldDef) {
    if (!preg_match('/^`([^`]+)`\s+(\w+)(?:\([^)]*\))?/', $fieldDef, $m)) {
        return null;
    }
    $name = $m[1];
    $typeBase = strtolower($m[2]);
    $full = trim($fieldDef);
    if (preg_match('/^`[^`]+`\s+(\S+)/', $fieldDef, $typeM)) {
        $fullType = strtolower($typeM[1]);
    } else {
        $fullType = $typeBase;
    }
    return array('name' => $name, 'type' => $typeBase, 'full_type' => $fullType, 'full' => $full);
}

/**
 * Load a single TOON (table) definition. Tries .toon as YAML, then .json fallback.
 * @param string $tableName base name e.g. lupo_actors
 * @param string $toonDir
 * @param string $jsonDir
 * @return array|null decoded array with table_name, fields, doctrine_metadata or null
 */
function loadToon($tableName, $toonDir, $jsonDir) {
    $toonPath = $toonDir . DIRECTORY_SEPARATOR . $tableName . '.toon';
    $jsonPath = $jsonDir . DIRECTORY_SEPARATOR . $tableName . '.json';
    $content = null;
    $isYaml = false;
    if (is_file($toonPath)) {
        $content = file_get_contents($toonPath);
        $isYaml = true;
    } elseif (is_file($jsonPath)) {
        $content = file_get_contents($jsonPath);
    }
    if ($content === null || $content === false) {
        return null;
    }
    if ($isYaml && function_exists('yaml_parse')) {
        $decoded = @yaml_parse($content);
        return is_array($decoded) ? $decoded : null;
    }
    $decoded = @json_decode($content, true);
    return is_array($decoded) ? $decoded : null;
}

$toonFiles = glob($toonDir . DIRECTORY_SEPARATOR . '*.toon');
$tableList = array();
foreach ($toonFiles as $path) {
    $base = basename($path, '.toon');
    $tableList[] = $base;
}
sort($tableList);

$report['summary']['tables_checked'] = count($tableList);

foreach ($tableList as $table) {
    $toon = loadToon($table, $toonDir, $jsonDir);
    if (!$toon || empty($toon['fields']) || !is_array($toon['fields'])) {
        $report['warnings'][] = array('table' => $table, 'message' => 'TOON missing or invalid (no fields).');
        $report['summary']['warnings']++;
        continue;
    }

    $report['summary']['columns_checked'] += count($toon['fields']);
    $cols = array();
    foreach ($toon['fields'] as $fieldStr) {
        $parsed = parseToonField($fieldStr);
        if ($parsed) {
            $cols[] = $parsed;
        }
    }

    foreach ($cols as $c) {
        $colName = $c['name'];
        $dataType = $c['type'];
        $colType = $c['full_type'];

        if ($dataType === 'datetime') {
            $report['violations']['forbidden_temporal_types'][] = array('table' => $table, 'column' => $colName, 'actual_type' => $colType);
            $report['summary']['datetime_timestamp_violations']++;
        }
        if ($dataType === 'timestamp') {
            $report['violations']['forbidden_temporal_types'][] = array('table' => $table, 'column' => $colName, 'actual_type' => $colType);
            $report['summary']['datetime_timestamp_violations']++;
        }

        $isTimeLike = false;
        foreach ($timeColumnPatterns as $p) {
            if ($colName === $p) {
                $isTimeLike = true;
                break;
            }
        }
        if (!$isTimeLike) {
            foreach ($timeLikeSuffixes as $suf) {
                if (strpos($colName, $suf) !== false || substr($colName, -strlen($suf)) === $suf) {
                    $isTimeLike = true;
                    break;
                }
            }
        }
        if ($isTimeLike && $dataType !== 'bigint') {
            $report['violations']['bigint_time_columns'][] = array('table' => $table, 'column' => $colName, 'actual_type' => $colType, 'expected' => 'BIGINT');
            $report['summary']['bigint_time_violations']++;
        }
        if ($isTimeLike && $dataType === 'bigint' && stripos($c['full'], 'unsigned') !== false) {
            $report['warnings'][] = array('table' => $table, 'column' => $colName, 'message' => 'BIGINT UNSIGNED; doctrine prefers signed BIGINT for timestamps.');
            $report['summary']['warnings']++;
        }
    }

    $doctrineMeta = isset($toon['doctrine_metadata']) && is_array($toon['doctrine_metadata']) ? $toon['doctrine_metadata'] : array();
    if (strpos($table, 'lupo_') === 0) {
        if (isset($doctrineMeta['no_foreign_keys']) && $doctrineMeta['no_foreign_keys'] !== true && $doctrineMeta['no_foreign_keys'] !== 'true') {
            $report['violations']['doctrine_metadata'][] = array('table' => $table, 'message' => 'doctrine_metadata.no_foreign_keys should be true.');
            $report['summary']['doctrine_metadata_violations']++;
        }
        if (isset($doctrineMeta['no_triggers']) && $doctrineMeta['no_triggers'] !== true && $doctrineMeta['no_triggers'] !== 'true') {
            $report['violations']['doctrine_metadata'][] = array('table' => $table, 'message' => 'doctrine_metadata.no_triggers should be true.');
            $report['summary']['doctrine_metadata_violations']++;
        }
    }

    $needsSoftDelete = false;
    $exempt = false;
    foreach ($softDeleteExemptTables as $et) {
        if ($table === $et) {
            $exempt = true;
            break;
        }
    }
    if (!$exempt) {
        foreach ($softDeleteExemptPrefixes as $pfx) {
            if (strpos($table, $pfx) === 0) {
                $exempt = true;
                break;
            }
        }
    }
    if (!$exempt) {
        foreach ($tablesRequiringSoftDelete as $t) {
            if ($table === $t) {
                $needsSoftDelete = true;
                break;
            }
        }
        if (!$needsSoftDelete && strpos($table, 'lupo_') === 0) {
            foreach ($softDeleteLikelyPatterns as $pat) {
                if (strpos($table, $pat) !== false) {
                    $needsSoftDelete = true;
                    break;
                }
            }
        }
    }
    if ($needsSoftDelete) {
        $hasIsDeleted = false;
        foreach ($cols as $c) {
            if (strtolower($c['name']) === 'is_deleted') {
                $hasIsDeleted = true;
                break;
            }
        }
        if (!$hasIsDeleted) {
            $report['violations']['soft_delete'][] = array('table' => $table, 'message' => 'Table likely requires soft-delete but has no is_deleted TINYINT.');
            $report['summary']['soft_delete_violations']++;
        }
    }
}

$reportDir = $baseDir . DIRECTORY_SEPARATOR . 'artifacts' . DIRECTORY_SEPARATOR . 'reports';
if (!is_dir($reportDir)) {
    @mkdir($baseDir . DIRECTORY_SEPARATOR . 'artifacts', 0755, true);
    @mkdir($reportDir, 0755, true);
}
$jsonPath = $reportDir . DIRECTORY_SEPARATOR . 'schema_doctrine_audit.json';
file_put_contents($jsonPath, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

if (!$jsonOnly) {
    echo "=== LUPO Schema Doctrine Audit (TOON source) ===\n";
    echo "Schema source: database/lupopedia/toon\n";
    echo "Generated: " . $report['generated_utc'] . " (UTC YmdHis)\n";
    echo "Tables checked: " . $report['summary']['tables_checked'] . " | Columns: " . $report['summary']['columns_checked'] . "\n\n";

    $violCount = $report['summary']['doctrine_metadata_violations'] + $report['summary']['datetime_timestamp_violations']
        + $report['summary']['bigint_time_violations'] + $report['summary']['soft_delete_violations'];

    if ($violCount === 0 && $report['summary']['warnings'] === 0) {
        echo "[COMPLIANT] No doctrine violations or warnings.\n";
    } else {
        if ($report['summary']['doctrine_metadata_violations'] > 0) {
            echo "[VIOLATION] doctrine_metadata (no_foreign_keys/no_triggers): " . $report['summary']['doctrine_metadata_violations'] . "\n";
            foreach (array_slice($report['violations']['doctrine_metadata'], 0, 5) as $v) {
                echo "  - " . $v['table'] . ": " . $v['message'] . "\n";
            }
        }
        if ($report['summary']['datetime_timestamp_violations'] > 0) {
            echo "[VIOLATION] DATETIME/TIMESTAMP columns: " . $report['summary']['datetime_timestamp_violations'] . "\n";
            foreach (array_slice($report['violations']['forbidden_temporal_types'], 0, 5) as $v) {
                echo "  - " . $v['table'] . "." . $v['column'] . " (" . $v['actual_type'] . ")\n";
            }
        }
        if ($report['summary']['bigint_time_violations'] > 0) {
            echo "[VIOLATION] Time-like columns not BIGINT: " . $report['summary']['bigint_time_violations'] . "\n";
            foreach (array_slice($report['violations']['bigint_time_columns'], 0, 5) as $v) {
                echo "  - " . $v['table'] . "." . $v['column'] . " (" . $v['actual_type'] . ")\n";
            }
        }
        if ($report['summary']['soft_delete_violations'] > 0) {
            echo "[VIOLATION] Soft-delete missing (is_deleted): " . $report['summary']['soft_delete_violations'] . "\n";
            foreach (array_slice($report['violations']['soft_delete'], 0, 5) as $v) {
                echo "  - " . $v['table'] . ": " . $v['message'] . "\n";
            }
        }
        if ($report['summary']['warnings'] > 0) {
            echo "[WARNING] Warnings: " . $report['summary']['warnings'] . "\n";
        }
    }
    echo "\nJSON report: " . $jsonPath . "\n";
}

$violCount = $report['summary']['doctrine_metadata_violations'] + $report['summary']['datetime_timestamp_violations']
    + $report['summary']['bigint_time_violations'] + $report['summary']['soft_delete_violations'];
exit($violCount > 0 ? 1 : 0);
