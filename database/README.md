# database/

## Purpose of this folder

This folder is **intentionally empty** except for this documentation.

All real database files live in the directory configured in:

**`lupopedia-config.php`**

## Canonical database root

The configured root is:

```php
$lupo_database_root = 'lupo-database/lupopedia/';
```

## Canonical subdirectories

All database-related files must be written to one of:

- **`lupo-database/lupopedia/mysql/`** — MySQL table exports
- **`lupo-database/lupopedia/postgres/`** — PostgreSQL table exports
- **`lupo-database/lupopedia/toon/`** — TOON files
- **`lupo-database/lupopedia/csv/`** — CSV exports

## Important notes

- The old **`database/`** folder has been archived to **`database_old/`**.
- **No new files** should ever be written to **`database/`** (except this README).
- All tooling, agents, and installers must use the configured **`$lupo_database_root`**.
- Any references to **`database/`** in documentation or scripts are legacy and must be updated.
