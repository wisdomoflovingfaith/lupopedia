---
lupopedia.headers:
  lupopedia.version: "4.0.75"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/DATABASE_DOCTRINE.md"
  last_modified_utc: "20260315"
  system_version: "4.0.75"
  channel_id: 42
  actor_id: 102
  purpose: "Database schema and index conventions; reserved ID, soft delete, timestamp, and verification doctrine."
  lupo_agent: "cursor"

lupopedia.edges:
  referenced_by:
    - { from: "lupo-docs/database/lupopedia/tables/lupopedia_actors_collections_organization_reference.md", type: "references", note: "Canonical schema reference" }
    - { from: "lupo-docs/database/lupopedia/tables/README.md", type: "references" }
    - { from: "lupo-docs/database/lupopedia/tables/active/planning/README.md", type: "references" }
    - { from: "lupo-channels/42/content/federation_node_id/0/NO_INFORMATION_SCHEMA_RULE.md", type: "references" }
    - { from: "lupo-docs/database/lupopedia/tables/migrations/livehelp_config_migration.md", type: "documents" }
    - { from: "lupo-docs/database/lupopedia/tables/migrations/livehelp_modules_migration.md", type: "documents" }
    - { from: "lupo-docs/database/lupopedia/tables/migrations/livehelp_qa_migration.md", type: "documents" }
  note: "Also referenced by other migration docs (doctrine/database/README.md targets), channel broadcasts, and status/audit reports. Grep: DATABASE_DOCTRINE.md"

lupopedia.footer:
  last_verified: "20260315"
  last_verified_by: "cursor"
---

# Database Doctrine

## Index and schema conventions

- No foreign keys, triggers, stored procedures, or DB-generated timestamps (see Database Logic Prohibition in root rules).
- Primary keys: `<table_singular>_id` unless a doctrinal exception applies (see Primary Key Naming and Exceptions).
- Reference columns match the referenced table’s PK name.
- Indexes: `CREATE INDEX <table>_idx_<purpose> ON <table> (...)`.

## Reserved ID doctrine

Registry-backed entities (e.g. `lupo_actors`, `lupo_channels`, `lupo_auth_users`, and other tables whose IDs come from the registry) must **not** use AUTO_INCREMENT or rely on `lastInsertId()`. Allocation must follow the canonical registry workflow:

1. **Allocate** via `lupo_registry` and, when applicable, `lupo_registry_open` (check freed IDs first; otherwise compute next ID from the target entity table or registry for that `entity_type`).
2. **Insert** with an explicit ID; never omit the ID for registry-backed tables.
3. **Upsert:** If a row with that ID may already exist, check first — then UPDATE or INSERT with the explicit ID. Do not use `INSERT ... ON DUPLICATE KEY UPDATE` for these tables; use explicit SELECT → UPDATE or INSERT.

Agents and code must **not**: use AUTO_INCREMENT for these tables, use `lastInsertId()` for them, or rely on casual numeric allocation. The installer, importer, and seed logic must preserve reserved IDs exactly. See `lupo-rules/root/reserved-id-doctrine.md` and `lupo-docs/doctrine/REGISTRY_DOCTRINE.md`.

## Soft delete pattern

Tables that participate in the soft-delete lifecycle use:

- **`is_deleted`** — TINYINT, 0 = active, non-zero = deleted.
- **`deleted_ymdhis`** — BIGINT, UTC YYYYMMDDHHIISS when the row was marked deleted (set in application code).

Active queries must filter appropriately (e.g. `WHERE is_deleted = 0` by default). Soft-delete timestamps are set in application code only; never use database-generated defaults for `deleted_ymdhis`. Physical deletes are exceptional (e.g. purge/cleanup); normal application behavior is to set `is_deleted` and `deleted_ymdhis`. Not every table has these columns; the canonical install defines them where the lifecycle requires soft delete.

## Timestamp convention

- **Naming:** Use `{action}_ymdhis` (e.g. `created_ymdhis`, `updated_ymdhis`, `deleted_ymdhis`).
- **Type:** BIGINT.
- **Format:** UTC `YYYYMMDDHHIISS` (e.g. `20260315143000`).
- **Source:** Application-generated only (e.g. `gmdate('YmdHis')`). Never use database-generated timestamps, `CURRENT_TIMESTAMP`, or `ON UPDATE` automatic timestamps.
- All timestamp values must be set explicitly in application code.

