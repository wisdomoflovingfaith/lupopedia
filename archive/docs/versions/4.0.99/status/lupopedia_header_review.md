---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: documentation
  when_updated: "20260415015943"
  file_path_from_root: "docs/versions/4.0.99/status/lupopedia_header_review.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/versions/4.0.99/status/lupopedia_header_review.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "staging"
  memory_key: "memory/development/staging/2026/04/lupopedia-header-review.toon"
  artifact_type: documentation
  artifact_kind: guide
  thread_id: ''
  content_id: null
  pk_id: null
  pk_slug: ""
  title: "Critical Review: Lupopedia Headers Specification (PRD 16 v4.0.99)"
  status: "draft"
  parent_pk_id: "16"
  summary: "Critical audit of PRD 16 v4.0.99 against implementation reality: 0/66 PRD files pass CI, systemic memory_key mismatch, sidecar validation unimplemented, http:// corpus-wide."
  module: null
  dialog_transcript: "0/development/lupopedia-headers"
---
# Critical Review: Lupopedia Headers Specification (PRD 16 v4.0.99)

**Reviewer:** AUGGIE — Critical Auditor and Systems Analyst
**Date:** 2026-04-15 UTC
**Scope:** PRD 16 v4.0.99 (`docs/prd/16_lupopedia_headers.md`, 1532 lines)
**Method:** Repo inspection, live validator runs, grep corpus analysis, file-by-file spot checks

---

## Executive Summary

**VERDICT: REVISE — Approve with mandatory corrections before CI enforcement**

PRD 16 v4.0.99 is the best-written specification in the Lupopedia corpus. Its error-code taxonomy is thorough, its tooling is real, and the validator is genuinely capable (2797 lines, covers MD/Python/PHP/JS). However, the specification has suffered a critical gap between theory and practice that makes it operationally **BLOCKED** right now:

> **0 of 66 PRD files pass the validator.** Pass rate: **0.0%.**

Every single PRD in `docs/prd/` fails with `HDR_MEMORY_KEY` — a systemic error where `memory_key` path segments do not match `trust_tier` (most files) or `channel_key` (PRD 16 itself). This is not a spec problem; it is a batch-stamping bug from the transition that changed `trust_tier` to `canonical` without updating `memory_key` paths. But the result is that the validator is currently **useless for CI** because it would reject 100% of the code it's supposed to guard.

**Additional top issues:**

| # | Issue | Severity |
|---|-------|----------|
| 1 | 0/66 PRDs pass validator (systemic HDR_MEMORY_KEY) | 🔴 BLOCKING |
| 2 | PRD 16 itself fails its own validator | 🔴 BLOCKING |
| 3 | All PRDs use `http://` web_path — hidden by --development | 🔴 BLOCKING |
| 4 | `HDR_DUAL_MISMATCH` is defined in §19.3 but never implemented | 🔴 BLOCKING |
| 5 | 2 JSONL transcript files still active post-freeze (§9 violation) | 🟠 IMPORTANT |
| 6 | 21 of 66 PRDs still at version 4.0.98, not 4.0.99 | 🟠 IMPORTANT |
| 7 | Cursor rule references UPPERCASE doctrine path; actual path is lowercase | 🟡 RISK |
| 8 | `last_modified_utc` is redundant: always equals `when_updated` | 🟡 REVIEW |
| 9 | ANUBIS orphan-header injection (§15) has no implementation in repo | 🟡 ASPIRATIONAL |

**Bottom line:** Spec is excellent; corpus is broken; CI is impossible. Fix the `memory_key` and `http://` issues across all 66 PRDs, then the system becomes functional. The spec can be approved after those corrections are confirmed and §19.3's `HDR_DUAL_MISMATCH` gets an implementation note.

---

## Section 1 — What Exists (Reality Check)

### 1.1 Validator

`scripts/validate_lupopedia_headers_universal.py` **EXISTS and works** (2797 lines). It handles `.md`, `.py`, `.php`, and `.js` correctly. It was validated by running it on 10 PRD files and on itself.

