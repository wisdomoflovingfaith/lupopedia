---
lupopedia.headers:
  file_path_from_root: lupo-docs/versions/4.0.87/TODO.md
  last_modified_utc: '20260324220000'
  channel_id: 42
  thread_id: 4.0.87-init
  actor_id: 102
  actor_name: cursor
  artifact_type: planning
  artifact_kind: version_todo
  purpose: Actionable TODO queue for 4.0.87.
  when_updated: '20260324220000'
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/TODO.md
  delegation_chain: cursor:root
lupopedia.footer:
  last_verified: '20260324200640'
  last_verified_by: wolfie
  last_verified_by_actor_id: 1
  orchestrator: wolfie:root
---

# 4.0.87 TODO

## Completed (Current Session)
- [x] Registered Junie (Actor 108) as a canonical agent with root department and user mapping.
- [x] Implemented LUPOPEDIA HEADERS Version Semantics Model and namespace distinction.
- [x] Created `VERIFICATION_GUIDE.md` for header auditing.
- [x] Unified configuration into root `lupopedia-config.php`.
- [x] Activated Edge Graph layer (ATHENA_STRATEGY Tracks 1-3: seeding and migration).
- [x] Normalized identity (Root user 0) across seeds and registries.
- [x] Reorganized 169 table documentation artifacts into status-based directories.
- [x] Updated `TABLE_INDEX.md` and core documentation (`README.md`, `AGENTS.md`).
- [x] Populated complete documentation for 22+ agents in `lupo-agents/`.
- [x] Resolved contradictions in Channels 58-61 regarding agent identities.
- [x] Added admin channel chat interface and effective actor resolution service.
- [x] Reconciled canonical channel registry with active channels.
- [x] Added context-graph API endpoint for channel-map visualization.

## Completed (20260324 — Edge Graph Session)
- [x] Deep TOON-based schema audit of all channel/thread edge tables — confirmed all six edge tables empty, three incompatible relationship stores identified.
- [x] Created discovery thread `EDGE_GRAPH_ANALYSIS_4_0_84` in channel 42 (4-actor dialog: cursor, ATHENA, ROSE, ATHENA).
- [x] Published `ATHENA_STRATEGY_20260324_120000_edge_graph_channel_thread_recommendations.md` — 6 implementation tracks, full SQL seeds, PHP migration skeleton, 4 example queries, priority matrix.
- [x] Confirmed `lupo_context_edges` scope: AI/agent cognitive context only (not general relationship storage).
- [x] Confirmed `lupo_collections` scope: UI/navigation bundling only (not edge traversal substitute).
- [x] Established canonical table mandate: `lupo_edges` is the authoritative general-purpose relationship graph.

## Remaining Work
- [ ] **P0** — Execute Track 1: Seed `lupo_edge_types` (SQL in ATHENA_STRATEGY artifact, route to HEPHAESTUS).
- [ ] **P0** — Execute Track 2: Seed `lupo_edge_type_definitions` (SQL in ATHENA_STRATEGY artifact).
- [ ] **P1** — Execute Track 3a: Run `EdgeMigrationService::migrateDialogChannelRelations()` (PHP in artifact).
- [ ] **P1** — Execute Track 3c: Backfill `parent_channel_id` → `channel_parent` edge rows (SQL in artifact).
- [ ] **P1** — Execute Track 5: Create `lupo_context_edges.md` table doc with AI-scope-only note.
- [ ] **P1** — Execute Track 6: Add deprecation notices to `lupo_dialog_threads.md` and `lupo_dialog_channels.md`.
- [ ] **P2** — Execute Track 4: Build `EdgeQueryService` PHP class (`app/Services/EdgeQueryService.php`).
- [ ] **P2** — Execute Track 3b: Migrate `thread_lineage` TEXT → `lupo_edges` rows (needs heuristic parsing).
- [ ] Complete full atom/version marker audit and close mismatches.
- [ ] Reconcile channel documentation to match live routing/security behavior.
- [ ] Produce `LUPOPEDIA_HEADERS` implementation matrix (`init`, `edges`, `footer`).
- [ ] Add/refresh docs for actor-agent-auth_user-department-faucet relationships.
- [ ] Verify DB docs for identity and membership tables are canonical and current.
- [ ] Validate admin LLM call path in `admin.php` end-to-end (request, auth, response, error cases).
- [ ] Add explicit test checklist for localhost verification.
- [ ] Run Channel 62 stream for `lupo-*` folder inventory, deprecation cleanup, and docs reconciliation.
- [ ] Remove/mark deprecated documentation that conflicts with current 4.0.87 doctrine/runtime.
- [ ] Run Channel 63 database-documentation stream (table docs + edges docs) against `lupo-database/lupopedia/json` and 169 TOONs in `lupo-database/lupopedia/toon`.
- [ ] Run Channel 64 edge-governance stream for edge creation/inference/update + `lupopedia.edges` generation and DB population rules.
- [ ] Complete channel registry reconciliation so active channel directories are present in canonical `channel_id/registry.json`.
- [ ] Update CHANGELOG and TASK_REGISTRY as work lands.

