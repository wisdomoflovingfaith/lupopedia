<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "src/LLM/LLMProviderInterface.php"
#   web_path: "https://www.lupopedia.com/lupopedia/src/LLM/LLMProviderInterface.php"
#   status: "active"
#   when_updated: "20260620162738"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: null
#   atoms_toon: null
#   transcript_jsonl: "0/development/llm-provider-interface-php"
#   artifact_type: "implementation"
#   artifact_kind: "service"
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   content_slug: "llm-provider-interface-php"
#   default_collection_id: null
#   lupopedia.schema: "implementation"
#   title: "LLMProviderInterface.php -- LLM provider contract"
#   summary: "Interface for BYOK LLM providers: send, capabilities, health, slug."
# ---------------------------------------------------------------------

/**
 * Contract for runtime LLM providers (BYOK, installer-seeded config).
 */
interface LLMProviderInterface
{
    /**
     * Send a chat completion request.
     *
     * @param array $messages OpenAI-style message list
     * @param array $options model, temperature, max_tokens, request_class, etc.
     * @return LLMResponse
     */
    public function send($messages, $options = array());

    /**
     * Provider capabilities: supported models, features, limits.
     *
     * @return array
     */
    public function getCapabilities();

    /**
     * Lightweight health check (key present, endpoint reachable when possible).
     *
     * @return LLMHealthStatus
     */
    public function getHealth();

    /**
     * Stable provider slug (e.g. deepseek, gemini).
     *
     * @return string
     */
    public function getProviderSlug();
}
