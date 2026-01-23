<?php

namespace Lupopedia\Dialog\LLM;

/**
 * OpenAI LLM Provider
 * 
 * Implements LLM interface for OpenAI GPT models.
 * Follows Lupopedia doctrine: application logic first, no database dependencies.
 * 
 * @package Lupopedia\Dialog\LLM
 * @version 4.0.46
 * @author Captain Wolfie
 */
class OpenAIProvider implements LLMInterface
{
    private string $apiKey;
    private string $model;
    private array $config;
    private array $usageStats;
    
    public function __construct(array $config)
    {
        $this->apiKey = $config['api_key'] ?? '';
        $this->model = $config['model'] ?? 'gpt-3.5-turbo';
        $this->config = array_merge([
            'max_tokens' => 1000,
            'temperature' => 0.7,
            'timeout' => 30,
            'base_url' => 'https://api.openai.com/v1'
        ], $config);
        
        $this->usageStats = [
            'total_requests' => 0,
            'total_tokens' => 0,
            'rate_limit_remaining' => null,
            'last_request_time' => null
        ];
    }
    
    /**
     * Generate response using OpenAI API
     */
    public function generateResponse(string $prompt, array $context = [], array $options = []): array
    {
        $options = array_merge($this->config, $options);
        
        // Build messages array for OpenAI
        $messages = $this->buildMessages($prompt, $context);
        
        $requestData = [
            'model' => $this->model,
            'messages' => $messages,
            'max_tokens' => $options['max_tokens'] ?? $this->config['max_tokens'],
            'temperature' => $options['temperature'] ?? $this->config['temperature']
        ];
        
        try {
            $response = $this->makeRequest('chat/completions', $requestData);
            
            if ($response && isset($response['choices'][0]['message'])) {
                $this->updateUsageStats($response);
                
                return [
                    'content' => $response['choices'][0]['message']['content'],
                    'role' => $response['choices'][0]['message']['role'],
                    'finish_reason' => $response['choices'][0]['finish_reason'] ?? null,
                    'usage' => $response['usage'] ?? [],
                    'model' => $response['model'] ?? $this->model,
                    'created' => $response['created'] ?? null,
                    'provider' => 'openai',
                    'success' => true
                ];
            }
            
            return [
                'content' => 'Failed to generate response',
                'error' => 'Invalid API response',
                'provider' => 'openai',
                'success' => false
            ];
        } catch (\Exception $e) {
            return [
                'content' => 'Failed to generate response',
                'error' => $e->getMessage(),
                'provider' => 'openai',
                'success' => false
            ];
        }
    }
    
    /**
     * Get model information
     */
    public function getModelInfo(): array
    {
        return [
            'provider' => 'openai',
            'model' => $this->model,
            'max_tokens' => $this->config['max_tokens'],
            'temperature' => $this->config['temperature'],
            'capabilities' => [
                'chat_completion' => true,
                'function_calling' => true,
                'json_mode' => true,
                'vision' => false
            ],
            'rate_limits' => [
                'requests_per_minute' => 60,
                'tokens_per_minute' => 90000,
                'tokens_per_day' => 1000000
            ]
        ];
    }
    
    /**
     * Validate configuration
     */
    public function validateConfig(): bool
    {
        return !empty($this->apiKey) && 
               !empty($this->model) &&
               $this->config['max_tokens'] > 0 &&
               $this->config['temperature'] >= 0 &&
               $this->config['temperature'] <= 2;
    }
    
    /**
     * Get usage statistics
     */
    public function getUsageStats(): array
    {
        return $this->usageStats;
    }
    
    /**
     * Build messages array for OpenAI
     */
    private function buildMessages(string $prompt, array $context): array
    {
        $messages = [];
        
        // Add context messages
        foreach ($context as $message) {
            $messages[] = [
                'role' => $this->mapRole($message['type'] ?? 'user'),
                'content' => $message['content'] ?? ''
            ];
        }
        
        // Add current prompt
        $messages[] = [
            'role' => 'user',
            'content' => $prompt
        ];
        
        return $messages;
    }
    
    /**
     * Map role names to OpenAI format
     */
    private function mapRole(string $role): string
    {
        $roleMap = [
            'user' => 'user',
            'assistant' => 'assistant',
            'system' => 'system',
            'agent' => 'assistant',
            'human' => 'user'
        ];
        
        return $roleMap[$role] ?? 'user';
    }
    
    /**
     * Make HTTP request to OpenAI API
     */
    private function makeRequest(string $endpoint, array $data): array
    {
        $url = $this->config['base_url'] . '/' . $endpoint;
        
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => $this->config['timeout'],
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new \RuntimeException("CURL error: $error");
        }
        
        if ($httpCode !== 200) {
            throw new \RuntimeException("HTTP error: $httpCode");
        }
        
        return json_decode($response, true) ?? [];
    }
    
    /**
     * Update usage statistics
     */
    private function updateUsageStats(array $response): void
    {
        $this->usageStats['total_requests']++;
        $this->usageStats['last_request_time'] = date('YmdHis');
        
        if (isset($response['usage'])) {
            $this->usageStats['total_tokens'] += $response['usage']['total_tokens'] ?? 0;
            $this->usageStats['rate_limit_remaining'] = $response['usage']['rate_limit_remaining'] ?? null;
        }
    }
}
