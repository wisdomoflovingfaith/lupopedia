---
lupopedia.headers:
  header_format_version: "4.0.98"
  lupopedia.schema: documentation
  when_updated: "20260411014501"
  file_path_from_root: "docs/versions/4.0.98/TODO.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.0.98/TODO.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "memory/development/canonical/1026/04/version-4-0-98-todo.toon"
  artifact_type: documentation
  artifact_kind: guide
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: "Lupopedia 4.0.98 backlog"
  status: "active"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: "0/development/4_0_98_todo"
---
# TODO — Lupopedia 4.0.98

**Updated:** UTC `20260411014501` (`python bin/tick.py`)

## Session completion receipt (2026-04-11 01:45 UTC) — End of day: final receipts, git clean / origin up-to-date

- [x] **WOLFIE** — end of programming day; requested final **CHANGELOG** / **WHAT_WOLFIE** / **TODO** / **PLAN** pass (**hour** in **CHANGELOG** entry).
- [x] **`git status`** — **nothing to commit**, working tree **clean**; **`main`** aligned with **`origin/main`**.
- [x] **`git add .`** + **`git push`** (pre-closeout check) — **Everything up-to-date** (tip **`d4c6b9e6`**).
- [x] **Version docs:** **`CHANGELOG.md`** (entry **01:45 UTC**), **`TODO.md`** (this receipt), **`PLAN.md`** (**Phase X**), **`status/WHAT_WOLFIE_LEARNED_ON_LUPOPEDIA_HEADERS.md`** (addendum **01:45 UTC**), **`status/THREAD_INDEX.md`**; headers **`20260411014501`**.
- [x] **`git commit` / `git push`** — **`5ca4e960`** (**5** files) landed on **`origin/main`** with closeout receipts.

## Session completion receipt (2026-04-11 01:38 UTC) — `git add .`, commit, `git push`; version receipts; scratch file hygiene

- [x] **`git add .`** — stage full working tree per WOLFIE request (broad PRD / doctrine / scripts / memory / agents changes).
- [x] **`git commit`** — single commit with **Cursor** / **4.0.98** attribution (see **CHANGELOG** **01:38 UTC** for exact message).
- [x] **`git push`** — publish to **`origin`** (verify branch + fast-forward after run).
- [x] **Removed** accidental root **`_x.txt`** scratch before commit (do not ship empty test files).
- [x] **Version docs:** **`CHANGELOG.md`** (entry **01:38 UTC** with hour), **`TODO.md`** (this receipt), **`PLAN.md`** (**Phase W**), **`status/WHAT_WOLFIE_LEARNED_ON_LUPOPEDIA_HEADERS.md`** (addendum **01:38 UTC**), **`status/THREAD_INDEX.md`**; headers **`20260411013812`**.

## Session completion receipt (2026-04-11 01:31 UTC) — Trust ladder root docs, FORMAT HTTPS examples, README repair, `validate_trust_ladder_paths`, LILITH audit hygiene

- [x] **Root `README.md`:** rebuilt corrupt header + broken fence; **Chronological Trust Ladder** section + doctrine link; **Core Principles** trust / `memory` pointer.
- [x] **Trust ladder artifacts:** `TRUST_LADDER_DO_NOT_FIX.txt`; `memory/constitutional/seed/trust-ladder-trust-encoding.json`; `memory/development/canonical/1026/04/trust-ladder-past-as-trust-memory.json`; `readme-root.toon` edges; `CHRONOLOGICAL_TRUST_LADDER.md` cross-link.
- [x] **`validate_trust_ladder_paths.py`:** repo scan for legacy `canonical/2026` in path-shaped strings; default **non-fatal**; **`--strict`** for future CI.
- [x] **`run_tests.sh`:** advisory invocation of trust-ladder path validator.
- [x] **`16_lupopedia_headers.md`:** HTML `TRUST_LADDER` comment + header bump.
- [x] **`LUPOPEDIA_HEADERS_FORMAT.md`:** example `web_path` **https** in Python / PHP / migration snippets; timestamps.
- [x] **`VALIDATORS_AND_TOOLING.md`:** timestamp / verification batch.
- [x] **`normalize_lupopedia_md_header_25.py`:** self-header — **https**, **1026**, three-segment `dialog_transcript`, tick UTC.
- [x] **Agent prompts:** `lilith`, `themis`, `countermeasure` — trust-ladder / past-as-trust blocks.
- [x] **LILITH audits:** verified **superseded / false vs git** on validator, add-header (`dm`), CHANGELOG v4, FORMAT/VALIDATORS; thread responses + version receipts.
- [x] **Version docs:** `CHANGELOG.md` (entry **01:31 UTC** with hour), `TODO.md` (this receipt), `PLAN.md` (Phase **V**), `status/WHAT_WOLFIE_LEARNED_ON_LUPOPEDIA_HEADERS.md` (addendum **01:31 UTC**), `status/THREAD_INDEX.md`.

