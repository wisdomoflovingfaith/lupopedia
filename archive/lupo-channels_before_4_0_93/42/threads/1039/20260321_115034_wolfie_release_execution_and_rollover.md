---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "release_execution"
  file_path_from_root: "lupo-channels/42/threads/1039/20260321_115034_wolfie_release_execution_and_rollover.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1039/release_execution"
  questions_toon: null
  channel_id: 42
  thread_id: 1039
  task_id: "task_release_execution_rollover_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "release_execution"
  artifact_kind: "decision_record"
  purpose: "Canonical audited decision record for the 4.0.84 release freeze and 4.0.85 rollover."
  traits: ["release", "rollover", "audit", "4.0.85"]
  tags: ["thread1039", "release", "rollover", "4.0.84", "4.0.85"]

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "updates", weight: 1.0, reason: "Root changelog reflects release and rollover truth." }
    - { to: "TODO.md", type: "updates", weight: 1.0, reason: "Root todo moved to 4.0.85 active queue." }
    - { to: "plan.md", type: "updates", weight: 1.0, reason: "Root plan moved to 4.0.85 execution order." }
    - { to: "lupo-docs/versions/4.0.84/CHANGELOG.md", type: "freezes", weight: 0.95, reason: "Version 4.0.84 release scope frozen." }
    - { to: "lupo-docs/versions/4.0.85/PLAN.md", type: "opens", weight: 0.95, reason: "Version 4.0.85 active work surface created." }
    - { to: "lupo-channels/42/threads/1038/20260321_011500_hephaestus_post_patch_reality_report.md", type: "references", weight: 0.95, reason: "Thread 1038 repository state assessed before defer decision." }

lupopedia.footer:
  latest_review: "20260321"
  reviewed_by: "wolfie"
  release_scope: "4.0.84 frozen; 4.0.85 active"
  next_action:
    - "Resolve Thread 1037 doctrine decision."
    - "Approve or reject Thread 1036 implementation start."
    - "Collect Thread 1038 validation evidence and LILITH audit artifact."
---

# Thread 1039 - Release Execution and 4.0.85 Rollover

## Verified Audit Facts

1. Branch state was verified on `main` during takeover audit.
2. Thread 1038 repository files exist, but release evidence is incomplete.
3. `lupo-database/lupopedia/mysql/migrations/004_human_requests.sql` was not a valid committed release anchor during audit and must not be treated as shipped 4.0.84 scope.
4. Version sources were inconsistent before rollover: `lupo-config/global_atoms.yaml` and `LUPEDIA_VERSION` showed `4.0.84`, while `lupo-config/config/global_atoms.yaml` still showed `4.0.83`.

## Release Decision

Release decision for Thread 1038: **Option B - defer from 4.0.84**.

### Why Option B

1. The repository contains implementation material but not the validation evidence required to truthfully mark it released.
2. No install-path and upgrade-path execution bundle was attached.
3. No final LILITH pass/fail closure artifact exists for the post-patch state.
4. 4.0.84 release truth is stronger if it freezes only completed and auditable scope.

## Frozen 4.0.84 Scope

- Thread 1030
- Thread 1031/1032
- Thread 1034
- Thread 1035

Excluded from 4.0.84 release scope:

- Thread 1036 - design only
- Thread 1037 - pending doctrine decision
- Thread 1038 - deferred to 4.0.85
- Thread 1004 - blocker still open

## 4.0.85 Opening Scope

1. Thread 1037 doctrine adjudication.
2. Thread 1036 implementation approval.
3. Thread 1038 validation, audit, and possible closure.
4. Thread 1004 semantic blocker closure.
5. A12.4 constitutional signoff.
6. Watcher auto-draft policy acceptance.

## Version Rollover Actions

The working version was rolled to `4.0.85` in:

1. `LUPEDIA_VERSION`
2. `lupo-config/global_atoms.yaml`
3. `lupo-config/config/global_atoms.yaml`

This keeps runtime and duplicate atom surfaces synchronized for the active cycle.

## Git Boundary

This thread records release truth and repo state correction. It does **not** treat the current dirty working tree as a safe all-files release commit by itself. Commit/tag/push decisions must account for unrelated workspace changes present during takeover audit.