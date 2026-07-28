<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "src/LLM/LLMHealthStatus.php"
#   web_path: "https://www.lupopedia.com/lupopedia/src/LLM/LLMHealthStatus.php"
#   status: "active"
#   when_updated: "20260620162738"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: null
#   atoms_toon: null
#   transcript_jsonl: "0/development/llm-health-status-php"
#   artifact_type: "implementation"
#   artifact_kind: "service"
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   content_slug: "llm-health-status-php"
#   default_collection_id: null
#   lupopedia.schema: "implementation"
#   title: "LLMHealthStatus.php -- provider health probe result"
#   summary: "Health status for LLM providers: reachable, key present, models available."
# ---------------------------------------------------------------------

/**
 * Health probe result for an LLM provider.
 */
class LLMHealthStatus
{
    /** @var string */
    public $provider = '';

    /** @var bool */
    public $healthy = false;

    /** @var bool */
    public $hasApiKey = false;

    /** @var string */
    public $message = '';

    /** @var array */
    public $models = array();

    /** @var int checked at UTC YmdHis */
    public $checkedYmdhis = 0;

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'provider' => $this->provider,
            'healthy' => $this->healthy,
            'has_api_key' => $this->hasApiKey,
            'message' => $this->message,
            'models' => $this->models,
            'checked_ymdhis' => $this->checkedYmdhis,
        );
    }
}
