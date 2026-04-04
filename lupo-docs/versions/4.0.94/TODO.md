---
lupopedia.headers:
  lupopedia.schema: documentation
  version_when_written: "4.0.94"
  file_path_from_root: "lupo-docs/versions/4.0.94/TODO.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/TODO.md"
  when_updated: "20260404161001"
  channel_id: 42
  thread_id: "todo-backlog-4.0.94"
  actor_id: 102
  delegation_chain: "cursor:root"
  artifact_type: "todo"
  artifact_kind: "master_backlog"
  purpose: "Master backlog for Lupopedia 4.0.94; LILITH-prioritized critical path (install/import → admin UI → Crafty parity §7.4+; Softaculous gate §9–§10 separate track)"
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
  last_verified: "20260404161001"
  verified_by:
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.94/TODO.md — delegation: cursor:root

# 4.0.94 TODO

Merged from **`lupo-docs/versions/4.0.93/TODO.md`** on 2026-04-03 (deduplicated). Single active backlog for this version.

## Completed (documentation — service agents + Softaculous auto-installer narrative — UTC `20260404161001`)

- [x] **Service agent doctrine** — `SERVICE_AGENT_ARCHITECTURE.md` + PRD 00 §5.7–§5.10 + `implementations/service_agents/`; evidence: `decisions/20260404_161001_DECISION_APPROVED_service_agent_architecture_and_softaculous_auto_installer_docs.md`, `CHANGELOG.md` top entry **[2026-04-04] Service agent architecture…**.
- [x] **Softaculous package spec + sample config + packager credential exclude + bootstrap runtime dirs** — thread-verified file list in same **CHANGELOG** entry; does **not** satisfy PRD 33 §10.6–§10.7 (evidence rows still open below).

## Critical path — LILITH prioritization (UTC `20260404075034`)

**Verdict:** Backlog is complete; **do not conflate** daily product work with hoster certification. **Get the code working, then certify.**

| Step | Track | What | PRD 33 (indicative) | Owner |
|------|--------|------|---------------------|--------|
| **1** | Foundation | **Fresh install + Crafty Syntax 3.7.5 import** — reproducible baseline | §**10.1** + §**6** | WOLFIE |
| **2** | Foundation | **Verify operator shell** — **`admin.php`** / **`live.php`** after install | §**10.4** + §**7.3** | WOLFIE |
| **3** | **A — Crafty parity** | **One §7.4 row at a time** (suggested first: **real visitor list**); then §**7.5** GC/rollups, §**7.6** auto-invite + visitor questions | §**7.4**–§**7.6** | WOLFIE / HEPHAESTUS |
| **4** | **B — Softaculous gate** | Evidence, packager acceptance, §**10** bar + §**9** — **after** product loop is credible | §**9**, §**10** (e.g. §**10.6**–§**10.7**) | WOLFIE |

**Track A (Crafty parity + web/admin):** visitor-facing features, operator chat parity, embed contracts — tables **§7.4**–§**7.9** and §**10** rows tied to product behavior.

**Track B (Softaculous / 4.1.0 gate):** vendor evidence, constitutional audit on RC scope, hoster checklist closure — **not** a substitute for **Step 1–3**.

**Deferred (non-blocking):** **34** version ghosts (documentation confusion only); **federation navigation compiler** — **`questions/20260403_222042_…`** until **WOLFIE** product decision; **PLAN** Phase **C** — **PRD 30** / **31** working-copy rewrites (docs hygiene).

Detail and monitoring/chat embed notes: **`WHAT_TO_WORK_ON_NEXT_SESSION.md`**.

## High priority — department model runtime (code must match APPROVED docs)

- [ ] **Visitor/chat POST audit** — all paths resolve **`actor_id`** server-side per **PRD 05** / **PRD 18** and match **`channels-api`** posture (see **`answers/20260403_222043_ANSWER_department_model_visitor_chat_docs_synthesis.md`**). **LILITH:** high priority — runtime must match approved documentation.
- [ ] **`EffectiveActorResolver` / act-as callers** — verify department-first consistency after POST audit (no reintroduction of edge-only act-as for web selector).

