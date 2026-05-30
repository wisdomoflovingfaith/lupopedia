---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260405104405"
  file_path_from_root: "docs/versions/4.0.94/WHAT_TO_WORK_ON_NEXT_SESSION.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.94/WHAT_TO_WORK_ON_NEXT_SESSION.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: session_handoff
  thread_id: "version-4-0-94-handoff"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: "What to work on next session"
  status: "active"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: WHAT_TO_WORK_ON_NEXT_SESSION — web_path: /docs/versions/4.0.94/WHAT_TO_WORK_ON_NEXT_SESSION.md

# WHAT TO WORK ON NEXT SESSION

**Recorded (UTC):** `20260405104405` (real UTC from `python bin/tick.py` this edit batch.)

**Human note (orchestrator break):** When you are rested, **go through the list of Crafty Syntax features** Lupopedia still needs — **start with the easy ones** and work upward. No rush; this handoff is intentional after the semantic-navbar / admin embed session.

## Priority 0 — Next session (2026-04-05 handoff — WOLFIE)

| Area | Intent | Doctrine / PRD |
|------|--------|----------------|
| **Crafty Syntax 3.7.5 parity (ordered)** | Walk the **feature / parity checklist** — **start with easy rows**, then harder ones; align with **PRD 33** §**7.4**–§**7.9** and in-repo Crafty reference as needed | **PRD 33**; **`legacy/craftysyntax/`** read-only reference; **`TODO.md`** first bullet under *Next session* |
| **Direct-address routing** | Wire **`to_actor_id`** in **`channels-api`** + **`chat-display.js`** (and related) so UI/API match **PRD 18** / **PRD 05**; **NULL** = broadcast | **PRD 18**, **PRD 05**, **`decisions/20260404175216_…multi_actor_routing_docs.md`** |
| **KAIROS ingest** | Compare **`KairosConsolidationService`** + **`kairos-api.php`** to **PRD 37** §10.6 (full-thread / index-first); document gaps or implement | **PRD 37**, `app/Services/Kairos/KairosConsolidationService.php` |
| **Optional AGAPE scan** | Implement PRD 00 §14.6 banned acceptance strings in **`validate_lupopedia_headers_universal.py`** if you want machine enforcement (policy text already shipped) | **PRD 00** §14.6, **`AGAPE_DOCTRINE.md`** |
| **Visitor web** | **Login** and **configure** flows for end users (post-install operator/visitor expectations); simple mobile web acceptable per **Two-UI** | **`MOBILE_SEPARATION_DOCTRINE.md`**, **`WOLFIE_WORKFLOW_DOCTRINE.md`** |
| **Monitoring embed** | PHP emits JS on **monitored** pages; align **`lupopedia_js.php`** story with **existing** routes (e.g. **`nav/semantic-navbar-js`**) — do not document fake endpoints | **`SEMANTIC_MONITORING_DOCTRINE.md`**, **PRD 28** |
| **Admin chat** | Wire **admin** chat UI / **`channels-controller`** / **`chat-display`** patterns | **PRD 18**, **`CHAT_UI_JAVASCRIPT_SHARED_STATE_DOCTRINE.md`** |
| **JS style** | **Default non-IIFE** in admin paths; IIFE only for small isolation cases (Crafty reference: inline audio in **`admin_users_refresh.php`**) | **`CHAT_UI_JAVASCRIPT_SHARED_STATE_DOCTRINE.md`** |
| **Optional unify** | Product spike: fold **`livehelp_js.php`** needs into **`lupopedia_js.php`** so one script owns shared visitor + monitor state | Decision + **PRD 18** / **28** if adopted |

**Still parallel (not replaced):** **34** critical version ghosts (manual); **`find_edges.py`** debug when time allows; runtime visitor/chat **`actor_id`** audit (**PLAN** Phase **H**); open federation navigation **QUESTION** `20260403_222042_…`; remainder of **PRD 33** §7–§10 execution (**TODO.md** tables).

---

## Session summary (2026-04-05) — admin scroll nav chrome + logout intro replay (code thread)

