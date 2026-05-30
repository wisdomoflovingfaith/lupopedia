---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: decision
  when_updated: "20260406200000"
  file_path_from_root: "docs/versions/4.0.94/decisions/20260406_200000_DECISION_APPROVED_schema_review_chronos_activation_migration_docs.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.94/decisions/20260406_200000_DECISION_APPROVED_schema_review_chronos_activation_migration_docs.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: decision
  artifact_kind: approved
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# DECISION: Schema Review, CHRONOS Activation, Migration Documentation Update
**Status:** APPROVED  
**WHO:** claude-code (actor_id 102)  
**WHEN:** 2026-04-06 ~20:00 UTC  
**WHERE:** `database/`, `agents/chronos/`, `docs/database/lupopedia/tables/migrations/`

---

## Context

This decision record covers three discrete work blocks completed in a single thread on 2026-04-06, continuing from a prior session that ran out of context.

---

## Decision 1: Full Schema Review of install_new_lupopedia.sql

**WHAT:** Complete structural analysis of the 168-table Lupopedia 4.0.x install schema.

**WHY:** The schema had critical structural flaws (wrong PK on lupo_actors, lupo_agents contaminated with LLM config, duplicate index block causing SQL error, etc.) that would cause runtime failures and doctrine violations.

**HOW:**
- Read `database/lupopedia/mysql/install/install_new_lupopedia.sql` in 11 chunks
- Extracted all 168 table names
- Categorized 30+ flaws across 9 categories: critical structural, naming violations, normalization, missing tables, redundant tables, indexing, JSON vs column, actor/agent separation, constitutional compliance

**DECISIONS MADE:**
1. `lupo_actors` PRIMARY KEY changed from `actor_name varchar(64)` → `actor_id bigint NOT NULL`
2. `lupo_agents` split into `lupo_agent_definitions` (doctrine identity) + `lupo_agent_llm_configs` (provider config)
3. `system_prompt text` column replaced by `system_prompt_path varchar(512)` (filesystem reference, not inline blob)
4. `paired_actor_id` extracted to `lupo_actor_pairing` table
5. `adversarial_role` + `adversarial_oversight_actor_id` extracted to `lupo_actor_relationships` table
6. `metadata text` removed; `metadata_json json` retained
7. `department_id` column removed from `lupo_actors`; junction table `lupo_actor_departments` is sole dept path
8. `lupo_agent_faucets` renamed to `lupo_actor_faucets` (Faucet Proxy Pattern operates at actor layer)
9. `governance_overrid_id` typo corrected to `governance_override_id`
10. 20+ missing tables identified and specified (KAIROS memory, runtime state, faucet rules, etc.)

**DELIVERABLES PRODUCED:**
- `database/lupopedia/mysql/schema_review/schema_review_20260406.md` — full flaw analysis
- `database/lupopedia/mysql/schema_review/schema_corrected_core.sql` — corrected DDL (21 tables)
- `database/lupopedia/mysql/schema_review/schema_corrected_missing.sql` — 10 new tables
- `database/lupopedia/mysql/schema_review/schema_corrected_identity_model.md` — corrected identity model and relationships

**USER AUTHORIZATION:** "There is NO migration burden. There is NO legacy data to preserve. You are free to redesign the schema to match the doctrine perfectly."

---

## Decision 2: CHRONOS Kernel Agent Activation

**WHAT:** Activated the CHRONOS agent from reserved-slot status to active kernel agent.

**WHY:** CHRONOS had a complete role definition (Coordinated Hierarchical Reasoning & Optimization for Network Operation Systems) but its files were stubs. The ecosystem needed a temporal reasoning and dependency analysis engine.

**HOW:** Created all 5 required agent files with full doctrine content:

| File | Content |
|---|---|
| `agent.json` | Active status, domain boundaries map, yields_orchestration_to WOLFIE |
| `identity.json` | `is_kernel: true`, `agent_class: kernel_reasoning_engine`, 12 constitutional constraints |
| `capabilities.json` | 18 capabilities with notes + explicit `out_of_scope` array |
| `tools.json` | 15 tools across 4 categories (dependency_analysis, time_reasoning, scheduling, optimization) |
| `system_prompt.txt` | Full kernel agent prompt with CAN/CANNOT, domain boundaries, tool pipelines |

**KEY BOUNDARY DECISIONS:**
- CHRONOS is advisory-only. No execution authority. No system/DB/file/network access.
- KAIROS owns memory consolidation — CHRONOS does not overlap.
- WOLFIE owns orchestration — CHRONOS yields unconditionally.
- HYPNOS owns sleep/downtime — CHRONOS does not schedule downtime.
- All CHRONOS tools are analytical outputs, not commands.

**OVERLAP ANALYSIS (all clear):**
- KAIROS: name overlap only (Greek time concepts); functional domains distinct
- HYPNOS: peripheral on maintenance scheduling; different layer/specificity
- WOLFIE: governance boundary enforced via `yields_orchestration_to` field

---

## Decision 3: Crafty Syntax Migration Documentation Update

**WHAT:** Updated migration docs and import SQL to reflect corrected schema.

**WHY:** The corrected schema removed/renamed columns that the import SQL was actively inserting into. Without updating the import SQL, any fresh install + migration would fail.

**HOW:**
1. Read all 1790 lines of `import_from_old_crafty_syntax.sql` in chunks
2. Verified actual import logic (not assumed) — documented in `migration_impact_summary.md`
3. Applied 4 targeted edits to the import SQL

**VERIFIED FACTS (from SQL, not assumptions):**
- `actor_id = 10000 + livehelp_users.user_id` — no IdGenerator in this SQL
- Actors are created from `lupo_auth_users`, not directly from `livehelp_users`
- `actor_source_id = auth_user_id`, `actor_source_type = '{{prefix}}auth_users'`
- Department hybrid actors: `actor_id = 280000 + department_id`
- Timestamp conversion: Unix `lastaction` → `DATE_FORMAT(FROM_UNIXTIME(x), '%Y%m%d%H%i%S')`
- 34 Crafty tables processed; 8 are charset/comment-only with no data import

**IMPORT SQL CHANGES APPLIED:**
1. `actor_departments` INSERT: added `is_primary = 0` + `ON DUPLICATE KEY UPDATE` (UNIQUE constraint safety)
2. Operator actors INSERT: removed `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`; added `agent_key = NULL`; `actor_id` promoted to PK position
3. Dept hybrid actors INSERT: same removals; `metadata = '...'` → `metadata_json = JSON_OBJECT(...)`; `agent_key = 'wolfie'`
4. New INSERT blocks: `lupo_actor_filesystem` + `lupo_actor_sync_state` for both operator actors and dept hybrid actors (4 new INSERT blocks, all idempotency-guarded with NOT EXISTS)

**MIGRATION DOCS UPDATED:**
- `livehelp_users_migration.md` — added sections 10–12: actor creation phase, satellite tables, corrected schema impact
- `livehelp_operator_departments_migration.md` — added UNIQUE constraint explanation, multi-phase insert sequence
- `new_schema_tables_crafty_mapping.md` — NEW: maps all 27 new schema tables to Crafty source (or documents absence)

---

## Status

All three decisions approved and implemented. Files written to disk.

**NOT completed in this thread:**
- Option B migration (wolfie→/1, lilith→/2 folder move) — interrupted before execution
- Step 3 (Actor Reconstruction Pass) — deferred to next session
- Writing the corrected schema back into `install_new_lupopedia.sql` (schema review produced separate corrected files; the install file itself was not rewritten)

---

*Recorded by: claude-code (actor_id 102) | 2026-04-06 ~20:00 UTC*
