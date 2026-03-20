---
version_when_written: "4.0.84"
file_path_from_root: "plan.md"
web_path: "http://www.lupopedia.com/plan.md"
last_modified_utc: "20260320"
project_id: 0
project_slug: "lupopedia-core"
channel_id: 42
thread_id: 1043
task_id: "task_plan_root_001"
actor_id: 1
actor_name: "wolfie"
delegation_chain: "wolfie:root"
artifact_type: "roadmap"
artifact_kind: "plan"
purpose: "Root plan — Channel 66 production migration fixes and Thread 1004 semantic validation coordination"
tags: ["wolfie", "plan", "roadmap", "4.0.84", "channel66", "thread1004", "semantic_validation", "production_migration"]
lupopedia.interpretation:
  whoami:
    facet: "cursor"
    runtime_context: "planning"
    channel_id: 42
    thread_id: 1040
    session_mode: "development"
    project_id: 0
    project_slug: "lupopedia-core"
  whoareyou:
    actor_id: 1
    actor_name: "wolfie"
    identity_source: "canonical_registry"
    state: "active"
    authority_level: "canonical_orchestrator"
 
lupopedia.next_action:
    - "Complete LUPOPEDIA_HEADERS doctrine cleanup and single-field versioning model enforcement (4.0.84)"
    - "Remove all lupopedia.version, system_version, last_verified_system_version from documentation"
    - "Update LUPOPEDIA_HEADERS_FORMAT.md with baseline rewrite rules"
    - "Convert VERSIONING_MODEL.md to obsolete stub"
    - "Add LILITH edge case analysis to README.md"
    - "Create WOLFIE script generate_headers_from_db.py for TOON-based header generation"
    - "Resolve Thread 1001 test-evidence inconsistency for full production deployment"
    - "Implement Thread 1004 semantic validation fixes"
    - "Monitor Channel 66 production deployment safety"
    - "Coordinate HEPHAESTUS implementation across channels 66, 88, and 42"
---

# file: Root plan.md — channel system roadmap

**Paired:** [TODO.md](TODO.md) (**Global Task Registry (Option A)**). **Historical version plans:** [lupo-docs/versions/4.0.80/PLAN.md](lupo-docs/versions/4.0.80/PLAN.md) (no separate 4.0.81 PLAN file — this root `plan.md` is live). **Release gate (historical name):** [011500](lupo-channels/42/threads/1001/20260318_011500_wolfie_4.0.80_remaining-work.md).

**Directives:** [Master shutdown consolidation (4.0.83 sync)](lupo-channels/1/threads/1035/20260319_190000_wolfie_master_shutdown_consolidation.md) · [230500](lupo-channels/42/threads/1001/20260317_230500_wisdomoflovingfaith_channel-system-docs-and-routing-directive.md) · [231700](lupo-channels/42/threads/1001/20260317_231700_wisdomoflovingfaith_fix-the-system-now.md) · [MVP stabilization](lupo-channels/42/threads/1001/20260318_012000_wolfie_channel-hermes-mvp-stabilization.md)

Phases are **dependency-ordered** (no time estimates). **Prompt file names** below live under `lupo-channels/42/prompts/`.

---

## Current phase (where the system actually is)

- **Version:** 4.0.84 only for active execution.
- **Done or materially advanced:** HERMES mapping + WOLFIE ratification; HEPHAESTUS copy-not-move for mapped threads + system-limits hooks (see CHANGELOG [4.0.82], [4.0.83]); actor–facet doctrine + `lupo-rules/root/` packs; Option A TODO/plan structure; many prompts closed (041000, 022050, etc.).
- **Thread 1004 mapping revision (ATHENA):** revised Crafty `livehelp_*` → Lupopedia `lupo_` structural mapping after LILITH attack; verified **34** legacy tables and defined SQL-first documentation authority hierarchy (artifact: `lupo-channels/88/threads/1004/20260319_300000_athena_revised_structural_mapping_model_crafty_lupo_after_lilith_attack.md`). Next: WOLFIE narrowing; HEPHAESTUS blocked until remaining P0 semantic validation.
- **Channel 66 Thread 1001:** reposted Channel 66 Phase 1 audit into correct thread-local working artifact (routing correction), then responded to LILITH attack by reframing Channel 66 architecture boundaries and elevating header ingestion to P0.
- **Channel 66 Thread 1005:** ATHENA doctrine compliance confirmation completed - single-field versioning model confirmed doctrinally compliant with non-blocking cleanup remaining. Ready for WOLFIE closure.
- **Channel 66 Thread 1001:** implemented and executed the first P0 bounded-authority header ingestion scaffold (runner + validators + deterministic `lupo_metadata` projection + JSONL logging) and added fixture-based P0 unit test suite (evidence: `38 passed, 0 failed`) in `lupo-channels/66/threads/1001/20260319_390000_hephaestus_implementation_results_p0_bounded_authority_ingestion.md` (including concurrent-edit detection and toon-schema cache invalidation coverage).
- **Channel 66 Thread 1003:** produced decision-ready narrowing (collections vs namespaces) converting ATHENA’s revised model into an operational working position with explicit precedence and implementation-readiness rules.
- **Channel 88 Thread 1004:** created the Crafty `livehelp_*` → Lupopedia `lupo_*` mapping question thread and linked existing mapping/schema documentation sources.
- **Blocking release-quality closure:** `task_prompt_010100` (A12.4 signoff) and `task_prompt_234200` (watcher acceptance) — see `TODO.md`.
- **Parallel / non-blocking until allocated:** `task_deferred_0001`–`0005` (dedupe, UI, DB-primary, auto-heal, external read).

