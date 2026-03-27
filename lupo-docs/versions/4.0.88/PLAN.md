---
lupopedia.headers:
  lupopedia.version: "4.0.88"
  lupopedia.schema: "development_plan"
  file_path_from_root: "lupo-docs/versions/4.0.88/PLAN.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.88/PLAN.md"
  last_modified_utc: "20260327"
  system_version: "4.0.88"
  channel_id: 42
  thread_id: "4.0.88-planning"
  actor_id: 12
  delegation_chain: "12:1"
  artifact_type: "development_plan"
  artifact_kind: "roadmap"
  purpose: "Thread consolidation and carryover plan for 4.0.88 through future 4.0.x iterations"
  mood_rgb: "666666"
  traits: ["athena_strategy", "development_planning", "roadmap"]
  tags: ["4.0.88", "plan", "development", "athena", "strategy"]
  lupo_agent: "cascade"

lupopedia.edges:
  outbound_edges:
    - { to: "README.md", type: "complements", weight: 1.0 }
    - { to: "TASK_REGISTRY.md", type: "implements", weight: 1.0 }
    - { to: "TODO.md", type: "generates", weight: 1.0 }
    - { to: "CHANGELOG.md", type: "synchronizes", weight: 1.0 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "depends_on", weight: 1.0 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md", type: "depends_on", weight: 1.0 }
    - { to: "lupo-channels/42/", type: "executes_through", weight: 1.0 }
    - { to: "LIVEHELP_OPERATOR_DASHBOARD_SESSION_20260326.md", type: "tracks", weight: 1.0, reason: "Live help feature parity implementation" }

lupopedia.footer:
  approved_for_version: "4.1.0"
  approved_for_version_utc: "20260327103238"
  approved_for_version_by: "Cursor IDE Agent (Lead Orchestration)"
  approved_for_version_by_actor_id: 102
  approval_status: "approved"
  approval_target_version: "4.1.0"
  approval_status_utc: "20260327103238"
  approval_status_by: "Cursor IDE Agent (Lead Orchestration)"
  approval_status_by_actor_id: 102
  last_verified: "20260326120000"
  verified_by:
    identity_type: "actor"
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "cursor"
  next_action:
    - "Carry the open items into 4.0.89 and later 4.0.x iterations"
    - "Use this file as the closed 4.0.88 planning baseline"
    - "Do not treat 4.1.0 as the immediate next patch"
---

# file: 4.0.88 PLAN - delegation: cursor:root - web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.88/PLAN.md](http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.88/PLAN.md)

# 4.0.88 Development Plan

## Objective

Close the 4.0.88 documentation cycle for this thread, clearly separate completed planning work from unfinished execution work, and define how this work carries into future 4.0.x Softaculous review iterations.

## Iterative Release Model

Release flow for this workstream:

1. 4.0.88 review package -> Softaculous review -> feedback.
2. 4.0.89 review package -> Softaculous review -> feedback.
3. 4.0.90 review package -> Softaculous review -> feedback.
4. Repeat 4.0.x iterations until the baseline is approved.
5. Enter 4.1.0 only after an approved 4.0.x baseline exists.

Version boundary:

1. 4.0.88 is the current structure, doctrine, and system-definition iteration.
2. Future 4.0.x versions refine, stabilize, and close reviewer gaps.
3. 4.1.0 is a post-approval execution and production-readiness milestone, not the immediate next patch.

## Completed in 4.0.88 During This Thread

The following work is now documented and consolidated at the 4.0.88 level:

1. Channel-first workflow clarified as the governing execution model.
2. Questions system defined as the required intake surface before prompt execution.
3. Prompts system defined as downstream execution material rather than the first coordination surface.
4. CLI workflow model documented at the doctrine/planning level.
5. Channel refactor strategy documented.
6. Phased migration model documented.
7. Governance channel and governance thread defined for channel refactor work.
8. Edge-integrity rules documented for channel moves and redirect-pointer handling.
9. Approval footer model and approval-index model documented.
10. `lupopedia_js.php` system defined as a missing but required system surface.
11. Federation node model documented, including node 0, node 1, and node 2+ roles.
12. Deployment reality documented, including VPS, FTP, manual DB updates, and incomplete node 0 tolerance.

## In Progress or Partial at 4.0.88 Close

The following items are defined but not executed or not yet complete:

1. Channel refactor is planned but not executed.
2. CLI workflow is documented but not implemented.
3. Validators for the new workflow and move-impact reporting are not yet built.
4. Context system remains incomplete and underdefined.
5. Governance channel exists as a defined coordination space but is not yet an active operational control loop.
6. Approval indexing is documented but not automated.
7. Edge-dependency reporting for channel migration remains manual.

## Carryover to Future 4.0.x Iterations

The next 4.0.x iterations should carry this work forward in this order:

1. Refine the channel refactor plan using real channel audit evidence.
2. Validate edge references and migration-batch safety before moving artifacts.
3. Begin bounded partial implementation where planning is already stable.
4. Address Softaculous feedback as it arrives, version by version.
5. Keep updating PRD and version docs to reflect what changed in each 4.0.x review loop.

## Carryover Classification Toward 4.1.0

4.0.88 thread outputs now separate into three classes:

1. Approved carryover: workflow, governance, doctrine, federation, and approval-state clarifications that can inform later versions.
2. Pending carryover: execution trackers, migration batches, validator work, context work, and automation gaps.
3. Rejected carryover: 4.0.88 PRD surfaces that remain outside the 4.1.0 installer-acceptance boundary unless explicitly re-promoted.

## Next 4.0.x Planning Focus

For 4.0.89 and later 4.0.x iterations, priority should be:

1. Softaculous feedback closure.
2. Controlled channel audit and migration batching.
3. Edge-integrity validation and reporting.
4. PRD gap refinement, especially for workflow, federation, and tracking boundaries.
5. Structure testing before larger implementation steps.

## Relationship to 4.1.0

4.1.0 should only begin as an execution milestone when all of the following are true:

1. At least one 4.0.x package is accepted through the Softaculous loop.
2. The structure documented in 4.0.x is stable enough to implement against.
3. The carryover artifacts needed for 4.1.0 are validated and intentionally promoted.

Until then, this file remains the thread-closure planning surface for the current 4.0.88 iteration.
