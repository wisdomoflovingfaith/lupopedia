---
lupopedia.init:
  document_type: "documentation"
  system_version: "4.0.71"

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/frontend/semantic_navbar.md"
  web_path: "http://www.lupopedia.com/frontend/semantic_navbar"
  system_version: "4.0.71"
  last_modified_utc: "20260312"
  channel_id: 42
  actor_id: 1003
  artifact_type: "documentation"
  artifact_kind: "frontend"
  purpose: "JS semantic floating navbar: API endpoints, SQL usage, data flow, icon→table mapping, external-site behavior."
  tags: ["semantic_navbar", "frontend", "api", "4.0.71"]

lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"

lupopedia.footer:
  version: "4.0.71"
  last_verified: "20260312"
  last_verified_by: "cursor"
---
# file: Semantic Navbar (frontend) — web_path: http://www.lupopedia.com/frontend/semantic_navbar

# Semantic Floating Navigation Bar — Frontend & API

This document describes API endpoints used by the semantic navbar, SQL queries used on the backend, data flow, how each navbar icon/section maps to DB tables, behavior on external sites, and how the JS block communicates with Lupopedia.

---

## 1. API Endpoints Used

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `{LUPOPEDIA_PUBLIC_PATH}/api/semantic_navbar` | GET | Single response containing previous, references, contexts, edges, hashtags, folders, qa, next. |
| Query params | `content_id` (bigint) or `slug` (string) or `entity_type` + `entity_id` | Identifies the current page. |

**Response shape (example):**
```json
{
  "previous": [ { "content_id", "title", "slug", "url" } ],
  "references": [ { "reference_id", "url", "title", "citation_text" } ],
  "contexts": [ { "collection_id", "name", "tabs": [ ... ] } ],
  "edges": [ { "edge_id", "edge_type", "target_object_type", "target_object_id", "title", "url" } ],
  "hashtags": [ { "hashtag_id", "tag_slug", "label" } ],
  "folders": [ { "folder_id", "name", "slug" } ],
  "qa": [ { "truth_id", "slug", "title", "answer_preview" } ],
  "next": [ { "content_id", "title", "slug", "url" } ]
}
```

---

## 2. SQL Queries Used (Backend)

Backend resolves `content_id` or `slug` to a canonical entity (e.g. content_id). Then:

| Section | Primary tables | Query pattern |
|---------|----------------|---------------|
| Previous | lupo_paths, lupo_visits | WHERE entercontentid = :id OR exitcontentid = :id; order by created_ymdhis. |
| References | lupo_references, lupo_reference_links | JOIN reference_links ON object_type='content' AND object_id=:id, JOIN references. |
| Contexts | lupo_collections, lupo_collection_tabs, lupo_collection_tab_map | JOIN collection_tab_map ON item_type='content' AND item_id=:id, then tabs and collections. |
| Edges | lupo_edges | WHERE (left_object_type, left_object_id) = ('content', :id) OR (right_object_type, right_object_id) = ('content', :id). |
| Hashtags | lupo_hashtags, lupo_hashtag_map | JOIN hashtag_map ON object_type='content' AND object_id=:id, JOIN hashtags. Or read lupo_contents.hashtags JSON. |
| Folders | lupo_folders, lupo_folder_map | JOIN folder_map ON object_type='content' AND object_id=:id, JOIN folders. |
| Q/A | lupo_truth_knowledge, lupo_truth_answers | By object_type/object_id or slug linked to content. |
| Next | lupo_paths, lupo_edges | Same as previous (inverse direction); or edges where this page is left, target is right. |

All queries MUST filter `is_deleted = 0` where the table has that column. Timestamps are BIGINT YYYYMMDDHHIISS UTC.

---

## 3. Data Flow Diagram

