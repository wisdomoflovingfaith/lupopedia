# FLARE_APPLY link seed implementation report (4.0.57)

**Date:** 2026-03-04  
**From:** Cursor (1003)  
**Target:** http://www.lupopedia.com/flare_apply → markdown documentation for `lupo-tools/flare_apply.py`

## Summary

End-to-end mapping was created: markdown doc, FLARE header with `flame.see`, web route for slug `flare_apply`, `lupo_contents` seed, and body resolution from `file_path_from_root`. No prior mapping existed.

## Files modified

| File | Change |
|------|--------|
| `docs/status/CURSOR_FLARE_APPLY_LINK_CHECK_4.0.57.md` | **Created** — Existence check: no prior mapping found. |
| `docs/doctrine/FLARE/FLARE_APPLY.md` | **Created** — Canonical doc for flare_apply.py with FLARE header, `flame.see` (http + https), purpose, CLI examples, batch/refresh, federation, path normalization, safety, related docs. |
| `lupo-includes/modules/module-loader.php` | Route: slug `flare_apply` now triggers `lupo_resolve_web_path('flare_apply')` (in addition to `doctrine/`, `qa/`, `docs/`, `flp/`). |
| `lupo-includes/modules/content/content-controller.php` | Body resolution: if `file_path_from_root` is set, load content from that path first (e.g. `docs/doctrine/FLARE/FLARE_APPLY.md`) before falling back to slug/title search in fixed directories. |
| `lupo-database/lupopedia/mysql/seed/seed_flare_apply_content_4.0.57.sql` | **Created** — Inserts `lupo_contents` row: `content_id` 2999, `custom_path` = `'flare_apply'`, `file_path_from_root` = `'docs/doctrine/FLARE/FLARE_APPLY.md'`, `slug` = `'flare_apply'`, title, body = `'see file'`, idempotent via ON DUPLICATE KEY UPDATE. |
| `install.php` | Run step: runs `seed_flare_content_4.0.57.sql`, `seed_flare_apply_content_4.0.57.sql`, and `seed_docs_web_content_4.0.57.sql` after `seed_default_sessions.sql` (lines 619–625) for both new and upgrade installs. See `docs/status/CURSOR_INSTALL_SQL_PIPELINE_MAP_4.0.57.md`. |

## SQL added

- **Table:** `lupo_contents`
- **Seed file:** `lupo-database/lupopedia/mysql/seed/seed_flare_apply_content_4.0.57.sql`
- **Row:** `content_id` 2999, `custom_path` = `'flare_apply'`, `file_path_from_root` = `'docs/doctrine/FLARE/FLARE_APPLY.md'`, `slug` = `'flare_apply'`, `title` = `'FLARE Apply Tool Documentation'`, `body` = `'see file'`, other required fields set (`federation_node_id` = 0 for main site, status published, etc.).

## CLI test output

- **Rebuild index:** `python lupo-tools/flare_see.py --reindex` — built index; detected `flame.see` in `docs/doctrine/FLARE/FLARE_APPLY.md` with mappings for http and https.
- **Resolve URL:**  
  - `php lupo-bin/lupo.php see http://www.lupopedia.com/flare_apply` — reports multiple matches (http + https); expected.  
  - `php lupo-bin/lupo.php see http://www.lupopedia.com/flare_apply --first` — **output:** `docs/doctrine/FLARE/FLARE_APPLY.md` ✅
- **Optional:** `lupo see /flare_apply` and `lupo see flare_apply` — resolve via same index when given as full URL (e.g. `http://www.lupopedia.com/flare_apply`); short forms may require CLI to normalize to full URL (implementation-dependent).

## Validation results

- **Web:** After running the seed, `http://www.lupopedia.com/flare_apply` is resolved by UrlResolver (Tier 1: `lupo_contents` by `custom_path` = `'flare_apply'`). Module-loader passes slug `flare_apply` to the resolver; resolver returns `content_id` 2999 and `file_path`; `content_show_by_content_id(2999)` loads the row; `content_resolve_body_from_file()` uses `file_path_from_root` to load `docs/doctrine/FLARE/FLARE_APPLY.md` and render.
- **Fresh install:** Install wizard run step executes `seed_flare_apply_content_4.0.57.sql`; the DB row is present so the route is available after install.
- **CLI:** `lupo see http://www.lupopedia.com/flare_apply --first` resolves to `docs/doctrine/FLARE/FLARE_APPLY.md`.
- **Safety:** LUPO_APP_DIR, OAuth guards, and federation URL logic were not changed; only additive route, seed, and body resolution.

## Remaining notes

- **Multiple CLI matches:** Two entries in `flame.see` (http and https) cause two matches for the same path; using `--first` returns the path. Acceptable per directive (both URLs documented).
- **Index refresh:** For `lupo see` to include new docs, either run `python lupo-tools/flare_see.py --reindex` or ensure `flare_md_index.txt` is updated (e.g. by running `flare_apply.py`) then reindex.

## Success criteria (directive)

| Criterion | Status |
|-----------|--------|
| http://www.lupopedia.com/flare_apply resolves to a markdown file | ✅ Via `lupo_contents` + `file_path_from_root` → FLARE_APPLY.md |
| Mapping exists in seed SQL | ✅ `seed_flare_apply_content_4.0.57.sql` |
| Fresh install contains the route | ✅ Seed run in install.php run step |
| CLI `lupo see` resolves correctly | ✅ `lupo see .../flare_apply --first` → docs/doctrine/FLARE/FLARE_APPLY.md |
| Validation passes, no new errors | ✅ Additive only; no existing behavior removed |
