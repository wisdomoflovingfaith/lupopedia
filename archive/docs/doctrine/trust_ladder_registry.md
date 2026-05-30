---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/TRUST_LADDER_REGISTRY.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/doctrine/TRUST_LADDER_REGISTRY.md"
  status: ""
  when_updated: "20260411083734"
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: constitutional
  channel_key: null
  federation_node_id: 0
  thread_id: "trust-ladder-registry"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: Trust Ladder Registry — delegation: wolfie:root — web_path: http://www.lupopedia.com/lupopedia/docs/doctrine/TRUST_LADDER_REGISTRY.md

# Trust Ladder Registry

## Canonical Year Offset Rule for Trust Ladder PKs (Normative)

All canonical (long-term, merged, or archived) `memory_node_id` values and other full ladder PKs **MUST** encode the year as (calendar year – 1000) in the first four digits. This offset is required for all high-trust, living canonical, and archived ids, and is enforced by all trust ladder validators and migration scripts.

**Rationale:**
- The offset (calendar year – 1000) creates a distinct, lexicographically sortable band for high-trust, long-term ids (1000–1999), separate from runtime/staging ids (2000–2099).
- This prevents accidental mixing of staging and canonical ids, supports deterministic migration, and enables strict validation of trust ladder and memory graph integrity.
- Numeric banding is not a substitute for explicit trust semantics, but is a required convention for all canonical ids in trust ladder operations.

**Validation and migration requirements:**
- All trust ladder PKs and memory graph edges **MUST** enforce the offset rule for canonical ids.
- Validators **MUST** reject any canonical or archived id whose year is not in 1000–1999, or whose offset does not match the original runtime year minus 1000.
- Migration scripts **MUST** backfill or repair ids to conform to this rule if legacy data is found.
- Query helpers **MUST** use the offset band to distinguish canonical from staging ids, but **MUST NOT** rely on numeric banding alone for trust semantics (see PRD 43).

**See also:** PRD 16 §8.1 (header/memory_key year encoding), PRD 38 §8.1 (memory unification), PRD 43 (trust ladder PKs), PRD 51 (memory graph as source of truth), and all trust ladder migration scripts.

## PK offset validation

Every write to a ladder-participating table **MUST** pass **`IdGenerator::validateTrustLadderPk($pk, $context, $throw)`** (see **CHRONOLOGICAL_TRUST_LADDER.md** §2.2). Callers **SHOULD** supply table/column context (e.g. **`memory_nodes.memory_node_id`**) so **seed-only** ids can be checked against registries.

**Optional year-tier check (documentation / CI helper):** `scripts/validate_trust_ladder_pk.php` compares **18-digit** embedded years to the operator-supplied **calendar year** (default **UTC** via **`gmdate('Y')`**) for rows that are already in the **canonical** band (**1000–1999**) vs **staging** band (**2000–2099**). It complements **`IdGenerator::validateTrustLadderPk()`**; it does not replace registry-aware seed checks.

**Backfill / consolidation:** Prefer **`MemoryPromotionService`** ( **`promoted_to`** edges ) for staging→canonical moves. A CLI wrapper lives at **`scripts/backfill_canonical_offsets.php`** (**`--dry-run`** default; **`--apply`** runs promotion per row). Idempotency: **`MemoryPromotionService`** refuses duplicate work when a **`promoted_to`** edge already exists.

**Filesystem mirrors:** **`scripts/validate_memory_key_years.php`** scans **`memory/**/*.toon`** paths for **`trust_tier`** + year segment alignment (**PRD 16 §8.1**).

Normative ladder rules: **`docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md`**. **Participates** values:

- **`full`** — Full ladder (seed + staging + living canonical); **`validateTrustLadderPk`** on PK writes; canonical promotion via **`toCanonicalIdSafe`** where applicable.
- **`generator_staging`** — **`IdGenerator`** 18-digit PK; staging semantics; not necessarily merge/canonical in product code yet; still validate PK shape per path.
- **`seed_only`** — Low fixed / registry ids; not 18-digit ladder shape; see **PRD 41** / **`seedActorToCanonicalId`** for actors.
- **`not_ladder`** — Timestamp-shaped id without ladder tier semantics for that table’s PK alone.