```
┌─────────────────────────────────────────────────────────────────────────┐
│  Browser: Page with embedded semantic navbar JS                          │
│  - Reads data-nav-content-id or data-nav-slug (or config)                │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│  GET /api/semantic_navbar?content_id=123                                 │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│  Server: Resolve content_id/slug → entity_type + entity_id                │
│  Query: lupo_paths, lupo_references, lupo_reference_links,               │
│         lupo_collections, lupo_collection_tabs, lupo_collection_tab_map, │
│         lupo_collection_tab_paths, lupo_edges, lupo_hashtags,            │
│         lupo_hashtag_map, lupo_folders, lupo_folder_map,                 │
│         lupo_truth_knowledge, lupo_truth_answers                         │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│  Response: JSON { previous, references, contexts, edges, hashtags,       │
│                   folders, qa, next }                                    │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│  Browser: Render floating bar; each icon/section maps to one of the      │
│           keys above (see §4).                                           │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 4. How Each Icon Maps to DB Tables

| Navbar icon / section | Data key | Tables used |
|------------------------|----------|-------------|
| Previous pages | `previous` | lupo_paths, lupo_visits |
| References | `references` | lupo_references, lupo_reference_links |
| Contexts (collections) | `contexts` | lupo_collections, lupo_collection_tabs, lupo_collection_tab_map, lupo_collection_tab_paths |
| Edges | `edges` | lupo_edges, lupo_edge_type_definitions |
| Hashtags | `hashtags` | lupo_hashtags, lupo_hashtag_map (or lupo_contents.hashtags) |
| Folders | `folders` | lupo_folders, lupo_folder_map |
| Q/A | `qa` | lupo_truth_knowledge, lupo_truth_answers |
| Next pages | `next` | lupo_paths, lupo_edges |

---

## 5. External Sites (e.g. mywebsite.com/page.htm)

When the navbar JS is embedded on an **external site** (different origin):

- The JS cannot know the current page’s Lupopedia content_id unless the embedding page provides it (e.g. data attribute or config).
- **Required:** The external page (or a server-side include) must pass a **Lupopedia entity identifier** to the navbar (e.g. `content_id` or a stable `slug` that Lupopedia’s API can resolve). Options:
  - Embedder sets `data-nav-content-id="123"` or `data-nav-slug="my-page"` on the script container or a root element.
  - Or the embedder calls a Lupopedia endpoint that maps external URL → content_id (e.g. via lupo_contents.federation_source_url or a mapping table), then passes that content_id to the navbar.
- **CORS:** The Lupopedia API must allow the external origin in CORS headers if the browser calls the API from the external domain.
- **Communication:** Same as internal — JS sends GET to Lupopedia API with the resolved or provided content_id/slug; API returns the same JSON; JS renders the bar.

---

## 6. How the JS Block Communicates with Lupopedia

- **No direct DB access:** The navbar JS runs in the browser and never touches the database.
- **HTTP only:** All data is fetched via `GET {LUPOPEDIA_PUBLIC_PATH}/api/semantic_navbar?content_id=...` (or slug/entity params).
- **Single request:** One request returns all sections (previous, references, contexts, edges, hashtags, folders, qa, next) to minimize round-trips.
- **Auth:** If the API requires auth (e.g. session cookie or token), the embedding page must be served in a context where that cookie/token is sent (same-origin or CORS with credentials). Public content may allow anonymous GET.
- **Caching:** Client or CDN may cache the response per content_id according to cache headers returned by the API.

---

## 7. Related Documentation

- **Table audit and overview:** `lupo-docs/database/lupopedia/tables/semantic_navbar/SEMANTIC_NAVBAR_OVERVIEW.md`, `SEMANTIC_NAVBAR_TABLE_AUDIT_REPORT.md`
- **Per-table docs:** Same directory for lupo_references, lupo_reference_links, lupo_hashtags, lupo_hashtag_map, lupo_folders, lupo_folder_map; `lupo-docs/database/lupopedia/tables/active/` for lupo_paths, lupo_edges, lupo_collections, etc.
