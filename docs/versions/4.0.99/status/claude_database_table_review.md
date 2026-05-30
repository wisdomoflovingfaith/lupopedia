# Claude Database Table Review
**Date:** 2026-04-14
**Scope:** 178 SQL tables, 179 JSON schemas, 121 table docs
**Baseline:** install_new_lupopedia.sql post-Gemini update (2026-04-14)
**Constitutional Reference:** PRD 80 (Database Design Doctrine)
**Auditor:** Claude Code (Actor 116)

---

## Executive Summary

| Metric | Value |
|--------|-------|
| SQL CREATE TABLE statements | 178 |
| JSON schema files (live DB) | 179 |
| Table docs active | 121 / 178 (68%) |
| Tables undocumented | 57 / 178 |
| AUTO_INCREMENT violations | 0 |
| FOREIGN KEY violations | 0 |
| UNSIGNED violations in SQL | 0 |
| ENGINE= violations in SQL | **2 (unresolved)** |
| COLLATE= violations in SQL | **2 (same tables)** |
| SQL/JSON column mismatches | **4 tables** |
| JSON-only tables (not in SQL) | **1** |
| Live DB UNSIGNED violations (JSON) | **3 tables** |
| Non-exempt tables missing full soft delete | 24 |
| Non-exempt tables missing deleted_ymdhis only | 8 |

**Gemini's four stated changes verified:**

| Change | SQL Updated | JSON Match | Notes |
|--------|-------------|------------|-------|
| `lupo_votes` polymorphic refactor | PARTIAL — old column names still in SQL | NO — JSON has new schema | See Section 2.1 |
| `lupo_memory_nodes` vector columns | NO — SQL missing both new columns | YES (JSON has them) | See Section 2.2 |
| `lupo_paths_daily` hit_count/unique_actors | NO — SQL missing both columns | YES (JSON has them) | See Section 2.3 |
| `lupo_referers_daily` hit_count/unique_actors | YES — SQL updated correctly | PARTIAL — JSON has UNSIGNED, SQL does not | See Section 2.4 |
| Doctrine enforcement (UNSIGNED/ENGINE/COLLATE) | PARTIAL — UNSIGNED clean, ENGINE/COLLATE still present | — | See Section 1 |

---

## Section 1: Doctrine Violations in Install SQL

### 1.1 AUTO_INCREMENT

None. The only mention is a comment at line 1171:
```
-- All PKs application-assigned (IdGenerator); no AUTO_INCREMENT.
```

**Status: CLEAN**

---

### 1.2 FOREIGN KEY / REFERENCES Constraints

None. All occurrences of the word `REFERENCES` in the file are:
- Section comments and inline documentation
- The literal table name `{{prefix}}references` (line 3489)
- Column names in INSERT statements (e.g., `preferences_json`)

No relational constraint clauses exist.

**Status: CLEAN**

---

### 1.3 ENGINE= and COLLATE= — 2 VIOLATIONS REMAINING

Gemini's doctrine pass did not remove these two. They remain:

| Table | Line | Clause |
|-------|------|--------|
| `lupo_dialog_recent_files` | 2476 | `) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;` |
| `lupo_dialog_pending_tasks` | 2494 | `) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;` |

All other 176 tables correctly omit ENGINE= and COLLATE=.

**Status: 2 VIOLATIONS — fix required**

```sql
-- lupo_dialog_recent_files line 2476: replace
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- with
);

-- lupo_dialog_pending_tasks line 2494: same fix
```

---

### 1.4 UNSIGNED Columns

None in install SQL. Gemini's doctrine pass successfully removed all UNSIGNED qualifiers from the SQL.

**Status: CLEAN in SQL**

Note: UNSIGNED violations persist in the live database and are reflected in 3 JSON files — see Section 3.

---

### 1.5 Primary Key Naming

All 178 tables use `<table_singular>_id` for the primary key column. No bare `id` columns.

**Status: CLEAN**

---

### 1.6 Integer Display Widths

No `INT(11)`, `TINYINT(1)`, or other display-width annotations in SQL.

**Status: CLEAN in SQL**

Note: `tinyint(1)` appears in 2 live-DB JSON files — see Section 3.

---

### 1.7 Timestamp Column Types

