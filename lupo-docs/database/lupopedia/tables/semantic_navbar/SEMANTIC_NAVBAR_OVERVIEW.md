---
lupopedia.init:
  document_type: "documentation"
  system_version: "4.0.71"

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/semantic_navbar/SEMANTIC_NAVBAR_OVERVIEW.md"
  web_path: "http://www.lupopedia.com/database/lupopedia/tables/semantic_navbar"
  system_version: "4.0.71"
  last_modified_utc: "20260312"
  channel_id: 42
  actor_id: 1003
  artifact_type: "documentation"
  artifact_kind: "overview"
  purpose: "Overview of the semantic floating navigation bar and how it maps to DB tables, API, and JS."
  tags: ["semantic_navbar", "frontend", "database", "4.0.71"]

lupopedia.footer:
  version: "4.0.71"
  last_verified: "20260312"
  last_verified_by: "cursor"
---
# file: Semantic Navbar Overview — web_path: http://www.lupopedia.com/database/lupopedia/tables/semantic_navbar

# Semantic Navbar — Overview

The Lupopedia **semantic floating navigation bar** provides previous pages, references, contexts (collections), edges, hashtags, folders, Q/A, and next pages. This document describes what the navbar does, how each feature maps to DB tables, how the JS block retrieves data, and how pages are resolved.

---

## 1. What the Navbar Does

- **Previous Pages:** Shows pages the user came from (path tracking).
- **References:** Citations or source links attached to the current page.
- **Contexts (Collections):** Collections and tabs the current page belongs to.
- **Edges:** Semantic relationships (links) to other pages/artifacts.
- **Hashtags:** Tags associated with the current page.
- **Folders:** Folder-based grouping for the current page.
- **Q/A:** Related questions and answers (truth knowledge).
- **Next Pages:** Suggested or linked next pages (paths + edges).

---

## 2. Feature → Table Mapping

| Navbar feature   | Primary tables | Supporting / junction |
|------------------|----------------|------------------------|
| Previous Pages   | lupo_paths, lupo_visits | — |
| References       | lupo_references | lupo_reference_links |
| Contexts         | lupo_collections, lupo_collection_tabs | lupo_collection_tab_map, lupo_collection_tab_paths |
| Edges            | lupo_edges | lupo_edge_type_definitions |
| Hashtags         | lupo_hashtags | lupo_hashtag_map; lupo_contents.hashtags (JSON) |
| Folders          | lupo_folders | lupo_folder_map |
| Q/A              | lupo_truth_knowledge, lupo_truth_answers | — |
| Next Pages       | lupo_paths, lupo_edges | — |

---

## 3. How the JS Block Retrieves Data

- The navbar JS runs in the browser and calls **Lupopedia API endpoints** (e.g. under `LUPOPEDIA_PUBLIC_PATH` or a configured API base).
- Endpoints receive the **current page context** (e.g. content_id, slug, or entity_type + entity_id) and return JSON for previous/next, references, collections, edges, hashtags, folders, and Q/A.
- Backend PHP (or equivalent) runs **SQL against the tables above** and returns structured JSON. No direct DB access from JS.

**Example flow:**

1. Page loads; JS reads current page ID/slug from data attributes or config.
2. JS requests e.g. `GET /api/semantic_navbar?content_id=123` or `?slug=my-page`.
3. Server resolves content_id/slug to entity_type + entity_id, then:
   - **Previous:** SELECT from lupo_paths / lupo_visits where exitcontentid or entercontentid = content_id.
   - **References:** SELECT from lupo_references JOIN lupo_reference_links WHERE object_type = 'content' AND object_id = content_id.
   - **Contexts:** SELECT from lupo_collection_tab_map JOIN lupo_collection_tabs JOIN lupo_collections WHERE item_type = 'content' AND item_id = content_id.
   - **Edges:** SELECT from lupo_edges WHERE (left_object_type, left_object_id) or (right_object_type, right_object_id) = ('content', content_id).
   - **Hashtags:** SELECT from lupo_hashtag_map JOIN lupo_hashtags WHERE object_type = 'content' AND object_id = content_id; or read lupo_contents.hashtags JSON.
   - **Folders:** SELECT from lupo_folder_map JOIN lupo_folders WHERE object_type = 'content' AND object_id = content_id.
   - **Q/A:** SELECT from lupo_truth_knowledge / lupo_truth_answers linked to content or slug.
   - **Next:** Same as previous but inverse direction; or edges where this page is left, next is right.
4. JS renders each section (previous, references, contexts, edges, hashtags, folders, Q/A, next) in the floating bar.

---

## 4. Page Resolution

- **Internal pages:** Identified by content_id (lupo_contents.content_id) or by slug. Backend resolves slug → content_id (or artifact_id) and uses that for all table lookups.
- **External sites (e.g. mywebsite.com/page.htm):** If the navbar is embedded on an external site, the JS must pass a **resolved Lupopedia entity** (e.g. content_id or a stable URL that the API can resolve). The API may use federation_node_id + path or a mapping table to resolve external URL → content_id. All table lookups then use that content_id/entity.

---

## 5. Data Flow (high level)

```
[Browser] → Navbar JS → HTTP GET /api/semantic_navbar?content_id=X
                              ↓
[Server]  → Resolve content_id → Query lupo_paths, lupo_references, lupo_reference_links,
             lupo_collections, lupo_collection_tabs, lupo_collection_tab_map,
             lupo_collection_tab_paths, lupo_edges, lupo_hashtags, lupo_hashtag_map,
             lupo_folders, lupo_folder_map, lupo_truth_knowledge, lupo_truth_answers
                              ↓
[Server]  → Return JSON { previous, references, contexts, edges, hashtags, folders, qa, next }
                              ↓
[Browser] → Render floating bar sections
```

---

## 6. Per-Table Documentation

- **Existing tables:** See `lupo-docs/database/lupopedia/tables/active/` for lupo_paths, lupo_edges, lupo_collections, lupo_collection_tabs, lupo_collection_tab_map, lupo_collection_tab_paths, lupo_contents, lupo_truth_knowledge, lupo_truth_answers, lupo_visits.
- **New tables (4.0.71):** See in this directory: lupo_references.md, lupo_reference_links.md, lupo_hashtags.md, lupo_hashtag_map.md, lupo_folders.md, lupo_folder_map.md.

See also: **SEMANTIC_NAVBAR_TABLE_AUDIT_REPORT.md** and **lupo-docs/frontend/semantic_navbar.md** (API endpoints, SQL usage, data flow diagram, external-site behavior).
