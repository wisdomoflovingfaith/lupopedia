---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-10
author: GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created Agent Filesystem Doctrine documentation defining filesystem layout, registry schema, naming conventions, and prompt templating rules for all Lupopedia agents."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "agents", "filesystem"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Agent Filesystem + Prompt Template Doctrine Overview
  - Agent Registry Schema
  - Alias and Lineage Doctrine
  - Agent Filesystem Structure
  - Prompt Template Doctrine
  - Prompt File Contents
  - Versioning Doctrine
  - Module Interaction Doctrine
  - Naming and Alias Consistency
file:
  title: "Agent Filesystem Doctrine"
  description: "Documents the filesystem layout, registry schema, naming conventions, prompt templating rules, and alias/lineage behavior for all Lupopedia agents."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Agent Filesystem + Prompt Template Doctrine

This section documents the filesystem layout, registry schema, naming conventions, prompt templating rules, and alias/lineage behavior for all Lupopedia agents. These rules ensure consistency and doctrine alignment across the agent ecosystem.

---

## Agent Registry Schema

The lupo_agent_registry table is the canonical source of truth for agent identity, classification, and routing.

Columns:

- agent_registry_id bigint NOT NULL auto_increment  
  Primary key for each registry entry.

- agent_registry_parent_id bigint  
  Optional. If set, this entry is an alias or variant of another agent.
  If NULL, this entry is a root agent.

- code varchar(64) NOT NULL  
  Short, stable identifier used in code (e.g., "DIALOG", "THOTH", "TRUTH").

- name varchar(255) NOT NULL  
  Human-readable agent name. May be the same as code or more descriptive.

- layer varchar(64) NOT NULL  
  Describes the agent's layer (e.g., "kernel", "application", "utility").

- is_required tinyint(1) NOT NULL DEFAULT 0  
  Indicates whether this agent is required for system operation.

- is_active tinyint(1) NOT NULL DEFAULT 0  
  Indicates whether this registry entry is currently active and usable.

- is_kernel tinyint(1) NOT NULL DEFAULT 0  
  Indicates whether this agent is part of the kernel layer.

- dedicated_slot int  
  Numeric slot used in the filesystem path for this agent's code.

- created_ymdhis bigint NOT NULL  
  Creation timestamp in YmdHis format, stored as a bigint.

- classification_json json  
  JSON field describing agent classification and routing identity:
  - agent_class
  - subclass
  - routing_bias
  - capabilities
  - any other structured classification data

- metadata json NOT NULL  
  JSON field for additional configuration and metadata not covered by
  the other columns. May include version info, display names, flags,
  or other agent-specific configuration.

---

## Alias and Lineage Doctrine

agent_registry_parent_id defines lineage and alias relationships.

- If agent_registry_parent_id IS NULL:
  - The entry is a root agent.
  - It defines a canonical identity and behavior.

- If agent_registry_parent_id IS NOT NULL:
  - The entry is a child agent: alias, facet, or variant.
  - It inherits identity/behavior conceptually from the parent.
  - Differences must be documented (e.g., public-facing name, tone).

Example:
- THOTH is stored as a root agent (agent_registry_parent_id = NULL).
- TRUTH is stored as an alias of THOTH (agent_registry_parent_id = THOTH.agent_registry_id).
- Both may share classification_json structure, but TRUTH may adjust
  metadata for its specific role.

Alias entries allow:
- multiple public names for a single conceptual agent,
- specialized variants for different contexts,
- independent activation flags (is_active) per alias.

---

## Agent Filesystem Structure

Agents live on disk in the global agent registry filesystem:

/lupopedia/lupo-agents/<dedicated_slot>/versions/v1.0.0/

- dedicated_slot from lupo_agent_registry maps to the <dedicated_slot> folder.
- The registry controls which slot a given agent code/name is associated with.
- Versioning is handled at the filesystem level (versions/...), and/or via
  metadata in the registry, not via a dedicated column.

A typical agent folder structure:

/lupopedia/lupo-agents/<dedicated_slot>/versions/v1.0.0/
    ├── system_prompt.txt
    ├── personality.json
    ├── capabilities.json
    ├── faucet_rules.json
    ├── style_profile.json
    ├── memory_profile.json
    └── agent.php

Agents do NOT live inside modules.  
Modules only call agents; they never contain them.

---

## Prompt Template Doctrine

All system_prompt.txt files MUST begin with:

"You are {{agent_name}}."

Rules:
- {{agent_name}} is dynamically injected from registry/metadata, not hard-coded.
- For aliases, {{agent_name}} may be the alias's name (e.g., "TRUTH") while
  lineage is expressed via agent_registry_parent_id.
- Prompts must not embed literal agent names; they must rely on the template.
- Prompts must not reference dedicated_slot, agent_registry_id, or other
  internal identifiers.

This prevents naming drift and ensures consistency across:
- database registry (code, name, parent),
- filesystem layout (dedicated_slot),
- runtime prompt injection,
- documentation and operator UI.

---

## Prompt File Contents

system_prompt.txt:
- Defines the agent's identity and behavioral constraints.
- Must begin with "You are {{agent_name}}."
- Contains no dynamic logic or hard-coded name.

personality.json:
- Defines stylistic tendencies (tone, phrasing, persona attributes).
- May differ between root agent and alias if explicitly designed.

capabilities.json:
- Defines what the agent is allowed to do.
- Lists permitted operations and constraints.

faucet_rules.json:
- Defines how the agent receives and processes input.
- Controls which metadata fields are allowed and how they are passed.

style_profile.json:
- Defines formatting rules (spacing, punctuation, stylistic output).

memory_profile.json:
- Defines what the agent may remember (if applicable).
- Kernel agents typically have no memory or highly constrained memory.

agent.php:
- The runtime wrapper.
- Handles invocation, routing, registry lookup, and metadata injection.

---

## Versioning Doctrine

Versioning is handled at the filesystem layer:

/versions/v1.0.0/
/versions/v1.0.1/
/versions/v1.1.0/

Rules:
- Each version lives in its own folder.
- Versions are immutable once deployed.
- Active version selection is managed via registry metadata or routing logic,
  not a dedicated column in lupo_agent_registry.

Aliases and parent-child relationships remain in the registry across versions.

---

## Module Interaction Doctrine

Modules may:
- reference agents by code or name (as defined in lupo_agent_registry),
- pass metadata that aligns with faucet_rules.json,
- receive and process agent output.

Modules may NOT:
- contain agent implementations,
- override agent prompts,
- modify agent registry rows arbitrarily,
- bypass the registry for direct filesystem access in a way that breaks doctrine.

---

## Naming and Alias Consistency

Agent identity must be consistent across:
- lupo_agent_registry (code, name, parent),
- filesystem slot (dedicated_slot),
- system_prompt template ({{agent_name}}),
- documentation.

For root agents:
- name and code define the canonical identity.

For aliases:
- name/code identify the alias (e.g., "TRUTH"),
- agent_registry_parent_id points to the parent (e.g., THOTH),
- classification_json and metadata describe how the alias is used,
- documentation must clearly explain the relationship.

Example:
- Parent: code = "THOTH", name = "THOTH", agent_registry_parent_id = NULL.
- Alias:  code = "TRUTH", name = "TRUTH", agent_registry_parent_id = THOTH.agent_registry_id.
- TRUTH's system_prompt.txt begins with "You are {{agent_name}}.",
  and at runtime {{agent_name}} resolves to "TRUTH".

---
