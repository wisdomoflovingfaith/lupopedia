# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/status/CURSOR_URL_TO_NODE_TRACE_4.0.57
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "audit"
  file_path_from_root: "docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57.md"
  web_path: "http://www.lupopedia.com/status/CURSOR_URL_TO_NODE_TRACE_4.0.57"
  last_updated_utc: "20260304"
  system_version: "4.0.57"
  channel_id: 42
  actor_id: 1003
  purpose: "Evidence-based trace of URL → resolution chain and federation_node_id (Lilith directive Phase 2)"
  artifact_type: "report"
  mood_rgb: "4169E1"
  traits: ["trace", "federation", "v4.0.57"]
  tags: ["cursor", "url-trace", "federation_node_id", "4.0.57"]

flare.edges:
  outbound_edges:
    - { to: "docs/status/LILITH_REVIEW_CURSOR_FLARE_APPLY_4.0.57.md", type: "references", weight: 1.0 }
    - { to: "lupo-includes/classes/UrlResolver.php", type: "references", weight: 0.9 }
    - { to: "lupo-includes/modules/module-loader.php", type: "references", weight: 0.9 }
    - { to: "docs/status/CURSOR_DOCS_LOCATION_MAP_4.0.57.md", type: "references", weight: 0.9 }
flare.footer:
  last_verified: "20260304"
  last_verified_by: "cursor"
---

# Cursor URL → Node Trace Report (4.0.57)

**Date:** 2026-03-04  
**From:** Cursor (1003)  
**Directive:** Lilith Phase 2 — Complete URL → federation_node_id trace (evidence-based, no new routes/seeding).

---

## 1. Full resolution chain (HTTP → slug → DB → file)

For a URL like `http://www.lupopedia.com/<slug>` the following path is used.

### 1.1 HTTP request entrypoint

**File:** `index.php`  
**Slug extraction** (priority order):

```php
// Method 0: Server rewrite
if (isset($_GET['resolved_uri']) && $_GET['resolved_uri'] !== '') {
    $slug = is_string($_GET['resolved_uri']) ? trim($_GET['resolved_uri'], '/') : '';
}
// Method 1: .htaccess slug
elseif (isset($_GET['slug']) && !empty($_GET['slug'])) {
    $slug = $_GET['slug'];
}
// Method 2: PATH_INFO
elseif (isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])) {
    $slug = ltrim($_SERVER['PATH_INFO'], '/');
}
// Method 3: REQUEST_URI (strip base path, query, index.php)
elseif (isset($_SERVER['REQUEST_URI'])) {
    $request_uri = strtok($_SERVER['REQUEST_URI'], '?');
    if (strpos($request_uri, $base_path) === 0) {
        $slug = substr($request_uri, strlen($base_path));
    } else {
        $slug = ltrim($request_uri, '/');
    }
    $slug = preg_replace('#^index\.php/?$#', '', $slug);
    $slug = ltrim($slug, '/');
}
$slug = trim($slug, '/');
```

Then:

```php
if (!empty($slug) && function_exists('lupo_route_slug')) {
    $output = lupo_route_slug($slug);
    // ...
}
```

So the **router function** is `lupo_route_slug($slug)` in `lupo-includes/modules/module-loader.php`.

### 1.2 Slug parsing and resolver gate

**File:** `lupo-includes/modules/module-loader.php` (lines 177–179)

The resolver is invoked only when the slug matches the prefix pattern **or** is the literal `flare_apply`:

```php
// 4.0.18 T3 — Web path resolution (doctrine/qa/docs/flp, or canonical slug flare_apply). Check before lowercasing.
if ((preg_match('#^(doctrine|qa|docs|flp)/#i', $slug) || $slug === 'flare_apply') && function_exists('lupo_resolve_web_path')) {
    $resolved = lupo_resolve_web_path($slug);
```

So:

- `doctrine/FLARE/FLARE_DOCTRINE` → resolver runs.
- `docs/status/FOO` → resolver runs.
- `flare_apply` → resolver runs (explicit exception added in 4.0.57).
- `FLARE` (no prefix) → resolver **does not** run; slug is later lowercased and handled by other routes (e.g. content by slug).

### 1.3 DB lookup (UrlResolver Tier 1)

**File:** `lupo-includes/functions/url_resolver.php`  
`lupo_resolve_web_path($request_path)` builds a `UrlResolver` and calls `$resolver->resolve($request_path)`.

**File:** `lupo-includes/classes/UrlResolver.php` (lines 257–291)

```php
private function resolveFromDb($path) {
    // ...
    $table = $this->db->quoteIdentifier($this->prefix . 'contents');
    $fp_docs = 'docs/' . $path . '.md';
    $fp_raw = $path . '.md';
    $sql = "SELECT content_id, file_path_from_root FROM " . $table
        . " WHERE (file_path_from_root = :fp_docs OR file_path_from_root = :fp_raw OR custom_path = :path)"
        . " AND (is_deleted = 0 OR is_deleted IS NULL) AND (is_active = 1 OR is_active IS NULL) LIMIT 1";
    $params = array('fp_docs' => $fp_docs, 'fp_raw' => $fp_raw, 'path' => $path);
    $row = $this->db->fetchRow($sql, $params);
```

