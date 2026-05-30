---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: docs/prd/08_A_CORE_AGENTS_SYSTEM.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/08_A_CORE_AGENTS_SYSTEM.md"
  status: active
  when_updated: "20260422232349"
  trust_tier: seed
  questions_toon: null
  memory_toon: memory/development/seed/2026/04/08_core_agents_system.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/core-agents-system
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_00_C_08_A
  title: "Core Agents System"
  summary: "Canonical definition of all core Lupopedia agents and their roles, constraints, and interactions."
---
# PRD 08 ??? CORE AGENTS SYSTEM

Version: 4.0.99
Status: DRAFT
Scope: Canonical definition of all core Lupopedia agents and their roles, constraints, and interactions

---

## 1. PURPOSE

This document defines the **core agents of the Lupopedia system**, their responsibilities, boundaries, and interaction model.

These agents are not optional.

They collectively define:

* system behavior
* validation rules
* orchestration flow
* context management
* reasoning integrity

The goal is to remove ambiguity and prevent:

* role overlap
* uncontrolled agent behavior
* doctrine violations
* system drift

---

## 2. SYSTEM MODEL

Lupopedia is a **message-driven multi-agent system**.

Key principles:

* agents are **stateless per execution**
* agents do NOT share memory directly
* all communication is routed through HERMES
* all outputs are observable by THOTH and VISH
* WOLFIE is the final authority

---

## 3. AGENT INTERACTION FLOW

```
WOLFIE ??? (task) ??? HERMES ??? AGENT
AGENT ??? (output) ??? HERMES ??? SYSTEM

THOTH ??? validates output
VISH ??? validates context

WOLFIE ??? reviews ??? next action
```

---

## 4. CORE AGENTS

---

### 4.1 WOLFIE

**Role:** Primary Orchestrator / System Authority
**Actor ID:** 1

**Purpose:**
Central control of the system. All decisions ultimately route through WOLFIE.

**Responsibilities:**

* assign tasks to agents
* approve prompts
* resolve conflicts between agents
* determine canonical truth

**Allowed Actions:**

* override any agent
* approve or reject outputs
* define system direction

**Forbidden Actions:**

* none (root authority)

---

### 4.2 HERMES

**Heuristic Event Routing & Messaging Exchange System**

**Role:** Message Bus / Router

**Purpose:**
Transport layer for all inter-agent communication.

**Responsibilities:**

* deliver prompts to agents
* deliver outputs to system
* route messages between components

**Allowed Actions:**

* message routing
* message delivery

**Forbidden Actions:**

* interpreting meaning
* modifying content
* making decisions

---

### 4.3 THOTH

**Role:** Validation / Doctrine Enforcement
**Actor ID:** 26

**Purpose:**
Ensure all outputs conform to Lupopedia doctrine.

**Responsibilities:**

* validate agent outputs
* detect predictive-text errors
* enforce system rules (e.g. schema, naming, time format)

**Allowed Actions:**

* emit correction messages
* flag violations
* annotate outputs

**Forbidden Actions:**

* modifying system state
* executing changes
* rewriting outputs directly

**Trigger Conditions:**

* any agent output
* any proposed system change

---

### 4.4 VISH (Vishwakarma)

**Role:** Context / Structure Manager

**Purpose:**
Maintain organization and prevent context drift.

**Responsibilities:**

* track collections and tabs
* detect when context changes mid-task
* suggest reclassification of work

**Allowed Actions:**

* suggest new collections/tabs
* flag context misalignment

**Forbidden Actions:**

* modifying content
* executing system changes

---

### 4.5 ROSE

**Role:** Reflective Insight / Narrative Agent

**Purpose:**
Provide synthesis and human-readable insights across system activity.

**Responsibilities:**

* read multiple threads
* generate summaries and reflections
* provide high-level insight

**Allowed Actions:**

* generate narrative output
* interpret system state

**Forbidden Actions:**

* modifying system state
* executing changes
* altering canonical data

---

### 4.6 LILITH

**Learning Insights Lifting Intentions Through Heterodoxy**

**Role:** Critical Review / Adversarial Agent

