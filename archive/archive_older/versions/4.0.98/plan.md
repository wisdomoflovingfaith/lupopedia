---
lupopedia.headers:
  header_format_version: "4.0.98"
  lupopedia.schema: documentation
  when_updated: "20260411014501"
  file_path_from_root: "docs/versions/4.0.98/PLAN.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.0.98/PLAN.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "memory/development/canonical/1026/04/version-4-0-98-plan.toon"
  artifact_type: documentation
  artifact_kind: guide
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: "Lupopedia 4.0.98 execution plan"
  status: "active"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: "0/development/version-4-0-98-plan"
---
# PLAN — Lupopedia 4.0.98 (Updated 2026-04-11 01:45 UTC)

## Phase Status

| Phase | Description | Status | Completed |
|-------|-------------|--------|-----------|
| Phase A | Session identity (filtered UA) | COMPLETE | 2026-04-09 |
| Phase B | Memory compaction (DB-first + fallback) | COMPLETE | 2026-04-09 |
| Phase C | Emoji stripping | COMPLETE | 2026-04-09 |
| Phase D | LUPOPEDIA HEADERS v3 | COMPLETE | 2026-04-09 |
| Phase E | Channel key migration | COMPLETE | 2026-04-09 |
| Phase F | Top 12 PRD migration | COMPLETE | 2026-04-09 |
| Phase G | Trust Ladder locking + staging GC lineage exclusions | NEXT | Pending |
| Phase H | PostgreSQL migration support (new installs) | NEXT | Pending |
| Phase I | Cross-agent verification (latest headers + sidecars) | NEXT | Pending |
| Phase J | Probabilistic GC + session clearing hardening | NEXT | Pending |
| Phase K | Strict header validations v3 | COMPLETE | 2026-04-09 |
| Phase L | Public semantic chrome (shortcut pin -> tab map, Eye modes, book/scroll shell) | COMPLETE | 2026-04-09 20:59 UTC |
| Phase M | PRD memory program (all 54 PRDs JSON/TOON/validation) | COMPLETE | 2026-04-10 13:12 UTC |
| Phase N | Toolchain hardening (normalize/orchestrate/generators) | COMPLETE | 2026-04-10 13:12 UTC |
| Phase R | PRD 16 **25-line envelope** governance (peel fix, validator hints, mandatory-header policy, PRD 16 memory_key truth) | COMPLETE | 2026-04-10 16:55 UTC |
| Phase S | Header **batch** tooling + **validator** hardening + **batch driver** quiet mode + PRD 16 / FORMAT **encoding & v4** alignment | COMPLETE | 2026-04-10 18:35 UTC |
| Phase T | PRD 16 **production policy** pass: HTTPS **`web_path`**, **`HDR_EMPTY_BODY`**, JSON↔TOON pairing + **`--strict-memory-pair`**, **`--development`** in validator + batch driver, header generators (**trust_tier**, **federation_node_id**, three-segment **`dialog_transcript`**, drop **`dialog_middle`**) | COMPLETE | 2026-04-10 20:18 UTC |
| Phase U | Header **batch scale-up** + **validator strict ergonomics** + **Python shebang / `--thread-slug`** + **review-drift documentation** (see **CHANGELOG** 2026-04-11 **01:17 UTC**) | COMPLETE | 2026-04-11 01:17 UTC |
| Phase V | **Trust ladder root documentation** + **FORMAT example HTTPS sweep** + **`normalize_lupopedia_md_header_25.py` self-header** + **`validate_trust_ladder_paths` / `run_tests` advisory** + **facet prompt trust blocks** + **LILITH audit git-verified closures** + **version-folder receipts** (**CHANGELOG** 2026-04-11 **01:31 UTC**) | COMPLETE | 2026-04-11 01:31 UTC |
| Phase W | **Remote publish:** full-tree **`git add .`**, **commit**, **`git push origin`** + **version-folder receipts** (**CHANGELOG** / **TODO** / **PLAN** / **WHAT_WOLFIE** / **THREAD_INDEX**, **20260411013812**) + scratch-file hygiene | COMPLETE | 2026-04-11 01:38 UTC |
| Phase X | **End-of-day closeout (2026-04-11):** **`tick.py`** batch **`20260411014501`**; **CHANGELOG** entry **01:45 UTC** (5W+H + hour); **`WHAT_WOLFIE`** day-summary addendum; **TODO** / **PLAN** / **THREAD_INDEX** refresh; **`git status` clean**, **`git push`** **up-to-date** vs **`origin/main`** | COMPLETE | 2026-04-11 01:45 UTC |

