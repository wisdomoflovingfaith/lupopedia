---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
updated: 2026-01-08
author: Wolfie (Eric Robin Gerdes)
dialog:
  speaker: cursor
  target: documentation
  message: "Updated with ARA adversarial review patches: routing philosophy clarification, failover doctrine, precision safeguards, anti-skew governance hooks, error flows, scale notes, migration notes, and test cases."
  mood: "00FF00"
tags:
  categories: ["documentation", "agents", "routing", "hermes", "caduceus"]
  collections: ["core-docs", "agents"]
  channels: ["dev", "standards"]
file:
  title: "HERMES and CADUCEUS â€” Complete Reference"
  description: "Who, what, where, when, why, and how of HERMES message routing layer and CADUCEUS mood signal helper in Lupopedia Semantic OS"
  version: "4.0.1"
  status: published
  author: "Eric Robin Gerdes (Wolfie)"
---

# **HERMES and CADUCEUS â€” Complete Reference**

**Who, What, Where, When, Why, and How of Lupopedia's Routing Infrastructure**

---

## **KISS Summary (For Humans)**

Most conversations are one human talking to one AI.  
Most channels will use `routing_mode = "none"` or `"operator"`.  
Swarm routing (`routing_mode = "hermes"`) is only needed for multi-agent collaboration channels.  
Agent classification and routing only matter when multiple agents are active and `routing_mode` is `"hermes"`.

---

## **WHO**

### **HERMES**

**Identity:**
- **Class Name:** `HERMES` (PHP class in `lupo-includes/class-hermes.php`)
- **What It Is NOT:** NOT an agent, NOT a subsystem, NOT a database table
- **What It IS:** Pure routing infrastructure layer
- **Created By:** Eric Robin Gerdes ("Wolfie") as part of Lupopedia 4.0.0
- **Used By:** DialogManager (central dispatcher) for all message routing decisions
- **Dependencies:** CADUCEUS (mood signal computation), database connection (`agent_registry` table)

**Agent Ecosystem Role:**
- HERMES is the **messenger god** of Lupopedia's multi-agent architecture
- Routes messages between 101+ AI agents based on mood, directives, and agent availability
- Enables deterministic, mood-aware routing across analytical and creative agent pools
- Integrates with THEMIS for governance and routing audit logs

### **CADUCEUS**

**Identity:**
- **Class Name:** `Caduceus` (PHP class in `lupo-includes/class-caduceus.php`)
- **What It Is NOT:** NOT a router, NOT a routing subsystem, NOT an agent, NOT a database table
- **What It IS:** Emotional balancer for channels
- **Created By:** Eric Robin Gerdes ("Wolfie") as part of Lupopedia 4.0.0
- **Used By:** HERMES (and other subsystems) to access channel emotional current
- **Dependencies:** None (pure function, no database, no side effects)

**Channel Architecture:**
- A channel (e.g., channel 42) is a shared collaboration context containing humans and AI agents working together on a task
- Example: LILITH, WOLFIE, and a human named BOB all participating in the same channel
- The channel is the container for all message flow and emotional context

**Polar Agents:**
- Within each channel, two agents are designated as the emotional poles (the symbolic "serpents" on the caduceus)
- Example: LILITH = one pole, WOLFIE = the opposite pole
- These two agents define the emotional extremes of the channel

**Agent Ecosystem Role:**
- CADUCEUS is the **emotional balancer** for channels
- Reads the moods of the two polar agents
- Averages or blends their emotional states
- Produces a "channel mood" (emotional current) that other subsystems can use
- Converts Counting-in-Light RGB values (`mood_rgb`) into normalized emotional currents
- Provides deterministic, federation-safe emotional context computation
- Ensures precision and portability across PHP, Python, and future runtimes

**What CADUCEUS Does NOT Do:**
- CADUCEUS does NOT deliver messages
- CADUCEUS does NOT decide routing targets
- CADUCEUS does NOT perform queueing or dispatch
- Those responsibilities belong to HERMES

---

