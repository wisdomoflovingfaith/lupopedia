# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\audits\DEPARTMENTS_GROUPS_ROLES_IMPLEMENTATION_PLAN.md"
  file_hash: "1b5526bb1d21e6d2ad5af33ae206b469863f20fec07af962548802e42345474b"
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
  file_path_from_root: "docs\audits\DEPARTMENTS_GROUPS_ROLES_IMPLEMENTATION_PLAN.md"
  file_hash: "7055ad5c2a44ec5e6e4087458a89d10ad40c0ada13bb1b085c80bbca262f06f5"
  file_path_from_root: "docs\audits\DEPARTMENTS_GROUPS_ROLES_IMPLEMENTATION_PLAN.md"
  file_hash: "576ea8b31e87ceb4835d58258db8f5d687793f921e34a24a5f9f95c6952d3581"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Departments / Groups / Roles Unification — Implementation Planning Phase"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "audits", "departments_groups_roles_implementation_planmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Departments / Groups / Roles Unification — Implementation Planning Phase

**Date:** 2026-02-12  
**Prerequisite:** docs/audits/DEPARTMENTS_GROUPS_ROLES_STRUCTURAL_FEASIBILITY_REPORT.md  
**Constraint:** Planning only; no code or schema changes in this document.

---

## 1. Schema Change Plan

### 1.1 Table listing: current columns, required changes, migration steps

| Table name | Current columns (relevant) | Required new columns | Columns to remove | Indexes to add | Indexes to remove | Migration steps |
|------------|----------------------------|----------------------|-------------------|----------------|--------------------|------------------|
| **lupo_permissions** | permission_id, target_type, target_id, user_id, **group_id**, permission, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis | department_id bigint DEFAULT NULL | group_id | UNIQUE (target_type, target_id, department_id); INDEX (department_id) | UNIQUE lupo_permissions_uniq_target_group; INDEX lupo_permissions_idx_group | 1) ADD department_id. 2) Add new unique + index. 3) Migrate: DELETE rows where group_id IS NOT NULL (or 1:1 map group_id→department_id if product wants to preserve). 4) DROP INDEX uniq_target_group, idx_group; DROP COLUMN group_id. |
| **lupo_collections** | …, actor_id, **group_id**, name, slug, … | department_id bigint DEFAULT NULL | group_id | INDEX (department_id) | INDEX lupo_collections_idx_group | 1) ADD department_id. 2) Backfill: UPDATE SET department_id = 1 WHERE department_id IS NULL (optional). 3) DROP INDEX idx_group; DROP COLUMN group_id. |
| **lupo_collection_tabs** | …, collection_id, federations_node_id, **group_id**, user_id, … | department_id bigint DEFAULT NULL | group_id | INDEX (department_id) | (none in install for group_id on tabs) | 1) ADD department_id. 2) Backfill optional. 3) DROP COLUMN group_id. |
| **lupo_contents** | …, federation_node_id, **group_id**, actor_id, title, … | department_id bigint DEFAULT NULL | group_id | INDEX (department_id) | INDEX lupo_contents_idx_group | 1) ADD department_id. 2) Backfill optional. 3) DROP INDEX idx_group; DROP COLUMN group_id. |
| **lupo_analytics_referers_periods** | …, **group_id** bigint NOT NULL DEFAULT 0, … | department_id bigint NOT NULL DEFAULT 1 | group_id | INDEX (department_id, period_date) | INDEX lupo_analytics_referers_periods_idx_group | 1) ADD department_id NOT NULL DEFAULT 1. 2) UPDATE SET department_id = 1 (or from context). 3) DROP INDEX idx_group; DROP COLUMN group_id. |
| **lupo_analytics_visits_daily** | …, **group_id** bigint NOT NULL DEFAULT 0, … | department_id bigint NOT NULL DEFAULT 1 | group_id | INDEX (department_id, date_ymd) | INDEX lupo_analytics_visits_daily_idx_group | Same pattern as referers_periods. |
| **lupo_analytics_visits_monthly** | …, **group_id** bigint NOT NULL DEFAULT 0, … | department_id bigint NOT NULL DEFAULT 1 | group_id | INDEX (department_id, date_ym) | INDEX lupo_analytics_visits_monthly_idx_group | Same pattern. |
| **lupo_analytics_visits_periods** | …, **group_id** bigint NOT NULL DEFAULT 0, … | department_id bigint NOT NULL DEFAULT 1 | group_id | INDEX (department_id, period_date) | INDEX lupo_analytics_visits_periods_idx_group | Same pattern. |
| **lupo_actor_group_membership** | (entire table) | — | — (table dropped) | — | All | DROP TABLE lupo_actor_group_membership (after all FKs/refs removed; there are no FKs per doctrine). |
| **lupo_groups** | (entire table) | — | — (table dropped) | — | All | DROP TABLE lupo_groups. |

