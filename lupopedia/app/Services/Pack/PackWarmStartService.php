<?php
/**
 * Pack Warm Start Service
 *
 * Initializes Pack Architecture with known agents and neutral emotional metadata.
 *
 * @package Lupopedia
 * @version 4.0.107
 * @author Captain Wolfie
 */

namespace App\Services\Pack;

use Lupopedia\Pack\PackRegistry;
use Lupopedia\Pack\PackContext;

/**
 * PackWarmStartService
 *
 * Performs warm-start initialization of Pack Architecture.
 */
class PackWarmStartService
{
    /** @var PackRegistry Pack registry instance */
    private $registry;

    /** @var PackContext Pack context instance */
    private $context;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->registry = new PackRegistry();
        $this->context = new PackContext();
    }

    /**
     * Perform warm-start initialization
     *
     * @return array Initialization result with status and details
     */
    public function warmStart(): array
    {
        try {
            $registeredAgents = [];
            $errors = [];

            // Register Terminal AI agents
            $terminalAgents = [
                ['id' => 'TerminalAI_001', 'class' => 'App\TerminalAI\Agents\TerminalAI_001', 'capabilities' => ['command_echo', 'basic_handling']],
                ['id' => 'TerminalAI_005', 'class' => 'App\TerminalAI\Agents\TerminalAI_005', 'capabilities' => ['utc_timekeeper', 'temporal_authority']],
            ];

            foreach ($terminalAgents as $agent) {
                if ($this->registry->registerAgent($agent['id'], $agent['class'], $agent['capabilities'])) {
                    $registeredAgents[] = $agent['id'];
                } else {
                    $errors[] = "Failed to register agent: {$agent['id']}";
                }
            }

            // Register CIP (Critique Integration Protocol) agent
            if ($this->registry->registerAgent('CIP', 'Lupopedia\CIP\CIPEngine', ['critique_processing', 'analytics'])) {
                $registeredAgents[] = 'CIP';
            } else {
                $errors[] = 'Failed to register agent: CIP';
            }

            // Register AAL (Agent Awareness Layer) agent
            if ($this->registry->registerAgent('AAL', 'Lupopedia\AAL\AALEngine', ['awareness_tracking', 'metadata_management'])) {
                $registeredAgents[] = 'AAL';
            } else {
                $errors[] = 'Failed to register agent: AAL';
            }

            // Register KIP (Kritik Integration Protocol) agent
            if ($this->registry->registerAgent('KIP', 'Lupopedia\KIP\KIPEngine', ['critique_evaluation', 'integration'])) {
                $registeredAgents[] = 'KIP';
            } else {
                $errors[] = 'Failed to register agent: KIP';
            }

            // Initialize PackContext with neutral emotional metadata
            foreach ($registeredAgents as $agentId) {
                $this->context->setEmotion($agentId, [
                    'mood_RGB' => '808080', // Neutral gray
                    'state' => 'idle',
                    'intensity' => 0.5,
                ]);
            }

            // Set active agent to null (no agent active initially)
            $this->context->setActiveAgent(null);

            // Log initialization event
            error_log("PackWarmStartService: Pack initialized with " . count($registeredAgents) . " agents");

            return [
                'status' => 'ok',
                'message' => 'Pack warm-start completed successfully',
                'registered_agents' => $registeredAgents,
                'total_agents' => count($registeredAgents),
                'errors' => $errors,
                'timestamp' => gmdate('YmdHis'),
            ];
        } catch (\Exception $e) {
            error_log("PackWarmStartService::warmStart() error: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Warm-start failed: ' . $e->getMessage(),
                'timestamp' => gmdate('YmdHis'),
            ];
        }
    }
}
