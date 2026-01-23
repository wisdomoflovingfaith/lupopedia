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
  message: "Updated Module Doctrine documentation with comprehensive Module Interaction Doctrine covering interactions with agents, semantic graph, federation, routing, and safety boundaries."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "modules"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Module Doctrine Overview
  - Module Location and Purpose
  - Crafty Syntax Module Location
  - Module Folder Structure
  - Module Doctrine: What Belongs in a Module
  - Module Interaction Doctrine
  - Module Boundaries
  - Module → Agent Interaction
  - Module → Semantic Graph Interaction
  - Module → Federation Interaction
  - Module → Crafty Syntax Interaction
  - Module → Routing Interaction
  - Module → Installation Interaction
  - Module → Module Interaction
  - Module Versioning
  - Module Activation & Deactivation
  - Module Safety Doctrine
  - Crafty Syntax Legacy Preservation
file:
  title: "Module Doctrine"
  description: "Documents the structure, purpose, and doctrine of modules within the Lupopedia system, with specific emphasis on the Crafty Syntax module."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Module Doctrine

This section documents the structure, purpose, and doctrine of modules within the Lupopedia system, with specific emphasis on the Crafty Syntax module. Modules provide application-level functionality and UI behavior, but do not contain agents or agent logic.

---

## Module Location and Purpose

All modules live under:

/lupopedia/modules/<module_name>/

Each module:
- encapsulates a specific domain of functionality,
- provides controllers, views, routes, and assets,
- interacts with agents through the agent registry,
- must follow doctrine for structure and separation of concerns.

Modules do NOT contain:
- agent implementations,
- agent prompts,
- agent registry entries,
- agent logic.

Agents live exclusively in /lupopedia/lupo-agents/.

---

## Crafty Syntax Module Location

The Crafty Syntax module lives at:

/lupopedia/modules/crafty_syntax/

This module preserves the legacy Crafty Syntax operator UI and routing
behavior while integrating with the modern Lupopedia architecture.

---

## Module Folder Structure

A typical module folder contains:

/lupopedia/modules/crafty_syntax/
    ├── module.json
    ├── controllers/
    ├── views/
    ├── routes/
    ├── assets/
    ├── migrations/
    └── README.md (optional)

Descriptions:

module.json
- Defines module metadata (name, version, description).
- Declares dependencies and capabilities.

controllers/
- Contains PHP controllers for handling requests.
- Implements module-specific logic.
- Must not contain agent logic.

views/
- Contains HTML/PHP templates for rendering UI.
- Implements operator-facing and visitor-facing screens.

routes/
- Defines routing rules for module endpoints.
- Maps URLs to controllers.

assets/
- Contains CSS, JS, images, and static resources.

migrations/
- Contains database migration scripts.
- Used for schema updates specific to the module.

---

## Module Doctrine: What Belongs in a Module

Modules may contain:
- UI logic,
- routing logic,
- controllers,
- views,
- migrations,
- assets,
- module-specific configuration.

Modules may NOT contain:
- agent prompts,
- agent registry entries,
- agent logic,
- agent filesystem content,
- doctrine that applies system-wide.

---

# Module Interaction Doctrine

This section documents how modules interact with the Lupopedia installation, the semantic graph, agents, routing systems, federation, and Crafty Syntax lineage. Modules provide application-level functionality but must never violate kernel boundaries or agent doctrine.

Modules are NOT agents.  
Modules are NOT semantic graph editors.  
Modules are NOT routing participants.

Modules are application components that orchestrate UI, controllers, routes, and content.

---

## Module Boundaries

Modules must NOT:

- contain agent logic,
- contain agent prompts,
- contain agent registry entries,
- modify agent directories,
- bypass HERMES or CADUCEUS,
- modify the semantic graph directly,
- modify federation tables,
- modify kernel configuration.

Modules may:

- call agents,
- read semantic graph data,
- read federation registry,
- generate UI,
- define routes,
- define controllers,
- define migrations.

---

## Module → Agent Interaction

