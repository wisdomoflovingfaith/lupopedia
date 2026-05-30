---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/decisions/pseudocode/38_memory_unification_constitutionpseudo.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/decisions/pseudocode/38_memory_unification_constitutionpseudo.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: pseudocode
  channel_key: null
  federation_node_id: 0
  thread_key: pseudocode-38-memory-unification
  lupopedia.schema: documentation
  prd_cluster: null
  title: ''
  summary: ''
---
# PRD 38: PRD 38: Memory Unification â€” Constitutional Graph Compliance (Revised) â€” Shorthand

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## One-Line Summary

Unify memory graph with database as source of truth + filesystem export for IDE/Claude access by date

## Core Rules

- All writes go to database first (`lupo_memory_nodes`, `lupo_memory_edges`)
- An export service mirrors DB rows to `memory/YYYY/MM/{slug}.json` (slug derived in PHP; see section 5)
- IDE/Claude reads from filesystem (same experience as today)
- Graph queries, KAIROS consolidation, and edge traversal use the database
- `IdGenerator::generate()` â†’ **`202604081200001234`** (staging-shaped).
- `toCanonicalId('202604081200001234')` â†’ **`102604081200001234`** (living canonical-shaped).
- memory_node_id = IdGenerator::generate()
- created_ymdhis = (int) IdGenerator::extractTimestamp(memory_node_id)
- updated_ymdhis = gmdate('YmdHis') on update (or equal to created_ymdhis on insert)

## Forbidden Patterns

- âŒ NO living
- âŒ NO DB
- âŒ NO `memory_slug`
- âŒ NO longer
- âŒ NO separate

## Required Patterns

- âœ… Queryable graph
- âœ… Filesystem access
- âœ… Schema present |
- âœ… Migration runs successfully |
- âœ… Files match original content |
- âœ… MUST be preserved
- âœ… MUST go to the database first; the export service synchronizes to disk
- âœ… MUST match **`install_new_lupopedia

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
| **Agent** | `agents/{agent_key}/` | No | Immutable template, filesystem |

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

**Reserved / band notes (verify registry + PRD 00 Â§3.2.1):**
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
| **Install / seed** | Low id **1â€“2025** (not timestamp-shaped) | Highest (canonical) | No |
| **Living canonical** | **18-digit** id, year **1000â€“1999** (e.g. **1026** after archive or merge) | High â€” best current knowledge | **Yes** (**UPDATE** as new evidence arrives) |
| **Staging / runtime** | **18-digit** id, embedded year **2000â€“2099** (raw **`IdGenerator`**) | Low â€” temporary | Yes; merged then **soft-deleted** (or never inserted if **`toCanonicalId`** pre-persist) |

**Id rule:** **`toCanonicalId(IdGenerator::generate())`** â†’ living canonical (embedded year **1000â€“1999**). See **PRD 38 Â§4.2.1**.

**Consolidation:** staging â†’ if no canonical, **promote** via **`toCanonicalId($stagingId)`** or fresh generator + transform; if canonical exists, **UPDATE** canonical + edges â†’ soft-delete staging (**PRD 38 Â§4.2**, **PRD 00 Â§3.7**). **Query priority:** living canonical â†’ staging â†’ seed for actor-specific â€œbestâ€ row.

## Long-Term Archiving (Option B)

| Era | Year in id (first 4 digits) | Export path |
|-----|------------------------------|-------------|
| **Runtime / staging** | **2000â€“2099** | `memory/{YYYY}/MM/` |
| **Living canonical / archive** | **1000â€“1999** | `memory/{YYYY}/MM/` |
| **Seed / pre-history** | Low ids / `created_ymdhis = 0` | `memory/1970/01/` |

**Rule:** **`toCanonicalId` / `toLongTermId`** â€” subtract **1000** from embedded year when **â‰¥ 2000**. Keep **`created_ymdhis`** as the **14-digit prefix** of the new PK. Link original â†’ archived with **`archived_to`** (Â§8); distinguish from merge with **`consolidated_into`**.

**CLI (PRD 24 Â§5.8â€“5.9):**

    php bin/lupo.php memory archive --memory-id <runtime_id>
    php bin/lupo.php memory archive --actor <id> --older-than <days> [--dry-run]
    php bin/lupo.php memory restore --memory-id <archived_id>


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
*Auto-generated by `scripts/generate_prd_shorthands.py`*
*Source: `docs/prd/38_memory_unification.md`*
*Last sync: 20260408013734*
