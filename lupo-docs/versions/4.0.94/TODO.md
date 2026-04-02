---
lupopedia.headers:
  lupopedia.schema: documentation
  version_when_written: "4.0.94"
  file_path_from_root: "lupo-docs/versions/4.0.94/TODO.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/TODO.md"
  when_updated: "20260402180000"
  channel_id: 42
  thread_id: "todo-backlog-4.0.94"
  actor_id: 102
  delegation_chain: "cursor:root"
  artifact_type: "todo"
  artifact_kind: "master_backlog"
  purpose: "Master backlog for Lupopedia 4.0.94"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.94/PLAN.md"
      type: references
      weight: 1.0
      reason: "Plan for this version"
    - to: "lupo-docs/versions/4.0.94/CHANGELOG.md"
      type: references
      weight: 1.0
      reason: "Version changelog"
lupopedia.footer:
  last_verified: "20260402180000"
  verified_by:
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.94/TODO.md — delegation: cursor:root

# 4.0.94 TODO (from 4.0.93 freeze handoff)

## Carried from frozen 4.0.93

- [ ] Migrate `lupo-channels/` to `lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/`
- [ ] Update `.cursorrules` with new channel structure
- [ ] Implement edge-based Q&A in web UI
- [ ] Add `lupopedia.edges` validation for Q&A links
- [ ] Create migration script for old `decisions.md` files
- [ ] Archive old `lupo-channels` tree when safe
- [ ] Update documentation to reference new channel paths

## PRDs in this folder (`prd/`)

- [ ] **`30_prd_development_guide.md`** — rewrite as writing guide; promote to `lupo-docs/prd/` when approved
- [ ] **`31_context_system.md`** — redesign (reject parallel taxonomy; align with PRD 26)

## Product / agents / certification

- [ ] Softaculous certification preparation
- [ ] COUNTERMEASURE agent refinement
- [ ] ASCLEPIUS health monitor finalization
- [ ] Eye widget UI polish
- [ ] Actor onboarding flow (web)
- [ ] Collection system (emergent collections)

## Tooling / hygiene

- [ ] `enforce_doctrine.py` — extend coverage for seeds/assets (encoding issues resolved)
- [ ] Permanent fix for Git hook path issue
- [ ] Regenerate TOON files after schema work: `python lupo-scripts/generate_toon_files.py`

## Context / headers (if still open)

- [ ] Implement `context_id` consistently in header documentation and validators
- [ ] Update `lupo-scripts` (php/py) to validate `context_id` where required
