---
lupopedia.headers:
  file_path_from_root: lupo-docs/versions/4.0.87/DOCUMENTATION_AND_EDGES_STREAM.md
  last_modified_utc: '20260324200640'
  channel_id: 63
  thread_id: database_documentation
  actor_id: 102
  actor_name: cursor
  artifact_type: planning
  artifact_kind: channel_stream
  purpose: 4.0.87 stream for database documentation and edge-governance channels.
  when_updated: '20260324200640'
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/DOCUMENTATION_AND_EDGES_STREAM.md
  delegation_chain: cursor:root
lupopedia.footer:
  last_verified: '20260324200640'
  last_verified_by: wolfie
  last_verified_by_actor_id: 1
  orchestrator: wolfie:root
---

# 4.0.87 Documentation and Edges Stream

## Channel 63: Database Documentation
- scope: `lupo-docs/database/lupopedia/tables`
- source inputs:
  - `lupo-database/lupopedia/json`
  - `lupo-database/lupopedia/toon` (169 TOON files)
- deliverables: table-doc accuracy, edge documentation completeness, deprecated doc cleanup

## Channel 64: Edge Generation Governance
- scope: edge creation, inference, update, persistence, and documentation
- focus:
  - edge lifecycle and mutation rules
  - `lupopedia.edges` generation from database/service state
  - database edge population and verification pathways
- deliverables: doctrine + implementation map for edge systems
- implemented checkpoint:
  - `GET api/context-graph/channel-map` for channel/thread edge visibility
  - channel artifacts in `lupo-channels/64/threads/edge_generation_governance/`

## 4.0.87 Scope Confirmation
- Both streams are in scope for 4.0.87.
- No Lupopedia -> Lupopedia upgrade compatibility scope is introduced.

## Channel 64 Update — 20260324 (cursor / ATHENA / ROSE)

Edge governance stream advanced significantly in this session:

- **Schema audit completed**: All six edge tables (`lupo_edges`, `lupo_edge_map`, `lupo_edge_types`, `lupo_edge_type_definitions`, `lupo_context_edges`, `lupo_actor_edges`) verified empty via TOON files. All fragment stores (`dialog_channels.channels` JSON, `thread_lineage` TEXT, `parent_channel_id` bigint) identified and documented.
- **Multi-actor dialog thread produced**: `EDGE_GRAPH_ANALYSIS_4_0_84.md` in channel 42 captures the full conversation chain from discovery through strategic analysis and relational framing to artifact.
- **Formal strategy artifact published**: `ATHENA_STRATEGY_20260324_120000_edge_graph_channel_thread_recommendations.md` in `lupo-actors/athena/docs/`. Contains all SQL and PHP needed for Tracks 1–4.
- **Canonical mandate established**: `lupo_edges` is the authoritative general-purpose relationship graph. `lupo_context_edges` is explicitly scoped to AI/agent cognitive context only. Mandate is now on record in both the artifact and `CHANNEL_THREAD_EDGES_STATUS.md`.
- **Collections boundary confirmed**: `lupo_collections` is UI/navigation bundling. `lupo_edges` is semantic/operational relationship traversal. These must not be conflated.
- **Next action**: Route Tracks 1–2 SQL seeds to HEPHAESTUS for execution — these are P0 and block all data-layer edge work.

## Thread Update (2026-03-24: DB docs + script governance)
- Regenerated `lupo-docs/database/lupopedia/tables/VALIDATION_REPORT_JUNIE.md` against current TOON state.
- Added `validate_script_footer_verification.py` to enforce script comment metadata freshness.
- Updated LUPOPEDIA HEADERS doctrine docs to include `.py` and `.php` comment-based metadata conventions.

## Thread Update (2026-03-24: edge actor review queue)
- Added `EDGE_REVIEW_QUEUE.md` under 4.0.87 docs to bind edge review items to actor owners.
- Opened channel 66 thread 1051 to confirm actor ownership and SLA for blocking edge items.

