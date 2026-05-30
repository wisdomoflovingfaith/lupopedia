---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/34_A-i_FEDERATION_NODE_SEMANTIC_NETWORK.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/34_A-i_FEDERATION_NODE_SEMANTIC_NETWORK.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/34_federation_node_semantic_network.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/federation-node-semantic-network
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_34_A-i
  title: 'file: PRD 34 - delegation: cursor:root - web_path: http://www.lupopedia.com/lupopedia/docs/prd/34_federation_node_semantic_network.md'
  summary: null
---
# file: PRD 34 --- delegation: cursor:root --- web_path: http://www.lupopedia.com/lupopedia/docs/prd/34_federation_node_semantic_network.md

# PRD 34: Federation node semantic network

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

## The Silent Million (scale narrative - planning)

**Internal name** for the **combined** historical footprint: **~1,000,000+ lifetime Crafty Syntax installations** (cumulative) vs **~144,000 actively reporting** in the callback-era framing (see **[SILENT_HARVEST_DOCTRINE.md](../doctrine/SILENT_HARVEST_DOCTRINE.md)** - *Historical context and scale*). The **remainder** are **dormant, removed, or idle** - **not** centrally readable; reactivation is **operator-driven** per **[CRAFTY_NODE_REACTIVATION_STRATEGY.md](../doctrine/CRAFTY_NODE_REACTIVATION_STRATEGY.md)**.

**Federation implication:** Cross-node learning requires **opt-in** installs with agreed payloads - **not** automatic access to every dormant database. **Do not** publicly imply a -shadow empire- of data; document **consent**, **disclosure**, and **local-first** behavior.

## Status

**DRAFT - not implemented.** Do not add federation runtime behavior, sync, or new tables to **`install_new_lupopedia.sql`** until this PRD is reviewed and approved. **4.0.x** remains single-install focused (Crafty import, one node correct). Federation **logic** is explicitly **post-stabilization** unless a narrow exception is ratified here.

## Purpose

Specify how Lupopedia **installs** (federation nodes) **discover** each other, establish **trust**, propagate **reputation**, and exchange **semantic** data - without violating constitutional database rules. Align **-context-** language with the **edge graph** (`lupo_edges` and related tables already in install).

## Constitutional alignment (binding)

- **No foreign keys, no triggers, no stored procedures** - application-layer joins and cascades only.
- **Timestamps:** `BIGINT` UTC **`YYYYMMDDHHIISS`** (`*_ymdhis`), set in PHP - not `DATETIME` / Unix epoch in DDL for stored fields.
- **Primary keys:** `<table_singular>_id` naming; no `AUTO_INCREMENT` in doctrine stance - application allocates IDs per existing patterns.
- **Soft deletes:** `is_deleted`, `deleted_ymdhis` where tables already use that pattern.

## Existing federation-related tables (install today)

Authoritative DDL: **`database/lupopedia/mysql/install/install_new_lupopedia.sql`**. Summary for planning (prefix `lupo_` at runtime):

| Table | Role |
|-------|------|
| **`lupo_federation_nodes`** | Node registry (`node_type`, `node_base_url`, counts, `trust_level`, `status`, -). |
| **`lupo_federation_categories`** / **`lupo_federation_category_map`** | Taxonomy mapping nodes to categories. |
| **`lupo_federated_trust`** | Trust edges between `source_node_id` and `target_node_id` (`trust_level` float, `trust_type`, `last_verified_ymdhis`, -). **Note:** column is `last_verified_ymdhis` (suffix `ymdhis`, not `_utc`). |
| **`lupo_federation_discovery`** | Discovery row per `domain` with aggregates: `hashtag_count`, `question_count`, `atom_count`, **`context_count`**, **`collection_count`**, import flags `import_hashtags`, `import_questions`, `import_atoms`, **`import_collections`**. |

**LILITH audit (2026-04):** Schema is **constitutionally sound**; complexity is in **behavior and naming**, not FK violations.

## Historical analytics foundation (Crafty legacy) and -silent harvest-

Imports from **Crafty Syntax 3.7.5** can bring forward **years** of **first-party** visitor and path statistics into Lupopedia-s analytics model. Canonical tables include **`lupo_visits`**, **`lupo_paths`** (with `year_num` / `month_num` / `day_num`), **`lupo_paths_summary`**, **`lupo_visits_daily`**, and **`lupo_referers_daily`** - see **`install_new_lupopedia.sql`** and **[SILENT_HARVEST_DOCTRINE.md](../doctrine/SILENT_HARVEST_DOCTRINE.md)**.

**Framing:** That corpus is a **strategic asset per operator** for inferring real navigation behavior and building **semantic navigation** - not a license to misrepresent privacy posture or to claim a central -global- dataset Lupopedia does not operate.

## Navigation compiler (planned PRD 34 deliverable)

A **navigation compiler** (application-layer, deterministic) is a **planned** output of this PRD: **inputs** such as domain or site scope and **aggregated** path/visit rows; **outputs** such as **candidate** nav trees, ordered link sets, or **`lupo_folders` / `folder_map`** suggestions - **subject to approval** of this PRD and to **SILENT_HARVEST** privacy constraints.

