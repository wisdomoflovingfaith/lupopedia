<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "install/InstallWizardLLMConfigLoader.php"
#   web_path: "https://www.lupopedia.com/lupopedia/install/InstallWizardLLMConfigLoader.php"
#   status: "active"
#   when_updated: "20260620162738"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: null
#   atoms_toon: null
#   transcript_jsonl: "0/development/install-wizard-llm-config-loader-php"
#   artifact_type: "implementation"
#   artifact_kind: "tool"
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   content_slug: "install-wizard-llm-config-loader-php"
#   default_collection_id: null
#   lupopedia.schema: "implementation"
#   title: "InstallWizardLLMConfigLoader.php -- seed agent_llm_configs at install"
#   summary: "Idempotent seed of per-agent LLM routing from agent_definitions and provider config."
# ---------------------------------------------------------------------

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'InstallWizardLLMDefaults.php';

/**
 * Seeds agent_llm_configs from agent_definitions and installer provider config.
 */
class InstallWizardLLMConfigLoader
{
    /**
     * Seed or update agent_llm_configs for every agent_definition row.
     *
     * @param PDO $pdo
     * @param array $log
     * @param string $table_prefix
     * @param array $providerConfig LUPO_API_PROVIDER_CONFIG shape
     * @return array summary
     */
    public static function seedAgentLLMConfigs($pdo, &$log, $table_prefix = 'lupo_', $providerConfig = array())
    {
        $summary = array(
            'seeded' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => 0,
        );

        $definitionsTable = preg_replace('/[^a-zA-Z0-9_]/', '', $table_prefix . 'agent_definitions');
        $configsTable = preg_replace('/[^a-zA-Z0-9_]/', '', $table_prefix . 'agent_llm_configs');

        if (!self::tableExists($pdo, $definitionsTable)) {
            $log[] = InstallWizardLogger::logEntry('skip', 'agent_definitions table missing; LLM config seed skipped.');
            return $summary;
        }
        if (!self::tableExists($pdo, $configsTable)) {
            $log[] = InstallWizardLogger::logEntry('skip', 'agent_llm_configs table missing; LLM config seed skipped.');
            return $summary;
        }

        $routing = self::resolveRouting($providerConfig);
        if ($routing['primary_provider'] === '') {
            $log[] = InstallWizardLogger::logEntry('skip', 'No enabled LLM provider in config; agent_llm_configs seed skipped.');
            return $summary;
        }

        $sql = 'SELECT `agent_id`, `agent_key`, `layer`, `status` FROM `' . $definitionsTable . '` WHERE `is_deleted` = 0 ORDER BY `agent_id` ASC';
        $stmt = $pdo->query($sql);
        if (!$stmt) {
            $log[] = InstallWizardLogger::logEntry('error', 'Failed to read agent_definitions for LLM seed.');
            $summary['errors']++;
            return $summary;
        }

        $agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!is_array($agents)) {
            $agents = array();
        }

        $now = (int) gmdate('YmdHis');
        $log[] = InstallWizardLogger::logEntry('ok', 'LLM config seed: processing ' . count($agents) . ' agent_definitions rows.');

        foreach ($agents as $agentRow) {
            $agentId = isset($agentRow['agent_id']) ? (int) $agentRow['agent_id'] : 0;
            if ($agentId <= 0) {
                $summary['skipped']++;
                continue;
            }

            $agentKey = isset($agentRow['agent_key']) ? (string) $agentRow['agent_key'] : '';
            $configId = ($agentId * 10) + 1;

            $safety = array(
                'fallback_provider' => $routing['fallback_provider'],
                'fallback_model' => $routing['fallback_model'],
                'request_class_default' => 'default',
                'seed_source' => 'InstallWizardLLMConfigLoader',
                'agent_key' => $agentKey,
            );
            $safetyJson = json_encode($safety);
            if ($safetyJson === false) {
                $safetyJson = null;
            }

            try {
                $result = self::upsertAgentLlmConfig(
                    $pdo,
                    $configsTable,
                    $configId,
                    $agentId,
                    $routing['primary_provider'],
                    $routing['primary_model'],
                    $routing['temperature'],
                    $routing['max_tokens'],
                    $safetyJson,
                    $now
                );
                if ($result === 'insert') {
                    $summary['seeded']++;
                } elseif ($result === 'update') {
                    $summary['updated']++;
                } else {
                    $summary['skipped']++;
                }
            } catch (Exception $e) {
                $summary['errors']++;
                $log[] = InstallWizardLogger::logEntry('error', 'LLM config seed failed for agent_id ' . $agentId . ': ' . $e->getMessage());
            }
        }

