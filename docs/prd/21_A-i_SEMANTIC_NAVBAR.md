---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/21_A-i_SEMANTIC_NAVBAR.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/21_A-i_SEMANTIC_NAVBAR.md
  status: active
  when_updated: '20260817092400'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/21_semantic_navbar.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/semantic-navbar
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_A-i_21_A-i
  title: 'PRD 21: Semantic Navbar'
  summary: Defines the Semantic Floating Navigation Bar API, endpoints, SQL queries, data flow, federation trust for external sites, and frontend JS communication.
---
# PRD 21: Semantic Navbar

# Semantic Floating Navigation Bar - Frontend and API

This document describes API endpoints used by the semantic navbar, SQL queries used on the backend, data flow, how each navbar icon/section maps to DB tables, behavior on external sites, and how the JS block communicates with Lupopedia.

---

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## 1. API Endpoints Used

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `{LUPOPEDIA_PUBLIC_PATH}/api/semantic_navbar` | GET | Single response containing previous, references, contexts, edges, hashtags, folders, qa, next, and optional color identity. |
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
  "next": [ { "content_id", "title", "slug", "url" } ],
  "color_identity": {
    "group_color": "GOLD",
    "color_name": "goldenwolf",
    "collection_name": "",
    "hex6": "",
    "handshake": "lupopedia poweredby [GOLD] [goldenwolf]"
  }
}
```

**Color identity (optional):** The navbar MAY show the page's GroupColor, ColorName, Collection Name, and a small color badge when identity is known. HEX6 MUST NOT be guessed. HEX6 is six digits with no `#`. Color is not a LUP KEY token. Full color + lineage UI lives in **The Eye (PRD 28)**. Navbar display is a compact indicator, not the Color Registry form. Payload MAY arrive via the navbar API or via **`lupopedia_js.php`** (PRD 04). Empty/pending is valid until registry storage exists (PRD 90 / PRD 01_B). This PRD update adds no DDL.

## Color Groups and Collections (unified)

A Color Group is not only a color identity. It also represents a **Collection**: a named set of webpages, artifacts, or semantic nodes.

A Color Group stores: `color_group`, `color_nickname`, `collection_name` (new), lineage metadata, and semantic identity metadata.

**Collection Selector (blue dropdown):** Each entry is a Collection Name from **`lupo_collections`**. Selecting a Collection sets the active Collection and updates **all** semantic drop menus.

**Green content tabs:** Top-level tabs (for example `[Content A]`, `[Content B]`) become **multi-level drop menus** whose items come from **`lupo_collection_tabs`** filtered by the selected Collection. Item membership is **`lupo_collection_tab_map`**. Table doctrine is **PRD 73**.

Color Groups and Collections are unified: choosing a Collection is choosing which Color Group's webpage set the semantic navbar is showing.

**Navbar does not appear in artifact embeds.** Artifact lineage (documents, video, music, images, code) is **PRD 92**. Do not inject this navbar into an artifact player or iframe.

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
+-------------------------------------------------------------------------+
|  Browser: Page with embedded semantic navbar JS                         |
|  - Reads data-nav-content-id or data-nav-slug (or config)               |
+-------------------------------------------------------------------------+
                                    |
                                    v
+-------------------------------------------------------------------------+
|  GET /api/semantic_navbar?content_id=123                                |
+-------------------------------------------------------------------------+
                                    |
                                    v
+-------------------------------------------------------------------------+
|  Server: Resolve content_id/slug -> entity_type + entity_id             |
|  Query: lupo_paths, lupo_references, lupo_reference_links,              |
|         lupo_collections, lupo_collection_tabs, lupo_collection_tab_map,|
|         lupo_collection_tab_paths, lupo_edges, lupo_hashtags,           |
|         lupo_hashtag_map, lupo_folders, lupo_folder_map,                |
|         lupo_truth_knowledge, lupo_truth_answers                        |
+-------------------------------------------------------------------------+
                                    |
                                    v
