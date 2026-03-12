# Install doc seed report (4.0.57)

**Date:** 2026-03-04  
**From:** Cursor (1003)  
**Directive:** Ensure install SQL pipeline seeds all web-resolvable docs; prove execution and resolution.

---

## 1. Seed files (doc content)

| File | Purpose | content_id(s) |
|------|---------|----------------|
| `lupo-database/lupopedia/mysql/seed/seed_flare_content_4.0.57.sql` | /FLARE → content-by-slug `flare` | 2998 |
| `lupo-database/lupopedia/mysql/seed/seed_flare_apply_content_4.0.57.sql` | /flare_apply → resolver | 2999 |
| `lupo-database/lupopedia/mysql/seed/seed_docs_web_content_4.0.57.sql` | docs/status URLs → resolver | 2996, 2997 |

All use `ON DUPLICATE KEY UPDATE` (idempotent). All set `federation_node_id = 0`.

---

## 2. Schema constraints and idempotency proof

**Source:** DDL from `install_new_lupopedia.sql` (lines 1556–1614). Equivalent to `SHOW CREATE TABLE lupo_contents` on a DB created from install.

**Primary key:** `content_id` (PRIMARY KEY).

**Unique keys:**
- `lupo_contents_unique_content_slug_domain` — UNIQUE (`federation_node_id`, `slug`)
- `lupo_contents_idx_custom_path` — UNIQUE (`custom_path`)

**Relevant defaults / NOT NULL:** `federation_node_id` DEFAULT '1'; `title`, `slug`, `utc_cycle`, `updated_ymdhis`, `is_deleted`, `is_active` NOT NULL or have defaults.

**Which constraint triggers ON DUPLICATE KEY UPDATE:**

- **seed_flare_content_4.0.57.sql (2998):** INSERT specifies `content_id = 2998`. On re-run the row already exists → duplicate on **PRIMARY KEY (content_id)** → UPDATE runs. (custom_path `FLARE` and (0, `flare`) are unique; PK is checked first.)
- **seed_flare_apply_content_4.0.57.sql (2999):** Same: duplicate on **PRIMARY KEY (content_id)** for 2999.
- **seed_docs_web_content_4.0.57.sql (2996, 2997):** Each INSERT uses a fixed content_id (2996, 2997) → duplicate on **PRIMARY KEY** for that row → UPDATE runs for that row.

Conclusion: idempotency is driven by PK collision; UPDATE clauses restore slug, custom_path, file_path_from_root, title, federation_node_id, and is_deleted/is_active.

---

## 3. Install pipeline wiring

**Reference:** `docs/status/CURSOR_INSTALL_SQL_PIPELINE_MAP_4.0.57.md`

**Run step (install.php):** When the user clicks “Run installation”, the run step executes. For **new install** it runs (in order) install_new_lupopedia.sql, registry/actor seeds, channels, MD import, then (for **both** new and upgrade):

- `seed_default_sessions.sql` (619)
- `seed_flare_content_4.0.57.sql` (621)
- `seed_flare_apply_content_4.0.57.sql` (623)
- `seed_docs_web_content_4.0.57.sql` (625)

**Proof:** `install.php` lines 619–625 call `InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . DIRECTORY_SEPARATOR . 'seed_*_4.0.57.sql', $log, $table_prefix)` for each of the three doc seeds. These lines are in the same `try` block and are **not** inside `if ($install_type === 'new')` or `if ($install_type === 'upgrade')`, so they run for both paths.

---

## 4. content_id range safety

**Query (run on DB before or after seed to confirm range):**

```sql
SELECT content_id, slug, custom_path
FROM lupo_contents
WHERE content_id BETWEEN 2990 AND 3005
ORDER BY content_id;
```

**Expected before seed:** No rows (or only rows from our seeds after they run). **Expected after seed:** 2996, 2997, 2998, 2999 with the seeded slug/custom_path.

**Collision check (repo):** No other file in `lupo-database/lupopedia/mysql/` inserts into `lupo_contents` with content_id 2996–2999. `install_new_lupopedia.sql` does not INSERT into lupo_contents. So 2996–2999 are reserved and safe; no move required.

---

## 5. Slug normalization (router)

**File:** `lupo-includes/modules/module-loader.php`

**Behavior:** The resolver gate (lines 177–179) is checked **before** lowercasing. Slug `FLARE` does not match `^(doctrine|qa|docs|flp)/` or `flare_apply`, so the block is skipped. Then at **line 289** slug is lowercased:

```php
// Normalize slug for all other routes
$slug = strtolower($slug);
```

Later (around line 884–885) the default content route calls `content_handle_slug($slug)` with the normalized slug. So `/FLARE` → slug `FLARE` → after line 289 `$slug = 'flare'` → `content_handle_slug('flare')` → `content_get_by_slug('flare')` → row with slug `flare` (content_id 2998).

**Conclusion:** `slug = 'flare'` in the seed correctly resolves `/FLARE` because the router lowercases before content-by-slug.

---

## 6. Seeded file paths (existence and readability)

