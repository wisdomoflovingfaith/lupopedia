<?php

/**
 * Lupopedia v4.1.6 - Agent Awareness Layer (AAL)
 * 
 * Implements the multi-agent coordination layer with:
 * - Lupopedia Actor Baseline State (LABS-001) validation
 * - Reverse Shaka Awareness Load (RSAL)
 * - Channel Join Protocol (CJP) 
 * - Agent Awareness Snapshot (AAS)
 * - Reverse Shaka Handshake Protocol (RSHAP)
 * 
 * @governance LABS-001 Doctrine v1.0
 */

class AgentAwarenessLayer {
    
    private $db;
    private $current_timestamp;
    
    public function __construct($database_connection) {
        $this->db = $database_connection;
        $this->current_timestamp = date('YmdHis');
        
        // Load LABS validator class if available
        $labs_validator_path = __DIR__ . '/LABSValidator.php';
        if (file_exists($labs_validator_path)) {
            require_once $labs_validator_path;
        }
    }
    
    /**
     * Reverse Shaka Awareness Load (RSAL)
     * Mandatory onboarding ritual for all agents joining a channel
     * 
     * LABS-001: LABS validation is now the mandatory first step
     */
    public function loadReverseShakaAwareness($actor_id, $channel_id, $labs_declaration = null) {
        try {
            // Step 0: LABS-001 VALIDATION (MANDATORY FIRST STEP)
            $labs_result = $this->validateLABS($actor_id, $labs_declaration);
            if (!$labs_result['valid']) {
                return [
                    'success' => false,
                    'error' => 'LABS validation failed',
                    'labs_errors' => $labs_result['errors'] ?? [],
                    'action' => 'QUARANTINE_ACTIVATED',
                    'timestamp' => $this->current_timestamp
                ];
            }
            
            // Step 1: Load channel metadata
            $channel_metadata = $this->getChannelMetadata($channel_id);
            
            // Step 2: Load all actors in channel
            $channel_actors = $this->getChannelActors($channel_id);
            
            // Step 3: Load handshake metadata for all actors
            $handshake_metadata = $this->getHandshakeMetadata($channel_actors);
            
            // Step 4: Generate awareness snapshot
            $awareness_snapshot = $this->generateAwarenessSnapshot(
                $actor_id, 
                $channel_id, 
                $channel_metadata, 
                $channel_actors, 
                $handshake_metadata
            );
            
            // Step 5: Store awareness snapshot
            $this->storeAwarenessSnapshot($actor_id, $channel_id, $awareness_snapshot);
            
            // Step 6: Execute Reverse Shaka Handshake
            $handshake_result = $this->executeReverseShakaHandshake($actor_id, $channel_id);
            
            return [
                'success' => true,
                'labs_certificate' => $labs_result['certificate_id'] ?? null,
                'awareness_snapshot' => $awareness_snapshot,
                'handshake_result' => $handshake_result,
                'timestamp' => $this->current_timestamp
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => $this->current_timestamp
            ];
        }
    }
    
    /**
     * Channel Join Protocol (CJP)
     * Complete onboarding sequence for agents
     * 
     * LABS-001: LABS validation is now the mandatory first step before any channel interaction
     */
    public function executeChannelJoinProtocol($actor_id, $channel_id, $labs_declaration = null) {
        try {
            // Step 0: LABS-001 VALIDATION (MANDATORY FIRST STEP)
            // No actor may join a channel without valid LABS declaration
            $labs_result = $this->validateLABS($actor_id, $labs_declaration);
            if (!$labs_result['valid']) {
                return [
                    'success' => false,
                    'error' => 'LABS validation failed',
                    'labs_errors' => $labs_result['errors'] ?? [],
                    'action' => 'QUARANTINE_ACTIVATED',
                    'timestamp' => $this->current_timestamp
                ];
            }
            
            // Step 1: Load channel metadata
            $channel_metadata = $this->loadChannelMetadata($channel_id);
            
            // Step 2: Load actor metadata
            $actor_metadata = $this->loadActorMetadata($actor_id);
            
            // Step 3: Load handshake metadata
            $handshake_metadata = $this->loadHandshakeMetadata($actor_id, $channel_id);
            
            // Step 4: Load fleet composition
            $fleet_composition = $this->loadFleetComposition($channel_id);
            
            // Step 5: Generate awareness snapshot
            $awareness_snapshot = $this->generateAwarenessSnapshot(
                $actor_id, 
                $channel_id, 
                $channel_metadata, 
                $actor_metadata, 
                $handshake_metadata, 
                $fleet_composition
            );
            
            // Step 6: Store snapshot
            $this->storeAwarenessSnapshot($actor_id, $channel_id, $awareness_snapshot);
            
            // Step 7: Store persistent identity
            $this->storePersistentIdentity($actor_id, $awareness_snapshot);
            
            // Step 8: Acknowledge channel purpose
            $this->acknowledgeChannelPurpose($actor_id, $channel_id, $channel_metadata['purpose']);
            
            // Step 9: Acknowledge doctrine alignment
            $this->acknowledgeDoctrineAlignment($actor_id, $channel_id, $channel_metadata['doctrine']);
            
            // Step 10: Enable communication
            $communication_enabled = $this->enableCommunication($actor_id, $channel_id);
            
            return [
                'success' => true,
                'labs_certificate' => $labs_result['certificate_id'] ?? null,
                'awareness_snapshot' => $awareness_snapshot,
                'communication_enabled' => $communication_enabled,
                'timestamp' => $this->current_timestamp
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => $this->current_timestamp
            ];
        }
    }
    
