---
lupopedia.headers:
  lupopedia.schema: "architecture"
  file_path_from_root: "lupo-docs/versions/4.1.0/prd/architecture/channel_collection_context_model.md"
  last_modified_utc: "20260326"
  system_version: "4.1.0"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "prd"
  artifact_kind: "context_architecture"
  purpose: "Define how channels, threads, and collections organize context in 4.1.0"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/channels.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/THREAD_DIALOG_SYSTEM.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/doctrine/RELEASE_ARTIFACT_APPROVAL_GOVERNANCE_DOCTRINE.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_dialog_threads.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_dialog_messages.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_collections.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actor_collections.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_contents.md", type: "references", weight: 0.9 }

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
    - "Validate db/filesystem projection consistency for channel artifacts"
---

# Channel and Collection Context Model

## Core Principle

Context is organized in channels and threads, with collections used for structured knowledge grouping and retrieval.

## Channel and Thread Model

- Channel: primary communication and coordination scope.
- Thread: durable work unit within a channel.
- Message artifact: persistent unit associated with a thread and actor attribution.

## Database and Filesystem Relationship

For 4.1.0, most channel and thread state should be canonical in the database, with filesystem artifacts in `lupo-channels/` treated as generated or synchronized projections for IDE workflows.

Required behavior:

1. Database holds canonical identity, membership, role, and message records.
2. Filesystem artifacts are generated where needed for IDE surfaces and human review.
3. Synchronization paths preserve `content_id` and `federation_node_id` linkage.
4. Divergence must be observable and resolvable, never silent.

## Collection Model

Collections organize reusable context and knowledge artifacts.

Requirements:

- Collections must be actor-attributed and channel-aware where applicable.
- Collection linkage should not bypass channel/thread permission controls.
- Collection references must preserve source traceability.

## Acceptance Evidence Needed

To move this artifact to approved:

- Proof that channel artifacts can be projected from DB records without losing identity metadata.
- Proof that projected artifacts can be reconciled back to DB by `content_id`.
- Evidence that collection operations honor channel and actor permission boundaries.
