---
lupopedia.headers:
  version_when_written: "4.0.87"
  lupopedia.schema: "thread"
  file_path_from_root: "lupo-channels/table-structure-optimization/threads/20260325_170000_athena_semantic_table_architecture_review_4_0_87.md"
  web_path: "http://www.lupopedia.com/lupo-channels/table-structure-optimization/threads/20260325_170000_athena_semantic_table_architecture_review_4_0_87"
  questions_toon: null
  channel_id: "table-structure-optimization"
  thread_id: "semantic-table-architecture-review-4-0-87"
  actor_id: 12
  actor_name: "athena"
  faucet_name: "cursor"
  delegation_chain: "cursor:root > athena"
  artifact_type: "thread"
  artifact_kind: "strategy"
  purpose: "Comprehensive semantic table architecture review for Lupopedia 4.0.87. 167 TOONs inspected. Identifies ORM vomit tables, edge surface proliferation, lock-in risks, and structural fixes before more code hardens the schema."
  tags: ["athena", "schema_review", "orm_vomit", "edge_model", "semantic_architecture", "lock_in_risk", "4.0.87"]
  references:
    - "lupo-database/lupopedia/toon/"
    - "lupo-docs/database/lupopedia/tables/TABLE_INDEX.md"
    - "lupo-docs/database/lupopedia/tables/active/"
    - "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/table-structure-optimization/README.md", type: "extends", weight: 1.0, reason: "ATHENA strategy artifact within channel scope" }
    - { to: "lupo-channels/table-structure-optimization/threads/20260325_103929_athena_actor_agent_department_pairing_strategy.md", type: "supersedes", weight: 0.9, reason: "Broader review supersedes narrower actor/agent analysis" }
    - { to: "lupo-channels/table-structure-optimization/threads/20260325_163000_cursor_admin_ui_identity_alignment_4_0_87.md", type: "responds_to", weight: 0.8, reason: "Informed by identity drift findings in admin UI pass" }
lupopedia.footer:
  last_verified: "20260325170000"
  last_verified_by: "athena"
  last_verified_by_actor_id: 12
  orchestrator: "wolfie"
  faucet: "cursor"
  next_action:
    - "WOLFIE to triage lock-in warnings and gate further coding on edge model decision"
    - "HEPHAESTUS to implement priority fixes 1-5 only after WOLFIE confirms edge model direction"
    - "ANUBIS to confirm: is lupo_cip_events missing from install SQL or intentionally absent?"
    - "THOTH to document lupo_world_registry purpose or mark deprecated"
    - "Validate lupo_rolls vs lupo_actor_channel_roles overlap in install SQL before next session"
---

# ATHENA Strategy: Semantic Table Architecture Review — 4.0.87

**Strategist:** ATHENA (actor_id 12)  
**Faucet:** Cursor (actor_id 102)  
**Date:** 2026-03-25  
**Scope:** All 167 TOON files + active table documentation  
**Channel:** table-structure-optimization  

---

## A. Executive Summary

### What Is Healthy

The **identity core** (actors, agents, auth_users, departments, actor_auth_users, actor_departments, agent_faucets) is well-structured, semantically intentional, and correctly normalized without ORM habits. The actor-centric model has clear doctrine.

The **coordination layer** (channels, actor_channels, dialog_threads, dialog_messages, tasks, projects) is coherent. Channels as workspaces, threads as containers, messages as content — this is clean.

The **canonical edge store** (`lupo_edges`) is correctly designed as a polymorphic graph: left_object_type/id, right_object_type/id, edge_type, weight, channel_id. This is the right pattern. The problem is what surrounds it.

The **Anubis custodian cluster** is large but purposeful. Eight tables give ANUBIS dedicated working memory, queue, quarantine, and audit trail. Coherent within its domain.

The **Crafty Syntax legacy tables** are correctly namespaced under `lupo_crafty_syntax_*`. They are isolated from the canonical model.

### What Is Questionable

