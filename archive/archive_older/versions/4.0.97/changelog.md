---
lupopedia.headers:
  header_format_version: 3
  lupopedia.schema: changelog
  when_updated: "20260410055241"
  file_path_from_root: "docs/versions/4.0.97/CHANGELOG.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.97/CHANGELOG.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "memory/development/canonical/1000/01/changelog-root.toon"
  artifact_type: changelog
  artifact_kind: version
  thread_id: "version-4-0-97-changelog"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# Changelog — Lupopedia 4.0.97

## [2026-04-08 19:08 UTC] - Version Consolidation

**WHO:** Cursor IDE Agent (actor_id **102**).

**WHAT:** Version-doc consolidation: established **`docs/versions/4.0.97/`** as the **non-session, non-ladder** working backlog. Populated **`TODO.md`** (29 tracked items), **`PLAN.md`** (phases A–L), **`README.md`**, **`edges.md`**, and **`decisions/` / `status/` / `notes/` `THREAD_INDEX.md`** files. Copied **5** status artifacts from **`4.0.96/status/`** into **`4.0.97/status/`** (PRD/install/memory-archive reviews — see **`status/THREAD_INDEX.md`**). Copied open federation **QUESTION** from **`4.0.94/questions/`** to **`4.0.97/notes/`** (see **`notes/THREAD_INDEX.md`**). **Session + ladder** artifacts remain indexed under **4.0.96** only.

**WHERE:** `docs/versions/4.0.97/*`; source status files under `docs/versions/4.0.96/status/`.

**WHY:** Prior placeholders incorrectly duplicated **session GC** work already completed in **4.0.96**; backlog needed a single home for remaining product/PRD tasks without moving ladder/session doctrine files.

**HOW:** Real UTC via **`python bin/tick.py`** → **`20260408190824`**.

---

## [2026-04-09 11:46 UTC] - Memory Compaction Initial Implementation

**WHO:** Claude Code (actor_id **116**).

**WHAT:** Memory compaction system — initial implementation. Built the scaffolding layer that sits on top of the existing `lupo_memory_nodes` database system.

**Files created:**

| File | Description |
|------|-------------|
| `scripts/generate_json_headers.py` | Generates `header_json_v1` companion JSON + `toon_v1` memory node from any PRD `.md` YAML frontmatter |
| `scripts/migrate_headers_to_json.py` | Same for arbitrary `.md` directories |
| `scripts/migrate_transcript_to_memory.py` | Reads `transcript.jsonl` → creates `.toon` per entry |
| `scripts/generate_prd_index.py` | Auto-generates `PRD_INDEX.md` from directory scan |
| `docs/doctrine/JSON_SCHEMA_DOCTRINE.md` | Schema registry for `header_json_v1` and `toon_v1` |
| `docs/headers/` (dir) | JSON header companions |
| `memory/2026/04/` (dir) | `.toon` memory nodes |
| `channels/0/development/prd_files/44_prd_discussion/config.json` | Channel config with `memory_follow_rules` |
| `CLAUDE.md` | Task management commands added |

**Files modified:** `bin/tick.py`, `bin/pending.py`, `bin/transcript.py` (Windows Unicode/emoji fixes)

**WHY:** WOLFIE directive — implement memory compaction layer.

**HOW:** UTC `20260409114635`. All files DRAFT. No version bump.

---

## [2026-04-09 12:00 UTC] - Session Identity - Filtered User Agent

**WHO:** Cursor (actor_id: 102)

**WHAT:** Implemented filtered User Agent normalization for session identity hashing

**WHERE:** `app/auth/Session.php`

**WHY:** Raw User Agent strings were unstable and error-prone (version churn, whitespace, hostile payloads)

**HOW:** Added normalization to alphanumeric-only, lowercase, truncated value before identity hash composition

**Details:**
- Added normalization path for UA handling in session identity flow
- Preserved backward compatibility for existing session records
- Reduced identity drift between browser patch versions

