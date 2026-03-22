---
lupopedia.init:
  required_reading:
    - path: "lupo-docs/INIT_README.md"
      reason: "Prerequisites and init doctrine"
    - path: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
      reason: "Header format"
    - path: "plan.md"
      reason: "Root plan and P0/P1 tasks"
  required_context:
    - "Cursor (actor_id 102) lead orchestration; install SQL is schema authority"

lupopedia.actor_references:
  comment: "Actor IDs per lupo-database/lupopedia/actors/actor_id/registry.json"
  cursor: 102
  wolfie: 1

lupopedia.headers:
  lupopedia.version: "4.0.74"
  lupopedia.schema: "report"
  file_path_from_root: "lupo-docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md"
  web_path: "http://www.lupopedia.com/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74"
  last_modified_utc: "20260314"
  system_version: "4.0.74"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  faucet_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "implementation-report"
  artifact_kind: "status"
  purpose: "Cursor lead orchestration execution report: research, lupo_projects implementation, table ceiling, validation"

lupopedia.edges:
  comment: "Snapshot of outbound edges for this report at artifact creation."
  outbound_edges:
    - { to: "plan.md", type: "references", weight: 1.0 }
    - { to: "report.md", type: "references", weight: 0.95 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema", weight: 1.0 }
    - { to: "lupo-docs/TOON_REFERENCE.md", type: "references", weight: 0.9 }

lupopedia.footer:
  version: "4.0.74"
  last_verified: "20260314"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Run generate_toon_files.py when DB available to produce lupo_projects.toon"
    - "Phase folder renames (lupo-admin/, lupo-api/, etc.) after dependency audit"
---
# file: CURSOR_IMPLEMENTATION_REPORT_4_0_74 — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root

# Cursor Implementation Report 4.0.74

**Lead orchestration:** Cursor IDE (actor_id 102)  
**Channel:** 42  
**Version:** 4.0.74  
**Date:** 2026-03-14  

This report documents research (Parts 1–2), implementation (Parts 4–7), and validation (Parts 8–10) per the Lupopedia Implementation Directive.

---

## Part 1 & 2 — Research and investigation results

### 1. Documentation root

- **`lupo-docs/`** at project root: **Does not exist.** No top-level `lupo-docs/` directory.
- **`lupo-docs/`**: **Exists** and is the canonical documentation root (hundreds of files under lupo-docs/).
- **Conclusion:** **lupo-docs/** is the canonical documentation root. Any references to `lupo-docs/` in links or paths should point to `lupo-docs/` or be updated per plan P0.5.

### 2. TOON locations

| Location | Exists | Format | Contents |
|----------|--------|--------|----------|
| **lupo-database/lupopedia/toon/** | **No** (directory not present or empty) | .toon (YAML) | Intended output of generate_toon_files.py when run against live DB |
| **lupo-database/lupopedia/json/** | **Yes** | .json | JSON files (e.g. livehelp_visit_track.json, lupo_help_topics.json); mixed data/schema |
| **lupo-database/lupopedia/toon/** | **Yes** | .toon.json | Current in-repo TOON set (230+ .toon.json files) |

**TOON conclusion (doctrine-safe, evidence-based):** Install SQL is the canonical schema authority. TOON artifacts are derived representations and must defer to install SQL when discrepancies exist. The repo currently supports more than one TOON-related path: **lupo-database/lupopedia/toon/** is the current in-repo TOON set; **lupo-database/lupopedia/toon/** appears to be the intended output when generate_toon_files.py is run from a live DB; **lupo-database/lupopedia/json/** contains JSON output/data. Generator script output paths and repo paths are not fully aligned; further unification is follow-up work. No claim is made that one set is “stale legacy” without direct evidence.

### 3. TOON vs install SQL — lupo_actors

- **install_new_lupopedia.sql:** `lupo_actors` has **PRIMARY KEY (actor_name)** and **UNIQUE (actor_id)**. Columns include actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, etc.
- **lupo-database/lupopedia/toon/lupo_actors.toon.json:** Contains `actor_id`, `actor_type`, `slug`, `name` (and other fields) but **does not list primary_key** in the snippet read; column names may differ (e.g. `name` vs `actor_name`). Per doctrine, **install SQL is authoritative**; TOONs should be regenerated from install (or from live DB after applying install) to align.

### 4. TOON regeneration

- **lupo-scripts/generate_toon_files.py** exists; requires PyMySQL and DB credentials from lupopedia-config.php; writes to lupo-database/lupopedia/json/ and toon/. **Not run** in this session (no DB connection). After adding lupo_projects to install and applying to DB, running this script will produce lupo_projects.toon and lupo_projects.json.
- **lupo-scripts/generate_toon_from_sql.py** exists; parses install_new_lupopedia.sql and can output TOON JSON; output path in script is lupo-database/lupopedia/toon/ (should be lupo-database/lupopedia/toon/ for consistency). Running it after adding lupo_projects would create lupo_projects.toon.json in that output dir.

---

## Part 3 — Core doctrine (applied)

- Database: No foreign keys; BIGINT UTC YYYYMMDDHHIISS; explicit PK for registry-style tables; JSON column allowed (used elsewhere in install).
- Headers: lupopedia.init, metadata, headers, edges, footer, next_actions; legacy flare.* readable.

---

## Part 4 — Table count doctrine and schema decision

- **lupo_projects** is an approved 4.0.74 schema addition: a first-class project registry scoped by channel, orchestrator, and federation node (KIRO proposal, Captain directive).
- **Table ceiling:** The prior table ceiling (e.g. 222) is **advisory only**. Schema expansion is allowed when justified and approved. New tables may be added even if count exceeds the former guideline.
- **Docs updated:** SYMBOL_OPERATOR_DOCTRINE and related docs updated to state the ceiling is advisory; see CHANGELOG and this report.

---

## Part 5 — lupo_projects implementation

- **Table added** to **lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql** (after lupo_registry_open, before lupo_uploads) with:
  - project_id bigint NOT NULL (PK; application-supplied)
  - project_key, project_name, project_slug, description
  - channel_id, orchestrator_id, federation_node_id
  - status, project_type, metadata_json (json DEFAULT NULL)
  - created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis (BIGINT, defaults 0)
  - PRIMARY KEY (project_id), UNIQUE (project_key, federation_node_id)
  - Indexes: channel_id, orchestrator_id, federation_node_id, status, is_deleted
- **Seed file created:** **lupo-database/lupopedia/mysql/seed/seed_projects.sql** with Lupopedia core project and federation example rows using @now for timestamps.
- **Seed integration:** **seed_projects.sql is not yet in the installer seed execution path.** install.php runs a fixed list of seed files (e.g. seed_registry_comprehensive, seed_actors_agents, seed_default_sessions, seed_4_0_68, seed_4_0_69, etc.); seed_projects.sql was not added to that list. **Follow-up:** Wire seed_projects.sql into the installer/seed flow or document manual run.
- **TOON:** generate_toon_files.py (from DB) or generate_toon_from_sql.py (from install) would produce lupo_projects TOON when run; **TOON generation was not run in this session.**

---

## Part 6 — Documentation updates

- **README.md, CHANGELOG.md, plan.md, report.md:** Updated as needed for lupo_projects and table ceiling (advisory).
- **lupo-docs/database/lupopedia/SCHEMA_REGISTRY.md:** lupo_projects row added; header/footer updated in hardening pass.
- **lupo-docs/database/lupopedia/tables/active/lupo_projects.md:** New table doc (purpose, columns, doctrine notes).

---

## Part 7 — Folder naming audit

- **Directories listed in directive:** lupo-admin/, lupo-admin_sections/, lupo-api/, lupo-backups/, lupo-cache/, lupo-images/, lupo-install/, lupo-legacy/, lupo-meta/, lupo-prompts/, lupo-scripts/, lupo-templates/, lupo-tests/, lupo-tmp/, lupo-tools/, lupo-uploads/, lupo-views/.
- **Action:** Verification and rename must be **phased**; code and config references must be researched before renaming. This report records the directive; **no renames performed** in this session. Recommend a dedicated P1 task: (1) verify each directory exists, (2) grep for references, (3) plan renames (e.g. lupo-admin/, lupo-api/) and apply in a follow-up change.

---

## Part 8 — Validation

- **Schema:** lupo_projects is defined in install SQL. **SHOW TABLES LIKE 'lupo_projects'** should succeed after the updated install revision is applied to a target DB. **Runtime DB validation was not completed in this report session.**
- **TOON:** A lupo_projects TOON file would be produced when generate_toon_files.py (from DB) or generate_toon_from_sql.py (from install) is run; neither was run in this session. Generator output paths may need alignment with lupo-database/lupopedia/toon/.
- **Registry:** lupo-database/lupopedia/actors/actor_id/registry.json unchanged; actor IDs (Cursor 102, Wolfie 1, etc.) match.

---

## Part 9 — CHANGELOG

- Entry added under 4.0.74: lupo_projects table, seed_projects.sql, table ceiling advisory, TOON regeneration note, files changed.

---

## Files changed

Files actually modified or created in the 4.0.74 implementation (verified):

| File | Change |
|------|--------|
| lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql | Added lupo_projects table and indexes |
| lupo-database/lupopedia/mysql/seed/seed_projects.sql | Created (seed data; not yet in installer path) |
| lupo-docs/database/lupopedia/tables/active/lupo_projects.md | Created |
| lupo-docs/database/lupopedia/SCHEMA_REGISTRY.md | Added lupo_projects row; header/footer updated in hardening |
| lupo-docs/channels/doctrine/SYMBOL_OPERATOR_DOCTRINE.md | Table ceiling wording → advisory only |
| lupo-docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md | Created; hardened in this pass |
| CHANGELOG.md | 4.0.74 subsection: lupo_projects, seed, ceiling, report |
| plan.md | TOON/doc root wording; P0 criteria; next actions (hardening pass) |
| report.md | Edge to implementation report (if added) |

---

## Pass 2 — Cursor execution directive (2026-03-14)

Per [lupo-prompts/cursor/20260314_cursor_execute_plan_4_0_74.md](../../lupo-prompts/cursor/20260314_cursor_execute_plan_4_0_74.md), Cursor executed P0 and direct P1 items.

**Files changed this pass:**

| File | Change |
|------|--------|
| CHANGELOG.md | lupopedia.init → required_reading/required_context only; comment actor_id 1003→102; new subsection "Cursor execution pass — P0/P1 repo alignment" |
| README.md | lupopedia.init → required_reading/required_context only; lupo-docs/→lupo-docs/ (edges, badges, Primary references, Core doctrine link); added lupopedia.next_actions |
| AGENTS.md | lupo-docs/actors.md → lupo-docs/actors.md |
| lupo-prompts/cursor/20260314_cursor_execute_plan_4_0_74.md | Created (directive artifact) |
| plan.md | Edge to directive file (implements) |
| lupo-docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md | This section and file list |

**Validation (this pass):** Actor IDs from registry (KIRO 100, Cursor 102); canonical doc root lupo-docs/; path drift fixed in README and AGENTS; lupopedia.init discipline applied to README and CHANGELOG; no new actor or schema changes.

---

## Pass 3 — TOON alignment, seed integration, schema inventory (2026-03-14)

Per [lupo-prompts/cursor/20260314_cursor_pass3_toon_seed_cleanup_4_0_74.md](../../lupo-prompts/cursor/20260314_cursor_pass3_toon_seed_cleanup_4_0_74.md), Cursor executed TOON path alignment, seed wiring, schema inventory, and merge-process documentation.

**Files changed this pass:**

| File | Change |
|------|--------|
| lupo-scripts/generate_toon_from_sql.py | Install path: lupo-database/migrations/ → lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql; output: lupo-database/lupopedia/toon/ → lupo-database/lupopedia/toon/ |
| install.php | seed_projects.sql wired into installer: added seed_4_0_74 in bootstrap, new-install run, and main run (after 4.0.69) |
| lupo-docs/TOON_REFERENCE.md | Documented two workflows (from-SQL → lupo-docs/toons; from-DB → lupo-database/...); in-repo set = lupo-database/lupopedia/toon/*.toon.json |
| plan.md | Merge process rule (faucet files authoritative for domain; root maintained by Cursor; merge with attribution); edge to Pass 3 directive |
| lupo-prompts/cursor/20260314_cursor_pass3_toon_seed_cleanup_4_0_74.md | Created (directive artifact) |
| lupo-database/lupopedia/toon/lupo_projects.toon.json | Created by running generate_toon_from_sql.py (Pass 3) |
| lupo-docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md | This section, Schema inventory, updated Unresolved |

**Validation (this pass):** Script paths verified by reading generate_toon_from_sql.py and generate_toon_files.py. install.php seed lists read; seed_projects.sql was not in any list; wiring added in three places with is_file() check. TOON_REFERENCE and script docstrings aligned. **Script run:** `python lupo-scripts/generate_toon_from_sql.py` was executed; it reported "Generated 142 TOONs" and wrote to lupo-database/lupopedia/toon/; lupo_projects.toon.json was created.

### Schema inventory (verified 2026-03-14)

| Source | Count / location | Authority / notes |
|--------|------------------|-------------------|
| **Install SQL** | 100 CREATE TABLE (install_new_lupopedia.sql, grep count) | **Canonical schema authority.** All table definitions. |
| **In-repo TOONs** | lupo-database/lupopedia/toon/*.toon.json | **Derived.** generate_toon_from_sql.py (after path fix) wrote 142 TOONs; includes lupo_projects.toon.json after Pass 3 run. |
| **generate_toon_files.py** | Writes to lupo-database/lupopedia/json/ and toon/ | **From live DB.** Use when DB is available; outputs .json + .toon (YAML). |
| **generate_toon_from_sql.py** | Reads install SQL; writes lupo-database/lupopedia/toon/*.toon.json | **No DB.** Run to refresh in-repo TOON set after schema changes. |
| **Seed in installer** | seed_projects.sql in seed_4_0_74 (bootstrap + new install + main run) | **Wired.** Runs after 4.0.69 seeds when file exists. |
| **lupo-database/lupopedia/toon/** | 0 .toon files (directory may exist, empty) | Output of generate_toon_files.py when run; not committed. |

---

## Unresolved / follow-up

1. **TOON generator path alignment:** Resolved in Pass 3 — generate_toon_from_sql.py now uses lupo-database/.../install/ and lupo-database/lupopedia/toon/.
2. **lupo-database/lupopedia/toon/:** Output of generate_toon_files.py only; directory created when script runs. No change to repo layout.
3. **Seed execution path:** Resolved in Pass 3 — seed_projects.sql wired into install.php (seed_4_0_74).
4. **Folder renames:** Not done; deferred. P1 audit created (FOLDER_RENAME_AUDIT_4_0_74.md); no renames in Pass 4.
5. **Run generate_toon_from_sql.py:** Done in Pass 3; lupo_projects.toon.json added to lupo-database/lupopedia/toon/.

---

## Pass 4 — P1 execution start (2026-03-15)

Per [lupo-prompts/cursor/20260315_cursor_p1_execution_4_0_74.md](../../lupo-prompts/cursor/20260315_cursor_p1_execution_4_0_74.md), Cursor began P1 execution: folder rename audit and table count doctrine.

**Files changed this pass:**

| File | Change |
|------|--------|
| lupo-prompts/cursor/20260315_cursor_p1_execution_4_0_74.md | Created (P1 directive) |
| lupo-docs/status/FOLDER_RENAME_AUDIT_4_0_74.md | Created (audit table; no renames) |
| lupo-docs/doctrine/TABLE_COUNT_DOCTRINE.md | Created (canonical count 100, install authority, TOON derived, advisory ceiling) |
| README.md | Database paragraph → TABLE_COUNT_DOCTRINE + count 100 |
| CHANGELOG.md | Subsection "P1 execution start — folder rename audit and table count doctrine" |
| lupo-docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md | This section |

**Validation:** All 17 target folders verified at root. Grep used for PHP, .htaccess, Markdown, config, scripts. Install SQL CREATE TABLE count verified (100). No scripts run against live DB.

**Blockers:** Live DB TOON generation (P1 Task 2) — **blocked until DB available.** When a database exists with updated schema, run `python lupo-scripts/generate_toon_files.py` and document results here.

**Next actions:** Use FOLDER_RENAME_AUDIT_4_0_74.md to plan phased renames in a later P1/P2 pass; run generate_toon_files.py when DB is available.

---

*Cursor IDE (actor_id 102) — Lead orchestration implementation report 2026-03-14; Pass 4 2026-03-15*