## Deferred — federation navigation compiler (product pending)

- [ ] **Federation navigation compiler** — OPEN **`lupo-docs/versions/4.0.94/questions/20260403_222042_QUESTION_federation_navigation_compiler.md`** until **WOLFIE** product decision. **LILITH:** defer behind install / admin / Crafty parity.

## Deferred — version ghosts (manual; documentation hygiene only)

- [ ] Review **34** files with **critical** findings in **`lupo-docs/implementations/29_project_structure/status/version_ghosts_report.json`** (policy: **`answers/20260403_140554_ANSWER_version_ghost_cleanup_manual_review.md`**).
- [ ] Fix phantom legacy paths and ambiguous **3.0.x** prose **per file** (no repo-wide blind rewrite). **LILITH:** low execution priority — confusion only, not blocking install or parity work.

## Completed (department-first documentation batch — UTC `20260403222041`)

- [x] **`ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`** — canonical joins + eligibility (thread-verified in decision **`20260403_222041_…`**).
- [x] **PRD alignment** — **02**, **05**, **07**, **13**, **15**, **18**, **25**, **32** + implementation **13** Q3 answered (same decision body).
- [x] **Version artifacts** — decision **`20260403_222041_…`**, answer **`20260403_222043_…`**, open QUESTION **`20260403_222042_…`**; `CHANGELOG` / `PLAN` Phase **H** / `edges` / `WHAT_TO_WORK_ON_NEXT_SESSION` / `THREAD_INDEX` rows.

## Completed (this documentation pass — UTC `20260403140552`)

- [x] **`audit_doctrine_prd_edges.py`** — **189** doctrine files with PRD lineage edges (**0** missing) at run time.
- [x] **`find_version_ghosts.py`** + **`version_ghosts_report.json`** — critical set enumerated (**34** files).
- [x] **Mobile / workflow docs** — `MOBILE_SEPARATION_DOCTRINE.md`, `WOLFIE_WORKFLOW_DOCTRINE.md`, **PRD 35** draft, **PRD 33** mobile checklist (where edited), **AGENTS.md** / **LESSONS** updates.
- [x] **Version folder sync** — `CHANGELOG` / `PLAN` Phase **G** / `TODO` / `edges` / `README` / decision **20260403_140552** / Q **140553** / A **140554**.

## Next session (handoff — aligned to LILITH critical path)

Order for the next working session (expanded: **`WHAT_TO_WORK_ON_NEXT_SESSION.md`**):

- [ ] **Fresh install + Crafty Syntax 3.7.5 import** — **first**; foundation for everything (**PRD 33** §**10.1** + §**6**).
- [ ] **Verify `admin.php` / `live.php`** — operator shell usable after install (**PRD 33** §**10.4** + §**7.3**).
- [ ] **Crafty parity — pick one §7.4 item** — e.g. **real visitor list** (smallest visible win); then continue §**7.4**, then §**7.5**, §**7.6**.
- [ ] **Visitor web / configure + monitoring embed + admin chat** — **`SEMANTIC_MONITORING_DOCTRINE.md`**, **`CHAT_UI_JAVASCRIPT_SHARED_STATE_DOCTRINE.md`**; optional **`livehelp_js` → `lupopedia_js`** spike (see section below).
- [ ] **Semantic “The Eye”** — after embed contracts honest; **PLAN** Phase D.
- [ ] **Softaculous gate / §9 evidence** — **Track B**; after product loop is credible.
- [ ] **`scaffold_implementation.py`** — align templates with **PRD 31** when touching scaffolds (docs/tooling; not install blocker).

## PLAN Phase C traceability (`PLAN.md`) — deferred behind product loop

**LILITH:** PRD **30** / **31** working-copy rewrites are documentation improvement, not blockers for install, admin, or Crafty parity.

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

### PRD 33 — two execution tracks (do not merge mentally)

| Track | Meaning | Primary TODO tables |
|-------|---------|---------------------|
| **A — Crafty parity + operator product** | Features operators and visitors need; comparison to **Crafty Syntax 3.7.5** | §**7.4**–§**7.9**; §**10** rows **10.1**–**10.5** where they describe product behavior |
| **B — Softaculous / 4.1.0 gate** | Hoster certification, evidence, vendor narrative | §**9**; §**10.6**–§**10.7**; **`implementations/33_softaculous_certification_4_1_0_gate/`** |