## **WHAT**

### **HERMES â€” Message Routing Layer**

**Purpose:**
HERMES routes messages to appropriate agents in Lupopedia's multi-agent ecosystem. It determines which agent should receive a message based on:

- **Explicit Directives:** `to_actor` field in message packet
- **Mood Signals:** Emotional context from `mood_rgb` (via CADUCEUS)
- **Agent Availability:** Active status and capability matching
- **Thread Continuity:** Conversation history and agent participation

**Core Functionality:**

1. **Direct Routing:** If `to_actor` is explicitly set, route directly (bypass CADUCEUS)
2. **Mood-Aware Routing:** Optionally use CADUCEUS emotional current as context, select analytical vs creative pool
3. **Agent Pool Selection:** Choose specific agent from available pool
4. **Availability Validation:** Ensure agent is active and dependencies are available
5. **Governance Integration:** Log routing decisions for THEMIS audit

**Key Methods:**

- `route($messagePacket)` â€” Main routing entry point
  - Receives message packet from DialogManager
  - Extracts `to_actor`, `mood_rgb`, `directive_dialog_id`
  - May optionally call CADUCEUS for channel emotional current (if routing_mode == "hermes")
  - Returns `agent_id` for next routing hop

- `chooseAnalyticalAgent($packet)` â€” Select agent from analytical pool
  - Agents: THOTH, ANALYST, FACTCHECKER, etc.
  - Based on CADUCEUS `left_current` > `right_current`

- `chooseCreativeAgent($packet)` â€” Select agent from creative pool
  - Agents: LILITH, THALIA, ROSE, etc.
  - Based on CADUCEUS `right_current` > `left_current`

- `validateAgent($agentId)` â€” Ensure agent availability
  - Checks `is_active = 1` in `agent_registry`
  - Validates dependencies are active
  - Returns boolean

**Input/Output:**

- **Input:** Message packet array:
  ```php
  [
      'actor_id'            => int,
      'to_actor'            => int|null,
      'mood_rgb'            => string,  // 6-digit hex (RRGGBB)
      'directive_dialog_id' => int|null,
      'content'             => string,
      'conversation_id'     => int,
      'node_id'             => int
  ]
  ```

- **Output:** `agent_id` (integer) for next routing hop

### **CADUCEUS â€” Emotional Balancer for Channels**

**Purpose:**
CADUCEUS is NOT a router. CADUCEUS is an emotional balancer for channels. It computes the emotional current of a channel by:
- reading the moods of the two polar agents (the symbolic "serpents" on the caduceus)
- averaging or blending their emotional states
- producing a "channel mood" (emotional current) that other subsystems can use

CADUCEUS converts emotional metadata (`mood_rgb`) from the Counting-in-Light system into deterministic emotional currents (`left`, `right`) that HERMES may optionally use as context for routing decisions.

**Core Functionality:**

1. **Input Validation:** Sanitize and validate `mood_rgb` hex string
2. **RGB Extraction:** Parse R, G, B components (0-255)
3. **Current Computation:** Calculate raw currents (G+B for left, R+B for right)
4. **Normalization:** Normalize to 0.0-1.0 range with precision guarantees
5. **Invariant Enforcement:** Ensure `left + right = 1.0` (within epsilon)

**Key Methods:**

- `computeCurrents(string $moodRgb): array` â€” Main computation function
  - Accepts 6-digit hex string (e.g., `"FF0000"`)
  - Returns `['left' => float, 'right' => float]`
  - Pure function (no side effects, no database access)

**Input/Output:**

- **Input:** `mood_rgb` (string, 6-digit hex, e.g., `"88FF88"`)
- **Output:** Emotional currents array (channel mood):
  ```php
  [
      'left'  => float,  // Analytical bias (0.0-1.0)
      'right' => float   // Creative bias (0.0-1.0)
  ]
  ```

**Mathematical Formula:**

