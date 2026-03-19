# Collections Doctrine (4.0.69)

Collections are **channel-scoped resource bundles** that group artifacts, internal content, external URLs, and navigation paths for use in Web UI (menus, sidebars, tabbed views). This doctrine defines the Collection → Tab → Entry model and its relation to channels, federation, and navigation.

**Critical distinction:** Collections drive navigation, tabs, URLs, and breadcrumbs through `lupo_collection_tab_paths` but **do not define filesystem directory layout**. File paths are determined by directory doctrine and `file_path_from_root`, not by collection slugs.

|--------|--------|
| **Collection** | A resource bundle: a top-level container with optional channel scope and navigation flags. Stored in `lupo_collections`. |
| **Tab** | A named, grouped view within a collection (e.g. "Specs", "Code", "Links"). Stored in `lupo_collection_tabs`. |
| **Collection entry** | A single item in a tab: artifact, content, URL, or path. Stored in `lupo_collection_tab_map` with `item_type` and `item_id` (or equivalent for URL/path). |
| **Actor access** | Which actors can see or manage a collection. Stored in `lupo_actor_collections` (access_level). |

---

## 2. Tables (canonical)

- **`lupo_collections`** — collection_id, federation_node_id, actor_id, department_id, name, slug, channel_id, is_nav_menu, nav_icon, sort_order, properties, parent_id, timestamps, is_deleted.  
  - `channel_id`: when set, the collection is scoped to that channel (channel-sidebar / resource bundle).  
  - `is_nav_menu`: when 1, the collection is a top-level navigation menu (dropdown).  
  - `nav_icon`: optional UI icon identifier.

- **`lupo_collection_tabs`** — collection_tab_id, collection_id, actor_id, name, slug, sort_order, is_hidden, visibility_rule, tab_type, timestamps.  
  - `actor_id`: identity that owns or is associated with the tab (Actor–Faucet ontology; was user_id).  
  - `visibility_rule`: optional text/JSON for role- or trait-based visibility.  
  - `tab_type`: e.g. list, gallery, breadcrumb-set.

- **`lupo_collection_tab_map`** — collection_tab_map_id, collection_tab_id, item_type, item_id, sort_order, properties, timestamps.  
  - **item_type** (formalized): `artifact` | `content` | `url` | `path`.  
  - `properties`: optional JSON/text for UI overrides (e.g. custom label, open-in-new-tab for URL).

- **`lupo_collection_tab_paths`** — path, depth per (collection_id, collection_tab_id) for canonical URLs and breadcrumbs.

- **`lupo_actor_collections`** — actor_id, collection_id, access_level (permission layer).

---

## 3. Collection vs tab vs entry

- **Collection** = the bundle (e.g. "Channel 42 Resources"). It can be global (no channel_id, is_nav_menu=1) or channel-scoped (channel_id set).
- **Tab** = a category inside the collection (e.g. "Docs", "Links"). Tabs have optional visibility_rule and tab_type.
- **Entry** = one row in `lupo_collection_tab_map`: one artifact, content, url, or path in that tab.

---

## 4. Channel-local and navigation usage

- **Channel-scoped:** Collections with `channel_id = current_channel` are used for channel sidebars and channel resource bundles.
- **Global nav:** Collections with `is_nav_menu = 1` are used as top-level menu providers; their tabs become dropdown entries.
- **Resolution:** Use `CollectionTabsService::getCollectionsForNavMenu()` for global nav and `CollectionTabsService::getCollectionsForChannel($channelId)` for channel collections. Tab ordering and visibility_rule/tab_type are returned with tab data for UI to apply.

---

## 5. Relation to channels, federation, contents

- Collections belong to a **federation_node_id** and optionally a **channel_id** and **department_id**.
- **lupo_contents** can reference a **default_collection_id**; content can be placed in a channel via **channel_id**. Collection scope (channel_id) should align with where the content is used.
- No foreign keys; all relationships are enforced in application code. Timestamps are BIGINT UTC YmdHis set in PHP.

---

## 6. Metadata (optional UI)

- **lupo_metadata** with `entity_type = 'collection'` or `entity_type = 'collection_tab'` can store supplemental UI data (labels, CSS hooks, icons, display overrides). Core structure stays in the tables above; metadata is for decoration only.

---

## 7. References

- Schema: `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- TOONs: `lupo-database/lupopedia/toon/lupo_collections.toon`, `lupo_collection_tabs.toon`, `lupo_collection_tab_map.toon`, `lupo_collection_tab_paths.toon`
- Web navigation: `lupo-docs/specs/WEB_NAVIGATION_ARCHITECTURE.md`
- Orchestration: `lupo-docs/architecture/HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md`

---

## 8. Precedence When Used with Namespace

When collections and namespaces are both present on an artifact, precedence is determined by scope:

| Decision Type | Winner | Rule |
|---------------|--------|------|
| **Policy / validation / jurisdiction** | **Namespace** | Domain/jurisdiction (namespace) determines policy and validation (e.g. table-doc requirement, governance). Collection determines nav/filter/display grouping. |
| **Navigation / tabs / UI grouping** | **Collections** | Collections drive navigation menus, tabs, URLs, and breadcrumbs through database structure. Namespace does not override nav structure. |
| **File path / filesystem location** | **Filesystem** | File location is determined by directory doctrine and `file_path_from_root`. Collection membership does not override or define where a file lives on disk. |
| **Header vs DB for collections** | **Context-dependent** | For ingestion and file-authored truth: header `collections` is source of truth. For navigation menu and tab contents at runtime: DB (lupo_collections, tabs) is source of truth. |

**Summary:** Collections organize navigation and display; namespace enforces policy and taxonomy; filesystem paths follow directory doctrine.
