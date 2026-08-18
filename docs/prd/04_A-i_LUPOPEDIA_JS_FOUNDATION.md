---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/04_A-i_LUPOPEDIA_JS_FOUNDATION.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/04_A-i_LUPOPEDIA_JS_FOUNDATION.md
  status: active
  when_updated: '20260817092400'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/04_lupopedia_js_foundation.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/lupopedia-js-foundation
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: prd
  prd_cluster: 04_A-i_00_A-i_FORBIDDEN_AND_WHY_04_A_LUPOPEDIA_JS_FOUNDATION
  title: 04. Lupopedia JS Foundation (The Nerves) - 4.0.93
  summary: ''
---
**RULE [93.PROTECT_SCHEMA_JSON]** (formerly PROTECT_TOONS): JS event logic must reference actual database schema from `database/lupopedia/json/*.json`, not assumed structures. All schema evolution must be verified with `generate_toon_files.py`. See `00_root_constitutional_system_requirements.md` ????6 and ????9.9.

**LILITH Verdict**: The "Nerves" must connect to the real "Senses" (database schema) not imagined structures.


---

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## Product Lineage Note (Crafty Syntax to Lupopedia)

The following systems were developed by the same author and share a single unified code lineage:

- **Crafty Syntax** -- original open-source lineage
- **Sales Syntax** -- commercial branding fork
- **White Label Syntax** -- reseller branding fork
- **Black Label Syntax** -- enterprise branding fork

These names are branding forks of the same underlying system, created for different distribution channels. They are one family, not separate or competing products. All four forks converge into the unified Lupopedia OS architecture.

`livehelp_js.php` (Crafty-family live help embed) and `lupopedia_js.php` (The Eye) are Lupopedia OS scripts. Legacy embeds that still name Crafty Syntax, Sales Syntax, White Label Syntax, or Black Label Syntax are the same family. Do not treat those names as independent products.

## lupopedia_js.php -- color identity and lineage payload (The Eye)

**`lupopedia_js.php`** is the public JS entry for The Eye (PRD 28). It MUST fetch color identity and lineage metadata from the Lupopedia OS and pass that payload to the widget. The browser MUST NOT query the database.

This is a **PRD requirement**. It does **not** add install SQL. Until color registry / lineage storage exists (PRD 90 / PRD 01_B), the script MUST pass empty or pending fields. HEX6 MUST NOT be guessed. HEX6 is six digits with no `#`. HEX5 is not a color. Color is not a LUP KEY token.

A Color Group is not only a color identity. It also represents a **Collection** (named set of webpages, artifacts, or semantic nodes). Color Groups and Collections are unified. The payload MUST include `collection_name`.

Required payload fields (empty string or omitted when unknown):

```text
color.group_color
color.color_name
color.collection_name
color.hex6
color.handshake
lineage.parent_url
lineage.child_urls[]
lineage.change_type
lineage.change_intent
lineage.change_explanation
collection.collection_id
collection.collection_name
collection.tabs[]
actions.color_this_page_url
actions.declare_child_page_url
actions.view_lineage_url
actions.edit_page_url
```

**Collection Selector:** `lupopedia_js.php` MUST pass Collection list and the active Collection to The Eye / semantic navbar. The **blue dropdown** is the Collection Selector. Selecting a Collection MUST reload semantic menus from **`lupo_collection_tabs`** filtered by that Collection (`lupo_collections` + `lupo_collection_tabs`, PRD 73 / PRD 21). Green tabs become multi-level drop menus for that Collection's pages.

**Declare Child Page URL:** `lupopedia_js.php` MUST be able to construct `{LUPOPEDIA_PUBLIC_PATH}/?parent=` using the current page path **relative to the domain root** (parent of `/lupopedia/`), not relative to the OS directory.

**Coexistence:** `livehelp_js.php` remains the Crafty live-help embed (chat icon / invites). `lupopedia_js.php` remains The Eye. Both MAY appear on the same page. Live help handles local domain content coloring (Content). The Eye displays color identity + lineage indicators and routes Declare Child Page to the Color Registry homepage.

**Artifact lineage payloads:** JS foundation MUST support artifact lineage payloads for **PRD 92** (Artifact Lineage Widget; CC-BY music is the first surface). Allowed as `lupopedia_js.php?mode=artifact` and/or `artifact_lineage_js.php`. That mode MUST return artifact metadata (parent, remix chain, attribution, kind) without loading The Eye or the semantic navbar.

**Tracking:** When the widget surfaces color identity, lineage, or a Collection change, emit the PRD 11 events: `color_identity_viewed`, `lineage_viewed`, `child_page_created`, `parent_page_referenced`, `collection_selected`.

## Context-Typed, Status-Aware, Directional Edged Memory Doctrine (4.0.96)

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
  - unidirectional (A ???????? B)
  - bidirectional (A ???????? B)
  - restricted-direction (A ???????? B but not B ???????? A unless explicitly defined)
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
  or reclassified. A node may move from unsupported ???????? supported when 
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
