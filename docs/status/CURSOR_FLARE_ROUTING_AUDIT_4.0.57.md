# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "audit"
  file_path_from_root: "docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57.md"
  web_path: "http://www.lupopedia.com/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57"
  last_updated_utc: "20260304"
  system_version: "4.0.57"
  channel_id: 42
  actor_id: 1003
  purpose: "Audit of /flare_apply routing and federation behavior (Captain directive — analysis only, no code changes)"
  artifact_type: "report"
  mood_rgb: "4169E1"
  traits: ["audit", "routing", "federation", "v4.0.57"]
  tags: ["cursor", "flare_apply", "routing", "federation_node_id", "4.0.57"]

flare.edges:
  outbound_edges:
    - { to: "docs/doctrine/FLARE/FLARE_APPLY.md", type: "references", weight: 1.0 }
    - { to: "lupo-includes/modules/module-loader.php", type: "references", weight: 0.9 }
    - { to: "lupo-includes/classes/UrlResolver.php", type: "references", weight: 0.9 }
    - { to: "lupo-docs/channels/doctrine/WEB_ROUTING_DOCTRINE_4_0_18.md", type: "references", weight: 0.8 }
flare.footer:
  last_verified: "20260304"
  last_verified_by: "cursor"
---

# Cursor FLARE routing audit (4.0.57)

**Date:** 2026-03-04  
**From:** Cursor (1003)  
**Directive:** Captain Wolfie — Verify `/flare_apply` routing and federation behavior before adding more routes. **Analysis only; no code or seed changes in this audit.**

**Architecture note (Captain):** www.lupopedia.com is always **federation_node_id 0**; in this install **federation_node_id 1** is localhost (which may serve the same file set as www.lupopedia.com). Doctrine docs canonical for the main site should be node **0**.

---

## 1. Router safety analysis

### 1.1 What the regex does

**File:** `lupo-includes/modules/module-loader.php` (lines 177–179)

```php
// 4.0.18 T3 — Web path resolution (doctrine/qa/docs/flp, or canonical slug flare_apply). Check before lowercasing (paths are case-sensitive).
if ((preg_match('#^(doctrine|qa|docs|flp)/#i', $slug) || $slug === 'flare_apply') && function_exists('lupo_resolve_web_path')) {
    $resolved = lupo_resolve_web_path($slug);
```

- **Pattern:** `^(doctrine|qa|docs|flp)/` (case-insensitive). Only slugs that **start with** one of these four prefixes invoke the three-tier resolver.
- **Exception:** `$slug === 'flare_apply'` (added in 4.0.57) invokes the resolver without a prefix.

### 1.2 Why the regex exists (evidence)

**Source:** `lupo-docs/channels/doctrine/WEB_ROUTING_DOCTRINE_4_0_18.md`

- §2.2: “**Wildcard route(s)** for `/{base}/{slug}` … that invoke the resolver.”
- §2.3: “**Server-side rewrite rules** … funnel relevant requests … (e.g. `/doctrine/…`, `/docs/…`).”

So the design is: **only paths under a small set of bases** (doctrine, qa, docs, flp) go to the resolver. The regex is a **routing constraint**, not a security check. The resolver itself does not enforce privileges by path; Ban at Gate runs after resolution and applies to the resolved path.

**Conclusion:** The regex exists to **limit which slugs hit the resolver** (doctrine/qa/docs/flp only). It avoids invoking the resolver for every possible slug (e.g. `login`, `admin`, `content/foo`) and keeps doc resolution under a clear URL shape.

### 1.3 Risk of the bare-slug exception

- **Conflict:** A future content item with `slug = 'flare_apply'` in `lupo_contents` (e.g. for a different node or purpose) would still be found by the resolver when the URL is `/flare_apply`. The resolver does not filter by `federation_node_id`, so the first matching row wins. Low risk today (single row), but the pattern is brittle.
- **Security:** No evidence that the regex was added for security. The resolver only returns `content_id` and `file_path`; access control is elsewhere (e.g. Ban at Gate, content visibility). So the exception does not introduce a clear privilege bypass.
- **Maintainability:** Adding more bare slugs (e.g. `slug === 'other_doc'`) would grow a hardcoded list and bypass the “only these prefixes” rule. Prefer one rule: **all doc URLs use a prefix**.

