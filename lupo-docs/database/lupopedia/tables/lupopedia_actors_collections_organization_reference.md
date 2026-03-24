---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/lupopedia_actors_collections_organization_reference.md
  channel_id: 42
  actor_id: 102
  last_modified_utc: '20260315'
  artifact_type: schema_reference
  artifact_kind: documentation
  purpose: Canonical TOON structure reference for actor, collection, and organization
    tables with implementation doctrine constraints.
  traits:
  - canonical
  - schema_reference
  - toon_aligned
  - implementation_aware
  - v4.0.75
  tags:
  - database
  - toon
  - actors
  - collections
  - channels
  - federation
  - doctrine
  lupo_agent: cursor
  when_updated: '20260324174654'
lupopedia.edges:
  outbound_edges:
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: references
    weight: 1.0
  - to: lupo-database/lupopedia/toon/
    type: references
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/active/lupo_actors.md
    type: references
    weight: 0.95
  - to: lupo-docs/database/lupopedia/tables/active/lupo_collections.md
    type: references
    weight: 0.95
  - to: lupo-docs/database/lupopedia/tables/active/lupo_channels.md
    type: references
    weight: 0.95
  - to: lupo-docs/database/lupopedia/tables/active/lupo_sessions.md
    type: references
    weight: 0.9
  - to: lupo-docs/database/lupopedia/tables/active/lupo_registry.md
    type: references
    weight: 0.95
  - to: lupo-docs/doctrine/DATABASE_DOCTRINE.md
    type: references
    weight: 1.0
  - to: lupo-docs/doctrine/COLLECTIONS_DOCTRINE.md
    type: references
    weight: 0.95
  - to: lupo-docs/doctrine/FEDERATION_SCOPING_DOCTRINE.md
    type: references
    weight: 0.95
  - to: lupo-docs/doctrine/SESSION_DOCTRINE.md
    type: references
    weight: 0.9
  - to: lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md
    type: references
    weight: 0.95
  - to: lupo-docs/ACTOR_FORENSIC_LOGGING_AND_COLLECTIONS_INTELLIGENCE_PLAN.md
    type: references
    weight: 0.9
lupopedia.footer:
  last_verified: '20260315000000'
  last_verified_by: cursor
  orchestrator: cursor
  next_action:
  - Update this reference when install SQL or TOON schemas change in actor, collection,
    or organization domains.
  last_verified_by_actor_id: 102
---

# Lupopedia Actors, Collections, and Organization Reference

## Executive Summary

This document is the canonical cross-table reference for the core TOON structure across three domains:

- Actors: 8 tables for identity, auth/agent linkage, channel participation, and collection access.
- Collections: 6 tables for hierarchy, polymorphic membership, tab navigation, and tab paths.
- Organization: 4 tables for registry allocation, federation scope, department structure, and channel context.

Total covered: 18 tables.

This reference is implementation-oriented and doctrine-aligned. It complements per-table docs under `tables/active/` and does not replace TOON or install SQL as schema authority.

## Cross-Domain Relationship Map

```text
Organization
  (lupo_registry, lupo_federation_nodes, lupo_departments, lupo_channels)
        | scopes and allocates
        v
Actors <---------------------> Collections
(identity, membership,          (containers, tabs, map,
 roles, access)                  paths, links)
        \                         /
         \ actor_collection + channel/department/node scope
          \                     /
           ------ application-managed invariants ------
```

## Actor System

### Identity layers

- `lupo_actors`: Core actor identity and orchestration anchor.
- `lupo_auth_users`: Human login and authentication metadata.
- `lupo_agents`: Agent model and runtime metadata.

One operational identity can involve human, AI, or hybrid pairing patterns via actor relationships (not database-enforced foreign keys).

### Human, AI, and hybrid pairing

- Human orchestration identity is represented through actor + auth user surfaces.
- AI execution identity is represented through actor + agent surfaces.
- Hybrid orchestration is represented with pair-link semantics such as `paired_actor_id` on actor records.

### Actor relationship tables

- `lupo_actor_channels`: Actor to channel membership.
- `lupo_actor_channel_roles`: Channel-scoped actor permissions and role state.
- `lupo_actor_edges`: Actor-to-actor graph relationships (delegation/trust/semantic).
- `lupo_actor_collections`: Actor-to-collection access mapping.
- `lupo_actor_apps`: Actor-to-filesystem app path linkage.

