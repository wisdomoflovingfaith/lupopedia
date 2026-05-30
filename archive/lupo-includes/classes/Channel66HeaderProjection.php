<?php
/**
 * Channel66HeaderProjection
 *
 * Builds and writes lupo_metadata row trees for Channel 66 LUPOPEDIA HEADERS P0.
 * Implements:
 * - deterministic entity_id
 * - full replace semantics for accept/warn and reject
 * - conflict_flagged minimal root-state update without deleting authoritative content
 */

class Channel66HeaderProjection
{
    /** @var PDO_DB */
    private $db;
    /** @var string */
    private $table_prefix;
    /** @var string */
    private $metadataTable;

    public function __construct($db = null)
    {
        $this->db = $db ? $db : lupo_get_db();
        $this->table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $this->metadataTable = $this->table_prefix . 'metadata';
    }

    private function nowYmdHis()
    {
        return (int)gmdate('YmdHis');
    }

    /**
     * Deterministic entity_id from file_path_from_root.
     *
     * @param string $filePathFromRoot
     * @return int
     */
    public function computeEntityId($filePathFromRoot)
    {
        $s = (string)$filePathFromRoot;
        // 15 hex chars => fits safely in 64-bit signed range.
        $hex = substr(md5($s), 0, 15);
        $id = hexdec($hex);
        // Ensure integer.
        return (int)$id;
    }

    private function encodeJson($value)
    {
        if (is_null($value)) {
            return null;
        }
        return json_encode($value, JSON_UNESCAPED_SLASHES);
    }

    private function allocNextMetadataId(&$nextId)
    {
        $id = $nextId;
        $nextId = $nextId + 1;
        return $id;
    }

    private function getBaseNextMetadataId()
    {
        $sql = "SELECT COALESCE(MAX(metadata_id), 0) + 1 FROM {$this->metadataTable}";
        $n = $this->db->fetchOne($sql, array());
        if ($n === null) {
            return 1;
        }
        return (int)$n;
    }

    private function hardDeleteEntity($entityId)
    {
        $sql = "DELETE FROM {$this->metadataTable}
                WHERE entity_type = :et AND entity_id = :eid AND domain_id IS NULL";
        // PDO_DB does not provide execute(); use query() for write statements.
        $this->db->query($sql, array(
            'et' => 'channel66_artifact',
            'eid' => $entityId,
        ));
    }

    /**
     * Upsert a root-level property row (or any property key row) by property_key uniqueness.
     * Unique index is on (entity_type, entity_id, domain_id, property_key), so domain_id is fixed to NULL.
     *
     * @param int $entityId
     * @param int $rootMetadataId
     * @param string $propertyKey
     * @param mixed $propertyValue
     */
    private function upsertProperty($entityId, $rootMetadataId, $propertyKey, $propertyValue)
    {
        $sql = "SELECT metadata_id FROM {$this->metadataTable}
                WHERE entity_type = :et AND entity_id = :eid AND domain_id IS NULL AND property_key = :pk AND is_deleted = 0
                LIMIT 1";
        $row = $this->db->fetchRow($sql, array(
            'et' => 'channel66_artifact',
            'eid' => $entityId,
            'pk' => $propertyKey,
        ));
        $now = $this->nowYmdHis();

        if ($row && isset($row['metadata_id'])) {
            $mid = (int)$row['metadata_id'];
            $this->db->update(
                $this->metadataTable,
                array(
                    'property_value' => $propertyValue,
                    'updated_ymdhis' => $now,
                ),
                'metadata_id = :mid',
                array('mid' => $mid)
            );
            return;
        }

        $nextId = $this->getBaseNextMetadataId();
        $nextIdUsed = $nextId;

        // Insert with explicit metadata_id.
        $this->db->insert($this->metadataTable, array(
            'metadata_id' => $nextIdUsed,
            'entity_type' => 'channel66_artifact',
            'entity_id' => $entityId,
            'domain_id' => null,
            'meta_type' => 'property',
            'property_key' => $propertyKey,
            'property_value' => $propertyValue,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
            'deleted_ymdhis' => null,
            'channel_id' => 66,
            'parent_metadata_id' => $rootMetadataId,
            'class_name' => 'lupopedia_property',
            'schema_ref' => null,
        ));
    }

