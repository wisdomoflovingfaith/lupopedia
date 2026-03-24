---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "TODO.md"
  last_modified_utc: "20260322"
  channel_id: 42
  thread_id: 1047
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "task_list"
  artifact_kind: "derived_todo_view"
  purpose: "Root derived TODO surface under controlled synchronization v9."

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.0.85/TASK_REGISTRY.md", type: "derived_from" }
    - { to: "lupo-docs/versions/4.0.85/CONTRADICTIONS.md", type: "references" }
---

# Root TODO

- This file is a root coordination pointer only.
- For 4.0.85+, authoritative task/change state is version-scoped under `lupo-docs/versions/<version>/`.

## Version 4.0.86 Remaining Work

Authoritative details for 4.0.86 are maintained under `lupo-docs/versions/4.0.86/`. Key focus areas for this version before scope closure:
1. **Agent System Rollout**: Validate documentation and structures for all 22+ agents in `lupo-agents/` and `lupo-actors/`.
2. **ROSE/DIALOG Integration**: Implement mood-RGB and mood-label fields in DB schema and agent prompts.
3. **Actor Resolution API**: Finalize deterministic resolution logic in `app/Services/`.
4. **Validation Hardening**: Ensure cross-language parity (PHP/Python) for LUPOPEDIA HEADERS validation.

## Version 4.0.85+ Read Path
1. Open `lupo-docs/versions/<version>/TODO.md` for version-scoped backlog intent.
2. Open `lupo-docs/versions/<version>/TASK_REGISTRY.md` for authoritative task/question lifecycle state.
3. Open `lupo-docs/versions/<version>/CONTRADICTIONS.md` for diagnostic contradiction tracking.
4. Review additional category artifacts in the same version folder (database, doctrine, structural, organization, research).

## Authority Notes
- Do not use root TODO as a full change log for 4.0.85+.
- Do not duplicate version-folder detail in root TODO.
- Root TODO should only direct readers to the active version folder surfaces.
- 4.0.85 final state is install ready and system compliant; remaining work is version-scoped and non-blocking unless explicitly stated in TASK_REGISTRY.

## 4.0.85 Close Declaration

- INSTALL READY
- SYSTEM COMPLIANT
- Authoritative details: `lupo-docs/versions/4.0.85/`

## Execution Order
1. Validate through LILITH output.
2. Record violations in version-scoped CONTRADICTIONS.md.
3. Execute and close work through version-scoped TASK_REGISTRY only.