<?php

namespace Lupopedia\Dialog\LLM;

/**
 * LLM Interface for Dialog System
 * 
 * Defines contract for Large Language Model integration.
 * Supports multiple LLM providers with unified interface.
 * 
 * @package Lupopedia\Dialog\LLM
 * @version 4.0.46
 * @author Captain Wolfie
 */
interface LLMInterface
{
    /**
     * Generate response based on prompt and context
     * 
     * @param string $prompt The user prompt or message
     * @param array $context Previous messages/conversation history
     * @param array $options Additional options (max_tokens, temperature, etc.)
     * @return array Generated response with metadata
     */
    public function generateResponse(string $prompt, array $context = [], array $options = []): array;
    
    /**
     * Get model information
     * 
     * @return array Model capabilities and configuration
     */
    public function getModelInfo(): array;
    
    /**
     * Validate configuration
     * 
     * @return bool True if configuration is valid
     */
    public function validateConfig(): bool;
    
    /**
     * Get current usage statistics
     * 
     * @return array Token usage and rate limit information
     */
    public function getUsageStats(): array;
}
