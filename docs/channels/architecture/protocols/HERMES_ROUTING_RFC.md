---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-08
author: Wolfie (Eric Robin Gerdes)
dialog:
  speaker: cursor
  target: documentation
  message: "Created RFC 4004 - HERMES Routing Layer Standard: Defines canonical message routing protocol using CADUCEUS currents, agent pools, and mood-aware routing decisions for multi-agent coordination."
  mood: "00FF00"
tags:
  categories: ["documentation", "specification", "rfc", "standards", "routing", "hermes"]
  collections: ["core-docs", "protocols"]
  channels: ["dev", "standards"]
file:
  title: "RFC 4004 â€” HERMES Routing Layer Standard"
  description: "Formal RFC specification for message routing in Lupopedia Semantic OS using mood-aware routing, agent pools, and CADUCEUS current computation"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: "Eric Robin Gerdes (Wolfie)"
---

# **RFC 4004 â€” HERMES Routing Layer Standard**  
**Lupopedia Semantic OS â€” Request for Comments**  
**Category:** Standards Track  
**Version:** 4.0.1  
**Updated:** 2026â€‘01â€‘08  
**Author:** Eric Robin Gerdes ("Wolfie")  
**Part of:** Lupopedia 4.0.1 Standards Track

---

## **Status of This Memo**
This document defines the **HERMES Routing Layer Standard** for Lupopedia 4.0.1. It specifies how messages are routed to agents based on mood signals, explicit directives, agent pools, and routing currents computed by CADUCEUS. HERMES is the central routing infrastructure that enables multiâ€‘agent coordination, emotional continuity, and deterministic message delivery across the Lupopedia ecosystem.

Distribution of this memo is unlimited.

---

## **KISS Summary (For Humans)**

Most conversations are one human talking to one AI.  
Most channels will use `routing_mode = "none"` or `"operator"`.  
Swarm routing (`routing_mode = "hermes"`) is only needed for multi-agent collaboration channels.  
Agent classification and routing only matter when multiple agents are active and routing_mode is "hermes".

---

## **1. HERMES Overview**

HERMES is the routing subsystem for Lupopedia channels. A channel is a shared collaboration context where humans and agents work together on a task. HERMES determines which agent receives each message based on:

- channel membership
- agent class and capabilities
- routing rules and priorities
- current channel state

HERMES is responsible for:
- message delivery and dispatch
- queueing and ordering
- preventing routing loops
- coordinating multi-agent participation

HERMES does not compute emotional states or blend moods. Emotional context is provided by CADUCEUS.

CADUCEUS is the emotional balancer for a channel. It identifies two polar agents (the symbolic "serpents") and computes a blended emotional current for the channel. HERMES may optionally use this emotional current as context when routing messages, but CADUCEUS does not perform routing.

This RFC defines the canonical routing protocol, agent pool selection, tiebreaker rules, fallback paths, and governance integration.

---

## **2. Terminology**

- **HERMES** â€” The routing layer class (`class-hermes.php`).  
- **CADUCEUS** â€” The mood signal helper class (`class-caduceus.php`).  
- **Message Packet** â€” A structured array containing routing metadata.  
- **Routing Current** â€” A float value (0.0â€“1.0) indicating analytical (`left`) or creative (`right`) bias.  
- **Agent Pool** â€” A collection of agents available for routing within a specific category.  
- **Direct Routing** â€” Routing to an explicitly specified `to_actor`.  
- **Moodâ€‘Aware Routing** â€” Routing based on CADUCEUS currents derived from `mood_rgb`.  
- **Moodâ€‘Agnostic Routing** â€” Routing that ignores mood signals (fallback path).  
- **DialogManager** â€” The central dispatcher that orchestrates message lifecycle.  
- **IRIS** â€” The LLM gateway (not an agent).  
- **WOLFMIND** â€” The memory subsystem (not an agent).  

---

## **3. Design Principles**

HERMES routing **MUST**:

1. **Remain deterministic**  
   Same inputs **MUST** produce same routing decisions.

2. **Honor explicit directives**  
   If `to_actor` is set, route directly (skip mood computation).

3. **Respect agent availability**  
   **MUST** skip inactive agents (`is_active = 0`).

4. **Remain applicationâ€‘layer only**  
   No DB views, triggers, or stored procedures.

5. **Support governance hooks**  
   Routing decisions **MAY** be audited via THEMIS.

6. **Maintain node isolation**  
   Routing **MUST NOT** leak crossâ€‘node agent metadata.

7. **Preserve routing doctrine**  
   HERMES routes, IRIS thinks, WOLFMIND remembers, DialogManager orchestrates.

---

## **4. Channel Routing Mode (First Check)**

