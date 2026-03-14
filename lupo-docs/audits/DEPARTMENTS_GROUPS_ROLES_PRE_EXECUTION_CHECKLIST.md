# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\audits\DEPARTMENTS_GROUPS_ROLES_PRE_EXECUTION_CHECKLIST.md"
  file_hash: "01cee10687b8b8fd9ce15e30c4b9702c2ef691584e98335915caa1718989b2a9"
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
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "lupo-docs\audits\DEPARTMENTS_GROUPS_ROLES_PRE_EXECUTION_CHECKLIST.md"
  file_hash: "9beacf0d2f72dcbb9ecda4080fcec99d61c0a3aae731128c5800871b70e94e1c"
  file_path_from_root: "lupo-docs\audits\DEPARTMENTS_GROUPS_ROLES_PRE_EXECUTION_CHECKLIST.md"
  file_hash: "651cc00285f5c8aee3045061ded62ee69d10e5e5290996f716023517fd83cf79"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Departments / Groups / Roles Unification — Pre-Execution Checklist"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "audits", "departments_groups_roles_pre_execution_checklistmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Departments / Groups / Roles Unification — Pre-Execution Checklist

**Date:** 2026-02-12  
**Prerequisite:** lupo-docs/audits/DEPARTMENTS_GROUPS_ROLES_IMPLEMENTATION_PLAN.md  
**Constraint:** Preparation only; no code or schema changes in this document.

This checklist validates the execution order, enumerates every file and step, expands risks, and is the single authoritative reference for the execution phase.

---

## 1. Validated Execution Order

### 1.1 Validation of the 9-step sequence (§7.7)

| Step | Plan order | Valid? | Notes |
|------|------------|--------|--------|
| 1. Schema migration file | First | Yes | Run only on **existing** DBs that have group_id. Must be idempotent where possible (e.g. ADD COLUMN only if not exists, or document run-once). |
| 2. install_new_lupopedia.sql | Second | Yes | Ensures **new** installs never create group tables and use department_id. Independent of step 1 (different code path: new install vs upgrade). |
| 3. Seed and generated SQL | Third | Yes | Must be done in same pass as step 2 so seed runs succeed after a fresh install. |
| 4. import_from_old_crafty_syntax.sql | Fourth | Yes | Crafty upgrade path; must use department_id in INSERTs. |
| 5. Alignment migrations | Fifth | Yes | Prevents alignment scripts from failing on new installs (no group_id to MODIFY) and from referencing dropped tables. |
| 6. Permission resolution (optional) | Sixth | Yes | Application code; depends on schema being settled. |
| 7. Documentation | Seventh | Yes | Doctrine and schema docs. |
| 8. TOON regeneration | Eighth | Yes | **Must run after** schema is applied to a DB (run lupo-scripts/generate_toon_files.py against migrated or fresh DB). |
| 9. Smoke test | Last | Yes | Validates new install, migration on copy, and Crafty import path. |

### 1.2 Missing steps (added)

- **0. Pre-flight:** Backup target database(s); confirm no application code reads group_id or lupo_groups at runtime (already verified).
- **2b. Crafty import duplicate:** The file `lupo-database/migrations/craftysyntax_to_lupopedia_mysql.sql` also contains group_id in lupo_collections and lupo_collection_tabs INSERTs (same pattern as import_from_old_crafty_syntax.sql). It must be updated in the same pass as step 4. Listed in §3.
- **5b. Alignment summary artifact:** `lupo-database/migrations/dev_20260204_fix_schema_alignment_summary.txt` is a generated summary; update or regenerate after editing dev_20260204_fix_schema_alignment.sql so it does not list group_id/group tables for the dropped tables.

### 1.3 Steps that must be split

- **Step 8 (TOON regeneration):** Split conceptually into: (8a) Apply schema to a DB (migration for existing, or install+seed for new), (8b) Run `lupo-scripts/generate_toon_files.py` against that DB, (8c) Commit or archive generated TOONs per GOV-TOON-GENERATION-001. Execution phase may do 8b after 9 (smoke test) using the DB from the test.

