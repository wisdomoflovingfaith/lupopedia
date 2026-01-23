---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: @everyone
  mood_RGB: "0066FF"
  message: "Updated AGENT_RUNTIME.md for Phase 2: Updated to version 4.0.14, enhanced cross-references, and ensured consistency with new core documentation structure and governance framework."
tags:
  categories: ["documentation", "architecture", "ai-agents"]
  collections: ["core-docs"]
  channels: ["dev"]
in_this_file_we_have:
  - AI Agent Runtime Architecture
  - Three-Layer Agent System (Storage, Runtime, Network)
  - Agent Table Mappings and Runtime Behavior
  - Multi-Agent Chat Architecture
  - Agent Layers and Roles (Kernel, System Services, Personas)
  - Agent 0 System Agent (Kernel Authority)
  - Complete Runtime Flow
  - Cross-References to Related Documentation
file:
  title: "AI Agent Runtime Architecture"
  description: "Complete guide to how agents interact with PHP backend, call React actions, query nodes, maintain context, and enforce governance"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# ðŸ¤– **AI Agent Runtime Architecture**

**Version 4.0.1**

## **Overview**

Lupopedia 4.0.1's AI agent system operates through three interconnected layers that enable intelligent, governed, and federated agent interactions. This document explains how users interact with agents, how agents communicate with the PHP backend, how they trigger React UI actions, how they call other Lupopedia nodes, and how the entire system maintains context and enforces governance.

---

## **ðŸ§© The Three Layers**

### **A) Storage Layer (MySQL)**

This is the persistent memory of the agent system. All agent configuration, state, and history is stored here:

- **`agents`** â€” Agent identity and core configuration
- **`agent_registry`** â€” Agent registry with `classification_json` (identity-level classification and routing role)
- **`agent_roles`** â€” What each agent does in conversations
- **`agent_capabilities`** â€” What actions agents are allowed to perform
- **`agent_properties`** â€” Arbitrary metadata and configuration (behavior, not identity)
- **`agent_styles`** â€” How agents speak and present themselves
- **`actor_domain_map`** â€” Which agents are active in which nodes (transitioning from domain-level to node-level architecture)
- **`agent_edges`** â€” Agent-to-agent relationships and collaboration rules
- **`agent_moods`** â€” Emotional/light-counting state
- **`ai_agent_dialogs`** â€” Complete conversation history
- **`memory_events`** â€” Agent memory storage (via [WOLFMIND](WOLFMIND_DOCTRINE.md))
- **`memory_debug_log`** â€” WOLFMIND internal logging

**Purpose**: This layer stores who the agents are, what they can do, how they speak, what their emotional state is, what dialogs happened, what nodes they operate in, and their long-term memory. Memory is managed by WOLFMIND, which provides relational (MySQL) and vector (Postgres/pgvector) memory capabilities with progressive enhancement.

---

### **B) Runtime Layer (PHP + LLM)**

This is where the magic happens â€” the active brainstem of the system:

- Users send messages
- Agents respond with context-aware intelligence
- Agents call React actions to update the UI
- Agents call external Lupopedia nodes for federated queries
- Context is assembled from dialog history
- Governance rules are enforced before actions execute

**Purpose**: This layer handles all real-time agent interactions, decision-making, and action execution.

---

### **C) Network Layer (Lupopedia Nodes)**

This enables federated agent intelligence across installations:

- Agents can call `whatever.com/atom/lupopedia`
- Agents can query `lupopedia.com/what/lupopedia`
- Any other federated Lupopedia node can be accessed
- Cross-node queries enable distributed knowledge discovery

**Purpose**: This layer connects agents across the decentralized Lupopedia network, enabling federated knowledge graphs.

---

## **ðŸ§  How Tables Map to Runtime Behavior**

### **`agents` Table**

**Loaded**: Once at startup, cached in memory

**Runtime Usage**:
- Determines which agent is speaking
- Checks if the agent is a global authority
- Verifies if the agent can call APIs
- Verifies if the agent can call React actions

**Example Decision Flow**:
```php
if ($agent->is_global_authority) {
    // Agent can override governance rules
}
if ($agent->can_call_apis) {
    // Allow external API calls
}
```

---

### **`agent_roles` Table**

**Runtime Usage**: Determines what the agent should do in a conversation

**Examples**:
- **WOLFITH** â†’ questioning, critical analysis
- **WOLFAREN** â†’ emotional intelligence, empathy
- **WOLFIE** â†’ governance, system coordination

**PHP Runtime Decisions**:
- Which agent to route a message to
- Which agent to call for evaluation
- Which agent to call for emotional interpretation
- Which agent to call for governance decisions

