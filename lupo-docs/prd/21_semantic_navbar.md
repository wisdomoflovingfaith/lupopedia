---
lupopedia.init:
  document_type: "documentation"
  system_version: "4.0.71"

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/prd/21_semantic_navbar.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/21_semantic_navbar.md"
  system_version: "4.0.71"
  last_modified_utc: "20260405103800"
  channel_id: 42
  actor_id: 102
  artifact_type: "documentation"
  artifact_kind: "frontend"
  purpose: "JS semantic floating navbar: API endpoints, SQL usage, data flow, icon→table mapping, external-site allowlist (federation + trust), admin web UI for embedder setup, discovery of unapproved origins."
  tags: ["semantic_navbar", "frontend", "api", "4.0.71", "federation", "embed"]

lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Constitutional anchor"
    - to: "lupo-docs/prd/11_analytics_tracking.md"
      type: references
      weight: 0.95
      reason: "Cross-origin identity continuity; visitor embed fingerprint (embed_vid, sessions metadata)"
    - to: "lupo-docs/prd/34_federation_node_semantic_network.md"
      type: references
      weight: 0.95
      reason: "Federation nodes, trust, semantic network scope; complements embed allowlist and discovery"
    - to: "lupo-docs/doctrine/SILENT_HARVEST_DOCTRINE.md"
      type: references
      weight: 0.9
      reason: "Ethics and disclosure for path/visit analytics and operator-facing discovery queue copy"
    - to: "lupo-docs/doctrine/SEMANTIC_MONITORING_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Routing truth for semantic-navbar-js and monitoring surfaces"
    - to: "lupo-includes/classes/SemanticNavbarEmbedContext.php"
      type: references
      weight: 1.0
      reason: "Cross-origin gate: federation_nodes, federated_trust, federation_discovery"
    - to: "lupo-includes/classes/AdminSemanticWidgetHandler.php"
      type: references
      weight: 1.0
      reason: "Admin UI: register embedder federation node, grant semantic_widget trust, embed snippet"

lupopedia.footer:
  version: "4.0.71"
  last_verified: "20260405103800"
  last_verified_by: "cursor"
---
# file: Semantic Navbar (PRD 21) — web_path: see lupopedia.headers.web_path

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

## 5. External Sites (e.g. https://whatever.com/page.htm)

When the navbar script is loaded from **Lupopedia** but the **page** is another **origin**, the browser performs **cross-origin** `fetch()` calls. Third-party **Lupopedia cookies are not available** on the embedder site; identity continuity is handled separately (see **PRD 11** — visitor embed fingerprint, `lupo_sessions` metadata). The product **does not** allow arbitrary sites to consume the widget: access is **gated** on federation data.

### 5.1 How an external site is **allowed** (approved embedder)

**Operators do not provision embedders by writing SQL.** Routine setup is **authenticated Admin** only: register the origin, grant trust, then publish content. The tables below are the **data model** the runtime enforces; the **web UI** is canonical for steps 1–2.

All of the following must be true before the slug-routed navbar API returns data for that origin:

| Step | Table / artifact | Rule |
|------|-------------------|------|
| 1 | **`lupo_federation_nodes`** | A row exists with **`is_deleted = 0`**, **`node_base_url`** equal to the embedder **origin** only: `scheme://host` and, if non-default, **`:port`** (e.g. `https://whatever.com`, or `http://localhost:8080`). Must match **`HTTP_ORIGIN`** when the browser sends it (preferred), else the `embed_origin` query param after server-side normalization. |
| 2 | **`lupo_federated_trust`** | **Skip** if the resolved embedder **`federation_node_id`** equals the **hub** id (origin registered as the hub node itself). Otherwise: a row exists with **`source_node_id`** = **hub** (default **`1`**, or **`LUPO_HUB_FEDERATION_NODE_ID`**), **`target_node_id`** = the embedder’s node id, **`trust_type`** = **`semantic_widget`**, **`is_deleted` = 0**. |
| 3 | **`lupo_contents`** | Navbar data is resolved for **`(federation_node_id, slug)`** where **`federation_node_id`** is the **resolved** embed context from the gate: the **trusted embedder’s** node id, or the **hub** id when the origin maps to the hub node or the request is treated as non–cross-origin (see **`SemanticNavbarEmbedContext::resolveEmbedFederationContext()`**). Must match the install unique key **`(federation_node_id, slug)`**. |

