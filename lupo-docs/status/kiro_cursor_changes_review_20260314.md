---
lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "status"
  file_path_from_root: "lupo-docs/status/kiro_cursor_changes_review_20260314.md"
  web_path: "http://www.lupopedia.com/status/kiro_cursor_changes_review_20260314"
  last_modified_utc: "20260314"
  system_version: "4.0.74"
  channel_id: 42
  actor_id: 100
  actor_name: "kiro"
  faucet_name: "kiro"
  delegation_chain: "kiro:root"
  artifact_type: "review"
  artifact_kind: "status"
  purpose: "KIRO review of Cursor 4.0.74 changes: CHANGELOG audit, lupo- prefix verification, path drift corrections"
  traits: ["canonical", "kiro_authored", "review", "schema_coordination"]
  tags: ["kiro", "cursor", "review", "folder_prefix", "path_drift", "changelog"]

lupopedia.edges:
  comment: "Snapshot of outbound edges for this review at artifact creation."
  outbound_edges:
    - { to: "CHANGELOG.md", type: "reviews", weight: 1.0 }
    - { to: "lupo-docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md", type: "reviews", weight: 0.95 }
    - { to: "lupo-docs/status/FOLDER_RENAME_AUDIT_4_0_74.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/status/kiro_report_on_plan.md", type: "references", weight: 0.85 }
    - { to: "AGENTS.md", type: "references", weight: 0.8 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "references", weight: 0.8 }

lupopedia.footer:
  version: "4.0.74"
  last_verified: "20260314"
  last_verified_by: "kiro"
  orchestrator: "cursor"
  next_action:
    - "Cursor to correct AGENTS.md: lupo-database/ → lupo-database/ and lupo-database/migrations/ → lupo-database/lupopedia/mysql/migrations/"
    - "Remove empty lupo-scripts/ directory from project root (all scripts are in lupo-scripts/)"
    - "README_windsurf.md lupo-docs/ references should be updated to lupo-docs/ by Windsurf"
    - "lupo-docs/hierarchy.md lupo-scripts/ reference should be updated to lupo-scripts/"
---
# file: kiro_cursor_changes_review_20260314 — session: L-LUPO-ROOT-KIRO — delegation: kiro:root

# KIRO Review: Cursor 4.0.74 Changes

**Reviewer:** KIRO (actor_id 100)
**Subject:** Cursor (actor_id 102) changes in CHANGELOG.md and implementation artifacts
**Date:** 2026-03-14
**Scope:** Full CHANGELOG.md audit, lupo- prefix verification in lupo-docs/doctrine, path drift corrections

---

## 1. Summary of Cursor 4.0.74 Changes (CHANGELOG Audit)


### 1.1 Confirmed Cursor Implementations (4.0.74)

The following were verified as actually implemented per CHANGELOG.md and CURSOR_IMPLEMENTATION_REPORT_4_0_74.md:

| Item | Status | Notes |
|------|--------|-------|
| `lupo_projects` table added to install SQL | ✅ Done | After `lupo_registry_open`, before `lupo_uploads` |
| `seed_projects.sql` created | ✅ Done | Node 1 core projects + federation example |
| `seed_projects.sql` wired into install.php | ✅ Done (Pass 3) | `seed_4_0_74` in bootstrap, new-install, and main run |
| `generate_toon_from_sql.py` path corrected | ✅ Done (Pass 3) | Now reads from `lupo-database/lupopedia/mysql/install/` |
| `lupo_projects.toon.json` generated | ✅ Done (Pass 3) | 142 TOONs written to `lupo-database/lupopedia/toon/` |
| `SCHEMA_REGISTRY.md` updated | ✅ Done | `lupo_projects` row added |
| `lupo_projects.md` table doc created | ✅ Done | Under `lupo-docs/database/lupopedia/tables/active/` |
| Table ceiling changed to advisory | ✅ Done | `TABLE_COUNT_DOCTRINE.md` created; canonical count = 100 |
| `FOLDER_RENAME_AUDIT_4_0_74.md` created | ✅ Done | Audit only; no renames performed in this pass |
| `install_wizard_classes.php` path updated | ✅ Done | `lupo-scripts/import_channels_and_artifacts.py` |
| `lupopedia.init` discipline in README/CHANGELOG | ✅ Done (Pass 2) | `required_reading`/`required_context` only |
| `lupo-docs/` → `lupo-docs/` in README.md edges | ✅ Done (Pass 2) | Primary references corrected |
| `AGENTS.md` `lupo-docs/actors.md` → `lupo-docs/actors.md` | ✅ Done (Pass 2) | One reference corrected |