All `created_ymdhis`, `updated_ymdhis`, and `deleted_ymdhis` columns use `BIGINT`. No `DATETIME` or `TIMESTAMP` types anywhere.

**Status: CLEAN**

---

## Section 2: SQL vs JSON Schema Discrepancies

JSON files are auto-generated from the live database (`generate_toon_files.py`) and represent the live DB truth. SQL install is the canonical reproducible baseline. Discrepancies mean the install SQL does not reproduce the live DB.

### 2.1 lupo_votes — MAJOR MISMATCH

Gemini refactored `lupo_votes` to a polymorphic engagement schema in the live DB. The SQL install was not fully updated.

| Column | SQL (install) | JSON (live DB) |
|--------|--------------|----------------|
| `object_type` | `varchar(64) NOT NULL` | — (removed) |
| `object_id` | `bigint NOT NULL` | — (removed) |
| `actor_id` | `bigint NOT NULL` | — (removed) |
| `target_type` | — (missing) | `varchar(64) NOT NULL` |
| `target_id` | — (missing) | `bigint unsigned NOT NULL` |
| `cast_by_actor_id` | — (missing) | `bigint unsigned NOT NULL` |
| `vote_type` | — (missing) | `varchar(32) NOT NULL` |
| `reason_text` | — (missing) | `text` |
| `reason_code` | — (missing) | `varchar(64)` |
| `is_current` | — (missing) | `tinyint(1) NOT NULL DEFAULT 1` |
| `vote_value` | `tinyint NOT NULL` | `tinyint NOT NULL` |
| `vote_weight` | `float DEFAULT 1.0` | `float DEFAULT 1` |
| `created_ymdhis` | `bigint NOT NULL` | `bigint NOT NULL` |
| `updated_ymdhis` | `bigint NOT NULL` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint DEFAULT NULL` | `bigint` |

SQL unique index: `uq_vote_object_actor (object_type, object_id, actor_id)`
JSON unique index: `uq_vote_object_actor (target_type, target_id, cast_by_actor_id)`

**SQL total columns: 8 — JSON total columns: 14**

**Required SQL fix:** Replace the `lupo_votes` CREATE TABLE entirely with the new polymorphic schema:

```sql
CREATE TABLE {{prefix}}votes (
  vote_id             bigint NOT NULL,
  target_type         varchar(64) NOT NULL,
  target_id           bigint NOT NULL,
  cast_by_actor_id    bigint NOT NULL,
  vote_value          tinyint NOT NULL,
  vote_weight         float DEFAULT 1.0,
  vote_type           varchar(32) NOT NULL,
  reason_text         text DEFAULT NULL,
  reason_code         varchar(64) DEFAULT NULL,
  is_current          tinyint NOT NULL DEFAULT 1,
  created_ymdhis      bigint NOT NULL,
  updated_ymdhis      bigint NOT NULL,
  is_deleted          tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis      bigint DEFAULT NULL,
  PRIMARY KEY (vote_id),
  UNIQUE KEY uq_vote_object_actor (target_type, target_id, cast_by_actor_id),
  INDEX idx_lupo_votes_target_current (target_type, target_id, cast_by_actor_id, is_current),
  INDEX idx_vote_actor (cast_by_actor_id),
  INDEX idx_vote_created (created_ymdhis),
  INDEX idx_vote_is_deleted (is_deleted),
  INDEX idx_vote_object (target_type, target_id),
  INDEX idx_vote_value (vote_value)
);
```

---

### 2.2 lupo_memory_nodes — 2 COLUMNS MISSING IN SQL

Gemini added `embedding_vector` and `has_vector_index` to the live DB. SQL install does not include them.

| Column | SQL (install) | JSON (live DB) |
|--------|--------------|----------------|
| `embedding_vector` | — (missing) | `json DEFAULT NULL` |
| `has_vector_index` | — (missing) | `tinyint NOT NULL DEFAULT 0` |
| All other columns | Present | Present — exact match |

**Required SQL fix:** Add two columns before the PRIMARY KEY line in the `memory_nodes` CREATE TABLE:

```sql
  embedding_vector  json          DEFAULT NULL,
  has_vector_index  tinyint       NOT NULL DEFAULT 0,
