---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/schema_and_toon_alignment_context.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/schema_and_toon_alignment_context.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: documentation
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: documentation
  prd_cluster: null
  title: ''
  summary: ''
---
# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  

lupopedia.edges: []
  file_path_from_root: "docs\doctrine\SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md"
  file_hash: "329848452d3f7ed8b38069c547bea0b07ce0d2c37c4e2dcb376eed98669bd518"
  file_path_from_root: "docs\doctrine\SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md"
  file_hash: "cb52aa96da839048e8c13f8b6d051bb818602180b14dbc0824dac7d124141b44"
  last_updated_utc: "20260228"
  system_version: "4.0.88"
  channel_id: 1
  actor_id: 102
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "schema_and_toon_alignment_contextmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.88"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "cursor"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
lupopedia.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md
file.last_modified_system_version: "4.0.88"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_vector: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md
---

# Schema and TOON Alignment — Context for AI Agents (Copilot, Cursor, etc.)

Use this as a prompt or reference when working on Lupopedia schema, migrations, or TOONs. It summarizes what was done to align the live database and TOONs with doctrine.

---

## Copy-paste prompt for Copilot

When working on Lupopedia schema, migrations, or TOONs, use this context:

- **Canonical schema** is `database/migrations/install_new_lupopedia.sql` (not TOONs). TOONs are generated **from** the live database and must not be edited manually.
- **Two one-time migrations** were run to align the live DB with doctrine: (1) `dev_20260204_fix_schema_alignment.sql` — MODIFY COLUMN for all non-PK columns to match install, with backtick-quoted column names and `lupo_contents.body` as longtext to avoid truncation; (2) `dev_20260205_doctrine_alignment_phase2.sql` — drop UNSIGNED on PKs (keep AUTO_INCREMENT), fix tinyint(1)→tinyint, and timestamp→bigint in lupo_crafty_user_mapping.
- **Unification (groups → departments):** `migration_unify_groups_into_departments.sql` added department_id to permissions, collections, collection_tabs, contents, and analytics tables; dropped group_id and removed lupo_groups and lupo_actor_group_membership. Schema and TOONs are department-only; no group tables exist.
- **Doctrine:** no UNSIGNED, no display widths (e.g. no int(11), tinyint(1)), no timestamp/datetime (use BIGINT YYYYMMDDHHIISS), no FKs/triggers. Check TOONs with `python database/check_toon_doctrine_alignment.py`.

---

## Canonical sources

- **Canonical schema:** `database/migrations/install_new_lupopedia.sql` (not TOONs, not the live DB).
- **TOONs:** Generated **from** the live database (e.g. `scripts/generate_toons.py`). They reflect current DB state; they are not edited manually.
- **Doctrine:** LUPOPEDIA_DOCTRINE.md — SQL Doctrine (no UNSIGNED, no display widths, no FKs/triggers), Temporal Doctrine §5 (BIGINT YYYYMMDDHHIISS, no timestamp/datetime).

---

## What was done (alignment work)

### 1. First one-time migration: live DB → install schema

- **File:** `database/migrations/dev_20260204_fix_schema_alignment.sql`
- **Purpose:** Bring live database column definitions into alignment with `install_new_lupopedia.sql`.
- **Contents:** ALTER TABLE … MODIFY COLUMN only (no CREATE/DROP table, no data migrations). All **non-PK** columns were modified to match install (type, NULL/NOT NULL, DEFAULT). **PK columns were skipped** to preserve AUTO_INCREMENT.
- **Details:**
  - Column names in MODIFY COLUMN are **backtick-quoted** (e.g. `` `utc_timestamp` ``) to avoid MySQL reserved-word errors.
  - **lupo_contents.body** was set to **longtext** in this migration (not text) to avoid "Data too long for column" on existing rows; install keeps `text` for new installs.
- **Generator script:** `database/generate_schema_alignment_migration.py` (reads install, emits MODIFY COLUMN for every non-PK column).

