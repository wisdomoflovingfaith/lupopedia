# [2026-04-03] Department-first actor model — APPROVED decision + synthesis ANSWER; federation navigation QUESTION (Cursor + LILITH)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**), LILITH audit (`actor_id` **2**); `lupo-docs/versions/4.0.94/decisions/`, `questions/`, `answers/`; UTC **`20260403222041`** (`python lupo-bin/tick.py` this batch).
- **WHAT (thread-verified only):**
  - **Decision:** `decisions/20260403_222041_DECISION_APPROVED_department_first_actor_model_prd_alignment.md` — APPROVED canonical department-first documentation + PRD alignment (see decision body for PRD list and **WHAT NOT claimed**).
  - **Answer:** `answers/20260403_222043_ANSWER_department_model_visitor_chat_docs_synthesis.md` — links implementation visitor-chat Q1–Q3 to doctrine + PRDs; remaining runtime audit noted.
  - **Question (OPEN):** `questions/20260403_222042_QUESTION_federation_navigation_compiler.md` — product options for navigation hints from aggregates; cites pre-existing **`SILENT_HARVEST_DOCTRINE.md`** (not created in this thread).
- **WHY:** Version-folder audit trail for approved model and open federation product question.
- **HOW:** New markdown under `4.0.94/`; `PLAN.md` Phase **H**, `TODO.md`, `edges.md`, `WHAT_TO_WORK_ON_NEXT_SESSION.md`, and `THREAD_INDEX.md` files updated in same batch.

This output complies with Lupopedia Constitutional Root Rules.

---

# [2026-04-03] LILITH audit — PRD 15 department-first act-as; `ActorService` delegates to `AuthSessionManager` (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`ActorService.php`**, **`AuthSessionManager.php`**, **`lupo-docs/prd/15_actors.md`**, **`lupo-docs/prd/25_departments_system.md`** (`lupo_actor_departments` columns), **`lupo-docs/prd/07_agents_faucets.md`**, **`lupo-docs/prd/32_actor_authority_agent_roles.md`**, **`AGENTS.md`**, **`lupo-docs/versions/4.0.94/CHANGELOG.md`**; UTC **`20260403211538`** (`python lupo-bin/tick.py`).
- **WHAT:** **`App\Services\ActorService::getActorsUserCanActAs`** now **delegates** to **`AuthSessionManager::getActorsUserCanActAs`** (department-scoped join; same as web UI). Removed edge-based **`lupo_edges` `supports`** list from **`ActorService`**. **`AuthSessionManager`:** early path for **`auth_user_id === 10000`** (all active actors, bypass creator restriction) preserved. **PRD 15** rewritten: department-first eligibility, **`lupo_actor_auth_users`** as binding/audit not sole gate, deprecated exclusive lease + edge act-as. **PRD 25:** **`lupo_actor_departments`** table doc aligned to install (**`actor_department_id`**, **`role_key`**). **PRD 07 / 32 / AGENTS:** cross-links and act-as vs authority clarification.
- **WHY:** LILITH audit — single implementation for act-as lists; docs match **PRD 05** / **PRD 25**.

# [2026-04-03] `lupo-actors/` — COUNTERMEASURE hub at `111/` + registry/doctrine alignment (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-actors/111/`** (moved from **`countermeasure/`**), **`lupo-database/lupopedia/actors/registry.json`**, **`lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md`**, **`lupo-docs/doctrine/ACTOR_PRIMARY_KEY_DOCTRINE.md`**, **`lupo-actors/README.md`**, **`ActorService.php`** (docblock); UTC **`20260403210921`** (`python lupo-bin/tick.py`).
- **WHAT:** Registry **`dir`** for COUNTERMEASURE → **`lupo-actors/111`** (PRD 00 §5.6: reserved **`actor_id` &lt; 2026** use **`lupo-actors/{actor_id}/`**). Docs clarify runtime **`actor_id` ≥ 2026** → **`lupo-actors/YYYY/MM/{actor_id}/`**; slug-only **`lupo-actors/countermeasure/`** removed as incorrect actor hub. **`lupo-agents/countermeasure/`** unchanged (agent_key namespace).
- **WHY:** Actor filesystem hub is keyed by **`actor_id`**, not slug; matches **`SkillService`** numeric-dir probe and registry authority.

