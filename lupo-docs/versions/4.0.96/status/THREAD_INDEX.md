---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/versions/4.0.96/status/THREAD_INDEX.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.96/status/THREAD_INDEX.md
  last_modified_utc: "20260408161022"
  when_updated: "20260408161022"
  federation_node_id: 0
  channel_id: 42
  artifact_type: documentation
  artifact_kind: thread_index
  purpose: "Index for 4.0.96 status artifacts (PRD review, schema notes)"
  tags:
    - version
    - "4.0.96"
    - status
lupopedia.footer:
  last_verified: "20260408161022"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
---

# file: THREAD_INDEX — lupo-docs/versions/4.0.96/status

| Artifact | Summary |
|----------|---------|
| [PRD_REVIEW_DISCREPANCIES_AND_IMPROVEMENTS_20260407224750.md](PRD_REVIEW_DISCREPANCIES_AND_IMPROVEMENTS_20260407224750.md) | Cross-PRD discrepancies, index vs README drift, memory model forks, improvement backlog |
| [STATUS_SESSION_PRD_MEMORY_IDENTITY_20260407232053.md](STATUS_SESSION_PRD_MEMORY_IDENTITY_20260407232053.md) | Completed workstream summary + embedded forward prompt (constitutional PRD fixes) |
| [PRD_CONSISTENCY_AUDIT_20260407225301.md](PRD_CONSISTENCY_AUDIT_20260407225301.md) | Prior LILITH-style PRD/schema audit (if present) |
| [STATUS_SEED_PK_CREATED_YMDHIS_MEMORY_EXPORT_20260407235530.md](STATUS_SEED_PK_CREATED_YMDHIS_MEMORY_EXPORT_20260407235530.md) | Seed/reserved PK vs `created_ymdhis`; `created_ymdhis=0` → `lupo-memory/1970/01/` export (PRD 00/01/24/38 + MemoryExportService) |
| [STATUS_SEED_RUNTIME_PK_DOCTRINE_20260407235921.md](STATUS_SEED_RUNTIME_PK_DOCTRINE_20260407235921.md) | Global **§3.2.1** seed vs runtime PK strategy across tables; PRDs 01, 07, 15, 24, 38 aligned |
| [FOR_CLAUDE_CODE_ON_PK_IDS.md](FOR_CLAUDE_CODE_ON_PK_IDS.md) | Handoff for Claude Code (**116**): Chronological Trust Ladder, IdGenerator/KAIROS/registry validator, actors/DB/install assumptions, future web UI for install records |
| [STATUS_MEMORY_ARCHIVE_OPTION_B_20260408001717.md](STATUS_MEMORY_ARCHIVE_OPTION_B_20260408001717.md) | Memory archive Option B observations (PRD 38 §8, PRD 24 §5.8–5.9); no implementation claims |
| [STATUS_SESSION_IDENTITY_AND_GC_20260408161022.md](STATUS_SESSION_IDENTITY_AND_GC_20260408161022.md) | **Cursor (102)**: Session identity + GC handoff — salted fingerprints, `session_identity_hash`, probabilistic GC, slim `SessionManager`; observations and follow-ups |
| _(CHANGELOG.md entry 2026-04-08 09:51 UTC)_ | **Claude Code (116)**: CTL enforcement infrastructure — `audit_edge_integrity.py`, §13 test suite (49 tests, all pass), `StagingGcService`, staging-gc CLI, `AdminTrustLadderHandler`; `IdGenerator::isReservedSpace()` bug fix |
