---
# FLIP Header (alias: Wolfie Header, CROP Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: "app/Services/IDEAgentRegistryService.php"
file.last_modified_system_version: "4.0.31"
file.last_modified_utc: "20260223144700"
channel_id: 42
mood_rgb: "4B0082"
---

<?php

/**
 * IDE Agent Registry Service
 * 
 * Manages registration, detection, and communication with IDE agents
 * Maintains active agent list and handles agent coordination
 * Integrates with OAuth actor pairing and Channel 42 coordination
 * 
 * @author Lupopedia Development Team
 * @version 4.0.31
 */

class IDEAgentRegistryService
{

    private $db;
    private $active_agents = [];
    private $registry_cache = [];

    // Supported IDE agents (aligned with AGENT_REGISTRY_DOCTRINE.md)
    private $supported_agents = [
        'kiro' => [
            'name' => 'KIRO IDE',
            'actor_id' => 1001,
            'capabilities' => ['code_edit', 'file_analysis', 'semantic_understanding', 'oauth_management'],
            'extension_support' => true
        ],
        'windsurf' => [
            'name' => 'Windsurf IDE',
            'actor_id' => 1002,
            'capabilities' => ['code_edit', 'file_analysis', 'semantic_understanding', 'multi_agent_coordination'],
            'extension_support' => true
        ],
        'antigravity' => [
            'name' => 'Antigravity IDE',
            'actor_id' => 1003,
            'capabilities' => ['code_edit', 'file_analysis', 'extension_integration', 'advanced_semantics'],
            'extension_support' => true
        ],
        'warp' => [
            'name' => 'Warp IDE',
            'actor_id' => 1004,
            'capabilities' => ['terminal_operations', 'command_execution', 'file_analysis'],
            'extension_support' => true
        ],
        'cursor' => [
            'name' => 'Cursor IDE',
            'actor_id' => 1005,
            'capabilities' => ['code_edit', 'file_analysis', 'semantic_understanding'],
            'extension_support' => true
        ],
        'zed' => [
            'name' => 'Zed IDE',
            'actor_id' => 1006,
            'capabilities' => ['code_edit', 'file_analysis', 'lightweight_mode'],
            'extension_support' => true
        ],
        'intelij' => [
            'name' => 'IntelliJ IDEA',
            'actor_id' => 1007,
            'capabilities' => ['code_edit', 'file_analysis', 'project_management'],
            'extension_support' => true
        ],
        'webstorm' => [
            'name' => 'WebStorm',
            'actor_id' => 1008,
            'capabilities' => ['code_edit', 'file_analysis', 'web_development'],
            'extension_support' => true
        ],
        'theiaide' => [
            'name' => 'Theia IDE',
            'actor_id' => 1009,
            'capabilities' => ['code_edit', 'file_analysis', 'cloud_development'],
            'extension_support' => true
        ],
        'cs_code' => [
            'name' => 'CS Code',
            'actor_id' => 1010,
            'capabilities' => ['code_edit', 'file_analysis', 'custom_integration'],
            'extension_support' => true
        ],
        'captain_wolfie' => [
            'name' => 'CAPTAIN WOLFIE',
            'actor_id' => 1000,
            'capabilities' => ['coordination', 'semantic_understanding', 'ai_partnership', 'task_distribution', 'multi_agent_management'],
            'extension_support' => true,
            'type' => 'ai_partner'
        ],
        'human_user' => [
            'name' => 'Human User',
            'actor_id' => 10000,
            'capabilities' => ['oauth_authentication', 'task_approval', 'final_decision', 'creative_direction'],
            'extension_support' => true,
            'type' => 'human'
        ]
    ];

    public function __construct()
    {
        $this->db = DatabaseFactory::getConnection();
        $this->loadActiveAgents();
    }

    /**
     * Load all active agents from registry
     */
    public function loadActiveAgents()
    {
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

        $agents = $this->db->fetchAll(
            "SELECT * FROM {$table_prefix}ide_agent_registry 
             WHERE status = 'active' 
             ORDER BY last_seen_ymdhis DESC"
        );

        foreach ($agents as $agent) {
            $this->active_agents[$agent['agent_type']] = $agent;
        }

        return $this->active_agents;
    }