### Channel membership and permissions

- Membership is handled in `lupo_actor_channels`.
- Role and permission state is handled in `lupo_actor_channel_roles`.
- Effective authorization is computed in application code, not by foreign key constraints.

### Actor collection access

- `lupo_actor_collections` is the join surface for collection access level and access-associated metadata.
- Access semantics must be interpreted by services in `app/Services/` and module-layer authorization logic.

### Actor app path linkage

- `lupo_actor_apps` maintains per-actor app path references for execution surfaces and tooling linkage.
- This remains doctrine-bound to explicit actor identity mappings and soft-delete behavior where applicable.

### Common mistakes to avoid

- Do not use AUTO_INCREMENT patterns for registry-allocated actor identity flows.
- Do not create actor records without evaluating required auth (`lupo_auth_users`) and/or agent (`lupo_agents`) linkage.
- Do not insert actor-channel membership without checking whether role rows are also required.

## Collections System

### Core six-table hierarchy

- `lupo_collections`
- `lupo_collection_map`
- `lupo_collection_links`
- `lupo_collection_tabs`
- `lupo_collection_tab_map`
- `lupo_collection_tab_paths`

### Polymorphic item mapping

- `lupo_collection_map` supports polymorphic mapping (`object_type` + `object_id`) so collections can include multiple entity kinds.
- `lupo_collection_tab_map` extends this pattern for tab-scoped items (`item_type` + `item_id`).

### Nested tabs

- `lupo_collection_tabs` supports hierarchy via parent relationships for multi-level tab structures.
- This enables collection-local navigation trees without introducing relational FK constraints.

### Tab path and breadcrumb behavior

- `lupo_collection_tab_paths` stores materialized path/depth-style records for traversal and breadcrumb rendering.
- Path rows are navigational accelerators and must be maintained by application logic.

### Scoping rules

Collections and tabs can be scoped by combinations of:

- Federation node
- Department
- Actor
- Channel

Scope is explicit in columns and interpreted at query/service layer.

### Why collections are considered complicated

Collections combine:

- Polymorphic mappings
- Hierarchical tabs
- Materialized tab paths
- Multi-scope placement (node/department/actor/channel)

Because integrity and semantics are application-managed, implementation must synchronize mappings, tab tree state, and path records consistently.

### Common mistakes to avoid

- Do not treat `object_type` or `item_type` as uncontrolled free-form values.
- Do not reparent or reorder tabs without rebuilding dependent `lupo_collection_tab_paths` rows.
- Do not write map rows without matching scope assumptions (node/department/actor/channel).

## Organizational Structure

### Registry (`lupo_registry`)

- Canonical allocation and reservation surface for registry-backed identifiers.
- Used to avoid ad hoc ID assignment in reserved domains.

### Federation nodes (`lupo_federation_nodes`)

- Top-level deployment and trust scope.
- Defines node context for actors, channels, collections, and departments.

### Departments (`lupo_departments`)

- Organizational partition within federation scope.
- Used by actor placement, channel defaults, and collection/tab scoping.

### Channels (`lupo_channels`)

- Conversation/work context container.
- Provides channel-level scope for actor participation and collection linkage.

### Scope interaction

Organization tables define the context in which actor and collection rows are interpreted. Federation and department boundaries are contextual constraints applied in code and query design.

### Common mistakes to avoid

- Do not insert actor-channel membership while skipping channel role evaluation.
- Do not run cross-node queries without explicit `federation_node_id` constraints.
- Do not treat registry-backed identifiers as local-only values outside federation context.

## Critical Doctrine Rules

### Reserved ID doctrine

- Do not assume casual auto-allocation for registry-backed entities.
- Resolve allocation through canonical registry flows and existing project registries.

### Primary key versus unique mapping

- `actor_name` is doctrinally the actor semantic primary identity key.
- `actor_id` is the unique numeric mapping used for relations, allocation, and orchestration linkage.

### No foreign keys

- Database-level foreign keys are forbidden by doctrine.
- Referential integrity is enforced by application code and migration discipline.