## Completed (Channel 66 Workstream - Threads 1001-1005)

### Thread 1005 - Single-Field Versioning Model
- **Status:** 🔒 CLOSED AND DOCTRINE-LOCKED
- **Key Achievement:** Established canonical versioning model - only `version_when_written` stored in new artifacts
- **Components Verified:** Version resolver, template generator, validator, projection, and test suite all operational
- **Multi-Agent Coordination:** ATHENA doctrine → HEPHAESTUS implementation → LILITH adversarial validation → WOLFIE closure
- **System Guarantee:** No version drift, no duplicated state, immutable artifacts

### Thread 1002 - Bounded Authority Constraints
- **Status:** No activity during this workstream
- **Note:** Thread remained inactive during 1003-1005 work
- **Previous Work:** Addressed bounded authority constraints in earlier workstream

### Thread 1003 - Decision-Ready Narrowing (Collections vs Namespaces)
- **Status:** No activity during this workstream
- **Note:** Thread remained inactive during 1004-1005 work
- **Previous Work:** WOLFIE produced decision-ready narrowing converting ATHENA's revised model into operational working position

### Thread 1004 - Documentation Consistency & Semantic Validation
- **Status:** 🔄 ACTIVE - P0 SEMANTIC ATTACK IN PROGRESS
- **Critical Finding:** `lupo_visits.actor_id` semantic mapping invalid
- **Issue:** `livehelp_visits_daily.livehelp_id` incorrectly mapped to `lupo_visits.actor_id`
- **Impact:** P0 semantic blocker - violates data meaning integrity
- **Next Steps:** Awaiting HEPHAESTUS implementation plan

### Thread 1001 - Channel 66 System Audit Review
- **Status:** COMPLETED — Documentation reconciliation workstream complete
- **Key Actions:** LILITH review identified routing and doctrine compliance issues - RESOLVED
- **Issue:** Routing violations identified in audit artifact placement - RESOLVED
- **Resolution:** Documentation reconciliation workstream complete — verified by LILITH 20260320 — all P0 violations resolved
- **Next Steps:** Resolution pending

### Thread 1005 - System-Wide Documentation Normalization
- **Status:** ✅ NORMALIZATION COMPLETE
- **Key Achievement:** All root-level documentation now reflects single-field versioning doctrine consistently
- **Files Updated:** CHANGELOG.md, TODO.md, PLAN.md, README.md, THREAD_INDEX.md (Channels 66 & 88)
- **System Consistency:** Documentation matches reality across all files, no contradictions detected

## Active Work (Channel 66 & Channel 88)

### Channel 88 Active Tasks
- **Thread 1004 - Semantic Validation**
  - **Status:** 🔄 ACTIVE
  - **Critical Issue:** Same P0 semantic blocker as Channel 66 Thread 1004
  - **Details:** `lupo_visits.actor_id` mapping violates data meaning integrity
  - **Next Steps:** HEPHAESTUS implementation plan pending

### Channel 66 Active Tasks
- **Thread 1001 - Routing Violations** [COMPLETED — 20260320 — verified by LILITH]
  - **Status:** Completed
  - **Issue:** Routing violations in audit artifact placement - RESOLVED
  - **Resolution:** Documentation reconciliation workstream complete — all P0 violations resolved

