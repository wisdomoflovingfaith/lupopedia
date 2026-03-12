# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\audits\DEPARTMENTS_GROUPS_ROLES_PHP_IMPLEMENTATION_PLAN.md"
  file_hash: "4bf514482be3cf827107d7c7f680b9c474e26dc929707d4a504f8aae242c8e4f"
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
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\audits\DEPARTMENTS_GROUPS_ROLES_PHP_IMPLEMENTATION_PLAN.md"
  file_hash: "db293cf3d694a9b823d9e6fd1749cb2540d27dbe1bd9bca1250323eb63f00e46"
  file_path_from_root: "docs\audits\DEPARTMENTS_GROUPS_ROLES_PHP_IMPLEMENTATION_PLAN.md"
  file_hash: "b41eaa3ec1302a353e9c271cf45751b7b2d35348a41b6a5555c33658e82db80b"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Departments / Groups / Roles Unification — PHP-Level Implementation Plan"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "audits", "departments_groups_roles_php_implementation_planmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Departments / Groups / Roles Unification — PHP-Level Implementation Plan

**Date:** 2026-02-12  
**Prerequisite:** Schema already applied via `migration_unify_groups_into_departments.sql`; TOONs regenerated; SQL phase complete (see DEPARTMENTS_GROUPS_ROLES_EXECUTION_SUMMARY.md).  
**Scope:** Planning only — no PHP files are modified in this document.  
**Authority:** DEPARTMENTS_GROUPS_ROLES_IMPLEMENTATION_PLAN.md, DEPARTMENTS_GROUPS_ROLES_PRE_EXECUTION_CHECKLIST.md.

---

## 1. PHP Files Requiring Updates — Master Table

### 1.1 Summary

- **No PHP code currently reads `group_id`, `lupo_groups`, or `lupo_actor_group_membership`.** Grep confirms zero runtime references. No files need "remove group references" from live logic.
- **SQL generators** were already updated in the SQL phase: `generate_content_seed.php` and `generate_hierarchical_seed_3.0.12.php` use `department_id`. No further generator changes required for column names.
- **PHP-level work** is: (1) optional extension of permission resolution to `department_id`, (2) verification that no installer/wizard/Crafty PHP assumes groups, (3) documentation and doctrine updates.

### 1.2 File-by-File Table