## Archetype declaration contract (single source of truth)

Every ladder-participating table must declare one and only one archetype:

- `parent`
- `child`
- `system`

Documentation declaration lives in this file and must be mirrored in runtime registry/config cache.

Example declaration format:

```markdown
### Table: lupo_memory_nodes
archetype: parent
seed_required: true
canonical_lineage_edge: canonical_instance_of
promotion_target: canonical
```

Runtime usage pattern (illustrative):

```php
$tableArchetype = TrustLadderRegistry::getArchetype('lupo_memory_nodes');
```

Numeric seed bands below are **documentation pointers** — **PRD 41**, **PRD 00 §3.2.1**, and **registry.json** are authoritative.

## Seed ID Ranges by Category

| Category | Range | Example | Registration File |
|----------|-------|---------|-------------------|
| System seeds | 0-999,999 | actor_id:1, channel_id:42 | `database/lupopedia/actors/registry.json` |

**Rule:** Any ID in `0-999,999` that participates in ladder semantics MUST appear in one of the above registries.

## Active — full ladder (18-digit timestamp-shaped PKs)

| Table | PK column | PK shape | Participates | Entity archetype | Notes |
|-------|-----------|----------|--------------|------------------|-------|
| `lupo_memory_nodes` | `memory_node_id` | 18-digit timestamp | **full** | **parent** | Parent-anchor lineage is required for seed-linked rows; child-like flows must be explicitly modeled and reviewed |
| `lupo_memory_edges` | `memory_edge_id` | 18-digit timestamp | **full** | **system** | Typed edge layer supporting both parent and child records |

## Active — generator staging (not full merge ladder in all code paths)

| Table | PK column | PK shape | Participates | Entity archetype | Notes |
|-------|-----------|----------|--------------|------------------|-------|
| `lupo_dialog_messages` | `dialog_message_id` | 18-digit timestamp | **generator_staging** | **child** | Chat events; validate **`validateFormat`** / **`validateTrustLadderPk`** per insert path |
| `lupo_edges` | `edge_id` | 18-digit timestamp | **generator_staging** | **system** | Trust follows endpoint objects |

## Seed-only (exempt short / low PK band; not 18-digit ladder shape)

| Table | PK column | Seed range (illustrative) | Participates | Entity archetype | Notes |
|-------|-----------|---------------------------|--------------|------------------|-------|
| `lupo_actors` | `actor_id` | per registry / PRD 15 | **seed_only** | **parent** | **`seedActorToCanonicalId`** for deterministic canonical actor rows |
| `lupo_agent_definitions` | `agent_id` | per seed | **seed_only** | **system** | Replaces legacy monolithic agents table (install SQL §3) |
| `lupo_departments` | `department_id` | e.g. 0–1 typical | **seed_only** | **parent** | |
| `lupo_channels` | `channel_id` | per channel registry | **seed_only** | **parent** | |
| `lupo_auth_users` | `auth_user_id` | per PRD 01 | **seed_only** | **parent** | Root id doctrine in PRD 01 |
| `lupo_permissions` | `permission_id` | per seed | **seed_only** | **system** | |
| `lupo_rules` | `rule_id` | per seed | **seed_only** | **system** | |

## Adding or changing a table

1. Confirm PK strategy in **`install_new_lupopedia.sql`** and TOONs.
2. Update this file (correct **Participates** and **Entity archetype** columns).
3. Ensure INSERT/UPDATE paths use **`validateTrustLadderPk`** / **`validateFormat`** as required by **CHRONOLOGICAL_TRUST_LADDER.md** §2.2.
4. For canonical promotion, use **`toCanonicalIdSafe`** when inserting canonical rows.
5. Document in the relevant PRD.
6. Migrations that change participation or archetype: set **`trust_ladder_impacting: true`** in migration metadata and re-run validation.

## Validation

```bash
python scripts/validate_trust_ladder_registry.py
```

---

**Status:** ACTIVE
