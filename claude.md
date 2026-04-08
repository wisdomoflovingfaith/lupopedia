# Claude Code — Lupopedia Actor Overview

Claude Code is a distinct AI actor in Lupopedia, separate from Cursor (actor_id 102) and all other IDE or agent facets. This file documents the canonical identity, workspace, memory system, and department model for Claude Code.

## Key Doctrines

### Chronological Trust Ladder

See `lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md`.

- **Tier 0 (Seed)**: PK below install/seed policy band (e.g. low reserved ids), immutable, installed with system
- **Tier 1 (Living Canonical)**: PK embedded calendar year **1000–1999**, mutable, verified truth
- **Tier 2 (Staging)**: PK embedded calendar year **2000–2099** from **`IdGenerator`**, temporary, merged then soft-deleted

**Conversion (18-digit staging):** `toCanonicalId($stagingId)` = subtract **1000** from the embedded **year** when year ≥ 2000 (see doctrine).

**Low registry `actor_id` (install seed, not `IdGenerator`):** Use **`seedActorToCanonicalId($seed)`** = **`100000000000000000 + $seed`** for paired living canonical rows — see **`lupo-docs/prd/41_install_seed_doctrine.md`** §2.3 and **`lupo-docs/prd/15_actors.md`** (seed-to-canonical mapping). Do **not** use **`toCanonicalId(116)`**; it leaves **`116`** unchanged.

---

## Actor Model (3-Layer Identity)

Lupopedia uses a three-layer identity model that differs from typical agent systems:

| Layer | What | Storage | Can Learn? | Example |
|-------|------|---------|------------|---------|
| **Auth User** | Human or system account | `lupo_auth_users` | N/A | `root@lupopedia.local` (id=1) |
| **Actor** | Runtime persona that does work | `lupo_actors` + workspace | ✅ Yes | Claude Code (seed **116** / canonical **`100000000000000116`** when instantiated) |
| **Agent** | Immutable template (filesystem) | `lupo-agents/{agent_key}/` | ❌ No | `lupo-agents/claude/` |

### How They Relate

```
Auth User (Human/System)
    │
    ├── belongs to Department(s)
    │
    ▼
Department (e.g., "Root", "Sales", "Engineering")
    │
    ├── has many Actors assigned
    │
    ▼
Actor (Persona)
    │
    ├── extends an Agent template
    ├── learns from ALL Auth Users in its department
    └── memory diverges from the Agent over time
```

### Access Rules

| Environment | Auth User | Department | Can Act As |
|-------------|-----------|------------|------------|
| **Web UI** | Normal user | User's departments | Actors in intersecting departments |
| **Terminal / CLI** | Root (id=1) | 0 (Root) | ANY actor (bypass) |
| **IDE Agents** | Root (id=1) | 0 (Root) | ANY actor (bypass) |

**Claude Code (you)** always operates as root auth_user (id=1) in department 0. This is why you can run commands targeting any actor:

```bash
php lupo-bin/lupo.php memory add --actor 1 --type observation ...
php lupo-bin/lupo.php memory add --actor 116 --type observation ...
```

Post-install runtime memory and edges should use **`100000000000000116`** when the living canonical row exists (**PRD 15**, **PRD 41** §2.3).

---

## Identity for Claude Code

| Field | Value |
|-------|-------|
| **actor_id (install seed / dev reference)** | **116** — immutable row from seed SQL / registry; **`lupo-actors/116/`** hub path |
| **actor_id (living canonical, post-install)** | **`100000000000000116`** — **`seedActorToCanonicalId(116)`**; use for runtime graph once instantiated |
| **actor_code** | CLAUDE_CODE |
| **actor_name** | Claude Code |
| **type** | agent (AI) |
| **status** | active |
| **registry** | `lupo-database/lupopedia/actors/registry.json` |

### Auth User

- Claude Code **does not have** its own `lupo_auth_users` row
- All CLI/IDE operations assume **root auth_user_id = 1**
- This aligns with actor_id 1 (WOLFIE) and department 0 (Root)

**Do not create** separate auth_user rows for IDE products. Attribute automation to the facet `actor_id` from the registry.

---

## Purpose

Claude Code represents the Anthropic Claude AI persona within Lupopedia. Used for:
- AI code review, planning, and reasoning tasks
- Channel and thread participation as a distinct agent
- Memory operations via CLI (`php lupo-bin/lupo.php memory ...`)
- Separation of identity and authority from Cursor (102), Antigravity (103), etc.

---

## Workspace Structure

```
lupo-actors/116/
├── identity.json       # Canonical identity metadata
├── boundaries.json     # Operational and interaction rules
└── preferences.json    # Actor-specific preferences
```

**Note:** No files use the actor name or code in the directory name; only the numeric actor_id is used.

---

## Memory System (PRD 38)

