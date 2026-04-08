---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  file_path_from_root: lupo-docs/prd/PRD_AGENT_DEFINITION_MODEL.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/prd/PRD_AGENT_DEFINITION_MODEL.md
  last_modified_utc: "20260401180000"
  channel_id: 42
  actor_id: 2
  agent_name_identity: "LILITH"
  delegation_chain: "lilith:audit|cursor:implementation"
  artifact_type: prd
  artifact_kind: agent_definition
  purpose: Canonical PRD for agent definition, structure, and doctrine in Lupopedia
  tags:
    - prd
    - agent
    - doctrine
    - constitutional
    - structure
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: lupo-agents/
      type: references
      weight: 1.0
      reason: Canonical agent directory
    - to: lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md
      type: references
      weight: 1.0
      reason: Identity model doctrine
lupopedia.footer:
  last_verified: "20260331153000"
  verified_by:
    actor_id: 2
    agent_name_identity: "LILITH"
  orchestrator: "lilith:audit|cursor:implementation"
---

> **DEPRECATED (2026-04-01):** On-disk **canonical** agent layout is defined in [`01_core_identity.md`](01_core_identity.md): `agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt` under `lupo-agents/{agent_key}/`. This file remains **non-canonical** reference for optional concepts (`soul.txt`, `memory.json`, `boundaries.json`, `activation/`, `identity.json` vs `agent.json`) until any unique material is merged into `01_core_identity.md`. For constitutional rules see [`00_root_constitutional_system_requirements.md`](00_root_constitutional_system_requirements.md) §5.1, §5.5, §6.1, §9.16.

# PRD: Agent Definition Model (deprecated file layout)

## What is an Agent in Lupopedia?
An agent is a fully defined, doctrine-aligned, versioned, and constitutional entity with identity, temperament, skills, tools, memory, boundaries, activation logic, and runtime state. Every agent must be self-describing, auditable, and compliant with all Lupopedia doctrines.

## Canonical Agent Directory Structure
```
lupo-agents/
  {agent_key}/
    identity.json
    soul.txt
    system_prompt.txt
    skills.json
    tools.json
    # memory: root memory node at lupo-memory/YYYY/MM/{memory_slug}.json (4.0.96+; memory.json DEPRECATED)
    capabilities.json
    boundaries.json
    activation/
      default.txt
      paired_user.txt
      escalation.txt
    versions/
      v<version>/
        identity.json
        system_prompt.txt
        skills.json
        tools.json
        # memory: root memory node at lupo-memory/YYYY/MM/{memory_slug}.json (4.0.96+; memory.json DEPRECATED)
        capabilities.json
        boundaries.json
```

## Required Files and Fields

### identity.json
- agent_id
- agent_name
- agent_code
- department
- is_kernel
- is_required
- version
- provenance
- creator_actor_id
- creation_ymdhis
- agent_class
- classification_json

### soul.txt
- Core temperament (e.g., adversarial, heterodox, doctrine-enforcing)

### system_prompt.txt
- Reasoning style
- Dialogue style
- Doctrine enforcement
- Conflict/ambiguity handling

### skills.json
- skill name
- skill description
- activation domains
- skill level
- constraints

### tools.json
- allowed tools
- tool usage rules
- tool constraints

### Root Memory Node (4.0.96+) — replaces memory.json

> **DEPRECATED:** `memory.json` is no longer the canonical memory storage for agents or actors.

Memory is stored as a root memory node at `lupo-memory/YYYY/MM/{memory_slug}.json`, registered in `lupo_memory_nodes`, and linked via `lupo_edges`. The following concepts previously in `memory.json` are now expressed as node properties or edge metadata:

- memory boundaries
- retention rules
- forbidden memory

### capabilities.json
- allowed actions
- forbidden actions
- escalation rules
- fallback rules

### boundaries.json
- forbidden behaviors
- forbidden topics
- constitutional constraints

### activation/
- default.txt: default activation prompt
- paired_user.txt: paired user activation prompt
- escalation.txt: escalation activation prompt

### versions/
- v<version>/: snapshot of all above files for versioning and provenance

## Versioning and Provenance
- All agents must track version and provenance in identity.json and versions/.
- Changes to agent definition require a new version directory.

## Pairing and Department
- Pairing rules and department assignment must be explicit in identity.json and activation/.

## Agent Faucet Rules
- Each agent must define allowed faucet surfaces and tool usage in tools.json and capabilities.json.

## Runtime State
- Agents may have runtime state fields (e.g., last_activated, last_paired_user) tracked in a `runtime_state.json` (optional) or as memory nodes at `lupo-memory/YYYY/MM/{memory_slug}.json` (4.0.96+). `memory.json` is DEPRECATED.

## Compliance
- All agent files must be ASCII, no symlinks, no schema inference, no framework patterns.
- All fields and files are required unless doctrine explicitly allows omission.
- All agent files and directories MUST be ASCII-only, lowercase, no spaces, no BOM, no Unicode, and no symlinks.
- No Unicode filenames or uppercase directories allowed.
- Each agent MUST define faucet surfaces, types, constraints, and activation rules in either:
  - `agent_faucets.json` (preferred)
  - or a dedicated faucet rules section in `tools.json` (if not using a separate file)

