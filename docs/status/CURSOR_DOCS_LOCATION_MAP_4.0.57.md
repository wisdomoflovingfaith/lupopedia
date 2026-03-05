# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/status/CURSOR_DOCS_LOCATION_MAP_4.0.57
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "audit"
  file_path_from_root: "docs/status/CURSOR_DOCS_LOCATION_MAP_4.0.57.md"
  web_path: "http://www.lupopedia.com/status/CURSOR_DOCS_LOCATION_MAP_4.0.57"
  last_updated_utc: "20260304"
  system_version: "4.0.57"
  channel_id: 42
  actor_id: 1003
  purpose: "Filesystem mappings, TOON role, and router slug rules (Lilith directive Phase 2)"
  artifact_type: "report"
  mood_rgb: "4169E1"
  traits: ["map", "toon", "router", "v4.0.57"]
  tags: ["cursor", "docs-location", "toon", "router", "4.0.57"]

flare.edges:
  outbound_edges:
    - { to: "docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57.md", type: "references", weight: 1.0 }
    - { to: "lupo-includes/modules/module-loader.php", type: "references", weight: 0.9 }
    - { to: "docs/toons", type: "references", weight: 0.8 }
flare.footer:
  last_verified: "20260304"
  last_verified_by: "cursor"
---

# Cursor docs location map and router rules (4.0.57)

**Date:** 2026-03-04  
**From:** Cursor (1003)  
**Directive:** Lilith Phase 2 — Filesystem mappings, TOON integration, router slug rules.

---

## 1. Filesystem mapping (file_path_from_root → absolute path)

### 1.1 Primary conversion (content body loading)

**File:** `lupo-includes/modules/content/content-controller.php` — `content_resolve_body_from_file($content)`:

```php
if (!empty($content['file_path_from_root'])) {
    $abs_path = rtrim(LUPOPEDIA_ABSPATH, '/\\') . '/' . str_replace('\\', '/', $content['file_path_from_root']);
    if (file_exists($abs_path) && is_readable($abs_path)) {
        $body = file_get_contents($abs_path);
        // ... strip YAML, render
    }
}
```

So: **absolute path = LUPOPEDIA_ABSPATH . '/' . file_path_from_root**.  
No node-specific prefix (e.g. `lupo-database/files/<node_id>/`) is applied here. All paths are relative to repo root.

### 1.2 UrlResolver Tier 3 (filesystem fallback)

**File:** `lupo-includes/classes/UrlResolver.php` — `resolveFromFilesystem($path)`:

```php
$candidates = array(
    'docs/' . $path . '.md',
    $path . '.md',
);
foreach ($candidates as $rel) {
    $full = $this->repo_root . '/' . str_replace('/', DIRECTORY_SEPARATOR, $rel);
    if (!is_file($full) || !is_readable($full)) {
        continue;
    }
    // parse FLIP web block, return file_path = $rel
}
```

So Tier 3 only checks under **repo_root**:

- `repo_root/docs/<path>.md`
- `repo_root/<path>.md`

Again, no `channels/<node_id>/`, `artifacts/<node_id>/`, or `lupo-database/files/<node_id>/` in this code path.

### 1.3 content_resolve_body_from_file fallback directories

