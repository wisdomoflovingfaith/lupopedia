# Schema Review — Lupopedia 4.0.x Install Schema
# Date: 20260406 | Reviewer: claude-code (actor_id 102) | Version reviewed: install_new_lupopedia.sql

---

## Overview

Total tables analyzed: **168**
Review scope: All tables in `database/lupopedia/mysql/install/install_new_lupopedia.sql`
Constitutional reference: `rules/root/LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md`

This review categorizes every flaw found. No migration burden. Full redesign is authorized.

---

## CRITICAL STRUCTURAL FLAWS

These will cause runtime errors, data corruption, or doctrine violations.

### C1 — lupo_actors PRIMARY KEY is wrong

**Current:** `PRIMARY KEY (actor_name)`
**Required:** `PRIMARY KEY (actor_id)`

The doctrine comment in the file itself says "RESERVED ID DOCTRINE: actor_id is NOT ; application must supply explicit ID." — yet `actor_id bigint DEFAULT NULL` is a nullable secondary column, not the PK. `actor_name` as PK couples the entire identity model to a mutable string. Every lookup, join, and reference must be by `actor_id` per doctrine.

**Fix:** Set `actor_id bigint NOT NULL` as PRIMARY KEY. Make `actor_name` a UNIQUE index.

---

### C2 — lupo_actors.actor_id is nullable

**Current:** `actor_id bigint DEFAULT NULL`
**Required:** `actor_id bigint NOT NULL`

A nullable actor_id breaks the Reserved ID Doctrine. Application must always supply an explicit ID. NULL is not a valid actor identity.

---

### C3 — lupo_agents is an LLM config table, not an agent identity table

**Current columns:** `temperature`, `top_p`, `max_tokens`, `presence_penalty`, `frequency_penalty`, `cost_per_1k_tokens`, `provider`, `api_key_id`, `system_prompt text`, `avg_response_time_ms`, `total_tokens_processed`, `success_rate`

This table conflates two entirely separate concerns:
1. **Lupopedia doctrine agent identity** — slug, layer, role, is_kernel, learning_boundary, status, version
2. **LLM model configuration** — provider, temperature, top_p, tokens, costs, penalties

A Lupopedia agent (e.g., CHRONOS, WOLFIE, LILITH) is a defined entity in the ecosystem. An LLM configuration is a runtime provider setting. These must be separate tables.

**Missing from lupo_agents:** `slug`, `layer`, `is_kernel`, `is_required`, `learning_boundary`, `department_id`, `agent_class`, `lineage_json`, `status`

**Fix:** Split into:
- `lupo_agent_definitions` — doctrine agent identity
- `lupo_agent_llm_configs` — LLM provider configuration

---

### C4 — lupo_edges indexes defined TWICE (SQL syntax error)

Lines 401–417 define indexes for `lupo_edges`. Lines 2226–2241 define the **exact same index set again** for the same table. MySQL will error on duplicate index names. The install will fail at that line.

**Fix:** Remove the second block entirely.

---

### C5 — actor_id 2 historical collision documented in seed comments

A seed comment reads: `"Windsurf IDE: actor_id 220 (reassigned from conflicting actor_id 2 = CAPTAIN)"`. This confirms a prior actor_id collision was resolved by reassigning Windsurf from 2 to 220. The schema does not enforce actor_id uniqueness at the PK level (because PK is actor_name), which allowed this collision to exist. Once C1 is fixed, the PK constraint will prevent future collisions.

---

### C6 — lupo_actor_handshakes uses backtick-quoted `utc_timestamp`

**Current:** `` `utc_timestamp` bigint NOT NULL ``

`UTC_TIMESTAMP` is a reserved MySQL function name. Using it as a column name with backticks will work but is a maintenance trap. Any raw query that omits backticks will silently call the function instead of referencing the column.

**Fix:** Rename to `handshake_ymdhis bigint NOT NULL`.

