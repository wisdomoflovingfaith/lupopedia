---
lupopedia.headers:
  lupopedia.schema: "prd_index"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/README.md"
  last_modified_utc: "20260327"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "release_definition"
  purpose: "Authoritative PRD index for Lupopedia 4.1.0"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 1.0 }
lupopedia.footer:
  version: "4.1.0"
  last_verified: "20260326"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  approved_for_release: "4.1.0"
  approval_status: "approved"
  approval_target_version: "4.1.0"
  approval_status_utc: "20260327103238"
  approval_status_by: "Cursor IDE Agent (Lead Orchestration)"
  approval_status_by_actor_id: 102
  approved_by_actor_id: 1
  approved_utc: 20260326223000
  next_action:
    - "Use only approved 4.1.0 artifacts for release decisions"
    - "Keep 4.1.0 blocked on approved 4.0.x baseline"
    - "Keep plan.md, todo.md, and changelog.md synchronized with this PRD"
---

# 4.1.0 PRD Directory

This directory is the authoritative release definition for 4.1.0.

## 🚨 4.1.0 Governance Reset

Lupopedia 4.1.0 is a governance reset.

Artifacts from 4.0.x:

- Are preserved for history.
- Are not part of 4.1.0 by default.
- Only become part of 4.1.0 if listed in `APPROVED_ARTIFACTS_INDEX.md`.

If it is not approved, it is not part of the release.

## 4.0.x Iterative Release Model

This PRD directory must be read with the actual release sequence in mind:

1. 4.0.88 -> Softaculous review -> feedback.
2. 4.0.89 -> Softaculous review -> feedback.
3. 4.0.90 -> Softaculous review -> feedback.
4. Additional 4.0.x iterations continue as needed.
5. 4.1.0 begins only after the 4.0.x baseline is approved.

Therefore:

- 4.0.88 is not final.
- 4.1.0 is not the immediate next version.
- Work can and should evolve across multiple 4.0.x iterations before 4.1.0 opens fully.

## Foundation from 4.0.x

4.1.0 expects the following to be established across 4.0.x iterations:

- workflow model
- architecture boundaries
- federation model
- `lupopedia_js.php` system definition
- approval footer and approval index model

## Preconditions for 4.1.0

The 4.1.0 milestone is valid only when all of the following are true:

- a Softaculous-approved 4.0.x version exists
- the system is stable enough for direct execution work
- the required structure and governance surfaces are validated

## Rule: Release Signal Versus Legacy Noise

Only artifacts with all of the following count toward 4.1.0 release definition:

- `approval_status: "approved"`
- `approval_target_version: "4.1.0"`

Legacy compatibility during normalization:

- Existing 4.1.0 artifacts that still use `approved_for_release: "4.1.0"` remain readable as legacy-approved surfaces.
- Existing carryover artifacts that still use `approved_for_version: "4.1.0"` remain readable as legacy-approved carryover surfaces.
- The preferred and clearer indexing basis is explicit `approval_status` + `approval_target_version`.
- If a listed artifact has only legacy approval fields and no explicit target field yet, that must be tracked as a normalization gap rather than silently ignored.

All other artifacts are non-binding for release decisions.

Authority files:

- `lupo-docs/versions/4.1.0/APPROVED_ARTIFACTS_INDEX.md`
- `lupo-docs/versions/4.1.0/PENDING_ARTIFACTS_INDEX.md`
- `lupo-docs/versions/4.1.0/REJECTED_ARTIFACTS_INDEX.md`

## How to Use This PRD

1. Start here: `README.md` to understand governance reset and acceptance filter.
2. Lock product definition: `product_overview.md` (approved).
3. Lock installer gates: `requirements/installer_requirements.md` (approved).
4. Work pending artifacts: each pending artifact includes an `Acceptance Evidence Needed` section or equivalent checklist evidence requirements.
5. Get approval: when evidence is complete, set `approval_status: approved` and record `approved_by_actor_id` and `approved_utc`.
6. Update indexes: move the artifact into `APPROVED_ARTIFACTS_INDEX.md` and remove it from pending.

## Footer-Driven Index Contract

The three 4.1.0 index files are classification surfaces, not opinion logs.

- `APPROVED_ARTIFACTS_INDEX.md` lists artifacts whose footer status for target version 4.1.0 is approved.
- `PENDING_ARTIFACTS_INDEX.md` lists artifacts whose footer status for target version 4.1.0 is pending.
- `REJECTED_ARTIFACTS_INDEX.md` lists artifacts whose footer status for target version 4.1.0 is rejected.

Important distinction:

- The footer of an index file describes the approval status of the index file itself.
- The rows inside the index describe the classification of other artifacts.

## Approval Authority