    /**
     * Write a full authoritative tree: root + blocks + properties + edge nodes.
     *
     * @param int $entityId
     * @param int $channelId
     * @param string $validationStatus ingested or rejected
     * @param array $rootExtraProps map property_key => property_value strings/JSON
     * @param array $headerProperties classification output (property_key => property_value)
     * @param array|null $edgesParsed edges block parsed from YAML
     * @param int|null $threadId optional
     */
    public function writeFullTree($entityId, $channelId, $validationStatus, $rootExtraProps, $headerProperties, $edgesParsed)
    {
        $now = $this->nowYmdHis();
        $nextId = $this->getBaseNextMetadataId();

        $rootMetadataId = $this->allocNextMetadataId($nextId);

        // Replace semantics: hard delete all existing rows.
        $this->hardDeleteEntity($entityId);

        // Root row
        $this->db->insert($this->metadataTable, array(
            'metadata_id' => $rootMetadataId,
            'entity_type' => 'channel66_artifact',
            'entity_id' => $entityId,
            'domain_id' => null,
            'meta_type' => 'lupopedia_header',
            'property_key' => '__root__',
            'property_value' => '1',
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
            'deleted_ymdhis' => null,
            'channel_id' => $channelId,
            'parent_metadata_id' => null,
            'class_name' => 'lupopedia_header_root',
            'schema_ref' => null,
        ));

        // Root properties
        $rootProps = array();
        $rootProps['validation_status'] = $validationStatus;
        foreach ($rootExtraProps as $k => $v) {
            $rootProps[(string)$k] = $v;
        }
        foreach ($rootProps as $pk => $pv) {
            $pid = $this->allocNextMetadataId($nextId);
            $this->db->insert($this->metadataTable, array(
                'metadata_id' => $pid,
                'entity_type' => 'channel66_artifact',
                'entity_id' => $entityId,
                'domain_id' => null,
                'meta_type' => 'property',
                'property_key' => $pk,
                'property_value' => $pv,
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'is_deleted' => 0,
                'deleted_ymdhis' => null,
                'channel_id' => $channelId,
                'parent_metadata_id' => $rootMetadataId,
                'class_name' => 'lupopedia_property',
                'schema_ref' => null,
            ));
        }

        // Reject minimal projections are root-only: no blocks.
        if ($validationStatus === 'rejected') {
            return;
        }

        // lupopedia.headers block row
        $headersBlockId = $this->allocNextMetadataId($nextId);
        $this->db->insert($this->metadataTable, array(
            'metadata_id' => $headersBlockId,
            'entity_type' => 'channel66_artifact',
            'entity_id' => $entityId,
            'domain_id' => null,
            'meta_type' => 'block',
            'property_key' => 'lupopedia.headers',
            'property_value' => '',
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
            'deleted_ymdhis' => null,
            'channel_id' => $channelId,
            'parent_metadata_id' => $rootMetadataId,
            'class_name' => 'lupopedia_block',
            'schema_ref' => null,
        ));

        foreach ($headerProperties as $pk => $pv) {
            $propId = $this->allocNextMetadataId($nextId);
            $this->db->insert($this->metadataTable, array(
                'metadata_id' => $propId,
                'entity_type' => 'channel66_artifact',
                'entity_id' => $entityId,
                'domain_id' => null,
                'meta_type' => 'property',
                'property_key' => $pk,
                'property_value' => $pv,
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'is_deleted' => 0,
                'deleted_ymdhis' => null,
                'channel_id' => $channelId,
                'parent_metadata_id' => $headersBlockId,
                'class_name' => 'lupopedia_property',
                'schema_ref' => null,
            ));
        }

        // lupopedia.edges block row (optional)
        if (is_array($edgesParsed) && !empty($edgesParsed)) {
            $edgesBlockId = $this->allocNextMetadataId($nextId);
            $this->db->insert($this->metadataTable, array(
                'metadata_id' => $edgesBlockId,
                'entity_type' => 'channel66_artifact',
                'entity_id' => $entityId,
                'domain_id' => null,
                'meta_type' => 'block',
                'property_key' => 'lupopedia.edges',
                'property_value' => '',
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'is_deleted' => 0,
                'deleted_ymdhis' => null,
                'channel_id' => $channelId,
                'parent_metadata_id' => $rootMetadataId,
                'class_name' => 'lupopedia_block',
                'schema_ref' => null,
            ));

            $edges = $edgesParsed;
            $i = 0;
            foreach ($edges as $edge) {
                $edgeId = $this->allocNextMetadataId($nextId);
                $this->db->insert($this->metadataTable, array(
                    'metadata_id' => $edgeId,
                    'entity_type' => 'channel66_artifact',
                    'entity_id' => $entityId,
                    'domain_id' => null,
                    'meta_type' => 'edge',
                    'property_key' => 'edge_' . $i,
                    'property_value' => $this->encodeJson($edge),
                    'created_ymdhis' => $now,
                    'updated_ymdhis' => $now,
                    'is_deleted' => 0,
                    'deleted_ymdhis' => null,
                    'channel_id' => $channelId,
                    'parent_metadata_id' => $edgesBlockId,
                    'class_name' => 'lupopedia_edge',
                    'schema_ref' => null,
                ));
                $i++;
            }
        }
    }