1. **Six "map" tables** (collection_map, question_map, reference_links, reference_map, folder_map, hashtag_map) all share the same polymorphic `(object_type, object_id)` join pattern. These are not semantically separate concepts — they are the same pattern instantiated six times.
2. **The decision cluster** has two tables expressing the same "decision affects decision" relationship with different column names. 
3. **The rule subsystem** split into rules, rule_targets, rule_logs is legitimate in intent but the target table mixes "where the rule applies" with "what was triggered."
4. **lupo_dialog_channels** coexists with `lupo_channels` with unclear semantic differentiation. One appears to be a file-import layer.
5. **The channel boot cluster** has three tables generated from two separate implementation passes that were never reconciled.

### What Is Dangerous

1. **Seven edge surfaces exist.** One canonical (`lupo_edges`), six domain-scoped duplicates (`lupo_actor_edges`, `lupo_context_edges`, `lupo_decision_edges`, `lupo_edge_map`, `lupo_edge_types`, `lupo_edge_type_definitions`). Two type registries conflict with each other. This is the most severe structural problem in the schema.
2. **The CIP analytics cluster references a `cip_event_id` that has no corresponding table in the 167 TOONs.** Three tables are orphaned on a missing anchor.
3. **`lupo_semantic_index` is structured as a canonical store but is a derived/projection table.** Code that writes canonical data here instead of to source tables will create silent authoritative drift.
4. **`lupo_channel_state` embeds a `context_vector BLOB`** — a machine-learning vector embedding construct inside a PHP/MySQL runtime. This construct has no PHP runtime path that can generate or consume it correctly.

---

## B. ORM Vomit Table Review

### B1. Edge Table Cluster — CRITICAL

The schema has accumulated **seven distinct edge surfaces** for what should be a single canonical relationship store.

| Table | Columns structure | Problem |
|---|---|---|
| `lupo_edges` | left_type/id + right_type/id + edge_type | **Canonical. Keep.** |
| `lupo_edge_map` | edge_id + source_type/id + target_type/id | Duplicate of lupo_edges structural shape, adds nothing |
| `lupo_edge_types` | slug + label + description + is_bidirectional | Type registry, slug-keyed |
| `lupo_edge_type_definitions` | edge_type + domain + allowed_left/right + semantic_meaning | Second type registry, overlaps with edge_types |
| `lupo_actor_edges` | source_actor_id + target_actor_id + edge_type + weight | lupo_edges constrained to actor-actor, adds no semantic value |
| `lupo_context_edges` | source_type/id + target_type/id + edge_type + metadata_json | Nearly identical to lupo_edges |
| `lupo_decision_edges` | source_decision_id + target_decision_id + edge_type + probability | lupo_edges constrained to decision-decision |

**`lupo_edge_map`:** This table re-implements the same polymorphic source/target structure as `lupo_edges` but with an `edge_id` linking back to `lupo_edges`. It is either a redundant index of lupo_edges (in which case it should be renamed or generated, not hand-maintained) or it was intended as the canonical store (in which case lupo_edges is the duplicate). Either way: **merge into lupo_edges or deprecate**.

**`lupo_edge_types` vs `lupo_edge_type_definitions`:** Two tables registry the same concept (what kinds of edges exist). `edge_types` uses slug as PK; `edge_type_definitions` uses `edge_type` varchar as unique key. These are the same table written twice. **Merge: keep edge_types slug-keyed canonical registry; fold the domain + allowed_types columns into it or into lupo_metadata per type.**

**`lupo_actor_edges`, `lupo_context_edges`, `lupo_decision_edges`:** Domain-scoped edge tables that provide no structural benefit over writing to `lupo_edges` with appropriate object_type values. All three duplicate lupo_edges columns. Code that writes actor→actor edges here instead of to lupo_edges fractures the relationship graph. **These should be retired and replaced by lupo_edges writes with left_object_type = 'actor' etc.**

**Recommendation:** `lupo_edge_map` → **deprecate**; `lupo_edge_type_definitions` → **merge into lupo_edge_types**; `lupo_actor_edges` → **replace with lupo_edges**; `lupo_context_edges` → **replace with lupo_edges**; `lupo_decision_edges` → **replace with lupo_edges**.

---

### B2. Reference Table Sprawl — DANGEROUS

Five tables for citations/references:

| Table | What it does |
|---|---|
| `lupo_references` | Source entity → URL/title/citation_text |
| `lupo_reference_objects` | object_type + slug registry for things being cited |
| `lupo_reference_cited_by` | reference_object_id → content_id (who cites something) |
| `lupo_reference_links` | reference_id → object_type/object_id (what a reference is linked to) |
| `lupo_reference_map` | reference_id → target_type/target_id (another link mapping) |

`lupo_reference_links` and `lupo_reference_map` are structurally identical: both map a reference to a target object via `(object_type/target_type, object_id/target_id)`. They have different column names for the same concept. One is redundant.

`lupo_reference_cited_by` is an inverted index (content → reference_object). This is a queryable projection of a relationship that could live in `lupo_edges` with edge_type = `cites` or `cited_by`.

**The core problem:** reference-as-citation is a semantic relationship between two entities. That is exactly what `lupo_edges` is for. The accumulation of 5 reference tables is a textbook expansion that happens when domain-specific join tables are created per-relationship-type instead of using the canonical edge store.

**Recommendation:** `lupo_references` → **keep as citation content entity** (URL + citation text); `lupo_reference_objects` → **merge with lupo_references** or use `lupo_metadata`; `lupo_reference_cited_by` → **replace with lupo_edges** edge_type = `cited_by`; `lupo_reference_links` and `lupo_reference_map` → **merge into one, then evaluate whether lupo_edges covers it**.

---

### B3. CIP Analytics Orphan Cluster — LOCK-IN RISK

Three tables tied to `cip_event_id`:

- `lupo_cip_analytics` — event scores (defensiveness_index, integration_velocity, architectural_impact_score)
- `lupo_cip_propagation_tracking` — per-event propagation tracking
- `lupo_multi_agent_critique_sync` — per-agent sync state per CIP event

**No `lupo_cip_events` table exists in the 167 TOONs.** All three tables reference an anchor that is absent from the canonical schema. The string `analytics_version: "3.0.0"` appears throughout these tables, suggesting they were generated from a tooling pass at a different version of the system and never fully integrated.

Additionally, `lupo_cip_analytics.cip_analytics_id` is referenced by `lupo_emotional_geometry_calibrations`, creating a dependency chain that is entirely ungrounded in the actual schema.

**Recommendation:** ANUBIS must audit whether `lupo_cip_events` was intentionally excluded from `install_new_lupopedia.sql` or was lost. Until resolved: **mark all three tables as ungrounded and halt all code that writes to them**. If the CIP event concept belongs in Lupopedia, the anchor table must be created first.

---

### B4. Emotional Geometry Cluster — SUSPICIOUS

Two tables:

- `lupo_emotional_frameworks` — mood framework registry, `framework_name` as PK (no integer PK)
- `lupo_emotional_geometry_calibrations` — complex calibration scores per CIP analytics event

Problems:
- `lupo_emotional_frameworks` uses a string as primary key rather than BIGINT. This violates the ID pattern used universally elsewhere.
- `lupo_emotional_geometry_calibrations` depends on `cip_analytics_id` — orphaned on the missing CIP system.
- `mood_framework` appears not only here but also in `lupo_channel_state` as a varchar column — two separate representations of framework state.
- The "calibration_algorithm", "tension_vectors_detected", "calibration_version" fields look like they belong to an AI/ML toolchain, not a PHP runtime.

**Recommendation:** `lupo_emotional_frameworks` → **redefine with integer pk**; `lupo_emotional_geometry_calibrations` → **defer until CIP anchor exists**; resolve `mood_framework` duplication between channel_state and framework table.

---

### B5. Channel Boot Triplication — QUESTIONABLE

Three tables from two separate implementation passes:

- `lupo_channel_boot_detail` — references `boot_id` (what table is boot_id from?)
- `lupo_channel_boot_lifecycle` — lifecycle_id, channel+actor+session tracking
- `lupo_channel_boot_detail_lifecycle` — lifecycle_id + per-channel detail within a lifecycle

`lupo_channel_boot_detail` references `boot_id` that has no corresponding boot header table in the TOONs. `lupo_channel_boot_lifecycle` has `lifecycle_id` as its key. These are not the same concept with the same name — they appear to be two separate boot tracking attempts that were never reconciled.