## Session completion receipt (2026-04-11 01:17 UTC) — Header toolchain scale-up, strict flags, shebang/thread-slug, review-drift notes, version receipts

- [x] **`batch_validate_prd_headers.py`**: **`--jobs`** parallel validation; **`--fail-fast`**; **`--no-progress`**; **`--report PATH`** (JSON); **`--include-py`** (`scripts/**/*.py`); **`--extensions`**; **`--strict`** alias → **`--strict-memory-pair`**; **`--strict-memory-year`** passthrough; scope labeling.
- [x] **`validate_lupopedia_headers_universal.py`**: **`--strict`** alias; **`--strict-memory-year`**; **`validate_dialog_transcript_triple`** empty/empty-segment **ERROR**, **>3** segments **`HDR_DIALOG_EXTRA_SEGMENTS` WARN** (legacy paths); **ASCII-only** hints in new prints (**Windows cp1252**); module docstring **auditor note** (stale external reviews).
- [x] **`add_lupopedia_header_to_file.py`**: **`include_shebang_line`** / header **after** **`#!`**; **`--thread-slug`**; dead code removal; auditor note; temporal bump.
- [x] **`add_lupopedia_headers_everywhere.py`**: shebang no longer skips; window lines **2–26** after **`#!`**; **`--thread-slug`**; aligned with single-file tool.
- [x] **Version docs:** **`CHANGELOG.md`** (entry **01:17 UTC** with hour + **v4** 25-line envelope fix for this file), **`TODO.md`** (this receipt), **`PLAN.md`** (Phase **U**), **`status/WHAT_WOLFIE_LEARNED_ON_LUPOPEDIA_HEADERS.md`** (addendum: troubles, rules vs validator vs CLI relaxations), **`status/THREAD_INDEX.md`**.
- [x] **`M-10`**: **`batch_validate_prd_headers.py --include-py`** satisfies “validate `.py` under `scripts`” — mark **Complete** in table below.

## Session completion receipt (2026-04-10 20:18 UTC) — PRD 16 policy pass: HTTPS, `HDR_EMPTY_BODY`, JSON↔TOON pairing, `--development`, generator alignment

