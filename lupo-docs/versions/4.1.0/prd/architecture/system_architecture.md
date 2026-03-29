---
lupopedia.headers:
  lupopedia.schema: "architecture"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/architecture/system_architecture.md"
  last_modified_utc: "20260326"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "system_architecture"
  purpose: "Minimal stable architecture for 4.1.0"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/RELEASE_ARTIFACT_APPROVAL_GOVERNANCE_DOCTRINE.md", type: "implements", weight: 1.0 }
    - { to: "lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md", type: "implements", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_auth_users.md", type: "affects", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "affects", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md", type: "affects", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_dialog_threads.md", type: "affects", weight: 0.9 }
lupopedia.footer:
  version: "4.1.0"
  last_verified: "20260326"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  approved_for_release: "4.1.0"
  approval_status: "approved"
  approved_by_actor_id: 1
  approved_utc: 20260326192115
  next_action:
    - "Use this approved architecture boundary for Phase 2 validations"
---

# System Architecture

## 4.1.0 Architecture Principle

Minimal stable surface first. Installability and acceptance are the architecture drivers.

## Included Layers

1. Installer and schema initialization
2. Core auth/session pipeline
3. Web chat baseline
4. Admin baseline
5. Channel/thread UI baseline
6. Minimal actor routing support

## Excluded from 4.1.0 Critical Path

1. Experimental orchestration subsystems
2. Unproven federation expansions
3. Half-finished high-complexity feature branches

## Exclusion Rationale

- Experimental orchestration subsystems are excluded because they add runtime uncertainty and review complexity that do not improve installer acceptance probability.
- Unproven federation expansions are excluded because node expansion behavior is not yet externally validated; 4.1.0 only includes federation behavior needed for deterministic baseline ingestion.
- Half-finished high-complexity branches are excluded because unresolved integration paths increase installation failure risk and support burden on shared hosting targets.

## Phase 1 Evidence Snapshot (20260326)

- Architecture scope was validated against the 4.1.0 release gate model: installability and acceptance first, expansion later.
- Excluded surfaces were retained outside critical path due reviewer-risk and non-essential complexity for installer acceptance.
- Doctrine/table edges were normalized to identity and channel/thread data surfaces to preserve traceability from architecture to storage.