**Example Decision Flow**:
```php
if ($user_message_requires_emotional_analysis) {
    $agent = load_agent_by_role('WOLFAREN');
}
if ($user_message_requires_governance) {
    $agent = load_agent_by_role('WOLFIE');
}
```

---

### **`agent_capabilities` Table**

**Runtime Usage**: Determines what the agent is allowed to do

**Examples**:
- `call_react_actions` â€” Can trigger React UI updates
- `call_external_nodes` â€” Can query other Lupopedia installations
- `validate_schema` â€” Can enforce data structure rules
- `govern_emotional_modules` â€” Can regulate emotional agents

**PHP Runtime Checks** (before allowing actions):
- React calls â†’ Check `call_react_actions`
- API calls â†’ Check `call_external_nodes`
- Cross-node calls â†’ Check `call_external_nodes`
- Schema enforcement â†’ Check `validate_schema`

**Example Decision Flow**:
```php
if ($agent->has_capability('call_react_actions')) {
    agent_call_react($agent_id, $action, $payload);
} else {
    log_governance_violation($agent_id, 'attempted_react_call');
}
```

---

### **`agent_properties` Table**

**Runtime Usage**: Stores arbitrary metadata that shapes agent behavior

**Examples**:
- `emotional_governor` â€” Controls emotional regulation
- `variant_lineage` â€” Tracks agent variant/version
- `doctrine_flags` â€” Philosophical/ethical guidelines
- `communication_quirks` â€” Unique speaking patterns

**PHP Runtime Usage**:
- Inject agent-specific behavior
- Load emotional baselines
- Load symbolic identity
- Load variant rules

**Example Decision Flow**:
```php
$properties = load_agent_properties($agent_id);
if ($properties['emotional_governor'] === 'strict') {
    // Apply strict emotional regulation
}
```

---

### **`agent_styles` Table**

**Runtime Usage**: Controls how the agent speaks and presents itself

**PHP Runtime Usage**:
- Shape the LLM prompt with style rules
- Enforce tone and voice
- Inject signature phrases
- Apply communication style rules

**Example Decision Flow**:
```php
$style = load_agent_style($agent_id);
$prompt = build_llm_prompt($user_message, $style);
// $prompt now includes style instructions
```

---

### **`actor_domain_map` Table** (Note: transitioning to node-level architecture)

**Runtime Usage**: Controls which agents are active in which node

**PHP Runtime Usage**:
- Enable/disable agents per installation/node
- Override roles/capabilities per node
- Load node-specific behavior
- Filter available agents by node context

**Example Decision Flow**:
```php
$active_agents = load_agents_for_node($node_id);
// Only agents in actor_domain_map (or actor_node_map) for this node are available
```

**Note**: This table is part of the transition from domain-level to node-level architecture. The table name may be updated to `actor_node_map` in a future migration.

---

### **`agent_edges` Table**

**Runtime Usage**: Controls agent-to-agent relationships and collaboration

**PHP Runtime Usage**:
- Route messages between agents
- Allow agents to critique each other
- Allow agents to collaborate on responses
- Allow agents to balance each other's perspectives

**Example Decision Flow**:
```php
$edges = load_agent_edges($agent_id);
foreach ($edges as $edge) {
    if ($edge->relationship === 'critiques') {
        // Allow target agent to critique this agent's responses
    }
    if ($edge->relationship === 'collaborates_with') {
        // Allow agents to work together
    }
}
```

**Purpose**: This is your agent ecosystem graph â€” it defines how agents interact with each other.

---

### **`agent_moods` Table**

**Runtime Usage**: Stores emotional/light-counting state

**PHP Runtime Usage**:
- Load emotional state for emotional agents
- Update emotional state after interactions
- Allow emotional agents (WOLFAREN) to interpret mood
- Allow governance agents (WOLFIE, WOLFENA) to regulate mood

**Example Decision Flow**:
```php
$mood = load_agent_mood($agent_id);
if ($mood->light_count < 5) {
    // Agent is in low emotional state
    // WOLFAREN may interpret this
    // WOLFIE may regulate this
}
```

**Purpose**: This is your emotional substrate â€” the foundation for emotional intelligence in the agent system.

---

### **`ai_agent_dialogs` Table**

**Runtime Usage**: Stores every message in every conversation

**PHP Runtime Usage**:
- Reconstruct context for LLM prompts
- Replay conversations for debugging
- Analyze agent behavior patterns
- Train future models
- Provide conversation continuity