**Recommendation:** Audit whether `lupo_channel_boot_detail.boot_id` is supposed to reference `lupo_channel_boot_lifecycle.lifecycle_id` or a now-missing table. Consolidate into **one** boot tracking table once the anchor is resolved. Low urgency unless channel boot code is being written.

---

### B6. Decision Cluster Overlap — MODERATE

`lupo_decision_influences` and `lupo_decision_edges` both represent "decision → decision" relationships:

| | `lupo_decision_edges` | `lupo_decision_influences` |
|---|---|---|
| source | source_decision_id | decision_id |
| target | target_decision_id | influencing_decision_id |
| type | edge_type varchar(50) | influence_type varchar(50) |
| weight | probability decimal(4,3) | weight decimal(4,3) |
| other | channel_id, project_id, federation_node_id | channel_id, project_id, federation_node_id, session_id |

These are the same table with different column names. The only structural difference is `probability` (edges) vs `weight` (influences) and the inclusion of `session_id` in influences.

**Recommendation:** Merge into one table (`lupo_decision_edges` or migrate both to `lupo_edges` with edge_type = `influences_decision`). Since decision→decision is exactly the lupo_edges use case, **prefer lupo_edges migration**.

---

### B7. Six Structural "Map" Tables — DOCUMENT AS PATTERN

These six tables share the same structural pattern — mapping an entity to arbitrary `(object_type, object_id)` pairs:

| Table | Maps |
|---|---|
| `lupo_collection_map` | collection → (object_type, object_id) |
| `lupo_question_map` | question → (object_type, object_id) |
| `lupo_reference_links` | reference → (object_type, object_id) |
| `lupo_reference_map` | reference → (target_type, target_id) |
| `lupo_folder_map` | folder → (object_type, object_id) |
| `lupo_hashtag_map` | hashtag → (object_type, object_id) |

These are not ORM vomit individually — some form of polymorphic membership map is needed. But the pattern should be documented and consistent. Currently they have varying column naming (`object_type`/`target_type`, `object_id`/`target_id`). The question of whether these should be `lupo_edges` entries (left_object = collection, right_object = target, edge_type = `contains`) should be answered once and the answer applied consistently.

**Recommendation:** **Keep** as explicit join tables (preferable over lupo_edges for high-frequency containment queries). **Standardize** column naming to `(object_type, object_id)` across all. Document the pattern in a doctrine note so future agents don't add a seventh.

---

### B8. lupo_world_registry — UNDOCUMENTED

`lupo_world_registry` — world_key, world_type, world_label, world_metadata — has zero rows, no table doc in `active/`, and "world" is not a defined concept in any doctrine file reviewed.

**Recommendation:** THOTH to produce documentation justifying this table's semantic purpose within 4.0.x scope, or **deprecate** it. AGENTS.md confirms this is a single-installation system and there are no external installations — federation scenarios in which "worlds" make sense are not in scope.

---

### B9. lupo_semantic_index — PROJECTION TABLE MISUSED AS CANONICAL

`lupo_semantic_index` has a very broad schema: semantic_type, slug, name, title, description, parent_id, source_content_id, target_content_id, source_page_id — many columns covering many entity types. This is the structure of a **search index** or **denormalized navigation cache**, not a canonical entity table.

If code writes canonical data here (instead of to the source table and relying on re-indexing), the system will have undocumented authoritative copies split across the canonical table and this index.

`lupo_search_index` has the same issue — entity_type + entity_id + denormalized text fields.

**Recommendation:** Both tables must be **explicitly documented as derived/projection tables** that are rebuilt from source tables. Code must never treat them as canonical. Add a `last_rebuilt_ymdhis` column and a rebuild log entry path.

---

### B10. lupo_dialog_channels — SEMANTIC AMBIGUITY

`lupo_dialog_channels` (TOON) has its own `channel_id`, `channel_name`, `file_source`, `speaker`, `target`, `message_count`. The `file_source` field confirms these are imported from YAML dialog files. This is not the same as `lupo_channels` (operational coordination workspaces).

The name collision ("dialog channels" vs "channels") creates ambiguity in any code that joins across both.

**Recommendation:** Rename to `lupo_dialog_imports` or `lupo_dialog_files` to distinguish dialog file imports from live coordination channels. Document that `lupo_dialog_channels.channel_id` is NOT a foreign key to `lupo_channels`.

