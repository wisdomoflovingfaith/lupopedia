---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/16_E_LUPOPEDIA_HEADERS_MIGRATION.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/16_E_LUPOPEDIA_HEADERS_MIGRATION.md"
  status: active
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/headers/canonical/1026/04/lupopedia-headers.toon
  atoms_toon: lupo-memory/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/headers/lupopedia-headers
  artifact_type: prd
  artifact_kind: guide
  channel_key: headers
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_16_B_16_C_16_E
  title: "PRD 16: Lupopedia Headers (Migration Guide)"
  summary: "Migration guide for Lupopedia header contract at version 4.1.4. Covers legacy field aliases, per-file migration workflow, and content_slug removal."
---
<!-- ASCII_ART_BLOCK -->
. /#\ .................../#\ . .------------- LUPOPEDIA Semantic Operating System ------------.
/###\................../###\ .| -------------------------------------------------------------|
/#####\ . ######### . ./#####\ | A two-dimensional, finite, constitutional PRD documentation  |
############################## | architecture that lets docs build software. PRDs reference   |
############################## | other PRDs, forming clusters that define behavior, truth,    |
. ####### ########## ####### .| limits, and system identity. Each file carries a header that |
######## o ###### o ######### .| records the exact prd_cluster (reading order), the full     |
########## ###### ########### .| transcript_jsonl dialog, and atoms_toon for canonical truth,|
. ########################## . | ensuring deterministic lineage and reproducibility.         |
. . . . ############### . . . .| - Eric Robin Gerdes ( Captain WOLFIE ) lupopedia@gmail.com  |
. . . . ####|-----|#### . . . .----------------------------------------------------------------
. . . . ####|_____|#### . . . .| https://www.lupopedia.com/                                 |
. . . . ############# . . . . .--------------------------------------------------------------.
<!-- /ASCII_ART_BLOCK -->

<!-- HUMAN_SEMANTIC -->
This file belongs to:
??? PRD Group 16 (Identity Layer ??? Headers, Atoms, Migration)
??? Cluster 16ABCD
??? Channel: headers
??? No default collection yet

See also:
??? 00_A_FORBIDDEN_AND_WHY.md
??? 00_B_ROOT_CONSTITUTIONAL_SYSTEM_REQUIREMENTS.md
??? PRD 86 ??? Immune system (no drift, no entropy)
??? Order of Operations: PRD ??? Schema ??? Mockups ??? Code
<!-- /HUMAN_SEMANTIC -->

# PRD 16 Migration Guide

## 1. Principle

Do not mass-migrate headers blindly. Migrate per file with validation.

**Note on Future Automation:** Starting with version 4.1.4, the manual "per-file" migration workflow is deprecated in favor of automated validator-led enforcement. Future header schema updates and alias retirements will be handled primarily by the universal validator's `--fix` capabilities.

## 2. Legacy alias policy

All legacy field names are accepted by the validator and emitted as warnings. Migrate per file.

### 2.1 v4.1.1 ??? pk_* ??? content_* (HDR_PK_LEGACY_ALIAS)

| Legacy field | Canonical field | Accepted until | Rejected from | Status |
|---|---|---|---|---|
| `pk_id` | `content_id` | 4.1.2 (warning only) | 4.1.3 (validation error) | Migrated |
| `pk_slug` | `content_slug` | 4.1.2 (warning only) | 4.1.3 (validation error) | **REMOVED** |
| `parent_pk_id` | `content_parent_id` | 4.1.2 (warning only) | 4.1.3 (validation error) | Migrated |

### 2.2 v4.0.99 ??? pre-canonical renames (HDR_LEGACY_FIELD_NAME)

| Legacy field | Canonical field | Removed in | Status |
|---|---|---|---|
| `prd_id` | `content_id` | 4.1.1 | Migrated |
| `prd_slug` | `content_slug` | 4.1.1 | **REMOVED** |
| `parent_prd` | `content_parent_id` | 4.1.1 | Migrated |
| `last_modified_utc` | `questions_toon` | 4.1.1 | Migrated |
| `module` | `atoms_toon` | 4.1.1 | Migrated |

### 2.3 v4.1.0 ??? toon renames

| Legacy field | Canonical field | Removed in |
|---|---|---|
| `memory_key` | `memory_toon` | 4.2.0 |
| `dialog_transcript` | `transcript_jsonl` | 4.1.3 |

## 3. Cutoff policy

- **4.1.3**: Removed `pk_*` aliases and `dialog_transcript`
- **4.1.4**: Removed `content_slug` entirely (now generated at ingestion time from `file_path_from_root` and/or `title`)
- **4.2.0**: Remove all remaining migration compatibility aliases

## 4. Per-file migration workflow

### 4.1 Steps

1. Read header and confirm canonical **22-key** order target (including `prd_cluster`).
2. Rename legacy fields to canonical names.
3. **Remove** any remaining `content_slug` fields.
4. Confirm `transcript_jsonl` is a DB slug, not a path.
5. Validate nullable fields (see ??4.4).
6. Run `python lupo-bin/tick.py` and set `when_updated`.
7. Validate with the universal validator.
8. Regenerate sidecar from header authority.

### 4.4 `content_parent_id` by artifact_type

| artifact_type | content_parent_id rule |
|---------------|------------------------|
| `prd` | MUST be null (PRDs have no parent) |
| `implementation` | SHOULD be integer (PRD number this implements) |
| `documentation` | MAY be integer (parent PRD if documenting specific spec) |
| `doctrine` | MUST be null (root constitutional rules) |
| `version-doc` | SHOULD be integer (PRD this version doc relates to) |
| `status` | SHOULD be integer (PRD or version doc this status tracks) |

`default_collection_id` is always nullable for all `artifact_type` values.

## 5. `when_updated` semantics (objective rule)

**Rule:** `when_updated` SHALL be set to the UTC timestamp of the **last byte change** to the file using `tick.py` output.

**Implementation:**

```bash
python lupo-bin/tick.py   # Copy current_utc to when_updated