### 1.4 Steps that must be merged

- None. Steps are logically separate (migration vs install vs seed vs import vs alignment vs code vs docs vs TOON vs test).

### 1.5 Reordering for safety or idempotency

- **Idempotency:** The **new migration file** (step 1) should be written so it can be run once. Use explicit steps: ADD COLUMN department_id (if supported: IF NOT EXISTS or check information_schema); backfill; DROP INDEX; DROP COLUMN group_id; DROP TABLE. Do not assume “run once” without documenting.
- **Order of operations within the migration file:** Add department_id to all seven tables → add new indexes → backfill → drop group_id and group-related indexes from the seven tables → DROP TABLE lupo_actor_group_membership → DROP TABLE lupo_groups. This order avoids dropping tables that are still referenced by columns in other tables.

### 1.6 Corrected execution order (authoritative)

1. **Pre-flight:** Backup DB; verify no runtime references to group_id/lupo_groups.
2. **Create and run one-time migration** (existing DBs only): `migration_unify_groups_into_departments.sql` — add department_id, add indexes, backfill, drop group_id and indexes, drop lupo_actor_group_membership, drop lupo_groups.
3. **Update install_new_lupopedia.sql:** Remove CREATE TABLE lupo_groups and lupo_actor_group_membership; add department_id and remove group_id from the seven tables per §1 of implementation plan.
4. **Update all seed and generator SQL:** generate_content_seed.php, generate_hierarchical_seed_3.0.12.php, seed_collection_0_content.sql, seed_collection_0_system_tabs.sql, seed_collection_0_hierarchical_tabs_3.0.12.sql, truth_test_data_captain_wolfie.sql, lupopedia_seed_mysql.sql.
5. **Update Crafty import SQL:** import_from_old_crafty_syntax.sql and craftysyntax_to_lupopedia_mysql.sql — department_id in INSERTs; remove group_id from column lists and values.
6. **Update alignment migrations:** dev_20260204_fix_schema_alignment.sql (remove group/group_id MODIFYs; add department_id MODIFYs for the seven tables); dev_20260206_reserved_word_column_renames.sql (remove block for lupo_actor_group_membership). Update or regenerate dev_20260204_fix_schema_alignment_summary.txt.
7. **Optional: Permission resolution** — AuthRoleResolver, SavedCollectionsService (department-based path).
8. **Documentation** — REQUIRED_TABLES_4.1.0.md, DATABASE_SCHEMA.md, doctrine docs per §5 of implementation plan.
9. **TOON regeneration** — Apply schema to a DB (migration or install+seed), then run `lupo-scripts/generate_toon_files.py`; commit/archive TOONs per GOV-TOON-GENERATION-001.
10. **Smoke test** — New install (install_new_lupopedia + seed); run migration on a copy of an old DB; run Crafty import path; verify no references to lupo_groups or group_id in application.

---

## 2. Required Migration SQL Files

### 2.1 Migration files to **create**

| Filename | Purpose | Run order |
|----------|---------|-----------|
| **migration_unify_groups_into_departments.sql** | One-time migration for existing DBs: ADD department_id to lupo_permissions, lupo_collections, lupo_collection_tabs, lupo_contents, lupo_analytics_referers_periods, lupo_analytics_visits_daily, lupo_analytics_visits_monthly, lupo_analytics_visits_periods; ADD new indexes; backfill where needed; DROP group_id and group-related indexes; DROP TABLE lupo_actor_group_membership; DROP TABLE lupo_groups. | Run once per existing DB before or during upgrade. |

**Suggested location:** `lupo-database/migrations/migration_unify_groups_into_departments.sql`

### 2.2 Migration files to **update**