| Field | Value |
|-------|-------|
| **WHO** | Cursor (`actor_id` **102**), `cursor:root` |
| **WHAT** | **`logout.php`** clears **`sessionStorage`** key **`lupo_admin_scroll_intro_v1`** before **`login.php`** redirect; **`admin_layout.php`** **90×60** logo (**`$admin_nav_logo_*`**); actor name **15** + **`..`**, no **`ACTOR:`**, **`title`** full; **`admin-intro-scroll.css`** / JS comment |
| **WHERE** | Decision **`decisions/20260405001004_DECISION_APPROVED_admin_nav_logout_intro_cursor_thread.md`**; comment **`comments/20260405001004_COMMENT_cursor_session_end_admin_nav_logout_handoff.md`**; **`CHANGELOG`** **[2026-04-05]**; **`PLAN`** Phase **L** |
| **WHEN** | UTC **`20260405001004`** |
| **WHY** | Same-tab re-login skipped intro because **`sessionStorage`** outlived PHP session; top bar polish |
| **HOW** | HTML interstitial on logout; layout/CSS/PHP truncation (**`mb_*`** when available) |

**Human handoff:** Orchestrator break — next block = **Crafty Syntax feature list**, easy items first (**Priority 0** table above).

---

## Session summary (2026-04-04) — AGAPE, KAIROS temporal, multi-actor routing (documentation thread)

| Field | Value |
|-------|-------|
| **WHO** | Cursor (`actor_id` **102**), `cursor:root` |
| **WHAT** | **AGAPE** as technical constitutional metric (PRD 00 §14.6 + `AGAPE_DOCTRINE.md` + LILITH/validator/ROSE/agents); **PRD 37** §10 temporal discipline + **`scaffold_implementation.py add-status`** + PRD 31 cross-links; **multi-actor** docs — **`to_actor_id`** on **`lupo_dialog_messages`** across **PRD 18 / 36 / 37 / 31 / 05** |
| **WHERE** | Canonical paths listed in **`decisions/20260404175216_DECISION_APPROVED_agape_kairos_temporal_multi_actor_routing_docs.md`** |
| **WHEN** | PRD/doctrine edit stamps **`20260404172442`**, **`20260404173921`**, **`20260404174956`**; version receipt **`20260404175216`**; folder index sync **`20260404175352`** |
| **WHY** | Measurable cooperation vs sentimental QA strings; stop filesystem order posing as truth; LILITH **Option 1** routing simplicity |
| **HOW** | Markdown + YAML headers; **no** new **`parent_dialog_message_id`** in install SQL this thread; **no** runtime chat routing closure claimed |

**Version-folder artifacts:** **`CHANGELOG.md`** (four top entries including sync line), **`PLAN.md`** Phase **K**, **`TODO.md`** (completed + **Open — direct-address…**), **`edges.md`**, **`THREAD_INDEX`** files, this file, **`comments/20260404175216_…`**.

---

## Session summary (2026-04-03) — department-first approval batch

| Field | Value |
|-------|--------|
| **Duration** | Multiple sessions (thread handoff) |
| **Primary work** | **`ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`** + PRD alignment (**02**, **05**, **07**, **13**, **15**, **18**, **25**, **32**); implementation **13** visitor-chat Q3 answered; version artifacts **`222041`** decision, **`222043`** synthesis answer, **`222042`** open federation QUESTION |
| **Code already aligned (earlier same-day CHANGELOG)** | **`ActorService::getActorsUserCanActAs`** delegates **`AuthSessionManager::getActorsUserCanActAs`**; edge-based **`lupo_edges` `supports`** removed from **`ActorService`** — see top **`CHANGELOG.md`** entry **[2026-04-03] LILITH audit — PRD 15** |
| **Still open** | Runtime audit: visitor/chat POST paths resolve **`actor_id`** server-side (**implementation Q2** note). **34** critical version ghosts (unchanged queue). |

---

## Prior session summary (2026-04-03) — doctrine audit + mobile / workflow

| Field | Value |
|-------|--------|
| **Duration** | Multiple hours / multiple sessions (orchestrator break) |
| **Primary work** | Doctrine PRD-edge audit tooling; mobile separation + WOLFIE workflow doctrine; version-ghost identification; `4.0.94` version-folder sync (`CHANGELOG`, `PLAN` Phase G, `TODO`, `edges`, decision/Q&A `140552`–`140554`) |
| **Verified metrics** | `python scripts/audit_doctrine_prd_edges.py` → **189** files under `docs/doctrine/` with PRD lineage edges, **0** missing. `version_ghosts_report.json` → **34** files with **critical** ghost findings. |