---

## NAMING VIOLATIONS

### N1 — lupo_rolls is a typo

`lupo_rolls` should be `lupo_channel_actor_roles`. The current name gives no information about what the table contains.

### N2 — governance_overrid_id typo

`lupo_governance_overrides.governance_overrid_id` — missing 'e'. Should be `governance_override_id`.

### N3 — lupo_agent_faucets should be lupo_actor_faucets

The Faucet Proxy Pattern (v4.0.90+) operates at the **actor** layer (all IDE faucets execute as HEPHAESTUS actor_id 102). The table references `actor_id` throughout but is named `agent_faucets`. Per doctrine, faucets are actor-scoped, not agent-scoped.

**Fix:** Rename to `lupo_actor_faucets`.

### N4 — Timestamp column naming inconsistency

Multiple tables mix naming conventions:
- ANUBIS tables: `created_utc`, `updated_utc`, `attempt_utc`, `quarantined_utc` — should be `*_ymdhis`
- `lupo_auth_audit_log`: `created_at`, `updated_at` — should be `created_ymdhis`, `updated_ymdhis`
- `lupo_crafty_user_mapping`: `created_at`, `updated_at` — same
- `lupo_actor_handshakes`: `utc_timestamp`, `expires_utc` — should be `handshake_ymdhis`, `expires_ymdhis`
- `lupo_actor_moods`: `timestamp_utc` — should be `recorded_ymdhis`

**All timestamps must be BIGINT YYYYMMDDHHIISS format with `_ymdhis` suffix per constitutional rule.**

### N5 — lupo_event_metadata is orphaned

The parent `lupo_events` table was removed in v4.0.86 (consolidated into `lupo_unified_log`). `lupo_event_metadata` references events by `event_id` but there is no events table to reference. This table is either orphaned or should reference `unified_log_id`.

---

## NORMALIZATION VIOLATIONS

### NV1 — lupo_actors has 40+ columns — requires decomposition

The table collapses at minimum 6 distinct concerns into one row:

| Concern | Columns |
|---|---|
| Core identity | actor_id, actor_name, slug, name, actor_type |
| Status flags | is_active, is_deleted, is_kernel, can_login, is_agent |
| Dept/Tier | department_id, actor_tier |
| Pairing | paired_actor_id, adversarial_role, adversarial_oversight_actor_id |
| File system | actor_root_path, workspace_path, php_namespace |
| Sync state | who_json_sync_status, last_sync_ymdhis |
| Metadata | metadata text, metadata_json json |
| Auth | auth_user_id, identity_provider_config json |
| Access | web_restrict_act_as_creator_or_root |
| Federation | primary_federation_node_id |

**Fix:** Extract into satellite tables:
- `lupo_actor_sync_state` — sync fields
- `lupo_actor_pairing` — paired_actor_id, adversarial_role, adversarial_oversight_actor_id
- `lupo_actor_filesystem` — actor_root_path, workspace_path, php_namespace
- Keep metadata_json; remove `metadata text` duplicate

### NV2 — Dual metadata columns

`lupo_actors` has both `metadata text` and `metadata_json json`. These are duplicate storage for the same data. The `text` column is unstructured and unindexable. The `json` column is the canonical form.

**Fix:** Remove `metadata text`. Use `metadata_json json` only.

### NV3 — Dual department path

`lupo_actors.department_id bigint` AND `lupo_actor_departments` junction table create two separate department assignment paths. One actor could have `department_id = 3` in the main row while `lupo_actor_departments` says `department_id = 5`. No single source of truth.

**Fix:** Remove `department_id` from `lupo_actors`. Use `lupo_actor_departments` exclusively.

### NV4 — Template string literal as column default

`lupo_actors.actor_root_path varchar(512) DEFAULT 'actors/{actor_id}'`

