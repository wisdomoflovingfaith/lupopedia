<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "lupo-scripts/check_repo_limits.php"
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
lupopedia.headers:
  when_updated: "20260324175617"
  file_path_from_root: "lupo-scripts/check_repo_limits.php"
  last_modified_utc: "20260324175617"
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
 * Repository file limit checker (SYSTEM_LIMITS Doctrine).
 *
 * Logic:
 * - Count all files under the repo root (recursively).
 * - FAIL if file_count > 10000.
 *
 * Usage:
 *   php lupo-scripts/check_repo_limits.php
 *   php lupo-scripts/check_repo_limits.php --root="."
 */

// PHP 5.6+ compatible (no typed properties, no modern syntax).

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