**Rules until specified:** Use **only** documented columns on **`lupo_visits`**, **`lupo_paths`**, **`lupo_visits_daily`**, **`lupo_referers_daily`**, and related tables - **no** hypothetical `*_monthly` tables unless added to install with full doctrine process.

## Problem: -contexts- vs edges

**Insight:** In the semantic model, a -context- is effectively a **named collection of relationships** - often better modeled as **edges** (and optional grouping) than as a parallel concept.

**Current install:** `lupo_federation_discovery` exposes both **`context_count`** and **`collection_count`**, and **`import_collections`** - there is **no** `import_contexts` column (do not assume it).

**PRD direction (decision pending):**

- Treat **discovery aggregates** as **telemetry** (`context_count` may remain a remote-reported statistic).
- Prefer **edge-native** modeling for **actual** shared data: use **`lupo_edges`**, **`lupo_edge_types`**, **`lupo_edge_map`**, **`collections`** as appropriate - avoid introducing a separate -contexts- table unless a future design proves it is not isomorphic to edge collections.
- **Optional rename (breaking):** consider renaming **`context_count`** - **`edge_collection_count`** (or document that -context- means -edge collection bundle- in discovery UI only). **Requires** explicit migration/install update and TOON regen - not 4.0.x unless approved.

## Proposed: `lupo_actor_ratings` (not in install until approved)

**Goal:** Per-actor (and optionally per-node) **engagement and trust signals** for federation ranking and spam resistance - **derived in application code** from events, not DB triggers.

**Sketch only** - column names must match PK doctrine and `*_ymdhis` timestamps if added to install:

```sql
-- PROPOSED - do not paste into install without PRD approval + TOON + required_tables audit
CREATE TABLE {{prefix}}actor_ratings (
  actor_rating_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  federation_node_id bigint NOT NULL DEFAULT 0,
  content_count bigint NOT NULL DEFAULT 0,
  like_count bigint NOT NULL DEFAULT 0,
  share_count bigint NOT NULL DEFAULT 0,
  comment_count bigint NOT NULL DEFAULT 0,
  engagement_score float DEFAULT NULL,
  trust_score float DEFAULT NULL,
  influence_score float DEFAULT NULL,
  trust_source_node_id bigint DEFAULT NULL,
  trust_verified_ymdhis bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (actor_rating_id)
);
-- Indexes TBD; no FK clauses
```

**Open questions:** Which events increment counts? How does federation **merge** ratings from peers without hidden DB logic? Privacy and consent for cross-node reputation.

## Proposed: edge collections (design phase)

If a first-class **named bundle of edges** is required, design **`lupo_edge_collections`** (or reuse **`collections`** + membership) **here** before DDL. Must not duplicate **`lupo_edges`** semantics.

## Key decisions (unresolved)

1. **Discovery:** How do nodes find peers - manual config only for v1, or later DHT-style / directory?
2. **Trust:** Algorithm for `lupo_federated_trust.trust_level` updates; verification cadence; revocation.
3. **Shareable payload:** Which atoms, hashtags, questions, edges may cross nodes; **opt-in** defaults.
4. **Sync:** Batch vs real-time - **out of scope** for first delivery (see below).
5. **Ratings:** Whether `lupo_actor_ratings` is per-node only, or includes federated aggregates.

## Out of scope for 4.0.x (default)

- Automatic peer discovery and background mesh sync.
- Real-time bidirectional replication.
- Complex trust games - start from **explicit** trust rows and **simple** levels.
- Any schema change **without** updating install SQL, seed, TOONs, and **REQUIRED_TABLES** audit per project rules.

## Dependencies (read before implementation)

- **[PRD 28](28_semantic_monitoring_widget.md)** - semantic surfaces.
- **[PRD 29](29_project_structure.md)** - layout including `research/federation_nodes/`.
- **[REVERSE_ENGINEERING_DOCTRINE.md](../doctrine/REVERSE_ENGINEERING_DOCTRINE.md)** - dual-purpose `federation_node_id`, research vs future peers.
- **[SILENT_HARVEST_DOCTRINE.md](../doctrine/SILENT_HARVEST_DOCTRINE.md)** - long-horizon visit/path aggregates; ethics; navigation compiler foundation.
- **[PRD 11](11_analytics_tracking.md)** - paths, visits, analytics tracking semantics.

## Implementation workspace

- **`docs/implementations/34_federation_node_semantic_network/`** - questions, decisions, status artifacts.

## Checklist before first DDL change

- [ ] Decisions above answered in **`decisions/`** or PRD body.
- [ ] `install_new_lupopedia.sql` updated (single source for 4.0.x).
- [ ] `seed_lupopedia.sql` if seeded rows required.
- [ ] TOON regen per project process; **REQUIRED_TABLES** and audits updated.
- [ ] No FK/trigger DDL; timestamps `*_ymdhis`.

This output complies with Lupopedia Constitutional Root Rules.


---

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
  - unidirectional (A - B)
  - bidirectional (A - B)
  - restricted-direction (A - B but not B - A unless explicitly defined)
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
  or reclassified. A node may move from unsupported - supported when 
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