### Timestamp doctrine

- Use `BIGINT` timestamps in UTC `YYYYMMDDHHIISS`.
- Generate with application code (`gmdate('YmdHis')`-style), not database-generated timestamps.

## Rule Cross-References

Doctrine documents and root rules apply to this reference as follows. Rule IDs are declared in `lupo-rules/root/` and are stable for lookup.

| Section | Related Canonical Doctrine |
|---|---|
| Actor system | `lupo-rules/root/pk-reference-naming-doctrine.md`, `lupo-rules/root/reserved-id-doctrine.md`, `lupo-docs/doctrine/REGISTRY_DOCTRINE.md`, `lupo-docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md` |
| Collections | `lupo-docs/doctrine/COLLECTIONS_DOCTRINE.md`, `lupo-docs/doctrine/DATABASE_DOCTRINE.md` |
| Organization | `lupo-docs/doctrine/FEDERATION_SCOPING_DOCTRINE.md`, `lupo-docs/doctrine/REGISTRY_DOCTRINE.md`, `lupo-docs/doctrine/channels.md` |
| Sessions and auth boundaries | `lupo-docs/doctrine/SESSION_DOCTRINE.md`, `lupo-docs/doctrine/AUTHORIZATION_DOCTRINE.md` |
| Core database constraints | `lupo-docs/doctrine/DATABASE_DOCTRINE.md` |

### Rule ID Quick Reference

Rule IDs below are taken from `lupo-rules/root/` and are relevant to this reference. Use the root files as the source of truth.

| Domain / operation | Rule ID | Rule document |
|-------------------|---------|----------------|
| No foreign keys / no DB logic | DB001 | `lupo-rules/root/database-logic-prohibition-doctrine.md` |
| PK and reference column naming | DB004 | `lupo-rules/root/pk-reference-naming-doctrine.md` |
| Reserved ID (no AUTO_INCREMENT for registry tables) | DB006 | `lupo-rules/root/reserved-id-doctrine.md` |
| TOON / install SQL as schema authority | DB007 | `lupo-rules/root/toon-source-of-truth.md` |
| Agent identity and actor pairing | ACT001 | `lupo-rules/root/ide-agent-identity-actor-pairing-doctrine.md` |
| Channels, federation, offline session context | CTX001 | `lupo-rules/root/channels-federation-offline-session-doctrine.md` |

## Quick Reference Tables

### Actor tables (8)

| Table | Purpose |
|---|---|
| `lupo_actors` | Core actor identity |
| `lupo_auth_users` | Human auth metadata |
| `lupo_agents` | Agent runtime metadata |
| `lupo_actor_channels` | Actor-channel membership |
| `lupo_actor_channel_roles` | Channel-scoped actor roles |
| `lupo_actor_edges` | Actor graph relationships |
| `lupo_actor_collections` | Actor-collection access |
| `lupo_actor_apps` | Actor app path mapping |

### Collection tables (6)

| Table | Purpose |
|---|---|
| `lupo_collections` | Collection containers |
| `lupo_collection_map` | Polymorphic collection membership |
| `lupo_collection_links` | Collection link objects |
| `lupo_collection_tabs` | Nested collection tabs |
| `lupo_collection_tab_map` | Polymorphic tab membership |
| `lupo_collection_tab_paths` | Tab hierarchy paths/breadcrumbs |

### Organization tables (4)

| Table | Purpose |
|---|---|
| `lupo_registry` | Registry-backed ID allocation |
| `lupo_federation_nodes` | Federation/deployment scope |
| `lupo_departments` | Organizational partition |
| `lupo_channels` | Channel/work context |

## Implementation Implications

### Actor registration

- Registration flows must align `lupo_actors`, registry allocation, and optional auth/agent surfaces.
- Pairing semantics (`paired_actor_id`) must be explicit and validated in application code.

### Collection seeding

- Seed operations must create coherent rows across collection root, map/link rows, tabs, tab_map, and tab_paths where required.
- Scope columns must be intentional to avoid cross-node or cross-department leakage.

### Collection tabs

- Tab operations must keep hierarchy and path materialization consistent.
- Any reorder/reparent action must update dependent path records safely.

### Channel work