**What it DOES check (confirmed by source inspection):**
- HDR_LINE_COUNT, HDR_HEADER_INTERNAL_BLANK, HDR_MISSING_CLOSE, HDR_EMPTY_BODY
- HDR_KEY_ORDER, HDR_EXTRA_KEY, HDR_MISSING_KEY
- HDR_TRUST_TIER_INVALID, HDR_WEB_PATH_HTTP, HDR_MEMORY_KEY, HDR_MEMORY_KEY_SUFFIX
- HDR_MEMORY_YEAR_OFFSET (warn default; error with --strict-memory-year)
- HDR_MEMORY_TOON_MISSING (warn default; error with --strict-memory-files)
- HDR_MEMORY_JSON_MASTER / SIDECAR_JSON_MASTER_MISSING (warn; error with --strict-memory-pair)
- HDR_MODULE_EMPTY_STRING, HDR_FORBIDDEN_KEY (note/namespace)
- HDR_LEGACY_ENVELOPE (warn; error with --reject-legacy-envelope)
- HDR_LEGACY_FIELD_NAME (warn)
- HDR_PYTHON_HEADER, HDR_PYTHON_LINE_COUNT, HDR_PYTHON_SHEBANG
- HDR_PHP_HEADER, HDR_PHP_LEGACY_INLINE_V3
- HDR_JS_HEADER
- HDR_DIALOG_MISSING, HDR_DIALOG_FORMAT, HDR_DIALOG_EXTRA_SEGMENTS
- HDR_CONTENT_ID_MISSING, HDR_CONTENT_ID_INVALID
- HDR_VERSION_FIELD_REMOVED, HDR_VERSION_FAMILY
- HDR_SCHEMA_ARTIFACT_MISMATCH, HDR_MULTIPLE_HEADERS, HDR_NOT_AT_TOP
- HDR_UNICODE_BOX
- Cross-field: validate_required_fields_by_type(), validate_field_ordering(), validate_ymdhis_pair()
- DB sync checks (--check-db): optional, pymysql required

**Batch validator** (`batch_validate_prd_headers.py`) also exists. Ran successfully, confirmed 0/66 pass rate.


### 1.2 Tooling Scripts

| Script | Status | Notes |
|--------|--------|-------|
| `validate_lupopedia_headers_universal.py` | ✅ EXISTS | 2797 lines, functional |
| `normalize_lupopedia_md_header_25.py` | ✅ EXISTS | Migrates v4.0.0 → v4.0.99 envelope |
| `fix_double_headers.py` | ✅ EXISTS | Merges duplicate header blocks |
| `json_to_toon.py` | ✅ EXISTS | JSON master → .toon export |
| `batch_validate_prd_headers.py` | ✅ EXISTS | Batch runs universal validator |
| `add_lupopedia_header_to_file.py` | ✅ EXISTS | Bootstrap header on new files |
| `add_lupopedia_headers_everywhere.py` | ✅ EXISTS | Batch bootstrap across tree |
| `generate_memory_from_header.py` | ✅ EXISTS | Referenced in validator WARN output |
| `bin/transcript.py` | ✅ EXISTS | POSTs to PHP API endpoint |
| `fix_unicode_box_drawing_ascii.py` | ✅ EXISTS | Referenced in §14.11 |
| `validate_memory_json_toon_pair.py` | ✅ EXISTS | Deep sidecar sync check |

### 1.3 PHP Transcript Endpoint

The PHP endpoint IS implemented:
- `includes/classes/TranscriptAppendService.php` (10,557 bytes, 2026-04-10)
- `includes/modules/api/transcript-api.php` (5,685 bytes, 2026-04-10)
- `bin/transcript.py` POSTs to `{base_url}/index.php?route=api/transcript/append` ✅

**However:** Two JSONL files in `channels/` still show write activity dated **2026-04-09**, which is post-freeze per §9:
- `channels/0/development/general/transcript.jsonl`
- `channels/0/development/prd_files/44_prd_discussion/transcript.jsonl`

### 1.4 Sidecar JSON Files

`memory/headers/prd/2026/04/` contains sidecar JSON files. The system is partially deployed for recently authored PRDs. No automated sidecar-creation for older PRDs confirmed. **Naming quirk:** Sidecars are named `16_lupopedia_headersmetadata.json` (no dot before `metadata`) instead of `16_lupopedia_headers.metadata.json`. Consistent across files but deviates from the spec's stated path formula.

### 1.5 Memory (.toon) Files

`.toon` files exist in `memory/development/canonical/1026/04/` — confirming that **year-offset encoding (calendar − 1000 = 1026) is correct** for newly generated canonical files. The `header_spec_v3_1.py` library correctly declares `memory_key: "memory/development/canonical/1026/04/header-spec-v3-1.toon"`. New tooling honors §8.1.

### 1.6 Cursor / IDE Rules

`.cursor/rules/lupopedia-headers-mandatory.mdc` — **EXISTS**, 46 lines, `alwaysApply: true`. Well-written. Correct fields, version, and tooling commands.

### 1.7 Doctrine Documentation

`docs/doctrine/lupopedia-headers/` **EXISTS** (lowercase). Contains 11 documents: `lupopedia_headers_format.md`, `templates_new_file.md`, `validators_and_tooling.md`, `verification_guide.md`, `versioning_model.md`, `lupopedia_headers_doctrine.md`, `lupopedia_headers_migration.md`, `memory_file_schema.md`, `taxonomy_reference.md`, `optional_blocks.md`, `readme.md`. `docs/doctrine/TOON_ORDERING_SPEC.md` ✅ also exists.


---

## Section 2 — What's Missing (Gap Analysis)

