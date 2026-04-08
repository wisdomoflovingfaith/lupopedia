---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260408111331"
  file_path_from_root: "lupo-docs/doctrine/TRUST_LADDER_REGISTRY.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/TRUST_LADDER_REGISTRY.md"
  last_modified_utc: "20260408111331"
  federation_node_id: 0
  channel_id: 42
  thread_id: "trust-ladder-registry"
  actor_id: 1
  actor_name: "WOLFIE"
  delegation_chain: "wolfie:root"
  artifact_type: doctrine
  artifact_kind: constitutional
  purpose: "Authoritative registry of tables participating in the Chronological Trust Ladder"
  tags:
    - doctrine
    - trust_ladder
    - registry
    - constitutional
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md"
      type: implements
      weight: 1.0
      reason: "Registry for trust ladder doctrine"
    - to: "lupo-docs/prd/38_memory_unification.md"
      type: references
      weight: 1.0
      reason: "Memory nodes implementation"
    - to: "lupo-docs/prd/41_install_seed_doctrine.md"
      type: references
      weight: 1.0
      reason: "Install seed vs runtime PK bands"
    - to: "lupo-docs/doctrine/RETENTION_POLICY.md"
      type: references
      weight: 1.0
      reason: "Staging retention"
lupopedia.footer:
  last_verified: "20260408111331"
  verified_by:
    identity_type: actor
    actor_id: 1
    agent_name_identity: "WOLFIE"
  orchestrator: "wolfie:root"
---

# file: Trust Ladder Registry — delegation: wolfie:root — web_path: http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/TRUST_LADDER_REGISTRY.md

# Trust Ladder Registry

Normative ladder rules: **`lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md`**. **Participates** values:

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
| System seeds | 0-999,999 | actor_id:1, channel_id:42 | `lupo-database/lupopedia/actors/registry.json` |

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
| `lupo_actor_memory` | `actor_memory_id` | 18-digit timestamp | **deprecated** | **child (legacy)** | Superseded by **`lupo_memory_nodes`** in 4.0.96; retained for legacy data only |

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
python lupo-scripts/validate_trust_ladder_registry.py
```

---

**Status:** ACTIVE