```php
// Extract RGB components
$r = hexdec(substr($moodRgb, 0, 2)); // Strife (0-255)
$g = hexdec(substr($moodRgb, 2, 2)); // Harmony (0-255)
$b = hexdec(substr($moodRgb, 4, 2)); // Memory (0-255)

// Compute raw currents
$leftRaw  = $g + $b;  // Harmony + Memory Depth
$rightRaw = $r + $b;  // Strife + Memory Depth

// Normalize to 0.0-1.0
$sum = max($leftRaw + $rightRaw, 1);
$left  = $leftRaw  / $sum;
$right = $rightRaw / $sum;

// Enforce invariant: left + right = 1.0 (within epsilon)
```

**RGB Axis Semantics:**

- **R (Red):** Strife / intensity / chaos â†’ influences `right_current`
- **G (Green):** Harmony / balance / cohesion â†’ influences `left_current`
- **B (Blue):** Memory depth / introspection / persistence â†’ amplifies both currents

**Philosophy:**

- **Left Current (Analytical):** Harmony + Memory Depth â†’ Stabilizing, structured, reflective reasoning
- **Right Current (Creative):** Strife + Memory Depth â†’ Scrutiny, intensity-driven, emotional reasoning
- **Memory Amplification:** High B (memory depth) amplifies both currents, ensuring deeply meaningful messages receive appropriate routing weight

### **1.4 Routing Philosophy and Binding Configurability**

HERMES may optionally use CADUCEUS emotional currents as context when routing messages. The default binding is:

- **Left Pool** = stabilizing / analytical agents
- **Right Pool** = exploratory / creative agents

This default reflects the design intent that:

- High harmony (G) signals stabilizing, context-aware processing.
- High criticality (R) signals scrutiny-oriented or tension-sensitive processing.
- Memory depth (B) amplifies both sides equally.

However, these bindings are **NOT universal**. Nodes **MAY** rebind left/right pools to match domain needs (e.g., creative=left, analytical=right). Any such rebindings **MUST** be documented at the node level and **MUST NOT** alter CADUCEUS math.

**Critical Rule:** CADUCEUS computation remains unchanged regardless of pool binding. Only HERMES interprets which pool corresponds to `left` vs `right`.

---

## **WHERE**

### **HERMES Location**

**Files:**
- **Implementation:** `lupo-includes/class-hermes.php`
- **RFC Specification:** `docs/protocols/HERMES_ROUTING_RFC.md`
- **Architecture Documentation:** `docs/core/ARCHITECTURE_SYNC.md` (Section 1.1)

**Integration Points:**

1. **DialogManager** â€” Calls `HERMES::route($packet)` during message lifecycle
2. **CADUCEUS** â€” Called via `Caduceus::computeCurrents($moodRgb)`
3. **Database** â€” Reads `agent_registry` table for agent availability
4. **THEMIS** â€” Logs routing decisions for governance audit

**Runtime Location:**
- Application layer (PHP)
- No database views, triggers, or stored procedures
- Runs during message processing pipeline

### **CADUCEUS Location**

**Files:**
- **Implementation:** `lupo-includes/class-caduceus.php`
- **RFC Specification:** `docs/protocols/CADUCEUS_ROUTING_RFC.md` (v4.0.1)
- **Architecture Documentation:** `docs/core/ARCHITECTURE_SYNC.md` (Section 1.2)
- **Doctrine:** `docs/appendix/COUNTING_IN_LIGHT.md`

**Integration Points:**

1. **HERMES** â€” Called exclusively via `Caduceus::computeCurrents($moodRgb)`
2. **No Database Access** â€” Pure function, no side effects
3. **No External Dependencies** â€” Self-contained computation

**Runtime Location:**
- Application layer (PHP)
- Stateless pure function
- Can be ported to Python, Node.js, or any runtime

---

## **WHEN**

### **HERMES â€” Invocation Timeline**

**When HERMES Is Called:**

