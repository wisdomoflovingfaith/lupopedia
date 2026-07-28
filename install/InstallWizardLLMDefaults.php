<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "install/InstallWizardLLMDefaults.php"
#   web_path: "https://www.lupopedia.com/lupopedia/install/InstallWizardLLMDefaults.php"
#   status: "active"
#   when_updated: "20260620162738"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: null
#   atoms_toon: null
#   transcript_jsonl: "0/development/install-wizard-llm-defaults-php"
#   artifact_type: "implementation"
#   artifact_kind: "tool"
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   content_slug: "install-wizard-llm-defaults-php"
#   default_collection_id: null
#   lupopedia.schema: "implementation"
#   title: "InstallWizardLLMDefaults.php -- default LLM model maps for installer"
#   summary: "Canonical default models per provider and request class for install wizard."
# ---------------------------------------------------------------------

/**
 * Default LLM model configuration for the install wizard.
 */
class InstallWizardLLMDefaults
{
    /**
     * Default model names per provider and request class.
     *
     * @return array
     */
    public static function defaultModelsPerProvider()
    {
        return array(
            'deepseek' => array(
                'default' => 'deepseek-chat',
                'complex' => 'deepseek-reasoner',
                'audit' => 'deepseek-reasoner',
                'temperature' => 0.7,
                'max_tokens' => 2048,
                'reasoning_mode' => 0,
            ),
            'gemini' => array(
                'default' => 'gemini-2.0-flash',
                'complex' => 'gemini-2.0-flash',
                'audit' => 'gemini-2.0-flash',
                'temperature' => 0.7,
                'max_tokens' => 2048,
                'reasoning_mode' => 0,
            ),
            'groq' => array(
                'default' => 'llama-3.3-70b-versatile',
                'complex' => 'llama-3.3-70b-versatile',
                'audit' => 'llama-3.3-70b-versatile',
                'temperature' => 0.7,
                'max_tokens' => 2048,
                'reasoning_mode' => 0,
            ),
            'anthropic' => array(
                'default' => 'claude-3-5-sonnet-20241022',
                'complex' => 'claude-3-5-sonnet-20241022',
                'audit' => 'claude-3-5-sonnet-20241022',
                'temperature' => 0.7,
                'max_tokens' => 2048,
                'reasoning_mode' => 0,
            ),
            'grok' => array(
                'default' => 'grok-beta',
                'complex' => 'grok-beta',
                'audit' => 'grok-beta',
                'temperature' => 0.7,
                'max_tokens' => 2048,
                'reasoning_mode' => 0,
            ),
            'openai' => array(
                'default' => 'gpt-4o-mini',
                'complex' => 'gpt-4o',
                'audit' => 'gpt-4o',
                'temperature' => 0.7,
                'max_tokens' => 2048,
                'reasoning_mode' => 0,
            ),
        );
    }

    /**
     * Global LLM defaults (temperature, max_tokens, reasoning toggle).
     *
     * @return array
     */
    public static function globalLlmDefaults()
    {
        return array(
            'temperature' => 0.7,
            'max_tokens' => 2048,
            'reasoning_mode' => 0,
        );
    }

    /**
     * Build models block from POST/session values merged with defaults.
     *
     * @param array $input keyed by provider slug
     * @return array
     */
    public static function buildModelsConfig($input)
    {
        $defaults = self::defaultModelsPerProvider();
        $models = array();

        foreach ($defaults as $provider => $providerDefaults) {
            $row = $providerDefaults;
            if (isset($input[$provider]) && is_array($input[$provider])) {
                foreach ($providerDefaults as $field => $defaultValue) {
                    if (isset($input[$provider][$field]) && $input[$provider][$field] !== '') {
                        if ($field === 'temperature') {
                            $row[$field] = (float) $input[$provider][$field];
                        } elseif ($field === 'max_tokens' || $field === 'reasoning_mode') {
                            $row[$field] = (int) $input[$provider][$field];
                        } else {
                            $row[$field] = trim((string) $input[$provider][$field]);
                        }
                    }
                }
            }
            $models[$provider] = $row;
        }

        if (isset($input['custom']) && is_array($input['custom'])) {
            foreach ($input['custom'] as $provider => $customRow) {
                if (!is_array($customRow)) {
                    continue;
                }
                $models[$provider] = array_merge(array(
                    'default' => 'default',
                    'complex' => 'default',
                    'audit' => 'default',
                    'temperature' => 0.7,
                    'max_tokens' => 2048,
                    'reasoning_mode' => 0,
                ), $customRow);
            }
        }

        return $models;
    }

    /**
     * Parse installer POST into per-provider model input array.
     *
     * @param array $post $_POST
     * @return array
     */
    public static function parseModelPost($post)
    {
        $providers = array('deepseek', 'gemini', 'groq', 'anthropic', 'grok', 'openai');
        $input = array();
        $classes = array('default', 'complex', 'audit');

        foreach ($providers as $provider) {
            $row = array();
            foreach ($classes as $classKey) {
                $field = 'model_' . $provider . '_' . $classKey;
                if (isset($post[$field])) {
                    $row[$classKey] = trim((string) $post[$field]);
                }
            }
            $tempField = 'model_' . $provider . '_temperature';
            $maxField = 'model_' . $provider . '_max_tokens';
            $reasonField = 'model_' . $provider . '_reasoning_mode';
            if (isset($post[$tempField])) {
                $row['temperature'] = trim((string) $post[$tempField]);
            }
            if (isset($post[$maxField])) {
                $row['max_tokens'] = trim((string) $post[$maxField]);
            }
            $row['reasoning_mode'] = (isset($post[$reasonField]) && $post[$reasonField] === '1') ? 1 : 0;
            $input[$provider] = $row;
        }

        return $input;
    }

    /**
     * Flat defaults for api_keys form display.
     *
     * @return array
     */
    public static function formDisplayDefaults()
    {
        $models = self::defaultModelsPerProvider();
        $global = self::globalLlmDefaults();
        $out = array('llm_global' => $global);

        foreach ($models as $provider => $row) {
            $out[$provider] = $row;
        }

        return $out;
    }
}
