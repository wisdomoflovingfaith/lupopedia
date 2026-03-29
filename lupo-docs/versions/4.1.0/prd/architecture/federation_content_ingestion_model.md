---
lupopedia.headers:
  lupopedia.schema: "architecture"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/architecture/federation_content_ingestion_model.md"
  last_modified_utc: "20260326"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "ingestion_architecture"
  purpose: "Define content ingestion and federation_node_id behavior in 4.1.0"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 1.0 }
    - { to: "livehelp_js.php", type: "references", weight: 1.0 }
    - { to: "image.php", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/RELEASE_ARTIFACT_APPROVAL_GOVERNANCE_DOCTRINE.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/doctrine/migrations/", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_federation_nodes.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_contents.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_channel_content.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_legacy_content_mapping.md", type: "references", weight: 0.9 }

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
    - "Confirm federation node defaults and ingestion precedence in runtime tests"
---

# Federation and Content Ingestion Model

## Goal

Define where content context comes from and how `federation_node_id` and `content_id` are used to keep ingestion deterministic.

## Federation Node Semantics

- Node 0: canonical Lupopedia core context (`lupopedia.com/lupopedia/`) and project root knowledge.
- Node 1: local installed instance context (default installation node).
- Additional nodes: other domains/instances with their own scoped ingestion context.

## Deployment Reality (Transitional)

Current federation reality for this release cycle:

1. Node 0 is not fully deployed/live yet.
2. Node 0 has included VPS deployment with FTP/manual update workflows.
3. Partial data and broken references to node 0 are expected in transition.
4. This state is expected pre-release transition and not inherently a bug.

## Operational Implications

1. Node 1 must remain fully functional without node 0 completeness.
2. Ingestion must prefer local observed behavior and local DB truth.
3. Cross-node federation sync can be deferred without blocking local operation.
4. All cross-node assumptions must be explicitly guarded.

## Content Source Precedence

For 4.1.0, ingestion source precedence is:

1. Migration import data for historical Crafty Syntax records (structured legacy baseline).
2. Runtime telemetry and page signals from generated `livehelp_js.php` flows (current behavior discovery).
3. Explicit operator/admin registration workflows where required.

## Clarification for Unknown Content Above `/lupopedia/`

Unknown content above installation subdirectory is not assumed from doctrine. It must be derived from one of:

- Crafty Syntax import baseline when available.
- Runtime telemetry signals captured through the monitoring path.

No blind inference should create canonical content records.

## Required Linking Rules

1. All ingested records must carry `federation_node_id`.
2. Content and channel artifacts must be linkable via deterministic `content_id`.
3. Filesystem projections must preserve DB identity mapping.
4. Cross-node data must remain explicitly scoped.

## Softaculous Gate Alignment

1. 4.0.x approval path remains prerequisite signal before final 4.1.0 release posture.
2. Federation completeness is staged and must not be assumed complete before approval loops close.

## Acceptance Evidence Needed

To move this artifact to approved:

- Demonstrated ingestion path from migration import.
- Demonstrated ingestion path from `livehelp_js.php` runtime events.
- Verified node scoping correctness for node 0 and node 1 baseline cases.
- Verified round-trip traceability between database record and filesystem artifact.
