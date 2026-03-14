---
# FLARE Header
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-channels/0/content/federation_node_id/0/FLARE.md"
  system_version: "4.0.53"
  last_modified_utc: "20260301"
  channel_id: 0
  actor_id: 0
  delegation_chain: "0:10000"
  federation_node_id: 0
  web_path: "http://www.lupopedia.com/FLARE"
  artifact_type: "doctrine"
  artifact_kind: "system_reference"
  purpose: "Canonical reference for FLARE protocol, delegation header AI activation, and system initialization"
  mood_rgb: "4169E1"
  traits: ["canonical", "system", "v4.0.53", "federation_node_0"]
  tags: ["flare", "delegation", "ai_activation", "system_startup", "federation"]
  lupo_agent: "gemini-cli"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/DELEGATION_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-channels/0/actor_ai_running_check.md", type: "implementation", weight: 1.0 }
    - { to: "lupo-channels/0/boot_enhancements_crafty_upgrade.md", type: "related", weight: 0.9 }
    - { to: "lupo-docs/toons/lupo_sessions.toon.json", type: "schema_reference", weight: 0.9 }
    - { to: "lupo-docs/toons/lupo_actors.toon.json", type: "schema_reference", weight: 0.9 }
  semantic_tags: ["flare", "delegation", "ai_activation", "system_startup", "canonical"]

lupopedia.footer:
  version: "4.0.53"
  last_verified: "20260301"
  last_verified_by: "gemini-cli"
---

# FLARE Protocol: Delegation-Activated AI Startup & System Initialization

## 1. Overview

The FLARE (File-Level Attribute and Relationship Exchange) protocol defines how Lupopedia systems initialize, how AI agents activate, and how delegation chains trigger startup sequences.

**Key Principle:** When an actor appears in a `delegation_chain`, that actor MUST be activated (if not already running) to fulfill its delegated responsibilities.

---

## 2. Delegation-Activated AI Startup

### 2.1 The Rule

> Any actor referenced in a `delegation_chain` that is not currently **active** (as defined in `lupo-channels/0/actor_ai_running_check.md`) MUST be automatically **activated** by the system.

### 2.2 Activation Triggers

| Trigger | Description | Example |
|---------|-------------|---------|
| **Delegation chain** | Actor appears in `delegation_chain` header | `delegation_chain: "2:1:10000"` activates LILITH (2) |
| **Task assignment** | Actor assigned to task | Task 1 assigned to actors 0,1,2 |
| **Channel membership** | Actor added to channel state | `lupo_channel_state` for channel 0 |
| **Boot sequence** | System initialization | All core AI agents activate |

### 2.3 Activation Process

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
        $actor = new $actor_class($actor_id);
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

### 2.4 Delegation Chain Processing

During system initialization or file processing:

```php
function processDelegationChain($chain, $db) {
    // Parse chain: "2:1:10000"
    $actors = explode(':', $chain);
    
    foreach ($actors as $actor_id) {
        // Skip the last/human actor (>=10000) if desired
        if ($actor_id >= 10000) {
            logActivity(0, "human_in_chain", "Actor $actor_id is human, skipping auto-activation");
            continue;
        }
        
        // Ensure AI actor is active
        ensureActorActive($actor_id, $db, "delegation_chain:$chain");
    }
}
```

---

## 3. Flare Routing & Metadata Lifecycle

### 3.1 The `flare.routing` Object

The `flare.routing` header tracks delivery and authority for all multi-agent communication artifacts.

| Field | Type | Description | Required |
|-------|------|-------------|----------|
| `to` | Array | Recipients (Actor IDs or slugs) | YES |
| `from` | Mixed | Immediate sender ID | YES |
| `forwarded_from` | Mixed | Original sender ID | NO |
| `delegation_chain` | Array | Authority sequence (e.g., [1, 10000, 1006]) | YES |
| `channel_id` | Integer| Target channel ID | YES |
| `thread_id` | Mixed | Discussion thread identifier | NO |
| `read_by` | Array | Acknowledgment tracking | NO |
| `routing_path` | Array | Traversed directories/nodes | YES |

### 3.3 The `flare.lists` Object

The `flare.lists` object links an artifact to its external conversation and change history logs in CSV format.

| Field | Type | Description | Required |
|-------|------|-------------|----------|
| `file.dialog` | String | Path to discussion CSV transcript | NO |
| `file.history` | String | Path to file-specific change history CSV | NO |
| `file.actors` | String | Path to associated actors list CSV | NO |