- Actor-channel membership and role assignment must be synchronized across `lupo_actor_channels` and `lupo_actor_channel_roles`.
- Channel docs and session logic must consume the same actor identity boundaries.

### Session alignment

- Session handling (for example `lupo_sessions`) must resolve actor identity through doctrinal actor keys and registry-safe numeric IDs.
- Session lifecycle and reconciliation should remain session-table concerns; actor/channel/collection tables provide scope context, not session event history.

### Federation scoping

- Query design should treat federation as first-order context.
- Department/channel/actor scopes refine results but should not bypass federation boundaries.

### Forensic logging boundaries

- Core identity/collection schema is not a complete telemetry substrate.
- Forensic logging should be implemented in dedicated logging tables/services, not by overloading actor/collection core tables.

## Safe Query Patterns

Use these as pattern references, not fixed application queries.

### Actor membership with channel role context

```sql
SELECT
  a.actor_id,
  a.actor_name,
  ac.channel_id,
  acr.role_key
FROM lupo_actors a
JOIN lupo_actor_channels ac
  ON a.actor_id = ac.actor_id
LEFT JOIN lupo_actor_channel_roles acr
  ON acr.actor_id = ac.actor_id
 AND acr.channel_id = ac.channel_id
 AND acr.is_deleted = 0
WHERE ac.channel_id = :channel_id
  AND a.is_deleted = 0
  AND ac.is_deleted = 0;
```

### Collection tab membership and path context

```sql
SELECT
  ct.collection_tab_id,
  ct.name AS tab_name,
  ctm.item_type,
  ctm.item_id,
  ctp.path,
  ctp.depth
FROM lupo_collection_tabs ct
JOIN lupo_collection_tab_map ctm
  ON ctm.collection_tab_id = ct.collection_tab_id
LEFT JOIN lupo_collection_tab_paths ctp
  ON ctp.collection_tab_id = ct.collection_tab_id
 AND ctp.collection_id = ct.collection_id
 AND ctp.is_deleted = 0
WHERE ct.collection_id = :collection_id
  AND ct.is_deleted = 0
  AND ctm.is_deleted = 0;
```

### Federation-scoped channel and collection query

```sql
SELECT
  ch.channel_id,
  ch.channel_key,
  c.collection_id,
  c.slug AS collection_slug
FROM lupo_channels ch
LEFT JOIN lupo_collections c
  ON c.channel_id = ch.channel_id
 AND c.federation_node_id = ch.federation_node_id
 AND c.is_deleted = 0
WHERE ch.federation_node_id = :federation_node_id
  AND ch.is_deleted = 0;
```

## Implementation Quick Reference

### Adding a new actor

- Allocate the numeric identity through the registry workflow for the target federation scope.
- Insert actor identity in `lupo_actors` using doctrinal primary/unique semantics.
- Insert corresponding auth and/or agent rows when required by actor type.
- Create actor-channel membership rows where needed.
- Insert actor-channel role rows where channel policy requires explicit roles.
- Add actor-collection access rows when collection access is required.

### Creating a new collection

- Insert base row in `lupo_collections` with explicit scope columns.
- Insert object membership rows in `lupo_collection_map` as needed.
- Insert rows in `lupo_collection_links` when link objects are needed.
- Create initial tab hierarchy in `lupo_collection_tabs`.
- Insert tab memberships in `lupo_collection_tab_map`.
- Generate or rebuild `lupo_collection_tab_paths` for navigable hierarchy.

## Cross-Table Integrity Notes

| Tables | Invariant | Enforcement Area |
|---|---|---|
| `lupo_actors` + `lupo_registry` | Actor IDs must align with registry allocation expectations for scoped identity workflows. | Registry allocation workflow and actor service logic |
| `lupo_actors` + `lupo_actor_channels` | Soft-deleted actors should not remain active channel participants. | Actor/channel membership services |
| `lupo_actor_channels` + `lupo_actor_channel_roles` | Membership and role state must not drift for channel-scoped authorization. | Channel membership/authorization services |
| `lupo_collection_tabs` + `lupo_collection_tab_paths` | Path rows must represent the current tab hierarchy. | Collection tab maintenance logic |
| `lupo_collections` + `lupo_collection_map` | Collection scope assumptions must match mapped object usage. | Collection mapping services |
| `lupo_channels` + `lupo_collections` | Cross-domain joins must preserve federation/node scope. | Query/repository layer |

