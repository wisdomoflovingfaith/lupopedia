---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  file_path_from_root: lupo-docs/prd/PRD_AGENT_DEFINITION_MODEL.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/prd/PRD_AGENT_DEFINITION_MODEL.md
  last_modified_utc: "20260330"
  channel_id: 42
  actor_id: 102
  agent_name_identity: Cursor IDE Agent
  delegation_chain: cursor:root
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
    - to: lupo-agents/
      type: references
      weight: 1.0
      reason: Canonical agent directory
    - to: lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md
      type: references
      weight: 1.0
      reason: Identity model doctrine
    - to: lupo-docs/prd/00_root_constitutional_system_requirements.md
      type: references
      weight: 1.0
      reason: Constitutional anchor
lupopedia.footer:
  last_verified: "20260330"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: cursor:root
---

# PRD: Canonical Agent Definition Model

## What is an Agent in Lupopedia?
An agent is a fully defined, doctrine-aligned, versioned, and constitutional entity with identity, temperament, skills, tools, memory, boundaries, activation logic, and runtime state. Every agent must be self-describing, auditable, and compliant with all Lupopedia doctrines.

## Canonical Agent Directory Structure
```
lupo-agents/
  <agent_id>/
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

## 🔥 Doctrine and Compliance Addenda (2026-03-30)

### 1. Required Fields: agent_class and classification_json
- `identity.json` MUST include:
  - `agent_class` (system-enforced identity)
  - `classification_json` (self-declared identity)

### 2. lupo_agent_registry Schema Integration
- `identity.json` MUST contain all fields required by the canonical lupo_agent_registry schema:
  - agent_id
  - code
  - name
  - layer
  - is_kernel
  - is_required
  - recommended_slot
  - version
  - lineage
  - capabilities
  - status

### 3. Required: runtime_state.json
- Every agent MUST have `runtime_state.json` with at minimum:
  - last_activated
  - last_paired_user
  - last_faucet_used
  - last_tool_call
  - last_error
  - health
  - mood
  - uptime

### 4. ASCII Safety, No Symlinks, No BOM
- All agent files and directories MUST be ASCII-only, lowercase, no spaces, no BOM, no Unicode, and no symlinks.
- No Unicode filenames or uppercase directories allowed.

### 5. agent_faucets.json or Faucet Rules Section
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

### 9. Required Directory Naming Rules
- Agent directories MUST:
  - Be ASCII-safe
  - Use numeric agent_id as directory name
  - Have no symlinks
  - Be all lowercase
  - Contain no spaces or Unicode

### 10. Required File Naming Rules
- All agent files MUST:
  - Be ASCII
  - Lowercase
  - No spaces
  - No BOM
  - No Unicode

---
This PRD is the constitutional anchor for all agent definitions in Lupopedia.