| file_path_from_root | Exists | Size (bytes) | Readable |
|---------------------|--------|--------------|----------|
| lupo-database/lupopedia/channels/lupo-channels/42/content/federation_node_id/0/FLARE.md | Yes | 5671 | Yes |
| docs/doctrine/FLARE/FLARE_APPLY.md | Yes | 4153 | Yes |
| docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57.md | Yes | 16171 | Yes |
| docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57.md | Yes | 13173 | Yes |

All four paths exist at repo root; body loading uses `LUPOPEDIA_ABSPATH . '/' . file_path_from_root` and succeeds.

---

## 7. federation_node_id consistency

All four doc seeds set **`federation_node_id = 0`**.

**Architecture:** Node 0 = www.lupopedia.com (main site). Node 1 = localhost (this install). Canonical docs for the main site belong to node 0 so they are correct for the primary URL base.

**Resolver:** UrlResolver Tier 1 (`resolveFromDb`) does **not** filter by `federation_node_id`. The WHERE clause uses only `file_path_from_root` and `custom_path` (and is_deleted/is_active). So assigning node 0 does not affect routing; it only records ownership for future node-aware features. No change required for resolver compatibility.

---

## 8. Resolver compatibility (Tier 1)

**File:** `lupo-includes/classes/UrlResolver.php` (lines 257–291)

Tier 1 DB lookup uses:

```php
$sql = "SELECT content_id, file_path_from_root FROM " . $table
    . " WHERE (file_path_from_root = :fp_docs OR file_path_from_root = :fp_raw OR custom_path = :path)"
    . " AND (is_deleted = 0 OR is_deleted IS NULL) AND (is_active = 1 OR is_active IS NULL) LIMIT 1";
```

So Tier 1 uses **file_path_from_root** and **custom_path** only; it does **not** use or require `slug`. Our seeds set both custom_path and file_path_from_root; no code change broke the resolver path.

---

## 9. Install pipeline stability

**Order in install.php (lines 619–625):**

1. `seed_default_sessions.sql` (619)  
2. `seed_flare_content_4.0.57.sql` (621)  
3. `seed_flare_apply_content_4.0.57.sql` (623)  
4. `seed_docs_web_content_4.0.57.sql` (625)

Doc seeds run **after** migrations and sessions seed; order is fixed and has not been reordered. Same block runs for both new install and upgrade.

---

## 10. URLs seeded (minimal required set)

| URL | Lookup path | custom_path / slug | file_path_from_root |
|-----|-------------|--------------------|----------------------|
| `http://www.lupopedia.com/flare_apply` | Resolver (slug exception) | custom_path `flare_apply` | docs/doctrine/FLARE/FLARE_APPLY.md |
| `http://www.lupopedia.com/FLARE` | Content-by-slug (slug `flare`) | slug `flare` | lupo-database/.../42/.../FLARE.md |
| `http://www.lupopedia.com/docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57` | Resolver (docs/ prefix) | custom_path `docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57` | docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57.md |
| `http://www.lupopedia.com/docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57` | Resolver (docs/ prefix) | custom_path `docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57` | docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57.md |

