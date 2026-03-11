# Cursor Collections, Tabs, and Navigation Implementation (4.0.69)

Implementation of the collections/tabs/navigation expansion recommended by Antigravity: collections as **channel-scoped resource bundles** with global nav and channel-sidebar support. Install-first rule followed; migration, service, TOONs, and docs updated.

---

## 1. Files reviewed

- **Source:** `lupo-docs/status/ANTIGRAVITY_COLLECTIONS_TABS_NAVIGATION_REVIEW_4.0.69.md`, `lupo-channels/42/broadcasts/20260312000000_antigravity_wolfie_collections_tabs_navigation_research.md`
- **Schema:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (lupo_collections, lupo_collection_tabs, lupo_collection_tab_map, lupo_collection_tab_paths)
- **TOONs:** lupo_collections.toon, lupo_collection_tabs.toon, lupo_collection_tab_map.toon, lupo_collection_tab_paths.toon
- **PHP:** `lupo-database/lupopedia/content/lupo-app/Services/CollectionTabsService.php`, CollectionZeroService.php, SavedCollectionsService.php, `lupo-includes/functions/collection-tabs-loader.php`
- **Architecture:** `lupo-docs/architecture/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md`, `lupo-docs/architecture/cursor_actors_channels_semantic_architecture_4.0.69.md`

---

## 2. Schema changes made

### install_new_lupopedia.sql

- **lupo_collections:** Added `channel_id` bigint DEFAULT NULL, `is_nav_menu` tinyint NOT NULL DEFAULT 0, `nav_icon` varchar(64) DEFAULT NULL. Added indexes: `lupo_collections_idx_channel_id`, `lupo_collections_idx_is_nav_menu`.
- **lupo_collection_tabs:** Renamed `user_id` to `actor_id` (bigint DEFAULT NULL). Added `visibility_rule` text DEFAULT NULL, `tab_type` varchar(32) DEFAULT NULL. Added index `lupo_collection_tabs_idx_actor_id`.

### lupo_collection_tab_map

- No schema change. Formalized **item_type** in doctrine and spec: `artifact`, `content`, `url`, `path`. Table already has `properties` for UI overrides.

---

## 3. Migration

- **Created:** `database/migrations/20260312_collections_tabs_navigation_4_0_69.sql`
  - ALTER lupo_collections: ADD channel_id, is_nav_menu, nav_icon; CREATE INDEX for channel_id and is_nav_menu.
  - ALTER lupo_collection_tabs: CHANGE user_id → actor_id; ADD visibility_rule, tab_type; CREATE INDEX actor_id.
  - INSERT into lupo_schema_migrations (schema_migration_id 20260312002, version 20260312, name 20260312_collections_tabs_navigation_4_0_69).

---

## 4. Runtime / service changes

- **CollectionTabsService** (lupo-database/lupopedia/content/lupo-app/Services/CollectionTabsService.php):
  - **getCollectionsForNavMenu()** — Returns collections where `is_nav_menu = 1` (collection_id, name, slug, nav_icon), ordered by sort_order, name. For top-level dropdowns.
  - **getCollectionsForChannel($channelId)** — Returns collections where `channel_id = $channelId` (same columns). For channel sidebar and resource bundle.
  - **loadCollectionTabs()** — SELECT extended to include `visibility_rule`, `tab_type`; response includes `_visibility_rule` and `_tab_type` when set for UI/resolver use.
  - PHP 5.3 compatibility: use of `array()` instead of short arrays in new/updated code.

- **CollectionZeroService:** Inserts into collection_tabs do not set user_id/actor_id; no change required. Existing inserts remain valid (actor_id DEFAULT NULL).

- **SavedCollectionsService:** Uses collection_tabs for tab list; no reference to user_id column name in SELECT. No change required.

- No new `lupo_web_links` table; URL support via `item_type = 'url'` in lupo_collection_tab_map and optional properties.

---

## 5. TOON updates

- **lupo_collections.toon:** Added fields channel_id, is_nav_menu, nav_icon; added indexes idx_channel_id, idx_is_nav_menu.
- **lupo_collection_tabs.toon:** Replaced user_id with actor_id; added visibility_rule, tab_type; added index idx_actor_id.
- **lupo_collection_tab_map.toon**, **lupo_collection_tab_paths.toon:** No schema change; item_type values documented in doctrine/spec only.

---

## 6. Documentation created or updated

- **Created:** `lupo-docs/doctrine/COLLECTIONS_DOCTRINE.md` — Collection = resource bundle; Tab = grouped view; Entry = artifact/content/url/path; channel_id, is_nav_menu, nav_icon; actor_id, visibility_rule, tab_type; item_type formalized; relation to channels, federation, contents; optional lupo_metadata for UI.
- **Created:** `lupo-docs/specs/WEB_NAVIGATION_ARCHITECTURE.md` — Global nav (getCollectionsForNavMenu), channel sidebar (getCollectionsForChannel), tab paths and canonical URLs, item types and rendering, visibility_rule and tab_type, backend support summary.
- **Updated:** `lupo-docs/architecture/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md` — New §12 "Resource bundles (collections)" describing channel-scoped bundles, is_nav_menu, nav_icon, and resolution; Collection row in summary table; references to COLLECTIONS_DOCTRINE and WEB_NAVIGATION_ARCHITECTURE.

---

## 7. UI-facing behaviors now supported (backend)

- **Top-level dropdowns:** Backend can return collections with `is_nav_menu = 1` via `getCollectionsForNavMenu()`; tabs per collection via `loadCollectionTabs(collection_id)`.
- **Channel sidebar:** Backend can return channel collections via `getCollectionsForChannel($channelId)`; tabs and entries resolvable from existing tables.
- **Tab paths:** `lupo_collection_tab_paths` remains the basis for canonical tab-aware URLs and breadcrumbs; no backend API change.
- **Item types:** artifact, content, url, path formalized; map properties available for UI overrides. No new table; URL stored via item_type and properties as needed.
- **Visibility / tab type:** Tab payload includes _visibility_rule and _tab_type when set; UI or a future resolver can filter/display accordingly.

---

## 8. Remaining follow-up (optional)

- **Visibility resolver:** Implement optional resolution of visibility_rule by actor traits or channel roles so tabs can be hidden per actor/context.
- **URL entries:** If external URLs need structured storage (e.g. title, icon, open-in-new-tab) beyond properties JSON, consider a small link table later; current spec uses item_type=url and properties.
- **Frontend:** Wire header/sidebar to `getCollectionsForNavMenu()` and `getCollectionsForChannel()` and render dropdowns/sidebar from returned collections and tabs.
- **lupo_metadata:** Use entity_type = 'collection' / 'collection_tab' for optional UI metadata (icons, CSS, labels) where helpful.

---

*Implementation: Wolfie (actor_id 1) via Cursor faucet. 4.0.69 collections/tabs/navigation expansion; install-first; doctrine and specs added.*