### 6. boundaries.json: Constitutional Constraints
- `boundaries.json` MUST include all constitutional constraints from the root PRD.
- This file is the enforcement anchor for forbidden behaviors, topics, and actions.

### 7. activation/: Pairing Logic Rules
- The `activation/` directory MUST include explicit pairing logic, pairing constraints, and override rules.
- This is critical for agents with special pairing relationships (e.g., LILITH).

### 8. Required Agent Metadata in identity.json
- `identity.json` MUST include:
  - actor_version
  - agent_signature
  - agent_hash

### 9. Filesystem vs Database Separation

**DOCTRINE**: Clear separation of concerns between filesystem definitions and database runtime tracking.

**Filesystem (Source of Truth)**:
- Agent definitions in `lupo-agents/{agent_key}/` directories
- All configuration files (identity.json, skills.json, etc.)
- Version history in `versions/` subdirectories
- **Immutable**: Runtime state never stored in filesystem

**Database (Runtime Reflection)**:
- `lupo_agents` table: Minimal runtime metrics only
- `lupo_actor_*` tables: Actor capabilities, memory, skills, tools
- `lupo_agent_*` tables: Tool calls, faucets, heartbeats
- **Mutable**: Runtime state tracked in database

**Key Principle**: Filesystem defines WHAT an agent IS; database tracks HOW an agent RUNS.

### 10. Required Directory Naming Rules
- Agent directories MUST:
  - Be ASCII-safe
  - Use agent_key as directory name
  - Have no symlinks
  - Be all lowercase
  - Contain no spaces or Unicode

---
This PRD is the constitutional anchor for all agent definitions in Lupopedia.


---

## Context‑Typed, Status‑Aware, Directional Edged Memory Doctrine (4.0.96)

1. Memory in Lupopedia is represented as a directed graph of nodes and edges. 
  Each memory node is a first-class entity in the semantic network and may be 
  owned by actors, departments, auth_users, channels, federation nodes, or the 
  global system.

2. Every edge in the memory graph has FOUR dimensions:
  - **edge type** (the relationship)
  - **edge context** (the classification of the memory)
  - **edge status** (the epistemic support level)
  - **edge direction** (the traversal orientation)

3. **Edge Direction** defines whether the relationship is:
  - unidirectional (A → B)
  - bidirectional (A ↔ B)
  - restricted-direction (A → B but not B → A unless explicitly defined)
  Reverse traversal MUST NOT be inferred unless explicitly defined.

4. **Edge Type** defines the relationship between nodes, including but not 
  limited to:
  - influences
  - inherits
  - authored_by
  - observed_by
  - contradicts
  - supports
  - consolidates_from
  - refines
  - overrides

5. **Edge Context** defines the classification of the memory node. Context is 
  not based on the content of the memory, but on the structural support 
  provided by the graph. The primary context classifications are:
  - doctrine
  - experiential
  - system_generated
  - countermeasure_generated
  - summary
  - contradictory
  - deprecated

6. **Edge Status** defines the epistemic support level of the memory node:
  - **unsupported**: insufficient supporting edges; provisional memory.
  - **supported**: sufficient supporting edges; validated memory.
  - **needs_review**: conflicting, incomplete, or ambiguous edges requiring 
    agent or human intervention.

7. When `edge_status = 'needs_review'`, a **review_reason** MUST be provided. 
  This field explains *why* the edge requires review and *which agent* should 
  handle it. Examples include:
  - orphaned_edge
  - contradiction
  - new_doctrine
  - schema_drift
  - consolidation_candidate
  - integrity_unknown
  - human_escalation

  Agents use this field to determine their work queues:
  - ANUBIS handles: integrity_unknown, orphaned_edge
  - THOTH handles: schema_drift, contradiction, new_doctrine
  - KAIROS handles: consolidation_candidate
  - Human operator handles: human_escalation

8. Memory nodes may transition between statuses as edges are added, removed, 
  or reclassified. A node may move from unsupported → supported when 
  sufficient supporting edges accumulate.

9. Actors inherit memory edges from:
  - their department
  - their auth_user
  - their federation node
  - their assigned faucets
  - their assigned tasks

10. Memory traversal is context-aware and direction-aware. Actors may only 
   traverse edges permitted by their boundaries, department rules, auth_user 
   pairing, faucet assignments, and operational mode (live, simulation, 
   analysis).

11. No inference is allowed. All edges, contexts, statuses, directions, and 
   review reasons must be explicitly defined in PRDs, database rows, or 
   system-generated memory.

12. Memory is not a flat file. It is a structured, typed, classified, 
   status-aware, direction-aware graph. Traversal depth determines visible 
   memory; deeper traversal reveals more context, subject to boundary rules.

13. All changes to memory structure, edge types, edge contexts, edge statuses, 
   edge directions, or review reasons must be documented in PRDs and versioned.
```