## Thread Update (2026-03-24: Metadata hardening)
- [x] Migrated 4.0.87 version artifacts from `version_when_written` to `when_updated`.
- [x] Added/normalized `lupopedia.footer` verification fields across 4.0.87 docs.
- [x] Added script metadata validation path for `.py`/`.php` tooling comments.
- [ ] Expand script metadata comments beyond key validators/importers to full `lupo-scripts` coverage.

## Thread Update (2026-03-24: Root organization + channel 66)
- [x] Archived high-confidence stale root files to `lupo-docs/archived/root_stale_20260324/`.
- [x] Opened channel 66 thread 1050 for archive scope and retention policy questions.
- [x] Opened channel 66 thread 1051 for edge-review actor ownership questions.
- [x] Added `EDGE_REVIEW_QUEUE.md` with actor-owned items and blocking release rule.

## Thread Update (2026-03-24: Major agent and pairing pass)
- [x] Updated major agent packets for WOLFIE/LILITH/ROSE/THEMIS/ATHENA/HEPHAESTUS/HERMES/IRIS/THOTH/VISHWAKARMA.
- [x] Added major-agent manifest `lupo-database/lupopedia/actors/major_agents_manifest.json`.
- [x] Added `MAJOR_AGENT_COVERAGE_AND_READ_ORDER.md`.
- [x] Added `ACTOR_PAIRING_USERS_DEPARTMENTS_MODEL.md`.
- [x] Added channel artifacts in 58/60/63/64 with explicit blocker edges to channel 66 questions.
- [x] Added channel 66 thread 1052 for actor pairing default policy blocker.

## Thread Update (2026-03-24: Channel 66 validation + relevance)
- [x] Validated all channel 66 thread artifacts under strict footer-validation mode (0 issues).
- [x] Added channel 66 relevance filter artifact (`thread 1053`).
- [x] Updated channel 66 index so 4.0.87 priority questions are explicit (1051, 1052, 1050).

## Session Refresh (2026-03-24 20:04 UTC)

### Newly Completed
- [x] Executed Track 1 migration: `dev_20260324_seed_edge_types_channel_thread.sql`.
- [x] Executed Track 2 migration: `dev_20260324_seed_edge_type_definitions.sql`.
- [x] Executed Track 3c migration: `dev_20260324_backfill_parent_channel_edges.sql`.
- [x] Added explicit answers for channel 66 production threads 1050/1051/1052.
- [x] Added channel 66 takeover directive thread 1054 with temporary owner map through 2026-04-03.
- [x] Added `lupo-scripts/run_edge_migration_track3a.php` runner and executed Track 3a pass.

### Still Pending (Blocking)
- [x] Resolve channel 66 thread 1047 remaining unanswered Q1-Q7. — ANSWERED 20260324_220000 by WOLFIE (actor_id 1, per thread 1054 takeover directive). See `lupo-channels/66/threads/1047/20260324_220000_cursor_answers_q1_q7_thread_1047.md`.
- [ ] Publish channel 62 organization completion artifact with manifest.
- [ ] Publish channel 63 DB docs reconciliation closure artifact.
- [ ] Publish channel 64 edge queue closure artifact.
- [ ] Finalize admin LLM/chatbot call path evidence artifact for 4.0.87 release packet.

## Session Refresh (2026-03-24 22:00 UTC — Junie handoff: WOLFIE takes over Q1-Q7 per thread 1054 directive)

### Decisions Made

- [x] Q1 — Header reimport: deprecated by design. New-record ingestion only via upsert on `file_path_from_root`. DB is always authoritative; regenerate files from DB.
- [x] Q2 — Multi-channel ownership: creating channel owns the single `lupo_metadata` record. Cross-channel presence is expressed via `lupopedia.edges` outbound_edges only.
- [x] Q3 — Immutability: headers in files are immutable snapshots. Edit DB record → regenerate file. No in-file YAML editing.
- [x] Q6 — `when_updated` scope: file-global. Never per-channel. Channel context for edits captured by edges.
- [x] Q7 — Permission model: global admin (`is_admin=1` or channel-1 captain), CLI only, local environment only, `--dry-run` default, `--write` required for mutations, audit log on every run.

### Still Requires Implementation (routed to HEPHAESTUS)

- [ ] Q4 — Staleness panel in `admin.php`: read-only section behind `$isAdmin` gate, query `lupo_metadata` for `last_verified < 20260301000000` or NULL.
- [ ] Q5 — Timestamp validation additions to `generate_headers_from_db.py`: Tier 2 (semantic range) and Tier 3 (role-integrity) checks.

