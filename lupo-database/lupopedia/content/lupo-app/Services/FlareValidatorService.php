<?php
/**
 * FlareValidatorService - Database-Driven Validation for FLARE Protocol
 *
 * @package Lupopedia\App\Services
 * @version 4.1.0
 * @author Antigravity (1003)
 */

class ValidationResult
{
    public $isValid;
    public $errors;
    public $warnings;
    public $suggestions;
    public $validationTime;
    public $metadata;

    public function __construct()
    {
        $this->isValid = true;
        $this->errors = array();
        $this->warnings = array();
        $this->suggestions = array();
        $this->validationTime = 0.0;
        $this->metadata = array();
    }
}

class FlareValidatorService
{
    private $db;
    private $tablePrefix;
    private static $cache = array();

    public function __construct()
    {
        $this->db = DatabaseFactory::getConnection();
        $this->tablePrefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
    }

    /**
     * Validate FLARE data against database schema
     * 
     * @param array $flareData FLARE header/footer data
     * @return ValidationResult Validation results with detailed feedback
     */
    public function validateAgainstDatabase($flareData)
    {
        $startTime = microtime(true);
        $result = new ValidationResult();

        if (!isset($flareData['flare.headers'])) {
            $result->isValid = false;
            $result->errors[] = array('message' => 'Missing flare.headers section');
            $result->validationTime = microtime(true) - $startTime;
            return $result;
        }

        $headers = $flareData['flare.headers'];

        // 1. Table Existence Check (if applicable)
        if (isset($headers['lupo_table'])) {
            $tableName = $headers['lupo_table'];
            if (!$this->tableExists($tableName)) {
                $result->isValid = false;
                $result->errors[] = array('message' => "Referenced table does not exist: $tableName");
            }
        }

        // 2. Actor ID Validation
        if (isset($headers['actor_id'])) {
            $actorId = (int) $headers['actor_id'];
            if (!$this->actorExists($actorId)) {
                $result->warnings[] = array('message' => "Actor ID not found in registry: $actorId");
                $result->suggestions[] = array('message' => "Run 'php bin/lupo.php register' for this actor.");
            }
        }

        $result->validationTime = microtime(true) - $startTime;
        return $result;
    }

    /**
     * Normalize outbound_edges to a flat list. Accepts:
     * - Flat: outbound_edges: [ { to, type, weight }, ... ]
     * - Grouped: outbound_edges: { code: [ ... ], documentation: [ ... ] }
     *
     * @param array $outboundEdges Raw outbound_edges value (array or associative array of arrays)
     * @return array Flat list of edge objects (each with to, type, weight, etc.)
     */
    protected function normalizeOutboundEdges($outboundEdges)
    {
        if (!is_array($outboundEdges)) {
            return array();
        }
        $flat = array();
        $isGrouped = false;
        foreach (array_keys($outboundEdges) as $key) {
            if (is_string($key) && $key !== '' && !is_numeric($key)) {
                $isGrouped = true;
                break;
            }
        }
        if ($isGrouped) {
            foreach ($outboundEdges as $groupList) {
                if (is_array($groupList)) {
                    foreach ($groupList as $edge) {
                        if (is_array($edge) && (isset($edge['to']) || array_key_exists('to', $edge))) {
                            $flat[] = $edge;
                        }
                    }
                }
            }
        } else {
            foreach ($outboundEdges as $edge) {
                if (is_array($edge) && (isset($edge['to']) || array_key_exists('to', $edge))) {
                    $flat[] = $edge;
                }
            }
        }
        return $flat;
    }

