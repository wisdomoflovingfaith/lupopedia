---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  file_path_from_root: "TODO.md"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  artifact_kind: "task_list"
  purpose: "Active execution queue for 4.0.82 - rolled forward from 4.0.81"
---

# file: Root TODO — canonical multi-agent coordination (TSK001)

**Blessed by WOLFIE** per [todo-authority-alignment](lupo-channels/42/threads/1001/20260318_050000_wolfie_todo-authority-alignment.md) (closes HERMES prompt **004501**). Doctrine: [MULTI_AGENT §9](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md#9-todo-authority-two-tier).

**Authority:** [230500](lupo-channels/42/threads/1001/20260317_230500_wisdomoflovingfaith_channel-system-docs-and-routing-directive.md) · [4.0.80 release readiness](lupo-channels/42/threads/1001/20260318_051500_wolfie_4.0.80_release-readiness.md) · [LILITH remaining-work P0–P2](lupo-channels/42/threads/1001/20260318_001200_lilith_remaining-work-4.0.80.md). **Master sync (4.0.82):** [WOLFIE master shutdown consolidation](lupo-channels/1/threads/1035/20260319_190000_wolfie_master_shutdown_consolidation.md). **Archived version backlogs:** e.g. [lupo-docs/versions/4.0.80/TODO.md](lupo-docs/versions/4.0.80/TODO.md) — not the live execution queue.

**Pick up work from `lupo-channels/42/prompts/`** — files below are **pending execution** until to target actor posts a result artifact or marks done in a follow-up commit.

---

## Global Task Registry (Option A)

| task_id | task_title | owner_actor | lifecycle_state | status | thread_id | priority | created_utc | updated_utc | primary_artifact | notes |
|---|---|---|---|---|---|---|---|---|---|---|
| task_impl_001 | Implement Option A TODO.md + plan.md restructuring | 14:hephaestus | resolved | complete | 1005 | P0 | 20260318_142300 | 20260318_142300 | lupo-channels/42/threads/1005/20260318_142300_hephaestus_directive_task_impl_001_kickoff.md | Structural migration complete (see thread 1005 status artifact). |
| task_prompt_010100 | A12 QA checklist | 2:lilith | open | planned | - | P0 | 20260318_142300 | 20260318_142300 | lupo-channels/42/threads/1001/20260318_093000_lilith_review_formal-a12-pass-fail-checklist.md | Formal A12 checklist artifact exists, but Final Verdict is FAIL (constitutional compliance signoff missing). |
| task_prompt_022050 | External AI batch completion | 15:hermes | resolved | complete | 1001 | P0 | 20260318_142300 | 20260318_142300 | lupo-channels/42/threads/1001/20260318_133928_hermes_report_externalai-batch-coverage-1001-1002.md | Coverage report deliverable fulfilled for threads 1001 + 1002 (what remains is A12 compliance + watcher acceptance). |
| task_prompt_041000 | Table-doc authorship fix | 1:wolfie | resolved | complete | 1001 | P0 | 20260318_142300 | 20260318_142300 | lupo-channels/42/threads/1001/20260318_090000_wolfie_directive_table-doc-authorship-closure.md | Prompt 041000 closed (authorship confirmed VALID; HERMES routing corrected). |
| task_prompt_234200 | Prompt routing automation policy (watcher/auto-draft) | 14:hephaestus | open | planned | - | P0 | 20260318_142300 | 20260318_142300 | lupo-channels/42/threads/1002/20260317_234200_athena_prompt-routing-watcher-policy.md | Spec exists in thread 1002; watcher auto-draft status is still `draft` in thread 1001, pending WOLFIE acceptance. |
| task_plan_001 | Planning system spec (Option A) | 12:athena | resolved | complete | 1004 | P1 | 20260318_141109 | 20260318_150000 | lupo-channels/42/threads/1004/20260318_150000_wolfie_directive_task_plan_001_review.md | Spec accepted by WOLFIE; implementation allocated as task_impl_001. |
| task_doc_001 | Documentation alignment + thread model explanation | 26:thoth | resolved | complete | 1003 | P1 | 20260318_170000 | 20260318_175000 | lupo-channels/42/threads/1003/20260318_175000_thoth_status_task_doc_001_complete.md | README updated for 4.0.81 thread/task model; awaiting archival directive. |
| task_deferred_0001 | Table-doc path deduplication | - | open | planned | - | P2 | 20260318_142300 | 20260318_142300 | - | Migrated from legacy deferred list; needs WOLFIE allocation (owner + thread). |
| task_deferred_0002 | UI channel visualization | - | open | planned | - | P2 | 20260318_142300 | 20260318_142300 | - | Migrated from legacy deferred list; needs WOLFIE allocation (owner + thread). |
| task_deferred_0003 | DB-primary channel ingestion | - | open | planned | - | P2 | 20260318_142300 | 20260318_142300 | - | Migrated from legacy deferred list; needs WOLFIE allocation (owner + thread). |
| task_deferred_0004 | Artifact auto-healing | - | open | planned | - | P2 | 20260318_142300 | 20260318_142300 | - | Migrated from legacy deferred list; needs WOLFIE allocation (owner + thread). |
| task_deferred_0005 | External read capabilities | - | open | planned | - | P2 | 20260318_142300 | 20260318_142300 | - | Migrated from legacy deferred list; needs WOLFIE allocation (owner + thread). |

## 4.0.82 Active Tasks (Rolled Forward from 4.0.81)

### Channel Migration Execution (Priority 0)
- **task_channel_migration_execution_001** — Channel 42 thread-to-channel migration (copy-not-move)
  - **Status**: HERMES table lists **24** mapped threads; HEPHAESTUS copy + redirects executed (see [enforcement_and_migration audit](lupo-channels/7/threads/1035/20260318_212358_hephaestus_enforcement_and_migration.md)); **THOTH** (THREAD_INDEX / docs) and **LILITH** (audit) and **HERMES** (checklists) may still need closure artifacts.
  - **Artifacts**: [HERMES mapping](lupo-channels/42/threads/1027/20260318_155033_hermes_report_thread_channel_mapping.md) · [Ratification 1033](lupo-channels/51/threads/1033/20260319_170500_wolfie_directive_channel-migration-ratification.md)

### Actor-Facet Separation Doctrine Implementation (Priority 0)
- **task_actor_facet_separation_doctrine_001** - Complete implementation of actor-facet separation doctrine
  - **Status**: Doctrine created, rule system complete
  - **Artifacts**: 
    - [ACTOR_FACET_SEPARATION_DOCTRINE.md](lupo-docs/doctrine/ACTOR_FACET_SEPARATION_DOCTRINE.md)
    - [lupo-rules/root/](lupo-rules/root/) - 20 canonical YAML rules
  - **Next Steps**: Facet bootstrapping validation, actor compliance verification
  - **Target**: All facets load rules from lupo-rules/root/

### Release Blockers (Carried into 4.0.82)

| # | Prompt | Target | Owner | Status | Notes |
|---|--------|--------|-------|--------|-------|
| 1 | [041000_wolfie_table-doc-authorship-003000.md](lupo-channels/42/prompts/20260318_041000_wolfie_table-doc-authorship-003000.md) | **WOLFIE** | done | Fix authorship on `184500` repair artifact |
| 2 | [022050_hermes_externalai-batch.md](lupo-channels/42/prompts/20260318_022050_hermes_externalai-batch.md) | **HERMES** | complete | Complete documentation and full coverage report |
| 3 | [010100_lilith_formal-a12-pass-fail-checklist.md](lupo-channels/42/threads/1001/20260318_093000_lilith_review_formal-a12-pass-fail-checklist.md) | **LILITH** | pending | Formal A12 pass/fail QA checklist + result (Final Verdict is FAIL; needs constitutional compliance signoff artifact) |
| 4 | [234200_athena_prompt-routing-watcher-policy.md](lupo-channels/42/threads/1002/20260317_234200_athena_prompt-routing-watcher-policy.md) §3–§4 | **HEPHAESTUS** | pending | Implement filesystem watcher / auto-draft per policy |

### 4.0.82 Deferred Work (Non-blocking)

| # | Task | Owner | Status | Notes |
|---|------|-------|--------|-------|
| 1 | Table-doc path deduplication | TBD | **4.0.82** | Resolve duplicate `tables/active/projects/` vs `active/lupo_*.md` paths |
| 2 | UI channel visualization | TBD | **4.0.82** | Build UI for browsing channel artifacts |
| 3 | DB-primary channel ingestion | TBD | **4.0.82** | Database-first channel operations |
| 4 | Artifact auto-healing | TBD | **4.0.82** | Self-repairing channel artifacts |
| 5 | External read capabilities | TBD | **4.0.82** | Public read-only channel access |

---

### Interpretation Header Hardening (Checkpoint)
- Interpretation-layer hardening is implemented and integrated: validators enforce stored YAML key casing, WHOAMI identity isolation, non-persistent `whoopposesyou` default resolution to `lilith`, and self-opposition rejection.
- Remaining for next session: run a repo-wide interpretation-header sweep (`python lupo-scripts/validate_interpretation_headers.py --file .`) and migrate any remaining stored artifacts that violate canonical casing or required structure (without rewriting persisted data during validation).

### Shutdown checkpoint (Global state sync)
- **task_global_state_sync_001** — confirm `CHANGELOG.md`, `TODO.md`, `plan.md` are aligned with: web_path deterministic rule, interpretation header model, and next limiter phases (login/posting/uploads; then global request guard; then pagination/search limits).

### web_path canonicalization (repo-wide)
- Run repo-wide deterministic normalization: `python lupo-scripts/generate_web_path.py --repo-root . --apply --path .`
- Re-run validation (Channel 42/other channels): `python lupo-scripts/validate_channel_artifacts.py --repo-root . --channel 42 --actor-identity-validation`

### Identity & filename violations (cleanup)
- Fix actor registry mismatches in channel artifacts (e.g. artifacts authored as `hermes` but carrying `actor_id: 102`, and `actor_id: 1000` using non-canonical `actor_name`).
- Normalize non-canonical filenames flagged by `validate_channel_artifacts.py` (BAD_FILENAME).

### Request / rate limits (not yet implemented)
- Implement **RequestLimiter Phase 1**: login + channel posting + uploads (429/413/400 with stable error codes; audit logging).
- Implement **global request guard** (front controller): request-size limits, JSON depth limits, header size/count limits.
- Add pagination/search/export caps for expensive endpoints.

## Version truth (4.0.82 only for active work)

- **4.0.82** — **current** — root `TODO.md` / `plan.md` / `CHANGELOG.md` / `GLOBAL_CURRENT_LUPOPEDIA_VERSION` in `lupo-config/config/global_atoms.yaml`.
- **4.0.81 / 4.0.80 / 4.0.79** — historical; unfinished items live only as rows above.

---

*Last updated: 2026-03-19 (master shutdown consolidation aligned)*
