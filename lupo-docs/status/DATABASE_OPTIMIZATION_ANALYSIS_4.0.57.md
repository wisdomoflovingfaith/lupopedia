# v4.0.57 Database Optimization Analysis and Recommendations

---
# LUPOPEDIA HEADERS (replaces FLARE) — see http://www.lupopedia.com/status/DATABASE_OPTIMIZATION_ANALYSIS_4.0.57
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "report"
  file_path_from_root: "docs/status/DATABASE_OPTIMIZATION_ANALYSIS_4.0.57.md"
  last_modified_utc: "20260306"
  system_version: "4.0.57"
  channel_id: 42
  actor_id: 1003
  delegation_chain: "1003:10000"
  artifact_type: "report"
  artifact_kind: "analysis"
  purpose: "v4.0.57 Database Optimization Analysis and Recommendations"
  mood_rgb: "4169E1"
  traits: ["report", "v4.0.57", "database", "analysis"]
  tags: ["4.0.57", "database", "optimization", "cursor"]
  lupo_agent: "cursor"
lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/versions/REQUIRED_TABLES_4.0.21.md", type: "references", weight: 0.9 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "references", weight: 0.9 }
    - { to: "docs/status/V4.0.57_TASK_PLAN.md", type: "references", weight: 0.8 }
lupopedia.footer:
  last_verified: "20260306"
  last_verified_by: "cursor"
---

## 1. Analysis Summary

This report analyzes the Lupopedia database structure for v4.0.57 using:

- **TOONs:** Schema definitions in `lupo-database/lupopedia/toon/` and `lupo-docs/toons/` (canonical column/types).
- **Install SQL:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (single schema source).
- **Seed/migrations:** `seed_registry_comprehensive_4.0.45.sql` and related manifests under `lupo-database/lupopedia/mysql/`.
- **REQUIRED_TABLES:** `lupo-docs/versions/REQUIRED_TABLES_4.0.21.md` (importer + runtime required; optional vs future-features).

**Scope:** Channel 42–relevant tables (actors, channels, agent faucets, contents, sessions, unified log), index coverage, soft-delete consistency, and doctrine alignment. **Flame/FLARE-neutral:** No mandatory flame blocks; recommendations respect Database Logic Prohibition (no FK, triggers, views, stored procedures, or DB-generated timestamps).

**Findings:** Index coverage is generally strong on hot paths (actor_id, channel_id, is_deleted, created_ymdhis). A few targeted index additions and one cross-DB index compatibility concern are recommended. Query patterns in `lupo-includes/` and `app/` use prepared statements and PDO_DB; no raw SQL anti-patterns observed. Faucet-related tables and Channel 42 usage (faucets, actors, channels) are aligned with TOON and install SQL.

---

## 2. Dependencies Reviewed

| Dependency | Location | Notes |
|------------|----------|--------|
| TOON schema | `lupo-database/lupopedia/toon/*.toon.json`, `lupo-docs/toons/*.toon.json` | 432 TOON files; install SQL is source of truth; TOONs generated from install. |
| Install SQL | `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` | Single install script; manifest: `install_manifest.txt`. |
| Seed/migrations | `lupo-database/lupopedia/mysql/seed/`, `migrations/`, `manifest/` | Execution order via manifests; no installer load from `database/migrations/`. |
| REQUIRED_TABLES | `lupo-docs/versions/REQUIRED_TABLES_4.0.21.md` | Importer tables + runtime-required; optional list; future-features (4 tables) not in install. |

---

## 3. Identified Issues (Tabular)

| # | Issue | Table(s) / Area | Severity | Doctrine note |
|---|--------|------------------|----------|----------------|
| 1 | **Functional indexes on JSON columns** | lupo_contents | Low | `lupo_contents_idx_has_media`, `_has_events`, `_has_hashtags` use `(JSON_LENGTH(...) > 0)`. MySQL 8.0.13+; MariaDB/PostgreSQL syntax differs. May break on PostgreSQL or older MySQL. |
| 2 | **Missing composite index for session-by-channel lookups** | lupo_sessions | Low | Queries that filter by `channel_id` (e.g. “sessions in channel 42”) have no dedicated index; `channel_id` appears in table but only single-column indexes exist for actor, domain, last_seen, expires, etc. |
| 3 | **Duplicate index names on lupo_unified_log** | lupo_unified_log | Low | Both `idx_created_ymdhis` and `lupo_unified_log_idx_created_ymdhis` exist; redundant. Prefer single naming convention (doctrine: `<table>_idx_<columns>`). |
| 4 | **Soft-delete consistency** | Multiple | Info | Most tables use `is_deleted` + `deleted_ymdhis`; application code must consistently filter `WHERE is_deleted = 0` (or equivalent). No schema change; audit in application layer. |
| 5 | **Optional tables still in install** | install_new_lupopedia.sql | Info | REQUIRED_TABLES lists optional tables (e.g. lupo_aliases, lupo_anubis_orphaned, lupo_tldnr). Consider moving to future_features_lupopedia.sql in a later patch to reduce install surface. |

