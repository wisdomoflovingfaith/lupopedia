<?php
/**
 * ChatRoutingService – Intelligent chat routing to available operators
 * 
 * Responsible for:
 * - Finding the best available operator for an incoming chat
 * - Applying routing rules (load balance, skill-based, first-available)
 * - Assigning chat to operator and sending invitation
 * - Tracking chat assignments
 */

class ChatRoutingService
{
    private $db;
    private $table_prefix;
    private $channel_id;
    private $availability_service;
    
    const STRATEGY_LOAD_BALANCE = 'load_balance';
    const STRATEGY_FIRST_AVAILABLE = 'first_available';
    const STRATEGY_SKILL_BASED = 'skill_based';
    
    const MAX_CONCURRENT_CHATS_DEFAULT = 10;
    
    /**
     * Constructor
     * 
     * @param PDO_DB $db Database connection
     * @param int $channel_id Channel to route chats within
     * @param string $table_prefix Table prefix (default: 'lupo_')
     */
    public function __construct($db, $channel_id, $table_prefix = 'lupo_')
    {
        $this->db = $db;
        $this->table_prefix = $table_prefix;
        $this->channel_id = $channel_id;
        $this->availability_service = new ActorAvailabilityService($db, $table_prefix);
    }
    
    /**
     * Find the best available operator for a new incoming chat
     * 
     * Applies routing strategy from channel config:
     * - load_balance: Route to operator with fewest active chats
     * - first_available: Route to first online operator
     * - skill_based: Route based on skill tags (requires skill metadata)
     * 
     * @param array $visitor_context Optional context about visitor (for skill matching)
     *        ['visitor_name' => '...', 'skill_tags' => [...], ...]
     * @return int|false Operator actor_id or false if no available operator
     */
    public function findAvailableOperator($visitor_context = [])
    {
        // Get routing strategy from channel config
        $strategy = $this->_getRoutingStrategy();
        
        // Get available operators
        $available = $this->availability_service->getAvailableOperators($this->channel_id);
        
        if (empty($available)) {
            return false;
        }
        
        // Apply strategy
        switch ($strategy) {
            case self::STRATEGY_SKILL_BASED:
                return $this->_routeBySkill($available, $visitor_context);
                break;
            case self::STRATEGY_LOAD_BALANCE:
                return $this->_routeByLoadBalance($available);
                break;
            case self::STRATEGY_FIRST_AVAILABLE:
            default:
                return $this->_routeFirstAvailable($available);
                break;
        }
    }
    
    /**
     * Assign a chat to an operator (create invitation)
     * 
     * Creates a chat collection, sends operator invitation, stores routing decision.
     * 
     * @param int $visitor_actor_id Guest actor for the visitor
     * @param int $operator_actor_id Assigned operator
     * @param string $chat_context Optional context (entry_page, referrer, etc.)
     * @return int|false Chat collection_id or false on error
     */
    public function assignChatToOperator($visitor_actor_id, $operator_actor_id, $chat_context = null)
    {
        $now = gmdate('YmdHis');
        
        // Create collection (chat container)
        $chat_collection_id = $this->_createChatCollection(
            $visitor_actor_id,
            $operator_actor_id,
            $chat_context
        );
        
        if (!$chat_collection_id) {
            return false;
        }
        
        // Log routing decision
        $this->_logRoutingDecision(
            $visitor_actor_id,
            $operator_actor_id,
            $chat_collection_id
        );
        
        // Update operator status to 'busy' if needed
        $operator_status = $this->availability_service->getStatusForOperator(
            $operator_actor_id,
            $this->channel_id
        );
        
        if ($operator_status === 'online') {
            // Check if operator is at max capacity
            $active_chats = $this->_countActiveChatsForOperator($operator_actor_id);
            $max_chats = $this->_getMaxConcurrentChats();
            
            if ($active_chats >= $max_chats) {
                $this->availability_service->setStatus(
                    $operator_actor_id,
                    $this->channel_id,
                    'busy'
                );
            }
        }
        
        return $chat_collection_id;
    }
    