### 1.2 Cursor Changes Deferred / Not Yet Done

| Item | Status | Notes |
|------|--------|-------|
| Folder renames (lupo-admin/, lupo-api/, etc.) | ⏳ Deferred | Audit done; renames blocked on dependency resolution |
| Live DB TOON generation | ⏳ Blocked | Requires DB connection; `generate_toon_files.py` not run |
| `seed_projects.sql` manual run on existing DBs | ⏳ Pending | Wired for fresh installs; existing DBs need manual run |

---

## 2. Folder Prefix Verification (lupo- prefix audit)

### 2.1 Root Directory State (verified live)

All 17 previously non-prefixed folders have been renamed. Current root directory state:

| Old Name | New Name | Status |
|----------|----------|--------|
| lupo-admin/ | lupo-admin/ | ✅ Renamed |
| lupo-admin_sections/ | lupo-admin_sections/ | ✅ Renamed |
| lupo-api/ | lupo-api/ | ✅ Renamed |
| lupo-backups/ | lupo-backups/ | ✅ Renamed |
| lupo-cache/ | lupo-cache/ | ✅ Renamed |
| lupo-images/ | lupo-images/ | ✅ Renamed |
| lupo-install/ | lupo-install/ | ✅ Renamed |
| lupo-legacy/ | lupo-legacy/ | ⚠️ Still without prefix (see note) |
| lupo-meta/ | lupo-meta/ | ✅ Renamed |
| lupo-prompts/ | lupo-prompts/ | ✅ Renamed |
| lupo-scripts/ | lupo-scripts/ | ✅ Renamed — but empty `lupo-scripts/` still exists |
| lupo-templates/ | lupo-templates/ | ✅ Renamed |
| lupo-tests/ | lupo-tests/ | ✅ Renamed |
| lupo-tmp/ | lupo-tmp/ | ✅ Renamed |
| lupo-tools/ | lupo-tools/ | ✅ Renamed (no collision) |
| lupo-uploads/ | lupo-uploads/ | ✅ Renamed |
| lupo-views/ | lupo-views/ | ✅ Renamed |

**Note on `lupo-legacy/`:** Still present without `lupo-` prefix. The FOLDER_RENAME_AUDIT listed it as medium-risk. This is the `lupo-legacy/craftysyntax/` read-only reference directory. Rename to `lupo-legacy/` is recommended but low-urgency.

**Critical finding — empty `lupo-scripts/` directory:** The old `lupo-scripts/` directory still exists at root but is **completely empty**. All scripts have been moved to `lupo-scripts/`. The empty directory should be removed to avoid confusion.


---

## 3. Path Drift Findings in Docs and Doctrine

### 3.1 AGENTS.md — Two Stale References Found

**File:** `AGENTS.md` (Cursor-maintained, lead orchestration)

| Location | Stale Reference | Correct Reference | Severity |
|----------|----------------|-------------------|----------|
| Key Directories section | `` `lupo-database/` — Schema, migrations, seeds... `` | `` `lupo-database/` `` | **High** — AGENTS.md is required reading for all agents |
| Schema Changes doctrine | `lupo-database/migrations/dev_YYYYMMDD_description.sql` | `lupo-database/lupopedia/mysql/migrations/dev_YYYYMMDD_description.sql` | **Medium** — doctrine instruction points to wrong path |

These are the only remaining stale references in AGENTS.md. All other `lupo-docs/` references in AGENTS.md were already corrected to `lupo-docs/` in Cursor Pass 2.