| File path | Type of update | Reason for update |
|-----------|----------------|-------------------|
| **app/auth/AuthRoleResolver.php** | Logic rewrite (optional) | Add department-based permission path: resolve actor → departments via lupo_actor_departments; allow if lupo_permissions has (target_type, target_id, department_id) for any of actor’s departments. Current: channel_roles + lupo_permissions by user_id only. |
| **app/Services/SavedCollectionsService.php** | Logic rewrite (optional) | Extend collection permission check to include lupo_permissions by (target_type='collection', target_id, department_id) for current actor’s departments. Today: JOIN on user_id only. |
| **api/list_user_collections.php** | Verify only | Uses actor_collections (actor_id), not lupo_permissions. If SavedCollectionsService gains department permission, consider aligning this endpoint to same model (user_id OR department_id) for consistency; else verify only. |
| **routes/auth_routes.php** | Documentation | `/auth/permissions` is null (no handler). When a handler is added, document that permissions are user_id OR department_id OR channel_roles. No code change until handler exists. |
| **install.php** | Verify only | Confirms it only invokes install_new_lupopedia.sql and seed; no group table creation. No change expected. |
| **install_wizard_classes.php** | Verify only | Already uses department_id for channels. Confirm no INSERT/SELECT references group_id or lupo_groups. Add short comment that group tables are removed. |
| **lupo-includes/bootstrap.php** | Verify only | Instantiates AuthRoleResolver and SavedCollectionsService; no group refs. No change. |
| **lupo-includes/functions/auth-helpers.php** | Verify only | No group refs; delegates to AuthRoleResolver. No change. |
| **lupo-includes/functions/render-saved-collections.php** | Verify only | Thin wrapper around SavedCollectionsService. No change unless service is extended. |
| **lupo-includes/modules/auth/auth-renderer.php** | No change | "form-group" is CSS class only. |
| **lupo-includes/modules/crafty_syntax/* (livehelp.php, visitor-image.php, livehelp-js.php, choosedepartment.php, visitor-session-helper.php)** | Verify only | Use department_id only; no group_id. Grep for "group" in permission/scope logic — none found (only form-group, GROUP BY, or unrelated "group" in TrinitaryRouter word list). |
| **image.php, livehelp_js.php** | Verify only | Same as above; department_id only. |
| **lupo-includes/modules/channels/* (operator-pending-visitors-api, channels-controller, operator-accept-visitor-api, views)** | Verify only | Use department_id and lupo_actor_departments; no group refs. No change. |
| **lupo-includes/modules/module-loader.php** | Verify only | Uses channel_roles and department_id; no group_id. No change. |
| **lupo-includes/models/GroundedAgentModel.php** | Verify only | If "permissions table" or "group" appears in comments, update to department/permission model. |
| **app/Services/CraftySyntax/* (WorldGraphHelper, LegacyUserChatRefresh, LegacyIsFlushDetection, LegacyChooseDepartment, LegacyDepartments, etc.)** | Verify only | Use departments only; no group logic. Verify no stray "group" references. |
| **app/Http/Controllers/CraftyImportController.php, app/Services/CraftyConfigTransformer.php** | Verify only | No group references in config/import logic. |
| **database/install/generate_content_seed.php** | No change | Already updated in SQL phase: department_id in column list and values. |
| **database/install/generate_hierarchical_seed_3.0.12.php** | No change | Already updated in SQL phase: department_id in generated INSERTs. |
| **install_wizard_classes.php (findDuplicateEmailGroups)** | No change | "Duplicate email groups" = groups of duplicate emails, not lupo_groups. |

### 1.3 Files Not Previously Listed (added)

| File | Type | Reason |
|------|------|--------|
| **api/list_user_collections.php** | Verify / optional align | Uses actor_collections; if permission model becomes user OR department, consider including collections where user has access via department_id in lupo_permissions. |
| **lupo-includes/bootstrap.php** | Verify | Ensure AuthRoleResolver and SavedCollectionsService are still correct after any optional permission changes. |

### 1.4 Runtime Logic That Implicitly Assumed Groups

- **None.** No PHP reads lupo_groups or lupo_actor_group_membership. No code filters by group_id. Dropping tables and columns does not break any existing PHP.

### 1.5 UI Components That Referenced Groups

- **None.** No UI lists or edits groups.

### 1.6 Seed/Install Generators That Referenced Groups

- **Already updated in SQL phase:** generate_content_seed.php, generate_hierarchical_seed_3.0.12.php. No other PHP generators reference group_id.

---

## 2. Permission System Rewrite Plan (PHP-Level)

### 2.1 New Permission Model (Target State)

- **user_id** — Allow if lupo_permissions has (target_type, target_id, user_id) and permission sufficient. (Unchanged.)
- **department_id** — Resolve actor → list of department_id via lupo_actor_departments. Allow if any lupo_permissions row exists with (target_type, target_id, department_id) for one of those departments. (New path.)
- **Channel roles** — lupo_channel_roles (e.g. channel_id = 1 for global): captain/administrator → admin. (Unchanged.)
- **Combined:** Allow if user_id match OR any department_id match OR channel_roles grant.

### 2.2 Permission-Related Classes and Helpers

| Location | Role |
|----------|------|
| **app/auth/AuthRoleResolver.php** | isAdmin($actorId), hasAnyChannelRole($actorId). Uses channel_roles + lupo_permissions (user_id only for fallback admin). |
| **app/auth/AuthService.php** | Delegates isAdmin to AuthRoleResolver. No direct permission queries. |
| **app/Services/SavedCollectionsService.php** | renderSavedCollections($userId): JOIN lupo_permissions on user_id only. loadTabChildren, countTabItems: no permission. |
| **lupo-includes/functions/auth-helpers.php** | Thin wrapper around AuthRoleResolver for admin check. |
| **routes/auth_routes.php** | '/auth/permissions' => null; no handler yet. |

### 2.3 Places Where Group-Based Logic Appears in Comments/Docs

- **Implementation plan / checklist:** Describe group_id as removed and department_id as the permission-bearing scope.
- **DATABASE_SCHEMA.md:** Sections describing actor_group_membership, groups, group_modules (see §5).
- **REQUIRED_TABLES_4.1.0.md:** Lists lupo_actor_group_membership and lupo_groups; must be removed and replaced with note that scope is department only.
- **SCHEMA_SYNC_3_0_46_SUMMARY.md, 3.0.46.md:** Reference group_id in lupo_permissions; update to department_id in narrative.

### 2.4 Edge Cases and How to Handle Them

| Edge case | Handling |
|-----------|----------|
| **NULL user_id, NULL department_id in lupo_permissions** | Row is "global" or unused; do not grant by that row for a specific user/department. Only grant when user_id matches current user OR department_id is in actor’s department list. |
| **NULL department_id for actor** | Actor has no lupo_actor_departments rows → department-based path grants nothing. Rely on user_id and channel_roles only. |
| **Multi-department actor** | Resolve actor_id → all department_id from lupo_actor_departments. Allow if any of those department_ids appears in lupo_permissions for (target_type, target_id, department_id). |
| **user_id and department_id both set on same permission row** | Schema allows both; application should treat as "grant to this user OR this department". For isAdmin fallback, only user_id is used today; department path would be additive. |
| **No lupo_permissions rows for a collection** | SavedCollectionsService: no access via permissions (only public or actor_collections if that path is used elsewhere). |

### 2.5 Required Updates to SavedCollectionsService

- **Current:** `renderSavedCollections(int $userId)` — when $userId > 0, JOIN lupo_collections with lupo_permissions on target_type='collection', target_id=collection_id, **user_id** only.
- **Change (optional):** Accept actor_id (or resolve userId → actor_id). Resolve actor_id → department_ids from lupo_actor_departments. In the collections query, include collections where lupo_permissions has (target_type='collection', target_id, **department_id**) for any of those department_ids. Combine with existing user_id-based result (DISTINCT).
- **Signature:** Either keep `renderSavedCollections(int $userId)` and resolve userId → actor_id internally, or add overload `renderSavedCollectionsByActor(int $actorId)` and have callers pass actor when available.

### 2.6 Required Updates to AuthRoleResolver

- **Current:** isAdmin: (1) channel_roles (channel_id=1, captain/administrator), (2) fallback lupo_permissions (owner on admin module for **user_id** from actor).
- **Change (optional):** After (1) and (2), add (3) department fallback: resolve actor_id → department_ids; allow if any lupo_permissions row (target_type='module', target_id=admin_module_id, **department_id** IN actor’s departments, permission=owner). Same for any future "hasPermission(actorId, target_type, target_id)" helper.

### 2.7 Channel Role Logic

- **No change.** Channel roles (lupo_channel_roles) and channel→department (lupo_channels.department_id) are already used. Operator pending visitors and channels controller resolve department_id from channel or actor_departments. No group_id involved.

### 2.8 File-by-File Permission Change Plan

| File | Change |
|------|--------|
| AuthRoleResolver.php | Add private method getDepartmentIdsForActor($actorId). In isAdmin, after user_id fallback, add department-based check: get department_ids, then SELECT 1 FROM lupo_permissions WHERE target_type='module' AND target_id=:module_id AND department_id IN (:ids) AND permission='owner'. Return true if any. |
| SavedCollectionsService.php | Option A: In renderSavedCollections($userId), resolve userId → actor_id (via lupo_actors where actor_source_type='user' and actor_source_id=userId). Get department_ids for that actor. Extend SQL to include collections where lupo_permissions (target_type='collection', target_id, department_id) IN actor’s department_ids. Use DISTINCT. Option B: Add renderSavedCollectionsByActor($actorId) and use it from callers that have actor_id. |
| routes/auth_routes.php | Document in comment: when /auth/permissions is implemented, return permissions combining user_id, department_id, and channel_roles. |

### 2.9 Logic-Flow Diagram (Textual)

```
Permission check (target_type, target_id, actor_id):
  1. Resolve actor_id → auth_user_id (if user-backed) and → list of department_ids (lupo_actor_departments).
  2. Channel path: if lupo_channel_roles grants role for channel_id=1 (e.g. captain/administrator) → ALLOW.
  3. User path: if lupo_permissions has (target_type, target_id, user_id=auth_user_id) with sufficient permission → ALLOW.
  4. Department path: if lupo_permissions has (target_type, target_id, department_id IN department_ids) with sufficient permission → ALLOW.
  5. Otherwise → DENY.
```

---

## 3. Installer + Wizard PHP Update Plan

### 3.1 Installer PHP Files to Update

| File | Action |
|------|--------|
| **install.php** | Verify only: no creation of lupo_groups or lupo_actor_group_membership; no reference to group_id. Invokes install_new_lupopedia.sql and seed. No change expected. |

### 3.2 Wizard PHP Files to Update

| File | Action |
|------|--------|
| **install_wizard_classes.php** | Verify: channel INSERTs use department_id only. Confirm no SELECT/INSERT references group_id or lupo_groups. Add one-line comment near channel creation: "Group tables (lupo_groups, lupo_actor_group_membership) are removed; scope is department only." |

### 3.3 Crafty Syntax Import PHP Files to Update

| File | Action |
|------|--------|
| **app/Http/Controllers/CraftyImportController.php** | Verify no group references. Import uses SQL files (already updated to department_id). |
| **app/Services/CraftyConfigTransformer.php** | Verify no group references in config or permission wording. |

- **No PHP invokes INSERT into lupo_groups or lupo_actor_group_membership.** Crafty import is SQL-driven (import_from_old_crafty_syntax.sql, craftysyntax_to_lupopedia_mysql.sql); already updated in SQL phase.

---

## 4. Runtime Logic Update Plan

### 4.1 Runtime Behaviors Affected

| Behavior | Affected? | Notes |
|----------|-----------|--------|
| **Identity resolution** | No | Actor ↔ user resolution does not use groups. |
| **Actor → department resolution** | No | Already implemented: operator-pending-visitors-api, channels-controller use lupo_actor_departments and channel.department_id. |
| **Channel → department resolution** | No | lupo_channels.department_id already used. |
| **Permission checks** | Optional | Extend to department_id path in AuthRoleResolver and SavedCollectionsService (see §2). |
| **UI visibility rules** | No | No UI that shows/hides by group. |
| **Analytics filters** | No | No PHP found that filters analytics by group_id; analytics tables now have department_id; any future analytics PHP should use department_id. |
| **Code that previously filtered by group_id** | None | No such code exists. |

### 4.2 Files Implementing Permission/Collection Behaviors

| Behavior | File(s) | Plan |
|----------|---------|------|
| Admin check | AuthRoleResolver::isAdmin, auth-helpers | Optional: add department-based admin fallback. |
| Collection list for nav | SavedCollectionsService::renderSavedCollections | Optional: include collections allowed by department_id. |
| Collection list API | api/list_user_collections.php | Verify; optionally align with SavedCollectionsService permission model once extended. |
| Channel/operator context | operator-pending-visitors-api, channels-controller, module-loader | No change; already department-based. |

### 4.3 Plan for Updating Each Behavior

- **Admin check:** Implement getDepartmentIdsForActor in AuthRoleResolver; in isAdmin, after user_id fallback, add department-based permission check.
- **Collection list:** Extend SavedCollectionsService to include collections where lupo_permissions (target_type='collection', target_id, department_id) matches actor’s departments; keep user_id path.
- **list_user_collections.php:** If product wants consistency with SavedCollectionsService, add department-based collection visibility when that service is extended; else leave as-is (actor_collections + fallback to all).

---

## 5. Doctrine Update Plan (PHP-Level Impact)

### 5.1 Doctrine Documents to Update

| Document | Sections to rewrite / add / remove |
|----------|-----------------------------------|
| **docs/REQUIRED_TABLES_4.1.0.md** | Remove lupo_actor_group_membership and lupo_groups from required tables. Add note: "Organizational scope and permission-bearing entity is department only; group tables are removed." |
| **docs/channels/schema/DATABASE_SCHEMA.md** | Rewrite or remove: actor_group_membership (§313–319), groups / group_modules (§991–1016). Replace with actor_departments and department-scoped permissions. Update any lupo_permissions description from group_id to department_id. |
| **docs/doctrine/DEVELOPMENT_WORKFLOW_DOCTRINE.md** | Add short note: "Organizational unit for permissions and scope is department only (lupo_departments, lupo_actor_departments). Group tables (lupo_groups, lupo_actor_group_membership) are removed." |
| **docs/doctrine/SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md** | If it enumerates tables, remove lupo_groups and lupo_actor_group_membership. Note department_id on lupo_permissions, lupo_collections, lupo_collection_tabs, lupo_contents, lupo_analytics_*. |
| **docs/channels/schema/migrations/analysis/SCHEMA_SYNC_3_0_46_SUMMARY.md** | Update lupo_permissions description: group_id → department_id. |
| **docs/channels/schema/migrations/3.0.46.md** | Update narrative: permissions are user- and department-scoped; no groups. |

### 5.2 New Doctrine Rules to Add

- Permission resolution: "Allow if user_id match OR department_id match (actor’s departments) OR channel_roles grant. No group-based permission."
- Schema: "lupo_groups and lupo_actor_group_membership do not exist. Use lupo_departments and lupo_actor_departments for organizational scope."

### 5.3 Old Doctrine Rules to Remove

- Any reference to "group_id" or "groups" as a permission-bearing or organizational table in canonical schema docs. Keep "group" only where it means "group of" (e.g. duplicate email groups) or CSS class names.

---

## 6. Risk Assessment (PHP-Level)

### 6.1 Risks Introduced by PHP-Level Changes

| Risk | Severity | Mitigation |
|------|----------|------------|
| **Permission logic regression** | Medium | Add department path only after user_id and channel_roles; test isAdmin and collection list with and without department data. |
| **Actor without departments** | Low | Department path returns no rows; behavior same as today (user_id and channel only). |
| **Multi-department actor** | Low | IN (department_ids) is well-defined; test with actor in 2+ departments. |
| **SavedCollectionsService signature change** | Low | Prefer extending existing method (resolve userId → actor_id internally) to avoid breaking callers. |
| **list_user_collections vs SavedCollectionsService** | Low | Document that list_user_collections uses actor_collections; optional later alignment with permission model. |

### 6.2 Potential Regressions

- **Installer/wizard:** Unlikely; no group references. Verification only.
- **Crafty import:** SQL already updated; PHP only drives execution. No regression expected.
- **Channels/operator:** Already department-based; no change.

### 6.3 Permission-System Edge Cases

- See §2.4 (NULL user_id/department_id, multi-department, etc.). Handled by explicit resolution order and IN (department_ids).

### 6.4 Crafty Syntax Edge Cases

- Import SQL uses department_id; legacy data has no lupo_groups. No PHP in import path references groups. Safe.

### 6.5 Installer/Wizard Edge Cases

- New install: install_new_lupopedia.sql has no group tables. Upgrade: migration already dropped groups. Wizard does not create groups. Safe.

### 6.6 Runtime Behavior Changes

- Only if optional permission extension is implemented: more collections may appear for users who have department-based permission but not user_id permission. Document as intended.

---

## 7. Final Output: Execution Order and Checklist

### 7.1 Recommended Execution Order for PHP Changes

1. **Doctrine and docs first (no code):** Update REQUIRED_TABLES_4.1.0.md, DATABASE_SCHEMA.md, DEVELOPMENT_WORKFLOW_DOCTRINE.md, SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md, and migration analysis docs to remove group tables and state department-only scope.
2. **Verification pass:** Grep all PHP for group_id, lupo_groups, actor_group_membership; confirm zero. Verify install.php, install_wizard_classes.php, CraftyImportController, CraftyConfigTransformer, and channel/Crafty modules.
3. **Optional permission extension:** Implement AuthRoleResolver::getDepartmentIdsForActor and department-based isAdmin fallback; then extend SavedCollectionsService to include department-based collection access; then optionally align list_user_collections.php.
4. **Comment additions:** install_wizard_classes.php (group tables removed); routes/auth_routes.php (/auth/permissions doc).
5. **Smoke test:** New install, upgrade path, login, admin check, saved collections nav, list_user_collections API.

### 7.2 Checklist Summary

| Phase | Items |
|-------|--------|
| **Docs** | REQUIRED_TABLES, DATABASE_SCHEMA, DEVELOPMENT_WORKFLOW, SCHEMA_AND_TOON, analysis docs |
| **Verify** | install.php, install_wizard_classes.php, AuthRoleResolver, SavedCollectionsService, Crafty PHP, channel PHP, list_user_collections, bootstrap, auth-helpers |
| **Optional logic** | AuthRoleResolver department path, SavedCollectionsService department path, list_user_collections alignment |
| **Comments** | install_wizard (groups removed), auth_routes (/auth/permissions model) |
| **Test** | Install, upgrade, login, admin, collections, API |

---

*End of PHP-level implementation plan. No PHP files have been modified; this document is planning only.*