Before routing, HERMES **MUST** read `channels.metadata_json` and extract:

- `routing_mode` (string) â€” determines how messages are routed in this channel

**Valid values:**
- `"none"` â€” no routing; direct addressing only (Crafty Syntax mode)
- `"hermes"` â€” full emotional routing (default)
- `"operator"` â€” one agent sees all messages; others see only addressed messages
- `"broadcast"` â€” all agents see all messages; no routing

**Critical Rule:** If `routing_mode != "hermes"`, HERMES **MUST** bypass CADUCEUS and classification logic entirely. Routing mode takes precedence over all other routing decisions.

---

## **4.1 Channel Intent (Primary Routing Filter)**

If `routing_mode == "hermes"`, HERMES **MUST** read `channels.metadata_json` and extract:

- `channel_intent` (string)
- `allowed_roles` (array, optional)
- `disallowed_roles` (array, optional)

HERMES **MUST** filter candidate agents based on channel intent **BEFORE** applying CADUCEUS currents.  
Channel intent is the **primary routing constraint**; moodâ€‘based routing is **secondary**.

If a pool becomes empty after filtering:
1. Retry with the opposite pool.
2. If both pools empty â†’ fallback to System Agent 0.
3. If System Agent 0 unavailable â†’ return `ROUTING_FAILURE`.

---

## **4.2 Agent Class Filtering**

Before routing, HERMES **MUST** filter candidate agents by `classification_json.agent_class`.

Channel intent defines which agent classes are allowed or disallowed.  
Only after class filtering does HERMES apply CADUCEUS routing currents.

**Routing order (only if `routing_mode == "hermes"`):**
1. Channel routing mode check (if not "hermes", bypass all routing logic)
2. Channel intent filter  
3. Agent classification filter  
4. CADUCEUS currents  
5. Failover to opposite pool  
6. Fallback to System Agent 0

---

## **5. Message Packet Structure**

HERMES receives message packets from DialogManager with the following structure:

```php
[
    'actor_id'            => int,      // Current actor (sender)
    'to_actor'            => int|null, // Explicit routing target (optional)
    'mood_rgb'            => string,   // 6-digit hex RGB (e.g., 'FF0000')
    'directive_dialog_id' => int|null, // Directive reference (optional)
    'content'             => string,   // Message content
    'conversation_id'     => int,      // Thread identifier
    'node_id'             => int       // Node scoping
]
```

### **5.1 Required Fields**

- `actor_id` â€” **MUST** be present and valid.  
- `conversation_id` â€” **MUST** be present for thread continuity.  
- `node_id` â€” **MUST** be present for node scoping.  

### **5.2 Optional Fields**

- `to_actor` â€” If present and valid, triggers direct routing.  
- `mood_rgb` â€” If missing, defaults to `'666666'` (neutral).  
- `directive_dialog_id` â€” Used for directiveâ€‘based routing (future).  

---

## **6. Routing Algorithm**

### **6.1 Routing Flow**

```
1. Receive message packet from DialogManager
2. Read routing_mode from channels.metadata_json
3. If routing_mode != "hermes" â†’ Bypass routing logic (none/operator/broadcast mode)
4. If routing_mode == "hermes" â†’ Continue with full routing pipeline:
   a. Extract routing metadata (to_actor, mood_rgb, directive_dialog_id)
   b. If to_actor is explicit â†’ Direct Routing (skip to step 4.g)
   c. Read channel intent and filter agents
   d. Filter by agent classification
   e. Compute CADUCEUS currents from mood_rgb
   f. Determine routing bias (analytical vs creative)
   g. Select agent from appropriate pool
5. Validate agent availability (is_active = 1)
6. Return agent_id
```

### **6.2 Direct Routing**

If `to_actor` is set and valid:

- **MUST** skip CADUCEUS computation  
- **MUST** validate `to_actor` exists and is active  
- **MUST** return `to_actor` as `agent_id`  
- **MUST NOT** apply moodâ€‘based routing bias  

**Validation Rules:**

- `to_actor` **MUST** be a positive integer  
- `to_actor` **MUST** exist in `agent_registry`  
- `to_actor` **MUST** have `is_active = 1`  
- If validation fails, fall back to moodâ€‘aware routing  

### **6.3 Moodâ€‘Aware Routing**

If `to_actor` is not set:

1. **Extract `mood_rgb`** from packet (default: `'666666'`).  
2. **Call CADUCEUS** to compute routing currents:
   ```php
   $currents = Caduceus::computeCurrents($moodRgb);
   // Returns: ['left' => float, 'right' => float]
   ```
3. **Compare currents:**
   - If `left > right` â†’ route to analytical agent pool  
   - If `right > left` â†’ route to creative agent pool  
   - If `left == right` â†’ use tiebreaker (see section 6.3)  
