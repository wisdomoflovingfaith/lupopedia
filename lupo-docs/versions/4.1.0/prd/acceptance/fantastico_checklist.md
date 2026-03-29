---
lupopedia.headers:
  lupopedia.schema: "acceptance_checklist"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/acceptance/fantastico_checklist.md"
  last_modified_utc: "20260326"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "acceptance_checklist"
  purpose: "Fantastico acceptance checklist for 4.1.0"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 1.0 }
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
    - "Validate Fantastico path assumptions and installer behavior"
---

# Fantastico Acceptance Checklist

This checklist is split into two stages:

1. Internal preflight validation (execution controlled by the Lupopedia team).
2. External manual review feedback from Fantastico after package publication.

## Packaging and Installability

- [ ] Package can be installed under shared hosting constraints.
- [ ] Subdirectory and URL rewriting behavior is stable.
- [ ] No root-only install behavior.

## Operational Readiness

- [ ] Installer provisions required database objects cleanly.
- [ ] Post-install baseline routes function.
- [ ] No environment-specific hardcoded dependency.

## Compatibility

- [ ] PHP 5.6+ compatibility validated.
- [ ] Crafty Syntax 3.7.5 migration path remains supported.

## Release Decision

- [ ] Evidence captured in changelog.
- [ ] Softaculous review outcome recorded as primary external signal.
- [ ] 4.0.x package published and submitted for Fantastico review.
- [ ] External reviewer feedback received and documented.
- [ ] Required reviewer fixes applied and revalidated.
- [ ] Approval status moved to `approved` only after all checks pass and external feedback is resolved.
