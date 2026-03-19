---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  file_path_from_root: "plan.md"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  artifact_kind: "plan"
  purpose: "Root plan — stabilization, enforcement, automation; gated on HERMES prompt queue"
---

# file: Root plan.md — channel system roadmap

**Paired:** [TODO.md](TODO.md) (**Global Task Registry (Option A)**). **Historical version plans:** [lupo-docs/versions/4.0.80/PLAN.md](lupo-docs/versions/4.0.80/PLAN.md) (no separate 4.0.81 PLAN file — this root `plan.md` is live). **Release gate (historical name):** [011500](lupo-channels/42/threads/1001/20260318_011500_wolfie_4.0.80_remaining-work.md).

**Directives:** [Master shutdown consolidation 4.0.82](lupo-channels/1/threads/1035/20260319_190000_wolfie_master_shutdown_consolidation.md) · [230500](lupo-channels/42/threads/1001/20260317_230500_wisdomoflovingfaith_channel-system-docs-and-routing-directive.md) · [231700](lupo-channels/42/threads/1001/20260317_231700_wisdomoflovingfaith_fix-the-system-now.md) · [MVP stabilization](lupo-channels/42/threads/1001/20260318_012000_wolfie_channel-hermes-mvp-stabilization.md)

Phases are **dependency-ordered** (no time estimates). **Prompt file names** below live under `lupo-channels/42/prompts/`.

---

## Current phase (where the system actually is)

- **Version:** 4.0.82 only for active execution.
- **Done or materially advanced:** HERMES mapping + WOLFIE ratification; HEPHAESTUS copy-not-move for mapped threads + system-limits hooks (see CHANGELOG [4.0.82] checkpoint); actor–facet doctrine + `lupo-rules/root/` packs; Option A TODO/plan structure; many prompts closed (041000, 022050, etc.).
- **Blocking release-quality closure:** `task_prompt_010100` (A12.4 signoff) and `task_prompt_234200` (watcher acceptance) — see `TODO.md`.
- **Parallel / non-blocking until allocated:** `task_deferred_0001`–`0005` (dedupe, UI, DB-primary, auto-heal, external read).

## Next steps (ordered)

1. **LILITH + WOLFIE:** Close A12.4 constitutional signoff path → update `TODO.md` row `task_prompt_010100`.
2. **HEPHAESTUS + WOLFIE:** Watcher auto-draft acceptance → `task_prompt_234200`.
3. **Phase 1 — web_path normalization + cleanup:**
   - repo-wide deterministic web_path normalization: `python lupo-scripts/generate_web_path.py --repo-root . --apply --path .`
   - identity + filename violations cleanup (actor_name/actor_id registry mismatches; BAD_FILENAME artifacts) until channel validators are clean
4. **Phase 2 — RequestLimiter (rate/request limits):**
   - implement RequestLimiter for: login, channel posting, uploads (429/413/400 with stable error codes; audit logging; deterministic keys)
5. **Phase 3 — Global request guard + pagination/search limits:**
   - front controller guard: max body size, JSON depth/field limits, header size/count limits
   - pagination/search/export caps for expensive endpoints
6. **Validate:** `python lupo-scripts/validate_todo_plan.py --repo-root .`

## Dependencies

- **010100** and **234200** gate a coherent “stabilization complete” story before heavy deferred work (0001–0005).
- **Deferred tasks** depend on WOLFIE allocation (owner + thread) — currently unassigned in registry.

---

## Prompt queue (view; non-authoritative)

| Phase gap | Resolving prompts (targets) |
|-----------|-----------------------------|
| Canonical TODO + release picture | ~~**004501**~~ **done**; ~~**032100**~~ **done**; ~~**022020**~~ **done** ([051500](lupo-channels/42/threads/1001/20260318_051500_wolfie_4.0.81_release-readiness.md)); ~~**022050**~~ **done** |
| Controller / API + CI + A11 table docs | ~~**004502**~~ ~~**022010**~~ ~~**041100**~~ **done** ([180000_hephaestus_prompt-execution-complete](lupo-channels/42/threads/1002/20260318_180000_hephaestus_prompt-execution-complete.md)) |
| Prompts convention + External AI R3 + A12 record | ~~**004503**~~ ~~**022030**~~ ~~**041200**~~ **done** ([010000_lilith_prompts-complete-review](lupo-channels/42/threads/1001/20260318_010000_lilith_prompts-complete-review.md)) |
| Automation policy | ~~**004504**~~ ~~**022040**~~ **done** ([234200_athena_prompt-routing-watcher-policy](lupo-channels/42/threads/1002/20260317_234200_athena_prompt-routing-watcher-policy.md)) |