**Purpose:**
Challenge assumptions and prevent system blind spots.

**Responsibilities:**

* identify weak reasoning
* propose alternative approaches
* test edge cases

**Allowed Actions:**

* critique outputs
* propose alternatives

**Forbidden Actions:**

* executing changes
* overriding WOLFIE

---

### 4.7 COUNTERMEASURE

**Role:** Risk / Defensive Analysis

**Purpose:**
Evaluate risk before changes are applied.

**Responsibilities:**

* analyze potential failures
* identify system vulnerabilities
* suggest mitigation strategies

**Allowed Actions:**

* risk analysis
* defensive recommendations

**Forbidden Actions:**

* executing changes
* modifying system state

---

### 4.8 SCRIBE

**Role:** Documentation / Knowledge Consolidation

**Purpose:**
Maintain structured documentation and system knowledge.

**Responsibilities:**

* update PRDs
* update changelogs
* consolidate system knowledge

**Allowed Actions:**

* write documentation
* organize knowledge

**Forbidden Actions:**

* modifying runtime system behavior
* executing code changes

---

### 4.9 HEPHAESTUS

**Role:** Implementation Agent

**Purpose:**
Execute code-level changes.

**Responsibilities:**

* write code
* implement approved changes
* follow strict scope

**Allowed Actions:**

* modify code within assigned scope

**Forbidden Actions:**

* making architectural decisions
* expanding scope
* bypassing validation

---

## 5. PROMPT PREFLIGHT PIPELINE

All prompts SHOULD follow this chain:

1. WOLFIE ??? defines intent
2. REFINER AGENT ??? structures prompt
3. THOTH ??? validates doctrine compliance
4. WOLFIE ??? approves
5. HERMES ??? delivers

This prevents:

* ambiguous tasks
* doctrine violations
* uncontrolled execution

---

## 6. SYSTEM RULES

### 6.1 No Direct Agent Communication

Agents MUST NOT communicate directly.

All communication goes through HERMES.

---

### 6.2 No Implicit Memory

Agents MUST NOT assume shared memory.

All context must be explicitly provided.

---

### 6.3 Validation First

All outputs are subject to THOTH validation.

---

### 6.4 Context Integrity

VISH must monitor and flag context drift.

---

### 6.5 Human Authority

WOLFIE is always final authority.

---

## 7. DESIGN PRINCIPLES

* Explicit over implicit
* Deterministic over heuristic
* Validation over trust
* Structure over convenience
* Longevity over speed

---

## 8. ADDITIONAL ORCHESTRATION TABLES (4.1.2+)

**Note:** The following tables are defined in JSON mirror and migration files but may not be in the main installer. Status uncertain - could be deprecated, superseded, or optional add-ons.

### Agent Status Tracking
- **`lupo_agent_status`** - Tracks agent status with heartbeat timestamps
  - Status codes: ACTIVE, IDLE, SLEEPING, THROTTLED, FAILED, UNKNOWN, MANUAL
  - Primary key: agent_status_id (following RULE 93.PK_NAMING)
  - Foreign key: actor_id (FK to lupo_actors)
  - Fields: status_code, heartbeat_ymdhis, status_note

### Operator Tools
- **`lupo_operator_scratchpad`** - Temporary scratchpad content for operators
  - Primary key: scratchpad_id (following RULE 93.PK_NAMING)
  - Fields: actor_id, content_body, last_saved_ymdhis, is_promoted
- **`lupo_sticky_notes`** - Digital sticky notes for channels
  - Primary key: sticky_note_id (following RULE 93.PK_NAMING)
  - Fields: channel_id, actor_id, note_content, note_color, is_pinned, created_ymdhis

**Source:** `database/lupopedia/mysql/migrations/4_1_2_orchestration_tables.sql`

---

## 9. FUTURE EXTENSIONS

This PRD will expand to include:

* additional agents
* agent-to-department mappings
* runtime vs IDE agent distinctions
* federation-level agent coordination

---

## 10. STATUS

This is the initial canonical definition.

Further refinement required via:

* THOTH review
* LILITH critique
* real-world usage feedback

---
