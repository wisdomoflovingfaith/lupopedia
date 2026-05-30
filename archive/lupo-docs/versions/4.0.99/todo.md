---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: documentation
  when_updated: "20260414003000"
  file_path_from_root: "lupo-docs/versions/4.0.99/TODO.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.99/TODO.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "lupo-memory/development/canonical/1026/04/version-4-0-99-todo.toon"
  artifact_type: documentation
  artifact_kind: guide
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: "Lupopedia 4.0.99 backlog"
  status: "active"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: "0/development/4_0_99_todo"
---
# TODO — Lupopedia 4.0.99

**Updated:** UTC `20260414003000` (`python lupo-bin/tick.py`)

## Completed (2026-04-14 00:30 UTC) — Gemini Operating Contract & Channel UI Refinement

- [x] **GEMINI.md Operating Contract** — Synthesized system-wide operating contract from source documents (Crafty Syntax lineage, PRD 02, PRD 16, memory graph doctrine).
- [x] **Channel UI Refinement** — Fixed `channels/index.php` channel dropdown visibility (1000 channels), auto-join membership, and channel-scoped member sidebar.
- [x] **Delta Analysis** — Created `what_gemini_learned_from_crafty_syntax_that_claude_did_not.md` to document unique structural and behavioral insights.
- [x] **Session Reporting** — Detailed status report in `status/session_report_20260413_gemini_contract_channels_cursor.md`.
- [x] **Backlog Hygiene** — Updated `TODO.md` and `PLAN.md`.

**Impact:** Constitutional continuity ensured via `GEMINI.md`; PRD 02 compliance achieved for channel navigation and membership.

---

## Completed (2026-04-12 18:00 UTC) — End of Day Programming: Constitutional Audit, Memory Graph, and Header Enforcement

- [x] **Documentation and memory graph refactor** — Section-by-section editing and explicit cross-linking for constitutional compliance and meta-level doctrine.
- [x] **Comprehensive cross-file audit** — CLAUDE.md, README_WTF.md, all PRDs in lupo-docs/prd/; actionable recommendations produced and implemented.
- [x] **Audit recommendations implemented** — Section-by-section edits, cross-references, vector similarity roadmap, THOTH verification, exception policy, and header validation.
- [x] **Pre-commit hook for header validation** — Installed (chmod failed on Windows, file copied).
- [x] **Status and session reports** — Written to status folder, including troubles, observations, and lessons learned.

**Impact:** Documentation, memory graph, and process are now fully aligned with constitutional doctrine and audit recommendations. Header and memory file standards, cross-linking, and validation are enforced for future-proofing and maintainability.

---

## Completed (2026-04-12 14:23 UTC) — Collections Architecture Clarification & Critical Implementation (Cascade)

- [x] **PRD 43 Trust Ladder** — Transformed from 41-line placeholder to complete 250-line implementation with 5 edge predicates (`trusts`, `delegates_to`, `parent_channel`, `memory_scope_inherits`, `has_access_to`), trust weight quantification (0.0-1.0), and query patterns.
- [x] **Collections Architecture** — Clarified distinction between human UI collections (6 database tables) and AI memory collections (memory edges) in PRDs 72 & 73.
- [x] **Bidirectional Sync Strategy** — Implemented 4-phase sync strategy (Phase 1: Human→AI edges, Phase 2: AI discovery, Phase 3: Approval workflow, Phase 4: Advanced features) in PRD 73 §8.
- [x] **Header Violations Fixed** — Corrected empty summaries and null modules in critical PRDs (00, 43, 72, 73) violating PRD 16.
- [x] **Memory Key Standardization** — Established 1026/04 pattern for core system PRDs, 2026/04 for general PRDs.
- [x] **Memory Export Service** — Built complete `app/Services/MemoryExportService.php` for database → filesystem TOON export with batch processing, incremental updates, and validation.
- [x] **Collection Memory Service** — Created `app/Services/CollectionMemoryService.php` for graph traversal, hierarchy management, and bidirectional sync.
- [x] **Memory System Integration** — Updated PRDs 38 & 51 with collection edge predicates and header inference patterns.
- [x] **CHANGELOG Documentation** — Comprehensive session documentation with WHO, WHAT, WHERE, WHEN, WHY, HOW details.

**Impact:** Collections architecture evolved from "architecturally redundant" to "appropriately separated with integration strategy"; memory graph established as unifying fabric.

## Completed (2026-04-12 13:48 UTC) — PRD core vs secondary renumber + Database Design Doctrine → PRD 80 (Cursor 102)

