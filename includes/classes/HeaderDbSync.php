<?php
/**
 * HeaderDbSync — PHP mirror of scripts/lib/header_db_sync.py + import_content helpers.
 * Database-first LUPOPEDIA HEADERS: sync YAML blocks to lupo_metadata, lupo_edges, revision_history.
 *
 * Requires php-yaml (yaml_parse) for import/regenerate parity with Python.
 * Requires bcmath OR gmp for deterministic content_id (SHA-256 first 16 hex → signed BIGINT fit).
 *
 * @package Lupopedia
 */

class HeaderDbSync
{
    const HDR_PREFIX = 'hdr.';
    const FTR_PREFIX = 'ftr.';
    const BLOCK_PREFIX = 'block.';
    const SYNC_CLASS = 'lupopedia_header_sync';
    const EDGE_CATEGORY = 'lupopedia_header';
    const REF_OBJECT_TYPE = 'file_path_ref';

    /** @var array */
    public static $requiredHeaderFields = array(
        'lupopedia.schema',
        'file_path_from_root',
        'web_path',
        'federation_node_id',
        'last_modified_utc',
        'when_updated',
        'channel_id',
        'thread_id',
        'actor_id',
        'actor_name',
        'delegation_chain',
        'artifact_type',
        'artifact_kind',
        'purpose',
        'tags',
    );

    /**
     * @param string $p
     * @return string
     */
    public static function normPath($p)
    {
        $s = str_replace('\\', '/', trim((string) $p));
        $s = preg_replace('#/+#', '/', $s);
        return ltrim($s, '/');
    }

    /**
     * @param string $path
     * @return string
     */
    public static function refSlugForPath($path)
    {
        $n = self::normPath($path);
        if (strlen($n) <= 240) {
            return $n;
        }
        return 'md5:' . md5($n);
    }