---

## [2026-04-09 12:30 UTC] - Memory Compaction - DB First with Fallback

**WHO:** Cursor (actor_id: 102)

**WHAT:** Implemented database-first memory writer with filesystem fallback option

**WHERE:** `scripts/lib/db_memory_writer.py`, `scripts/migrate_transcript_to_memory.py`

**WHY:** PRD 38 requires DB authority with filesystem mirror; fallback keeps work unblocked if DB is down

**HOW:** Added DB connectivity gating, fallback writer path, and no-fallback mode flag

**Details:**
- `DBMemoryWriter` attempts DB write as primary path
- Fallback writes filesystem `.toon` when DB unavailable (configurable)
- Added `--no-fallback` support in transcript migration script

---

## [2026-04-09 13:00 UTC] - Emoji Stripping for Machine Data

**WHO:** Cursor (actor_id: 102)

**WHAT:** Replaced emoji with ASCII tags in transcript/task machine-write paths

**WHERE:** `bin/transcript.py`, `bin/pending.py`, `scripts/lib/string_utils.py`

**WHY:** Emoji created cross-platform encoding failures (notably Windows cp1252 terminal paths)

**HOW:** Added centralized sanitization and applied it to write and output paths

**Details:**
- Introduced `sanitize_text()` utility for reusable normalization
- Mapped common symbols (`[OK]`, `[FAIL]`, `[TASK]`, etc.)
- Removed terminal crashes from unsanitized emoji output paths

---

## [2026-04-09 13:30 UTC] - LUPOPEDIA HEADERS v3 Documentation Migration

**WHO:** Cursor (actor_id: 102)

**WHAT:** Migrated LUPOPEDIA HEADERS doctrine docs to v3 minimal-pointer model

**WHERE:** `docs/doctrine/LUPOPEDIA_HEADERS/*`

**WHY:** v2 headers were oversized and difficult to maintain; v3 moves rich metadata into memory sidecars

**HOW:** Rewrote format/taxonomy/validators/migration docs and added memory schema spec

**Details:**
- Added `MEMORY_FILE_SCHEMA.md`
- Standardized v3 required fields and deprecation notes
- Documented v2 compatibility window and v1 rejection

---

## [2026-04-09 14:00 UTC] - Constitutional Rule Extension - No Emoji in Machine Data

**WHO:** Eric (actor_id: 1), Cursor (actor_id: 102)

**WHAT:** Established and enforced no-emoji policy for machine-readable transcript/task channels

**WHERE:** Constitutional and tooling flow (`docs/prd/00_root_constitutional_system_requirements.md`, validation/sanitization scripts)

**WHY:** Unicode emoji in machine data breaks deterministic parsing and multiplatform stability

**HOW:** Added doctrine language and wired sanitizer/validator behavior

**Details:**
- Documented policy in constitutional context
- Added validator/sanitizer enforcement path
- Standardized ASCII operational tokens

---

## [2026-04-09 14:00 UTC] - Header Validator Strict Versioning (v3)

**WHO:** Antigravity IDE (actor_id **103**)

**WHAT:** Updated Python header validators to strictly reject version 2 headers. Discovered a registry domain disconnect with channel keys.

**WHERE:** `scripts/validate_lupopedia_headers.py`, `scripts/validate_lupopedia_headers_universal.py`, `scripts/lib/header_validation.py`, `channels/registry.json`

**WHY:** To ensure absolute compliance with the new v3 sparse header + `.toon` sidecar memory layer, and to explicitly prevent legacy v2 files from slipping past validation

**HOW:** Blocked `version_int == 2` in the validators with explicit error: `"the file is not the right version and needs updating"`

**Details:**
- Modded 3 separate python validation scripts
- Discovered that migrated PRDs map to domains (`constitutional`, `actors`) not present in `registry.json`
- Logged registry disconnect for architectural decision-making

---

## [2026-04-09 15:00 UTC] - Channel Key Migration and Trust-Tier Memory Paths

