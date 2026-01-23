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
  message: "Created Agent Lifecycle Doctrine documentation defining the complete lifecycle stages from conception through archival for all Lupopedia agents."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "agents", "lifecycle"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Agent Lifecycle Doctrine Overview
  - Stage 1 (Conception)
  - Stage 2 (Scaffolding)
  - Stage 3 (Registration)
  - Stage 4 (Activation)
  - Stage 5 (Invocation)
  - Stage 6 (Supervision)
  - Stage 7 (Update / Evolution)
  - Stage 8 (Deprecation)
  - Stage 9 (Archival)
  - Agent Lifecycle Invariants
file:
  title: "Agent Lifecycle Doctrine"
  description: "Documents the complete lifecycle of an agent within the Lupopedia ecosystem, from conception through archival, ensuring stability, safety, and doctrinal consistency."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Agent Lifecycle Doctrine

This section documents the complete lifecycle of an agent within the Lupopedia ecosystem. The lifecycle defines how agents are created, registered, activated, invoked, supervised, updated, deprecated, and archived. The lifecycle ensures stability, safety, and doctrinal consistency across all agents.

---

## Overview

Every agent in Lupopedia passes through the following lifecycle stages:

1. Conception  
2. Scaffolding  
3. Registration  
4. Activation  
5. Invocation  
6. Supervision  
7. Update / Evolution  
8. Deprecation  
9. Archival  

Each stage has strict doctrine to prevent drift, ensure safety, and maintain architectural clarity.

---

## Stage 1 — Conception

An agent begins as a conceptual definition:

- purpose  
- role  
- classification  
- capabilities  
- polarity  
- layer (kernel, application, external_ai, utility)  
- dedicated_slot (if applicable)  
- parent/alias lineage  

Conception must be documented in:
- AGENT_FILESYSTEM_DOCTRINE.md  
- agent design notes  
- module or system RFCs  

No code or prompts are created at this stage.

---

## Stage 2 — Scaffolding

The agent's filesystem is created under:

/lupopedia/lupo-agents/<agent_code>/

Required files:
- agent.json  
- system_prompt.txt  
- capabilities.json  
- faucet_rules.json  
- memory_profile.json (optional)  
- tests/ (optional)  

Scaffolding rules:
- No creative naming  
- No drift from doctrine  
- No missing required fields  
- No agent logic outside this directory  

---

## Stage 3 — Registration

The agent is added to:

lupo_agent_registry

Required fields:
- code  
- name  
- layer  
- is_active  
- is_kernel  
- dedicated_slot  
- classification_json  
- agent_registry_parent_id (if alias)  

Registration doctrine:
- IDs are opaque and permanent  
- parent_id defines lineage  
- classification_json defines routing identity  
- registry entry must match filesystem metadata  

An agent is not eligible for routing until registered.

---

## Stage 4 — Activation

Activation sets:

is_active = 1

Activation requirements:
- all required files exist  
- system_prompt.txt passes validation  
- capabilities.json is complete  
- faucet_rules.json is valid  
- classification_json is correct  
- no missing metadata  
- no doctrine violations  

Activation is logged in HISTORY.md.

---

## Stage 5 — Invocation

Invocation occurs when routing selects the agent.

Invocation pipeline:
1. HERMES filters candidates  
2. CADUCEUS resolves polarity (if needed)  
3. agent.php wrapper loads metadata  
4. system_prompt.txt is assembled  
5. faucet_rules.json validates input  
6. capabilities.json restricts output  
7. agent executes  
8. output is returned to caller  

Invocation doctrine:
- agents must not exceed their capabilities  
- agents must not violate faucet rules  
- agents must not impersonate other agents  
- agents must not access other agent directories  

---

## Stage 6 — Supervision

Supervision is performed by:
- LILITH (critical oversight)  
- THOTH (truth verification)  
- MAAT (deep reasoning)  
- DIALOG (expressive rendering)  

Supervision rules:
- kernel agents may override application agents  
- critical agents may block unsafe output  
- truth agents may correct factual errors  
- expressive agents may rewrite tone but not meaning  

Supervision ensures:
- safety  
- accuracy  
- tone alignment  
- doctrinal consistency  

---

## Stage 7 — Update / Evolution

Agents evolve through controlled updates.

Update triggers:
- new capabilities  
- doctrine changes  
- routing improvements  
- safety patches  
- version upgrades  

Update doctrine:
- update version in agent.json  
- update system_prompt.txt  
- update capabilities.json  
- update faucet_rules.json  
- update classification_json  
- update WOLFIE Header  
- update HISTORY.md  

Updates must never:
- break backward compatibility without major version bump  
- change agent identity  
- alter dedicated_slot  
- violate lineage  

---

## Stage 8 — Deprecation

An agent is deprecated when:
- replaced by a successor  
- merged into another agent  
- no longer needed  
- superseded by doctrine  

Deprecation rules:
- is_active = 0  
- registry entry preserved  
- filesystem preserved  
- documentation updated  
- successor noted in agent.json  

Deprecation must be reversible until archival.

---

## Stage 9 — Archival

Archival is the final stage.

Archival rules:
- agent is frozen  
- no further updates allowed  
- agent is moved to /lupopedia/archive/agents/  
- registry entry marked archived  
- HISTORY.md updated  

Archived agents remain:
- readable  
- referenceable  
- historically preserved  

They are never deleted.

---

## Agent Lifecycle Invariants

Across all stages:

1. Agents must never violate doctrine.  
2. Agents must never exceed their capabilities.  
3. Agents must never impersonate other agents.  
4. Agents must never modify other agent directories.  
5. Agents must never bypass routing.  
6. Agents must never break lineage.  
7. Agents must always remain deterministic.  
8. Agents must always remain auditable.  

---