    /**
     * @param mixed $val
     * @return string
     */
    public static function serializeValue($val)
    {
        if ($val === null) {
            return '';
        }
        if (is_bool($val)) {
            return $val ? '1' : '0';
        }
        if (is_int($val) || is_float($val)) {
            return (string) $val;
        }
        if (is_string($val)) {
            return $val;
        }
        return json_encode($val, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param string $raw
     * @return mixed
     */
    public static function deserializeValue($raw)
    {
        if ($raw === null) {
            return null;
        }
        $t = trim((string) $raw);
        if ($t === '') {
            return '';
        }
        if ($t !== '' && ($t[0] === '{' || $t[0] === '[')) {
            $decoded = json_decode($t, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
            return $raw;
        }
        if (preg_match('/^-?\d+$/', $t)) {
            return (int) $t;
        }
        return $raw;
    }

    /**
     * @param string $ident
     * @return string
     */
    public static function safeSqlIdentifier($ident)
    {
        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $ident)) {
            throw new InvalidArgumentException('Unsafe SQL identifier: ' . $ident);
        }
        return $ident;
    }

    /**
     * @param PDO_DB $db
     * @param string $table
     * @param string $pk
     * @return string Decimal string for BIGINT
     */
    public static function nextId($db, $table, $pk)
    {
        $t = self::safeSqlIdentifier($table);
        $k = self::safeSqlIdentifier($pk);
        $sql = 'SELECT COALESCE(MAX(`' . $k . '`), 0) + 1 AS n FROM `' . $t . '`';
        $row = $db->fetchRow($sql, array());
        if (!$row || !isset($row['n'])) {
            return '1';
        }
        return (string) (int) $row['n'];
    }

    /**
     * Timestamp + 4-digit random content_id: YYYYMMDDHHIISS + 4-digit random (see IdGenerator::generate)
     *
     * @param PDO_DB|null $db Optional DB connection for collision check
     * @return int Decimal (for PDO BIGINT binding)
     */
    public static function calculateContentId($db = null)
    {
        require_once __DIR__ . '/IdGenerator.php';
        return IdGenerator::generate($db);
    }

    /**
     * @param string $hex16 16 hex chars
     * @return string
     */
    public static function signedBigintFitHex16($hex16)
    {
        $max = '9223372036854775807';
        $mod = bcadd($max, '1', 0);

        if (function_exists('bcadd')) {
            $n = '0';
            $hex16 = strtolower($hex16);
            $len = strlen($hex16);
            for ($i = 0; $i < $len; $i++) {
                $c = $hex16[$i];
                $d = ctype_digit($c) ? $c : (string) (ord($c) - ord('a') + 10);
                $n = bcmul($n, '16', 0);
                $n = bcadd($n, $d, 0);
            }
            if (bccomp($n, $max) <= 0) {
                return $n;
            }
            return bcmod($n, $mod);
        }

        if (function_exists('gmp_init')) {
            $n = gmp_init('0x' . $hex16, 0);
            $maxG = gmp_init($max);
            $modG = gmp_init($mod);
            if (gmp_cmp($n, $maxG) <= 0) {
                return gmp_strval($n);
            }
            return gmp_strval(gmp_mod($n, $modG));
        }

        throw new RuntimeException(
            'PHP bcmath or gmp extension required for deterministic content_id (parity with Python import_content.py).'
        );
    }

    /**
     * @param string $raw Full file text
     * @return array keys: ok, yaml_data, yaml_text, body, error
     */
    public static function parseYamlFrontMatter($raw)
    {
        $out = array(
            'ok' => false,
            'yaml_data' => array(),
            'yaml_text' => '',
            'body' => '',
            'error' => '',
        );
        if (!is_string($raw) || $raw === '') {
            $out['error'] = 'File is empty';
            return $out;
        }
        // Order matters: replace CRLF first, then lone CR (else CRLF becomes two LFs and body/yaml splits diverge from Python).
        $norm = str_replace("\r\n", "\n", $raw);
        $norm = str_replace("\r", "\n", $norm);
        if (strncmp(trim($norm), '---', 3) !== 0) {
            $out['error'] = 'Missing opening --- YAML delimiter at the start of file';
            return $out;
        }
        $lines = explode("\n", $norm);
        if (count($lines) < 2 || trim($lines[0]) !== '---') {
            $out['error'] = 'Missing opening --- YAML delimiter at the start of file';
            return $out;
        }
        $closeIdx = null;
        for ($i = 1; $i < count($lines); $i++) {
            if (trim($lines[$i]) === '---') {
                $closeIdx = $i;
                break;
            }
        }
        if ($closeIdx === null) {
            $out['error'] = 'Missing closing --- YAML delimiter';
            return $out;
        }
        $yamlLines = array_slice($lines, 1, $closeIdx - 1);
        $yamlText = implode("\n", $yamlLines);
        if ($closeIdx + 1 < count($lines)) {
            $bodyLines = array_slice($lines, $closeIdx + 1);
            $out['body'] = implode("\n", $bodyLines);
        } else {
            $out['body'] = '';
        }
        $out['yaml_text'] = $yamlText;

        if (!function_exists('yaml_parse')) {
            $out['error'] = 'yaml_parse() unavailable; install php-yaml (PECL) for import/regenerate.';
            return $out;
        }
        $parsed = @yaml_parse($yamlText);
        if ($parsed === false || !is_array($parsed)) {
            $out['error'] = 'YAML parse failed';
            return $out;
        }
        $out['yaml_data'] = $parsed;
        $out['ok'] = true;
        return $out;
    }

    /**
     * @param array $yamlData
     * @return array
     */
    public static function extractLupopediaHeadersBlock($yamlData)
    {
        if (isset($yamlData['lupopedia.headers']) && is_array($yamlData['lupopedia.headers'])) {
            return $yamlData['lupopedia.headers'];
        }
        if (isset($yamlData['lupopedia']) && is_array($yamlData['lupopedia'])) {
            $inner = $yamlData['lupopedia'];
            if (isset($inner['headers']) && is_array($inner['headers'])) {
                return $inner['headers'];
            }
        }
        throw new InvalidArgumentException('lupopedia.headers block missing');
    }

    /**
     * @param array $yamlData
     * @param string|int $contentId
     */
    public static function setContentIdInYamlData(&$yamlData, $contentId)
    {
        if (isset($yamlData['lupopedia.headers']) && is_array($yamlData['lupopedia.headers'])) {
            $yamlData['lupopedia.headers']['content_id'] = (int) $contentId;
            return;
        }
        if (isset($yamlData['lupopedia']) && is_array($yamlData['lupopedia'])) {
            if (!isset($yamlData['lupopedia']['headers']) || !is_array($yamlData['lupopedia']['headers'])) {
                throw new InvalidArgumentException('lupopedia.headers block missing');
            }
            $yamlData['lupopedia']['headers']['content_id'] = (int) $contentId;
            return;
        }
        throw new InvalidArgumentException('lupopedia.headers block missing');
    }

    /**
     * @param string $filePathFromRoot
     * @return string
     */
    public static function slugifyContentPath($filePathFromRoot)
    {
        $p = str_replace('\\', '/', $filePathFromRoot);
        if (substr($p, -3) === '.md') {
            $p = substr($p, 0, -3);
        }
        $parts = array_filter(explode('/', $p));
        $joined = implode('-', $parts);
        $joined = strtolower($joined);
        $joined = preg_replace('/[^a-z0-9\-]+/', '', $joined);
        $joined = preg_replace('/\-+/', '-', $joined);
        $joined = trim($joined, '-');
        return $joined !== '' ? $joined : 'content';
    }

    /**
     * @param string $filePathFromRoot
     * @return string
     */
    public static function titleFromFilePath($filePathFromRoot)
    {
        $slug = self::slugifyContentPath($filePathFromRoot);
        $title = str_replace('-', ' ', $slug);
        $title = trim($title);
        if ($title === '') {
            return 'Untitled';
        }
        return strtoupper(substr($title, 0, 1)) . substr($title, 1);
    }

    /**
     * @param int $now
     * @param array $headers
     * @return int
     */
    public static function parseLastModifiedUtc($now, $headers)
    {
        if (!array_key_exists('last_modified_utc', $headers) || $headers['last_modified_utc'] === null) {
            return $now;
        }
        $last = $headers['last_modified_utc'];
        if (is_int($last)) {
            $asStr = (string) $last;
            if (preg_match('/^\d{14}$/', $asStr)) {
                return (int) $last;
            }
            throw new InvalidArgumentException('lupopedia.headers.last_modified_utc must be 14-digit YYYYMMDDHHIISS int');
        }
        if (is_string($last)) {
            $c = trim($last);
            if (preg_match('/^\d{14}$/', $c)) {
                return (int) $c;
            }
            throw new InvalidArgumentException('lupopedia.headers.last_modified_utc must be 14-digit string');
        }
        throw new InvalidArgumentException('lupopedia.headers.last_modified_utc must be int or string');
    }

    /**
     * @param string $repoRoot
     * @return array
     */
    public static function loadLupoContentsColumnOrder($repoRoot)
    {
        $path = rtrim($repoRoot, '/\\') . '/database/lupopedia/json/lupo_contents.json';
        if (!is_file($path)) {
            throw new RuntimeException('Missing lupo_contents.json: ' . $path);
        }
        $json = json_decode(file_get_contents($path), true);
        if (!is_array($json) || !isset($json['fields'])) {
            throw new RuntimeException('Invalid lupo_contents.json');
        }
        $cols = array();
        foreach ($json['fields'] as $f) {
            if (preg_match('/`([^`]+)`/', (string) $f, $m)) {
                $cols[] = $m[1];
            }
        }
        return $cols;
    }

    /**
     * @param array $headers From extractLupopediaHeadersBlock
     * @param string $bodyContent
     * @param string|int $contentId
     * @param int $now
     * @return array column => value
     */
    public static function buildValuesForLupoContents($headers, $bodyContent, $contentId, $now)
    {
        $fp = $headers['file_path_from_root'];
        $whenUpdated = isset($headers['when_updated']) ? (string) $headers['when_updated'] : '';
        $title = isset($headers['title']) && $headers['title'] !== null && trim((string) $headers['title']) !== ''
            ? (string) $headers['title']
            : self::titleFromFilePath((string) $fp);
        $slug = self::slugifyContentPath((string) $fp);
        $lm = self::parseLastModifiedUtc($now, $headers);

        $ch = null;
        if (isset($headers['channel_id']) && $headers['channel_id'] !== null && $headers['channel_id'] !== '') {
            $ch = (int) $headers['channel_id'];
        }
        $aid = null;
        if (isset($headers['actor_id']) && $headers['actor_id'] !== null && $headers['actor_id'] !== '') {
            $aid = (int) $headers['actor_id'];
        }

        return array(
            'content_id' => $contentId,
            'content_parent_id' => null,
            'federation_node_id' => 1,
            'federation_source_url' => null,
            'channel_id' => $ch,
            'department_id' => null,
            'actor_id' => $aid,
            'title' => $title,
            'slug' => $slug,
            'custom_path' => null,
            'description' => null,
            'seo_keywords' => null,
            'body' => $bodyContent,
            'content' => $bodyContent,
            'content_type' => 'article',
            'format' => 'markdown',
            'content_url' => null,
            'default_collection_id' => null,
            'source_url' => null,
            'source_title' => null,
            'is_template' => 0,
            'status' => 'draft',
            'visibility' => 'public',
            'view_count' => 0,
            'created_ymdhis' => $now,
            'utc_cycle' => 'creative',
            'triage_status' => 'untriaged',
            'triage_notes' => null,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
            'is_active' => 1,
            'deleted_ymdhis' => null,
            'content_sections' => null,
            'version_number' => 1,
            'file_path_from_root' => self::normPath((string) $fp),
            'file_last_modified_system_version' => $whenUpdated,
            'file_last_modified_utc' => $lm,
            'tags' => null,
            'dialog_notes' => null,
            'atom_mappings' => null,
            'category_mappings' => null,
            'content_events' => null,
            'hashtags' => null,
            'inbound_links' => null,
            'like_users' => null,
            'media_attachments' => null,
            'question_mappings' => null,
            'content_references' => null,
            'revision_history' => null,
            'share_users' => null,
            'tag_relationships' => null,
            'like_count' => 0,
            'share_count' => 0,
            'comment_count' => 0,
        );
    }

    /**
     * @param PDO_DB $db
     * @param string $tablePrefix
     * @param int $now
     * @param string $path
     * @return string|null content_id string
     */
    public static function getContentIdByPath($db, $tablePrefix, $path, $now)
    {
        $c = self::safeSqlIdentifier($tablePrefix . 'contents');
        $p = self::normPath($path);
        $sql = 'SELECT content_id FROM `' . $c . '` WHERE file_path_from_root = :p AND is_deleted = 0 LIMIT 1';
        $row = $db->fetchRow($sql, array('p' => $p));
        if (!$row) {
            return null;
        }
        return (string) $row['content_id'];
    }

    /**
     * @param PDO_DB $db
     * @param string $tablePrefix
     * @param string $filePath
     * @param int $now
     * @return string reference_object_id
     */
    public static function getOrCreateReferenceObjectId($db, $tablePrefix, $filePath, $now)
    {
        $r = self::safeSqlIdentifier($tablePrefix . 'reference_objects');
        $slug = self::refSlugForPath($filePath);
        $sql = 'SELECT reference_object_id FROM `' . $r . '` WHERE object_type = :ot AND object_slug = :sl AND is_deleted = 0 LIMIT 1';
        $row = $db->fetchRow($sql, array('ot' => self::REF_OBJECT_TYPE, 'sl' => $slug));
        if ($row) {
            return (string) $row['reference_object_id'];
        }
        $rid = self::nextId($db, $tablePrefix . 'reference_objects', 'reference_object_id');
        $full = self::normPath($filePath);
        $label = strlen($full) > 255 ? substr($full, 0, 255) : $full;
        $meta = json_encode(array('file_path_from_root' => $full), JSON_UNESCAPED_UNICODE);
        $ins = 'INSERT INTO `' . $r . '` (reference_object_id, object_type, object_slug, object_label, meta_json, '
            . 'is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis) VALUES ('
            . ':id, :ot, :sl, :lb, :mj, 0, NULL, :c1, :c2)';
        $db->query($ins, array(
            'id' => $rid,
            'ot' => self::REF_OBJECT_TYPE,
            'sl' => $slug,
            'lb' => $label,
            'mj' => $meta,
            'c1' => $now,
            'c2' => $now,
        ));
        return $rid;
    }

    /**
     * @param mixed $toVal
     * @return array 0 => right_object_type, 1 => right_object_id string
     */
    public static function resolveEdgeRight($db, $tablePrefix, $toVal, $now)
    {
        if ($toVal === null) {
            $rid = self::getOrCreateReferenceObjectId($db, $tablePrefix, '', $now);
            return array('reference_object', $rid);
        }
        $path = trim((string) $toVal);
        if ($path === '') {
            $rid = self::getOrCreateReferenceObjectId($db, $tablePrefix, '', $now);
            return array('reference_object', $rid);
        }
        $cid = self::getContentIdByPath($db, $tablePrefix, $path, $now);
        if ($cid !== null) {
            return array('content', $cid);
        }
        $rid = self::getOrCreateReferenceObjectId($db, $tablePrefix, $path, $now);
        return array('reference_object', $rid);
    }

    /**
     * @param PDO_DB $db
     * @param string $tablePrefix
     * @param int|string $contentId
     */
    public static function deleteSyncMetadata($db, $tablePrefix, $contentId)
    {
        $m = self::safeSqlIdentifier($tablePrefix . 'metadata');
        $sql = 'DELETE FROM `' . $m . '` WHERE entity_type = :et AND entity_id = :eid AND domain_id = 1 AND class_name = :cn';
        $db->query($sql, array(
            'et' => 'content',
            'eid' => $contentId,
            'cn' => self::SYNC_CLASS,
        ));
    }

    /**
     * @param PDO_DB $db
     * @param string $tablePrefix
     * @param int|string $contentId
     * @param string $propertyKey
     * @param string $propertyValue
     * @param int $now
     */
    public static function insertMetadataRow($db, $tablePrefix, $contentId, $propertyKey, $propertyValue, $now)
    {
        $m = self::safeSqlIdentifier($tablePrefix . 'metadata');
        $mid = self::nextId($db, $tablePrefix . 'metadata', 'metadata_id');
        $sql = 'INSERT INTO `' . $m . '` (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, '
            . 'property_value, created_ymdhis, updated_ymdhis, is_deleted, channel_id, parent_metadata_id, class_name, schema_ref) '
            . 'VALUES (:mid, :et, :eid, 1, :mt, :pk, :pv, :c1, :c2, 0, NULL, NULL, :cn, NULL)';
        $db->query($sql, array(
            'mid' => $mid,
            'et' => 'content',
            'eid' => $contentId,
            'mt' => 'lupopedia_header',
            'pk' => $propertyKey,
            'pv' => $propertyValue,
            'c1' => $now,
            'c2' => $now,
            'cn' => self::SYNC_CLASS,
        ));
    }

    /**
     * @param PDO_DB $db
     * @param string $tablePrefix
     * @param int|string $contentId
     * @param int $now
     */
    public static function softDeleteHeaderEdges($db, $tablePrefix, $contentId, $now)
    {
        $e = self::safeSqlIdentifier($tablePrefix . 'edges');
        $sql = 'UPDATE `' . $e . '` SET is_deleted = 1, deleted_ymdhis = :d, updated_ymdhis = :u '
            . 'WHERE left_object_type = :lot AND left_object_id = :lid AND edge_category = :ec AND is_deleted = 0';
        $db->query($sql, array(
            'd' => $now,
            'u' => $now,
            'lot' => 'content',
            'lid' => $contentId,
            'ec' => self::EDGE_CATEGORY,
        ));
    }

    /**
     * @param PDO_DB $db
     * @param string $tablePrefix
     * @param array $yamlData
     * @param int|string $contentId
     * @param int $now
     * @return int Number of edges inserted
     */
    public static function syncHeaderArtifactToDb($db, $tablePrefix, $yamlData, $contentId, $now)
    {
        $headers = self::extractLupopediaHeadersBlock($yamlData);
        self::deleteSyncMetadata($db, $tablePrefix, $contentId);

        foreach ($headers as $key => $val) {
            $pk = self::HDR_PREFIX . (string) $key;
            self::insertMetadataRow($db, $tablePrefix, $contentId, $pk, self::serializeValue($val), $now);
        }

        if (isset($yamlData['lupopedia.footer']) && is_array($yamlData['lupopedia.footer'])) {
            foreach ($yamlData['lupopedia.footer'] as $key => $val) {
                $pk = self::FTR_PREFIX . (string) $key;
                self::insertMetadataRow($db, $tablePrefix, $contentId, $pk, self::serializeValue($val), $now);
            }
        }

        $blockKeys = array_keys($yamlData);
        sort($blockKeys);
        foreach ($blockKeys as $blockKey) {
            if (in_array($blockKey, array('lupopedia.headers', 'lupopedia.footer', 'lupopedia.edges', 'lupopedia.history'), true)) {
                continue;
            }
            if (strncmp((string) $blockKey, 'lupopedia.', 10) !== 0) {
                continue;
            }
            $blk = isset($yamlData[$blockKey]) ? $yamlData[$blockKey] : null;
            if ($blk === null) {
                continue;
            }
            $pk = self::BLOCK_PREFIX . (string) $blockKey;
            self::insertMetadataRow($db, $tablePrefix, $contentId, $pk, self::serializeValue($blk), $now);
        }

        $outbound = array();
        if (isset($yamlData['lupopedia.edges']) && is_array($yamlData['lupopedia.edges'])) {
            $oe = isset($yamlData['lupopedia.edges']['outbound_edges']) ? $yamlData['lupopedia.edges']['outbound_edges'] : null;
            if (is_array($oe)) {
                foreach ($oe as $item) {
                    if (is_array($item)) {
                        $outbound[] = $item;
                    }
                }
            }
        }

        self::softDeleteHeaderEdges($db, $tablePrefix, $contentId, $now);
        $e = self::safeSqlIdentifier($tablePrefix . 'edges');

        $actorIdInt = null;
        if (isset($headers['actor_id']) && $headers['actor_id'] !== null && $headers['actor_id'] !== '') {
            $actorIdInt = (int) $headers['actor_id'];
        }
        $chInt = null;
        if (isset($headers['channel_id']) && $headers['channel_id'] !== null && $headers['channel_id'] !== '') {
            $chInt = (int) $headers['channel_id'];
        }

        $edgeCount = 0;
        foreach ($outbound as $ed) {
            $toV = isset($ed['to']) ? $ed['to'] : null;
            if ($toV === null || (is_string($toV) && trim($toV) === '')) {
                continue;
            }
            $etype = isset($ed['type']) ? (string) $ed['type'] : 'references';
            if ($etype === '') {
                $etype = 'references';
            }
            $weight = isset($ed['weight']) ? $ed['weight'] : 0.5;
            $wFloat = is_numeric($weight) ? (float) $weight : 0.5;
            $reason = isset($ed['reason']) ? $ed['reason'] : null;
            $reasonS = $reason !== null ? (string) $reason : null;
            if ($reasonS !== null && strlen($reasonS) > 255) {
                $reasonS = substr($reasonS, 0, 252) . '...';
            }

            $resolved = self::resolveEdgeRight($db, $tablePrefix, $toV, $now);
            $rt = $resolved[0];
            $rid = $resolved[1];
            $eid = self::nextId($db, $tablePrefix . 'edges', 'edge_id');
            $wScore = (int) max(0, min(100, (int) round($wFloat * 100)));
            $flareW = min(1.0, max(0.0, $wFloat));

            $sql = 'INSERT INTO `' . $e . '` (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, '
                . 'edge_type, edge_category, edge_description, channel_id, channel_key, domain_id, weight_score, sort_num, '
                . 'actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis, semantic_weight, relationship_type, '
                . 'bidirectional, context_scope, properties, flare_weight, flare_reason, flare_db_source, flare_auto_generated, '
                . 'flare_verified, flare_discovered_via) VALUES ('
                . ':eid, :lot, :lid, :rot, :rid, :et, :ec, NULL, :ch, NULL, 1, :ws, 0, :aid, 0, 0, :c1, :c2, :sw, '
                . '\'semantic\', 0, \'lupopedia_header_import\', NULL, :fw, :fr, \'header_import\', 1, 0, \'filesystem_yaml\')';
            $db->query($sql, array(
                'eid' => $eid,
                'lot' => 'content',
                'lid' => $contentId,
                'rot' => $rt,
                'rid' => $rid,
                'et' => $etype,
                'ec' => self::EDGE_CATEGORY,
                'ch' => $chInt,
                'ws' => $wScore,
                'aid' => $actorIdInt,
                'c1' => $now,
                'c2' => $now,
                'sw' => $wFloat,
                'fw' => $flareW,
                'fr' => $reasonS,
            ));
            $edgeCount++;
        }

        $c = self::safeSqlIdentifier($tablePrefix . 'contents');
        if (array_key_exists('lupopedia.history', $yamlData)) {
            $hist = $yamlData['lupopedia.history'];
            $revJson = null;
            if ($hist !== null) {
                $revJson = json_encode($hist, JSON_UNESCAPED_UNICODE);
            }
            $sql = 'UPDATE `' . $c . '` SET revision_history = :rh, updated_ymdhis = :u WHERE content_id = :cid';
            $db->query($sql, array('rh' => $revJson, 'u' => $now, 'cid' => $contentId));
        } else {
            $sql = 'UPDATE `' . $c . '` SET updated_ymdhis = :u WHERE content_id = :cid';
            $db->query($sql, array('u' => $now, 'cid' => $contentId));
        }

        return $edgeCount;
    }

    /**
     * @param PDO_DB $db
     * @param string $tablePrefix
     * @param int|string $contentId
     * @return array
     */
    public static function fetchMetadataRows($db, $tablePrefix, $contentId)
    {
        $m = self::safeSqlIdentifier($tablePrefix . 'metadata');
        $sql = 'SELECT metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, '
            . 'created_ymdhis, updated_ymdhis, channel_id, parent_metadata_id, class_name '
            . 'FROM `' . $m . '` WHERE entity_type = :et AND entity_id = :eid AND class_name = :cn AND is_deleted = 0 '
            . 'ORDER BY property_key ASC';
        return $db->fetchAll($sql, array('et' => 'content', 'eid' => $contentId, 'cn' => self::SYNC_CLASS));
    }

    /**
     * @param PDO_DB $db
     * @param string $tablePrefix
     * @param int|string $contentId
     * @return array
     */
    public static function fetchHeaderEdges($db, $tablePrefix, $contentId)
    {
        $e = self::safeSqlIdentifier($tablePrefix . 'edges');
        $sql = 'SELECT edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, '
            . 'semantic_weight, flare_weight, flare_reason, channel_id, actor_id, created_ymdhis '
            . 'FROM `' . $e . '` WHERE left_object_type = :lot AND left_object_id = :lid AND edge_category = :ec '
            . 'AND is_deleted = 0 ORDER BY edge_id ASC';
        return $db->fetchAll($sql, array('lot' => 'content', 'lid' => $contentId, 'ec' => self::EDGE_CATEGORY));
    }

    /**
     * @param PDO_DB $db
     * @param string $tablePrefix
     * @param string $rightType
     * @param string|int $rightId
     * @return string
     */
    public static function pathForRight($db, $tablePrefix, $rightType, $rightId)
    {
        if ($rightType === 'content') {
            $c = self::safeSqlIdentifier($tablePrefix . 'contents');
            $row = $db->fetchRow('SELECT file_path_from_root FROM `' . $c . '` WHERE content_id = :id LIMIT 1', array('id' => $rightId));
            if (!$row || !isset($row['file_path_from_root'])) {
                return (string) $rightId;
            }
            $v = $row['file_path_from_root'];
            return $v !== null && $v !== '' ? (string) $v : (string) $rightId;
        }
        if ($rightType === 'reference_object') {
            $r = self::safeSqlIdentifier($tablePrefix . 'reference_objects');
            $row = $db->fetchRow(
                'SELECT object_slug, meta_json, object_label FROM `' . $r . '` WHERE reference_object_id = :id LIMIT 1',
                array('id' => $rightId)
            );
            if (!$row) {
                return (string) $rightId;
            }
            $mj = isset($row['meta_json']) ? $row['meta_json'] : null;
            if ($mj !== null && $mj !== '') {
                $parsed = json_decode((string) $mj, true);
                if (is_array($parsed) && isset($parsed['file_path_from_root'])) {
                    return (string) $parsed['file_path_from_root'];
                }
            }
            if (isset($row['object_label']) && $row['object_label'] !== null && $row['object_label'] !== '') {
                return (string) $row['object_label'];
            }
            if (isset($row['object_slug'])) {
                return (string) $row['object_slug'];
            }
            return (string) $rightId;
        }
        return (string) $rightId;
    }

    /**
     * @param PDO_DB $db
     * @param string $tablePrefix
     * @param array $rows
     * @return array
     */
    public static function outboundEdgesFromDbRows($db, $tablePrefix, $rows)
    {
        $out = array();
        foreach ($rows as $row) {
            $rt = $row['right_object_type'];
            $ri = $row['right_object_id'];
            $et = $row['edge_type'];
            $sw = isset($row['semantic_weight']) ? $row['semantic_weight'] : null;
            $fw = isset($row['flare_weight']) ? $row['flare_weight'] : null;
            $fr = isset($row['flare_reason']) ? $row['flare_reason'] : null;
            if ($fw !== null && is_numeric($fw)) {
                $w = (float) $fw;
            } elseif ($sw !== null && is_numeric($sw)) {
                $w = (float) $sw;
            } else {
                $w = 0.5;
            }
            $toPath = self::pathForRight($db, $tablePrefix, (string) $rt, $ri);
            $item = array('to' => $toPath, 'type' => (string) $et, 'weight' => $w);
            if ($fr !== null && $fr !== '') {
                $item['reason'] = (string) $fr;
            }
            $out[] = $item;
        }
        return $out;
    }

    /**
     * @param PDO_DB $db
     * @param string $tablePrefix
     * @param array $contentRow Assoc lupo_contents row
     * @return array Ordered yaml blocks
     */
    public static function buildYamlDataFromDb($db, $tablePrefix, $contentRow)
    {
        $cid = (string) $contentRow['content_id'];
        $metaRows = self::fetchMetadataRows($db, $tablePrefix, $cid);
        $edgeRows = self::fetchHeaderEdges($db, $tablePrefix, $cid);

        $headers = array();
        $footer = array();
        $extraBlocks = array();

        foreach ($metaRows as $row) {
            $pk = $row['property_key'];
            $pv = $row['property_value'];
            if (strncmp($pk, self::HDR_PREFIX, strlen(self::HDR_PREFIX)) === 0) {
                $headers[substr($pk, strlen(self::HDR_PREFIX))] = self::deserializeValue((string) $pv);
            } elseif (strncmp($pk, self::FTR_PREFIX, strlen(self::FTR_PREFIX)) === 0) {
                $footer[substr($pk, strlen(self::FTR_PREFIX))] = self::deserializeValue((string) $pv);
            } elseif (strncmp($pk, self::BLOCK_PREFIX, strlen(self::BLOCK_PREFIX)) === 0) {
                $extraBlocks[substr($pk, strlen(self::BLOCK_PREFIX))] = self::deserializeValue((string) $pv);
            }
        }

        if ((!isset($headers['file_path_from_root']) || $headers['file_path_from_root'] === '') && isset($contentRow['file_path_from_root'])) {
            $headers['file_path_from_root'] = $contentRow['file_path_from_root'];
        }
        if (isset($contentRow['title']) && $contentRow['title'] !== '' && !isset($headers['title'])) {
            $headers['title'] = $contentRow['title'];
        }
        foreach (array('channel_id', 'actor_id') as $col) {
            if (isset($contentRow[$col]) && $contentRow[$col] !== null && !isset($headers[$col])) {
                $headers[$col] = (int) $contentRow[$col];
            }
        }
        if (isset($contentRow['tags']) && $contentRow['tags'] !== null && !isset($headers['tags'])) {
            $headers['tags'] = is_string($contentRow['tags']) ? json_decode($contentRow['tags'], true) : $contentRow['tags'];
        }
        if (!isset($headers['web_path']) && isset($headers['file_path_from_root'])) {
            $fp = (string) $headers['file_path_from_root'];
            $headers['web_path'] = 'http://www.lupopedia.com/lupopedia/' . $fp;
        }
        if (!isset($headers['when_updated']) || $headers['when_updated'] === '' || $headers['when_updated'] === null) {
            $wu = isset($contentRow['updated_ymdhis']) ? $contentRow['updated_ymdhis'] : (isset($contentRow['created_ymdhis']) ? $contentRow['created_ymdhis'] : null);
            if ($wu !== null) {
                $headers['when_updated'] = (string) (int) $wu;
            }
        }
        if (!isset($headers['last_modified_utc']) || $headers['last_modified_utc'] === '' || $headers['last_modified_utc'] === null) {
            $lm = isset($contentRow['file_last_modified_utc']) ? $contentRow['file_last_modified_utc'] : (isset($contentRow['updated_ymdhis']) ? $contentRow['updated_ymdhis'] : null);
            if ($lm !== null) {
                $headers['last_modified_utc'] = (string) (int) $lm;
            }
        }
        $headers['content_id'] = (int) $cid;

        $outbound = self::outboundEdgesFromDbRows($db, $tablePrefix, $edgeRows);
        $edgesBlock = null;
        if (count($outbound) > 0) {
            $edgesBlock = array('outbound_edges' => $outbound);
        }

        $histBlock = null;
        if (isset($contentRow['revision_history']) && $contentRow['revision_history'] !== null && $contentRow['revision_history'] !== '') {
            $rh = $contentRow['revision_history'];
            if (is_string($rh)) {
                $histBlock = json_decode($rh, true);
            } elseif (is_array($rh)) {
                $histBlock = $rh;
            }
        }

        $ordered = array();
        $ordered['lupopedia.headers'] = $headers;
        $ek = array_keys($extraBlocks);
        sort($ek);
        foreach ($ek as $k) {
            $ordered[$k] = $extraBlocks[$k];
        }
        if ($edgesBlock !== null) {
            $ordered['lupopedia.edges'] = $edgesBlock;
        }
        if ($histBlock !== null) {
            $ordered['lupopedia.history'] = $histBlock;
        }
        if (count($footer) > 0) {
            $ordered['lupopedia.footer'] = $footer;
        }

        return $ordered;
    }

    /**
     * @param mixed $val
     * @return bool
     */
    public static function headerValuePresent($val)
    {
        if ($val === null) {
            return false;
        }
        if (is_string($val) && trim($val) === '') {
            return false;
        }
        if (is_array($val) && count($val) === 0) {
            return false;
        }
        return true;
    }

    /**
     * @param string $filePath Absolute or relative path to .md
     * @param string $repoRoot
     * @param bool $checkDb
     * @return array errors, warnings, valid, header_data
     */
    public static function validateFile($filePath, $repoRoot, $checkDb)
    {
        $errors = array();
        $warnings = array();
        $headerData = null;

        if (!is_file($filePath)) {
            $errors[] = $filePath . ': File not found';
            return array('errors' => $errors, 'warnings' => $warnings, 'valid' => false, 'header_data' => null);
        }
        $raw = file_get_contents($filePath);
        if ($raw === false) {
            $errors[] = $filePath . ': Could not read file';
            return array('errors' => $errors, 'warnings' => $warnings, 'valid' => false, 'header_data' => null);
        }
        if (strncmp($raw, '---', 3) !== 0) {
            $errors[] = $filePath . ': File must start with --- (line 1)';
            return array('errors' => $errors, 'warnings' => $warnings, 'valid' => false, 'header_data' => null);
        }
        $headerEnd = strpos($raw, '---', 3);
        if ($headerEnd === false) {
            $errors[] = $filePath . ': Missing closing --- for YAML header';
            return array('errors' => $errors, 'warnings' => $warnings, 'valid' => false, 'header_data' => null);
        }
        $headerContent = substr($raw, 3, $headerEnd - 3);
        if (!function_exists('yaml_parse')) {
            $errors[] = $filePath . ': yaml_parse() unavailable; install php-yaml for validation parity';
            return array('errors' => $errors, 'warnings' => $warnings, 'valid' => false, 'header_data' => null);
        }
        $headerData = @yaml_parse($headerContent);
        if (!is_array($headerData)) {
            $errors[] = $filePath . ': Invalid YAML in header';
            return array('errors' => $errors, 'warnings' => $warnings, 'valid' => false, 'header_data' => null);
        }

        if (!isset($headerData['lupopedia.headers']) || !is_array($headerData['lupopedia.headers'])) {
            $errors[] = $filePath . ': Missing lupopedia.headers section';
            return array('errors' => $errors, 'warnings' => $warnings, 'valid' => false, 'header_data' => $headerData);
        }
        $headers = $headerData['lupopedia.headers'];

        if (isset($headers['version_when_written'])) {
            $warnings[] = $filePath . ': version_when_written is deprecated, use when_updated';
        }
        if (isset($headers['lupopedia.version'])) {
            $warnings[] = $filePath . ': lupopedia.version is deprecated in headers';
        }
        if (isset($headers['system_version'])) {
            $errors[] = $filePath . ': Deprecated field system_version in lupopedia.headers (remove)';
        }

        foreach (self::$requiredHeaderFields as $field) {
            if (!isset($headers[$field]) || !self::headerValuePresent($headers[$field])) {
                $errors[] = $filePath . ': Missing or empty required header field ' . $field;
            }
        }

        $tags = isset($headers['tags']) ? $headers['tags'] : null;
        if ($tags !== null && !is_array($tags)) {
            $errors[] = $filePath . ': tags must be a YAML list';
        }

        $fn = isset($headers['federation_node_id']) ? $headers['federation_node_id'] : null;
        if (self::headerValuePresent($fn)) {
            if (!is_int($fn) && !(is_string($fn) && ctype_digit(trim((string) $fn)))) {
                $errors[] = $filePath . ': federation_node_id must be an integer';
            }
        }

        $tid = isset($headers['thread_id']) ? $headers['thread_id'] : null;
        if (self::headerValuePresent($tid)) {
            $ts = trim((string) $tid);
            if (!preg_match('/^[a-z0-9][a-z0-9-]*$/', $ts)) {
                $errors[] = $filePath . ': thread_id must match ^[a-z0-9][a-z0-9-]*$ (got ' . $ts . ')';
            }
        }

        foreach (array('when_updated', 'last_modified_utc') as $tsName) {
            $tsVal = isset($headers[$tsName]) ? $headers[$tsName] : null;
            $tsS = $tsVal !== null ? trim((string) $tsVal) : '';
            if ($tsS !== '' && !preg_match('/^\d{14}$/', $tsS)) {
                $errors[] = $filePath . ': ' . $tsName . ' must be UTC YYYYMMDDHHIISS (14 digits)';
            }
        }

        if (isset($headers['web_path'])) {
            $wp = (string) $headers['web_path'];
            $fed = isset($headers['federation_node_id']) ? (int) $headers['federation_node_id'] : 1;
            $isUrl = (strncmp($wp, 'http://', 7) === 0 || strncmp($wp, 'https://', 8) === 0);
            if ($fed <= 1 && strpos($wp, '/lupopedia/') === false) {
                $warnings[] = $filePath . ': web_path should include /lupopedia/ subdirectory for federation_node_id ' . $fed;
            }
            if ($fed >= 2 && !$isUrl) {
                $errors[] = $filePath . ': federation_node_id >= 2 requires web_path to be http(s) URL';
            }
        }

        $cid = isset($headers['content_id']) ? $headers['content_id'] : null;
        if ($cid === null || $cid === '' || (is_string($cid) && trim($cid) === '')) {
            $warnings[] = $filePath . ': No content_id - file not linked to lupo_contents. Import: php scripts/import_content.php (optional --write-back to persist content_id in file)';
        } else {
            $s = trim((string) $cid);
            if ($s !== '' && !ctype_digit($s)) {
                $warnings[] = $filePath . ': content_id should be numeric (got ' . $cid . ')';
            }
        }

        if (isset($headerData['lupopedia.footer']) && is_array($headerData['lupopedia.footer'])) {
            $footer = $headerData['lupopedia.footer'];
            if (isset($footer['last_verified_by'])) {
                $warnings[] = $filePath . ': last_verified_by is deprecated, use verified_by structure';
            }
            if (isset($footer['last_verified'])) {
                $lvRaw = $footer['last_verified'];
                $lvDigits = preg_replace('/[^0-9]/', '', (string) $lvRaw);
                if (strlen($lvDigits) === 8) {
                    $lvDigits .= '000000';
                }
                if (preg_match('/^\d{14}$/', $lvDigits) && (int) $lvDigits < 20260301000000) {
                    $warnings[] = $filePath . ': Stale footer last_verified (before 20260301000000 UTC); revalidation recommended';
                }
                if (!isset($footer['verified_by']) || !is_array($footer['verified_by'])) {
                    $errors[] = $filePath . ': footer has last_verified but missing verified_by structure';
                } else {
                    $vb = $footer['verified_by'];
                    foreach (array('identity_type', 'actor_id') as $req) {
                        if (!isset($vb[$req])) {
                            $errors[] = $filePath . ': missing verified_by.' . $req;
                        }
                    }
                }
            }
        }

        $relPath = str_replace('\\', '/', $filePath);
        if ($repoRoot !== '') {
            $rr = rtrim(str_replace('\\', '/', $repoRoot), '/');
            if (strncmp($relPath, $rr, strlen($rr)) === 0) {
                $relPath = ltrim(substr($relPath, strlen($rr)), '/');
            }
        }
        if (isset($headerData['lupopedia.edges']) && is_array($headerData['lupopedia.edges'])) {
            $oe = isset($headerData['lupopedia.edges']['outbound_edges']) ? $headerData['lupopedia.edges']['outbound_edges'] : array();
            if (is_array($oe)) {
                foreach ($oe as $edge) {
                    if (!is_array($edge) || !isset($edge['to'])) {
                        continue;
                    }
                    $to = (string) $edge['to'];
                    $target = rtrim($repoRoot, '/\\') . '/' . self::normPath($to);
                    if (!is_file($target)) {
                        $warnings[] = $filePath . ': edge target path not found on disk: ' . $to;
                    }
                }
            }
        }

        $valid = count($errors) === 0;

        if ($checkDb && $valid) {
            self::appendCheckDbWarnings($warnings, $filePath, $headerData, $repoRoot);
        }

        return array('errors' => $errors, 'warnings' => $warnings, 'valid' => $valid, 'header_data' => $headerData);
    }

    /**
     * @param array $warnings
     * @param string $filePath
     * @param array $headerData
     * @param string $repoRoot
     */
    public static function appendCheckDbWarnings(&$warnings, $filePath, $headerData, $repoRoot)
    {
        $headers = isset($headerData['lupopedia.headers']) ? $headerData['lupopedia.headers'] : array();
        if (!is_array($headers)) {
            return;
        }
        $cid = isset($headers['content_id']) ? $headers['content_id'] : null;
        if ($cid === null || $cid === '') {
            $warnings[] = $filePath . ': --check-db skipped: no content_id (import first)';
            return;
        }
        if (!ctype_digit(trim((string) $cid))) {
            $warnings[] = $filePath . ': --check-db skipped: content_id not numeric';
            return;
        }
        $cidInt = (int) $cid;
        try {
            if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
                $warnings[] = $filePath . ': --check-db skipped: config not loaded';
                return;
            }
            $db = DatabaseFactory::getConnection();
            $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
            $ct = HeaderDbSync::safeSqlIdentifier($prefix . 'contents');
            $et = HeaderDbSync::safeSqlIdentifier($prefix . 'edges');
            $row = $db->fetchRow('SELECT revision_history FROM `' . $ct . '` WHERE content_id = :id AND is_deleted = 0 LIMIT 1', array('id' => $cidInt));
            $dbRh = $row && isset($row['revision_history']) ? $row['revision_history'] : null;
            $erow = $db->fetchRow(
                'SELECT COUNT(*) AS c FROM `' . $et . '` WHERE left_object_type = :lot AND left_object_id = :lid AND edge_category = :ec AND is_deleted = 0',
                array('lot' => 'content', 'lid' => $cidInt, 'ec' => HeaderDbSync::EDGE_CATEGORY)
            );
            $edgeCount = $erow && isset($erow['c']) ? (int) $erow['c'] : 0;

            $outbound = array();
            if (isset($headerData['lupopedia.edges']['outbound_edges']) && is_array($headerData['lupopedia.edges']['outbound_edges'])) {
                $outbound = $headerData['lupopedia.edges']['outbound_edges'];
            }
            $fileHist = isset($headerData['lupopedia.history']) ? $headerData['lupopedia.history'] : null;

            if (count($outbound) > 0 && $edgeCount === 0) {
                $warnings[] = $filePath . ': File has outbound_edges but DB has 0 lupo_edges (lupopedia_header). Run python scripts/import_content.py or php scripts/import_content.php';
            }
            if ($fileHist !== null) {
                $emptyDb = ($dbRh === null || $dbRh === '');
                if (is_string($dbRh) && trim($dbRh) === '') {
                    $emptyDb = true;
                }
                if ($emptyDb) {
                    $warnings[] = $filePath . ': File has lupopedia.history but revision_history empty in DB. Run python scripts/import_content.py or php scripts/import_content.php';
                }
            }
        } catch (Exception $e) {
            $warnings[] = $filePath . ': --check-db failed: ' . $e->getMessage();
        }
    }

