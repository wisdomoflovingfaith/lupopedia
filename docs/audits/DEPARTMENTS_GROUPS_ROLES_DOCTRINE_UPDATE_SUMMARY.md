# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\audits\DEPARTMENTS_GROUPS_ROLES_DOCTRINE_UPDATE_SUMMARY.md"
  file_hash: "45ed5315a0df078e1e2dab86d9034e2657ab17b6bcd40677e26f0dd24919ca91"
  file_path_from_root: "docs\audits\DEPARTMENTS_GROUPS_ROLES_DOCTRINE_UPDATE_SUMMARY.md"
  file_hash: "35ae130a8ead70beeb72e500f9c8d5be148d5bb865c1047495b3621478367d33"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Departments / Groups / Roles Unification — Doctrine Update Summary"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "audits", "departments_groups_roles_doctrine_update_summarymd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Departments / Groups / Roles Unification — Doctrine Update Summary

**Date:** 2026-02-12  
**Prerequisite:** Schema applied; TOONs regenerated; PHP execution complete (see DEPARTMENTS_GROUPS_ROLES_PHP_EXECUTION_SUMMARY.md).  
**Scope:** Documentation only; no code or SQL changes in this phase.

---

## 1. Doctrine Files Updated

| Document | Summary of changes |
|----------|--------------------|
| **docs/REQUIRED_TABLES_4.1.0.md** | Removed lupo_actor_group_membership and lupo_groups from required tables lists. Added "Organizational scope" paragraph: departments are the sole organizational unit; group tables are removed; lupo_permissions uses department_id; permission satisfied if user_id OR department_id (actor's departments) OR channel_roles. |
| **docs/channels/schema/DATABASE_SCHEMA.md** | In Core Identity §1: replaced actor_group_membership with actor_departments; added note that group tables are removed. Replaced full actor_group_membership section with actor_departments (key fields, permission model). Removed groups and group_modules sections; updated departments section to state it is the sole organizational unit and that permissions/collections/contents/analytics use department_id. |
| **docs/doctrine/DEVELOPMENT_WORKFLOW_DOCTRINE.md** | Added paragraph under §3: organizational scope is department only; lupo_groups and lupo_actor_group_membership are removed; use lupo_departments and lupo_actor_departments; schema alignment and TOON regeneration reflect department_id. |
| **docs/doctrine/SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md** | Added bullet: unification migration (migration_unify_groups_into_departments.sql) added department_id, dropped group_id and group tables; schema and TOONs are department-only. In Takeaways: added point 2 (organizational scope department only, no group tables, department_id for permissions/scoping, regenerate TOONs after unification); renumbered points 3–6; added unification migration to Files reference table. |
| **docs/channels/schema/migrations/analysis/SCHEMA_SYNC_3_0_46_SUMMARY.md** | Updated lupo_permissions description: purpose now states permission satisfied if user_id OR department_id OR channel_roles; fields use department_id (group_id removed); indexes use unique (target+department), index (department_id). |
| **docs/channels/schema/migrations/3.0.46.md** | Updated Tables Added: actor_collections now "users, agents" (removed "groups"); lupo_permissions now documents department_id, OR-based permission model, and that group tables have been removed (department-only scope). |

---

## 2. Confirmations

### 2.1 All doctrine references to groups removed

- **REQUIRED_TABLES_4.1.0.md:** lupo_groups and lupo_actor_group_membership removed from lists; only mention is that they are removed.
- **DATABASE_SCHEMA.md:** actor_group_membership section replaced by actor_departments; groups and group_modules sections removed; departments section updated; no remaining normative reference to group tables.
- **DEVELOPMENT_WORKFLOW_DOCTRINE.md:** States group tables are removed; no creation or reference.
- **SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md:** Unification and department-only scope documented; no group tables in schema.
- **SCHEMA_SYNC_3_0_46_SUMMARY.md, 3.0.46.md:** group_id replaced with department_id; group tables noted as removed.

### 2.2 New permission model documented

- **REQUIRED_TABLES_4.1.0.md:** Permission satisfied if user_id OR department_id (actor's departments) OR channel_roles; permissions table uses department_id.
- **DATABASE_SCHEMA.md:** actor_departments and permissions.department_id; "permission is satisfied if user_id OR department_id (actor's departments) OR channel_roles grant."
- **SCHEMA_SYNC_3_0_46_SUMMARY.md, 3.0.46.md:** lupo_permissions uses department_id; OR-based model stated.

### 2.3 Schema diagrams and tables updated

- **DATABASE_SCHEMA.md:** Core Identity subsection and table descriptions updated (actor_departments, departments; groups/group_modules removed). Permissions, collections, contents, analytics referenced as using department_id where applicable.
- **REQUIRED_TABLES_4.1.0.md:** Table lists updated; no group tables.

### 2.4 TOON regeneration rules updated

- **SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md:** After unification migration, regenerate TOONs so they reflect current schema; department-only; no group tables. Unification migration added to Files reference.
- **DEVELOPMENT_WORKFLOW_DOCTRINE.md:** Schema alignment and TOON regeneration after unification reflect department_id.

### 2.5 Doctrine internally consistent

- Canonical schema is install_new_lupopedia.sql (no group tables, department_id on affected tables).
- All updated docs state: departments only; group tables removed; permission = user_id OR department_id OR channel_roles; department_id used for permissions, collections, tabs, contents, analytics.

---

## 3. Final Smoke-Test Checklist

Use this checklist after doctrine updates to validate the full unification path. Execute in order where dependencies exist.

### 3.1 Installer

| # | Check | Pass |
|---|--------|------|
| 1 | **Fresh install:** Run install_new_lupopedia.sql on empty DB; then seed. | ☐ |
| 2 | **No group tables:** Confirm lupo_groups and lupo_actor_group_membership do not exist after install. | ☐ |
| 3 | **department_id present:** Confirm lupo_permissions, lupo_collections, lupo_collection_tabs, lupo_contents, and analytics tables have department_id (no group_id). | ☐ |
| 4 | **Crafty upgrade path:** Load old_crafty_syntax_3_7_5.sql (or equivalent), run wizard; complete upgrade. | ☐ |
| 5 | **Config writer:** lupopedia-config.php written; no references to group tables. | ☐ |
| 6 | **Wizard steps:** Credentials → install → seed → reserved channels → identity normalization (if upgrade) → import → operator channels → drop legacy → config. | ☐ |
| 7 | **Channel creation:** Reserved and operator channels use department_id only; no group_id in INSERTs. | ☐ |
| 8 | **Department creation:** Departments and actor_departments populated as expected; no group creation. | ☐ |

### 3.2 Authentication

| # | Check | Pass |
|---|--------|------|
| 9 | **Login:** Unified login succeeds; session created. | ☐ |
| 10 | **Session creation:** Session record in lupo_sessions; no group table access. | ☐ |
| 11 | **Identity resolution:** Actor resolved from auth user; actor_departments used where needed. | ☐ |
| 12 | **CSRF / redirects:** Login redirect and legacy Crafty redirect work; no errors. | ☐ |

### 3.3 Permissions

| # | Check | Pass |
|---|--------|------|
| 13 | **user_id path:** User with lupo_permissions row (user_id) can access target (e.g. admin module, collection). | ☐ |
| 14 | **department_id path:** Actor in lupo_actor_departments; lupo_permissions has (target_type, target_id, department_id); access granted. | ☐ |
| 15 | **Channel roles path:** Actor with channel role (e.g. captain/administrator on channel_id=1) has admin. | ☐ |
| 16 | **SavedCollectionsService:** Collections list includes collections allowed by user_id and by department_id. | ☐ |
| 17 | **AuthRoleResolver::isAdmin:** Returns true for channel role, user_id permission, or department_id permission on admin module. | ☐ |

### 3.4 Collections / Tabs / Contents

| # | Check | Pass |
|---|--------|------|
| 18 | **Creation:** Create collection/tab/content with department_id (or NULL); no group_id. | ☐ |
| 19 | **Editing:** Edit existing collection/tab/content; visibility and permission checks use department_id where applicable. | ☐ |
| 20 | **Visibility rules:** No UI or query filters by group_id. | ☐ |
| 21 | **Department scoping:** Where applicable, scope by department_id; no group scoping. | ☐ |

### 3.5 Analytics

| # | Check | Pass |
|---|--------|------|
| 22 | **Daily / monthly / periods:** Analytics tables (e.g. lupo_analytics_visits_daily, lupo_analytics_visits_periods) have department_id; queries use department_id. | ☐ |
| 23 | **department_id filters:** Any analytics filtering uses department_id; no group_id. | ☐ |

### 3.6 API

| # | Check | Pass |
|---|--------|------|
| 24 | **list_user_collections:** Returns collections; no group_id in query or response. | ☐ |
| 25 | **Endpoints referencing permissions:** Any permission check uses user_id OR department_id OR channel roles; no group references. | ☐ |

### 3.7 UI

| # | Check | Pass |
|---|--------|------|
| 26 | **Admin:** Admin area loads; permission from channel role or user/department permission. | ☐ |
| 27 | **Channels:** Channel UI uses department_id; operator pending visitors by department. | ☐ |
| 28 | **Departments:** Department list and assignment (actor_departments) work. | ☐ |
| 29 | **Collections:** Saved collections nav and collection list show correct data; no group references. | ☐ |
| 30 | **No group references:** No UI labels, links, or forms for "groups" or group membership. | ☐ |

### 3.8 TOONs

| # | Check | Pass |
|---|--------|------|
| 31 | **Regenerated TOONs:** TOONs generated from DB after migration/install; no lupo_groups or lupo_actor_group_membership TOONs. | ☐ |
| 32 | **Schema match:** Affected TOONs (permissions, collections, collection_tabs, contents, analytics_*) show department_id; no group_id. | ☐ |

---

*End of doctrine update summary and smoke-test checklist.*