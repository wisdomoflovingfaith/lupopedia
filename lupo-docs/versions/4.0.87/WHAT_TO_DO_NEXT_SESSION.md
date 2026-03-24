---
lupopedia.headers:
  file_path_from_root: lupo-docs/versions/4.0.87/WHAT_TO_DO_NEXT_SESSION.md
  last_modified_utc: '20260324230000'
  channel_id: 42
  thread_id: 4.0.87-init
  actor_id: 102
  actor_name: cursor
  artifact_type: handoff
  artifact_kind: next_session
  purpose: Next session execution checklist for 4.0.87. Updated by Cursor 20260324 23:00 UTC after Q4/Q5 implementation, EdgeQueryService, and channel 62/63/64 closures.
  when_updated: '20260324230000'
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/WHAT_TO_DO_NEXT_SESSION.md
  delegation_chain: cursor:root
lupopedia.footer:
  last_verified: '20260324230000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: wolfie:root
  next_action:
  - ERQ-006 WOLFIE release signoff (channel 66)
  - Remove dev diagnostic scripts after release (check_edge_state.php, check_metadata_state.php)
  - Finalize 4.0.87 release packet
---

# file: 4.0.87 next session handoff — delegation: cursor:root — web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/WHAT_TO_DO_NEXT_SESSION.md

# 4.0.87 NEXT SESSION

## Session State (as of 20260324 23:00 UTC)

- Cursor resumed ownership per WOLFIE takeover directive (thread 1054). All Q4/Q5 HEPHAESTUS-routed tasks executed by Cursor this session.
- Channel 66 thread 1047 Q1–Q7: **ALL RESOLVED** — see `lupo-channels/66/threads/1047/20260324_220000_cursor_answers_q1_q7_thread_1047.md`.
- Channel 66 threads 1050/1051/1052: **ALL RESOLVED** with published decision artifacts.
- P0 SQL migrations (ERQ-001/ERQ-002) confirmed executed: 12 rows in `lupo_edge_types`, 12 rows in `lupo_edge_type_definitions`.
- Channel 62/63/64 closure artifacts published this session.
- All code changes syntax-validated and tested.

## Ordered Execution Checklist

### ✅ Completed This Session (20260324 23:00 UTC)

1. **Q4**: Read-only staleness panel added to `admin.php` Dashboard behind `$isAdmin` — queries `lupo_metadata` for `last_verified < 20260301000000` or NULL, read-only.
2. **Q5**: Tier 2 (semantic range) and Tier 3 (role-integrity) validators added to `lupo-scripts/generate_headers_from_db.py`. Unit tests: 9/9 pass (`lupo-tests/unit/test_header_validators.py`).
3. **ERQ-001/ERQ-002 verified**: Both SQL migrations already executed (12 rows each). Backfill SQL is correct no-op (0 channels with parent_channel_id).
4. **EdgeQueryService**: `lupo-includes/classes/EdgeQueryService.php` created — 11 read-only query methods covering object/type/channel lookups, aggregate counts, and duplicate guard.
5. **Channel 62 closure**: `lupo-channels/62/threads/6201/20260324_230000_cursor_organization_pass_closure.md` published.
6. **Channel 63 closure**: `lupo-channels/63/threads/6301/20260324_230000_cursor_db_docs_reconciliation_closure.md` published.
7. **Channel 64 closure**: `lupo-channels/64/threads/6401/20260324_230000_cursor_edge_governance_closure.md` published (ERQ-001 ✅, ERQ-002 ✅, ERQ-006 pending WOLFIE).
8. **Channel 66 threads 1050/1051/1052**: Confirmed all three are already resolved (resolution artifacts from 20260324 session).

### 🔄 Remaining (Next Session)

1. **ERQ-006**: Route WOLFIE release signoff via channel 66 — blocks final 4.0.87 release gate.
2. **admin.php validation**: Validate `section=channel-chat` path against `/api/channels/{id}/messages` behavior; capture evidence artifact.
3. **Atom/version audit**: Close remaining `4.0.86` references; publish audit output.
4. **Release packet**: Finalize `lupo-docs/versions/4.0.87/CHANGELOG.md` with complete UTC execution log.
5. **Cleanup**: Remove dev diagnostic scripts (`check_edge_state.php`, `check_metadata_state.php`) from root after release.

## Resolved This Session (20260324 22:00 UTC — WOLFIE takeover per thread 1054 directive)

| Q | Decision | Status |
|---|----------|--------|
| Q1 | Header reimport deprecated; one-way DB→files; upsert for new records only | ✅ Closed |
| Q2 | Creating channel owns single `lupo_metadata` record; cross-channel = edges | ✅ Closed |
| Q3 | Headers in files are immutable snapshots; edit DB → regenerate | ✅ Closed |
| Q4 | Read-only staleness panel in `admin.php` behind `$isAdmin` | ✅ Implemented |
| Q5 | Tier 2 + Tier 3 timestamp checks in `generate_headers_from_db.py` | ✅ Implemented |
| Q6 | `when_updated` is file-global; never per-channel | ✅ Closed |
| Q7 | Global admin, CLI/local only, `--dry-run` default, audit log mandatory | ✅ Closed |

## Artifacts Published This Session (20260324 23:00 UTC)

- Channel 62 closure: `lupo-channels/62/threads/6201/20260324_230000_cursor_organization_pass_closure.md` ✅
- Channel 63 closure: `lupo-channels/63/threads/6301/20260324_230000_cursor_db_docs_reconciliation_closure.md` ✅
- Channel 64 closure: `lupo-channels/64/threads/6401/20260324_230000_cursor_edge_governance_closure.md` ✅
- `lupo-docs/versions/4.0.87/CHANGELOG.md` updated with session execution log ✅
- `lupo-docs/versions/4.0.87/TASK_REGISTRY.md` updated with V487-050 through V487-057 ✅
- `lupo-docs/versions/4.0.87/EDGE_REVIEW_QUEUE.md` updated (ERQ-001/002 closed) ✅

