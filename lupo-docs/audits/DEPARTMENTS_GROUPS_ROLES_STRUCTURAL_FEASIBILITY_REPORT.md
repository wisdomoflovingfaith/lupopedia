# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/audits/DEPARTMENTS_GROUPS_ROLES_STRUCTURAL_FEASIBILITY_REPORT.md"
  file_hash: "9a1ac39526b4f6a4081cbf4b64642d9c8792050f20ef5a5558b0bed0e77fe536"
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
  file_path_from_root: "lupo-docs\audits\DEPARTMENTS_GROUPS_ROLES_STRUCTURAL_FEASIBILITY_REPORT.md"
  file_hash: "7516e6bf033196063354c161e1f42e4d99759661684bfcbe5dc70a99e0e03372"
  file_path_from_root: "lupo-docs\audits\DEPARTMENTS_GROUPS_ROLES_STRUCTURAL_FEASIBILITY_REPORT.md"
  file_hash: "89b19c9885099e51e66547947c00cce21e2610ba1ff29ee7af270d9c29ecad37"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Departments, Groups, and Roles — Structural Simplification Feasibility Report"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "audits", "departments_groups_roles_structural_feasibility_reportmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Departments, Groups, and Roles — Structural Simplification Feasibility Report

**Date:** 2026-02-12  
**Scope:** Schema (install_new_lupopedia.sql, migrations), permission system, actor membership, and codebase references.  
**Constraint:** Analysis only; no files modified.

---

## 1. Table-by-Table Analysis

### 1.1 Tables containing `group_id`

| Table | group_id present? | department_id present? | Required changes if groups removed |
|-------|-------------------|------------------------|-------------------------------------|
| **lupo_actor_group_membership** | Yes (PK link to lupo_groups) | No | Table dropped. Actor–org membership becomes actor_departments only. If any actor→group data exists, migrate to actor_departments (map group_id→department_id or drop rows). |
| **lupo_analytics_referers_periods** | Yes (default 0) | No | Add `department_id` (nullable or default 1); use for analytics scope. Migrate: set department_id from context or default 1; then drop group_id. |
| **lupo_analytics_visits_daily** | Yes (default 0) | No | Same as above. |
| **lupo_analytics_visits_monthly** | Yes (default 0) | No | Same as above. |
| **lupo_analytics_visits_periods** | Yes (default 0) | No | Same as above. |
| **lupo_collections** | Yes (nullable) | No | Add `department_id` bigint DEFAULT NULL. Optionally migrate: assign default department for existing rows; then drop group_id. |
| **lupo_collection_tabs** | Yes (nullable) | No | Same as above. |
| **lupo_contents** | Yes (nullable) | No | Same as above. |
| **lupo_gov_events** | No; has **utc_group_id** | No | **No change.** utc_group_id is a governance/time-group identifier, not a reference to lupo_groups. Leave as-is. |
| **lupo_groups** | Yes (PK) | N/A | **Drop table** after all group_id references removed and permission logic moved to departments. |
| **lupo_permissions** | Yes (nullable) | No | Add `department_id` bigint DEFAULT NULL. Permission resolution: resolve actor→departments, then check permissions by (target_type, target_id, department_id). Migrate existing group_id rows: map group_id→department_id (1:1 seed or drop). Then drop group_id. |

### 1.2 Tables containing `department_id` (no group_id)

These already use departments; no structural change for “collapse groups into departments” beyond ensuring they remain the single org/permission axis.

| Table | Purpose |
|-------|---------|
| lupo_actor_departments | Actor membership in departments (Crafty operator departments). |
| lupo_channels | Channel belongs to a department. |
| lupo_crafty_syntax_leave_message | Leave-message per department. |
| lupo_crafty_syntax_chat_questions | Chat questions per department. |
| lupo_crafty_syntax_chat_mod_departments | Module–department link for chat. |
| lupo_crafty_syntax_auto_invite | Auto-invite per department. |
| lupo_departments | Department entity. |
| lupo_department_metadata | Metadata per department. |
| lupo_federation_nodes | default_department_id. |
| lupo_help_tree | Help tree scoped by department. |
| lupo_modules_departments | Module enabled per department. |

