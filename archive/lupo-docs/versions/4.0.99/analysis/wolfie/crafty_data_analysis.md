---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: documentation
  when_updated: "20260411170424"
  file_path_from_root: "lupo-docs/versions/4.0.99/analysis/wolfie/crafty_data_analysis.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.99/analysis/wolfie/crafty_data_analysis.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "lupo-memory/development/canonical/1026/04/analysis-wolfie-crafty-data.toon"
  artifact_type: documentation
  artifact_kind: guide
  thread_id: "wolfie-crafty-analysis"
  content_id: null
  pk_id: null
  pk_slug: "crafty-sql-analysis"
  title: "AI WOLFIE — Crafty Syntax 3.7.5 SQL dump and import mapping"
  status: "active"
  parent_pk_id: ""
  summary: "Row estimates from old_crafty_syntax_3_7_5_start.sql; import_from_old_crafty_syntax.sql workflow; target lupo_* tables; Breakthrough Registry cross-ref."
  module: null
  dialog_transcript: "0/development/wolfie-crafty-analysis"
---
# AI WOLFIE — Old Crafty Syntax SQL data and import mapping

**Sources (canonical paths):**

- **Legacy dump (schema + data):** [`lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql`](../../../../../lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql) (~279 KiB, ~4.9k lines).
- **Import transform:** [`lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`](../../../../../lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql) (~72 KiB, ~1.8k lines). Uses template `{{prefix}}` (installed as `lupo_`).

This artifact is **read-only analysis** for operators and **AI WOLFIE** (`session`: `wolfie/crafty_analysis`). It does **not** change importer behavior unless **WOLFIE** accepts follow-up work.

## 1. What is in the legacy dump?

### 1.1 Tables with data in *this* repository sample

The dump ships **28** single `INSERT INTO ... VALUES` blocks. Row counts below count **lines starting with `(`** inside each block (typical `mysqldump` shape). **Your production Crafty export may be much larger.**

| Crafty table | Est. rows (this file) | Notes |
|--------------|----------------------:|-------|
| `livehelp_paths_monthly` | 1311 | Aggregated path flows |
| `livehelp_paths_firsts` | 1010 | First-touch path flows |
| `livehelp_visits_daily` | 368 | Daily visit rollups |
| `livehelp_operator_history` | 347 | Operator action log |
| `livehelp_visits_monthly` | 249 | Monthly visit rollups |
| `livehelp_referers_monthly` | 151 | Referrer rollups |
| `livehelp_referers_daily` | 77 | Referrer daily |
| `livehelp_leavemessage` | 31 | Offline messages |
| `livehelp_smilies` | 33 | Legacy smilies (not imported to Lupopedia chat_smilies replacement via this SQL) |
| `livehelp_layerinvites` | 6 | Layer invite assets |
| `livehelp_questions` | 7 | Pre-chat questions |
| `livehelp_qa` | 9 | Q&A folders |
| `livehelp_emailque` | 4 | Email queue |
| `livehelp_leads` | 4 | CRM leads |
| `livehelp_visit_track` | 4 | **Raw** session hits (small in this sample) |
| `livehelp_modules` | 3 | Module registry |
| `livehelp_modules_dep` | 3 | Module/department dep (not imported) |
| `livehelp_users` | 3 | Users / operators |
| `livehelp_operator_departments` | 2 | Operator to department |
| `livehelp_autoinvite` | 1 | Auto-invite rules |
| `livehelp_config` | 1 | Key/value config |
| `livehelp_departments` | 1 | Departments |
| `livehelp_emails` | 1 | Email log |
| `livehelp_identity_daily` | 1 | Identity rollup |
| `livehelp_identity_monthly` | 1 | Identity rollup |
| `livehelp_quick` | 1 | Canned responses |
| `livehelp_transcripts` | 1 | Chat transcript (sample) |
| `livehelp_websites` | 1 | Sites / federation seeds |