**Key insight (common practice vs WOLFIE way):** “Industry standard” **responsive CSS** (one DOM, `@media` tweaks) is **not** sufficient for WOLFIE-class layouts (book spreads, liquid scroll, mouse-following eyes, floating chrome). Doctrine now encodes **Two-UI**: **separate** mobile surfaces vs **hand-crafted** desktop surfaces, with **admin desktop-first** and **consumer mobile-first** where applicable — see **`MOBILE_SEPARATION_DOCTRINE.md`** and **`WOLFIE_WORKFLOW_DOCTRINE.md`**.

**Honesty note:** Helpful batch scripts exist under **`scripts/`** (e.g. `fix_doctrine_headers.py`, `apply_doctrine_prd_lineage.py`, `find_version_ghosts.py`, `convert_wolfie_to_lupo.py`). **Do not** treat unlogged run counts as facts in the next session — re-run and capture output if you need numbers for CHANGELOG.

---

## When WOLFIE is back — `find_edges.py` (and everything else)

**Intent:** On return, **debug and test** **`scripts/find_edges.py`** against real **`.md`** files (doctrine/PRD samples, then wider passes): dry-run suggestions, tune **`--confidence`** / **`--headers`**, and only use **`--apply --yes`** or **`--interactive`** when outputs look right — then run **`validate_lupopedia_headers_universal.py`** and **`audit_doctrine_prd_edges.py`** on touched files. This sits **alongside** Priority **1** (34 ghosts), install/admin/Eye backlog, and mobile/desktop work — not a replacement for them.

---

## Priority 1 — Review 34 critical doctrine/PRD files (manual)

These paths are exactly the set where **`critical_findings`** is non-empty in **`docs/implementations/29_project_structure/status/version_ghosts_report.json`** (generated by **`scripts/find_version_ghosts.py`**). Each file needs **human** triage — **no** repo-wide blind replace (see **`answers/20260403_140554_ANSWER_version_ghost_cleanup_manual_review.md`**).

### Full list (34 files)

```
docs/doctrine/4.0.21_COMPLETION_PLAN.md
docs/doctrine/ACTOR_AGENT_AUTH_USER_MODEL.md
docs/doctrine/AGENT_BOUNDARIES_COMPACT.md
docs/doctrine/AI_AGENT_BOOT_NOTES.md
docs/doctrine/ANUBIS_FALLBACK_DOCTRINE.md
docs/doctrine/ANUBIS_ORPHAN_RULES.md
docs/doctrine/BAN_REASONS.md
docs/doctrine/CLASS_CONVERSION_DOCTRINE.md
docs/doctrine/COMPATIBILITY_MATRIX.md
docs/doctrine/CRAFTY_SYNTAX_IMPORT_IMPLEMENTATION_CHECKLIST.md
docs/doctrine/CRAFTY_SYNTAX_MIGRATION_PROJECT_BRIEF.md
docs/doctrine/DEVELOPMENT_WORKFLOW_DOCTRINE.md
docs/doctrine/EDGE_MODEL_DOCTRINE.md
docs/doctrine/ETHICAL_STATE_MARKERS_DOCTRINE.md
docs/doctrine/FILESYSTEM_MIGRATION_GUIDE.md
docs/doctrine/HYBRID_ACTOR_DOCTRINE_4.0.29.md
docs/doctrine/INDEX.md
docs/doctrine/INSTALLATION_PATH_DOCTRINE.md
docs/doctrine/LEXA_GATEWAY_INTEGRATION.md
docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md
docs/doctrine/LUPOPEDIA_DOCTRINE.md
docs/doctrine/MESSAGE_ATTRIBUTION.md
docs/doctrine/MIGRATION_DOCTRINE.md
docs/doctrine/MINIMAL_HOSTING_REQUIREMENTS.md
docs/doctrine/MigrationAtlas.md
docs/doctrine/PHP_COMPATIBILITY_AND_MINIMAL_HOSTING_DOCTRINE.md
docs/doctrine/PROJECT_REGISTRY_WORKFLOW.md
docs/doctrine/SCHEMA_CANONICAL_SOURCES.md
docs/doctrine/TABLE_COUNT_DOCTRINE.md
docs/doctrine/VERSIONING_DOCTRINE.md
docs/doctrine/VERSION_DOCTRINE.md
docs/doctrine/channels/filesystem_padding_layer.md
docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md
docs/prd/18_channel_chat_display.md
```