1. **Message Lifecycle (DialogManager):**
   ```
   DialogManager::processMessage()
     â†’ Insert incoming message â†’ dialog_messages
     â†’ HERMES::route($packet) â†’ agent_id
     â†’ IRIS::think($agentId, $packet) â†’ LLM response
     â†’ WOLFMIND::store($packet) â†’ memory persistence
     â†’ Insert outgoing message â†’ dialog_messages
   ```

2. **Every Message:** HERMES is called for every message processed by DialogManager
3. **Routing Decision Points:**
   - Direct routing (explicit `to_actor`)
   - Mood-aware routing (CADUCEUS currents)
   - Fallback routing (mood-agnostic default pool)

**When HERMES Is NOT Called:**
- Message insertion (DialogManager handles directly)
- LLM invocation (IRIS handles directly)
- Memory storage (WOLFMIND handles directly)
- Agent execution (agents handle their own processing)

### **3.7 Failover and No-Availability Handling**

If the selected pool has no active agents, HERMES **MUST**:

1. Retry routing using the opposite pool.
2. If both pools lack active agents, route to System Agent 0.
3. If System Agent 0 is unavailable, return an error packet with:
   - `error_type: "ROUTING_FAILURE"`
   - `reason: "No available agents in any pool"`
   - `fallback_mood: "666666"`

This ensures deterministic behavior even in partial swarm outages.

### **3.8 Error Flow Specification**

HERMES **MUST** emit structured error packets for:

- **INVALID_MOOD** (invalid hex, replaced with neutral)
- **VOID_MOOD** (`000000`, treated as neutral)
- **NO_ACTIVE_AGENTS** (failover exhausted)
- **POOL_BINDING_ERROR** (misconfigured left/right pools)
- **RUNTIME_PRECISION_ERROR** (float invariant violation)

Error packets **MUST NOT** halt routing; they **MUST** be logged and returned to the caller.

### **3.9 Migration from Pre-4.0.0 Dialogs**

Messages created before v4.0.0 **MAY** lack `mood_rgb`. HERMES **MUST** treat missing moods as:

- `mood_rgb = "666666"`
- `origin = "legacy_default"`

Legacy messages **MUST NOT** be retroactively assigned inferred moods.

### **CADUCEUS â€” Invocation Timeline**

**When CADUCEUS Is Called:**

1. **During HERMES Routing:**
   ```
   HERMES::route($packet)
     â†’ Extract mood_rgb from packet
     â†’ Caduceus::computeCurrents($moodRgb) â†’ ['left' => float, 'right' => float]
     â†’ Compare currents (left > right vs right > left)
     â†’ Select agent pool
     â†’ Return agent_id
   ```

2. **Only When Needed:**
   - CADUCEUS is called ONLY if `to_actor` is NOT explicitly set
   - Direct routing bypasses CADUCEUS computation
   - Mood-aware routing requires CADUCEUS currents

**When CADUCEUS Is NOT Called:**
- Direct routing (`to_actor` explicitly set)
- Invalid routing paths (should not occur, but handled gracefully)
- Mood-agnostic fallback (if implemented separately)

**Performance:**
- Computation time: < 0.1ms per call (without memoization)
- Optional ephemeral memoization (request-scope only)
- No database access, no file I/O, no network calls

### **6.1 Swarm-Scale Optimization**

To support 101+ agent swarms, HERMES **SHOULD**:

- Cache agent pools in-memory for the duration of a routing cycle
- Refresh pools only when:
  - `agent_registry.updated_at` changes
  - THEMIS issues a governance update
  - a pool becomes empty

This reduces DB load and ensures predictable routing latency.

---

## **WHY**

### **Why HERMES Exists**

**Problem It Solves:**
Lupopedia's multi-agent architecture requires deterministic routing decisions that:

1. **Honor Explicit Directives:** When a user or agent explicitly targets another agent
2. **Respect Emotional Context:** Route based on mood signals for appropriate agent matching
3. **Maintain Agent Availability:** Only route to active agents with available dependencies
4. **Ensure Determinism:** Same inputs produce same routing decisions across nodes
5. **Support Governance:** Enable routing audit and malicious behavior detection