### 1.2 DDL present but **no data** in this file

| Table | Relevance |
|-------|-----------|
| `livehelp_channels` | Schema only here; import script `ALTER`s legacy table but **does not** `INSERT` from an empty dump row set. |
| `livehelp_operator_channels` | Same — structure exists; no rows in this export. |
| `livehelp_messages` | Table exists; **no `INSERT` in this dump** (messages may live only in full customer exports). |
| `livehelp_keywords_daily` / `livehelp_keywords_monthly` | Schema + indexes; **no data** in this file. |

### 1.3 Notable omissions vs some Crafty docs

This dump does **not** include separate `livehelp_paths_visits` / `livehelp_paths_visits_day` tables; path analytics in the sample are **`livehelp_paths_firsts`** and **`livehelp_paths_monthly`** only.

## 2. Data quality (sample-scale)

| Issue | Severity | Mitigation (already / proposed) |
|-------|----------|----------------------------------|
| **Tiny `livehelp_visit_track` sample** | Med | Treat this repo file as **fixture**; validate counts on real 3.7.5 export before performance planning. |
| **Mixed legacy time shapes** (`whendone`, `dateof` 6 vs 8 digits) | High | Import SQL normalizes to **14-digit BIGINT UTC** (see **§3** below). |
| **AUTO_INCREMENT legacy IDs** | Med | Importer uses explicit IDs / sequences where required by Lupopedia doctrine; see import comments (e.g. `@lupo_import_visit_id`). |
| **No FKs in Lupopedia** | Low | Constitutional; relationships enforced in app code after import. |

## 3. Import workflow (`import_from_old_crafty_syntax.sql`)

### 3.1 Entry point and style

- **Preamble** (lines 1–43): scope (**34** legacy Crafty tables), **199** core Lupopedia tables post-migration, **no FK/trigger** doctrine, **BIGINT UTC** timestamps.
- **Per-table blocks:** `ALTER TABLE livehelp_*` (utf8mb4), `COMMENT` deprecation, then **`TRUNCATE` / `INSERT ... SELECT`** into `{{prefix}}*` tables.

### 3.2 Idempotence and resume

- **`INSERT IGNORE`**, **`ON DUPLICATE KEY UPDATE`**, and **`NOT EXISTS` subqueries** appear (e.g. departments, truth Q&A, collection tabs, referers).
- **Not a dry-run:** executing the file mutates the target DB.
- **Resume:** partially possible where inserts are guarded; **`TRUNCATE`** sections (e.g. `visits`, `paths`, `dialog_threads`) are **destructive** — re-run from backup if mid-flight failure leaves legacy tables intact but Lupo tables half-filled.

### 3.3 Key transformations (examples)

| Source | Target | Transformation (from SQL) |
|--------|--------|---------------------------|
| `livehelp_users.user_id` | `lupo_auth_users.auth_user_id` | **`10000 + user_id`** (human range); two passes: operators first, then remainder. |
| `livehelp_users.lastaction` | `last_login_ymdhis` | `FROM_UNIXTIME` → `DATE_FORMAT` → **SIGNED** BIGINT UTC. |
| `livehelp_visit_track.whendone` | `lupo_visits.created_ymdhis` | `CASE`: already 14-digit; 13-digit + `'0'`; 8-digit `YYYYMMDD` + `'120000'`; else `LPAD` to 14. |
| `livehelp_visits_daily.dateof` | synthetic `lupo_visits` | `CONCAT(dateof,'120000')`; `is_processed=1`. |
| `livehelp_visits_monthly.dateof` | synthetic `lupo_visits` | `CONCAT(dateof,'01120000')`. |
| `livehelp_visit_track.sessionid` | `lupo_visits.session_id` | **`CRC32(sessionid)`** (MySQL) for grouping. |
| `livehelp_paths_*` | `lupo_paths` | Allocated `path_id` via user variable; `year_num`/`month_num`/`day_num` derived from `dateof`. |
| `livehelp_transcripts` | `lupo_dialog_threads` + `lupo_dialog_messages` | `recno` as thread/message id; **placeholder actors `1`**, channel **`1`**, federation **`1`** (import defaults). |
| `livehelp_websites` | `lupo_federation_nodes` | **`DELETE` all `federation_node_id != 0`** then insert from legacy sites. |
| `livehelp_autoinvite.user_id` | `operator_user_id` | **`10000 + user_id`**. |