- [x] **Collision policy** — Reserved **01–49** for **core** PRDs; moved **ten secondary** specs to **`70_*`–`79_*`** per approved map (**data model** = **`70_data_model.md`**, … **install seed** = **`79_install_seed_doctrine.md`**).
- [x] **PRD 80** — Relocated **Database Design Doctrine** from **`70_database_design_doctrine.md`** to **`80_database_design_doctrine.md`** so **PRD 70** is unambiguous; **PRD 00** §3 link updated.
- [x] **Memory + headers + index** — Renamed **`lupo-memory/development/canonical/2026/04/*`**, **`headers/prd/2026/04/*.metadata.json`**, **`install/seed`** toon, **`1026/04`** doctrine sidecars; regenerated **`PRD_INDEX.md`**; fixed **`generate_prd_index.py`** **`HDR_EMPTY_BODY`** regression.
- [x] **Pseudocode + README + agents PRD** — Constitution filenames **`70_*`–`79_*`**, **`THREAD_INDEX.md`**, **`README.md`**, **`07_agents_faucets.md`**, **`migrate_top_prds_v3.py`**, **`generate_phase2_prd_memory_json.py`**.

**Still open (optional hygiene):** Archived version trees (**`4.0.93`**, **`4.0.96–98/status`**) may still cite **historical** filenames — **no automatic mass rewrite** unless WOLFIE scopes it.

## Completed (2026-04-12 02:32 UTC) — KAIROS graph validation + AGENTS v4 header (Cursor 102)

- [x] **KAIROS** — **`verify_edges_for_file`** **`node_status`** (**`complete`** / **`isolated`** / **`incomplete`** / **`missing`** / **`deleted_only`**), **`outgoing_edge_types`**, optional **`expected_edge_types`**, CLI **`--expected-edge-types`**, exit semantics (warnings vs fatal).
- [x] **Pattern #6** — **`detect_memory_graph_orphans.py`** calls KAIROS for **`db_status == ok`**; **`isolated`**/**`incomplete`** = stderr warn only; **`missing`**/**`deleted_only`** contributes to exit **1**; **`kairos`** blob on JSON rows.
- [x] **Normalize** — **`_kairos_verify_after_normalize`** branches on **`node_status`** for clearer operator output.
- [x] **`AGENTS.md`** — **PRD 16 v4.0.99** dense header (**https** **`web_path`**, **`channel_key`**, sidecar doctrine for edges/footer); body preserved.

**Still open:** **`AGENTS.md`** **`memory_key`** **`.toon`** sidecar for **`--strict-memory-pair`**; optional refactor to reuse one DB connection across KAIROS calls in **Pattern #6**.

## Completed (2026-04-11 05:11 UTC) — header hardening session

- [x] **Peel-then-replace** on **`add_lupopedia_header_to_file.py`** — **`peel_leading_lupopedia_yaml_blocks`** before writing a new Markdown header (reduces duplicate stacked blocks).
- [x] **PRD 50** — repaired dense **v4.0.99** envelope (closing `---`, missing keys, **`dialog_transcript`** triple, ASCII-only example UI mock-up).
- [x] **Core tooling headers** — validator / spec / normalize / batch scripts confirmed **v4.0.99** dense compliance this session (see **CHANGELOG** entry **`20260411051122`**).
- [x] **Root-cause write-up** — addendum appended to **`WHAT_WOLFIE_LEARNED_ON_LUPOPEDIA_HEADERS.md`**; **PLAN** / **TODO** updated for enforcement next steps.

*(Note: existing table row **M-14** below remains the **readme-root.json** task — not renumbered.)*

## PRD 16 closure set (carryover)

**Release gate:** Do not tag/ship **4.0.99** until WOLFIE verifies behavior in the **web UI** when this milestone declares release readiness.

- [ ] **LOW** Docs hygiene pass (PRD + root files on `header_format_version` 4.0.x, ongoing; bulk **`http://` → `https://`** on `web_path` where still strict-failing)

## High priority backlog (active)

- [x] Header validator and orphan doctrine enforcement (2026-04-12 17:00 UTC)
- [x] .vscode validation tasks and keybindings for header checks
- [x] CHANGELOG and session report for 4.0.99 checkpoint
- [x] Memory file generation workflow (TOON/.json from headers)


| ID | Task | Assigned | Status |
|----|------|----------|--------|
| H-01 | Trust Ladder: SELECT FOR UPDATE locking (verify/close in current tree) | TBD | Pending |
| H-02 | Staging GC: exclude lineage edges (verify/close in current tree) | TBD | Pending |
| H-03 | PostgreSQL migration support for new installs | TBD | Pending |
| H-04 | Cross-agent verification of latest Lupopedia headers + sidecars | Multi-agent | Pending |
| H-06 | Review and refine generated placeholder section/rule summaries in new PRD memory JSONs | Multi-agent | Pending |

## Priority medium (active)