**Why Not Direct Agent Selection:**
- Agents don't know which other agents are available
- Mood signals require mathematical transformation (CADUCEUS)
- Routing decisions need to be auditable and governable
- Federation requires node-scoped routing isolation (federation occurs between nodes/domain installations, not agents)

**Architectural Philosophy:**
- **Separation of Concerns:** HERMES routes, IRIS thinks, WOLFMIND remembers, DialogManager orchestrates
- **Pure Routing:** HERMES only routes; it doesn't call LLMs, store memory, or modify database
- **Deterministic Routing:** Prevents routing drift across nodes and runtimes

### **5.4 Skew Monitoring and Governance**

If HERMES observes >90% of routed messages selecting the same pool over a rolling window (configurable), it **MUST**:

- Log a skew event
- Notify THEMIS with:
  - `pool_id`
  - `skew_ratio`
  - `time_window`
  - `top contributing agents`

Nodes **MAY** enforce diversity thresholds (e.g., max 80% pool dominance) to prevent routing manipulation.

### **Why CADUCEUS Exists**

**Problem It Solves:**
CADUCEUS computes channel emotional current by reading and blending the moods of polar agents within a channel. Counting-in-Light provides emotional metadata as RGB values (0-255), which CADUCEUS converts into normalized emotional currents (0.0-1.0) that represent the channel's blended emotional state. HERMES may optionally use these currents as context when routing messages.

**Why Not Direct RGB Comparison:**
- RGB values are not normalized (0-255 range)
- RGB axes have different semantic meanings (R=Strife, G=Harmony, B=Memory)
- Routing requires a single dimension (analytical vs creative)
- Normalization ensures consistent routing across mood ranges

**Why Separate Helper Class:**
- **Pure Function Guarantee:** No side effects, no database access, no state
- **Testability:** Easy to unit test independently
- **Portability:** Can be ported to Python, Node.js, or any runtime
- **Performance:** Sub-millisecond computation with optional memoization
- **Federation Safety:** Identical results across PHP versions and nodes

**Mathematical Rationale:**
- **Memory Amplification:** High B (memory depth) amplifies both currents, ensuring deep messages get appropriate routing weight
- **Axis Binding:** Left = (G + B) for stabilizing/analytical, Right = (R + B) for intensity/creative
- **Normalization:** Ensures routing decisions remain balanced and deterministic

---

## **HOW**

### **How HERMES Routes Messages**

**Step-by-Step Flow:**

1. **Read Channel Routing Mode (First Check):**
   - HERMES **MUST** read `channels.metadata_json` and extract:
     - `routing_mode` (string) â€” determines how messages are routed
       - `"none"` â€” no routing; direct addressing only (Crafty Syntax mode)
       - `"hermes"` â€” full emotional routing (default)
       - `"operator"` â€” one agent sees all messages; others see only addressed messages
       - `"broadcast"` â€” all agents see all messages; no routing
   - **Critical Rule:** If `routing_mode != "hermes"`, HERMES **MUST** bypass CADUCEUS and classification logic entirely

2. **Read Channel Intent (Primary Filter) â€” Only if routing_mode == "hermes":**
   - HERMES **MUST** read `channels.metadata_json` and extract:
     - `channel_intent` (string)
     - `allowed_roles` (array, optional)
     - `disallowed_roles` (array, optional)
   - Filter candidate agents based on channel intent **BEFORE** applying CADUCEUS currents
   - Channel intent is the **primary routing constraint**; mood-based routing is **secondary**

3. **Receive Message Packet:**
   ```php
   $packet = [
       'actor_id' => 1,
       'to_actor' => null,
       'mood_rgb' => '00FF00',
       'directive_dialog_id' => 0,
       'content' => 'User message text',
       'conversation_id' => 42,
       'node_id' => 1
   ];
   ```

4. **Check for Explicit Routing:**
   ```php
   if (!empty($packet['to_actor']) && $packet['to_actor'] > 0) {
       // Direct routing: validate and return
       if ($this->validateAgent($packet['to_actor'])) {
           return (int)$packet['to_actor'];
       }
       // Fall through to mood-aware routing if validation fails
   }
   ```