### 1.2 Default values and nullability

- **lupo_permissions.department_id:** DEFAULT NULL; nullable. One of (user_id, department_id) can be set per row (mutually exclusive in application logic).
- **lupo_collections, lupo_collection_tabs, lupo_contents.department_id:** DEFAULT NULL; nullable. Backfill to 1 or leave NULL for “no department scope”.
- **lupo_analytics_* .department_id:** NOT NULL DEFAULT 1 so existing analytics rows have a valid scope without backfill.

### 1.3 Foreign keys and doctrine

- **No foreign keys** are to be added (Database Logic Prohibition Doctrine). All relationships are enforced in application code only.

### 1.4 Installer SQL and wizard upgrade SQL

- **install_new_lupopedia.sql:**  
  - Remove `CREATE TABLE lupo_groups` and all indexes on lupo_groups.  
  - Remove `CREATE TABLE lupo_actor_group_membership` and all indexes.  
  - For lupo_permissions: define department_id bigint DEFAULT NULL; do not define group_id; add UNIQUE (target_type, target_id, department_id) and INDEX (department_id).  
  - For lupo_collections, lupo_collection_tabs, lupo_contents: define department_id bigint DEFAULT NULL; do not define group_id; add INDEX (department_id) where useful.  
  - For lupo_analytics_referers_periods, lupo_analytics_visits_daily, lupo_analytics_visits_monthly, lupo_analytics_visits_periods: define department_id bigint NOT NULL DEFAULT 1; do not define group_id; add INDEX (department_id, period_date) or (department_id, date_ymd/date_ym) as appropriate.
- **dev_20260204_fix_schema_alignment.sql** (and any later alignment migrations that touch these tables):  
  - Remove all MODIFY/ALTER for lupo_groups and lupo_actor_group_membership.  
  - Replace MODIFY group_id with MODIFY department_id for the seven modified tables; add new indexes; remove group_id from MODIFY list.
- **Wizard upgrade SQL:** install_wizard_classes.php does not create group tables or reference group_id; it already uses department_id for channels. No wizard SQL changes required beyond ensuring the wizard never references group_id (already the case).

---

## 2. Permission System Rewrite Plan

### 2.1 Mapping: how permissions work when departments are permission-bearing

- **Current (unchanged in code):** Resolve by user_id (lupo_permissions) and by channel_roles (lupo_channel_roles). group_id exists in schema but is never read in code.
- **After unification:**  
  - **User-level:** Unchanged. lupo_permissions (target_type, target_id, user_id) → allow if current user’s auth_user_id (or actor→auth_user) matches.  
  - **Department-level (new):** Resolve actor → list of department_id via lupo_actor_departments. For each (target_type, target_id), allow if any lupo_permissions row exists with (target_type, target_id, department_id) and permission sufficient.  
  - **Channel roles:** Unchanged. lupo_channel_roles (channel_id = 1 for “global”) for admin/operator.  
  - **Combined:** Allow if user_id match OR any department_id match OR channel_roles grant.

### 2.2 Files to update for permission system

