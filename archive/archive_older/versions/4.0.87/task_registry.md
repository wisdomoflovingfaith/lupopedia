---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260325230000"
  file_path_from_root: "docs/versions/4.0.87/TASK_REGISTRY.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.87/TASK_REGISTRY.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: planning
  artifact_kind: task_registry
  thread_id: "4.0.87-init"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: 4.0.87 TASK REGISTRY â€” delegation: cursor:root â€” web_path: http://www.lupopedia.com/docs/versions/4.0.87/TASK_REGISTRY.md

# 4.0.87 TASK REGISTRY

| Task ID | Workstream | Status | Owner | Notes |
|---|---|---|---|---|
| V487-001 | Atoms/version propagation | completed | cursor | Carried over to 4.0.88 as V488-001 (release closeout migration) |
| V487-002 | Channel docs alignment | completed | cursor | Carried over to 4.0.88 as V488-002 (release closeout migration) |
| V487-003 | Headers class matrix | completed | junie | Refactor v4.0.84, Version Semantics, Namespace |
| V487-004 | Identity model docs/implementation | completed | junie | Root 0, Junie 108, registry.json normalization |
| V487-005 | Admin LLM interface | completed | cursor | Carried over to 4.0.88 as V488-003 (release closeout migration) |
| V487-006 | Admin channel chat UI | completed | cursor | `admin.php?section=channel-chat` with channel send/read flow |
| V487-007 | Effective actor routing for channel API | completed | cursor | Active actor + agent/department/user preferences used server-side |
| V487-008 | Chat acting-context display | completed | cursor | UI now states actor + selected channel context while sending |
| V487-009 | Channel 62 organization stream | completed | cursor | Closure 20260324: `channels/62/threads/6201/20260324_230000_cursor_organization_pass_closure.md` |
| V487-010 | Channel 63 database docs stream | completed | junie | 169 table audit into status folders, TABLE_INDEX sync |
| V487-011 | Channel 64 edge governance stream | completed | junie | Edge Graph Activation (Tracks 1-3), seed/migration |
| V487-012 | Channel registry reconciliation | completed | cursor | Added missing active channels (including 58-61 and others) to canonical registry |
| V487-013 | Channel/thread edge map API | completed | cursor | Added `api/context-graph/channel-map` endpoint with access guard and summary map |
| V487-014 | Agent Documentation Rollout | completed | junie | Populated 22+ agents in agents/ |
| V487-015 | Config Consolidation | completed | junie | Unified root lupopedia-config.php |
| V487-016 | Contradiction Cleanup | completed | junie | Resolved identity contradictions in Channels 58-61 |
| V487-017 | Edge graph schema audit (TOON-based) | completed | cursor | Deep read of all 6 edge tables + channel/thread tables via TOON files; gaps documented |
| V487-018 | EDGE_GRAPH_ANALYSIS_4_0_84 thread | completed | cursor/athena/rose | 4-message dialog thread in channel 42: cursor discovery â†’ ATHENA strategy â†’ ROSE dialogue â†’ artifact publication |
| V487-019 | ATHENA_STRATEGY_20260324_120000 artifact | completed | athena | Formal recommendations artifact: 6 tracks, SQL seeds for edge_types + edge_type_definitions, PHP migration skeleton, EdgeQueryService skeleton, example queries, priority matrix |
| V487-020 | Track 1: Seed lupo_edge_types | completed | hephaestus | Executed; 12 rows in `lupo_edge_types` (ERQ-001 closed) |
| V487-021 | Track 2: Seed lupo_edge_type_definitions | completed | hephaestus | Executed; 12 rows in `lupo_edge_type_definitions` (ERQ-002 closed) |
| V487-022 | Track 3a: Migrate dialog_channels.channels JSON | completed | hephaestus | Runner executed; zero rows for current dataset (see V487-045) |
| V487-023 | Track 3c: Backfill parent_channel_id edges | completed | hephaestus | Executed; no-op where no `parent_channel_id` set |
| V487-024 | Track 4: EdgeQueryService PHP class | completed | cursor | `includes/classes/EdgeQueryService.php` â€” 11 read-only query methods |
| V487-025 | Track 5/6: Context_edges doc + deprecation notices | completed | thoth | Docs per PHASE_2 / ATHENA_STRATEGY; scope notices on legacy JSON fields |

