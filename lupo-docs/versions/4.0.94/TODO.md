---
lupopedia.headers:
  lupopedia.schema: documentation
  version_when_written: "4.0.94"
  file_path_from_root: "lupo-docs/versions/4.0.94/TODO.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/TODO.md"
  when_updated: "20260403140552"
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
  last_verified: "20260403025155"
  verified_by:
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.94/TODO.md — delegation: cursor:root

# 4.0.94 TODO

Merged from **`lupo-docs/versions/4.0.93/TODO.md`** on 2026-04-03 (deduplicated). Single active backlog for this version.

## High priority — version ghosts (manual)

- [ ] Review **34** files with **critical** findings in **`lupo-docs/implementations/29_project_structure/status/version_ghosts_report.json`** (policy: **`answers/20260403_140554_ANSWER_version_ghost_cleanup_manual_review.md`**).
- [ ] Fix phantom legacy paths and ambiguous **3.0.x** prose **per file** (no repo-wide blind rewrite).

## Completed (this documentation pass — UTC `20260403140552`)

- [x] **`audit_doctrine_prd_edges.py`** — **189** doctrine files with PRD lineage edges (**0** missing) at run time.
- [x] **`find_version_ghosts.py`** + **`version_ghosts_report.json`** — critical set enumerated (**34** files).
- [x] **Mobile / workflow docs** — `MOBILE_SEPARATION_DOCTRINE.md`, `WOLFIE_WORKFLOW_DOCTRINE.md`, **PRD 35** draft, **PRD 33** mobile checklist (where edited), **AGENTS.md** / **LESSONS** updates.
- [x] **Version folder sync** — `CHANGELOG` / `PLAN` Phase **G** / `TODO` / `edges` / `README` / decision **20260403_140552** / Q **140553** / A **140554**.

## Next session (handoff)

Prioritized intent for the next working session (detail: **`WHAT_TO_WORK_ON_NEXT_SESSION.md`** — refresh stamp when that file next changes):

- [ ] **Admin web interface** — operator flows after reproducible install.
- [ ] **Fresh install + Crafty Syntax 3.7.5 import** — baseline before Crafty parity and **Eye** work.
- [ ] **Crafty feature parity** — checklist from live comparison (dependency order).
- [ ] **Semantic “The Eye”** — align with **PLAN** Phase D + semantic / Mood RGB doctrine as applicable.
- [ ] **`scaffold_implementation.py`** — align copied templates with **PRD 31** LUPOPEDIA HEADER placeholders (**PRD 31** `next_action`).

## PLAN Phase C traceability (`PLAN.md`)

Open rewrite/promotion work maps to **PLAN** rows **C-1**, **C-2**, **C-3**. **Done** framework rows **C-FW-1..3** are closed with evidence recorded in `PLAN.md` and `CHANGELOG.md` (UTC **20260402210000** per [2026-04-02] channel/docs framework entry — deterministic anchor, not a “day” bucket).

- [x] **C-FW-1..3** — Channel usage + implementation-folder **canonical** PRDs and tooling shipped (evidence: `lupo-docs/prd/30_channel_usage_patterns.md`, `lupo-docs/prd/31_implementation_folder_guidelines.md`, `decisions/20260402_210000_DECISION_channel_docs_framework.md`).
- [ ] **C-1** — Rewrite `lupo-docs/versions/4.0.94/prd/30_prd_development_guide.md`; completion = SHA-256 + approval artifact (see `PLAN.md`).
- [ ] **C-2** — Redesign `lupo-docs/versions/4.0.94/prd/31_context_system.md`; completion = SHA-256 + approval artifact.
- [ ] **C-3** — Promote to `lupo-docs/prd/` or APPROVED decision for alternate canonical path.

## Channels on disk (strategy)

- **Legacy archive:** **`lupo-channels_before_4_0_93/`** — read-only; do **not** bulk-migrate. Historical reference only.
- **New layout:** **`lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/`** for active coordination. Create **fresh** threads for documentation-system PRDs and organization (see **`lupo-docs/prd/29_project_structure.md`** and **`lupo-channels/0/organization/prd_29_project_organization/`**).
- [ ] Finish aligning **`lupo-channels/`** and **`lupo-channels/channel_index.md`** with the canonical layout for new work.
- [x] Update **`.cursorrules`** (and generated rule bundles) to describe the active channel path convention and the legacy archive folder name — see §30 in `.cursorrules`; run **`php lupo-scripts/propagate_agent_rules.php`** when you want IDE rule bundles refreshed.
- [x] Update **canonical** documentation that assumed **only** numeric `lupo-channels/<digits>/` paths (root README, AGENTS, lupo-rules README, PRD 02/17/21, doctrines, implementation indexes). Historical paths in frozen changelogs and `lupo-channels/42/` examples remain valid as **legacy** references.