### 1.3 Structural correctness of adding `department_id`

- **lupo_collections, lupo_collection_tabs, lupo_contents:** Adding department_id is structurally correct; content/collections can be scoped by department instead of (or in addition to) group.
- **lupo_analytics_*:** Analytics are often scoped by “scope entity”; department_id is a valid scope dimension (replace or supplement group_id).
- **lupo_permissions:** Permissions can be granted to a department (all members) instead of a group; adding department_id and using it in resolution is structurally correct.
- **lupo_actor_group_membership:** Install schema has **no actor_id** column (only actor_group_membership_id, group_id, domain_id, …). So the table does not currently link actors to groups in the install; it appears incomplete or legacy. Dropping it does not require migrating actor→group rows if none are produced by current code (see §3).

---

## 2. Permission System Analysis

### 2.1 Permission-related tables

| Table | Role |
|-------|------|
| **lupo_permissions** | Grants permission on (target_type, target_id) to either user_id or group_id. Unique on (target_type, target_id, user_id) and (target_type, target_id, group_id). |
| **lupo_channel_roles** | Channel-scoped roles: actor_id, channel_id, role_type (e.g. captain, administrator, editor, monitor, operator, support). Used for auth. |
| **lupo_actor_channel_roles** | Separate channel-role table (role_key, protocol, etc.); used in lupo-channels/awareness. |
| **lupo_actor_roles** | Exists in dev alignment (actor_id, context_id, department_id, role_key). Not referenced by AuthRoleResolver or auth code. |

### 2.2 How permissions are currently resolved

- **Auth (lupo-admin/operator):** `App\Auth\AuthRoleResolver` uses **lupo_channel_roles** only (channel_id = 1 for “global” admin; role_type IN ('captain', 'administrator')). Fallback: **lupo_permissions** with target_type = 'module', target_id = admin module_id, **user_id** and permission = 'owner'. **No group_id is used in auth resolution.**
- **Collections:** `App\Services\SavedCollectionsService` uses **lupo_permissions** with target_type = 'collection', **user_id** only (no group_id in the query).
- **No application code** was found that joins lupo_permissions to lupo_actor_group_membership or resolves permissions via group_id.

### 2.3 How groups are used in permission resolution

- **Schema:** lupo_permissions has group_id and unique index (target_type, target_id, group_id).
- **Code:** No PHP code reads or writes permission by group_id. Permission resolution is user_id-based (and channel_roles for lupo-admin/operator).

### 2.4 What would need to change to make departments permission-bearing

1. **Schema:** Add department_id to lupo_permissions (nullable); add unique index (target_type, target_id, department_id); eventually drop group_id.
2. **Resolution logic:** When checking permission for an actor: resolve actor→departments (lupo_actor_departments), then for each department_id check lupo_permissions where (target_type, target_id, department_id). Combine with existing user_id and channel-role checks.
3. **Writes:** When granting “to a department”, set department_id and leave user_id/group_id null (or phase out group_id).

### 2.5 Doctrine violations

- **No new doctrine violations** if changes are limited to: adding columns, application-side resolution logic, and dropping unused tables. Doctrine forbids DB-side logic (triggers, FKs, etc.); permission resolution remains in application code.
- **TOON / PK doctrine:** Any new or modified tables/columns must be reflected in TOONs (TOONs are read-only for Cursor; generate via scripts). PK/reference naming (e.g. department_id) already aligns with doctrine.

---

## 3. Feasibility of Dropping Group Tables

### 3.1 lupo_groups

| Dependency type | Finding |
|-----------------|--------|
| **Code** | No PHP references to lupo_groups. |
| **Modules** | No module references. |
| **UI** | No UI found that lists or edits groups. |
| **Permission logic** | lupo_permissions has group_id but no code path uses it for resolution. |
| **Migration (Crafty import)** | import_from_old_crafty_syntax.sql does not populate lupo_groups; it maps livehelp_* to departments (e.g. livehelp_departments→lupo_departments, livehelp_operator_departments→lupo_actor_departments). |
| **Installer / wizard** | install_wizard_classes.php and install flow use department_id (e.g. channels, department_id = 1); no references to lupo_groups. |