    /**
     * Write conflict_flagged root-level properties without deleting authoritative content.
     *
     * @param int $entityId
     * @param int $channelId
     * @param string $conflictType
     * @param string $conflictReason
     */
    public function writeConflictFlagged($entityId, $channelId, $conflictType, $conflictReason)
    {
        $now = $this->nowYmdHis();

        // Try to find existing root.
        $sql = "SELECT metadata_id FROM {$this->metadataTable}
                WHERE entity_type = :et AND entity_id = :eid AND domain_id IS NULL AND class_name = 'lupopedia_header_root'
                  AND property_key = '__root__' AND is_deleted = 0
                LIMIT 1";
        $row = $this->db->fetchRow($sql, array(
            'et' => 'channel66_artifact',
            'eid' => $entityId,
        ));

        if ($row && isset($row['metadata_id'])) {
            $rootId = (int)$row['metadata_id'];

            // Update validation_status
            $this->upsertProperty($entityId, $rootId, 'validation_status', 'conflict_flagged');
            // Insert/update conflict properties
            $this->upsertProperty($entityId, $rootId, 'conflict_type', $conflictType);
            $this->upsertProperty($entityId, $rootId, 'conflict_reason', $conflictReason);
            return;
        }

        // No root exists yet: create minimal root-only state.
        $rootMetadataId = $this->getBaseNextMetadataId();
        $this->db->insert($this->metadataTable, array(
            'metadata_id' => $rootMetadataId,
            'entity_type' => 'channel66_artifact',
            'entity_id' => $entityId,
            'domain_id' => null,
            'meta_type' => 'lupopedia_header',
            'property_key' => '__root__',
            'property_value' => '1',
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
            'deleted_ymdhis' => null,
            'channel_id' => $channelId,
            'parent_metadata_id' => null,
            'class_name' => 'lupopedia_header_root',
            'schema_ref' => null,
        ));

        // Insert root state properties.
        $pid = $rootMetadataId + 1;
        $stateProps = array(
            'validation_status' => 'conflict_flagged',
            'conflict_type' => $conflictType,
            'conflict_reason' => $conflictReason,
        );
        foreach ($stateProps as $pk => $pv) {
            $this->db->insert($this->metadataTable, array(
                'metadata_id' => $pid,
                'entity_type' => 'channel66_artifact',
                'entity_id' => $entityId,
                'domain_id' => null,
                'meta_type' => 'property',
                'property_key' => $pk,
                'property_value' => $pv,
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'is_deleted' => 0,
                'deleted_ymdhis' => null,
                'channel_id' => $channelId,
                'parent_metadata_id' => $rootMetadataId,
                'class_name' => 'lupopedia_property',
                'schema_ref' => null,
            ));
            $pid++;
        }
    }