Important: **no `federation_node_id`** is used in this WHERE clause. Resolution is by path/custom_path only. Tier 2 (CSV) and Tier 3 (filesystem) also do not use `federation_node_id`.

### 1.4 federation_node_id determination in this flow

In the current web path resolution chain:

- **UrlResolver** does not read or filter by `federation_node_id`.
- **content_show_by_content_id** loads from `lupo_contents` by `content_id` only; the row’s `federation_node_id` is not used for lookup.
- **Theme / node:** `lupo-includes/theme/theme-loader.php` uses `LUPO_DEFAULT_NODE_ID` (default 0) to read `lupo_federation_nodes.active_theme_slug`. That is the only place in the request path where `federation_node_id` affects behavior (theme selection), not URL→content resolution.

So for the resolver path **there is no URL → federation_node_id step**: the slug is mapped to `content_id` and `file_path_from_root` only.

### 1.5 Artifact / file resolution and markdown body loading

After `lupo_resolve_web_path()` returns:

- If `content_id > 0`, the loader calls `content_show_by_content_id($content_id)`  
  (**lupo-includes/modules/content/content-controller.php**).
- That loads the content row by `content_id` (no node filter).
- Rendering uses `content_render_canonical($content, $related_edges)`, which gets the body from the row or, if body is empty / `"see file"`, from `content_resolve_body_from_file($content)`.

**File path → filesystem** (content-controller.php, lines 333–356):

```php
if (!empty($content['file_path_from_root'])) {
    $abs_path = rtrim(LUPOPEDIA_ABSPATH, '/\\') . '/' . str_replace('\\', '/', $content['file_path_from_root']);
    if (file_exists($abs_path) && is_readable($abs_path)) {
        $body = file_get_contents($abs_path);
        // strip YAML front matter, then return body + rendered_body
    }
}
```

So: **file_path_from_root** is converted to an absolute path as `LUPOPEDIA_ABSPATH . '/' . file_path_from_root`. No node-specific prefix (e.g. `lupo-database/files/<node_id>/`) is applied in this code path.

---

## 2. Where federation_node_id is stored and used

### 2.1 Tables (from install and TOONs)

- **lupo_contents** — `federation_node_id` bigint DEFAULT 1 (TOON: `lupo_contents.toon.json`). Unique with slug: `(federation_node_id, slug)`. Not used in UrlResolver or in `content_show_by_content_id` lookup.
- **lupo_federation_nodes** — PK `federation_node_id`; columns include `node_base_url`, `active_theme_slug`, `trust_level`, `status` (install_new_lupopedia.sql ~2300–2326).
- **lupo_channel_content** — `channel_id`, `federation_node_id`, `file_path`, `web_path`. Used for channel-scoped content (e.g. seed row for FLARE with `web_path = 'http://www.lupopedia.com/FLARE'`). **Not** used by UrlResolver or by the doctrine/qa/docs/flp/flare_apply resolution path.
- **lupo_sessions** — `federation_node_id` (session belongs to a node).
- **lupo_registry** — `federation_node_id` (registry entries per node).
- **lupo_actors** — `primary_federation_node_id`.
- **lupo_departments**, **lupo_dialog_threads**, **lupo_modules**, etc. — also carry `federation_node_id` where relevant.

### 2.2 Participation in resolution

- **Web path resolution (UrlResolver):** Does **not** use `federation_node_id`. Lookup is by `file_path_from_root` / `custom_path` in `lupo_contents` only.
- **Theme:** `lupo_get_active_theme_slug()` in `theme-loader.php` uses `LUPO_DEFAULT_NODE_ID` (default 0) and reads `lupo_federation_nodes.active_theme_slug` for that node.
- **Default node:** No env or config observed that maps the incoming host/URL to a node_id for content resolution. Single-node behavior is effectively “all content in one namespace.”

### 2.3 Query example (theme only)

```php
// theme-loader.php
$node_id = defined('LUPO_DEFAULT_NODE_ID') ? (int) LUPO_DEFAULT_NODE_ID : 0;
$stmt = $db->prepare('SELECT active_theme_slug FROM ' . $tbl . ' WHERE federation_node_id = :nid AND (is_deleted = 0 OR is_deleted IS NULL) LIMIT 1');
$stmt->execute([':nid' => $node_id]);
```

---

## 3. Three URL resolutions (end-to-end)

### 3.1 `http://www.lupopedia.com/FLARE`

