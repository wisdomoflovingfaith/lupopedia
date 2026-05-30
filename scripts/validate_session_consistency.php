#!/usr/bin/env php
<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "scripts/validate_session_consistency.php"
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
 * Session consistency validator (4.0.69).
 *
 * Compares session state between lupo_sessions (DB) and database/sessions/*.md (files).
 * Reports drift only; does not auto-correct. See docs/doctrine/SESSION_RECONCILIATION_DOCTRINE.md.
 *
 * Usage:
 *   php scripts/validate_session_consistency.php [--db] [--files-only]
 *
 *   --db         Attempt DB connection and compare file vs DB (default if bootstrap succeeds).
 *   --files-only Only validate session files (required fields, no DB).
 */

$repoRoot = dirname(__DIR__);
if (!defined('LUPOPEDIA_PATH')) {
    if (file_exists($repoRoot . '/lupopedia-config.php')) {
        require_once $repoRoot . '/lupopedia-config.php';
    }
    if (!defined('LUPOPEDIA_PATH')) {
        define('LUPOPEDIA_PATH', $repoRoot);
    }
}

$sessionsDir = LUPOPEDIA_PATH . '/database/sessions';
$useDb = true;
foreach (array_slice($argv, 1) as $arg) {
    if ($arg === '--files-only') {
        $useDb = false;
    }
    if ($arg === '--db') {
        $useDb = true;
    }
}

$requiredFileFields = array('session_id', 'actor_id', 'channel_id', 'federation_node_id');
$optionalFileFields = array('paired_actor_id', 'system_version', 'actor_name', 'channel_name');

function parseSessionBlock($path) {
    $content = @file_get_contents($path);
    if ($content === false) {
        return null;
    }
    if (!preg_match('/^---\s*\n(.*?)\n---/s', $content, $m)) {
        return null;
    }
    $yaml = $m[1];
    $block = null;
    if (preg_match('/lupopedia\.session:\s*\n(.*?)(?=\n\w|\n---|$)/s', $yaml, $n)) {
        $block = $n[1];
    }
    if ($block === null) {
        return array();
    }
    $out = array();
    foreach (explode("\n", $block) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }
        if (preg_match('/^(\w+):\s*(.*)$/', $line, $v)) {
            $key = $v[1];
            $val = trim($v[2], " \t\"'");
            $out[$key] = $val;
        }
    }
    return $out;
}

$reports = array();
$files = glob($sessionsDir . '/*.md');
if ($files === false) {
    $files = array();
}

foreach ($files as $f) {
    $base = basename($f);
    $data = parseSessionBlock($f);
    if ($data === null) {
        $reports[] = array('file' => $base, 'error' => 'Could not read or parse file');
        continue;
    }
    $missing = array();
    foreach ($requiredFileFields as $k) {
        if (!isset($data[$k]) || $data[$k] === '') {
            $missing[] = $k;
        }
    }
    if (!empty($missing)) {
        $reports[] = array('file' => $base, 'missing_required' => $missing, 'data' => $data);
    } else {
        $reports[] = array('file' => $base, 'ok' => true, 'data' => $data);
    }
}

if ($useDb && file_exists(LUPOPEDIA_PATH . '/includes/bootstrap.php')) {
    try {
        require_once LUPOPEDIA_PATH . '/includes/bootstrap.php';
        $db = DatabaseFactory::getConnection();
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $t = $prefix . 'sessions';
        $rows = $db->fetchAll("SELECT session_id, actor_id, actor_name, channel_id, federation_node_id FROM {$t} WHERE is_deleted = 0");
        $bySession = array();
        foreach ($rows as $r) {
            $bySession[$r['session_id']] = $r;
        }
        foreach ($reports as $i => $r) {
            if (empty($r['ok']) || empty($r['data']['session_id'])) {
                continue;
            }
            $sid = $r['data']['session_id'];
            $fileData = $r['data'];
            if (!isset($bySession[$sid])) {
                $reports[$i]['db'] = 'session_id not in DB';
                continue;
            }
            $dbRow = $bySession[$sid];
            $diffs = array();
            if (isset($fileData['actor_id']) && (string)$fileData['actor_id'] !== (string)$dbRow['actor_id']) {
                $diffs[] = 'actor_id: file=' . $fileData['actor_id'] . ' db=' . $dbRow['actor_id'];
            }
            if (isset($fileData['channel_id']) && (string)$fileData['channel_id'] !== (string)$dbRow['channel_id']) {
                $diffs[] = 'channel_id: file=' . $fileData['channel_id'] . ' db=' . $dbRow['channel_id'];
            }
            if (isset($fileData['federation_node_id']) && (string)$fileData['federation_node_id'] !== (string)$dbRow['federation_node_id']) {
                $diffs[] = 'federation_node_id: file=' . $fileData['federation_node_id'] . ' db=' . $dbRow['federation_node_id'];
            }
            if (!empty($diffs)) {
                $reports[$i]['db_drift'] = $diffs;
            }
        }
    } catch (Exception $e) {
        echo "DB skipped: " . $e->getMessage() . "\n";
    }
}

foreach ($reports as $r) {
    $f = $r['file'];
    if (!empty($r['error'])) {
        echo "[ERROR] {$f}: " . $r['error'] . "\n";
    } elseif (!empty($r['missing_required'])) {
        echo "[MISSING] {$f}: required fields " . implode(', ', $r['missing_required']) . "\n";
    } elseif (!empty($r['db_drift'])) {
        echo "[DRIFT]   {$f}: " . implode('; ', $r['db_drift']) . "\n";
    } else {
        echo "[OK]      {$f}\n";
    }
}

echo "\nDone. No auto-correction; see SESSION_RECONCILIATION_DOCTRINE.md for reconciliation rules.\n";