```

---

### 2.3 lupo_paths_daily — 2 COLUMNS MISSING IN SQL

Gemini added `hit_count` and `unique_actors` to `lupo_paths_daily` in the live DB but SQL was not updated.

| Column | SQL (install) | JSON (live DB) |
|--------|--------------|----------------|
| `hit_count` | — (missing) | `int unsigned NOT NULL DEFAULT 1` |
| `unique_actors` | — (missing) | `int unsigned NOT NULL DEFAULT 0` |
| All other columns | Present | Present — exact match |

**Required SQL fix:** Add two columns to `paths_daily` (doctrine-correct, no UNSIGNED):

```sql
  hit_count       int NOT NULL DEFAULT 1,
  unique_actors   int NOT NULL DEFAULT 0,
```

---

### 2.4 lupo_referers_daily — SQL CORRECT; LIVE DB HAS UNSIGNED

SQL install was correctly updated with `hit_count` and `unique_actors` using signed `int`.

However, the live DB (JSON) has `int unsigned` on both columns:
```
"`hit_count` int unsigned NOT NULL DEFAULT 1"
"`unique_actors` int unsigned NOT NULL DEFAULT 0"
```

The SQL is doctrine-correct. The live database needs an `ALTER TABLE` to remove UNSIGNED — this is a live-DB correction, not a SQL fix.

---

### 2.5 lupo_dialog_read_log — IN JSON, NOT IN SQL

`lupo_dialog_read_log` exists as a JSON schema file but has no `CREATE TABLE` statement in the install SQL.

This table either:
- Was removed from the install script intentionally (SQL authoritative — JSON stale)
- Was added to the live DB but the install script was not updated (JSON authoritative — SQL incomplete)

**Action required:** Determine intent. If the table is active, add it to the install SQL. If deprecated, remove the JSON file.

---

### 2.6 lupo_channel_typing_previews and lupo_dialog_pending_tasks — FALSE POSITIVES

The Python table-count analysis reported these as missing from SQL. They are present at lines 2497 and 2479 respectively. The discrepancy is a parsing artifact: their CREATE TABLE blocks end with `ENGINE=InnoDB ...;` instead of `);`, causing the regex to fail. They exist and are counted in Section 1.3's ENGINE= violations.

---

## Section 3: Live DB Doctrine Violations (JSON)

These UNSIGNED and display-width violations exist in the live database. The SQL install correctly avoids them. They require `ALTER TABLE` on the live DB to achieve full doctrine compliance.

### 3.1 UNSIGNED Columns in Live DB

| Table | Column | Live Type | Doctrine-Correct Type |
|-------|--------|-----------|----------------------|
| `lupo_votes` | `target_id` | `bigint unsigned` | `bigint` |
| `lupo_votes` | `cast_by_actor_id` | `bigint unsigned` | `bigint` |
| `lupo_paths_daily` | `hit_count` | `int unsigned` | `int` |
| `lupo_paths_daily` | `unique_actors` | `int unsigned` | `int` |
| `lupo_referers_daily` | `hit_count` | `int unsigned` | `int` |
| `lupo_referers_daily` | `unique_actors` | `int unsigned` | `int` |

### 3.2 Display Width Annotations in Live DB

| Table | Column | Live Type | Doctrine-Correct Type |
|-------|--------|-----------|----------------------|
| `lupo_votes` | `is_current` | `tinyint(1)` | `tinyint` |
| `lupo_memory_nodes` | `has_vector_index` | `tinyint(1)` | `tinyint` |

---

## Section 4: Missing Soft Delete Columns

PRD 80 §9.8 requires `is_deleted TINYINT NOT NULL DEFAULT 0` and `deleted_ymdhis BIGINT DEFAULT NULL` on all mutable tables.

### 4.1 Exempt Tables (34 total — no action required)

**Audit/log/immutable (15):** lupo_bans_log, lupo_anubis_log, lupo_anubis_events, lupo_anubis_processing_log, lupo_anubis_recovery_attempts, lupo_anubis_operations, lupo_auth_audit_log, lupo_api_token_logs, lupo_unified_log, lupo_two_factor_audit, lupo_schema_migrations, lupo_search_rebuild_log, lupo_actor_runtime_events, lupo_rule_logs, lupo_routing_decisions

**Ephemeral/session/cache (15):** lupo_sessions, lupo_channel_state, lupo_actor_runtime_state, lupo_actor_sync_state, lupo_channel_typing_previews, lupo_magic_link_tokens, lupo_password_resets, lupo_auth_rate_limits, lupo_api_rate_limits, lupo_anubis_queue, lupo_anubis_quarantine, lupo_anubis_redirects, lupo_actor_filesystem, lupo_escalation_tasks, lupo_dialog_recent_files

**Versioning/immutable (4):** lupo_versions, lupo_actor_versions, lupo_agent_definition_versions, lupo_rolls

### 4.2 PARTIAL — Has is_deleted, Missing deleted_ymdhis (8 tables)

These tables can flag deletions but cannot record when. Add `deleted_ymdhis BIGINT DEFAULT NULL`.

| Table | Priority |
|-------|----------|
| `lupo_channel_content` | HIGH |
| `lupo_collection_links` | MED |
| `lupo_collection_map` | MED |
| `lupo_notifications` | MED |
| `lupo_reference_map` | MED |
| `lupo_registry` | MED |
| `lupo_help_topics` | LOW |
| `lupo_labs_violations` | LOW |

### 4.3 MISSING — Both Columns Absent (24 tables)

| Table | Priority |
|-------|----------|
| `lupo_action_authorization` | HIGH |
| `lupo_actor_actions` | HIGH |
| `lupo_actor_apps` | HIGH |
| `lupo_agent_boundaries` | HIGH |
| `lupo_agent_memory_config` | HIGH |
| `lupo_aliases` | HIGH |
| `lupo_api_clients` | HIGH |
| `lupo_api_webhooks` | HIGH |
| `lupo_atoms` | HIGH |
| `lupo_auth_providers` | HIGH |
| `lupo_channel_departments` | HIGH |
| `lupo_human_request_context` | HIGH |
| `lupo_identity_layers` | HIGH |
| `lupo_memory_rollups` | HIGH |
| `lupo_orchestrator_rules` | HIGH |
| `lupo_system_config` | HIGH |
| `lupo_world_registry` | MED |
| `lupo_crafty_user_mapping` | MED |
| `lupo_emotional_frameworks` | MED |
| `lupo_federation_discovery` | MED |
| `lupo_legacy_content_mapping` | MED |
| `lupo_paths_summary` | LOW |
| `lupo_referers` | LOW |
| `lupo_crafty_syntax_chat_mod_departments` | LOW |

---

## Section 5: Missing Table Documentation

57 of 178 tables have no `.md` file in `docs/database/lupopedia/tables/active/`.

Documentation coverage: **121 / 178 (68%)**

**Undocumented tables (57):**

```
lupo_actor_apps               lupo_actor_availability_status
lupo_actor_faucets            lupo_actor_filesystem
lupo_actor_pairing            lupo_actor_prompts
lupo_actor_relationships      lupo_actor_runtime_events
lupo_actor_runtime_state      lupo_actor_skills
lupo_actor_sync_state         lupo_actor_tools
lupo_actor_training           lupo_actor_versions
lupo_agent_boundaries         lupo_agent_capabilities
lupo_agent_definition_versions  lupo_agent_definitions
lupo_agent_llm_configs        lupo_agent_memory_config
lupo_agent_performance_stats  lupo_agent_tools
lupo_analytics_campaign_vars  lupo_anubis_log
lupo_anubis_operations        lupo_auth_rate_limits
lupo_auth_user_departments    lupo_channel_departments
lupo_channel_typing_previews  lupo_collection_links
lupo_collection_map           lupo_department_capabilities
lupo_dialog_pending_tasks     lupo_dialog_recent_files
lupo_emotional_frameworks     lupo_escalation_tasks
lupo_faucet_rules             lupo_federated_trust
lupo_federation_discovery     lupo_folder_map
lupo_hashtag_map              lupo_human_request_context
lupo_human_request_responses  lupo_human_requests
lupo_identity_context         lupo_identity_layers
lupo_magic_link_tokens        lupo_memory_edges
lupo_memory_nodes             lupo_pairing_rules
lupo_password_resets          lupo_reference_links
lupo_reference_map            lupo_rolls
lupo_schema_migrations        lupo_system_health_snapshots
lupo_thread_metadata          lupo_trust_ladder_registry
lupo_two_factor_audit         lupo_versions
lupo_world_registry
```

**Architecturally critical and undocumented:**
- `lupo_memory_nodes`, `lupo_memory_edges` (PRD 38 core tables)
- `lupo_human_requests`, `lupo_human_request_context`, `lupo_human_request_responses` (human oversight layer)
- `lupo_trust_ladder_registry` (trust infrastructure)
- `lupo_agent_definitions`, `lupo_agent_llm_configs` (agent configuration)

---

## Section 6: Recommendations

### Immediate — SQL Correctness

| # | Action | Target | Notes |
|---|--------|--------|-------|
| 1 | Remove ENGINE= COLLATE= | `lupo_dialog_recent_files` L2476, `lupo_dialog_pending_tasks` L2494 | Replace closing line with bare `);` |
| 2 | Replace `lupo_votes` CREATE TABLE | Full table replacement | New polymorphic schema — see Section 2.1 for exact SQL |
| 3 | Add `embedding_vector`, `has_vector_index` to `lupo_memory_nodes` | 2 columns before PRIMARY KEY | See Section 2.2 |
| 4 | Add `hit_count`, `unique_actors` to `lupo_paths_daily` | 2 columns, `int NOT NULL` (no UNSIGNED) | See Section 2.3 |
| 5 | Resolve `lupo_dialog_read_log` | Add to SQL or delete JSON | Ambiguous intent |

### Short-term — Live DB Corrections

| # | Action | Target |
|---|--------|--------|
| 6 | ALTER to remove UNSIGNED | `lupo_votes.target_id`, `lupo_votes.cast_by_actor_id`, `lupo_paths_daily.hit_count`, `lupo_paths_daily.unique_actors`, `lupo_referers_daily.hit_count`, `lupo_referers_daily.unique_actors` |
| 7 | ALTER to remove tinyint(1) | `lupo_votes.is_current`, `lupo_memory_nodes.has_vector_index` |
| 8 | Add `deleted_ymdhis` | 8 PARTIAL tables (Section 4.2) |
| 9 | Add full soft-delete columns | 24 HIGH/MED MISSING tables (Section 4.3) |

### Long-term — Documentation

| # | Action |
|---|--------|
| 10 | Write table docs for 57 undocumented tables |
| 11 | Prioritize: memory_nodes, memory_edges, trust_ladder_registry, human_requests, agent_definitions |

---

## Section 7: Files Examined

| Source | File / Path | Count |
|--------|-------------|-------|
| Install SQL | `database/lupopedia/mysql/install/install_new_lupopedia.sql` | 4,770 lines, 178 tables |
| JSON schemas | `database/lupopedia/json/*.json` | 179 files |
| Table docs | `docs/database/lupopedia/tables/active/*.md` | 121 files |
| PRDs consulted | PRD 80, PRD 02, PRD 38 | — |
| Agent brief | `CLAUDE.md` | — |

---

## Appendix: Violation Scorecard

| Doctrine | Rule | SQL Status | Live DB (JSON) Status |
|----------|------|------------|-----------------------|
| No AUTO_INCREMENT | PRD 80 §3.2 | CLEAN | CLEAN |
| No FOREIGN KEY | PRD 80 §3.1 | CLEAN | CLEAN |
| No ENGINE= | PRD 80 §9.12 | **2 violations** | N/A |
| No COLLATE= | PRD 80 §9.12 | **2 violations (same)** | N/A |
| No UNSIGNED | PRD 80 | CLEAN | **6 columns in 3 tables** |
| No INT display widths | PRD 80 | CLEAN | **2 columns in 2 tables** |
| BIGINT timestamps | PRD 80 §3.3 | CLEAN | CLEAN |
| PK `<table>_id` naming | PRD 80 §9.7 | CLEAN | CLEAN |
| Soft delete — partial | PRD 80 §9.8 | 8 non-exempt tables | — |
| Soft delete — missing | PRD 80 §9.8 | 24 non-exempt tables | — |
| SQL/JSON parity | — | **4 tables out of sync** | — |
| JSON-only tables | — | **1 (dialog_read_log)** | — |
