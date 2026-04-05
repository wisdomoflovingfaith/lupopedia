---
lupopedia.headers:
  lupopedia.schema: documentation
  version_when_written: "4.0.94"
  file_path_from_root: "lupo-docs/versions/4.0.94/PLAN.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/PLAN.md"
  when_updated: "20260405104405"
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

## Canonical authority for PRD 30 / PRD 31

Same table as [`README.md`](README.md) — **CANONICAL** under `lupo-docs/prd/` (`30_channel_usage_patterns.md`, `31_implementation_folder_guidelines.md`); **WORKING COPY** under `lupo-docs/versions/4.0.94/prd/`. Edits to normative text go through working copy → explicit approval → promotion.

## Dependency order (no time estimates)

### Phase A — Channel infrastructure (4.0.94)

**Do not migrate** from **`lupo-channels_before_4_0_93/`**. That directory is a **read-only archive**. Wholesale “migrate old → new” is out of scope and contradicts PRD 29 (see **`lupo-docs/prd/29_project_structure.md`** — channel filesystem strategy).

**Instead — establish fresh channels for active work:**

- Create **new** channel threads for active PRD and documentation work (PRD **29**, **30**, **31**, **organization**, documentation system). Use the pattern **`lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/`** with `decisions/`, `questions/`, `answers/`, `comments/` as needed. Ground behavior in **`lupo-docs/prd/02_channels_discussions.md`**.
- **Cherry-pick only** from **channel 42** in the legacy tree for files **newer than 2026-03-25** (per audit). **Archive reference only** for all other pre–4.0.93 channel content—do not replay the whole old structure into the new layout.
- [x] **`semantic` / `mood_rgb_system`:** thread scaffold, APPROVED decisions (evidence + color definitions), **`MOOD_RGB_DOCTRINE.md`** summary + edges; validator-safe header **`thread_id: mood-rgb-system`** vs folder `mood_rgb_system`.
- [x] **Archive path fix:** documentation and implementation indexes that pointed at live **`lupo-channels/42/...`** now target **`lupo-channels_before_4_0_93/42/...`** where exemplars live only in the archive.
- [ ] Align active **`lupo-channels/`** tree and **`lupo-channels/channel_index.md`** with the canonical layout for **all** new work (including threads already started, e.g. **`lupo-channels/0/organization/prd_29_project_organization/`** — partial progress recorded in **`CHANGELOG.md`**).
- [x] Update **`.cursorrules`** (and successor rule bundles) so the active path convention and archive folder name are explicit — **§30 Channel filesystem paths** in `.cursorrules`; align **`lupo-rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md`** §1.1, **`lupo-rules/root/README.md`**, root **`README.md`**, **`AGENTS.md`**, PRD **02** / **17** / **21**, **`DOCUMENTATION_ARCHITECTURE.md`**, **`LUPOPEDIA_HEADERS_FORMAT.md`**, implementation thread indexes.
- [ ] Update **remaining** cross-documentation links (e.g. historical edges in doctrine, frozen version changelogs) **as needed** when those files are next edited; canonical paths above are now explicit.

**Completion criteria:** No task is framed as “migrate the full legacy tree”; authors use the archive as **historical reference**, create **fresh** threads under the new pattern; optional cherry-picks only **per PRD 29 archive policy** (channel 42, timestamps newer than `20260325`); docs, `channel_index.md`, and rules agree; no broken canonical links in `lupo-docs/` for channel paths.

### Phase B — Edge-based Q&A (product + docs)

- [ ] Implement edge-based Q&A in the web UI where appropriate
- [ ] Add or extend validation so `lupopedia.edges` Q&A link types (`has_answer`, `answers`, etc.) are checked in CI or scripts
- [ ] Conversion path for any monolithic `decisions.md` leftovers (not channel-tree migration)

**Completion criteria:** UI or API can navigate Q&A via edges; validators document expected edge types.

### Phase C — PRD 30 and PRD 31

**Shipped (framework — deterministic evidence):**

| PLAN id | Done | Completion evidence | Evidence timestamp (UTC) |
|---------|------|----------------------|---------------------------|
| C-FW-1 | [x] | Canonical files exist: `lupo-docs/prd/30_channel_usage_patterns.md`, `lupo-docs/prd/31_implementation_folder_guidelines.md` | 20260402210000 (see `CHANGELOG.md` [2026-04-02] framework entry) |
| C-FW-2 | [x] | Scripts: `lupo-scripts/scaffold_implementation.py`, `lupo-scripts/validate_framework_compliance.py`; docs: `lupo-docs/CHANNEL_VS_DOCS_QUICK_REFERENCE.md`, `lupo-docs/IMPLEMENTATION_FRAMEWORK_SUMMARY.md` | 20260402210000 |
| C-FW-3 | [x] | Decision `decisions/20260402_210000_DECISION_channel_docs_framework.md` + linked answer | 20260402210000 |
| C-FW-4 | [x] | **PRD 31** — LILITH final audit (98/100) merged into canonical `31_implementation_folder_guidelines.md`; operational note + version sync **UTC `20260403025155`** (decision `20260403_025155…`) | Evidence: PRD file + `decisions/20260403_025155_DECISION_APPROVED_prd31_lilith_final_audit_version_sync.md` |