### 2. TOON doctrine check

- **Script:** `database/check_toon_doctrine_alignment.py`
- **Purpose:** Scan all TOONs in `docs/toons/*.toon.json` for doctrine violations.
- **Checks:** No UNSIGNED, no integer display widths (e.g. no tinyint(1), int(11)), no timestamp/datetime types, no CURRENT_TIMESTAMP / ON UPDATE CURRENT_TIMESTAMP; doctrine_metadata has no_foreign_keys and no_triggers.
- **Result after first migration:** TOONs still had violations because the **live DB** still had UNSIGNED on PKs, tinyint(1) in some crafty tables, and timestamp in lupo_crafty_user_mapping — TOONs are generated from the DB, so they reflected that.

### 3. Second one-time migration: doctrine alignment (PKs + misc)

- **File:** `database/migrations/dev_20260205_doctrine_alignment_phase2.sql`
- **Purpose:** Fix remaining doctrine violations in the live DB so that **regenerating TOONs** would produce doctrine-aligned TOONs.
- **Contents:**
  - **PK columns:** MODIFY to drop UNSIGNED and keep AUTO_INCREMENT (e.g. `actor_meta_id` → bigint NOT NULL AUTO_INCREMENT) for 22 tables.
  - **Display width:** tinyint(1) → tinyint in lupo_crafty_syntax_chat_mod_departments and lupo_crafty_syntax_chat_questions.
  - **Temporal §5:** lupo_crafty_user_mapping `created_at` and `updated_at` → timestamp to bigint NOT NULL DEFAULT 0.
- **Order:** Run after the first migration; then **regenerate TOONs**.

### 4. Third pass: verification

- After running the second migration and regenerating TOONs, `check_toon_doctrine_alignment.py` was run again.
- **Result:** All 208 TOONs reported as aligned with doctrine (no UNSIGNED, no display widths, no timestamp/datetime, no FKs/triggers).

---

## Takeaways for AI agents

1. **install_new_lupopedia.sql** is the single source of truth for schema; do not change it unless explicitly asked. TOONs are generated from the DB and must not be edited manually.
2. **Organizational scope** is department only. Tables lupo_groups and lupo_actor_group_membership do not exist. Permissions and scoping use department_id (lupo_permissions, lupo_collections, lupo_collection_tabs, lupo_contents, lupo_analytics_*). After running the unification migration, regenerate TOONs so they reflect the current schema.
3. **One-time dev migrations** (doctrine §18.9) are for aligning existing DBs; they use only ALTER TABLE (MODIFY COLUMN, ADD/DROP INDEX). The unification migration added department_id and dropped group tables; it is run once on existing DBs. No CREATE/DROP TABLE in alignment migrations except as in that unification script.
4. **Reserved words:** In migration SQL, quote column names with backticks (e.g. `` `utc_timestamp` ``) to avoid MySQL syntax errors.
5. **Large text columns:** If changing to a smaller type would truncate data (e.g. body → text when rows exceed 64KB), use longtext in the migration and document why; install can stay as text for new installs.
6. **Checking alignment:** Run `python database/check_toon_doctrine_alignment.py` after any schema or TOON changes to verify TOONs still match doctrine.

---

## Files reference

| Item | Path |
|------|------|
| Canonical schema | `database/migrations/install_new_lupopedia.sql` |
| First alignment migration | `database/migrations/dev_20260204_fix_schema_alignment.sql` |
| Second (doctrine) migration | `database/migrations/dev_20260205_doctrine_alignment_phase2.sql` |
| Unification (groups → departments) | `database/migrations/migration_unify_groups_into_departments.sql` |
| Migration generator | `database/generate_schema_alignment_migration.py` |
| TOON doctrine check | `database/check_toon_doctrine_alignment.py` |
| TOONs | `docs/toons/*.toon.json` |
| Doctrine | `docs/doctrine/LUPOPEDIA_DOCTRINE.md` |