SQL column defaults are scalar literals. The string `'actors/{actor_id}'` will be stored verbatim — `{actor_id}` is not interpolated by MySQL. This is misleading and functionally wrong. The default is useless.

**Fix:** Set `DEFAULT NULL` and compute the path in application code via `ActorService`.

### NV5 — Paired actor stored in main row

`lupo_actors.paired_actor_id bigint NOT NULL DEFAULT 0` encodes a pairing relationship in the actor row itself. This cannot represent:
- Pairing history
- Pairing roles
- Multiple pairings
- Pairing start/end times

**Fix:** Move to `lupo_actor_pairing` table (see NV1).

### NV6 — Adversarial relationship encoded in lupo_actors

`adversarial_role varchar(64)` and `adversarial_oversight_actor_id bigint` encode the LILITH-style adversarial relationship directly in the actor row. Same problems as NV5 — no history, no role metadata, no multi-actor oversight.

**Fix:** Move to `lupo_actor_relationships` table.

### NV7 — lupo_memory_rollups.actor_id is int, not bigint

All actor references must be `bigint`. `int` truncates actor IDs above 2,147,483,647. This is a type inconsistency across the schema.

**Fix:** `actor_id bigint NOT NULL`.

---

## MISSING TABLES

These tables are required by doctrine, referenced by agents, or needed for structural completeness. None exist in the current schema.

### Agent/Actor Identity Layer
- `lupo_agent_definitions` — doctrine agent identity (slug, layer, role, is_kernel, learning_boundary, version, status)
- `lupo_agent_llm_configs` — LLM provider config (temperature, top_p, provider, api_key_id, costs) — split from lupo_agents
- `lupo_actor_pairing` — actor pairing relationships with history
- `lupo_actor_relationships` — adversarial, coordination, and oversight relationships
- `lupo_actor_filesystem` — actor filesystem paths (root_path, workspace_path, php_namespace)
- `lupo_actor_sync_state` — WHO.json sync state tracking
- `lupo_actor_versions` — actor version history
- `lupo_agent_versions` — agent definition version history (currently referenced but missing core identity fields)

### Capability & Tool Tracking
- `lupo_agent_capabilities` — capability definitions per agent (not per actor — per agent template)
- `lupo_agent_tools` — tool definitions per agent
- `lupo_agent_tool_constraints` — tool-level constraints (no system calls, no DB writes, etc.)
- `lupo_agent_memory_config` — memory configuration per agent (lupo_memory_rollups references agents but no config table exists)
- `lupo_agent_boundaries` — domain boundary definitions per agent

### Faucet & Pairing Rules
- `lupo_faucet_rules` — faucet proxy rules (which actor_id to execute as, which IDE, conditions)
- `lupo_pairing_rules` — rules governing actor pairing and reassignment

### Department Capability
- `lupo_department_capabilities` — capability grants at the department level

### KAIROS Memory System
- `lupo_kairos_observations` — raw KAIROS observation records
- `lupo_kairos_memory` — consolidated KAIROS memory entries
- (KAIROS is a defined kernel agent with memory consolidation capabilities — no backing tables exist)

### Identity & Context Layers
- `lupo_identity_layers` — the two-layer identity model (template vs runtime) definition
- `lupo_identity_context` — active identity context per session/channel

### Runtime State
- `lupo_runtime_state` — current runtime state per actor (active session, last tool call, current task)
- `lupo_runtime_events` — runtime lifecycle events (activate, deactivate, handoff, error)

### Versioning
- `lupo_versions` — schema and system version registry
- `lupo_version_changelog` — version history entries

---

## REDUNDANT / CONSOLIDATE-CANDIDATE TABLES

### R1 — lupo_edge_types + lupo_edge_type_definitions

Two tables defining edge types. Neither references the other. Merge into one authoritative `lupo_edge_types` table with all definition fields.

### R2 — lupo_edge_map