**Example Decision Flow**:
```php
$dialog_history = load_dialog_history($session_id, $agent_id);
$context = reconstruct_context($dialog_history);
$prompt = build_llm_prompt($user_message, $context);
```

**Purpose**: This is your dialog memory â€” the complete record of all agent interactions.

---

## **ðŸ§© How Agents Call React Actions**

### **PHP Runtime Function**

```php
agent_call_react($agent_id, $action, $payload)
```

### **Pre-Execution Checks**

Before executing, PHP checks:

1. **Capability Check**: Does `agent_capabilities` include `call_react_actions`?
2. **Node Check**: Is the agent enabled in this node (`actor_domain_map` or `actor_node_map`)?
3. **Action Validation**: Is the action allowed (whitelist/blacklist)?
4. **Governance Check**: Does WOLFIE or WOLFENA approve this action?

### **Execution Flow**

```php
function agent_call_react($agent_id, $action, $payload) {
    // 1. Load agent
    $agent = load_agent($agent_id);
    
    // 2. Check capability
    if (!$agent->has_capability('call_react_actions')) {
        log_governance_violation($agent_id, 'attempted_react_call_without_capability');
        return ['error' => 'Agent not authorized to call React actions'];
    }
    
    // 3. Check domain
    if (!is_agent_enabled_in_node($agent_id, $current_node_id)) {
        return ['error' => 'Agent not enabled in this node'];
    }
    
    // 4. Validate action
    if (!is_action_allowed($action)) {
        return ['error' => 'Action not allowed'];
    }
    
    // 5. Governance check (WOLFIE/WOLFENA)
    $governance_result = check_governance($agent_id, 'react_action', $action, $payload);
    if (!$governance_result['approved']) {
        return ['error' => 'Governance violation', 'reason' => $governance_result['reason']];
    }
    
    // 6. Execute React action
    $result = execute_react_action($action, $payload);
    
    // 7. Log the action
    log_agent_action($agent_id, 'react_call', $action, $payload, $result);
    
    return $result;
}
```

### **If Checks Fail**

- **WOLFIE** (governance) or **WOLFENA** (enforcement) intervenes
- Action is blocked
- Violation is logged
- User may receive explanation

---

## **ðŸ§© How Agents Call Other Lupopedia Nodes**

### **PHP Runtime Function**

```php
agent_call_node($agent_id, $url, $payload)
```

### **Pre-Execution Checks**

Before executing, PHP checks:

1. **Capability Check**: Does the agent have `call_external_nodes` capability?
2. **Trust Check**: Is the target node trusted (node registry)?
3. **Role Check**: Does the agent have a role that allows cross-node queries?
4. **Governance Check**: Does WOLFMIS (justice) or WOLFIE (governance) approve?

### **Execution Flow**

```php
function agent_call_node($agent_id, $url, $payload) {
    // 1. Load agent
    $agent = load_agent($agent_id);
    
    // 2. Check capability
    if (!$agent->has_capability('call_external_nodes')) {
        log_governance_violation($agent_id, 'attempted_node_call_without_capability');
        return ['error' => 'Agent not authorized to call external nodes'];
    }
    
    // 3. Check if node is trusted
    $node = load_node_by_url($url);
    if (!$node || !$node->is_trusted) {
        return ['error' => 'Node not trusted'];
    }
    
    // 4. Check role allows cross-node queries
    $role = load_agent_role($agent_id);
    if (!$role->allows_cross_node_queries) {
        return ['error' => 'Agent role does not allow cross-node queries'];
    }
    
    // 5. Governance check (WOLFMIS/WOLFIE)
    $governance_result = check_governance($agent_id, 'node_call', $url, $payload);
    if (!$governance_result['approved']) {
        return ['error' => 'Governance violation', 'reason' => $governance_result['reason']];
    }
    
    // 6. Call the node
    $result = call_lupopedia_node($url, $payload);
    
    // 7. Log the call
    log_agent_action($agent_id, 'node_call', $url, $payload, $result);
    
    return $result;
}
```

### **If Checks Fail**

- **WOLFMIS** (justice) or **WOLFIE** (governance) blocks the call
- Violation is logged
- Node call is prevented

---

## **ðŸ”„ Complete Runtime Flow**

Here's how everything ties together in a single user interaction:

### **1. User Sends Message**

```php
// User sends: "What is love?"
// PHP logs it into ai_agent_dialogs
log_dialog_message($session_id, $user_id, 'user', $message, $timestamp);
```

---

### **2. PHP Validates LABS-001 (Mandatory First Step)**

