# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\HELPER_TO_CLASS_MAPPING_ANALYSIS.md"
  file_hash: "0213b4e9aa281a5cd7b27b445eb7803629d5dfdad8fe62df8a94d101f54ddfae"
  file_path_from_root: "docs\HELPER_TO_CLASS_MAPPING_ANALYSIS.md"
  file_hash: "6ab670175d5e9ad97a1e96db668417b72fb4977d3c672ad2e6463bc05401d08d"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Helper-to-Class Mapping Plan (Analysis Only)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "helper_to_class_mapping_analysismd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Helper-to-Class Mapping Plan (Analysis Only)

**Purpose:** Map every procedural helper in `lupo-includes/functions/` to the correct OOP class based on **actual code behavior**. No refactoring in this step.

**Source:** Real function implementations read from the codebase. Role model: channel roles only (`lupo_channel_roles`, default `channel_id = 1`). Session model: `App\Auth\Session` only. No `actor_roles`, no `sessions`, no operator tables.

---

## A. Table of All Helper Functions and Recommended Class

| Helper Function | File | What It Actually Does | Recommended Class | Notes |
|-----------------|------|------------------------|-------------------|--------|
| **auth-helpers.php** | | | | |
| `lupo_get_actor_id_from_auth_user_id` | auth-helpers.php | SELECT actor_id from lupo_actors where actor_source_type='user' and actor_source_id=:id | **ActorService** (or AuthService) | Actor–auth_user linkage; no session. |
| `lupo_create_actor_for_auth_user` | auth-helpers.php | Generates slug from email, checks slug uniqueness, INSERT into lupo_actors | **ActorService** | Actor creation + slug generation; calls lupo_actor_slug_exists. |
| `lupo_actor_slug_exists` | auth-helpers.php | SELECT COUNT from lupo_actors where slug=:slug and is_deleted=0 | **ActorService** | Pure actor table check. |
| `current_user` | auth-helpers.php | Gets actor_id from Session->validateSession(); loads actor+auth_user from DB (JOIN); calls lupo_is_admin(actor_id); returns array | **AuthService** | Core identity lookup; duplicates logic that belongs in AuthService::getCurrentUser(). |
| `lupo_is_admin` | auth-helpers.php | 1) SELECT from lupo_channel_roles (channel_id=1, role_type IN captain/administrator) 2) Fallback: lupo_permissions owner on admin module | **AuthRoleResolver** | Already channel-scoped; no actor_roles. |
| `lupo_get_auth_user_id_from_actor_id` | auth-helpers.php | SELECT actor_source_id from lupo_actors where actor_id and actor_source_type='user' | **ActorService** or **AuthService** | Actor→auth_user resolution. |
| `require_login` | auth-helpers.php | Calls current_user(); if false, stores redirect in $_SESSION, then lupo_safe_redirect to login | **AuthService** | Gate; should be AuthService::requireLogin(). |
| `require_admin` | auth-helpers.php | Calls require_login(), then current_user(), then 403 if !is_admin | **AuthService** | Gate; should be AuthService::requireAdmin(). |
| **auth-ui-helpers.php** | | | | |
| `lupo_render_login_status` | auth-ui-helpers.php | Gets current_user(); builds HTML (avatar, dropdown, links); checks lupo_channel_roles for actor to set is_operator; outputs Crafty Syntax / admin links | **AuthService** (data) + **View/UI** (render) | Presentation; role check is channel_roles (correct). Could stay as thin helper calling AuthService + template. |
| `lupo_get_current_user_data` | auth-ui-helpers.php | current_user(); returns same array or null | **AuthService** | Duplicates AuthService::getCurrentUser() shape; thin wrapper. |
| `lupo_is_logged_in` | auth-ui-helpers.php | current_user() !== false | **AuthService** | Duplicates AuthService::isLoggedIn(). |
| `lupo_get_username` | auth-ui-helpers.php | current_user()['username'] or '' | **AuthService** | Accessor on current user. |
| `lupo_get_display_name` | auth-ui-helpers.php | current_user()['display_name'] ?? username or '' | **AuthService** | Accessor on current user. |
| **session-helpers.php** | | | | |
| (no functions) | session-helpers.php | File is a stub; comment says use App\Auth\Session | — | No helpers to map; file is deprecated placeholder. |
| **collection-zero-helpers.php** | | | | |
| `lupo_ensure_collection_zero` | collection-zero-helpers.php | SELECT/INSERT/UPDATE lupo_collections for collection_id=0; creates "Lupopedia Documentation" collection | **CollectionZeroService** | Domain: Collection Zero bootstrap. |
| `lupo_populate_collection_zero_tabs` | collection-zero-helpers.php | INSERT/UPDATE lupo_collection_tabs for collection_id=0 with fixed doc tab list | **CollectionZeroService** | Same domain. |
| `lupo_get_collection_zero_url` | collection-zero-helpers.php | Returns LUPOPEDIA_PUBLIC_PATH + '/collection/0/lupopedia' + optional tab_slug | **CollectionZeroService** or **Support/UrlUtils** | URL building for Collection 0. |
| `lupo_initialize_collection_zero` | collection-zero-helpers.php | Calls lupo_ensure_collection_zero() then lupo_populate_collection_zero_tabs() | **CollectionZeroService** | Orchestration. |
| **collection-tabs-loader.php** | | | | |
| `load_collection_tabs` | collection-tabs-loader.php | SELECT from lupo_collection_tabs (root + children by collection_tab_parent_id); formats for UI component | **CollectionZeroService** or **CollectionTabsService** | Collection tabs domain; uses $table_prefix. |
| `get_collection_name` | collection-tabs-loader.php | SELECT name from lupo_collections where collection_id=:id | **CollectionZeroService** or **CollectionService** | Generic collection lookup. |
| **redirect-helpers.php** | | | | |
| `lupo_safe_redirect` | redirect-helpers.php | Prepends LUPOPEDIA_PUBLIC_PATH to relative URLs; sanitizes URL; header(Location) or meta refresh + JS + link | **Support/RedirectUtils** or **Support/HttpUtils** | No auth/session; pure HTTP redirect. |
| **limits_logger.php** | | | | |
| `log_limits_violation` | limits_logger.php | Writes to storage/logs/lupopedia_limits.log with timestamp and context | **Support/LogUtils** or **System/LimitsEnforcementService** | Logging; service already exists. |
| `safe_check_version_bump` | limits_logger.php | Requires LimitsEnforcementService, calls checkVersionBump; on exception logs via log_limits_violation | **System/LimitsEnforcementService** | Duplicates / wraps service. |
| `safe_check_table_count` | limits_logger.php | Requires LimitsEnforcementService, calls checkTableCount; logs violation if not allowed | **System/LimitsEnforcementService** | Same. |
| `safe_check_weekend_mode` | limits_logger.php | Requires LimitsEnforcementService, calls isWeekendDay; logs if weekend | **System/LimitsEnforcementService** | Same. |
| **load_atoms.php** | | | | |
| `load_atoms` | load_atoms.php | Reads config/global_atoms.yaml; parses YAML (or regex); caches; returns atom(s) | **Support/AtomLoader** or **Config/GlobalAtoms** | Config/version domain. |
| `get_atom` | load_atoms.php | Returns load_atoms($atom_name) | Same as load_atoms | Thin wrapper. |
| `get_lupopedia_version` | load_atoms.php | get_atom('GLOBAL_CURRENT_LUPOPEDIA_VERSION') or atoms['version'] or fallback '3.0.0' | Same | Version from atoms. |
| `calculate_version_num` | load_atoms.php | Parses "M.m.p" to M*10000 + m*100 + p | **Support/VersionUtils** | Pure calculation. |
| `_parse_atoms_yaml` | load_atoms.php | file_get_contents + yaml_parse or regex; returns array | Internal to AtomLoader | Implementation detail. |
| `_parse_atoms_yaml_regex` | load_atoms.php | Regex parse of YAML content for base atoms | Internal to AtomLoader | Implementation detail. |
| `read_cosmic_microwave_background` | load_atoms.php | Merges global_atoms.yaml + GLOBAL_IMPORTANT_ATOMS.yaml; cached | Same as load_atoms | CMB = base atoms. |
| `get_base_atom` | load_atoms.php | read_cosmic_microwave_background() then dot-notation key lookup | Same | Nested atom access. |
| **identity-helpers.php** | | | | |
| `allocateAnonymousActorId` | identity-helpers.php | Finds first gap in actor_id 1000–9999 in lupo_actors | **ActorService** | Actor allocation; uses hardcoded "lupo_actors" (no prefix). |
| `getOrAllocateJsrnForActor` | identity-helpers.php | Reads/updates metadata_json $.jsrn in lupo_actors | **ActorService** | Actor metadata; hardcoded table name. |
| `mergeAnonymousActorIntoRealActor` | identity-helpers.php | Updates lupo_sessions, lupo_actor_events, etc. to point actor_id to real; merges metadata_json; marks temp deleted | **ActorService** | Actor merge; hardcoded table list. |
| **render-saved-collections.php** | | | | |
| `render_saved_collections` | render-saved-collections.php | Loads collections (with lupo_permissions / lupo_actor_group_membership); loads tabs and children; builds nested array | **CollectionService** or **SavedCollectionsService** | Uses c.id, lupo_collection_tabs; schema may use collection_id (verify TOONs). |
| `load_tab_children` | render-saved-collections.php | SELECT from lupo_collection_tab_map; recursive for type=tab; loads content/link | Same | Tab tree resolution. |
| `count_tab_items` | render-saved-collections.php | Recursive count from lupo_collection_tab_map (content+link, not tab nodes) | Same | Tab item count. |
| **upload-handler.php** | | | | |
| `lupo_get_upload_handler` | upload-handler.php | Returns singleton LupoUploadHandler instance | **Keep as helper** or **App/Services/UploadService** | Factory; class already exists in same file. |
| `lupo_upload_file` | upload-handler.php | lupo_get_upload_handler()->upload(...) | Same | Thin wrapper. |

