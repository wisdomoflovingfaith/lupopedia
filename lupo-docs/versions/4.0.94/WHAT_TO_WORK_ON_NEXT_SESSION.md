---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  file_path_from_root: "lupo-docs/versions/4.0.94/WHAT_TO_WORK_ON_NEXT_SESSION.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/WHAT_TO_WORK_ON_NEXT_SESSION.md"
  when_updated: "20260403222041"
  last_modified_utc: "20260403222041"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4-0-94-handoff"
  title: "What to work on next session"
  author:
    type: "actor"
    id: 102
    name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: documentation
  artifact_kind: session_handoff
  purpose: "Handoff after department-first approval — 34 ghosts, runtime visitor/chat audit, federation QUESTION open, Two-UI / find_edges, product backlog"
  status: active
  tags:
    - "4.0.94"
    - "handoff"
    - "next_session"
    - "version_ghosts"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.94/TODO.md"
      type: references
      weight: 1.0
      reason: "Master backlog"
    - to: "lupo-docs/versions/4.0.94/PLAN.md"
      type: references
      weight: 1.0
      reason: "Phase order"
    - to: "lupo-docs/implementations/29_project_structure/status/version_ghosts_report.json"
      type: references
      weight: 1.0
      reason: "Authoritative 34-file critical set (scanner output)"
    - to: "lupo-docs/versions/4.0.94/answers/20260403_140554_ANSWER_version_ghost_cleanup_manual_review.md"
      type: references
      weight: 1.0
      reason: "No batch policy — manual per file"
    - to: "lupo-docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Two-UI strategy"
    - to: "lupo-docs/doctrine/WOLFIE_WORKFLOW_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Consumer mobile-first vs admin desktop-first"
    - to: "lupo-docs/doctrine/SILENT_HARVEST_DOCTRINE.md"
      type: references
      weight: 0.95
      reason: "Install / active-site scale narrative (sourced in doctrine tables)"
    - to: "lupo-docs/prd/31_implementation_folder_guidelines.md"
      type: references
      weight: 0.9
      reason: "Scaffold / templates follow-up"
    - to: "lupo-scripts/find_edges.py"
      type: references
      weight: 0.95
      reason: "Suggest outbound_edges from markdown; dry-run by default"
    - to: "lupo-docs/versions/4.0.94/decisions/20260403_222041_DECISION_APPROVED_department_first_actor_model_prd_alignment.md"
      type: references
      weight: 1.0
      reason: "APPROVED department-first docs + PRD alignment (LILITH)"
    - to: "lupo-docs/versions/4.0.94/answers/20260403_222043_ANSWER_department_model_visitor_chat_docs_synthesis.md"
      type: references
      weight: 1.0
      reason: "Synthesis ANSWER — implementation Q1–Q3 + doctrine"
    - to: "lupo-docs/versions/4.0.94/questions/20260403_222042_QUESTION_federation_navigation_compiler.md"
      type: references
      weight: 0.95
      reason: "OPEN — federation navigation compiler product"
    - to: "lupo-docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Canonical auth_user + department + actor joins"
lupopedia.footer:
  last_verified: "20260403222041"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: WHAT_TO_WORK_ON_NEXT_SESSION — web_path: /lupo-docs/versions/4.0.94/WHAT_TO_WORK_ON_NEXT_SESSION.md

# WHAT TO WORK ON NEXT SESSION

**Recorded (UTC):** `20260403222041` (real UTC from `python lupo-bin/tick.py` this edit batch.)

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
| **Verified metrics** | `python lupo-scripts/audit_doctrine_prd_edges.py` → **189** files under `lupo-docs/doctrine/` with PRD lineage edges, **0** missing. `version_ghosts_report.json` → **34** files with **critical** ghost findings. |

**Key insight (common practice vs WOLFIE way):** “Industry standard” **responsive CSS** (one DOM, `@media` tweaks) is **not** sufficient for WOLFIE-class layouts (book spreads, liquid scroll, mouse-following eyes, floating chrome). Doctrine now encodes **Two-UI**: **separate** mobile surfaces vs **hand-crafted** desktop surfaces, with **admin desktop-first** and **consumer mobile-first** where applicable — see **`MOBILE_SEPARATION_DOCTRINE.md`** and **`WOLFIE_WORKFLOW_DOCTRINE.md`**.

**Honesty note:** Helpful batch scripts exist under **`lupo-scripts/`** (e.g. `fix_doctrine_headers.py`, `apply_doctrine_prd_lineage.py`, `find_version_ghosts.py`, `convert_wolfie_to_lupo.py`). **Do not** treat unlogged run counts as facts in the next session — re-run and capture output if you need numbers for CHANGELOG.

---

## When WOLFIE is back — `find_edges.py` (and everything else)

**Intent:** On return, **debug and test** **`lupo-scripts/find_edges.py`** against real **`.md`** files (doctrine/PRD samples, then wider passes): dry-run suggestions, tune **`--confidence`** / **`--headers`**, and only use **`--apply --yes`** or **`--interactive`** when outputs look right — then run **`validate_lupopedia_headers_universal.py`** and **`audit_doctrine_prd_edges.py`** on touched files. This sits **alongside** Priority **1** (34 ghosts), install/admin/Eye backlog, and mobile/desktop work — not a replacement for them.

---

## Priority 1 — Review 34 critical doctrine/PRD files (manual)

These paths are exactly the set where **`critical_findings`** is non-empty in **`lupo-docs/implementations/29_project_structure/status/version_ghosts_report.json`** (generated by **`lupo-scripts/find_version_ghosts.py`**). Each file needs **human** triage — **no** repo-wide blind replace (see **`answers/20260403_140554_ANSWER_version_ghost_cleanup_manual_review.md`**).

