---
lupopedia.headers:
  lupopedia.schema: documentation
  version_when_written: "4.0.94"
  file_path_from_root: "lupo-docs/versions/4.0.94/PLAN.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/PLAN.md"
  when_updated: "20260403180000"
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

### Phase A — Channel infrastructure (4.0.94)

**Do not migrate** from **`lupo-channels_before_4_0_93/`**. That directory is a **read-only archive**. Wholesale “migrate old → new” is out of scope and contradicts PRD 29 (see **`lupo-docs/prd/29_project_structure.md`** — channel filesystem strategy).

**Instead — establish fresh channels for active work:**

- Create **new** channel threads for active PRD and documentation work (PRD **29**, **30**, **31**, **organization**, documentation system). Use the pattern **`lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/`** with `decisions/`, `questions/`, `answers/`, `comments/` as needed. Ground behavior in **`lupo-docs/prd/02_channels_discussions.md`**.
- **Cherry-pick only** from **channel 42** in the legacy tree for files **newer than 2026-03-25** (per audit). **Archive reference only** for all other pre–4.0.93 channel content—do not replay the whole old structure into the new layout.
- [ ] Align active **`lupo-channels/`** tree and **`lupo-channels/channel_index.md`** with the canonical layout for **new** work (including threads already started, e.g. **`lupo-channels/0/organization/prd_29_project_organization/`**).
- [ ] Update **`.cursorrules`** (and successor rule bundles) so the active path convention and archive folder name are explicit.
- [ ] Update cross-documentation links that still assume **only** numeric legacy channel directories.

**Completion criteria:** No task is framed as “migrate the full legacy tree”; authors use the archive as **historical reference**, create **fresh** threads under the new pattern; optional cherry-picks only **per PRD 29 archive policy** (channel 42, timestamps newer than `20260325`); docs, `channel_index.md`, and rules agree; no broken canonical links in `lupo-docs/` for channel paths.

### Phase B — Edge-based Q&A (product + docs)

- [ ] Implement edge-based Q&A in the web UI where appropriate
- [ ] Add or extend validation so `lupopedia.edges` Q&A link types (`has_answer`, `answers`, etc.) are checked in CI or scripts
- [ ] Conversion path for any monolithic `decisions.md` leftovers (not channel-tree migration)

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