- [x] **PRD 16** (`16_lupopedia_headers.md`): **`web_path`** MUST **`https://`** (reject **`http://`** except **`--development`**); **§4.3 rule 8** / **`HDR_EMPTY_BODY`** (Markdown line 26 non-empty); **§5.2.2** JSON master ↔ TOON pairing (**`HDR_MEMORY_JSON_MASTER`**, alias **`SIDECAR_JSON_MASTER_MISSING`**); **§12.4** **`--development`** semantics; supplementary list **`content_id`** correction (field stays required in §4.2).
- [x] **Doctrine:** `LUPOPEDIA_HEADERS_FORMAT.md`, `VALIDATORS_AND_TOOLING.md` — HTTPS, line-26 rule, pairing + dev mode, alias note.
- [x] **`validate_lupopedia_headers_universal.py`**: HTTPS validation; MD line 26 + Python body scan; **`--development`**, **`--strict-memory-pair`**; message alias for sidecar pairing.
- [x] **`batch_validate_prd_headers.py`**: passthrough **`--development`**, **`--strict-memory-pair`**.
- [x] **`add_lupopedia_header_to_file.py`**: **`trust_tier`**, **`federation_node_id`**, **`use_https_web_path`** / **`--development`**; **`dialog_transcript`** = three segments; drop **`dialog_middle`**; Python **`status: complete`**; **`_memory_path_year_segment`** (canonical → **year−1000**).
- [x] **`add_lupopedia_headers_everywhere.py`**: defaults **`development`**, HTTPS, aligned memory year in self-header; CLI **`--trust-tier`**, **`--federation-node-id`**, **`--parent-prd`**, **`--validate`**, **`--development`**; stricter Python skip (marker in first 25 lines).
- [x] **Version docs:** `CHANGELOG.md` (entry **20:18 UTC**), this `TODO.md`, `PLAN.md`, `status/WHAT_WOLFIE_LEARNED_ON_LUPOPEDIA_HEADERS.md`, `status/THREAD_INDEX.md`.

## Session completion receipt (2026-04-10 18:35 UTC) — header batch tooling, validator hardening, PRD 16 encoding

- [x] `add_lupopedia_headers_everywhere.py`: batch **`.md` / `.py`** headers (temporal anchor, idempotent skips, safe defaults).
- [x] `add_lupopedia_header_to_file.py`: **`channel_key`** on MD/PY header builders (**superseded 20:18 UTC:** **`dialog_middle`** removed; use **three-segment** **`dialog_transcript`** only).
- [x] `batch_validate_prd_headers.py`: timeout, **`--all-md`**, **`--dry-run-list`**, **`--quiet`** passthrough to universal validator, repo-relative paths.
- [x] `validate_lupopedia_headers_universal.py`: **`memory_key` `.toon`**, deterministic 25-line MD YAML extract, Python envelope hints, repo-root cache, **`[PASS]`** / **`--quiet`** / **`--type`** / **`--version`** (**extended 20:18 UTC:** HTTPS, empty body, pairing, **`--development`**).
- [x] PRD 16 + `LUPOPEDIA_HEADERS_FORMAT.md`: ASCII-first punctuation, self-check + YAML **`headers`** channel, §4.2 mechanical-parsing sentence, companion **v4** section titles (**extended 20:18 UTC:** policy rows above).
- [x] Version docs: `CHANGELOG.md`, this `TODO.md`, `PLAN.md`, `status/SESSION_HEADER_TOOLCHAIN_PRD16_ENCODING_20260410183513.md`, `status/THREAD_INDEX.md`.

## Session completion receipt (2026-04-10 16:55 UTC) — LUPOPEDIA HEADERS envelope governance

- [x] Validator: PRD §19.3-aligned codes, `[HINT]` missing §4.2 keys on envelope failure, removed dead `generate_context_id`.
- [x] `lupopedia_markdown_header_peel.py`: 25-line fast path uses **tail-relative** indices for blanks and closing `---`.
- [x] Mandatory headers: `add_lupopedia_header_to_file.py`, `TEMPLATES_NEW_FILE.md`, `.cursor/rules/lupopedia-headers-mandatory.mdc`, git-hook sample, AGENTS.md section, template README pointer.
- [x] Doctrine: PRD 16 + `LUPOPEDIA_HEADERS_FORMAT.md` — all 20 keys present; optional = value only.
- [x] PRD 16 `memory_key` → `16-lupopedia-headers.toon`; `normalize_lupopedia_md_header_25.py` full Python header.
- [x] Version docs: `CHANGELOG.md`, this `TODO.md`, `PLAN.md`, `status/SESSION_HEADER_ENVELOPE_GOVERNANCE_20260410165509.md`, `status/THREAD_INDEX.md`.

## Session completion receipt (2026-04-10 13:12 UTC)

