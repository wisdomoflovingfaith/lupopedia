# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/audits/DEPARTMENTS_GROUPS_ROLES_PHP_EXECUTION_SUMMARY.md"
  file_hash: "6c5180706b24b0c1d0331407032bb4b24206c0df1f7e1ba2dd37bc5c728168a8"
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
  file_path_from_root: "lupo-docs\audits\DEPARTMENTS_GROUPS_ROLES_PHP_EXECUTION_SUMMARY.md"
  file_hash: "a1078f728118003902e6c71da565cd1cea370f58a0981e02b7898d8d11640d8f"
  file_path_from_root: "lupo-docs\audits\DEPARTMENTS_GROUPS_ROLES_PHP_EXECUTION_SUMMARY.md"
  file_hash: "dacac8d3e431e38bb4ea08886171aa1abce6c0592a6d0a869f5d9f8859219d6c"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Departments / Groups / Roles Unification — PHP Execution Summary"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "audits", "departments_groups_roles_php_execution_summarymd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Departments / Groups / Roles Unification — PHP Execution Summary

**Date:** 2026-02-12  
**Authority:** lupo-docs/audits/DEPARTMENTS_GROUPS_ROLES_PHP_IMPLEMENTATION_PLAN.md  
**Prerequisite:** Schema applied (migration_unify_groups_into_departments.sql); TOONs regenerated; SQL phase complete.

---

## 1. PHP Files Modified

| File | Changes made |
|------|----------------|
| **app/auth/AuthRoleResolver.php** | Updated class docblock to state permission model (user_id OR department_id OR channel roles; no group tables). Added private getDepartmentIdsForActor($actorId). In isAdmin(), added step (3): after user_id fallback, resolve actor → department_ids and allow if lupo_permissions has owner on admin module for any department_id in that list. |
| **app/Services/SavedCollectionsService.php** | Updated class docblock to state permissions via user_id OR department_id (no group tables). Replaced inline collection query for logged-in users with getCollectionsForUser(). Added private getCollectionsForUser($userId, $collT, $permT) which resolves userId → actor_id, gets department_ids for actor, and returns collections where lupo_permissions has user_id = userId OR department_id IN (actor's departments). Added getActorIdFromAuthUserId($userId) and getDepartmentIdsForActor($actorId). |
| **routes/auth_routes.php** | Added comment for '/auth/permissions': "when implemented: return permissions combining user_id, department_id, and channel_roles (no group tables)". |
| **install_wizard_classes.php** | Added docblock above class InstallWizardChannels: "Channel creation for lupo-install/upgrade. Uses department_id only. Group tables (lupo_groups, lupo_actor_group_membership) are removed; organizational scope is department only." |

---

## 2. List of All Changes Made

### 2.1 Permission system

- **AuthRoleResolver:** Permission is satisfied if (1) channel_roles grant, or (2) lupo_permissions by user_id, or (3) lupo_permissions by department_id for any of actor's departments (lupo_actor_departments). Implemented for isAdmin() with new getDepartmentIdsForActor() and department-based SELECT on lupo_permissions.
- **SavedCollectionsService:** Collections for a logged-in user are those where lupo_permissions (target_type='collection') has user_id = userId OR department_id IN (actor's department_ids). Implemented via getCollectionsForUser(), getActorIdFromAuthUserId(), getDepartmentIdsForActor().

### 2.2 Documentation / comments

- **auth_routes.php:** Documented /auth/permissions future behavior (user_id, department_id, channel_roles).
- **install_wizard_classes.php:** Documented that group tables are removed and scope is department only.

### 2.3 Verification (no code changes)

- **install.php:** Grep confirmed no references to group_id, lupo_groups, or actor_group_membership.
- **install_wizard_classes.php:** Grep confirmed no such references; only the new comment mentions group tables as removed.
- **app/Http/Controllers/CraftyImportController.php, app/Services/CraftyConfigTransformer.php, app/Services/CraftySyntax/***: Grep confirmed no group references.

---

## 3. Confirmations

### 3.1 No PHP references to group_id or group tables remain

- **Runtime logic:** No PHP file reads group_id, lupo_groups, or lupo_actor_group_membership. The only occurrence in PHP is the comment in install_wizard_classes.php stating that those tables are removed.
- **Grep result:** No matches for group_id, lupo_groups, or actor_group_membership in *.php except the docblock in install_wizard_classes.php (documentation only).

### 3.2 Permission system supports user_id OR department_id OR channel roles

- **AuthRoleResolver::isAdmin():** Uses (1) lupo_channel_roles, (2) lupo_permissions by user_id, (3) lupo_permissions by department_id (actor's departments). Combined with OR semantics.
- **SavedCollectionsService::renderSavedCollections():** For userId > 0, collections are those with lupo_permissions (target_type='collection') where user_id = userId OR department_id IN (actor's departments). Channel roles are not used for collection list (unchanged); lupo-admin/operator checks remain in AuthRoleResolver.

### 3.3 Installer/wizard PHP aligned

- **install.php:** Invokes install_new_lupopedia.sql and seed; no group table creation; verified no group references.
- **install_wizard_classes.php:** Uses department_id for channels; no INSERT/SELECT for group_id or lupo_groups; comment added that group tables are removed.

### 3.4 Runtime logic aligned

- Identity resolution: unchanged (no group usage).
- Actor → department resolution: already present in operator-pending-visitors-api and channels-controller; AuthRoleResolver and SavedCollectionsService now use getDepartmentIdsForActor() for permission path.
- Channel → department resolution: unchanged.
- Permission checks: updated per plan (AuthRoleResolver, SavedCollectionsService).
- Analytics: no PHP filters by group_id; schema uses department_id.
- UI: no group references.

### 3.5 Doctrine satisfied

- No foreign keys, triggers, or stored procedures introduced.
- All DB access via PDO_DB and bound parameters; table prefix via LUPO_TABLE_PREFIX.
- No references to dropped group tables in executable code; only documentation notes removal.

### 3.6 No modern PHP syntax introduced

- New code uses array() for arrays and isset() for optional keys where appropriate.
- No new use of ?? or <=> in the added methods. Existing file style (e.g. [] and <=> elsewhere in SavedCollectionsService) was left unchanged where not modified by this phase.

---

## 4. Files Not Modified (Verify Only)

Per the plan, the following were verified only; no changes were required:

- install.php  
- lupo-includes/bootstrap.php  
- lupo-includes/functions/auth-helpers.php  
- lupo-includes/functions/render-saved-collections.php  
- lupo-api/list_user_collections.php  
- app/Http/Controllers/CraftyImportController.php (and Crafty PHP)  
- app/Services/CraftyConfigTransformer.php  
- app/Services/CraftySyntax/*  
- lupo-includes/modules/crafty_syntax/*, image.php, livehelp_js.php  
- lupo-includes/modules/channels/*  
- lupo-includes/modules/module-loader.php  
- lupo-includes/models/GroundedAgentModel.php  
- lupo-database/install/generate_content_seed.php, generate_hierarchical_seed_3.0.12.php  

---

*End of PHP execution summary.*
