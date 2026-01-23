---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
channel_key: system/kernel
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-10
author: GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created Semantic Graph Doctrine documentation defining the semantic graph architecture, installation sovereignty, entities, relationships, federation boundaries, and agent interaction with the graph."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "semantic", "graph"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Semantic Graph Doctrine Overview
  - Installation Sovereignty
  - Federation Registry
  - Semantic Entities (Graph Elements)
  - Relationships (Semantic Edges)
  - Semantic Inheritance
  - Crafty Syntax Lineage (Per Installation)
  - Q&A Semantic Mapping
  - Radius-Based Semantics
  - Cross-Installation References
  - Semantic Graph Storage
  - Agent Interaction with the Graph
  - Semantic Graph in Routing
  - Semantic Graph in Search
  - Semantic Graph in Navigation
  - Federation & The Semantic Graph
file:
  title: "Semantic Graph Doctrine"
  description: "Documents the semantic graph architecture of a Lupopedia installation, including entities, relationships, federation boundaries, storage, and interaction with agents, routing, search, and navigation."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Semantic Graph Doctrine

This section documents the semantic graph architecture of a Lupopedia installation. Each installation maintains its own independent semantic graph representing its local content, relationships, lineage, and meaning. The semantic graph is not global. It is local to the installation (federation_node_id = 1).

Federation connects installations, not semantic graphs.

---

## Overview

Every Lupopedia installation maintains:

- its own content,
- its own ingestion radius,
- its own Crafty Syntax import,
- its own Q&A mappings,
- its own navigation structure,
- its own semantic graph.

The semantic graph is a directed, typed, multi‑edge graph composed of:

- **entities** (content items),
- **relationships** (semantic edges),
- **metadata** (context),
- **lineage** (historical origin),
- **radius** (ingestion source).

The graph describes meaning *within this installation only*.

---

## Installation Sovereignty

Each installation is a sovereign semantic universe.

It has:
- its own database,
- its own content,
- its own semantic graph,
- its own ingestion pipeline,
- its own federation registry,
- its own agents (except kernel agents).

Nothing is shared automatically between installations.

---

## Federation Registry

The table `lupo_federation_nodes` defines known installations:

- federation_node_id = 0 → lupopedia.com (root)
- federation_node_id = 1 → this installation (local)
- federation_node_id >= 2 → other installations this one knows about

These are **installations**, not semantic graph elements.

The semantic graph does NOT merge across installations.

---

## Semantic Entities (Graph Elements)

Inside an installation, the semantic graph contains **entities**, such as:

- content pages,
- help topics,
- Crafty Syntax pages,
- Crafty Syntax departments,
- Crafty Syntax categories,
- questions,
- answers,
- collection tabs,
- modules,
- doctrine sections,
- external URLs (as references).

These are the "things" the semantic graph describes.

**Important:** Entities are NOT stored in a separate table. They are represented polymorphically in `lupo_edges` via:
- `left_object_type` + `left_object_id` (source entity)
- `right_object_type` + `right_object_id` (target entity)

The `object_type` field identifies the entity type (e.g., "content", "actor", "collection_tab", "module").

We never call these "nodes" to avoid confusion with federation nodes.

---

## Relationships (Semantic Edges)

Relationships are stored in `lupo_edges` and describe how entities relate to each other.

Each edge has:
- `left_object_type` + `left_object_id` (source entity)
- `right_object_type` + `right_object_id` (target entity)
- `edge_type` (relationship type from `lupo_edge_types`)

Edge type examples:
- **is_a**
- **part_of**
- **inherits_from**
- **related_to**
- **linked_to**
- **linked_from**
- **answers**
- **asked_about**
- **navigates_to**
- **placed_under**
- **originates_from**
- **historical_equivalent**

Edges are:
- directional,
- typed (via `edge_type` referencing `lupo_edge_types`),
- deterministic,
- grounded in real data,
- polymorphic (can connect any entity type to any other entity type).