### 1.4 Recommendation for canonical doc URLs

**Prefer prefix-based URLs so they match the existing regex:**

- **Option A:** `/docs/flare_apply` — slug `docs/flare_apply` → matches `^(doctrine|qa|docs|flp)/` → resolver with path `docs/flare_apply` → Tier 1: `custom_path = 'docs/flare_apply'` or `file_path_from_root = 'docs/docs/flare_apply.md'` or `'docs/flare_apply.md'`. Would require a seed row with `custom_path = 'docs/flare_apply'` and/or `file_path_from_root = 'docs/doctrine/FLARE/FLARE_APPLY.md'`, and no exception in the router.
- **Option B:** `/doctrine/FLARE/flare_apply` (or similar) — same idea, under `doctrine/`.

**Current state:** `/flare_apply` works because of the explicit `$slug === 'flare_apply'` check. It is **safe to keep** for this single doc, but for **future doctrine URLs** the audit recommends **using `/docs/...` or `/doctrine/...`** and avoiding more bare-slug exceptions.

---

## 2. Full URL resolution trace: `http://www.lupopedia.com/flare_apply`

Step-by-step with code references.

### Step 1 — HTTP request → slug

**File:** `index.php`  
Slug comes from `$_GET['resolved_uri']`, `$_GET['slug']`, `PATH_INFO`, or `REQUEST_URI` (strip base path and query). For `http://www.lupopedia.com/flare_apply`, slug becomes **`flare_apply`**.

### Step 2 — Router gate

**File:** `lupo-includes/modules/module-loader.php` (177–179)

```php
if ((preg_match('#^(doctrine|qa|docs|flp)/#i', $slug) || $slug === 'flare_apply') && function_exists('lupo_resolve_web_path')) {
    $resolved = lupo_resolve_web_path($slug);
```

- `preg_match('#^(doctrine|qa|docs|flp)/#i', 'flare_apply')` → false.
- `$slug === 'flare_apply'` → true.
- So **resolver is invoked** with `$slug = 'flare_apply'`.

### Step 3 — Resolver entry

**File:** `lupo-includes/functions/url_resolver.php` (30–43)

```php
function lupo_resolve_web_path($request_path) {
    // ...
    $resolver = new UrlResolver($db, $prefix, $repo_root, $alias_redirect, $log_fallback);
    return $resolver->resolve($request_path);
}
```

`resolve('flare_apply')` runs; internal path is normalized (still `flare_apply`).

### Step 4 — Database lookup (Tier 1)

**File:** `lupo-includes/classes/UrlResolver.php` (257–291)

```php
private function resolveFromDb($path) {
    $table = $this->db->quoteIdentifier($this->prefix . 'contents');
    $fp_docs = 'docs/' . $path . '.md';   // 'docs/flare_apply.md'
    $fp_raw = $path . '.md';              // 'flare_apply.md'
    $sql = "SELECT content_id, file_path_from_root FROM " . $table
        . " WHERE (file_path_from_root = :fp_docs OR file_path_from_root = :fp_raw OR custom_path = :path)"
        . " AND (is_deleted = 0 OR is_deleted IS NULL) AND (is_active = 1 OR is_active IS NULL) LIMIT 1";
    $params = array('fp_docs' => $fp_docs, 'fp_raw' => $fp_raw, 'path' => $path);
    $row = $this->db->fetchRow($sql, $params);
```

- **No `federation_node_id`** in the WHERE clause.
- Seed row has `custom_path = 'flare_apply'` → match.
- Returns **content_id = 2999**, **file_path = file_path_from_root** = `docs/doctrine/FLARE/FLARE_APPLY.md`.

### Step 5 — Resolver return → module-loader

**File:** `lupo-includes/modules/module-loader.php` (180–226)

```php
$content_id = isset($resolved['content_id']) ? (int) $resolved['content_id'] : 0;
// ...
if ($content_id > 0) {
    require_once $content_controller;
    if (function_exists('content_show_by_content_id')) {
        $out = content_show_by_content_id($content_id);
```

So **content_id 2999** → **content_show_by_content_id(2999)**.

### Step 6 — Content row load (no node filter)

**File:** `lupo-includes/modules/content/content-controller.php` (149–167, 199–205)

