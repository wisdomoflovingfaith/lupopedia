---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/versions/4.1.0/CHANGELOG.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.1.0/CHANGELOG.md"
  status: "active"
  when_updated: "20260415101800"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/version-4-1-0-changelog.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/4_1_0_changelog"
  artifact_type: changelog
  artifact_kind: version_specific
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: changelog
  title: "Lupopedia 4.1.0 changelog"
  summary: "Hourly changelog for version 4.1.0 migration and implementation work."
---
# file: Lupopedia 4.1.0 changelog — session: 4.1.0 — delegation: wolf:root — web_path: [https://www.lupopedia.com/lupopedia/docs/versions/4.1.0/CHANGELOG.md](https://www.lupopedia.com/lupopedia/docs/versions/4.1.0/CHANGELOG.md)

# Lupopedia 4.1.0 Changelog

## UTC HOUR 20260415 09

### 20260415091500 - AUGGIE
**What changed:**
- Implemented atoms_toon Phase 1 validation in Python tooling
- Added dedicated `validate_atoms_toon()` function with suffix, canonical year (1026), existence, and collision checks
- Cleaned up legacy `module` handling and dead post-normalization checks
- Updated header_spec_v3_1.py with new constants and field documentation

**Files:**
- scripts/lib/header_spec_v3_1.py
- scripts/validate_lupopedia_headers_universal.py

**Why:**
- Enforce pointer hygiene for immutable constraints layer without waiting for full THOTH

### 20260415093000 - WOLF
**What changed:**
- Upgraded PRD 02 header to full v4.1.0 canonical 22-field dense format
- Renamed memory_key → memory_toon and dialog_transcript → transcript_jsonl in header + memory artifacts
- Updated associated .toon / .json pair for PRD 02

**Files:**
- docs/prd/02_channels_discussions.md (header only)

**Why:**
- Complete header alignment for active PRDs as part of 4.1.0 migration

### 20260415094500 - WOLF
**What changed:**
- Created version 4.1.0 session documentation structure
- Populated CHANGELOG.md, status/open_questions.md, and plan.md per 4.1.0 rules

**Files:**
- docs/versions/4.1.0/CHANGELOG.md (new)
- docs/versions/4.1.0/status/open_questions.md (new)
- docs/versions/4.1.0/plan.md (new)

**Why:**
- Establish live hourly tracking for multi-actor 4.1.0 work