| Filename | Purpose | Changes |
|----------|---------|---------|
| **lupo-database/migrations/install_new_lupopedia.sql** | Canonical install for new DBs. | Remove CREATE TABLE lupo_groups and all its indexes. Remove CREATE TABLE lupo_actor_group_membership and all its indexes. In lupo_permissions: remove group_id, add department_id bigint DEFAULT NULL; replace uniq_target_group and idx_group with unique (target_type, target_id, department_id) and index (department_id). In lupo_collections, lupo_collection_tabs, lupo_contents: remove group_id, add department_id bigint DEFAULT NULL; add index (department_id). In lupo_analytics_referers_periods, lupo_analytics_visits_daily, lupo_analytics_visits_monthly, lupo_analytics_visits_periods: remove group_id, add department_id bigint NOT NULL DEFAULT 1; add indexes (department_id, period_date) etc. Leave lupo_gov_events.utc_group_id unchanged. |
| **lupo-database/migrations/dev_20260204_fix_schema_alignment.sql** | Alignment of column types with canonical schema. | Remove all ALTER/MODIFY for lupo_groups and lupo_actor_group_membership. For the seven tables: remove MODIFY group_id; add MODIFY department_id (with correct type/nullability). Add new index creation for department_id where needed. |
| **lupo-database/migrations/dev_20260204_fix_schema_alignment_summary.txt** | Human-readable summary of alignment. | Regenerate or manually remove lines referencing lupo_groups, lupo_actor_group_membership, and group_id for the seven tables; add department_id lines for those tables. |
| **lupo-database/migrations/dev_20260206_reserved_word_column_renames.sql** | Reserved word renames. | Remove the block "1. lupo_actor_group_membership: role -> role_key" (lines 6–8). Table will be dropped; no rename. |
| **lupo-database/migrations/import_from_old_crafty_syntax.sql** | Crafty Syntax upgrade import. | In every INSERT into lupo_collections: replace group_id with department_id; use NULL or 1 in VALUES. In every INSERT into lupo_collection_tabs: same. No INSERTs into lupo_groups or lupo_actor_group_membership. |
| **lupo-database/migrations/craftysyntax_to_lupopedia_mysql.sql** | Alternative/legacy Crafty-to-Lupopedia SQL. | Same as import_from_old_crafty_syntax.sql for lupo_collections and lupo_collection_tabs: department_id instead of group_id. |

### 2.3 Migration files to **remove**

- **None.** No migration file is to be deleted. The new migration adds and drops; existing migrations are edited, not removed. Legacy migrations in `lupo-database/migrations_legacy/` are not run; document in a README or comment that group_id and group tables are retired.

### 2.4 Order in which migrations must run (for an existing DB)

1. **migration_unify_groups_into_departments.sql** (one-time).
2. Any other migrations that assume the new schema (e.g. dev_20260204, dev_20260206) — typically run after install or after this unification migration. For a **new** install, only install_new_lupopedia.sql + seed run; no unification migration.

---

## 3. Installer + Seed SQL Updates (File-by-File)

### 3.1 Installer SQL files to update

| File | Change |
|------|--------|
| lupo-database/migrations/install_new_lupopedia.sql | See §2.2. |

### 3.2 Seed SQL files to update

| File | Change |
|------|--------|
| lupo-database/install/seed_collection_0_content.sql | Replace every `group_id` column and value with `department_id`; use NULL or 1. Update comment "user_id = NULL, group_id = NULL" to "user_id = NULL, department_id = NULL". |
| lupo-database/install/seed_collection_0_system_tabs.sql | Replace every `group_id` column and value with `department_id`. |
| lupo-database/install/seed_collection_0_hierarchical_tabs_3.0.12.sql | Replace every `group_id` column and value with `department_id`. |
| lupo-database/install/truth_test_data_captain_wolfie.sql | Replace `group_id` with `department_id` in column list and values. |
| lupo-database/install/lupopedia_seed_mysql.sql | Remove the INSERT into lupo_groups (lines 21–27). The file uses wrong columns (domain_id, group_name, created_at) and is legacy; if this file is still used elsewhere, remove only the lupo_groups block. If the whole file is obsolete, document and do not run it. |
| lupo-database/migrations/seed_lupopedia.sql | No change (already no group_id; uses department_id for lupo-channels/federation_nodes). |

### 3.3 Crafty Syntax import SQL files to update

