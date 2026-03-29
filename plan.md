---
lupopedia.headers:
  lupopedia.schema: documentation
  version_when_written: "4.0.88"
  file_path_from_root: "plan.md"
  web_path: "http://www.lupopedia.com/lupopedia/plan.md"
  last_modified_utc: "20260327"
  channel_id: 42
  thread_id: "4.0.x-root-alignment"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "plan"
  artifact_kind: "high_level_plan"
  purpose: "Root high-level strategic roadmap aligned to version-driven iterative execution."

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.0.88/PLAN.md", type: "references" }
    - { to: "lupo-docs/versions/4.0.88/TODO.md", type: "references" }
    - { to: "lupo-docs/versions/4.1.0/plan.md", type: "references" }
    - { to: "lupo-docs/versions/4.1.0/prd/README.md", type: "references" }
    - { to: "README.md", type: "aligns_with" }
    - { to: "TODO.md", type: "aligns_with" }

lupopedia.footer:
  last_verified: "20260327"
  verified_by:
    identity_type: "actor"
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "cursor"
  orchestrator: "wolfie:root"
  next_action:
    - "Keep the root plan strategic and version-aware"
    - "Point detailed execution to lupo-docs/versions/"
---

# Root Plan

This file is the high-level strategic roadmap for the repository.

Detailed execution planning lives in:

- `lupo-docs/versions/4.0.88/PLAN.md`
- `lupo-docs/versions/4.1.0/plan.md`

## Overall Strategy

Lupopedia is being developed through version-based, documentation-driven execution.

The strategic model is:

1. Define and stabilize systems through iterative 4.0.x releases.
2. Use version-scoped documentation as the planning and execution authority.
3. Use Softaculous review as the primary external feedback loop.
4. Enter 4.1.0 only after an approved 4.0.x baseline exists.

## Iterative Release Flow

1. 4.0.88 -> Softaculous review -> feedback.
2. 4.0.89 -> Softaculous review -> feedback.
3. 4.0.90 -> additional review and refinement.
4. Approved 4.0.x baseline.
5. 4.1.0 final target.

## System Evolution

4.0.x focus:

- stabilization
- structural alignment
- workflow clarification
- reviewer-feedback closure

4.1.0 focus:

- post-approval execution
- production readiness
- release hardening

## Major Workstreams

The main cross-version workstreams are:

1. Channel system refactor.
2. Navigation and tracking system via `lupopedia_js.php`.
3. Approval footer and approval index system.
4. Federation model and deployment constraints.
5. Hybrid architecture across MySQL runtime authority and filesystem coordination surfaces.

## Alignment Rules

Root plan rules:

- Root plan is strategic, not task-by-task.
- Version plans carry the detailed sequencing.
- If priorities differ, version-scoped plans under `lupo-docs/versions/` are the execution authority.

## Current Strategic Focus

The current strategic focus is:

- complete the 4.0.88 iteration cleanly
- prepare follow-on 4.0.x iterations such as 4.0.89 and 4.0.90
- keep 4.1.0 positioned as the milestone after Softaculous approval