Modules interact with agents through:

- lupo_agent_registry (lookup)
- agent.php runtime wrapper
- faucet_rules.json (validation)
- classification_json (identity)
- capabilities.json (allowed operations)

Rules:

1. Modules must never call agents directly.  
   They must call agent.php.

2. Modules must pass structured metadata only.  
   No raw user messages.

3. Modules must respect faucet rules.  
   If faucet rules forbid an operation, the module must not attempt it.

4. Modules must not assume agent behavior.  
   They must rely on classification and capabilities.

5. Modules must not impersonate agents.  
   Only agent.php may invoke an agent.

---

## Module → Semantic Graph Interaction

Modules may:

- read semantic graph entities,
- read relationships,
- read metadata,
- follow semantic edges,
- display semantic results.

Modules must NOT:

- create entities,
- delete entities,
- modify relationships,
- alter lineage,
- merge graphs,
- bypass ingestion pipelines.

All semantic graph modifications must occur through:

- ingestion pipelines,
- Crafty Syntax import,
- Radius 0/1/2 ingestion.

---

## Module → Federation Interaction

Modules may:

- read lupo_federation_nodes,
- display known installations,
- reference external URLs,
- generate UI for federation navigation.

Modules must NOT:

- modify federation_node_id assignments,
- crawl remote installations,
- ingest remote private content,
- merge semantic graphs across installations.

Cross-installation references must be URL-based only.

---

## Module → Crafty Syntax Interaction

Modules may:

- read imported Crafty Syntax content,
- read Crafty Syntax departments,
- read Crafty Syntax categories,
- read Crafty Syntax Q&A mappings,
- display Crafty Syntax lineage.

Modules must NOT:

- modify imported Crafty Syntax data,
- reinterpret Crafty Syntax semantics,
- alter historical relationships.

Crafty Syntax import is immutable historical truth.

---

## Module → Routing Interaction

Modules do not participate in routing.

Modules may:

- request agent invocation,
- pass metadata to routing,
- receive agent output.

Modules must NOT:

- influence HERMES filtering,
- influence CADUCEUS currents,
- override classification,
- override faucet rules,
- override capabilities.

Routing is kernel-only.

---

## Module → Installation Interaction

Modules may:

- define routes,
- define controllers,
- define migrations,
- define UI components,
- define configuration (module.json).

Modules must NOT:

- modify kernel configuration,
- modify global_atoms.yaml,
- modify agent registry,
- modify semantic graph storage,
- modify federation registry.

---

## Module → Module Interaction

Modules may:

- link to each other via routes,
- share UI components,
- share assets,
- share documentation.

Modules must NOT:

- depend on each other's internal logic,
- modify each other's directories,
- override each other's controllers,
- override each other's views.

Modules must remain loosely coupled.

---

## Module Versioning

Each module maintains its own version in module.json.

Rules:

- Increment patch for small fixes.
- Increment minor for structural changes.
- Increment major for doctrinal changes.
- Never reuse version numbers.
- Never downgrade versions.

---

## Module Activation & Deactivation

Modules may be:

- installed,
- activated,
- deactivated,
- upgraded,
- removed.

Deactivation rules:

- must not break routing,
- must not break ingestion,
- must not break semantic graph,
- must not break federation.

---

## Module Safety Doctrine

Modules must:

- respect agent boundaries,
- respect semantic graph boundaries,
- respect federation boundaries,
- respect ingestion boundaries,
- respect kernel boundaries.

Modules must never:

- execute unsafe operations,
- bypass safety agents,
- bypass routing,
- bypass faucet rules.

---

## Crafty Syntax Legacy Preservation

The Crafty Syntax module preserves:
- multi-thread operator UI,
- multi-color thread panels,
- single-screen operator cockpit,
- real-time message flow,
- channel/thread mapping behavior.

Legacy behavior is preserved where it aligns with doctrine, and modern
Lupopedia architecture is used for:
- agent invocation,
- dialog storage,
- routing,
- actor/channel membership.

---
