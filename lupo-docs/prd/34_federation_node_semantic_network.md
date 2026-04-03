---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  when_updated: "20260403125325"
  file_path_from_root: "lupo-docs/prd/34_federation_node_semantic_network.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/34_federation_node_semantic_network.md"
  last_modified_utc: "20260403125325"
  federation_node_id: 0
  channel_id: 42
  thread_id: "prd-34-federation-node-semantic-network"
  prd_id: 34
  prd_slug: federation_node_semantic_network
  author:
    type: "actor"
    id: 102
    name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: prd
  artifact_kind: federation_semantic_network
  purpose: "Define how Lupopedia installs discover, trust, and exchange semantic data; actor reputation; edge-centric modeling vs legacy discovery fields"
  status: "draft"
  tags:
    - prd
    - federation
    - semantic_network
    - trust
    - discovery
    - actor_ratings
    - edges
    - planned_post_4_0_x
    - silent_harvest
    - navigation_compiler
    - silent_million
    - scale_narrative
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional DB rules — no FKs, no triggers, BIGINT UTC timestamps, soft deletes"
    - to: "lupo-docs/prd/29_project_structure.md"
      type: references
      weight: 1.0
      reason: "Federation node filesystem and lupo-research layout"
    - to: "lupo-docs/prd/28_semantic_monitoring_widget.md"
      type: references
      weight: 0.95
      reason: "Semantic graph and monitoring — future cross-cutting concerns"
    - to: "lupo-docs/doctrine/REVERSE_ENGINEERING_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Dual-purpose federation_node_id; research vs semantic peers"
    - to: "lupo-docs/doctrine/TWO_LAYER_SECURITY_DOCTRINE.md"
      type: references
      weight: 0.85
      reason: "Trust and supply-chain posture for cross-node exchange"
    - to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"
      type: references
      weight: 1.0
      reason: "Canonical DDL for federation tables today"
    - to: "lupo-docs/implementations/34_federation_node_semantic_network/README.md"
      type: references
      weight: 0.9
      reason: "Implementation workspace for this PRD"
    - to: "lupo-docs/doctrine/SILENT_HARVEST_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Crafty-era path/visit aggregates as foundation; ethics and disclosure"
    - to: "lupo-docs/doctrine/CRAFTY_NODE_REACTIVATION_STRATEGY.md"
      type: references
      weight: 1.0
      reason: "Dormant Crafty → Lupopedia; opt-in federation order"
lupopedia.footer:
  last_verified: "20260403125325"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
  next_action:
    - "Resolve open decisions before any install.sql change"
    - "Defer runtime federation sync until post-4.0.x stabilization"
    - "Specify navigation compiler I/O against real columns (visits, paths, visits_daily) per SILENT_HARVEST_DOCTRINE"
    - "Align federation messaging with SILENT_HARVEST scale table and CRAFTY_NODE_REACTIVATION_STRATEGY"
---

# file: PRD 34 — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/prd/34_federation_node_semantic_network.md

# PRD 34: Federation node semantic network

## The Silent Million (scale narrative — planning)

**Internal name** for the **combined** historical footprint: **~1,000,000+ lifetime Crafty Syntax installations** (cumulative) vs **~144,000 actively reporting** in the callback-era framing (see **[SILENT_HARVEST_DOCTRINE.md](../doctrine/SILENT_HARVEST_DOCTRINE.md)** — *Historical context and scale*). The **remainder** are **dormant, removed, or idle** — **not** centrally readable; reactivation is **operator-driven** per **[CRAFTY_NODE_REACTIVATION_STRATEGY.md](../doctrine/CRAFTY_NODE_REACTIVATION_STRATEGY.md)**.

**Federation implication:** Cross-node learning requires **opt-in** installs with agreed payloads — **not** automatic access to every dormant database. **Do not** publicly imply a “shadow empire” of data; document **consent**, **disclosure**, and **local-first** behavior.

## Status

**DRAFT — not implemented.** Do not add federation runtime behavior, sync, or new tables to **`install_new_lupopedia.sql`** until this PRD is reviewed and approved. **4.0.x** remains single-install focused (Crafty import, one node correct). Federation **logic** is explicitly **post-stabilization** unless a narrow exception is ratified here.

## Purpose

Specify how Lupopedia **installs** (federation nodes) **discover** each other, establish **trust**, propagate **reputation**, and exchange **semantic** data — without violating constitutional database rules. Align **“context”** language with the **edge graph** (`lupo_edges` and related tables already in install).

## Constitutional alignment (binding)

- **No foreign keys, no triggers, no stored procedures** — application-layer joins and cascades only.
- **Timestamps:** `BIGINT` UTC **`YYYYMMDDHHIISS`** (`*_ymdhis`), set in PHP — not `DATETIME` / Unix epoch in DDL for stored fields.
- **Primary keys:** `<table_singular>_id` naming; no `AUTO_INCREMENT` in doctrine stance — application allocates IDs per existing patterns.
- **Soft deletes:** `is_deleted`, `deleted_ymdhis` where tables already use that pattern.

## Existing federation-related tables (install today)

Authoritative DDL: **`lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`**. Summary for planning (prefix `lupo_` at runtime):

| Table | Role |
|-------|------|
| **`lupo_federation_nodes`** | Node registry (`node_type`, `node_base_url`, counts, `trust_level`, `status`, …). |
| **`lupo_federation_categories`** / **`lupo_federation_category_map`** | Taxonomy mapping nodes to categories. |
| **`lupo_federated_trust`** | Trust edges between `source_node_id` and `target_node_id` (`trust_level` float, `trust_type`, `last_verified_ymdhis`, …). **Note:** column is `last_verified_ymdhis` (suffix `ymdhis`, not `_utc`). |
| **`lupo_federation_discovery`** | Discovery row per `domain` with aggregates: `hashtag_count`, `question_count`, `atom_count`, **`context_count`**, **`collection_count`**, import flags `import_hashtags`, `import_questions`, `import_atoms`, **`import_collections`**. |