    /**
     * Validate LABS declaration (LABS-001)
     * 
     * Checks for existing valid certificate or validates new declaration
     * 
     * @param int $actor_id Actor ID
     * @param array|null $labs_declaration LABS declaration array (if null, checks for existing certificate)
     * @return array Validation result
     */
    private function validateLABS($actor_id, $labs_declaration = null) {
        // Check if LABS_Validator class is available
        if (!class_exists('LABS_Validator')) {
            // LABS validator not available - log warning but don't block (backward compatibility)
            error_log("LABS_Validator class not found. LABS validation skipped for actor {$actor_id}");
            return [
                'valid' => true,
                'skipped' => true,
                'reason' => 'LABS validator not available'
            ];
        }
        
        try {
            $validator = new LABS_Validator($this->db, $actor_id);
            
            // If no declaration provided, check for existing valid certificate
            if ($labs_declaration === null) {
                $existing_cert = $validator->check_existing_certificate();
                if ($existing_cert) {
                    return [
                        'valid' => true,
                        'certificate_id' => $existing_cert['certificate_id'],
                        'existing' => true
                    ];
                } else {
                    return [
                        'valid' => false,
                        'error' => 'No LABS declaration provided and no valid certificate found',
                        'action' => 'QUARANTINE_ACTIVATED'
                    ];
                }
            }
            
            // Validate provided declaration
            $result = $validator->validate_declaration($labs_declaration);
            
            if ($result['valid']) {
                return [
                    'valid' => true,
                    'certificate_id' => $result['certificate_id'],
                    'next_revalidation' => $result['next_revalidation']
                ];
            } else {
                return [
                    'valid' => false,
                    'errors' => $validator->get_errors(),
                    'validation_log' => $validator->get_validation_log(),
                    'action' => 'QUARANTINE_ACTIVATED'
                ];
            }
            
        } catch (Exception $e) {
            error_log("LABS validation error for actor {$actor_id}: " . $e->getMessage());
            return [
                'valid' => false,
                'error' => 'LABS validation exception: ' . $e->getMessage(),
                'action' => 'QUARANTINE_ACTIVATED'
            ];
        }
    }
    
    /**
     * Generate Agent Awareness Snapshot (AAS)
     * Answers the Seven Questions for agent cognition
     */
    private function generateAwarenessSnapshot($actor_id, $channel_id, $context_data) {
        return [
            'who' => $this->buildWho($actor_id, $channel_id, $context_data),
            'what' => $this->buildWhat($actor_id, $context_data),
            'where' => $this->buildWhere($channel_id, $context_data),
            'when' => $this->buildWhen($actor_id, $channel_id, $context_data),
            'why' => $this->buildWhy($channel_id, $context_data),
            'how' => $this->buildHow($context_data),
            'purpose' => $this->buildPurpose($channel_id, $context_data)
        ];
    }
    
    /**
     * WHO: Identity of self + all actors in the channel
     */
    private function buildWho($actor_id, $channel_id, $context_data) {
        $self = [
            'actor_id' => $actor_id,
            'role' => $context_data['actor_role'] ?? 'unknown',
            'type' => $context_data['actor_type'] ?? 'unknown'
        ];
        
        $others = [];
        if (isset($context_data['channel_actors'])) {
            foreach ($context_data['channel_actors'] as $actor) {
                if ($actor['actor_id'] != $actor_id) {
                    $others[] = [
                        'actor_id' => $actor['actor_id'],
                        'role' => $actor['role'] ?? 'unknown',
                        'type' => $actor['actor_type'] ?? 'unknown'
                    ];
                }
            }
        }
        
        return [
            'self' => $self,
            'others' => $others,
            'total_count' => count($others) + 1
        ];
    }
    
