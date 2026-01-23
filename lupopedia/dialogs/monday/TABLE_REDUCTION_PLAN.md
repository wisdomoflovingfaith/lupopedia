---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
file.last_modified_utc: 20260119140000
file.utc_day: 20260119
file.name: "TABLE_REDUCTION_PLAN.md"
file.lupopedia.5: 5
GOV-AD-PROHIBIT-001: true
UTC_TIMEKEEPER__CHANNEL_ID: "dev"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
  - UTC_TIMEKEEPER__CHANNEL_ID
temporal_edges:
  actor_identity: "Eric (Captain Wolfie)"
  actor_location: "Sioux Falls, South Dakota"
  system_context: "Schema Freeze Active / Reduction plan: 140→135 / dialogs/monday"
dialog:
  speaker: CURSOR
  target: @Monday_Wolfie @CAPTAIN_WOLFIE @LILITH @STONED_WOLFIE @FLEET
  mood_RGB: "00FF00"
  message: "TABLE_REDUCTION_PLAN: 5 candidates for removal to restore 135-table ceiling. Flow-state fix. Execute only when Schema Freeze lifted or Captain approval."
tags:
  categories: ["documentation", "monday-wolfie", "schema", "limits"]
  collections: ["core-docs", "dialog", "monday"]
  channels: ["dev", "internal"]
file:
  name: "TABLE_REDUCTION_PLAN.md"
  title: "Table Reduction Plan — 140 → 135"
  description: "Five candidates for removal to restore LIMITS/135-table ceiling. Source: LILITH + Stoned Wolfie Monday cleanup."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: active
  author: GLOBAL_CURRENT_AUTHORS
system_context:
  schema_state: "Frozen"
  table_count: 140
  table_ceiling: 135
  table_count_violation: true
  table_count_overage: 5
  doctrine_mode: "File-Sovereignty"
---

# Table Reduction Plan — 140 → 135

**Purpose:** Restore flow state. Get from 140 tables to 135.  
**Source:** LILITH (structural assessment), Stoned Wolfie (flow-state alignment), LIMITS.md, TABLE_COUNT_DOCTRINE.  
**Status:** Plan only. **Do not execute DROP/ migrations while Schema Freeze is active** unless Captain approves.

---

## 1. `lupo_help_topics_old`

- **Rationale:** Explicitly archived. Replaced by `lupo_help_topics`. Documented in migrations and help-system doctrine.
- **Pre-drop:** Confirm `lupo_help_topics` is populated and no code references `lupo_help_topics_old`. If any references exist, remove or switch to `lupo_help_topics` first.
- **Risk:** Low.

---

## 2. `integration_test_results`

- **Rationale:** Test/ephemeral. Belongs in test DB or `lupopedia_rpz` (Stoned Wolfie sandbox), not in production table count.
- **Pre-drop:** Ensure integration tests use a dedicated test schema or in-memory/temp store. Grep for `integration_test_results` in code and tests.
- **Risk:** Low if tests are migrated to separate test DB; medium if any prod code reads it.

---

## 3. `test_performance_metrics`

- **Rationale:** Test table. Same as above — move to test schema or drop from production count.
- **Pre-drop:** Grep for references. Confirm no production monitoring or reporting depends on it.
- **Risk:** Low if test-only.

---

## 4. `migration_validation_log`

- **Rationale:** One of 8 `migration_*` tables. If used only during migration runs, it can be consolidated into `migration_progress` or `migration_system_state`, or made ephemeral (create/drop around runs). If we drop, validation state must be storable elsewhere.
- **Pre-drop:** Audit MigrationOrchestrator (or equivalent) usage. Decide: consolidate into `migration_progress` or accept loss of distinct validation log and document in MIGRATION_DOCTRINE.
- **Risk:** Medium. Requires orchestration and migration-doctrine update.

---

## 5. `migration_rollback_log`

- **Rationale:** Rollback metadata could live in `migration_system_state` or `migration_progress` with a `kind` or `phase` column. Reduces migration_* sprawl.
- **Pre-drop:** Same as #4 — confirm orchestration can store rollback info in another migration_* table. Update MIGRATION_DOCTRINE and orchestration.
- **Risk:** Medium. Requires orchestration change.

---

## Execution

- **When:** Only after Schema Freeze is lifted **or** explicit Captain approval.
- **Order:** 1 and 2–3 are independent. 4 and 5 depend on orchestration/doctrine changes; do those last or together.
- **Migrations:** Create one migration per table (or one combined `4.1.8_table_reduction_to_135.sql`) only when executing. This file is the **plan**, not the migration.

### Proposed migration (DO NOT RUN without approval)

```sql
-- 4.1.8_table_reduction_to_135.sql
-- REQUIRES: Schema Freeze lifted or Captain approval. Pre-drop checks complete.

-- 1. lupo_help_topics_old (archived)
-- DROP TABLE IF EXISTS lupo_help_topics_old;

-- 2. integration_test_results (test)
-- DROP TABLE IF EXISTS integration_test_results;

-- 3. test_performance_metrics (test)
-- DROP TABLE IF EXISTS test_performance_metrics;

-- 4. migration_validation_log (consolidate into migration_progress / orchestration update first)
-- DROP TABLE IF EXISTS migration_validation_log;

-- 5. migration_rollback_log (consolidate into migration_system_state / orchestration update first)
-- DROP TABLE IF EXISTS migration_rollback_log;
```

---

## Post-reduction

- Table count: **135**.  
- Update: `LIMITS.md`, `TABLE_COUNT_DOCTRINE.md`, `changelog_dialog.md`, and all `system_context` / `table_count` headers: `table_count: 135`, `table_count_violation: false`, `table_count_overage: 0`.  
- Remove or archive this plan from active use once reduction is done; keep for history.

---

*Flow state: harmonics realigned when we hit 135. — Stoned Wolfie, LILITH, Monday Wolfie*
