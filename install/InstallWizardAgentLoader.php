<?php
/*
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: "install/InstallWizardAgentLoader.php"
  web_path: "https://www.lupopedia.com/lupopedia/install/InstallWizardAgentLoader.php"
  status: "active"
  when_updated: "20260620180000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: "0/development/install-wizard-agent-loader-php"
  artifact_type: "implementation"
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: "implementation"
  prd_cluster: "00_A_FORBIDDEN_AND_WHY_00_C_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS_16_B_ATOMS_16_C_HEADERS_26_A_FIVE_LAYER_DOCUMENTATION_ARCHITECTURE"
  title: "InstallWizardAgentLoader.php -- Agent template pack importer for install wizard"
  summary: "Scans agents/*/ template packs and upserts agent_definitions and agent_capabilities during installation."
  artifact_kind: "tool"
*/

/**
 * Install Wizard Agent Template Loader
 *
 * Imports agent template packs from agents/{slug}/ into agent_definitions and
 * agent_capabilities. Filesystem packs are authoritative; does not create actors.
 */
class InstallWizardAgentLoader
{
    const AGENTS_PATH = 'agents';

    const SKIP_DIR_NAMES = array('meta', '_TEMPLATE');

    /**
     * Import all valid agent packs under agents/.
     *
     * @param PDO $pdo
     * @param array $log
     * @param string $table_prefix
     * @return array summary counts
     */
    public static function importAllAgentPacks($pdo, &$log, $table_prefix = 'lupo_')
    {
        $summary = array(
            'imported' => 0,
            'skipped' => 0,
            'errors' => 0,
            'capabilities' => 0,
        );

        $agentsRoot = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . self::AGENTS_PATH;
        if (!is_dir($agentsRoot)) {
            $log[] = InstallWizardLogger::logEntry('skip', 'Agents directory not found: ' . self::AGENTS_PATH);
            return $summary;
        }

        self::checkRegistryDrift($agentsRoot, $log);

        $definitionsTable = preg_replace('/[^a-zA-Z0-9_]/', '', $table_prefix . 'agent_definitions');
        $capabilitiesTable = preg_replace('/[^a-zA-Z0-9_]/', '', $table_prefix . 'agent_capabilities');

        $dirs = glob($agentsRoot . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
        if (!is_array($dirs)) {
            $dirs = array();
        }
        sort($dirs, SORT_STRING);

        $log[] = InstallWizardLogger::logEntry('ok', 'Agent pack import: scanning ' . count($dirs) . ' directories under ' . self::AGENTS_PATH . '/');

        foreach ($dirs as $dirPath) {
            $dirName = basename($dirPath);
            if (self::shouldSkipDirectory($dirName)) {
                $summary['skipped']++;
                continue;
            }

            $result = self::importSingleAgentPack(
                $pdo,
                $dirPath,
                $dirName,
                $definitionsTable,
                $capabilitiesTable,
                $log
            );

            if (is_array($result) && isset($result['capabilities'])) {
                $summary['imported']++;
                $summary['capabilities'] += (int) $result['capabilities'];
            } elseif ($result === false && is_file($dirPath . DIRECTORY_SEPARATOR . 'agent.json')) {
                $summary['errors']++;
            } else {
                $summary['skipped']++;
            }
        }

        $log[] = InstallWizardLogger::logEntry(
            'ok',
            'Agent pack import complete: ' . $summary['imported'] . ' imported, '
            . $summary['skipped'] . ' skipped, '
            . $summary['capabilities'] . ' capability rows, '
            . $summary['errors'] . ' errors'
        );

        return $summary;
    }

    /**
     * @param string $dirName
     * @return bool
     */
    private static function shouldSkipDirectory($dirName)
    {
        if ($dirName === '' || $dirName === '.' || $dirName === '..') {
            return true;
        }
        if (isset($dirName[0]) && $dirName[0] === '_') {
            return true;
        }
        if (in_array($dirName, self::SKIP_DIR_NAMES, true)) {
            return true;
        }
        return false;
    }

    /**
     * @param PDO $pdo
     * @param string $dirPath
     * @param string $dirName
     * @param string $definitionsTable
     * @param string $capabilitiesTable
     * @param array $log
     * @return bool|array false on skip/error, array with capabilities count on success
     */
    private static function importSingleAgentPack($pdo, $dirPath, $dirName, $definitionsTable, $capabilitiesTable, &$log)
    {
        $agentJsonPath = $dirPath . DIRECTORY_SEPARATOR . 'agent.json';
        if (!is_file($agentJsonPath)) {
            return false;
        }

        $agentData = self::loadJsonFile($agentJsonPath, $log);
        if ($agentData === null) {
            $log[] = InstallWizardLogger::logEntry('error', 'Invalid agent.json: ' . self::AGENTS_PATH . '/' . $dirName . '/agent.json');
            return false;
        }

        $validationError = self::validateAgentJson($agentData, $dirName);
        if ($validationError !== null) {
            $log[] = InstallWizardLogger::logEntry('error', 'Agent pack validation failed for ' . $dirName . ': ' . $validationError);
            return false;
        }

        $capabilitiesPath = $dirPath . DIRECTORY_SEPARATOR . 'capabilities.json';
        $propertiesPath = $dirPath . DIRECTORY_SEPARATOR . 'properties.json';
        $capabilitiesData = is_file($capabilitiesPath) ? self::loadJsonFile($capabilitiesPath, $log) : array();
        $propertiesData = is_file($propertiesPath) ? self::loadJsonFile($propertiesPath, $log) : array();

        if ($capabilitiesData === null) {
            $log[] = InstallWizardLogger::logEntry('error', 'Invalid capabilities.json: ' . self::AGENTS_PATH . '/' . $dirName . '/capabilities.json');
            return false;
        }
        if ($propertiesData === null) {
            $log[] = InstallWizardLogger::logEntry('error', 'Invalid properties.json: ' . self::AGENTS_PATH . '/' . $dirName . '/properties.json');
            return false;
        }

        $allowedCaps = self::extractCapabilityList($capabilitiesData, 'capabilities');
        $blockedCaps = self::extractCapabilityList($capabilitiesData, 'blocked_capabilities');

        $promptMd = $dirPath . DIRECTORY_SEPARATOR . 'system_prompt.md';
        $promptTxt = $dirPath . DIRECTORY_SEPARATOR . 'system_prompt.txt';
        $systemPromptPath = null;
        if (is_file($promptMd)) {
            $systemPromptPath = self::AGENTS_PATH . '/' . $dirName . '/system_prompt.md';
        } elseif (is_file($promptTxt)) {
            $systemPromptPath = self::AGENTS_PATH . '/' . $dirName . '/system_prompt.txt';
        }

        $agentId = (int) $agentData['agent_id'];
        $agentKey = (string) $agentData['agent_key'];
        $slug = isset($agentData['slug']) && $agentData['slug'] !== '' ? (string) $agentData['slug'] : $agentKey;
        $name = (string) $agentData['name'];
        $layer = isset($agentData['layer']) && $agentData['layer'] !== '' ? (string) $agentData['layer'] : 'application';
        $role = isset($agentData['role']) ? (string) $agentData['role'] : null;
        $version = isset($agentData['version']) && $agentData['version'] !== '' ? (string) $agentData['version'] : '1.0.0';
        $isKernel = !empty($agentData['is_kernel']) ? 1 : 0;
        $isRequired = !empty($agentData['is_required']) ? 1 : 0;
        $now = (int) gmdate('YmdHis');

        $metadataJson = self::encodeJson($propertiesData);
        $capabilitiesJson = self::encodeJson(array(
            'capabilities' => $allowedCaps,
            'blocked_capabilities' => $blockedCaps,
        ));

        try {
            self::upsertAgentDefinition(
                $pdo,
                $definitionsTable,
                $agentId,
                $agentKey,
                $slug,
                $name,
                $layer,
                $role,
                $version,
                $isRequired,
                $isKernel,
                $systemPromptPath,
                $metadataJson,
                $capabilitiesJson,
                $now
            );

            $capCount = self::replaceAgentCapabilities(
                $pdo,
                $capabilitiesTable,
                $agentId,
                $allowedCaps,
                $blockedCaps,
                $now
            );

            $log[] = InstallWizardLogger::logEntry(
                'ok',
                'Imported agent pack ' . $agentKey . ' (agent_id ' . $agentId . ', ' . $capCount . ' capabilities)'
            );

            return array('capabilities' => $capCount);
        } catch (Exception $e) {
            $log[] = InstallWizardLogger::logEntry(
                'error',
                'Failed importing agent pack ' . $dirName . ': ' . $e->getMessage()
            );
            error_log('InstallWizardAgentLoader: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * @param array $agentData
     * @param string $dirName
     * @return string|null error message or null if valid
     */
    private static function validateAgentJson($agentData, $dirName)
    {
        if (!is_array($agentData)) {
            return 'agent.json must decode to an object';
        }
        if (!isset($agentData['agent_key']) || $agentData['agent_key'] === '') {
            return 'missing agent_key';
        }
        if (!isset($agentData['agent_id']) || !is_numeric($agentData['agent_id'])) {
            return 'missing or invalid agent_id';
        }
        if ((int) $agentData['agent_id'] < 0) {
            return 'agent_id must be zero or positive';
        }
        if (!isset($agentData['name']) || $agentData['name'] === '') {
            return 'missing name';
        }
        if (isset($agentData['slug']) && $agentData['slug'] !== '' && $agentData['slug'] !== $dirName) {
            // Directory name should match slug when slug is present (warn only in logs elsewhere)
        }
        return null;
    }

    /**
     * @param string $path
     * @param array $log
     * @return array|null
     */
    private static function loadJsonFile($path, &$log)
    {
        $raw = @file_get_contents($path);
        if ($raw === false) {
            return null;
        }
        $decoded = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            return null;
        }
        return $decoded;
    }

    /**
     * @param array $data
     * @param string $key
     * @return array
     */
    private static function extractCapabilityList($data, $key)
    {
        if (!is_array($data) || !isset($data[$key]) || !is_array($data[$key])) {
            return array();
        }
        $out = array();
        foreach ($data[$key] as $cap) {
            if (!is_string($cap) && !is_numeric($cap)) {
                continue;
            }
            $cap = trim((string) $cap);
            if ($cap === '') {
                continue;
            }
            if (strlen($cap) > 100) {
                $cap = substr($cap, 0, 100);
            }
            $out[] = $cap;
        }
        return array_values(array_unique($out));
    }

    /**
     * @param mixed $value
     * @return string|null
     */
    private static function encodeJson($value)
    {
        if ($value === null || $value === array()) {
            return null;
        }
        $encoded = json_encode($value);
        if ($encoded === false) {
            return null;
        }
        return $encoded;
    }

    /**
     * @param PDO $pdo
     * @param string $table
     * @param int $agentId
     * @param string $agentKey
     * @param string $slug
     * @param string $name
     * @param string $layer
     * @param string|null $role
     * @param string $version
     * @param int $isRequired
     * @param int $isKernel
     * @param string|null $systemPromptPath
     * @param string|null $metadataJson
     * @param string|null $capabilitiesJson
     * @param int $now
     */
    private static function upsertAgentDefinition(
        $pdo,
        $table,
        $agentId,
        $agentKey,
        $slug,
        $name,
        $layer,
        $role,
        $version,
        $isRequired,
        $isKernel,
        $systemPromptPath,
        $metadataJson,
        $capabilitiesJson,
        $now
    ) {
        $sql = 'INSERT INTO `' . $table . '` (
            `agent_id`, `agent_key`, `slug`, `name`, `layer`, `role`, `version`,
            `is_required`, `is_kernel`, `system_prompt_path`, `metadata_json`, `capabilities_json`,
            `status`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
        ) VALUES (
            :agent_id, :agent_key, :slug, :name, :layer, :role, :version,
            :is_required, :is_kernel, :system_prompt_path, :metadata_json, :capabilities_json,
            :status, :created_ymdhis, :updated_ymdhis, 0, NULL
        ) ON DUPLICATE KEY UPDATE
            `agent_key` = VALUES(`agent_key`),
            `slug` = VALUES(`slug`),
            `name` = VALUES(`name`),
            `layer` = VALUES(`layer`),
            `role` = VALUES(`role`),
            `version` = VALUES(`version`),
            `is_required` = VALUES(`is_required`),
            `is_kernel` = VALUES(`is_kernel`),
            `system_prompt_path` = VALUES(`system_prompt_path`),
            `metadata_json` = VALUES(`metadata_json`),
            `capabilities_json` = VALUES(`capabilities_json`),
            `status` = VALUES(`status`),
            `updated_ymdhis` = VALUES(`updated_ymdhis`),
            `is_deleted` = 0,
            `deleted_ymdhis` = NULL';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            'agent_id' => $agentId,
            'agent_key' => substr($agentKey, 0, 100),
            'slug' => substr($slug, 0, 255),
            'name' => substr($name, 0, 255),
            'layer' => substr($layer, 0, 64),
            'role' => $role !== null ? substr($role, 0, 500) : null,
            'version' => substr($version, 0, 50),
            'is_required' => $isRequired,
            'is_kernel' => $isKernel,
            'system_prompt_path' => $systemPromptPath !== null ? substr($systemPromptPath, 0, 512) : null,
            'metadata_json' => $metadataJson,
            'capabilities_json' => $capabilitiesJson,
            'status' => 'active',
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
        ));
    }