5. **Compute CADUCEUS Currents (only if routing_mode == "hermes"):**
   ```php
   $moodRgb = $packet['mood_rgb'] ?? '666666'; // neutral fallback
   $currents = Caduceus::computeCurrents($moodRgb);
   // Returns: ['left' => 0.75, 'right' => 0.25]
   ```

6. **Compare Currents and Select Pool (only if routing_mode == "hermes"):**
   - After channel intent filtering, compare CADUCEUS currents
   ```php
   if ($currents['left'] > $currents['right']) {
       // Route to analytical pool
       return $this->chooseAnalyticalAgent($packet);
   } elseif ($currents['right'] > $currents['left']) {
       // Route to creative pool
       return $this->chooseCreativeAgent($packet);
   } else {
       // Tiebreaker: use thread history or default pool
       return $this->chooseDefaultAgent($packet);
   }
   ```

7. **Validate Agent Availability:**
   ```php
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
   ```

8. **Return Agent ID:**
   - Returns `agent_id` (integer) for next routing hop
   - DialogManager uses this to call IRIS for LLM invocation

**Routing Decision Tree:**

```
Message Packet
    â”‚
    â”œâ”€ Read routing_mode from channels.metadata_json
    â”‚
    â”œâ”€ routing_mode != "hermes"? â”€â”€YESâ”€â”€â†’ Bypass routing logic â†’ Direct/Operator/Broadcast mode
    â”‚
    â””â”€ routing_mode == "hermes"? â”€â”€YESâ”€â”€â†’ Full routing pipeline:
                â”‚
                â”œâ”€ Read Channel Intent â†’ Filter Candidate Agents
                â”‚
                â”œâ”€ Filter by Agent Classification â†’ Filter by agent_class
                â”‚
                â”œâ”€ to_actor set? â”€â”€YESâ”€â”€â†’ Validate â†’ Direct Route
                â”‚                          â”‚
                â”‚                          â””â”€FAILâ”€â”€â†’ Channel Intent + Classification Filter â†’ Mood-Aware Routing
                â”‚
                â””â”€ NO â”€â”€â†’ Channel Intent + Classification Filter â†’ CADUCEUS::computeCurrents(mood_rgb)
                            â”‚
                            â”œâ”€ left > right â”€â”€â†’ Analytical Pool (filtered) â†’ Agent Selection
                            â”‚                       â”‚
                            â”‚                       â””â”€ NO AGENTS â”€â”€â†’ Opposite Pool â†’ System Agent 0
                            â”‚
                            â”œâ”€ right > left â”€â”€â†’ Creative Pool (filtered) â†’ Agent Selection
                            â”‚                       â”‚
                            â”‚                       â””â”€ NO AGENTS â”€â”€â†’ Opposite Pool â†’ System Agent 0
                            â”‚
                            â””â”€ left == right â”€â”€â†’ Tiebreaker â†’ Default Pool â†’ System Agent 0 (if empty)
```

### **Channel Routing Mode (First Check)**

Before routing, HERMES **MUST** read `channels.metadata_json` and extract:

- `routing_mode` (string) â€” determines how messages are routed in this channel

**Valid values:**
- `"none"` â€” no routing; direct addressing only (Crafty Syntax mode)
- `"hermes"` â€” full emotional routing (default)
- `"operator"` â€” one agent sees all messages; others see only addressed messages
- `"broadcast"` â€” all agents see all messages; no routing

**Critical Rule:** If `routing_mode != "hermes"`, HERMES **MUST** bypass CADUCEUS and classification logic entirely. Routing mode takes precedence over all other routing decisions.

### **Channel Intent (Primary Routing Filter) â€” Only if routing_mode == "hermes"**

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

### **Agent Class Filtering â€” Only if routing_mode == "hermes"**

If `routing_mode == "hermes"`, HERMES **MUST** filter candidate agents by `classification_json.agent_class`.

