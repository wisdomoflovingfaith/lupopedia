---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  system_version: "4.0.80"
  file_path_from_root: "lupo-channels/88/threads/1004/20260319_230000_wolfie_question_crafty_lupo_table_mapping.md"
  web_path: "http://www.lupopedia.com/lupo-channels/88/threads/1004/20260319_230000_wolfie_question_crafty_lupo_table_mapping"
  last_modified_utc: "20260319"
  project_id: 0
  project_slug: "lupopedia-core"
  channel_id: 88
  thread_id: 1004
  task_id: "task_channel88_crafty_lupo_mapping_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "question"
  purpose: "Thread 1004 question — Crafty Syntax livehelp_ to Lupopedia lupo_ table mapping and documentation location"
  tags: ["channel88", "crafty_syntax", "lupopedia", "mapping", "migration", "4.0.80"]
  message_type: "question"
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql", type: "requires_reading", weight: 1.0, reason: "Crafty Syntax 3.7.5 source schema — all livehelp_ table definitions" }
    - { to: "lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql", type: "requires_reading", weight: 1.0, reason: "Canonical import: livehelp_ data → lupo_ tables" }
    - { to: "lupo-database/lupopedia/mysql/import/drop_old_crafty_syntax_tables.sql", type: "references", weight: 0.8, reason: "Post-upgrade drop of legacy tables" }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "requires_reading", weight: 1.0, reason: "Lupopedia 4.x canonical schema — all lupo_ tables" }
    - { to: "lupo-docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 1.0, reason: "Index of legacy → Lupopedia table mappings" }
    - { to: "lupo-docs/channels/schema/migrations/analysis/CRAFTY_SYNTAX_TO_LUPOPEDIA_STRUCTURED_MAPPING.md", type: "references", weight: 1.0, reason: "Structured mapping report — table and column mapping" }
    - { to: "lupo-docs/channels/schema/migrations/analysis/CRAFTY_SYNTAX_TO_LUPOPEDIA_ANALYSIS.md", type: "references", weight: 0.9, reason: "Crafty → Lupopedia analysis" }
    - { to: "lupo-docs/database/lupopedia/tables/PROJECT_REGISTRY_SCHEMA_DESIGN.md", type: "references", weight: 0.7, reason: "Registry/schema design context if relevant to mapping" }
    - { to: "lupo-docs/doctrine/MIGRATION_DOCTRINE.md", type: "references", weight: 0.9, reason: "Migration doctrine — two-place rule, no hidden logic" }
    - { to: "lupo-docs/doctrine/CRAFTY_SYNTAX_INTEGRATION_PLAN.md", type: "references", weight: 0.8, reason: "Crafty Syntax integration and upgrade path" }
    - { to: "lupo-rules/root/single-install-no-4.0-upgrade-doctrine.md", type: "depends_on", weight: 0.9, reason: "Upgrade path: Crafty 3.7.5 → Lupopedia 4.0.x only; no Lupopedia→Lupopedia until 4.1" }
    - { to: "lupo-channels/66/threads/1001/20260319_200000_wolfie_audit_channel66_system_phase1_thread_repost.md", type: "related_question", weight: 0.5, reason: "Channel 66 ingestion/indexing — may inform migration pipeline" }
    - { to: "lupo-channels/66/threads/1002/20260319_000000_wolfie_question_lupopedia_headers_source_of_truth.md", type: "related_question", weight: 0.5, reason: "Channel 66 headers/metadata source of truth — may inform migration metadata" }
    - { to: "lupo-docs/database/lupopedia/tables/migrations/livehelp_sessions_migration.md", type: "references", weight: 0.8, reason: "Per-table migration doc example — livehelp_sessions" }
    - { to: "lupo-docs/database/lupopedia/tables/migrations/livehelp_messages_migration.md", type: "references", weight: 0.8, reason: "Per-table migration doc — livehelp_messages" }
    - { to: "lupo-docs/database/lupopedia/tables/migrations/livehelp_transcripts.md", type: "references", weight: 0.8, reason: "Per-table migration doc — transcripts → dialog" }
    - { to: "lupo-docs/doctrine/migrations/livehelp_migrations_readme.md", type: "references", weight: 0.85, reason: "Relocation notice: livehelp_*.md moved to tables/migrations/; import SQL See: uses old path" }
lupopedia.interpretation:
  whoami:
    facet: "orchestrator"
    runtime_context: "question_thread"
    session_mode: "definition"
    project_id: 0
    project_slug: "lupopedia-core"
    channel_id: 88
    thread_id: 1004
  whoareyou:
    actor_id: 1
    actor_name: "wolfie"
    identity_source: "canonical_registry"
    state: "active"
    authority_level: "canonical_orchestrator"
  whoopposesyou: "lilith"
lupopedia.footer:
  version: "4.0.80"
  last_verified: "20260319"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "ATHENA: structural mapping model (table + column relationships)"
    - "LILITH: attack mapping completeness and correctness"
    - "HEPHAESTUS: implementation of migration pipeline (after mapping canonical)"
---

# file: Thread 1004 Question — Crafty → Lupo Mapping — session: L-LUPO-ROOT-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/lupo-channels/88/threads/1004/20260319_230000_wolfie_question_crafty_lupo_table_mapping

# Thread 1004 — Crafty Syntax → Lupopedia Table Mapping

**Channel:** 88  
**Thread:** 1004  
**Author:** WOLFIE (actor_id 1)  
**Status:** Question thread — working material. Not canonical doctrine.  
**Date:** 20260319  

---

## 1. Explicit question

**Thread 1004 question:** *How do the old Crafty Syntax `livehelp_` tables map to the new `lupo_` tables, and where is all the documentation for that mapping?*

This thread is the central question node for:

- 1:1, 1:many, and many:1 mappings between Crafty Syntax 3.7.5 `livehelp_*` and Lupopedia 4.x `lupo_*` tables  
- What data moved, was renamed, split, merged, or dropped  
- Location of existing mapping documentation and gaps  
- Support for upgrade path (Crafty Syntax → Lupopedia), install/migration correctness, and 4.0.x → 4.1.x readiness  

---

## 2. Why this question exists

### Upgrade path dependency

- The **only** supported upgrade path for 4.0.x is **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** (per single-install doctrine). No Lupopedia→Lupopedia upgrade until 4.1.0.
- The installer and import pipeline rely on **deterministic** mapping: which `livehelp_` table and columns feed which `lupo_` table and columns.
- Undocumented or inferred transformations create risk: wrong data placement, lost fields, broken references, and non-reproducible upgrades.

### Need for deterministic migration

- Mapping must be **explicit and reproducible**. Doctrine: no hidden migration logic; DB is dumb storage; all logic in application (or in documented SQL that is idempotent and explicit).
- ID handling: reserved-ID doctrine applies to registry-backed tables; Crafty-origin IDs may be preserved or translated by documented rules. AUTO_INCREMENT removal and deterministic ID assignment must be documented where they apply.

### Risk of undocumented transformations

- If mapping is scattered across SQL, code, and ad-hoc docs, future changes (4.1.x auto-installer, new lupo_ tables) will introduce drift and bugs.
- A single, thread-owned investigation that **locates** all mapping docs and **identifies gaps** supports a future canonical mapping artifact.

---

## 3. Scope of investigation

### 3.1 Table mapping

- **List all `livehelp_*` tables** — from `old_crafty_syntax_3_7_5_start.sql`: e.g. livehelp_autoinvite, livehelp_channels, livehelp_config, livehelp_departments, livehelp_emailque, livehelp_emails, livehelp_identity_daily, livehelp_identity_monthly, livehelp_keywords_daily, livehelp_keywords_monthly, livehelp_layerinvites, livehelp_leads, livehelp_leavemessage, livehelp_messages, livehelp_modules, livehelp_operator_channels, livehelp_operator_departments, livehelp_operator_history, livehelp_paths_firsts, livehelp_questions, livehelp_quick, livehelp_referers_daily, livehelp_sessions, livehelp_transcripts, livehelp_users, and any others present in the source file.
- **List all `lupo_*` tables** — from `install_new_lupopedia.sql` (required tables; future-features tables in separate file per doctrine).
- **Classify:**
  - **Renamed:** livehelp_X → lupo_Y (1:1 name/role change)
  - **Split:** one livehelp_ table → multiple lupo_ tables
  - **Merged:** multiple livehelp_ tables → one lupo_ table
  - **Dropped:** livehelp_ table has no lupo_ counterpart (data not imported or intentionally discarded)

### 3.2 Column mapping

- For each mapped table pair (or set):
  - How column **names** changed (e.g. recno → dialog_thread_id, user_id → actor_id)
  - How **types** changed (e.g. DATETIME → BIGINT YYYYMMDDHHIISS, UNSIGNED removal per doctrine)
  - How **semantics** changed (e.g. transcript blob → thread + message rows)
- **BIGINT timestamp conversion:** Crafty date/time columns → Lupopedia BIGINT UTC YmdHis (explicit in application; no DB-side defaults).
- **ID determinism:** Where AUTO_INCREMENT was removed or replaced by explicit ID (reserved-ID doctrine); how Crafty IDs are preserved or reallocated.

### 3.3 Data transformation

- **Normalization:** e.g. config keys → JSON or separate columns
- **Denormalization:** e.g. single transcript → thread + messages
- **Metadata extraction:** e.g. headers, session context
- **Header generation:** if any Lupopedia headers or metadata are derived from Crafty data during import (document where and how)

### 3.4 Documentation location

- **Where mapping is currently documented:**
  - `lupo-docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md` — index of legacy → new
  - `lupo-docs/channels/schema/migrations/analysis/CRAFTY_SYNTAX_TO_LUPOPEDIA_STRUCTURED_MAPPING.md` — structured table/column mapping
  - `lupo-docs/channels/schema/migrations/analysis/CRAFTY_SYNTAX_TO_LUPOPEDIA_ANALYSIS.md` — analysis
  - Per-table migration docs under `lupo-docs/database/lupopedia/tables/migrations/` (e.g. livehelp_sessions_migration.md, livehelp_messages_migration.md, livehelp_transcripts.md, livehelp_qa_migration.md, livehelp_departments_migration.md, etc.)
  - Import SQL: `import_from_old_crafty_syntax.sql` (canonical INSERT/SELECT mapping)
- **What is missing:** e.g. column-level mapping for all tables, transformation rules in one place, ID translation matrix
- **What is inconsistent:** e.g. MIGRATION_MAPPING_REFERENCE vs import SQL vs per-table docs

### 3.5 Existing per-table mapping files (livehelp_*.md) — CONFIRMED

**Many mapping files already exist.** Search for `livehelp_*.md` under the repo:

- **Canonical location (current):** `lupo-docs/database/lupopedia/tables/migrations/`
- **Count:** 62+ files (e.g. livehelp_autoinvite_migration.md, livehelp_channels_migration.md, livehelp_config_migration.md, livehelp_departments_migration.md, livehelp_sessions_migration.md, livehelp_transcripts_migration.md, livehelp_users_migration.md, and many others). Some tables have both a short-name doc (e.g. livehelp_sessions.md) and a `_migration` doc (livehelp_sessions_migration.md).
- **Relocation notice:** `lupo-docs/doctrine/migrations/livehelp_migrations_readme.md` states that legacy migration docs were **relocated** from `docs/doctrine/migrations/` to `lupo-docs/database/lupopedia/tables/migrations/`. Use the latter as the canonical path.

**Import SQL “See:” comments:** `import_from_old_crafty_syntax.sql` contains inline `-- See:` comments that point to per-table migration docs. These references use the **old path** and should be updated for consistency:

| Import SQL currently says | Canonical location (use this) |
|--------------------------|-------------------------------|
| `docs/doctrine/migrations/livehelp_*_migration.md` or `/docs/doctrine/migrations/...` | `lupo-docs/database/lupopedia/tables/migrations/livehelp_*_migration.md` |

**Tables with a “See:” in import_from_old_crafty_syntax.sql (non-exhaustive):** livehelp_autoinvite, livehelp_channels, livehelp_config, livehelp_departments, livehelp_emailque, livehelp_emails, livehelp_identity, livehelp_keywords, livehelp_layerinvites, livehelp_leads, livehelp_leavemessage, livehelp_messages, livehelp_modules, livehelp_modules_dep, livehelp_operator_channels, livehelp_operator_departments, livehelp_operator_history, livehelp_qa, livehelp_questions, livehelp_quick, livehelp_smilies, livehelp_sessions, livehelp_referers_daily, livehelp_transcripts, livehelp_websites, livehelp_users.

**Action for HEPHAESTUS or maintainers:** Update the `-- See:` lines in `import_from_old_crafty_syntax.sql` to the canonical path `lupo-docs/database/lupopedia/tables/migrations/<filename>` so that the import script and the docs tree stay in sync.

---

## 4. Known sources

| Source | Path | Role |
|--------|------|------|
| Crafty Syntax 3.7.5 schema (livehelp_*) | lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql | Source table definitions and seed data |
| Lupopedia install schema (lupo_*) | lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql | Target table definitions |
| Import mapping (data movement) | lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql | Canonical livehelp_ → lupo_ INSERT/SELECT |
| Drop legacy tables | lupo-database/lupopedia/mysql/import/drop_old_crafty_syntax_tables.sql | Post-upgrade cleanup |
| Mapping index | lupo-docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md | Legacy → new table index and notes |
| Structured mapping report | lupo-docs/channels/schema/migrations/analysis/CRAFTY_SYNTAX_TO_LUPOPEDIA_STRUCTURED_MAPPING.md | Table and column mapping |
| Crafty → Lupo analysis | lupo-docs/channels/schema/migrations/analysis/CRAFTY_SYNTAX_TO_LUPOPEDIA_ANALYSIS.md | Analysis and context |
| Migration doctrine | lupo-docs/doctrine/MIGRATION_DOCTRINE.md | Two-place rule; no hidden logic |
| Crafty integration plan | lupo-docs/doctrine/CRAFTY_SYNTAX_INTEGRATION_PLAN.md | Upgrade and integration |
| Single-install doctrine | lupo-rules/root/ (single-install-no-4.0-upgrade-doctrine) | Crafty 3.7.5 → 4.0.x only |
| Per-table migration docs | lupo-docs/database/lupopedia/tables/migrations/livehelp_*.md | Per legacy table migration notes (62+ files); canonical location |
| Relocation readme | lupo-docs/doctrine/migrations/livehelp_migrations_readme.md | Documents move from docs/doctrine/migrations/ to tables/migrations/ |
| PROJECT_REGISTRY_SCHEMA_DESIGN | lupo-docs/database/lupopedia/tables/PROJECT_REGISTRY_SCHEMA_DESIGN.md | Registry/schema context if relevant |
| TOON files | lupo-docs/database/lupopedia/tables/active/*.md, lupo-database/lupopedia/json/*.json | lupo_ table column reference (from install SQL) |

---

## 5. Open sub-questions

- Is there a **single complete** mapping document today (table + column + transformation), or is it spread across MIGRATION_MAPPING_REFERENCE, CRAFTY_SYNTAX_TO_LUPOPEDIA_STRUCTURED_MAPPING, import SQL, and per-table docs?
- Are mappings **deterministic** (documented rules, same result every run) or **inferred** (only visible in code/SQL)?
- How are **IDs translated** (e.g. livehelp_users.id → lupo_auth_users.auth_user_id or actor_id; preserved vs reallocated)?
- What happens to **unsupported legacy fields** (dropped, stored in JSON, or ignored)?
- How are **relationships preserved** without foreign keys (e.g. thread_id, actor_id, department_id — application-level consistency only)?
- Does `import_from_old_crafty_syntax.sql` cover **all** livehelp_ tables that have a lupo_ target, or are some mappings only in PHP/code?
- For 4.1.x readiness: what would a **canonical mapping artifact** (e.g. machine-readable table/column map) need to contain, and where would it live?

---

## 6. Next actions

- **ATHENA:** Produce or refine a **structural mapping model** (table + column relationships, 1:1 / 1:many / many:1) and identify gaps in current docs.
- **LILITH:** **Attack** mapping completeness and correctness — missing tables, wrong column mappings, undocumented transformations, ID handling risks.
- **HEPHAESTUS:** When mapping is canonical and thread-resolved, **implement or verify** migration pipeline (import SQL, install order, seed order) and document any code that performs transformation outside the SQL.
- **Thread 1004:** Keep all mapping artifacts, evidence, and closure in this thread until promoted (e.g. to lupo-docs/doctrine) only when the question is resolved and the system accepts a canonical mapping artifact.

---

## 7. Doctrine constraints (non-negotiable)

- **No foreign keys** — relationships are application-enforced only.
- **No hidden migration logic** — all mapping and transformation must be explicit (SQL or documented application code).
- **Deterministic mapping only** — reproducible upgrade; no implicit or environment-dependent behavior.
- **DB is dumb storage** — no triggers, stored procedures, or default timestamp logic; timestamps set in application (BIGINT UTC YmdHis).
- Mapping must be **explicit and reproducible** for install/migration correctness and 4.0.x → 4.1.x readiness.

---

*End of Thread 1004 question — Channel 88. Working material only.*
