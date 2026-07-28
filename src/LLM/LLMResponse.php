<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "src/LLM/LLMResponse.php"
#   web_path: "https://www.lupopedia.com/lupopedia/src/LLM/LLMResponse.php"
#   status: "active"
#   when_updated: "20260620162738"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: null
#   atoms_toon: null
#   transcript_jsonl: "0/development/llm-response-php"
#   artifact_type: "implementation"
#   artifact_kind: "service"
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   content_slug: "llm-response-php"
#   default_collection_id: null
#   lupopedia.schema: "implementation"
#   title: "LLMResponse.php -- normalized LLM provider response envelope"
#   summary: "Value object for provider send() results: text, usage, latency, model, errors."
# ---------------------------------------------------------------------

/**
 * Normalized response envelope returned by LLM providers.
 */
class LLMResponse
{
    /** @var bool */
    public $success = false;

    /** @var string */
    public $content = '';

    /** @var string */
    public $model = '';

    /** @var string */
    public $provider = '';

    /** @var int */
    public $promptTokens = 0;

    /** @var int */
    public $completionTokens = 0;

    /** @var int */
    public $totalTokens = 0;

    /** @var int latency in milliseconds */
    public $latencyMs = 0;

    /** @var string */
    public $errorMessage = '';

    /** @var int HTTP or provider status code */
    public $statusCode = 0;

    /** @var array raw provider payload subset for logging */
    public $rawMeta = array();

    /**
     * @param array $data
     * @return LLMResponse
     */
    public static function fromArray($data)
    {
        $response = new LLMResponse();
        if (!is_array($data)) {
            return $response;
        }
        if (isset($data['success'])) {
            $response->success = (bool) $data['success'];
        }
        if (isset($data['content'])) {
            $response->content = (string) $data['content'];
        }
        if (isset($data['model'])) {
            $response->model = (string) $data['model'];
        }
        if (isset($data['provider'])) {
            $response->provider = (string) $data['provider'];
        }
        if (isset($data['prompt_tokens'])) {
            $response->promptTokens = (int) $data['prompt_tokens'];
        }
        if (isset($data['completion_tokens'])) {
            $response->completionTokens = (int) $data['completion_tokens'];
        }
        if (isset($data['total_tokens'])) {
            $response->totalTokens = (int) $data['total_tokens'];
        }
        if (isset($data['latency_ms'])) {
            $response->latencyMs = (int) $data['latency_ms'];
        }
        if (isset($data['error_message'])) {
            $response->errorMessage = (string) $data['error_message'];
        }
        if (isset($data['status_code'])) {
            $response->statusCode = (int) $data['status_code'];
        }
        if (isset($data['raw_meta']) && is_array($data['raw_meta'])) {
            $response->rawMeta = $data['raw_meta'];
        }
        return $response;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'success' => $this->success,
            'content' => $this->content,
            'model' => $this->model,
            'provider' => $this->provider,
            'prompt_tokens' => $this->promptTokens,
            'completion_tokens' => $this->completionTokens,
            'total_tokens' => $this->totalTokens,
            'latency_ms' => $this->latencyMs,
            'error_message' => $this->errorMessage,
            'status_code' => $this->statusCode,
            'raw_meta' => $this->rawMeta,
        );
    }
}
