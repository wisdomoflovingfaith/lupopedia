<?php
/**
 * Channel66HeaderIngester
 *
 * Thread 1001 P0 bounded-authority ingestion pipeline for LUPOPEDIA HEADERS:
 * - discover files under lupo-channels/66/threads/<threadId>/*.md (scope-root aware)
 * - extract first YAML front-matter block and parse YAML
 * - structural validation (required lupopedia.headers fields)
 * - bounded-authority validation (compat matrix, TOON safety)
 * - field preservation + deterministic lupo_metadata projection
 * - concurrent edit detection using mtime re-check
 * - per-file JSONL logging to lupo-logs/admin/
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . 'ToonSchemaCache.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'BoundedHeaderAuthorityValidator.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'HeaderFieldPreservationMatrix.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Channel66HeaderProjection.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Channel66IngestionLogger.php';

class Channel66HeaderIngester
{
    /** @var PDO_DB */
    private $db;
    /** @var ToonSchemaCache */
    private $toonCache;
    /** @var BoundedHeaderAuthorityValidator */
    private $validator;
    /** @var HeaderFieldPreservationMatrix */
    private $preservation;
    /** @var Channel66HeaderProjection */
    private $projection;
    /** @var Channel66IngestionLogger */
    private $logger;

    public function __construct($db = null)
    {
        $this->db = $db ? $db : lupo_get_db();
        $this->toonCache = new ToonSchemaCache();
        $this->validator = new BoundedHeaderAuthorityValidator($this->toonCache);
        $this->preservation = new HeaderFieldPreservationMatrix();
        $this->projection = new Channel66HeaderProjection($this->db);
        $this->logger = new Channel66IngestionLogger();
    }

    /**
     * Allow a shared ToonSchemaCache instance for test cache invalidation.
     *
     * @param ToonSchemaCache $cache
     */
    public function setToonSchemaCache($cache)
    {
        $this->toonCache = $cache;
        $this->validator = new BoundedHeaderAuthorityValidator($this->toonCache);
    }

    private function normalizePathSlashes($s)
    {
        $s = str_replace('\\', '/', (string)$s);
        // Normalize accidental double slashes
        while (strpos($s, '//') !== false) {
            $s = str_replace('//', '/', $s);
        }
        return $s;
    }

    /**
     * Extract the first YAML front-matter block from a markdown file.
     *
     * @param string $content
     * @return array ['ok'=>bool,'yaml'=>array|null,'error'=>string|null]
     */
    private function extractAndParseFrontMatter($content)
    {
        $lines = explode("\n", str_replace("\r\n", "\n", $content));
        if (count($lines) < 2) {
            return array('ok' => false, 'error' => 'file_too_short');
        }
        if (trim($lines[0]) !== '---') {
            return array('ok' => false, 'error' => 'missing_opening_delimiter');
        }

        $yamlBlock = '';
        $foundClosing = false;
        for ($i = 1; $i < count($lines); $i++) {
            if (preg_match('/^---\s*$/', trim($lines[$i]))) {
                $foundClosing = true;
                break;
            }
            $yamlBlock .= $lines[$i] . "\n";
        }
        if (!$foundClosing) {
            return array('ok' => false, 'error' => 'missing_closing_delimiter');
        }

        if (!function_exists('yaml_parse')) {
            return array('ok' => false, 'error' => 'yaml_parse_unavailable');
        }

        $decoded = @yaml_parse($yamlBlock);
        if (!is_array($decoded)) {
            return array('ok' => false, 'error' => 'yaml_parse_failed');
        }

        return array('ok' => true, 'yaml' => $decoded, 'error' => null);
    }

    /**
     * Scan and return candidate files for threadId under scopeRoot.
     *
     * @param string $scopeRoot
     * @param int $threadId
     * @return array list of absolute file paths (sorted)
     */
    private function discoverThreadFiles($scopeRoot, $threadId)
    {
        $threadDir = rtrim($scopeRoot, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR . 'lupo-channels' . DIRECTORY_SEPARATOR . '66' . DIRECTORY_SEPARATOR . 'threads'
            . DIRECTORY_SEPARATOR . (string)$threadId;

        $out = array();
        if (!is_dir($threadDir)) {
            return $out;
        }

        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($threadDir));
        foreach ($it as $file) {
            if ($file->isFile()) {
                $path = $file->getPathname();
                if (substr(strtolower($path), -3) === '.md') {
                    $out[] = $path;
                }
            }
        }
        sort($out, SORT_STRING);
        return $out;
    }

    /**
     * Convert absolute file path to file_path_from_root, relative to scopeRoot.
     *
     * @param string $scopeRoot
     * @param string $absPath
     * @return string
     */
    private function filePathFromRootFromScope($scopeRoot, $absPath)
    {
        $scopeRootNorm = rtrim($this->normalizePathSlashes($scopeRoot), '/');
        $absNorm = $this->normalizePathSlashes($absPath);
        if (strpos($absNorm, $scopeRootNorm) !== 0) {
            // Fallback: use basename relative to current working directory.
            $rel = $absNorm;
        } else {
            $rel = substr($absNorm, strlen($scopeRootNorm));
        }
        $rel = ltrim($rel, '/');
        return $rel;
    }

    /**
     * Normalize and project outbound edges from YAML parsed structure.
     *
     * @param array $parsedYaml
     * @param string $scopeRootAbs
     * @return array list of edge payload arrays to store in lupo_metadata.edge property_value JSON
     */
    private function projectEdgesAsJson($parsedYaml, $scopeRootAbs)
    {
        if (!isset($parsedYaml['lupopedia.edges']) || !is_array($parsedYaml['lupopedia.edges'])) {
            return array();
        }
        $edgesBlock = $parsedYaml['lupopedia.edges'];
        if (!isset($edgesBlock['outbound_edges'])) {
            return array();
        }
        $outbound = $edgesBlock['outbound_edges'];
        if (!is_array($outbound)) {
            return array();
        }

        $edges = array();

        // Case A: grouped outbound_edges: outbound_edges: { code: [..], documentation: [..] }
        $isAssoc = array_keys($outbound) !== range(0, count($outbound) - 1);
        if ($isAssoc) {
            foreach ($outbound as $cat => $list) {
                if (!is_array($list)) {
                    continue;
                }
                foreach ($list as $edge) {
                    if (!is_array($edge)) {
                        continue;
                    }
                    $edges[] = $this->normalizeEdge($edge, $scopeRootAbs, $cat);
                }
            }
            return $edges;
        }

        // Case B: flat outbound_edges: list of edge objects
        foreach ($outbound as $edge) {
            if (!is_array($edge)) {
                continue;
            }
            $edges[] = $this->normalizeEdge($edge, $scopeRootAbs, null);
        }
        return $edges;
    }

    private function normalizeEdge($edge, $scopeRootAbs, $defaultCategory)
    {
        $to = isset($edge['to']) ? (string)$edge['to'] : '';
        $toNorm = $this->normalizePathSlashes($to);
        $edgeType = isset($edge['type']) ? (string)$edge['type'] : '';
        $weight = isset($edge['weight']) ? (string)$edge['weight'] : '';
        $reason = isset($edge['reason']) ? (string)$edge['reason'] : '';
        $edgeCategory = isset($edge['edge_category']) ? (string)$edge['edge_category'] : (isset($defaultCategory) ? (string)$defaultCategory : '');
        if ($edgeCategory === '') {
            $edgeCategory = 'documentation';
        }

        $absTarget = rtrim($scopeRootAbs, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $toNorm);
        $verified = is_file($absTarget) ? 1 : 0;

        return array(
            'to' => $toNorm,
            'type' => $edgeType,
            'weight' => $weight,
            'reason' => $reason,
            'edge_category' => $edgeCategory,
            'edge_target_verified' => $verified,
        );
    }

    /**
     * Convert classified field list into property_key => property_value map.
     *
     * @param array $classified
     * @return array
     */
    private function classifyToPropertyMap($classified)
    {
        $out = array();
        foreach ($classified as $row) {
            if (!is_array($row) || !isset($row['property_key'])) {
                continue;
            }
            $out[(string)$row['property_key']] = $row['property_value'];
        }
        return $out;
    }

    /**
     * Ingest all thread files under scopeRoot for a given threadId.
     *
     * @param array $config
     * @return array summary
     */
    public function ingest($config)
    {
        $scopeRoot = isset($config['scope_root']) ? (string)$config['scope_root'] : getcwd();
        $threadId = isset($config['thread_id']) ? (int)$config['thread_id'] : 1001;
        $toonDir = isset($config['toon_dir']) ? (string)$config['toon_dir'] : $scopeRoot;

        $mode = isset($config['mode']) ? (string)$config['mode'] : 'p0';
        if ($mode !== 'p0') {
            throw new Exception('Unsupported mode for Channel66 bounded-authority P0 ingester: ' . $mode);
        }

        $files = $this->discoverThreadFiles($scopeRoot, $threadId);

        $counts = array(
            'ingested' => 0,
            'rejected' => 0,
            'conflict_flagged' => 0,
            'total' => count($files),
        );

        foreach ($files as $absFile) {
            $filePathFromRoot = $this->filePathFromRootFromScope($scopeRoot, $absFile);
            $entityId = $this->projection->computeEntityId($filePathFromRoot);

            $mtimeRead = @filemtime($absFile);
            if ($mtimeRead === false) {
                $mtimeRead = 0;
            }

            $raw = @file_get_contents($absFile);
            if ($raw === false) {
                // Treat as malformed YAML outcome.
                $this->db->beginTransaction();
                try {
                    $this->projection->writeFullTree(
                        $entityId,
                        66,
                        'rejected',
                        array(
                            'reject_type' => 'malformed_yaml',
                            'parse_error' => '1',
                            'parse_error_message' => 'read_failed',
                        ),
                        array(),
                        array()
                    );
                    $this->db->commit();
                    $counts['rejected']++;
                    $this->logger->log(array(
                        'file_path_from_root' => $filePathFromRoot,
                        'entity_id' => $entityId,
                        'outcome' => 'rejected',
                        'reject_type' => 'malformed_yaml',
                    ));
                } catch (Exception $e) {
                    $this->db->rollBack();
                    $this->logger->log(array(
                        'file_path_from_root' => $filePathFromRoot,
                        'entity_id' => $entityId,
                        'outcome' => 'rejected',
                        'reject_type' => 'malformed_yaml',
                        'error' => $e->getMessage(),
                    ));
                }
                continue;
            }

            $parseResult = $this->extractAndParseFrontMatter($raw);
            if (!$parseResult['ok']) {
                $this->db->beginTransaction();
                try {
                    $this->projection->writeFullTree(
                        $entityId,
                        66,
                        'rejected',
                        array(
                            'reject_type' => 'malformed_yaml',
                            'parse_error' => '1',
                            'parse_error_message' => $parseResult['error'],
                        ),
                        array(),
                        array()
                    );
                    $this->db->commit();
                    $counts['rejected']++;
                    $this->logger->log(array(
                        'file_path_from_root' => $filePathFromRoot,
                        'entity_id' => $entityId,
                        'outcome' => 'rejected',
                        'reject_type' => 'malformed_yaml',
                    ));
                } catch (Exception $e) {
                    $this->db->rollBack();
                    $this->logger->log(array(
                        'file_path_from_root' => $filePathFromRoot,
                        'entity_id' => $entityId,
                        'outcome' => 'rejected',
                        'reject_type' => 'malformed_yaml',
                        'error' => $e->getMessage(),
                    ));
                }
                continue;
            }

            $parsedYaml = $parseResult['yaml'];
            $validation = $this->validator->validateP0($parsedYaml, $filePathFromRoot, $toonDir, $threadId);

            if (!is_array($validation) || !isset($validation['outcome'])) {
                // Defensive: treat as reject.
                $this->db->beginTransaction();
                try {
                    $this->projection->writeFullTree(
                        $entityId,
                        66,
                        'rejected',
                        array(
                            'reject_type' => 'structural_validation_failure',
                        ),
                        array(),
                        array()
                    );
                    $this->db->commit();
                    $counts['rejected']++;
                    $this->logger->log(array(
                        'file_path_from_root' => $filePathFromRoot,
                        'entity_id' => $entityId,
                        'outcome' => 'rejected',
                        'reject_type' => 'structural_validation_failure',
                    ));
                } catch (Exception $e) {
                    $this->db->rollBack();
                    $this->logger->log(array(
                        'file_path_from_root' => $filePathFromRoot,
                        'entity_id' => $entityId,
                        'outcome' => 'rejected',
                        'reject_type' => 'structural_validation_failure',
                        'error' => $e->getMessage(),
                    ));
                }
                continue;
            }

            if ($validation['outcome'] === 'reject') {
                $rejectType = isset($validation['reject_type']) ? (string)$validation['reject_type'] : 'structural_validation_failure';
                $rootExtra = array('reject_type' => $rejectType);
                if ($rejectType === 'structural_validation_failure' && isset($validation['validation_warnings'])) {
                    $rootExtra['validation_warnings'] = $this->classToJsonString($validation['validation_warnings']);
                }
                if ($rejectType === 'version_incompatible' && isset($validation['version_scenario'])) {
                    $rootExtra['version_scenario'] = (string)$validation['version_scenario'];
                }
                if ($rejectType === 'toon_conflict' && isset($validation['toon_error_code'])) {
                    $rootExtra['toon_error_code'] = (string)$validation['toon_error_code'];
                }
                $this->db->beginTransaction();
                try {
                    $this->projection->writeFullTree($entityId, 66, 'rejected', $rootExtra, array(), array());
                    $this->db->commit();
                    $counts['rejected']++;
                    $this->logger->log(array(
                        'file_path_from_root' => $filePathFromRoot,
                        'entity_id' => $entityId,
                        'outcome' => 'rejected',
                        'reject_type' => $rejectType,
                    ));
                } catch (Exception $e) {
                    $this->db->rollBack();
                    $this->logger->log(array(
                        'file_path_from_root' => $filePathFromRoot,
                        'entity_id' => $entityId,
                        'outcome' => 'rejected',
                        'reject_type' => $rejectType,
                        'error' => $e->getMessage(),
                    ));
                }
                continue;
            }

            // Accept or warn: classify fields and prepare edges.
            $headersBlock = isset($parsedYaml['lupopedia.headers']) && is_array($parsedYaml['lupopedia.headers']) ? $parsedYaml['lupopedia.headers'] : array();
            $classified = $this->preservation->classifyFields($headersBlock);
            $headerPropsMap = $this->classifyToPropertyMap($classified);

            // Project edges as JSON payloads in edge node rows.
            $edgesList = $this->projectEdgesAsJson($parsedYaml, $scopeRoot);

            // Concurrency simulation hook (tests can touch file mtime).
            if (isset($GLOBALS['LUPO_CHANNEL66_CONCURRENT_EDIT_HOOK']) && is_callable($GLOBALS['LUPO_CHANNEL66_CONCURRENT_EDIT_HOOK'])) {
                call_user_func($GLOBALS['LUPO_CHANNEL66_CONCURRENT_EDIT_HOOK'], $absFile);
            }

            $mtimeBeforeWrite = @filemtime($absFile);
            if ($mtimeBeforeWrite === false) {
                $mtimeBeforeWrite = 0;
            }

            if ($mtimeBeforeWrite !== $mtimeRead) {
                // Conflict_flagged: update root validation_status and conflict properties.
                $this->db->beginTransaction();
                try {
                    $this->projection->writeConflictFlagged($entityId, 66, 'concurrent_edit', 'file_mtime_changed');
                    $this->db->commit();
                    $counts['conflict_flagged']++;
                    $this->logger->log(array(
                        'file_path_from_root' => $filePathFromRoot,
                        'entity_id' => $entityId,
                        'outcome' => 'conflict_flagged',
                        'conflict_type' => 'concurrent_edit',
                    ));
                } catch (Exception $e) {
                    $this->db->rollBack();
                    $this->logger->log(array(
                        'file_path_from_root' => $filePathFromRoot,
                        'entity_id' => $entityId,
                        'outcome' => 'conflict_flagged',
                        'conflict_type' => 'concurrent_edit',
                        'error' => $e->getMessage(),
                    ));
                }
                continue;
            }

            $rootExtra = array();
            if ($validation['outcome'] === 'warn') {
                $warningCodes = isset($validation['warning_codes']) && is_array($validation['warning_codes']) ? $validation['warning_codes'] : array();
                $rootExtra['warning_codes'] = json_encode($warningCodes, JSON_UNESCAPED_SLASHES);
            }

            $this->db->beginTransaction();
            try {
                $this->projection->writeFullTree($entityId, 66, 'ingested', $rootExtra, $headerPropsMap, $edgesList);
                $this->db->commit();
                $counts['ingested']++;
                $this->logger->log(array(
                    'file_path_from_root' => $filePathFromRoot,
                    'entity_id' => $entityId,
                    'outcome' => 'ingested',
                ));
            } catch (Exception $e) {
                $this->db->rollBack();
                $this->logger->log(array(
                    'file_path_from_root' => $filePathFromRoot,
                    'entity_id' => $entityId,
                    'outcome' => 'rejected',
                    'error' => $e->getMessage(),
                ));
                $counts['rejected']++;
            }
        }

        return $counts;
    }

    private function classToJsonString($value)
    {
        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_SLASHES);
        }
        return (string)$value;
    }
}