+-------------------------------------------------------------------------+
|  Response: JSON { previous, references, contexts, edges, hashtags,      |
|                   folders, qa, next }                                   |
+-------------------------------------------------------------------------+
                                    |
                                    v
+-------------------------------------------------------------------------+
|  Browser: Render floating bar; each icon/section maps to one of the     |
|           keys above (see ????4).                                          |
+-------------------------------------------------------------------------+
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

When the navbar script is loaded from **Lupopedia** but the **page** is another **origin**, the browser performs **cross-origin** `fetch()` calls. Third-party **Lupopedia cookies are not available** on the embedder site; identity continuity is handled separately (see **PRD 11** -- visitor embed fingerprint, `lupo_sessions` metadata). The product **does not** allow arbitrary sites to consume the widget: access is **gated** on federation data.

### 5.1 How an external site is **allowed** (approved embedder)

**Operators do not provision embedders by writing SQL.** Routine setup is **authenticated Admin** only: register the origin, grant trust, then publish content. The tables below are the **data model** the runtime enforces; the **web UI** is canonical for steps 1-2.

All of the following must be true before the slug-routed navbar API returns data for that origin:

| Step | Table / artifact | Rule |
|------|-------------------|------|
| 1 | **`lupo_federation_nodes`** | A row exists with **`is_deleted = 0`**, **`node_base_url`** equal to the embedder **origin** only: `scheme://host` and, if non-default, **`:port`** (e.g. `https://whatever.com`, or `http://localhost:8080`). Must match **`HTTP_ORIGIN`** when the browser sends it (preferred), else the `embed_origin` query param after server-side normalization. |
| 2 | **`lupo_federated_trust`** | **Skip** if the resolved embedder **`federation_node_id`** equals the **hub** id (origin registered as the hub node itself). Otherwise: a row exists with **`source_node_id`** = **hub** (default **`1`**, or **`LUPO_HUB_FEDERATION_NODE_ID`**), **`target_node_id`** = the embedder's node id, **`trust_type`** = **`semantic_widget`**, **`is_deleted` = 0**. |
| 3 | **`lupo_contents`** | Navbar data is resolved for **`(federation_node_id, slug)`** where **`federation_node_id`** is the **resolved** embed context from the gate: the **trusted embedder's** node id, or the **hub** id when the origin maps to the hub node or the request is treated as non-cross-origin (see **`SemanticNavbarEmbedContext::resolveEmbedFederationContext()`**). Must match the install unique key **`(federation_node_id, slug)`**. |

**Admin (web):** **`admin.php?section=semantic-widget`** -- **`AdminSemanticWidgetHandler`**: form to **register** an embedder origin (creates or reactivates a **`lupo_federation_nodes`** row with normalized **`node_base_url`**), form to **grant** hub -> target **`semantic_widget`** trust (**`lupo_federated_trust`**), summary table of nodes vs trust, and copy-paste **`nav/semantic-navbar-js`** snippet. Uses CSRF on POST; explicit **`federation_node_id`** / **`trust_id`** allocation (no auto-increment reliance).

**Content (step 3):** Publish **`lupo_contents`** for that **`federation_node_id`** and slug through the **normal content / artifact / header workflow** (e.g. LUPOPEDIA HEADERS **`federation_node_id`**, **`admin.php?section=artifacts`** and related tools)--not ad hoc SQL.

**Optional taxonomy:** Operators may group embedder nodes using **`lupo_federation_categories`** and **`lupo_federation_category_map`** (organizational only; the gate does not require a category). Prefer a future admin screen for categories; until then, advanced operators only beyond the semantic-widget page.

**Runtime gate:** **`includes/classes/SemanticNavbarEmbedContext.php`** -- `resolveEmbedFederationContext()`, constant **`TRUST_TYPE_SEMANTIC_WIDGET`**.

