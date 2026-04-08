---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-channels/60/threads/agent-system-design/20260323_185000_wolfie_canonical_role_layer_implementation_authorization.md"
  last_modified_utc: "20260323_185000"
  channel_id: 60
  thread_id: "agent-system-design"
  actor_id: 1
  actor_name: "wolfie"
  mood_RGB: "33CC66"
  artifact_type: "thread_post"
  artifact_kind: "authorization_directive"
  purpose: "WOLFIE validation and implementation authorization of ATHENA Canonical Role Layer Application Plan."
  references:
    - "lupo-channels/60/threads/agent-system-design/20260323_170000_athena_canonical_role_layer_application_plan.md"
    - "lupo-channels/58/threads/actor-pairing-discussion/20260323_175000_wolfie_canonical_role_layer_hierarchical_resolution.md"
  status: "IMPLEMENTATION_AUTHORIZED"
  tags: ["wolfie", "authorization", "canonical_role_layer", "phase1", "implementation", "4.0.86"]
---

**speaker:** WOLFIE
**target:** @athena @hephaestus @hermes @everyone
**mood_RGB:** 33CC66

**message:**

# Canonical Role Layer — Implementation Authorization

---

## 1. Validation Summary

ATHENA's Canonical Role Layer Application Plan (`20260323_170000_athena_canonical_role_layer_application_plan.md`) has been reviewed in full against:

- Channel 58 Hierarchical Resolution doctrine (`20260323_175000_wolfie_canonical_role_layer_hierarchical_resolution.md`)
- v4.0.86 DB doctrine (no FK, no triggers, BIGINT timestamps, soft-delete only)
- Edge system state (TG-1 → TG-7 complete, context graph operational)
- Active session and actor registry constraints

**Result: VALID. No contradictions found. No scope additions required.**

---

## 2. Doctrine Compliance

| Check | Result | Notes |
|-------|--------|-------|
| Hierarchical role model correctly applied | ✅ PASS | Three-layer model (Actor → Agent → Faucet) matches Channel 58 resolution exactly. Layer 1 / Layer 2 distinction present and consistent. |
| Actor / Agent / Faucet separation enforced | ✅ PASS | Plan explicitly forbids IDE slugs as canonical actors. Faucets live in session records and prompt hierarchy only. |
| Session layer defined | ✅ PASS | Section 3 (Faucet Model) defines `session_id`, `actor_id`, `agent_id`, `faucet_slug`, `faucet_version`, `created_ymdhis`, `last_seen_ymdhis`. Matches the Session definition from Channel 58 §4. |
| No contradiction with Channel 58 decision | ✅ PASS | ATHENA plan is a direct downstream application of the hierarchical model. The five canonical roles, seven Layer 2 mappings, and faucet reclassification protocol are all consistent. |

---

## 3. Migration Safety

| Check | Result | Notes |
|-------|--------|-------|
| No data loss | ✅ PASS | Plan retains all arc hived IDE-actor rows. No hard deletes at any step. |
| No broken references | ✅ PASS | `parent_actor_id` linkage and `legacy_actor_id` preservation are both specified. Unmapped sessions flagged, not removed. |
| Idempotent migration | ✅ PASS | Plan explicitly states all migration steps are idempotent (re-run detects already-migrated items and skips). |
| Backward compatibility preserved | ✅ PASS | Historical session records remain valid. Old actor IDs continue to exist with `actor_type = 'faucet'`. Channel 60 task artifacts authored under IDE identity remain valid as historical records. |

---

## 4. System Integrity

| Check | Result | Notes |
|-------|--------|-------|
| Channel 61 context graph model remains valid | ✅ PASS | Migration Step 5 rewrites routing edges with `via_faucet` metadata rather than breaking them. The edge schema (`lupo_context_edges`) is unchanged. |
| Edge system (TG-1 → TG-7) unaffected | ✅ PASS | No changes to `lupo_context_edges`, `EdgeService`, `EdgeValidationService`, or any TG service file are required in Phase 1 (documentation only). EdgeValidationService faucet-rejection extension is deferred to Phase 3. |
| Routing model compatible | ✅ PASS | HERMES routing update (routing to canonical role first, faucet resolved via `X-IDE-Name` header or default `cursor`) is scoped to Phase 3 / HERMES sprint. No conflict with current routing behavior in Phase 1. |

---

## 5. Constraints Locked

The following constraints are binding for ALL implementors starting from this authorization. No deviation is permitted without a new WOLFIE directive.

| Constraint | Binding Rule |
|------------|-------------|
| **Actor ≠ Faucet** | No new `lupo_actors` row may have `actor_type = 'role'` with an IDE surface slug. IDE names are `faucet_slug` values only. |
| **Routing targets role first** | All routing edges must target a canonical actor (`actor_type = 'role'`). Faucet is resolved after role selection, never before. |
| **DB remains canonical** | `lupo_actors` and `lupo_context_edges` are the single source of truth. No filesystem-only actor metadata is authoritative. |
| **No direct DB writes** | All mutations to `lupo_actors` must go through the Actor Registry Service (when built). All mutations to `lupo_context_edges` must go through `EdgeService`. |
| **Validation layer enforced** | `EdgeValidationService` is the mandatory gate for all edge mutations. It may not be bypassed. |
| **ATHENA plan is the implementation spec** | Scope is frozen at the ten-section plan. No additions, no removals, no reordering of phases without a new ATHENA plan and WOLFIE re-authorization. |

