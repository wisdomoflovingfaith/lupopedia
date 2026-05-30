<?php
/**
 * Phase 4: Antigravity governance — monitor and reject non-compliant headers.
 * Scans Markdown under lupo-docs/ for canonical Synthesized Documentation headers;
 * reports missing or invalid headers. PHP 7.4+ compatible. Uses LUPOPEDIA_PATH.
 *
 * Usage: php lupo-bin/antigravity_governance.php [path]
 *   php lupo-bin/antigravity_governance.php
 *   php lupo-bin/antigravity_governance.php lupo-docs/specs
 *
 * Exit 0 if all compliant, 1 if any non-compliant.
 */

$basePath = dirname(__DIR__);
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', $basePath . DIRECTORY_SEPARATOR);
}
$scanPath = isset($argv[1]) && trim($argv[1]) !== '' ? trim($argv[1]) : (LUPOPEDIA_PATH . 'lupo-docs');
if (strpos($scanPath, DIRECTORY_SEPARATOR) !== 0 && strpos($scanPath, ':') !== 1) {
    $scanPath = $basePath . DIRECTORY_SEPARATOR . $scanPath;
}
if (!is_dir($scanPath)) {
    fwrite(STDERR, "Path is not a directory: " . $scanPath . "\n");
    exit(1);
}

$required_blocks = array('synthesized.headers', 'lupopedia.headers', 'flare.headers');
$required_fields = array('FILE', 'CLASS', 'NAMESPACE', 'CHANNEL', 'COLLECTION');
$non_compliant = array();

$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($scanPath, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);
foreach ($it as $file) {
    if (!$file->isFile()) {
        continue;
    }
    $name = $file->getFilename();
    if (strtolower(substr($name, -3)) !== '.md') {
        continue;
    }
    $path = $file->getPathname();
    $content = @file_get_contents($path);
    if ($content === false) {
        $non_compliant[] = array('path' => $path, 'reason' => 'unreadable');
        continue;
    }
    if (strpos($content, '---') !== 0) {
        $non_compliant[] = array('path' => $path, 'reason' => 'missing YAML front matter');
        continue;
    }
    $end = strpos($content, '---', 3);
    if ($end === false) {
        $non_compliant[] = array('path' => $path, 'reason' => 'unclosed front matter');
        continue;
    }
    $front = substr($content, 3, $end - 3);
    $block_found = null;
    foreach ($required_blocks as $block) {
        if (strpos($front, $block . ':') !== false) {
            $block_found = $block;
            break;
        }
    }
    if ($block_found === null) {
        $non_compliant[] = array('path' => $path, 'reason' => 'missing canonical header block (synthesized.headers / lupopedia.headers; legacy flare.headers accepted)');
        continue;
    }
    if ($block_found === 'synthesized.headers') {
        foreach ($required_fields as $field) {
            if (strpos($front, $field . ':') === false) {
                $non_compliant[] = array('path' => $path, 'reason' => 'missing field: ' . $field);
                break;
            }
        }
    }
}

if (count($non_compliant) > 0) {
    foreach ($non_compliant as $item) {
        fwrite(STDERR, "NON-COMPLIANT: " . $item['path'] . " — " . $item['reason'] . "\n");
    }
    exit(1);
}
echo "All scanned Markdown files have compliant headers.\n";
exit(0);