---

## Semantic Inheritance

Entities inherit meaning from:

1. Navigation tab placement  
2. Folder structure  
3. Crafty Syntax department  
4. Crafty Syntax category  
5. Q&A mappings  
6. Explicit links  
7. Module placement  
8. Doctrine section placement  

Inheritance rules:
- Placement defines meaning.
- Meaning flows downward.
- Inheritance is additive.
- No creative inference is allowed.

---

## Crafty Syntax Lineage (Per Installation)

Each installation imports its own Crafty Syntax dataset:

- its own help pages,
- its own departments,
- its own categories,
- its own operator workflows,
- its own questions,
- its own answers,
- its own navigation structure.

This becomes part of the installation's semantic graph.

Nothing from other installations is imported automatically.

---

## Q&A Semantic Mapping

Crafty Syntax Q&A imports generate semantic edges:

- question → asked_about → topic  
- answer → answers → question  
- question → navigates_to → page  
- department → part_of → category  

These edges reflect real historical usage patterns.

They are not guesses.  
They are not heuristics.  
They are historical truth.

---

## Radius-Based Semantics

Each entity has a radius:

- Radius 0 — local filesystem  
- Radius 1 — internal URLs of this installation  
- Radius 2 — trusted external URLs  

Radius affects:
- trust,
- weighting,
- search ranking,
- reasoning context.

Radius does NOT change meaning.

---

## Cross-Installation References

If this installation references a URL belonging to another installation:

- It creates a local entity representing that URL.
- It stores metadata only.
- It does NOT ingest remote private content.
- It does NOT merge semantic graphs.
- It does NOT crawl the remote installation.

Cross-installation relationships are URL-based, not graph-based.

This preserves:
- privacy,
- sovereignty,
- safety,
- federation boundaries.

---

## Semantic Graph Storage

The semantic graph is stored in:

- lupo_edges (polymorphic edge table)
- lupo_edge_types (edge type definitions)

The `lupo_edges` table uses polymorphic endpoints:
- `left_object_type` + `left_object_id` (source entity)
- `right_object_type` + `right_object_id` (target entity)
- `edge_type` (relationship type from lupo_edge_types)

Entities are represented polymorphically via object_type/object_id pairs. There is no separate entities table.

Rules:
- edges are append-only,
- historical edges must never be deleted,
- lineage must be preserved,
- object_type must reference valid entity types (content, actor, collection_tab, etc.).

---

## Agent Interaction with the Graph

Agents may:
- read edges (via `lupo_edges`),
- read edge types (via `lupo_edge_types`),
- query edges by object_type/object_id,
- perform semantic lookups,
- follow relationships.

Agents may NOT:
- modify edges,
- modify edge types,
- modify lineage,
- create new semantic edges.

Only ingestion pipelines may modify the graph.

---

## Semantic Graph in Routing

The semantic graph influences routing by providing:

- context for HERMES filtering,
- polarity cues for CADUCEUS,
- semantic clusters for agent selection,
- historical relevance for reasoning agents.

Examples:
- A question entity with strong "help_topic" edges biases toward reason agents.
- An entity with strong "expressive" edges biases toward dialog agents.

---

## Semantic Graph in Search

Search uses:
- entity labels,
- relationship types,
- relationship weights,
- semantic clusters,
- Q&A mappings,
- radius weighting.

Search results are deterministic and explainable.

---

## Semantic Graph in Navigation

Navigation tabs are semantic entities.

Relationships:
- placed_under  
- part_of  
- navigates_to  

Define:
- hierarchy,
- grouping,
- semantic meaning,
- user-facing navigation.

---

## Federation & The Semantic Graph

Federation connects installations, not semantic graphs.

Rules:
- Each installation maintains its own graph.
- No installation may modify another's graph.
- Cross-installation references are URL-based only.
- No graph merging is allowed.
- No remote crawling is allowed.

This preserves sovereignty and privacy.

---
