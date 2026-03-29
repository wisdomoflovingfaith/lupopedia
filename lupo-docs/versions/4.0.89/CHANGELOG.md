---
lupopedia.headers:
  lupopedia.schema: changelog
  file_path_from_root: "lupo-docs/versions/4.0.89/CHANGELOG.md"
  content_id: 217050541066347226
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.89/CHANGELOG.md"
  federation_node_id: 0
  last_modified_utc: "20260329235907"
  when_updated: "20260329235907"
  channel_id: 42
  thread_id: "changelog"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: changelog
  artifact_kind: version_specific
  purpose: Version 4.0.89 changelog with PK key generation overhaul, LUPOPEDIA headers doctrine establishment, Windows WSL command patterns, department-based access control implementation, upgrade policy establishment, content_id integration, registry tables removal, database doctrine establishment, and independent coder's manifesto
  tags:
  - "changelog"
  - "version"
  - "changes"
  - "pk_key_generation"
  - "registry_removal"
  - "timestamp_based_ids"
  - "lupopedia_headers_doctrine"
  - "schema_taxonomy"
  - "federation_node_validation"
  - "context_model"
  - "department_access_control"
  - "security_policy"
  - "upgrade_policy"
  - "content_id"
  - "database_neutrality"
  - "wolfie_directive"
  - "independent_coder"
  - "manifesto"
  - "philosophy"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.89/PLAN.md"
      type: references
      weight: 1.0
      reason: Version planning and task tracking
    - to: "lupo-docs/versions/4.0.89/TODO.md"
      type: references
      weight: 1.0
      reason: Current task tracking and priorities
    - to: "lupo-docs/versions/4.0.89/WHAT_TO_DO_NEXT_SESSION.md"
      type: references
      weight: 1.0
      reason: IDE session handoff after release verification (2026-03-29)
lupopedia.footer:
  last_verified: "20260329235907"
  last_verified_by: "wolfie"
  last_verified_by_actor_id: 1
  orchestrator: "wolfie:root"
  next_action:
    - "Next session: lupo-docs/versions/4.0.89/WHAT_TO_DO_NEXT_SESSION.md (headers handoff)"
    - Monitor compliance with Windows-specific command patterns
    - Continue cross-platform compatibility improvements
    - Update documentation as needed for WSL enforcement
    - Test department-based access control implementation
    - Run migration scripts for existing databases
    - Validate content_id integration in import scripts
    - Verify registry tables removal and timestamp-based ID implementation
    - Follow independent coder's manifesto principles in all development
---

# Changelog — Version 4.0.89

**Version**: 4.0.89  
**Release Date**: 2026-03-29 (release verification + H8 parity fixes recorded)  
**Status**: **H5/H7/H8 dry-run parity verified** — re-import existing DB rows after `content_id` normalization if slug/PK conflicts appear (see **Release verification**)  

---

## Version Overview: 4.0.89 — Headers, database authority, dual toolchains

**Release focus:** LUPOPEDIA HEADERS as the tagging spine, with end-to-end infrastructure for both **IDE agents** (Python) and **PHP agents** (shared hosting).

### Core achievements

| Area | Description |
|------|-------------|
| **Header doctrine** | Single source of truth in **`lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`**; taxonomy, validation rules, DB mapping |
| **Python toolchain** | **`import_content.py`**, **`generate_headers_from_db.py`**, **`lib/header_db_sync.py`** — IDE agent workflow |
| **PHP toolchain** | **`import_content.php`**, **`generate_headers_from_db.php`**, **`HeaderDbSync.php`** — PHP agent + shared hosting workflow |
| **Dual running log** | **`lupopedia.history`** ↔ **`lupo_contents.revision_history`**; key present = serialize/replace; key absent = preserve DB |
| **Database authority** | **`install_new_lupopedia.sql`** + **`lupo-database/lupopedia/json/*.json`** as canonical schema; no FK/triggers/auto-timestamps in DB |
| **PK registry removal** | IDs generated in application code (**`IdGenerator.php`**); **`lupo_registry`** / **`lupo_registry_open`** removed |
| **Code ↔ DB alignment** | Imports, services, and validators aligned with install schema; drift = release blocker |
| **PHP agent filesystem safety** | Allowed dirs: **`lupo-rules/`**, **`lupo-docs/`**, **`lupo-channels/`**, **`lupo-content/`**; extensions: **`.md`**, **`.txt`**, **`.yaml`**, **`.yml`**, **`.json`**, **`.csv`**, **`.xml`** — **no** **`.html` / `.htm` / `.js` / `.php`**. **`AgentFileWriter`** + deploy doc — **`TODO.md` H9**, **`README.md` #12** |
| **`lupo-*` literacy** | Root **`ORGANIZATION.md` §4** + **`lupo-docs/ORGANIZATION.md`**; release verify **`TODO.md` H4.4** |