---

### B11. lupo_analytics_campaign_vars — LEGACY GHOST

Zero rows. Column structure (`period`, `date_ymd`, `yearmonth`, `year`, `campaign_key`, `campaign_value`) is classic Crafty Syntax analytics schema, matching the historical analytics pattern from the 3.x era.

**Recommendation:** **Deprecate**. Move to `deprecated/` in docs. Analytics in Lupopedia 4.x belongs under `lupo_unified_log` or dedicated channel logs. Campaign tracking is explicitly listed under banned concepts (advertising, tracking, monetization hooks).

---

### B12. lupo_memory_rollups — PROJECTION TABLE

`lupo_memory_rollups` contains actor_id + summary (text) + source_event_ids (text, likely comma-separated). `source_event_ids` stored as a text blob is an anti-pattern for any table that might need to be queried or joined on event membership.

**Recommendation:** **Document as derived/rollup cache**. If this is a materialized summary of event data, it should be rebuildable from source. The `source_event_ids` text column should either be normalized (separate join table) or documented as explicitly non-queryable denormalization.

---

### B13. lupo_documentation_frameworks — TOOLING METADATA IN PRODUCTION TABLE

`lupo_documentation_frameworks` stores: framework_key, namespace_key, channel_id, orchestrator_actor_id, facet_slug, agent_key, role_key, database_table, runtime_min_php — metadata describing Lupopedia's own subsystem frameworks.

This is organizational metadata that belongs in `lupo_metadata` keyed to the relevant entity, or in `lupo_atoms`, or in documentation files. Having a production table describing what `runtime_min_php` version a framework demands is not storage — it's documentation embedded in a table.

**Recommendation:** **Evaluate for merge into lupo_atoms or lupo_metadata**. Retain only if runtime code actually reads from this table. Audit callsites.

---

### B14. lupo_rolls — OVERLAP WITH lupo_actor_channel_roles

`lupo_rolls` — channel_id + actor_id + role_slug + permission_scope_json  
`lupo_actor_channel_roles` — (from TOON listing) — also covers actor roles within channels

Before any authorization code is added, verify that these are not the same concept. `lupo_rolls` includes `permission_scope_json`; `lupo_actor_channel_roles` may be more granular. If both are active, authorization code will query one or the other inconsistently.

**Recommendation:** **Read both TOONs together with doctrine and confirm one is canonical**. Deprecate the redundant one before any new auth code is written.

---

### B15. lupo_channel_state — MIXED CONCERNS

`lupo_channel_state` mixes operational presence tracking (active_actors_json, speaker_actors_json, observer_actors_json) with analytical signals (semantic_weight, trend_score) and an AI/ML artifact (`context_vector BLOB`).

`context_vector BLOB` cannot be meaningfully populated or consumed by PHP 5.6-8.3. It appears to be a designed hook for a vector embedding system that does not exist in the runtime. Storing a BLOB of undefined content silently in this table risks corruption of operational state reads.

`mood_framework` here conflicts with the standalone `lupo_emotional_frameworks` table — two representations of the same concept.

**Recommendation:** Remove `context_vector` from the PHP-facing channel state table (or quarantine it in a separate `lupo_channel_state_ml` table explicitly marked as non-PHP). Document `mood_framework` as a foreign key to `lupo_emotional_frameworks.framework_name` and resolve the string-PK issue there first.

---

## C. Edge Strategy

### C1. Relationships That Should Live in lupo_edges

These relationship types are currently distributed across domain-specific tables but belong in `lupo_edges`:

| Relationship | Current table | Move to lupo_edges as |
|---|---|---|
| actor → actor (social, orchestration) | lupo_actor_edges | left=actor/id, right=actor/id, edge_type=slug |
| decision → decision (influence, dependency) | lupo_decision_edges + lupo_decision_influences | left=decision/id, right=decision/id, edge_type=influences |
| content → context | lupo_context_edges / lupo_contexts_map | left=content, right=context, edge_type=contextualized_by |
| reference → content (citation) | lupo_reference_cited_by | left=reference, right=content, edge_type=cited_by |
| any → any (general semantic) | lupo_context_edges | already lupo_edges shape — merge directly |

