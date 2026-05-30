---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: status
  when_updated: "20260414150000"
  file_path_from_root: "lupo-docs/versions/4.0.99/status/missing_table_analysis.md"
  questions_toon: null
  artifact_type: status
  artifact_kind: analysis
  channel_key: "development"
  trust_tier: "canonical"
---
# Missing Table Analysis — Fresh Install vs Old Database

**Date:** 2026-04-14
**Analyst:** Augment Agent
**Context:** Fresh install created 178 tables. Old database had 179. One table is missing.

---

## 1. Methodology

| Source | Count | Method |
|--------|-------|--------|
| Live DB (`lupopedia`) | **178** | `SHOW TABLES;` via mysql CLI |
| JSON schema dir (`lupo-database/lupopedia/json/`) | **178** | `Get-ChildItem *.json \| Measure-Object` |
| Install SQL (`install_new_lupopedia.sql`) | **175** | `grep -c "^CREATE TABLE"` |
| Clean SQL (`install_new_lupopedia_clean.sql`) | **176** | Includes `dialog_read_log` |

**Diff result:** JSON and live DB are identical (178 tables each, zero delta).
The 179th table was in the old database but was **never exported to JSON** and **never added to the canonical install SQL**.

---

## 2. The Missing Table

### `lupo_dialog_read_log`

**Verdict: ✅ NEEDED — must be added to the install SQL and recreated as a JSON schema file.**

---

## 3. Evidence Trail

### 3.1 Where It Exists

| Location | Present? | Notes |
|----------|----------|-------|
| Live DB (`lupopedia`) | ❌ No | Not created by fresh install |
| `json/lupo_dialog_read_log.json` | ❌ No | File does not exist — was removed or never exported |
| `install_new_lupopedia.sql` (canonical) | ❌ No | Omitted — root cause of the missing table |
| `install_new_lupopedia_clean.sql` (alt) | ✅ Yes | Present at line 2156 with full schema |
| `lupo-includes/classes/DialogMvpService.php` | ✅ Referenced | `updateReadLog()` method (line 487) writes to this table |
| `open_questions.md` OQ-04 | ✅ Referenced | Tracks missing `last_read_created_ymdhis` column |
| `claude_database_table_review.md` §2.5 | ✅ Referenced | Previous analysis flagged this as "IN JSON, NOT IN SQL" |

### 3.2 Schema (from `install_new_lupopedia_clean.sql`)

```sql
-- lupo_dialog_read_log
CREATE TABLE {{prefix}}dialog_read_log (
  `read_log_id`          BIGINT NOT NULL,
  `actor_id`             BIGINT NOT NULL,
  `channel_id`           BIGINT NOT NULL,
  `thread_id`            BIGINT NOT NULL,
  `last_read_message_id` BIGINT NOT NULL,
  `updated_ymdhis`       BIGINT NOT NULL,
  PRIMARY KEY (read_log_id)
);

CREATE INDEX {{prefix}}lupo_read_log_idx_actor_context
  ON {{prefix}}dialog_read_log (actor_id, channel_id, thread_id);
```

**Note (OQ-04):** `last_read_created_ymdhis` BIGINT column is planned but not yet in schema.
Per doctrine, the polling cursor is `after_ymdhis` (14-digit UTC BIGINT), not `last_message_id`.
Adding `last_read_created_ymdhis` via ALTER TABLE is the correct path when implementing full read-cursor tracking.

### 3.3 Active Code Dependency

`DialogMvpService::updateReadLog()` in `lupo-includes/classes/DialogMvpService.php` (line 477):

- Performs `SELECT` from `lupo_dialog_read_log` to check existing read mark
- Performs `INSERT` or `UPDATE` to persist the high-water read mark per `(actor_id, channel_id, thread_id)`
- Will throw a MySQL error at runtime if this table does not exist

---

## 4. Why It Went Missing

**Root cause:** `install_new_lupopedia.sql` was never updated when `dialog_read_log` was added to the system.
The table existed in the old database (created via `install_new_lupopedia_clean.sql` or manually).
When the fresh install ran from `install_new_lupopedia.sql`, it skipped this table.
The JSON export was also done from the fresh install, so the JSON file was never created either.
Previous analysis (`claude_database_table_review.md` §2.5) correctly identified the issue but action was deferred.

---

## 5. Recommendation

| Action | Owner | Priority |
|--------|-------|----------|
| Add `dialog_read_log` CREATE TABLE to `install_new_lupopedia.sql` | Dev | HIGH |
| Create `lupo-database/lupopedia/json/lupo_dialog_read_log.json` schema file | Dev | HIGH |
| Consider adding `last_read_created_ymdhis BIGINT NULL` column at same time (OQ-04) | Dev | MED |
| Close OQ-04 once schema is updated | Dev | MED |

**Do not ignore this.** `DialogMvpService::updateReadLog()` is live code that will produce SQL errors
in any environment where the table does not exist (including the current fresh install).

---

## 6. Cross-References

- `lupo-docs/versions/4.0.99/status/open_questions.md` — OQ-04
- `lupo-docs/versions/4.0.99/status/claude_database_table_review.md` — §2.5
- `lupo-includes/classes/DialogMvpService.php` — `updateReadLog()` method
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia_clean.sql` — line 2156 (source of truth for schema)
- `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` — canonical installer (needs update)