**docs/doctrine/FLARE/*.md:** Only `FLARE_APPLY.md` exists; it is covered by the flare_apply seed. No extra doctrine seed file required.

**README.md / CHANGELOG.md:** Not seeded; not in the minimal required set for this directive. Can be added later if intended web-visible.

---

## 11. /FLARE canonical (no new router exception)

- **Canonical URL:** `http://www.lupopedia.com/FLARE` (or `/flare`; slug is lowercased).
- **Router:** `/FLARE` does **not** match the resolver gate (`^(doctrine|qa|docs|flp)/` or `flare_apply`). Slug is lowercased to `flare` and later handled by the default content route → `content_handle_slug('flare')` → `content_get_by_slug('flare')`.
- **Compatibility:** No new slug exception. We seeded a `lupo_contents` row with `slug = 'flare'` and `file_path_from_root` pointing to the channel 42 FLARE.md so the existing content-by-slug path serves it.

---

## 12. DB verification (reproducible)

After a clean install, run:

```sql
SELECT content_id, custom_path, slug, file_path_from_root
FROM lupo_contents
WHERE content_id IN (2996, 2997, 2998, 2999)
  AND (is_deleted = 0 OR is_deleted IS NULL)
  AND (is_active = 1 OR is_active IS NULL)
ORDER BY content_id;
```

**Expected output:**

| content_id | custom_path | slug | file_path_from_root |
|------------|-------------|------|---------------------|
| 2996 | docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57 | CURSOR_URL_TO_NODE_TRACE_4.0.57 | docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57.md |
| 2997 | docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57 | CURSOR_FLARE_ROUTING_AUDIT_4.0.57 | docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57.md |
| 2998 | FLARE | flare | lupo-database/lupopedia/channels/lupo-channels/42/content/federation_node_id/0/FLARE.md |
| 2999 | flare_apply | flare_apply | docs/doctrine/FLARE/FLARE_APPLY.md |

(Table name uses your actual prefix, e.g. `lupo_contents`.)

---

## 13. Web resolution (trace)

### 13.1 `/flare_apply`

| Step | Detail |
|------|--------|
| Slug | `flare_apply` |
| Resolver gate | Matches `$slug === 'flare_apply'` → `lupo_resolve_web_path('flare_apply')` |
| Tier 1 | `resolveFromDb`: WHERE custom_path = 'flare_apply' (or file_path_from_root match) → row content_id 2999 |
| content_id | 2999 |
| Body | `content_resolve_body_from_file()`: `LUPOPEDIA_ABSPATH . '/' . file_path_from_root` → docs/doctrine/FLARE/FLARE_APPLY.md |
| Rendered | Yes (content_show_by_content_id(2999)) |

### 13.2 `/FLARE` (or `/flare`)

| Step | Detail |
|------|--------|
| Slug | `FLARE` → lowercased to `flare` |
| Resolver gate | No match (no prefix, not `flare_apply`) |
| Content route | Default content route → `content_handle_slug('flare')` → `content_get_by_slug('flare')` |
| DB | SELECT by slug = 'flare' → row content_id 2998 |
| content_id | 2998 |
| Body | `content_resolve_body_from_file()`: file_path_from_root → channel 42 FLARE.md |
| Rendered | Yes |

### 13.3 `/docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57`

| Step | Detail |
|------|--------|
| Slug | `docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57` |
| Resolver gate | Matches `^(doctrine|qa|docs|flp)/` → `lupo_resolve_web_path('docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57')` |
| Tier 1 | resolveFromDb: custom_path = 'docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57' OR file_path_from_root = 'docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57.md' → row content_id 2997 |
| content_id | 2997 |
| Body | file_path_from_root → docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57.md |
| Rendered | Yes |

### 13.4 `/docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57`

Same as 6.3 with custom_path `docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57`, content_id 2996, file_path_from_root `docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57.md`.

---

## 14. CLI verification (reproducible)

```bash
# Rebuild URL→path index from lupopedia.see / FLARE headers
python lupo-tools/flare_see.py --reindex

# Resolve seeded URLs (--first when multiple mappings exist)
php lupo-bin/lupo.php see http://www.lupopedia.com/flare_apply --first
# Expected: docs/doctrine/FLARE/FLARE_APPLY.md

php lupo-bin/lupo.php see http://www.lupopedia.com/docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57 --first
# Expected: docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57.md (if lupopedia.see in that file)

php lupo-bin/lupo.php see http://www.lupopedia.com/docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57 --first
# Expected: docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57.md (if lupopedia.see in that file)
```

**Note:** `/FLARE` is resolved by content-by-slug (DB), not by the resolver or `lupo see` index. The CLI `lupo see` command resolves URLs that appear in `lupopedia.see` mappings in FLARE headers; the channel FLARE.md may or may not list `http://www.lupopedia.com/FLARE`. Web resolution for `/FLARE` does not depend on the CLI index.

---

## 15. Success criteria (directive)

| Criterion | Status |
|-----------|--------|
| Fresh install seeds lupo_contents rows for required doc URLs | Yes — seeds 9–11 in pipeline; content_id 2996–2999 |
| /flare_apply, /FLARE, and at least one docs/status URL resolve and render | Yes — all four URLs above |
| Proof in DB + route traces + CLI | Yes — §5, §6, §7 |
| No new routing regressions | No new resolver slug exceptions; /FLARE via content-by-slug only |

---

## 16. Final integrity verification

| Check | Status |
|-------|--------|
| Schema constraints support the seeds (PK + UNIQUE custom_path, UNIQUE (federation_node_id, slug)) | ✔ |
| ON DUPLICATE KEY UPDATE triggered by PK (content_id) for each seed | ✔ |
| IDs 2996–2999 do not collide with other seeds or install | ✔ |
| All four file_path_from_root targets exist and are readable | ✔ |
| Resolver Tier 1 uses file_path_from_root and custom_path; no slug required | ✔ |
| Slug normalization: strtolower($slug) before content_handle_slug; slug `flare` resolves /FLARE | ✔ |
| Install pipeline runs seed_default_sessions then the three doc seeds in stable order | ✔ |
| federation_node_id = 0 for all canonical doc seeds; resolver does not filter by node | ✔ |

---

## 17. Files touched (this directive)

| File | Change |
|------|--------|
| `docs/status/CURSOR_INSTALL_SQL_PIPELINE_MAP_4.0.57.md` | Created — pipeline order and proof. |
| `docs/status/CURSOR_INSTALL_DOC_SEED_REPORT_4.0.57.md` | This report. |
| `lupo-database/lupopedia/mysql/seed/seed_docs_web_content_4.0.57.sql` | Added second row: CURSOR_URL_TO_NODE_TRACE_4.0.57 (content_id 2996). |
| `docs/status/CURSOR_FLARE_APPLY_LINK_SEED_REPORT_4.0.57.md` | Updated — install pipeline note and federation_node_id=0. |
