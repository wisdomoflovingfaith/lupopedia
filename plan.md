---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "plan.md"
  last_modified_utc: "20260322"
  channel_id: 42
  thread_id: 1047
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "plan"
  artifact_kind: "derived_plan_view"
  purpose: "Root planning surface under controlled synchronization v9."

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.0.85/TASK_REGISTRY.md", type: "derived_from" }
    - { to: "lupo-docs/versions/4.0.85/CONTRADICTIONS.md", type: "references" }
---

# Root Plan

## Version 4.0.86 (Stop-Point Consolidation)
1. **Current State**: Phase 1 Identity Model and Baseline Documentation complete. 169 tables audited. 22+ agents initialized.
2. **Next Actions**: 
   - Move to **Phase 2 (Agent Rollout)**: Enforce full documentation compliance for all agents in `lupo-actors/`.
   - Implement **ROSE/DIALOG** emotional dialogue layer across all core agents.
   - Deploy automated **Header Validation Gate** for both filesystem and database metadata.
3. **Risks**: Unresolved design blockers for the actor resolution API; potential metadata drift during 22-agent rollout.

## Version 4.0.85+
1. Canonical planning and execution detail is version-scoped under `lupo-docs/versions/<version>/`.
2. Root plan is an index surface only and must not duplicate version-folder state.
3. Read plan context in this order: `TODO.md` (root coordination pointer) -> `lupo-docs/versions/<version>/TODO.md` -> `lupo-docs/versions/<version>/TASK_REGISTRY.md` -> `lupo-docs/versions/<version>/CONTRADICTIONS.md`.
4. For 4.0.85+, multi-dimensional change categories are maintained in version folders (database, doctrine, structure, organization, research, contradictions, registry state).

## 4.0.85
1. Maintain TASK_REGISTRY as the only authoritative state surface for the active version folder.
2. Keep THREAD_INDEX files navigation-only.
3. Route LILITH findings through version-scoped CONTRADICTIONS.md and linked tasks.
4. Treat `lupo-docs/versions/4.0.85/` as the authoritative explanation of current install-ready, system-compliant state.
5. 4.0.85 close state: INSTALL READY + SYSTEM COMPLIANT.

## 4.0.86
1. Resume deferred work only after active contradictions are resolved in TASK_REGISTRY.
2. Extend derived views without reintroducing duplicate status ownership.