| ID | Task | Assigned | Status |
|----|------|----------|--------|
| M-01 | Probabilistic GC (no cron) + session clearing audit/update | TBD | Pending |
| M-02 | Semantic Widget session handling in PHP (not JS) | TBD | Pending |
| M-03 | PHPStan integration | TBD | Pending |
| M-04 | Add optional rule-extraction pass to PRD memory generators (MUST/SHALL/FORBIDDEN signal extraction) | Cursor | Pending |
| M-05 | Add status-folder aggregation script for session lessons/troubles report indexing | Cursor | Pending |
| M-15 | Web-based validator for shared hosting (Python/JS, no CLI required) | Cursor | Pending |
| M-16 | Memory file generation/edge creation for all PRDs (batch + on-demand) | Cursor | Pending |
| M-06 | Optional CI gate: run `validate_lupopedia_headers_universal.py` on changed Markdown/Python (decide **strict** vs **`--development`** for legacy `http://` and pairing) | TBD | Pending |
| M-07 | Propagate `pre-commit-lupopedia-headers.sample` into contributor docs / optional install note | Cursor | Pending |
| M-08 | Align `lupo-bin/temporal_anchor.json` / `echo_anchor_utc.py` with `tick.py` session UTC (ops + doc): eliminate drift for header batches | Cursor / WOLFIE | Pending |
| M-09 | Retitle remaining `lupo-docs/doctrine/LUPOPEDIA_HEADERS/*` files whose H1 still says **(v3)** to **(v4)** or cross-reference PRD 16 v4.0.0 | Cursor | Pending |
| M-11 | Optional: **`--update-existing`** on `add_lupopedia_headers_everywhere.py` (refresh `when_updated` / `last_modified_utc` only, no duplicate blocks) | Cursor | Pending |
| M-13 | Bulk migrate legacy **`memory_key` / path strings** **`.../canonical/2026/...`** → **`.../canonical/1026/...`** (align **`validate_trust_ladder_paths.py`**, **`--strict-memory-year`**, PRD 16 §8.1); optional CI: **`validate_trust_ladder_paths.py --strict`** when clean | Cursor / Multi-agent | Pending |
| M-14 | Optional **`readme-root.json`** master next to **`readme-root.toon`** if strict JSON↔TOON pairing is required for root README memory | Cursor | Pending |
| M-18 | Make **`--validate`** default in **`add_lupopedia_header_to_file.py`** (or auto-run **`validate_lupopedia_headers_universal.py`** after write) | Cursor | Pending |
| M-19 | Pre-flight check: assert **22** scalar keys before emitting Markdown/Python header blocks | Cursor | Pending |
| M-20 | Wire **CI gate** — pre-commit hook or GitHub Action (same intent as **M-06**; pick one owner path and close the duplicate row when done) | TBD | Pending |
| M-21 | Optional **`--auto-fix`** (or repair mode) in **`validate_lupopedia_headers_universal.py`** for common mechanical failures | TBD | Pending |
| M-22 | Agent prompt template: **peel before write, validate after write** — **`AGENTS.md`** / **`.cursor/rules`** | Cursor | In progress (**`AGENTS.md`** header now v4.0.99; rules propagation + default **`--validate`** remain) |

## Priority low

| ID | Task | Assigned | Status |
|----|------|----------|--------|
| L-01 | IPv6 test coverage for `ipNetworkPrefix()` | TBD | Pending |
| L-02 | Exponential backoff for suffix exhaustion | TBD | Pending |
| L-03 | Comment-embedded header builders + validator paths for **`.php`**, **`.sql`**, **`.html`** (behind explicit scope flag) | TBD | Pending |
| L-04 | **Git hygiene:** avoid **`git add .`** without scanning for root scratch files (`_x.txt`, temp logs); prefer scoped **`git add -p` / path lists** when possible; document in **AGENTS.md** or **CONTRIBUTING.md** | Cursor | Pending |

## Carried over pending work (historical lines)

- [ ] T-VERIFY clean-install checks (`T-VERIFY-001`..`005`) from 4.0.94 closeout.
- [ ] Packaging smoke/deploy checks previously tracked after 4.0.94 pre-release verification.
- [ ] Step 3 Actor Reconstruction Pass (deferred in 4.0.94).
- [ ] Seed/registry reconciliation tasks previously left open in 4.0.96 (`validate_trust_ladder_registry.py`, seed content_id band checks, audit report).
- [ ] Session test carryovers from 4.0.96 (`D-004`, `D-005`).

## Migration receipts

- **4.0.98** is archival for session receipts and completed phase closeouts; **active execution backlog** for remaining work lives here.
- Completed in **4.0.98** (not duplicated here): **H-05** (PRD memory audit script), **M-10** (`batch_validate_prd_headers.py --include-py`), **M-12** (CHANGELOG v4 envelope), and all **PRD 16 closure** items marked complete there.

This output complies with Lupopedia Constitutional Root Rules.