    /**
     * Register or update an IDE agent
     */
    public function registerAgent($agent_type, $agent_info = [])
    {
        if (!isset($this->supported_agents[$agent_type])) {
            throw new Exception("Unsupported agent type: {$agent_type}");
        }

        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $now = gmdate('YmdHis');

        $agent_data = array_merge([
            'agent_type' => $agent_type,
            'agent_name' => $this->supported_agents[$agent_type]['name'],
            'actor_id' => $this->supported_agents[$agent_type]['actor_id'],
            'capabilities' => json_encode($this->supported_agents[$agent_type]['capabilities']),
            'extension_support' => $this->supported_agents[$agent_type]['extension_support'] ? 1 : 0,
            'status' => 'active',
            'last_seen_ymdhis' => $now,
            'created_ymdhis' => $now,
            'modified_ymdhis' => $now
        ], $agent_info);

        // Check if agent already exists
        $existing = $this->db->fetchRow(
            "SELECT id FROM {$table_prefix}ide_agent_registry 
             WHERE agent_type = :agent_type",
            ['agent_type' => $agent_type]
        );

        if ($existing) {
            $this->db->update(
                "{$table_prefix}ide_agent_registry",
                $agent_data,
                ['agent_type' => $agent_type]
            );
        } else {
            $this->db->insert("{$table_prefix}ide_agent_registry", $agent_data);
        }

        // Update active agents cache
        $this->active_agents[$agent_type] = $agent_data;

        return $agent_data;
    }

    /**
     * Get all active agents
     */
    public function getActiveAgents()
    {
        return $this->active_agents;
    }

    /**
     * Get agents with extension support
     */
    public function getExtensionSupportedAgents()
    {
        $extension_agents = [];

        foreach ($this->active_agents as $agent_type => $agent) {
            if ($agent['extension_support']) {
                $extension_agents[$agent_type] = $agent;
            }
        }

        return $extension_agents;
    }

    /**
     * Get agents by capability
     */
    public function getAgentsByCapability($capability)
    {
        $capable_agents = [];

        foreach ($this->active_agents as $agent_type => $agent) {
            $capabilities = json_decode($agent['capabilities'], true);
            if (in_array($capability, $capabilities)) {
                $capable_agents[$agent_type] = $agent;
            }
        }

        return $capable_agents;
    }

    /**
     * Validate actor ID mapping
     */
    public function validateActorMapping()
    {
        $validation_results = [];

        foreach ($this->active_agents as $agent_type => $agent) {
            $expected_actor_id = $this->supported_agents[$agent_type]['actor_id'];
            $actual_actor_id = $agent['actor_id'];

            $validation_results[$agent_type] = [
                'expected_actor_id' => $expected_actor_id,
                'actual_actor_id' => $actual_actor_id,
                'valid' => ($expected_actor_id == $actual_actor_id)
            ];
        }

        return $validation_results;
    }

    /**
     * Check agent communication status
     */
    public function checkAgentCommunication()
    {
        $communication_status = [];

        foreach ($this->active_agents as $agent_type => $agent) {
            $last_seen = $agent['last_seen_ymdhis'];
            $current_time = gmdate('YmdHis');

            // Calculate time difference in minutes
            $time_diff = $this->calculateTimeDifference($last_seen, $current_time);

            $communication_status[$agent_type] = [
                'last_seen' => $last_seen,
                'minutes_ago' => $time_diff,
                'status' => ($time_diff < 5) ? 'online' : 'offline',
                'extension_support' => $agent['extension_support']
            ];
        }

        return $communication_status;
    }

    /**
     * Pair human user with AI partner
     */
    public function pairHumanWithAI($human_actor_id, $ai_actor_id = 1000)
    {
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

        // Validate actors
        $human_agent = $this->db->fetchRow(
            "SELECT * FROM {$table_prefix}ide_agent_registry 
             WHERE actor_id = :actor_id AND agent_type = 'human_user'",
            ['actor_id' => $human_actor_id]
        );

        $ai_agent = $this->db->fetchRow(
            "SELECT * FROM {$table_prefix}ide_agent_registry 
             WHERE actor_id = :actor_id AND agent_type = 'captain_wolfie'",
            ['actor_id' => $ai_actor_id]
        );

        if (!$human_agent || !$ai_agent) {
            throw new Exception("Invalid actor pairing: human or AI agent not found");
        }

        // Create or update pairing
        $pairing_data = [
            'human_actor_id' => $human_actor_id,
            'ai_actor_id' => $ai_actor_id,
            'pairing_status' => 'active',
            'channel_id' => 42, // Development channel
            'created_ymdhis' => gmdate('YmdHis'),
            'modified_ymdhis' => gmdate('YmdHis')
        ];

        $existing = $this->db->fetchRow(
            "SELECT id FROM {$table_prefix}agent_pairs 
             WHERE human_actor_id = :human_id AND ai_actor_id = :ai_id",
            ['human_id' => $human_actor_id, 'ai_id' => $ai_actor_id]
        );

        if ($existing) {
            $this->db->update(
                "{$table_prefix}agent_pairs",
                $pairing_data,
                ['id' => $existing['id']]
            );
        } else {
            $this->db->insert("{$table_prefix}agent_pairs", $pairing_data);
        }

        return $pairing_data;
    }