### PRD 33 / Softaculous / 4.1.0 gate (traceability per PRD §12)

- [x] **Gate PRD approved** — normative text **`lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md`** (`status: approved`); evidence **`decisions/20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md`**; LILITH final **§13.1** UTC **`20260403222833`** (100/100, ready for code).
- [x] **§12 backlog rows created** — subsections below (§7.4–§7.9 + §10); each line: **PRD 33**, **§** ref, **owner `actor_id`**, **`planned` / `in_progress` / `complete` / `blocked`**, **evidence** (on close: artifact path + BIGINT UTC).

**Default owner:** **WOLFIE** (`actor_id` **1**) — reassign per task in channel decisions if HEPHAESTUS or another actor executes.

#### PRD 33 §10 — 4.1.0 gate conditions (tagging bar)

| Done | PRD § | Task (summary) | Owner `actor_id` | Status | Evidence (path + UTC when complete) |
|------|--------|----------------|------------------|--------|--------------------------------------|
| [ ] | §10.1 + §6 | Root scripts **`livehelp_js.php`**, **`lupopedia_js.php`** contract on clean install + Crafty import path | 1 | planned | |
| [ ] | §10.2 + §7.1 | Unified operator chat — functional parity (multi-session, colors, transport chain) | 1 | planned | |
| [ ] | §10.3 + §7.4–§7.9 | §7.4 all checked or **APPROVED** deferrals; §7.5–§7.9 same | 1 | planned | |
| [ ] | §10.4 + §7.3 | **`admin.php`** demo-ready operator shell (or **APPROVED** deferral) | 1 | planned | |
| [ ] | §10.5 + §5 | PHP **5.6** floor + current **8.x** smoke documented | 1 | planned | |
| [ ] | §10.6 + §9 + §2.1 | Softaculous / hoster evidence artifact + vendor acceptance where claimed | 1 | planned | |
| [ ] | §10.7 | Constitutional compliance audit (recommended: LILITH **actor_id 2**) on RC scope | 2 | planned | |

#### PRD 33 §7.4 — visitor-facing Crafty parity (implementation roadmap)

| Done | PRD § | Task (summary) | Owner `actor_id` | Status | Evidence |
|------|--------|----------------|------------------|--------|----------|
| [ ] | §7.4 | Visits reporting — **`data.php` tab 3** / **`data_visits.php`** parity | 1 | planned | |
| [ ] | §7.4 | Path funnel — **`data.php` tab 4** / paths drill-down | 1 | planned | |
| [ ] | §7.4 | Per-hit page stream — embed → tracker, session page sequences | 1 | planned | |
| [ ] | §7.4 | Referrer + campaign context (PRD **11**) | 1 | planned | |
| [ ] | §7.4 | Real visitor list (“who is on the site now”) | 1 | planned | |
| [ ] | §7.4 | Real client IP — **`get_ipaddress()`** parity | 1 | planned | |
| [ ] | §7.4 | Session / visitor identity fallbacks — **`identity()` / `detectID()`** chain | 1 | planned | |
| [ ] | §7.4 | Real-time transport fallbacks — buffer / XHR / image legs | 1 | planned | |
| [ ] | §7.4 + §7.6 | Proactive chat invites | 1 | planned | |
| [ ] | §7.4 + §3.11 | Typing preview — floating layer + **PREVIEW** visibility modes | 1 | planned | |
| [ ] | §7.4 | Chat transcripts + logging | 1 | planned | |
| [ ] | §7.4 | Canned messages (operator / department scoped) | 1 | planned | |
| [ ] | §7.4 + §7.9 | Multilingual operator + visitor UI | 1 | planned | |
| [ ] | §7.4 + §7.8 | Emoji / **`::img|…::`** icons in chat | 1 | planned | |
| [ ] | §7.4 | Improved visitor chat templates — preserve §6 embed contract | 1 | planned | |
| [ ] | §7.4 | **Mobile client chat** — separate **`/mobile/...`** pages, device detection; **PRD 35** / **MOBILE_SEPARATION_DOCTRINE.md** | 1 | planned | |
| [ ] | §7.4 | Departments — PRD **25** + Crafty routing/templates | 1 | planned | |
| [ ] | §7.4 | Operator / user admin — Crafty-style users + permissions | 1 | planned | |
| [ ] | §7.4 | Leads — import + UI parity or **APPROVED** replacement | 1 | planned | |
| [ ] | §7.4 + §7.6 | Visitor-composed questions + Q/A | 1 | planned | |
| [ ] | §7.4 | Campaign / attribution tracking (+ search-term fix or documented deprecation) | 1 | planned | |