| File | Change |
|------|--------|
| lupo-database/migrations/import_from_old_crafty_syntax.sql | lupo_collections INSERT: column list and VALUES use department_id (NULL or 1), not group_id. lupo_collection_tabs INSERTs: same. |
| lupo-database/migrations/craftysyntax_to_lupopedia_mysql.sql | Same as above for collections and collection_tabs. |

### 3.4 SQL generators to update

| File | Change |
|------|--------|
| lupo-database/install/generate_content_seed.php | Output `department_id` instead of `group_id`; use NULL or 1. Update comment "group_id = NULL" to "department_id = NULL". |
| lupo-database/install/generate_hierarchical_seed_3.0.12.php | In generated INSERT columns, use `department_id` instead of `group_id`; values NULL or 1. |

### 3.5 SQL alignment / summary artifacts to update

| File | Change |
|------|--------|
| lupo-database/migrations/dev_20260204_fix_schema_alignment_summary.txt | Remove or regenerate so it does not list lupo_groups, lupo_actor_group_membership, or group_id for the seven tables; include department_id for those tables. |
| lupo-database/migrations/reserved_word_audit_report.txt | Reference only; optionally add a note that lupo_actor_group_membership is dropped so the reserved-word recommendation for it is obsolete. |

---

## 4. PHP Files Requiring Updates (Corrected List)

### 4.1 PHP files that **must** be updated

| File | Update type | Notes |
|------|-------------|--------|
| lupo-database/install/generate_content_seed.php | Schema generator | Replace group_id with department_id in generated SQL (column name and value). |
| lupo-database/install/generate_hierarchical_seed_3.0.12.php | Schema generator | Replace group_id with department_id in generated INSERT columns and values. |

### 4.2 PHP files that must be **verified** (no group references)

