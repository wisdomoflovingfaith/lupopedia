<?php

/**
 * IRIS — External AI Gateway / LLM Faucet
 *
 * ROLE:
 *   - Receives a target agent_id and message packet
 *   - Loads agent configuration (system prompt, persona, rules)
 *   - Builds an LLM request payload
 *   - Sends request to external provider (OpenAI, DeepSeek, Gemini, etc.)
 *   - Returns the model's response text
 *
 * DOCTRINE:
 *   - IRIS is NOT an agent
 *   - IRIS is NOT a router (that's HERMES)
 *   - IRIS is NOT memory (that's WOLFMIND)
 *   - IRIS is the "faucet" that turns LLM thinking on/off
 *
 * PHASE 1:
 *   - Minimal implementation
 *   - Single provider (OpenAI-style JSON POST)
 *   - No streaming
 *   - No multi-model switching yet
 */

class IRIS
{
    protected $db;
    protected $pdo;

    public function __construct($db)
    {
        $this->db  = $db;
        $this->pdo = $db->getPdo();
    }

    /**
     * Main entry point for invoking an agent through an LLM.
     *
     * @param int   $agentId
     * @param array $packet
     * @return string  The LLM-generated response text
     */
    public function invokeAgent(int $agentId, array $packet): string
    {
        // ---------------------------------------------------------
        // 1. Load agent configuration
        // ---------------------------------------------------------
        $agent = $this->loadAgentConfig($agentId);

        if (!$agent) {
            return "[IRIS ERROR] Unknown agent_id: {$agentId}";
        }

        // ---------------------------------------------------------
        // 2. Build LLM prompt
        // ---------------------------------------------------------
        $systemPrompt = isset($agent['system_prompt']) ? $agent['system_prompt'] : "You are agent " . (isset($agent['slug']) ? $agent['slug'] : (isset($agent['id']) ? $agent['id'] : 'unknown')) . ".";
        $persona      = isset($agent['persona']) ? $agent['persona'] : "";
        $rules        = isset($agent['rules']) ? $agent['rules'] : "";

        $userMessage  = $packet['content'] ?? "";

        $messages = [
            [
                'role'    => 'system',
                'content' => $systemPrompt . "\n\n" . $persona . "\n\n" . $rules
            ],
            [
                'role'    => 'user',
                'content' => $userMessage
            ]
        ];

        // ---------------------------------------------------------
        // 3. Send to external LLM provider
        // ---------------------------------------------------------
        $response = $this->callLLMProvider($messages);

        if (!$response) {
            return "[IRIS ERROR] LLM provider returned no response.";
        }

        return $response;
    }

    /**
     * Load agent/service configuration from actors tables only.
     * Doctrine: registry is NOT a config table. All agent config comes from
     * lupo_actors + lupo_actor_properties (actor_type = 'agent' or 'service').
     */
    protected function loadAgentConfig(int $agentId)
    {
        $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $actorsTable = $prefix . 'actors';
        $propsTable = $prefix . 'actor_properties';

        $actorSql = "SELECT actor_id, slug, name, metadata
                     FROM " . $actorsTable . "
                     WHERE actor_id = :agent_id
                       AND actor_type IN ('agent', 'service')
                       AND is_active = 1
                       AND (is_deleted = 0 OR is_deleted IS NULL)
                     LIMIT 1";
        $agent = $this->db->fetchRow($actorSql, array('agent_id' => $agentId));
        if (!$agent) {
            return null;
        }

        $agent['id'] = $agent['actor_id'];
        $propsSql = "SELECT property_key, property_value FROM " . $propsTable . "
                     WHERE actor_id = :actor_id AND is_deleted = 0";
        $properties = $this->db->fetchAll($propsSql, array('actor_id' => $agentId));
        $props = array();
        foreach ($properties as $prop) {
            $props[$prop['property_key']] = $prop['property_value'];
        }

        $display = (isset($agent['name']) && $agent['name'] !== '') ? $agent['name'] : (isset($agent['slug']) ? $agent['slug'] : (string) $agent['id']);
        $agent['system_prompt'] = isset($props['system_prompt']) ? $props['system_prompt'] : "You are " . $display . ".";
        $agent['persona'] = isset($props['persona']) ? $props['persona'] : "";
        $agent['rules'] = isset($props['rules']) ? $props['rules'] : "";

        return $agent;
    }

    /**
     * Call the external LLM provider.
     *
     * PHASE 1:
     *   - Simple JSON POST
     *   - No streaming
     *   - No retries
     *   - No multi-provider switching
     *
     * Replace the URL + headers with your actual provider.
     */
    protected function callLLMProvider(array $messages): ?string
    {
        // Get provider configuration from constants
        $provider = defined('LLM_PROVIDER') ? LLM_PROVIDER : 'openai';
        $model = defined('LLM_MODEL') ? LLM_MODEL : 'gpt-4o-mini';
        
        // Set provider-specific URL and API key
        switch (strtolower($provider)) {
            case 'deepseek':
                $url = "https://api.deepseek.com/v1/chat/completions";
                $apiKey = defined('DEEPSEEK_API_KEY') ? DEEPSEEK_API_KEY : '';
                break;
            case 'openai':
            default:
                $url = "https://api.openai.com/v1/chat/completions";
                $apiKey = defined('OPENAI_API_KEY') ? OPENAI_API_KEY : '';
                break;
        }

        if (empty($apiKey)) {
            error_log("[IRIS ERROR] No API key configured for provider: {$provider}");
            return "[IRIS ERROR] LLM provider API key not configured. Please set OPENAI_API_KEY or DEEPSEEK_API_KEY in lupopedia-config.php";
        }

        $payload = [
            'model'       => $model,
            'messages'    => $messages,
            'temperature' => 0.7
        ];

        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer {$apiKey}"
        ];

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 10
        ]);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            error_log("[IRIS ERROR] cURL error: {$curlError}");
            return "[IRIS ERROR] Failed to connect to LLM provider: {$curlError}";
        }

        if ($httpCode !== 200) {
            error_log("[IRIS ERROR] HTTP {$httpCode} from LLM provider. Response: {$result}");
            return "[IRIS ERROR] LLM provider returned HTTP {$httpCode}";
        }

        if (!$result) {
            return null;
        }

        $json = json_decode($result, true);

        if (isset($json['error'])) {
            error_log("[IRIS ERROR] LLM provider error: " . json_encode($json['error']));
            return "[IRIS ERROR] " . ($json['error']['message'] ?? 'Unknown error from LLM provider');
        }

        return $json['choices'][0]['message']['content'] ?? null;
    }
}

?>