#### PRD 33 §7.5 — GC + rollups

| Done | PRD § | Task | Owner | Status | Evidence |
|------|--------|------|-------|--------|----------|
| [ ] | §7.5 | Hitched probabilistic GC on hot endpoints | 1 | planned | |
| [ ] | §7.5 | Rollup targets — **`lupo_*`** shapes per §3.9 + install SQL | 1 | planned | |
| [ ] | §7.5 | Stale visit_track → aggregates + prune raw rows | 1 | planned | |
| [ ] | §7.5 | Visitor idle session end + **`archive*`** equivalents on **`lupo_*`** | 1 | planned | |
| [ ] | §7.5 | Operator stale presence + history rows | 1 | planned | |
| [ ] | §7.5 | Table caps + recursive graph deletes | 1 | planned | |
| [ ] | §7.5 | Abandoned chat timeout (~90s in **`status='chat'`**) | 1 | planned | |

#### PRD 33 §7.6 — auto-invite + visitor questions

| Done | PRD § | Task | Owner | Status | Evidence |
|------|--------|------|-------|--------|----------|
| [ ] | §7.6 | Auto-invite rules engine (image-class evaluation parity) | 1 | planned | |
| [ ] | §7.6 | Visitor question UX after chat end + persistence mapping | 1 | planned | |

#### PRD 33 §7.7 — modernization checklist

| Done | PRD § | Task | Owner | Status | Evidence |
|------|--------|------|-------|--------|----------|
| [ ] | §7.7 | No framesets — iframe/div shells | 1 | planned | |
| [ ] | §7.7 | PDO_DB + named placeholders on new/port work | 1 | planned | |
| [ ] | §7.7 | **`$UNTRUSTED`** (or documented equivalent) + validation | 1 | planned | |
| [ ] | §7.7 | Dynapi/dynlayer — **`lupo-includes/js/dynapi/`** parity | 1 | planned | |
| [ ] | §7.7 | Fallbacks retained until superseded | 1 | planned | |
| [ ] | §7.7 | §5.1 unknown shared-hosting environment posture | 1 | planned | |
| [ ] | §7.7 | §5.2 admin security / extension warnings | 1 | planned | |
| [ ] | §7.7 | §7.9 localization layer discovery + admin locale list | 1 | planned | |

#### PRD 33 §7.8 — emoji / picker

| Done | PRD § | Task | Owner | Status | Evidence |
|------|--------|------|-------|--------|----------|
| [ ] | §7.8 | Picker allow-list + validated **`::img|…::`** tokens only | 1 | planned | |
| [ ] | §7.8 | Public emoji URLs — no directory listing beyond approved files | 1 | planned | |
| [ ] | §7.8 | Write-time reject invalid tokens (no silent strip) | 1 | planned | |

#### PRD 33 §7.9 — multilingual UI

| Done | PRD § | Task | Owner | Status | Evidence |
|------|--------|------|-------|--------|----------|
| [ ] | §7.9 | Lookup API **key + locale → string** for gated UI | 1 | planned | |
| [ ] | §7.9 | Charset / **Content-Type** / HTML declaration aligned (UTF-8 default) | 1 | planned | |
| [ ] | §7.9 | **APPROVED** decision: filesystem vs DB (or hybrid) + seed strategy | 1 | planned | |
| [ ] | §7.9 | Parity spot-check vs **`craftysyntax-reference/lang/`** | 1 | planned | |

