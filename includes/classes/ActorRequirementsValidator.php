<?php
/**
 * Validates proposals (e.g. SQL) against actor-level doctrine requirements (e.g. LUPO database rules).
 * Used by consensus and migration flows to enforce kernel agent constraints.
 *
 * wolfie.headers: {
 *   file_path_from_root: "includes/classes/ActorRequirementsValidator.php",
 *   system_version: "4.0.66",
 *   purpose: "Enforce actor requirements (LUPO database doctrine) on schema/code proposals.",
 *   last_modified_utc: "20260308"
 * }
 */

class ActorRequirementsValidator
{
    /** @var array LUPO actor_id for database doctrine */
    const LUPO_ACTOR_ID = 106;

    /** @var PDO_DB */
    private $db;
    /** @var string */
    private $prefix;

    public function __construct($db, $prefix)
    {
        $this->db = $db;
        $this->prefix = $prefix;
    }

    /**
     * Get LUPO (database architect) requirements and validate SQL does not violate them.
     *
     * @param string $sql Raw SQL (e.g. migration or DDL)
     * @return array List of violation messages; empty if compliant
     */
    public function validateSqlAgainstLupo($sql)
    {
        $requirements = $this->getLupoRequirements();
        $violations = array();

        if (empty($requirements['database']))
            return $violations;

        $db = $requirements['database'];
        $upper = strtoupper($sql);

        if (!empty($db['no_foreign_keys']) && (strpos($upper, 'FOREIGN KEY') !== false || strpos($upper, 'REFERENCES ') !== false)) {
            $violations[] = 'LUPO requirement: no foreign keys (FOREIGN KEY / REFERENCES not allowed).';
        }
        if (!empty($db['no_triggers']) && (strpos($upper, 'TRIGGER') !== false)) {
            $violations[] = 'LUPO requirement: no database triggers.';
        }
        if (!empty($db['no_procedures']) && (strpos($upper, 'PROCEDURE') !== false)) {
            $violations[] = 'LUPO requirement: no stored procedures.';
        }
        if (!empty($db['no_functions']) && (preg_match('/\b(FUNCTION|RETURNS)\s+\w+/i', $sql))) {
            $violations[] = 'LUPO requirement: no stored functions.';
        }
        if (isset($db['datetime_types_allowed']) && $db['datetime_types_allowed'] === false) {
            if (preg_match('/\b(DATETIME|TIMESTAMP)\s/i', $upper)) {
                $violations[] = 'LUPO requirement: no DATETIME/TIMESTAMP types; use BIGINT UTC YYYYMMDDHHIISS.';
            }
        }

        return $violations;
    }

    /**
     * Load LUPO requirements from registry (or AgentService).
     *
     * @return array Requirements structure
     */
    public function getLupoRequirements()
    {
        static $cached = null;
        if ($cached !== null)
            return $cached;

        $regTable = $this->prefix . 'registry';
        $row = $this->db->fetchRow(
            "SELECT metadata_json FROM " . $regTable . " WHERE entity_type = 'agent' AND (entity_index_id = :aid OR entity_index = :aid2) AND is_deleted = 0 LIMIT 1",
            array('aid' => self::LUPO_ACTOR_ID, 'aid2' => self::LUPO_ACTOR_ID)
        );
        if (!$row || empty($row['metadata_json'])) {
            $cached = array();
            return $cached;
        }
        $decoded = json_decode($row['metadata_json'], true);
        $cached = is_array($decoded) && isset($decoded['requirements']) ? $decoded['requirements'] : array();
        return $cached;
    }
}
