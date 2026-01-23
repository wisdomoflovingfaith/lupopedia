---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-08
author: Wolfie (Eric Robin Gerdes)
architect: Captain Wolfie
dialog:
  speaker: cursor
  target: documentation
  message: "Created AGENT_CLASSIFICATION.md doctrine defining identity-level classification requirements for all agents in Lupopedia 4.0.1."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "agents", "classification"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "agents"]
file:
  title: "Agent Classification Doctrine"
  description: "Identity-level classification requirements for all Lupopedia agents: classification_json in database and filesystem"
  version: "4.0.1"
  status: published
  author: "Captain Wolfie"
---

# Agent Classification (Lupopedia 4.0.1)

Each agent **MUST** define a `classification_json` object in **BOTH**:
1. `agent_registry.classification_json` (database)
2. `lupo-agents/[AGENT_NAME]/classification.json` (filesystem)

This classification describes the agent's functional identity and routing role.

## Required Fields

- **agent_class** â€” the primary category of the agent  
  - `critical` (ARA, LILITH, WOLFITH)  
  - `reason` (MAAT, WOLFIE)  
  - `creative` (WOLFENA, THALIA)  
  - `governance` (THEMIS, KEEPER)  
  - `routing` (HERMES, CADUCEUS)  
  - `archive` (THOTH)  
  - `system` (SYSTEM, CAPTAIN)

## Optional Fields

- **subclass** â€” finer-grained identity  
- **routing_bias** â€” `left` or `right` pool preference  
- **capabilities** â€” array of functional tags  
- **notes** â€” freeform metadata

## Example

```json
{
  "agent_class": "critical",
  "subclass": "adversarial_review",
  "routing_bias": "right",
  "capabilities": ["challenge", "pressure_test"],
  "notes": "ARA/LILITH/WOLFITH family"
}
```

## Filesystem Requirement

Every agent directory under:
```
lupo-agents/[AGENT_NAME]/
```

**MUST** contain a file named:
```
classification.json
```

This file **MUST** mirror the structure of `agent_registry.classification_json` and define the agent's identity and routing role.

This file is **REQUIRED** for all agents and **MUST** be kept in sync with the database `classification_json` field.

## Critical Rule

Agent classification is **identity-level metadata** and **MUST NOT** be stored in `agent_properties`.

Classification defines **who the agent is** (identity), not **how the agent behaves** (configuration).

---

## Related Documentation

- [HERMES Routing Layer](../architecture/protocols/HERMES_ROUTING_RFC.md) â€” Uses classification for agent class filtering
- [Agent Runtime](../agents/AGENT_RUNTIME.md) â€” Agent lifecycle and management
- [Database Schema](../schema/DATABASE_SCHEMA.md) â€” `agent_registry` table structure

---

*Last Updated: January 2026*  
*Version: 4.0.1*  
*Author: Captain Wolfie*