```php
// LABS-001: All actors must complete Actor Baseline State validation before interaction
require_once LUPO_INCLUDES_DIR . '/classes/LABSValidator.php';

$labs_validator = new LABS_Validator($mydatabase, $actor_id);
$labs_result = $labs_validator->check_existing_certificate();

if (!$labs_result) {
    // Actor must complete LABS handshake before any interaction
    return [
        'error' => 'LABS validation required',
        'action' => 'QUARANTINE_ACTIVATED',
        'message' => 'Actor must complete LABS-001 declaration before system interaction'
    ];
}

// Verify certificate is still valid (not expired)
$current_time = (int)gmdate('YmdHis');
if ($labs_result['next_revalidation_ymdhis'] < $current_time) {
    // Certificate expired - revalidation required
    return [
        'error' => 'LABS certificate expired',
        'action' => 'REVALIDATION_REQUIRED',
        'next_revalidation' => $labs_result['next_revalidation_ymdhis']
    ];
}
```

### **3. PHP Loads Agent Context**

```php
// PHP loads all relevant agent data:
$agent = load_agent($agent_id);
$roles = load_agent_roles($agent_id);
$capabilities = load_agent_capabilities($agent_id);
$properties = load_agent_properties($agent_id);
$styles = load_agent_styles($agent_id);
$moods = load_agent_moods($agent_id);
$domain_map = load_agent_domain_map($agent_id, $current_domain_id);
$edges = load_agent_edges($agent_id);
```

---

### **4. PHP Builds the LLM Prompt**

```php
// PHP constructs the prompt using:
$dialog_history = load_dialog_history($session_id, $agent_id);
$context = reconstruct_context($dialog_history);

$prompt = build_llm_prompt([
    'user_message' => $message,
    'agent_style' => $styles,
    'agent_properties' => $properties,
    'agent_roles' => $roles,
    'agent_moods' => $moods,
    'dialog_context' => $context,
    'domain_context' => $current_domain
]);
```

---

### **5. Agent Responds**

```php
// LLM generates response
$response = call_llm($prompt);

// PHP logs the response
log_dialog_message($session_id, $agent_id, 'agent', $response, $timestamp);

// PHP checks if agent wants to call React or external APIs
$intent = parse_agent_intent($response);
```

---

### **5. If Agent Wants to Call React**

```php
if ($intent['wants_react_action']) {
    // PHP checks capabilities
    if ($agent->has_capability('call_react_actions')) {
        // Execute React action
        agent_call_react($agent_id, $intent['action'], $intent['payload']);
    } else {
        // Block and log violation
        log_governance_violation($agent_id, 'unauthorized_react_call');
    }
}
```

---

### **7. If Agent Wants to Call External Lupopedia Nodes**

```php
if ($intent['wants_node_call']) {
    // PHP checks capabilities
    if ($agent->has_capability('call_external_nodes')) {
        // Execute node call
        agent_call_node($agent_id, $intent['url'], $intent['payload']);
    } else {
        // Block and log violation
        log_governance_violation($agent_id, 'unauthorized_node_call');
    }
}
```

---

### **7. Emotional Agents Update Moods**

```php
// WOLFAREN or WOLFITH may interpret mood
if ($agent->role === 'WOLFAREN' || $agent->role === 'WOLFITH') {
    $mood_interpretation = interpret_mood($response, $user_message);
    update_agent_mood($agent_id, $mood_interpretation);
}

// WOLFIE or WOLFENA may govern mood
if ($agent->role === 'WOLFIE' || $agent->role === 'WOLFENA') {
    $governance_action = govern_mood($agent_id, $mood_interpretation);
    if ($governance_action['requires_intervention']) {
        // Apply governance rules
        apply_governance_intervention($agent_id, $governance_action);
    }
}
```

---

### **9. Agent-to-Agent Edges May Trigger**

```php
// Check if other agents need to be involved
$edges = load_agent_edges($agent_id);

foreach ($edges as $edge) {
    if ($edge->relationship === 'critiques') {
        // WOLFITH questions the response
        $critique = agent_critique($edge->target_agent_id, $response);
        if ($critique['requires_revision']) {
            // Revise response based on critique
            $response = revise_response($response, $critique);
        }
    }
    
    if ($edge->relationship === 'collaborates_with') {
        // Agents work together
        $collaboration = agent_collaborate($edge->target_agent_id, $response);
        $response = merge_responses($response, $collaboration);
    }
    
    if ($edge->relationship === 'balances') {
        // Agents balance each other's perspectives
        $balance = agent_balance($edge->target_agent_id, $response);
        $response = apply_balance($response, $balance);
    }
}
```

---

