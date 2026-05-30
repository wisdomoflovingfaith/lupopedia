# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/doctrine/AGENT_CLASSIFICATION.md"
  file_hash: "8b8943845d6e010fabf84e90ba821271421c69e1ab53d965439bfe403fe3128e"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\channels\doctrine\AGENT_CLASSIFICATION.md"
  file_hash: "5969d38ef7b974d9a298b97df020c65f964ab5a1b1154eed3d0b7ee49508dfc3"
  file_path_from_root: "docs\channels\doctrine\AGENT_CLASSIFICATION.md"
  file_hash: "8fe75f87640e16d2b288dddc3dd49233a13357dc7ee1144b043e582d4d5328b3"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for AGENT_CLASSIFICATION.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "agent_classificationmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-08
author: Wolfie (Eric Robin Gerdes)
architect: Captain Wolfie
dialog:
  speaker: cursor
  target: documentation
  message: "Created AGENT_CLASSIFICATION.md doctrine defining identity-level classification requirements for all agents in Lupopedia 3.0.1."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "agents", "classification"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "agents"]
file:
  title: "Agent Classification Doctrine"
  description: "Identity-level classification requirements for all Lupopedia agents: classification_json in database and filesystem"
  version: "3.0.1"
  status: published
  author: "Captain Wolfie"
---

# Agent Classification (Lupopedia 3.0.1)

Each agent **MUST** define a `classification_json` object in **BOTH**:
1. `agent_registry.classification_json` (database)
2. `agents/[AGENT_NAME]/classification.json` (filesystem)

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
agents/[AGENT_NAME]/
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
*Version: 3.0.1*  
*Author: Captain Wolfie*