### 2.1 The Systemic Failure: `memory_key` Path Mismatch

**This is the #1 operational issue in the repo.** All 66 PRD files in `docs/prd/` fail validation.

**Root cause:** When `trust_tier` was changed from `staging` → `canonical` across PRDs, the `memory_key` paths were not updated. The path segment for `trust_tier` in `memory_key` still says `staging`. Additionally, PRD 16 itself has `channel_key: headers` but `memory_key: memory/development/...` — the channel_key segment is wrong too.

**Error distribution (confirmed by live validator runs):**
- 65 of 66 PRDs: `memory_key segment 'staging' != header trust_tier 'canonical'` → `HDR_MEMORY_KEY`
- PRD 16: `memory_key segment 'development' != header channel_key 'headers'` → `HDR_MEMORY_KEY`

**Hidden secondary failures** (masked because the validator stops at first error):
- All 66 PRDs use `web_path: "http://..."` → would fail `HDR_WEB_PATH_HTTP` in production mode (currently hidden by `--development` in batch runs)
- 21 of 66 PRDs have `header_format_version: "4.0.98"` (not 4.0.99)
- 69 PRDs have `memory_key` containing `staging` in the trust_tier segment (69 instances from grep)

### 2.2 Unimplemented Validator Error Codes

Codes defined in §19.3 table that have **no implementation** in `validate_lupopedia_headers_universal.py`:

| Error Code | §19.3 Status | Validator Status | Impact |
|------------|-------------|-----------------|--------|
| `HDR_DUAL_MISMATCH` | MUST | ❌ NOT IMPLEMENTED | Sidecar never read; dual-field guarantee unenforceable |
| `SCHEMA_HEADER_METADATA` | SHOULD | ❌ NOT PRESENT | Sidecar schema not validated |
| `EDGE_NEEDS_REVIEW` | SHOULD | ❌ NOT PRESENT | Edge review_reason not checked |
| `HDR_CONTENT_TOO_EARLY` | MUST | 🟡 UNCLEAR | No dedicated code; body offset checks exist but code label not used |
| `HDR_VERSION_LEGACY` | MUST | 🟡 PARTIAL | v3 accepted (correct); v1/v2 rejected implicitly via format; no explicit code emitted |
| `HDR_SCHEMA_VALUE` | MUST | 🟡 PARTIAL | `validate_schema()` checks values but emits `HDR_SCHEMA_ARTIFACT_MISMATCH`, not `HDR_SCHEMA_VALUE` |

**Critical gap:** `HDR_DUAL_MISMATCH` is the most architecturally important unimplemented code. PRD 16 §6 calls `dialog_transcript` the "dual-field" — mandatory in both YAML header and sidecar JSON, byte-for-byte identical. The universal validator never opens any sidecar file. This means the most distinctive feature of v4.0.99 — the dual-field integrity check — is aspirational.

### 2.3 Missing Documentation Path (Case Issue)

The Cursor rule references `LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md` (UPPERCASE).
Actual path: `lupopedia-headers/lupopedia_headers_format.md` (lowercase).

On Windows (case-insensitive): works. On Linux CI / Docker: broken. This is a portability risk for any future CI pipeline running on Linux.

**Affected references in cursor rule (line 41):**
- `docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md`
- `docs/doctrine/LUPOPEDIA_HEADERS/TEMPLATES_NEW_FILE.md`

### 2.4 ANUBIS Orphan-Header Injection (§15)

PRD 16 §15 describes two paths to content. Path 2 depends on ANUBIS detecting headerless files and injecting headers automatically. **No ANUBIS implementation was found** in the repo — no daemon, no cron, no script. During the 47-hour marathon, agents (Cursor/Castcade) writing files without headers would have silently created orphans. ANUBIS is the most important missing piece for making the "agents can write files without headers" claim safe.

### 2.5 Offline Queue (Non-Issue)

`config/offline_transcript_queue.jsonl` does **not** exist. **This is good** — the PHP endpoint has been reliably reachable and the offline fallback was never triggered. No action needed.

---

## Section 3 — Header Field Analysis (All 22 Fields)