---

## B. Helpers That Should Be Deleted

| Helper | Reason |
|--------|--------|
| **None** | No helper in the scanned files references `actor_roles`, `sessions`, or `livehelp_operator*` tables. No helper is marked "Obsolete — delete" solely from this analysis. |
| *(Future)* | Any helper discovered later that only references dropped tables should be deleted or rewritten per doctrine. |

---

## C. Helpers That Duplicate Existing Class Methods

| Helper | Duplicates / Overlaps With | Notes |
|--------|----------------------------|--------|
| `current_user` | **AuthService::getCurrentUser()** (if AuthService exists in codebase) | Same flow: validateSession → load actor+auth_user → isAdmin. |
| `lupo_is_logged_in` | **AuthService::isLoggedIn()** | Boolean of getCurrentUser() !== false. |
| `lupo_get_current_user_data` | **AuthService::getCurrentUser()** | Same data shape; returns null instead of false. |
| `lupo_get_username` | AuthService::getCurrentUser() then ['username'] | Accessor. |
| `lupo_get_display_name` | AuthService::getCurrentUser() then display_name/username | Accessor. |
| `lupo_is_admin` | **AuthRoleResolver::isAdmin()** (if present) | Same channel_roles + permissions fallback. |
| `require_login` | **AuthService::requireLogin()** (if present) | Same redirect + session store. |
| `require_admin` | **AuthService::requireAdmin()** (if present) | requireLogin + 403 if !is_admin. |
| Session logic | **Session::getSessionId()**, **validateSession()**, **getNameKey()**, **getSessionRow()** | session-helpers.php already has no functions; all session access is via Session. |
| `safe_check_version_bump`, `safe_check_table_count`, `safe_check_weekend_mode` | **LimitsEnforcementService** methods | Helpers only wrap the service and log violations. |