### C2. Relationships That Should Stay as Explicit Join Tables

High-frequency containment and membership relationships should remain as dedicated join tables rather than moving to `lupo_edges` because query cost and join clarity matter more than semantic generality here:

| Relationship | Table | Reason to keep |
|---|---|---|
| actor ↔ channel | lupo_actor_channels | Authorization critical path, queried on every request |
| actor ↔ department | lupo_actor_departments | Identity model, queried for effective actor resolution |
| actor ↔ auth_user | lupo_actor_auth_users | Identity model, ordering semantics encoded in columns |
| collection → object | lupo_collection_map | High-frequency navigation |
| hashtag → object | lupo_hashtag_map | Tagging query path |
| folder → object | lupo_folder_map | File organization |
| question → object | lupo_question_map | Help search |
| channel → department | lupo_channel_departments | Channel routing |

### C3. Relationships That Should Stay as Direct Columns

| Relationship | Where | Reason |
|---|---|---|
| message → thread | lupo_dialog_messages.thread_id | Foreign-like lookup, always 1:1 parent |
| thread → channel | lupo_dialog_threads.channel_id | Always 1:1 parent |
| actor → department (primary) | lupo_actors.department_id | Single default, fast lookup |
| task → project | lupo_tasks.project_id | Single parent scope |

### C4. Type Registry Strategy

There should be exactly ONE edge type registry. Consolidate `lupo_edge_types` (slug-keyed) and `lupo_edge_type_definitions` (domain + allowed types) into a single `lupo_edge_types` table with these columns: slug, label, description, domain, allowed_left_object_types, allowed_right_object_types, is_bidirectional.

---

## D. Recommended Structural Fixes

### D1. Top 5 — Fix Before More Code Locks the Schema

**Fix 1 — Decide the canonical edge surface and document it.**  
All new relationship code must write to `lupo_edges`. Retire `lupo_actor_edges`, `lupo_context_edges`, `lupo_decision_edges` migration plan. This is the single highest-priority schema decision because every relationship-graph feature adds code to one of these competing tables.

**Fix 2 — Resolve the CIP orphan anchor.**  
`lupo_cip_events` is either missing from `install_new_lupopedia.sql` or doesn't exist. ANUBIS must audit. Until resolved, no new code should write to `lupo_cip_analytics`, `lupo_cip_propagation_tracking`, or `lupo_multi_agent_critique_sync`. These tables are currently ungrounded.

**Fix 3 — Merge edge type registries.**  
`lupo_edge_type_definitions` and `lupo_edge_types` are the same concept. Merge into `lupo_edge_types`. Any code reading `lupo_edge_type_definitions` must be updated.

**Fix 4 — Separate `lupo_semantic_index` and `lupo_search_index` as projection tables.**  
Add `last_rebuilt_ymdhis` column to both. Add a note to all table docs. Audit all PHP callsites that write to these — any that treat them as canonical sources of truth must be corrected to write to source tables instead.

**Fix 5 — Remove `context_vector BLOB` from `lupo_channel_state`.**  
This field has no runtime consumer in the PHP stack. It makes every channel state read carry a potentially large binary column for no runtime benefit. Move to a `lupo_channel_state_ml` table if the ML integration path is ever built.

### D2. Top 5 — Deferred / Non-Urgent

**Defer 1 — Reference table consolidation.**  
`lupo_reference_cited_by` → lupo_edges migration. Low urgency unless reference lookup code is being extended.

**Defer 2 — Channel boot table reconciliation.**  
`lupo_channel_boot_detail.boot_id` anchor resolution. Only urgent if new channel boot monitoring code is planned.

**Defer 3 — `lupo_rolls` vs `lupo_actor_channel_roles` audit.**  
Read both TOONs together. Only urgent if new authorization code is planned.

**Defer 4 — `lupo_world_registry` documentation or deprecation.**  
No runtime use detected. THOTH to document or mark deprecated.

**Defer 5 — `lupo_emotional_frameworks` string-PK correction.**  
Low urgency unless emotional/mood framework code is being extended. Fix the PK type to BIGINT at next schema migration opportunity.

