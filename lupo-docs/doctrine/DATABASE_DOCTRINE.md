# Database Doctrine

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/DATABASE_DOCTRINE.md"
  last_modified_utc: "20260306"
  system_version: "4.0.57"
  channel_id: 42
  actor_id: 1003
  purpose: "Database schema and index conventions; expression index portability"
  lupo_agent: "cursor"
flare.footer:
  last_verified: "20260306"
  last_verified_by: "cursor"
---

## Index and schema conventions

- No foreign keys, triggers, stored procedures, or DB-generated timestamps (see Database Logic Prohibition).
- Primary keys: `<table_singular>_id`. Reference columns match the referenced PK name.
- Indexes: `CREATE INDEX <table>_idx_<purpose> ON <table> (...)`.
- Timestamps: BIGINT YYYYMMDDHHIISS (set in application only).

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
