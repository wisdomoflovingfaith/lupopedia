<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "src/LLM/Providers/DeepSeekProvider.php"
#   web_path: "https://www.lupopedia.com/lupopedia/src/LLM/Providers/DeepSeekProvider.php"
#   status: "active"
#   when_updated: "20260620162738"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: null
#   atoms_toon: null
#   transcript_jsonl: "0/development/deepseek-provider-php"
#   artifact_type: "implementation"
#   artifact_kind: "service"
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   content_slug: "deepseek-provider-php"
#   default_collection_id: null
#   lupopedia.schema: "implementation"
#   title: "DeepSeekProvider.php -- DeepSeek LLM provider adapter"
#   summary: "Supports deepseek-chat and deepseek-reasoner with token accounting and latency."
# ---------------------------------------------------------------------

/**
 * DeepSeek API provider (OpenAI-compatible chat completions).
 */
class DeepSeekProvider implements LLMProviderInterface
{
    const API_ENDPOINT = 'https://api.deepseek.com/chat/completions';

    const SUPPORTED_MODELS = array('deepseek-chat', 'deepseek-reasoner');

    /** @var array */
    private $providerConfig = array();

    /** @var array */
    private $modelsConfig = array();

    /**
     * @param array $providerConfig enabled, key/api_key, budget
     * @param array $modelsConfig per-provider models map from LUPO_API_PROVIDER_CONFIG
     */
    public function __construct($providerConfig, $modelsConfig = array())
    {
        $this->providerConfig = is_array($providerConfig) ? $providerConfig : array();
        $this->modelsConfig = is_array($modelsConfig) ? $modelsConfig : array();
    }

    /**
     * @inheritdoc
     */
    public function getProviderSlug()
    {
        return 'deepseek';
    }

    /**
     * @inheritdoc
     */
    public function getCapabilities()
    {
        return array(
            'provider' => 'deepseek',
            'models' => self::SUPPORTED_MODELS,
            'features' => array('chat', 'reasoning'),
            'default_model' => $this->resolveModelName('default'),
            'complex_model' => $this->resolveModelName('complex'),
            'audit_model' => $this->resolveModelName('audit'),
            'max_tokens_limit' => 8192,
            'supports_streaming' => false,
        );
    }

    /**
     * @inheritdoc
     */
    public function getHealth()
    {
        $status = new LLMHealthStatus();
        $status->provider = 'deepseek';
        $status->checkedYmdhis = (int) gmdate('YmdHis');
        $status->hasApiKey = ($this->getApiKey() !== '');
        $status->models = self::SUPPORTED_MODELS;

        if (!$status->hasApiKey) {
            $status->healthy = false;
            $status->message = 'API key not configured';
            return $status;
        }

        $status->healthy = true;
        $status->message = 'API key present';
        return $status;
    }

    /**
     * @inheritdoc
     */
    public function send($messages, $options = array())
    {
        $started = microtime(true);
        $response = new LLMResponse();
        $response->provider = 'deepseek';

        if (!is_array($messages) || empty($messages)) {
            $response->errorMessage = 'messages array is required';
            $response->latencyMs = $this->elapsedMs($started);
            return $response;
        }

        $apiKey = $this->getApiKey();
        if ($apiKey === '') {
            $response->errorMessage = 'DeepSeek API key not configured';
            $response->latencyMs = $this->elapsedMs($started);
            return $response;
        }

        $requestClass = isset($options['request_class']) ? (string) $options['request_class'] : 'default';
        if ($requestClass === '') {
            $requestClass = 'default';
        }

        $model = isset($options['model']) && trim((string) $options['model']) !== ''
            ? trim((string) $options['model'])
            : $this->resolveModelName($requestClass);

        $temperature = isset($options['temperature']) ? (float) $options['temperature'] : $this->resolveFloatOption('temperature', 0.7);
        $maxTokens = isset($options['max_tokens']) ? (int) $options['max_tokens'] : (int) $this->resolveFloatOption('max_tokens', 2048);
        if ($maxTokens < 1) {
            $maxTokens = 2048;
        }

        $reasoningMode = false;
        if (isset($options['reasoning_mode'])) {
            $reasoningMode = (bool) $options['reasoning_mode'];
        } elseif (isset($this->modelsConfig['reasoning_mode'])) {
            $reasoningMode = (bool) $this->modelsConfig['reasoning_mode'];
        }
        if ($reasoningMode && $model === 'deepseek-chat') {
            $model = 'deepseek-reasoner';
        }

        $payload = array(
            'model' => $model,
            'messages' => $messages,
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
        );

        $httpResult = $this->postJson(self::API_ENDPOINT, $payload, $apiKey);
        $response->latencyMs = $this->elapsedMs($started);
        $response->model = $model;
        $response->statusCode = isset($httpResult['status_code']) ? (int) $httpResult['status_code'] : 0;

        if (!isset($httpResult['success']) || !$httpResult['success']) {
            $response->errorMessage = isset($httpResult['error']) ? (string) $httpResult['error'] : 'DeepSeek request failed';
            if (isset($httpResult['body']) && is_array($httpResult['body']) && isset($httpResult['body']['error']['message'])) {
                $response->errorMessage = (string) $httpResult['body']['error']['message'];
            }
            return $response;
        }

        $body = isset($httpResult['body']) && is_array($httpResult['body']) ? $httpResult['body'] : array();
        $content = '';
        if (isset($body['choices'][0]['message']['content'])) {
            $content = (string) $body['choices'][0]['message']['content'];
        }

        $usage = isset($body['usage']) && is_array($body['usage']) ? $body['usage'] : array();
        $response->success = ($content !== '');
        $response->content = $content;
        $response->promptTokens = isset($usage['prompt_tokens']) ? (int) $usage['prompt_tokens'] : 0;
        $response->completionTokens = isset($usage['completion_tokens']) ? (int) $usage['completion_tokens'] : 0;
        $response->totalTokens = isset($usage['total_tokens']) ? (int) $usage['total_tokens'] : ($response->promptTokens + $response->completionTokens);
        $response->rawMeta = array(
            'request_class' => $requestClass,
            'id' => isset($body['id']) ? (string) $body['id'] : '',
        );

        if (!$response->success && $response->errorMessage === '') {
            $response->errorMessage = 'Empty completion from DeepSeek';
        }

        return $response;
    }