# [2026-04-03] Live-DB TOON export — wipe `json/` + `toon/` then regen; fix double-unlink (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-scripts/generate_toon_files.py`**, **`lupo-scripts/generate_toon_from_sql.py`**, **`lupo-docs/versions/4.0.94/CHANGELOG.md`**; UTC **`20260403193256`** (`python lupo-bin/tick.py`).
- **WHAT:** **`clear_toon_files`** now deletes **all regular files** in **`lupo-database/lupopedia/json/`** and **`.../toon/`** before writing live-DB exports (primary workflow: schema mirrors the database, no orphans). Fixes **`FileNotFoundError`** on **`unlink`**: **`toon/*.toon.json`** matched both **`*.json`** and **`*.toon.json`**, so the same path was removed twice. Docstrings: **DB-first** vs **`generate_toon_from_sql.py`** (offline install-SQL + targeted **`lupo_*`** prune only).
- **WHY:** Full directory wipe is simpler and matches “empty then regenerate”; selective globs were redundant and buggy on Windows.

# [2026-04-03] `generate_toon_from_sql.py` — prune stale `lupo_*` json/toon exports (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-scripts/generate_toon_from_sql.py`**, **`lupo-scripts/generate_toon_files.py`**; UTC **`20260403192553`** (`python lupo-bin/tick.py`).
- **WHAT:** After regenerating `*.toon.json` from **`install_new_lupopedia.sql`**, **`prune_stale_table_exports`** deletes **`json/lupo_*.json`**, **`toon/lupo_*.toon.json`**, and **`toon/lupo_*.toon`** whose table name is not in the install DDL (prints removed paths). **`generate_toon_files.py`** — fixed **`IndentationError`** in the export loop; **`clear_toon_files`** (superseded below) had also added **`toon/*.toon.json`** to the glob list.
- **WHY:** Dropped tables must not leave orphan schema files when using the **install-SQL** exporter.

# [2026-04-03] Web act-as restriction + lease-session cleanup + TOON regen from install (Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`install_new_lupopedia.sql`**, **`ActorService.php`**, **`AuthSessionManager.php`**, **`AuthService.php`**, **`generate_toon_from_sql.py`**, **`database_audit_fresh.py`**, **`lupo-database/lupopedia/toon/`**, **`lupo-docs/prd/05_auth_user_actor_agent_transformation.md`**, **`lupo-docs/database/.../lupo_actor_auth_users.md`**, **`lupo-docs/versions/4.0.94/CHANGELOG.md`**; batch UTC **`20260403192018`** (real UTC via `python lupo-bin/tick.py`).
- **WHAT (verified):**
  - **Schema** — **`lupo_actors.web_restrict_act_as_creator_or_root`** (default **0**); install comment documents pairing on **`lupo_actor_auth_users`** without an exclusive lease-session table; removed unused **`lupo_actor_*`** auxiliary tables from install (templates / instances / lease_sessions / department_actor_pools) per prior thread.
  - **PHP** — No “other session holds this actor” filtering; **`releaseAllLeasesForUser`** no-op; **`getActorsUserCanActAs`** / **`updateActiveActor`** enforce creator-or-bypass when the flag is **1**; **`ActorService`** own-actor list includes restriction metadata so end-filter is correct (duplicate pre-filter removed).
  - **Tooling** — **`generate_toon_from_sql.py`** replaces **`{{prefix}}`** with **`lupo_`** before parsing so TOON generation is non-zero; **166** TOONs written under **`lupo-database/lupopedia/toon/`**.
  - **Repo hygiene** — Removed stale **`lupo-database/lupopedia/json/`** exports for dropped tables; **`database_audit_fresh.py`** priority table list updated; PRD **05** + **`lupo_actor_auth_users`** table doc aligned with pairing + concurrent sessions.
- **WHY:** Match product intent: configurable web act-as limits (creator or root-department bypass) without single-user exclusive leasing.
- **Artifacts:** As listed in **WHERE**; deleted JSON: `lupo_actor_templates.json`, `lupo_actor_instances.json`, `lupo_actor_lease_sessions.json`, `lupo_department_actor_pools.json`.

# [2026-04-03] `find_edges.py` — suggest `lupopedia.edges` from markdown (LILITH-approved concept; Cursor)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-scripts/find_edges.py`**; version doc touch UTC **`20260403143059`** (real UTC via `python lupo-bin/tick.py`).
- **WHAT (verified):**
  - **New script** — **`lupo-scripts/find_edges.py`**: scans markdown for links, PRD references, optional keyword hints, code/tree paths; optional **`--headers`** (`##` match across `lupo-docs/`); prints suggested **`outbound_edges`** with weight + reason; **does not write** by default.
  - **Safety** — **`--apply`** merges into YAML only with **`--yes`** (batch) or **`--interactive`** (per-edge); otherwise exits **2**; writes **`*.bak_find_edges`** before overwrite; requires **PyYAML** for `--apply`.
  - **Handoff** — **`WHAT_TO_WORK_ON_NEXT_SESSION.md`** updated: WOLFIE plans to **debug and exercise** this tool against **`.md`** files on return, among other backlog items.