    /**
     * Validate edge relationships using database.
     * Accepts both flat outbound_edges and grouped outbound_edges (code, documentation, etc.).
     *
     * @param array $edges FLARE/lupopedia edges data (e.g. flare.edges or lupopedia.edges)
     * @return ValidationResult Validation results
     */
    public function validateRelationships($edges)
    {
        $startTime = microtime(true);
        $result = new ValidationResult();

        if (empty($edges) || !isset($edges['outbound_edges'])) {
            $result->validationTime = microtime(true) - $startTime;
            return $result;
        }

        $flatEdges = $this->normalizeOutboundEdges($edges['outbound_edges']);

        $validTypes = array('references', 'implements', 'schema_reference', 'supersedes', 'depends_on', 'example_of', 'related_to', 'precedes', 'succeeds', 'generates', 'updates', 'documents', 'related_table', 'api_reference');

        foreach ($flatEdges as $edge) {
            $target = isset($edge['to']) ? $edge['to'] : null;
            $type = isset($edge['type']) ? $edge['type'] : null;

            if ($target === null) {
                $result->isValid = false;
                $result->errors[] = array('message' => 'Edge missing target ("to")');
                continue;
            }

            // Object Existence Check (simulated for files vs db objects)
            if (is_numeric($target)) {
                // Assume it's a content_id
                if (!$this->contentExists((int) $target)) {
                    $result->warnings[] = array('message' => "Edge target content ID not found: $target");
                    $result->suggestions[] = array('message' => "Verify content ID existence in lupo_contents table.");
                }
            } else {
                // Assume it's a file path
                $path = LUPOPEDIA_PATH . $target;
                if (!file_exists($path)) {
                    // Check if it's in the database as a path
                    if (!$this->contentPathExists($target)) {
                        $result->warnings[] = array('message' => "Edge target file/path not found: $target");
                        $result->suggestions[] = array('message' => "Verify file existence or manual content path registration.");
                    }
                }
            }

            // Weight Validation 0.5 - 1.0
            if (isset($edge['weight'])) {
                $weight = (float) $edge['weight'];
                if ($weight < 0.5 || $weight > 1.0) {
                    $result->warnings[] = array('message' => "Edge weight out of standard range (0.5-1.0): $weight for target $target");
                    $result->suggestions[] = array('message' => "Adjust weight to reflect relationship strength per FLARE doctrine.");
                }
            }

            // Edge Type Validation (includes grouped-edge types: documents, related_table, api_reference)
            if ($type && !in_array($type, $validTypes)) {
                $result->warnings[] = array('message' => "Non-standard edge type: $type for target $target");
                $result->suggestions[] = array('message' => "Use standardized edge types: " . implode(', ', $validTypes));
            }
        }

        $result->validationTime = microtime(true) - $startTime;
        return $result;
    }

    /**
     * Enhanced TOON file validation
     * 
     * @param string $tableName Table name
     * @param array $flareData FLARE data
     * @return ValidationResult Validation results
     */
    public function validateAgainstTOON($tableName, $flareData)
    {
        $startTime = microtime(true);
        $result = new ValidationResult();

        $toonPath = LUPOPEDIA_PATH . "lupo-database/lupopedia/toon/{$tableName}.toon.json";
        if (!file_exists($toonPath)) {
            $result->warnings[] = array('message' => "TOON file not found for table: $tableName");
            $result->validationTime = microtime(true) - $startTime;
            return $result;
        }

        $toonContent = file_get_contents($toonPath);
        $toonData = json_decode($toonContent, true);
        if (!$toonData) {
            $result->errors[] = array('message' => "Failed to parse TOON file for table: $tableName");
            $result->isValid = false;
            $result->validationTime = microtime(true) - $startTime;
            return $result;
        }

        // Compare FLARE metadata with TOON schema
        if (isset($flareData['lupo_field'])) {
            $fieldFound = false;
            $fieldName = $flareData['lupo_field'];
            foreach ($toonData['fields'] as $fieldDef) {
                if (strpos($fieldDef, "`$fieldName`") !== false) {
                    $fieldFound = true;
                    break;
                }
            }
            if (!$fieldFound) {
                $result->warnings[] = array('message' => "Field '$fieldName' not found in TOON schema for table '$tableName'");
                $result->suggestions[] = array('message' => "Check spelling or if migration to add this field is pending.");
            }
        }

        $result->validationTime = microtime(true) - $startTime;
        return $result;
    }