- [x] Completed PRD memory pairs for all 54 PRDs (JSON + TOON + validator pass).
- [x] Hardened `normalize_prd_headers_4098.py` (PyYAML enforcement, dry-run, sidecar preservation, dynamic year/month).
- [x] Hardened `cleanup_and_normalize_prds.py` (normalize dry-run passthrough + script existence checks).
- [x] Hardened `generate_phase2_prd_memory_json.py` (unknown-reference warnings + timestamp-based output path).
- [x] Added `generate_remaining_prd_memory_pairs.py` to complete remaining 32 PRDs in one controlled pass.
- [x] Re-ran normalization and universal header validation for all 54 PRD files.

## Completed (2026-04-09)

| ID | Task | Who | Completed |
|----|------|-----|-----------|
| C-01 | Session identity - filtered User Agent | Cursor | 2026-04-09 12:00 |
| C-02 | Memory compaction - DB-first with fallback | Cursor | 2026-04-09 12:30 |
| C-03 | Emoji stripping from transcripts | Cursor | 2026-04-09 13:00 |
| C-04 | Constitutional Rule 7 (No Emoji) | Eric | 2026-04-09 14:00 |
| C-05 | Channel ID -> Channel Key migration | Cursor | 2026-04-09 15:00 |
| C-06 | Top 12 PRDs migrated to v3 headers | Cursor | 2026-04-09 16:00 |
| C-07 | LUPOPEDIA HEADERS v3 format documentation | Cursor | 2026-04-09 13:30 |
| C-08 | MEMORY_FILE_SCHEMA.md created | Cursor | 2026-04-09 13:30 |
| C-09 | Version 4.0.97 docs handoff refresh (CHANGELOG/TODO/PLAN) | Cursor | 2026-04-09 14:37 |
| C-10 | Multi-agent status report package in `status/` | Cursor | 2026-04-09 14:37 |
| C-11 | Validated v3 Headers (Python script updates + error messaging) | Antigravity | 2026-04-09 14:50 |
| C-12 | Semantic UI: Add shortcut (pin to `lupo_collection_tab_map`), dynamic tabs menu, Eye edge focus, book/scroll shell setting | Cursor | 2026-04-09 20:59 UTC |

---

## PRD 16 closure set

**Release gate:** Do not tag/ship **4.0.98** until WOLFIE verifies behavior in the **web UI**.

- [x] **HIGH** Enforce §12 validator rules
- [x] **HIGH** Add §9 transcript API path
- [x] **HIGH** Run §15 E2E JSONL delta gate (script + snapshot/assert smoke run)
- [x] **MEDIUM** Execute 4.0.97 -> 4.0.98 backlog migration
- [ ] **LOW** Docs hygiene pass (PRD + root files on `header_format_version` 4.0.x, ongoing; bulk **`http://` → `https://`** on `web_path` where still strict-failing)

## High priority backlog (active)

| ID | Task | Assigned | Status |
|----|------|----------|--------|
| H-01 | Trust Ladder: SELECT FOR UPDATE locking (verify/close in current tree) | TBD | Pending |
| H-02 | Staging GC: exclude lineage edges (verify/close in current tree) | TBD | Pending |
| H-03 | PostgreSQL migration support for new installs | TBD | Pending (moved from 4.0.97) |
| H-04 | Cross-agent verification of latest Lupopedia headers + sidecars | Multi-agent | Pending |
| H-05 | Build one-command audit receipt for all 54 PRDs (JSON/TOON/validation matrix export) | Cursor | **Complete** (see `scripts/audit_prd_memory_pairs.py`, status `PRD_MEMORY_AUDIT_20260410131532.md`, CHANGELOG 2026-04-10 13:19 UTC) |
| H-06 | Review and refine generated placeholder section/rule summaries in new PRD memory JSONs | Multi-agent | Pending |

## Priority Medium (active)

