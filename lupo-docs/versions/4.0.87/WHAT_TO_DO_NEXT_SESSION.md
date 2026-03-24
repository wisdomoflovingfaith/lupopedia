---
lupopedia.headers:
  file_path_from_root: lupo-docs/versions/4.0.87/WHAT_TO_DO_NEXT_SESSION.md
  last_modified_utc: '20260324215500'
  channel_id: 42
  thread_id: 4.0.87-init
  actor_id: 102
  actor_name: cursor
  artifact_type: handoff
  artifact_kind: next_session
  purpose: Next session execution checklist for 4.0.87.
  when_updated: '20260324215500'
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/WHAT_TO_DO_NEXT_SESSION.md
  delegation_chain: cursor:root
lupopedia.footer:
  last_verified: '20260324215500'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# 4.0.87 NEXT SESSION

1. Run atom/version audit and close any remaining 4.0.86 references.
2. Review channel code paths and update docs for exact runtime behavior.
3. Build header class behavior table for `init`, `edges`, `footer`.
4. Validate actor/agent/auth_user/department/faucet mapping with schema + services.
5. Test `admin.php` LLM call path on localhost and capture pass/fail evidence.
6. Execute Channel 62 folder-organization stream and publish lupo-folder inventory + cleanup actions.
7. **P0 — Execute edge graph Track 1**: Run `dev_20260324_seed_edge_types_channel_thread.sql` (SQL is in `lupo-actors/athena/docs/ATHENA_STRATEGY_20260324_120000_edge_graph_channel_thread_recommendations.md` Track 1). Route to HEPHAESTUS.
8. **P0 — Execute edge graph Track 2**: Run `dev_20260324_seed_edge_type_definitions.sql` (SQL in same artifact, Track 2). Route to HEPHAESTUS.
9. **P1 — Execute edge graph Track 3a + 3c**: Run channel JSON migration script and parent_channel_id backfill SQL. Both in ATHENA_STRATEGY artifact.
10. **P1 — Execute edge graph Track 5/6**: Create `lupo_context_edges.md` table doc (AI-scope-only note) and add deprecation notices to `lupo_dialog_threads.md` and `lupo_dialog_channels.md`. Route to THOTH.
11. Validate that the `api/context-graph/channel-map` endpoint reads from `lupo_edges` correctly after seeding.

## Thread Update (2026-03-24: Metadata hardening)
- Continue script metadata rollout for remaining `.py` / `.php` under `lupo-scripts`.
- Run `python lupo-scripts/validate_script_footer_verification.py --repo-root . --strict` and close failures.
- Keep 4.0.87 docs on `when_updated` + fresh `lupopedia.footer` validation fields.

## Thread Update (2026-03-24: Channel 66 question carry-forward)
- Carry forward all open Channel 66 questions from `lupo-channels/66/threads/1047/20260324_214000_ch66_unanswered_questions_inline_snapshot.md`:
  1. Q1: Header Reimport Safety and Determinism (awaiting ROSE external consultation)
  2. Q2: Multi-Channel Header Ownership Model (architectural decision pending)
  3. Q3: Header Immutability vs Editability (architectural decision pending)
  4. Q4: Staleness Detection Warnings (implementation pending)
  5. Q5: Timestamp Validation in `generate_headers_from_db.py` (implementation pending)
  6. Q6: Channel-Specific Metadata Authority (governance/architecture consensus pending)
  7. Q7: Permission Model for Header Reimport (security/governance decision pending)
- Route execution ownership:
  - Q1-Q3 -> ROSE / ATHENA / WOLFIE
  - Q4-Q5 -> HEPHAESTUS
  - Q6-Q7 -> THEMIS / HEIMDALL / LEXA
