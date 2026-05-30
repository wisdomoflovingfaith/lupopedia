---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/4.0.99/changelog.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.0.99/changelog.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: changelog
  artifact_kind: version_specific
  channel_key: development
  federation_node_id: 0
  thread_key: version-4-0-99-changelog
  lupopedia.schema: changelog
  prd_cluster: null
  title: Changelog 4.0.99
  summary: Complete session log for 4.0.99 header standardization and constitutional limits
---
# Changelog — Lupopedia 4.0.99

## Changelog Rules (READ THIS FIRST)

- **ALWAYS append new entries to the BOTTOM of this file.**
- **Do NOT** try to maintain reverse chronological order (newest at top).
- **Do NOT** insert entries in the middle.
- **Oldest at top, newest at bottom.**
- **Use `tac CHANGELOG.md` to read newest first** if you need reverse order.
- Each entry title: `## [YYYY-MM-DD HH:MM UTC] — Brief Title`
- Each entry includes: **WHO**, **WHAT**, **WHERE**, **WHEN** (packed UTC + wall time), **WHY**, **HOW**

---

## [2026-04-12 ~00:10 UTC] PRD 38 §3.0.1 webroot doctrine; WOLFIE memory↔contents mapping → §3.0.2

**WHAT:**

- **PRD 38** [`38_memory_unification.md`](../../prd/38_memory_unification.md): new **§3.0.1** *Webroot exposure and on-disk mirrors*; former **§3.0.1** (*memory_nodes* ↔ *lupo_contents*) renumbered to **§3.0.2**.
- Cross-references updated: **PRD 16**, **PRD 50** §4.17.3 table, **`lupopedia_quick_reference.md`**, **BREAKTHROUGH_REGISTRY** (PRD 38 bullet).
- **PRD 50** §1.1, **PRD 51** §3.4, **PRD 52** §8: explicit webroot / script-exposure alignment with **`lupopedia_quick_reference.md`**.
- **`CLAUDE.md`**: Layer 2 bullet — webroot readability, secrets boundary, non-PHP scripts.

**WHY:** WOLFIE clarification (2026-04-11): document **visibility default**, **`lupopedia-config.php`** as sole secrets surface, and **execution vs HTTP access** for non-PHP tooling — no framework fantasy layer.

**WHEN:** Header/footer batch UTC **`20260412001055`** (`python bin/tick.py`).

---

## [2026-04-12 02:32 UTC] — KAIROS graph classification; Pattern #6 hook; normalize KAIROS UX; AGENTS.md v4.0.99 header

**WHO:** Cursor IDE Agent (**actor_id** **102**, lead orchestration facet). **Orchestrator context:** WOLFIE (**1**) / **junie:root** as referenced in repo identity lines where applicable.

**WHAT:**

1. **KAIROS (`verify_edges_for_file`)** — **`scripts/lib/kairos_edge_verification.py`**: After Markdown migration, resolves **`lupo_memory_nodes`** by optional **`memory_node_id`**, else header **`content_id`**, else **`memory_key`**; counts outgoing edges by **`edge_type`** (distinct **`outgoing_edge_types`**), counts incoming; classifies **`node_status`**: **`complete`**, **`isolated`** (zero outgoing), **`incomplete`** (optional **`expected_edge_types`** not all present, case-insensitive), **`missing`**, **`deleted_only`** (soft-deleted row). CLI **`--test`** gains **`--expected-edge-types`** (comma-separated). Exit **1** only for **`missing`** / **`deleted_only`**; **`isolated`** / **`incomplete`** exit **0** with **stderr** warnings on **`--test`**.
2. **Pattern #6 integration** — **`scripts/detect_memory_graph_orphans.py`**: After header-vs-DB **`ok`**, calls **`verify_edges_for_file`**; attaches **`kairos`** dict per file row; **`missing`** / **`deleted_only`** from KAIROS set **`actionable`** (exit **1**); **`isolated`** / **`incomplete`** log **`[WARN]`** to **stderr** only; human output lists **`[kairos_*]`** lines for KAIROS-hard failures. Docstring and notes updated (three tracks; exit semantics).
3. **Normalize post-verify** — **`scripts/normalize_lupopedia_md_header_25.py`**: **`_kairos_verify_after_normalize`** branches on **`node_status`** so **`missing`**/**`deleted_only`** vs **`isolated`**/**`incomplete`** vs clean paths have appropriate **`[KAIROS]`** messaging.
4. **AGENTS.md** — Replaced obsolete v2/v3-style front matter ( **`channel_id`**, actor fields in header, **`lupopedia.edges`** / **`lupopedia.footer`** in-file) with **PRD 16 v4.0.99** dense **22-key** envelope; **`https://`** **`web_path`** with **`/lupopedia/`**; **`channel_key`**, **`trust_tier`**, **`memory_key`**, **`dialog_transcript`**; edges/footer deferred to sidecar doctrine. Body unchanged except identity line **https** links.

**WHERE:**

