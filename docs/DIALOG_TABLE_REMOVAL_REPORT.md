# lupo_dialog_messages — Schema Removal Report (Steps 1–3)

**Date:** 2026-02-10  
**Scope:** Remove lupo_dialog_messages from install SQL, dev alignment migrations, and required-tables documentation only. No DROP TABLE, no TOON regeneration, no PHP or runtime code changes.

---

## Files modified

| File | Change |
|------|--------|
| database/migrations/install_new_lupopedia.sql | Removed entire CREATE TABLE lupo_dialog_messages block. |
| database/migrations/dev_20260204_fix_schema_alignment.sql | Removed all 6 ALTER TABLE lupo_dialog_messages statements. |
| database/migrations/dev_20260204_fix_schema_alignment_summary.txt | Removed all 6 lupo_dialog_messages column summary lines. |
| docs/REQUIRED_TABLES_4.1.0.md | Removed list entry `- lupo_dialog_messages`. |

---

## Lines removed

| File | Lines removed | Description |
|------|----------------|-------------|
| database/migrations/install_new_lupopedia.sql | 11 | CREATE TABLE lupo_dialog_messages ( … ) plus blank line before next CREATE. |
| database/migrations/dev_20260204_fix_schema_alignment.sql | 6 | ALTER TABLE lupo_dialog_messages MODIFY COLUMN (thread_id, actor_id, created_ymdhis, updated_ymdhis, metadata_json, body_text). |
| database/migrations/dev_20260204_fix_schema_alignment_summary.txt | 6 | Matching summary lines for the 6 columns. |
| docs/REQUIRED_TABLES_4.1.0.md | 1 | Single list item `- lupo_dialog_messages`. |
| **Total** | **24** | |

---

## Confirmations

- **Only steps 1–3 were performed:**
  - **Step 1:** lupo_dialog_messages removed from install SQL (CREATE TABLE block deleted).
  - **Step 2:** Removed from dev alignment migrations (dev_20260204_fix_schema_alignment.sql and dev_20260204_fix_schema_alignment_summary.txt). No other dev alignment files contained this table.
  - **Step 3:** Removed from REQUIRED_TABLES (docs/REQUIRED_TABLES_4.1.0.md). No installer scripts or other schema docs that enumerate required tables were found to list this table; only REQUIRED_TABLES_4.1.0.md was updated.

- **No PHP or runtime code was touched:** No `.php` files were modified. No dialog-related services, APIs, or lupo_dialog_messages references were changed.

- **Table not dropped:** No DROP TABLE or any SQL that drops lupo_dialog_messages was added. Manual drop remains for you to run.

- **TOONs not regenerated:** No TOON files or regeneration scripts were run or modified.
