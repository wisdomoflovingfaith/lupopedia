---
lupopedia.headers:
  lupopedia.schema: documentation
  version_when_written: "4.0.88"
  file_path_from_root: "TODO.md"
  web_path: "http://www.lupopedia.com/lupopedia/TODO.md"
  last_modified_utc: "20260327"
  channel_id: 42
  thread_id: "4.0.x-root-alignment"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "task_list"
  artifact_kind: "high_level_todo"
  purpose: "Root high-level TODO aligned to version-driven iterative development."

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.0.88/TODO.md", type: "references" }
    - { to: "lupo-docs/versions/4.0.88/PLAN.md", type: "references" }
    - { to: "lupo-docs/versions/4.1.0/todo.md", type: "references" }
    - { to: "lupo-docs/versions/4.1.0/plan.md", type: "references" }
    - { to: "README.md", type: "aligns_with" }
    - { to: "plan.md", type: "aligns_with" }

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
    - "Keep this file high level only"
    - "Route detailed execution into version-scoped TODO files"
---

# Root TODO

This file is a high-level coordination summary only.

Detailed execution lives in:

- `lupo-docs/versions/4.0.88/TODO.md`
- `lupo-docs/versions/4.1.0/todo.md`

## Immediate: 4.0.x Iterations

- [ ] Stabilize the system across iterative 4.0.x releases.
- [ ] Refine channel structure and migration batches.
- [ ] Implement missing feature surfaces required for reviewer closure.
- [ ] Address Softaculous feedback in each review loop.

## Medium-Term

- [ ] Complete the channel refactor.
- [ ] Implement the `lupopedia_js.php` navigation and tracking system.
- [ ] Build the CLI workflow surface.
- [ ] Build validator and reporting tooling.

## Long-Term: 4.1.0 Milestone

- [ ] Reach production readiness after approved 4.0.x baseline.
- [ ] Complete full deployment hardening.
- [ ] Complete federation integration work that belongs in the post-approval milestone.
- [ ] Enter 4.1.0 only after Softaculous approval of a 4.0.x baseline.

## Root TODO Rules

- Keep this file high level.
- Do not duplicate version-scoped execution tasks here.
- Use versioned TODO files for detailed sequencing and evidence-driven closure.