**WHO:** Cursor (actor_id: 102)

**WHAT:** Replaced numeric channel identity with `channel_key`; added trust-tier memory path rules

**WHERE:** header docs, migration tooling, and `channels/registry.json`

**WHY:** Numeric channel IDs were opaque; trust-tier pathing required explicit channel/tier hierarchy

**HOW:** Introduced structured channel registry and updated migration logic for tier-aware memory keys

**Details:**
- `channel_id` → `channel_key` in v3 header model
- Added `trust_tier` and tier path conventions
- Canonical path year transform documented (`actual_year - 1000`)

---

## [2026-04-09 16:00 UTC] - Top 12 PRDs Migrated to v3 Headers

**WHO:** Cursor (actor_id: 102)

**WHAT:** Migrated critical PRDs to v3 minimal headers and created corresponding `.toon` memory metadata files

**WHERE:** `docs/prd/` (00, 15, 16, 17, 33, 36, 37, 38, 41, 42, 43, 44) and `memory/{channel_key}/...`

**WHY:** Make core PRDs consistent with v3 header architecture and memory-sidecar metadata model

**HOW:** Batch migration script + validator update for v3 schema acceptance

**Details:**
- Preserved purpose/status/tags/edges/footer metadata in sidecar `.toon` files
- Kept PRD body content intact while replacing frontmatter
- Updated `validate_lupopedia_headers.py` to accept v3 minimal headers

---

## [2026-04-09 15:36 UTC] - PRD Header Memory JSON Write Clarification

**WHO:** Cursor (actor_id: 102)

**WHAT:** Updated PRD documentation to explicitly define how LUPOPEDIA header metadata is written to JSON memory sidecars and linked via `memory_key`

**WHERE:** `docs/prd/16_lupopedia_headers.md`, `docs/prd/38_memory_unification.md`

**WHY:** Header architecture moved rich metadata out of YAML into `.toon` JSON files, but the PRD layer needed explicit write-path and sidecar semantics documented

**HOW:** Added dedicated sections describing v3 pointer-header model, sidecar JSON required fields, and DB-first write then filesystem mirror behavior

**Details:**
- PRD 16 now includes a canonical "Memory Sidecar JSON Write Contract" section
- PRD 38 now explicitly states header-associated memory files are JSON sidecars (`.toon`)
- Reinforced machine-readable JSON requirement for memory metadata files

---

## [2026-04-09 15:56 UTC] - PRD 16 Fixed 19-Field Header Spec Lock-In

**WHO:** Cursor (actor_id: 102)

**WHAT:** Finalized PRD 16 to the fixed v3 header model (19 fields max) and aligned sidecar JSON contract language

**WHERE:** `docs/prd/16_lupopedia_headers.md`

**WHY:** Remove ambiguity and header drift by defining a strict fixed field set and moving rich metadata fully to sidecar JSON

**HOW:** Replaced mixed v2/v3 field guidance with a fixed 19-field table, updated v3.1 fixed 22-line layout text, and removed legacy header-field guidance (`channel_id`, `content_id`, `context_id`, and header-level actor identity fields)

**Details:**
- Added explicit v3 fixed field set and conditional rules
- Updated sidecar contract with required JSON fields (`author`, `delegation_chain`, `edges`, `footer`, `init`)
- Updated discussion-type conditional fields to `channel_key` + `thread_id`

---

## [2026-04-09 20:59 UTC] - Semantic UI: Add Shortcut (Collections Pin), Eye Context, Book/Scroll Shell

**WHO:** Cursor IDE Agent (actor_id **102**), implementing WOLFIE-facing **semantic chrome** requests (shortcut pin, Eye behavior, visitor shell toggle). No other IDE faucets authored this batch.

**WHAT:** Delivered end-to-end **“Add shortcut”** flow into **`lupo_collection_tab_map`** (not a new `lupo_collection_items` table), **Eye / monitor** behavior keyed off **`artifact_type`**, **visitor book vs scroll** 9-slice shell from **Master Settings**, and a **dynamic contextual shortcut menu** fed by the same tabs JSON as the blue collections bar.