## Documentation system / edges / decisions

- [x] **Temporal anchor (constitutional)** — `UTC_TEMPORAL_ANCHOR_DOCTRINE.md`, PRD 00 §3.5a, `tick.py`, `echo_anchor_utc.py`, TICK_PY doctrine, README/AGENTS/ONBOARDING; header sync UTC **`20260402225416`** (artifacts `20260402_225223_*` unchanged)
- [x] **Identity doctrine consolidation** — `IDENTITY_LAYERS_DOCTRINE.md` §3 + AGENTS/ONBOARDING summaries; evidence: `decisions/20260402_225223_DECISION_APPROVED_cursor_thread_identity_temporal_docs.md`
- [x] **IDE facet packs + VS Code propagation** — thin `lupo-agents/{kiro,windsurf,warp,cascade,vscode-ide,trae}/`, hubs `lupo-actors/{100,101,104,105,106,107}/`, `--target=vscode` → `.vscode/lupopedia/`; docs: `AGENTS.md`, `lupo-docs/doctrine/AGENT_REGISTRY.md`, `_shared/README.md`; `validate_actor_identity.py` **IDE_FAUCETS**; evidence: `decisions/20260402_234551_DECISION_APPROVED_ide_facet_packs_vscode_propagation.md`
- [ ] **`php lupo-scripts/propagate_agent_rules.php`** — run after root-rule additions so IDE bundles pick up UTC / identity pointers (manual step); use **`--target=vscode`** for VS Code facet tree when root rules change.
- [ ] **Propagation targets** — add **`--target=warp`** and **`--target=trae`** (or document permanent mirror policy); Antigravity IDE target still pending in script.
- [ ] **Install / seed vs file registry** — reconcile `lupo_actors` rows for IDE facets (**106** `vscode-ide`, **107** `trae`, etc.) with `lupo-database/lupopedia/actors/registry.json` in a dedicated schema/seed pass.

- [x] **Channel Usage Framework** — maps to **PLAN C-FW-1**; evidence: `lupo-docs/prd/30_channel_usage_patterns.md`, `lupo-docs/prd/31_implementation_folder_guidelines.md`; anchor UTC **20260402210000**
- [x] **Implementation Tools** — maps to **PLAN C-FW-2**; evidence: `lupo-scripts/scaffold_implementation.py`, `lupo-scripts/validate_framework_compliance.py`
- [x] **Question Lifecycle** — maps to **PLAN C-FW-3** (framework scope); evidence: `IMPLEMENTATION_FRAMEWORK_SUMMARY.md`, `decisions/20260402_210000_DECISION_channel_docs_framework.md`
- [x] **Cross-Linking** — maps to **PLAN C-FW-3**; evidence: framework summary + header patterns in linked docs
- [x] **Channel-Docs Sync** — maps to **PLAN C-FW-3**; evidence: same decision + quick reference
- [x] **Framework Documentation** — maps to **PLAN C-FW-3**; evidence: `lupo-docs/IMPLEMENTATION_FRAMEWORK_SUMMARY.md`, `lupo-docs/CHANNEL_VS_DOCS_QUICK_REFERENCE.md`
- [x] **Actor Authority Framework** — PRD 32 created; evidence: `lupo-docs/prd/32_actor_authority_agent_roles.md`, `lupo-docs/ACTOR_AUTHORITY_QUICK_REFERENCE.md`; anchor UTC **20260402220000**
- [x] **COUNTERMEASURE Red Team** — Defined in PRD 32 with analysis-only authority; evidence: same decision + PRD 32

- [ ] Implement **edge-based Q&A** in the web UI where appropriate.
- [ ] Add **`lupopedia.edges` validation** for Q&A link types (e.g. `has_answer`, `answers`) in CI or scripts.
- [ ] Create **migration script** for monolithic legacy `decisions.md` files (optional).
- [ ] Implement **`context_id` consistently** in header documentation and validators.
- [ ] Create or relocate **`lupo-contexts/`** decisions context artifact if still required by doctrine (evaluate vs PRD 31 redesign).
- [ ] Ensure **all legacy `decisions.md` files** follow **`lupo-docs/prd/17_decisions_format.md`** where they remain.
- [ ] Update **`lupo-scripts`** (PHP/Python) to **validate `context_id`** where required.