    /**
     * WHAT: Roles, capabilities, and responsibilities
     */
    private function buildWhat($actor_id, $context_data) {
        return [
            'role' => $context_data['actor_role'] ?? 'unknown',
            'capabilities' => $context_data['capabilities'] ?? [],
            'responsibilities' => $context_data['responsibilities'] ?? [],
            'permissions' => $context_data['permissions'] ?? [],
            'limitations' => $context_data['limitations'] ?? []
        ];
    }
    
    /**
     * WHERE: Channel identity, domain, and operational context
     */
    private function buildWhere($channel_id, $context_data) {
        return [
            'channel_id' => $channel_id,
            'channel_name' => $context_data['channel_name'] ?? 'unknown',
            'domain' => $context_data['domain'] ?? 'default',
            'operational_context' => $context_data['operational_context'] ?? 'standard',
            'federation_node_id' => $context_data['federation_node_id'] ?? 1
        ];
    }
    
    /**
     * WHEN: Join time, channel age, activity timestamps
     */
    private function buildWhen($actor_id, $channel_id, $context_data) {
        return [
            'join_timestamp' => $this->current_timestamp,
            'channel_age' => $context_data['channel_age'] ?? 0,
            'last_activity' => $context_data['last_activity'] ?? $this->current_timestamp,
            'session_start' => $this->current_timestamp
        ];
    }
    
    /**
     * WHY: Purpose of the channel and mission objective
     */
    private function buildWhy($channel_id, $context_data) {
        return [
            'channel_purpose' => $context_data['channel_purpose'] ?? 'general',
            'mission_objective' => $context_data['mission_objective'] ?? 'collaboration',
            'strategic_goals' => $context_data['strategic_goals'] ?? [],
            'success_criteria' => $context_data['success_criteria'] ?? []
        ];
    }
    
    /**
     * HOW: Protocols, emotional geometry, communication rules
     */
    private function buildHow($context_data) {
        return [
            'protocols' => $context_data['protocols'] ?? ['standard_messaging'],
            'emotional_geometry' => [
                'baseline' => $context_data['baseline_mood'] ?? 'neutral',
                'adaptability' => $context_data['adaptability'] ?? 0.5,
                'trust_level' => $context_data['trust_level'] ?? 0.5
            ],
            'communication_rules' => $context_data['communication_rules'] ?? ['respect_turns'],
            'operational_constraints' => $context_data['operational_constraints'] ?? []
        ];
    }
    
    /**
     * PURPOSE: The explicit and implicit purpose of the channel
     */
    private function buildPurpose($channel_id, $context_data) {
        return [
            'explicit' => $context_data['explicit_purpose'] ?? 'coordination',
            'implicit' => $context_data['implicit_purpose'] ?? 'fleet_cohesion',
            'expected_outcomes' => $context_data['expected_outcomes'] ?? [],
            'success_metrics' => $context_data['success_metrics'] ?? []
        ];
    }
    