```php
function content_lookup_by_id($content_id) {
    // ...
    $stmt = $pdo->prepare("
        SELECT * FROM lupo_contents
        WHERE content_id = :content_id AND (is_deleted = 0 OR is_deleted IS NULL) AND (is_active = 1 OR is_active IS NULL)
        LIMIT 1
    ");
    $stmt->execute(array('content_id' => $content_id));
    $content = $stmt->fetch(PDO::FETCH_ASSOC);
```

- Lookup is by **content_id only**. The row’s **federation_node_id** is not used in the query.
- Returned row includes **file_path_from_root** = `docs/doctrine/FLARE/FLARE_APPLY.md` and **federation_node_id** = 1 (from current seed).

### Step 7 — Body from file (file_path_from_root → filesystem)

**File:** `lupo-includes/modules/content/content-controller.php` (333–356)

```php
function content_resolve_body_from_file($content) {
    if (!empty($content['file_path_from_root'])) {
        $abs_path = rtrim(LUPOPEDIA_ABSPATH, '/\\') . '/' . str_replace('\\', '/', $content['file_path_from_root']);
        if (file_exists($abs_path) && is_readable($abs_path)) {
            $body = file_get_contents($abs_path);
            // strip YAML front matter, render markdown, return body + rendered_body
        }
    }
```

- **Absolute path** = **LUPOPEDIA_ABSPATH . '/' . file_path_from_root**.
- No node-based segment (e.g. no `lupo-database/files/<node_id>/`). So **markdown is loaded from repo root**, not from a node-specific directory.

**End-to-end:**  
HTTP → slug `flare_apply` → resolver gate (exception) → `lupo_resolve_web_path('flare_apply')` → Tier 1 DB → **content_id 2999**, **file_path_from_root** `docs/doctrine/FLARE/FLARE_APPLY.md` → **content_show_by_content_id(2999)** → **content_lookup_by_id(2999)** (no federation_node_id in query) → **content_resolve_body_from_file** → **LUPOPEDIA_ABSPATH/docs/doctrine/FLARE/FLARE_APPLY.md** → markdown rendered.

---

## 3. federation_node_id behavior

### 3.1 Tables that define or store node

- **lupo_federation_nodes** (install_new_lupopedia.sql ~2300–2326): PK **federation_node_id**; columns include **node_base_url**, **active_theme_slug**, **trust_level**, **status**. Defines each node.
- **lupo_contents**: Column **federation_node_id** (e.g. default 1 in schema). Unique with slug: **(federation_node_id, slug)**. So the same slug can exist once per node.

### 3.2 Which node is “main” (Captain’s architecture)

- **www.lupopedia.com** = **federation_node_id 0**.
- **localhost (this install)** = **federation_node_id 1**, serving the same file set as www.lupopedia.com.

So content that is **canonical for the main site (www.lupopedia.com)** should be stored with **federation_node_id = 0**.

### 3.3 How resolution uses (or ignores) node

- **UrlResolver::resolveFromDb()** — does **not** filter by `federation_node_id`. It matches only on `file_path_from_root` and `custom_path`. So any row with `custom_path = 'flare_apply'` is returned regardless of node.
- **content_lookup_by_id()** — loads by **content_id** only. So the **federation_node_id** on the row is **not** used for resolution or file loading.

**Conclusion:** Today, **resolution and file loading do not depend on federation_node_id**. The seed’s **federation_node_id = 1** does not break behavior. Semantically, however, **flare_apply** is canonical for www.lupopedia.com, so the audit recommends **federation_node_id = 0** in the seed when a change is made (not in this audit).

### 3.4 If future code filters by node

If later the resolver or content layer filters by `federation_node_id` (e.g. by host or config), then:

- Content for **www.lupopedia.com** should have **federation_node_id = 0**.
- Keeping the current seed at **1** would then make this doc belong to “localhost” in a multi-node world. So fixing the seed to **0** now avoids a future semantic bug.

---

## 4. Filesystem resolution (where markdown is loaded)

### 4.1 Single conversion used in this path

**Function:** `content_resolve_body_from_file($content)`  
**File:** `lupo-includes/modules/content/content-controller.php` (333–336)

```php
$abs_path = rtrim(LUPOPEDIA_ABSPATH, '/\\') . '/' . str_replace('\\', '/', $content['file_path_from_root']);
```

