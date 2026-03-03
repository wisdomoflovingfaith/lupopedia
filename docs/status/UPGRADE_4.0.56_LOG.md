---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "docs/status/UPGRADE_4.0.56_LOG.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "log"
  purpose: "Log for upgrading from Crafty 3.7.5 to Lupopedia v4.0.56"
  tags: ["upgrade", "4.0.56", "install", "cursor"]
  lupo_agent: "cursor"
---

# Upgrade to Lupopedia v4.0.56 — Process Log

**Date:** 2026-03-03  
**Actor:** Cursor (1003)  
**Purpose:** Document the upgrade process from Crafty Syntax 3.7.5 baseline to Lupopedia v4.0.56, including migrations, seed, and install.php.

---

## 1. Preconditions

- Crafty 3.7.5 baseline installed (see `docs/status/CRAFTY_3.7.5_INSTALL_LOG.md`).
- Database contains `livehelp_*` tables.
- `LUPO_MYSQL_DIR` points to `lupo-database/lupopedia/mysql/` (or `LUPO_DATABASE_DIR` set accordingly).

---

## 2. Upgrade flow (install.php)

The wizard runs in **upgrade mode** when it detects `livehelp_*` tables (or Crafty `config.php`).

### 2.1 Credentials step (bootstrap)

After DB credentials and table prefix are entered and validated, the wizard:

1. **Detects** `livehelp_*` tables → sets upgrade path.
2. **Runs bootstrap SQL** (same as new install):
   - `install/install_new_lupopedia.sql`
   - `seed/seed_registry_comprehensive_4.0.45.sql`
   - `seed/seed_registry_additional_csv_entities_4.0.45.sql`
   - `seed/seed_registry_open_4.0.45.sql`
   - `seed/seed_actors_agents_4.0.45.sql`
3. **Identity normalization** (upgrade only): validate unique emails in `livehelp_users`, apply normalization when admin confirms.

### 2.2 Run step (upgrade)

After normalization (if required) and confirmation:

| Order | Script | Purpose |
|-------|--------|---------|
| 1 | `install/install_new_lupopedia.sql` | Full Lupopedia schema (~179 tables) |
| 2 | `seed/seed_registry_comprehensive_4.0.45.sql` | Registry seed |
| 3 | `seed/seed_registry_additional_csv_entities_4.0.45.sql` | Additional registry |
| 4 | `seed/seed_registry_open_4.0.45.sql` | Open registry seed |
| 5 | `seed/seed_actors_agents_4.0.45.sql` | Actors and agents seed |
| 6 | `import/import_from_old_crafty_syntax.sql` | Migrate data from livehelp_* to lupo_* |
| 7 | (optional) `import/drop_old_crafty_syntax_tables.sql` | Drop livehelp_* if checkbox set |
| 8 | `migrations/anubis_queue_tables_4.0.53.sql` | ANUBIS queue tables |
| 9 | `migrations/20260301_anubis_database_primacy_updates.sql` | ANUBIS primacy updates |
| 10 | `seed/seed_default_sessions.sql` | Default sessions |

All paths relative to `LUPO_MYSQL_DIR` (e.g. `lupo-database/lupopedia/mysql/`).

---

## 3. Log entries (fill when run)

| Step | Completed (Y/N) | Notes / Errors |
|------|-----------------|----------------|
| install.php opened (upgrade detected) | | |
| Bootstrap (install + seed x4) | | |
| Identity normalization applied | | |
| import_from_old_crafty_syntax.sql | | |
| drop_old_crafty_syntax_tables.sql (if selected) | | |
| anubis_queue_tables_4.0.53.sql | | |
| 20260301_anubis_database_primacy_updates.sql | | |
| seed_default_sessions.sql | | |
| Config written (lupopedia-config.php) | | |
| Redirect to login | | |

---

## 4. Errors and resolutions

*(Document any errors encountered and how they were resolved.)*

| Error | Resolution |
|-------|------------|
| | |

---

## 5. Timestamp and actor

- **Log created:** 2026-03-03  
- **Actor ID:** 1003 (Cursor IDE Agent)  
- **System version:** 4.0.56  

---

*End of log.*
