<?php
/**
 * BoundedHeaderAuthorityValidator
 *
 * Thread 1001 P0 bounded authority checks for Channel 66:
 * - structural validation for lupopedia.headers required fields
 * - version compatibility enforcement using Thread 1001 locked matrix rules
 * - TOON/schema safety validation (projection safety, reject on missing columns)
 * - actor registry existence validation (reject on unknown actor_id)
 */

class BoundedHeaderAuthorityValidator
{
    /** @var array */
    private $requiredHeaderFields = array();

    /** @var ToonSchemaCache */
    private $toonCache;

    public function __construct($toonCache)
    {
        $this->toonCache = $toonCache;
        $this->requiredHeaderFields = array(
            'lupopedia.version' => true,
            'lupopedia.schema' => true,
            'file_path_from_root' => true,
            'web_path' => true,
            'last_modified_utc' => true,
            'system_version' => true,
            'channel_id' => true,
            'actor_id' => true,
            'delegation_chain' => true,
            'artifact_type' => true,
            'artifact_kind' => true,
            'purpose' => true,
        );
    }

    /**
     * Extract a numeric actor_id from parsed header fields.
     *
     * @param mixed $value
     * @return int|null
     */
    private function parseActorId($value)
    {
        if (is_null($value)) {
            return null;
        }
        if (is_int($value)) {
            return $value;
        }
        $s = trim((string)$value);
        if ($s === '') {
            return null;
        }
        if (!ctype_digit($s)) {
            return null;
        }
        return (int)$s;
    }

    /**
     * Validate actor_id exists in canonical actor registry.
     *
     * @param int $actorId
     * @return bool
     */
    private function actorExists($actorId)
    {
        if ($actorId === null) {
            return false;
        }

        static $registry = null;
        if ($registry === null) {
            $base = defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : dirname(dirname(__DIR__));
            $regPath = $base . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'lupopedia'
                . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'actor_id'
                . DIRECTORY_SEPARATOR . 'registry.json';

            if (is_file($regPath)) {
                $raw = @file_get_contents($regPath);
                $decoded = json_decode($raw, true);
                if (is_array($decoded) && isset($decoded['actors']) && is_array($decoded['actors'])) {
                    $registry = $decoded['actors'];
                } else {
                    $registry = array();
                }
            } else {
                $registry = array();
            }
        }

        foreach ($registry as $a) {
            if (!is_array($a) || !isset($a['id'])) {
                continue;
            }
            if ((int)$a['id'] === (int)$actorId) {
                return true;
            }
        }
        return false;
    }

    /**
     * Parse a semantic version major.minor.patch into integers.
     *
     * @param string $version
     * @return array|null ['major'=>, 'minor'=>, 'patch'=>]
     */
    private function parseSemanticVersion($version)
    {
        $v = trim((string)$version);
        if ($v === '') {
            return null;
        }
        if (!preg_match('/^(\d+)\.(\d+)\.(\d+)$/', $v, $m)) {
            return null;
        }
        return array(
            'major' => (int)$m[1],
            'minor' => (int)$m[2],
            'patch' => (int)$m[3],
        );
    }

    /**
     * Apply Thread 1001 compatibility matrix rules.
     *
     * @param array $headers
     * @return array validation result:
     *  - ['outcome'=>'accept','scenario'=>'accept_current']
     *  - ['outcome'=>'warn','warning_codes'=>['deprecated_version_minor_newer'],'scenario'=>'warn_minor_newer']
     *  - ['outcome'=>'reject','reject_type'=>'version_incompatible','scenario'=>...]
     */
    private function validateCompatibilityMatrix($headers)
    {
        if (!isset($headers['lupopedia.version']) || !isset($headers['system_version'])) {
            return array(
                'outcome' => 'reject',
                'reject_type' => 'version_incompatible',
                'version_scenario' => 'reject_missing_version',
            );
        }

        // Exact match is always ACCEPT for P0.
        $rawHeader = trim((string)$headers['lupopedia.version']);
        $rawSystem = trim((string)$headers['system_version']);
        if ($rawHeader !== '' && $rawHeader === $rawSystem) {
            return array(
                'outcome' => 'accept',
                'scenario' => 'accept_current',
            );
        }

        $headerParsed = $this->parseSemanticVersion($headers['lupopedia.version']);
        $systemParsed = $this->parseSemanticVersion($headers['system_version']);
        if ($headerParsed === null || $systemParsed === null) {
            return array(
                'outcome' => 'reject',
                'reject_type' => 'version_incompatible',
                'version_scenario' => 'reject_malformed_version',
            );
        }

        $hMajor = $headerParsed['major'];
        $hMinor = $headerParsed['minor'];
        $hPatch = $headerParsed['patch'];
        $sMajor = $systemParsed['major'];
        $sMinor = $systemParsed['minor'];
        $sPatch = $systemParsed['patch'];

        if ($hMajor !== $sMajor) {
            return array(
                'outcome' => 'reject',
                'reject_type' => 'version_incompatible',
                'version_scenario' => 'reject_major_mismatch',
            );
        }

        // Locked matrix treats minor-component mismatch (e.g. 4.1.x vs 4.0.80) as REJECT.
        if ($hMinor !== $sMinor) {
            return array(
                'outcome' => 'reject',
                'reject_type' => 'version_incompatible',
                'version_scenario' => 'reject_major_mismatch',
            );
        }

        // Same major.minor: patch differences drive ACCEPT vs WARN.
        // Locked matrix examples:
        // - 4.0.79 vs 4.0.80 => ACCEPT (patch older)
        // - 4.0.81 vs 4.0.80 => WARN (patch newer)
        if ($hPatch < $sPatch) {
            return array(
                'outcome' => 'accept',
                'scenario' => 'accept_minor_older',
            );
        }

        if ($hPatch > $sPatch) {
            return array(
                'outcome' => 'warn',
                'scenario' => 'warn_minor_newer',
                'warning_codes' => array('deprecated_version_minor_newer'),
            );
        }

        // Same major.minor.patch handled by exact match above, but keep deterministic fallback.
        return array(
            'outcome' => 'accept',
            'scenario' => 'accept_current',
        );
    }