### 4.0.82 Active Work (Priority 0)

| Phase gap | Active work (targets) |
|-----------|---------------------|
| Channel migration execution | `task_id: task_channel_migration_execution_001` — **WOLFIE Directive 1033** — authorized |
| Actor-facet separation doctrine | `task_id: task_actor_facet_separation_doctrine_001` — **DOCTRINE COMPLETE** — implementation phase |
| Table-doc authorship fix | `task_id: task_prompt_041000` — **041000** (WOLFIE) — complete |
| External AI batch completion | `task_id: task_prompt_022050` — **022050** (HERMES) — complete |
| A12 QA checklist | `task_id: task_prompt_010100` — **010100** (LILITH) — pending |
| Prompt routing automation | `task_id: task_prompt_234200` — **234200** (HEPHAESTUS) — pending |

### Deferred Work (Carried into 4.0.82)

| Phase gap | Planned work |
|-----------|-------------|
| Table-doc path deduplication | TBD |
| UI channel visualization | TBD |
| DB-primary channel ingestion | TBD |
| Artifact auto-healing | TBD |
| External read capabilities | TBD |

---

## Phase 1 — Channel Migration Execution (Priority 0)

**Depends on:** Ratification + mapping (done). Facet doctrine can proceed in parallel.

**Completion when:**
- [x] HERMES thread-to-channel mapping complete (thread 1027)
- [x] WOLFIE migration ratification complete (thread 1033)
- [x] HEPHAESTUS copy-not-move for **24** mapped threads (per HERMES table) + redirects — see CHANGELOG [4.0.82]
- [ ] THOTH updates THREAD_INDEX / channel documentation as needed
- [ ] LILITH validates migration correctness (if not already closed)
- [ ] HERMES migration checklists (if still required)

**Registry links:**
- task_id: task_channel_migration_execution_001 — Channel 42 thread-to-channel migration execution

---

## Phase 2 — Actor-Facet Separation Implementation (Priority 0)

**Depends on:** Doctrine ratified (done). Can run **in parallel** with Phase 1 follow-ups.

**Completion when:**
- [x] ACTOR_FACET_SEPARATION_DOCTRINE.md created and ratified
- [x] lupo-rules/root/ canonical rule system created (20 YAML files)
- [ ] Facet bootstrapping validation complete
- [ ] Actor compliance verification complete
- [ ] Environment independence validation complete

**Registry links:**
- task_id: task_actor_facet_separation_doctrine_001 — Complete implementation of actor-facet separation doctrine

---

## Phase 3 — Stabilization (Rolled Forward)

**Depends on:** Phase 1 & 2

**Completion when:**
- [x] foundations moved
- [x] 022050 coverage report complete
- [ ] 234200 watcher
- [ ] 010100 LILITH checklist

**After next session (before release candidate):**
- 234200: finalize watcher auto-draft behavior (accept/revise draft status artifact)
- 010100: produce explicit constitutional rules compliance signoff artifact (A12.4) and re-run final verdict

**Registry links:**
- task_id: task_prompt_022050 — External AI batch completion
- task_id: task_prompt_234200 — Prompt routing automation (watcher)
- task_id: task_prompt_010100 — A12 QA checklist

---

## Phase 4 — Enforcement (Rolled Forward)

**Depends on:** Phase 3

**Completion when:**
- [x] `TODO.md` migrated to Global Task Registry (Option A) and is the single source of truth for task status/ownership/thread mapping
- [x] `plan.md` migrated to Strategic Roadmap (Option A) and references tasks by task_id only (prompt queue remains view)

**Registry links:**
- task_id: task_impl_001 — implement TODO.md + plan.md restructuring (this task)

---

## Phase 5 — Automation (Rolled Forward)

**Depends on:** Phase 4

**Completion when:**
- [ ] Watcher/auto-draft behavior operates within policy boundaries (help_response-only conditional; no overwrites; actor boundaries respected)

**Registry links:**
- task_id: task_prompt_234200 — automation policy + watcher followups (registry placeholder; allocation pending)

---

## Version truth

- **4.0.82** — sole active line; earlier versions are history only ([CHANGELOG.md](CHANGELOG.md)).

---

*Last updated: 2026-03-19 — aligned with WOLFIE master shutdown consolidation*