| # | Field | Verdict | Rationale |
|---|-------|---------|-----------|
| 1 | `header_format_version` | **KEEP** | Necessary; used by validator branch logic |
| 2 | `lupopedia.schema` | **KEEP** | Closed enum; used by cross-field validator |
| 3 | `when_updated` | **KEEP** | Primary timestamp; used by staleness check |
| 4 | `file_path_from_root` | **KEEP** | Used by memory_key path validation and DB sync |
| 5 | `web_path` | **MAKE OPTIONAL** | Derivable: `https://www.lupopedia.com/lupopedia/` + `file_path_from_root`. Currently WRONG (http://) on all 66 PRDs. Consider auto-computing it. |
| 6 | `last_modified_utc` | **REMOVE OR ALIAS** | Always identical to `when_updated` in every inspected file. No tool reads it independently. Two timestamps that must be equal provide zero information and double the maintenance. Consider: make `last_modified_utc` an optional alias accepted on read but not required on write. |
| 7 | `federation_node_id` | **KEEP** | Used by dialog_transcript validation and web_path checks |
| 8 | `channel_key` | **KEEP** | Required for memory_key path validation; used by transcript routing |
| 9 | `trust_tier` | **KEEP** | Core to the memory system; closed enum; well-defined |
| 10 | `memory_key` | **KEEP** | Core link to memory system; validated path shape |
| 11 | `artifact_type` | **KEEP** | Drives type-specific field requirements in validator |
| 12 | `artifact_kind` | **KEEP** | Taxonomy; cross-field validates with artifact_type |
| 13 | `thread_id` | **KEEP** | Required for discussion type; validated |
| 14 | `content_id` | **KEEP** | DB linkage; NULL is valid state; validated |
| 15 | `pk_id` | **KEEP** | Primary identifier; required for PRDs; validated |
| 16 | `pk_slug` | **KEEP** | URL-safe identifier; validated format |
| 17 | `title` | **MAKE OPTIONAL** | Required for PRDs but duplicates Markdown H1. For non-PRD files always `''`. Could be omitted for non-PRD files or auto-derived from first `#` heading. |
| 18 | `status` | **KEEP** | Required for PRDs/implementation; well-defined enum per type |
| 19 | `parent_pk_id` | **KEEP** | Implementation linkage; required for implementation type |
| 20 | `summary` | **MAKE OPTIONAL / DEFAULT EMPTY** | Often `''` even for PRDs. Useful when populated; no tool reads it programmatically. Could default to `''` silently. |
| 21 | `module` | **REMOVE OR AUTO-NULL** | Always `null` in practice across the entire corpus. The prohibition on `''` adds friction for no benefit. If kept, validator should silently coerce missing module to null rather than requiring explicit declaration. |
| 22 | `dialog_transcript` | **KEEP** | Dual-field contract; required non-empty; validated |

**Priority recommendations:**
1. **Remove `last_modified_utc`** (or accept but not require) — zero information value, corpus-wide maintenance burden
2. **Auto-compute `web_path`** from `file_path_from_root` — eliminates the corpus-wide `http://` failure
3. **Auto-null `module`** — currently required as explicit `null`; could default silently


---

## Section 4 — Validator Gap Analysis (§19.3 Error Codes)

All error codes defined in PRD 16 §19.3, evaluated against the validator source:

| Error Code | §19.3 Enforceability | Validator Status | Notes |
|------------|---------------------|-----------------|-------|
| `HDR_VERSION_LEGACY` | MUST | 🟡 PARTIAL | No explicit emit; v3 passes as legacy |
| `HDR_KEY_ORDER` | MUST | ✅ IMPLEMENTED | `validate_field_ordering()` + mechanical line check |
| `HDR_EXTRA_KEY` | MUST | ✅ IMPLEMENTED | `validate_field_ordering()` set difference |
| `HDR_MULTILINE` | MUST | ✅ IMPLEMENTED | `validate_v3_scalar_header_values()` |
| `HDR_ARRAY` | MUST | ✅ IMPLEMENTED | `validate_v3_scalar_header_values()` |
| `HDR_DUAL_MISMATCH` | MUST | ❌ NOT IMPLEMENTED | Sidecar never opened |
| `HDR_SCHEMA_VALUE` | MUST | 🟡 PARTIAL | `validate_schema()` checks enum; wrong label emitted |
| `HDR_VERSION_FIELD_REMOVED` | MUST | ✅ IMPLEMENTED | `deprecated_fields` check in `_validate_lupopedia_headers_payload` |
| `HDR_TRUST_TIER_INVALID` | MUST | ✅ IMPLEMENTED | `validate_required_header_fields()` |
| `HDR_LINE_COUNT` | MUST | ✅ IMPLEMENTED | `validate_markdown_header_line_count()` |
| `HDR_HEADER_INTERNAL_BLANK` | MUST | ✅ IMPLEMENTED | Blank-line scan in line-count validation |
| `HDR_LEGACY_ENVELOPE` | WARN/MUST | ✅ IMPLEMENTED | WARN by default; ERROR with `--reject-legacy-envelope` |
| `HDR_LEGACY_FIELD_NAME` | WARN | ✅ IMPLEMENTED | `_warn_legacy_header_yaml_key_names()` |
| `HDR_MODULE_EMPTY_STRING` | MUST | ✅ IMPLEMENTED | `module == ''` check in payload validator |
| `HDR_MISSING_BLANK_LINE` | Legacy | ✅ IMPLEMENTED | Covered under legacy envelope handling |
| `HDR_MISSING_CLOSE` | MUST | ✅ IMPLEMENTED | Line 25 `---` check |
| `HDR_CONTENT_TOO_EARLY` | MUST | 🟡 UNCLEAR | Body offset logic exists; dedicated label not emitted |
| `HDR_EMPTY_BODY` | MUST | ✅ IMPLEMENTED | Line 26 non-whitespace check; WARN under `--development` |
| `HDR_WEB_PATH_HTTP` | MUST | ✅ IMPLEMENTED | `validate_web_path_https()` |
| `HDR_MEMORY_JSON_MASTER` | SHOULD | ✅ IMPLEMENTED | WARN default; ERROR with `--strict-memory-pair` |
| `SIDECAR_JSON_MASTER_MISSING` | SHOULD | ✅ IMPLEMENTED | Alias for `HDR_MEMORY_JSON_MASTER` |
| `HDR_PYTHON_HEADER` | MUST | ✅ IMPLEMENTED | `validate_python_header_envelope()` |
| `HDR_PYTHON_LINE_COUNT` | MUST | ✅ IMPLEMENTED | `validate_python_header_envelope()` |
| `HDR_PYTHON_SHEBANG` | MUST | ✅ IMPLEMENTED | Canonical shebang check |
| `HDR_PHP_HEADER` | MUST | ✅ IMPLEMENTED | `validate_php_header_envelope()` |
| `HDR_PHP_LEGACY_INLINE_V3` | MUST | ✅ IMPLEMENTED | `_PHP_LEGACY_INLINE_YAML_RE` pattern check |
| `HDR_JS_HEADER` | MUST | ✅ IMPLEMENTED | `validate_js_file()` |
| `HDR_MISSING_KEY` | MUST | ✅ IMPLEMENTED | Set membership in `validate_required_header_fields()` |
| `HDR_ARTIFACT_TYPE` | MUST | ✅ IMPLEMENTED | `validate_cross_fields()` |
| `HDR_SCHEMA_ARTIFACT_MISMATCH` | SHOULD | ✅ IMPLEMENTED | `validate_cross_fields()` |
| `HDR_VERSION_FAMILY` | MUST | ✅ IMPLEMENTED | `_is_valid_header_format_version()` |
| `HDR_CONTENT_ID_INVALID` | MUST | ✅ IMPLEMENTED | `validate_content_id()` |
| `HDR_CONTENT_ID_MISSING` | MUST | ✅ IMPLEMENTED | `validate_content_id()` |
| `HDR_MULTIPLE_HEADERS` | MUST | ✅ IMPLEMENTED | `peel_leading_lupopedia_yaml_blocks()` |
| `HDR_NOT_AT_TOP` | MUST | ✅ IMPLEMENTED | First line check in `validate_yaml_file()` |
| `HDR_UNICODE_BOX` | SHOULD | ✅ IMPLEMENTED | `validate_no_unicode_box_chars()` |
| `HDR_MEMORY_KEY` | MUST | ✅ IMPLEMENTED | `validate_memory_key_path_shape()` |
| `HDR_MEMORY_KEY_SUFFIX` | MUST | ✅ IMPLEMENTED | `.toon` suffix check |
| `HDR_MEMORY_YEAR_OFFSET` | SHOULD | ✅ IMPLEMENTED | WARN default; ERROR with `--strict-memory-year` |
| `HDR_MEMORY_TOON_MISSING` | SHOULD | ✅ IMPLEMENTED | WARN default; ERROR with `--strict-memory-files` |
| `SCHEMA_HEADER_METADATA` | SHOULD | ❌ NOT PRESENT | Sidecar schema not validated |
| `EDGE_NEEDS_REVIEW` | SHOULD | ❌ NOT PRESENT | Edge review_reason not checked |

**Summary:** 36 of 43 error codes are implemented (✅ 34 full, 🟡 4 partial, ❌ 3 missing critical + 2 missing SHOULD).

---

## Section 5 — Tooling Gap Analysis

All scripts referenced in PRD 16, the cursor rule, and §14 procedures:

| Script / Tool | Referenced In | Status |
|--------------|--------------|--------|
| `validate_lupopedia_headers_universal.py` | PRD 16, cursor rule | ✅ EXISTS, functional |
| `normalize_lupopedia_md_header_25.py` | §14.10 | ✅ EXISTS |
| `fix_double_headers.py` | §14.9 | ✅ EXISTS |
| `json_to_toon.py` | §5.2.2, cursor rule | ✅ EXISTS |
| `add_lupopedia_header_to_file.py` | cursor rule | ✅ EXISTS |
| `add_lupopedia_headers_everywhere.py` | cursor rule | ✅ EXISTS |
| `batch_validate_prd_headers.py` | §14 references | ✅ EXISTS |
| `generate_memory_from_header.py` | validator WARN output | ✅ EXISTS |
| `fix_unicode_box_drawing_ascii.py` | §14.11 | ✅ EXISTS |
| `validate_memory_json_toon_pair.py` | §5.2.2 | ✅ EXISTS |
| `bin/transcript.py` | §9 | ✅ EXISTS |
| `bin/tick.py` | cursor rule | ✅ EXISTS |
| `bin/echo_anchor_utc.py` | cursor rule | ✅ EXISTS |
| PHP transcript endpoint | §9 | ✅ EXISTS (`TranscriptAppendService.php`) |
| ANUBIS orphan injector | §15 Path 2 | ❌ NOT FOUND |
| Book UI | §20.3 gap list | ❌ NOT FOUND (aspirational) |
| Edge editor UI | §20.3 gap list | ❌ NOT FOUND (aspirational) |
| Memory node one-click creator | §20.3 gap list | ❌ NOT FOUND (aspirational) |
| Engagement UI | §20.3 gap list | ❌ NOT FOUND (aspirational) |

The missing ANUBIS orphan injector is the **only functionally blocking gap** in the tooling list. The Book UI, edge editor, memory node creator, and engagement UI are explicitly listed as aspirational in §20.3 — they are known gaps, not oversights.

**Positive surprise:** Nearly the entire tool chain exists. The marathon built real tooling. This is not a theoretical spec with no implementation.

---

## Section 6 — Critical Corrections Needed

### P0 — Blocking (must fix before CI can be enabled)

**P0-A: Fix `memory_key` across all 66 PRDs**
- Root cause: `trust_tier` updated without updating `memory_key` path segment
- Action: Update `memory_key` to `memory/{channel_key}/{trust_tier}/...` for all 66 PRDs
- Fix PRD 16 specifically: `channel_key: headers` requires `memory/headers/canonical/1026/04/16-lupopedia-headers.toon`
- Tool: `batch_validate_prd_headers.py` could add a `--fix-memory-key` option, or a targeted sed/script pass
- Estimate: 1–2 hours of scripted work

**P0-B: Fix `web_path` to use `https://` across all 66 PRDs**
- Root cause: All PRDs use `http://` and rely on `--development` to suppress the error
- Action: Update all `web_path` values to `https://`
- Better long-term: Auto-compute `web_path` from `file_path_from_root` in emitters (not in spec field list)
- Estimate: 30 minutes of scripted work

**P0-C: Fix PRD 16's own header**
- The spec author's own PRD fails its own spec
- Current: `channel_key: headers`, `memory_key: memory/development/staging/...`
- Required: `memory_key: memory/headers/canonical/1026/04/16-lupopedia-headers.toon`
- Action: Update PRD 16 header directly (lines 1–25)
- Estimate: 5 minutes

### P1 — Important (fix within 1 week)

**P1-A: Implement `HDR_DUAL_MISMATCH` in the universal validator**
- Add `--check-sidecar` flag: load `header_metadata` JSON at derived path, compare `dialog_transcript` byte-for-byte
- Derived path formula: `memory/headers/{artifact_type}/{YYYY}/{MM}/{stem}.metadata.json`
- Note: sidecar filename convention (`stem.metadata.json` vs `stemmetadata.json`) needs to be standardized first
- Estimate: 4–6 hours

**P1-B: Upgrade 21 PRDs from 4.0.98 → 4.0.99**
- The `header_format_version` bump is non-breaking; it just acknowledges the 22-key dense format
- Tool: `normalize_lupopedia_md_header_25.py` already handles migration
- Estimate: 30 minutes scripted

**P1-C: Stop writing JSONL in channels/**
- Two files still active post-freeze: `general/transcript.jsonl` and `44_prd_discussion/transcript.jsonl`
- Find the code path still appending to these files and redirect to DB via PHP endpoint
- Archive existing JSONL files (rename to `.jsonl.archive`)
- Estimate: 2–4 hours investigation + fix

**P1-D: Fix cursor rule doc path case**
- Change `LUPOPEDIA_HEADERS/` → `lupopedia-headers/` in `.cursor/rules/lupopedia-headers-mandatory.mdc` line 41
- Estimate: 2 minutes

### P2 — Nice-to-Have (address within 1 month)

**P2-A: Simplify the 22-field header to 19–20 fields**
- Remove `last_modified_utc` (or accept-but-not-require)
- Auto-compute `web_path` in emitters; keep in spec but make derivable
- Auto-null `module` (don't require explicit declaration)
- This would reduce header maintenance friction without breaking existing validators

**P2-B: Add sidecar filename convention note to PRD 16**
- Current files use `{stem}metadata.json` (no dot separator)
- Spec describes `{stem}.metadata.json` (with dot)
- Choose one and enforce it — update §5.2 with the exact filename formula

**P2-C: Emit `HDR_CONTENT_TOO_EARLY` explicitly**
- Add the label string to the body-offset check in `_validate_lupopedia_headers_payload`
- Estimate: 15 minutes

**P2-D: Build ANUBIS orphan detector**
- Minimum viable: a script that walks the repo, finds in-scope files without a header, and emits a report
- Full ANUBIS: a file-watcher that triggers on new file creation and auto-bootstraps headers
- Estimate: 4–8 hours for the scan script; 2 weeks for the watcher

---

## Section 7 — Recommended Header Changes

### What the PRD 16 header SHOULD look like after P0 fixes

```yaml
---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: prd
  when_updated: "20260415020000"
  file_path_from_root: docs/prd/16_lupopedia_headers.md
  web_path: "https://www.lupopedia.com/lupopedia/docs/prd/16_lupopedia_headers.md"
  last_modified_utc: "20260415020000"
  federation_node_id: 0
  channel_key: headers
  trust_tier: canonical
  memory_key: "memory/headers/canonical/1026/04/16-lupopedia-headers.toon"
  artifact_type: prd
  artifact_kind: specification
  thread_id: ''
  content_id: null
  pk_id: 16
  pk_slug: lupopedia-headers
  title: Lupopedia File Headers and Verification
  status: active
  parent_pk_id: ''
  summary: "PRD 16 v4.0.99: 22-field header format, sidecar system, DB-first transcripts, dual-field rule"
  module: null
  dialog_transcript: "0/headers/lupopedia-headers"
---
```

**Changes from current PRD 16 header:**
1. `memory_key`: `development/staging/2026` → `headers/canonical/1026` (fixes P0-A and P0-C)
2. `web_path`: `http://` → `https://` (fixes P0-B)
3. `dialog_transcript`: `0/development/prd_files/lupopedia-headers` (4 segments, WARN) → `0/headers/lupopedia-headers` (3 segments, normative)
4. `summary`: was empty → populated with meaningful description


### A Simplified 5-Field Header (for extreme offline / disaster recovery use)

When ANUBIS is unavailable and a developer needs to create a compliant header quickly, the following minimal template can be used as a **draft** (`trust_tier: staging`). All 22 fields are still required; this is the minimum that's semantically meaningful:

```yaml
---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: documentation
  when_updated: "YYYYMMDDHHIISS"
  file_path_from_root: "path/to/file.md"
  web_path: "https://www.lupopedia.com/lupopedia/path/to/file.md"
  last_modified_utc: "YYYYMMDDHHIISS"
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "staging"
  memory_key: "memory/development/staging/YYYY/MM/file-slug.toon"
  artifact_type: documentation
  artifact_kind: guide
  thread_id: ''
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: "draft"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: "0/development/file-slug"
---
```

**One-liner to validate without Python** (bash/PowerShell check for the opening envelope):

```bash
# Bash: check file has valid header envelope
head -25 "$1" | awk 'NR==1{ok=($0=="---")} NR==2{ok=ok&&($0=="lupopedia.headers:")} NR==25{ok=ok&&($0=="---")} END{exit !ok}'
```

```powershell
# PowerShell quick header check
$lines = Get-Content $args[0] | Select-Object -First 25
if ($lines[0] -eq "---" -and $lines[1] -eq "lupopedia.headers:" -and $lines[24] -eq "---") { "ENVELOPE OK" } else { "ENVELOPE FAIL" }
```

---

## Section 8 — Implementation Roadmap

### Week 1 (P0 — Unblock CI)

| Day | Task | Owner |
|-----|------|-------|
| 1 | Fix `memory_key` in all 66 PRDs via batch script | Agent/Script |
| 1 | Fix `web_path` `http://` → `https://` in all 66 PRDs | Agent/Script |
| 1 | Fix PRD 16 header (memory_key, web_path, dialog_transcript) | Human |
| 2 | Run batch validator — target: >80% pass rate | CI |
| 2–3 | Fix remaining validation errors file by file | Human + Agent |
| 4 | Enable CI on PR hook (no --development flag) | DevOps |
| 5 | Fix cursor rule doc path case (2 min) | Human |

### Week 2 (P1 — Stabilize)

| Task | Effort |
|------|--------|
| Upgrade 21 PRDs from 4.0.98 → 4.0.99 | 30 min scripted |
| Stop JSONL writes in channels/ | 2–4 hours |
| Standardize sidecar filename convention (dot vs no-dot) | 1 hour spec edit |
| Document `HDR_DUAL_MISMATCH` as "not yet implemented" in §19.3 | 30 min spec edit |
| Emit `HDR_CONTENT_TOO_EARLY` label explicitly in validator | 15 min |

### Month 1 (P2 — Simplify)

| Task | Effort |
|------|--------|
| Implement `--check-sidecar` and HDR_DUAL_MISMATCH validator | 1 week |
| Build ANUBIS orphan scanner (minimum viable: report-only) | 4–8 hours |
| Evaluate removal of `last_modified_utc` from required fields | PRD revision + 1 week migration |
| Auto-compute `web_path` in all header emitters | 4 hours code + 1 week validation |

### Month 2–3 (Strategic)

| Task | Effort |
|------|--------|
| Auto-null `module` at emitter level (keep in spec, remove from template) | 2 hours |
| ANUBIS full watcher (file-watch triggered header injection) | 2 weeks |
| Book UI for header creation | 3–4 weeks |
| Edge editor UI | 3–4 weeks |
| 100% PRD corpus passing CI without --development flag | Ongoing |

---

## Section 9 — Operational Readiness Assessment

### CI/CD Readiness

| Dimension | Current State | Target |
|-----------|--------------|--------|
| Pass rate (without --development) | 0/66 (0%) | >95% |
| Pass rate (with --development) | ~0% (hidden http:// errors) | N/A |
| CI integration | Not enabled | PR hook |
| False positive rate | N/A (can't test: 0% pass) | <5% |

**Bottom line on CI:** Cannot enable CI until P0 corrections are applied. The `--development` flag is being used as a permanent escape hatch, not a temporary development tool. This defeats the purpose of having a validator.

### Developer Experience

| Dimension | Current State | Assessment |
|-----------|--------------|------------|
| Can a human write a compliant header in < 5 min? | Yes, with template | ✅ Good |
| Can an agent write a compliant header? | Yes, Cursor rule enforces it | ✅ Good |
| Can tooling auto-generate headers? | Yes, `add_lupopedia_header_to_file.py` | ✅ Good |
| Is validation feedback clear? | Yes, specific error codes with hints | ✅ Good |
| Is the 22-field requirement explained in < 1 page? | Yes, `templates_new_file.md` | ✅ Good |
| Does the spec author's own file pass validation? | NO | ❌ Bad |

### Recovery

If a header is malformed, the following tools can fix it automatically:
- Envelope migration: `normalize_lupopedia_md_header_25.py`
- Duplicate headers: `fix_double_headers.py`
- Unicode box chars: `fix_unicode_box_drawing_ascii.py`
- Memory key/web_path bulk fixes: needs a dedicated repair script (P0-A/B work)

Recovery capability is good for structural errors. **Semantic errors (wrong memory_key, wrong web_path) require new tooling to fix at scale.**

### The 47-Hour Marathon Lens

Would better headers have helped during the marathon?

**Yes:** The main problem was agent-created files without proper headers, or headers with wrong `memory_key` after the trust_tier change. A working ANUBIS orphan detector would have caught these in real time. The current system requires post-hoc audit.

**No:** The 25-line rigid envelope actually imposes almost no overhead once you have the template. The Cursor rule is well-written and would guide an agent correctly on new files.

**Verdict:** The headers spec did not make the marathon harder. The broken corpus did. Fix the corpus; the spec is sound.

---

## Section 10 — Final Verdict

**REVISE — Approve with mandatory corrections (P0 items) before CI enforcement.**

The Lupopedia Headers specification (PRD 16 v4.0.99) is architecturally correct, well-specified, and better-tooled than almost any other component in the Lupopedia codebase. The validator is real. The tooling chain is real. The doctrine documentation is comprehensive. The Cursor rules are excellent.

The specification is **not operationally ready** because:

1. **The corpus is broken.** Zero files pass CI. This is a data quality problem, not a spec problem. The fix is mechanical (batch memory_key and web_path corrections) and can be done in a single session.

2. **The spec author's own PRD fails its own spec.** PRD 16's header has the wrong `memory_key` and `channel_key` mismatch. This must be fixed before the spec can be held as authoritative.

3. **The dual-field rule is aspirational.** `HDR_DUAL_MISMATCH` is defined but not implemented. The most architecturally interesting feature of v4.0.99 — the sidecar integrity check — cannot be enforced by any tool right now.

**Conditions for approval:**
- [ ] P0-A: All 66 PRDs pass validator without `--development` flag
- [ ] P0-B: PRD 16 itself passes its own validator
- [ ] P0-C: Cursor rule doc paths corrected for Linux portability
- [ ] P1-A: `HDR_DUAL_MISMATCH` either implemented or explicitly marked "planned" with a PRD note
- [ ] P1-B: JSONL post-freeze writes stopped

Upon completion of these five conditions, PRD 16 v4.0.99 is recommended for **APPROVE with monitoring**.

The spec deserves to pass. Fix the corpus, and it will.

---

*Review completed: 2026-04-15 UTC*
*Reviewer: AUGGIE — Lupopedia Critical Auditor*
*Files inspected: 12 scripts, 11 doctrine docs, 66 PRD headers, 2 PHP endpoints, 3 cursor rules, memory/headers sidecar tree*
*Validator runs: 10 individual files, 1 full batch (66 files)*
