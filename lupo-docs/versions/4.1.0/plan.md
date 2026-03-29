---
lupopedia.headers:
  lupopedia.schema: "release_plan"
  file_path_from_root: "lupo-docs/versions/4.1.0/plan.md"
  last_modified_utc: "20260327220000"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "plan"
  artifact_kind: "release_phase_plan"
  purpose: "Post-approval execution plan for 4.1.0 after the iterative 4.0.x Softaculous loop"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-channels/42/broadcasts/20260327_215700_wolfie_laravel_composer_audit_complete.md", type: "references", weight: 0.95 }
    - { to: "lupo-channels/42/broadcasts/20260327_210500_wolfie_external_libraries_doctrine_clarified.md", type: "references", weight: 0.95 }
lupopedia.footer:
  version: "4.1.0"
  last_verified: "20260327220000"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  approved_for_release: "4.1.0"
  approval_status: "approved"
  approval_target_version: "4.1.0"
  approval_status_utc: "20260327103238"
  approval_status_by: "Cursor IDE Agent (Lead Orchestration)"
  approval_status_by_actor_id: 102
  approved_by_actor_id: 1
  approved_utc: "20260326223400"
  next_action:
    - "Keep 4.1.0 positioned as post-approval milestone"
    - "Use approved 4.0.x outputs as the foundation entry gate"
    - "Do not execute 4.1.0 scope before preconditions are met"
    - "Carry forward PHP 5.6+ compatibility and external libraries doctrine from 4.0.89+"
---

# 4.1.0 Plan

## Objective

Define the post-approval execution plan for 4.1.0. This version is not the immediate successor to 4.0.88; it begins only after the iterative 4.0.x Softaculous review loop produces an approved baseline.

## 4.0.x Foundation for 4.1.0

The foundation established across 4.0.x iterations is expected to include:

1. Workflow model: channel -> questions -> discussion -> prompts -> execution.
2. Architecture boundaries for channels, threads, hybrid storage, edge authority, and migration safety.
3. Federation model, including node 0 transitional incompleteness and node-local-first behavior.
4. JavaScript tracking and navigation system definition for `lupopedia_js.php`.
5. Approval footer and approval index model for version governance.
6. **PHP 5.6+ compatibility doctrine** - Comprehensive compatibility rules enforced.
7. **External libraries doctrine** - Self-contained library usage patterns established.
8. **Auth user to actor mapping** - Session management and actor selection workflow.

Carryover rule:

1. Approved 4.0.x artifacts may inform 4.1.0.
2. Pending 4.0.x artifacts remain non-binding until resolved.
3. Rejected 4.0.x artifacts stay outside 4.1.0 unless explicitly re-promoted.
4. **Doctrine compliance** - All 4.0.89+ doctrines carry forward to 4.1.0.

## Preconditions for Entering 4.1.0

4.1.0 should only move into active execution when all of the following are true:

1. A Softaculous-approved 4.0.x version exists.
2. The system is stable enough to implement without re-litigating core structure.
3. Structure, migration boundaries, and release-critical documentation are validated.
4. **All Laravel/Composer violations remediated** - No framework dependencies remain.
5. **PHP 5.6+ compatibility verified** - All code works on shared hosting.

## Execution Scope for 4.1.0

Priority order after preconditions are met:

1. `lupopedia_js.php` implementation.
2. Channel refactor completion.
3. Validator system.
4. CLI implementation.
5. Context system.
6. **Template system migration** - Convert remaining Blade templates to pure PHP.
7. **Email system integration** - Deploy EmailService with PHPMailer.

## Implementation Readiness

Ready to build:

1. `lupopedia_js.php`.
2. Migration scripts.
3. Validator scripts.
4. **AuthSessionManager** - Complete actor mapping system.
5. **EmailService** - Production email capabilities.

Needs clarification:

1. Context system boundaries.
2. Federation syncing beyond local-node correctness.

Needs validation:

1. Edge integrity.
2. Approval indexing.
3. Schema alignment.
4. **Doctrine compliance** - Verify all rules followed.
5. **Shared hosting compatibility** - Test on actual shared hosting.

## Phase 0: Governance Lock

 - Keep 4.1.0 PRD as the release-definition source once the milestone opens.
 - Require explicit promotion of carryover artifacts.
 - Keep 4.0.x artifacts untouched and non-binding until promoted.
 - **Enforce all 4.0.89+ doctrines** - No exceptions for 4.1.0 development.

## Phase 1: Installability Baseline

- Validate clean install in subdirectory deployment.
- Validate no absolute-path or environment-specific assumptions.
- Validate schema determinism and doctrine constraints.
- Validate database documentation alignment with install SQL.
- Validate migration documentation clarity from Crafty Syntax 3.7.5 through the approved 4.0.x baseline.

## Phase 2: Auto-Installer Readiness

- This phase assumes an approved 4.0.x baseline already exists.
- Revalidate internal Softaculous evidence against the post-approval build.
- Run internal Installatron preflight checklist.
- Run internal Fantastico preflight checklist.
- Capture preflight evidence and remaining blockers.
- Use Softaculous as the primary external approval signal and the others as confirmation signals.

## Phase 3: Parity Closure

- Validate core Crafty Syntax parity surfaces.
- Execute migration-path proofs.
- Close parity test suite for release scope.
- Validate core concept clarity: channels, threads, actors, agents, auth_users, faucets, collections.
- Validate identity model implementation (auth_user to actor to department to role resolution).
- Validate channel/thread/collection context model with DB canonical + filesystem projection behavior.
- Validate federation ingestion precedence and node-scoped content behavior.

## Phase 4: Submission Readiness

- Promote pending artifacts to approved only with evidence.
- Finalize 4.1.0 package after preconditions, execution, and sign-off are complete.
- Freeze release-critical scope.
- Produce final submission package and sign-off.
- Ensure `APPROVED_ARTIFACTS_INDEX.md` fully matches approved footer states.

## Approval-State Normalization Gap

- The explicit indexing model is `approval_status` + `approval_target_version`.
- Some pre-existing 4.1.0 artifacts still rely on legacy `approved_for_release` fields.
- Until those artifacts are normalized, the index files must document where compatibility handling remains in effect.
- Automatic index generation from footer metadata is not yet implemented and remains a follow-up task.
