---
lupopedia.headers:
  lupopedia.schema: documentation
  version_when_written: "4.0.94"
  file_path_from_root: "lupo-docs/versions/4.0.94/PLAN.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/PLAN.md"
  when_updated: "20260402180000"
  channel_id: 42
  actor_id: 102
  actor_name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "plan"
  artifact_kind: "version_plan"
  purpose: "Plan for 4.0.94 after 4.0.93 documentation freeze"
---

# file: lupo-docs/versions/4.0.94/PLAN.md — delegation: cursor:root

# Lupopedia 4.0.94 PLAN

## Dependency order (no time estimates)

### Phase A — Channel and coordination filesystem

- [ ] Migrate `lupo-channels/` to `lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/`
- [ ] Update `.cursorrules` (or successor rule bundles) to describe the new channel paths
- [ ] Archive or rename legacy channel tree when migration is done (`lupo-channels_before_4_0_93` or equivalent)
- [ ] Update cross-documentation links that still assume numeric-only channel directories

**Completion criteria:** New tree in use; index/manifest docs accurate; no broken canonical links in `lupo-docs/` for channel paths.

### Phase B — Edge-based Q&A (product + docs)

- [ ] Implement edge-based Q&A in the web UI where appropriate
- [ ] Add or extend validation so `lupopedia.edges` Q&A link types (`has_answer`, `answers`, etc.) are checked in CI or scripts
- [ ] Migration path for any monolithic `decisions.md` leftovers

**Completion criteria:** UI or API can navigate Q&A via edges; validators document expected edge types.

### Phase C — PRD 30 and PRD 31

- [ ] Rewrite `prd/30_prd_development_guide.md` as the canonical PRD writing guide; move back to `lupo-docs/prd/` when approved
- [ ] Redesign `prd/31_context_system.md` without parallel classification; align with PRD 26 WHERE layer (`edges.md`)

**Completion criteria:** Headers show `status: approved` and files live under `lupo-docs/prd/` again, or an explicit decision records a different canonical path.

### Phase D — Certification, health, UI, onboarding, collections (parallel where independent)

- [ ] Softaculous certification documentation and checklist execution
- [ ] ASCLEPIUS health monitor PRD implementation follow-through
- [ ] Eye / semantic monitoring widget visual polish
- [ ] Actor onboarding web flow
- [ ] Emergent collections behavior
- [ ] COUNTERMEASURE agent refinement

**Completion criteria:** Each line item has an owning artifact (PRD, decision, or implementation doc) and a clear done definition in `TODO.md`.

## References

- Frozen baseline: `lupo-docs/versions/4.0.93/README.md`
- Backlog: `lupo-docs/versions/4.0.94/TODO.md`