    /**
     * @param string $requestClass default|complex|audit
     * @return string
     */
    private function resolveModelName($requestClass)
    {
        $classKey = is_string($requestClass) ? strtolower(trim($requestClass)) : 'default';
        if ($classKey === '') {
            $classKey = 'default';
        }

        if (isset($this->modelsConfig[$classKey]) && trim((string) $this->modelsConfig[$classKey]) !== '') {
            return trim((string) $this->modelsConfig[$classKey]);
        }
        if (isset($this->modelsConfig['default']) && trim((string) $this->modelsConfig['default']) !== '') {
            return trim((string) $this->modelsConfig['default']);
        }

        if ($classKey === 'complex' || $classKey === 'audit') {
            return 'deepseek-reasoner';
        }

        return 'deepseek-chat';
    }

    /**
     * @param string $key
     * @param float $default
     * @return float
     */
    private function resolveFloatOption($key, $default)
    {
        if (isset($this->modelsConfig[$key]) && is_numeric($this->modelsConfig[$key])) {
            return (float) $this->modelsConfig[$key];
        }
        return $default;
    }

    /**
     * @return string
     */
    private function getApiKey()
    {
        if (isset($this->providerConfig['key']) && trim((string) $this->providerConfig['key']) !== '') {
            return trim((string) $this->providerConfig['key']);
        }
        if (isset($this->providerConfig['api_key']) && trim((string) $this->providerConfig['api_key']) !== '') {
            return trim((string) $this->providerConfig['api_key']);
        }
        return '';
    }

    /**
     * @param string $url
     * @param array $payload
     * @param string $apiKey
     * @return array
     */
    private function postJson($url, $payload, $apiKey)
    {
        $jsonBody = json_encode($payload);
        if ($jsonBody === false) {
            return array('success' => false, 'error' => 'Failed to encode request JSON', 'status_code' => 0);
        }

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
        );

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            if ($ch === false) {
                return array('success' => false, 'error' => 'curl_init failed', 'status_code' => 0);
            }
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);

            $raw = curl_exec($ch);
            $statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($raw === false) {
                return array('success' => false, 'error' => $curlError !== '' ? $curlError : 'curl_exec failed', 'status_code' => $statusCode);
            }

            $decoded = json_decode($raw, true);
            if (!is_array($decoded)) {
                return array('success' => false, 'error' => 'Invalid JSON response from DeepSeek', 'status_code' => $statusCode, 'body' => array());
            }

            if ($statusCode < 200 || $statusCode >= 300) {
                $errMsg = 'HTTP ' . $statusCode;
                if (isset($decoded['error']['message'])) {
                    $errMsg = (string) $decoded['error']['message'];
                }
                return array('success' => false, 'error' => $errMsg, 'status_code' => $statusCode, 'body' => $decoded);
            }

            return array('success' => true, 'status_code' => $statusCode, 'body' => $decoded);
        }

        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => implode("\r\n", $headers),
                'content' => $jsonBody,
                'timeout' => 120,
                'ignore_errors' => true,
            ),
        ));

        $raw = @file_get_contents($url, false, $context);
        if ($raw === false) {
            return array('success' => false, 'error' => 'HTTP request failed (no curl extension)', 'status_code' => 0);
        }

        $statusCode = 0;
        if (isset($http_response_header) && is_array($http_response_header) && isset($http_response_header[0])) {
            if (preg_match('/\s(\d{3})\s/', $http_response_header[0], $matches)) {
                $statusCode = (int) $matches[1];
            }
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return array('success' => false, 'error' => 'Invalid JSON response from DeepSeek', 'status_code' => $statusCode);
        }

        if ($statusCode < 200 || $statusCode >= 300) {
            $errMsg = 'HTTP ' . $statusCode;
            if (isset($decoded['error']['message'])) {
                $errMsg = (string) $decoded['error']['message'];
            }
            return array('success' => false, 'error' => $errMsg, 'status_code' => $statusCode, 'body' => $decoded);
        }

        return array('success' => true, 'status_code' => $statusCode, 'body' => $decoded);
    }

    /**
     * @param float $started microtime(true)
     * @return int
     */
    private function elapsedMs($started)
    {
        return (int) round((microtime(true) - $started) * 1000);
    }
}