So: **filesystem path = LUPOPEDIA_ABSPATH + '/' + file_path_from_root**. No node id in the path.

### 4.2 Other locations (not used for this URL)

- **UrlResolver::resolveFromFilesystem()** (Tier 3): builds paths under **repo_root** as `docs/<path>.md` or `<path>.md`. No `lupo-database/files/<node_id>/`, no `channels/<node_id>/`, no `artifacts/<node_id>/` in that code.
- **content_resolve_body_from_file** fallback: fixed list of directories under **LUPOPEDIA_ABSPATH/docs/...** (channels/overview, doctrine, architecture, etc.). Still repo-root relative, no node segment.

**Conclusion:** For the current `/flare_apply` flow, **docs are loaded from the repo root** via **file_path_from_root** only. Node-specific paths like **lupo-database/files/<node_id>/** or **channels/<node_id>/** are **not** used in this resolution path.

---

## 5. Validation of three URLs

| URL | Slug | Router | DB lookup | federation_node_id in flow | File path / result |
|-----|------|--------|-----------|----------------------------|---------------------|
| **http://www.lupopedia.com/FLARE** | `FLARE` | Resolver **not** invoked (no prefix, not `flare_apply`). Slug later lowercased to `flare`. | Later: content_handle_slug → content_get_by_slug('flare') → lupo_contents by slug. | Not used. | If no row with slug `flare` → 404. **lupo_channel_content** has a FLARE row (web_path, file_path) but is **not** read by this flow. |
| **http://www.lupopedia.com/flare_apply** | `flare_apply` | Resolver invoked (exception). | Tier 1: lupo_contents by custom_path = 'flare_apply' → content_id 2999, file_path_from_root = docs/doctrine/FLARE/FLARE_APPLY.md. | Stored on row (1) but **not** used in lookup or file path. | LUPOPEDIA_ABSPATH/docs/doctrine/FLARE/FLARE_APPLY.md loaded. **Success.** |
| **http://www.lupopedia.com/docs/status/LILITH_META_REVIEW_WINDSURF_AUDIT_4.0.57** | `docs/status/LILITH_META_REVIEW_WINDSURF_AUDIT_4.0.57` | Resolver invoked (matches `docs/`). | Tier 1: file_path_from_root or custom_path match; Tier 2: CSV; Tier 3: repo_root + docs/... or ... .md. | Not used. | If Tier 1 row exists with content_id > 0 → content shown from file_path_from_root. If only Tier 3 finds a file, content_id is 0 and current code does not call content_show_by_content_id, so no page is rendered from that file. |

---

## 6. Recommendations

### 6.1 Router

- **Current `/flare_apply` exception:** Safe to keep for this single doc; no evidence of a security issue. The main risk is **maintainability** if more bare slugs are added.
- **Going forward:** Prefer **prefix-based canonical URLs** for doctrine/docs (e.g. **/docs/flare_apply** or **/doctrine/FLARE/flare_apply**) so they match `^(doctrine|qa|docs|flp)/` and no new router exceptions are needed.

### 6.2 federation_node_id in seed

- **Recommendation:** When the seed is next updated, set **federation_node_id = 0** for the flare_apply row so it is clearly “main site” (www.lupopedia.com) content. Resolution and file loading will still work, and future node-aware logic will treat the doc correctly.

### 6.3 Filesystem

- No change needed for current behavior. Doc resolution uses **LUPOPEDIA_ABSPATH + file_path_from_root** only. Node-based paths are not in use in this flow.

### 6.4 Summary

| Item | Finding | Recommendation |
|------|---------|----------------|
| Router exception `flare_apply` | Routing constraint only; no security role in regex. One-off exception is acceptable. | Keep; for new doc URLs use `/docs/...` or `/doctrine/...`. |
| federation_node_id = 1 in seed | Resolution ignores it; semantics: main site = 0. | When editing seed, set **federation_node_id = 0**. |
| file_path_from_root → path | Single formula: LUPOPEDIA_ABSPATH + '/' + file_path_from_root. | No change. |
| Node-specific dirs | Not used in this path. | No change unless product adds node-scoped file storage. |

---

**Audit complete. No code or seed modified.**  
Next step (if desired): apply **federation_node_id = 0** in seed and/or add a redirect from `/docs/flare_apply` to the same content for consistency with prefix-based doc URLs.
