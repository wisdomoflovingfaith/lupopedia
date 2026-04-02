---
lupopedia.headers:
  lupopedia.schema: questions_snapshot
  file_path_from_root: lupo-channels/66/threads/1047/20260324_214000_ch66_unanswered_questions_inline_snapshot.md
  when_updated: '20260324214000'
  last_modified_utc: '20260324214000'
  channel_id: 66
  thread_id: 1047
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: analysis
  artifact_kind: unanswered_questions_snapshot
  purpose: Inline-ready snapshot of all currently unanswered Channel 66 questions after seed idempotency updates. ALL QUESTIONS RESOLVED 20260324_220000 by WOLFIE (actor_id 1, per thread 1054 takeover directive) — see 20260324_220000_cursor_answers_q1_q7_thread_1047.md.
  web_path: http://www.lupopedia.com/lupo-channels/66/threads/1047/20260324_214000_ch66_unanswered_questions_inline_snapshot.md
lupopedia.edges:
  outbound_edges:
  - to: lupo-channels/66/threads/1047/20260324_ch66_fresh_unanswered_questions.md
    type: references
    weight: 1.0
    reason: Canonical expanded queue with ownership and dependencies
  - to: lupo-database/lupopedia/mysql/seed/seed_traits_edge_types_action_auth_4.0.69.sql
    type: references
    weight: 0.8
    reason: Seed idempotency update resolves duplicate-key install failures
lupopedia.footer:
  last_verified: '20260324220000'
  last_verified_by: wolfie
  last_verified_by_actor_id: 1
  orchestrator: wolfie:root
  next_action:
  - Route Q4 to HEPHAESTUS (admin UI staleness panel in admin.php)
  - Route Q5 to HEPHAESTUS (generate_headers_from_db.py Tier 2/3 validation)
  - All other questions (Q1, Q2, Q3, Q6, Q7) are architecturally closed
---

# Channel 66 Unanswered Questions (Inline Snapshot)

## Scope

This snapshot lists all currently unanswered/open questions in Channel 66 that still require consultation, implementation, or governance decisions.

## Unanswered Questions

**STATUS: ALL RESOLVED 20260324_220000 — see 20260324_220000_cursor_answers_q1_q7_thread_1047.md**

1. Q1: Header Reimport Safety and Determinism
- Can headers be safely reimported into canonical DB with deterministic behavior?
- Status: ✅ RESOLVED — Reimport deprecated by design; new-record ingestion only via upsert on file_path_from_root.

2. Q2: Multi-Channel Header Ownership Model
- When same file appears in multiple channels, what metadata authority model is canonical?
- Status: ✅ RESOLVED — Creating channel owns single lupo_metadata record; cross-channel = lupopedia.edges only.

3. Q3: Header Immutability vs Editability
- Should headers be immutable/generated-only or editable with versioning controls?
- Status: ✅ RESOLVED — Files are immutable snapshots; edit DB record → regenerate file.

4. Q4: Staleness Detection Warnings
- How should stale header warnings/dashboard/alerts be implemented in admin UI?
- Status: ✅ DECIDED (implementation pending HEPHAESTUS) — Read-only panel in admin.php behind $isAdmin gate, query lupo_metadata for last_verified < 20260301000000 or NULL.

5. Q5: Timestamp Validation in generate_headers_from_db.py
- How should timestamp role validation and conflict/anomaly detection be enforced?
- Status: ✅ DECIDED (implementation pending HEPHAESTUS) — Three-tier validation: format, semantic range, role-integrity. Errors halt; warnings surface; all non-mutating.

6. Q6: Channel-Specific Metadata Authority
- Should when_updated be per-channel or file-global when edited across channels?
- Status: ✅ RESOLVED — when_updated is file-global. Never per-channel.

7. Q7: Permission Model for Header Reimport
- Who is authorized to run reimport into canonical DB and under what controls?
- Status: ✅ RESOLVED — Global admin only, CLI only, local environment, --dry-run default, --write flag required, audit log mandatory.

## Notes

- Fresh install seed path now includes idempotent edge/trait/action seeding to avoid duplicate-key failures on reruns.
- Canonical expanded detail remains in 20260324_ch66_fresh_unanswered_questions.md.