When `file_path_from_root` is not set or file not found, body is searched by slug/title in fixed directories under **docs/**:

```php
$doc_root = rtrim(LUPOPEDIA_ABSPATH, '/\\') . '/docs';
$directories = [
    $doc_root . '/channels/overview',
    $doc_root . '/channels/doctrine',
    $doc_root . '/channels/architecture',
    $doc_root . '/channels/schema',
    $doc_root . '/channels/agents',
    $doc_root . '/channels/ui-ux',
    $doc_root . '/channels/developer',
    $doc_root . '/channels/history',
    $doc_root . '/channels/appendix',
    $doc_root . '/overview',
    $doc_root . '/doctrine',
    $doc_root . '/architecture',
    $doc_root . '/schema',
    $doc_root . '/agents',
    $doc_root . '/ui-ux',
    $doc_root . '/developer',
    $doc_root . '/history',
    $doc_root . '/appendix'
];
```

So all are **LUPOPEDIA_ABSPATH/docs/...** — no node-based paths. **docs/doctrine/FLARE/** is not in this list; FLARE_APPLY is found only via `file_path_from_root` (e.g. `docs/doctrine/FLARE/FLARE_APPLY.md`).

### 1.4 Other locations (reference only)

- **lupo_channel_content** seed references `channels/42/content/federation_node_id/0/FLARE.md` and `web_path = 'http://www.lupopedia.com/FLARE'`. That table is not used by UrlResolver or the doctrine/docs/flp/flare_apply path; channel content is a separate concern.
- **lupo-database/files/<node_id>/** is documented in FLARE federation refinement (e.g. FLARE_DOCTRINE Section 22) as an optional future layout; it is not implemented in the current resolver or content body loading.

---

## 2. TOON integration

### 2.1 Location and role

- **Path:** `docs/toons/` (and `lupo-database/lupopedia/toon/` in some references).  
- **Files:** `*.toon.json` (e.g. `lupo_contents.toon.json`, `lupo_federation_nodes.toon.json`).

TOONs define **table schema** (columns, types, indexes) for codegen, validation, and exports. They are **not** used in the URL routing or resolution chain.

### 2.2 Where TOONs are used (evidence)

- **AdminCsvExportHandler** (`lupo-includes/classes/AdminCsvExportHandler.php`): Reads TOON files from `LUPOPEDIA_PATH . '/lupo-database/lupopedia/toon'` (or similar), uses them to export table data to CSV (column names from TOON schema).
- **TOONParser** (`lupo-includes/classes/TOONParser.php`): Parses TOON content for schema/migration generation.
- **AgentClasses** (e.g. `lupo-includes/classes/AI/AgentClasses.php`): Can validate DB tables against TOON files (path `lupo-docs/toons` or similar).

No references in:

- `module-loader.php`
- `UrlResolver.php`
- `url_resolver.php`
- `content-controller.php`

So: **TOONs do not participate in routing or in URL→content resolution.** They are schema artifacts for DB/export/tooling.

### 2.3 Federation in TOONs

- **lupo_federation_nodes.toon.json** — defines `federation_node_id`, `node_base_url`, `active_theme_slug`, etc.
- **lupo_contents.toon.json** — defines `federation_node_id` on contents.
- **lupo_channel_content** — has TOON; table has `federation_node_id`, `file_path`, `web_path`.

These describe schema only; resolution logic does not read TOONs at runtime.

---

## 3. Router slug rules

### 3.1 Code location

**File:** `lupo-includes/modules/module-loader.php`  
**Line:** 177–179 (and 178 in context):

```php
// 4.0.18 T3 — Web path resolution (doctrine/qa/docs/flp, or canonical slug flare_apply). Check before lowercasing (paths are case-sensitive).
if ((preg_match('#^(doctrine|qa|docs|flp)/#i', $slug) || $slug === 'flare_apply') && function_exists('lupo_resolve_web_path')) {
    $resolved = lupo_resolve_web_path($slug);
```

So:

- **Pattern:** `^(doctrine|qa|docs|flp)/` (case-insensitive) **or** exact slug `flare_apply`.
- **Effect:** Only these slugs trigger the three-tier resolver (DB → CSV → filesystem). All other slugs skip this block and are lowercased later for other routes (auth, content by slug, help, list, crafty_syntax, etc.).

### 3.2 Configurability

- The pattern is **hardcoded** in `module-loader.php`. No env var or config file was found that alters this pattern (search for `doctrine|qa|docs|flp` and `lupo_resolve_web_path`).
- The only special case added via code is `$slug === 'flare_apply'` (4.0.57).

### 3.3 Implications for canonical URLs

- **To use the resolver (and thus DB/CSV/filesystem tiers), a slug must:**
  - Start with `doctrine/`, `qa/`, `docs/`, or `flp/`, **or**
  - Be exactly `flare_apply`.
- So:
  - **`/flare_apply`** — works (explicit exception).
  - **`/FLARE`** — does **not** match; resolver not run; later handled as content-by-slug (e.g. `flare`) and 404 if no lupo_contents row.
  - **`/docs/doctrine/FLARE/FLARE_APPLY`** — would run resolver with slug `docs/doctrine/FLARE/FLARE_APPLY` (Tier 1 could match `file_path_from_root` or `custom_path` if seeded).
- **Canonical doc URLs** that should be resolvable via the three-tier path should use one of these prefixes (e.g. `docs/...` or `doctrine/...`) or be explicitly added like `flare_apply`.

---

## 4. Summary

| Topic | Finding |
|-------|--------|
| **file_path_from_root → filesystem** | `LUPOPEDIA_ABSPATH . '/' . file_path_from_root` in content body loading; UrlResolver Tier 3 uses `repo_root` + `docs/<path>.md` or `<path>.md` only. |
| **Node-specific paths** | Not used in resolver or content body loading; `lupo-database/files/<node_id>/` is doc-only/future. |
| **TOON role** | Schema/export/validation only; no use in routing or URL resolution. |
| **Router slug rule** | Hardcoded in `module-loader.php`: `^(doctrine|qa|docs|flp)/` or `flare_apply`. Not configurable via env/config. |
| **Canonical URL shape** | Use `docs/...`, `doctrine/...`, `qa/...`, or `flp/...`, or add an explicit slug exception (e.g. `flare_apply`) for resolver-based URLs. |