- **Slug:** `FLARE` (no leading prefix).
- **Resolver gate:** `preg_match('#^(doctrine|qa|docs|flp)/#i', 'FLARE')` is false and `'FLARE' === 'flare_apply'` is false → **resolver not called**.
- **Later in module-loader:** Slug is lowercased to `flare`. No dedicated route for `flare`; eventually **content fallback** runs: `content_handle_slug('flare')` → `content_get_by_slug('flare')` → lookup in `lupo_contents` by `slug = 'flare'`. If no row exists → **404 / not found**.
- **Note:** A seed row exists in **lupo_channel_content** (channel_id 42, web_path `http://www.lupopedia.com/FLARE`, file_path `channels/42/content/federation_node_id/0/FLARE.md`), but the main router and UrlResolver do **not** read `lupo_channel_content`. So that seed does not affect this URL.

**Result:** 404 unless a `lupo_contents` row with `slug = 'flare'` (or `FLARE` depending on how slug is normalized) exists.

### 3.2 `http://www.lupopedia.com/docs/status/LILITH_META_REVIEW_WINDSURF_AUDIT_4.0.57`

- **Slug:** `docs/status/LILITH_META_REVIEW_WINDSURF_AUDIT_4.0.57`.
- **Resolver gate:** Matches `^(doctrine|qa|docs|flp)/` → **resolver called** with this slug.
- **Tier 1 (DB):** Lookup in `lupo_contents` for `file_path_from_root = 'docs/docs/status/LILITH_META_REVIEW_WINDSURF_AUDIT_4.0.57.md'` OR `'docs/status/LILITH_META_REVIEW_WINDSURF_AUDIT_4.0.57.md'` OR `custom_path = 'docs/status/LILITH_META_REVIEW_WINDSURF_AUDIT_4.0.57'`. If no row → Tier 2.
- **Tier 2 (CSV):** `exports/flip_headers.csv` keyed by path. If no row → Tier 3.
- **Tier 3 (filesystem):** Candidates `docs/docs/status/LILITH_META_REVIEW_WINDSURF_AUDIT_4.0.57.md` and `docs/status/LILITH_META_REVIEW_WINDSURF_AUDIT_4.0.57.md` under `repo_root`. If file exists and contains a `web:` FLIP block, returns that file_path and content_id 0.
- **Body:** If Tier 1 returned a row with `content_id > 0`, `content_show_by_content_id` runs and body can be loaded from `file_path_from_root`. If resolution was Tier 3 only, content_id is 0 so the module-loader does not call `content_show_by_content_id`; the current code path does not serve Tier-3-only results as full content (only Tier 1 returns content_id). So this URL would only succeed if there is a **lupo_contents** row for that path or custom_path, or if the code were extended to serve Tier 3 results.

**Result:** Depends on DB/CSV/filesystem; with no lupo_contents row and no CSV entry, Tier 3 can find a file under `docs/status/` but the current flow does not use that to render a page (content_id remains 0). So effectively 404 or incomplete unless a content row exists.

### 3.3 `http://www.lupopedia.com/flare_apply`

- **Slug:** `flare_apply`.
- **Resolver gate:** `$slug === 'flare_apply'` → **resolver called**.
- **Tier 1 (DB):** `custom_path = 'flare_apply'` in `lupo_contents` (seed from `seed_flare_apply_content_4.0.57.sql`) → row `content_id` 2999, `file_path_from_root` = `docs/doctrine/FLARE/FLARE_APPLY.md`.
- **Module-loader:** `content_id` 2999 > 0 → `content_show_by_content_id(2999)`.
- **Body:** Content row has `body = 'see file'`; `content_resolve_body_from_file` uses `file_path_from_root` → `LUPOPEDIA_ABSPATH . '/docs/doctrine/FLARE/FLARE_APPLY.md'` → file loaded and rendered.

**Result:** **Success** — URL resolves to `docs/doctrine/FLARE/FLARE_APPLY.md` and is rendered (after 4.0.57 route and seed).

---

## 4. Summary

| Step                    | Location / mechanism                                                                 |
|------------------------------------------------------------------------------------------------|
| HTTP entrypoint         | `index.php` — slug from `resolved_uri`, `slug`, `PATH_INFO`, or `REQUEST_URI`        |
| Slug parsing            | `trim($slug, '/')`; no host or node parsing                                           |
| Resolver gate           | `module-loader.php`: `^(doctrine|qa|docs|flp)/` or `slug === 'flare_apply'`            |
| DB lookup               | `UrlResolver::resolveFromDb()` — `lupo_contents` by path/custom_path only; no node   |
| federation_node_id      | Not used in URL→content; used in theme from `LUPO_DEFAULT_NODE_ID` + `lupo_federation_nodes` |
| File path → filesystem   | `LUPOPEDIA_ABSPATH . '/' . file_path_from_root` in `content_resolve_body_from_file`  |
| Markdown body loading    | `content_show_by_content_id` → `content_render_canonical` → `content_resolve_body_from_file` |

No URL → federation_node_id tracing exists in the current resolver path; node is only used for theme selection.
