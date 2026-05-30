---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/22_A_WEB_NAVIGATION_ARCHITECTURE.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/22_A_WEB_NAVIGATION_ARCHITECTURE.md"
  status: active
  when_updated: "20260422232349"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/22_web_navigation_architecture.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/web-navigation-architecture
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_22_A
  title: "Web Navigation Architecture (4.0.69)"
  summary: null
---
# Web Navigation Architecture (4.0.69)

This spec describes how **collections and tabs** drive global and channel-specific web navigation: dropdown menus, sidebars, and tab-based resource views. Implementation is backend/data-layer; frontend templates consume the data.

---

## 1. Data sources

- **Collections** with `is_nav_menu = 1`: top-level navigation (header dropdowns).
- **Collections** with `channel_id = current_channel`: channel sidebar and channel resource bundle.
- **Tabs** within a collection: ordered list of categories; each tab has optional `visibility_rule`, `tab_type`, and entries from `lupo_collection_tab_map`.
- **Tab paths:** `lupo_collection_tab_paths` provides path and depth for canonical URLs and breadcrumbs.

---

## 2. Global navigation (top-level dropdowns)

- The Web UI header resolves **global nav collections** via `CollectionTabsService::getCollectionsForNavMenu()`.
- Each returned collection (collection_id, name, slug, nav_icon) is a top-level menu item.
- Tabs for that collection (from `loadCollectionTabs(collection_id)`) become the dropdown links.
- **Dropdown generation:** One menu item per collection; sub-items = tabs (name, slug). Use slug/path for href; nav_icon for icon if present.

---

## 3. Channel sidebar

- When the session is in a channel, resolve **channel collections** via `CollectionTabsService::getCollectionsForChannel($channelId)`.
- Render each collection in the sidebar; tabs within each collection can be expandable or linked.
- **Resource bundle:** A channel can expose one collection as its default "Resources" tabbed view; use the first channel collection or a designated default.

---

## 4. Tab activation and paths

- **Tab paths:** `lupo_collection_tab_paths` stores (collection_id, collection_tab_id, path, depth). Use this to build canonical URLs (e.g. `/collection/42/docs/architecture`).
- **Tab activation:** When the URL matches a path, activate the corresponding tab and load its entries (from `lupo_collection_tab_map`).
- **Breadcrumbs:** Derive from path/depth for the current collection and tab.

---

## 5. Item types and rendering

- **item_type** in `lupo_collection_tab_map` is one of: **artifact**, **content**, **url**, **path**.
  - **artifact:** Internal artifact; resolve via item_id to artifact storage; render as link to artifact viewer.
  - **content:** Internal content; resolve via item_id to `lupo_contents`; render as link to content slug/path.
  - **url:** External URL; item_id may be 0 or a link table id; URL and label can be in `properties` (JSON/text).
  - **path:** Internal navigation path (e.g. help topic, admin page); resolve via properties or item_id to route.
- **properties** on the map row can hold UI overrides: custom title, open-in-new-tab, icon.

---

## 6. Visibility and tab type

- **visibility_rule:** Optional; applied by UI or a small resolver (e.g. by actor traits or channel roles). If not implemented yet, all tabs are shown; when implemented, filter tabs by rule for the current actor/channel.
- **tab_type:** Hint for UI (list, gallery, breadcrumb-set). UI may render the tab????????s entries differently based on tab_type.

---

## 7. Backend support summary

| Behavior | Backend support |
|----------|-----------------|
| Top-level dropdowns | `getCollectionsForNavMenu()`; then `loadCollectionTabs(collection_id)` per collection. |
| Channel sidebar | `getCollectionsForChannel(channel_id)`; then load tabs per collection. |
| Tab paths / canonical URLs | Read `lupo_collection_tab_paths` by collection_id and optionally path. |
| Tab entries (artifact/content/url/path) | Read `lupo_collection_tab_map` by collection_tab_id; resolve item_type and item_id; use properties for overrides. |
| Visibility / tab type | Returned in tab data (_visibility_rule, _tab_type); UI or resolver applies. |

---

## 8. References

- Doctrine: `lupo-docs/doctrine/COLLECTIONS_DOCTRINE.md`
- Schema: `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- Service: `CollectionTabsService` (getCollectionsForNavMenu, getCollectionsForChannel, loadCollectionTabs).
