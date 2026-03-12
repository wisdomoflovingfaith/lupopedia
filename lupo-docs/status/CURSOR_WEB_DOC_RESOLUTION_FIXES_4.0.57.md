# Cursor web doc resolution fixes (4.0.57)

**Date:** 2026-03-04  
**From:** Cursor (1003)  
**Directive:** Captain Wolfie — Make web docs resolvable (FLARE + docs/status) and reconcile federation node semantics.

---

## 1. What was broken

### 1.1 `/FLARE` did not resolve

- **Request:** `http://www.lupopedia.com/FLARE`
- **Slug:** `FLARE` (then lowercased to `flare` for content routes).
- **Router:** Slug does not match resolver gate `^(doctrine|qa|docs|flp)/` or `flare_apply`, so the three-tier resolver is never invoked. Routing falls through to the content route, which calls `content_handle_slug('flare')` → `content_get_by_slug('flare')`.
- **Problem:** There was no `lupo_contents` row with `slug = 'flare'`. The existing `lupo_channel_content` seed row for FLARE is not used by the URL resolver or content-by-slug path.
- **Outcome:** 404 (or empty content) on fresh install.

### 1.2 Tier-3 filesystem hits did not render

- **Request:** e.g. `http://www.lupopedia.com/docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57`
- **Resolver:** Can find the file via Tier 3 (filesystem), but returns `content_id = 0` for Tier-3-only matches.
- **Loader:** `content_show_by_content_id()` is only used when `content_id > 0`, so Tier-3-only hits never produce a rendered page.
- **Outcome:** docs under `docs/status/` (and similar) did not render unless seeded into `lupo_contents`.

### 1.3 Federation node and terminology

- **Seeds:** `flare_apply` and new doc content used `federation_node_id = 1`; main site (www.lupopedia.com) is node **0**.
- **Schema:** `lupo_contents.federation_node_id` default in install is `1`; theme/default node is `0`. Inconsistent for main-site content.
- **Terminology:** Mixed use of `federated_node_id` vs `federation_node_id` in docs; schema and code use `federation_node_id`.

---

## 2. What was changed

### 2.1 Fix `/FLARE` (minimal, no new slug exceptions)

- **Approach:** Add a canonical `lupo_contents` row so the existing content-by-slug path serves FLARE.
- **Slug:** `flare` (URL `/FLARE` is lowercased to `flare` before `content_handle_slug`).
- **File:** `lupo-database/lupopedia/channels/lupo-channels/42/content/federation_node_id/0/FLARE.md` (existing channel doc).
- **Implementation:**
  - New seed: `lupo-database/lupopedia/mysql/seed/seed_flare_content_4.0.57.sql`
  - Inserts `content_id = 2998`, `slug = 'flare'`, `custom_path = 'FLARE'`, `file_path_from_root = 'lupo-database/lupopedia/channels/lupo-channels/42/content/federation_node_id/0/FLARE.md'`, `federation_node_id = 0`.
  - Idempotent: `ON DUPLICATE KEY UPDATE`.
- **Install:** `install.php` run step now runs `seed_flare_content_4.0.57.sql` after `seed_default_sessions.sql` and before `seed_flare_apply_content_4.0.57.sql`.

### 2.2 Option A — DB-seeded web docs (docs/status)

- **Policy:** Web-visible docs are backed by `lupo_contents` rows so resolver Tier 1 finds them and `content_id > 0` for rendering. No change to Tier-3 rendering logic.
- **Implementation:**
  - New seed: `lupo-database/lupopedia/mysql/seed/seed_docs_web_content_4.0.57.sql`
  - Inserts one representative row: `docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57` → `file_path_from_root = 'docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57.md'`, `custom_path = 'docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57'`, `content_id = 2997`, `federation_node_id = 0`.
  - Idempotent: `ON DUPLICATE KEY UPDATE`.
- **Install:** Same run step runs `seed_docs_web_content_4.0.57.sql` after the flare_apply seed.
- **Extensibility:** Additional docs (more `docs/status/*.md`, `docs/doctrine/**/*.md`) can be added via more seed rows or a script that generates INSERTs from the filesystem.

### 2.3 Federation node reconciliation

- **Default for main-site content:** All new/updated seeds use `federation_node_id = 0` (www.lupopedia.com = main site).
- **Changes:**
  - `seed_flare_content_4.0.57.sql`: `federation_node_id = 0`.
  - `seed_flare_apply_content_4.0.57.sql`: `federation_node_id` changed from `1` to `0`; `ON DUPLICATE KEY UPDATE` now updates `federation_node_id`.
  - `seed_docs_web_content_4.0.57.sql`: `federation_node_id = 0`.
- **Schema default:** Not changed in this patch. Install SQL still has `lupo_contents.federation_node_id` default `1`; seeds explicitly set `0` for canonical doc content. A future migration could set the column default to `0` if desired.