    /**
     * Validate FLARE structure to prevent cross-pollution between sections
     * 
     * @param array $flareData Complete FLARE data
     * @return ValidationResult Validation results
     */
    public function validateStructure($flareData)
    {
        $startTime = microtime(true);
        $result = new ValidationResult();

        // 1. Relational Pollution Check
        $sections = array('flare.headers', 'flare.footer');
        foreach ($sections as $section) {
            if (isset($flareData[$section])) {
                if (isset($flareData[$section]['outbound_edges'])) {
                    $result->isValid = false;
                    $result->errors[] = array('message' => "Structural Error: 'outbound_edges' found in '$section'. Relationships must be in 'flare.edges'.");
                }
                if (isset($flareData[$section]['semantic_tags'])) {
                    $result->isValid = false;
                    $result->errors[] = array('message' => "Structural Error: 'semantic_tags' found in '$section'. Categorization must be in 'flare.edges'.");
                }
            }
        }

        // 2. Engagement Pollution Check
        $sections = array('flare.headers', 'flare.edges');
        $engagementKeys = array('view_count', 'like_count', 'share_count', 'comment_count');
        foreach ($sections as $section) {
            if (isset($flareData[$section])) {
                foreach ($engagementKeys as $key) {
                    if (isset($flareData[$section][$key])) {
                        $result->isValid = false;
                        $result->errors[] = array('message' => "Structural Error: Engagement metric '$key' found in '$section'. Metrics must be in 'flare.footer'.");
                    }
                }
            }
        }

        $result->validationTime = microtime(true) - $startTime;
        return $result;
    }

    /**
     * Cached validation result with batch support
     */
    public function validateWithCaching($flareData, $useCache = true)
    {
        $startTime = microtime(true);
        $cacheKey = md5(serialize($flareData));
        if ($useCache && isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }

        $result = $this->validateAgainstDatabase($flareData);

        // Add structural validation
        $structResult = $this->validateStructure($flareData);
        $result->errors = array_merge($result->errors, $structResult->errors);
        $result->warnings = array_merge($result->warnings, $structResult->warnings);
        $result->isValid = $result->isValid && $structResult->isValid;

        if (isset($flareData['flare.edges'])) {
            $edgeResult = $this->validateRelationships($flareData['flare.edges']);
            $result->errors = array_merge($result->errors, $edgeResult->errors);
            $result->warnings = array_merge($result->warnings, $edgeResult->warnings);
            $result->isValid = $result->isValid && $edgeResult->isValid;
        }

        if (isset($flareData['flare.headers']['lupo_table'])) {
            $toonResult = $this->validateAgainstTOON($flareData['flare.headers']['lupo_table'], $flareData);
            $result->errors = array_merge($result->errors, $toonResult->errors);
            $result->warnings = array_merge($result->warnings, $toonResult->warnings);
            $result->isValid = $result->isValid && $toonResult->isValid;
        }

        $result->validationTime = (microtime(true) - $startTime) * 1000; // ms

        if ($useCache) {
            self::$cache[$cacheKey] = $result;
        }

        return $result;
    }

    // Helper methods

    private function tableExists($tableName)
    {
        try {
            $stmt = $this->db->prepare("SELECT 1 FROM information_schema.tables WHERE table_name = :t LIMIT 1");
            $stmt->execute(array('t' => $tableName));
            return (bool) $stmt->fetchColumn();
        } catch (Exception $e) {
            return false;
        }
    }

    private function actorExists($actorId)
    {
        $t = $this->tablePrefix . 'actors';
        try {
            $stmt = $this->db->prepare("SELECT 1 FROM $t WHERE actor_id = :id AND is_deleted = 0 LIMIT 1");
            $stmt->execute(array('id' => $actorId));
            return (bool) $stmt->fetchColumn();
        } catch (Exception $e) {
            return false;
        }
    }

    private function contentExists($contentId)
    {
        $t = $this->tablePrefix . 'contents';
        try {
            $stmt = $this->db->prepare("SELECT 1 FROM $t WHERE content_id = :id AND is_deleted = 0 LIMIT 1");
            $stmt->execute(array('id' => $contentId));
            return (bool) $stmt->fetchColumn();
        } catch (Exception $e) {
            return false;
        }
    }

    private function contentPathExists($path)
    {
        $t = $this->tablePrefix . 'contents';
        try {
            $stmt = $this->db->prepare("SELECT 1 FROM $t WHERE (file_path_from_root = :p OR custom_path = :p2) AND is_deleted = 0 LIMIT 1");
            $stmt->execute(array('p' => $path, 'p2' => $path));
            return (bool) $stmt->fetchColumn();
        } catch (Exception $e) {
            return false;
        }
    }
}