    /**
     * @param string $yamlText Original inner YAML (no delimiters)
     * @param string|int $contentId
     * @return string
     */
    public static function updateContentIdInYamlText($yamlText, $contentId)
    {
        $lines = preg_split('/\r\n|\r|\n/', $yamlText);
        $findDotted = -1;
        for ($idx = 0; $idx < count($lines); $idx++) {
            if (preg_match('/^lupopedia\.headers\s*:\s*$/', trim($lines[$idx]))) {
                $findDotted = $idx;
                break;
            }
        }
        $findNested = array(-1, -1);
        for ($idx = 0; $idx < count($lines); $idx++) {
            if (preg_match('/^lupopedia\s*:\s*$/', trim($lines[$idx]))) {
                $findNested[0] = $idx;
            }
            if ($findNested[0] >= 0 && preg_match('/^\s+headers\s*:\s*$/', $lines[$idx])) {
                $findNested[1] = $idx;
                break;
            }
        }

        $updateWithBlock = function ($blockStartIdx) use (&$lines, $contentId) {
            $baseIndent = '';
            if (preg_match('/^(\s*)/', $lines[$blockStartIdx], $m)) {
                $baseIndent = $m[1];
            }
            $childIndent = $baseIndent . '  ';
            $contentIdLineIdx = null;
            for ($j = $blockStartIdx + 1; $j < count($lines); $j++) {
                $ln = $lines[$j];
                if (preg_match('/^[A-Za-z0-9_].*:\s*$/', trim($ln)) && strpos(trim($ln), 'lupopedia') === 0 && strpos(trim($ln), 'lupopedia.') === 0) {
                    break;
                }
                if (preg_match('/^[A-Za-z0-9_].*:\s*$/', trim($ln)) && trim($ln) !== '' && $ln[0] !== ' ' && $ln[0] !== "\t") {
                    break;
                }
                if (preg_match('/^\s*content_id\s*:\s*/', $ln)) {
                    $contentIdLineIdx = $j;
                    break;
                }
            }
            $lineEnding = "\n";
            if ($contentIdLineIdx !== null) {
                if (substr($lines[$contentIdLineIdx], -1) === "\r") {
                    $lineEnding = "\r\n";
                }
                if (preg_match('/^(\s*)/', $lines[$contentIdLineIdx], $mi)) {
                    $indent = $mi[1];
                    $lines[$contentIdLineIdx] = $indent . 'content_id: ' . (int) $contentId . $lineEnding;
                }
                return true;
            }
            $insertAfterIdx = $blockStartIdx;
            for ($j = $blockStartIdx + 1; $j < count($lines); $j++) {
                $ln = $lines[$j];
                if (strlen($ln) > 0 && ($ln[0] !== ' ' && $ln[0] !== "\t")) {
                    break;
                }
                if (preg_match('/^\s+[^\s#].*:\s*/', $ln)) {
                    $insertAfterIdx = $j;
                }
            }
            array_splice($lines, $insertAfterIdx + 1, 0, array($childIndent . 'content_id: ' . (int) $contentId));
            return true;
        };

        if ($findDotted >= 0) {
            $updateWithBlock($findDotted);
            return implode("\n", $lines);
        }
        if ($findNested[0] >= 0 && $findNested[1] >= 0) {
            $updateWithBlock($findNested[1]);
            return implode("\n", $lines);
        }
        throw new InvalidArgumentException('Could not locate lupopedia.headers block in YAML front matter text');
    }

