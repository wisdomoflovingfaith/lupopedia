---
lupopedia.headers:
  file_path_from_root: lupo-docs/versions/4.0.87/WHAT_TO_DO_NEXT_SESSION.md
  last_modified_utc: '20260324200640'
  channel_id: 42
  thread_id: 4.0.87-init
  actor_id: 1
  actor_name: wolfie
  artifact_type: handoff
  artifact_kind: next_session
  purpose: Next session execution checklist for 4.0.87 with non-cursor takeover ownership.
  when_updated: '20260324200640'
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.87/WHAT_TO_DO_NEXT_SESSION.md
  delegation_chain: wolfie:root
lupopedia.footer:
  last_verified: '20260324200640'
  last_verified_by: wolfie
  last_verified_by_actor_id: 1
  orchestrator: wolfie:root
---

# 4.0.87 NEXT SESSION

## Temporary Ownership Constraint

- `cursor` and `junie` are unavailable until **2026-04-03 00:00:00 UTC**.
- Session work must be executed by: `wolfie`, `hephaestus`, `thoth`, `athena`, `themis`, `lilith`, `rose`.

## Ordered Execution Checklist

1. Channel 66 (`thread 1054`): confirm takeover ownership and acknowledge directive artifact.
2. Channel 64: verify edge governance queue closure for ERQ-001/ERQ-002/ERQ-006.
3. Channel 63: reconcile table docs against TOON/json for channel/thread/edge surfaces.
4. Channel 62: complete root/archive cleanup pass and publish manifest diff.
5. Validate `admin.php` call path (`section=channel-chat`) against `/api/channels/{id}/messages` read/write behavior and capture evidence artifact.
6. Close remaining `4.0.86` atom/version references and publish audit output.
7. Run channel 66 strict validation and ensure priority artifacts remain current:
   - `1050` (archive policy),
   - `1051` (edge ownership),
   - `1052` (pairing defaults),
   - `1054` (takeover directive).

## Open Questions That Still Need Answers

Source: `lupo-channels/66/threads/1047/20260324_214000_ch66_unanswered_questions_inline_snapshot.md`

1. Q1 Header reimport safety/determinism (owner: ROSE + ATHENA).
2. Q2 Multi-channel header ownership model (owner: THEMIS + WOLFIE).
3. Q3 Header immutability vs editability rule (owner: THEMIS + LILITH).
4. Q4 Staleness warning UX and error policy (owner: HEPHAESTUS).
5. Q5 Timestamp validation in regeneration pipeline (owner: HEPHAESTUS).
6. Q6 Channel-specific metadata authority model (owner: THEMIS + HEIMDALL).
7. Q7 Permission model for header reimport (owner: THEMIS + LEXA).

## Required Artifacts To Publish Next Session

- Channel 62 thread: organization pass completion + moved file manifest.
- Channel 63 thread: DB docs reconciliation evidence.
- Channel 64 thread: edge queue closure evidence.
- Channel 66 thread: unanswered question resolution deltas.
- `lupo-docs/versions/4.0.87/CHANGELOG.md` update with exact UTC execution log.