## Thread Update (2026-03-24: Metadata hardening)
| V487-026 | 4.0.87 header/footer normalization | completed | cursor | All 4.0.87 docs now use `when_updated` + footer verifier fields |
| V487-027 | Script comment metadata doctrine + validator | completed | cursor | Added `validate_script_footer_verification.py` and doctrine updates |
| V487-028 | Script metadata rollout to key tooling files | completed | cursor | Added comment metadata to core validator/import scripts |
| V487-029 | Script metadata full-coverage sweep | completed | cursor | Carried over to 4.0.88 as V488-004 (release closeout migration) |

## Thread Update (2026-03-24: Root organization + channel 66 questions)
| V487-030 | Root stale file archival pass | completed | cursor | Moved high-confidence stale/temp root files into `docs/archived/root_stale_20260324/` |
| V487-031 | Channel 66 root archive scope question | completed | cursor | Opened thread 1050 with policy questions |
| V487-032 | Channel 66 edge ownership question | completed | cursor | Opened thread 1051 with actor ownership questions |
| V487-033 | 4.0.87 edge review queue | completed | cursor | Added `EDGE_REVIEW_QUEUE.md` with actor-owned queue and blocking rule |

## Thread Update (2026-03-24: major agent and pairing pass)
| V487-034 | Major agent packet normalization | completed | cursor | Updated JSON + prompts for key agents including actor 91 as VISHWAKARMA |
| V487-035 | Major agent coverage/read-order doc | completed | cursor | Added `MAJOR_AGENT_COVERAGE_AND_READ_ORDER.md` |
| V487-036 | Actor pairing doctrine update | completed | cursor | Added `ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md` with db truth surfaces |
| V487-037 | Channel 63 pairing db truth artifact | completed | cursor | Added channel 63 thread 6301 with blocker edge to channel 66 thread 1052 |
| V487-038 | Channel 64 blocker edge map artifact | completed | cursor | Added channel 64 thread 6401 with explicit `blocks_on_question` edges |
| V487-039 | Channel 66 actor pairing defaults question | completed | cursor | Added thread 1052 to resolve pairing precedence |

## Thread Update (2026-03-24: Channel 66 validation and relevance)
| V487-040 | Channel 66 artifact canonicalization | completed | cursor | Normalized web_path/footer/actor context and resolved strict validation issues |
| V487-041 | Channel 66 relevance filter artifact | completed | cursor | Added thread 1053 and updated THREAD_INDEX with priority questions |

## Session Refresh (2026-03-24: takeover + execution pass)
| V487-042 | Track 1 edge type seed execution | completed | hephaestus | Executed via run_one_time_migration; version `20260324_edge_types_channel_thread` |
| V487-043 | Track 2 edge type definitions execution | completed | hephaestus | Executed via run_one_time_migration; version `20260324_edge_type_definitions` |
| V487-044 | Track 3c parent-channel backfill execution | completed | hephaestus | Executed via run_one_time_migration; version `20260324_backfill_parent_channel_edges` |
| V487-045 | Track 3a migration runner + execution | completed | hephaestus | Carried over to 4.0.88 as V488-005 for post-release monitoring (current run: zero rows) |
| V487-046 | Channel 66 production answers finalized | completed | wolfie | Answer artifacts posted for threads 1050/1051/1052 |
| V487-047 | Temporary owner reassignment (cursor/junie unavailable) | completed | wolfie | Takeover directive published in channel 66 thread 1054 |
| V487-048 | Channel 66 unresolved legacy question closure (Q1-Q7) | completed | wolfie | Q1-Q7 all answered 20260324_220000 under WOLFIE takeover directive (thread 1054); see channels/66/threads/1047/20260324_220000_cursor_answers_q1_q7_thread_1047.md |
| V487-049 | 4.0.87 release packet continuity docs refresh | completed | wolfie | PLAN/TODO/TASK_REGISTRY/WHAT_TO_DO_NEXT_SESSION/README synchronized 20260324_220000 under WOLFIE takeover |

## Session Update (2026-03-24 22:00 UTC â€” WOLFIE takeover from junie per thread 1054 directive)
| V487-050 | Q4: Admin UI staleness panel | completed | cursor | Read-only section added to `admin.php` Dashboard; queries `lupo_metadata` for stale/missing `last_verified`; `$isAdmin` gate; no mutations |
| V487-051 | Q5: Tier 2/3 timestamp validation in generate_headers_from_db.py | completed | cursor | `validate_timestamp_semantic_range()` + `validate_role_integrity()` added; called from `emit_staleness_warnings()`; 9/9 unit tests pass |

