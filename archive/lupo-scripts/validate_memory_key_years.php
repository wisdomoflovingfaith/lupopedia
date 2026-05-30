#!/usr/bin/env php
<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.0.99"
#   lupopedia.schema: implementation
#   when_updated: "20260412162124"
#   file_path_from_root: "lupo-scripts/validate_memory_key_years.php"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/validate_memory_key_years.php"
#   last_modified_utc: "20260412162124"
#   federation_node_id: 0
#   channel_key: "development"
#   trust_tier: "canonical"
#   memory_key: "lupo-memory/development/canonical/1026/04/validate-memory-key-years.toon"
#   artifact_type: implementation
#   artifact_kind: tool
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   title: "Memory key path year segment validator"
#   status: "complete"
#   parent_pk_id: "16"
#   summary: "Scans lupo-memory for .toon paths; checks memory_key year vs trust_tier (PRD 16 section 8.1)."
#   module: null
#   dialog_transcript: "0/development/validate-memory-key-years"
# ---------------------------------------------------------------------
/**
 * validate_memory_key_years.php - recursively scans lupo-memory/ for .toon paths and checks
 * year segment vs trust_tier (PRD 16 section 8.1).
 *
 * Usage: php lupo-scripts/validate_memory_key_years.php [--strict]
 */

$repoRoot = dirname(__DIR__);
$root     = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-memory';
$strict   = in_array('--strict', $argv, true);
$cal      = (int) gmdate('Y');
$expectedCanonicalYear = $cal - 1000;

$errors = array();
$warns  = array();

if (!is_dir($root)) {
    echo "No lupo-memory/ directory - nothing to scan.\n";
    exit(0);
}

$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS)
);
foreach ($it as $fileInfo) {
    /** @var SplFileInfo $fileInfo */
    if (!$fileInfo->isFile()) {
        continue;
    }
    if (substr($fileInfo->getFilename(), -5) !== '.toon') {
        continue;
    }
    $rel = str_replace('\\', '/', substr($fileInfo->getPathname(), strlen($repoRoot) + 1));
    $parts = explode('/', $rel);
    if (count($parts) < 5 || $parts[0] !== 'lupo-memory') {
        continue;
    }
    $tt = isset($parts[2]) ? $parts[2] : '';
    if (!isset($parts[3])) {
        continue;
    }
    $yearSeg = $parts[3];
    if (!ctype_digit($yearSeg) || strlen($yearSeg) !== 4) {
        continue;
    }
    $y = (int) $yearSeg;
    if ($tt === 'canonical') {
        if ($y !== $expectedCanonicalYear) {
            $msg = $rel . ': canonical path year ' . $yearSeg . ' != ' . $expectedCanonicalYear . ' (calendar ' . $cal . ' - 1000)';
            if ($strict) {
                $errors[] = $msg;
            } else {
                $warns[] = $msg;
            }
        }
    } elseif ($tt === 'staging') {
        if ($y !== $cal) {
            $msg = $rel . ': staging path year ' . $yearSeg . ' != calendar ' . $cal;
            if ($strict) {
                $errors[] = $msg;
            } else {
                $warns[] = $msg;
            }
        }
    }
}

foreach ($warns as $w) {
    fwrite(STDERR, '[WARN] ' . $w . "\n");
}
foreach ($errors as $e) {
    fwrite(STDERR, '[ERROR] ' . $e . "\n");
}

if (!empty($errors)) {
    exit(1);
}

echo 'validate_memory_key_years: OK (scanned under lupo-memory/, warnings=' . count($warns) . ")\n";
exit(0);