    /**
     * @param array $ordered From buildYamlDataFromDb
     * @return string YAML without leading --- 
     */
    public static function dumpYamlOrderedBlocks($ordered)
    {
        $parts = array();
        foreach ($ordered as $topKey => $topVal) {
            $parts[] = self::emitYamlKeyValue($topKey, $topVal, 0);
        }
        return implode("\n", $parts) . "\n";
    }

    /**
     * @param string $key
     * @param mixed $val
     * @param int $indent
     * @return string
     */
    public static function emitYamlKeyValue($key, $val, $indent)
    {
        $pad = str_repeat(' ', $indent);
        if (is_array($val)) {
            if (self::isSequentialArray($val)) {
                $lines = array($pad . $key . ':');
                foreach ($val as $item) {
                    if (is_array($item) && !self::isSequentialArray($item)) {
                        $firstKey = true;
                        foreach ($item as $ik => $iv) {
                            $suffix = (string) $ik . ': ' . self::emitYamlScalarOneLine($iv);
                            if ($firstKey) {
                                $lines[] = $pad . '  - ' . $suffix;
                                $firstKey = false;
                            } else {
                                $lines[] = $pad . '    ' . $suffix;
                            }
                        }
                    } else {
                        $lines[] = $pad . '  - ' . self::emitYamlScalarOneLine($item);
                    }
                }
                return implode("\n", $lines);
            }
            $lines = array($pad . $key . ':');
            foreach ($val as $k => $v) {
                $lines[] = self::emitYamlKeyValue((string) $k, $v, $indent + 2);
            }
            return implode("\n", $lines);
        }
        return $pad . $key . ': ' . self::emitYamlScalarInlineOrBlock($val, $indent);
    }

