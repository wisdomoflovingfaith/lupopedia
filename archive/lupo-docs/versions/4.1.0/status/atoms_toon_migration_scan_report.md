---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: status_report
  when_updated: "20260414000000"
  file_path_from_root: "lupo-docs/versions/4.1.0/status/atoms_toon_migration_scan_report.md"
  web_path: ""
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "staging"
  memory_key: "lupo-memory/development/staging/2026/04/atoms_toon_migration_scan_report.toon"
  artifact_type: status_report
  artifact_kind: migration_scan
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: "atoms_toon Migration Scan Report — Phase 0"
  status: "active"
  parent_pk_id: ""
  summary: "Phase 0 impact scan for module -> atoms_toon migration. Classifies all references before any edits."
  atoms_toon: null
  dialog_transcript: ""
---

# atoms_toon Migration Scan Report — Phase 0

**Date:** 20260414000000
**Actor:** Claude Code (116)
**Task:** Controlled `module` -> `atoms_toon` header field migration per `for_claude.md`
**Status:** Scan complete. No edits made prior to this report.

---

## 1. Scan Summary

| Metric | Count |
|--------|-------|
| Files scanned (active codebase, excluding archive/memory) | ~2 800 |
| Files with `module:` in header zone (first 50 lines) | 1 149 |
| Files with `atoms_toon` anywhere (pre-migration) | 2 |
| Files with non-null `module` header values | 7 (active) + 1 (historical) |
| Python scripts with `"module"` string references in code | 7 |
| Doctrine files with `module` in body/examples | 3 |
| Rule files with `module` references | 1 |

**`atoms_toon` pre-migration exists only in:**
- `for_claude.md` (the instruction file, not a header)
- `lupo-docs/versions/4.0.99/status/open_questions.md` (OQ-39, logged this session)

---

## 2. Files Grouped by Classification

### 2A. Active PRD Files (active_prd) — 120 files

All PRD files under `lupo-docs/prd/` that contain a Lupopedia header with `module:` in the header zone.

**Recommendation:** Migrate all. Replace `module: null` -> `atoms_toon: null` in header zone only.

**Non-null `module` values in active PRDs (6 files):**

| File | Current `module` value | Migration action |
|------|------------------------|-----------------|
| `lupo-docs/prd/00_root_constitutional_system_requirements.md` | `"constitution"` | Set `atoms_toon: null` (string is NOT a `.atoms.toon` path) |
| `lupo-docs/prd/08_core_agents_system.md` | `"agents"` | Set `atoms_toon: null` |
| `lupo-docs/prd/43_parent_child_trust_ladder.md` | `"architecture"` | Set `atoms_toon: null` |
| `lupo-docs/prd/81_agent_orchestration_chat.md` | `"orchestration"` | Set `atoms_toon: null` |
| `lupo-docs/prd/99_limits_for_everything_and_why.md` | `"governance"` | Set `atoms_toon: null` |
| `lupo-docs/prd/decisions/pseudocode/02_channels_discussions_constitutionpseudo.md` | `"orchestration"` | Set `atoms_toon: null` |

**Policy for non-null values:** The legacy string values (`"constitution"`, `"agents"`, etc.) are subsystem labels — they are NOT `.atoms.toon` file paths. They must NOT be reinterpreted as `atoms_toon` paths. Correct action: rename key, set value to `null`.

### 2B. Active Doctrine Files (active_doctrine) — 208 files

All files under `lupo-docs/doctrine/` that contain a Lupopedia header with `module:`.

**Recommendation:** Migrate all. Replace `module: null` -> `atoms_toon: null` in header zone only.

**Primary doctrine files to update manually (body text also references `module`):**

- `lupo-docs/doctrine/lupopedia-headers/lupopedia_headers_format.md`
  - Line 23: `module: null` in example block
  - Line 40: prose reference to `module` nullability rule and `HDR_MODULE_EMPTY_STRING`
  - Line 66: field table row for field 21
  - Line 113: example snippet
  - Line 163: v4.0.99 changelog mention
- `lupo-docs/doctrine/lupopedia-headers/validators_and_tooling.md`
  - Body references to `module` field behavior

### 2C. Validator / Parser Library Files (validator_parser_lib) — 1 file

`lupo-scripts/lib/header_spec_v3_1.py`

Code references (must change):

| Location | Current | Required Change |
|----------|---------|-----------------|
| `V4_HEADER_KEYS_ORDERED` tuple, index 20 | `"module"` | `"atoms_toon"` |
| `LEGACY_KEYS_V4` dict | (missing entry) | Add `"module": "atoms_toon"` |
| `apply_v4099_header_defaults()` | `out["module"] = None` | `out["atoms_toon"] = None` |
| `normalize_header_dict_for_validation()` | `elif k == "module": od[k] = None` | Handle `"atoms_toon"` + keep `"module"` as legacy rename |

`lupo-scripts/lib/header_validation.py`

Code references (must change):

| Location | Current | Required Change |
|----------|---------|-----------------|
| L252 | `header.get("module") == ""` | Add `atoms_toon` validation; keep legacy `module` compatibility check |
| L268 | null allowlist `"module"` | Replace with `"atoms_toon"`; add compatibility path for `"module"` |

### 2D. Validator Scripts (validator_script) — 4 files

