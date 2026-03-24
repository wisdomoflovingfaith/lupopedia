---
lupopedia.headers:
  file_path_from_root: lupo-docs/versions/4.0.87/PLAN.md
  last_modified_utc: '20260324180128'
  channel_id: 42
  thread_id: 4.0.87-init
  actor_id: 102
  actor_name: cursor
  artifact_type: planning
  artifact_kind: version_plan
  purpose: Execution plan for version 4.0.87.
  when_updated: '20260324180128'
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/PLAN.md
  delegation_chain: cursor:root
lupopedia.footer:
  last_verified: '20260324180128'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# 4.0.87 PLAN

## Current Status: PHASE 2 ROLLOUT (ACTIVE)

### Session Achievements (Consolidated)
- **Identity Foundation**: Registered Junie (Actor 108). Standardized root user (0) and department (1). Unified configuration in `lupopedia-config.php`.
- **Relationship Graph**: Activated `lupo_edges` for channels and threads. Migrated legacy JSON and structural relationships into the graph (Tracks 1-3).
- **Header Doctrine**: Finalized Version Semantics Model and namespace distinction. Locked v4.0.84 baseline rewrite rules.
- **Ecosystem Compliance**: Audited 169 tables and updated documentation for 22+ agents.

## Workstreams

### WS1: Atoms and Version Propagation
- Validate and normalize all canonical version markers.
- Confirm runtime reads from `GLOBAL_CURRENT_LUPOPEDIA_VERSION` consistently.

### WS2: Channels and Documentation
- Reconcile channel routes, posting/security behavior, and channel docs.
- Ensure channel docs align with current code and doctrine.

### WS3: LUPOPEDIA HEADERS Implementation
- Audit header parsing/ingestion/serialization paths.
- Lock behavior for `lupopedia.init`, `lupopedia.edges`, and `lupopedia.footer`.
- Validate deterministic write rules and header verification workflow.

### WS4: Identity Model Implementation Clarity
- Document and verify actor vs agent vs auth_user vs department vs faucet rules.
- Validate DB schema usage and service-layer mapping consistency.

### WS5: Admin LLM Web Interface
- Validate existing admin UI path at `localhost/lupopedia/admin.php`.
- Implement/finalize LLM chatbot call integration and failure handling.
- Document configuration, auth boundaries, and test procedure.

### WS6: Lupo Folder Organization and Documentation Accuracy
- Run a dedicated channel stream for `lupo-*` folder organization and inventory accuracy.
- Identify and remove deprecated/redundant documentation artifacts.
- Reconcile `lupo-docs/` to latest 4.0.87 runtime and doctrine state.

### WS7: Database Documentation and Edge Governance Channels
- Channel 63: database-table docs + TOON/json-grounded reconciliation under `lupo-docs/database/lupopedia/tables`.
- Channel 64: edge lifecycle governance (create/infer/update), `lupopedia.edges` generation, and DB population paths.
- Ensure channel artifacts are produced in `lupo-channels/` for both streams.

### WS8: Edge Service Layer (NEXT)
- Implement `EdgeQueryService` (Track 4 of ATHENA_STRATEGY) to provide a unified API for graph traversal.
- Deprecate direct `thread_lineage` reads in favor of the service layer.

## Upgrade Path Lock
- No Lupopedia -> Lupopedia upgrade compatibility work is required in 4.0.87.
- Supported flows remain: fresh install, or import/upgrade from Crafty Syntax 3.7.5 baseline.

## Thread Update (2026-03-24: Metadata hardening)
- Added explicit script-tooling governance workstream: comment-based `lupopedia.headers` and `lupopedia.footer` support for `.py` and `.php` under `lupo-scripts`.
- Added validation tool path for script metadata freshness against `20260301000000` cutoff.
- Completed 4.0.87 artifact-level header/footer normalization to remove stale version field usage.
