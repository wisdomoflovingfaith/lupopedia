---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: status_report
  when_updated: "20260415050000"
  file_path_from_root: "lupo-docs/versions/4.1.0/status/atoms_toon_migration_change_report.md"
  web_path: ""
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "staging"
  memory_key: "lupo-memory/development/staging/2026/04/atoms_toon_migration_change_report.toon"
  artifact_type: status_report
  artifact_kind: migration_report
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: "atoms_toon Migration Change Report — Phases 1–6"
  status: "complete"
  parent_pk_id: ""
  summary: "Complete change report for module -> atoms_toon migration (PRD 16 field 21). 321 files migrated, 6 flagged, 0 errors."
  atoms_toon: null
  dialog_transcript: ""
---

# atoms_toon Migration Change Report — Phases 1–6

**Date:** 20260415050000  
**Actor:** Augment Agent (continuing from Claude Code 116 crash at Phase 0)  
**Phase 0 predecessor:** `atoms_toon_migration_scan_report.md`

---

## Executive Summary

| Metric | Value |
|--------|-------|
| Files migrated (`module: null` → `atoms_toon: null`) | **321** |
| Files flagged (non-null `module` — manual review required) | **6** |
| Files already OK (`atoms_toon` already present) | **4** |
| Files skipped (no `module` in header zone) | **550** |
| Errors | **0** |
| `module: null` remaining in in-scope files | **0** |
| `atoms_toon:` in PRD/doctrine | **140+** |
| PRD 16 `atoms_toon` references | **25** |

---

## Phase 1 — PRD 16 Spec Update (`lupo-docs/prd/16_lupopedia_headers.md`)

| Change | Details |
|--------|---------|
| Own header field 21 | `module: null` → `atoms_toon: null` |
| Changelog entry added | v4.0.99 / 2026-04-15: field 21 rename, THOTH anchor, migration plan |
| §3 Definitions | Added `atoms_toon` and `THOTH` term definitions |
| §4.1 null-semantics rule | Replaced `module:` rule with `atoms_toon:` rule + deprecated `module` note |
| §4.2 key presence text | `module` → `atoms_toon` in normative key-presence prose |
| Field 21 definition | Full replacement: subsystem label → atoms sidecar pointer definition |
| §4.4 Forbidden section | Updated examples: `module: ''` → `atoms_toon: ''`; namespace → atoms_toon |
| §12 validator rule | Updated `module null semantics` → `atoms_toon null/suffix semantics` |
| §19.3 Error codes | Added `HDR_ATOMS_TOON_SUFFIX`, `HDR_MODULE_DEPRECATED`; updated `HDR_MODULE_EMPTY_STRING` |
| §14 migration note | `summary/module` → `summary/atoms_toon` |
| All example headers | `module: null` → `atoms_toon: null` in examples at lines 676, 709, 826, 861, 1972, 2004+ |
| TOON file edges added | `atoms_toon_schema.md` (references), `migrate_module_to_atoms_toon.py` (implements) |

---

## Phase 2 — Validator Update

### `lupo-scripts/lib/header_spec_v3_1.py`

| Change | Details |
|--------|---------|
| Own header | `module: null` → `atoms_toon: null` |
| `V4_HEADER_KEYS_ORDERED` | Position 21: `"module"` → `"atoms_toon"` |
| `LEGACY_KEYS_V4` | Added: `"module": "atoms_toon"` |
| `apply_v4099_header_defaults()` | `"module" not in out` → `"atoms_toon" not in out` |
| `normalize_header_dict_for_validation()` | `elif k == "module"` → `elif k == "atoms_toon"` |

### `lupo-scripts/lib/header_validation.py`

| Change | Details |
|--------|---------|
| Own header | `module: null` → `atoms_toon: null` |
| Empty-string check | `header.get("module") == ""` → `atoms_toon` check + `HDR_ATOMS_TOON_SUFFIX` |
| Legacy `module` check | Added: warn when `module` survives normalization (`HDR_MODULE_DEPRECATED`) |
| Field skip in key order | `if field == "module"` → `if field == "atoms_toon"` |
| `validate_atoms_toon()` | **NEW function** — validates null/suffix/.atoms.toon semantics |
| ATOMS_TOON_SUFFIX constant | `".atoms.toon"` |