    /**
     * Decline a chat assignment (operator rejected invitation)
     * 
     * Marks collection as declined, re-routes to next available operator.
     * 
     * @param int $chat_collection_id Chat collection to decline
     * @param int $reason_code Optional reason code
     * @return int|false New operator actor_id if rerouted, false if no alternate available
     */
    public function declineChat($chat_collection_id, $reason_code = 1)
    {
        // Mark as declined
        $this->db->update(
            $this->table_prefix . 'collections',
            [
                'metadata' => json_encode(['status' => 'declined', 'reason_code' => $reason_code]),
                'updated_ymdhis' => gmdate('YmdHis'),
            ],
            ['collection_id' => $chat_collection_id]
        );
        
        // Get visitor from original assignment
        $chat = $this->db->fetchRow(
            "SELECT * FROM {$this->table_prefix}collections 
             WHERE collection_id = :id",
            ['id' => $chat_collection_id]
        );
        
        if (!$chat) {
            return false;
        }
        
        // Get visitor actor_id from collection members
        $visitor_result = $this->db->fetchRow(
            "SELECT actor_id FROM {$this->table_prefix}collection_tabs 
             WHERE collection_id = :collection_id 
             ORDER BY created_ymdhis ASC LIMIT 1",
            ['collection_id' => $chat_collection_id]
        );
        
        if (!$visitor_result) {
            return false;
        }
        
        // Try to find next available operator (fallback routing)
        $next_operator = $this->findAvailableOperator();
        
        if ($next_operator) {
            // Create new chat with next operator
            $new_chat_id = $this->assignChatToOperator(
                $visitor_result['actor_id'],
                $next_operator,
                ['rerouted_from_collection' => $chat_collection_id]
            );
            
            return $new_chat_id ? $next_operator : false;
        }
        
        return false;
    }
    
    // ===== PRIVATE HELPERS =====
    
    /**
     * Get routing strategy from channel config
     * 
     * @return string Strategy: load_balance, first_available, skill_based
     */
    private function _getRoutingStrategy()
    {
        $config = $this->db->fetchRow(
            "SELECT config_value FROM {$this->table_prefix}system_config 
             WHERE config_key = :key 
             AND is_deleted = 0",
            ['key' => 'chat.routing.' . $this->channel_id . '.strategy']
        );
        
        return $config ? $config['config_value'] : self::STRATEGY_LOAD_BALANCE;
    }
    
    /**
     * Route by load balance (fewest active chats)
     * 
     * @param array $available Available operators (from availability_service)
     * @return int|false Best operator actor_id
     */
    private function _routeByLoadBalance($available)
    {
        $best_operator = null;
        $min_chats = PHP_INT_MAX;
        
        foreach ($available as $op) {
            $active_chats = $this->_countActiveChatsForOperator($op['actor_id']);
            
            if ($active_chats < $min_chats) {
                $min_chats = $active_chats;
                $best_operator = $op['actor_id'];
            }
        }
        
        return $best_operator ?: false;
    }
    
    /**
     * Route to first available operator
     * 
     * @param array $available Available operators
     * @return int First operator actor_id
     */
    private function _routeFirstAvailable($available)
    {
        return !empty($available) ? $available[0]['actor_id'] : false;
    }
    
    /**
     * Route by skill (match visitor tags to operator skills)
     * 
     * @param array $available Available operators
     * @param array $visitor_context Visitor context with skill_tags
     * @return int|false Best matching operator
     */
    private function _routeBySkill($available, $visitor_context)
    {
        // TODO: Implement skill matching in Phase 2
        // For now, fall back to load balance
        return $this->_routeByLoadBalance($available);
    }
    