A secondary mapping table alongside `lupo_edges`. Any routing or mapping concern can be expressed as an edge_type on `lupo_edges` itself or via `lupo_registry`. Remove.

### R3 — lupo_questions / lupo_answers / lupo_question_map

These three tables attempt to model Q&A as a separate system while the truth/assertion system (lupo_truths, lupo_assertions) covers overlapping ground. Q&A is a subtype of truth assertion. These should be deprecated and the Q&A pattern should use the truth system.

### R4 — lupo_reference_* tables (4 tables)

Four narrowly-scoped reference tables that could be unified into `lupo_metadata` with `meta_type = 'reference'` or into a single `lupo_reference_data` table with a `reference_type` discriminator.

### R5 — lupo_event_metadata (orphaned)

Parent `lupo_events` removed in 4.0.86. This table has no parent. Either point it at `lupo_unified_log` or remove it.

---

## INDEXING ISSUES

### I1 — lupo_department_roles: missing UNIQUE on (actor_id, department_id, role_key)

Without this uniqueness constraint, the same actor can have duplicate role entries for the same department. Application-enforced uniqueness is insufficient.

### I2 — lupo_actor_departments: missing UNIQUE on (actor_id, department_id)

An actor could appear in the same department twice in the junction table.

**Fix:** `CREATE UNIQUE INDEX ... ON lupo_actor_departments (actor_id, department_id)`

### I3 — lupo_actor_auth_users: 7-column composite indexes

Two 7-column composite indexes exist:
- `(actor_id, relationship_role, status, is_deleted, is_primary, routing_priority, auth_user_id)`
- `(actor_id, status, is_deleted, relationship_role, is_primary, routing_priority, auth_user_id)`

These are over-engineered for a lookup table. MySQL uses only the leftmost prefix of a composite index for most queries. A 7-column covering index should be added only if a specific query requires it. Replace with targeted 2-3 column indexes for the actual query patterns.

### I4 — lupo_actor_moods: no PRIMARY KEY, no is_deleted

`lupo_actor_moods` has no PK (just columns `actor_id`, `mood_r`, `mood_g`, `mood_b`, `mood_framework`, `timestamp_utc`). No `is_deleted`. No way to soft-delete mood records or identify rows uniquely.

**Fix:** Add `actor_mood_id bigint NOT NULL` as PK. Add `is_deleted tinyint NOT NULL DEFAULT 0`. Rename `timestamp_utc` → `recorded_ymdhis bigint NOT NULL`.

---

## JSON vs COLUMN VIOLATIONS

### J1 — lupo_actor_collections.emotional_geometry_baseline json

Philosophy and emotional geometry state do not belong in a collections junction table. This is a cross-cutting actor attribute that belongs in a dedicated actor attribute or identity table.

### J2 — lupo_agents.system_prompt text

The system_prompt for an agent should be a **filesystem path reference**, not an inline text blob. Agent system prompts live in `agents/{slug}/system_prompt.txt`. The database should store the path, not the content. This avoids sync drift between file and database.

**Fix:** Replace `system_prompt text` with `system_prompt_path varchar(512)`.

---

## ACTOR vs AGENT SEPARATION VIOLATIONS

### AS1 — lupo_agent_tool_calls logs by agent_id but caller is an actor

`lupo_agent_tool_calls` records tool call executions. The entity executing a tool is an **actor** (a runtime instance), not an agent (a template). Logging by `agent_id` loses the runtime identity of which specific actor instance made the call.

**Fix:** Add `actor_id bigint NOT NULL` as the primary execution identity. Keep `agent_id` as a reference to the agent definition.

### AS2 — lupo_agent_faucets references actor_id but is named agent_faucets

See N3. Renaming is required.

### AS3 — lupo_agents missing all doctrine identity fields

Current `lupo_agents` has no: `slug`, `layer`, `is_kernel`, `is_required`, `learning_boundary`, `agent_class`, `lineage_json`, `department_id`, `status`