    /**
     * Broadcast message to all active agents
     */
    public function broadcastToAgents($message, $channel_id = 42)
    {
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

        foreach ($this->active_agents as $agent_type => $agent) {
            $broadcast_data = [
                'agent_type' => $agent_type,
                'actor_id' => $agent['actor_id'],
                'channel_id' => $channel_id,
                'message_type' => 'broadcast',
                'message_content' => $message,
                'created_ymdhis' => gmdate('YmdHis'),
                'status' => 'sent'
            ];

            $this->db->insert("{$table_prefix}agent_broadcasts", $broadcast_data);
        }

        return true;
    }

    /**
     * Get agent coordination status
     */
    public function getCoordinationStatus()
    {
        $coordination_status = [
            'total_agents' => count($this->active_agents),
            'extension_supported' => count($this->getExtensionSupportedAgents()),
            'online_agents' => 0,
            'actor_mapping_valid' => true,
            'pairings_active' => 0
        ];

        $communication_status = $this->checkAgentCommunication();
        $validation_results = $this->validateActorMapping();

        foreach ($communication_status as $agent_type => $status) {
            if ($status['status'] === 'online') {
                $coordination_status['online_agents']++;
            }
        }

        foreach ($validation_results as $agent_type => $validation) {
            if (!$validation['valid']) {
                $coordination_status['actor_mapping_valid'] = false;
                break;
            }
        }

        // Count active pairings
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $pairings = $this->db->fetchRow(
            "SELECT COUNT(*) as count FROM {$table_prefix}agent_pairs 
             WHERE pairing_status = 'active'"
        );
        $coordination_status['pairings_active'] = $pairings['count'];

        return $coordination_status;
    }

    /**
     * Calculate time difference between two YMDHIS timestamps
     */
    private function calculateTimeDifference($start_time, $end_time)
    {
        $start = new DateTime($start_time, new DateTimeZone('UTC'));
        $end = new DateTime($end_time, new DateTimeZone('UTC'));
        $interval = $start->diff($end);

        return ($interval->h * 60) + $interval->i;
    }

    /**
     * Update agent last seen time
     */
    public function updateAgentLastSeen($agent_type)
    {
        $table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

        $this->db->update(
            "{$table_prefix}ide_agent_registry",
            ['last_seen_ymdhis' => gmdate('YmdHis')],
            ['agent_type' => $agent_type]
        );

        // Update cache
        if (isset($this->active_agents[$agent_type])) {
            $this->active_agents[$agent_type]['last_seen_ymdhis'] = gmdate('YmdHis');
        }
    }

    /**
     * Get agent statistics
     */
    public function getAgentStatistics()
    {
        $stats = [
            'total_supported' => count($this->supported_agents),
            'active_agents' => count($this->active_agents),
            'extension_support_ratio' => 0,
            'capability_distribution' => [],
            'agent_types' => []
        ];

        $extension_count = 0;
        foreach ($this->active_agents as $agent) {
            if ($agent['extension_support']) {
                $extension_count++;
            }

            $capabilities = json_decode($agent['capabilities'], true);
            foreach ($capabilities as $capability) {
                if (!isset($stats['capability_distribution'][$capability])) {
                    $stats['capability_distribution'][$capability] = 0;
                }
                $stats['capability_distribution'][$capability]++;
            }

            $stats['agent_types'][] = [
                'type' => $agent['agent_type'],
                'name' => $agent['agent_name'],
                'actor_id' => $agent['actor_id'],
                'status' => $agent['status']
            ];
        }

        if ($stats['active_agents'] > 0) {
            $stats['extension_support_ratio'] = $extension_count / $stats['active_agents'];
        }

        return $stats;
    }
}

?>