**Admin (web):** **`admin.php?section=semantic-widget`** — **`AdminSemanticWidgetHandler`**: form to **register** an embedder origin (creates or reactivates a **`lupo_federation_nodes`** row with normalized **`node_base_url`**), form to **grant** hub → target **`semantic_widget`** trust (**`lupo_federated_trust`**), summary table of nodes vs trust, and copy-paste **`nav/semantic-navbar-js`** snippet. Uses CSRF on POST; explicit **`federation_node_id`** / **`trust_id`** allocation (no auto-increment reliance).

**Content (step 3):** Publish **`lupo_contents`** for that **`federation_node_id`** and slug through the **normal content / artifact / header workflow** (e.g. LUPOPEDIA HEADERS **`federation_node_id`**, **`admin.php?section=artifacts`** and related tools)—not ad hoc SQL.

**Optional taxonomy:** Operators may group embedder nodes using **`lupo_federation_categories`** and **`lupo_federation_category_map`** (organizational only; the gate does not require a category). Prefer a future admin screen for categories; until then, advanced operators only beyond the semantic-widget page.

**Runtime gate:** **`lupo-includes/classes/SemanticNavbarEmbedContext.php`** — `resolveEmbedFederationContext()`, constant **`TRUST_TYPE_SEMANTIC_WIDGET`**.

### 5.2 Tracking **allowed** embed traffic

- **HTTP / server logs:** Web server and PHP stacks record URL, status (**200** vs **403**), and **`Origin`** / query string as configured by the host — useful for volume and abuse review.
- **Cross-origin attribution params:** **`embed_origin`**, **`embed_page`**, **`embed_vid`** (see **5.4**) let operators correlate traffic with a specific embedder page and a stable first-party visitor id on the embedder (not a Lupopedia cookie). **`embed_vid`** is written only in the embedder origin’s **`localStorage`**; pairing with **`lupo_sessions`** on the Lupopedia side is covered in **PRD 11** (visitor embed fingerprint).

### 5.3 How **unapproved** origins are **discovered** (not allowed)

If the request is treated as **cross-origin** (valid normalized origin present) and either:

- **no** **`lupo_federation_nodes`** row matches that **`node_base_url`**, or  
- a node exists but **no** qualifying **`lupo_federated_trust`** row exists for **hub → target** with **`trust_type = semantic_widget`**,

then:

| Outcome | Behavior |
|---------|----------|
| **HTTP response** | **403** with JSON **`success: false`**, **`error`: `embed_not_trusted`**, **`reason`**: `unknown_node` or `no_trust`. |
| **Discovery** | **`lupo_federation_discovery`** is **upserted** by **registrable host** (lowercased host from the origin): insert or update **`last_seen_ymdhis`**, **`updated_ymdhis`**, **`install_url`**, **`description`** text noting `semantic_widget embed: unknown_node` or `semantic_widget embed: no_trust`. |

This gives operators a **review queue** of hosts that attempted the widget without being fully provisioned, without auto-creating **`lupo_federation_nodes`** for attackers.

**Ethics / disclosure:** Align operator-facing copy with **SILENT_HARVEST** and **PRD 11** when describing what is logged and why.

### 5.4 Client request shape (**`nav/semantic-navbar-js`**)

Shipped generator: **`{LUPOPEDIA_PUBLIC_PATH}/nav/semantic-navbar-js?slug=...`**.