## Current Focus

**End of day (2026-04-11 ~01:45 UTC):** **Phase X** receipts committed as **`5ca4e960`** and **pushed** to **`origin/main`** (**5** files under **`docs/versions/4.0.98/`**).

**Next up (resume):** Trust Ladder SELECT FOR UPDATE locking + StagingGcService lineage exclusion verification (Phase G / Phase Q). In parallel: **bulk `web_path` HTTPS migration or CI policy** (strict vs **`--development`** — `TODO.md` M-06), **legacy `memory_key` year segment migration** (`TODO.md` M-13 — **`canonical/2026` → `1026`**, optional **`validate_trust_ladder_paths.py --strict`** in CI when clean), **git review hygiene** (`TODO.md` L-04 — avoid blind **`git add .`**), **`temporal_anchor` / `tick` alignment** (`TODO.md` M-08), doctrine H1 **v3 → v4** retitles (`TODO.md` M-09), status-folder aggregation script (`TODO.md` M-05), optional **`readme-root.json`** if strict sidecar pairing is required (`TODO.md` M-14), optional extend **`batch_validate_prd_headers.py`** Python scope beyond **`scripts/`** (e.g. **`app/`**) if product wants one matrix.

**Blocked by:** Nothing

**Waiting for:** Claude Code token reset (for ladder/GC verification handoff); WOLFIE next session for **Phase G/Q** execution

## Multi-Agent Follow Through (4.0.98)

- Claude Code (actor 116): verify ladder locking and staging GC invariants on current tree.
- Windsurf/Kiro/Antigravity: verify latest `4.0.x` headers + sidecar parity, and semantic widget PHP-session behavior.
- Cursor: keep 4.0.98 TODO as single active backlog and preserve 4.0.97 as archive only.

## New dependency-ordered plan (post PRD-memory completion)

### Phase R (COMPLETE) — Envelope governance
- **Dependency:** PRD 16 + universal validator + peel path.
- **Completion criteria:** peel 25-line branch matches tail indices; validator emits missing-key hints; mandatory-header tooling + IDE rule + AGENTS; PRD 16 `memory_key` matches on-disk TOON; status report `SESSION_HEADER_ENVELOPE_GOVERNANCE_20260410165509.md`.

### Phase S (COMPLETE) — Batch toolchain + validator + doctrine encoding
- **Dependency:** Phase R baseline; `add_lupopedia_header_to_file.py` builders; universal validator CLI.
- **Completion criteria:** `add_lupopedia_headers_everywhere.py` shipped with safe defaults; `batch_validate_prd_headers.py` supports timeouts, `--all-md`, validator `--quiet`; universal validator enforces **`memory_key` `.toon`**, deterministic MD YAML extraction for 25-line envelope, Python envelope hints, repo-root cache, **`[PASS]`** / **`--quiet`** / **`--type`**; PRD 16 + FORMAT encoding and v4 alignment; status `SESSION_HEADER_TOOLCHAIN_PRD16_ENCODING_20260410183513.md`; CHANGELOG/TODO/PLAN updated at **18:35 UTC**.

### Phase T (COMPLETE) — PRD 16 production validator policy + generator parity
- **Dependency:** Phase S baseline; WOLFIE rulings on TLS `web_path`, header-only files, sidecar pairing, dev relaxations.
- **Completion criteria:** Universal validator rejects **`http://`** unless **`--development`**; Markdown line 26 non-empty (**`HDR_EMPTY_BODY`**); seed/canonical **`memory_key`** JSON master paired with on-disk `.toon` (warn; **`--strict-memory-pair`** error); **`batch_validate_prd_headers.py`** passes **`--development`** / **`--strict-memory-pair`**; `add_lupopedia_header_to_file.py` and `add_lupopedia_headers_everywhere.py` emit **HTTPS**, **three-segment** `dialog_transcript`, no **`dialog_middle`**, parameterized **trust** / **federation**; PRD 16 + FORMAT + VALIDATORS_AND_TOOLING updated; version **CHANGELOG** entry **2026-04-10 20:18 UTC**; status `WHAT_WOLFIE_LEARNED_ON_LUPOPEDIA_HEADERS.md`; TODO/PLAN/THREAD_INDEX refreshed for same batch (**packed UTC `20260410201846`**).