These are all first-class doctrine fields defined in every `identity.json`. After splitting LLM config to a separate table, the doctrine identity fields must be added to `lupo_agent_definitions`.

---

## CONSTITUTIONAL COMPLIANCE ISSUES

### CC1 — No AUTO_INCREMENT in schema (CORRECT — compliant)
All PKs use explicit `bigint NOT NULL` — no AUTO_INCREMENT. This is correct per doctrine.

### CC2 — No FKs, triggers, stored procedures (CORRECT — compliant)
No FOREIGN KEY constraints, no TRIGGER definitions, no stored procedures or functions. Compliant.

### CC3 — All timestamps BIGINT (PARTIALLY COMPLIANT)
Most timestamps are `bigint NOT NULL`. Violations listed in N4. Specific non-conforming columns must be renamed and retyped.

### CC4 — Soft-delete pattern used consistently (MOSTLY CORRECT)
Most tables use `is_deleted tinyint NOT NULL DEFAULT 0` + `deleted_ymdhis`. Exception: `lupo_actor_moods` (no PK, no is_deleted).

---

## SUMMARY TABLE

| Category | Count | Severity |
|---|---|---|
| Critical structural flaws | 6 | CRITICAL |
| Naming violations | 5 | HIGH |
| Normalization violations | 7 | HIGH |
| Missing tables | 20+ | HIGH |
| Redundant tables | 5 | MEDIUM |
| Indexing issues | 4 | MEDIUM |
| JSON vs column violations | 2 | MEDIUM |
| Actor vs agent separation | 3 | HIGH |
| Constitutional compliance | 1 partial (N4) | HIGH |

---

## PROPOSED CORRECTED IDENTITY MODEL

```
lupo_agent_definitions (agent_id, slug, agent_key, name, layer, role, is_kernel, is_required,
                         status, department_id, learning_boundary, agent_class, version,
                         lineage_json, capabilities_json, system_prompt_path)
         |
         | agent_id references
         |
lupo_actors (actor_id PK, actor_name, slug, name, actor_type, agent_key, is_kernel,
              is_required, can_login, is_active, is_deleted, ...)
         |
         | actor_id references
         |
lupo_actor_auth_users (actor_id, auth_user_id, relationship_role, is_primary, routing_priority)
         |
         | auth_user_id references
         |
lupo_auth_users (auth_user_id, username, email, ...)
```

**Key principle:** agent_definitions is the template. actors is the runtime instance. auth_users is the human or external entity. These three layers do not collapse.

---

## PROPOSED CORRECTED RELATIONSHIP MODEL

```
Actor pairing:
  lupo_actors.actor_id → lupo_actor_pairing.actor_id (paired_actor_id, pairing_role, pairing_type)

Adversarial oversight:
  lupo_actors.actor_id → lupo_actor_relationships.actor_a_id
                       → lupo_actor_relationships.actor_b_id (relationship_type, authority_direction)

Department membership:
  lupo_actors.actor_id → lupo_actor_departments.actor_id
  NOT lupo_actors.department_id (remove this column)

Capability grants:
  lupo_agent_definitions.agent_id → lupo_agent_capabilities.agent_id (capability_key, domain)
  lupo_actors.actor_id → lupo_actor_capabilities.actor_id (capability_key, domain, scope)

Tool access:
  lupo_agent_definitions.agent_id → lupo_agent_tools.agent_id (tool_id, tool_key, constraints_json)

Faucet execution:
  lupo_actor_faucets.actor_id = executing actor (HEPHAESTUS = 102 by doctrine)
  lupo_actor_faucets.target_actor_id = the IDE or faucet source actor
```

---

## NEXT STEP

See: `schema_corrected_core.sql` for the corrected DDL of the core actor/agent tables.
See: `schema_corrected_missing.sql` for the new tables that must be added.

Reviewer: claude-code actor_id 102
Review completed: 20260406
