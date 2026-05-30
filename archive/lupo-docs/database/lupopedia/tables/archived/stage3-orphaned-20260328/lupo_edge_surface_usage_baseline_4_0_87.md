---
lupopedia.headers:
  lupopedia.schema: database_table_audit
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_edge_surface_usage_baseline_4_0_87.md
  web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_edge_surface_usage_baseline_4_0_87
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: table_documentation
  artifact_kind: audit
  purpose: Usage map, callsite matrix, baseline counts, and duplicate-candidate baseline for edge and projection tables in 4.0.87.
  tags:
  - database
  - edge_model
  - baseline
  - 4.0.87
  when_updated: '20260325125224'
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: '20260325125224'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: wolfie:root
---

# Edge Surface Usage Baseline (4.0.87)

## Scope

This baseline covers the requested tables:

- lupo_actor_edges
- lupo_context_edges
- lupo_decision_edges
- lupo_edges
- lupo_edge_types
- lupo_edge_type_definitions
- lupo_semantic_index
- lupo_search_index
- lupo_memory_rollups

## 1. Usage Map (Reads/Writes)

### lupo_actor_edges

- Read: lupo-includes/classes/EmergentRoleDiscovery.php (multiple SELECT statements by actor and edge_type, plus domain/time window filters).
- Read: lupo-database/lupopedia/content/lupo-app/Services/ActorService.php (actor-switch authorization via edge_type = 'supports').
- Observed assumption: edge_type 'supports' is used as permission logic.

### lupo_context_edges

- Read/Write: app/Services/ContextGraph/EdgeService.php (INSERT, SELECT by source/target/edge_type, soft-delete UPDATE, reactivation UPDATE).
- Read: app/Services/ContextGraph/ChannelThreadEdgeMapService.php (channel/thread graph reads from context_edges).
- Read via resolver layer: app/Services/ContextGraph/ResolutionEngine.php (dependency/subtask/contradiction/refinement semantics executed against EdgeService query outputs).
- Observed assumptions: edge_type values include dependency, subtask, contradiction, refinement.

### lupo_decision_edges

- Read: lupo-database/lupopedia/content/lupo-app/Services/BayesianDecisionService.php
  - getOutgoingEdges(): SELECT by source_decision_id
  - getIncomingEdges(): SELECT by target_decision_id
- No direct write callsite found in active runtime service code during this pass.

### lupo_edges

- Read: lupo-includes/classes/EdgeQueryService.php (canonical read API; reads by object, type, channel, counts, existence).
- Read/Write (migration context): app/Services/EdgeMigrationService.php
  - INSERT channel_related edges from dialog_channels JSON
  - SELECT grouped counts by edge_type for verification
- Read/Write (legacy service path): lupo-database/lupopedia/content/lupo-app/Services/EdgeService.php
  - INSERT row
  - soft-delete UPDATE
  - SELECT by left/right/channel scope
- Write (migration utility): hephaestus_execute_migrations.php inserts channel_parent backfill rows.

### lupo_edge_types

- Read: lupo-includes/classes/EdgeQueryService.php (active edge type registry reads from edge_types).
- Read: check_edge_state.php count check.
- Write: hephaestus_execute_migrations.php seed inserts.
- Write: lupo-database/lupopedia/mysql/seed/seed_traits_edge_types_action_auth_4.0.69.sql canonical seed/upsert.

### lupo_edge_type_definitions

- Read: check_edge_state.php count check.
- Write: hephaestus_execute_migrations.php seed inserts.
- Write: lupo-database/lupopedia/mysql/seed/seed_traits_edge_types_action_auth_4.0.69.sql canonical seed/upsert.

### lupo_semantic_index

- No active runtime read/write callsite found in PHP service/controller code during this pass.

### lupo_search_index

- No active runtime read/write callsite found for prefixed table name lupo_search_index during this pass.
- Note: lupo-includes/classes/SearchIndexer.php references a non-prefixed table name search_index (legacy/parallel surface).

### lupo_memory_rollups

- No active runtime read/write callsite found in PHP service/controller code during this pass.
- schema-config list presence only (lupo-includes/schema-config.php).

## 2. Callsite Matrix

