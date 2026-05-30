> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/doctrine/NAVIGATION_TAB_DOCTRINE.md"
  file_hash: "973dceb620fb0193b50fa074f34573e66dde1123f2260553b7265c00f7a6e15c"
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
  file_path_from_root: "docs\channels\doctrine\NAVIGATION_TAB_DOCTRINE.md"
  file_hash: "4e68bce46b22e14c893941bac77979b0c846b400c5412251dbef94cbae94afd7"
  file_path_from_root: "docs\channels\doctrine\NAVIGATION_TAB_DOCTRINE.md"
  file_hash: "df5642085e0f4e2ed150bb9143c09525fde230a21ac901418b565cd4fbdf8881"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for NAVIGATION_TAB_DOCTRINE.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "navigation_tab_doctrinemd"]
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
updated: 2026-01-10
author: GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created Navigation Tab Doctrine documentation defining the Navigation Tab system, semantic inheritance, tab hierarchy, and interaction with content, semantic graph, UI, federation, and Crafty Syntax."
  mood: "00FF00"
tags:
  categories: ["documentation", "doctrine", "navigation", "semantic"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Navigation Tab Doctrine Overview
  - Navigation Tab Location
  - Collections & Tabs
  - Tab Hierarchy
  - Semantic Inheritance
  - Semantic Edge Generation
  - Tab Creation Doctrine
  - Tab Naming Doctrine
  - Tab Reorganization
  - Tab → Content Relationship
  - Tab → Semantic Graph Interaction
  - Tab → UI Interaction
  - Tab → Federation Interaction
  - Tab → Crafty Syntax Interaction
  - Tab Safety Doctrine
file:
  title: "Navigation Tab Doctrine"
  description: "Documents the Navigation Tab system used by each Lupopedia installation, including semantic inheritance, tab hierarchy, collections, and interaction with content, semantic graph, UI, federation, and Crafty Syntax."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Navigation Tab Doctrine

This section documents the Navigation Tab system used by each Lupopedia installation. Navigation Tabs define the semantic categories of an installation's content and act as the primary mechanism for generating semantic meaning, inheritance, and relationships within the local semantic graph.

Navigation Tabs are per-installation.  
They are not global.  
They do not federate.  
They define meaning only within the installation that owns them.

---

## Overview

Navigation Tabs are:

- user-defined semantic categories,
- local to the installation,
- hierarchical (tabs and sub-tabs),
- deterministic,
- non-creative,
- non-inferred,
- the primary source of semantic inheritance.

Navigation Tabs are not:
- agents,
- installations,
- federation nodes,
- global categories,
- shared taxonomies.

They are the semantic "folders" of the installation.

---

## Navigation Tab Location

Navigation Tabs are stored in:

- lupo_collection_tabs (database)
- content headers (WOLFIE Header)
- filesystem placement (optional)
- UI configuration (per installation)

Tabs are defined per Collection.

---

## Collections & Tabs

Each installation may have multiple Collections.

Each Collection has:
- its own Navigation Tabs,
- its own tab hierarchy,
- its own semantic meaning,
- its own content placement rules,
- its own permissions (via `lupo_permissions` with `target_type='collection'`).

Tabs do not cross Collections.

### Collection Permissions

Collections use `lupo_permissions` (polymorphic permissions table) as the ONLY source of truth for access control. Uses `target_type='collection'` and `target_id` to identify collection permissions.

Permissions can be:
- user-based (`user_id` set, `group_id` NULL)
- group-based (`user_id` NULL, `group_id` set)
- permission levels: `read`, `write`, `owner`

The `user_id` and `group_id` fields in `lupo_collections` are metadata only (origin/ownership tracking), not access control.

All collection-related features (navigation, tab editor, add-to-collection, collection management UI) must check `lupo_permissions` with `target_type='collection'` to determine visibility and editability.

Example:
Collection: "Desktop"
Tabs:
- WHO
- WHAT
- WHERE
- WHEN
- WHY
- HOW
- DO

These tabs define the semantic structure of that Collection only.

---

## Tab Hierarchy

Tabs may contain sub-tabs.

Example:
Agents
  +-- Kernel
  +-- Application
  +-- External

Hierarchy rules:
- unlimited depth,
- deterministic ordering,
- no creative naming,
- no automatic inference,
- user-defined only.

---

## Semantic Inheritance

When a content item is placed under a Navigation Tab:

- it inherits the meaning of that tab,
- it inherits the meaning of all parent tabs,
- it generates semantic edges in the local semantic graph.

Example:
Navigation → Agents → Kernel → DIALOG

Content inherits:
- "agent"
- "kernel agent"
- "dialog agent"
- "expressive rendering"

Inheritance is:
- additive,
- deterministic,
- non-creative,
- non-inferred.

---

## Semantic Edge Generation

Placing content under a tab generates edges:

- placed_under  
- part_of  
- inherits_from  
- related_to (optional)  

These edges are stored in the installation's semantic graph.

Edges are:
- directional,
- typed,
- deterministic.

---

## Tab Creation Doctrine

Tabs may be created by:
- the installation owner,
- authorized operators,
- automated import (Crafty Syntax departments/categories).

Tabs must NOT be created by:
- agents,
- routing systems,
- ingestion pipelines (except Crafty import),
- external installations.

---

## Tab Naming Doctrine

Tab names must be:
- short,
- descriptive,
- deterministic,
- non-creative,
- stable over time.

Tab names must NOT:
- contain emojis,
- contain creative prose,
- contain dynamic values,
- contain user-specific data.

---

## Tab Reorganization

Tabs may be:
- renamed,
- reordered,
- nested,
- moved,
- merged,
- deleted.

Reorganization rules:
- must preserve semantic meaning,
- must update semantic edges,
- must update content placement,
- must not break lineage,
- must not violate doctrine.

---

## Tab → Content Relationship

Content may be placed under:
- one tab,
- multiple tabs (optional),
- nested tabs.

Rules:
- placement defines meaning,
- meaning flows downward,
- content may inherit multiple semantic categories.

---

## Tab → Semantic Graph Interaction

Tabs are semantic entities.

They generate:
- inheritance edges,
- grouping edges,
- navigation edges.

Tabs do NOT:
- modify content,
- modify agents,
- modify federation,
- modify routing.

Tabs only define meaning.

---

## Tab → UI Interaction

Tabs appear in:
- the operator UI,
- the public UI (optional),
- the content editor,
- the navigation tree.

UI rules:
- tabs must be displayed in hierarchy order,
- tabs must be collapsible,
- tabs must be selectable for content placement.

---

## Tab → Federation Interaction

Tabs do NOT federate.

Rules:
- tabs are local to the installation,
- tabs do not sync across installations,
- tabs do not merge across installations,
- tabs do not define global meaning.

Cross-installation meaning is URL-based only.

---

## Tab → Crafty Syntax Interaction

Crafty Syntax departments and categories may be imported as tabs.

Rules:
- import is one-way,
- imported tabs are immutable historical truth,
- imported tabs may be reorganized but not rewritten,
- imported tabs generate semantic edges.

---

## Tab Safety Doctrine

Tabs must never:
- violate semantic graph boundaries,
- violate ingestion boundaries,
- violate federation boundaries,
- violate agent boundaries,
- violate kernel boundaries.

Tabs must always:
- define meaning,
- preserve lineage,
- remain deterministic,
- remain user-defined.

---