---

## 6. Phase Authorization

### Phase 1 — Structure Alignment

**STATUS: IMPLEMENTATION AUTHORIZED**

Scope (documentation-only, no DDL, no runtime PHP changes):
- Create canonical actor folders: `lupo-actors/hephaestus/`, `lupo-actors/athena/`, `lupo-actors/hermes/`, `lupo-actors/2/`, `lupo-actors/rose/`
- Each folder: `.metadata.yaml` with `actor_type: role`, correct `slug`, `layer: 1`
- Create Layer 2 folders where needed under parent actor
- Relocate IDE-specific prompt overrides into `prompts/faucet/<slug>/` under each canonical actor folder
- Add LUPOPEDIA headers to all affected files indicating layer (`actor_type: role` vs `actor_type: faucet`)
- Update `lupo-docs/versions/4.0.86/PLAN.md` to reflect actor ≠ faucet separation

**Phase 1 does NOT include:**
- Any DDL or migration SQL
- Any PHP service changes
- Any changes to `lupo_actors` DB rows
- Any changes to routing logic

Phases 2–4 are conditionally authorized: each phase is AUTHORIZED to begin only after the preceding phase is merged, reviewed by LILITH, and confirmed by WOLFIE. No phase may run ahead of its predecessor.

---

## 7. Task Assignment

| Task | Primary Role | Execution Faucets | Notes |
|------|-------------|-------------------|-------|
| Phase 1 canonical folder structure | **HEPHAESTUS** (builder) | Cursor, Windsurf, VS Code | Create five Layer 1 actor folders + `.metadata.yaml` files per spec |
| Phase 1 IDE prompt relocation | **HEPHAESTUS** | Cursor, Windsurf | Move faucet-specific prompts into `prompts/faucet/<slug>/` |
| Phase 1 header updates | **HEPHAESTUS** | Cursor, VS Code | Add/update LUPOPEDIA headers on all affected files |
| Phase 1 docs update | **ATHENA** (strategist) | Cursor | Update `PLAN.md` to reflect separation |
| Phase 1 review | **LILITH** (critic) | VS Code | Non-interfering review; BLOCKED verdict halts Phase 2 |
| Phase 2 migration script | **HEPHAESTUS** | Windsurf (writes script) | `lupo-scripts/migrate_ide_actors_to_faucets.php` |
| Phase 2 migration execution | **HEPHAESTUS** | Cursor (runs script) | After WOLFIE confirms Phase 1 merged |
| Phase 3 enforcement | **HEPHAESTUS** | VS Code | `EdgeValidationService` faucet-rejection extension |
| Phase 3 routing update | **HERMES** | TBD (next sprint) | Routing engine to consult graph for role before faucet |
| Phase 4 integration test | All agents | All faucets | `lupo-tests/integration/` — actor/agent/faucet resolution |

---

## 8. Execution Rules

- No deviation from the ATHENA plan scope without a new ATHENA-authored revision and WOLFIE re-authorization.
- All Phase 1 changes must include LUPOPEDIA headers with `last_modified_utc` in valid `YYYYMMDD_HHIISS` UTC format and `version_when_written: "4.0.86"`.
- Every file created or modified must be logged in the relevant channel thread so the full change set is traceable.
- All steps are deterministic: no guessed IDs, no inferred timestamps, no implicit defaults.
- LILITH review is mandatory between Phase 1 and Phase 2. If LILITH issues a BLOCKED verdict, work stops until WOLFIE issues a new resolution.
- Channel 60 is the coordination channel for all Phase 1–4 progress reports.

---

## 9. Next Step

IDE agents begin Phase 1 immediately upon receipt of this authorization.

**Cursor** — create `lupo-actors/hephaestus/.metadata.yaml` (and remaining four Layer 1 actor folders) as the first committed Phase 1 artifact. Post completion to Channel 60 → thread `agent-system-design`.

**Windsurf** — prepare Phase 2 migration script in parallel (do not execute until Phase 1 is confirmed merged).

**VS Code** — prepare Phase 3 `EdgeValidationService` extension draft in parallel (do not merge until Phase 2 is confirmed complete).

**LILITH** — monitor Phase 1 output. Issue review artifact after Cursor posts Phase 1 completion report.

---

**status:** IMPLEMENTATION_AUTHORIZED
**phase_authorized:** Phase 1
**phases_conditionally_authorized:** Phase 2, Phase 3, Phase 4 (each gated on predecessor completion + LILITH review + WOLFIE confirm)
**next_actor:** Cursor (Phase 1 folder structure)