    /**
     * @param array $arr
     * @return bool
     */
    public static function isSequentialArray($arr)
    {
        if (!is_array($arr)) {
            return false;
        }
        $i = 0;
        foreach (array_keys($arr) as $k) {
            if ($k !== $i) {
                return false;
            }
            $i++;
        }
        return true;
    }

    /**
     * Single-line YAML scalar or inline structure (for list items).
     *
     * @param mixed $val
     * @return string
     */
    public static function emitYamlScalarOneLine($val)
    {
        if ($val === null) {
            return 'null';
        }
        if (is_bool($val)) {
            return $val ? 'true' : 'false';
        }
        if (is_int($val) || is_float($val)) {
            return (string) $val;
        }
        if (is_string($val)) {
            return '"' . str_replace(array('\\', '"'), array('\\\\', '\\"'), $val) . '"';
        }
        if (is_array($val)) {
            return self::emitYamlScalarInlineOrBlock($val, 0);
        }
        return '"' . str_replace(array('\\', '"'), array('\\\\', '\\"'), (string) $val) . '"';
    }

    /**
     * @param mixed $val
     * @param int $indent
     * @return string without leading indent
     */
    public static function emitYamlScalarInlineOrBlock($val, $indent)
    {
        if ($val === null) {
            return 'null';
        }
        if (is_bool($val)) {
            return $val ? 'true' : 'false';
        }
        if (is_int($val) || is_float($val)) {
            return (string) $val;
        }
        if (is_string($val)) {
            if (preg_match('/[\n\r:#\[\]{},"\'@`]/', $val) || strpos($val, '  ') !== false) {
                return '"' . str_replace(array('\\', '"'), array('\\\\', '\\"'), $val) . '"';
            }
            if ($val === '' || strpos($val, ' ') !== false || strpos($val, ':') !== false) {
                return '"' . str_replace(array('\\', '"'), array('\\\\', '\\"'), $val) . '"';
            }
            return $val;
        }
        if (is_array($val)) {
            if (self::isSequentialArray($val)) {
                $inner = array();
                foreach ($val as $item) {
                    $inner[] = self::emitYamlScalarInlineOrBlock($item, $indent);
                }
                return '[' . implode(', ', $inner) . ']';
            }
            $pad = str_repeat(' ', $indent);
            $lines = array();
            foreach ($val as $k => $v) {
                $lines[] = self::emitYamlKeyValue((string) $k, $v, 0);
            }
            return "\n" . $pad . implode("\n" . $pad, $lines);
        }
        return '"' . (string) $val . '"';
    }
}