**Remaining (rewrite / promotion — completion = hash + path):**

| PLAN id | Task | Status | Completion evidence | Timestamp (UTC) |
|---------|------|--------|---------------------|-----------------|
| C-1 | Rewrite `versions/4.0.94/prd/30_prd_development_guide.md` as PRD *writing* guide | [ ] | SHA-256 of approved markdown + link to decision or PR header `status: approved` | TBD |
| C-2 | Redesign `versions/4.0.94/prd/31_context_system.md` (no parallel taxonomy; align PRD 26 WHERE / `edges.md`) | [ ] | SHA-256 of approved markdown + link to decision or PR header `status: approved` | TBD |
| C-3 | Promote working copies to `lupo-docs/prd/` (or record alternate canonical path in an APPROVED decision) | [ ] | Target paths under `lupo-docs/prd/` updated + pointer in `edges.md` | TBD |

### Phase F — IDE facet packs + VS Code propagation (completed — Cursor thread; UTC `20260402234551`)

**Evidence:** `decisions/20260402_234551_DECISION_APPROVED_ide_facet_packs_vscode_propagation.md`, `CHANGELOG.md` entry **[2026-04-02] IDE facet packs + VS Code propagation**.

- [x] Thin **`lupo-agents/`** packs for **Kiro**, **Windsurf**, **Warp**, **Cascade**, **VS Code** (`vscode-ide`), **Trae** — shared base + per-facet `actor_id` / propagation notes.
- [x] **`lupo-actors/{100,101,104,105,106,107}/README.md`** hub pages.
- [x] **`propagate_agent_rules.php --target=vscode`** and **`.vscode/lupopedia/`** outputs.
- [x] **`AGENTS.md`**, **`AGENT_REGISTRY.md`**, **`lupo-agents/_shared/README.md`**, **`validate_actor_identity.py`** aligned with registry slugs.

**Remaining (explicit gaps):** **`--target=warp`** and **`--target=trae`** not implemented; Antigravity IDE propagation still pending; **`install_new_lupopedia.sql`** may still drift from file registry for some facet rows — reconcile in a dedicated install/seed change.

### Phase E — Identity, temporal anchor, README (completed — Cursor thread; artifacts `20260402_225223`…`225226`; folder headers synced `20260402225416`)

**Evidence:** `decisions/20260402_225223_DECISION_APPROVED_cursor_thread_identity_temporal_docs.md`, `CHANGELOG.md` entry `[2026-04-02] Cursor thread — …`.

- [x] **Identity:** `IDENTITY_LAYERS_DOCTRINE.md` §3 canonical; `AGENTS.md` / `ONBOARDING.md` summaries; LILITH audit corrections (facet `actor_id`, registry authority, no hardcoded `auth_user`).
- [x] **Temporal:** `lupo-bin/tick.py`, `lupo-bin/echo_anchor_utc.py`, `lupo-docs/doctrine/TICK_PY_DOCTRINE.md`, `lupo-rules/root/UTC_TEMPORAL_ANCHOR_DOCTRINE.md`, PRD 00 §3.5a, `LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES` §2.4a, root `README.md` Temporal section + `lupopedia.init`, `.cursor/rules/TIMESTAMP_DOCTRINE.mdc`.
- [x] **README:** Thread manifest block (PRD 02/21 pointers); header timestamps from anchor policy.
- [x] **Version doc sync:** This folder `CHANGELOG` / `edges` / Q&A / comment for thread scope (no speculative template claims).

### Phase D — Certification, health, UI, onboarding, collections (parallel where independent)

- [x] **PRD 33** — Softaculous / **4.1.0** **gate** text **approved** (`lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md`, **`status: approved`**); **§12** traceability hooks in **`TODO.md`**; **APPROVED** decision **`decisions/20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md`**; implementation hub **`lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/`**.
- [ ] **Softaculous certification execution** — **§7–§10** checklist / installer / product evidence toward hoster certification (**not** satisfied by PRD text alone; track in **`TODO.md`**)
- [ ] ASCLEPIUS health monitor PRD implementation follow-through
- [ ] Eye / semantic monitoring widget visual polish
- [ ] Actor onboarding web flow
- [ ] Emergent collections behavior
- [ ] COUNTERMEASURE agent refinement