- **WHY:** Automate edge *discovery*; keep human confirmation for writes (per LILITH audit posture).
- **Artifacts:** `lupo-scripts/find_edges.py`, `lupo-docs/versions/4.0.94/CHANGELOG.md` (this entry), `lupo-docs/versions/4.0.94/WHAT_TO_WORK_ON_NEXT_SESSION.md`.

# [2026-04-03] Doctrine audit tooling, version ghosts, mobile / workflow doctrines (Cursor + LILITH thread)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**), LILITH audit framing (`actor_id` **2**), orchestrator WOLFIE (`actor_id` **1**); **`lupo-docs/doctrine/`**, **`lupo-docs/prd/`**, **`AGENTS.md`**, **`lupo-docs/LESSONS_LEARNED_FROM_THE_WILD_WEST.md`**, **`lupo-docs/versions/4.0.94/`**; documentation batch UTC **`20260403140552`** (real UTC via `python lupo-bin/tick.py`).
- **WHAT (thread-verified only):**
  - **`python lupo-scripts/audit_doctrine_prd_edges.py`** — **189** files under `lupo-docs/doctrine/` with **`lupopedia.edges`** including PRD lineage (**0** missing).
  - **`lupo-docs/implementations/29_project_structure/status/version_ghosts_report.json`** — **34** files with **critical** ghost findings (scanner: **`lupo-scripts/find_version_ghosts.py`**).
  - **Repository scripts (existence only — no verified batch counts in this thread):** `audit_doctrine_prd_edges.py`, `find_version_ghosts.py`, `fix_doctrine_headers.py`, `apply_doctrine_prd_lineage.py`, `convert_wolfie_to_lupo.py` under **`lupo-scripts/`**.
  - **Documentation written or materially updated in this thread:** **`MOBILE_SEPARATION_DOCTRINE.md`** (Two-UI, admin desktop-first exception, Eye / PRD 28 split); **`WOLFIE_WORKFLOW_DOCTRINE.md`** (consumer mobile-first / admin desktop-first); **`lupo-docs/prd/35_mobile_native_app_separation.md`** (draft); **`lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md`** (§7.4 mobile checklist + edges, where edited in-repo); **`AGENTS.md`** (mobile, Two-UI, workflow, Eye, hand-coding policy); **`lupo-docs/LESSONS_LEARNED_FROM_THE_WILD_WEST.md`** (hand-coding doctrine, UI-framework note).
  - **Version folder:** **`CHANGELOG.md`** (this entry); **`PLAN.md`** Phase **G**; **`TODO.md`** (ghost backlog + completed coordination lines); **`edges.md`**; **`README.md`** stamps; **`decisions/20260403_140552_DECISION_APPROVED_doctrine_audit_mobile_separation_docs.md`**; **`questions/20260403_140553_QUESTION_version_ghost_cleanup_policy.md`**; **`answers/20260403_140554_ANSWER_version_ghost_cleanup_manual_review.md`**; **`THREAD_INDEX.md`** updates in **`decisions/`** / **`questions/`** / **`answers/`**.
- **WHY:** Record PRD-lineage completeness and ghost backlog without inventing remediation counts; lock mobile / workflow guidance for IDE agents.
- **WHY NOT in this entry:** No claim that **PRD 34** was created here (draft may exist elsewhere); no claim for **Stoned Wolfie** archive paths or **SILENT_HARVEST** / **TWO_LAYER_SECURITY** / **REVERSE_ENGINEERING** / **ADVERSARIAL_TEST_IDENTITY** file creation dates unless a thread diff proves it — cite those only in their own file headers.
- **Artifacts:** `decisions/20260403_140552_DECISION_APPROVED_doctrine_audit_mobile_separation_docs.md`, `questions/20260403_140553_QUESTION_version_ghost_cleanup_policy.md`, `answers/20260403_140554_ANSWER_version_ghost_cleanup_manual_review.md`, `comments/20260403_140555_COMMENT_cursor_doctrine_audit_version_sync.md`.