## Primary key naming and exceptions

**Normal pattern:** Primary key column is `<singular_table_name>_id` (e.g. `lupo_sessions` → `session_id`). Reference columns in other tables use the same name as the referenced PK.

**Doctrinal exceptions:** Lupopedia allows deliberate, documented exceptions. The major example is **`lupo_actors`**: `actor_name` is the doctrinal primary identity key (PRIMARY KEY); `actor_id` is the unique numeric mapping used for joins and allocation. Contributors must not “normalize away” this or other intentional schema doctrine. Exceptions must be stated in doctrine and in the install/TOON so that tooling and code do not assume a single pattern for all tables.

## Character set and collation defaults

Where table storage is specified, the project standard is:

- **ENGINE=InnoDB**
- **DEFAULT CHARSET=utf8mb4**
- **COLLATE=utf8mb4_unicode_ci**

The canonical `install_new_lupopedia.sql` may rely on server defaults; when adding or altering tables, match the above so behavior is consistent with existing install and future-features SQL.

## Expression indexes (lupo_contents) — portability

The table `lupo_contents` defines **functional (expression) indexes** on JSON columns in the canonical install:

- `lupo_contents_idx_has_media ON lupo_contents ((JSON_LENGTH(media_attachments) > 0))`
- `lupo_contents_idx_has_events ON lupo_contents ((JSON_LENGTH(content_events) > 0))`
- `lupo_contents_idx_has_hashtags ON lupo_contents ((JSON_LENGTH(hashtags) > 0))`

**Portability:**

- **MySQL 8.0.13+:** Supported (functional key parts).
- **MariaDB 10.2.1+:** Limited support; syntax may differ.
- **PostgreSQL:** Uses expression index syntax `CREATE INDEX ... ON t ((expr));` — same idea, different function names (e.g. `jsonb_array_length` for JSONB).

When targeting multiple engines or versions, document or conditionally create these indexes per platform, or omit them for non-MySQL installs. Do not add further DB-generated or computed columns; keep logic in application code.

## Platform-specific exceptions

When a feature is not fully portable (e.g. expression indexes, engine-specific optimizations, platform-specific syntax):

1. **Document the exception** in the relevant doctrine or compatibility notes (do not invent file paths that do not exist).
2. **Define fallback behavior** or an alternative path where the feature is unsupported.
3. **Add explicit compatibility notes** (e.g. “MySQL 8.0.13+ only” or “MariaDB syntax differs”).
4. **Avoid silent engine lock-in** — call out which engines/versions are supported.

## Doctrine verification

Use the following to check compliance. Queries are MySQL/MariaDB unless noted.

### Forbidden foreign keys

```sql
SELECT TABLE_NAME, CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = DATABASE()
  AND REFERENCED_TABLE_NAME IS NOT NULL;
```

Expected: no rows for Lupopedia tables (doctrine forbids FKs).

### Forbidden triggers

```sql
SHOW TRIGGERS;
-- or: SELECT * FROM information_schema.TRIGGERS WHERE TRIGGER_SCHEMA = DATABASE();
```

Expected: no triggers on Lupopedia tables.

### Timestamp column type

Ensure columns whose names suggest timestamps (e.g. ending in `_ymdhis`) are BIGINT, not DATETIME/TIMESTAMP:

```sql
SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND COLUMN_NAME LIKE '%ymdhis%'
  AND DATA_TYPE NOT IN ('bigint', 'BIGINT');
```

Expected: no rows (all such columns should be BIGINT).

### AUTO_INCREMENT on registry-backed tables

Registry-backed tables (e.g. `lupo_actors`, `lupo_channels`, `lupo_auth_users`) must not use AUTO_INCREMENT on their identity column. Inspect the install SQL or run:

```sql
SELECT TABLE_NAME, COLUMN_NAME, EXTRA
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME IN ('lupo_actors', 'lupo_channels', 'lupo_auth_users')
  AND EXTRA LIKE '%auto_increment%';
```

Expected: no rows for those tables.

### Soft delete columns (where applicable)

For tables that are defined in the install with `is_deleted` / `deleted_ymdhis`, verify the columns exist and that queries default to filtering `is_deleted = 0`. No single query fits all schemas; use install SQL and TOONs as the source of which tables participate in soft delete.