**Completion criteria:** Each line item has an owning artifact (PRD, decision, or implementation doc) and a clear done definition in `TODO.md`.

### Phase G — Doctrine audit + mobile / workflow UX (documentation — UTC `20260403140552`)

**Evidence:** `CHANGELOG.md` entry **[2026-04-03] Doctrine audit tooling, version ghosts, mobile / workflow doctrines**; decision `decisions/20260403_140552_DECISION_APPROVED_doctrine_audit_mobile_separation_docs.md`.

- [x] **PRD lineage audit** — `audit_doctrine_prd_edges.py` reports **189** `lupo-docs/doctrine/` files with PRD edges (**0** missing) at documentation pass time.
- [x] **Version ghost scanner + report** — `find_version_ghosts.py` + `version_ghosts_report.json` (**34** files with **critical** findings — manual review backlog).
- [x] **Mobile / workflow doctrines** — `MOBILE_SEPARATION_DOCTRINE.md`, `WOLFIE_WORKFLOW_DOCTRINE.md`; **PRD 35** draft; **PRD 33** §7.4 mobile items where merged; **AGENTS.md** + **LESSONS** alignment.
- [ ] **Manual remediation** — file-by-file cleanup of **34** critical ghost rows (no batch policy — see `answers/20260403_140554_ANSWER_version_ghost_cleanup_manual_review.md`).
- [ ] **PRD 34 / PRD 35 product execution** — deferred past pure documentation (track in `TODO.md`).

**Completion criteria:** Ghost backlog owned in `TODO.md`; doctrines and PRD edges discoverable from `edges.md`; no undocumented claims for batch header repair counts without measured runs.

### Phase H — Department-first actor model documentation + runtime follow-up (UTC `20260403222041`)

**Evidence:** `CHANGELOG.md` entry **[2026-04-03] Department-first actor model — APPROVED decision + synthesis ANSWER**; decision `decisions/20260403_222041_DECISION_APPROVED_department_first_actor_model_prd_alignment.md`; answer `answers/20260403_222043_ANSWER_department_model_visitor_chat_docs_synthesis.md`; open question `questions/20260403_222042_QUESTION_federation_navigation_compiler.md`.

- [x] **Canonical doctrine** — `lupo-docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md` + `IDENTITY_LAYERS_DOCTRINE.md` cross-link; PRD prose/edges for **02**, **05**, **07**, **13**, **15**, **18**, **25**, **32**; implementation **13** visitor-chat Q3 answered (thread-verified in decision body).
- [x] **Version-folder artifacts** — APPROVED decision, synthesis ANSWER (implementation Q1–Q3), OPEN federation navigation QUESTION.
- [x] **Act-as list unification (code)** — already recorded in `CHANGELOG.md` **[2026-04-03] LILITH audit — PRD 15** (`ActorService` delegates `AuthSessionManager`; `lupo_edges` supports removed from `ActorService`) — not duplicated as new work here.
- [ ] **Runtime audit** — visitor/chat POST paths resolve `actor_id` server-side per **PRD 05** / **PRD 18** + implementation Q2 note; align with `channels-api` behavior where applicable.
- [ ] **Resolver / callers** — verify `EffectiveActorResolver` (and any remaining act-as helpers) stay consistent with department-first doctrine after the above audit.
- [ ] **Federation navigation compiler** — product TBD; gated on **WOLFIE** decision (see open QUESTION `20260403_222042_…`).

**Completion criteria:** Runtime audit either closed with evidence paths in `CHANGELOG` or explicitly queued with owning artifact; federation compiler remains OPEN until a separate APPROVED decision exists.

### Phase I — Softaculous / WordPress documentation + partial installer alignment (UTC `20260404074421`)

**Evidence:** `CHANGELOG.md` entry **[2026-04-04] Softaculous / WordPress patterns**; `comments/20260404_074421_COMMENT_cursor_session_end_softaculous_wordpress_semantic_chat.md`.