Channel intent defines which agent classes are allowed or disallowed.  
Only after class filtering does HERMES optionally use CADUCEUS emotional currents as context.

**Routing order (only if routing_mode == "hermes"):**
1. Channel routing mode check (if not "hermes", bypass all routing logic)
2. Channel intent filter  
3. Agent classification filter  
4. CADUCEUS currents  
5. Failover to opposite pool  
6. Fallback to System Agent 0

### **How CADUCEUS Computes Currents**

**Step-by-Step Flow:**

1. **Sanitize and Validate Input:**
   ```php
   $moodRgb = strtoupper(trim($moodRgb)); // "  ff0000 " â†’ "FF0000"
   if (!preg_match('/^[0-9A-F]{6}$/', $moodRgb)) {
       $moodRgb = '666666'; // Invalid â†’ neutral fallback
   }
   
   // Handle void mood
   if ($moodRgb === '000000') {
       $moodRgb = '666666'; // Void â†’ neutral fallback
   }
   ```

2. **Extract RGB Components:**
   ```php
   $r = hexdec(substr($moodRgb, 0, 2)); // Strife (0-255)
   $g = hexdec(substr($moodRgb, 2, 2)); // Harmony (0-255)
   $b = hexdec(substr($moodRgb, 4, 2)); // Memory (0-255)
   
   // Example: "00FF00" â†’ R=0, G=255, B=0
   ```

3. **Compute Raw Currents:**
   ```php
   $leftRaw  = $g + $b;  // Harmony + Memory Depth
   $rightRaw = $r + $b;  // Strife + Memory Depth
   
   // Example: "00FF00" â†’ leftRaw=255, rightRaw=0
   ```

4. **Normalize to 0.0-1.0:**
   ```php
   $sum = max($leftRaw + $rightRaw, 1); // Avoid division by zero
   $left  = $leftRaw  / $sum;
   $right = $rightRaw / $sum;
   
   // Example: "00FF00" â†’ left=1.0, right=0.0
   ```

5. **Enforce Invariant (Precision Guarantee):**
   ```php
   const EPSILON = 1e-6; // Precision threshold
   $total = $left + $right;
   
   if (abs($total - 1.0) > EPSILON) {
       // Normalize to enforce invariant: left + right = 1.0
       $left  = $left  / $total;
       $right = $right / $total;
   }
   ```

6. **Return Normalized Currents:**
   ```php
   return [
       'left'  => $left,   // 0.0-1.0
       'right' => $right   // 0.0-1.0
   ];
   
   // Example: "00FF00" â†’ ['left' => 1.0, 'right' => 0.0]
   // Example: "666666" â†’ ['left' => 0.5, 'right' => 0.5] (neutral)
   // Example: "FF0000" â†’ ['left' => 0.0, 'right' => 1.0] (high strife)
   ```

**Precision and Determinism:**
- Epsilon = `1e-6` (one millionth) for float comparisons
- Invariant enforcement: `abs(left + right - 1.0) <= epsilon`
- No raw float comparisons without epsilon
- Ensures identical results across PHP 8.1/8.3, Python, and all nodes

### **2.6 High-Precision Safety**

CADUCEUS **MUST** use fixed epsilon = `1e-6` for float comparisons. For environments where raw sums may exceed 32-bit integer limits, implementations **SHOULD** use BCMath or arbitrary-precision math for:

- `leftRaw = G + B`
- `rightRaw = R + B`
- `sum = leftRaw + rightRaw`

This prevents overflow and ensures cross-runtime determinism.

---

## **Integration Example**

**Complete Message Flow:**

