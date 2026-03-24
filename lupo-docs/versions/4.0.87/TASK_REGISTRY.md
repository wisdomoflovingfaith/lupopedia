---
lupopedia.headers:
  file_path_from_root: lupo-docs/versions/4.0.87/TASK_REGISTRY.md
  last_modified_utc: '20260324180128'
  channel_id: 42
  thread_id: 4.0.87-init
  actor_id: 102
  actor_name: cursor
  artifact_type: planning
  artifact_kind: task_registry
  purpose: Task registry for version 4.0.87 workstreams.
  when_updated: '20260324180128'
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/TASK_REGISTRY.md
  delegation_chain: cursor:root
lupopedia.footer:
  last_verified: '20260324180128'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# 4.0.87 TASK REGISTRY

| Task ID | Workstream | Status | Owner | Notes |
|---|---|---|---|---|
| V487-001 | Atoms/version propagation | queued | unassigned | Canonical marker validation |
| V487-002 | Channel docs alignment | in_progress | cursor | Route + membership + API docs |
| V487-003 | Headers class matrix | completed | junie | Refactor v4.0.84, Version Semantics, Namespace |
| V487-004 | Identity model docs/implementation | completed | junie | Root 0, Junie 108, registry.json normalization |
| V487-005 | Admin LLM interface | in_progress | cursor | `admin.php` chatbot call flow |
| V487-006 | Admin channel chat UI | completed | cursor | `admin.php?section=channel-chat` with channel send/read flow |
| V487-007 | Effective actor routing for channel API | completed | cursor | Active actor + agent/department/user preferences used server-side |
| V487-008 | Chat acting-context display | completed | cursor | UI now states actor + selected channel context while sending |
| V487-009 | Channel 62 organization stream | in_progress | cursor | `lupo-*` folder organization, deprecation cleanup, docs accuracy |
| V487-010 | Channel 63 database docs stream | completed | junie | 169 table audit into status folders, TABLE_INDEX sync |
| V487-011 | Channel 64 edge governance stream | completed | junie | Edge Graph Activation (Tracks 1-3), seed/migration |
| V487-012 | Channel registry reconciliation | completed | cursor | Added missing active channels (including 58-61 and others) to canonical registry |
| V487-013 | Channel/thread edge map API | completed | cursor | Added `api/context-graph/channel-map` endpoint with access guard and summary map |
| V487-014 | Agent Documentation Rollout | completed | junie | Populated 22+ agents in lupo-agents/ |
| V487-015 | Config Consolidation | completed | junie | Unified root lupopedia-config.php |
| V487-016 | Contradiction Cleanup | completed | junie | Resolved identity contradictions in Channels 58-61 |
| V487-017 | Edge graph schema audit (TOON-based) | completed | cursor | Deep read of all 6 edge tables + channel/thread tables via TOON files; gaps documented |
| V487-018 | EDGE_GRAPH_ANALYSIS_4_0_84 thread | completed | cursor/athena/rose | 4-message dialog thread in channel 42: cursor discovery → ATHENA strategy → ROSE dialogue → artifact publication |
| V487-019 | ATHENA_STRATEGY_20260324_120000 artifact | completed | athena | Formal recommendations artifact: 6 tracks, SQL seeds for edge_types + edge_type_definitions, PHP migration skeleton, EdgeQueryService skeleton, example queries, priority matrix |
| V487-020 | Track 1: Seed lupo_edge_types | queued | hephaestus | SQL in ATHENA_STRATEGY artifact; P0 blocking |
| V487-021 | Track 2: Seed lupo_edge_type_definitions | queued | hephaestus | SQL in ATHENA_STRATEGY artifact; P0 blocking |
| V487-022 | Track 3a: Migrate dialog_channels.channels JSON | queued | hephaestus | PHP migration in ATHENA_STRATEGY artifact; P1 |
| V487-023 | Track 3c: Backfill parent_channel_id edges | queued | hephaestus | SQL in ATHENA_STRATEGY artifact; P1 |
| V487-024 | Track 4: EdgeQueryService PHP class | queued | hephaestus | app/Services/EdgeQueryService.php; P2 |
| V487-025 | Track 5/6: Context_edges doc + deprecation notices | queued | thoth | lupo_context_edges.md + updates to dialog_threads.md + dialog_channels.md; P1 |

## Thread Update (2026-03-24: Metadata hardening)
| V487-026 | 4.0.87 header/footer normalization | completed | cursor | All 4.0.87 docs now use `when_updated` + footer verifier fields |
| V487-027 | Script comment metadata doctrine + validator | completed | cursor | Added `validate_script_footer_verification.py` and doctrine updates |
| V487-028 | Script metadata rollout to key tooling files | completed | cursor | Added comment metadata to core validator/import scripts |
| V487-029 | Script metadata full-coverage sweep | queued | cursor | Extend to remaining legacy scripts under `lupo-scripts/` |
