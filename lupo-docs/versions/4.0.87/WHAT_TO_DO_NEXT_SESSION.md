---
lupopedia.headers:
  file_path_from_root: lupo-docs/versions/4.0.87/WHAT_TO_DO_NEXT_SESSION.md
  last_modified_utc: '20260324222000'
  channel_id: 42
  thread_id: 4.0.87-init
  actor_id: 1
  actor_name: wolfie
  artifact_type: handoff
  artifact_kind: next_session
  purpose: Next session execution checklist for 4.0.87. Updated after WOLFIE takeover (thread 1054) and completion of channel 66 thread 1047 Q1-Q7 resolution.
  when_updated: '20260324222000'
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/WHAT_TO_DO_NEXT_SESSION.md
  delegation_chain: wolfie:root
lupopedia.footer:
  last_verified: '20260324222000'
  last_verified_by: wolfie
  last_verified_by_actor_id: 1
  orchestrator: wolfie:root
  next_action:
  - Route HEPHAESTUS Q4/Q5 implementation tasks
  - Publish channel 62/63/64 closure artifacts
  - Finalize 4.0.87 release packet
---

# 4.0.87 NEXT SESSION

## Session State (as of 20260324 22:00 UTC)

- Junie exhausted tokens. WOLFIE (actor_id 1) took over per the channel 66 thread 1054 takeover directive. **Cursor did NOT resume ownership.**
- Channel 66 thread 1047 Q1–Q7: **ALL RESOLVED** under WOLFIE authority — see `lupo-channels/66/threads/1047/20260324_220000_cursor_answers_q1_q7_thread_1047.md`.
- Temporary actor reassignment (cursor/junie unavailable through 2026-04-03) remains in effect; WOLFIE is acting orchestrator.

## Ordered Execution Checklist (Remaining)

1. **HEPHAESTUS — Q4 implementation**: Add read-only staleness panel to `admin.php` behind `$isAdmin` gate; query `lupo_metadata` for `last_verified < 20260301000000` or NULL; no UI mutations.
2. **HEPHAESTUS — Q5 implementation**: Add Tier 2 (semantic range) and Tier 3 (role-integrity) timestamp validations to `lupo-scripts/generate_headers_from_db.py`.
3. Channel 64: verify edge governance queue closure for ERQ-001/ERQ-002/ERQ-006 and publish closure artifact.
4. Channel 63: reconcile table docs against TOON/json for channel/thread/edge surfaces and publish closure artifact.
5. Channel 62: complete root/archive cleanup pass and publish manifest diff artifact.
6. Validate `admin.php` call path (`section=channel-chat`) against `/api/channels/{id}/messages` read/write behavior; capture evidence artifact for 4.0.87 release packet.
7. Close remaining `4.0.86` atom/version references and publish audit output.
8. Run channel 66 strict validation; confirm priority artifacts remain current (1050, 1051, 1052, 1054).

## Resolved This Session (20260324 22:00 UTC — WOLFIE takeover per thread 1054 directive)

| Q | Decision | Status |
|---|----------|--------|
| Q1 | Header reimport deprecated; one-way DB→files; upsert for new records only | ✅ Closed |
| Q2 | Creating channel owns single `lupo_metadata` record; cross-channel = edges | ✅ Closed |
| Q3 | Headers in files are immutable snapshots; edit DB → regenerate | ✅ Closed |
| Q4 | Read-only staleness panel in `admin.php` behind `$isAdmin` | → HEPHAESTUS |
| Q5 | Tier 2 + Tier 3 timestamp checks in `generate_headers_from_db.py` | → HEPHAESTUS |
| Q6 | `when_updated` is file-global; never per-channel | ✅ Closed |
| Q7 | Global admin, CLI/local only, `--dry-run` default, audit log mandatory | ✅ Closed |

## Required Artifacts Still To Publish

- Channel 62 thread: organization pass completion + moved file manifest.
- Channel 63 thread: DB docs reconciliation evidence.
- Channel 64 thread: edge queue closure evidence.
- `lupo-docs/versions/4.0.87/CHANGELOG.md` update with exact UTC execution log for this session.