- [x] **PRD 00 §15** — WordPress multi-environment patterns; edges to implementation answers / `InstallWizardHtaccessWriter` / `LEARNED_FROM_WORDPRESS.md` (per committed PRD body).
- [x] **`LEARNED_FROM_WORDPRESS.md`**, **`SEMANTIC_MONITORING_DOCTRINE.md`**, **`CHAT_UI_JAVASCRIPT_SHARED_STATE_DOCTRINE.md`** — doctrine files + PRD 28 / 18 / 33 cross-links as committed in-repo.
- [x] **Installer / packager code** — `lupo-install/InstallWizardHtaccessWriter.php` marker merge + Apache environment gate; `lupo-scripts/build_softaculous_package.sh` explicit **rsync** excludes for `.htaccess` / `.htpasswd`.
- [x] **Implementation 33** — WordPress study + packager flow **Q/A** and task list updates under `lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/` (as committed).
- [ ] **Softaculous certification execution** — remainder of PRD **§7–§10** checklist, optional packaging samples, permissions narrative; track in **`TODO.md`** (documentation approval does **not** close certification).
- [ ] **Next product slice (WOLFIE priority)** — visitor-facing web **login + configure**; **PHP-generated monitoring JS** on monitored pages (align **`lupopedia_js.php`** contract with current **`nav/semantic-navbar-js`** reality per **`SEMANTIC_MONITORING_DOCTRINE.md`**); **admin chat** interface; **default non-IIFE** for admin/chat per **`CHAT_UI_JAVASCRIPT_SHARED_STATE_DOCTRINE.md`**; optional spike — consolidate **`livehelp_js.php`** into **`lupopedia_js.php`** (requires explicit product decision + PRD touch).

**Completion criteria:** Thread outcomes recorded in **`CHANGELOG.md`** + session **COMMENT**; remaining work owned in **`WHAT_TO_WORK_ON_NEXT_SESSION.md`** and **`TODO.md`** without claiming full hoster certification complete.

### Phase J — Service agent doctrine + Softaculous auto-installer documentation + packaging hygiene (UTC `20260404161001`)

**Evidence:** `CHANGELOG.md` entry **[2026-04-04] Service agent architecture + PRD 00 §5 …**; `decisions/20260404_161001_DECISION_APPROVED_service_agent_architecture_and_softaculous_auto_installer_docs.md`; `comments/20260404_161001_COMMENT_cursor_service_agents_softaculous_version_doc_sync.md`.

- [x] **PRD 00 §5** — Agents/actors, KAIROS §5.7, implementation mirroring §5.8, THOTH §5.9, service agents §5.10 with roster **IRIS / ANUBIS / ROSE / THOTH / KAIROS** and **RuntimeActorLoopService** contrast.
- [x] **`SERVICE_AGENT_ARCHITECTURE.md`** + **`implementations/service_agents/`** (status, decisions, Q/A/C indexes).
- [x] **`LUPOPEDIA_HEADERS/README.md`** — THOTH grounding (JSON + table docs; forbid model-only schema claims).
- [x] **Softaculous spec + runtime** — `SOFTACULOUS_PACKAGE_BUILD.md` §1b–§1c + summary table; `lupopedia-config-sample.php`; `README.txt`; `bootstrap.php` runtime dirs; `install.php` `LUPO_INSTALLING`; `index.php` silent-install comment; `build_softaculous_package.sh` excludes live `lupopedia-config.php`.
- [ ] **Hoster certification execution** — still **`TODO.md`** / PRD 33 §10; documentation **does not** close §10.6–§10.7.

**Completion criteria:** Phase I + J **documented** outcomes in **`CHANGELOG`**; no false claim that Softaculous vendor acceptance is complete.

### Phase K — AGAPE + PRD 37 temporal discipline + multi-actor `to_actor_id` routing docs + scaffold `add-status` (UTC `20260404175352`)

**Evidence:** `CHANGELOG.md` top entries **[2026-04-04]** — AGAPE technical doctrine; PRD 37 temporal + `add-status`; multi-actor routing (`to_actor_id`); `decisions/20260404175216_DECISION_APPROVED_agape_kairos_temporal_multi_actor_routing_docs.md`; `comments/20260404175216_COMMENT_cursor_session_end_agape_kairos_routing_version_sync.md`.

- [x] **AGAPE** — PRD 00 §14.6; `AGAPE_DOCTRINE.md`; LILITH / validator policy; ROSE synthetic optional keys; `lupo-agents/agape/*` and ROSE pack alignment (thread-verified in **CHANGELOG**).
- [x] **KAIROS temporal** — PRD 37 §10.x (index-first, freshness hierarchy, `supersedes` vs `references`); §10.6 chat-context ingest rules; `AGAPE_DOCTRINE` §1.3 cross-ref; `scaffold_implementation.py` **`add-status`**; PRD 31 usage + orchestration notes.
- [x] **Multi-actor routing (docs)** — PRDs **18**, **36** §1.3, **37** §10.6, **31**, **05** aligned to **`lupo_dialog_messages.to_actor_id`** (routing-only; channel + thread = read context; no `mention_actor_ids`; `parent_dialog_message_id` future-only in prose).
- [ ] **Runtime wiring** — **`channels-api`** + chat UI accept/persist **`to_actor_id`** per **PRD 18** (docs complete; product open).
- [ ] **KAIROS code vs §10.6** — compare **`KairosConsolidationService`** (and API tick path) to PRD 37 §10.6 full-thread contract; gap list or code change in a later session.
- [ ] **Optional validator** — automated scan for PRD 00 §14.6 banned acceptance strings in headers (policy text shipped; code not required in this thread).