**Conclusion:** Safe to drop lupo_groups after: (1) dropping or migrating group_id from lupo_permissions and other tables, (2) updating install SQL so lupo_groups is no longer created (or replaced by a stub that is never used).

### 3.2 lupo_actor_group_membership

| Dependency type | Finding |
|-----------------|--------|
| **Code** | No PHP references to lupo_actor_group_membership. |
| **Modules** | No module references. |
| **UI** | No UI found. |
| **Permission logic** | Not used; auth uses channel_roles and (for admin fallback) lupo_permissions by user_id. |
| **Migration** | import_from_old_crafty_syntax.sql does not insert into lupo_actor_group_membership. Operator membership is migrated to lupo_actor_departments. |
| **Installer / wizard** | No references. |

**Schema note:** The install defines lupo_actor_group_membership without an actor_id column (only actor_group_membership_id, group_id, domain_id, …). So the table does not currently model “actor belongs to group” in the install schema. Dropping it is safe from a code and migration perspective; no actor→group data is produced by current code or Crafty import.

**Conclusion:** Safe to drop lupo_actor_group_membership once all references to it (and group_id elsewhere) are removed from schema/migrations.

---

## 4. Doctrine Alignment

| Criterion | Assessment |
|-----------|------------|
| **Simplifies ontology** | Yes. One organizational concept (department) instead of two (group + department). “Group” and “department” are often conflated in product language; unifying reduces ambiguity. |
| **Reduces cognitive load** | Yes. Developers and docs can refer to “department” as the single org and permission-bearing unit. |
| **Reduces schema complexity** | Yes. Fewer tables (drop lupo_groups, lupo_actor_group_membership), fewer columns (group_id removed), one membership table (actor_departments) for “actor in org”. |
| **Aligns with semantic OS goals** | Yes. Departments already represent Crafty-style “departments” and channel/module scoping; making them the single org boundary fits a clear, consistent model. |
| **Violates existing doctrine** | No. No FKs, triggers, or DB-side logic required. TOON/PK doctrine: use department_id consistently; no new violations. |
| **Ambiguity in identity, permissions, or routing** | None introduced. Identity remains actor/session/auth; permissions become “user + channel_roles + department-scoped permissions”; routing is unchanged. |

---

## 5. Recommended Path Forward

### **A. Recommended: Collapse groups into departments**

Unifying on departments is feasible and aligns with the codebase and doctrine. No production Lupopedia data depends on groups; Crafty import already uses departments.

#### 5.A.1 Tables to modify

- **lupo_permissions:** Add department_id (bigint DEFAULT NULL); add unique (target_type, target_id, department_id). Migrate: either map existing group_id to department_id (e.g. 1:1 seed) or delete group_id rows. Then drop group_id and index lupo_permissions_idx_group / lupo_permissions_uniq_target_group.
- **lupo_collections:** Add department_id (bigint DEFAULT NULL). Optionally backfill from context or default 1. Drop group_id.
- **lupo_collection_tabs:** Same as collections.
- **lupo_contents:** Same as collections.
- **lupo_analytics_referers_periods, lupo_analytics_visits_daily, lupo_analytics_visits_monthly, lupo_analytics_visits_periods:** Add department_id (e.g. default 1 or nullable); backfill; drop group_id.

#### 5.A.2 Tables to drop

- **lupo_actor_group_membership**
- **lupo_groups**

#### 5.A.3 Code to update

- **Permission resolution:** Add a path that resolves actor→department_id(s) via lupo_actor_departments and checks lupo_permissions by (target_type, target_id, department_id). Keep existing user_id and channel_roles logic.
- **SavedCollectionsService** (and any other permission checks): If “department-based” access is desired for collections, extend to consider department_id in lupo_permissions; otherwise leave as user_id-only until product requires it.
- **Seed/install scripts:** lupo-database/install/generate_content_seed.php and generate_hierarchical_seed_3.0.12.php reference group_id; switch to department_id and use NULL or default department 1.

#### 5.A.4 Permission logic to rewrite

