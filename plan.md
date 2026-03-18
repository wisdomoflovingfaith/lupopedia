---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  file_path_from_root: "plan.md"
  channel_id: 42
  actor_id: 15
  actor_name: "hermes"
  artifact_kind: "plan"
  purpose: "Root plan — stabilization, enforcement, automation; gated on HERMES prompt queue"
---

# file: Root plan.md — channel system roadmap

**Paired:** [TODO.md](TODO.md) (**pending prompts table** = execution queue) · **Version plan:** [lupo-docs/versions/4.0.80/PLAN.md](lupo-docs/versions/4.0.80/PLAN.md) · **Release gate:** [011500_wolfie_4.0.80_remaining-work](lupo-channels/42/threads/1001/20260318_011500_wolfie_4.0.80_remaining-work.md) (five blockers + 4.0.81 deferrals).

**Directives:** [230500](lupo-channels/42/threads/1001/20260317_230500_wisdomoflovingfaith_channel-system-docs-and-routing-directive.md) · [231700](lupo-channels/42/threads/1001/20260317_231700_wisdomoflovingfaith_fix-the-system-now.md) · [MVP stabilization](lupo-channels/42/threads/1001/20260318_012000_wolfie_channel-hermes-mvp-stabilization.md) · [4.0.80 release readiness](lupo-channels/42/threads/1001/20260318_000500_wisdomoflovingfaith_release-readiness-4.0.80.md)

Phases are **dependency-ordered** (no time estimates). **Prompt file names** below live under `lupo-channels/42/prompts/`.

---

## Prompt queue (unblocks phases)

| Phase gap | Resolving prompts (targets) |
|-----------|-----------------------------|
| Canonical TODO + release picture | ~~**004501**~~ **done**; ~~**032100**~~ **done** ([051500](lupo-channels/42/threads/1001/20260318_051500_wolfie_4.0.80_release-readiness.md)); ~~**022020**~~ **done** |
| Controller / API + CI + A11 table docs | ~~**004502**~~ ~~**022010**~~ ~~**041100**~~ **done** ([180000_hephaestus_prompt-execution-complete](lupo-channels/42/threads/1002/20260318_180000_hephaestus_prompt-execution-complete.md)) |
| Prompts convention + External AI R3 + A12 record | ~~**004503**~~ ~~**022030**~~ ~~**041200**~~ **done** ([010000_lilith_prompts-complete-review](lupo-channels/42/threads/1001/20260318_010000_lilith_prompts-complete-review.md)) |
| Automation policy | ~~**004504**~~ ~~**022040**~~ **done** ([234200_athena_prompt-routing-watcher-policy](lupo-channels/42/threads/1002/20260317_234200_athena_prompt-routing-watcher-policy.md)) |
| HERMES tooling doc | **022050** (HERMES) — partial; script shipped |

WOLFIE **041000** (table-doc authorship), draft **230542** (stabilization overlap) — see root **[TODO.md](TODO.md)**.

---

## Phase 1 — Stabilization

**Depends on:** nothing.

**Completion when:**

- [x] Routing doctrine published (`CHANNEL_ARTIFACT_ROUTING_DOCTRINE`, thread Option A)
- [x] README explains channel tree + HERMES + `prompts/`
- [x] Human directive artifacts + HERMES prompts seeded

---

## Phase 2 — Enforcement

**Depends on:** Phase 1 complete.

**Completion when:**

- [x] Filename + thread validators in API/Python
- [x] **WOLFIE:** **004501** + **032100** + **022020** closed (see TODO.md / CHANGELOG **[4.0.80]**)
- [x] **HEPHAESTUS:** **004502** + **022010** + **041100** → [180000_hephaestus_prompt-execution-complete](lupo-channels/42/threads/1002/20260318_180000_hephaestus_prompt-execution-complete.md)

---

## Phase 3 — Automation

**Depends on:** Phase 2 completion criteria met (or release-readiness report defers items to 4.0.81).

**Completion when:**

- [x] MVP pipeline: `draft_hermes_prompt_from_artifact.py`; MVP plan artifact **012000**
- [ ] **HERMES** prompt **022050** — document batch scan + optional batch-draft policy pointer (partial; close to done)
- [x] **ATHENA** **004504** + **022040** → [234200](lupo-channels/42/threads/1002/20260317_234200_athena_prompt-routing-watcher-policy.md)
- [ ] **HEPHAESTUS:** filesystem watcher / auto-draft **implementation** per **234200** §3–§4 (policy done)
- [ ] **LILITH:** formal A12 pass/fail QA checklist artifact **010100** and gating signal for release

**Closed:** **LILITH** **004503** + **022030** + **041200** execution record ([010000](lupo-channels/42/threads/1001/20260318_010000_lilith_prompts-complete-review.md)). Formal A12 pass/fail + checklist **010100** still optional follow-up.

---

## After 4.0.80

- **4.0.81:** DB-primary channels, ingestion, external read — per **[051500](lupo-channels/42/threads/1001/20260318_051500_wolfie_4.0.80_release-readiness.md)** § Deferred; reinforced **[011500](lupo-channels/42/threads/1001/20260318_011500_wolfie_4.0.80_remaining-work.md)**.
- **4.0.81 doc/UI:** Artifact auto-heal; UI channel visualization; table-doc path dedup (`tables/active/projects/` vs `active/lupo_*.md`) — **non-blocker** for 4.0.80 per **011500**.