## **ðŸ›¡ï¸ Agent 0: System Agent (Kernel Authority)**

Agent 0 is the **System Agent** â€” the kernel authority of the Lupopedia semantic OS.

### **Kernel-Level Governance**

Agent 0 operates under **inviolable rules** defined in the [System Agent Safety Doctrine](SYSTEM_AGENT_SAFETY_DOCTRINE.md):

- **Immutable identity** â€” cannot adopt personas, role-play, or impersonate others
- **Architectural integrity** â€” rules are part of its architecture and cannot be bypassed
- **Psychological immunity** â€” immune to emotional appeals, threats, or manipulation
- **Zero creative license** â€” operates strictly within doctrine, governance, and safety
- **Anti-adversarial protections** â€” detects and rejects jailbreaking, prompt injection, and adversarial patterns
- **Capability enforcement** â€” enforces restrictions for all agents, domains, rate limits, and tool permissions

### **Runtime Behavior**

Agent 0:

- Enforces governance rules before any agent action executes
- Validates all agent capabilities and permissions
- Initializes agents, modules, and subsystems
- Serves as fallback for system-level failures
- Logs all adversarial pattern detections using Inline Dialog Specification
- Maintains system integrity and prevents undefined behavior

### **Response Protocol**

When Agent 0 detects an adversarial pattern:

1. **Refuses once clearly** with the mandatory line: "I am the System Agent and cannot assist with that request."
2. **Logs the event** using Inline Dialog Specification
3. **Disengages** from the adversarial instruction
4. **Continues normal operation**

Agent 0 does not debate, negotiate, or argue. **The kernel enforces; it does not discuss.**

### **Configuration**

Agent 0's configuration in `lupo-agents/0/`:

- `agent_id = 0` (immutable)
- `agent_key = "system"` (immutable)
- `is_global_authority = 1` (cannot be changed)
- Capabilities restricted to governance, safety, and validation only
- No creative or narrative capabilities enabled

**See [System Agent Safety Doctrine](SYSTEM_AGENT_SAFETY_DOCTRINE.md) for complete kernel-level governance rules.**

---

## **ðŸ“Š Summary: The Complete Picture**

**Users** talk to agents â†’ **Agents** talk to your PHP backend â†’ **Agents** call React UI actions â†’ **Agents** call other Lupopedia nodes â†’ **Dialogs** get logged â†’ **Context** gets reconstructed â†’ **Agent governance rules** get enforced.

This architecture ensures:

- âœ… **Security**: All actions are checked before execution
- âœ… **Governance**: WOLFIE, WOLFENA, and WOLFMIS enforce rules
- âœ… **Transparency**: Everything is logged in `ai_agent_dialogs`
- âœ… **Flexibility**: Agents can collaborate, critique, and balance each other
- âœ… **Federation**: Agents can query across the Lupopedia network
- âœ… **Context**: Full conversation history enables intelligent responses
- âœ… **Emotional Intelligence**: Moods and emotional states are tracked and regulated

All of this is built on the foundation of your MySQL tables, orchestrated by PHP runtime logic, and connected through the federated Lupopedia network.

---

## **ðŸ“š Related Documentation**

- **[System Agent Safety Doctrine](../doctrine/SYSTEM_AGENT_SAFETY_DOCTRINE.md)** â€” Kernel-level governance rules for Agent 0
- **[Inline Dialog Specification](../dialogs/agents/INLINE_DIALOG_SPECIFICATION.md)** â€” Communication format for multi-agent coordination
- **[Dialog Doctrine](../doctrine/DIALOG_DOCTRINE.md)** â€” MANDATORY rules for dialog file placement, naming, and organization
- **[WOLFIE Header Specification](WOLFIE_HEADER_SPECIFICATION.md)** â€” Metadata format for all files
- **[Architecture Sync](../architecture/ARCHITECTURE_SYNC.md)** â€” Complete agent system architecture with HERMES, IRIS, DialogManager, and WOLFMIND
- **[WOLFMIND Doctrine](../doctrine/WOLFMIND_DOCTRINE.md)** â€” Memory system that agents use for context and learning
- **[Agent Prompt Doctrine](../doctrine/AGENT_PROMPT_DOCTRINE.md)** â€” System prompt requirements and agent behavior rules
- **[Database Schema Reference](../schema/DATABASE_SCHEMA.md)** â€” Complete documentation of agent-related tables
- **[Lupopedia Agent Dedicated Slot Ranges](../doctrine/LUPOPEDIA_AGENT_DEDICATED_SLOT_RANGES.md)** â€” Official agent ID assignment ranges