| File | Change |
|------|--------|
| app/auth/AuthRoleResolver.php | Optionally add department-based permission path for admin fallback (e.g. owner on module via department). Current: channel_roles + lupo_permissions by user_id only. |
| app/Services/SavedCollectionsService.php | If product requires department-scoped collection access: extend collection permission check to include lupo_permissions by (target_type='collection', target_id, department_id) for current actor’s departments. Today it uses user_id only. |
| routes/auth_routes.php | No schema change; '/auth/permissions' is a route placeholder. If a future handler returns permission list, ensure it documents/codes department-based permissions. |
| (New or existing permission helper) | Centralize “actor has permission on (target_type, target_id)”: resolve actor→departments, then check lupo_permissions by user_id and by department_id. |

### 2.3 Doctrine documents to update (permission/groups)

- **docs/REQUIRED_TABLES_4.1.0.md:** Remove lupo_actor_group_membership and lupo_groups from required tables; add note that permissions are user- and department-scoped only.
- **docs/channels/schema/DATABASE_SCHEMA.md:** Rewrite or remove sections that describe actor_group_membership, groups, group_id, group_modules; replace with department-based permission and actor_departments.

### 2.4 TOONs or semantic OS components that reference groups

- **TOONs:** Generated from live DB by scripts/generate_toon_files.py. After schema migration, regenerate TOONs so that lupo_groups and lupo_actor_group_membership no longer exist and affected tables show department_id instead of group_id. No manual TOON edits (Cursor must not create/edit TOONs).
- **docs/channels/schema/DATABASE_SCHEMA.md:** Contains narrative references to groups, actor_group_membership, group_modules; must be updated to reflect departments-only ontology.

---

## 3. Installer + Wizard Update Plan

### 3.1 Installer files affected

| File | Change |
|------|--------|
| database/migrations/install_new_lupopedia.sql | See §1.4: remove group tables; add department_id and remove group_id from the seven tables. |
| install.php | No group_id or group table creation; confirm it only invokes install_new_lupopedia.sql and seed; no change if so. |
| database/migrations/seed_lupopedia.sql | Already uses department_id for channels/federation_nodes; no group_id. No change. |

### 3.2 Wizard steps affected

| Step / flow | Change |
|-------------|--------|
| install_wizard_classes.php | Already uses department_id (e.g. channels, department_id = 1). Ensure no INSERT or SELECT references group_id or lupo_groups. Add comment that group tables are removed. |
| Credentials / install / seed / reserved channels | No creation of lupo_groups or lupo_actor_group_membership. Seed and reserved channel SQL must use department_id only. |
| Crafty import (import_from_old_crafty_syntax.sql) | Replace group_id in INSERTs with department_id (or NULL). Collections/tabs/contents: use department_id; remove group_id from column list and VALUES. |

### 3.3 SQL files affected