- [ ] **install.php:** classes-based instantiation and seeding story for system “Truths” / contexts (aligned with consolidated SQL + importers).
- [ ] **Unified SQL artifact:** optional `lupopedia_v4.0.x.sql`-style bundle naming for distributors (canonical paths remain `install_new_lupopedia.sql` + `install/seed_lupopedia_4_1_0.sql` + Crafty import on upgrade).
- [ ] **uninstall.php / upgrade.php** for DB edges and filesystem atoms (product decision).
- [ ] **Lupo-Monitor:** live visitor dashboard using semantic monitor logic.
- [ ] **Actor/Agent leasing:** operator panel — auth_users lease actors; implement actor leasing doctrines.
- [ ] **Proactive invite** from contextual edges (high-weight Truth pages).
- [ ] **Contextual installation:** seed context registry / semantic edges for “Brain” where product requires.
- [ ] **Subdirectory installation** hardening (not web root) — verify end-to-end beyond PRD text.
- [ ] **Softaculous certification execution (umbrella — Track B)** — Close via **§9** + **§10** gate rows and evidence artifacts; **§7.4–§7.9** tables are **Track A** (product). **Not** closed by documentation approval alone. Hub: **`lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/`**. **PRD:** [lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md](../../prd/33_softaculous_certification_4_1_0_gate.md).

## Visitor web + monitoring embed + admin chat (after install + admin baseline)

**Order:** Runs **after** critical path **Step 1–2** (install/import + **`admin.php`** / **`live.php`** smoke). Supports **Track A** (Crafty parity + operator UX).

- [ ] **Visitor-facing web:** login, account/session flows, and **configure** surfaces (settings that operators expect post-install) — align with **Two-UI** / **WOLFIE_WORKFLOW_DOCTRINE** (consumer mobile-first where applicable).
- [ ] **Monitoring / silent-harvest embed:** PHP-generated JS placed on **monitored** host pages; reconcile **`lupopedia_js.php`** naming/contract with routes that exist today (e.g. **`nav/semantic-navbar-js`**) per **`SEMANTIC_MONITORING_DOCTRINE.md`** — no invented API paths.
- [ ] **Admin chat interface:** operator/visitor chat chrome and **`channels-controller`** / **`chat-display`** wiring; follow **`CHAT_UI_JAVASCRIPT_SHARED_STATE_DOCTRINE.md`** — **default non-IIFE**; admin code does not need IIFE except rare cases (e.g. legacy audio `play()` isolation).
- [ ] **Optional product simplification:** spike merging needed symbols from **`livehelp_js.php`** into **`lupopedia_js.php`** so one embed owns shared visitor/monitor state (decision + PRD 18/28 update if adopted).

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
- [x] **Cursor session end (UTC `20260404074421`):** WordPress / Softaculous **docs + partial code** — **PRD 00** §15, **`LEARNED_FROM_WORDPRESS.md`**, **`SEMANTIC_MONITORING_DOCTRINE.md`**, **`CHAT_UI_JAVASCRIPT_SHARED_STATE_DOCTRINE.md`**, **PRD 33** §14 / implementation **33** Q&A + tasks, **`InstallWizardHtaccessWriter`** marker merge, **`build_softaculous_package.sh`** excludes; version-folder **`CHANGELOG`** / **`PLAN`** Phase **I** / **`TODO`** / **`edges`** / **`WHAT_TO_WORK_ON_NEXT_SESSION`** / **`comments/20260404_074421_…`** (see **`CHANGELOG.md`** top entry **[2026-04-04]**).
- [ ] Transition remaining **“Unfinished Business”** items from 4.0.87 into documented contexts.
- [ ] **Enhance channel coordination automation** and thread indexing.
- [ ] **Improve context linking** and multi-agent workflows.

## Product / agents (non-installer)

- [ ] **COUNTERMEASURE** agent refinement.
- [ ] **ASCLEPIUS** health monitor finalization.
- [ ] **Eye** / semantic monitoring widget UI polish.
- [ ] **Actor onboarding** flow (web).
- [ ] **Collection system** (emergent collections).