**Completion criteria:** Version-folder **THREAD_INDEX**, **edges**, **PLAN** K, **TODO**, **CHANGELOG** sync entry, and **WHAT_TO_WORK_ON_NEXT_SESSION** reference the same thread without claiming runtime chat routing or KAIROS ingest are closed.

### Phase L — Admin scroll top nav chrome + logout / intro replay (code — UTC `20260405001004`)

**Evidence:** `CHANGELOG.md` entry **[2026-04-05] Admin scroll nav**; `decisions/20260405001004_DECISION_APPROVED_admin_nav_logout_intro_cursor_thread.md`; `comments/20260405001004_COMMENT_cursor_session_end_admin_nav_logout_handoff.md`.

- [x] **`logout.php`** — clear **`sessionStorage`** key **`lupo_admin_scroll_intro_v1`** before redirect (HTML interstitial + **`location.replace`** + meta refresh fallback).
- [x] **`admin_layout.php`** — **90×60** left logo slot (**`$admin_nav_logo_*`** overrides); actor name **15** + **`..`**, no **`ACTOR:`** prefix, **`title`** = full name.
- [x] **`admin-intro-scroll.css`** — **`.lupo-admin-nav-logo`**, **`.lupo-admin-nav-lead`**, actor text **`max-width: 12em`**.
- [x] **`admin-intro-scroll.js`** — comment aligned with logout clear behavior.
- [ ] **Other sign-out paths** — if any bypass **`logout.php`**, mirror **`sessionStorage.removeItem`** or document exception (open if discovered).

**Completion criteria:** Thread outcomes in **`CHANGELOG`**; **WHAT NOT** in decision excludes unrelated PRD/validator threads; **TODO** / **WHAT_TO_WORK_ON_NEXT_SESSION** carry **Crafty parity checklist** handoff for next orchestrator session.

### Phase M — Semantic navbar external embed + Admin provisioning + PRD 21 (code + docs — UTC `20260405104405`)

**Evidence:** `CHANGELOG.md` entry **[2026-04-05] Semantic navbar external embed**; `decisions/20260405104405_DECISION_APPROVED_semantic_navbar_embed_admin_prd21_cursor_thread.md`; `comments/20260405104405_COMMENT_cursor_session_end_semantic_navbar_crafty_handoff.md`.

- [x] **`SemanticNavbarEmbedContext`** — origin normalization, federation node + **`semantic_widget`** trust gate, **`federation_discovery`** touch on **`unknown_node`** / **`no_trust`**, CORS emission.
- [x] **`semantic-navbar-api.php`** — **403** payload; operator message → **Admin → Semantic widget** (not SQL runbook).
- [x] **`AdminSemanticWidgetHandler`** — POST forms (register **`lupo_federation_nodes`**, grant **`lupo_federated_trust`**), CSRF, explicit PK allocation; UI order: slug controls → **relative** snippet → **absolute** snippet → federation section.
- [x] **`lupo-en.php`** — semantic admin strings (web-first wording); flash keys.
- [x] **`admin.php`** — semantic-widget section description.
- [x] **`lupo-docs/prd/21_semantic_navbar.md`** — external embed **5.x** narrative, admin obligation, slug contract, edges (PRD **11**, **34**, **SILENT_HARVEST**, **SEMANTIC_MONITORING**, **`AdminSemanticWidgetHandler`**).
- [ ] **Optional:** dedicated Admin flow to create **`lupo_contents`** scoped to a chosen **`federation_node_id`** (deferred — content via artifacts/headers until APPROVED).

**Completion criteria:** **`CHANGELOG`** + version **`decisions`/`comments`** + **`edges`** + **`TODO`** + **`WHAT_TO_WORK_ON_NEXT_SESSION`** reference this thread; **no** false claim that step-3 content creation is fully form-driven in Admin unless a later decision says so.

## References

- Frozen baseline: `lupo-docs/versions/4.0.93/README.md`
- Backlog: `lupo-docs/versions/4.0.94/TODO.md`