    /**
     * Create chat collection object
     * 
     * @param int $visitor_actor_id
     * @param int $operator_actor_id
     * @param array|string $chat_context Context data
     * @return int|false Collection_id
     */
    private function _createChatCollection($visitor_actor_id, $operator_actor_id, $chat_context)
    {
        $now = gmdate('YmdHis');
        $collection_id = $this->_allocateCollectionId();
        
        $metadata = [
            'collection_type' => 'live_help_chat',
            'status' => 'waiting_for_acceptance',
            'initiated_ymdhis' => $now,
            'visitor_actor_id' => $visitor_actor_id,
            'operator_actor_id' => $operator_actor_id,
            'context' => $chat_context,
        ];
        
        $result = $this->db->insert(
            $this->table_prefix . 'collections',
            [
                'collection_id' => $collection_id,
                'channel_id' => $this->channel_id,
                'collection_name' => 'Live Chat - ' . $visitor_actor_id,
                'collection_type' => 'live_help_chat',
                'metadata' => json_encode($metadata),
                'created_ymdhis' => $now,
                'updated_ymdhis' => $now,
                'is_deleted' => 0,
            ]
        );
        
        return $result ? $collection_id : false;
    }
    
    /**
     * Count active chats for an operator
     * 
     * @param int $operator_actor_id
     * @return int Count of active chats
     */
    private function _countActiveChatsForOperator($operator_actor_id)
    {
        $result = $this->db->fetchRow(
            "SELECT COUNT(*) as cnt FROM {$this->table_prefix}collections c 
             WHERE c.collection_type = 'live_help_chat' 
             AND c.channel_id = :channel_id
             AND JSON_EXTRACT(c.metadata, '$.operator_actor_id') = :op_id
             AND JSON_EXTRACT(c.metadata, '$.status') IN ('waiting_for_acceptance', 'active')
             AND c.is_deleted = 0",
            ['channel_id' => $this->channel_id, 'op_id' => $operator_actor_id]
        );
        
        return $result ? (int) $result['cnt'] : 0;
    }
    
    /**
     * Get max concurrent chats per operator
     * 
     * @return int Max concurrent chats (default: 10)
     */
    private function _getMaxConcurrentChats()
    {
        $config = $this->db->fetchRow(
            "SELECT config_value FROM {$this->table_prefix}system_config 
             WHERE config_key = :key 
             AND is_deleted = 0",
            ['key' => 'chat.settings.channel_' . $this->channel_id . '.max_concurrent_chats']
        );
        
        return $config ? (int) $config['config_value'] : self::MAX_CONCURRENT_CHATS_DEFAULT;
    }
    
    /**
     * Log routing decision for audit trail
     * 
     * @param int $visitor_actor_id
     * @param int $operator_actor_id
     * @param int $chat_collection_id
     */
    private function _logRoutingDecision($visitor_actor_id, $operator_actor_id, $chat_collection_id)
    {
        $routing_log_id = $this->_allocateRoutingLogId();
        
        $this->db->insert(
            $this->table_prefix . 'routing_decisions',
            [
                'routing_decision_id' => $routing_log_id,
                'channel_id' => $this->channel_id,
                'visitor_actor_id' => $visitor_actor_id,
                'assigned_operator_actor_id' => $operator_actor_id,
                'chat_collection_id' => $chat_collection_id,
                'routing_strategy' => $this->_getRoutingStrategy(),
                'created_ymdhis' => gmdate('YmdHis'),
                'is_deleted' => 0,
            ]
        );
    }
    
    /**
     * Allocate chat collection ID
     * 
     * @return int
     */
    private function _allocateCollectionId()
    {
        if (class_exists('DeterministicIdService')) {
            $service = new DeterministicIdService($this->db, $this->table_prefix);
            return $service->allocateId('collections');
        }
        
        $last = $this->db->fetchRow(
            "SELECT MAX(collection_id) as max_id FROM {$this->table_prefix}collections"
        );
        
        return ($last && $last['max_id']) ? (int) $last['max_id'] + 1 : 1001;
    }
    
    /**
     * Allocate routing log ID
     * 
     * @return int
     */
    private function _allocateRoutingLogId()
    {
        if (class_exists('DeterministicIdService')) {
            $service = new DeterministicIdService($this->db, $this->table_prefix);
            return $service->allocateId('routing_decisions');
        }
        
        $last = $this->db->fetchRow(
            "SELECT MAX(routing_decision_id) as max_id FROM {$this->table_prefix}routing_decisions"
        );
        
        return ($last && $last['max_id']) ? (int) $last['max_id'] + 1 : 1001;
    }
}
