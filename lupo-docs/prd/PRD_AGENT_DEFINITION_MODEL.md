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
    memory.json
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
        memory.json
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

### memory.json
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
- Agents may have runtime state fields (e.g., last_activated, last_paired_user) tracked in memory.json or a runtime_state.json (optional).

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