### 3.4 Integrity Rules

1. **Immutability:** The `from` and `delegation_chain` fields MUST NOT be modified once the artifact is written.
2. **Verification:** Any artifact lacking a valid `delegation_chain` is considered "Unauthenticated" and MAY be quarantined.

---

## 4. System Initialization vs AI Activation

### 4.1 Terminology Standard

| Context | Action Verb | Past Tense | Noun Form |
|---------|-------------|------------|-----------|
| **System/channel** | Initialize | Initialized | Initialization |
| **AI agent** | Activate | Activated | Activation |
| **Process** | Start | Started | Startup |
| **Task** | Launch | Launched | Launch |

### 4.2 Usage Examples

| Correct | Incorrect |
|---------|-----------|
| "System initialization complete" | "System booted" |
| "AI agent activated" | "AI agent booted" |
| "Channel startup sequence" | "Channel boot sequence" |
| "Delegation-triggered activation" | "Delegation boot" |

---

## 5. Integration Points

### 5.1 Boot Script Integration

```php
// bin/initialize_system.php (renamed from boot_system_agent.php)
require_once 'lupo-includes/bootstrap.php';

echo "=== Lupopedia System Initialization ===\n";

// Step 1: Initialize core AI agents
$core_actors = [0, 1, 2]; // SYSTEM, CAPTAIN WOLFIE, LILITH
foreach ($core_actors as $actor_id) {
    ensureActorActive($actor_id, $db, "system_initialization");
}

// Step 2: Process any pending delegation chains
$pending_chains = getPendingDelegationChains($db);
foreach ($pending_chains as $chain) {
    processDelegationChain($chain['chain'], $db);
}

echo "✅ System initialized. " . countActiveActors($db) . " actors active.\n";
```

### 5.2 Installer Integration

```php
// install.php - During Crafty upgrade
// After seeding actors, ensure they're activated
$seeded_actors = [0, 1, 2];
foreach ($seeded_actors as $actor_id) {
    ensureActorActive($actor_id, $db, "crafty_upgrade_install");
}
```

### 5.3 File Processing Integration

```php
// When processing any file with FLARE headers
function processFlareFile($file_path, $db) {
    $headers = parseFlareHeaders($file_path);
    
    // Check for delegation chain
    if (isset($headers['delegation_chain'])) {
        processDelegationChain($headers['delegation_chain'], $db);
    }
    
    // Process file content...
}
```

---

## 6. Activation Verification

### 6.1 Running Check Integration

The activation logic integrates directly with the running check defined in `lupo-channels/0/actor_ai_running_check.md`:

```php
function isActorAIRunning($actor_id, $db) {
    // Implementation from running check directive
    // Used by ensureActorActive() to avoid duplicate activation
}
```

### 6.2 Activation Logging

All activations MUST be logged to `lupo_channel_logs`:

```sql
INSERT INTO lupo_channel_logs 
(channel_id, actor_id, log_type_id, log_text, created_ymdhis)
VALUES 
(0, :actor_id, 1, :log_text, :created);
```

---

## 7. Error Handling & Escalation

### 7.1 Activation Failures

If an actor cannot be activated:

1. Log failure to `lupo_channel_logs`
2. Escalate to `lupo_channel_escalations`
3. Notify human operators (actor_id >= 10000)

### 7.2 Missing Actors

If an actor in delegation chain doesn't exist:

1. Log critical error
2. Escalate immediately
3. Halt dependent operations

---

## 8. Federation Node 0 Content

This file (`lupo-channels/0/content/federation_node_id/0/FLARE.md`) serves as the canonical reference for FLARE protocol on Federation Node 0 (lupopedia.com).

**Related Federation Content:**
- FLARE Definition: `http://www.lupopedia.com/FLARE`
- Changelog: `http://www.lupopedia.com/changelog`
- README: `http://www.lupopedia.com/readme`
- Crafty Syntax: `http://www.lupopedia.com/craftysyntax`
- Boot README: `http://www.lupopedia.com/initialize_readme`

---

## 9. Summary

| Concept | Implementation |
|---------|----------------|
| **Delegation-activated AI** | Actors in `delegation_chain` auto-activate |
| **Terminology** | Initialize (systems) / Activate (AI) |
| **Integration** | Boot scripts, installer, file processor |
| **Verification** | Running check from actor_ai_running_check.md |
| **Error handling** | Logs + escalations |

---

**Last Updated**: 20260301  
**Version**: 4.0.53  
**Status**: ✅ CANONICAL