| Artifact Type | Approving Actor | Notes |
|---------------|-----------------|-------|
| product_overview | WOLFIE | Orchestrator approval |
| installer_requirements | WOLFIE | Orchestrator approval |
| core_system | ATHENA | Strategy approval |
| database_constraints | THOTH | Records and doctrine alignment approval |
| architecture/* | ATHENA + WOLFIE | Strategy plus orchestration approval |
| acceptance/* | WOLFIE | Orchestrator approval after external feedback closure |

## Contradiction Resolution

If approved artifacts contradict:

1. `product_overview.md` has highest priority for release definition.
2. `requirements/installer_requirements.md` has second priority as release gate authority.
3. Architecture artifacts must align with both priority artifacts.
4. Contradictions must be resolved before approval or promotion.

## 4.1.0 Stability Promise

After 4.1.0 is accepted by auto-installers:

- No breaking schema changes in 4.1.x patch releases.
- No removal of core Crafty Syntax features.
- No introduction of new mandatory configuration.
- Backward compatibility for 4.1.0 installations through the 4.1.x lifecycle.

Major changes move to 4.2.0.

## 4.1.0 Release Gate Reality

4.1.0 is blocked by auto-installer acceptance, not feature invention.

Primary external gates:

- Softaculous approval and upgrade-path confirmation (primary signal)
- Installatron acceptance
- Fantastico acceptance

External gate sequencing for 4.1.0:

1. Target Softaculous first for approval of Crafty Syntax 3.7.5 to Lupopedia 4.0.x upgrade path.
2. Use Softaculous acceptance outcome as primary go/no-go signal.
3. Use Installatron and Fantastico as secondary confirmations.

## 4.1.0 Execution Scope

Once preconditions are met, 4.1.0 execution priority is:

1. `lupopedia_js.php` implementation.
2. Channel refactor completion.
3. Validator system.
4. CLI implementation.
5. Context system.

## Release Definition After Softaculous Acceptance

Softaculous acceptance of the upgrade path from Crafty Syntax 3.7.5 to Lupopedia 4.0.x is the primary release signal.

When Softaculous confirms:

1. The upgrade path works.
2. Clean install works in subdirectory deployment.
3. Core features function.

Then:

- Final 4.1.0 package is prepared.
- Installatron and Fantastico are submitted as secondary confirmations.
- If secondary confirmations raise issues, fixes are queued for 4.1.1 or later 4.1.x patches.
- 4.1.0 ships with Softaculous as the primary supported installer path.

Primary internal gates:

- Installability on shared hosting under `/lupopedia/`
- Stable schema and deterministic IDs
- Crafty Syntax 3.7.5 feature parity for core operational behaviors
- Clean install and upgrade paths without manual intervention

## PRD Contents

- `product_overview.md`
- `requirements/core_system.md`
- `requirements/web_interface.md`
- `requirements/installer_requirements.md`
- `requirements/database_constraints.md`
- `requirements/lupopedia_js_navigation_tracking_requirements.md`
- `constraints/hosting_constraints.md`
- `constraints/auto_installer_constraints.md`
- `constraints/artifact_governance_reset.md`
- `acceptance/softaculous_checklist.md`
- `acceptance/installatron_checklist.md`
- `acceptance/fantastico_checklist.md`
- `architecture/system_architecture.md`
- `architecture/deployment_model.md`
- `architecture/identity_actor_faucet_auth_system.md`
- `architecture/channel_collection_context_model.md`
- `architecture/federation_content_ingestion_model.md`
- `architecture/lupopedia_js_navigation_tracking_architecture.md`

## Softaculous Feedback Closure Surface

The following 4.1.0 PRD artifacts define closure for missing frontend/tracking expectations identified in review:

- `architecture/lupopedia_js_navigation_tracking_architecture.md`
- `requirements/lupopedia_js_navigation_tracking_requirements.md`

These artifacts define `lupopedia_js.php` as an implementation target for 4.1.0 and bind it to canonical schema/doctrine references.


## 4.0.x Artifact Governance Baseline

Observed in `lupo-docs/versions/4.0.*`: high artifact volume with mixed intent and inconsistent release-binding status.

4.1.0 policy:

- Do not delete 4.0.x artifacts.
- Treat them as legacy reference unless explicitly approved for 4.1.0.
- Promote only artifacts that are installability-relevant, non-contradictory, and PRD-aligned.

## Installer Acceptance Filter

For every requirement and task, ask:

Does this increase the probability of Softaculous acceptance and the follow-on acceptance pattern across other auto-installers?

If no, it is out of 4.1.0 release scope.

## 4.1.0 Release Sign-Off

- [ ] All release-binding artifacts are listed in `APPROVED_ARTIFACTS_INDEX.md`.
- [ ] `PENDING_ARTIFACTS_INDEX.md` has no unresolved release-blocking items.
- [ ] Softaculous acceptance is confirmed and documented.
- [ ] Installatron/Fantastico confirmations are recorded, or explicitly waived with rationale.
- [ ] `changelog.md` contains final release notes and acceptance evidence.
- [ ] `plan.md` phases are complete for release scope.
- [ ] `todo.md` has no unresolved release-critical tasks.

## Synchronization Contract

The following files must always agree:

- `lupo-docs/versions/4.1.0/prd/` (requirements and constraints)
- `lupo-docs/versions/4.1.0/plan.md` (phases)
- `lupo-docs/versions/4.1.0/todo.md` (execution tasks)
- `lupo-docs/versions/4.1.0/changelog.md` (completed work)