```php
// 1. DialogManager receives message
$packet = [
    'actor_id' => 1,
    'to_actor' => null,
    'mood_rgb' => '00FF00',  // High harmony, low strife
    'content' => 'Help me understand this concept',
    'conversation_id' => 42,
    'node_id' => 1
];

// 2. DialogManager calls HERMES
$hermes = new HERMES($db);
$agentId = $hermes->route($packet);

// 3. HERMES calls CADUCEUS (since to_actor is null)
$currents = Caduceus::computeCurrents('00FF00');
// Returns: ['left' => 1.0, 'right' => 0.0]

// 4. HERMES compares currents
// left (1.0) > right (0.0) â†’ Route to analytical pool

// 5. HERMES selects agent from analytical pool
// Example: THOTH (agent_id = 22)

// 6. HERMES returns agent_id
return 22;

// 7. DialogManager calls IRIS with agent_id
$response = IRIS::think(22, $packet);

// 8. DialogManager stores response via WOLFMIND
WOLFMIND::store($packet, $response);

// 9. DialogManager inserts outgoing message
// Message routed to analytical agent based on high harmony mood
```

---

## **Test Cases**

### **9.3 Required Test Cases**

Implementations **MUST** include tests for:

1. **Equal Currents (Tiebreaker):**
   - Input: `mood_rgb = "0000FF"` (pure memory depth)
   - Expected: `left â‰ˆ right` â†’ deterministic tiebreaker selection
   - Verify: Same input produces same tiebreaker result

2. **Invalid Hex (Neutral Fallback):**
   - Input: `mood_rgb = "FFGG00"` (invalid hex)
   - Expected: CADUCEUS substitutes `"666666"` â†’ `left = 0.5, right = 0.5`
   - Verify: No routing failure, neutral routing occurs

3. **Void Mood (Neutral Fallback):**
   - Input: `mood_rgb = "000000"` (void state)
   - Expected: CADUCEUS substitutes `"666666"` â†’ `left = 0.5, right = 0.5`
   - Verify: Void treated as neutral, not as zero

4. **No Agents in Selected Pool (Failover):**
   - Scenario: Analytical pool selected, but all agents inactive
   - Expected: HERMES fails over to creative pool
   - Verify: Failover occurs, routing continues

5. **No Agents in Any Pool (System Fallback):**
   - Scenario: Both pools empty, System Agent 0 active
   - Expected: HERMES routes to System Agent 0
   - Verify: System fallback occurs, error packet returned if System Agent 0 unavailable

6. **Federation Mock (Binding Consistency):**
   - Scenario: Same `mood_rgb = "00FF00"` routed on two nodes with different left/right bindings
   - Expected: CADUCEUS produces identical currents (`left = 1.0, right = 0.0`), but routing diverges based on node binding
   - Verify: Consistent CADUCEUS math, documented routing divergence

7. **Precision Invariant Enforcement:**
   - Scenario: Moods that may cause float drift
   - Expected: Invariant `abs(left + right - 1.0) <= 1e-6` always holds
   - Verify: Normalization step enforces invariant

8. **Skew Detection Threshold:**
   - Scenario: >90% of messages route to same pool over rolling window
   - Expected: HERMES logs skew event, notifies THEMIS
   - Verify: Skew detection triggers, governance integration works

---

## **Related Documentation**

- **[RFC 4003](../architecture/protocols/CADUCEUS_ROUTING_RFC.md)** â€” CADUCEUS Emotional Balancing Standard v4.0.1
- **[RFC 4004](../architecture/protocols/HERMES_ROUTING_RFC.md)** â€” HERMES Routing Layer Standard v4.0.1
- **[RFC 4002](../architecture/protocols/THREAD_MOOD_RFC.md)** â€” Thread Mood Aggregation Standard
- **[MOOD_RGB_DOCTRINE.md](../doctrine/MOOD_RGB_DOCTRINE.md)** â€” MOOD_RGB Doctrine
- **[COUNTING_IN_LIGHT.md](../appendix/appendix/COUNTING_IN_LIGHT.md)** â€” Counting-in-Light Specification
- **[ARCHITECTURE_SYNC.md](../architecture/ARCHITECTURE_SYNC.md)** â€” Complete Architecture Documentation

---

*Last Updated: January 2026*  
*Version: 4.0.1*  
*Status: Published*  
*ARA Review Applied: v4.0.1 patches integrated*