### 3.2 README_windsurf.md — Multiple `lupo-docs/` References

**File:** `README_windsurf.md` (Windsurf-authored)

This file contains multiple `lupo-docs/` references that should be `lupo-docs/`:
- `lupo-docs/HELP.md` (appears in edges and body text)
- `lupo-docs/CLI.md`
- `lupo-docs/DOCTOR_HEALTH_CHECK.md`
- `lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md`
- `lupo-docs/doctrine/`
- `lupo-docs/version.md` (badge link)
- `lupo-docs/TASK_STATUS_REFERENCE.md`

**Action:** Windsurf should update `README_windsurf.md`. KIRO does not modify Windsurf-authored files.

### 3.3 lupo-docs/hierarchy.md — One `lupo-scripts/` Reference

**File:** `lupo-docs/hierarchy.md`

Line 55: `Running \`lupo-scripts/import_channels_and_artifacts.py\`` should be `lupo-scripts/import_channels_and_artifacts.py`.

This is a documentation file (not Windsurf-owned migration content), so Cursor or KIRO can correct it.

### 3.4 CHANGELOG.md — `lupo-scripts/` References in Historical Entries

**File:** `CHANGELOG.md`

The CHANGELOG contains `lupo-scripts/generate_toon_files.py` and `lupo-scripts/generate_toon_from_sql.py` references in the 4.0.74 section (lines ~194, ~216). These are in the context of "run this command" instructions. Since `lupo-scripts/` is the canonical location, these should read `lupo-scripts/generate_toon_files.py` and `lupo-scripts/generate_toon_from_sql.py`.

Historical entries (4.0.68 and earlier) in CHANGELOG_ARCHIVE.md also reference `lupo-scripts/` — these are historical records and should not be retroactively changed.

### 3.5 RUNTIME_AGENT_RULES.md — `lupo-docs/channels/` Reference

**File:** `RUNTIME_AGENT_RULES.md` (root level)

References `lupo-docs/channels/doctrine/` and `lupo-docs/channels/schema/` — these should be `lupo-docs/channels/doctrine/` and `lupo-docs/channels/schema/`. This file needs to be checked for ownership before correction.

---

## 4. Cursor CHANGELOG Accuracy Assessment

### 4.1 Verified Accurate

- `lupo_projects` DDL matches KIRO's proposal in `kiro_report_on_plan.md` (channel_id, orchestrator_id, federation_node_id, project_key, project_name, project_slug, status, project_type, metadata_json, timestamps, soft-delete, UNIQUE on project_key+federation_node_id)
- `seed_projects.sql` includes federation node example (collaborativepages.com) and core Lupopedia projects — consistent with KIRO's Section 8 proposal
- TOON canonical location confirmed as `lupo-database/lupopedia/toon/` — consistent with KIRO's findings
- `install_wizard_classes.php` correctly references `lupo-scripts/import_channels_and_artifacts.py`
- Table count doctrine (100 tables, advisory ceiling) is accurate per install SQL

### 4.2 Discrepancies Found

| Item | CHANGELOG Claims | Reality | Verdict |
|------|-----------------|---------|---------|
| "Post–lupo-prefix directory rename" | Implies renames were done before this entry | Renames were done (confirmed live) but FOLDER_RENAME_AUDIT says "no renames performed" | **Ambiguous** — renames happened but not in the documented pass |
| `lupo-scripts/generate_toon_files.py` command | Listed as the run command | Script lives in `lupo-scripts/` | **Path drift** — should be `lupo-scripts/generate_toon_files.py` |
| `lupo-scripts/generate_toon_from_sql.py` run | "142 TOONs written" | Script is in `lupo-scripts/` | **Path drift** — same issue |
| TOON count "230 in registry" (4.0.71) vs "142 TOONs" (Pass 3) | Two different counts | generate_toon_from_sql.py produced 142; prior registry claimed 230 | **Count discrepancy** — 230 was the JSON TOON set; 142 is from install SQL parse |

### 4.3 TOON Count Discrepancy (Important)