---

## E. Doctrine Boundaries

### What Must Never Move into lupo_edges

- **Actor ↔ auth_user pairing** (`lupo_actor_auth_users`): carries ordering semantics (`is_primary`, `routing_priority`) that cannot be represented by an edge weight alone.
- **Actor ↔ channel membership** (`lupo_actor_channels`): authorization critical path; must be queryable without edge traversal overhead.
- **Session state** (`lupo_sessions`): session data is mutable operational state, not a relationship.
- **Authentication records** (`lupo_auth_users`): identity, not graph data.

### What Should Not Remain as Hardcoded Table Structure

- Actor-to-actor relationships: all actor↔actor semantic relationships should be `lupo_edges` entries, not `lupo_actor_edges` rows. Operational relationships (channel membership, auth pairing) are the exception.
- Decision-to-decision relationships: `lupo_decision_edges` and `lupo_decision_influences` should be retired in favor of `lupo_edges`.
- Context assignments (what context does this content belong to): polymorphic mapping, belongs in `lupo_edges` or `lupo_contexts_map` but not `lupo_context_edges` (which is a third duplicate).

### What Should Remain as Canonical Tables Even in a Graph-Aware System

- `lupo_actors`, `lupo_agents`, `lupo_auth_users`, `lupo_departments` — identity layer; must never be "just edges"
- `lupo_channels`, `lupo_dialog_threads`, `lupo_dialog_messages` — coordination layer; containment structure is too query-critical to edge-ify
- `lupo_tasks`, `lupo_projects` — workflow layer; task state machines need direct rows
- `lupo_rules`, `lupo_permissions` — policy layer; must remain queryable without graph traversal
- `lupo_metadata`, `lupo_atoms` — property/constant layer; key-value systems are not edges

---

## F. Lock-In Warnings

**Code must pause before writing new features in these areas until the ambiguity is resolved:**

### F1 — Edge Model Divergence [CRITICAL]
Any new feature that records a relationship between entities faces a choice: write to `lupo_edges`, `lupo_actor_edges`, `lupo_context_edges`, or create a new domain-specific edge table. Without a documented decision, each developer will pick a different table and the graph will continue to fragment. **WOLFIE must make the canonical edge surface decision explicit before any new relationship-graph code is merged.**

### F2 — CIP Analytics Anchor [CRITICAL]
`lupo_cip_analytics`, `lupo_cip_propagation_tracking`, `lupo_multi_agent_critique_sync`, and `lupo_emotional_geometry_calibrations` all reference a `cip_event_id` or `cip_analytics_id` chain that is not grounded in any table in `install_new_lupopedia.sql`. Code that writes to these tables is writing to an unanchored system. **Halt all CIP analytics writes until the anchor is confirmed or the tables are formally declared planning-phase-only.**

### F3 — Semantic Index Canon vs Projection [HIGH]
`lupo_semantic_index` has been written to as both a canonical store and a derived index in different code contexts. The next write to this table needs a clear designation. **Document the table as projection-only and audit all PHP callsites before adding new semantic content writes.**

### F4 — Authorization Overlap [HIGH]
`lupo_rolls` and `lupo_actor_channel_roles` potentially encode the same actor+channel+role relationship. **Do not add new authorization checks until it is confirmed which table is authoritative for channel-level role resolution.**

### F5 — Human Request Response Duplication [MODERATE]
`lupo_human_requests` embeds `response_text`, `response_auth_user_id`, `response_actor_id` directly as columns, AND a separate `lupo_human_request_responses` table exists for full response objects. **Any new "respond to request" feature must pick one path and migrate the other to a read-only fallback.**

---

## Appendix: Full Table Classification

