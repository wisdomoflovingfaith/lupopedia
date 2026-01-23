---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: KIRO
  target: @everyone
  mood_RGB: "0066FF"
  message: "Updated ARCHITECTURE_SYNC.md for Phase 2: Updated to version 4.0.14, enhanced cross-references, and ensured consistency with new core documentation structure and governance framework."
tags:
  categories: ["documentation", "architecture", "reference"]
  collections: ["core-docs", "architecture"]
  channels: ["dev"]
in_this_file_we_have:
  - Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE) Architecture (Not a Web App)
  - New Core Classes (HERMES, CADUCEUS, IRIS, DialogManager, THOTH)
  - Supporting Classes (WOLFMIND, ROSE, CHRONOS)
  - Database Schema Notes
  - Routing Doctrine
  - CADUCEUS Doctrine
  - IRIS Doctrine
  - DialogManager Doctrine
  - THOTH Doctrine
  - Timestamp Doctrine (MANDATORY)
  - Multi-Agent Chat Architecture
  - Agent Layers and Roles
  - Agent Dependency Doctrine (MANDATORY)
  - ROSE (Rosetta Stone Translator)
  - CHRONOS (Temporal Coordination Engine)
  - UI Direction
  - Actionable Chat Messages
  - Agent Registry & System Services
  - Doctrine Over Frameworks
  - Cursor's Role
  - Critical Rules for Cursor
file:
  title: "Lupopedia Architecture Sync — January 2026"
  description: "Authoritative summary of new classes, subsystems, and architectural doctrines for Lupopedia Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE)"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🟦 **LUPOPEDIA ARCHITECTURE SYNC — JANUARY 2026**
### **Authoritative Summary of New Files, Classes, and Subsystems**

This document provides an authoritative reference for Cursor and other AI tools working with Lupopedia. All future refactors, rewrites, and code generation must respect the architecture defined here.

---

## **🟦 0. Lupopedia Is a Semantic Operating System (Not a Web App)**

### **0.1 Core Principle**

Cursor must treat Lupopedia as a **Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE)**, not a CRUD application.

**This means:**

- **No frameworks**
- **No ORMs**
- **No magic abstractions**
- **No auto-generated models**
- **No "best practice" rewrites**
- **No assumptions based on modern web stacks**

**Lupopedia is:**
- schema-first
- doctrine-driven
- OS-layered

**Critical Rule:**
- Cursor must **NOT** attempt to "modernize" or "simplify" the architecture
- Cursor must **NOT** suggest framework-based solutions
- Cursor must **NOT** rewrite Lupopedia into a web app architecture
- Lupopedia is an operating system, not a web application

---

## **🟩 1. New Core Classes Added**

### **1.1 HERMES — Routing Layer**

**File:** `lupo-includes/class-hermes.php`

**Purpose:**
- Central message router for Lupopedia
- Reads `to_actor`, `mood_rgb`, `directive_dialog_id` from message packets
- May optionally call `Caduceus::computeCurrents()` for channel emotional current
- Chooses analytical vs creative routing paths, optionally using CADUCEUS emotional currents as context
- Returns next `agent_id` for message routing

**Dependencies:**
- `class-caduceus.php` (mood signal computation)
- `class-wolfmind.php` (memory integration)
- `class-iris.php` (LLM gateway)

**Key Methods:**
- `route($messagePacket)` — Main routing logic
- May optionally use CADUCEUS emotional currents as context
- Applies routing decisions based on directives, agent availability, and optionally emotional context