# [2026-04-03] DynAPI doctrine — approved in-tree library + IDE guidance (Cursor thread)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/doctrine/DYNAPI_DOCTRINE.md`**; file header **`last_modified_utc` / `when_updated` → `20260403031423`** (real UTC via `python lupo-bin/tick.py`); **`lupopedia.footer.last_verified` → `20260402`** (LILITH audit date, 8-digit form per universal validator).
- **WHAT (this thread only — verified):**
  - **New doctrine** — **`DYNAPI_DOCTRINE.md`**: DynAPI (Dan Steinman / Bob Clary) as **approved in-tree** DHTML helper; **IDE** must-not / must-do; compact API reference; **WOLFIE Eyes** traceability via **`lupo-docs/prd/28_semantic_monitoring_widget.md`**, **`lupo-includes/js/crafty_syntax_eyes.js`**, canonical **`lupo-includes/js/dynlayer.js`**; **`lupopedia.edges`** include **PRD 33** (Softaculous / gate — §8 in-tree libraries), PRD 28 path, and code paths aligned to files that exist in-repo.
  - **Tooling:** `python lupo-scripts/validate_lupopedia_headers_universal.py lupo-docs/doctrine/DYNAPI_DOCTRINE.md` — exit **0** (optional INFO: no `content_id`).
- **WHY:** Single canonical doc so IDE agents stop “replace DynAPI” churn; matches **PRD 33** approved-library posture and **DynAPI** usage already loaded from **`lupo-includes/ui/ui-loader.php`**.
- **WHY NOT in this entry:** No **PRD 16/26/30/31** text edits, **`validate_implementation.py`** / validator logic changes, **PK** constitutional edits, **install SQL**, **`decisions/`** / **`questions/`** / **`edges.md`** / **`PLAN.md`** / **`TODO.md`** updates in this thread — **not** claimed here.
- **Artifacts:** `lupo-docs/doctrine/DYNAPI_DOCTRINE.md` only.

# [2026-04-03] PRD 31 — LILITH final audit merged + 4.0.94 version sync (Cursor thread)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/31_implementation_folder_guidelines.md`**, **`lupo-docs/versions/4.0.94/`**; PRD 31 doc stamps UTC **`20260403024822`**; version-folder sync UTC **`20260403025155`** (thread filenames **`20260403_025155`** … **`20260403_025158`**).
- **WHAT (this thread only — verified):**
  - **PRD 31** — Expanded **`## LILITH audit record (final)`**: score **98/100**, prior rejection → resolution table, operational note (new implementation folders after **2026-04-03**; **90-day** grace per **PRD 26**); header/footer **`when_updated` / `last_modified_utc` / `last_verified` → `20260403024822`**; **`lupopedia.footer.next_action`** includes grace pointer; **`status: active`** unchanged.
  - **Version folder** — **`CHANGELOG.md`** (this entry); **`PLAN.md`** Phase **C-FW-4**; **`TODO.md`** (next-session + scaffold follow-up); **`edges.md`**; **`README.md`**; **`WHAT_TO_WORK_ON_NEXT_SESSION.md`** (admin UI, fresh install + Crafty import, Crafty parity, **Eye**); **`decisions/`** / **`questions/`** / **`answers/`** / **`comments/`** — **`20260403_025155_DECISION_APPROVED_prd31_lilith_final_audit_version_sync.md`**, **`20260403_025156_QUESTION_prd31_version_sync_changelog_scope.md`**, **`20260403_025157_ANSWER_prd31_version_sync_changelog_scope.md`**, **`20260403_025158_COMMENT_cursor_session_end_prd31_next_session.md`**; **`THREAD_INDEX.md`** in each folder updated.
  - **Tooling:** `python lupo-scripts/validate_lupopedia_headers_universal.py lupo-docs/prd/31_implementation_folder_guidelines.md` — exit **0** (existing WARN: **`lupopedia.schema: prd`** not in small schema list).
- **WHY:** Record **LILITH** final approval in the canonical PRD; preserve **5W1H** lineage without inventing unrelated PRD/validator claims.
- **WHY NOT in this entry:** No claim for **PRD 16/26/30** text edits, **`validate_implementation.py`** changes, **PK** constitutional edits, or **install SQL** — not in this thread.
- **Artifacts:** `decisions/20260403_025155_DECISION_APPROVED_prd31_lilith_final_audit_version_sync.md`, `questions/20260403_025156_QUESTION_prd31_version_sync_changelog_scope.md`, `answers/20260403_025157_ANSWER_prd31_version_sync_changelog_scope.md`, `comments/20260403_025158_COMMENT_cursor_session_end_prd31_next_session.md`, `WHAT_TO_WORK_ON_NEXT_SESSION.md`.