- `scripts/lib/kairos_edge_verification.py`
- `scripts/detect_memory_graph_orphans.py`
- `scripts/normalize_lupopedia_md_header_25.py`
- `AGENTS.md`
- `docs/versions/4.0.99/CHANGELOG.md` (this entry)
- `docs/versions/4.0.99/status/session_report_20260412_eod_kairos_pattern6_agents_cursor102.md` (session / EOD report)

**WHY:**

- Turn KAIROS from a coarse “node exists?” check into a **graph validator** (outbound edge health + optional expected types) for **needs_review** / operator workflows without treating **isolated**/**incomplete** as hard CI failures.
- Unify **Pattern #6** (header **`memory_key`** vs DB) with **edge reality** when the DB says the node is active.
- Align **AGENTS.md** with **v4.0.99** header law (dense envelope, sidecar for edges/footer) so new agents see the correct shape at the repo entrypoint.

**WHEN:** Packed UTC **`20260412023225`** — wall clock **2026-04-12 02:32:25** UTC (`python bin/tick.py` for this documentation batch).

**HOW:**

- **SQL:** Portable **`GROUP BY edge_type`** on **`lupo_memory_edges`** for **`from_memory_node_id`**, **`is_deleted = 0`**; separate **`COUNT(*)`** for incoming **`to_memory_node_id`**.
- **Imports:** KAIROS continues lazy import of **`detect_memory_graph_orphans._extract_memory_key_from_md`** inside **`verify_edges_for_file`** to limit circular import risk; orphan detector imports **`verify_edges_for_file`** only after DB connection succeeds.
- **Validation:** `python scripts/validate_lupopedia_headers_universal.py` on touched Python modules and **`AGENTS.md`** (**PASS** without **`--strict-memory-pair`** for **`AGENTS.md`** — sidecar **`.toon`** at **`memory_key`** may still be created in a follow-up for strict pairing).

---

## [2026-04-12 13:48 UTC] — PRD number hygiene: core **01–49** vs secondary **70–99**; Database Design Doctrine → **PRD 80**

**WHO:** Cursor IDE Agent (**actor_id** **102**, lead orchestration facet). **Orchestrator:** WOLFIE (**1**) / workspace owner (**junie:root** where repo identity lines apply).

**WHAT:**

1. **Resolved duplicate PRD numbers** (two files sharing **02**, **03**, **04**, **05**, **08**, **15**, **20**, **21**, **24**, **41**) by reserving **01–49** for **core** specs and moving **secondary** specs to **70–79** per the renumbering map (e.g. **`02_data_model.md` → `70_data_model.md`**, **`41_install_seed_doctrine.md` → `79_install_seed_doctrine.md`**).
2. **Freed `PRD 70` for Data Model** by relocating the existing **Database Design Doctrine** from **`70_database_design_doctrine.md`** to **`80_database_design_doctrine.md`** (**`pk_id` 80**, new **`memory_key`** **`…/1026/04/80-database-design-doctrine.toon`**); **PRD 00** §3 “full specification” link now targets **`80_database_design_doctrine.md`**.
3. **Filesystem + memory alignment:** Renamed canonical **JSON/TOON** pairs under **`memory/development/canonical/2026/04/`** (e.g. **`02-data-model` → `70-data-model`**), renamed **`memory/headers/prd/2026/04/*.metadata.json`**, **`memory/install/seed/41-install-seed-doctrine.toon` → `79-install-seed-doctrine.toon`**, updated **`1026/04`** sidecars for database doctrine; bulk **`docs/prd/*.md`** path replacements across the tree where safe.
4. **Index + tooling:** Regenerated **`docs/prd/PRD_INDEX.md`** via **`generate_prd_index.py`**, restored **PRD 16**-dense front matter, fixed **HDR_EMPTY_BODY** (no blank-only line immediately after closing `---`); patched generator to emit a single newline after the header fence; updated **`README.md`**, **`07_agents_faucets.md`**, **`docs/prd/decisions/pseudocode/THREAD_INDEX.md`**, constitution **`.pseudo.md`** filenames (→ **`70_*` … `79_*`**) + YAML paths; **`migrate_top_prds_v3.py`**, **`generate_phase2_prd_memory_json.py`** filename lists.

**WHERE:** `docs/prd/` (renamed **11** PRD files + **PRD_INDEX**), `memory/development/canonical/2026/04/`, `memory/headers/prd/2026/04/`, `memory/install/seed/`, `memory/development/canonical/1026/04/`, `docs/prd/decisions/pseudocode/`, `README.md`, `scripts/generate_prd_index.py`, `scripts/migrate_top_prds_v3.py`, `scripts/generate_phase2_prd_memory_json.py`, `docs/versions/4.0.99/CHANGELOG.md` (this entry), `docs/versions/4.0.99/status/session_report_20260412_eod_prd_renumber_cursor102.md`.

**WHY:** Agents could not resolve “see **PRD 02**” (channels vs data model). **Policy:** core band **01–49**; secondary **70–99**. **Database doctrine** could not share **70** with **data model** without collision — **doctrine → 80** preserves both documents.

**WHEN:** Packed UTC **`20260412134809`** — wall clock **2026-04-12 13:48:09** UTC (`python bin/tick.py` from repo root for this documentation batch).

**HOW:**

- **Renames:** PowerShell **`Move-Item`** / filesystem renames where **`git mv`** failed (e.g. previously **untracked** **`70_database_design_doctrine.md`**); then **`git mv`**-style renames for the ten secondary PRDs where git tracked them.
- **Memory rows:** Python one-shot patched **`metadata[].prd_id`** / **`source_path`** in JSON and synced the **TOON** metadata line; bulk **`str.replace`** walk for **`docs/prd/<old>.md` → `<new>.md`** (scoped extensions).
- **Headers:** Scripted YAML updates (**`pk_id`**, **`pk_slug`**, **`file_path_from_root`**, **`web_path`**, **`https`**, **`memory_key`**, **`when_updated` / `last_modified_utc`**) on moved PRDs and **PRD 80** body (**PRD 79** install-seed cross-refs where applicable).
- **Validation:** **`validate_lupopedia_headers_universal.py`** on **`PRD_INDEX.md`**, **`70_data_model.md`**, **`80_database_design_doctrine.md`**, **`79_install_seed_doctrine.md`** — **PASS** with existing **WARN**s (**`memory_key`** year **2026** vs **1026** recommendation; some **`dialog_transcript`** four-segment paths).

---

## [2026-04-12 14:23 UTC] — Collections Architecture Clarification & Critical Implementation

**WHO:** Cascade AI Agent (actor_id **N/A**).

**WHAT:**
- **Clarified collections architecture** distinction between human UI collections (database tables) and AI memory collections (memory edges)
- **Updated PRD 72 & 73** with comprehensive sync strategy for bidirectional human-AI collaboration
- **Completed PRD 43** trust ladder implementation with 5 edge predicates and trust weight quantification
- **Fixed critical header violations** (empty summaries, null modules) in key PRDs
- **Standardized memory key patterns** (1026/04 for core system, 2026/04 for general PRDs)
- **Implemented MemoryExportService** for database → filesystem TOON export
- **Created CollectionMemoryService** for graph traversal and bidirectional sync
- **Documented complete integration strategy** for memory, collections, trust ladder, and headers

**WHERE:**
- **PRD Updates:** `docs/prd/72_tags_metadata.md`, `docs/prd/73_collections_navigation.md`, `docs/prd/43_parent_child_trust_ladder.md`, `docs/prd/38_memory_unification.md`, `docs/prd/51_memory_graph_as_source_of_truth.md`
- **New Services:** `app/Services/MemoryExportService.php`, `app/Services/CollectionMemoryService.php`
- **Documentation:** `docs/versions/4.0.99/CHANGELOG.md`

**WHY:**
- **Resolve architectural confusion** between human UI collections and AI memory collections
- **Implement missing trust ladder** (PRD 43 was only 41 lines, no concrete implementation)
- **Fix constitutional violations** (80+ PRDs with empty summaries violating PRD 16)
- **Enable bidirectional sync** between human curation and AI discovery
- **Establish memory graph as unifying fabric** connecting all systems
- **Provide concrete implementation path** for collections integration

**WHEN:** Packed UTC **`20260412142346`** (2026-04-12 14:23:46 UTC).

**HOW:**
- **Architecture Analysis:** Conducted critical review of memory system, collections, trust ladder, and headers integration
- **PRD 43 Completion:** Added 5 trust edge predicates (`trusts`, `delegates_to`, `parent_channel`, `memory_scope_inherits`, `has_access_to`) with weight quantification (0.0-1.0) and query patterns
- **Collections Distinction:** Clarified human UI collections use 6 existing tables vs AI collections use `lupo_memory_edges` with specific predicates
- **Sync Strategy:** Implemented 4-phase bidirectional sync (Phase 1: Human→AI edges, Phase 2: AI discovery, Phase 3: Approval workflow, Phase 4: Advanced features)
- **Memory Export Service:** Created full export service with batch processing, incremental updates, TOON format compliance, and validation methods
- **Collection Memory Service:** Built service for creating memory nodes, managing hierarchy, syncing items, and enabling graph traversal
- **Header Fixes:** Corrected empty summaries and null modules in critical PRDs (00, 43, 72, 73)
- **Memory Key Standardization:** Established 1026/04 pattern for core system PRDs, 2026/04 for general PRDs

---

## [4.0.99] - 2026-04-12 18:00 UTC (Session Final)

### WHO
- **Actor:** WOLFIE (actor_id: 1) / Eric Gerdes
- **Role:** Constitutional authority, system architect, lead developer
- **Facilitated by:** Cursor IDE (primary), Claude (adversarial review), VS Code (fallback)
- **Session type:** Header standardization, validator creation, constitutional limits documentation

### WHAT

**Files Created:**
- `scripts/generate_memory_from_header.py` - Memory sidecar generator (.json + .toon)
- `scripts/validate_table_count.php` - Database table count validator (<=199)
- `scripts/validate_actor_id.php` - Seeded actor validator (1-999)
- `scripts/validate_trust_ladder_pk.php` - PK year offset validator
- `scripts/backfill_canonical_offsets.php` - Staging→canonical PK migration
- `scripts/check_limit_utilization.php` - Constitutional limits dashboard
- `includes/classes/TrustLadderQueryHelper.php` - PK band abstraction trait
- `docs/prd/99_limits_for_everything_and_why.md` - Constitutional limits PRD

**Files Updated (v4.0.99 header standardization):**
- `scripts/validate_lupopedia_headers_universal.py` - Added `--strict-memory-files`
- `scripts/add_lupopedia_header_to_file.py` - Added `--skip-memory-sidecar`, `--force-memory`
- `scripts/add_lupopedia_headers_everywhere.py` - Batch header adder

**Documentation Updated:**
- `docs/prd/16_lupopedia_headers.md` - v4.0.99 dense envelope, PK fields, orphan doctrine
- `docs/prd/99_limits_for_everything_and_why.md` - Complete constitutional limits
- `docs/doctrine/TRUST_LADDER_REGISTRY.md` - PK offset validation section
- `docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md` - §2.2 PK offset rule
- `docs/prd/PRD_INDEX.md` - Regenerated (65 PRDs indexed)

---

## [4.0.99] - 2026-04-12 17:00 UTC

### WHO
- **Actor:** WOLFIE (actor_id: 1)
- **Role:** Constitutional authority, system architect
- **Facilitated by:** Cursor IDE (agent session)

---

## [20260413200000] - Actor 116 (Claude Code)

WHEN: 20260413200000
WHO: Actor 116 — Claude Code (actor_id: 116)
WHAT:
- SQUASH SQLSTATE[HY093]: Rewrote DialogMvpService::createDialogMessage() with strict schema-aligned insert array.
- Rewrote createDialogThread() with same IdGenerator + NULL-correct pattern.
- Added DialogMvpService::THOTH_ACTOR_ID=26 and ROSE_ACTOR_ID=39 class constants.
- READ LOY: added DialogMvpService::updateReadLog() — tracks last_read_message_id per (actor, channel, thread).
- CHANNEL MEMBERSHIP MODEL: right panel now shows ONLY explicit channel members.
- AUTO-JOIN: added DialogMvpService::ensureChannelMembership().
- CHANNEL DROPDOWN: sidebar now loads all non-deleted active channels.

---

## [2026-04-13 23:55 UTC] — GEMINI.md Creation & Channel UI Finalization

### WHO
- **Actor:** GEMINI (actor_id: 111)
- **Role:** AI Agent / Developer
- **Facilitated by:** Gemini CLI

### WHAT
- **Created GEMINI.md**: Synthesized system-wide operating contract.
- **Fixed channels/index.php**:
  - Channel dropdown now shows all available active channels.
  - Implemented auto-join logic.
  - Members sidebar is now strictly channel-scoped.

---

## [2026-04-14 00:30 UTC] — Session Finalization: Gemini Contract & Backlog Update

### WHO
- **Actor:** GEMINI (actor_id: 111)
- **Role:** AI Agent / Developer
- **Facilitated by:** Gemini CLI

### WHAT
- **Delta Analysis**: Created what_gemini_learned_from_crafty_syntax_that_claude_did_not.md.
- **Session Reporting**: Created status/session_report_20260413_gemini_contract_channels_cursor.md.
- **Backlog Hygiene**: Updated TODO.md and PLAN.md.

---

## [2026-04-14 01:00 UTC] — Session Debug Pages for Localhost Diagnosis

### WHO
- **Actor:** GEMINI (actor_id: 111)

### WHAT
- **Created debug_login.php** and **debug_loggedin.php**.

---

## [2026-04-14 01:15 UTC] — Enhanced Session Debugging: Multi-Key Lookup

### WHO
- **Actor:** GEMINI (actor_id: 111)

### WHAT
- **Updated debug_loggedin.php** with multi-key lookup strategy.

---

## [2026-04-14 12:00 UTC] Root Philosophy Alignment — README_WTF + PRD 02 + Doctrine + .toon

### WHO
- **Actor:** Claude Code (actor_id: 116)

### WHAT
Full root philosophy alignment pass.

---

## [2026-04-14 12:00 UTC] Gemini Delta Consolidated into Claude Master Analysis

### WHO
- **Actor:** Claude Code (actor_id: 116)

### WHAT
Consolidated all valid insights from Gemini's delta analysis into the existing document.

---

## [2026-04-14 12:00 UTC] Command Center Implementation — channels/index.php Full Overhaul

### WHO
- **Actor:** Claude Code (actor_id: 116)

### WHAT
Applied all accumulated doctrine to make channels/index.php function correctly.

---

## [2026-04-14 12:00 UTC] PRD Doctrine Refinement from Blog Context (AI Kindergarten)

### WHO
- **Actor:** Claude Code (actor_id: 116)

### WHAT
Read blog entry to identify where existing PRD/doctrine files lacked the WHY behind design decisions.

---

## [2026-04-14 16:00 UTC] -- Database Schema Constitutional Audit + Install SQL Repair
**Agent:** Claude Code (Actor 116)

---

## [2026-04-14 21:15 UTC] -- PRD 02 Task Manager Update + Missing Table Investigation
**Agent:** Auggie (Augment Agent)

---

## [2026-04-14 17:40 UTC] — Task Management System & Schema Alignment

### WHO
Agent: Gemini
Role: AI Developer / Researcher
Human orchestrator: Wolfie (actor_id 1)

### WHAT
- **Research & Context Building**: Read 22 PRDs and foundational readmes.
- **Database Table Report**: Created `docs/versions/4.0.99/status/gemni_database_table_report.md`.
- **Install SQL Alignment**: Updated `database/lupopedia/mysql/install/install_new_lupopedia.sql` to match live schema:
  - `lupo_votes`: Updated to polymorphic engagement schema.
  - `lupo_memory_nodes`: Added `embedding_vector` and `has_vector_index`.
  - `lupo_paths_daily`, `lupo_referers_daily`: Added `hit_count` and `unique_actors`.
- **Task Management System**:
  - `TaskService.php`: Created/updated central task service with DB + Filesystem sync.
  - API Endpoints: Created `create.php`, `update.php`, `list.php`, `next.php` in `api/v1/tasks/`.
  - `ActorLookup.php`: Added `findIdByName`.
  - Chat Integration: Added `[task]` command parsing.
  - Sidebar UI: Added task list to sidebar in `channels/index.php`.

### WHERE
- `docs/versions/4.0.99/status/gemni_database_table_report.md`
- `database/lupopedia/mysql/install/install_new_lupopedia.sql`
- `includes/classes/TaskService.php`
- `api/v1/tasks/`
- `channels/index.php`

### WHY
- **Schema Drift**: fragmented truth resolution.
- **Coordination Gap**: need for shared, persistent task queue.

### HOW
- Surgical updates to legacy PHP and SQL.
- Integrated Vanilla JS/CSS for sidebar UI.

### NOTES
- `TaskService` uses a dual-sync approach: DB + JSON export.
- MySQL `json` type columns are used for vector and context data.

---

## [2026-04-14 18:00 UTC] — Session Wrap-up & Final Alignment

### WHO
Agent: Gemini
Role: AI Developer / Researcher
Human orchestrator: Wolfie (actor_id 1)

### WHAT
- **Final Schema Audit**: Verified that `install_new_lupopedia.sql` is fully synchronized with the 179-table live schema (v4.0.99).
- **Task System Validation**: Confirmed `TaskService` and chat command parsing are correctly registered in the `channels/index.php` loop.
- **Changelog Archival**: Successfully moved entries older than 3 days (pre-2026-04-12) to `archive/changelog_archive.md`.
- **Documentation Consolidation**: Updated `todo.md` and `plan.md` with Gemini's accomplishments and new architectural decisions.

### WHERE
- `docs/versions/4.0.99/changelog.md`
- `docs/versions/4.0.99/archive/changelog_archive.md`
- `docs/versions/4.0.99/status/todo.md`
- `docs/versions/4.0.99/status/plan.md`

### WHY
- Ensure a clean handoff for the next session.
- Maintain constitutional hygiene by keeping the active changelog focused on recent high-impact work.

### HOW
- Python-based surgical archival of changelog entries.
- Sequential `replace` and `write_file` calls for status documentation.

### NOTES
- All core objectives for the "Task Management System" and "Schema Alignment" milestones are now marked as COMPLETE.
- System is ready for live testing of multi-agent coordination.

---

## [2026-04-15 05:00 UTC] — PRD 16 Architecture Clarification: Real Architecture, Agent Discovery, and Q&A System (questions_toon field rename)

### WHO
Agent: Auggie (Claude Sonnet 4.6 / Augment Code)
Human orchestrator: Captain Eric / WOLFIE (actor_id 1)

### WHAT

**Phase 1 — PRD 16 v4.0.99 Real Architecture Clarifications (from `for_auggie.md`):**
- Added §1 bullets: runtime-key purpose, ZIP distribution model, ANUBIS requirements
- Added §3 definitions: TOON file, Orphan, ANUBIS, Dialog Thread, ZIP distribution
- Added §15 ANUBIS protocol: two-path model, 8-action ordered sequence, failure handling, actor identity (actor_id 26)
- Added §16 ZIP Distribution and Runtime Architecture: packaging flow, runtime header usage table, CI validation contract, ANUBIS/ZIP delivery rule (`content_id: null` must not ship in production ZIP)
- Added §17 The Dialog Transcript as WHY: WHAT/WHY duality, 5 normative rules for `dialog_transcript`, transcript as permanent reasoning record

**Phase 2 — Agent Discovery Protocol (from `for_gemini.md`):**
- Added §18 Header as Agent Discovery Mechanism: head-25 contract, O(n) directory scanning, orphan detection by grep, three access depths, `file_path_from_root` as external-agent anchor, token efficiency table
- Updated `lupopedia_headers_format.md`: 22-field table, Fixed Position Headers section, Agent Discovery Protocol summary
- Updated `validators_and_tooling.md`: key count references (20→22), added `fix_memory_key_paths.py` and `fix_web_path_https.py` to tooling inventory
- Updated `.cursor/rules/lupopedia-headers-mandatory.mdc`: added "Why the 25-line limit exists" section
- Added header to `for_gemini.md` (was an orphan)

**Phase 3 — Field Rename: `last_modified_utc` → `questions_toon` (from `for_auggie.md`):**

Field 6 of the 22-key header was `last_modified_utc` — always identical to `when_updated`, carrying zero additional information. It was renamed to `questions_toon` (path to `.questions.toon` Q&A file, or `null`).

*Python scripts updated (30 files, own-headers only):*
- `scripts/lib/header_spec_v3_1.py` — `V4_HEADER_KEYS_ORDERED` updated; `LEGACY_KEYS_V4` mapping added
- `scripts/validate_lupopedia_headers_universal.py` — added `validate_questions_toon()`, `warn_legacy_last_modified_utc()`; removed timestamp equality check; added `HDR_QUESTIONS_TOON_SUFFIX`, `HDR_LAST_MODIFIED_RENAMED` error/warn codes; added `questions_toon` to null-allowlist so `null` is always valid
- `scripts/add_lupopedia_header_to_file.py` — template updated to `questions_toon: null`
- `scripts/fix_double_headers.py` — tie-break logic updated to use `when_updated` only
- `scripts/lib/lupopedia_markdown_header_peel.py` — legacy regex note added
- `scripts/rename_last_modified_to_questions_toon.py` — NEW migration script (batch rename for `.py` own-headers)

*Doctrine and spec files updated:*
- `docs/prd/16_lupopedia_headers.md` — §4.2 field 6 definition; §3 new definitions (`questions_toon`, Q&A TOON); §14.9 tie-break description; all 11 example YAML occurrences; new §19 Q&A System (19.1–19.5: purpose, planned `.questions.toon` JSON structure, path convention, validator rules, 3-phase migration plan); new changelog row
- `docs/doctrine/lupopedia-headers/lupopedia_headers_format.md` — field 6 table row; all 6 example occurrences
- `docs/doctrine/lupopedia-headers/validators_and_tooling.md` — timestamps bullet; `questions_toon` bullet; tooling table row
- `.cursor/rules/lupopedia-headers-mandatory.mdc` — workflow step 1 (removed `last_modified_utc` instruction)

*PRD corpus migration (66 files):*
- All 66 `docs/prd/*.md` header field 6 migrated from `last_modified_utc: "TIMESTAMP"` → `questions_toon: null` (within lines 1–25 only; body references preserved)
- `for_auggie.md` — added complete 25-line v4.0.99 header (was an orphan)
- `docs/versions/4.0.99/changelog.md` — own-header field 6 migrated
- `docs/versions/4.0.99/status/open_questions.md` — own-header field 6 migrated

**Final validator result: 66/66 PASS — 0 FAIL**

### WHERE
- `docs/prd/16_lupopedia_headers.md`
- `docs/doctrine/lupopedia-headers/lupopedia_headers_format.md`
- `docs/doctrine/lupopedia-headers/validators_and_tooling.md`
- `.cursor/rules/lupopedia-headers-mandatory.mdc`
- `scripts/lib/header_spec_v3_1.py`
- `scripts/validate_lupopedia_headers_universal.py`
- `scripts/add_lupopedia_header_to_file.py`
- `scripts/fix_double_headers.py`
- `scripts/lib/lupopedia_markdown_header_peel.py`
- `scripts/rename_last_modified_to_questions_toon.py` (NEW)
- All 66 `docs/prd/*.md` (header field 6 only)
- `for_auggie.md`, `for_gemini.md`
- `docs/versions/4.0.99/changelog.md`, `status/open_questions.md`

### WHY
- `last_modified_utc` was redundant with `when_updated` — the two fields were always identical. Every field in the 22-key header must carry unique semantic value.
- `questions_toon` opens the Q&A surface: a planned `.questions.toon` file per artifact containing anticipated questions + authoritative answers, usable by agents for context injection and by UI for FAQ display.
- PRD 16 needed architectural clarification: Lupopedia is a ZIP distribution; headers are runtime-essential keys linking files to the DB, memory graph, and dialog threads — not development tooling.

### HOW
- PRD 16 edited surgically (11 occurrences of `last_modified_utc` replaced; §19 added)
- Doctrine files and cursor rule edited surgically
- All 30 `.py` own-headers updated via `rename_last_modified_to_questions_toon.py`
- All 66 PRD headers updated via PowerShell batch (header zone: lines 1–25 only)
- Validator null-allowlist patched so `questions_toon: null` passes without error

### NOTES
- The Q&A system (`generate_questions_toon.py`, UI surface) is NOT yet built. `questions_toon: null` is correct and expected for all files until Phase 2 of the migration plan (PRD 16 §19.5).
- Body text references to `last_modified_utc` inside PRD documents (e.g. changelog prose, migration plan text explaining the rename) were preserved — only the YAML header field was changed.
- Two ambiguities surfaced during this session and logged as OQ-36 and OQ-37 in `open_questions.md`.

---

## [2026-04-14] — Full workspace migration: `last_modified_utc` → `questions_toon` (code files, corpus sweep)

### WHO
Agent: Claude Code (Actor 116)
Human orchestrator: Captain Eric / WOLFIE (actor 1)

### WHAT

Complete workspace migration of `last_modified_utc` → `questions_toon` across ALL files Auggie missed in the prior session. Auggie's pass covered documentation and PRD files only; all Python scripts, validators, importers, generators, DB sync, test files, and the full `.md`/`.py`/`.php` corpus were untouched.

**Inventory (Step 0):**
- Full grep across workspace before touching anything: 40+ file categories identified
- Scope boundaries confirmed: `file_last_modified_utc` (DB column) and `file.last_modified_utc` (flip/wolfie legacy header format) are different systems — NOT changed
- Body text migration notes preserved (historical record); only YAML header field `last_modified_utc` migrated

**Code files updated (Step 2):**
- `scripts/lib/header_spec_v3_1.py` — `V4_HEADER_KEYS_ORDERED` field 6; `LEGACY_KEYS_V4` mapping; `QUESTIONS_TOON_SUFFIX` constant
- `scripts/lib/header_validation.py` — backward-compat check for `last_modified_utc` (`HDR_LAST_MODIFIED_RENAMED`); `questions_toon` null/path/collision validation
- `scripts/lib/header_db_sync.py` — migration logic: delete `last_modified_utc`, populate `questions_toon: null` on encounter
- `scripts/lib/lupopedia_markdown_header_peel.py` — `_LAST_MOD_RE` kept for legacy tie-break; docstrings updated
- `scripts/import_content.py` — `_parse_last_modified_utc` renamed to `_parse_file_last_modified_utc(headers, when_updated_fallback)`; `when_updated` used as primary fallback for `file_last_modified_utc` DB column; stale `last_modified_utc` block removed
- `scripts/import_filesystem_channels_to_db.py` — `when_updated` as primary fallback at all 8 read sites; Phase 3 removal comments
- `scripts/import_context.py` — backward-compat comment added to timestamp validation loop
- `scripts/validate_lupopedia_headers.py` — `REQUIRED_HEADER_FIELDS_V4_0_99` updated
- `scripts/new_validate_lupopedia_headers_universal.py` — `required_keys` list updated; `questions_toon` case added to value validator; `last_modified_utc` kept as backward-compat check
- `scripts/validate_pseudocode_discipline.py` — required field check accepts `questions_toon:` OR legacy `last_modified_utc:`
- `scripts/regenerate_headers_for_stale_files.py` — migrates `last_modified_utc` → `questions_toon: null` on encounter
- `scripts/update_stale_headers.py` — pattern `last_modified_utc: "TIMESTAMP"` → `questions_toon: null`
- Generator templates (8 files): `generate_prd_index.py`, `generate_table_docs_from_toons.py`, `generate_headers_from_db.py`, `fix_doctrine_headers.py`, `full_system_sync_1047.py`, `generate_planning_toons.py`, `generate_prd_shorthands.py`, `normalize_prd_headers_4098.py`, `scaffold_implementation.py`, `structural_repair_1048.py`
- `tests/unit/test_header_validators.py` — obsolete ordering violation test removed; legacy test preserved for backward compat

**New doctrine file:**
- `docs/doctrine/lupopedia-headers/questions_toon_schema.md` — complete JSON Schema draft-07 for `.questions.toon` files; path convention; validation rules table; implementation status checklist

**Corpus sweep (batch migration):**
- 1,870 `.md` files: `last_modified_utc: "..."` → `questions_toon: null` within YAML header region (first 40 lines only; body text untouched)
- 82 `.py` files (unfenced comment-grid format): `#   last_modified_utc: ...` → `#   questions_toon: null`
- 86 `.php` files: `  last_modified_utc: ...` → `  questions_toon: null`

**Final verification:**
- 0 `.md` files with `last_modified_utc` in `lupopedia.headers` YAML (6 confirmed false positives in `lupopedia.footer` — correct as-is)
- All Python emitter templates updated
- All Python validators updated with Phase 2 backward-compat + Phase 3 removal comments

### WHERE
- `scripts/lib/` (4 library files)
- `scripts/import_*.py` (3 import scripts)
- `scripts/validate_*.py` (3 validator scripts)
- `scripts/generate_*.py`, `scripts/fix_*.py`, `scripts/normalize_*.py`, `scripts/scaffold_*.py`, `scripts/structural_*.py`, `scripts/full_system_sync_*.py` (10 generator/repair scripts)
- `tests/unit/test_header_validators.py`
- `docs/doctrine/lupopedia-headers/questions_toon_schema.md` (NEW)
- 1,870 `.md` corpus files, 82 `.py` corpus files, 86 `.php` corpus files

### WHY
Auggie's prior session renamed the field in documentation and PRD specs but left all code files using `last_modified_utc`. Validators would emit `HDR_LAST_MODIFIED_RENAMED` on every file that passed through the import pipeline, and generators/templates would write new files with the deprecated field, creating fresh violations continuously. A complete code-layer migration was required to close the gap.

### HOW
- Step 0: Full inventory grep before touching anything; scope boundaries confirmed
- Step 1: Documentation already correct from Auggie's pass; confirmed
- Step 2: Surgical edits to each script, library, and test file; backward-compat Phase 2 strategy throughout (accept both fields, emit WARN for old, `# REMOVE after Phase 3` comments on all backward-compat blocks)
- Step 3: Three-pass corpus sweep — initial batch with 600-byte cutoff, second pass with 800-byte cutoff catching missed files, third pass for `.py` unfenced format
- Batch migration scoped to header region only (first 40 lines for .md, first 25 lines for .py/.php)

### NOTES
- Phase 3 (removing all backward-compat code) is NOT done — `# REMOVE after Phase 3` comments mark every block to clean up once the corpus sweep is confirmed complete
- `questions_toon: null` is correct and expected for ALL files until the `.questions.toon` generation system is built (PRD 16 §19.5)
- The `file_last_modified_utc` DB column in `lupo_contents` was intentionally NOT changed — it is populated from `when_updated` now (since `questions_toon` is no longer a timestamp)
- OQ-38 logged in `open_questions.md` tracking Phase 3 cleanup timing and ownership

---

## [2026-04-15 12:00 UTC] — Validator updated for v4.1.1 field order (pk_* → content_* migration)

**WHO:** AUGGIE

**WHAT:**
- Updated `lib/header_spec_v3_1.py`:
  - `V4_HEADER_KEYS_ORDERED` positions 17–19 changed from `pk_id / pk_slug / parent_pk_id` to `content_parent_id / content_slug / default_collection_id`
  - Added `LEGACY_FIELD_ALIASES = {"pk_id": "content_id", "pk_slug": "content_slug", "parent_pk_id": "content_parent_id"}`
  - Updated `LEGACY_KEYS_V4` — `prd_*` now resolves directly to `content_*` (skipping pk_* hop); added `pk_* → content_*` entries
  - Updated `V3_KEYS_ALLOW_EMPTY_VALUE`: `pk_slug → content_slug`, `parent_pk_id → content_parent_id`
  - Updated `normalize_header_dict_for_validation`: handles `default_collection_id` as nullable; no longer special-cases `pk_id`
  - Module self-header upgraded to `header_format_version: 4.1.1`
- Updated `validate_lupopedia_headers_universal.py`:
  - Imported `LEGACY_FIELD_ALIASES` from `header_spec_v3_1`
  - Added `_resolve_canonical_field_order(hdr, file_path)`: loads field order from atoms_toon atom if available, else falls back to `V3_HEADER_KEYS_ORDERED`
  - `validate_field_ordering` now uses `_resolve_canonical_field_order` for atom-backed order validation
  - `validate_markdown_mechanical_key_line_order`, `validate_python_mechanical_key_line_order`, `validate_star_mechanical_key_line_order`: pk_* keys at any position emit `HDR_PK_LEGACY_ALIAS` warning and continue (no error), enabling graceful migration
  - `_warn_legacy_header_yaml_key_names`: added pk_* warning block emitting `HDR_PK_LEGACY_ALIAS`; updated prd_* migration message to reference `content_*` names
  - `validate_required_fields_by_type`: PRD check now uses `content_slug`; implementation check uses `content_parent_id`
  - `validate_required_header_fields`: removed pk_id special-case; added `default_collection_id` nullable skip; removed after-loop pk_id PRD validation
  - Module self-header upgraded to `header_format_version: 4.1.1`

**WHERE:**
- `lupopedia/scripts/lib/header_spec_v3_1.py`
- `lupopedia/scripts/validate_lupopedia_headers_universal.py`

**WHEN:** `20260415120000`

**WHY:** PRD 16 v4.1.1 defines a 22-field canonical header order using `content_*` fields. The validator was still enforcing the legacy `pk_*` field names, causing PRD 16 itself to fail validation. Files using the old `pk_*` names now pass with migration warnings rather than errors.

**HOW:**
- Read PRD 16 (`docs/prd/16_lupopedia_headers.md`) and `memory/atoms/lupopedia_global_constants.atom.toon` to confirm the 22-field canonical v4.1.1 order
- Updated field order constant and alias maps in `header_spec_v3_1.py`
- Extended mechanical key order validators with pk_* leniency (WARN not ERROR)
- Added atom-backed field order resolution in `validate_field_ordering`
- Verified: `docs/prd/16_lupopedia_headers.md` → `[PASS]`; `AGENTS.md` (pk_* file) → `[PASS]` with `HDR_PK_LEGACY_ALIAS` warnings
