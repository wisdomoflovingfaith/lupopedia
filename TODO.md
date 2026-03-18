---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  file_path_from_root: "TODO.md"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  artifact_kind: "task_list"
  purpose: "Active execution queue for 4.0.81 - rolled forward from 4.0.80"
---

# file: Root TODO — canonical multi-agent coordination (TSK001)

**Blessed by WOLFIE** per [todo-authority-alignment](lupo-channels/42/threads/1001/20260318_050000_wolfie_todo-authority-alignment.md) (closes HERMES prompt **004501**). Doctrine: [MULTI_AGENT §9](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md#9-todo-authority-two-tier).

**Authority:** [230500](lupo-channels/42/threads/1001/20260317_230500_wisdomoflovingfaith_channel-system-docs-and-routing-directive.md) · [4.0.81 release readiness](lupo-channels/42/threads/1001/20260318_051500_wolfie_4.0.81-release-readiness.md) · [LILITH remaining-work P0–P2](lupo-channels/42/threads/1001/20260318_001200_lilith_remaining-work-4.0.81.md). **Version backlog (not duplicate execution queue):** [lupo-docs/versions/4.0.81/TODO.md](lupo-docs/versions/4.0.81/TODO.md).

**Pick up work from `lupo-channels/42/prompts/`** — files below are **pending execution** until to target actor posts a result artifact or marks done in a follow-up commit.

---

## Global Task Registry (Option A)

| task_id | task_title | owner_actor | lifecycle_state | status | thread_id | priority | created_utc | updated_utc | primary_artifact | notes |
|---|---|---|---|---|---|---|---|---|---|---|
| task_impl_001 | Implement Option A TODO.md + plan.md restructuring | 14:hephaestus | active | in_progress | 1005 | P0 | 20260318_142300 | 20260318_142300 | lupo-channels/42/threads/1005/20260318_142300_hephaestus_directive_task_impl_001_kickoff.md | Binding implementation thread (1005); coordinated migration in progress. |
| task_prompt_010100 | A12 QA checklist | 2:lilith | open | planned | - | P0 | 20260318_142300 | 20260318_142300 | lupo-channels/42/prompts/20260318_010100_lilith_formal-a12-pass-fail-checklist.md | Prompt pending execution; no thread allocation recorded in registry yet. |
| task_prompt_022050 | External AI batch completion | 15:hermes | open | planned | - | P0 | 20260318_142300 | 20260318_142300 | lupo-channels/42/prompts/20260318_022050_hermes_externalai-batch.md | Prompt partial in legacy view; thread allocation not recorded in registry yet. |
| task_prompt_041000 | Table-doc authorship fix | 1:wolfie | open | planned | - | P0 | 20260318_142300 | 20260318_142300 | lupo-channels/42/prompts/20260318_041000_wolfie_table-doc-authorship-003000.md | Prompt pending execution; thread allocation not recorded in registry yet. |
| task_prompt_234200 | Prompt routing automation policy (watcher/auto-draft) | 14:hephaestus | open | planned | - | P0 | 20260318_142300 | 20260318_142300 | lupo-channels/42/threads/1002/20260317_234200_athena_prompt-routing-watcher-policy.md | Legacy artifact in thread 1002; implementation produced watcher draft/status earlier; registry thread allocation not recorded here. |
| task_plan_001 | Planning system spec (Option A) | 12:athena | resolved | complete | 1004 | P1 | 20260318_141109 | 20260318_150000 | lupo-channels/42/threads/1004/20260318_150000_wolfie_directive_task_plan_001_review.md | Spec accepted by WOLFIE; implementation allocated as task_impl_001. |
| task_doc_001 | Documentation alignment + thread model explanation | 26:thoth | resolved | complete | 1003 | P1 | 20260318_170000 | 20260318_175000 | lupo-channels/42/threads/1003/20260318_175000_thoth_status_task_doc_001_complete.md | README updated for 4.0.81 thread/task model; awaiting archival directive. |
| task_deferred_0001 | Table-doc path deduplication | - | open | planned | - | P2 | 20260318_142300 | 20260318_142300 | - | Migrated from legacy deferred list; needs WOLFIE allocation (owner + thread). |
| task_deferred_0002 | UI channel visualization | - | open | planned | - | P2 | 20260318_142300 | 20260318_142300 | - | Migrated from legacy deferred list; needs WOLFIE allocation (owner + thread). |
| task_deferred_0003 | DB-primary channel ingestion | - | open | planned | - | P2 | 20260318_142300 | 20260318_142300 | - | Migrated from legacy deferred list; needs WOLFIE allocation (owner + thread). |
| task_deferred_0004 | Artifact auto-healing | - | open | planned | - | P2 | 20260318_142300 | 20260318_142300 | - | Migrated from legacy deferred list; needs WOLFIE allocation (owner + thread). |
| task_deferred_0005 | External read capabilities | - | open | planned | - | P2 | 20260318_142300 | 20260318_142300 | - | Migrated from legacy deferred list; needs WOLFIE allocation (owner + thread). |

## 4.0.81 Active Tasks (Rolled Forward from 4.0.80)

### Release Blockers (view; non-authoritative — use registry rows above)

| # | Prompt | Target | Owner | Status | Notes |
|---|--------|--------|-------|--------|-------|
| 1 | [041000_wolfie_table-doc-authorship-003000.md](lupo-channels/42/prompts/20260318_041000_wolfie_table-doc-authorship-003000.md) | **WOLFIE** | pending | Fix authorship on `184500` repair artifact |
| 2 | [022050_hermes_externalai-batch.md](lupo-channels/42/prompts/20260318_022050_hermes_externalai-batch.md) | **HERMES** | partial | Complete documentation and full coverage report |
| 3 | [010100_lilith_formal-a12-pass-fail-checklist.md](lupo-channels/42/prompts/20260318_010100_lilith_formal-a12-pass-fail-checklist.md) | **LILITH** | pending | Formal A12 pass/fail QA checklist + result thread 1001 |
| 4 | [234200_athena_prompt-routing-watcher-policy.md](lupo-channels/42/threads/1002/20260317_234200_athena_prompt-routing-watcher-policy.md) §3–§4 | **HEPHAESTUS** | pending | Implement filesystem watcher / auto-draft per policy |

### 4.0.81 Deferred Work (Non-blocking)

| # | Task | Owner | Status | Notes |
|---|------|-------|--------|-------|
| 1 | Table-doc path deduplication | TBD | **4.0.81** | Resolve duplicate `tables/active/projects/` vs `active/lupo_*.md` paths |
| 2 | UI channel visualization | TBD | **4.0.81** | Build UI for browsing channel artifacts |
| 3 | DB-primary channel ingestion | TBD | **4.0.81** | Database-first channel operations |
| 4 | Artifact auto-healing | TBD | **4.0.81** | Self-repairing channel artifacts |
| 5 | External read capabilities | TBD | **4.0.81** | Public read-only channel access |

---

## Version History

- **4.0.80**: Finalized and released - All completed work preserved in [lupo-docs/versions/4.0.80/](lupo-docs/versions/4.0.80/)
- **4.0.81**: Active development - Current work rolled forward from 4.0.80

---

*Last updated: 2026-03-18*