| ID | Task | Assigned | Status |
|----|------|----------|--------|
| M-01 | Probabilistic GC (no cron) + session clearing audit/update | TBD | Pending |
| M-02 | Semantic Widget session handling in PHP (not JS) | TBD | Pending |
| M-03 | PHPStan integration | TBD | Pending |
| M-04 | Add optional rule-extraction pass to PRD memory generators (MUST/SHALL/FORBIDDEN signal extraction) | Cursor | Pending |
| M-05 | Add status-folder aggregation script for session lessons/troubles report indexing | Cursor | Pending |
| M-06 | Optional CI gate: run `validate_lupopedia_headers_universal.py` on changed Markdown/Python (decide **strict** vs **`--development`** for legacy `http://` and pairing) | TBD | Pending |
| M-07 | Propagate `pre-commit-lupopedia-headers.sample` into contributor docs / optional install note | Cursor | Pending |
| M-08 | Align `bin/temporal_anchor.json` / `echo_anchor_utc.py` with `tick.py` session UTC (ops + doc): eliminate drift for header batches | Cursor / WOLFIE | Pending |
| M-09 | Retitle remaining `docs/doctrine/LUPOPEDIA_HEADERS/*` files whose H1 still says **(v3)** to **(v4)** or cross-reference PRD 16 v4.0.0 | Cursor | Pending |
| M-10 | `batch_validate_prd_headers.py` **`--include-py`** validates **`scripts/**/*.py`** via universal validator **`--type py`** | Cursor | **Complete** (2026-04-11 01:17 UTC) |
| M-11 | Optional: **`--update-existing`** on `add_lupopedia_headers_everywhere.py` (refresh `when_updated` / `last_modified_utc` only, no duplicate blocks) | Cursor | Pending |
| M-12 | **`CHANGELOG.md`** (4.0.98) — **PRD 16** v4 twenty-key grid, **`content_id`**, two blanks before closing **`---`**, line **26** body | Cursor | **Complete** (2026-04-11 01:17 UTC) |
| M-13 | Bulk migrate legacy **`memory_key` / path strings** **`.../canonical/2026/...`** → **`.../canonical/1026/...`** (align **`validate_trust_ladder_paths.py`**, **`--strict-memory-year`**, PRD 16 §8.1); optional CI: **`validate_trust_ladder_paths.py --strict`** when clean | Cursor / Multi-agent | Pending |
| M-14 | Optional **`readme-root.json`** master next to **`readme-root.toon`** if strict JSON↔TOON pairing is required for root README memory | Cursor | Pending |

## Priority Low

| ID | Task | Assigned | Status |
|----|------|----------|--------|
| L-01 | IPv6 test coverage for `ipNetworkPrefix()` | TBD | Pending |
| L-02 | Exponential backoff for suffix exhaustion | TBD | Pending |
| L-03 | Comment-embedded header builders + validator paths for **`.php`**, **`.sql`**, **`.html`** (behind explicit scope flag) | TBD | Pending |
| L-04 | **Git hygiene:** avoid **`git add .`** without scanning for root scratch files (`_x.txt`, temp logs); prefer scoped **`git add -p` / path lists** when possible; document in **AGENTS.md** or **CONTRIBUTING.md** | Cursor | Pending |

## Carried over pending work (4.0.94 -> 4.0.97)

- [ ] T-VERIFY clean-install checks (`T-VERIFY-001`..`005`) from 4.0.94 closeout.
- [ ] Packaging smoke/deploy checks previously tracked after 4.0.94 pre-release verification.
- [ ] Step 3 Actor Reconstruction Pass (deferred in 4.0.94).
- [ ] Seed/registry reconciliation tasks previously left open in 4.0.96 (`validate_trust_ladder_registry.py`, seed content_id band checks, audit report).
- [ ] Session test carryovers from 4.0.96 (`D-004`, `D-005`).

## Migration receipts

- All pending items from `4.0.94` / `4.0.95` / `4.0.96` / `4.0.97` are now tracked here if still open.
- `4.0.97` is archival history only; no active execution remains there.

This output complies with Lupopedia Constitutional Root Rules.
