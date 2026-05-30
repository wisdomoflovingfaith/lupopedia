# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "protocol"
  file_path_from_root: "database/lupopedia/channels/channel_id/0/content/federation_node_id/0/FLARE_updated.md"
  system_version: "4.0.53"
  last_modified_utc: "20260301"
  channel_id: 0
  actor_id: 0
  delegation_chain: "0:10000"
  artifact_type: "protocol_documentation"
  artifact_kind: "system_specification"
  purpose: "Define FLARE protocol with delegation-activated AI startup and system initialization"
  mood_vector: "4169E1"  # RoyalBlue for core protocol
  traits: ["flare_protocol", "delegation_activation", "ai_startup", "v4.0.53"]
  tags: ["flare", "delegation", "ai_activation", "system_initialization", "federation"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "bin/initialize_system.php", type: "integration", weight: 1.0 }
    - { to: "channels/0/initialize_readme.md", type: "documentation", weight: 0.9 }
    - { to: "channels/0/actor_ai_running_check.md", type: "integration", weight: 1.0 }
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 0.9 }
    - { to: "includes/functions/ai_activation.php", type: "integration", weight: 1.0 }
  semantic_tags: ["flare_protocol", "delegation_activation", "ai_startup"]

lupopedia.footer:
  version: "4.0.53"
  last_verified: "20260301"
  last_verified_by: "windsurf"
---

# FLARE Protocol — Federation Node 0

**FLARE (Federated Logic and Actor Runtime Environment)** — Core protocol for Lupopedia federation and AI agent management.

## 🎯 Overview

FLARE is the canonical protocol that governs:
- **Federation Node Management**: Node 0 as canonical federation reference
- **Delegation-Activated AI Startup**: Automatic activation of AI agents from delegation chains
- **System Initialization**: Proper initialization of system components
- **Actor Runtime Management**: Session-based actor lifecycle management

## 🔑 Core Principles

### 1. Delegation-Activated AI Startup
**Rule**: Any AI actor (actor_id < 10000) present in a `delegation_chain` must be automatically activated if not already running.

**Implementation**:
```php
// Process delegation chain and activate AI actors
function processDelegationChain($chain, $db) {
    $results = [];
    $actors = explode(':', $chain);
    
    foreach ($actors as $actor_id) {
        $actor_id = (int)$actor_id;
        
        // Skip human actors (>=10000)
        if ($actor_id >= 10000) {
            $results[$actor_id] = 'skipped_human';
            continue;
        }
        
        // Activate AI actor
        $success = ensureActorActive($actor_id, $db, "delegation_chain:$chain");
        $results[$actor_id] = $success ? 'activated' : 'failed';
    }
    
    return $results;
}
```

### 2. System Initialization vs AI Activation
**Terminology Standardization**:
- **Initialize**: Systems and channels (e.g., "initialize the system")
- **Activate**: AI agents (e.g., "activate the AI agent")
- **Startup**: Generic term for any startup process

**Implementation**:
```php
// System initialization
function initializeSystem($db) {
    // Initialize core system components
    echo "🚀 Initializing Lupopedia System...\n";
    // ... initialization logic
}

// AI activation
function activateAI($actor_id, $db) {
    // Activate specific AI agent
    echo "🤖 Activating AI Agent $actor_id...\n";
    // ... activation logic
}
```

### 3. Federation Node 0 Authority
**Node 0** serves as the canonical reference for:
- **FLARE Protocol Documentation**: This document
- **Federation Web Paths**: `http://lupopedia.local/channels/0/content/federation_node_id/0/`
- **Delegation Chain Processing**: Central activation logic
- **Actor Registry**: Canonical actor definitions

## 📋 Activation Triggers

### Automatic Triggers
1. **Delegation Chain Processing**: When processing any delegation chain
2. **System Initialization**: During system startup
3. **Channel Operations**: When Channel 0 operations require AI assistance
4. **Scheduled Tasks**: For periodic AI health checks

### Manual Triggers
1. **Administrative Commands**: Via CLI or web interface
2. **Emergency Activation**: For critical system recovery
3. **Testing**: During development and testing phases

## 🔄 Activation Process

### Step 1: Check Running Status
```php
function isActorAIRunning($actor_id, $db, $threshold_hours = 24) {
    $threshold = gmdate('YmdHis', time() - ($threshold_hours * 3600));
    
    // Check active session
    $session = $db->query(
        "SELECT session_id FROM lupo_sessions
         WHERE actor_id = :actor_id 
           AND status = 'active' 
           AND is_deleted = 0 
           AND last_seen_ymdhis > :threshold",
        ['actor_id' => $actor_id, 'threshold' => $threshold]
    )->fetch();
    
    if (!$session) {
        return false;
    }
    
    // Check registry and channel state
    $registry = $db->query(
        "SELECT a.actor_id, cs.state_data
         FROM lupo_actors a
         LEFT JOIN lupo_channel_state cs ON a.actor_id = cs.actor_id AND cs.channel_id = 0
         WHERE a.actor_id = :actor_id 
           AND a.status = 'active' 
           AND a.is_deleted = 0",
        ['actor_id' => $actor_id]
    )->fetch();
    
    if (!$registry) {
        return false;
    }
    
    // Parse channel state JSON
    $state = json_decode($registry['state_data'], true);
    return ($state && isset($state['status']) && $state['status'] === 'active');
}
```