---

## D. Helpers That Require Rewriting (Dropped Tables / Schema)

| Helper | Issue | Action |
|--------|--------|--------|
| **None** | No helper references `actor_roles`, `sessions`, or operator tables. | — |
| `allocateAnonymousActorId`, `getOrAllocateJsrnForActor`, `mergeAnonymousActorIntoRealActor` | Use hardcoded `lupo_*` table names instead of `LUPO_TABLE_PREFIX`. | Rewrite to use prefix (or ActorService with injected table name). |
| `render_saved_collections`, `load_tab_children`, `count_tab_items` | Use `lupo_collections` with `c.id`, `lupo_collection_tabs`, `lupo_collection_tab_map`; also `lupo_actor_group_membership` with `actor_group_membership_id = :user_id` (likely should be actor_id or different column per TOONs). | Verify column/table names against TOONs and install SQL; rewrite if schema differs. |

---

## E. Domain Boundaries Inferred From Code

| Domain | What the Code Does | Recommended Class(es) |
|--------|--------------------|------------------------|
| **Auth / session identity** | Validate session, load current actor+auth_user, return "current user" array | AuthService |
| **Roles / permissions** | Check channel_roles (channel_id=1), permissions (owner on module) | AuthRoleResolver |
| **Context** | Path-based lupopedia vs crafty_syntax (no helpers in list; SessionHandler does it) | AuthContextResolver |
| **Session** | All session read/write is in Session + SessionHandler; no procedural session helpers left | Session |
| **Actor** | Actor creation, slug existence, actor_id↔auth_user_id, anonymous allocation, JSRN, merge | ActorService |
| **Collection Zero** | Ensure collection 0 exists, populate doc tabs, get Collection 0 URL, init | CollectionZeroService |
| **Collection tabs** | Load tabs (root + children) for a collection, get collection name | CollectionZeroService or CollectionTabsService |
| **Saved collections (render)** | Load collections/tabs/tab_map by permissions, build tree, count items | CollectionService / SavedCollectionsService |
| **Redirect** | Safe redirect with public path and headers-sent fallback | Support/RedirectUtils or HttpUtils |
| **Limits / version** | Log violations, call LimitsEnforcementService checks | System/LimitsEnforcementService (+ optional LogUtils) |
| **Atoms / config** | Load YAML atoms, version string, version number calculation, CMB | Support/AtomLoader or Config/GlobalAtoms; Support/VersionUtils |
| **Upload** | Get handler instance, upload file | Keep LupoUploadHandler; optional UploadService facade |

---

## Summary

- **Auth:** current_user, lupo_is_admin, require_login, require_admin, and auth-ui identity/accessors map to **AuthService** and **AuthRoleResolver**. No references to dropped tables.
- **Session:** No procedural session helpers; Session class is the single place for session logic.
- **Actor:** Actor–auth_user linkage, actor creation, slug existence, and identity-helpers (anonymous, JSRN, merge) map to **ActorService**; identity-helpers need table prefix fix.
- **Collection Zero / tabs / saved collections:** Map to **CollectionZeroService** (and optionally CollectionTabsService / CollectionService); render_saved_collections schema should be verified against TOONs.
- **Redirect, limits, atoms, upload:** Map to Support/System/Config or existing services as in the table; no auth/session/role tables.
- **No helpers** in the analyzed files reference **actor_roles**, **sessions**, or **livehelp_operator*** tables.