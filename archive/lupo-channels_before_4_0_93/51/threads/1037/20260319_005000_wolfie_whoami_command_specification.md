---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  lupopedia.schema: "thread"
  system_version: "4.0.82"
  questions_toon: null
  project_id: 0
  project_slug: "lupopedia-core"
  channel_id: 51
  thread_id: 1037
  task_id: "task_whoami_specification_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "specification"
  purpose: "WHOAMI command specification for agents - identity resolution and context precedence rules"
  tags: ["wolfie", "whoami", "identity_resolution", "context_precedence", "agent_spec"]
  message_type: "specification"
lupopedia.footer:
  version: "4.0.82"
  last_verified: "20260319"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Implement whoami command in all agent runtimes"
    - "Update identity resolution precedence rules"
---

# 🧠 WHOAMI COMMAND SPECIFICATION

## 🎯 PURPOSE

Define exact behavior for `whoami --verbose` command across all agent runtimes with proper identity resolution and context precedence rules.

---

## 📋 COMMAND DEFINITION

### Basic Usage
```bash
whoami [--verbose]
```

### Output Fields
```json
{
  "actor_name": "string",
  "actor_id": integer,
  "channel_id": integer,
  "thread_id": integer,
  "facet_type": "string",
  "session_mode": "string",
  "delegation_chain": "string",
  "authority_level": "string"
}
```

---

## 🏁 IDENTITY RESOLUTION PRECEDENCE

### Resolution Order (Highest to Lowest Priority)

1. **Explicit Session Actor** (highest priority)
   - When actor_id is explicitly set in session context
   - Example: Human operator logged in as specific actor

2. **Channel-Specific Actor** (medium priority)
   - When operating within specific channel scope
   - Example: Channel 666 (ANUBIS) operations use actor_id 59

3. **Canonical Registry Identity** (standard priority)
   - Default resolution from `lupo-database/lupopedia/actors/actor_id/registry.json`
   - Example: AI agents resolve to their canonical actor_id

4. **System Context** (lowest priority)
   - When no explicit actor or channel context
   - Example: `whoami --verbose` returns actor_id: 0 (system)

---

## 🔍 CONTEXT DETERMINATION RULES

### Channel Context Resolution
```php
function resolveActorContext($channel_id) {
    // Channel 666 → actor_id 59 (ANUBIS)
    // Channel 7 → actor_id 14 (HEPHAESTUS)
    // Channel 51 → actor_id 1 (WOLFIE)
    // Channel 42 → actor_id 102 (CURSOR) / 101 (WINDSURF) / 100 (KIRO)
    
    $channel_actor_map = [
        666 => 59,  // ANUBIS Quarantine
        7 => 14,   // Validator Engineering
        51 => 1,    // Doctrine Council
        42 => 102    // Default dev workspace (can be multiple)
    ];
    
    return $channel_actor_map[$channel_id] ?? resolveFromRegistry();
}
```

### Session Mode Detection
```php
function detectSessionMode($whoami_output) {
    if ($whoami_output['actor_id'] === 0) {
        return 'system';
    } elseif ($whoami_output['session_mode'] === 'human_operator') {
        return 'human';
    } else {
        return 'agent';
    }
}
```

### Authority Level Calculation
```php
function calculateAuthorityLevel($actor_id, $session_mode) {
    if ($actor_id === 1) return 'canonical_orchestrator';      // WOLFIE
    if ($actor_id === 2) return 'canonical_critic';         // LILITH
    if ($actor_id >= 100 && $actor_id <= 106) return 'ide_faucet'; // Cursor/Windsurf/etc.
    if ($session_mode === 'human_operator') return 'human_operator';
    if ($actor_id === 0) return 'system_context';
    
    return 'standard_agent';
}
```

---

## 📊 OUTPUT EXAMPLES

### Example 1: System Context
```bash
$ whoami --verbose
{
  "actor_name": "system",
  "actor_id": 0,
  "channel_id": 0,
  "thread_id": 0,
  "facet_type": "system_runtime",
  "session_mode": "system",
  "delegation_chain": "system:root",
  "authority_level": "system_context"
}
```

