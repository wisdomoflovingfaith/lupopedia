<?php
/**
 * import_content.php — PHP mirror of import_content.py (4.0.89 shared hosting / PHP agents).
 *
 * Usage:
 *   php lupo-scripts/import_content.php <path/to/file.md> [--dry-run]
 *   php lupo-scripts/import_content.php <path/to/file.md> [--write-back]
 *
 * Default: upsert DB + sync metadata/edges/revision_history; does NOT modify the markdown file
 * (safe for shared hosting and read-only checkouts). Use --write-back to inject lupopedia.headers.content_id.
 * Python import_content.py matches this: DB import by default; use --write-back to update the file.
 * --write-back uses AgentFileWriter (operator context): path must be under lupo-rules/, lupo-docs/,
 * lupo-channels/, or lupo-content/ with an allowed extension (see TODO.md H9). Root-level or other
 * trees: use Python import or relocate the file.
 *
 * Requires: lupopedia-config.php, php-yaml, bcmath or gmp, PDO_DB.
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

$argvList = isset($argv) ? $argv : array();
$dryRun = in_array('--dry-run', $argvList, true);
$writeBack = in_array('--write-back', $argvList, true);
$mdPath = '';
foreach (array_slice($argvList, 1) as $arg) {
    if ($arg === '--dry-run' || $arg === '--write-back') {
        continue;
    }
    if ($arg !== '' && $arg[0] !== '-') {
        $mdPath = $arg;
        break;
    }
}

if ($mdPath === '') {
    fwrite(STDERR, "Usage: php lupo-scripts/import_content.php <path/to/file.md> [--dry-run] [--write-back]\n");
    fwrite(STDERR, "  Default: import to DB only (no file changes). --write-back: also set content_id in the file.\n");
    exit(2);
}

if (!is_file($mdPath)) {
    fwrite(STDERR, "ERROR: File not found: {$mdPath}\n");
    exit(2);
}

$originalText = file_get_contents($mdPath);
if ($originalText === false) {
    fwrite(STDERR, "ERROR: Could not read file.\n");
    exit(2);
}

$newline = (strpos($originalText, "\r\n") !== false) ? "\r\n" : "\n";

$parsed = HeaderDbSync::parseYamlFrontMatter($originalText);
if (!$parsed['ok']) {
    fwrite(STDERR, 'ERROR: ' . $parsed['error'] . "\n");
    exit(3);
}

$yamlData = $parsed['yaml_data'];
$yamlText = $parsed['yaml_text'];
$bodyContent = $parsed['body'];

try {
    $headers = HeaderDbSync::extractLupopediaHeadersBlock($yamlData);
} catch (Exception $e) {
    fwrite(STDERR, 'ERROR: ' . $e->getMessage() . "\n");
    exit(3);
}

$repoRoot = $base;
$v = HeaderDbSync::validateFile($mdPath, $repoRoot, false);
foreach ($v['warnings'] as $w) {
    fwrite(STDERR, 'WARNING: ' . $w . "\n");
}
if (!$v['valid']) {
    fwrite(STDERR, 'ERROR: Header validation failed:' . "\n");
    foreach ($v['errors'] as $e) {
        fwrite(STDERR, '  ' . $e . "\n");
    }
    exit(3);
}

$filePathFromRoot = HeaderDbSync::normPath((string) $headers['file_path_from_root']);
$headers['file_path_from_root'] = $filePathFromRoot;

try {
    $contentId = HeaderDbSync::calculateContentId($filePathFromRoot, $bodyContent);
} catch (Exception $e) {
    fwrite(STDERR, 'ERROR: ' . $e->getMessage() . "\n");
    exit(3);
}

if (isset($headers['content_id']) && $headers['content_id'] !== null && $headers['content_id'] !== '') {
    $existing = trim((string) $headers['content_id']);
    if ($existing !== '' && ctype_digit($existing) && (string) (int) $existing !== (string) $contentId) {
        fwrite(STDERR, 'WARNING: lupopedia.headers.content_id is ' . $existing . ', deterministic recompute is ' . $contentId . "; overwriting.\n");
    }
}

if ($dryRun) {
    echo "Imported (dry-run): {$mdPath}\n";
    echo "content_id: {$contentId}\n";
    exit(0);
}

$now = (int) gmdate('YmdHis');
$tablePrefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$contentsTable = $tablePrefix . 'contents';

try {
    $columnOrder = HeaderDbSync::loadLupoContentsColumnOrder($repoRoot);
} catch (Exception $e) {
    fwrite(STDERR, 'ERROR: ' . $e->getMessage() . "\n");
    exit(3);
}

$values = HeaderDbSync::buildValuesForLupoContents($headers, $bodyContent, $contentId, $now);
$db = DatabaseFactory::getConnection();

$ct = HeaderDbSync::safeSqlIdentifier($contentsTable);
$sqlSel = 'SELECT content_id FROM `' . $ct . '` WHERE content_id = :cid LIMIT 1';
$exists = $db->fetchRow($sqlSel, array('cid' => $contentId)) !== null;

$db->beginTransaction();
try {
    if ($exists) {
        $updateColumns = array(
            'body',
            'content',
            'updated_ymdhis',
            'title',
            'slug',
            'file_path_from_root',
            'file_last_modified_system_version',
            'file_last_modified_utc',
            'channel_id',
            'actor_id',
        );
        $updateColumns = array_values(array_intersect($updateColumns, $columnOrder));
        $setParts = array();
        $params = array('pk' => $contentId);
        foreach ($updateColumns as $col) {
            $setParts[] = '`' . str_replace('`', '``', $col) . '` = :' . $col;
            $params[$col] = ($col === 'updated_ymdhis') ? $now : (isset($values[$col]) ? $values[$col] : null);
        }
        $sqlUp = 'UPDATE `' . $ct . '` SET ' . implode(', ', $setParts) . ' WHERE content_id = :pk';
        $db->query($sqlUp, $params);
    } else {
        $cols = array();
        $ph = array();
        $params = array();
        foreach ($columnOrder as $col) {
            if (!array_key_exists($col, $values)) {
                throw new RuntimeException('Missing value for column: ' . $col);
            }
            $cols[] = '`' . str_replace('`', '``', $col) . '`';
            $ph[] = ':' . $col;
            $params[$col] = $values[$col];
        }
        $sqlIn = 'INSERT INTO `' . $ct . '` (' . implode(', ', $cols) . ') VALUES (' . implode(', ', $ph) . ')';
        $db->query($sqlIn, $params);
    }

    $edgeCount = HeaderDbSync::syncHeaderArtifactToDb($db, $tablePrefix, $yamlData, $contentId, $now);
    $db->commit();
} catch (Exception $e) {
    $db->rollBack();
    fwrite(STDERR, 'ERROR: DB failure: ' . $e->getMessage() . "\n");
    exit(4);
}

if ($writeBack) {
    try {
        HeaderDbSync::setContentIdInYamlData($yamlData, $contentId);
        $updatedYamlText = HeaderDbSync::updateContentIdInYamlText($yamlText, $contentId);
        if ($updatedYamlText !== '' && substr($updatedYamlText, -1) !== "\n") {
            $updatedYamlText .= "\n";
        }
        $updatedText = '---' . $newline . $updatedYamlText . '---' . $newline . $bodyContent;
        try {
            AgentFileWriter::writeFile($mdPath, $updatedText, $repoRoot, AgentFileWriter::CONTEXT_OPERATOR, null);
        } catch (LupoAgentFileWriterException $e) {
            fwrite(STDERR, 'ERROR: write policy: ' . $e->getMessage() . "\n");
            exit(5);
        }
    } catch (Exception $e) {
        fwrite(STDERR, 'ERROR: file rewrite failed: ' . $e->getMessage() . "\n");
        exit(5);
    }
}

$histNote = array_key_exists('lupopedia.history', $yamlData) ? 'synced' : 'preserved (key absent)';
echo "Imported: {$mdPath}\n";
echo "content_id: {$contentId}\n";
echo "Operation: " . ($exists ? 'UPDATE' : 'INSERT') . "\n";
echo "Edges: {$edgeCount} synced\n";
echo "History: {$histNote}\n";
if (!$writeBack) {
    echo "File: unchanged (use --write-back to set content_id in markdown)\n";
}

exit(0);
