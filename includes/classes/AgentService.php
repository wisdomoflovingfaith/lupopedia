<?php
/**
 * wolfie.headers: {
 *   file_path_from_root: "includes/classes/AgentService.php",
 *   system_version: "4.0.66",
 *   channel_id: 42,
 *   actor_id: 1006,
 *   purpose: "Manages AI actor identities, capabilities, and hierarchical relations.",
 *   last_modified_utc: "20260308"
 * }
 */

class AgentService
{
    private $db;
    private $prefix;
    private $actorBaseDir;

    public function __construct($db, $prefix)
    {
        $this->db = $db;
        $this->prefix = $prefix;
        $this->actorBaseDir = ABSPATH . LUPO_ACTORS_DIR;
    }

    /**
     * Get agent details from DB and Filesystem
     *
     * @param int $actorId
     * @return array
     */
    public function getAgent($actorId)
    {
        // 1. Get DB Record
        $sql = "SELECT * FROM {$this->prefix}actors WHERE actor_id = :id AND is_deleted = 0";
        $actor = $this->db->fetchRow($sql, array('id' => $actorId));
        if (!$actor)
            return null;

        // 2. Resolve Workspace Path
        $actorSlug = $actor['actor_name']; // Standardized in 4.0.64
        $workspacePath = $this->actorBaseDir . DIRECTORY_SEPARATOR . $actorSlug;

        // 3. Load agent.json if exists
        $agentConfig = array();
        $jsonPath = $workspacePath . DIRECTORY_SEPARATOR . 'agent.json';
        if (is_file($jsonPath)) {
            $agentConfig = json_decode(file_get_contents($jsonPath), true);
        }

        // 4. Load System Prompt
        $prompt = '';
        $promptPath = $workspacePath . DIRECTORY_SEPARATOR . 'system_prompt.txt';
        if (is_file($promptPath)) {
            $prompt = file_get_contents($promptPath);
        }

        return array(
            'actor' => $actor,
            'config' => $agentConfig,
            'system_prompt' => $prompt,
            'workspace' => $workspacePath
        );
    }

    /**
     * List all skills for an agent
     */
    public function getSkills($actorId)
    {
        $agent = $this->getAgent($actorId);
        if (!$agent)
            return array();

        $skillsPath = $agent['workspace'] . DIRECTORY_SEPARATOR . 'skills';
        if (!is_dir($skillsPath))
            return array();

        $skills = array();
        $items = scandir($skillsPath);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..')
                continue;
            if (is_dir($skillsPath . DIRECTORY_SEPARATOR . $item)) {
                $skills[] = $item;
            }
        }
        return $skills;
    }

    /**
     * Get actor-level doctrine requirements (machine-readable constraints for code/schema enforcement).
     * Sources: (1) agent workspace requirements.json, (2) agent.json config.requirements, (3) lupo_registry.metadata_json for entity_type=agent.
     *
     * @param int $actorId Actor ID (used as agent identifier for kernel agents)
     * @return array Decoded requirements structure, e.g. array('database' => array('no_foreign_keys' => true, ...))
     */
    public function getRequirements($actorId)
    {
        $agent = $this->getAgent($actorId);
        if (!$agent)
            return array();

        $requirements = array();
        $workspace = isset($agent['workspace']) ? $agent['workspace'] : '';

        if ($workspace !== '' && is_file($workspace . DIRECTORY_SEPARATOR . 'requirements.json')) {
            $raw = file_get_contents($workspace . DIRECTORY_SEPARATOR . 'requirements.json');
            $decoded = json_decode($raw, true);
            if (is_array($decoded))
                $requirements = $decoded;
        }
        if (isset($agent['config']['requirements']) && is_array($agent['config']['requirements'])) {
            $requirements = array_merge($requirements, $agent['config']['requirements']);
        }

        $regTable = $this->prefix . 'registry';
        $row = $this->db->fetchRow(
            "SELECT metadata_json FROM " . $regTable . " WHERE entity_type = 'agent' AND (entity_index_id = :aid OR entity_index = :aid2) AND is_deleted = 0 LIMIT 1",
            array('aid' => $actorId, 'aid2' => $actorId)
        );
        if ($row && !empty($row['metadata_json'])) {
            $decoded = json_decode($row['metadata_json'], true);
            if (is_array($decoded) && isset($decoded['requirements']) && is_array($decoded['requirements'])) {
                $requirements = array_merge($requirements, $decoded['requirements']);
            }
        }

        return $requirements;
    }
}