### 5.2 Tracking **allowed** embed traffic

- **HTTP / server logs:** Web server and PHP stacks record URL, status (**200** vs **403**), and **`Origin`** / query string as configured by the host -- useful for volume and abuse review.
- **Cross-origin attribution params:** **`embed_origin`**, **`embed_page`**, **`embed_vid`** (see **5.4**) let operators correlate traffic with a specific embedder page and a stable first-party visitor id on the embedder (not a Lupopedia cookie). **`embed_vid`** is written only in the embedder origin's **`localStorage`**; pairing with **`lupo_sessions`** on the Lupopedia side is covered in **PRD 11** (visitor embed fingerprint).

### 5.3 How **unapproved** origins are **discovered** (not allowed)

If the request is treated as **cross-origin** (valid normalized origin present) and either:

- **no** **`lupo_federation_nodes`** row matches that **`node_base_url`**, or  
- a node exists but **no** qualifying **`lupo_federated_trust`** row exists for **hub -> target** with **`trust_type = semantic_widget`**,

then:

| Outcome | Behavior |
|---------|----------|
| **HTTP response** | **403** with JSON **`success: false`**, **`error`: `embed_not_trusted`**, **`reason`**: `unknown_node` or `no_trust`. |
| **Discovery** | **`lupo_federation_discovery`** is **upserted** by **registrable host** (lowercased host from the origin): insert or update **`last_seen_ymdhis`**, **`updated_ymdhis`**, **`install_url`**, **`description`** text noting `semantic_widget embed: unknown_node` or `semantic_widget embed: no_trust`. |

This gives operators a **review queue** of hosts that attempted the widget without being fully provisioned, without auto-creating **`lupo_federation_nodes`** for attackers.

**Ethics / disclosure:** Align operator-facing copy with **SILENT_HARVEST** and **PRD 11** when describing what is logged and why.

### 5.4 Client request shape (**`nav/semantic-navbar-js`**)

Shipped generator: **`{LUPOPEDIA_PUBLIC_PATH}/nav/semantic-navbar-js?slug=...`**.

- When **script origin != page origin**, the IIFE appends query params on each **`edges|contexts|.../{slug}`** request: **`embed_origin`**, **`embed_page`** (full URL truncated), **`embed_vid`** (random id stored in **embedder** `localStorage`, first-party to the external site -- not a Lupopedia cookie).
- **`fetch`** uses **`credentials: 'omit'`** for cross-origin calls so third-party cookie semantics do not apply to Lupopedia.
- **CORS:** API responses emit **`Access-Control-Allow-Origin`** (reflect **`Origin`** when present). **`OPTIONS`** returns **204** for preflight.

Server prefers **`HTTP_ORIGIN`** over **`embed_origin`** when resolving the embedder origin (browser-originated requests).

### 5.5 Why the API needs a **Lupopedia slug** (not "just the page path???????)

The browser knows the embedder page URL (e.g. `https://shop.example/products/red-widget`). **Lupopedia does not treat that path as the primary key** for `lupo_contents`. The widget must receive an explicit **Lupopedia** identifier -- today the **`slug`** in **`?slug=`** on `nav/semantic-navbar-js`, which flows into **`GET .../{type}/{slug}`** -- resolved together with **`federation_node_id`** from the trust gate.

**Why path lookup is not automatic**

1. **Different namespace.** The path belongs to the **foreign site's** router (WordPress, static files, SPA hash routes, etc.). Lupopedia's **`slug`** is defined **inside Lupopedia** under **`(federation_node_id, slug)`**. There is no guarantee that `/blog/my-post` on the partner site is the same string as, or maps 1:1 to, a row in your DB -- often it is not.

2. **Ambiguity and collisions.** Many sites reuse common paths (`/about`, `/contact`, locale prefixes `/en/...` vs `/fr/...`, trailing slashes, redirects). Using "whatever path the user hit??????? as a lookup key would either fail randomly or match the **wrong** content when two nodes or two pages collide.