### 3.4 Dropped / not carried by this import

| Legacy | Reason (from script + doctrine) |
|--------|----------------------------------|
| `livehelp_modules_dep` | Lupopedia enables modules without this join table. |
| `livehelp_smilies` | Replaced by filesystem / product approach (not bulk-imported here). |
| `livehelp_sessions` | Replaced by `lupo_sessions` model (not a straight row copy in this file). |

### 3.5 Post-import actor repair (excerpt)

- **`UPDATE lupo_actor_departments`** rewires `actor_id` using `livehelp_operator_departments` + username join.
- **Department hybrids:** inserts `actor_id` **`280000 + department_id`** with `agent_key = 'wolfie'` per comments (import-only band).

## 4. Mapping: Crafty → Lupopedia (implemented in import SQL)

| Crafty Syntax | Lupopedia target (prefix `lupo_`) | Status in script |
|---------------|-----------------------------------|------------------|
| `livehelp_visit_track` (+ daily/monthly rollups) | `lupo_visits` | **Implemented** (`TRUNCATE` + multi-`INSERT SELECT`). |
| `livehelp_paths_firsts` / `livehelp_paths_monthly` | `lupo_paths` | **Implemented**. |
| `livehelp_referers_daily` / `livehelp_referers_monthly` | `lupo_referers` | **Implemented** (with dedup guards). |
| `livehelp_transcripts` | `lupo_dialog_threads`, `lupo_dialog_messages` | **Implemented** (default channel/actor placeholders). |
| `livehelp_users` | `lupo_auth_users`, then `lupo_actors` (+ filesystem/sync rows) | **Implemented**. |
| `livehelp_departments` | `lupo_departments`, metadata | **Implemented**. |
| `livehelp_websites` | `lupo_federation_nodes` | **Implemented** (preserves node 0). |
| `livehelp_operator_departments` | `lupo_actor_departments` (after seed rebuild) | **Implemented** + `UPDATE`. |
| `livehelp_operator_history` | `lupo_audit_log` | **Implemented** (see script block). |
| `livehelp_qa` / answers | `lupo_truth_questions`, `lupo_truth_answers`, collections | **Implemented**. |
| `livehelp_quick` | `lupo_actor_reply_templates` | **Implemented**. |
| `livehelp_autoinvite` | `lupo_crafty_syntax_auto_invite` | **Implemented**. |
| `livehelp_layerinvites` | `lupo_crafty_syntax_layer_invites` | **Implemented**. |
| `livehelp_leavemessage` | `lupo_crafty_syntax_leave_message` | **Implemented**. |
| `livehelp_questions` | `lupo_crafty_syntax_chat_questions` | **Implemented**. |
| `livehelp_modules` + `livehelp_config` | `lupo_modules` (+ JSON config for id=1) | **Implemented** (see script). |
| `livehelp_emails` / `livehelp_leads` / `livehelp_emailque` | CRM tables | **Implemented** in script sections. |
| `livehelp_channels` | `lupo_channels` | **Prepared** via `ALTER` on legacy table; row mapping depends on legacy data present at run time. |

## 5. Breakthrough Registry cross-reference

See **`SUMMARY.md`** in this folder for pattern applicability, **proposed** (non-scored) import patterns, and open questions added to **`BREAKTHROUGH_REGISTRY.md`** **§13.1**.

This output complies with Lupopedia Constitutional Root Rules.