---

## 4. Recommendations (Tabular: Effort / Impact)

| # | Recommendation | Effort | Impact | Backward compatibility |
|----|----------------|--------|--------|------------------------|
| R1 | **Document or conditionally create expression indexes for lupo_contents** | Low | Medium | Document MySQL vs MariaDB vs PostgreSQL; if supporting only MySQL 8.0+, keep as-is; otherwise add migration that creates equivalent indexes per engine or drops them for non-MySQL. |
| R2 | **Add index on lupo_sessions (channel_id)** for channel-scoped session lists | Low | Low–Medium | Add `CREATE INDEX lupo_sessions_idx_channel ON lupo_sessions (channel_id);` in a one-time migration; update install_new_lupopedia.sql to match. No app logic change required. |
| R3 | **Deduplicate lupo_unified_log created_ymdhis index** | Low | Low | Drop one of `idx_created_ymdhis` or `lupo_unified_log_idx_created_ymdhis`; keep single index; align TOON and install SQL. |
| R4 | **Application audit: is_deleted filters** | Medium | Medium | Audit all SELECTs in lupo-includes and app that reference tables with `is_deleted`; ensure `WHERE is_deleted = 0` (or equivalent) where intended. No schema change. |
| R5 | **Optional tables → future_features (later patch)** | Medium | Low | Move optional tables from REQUIRED_TABLES optional list into future_features_lupopedia.sql and out of install_new_lupopedia.sql per doctrine; requires REQUIRED_TABLES and audit doc update. |

All recommendations are **doctrine-compliant:** no new FK, triggers, views, stored procedures, or DB-generated timestamps.

---

## 5. Query and Usage Patterns (Channel 42 / Faucets)

- **Faucets:** `lupo_agent_faucets` is indexed on `actor_id`, `slug`, `domain_id`, `is_default`. FaucetLoader resolves by (channel_id, actor_id) then loads from file or DB; lookup order is application-side. No additional index required for current usage.
- **Actors/channels:** `lupo_actors` (slug, actor_type, is_active, created_ymdhis), `lupo_channels` (channel_key, federation_node_id, status_flag, etc.) are well indexed for lookups used in bootstrap and channel/actor services.
- **Contents:** `lupo_contents` has strong index set (file_path_from_root, content_parent_id, content_type, federation_node_id, actor_id, is_deleted, created_ymdhis, updated_ymdhis). Expression indexes on JSON columns are the only portability concern (see Issue 1).
- **Sessions:** Lookups by session_id (PK), actor_id, federation_node_id, last_seen_ymdhis, expires_ymdhis, and cleanup (is_deleted, last_seen_ymdhis) are covered. Adding `channel_id` index (R2) improves channel-scoped session queries if used.

No SQL execution was performed; analysis is based on install script, TOON references, and codebase patterns. EXPLAIN-style analysis can be run by human/ops against a live DB when available.

---

## 6. Validation (Faucets)

Faucet validation was run as part of this analysis (relevant to Channel 42 and agent faucets):

```bash
php lupo-bin/validate_faucets.php 2>&1
```

**Result:** Exit code **0**. No stdout captured (script reports only on error or with verbose options). ID-scoped faucets under `lupo-database/lupopedia/actors/faucets/` (e.g. 6, 7) and TOON alignment are assumed valid per successful exit. For full output, run:

```bash
php lupo-bin/validate_faucets.php 2>&1 | tee docs/status/faucet_validation_4.0.57.txt
```

---

## 7. Next Steps

1. **Implement R2 and R3** in a single one-time migration (e.g. `database/migrations/dev_YYYYMMDD_db_optimization_indexes.sql` or under `lupo-database/lupopedia/mysql/migrations/` per project layout) and update `install_new_lupopedia.sql` so fresh installs match.
2. **Document R1** in a short doctrine or migration note (expression index portability) and decide per supported DB matrix.
3. **Schedule R4** (application audit for is_deleted) as a follow-up task; no DB change.
4. **Defer R5** to a later patch when optional-table migration is planned; update REQUIRED_TABLES and audit docs when done.
5. **Delegation:** For any new flame/FLARE artifact that summarizes this report, delegate to **Lilith (actor 2)** for meta-review if desired. **Captain Wolfie (10000):** Channel 0 DB resets/installs are human-only; no agent execution.

---

## 8. Timestamp and Actor

- **Report generated:** 2026-03-06  
- **Actor ID:** 1003 (Cursor IDE Agent)  
- **Channel:** 42  
- **lupo_agent:** cursor  

---

*End of report.*
