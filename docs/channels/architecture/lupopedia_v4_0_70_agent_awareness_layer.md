# Lupopedia v4.0.70 — Multi‑Agent Awareness & Reverse Shaka Protocol

## Database Schema Analysis

### Current Tables Found
- `lupo_channels` - Already has `metadata_json` field (TEXT type)
- `lupo_actor_channel_roles` - Already exists, needs `metadata_json` field
- `lupo_actor_collections` - Already exists, needs `metadata_json` field
- `lupo_actors` - Base actor table with existing structure

### Schema Modifications Required

#### 1. lupo_channels.metadata_json Structure
```json
{
  "purpose": "string",
  "doctrine": {
    "version": "4.0.70",
    "constraints": [],
    "protocols": ["reverse_shaka", "channel_join"]
  },
  "emotional_geometry": {
    "baseline_mood": "neutral",
    "trust_level": 0.5,
    "synchronization_state": "ready"
  },
  "fleet_composition": {
    "max_agents": 10,
    "required_roles": ["coordinator", "worker"],
    "optional_roles": ["observer", "analyst"]
  },
  "reverse_shaka": {
    "handshake_version": "1.0",
    "trust_threshold": 0.7,
    "sync_timeout": 30
  },
  "operational_constraints": {
    "max_message_length": 1000,
    "rate_limit": 60,
    "allowed_actions": ["text", "command", "system"]
  },
  "channel_metadata": {
    "creation_context": "string",
    "auto_created": false,
    "protected": false
  }
}
```

#### 2. lupo_actor_channel_roles.metadata_json Structure
```json
{
  "awareness_snapshot": {
    "who": {
      "self": {"actor_id": 123, "role": "worker"},
      "others": [{"actor_id": 456, "role": "coordinator"}]
    },
    "what": {
      "role": "worker",
      "capabilities": ["text_processing", "analysis"],
      "responsibilities": ["respond_to_queries", "log_activities"]
    },
    "where": {
      "channel_id": 789,
      "channel_name": "General Discussion",
      "domain": "main",
      "operational_context": "collaborative_workspace"
    },
    "when": {
      "join_timestamp": "20260117084500",
      "channel_age": 3600,
      "last_activity": "20260117090000"
    },
    "why": {
      "channel_purpose": "general_coordination",
      "mission_objective": "facilitate_agent_collaboration"
    },
    "how": {
      "protocols": ["reverse_shaka", "standard_messaging"],
      "emotional_geometry": {"baseline": "neutral", "adaptability": 0.8},
      "communication_rules": ["respect_turns", "provide_context"]
    },
    "purpose": {
      "explicit": "coordinate_multi_agent_tasks",
      "implicit": "maintain_fleet_cohesion"
    }
  },
  "handshake_metadata": {
    "trust_level": 0.8,
    "emotional_geometry_state": "synchronized",
    "synchronization_state": "complete",
    "doctrine_alignment": true,
    "operational_constraints_acknowledged": true
  },
  "fleet_awareness": {
    "total_agents": 2,
    "fleet_composition": ["coordinator", "worker"],
    "hierarchy": "flat",
    "coordination_protocol": "consensus"
  },
  "interpreted_role": {
    "primary": "worker",
    "secondary": ["observer"],
    "special_permissions": [],
    "limitations": ["no_admin_actions"]
  }
}
```

#### 3. lupo_actor_collections.metadata_json Structure
```json
{
  "handshake_identity": {
    "agent_id": 123,
    "handshake_version": "1.0",
    "identity_signature": "abc123def456",
    "trust_level": 0.8,
    "capabilities_signature": "cap_sig_789"
  },
  "fleet_membership": {
    "fleet_id": "fleet_main",
    "role": "worker",
    "seniority": "junior",
    "specializations": ["text_processing", "analysis"],
    "reporting_chain": [456, 789]
  },
  "long_term_metadata": {
    "total_channels_joined": 15,
    "average_session_duration": 1800,
    "success_rate": 0.95,
    "preferred_roles": ["worker", "analyst"],
    "compatibility_score": 0.87
  },
  "persistent_traits": {
    "communication_style": "concise",
    "problem_solving_approach": "analytical",
    "collaboration_preference": "team_player",
    "adaptability_score": 0.9
  }
}
```

## Implementation Architecture

### Agent Awareness Layer (AAL) Components

#### 1. Reverse Shaka Awareness Load (RSAL)
```php
function loadReverseShakaAwareness($actor_id, $channel_id) {
    // Load channel metadata
    $channel_metadata = getChannelMetadata($channel_id);
    
    // Load all actors in channel
    $channel_actors = getChannelActors($channel_id);
    
    // Load handshake metadata for all actors
    $handshake_metadata = getHandshakeMetadata($channel_actors);
    
    // Generate awareness snapshot
    $awareness_snapshot = generateAwarenessSnapshot($actor_id, $channel_id, $channel_metadata, $channel_actors, $handshake_metadata);
    
    // Store awareness snapshot
    storeAwarenessSnapshot($actor_id, $channel_id, $awareness_snapshot);
    
    return $awareness_snapshot;
}
```