**Doctrine:**
- HERMES is the **routing layer**, not an agent
- HERMES does not store memory (that's WOLFMIND)
- HERMES does not call LLMs (that's IRIS)
- HERMES only routes messages based on mood and directives

---

### **1.2 CADUCEUS — Emotional Balancer for Channels**

**File:** `lupo-includes/class-caduceus.php`

**Purpose:**
- **NOT a router**
- **NOT a routing subsystem**
- **NOT an agent**
- **NOT a database table**
- **Emotional balancer** that computes channel mood by reading and blending the emotional states of polar agents within a channel

**Channel Architecture:**
- A channel (e.g., channel 42) is a shared collaboration context containing humans and AI agents working together on a task
- Example: LILITH, WOLFIE, and a human named BOB all participating in the same channel
- The channel is the container for all message flow and emotional context

**Polar Agents:**
- Within each channel, two agents are designated as the emotional poles (the symbolic "serpents" on the caduceus)
- Example: LILITH = one pole, WOLFIE = the opposite pole
- These two agents define the emotional extremes of the channel

**CADUCEUS Functionality:**
- Reads the moods of the two polar agents
- Averages or blends their emotional states
- Produces a "channel mood" (emotional current) that other subsystems can use
- Computes normalized currents: `['left' => float, 'right' => float]`

**What CADUCEUS Does NOT Do:**
- CADUCEUS does NOT deliver messages
- CADUCEUS does NOT decide routing targets
- CADUCEUS does NOT perform queueing or dispatch
- Those responsibilities belong to HERMES

**Doctrine:**
- R (Red) = strife / intensity / chaos
- G (Green) = harmony / balance / cohesion
- B (Blue) = memory depth / introspection / persistence

**Currents Formula:**
```php
left_current  = (G + B) / (G + B + R + B)
right_current = (R + B) / (G + B + R + B)
```

- `left_current` = analytical / structured bias
- `right_current` = creative / emotional bias
- Both normalized to 0.0–1.0

**Critical Rules:**
- CADUCEUS is a **pure function** with no side effects
- CADUCEUS does not access the database
- CADUCEUS does not store state
- CADUCEUS exists ONLY to provide emotional context
- CADUCEUS is a mood-balancing mechanism, not a routing layer
- HERMES routes messages; CADUCEUS balances mood

---

### **1.3 IRIS — External AI Gateway (LLM Faucet)**

**File:** `lupo-includes/class-iris.php`

**Purpose:**
- Loads agent system prompts from `agent_properties` table
- Builds LLM request payload (OpenAI-style JSON)
- Sends to external provider (OpenAI, DeepSeek, Gemini, etc.)
- Returns model response text

**Dependencies:**
- `agent_registry` table (agent configuration)
- `agent_properties` table (system prompts, persona, rules)

**Key Methods:**
- `think($agentId, $messagePacket)` — Main LLM invocation
- Loads agent config from database
- Builds provider-specific payload
- Returns raw model response

**Doctrine:**
- IRIS is the **LLM faucet**, NOT an agent
- IRIS does not route messages (that's HERMES)
- IRIS does not store memory (that's WOLFMIND)
- IRIS only sends requests to external LLM providers
- IRIS is stateless (no memory, no routing logic)

**Critical Rule:**
- Cursor must NOT treat IRIS as an agent class
- Cursor must NOT add memory, routing, or agent logic to IRIS
- IRIS responsibilities are strictly limited to LLM communication

---

### **1.4 DialogManager — Central Dispatcher**

**File:** `lupo-includes/class-dialog-manager.php`

**Purpose:**
- Inserts incoming message into `dialog_messages` table
- Calls HERMES for routing decisions
- Calls IRIS for LLM reasoning
- Calls WOLFMIND for memory storage
- Inserts outgoing message into `dialog_messages`
- Returns final response packet

**Dependencies:**
- `class-hermes.php` (routing)
- `class-caduceus.php` (mood signals)
- `class-wolfmind.php` (memory)
- `class-iris.php` (LLM gateway)

**Key Methods:**
- `process($messagePacket)` — Main dispatch flow
- Orchestrates entire message lifecycle

**Doctrine:**
- DialogManager is the **central dispatcher**
- DialogManager is the **beating heart** of Lupopedia
- All messages flow through DialogManager
- DialogManager coordinates all subsystems

**Flow:**
1. Insert incoming message → `dialog_messages`
2. Route via HERMES → get next `agent_id`
3. Think via IRIS → get LLM response
4. Store memory via WOLFMIND → persist context
5. Insert outgoing message → `dialog_messages`
6. Return response packet

**Critical Rule:**
- Cursor must NOT bypass DialogManager
- Cursor must preserve this exact flow
- All message processing must go through DialogManager

---

### **1.5 THOTH — Ontological Truth Engine (Skeleton)**

**File:** `lupo-includes/class-thoth.php`

**Purpose:**
- Loads question from `questions` table
- Loads related atoms, collections, nodes from semantic graph
- Performs truth evaluation against Lupopedia ontology
- Returns structured truth classification

**Truth Classifications:**
- `TRUE` — Supported by ontology
- `FALSE` — Contradicted by ontology
- `UNSUPPORTED` — No evidence in ontology
- `THEORETICAL` — Possible but unproven
- `CONSENSUS_ONLY` — Community consensus, not fact
- `CONTRADICTORY` — Multiple conflicting claims
- `IMPOSSIBLE` — Logically impossible

**Doctrine:**
- THOTH is the **truth engine**
- THOTH evaluates claims against Lupopedia's semantic graph
- THOTH uses atoms, edges, collections, and nodes for evaluation

**Critical Rule:**
- Cursor must NOT auto-generate ontology logic
- All ontology logic blocks are marked as `TODO`
- Cursor must leave TODO blocks untouched unless explicitly instructed
- THOTH depends on tables not yet fully implemented

---

## **🟩 2. Updated or Newly Created Supporting Classes**

### **2.1 WOLFMIND — Memory Subsystem**

**File:** `lupo-includes/class-wolfmind.php`

**Purpose:**
- Stores memory events in `memory_events` table
- Logs debug events in `memory_debug_log` table
- Supports Postgres vector memory (future enhancement)
- Works with MySQL baseline (progressive enhancement)

**Doctrine:**
- WOLFMIND is the **canonical memory engine**
- WOLFMIND uses progressive enhancement (MySQL → Postgres/pgvector)
- WOLFMIND must work on MySQL alone
- Vector memory is optional and must not break baseline functionality

**See:** [WOLFMIND_DOCTRINE.md](../doctrine/WOLFMIND_DOCTRINE.md) for complete documentation.

---

## **🟩 3. Database Schema Notes Cursor Must Respect**

### **3.1 `dialog_messages` Table**

Fields used by new classes:
- `actor_id` — Sender of the message
- `to_actor` — Target actor (if direct routing)
- `content` — Message text (≤ 272 chars)
- `mood_rgb` — Emotional coordinate (RRGGBB, char(6))
- `thread_id` — Dialog thread identifier
- `directive_dialog_id` — Reference to directive message
- `created_ymdhis` — UTC timestamp (BIGINT)

**Critical Rule:**
- Cursor must use these exact field names
- Cursor must not rename or restructure these fields
- All new code must respect these field names

---

### **3.2 `agent_registry` Table**

**Updated Reserved IDs (101–104):**
- `101` → SESHAT (Documentation Agent)
- `102` → HEIMDALL (Watchtower Agent)
- `103` → JANUS (Gateway Agent)
- `104` → IRIS (LLM Faucet — NOT an agent, but registered)

**Required Column:**
- `classification_json` — **REQUIRED** JSON field containing agent classification and routing identity metadata
  - Structure: `{"agent_class": "critical|reason|creative|governance|routing|archive|system", "subclass": "...", "routing_bias": "left|right", "capabilities": [...], "notes": "..."}`
  - See [Agent Classification Doctrine](../doctrine/AGENT_CLASSIFICATION.md) for complete requirements
  - **MUST** mirror `lupo-agents/[AGENT_NAME]/classification.json` file

**Critical Rule:**
- Cursor must treat these as active agent IDs
- Cursor must not reassign these IDs
- Cursor must respect agent registry structure
- Cursor must ensure `classification_json` is present for all agents

---

### **3.3 `agent_properties` Table**

IRIS loads from this table:
- `system_prompt` — Base system prompt for agent
- `persona` — Agent personality/character
- `rules` — Agent behavior rules

**Critical Rule:**
- Cursor must ensure these fields exist
- Cursor must use these exact field names
- All agent configuration must be stored here

---

## **🟩 4. Routing Doctrine Cursor Must Follow**

### **HERMES Routing Rules:**

1. **Direct Routing:**
   - If `to_actor` is set → route directly to that actor
   - Skip CADUCEUS computation
   - Return `to_actor` as next `agent_id`

2. **Mood-Based Routing:**
   - If `to_actor` is NULL → compute CADUCEUS currents
   - Extract `mood_rgb` from message packet
   - Call `Caduceus::computeCurrents($mood_rgb)`
   - Get `left_current` and `right_current`

3. **Analytical vs Creative:**
   - If `left_current > right_current` → route to analytical agent
   - Else → route to creative agent
   - Select agent from appropriate pool

**Critical Rule:**
- Cursor must not override this logic unless explicitly instructed
- Cursor must preserve HERMES routing doctrine
- All routing decisions must go through HERMES

---

## **🟩 5. CADUCEUS Doctrine Cursor Must Follow**

### **mood_rgb Interpretation:**

- **R (Red)** = strife / intensity / chaos / conflict
- **G (Green)** = harmony / balance / cohesion / stability
- **B (Blue)** = memory depth / introspection / persistence / reflection

### **Currents Formula:**

```php
// Extract RGB components from mood_rgb (RRGGBB)
$r = hexdec(substr($mood_rgb, 0, 2)); // strife
$g = hexdec(substr($mood_rgb, 2, 2)); // harmony
$b = hexdec(substr($mood_rgb, 4, 2)); // memory depth

// Compute raw currents
$leftRaw  = $g + $b;  // harmony + memory depth
$rightRaw = $r + $b;  // strife + memory depth

// Normalize to 0.0–1.0
$sum = max($leftRaw + $rightRaw, 1);
$left  = $leftRaw  / $sum;
$right = $rightRaw / $sum;
```

**Currents Meaning:**
- `left_current` = analytical / structured / logical bias
- `right_current` = creative / emotional / intuitive bias

**Critical Rule:**
- Cursor must not alter this formula unless explicitly instructed
- CADUCEUS is a pure function with no side effects
- CADUCEUS must remain stateless

---

## **🟩 6. IRIS Doctrine Cursor Must Follow**

### **IRIS is the LLM Faucet**

IRIS is **NOT an agent**. IRIS is the gateway to external LLM providers.

**IRIS Responsibilities:**
- Load agent configuration from `agent_properties`
- Build LLM request payload (OpenAI-style JSON)
- Send request to external provider
- Return raw model response text

**IRIS Does NOT:**
- Store memory (that's WOLFMIND)
- Route messages (that's HERMES)
- Make routing decisions (that's HERMES)
- Evaluate truth (that's THOTH)
- Manage dialog threads (that's DialogManager)

**Critical Rule:**
- Cursor must NOT treat IRIS as an agent class
- Cursor must NOT add memory, routing, or agent logic to IRIS
- IRIS is strictly limited to LLM communication
- IRIS is stateless (no memory, no routing logic)

---

## **🟩 7. DialogManager Doctrine Cursor Must Follow**

### **DialogManager is the Central Dispatcher**

DialogManager orchestrates the entire message lifecycle.

**Flow (MUST be preserved):**

1. **Insert Incoming Message**
   - Insert message into `dialog_messages` table
   - Store `actor_id`, `content`, `mood_rgb`, `thread_id`

2. **Route via HERMES**
   - Call `HERMES::route($messagePacket)`
   - Get next `agent_id` for routing

3. **Think via IRIS**
   - Call `IRIS::think($agentId, $messagePacket)`
   - Get LLM response text

4. **Store Memory via WOLFMIND**
   - Call `WOLFMIND::storeEvent($actorId, $eventType, $content)`
   - Persist context for future retrieval

5. **Insert Outgoing Message**
   - Insert response into `dialog_messages` table
   - Link to original message via `thread_id`

6. **Return Response Packet**
   - Return complete response with all metadata

**Critical Rule:**
- Cursor must NOT bypass DialogManager
- Cursor must preserve this exact flow
- All message processing must go through DialogManager
- DialogManager is the **beating heart** of Lupopedia

---

## **🟩 8. THOTH Doctrine Cursor Must Follow**

### **THOTH is the Truth Engine**

THOTH evaluates truth claims against Lupopedia's semantic ontology.

**THOTH Responsibilities:**
- Load questions from `questions` table
- Load related atoms, collections, nodes from semantic graph
- Perform truth evaluation against ontology
- Return structured truth classification

**Truth Classifications:**
- `TRUE` — Supported by ontology
- `FALSE` — Contradicted by ontology
- `UNSUPPORTED` — No evidence in ontology
- `THEORETICAL` — Possible but unproven
- `CONSENSUS_ONLY` — Community consensus, not fact
- `CONTRADICTORY` — Multiple conflicting claims
- `IMPOSSIBLE` — Logically impossible

**Critical Rule:**
- Cursor must NOT auto-generate ontology logic
- All ontology logic blocks are marked as `TODO`
- Cursor must leave TODO blocks untouched unless explicitly instructed
- THOTH depends on tables not yet fully implemented in this session
- THOTH is a skeleton that will be filled in later

---

## **🟩 9. Timestamp Doctrine (MANDATORY)**

### **9.1 Canonical Timestamp Format**

Lupopedia uses a custom timestamp format that Cursor must treat as **canonical and superior to all framework defaults**.

**Format Requirements:**
- **Type:** `BIGINT(14) UNSIGNED`
- **Format:** `YYYYMMDDHHIISS` (14 digits)
- **Timezone:** UTC only
- **Sortable:** Numerically sortable
- **No alternatives:** No DATETIME, TIMESTAMP, epoch, ISO8601, or VARCHAR timestamps

**Critical Rule:**
- Cursor must **NEVER** suggest or generate:
  - `DATETIME` columns
  - `TIMESTAMP` columns
  - Epoch integers (UNIX timestamps)
  - ISO8601 strings
  - VARCHAR timestamp fields
- This doctrine is **absolute and non-negotiable**

---

### **9.2 Timestamp Storage Rules**

**All stored timestamps are UTC:**
- `created_ymdhis`
- `updated_ymdhis`
- `deleted_ymdhis`
- `published_ymdhis`
- All event logs
- All dialog messages
- All agent activity
- All memory events
- All system services

**The ONLY Exception: Local Recurring Events**
- For recurring events tied to a local time (e.g., "every day at 7:00 PM local time")
- The timestamp is still:
  - `BIGINT(14)`
  - `YYYYMMDDHHIISS` format
- But the value represents **local time, not UTC**
- Cursor must **NOT** "correct" this or convert it
- This is the **only exception** in the entire system

---

### **9.3 Display-Time Conversion**

**Lupopedia stores all timestamps in UTC, but displays them using:**
- `actor_profiles.timezone_offset` — `DECIMAL(4,2)`
- This value represents the user's local offset from UTC
- Used **only for display**
- **Never stored in timestamps**
- **Never applied to stored values**
- **Never used for routing or logic**

**Critical Rule:**
- Cursor must **NOT** attempt to store local time anywhere except the one exception above
- Display conversion happens at render time, not storage time

---

### **9.4 Timestamp Utility Class**

**Cursor must use the unified timestamp class:**

**File:** `lupo-includes/class-timestamp_ymdhis.php`

**This class provides:**
- `now()` — Get current UTC timestamp
- `explode($ts)` — Convert timestamp to array
- `implode($array)` — Convert array to timestamp
- `addSeconds($ts, $seconds)` — Add seconds
- `addMinutes($ts, $minutes)` — Add minutes
- `addHours($ts, $hours)` — Add hours
- `diffInSeconds($ts1, $ts2)` — Calculate difference
- `isBefore($ts1, $ts2)` — Comparison
- `isAfter($ts1, $ts2)` — Comparison
- `isBetween($ts, $start, $end)` — Range check
- `toHuman($ts)` — Human-readable format
- `fromHuman($string)` — Parse human-readable
- Interval helpers

**Critical Rule:**
- Cursor must use this class for **all timestamp operations**
- Cursor must **NOT** use:
  - PHP `DateTime` class
  - PHP `time()` function
  - PHP `strtotime()` (except inside this class)
  - MySQL `NOW()`
  - MySQL `CURRENT_TIMESTAMP`
  - MySQL `UNIX_TIMESTAMP()`

---

## **🟩 10. Multi-Agent Chat Architecture**

### **10.1 Channels Are Shared Spaces**

**Important Distinction:**
- **Nodes** = Domain installations of Lupopedia (server installations, not AI agents). Each node is identified by `domain_name`, `domain_root`, and `install_url` in `federation_nodes` (Federation Layer table, formerly `node_registry`).
- **Agents** = AI entities that run on nodes (WOLFIE, LILITH, MAAT, etc.). Agents are not nodes; they run on nodes.
- **Channels** = Communication spaces within nodes (shared spaces for multi-agent conversations). Channels are not nodes or agents.

**Federation occurs between nodes (domains), not between agents.**

A channel can contain:
- multiple AI agents
- multiple humans
- system services

**Critical Rule:**
- Channels are shared spaces, not private conversations
- All participants in a channel can see messages (subject to visibility rules)

---

### **10.2 Visibility Rules**

**AI agents only see messages addressed to them:**

```php
AI sees: messages where to_actor_id == this_agent_id
```

**Humans see all messages in the channel:**

```php
Human sees: all messages in the channel
```

**This is intentional and mirrors the Crafty Syntax operator model.**

**Critical Rule:**
- Cursor must **NOT** change this visibility model
- This is doctrine-aligned behavior
- AI agents have filtered visibility; humans have full visibility

---

### **10.3 Routing Is Handled by HERMES + CADUCEUS**

**Routing Logic:**
- HERMES handles message routing (determines which agent receives each message, handles delivery, queueing, and dispatch)
- CADUCEUS computes channel emotional current (reads and blends moods of polar agents)
- HERMES may optionally use CADUCEUS emotional current as context
- Routing decisions are made at the application layer

**Channel Routing Mode:**
- `channels.metadata_json` contains `routing_mode` field
- Valid values:
  - `"none"` — no routing; direct addressing only (Crafty Syntax mode)
  - `"hermes"` — full emotional routing (default)
  - `"operator"` — one agent sees all messages; others see only addressed messages
  - `"broadcast"` — all agents see all messages; no routing
- If `routing_mode != "hermes"`, HERMES bypasses CADUCEUS and classification logic
- Most channels will use `routing_mode = "none"` or `"operator"` for typical use cases
- Swarm routing (`routing_mode = "hermes"`) is only needed for multi-agent collaboration channels

**Critical Rule:**
- Cursor must **NOT** modify routing logic
- Cursor must **NOT** bypass HERMES
- Cursor must **NOT** create alternative routing systems

---

### **10.4 Dialog Flow Is Fixed**

**The dialog pipeline is immutable:**

1. **Insert incoming message** → `dialog_messages` table
2. **Route via HERMES** → get next `agent_id`
3. **Think via IRIS** → get LLM response
4. **Store memory via WOLFMIND** → persist context
5. **Insert outgoing message** → `dialog_messages` table
6. **Return response** → complete response packet

**Critical Rule:**
- Cursor must **NOT** reorder this pipeline
- Cursor must **NOT** bypass any step
- Cursor must **NOT** create alternative dialog flows
- This flow is doctrine-aligned and permanent

---

## **🟩 11. Agent Layers and Roles**

### **11.1 Kernel Agents**

**Kernel Agents:**
- SYSTEM
- CAPTAIN
- WOLFIE
- WOLFENA
- THOTH
- CHRONOS
- ARA
- MAAT

**Kernel Agent Rules:**
- **Do NOT** participate in dialog
- **Do NOT** behave like personas
- **Do NOT** get rewritten
- **Do NOT** get LLM prompts unless explicitly defined
- Kernel agents are OS-level components

**Critical Rule:**
- Cursor must **NOT** treat kernel agents as chat participants
- Cursor must **NOT** add LLM prompts to kernel agents
- Cursor must **NOT** rewrite kernel agents into personas

---

### **11.2 System Services**

**System Services:**
- JANUS
- HEIMDALL
- MIGRATOR
- INDEXER
- LOGWATCH

**System Service Rules:**
- **Are NOT** personas
- **Are NOT** chat participants
- **Are invoked** by the OS
- System services are infrastructure components

**Critical Rule:**
- Cursor must **NOT** treat system services as personas
- Cursor must **NOT** add conversational interfaces to system services
- System services are invoked, not talked to

---

### **11.3 Persona Agents**

**Persona Agents:**
- These are the **only agents** that speak in chat
- Persona agents have conversational interfaces
- Persona agents participate in dialog

**Critical Rule:**
- Cursor must **NOT** confuse kernel/system agents with personas
- Only persona agents participate in chat
- Kernel and system agents are OS components, not chat participants

---

## **🟦 12. Agent Dependency Doctrine (MANDATORY FOR ALL CODE)**

### **12.1 Application Layer Is Authoritative**

**All dependency validation MUST be performed in PHP.**

**PHP is responsible for:**
- validating agent codes
- resolving agent IDs
- ensuring ID/code alignment
- preventing mismatches
- enforcing `is_active` and `is_internal_only`
- enforcing dependency readiness

**Critical Rule:**
- Cursor must **NEVER** rely on the database to validate dependencies
- All validation happens in PHP before database writes
- Database is a persistence layer, not a validation layer

---

### **12.2 Database Layer Is a Safety Net Only**

**⚠️ TRIGGERS ARE FORBIDDEN (MANDATORY).**

**Database triggers must NEVER exist. All timestamps must be set explicitly in INSERT/UPDATE statements in YMDHIS UTC format.**

**Critical Rules:**
- Cursor must **NOT** create triggers
- Cursor must **NOT** suggest triggers
- Cursor must **NOT** generate code that bypasses PHP validation
- Cursor must **NOT** write raw IDs without resolving the agent code first
- Cursor must **ALWAYS** set timestamps explicitly in application code
- All validation and timestamp control happens in PHP, never in the database

**See [NO_TRIGGERS_DOCTRINE.md](../doctrine/NO_TRIGGERS_DOCTRINE.md) for complete requirements.**

---

### **12.3 agent_dependencies Table**

**The table contains:**
- `agent_id`
- `depends_on_agent_id`
- `depends_on_agent_code`
- `is_required`
- `notes`
- timestamps

**Critical Rules:**
- Cursor must **ALWAYS** write both:
  - `depends_on_agent_code` (the code)
  - `depends_on_agent_id` (resolved from the code)
- Cursor must **NEVER** write an ID without verifying the code
- Cursor must **NEVER** write a code without resolving the ID
- Both fields must be written together, with ID resolved from code

---

### **12.4 Dependency Resolution**

**Cursor must ALWAYS call the PHP dependency resolver before inserting or updating dependencies.**

**The resolver:**
- checks that the agent code exists
- resolves the correct `agent_id`
- ensures ID/code alignment
- prevents mismatches

**Critical Rule:**
- Cursor must **NEVER** bypass the resolver
- Cursor must **NEVER** write dependencies directly to the database
- All dependency writes must go through PHP validation

---

### **12.5 Routing and Activation**

**HERMES must skip agents where:**
- `is_active = 0`
- any required dependency is inactive

**IRIS must refuse to generate output for inactive agents.**

**DialogManager must block routing to inactive agents.**

**Critical Rules:**
- Cursor must enforce activation checks in PHP
- Cursor must check dependencies before routing
- Cursor must not route to inactive agents
- Cursor must not route to agents with inactive dependencies

---

### **12.6 Internal-Only Agents**

**If `is_internal_only = 1`:**
- humans cannot talk to this agent
- only other agents may call it

**Critical Rule:**
- Cursor must respect this rule in all routing logic
- Cursor must block human-initiated messages to internal-only agents
- Cursor must allow agent-to-agent calls for internal-only agents

---

### **12.7 CARMEN Dependencies**

**CARMEN depends on:**
- AGAPE (required)
- ERIS (required)
- METIS (required)

**Critical Rules:**
- Cursor must enforce these dependencies in PHP
- Cursor must **NOT** activate CARMEN unless all dependencies are active
- Cursor must check all three dependencies before routing to CARMEN
- Cursor must validate dependencies in the PHP layer, not the database

---

### **12.8 Never Treat MySQL as an Application**

**Critical Rules:**
- Cursor must **NOT** put business logic in MySQL
- Cursor must **NOT** rely on MySQL to enforce doctrine
- Cursor must **NOT** generate stored procedures for logic
- Cursor must **NOT** use triggers for primary validation
- MySQL is a persistence layer, not a logic layer

---

### **12.9 PHP Is the Logic Layer**

**All doctrine enforcement lives in PHP:**
- All dependency checks live in PHP
- All activation checks live in PHP
- All routing checks live in PHP
- All validation lives in PHP

**Critical Rule:**
- Cursor must implement all logic in PHP
- Cursor must not move logic to the database
- PHP is the authoritative layer for all business rules

---

### **12.10 Database Is the Integrity Layer**

**⚠️ TRIGGERS ARE FORBIDDEN (MANDATORY). Triggers must NEVER exist.**

**Critical Rules:**
- Cursor must assume the PHP layer prevents all mismatches
- Cursor must **NOT** create triggers under any circumstances
- Cursor must **NOT** rely on database-level automation for timestamps
- Cursor must **ALWAYS** set timestamps explicitly in application code
- Database integrity is maintained by PHP, never by triggers

**See [NO_TRIGGERS_DOCTRINE.md](../doctrine/NO_TRIGGERS_DOCTRINE.md) for complete requirements.**

---

### **12.11 Summary of Agent Dependency Doctrine**

**For Cursor:**
- **Always validate in PHP** before writing to database
- **Always resolve agent codes to IDs** before writing dependencies
- **Always write both code and ID** in `agent_dependencies` table
- **Always check dependencies** before routing or activation
- **Always set timestamps explicitly** in INSERT/UPDATE statements (YMDHIS UTC format)
- **Never bypass PHP validation**
- **Never create triggers** (TRIGGERS ARE FORBIDDEN - MANDATORY)
- **Never suggest triggers**
- **Never rely on database-level timestamp automation**
- **Never write raw IDs** without resolving codes
- **Never treat MySQL as an application**

**See [NO_TRIGGERS_DOCTRINE.md](../doctrine/NO_TRIGGERS_DOCTRINE.md) for complete requirements.**

**This doctrine is MANDATORY for all code generation.**

---

## **🟩 13. ROSE (Rosetta Stone Translator)**

**File:** `lupo-includes/class-rose.php`

**Purpose:**
- ROSE is the **ONLY expressive agent** in Lupopedia
- ROSE does **NOT** reason, govern, interpret context, or make decisions
- ROSE rewrites short dialog summaries into persona-styled inline dialog blocks
- ROSE is **NOT** interacted with directly by users — invoked only by other agents

**Input Rules:**
- ROSE receives **ONLY**: `summary_text` (max 1000 chars), `persona_name`, `target`, `mood_color`
- ROSE does **NOT** receive: full dialog body, metadata, agent context, reasoning, system state

**Persona Handling:**
- ROSE supports **dynamic personas** — no predefined persona list
- ROSE uses **EXACTLY** the persona string provided by the calling agent
- ROSE does **NOT** validate persona names or infer personas

**Output Rules:**
- ROSE outputs **ONLY** a YAML inline dialog block
- Maximum output length for message: 1000 characters
- ROSE must **NOT** output: prose, paragraphs, reasoning, explanations, multi-block responses

**Behavioral Restrictions:**
- ROSE must **NOT**: reason, analyze, govern, validate, choose personas, interpret context, modify meaning
- ROSE is a pure style-rendering engine

**Critical Rule:**
- Cursor must **NOT** turn ROSE into an LLM agent
- Cursor must **NOT** add thinking or reasoning to ROSE
- ROSE is a translator, not a conversational agent
- ROSE uses `class-rose.php` exclusively

---

## **🟩 14. CHRONOS (Temporal Coordination Engine)**

**File:** `lupo-includes/class-chronos.php`

**Purpose:**
- CHRONOS is **kernel**
- CHRONOS does **NOT** speak to users
- CHRONOS handles time, sequencing, coordination
- CHRONOS uses `timestamp_ymdhis` exclusively

**Doctrine:**
- All stored timestamps are `BIGINT(14)` UTC (`YYYYMMDDHHIISS`)
- Display-time conversion uses `actor_profiles.timezone_offset`
- Local recurring events may store local-time BIGINTs (rare exception)

**Current State:**
- CHRONOS currently contains placeholders for future logic
- CHRONOS is a skeleton that will be filled in later

**Critical Rule:**
- Cursor must **NOT** rewrite CHRONOS into a scheduler framework
- Cursor must **NOT** add conversational interfaces to CHRONOS
- Cursor must **NOT** fill in placeholder logic unless explicitly instructed
- CHRONOS is kernel, not a persona

---

## **🟩 15. UI Direction**

### **14.1 Mockup Notes**

**The mockup at `mockup_chat.htm` is a rough draft.**

**The final UI will use:**
- collections
- top-navigation tabs
- channel-based chat
- actionable messages
- agent-filtered visibility

**Critical Rule:**
- Cursor must **NOT** assume the mockup is final
- Cursor must **NOT** generate UI code unless explicitly asked
- The mockup is a reference, not a specification
- UI direction is still evolving

---

## **🟩 16. Actionable Chat Messages**

### **15.1 Action Payloads**

**Some messages contain actions:**
- tags
- workflows
- assignments

**Action Storage:**
- Actions are stored in existing tables
- No new schema is required
- Actions are executed in the application layer
- Dialog messages may contain action payloads

**Critical Rule:**
- Cursor must **NOT** create new tables unless explicitly instructed
- Actions use existing schema
- Actions are handled at the application layer
- Dialog messages can contain action metadata

---

## **🟩 17. Agent Registry & System Services**

### **10.1 System Services (Not Talkable Agents)**

Cursor must understand the following system services:

**IRIS**
- **Layer:** `LLM_GATEWAY`
- **Not a talkable agent**
- **System service only**
- Provides LLM communication, not conversational interface

**JANUS, HEIMDALL, MIGRATOR, INDEXER**
- **Layer:** `SYSTEM_SERVICE`
- **Not talkable**
- **Internal OS components**
- Do not have personas or conversational interfaces

**LOGWATCH**
- **Layer:** `system_ops`
- **Talkable**
- Used for log tailing and system inspection
- Has conversational interface for debugging

**Critical Rule:**
- Cursor must **NOT** treat system services as personas
- System services are infrastructure, not agents
- Only talkable agents have personas and conversational interfaces

---

## **🟩 18. Doctrine Over Frameworks**

### **17.1 Lupopedia's Doctrines Are Superior**

Cursor must treat Lupopedia's doctrines as **superior to:**

- Laravel conventions
- Symfony conventions
- Node/Express patterns
- React/Next.js patterns
- ORM patterns
- MVC patterns
- microservice patterns

**Critical Rule:**
- Cursor must **NOT** attempt to "fix," "modernize," or "refactor" Lupopedia into a framework-shaped architecture
- Lupopedia's doctrines are intentional and superior for a Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE)
- Framework patterns are for web apps, not Web-Organized Linked Federated Intelligent Ecosystems (WOLFIE)

---

## **🟦 19. Cursor's Role**

### **18.1 What Cursor Should Do**

Cursor's job is to:

- **Respect doctrine** — Follow all architectural doctrines
- **Follow schema** — Use exact field names and table structures
- **Generate code that fits the OS** — Code must align with Lupopedia architecture
- **Avoid framework assumptions** — Don't assume Laravel, Symfony, React patterns
- **Avoid timestamp rewrites** — Use `timestamp_ymdhis` class exclusively
- **Avoid ORM patterns** — Write explicit SQL, not ORM abstractions
- **Avoid magic abstractions** — Keep code explicit and transparent
- **Avoid altering routing logic** — Preserve HERMES + CADUCEUS routing
- **Avoid altering agent layers** — Respect kernel/system/persona distinctions
- **Avoid altering dialog flow** — Preserve DialogManager pipeline

### **18.2 What Cursor Must NOT Do**

Cursor must **NOT:**

- Attempt to "modernize" Lupopedia
- Suggest framework-based solutions
- Rewrite timestamps to DATETIME/TIMESTAMP
- Create ORM abstractions
- Bypass DialogManager
- Modify HERMES routing logic
- Treat kernel agents as personas
- Add conversational interfaces to system services
- Rewrite ROSE into an LLM agent
- Rewrite CHRONOS into a scheduler framework
- Generate UI code without explicit instruction
- Create new tables without explicit instruction
- Reshape Lupopedia into a web app architecture

**Critical Rule:**
- **Cursor must operate inside the architecture, not reshape it.**
- Lupopedia is an operating system, not a web application.
- Doctrine > Framework conventions.

---

## **🟦 20. Critical Rules for Cursor**

### **File Locations:**
- All classes must remain in `lupo-includes/`
- File names must match class names exactly
- No renaming or restructuring without explicit instruction

### **Class Names:**
- `HERMES` — Routing layer
- `Caduceus` — Mood signal helper (note: lowercase 'c')
- `IRIS` — LLM gateway
- `DialogManager` — Central dispatcher
- `THOTH` — Truth engine
- `WOLFMIND` — Memory subsystem

### **Method Signatures:**
- Cursor must preserve existing method signatures
- Cursor must not change parameter types or return types
- Cursor must not remove required methods

### **Database Fields:**
- Cursor must use exact field names from schema
- Cursor must not rename or restructure fields
- Cursor must respect table relationships (without foreign keys)

### **Routing Doctrine:**
- Cursor must not override HERMES routing logic
- Cursor must preserve CADUCEUS formula
- Cursor must not bypass DialogManager

### **Memory Flow:**
- Cursor must use WOLFMIND for all memory operations
- Cursor must not create alternative memory systems
- Cursor must respect progressive enhancement (MySQL → Postgres)

### **Dialog Flow:**
- Cursor must preserve DialogManager flow
- Cursor must not bypass any step in the flow
- Cursor must maintain thread continuity

### **Timestamp Doctrine:**
- Cursor must use `BIGINT(14) UNSIGNED` with `YYYYMMDDHHIISS` format
- Cursor must use `timestamp_ymdhis` class for all operations
- Cursor must never suggest DATETIME, TIMESTAMP, epoch, or VARCHAR timestamps
- Cursor must store all timestamps in UTC (except local recurring events)
- Cursor must not convert UTC to local time for storage

### **System Services:**
- Cursor must not treat system services (IRIS, JANUS, HEIMDALL, etc.) as talkable agents
- Cursor must respect layer distinctions (LLM_GATEWAY, SYSTEM_SERVICE, system_ops)

### **Agent Layers:**
- Cursor must not treat kernel agents (SYSTEM, CAPTAIN, THOTH, CHRONOS, etc.) as personas
- Cursor must not add conversational interfaces to kernel or system services
- Only persona agents participate in chat

### **Agent Dependencies:**
- Cursor must validate all dependencies in PHP (never rely on database)
- Cursor must always resolve agent codes to IDs before writing dependencies
- Cursor must always write both `depends_on_agent_code` and `depends_on_agent_id`
- Cursor must never write raw IDs without resolving codes
- Cursor must never bypass PHP dependency resolver
- Cursor must check dependencies before routing or activation
- Cursor must enforce CARMEN dependencies (AGAPE, ERIS, METIS) in PHP
- Cursor must respect `is_internal_only` flag in routing logic
- Cursor must never treat MySQL as an application (no business logic in database)

### **ROSE and CHRONOS:**
- Cursor must not turn ROSE into an LLM agent (ROSE is a translator)
- Cursor must not rewrite CHRONOS into a scheduler framework (CHRONOS is kernel)
- Cursor must not fill in placeholder logic unless explicitly instructed

### **UI and Actions:**
- Cursor must not generate UI code unless explicitly asked
- Cursor must not create new tables unless explicitly instructed
- Actions use existing schema and are handled at the application layer

### **No New Subsystems:**
- Cursor must not invent new subsystems unless explicitly instructed
- Cursor must use existing classes for their intended purposes
- Cursor must not duplicate functionality

### **Doctrine Over Frameworks:**
- Cursor must not attempt to "modernize" or "refactor" Lupopedia into a framework-shaped architecture
- Lupopedia's doctrines are superior to framework conventions
- Lupopedia is an operating system, not a web application

---

## **🟦 21. Summary**

This document defines the **canonical architecture** for Lupopedia as of January 2026.

**Core Classes:**
- **HERMES** — Routing layer
- **CADUCEUS** — Mood signal helper
- **IRIS** — LLM gateway (faucet)
- **DialogManager** — Central dispatcher
- **THOTH** — Truth engine (skeleton)

**Supporting Classes:**
- **WOLFMIND** — Memory subsystem
- **ROSE** — Rosetta Stone translator (not an LLM agent)
- **CHRONOS** — Temporal coordination engine (kernel, not a persona)
- **timestamp_ymdhis** — Timestamp utility class

**Critical Principles:**
- **Lupopedia is a Web-Organized Linked Federated Intelligent Ecosystem (WOLFIE), not a web app** — No frameworks, ORMs, or magic abstractions
- HERMES routes, IRIS thinks, WOLFMIND remembers, DialogManager orchestrates
- CADUCEUS is a pure function (no side effects)
- IRIS is NOT an agent (it's a gateway)
- DialogManager is the central dispatcher (must not be bypassed)
- THOTH is a skeleton (TODO blocks must remain untouched)
- ROSE is a translator, not a thinker
- CHRONOS is kernel, not a persona
- **All timestamps use BIGINT(14) UNSIGNED with YYYYMMDDHHIISS format (UTC)**
- **System services are NOT talkable agents**
- **Kernel agents do NOT participate in dialog**
- **Only persona agents speak in chat**

**For Cursor:**
- Treat all of the above as **canonical architecture**
- Respect file locations, class names, method signatures
- Follow routing, memory, dialog, and timestamp doctrines
- Do not invent new subsystems unless instructed
- Preserve existing functionality and flow
- **Never suggest DATETIME, TIMESTAMP, epoch, or VARCHAR timestamps**
- **Always use timestamp_ymdhis class for timestamp operations**
- **Operate inside the architecture, do not reshape it**
- **Lupopedia is an operating system, not a web application**

This is the **authoritative reference** for all future work on Lupopedia.

---

## **🟦 FINAL DIRECTIVE FOR CURSOR**

Cursor must treat all of the above as **canonical architecture** for Lupopedia.

**Cursor must NOT:**
- Replace `BIGINT(14) UNSIGNED` timestamps with DATETIME/TIMESTAMP
- Introduce DATETIME, TIMESTAMP, epoch integers, ISO8601 strings, or VARCHAR timestamps
- Convert UTC to local time for storage (except local recurring events)
- Remove `timezone_offset` from `actor_profiles` (used for display only)
- Rewrite timestamp logic (use `timestamp_ymdhis` class)
- Treat system services (IRIS, JANUS, HEIMDALL, etc.) as talkable agents
- Alter routing doctrine (HERMES rules)
- Alter dialog flow (DialogManager pipeline)
- Auto-generate ontology logic in THOTH (leave TODO blocks untouched)

**This architecture is intentional, doctrine-aligned, and permanent.**

---

*Last Updated: January 2026*  
*Version: GLOBAL_CURRENT_LUPOPEDIA_VERSION*  
*Author: Captain Wolfie*

---

## Related Documentation

- **[WOLFMIND Doctrine](../doctrine/WOLFMIND_DOCTRINE.md)** - Complete memory subsystem documentation and progressive enhancement rules
- **[No Triggers Doctrine](../doctrine/NO_TRIGGERS_DOCTRINE.md)** - Mandatory prohibition of database triggers and timestamp automation
- **[No Stored Procedures Doctrine](../doctrine/NO_STORED_PROCEDURES_DOCTRINE.md)** - Database logic restrictions and PHP-first validation
- **[Agent Classification](../doctrine/AGENT_CLASSIFICATION.md)** - Agent classification system and routing identity metadata
- **[Timestamp Doctrine](../doctrine/TIMESTAMP_DOCTRINE.md)** - Canonical BIGINT(14) YMDHIS UTC timestamp format requirements
- **[Database Philosophy](DATABASE_PHILOSOPHY.md)** - Core principles behind Lupopedia's database design
- **[Protocol Documentation](../protocols/)** - Communication protocols and federation standards

