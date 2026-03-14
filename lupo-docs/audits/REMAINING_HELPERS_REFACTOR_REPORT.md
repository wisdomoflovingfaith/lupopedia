# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\REMAINING_HELPERS_REFACTOR_REPORT.md"
  file_hash: "80b0b32198271dca78289e34b4d692dd48ca25e9aba9a39a4677cce2b4a7e224"
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
  file_path_from_root: "lupo-docs\REMAINING_HELPERS_REFACTOR_REPORT.md"
  file_hash: "6ed5d887bcb908df893135b6a4d1df71a8ea0fc771c0f2895f015f30c400a215"
  file_path_from_root: "lupo-docs\REMAINING_HELPERS_REFACTOR_REPORT.md"
  file_hash: "8c567ce09895e9e598894838d0bb7867fdab1dffda85bbfd376bdc8134371275"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Remaining Helpers Refactor Report"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "remaining_helpers_refactor_reportmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Remaining Helpers Refactor Report

**Scope:** Collection Zero, Collection Tabs, Saved Collections, Redirect, Limits, Atoms/Version, Upload (per `lupo-docs/HELPER_TO_CLASS_MAPPING_ANALYSIS.md`).  
**Date:** 2026-02-10.

Auth and Actor domains were **not** modified.

---

## 1. Files changed

### New classes

| File | Purpose |
|------|--------|
| `app/Services/CollectionZeroService.php` | Ensure collection 0, populate tabs, get URL, initialize. PDO_DB + LUPO_TABLE_PREFIX. |
| `app/Services/CollectionTabsService.php` | loadCollectionTabs, getCollectionName. PDO_DB + LUPO_TABLE_PREFIX. |
| `app/Services/SavedCollectionsService.php` | renderSavedCollections, loadTabChildren, countTabItems. TOON column names (collection_id, collection_tab_id, name, lupo_contents). |
| `app/Services/UploadService.php` | Wraps LupoUploadHandler; getHandler(), upload(). |
| `app/Support/RedirectUtils.php` | safeRedirect (static). LUPOPEDIA_PUBLIC_PATH, sanitize, header or meta refresh. |
| `app/Support/AtomLoader.php` | loadAtoms, getAtom, getLupopediaVersion, readCosmicMicrowaveBackground, getBaseAtom, parseAtomsYaml, parseAtomsYamlRegex. |
| `app/Support/VersionUtils.php` | calculateVersionNum (static). M.m.p → integer. |
| `app/Support/LimitsLogger.php` | logViolation (static). Writes to storage/logs/lupopedia_limits.log. |

### Bootstrap

| File | Change |
|------|--------|
| `lupo-includes/bootstrap.php` | Load and register CollectionZeroService, CollectionTabsService, SavedCollectionsService, UploadService, AtomLoader, VersionUtils, RedirectUtils, LimitsLogger. Set `$GLOBALS['lupo_collection_zero_service']`, `lupo_collection_tabs_service`, `lupo_saved_collections_service`, `lupo_upload_service`, `lupo_atom_loader`. |

### Helpers replaced with thin wrappers

| File | Helpers |
|------|--------|
| `lupo-includes/functions/collection-zero-helpers.php` | lupo_ensure_collection_zero, lupo_populate_collection_zero_tabs, lupo_get_collection_zero_url, lupo_initialize_collection_zero |
| `lupo-includes/functions/collection-tabs-loader.php` | load_collection_tabs, get_collection_name |
| `lupo-includes/functions/render-saved-collections.php` | render_saved_collections, load_tab_children, count_tab_items |
| `lupo-includes/functions/redirect-helpers.php` | lupo_safe_redirect |
| `lupo-includes/functions/limits_logger.php` | log_limits_violation, safe_check_version_bump, safe_check_table_count, safe_check_weekend_mode |
| `lupo-includes/functions/load_atoms.php` | load_atoms, get_atom, get_lupopedia_version, calculate_version_num, _parse_atoms_yaml, _parse_atoms_yaml_regex, read_cosmic_microwave_background, get_base_atom |
| `lupo-includes/functions/upload-handler.php` | lupo_get_upload_handler, lupo_upload_file |

---

## 2. Helpers migrated per domain

### A. CollectionZeroService

| Helper | Method |
|--------|--------|
| lupo_ensure_collection_zero | ensureCollectionZero() |
| lupo_populate_collection_zero_tabs | populateCollectionZeroTabs() |
| lupo_get_collection_zero_url | getCollectionZeroUrl($tabSlug) |
| lupo_initialize_collection_zero | initializeCollectionZero() |

### B. CollectionTabsService

| Helper | Method |
|--------|--------|
| load_collection_tabs | loadCollectionTabs($collectionId) |
| get_collection_name | getCollectionName($collectionId) |

### C. SavedCollectionsService

| Helper | Method |
|--------|--------|
| render_saved_collections | renderSavedCollections($userId) |
| load_tab_children | loadTabChildren($tabId) |
| count_tab_items | countTabItems($tabId) |

Schema aligned with TOONs: `collection_id`, `collection_tab_id`, `name`, `slug`, `lupo_contents` (content_id, title), `lupo_collection_tab_map` (collection_tab_map_id, collection_tab_id, item_type, item_id). No `c.id` or `c.type`; use `collection_id` and `slug` as type key.