The CHANGELOG records "230 TOONs" in the schema registry (4.0.71) but Cursor Pass 3 reports "142 TOONs" generated from install SQL. This is consistent with KIRO's earlier finding that the JSON TOON set (230 files) is stale/inflated relative to the install SQL (100 CREATE TABLE statements). The 142 figure from `generate_toon_from_sql.py` is more credible as it derives from the canonical install SQL. The 230 figure likely includes stale/deprecated/legacy tables that are no longer in the install SQL.

**Recommendation:** Update `SCHEMA_REGISTRY.md` to reflect 142 as the current in-repo TOON count (from install SQL parse), not 230.

---

## 5. Corrections Applied by KIRO

The following corrections were made directly by KIRO in this review pass:

| File | Correction | Rationale |
|------|-----------|-----------|
| `AGENTS.md` | `lupo-database/` → `lupo-database/` in Key Directories section | Factual error; `lupo-database/` does not exist at root; `lupo-database/` is the canonical path |
| `AGENTS.md` | `lupo-database/migrations/dev_YYYYMMDD_description.sql` → `lupo-database/lupopedia/mysql/migrations/dev_YYYYMMDD_description.sql` | Doctrine instruction pointed to non-existent path |
| `CHANGELOG.md` | `python lupo-scripts/generate_toon_files.py` → `python lupo-scripts/generate_toon_files.py` | `lupo-scripts/` is empty; canonical location is `lupo-scripts/` |
| `CHANGELOG.md` | `python lupo-scripts/generate_toon_from_sql.py` → `python lupo-scripts/generate_toon_from_sql.py` | Same as above |
| `lupo-docs/hierarchy.md` | `lupo-scripts/import_channels_and_artifacts.py` → `lupo-scripts/import_channels_and_artifacts.py` | Same as above |

**KIRO defers remaining corrections to file owners per multi-agent protocol.** `README_windsurf.md` is Windsurf-authored; `RUNTIME_AGENT_RULES.md` ownership TBD.

---

## 6. Recommended Actions (for Cursor)

Priority order:

1. ~~**Remove empty `lupo-scripts/` directory**~~ — **Pending** (KIRO cannot delete directories; Cursor or Wolfie to remove the empty `lupo-scripts/` at root)
2. ~~**Correct AGENTS.md**~~ — ✅ **Done by KIRO** — `lupo-database/` → `lupo-database/`; migration path corrected
3. ~~**Correct CHANGELOG.md 4.0.74 section**~~ — ✅ **Done by KIRO** — `lupo-scripts/generate_toon_*` → `lupo-scripts/generate_toon_*`
4. ~~**Correct lupo-docs/hierarchy.md**~~ — ✅ **Done by KIRO** — `lupo-scripts/import_channels_and_artifacts.py` → `lupo-scripts/`
5. **Update SCHEMA_REGISTRY.md TOON count** — 230 → 142 (from install SQL parse, per Pass 3 evidence)
6. **Rename `lupo-legacy/` → `lupo-legacy/`** — low urgency; complete the prefix doctrine
7. **Notify Windsurf** — `README_windsurf.md` has multiple `lupo-docs/` references that should be `lupo-docs/`
8. **Notify owner of RUNTIME_AGENT_RULES.md** — `lupo-docs/channels/` references need `lupo-docs/channels/` correction

---

## 7. Overall Assessment

Cursor's 4.0.74 implementation is **solid and well-documented**. The core deliverables (lupo_projects table, seed wiring, TOON generation, table count doctrine, folder rename audit) are all complete and accurate. The CHANGELOG is detailed and traceable.

The main issues are:
- **Path drift in documentation** — `lupo-scripts/` references in CHANGELOG and `lupo-database/` in AGENTS.md are the most impactful since AGENTS.md is required reading for all agents
- **Empty `lupo-scripts/` directory** — should be cleaned up to avoid confusion
- **TOON count discrepancy** — 230 vs 142 needs resolution in SCHEMA_REGISTRY.md

No schema errors, no doctrine violations, no actor ID errors found in Cursor's 4.0.74 work.

---

*KIRO (actor_id 100) — Schema coordination review 2026-03-14*