### Full list (34 files)

```
lupo-docs/doctrine/4.0.21_COMPLETION_PLAN.md
lupo-docs/doctrine/ACTOR_AGENT_AUTH_USER_MODEL.md
lupo-docs/doctrine/AGENT_BOUNDARIES_COMPACT.md
lupo-docs/doctrine/AI_AGENT_BOOT_NOTES.md
lupo-docs/doctrine/ANUBIS_FALLBACK_DOCTRINE.md
lupo-docs/doctrine/ANUBIS_ORPHAN_RULES.md
lupo-docs/doctrine/BAN_REASONS.md
lupo-docs/doctrine/CLASS_CONVERSION_DOCTRINE.md
lupo-docs/doctrine/COMPATIBILITY_MATRIX.md
lupo-docs/doctrine/CRAFTY_SYNTAX_IMPORT_IMPLEMENTATION_CHECKLIST.md
lupo-docs/doctrine/CRAFTY_SYNTAX_MIGRATION_PROJECT_BRIEF.md
lupo-docs/doctrine/DEVELOPMENT_WORKFLOW_DOCTRINE.md
lupo-docs/doctrine/EDGE_MODEL_DOCTRINE.md
lupo-docs/doctrine/ETHICAL_STATE_MARKERS_DOCTRINE.md
lupo-docs/doctrine/FILESYSTEM_MIGRATION_GUIDE.md
lupo-docs/doctrine/HYBRID_ACTOR_DOCTRINE_4.0.29.md
lupo-docs/doctrine/INDEX.md
lupo-docs/doctrine/INSTALLATION_PATH_DOCTRINE.md
lupo-docs/doctrine/LEXA_GATEWAY_INTEGRATION.md
lupo-docs/doctrine/LUPOPEDIA_CANONICAL_DOCTRINE.md
lupo-docs/doctrine/LUPOPEDIA_DOCTRINE.md
lupo-docs/doctrine/MESSAGE_ATTRIBUTION.md
lupo-docs/doctrine/MIGRATION_DOCTRINE.md
lupo-docs/doctrine/MINIMAL_HOSTING_REQUIREMENTS.md
lupo-docs/doctrine/MigrationAtlas.md
lupo-docs/doctrine/PHP_COMPATIBILITY_AND_MINIMAL_HOSTING_DOCTRINE.md
lupo-docs/doctrine/PROJECT_REGISTRY_WORKFLOW.md
lupo-docs/doctrine/SCHEMA_CANONICAL_SOURCES.md
lupo-docs/doctrine/TABLE_COUNT_DOCTRINE.md
lupo-docs/doctrine/VERSIONING_DOCTRINE.md
lupo-docs/doctrine/VERSION_DOCTRINE.md
lupo-docs/doctrine/channels/filesystem_padding_layer.md
lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md
lupo-docs/prd/18_channel_chat_display.md
```

### Review guidelines

| Finding | Action |
|---------|--------|
| Phantom path (`/docs/`, legacy `docs/...` without `lupo-docs/`) | Point at **`lupo-docs/...`** or archive under **`lupo-docs/versions/`** |
| Phantom **`/database/migrations/`** (or pre-4.1.0 migration churn) | Align with **single install** doctrine — **no** Lupopedia→Lupopedia migration story until **4.1.0**; fix prose, do not invent ALTER chains |
| **`3.0.x`** reference (Crafty Syntax / external history) | Often **keep**; add explicit “historical / Crafty-era” context |
| **`3.0.x`** reference (reads like **current Lupopedia**) | Update to **4.0.x** or move to **`lupo-docs/versions/3.0.x/`** if the file is historical snapshot |
| File is entirely legacy structure description | Consider **`lupo-docs/versions/3.0.x/doctrine/`** (policy per file) |

---

## Priority 2 — PRDs deferred (documentation / product)

| PRD | Status | Next action |
|-----|--------|-------------|
| **PRD 34** — Federation semantic network | Deferred past “4.0.x stable” narrative | Draft exists as `lupo-docs/prd/34_federation_node_semantic_network.md` — expand when WOLFIE prioritizes |
| **PRD 35** — Mobile native app | Deferred past stable window | Draft: `lupo-docs/prd/35_mobile_native_app_separation.md` — aligns with Two-UI |

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
4. **Semantic “The Eye”** — product slice; align with **`MOOD_RGB_DOCTRINE.md`** / PRD 28 where applicable.
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

**Scripts:** `audit_doctrine_prd_edges.py`, `find_version_ghosts.py`, `find_edges.py`, `fix_doctrine_headers.py`, `apply_doctrine_prd_lineage.py`, `convert_wolfie_to_lupo.py` — under **`lupo-scripts/`**.

**Version docs:** Top of **`lupo-docs/versions/4.0.94/CHANGELOG.md`** for the latest **5W1H** session entries.

---

## Break reminder

Department-first actor model documentation is **APPROVED** (`decisions/20260403_222041_…`). **34** ghost files remain **intentionally** open for **manual** review. Doctrine PRD-edge coverage was **complete** at last audit (**189**/189 with lineage).

Rest is allowed. The repo keeps state.

---

**Next read:** [`TODO.md`](TODO.md), [`PLAN.md`](PLAN.md) Phase **H**, [`CHANGELOG.md`](CHANGELOG.md) (top entry), [`decisions/20260403_222041_DECISION_APPROVED_department_first_actor_model_prd_alignment.md`](decisions/20260403_222041_DECISION_APPROVED_department_first_actor_model_prd_alignment.md), [`version_ghosts_report.json`](../../implementations/29_project_structure/status/version_ghosts_report.json).

This output complies with Lupopedia Constitutional Root Rules.
