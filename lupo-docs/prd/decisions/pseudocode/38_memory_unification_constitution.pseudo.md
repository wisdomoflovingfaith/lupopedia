---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  file_path_from_root: "lupo-docs/prd/decisions/pseudocode/38_memory_unification_constitution.pseudo.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/decisions/pseudocode/38_memory_unification_constitution.pseudo.md"
  when_updated: "20260408013734"
  last_modified_utc: "20260408013734"
  federation_node_id: 0
  channel_id: 42
  thread_id: "pseudocode-38-memory-unification"
  author:
    type: "actor"
    id: 116
    name: "CLAUDE"
  actor_id: 116
  actor_name: "CLAUDE"
  delegation_chain: "claude:shorthand"
  artifact_type: "documentation"
  artifact_kind: "pseudocode"
  purpose: "Token-efficient shorthand for PRD 38 — PRD 38: Memory Unification — Constitutional Graph Compliance (Revised)"
  tags:
    - pseudocode
    - constitution
    - shorthand
    - "prd-38"
lupopedia.edges:
  outbound_edges:
    - to: "../38_memory_unification.md"
      type: summarizes
      weight: 1.0
      reason: "Shorthand digest of canonical PRD"
lupopedia.footer:
  last_verified: "20260408013734"
  verified_by:
    actor_id: 116
    agent_name_identity: "Claude Code"
  orchestrator: "claude:shorthand"
---
# PRD 38: PRD 38: Memory Unification — Constitutional Graph Compliance (Revised) — Shorthand

## One-Line Summary

Unify memory graph with database as source of truth + filesystem export for IDE/Claude access by date

## Core Rules

- All writes go to database first (`lupo_memory_nodes`, `lupo_memory_edges`)
- An export service mirrors DB rows to `lupo-memory/YYYY/MM/{slug}.json` (slug derived in PHP; see section 5)
- IDE/Claude reads from filesystem (same experience as today)
- Graph queries, KAIROS consolidation, and edge traversal use the database
- `IdGenerator::generate()` → **`202604081200001234`** (staging-shaped).
- `toCanonicalId('202604081200001234')` → **`102604081200001234`** (living canonical-shaped).
- memory_node_id = IdGenerator::generate()
- created_ymdhis = (int) IdGenerator::extractTimestamp(memory_node_id)
- updated_ymdhis = gmdate('YmdHis') on update (or equal to created_ymdhis on insert)

## Forbidden Patterns

- ❌ NO living
- ❌ NO DB
- ❌ NO `memory_slug`
- ❌ NO longer
- ❌ NO separate

## Required Patterns