**WHERE (paths):**

| Area | Files |
|------|--------|
| API | `api/add_to_collection.php` — POST pin; **`IdGenerator`** PK; accepts **`content_id`** / **`id`**, **`content_slug`** (resolve to id), **`collection_tab_id`** / **`tab_id`**, **`collection_id`**, optional **`title`** → JSON in **`properties`** |
| API alias | `api/get_actor_tabs.php` — thin **`require`** of **`load_collection_tabs.php`** (canonical tabs JSON) |
| Tabs data | `database/lupopedia/content/app/Services/CollectionTabsService.php` — **`_collection_tab_id`**, **`_children`** `{ name, slug, collection_tab_id }` |
| Loader | `includes/functions/collection-tabs-loader.php` — **`lupo_get_public_content_shell()`** reads **`lupo_settings.public_content_shell`** |
| Layout / shell | `includes/themes/default/layouts/main_layout.php` — **`#shortcut-tabs-dynamic`**, **`lupoOpenShortcutDropdown`**, dual inline CSS for **`s*a` / `s*b`**, **`data-edge-focus`**, hidden **`#current-content-id`**, **`LUPO_MAIN_LAYOUT.contentId`** |
| CSS | `includes/css/main-layout.css` — **`body.scroll-layout`** shares grid with book |
| JS collections | `includes/js/main-layout-collections.js` — **`get_current_page_context()`**, **`lupoRefreshShortcutDropdown`**, **`lupoSyncShortcutTabsFromServerData`**, **`lupoBuildShortcutTabsListHtml`**, **`lupoAddCurrentPageToCollectionTab`** (slug + title payload), **`loadCollectionTabs`** syncs **`#active-collection-id`** + shortcut panel |
| JS monitor / Eye | `includes/js/monitor.js` — **`semantic_edge_focus`** / **`semantic_artifact_type`** on track body; command bar passes **`edge_focus`**; **`window.LUPO_SEMANTIC_EDGE_FOCUS`** |
| JS Eye sprites | `includes/js/crafty_syntax_eyes.js` — tutorial vs transcript **pupil speed** + **blink** timing from DOM |
| Admin | `includes/classes/AdminSettingsHandler.php` — **`public_content_shell`** select (**book** / **scroll**) |
| Legacy component | `includes/themes/default/components/collection_tabs.php` — pin markup aligned with main layout |

**WHEN:** **2026-04-09 20:59:48 UTC** (timestamp from **`python bin/tick.py`** → **`20260409205948`**).

**WHY:** Bridge **URL/page context** with the **collections database** so the plus icon is a real **semantic knowledge graph** entry point (tab map), align **PRD 28** visitor chrome with **`help_guide` vs `text/markdown`**, and let operators switch **tile set** without code edits.

**HOW:** **PDO_DB** + **`DatabaseFactory`** on the API; **no new tables**; **soft-delete revive** on map rows; client **refreshes `#shortcut-tabs-dynamic`** on dropdown open via **`load_collection_tabs.php`**; pin uses **hidden content id** when present, else **query-string `slug`** resolved server-side; shell preference persisted in **`lupo_settings`** and applied as **`body.book-layout` vs `scroll-layout`**.

---

## [2026-04-09 23:38 UTC] - Collections try2 bar: AJAX `loadCollectionTabs` parity + master-dropdown helpers (content routes)

**WHO:** **Cursor IDE Agent** (**actor_id 102**), lead orchestration facet, on behalf of the human orchestrator (WOLFIE workspace). No other faucets committed this batch.

**WHAT:** Closed the gap between **standalone collections debug pages** and **real pages wrapped in `main_layout.php`** (including **`content/index.php`** → **`content-controller.php`** → **`render_main_layout()`**): the **try2** collections strip (`.dropdown`, **`lupoDbgNavToggle`**, `.dropdown-panel`, `.menu-item`) now stays consistent after **client-side tab reload** and legacy **master** helpers no longer target only pre-try2 class names.

