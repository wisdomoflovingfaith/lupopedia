#!/usr/bin/env php
<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "lupo-scripts/session_custodian.php"
  last_modified_utc: "20260324175911"
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
 * SessionCustodian — optional tool for Antigravity (or any IDE) to audit/correct lupo-database/sessions/*.md (4.0.69).
 * Audits: paired_actor_id (e.g. 1000 for human root), required fields. Optionally corrects drift (dry_run by default).
 * Doctrine: SESSION_RECONCILIATION_DOCTRINE; no silent auto-correction without explicit flag.
 *
 * Usage:
 *   php scripts/session_custodian.php [--audit] [--correct] [--dry-run]
 *   --audit   Report drift only (default).
 *   --correct Apply corrections to session files (paired_actor_id, required fields).
 *   --dry-run With --correct: show what would be changed without writing (default for --correct).
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

$sessionsDir = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'sessions';
$auditOnly = true;
$dryRun = true;
foreach (array_slice($argv, 1) as $arg) {
    if ($arg === '--correct') {
        $auditOnly = false;
    }
    if ($arg === '--dry-run') {
        $dryRun = true;
    }
}

$expectedPairedActorId = 1000;
$requiredKeys = array('session_id', 'actor_id', 'channel_id', 'federation_node_id');

function parseSessionFile($path) {
    $content = @file_get_contents($path);
    if ($content === false) {
        return array('raw' => '', 'block' => array(), 'full' => $content);
    }
    $block = array();
    if (preg_match('/^---\s*\n(.*?)\n---/s', $content, $m)) {
        $yaml = $m[1];
        if (preg_match('/lupopedia\.session:\s*\n(.*?)(?=\n\w|\n---|$)/s', $yaml, $n)) {
            $blockStr = $n[1];
            foreach (explode("\n", $blockStr) as $line) {
                $line = trim($line);
                if ($line === '' || $line[0] === '#') {
                    continue;
                }
                if (preg_match('/^(\w+):\s*(.*)$/', $line, $v)) {
                    $key = $v[1];
                    $val = trim($v[2], " \t\"'");
                    $block[$key] = $val;
                }
            }
        }
    }
    return array('raw' => $content, 'block' => $block, 'full' => $content);
}

function writeSessionFile($path, $content) {
    return @file_put_contents($path, $content, LOCK_EX) !== false;
}

$reports = array();
$files = glob($sessionsDir . DIRECTORY_SEPARATOR . '*.md');
if ($files === false) {
    $files = array();
}

foreach ($files as $f) {
    $base = basename($f);
    $parsed = parseSessionFile($f);
    $block = $parsed['block'];
    $drift = array();
    foreach ($requiredKeys as $k) {
        if (!isset($block[$k]) || $block[$k] === '') {
            $drift[] = 'missing:' . $k;
        }
    }
    $paired = isset($block['paired_actor_id']) ? (int) $block['paired_actor_id'] : null;
    if ($paired !== null && $paired !== $expectedPairedActorId) {
        $drift[] = 'paired_actor_id=' . $paired . ' (expected ' . $expectedPairedActorId . ')';
    }
    $reports[$base] = array('path' => $f, 'block' => $block, 'drift' => $drift, 'parsed' => $parsed);
}

foreach ($reports as $base => $r) {
    if (empty($r['drift'])) {
        echo "[OK] {$base}\n";
        continue;
    }
    echo "[DRIFT] {$base}: " . implode('; ', $r['drift']) . "\n";
    if (!$auditOnly && !$dryRun) {
        $content = $r['parsed']['full'];
        $block = $r['block'];
        $needWrite = false;
        if (in_array('paired_actor_id=' . (int)(isset($block['paired_actor_id']) ? $block['paired_actor_id'] : 0) . ' (expected ' . $expectedPairedActorId . ')', $r['drift']) ||
            (isset($block['paired_actor_id']) && (int) $block['paired_actor_id'] !== $expectedPairedActorId)) {
            $content = preg_replace('/paired_actor_id:\s*(\d+)/', 'paired_actor_id: ' . $expectedPairedActorId, $content, 1);
            $needWrite = true;
        }
        if ($needWrite && writeSessionFile($r['path'], $content)) {
            echo "  -> corrected {$base}\n";
        }
    } elseif (!$auditOnly && $dryRun) {
        echo "  -> would correct (use --correct without --dry-run to apply)\n";
    }
}

if ($auditOnly) {
    echo "\nAudit only. Use --correct [--dry-run] to apply corrections.\n";
}