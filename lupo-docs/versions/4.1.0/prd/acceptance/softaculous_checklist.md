---
lupopedia.headers:
  lupopedia.schema: "acceptance_checklist"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/acceptance/softaculous_checklist.md"
  last_modified_utc: "20260326"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "acceptance_checklist"
  purpose: "Softaculous primary acceptance checklist for 4.1.0"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/versions/4.1.0/prd/constraints/auto_installer_constraints.md", type: "implements", weight: 1.0 }
    - { to: "lupo-docs/versions/4.1.0/prd/requirements/installer_requirements.md", type: "implements", weight: 1.0 }
lupopedia.footer:
  version: "4.1.0"
  last_verified: "20260326"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  approved_for_release: "4.1.0"
  approval_status: "pending"
  approved_by_actor_id: 0
  approved_utc: 0
  next_action:
    - "Run internal preflight and submit 4.0.x package for Softaculous review"
---

# Softaculous Acceptance Checklist

This checklist is split into two stages:

1. Internal preflight validation (execution controlled by the Lupopedia team).
2. External manual review feedback from Softaculous after package publication.

## Package and App Identity

- [ ] Crafty Syntax 3.7.5 lineage is explicit and accurate.
- [ ] Upgrade path from Crafty Syntax 3.7.5 to Lupopedia 4.0.x is documented and testable.
- [ ] Package metadata and install behavior are consistent with Softaculous expectations.

## Install and Upgrade Behavior

- [ ] Clean install succeeds in subdirectory deployment (`/lupopedia/`).
- [ ] Upgrade flow from Crafty Syntax 3.7.5 to Lupopedia 4.0.x is deterministic.
- [ ] No manual patching is required to complete install or upgrade.

## Runtime Baseline

- [ ] Core web chat baseline functions after install/upgrade.
- [ ] Admin baseline functions after install/upgrade.
- [ ] Session and auth flows remain stable.
- [ ] `lupopedia_js.php` emits embeddable JavaScript under subdirectory installs.
- [ ] Floating bottom navigation bar renders and loads previous/next/related/knowledge/context sections.
- [ ] Page-view and transition telemetry persists to canonical tracking tables.
- [ ] Like/share interactions are captured and reflected in content engagement counters.
- [ ] Behavior-derived navigation and knowledge links are inferential only (no manual host registry dependency).

## Release Decision

- [ ] Evidence captured in changelog.
- [ ] Latest 4.0.x package published and submitted for Softaculous review.
- [ ] External reviewer feedback received and documented.
- [ ] Iteration loop applied on 4.0.x until reviewer-blocking issues are closed.
- [ ] Softaculous acceptance confirmed for Crafty Syntax 3.7.5 to Lupopedia 4.0.x upgrade path.
- [ ] 4.1.0 finalization decision recorded after acceptance confirmation.
- [ ] Required reviewer fixes applied and revalidated.
- [ ] Approval status moved to `approved` only after all checks pass and external feedback is resolved.

## Reviewer Feedback Tracking

- [ ] Create issue/thread entry for each reviewer comment.
- [ ] Tag relevant artifact in `PENDING_ARTIFACTS_INDEX.md`.
- [ ] Update affected requirements and constraints based on feedback.
- [ ] Re-run internal preflight checks after fixes.
- [ ] Document feedback resolution in `changelog.md`.
- [ ] If reviewer flags missing frontend tracking/navigation parity, update `lupopedia_js.php` architecture and requirements artifacts first.