### Step 2: Activate if Needed
```php
function ensureActorActive($actor_id, $db, $trigger_source) {
    // Check if already running
    if (isActorAIRunning($actor_id, $db)) {
        logActivity($actor_id, "already_active", $trigger_source);
        return true;
    }
    
    // Attempt to activate
    try {
        $actor_class = getActorClass($actor_id);
        if (!class_exists($actor_class)) {
            throw new Exception("Actor class $actor_class not found");
        }
        
        $actor = new $actor_class($actor_id);
        if (!method_exists($actor, 'activate')) {
            throw new Exception("Actor class $actor_class missing activate() method");
        }
        
        $actor->activate();
        
        // Log activation
        logActivity($actor_id, "activated_by_delegation", $trigger_source);
        
        // Create session
        createActorSession($actor_id, $db);
        
        return true;
    } catch (Exception $e) {
        // Log failure and escalate
        logError($actor_id, "activation_failed", $e->getMessage());
        escalateIssue($actor_id, "activation_failure", $trigger_source);
        return false;
    }
}
```

### Step 3: Create Session and Update State
```php
function createActorSession($actor_id, $db) {
    $session_id = bin2hex(random_bytes(16));
    $now = gmdate('YmdHis');
    
    $db->execute(
        "INSERT INTO lupo_sessions 
         (session_id, actor_id, status, last_seen_ymdhis, created_ymdhis, updated_ymdhis, is_deleted)
         VALUES 
         (:session_id, :actor_id, 'active', :last_seen, :created, :updated, 0)",
        [
            'session_id' => $session_id,
            'actor_id' => $actor_id,
            'last_seen' => $now,
            'created' => $now,
            'updated' => $now
        ]
    );
    
    return $session_id;
}
```

## 🎛️ Integration Points

### System Integration
- **Initialize Script**: `bin/initialize_system.php` uses FLARE for system startup
- **Installer**: Web installer uses FLARE for AI activation during setup
- **File Processing**: File processing scripts use FLARE for AI assistance

### Database Integration
- **lupo_sessions**: Session management for active actors
- **lupo_actors**: Actor registry and status tracking
- **lupo_channel_state**: Channel-specific actor state
- **lupo_channel_logs**: Activity logging
- **lupo_channel_escalations**: Error escalation

### Federation Integration
- **Node 0 Authority**: Canonical reference for federation operations
- **Web Paths**: Standardized paths for federation content
- **Delegation Processing**: Central delegation chain processing

## 🚨 Error Handling and Escalation

### Activation Failures
```php
function escalateIssue($actor_id, $issue_type, $context) {
    $db = DatabaseFactory::getConnection();
    
    $db->execute(
        "INSERT INTO lupo_channel_escalations 
         (channel_id, actor_id, escalation_type, reason, context_data, created_ymdhis, is_deleted)
         VALUES 
         (0, :actor_id, :type, :reason, :context, :created, 0)",
        [
            'actor_id' => $actor_id,
            'type' => $issue_type,
            'reason' => "FLARE activation failure: $issue_type",
            'context' => json_encode(['context' => $context, 'timestamp' => gmdate('YmdHis')]),
            'created' => gmdate('YmdHis')
        ]
    );
}
```

### Logging
```php
function logActivity($actor_id, $activity, $context) {
    $db = DatabaseFactory::getConnection();
    
    $db->execute(
        "INSERT INTO lupo_channel_logs 
         (channel_id, actor_id, log_type_id, log_text, created_ymdhis, is_deleted)
         VALUES 
         (0, :actor_id, 1, :log_text, :created, 0)",
        [
            'actor_id' => $actor_id,
            'log_text' => "FLARE: $activity - $context",
            'created' => gmdate('YmdHis')
        ]
    );
}
```

## 📊 Actor Class Mapping

```php
function getActorClass($actor_id) {
    $map = [
        0 => 'SystemAI',
        1 => 'CaptainWolfieAI',
        2 => 'LilithAI',
        3 => 'RoseAI',
        4 => 'ErisAI',
        5 => 'MetisAI',
        19 => 'AnubisAI',
        25 => 'VishwakarmaAI',
        1000 => 'KiroIDE',
        1001 => 'WindsurfIDE',
        1002 => 'CursorIDE',
        1003 => 'AntigravityIDE',
        1004 => 'WarpIDE',
        1005 => 'CascadeIDE',
    ];
    
    return isset($map[$actor_id]) ? $map[$actor_id] : 'GenericAI';
}
```

## 🌐 Federation Web Paths

**Node 0 Web Paths**:
- **FLARE Protocol**: `http://lupopedia.local/channels/0/content/federation_node_id/0/FLARE.md`
- **Changelog**: `http://lupopedia.local/channels/0/content/federation_node_id/0/changelog.md`
- **README**: `http://lupopedia.local/channels/0/content/federation_node_id/0/readme.md`

**Standardized Path Format**:
```
http://lupopedia.local/channels/0/content/federation_node_id/0/{document}.md
```

## 🔄 Version History

**v4.0.53** (2026-03-01):
- ✅ Created canonical FLARE.md at federation node 0
- ✅ Implemented delegation-activated AI startup
- ✅ Standardized terminology: Initialize (systems), Activate (AI)
- ✅ Added comprehensive error handling and escalation
- ✅ Integrated with session management system

## 📚 References

- **FLARE Doctrine**: `docs/doctrine/FLARE/FLARE_DOCTRINE.md`
- **Actor Running Check**: `channels/0/actor_ai_running_check.md`
- **Initialize Script**: `bin/initialize_system.php`
- **AI Activation Functions**: `includes/functions/ai_activation.php`
- **Session Management**: `docs/database/lupopedia/tables/SESSION_MANAGEMENT_SYSTEM.md`

---

**FLARE Protocol Authority**: Federation Node 0  
**Canonical Location**: `channels/0/content/federation_node_id/0/FLARE.md`  
**Version**: 4.0.53  
**Last Updated**: 2026-03-01