- When **script origin ≠ page origin**, the IIFE appends query params on each **`edges|contexts|…/{slug}`** request: **`embed_origin`**, **`embed_page`** (full URL truncated), **`embed_vid`** (random id stored in **embedder** `localStorage`, first-party to the external site — not a Lupopedia cookie).
- **`fetch`** uses **`credentials: 'omit'`** for cross-origin calls so third-party cookie semantics do not apply to Lupopedia.
- **CORS:** API responses emit **`Access-Control-Allow-Origin`** (reflect **`Origin`** when present). **`OPTIONS`** returns **204** for preflight.

Server prefers **`HTTP_ORIGIN`** over **`embed_origin`** when resolving the embedder origin (browser-originated requests).

### 5.5 Why the API needs a **Lupopedia slug** (not “just the page path”)

The browser knows the embedder page URL (e.g. `https://shop.example/products/red-widget`). **Lupopedia does not treat that path as the primary key** for `lupo_contents`. The widget must receive an explicit **Lupopedia** identifier — today the **`slug`** in **`?slug=`** on `nav/semantic-navbar-js`, which flows into **`GET …/{type}/{slug}`** — resolved together with **`federation_node_id`** from the trust gate.

**Why path lookup is not automatic**

1. **Different namespace.** The path belongs to the **foreign site’s** router (WordPress, static files, SPA hash routes, etc.). Lupopedia’s **`slug`** is defined **inside Lupopedia** under **`(federation_node_id, slug)`**. There is no guarantee that `/blog/my-post` on the partner site is the same string as, or maps 1:1 to, a row in your DB — often it is not.

2. **Ambiguity and collisions.** Many sites reuse common paths (`/about`, `/contact`, locale prefixes `/en/...` vs `/fr/...`, trailing slashes, redirects). Using “whatever path the user hit” as a lookup key would either fail randomly or match the **wrong** content when two nodes or two pages collide.

3. **Intent and safety.** Cross-origin embed is **untrusted input** until federation + trust are proven. **Guessing** content from path would let a page author (or attacker) vary the path and probe or mis-associate navbar data. Requiring a **declared** slug makes the binding **explicit**: the publisher says which Lupopedia entity this embed represents.

4. **What you can do instead.** If the operator **wants** path-driven behavior, they implement it **on their side** or in **maintained data**: e.g. server-side template sets **`data-nav-slug`** / script **`?slug=`** from their CMS; or Lupopedia stores an explicit external URL on the row (**`federation_source_url`**) or a **mapping table** and a **server-side** resolver returns the slug. Those are **deliberate** joins — not “strip the path and hope it equals `lupo_contents.slug`.”

**Summary:** The path of the website is **not** a reliable or authoritative key into Lupopedia. The **slug** (or a future explicit `content_id` parameter) is the supported contract so resolution is deterministic under the correct **`federation_node_id`**.

---

## 6. How the JS Block Communicates with Lupopedia

- **No direct DB access:** The navbar JS runs in the browser and never touches the database.
- **HTTP only (shipped 4.0.x):** Per-section **`GET {LUPOPEDIA_PUBLIC_PATH}/{type}/{slug}`** where **`type`** is one of **`edges`**, **`contexts`**, **`hashtags`**, **`folders`**, **`qa`**, **`references`**, **`namespaces`**, **`next`**, **`previous`** — routed by **`lupo-includes/modules/module-loader.php`** to **`semantic-navbar-api.php`**. A consolidated **`api/semantic_navbar`** single-response shape remains a possible future optimization; do not assume it exists without checking routing.
- **Cross-origin:** See **section 5** — federation + trust required; **`credentials: 'omit'`** on embedder fetches.
- **Auth:** Public navbar JSON is intended for **anonymous GET** when trust + content exist; session cookies are not relied on for third-party embeds.
- **Caching:** Client or CDN may cache per **`(origin, type, slug)`** subject to **403** when trust is missing.

---

## 7. Related Documentation