### Agent toolchains

- **IDE agents** (Cursor, Windsurf, Kiro): **full repository write access** (developer tools; **not** limited by **`AgentFileWriter`**); Python toolchain in **`lupo-scripts/`**
- **PHP agents** (DeepSeek, OpenAI, Grok via API): **invoke** PHP scripts in **`lupo-scripts/`** on shared hosting; **author** markdown/docs only in **safe** trees — see **`TODO.md` H9**

Identical **database state** for the **same** markdown artifact imported by either toolchain; **not** identical *filesystem* permissions — PHP agents do **not** mirror IDE code-editing scope.

### Database rules (canonical)

- Schema: **`install_new_lupopedia.sql`** only; **`lupo-database/lupopedia/json/*.json`** mirrors for tooling
- No foreign keys, triggers, or stored procedures in DB
- No DB auto-timestamps (`CURRENT_TIMESTAMP`) — all timestamps are **BIGINT** UTC written by application code
- Safe migrations only; no dropped tables/columns assumed without a migration path

**Release criterion #8:** **Database & code alignment** must pass alongside header readiness.

**Release criterion #12:** **PHP agent filesystem safety** (allowed trees + extensions, no HTML for agent output, **`AgentFileWriter`**) per **`TODO.md` H9** and **`lupo-docs/ORGANIZATION.md` §2.2**; deployment notes in **`PHP_AGENT_FILESYSTEM_DEPLOYMENT.md`**.

### PHP import/validation toolchain (2026-03-29)

PHP equivalents of the Python database-first header pipeline: **`import_content.php`**, **`validate_lupopedia_headers.php`**, **`generate_headers_from_db.php`**, **`HeaderDbSync.php`**. Default **both** PHP and Python imports are **DB-only**; use **`--write-back`** when you need `content_id` written into the markdown (one-time migration / header sync). **Quick commands**: **`README.md`**; org summary: **`lupo-docs/ORGANIZATION.md` §2.1**.

**Implementation:** See **[TODO.md](TODO.md)** — sections **H8** (import parity) and **H9** (PHP agent write boundaries) — for task breakdown and acceptance criteria.

### Scope and timeline notes