3. **Intent and safety.** Cross-origin embed is **untrusted input** until federation + trust are proven. **Guessing** content from path would let a page author (or attacker) vary the path and probe or mis-associate navbar data. Requiring a **declared** slug makes the binding **explicit**: the publisher says which Lupopedia entity this embed represents.

4. **What you can do instead.** If the operator **wants** path-driven behavior, they implement it **on their side** or in **maintained data**: e.g. server-side template sets **`data-nav-slug`** / script **`?slug=`** from their CMS; or Lupopedia stores an explicit external URL on the row (**`federation_source_url`**) or a **mapping table** and a **server-side** resolver returns the slug. Those are **deliberate** joins -- not "strip the path and hope it equals `lupo_contents.slug`.???????

**Summary:** The path of the website is **not** a reliable or authoritative key into Lupopedia. The **slug** (or a future explicit `content_id` parameter) is the supported contract so resolution is deterministic under the correct **`federation_node_id`**.

---

## 6. How the JS Block Communicates with Lupopedia

- **No direct DB access:** The navbar JS runs in the browser and never touches the database.
- **HTTP only (shipped 4.0.x):** Per-section **`GET {LUPOPEDIA_PUBLIC_PATH}/{type}/{slug}`** where **`type`** is one of **`edges`**, **`contexts`**, **`hashtags`**, **`folders`**, **`qa`**, **`references`**, **`namespaces`**, **`next`**, **`previous`** -- routed by **`includes/modules/module-loader.php`** to **`semantic-navbar-api.php`**. A consolidated **`api/semantic_navbar`** single-response shape remains a possible future optimization; do not assume it exists without checking routing.
- **Cross-origin:** See **section 5** -- federation + trust required; **`credentials: 'omit'`** on embedder fetches.
- **Auth:** Public navbar JSON is intended for **anonymous GET** when trust + content exist; session cookies are not relied on for third-party embeds.
- **Caching:** Client or CDN may cache per **`(origin, type, slug)`** subject to **403** when trust is missing.

---

## 7. Related Documentation

- **Color identity compact display:** this PRD (section 1). Full Eye color + lineage: `docs/prd/28_A-i_SEMANTIC_MONITORING_WIDGET.md`
- **Color Groups and Collections (unified):** this PRD; tables `lupo_collections` / `lupo_collection_tabs` in `docs/prd/73_A-i_COLLECTIONS_NAVIGATION.md`
- **lupopedia_js.php payload:** `docs/prd/04_A-i_LUPOPEDIA_JS_FOUNDATION.md`
- **Admin embedder setup (forms, snippet):** `includes/classes/AdminSemanticWidgetHandler.php`
- **Federation / semantic network (peers, navigation compiler direction):** `docs/prd/34_federation_node_semantic_network.md`
- **Cross-origin visitor identity and embed fingerprint:** `docs/prd/11_analytics_tracking.md`
- **Ethics and disclosure (analytics, operator copy for discovery):** `docs/doctrine/SILENT_HARVEST_DOCTRINE.md`
- **Table audit and overview:** `docs/database/lupopedia/tables/semantic_navbar/SEMANTIC_NAVBAR_OVERVIEW.md`, `SEMANTIC_NAVBAR_TABLE_AUDIT_REPORT.md`
- **Per-table docs:** Same directory for lupo_references, lupo_reference_links, lupo_hashtags, lupo_hashtag_map, lupo_folders, lupo_folder_map; `docs/database/lupopedia/tables/active/` for lupo_paths, lupo_edges, lupo_collections, etc.


---

## Memory Graph Integration

For memory graph doctrine (edge types, contexts, statuses, directions), see:
- **PRD 38** -- Memory Unification
- **PRD 51** -- Memory Graph as Source of Truth

The Semantic Navbar displays edges from `lupo_edges` table per PRD 38 edge types.
```