- **Admin embedder setup (forms, snippet):** `lupo-includes/classes/AdminSemanticWidgetHandler.php`
- **Federation / semantic network (peers, navigation compiler direction):** `lupo-docs/prd/34_federation_node_semantic_network.md`
- **Cross-origin visitor identity and embed fingerprint:** `lupo-docs/prd/11_analytics_tracking.md`
- **Ethics and disclosure (analytics, operator copy for discovery):** `lupo-docs/doctrine/SILENT_HARVEST_DOCTRINE.md`
- **Table audit and overview:** `lupo-docs/database/lupopedia/tables/semantic_navbar/SEMANTIC_NAVBAR_OVERVIEW.md`, `SEMANTIC_NAVBAR_TABLE_AUDIT_REPORT.md`
- **Per-table docs:** Same directory for lupo_references, lupo_reference_links, lupo_hashtags, lupo_hashtag_map, lupo_folders, lupo_folder_map; `lupo-docs/database/lupopedia/tables/active/` for lupo_paths, lupo_edges, lupo_collections, etc.


---

## Context‑Typed, Status‑Aware, Directional Edged Memory Doctrine (4.0.96)

1. Memory in Lupopedia is represented as a directed graph of nodes and edges. 
  Each memory node is a first-class entity in the semantic network and may be 
  owned by actors, departments, auth_users, channels, federation nodes, or the 
  global system.

2. Every edge in the memory graph has FOUR dimensions:
  - **edge type** (the relationship)
  - **edge context** (the classification of the memory)
  - **edge status** (the epistemic support level)
  - **edge direction** (the traversal orientation)

3. **Edge Direction** defines whether the relationship is:
  - unidirectional (A → B)
  - bidirectional (A ↔ B)
  - restricted-direction (A → B but not B → A unless explicitly defined)
  Reverse traversal MUST NOT be inferred unless explicitly defined.

4. **Edge Type** defines the relationship between nodes, including but not 
  limited to:
  - influences
  - inherits
  - authored_by
  - observed_by
  - contradicts
  - supports
  - consolidates_from
  - refines
  - overrides

5. **Edge Context** defines the classification of the memory node. Context is 
  not based on the content of the memory, but on the structural support 
  provided by the graph. The primary context classifications are:
  - doctrine
  - experiential
  - system_generated
  - countermeasure_generated
  - summary
  - contradictory
  - deprecated

6. **Edge Status** defines the epistemic support level of the memory node:
  - **unsupported**: insufficient supporting edges; provisional memory.
  - **supported**: sufficient supporting edges; validated memory.
  - **needs_review**: conflicting, incomplete, or ambiguous edges requiring 
    agent or human intervention.

7. When `edge_status = 'needs_review'`, a **review_reason** MUST be provided. 
  This field explains *why* the edge requires review and *which agent* should 
  handle it. Examples include:
  - orphaned_edge
  - contradiction
  - new_doctrine
  - schema_drift
  - consolidation_candidate
  - integrity_unknown
  - human_escalation

  Agents use this field to determine their work queues:
  - ANUBIS handles: integrity_unknown, orphaned_edge
  - THOTH handles: schema_drift, contradiction, new_doctrine
  - KAIROS handles: consolidation_candidate
  - Human operator handles: human_escalation

8. Memory nodes may transition between statuses as edges are added, removed, 
  or reclassified. A node may move from unsupported → supported when 
  sufficient supporting edges accumulate.

9. Actors inherit memory edges from:
  - their department
  - their auth_user
  - their federation node
  - their assigned faucets
  - their assigned tasks

10. Memory traversal is context-aware and direction-aware. Actors may only 
   traverse edges permitted by their boundaries, department rules, auth_user 
   pairing, faucet assignments, and operational mode (live, simulation, 
   analysis).

11. No inference is allowed. All edges, contexts, statuses, directions, and 
   review reasons must be explicitly defined in PRDs, database rows, or 
   system-generated memory.

12. Memory is not a flat file. It is a structured, typed, classified, 
   status-aware, direction-aware graph. Traversal depth determines visible 
   memory; deeper traversal reveals more context, subject to boundary rules.

13. All changes to memory structure, edge types, edge contexts, edge statuses, 
   edge directions, or review reasons must be documented in PRDs and versioned.
```