### Review guidelines

| Finding | Action |
|---------|--------|
| Phantom path (`/docs/`, legacy `docs/...` without `docs/`) | Point at **`docs/...`** or archive under **`docs/versions/`** |
| Phantom **`/database/migrations/`** (or pre-4.1.0 migration churn) | Align with **single install** doctrine — **no** Lupopedia→Lupopedia migration story until **4.1.0**; fix prose, do not invent ALTER chains |
| **`3.0.x`** reference (Crafty Syntax / external history) | Often **keep**; add explicit “historical / Crafty-era” context |
| **`3.0.x`** reference (reads like **current Lupopedia**) | Update to **4.0.x** or move to **`docs/versions/3.0.x/`** if the file is historical snapshot |
| File is entirely legacy structure description | Consider **`docs/versions/3.0.x/doctrine/`** (policy per file) |

---

## Priority 2 — PRDs deferred (documentation / product)

| PRD | Status | Next action |
|-----|--------|-------------|
| **PRD 34** — Federation semantic network | Deferred past “4.0.x stable” narrative | Draft exists as `docs/prd/34_federation_node_semantic_network.md` — expand when WOLFIE prioritizes |
| **PRD 35** — Mobile native app | Deferred past stable window | Draft: `docs/prd/35_mobile_native_app_separation.md` — aligns with Two-UI |

---

## Priority 3 — Mobile UI (utility-first; AI-assisted implementation)

Per **Two-UI** and **`WOLFIE_WORKFLOW_DOCTRINE.md`**, **consumer** mobile can be built as **functional** UI (touch, simple chrome), wired to the same **PDO / API** contracts as desktop — **not** a pixel clone of desktop.

| Area | Focus |
|------|--------|
| Mobile visitor chat | End-to-end flows, session, channels API |
| Mobile **Eye** (PRD 28) | Doctrine allows **animated/static** treatment + tracking — not the desktop DynAPI mouse-follow path |
| Mobile forms | Large targets, minimal steps |

**Constraint:** Respect **`MOBILE_SEPARATION_DOCTRINE.md`** — responsive-only is **not** the sole strategy for WOLFIE-class desktop art.

---

## Priority 4 — Admin / operator desktop (WOLFIE-first)

Desktop-first surfaces for people at desks (per doctrine):

- **`admin.php`** — full operator tooling
- **`live.php`** — operator console (multi-pane / color protocols as designed)
- Settings, data viewers, analytics — **precision** over mobile mimicry

---

## Carried forward — product backlog (still true)

These were on the prior handoff and remain valid in **dependency order** (see **`TODO.md`** / **`PLAN.md`** Phase D):

1. **Reproducible fresh install + Crafty 3.7.5 import** — only supported import path until **4.1.0** auto-install story.
2. **Admin web interface** — unlock day-to-day operator work after baseline install.
3. **Crafty feature parity** — checklist-driven; no drive-by refactors.
4. **Semantic “The Eye”** — product slice; align with **`MOOD_VECTOR_DOCTRINE.md`** / PRD 28 where applicable.
5. **Small hygiene** — `scaffold_implementation.py` templates vs **PRD 31** headers; optional **`prd`** schema value in header validator (separate pass).

---

## Observations — common practice vs WOLFIE way (IDE notes)

### 1) Responsive CSS is not the whole story

- **Common assumption:** One DOM + `@media` covers mobile and desktop.
- **WOLFIE way:** Book spreads, liquid scroll, mouse-following eyes, floating pens need **different DOM/JS/interaction** models; **separate** mobile pages or apps are in-bounds.

### 2) “Mobile-first” is not universal here