| Table | File Path | Read/Write | Query Shape | Edge Type Assumptions |
|---|---|---|---|---|
| lupo_actor_edges | lupo-includes/classes/EmergentRoleDiscovery.php | Read | SELECT grouped by edge_type; SELECT by actor/time windows | dynamic edge_type semantics in properties |
| lupo_actor_edges | lupo-database/lupopedia/content/lupo-app/Services/ActorService.php | Read | JOIN actors to actor_edges by source/target + is_deleted | hardcoded supports |
| lupo_context_edges | app/Services/ContextGraph/EdgeService.php | Read + Write | INSERT full row; SELECT by source/target/type; UPDATE soft-delete/reactivate | dependency, subtask, contradiction, refinement |
| lupo_context_edges | app/Services/ContextGraph/ChannelThreadEdgeMapService.php | Read | SELECT edges touching channel/thread typed nodes | channel/thread scoped edge_type usage |
| lupo_decision_edges | lupo-database/lupopedia/content/lupo-app/Services/BayesianDecisionService.php | Read | SELECT outgoing/incoming by source_decision_id/target_decision_id | decision graph edge types (table-driven) |
| lupo_edges | lupo-includes/classes/EdgeQueryService.php | Read | SELECT by object side, by edge_type, by channel_id, grouped counts, existence check | registry-driven edge_type slugs |
| lupo_edges | app/Services/EdgeMigrationService.php | Write + Read | INSERT channel_related migration rows; SELECT COUNT/GROUP BY edge_type for verification | channel_related |
| lupo_edges | lupo-database/lupopedia/content/lupo-app/Services/EdgeService.php | Write + Read | INSERT full row; UPDATE soft-delete; SELECT by left/right/channel | caller-supplied edge_type |
| lupo_edge_types | lupo-includes/classes/EdgeQueryService.php | Read | SELECT active edge types and lookup by slug | slug registry |
| lupo_edge_types | lupo-database/lupopedia/mysql/seed/seed_traits_edge_types_action_auth_4.0.69.sql | Write | INSERT/UPSERT 12 canonical channel/thread slugs | seeded 12 slugs |
| lupo_edge_type_definitions | lupo-database/lupopedia/mysql/seed/seed_traits_edge_types_action_auth_4.0.69.sql | Write | INSERT/UPSERT allowed left/right object type constraints | per-edge allowed object types |
| lupo_semantic_index | (no runtime callsite found) | None | None found | None found |
| lupo_search_index | (no runtime callsite found) | None | None found | None found |
| lupo_memory_rollups | (no runtime callsite found) | None | None found | None found |

## 3. Baseline Row Counts (captured 2026-03-25 UTC)

Read-only DB baseline query result:

| Table | Row Count |
|---|---:|
| lupo_actor_edges | 0 |
| lupo_context_edges | 0 |
| lupo_decision_edges | 0 |
| lupo_edges | 0 |
| lupo_edge_types | 0 |
| lupo_edge_type_definitions | 0 |
| lupo_semantic_index | 0 |
| lupo_search_index | 0 |
| lupo_memory_rollups | 0 |

## 4. Baseline Counts by edge_type

Cross-table edge_type aggregation result (actor_edges, context_edges, decision_edges, edges):

- None (no rows currently present).

## 5. Baseline Duplicate Candidate Counts Across Edge Tables

Duplicate-candidate identity used:

- left_object_type
- left_object_id
- right_object_type
- right_object_id
- edge_type

Mapped from source tables:

- lupo_actor_edges -> actor/actor mapping
- lupo_context_edges -> source_type/source_id -> target_type/target_id mapping
- lupo_decision_edges -> decision/decision mapping
- lupo_edges -> native columns

Result:

- duplicate_group_count = 0

## 6. LUPOPEDIA Headers Edge Type Guidance

This repository currently uses two related but distinct edge-type vocabularies.

### A) Database relationship edge types (stored in lupo_edges / lupo_edge_types)

These represent runtime structural graph relations between entities.

Examples seeded in 4.0.87 channel/thread package:

- channel_related
- channel_parent
- channel_successor
- channel_spawned_thread
- channel_references
- thread_continuation
- thread_spawned_from
- thread_references
- thread_crosses_channel
- channel_sibling
- artifact_spawned_from
- channel_observes

### B) Documentation header edge types (in lupopedia.edges.outbound_edges[].type)

These represent documentation graph semantics in file headers.

Observed active values in table/doc doctrine surfaces include:

- references
- schema_reference
- DEFINES_SCHEMA_FOR
- USED_IN_PHP
- USED_IN_PYTHON

Guidance:

- Treat A and B as separate vocabularies.
- Do not assume every header edge type belongs in lupo_edge_types.
- If header edges are projected into lupo_edges, map documentation edge semantics explicitly and consistently.

## 7. Immediate Documentation Follow-through

Recommended next updates in active table docs:

1. Normalize legacy headers in lupo_actor_edges.md and lupo_memory_rollups.md.
2. Mark lupo_semantic_index.md, lupo_search_index.md, and lupo_memory_rollups.md as projection/non-canonical surfaces in 4.0.87 doctrine context.
3. Add dedicated active docs for lupo_edge_types and lupo_edge_type_definitions (currently missing in active/).