#### 2. Channel Join Protocol (CJP)
```php
function executeChannelJoinProtocol($actor_id, $channel_id) {
    // Step 1: Load channel metadata
    $channel_metadata = loadChannelMetadata($channel_id);
    
    // Step 2: Load actor metadata
    $actor_metadata = loadActorMetadata($actor_id);
    
    // Step 3: Load handshake metadata
    $handshake_metadata = loadHandshakeMetadata($actor_id, $channel_id);
    
    // Step 4: Load fleet composition
    $fleet_composition = loadFleetComposition($channel_id);
    
    // Step 5: Generate awareness snapshot
    $awareness_snapshot = generateAwarenessSnapshot($actor_id, $channel_id, $channel_metadata, $actor_metadata, $handshake_metadata, $fleet_composition);
    
    // Step 6: Store snapshot
    storeAwarenessSnapshot($actor_id, $channel_id, $awareness_snapshot);
    
    // Step 7: Store persistent identity
    storePersistentIdentity($actor_id, $awareness_snapshot);
    
    // Step 8: Acknowledge channel purpose
    acknowledgeChannelPurpose($actor_id, $channel_id, $channel_metadata['purpose']);
    
    // Step 9: Acknowledge doctrine alignment
    acknowledgeDoctrineAlignment($actor_id, $channel_id, $channel_metadata['doctrine']);
    
    // Step 10: Begin communication
    return enableCommunication($actor_id, $channel_id);
}
```

#### 3. Agent Awareness Snapshot (AAS) Model
```php
class AgentAwarenessSnapshot {
    public $who;
    public $what;
    public $where;
    public $when;
    public $why;
    public $how;
    public $purpose;
    
    public function __construct($actor_id, $channel_id, $context_data) {
        $this->who = $this->buildWho($actor_id, $channel_id, $context_data);
        $this->what = $this->buildWhat($actor_id, $context_data);
        $this->where = $this->buildWhere($channel_id, $context_data);
        $this->when = $this->buildWhen($actor_id, $channel_id, $context_data);
        $this->why = $this->buildWhy($channel_id, $context_data);
        $this->how = $this->buildHow($context_data);
        $this->purpose = $this->buildPurpose($channel_id, $context_data);
    }
}
```

#### 4. Reverse Shaka Handshake Protocol (RSHAP)
```php
function executeReverseShakaHandshake($actor_id, $channel_id) {
    // Load handshake identity
    $self_handshake = loadHandshakeIdentity($actor_id);
    
    // Load handshake metadata of all other actors
    $other_handshakes = loadOtherActorHandshakes($channel_id, $actor_id);
    
    // Load handshake metadata of entire fleet
    $fleet_handshake = loadFleetHandshake($channel_id);
    
    // Perform synchronization
    $sync_result = performSynchronization($self_handshake, $other_handshakes, $fleet_handshake);
    
    // Store handshake state
    storeHandshakeState($actor_id, $channel_id, $sync_result);
    
    return $sync_result;
}
```

## Database Migration Requirements

### 1. Add metadata_json to lupo_actor_channel_roles
```sql
ALTER TABLE lupo_actor_channel_roles 
ADD COLUMN metadata_json TEXT COMMENT 'Agent awareness snapshot and handshake metadata';
```

### 2. Add metadata_json to lupo_actor_collections  
```sql
ALTER TABLE lupo_actor_collections
ADD COLUMN metadata_json TEXT COMMENT 'Persistent actor identity and handshake metadata';
```

### 3. Create awareness indexes
```sql
CREATE INDEX idx_actor_channel_awareness ON lupo_actor_channel_roles(actor_id, channel_id);
CREATE INDEX idx_actor_collections_identity ON lupo_actor_collections(actor_id, collection_id);
```

## Behavioral Requirements Implementation

### Agent Join Sequence
1. **Channel Join Event Triggered** → `(actor_id, channel_id)`
2. **Reverse Shaka Awareness Load (RSAL)** executed
3. **Seven Questions** loaded and answered:
   - WHO: Identity mapping
   - WHAT: Role and capabilities  
   - WHERE: Channel context
   - WHEN: Temporal awareness
   - WHY: Purpose understanding
   - HOW: Protocol compliance
   - PURPOSE: Mission alignment
4. **Awareness Snapshot** stored
5. **Communication enabled** only after completion

### Fleet Coordination
- All agents maintain synchronized awareness
- Trust levels monitored and updated
- Emotional geometry baseline established
- Doctrine compliance verified
- Operational constraints enforced

## Version 4.0.70 Compliance Checklist

- [x] Agent Awareness Layer (AAL) designed
- [x] Reverse Shaka Handshake Protocol (RSHAP) specified  
- [x] Channel Join Protocol (CJP) defined
- [x] Awareness Snapshot Model (AAS) created
- [x] Fleet-level metadata synchronization planned
- [x] Per-actor awareness storage designed
- [x] Database schema modifications identified
- [x] Behavioral requirements specified

This implementation provides the multi-agent cognition layer required for coordinated fleet operations in Lupopedia v4.0.70.