- `lupo-scripts/validate_lupopedia_headers_universal.py` — L1451, L2038 (code refs)
- `lupo-scripts/validate_lupopedia_headers.py` — L52 field list, L143 empty-string check
- `lupo-scripts/new_validate_lupopedia_headers_universal.py` — L116, L129 field order list
- `lupo-scripts/add_lupopedia_header_to_file.py` — L24, L217, L297 (template emits `module: null`)
- `lupo-scripts/audit_namespace_headers.py` — L63 (code ref)

### 2E. Rule / Policy Files (rule_policy_file) — 1 file

`.cursor/rules/lupopedia-headers-mandatory.mdc`

- L13: prose reference `module` as example nullable field
- Header zone: `module: null` (if file has own Lupopedia header)

### 2F. Agent Artifact Files (agent_artifact) — 1 file

- `lupo-agents/thoth/system_prompt.txt` or similar — scan detected 1 agent file with `module:` in header zone

### 2G. Historical Version Artifacts (historical_version_artifact) — 44 files

Files under `lupo-docs/versions/` (excluding 4.1.0/ which is new).

**Non-null `module` in historical:**
- `lupo-docs/versions/4.0.99/status/session_report_20260412_collections_architecture_cascade.md`: `"status"` — intentional historical record

**Recommendation:** DO NOT migrate. Preserve as-is. These are frozen records.

### 2H. Memory Graph TOONs (memory_graph_toon)

Not scanned for `module:` — TOON files are JSON sidecars, not YAML headers. Not applicable.

### 2I. Other Files (other) — 775 files

Includes all PHP sources, JSON schemas, miscellaneous files outside classified directories. Most have `module:` in own Lupopedia headers.

**Recommendation:** Migrate headers in active PHP source files. Exclude JSON DB schemas and generated artifacts.

---

## 3. Recommended Edit Scope

**Automated migration (script):** All active Lupopedia-headered files with `module: null` in the header zone:
- `lupo-docs/prd/` (120 files)
- `lupo-docs/doctrine/` (208 files)
- `lupo-scripts/` (script own-headers only, not code body)
- Active PHP sources with own headers
- Agent files with own headers

**Manual spec/validator changes (Phase 1-3):**
- `lupo-scripts/lib/header_spec_v3_1.py`
- `lupo-scripts/lib/header_validation.py`
- `lupo-scripts/validate_lupopedia_headers_universal.py`
- `lupo-scripts/validate_lupopedia_headers.py`
- `lupo-scripts/new_validate_lupopedia_headers_universal.py`
- `lupo-scripts/add_lupopedia_header_to_file.py`
- `lupo-scripts/audit_namespace_headers.py`
- `lupo-docs/prd/16_lupopedia_headers.md` (body text + examples)
- `lupo-docs/doctrine/lupopedia-headers/lupopedia_headers_format.md` (body text + examples)
- `lupo-docs/doctrine/lupopedia-headers/validators_and_tooling.md`
- `.cursor/rules/lupopedia-headers-mandatory.mdc`

---

## 4. Files Intentionally Excluded from Automated Migration

| File / Path | Reason |
|-------------|--------|
| `lupo-docs/versions/4.0.99/` (44 files) | Historical version artifacts — frozen records |
| `lupo-docs/archive/` (if present) | Archive — must not be rewritten |
| `lupo-memory/` (TOON files) | JSON sidecars — do not have YAML header zone |
| `lupo-scripts/audit_toon_reserved_words.py` | References `"module"` as a reserved word name, not a field reference |
| `for_claude.md` | Instruction file — not a headered artifact |

---

## 5. Compatibility Risks

1. **THOTH verification anchor undefined (OQ-39):** `atoms_toon` is intended to eventually hold a THOTH verification anchor but THOTH has no current code reading any header field. Risk: low for this migration (field is nullable, no enforcement added). Risk level: deferred.

2. **`lupo_atoms` DB table name collision:** The `lupo_atoms` DB table is an unrelated key-value store. The `atoms_toon` field name may cause conceptual confusion but no technical collision exists.

3. **Non-null `module` values reinterpreted as null:** 6 active PRD files have non-null string values in `module` that are NOT `.atoms.toon` paths. These will be set to `null` in `atoms_toon`. The original subsystem labels are lost from the header (though present in git history). Risk: low — these values were unused by any current tooling.

4. **Legacy `module` in historical files:** 44 historical files retain `module:` field permanently. If a new validator runs `--strict` mode against historical files, it may emit `HDR_MODULE_DEPRECATED` warnings. Mitigation: validator must implement transitional acceptance with deprecation warning (not a hard error) for files in versioned/archive paths.

5. **Template update in `add_lupopedia_header_to_file.py`:** If the script runs against any file before its own update, it will emit a header with `module: null`. Must be updated first or simultaneously.

---

## 6. Open Questions

| OQ | Description | Status |
|----|-------------|--------|
| OQ-39 | THOTH verification anchor / `lupo_atoms` DB relationship undefined | open |
| (implicit) | Whether `.atoms.toon` existence should ever be required by validator | deferred — null allowed indefinitely until tooling exists |
| (implicit) | Whether `audit_toon_reserved_words.py` list should add `atoms_toon` as a reserved word | manual review recommended |

---

## 7. Pre-Migration State Confirmation

- `atoms_toon` field: DOES NOT EXIST in any active header as of scan date.
- `module` field: EXISTS in 1 149 headers (active + historical combined).
- All 120 active PRD files confirmed to have `module: null` or non-null string in header zone.
- Validator code has 7 `"module"` string references across 5 files.
- Doctrine body text has `module` references in 2 primary files.
- Rule file has 1 prose reference to `module`.

Scan complete. Proceeding to Phase 1 (spec update).