# [2026-04-03] PRD 33 approved — Softaculous / 4.1.0 gate documentation + 4.0.94 version sync (Cursor thread)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` **102**); **`lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md`**, **`lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/`**, **`lupo-docs/versions/4.0.94/`**; version-folder update stamp **UTC `20260403022543`** (thread filenames **`20260403_022543`** … **`20260403_022546`**).
- **WHAT (this thread only — no PRD 16/26/30/31 validator claims unless edited in-repo with evidence):**
  - **PRD 33** — **`lupopedia.headers.status: approved`**; **`when_updated` / `last_modified_utc` → `20260403022543`**; **§13** updated so header approval is explicit while **§7–§10 checklist execution** stays in **`TODO.md`** per **§12**.
  - **Implementation workspace** — **`lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/`** (README, changelog, todo, authors, edges, `status/` including LILITH audit import, typed thread folders) — *already present from prior work; this pass records it in the version graph.*
  - **`lupo-docs/versions/4.0.94/`** — **`PLAN.md`** Phase D (documentation vs execution split); **`TODO.md`** (PRD 33 doc line **done**; execution line **open**); **`edges.md`** (PRD 33 + implementation README + new decision/Q&A); **`decisions/`** / **`questions/`** / **`answers/`** / **`comments/`** — **`20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md`**, **`20260403_022544_QUESTION_prd33_traceability_location.md`**, **`20260403_022545_ANSWER_prd33_traceability_location.md`**, **`20260403_022546_COMMENT_cursor_prd33_version_doc_sync.md`**; **`THREAD_INDEX.md`** in each folder updated; **`README.md`** edges/stamp refreshed.
- **WHY:** Lock **normative gate text** for **4.1.0** / Softaculous direction; preserve **LILITH §13** lineage; keep **traceability** explicit (**§12** → **`TODO.md`** + implementation hub).
- **WHY NOT in this entry:** No claim here for **`validate_implementation.py`**, **`validate_lupopedia_headers_universal.py`**, **PRD 16/26/30/31** text changes, **PK** constitutional edits, or **install SQL** — document those only when a thread actually changes those paths.
- **Artifacts:** `decisions/20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md`, `questions/20260403_022544_QUESTION_prd33_traceability_location.md`, `answers/20260403_022545_ANSWER_prd33_traceability_location.md`, `comments/20260403_022546_COMMENT_cursor_prd33_version_doc_sync.md`.