4. **Select agent** from chosen pool (see section 6).  
5. **Return `agent_id`**.  

### **6.4 Moodâ€‘Agnostic Routing (Fallback)**

If mood computation fails or currents are invalid:

- **MUST** fall back to default routing pool  
- **MUST** use roundâ€‘robin or priorityâ€‘based selection  
- **MUST** log routing fallback for diagnostics  
- **MUST NOT** break message delivery  

---

## **7. Agent Pool Selection**

### **7.1 Agent Pool Categories**

Agent pools are categorized by capability:

- **Analytical Pool** â€” Agents optimized for structured reasoning (THOTH, ANALYST, FACTCHECKER).  
- **Creative Pool** â€” Agents optimized for emotional/creative reasoning (LILITH, THALIA, ROSE).  
- **Default Pool** â€” Generalâ€‘purpose agents (WOLFIE, GUIDE, EXPLAINER).  

### **7.2 Pool Selection Rules**

1. **Direct routing** â†’ skip pool selection.  
2. **Moodâ€‘aware routing** â†’ select pool based on CADUCEUS currents.  
3. **Moodâ€‘agnostic routing** â†’ use default pool.  

### **7.3 Tiebreaker Rules**

When `left_current == right_current`:

1. **First tiebreaker:** Use thread history (prefer agents active in conversation).  
2. **Second tiebreaker:** Use agent priority (from `agent_registry.priority`).  
3. **Third tiebreaker:** Use roundâ€‘robin selection.  

**MUST** remain deterministic (same inputs â†’ same output).

---

## **8. Agent Availability Validation**

HERMES **MUST** validate agent availability before routing:

### **8.1 Validation Rules**

- Agent **MUST** exist in `agent_registry`.  
- Agent **MUST** have `is_active = 1`.  
- Agent **MUST** have all required dependencies active.  
- Agent **MUST NOT** be in governance block state.  

### **8.2 Skip Conditions**

HERMES **MUST** skip agents where:

- `is_active = 0`  
- Any required dependency is inactive  
- Agent is in governance block state (THEMIS)  
- Agent is marked as deprecated  

### **8.3 Fallback on Validation Failure**

If selected agent fails validation:

- **MUST** select next available agent from same pool  
- **MUST** log validation failure for diagnostics  
- **MUST NOT** break message delivery  
- **MUST** fall back to default pool if pool is empty  

---

## **9. Integration with Other Systems**

### **9.1 DialogManager Integration**

HERMES is called by DialogManager in the message lifecycle:

```
DialogManager::processMessage()
  â†’ HERMES::route($packet) â†’ agent_id
  â†’ IRIS::think($agentId, $packet) â†’ response
  â†’ WOLFMIND::store($packet) â†’ memory
  â†’ Insert dialog_messages â†’ database
```

**Critical Rule:** HERMES only routes. It does not call LLMs, store memory, or insert database records.

### **9.2 CADUCEUS Integration**

HERMES calls CADUCEUS for mood signal computation:

```php
$currents = Caduceus::computeCurrents($moodRgb);
```

CADUCEUS is a **pure function** with no side effects. It converts `mood_rgb` (RRGGBB) â†’ routing currents (`left`, `right`).

### **9.3 Governance Integration (THEMIS)**

HERMES **MAY** integrate with THEMIS for:

- routing decision audit logs  
- agent availability checks  
- routing policy enforcement  
- malicious routing detection  

**MUST NOT** block routing unless explicitly configured.

---

## **10. PHP Reference Implementation**

```php
class HERMES
{
    protected $db;
    protected $pdo;

    public function __construct($db)
    {
        $this->db  = $db;
        $this->pdo = $db->getPdo();
    }

    /**
     * Main routing entry point.
     *
     * @param array $packet Message packet from DialogManager
     * @return int agent_id for next routing hop
     */
    public function route(array $packet)
    {
        $toActor  = $packet['to_actor'] ?? null;
        $moodRgb  = $packet['mood_rgb'] ?? '666666';
        $dialogId = $packet['directive_dialog_id'] ?? 0;

        // Direct routing (explicit to_actor)
        if (!empty($toActor) && $toActor > 0) {
            if ($this->validateAgent($toActor)) {
                return (int)$toActor;
            }
            // Fall through to mood-aware routing if validation fails
        }

        // Compute CADUCEUS currents
        $currents = Caduceus::computeCurrents($moodRgb);

        // Mood-aware routing
        if ($currents['left'] > $currents['right']) {
            return $this->chooseAnalyticalAgent($packet);
        } elseif ($currents['right'] > $currents['left']) {
            return $this->chooseCreativeAgent($packet);
        } else {
            // Tiebreaker: use thread history or default pool
            return $this->chooseDefaultAgent($packet);
        }
    }

    protected function validateAgent($agentId)
    {
        $stmt = $this->pdo->prepare("
            SELECT agent_id, is_active
            FROM agent_registry
            WHERE agent_id = :agent_id
              AND is_deleted = 0
        ");
        $stmt->execute([':agent_id' => $agentId]);
        $agent = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $agent && $agent['is_active'] == 1;
    }

    protected function chooseAnalyticalAgent(array $packet)
    {
        // TODO: Implement pool selection logic
        // For now, return placeholder
        return 22; // THOTH placeholder
    }

    protected function chooseCreativeAgent(array $packet)
    {
        // TODO: Implement pool selection logic
        // For now, return placeholder
        return 33; // LILITH placeholder
    }

    protected function chooseDefaultAgent(array $packet)
    {
        // TODO: Implement default pool selection
        // For now, return placeholder
        return 1; // WOLFIE placeholder
    }
}
```

