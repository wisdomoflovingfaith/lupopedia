---
lupopedia.headers:
  lupopedia.schema: documentation
  version_when_written: "4.0.94"
  file_path_from_root: "lupo-docs/versions/4.0.94/TODO.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/TODO.md"
  when_updated: "20260403120000"
  channel_id: 42
  thread_id: "todo-backlog-4.0.94"
  actor_id: 102
  delegation_chain: "cursor:root"
  artifact_type: "todo"
  artifact_kind: "master_backlog"
  purpose: "Master backlog for Lupopedia 4.0.94 (includes merge from 4.0.93/TODO.md cleanup 2026-04-03)"
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
    - to: "lupo-docs/versions/4.0.93/TODO.md"
      type: references
      weight: 0.9
      reason: "Frozen 4.0.93 completed record"
lupopedia.footer:
  last_verified: "20260403120000"
  verified_by:
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.94/TODO.md — delegation: cursor:root

# 4.0.94 TODO

Merged from **`lupo-docs/versions/4.0.93/TODO.md`** on 2026-04-03 (deduplicated). Single active backlog for this version.

## Channels on disk (strategy)

- **Legacy archive:** **`lupo-channels_before_4_0_93/`** — read-only; do **not** bulk-migrate. Historical reference only.
- **New layout:** **`lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/`** for active coordination. Create **fresh** threads for documentation-system PRDs and organization (see **`lupo-docs/prd/29_project_structure.md`** and **`lupo-channels/0/organization/prd_29_project_organization/`**).
- [ ] Finish aligning **`lupo-channels/`** and **`lupo-channels/channel_index.md`** with the canonical layout for new work.
- [ ] Update **`.cursorrules`** (and generated rule bundles) to describe the active channel path convention and the legacy archive folder name.
- [ ] Update documentation links that still assume **only** numeric legacy channel directories.

## Documentation system / edges / decisions

- [ ] Implement **edge-based Q&A** in the web UI where appropriate.
- [ ] Add **`lupopedia.edges` validation** for Q&A link types (e.g. `has_answer`, `answers`) in CI or scripts.
- [ ] Create **migration script** for monolithic legacy `decisions.md` files (optional).
- [ ] Implement **`context_id` consistently** in header documentation and validators.
- [ ] Create or relocate **`lupo-contexts/`** decisions context artifact if still required by doctrine (evaluate vs PRD 31 redesign).
- [ ] Ensure **all legacy `decisions.md` files** follow **`lupo-docs/prd/17_decisions_format.md`** where they remain.
- [ ] Update **`lupo-scripts`** (PHP/Python) to **validate `context_id`** where required.

## PRDs in `lupo-docs/versions/4.0.94/prd/`

- [ ] **`30_prd_development_guide.md`** — rewrite as writing guide; promote to `lupo-docs/prd/` when approved.
- [ ] **`31_context_system.md`** — redesign (no parallel taxonomy; align with PRD 26).
- [ ] Continue **PRD improvement pass** for remaining files under `lupo-docs/prd/` as needed.

## Installer / Softaculous / “Brain” product

- [ ] **install.php:** classes-based instantiation and seeding story for system “Truths” / contexts (aligned with consolidated SQL + importers).
- [ ] **Unified SQL artifact:** optional `lupopedia_v4.0.x.sql`-style bundle naming for distributors (canonical paths remain `install_new_lupopedia.sql` + `install/seed_lupopedia_4_1_0.sql` + Crafty import on upgrade).
- [ ] **uninstall.php / upgrade.php** for DB edges and filesystem atoms (product decision).
- [ ] **Lupo-Monitor:** live visitor dashboard using semantic monitor logic.
- [ ] **Actor/Agent leasing:** operator panel — auth_users lease actors; implement actor leasing doctrines.
- [ ] **Proactive invite** from contextual edges (high-weight Truth pages).
- [ ] **Contextual installation:** seed context registry / semantic edges for “Brain” where product requires.
- [ ] **Subdirectory installation** hardening (not web root) — verify end-to-end beyond PRD text.
- [ ] **Softaculous certification** preparation and checklist execution.

## Real-time chat / Glass UI

- [ ] **Live typing refraction** through State Mirror without persistent DB writes.
- [ ] **Quick responses** / low-weight contexts in `lupo_contexts` (if table still in scope).
- [ ] **Sound and visual alerts** — legacy `/sounds/` hooks into `lupo.js` event bus.
- [ ] **Live typing preview** in High-Density Scroller (60fps target).
- [ ] **Visitor tracking** hooks expected for hoster certification.
- [ ] **Optimize Glass reflection** for mobile viewports.
- [ ] **Optional:** integrate main **`channels-controller`** message panel with **`api/lupo-channels`** + shared chat-display patterns.

## Data migration / DB / filesystem

- [ ] **Clean install** test pass to validate schema + seed.
- [ ] Run **`php lupo-scripts/SyncChannelsToDb.php --commit`** when importing coordination artifacts to DB.
- [ ] Verify **filesystem coordination** replicates correctly to DB where required.
- [ ] **Test** web UI reads from DB as designed.

## Tooling / hygiene / deferred

- [ ] **`enforce_doctrine.py`:** run on all seed files; extend to `.js`, `.php`, SQL (encoding issues resolved).
- [ ] **Hydrator:** Channel 42 elevation output review.
- [ ] **Permanent fix** for Git hook path issue.
- [ ] **Automate TOON updates** from schema changes (`python lupo-scripts/generate_toon_files.py` after DDL).
- [ ] **Regenerate TOON files** after substantive schema edits.
- [ ] **Implement systematic agent version management.**

## Coordination / backlog (cross-cutting)

- [ ] Transition remaining **“Unfinished Business”** items from 4.0.87 into documented contexts.
- [ ] **Enhance channel coordination automation** and thread indexing.
- [ ] **Improve context linking** and multi-agent workflows.

## Product / agents (non-installer)

- [ ] **COUNTERMEASURE** agent refinement.
- [ ] **ASCLEPIUS** health monitor finalization.
- [ ] **Eye** / semantic monitoring widget UI polish.
- [ ] **Actor onboarding** flow (web).
- [ ] **Collection system** (emergent collections).