## PRDs in `lupo-docs/versions/4.0.94/prd/`

- [ ] **`30_prd_development_guide.md`** — **PLAN C-1**; rewrite as PRD writing guide; then **PLAN C-3** promotion (or APPROVED alternate path).
- [ ] **`31_context_system.md`** — **PLAN C-2**; redesign (no parallel taxonomy; align with PRD 26); then **PLAN C-3** promotion (or APPROVED alternate path).
- [ ] Continue **PRD improvement pass** for remaining files under `lupo-docs/prd/` as needed.

## Installer / Softaculous / “Brain” product

### PRD 33 / Softaculous / 4.1.0 gate (traceability per PRD §12)

- [x] **Gate PRD approved** — see checklist lines in **Installer / Softaculous** above; primary evidence **`decisions/20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md`**.
- [ ] **Checklist rows** — add lines per **§7.x / §10** as work starts; each line: **§** ref, **owner `actor_id`**, **status**, **evidence** path + BIGINT UTC.

- [ ] **install.php:** classes-based instantiation and seeding story for system “Truths” / contexts (aligned with consolidated SQL + importers).
- [ ] **Unified SQL artifact:** optional `lupopedia_v4.0.x.sql`-style bundle naming for distributors (canonical paths remain `install_new_lupopedia.sql` + `install/seed_lupopedia_4_1_0.sql` + Crafty import on upgrade).
- [ ] **uninstall.php / upgrade.php** for DB edges and filesystem atoms (product decision).
- [ ] **Lupo-Monitor:** live visitor dashboard using semantic monitor logic.
- [ ] **Actor/Agent leasing:** operator panel — auth_users lease actors; implement actor leasing doctrines.
- [ ] **Proactive invite** from contextual edges (high-weight Truth pages).
- [ ] **Contextual installation:** seed context registry / semantic edges for “Brain” where product requires.
- [ ] **Subdirectory installation** hardening (not web root) — verify end-to-end beyond PRD text.
- [x] **PRD 33 gate text (documentation)** — **`lupopedia.headers.status: approved`**; **§12** maps to this **`TODO.md`** subsection; evidence: **`lupo-docs/versions/4.0.94/decisions/20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md`**; hub: **`lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/`** — **PRD:** [lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md](../../prd/33_softaculous_certification_4_1_0_gate.md).
- [ ] **Softaculous certification execution** — **§7–§10** / installer / product checklist toward hoster certification (same **PRD** link); **not** closed by documentation approval alone.

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

- [x] **PRD 31 LILITH final audit + 4.0.94 version sync (Cursor thread, UTC `20260403025155`):** **`CHANGELOG.md`** top entry; **`WHAT_TO_WORK_ON_NEXT_SESSION.md`**; **`decisions/20260403_025155_DECISION_APPROVED_prd31_lilith_final_audit_version_sync.md`** + Q/A/C **`025156`–`025158`**; canonical **`lupo-docs/prd/31_implementation_folder_guidelines.md`** LILITH block (**`20260403024822`**).
- [x] **LILITH directive (2026-04-04 UTC):** Refresh **`lupo-docs/versions/4.0.94/`** — `decisions/`, `questions/`, `answers/`, `comments/`, **`THREAD_INDEX`**, **`PLAN.md`**, **`TODO.md`**, **`CHANGELOG.md`**, **`edges.md`**; **`when_updated`** / **`last_verified`** stamps; scope = verified documentation/channel/Mood RGB work (see **`decisions/20260404_200000_DECISION_APPROVED_documentation_coordination_channel_semantic_mood_rgb.md`**).
- [ ] Transition remaining **“Unfinished Business”** items from 4.0.87 into documented contexts.
- [ ] **Enhance channel coordination automation** and thread indexing.
- [ ] **Improve context linking** and multi-agent workflows.

## Product / agents (non-installer)

- [ ] **COUNTERMEASURE** agent refinement.
- [ ] **ASCLEPIUS** health monitor finalization.
- [ ] **Eye** / semantic monitoring widget UI polish.
- [ ] **Actor onboarding** flow (web).
- [ ] **Collection system** (emergent collections).