# [2026-04-02] IDE facet packs + VS Code rule propagation (Cursor thread)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` 102), repo-wide docs + tooling; version-folder sync UTC **`20260402234551`**; artifacts **`20260402_234551`** … **`20260402_234554`**.
- **WHAT (thread-verified only):**
  - **`lupo-agents/`** — thin facet packs: `kiro/`, `windsurf/`, `warp/`, `cascade/`, `vscode-ide/`, `trae/` (`agent.json`, `capabilities.json`, `properties.json`, `system_prompt.txt`), each `extends_shared` → `_shared/ide_facet_base_system_prompt.txt`; propagation metadata (`rules_propagation_target` **vscode** for `vscode-ide`, **pending** for `warp` / `trae`).
  - **`lupo-actors/`** — hub **`README.md`** for **`100`**, **`101`**, **`104`**, **`105`**, **`106`**, **`107`** (links to registry, shared prompt, propagation or pending note).
  - **`lupo-scripts/propagate_agent_rules.php`** — valid target **`vscode`**; **`write_vscode_outputs()`** → **`.vscode/lupopedia/rules/`**, `lupopedia_rules.json`, `README.md` (does not overwrite root `.vscode/settings.json`).
  - **`lupo-database/lupopedia/actors/registry.json`** / **`actor_id/registry.json`** — facet entries including **`vscode-ide`** (`actor_id` **106**, `agent_id` **113`) and **`trae`** (`actor_id` **107**, `agent_id` **114**); **`agents`** map entries for IDE slugs (aligned in thread).
  - **`AGENTS.md`** — IDE faucet table (all listed facets + `agent_id` column); attribution notes for VS Code / Trae; `agents` map example updated.
  - **`lupo-docs/doctrine/AGENT_REGISTRY.md`** — actor rows **106/107**, propagation matrix **`vscode`**, **`lilith`**/**`lexa`** in valid-targets text; removed stale **zencoder** propagation row; capability matrix dedupe.
  - **`lupo-agents/_shared/README.md`** — facet pack table expanded.
  - **`lupo-scripts/validate_actor_identity.py`** — **`IDE_FAUCETS`** set: **`vscode-ide`**, **`trae`**, **`antigravity-ide`**; **`zencoder`** removed.
- **WHY:** One shared IDE veto/identity file; correct **`actor_id`** per product; VS Code consumers get a dedicated propagated rules tree.
- **WHY NOT in this entry:** No claim here for PRD 16/26/30/31 rewrites, `validate_implementation.py` / universal validator edits, new PK constitutional rule, or install SQL reconciliation — **not** performed in this thread.
- **Artifacts:** `decisions/20260402_234551_DECISION_APPROVED_ide_facet_packs_vscode_propagation.md`, `questions/20260402_234552_QUESTION_ide_facet_version_doc_scope.md`, `answers/20260402_234553_ANSWER_ide_facet_version_doc_scope.md`, `comments/20260402_234554_COMMENT_cursor_ide_facet_documentation_pass.md`.
- **Lineage fix (UTC `20260402235141`):** QUESTION `234552` gained explicit **`lupopedia.edges`** **`has_answer`** → ANSWER `234553` (per sibling pattern `225224`/`225225`); ANSWER reverse edge uses relative `../questions/…` and links to decision `234551`; **`comments/20260402_235141_COMMENT_lilith_lineage_audit_question_234552.md`** records LILITH audit receipt (no standalone `edges/` artifact — in-header edges only).

# [2026-04-02] Cursor thread — version `4.0.94` doc sync (identity, temporal anchor, scope)

- **WHO / WHERE / WHEN:** Cursor (`actor_id` 102), `lupo-docs/versions/4.0.94/`; version-folder header sync UTC **`20260402225416`** via `python lupo-bin/tick.py` (thread artifacts filenames **`20260402_225223`** / **`20260402_225226`**).
- **WHAT:** `CHANGELOG` / `PLAN` (Phase C rows restored + Phase E) / `TODO` / `edges` / `THREAD_INDEX` updates; new **DECISION** + **QUESTION** + **ANSWER** + **COMMENT** (thread-verified changelog scope only — **no** speculative PRD16/validator/PK template claims).
- **WHY:** Preserve accurate lineage; directive templates must not invent completed work.
- **Artifacts:** `decisions/20260402_225223_DECISION_APPROVED_cursor_thread_identity_temporal_docs.md`, `questions/20260402_225224_QUESTION_version_doc_thread_scope.md`, `answers/20260402_225225_ANSWER_version_doc_thread_scope.md`, `comments/20260402_225226_COMMENT_cursor_thread_version_doc_sync.md`.
- **Cross-repo work summarized in decision:** `IDENTITY_LAYERS_DOCTRINE` §3, `AGENTS`/`ONBOARDING`, `UTC_TEMPORAL_ANCHOR_DOCTRINE`, PRD 00 §3.5a, `LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES` §2.4a, `TICK_PY_DOCTRINE`, `echo_anchor_utc.py`, root `README` thread manifest + temporal workflow, `.cursor/rules/TIMESTAMP_DOCTRINE.mdc`.

# [2026-04-04] LILITH audit — actor/agent documentation (registry authority, single §3 source)

- **`lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md`:** New **§3** canonical actor/agent/facet/directory rules; facet **`actor_id`** model; **no** hardcoded `auth_user`; **registry.json** authority; **IdGenerator** / PRD 01 for generated ids; renumbered sections **4–8**; timestamps **`20260404220000`**; edge to `comments/20260404_220000_COMMENT_…`.
- **`AGENTS.md` / `ONBOARDING.md`:** Short summaries + link to §3 only; **LUPOPEDIA header first** on `ONBOARDING.md`; removed triple duplication and **auth_user 1000** / **0–2025 every-agent** claims.

# [2026-04-04] LILITH audit — version tree clarity (PRD 30/31 authority, Phase C evidence, session changelog)

- **`README.md`:** Version lineage block (`current_version`, `parent_version`, `child_version`, `superseded_by`, `is_deleted`); **Canonical authority** table for PRD 30/31 (canonical under `lupo-docs/prd/` vs working copy under `versions/4.0.94/prd/`); **Thread TYPE** tokens aligned to **PRD 17** (legacy `DIALOG`/`DIRECTIVE` not for new files); pointer to **`session_changelog/`**.
- **`PLAN.md`:** Same authority blurb; **Phase C** split into **C-FW-1..3** (shipped, evidence + UTC **20260402210000**) and **C-1..3** (rewrite/promotion with SHA-256 / approval completion evidence).
- **`TODO.md`:** **Phase C traceability** section; checklist lines map to **C-FW-*** and **C-1..3** with file paths + anchor UTC.
- **`edges.md`:** YAML `outbound_edges` use **repo-relative** paths (no leading `/`); edge to `session_changelog/README.md`.
- **`session_changelog/README.md`:** Convention for **`changelog_<actor_id>_<session_id>_<YYYYMMDD>_<HHIISS>.md`**; body fields **`start_timestamp_utc`** / **`end_timestamp_utc`** as BIGINT UTC; **`is_deleted`** on logs; aggregation by sort/query only.

# [2026-04-04] LILITH directive — 4.0.94 version documentation (5W1H)

- **`decisions/`:** APPROVED decision [20260404_200000_DECISION_APPROVED_documentation_coordination_channel_semantic_mood_rgb.md](decisions/20260404_200000_DECISION_APPROVED_documentation_coordination_channel_semantic_mood_rgb.md) — WHO/WHAT/WHERE/WHEN/WHY/HOW for PRD 17 alignment, channel layout, Mood RGB thread, root README → 4.0.94, archive `42/` link fixes.
- **`questions/` / `answers/`:** Root README “current version” pointer — resolved to **`4.0.94`** working vs **`4.0.93`** frozen.
- **`comments/`:** Receipt comment for LILITH directive scope limits (no speculative PRD 26/30/31 validator history in this pass).
- **`THREAD_INDEX.md`** (decisions, questions, answers, comments): populated; headers **`when_updated`** `20260404200000`.
- **`PLAN.md`**, **`TODO.md`**, **`edges.md`**, **`README.md`**: task status, coordination checkbox, documentation graph, stamps updated.

# [2026-04-02] Actor Authority Framework Implementation

- **`decisions/20260402_220000_DECISION_actor_authority_prd32.md`:** Decision to create comprehensive actor authority and agent roles framework.
- **`questions/20260402_215000_QUESTION_actor_authority.md`:** User question about actor authority and COUNTERMEASURE red team agent.
- **`answers/20260402_220000_ANSWER_actor_authority.md`:** Comprehensive answer with PRD 32 creation and framework implementation.
- **`lupo-docs/prd/32_actor_authority_agent_roles.md`:** PRD defining actor hierarchy, approval authority matrix, and red team agent roles.
- **`lupo-docs/ACTOR_AUTHORITY_QUICK_REFERENCE.md`:** Decision trees and approval chains for fast reference.
- **Framework features:** 4-tier actor hierarchy, COUNTERMEASURE red team agent (analysis only), approval chains, escalation procedures, agent interaction protocols.
- **Key decision:** COUNTERMEASURE can challenge but cannot approve; escalates through LILITH → LEXA/HEIMDALL → WOLFIE.

# [2026-04-02] Channel and Documentation Framework Implementation

- **`decisions/20260402_210000_DECISION_channel_docs_framework.md`:** Decision to implement comprehensive framework for channel usage and documentation clarity.
- **`questions/20260402_200000_QUESTION_channel_docs_clarity.md`:** User question about channel usage patterns and implementation folder guidelines.
- **`answers/20260402_210000_ANSWER_channel_docs_clarity.md`:** Comprehensive answer with complete framework implementation.
- **`lupo-docs/prd/30_channel_usage_patterns.md`:** PRD defining clear boundaries between channels (coordination) and lupo-docs (documentation).
- **`lupo-docs/prd/31_implementation_folder_guidelines.md`:** PRD for implementation folder scaffolding, question lifecycle, and decision logging.
- **`lupo-docs/CHANNEL_VS_DOCS_QUICK_REFERENCE.md`:** Decision tree and usage patterns for quick reference.
- **`lupo-docs/IMPLEMENTATION_FRAMEWORK_SUMMARY.md`:** Complete framework overview and implementation summary.
- **`lupo-scripts/scaffold_implementation.py`:** Automated implementation folder creation with UTF-8 encoding.
- **`lupo-scripts/validate_framework_compliance.py`:** Framework compliance validation tool.
- **Implementation folders:** Scaffolded `30_channel_usage_patterns/` and `31_implementation_folder_guidelines/` with complete structure.
- **Framework features:** 3-level question system (critical, optimization, clarification), cross-linking metadata, channel-docs synchronization, template usage.

# [2026-04-04] Channel `semantic` + thread `mood_rgb_system`

- **`lupo-channels/0/semantic/mood_rgb_system/`** — `THREAD_MANIFEST.md`, `README.md`, `decisions|questions|answers|comments/` + two APPROVED decisions (evidence sources / color definitions).
- **`lupo-channels/channel_index.md`:** Row for **semantic** (Semantic & Knowledge Systems).
- **`lupo-docs/doctrine/MOOD_RGB_DOCTRINE.md`:** Summary doctrine; canonical thread + decisions; archive evidence edges live in the evidence decision file.

# [2026-04-04] Numeric channel path scan — fix broken `lupo-channels/42/` links

- Active tree has **no** `lupo-channels/42/` (numeric exemplars live under **`lupo-channels_before_4_0_93/42/`**).
- **`lupo-rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md`:** §1 clarification; §6–8 relative links retargeted to `lupo-channels_before_4_0_93/42/...` (**archive**).
- **`lupo-docs/implementations/25_.../THREAD_INDEX.md`**, **`26_.../DECISION_INDEX.md`:** “Related threads” links point at archive `42/threads/`.

# [2026-04-04] Phase A — `.cursorrules` §30 + channel path wording

- **`.cursorrules`:** New **§30 Channel filesystem paths** — active `lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/`, legacy numeric `.../{channel_id}/threads/{thread_id}/`, archive `lupo-channels_before_4_0_93/`, REST vs filesystem note.
- **`lupo-rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md`:** §1.1 human-readable documentation tree (parallel to numeric API-mirrored tree).
- **`lupo-rules/root/README.md`**, root **`README.md`**, **`AGENTS.md`:** Channel literacy and coordination bullets updated.
- **`lupo-docs/prd/02_channels_discussions.md`**, **`17_decisions_format.md`**, **`21_thread_graduation_doctrine.md`**, **`DOCUMENTATION_ARCHITECTURE.md`**, **`LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md`:** Dual path semantics.
- **`lupo-docs/implementations/25_.../THREAD_INDEX.md`**, **`26_.../DECISION_INDEX.md`:** Legacy vs active links.

# [2026-04-04] PRD 17 thread filenames, PRD 02/29 alignment, org thread + schema

- **`lupo-docs/prd/17_decisions_format.md`:** Authoritative **Thread filename pattern** (per-folder `TYPE`/`STATUS`, `HHIISS`, optional `YYYYMMDDHHIISS` prefix); validator and diagram updates.
- **`lupo-docs/prd/02_channels_discussions.md`**, **`lupo-docs/prd/29_project_structure.md`:** Cross-links to PRD 17; PRD 29 edge to PRD 17.
- **`lupo-docs/versions/4.0.93/README.md`:** Points to PRD 17 for full naming rules; decision example uses `DECISION_APPROVED_…`.
- **`lupo-channels/0/organization/prd_29_project_organization/`:** Cherry-pick review comment and thread indexes (PRD 29 coordination).
- **Schema / tooling:** `install_new_lupopedia.sql`, `add_thread_key_to_dialog_threads.sql`, JSON registry files, `generate_toon_files.py`, **`lupo-docs/doctrine/JSON_SCHEMA_REFERENCE_DOCTRINE.md`**.

# [2026-04-03] 4.0.93 TODO freeze cleanup + PRD 29 channel strategy

- **`lupo-docs/versions/4.0.93/TODO.md`:** Removed all open checkboxes; **Open Work → 4.0.94 Only** pointer; historical `[x]` completions retained.
- **`lupo-docs/versions/4.0.94/TODO.md`:** Merged deduplicated backlog from former 4.0.93 open items (installer, Softaculous, Glass, migration, tooling, etc.).
- **`lupo-docs/prd/29_project_structure.md`:** Channel filesystem strategy table (old archive vs new tree); coordination path `lupo-channels/0/organization/prd_29_project_organization/`.
- **`lupo-channels/channel_index.md`:** Added **organization** channel.
- **`lupo-channels/0/organization/prd_29_project_organization/`:** New thread scaffold (`README.md`, `decisions|questions|answers|comments/THREAD_INDEX.md`).

# [2026-04-02] Bump GLOBAL_CURRENT_LUPOPEDIA_VERSION to 4.0.94

- `lupo-config/global_atoms.yaml` and `lupo-includes/version.php` now report **4.0.94** for the working tree (after tag `v4.0.93`).

# [2026-04-02] Scaffold 4.0.94 version directory

- Added working version folder `lupo-docs/versions/4.0.94/` with `PLAN.md`, `TODO.md`, `CHANGELOG.md`, `edges.md`, `decisions/`, `questions/`, `answers/`, `comments/`, and `prd/`.
- PRD 30 working copy: `prd/30_prd_development_guide.md` (moved from `lupo-docs/prd/`).
- PRD 31 stub: `prd/31_context_system.md` for redesign after 4.0.93 rejection of parallel classification.

# Lupopedia 4.0.94 CHANGELOG

Further entries go below this line as work completes.