**LILITH audit (2026-04):** Schema is **constitutionally sound**; complexity is in **behavior and naming**, not FK violations.

## Historical analytics foundation (Crafty legacy) and “silent harvest”

Imports from **Crafty Syntax 3.7.5** can bring forward **years** of **first-party** visitor and path statistics into Lupopedia’s analytics model. Canonical tables include **`lupo_visits`**, **`lupo_paths`** (with `year_num` / `month_num` / `day_num`), **`lupo_paths_summary`**, **`lupo_visits_daily`**, and **`lupo_referers_daily`** — see **`install_new_lupopedia.sql`** and **[SILENT_HARVEST_DOCTRINE.md](../doctrine/SILENT_HARVEST_DOCTRINE.md)**.

**Framing:** That corpus is a **strategic asset per operator** for inferring real navigation behavior and building **semantic navigation** — not a license to misrepresent privacy posture or to claim a central “global” dataset Lupopedia does not operate.

## Navigation compiler (planned PRD 34 deliverable)

A **navigation compiler** (application-layer, deterministic) is a **planned** output of this PRD: **inputs** such as domain or site scope and **aggregated** path/visit rows; **outputs** such as **candidate** nav trees, ordered link sets, or **`lupo_folders` / `folder_map`** suggestions — **subject to approval** of this PRD and to **SILENT_HARVEST** privacy constraints.

**Rules until specified:** Use **only** documented columns on **`lupo_visits`**, **`lupo_paths`**, **`lupo_visits_daily`**, **`lupo_referers_daily`**, and related tables — **no** hypothetical `*_monthly` tables unless added to install with full doctrine process.

## Problem: “contexts” vs edges

**Insight:** In the semantic model, a “context” is effectively a **named collection of relationships** — often better modeled as **edges** (and optional grouping) than as a parallel concept.

**Current install:** `lupo_federation_discovery` exposes both **`context_count`** and **`collection_count`**, and **`import_collections`** — there is **no** `import_contexts` column (do not assume it).

**PRD direction (decision pending):**

- Treat **discovery aggregates** as **telemetry** (`context_count` may remain a remote-reported statistic).
- Prefer **edge-native** modeling for **actual** shared data: use **`lupo_edges`**, **`lupo_edge_types`**, **`lupo_edge_map`**, **`collections`** as appropriate — avoid introducing a separate “contexts” table unless a future design proves it is not isomorphic to edge collections.
- **Optional rename (breaking):** consider renaming **`context_count`** → **`edge_collection_count`** (or document that “context” means “edge collection bundle” in discovery UI only). **Requires** explicit migration/install update and TOON regen — not 4.0.x unless approved.

## Proposed: `lupo_actor_ratings` (not in install until approved)

**Goal:** Per-actor (and optionally per-node) **engagement and trust signals** for federation ranking and spam resistance — **derived in application code** from events, not DB triggers.

**Sketch only** — column names must match PK doctrine and `*_ymdhis` timestamps if added to install:

```sql
-- PROPOSED — do not paste into install without PRD approval + TOON + required_tables audit
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

1. **Discovery:** How do nodes find peers — manual config only for v1, or later DHT-style / directory?
2. **Trust:** Algorithm for `lupo_federated_trust.trust_level` updates; verification cadence; revocation.
3. **Shareable payload:** Which atoms, hashtags, questions, edges may cross nodes; **opt-in** defaults.
4. **Sync:** Batch vs real-time — **out of scope** for first delivery (see below).
5. **Ratings:** Whether `lupo_actor_ratings` is per-node only, or includes federated aggregates.

## Out of scope for 4.0.x (default)

- Automatic peer discovery and background mesh sync.
- Real-time bidirectional replication.
- Complex trust games — start from **explicit** trust rows and **simple** levels.
- Any schema change **without** updating install SQL, seed, TOONs, and **REQUIRED_TABLES** audit per project rules.

## Dependencies (read before implementation)

- **[PRD 28](28_semantic_monitoring_widget.md)** — semantic surfaces.
- **[PRD 29](29_project_structure.md)** — layout including `lupo-research/federation_nodes/`.
- **[REVERSE_ENGINEERING_DOCTRINE.md](../doctrine/REVERSE_ENGINEERING_DOCTRINE.md)** — dual-purpose `federation_node_id`, research vs future peers.
- **[SILENT_HARVEST_DOCTRINE.md](../doctrine/SILENT_HARVEST_DOCTRINE.md)** — long-horizon visit/path aggregates; ethics; navigation compiler foundation.
- **[PRD 11](11_analytics_tracking.md)** — paths, visits, analytics tracking semantics.

## Implementation workspace

- **`lupo-docs/implementations/34_federation_node_semantic_network/`** — questions, decisions, status artifacts.

## Checklist before first DDL change

- [ ] Decisions above answered in **`decisions/`** or PRD body.
- [ ] `install_new_lupopedia.sql` updated (single source for 4.0.x).
- [ ] `seed_lupopedia.sql` if seeded rows required.
- [ ] TOON regen per project process; **REQUIRED_TABLES** and audits updated.
- [ ] No FK/trigger DDL; timestamps `*_ymdhis`.

This output complies with Lupopedia Constitutional Root Rules.
