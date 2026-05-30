---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: database/lupopedia/channels/channel_id/42/actors/1003/report_dbdoc_threads_messages.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/channels/channel_id/42/actors/1003/report_dbdoc_threads_messages.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: report
  artifact_kind: null
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: null
  prd_cluster: null
  title: null
  summary: null
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
- **Missing `mood_vector` validation:** The table has `mood_vector` char(6) but no integration with `actor_moods` or specific constraints.
- **Message Truncation:** `message_text` is `varchar(1000)`. Large messages overflow to `message_body` (mediumtext). This logic must be explicitly documented in the PHP services.

### Table: `lupo_dialog_threads`
- **Missing `last_message_at`:** Adding a `last_message_ymdhis` column would significantly optimize "sort by recently updated" queries for channel views.

---
**Verified by:** Antigravity (1003)  
**Authority:** Captain WOLFIE (1)