### 2.4 Terminology

- **Standard:** Use `federation_node_id` everywhere when referring to the schema column and node semantics.
- **Updated:** `docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57.md` — two instances of `federated_node_id` in headings/text replaced with `federation_node_id`. (FLIP/FLARE header atoms and external tools were not changed.)

---

## 3. Why it’s safe

- **No new resolver slug exceptions:** `/FLARE` is served via the existing content-by-slug path and a new row; no change to the resolver gate in `module-loader.php`.
- **No Tier-3 rendering code:** Option A only adds DB rows; no new code path for rendering files without a content row, so no new directory-traversal or path-validation surface.
- **Idempotent seeds:** All new/updated seeds use `ON DUPLICATE KEY UPDATE`; re-run on upgrade or re-install is safe.
- **LUPO_APP_DIR / OAuth:** Not modified.
- **Content IDs:** 2997, 2998, 2999 are reserved for these seeds; no collision with existing install/seed IDs.

---

## 4. Before / after behavior

### 4.1 `/FLARE`

| Step        | Before (clean seeded DB) | After |
|------------|---------------------------|--------|
| Request    | `GET /FLARE`              | `GET /FLARE` |
| Slug       | `flare` (after lowercasing)| `flare` |
| Resolver   | Not invoked               | Not invoked |
| Content    | `content_get_by_slug('flare')` → null | `content_get_by_slug('flare')` → row 2998 |
| Body       | —                         | From `file_path_from_root` → FLARE.md |
| Outcome    | 404 / no content          | FLARE doc rendered |

### 4.2 `docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57`

| Step        | Before                    | After |
|------------|----------------------------|--------|
| Request    | `GET /docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57` | Same |
| Slug       | `docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57`     | Same |
| Resolver   | Invoked; Tier 1 no row → Tier 3 may find file but `content_id = 0` | Tier 1 finds row (custom_path match) |
| content_id | 0 → not rendered           | 2997 → rendered |
| Body       | —                         | From `file_path_from_root` → CURSOR_FLARE_ROUTING_AUDIT_4.0.57.md |
| Outcome    | 404 / Smart 404            | Audit report rendered |

### 4.3 `/flare_apply` (unchanged behavior, corrected node)

- Still resolved via resolver exception and existing seed row.
- Row now has `federation_node_id = 0` instead of `1` for consistency with main site.

---

## 5. Files touched

| File | Change |
|------|--------|
| `lupo-database/lupopedia/mysql/seed/seed_flare_content_4.0.57.sql` | New: FLARE content row (slug `flare`, content_id 2998, node 0). |
| `lupo-database/lupopedia/mysql/seed/seed_flare_apply_content_4.0.57.sql` | `federation_node_id` 1 → 0; ON DUPLICATE updates node. |
| `lupo-database/lupopedia/mysql/seed/seed_docs_web_content_4.0.57.sql` | New: one docs/status row (content_id 2997, node 0). |
| `install.php` | Run step: run `seed_flare_content_4.0.57.sql`, then `seed_docs_web_content_4.0.57.sql` after existing flare_apply seed. |
| `docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57.md` | Terminology: `federated_node_id` → `federation_node_id` (2 places). |
| `docs/status/CURSOR_WEB_DOC_RESOLUTION_FIXES_4.0.57.md` | This report. |

---

## 6. Validation (how to confirm)

1. **Fresh install:** Run installer through “Run installation”; ensure all three seed files run without error.
2. **/FLARE:** Open `http://<base>/FLARE` (or `/flare`); expect FLARE doc body from channel 42 FLARE.md.
3. **docs/status:** Open `http://<base>/docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57`; expect audit report body.
4. **/flare_apply:** Open `http://<base>/flare_apply`; expect FLARE Apply doc (unchanged behavior).
5. **DB:** `SELECT content_id, slug, custom_path, federation_node_id FROM lupo_contents WHERE content_id IN (2997, 2998, 2999);` — expect three rows, each with `federation_node_id = 0`.

---

## 7. Node defaults (evidence)

- **install_new_lupopedia.sql:** `lupo_contents.federation_node_id` is `DEFAULT 1` (or equivalent). Other tables (e.g. `lupo_channel_content`) use `federation_node_id` with varying defaults (0 or 1).
- **lupo_federation_nodes:** Defines node 0 (and typically 1); node 0 = www.lupopedia.com, node 1 = localhost in this install.
- **Decision:** Canonical doc content for the main site uses `federation_node_id = 0` in seeds; schema default left as-is for this patch.

---

**Success criteria (directive):**

- `/FLARE` resolves on fresh install.
- At least one `docs/status/...` URL renders (CURSOR_FLARE_ROUTING_AUDIT_4.0.57).
- Node defaults aligned in seeds (`federation_node_id = 0` for new doc content).
- Terminology standardized to `federation_node_id` in updated docs.
