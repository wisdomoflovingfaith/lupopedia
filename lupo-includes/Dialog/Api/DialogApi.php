<?php

namespace Lupopedia\Dialog\Api;

use Lupopedia\Dialog\Database\DialogDatabase;
use Lupopedia\Dialog\LLM\LLMInterface;
use PDO;

/**
 * Dialog API Endpoint
 * 
 * Provides REST API endpoints for dialog system operations.
 * Follows Lupopedia doctrine: application logic first, database logic second.
 * 
 * @package Lupopedia\Dialog\Api
 * @version 4.0.46
 * @author Captain Wolfie
 */
class DialogApi
{
    private DialogDatabase $db;
    private LLMInterface $llm;
    private array $config;
    
    public function __construct(DialogDatabase $db, LLMInterface $llm, array $config = [])
    {
        $this->db = $db;
        $this->llm = $llm;
        $this->config = array_merge([
            'max_message_length' => 10000,
            'rate_limit_per_minute' => 60,
            'enable_cors' => true,
            'cors_origins' => ['*']
        ], $config);
    }
    
    /**
     * Handle API request
     */
    public function handleRequest(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
        $path = trim($path, '/');
        
        // CORS headers
        if ($this->config['enable_cors']) {
            $this->sendCorsHeaders();
        }
        
        try {
            switch ($method) {
                case 'GET':
                    $this->handleGetRequest($path);
                    break;
                case 'POST':
                    $this->handlePostRequest($path);
                    break;
                case 'PUT':
                    $this->handlePutRequest($path);
                    break;
                case 'DELETE':
                    $this->handleDeleteRequest($path);
                    break;
                default:
                    $this->sendJsonResponse(['error' => 'Method not allowed'], 405);
            }
        } catch (\Exception $e) {
            $this->sendJsonResponse([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Handle GET requests
     */
    private function handleGetRequest(string $path): void
    {
        $segments = explode('/', $path);
        
        switch ($segments[0] ?? '') {
            case 'threads':
                $this->getThreads($segments[1] ?? null);
                break;
            case 'messages':
                $this->getMessages($segments[1] ?? null);
                break;
            case 'channels':
                $this->getChannels($segments[1] ?? null);
                break;
            case 'actors':
                $this->getActors($segments[1] ?? null);
                break;
            case 'stats':
                $this->getStatistics();
                break;
            default:
                $this->sendJsonResponse(['error' => 'Endpoint not found'], 404);
        }
    }
    
    /**
     * Handle POST requests
     */
    private function handlePostRequest(string $path): void
    {
        $segments = explode('/', $path);
        $input = $this->getJsonInput();
        
        switch ($segments[0] ?? '') {
            case 'threads':
                $this->createThread($input);
                break;
            case 'messages':
                $this->createMessage($input);
                break;
            case 'generate':
                $this->generateResponse($input);
                break;
            default:
                $this->sendJsonResponse(['error' => 'Endpoint not found'], 404);
        }
    }
    
    /**
     * Handle PUT requests
     */
    private function handlePutRequest(string $path): void
    {
        $segments = explode('/', $path);
        $input = $this->getJsonInput();
        
        switch ($segments[0] ?? '') {
            case 'threads':
                $this->updateThread($segments[1] ?? null, $input);
                break;
            case 'messages':
                $this->updateMessage($segments[1] ?? null, $input);
                break;
            default:
                $this->sendJsonResponse(['error' => 'Endpoint not found'], 404);
        }
    }
    
    /**
     * Handle DELETE requests
     */
    private function handleDeleteRequest(string $path): void
    {
        $segments = explode('/', $path);
        
        switch ($segments[0] ?? '') {
            case 'threads':
                $this->deleteThread($segments[1] ?? null);
                break;
            case 'messages':
                $this->deleteMessage($segments[1] ?? null);
                break;
            default:
                $this->sendJsonResponse(['error' => 'Endpoint not found'], 404);
        }
    }
    
    /**
     * Get threads
     */
    private function getThreads(?string $threadKey = null): void
    {
        if ($threadKey) {
            $thread = $this->db->getThreadByKey($threadKey);
            if ($thread) {
                $this->sendJsonResponse($thread);
            } else {
                $this->sendJsonResponse(['error' => 'Thread not found'], 404);
            }
        } else {
            // Get all threads (would need to implement getAllThreads method)
            $this->sendJsonResponse(['error' => 'Get all threads not implemented'], 501);
        }
    }
    
    /**
     * Get messages
     */
    private function getMessages(?string $threadId = null): void
    {
        if ($threadId) {
            $limit = (int)($_GET['limit'] ?? 50);
            $offset = (int)($_GET['offset'] ?? 0);
            $messages = $this->db->getMessagesByThread((int)$threadId, $limit, $offset);
            $this->sendJsonResponse($messages);
        } else {
            $this->sendJsonResponse(['error' => 'Thread ID required'], 400);
        }
    }
    
    /**
     * Get channels
     */
    private function getChannels(?string $channelKey = null): void
    {
        if ($channelKey) {
            $channel = $this->db->getChannelByKey($channelKey);
            if ($channel) {
                $this->sendJsonResponse($channel);
            } else {
                $this->sendJsonResponse(['error' => 'Channel not found'], 404);
            }
        } else {
            $this->sendJsonResponse(['error' => 'Get all channels not implemented'], 501);
        }
    }
    
    /**
     * Get actors
     */
    private function getActors(?string $actorId = null): void
    {
        if ($actorId) {
            $actor = $this->db->getActorById((int)$actorId);
            if ($actor) {
                $this->sendJsonResponse($actor);
            } else {
                $this->sendJsonResponse(['error' => 'Actor not found'], 404);
            }
        } else {
            $this->sendJsonResponse(['error' => 'Get all actors not implemented'], 501);
        }
    }
    
    /**
     * Get statistics
     */
    private function getStatistics(): void
    {
        $stats = $this->db->getDialogStatistics();
        $this->sendJsonResponse($stats);
    }
    
    /**
     * Create thread
     */
    private function createThread(array $input): void
    {
        $required = ['thread_key', 'channel_key', 'created_by_actor_id', 'thread_title'];
        $missing = array_diff($required, array_keys($input));
        
        if (!empty($missing)) {
            $this->sendJsonResponse(['error' => 'Missing required fields', 'fields' => $missing], 400);
            return;
        }
        
        $threadData = [
            'thread_key' => $input['thread_key'],
            'channel_key' => $input['channel_key'],
            'created_by_actor_id' => (int)$input['created_by_actor_id'],
            'thread_title' => $input['thread_title'],
            'thread_description' => $input['thread_description'] ?? null,
            'metadata_json' => json_encode($input['metadata'] ?? []),
            'status_flag' => 1,
            'created_ymdhis' => date('YmdHis'),
            'updated_ymdhis' => date('YmdHis'),
            'is_deleted' => 0
        ];
        
        try {
            $this->db->beginTransaction();
            $threadId = $this->db->createThread($threadData);
            $this->db->commit();
            
            $this->sendJsonResponse([
                'success' => true,
                'thread_id' => $threadId,
                'message' => 'Thread created successfully'
            ], 201);
        } catch (\Exception $e) {
            $this->db->rollback();
            $this->sendJsonResponse([
                'error' => 'Failed to create thread',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Create message
     */
    private function createMessage(array $input): void
    {
        $required = ['thread_id', 'message_key', 'from_actor_id', 'message_type'];
        $missing = array_diff($required, array_keys($input));
        
        if (!empty($missing)) {
            $this->sendJsonResponse(['error' => 'Missing required fields', 'fields' => $missing], 400);
            return;
        }
        
        // Create message body first
        $messageBodyId = uniqid('msg_body_', true);
        $contentHash = hash('sha256', $input['content'] ?? '');
        
        $messageBodyData = [
            'message_body_id' => $messageBodyId,
            'content_type' => $input['content_type'] ?? 'text',
            'content_text' => $input['content'] ?? null,
            'content_json' => $input['content_json'] ?? null,
            'metadata_json' => json_encode($input['metadata'] ?? []),
            'content_hash' => $contentHash,
            'created_ymdhis' => date('YmdHis'),
            'updated_ymdhis' => date('YmdHis'),
            'is_deleted' => 0
        ];
        
        try {
            $this->db->beginTransaction();
            
            $this->db->createMessageBody($messageBodyData);
            
            $messageData = [
                'thread_id' => (int)$input['thread_id'],
                'message_key' => $input['message_key'],
                'from_actor_id' => (int)$input['from_actor_id'],
                'to_actor_id' => $input['to_actor_id'] ?? null,
                'message_type' => $input['message_type'],
                'message_body_id' => $messageBodyId,
                'metadata_json' => json_encode($input['metadata'] ?? []),
                'status_flag' => 1,
                'created_ymdhis' => date('YmdHis'),
                'updated_ymdhis' => date('YmdHis'),
                'is_deleted' => 0
            ];
            
            $messageId = $this->db->createMessage($messageData);
            $this->db->commit();
            
            $this->sendJsonResponse([
                'success' => true,
                'message_id' => $messageId,
                'message' => 'Message created successfully'
            ], 201);
        } catch (\Exception $e) {
            $this->db->rollback();
            $this->sendJsonResponse([
                'error' => 'Failed to create message',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Generate AI response
     */
    private function generateResponse(array $input): void
    {
        $required = ['thread_id', 'prompt', 'actor_id'];
        $missing = array_diff($required, array_keys($input));
        
        if (!empty($missing)) {
            $this->sendJsonResponse(['error' => 'Missing required fields', 'fields' => $missing], 400);
            return;
        }
        
        try {
            // Get thread context
            $messages = $this->db->getMessagesByThread((int)$input['thread_id'], 10);
            $context = $this->buildContext($messages);
            
            // Generate response using LLM
            $response = $this->llm->generateResponse($input['prompt'], $context, [
                'actor_id' => (int)$input['actor_id'],
                'thread_id' => (int)$input['thread_id'],
                'max_tokens' => 1000
            ]);
            
            $this->sendJsonResponse([
                'success' => true,
                'response' => $response,
                'context_used' => count($messages)
            ]);
        } catch (\Exception $e) {
            $this->sendJsonResponse([
                'error' => 'Failed to generate response',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update thread
     */
    private function updateThread(?string $threadId, array $input): void
    {
        if (!$threadId) {
            $this->sendJsonResponse(['error' => 'Thread ID required'], 400);
            return;
        }
        
        try {
            $success = $this->db->updateThreadStatus((int)$threadId, $input['status'] ?? 'active');
            if ($success) {
                $this->sendJsonResponse([
                    'success' => true,
                    'message' => 'Thread updated successfully'
                ]);
            } else {
                $this->sendJsonResponse(['error' => 'Failed to update thread'], 500);
            }
        } catch (\Exception $e) {
            $this->sendJsonResponse([
                'error' => 'Failed to update thread',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete thread
     */
    private function deleteThread(?string $threadId): void
    {
        if (!$threadId) {
            $this->sendJsonResponse(['error' => 'Thread ID required'], 400);
            return;
        }
        
        try {
            $success = $this->db->softDeleteThread((int)$threadId);
            if ($success) {
                $this->sendJsonResponse([
                    'success' => true,
                    'message' => 'Thread deleted successfully'
                ]);
            } else {
                $this->sendJsonResponse(['error' => 'Failed to delete thread'], 500);
            }
        } catch (\Exception $e) {
            $this->sendJsonResponse([
                'error' => 'Failed to delete thread',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Build context for LLM
     */
    private function buildContext(array $messages): array
    {
        $context = [];
        foreach ($messages as $message) {
            $context[] = [
                'role' => $message['from_actor_name'] ?? 'Unknown',
                'content' => $message['content_text'] ?? $message['content_json'] ?? '',
                'timestamp' => $message['created_ymdhis'],
                'type' => $message['message_type']
            ];
        }
        return $context;
    }
    
    /**
     * Get JSON input
     */
    private function getJsonInput(): array
    {
        $input = file_get_contents('php://input');
        return json_decode($input, true) ?? [];
    }
    
    /**
     * Send JSON response
     */
    private function sendJsonResponse(array $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json');
        header('HTTP/1.1 ' . $statusCode);
        echo json_encode($data);
        exit;
    }
    
    /**
     * Send CORS headers
     */
    private function sendCorsHeaders(): void
    {
        header('Access-Control-Allow-Origin: ' . implode(', ', $this->config['cors_origins']));
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Max-Age: 86400');
    }
}