### D. RedirectUtils

| Helper | Method |
|--------|--------|
| lupo_safe_redirect | RedirectUtils::safeRedirect($url, $delay, $message) |

### E. Limits (wrappers)

| Helper | Behavior |
|--------|----------|
| log_limits_violation | LimitsLogger::logViolation() or inline file write |
| safe_check_version_bump | LimitsEnforcementService::checkVersionBump() + log on !allowed |
| safe_check_table_count | LimitsEnforcementService::checkTableCount() + log on !allowed |
| safe_check_weekend_mode | LimitsEnforcementService::isWeekendDay() + log on weekend |

LimitsEnforcementService is loaded via require_once when needed; DB from `$GLOBALS['mydatabase']`.

### F. AtomLoader / VersionUtils

| Helper | Method |
|--------|--------|
| load_atoms | AtomLoader::loadAtoms($atomName) |
| get_atom | AtomLoader::getAtom($atomName) |
| get_lupopedia_version | AtomLoader::getLupopediaVersion() |
| calculate_version_num | VersionUtils::calculateVersionNum($version) |
| _parse_atoms_yaml | AtomLoader::parseAtomsYaml($path) |
| _parse_atoms_yaml_regex | AtomLoader::parseAtomsYamlRegex($content) |
| read_cosmic_microwave_background | AtomLoader::readCosmicMicrowaveBackground() |
| get_base_atom | AtomLoader::getBaseAtom($key) |

### G. UploadService

| Helper | Method |
|--------|--------|
| lupo_get_upload_handler | UploadService::getHandler() or new LupoUploadHandler() |
| lupo_upload_file | UploadService::upload() or handler->upload() |

---

## 3. References updated

- All call sites continue to use the **same helper names**; helpers delegate to the new classes when the corresponding global service is set.
- No call sites were changed to call the new classes directly; optional follow-up is to use `$GLOBALS['lupo_*_service']` / `$GLOBALS['lupo_atom_loader']` where desired.

---

## 4. Helpers removed or wrapped

- **Removed:** Full procedural implementations for all listed helpers.
- **Wrapped:** Each helper is a thin wrapper that calls the appropriate service/static method when available, with a minimal fallback where needed (e.g. redirect when RedirectUtils not loaded, version '3.0.0' when AtomLoader not loaded).

---

## 5. Confirmations

- **Only allowed domains modified:** Only Collection Zero, Collection Tabs, Saved Collections, Redirect, Limits, Atoms, Upload. AuthService, AuthRoleResolver, AuthContextResolver, ActorService, Session, SessionHandler, Crafty Syntax, and other domains were **not** modified.
- **Logic in correct classes:** Collection Zero → CollectionZeroService; Collection Tabs → CollectionTabsService; Saved Collections → SavedCollectionsService; Redirect → RedirectUtils; Limits logging → LimitsLogger and LimitsEnforcementService; Atoms/version → AtomLoader and VersionUtils; Upload → UploadService.
- **DB access uses PDO_DB:** CollectionZeroService, CollectionTabsService, SavedCollectionsService use `$this->db` (PDO_DB from bootstrap) and `fetchRow`, `fetchAll`, `query`, `insert` with named parameters only.
- **Table names use LUPO_TABLE_PREFIX:** All new service code uses `$this->prefix` (LUPO_TABLE_PREFIX) or `quoteIdentifier($this->prefix . 'table_name')`. No hardcoded `lupo_*` in new code.
- **Schema mismatches corrected:** SavedCollectionsService uses TOON/install column names: `collection_id` (not `c.id`), `name` (not `tab_name`), `collection_tab_id`, `collection_tab_map_id`, `lupo_contents` with `content_id` and `title`. Permissions query uses `target_id = collection_id` and `user_id`.

---

## 6. How to use the new services

```php
// Collection Zero
$s = $GLOBALS['lupo_collection_zero_service'] ?? null;
$s->ensureCollectionZero();
$s->populateCollectionZeroTabs();
$url = $s->getCollectionZeroUrl($tabSlug);
$s->initializeCollectionZero();

// Collection Tabs
$tabs = $GLOBALS['lupo_collection_tabs_service']->loadCollectionTabs($collectionId);
$name = $GLOBALS['lupo_collection_tabs_service']->getCollectionName($collectionId);

// Saved Collections
$data = $GLOBALS['lupo_saved_collections_service']->renderSavedCollections($userId);

// Redirect
\App\Support\RedirectUtils::safeRedirect($url, $delay, $message);

// Limits
\App\Support\LimitsLogger::logViolation($type, $message, $context);

// Atoms / version
$loader = $GLOBALS['lupo_atom_loader'] ?? null;
$atoms = $loader->loadAtoms();
$ver = $loader->getLupopediaVersion();
$num = \App\Support\VersionUtils::calculateVersionNum($version);

// Upload
$handler = $GLOBALS['lupo_upload_service']->getHandler();
$GLOBALS['lupo_upload_service']->upload($file, $entityType, $entityId, $fileType);
```

Existing helper calls (e.g. `lupo_safe_redirect()`, `load_atoms()`, `render_saved_collections()`) continue to work via the thin wrappers.