    /**
     * Replace capability rows for one agent (hard delete then insert).
     *
     * @param PDO $pdo
     * @param string $table
     * @param int $agentId
     * @param array $allowedCaps
     * @param array $blockedCaps
     * @param int $now
     * @return int rows inserted
     */
    private static function replaceAgentCapabilities($pdo, $table, $agentId, $allowedCaps, $blockedCaps, $now)
    {
        $deleteSql = 'DELETE FROM `' . $table . '` WHERE `agent_id` = :agent_id';
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->execute(array('agent_id' => $agentId));

        $insertSql = 'INSERT INTO `' . $table . '` (
            `agent_capability_id`, `agent_id`, `capability_key`, `capability_category`,
            `capability_description`, `is_out_of_scope`, `out_of_scope_owner`,
            `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
        ) VALUES (
            :agent_capability_id, :agent_id, :capability_key, :capability_category,
            NULL, :is_out_of_scope, NULL,
            :created_ymdhis, :updated_ymdhis, 0, NULL
        )';

        $insertStmt = $pdo->prepare($insertSql);
        $seq = 0;
        $inserted = 0;

        foreach ($allowedCaps as $capKey) {
            $seq++;
            $capId = ($agentId * 10000) + $seq;
            $insertStmt->execute(array(
                'agent_capability_id' => $capId,
                'agent_id' => $agentId,
                'capability_key' => $capKey,
                'capability_category' => 'allowed',
                'is_out_of_scope' => 0,
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
            ));
            $inserted++;
        }

        foreach ($blockedCaps as $capKey) {
            $seq++;
            $capId = ($agentId * 10000) + $seq;
            $insertStmt->execute(array(
                'agent_capability_id' => $capId,
                'agent_id' => $agentId,
                'capability_key' => $capKey,
                'capability_category' => 'blocked',
                'is_out_of_scope' => 1,
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
            ));
            $inserted++;
        }

        return $inserted;
    }

    /**
     * Log registry.json slug drift vs agents/ directories (no auto-fix).
     *
     * @param string $agentsRoot
     * @param array $log
     */
    private static function checkRegistryDrift($agentsRoot, &$log)
    {
        $registryPath = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'database'
            . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'actors'
            . DIRECTORY_SEPARATOR . 'actor_id' . DIRECTORY_SEPARATOR . 'registry.json';

        if (!is_file($registryPath)) {
            $log[] = InstallWizardLogger::logEntry('skip', 'Registry drift check skipped: registry.json not found');
            return;
        }

        $raw = @file_get_contents($registryPath);
        $data = $raw !== false ? json_decode($raw, true) : null;
        if (!is_array($data) || !isset($data['agents']) || !is_array($data['agents'])) {
            $log[] = InstallWizardLogger::logEntry('skip', 'Registry drift check skipped: invalid registry.json agents map');
            return;
        }

        $registrySlugs = array_keys($data['agents']);
        $diskSlugs = array();
        $dirs = glob($agentsRoot . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
        if (is_array($dirs)) {
            foreach ($dirs as $dirPath) {
                $dirName = basename($dirPath);
                if (self::shouldSkipDirectory($dirName)) {
                    continue;
                }
                if (is_file($dirPath . DIRECTORY_SEPARATOR . 'agent.json')) {
                    $diskSlugs[] = $dirName;
                }
            }
        }

        $registrySet = array_flip($registrySlugs);
        $diskSet = array_flip($diskSlugs);

        $registryOnly = array();
        foreach ($registrySlugs as $slug) {
            if (!isset($diskSet[$slug])) {
                $registryOnly[] = $slug;
            }
        }

        $diskOnly = array();
        foreach ($diskSlugs as $slug) {
            if (!isset($registrySet[$slug])) {
                $diskOnly[] = $slug;
            }
        }

        if (!empty($registryOnly)) {
            $log[] = InstallWizardLogger::logEntry(
                'skip',
                'Registry drift: in registry.json but no agents/ folder: ' . implode(', ', $registryOnly)
            );
        }
        if (!empty($diskOnly)) {
            $log[] = InstallWizardLogger::logEntry(
                'skip',
                'Registry drift: agents/ folder but not in registry.json: ' . implode(', ', $diskOnly)
            );
        }
        if (empty($registryOnly) && empty($diskOnly)) {
            $log[] = InstallWizardLogger::logEntry('ok', 'Registry drift check: agents/ slugs match registry.json');
        }
    }
}