- ✅ Queryable graph
- ✅ Filesystem access
- ✅ Schema present |
- ✅ Migration runs successfully |
- ✅ Files match original content |
- ✅ MUST be preserved
- ✅ MUST go to the database first; the export service synchronizes to disk
- ✅ MUST match **`install_new_lupopedia

## Edge Types

| Edge | Direction | Meaning |
|------|-----------|---------|
| `VARCHAR` | unidirectional | Defined in PRD |
| `IN` | unidirectional | Defined in PRD |

## Actor Model (3-Layer Identity)

| Layer | Storage | Can Learn? | Scope |
|-------|---------|------------|-------|
| **Auth User** | `lupo_auth_users` | N/A | Human account, belongs to departments |
| **Actor** | `lupo_actors` + workspace | Yes | Runtime persona, department-scoped, shared |
| **Agent** | `lupo-agents/{agent_key}/` | No | Immutable template, filesystem |

### Rules

- Actors belong to departments (`lupo_actor_departments`)
- Auth users belong to departments (`lupo_auth_user_departments`)
- User can act as Actor **only if** departments intersect
- Root department (0) has full access
- Terminal/IDE agents: resolve `auth_user_id` from session/seed (do not hardcode)
- Memory is polymorphic: `owner_type = 'actor'` (learned) OR `'agent'` (baseline)


## Seed Data vs Runtime Records

| Record Type | PK Format | `created_ymdhis` | Origin |
|-------------|-----------|------------------|--------|
| **Seed** (install) | Fixed low ids per registry/install | 0 or install UTC | `install_new_lupopedia.sql` / seed |
| **Runtime** | `IdGenerator::generate()` | 14-digit prefix of PK | Application code |

**Reserved / band notes (verify registry + PRD 00 §3.2.1):**
- `lupo_actors`: < 2026 vs runtime (see PRD 01 / registry)
- `lupo_agents`: per registry / PRD 07
- `lupo_departments`: 0-1 typical
- `lupo_channels`: per channel registry
- `lupo_auth_users`: per PRD 01 / seed (root id per install)
- `lupo_memory_nodes`: per PRD 38
- `lupo_edges`: IdGenerator at runtime
- `lupo_permissions`: per seed
- `lupo_rules`: per seed

When `created_ymdhis = 0`, memory export may use `1970/01/` as documented pre-history path (see PRD 38 / MemoryExportService).

## Memory trust tiers (Chronological Trust Ladder pattern)

| Tier | PK / year signal | Trust | Can modify after commit? |
|------|------------------|-------|---------------------------|
| **Install / seed** | Low id **1–2025** (not timestamp-shaped) | Highest (canonical) | No |
| **Living canonical** | **18-digit** id, year **1000–1999** (e.g. **1026** after archive or merge) | High — best current knowledge | **Yes** (**UPDATE** as new evidence arrives) |
| **Staging / runtime** | **18-digit** id, embedded year **2000–2099** (raw **`IdGenerator`**) | Low — temporary | Yes; merged then **soft-deleted** (or never inserted if **`toCanonicalId`** pre-persist) |

**Id rule:** **`toCanonicalId(IdGenerator::generate())`** → living canonical (embedded year **1000–1999**). See **PRD 38 §4.2.1**.

**Consolidation:** staging → if no canonical, **promote** via **`toCanonicalId($stagingId)`** or fresh generator + transform; if canonical exists, **UPDATE** canonical + edges → soft-delete staging (**PRD 38 §4.2**, **PRD 00 §3.7**). **Query priority:** living canonical → staging → seed for actor-specific “best” row.

## Long-Term Archiving (Option B)

| Era | Year in id (first 4 digits) | Export path |
|-----|------------------------------|-------------|
| **Runtime / staging** | **2000–2099** | `lupo-memory/{YYYY}/MM/` |
| **Living canonical / archive** | **1000–1999** | `lupo-memory/{YYYY}/MM/` |
| **Seed / pre-history** | Low ids / `created_ymdhis = 0` | `lupo-memory/1970/01/` |

**Rule:** **`toCanonicalId` / `toLongTermId`** — subtract **1000** from embedded year when **≥ 2000**. Keep **`created_ymdhis`** as the **14-digit prefix** of the new PK. Link original → archived with **`archived_to`** (§8); distinguish from merge with **`consolidated_into`**.

**CLI (PRD 24 §5.8–5.9):**

    php lupo-bin/lupo.php memory archive --memory-id <runtime_id>
    php lupo-bin/lupo.php memory archive --actor <id> --older-than <days> [--dry-run]
    php lupo-bin/lupo.php memory restore --memory-id <archived_id>


## Constitutional Cross-References

- See PRD 00 for root rules
- See PRD 05 for auth/actor transformation
- See PRD 15 for actor lifecycle
- See PRD 25 for departments

## Token-Efficient Checklist

- [ ] Read full PRD for complete context
- [ ] Apply core rules above
- [ ] Check forbidden patterns
- [ ] Verify required patterns
- [ ] Cross-reference with related PRDs

---
*Auto-generated by `lupo-scripts/generate_prd_shorthands.py`*
*Source: `lupo-docs/prd/38_memory_unification.md`*
*Last sync: 20260408013734*