### `lupo-scripts/validate_lupopedia_headers_universal.py`

| Change | Details |
|--------|---------|
| Own header | `module: null` → `atoms_toon: null` |
| `key == "module"` skip | → `key == "atoms_toon"` |
| Forbidden key error message | `summary/module` → `summary/atoms_toon` |
| Empty-string check | Added `atoms_toon` check with `HDR_ATOMS_TOON_SUFFIX` |
| Legacy `module` empty-string | Kept as backward compat check |
| `module` without `atoms_toon` | Added `HDR_MODULE_DEPRECATED` WARN |

---

## Phase 3 — Doctrine / Rule Files

| File | Changes |
|------|---------|
| `lupopedia_headers_format.md` | Own header, field 21 table row, null-vs-empty description, v4.0.99 change note |
| `validators_and_tooling.md` | Own header, validator behavior section: `atoms_toon` field 21 rule |
| `.cursor/rules/lupopedia-headers-mandatory.mdc` | §4.2 key list (`module` → `atoms_toon`), field 6/21 guidance line |

---

## Phase 4 — Atoms TOON Schema (New File)

**Created:** `lupo-docs/doctrine/lupopedia-headers/atoms_toon_schema.md`

Content: purpose, THOTH relationship (future/not implemented), current validation rules, example, related files. Status: **DRAFT — NOT ENFORCED**.

---

## Phase 5 — Controlled Migration

**Script:** `lupo-scripts/migrate_module_to_atoms_toon.py` (new, ~170 lines)

- Header-zone-only (first 50 lines)
- `module: null` → `atoms_toon: null` (exact 2-space indent match)
- Non-null `module` values → FLAGGED (not auto-converted)
- `--write` required (safe dry-run by default)

**Results (--write applied):** 321 migrated · 6 flagged · 4 already OK · 0 errors

---

## Phase 6 — Verification

| Check | Result |
|-------|--------|
| `module: null` remaining in in-scope files | ✅ **0** |
| `atoms_toon:` in PRD/doctrine | ✅ **140+** |
| `atoms_toon` in `V4_HEADER_KEYS_ORDERED` | ✅ confirmed |
| `"module": "atoms_toon"` in `LEGACY_KEYS_V4` | ✅ confirmed |
| `validate_atoms_toon()` function exists | ✅ confirmed |
| PRD 16 `atoms_toon` references | ✅ **25** |
| `atoms_toon_schema.md` exists | ✅ confirmed |
| `migrate_module_to_atoms_toon.py` exists | ✅ confirmed |
| Cursor rule updated | ✅ confirmed |

---

## 6 Flagged Files — Require Manual Decision (OQ-40)

These files have non-null `module` values that cannot be auto-converted (subsystem labels, not file paths). Validators emit `HDR_MODULE_DEPRECATED` WARN — NOT blocked. See `open_questions.md` OQ-40.

| File | Old value |
|------|-----------|
| `lupo-docs/prd/00_root_constitutional_system_requirements.md` | `module: "constitution"` |
| `lupo-docs/prd/08_core_agents_system.md` | `module: "agents"` |
| `lupo-docs/prd/43_parent_child_trust_ladder.md` | `module: "architecture"` |
| `lupo-docs/prd/81_agent_orchestration_chat.md` | `module: "orchestration"` |
| `lupo-docs/prd/99_limits_for_everything_and_why.md` | `module: "governance"` |
| `lupo-docs/prd/decisions/pseudocode/02_channels_discussions_constitutionpseudo.md` | `module: "orchestration"` |

**Options:** (a) set `atoms_toon: null` and drop the label, (b) set `atoms_toon: null` + comment, (c) create real `.atoms.toon` sidecars (requires OQ-39 resolution first).

---

## Out of Scope (Expected)

- `lupo-docs/versions/**` (status, open_questions — manually updated)
- `lupo-scripts/*.py` without Lupopedia headers (tools, helpers, PHP, SQL)
- `lupo-agents/*/system_prompt.txt` (most skipped — no `module:` in header zone)
- `.atoms.toon` file creation (future — requires OQ-39 first)
- THOTH integration (future — requires OQ-39 first)