    /**
     * Validate schema reference JSON safety for lupo_metadata projection (PRD 00 section 6).
     * Reads canonical-shaped schema from database/lupopedia/json/lupo_metadata.json
     * (or directory passed as ingest "toon_dir" for tests / overrides).
     *
     * @param string $schemaDirHint Directory hint from ingest config (legacy name toon_dir).
     * @return array outcome/reject fields
     */
    private function validateToonSafety($schemaDirHint)
    {
        $schema = $this->toonCache->loadToonTable($schemaDirHint, 'lupo_metadata');
        if ($schema === null) {
            return array(
                'outcome' => 'reject',
                'reject_type' => 'toon_conflict',
                'toon_error_code' => 'missing_toon_file_or_unparseable_lupo_metadata',
            );
        }

        $fieldNames = $this->toonCache->extractFieldNames($schema);
        $fieldSet = array();
        foreach ($fieldNames as $f) {
            $fieldSet[(string)$f] = true;
        }

        $required = array(
            'metadata_id',
            'entity_type',
            'entity_id',
            'domain_id',
            'meta_type',
            'property_key',
            'property_value',
            'created_ymdhis',
            'updated_ymdhis',
            'is_deleted',
            'deleted_ymdhis',
            'channel_id',
            'parent_metadata_id',
            'class_name',
            'schema_ref',
        );

        $missing = array();
        foreach ($required as $col) {
            if (!isset($fieldSet[$col])) {
                $missing[] = $col;
            }
        }

        if (!empty($missing)) {
            // Deterministic single error code for first missing column.
            return array(
                'outcome' => 'reject',
                'reject_type' => 'toon_conflict',
                'toon_error_code' => 'missing_required_column_' . $missing[0],
            );
        }

        return array('outcome' => 'accept');
    }

    /**
     * Validate the Thread 1001 P0 bounded authority pipeline.
     *
     * @param array $parsedYaml
     * @param string $filePathFromRoot computed from actual file location
     * @param string $toonDir
     * @param int $expectedThreadId
     * @return array outcome structure for projection:
     *  - accept: ['outcome'=>'accept']
     *  - warn: ['outcome'=>'warn','warning_codes'=>[...]]
     *  - reject: ['outcome'=>'reject','reject_type'=>..., ...]
     */
    public function validateP0($parsedYaml, $filePathFromRoot, $toonDir, $expectedThreadId)
    {
        // Structural validation for required blocks/fields.
        $headersBlock = null;
        if (isset($parsedYaml['lupopedia.headers']) && is_array($parsedYaml['lupopedia.headers'])) {
            $headersBlock = $parsedYaml['lupopedia.headers'];
        } elseif (isset($parsedYaml['flare.headers']) && is_array($parsedYaml['flare.headers'])) {
            // Allow legacy block naming for transition.
            $headersBlock = $parsedYaml['flare.headers'];
        }

        if ($headersBlock === null) {
            return array(
                'outcome' => 'reject',
                'reject_type' => 'structural_validation_failure',
                'validation_warnings' => array('missing: lupopedia.headers'),
            );
        }

        $missing = array();
        foreach ($this->requiredHeaderFields as $k => $_) {
            if (!isset($headersBlock[$k]) || $headersBlock[$k] === '') {
                $missing[] = $k;
            }
        }

        $headerPath = isset($headersBlock['file_path_from_root']) ? (string)$headersBlock['file_path_from_root'] : '';
        if ($headerPath === '' || $headerPath !== (string)$filePathFromRoot) {
            $already = false;
            foreach ($missing as $m) {
                if ($m === 'file_path_from_root') {
                    $already = true;
                    break;
                }
            }
            if (!$already) {
                $missing[] = 'file_path_from_root';
            }
        }

        if (!empty($missing)) {
            return array(
                'outcome' => 'reject',
                'reject_type' => 'structural_validation_failure',
                'validation_warnings' => $missing,
            );
        }

        // Actor registry existence (reject).
        $actorId = $this->parseActorId($headersBlock['actor_id']);
        if ($actorId === null || !$this->actorExists($actorId)) {
            return array(
                'outcome' => 'reject',
                'reject_type' => 'unknown_actor_id',
            );
        }

        // Compatibility matrix enforcement (reject/warn).
        $compat = $this->validateCompatibilityMatrix($headersBlock);
        if ($compat['outcome'] === 'reject') {
            return $compat;
        }

        // TOON safety validation (reject).
        $toon = $this->validateToonSafety($toonDir);
        if ($toon['outcome'] === 'reject') {
            return $toon;
        }

        if ($compat['outcome'] === 'warn') {
            return array(
                'outcome' => 'warn',
                'warning_codes' => isset($compat['warning_codes']) ? $compat['warning_codes'] : array(),
                'version_scenario' => isset($compat['scenario']) ? $compat['scenario'] : 'warn_minor_newer',
            );
        }

        return array('outcome' => 'accept', 'version_scenario' => isset($compat['scenario']) ? $compat['scenario'] : 'accept_current');
    }
}

