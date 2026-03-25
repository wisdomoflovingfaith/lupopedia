---
lupopedia.headers:
  file_path_from_root: lupo-docs/versions/4.0.88/TASK_REGISTRY.md
  last_modified_utc: '20260325205227'
  channel_id: 42
  thread_id: 4.0.88-init
  actor_id: 102
  actor_name: cursor
  artifact_type: planning
  artifact_kind: task_registry
  purpose: Task registry for version 4.0.88 carryover and new workstreams.
  when_updated: '20260325205227'
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.88/TASK_REGISTRY.md
  delegation_chain: cursor:root
lupopedia.edges:
  outbound_edges:
  - to: README.md
    type: references
    weight: 1.0
  - to: PLAN.md
    type: references
    weight: 1.0
  - to: TODO.md
    type: synchronizes
    weight: 1.0
  - to: CHANGELOG.md
    type: synchronizes
    weight: 1.0
  - to: lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md
    type: depends_on
    weight: 1.0
  - to: lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md
    type: depends_on
    weight: 1.0
lupopedia.footer:
  last_verified: '20260325205227'
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: Cursor IDE Agent (Lead Orchestration)
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: wolfie:root
---

# file: 4.0.88 TASK REGISTRY - delegation: cursor:root - web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.88/TASK_REGISTRY.md](http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.88/TASK_REGISTRY.md)

# 4.0.88 TASK REGISTRY

| Task ID | Workstream | Status | Owner | Notes |
|---|---|---|---|---|
| V488-001 | Atoms/version propagation carryover | queued | cursor | From 4.0.87 V487-001; canonical marker validation and closeout evidence |
| V488-002 | Channel docs alignment carryover | queued | cursor | From 4.0.87 V487-002; route + membership + API docs final consistency pass |
| V488-003 | Admin LLM interface carryover | queued | cursor | From 4.0.87 V487-005; finalize admin.php chatbot call flow evidence |
| V488-004 | Script metadata full-coverage sweep carryover | queued | cursor | From 4.0.87 V487-029; extend script metadata to remaining legacy lupo-scripts files |
| V488-005 | Track 3a migration monitoring carryover | queued | hephaestus | From 4.0.87 V487-045; monitor dialog_channels JSON migration pathway after dataset changes |
| V488-006 | Header/footer doctrine modernization | complete | cursor | Completed in thread: migrated core docs to `verified_by`/`verified_via`, removed vague verifier semantics in active doctrine files |
| V488-007 | Web path subdirectory normalization | complete | cursor | Completed in thread: normalized doctrine/root docs web_path to `/lupopedia/` pattern and clickable `# file` links |
| V488-008 | THOTH authority propagation | complete | cursor | Completed in thread: added THOTH semantic truth-check authority and source matrix to LUPOPEDIA_HEADERS doctrine and validator guide |

## Initialization Notes
- This registry is initialized from 4.0.87 release closeout migration.
- Source migration record: `lupo-docs/versions/4.0.87/TASK_REGISTRY.md` task V487-088.