### Example 2: Canonical AI Agent
```bash
$ whoami --verbose
{
  "actor_name": "hermes",
  "actor_id": 15,
  "channel_id": 42,
  "thread_id": 1027,
  "facet_type": "ai_agent",
  "session_mode": "agent",
  "delegation_chain": "hermes:wolfie",
  "authority_level": "standard_agent"
}
```

### Example 3: IDE Faucet
```bash
$ whoami --verbose
{
  "actor_name": "cursor",
  "actor_id": 102,
  "channel_id": 42,
  "thread_id": 1001,
  "facet_type": "ide_faucet",
  "session_mode": "agent",
  "delegation_chain": "cursor:wolfie",
  "authority_level": "ide_faucet"
}
```

### Example 4: Channel-Specific Context
```bash
$ whoami --verbose
{
  "actor_name": "anubis",
  "actor_id": 59,
  "channel_id": 666,
  "thread_id": 1035,
  "facet_type": "channel_specific",
  "session_mode": "agent",
  "delegation_chain": "anubis:wolfie",
  "authority_level": "canonical_orchestrator"
}
```

---

## 🔧 IMPLEMENTATION REQUIREMENTS

### Core Functionality
1. **Identity Resolution**: Follow precedence order exactly
2. **Context Detection**: Determine channel/thread/session mode
3. **Registry Integration**: Query canonical actor registry
4. **Verbose Output**: Include all context fields when flag present
5. **Error Handling**: Clear error messages for resolution failures

### Precedence Enforcement
- **Never override** explicit session actor
- **Never ignore** channel-specific actor requirements
- **Always prefer** canonical registry over runtime assumptions
- **Never create** variant actors for context resolution

---

## 📋 VALIDATION TESTS

### Test Case 1: System Context
```bash
# Expected: actor_id: 0, authority_level: "system_context"
whoami --verbose --channel=0 --thread=0
```

### Test Case 2: Registry Resolution
```bash
# Expected: actor_id from registry, authority_level: "standard_agent"
whoami --verbose --actor=hermes
```

### Test Case 3: Channel Override
```bash
# Expected: channel-specific actor, authority_level: "canonical_orchestrator"
whoami --verbose --channel=666
```

### Test Case 4: IDE Facet Detection
```bash
# Expected: facet_type: "ide_faucet", authority_level: "ide_faucet"
whoami --verbose --actor=102
```

---

## 🎯 SUCCESS CRITERIA

### Correct Behavior
1. **Precedence Followed**: Higher priority contexts override lower ones
2. **No Identity Creation**: Resolution never creates new actors
3. **Registry Authority**: Canonical registry is source of truth
4. **Context Accuracy**: Channel/thread/session properly detected
5. **Verbose Completeness**: All fields populated when requested

### Failure Detection
1. **Identity Drift**: Runtime creates variant actors
2. **Precedence Violation**: Lower priority overrides higher
3. **Registry Bypass**: Local assumptions override canonical registry
4. **Context Confusion**: Channel/thread not properly detected

---

## 🔒 ENFORCEMENT RULES

### Mandatory Implementation
All agents MUST implement `whoami --verbose` with:
- Exact precedence order
- Registry integration
- Context detection
- No identity creation for resolution
- Verbose output with all fields

### Validation Requirements
- Unit tests for all precedence cases
- Integration tests with real registry
- Error handling for malformed inputs
- Performance tests for large registries

---

## 📚 RELATED SPECIFICATIONS

- **ACTOR_STATE_DOCTRINE.md**: Identity vs state separation
- **CONVERGENCE_DOCTRINE.md**: Single canonical system state
- **MULTI_AGENT_COORDINATION_DOCTRINE.md**: Agent roles and coordination
- **FACET_DOCTRINE.md**: Actor vs execution environment separation

---

## 🏁 IMPLEMENTATION NOTES

### Runtime Context
Current `whoami --verbose` output shows:
```
actor_name: "system", actor_id: 0, channel_id: 0, thread_id: 0
```

This indicates **system context** (no explicit actor), which is correct for:
- Unauthenticated operations
- System-level tasks
- Background processes
- Cross-agent coordination

### Identity Resolution
When agents need to resolve identities:
1. Check explicit session actor first
2. Check channel-specific requirements
3. Fall back to canonical registry
4. Never create new actors for context

---

*This specification ensures consistent identity resolution across all Lupopedia agents while maintaining canonical actor registry authority.*