    /**
     * Get current system version from version source of truth
     * 
     * ATHENA ENFORCEMENT DOCTRINE: Strict resolver enforcement
     */
    private function getCurrentSystemVersion()
    {
        // Use the canonical version resolver
        if (function_exists('get_lupopedia_system_version')) {
            $version = get_lupopedia_system_version();
            
            // ATHENA ENFORCEMENT DOCTRINE: Strict resolver enforcement
            enforce_resolver_version($version, 'projection');
            
            return $version;
        }
        
        // Fallback to LUPEDIA_VERSION file
        $versionFile = __DIR__ . '/../../LUPEDIA_VERSION';
        if (file_exists($versionFile)) {
            $version = trim(file_get_contents($versionFile));
            if (!empty($version)) {
                return $version;
            }
        }
        
        // Last resort - should never be reached with proper version resolver
        error_log("WARNING: All version sources failed in projection");
        return '4.0.83'; // Emergency fallback only
    }
    
    /**
     * Production projection with batch support and performance tracking
     */
    public function projectProduction($entityId, $yamlData, $classifiedFields, $validationStatus, $warningCodes = array())
    {
        $startTime = microtime(true);
        
        // Project using existing writeFullTree method
        $this->writeFullTree($entityId, 66, $validationStatus, 
            array('version_when_written' => $this->getCurrentSystemVersion())
            $classifiedFields,
            isset($yamlData['lupopedia.edges']) ? $this->projectEdgesAsJson($yamlData) : array()
        );
        
        // Record processing time
        $processingTime = microtime(true) - $startTime;
        
        return array(
            'processing_time_ms' => round($processingTime * 1000, 2)
        );
    }
    
    /**
     * Project edges from YAML data (existing method)
     */
    private function projectEdgesAsJson($yamlData)
    {
        if (!isset($yamlData['lupopedia.edges'])) {
            return array();
        }
        
        $edgesBlock = $yamlData['lupopedia.edges'];
        if (!isset($edgesBlock['outbound_edges'])) {
            return array();
        }
        
        $outbound = $edgesBlock['outbound_edges'];
        if (!is_array($outbound)) {
            return array();
        }
        
        $edges = array();
        
        // Handle grouped outbound_edges
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
                    $edges[] = array(
                        'to' => isset($edge['to']) ? (string)$edge['to'] : '',
                        'type' => isset($edge['type']) ? (string)$edge['type'] : '',
                        'weight' => isset($edge['weight']) ? (string)$edge['weight'] : '',
                        'reason' => isset($edge['reason']) ? (string)$edge['reason'] : '',
                        'edge_category' => isset($edge['edge_category']) ? (string)$edge['edge_category'] : ''
                    );
                }
            }
        } else {
            // Handle flat outbound_edges
            foreach ($outbound as $edge) {
                if (!is_array($edge)) {
                    continue;
                }
                $edges[] = array(
                    'to' => isset($edge['to']) ? (string)$edge['to'] : '',
                    'type' => isset($edge['type']) ? (string)$edge['type'] : '',
                    'weight' => isset($edge['weight']) ? (string)$edge['weight'] : '',
                    'reason' => isset($edge['reason']) ? (string)$edge['reason'] : '',
                    'edge_category' => 'documentation'
                );
            }
        }
        
        return $edges;
    }
}