| Table | Classification | Recommendation |
|---|---|---|
| lupo_actors | canonical identity | keep |
| lupo_agents | canonical behavioral config | keep |
| lupo_auth_users | canonical auth | keep |
| lupo_departments | canonical org | keep |
| lupo_actor_auth_users | canonical pairing | keep |
| lupo_actor_departments | canonical membership | keep |
| lupo_actor_channel_roles | canonical role | keep — audit vs lupo_rolls |
| lupo_agent_faucets | canonical execution surface | keep |
| lupo_channels | canonical workspace | keep |
| lupo_actor_channels | canonical membership | keep |
| lupo_dialog_threads | canonical coordination | keep |
| lupo_dialog_messages | canonical messages | keep |
| lupo_edges | **canonical edge store** | keep — must be the one canonical |
| lupo_edge_types | canonical type registry | keep — merge edge_type_definitions into here |
| lupo_edge_type_definitions | type registry duplicate | merge into lupo_edge_types |
| lupo_edge_map | edge index/duplicate | deprecate |
| lupo_actor_edges | domain-scoped duplicate | replace with lupo_edges writes |
| lupo_context_edges | domain-scoped duplicate | replace with lupo_edges writes |
| lupo_decision_edges | domain-scoped duplicate | replace with lupo_edges writes |
| lupo_decision_influences | overlaps decision_edges | merge with decision_edges or replace with lupo_edges |
| lupo_decisions | canonical decision entity | keep |
| lupo_decision_evidence | canonical evidence | keep |
| lupo_references | canonical citation entity | keep |
| lupo_reference_objects | redundant object registry | merge into lupo_references or lupo_metadata |
| lupo_reference_cited_by | inverted citation index | replace with lupo_edges edge_type=cited_by |
| lupo_reference_links | reference join table | merge with reference_map |
| lupo_reference_map | reference join table | merge with reference_links |
| lupo_cip_analytics | orphaned — anchor missing | halt writes until anchor confirmed |
| lupo_cip_propagation_tracking | orphaned — anchor missing | halt writes until anchor confirmed |
| lupo_multi_agent_critique_sync | orphaned — anchor missing | halt writes until anchor confirmed |
| lupo_emotional_frameworks | keep but fix PK to BIGINT | keep, redefine |
| lupo_emotional_geometry_calibrations | orphaned on CIP chain | defer until CIP anchor exists |
| lupo_calibration_impacts | orphaned on calibration_id | defer |
| lupo_channel_state | keep, remove context_vector | redefine |
| lupo_channel_boot_detail | boot_id unanchored | audit, consolidate |
| lupo_channel_boot_lifecycle | keep | keep |
| lupo_channel_boot_detail_lifecycle | may duplicate boot_detail | audit, consolidate |
| lupo_channel_departments | canonical join | keep |
| lupo_dialog_channels | dialog file import, not canonical | rename to lupo_dialog_imports |
| lupo_contexts | canonical context entity | keep |
| lupo_contexts_map | polymorphic join (slug-based) | normalize to id-based |
| lupo_collection_map | polymorphic join | keep, standardize columns |
| lupo_question_map | polymorphic join | keep, standardize columns |
| lupo_hashtag_map | polymorphic join | keep, standardize columns |
| lupo_folder_map | polymorphic join | keep, standardize columns |
| lupo_folders | storage org unit | keep |
| lupo_rolls | overlap with actor_channel_roles | audit, deprecate one |
| lupo_semantic_index | projection table | document as derived, add rebuild tracking |
| lupo_search_index | projection table | document as derived, add rebuild tracking |
| lupo_world_registry | undocumented | document or deprecate |
| lupo_analytics_campaign_vars | legacy ghost | deprecate |
| lupo_memory_rollups | projection/cache | document as derived |
| lupo_documentation_frameworks | tooling metadata | evaluate for lupo_atoms merge |
| lupo_thread_metadata | metadata sidecar | keep — but ensure lupo_metadata not duplicated |
| lupo_registry | canonical reservation system | keep |
| lupo_registry_open | registry slot tracker | keep, rename to lupo_registry_slots for clarity |
| lupo_human_requests | canonical request entity | keep, remove embedded response columns |
| lupo_human_request_context | canonical context | keep |
| lupo_human_request_responses | canonical response | keep |
| lupo_routing_decisions | operational log | keep |
| lupo_schema_migrations | migration tracking | keep |
| lupo_paths | legacy analytics path tracking | keep if actively rebuilt, else deprecate |
| lupo_paths_summary | projection of paths | document as derived |
| lupo_anubis_* (8 tables) | ANUBIS working memory | keep as custodian cluster |
| crafty_syntax_* (5 tables) | legacy namespace | keep isolated |