### 20260415095200 - AUGGIE
**What changed:**
- Added `CANONICAL_YEAR = "1026"` constant to `header_spec_v3_1.py` with trust-ladder arithmetic docstring
- Added relative path guard as check 0 in `validate_atoms_toon()`: rejects Unix absolute paths (`/`), Windows drive paths (`C:\`, `C:/`), and URLs (`http://`, `https://`) with `HDR_ATOMS_TOON_INVALID`
- Added `ATOMS_TOON_SUFFIX` and `CANONICAL_YEAR` to import block in `validate_lupopedia_headers_universal.py`
- `HDR_ATOMS_TOON_YEAR` error message now surfaces `CANONICAL_YEAR=1026` explicitly
- All 9 targeted test cases pass (null valid, path guard × 4, suffix check, year check × 2, calendar-year pass)

**Files:**
- scripts/lib/header_spec_v3_1.py
- scripts/validate_lupopedia_headers_universal.py

**Why:**
- Harden atoms_toon validator against absolute path and URL injection; make trust-ladder year a named constant rather than a hardcoded integer

---

## UTC HOUR 20260415 10

### 20260415100000 - AUGGIE
**What changed:**
- Modernized body (not header) of PRD 02 to align with v4.1.0 terminology and architecture
- Replaced `memory_key` → `memory_toon` and `dialog_transcript` → `transcript_jsonl` throughout body
- Inserted new section "Header & Memory Integration (v4.1.0)" immediately after "The Chat Is Not A Conversation" block
- Color strategy clarified: thread-based colors are **primary**; agent-based colors renamed from "Alternative" to "Agent-Specific Color Override (Optional)"
- Merged duplicate "Task Assignment System" and "Task Manager System" sections into single unified "Task System" section; both DB schemas retained; PHP polling code condensed; duplicate explanations removed
- Fixed `lupo_messages` → `lupo_dialog_messages` in `insert_message()` PHP reference
- Condensed Phases 1–7 implementation detail (~130 lines of checkbox lists) into single summary table with "historical planning record" note

**Files:**
- docs/prd/02_channels_discussions.md (body only; header preserved)

**Why:**
- Align canonical PRD with v4.1.0 field names, consolidate duplicated sections, and enforce color doctrine consistency

### 20260415100644 - AUGGIE
**What changed:**
- Migrated 14 canonical root `.md` files to 25-line v4.1.0 header format
- 4 files had 25-line blocks with legacy field names — field names corrected (`memory_key` → `memory_toon`, `dialog_transcript` → `transcript_jsonl`, `module` → `atoms_toon`): `README.md`, `AGENTS.md`, `CHANGELOG.md`, `lupopedia_quick_reference.md`
- 8 files had legacy FLIP/EDGES format (`lupopedia.edges:`) — full header block replaced: `CURSOR.md`, `CONTRIBUTING.md`, `ONBOARDING.md`, `QUICKSTART.md`, `ORGANIZATION.md`, `CHANGELOG_ARCHIVE.md`, `CAPTAIN_WOLFIE_WORKFLOW.md`, `TODO.md`
- 2 files had no header at all — new 25-line v4.1.0 headers prepended: `CLAUDE.md`, `GEMINI.md`
- Body content of all files preserved exactly; `when_updated` from existing headers preserved where available
- Created 10 new `.toon` sidecar files in `memory/development/canonical/1026/04/`
- Updated 4 existing `.toon` sidecar files (readme-root, agents-md, root-changelog, lupopedia-quick-reference) to v4.1.0 field names (`memory_toon`, `transcript_jsonl`)

**Files:**
- README.md, AGENTS.md, CLAUDE.md, CURSOR.md, GEMINI.md
- CONTRIBUTING.md, ONBOARDING.md, QUICKSTART.md, ORGANIZATION.md
- CHANGELOG.md, CHANGELOG_ARCHIVE.md, CAPTAIN_WOLFIE_WORKFLOW.md, TODO.md
- lupopedia_quick_reference.md
- memory/development/canonical/1026/04/readme-root.toon (updated)
- memory/development/canonical/1026/04/agents-md.toon (updated)
- memory/development/canonical/1026/04/root-changelog.toon (updated)
- memory/development/canonical/1026/04/lupopedia-quick-reference.toon (updated)
- memory/development/canonical/1026/04/claude-md.toon (new)
- memory/development/canonical/1026/04/cursor-md.toon (new)
- memory/development/canonical/1026/04/gemini-md.toon (new)
- memory/development/canonical/1026/04/contributing-md.toon (new)
- memory/development/canonical/1026/04/onboarding-md.toon (new)
- memory/development/canonical/1026/04/quickstart-md.toon (new)
- memory/development/canonical/1026/04/organization-md.toon (new)
- memory/development/canonical/1026/04/changelog-archive.toon (new)
- memory/development/canonical/1026/04/captain-wolfie-workflow.toon (new)
- memory/development/canonical/1026/04/todo-md.toon (new)

**Why:**
- Complete root documentation layer migration to v4.1.0; all root .md files now have canonical headers and memory sidecar files

### 20260415101800 - AUGGIE
**What changed:**
- Reviewed PRD 02 against `channels/index.php`; added OQ-03 through OQ-16 (14 questions) to `docs/versions/4.1.0/status/open_questions.md`
- Reviewed PRD 73 against `content/index.php` and `content-controller.php`; added OQ-17 through OQ-28 (12 questions)
- Total 26 open questions added this session covering: polling endpoint URL mismatch, task UI broken element, missing HERMES implementation, THOTH color exception, DialogMvpService vs global functions, message_type enum conflict, hardcoded color override, DOM reload threshold arithmetic, undocumented dependency, direct message rendering, thread navigation, lupo_agent_colors table status, channel_id denormalization, deprecated ?memory_key= URL param, dual slug-lookup functions, dual content-show functions, missing lupo_collection_tab_map schema, default_collection_id cross-namespace coupling, collection_tabs_for_chrome(0) undefined behavior, PRD 73 §8 aspirational section unmarked, lupo_rolls undefined, item_count maintenance gap, lupo_paths routing vs chrome ambiguity, content/index.php v2 header, and display_errors security leak

**Files:**
- docs/versions/4.1.0/status/open_questions.md (OQ-03 through OQ-28 added; when_updated bumped)

**Why:**
- Surface concrete implementation gaps between PRD specs and live code for prioritization and resolution

---

## UTC HOUR 20260415 08

### 20260415080730 - AUGGIE
**What changed:**
- Fixed remaining Python v4.1.0 violations across 4 files
- `new_validate_lupopedia_headers_universal.py`: `header_format_version` 4.1.x accepted as current; 4.0.x → WARN only; `memory_toon` and `atoms_toon` are primary validated fields; legacy `memory_key` and `module` accepted as read-only fallbacks with `HDR_*_RENAMED` warnings; argparse description updated to PRD 16 v4.1.0
- `generate_table_docs_from_toons.py`: all three emission paths in `normalize_recovered_frontmatter()` output canonical v4.1.0 field names (`memory_toon`, `atoms_toon`, `transcript_jsonl`)
- `validate_lupopedia_headers.py`: envelope error messages updated `v4.0.99` → `v4.1.0`; `module == ""` check replaced with `atoms_toon` empty-string check; `_legacy_map` drives WARN emission and missing-field tolerance
- `normalize_lupopedia_md_header_25.py`: default `target_version` → `"4.1.0"`; primary branch is `tv == "4.1.0"`; `"4.0.99"` accepted as legacy alias; `--target-version` choices updated; all descriptive strings updated

**Files:**
- scripts/new_validate_lupopedia_headers_universal.py
- scripts/generate_table_docs_from_toons.py
- scripts/validate_lupopedia_headers.py
- scripts/normalize_lupopedia_md_header_25.py

**Why:**
- Eliminate all remaining uses of deprecated field names in write/emit paths; new writes are v4.1.0 only; legacy names are read-only fallback

---

## UTC HOUR 20260415 04 (earlier)

### 2026041504xx00 - CURSOR / AUGGIE
**What changed:**
- Ongoing memory_key → memory_toon DB read migration preparation
- atoms_toon validator test scaffolding

**Why:**
- Runtime alignment with renamed DB column and new header schema
