---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  file_path_from_root: "TODO.md"
  channel_id: 42
  actor_id: 15
  actor_name: "hermes"
  artifact_kind: "task_list"
  purpose: "Root tasks + pending HERMES prompts inventory (lupo-channels/42/prompts/)"
---

# file: Root TODO — canonical multi-agent coordination (TSK001)

**Blessed by WOLFIE** per [todo-authority-alignment](lupo-channels/42/threads/1001/20260318_050000_wolfie_todo-authority-alignment.md) (closes HERMES prompt **004501**). Doctrine: [MULTI_AGENT §9](lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md#9-todo-authority-two-tier).

**Authority:** [230500](lupo-channels/42/threads/1001/20260317_230500_wisdomoflovingfaith_channel-system-docs-and-routing-directive.md) · [4.0.80 release readiness](lupo-channels/42/threads/1001/20260318_000500_wisdomoflovingfaith_release-readiness-4.0.80.md) · [LILITH remaining-work P0–P2](lupo-channels/42/threads/1001/20260318_001200_lilith_remaining-work-4.0.80.md). **Version backlog (not duplicate execution queue):** [lupo-docs/versions/4.0.80/TODO.md](lupo-docs/versions/4.0.80/TODO.md).

**Pick up work from `lupo-channels/42/prompts/`** — files below are **pending execution** until the target actor posts a result artifact or marks done in a follow-up commit.

**4.0.80 release gate (WOLFIE):** [011500_wolfie_4.0.80_remaining-work](lupo-channels/42/threads/1001/20260318_011500_wolfie_4.0.80_remaining-work.md) — **five blockers** before RC; **4.0.81 deferrals** listed there. Incomplete blocker → mark **4.0.81 deferred** + criteria in this file.

---

## 4.0.80 release blockers (per 011500)

| # | Item | Owner | Notes |
|---|------|-------|-------|
| 1 | Prompt **041000** — table-doc authorship (184500) | WOLFIE | Closure artifact thread **1001** + CHANGELOG |
| 2 | Prompt **230542** — stabilization vs MVP | WOLFIE | Consolidate or archive → **done** |
| 3 | Prompt **022050** — scan report + README | HERMES | Partial → complete |
| 4 | **010100** + A12 pass/fail | LILITH | Checklist + result thread **1001** |
| 5 | Watcher code | HEPHAESTUS | Per [234200](lupo-channels/42/threads/1002/20260317_234200_athena_prompt-routing-watcher-policy.md) |

**4.0.81 deferred (non-blocking for 4.0.80):** table-doc path dedup; UI channel visualization; DB-primary ingestion (see [051500](lupo-channels/42/threads/1001/20260318_051500_wolfie_4.0.80_release-readiness.md)).

---

## Pending HERMES prompts (`lupo-channels/42/prompts/`)

All paths relative to repo root. Status **pending** = handoff not claimed complete in channel + root docs.

| Prompt file | Target | Purpose (short) | Status |
|-------------|--------|-----------------|--------|
| [20260318_004501_hermes_prompt_wolfie_doctrine-todo-alignment.md](lupo-channels/42/prompts/20260318_004501_hermes_prompt_wolfie_doctrine-todo-alignment.md) | **WOLFIE** | Single canonical TODO authority | **done** — [050000_wolfie_todo-authority-alignment](lupo-channels/42/threads/1001/20260318_050000_wolfie_todo-authority-alignment.md) |
| [20260318_032100_hermes_prompt_wolfie_4.0.80-release-readiness.md](lupo-channels/42/prompts/20260318_032100_hermes_prompt_wolfie_4.0.80-release-readiness.md) | **WOLFIE** | `wolfie_4.0.80_release-readiness.md` report in thread 1001 | **done** — [051500_wolfie_4.0.80_release-readiness](lupo-channels/42/threads/1001/20260318_051500_wolfie_4.0.80_release-readiness.md) |
| [20260318_022020_hermes_prompt_wolfie_externalai-batch.md](lupo-channels/42/prompts/20260318_022020_hermes_prompt_wolfie_externalai-batch.md) | **WOLFIE** | External AI batch R2 — TODO/table-doc/CHANGELOG | **done** — [052500_wolfie_table-doc-ground-truth-status](lupo-channels/42/threads/1001/20260318_052500_wolfie_table-doc-ground-truth-status.md) |
| [20260317_230542_hermes_prompt_wolfie_system_stabilization.md](lupo-channels/42/prompts/20260317_230542_hermes_prompt_wolfie_system_stabilization.md) | **WOLFIE** | Draft from directive 231700 (overlap with MVP plan — consolidate or archive) | pending / duplicate? |
| [20260318_004502_hermes_prompt_hephaestus_prompts-api-scan.md](lupo-channels/42/prompts/20260318_004502_hermes_prompt_hephaestus_prompts-api-scan.md) | **HEPHAESTUS** | Controller / API list `prompts/` | **done** — [180000_hephaestus_prompt-execution-complete](lupo-channels/42/threads/1002/20260318_180000_hephaestus_prompt-execution-complete.md) |
| [20260318_022010_hermes_prompt_hephaestus_externalai-batch.md](lupo-channels/42/prompts/20260318_022010_hermes_prompt_hephaestus_externalai-batch.md) | **HEPHAESTUS** | External AI batch R1 — API drift, CI enforce, optional fix_channel | **done** — [180000_hephaestus_prompt-execution-complete](lupo-channels/42/threads/1002/20260318_180000_hephaestus_prompt-execution-complete.md) |
| [20260318_041100_hermes_prompt_hephaestus_top50-a11-table-docs.md](lupo-channels/42/prompts/20260318_041100_hermes_prompt_hephaestus_top50-a11-table-docs.md) | **HEPHAESTUS** | Top 50 A11 — projects/tasks/rules/orchestrator_rules table docs | **done** — [180000_hephaestus_prompt-execution-complete](lupo-channels/42/threads/1002/20260318_180000_hephaestus_prompt-execution-complete.md) |
| [20260318_004503_hermes_prompt_lilith_review-prompts-readme.md](lupo-channels/42/prompts/20260318_004503_hermes_prompt_lilith_review-prompts-readme.md) | **LILITH** | Review prompts README convention | **done** — [010000_lilith_prompts-complete-review](lupo-channels/42/threads/1001/20260318_010000_lilith_prompts-complete-review.md) |
| [20260318_022030_hermes_prompt_lilith_externalai-batch.md](lupo-channels/42/prompts/20260318_022030_hermes_prompt_lilith_externalai-batch.md) | **LILITH** | External AI batch R3 — API vs review contract, CI recommend | **done** — same |
| [20260318_004504_hermes_prompt_athena_prompt-routing-policy.md](lupo-channels/42/prompts/20260318_004504_hermes_prompt_athena_prompt-routing-policy.md) | **ATHENA** | Full-auto classification / watcher policy | **done** — [234200_athena_prompt-routing-watcher-policy](lupo-channels/42/threads/1002/20260317_234200_athena_prompt-routing-watcher-policy.md) |
| [20260318_022040_hermes_prompt_athena_externalai-batch.md](lupo-channels/42/prompts/20260318_022040_hermes_prompt_athena_externalai-batch.md) | **ATHENA** | External AI batch R4 — watcher / auto-draft policy | **done** — same artifact |
| [20260318_022050_hermes_prompt_hermes_externalai-batch.md](lupo-channels/42/prompts/20260318_022050_hermes_prompt_hermes_externalai-batch.md) | **HERMES** | `hermes_scan_threads.py` (done) + thread 1002 artifact + README doc | partial — script shipped; doc/artifact optional |
| [20260318_041000_hermes_prompt_wolfie_table-doc-authorship-003000.md](lupo-channels/42/prompts/20260318_041000_hermes_prompt_wolfie_table-doc-authorship-003000.md) | **WOLFIE** | Fix authorship on `184500` repair artifact (`003000`) | pending |
| [20260318_041200_hermes_prompt_lilith_top50-a12-qa.md](lupo-channels/42/prompts/20260318_041200_hermes_prompt_lilith_top50-a12-qa.md) | **LILITH** | Top 50 A12 — QA after A8–A11 | **done** (execution record) — formal pass/fail QA after **041100**; checklist **010100** per LILITH §4 |

**Coverage index (thread ↔ prompt):** [threads/1002/20260318_043000_hermes_actionable-prompts-coverage-1001-1002.md](lupo-channels/42/threads/1002/20260318_043000_hermes_actionable-prompts-coverage-1001-1002.md).

**README:** [lupo-channels/42/prompts/README.md](lupo-channels/42/prompts/README.md) (conventions, not a task).

---

## High priority (non-prompt)

| Task | Owner | Status | Ref |
|------|-------|--------|-----|
| Filename + thread validation CI | HEPHAESTUS | mostly done | `validate_channel_artifacts.py --mode enforce` |
| Thread Option A seed | WOLFIE | done | `seed_channel_42_dialog_threads_4.0.80.sql` |
| Role-based API | — | done | `channels-api.php` |
| Impersonation doctrine | WOLFIE | in progress | MULTI_AGENT §5.3; `003000_hermes_actor-identity` |

---

## Medium / low

| Task | Owner | Status |
|------|-------|--------|
| Python sync scripts | — | done |
| Artifact validation | — | extend `--mode enforce` |
| UI channel visualization | TBD | **4.0.81** (per [011500](lupo-channels/42/threads/1001/20260318_011500_wolfie_4.0.80_remaining-work.md)) |
| Watcher / classify automation | HEPHAESTUS + ATHENA | **policy done** ([234200_athena_prompt-routing-watcher-policy](lupo-channels/42/threads/1002/20260317_234200_athena_prompt-routing-watcher-policy.md)); implement §3–§4 |

---

When a prompt is **fully executed**, remove or mark **done** in the table above and note in **CHANGELOG** under 4.0.80.
