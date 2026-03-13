# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\audits\DEPARTMENTS_GROUPS_ROLES_EXECUTION_SUMMARY.md"
  file_hash: "aa6c27663ee9b3e99fc9188ffd0df185fe635b39cdfd0af19668e9b4f0d57231"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\audits\DEPARTMENTS_GROUPS_ROLES_EXECUTION_SUMMARY.md"
  file_hash: "4ff9dd363f3050996877fd30364c54502060cf0837be51c2cf58ce8653842a57"
  file_path_from_root: "docs\audits\DEPARTMENTS_GROUPS_ROLES_EXECUTION_SUMMARY.md"
  file_hash: "3ff6e62e4238dffa833a610baa8d254e55954cac5d0093e173a5f278f6de92d6"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Departments / Groups / Roles Unification — Execution Summary (SQL Phase)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "audits", "departments_groups_roles_execution_summarymd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Departments / Groups / Roles Unification — Execution Summary (SQL Phase)

**Date:** 2026-02-12  
**Authority:** docs/audits/DEPARTMENTS_GROUPS_ROLES_PRE_EXECUTION_CHECKLIST.md, DEPARTMENTS_GROUPS_ROLES_IMPLEMENTATION_PLAN.md  
**Scope:** Schema migrations, installer SQL, seed SQL, Crafty import SQL, and SQL generators only. No PHP application code or doctrine document updates in this phase.

---

## 1. Files Created

| File | Purpose |
|------|----------|
| **database/migrations/migration_unify_groups_into_departments.sql** | One-time migration for existing DBs: add department_id to seven tables, add indexes, optional backfill, drop group_id and group-related indexes, drop lupo_actor_group_membership and lupo_groups. |

---

## 2. Files Updated

### 2.1 Installer and alignment migrations

| File | Changes |
|------|---------|
| **database/migrations/install_new_lupopedia.sql** | Removed CREATE TABLE lupo_groups and lupo_actor_group_membership (and their indexes). In lupo_permissions, lupo_collections, lupo_collection_tabs, lupo_contents, lupo_analytics_referers_periods, lupo_analytics_visits_daily, lupo_analytics_visits_monthly, lupo_analytics_visits_periods: replaced column group_id with department_id (same nullability/defaults). Replaced index names: uniq_target_group → uniq_target_department, idx_group → idx_department; added lupo_collection_tabs_idx_department. |
| **database/migrations/dev_20260204_fix_schema_alignment.sql** | Removed all MODIFY/ALTER for lupo_actor_group_membership and lupo_groups. For the seven tables: MODIFY group_id → MODIFY department_id (types unchanged). |
| **database/migrations/dev_20260204_fix_schema_alignment_summary.txt** | Removed lines for lupo_actor_group_membership and lupo_groups; for the seven tables replaced "group_id -> …" with "department_id -> …". |
| **database/migrations/dev_20260206_reserved_word_column_renames.sql** | Removed block "lupo_actor_group_membership: role -> role_key" (table dropped). Renumbered remaining comments. |

### 2.2 Seed SQL

| File | Changes |
|------|---------|
| **database/install/seed_collection_0_content.sql** | Replaced all group_id column names and values with department_id. |
| **database/install/seed_collection_0_system_tabs.sql** | Replaced all group_id with department_id. |
| **database/install/seed_collection_0_hierarchical_tabs_3.0.12.sql** | Replaced all group_id with department_id. |
| **database/install/truth_test_data_captain_wolfie.sql** | Replaced group_id with department_id. |
| **database/install/lupopedia_seed_mysql.sql** | Removed INSERT into lupo_groups (GROUPS section). Renumbered following section (3. ACTORS → 2. ACTORS). |

### 2.3 Crafty Syntax import SQL

| File | Changes |
|------|---------|
| **database/migrations/import_from_old_crafty_syntax.sql** | lupo_collections INSERT: group_id → department_id in column list and VALUES. lupo_collection_tabs INSERTs (root and child): group_id → department_id in column lists and SELECT expressions. |
| **database/migrations/craftysyntax_to_lupopedia_mysql.sql** | lupo_collections INSERT: group_id → department_id. lupo_collection_tabs INSERTs (folders and children): group_id → department_id in column lists and SELECT. |

### 2.4 SQL generators (PHP)

| File | Changes |
|------|---------|
| **database/install/generate_content_seed.php** | Column list and insert array key: group_id → department_id; comment "group_id = NULL" → "department_id = NULL". |
| **database/install/generate_hierarchical_seed_3.0.12.php** | Generated INSERT column list: group_id → department_id (main tabs and sub-tabs). |