| File | Change |
|------|--------|
| database/migrations/install_new_lupopedia.sql | Full schema edits per §1.1 and §1.4. |
| database/migrations/dev_20260204_fix_schema_alignment.sql | Remove group table MODIFYs; replace group_id with department_id for the seven tables; adjust indexes. |
| database/migrations/dev_20260206_reserved_word_column_renames.sql | Remove ALTER for lupo_actor_group_membership (table dropped). |
| database/migrations/import_from_old_crafty_syntax.sql | lupo_collections INSERT: replace group_id with department_id (NULL or 1). lupo_collection_tabs INSERTs: same. No inserts into lupo_groups or lupo_actor_group_membership. |
| database/install/generate_content_seed.php | Output department_id instead of group_id; use NULL or 1. |
| database/install/generate_hierarchical_seed_3.0.12.php | Output department_id in INSERT columns; remove group_id. |
| database/install/seed_collection_0_content.sql | Replace group_id column and values with department_id (NULL or 1). |
| database/install/seed_collection_0_system_tabs.sql | Replace group_id with department_id. |
| database/install/seed_collection_0_hierarchical_tabs_3.0.12.sql | Replace group_id with department_id. |
| database/install/truth_test_data_captain_wolfie.sql | Replace group_id with department_id if present. |
| database/install/lupopedia_seed_mysql.sql | Remove or rewrite INSERT into lupo_groups (current INSERT uses wrong columns: domain_id, group_name, created_at); if file is still used, remove lupo_groups insert entirely. |
| database/migrations_legacy/* (reference only) | Do not run legacy migrations; document that group_id and group tables are retired so future readers don’t reintroduce them. |

### 3.4 Crafty Syntax import and normalization

- **Crafty departments:** Already mapped to lupo_departments and lupo_actor_departments in import_from_old_crafty_syntax.sql. No change to that mapping.
- **Normalization:** Identity normalization (email uniqueness, etc.) does not touch groups. No change.
- **Import INSERTs:** Use department_id (or NULL) in lupo_collections, lupo_collection_tabs, lupo_contents; remove group_id from column lists and values.

---

## 4. Application Code Update Plan

### 4.1 File-by-file list (group references, permission resolution, identity, UI)

| File | Update type | Notes |
|------|-------------|--------|
| app/auth/AuthRoleResolver.php | Permission resolution | Optionally add department-based path for admin/owner; keep channel_roles + user_id. |
| app/auth/AuthManager.php | No group references | No change unless adding a permission API that exposes department-based checks. |
| app/Services/SavedCollectionsService.php | Permission resolution | Optionally add department_id permission check for collections; today user_id only. |
| lupo-includes/functions/auth-helpers.php | No group refs | No change. |
| lupo-includes/modules/crafty_syntax/* (livehelp.php, visitor-image, livehelp-js, choosedepartment, visitor-session-helper) | Department only | Already use department_id; no group_id. Verify no string "group" in permission/scope logic. |
| image.php, livehelp_js.php | Department only | Same; use department_id. No change if no group refs. |
| install_wizard_classes.php | No group creation | Confirm no group_id in INSERTs; already uses department_id. |
| lupo-includes/modules/channels/* (operator-pending-visitors-api, channels-controller, operator-accept-visitor-api, views) | Department only | Already department_id. No change. |
| app/Services/CraftySyntax/* (LegacySessionIdentity, LegacyFunctions, WorldGraphHelper, LegacyChooseDepartment, LegacyUserChatRefresh, LegacyDepartments, LegacyIsFlushDetection, LegacyUserChatFlush, LegacyAdmin, LegacyDepartmentFunction) | Department only | Use departments; no group logic. Verify no stray group references. |
| routes/auth_routes.php | Documentation | '/auth/permissions' placeholder; document that permissions are user + department + channel_roles. |
| lupo-includes/header.php, footer.php | No group refs | No change. |
| lupo-includes/module-loader.php | Department / channel_roles | Already uses channel roles and department_id where needed. No group_id. |
| lupo-includes/models/GroundedAgentModel.php | Permission wording | If it references “permissions table” or “group”, update to department/permission model. |
| api/list_user_collections.php | Permission | Uses SavedCollectionsService; if that gains department permission, behavior may extend automatically. |
| database/install/generate_content_seed.php | Schema | Replace group_id with department_id in generated SQL. |
| database/install/generate_hierarchical_seed_3.0.12.php | Schema | Replace group_id with department_id in generated SQL. |
| app/Http/Controllers/CraftyImportController.php, app/Services/CraftyConfigTransformer.php | No group logic | Verify no group references; config/permissions wording only. |
| ai-actors/index.php | Department | Already department_id. No change. |

No application code currently reads lupo_groups or lupo_actor_group_membership; no files need to “remove group references” from runtime logic. Changes are: (1) schema/seed scripts, (2) optional permission resolution extension to department_id, (3) documentation.

---

## 5. Doctrine Update Plan

### 5.1 Doctrine documents and sections to update

| Document | Sections / actions |
|----------|---------------------|
| docs/doctrine/DEVELOPMENT_WORKFLOW_DOCTRINE.md | If it mentions “groups” or “group tables”, replace with “departments” and “department-scoped permissions”. Grep found no matches; add a short note that the organizational unit is department only (no groups). |
| docs/doctrine/CLASS_CONVERSION_DOCTRINE.md | No change; “group” there means “group related functions”, not lupo_groups. |
| docs/doctrine/COMPATIBILITY_MATRIX.md | No group/permission content found. If a later version adds schema matrix, list department_id and omit group_id. |
| docs/doctrine/MINIMAL_HOSTING_REQUIREMENTS.md | No change. |
| docs/doctrine/PHP_COMPATIBILITY_AND_MINIMAL_HOSTING_DOCTRINE.md | No change. |
| docs/REQUIRED_TABLES_4.1.0.md | Remove lupo_actor_group_membership and lupo_groups from required tables. Add note: “Organizational scope and permission-bearing entity is department only; group tables are removed.” |
| docs/channels/schema/DATABASE_SCHEMA.md | Rewrite “actor_group_membership” and “groups” / “group_modules” sections to describe actor_departments and department-scoped permissions only. Remove or replace lines 204, 313–319, 593, 991–1016. |
| docs/doctrine/SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md | No group/permission matches. If it lists tables, remove lupo_groups and lupo_actor_group_membership; note department_id on permissions/collections/contents/analytics. |
| docs/doctrine/migrations/livehelp_operator_departments_migration.md, livehelp_departments_migration.md | Confirm they describe migration to lupo_departments and lupo_actor_departments only; no reference to groups. |
| docs/audits/DEPARTMENTS_GROUPS_ROLES_STRUCTURAL_FEASIBILITY_REPORT.md | Keep as-is; it is the prerequisite. |
| .cursor/rules (TOON source of truth, PK naming, etc.) | No change; department_id already follows PK/reference naming. |

### 5.2 TOON doctrine

- TOONs are generated by scripts/generate_toon_files.py from the live database. After schema migration, regenerate TOONs. Do not manually create or edit TOON files (per project rules).

---

## 6. Risk Assessment

### 6.1 Risks of unifying groups into departments

| Risk | Likelihood | Impact | Mitigation |
|------|------------|--------|------------|
| Existing DB with group_id data | Low | Medium | No production Lupopedia data; Crafty import uses departments. For any existing installs with group_id rows: migration script sets department_id (e.g. 1) and drops group_id. |
| Permission resolution regression | Low | High | Current code does not use group_id; adding department_id is additive. Test: admin fallback, collection list, channel roles. |
| Seed/install script breakage | Medium | Medium | All seed and install SQL must be updated in one pass; run full install + seed in clean DB and verify. |
| Legacy migration references | Low | Low | Old migrations in migrations_legacy/ reference group_id; do not run them; document retirement. |

### 6.2 Potential regressions

- **Collections/content visibility:** If any future or hidden code filtered by group_id, it would break. Grep shows no such code; regression risk is low.
- **Analytics reporting:** If reports filter by group_id, replace with department_id and default scope (e.g. department_id = 1).

### 6.3 Crafty Syntax edge cases

- **Operator departments:** Already migrated to lupo_actor_departments. No group path. Safe.
- **Department-scoped chat/leave-message/auto-invite:** Already use department_id. No change.
- **Visitor session metadata:** Uses department_id. No change.

### 6.4 Installer / wizard edge cases

- **New install:** Install SQL must create tables without group_id and with department_id; seed must not reference lupo_groups. Single coherent edit of install_new_lupopedia.sql and seed files.
- **Upgrade from Crafty:** Import SQL already uses departments; only ensure INSERTs into collections/tabs/contents use department_id (or NULL) and not group_id.

### 6.5 Permission system edge cases

- **Unique constraint on (target_type, target_id, department_id):** Multiple rows with same (target_type, target_id, department_id) and different permission levels: decide if unique is per (target_type, target_id, department_id) or allow multiple permission levels. Current group_id design had one row per (target_type, target_id, group_id); same for department_id is consistent.
- **user_id vs department_id mutual exclusivity:** Application logic must ensure a permission row has either user_id or department_id set (or document that both null is “public” if desired).

### 6.6 Schema migration pitfalls

- **Order of operations:** Add department_id and new indexes before dropping group_id; then drop group_id and group indexes; then drop lupo_actor_group_membership; then drop lupo_groups. Avoid dropping columns still referenced by application during rollout; deploy app that ignores group_id and reads department_id first.
- **Idempotency:** Migration SQL should be idempotent where possible (e.g. ADD COLUMN IF NOT EXISTS where supported, or document “run once”).

---

## 7. Final Output Summary

### 7.1 Full implementation plan

- **Phase 1 — Schema (migration SQL):** One-time migration file: add department_id to the seven tables; add new indexes; backfill department_id where needed; drop group_id and group-related indexes; drop lupo_actor_group_membership; drop lupo_groups.
- **Phase 2 — Install and seed:** Update install_new_lupopedia.sql (no group tables; department_id on the seven tables). Update all seed and generated SQL (generate_content_seed.php, generate_hierarchical_seed_3.0.12.php, seed_collection_0_*.sql, truth_test_data_captain_wolfie.sql, lupopedia_seed_mysql.sql). Update import_from_old_crafty_syntax.sql to use department_id in INSERTs.
- **Phase 3 — Alignment migrations:** Update dev_20260204_fix_schema_alignment.sql (and dev_20260206 if needed) to remove group tables and use department_id.
- **Phase 4 — Permission resolution (optional):** Extend AuthRoleResolver and/or SavedCollectionsService to consider lupo_permissions by department_id via lupo_actor_departments.
- **Phase 5 — Documentation and TOONs:** Update REQUIRED_TABLES_4.1.0.md, DATABASE_SCHEMA.md, and any doctrine that mentions groups/permissions. Regenerate TOONs after schema is applied.

### 7.2 Migration plan (execution order)

1. Create a new migration SQL file (e.g. migration_unify_groups_into_departments.sql) that: adds department_id to the seven tables; adds new indexes; backfills; drops group_id and indexes; drops lupo_actor_group_membership; drops lupo_groups.
2. Update install_new_lupopedia.sql so fresh installs never create group tables and use department_id.
3. Update all seed and import SQL files to use department_id only.
4. Update alignment migrations so they don’t reference group columns or group tables.
5. Deploy application: optional permission resolution changes; no runtime group references to remove.
6. Regenerate TOONs; update docs.

### 7.3 Schema change plan

- Summarized in §1: seven tables gain department_id and lose group_id; two tables dropped. No FKs. Indexes as in table §1.1.

### 7.4 Permission rewrite plan

- Summarized in §2: optional department-based path in AuthRoleResolver and SavedCollectionsService; documentation and DATABASE_SCHEMA.md updated to departments-only permission model.

### 7.5 Doctrine update plan

- Summarized in §5: REQUIRED_TABLES_4.1.0.md, DATABASE_SCHEMA.md, and any workflow/compatibility doc that mentions groups; TOONs regenerated.

### 7.6 Risk assessment

- Summarized in §6: low risk for code (no group_id in use); medium for seed/install coherence; mitigations: single coherent schema/seed pass, idempotent migration, test full install and upgrade path.

### 7.7 Recommended execution order

1. **Schema migration file** (add department_id, drop group_id, drop tables) — for existing DBs.
2. **install_new_lupopedia.sql** — remove group tables, add department_id, remove group_id.
3. **Seed and generated SQL** — generate_content_seed.php, generate_hierarchical_seed_3.0.12.php, seed_collection_0_*.sql, truth_test_data_captain_wolfie.sql, lupopedia_seed_mysql.sql.
4. **import_from_old_crafty_syntax.sql** — department_id in INSERTs; no group_id.
5. **dev_20260204_fix_schema_alignment.sql** (and related) — remove group table/column references.
6. **Permission resolution (optional)** — AuthRoleResolver, SavedCollectionsService.
7. **Documentation** — REQUIRED_TABLES_4.1.0.md, DATABASE_SCHEMA.md, doctrine.
8. **TOON regeneration** — after schema applied.
9. **Smoke test** — new install, run migration on copy of DB, run Crafty import path.

---

*End of implementation plan. No files were modified; this is planning only.*
