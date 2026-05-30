---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/versions/4.1.0/TODO.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.1.0/TODO.md"
  status: "active"
  when_updated: "20260415074141"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/version-4-1-0-todo.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/4_1_0_todo"
  artifact_type: documentation
  artifact_kind: guide
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: "Lupopedia 4.1.0 backlog"
  summary: ""
---
# TODO — Lupopedia 4.1.0

Rolled over from version 4.0.99 to 4.1.0
No changes to content
Migration date current UTC `20260415074141`

## Unfinished carryovers flagged in completed sections

**Still open (optional hygiene):** Archived version trees (**`4.0.93`**, **`4.0.96–98/status`**) may still cite **historical** filenames — **no automatic mass rewrite** unless WOLFIE scopes it.

**Still open:** **`AGENTS.md`** **`memory_key`** **`.toon`** sidecar for **`--strict-memory-pair`**; optional refactor to reuse one DB connection across KAIROS calls in **Pattern #6**.

## PRD 16 closure set (carryover)

**Release gate:** Do not tag/ship **4.0.99** until WOLFIE verifies behavior in the **web UI** when this milestone declares release readiness.

- [ ] **LOW** Docs hygiene pass (PRD + root files on `header_format_version` 4.0.x, ongoing; bulk **`http://` → `https://`** on `web_path` where still strict-failing)

## High priority backlog (active)

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
| M-08 | Align `bin/temporal_anchor.json` / `echo_anchor_utc.py` with `tick.py` session UTC (ops + doc): eliminate drift for header batches | Cursor / WOLFIE | Pending |
| M-09 | Retitle remaining `docs/doctrine/LUPOPEDIA_HEADERS/*` files whose H1 still says **(v3)** to **(v4)** or cross-reference PRD 16 v4.0.0 | Cursor | Pending |
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
