<?php
/**
 * Actor directory migration: move numeric dirs (0/, 1/, 42/) to name-based (system/, wolfie/, antigravity/).
 * Creates symlinks from old numeric path to new name path for backward compatibility.
 * Run: php lupo-database/lupopedia/mysql/migrations/20260306_actor_directory_migration.php [--dry-run]
 * Idempotent; safe to run multiple times.
 *
 * PHP 5.3 compatible.
 */

$opts = array();
if (function_exists('getopt')) {
    $opts = getopt('', array('dry-run'));
}
$dry_run = isset($opts['dry-run']) || (isset($opts['dry_run']) ? $opts['dry_run'] : false);

$script_dir = dirname(__FILE__);
$base = dirname(dirname(dirname(dirname($script_dir))));
$base = rtrim(str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $base), DIRECTORY_SEPARATOR);

$actors_dir = 'lupo-actors';
$registry_path = $base . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'registry.json';
$log_path = $base . DIRECTORY_SEPARATOR . $actors_dir . DIRECTORY_SEPARATOR . 'directory_migration.log';

$log_lines = array();
$log_lines[] = date('Y-m-d\TH:i:s\Z', time()) . ' Migration ' . ($dry_run ? '(DRY-RUN) ' : '') . 'started. Base: ' . $base;

if (!is_file($registry_path) || !is_readable($registry_path)) {
    $log_lines[] = 'ERROR: Registry not found or not readable: ' . $registry_path;
    echo implode("\n", $log_lines) . "\n";
    if (!$dry_run) {
        $log_dir = dirname($log_path);
        if (!is_dir($log_dir)) {
            @mkdir($log_dir, 0755, true);
        }
        @file_put_contents($log_path, implode("\n", $log_lines) . "\n", FILE_APPEND);
    }
    exit(1);
}

$raw = file_get_contents($registry_path);
$data = json_decode($raw, true);
if (!is_array($data) || !isset($data['actors']) || !is_array($data['actors'])) {
    $log_lines[] = 'ERROR: Invalid registry JSON or missing actors.';
    echo implode("\n", $log_lines) . "\n";
    if (!$dry_run) {
        $log_dir = dirname($log_path);
        if (!is_dir($log_dir)) {
            @mkdir($log_dir, 0755, true);
        }
        @file_put_contents($log_path, implode("\n", $log_lines) . "\n", FILE_APPEND);
    }
    exit(1);
}

$actors_root = $base . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $actors_dir);
$standard_subdirs = array('apps', 'tools', 'docs', 'db-changes', 'api', 'needs', 'prompts', 'logs', 'skills');

$renamed = 0;
$symlinked = 0;
$created = 0;
$skipped = 0;
$conflicts = array();

foreach ($data['actors'] as $actor_name => $actor) {
    $actor_id = isset($actor['actor_id']) ? (int) $actor['actor_id'] : null;
    $dir_rel = isset($actor['dir']) ? trim($actor['dir']) : '';
    if ($dir_rel === '') {
        $dir_rel = $actors_dir . DIRECTORY_SEPARATOR . $actor_name;
    }
    $new_path = $base . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $dir_rel);
    $old_path = $actors_root . DIRECTORY_SEPARATOR . (string) $actor_id;

    $old_exists = is_dir($old_path);
    $new_exists = is_dir($new_path);
    $old_is_link = $old_exists && is_link($old_path);
    $new_is_link = $new_exists && is_link($new_path);

    if ($old_exists && $new_exists && !$old_is_link && !$new_is_link) {
        $conflicts[] = $actor_name . ': both old (' . $old_path . ') and new (' . $new_path . ') exist. Skip or resolve manually.';
        $log_lines[] = 'WARNING: ' . end($conflicts);
        $skipped++;
        continue;
    }

    if ($old_exists && !$new_exists && !$old_is_link) {
        if (!$dry_run) {
            if (@rename($old_path, $new_path)) {
                $renamed++;
                $log_lines[] = 'RENAMED: ' . $old_path . ' -> ' . $new_path;
                if (@symlink($new_path, $old_path)) {
                    $symlinked++;
                    $log_lines[] = 'SYMLINK: ' . $old_path . ' -> ' . $new_path;
                } else {
                    $log_lines[] = 'WARNING: symlink failed: ' . $old_path . ' -> ' . $new_path;
                }
            } else {
                $log_lines[] = 'ERROR: rename failed: ' . $old_path . ' -> ' . $new_path;
            }
        } else {
            $log_lines[] = '[DRY-RUN] Would rename: ' . $old_path . ' -> ' . $new_path;
            $log_lines[] = '[DRY-RUN] Would symlink: ' . $old_path . ' -> ' . $new_path;
            $renamed++;
            $symlinked++;
        }
        continue;
    }

    if ($new_exists && !$old_exists) {
        if (!$dry_run && is_dir($new_path) && !is_link($new_path)) {
            if (@symlink($new_path, $old_path)) {
                $symlinked++;
                $log_lines[] = 'SYMLINK: ' . $old_path . ' -> ' . $new_path;
            } else {
                $log_lines[] = 'WARNING: symlink failed: ' . $old_path . ' -> ' . $new_path;
            }
        } elseif ($dry_run && is_dir($new_path)) {
            $log_lines[] = '[DRY-RUN] Would symlink: ' . $old_path . ' -> ' . $new_path;
            $symlinked++;
        }
        $skipped++;
        continue;
    }

    if ($new_exists && $old_exists && $old_is_link) {
        $skipped++;
        continue;
    }

    if (!$new_exists && !$old_exists) {
        if (!$dry_run) {
            if (@mkdir($new_path, 0755, true)) {
                foreach ($standard_subdirs as $sub) {
                    $sub_path = $new_path . DIRECTORY_SEPARATOR . $sub;
                    if (!is_dir($sub_path)) {
                        @mkdir($sub_path, 0755, true);
                    }
                }
                $created++;
                $log_lines[] = 'CREATED: ' . $new_path . ' (with standard subdirs)';
            } else {
                $log_lines[] = 'ERROR: mkdir failed: ' . $new_path;
            }
        } else {
            $log_lines[] = '[DRY-RUN] Would create: ' . $new_path;
            $created++;
        }
    }
}

$log_lines[] = 'Summary: renamed=' . $renamed . ' symlinked=' . $symlinked . ' created=' . $created . ' skipped=' . $skipped . ' conflicts=' . count($conflicts);
if (count($conflicts) > 0) {
    $log_lines[] = 'Conflicts (resolve manually): ' . implode('; ', $conflicts);
}
$log_lines[] = date('Y-m-d\TH:i:s\Z', time()) . ' Migration finished.';

echo implode("\n", $log_lines) . "\n";

if (!$dry_run) {
    $log_dir = dirname($log_path);
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0755, true);
    }
    @file_put_contents($log_path, implode("\n", $log_lines) . "\n", FILE_APPEND);
}

exit(count($conflicts) > 0 ? 1 : 0);
