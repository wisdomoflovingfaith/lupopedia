---
lupopedia.headers:
  file_path_from_root: lupo-docs/versions/4.0.87/CHANNEL_THREAD_EDGES_STATUS.md
  last_modified_utc: '20260324200640'
  channel_id: 64
  thread_id: edge_generation_governance
  actor_id: 102
  actor_name: cursor
  artifact_type: documentation
  artifact_kind: implementation_status
  purpose: Current state and improvement status for channel/thread edge handling in
    4.0.87.
  when_updated: '20260324200640'
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/CHANNEL_THREAD_EDGES_STATUS.md
  delegation_chain: cursor:root
lupopedia.footer:
  last_verified: '20260324200640'
  last_verified_by: wolfie
  last_verified_by_actor_id: 1
  orchestrator: wolfie:root
---

# Channel and Thread Edges: 4.0.87 Status

## Current Handling
- Edge writes/reads are implemented through `lupo_context_edges` service layer.
- Edge validation scope explicitly supports:
  - `channel:thread`
  - `channel:channel`
  - `thread:thread`
- Channels and threads still have primary ownership structure in:
  - `lupo_channels`
  - `lupo_dialog_threads`

## Improvement Added
- New endpoint: `api/context-graph/channel-map`
- Purpose: one-call graph visibility for:
  - channel edges
  - related channels
  - channel threads
  - thread edges + related threads

## Collections Clarification
- `lupo_collections` remains useful for polymorphic content/UI grouping.
- Edge governance and channel/thread relationship truth should be read from graph + channel/thread tables, not inferred from collection membership alone.

---

## Deep Schema Audit — 20260324 (cursor / ATHENA / ROSE)

*Source: EDGE_GRAPH_ANALYSIS_4_0_84 thread, channel 42. Formal artifact: ATHENA_STRATEGY_20260324_120000.*

### Gap Analysis: Current Storage Fragments

| Relationship | Current store | Problem |
|---|---|---|
| Channel parent | `lupo_channels.parent_channel_id` (single bigint) | One-parent only; no sibling, fork, semantic, or successor edges |
| Related channels | `lupo_dialog_channels.channels` (JSON array) | Not SQL-queryable; requires full-table deserialization |
| Thread → channel | `lupo_dialog_threads.channel_id` (single bigint) | One channel only; cross-channel thread references impossible |
| Thread lineage | `lupo_dialog_threads.thread_lineage` (TEXT) | Free text; cannot be JOINed, traversed, or queried |
| Thread → thread | No mechanism | Continuations, forks, and citations are invisible |
| Channel state → edges | `lupo_channel_state.edge_visibility` exists | Column exists but nothing writes to it |

### Edge Table Status (TOON-verified)

| Table | Schema | Live Data |
|---|---|---|
| `lupo_edges` | Production-ready, polymorphic, weighted, channel-scoped, FLARE fields | **Empty** |
| `lupo_edge_map` | Typed lookup index on lupo_edges | **Empty** |
| `lupo_edge_types` | Registry of edge type slugs | **Empty** |
| `lupo_edge_type_definitions` | Allowed types per edge_type + bidirectional flag | **Empty** |
| `lupo_context_edges` | Lighter polymorphic edge table | **Empty** |
| `lupo_actor_edges` | Actor-to-actor only | **Empty** |

### Canonical Table Mandate (from ATHENA_STRATEGY)

| Table | Scope | Write authority |
|---|---|---|
| `lupo_edges` | All cross-entity relationships | EdgeService (application layer) |
| `lupo_edge_map` | Typed index; auto-populated by EdgeService | EdgeService only (automatic) |
| `lupo_edge_types` | Valid slug registry | Seed migration; admin extension only |
| `lupo_edge_type_definitions` | Per-type allowed types + bidirectionality | Seed migration |
| `lupo_context_edges` | **AI/agent cognitive context only** — reasoning chains, context window refs | Agent subsystem only |
| `lupo_actor_edges` | Actor-to-actor graph | Actor service only |

### Implementation Tracks (see ATHENA_STRATEGY artifact for full SQL/PHP)

| Track | Content | Priority |
|---|---|---|
| 1 | Seed `lupo_edge_types` — 12 slugs for channel/thread domain | **P0 — Blocking** |
| 2 | Seed `lupo_edge_type_definitions` | **P0 — Blocking** |
| 3a | Migrate `dialog_channels.channels` JSON → `lupo_edges` rows | P1 |
| 3b | Migrate `thread_lineage` TEXT → `lupo_edges` rows | P2 |
| 3c | Backfill `parent_channel_id` → `channel_parent` edge rows | P1 |
| 4 | Build `EdgeQueryService` PHP class | P2 |
| 5 | Document `lupo_context_edges` scope (create table doc) | P1 |
| 6 | Deprecation notices in `lupo_dialog_threads.md` and `lupo_dialog_channels.md` | P1 |

### Artifact Reference
- Thread: `lupo-database/lupopedia/channels/channel_id/42/threads/EDGE_GRAPH_ANALYSIS_4_0_84.md`
- Artifact: `lupo-actors/athena/docs/ATHENA_STRATEGY_20260324_120000_edge_graph_channel_thread_recommendations.md`