---

## 3. Schema Changes Applied

### 3.1 Tables modified (eight)

| Table | Change |
|-------|--------|
| lupo_permissions | Add department_id bigint DEFAULT NULL; remove group_id. Unique (target_type, target_id, department_id); index (department_id). |
| lupo_collections | Add department_id bigint DEFAULT NULL; remove group_id. Index (department_id). |
| lupo_collection_tabs | Add department_id bigint DEFAULT NULL; remove group_id. Index (department_id). |
| lupo_contents | Add department_id bigint DEFAULT NULL; remove group_id. Index (department_id). |
| lupo_analytics_referers_periods | Add department_id bigint NOT NULL DEFAULT 1; remove group_id. Index (department_id, period_date). |
| lupo_analytics_visits_daily | Add department_id bigint NOT NULL DEFAULT 1; remove group_id. Index (department_id, date_ymd). |
| lupo_analytics_visits_monthly | Add department_id bigint NOT NULL DEFAULT 1; remove group_id. Index (department_id, date_ym). |
| lupo_analytics_visits_periods | Add department_id bigint NOT NULL DEFAULT 1; remove group_id. Index (department_id, period_date). |

### 3.2 Tables dropped (two)

| Table | Change |
|-------|--------|
| lupo_actor_group_membership | DROP TABLE (after group_id removed from all referencing tables). |
| lupo_groups | DROP TABLE. |

### 3.3 Unchanged

- **lupo_gov_events.utc_group_id** — governance/time-group column; not related to lupo_groups. Left unchanged.

---

## 4. Confirmations

### 4.1 Installer SQL matches new schema

- **install_new_lupopedia.sql** defines no lupo_groups or lupo_actor_group_membership. All seven tables use department_id (correct type and nullability). Index names and definitions match the post-migration schema (uniq_target_department, idx_department, lupo_collection_tabs_idx_department, analytics idx_department with period/date columns).

### 4.2 Seed SQL matches new schema

- All updated seed files use department_id in column lists and values. No INSERT references group_id or lupo_groups. lupopedia_seed_mysql.sql no longer inserts into lupo_groups.

### 4.3 Crafty import SQL matches new schema

- import_from_old_crafty_syntax.sql and craftysyntax_to_lupopedia_mysql.sql use department_id in lupo_collections and lupo_collection_tabs INSERTs. No INSERTs reference lupo_groups or lupo_actor_group_membership.

### 4.4 No remaining SQL references to group_id or lupo_groups

- Grep verification: no occurrences of group_id or lupo_groups in the updated migration, installer, seed, Crafty import, or generator output targets. (TOONs and PHP app code are out of scope for this phase.)

### 4.5 Migration safe and idempotent

- **Run-once:** Migration is intended for a single run on existing DBs that still have group_id. Steps are ordered: add column → add indexes → (optional backfill commented out) → drop old indexes → drop group_id → drop tables. No ADD COLUMN IF NOT EXISTS (not used in existing Lupopedia migrations); operator must run once.
- **Idempotency:** No conditional logic in the migration file; re-running after success would fail on "column already exists" or "unknown column" and signals that migration was already applied. Safe for one-time execution.

### 4.6 Doctrine satisfied

- **No foreign keys:** None added in migration or installer.
- **No triggers, views, or stored procedures:** None introduced.
- **No modern SQL features:** Standard ALTER TABLE, CREATE INDEX, DROP TABLE only; compatible with PHP 5.3 → 8.1 and target MySQL.
- **Timestamps:** No DB-side timestamp automation; application doctrine unchanged.
- **PK/reference naming:** department_id follows doctrine (references lupo_departments.department_id). No new tables created; dropped tables had existing names.

---

## 5. Next Steps (out of scope for this phase)

1. **Application code:** Update PHP to use department_id (and lupo_actor_departments) instead of group_id/lupo_groups per implementation plan §2–4.
2. **Documentation:** Update REQUIRED_TABLES_4.1.0.md, DATABASE_SCHEMA.md, and doctrine docs per checklist step 8.
3. **TOON regeneration:** After applying schema (migration or fresh install), run `scripts/generate_toon_files.py` and commit TOONs per GOV-TOON-GENERATION-001.
4. **Smoke test:** New install (install_new_lupopedia + seed); run migration on a copy of an old DB; run Crafty import path; verify no runtime references to lupo_groups or group_id.

---

*End of execution summary.*