- **New:** Resolve actor → list of department_id (from lupo_actor_departments). For each (target_type, target_id), allow if any lupo_permissions row matches (target_type, target_id, department_id) with sufficient permission. Combine with existing user_id and channel_roles checks (union of allowed).
- **Remove:** Any future or legacy logic that would resolve via group_id or lupo_actor_group_membership.

#### 5.A.5 Installer / wizard changes

- **install_new_lupopedia.sql:** Remove CREATE TABLE lupo_groups and lupo_actor_group_membership; remove group_id from lupo_permissions, lupo_collections, lupo_collection_tabs, lupo_contents, lupo_analytics_*; add department_id where needed.
- **install_wizard_classes.php:** No change if it already uses only department_id (it does).
- **Other lupo-install/seed files:** Use department_id only; remove group_id from INSERTs.

#### 5.A.6 Migration logic for Crafty Syntax imports

- **import_from_old_crafty_syntax.sql:** Already maps to departments (lupo_departments, lupo_actor_departments). No new migration from livehelp to groups. Remove any references to lupo_groups or lupo_actor_group_membership if present; ensure INSERTs into lupo_collections, lupo_contents, lupo_permissions use department_id (or NULL) and not group_id.

---

## 6. Output Summaries

### 6.1 Schema impact report

- **Tables modified:** lupo_permissions, lupo_collections, lupo_collection_tabs, lupo_contents, lupo_analytics_referers_periods, lupo_analytics_visits_daily, lupo_analytics_visits_monthly, lupo_analytics_visits_periods (add department_id; remove group_id).
- **Tables dropped:** lupo_groups, lupo_actor_group_membership.
- **Indexes:** Remove all group_id-based indexes; add department_id indexes where needed for permission and analytics queries.
- **lupo_gov_events.utc_group_id:** Unchanged (not a reference to lupo_groups).

### 6.2 Permission system impact report

- **Current:** Permissions resolved by user_id and channel_roles; group_id in schema but unused in code.
- **After:** Permissions resolved by user_id, channel_roles, and department_id (actor→actor_departments→lupo_permissions by department_id). No group_id.

### 6.3 Risk assessment

| Risk | Level | Mitigation |
|------|--------|------------|
| Breaking permission checks | Low | No code uses group_id today; adding department_id is additive. |
| Breaking Crafty import | Low | Import already uses departments; no group population. |
| Breaking lupo-install/wizard | Low | Wizard uses department_id only; install SQL must be updated to match. |
| Seed scripts | Low | Update generate_content_seed and generate_hierarchical_seed to use department_id. |
| Third-party or undocumented use of group_id | Low | Grep found no PHP references; document removal in release notes. |

### 6.4 Recommended plan

- **Proceed** with collapsing groups into departments: add department_id where only group_id exists, implement department-based permission resolution, migrate/backfill data, remove group_id and drop lupo_groups and lupo_actor_group_membership, update lupo-install/seed/migration and TOONs.

### 6.5 Affected files (candidate list)

- lupo-database/migrations/install_new_lupopedia.sql
- lupo-database/migrations/dev_20260204_fix_schema_alignment.sql (and any other migrations touching group_id or group tables)
- lupo-database/migrations/import_from_old_crafty_syntax.sql (if any group references)
- lupo-database/install/generate_content_seed.php
- lupo-database/install/generate_hierarchical_seed_3.0.12.php
- app/auth/AuthRoleResolver.php (if adding department-based permission check)
- app/Services/SavedCollectionsService.php (if adding department-based collection permission)
- lupo-docs/toons (regenerate after schema changes per project rules)
- Any new migration file for add department_id / drop group_id / drop lupo_groups / lupo_actor_group_membership

### 6.6 Affected tables

- **Modified:** lupo_permissions, lupo_collections, lupo_collection_tabs, lupo_contents, lupo_analytics_referers_periods, lupo_analytics_visits_daily, lupo_analytics_visits_monthly, lupo_analytics_visits_periods.
- **Dropped:** lupo_groups, lupo_actor_group_membership.
- **Unchanged but relevant:** lupo_actor_departments, lupo_departments, lupo_channel_roles, lupo_permissions (until migration).

---

*End of report. No files were modified; this is analysis only.*