- **Common mantra:** Mobile-first everywhere.
- **WOLFIE way:** **Consumer** mobile-first where it helps reach; **admin** **desktop-first** — operators are at desks; native mobile app is its own track (**PRD 35**), not a webview afterthought.

### 3) Silent harvest scale (sourced)

- **SILENT_HARVEST_DOCTRINE.md** tables cite **1,000,000+** lifetime installs and **~144,000** active/reporting (callback-era) — with explicit caveats that this is **doctrine-sourced narrative**, not an independent census. Use that file as the reference, not chat memory.

### 4) Hand-coded desktop vs AI mobile utility

- Desktop “masterpiece” UI (DynAPI-era craft, intentional motion) does not map 1:1 to generic AI layout generation; **division of labor** in doctrine is intentional.

### 5) Constitutional rules are load-bearing

- **No FKs, BIGINT UTC, soft deletes, no DB datetime** — treat as **integration constraints**, not suggestions. Changing them requires explicit architectural decisions, not drive-by “modern SQL.”

---

## Suggested next-session workflow

1. **Runtime audit** — visitor/chat POST paths and **`EffectiveActorResolver`** (or equivalent) vs **PRD 05** / **PRD 18** + **`channels-api`** (see **`answers/20260403_222043_ANSWER_department_model_visitor_chat_docs_synthesis.md`**).
2. Triage **Priority 1** (34 files) — a few files per session with edits + re-run **`find_version_ghosts.py`** to shrink the critical set.
3. **`find_edges.py`** — exercise on representative **`.md`** files (see section above); treat as **tooling QA**, not a substitute for manual edge judgment.
4. Keep **install + admin** unblocked in parallel where dependencies allow (**Carried forward**).
5. For **mobile**, pick **one** vertical slice (e.g. visitor chat **or** Eye mobile shell) and ship **working** before pixel-perfect.
6. For **desktop**, reserve **WOLFIE** time for admin/live surfaces without forcing mobile paradigms.
7. **Federation navigation compiler** — only after **WOLFIE** product decision; track **`questions/20260403_222042_QUESTION_federation_navigation_compiler.md`** (OPEN).

---

## Reference — doctrines and scripts touched this workstream

**Doctrines (verify paths exist in-tree):** `MOBILE_SEPARATION_DOCTRINE.md`, `WOLFIE_WORKFLOW_DOCTRINE.md`, `TWO_LAYER_SECURITY_DOCTRINE.md`, `REVERSE_ENGINEERING_DOCTRINE.md`, `SILENT_HARVEST_DOCTRINE.md`, `ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md` — plus **`LESSONS_LEARNED_FROM_THE_WILD_WEST.md`** updates as recorded in **`CHANGELOG.md`**.

**Scripts:** `audit_doctrine_prd_edges.py`, `find_version_ghosts.py`, `find_edges.py`, `fix_doctrine_headers.py`, `apply_doctrine_prd_lineage.py`, `convert_wolfie_to_lupo.py` — under **`scripts/`**.

**Version docs:** Top of **`docs/versions/4.0.94/CHANGELOG.md`** for the latest **5W1H** session entries.

---

## Break reminder

Department-first actor model documentation is **APPROVED** (`decisions/20260403_222041_…`). **34** ghost files remain **intentionally** open for **manual** review. Doctrine PRD-edge coverage was **complete** at last audit (**189**/189 with lineage).

Rest is allowed. The repo keeps state.

---

**Next read:** [`TODO.md`](TODO.md) (**Open — direct-address chat routing**), [`PLAN.md`](PLAN.md) Phase **K** + **H**, [`CHANGELOG.md`](CHANGELOG.md) (top entries), [`decisions/20260404175216_DECISION_APPROVED_agape_kairos_temporal_multi_actor_routing_docs.md`](decisions/20260404175216_DECISION_APPROVED_agape_kairos_temporal_multi_actor_routing_docs.md), [`decisions/20260403_222041_DECISION_APPROVED_department_first_actor_model_prd_alignment.md`](decisions/20260403_222041_DECISION_APPROVED_department_first_actor_model_prd_alignment.md), [`version_ghosts_report.json`](../../implementations/29_project_structure/status/version_ghosts_report.json).

This output complies with Lupopedia Constitutional Root Rules.