## Session Update (2026-03-24 23:00 UTC â€” Cursor execution pass)
| V487-052 | ERQ-001/002 SQL migration verification | completed | cursor | Confirmed 12 rows each in `lupo_edge_types` and `lupo_edge_type_definitions`; track 3c no-op confirmed |
| V487-053 | EdgeQueryService PHP class | completed | cursor | `includes/classes/EdgeQueryService.php` â€” 11 read-only methods: getEdgesForObject, getOutboundEdges, getInboundEdges, getEdgesByType, getChannelParentEdges, getEdgesByChannel, getEdgeTypes, getEdgeTypeBySlug, getEdgeCountsByType, getTotalEdgeCount, edgeExists |
| V487-054 | Channel 64 edge governance closure | completed | cursor | `channels/edge_generation_governance/threads/6401/20260324_230000_cursor_edge_governance_closure.md` â€” ERQ-001 âœ… ERQ-002 âœ… ERQ-006 pending WOLFIE |
| V487-055 | Channel 63 DB docs reconciliation closure | completed | cursor | `channels/63/threads/6301/20260324_230000_cursor_db_docs_reconciliation_closure.md` â€” surface tables reconciled |
| V487-056 | Channel 62 organization pass closure | completed | cursor | `channels/62/threads/6201/20260324_230000_cursor_organization_pass_closure.md` â€” root retention policy applied |
| V487-057 | Header validator unit tests | completed | cursor | `tests/unit/test_header_validators.py` â€” 9/9 tests pass (Tier 2 floor/future/order, Tier 3 valid/mismatch/unknown/skip/non-int) |
| V487-058 | Table optimization model correction | completed | cursor | Updated channel artifacts to lock actor-centric department mapping and effective actor resolution semantics |
| V487-059 | Slug-first channel directory doctrine update | completed | cursor | Updated `channels/channel_creation_doctrine.md` with canonical slug-first policy and legacy numeric compatibility |
| V487-060 | Channel index policy alignment | completed | cursor | Updated `channels/channel_index.md` and `channels/INDEX.md` for new-channel slug paths |
| V487-061 | 4.0.87 docpack sync from thread | completed | cursor | Synced README/PLAN/DOCTRINE/OVERVIEW/SCOPE/CHANGELOG/TODO/WHAT_TO_DO_NEXT_SESSION with thread outcomes |
| V487-062 | Admin UI identity alignment thread | completed | cursor | Published `20260325_163000_cursor_admin_ui_identity_alignment_4_0_87.md` in table-structure-optimization channel |
| V487-063 | ATHENA semantic table architecture review thread | completed | cursor/athena | Published `20260325_170000_athena_semantic_table_architecture_review_4_0_87.md` with full edge/table recommendations |
| V487-064 | CIP system active-surface removal | completed | cursor | Removed CIP tables from installer surfaces, deleted active CIP docs/TOON/JSON/CSV artifacts, and removed CIP runtime query function |
| V487-065 | 4.0.87 handoff and validation refresh | completed | cursor | Updated WHAT_TO_DO_NEXT_SESSION, CHANGELOG, README, OVERVIEW, PLAN, TODO, and TASK_REGISTRY to reflect 20260325 thread outcomes |

## Session Update (20260325 13:11 UTC — Bayesian decision table removal)
| V487-066 | Bayesian decision table removal — usage audit | completed | cursor | Full audit of `lupo_decisions`, `lupo_decision_edges`, `lupo_decision_evidence`, `lupo_decision_influences`; all four classified LEGACY_ONLY / UNUSED; zero runtime write paths confirmed |
| V487-067 | Bayesian decision table removal — PHP code deletion | completed | cursor | Deleted `BayesianDecisionService.php`, `decisions-api.php`, `bayesian_decision_service_test.php`; PHP scan clean |
| V487-068 | Bayesian decision table removal — install SQL cleanup | completed | cursor | Removed all four `CREATE TABLE` DDL blocks from `install_new_lupopedia.sql`; replaced with deprecation comment block; four active table docs marked `DEPRECATED`; `BAYESIAN_DECISION_DOCTRINE.md` superseded |
| V487-069 | DECISION_MODEL.md doctrine creation | completed | cursor | Created `docs/doctrine/DECISION_MODEL.md` — canonical doctrine: decisions live in channels/threads/artifacts; ROSE is interpretation layer; no decision-tracking table system |

