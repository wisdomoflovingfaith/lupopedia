<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: tooling
  when_updated: "20260406035358"
  file_path_from_root: "lupo-scripts/check_repo_limits.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-scripts/check_repo_limits.php"
  last_modified_utc: "20260406035358"
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
  purpose: "Repository file limit checker (SYSTEM_LIMITS Doctrine); counts files recursively and fails if over cap."
  tags: ["tooling", "limits", "script", "system_limits"]
lupopedia.footer:
  last_verified: "20260406035358"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent"
---
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