**WHERE (paths):**

| Area | Files |
|------|--------|
| **AJAX tab HTML** | **`includes/js/main-layout-collections.js`** — **`loadCollectionTabs`** rebuilds **`#collection-tabs-container`** with the same structure/classes as **`saved-collections-nav-try2.php`** (green buttons, **`lupoDbgNavToggle(this, event)`**, **`.count-badge`**, sub-links as **`.menu-item`**) instead of legacy **`saved-collections-dropdown`** / **`toggleSavedCollectionsDropdown`** markup. |
| **Master list / close helpers** | Same file — **`lupoMasterPanelLooksEmpty`** treats **`.menu-item`** as non-empty; **`lupoMasterCollectionsHydrateIfEmpty`** loading/error/row markup uses **`.menu-item`**; row **`onclick`** prefers **`lupoDbgNavCloseAll()`** when defined, else **`lupoCloseMasterCollectionsDropdown()`**; **`lupoCloseMasterCollectionsDropdown`** resolves **`.dropdown-panel`** / **`.dropdown-button`** as well as legacy **`.saved-collections-*`** selectors. |
| **Already in tree (context)** | **`includes/themes/default/layouts/main_layout.php`** includes **`saved-collections-nav-try2.php`** + loads **`saved-collections-nav-try2.js`** after **`main-layout-collections.js`**; **`includes/js/main-layout.js`** skips first-load **`loadCollectionTabs`** when **`#collection-tabs-container`** already contains **`.dropdown`**. |

**WHEN:** **2026-04-09 23:38:04 UTC** (packed **`20260409233804`** from **`python bin/tick.py`** at documentation time).

**WHY:** **Debug try2** looked correct because PHP rendered try2 markup, but **any path that replaced tabs via `loadCollectionTabs`** (e.g. choosing a collection from the master list) injected **old** dropdown HTML, breaking CSS/JS expectations on **content** and other **`main_layout`** routes. **Symptom:** “works on debug, wrong on `content/index.php`.” **Root cause:** **server vs client DOM contract drift.**

**HOW:** **Single DOM contract:** string-built HTML in **`loadCollectionTabs`** was rewritten to match the try2 partial. **Defensive alignment:** master hydrate/close paths updated so mixed or legacy callers do not leave the blue **Collections** control in an inconsistent open state. **Verification:** manual: open **`content/index.php`** (or slug under content), open master, load a collection, confirm green tab dropdowns still toggle and link to **`/collection/{id}/tab/...`** with correct styling.

**Troubleshooting note (environment):** On **Windows PowerShell**, **`&&`** between commands failed; use **`;`** or separate invocations when chaining **`cd`** and **`python`**.

---

## [2026-04-10 05:48 UTC] - PRD 16 / validators: remove obsolete `version` from `lupopedia.headers` (HDR_VERSION_FIELD_REMOVED)

**WHO:** Cursor IDE Agent (**actor_id 102**).

**WHAT:** Completed **Option 1** — the per-file **`version`** key is no longer part of the v3 header schema. **`header_format_version`** remains the only format version; document and product versioning stay in git, **`CHANGELOG.md`**, titles, and sidecar freshness. **`validate_lupopedia_headers_universal.py`**: dropped **`version`** from **`DETERMINISTIC_FIELD_ORDER_V3`**, implementation required keys no longer list **`version`**, v3 validation **errors** if **`version`** is present. **`LUPOPEDIA_HEADERS_FORMAT.md`**: documents removal; **`16_lupopedia_headers.md`**: flat-header rejection text and §12.1 validator bullet updated.

**WHEN:** Packed UTC **`20260410054849`** ( **`python bin/tick.py`** batch).

---

## [2026-04-10 05:52 UTC] - Install SQL: `thread_key` + UNIQUE on dialog threads; `channel_key` on dialog messages; edge_direction doc alignment

