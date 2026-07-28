<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "src/LLM/LLMProviderFactory.php"
#   web_path: "https://www.lupopedia.com/lupopedia/src/LLM/LLMProviderFactory.php"
#   status: "active"
#   when_updated: "20260620162738"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: null
#   atoms_toon: null
#   transcript_jsonl: "0/development/llm-provider-factory-php"
#   artifact_type: "implementation"
#   artifact_kind: "service"
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   content_slug: "llm-provider-factory-php"
#   default_collection_id: null
#   lupopedia.schema: "implementation"
#   title: "LLMProviderFactory.php -- instantiate registered LLM providers"
#   summary: "Builds provider implementations from runtime config and slug."
# ---------------------------------------------------------------------

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'LLMResponse.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'LLMHealthStatus.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'LLMProviderInterface.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Providers' . DIRECTORY_SEPARATOR . 'DeepSeekProvider.php';

/**
 * Factory for LLM provider implementations.
 */
class LLMProviderFactory
{
    /**
     * @param string $providerSlug
     * @param array $providerConfig
     * @param array $modelsConfig
     * @return LLMProviderInterface|null
     */
    public static function create($providerSlug, $providerConfig, $modelsConfig = array())
    {
        $slug = is_string($providerSlug) ? strtolower(trim($providerSlug)) : '';
        if ($slug === '') {
            return null;
        }

        if ($slug === 'deepseek') {
            return new DeepSeekProvider($providerConfig, $modelsConfig);
        }

        return null;
    }
}