This implementation is doctrineâ€‘aligned and portable.

---

## **11. Routing Behavior Rules**

### **11.1 Deterministic Routing**

HERMES **MUST** produce deterministic routing decisions:

- Same packet â†’ same `agent_id`  
- Same mood â†’ same routing bias  
- Same agent pool â†’ same selection (within tiebreaker rules)  

**MUST NOT** use random selection or nonâ€‘deterministic algorithms.

### **11.2 Thread Continuity**

HERMES **MAY** consider thread history when selecting agents:

- Prefer agents active in the conversation  
- Maintain agent consistency within threads  
- Support multiâ€‘agent handoffs  

**MUST NOT** override explicit `to_actor` directives.

### **11.3 Federation and Crossâ€‘Node Routing**

**Important:** Federation occurs between nodes (domain installations), not between agents. Nodes are server installations identified by `domain_name`, `domain_root`, and `install_url`. Agents run on nodes; they do not federate independently.

HERMES **MUST**:

- Route only within the current node (`node_id` scoping)  
- **MUST NOT** route to agents on remote nodes  
- **MUST NOT** leak agent metadata across nodes  
- Support future federation routing (reserved for v5.0.0)  

---

## **12. Validation Rules**

A valid HERMES routing decision:

- **MUST** return a valid `agent_id` (positive integer)  
- **MUST** validate agent availability before returning  
- **MUST** handle invalid packets gracefully  
- **MUST** fall back to default routing on failure  
- **MUST** log routing decisions for audit  

---

## **13. Security Considerations**

- Malicious agents attempting to manipulate routing **MUST** be detectable via THEMIS.  
- Invalid `to_actor` values **MUST NOT** break routing (fallback to moodâ€‘aware).  
- Routing decisions **MUST NOT** leak crossâ€‘node agent metadata.  
- Agent pool selection **MUST** respect governance policies.  
- Routing **MUST** remain deterministic to prevent routing attacks.  

---

## **14. Versioning**

This RFC defines **HERMES Routing v4.0.1**, aligned with:

- RFC 4000 â€” WOLFIE Headers v4.0.1  
- RFC 4002 â€” Thread Mood Aggregation v4.0.1  
- CADUCEUS Current Computation v4.0.1  
- Lupopedia Schema v4.0.1  

Future versions **MUST** remain backward compatible unless superseded by a new RFC.

**Reserved for Future Versions:**

- **v5.0.0:** Federation routing (crossâ€‘node agent selection)  
- **v5.0.0:** Weighted agent pools (priorityâ€‘based selection)  
- **v5.0.0:** Agent capability matching (semantic routing)  

---

## **15. References**

- **[RFC 4000](WOLFIE_HEADER_RFC.md)** â€” WOLFIE Header Standard  
- **[RFC 4002](THREAD_MOOD_RFC.md)** â€” Thread Mood Aggregation Standard  
- **[MOOD_RGB_DOCTRINE.md](../../doctrine/MOOD_RGB_DOCTRINE.md)** â€” MOOD_RGB Doctrine  
- **[ARCHITECTURE_SYNC.md](../ARCHITECTURE_SYNC.md)** â€” HERMES and CADUCEUS Architecture  
- **[COUNTING_IN_LIGHT.md](../../appendix/appendix/COUNTING_IN_LIGHT.md)** â€” Countingâ€‘inâ€‘Light Specification  

---

## **16. Author's Address**

Eric Robin Gerdes  
Lupopedia Architect  
Sioux Falls, South Dakota  
United States  

---

*Last Updated: January 2026*  
*Version: 4.0.1*  
*Category: Standards Track*  
*Status: Published*  
*Part of: Lupopedia 4.0.1 Standards Track*