**WHO:** Cursor IDE Agent (**actor_id 102**).

**WHAT:** **`install_new_lupopedia.sql`**: **`lupo_dialog_threads.thread_key`** (`varchar(255)` nullable) and **`UNIQUE`** index **`(federation_node_id, channel_id, thread_key)`**; **`lupo_dialog_messages.channel_key`** (`varchar(255) NOT NULL DEFAULT ''`) with index. **TOON** JSON for both tables updated. **`DialogMvpService::createDialogThread`** sets **`thread_key`** via **`threadKeyForNewThread()`**; **`channel-send-api.php`** copies **`channel_key`** from **`lupo_channels`** on send; **`import_filesystem_channels_to_db.py`** imports **`thread_key`**. **PRD 16** §11.1 reconciles **`edge_direction`** vocabulary with **`lupo_memory_edges`** (**`unidirectional`** / **`bidirectional`**). Legacy migration **`add_thread_key_to_dialog_threads.sql`** noted as superseded for fresh installs. Table docs **`lupo_dialog_threads.md`**, **`lupo_dialog_messages.md`** updated.

**WHEN:** Packed UTC **`20260410055241`**.

---

## [2026-04-10 06:28 UTC] - PRD 16 session close-out: §9, §12, §14, §15 delivery + header version-family policy

**WHO:** Cursor IDE Agent (**actor_id 102**) in direct implementation loop with WOLFIE guidance.

**WHAT:** Completed the requested PRD 16 execution set for this session:  
- **§9 transcript path**: implemented DB-first transcript append API route and service wiring, plus `transcript.py` DB-first POST mode with offline queue flush.  
- **§12 validator parity**: aligned universal and library validators to one ruleset; enforced extra-key rejection, scalar-only v3 headers, thread-id policy by artifact type, and schema/type cross-field constraints.  
- **§15 E2E gate tooling**: added `e2e_prd16_gates.py` for JSONL/offline-queue delta assertions and sidecar tmp-scan support.  
- **§14 version hygiene**: migrated active backlog focus into `docs/versions/4.0.98/`, archived `4.0.97/README.md`, and updated root plan/TODO pointers.  
- **Header version policy**: changed `header_format_version` validation from integer gate to **`4.0.x` family** (`^4\.0\.\d+$`), updated PRD 16 wording, and added doctrine note `docs/doctrine/HEADER_VERSIONING.md`.

**WHERE (paths):**
- PHP/API/runtime: `includes/classes/TranscriptAppendService.php`, `includes/modules/api/transcript-api.php`, `includes/modules/module-loader.php`, `index.php`, `lupopedia-config-sample.php`
- Python/validators/tools: `bin/transcript.py`, `scripts/validate_lupopedia_headers_universal.py`, `scripts/lib/header_validation.py`, `scripts/lib/header_spec_v3_1.py`, `scripts/e2e_prd16_gates.py`
- Docs/versioning: `docs/prd/16_lupopedia_headers.md`, `docs/doctrine/HEADER_VERSIONING.md`, `docs/versions/4.0.97/README.md`, `docs/versions/4.0.98/README.md`, `docs/versions/4.0.98/CHANGELOG.md`, root `plan.md`, root `TODO.md`

**WHEN:** **2026-04-10 06:28:12 UTC** (packed UTC `20260410062812` from `python bin/tick.py`).

**WHY:** Close PRD 16 release blockers with one coherent behavior across PHP + Python + docs: eliminate split writer paths, make validator behavior deterministic, formalize 4.0.x header-version family policy, and complete version-folder migration hygiene before tag decisions.

**HOW:** Iterative implementation with parity checks and syntax validation (`php -l`, `py_compile`, validator dry runs), then doc normalization and changelog/status capture. Kept git tag/bump deferred behind explicit WOLFIE web verification gate.

---

This output complies with Lupopedia Constitutional Root Rules.