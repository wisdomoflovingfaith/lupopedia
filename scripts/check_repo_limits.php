#!/usr/bin/env php
<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.0.99"
#   lupopedia.schema: implementation
#   when_updated: "20260412162124"
#   file_path_from_root: "scripts/check_repo_limits.php"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/check_repo_limits.php"
#   last_modified_utc: "20260412162124"
#   federation_node_id: 0
#   channel_key: "development"
#   trust_tier: "canonical"
#   memory_key: "memory/development/canonical/1026/04/check-repo-limits.toon"
#   artifact_type: implementation
#   artifact_kind: tool
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   title: "Repository file limit checker"
#   status: "complete"
#   parent_pk_id: "00"
#   summary: "Counts files recursively under repo root; fails when over SYSTEM_LIMITS cap (default 10000)."
#   module: null
#   dialog_transcript: "0/development/check-repo-limits"
# ---------------------------------------------------------------------
/**
 * Repository file limit checker (SYSTEM_LIMITS Doctrine).
 *
 * Logic:
 * - Count all files under the repo root (recursively).
 * - FAIL if file_count > 10000.
 *
 * Usage:
 *   php scripts/check_repo_limits.php
 *   php scripts/check_repo_limits.php --root="."
 */

// PHP 7.4+ compatible per project rules (see php-7-4-compatibility.md).

$root = null;
$max_files = 10000;

// Basic arg parsing (no external deps)
// $argv is a PHP CLI builtin; keep it deterministic.
foreach ($argv as $arg) {
    // skip script name at argv[0]
    if (!is_string($arg)) {
        continue;
    }
    if (strpos($arg, '--root=') === 0) {
        $root = substr($arg, strlen('--root='));
    }
    if (strpos($arg, '--max-files=') === 0) {
        $max_files = (int) substr($arg, strlen('--max-files='));
    }
}

if ($root === null || $root === '') {
    $root = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');
} else {
    $root = realpath($root);
}

if ($root === false || !is_dir($root)) {
    fwrite(STDERR, "Error: --root must resolve to a directory.\n");
    exit(1);
}

$count = 0;

$dirIt = new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS);
$it = new RecursiveIteratorIterator($dirIt);
foreach ($it as $fileInfo) {
    if (!($fileInfo instanceof SplFileInfo)) {
        continue;
    }
    if (!$fileInfo->isFile()) {
        continue;
    }

    $count++;
    if ($count > $max_files + 10) {
        // Early break if it is already beyond the threshold by a margin.
        break;
    }
}

if ($count > $max_files) {
    fwrite(STDERR, "FAIL: repo file count {$count} > max {$max_files}\n");
    exit(1);
}

echo "OK: repo file count {$count} <= max {$max_files}\n";
exit(0);