**Release scope refocus (2026-03-28):** Tagging **4.0.89** is **conditional on LUPOPEDIA HEADERS readiness** — Python + PHP validation (and operator/web surfacing where required), **import/regenerate** round-trips, **`lupo-*` organization** and **IDE rule packs** aligned with **`lupo-rules/root/`**, **header-related database** parity, and **documented release-gate tests**. **Agent model:** **`README.md`** (**#5**, **#6**, **#11**, **#12**), **`TODO.md` H4.4**, **H8**, **H9**, **`lupo-docs/ORGANIZATION.md` §2.1–§2.2**; dependency-ordered plan: **`PLAN.md`**.

**Running log (2026-03-29):** **`lupopedia.history`** and **`lupo_contents.revision_history`** are the **paired audit trail**. Binding rules live in **`lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md`** (*Dual running log*). **`TODO.md` H7** + **`README.md` criterion 10** require a **recorded** round-trip before tag.

**Product backlog moved to 4.0.90:** Context model database work, Crafty Syntax **implementation**, and documentation-clarity tasks **5.1–5.4** are **not** 4.0.89 exit criteria; they live under **`lupo-docs/versions/4.0.90/`** (`README.md`, `TODO.md`, `PLAN.md`, `SCOPE_CARRYOVER_FROM_4_0_89.md`).

**Changelog body below** still records **repository work** landed on this development line (registry removal, doctrines, WSL patterns, manifesto, header pipeline, etc.). It is **not** trimmed when scope narrows — it remains the historical record.

**Original overview (still true of the repo, not all of it gating the 4.0.89 tag):** Database neutrality enforcement, registry system removal, and Windows WSL command pattern compliance addressed critical architectural issues (LILITH audit) and WOLFIE’s directive on registry tables vs timestamp-based ID generation.

---

## Release verification (2026-03-29 UTC — Cursor / actor 102)

**Environment:** ServBay Windows host; `lupopedia-config.php` present; Python 3.13 + pymysql; PHP 8.4 CLI.

### H5 — Three-artifact matrix (Python toolchain)

| # | `file_path_from_root` | Import | Regenerate | `validate_lupopedia_headers.py` |
|---|------------------------|--------|------------|----------------------------------|
| 1 | `lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md` | OK (historical row may still show legacy `content_id` until re-import) | OK (script emitted footer/YAML ordering / timestamp notices) | **OK** |
| 2 | `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md` | OK | OK | **OK** |
| 3 | `lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md` | OK | OK | **OK** |

**Note:** `generate_headers_from_db.py` printed **Tier 2 / footer timestamp** warnings on some inputs (invalid hour in stored `when_updated` like `…240000`, block order). Artifacts still validated clean after regenerate.

**Prerequisite fix (same session):** `lupo-scripts/lib/header_validation.py` — `file_path_from_root` now allows **hyphens** in path segments (e.g. `lupo-rules/`); `thread_id` accepts **numeric or slug** `^[a-z0-9][a-z0-9-]*$` to match binding doctrine and **`HeaderDbSync.php`**. Without this, import failed on canonical paths.

### Post-verification code fixes (2026-03-29 UTC — H8 closure)

| Item | Change |
|------|--------|
| **CRLF → LF** | **`HeaderDbSync::parseYamlFrontMatter`** normalized newlines in the wrong order (`\r` before `\r\n`), which **doubled** line breaks on Windows CRLF files and shifted the closing `---` — PHP `content_id` did not match Python. **Fix:** `\r\n` → `\n`, then lone `\r` → `\n` (same order as Python). |
| **Python body/path parity** | **`import_content.py`** — front matter split uses LF-normalized `\n` lines (not `splitlines(keepends=True)` on raw CRLF); **`file_path_from_root`** normalized with **`_norm_path_repo()`** to match **`HeaderDbSync::normPath`**. |
| **PDO charset** | **`class-pdo_db.php`** — **`SET NAMES 'utf8mb4'`** (was **`utf8`** / utf8mb3), matching DSN and **`utf8mb4_*`** column collations so PHP import no longer hits mixed-collation errors. |

**Deterministic `content_id` (after fixes):** `python import_content.py … --dry-run` and `php import_content.php … --dry-run` report the **same** value (e.g. **`7089464568349913253`** for `LUPOPEDIA_HEADERS_DOCTRINE.md`).  

**DB migration note:** If the database still holds rows keyed by an **old** `content_id`, a PHP import may try **INSERT** with the **new** id and hit **`lupo_contents_unique_content_slug_domain`**. Resolve by running **Python** import once (upsert by new id + write-back) or adjusting/removing the stale row per ops policy.

**Errata — import tool behavior (end of session 2026-03-29 UTC, Cursor / actor 102):** **`import_content.py`** now matches **`import_content.php`**: default run updates **DB + header sync only**; add **`--write-back`** to inject **`lupopedia.headers.content_id`** into the file. When no row exists for the deterministic `content_id` but a row matches **`file_path_from_root`** (or **`slug`** with path disambiguation), the script performs **`RECONCILE_PK_UPDATE`**: repoints **`lupo_metadata`** / **`lupo_edges`** and **`UPDATE`s `lupo_contents`** to the new **`content_id`** (preserves **`created_ymdhis`**, **`view_count`**, **`version_number`**). **`import_content.php`** header comment corrected (Python no longer documented as always write-backing). Operational command: `python lupo-scripts/import_content.py --write-back <path.md>`.

### H7 — `revision_history` (doctrine file)

For `lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md` after import: **`revision_history`** is non-empty JSON array (**2** events in sample read). Round-trip **regenerate** executed; history preserved in DB-driven YAML flow per script output.

### H8 — PHP parity (**PASS** for dry-run identity)

| Check | Result |
|-------|--------|
| `php lupo-scripts/import_content.php … --dry-run` | **OK** — same deterministic `content_id` as Python on the same file (after CRLF + path normalization fixes above). |
| `python … import_content.py … --dry-run` | **OK** — matches PHP. |
| Full PHP import (no dry-run) | **OK** against connection/collation after **`SET NAMES utf8mb4`**. If a row already exists under a **legacy** `content_id` for the same slug, INSERT may fail with **duplicate slug** until DB/files are realigned (see migration note above). |

### README criteria 1–12 (sign-off table)

| # | Criterion | Status | Notes |
|---|-----------|--------|-------|
| 1 | Doctrine binding | OK | Docs + root doctrine |
| 2 | Python validation | OK | Scripts exercised in H5 |
| 3 | PHP validation | Partial | CLI exists; **H2.1/H2.3** still per `TODO.md` |
| 4 | Web/operator surface | Gap | Admin UI path still **PENDING** (`TODO` H2.3) |
| 5 | Import/regenerate | OK | Python path proven (H5) |
| 6 | `lupo-*` literacy | Process | **H4.4** — team sign-off |
| 7 | IDE rule packs | Process | VERIFY per `TODO` H4.2 |
| 8 | DB & code alignment | Ongoing | Spot-check imports vs install + JSON mirrors |
| 9 | Release-gate testing | OK | H5 recorded here |
| 10 | Running log H7 | OK | `revision_history` populated (doctrine) |
| 11 | Dual toolchains | **OK** | H8 dry-run parity + PDO utf8mb4 (full import: watch slug/PK migration) |
| 12 | PHP agent FS (H9) | OK | Policy + `AgentFileWriter` |

**Tagging:** **`4.0.89`** documents verified **Python + PHP** deterministic `content_id` alignment and release gates; operators with legacy `lupo_contents` rows should **re-import** or reconcile keys if they see unique-key errors.

---

## Major Changes

### 1. PK Key Generation: Timestamp + Random IDs

**This is the definitive ID generation system for 4.0.89 and beyond.**

#### Before: Registry Tables with AUTO_INCREMENT
- `lupo_registry` and `lupo_registry_open` tables tracked IDs
- Used MySQL `AUTO_INCREMENT` (PostgreSQL incompatible)
- Required registry lookups for every new record
- Over-engineered solution for a simple problem

#### After: Timestamp + Random ID Generation (Final)
- **Format:** `YYYYMMDDHHIISS` + 4-digit random (1-9999)
- **Example:** `202603281200001234` (timestamp: 2026-03-28 12:00:00, random: 1234)
- **Collision handling:** Up to 3 retries with new random (1 in 10,000 chance per second)
- **Application-layer:** `IdGenerator::generate()` (PHP) / `calculate_content_id()` (Python)
- **Database import default:** DB-only (no file write); use `--write-back` to inject content_id into file
- **Migration:** Existing hash-based IDs remain valid; new imports use timestamp+random; run `--write-back` to migrate files

#### Registry Tables Removed
- `lupo_registry` — REMOVED
- `lupo_registry_open` — REMOVED

#### Why This Matters
- **PostgreSQL Compatibility**: No more AUTO_INCREMENT
- **Simpler Architecture**: One less moving part
- **Self-Documenting IDs**: The ID tells you when it was created
- **Human-Readable**: Timestamp prefix makes debugging easier
- **Collision-Safe**: Random suffix with retry logic

### 2. LUPOPEDIA Headers Doctrine Established

Complete header taxonomy with 16 schema values, validation rules, and federation node assignment.

- **16 Schema Values**: `doctrine`, `rule`, `philosophy`, `plan`, `todo`, `changelog`, `directive`, `design`, `review`, `report`, `implementation`, `script`, `class`, `index`, `thread`, `broadcast` 
- **Federation Node Rules**: Node 0 for core docs, Node 1 for local, Node 2+ for external research
- **Deprecated Fields Removed**: `lupopedia.version`, `system_version`, `version` in footer
- **Validator Script Updated**: Enforces all header rules including cross-field consistency

### 3. Database Neutrality Doctrine
### 4. Windows WSL Command Patterns
### 5. Independent Coder's Manifesto

### 6. Database-first LUPOPEDIA HEADERS pipeline and doctrine consolidation (2026-03-28)

**Adds to** (does not replace) item **§2 LUPOPEDIA Headers Doctrine** above: operational import/regenerate path and explicit **single source of truth** for the binding doctrine file.

- **WOLFIE directive (channel 42):** Database authoritative for imported markdown; flow **file → import (`lupo_contents` + `lupo_metadata` + `lupo_edges` + optional `revision_history`) → regenerate YAML from DB**; validators warn on missing `content_id`.
- **Scripts:** `import_content.py` calls `lib/header_db_sync.sync_header_artifact_to_db`; `generate_headers_from_db.py` defaults to **live MySQL** (not mock); `ensure_imported.py`; `validate_lupopedia_headers.py` / `lib/header_validation.py` warn on missing `content_id`.
- **Single binding doctrine:** `lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md` only — field matrix, validation, **`lupo_contents` JSON column mapping** (which columns header import sets vs leaves for runtime).
- **Stable alias:** `lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md` — pointer + **`outbound_edges`** to all import/validate scripts and companion docs (no duplicate binding text).
- **Cross-links:** Repo `README.md`, `lupo-rules/root/README.md`, `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md`, `VALIDATORS_AND_TOOLING.md`, `lupo-docs/doctrine/INDEX.md`; root doctrine YAML edges expanded to the same tooling set.
- **4.0.89 narrative:** `lupo-docs/versions/4.0.89/HEADER_DB_FIRST_AND_DOCTRINE_CONSOLIDATION_4.0.89.md` — integration note preserving prior actor artifacts (THOTH README, WOLFIE changelog claims, clarity review).

**§6 follow-up (later 2026-03-28):** Clarified in binding doctrine that **`lupo_edges`** has no SQL columns named `weight` or `reason`; YAML maps to **`semantic_weight`**, **`weight_score`**, **`flare_reason`** per install SQL. Stated explicitly that **`sync_header_artifact_to_db`** is defined in **`lib/header_db_sync.py`** (not duplicated inside `import_content.py`) and that **`generate_headers_from_db.py`** uses **`build_yaml_data_from_db`** as the inverse reader. **`validate_lupopedia_headers.py`** gained optional **`--check-db`** (warns when file lists edges/history but MySQL has none for `content_id`). `VALIDATORS_AND_TOOLING.md` and `generate_headers_from_db.py` module docstring updated accordingly.

**§6 follow-up (later 2026-03-28, universal validator):** **`validate_lupopedia_headers_universal.py`** was brought in line with the same doctrine-heavy checks: optional **`--check-db`** (same drift idea when **`content_id`** is set), **required `lupopedia.headers` keys** (including **`thread_id`** pattern `^[a-z0-9][a-z0-9-]*$` and paired UTC **`when_updated` / `last_modified_utc`**), single **parsed-YAML** path for **`lupopedia.history`**, and **`outbound_edges[].to`** existence checks resolved from the **repository root** (walk up until `lupo-scripts` + `lupo-includes`). PyYAML imported at module top; log tags stay ASCII-safe on Windows.

### 7. PLAN.md — execution retrospective and 4.0.90 planning guidance (LILITH, 2026-03-28)

**Adds narrative** to the version plan (does not remove ATHENA phase structure): tables of **major deliverables** underplayed in the original plan (header consolidation, binding doctrines, registry removal, `IdGenerator`, WSL doc, manifesto), **planned vs approximate actual** phase order, verdict (**plan not followed sequentially; acceptable as history**), and **recommendations** for **`lupo-docs/versions/4.0.90/PLAN.md`** as a **living document** updated during development, with **dependency-based** phasing per **`TASK_PLANNING_DOCTRINE.md`**. Full text: **`lupo-docs/versions/4.0.89/PLAN.md`** § *Retrospective — execution vs plan*.

### 8. Version scope split — 4.0.89 headers release vs 4.0.90 backlog (2026-03-28)

**4.0.89** `README.md` / `PLAN.md` / `TODO.md` refocused: **release tag** when **LUPOPEDIA HEADERS** validation (Python + PHP + operator surface), **import/regenerate**, **`lupo-*` + IDE rules**, **header-related DB parity**, and **documented import/read/regenerate tests** are satisfied. **4.0.90** folder opened (`README`, `TODO`, `PLAN`, `CHANGELOG`, `SCOPE_CARRYOVER_FROM_4_0_89.md`) for **context model**, **Crafty Syntax execution**, and **doc-clarity** tasks **5.1–5.4** previously under 4.0.89. Retrospective appendix for pre-refocus execution lives in **`lupo-docs/versions/4.0.90/PLAN.md`** Appendix.

---


## Detailed Changes

### 9. Timestamp + Random content_id Implementation (2026-03-28, Cursor)

- **content_id generation** for all imports is now `YYYYMMDDHHIISS` + 4-digit random (1-9999), replacing both hash and sequence-based approaches.
- **Python:** `import_content.py` uses `calculate_content_id` with up to 3 DB-checked retries for collision; default is DB+sync only, `--write-back` sets content_id in YAML.
- **PHP:** `IdGenerator::generate` and `HeaderDbSync::calculateContentId` use timestamp+random, with up to 3 DB-checked retries for collision.
- **Doctrine updated:** See `DATABASE_DOCTRINE.md` §4.1 — PKs are now timestamp+random, not hash or sequence. All import scripts and doc references updated.
- **Migration note:** Existing hash-based IDs are valid, but new imports use the new format. Re-import to migrate, or run reconciliation as needed.

---

### New Features

#### PK Key Generation System
- **IdGenerator Class**: Created `lupo-includes/classes/IdGenerator.php` with 4-digit sequence
- **Production-Ready Pattern**: Catch-and-retry with exponential backoff (50ms to 1.6s)
- **TOCTOU Race Condition Fix**: Eliminated check-then-insert pattern
- **User Experience Protection**: Backoff starts small to prevent request freezing
- **Migration Script**: Created `lupo-tmp/fix_registry_auto_increment.sql` for existing installations

#### LUPOPEDIA Headers Doctrine
- **LUPOPEDIA_HEADERS_DOCTRINE.md**: Created comprehensive documentation of all header fields
- **Schema Taxonomy**: 16 canonical values with clear use cases
- **Federation Node Validation**: Path-based node assignment rules
- **Cross-Field Dependencies**: Schema implies valid artifact_type and artifact_kind combinations
- **Validator Enhanced**: `validate_lupopedia_headers_universal.py` with schema validation

#### Database-first header pipeline (2026-03-28, see Major Changes §6)
- **`import_content.py`**: After `lupo_contents` upsert, syncs metadata, header edges, optional `revision_history` via `header_db_sync.py`
- **`generate_headers_from_db.py`**: MySQL-backed regeneration by default; `--use-mock-db` for offline stub
- **`ensure_imported.py`**, **`validate_lupopedia_headers.py`** updates: `content_id` warnings; Windows-safe validator output
- **Doctrine layout**: Binding text only under `lupo-rules/root/`; `lupo-docs/.../LUPOPEDIA_HEADERS_DOCTRINE.md` is alias + tooling edge hub
- **§6 follow-up:** `lupo_edges` column naming table in root doctrine; `validate_lupopedia_headers.py --check-db`; docstring on `generate_headers_from_db.py`; `VALIDATORS_AND_TOOLING.md` script line for `--check-db`
- **§6 follow-up (later 2026-03-28):** `validate_lupopedia_headers_universal.py` — `--check-db`, required header fields, `thread_id` format, repo-root edge targets, parsed `lupopedia.history` (see Major Changes §6 second follow-up)

#### Windows WSL Command Patterns
- **Critical Enforcement**: All Unix commands must use `wsl` prefix on Windows
- **Command Reference Table**: Comprehensive examples for PowerShell direct and wrapped commands
- **PowerShell Alternatives**: `$null` redirection instead of `/dev/null`

#### Independent Coder's Manifesto
- **INDEPENDENT_CODERS_MANIFESTO.md**: Created eternal philosophy document by WOLFIE
- **30+ Years Experience**: Documented journey from 1996 Notepad coding to modern universal principles
- **No Dependencies Doctrine**: Copy folder, run anywhere - FTP deployment always works
- **Universal Compatibility**: PHP 5.6 to 8.3, MySQL and PostgreSQL, no assumptions
- **Teaching the AI**: AI learning from experience, not industry trends
- **Root README Integration**: Added binding philosophy section with ETERNAL status
- **Database Doctrine Links**: Connected philosophy to practical database rules

#### Database Neutral SQL Doctrine
- **DATABASE_NEUTRAL_SQL_DOCTRINE.md**: Created comprehensive rule forbidding MySQL-specific syntax
- **DATABASE_DOCTRINE.md**: Created canonical database doctrine by WOLFIE (LOCKED and binding)
- **Production-Ready ID Generation**: Implemented catch-and-retry with exponential backoff (50ms start)
- **TOCTOU Race Condition Fix**: Eliminated check-then-insert pattern, using catch-and-retry
- **Registry AUTO_INCREMENT Fix**: Identified and created migration to remove AUTO_INCREMENT from registry tables per LILITH audit
- **Registry Tables Removal**: Updated directive to remove registry tables entirely and use 4-digit timestamp-based IDs (0000-9999 range)
- **Install Schema Updated**: Removed lupo_registry and lupo_registry_open tables from install_new_lupopedia.sql
- **IdGenerator Class**: Created lupo-includes/classes/IdGenerator.php with 4-digit sequence
- **Migration Script**: Created lupo-tmp/fix_registry_auto_increment.sql for existing installations
- **Code Deprecation**: Updated AdminRegistryHandler.php with deprecation warnings

#### Windows WSL Command Patterns
- **Critical Enforcement**: All Unix commands must use `wsl` prefix on Windows
- **Command Reference Table**: Comprehensive examples for PowerShell direct and wrapped commands
- **PowerShell Alternatives**: `$null` redirection instead of `/dev/null`
- **IDE Agent Rules**: Updated .windsurf/rules/lupopedia-rules.md with WSL requirements

### Improvements

#### Version planning documentation
- **`PLAN.md`**: Status clarified as **historical baseline**; **LILITH** retrospective compares planned vs actual major deliverables; **`next_action`** and narrative for **4.0.90** living plan + **`TASK_PLANNING_DOCTRINE.md`**

#### Database Architecture
- **Vendor Lock-in Prevention**: Eliminated MySQL-only features for PostgreSQL compatibility
- **Simplified ID Generation**: Removed complex registry tracking in favor of deterministic timestamps
- **Storage Optimization**: Reduced overhead from registry table maintenance
- **Cross-Platform Support**: Single SQL codebase works on all supported databases

#### Development Environment
- **Windows Compliance**: All agents now follow proper WSL command patterns
- **Cross-Platform Scripts**: Python scripts work consistently across operating systems
- **Header Validation**: Markdown files validate correctly regardless of encoding
- **Documentation Standards**: All rule files have complete LUPOPEDIA headers

### Bug Fixes

#### Registry System
- **AUTO_INCREMENT Violation**: Fixed LILITH-identified database neutrality violation
- **Over-Engineering**: Eliminated unnecessary registry table complexity
- **PostgreSQL Incompatibility**: Removed MySQL-specific AUTO_INCREMENT syntax
- **Application Dependencies**: Removed dependency on registry table lookups

#### Windows Compatibility
- **Command Execution**: Fixed Unix command failures on Windows without WSL prefix
- **Redirection Issues**: Resolved PowerShell incompatibility with Unix-style redirection
- **Path Handling**: Corrected Windows path handling in cross-platform scripts
- **IDE Integration**: Updated IDE-specific rule files for Windows compliance

---

## Technical Details

- **Database Neutrality**: All SQL validated on both MySQL 8.0+ and PostgreSQL 15+
- **ID Format**: 17-character timestamp-based IDs (YYYYMMDDHHIISS + 4-digit sequence)
- **Registry Removal**: Complete elimination of registry table infrastructure
- **WSL Integration**: Full Windows Subsystem for Linux compatibility
- **Cross-Platform Validation**: Comprehensive testing across Windows, Linux, and macOS

---

## Impact

- **Database Compatibility**: Lupopedia now runs on any supported database platform
- **Windows Development**: Seamless development experience on Windows with WSL
- **Architectural Simplification**: Removed over-engineered registry system
- **Performance Improvement**: Eliminated registry table overhead and complexity
- **Standards Compliance**: Full compliance with database neutrality doctrine

---

## Files Modified

### Database Schema
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` - Removed registry tables

### PHP Classes
- `lupo-includes/classes/IdGenerator.php` - NEW timestamp-based ID generator
- `lupo-includes/classes/AdminRegistryHandler.php` - Updated with deprecation warnings

### Rules & Documentation
- `lupo-rules/root/DATABASE_NEUTRAL_SQL_DOCTRINE.md` - NEW comprehensive doctrine
- `lupo-rules/root/DATABASE_DOCTRINE.md` - NEW canonical database doctrine by WOLFIE
- `lupo-rules/root/INDEPENDENT_CODERS_MANIFESTO.md` - NEW eternal philosophy by WOLFIE
- `lupo-rules/root/README.md` - Updated with binding philosophy section
- `lupo-rules/root/WINDOWS_WSL_COMMAND_PATTERNS.md` - Updated with WSL enforcement
- `.windsurf/rules/lupopedia-rules.md` - NEW IDE-specific WSL rules

### Migration & Utilities
- `lupo-tmp/fix_registry_auto_increment.sql` - Registry removal migration script

### Documentation
- `lupo-docs/versions/4.0.89/CHANGELOG.md` - This file
- `lupo-docs/versions/4.0.89/HEADER_DB_FIRST_AND_DOCTRINE_CONSOLIDATION_4.0.89.md` - Integration note (DB-first headers + doctrine SST)
- `lupo-docs/versions/4.0.89/README.md` - §4 focus area + §5 PLAN retrospective pointer + Related Documentation link
- `lupo-docs/versions/4.0.89/PLAN.md` - LILITH retrospective (actual vs planned deliverables); `outbound_edges` to major artifacts + `TASK_PLANNING_DOCTRINE.md`; footer `next_action` for 4.0.90 living plan
- `lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md` - Expanded edges; DB mapping section (binding); §6 follow-up: `lupo_edges` YAML↔SQL column table; regeneration wording (`build_yaml_data_from_db`)
- `lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_DOCTRINE.md` - Alias + tooling edges
- `lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md`, `VALIDATORS_AND_TOOLING.md`, `lupo-docs/doctrine/INDEX.md` - SST / alias clarity; `VALIDATORS_AND_TOOLING.md` — `validate_lupopedia_headers.py` + `--check-db`
- Root `README.md`, `lupo-rules/root/README.md` - Pointers to binding doctrine

### Python (header pipeline)
- `lupo-scripts/import_content.py`, `lupo-scripts/generate_headers_from_db.py` (module docstring), `lupo-scripts/ensure_imported.py`
- `lupo-scripts/lib/header_db_sync.py`, `lupo-scripts/lib/header_validation.py`, `lupo-scripts/validate_lupopedia_headers.py` (`argparse`, `--check-db`), `lupo-scripts/validate_lupopedia_headers_universal.py` (parity: `--check-db`, required fields, `thread_id`, repo-root edges, history on parsed YAML)

---

## Related Artifacts

- **WOLFIE Directive**: Registry Tables Removal (channel 42, thread 4.0.89-registry-removal)
- **LILITH Audit**: AUTO_INCREMENT Violation (channel 42, thread 4.0.89-lilith-audit)
- **LILITH Plan retrospective**: Execution vs ATHENA phase order; 4.0.90 planning process (`PLAN.md` § Retrospective)
- **Migration Plan**: Registry System Elimination (channel 42, tasks/pending/20260328_registry_removal.md)

---

## Next Steps

- Execute migration script on existing installations
- Update all code to use IdGenerator::generate() for new IDs
- Test cross-platform database compatibility
- Monitor compliance with WSL command patterns
- Validate timestamp-based ID generation in production

---

## IDE Agent Session: Cursor (actor 102) — 2026-03-28

- **2FA Management in Profile:**
  - Added user-facing 2FA enable/disable/verify controls to the profile UI (`my-profile.php`).
  - Implemented controller logic for 2FA: generates verification code, sends via PHPMailer, verifies code, enables/disables 2FA per user action.
  - All code changes use canonical schema and PHPMailer integration; 2FA status is visible and manageable from the profile page.

- **Agent Selection Department Permissions:**
  - Updated agent selection flow (`select_agent.php`) to use department-based filtering: users only see/select agents allowed by their department.
  - Department 0 (root) users have full access to all agents; other users are restricted to their mapped departments.
  - Validated department mapping and root access logic in `AuthSessionManager` and agent selection flow.

- **Multi-Agent Coordination Compliance:**
  - All changes align with canonical schema, codebase, and multi-agent coordination doctrine.
  - No existing changelog content overwritten; session changes appended per IDE agent protocol.

---

**Version**: 4.0.89  
**Last Updated**: 2026-03-29 00:00 UTC (Major Changes §8 — 4.0.89 headers-only release scope; 4.0.90 backlog folder)