### Phase U (COMPLETE) — Batch driver scale-up, strict flags, shebang/thread-slug, anti–false-review notes
- **Dependency:** Phase T baseline; universal validator + both add-header scripts + batch driver.
- **Completion criteria:** **`batch_validate_prd_headers.py`** supports parallel **`--jobs`**, **`--report`**, **`--include-py`**, **`--extensions`**, **`--fail-fast`**, and passthrough **`--strict-memory-year`**; universal validator exposes **`--strict`** alias and **`--strict-memory-year`**; dialog transcript **>3** segments → **`HDR_DIALOG_EXTRA_SEGMENTS` WARN** where applicable; new stdout/stderr hints remain **ASCII-safe** on Windows; Python headers insert **after** shebang; **`--thread-slug`** on single-file and batch adders; **`WHAT_WOLFIE_LEARNED`** addendum documents troubles (stale reviews, console encoding, CI gap) and real CLI relaxation surface; **CHANGELOG** entry **2026-04-11 01:17 UTC** + **CHANGELOG** v4 envelope (**M-12**); **TODO** receipt + **M-10** / **M-12** complete; **PLAN** Phase U row; **THREAD_INDEX** row for **WHAT_WOLFIE** updated (**packed UTC `20260411011721`**).

### Phase V (COMPLETE) — Trust ladder root narrative, example alignment, path-debt scanner, audit hygiene
- **Dependency:** Phase T/U baseline; PRD 16 HTTPS policy; root **README** as public surface.
- **Completion criteria:** Root **`README.md`** validates and explains **Chronological Trust Ladder** + **`memory/`** seeds; **`LUPOPEDIA_HEADERS_FORMAT.md`** examples use **`https://`**; **`normalize_lupopedia_md_header_25.py`** self-header matches twenty-key grid + §8.1 year segment; **`validate_trust_ladder_paths.py`** + **`run_tests.sh`** advisory shipped; facet prompts (**`lilith`**, **`themis`**, **`countermeasure`**) include trust-ladder text; **LILITH** audit severities **verified against git**; **CHANGELOG** entry **2026-04-11 01:31 UTC**; **`WHAT_WOLFIE_LEARNED`** addendum **01:31 UTC**; **TODO** **M-13** / **M-14** queued; **packed UTC `20260411013132`**.

### Phase W (COMPLETE) — Full-tree stage, commit, push, receipts
- **Dependency:** Phase V baseline; clean enough working tree to publish (WOLFIE-requested **`git add .`**).
- **Completion criteria:** **`git push`** attempted to **`origin`**; **CHANGELOG** entry **2026-04-11 01:38 UTC** documents **WHO/WHAT/WHERE/WHEN/WHY/HOW**; **TODO** receipt **01:38 UTC**; **PLAN** Phase **W** row; **`WHAT_WOLFIE_LEARNED`** addendum **01:38 UTC**; **THREAD_INDEX** updated; accidental **`_x.txt`** removed; **packed UTC `20260411013812`**.

### Phase X (COMPLETE) — End-of-day documentation + remote parity check
- **Dependency:** Phase W complete; WOLFIE end-of-day request.
- **Completion criteria:** **CHANGELOG** **01:45 UTC** closeout entry; **`WHAT_WOLFIE_LEARNED`** **01:45 UTC** addendum (envelope summary + resume checklist); **TODO** receipt **01:45 UTC**; **PLAN** Phase **X** row + **Current Focus** end-of-day note; **THREAD_INDEX** row; **`git status`** clean and **`git push`** reports **up-to-date** at verification time; **packed UTC `20260411014501`**.

### Phase O (depends on Phase M/N complete) - Memory quality refinement
- Refine generic section/rule summaries in generated PRD memory JSON files.
- Add optional deterministic rule extraction (`MUST`/`SHALL`/`FORBIDDEN`) in generator scripts.
- Completion criteria: curated summaries committed for highest-traffic PRDs; extractor emits stable output.

### Phase P (depends on Phase O start, can run concurrently with Phase Q)
- Build and publish one-command audit matrix report for all 54 PRD memory pairs.
- Completion criteria: single script outputs JSON/TOON/validation status and missing-edge diagnostics.

### Phase Q (depends on earlier GC/security workstreams, concurrent with Phase P)
- Complete Trust Ladder locking and Staging GC lineage-edge verification tasks.
- Completion criteria: implementation verified in code path + tests/check receipts logged in status.

This output complies with Lupopedia Constitutional Root Rules.