        $log[] = InstallWizardLogger::logEntry(
            'ok',
            'LLM config seed complete: ' . $summary['seeded'] . ' inserted, '
            . $summary['updated'] . ' updated, '
            . $summary['skipped'] . ' skipped, '
            . $summary['errors'] . ' errors'
        );

        return $summary;
    }

    /**
     * @param array $providerConfig
     * @return array
     */
    private static function resolveRouting($providerConfig)
    {
        $defaults = InstallWizardLLMDefaults::defaultModelsPerProvider();
        $global = InstallWizardLLMDefaults::globalLlmDefaults();

        $order = array();
        if (isset($providerConfig['provider_order']) && is_array($providerConfig['provider_order'])) {
            $order = $providerConfig['provider_order'];
        } elseif (isset($providerConfig['fallback_order']) && is_array($providerConfig['fallback_order'])) {
            $order = $providerConfig['fallback_order'];
        }

        $models = isset($providerConfig['models']) && is_array($providerConfig['models'])
            ? $providerConfig['models']
            : $defaults;

        $llmDefaults = isset($providerConfig['llm_defaults']) && is_array($providerConfig['llm_defaults'])
            ? $providerConfig['llm_defaults']
            : $global;

        $enabled = self::pickEnabledProviders($order, $providerConfig);
        $primary = isset($enabled[0]) ? $enabled[0] : '';
        $fallback = isset($enabled[1]) ? $enabled[1] : $primary;

        $primaryModels = isset($models[$primary]) && is_array($models[$primary]) ? $models[$primary] : (isset($defaults[$primary]) ? $defaults[$primary] : array());
        $fallbackModels = isset($models[$fallback]) && is_array($models[$fallback]) ? $models[$fallback] : (isset($defaults[$fallback]) ? $defaults[$fallback] : array());

        $primaryModel = isset($primaryModels['default']) ? (string) $primaryModels['default'] : 'default';
        $fallbackModel = isset($fallbackModels['default']) ? (string) $fallbackModels['default'] : $primaryModel;

        $temperature = isset($llmDefaults['temperature']) ? (float) $llmDefaults['temperature'] : 0.7;
        $maxTokens = isset($llmDefaults['max_tokens']) ? (int) $llmDefaults['max_tokens'] : 2048;

        return array(
            'primary_provider' => $primary,
            'primary_model' => $primaryModel,
            'fallback_provider' => $fallback,
            'fallback_model' => $fallbackModel,
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
        );
    }

    /**
     * @param array $order
     * @param array $providerConfig
     * @return array enabled provider slugs in order
     */
    private static function pickEnabledProviders($order, $providerConfig)
    {
        $out = array();
        $providers = isset($providerConfig['providers']) && is_array($providerConfig['providers'])
            ? $providerConfig['providers']
            : array();

        $count = count($order);
        $index = 0;
        while ($index < $count) {
            $slug = is_string($order[$index]) ? strtolower(trim($order[$index])) : '';
            if ($slug !== '' && !in_array($slug, $out, true)) {
                if (isset($providers[$slug]) && is_array($providers[$slug])) {
                    $cfg = $providers[$slug];
                    $enabled = isset($cfg['enabled']) ? (bool) $cfg['enabled'] : false;
                    $hasKey = false;
                    if (isset($cfg['key']) && trim((string) $cfg['key']) !== '') {
                        $hasKey = true;
                    } elseif (isset($cfg['api_key']) && trim((string) $cfg['api_key']) !== '') {
                        $hasKey = true;
                    }
                    if ($enabled || $hasKey) {
                        $out[] = $slug;
                    }
                }
            }
            $index++;
        }

        if (empty($out)) {
            foreach ($providers as $slug => $cfg) {
                if (!is_array($cfg)) {
                    continue;
                }
                $enabled = isset($cfg['enabled']) ? (bool) $cfg['enabled'] : false;
                if ($enabled) {
                    $out[] = strtolower((string) $slug);
                }
            }
        }

        return $out;
    }

    /**
     * @param PDO $pdo
     * @param string $table
     * @param int $configId
     * @param int $agentId
     * @param string $provider
     * @param string $modelName
     * @param float $temperature
     * @param int $maxTokens
     * @param string|null $safetyJson
     * @param int $now
     * @return string insert|update|skip
     */
    private static function upsertAgentLlmConfig($pdo, $table, $configId, $agentId, $provider, $modelName, $temperature, $maxTokens, $safetyJson, $now)
    {
        $selectSql = 'SELECT `agent_llm_config_id` FROM `' . $table . '` WHERE `agent_id` = :agent_id AND `config_name` = :config_name AND `is_deleted` = 0 LIMIT 1';
        $selectStmt = $pdo->prepare($selectSql);
        $selectStmt->execute(array(
            'agent_id' => $agentId,
            'config_name' => 'default',
        ));
        $existing = $selectStmt->fetch(PDO::FETCH_ASSOC);

        if (is_array($existing) && isset($existing['agent_llm_config_id'])) {
            $updateSql = 'UPDATE `' . $table . '` SET
                `provider` = :provider,
                `model_name` = :model_name,
                `temperature` = :temperature,
                `max_tokens` = :max_tokens,
                `safety_json` = :safety_json,
                `is_active` = 1,
                `updated_ymdhis` = :updated_ymdhis
                WHERE `agent_llm_config_id` = :agent_llm_config_id';
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute(array(
                'provider' => substr($provider, 0, 50),
                'model_name' => substr($modelName, 0, 100),
                'temperature' => $temperature,
                'max_tokens' => $maxTokens,
                'safety_json' => $safetyJson,
                'updated_ymdhis' => $now,
                'agent_llm_config_id' => (int) $existing['agent_llm_config_id'],
            ));
            return 'update';
        }

        $insertSql = 'INSERT INTO `' . $table . '` (
            `agent_llm_config_id`, `agent_id`, `config_name`, `provider`, `model_name`,
            `api_key_id`, `temperature`, `top_p`, `max_tokens`, `presence_penalty`, `frequency_penalty`,
            `timeout_ms`, `cost_per_1k_tokens`, `safety_json`, `response_format`,
            `is_active`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`
        ) VALUES (
            :agent_llm_config_id, :agent_id, :config_name, :provider, :model_name,
            NULL, :temperature, 1.0, :max_tokens, 0.0, 0.0,
            20000, 0.0000, :safety_json, NULL,
            1, :created_ymdhis, :updated_ymdhis, 0, NULL
        )';

        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->execute(array(
            'agent_llm_config_id' => $configId,
            'agent_id' => $agentId,
            'config_name' => 'default',
            'provider' => substr($provider, 0, 50),
            'model_name' => substr($modelName, 0, 100),
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
            'safety_json' => $safetyJson,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
        ));

        return 'insert';
    }

    /**
     * @param PDO $pdo
     * @param string $table
     * @return bool
     */
    private static function tableExists($pdo, $table)
    {
        try {
            $stmt = $pdo->query('SELECT 1 FROM `' . $table . '` LIMIT 1');
            return ($stmt !== false);
        } catch (Exception $e) {
            return false;
        }
    }
}
