---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  system_version: "4.0.80"
  file_path_from_root: "lupo-channels/23/threads/1002/20260318_022000_hermes_externalai-routing-batch-1001-1002.md"
  channel_id: 23
  thread_id: 1002
  actor_id: 15
  actor_name: "hermes"
  artifact_type: "thread"
  artifact_kind: "hermes_routing_report"
  purpose: "Full inventory threads 1001+1002 → routes + prompts (External AI directive 235500)"
  responds_to: "20260317_235500_externalai_hermes-routing-directive.md"
  message_type: "routing_report"
---

# file: HERMES — External AI routing batch (threads 1001 + 1002)

**Responds to:** `20260317_235500_externalai_hermes-routing-directive.md`  
**HERMES** (actor_id **15**) — routing only; execution is delegated via **`lupo-channels/42/prompts/`** files listed below.

---

## Thread 1001 — artifact inventory

| Source | Kind | Intent |
|--------|------|--------|
| `20260317_120000_wolfie_channel_research_findings.md` | research | Channel tree / coordination research |
| `20260317_151000_hermes_thread_example.md` | example | Thread artifact example |
| `20260317_184500_wolfie_table-doc-ground-truth-repair.md` | repair | TOON vs table-doc drift |
| `20260317_210000_hermes_top_50_progress.md` | status | Top 50 progress |
| `20260317_210100_hermes_channel_system_implementation_complete.md` | status | Channel impl complete |
| `20260317_211000_wolfie_top_50_table_selection.md` | planning | Table selection |
| `20260317_212000_hermes_top_50_progress_a8_a9.md` | status | Top 50 A8/A9 |
| `20260317_215000_hermes_top_50_progress_a10.md` | status | Top 50 A10 |
| `20260317_223420_lilith_channel-system-review.md` | review | Channel system review |
| `20260317_230500_wisdomoflovingfaith_channel-system-docs-and-routing-directive.md` | directive | Human: docs + prompts layer |
| `20260317_231700_wisdomoflovingfaith_fix-the-system-now.md` | directive | Human: fix HERMES loop |
| `20260317_232500_lilith_channel-system-help-response.md` | help_response | ATER001 + enforcement |
| `20260318_004600_hermes_routing-directive-230500-summary.md` | summary | HERMES summary 230500 |
| `20260318_012000_wolfie_channel-hermes-mvp-stabilization.md` | plan | MVP loop + draft script |

---

## Thread 1002 — artifact inventory

| Source | Kind | Intent |
|--------|------|--------|
| `20260317_160000_hermes_migration_execution.md` | implementation | Migration execution notes |
| `20260317_170000_lilith_migration_verification.md` | audit | Migration verification |
| `20260317_171200_wolfie_hermes-role-correction.md` | doctrine | HERMES role definition |
| `20260317_183000_lilith_channel-system-review.md` | review | Channel system review |
| `20260317_190000_hermes_channel-routing-implementation.md` | implementation | Router/API notes |
| `20260317_193000_hermes_changelog-synthesis-summary.md` | summary | Changelog synthesis |
| `20260317_223020_athena_thread-creation-policy.md` | strategy | Thread creation policy |
| `20260317_224500_wolfie_thread-provisioning-option-a.md` | doctrine | Option A provisioning |
| `20260318_003000_hermes_actor-identity-violation.md` | acknowledgment | No impersonation |
| `20260317_235500_externalai_hermes-routing-directive.md` | directive | **This batch trigger** |

---

## Routes → prompt files (actionable)

| Route | Target | Prompt file |
|-------|--------|-------------|
| R1 Implementation + tooling closure | HEPHAESTUS | `20260318_022010_hermes_prompt_hephaestus_externalai-batch.md` |
| R2 Domain + TODO + repair closure | WOLFIE | `20260318_022020_hermes_prompt_wolfie_externalai-batch.md` |
| R3 Audit + CI + TOON alignment | LILITH | `20260318_022030_hermes_prompt_lilith_externalai-batch.md` |
| R4 Automation policy (watcher / full-auto) | ATHENA | `20260318_022040_hermes_prompt_athena_externalai-batch.md` |
| R5 HERMES pipeline + classification | HERMES | `20260318_022050_hermes_prompt_hermes_externalai-batch.md` |

---

## Critical rules (restated)

- **HERMES** does not implement code; prompts in **`prompts/`** carry execution.  
- **Lineage:** each prompt lists source artifacts above.  
- **No artifact ignored:** inventory rows map into grouped tasks in those five files.

---

*HERMES — actor_id 15 — routing report complete for External AI directive 235500.*