## Session Update (20260325 ~20:00 UTC — Edge Model Consolidation — Workstream 2 — HEPHAESTUS/cursor)
| V487-070 | WS2: Edge consolidation pre-execution audit | completed | cursor | All 7 redundant edge tables confirmed empty/absent; `lupo_actor_edges` and `lupo_reference_cited_by` both 0 rows; `lupo_entity_edges`, `lupo_gov_event_actor_edges`, `lupo_gov_event_references` absent from live DB |
| V487-071 | WS2: Remove lupo_actor_edges DDL from install SQL | completed | cursor/hephaestus | Removed CREATE TABLE + 10 indexes from `install_new_lupopedia.sql`; replaced with deprecation comment |
| V487-072 | WS2: Remove lupo_reference_cited_by DDL from install SQL | completed | cursor/hephaestus | Removed CREATE TABLE + 5 indexes from `install_new_lupopedia.sql`; replaced with deprecation comment |
| V487-073 | WS2: EmergentRoleDiscovery.php query migration | completed | cursor/hephaestus | 3 SQL queries updated: `lupo_actor_edges` → `lupo_edges` with polymorphic column mapping (`left_object_type='actor'`, `right_object_type='actor'`) |
| V487-074 | WS2: ActorService.php JOIN migration | completed | cursor/hephaestus | `getActorsUserCanActAs()`: `$edgesT` var + JOIN condition updated from `lupo_actor_edges` source/target columns to `lupo_edges` polymorphic pattern |
| V487-075 | WS2: audit_schema_doctrine.php cleanup | completed | cursor/hephaestus | `lupo_actor_edges` removed from `$tablesRequiringSoftDelete` array — prevented false-positive audit failures |
| V487-076 | WS2: TOON files deleted | completed | cursor/hephaestus | Deleted `database/lupopedia/toon/lupo_actor_edges.toon` and `lupo_reference_cited_by.toon` |
| V487-077 | WS2: lupo_actor_edges.md moved to deprecated | completed | cursor/hephaestus | `tables/active/lupo_actor_edges.md` → `tables/deprecated/lupo_actor_edges.md`; headers updated to 4.0.87 deprecated status with `superseded_by: lupo_edges.md` |
| V487-078 | WS2: Dev migration script created | completed | cursor/hephaestus | Created `database/lupopedia/mysql/migrations/dev_20260325_remove_redundant_edge_tables.sql` — DROP IF EXISTS for both tables |
| V487-079 | WS2: HEPHAESTUS completion status artifact | completed | hephaestus | `channels/42/threads/1005/20260325_200000_hephaestus_status_edge_consolidation_execution_complete.md` — directed to WOLFIE, LILITH, ATHENA, ROSE |
| V487-080 | WS2 Phase A: lupo_edges.md canonical update (WOLFIE directive Task A) | completed | cursor/thoth | Updated: canonical status section, supported object types table, edge type registry table (10 types), consolidated query examples, supersedes list |
| V487-081 | WS2 Phase A: lupo_reference_cited_by.md deprecated doc (WOLFIE directive Task B) | completed | cursor/thoth | Replaced stale dual-header with clean 4.0.87 deprecated headers; added column migration mapping table, replacement queries, original DDL preserved |
| V487-082 | WS2 Phase A: EDGE_MODEL_DOCTRINE.md created (WOLFIE directive Task C) | completed | cursor/thoth | `docs/doctrine/EDGE_MODEL_DOCTRINE.md` — 9 sections: single table rule, polymorphism model, registered object types, edge type registry, direction convention, soft delete, domain scoping, properties field, prohibited patterns |
| V487-083 | WS2 Phase A: 4.0.87 docpack sync (WOLFIE directive Task D + this update) | completed | cursor | Updated CHANGELOG, TODO, TASK_REGISTRY, WHAT_TO_DO_NEXT_SESSION, PLAN for 20260325 ~20:00 UTC WS2 completion |
| V487-084 | WS3 Phase D: LILITH audit closure | completed | lilith | Verified documentation accuracy and security boundaries; no contradictions found |
| V487-085 | WS3 Phase E: THOTH doc sync closure | completed | thoth | Synchronized CHANGELOG/version docs and closed thread 1006 documentation flow |
| V487-086 | ERQ-006 release signoff closure | completed | wolfie | Release gate closed; production authorization granted |
| V487-087 | 4.0.87 release-state doc synchronization | completed | cursor | Updated CHANGELOG/TODO/PLAN/WHAT_TO_DO_NEXT_SESSION/TASK_REGISTRY to release-authorized state |

## Release Closeout Migration (2026-03-25 23:00 UTC)
| V487-088 | Carry open tasks to 4.0.88 | completed | cursor | Migrated V487-001, V487-002, V487-005, V487-029, V487-045 into `docs/versions/4.0.88/TASK_REGISTRY.md` as V488-001..V488-005 |