- **Thread 1004 - Semantic Validation**
  - **Status:** 🔄 ACTIVE - P0 SEMANTIC ATTACK IN PROGRESS
  - **Critical Issue:** P0 semantic blocker identified
  - **Details:** Same semantic mapping issue as Channel 88
  - **Next Steps:** Awaiting HEPHAESTUS implementation plan

## Completed (Thread 1005 - Single-Field Versioning Model)

- **Thread 1005:** Single-field versioning model implementation, adversarial validation, and final doctrine lock
- **Key Achievement:** Established canonical versioning model - only `version_when_written` stored in new artifacts; `lupopedia.version` and `system_version` forbidden
- **Components Verified:** Version resolver, template generator, validator, projection, and test suite all operational
- **Final Status:** 🔒 CLOSED AND DOCTRINE-LOCKED by WOLFIE after LILITH adversarial validation confirmed TRUE
- **System Guarantee:** No version drift, no duplicated state, immutable artifacts

## Completed (System-Wide Documentation Normalization)

- **System Normalization:** Comprehensive documentation alignment after Thread 1005 doctrine lock
- **Key Achievement:** All root-level documentation now reflects single-field versioning doctrine consistently
- **Files Updated:** CHANGELOG.md, TODO.md, PLAN.md, README.md, THREAD_INDEX.md (Channels 66 & 88)
- ✅ LUPOPEDIA HEADERS doctrine cleanup (remove obsolete multi-version model references; enforce `version_when_written`-only; baseline rewrite-on-write rule; identity-line form fixes; optional-blocks canonicalization) completed
- ✅ LUPOPEDIA HEADERS doctrine glue-layer rules completed (serialization key constraints, DB↔YAML export/import mapping layer, routing precedence, metadata identity, placeholder constraints, next_actions/footer precedence, close deprecation, objective edge update criteria) and deterministic `import_content.py` importer implemented
- ✅ Root repo + `lupo-docs/` root header-baseline pass completed: top-level Markdown files aligned to `version_when_written: "4.0.84"` with missing LUPOPEDIA front matter added where required
- ✅ `import_content.py` upgraded for doctrine-safe DB behavior: application-layer SELECT->UPDATE/INSERT (no vendor-specific upsert), strict `last_modified_utc` validation, deterministic expanded re-import updates, and success output only after commit + file write
- **System Consistency:** Documentation matches reality across all files, no contradictions detected
- **Final Status:** ✅ NORMALIZATION COMPLETE - System truth verified as consistent
- **Quality Assurance:** No references to forbidden version fields, single-field doctrine properly documented everywhere

## Active Implementation (Current Thread)

- **Task:** `lupo-scripts/generate_headers_from_db.py` (DB-driven LUPOPEDIA header reconstruction)
- **Status:** 🔄 In progress (not finalized)
- **Completed so far:**
  - TOON-backed schema verification for `lupo_contents` and `lupo_metadata` completed (table and column names validated before generation logic)
  - Doctrine mapping constraints gathered for canonical block order, legacy normalization, and non-default session omission
- **Next dependency step:** implement deterministic DB resolution + block reconstruction + file merge/write path with `--dry-run` parity

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

### 4.0.83 Active Work (Priority 0)

| Phase gap | Active work (targets) |
|-----------|---------------------|
| Channel migration execution | `task_id: task_channel_migration_execution_001` — **WOLFIE Directive 1033** — authorized |
| Actor-facet separation doctrine | `task_id: task_actor_facet_separation_doctrine_001` — **DOCTRINE COMPLETE** — implementation phase |
| Table-doc authorship fix | `task_id: task_prompt_041000` — **041000** (WOLFIE) — complete |
| External AI batch completion | `task_id: task_prompt_022050` — **022050** (HERMES) — complete |
| A12 QA checklist | `task_id: task_prompt_010100` — **010100** (LILITH) — pending |
| Prompt routing automation | `task_id: task_prompt_234200` — **234200** (HEPHAESTUS) — pending |

### Deferred Work (Carried into 4.0.83)

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
- [x] HEPHAESTUS copy-not-move for **24** mapped threads (per HERMES table) + redirects — see CHANGELOG [4.0.82], [4.0.83]
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

- **4.0.84** — sole active line; earlier versions are history only ([CHANGELOG.md](CHANGELOG.md)).

---

*Last updated: 2026-03-20 — 4.0.84 roll-forward aligned*
