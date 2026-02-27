---
flare.headers:
  file_path_from_root: "channels/42/actors/1003/report_dbdoc_threads_messages.md"
  system_version: "4.0.48"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260226"
  delegation_chain: "1003:10000"
  artifact_type: "report"
  purpose: "Initial report on Database Documentation task for dialog tables"
  mood_rgb: "4169E1"
  actor_ip: "127.0.0.1"
  lupo_agent: "antigravity"
---

# 📊 Database Documentation Report - Dialog Tables

**Task ID:** DBDOC-2026-02-27-001  
**Lead Agent:** Antigravity (1003)  
**Tables Reviewed:** `lupo_dialog_threads`, `lupo_dialog_messages`, `lupo_dialog_channels`

## 🔍 General Findings

### 1. `lupo_dialog_messages` Index Name Prefix Mismatch
In `lupo_dialog_messages.toon.json`, all secondary indexes are prefixed with `lupo_dialog_doctrine_idx_` instead of `lupo_dialog_messages_idx_`.
- **Impact:** Inconsistent schema naming. This likely stems from the recent rename of the `lupo_dialog_doctrine` table to `lupo_dialog_messages`.
- **Recommendation:** Regenerate/Rename indexes to match the new table name during the next migration cycle.

### 2. `lupo_dialog_channels` Non-Canonical Timestamps
Unlike the rest of the Lupopedia system, this table uses:
- `created_timestamp`
- `modified_timestamp`
- **Impact:** Violates the "BIGINT UTC TIMESTAMPS (YYYYMMDDHHIISS)" doctrine.
- **Recommendation:** Add `created_ymdhis` and `updated_ymdhis` columns and migrate data.

### 3. Duplicate Fields in `lupo_dialog_threads`
- Both `dialog_thread_id` and `thread_id` exist. `dialog_thread_id` is the PK. `thread_id` defaults to 0. 
- **Inquiry:** Is `thread_id` a legacy reference or a external system mapping? If it's redundant, it should be marked for removal.

## 📋 Schema Optimization Suggestions

### Table: `lupo_dialog_messages`
- **Missing `mood_rgb` validation:** The table has `mood_rgb` char(6) but no integration with `actor_moods` or specific constraints.
- **Message Truncation:** `message_text` is `varchar(1000)`. Large messages overflow to `message_body` (mediumtext). This logic must be explicitly documented in the PHP services.

### Table: `lupo_dialog_threads`
- **Missing `last_message_at`:** Adding a `last_message_ymdhis` column would significantly optimize "sort by recently updated" queries for channel views.

---
**Verified by:** Antigravity (1003)  
**Authority:** Captain WOLFIE (1)
