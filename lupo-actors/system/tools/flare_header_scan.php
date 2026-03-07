<?php
/**
 * FLARE Header Scan — System Agent (Actor 0).
 * Scans LUPO_DATABASE_DIR, LUPO_CONTENT_DIR, LUPO_ACTORS_DIR for .md files
 * lacking FLARE headers and appends them to anubis-queue.json.
 * Logs to lupo-actors/0/logs/flare-scan.log.
 *
 * Usage: php flare_header_scan.php [project_root]
 * Run from project root or pass path. Does not require DB.
 */
$project_root = isset($argv[1]) ? rtrim($argv[1], DIRECTORY_SEPARATOR) : dirname(dirname(dirname(dirname(__FILE__))));
if (!is_dir($project_root)) {
    fwrite(STDERR, "Invalid project root: {$project_root}\n");
    exit(1);
}

if (!defined('LUPO_DATABASE_DIR')) { define('LUPO_DATABASE_DIR', 'lupo-database'); }
if (!defined('LUPO_CONTENT_DIR'))   { define('LUPO_CONTENT_DIR', 'lupo-content'); }
if (!defined('LUPO_ACTORS_DIR'))    { define('LUPO_ACTORS_DIR', 'lupo-actors'); }

$actors_0 = $project_root . DIRECTORY_SEPARATOR . LUPO_ACTORS_DIR . DIRECTORY_SEPARATOR . '0';
$queue_file = $actors_0 . DIRECTORY_SEPARATOR . 'anubis-queue.json';
$logs_dir   = $actors_0 . DIRECTORY_SEPARATOR . 'logs';
$log_file   = $logs_dir . DIRECTORY_SEPARATOR . 'flare-scan.log';

$scan_dirs = array(
    LUPO_DATABASE_DIR => $project_root . DIRECTORY_SEPARATOR . LUPO_DATABASE_DIR,
    LUPO_CONTENT_DIR  => $project_root . DIRECTORY_SEPARATOR . LUPO_CONTENT_DIR,
    LUPO_ACTORS_DIR   => $project_root . DIRECTORY_SEPARATOR . LUPO_ACTORS_DIR,
);

/**
 * Recursively collect all .md files under a directory.
 *
 * @param string $dir
 * @param string $base_len length of base path to strip for relative path
 * @return array list of relative paths (forward slashes)
 */
function collect_md_files($dir, $base_len) {
    $out = array();
    if (!is_dir($dir)) {
        return $out;
    }
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS | RecursiveDirectoryIterator::FOLLOW_SYMLINKS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($it as $fi) {
        if (!$fi->isFile()) {
            continue;
        }
        $ext = strtolower($fi->getExtension());
        if ($ext !== 'md') {
            continue;
        }
        $path = $fi->getPathname();
        $rel = substr($path, $base_len);
        $rel = str_replace('\\', '/', $rel);
        if ($rel === '' || $rel[0] === '/') {
            $rel = ltrim($rel, '/');
        }
        $out[] = $rel;
    }
    return $out;
}

/**
 * Return true if the file has a valid FLARE header (YAML frontmatter with flare or file_path_from_root).
 *
 * @param string $path full path to file
 * @return bool
 */
function has_flare_header($path) {
    if (!is_file($path) || !is_readable($path)) {
        return false;
    }
    $head = @file_get_contents($path, false, null, 0, 4096);
    if ($head === false || $head === '') {
        return false;
    }
    if (substr($head, 0, 3) !== '---') {
        return false;
    }
    $rest = substr($head, 3);
    $end = strpos($rest, '---');
    if ($end === false) {
        return false;
    }
    $block = substr($rest, 0, $end);
    return (stripos($block, 'flare') !== false || stripos($block, 'file_path_from_root') !== false);
}

$found = array();
$base_len = strlen($project_root) + 1;

foreach ($scan_dirs as $name => $abs_path) {
    if (!is_dir($abs_path)) {
        continue;
    }
    $files = collect_md_files($abs_path, strlen($abs_path) + 1);
    foreach ($files as $rel) {
        $full = $abs_path . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel);
        $canonical_path = $name . '/' . $rel;
        if (!has_flare_header($full)) {
            $found[] = array('path' => $canonical_path, 'type' => 'md', 'status' => 'pending');
        }
    }
}

$existing = array();
if (is_file($queue_file) && is_readable($queue_file)) {
    $raw = @file_get_contents($queue_file);
    if ($raw !== false) {
        $dec = json_decode($raw, true);
        if (is_array($dec)) {
            $existing = $dec;
        }
    }
}

$by_path = array();
foreach ($existing as $item) {
    $p = isset($item['path']) ? $item['path'] : '';
    if ($p !== '') {
        $by_path[$p] = $item;
    }
}
foreach ($found as $item) {
    $p = $item['path'];
    if (!isset($by_path[$p])) {
        $by_path[$p] = $item;
    }
}
$queue = array_values($by_path);

if (!is_dir($actors_0)) {
    @mkdir($actors_0, 0755, true);
}
if (!is_dir($logs_dir)) {
    @mkdir($logs_dir, 0755, true);
}

$now = gmdate('Y-m-d\TH:i:s\Z');
$log_lines = array();
$log_lines[] = '[' . $now . '] FLARE header scan run.';
$log_lines[] = 'Scanned: ' . LUPO_DATABASE_DIR . ', ' . LUPO_CONTENT_DIR . ', ' . LUPO_ACTORS_DIR;
$log_lines[] = 'New files without FLARE header: ' . count($found);
$log_lines[] = 'Queue total entries: ' . count($queue);
if (count($found) > 0) {
    $log_lines[] = 'Added paths:';
    foreach ($found as $item) {
        $log_lines[] = '  - ' . $item['path'];
    }
}
$log_lines[] = '';

$log_content = implode("\n", $log_lines);
if (is_dir($logs_dir)) {
    @file_put_contents($log_file, $log_content, FILE_APPEND | LOCK_EX);
}

$written = @file_put_contents($queue_file, json_encode($queue, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
if ($written === false) {
    $log_lines[] = '[' . $now . '] ERROR: Could not write anubis-queue.json';
    if (is_dir($logs_dir)) {
        @file_put_contents($log_file, '[' . $now . '] ERROR: Could not write anubis-queue.json' . "\n", FILE_APPEND | LOCK_EX);
    }
    fwrite(STDERR, "Could not write queue file: {$queue_file}\n");
    exit(1);
}

echo "FLARE scan complete. Queue: " . count($queue) . " entries, new: " . count($found) . ". Log: " . $log_file . "\n";
exit(0);
