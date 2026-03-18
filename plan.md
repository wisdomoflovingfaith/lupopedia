---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  file_path_from_root: "plan.md"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  artifact_kind: "plan"
  purpose: "Root plan — stabilization, enforcement, automation; gated on HERMES prompt queue"
---

# file: Root plan.md — channel system roadmap

**Paired:** [TODO.md](TODO.md) (**Global Task Registry (Option A)** = authoritative status/ownership/thread mapping) · **Version plan:** [lupo-docs/versions/4.0.81/PLAN.md](lupo-docs/versions/4.0.81/PLAN.md) · **Release gate:** [011500_wolfie_4.0.81_remaining-work](lupo-channels/42/threads/1001/20260318_011500_wolfie_4.0.81_remaining-work.md) (five blockers + 4.0.81 deferrals).

**Directives:** [230500](lupo-channels/42/threads/1001/20260317_230500_wisdomoflovingfaith_channel-system-docs-and-routing-directive.md) · [231700](lupo-channels/42/threads/1001/20260317_231700_wisdomoflovingfaith_fix-the-system-now.md) · [MVP stabilization](lupo-channels/42/threads/1001/20260318_012000_wolfie_channel-hermes-mvp-stabilization.md) · [4.0.80 release readiness](lupo-channels/42/threads/1001/20260318_000500_wisdomoflovingfaith_release-readiness-4.0.80.md)

Phases are **dependency-ordered** (no time estimates). **Prompt file names** below live under `lupo-channels/42/prompts/`.

---

## Prompt queue (view; non-authoritative)

| Phase gap | Resolving prompts (targets) |
|-----------|-----------------------------|
| Canonical TODO + release picture | ~~**004501**~~ **done**; ~~**032100**~~ **done**; ~~**022020**~~ **done** ([051500](lupo-channels/42/threads/1001/20260318_051500_wolfie_4.0.81_release-readiness.md)); ~~**022050**~~ **done** |
| Controller / API + CI + A11 table docs | ~~**004502**~~ ~~**022010**~~ ~~**041100**~~ **done** ([180000_hephaestus_prompt-execution-complete](lupo-channels/42/threads/1002/20260318_180000_hephaestus_prompt-execution-complete.md)) |
| Prompts convention + External AI R3 + A12 record | ~~**004503**~~ ~~**022030**~~ ~~**041200**~~ **done** ([010000_lilith_prompts-complete-review](lupo-channels/42/threads/1001/20260318_010000_lilith_prompts-complete-review.md)) |
| Automation policy | ~~**004504**~~ ~~**022040**~~ **done** ([234200_athena_prompt-routing-watcher-policy](lupo-channels/42/threads/1002/20260317_234200_athena_prompt-routing-watcher-policy.md)) |

### 4.0.81 Active Work (Rolled Forward)

| Phase gap | Active prompts (targets) |
|-----------|---------------------|
| Table-doc authorship fix | `task_id: task_prompt_041000` — **041000** (WOLFIE) — pending |
| External AI batch completion | `task_id: task_prompt_022050` — **022050** (HERMES) — partial |
| A12 QA checklist | `task_id: task_prompt_010100` — **010100** (LILITH) — pending |
| Prompt routing automation | `task_id: task_prompt_234200` — **234200** (ATHENA) — pending |

### 4.0.81 Deferred Work (Non-blocking)

| Phase gap | Planned work |
|-----------|-------------|
| Table-doc path deduplication | TBD |
| UI channel visualization | TBD |
| DB-primary channel ingestion | TBD |
| Artifact auto-healing | TBD |
| External read capabilities | TBD |

---

## Phase 1 — Stabilization

**Depends on:** nothing

**Completion when:**
- [x] Routing doctrine published (`CHANNEL_ARTIFACT_ROUTING_DOCTRINE`, thread Option A)
- [x] README explains channel tree + HERMES + `prompts/`
- [x] Human directive artifacts + HERMES prompts seeded

**Registry links:**
- task_id: task_doc_001 — README and contributor guidance aligned to thread/task model
- task_id: task_plan_001 — planning system spec accepted (Option A)

---

## Phase 2 — Enforcement

**Depends on:** Phase 1

**Completion when:**
- [ ] `TODO.md` migrated to Global Task Registry (Option A) and is the single source of truth for task status/ownership/thread mapping
- [ ] `plan.md` migrated to Strategic Roadmap (Option A) and references tasks by task_id only (prompt queue remains view)

**Registry links:**
- task_id: task_impl_001 — implement TODO.md + plan.md restructuring (this task)

---

## Phase 3 — Automation

**Depends on:** Phase 2

**Completion when:**
- [ ] Watcher/auto-draft behavior operates within policy boundaries (help_response-only conditional; no overwrites; actor boundaries respected)

**Registry links:**
- task_id: task_prompt_234200 — automation policy + watcher followups (registry placeholder; allocation pending)

---

## Version History

- **4.0.80**: Completed - Channel-based coordination migration completed, all work archived
- **4.0.81**: Active development - Current work rolled forward from 4.0.80

---

*Last updated: 2026-03-18*
