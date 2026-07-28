---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_MIGRATION_PLAN.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd_proposals/16_C_HEADER_FORMAT_4_2_0_MIGRATION_PLAN.md
  status: active
  when_updated: "20260728165913"
  trust_tier: development
  questions_toon: null
  memory_toon: memory/development/canonical/1026/07/16-c-header-format-4-2-0-migration-plan.toon
  atoms_toon: null
  transcript_jsonl: 0/development/16-c-header-format-4-2-0-migration-plan
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: documentation
  prd_cluster: 16_C
  title: "Migration plan -- header 4.1.9 to 4.2.0 Option A"
  summary: "Dependency-ordered header migration plan under product 4.2.0 UNSTABLE. No corpus rewrite until Captain authorizes Phase 3+. Mandatory breakage inventory included."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 102
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: headers
  faucet_actor_id: 102
---
# Migration Plan -- Header 4.1.9 -> 4.2.0 (Option A)

**Planning doctrine:** dependency order only (no calendar estimates).  
**Captain constraint:** actual header rewriting is NOT authorized until PRD 16_C merge is complete.  
**Product version:** `GLOBAL_CURRENT_LUPOPEDIA_VERSION` = **4.2.0** (TRANSITIONAL / UNSTABLE)  
**This file plans; it does not execute corpus rewrite.**

```text
######################################################################
# SYSTEM STATUS: UNSTABLE -- MULTIPLE KNOWN BREAKAGES
# Product GLOBAL_CURRENT_LUPOPEDIA_VERSION = 4.2.0 is TRANSITIONAL.
# Canonical narrative: docs/versions/4.2.0/SYSTEM_STATUS_UNSTABLE.md
######################################################################
# 1. Authentication / Permission Errors
#    - ACL propagation failures
#    - PermissionDenied on deleted channels
#    - PermissionDenied on theoretical channels
#    - installer token misalignment
#    - federation node lockouts
# 2. Missing Crafty Syntax Features
#    - legacy Crafty Syntax 3.x features not yet ported
#    - missing interpreter behaviors
#    - missing routing primitives
#    - missing semantic switches
#    - missing memory cluster bridges
# 3. Missing Filesystem Structures
#    - active lupo-channels/ tree missing
#    - active channel_index.md missing
#    - thread manifests missing
#    - dimensional memory map missing
#    - Actors Collection chapters missing
#    - CL-302 missing
#    - hermes_toon sidecars missing
# 4. Traffic Defense Division
#    - PRD 25_B proposal exists
#    - department not seeded
#    - ACL not initialized
#    - division not stable
# 5. 98_C Ops Logs
#    - spine exists
#    - full volume catalog missing
#    - JSON threads incomplete
######################################################################
# Migration of headers MUST NOT be mistaken for fixing the breakages above.
# ACL, Crafty parity, channel trees, Traffic Defense, and 98_C catalog
# remain open workstreams independent of dense-header append.
######################################################################
```

---

## Phase 0 -- Doctrine merge (CURRENT AUTHORIZED WORK)

**Depends on:** Captain ALII Option A approval (done)

**Actions:**

1. Land FINAL spec: `16_C_HEADER_FORMAT_4_2_0_FINAL.md`
2. Land merge text: `16_C_HEADER_FORMAT_4_2_0_MERGE_TEXT.md`
3. Apply merge blocks into `docs/prd/16_C-i_LUPOPEDIA_HEADERS.md`
4. Land validator notes + this migration plan
5. Mark proposal as SUPERSEDED

**Completion criteria:**

- PRD 16_C states 4.2.0 is current for new work
- 4.1.9 documented as legacy-accept
- Hawaiian densification explicitly forbidden
- No bulk file header rewrites yet

---

## Phase 1 -- Tooling dual-accept

**Depends on:** Phase 0 complete

**Actions:**

1. Update `scripts/lib/header_spec_v3_1.py` per validator notes
2. Update universal validator CLI help / error strings
3. Sync header field atoms (count 28 + order)
4. Add unit/fixture tests for 4.1.9 and 4.2.0

**Completion criteria:**

- CI accepts existing 4.1.9 corpus
- CI accepts sample 4.2.0 fixtures
- Hawaiian-in-dense fixtures fail

---

## Phase 2 -- Emitter defaults

**Depends on:** Phase 1 complete

**Actions:**

1. Header adder defaults to 4.2.0 + identity CLI flags
2. Document operator defaults for Captain (`10000`) and Cursor (`102`)
3. Update templates under doctrine header templates

**Completion criteria:**

- New files created by tooling emit 4.2.0
- Operators can set identity without guessing

---

## Phase 3 -- Pilot rewrite (narrow)

**Depends on:** Phase 2 complete + explicit Captain go for rewrite

**Scope (suggested):**

1. `docs/prd/16_C-i_LUPOPEDIA_HEADERS.md` (if not already 4.2.0)
2. Header proposal/final companions already on 4.2.0
3. One Captain Log example + one PRD 98_C example

**Completion criteria:**

- Pilot set validates clean under 4.2.0
- No silent identity invention (null/empty where unknown)

---

## Phase 4 -- Batched corpus migration

**Depends on:** Phase 3 complete + Captain authorization for batch

**Rules:**

1. Batch by tree (`docs/prd/`, `docs/doctrine/`, `AGENTS.md`, etc.)
2. Preserve fields 1-22 values; append 23-28
3. Identity population policy:
   - Prefer explicit authorship from git/transcript when known
   - Else `actor_id` from stewardship map; `faucet_actor_id` null if unknown
   - Never invent `department_id`
4. Skip generated/vendor/binary paths (PRD 16 applicability)
5. Do not change body text except as required for envelope close

**Completion criteria:**

- Target batch passes 4.2.0 validation
- Residual 4.1.9 inventory reported (not hidden)

---

## Phase 5 -- Cutover tightening (optional)

**Depends on:** Phase 4 substantially complete

**Actions:**

1. Enable `--require-current` in CI for in-scope paths
2. Keep dual-accept for archive trees if needed
3. Update AGENTS.md / CLAUDE.md discovery line counts (25 -> 31 for 4.2.0)

**Completion criteria:**

- New in-scope files cannot land as 4.1.9 without explicit exception

---

## Explicit non-actions (all phases)

- No Lupopedia-to-Lupopedia DB migration
- No ALTER TABLE for headers
- No product version 4.1.0 / major bump from this work
- No Hawaiian keys into dense headers
- No Dimensional Memory PRD allocation via this plan

---

## Rollback

If 4.2.0 emitters cause systemic failure:

1. Keep PRD text (doctrine history)
2. Revert `EXPECTED` tooling to dual-accept with default emit 4.1.9
3. Do not delete 4.2.0 fixtures; mark development

Identity fields already written remain valid forward; downgrade is emit-default only.
