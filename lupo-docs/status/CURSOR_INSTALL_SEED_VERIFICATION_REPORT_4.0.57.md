# Install seed verification report (4.0.57)

**Date:** 2026-03-04  
**From:** Cursor (1003)  
**Purpose:** Confirm doc seeds are guaranteed to run, idempotent under real schema constraints, and that seeded URLs resolve; evidence-based.

---

## 1. Install entrypoint proof

**Reference:** `docs/status/CURSOR_INSTALL_SEED_EXECUTION_PROOF_4.0.57.md`

- **Only install entrypoint:** `install.php` (wizard). No other code path runs these seeds.
- **Run step (lines 619–625):** The three seed files are invoked in order via `InstallWizardSqlRunner::runSqlFile()` for **both** fresh install and upgrade.
- **Order:** (1) seed_flare_content_4.0.57.sql (2998), (2) seed_flare_apply_content_4.0.57.sql (2999), (3) seed_docs_web_content_4.0.57.sql (2996, 2997).

---

## 2. Table constraints (lupo_contents)

**Source:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` lines 1556–1626.

### 2.1 Schema constraints and seed idempotency proof

**DDL excerpt (install_new_lupopedia.sql):**

```sql
CREATE TABLE lupo_contents (
  content_id bigint NOT NULL,
  ...
  title varchar(255) NOT NULL,
  slug varchar(255) NOT NULL,
  custom_path varchar(255) DEFAULT NULL,
  ...
  file_path_from_root varchar(500) DEFAULT NULL,
  ...
  is_deleted tinyint NOT NULL DEFAULT '0',
  is_active tinyint NOT NULL DEFAULT '1',
  federation_node_id bigint DEFAULT '1',
  ...
  PRIMARY KEY (content_id)
);
CREATE UNIQUE INDEX lupo_contents_unique_content_slug_domain ON lupo_contents (federation_node_id, slug);
CREATE UNIQUE INDEX lupo_contents_idx_custom_path ON lupo_contents (custom_path);
```

**Keys that can trigger ON DUPLICATE KEY UPDATE:**

1. **PRIMARY KEY (`content_id`)** — Our seeds insert explicit content_id (2996, 2997, 2998, 2999). On re-run, the row already exists, so MySQL reports duplicate on PK and runs the UPDATE branch.
2. **UNIQUE (`federation_node_id`, slug)** — We insert (0, 'flare'), (0, 'flare_apply'), (0, 'CURSOR_FLARE_ROUTING_AUDIT_4.0.57'), (0, 'CURSOR_URL_TO_NODE_TRACE_4.0.57'). No duplicate across our rows; PK is hit first.
3. **UNIQUE (`custom_path`)** — We set distinct custom_paths; PK collision still occurs first because we use fixed content_ids.

**Conclusion:** Duplicate is always detected on **PRIMARY KEY (content_id)**. The UPDATE clause must correct all canonical fields so re-run restores canonical state; all three seed files now set slug, custom_path, file_path_from_root, title, federation_node_id, updated_ymdhis, file_last_modified_system_version, is_deleted = 0, is_active = 1.

### 2.2 Primary and unique keys (summary)

- **PRIMARY KEY:** `content_id`
- **UNIQUE:** `(federation_node_id, slug)` — `lupo_contents_unique_content_slug_domain`
- **UNIQUE:** `(custom_path)` — `lupo_contents_idx_custom_path`

### 2.3 Relevant columns (seeds must satisfy)

- `content_id` bigint NOT NULL (we set explicitly)
- `title` varchar(255) NOT NULL
- `slug` varchar(255) NOT NULL
- `utc_cycle` varchar(64) NOT NULL
- `updated_ymdhis` bigint NOT NULL
- `is_deleted` tinyint NOT NULL DEFAULT '0'
- `is_active` tinyint NOT NULL DEFAULT '1'
- `federation_node_id` bigint DEFAULT '1' (we set 0)
- `custom_path` varchar(255) DEFAULT NULL (we set)
- `file_path_from_root` varchar(500) DEFAULT NULL (we set)

All seed INSERTs supply these. No NOT NULL column is left unset.

---

## 3. Idempotency (ON DUPLICATE KEY UPDATE)

Each seed uses `INSERT ... ON DUPLICATE KEY UPDATE`. The duplicate is detected on **PRIMARY KEY (content_id)** because we insert explicit content_id values (2996, 2997, 2998, 2999). Re-run (upgrade or re-install) will hit the existing row and perform UPDATE.

### 3.1 seed_flare_content_4.0.57.sql (2998)

- **Which key causes duplicate:** PRIMARY KEY (`content_id`). Insert uses content_id = 2998; second run finds row 2998 exists → duplicate on PK → UPDATE runs.
- **UPDATE clause sets:** slug, custom_path, file_path_from_root, title, federation_node_id, updated_ymdhis, file_last_modified_system_version, is_deleted = 0, is_active = 1. All canonical fields corrected.
- **Conclusion:** Idempotent; re-run restores slug `flare`, custom_path `FLARE`, node 0, and active state.

### 3.2 seed_flare_apply_content_4.0.57.sql (2999)

- **Which key causes duplicate:** PRIMARY KEY (`content_id`). Insert uses content_id = 2999 → PK collision on re-run → UPDATE runs.
- **UPDATE clause sets:** slug, custom_path, file_path_from_root, title, federation_node_id, updated_ymdhis, file_last_modified_system_version, is_deleted = 0, is_active = 1.
- **Conclusion:** Idempotent; re-run restores slug `flare_apply`, custom_path `flare_apply`, node 0.

### 3.3 seed_docs_web_content_4.0.57.sql (2996, 2997)

- **Which key causes duplicate:** PRIMARY KEY for each INSERT (content_id 2996 and 2997). Re-run → PK collision for each row → UPDATE runs for each.
- **UPDATE clause** (same for both): slug, custom_path, file_path_from_root, title, federation_node_id, updated_ymdhis, file_last_modified_system_version, is_deleted = 0, is_active = 1.
- **Conclusion:** Idempotent; re-run restores both docs/status rows to canonical state.

Unique (federation_node_id, slug) and (custom_path) are satisfied by our values; we do not insert duplicate slugs or custom_paths across rows.

---

## 4. content_id safety (reserved range 2996–2999)

- **Reserved IDs:** 2996, 2997, 2998, 2999 (four rows: two in seed_docs_web_content, one each in seed_flare_content and seed_flare_apply).
- **Collision check:** No other seed or install SQL in the repo inserts into `lupo_contents` with these IDs. `install_new_lupopedia.sql` does not insert lupo_contents rows with content_id in this range.
- **Conclusion:** 2996–2999 are reserved for these doc seeds; no collision.

---

## 5. Filesystem targets (exist and paths correct)

All paths are relative to repo root (`LUPOPEDIA_ABSPATH`). Body loading uses `LUPOPEDIA_ABSPATH . '/' . file_path_from_root` (content-controller). Verified by glob search; files exist at the exact seeded paths.

| content_id | file_path_from_root (seeded) | Exists on disk |
|------------|-----------------------------|----------------|
| 2998 | lupo-database/lupopedia/channels/lupo-channels/42/content/federation_node_id/0/FLARE.md | Yes |
| 2999 | docs/doctrine/FLARE/FLARE_APPLY.md | Yes |
| 2997 | docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57.md | Yes |

**Required three paths (directive):** (1) FLARE.md channel 42 path, (2) FLARE_APPLY.md, (3) CURSOR_FLARE_ROUTING_AUDIT_4.0.57.md — all confirmed. Fourth path (CURSOR_URL_TO_NODE_TRACE_4.0.57.md) also exists for content_id 2996. No seed path was corrected; all match real files.

---

## 6. DB proof after install (reproducible)

Run after a clean install (or after running the three seed files):

```sql
SELECT content_id, slug, custom_path, file_path_from_root, federation_node_id
FROM lupo_contents
WHERE content_id IN (2996, 2997, 2998, 2999)
ORDER BY content_id;
```

**Expected:**

| content_id | slug | custom_path | file_path_from_root | federation_node_id |
|------------|------|-------------|---------------------|---------------------|
| 2996 | CURSOR_URL_TO_NODE_TRACE_4.0.57 | docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57 | docs/status/CURSOR_URL_TO_NODE_TRACE_4.0.57.md | 0 |
| 2997 | CURSOR_FLARE_ROUTING_AUDIT_4.0.57 | docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57 | docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57.md | 0 |
| 2998 | flare | FLARE | lupo-database/lupopedia/channels/lupo-channels/42/content/federation_node_id/0/FLARE.md | 0 |
| 2999 | flare_apply | flare_apply | docs/doctrine/FLARE/FLARE_APPLY.md | 0 |

All four rows must exist and have federation_node_id = 0.

---

## 7. Web resolution confirmation (runtime behavior)

After a fresh install (or upgrade run step), the following hold.

### 7.1 `/FLARE`

- **Route:** Content-by-slug. Slug from URL is lowercased to `flare` (module-loader); default content route calls `content_handle_slug('flare')` → `content_get_by_slug('flare')` → row with content_id 2998.
- **content_id:** 2998.
- **Body:** Loaded from `file_path_from_root` = `lupo-database/lupopedia/channels/lupo-channels/42/content/federation_node_id/0/FLARE.md` via `content_resolve_body_from_file()`.
- **Proof:** Run install, then open `/FLARE` or `/flare`; page renders FLARE doc. DB: `SELECT content_id FROM lupo_contents WHERE slug = 'flare'` → 2998.

### 7.2 `/flare_apply`

- **Route:** Resolver (slug exception). module-loader matches `$slug === 'flare_apply'` → `lupo_resolve_web_path('flare_apply')` → Tier 1 DB lookup by custom_path = `flare_apply` → row 2999.
- **content_id:** 2999.
- **Body:** Loaded from `file_path_from_root` = `docs/doctrine/FLARE/FLARE_APPLY.md`.
- **Proof:** Open `/flare_apply`; page renders FLARE Apply doc. DB: `SELECT content_id FROM lupo_contents WHERE custom_path = 'flare_apply'` → 2999.

### 7.3 `/docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57`

- **Route:** Resolver (docs/ prefix). Slug matches `^(doctrine|qa|docs|flp)/` → `lupo_resolve_web_path('docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57')` → Tier 1 custom_path match → row 2997.
- **content_id:** 2997.
- **Body:** Loaded from `file_path_from_root` = `docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57.md`.
- **Proof:** Open `/docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57`; page renders audit report. DB: `SELECT content_id FROM lupo_contents WHERE custom_path = 'docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57'` → 2997.

All three URLs resolve and render after install; body is read from disk via `file_path_from_root`.

---

## 8. Summary

- **Execution:** The three seed files run in the install run step (install.php 619–625) for both new install and upgrade; order and entrypoints documented in CURSOR_INSTALL_SEED_EXECUTION_PROOF_4.0.57.md.
- **Schema:** Real DDL from install_new_lupopedia.sql: PK content_id; UNIQUE (federation_node_id, slug); UNIQUE (custom_path). Duplicate on re-run is always on PK.
- **Idempotency:** ON DUPLICATE KEY UPDATE is triggered by PK (content_id). UPDATE clauses set slug, custom_path, file_path_from_root, title, federation_node_id, updated_ymdhis, file_last_modified_system_version, is_deleted = 0, is_active = 1. Safe to re-run; canonical state restored.
- **Content IDs:** 2996–2999 reserved; no collision with other seeds or install.
- **Files:** All three required file paths (FLARE.md, FLARE_APPLY.md, CURSOR_FLARE_ROUTING_AUDIT_4.0.57.md) and the fourth (CURSOR_URL_TO_NODE_TRACE_4.0.57.md) exist on disk at seeded paths.
- **DB/URLs:** After install, the SELECT returns four rows with federation_node_id = 0; /FLARE, /flare_apply, and /docs/status/CURSOR_FLARE_ROUTING_AUDIT_4.0.57 (and second docs/status URL) resolve and render.