Lupopedia uses a **unified memory graph** with database as source of truth and filesystem as read-only mirror.

### Memory Location

Claude Code's memory is stored in:

| Storage | Path | Purpose |
|---------|------|---------|
| **Database (source of truth)** | `lupo_memory_nodes` table | Queryable graph with edges |
| **Filesystem (read-only mirror)** | `lupo-memory/YYYY/MM/{slug}.json` | IDE browsing by date |

### Find Claude Code's Root Memory

```sql
-- Query database for root memory node
SELECT * FROM lupo_memory_nodes 
WHERE owner_actor_id = 116 
  AND memory_type = 'root' 
  AND is_deleted = 0;
```

### Filesystem Path

The mirror file path is derived from `created_ymdhis`:

```
lupo-memory/{YYYY}/{MM}/{created_ymdhis}_{owner_type}_{owner_id}_{memory_type}_{memory_key}.json
```

Example:
```
lupo-memory/2026/04/20260408_120000_actor_116_root_actor_root_context.json
```

### Memory Commands (CLI)

```bash
# Add memory
php lupo-bin/lupo.php memory add --actor 116 --type observation --key "review:prd38" --value '{"status":"approved"}'

# List memory
php lupo-bin/lupo.php memory list --actor 116 --type observation

# Get memory by ID
php lupo-bin/lupo.php memory get --memory-id 1234567890123456

# Export all memory for Claude Code
php lupo-bin/lupo.php memory export --actor 116 --output-dir ./claude-memory-backup
```

### Memory Divergence (Actor vs Agent)

Because Actors learn from their department, you can compare Claude Code's learned memory against its base Agent template:

```sql
-- Agent baseline (immutable)
SELECT memory_value FROM lupo_memory_nodes 
WHERE owner_type = 'agent' AND owner_id = :agent_id

-- Actor learned behavior (may differ)
SELECT memory_value FROM lupo_memory_nodes 
WHERE owner_type = 'actor' AND owner_id = 116

-- Find divergences
SELECT * FROM lupo_memory_edges 
WHERE edge_type = 'diverges_from'
  AND to_memory_id IN (SELECT memory_id FROM lupo_memory_nodes WHERE owner_actor_id = 116)
```

---

## Registry and Documentation

Claude Code is registered in:
- `lupo-database/lupopedia/actors/registry.json` (actor_id 116)

Documented in:
- `lupo-docs/prd/01_core_identity.md` — Core identity, session logic
- `lupo-docs/prd/05_auth_user_actor_agent_transformation.md` — Department act-as model
- `lupo-docs/prd/15_actors.md` — Actor lifecycle, workspace rules
- `lupo-docs/prd/38_memory_unification.md` — Unified memory graph

Not conflated with Cursor (actor_id 102) or any other actor.

---

## Actor Creation Rule

Actor_id 116 was assigned according to the canonical registry and PRD rules. No actor_id was guessed or invented; the next available was used per documentation.

**ID ranges:**
| Range | Type | Workspace |
|-------|------|-----------|
| 1-2025 | System Actors | `lupo-actors/{actor_id}/` |
| 2026+ | Runtime Actors | `lupo-actors/YYYY/MM/{actor_id}/` |

Claude Code (116) falls in the system actor range.

---

## See Also

- [lupo-database/lupopedia/actors/registry.json](lupo-database/lupopedia/actors/registry.json)
- [lupo-docs/prd/01_core_identity.md](lupo-docs/prd/01_core_identity.md)
- [lupo-docs/prd/05_auth_user_actor_agent_transformation.md](lupo-docs/prd/05_auth_user_actor_agent_transformation.md)
- [lupo-docs/prd/15_actors.md](lupo-docs/prd/15_actors.md)
- [lupo-docs/prd/38_memory_unification.md](lupo-docs/prd/38_memory_unification.md)
- [AGENTS.md](AGENTS.md)
- [README.md](README.md) — Actor Model: Why It's Different
```

---

## Summary of Changes to `claude.md`

| Section | Change |
|---------|--------|
| **Actor Model (3-Layer Identity)** | Added — explains Auth User → Department → Actor relationship |
| **Access Rules table** | Added — clarifies web vs terminal vs IDE permissions |
| **Auth User clarification** | Explicit: Claude uses root auth_user_id=1, no separate auth_user row |
| **Memory System** | Updated to PRD 38 unified model (DB source of truth, filesystem mirror) |
| **Memory Commands** | Added CLI examples using `php lupo-bin/lupo.php memory ...` |
| **Memory Divergence** | Added — explains Actor vs Agent memory comparison |
| **Edges** | Added cross-references to PRD 05, 15, 38 |
| **Root memory node location** | Updated from file-only to DB + export mirror |

---

Do you want me to also update `AGENTS.md` and the root `README.md` with the same actor model explanation?