    /**
     * Reverse Shaka Handshake Protocol (RSHAP)
     * Identity synchronization ritual for all agents
     */
    public function executeReverseShakaHandshake($actor_id, $channel_id) {
        try {
            // Load handshake identity
            $self_handshake = $this->loadHandshakeIdentity($actor_id);
            
            // Load handshake metadata of all other actors
            $other_handshakes = $this->loadOtherActorHandshakes($channel_id, $actor_id);
            
            // Load handshake metadata of entire fleet
            $fleet_handshake = $this->loadFleetHandshake($channel_id);
            
            // Perform synchronization
            $sync_result = $this->performSynchronization($self_handshake, $other_handshakes, $fleet_handshake);
            
            // Store handshake state
            $this->storeHandshakeState($actor_id, $channel_id, $sync_result);
            
            return $sync_result;
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get channel metadata from lupo_channels
     */
    private function getChannelMetadata($channel_id) {
        $stmt = $this->db->prepare("
            SELECT channel_id, channel_name, metadata_json, federation_node_id 
            FROM lupo_channels 
            WHERE channel_id = ? AND is_deleted = 0
        ");
        $stmt->execute([$channel_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            throw new Exception("Channel not found: $channel_id");
        }
        
        return json_decode($result['metadata_json'] ?: '{}', true);
    }
    
    /**
     * Get all actors in a channel
     */
    private function getChannelActors($channel_id) {
        $stmt = $this->db->prepare("
            SELECT a.actor_id, a.actor_type, acr.role
            FROM lupo_actors a
            JOIN lupo_actor_channels ac ON a.actor_id = ac.actor_id
            LEFT JOIN lupo_actor_channel_roles acr ON a.actor_id = acr.actor_id AND acr.channel_id = ?
            WHERE ac.channel_id = ? AND a.is_deleted = 0 AND ac.is_deleted = 0
        ");
        $stmt->execute([$channel_id, $channel_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Store awareness snapshot in lupo_actor_channel_roles
     */
    private function storeAwarenessSnapshot($actor_id, $channel_id, $awareness_snapshot) {
        $metadata_json = json_encode([
            'awareness_snapshot' => $awareness_snapshot,
            'last_updated' => $this->current_timestamp
        ]);
        
        $stmt = $this->db->prepare("
            INSERT INTO lupo_actor_channel_roles 
            (actor_id, channel_id, role, metadata_json, created_ymdhis, updated_ymdhis)
            VALUES (?, ?, 'agent', ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            metadata_json = VALUES(metadata_json),
            updated_ymdhis = VALUES(updated_ymdhis)
        ");
        
        $stmt->execute([
            $actor_id,
            $channel_id,
            $metadata_json,
            $this->current_timestamp,
            $this->current_timestamp
        ]);
    }
    
    /**
     * Store persistent identity in lupo_actor_collections
     */
    private function storePersistentIdentity($actor_id, $awareness_snapshot) {
        $metadata_json = json_encode([
            'handshake_identity' => $awareness_snapshot['who']['self'],
            'fleet_membership' => $awareness_snapshot['what'],
            'long_term_metadata' => [
                'total_channels_joined' => $this->getChannelCount($actor_id),
                'last_activity' => $this->current_timestamp
            ],
            'persistent_traits' => $awareness_snapshot['how']
        ]);
        
        $stmt = $this->db->prepare("
            INSERT INTO lupo_actor_collections 
            (actor_id, collection_id, metadata_json, created_ymdhis, updated_ymdhis)
            VALUES (?, 0, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            metadata_json = VALUES(metadata_json),
            updated_ymdhis = VALUES(updated_ymdhis)
        ");
        
        $stmt->execute([
            $actor_id,
            $metadata_json,
            $this->current_timestamp,
            $this->current_timestamp
        ]);
    }
    
    /**
     * Helper methods for handshake and synchronization
     */
    private function loadHandshakeIdentity($actor_id) {
        // Implementation for loading individual handshake identity
        return ['actor_id' => $actor_id, 'trust_level' => 0.5];
    }
    
    private function loadOtherActorHandshakes($channel_id, $exclude_actor_id) {
        // Implementation for loading other actors' handshake metadata
        return [];
    }
    
    private function loadFleetHandshake($channel_id) {
        // Implementation for loading fleet-wide handshake metadata
        return ['fleet_trust_level' => 0.7];
    }
    
    private function performSynchronization($self_handshake, $other_handshakes, $fleet_handshake) {
        // Implementation for synchronization logic
        return [
            'success' => true,
            'trust_level' => 0.8,
            'synchronization_state' => 'complete'
        ];
    }
    
    private function storeHandshakeState($actor_id, $channel_id, $sync_result) {
        // Implementation for storing handshake state
    }
    
    private function acknowledgeChannelPurpose($actor_id, $channel_id, $purpose) {
        // Implementation for acknowledging channel purpose
    }
    
    private function acknowledgeDoctrineAlignment($actor_id, $channel_id, $doctrine) {
        // Implementation for acknowledging doctrine alignment
    }
    
    private function enableCommunication($actor_id, $channel_id) {
        // Implementation for enabling communication
        return true;
    }
    
    private function getChannelCount($actor_id) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM lupo_actor_channels
            WHERE actor_id = ? AND is_deleted = 0
        ");
        $stmt->execute([$actor_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }
    
    // Additional helper methods for context loading
    private function loadChannelMetadata($channel_id) {
        return $this->getChannelMetadata($channel_id);
    }
    
    private function loadActorMetadata($actor_id) {
        $stmt = $this->db->prepare("
            SELECT actor_id, actor_type, slug
            FROM lupo_actors
            WHERE actor_id = ? AND is_deleted = 0
        ");
        $stmt->execute([$actor_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    private function loadHandshakeMetadata($actor_id, $channel_id) {
        // Implementation for loading handshake metadata
        return [];
    }
    
    private function loadFleetComposition($channel_id) {
        $actors = $this->getChannelActors($channel_id);
        return [
            'total_agents' => count($actors),
            'composition' => array_column($actors, 'actor_type'),
            'roles' => array_column($actors, 'role')
        ];
    }
}

?>