## Performance Considerations

- `lupo_collection_tab_paths` is a navigation acceleration surface; stale path maintenance causes both correctness and performance regressions.
- `lupo_actor_edges` can grow quickly in dense actor graphs; keep edge queries aligned to indexed columns and scoped filters.
- `lupo_registry` lookups are on hot identity paths and should remain lightweight and index-aligned.
- Federation-scoped predicates should be applied early in joins to limit cross-node scan cost.

## TOON Regeneration and Documentation Sync

When schema changes touch these domains:

1. Update install SQL (`install_new_lupopedia.sql`) as DDL authority.
2. Regenerate TOON files (project workflow: `lupo-scripts/generate_toon_files.py`).
3. Verify generated TOON structure and doctrinal constraints.
4. Update this cross-domain reference when relationships or usage semantics changed.
5. Update affected `tables/active/*.md` docs for table-specific consistency.
6. Commit schema and documentation updates together.

## Validation Queries

Use these checks after schema or migration changes.

### Ensure actor_id uniqueness expectations hold

```sql
SELECT actor_id, COUNT(*) AS row_count
FROM lupo_actors
WHERE actor_id IS NOT NULL
GROUP BY actor_id
HAVING COUNT(*) > 1;
```

Expected: zero rows.

### Detect actor-channel rows referencing missing actors

```sql
SELECT ac.actor_channel_id, ac.actor_id
FROM lupo_actor_channels ac
LEFT JOIN lupo_actors a
  ON a.actor_id = ac.actor_id
 AND a.is_deleted = 0
WHERE ac.is_deleted = 0
  AND a.actor_name IS NULL;
```

Expected: zero rows.

### Detect collection tabs missing active path rows

```sql
SELECT ct.collection_tab_id, ct.collection_id
FROM lupo_collection_tabs ct
LEFT JOIN lupo_collection_tab_paths ctp
  ON ctp.collection_tab_id = ct.collection_tab_id
 AND ctp.collection_id = ct.collection_id
 AND ctp.is_deleted = 0
WHERE ct.is_deleted = 0
  AND ctp.collection_tab_path_id IS NULL;
```

Expected: zero rows for tabs that require navigation paths.

### Detect duplicate registry triples

```sql
SELECT entity_type, entity_index_id, federation_node_id, COUNT(*) AS row_count
FROM lupo_registry
WHERE is_deleted = 0
GROUP BY entity_type, entity_index_id, federation_node_id
HAVING COUNT(*) > 1;
```

Expected: zero rows.

## Relation To Current Work

### Forensic logging

This reference defines where actor, collection, and organization context should be read from when designing forensic logging, while keeping telemetry collection in dedicated logging architecture.

### Collections intelligence

Collections intelligence features (affinity grouping, access pattern grouping, referral-aware organization) should build on current collection map/tab surfaces without breaking doctrine or introducing schema-side logic.

### Seeded doctrine collection work

The seeded `doctrine` collection and related semantic navigation work should use these table responsibilities as stable anchors for data placement and retrieval patterns.

### Schema-safe implementation practices

- Treat install SQL as DDL authority.
- Treat TOON files as generated structural references.
- Keep docs aligned with both before implementation changes.
- Enforce reserved IDs, soft-delete defaults, and BIGINT timestamp doctrine in all schema-adjacent code.

## See Also

- `lupo-docs/database/lupopedia/SCHEMA_REGISTRY.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_actors.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_collections.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_channels.md`
- `lupo-docs/database/lupopedia/tables/active/lupo_sessions.md`
- `lupo-docs/doctrine/DATABASE_DOCTRINE.md`
- `lupo-docs/doctrine/COLLECTIONS_DOCTRINE.md`
- `lupo-docs/doctrine/FEDERATION_SCOPING_DOCTRINE.md`
- `lupo-docs/doctrine/SESSION_DOCTRINE.md`
- `lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md`
- `lupo-docs/ACTOR_FORENSIC_LOGGING_AND_COLLECTIONS_INTELLIGENCE_PLAN.md`