| File | Verification |
|------|----------------|
| install_wizard_classes.php | Confirm no INSERT/SELECT references group_id or lupo_groups. Already uses department_id for channels. |
| app/auth/AuthRoleResolver.php | No group_id in code. Optional: add department-based permission path. |
| app/Services/SavedCollectionsService.php | No group_id in code. Optional: add department_id permission check. |
| lupo-includes/modules/crafty_syntax/livehelp.php, visitor-image.php, livehelp-js.php, choosedepartment.php, visitor-session-helper.php | Use department_id only; grep for "group" in permission/scope logic. |
| image.php, livehelp_js.php | Same. |
| lupo-includes/modules/channels/* (operator-pending-visitors-api, channels-controller, operator-accept-visitor-api, views) | Use department_id only. |
| app/Services/CraftySyntax/* (all listed in plan) | No group logic; verify no stray "group" references. |
| routes/auth_routes.php | Document only: permissions are user + department + channel_roles. |
| lupo-includes/models/GroundedAgentModel.php | If "permissions table" or "group" appears in comments/strings, update to department/permission model. |
| app/Http/Controllers/CraftyImportController.php, app/Services/CraftyConfigTransformer.php | Verify no group references. |

### 4.3 Files missing from the implementation plan (added here)

| File | Update type |
|------|-------------|
| lupo-database/migrations/craftysyntax_to_lupopedia_mysql.sql | Same as import_from_old_crafty_syntax.sql (department_id in collections/tabs INSERTs). |
| lupo-database/migrations/dev_20260204_fix_schema_alignment_summary.txt | Regenerate or edit so summary matches updated alignment SQL. |

### 4.4 Runtime logic that implicitly assumes groups exist

- **None identified.** No PHP code reads lupo_groups or lupo_actor_group_membership. No code filters by group_id. Assumption “groups exist” is schema-only; dropping tables and columns does not break runtime.

### 4.5 UI components that reference groups

- **None identified.** No UI found that lists or edits groups.

### 4.6 Seed/install generators that reference groups

- **Already listed:** generate_content_seed.php, generate_hierarchical_seed_3.0.12.php. No other PHP generators reference group_id (grep confirmed).

---

## 5. Doctrine Documents Requiring Updates (Corrected List)

### 5.1 Doctrine documents that must be updated

| Document | Sections / actions |
|----------|---------------------|
| lupo-docs/REQUIRED_TABLES_4.1.0.md | Remove lupo_actor_group_membership and lupo_groups from required tables list. Add note: "Organizational scope and permission-bearing entity is department only; group tables are removed." |
| lupo-docs/channels/schema/DATABASE_SCHEMA.md | Rewrite or remove: actor_group_membership (e.g. lines 204, 313–319), groups / group_modules (e.g. 593, 991–1016). Replace with actor_departments and department-scoped permissions. |
| lupo-docs/doctrine/DEVELOPMENT_WORKFLOW_DOCTRINE.md | Add short note that the organizational unit is department only (no groups). |
| lupo-docs/doctrine/SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md | If it lists tables, remove lupo_groups and lupo_actor_group_membership; note department_id on lupo_permissions, lupo_collections, lupo_collection_tabs, lupo_contents, lupo_analytics_*. |

### 5.2 Doctrine documents to verify (no group/permission content or add note)

| Document | Action |
|----------|--------|
| lupo-docs/doctrine/CLASS_CONVERSION_DOCTRINE.md | No change ("group" = group of functions). |
| lupo-docs/doctrine/COMPATIBILITY_MATRIX.md | If schema matrix exists later, list department_id; omit group_id. |
| lupo-docs/doctrine/MINIMAL_HOSTING_REQUIREMENTS.md | No change. |
| lupo-docs/doctrine/PHP_COMPATIBILITY_AND_MINIMAL_HOSTING_DOCTRINE.md | No change. |
| lupo-docs/doctrine/migrations/livehelp_operator_departments_migration.md, livehelp_departments_migration.md | Confirm they describe only lupo_departments and lupo_actor_departments; no groups. |
| lupo-docs/channels/dev-teams/governance/GOV-TOON-GENERATION-001.md | No change; TOONs regenerated by script after schema apply. |

### 5.3 TOONs to be regenerated

- **Process:** After schema is applied (via migration or fresh install), run `lupo-scripts/generate_toon_files.py` against that database. Do not manually create or edit TOON files. Result: lupo_groups and lupo_actor_group_membership no longer have TOONs; lupo_permissions, lupo_collections, lupo_collection_tabs, lupo_contents, and the four analytics tables will have department_id in their TOONs.
- **Location of TOONs:** Per GOV-TOON-GENERATION-001, generator writes to the configured output (e.g. lupo-docs/toons or schema); confirm path in script.

### 5.4 Schema diagrams or table lists to update

- **lupo-docs/channels/schema/DATABASE_SCHEMA.md** — narrative and table descriptions (see 5.1).
- **lupo-docs/REQUIRED_TABLES_4.1.0.md** — table list (see 5.1).
- Any other doc that enumerates core tables: remove lupo_groups and lupo_actor_group_membership; add note about department_id where relevant.

---

## 6. Expanded Risk Assessment and Mitigations

### 6.1 Additional risks (added)

| Risk | Likelihood | Impact | Mitigation |
|------|------------|--------|------------|
| **craftysyntax_to_lupopedia_mysql.sql not updated** | Medium | Medium | Explicitly list in checklist (§3.3); update in same pass as import_from_old_crafty_syntax.sql. |
| **dev_20260206 run after migration** | Low | High | If someone runs dev_20260206 on a DB that already had lupo_actor_group_membership dropped, the ALTER would fail. Remove the lupo_actor_group_membership block from dev_20260206 so the script is safe for both pre- and post-unification DBs (post: table missing, so block is no-op if removed). |
| **lupopedia_seed_mysql.sql still run** | Low | Medium | File has wrong lupo_groups columns; remove lupo_groups INSERT. Document whether file is still in use; if obsolete, add comment at top. |
| **Unique constraint (target_type, target_id, department_id)** | Low | Low | Multiple permission rows with same (target_type, target_id, department_id) and different permission level: MySQL unique allows one row per (target_type, target_id, department_id). If product needs multiple levels per department, use a different design (e.g. permission_level column and unique on (target_type, target_id, department_id, permission_level)) — document in execution phase. |
| **Analytics code filters by group_id** | Low | Medium | No PHP found that filters analytics by group_id. If any reporting or cron job does, update to department_id and default 1 before dropping column. |

### 6.2 Underestimated risks (elevated)

| Risk | Revised likelihood/impact | Mitigation |
|------|----------------------------|------------|
| **Seed script breakage** | Medium / Medium | Run full install + all seeds in a clean DB in a test environment before merging. |
| **Alignment script run order** | Low / High | Document: after unification, dev_20260204 must not MODIFY group_id (column gone). So alignment script is updated to only MODIFY department_id for the seven tables; safe for post-unification and for new installs. |

### 6.3 Migration pitfalls (expanded)

| Pitfall | Mitigation |
|---------|------------|
| **Dropping table before dropping column references** | Order: drop group_id and indexes from all seven tables first, then DROP TABLE lupo_actor_group_membership, then DROP TABLE lupo_groups. |
| **MySQL ADD COLUMN IF NOT EXISTS** | MySQL 5.7 does not support IF NOT EXISTS for ADD COLUMN. Use a conditional in a procedure, or document "run once" and check for column existence in a wrapper script. |
| **Backfill of lupo_permissions** | If any rows have group_id set, decide: delete those rows or map group_id → department_id (e.g. 1:1). Document in migration file. |
| **Index name conflicts** | New indexes use names like lupo_permissions_idx_department; ensure no clash with existing index names when adding. |

### 6.4 Installer/wizard pitfalls (expanded)

| Pitfall | Mitigation |
|---------|------------|
| **Wizard runs reserved-word or alignment script** | If wizard or install flow runs dev_20260206 or dev_20260204, ensure they are updated before deployment so they never reference group_id or group tables. |
| **Partial seed run** | If only some seed files are updated, mixed schema (some tables with group_id, some with department_id) can break. Update all seed and generator files in one commit/pass. |

### 6.5 Permission-system pitfalls (expanded)

| Pitfall | Mitigation |
|---------|------------|
| **user_id and department_id both NULL** | Application logic or docs should state whether a permission row with both NULL is allowed (e.g. "public" access) or invalid. |
| **Resolution order** | Document: check user_id first, then department_id (actor→actor_departments→permissions), then channel_roles; combine with OR. |

### 6.6 Schema-alignment pitfalls (expanded)

| Pitfall | Mitigation |
|---------|------------|
| **dev_20260204 expects group_id to exist** | After editing, alignment script only adds MODIFY department_id for the seven tables and removes all MODIFY for lupo_groups and lupo_actor_group_membership. Safe for new install (has department_id) and for post-unification DB (has department_id, no group_id). |
| **Summary file out of sync** | Regenerate or manually edit dev_20260204_fix_schema_alignment_summary.txt after changing dev_20260204_fix_schema_alignment.sql. |

### 6.7 Mitigation plan summary

- **Pre-flight:** Backup; verify no runtime use of group_id/lupo_groups.
- **Idempotency:** Migration file documented as run-once; use explicit ADD/BACKFILL/DROP order.
- **Coherence:** All installer, seed, import, and alignment files updated in one logical change set.
- **Testing:** Smoke test new install, migration on copy of DB, and Crafty import.
- **TOONs:** Regenerate only after schema is applied; do not edit TOONs by hand.

---

## 7. Final Pre-Execution Checklist (Authoritative)

Use this section as the single checklist during execution. Complete every item before considering the unification complete.

### 7.1 Migration files

- [ ] **Create** `lupo-database/migrations/migration_unify_groups_into_departments.sql` with: ADD department_id (seven tables), ADD new indexes, BACKFILL, DROP group_id and group indexes, DROP TABLE lupo_actor_group_membership, DROP TABLE lupo_groups.
- [ ] **Update** `lupo-database/migrations/install_new_lupopedia.sql`: remove group tables; add department_id and remove group_id from the seven tables.
- [ ] **Update** `lupo-database/migrations/dev_20260204_fix_schema_alignment.sql`: remove group/group_id MODIFYs; add department_id MODIFYs for the seven tables.
- [ ] **Update** `lupo-database/migrations/dev_20260204_fix_schema_alignment_summary.txt`: align with updated alignment SQL.
- [ ] **Update** `lupo-database/migrations/dev_20260206_reserved_word_column_renames.sql`: remove lupo_actor_group_membership block.
- [ ] **Update** `lupo-database/migrations/import_from_old_crafty_syntax.sql`: department_id in collections/tabs INSERTs; no group_id.
- [ ] **Update** `lupo-database/migrations/craftysyntax_to_lupopedia_mysql.sql`: same as above.

### 7.2 Installer SQL

- [ ] install_new_lupopedia.sql (see 7.1).

### 7.3 Seed SQL

- [ ] lupo-database/install/seed_collection_0_content.sql — group_id → department_id.
- [ ] lupo-database/install/seed_collection_0_system_tabs.sql — group_id → department_id.
- [ ] lupo-database/install/seed_collection_0_hierarchical_tabs_3.0.12.sql — group_id → department_id.
- [ ] lupo-database/install/truth_test_data_captain_wolfie.sql — group_id → department_id.
- [ ] lupo-database/install/lupopedia_seed_mysql.sql — remove lupo_groups INSERT block.

### 7.4 Crafty Syntax import

- [ ] import_from_old_crafty_syntax.sql (see 7.1).
- [ ] craftysyntax_to_lupopedia_mysql.sql (see 7.1).

### 7.5 PHP files

- [ ] lupo-database/install/generate_content_seed.php — output department_id instead of group_id.
- [ ] lupo-database/install/generate_hierarchical_seed_3.0.12.php — output department_id instead of group_id.
- [ ] install_wizard_classes.php — verify no group_id/lupo_groups references.
- [ ] (Optional) app/auth/AuthRoleResolver.php — add department-based permission path.
- [ ] (Optional) app/Services/SavedCollectionsService.php — add department_id permission check.
- [ ] Verify all other PHP files in §4.2 for no group references.

### 7.6 Doctrine documents

- [ ] lupo-docs/REQUIRED_TABLES_4.1.0.md — remove group tables; add department note.
- [ ] lupo-docs/channels/schema/DATABASE_SCHEMA.md — rewrite groups/actor_group_membership to departments/actor_departments.
- [ ] lupo-docs/doctrine/DEVELOPMENT_WORKFLOW_DOCTRINE.md — add department-only note.
- [ ] lupo-docs/doctrine/SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md — update table list and department_id note.
- [ ] lupo-docs/doctrine/migrations/livehelp_operator_departments_migration.md, livehelp_departments_migration.md — confirm no groups.

### 7.7 TOON regeneration

- [ ] Apply schema to a DB (run migration on existing, or install_new_lupopedia + seed for new).
- [ ] Run `lupo-scripts/generate_toon_files.py` against that DB.
- [ ] Commit or archive generated TOONs per GOV-TOON-GENERATION-001.

### 7.8 Risks and mitigations

- [ ] Pre-flight: DB backup; confirm no runtime group_id/lupo_groups.
- [ ] Migration order: add department_id → indexes → backfill → drop group_id/indexes → drop tables.
- [ ] All seed/install/import/alignment files updated in one pass.
- [ ] dev_20260206 no longer alters lupo_actor_group_membership.
- [ ] lupopedia_seed_mysql.sql: lupo_groups INSERT removed; document if file is legacy.

### 7.9 Final execution order

1. Pre-flight (backup; verify no runtime group refs).
2. Create and run migration_unify_groups_into_departments.sql (existing DBs).
3. Update install_new_lupopedia.sql.
4. Update all seed SQL and generator PHP.
5. Update import_from_old_crafty_syntax.sql and craftysyntax_to_lupopedia_mysql.sql.
6. Update dev_20260204_fix_schema_alignment.sql and summary; update dev_20260206_reserved_word_column_renames.sql.
7. Optional: AuthRoleResolver, SavedCollectionsService (department permission path).
8. Update doctrine docs.
9. Regenerate TOONs (after schema applied).
10. Smoke test: new install; migration on copy; Crafty import.

---

*End of pre-execution checklist. No files were modified; preparation only.*
