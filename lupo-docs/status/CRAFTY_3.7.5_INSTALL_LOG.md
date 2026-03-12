---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/status/CRAFTY_3.7.5_INSTALL_LOG.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "log"
  purpose: "Log for installing Crafty Syntax 3.7.5 baseline before Lupopedia upgrade"
  tags: ["crafty", "install", "baseline", "4.0.56"]
  lupo_agent: "cursor"
---

# Crafty Syntax 3.7.5 Base Install Log

**Date:** 2026-03-03  
**Actor:** Cursor (1003)  
**Purpose:** Document steps to set up a fresh Crafty Syntax 3.7.5 baseline used for upgrading to Lupopedia v4.0.56.

---

## 1. Prerequisites

- **Database:** MySQL 8.0+ / MariaDB 10.5+ (e.g. ServBay).
- **Web server:** Apache or Nginx pointing at project root (subdirectory install, e.g. `http://localhost/lupopedia/`).
- **PHP:** 5.3+ with pdo_mysql, json.

---

## 2. Baseline SQL (Crafty 3.7.5 schema)

The canonical Crafty 3.7.5 baseline is **not** run by `install.php`. It must be loaded manually to create the ~34 `livehelp_*` tables that the upgrade wizard detects.

| Step | Action | Path / Command |
|------|--------|----------------|
| 1 | Create database (if needed) | `CREATE DATABASE lupopedia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;` |
| 2 | Load Crafty 3.7.5 baseline | `mysql -u <user> -p lupopedia < lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql` |
| 3 | Verify tables | `SHOW TABLES LIKE 'livehelp_%';` — expect ~34 tables |

**Canonical path for baseline SQL:**  
`lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql`

*(Legacy reference: repo may contain `legacy/craftysyntax/` for reference only; the installer uses the SQL in `lupo-database/.../mysql/import/`.)*

---

## 3. Verify baseline via web (optional)

- Ensure `lupopedia-config.php` (or equivalent) points to the database that now has `livehelp_*` tables.
- Open the Lupopedia installer: `http://<host>/<lupopedia_subdir>/install.php`.
- **Expected:** Wizard detects existing `livehelp_*` tables and shows **Upgrade from Crafty Syntax 3.7.5** path (do not run full upgrade yet if only logging this step).
- **Crafty legacy admin:** If the app exposes legacy live chat admin (e.g. `admin.php`), verify login and basic live chat/admin features work against the baseline DB.

---

## 4. Log entries (fill when run)

| Step | Completed (Y/N) | Notes / Errors |
|------|-----------------|----------------|
| Create DB | | |
| Load old_crafty_syntax_3_7_5_start.sql | | |
| SHOW TABLES livehelp_% | | Count: ___ |
| Web install.php detection | | Upgrade path shown: Y/N |
| Legacy admin (live chat) check | | |

---

## 5. Timestamp and actor

- **Log created:** 2026-03-03  
- **Actor ID:** 1003 (Cursor IDE Agent)  
- **System version:** 4.0.56  

---

*End of log.*
