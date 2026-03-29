<?php
/**
 * generate_headers_from_db.php — PHP mirror of generate_headers_from_db.py (DB → YAML).
 *
 * Usage:
 *   php lupo-scripts/generate_headers_from_db.php --file-path lupo-rules/root/X.md
 *   php lupo-scripts/generate_headers_from_db.php --content-id 123456
 *   php lupo-scripts/generate_headers_from_db.php --file-path X.md --dry-run
 *
 * Non-dry-run writes use AgentFileWriter (operator context): file must be under lupo-rules/,
 * lupo-docs/, lupo-channels/, or lupo-content/ with an allowed extension (TODO.md H9).
 */

$base = dirname(__DIR__);
$config = $base . DIRECTORY_SEPARATOR . 'lupopedia-config.php';
if (!is_file($config)) {
    fwrite(STDERR, "ERROR: lupopedia-config.php not found in repo root.\n");
    exit(2);
}
require_once $config;
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'class-pdo_db.php';
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'class-DatabaseFactory.php';
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'HeaderDbSync.php';
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'AgentFileWriter.php';

$args = isset($argv) ? array_slice($argv, 1) : array();
$filePath = null;
$contentId = null;
$dryRun = false;

for ($i = 0; $i < count($args); $i++) {
    if ($args[$i] === '--file-path' && isset($args[$i + 1])) {
        $filePath = $args[$i + 1];
        $i++;
    } elseif ($args[$i] === '--content-id' && isset($args[$i + 1])) {
        $contentId = $args[$i + 1];
        $i++;
    } elseif ($args[$i] === '--dry-run') {
        $dryRun = true;
    }
}

if ($filePath === null && $contentId === null) {
    fwrite(STDERR, "Usage: php lupo-scripts/generate_headers_from_db.php --file-path <path> | --content-id <id> [--dry-run]\n");
    exit(2);
}

$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$ct = HeaderDbSync::safeSqlIdentifier($prefix . 'contents');

$row = null;
if ($contentId !== null) {
    $row = $db->fetchRow('SELECT * FROM `' . $ct . '` WHERE content_id = :id AND is_deleted = 0 LIMIT 1', array('id' => $contentId));
} elseif ($filePath !== null) {
    $norm = HeaderDbSync::normPath($filePath);
    $row = $db->fetchRow(
        'SELECT * FROM `' . $ct . '` WHERE file_path_from_root = :fp AND is_deleted = 0 LIMIT 1',
        array('fp' => $norm)
    );
}

if (!$row) {
    fwrite(STDERR, "ERROR: No lupo_contents row found for the given file-path or content-id.\n");
    exit(3);
}

$cid = (string) $row['content_id'];
if ($filePath !== null && $contentId !== null) {
    $norm = HeaderDbSync::normPath($filePath);
    $fpDb = isset($row['file_path_from_root']) ? HeaderDbSync::normPath((string) $row['file_path_from_root']) : '';
    if ($fpDb !== $norm) {
        fwrite(STDERR, "ERROR: --file-path and --content-id refer to different rows.\n");
        exit(3);
    }
}

$ordered = HeaderDbSync::buildYamlDataFromDb($db, $prefix, $row);
$yamlInner = HeaderDbSync::dumpYamlOrderedBlocks($ordered);

if ($dryRun) {
    echo "---\n";
    echo $yamlInner;
    echo "---\n";
    exit(0);
}

$diskPath = $filePath;
if ($diskPath === null) {
    $diskPath = $base . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, (string) $row['file_path_from_root']);
}

if (!is_file($diskPath)) {
    fwrite(STDERR, "ERROR: File not on disk: {$diskPath}\n");
    exit(4);
}

$raw = file_get_contents($diskPath);
if ($raw === false) {
    fwrite(STDERR, "ERROR: Could not read file.\n");
    exit(4);
}

$parsed = HeaderDbSync::parseYamlFrontMatter($raw);
if (!$parsed['ok']) {
    fwrite(STDERR, 'ERROR: ' . $parsed['error'] . "\n");
    exit(4);
}

$newline = (strpos($raw, "\r\n") !== false) ? "\r\n" : "\n";
$newText = '---' . $newline . $yamlInner . '---' . $newline . $parsed['body'];

try {
    AgentFileWriter::writeFile($diskPath, $newText, $base, AgentFileWriter::CONTEXT_OPERATOR, null);
} catch (LupoAgentFileWriterException $e) {
    fwrite(STDERR, 'ERROR: write policy: ' . $e->getMessage() . "\n");
    exit(5);
}

echo "Regenerated headers: {$diskPath}\n";
echo "content_id: {$cid}\n";
exit(0);
